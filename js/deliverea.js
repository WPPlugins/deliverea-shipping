/**
 * This class handles all AJAX requests logic needed for the module
 */
var deliverea = {
    delivereaApiMapping: DelivereaApiMappingsFactory(),
    shipmentsQueue: $({}),
    schemaData: [],
    selectedPickupPoint: null,
    currentOrder: null,
    /**
     * @returns {boolean}
     */
    usesCollection: function () {
        var availableMethods = deliverea.schemaData.available_methods;
        return availableMethods.hasOwnProperty('new-collection');
    },
    /**
     * @returns {boolean}
     */
    usesGetShipmentLabel: function () {
        var availableMethods = deliverea.schemaData.available_methods;
        return availableMethods.hasOwnProperty('get-shipment-label');
    },
    /**
     * @returns {*}
     */
    synchronizeServices: function () {
        return $.ajax({
            type: 'POST',
            dataType: 'json',
            url: "/wp-admin/admin-ajax.php",
            data: {
                action: 'deliverea',
                method: 'synchronize-services'
            }
        });
    },
    /**
     * @param data
     * @returns {*}
     */
    newPickupPoint: function (data) {
        return $.ajax({
            type: 'POST',
            dataType: 'json',
            url: "/wp-admin/admin-ajax.php",
            data: {
                action: 'deliverea',
                method: 'new-pickup-point',
                alias: data.alias,
                phone: data.phone,
                email: data.email,
                attn: data.attn,
                address: data.address,
                city: data.city,
                zip_code: data.zip_code,
                country: data.country,
                observations: data.observations
            }
        });
    },
    /**
     * @param id
     * @returns {*}
     */
    removePickupPoint: function (id) {
        return $.ajax({
            type: 'POST',
            dataType: 'json',
            url: "/wp-admin/admin-ajax.php",
            data: {
                action: 'deliverea',
                method: 'remove-pickup-point',
                id_pickup_point: id
            }
        });
    },
    /**
     * @returns {*}
     */
    getClientCarriers: function () {
        return $.ajax({
            type: 'GET',
            dataType: 'json',
            url: "/wp-admin/admin-ajax.php",
            data: {
                action: 'deliverea',
                method: 'get-client-carriers'
            }
        });
    },
    /**
     * @returns {*}
     */
    getClientServices: function () {
        if (!this.delivereaApiMapping.carrierCode()) {
            var deferred = $.Deferred();
            deferred.reject("Invalid data");
            return deferred.promise();
        }

        return $.ajax({
            type: 'GET',
            dataType: 'json',
            url: "/wp-admin/admin-ajax.php",
            data: {
                action: 'deliverea',
                method: 'get-client-services',
                carrier_code: this.delivereaApiMapping.carrierCode()
            }
        });
    },
    /**
     * @returns {*}
     */
    getServiceInfo: function () {
        return $.ajax({
            type: 'GET',
            dataType: 'json',
            url: "/wp-admin/admin-ajax.php",
            data: {
                action: 'deliverea',
                method: 'get-service-info',
                from_country_code: this.delivereaApiMapping.fromCountryCode(),
                from_zip_code: this.delivereaApiMapping.fromZipCode(),
                to_country_code: this.delivereaApiMapping.toCountryCode(),
                to_zip_code: this.delivereaApiMapping.toZipCode(),
                carrier_code: this.delivereaApiMapping.carrierCode(),
                service_code: this.delivereaApiMapping.serviceCode()
            },
            success: function (data) {
                deliverea.schemaData = data;
            }
        });
    },
    /**
     * @returns {*}
     */
    getCollectionCutoffHour: function () {
        if (!deliverea.selectedPickupPoint) {
            var deferred = $.Deferred();
            deferred.reject("Invalid data");
            return deferred.promise();
        }

        return deliverea.doRequest('get-collection-cutoff-hour', 'GET');
    },
    /**
     * @returns {*}
     */
    newShipment: function () {
        return deliverea.doRequest('new-shipment', 'POST', {
            uses_collection: deliverea.usesCollection() ? 1 : 0
        });
    },
    /**
     * @returns {*}
     */
    newCollection: function () {
        return deliverea.doRequest('new-collection', 'POST');
    },
    /**
     * @param shippingDlvrRef
     * @returns {*}
     */
    getShipmentLabel: function (shippingDlvrRef) {
        return $.ajax({
            type: 'GET',
            dataType: 'json',
            url: "/wp-admin/admin-ajax.php",
            data: {
                action: 'deliverea',
                method: 'get-shipment-label',
                shipping_dlvr_ref: shippingDlvrRef
            },
            success: function (data) {
                deliverea.schemaData = data;
            }
        });
    },
    /**
     * The method to be used to create shipments
     * @param order
     * @returns {*}
     */
    queueShipment: function (order) {
        var dfd = $.Deferred();
        var promise = dfd.promise();

        var sendShipment = function (next) {
            deliverea.currentOrder = order;

            deliverea.newShipment().then(function (shipmentResponse) {
                // Add it for use with the collection
                deliverea.currentOrder.shipping_dlvr_ref = shipmentResponse.shipping_dlvr_ref;

                if (deliverea.usesCollection()) {
                    return deliverea.newCollection().then(function (collectionResponse) {
                        dfd.resolve({
                            shipment: shipmentResponse,
                            collection: collectionResponse
                        });
                    }).fail(function () {
                        dfd.reject({type: "Collection", message: "Collection rejected"});
                    });
                } else {
                    dfd.resolve({
                        shipment: shipmentResponse
                    });
                }
            }).fail(function () {
                dfd.reject({type: "Shipment", message: "Shipment Rejected"});
            }).then(next, next);
        };

        deliverea.shipmentsQueue.queue(sendShipment);

        return promise;
    },
    /**
     * Get additional information for the desired method
     * @param method
     * @returns {*}
     */
    getAdditionalInformationForMethod: function (method) {
        var allAdditionalInformation = deliverea.schemaData.additional_information;

        if (allAdditionalInformation.hasOwnProperty(method)) {
            return allAdditionalInformation[method];
        }

        return null;
    },
    /**
     * Generate a request from mapped schema data for the desired method
     * @param method
     * @param requestType
     * @param additionalData
     * @returns {*}
     */
    doRequest: function (method, requestType, additionalData) {
        if (!additionalData) {
            additionalData = {};
        }

        var data = $.extend({},
            {
                action: 'deliverea',
                method: method
            },
            additionalData
        );

        var availableMethods = deliverea.schemaData.available_methods;
        var schemas = deliverea.schemaData.schemas;

        if (!availableMethods.hasOwnProperty(method) && !schemas.hasOwnProperty(method)) {
            var deferred = $.Deferred();
            deferred.reject("Invalid method");
            return deferred.promise();
        }

        var properties = schemas[method].schema.properties;
        var additionalInformation = deliverea.getAdditionalInformationForMethod(method);

        var delivereaApiMappings = DelivereaApiMappingsFactory();
        delivereaApiMappings = deliverea.prepareMappingsForRequest(delivereaApiMappings, delivereaApiMappings, properties);
        deliverea.mapRequest(delivereaApiMappings, data, properties, additionalInformation, properties, '');

        return $.ajax({
            type: requestType,
            dataType: 'json',
            url: "/wp-admin/admin-ajax.php",
            data: data
        });
    },

    /**
     * We need to create a delivereaApiMappingObj with all the possible parameters if they're not manually filled out
     * @param delivereaApiMappingObj
     * @param currentApiMapping
     * @param properties
     * @returns {*}
     */
    prepareMappingsForRequest: function(delivereaApiMappingObj, currentApiMapping, properties) {
        for (var property in properties) {
            var propertyObj = properties[property];

            if ($.inArray(propertyObj.type, ['array', 'object']) !== -1) {
                var propertyName = delivereaHelpers.toCamelCase(property);

                if (!currentApiMapping.hasProp(property)) {
                    currentApiMapping[propertyName] = new DelivereaObject();
                }

                var propertyObjProperties = propertyObj.type === 'array' ? propertyObj.items.properties : propertyObj.properties;
                delivereaApiMappingObj = deliverea.prepareMappingsForRequest(delivereaApiMappingObj, currentApiMapping.get(propertyName), propertyObjProperties)
            }
        }

        return delivereaApiMappingObj;
    },

    /**
     * Manipulates data based on allProperties, recursively calls itself to fill it out according to delivereApiMappings
     * @param delivereaApiMappingsObj
     * @param data
     * @param allProperties
     * @param additionalInformation
     * @param currentProperties
     * @param rootPath
     */
    mapRequest: function (delivereaApiMappingsObj, data, allProperties, additionalInformation, currentProperties, rootPath) {
        for (var property in currentProperties) {
            var curPath = (rootPath + "." + property).replace(/^\./, '');
            var propertyObj = currentProperties[property];

            if ($.inArray(propertyObj.type, ['array', 'object']) !== -1) {
                // Iterate recursively
                if (propertyObj.type === 'array') {
                    deliverea.mapRequest(delivereaApiMappingsObj, data, allProperties, additionalInformation, propertyObj.items.properties, curPath);
                } else if (propertyObj.type === 'object') {
                    deliverea.mapRequest(delivereaApiMappingsObj, data, allProperties, additionalInformation, propertyObj.properties, curPath);
                }
            } else {
                var mappedValue = deliverea.getMappedValue(delivereaApiMappingsObj, curPath, additionalInformation);

                if (mappedValue) {
                    var tData = data;
                    var tProperties = allProperties;
                    var pathProperties = curPath.split('.');

                    // Iterate through the path in dotNotation
                    for (var x = 0; x < pathProperties.length; ++x) {
                        var tProperty = pathProperties[x];

                        propertyObj = tProperties[tProperty];

                        if ($.inArray(propertyObj.type, ['array', 'object']) !== -1) {
                            // Drill down
                            var assignedData = deliverea.assignMappingData(tData, tProperty, x, pathProperties, propertyObj, tProperties);
                            tData = assignedData[0];
                            tProperties = assignedData[1];
                        }

                        // Final value in the path, set it
                        if (x === pathProperties.length - 1) {
                            tData[tProperty] = mappedValue; // assign the final value
                            tData = data; // reset scope to original object
                        }
                    }
                }
            }
        }
    },

    /**
     * Get mapped values for the request either from our js or from the additionalInformation
     * @param delivereaApiMappingsObj
     * @param curPath
     * @param additionalInformation
     * @returns {*}
     */
    getMappedValue: function (delivereaApiMappingsObj, curPath, additionalInformation) {
        var pathProperties = curPath.split('.');

        var currentMapping = delivereaApiMappingsObj;
        currentMapping.setAdditionalInformation(additionalInformation);

        var mappedValue = null;

        // Read the mapping from API Mappings
        for (var x = 0; x < pathProperties.length; ++x) {
            var tProperty = pathProperties[x];
            var propertyName = delivereaHelpers.toCamelCase(tProperty);

            if ((currentMapping instanceof DelivereaObject) && currentMapping.hasProp(propertyName)) {
                mappedValue = this.assignMappedValue(currentMapping, mappedValue, propertyName);
                currentMapping = mappedValue;
            } else {
                currentMapping = null;
                mappedValue = null;
            }

            if (x === pathProperties.length - 1) {
                // reset scope
                currentMapping = delivereaApiMappingsObj;
                currentMapping.setAdditionalInformation(additionalInformation);
            }
        }

        return mappedValue;
    },

    /**
     * Check if method is a function or not and return respective value
     * @param currentMapping
     * @param mappedValue
     * @param propertyName
     * @returns {*}
     */
    assignMappedValue: function (currentMapping, mappedValue, propertyName) {
        if (currentMapping.get(propertyName) instanceof Function) {
            mappedValue = currentMapping.get(propertyName)();
        } else {
            mappedValue = currentMapping.get(propertyName);
        }

        if (mappedValue instanceof DelivereaObject) {
            mappedValue.setAdditionalInformation(currentMapping.getAdditionalInformation(propertyName));
        }

        return mappedValue;
    },

    /**
     ** Assigns mapping data based on array / object when drilling down
     * @param tData
     * @param tProperty
     * @param currentPosition
     * @param pathProperties
     * @param propertyObj
     * @param tProperties
     * @returns {*[]}
     */
    assignMappingData: function (tData, tProperty, currentPosition, pathProperties, propertyObj, tProperties) {
        if (propertyObj.type === 'array') {
            tProperties = propertyObj.items.properties;
        } else {
            tProperties = propertyObj.properties;
        }

        if (!tData.hasOwnProperty(tProperty) && currentPosition !== pathProperties.length - 1) {
            propertyObj.type === 'array' ? tData[tProperty] = [{}] : tData[tProperty] = {};
            propertyObj.type === 'array' ? tData = tData[tProperty][0] : tData = tData[tProperty];
        } else if (tData.hasOwnProperty(tProperty) && currentPosition !== pathProperties.length - 1) {
            propertyObj.type === 'array' ? tData = tData[tProperty][0] : tData = tData[tProperty];
        }

        return [tData, tProperties];
    }
};