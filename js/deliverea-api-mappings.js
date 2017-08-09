/**
 * Basic Object for Property Mapping
 * @param attrs
 * @returns {void|*}
 * @constructor
 */
var $ = jQuery;

function DelivereaObject(attrs) {
    this.additionalInformation = null;

    for (var attr in attrs) {
        this[attr] = attrs[attr];
    }
}


DelivereaObject.prototype = {
    /**
     * Checks both the object itself and recommendedValues to see if the value can be provided
     * @param key
     * @returns {boolean}
     */
    hasProp: function (key) {
        if (this.hasOwnProperty(key)) {
            var value = this[key];

            if (!delivereaHelpers.isEmpty(value)) {
                return true;
            }
        }

        return !delivereaHelpers.isEmpty(this.getRecommendedValue(key));
    },

    /**
     * Returns the value for the key
     * @param key
     * @returns {*}
     */
    get: function (key) {
        // We check against the object itself to check if it's mapped manually which is why we don't use hasProp
        if (this.hasOwnProperty(key)) {
            var value = this[key];

            if (!delivereaHelpers.isEmpty(value)) {
                return value;
            }
        }

        // We don't do this at the same time as hasOwnProperty because it's more resource intensive
        var recommendedValue = this.getRecommendedValue(key);

        if (recommendedValue) {
            return recommendedValue;
        }

        return null;
    },

    /**
     * Gets the recommended value value from additionalInformation
     * @param key
     * @returns {*}
     */
    getRecommendedValue: function (key) {
        var additionalInformation = this.getAdditionalInformation(key);

        if (additionalInformation instanceof Object) {
            if (additionalInformation.hasOwnProperty('recommended_value')) {
                return additionalInformation.recommended_value;
            }
        }

        return null;
    },

    /**
     * @param additionalInformation
     */
    setAdditionalInformation: function (additionalInformation) {
        this.additionalInformation = additionalInformation;
    },

    /**
     * Gets either the current additional information if no key is found,
     * drills down to get a deeper level or returns null if key is provided and it isn't found
     * @param key
     * @returns {*}
     */
    getAdditionalInformation: function (key) {
        key = delivereaHelpers.fromCamelCase(key, '_');

        if (!key) {
            return this.additionalInformation;
        }

        if ((this.additionalInformation instanceof Object) && this.additionalInformation.hasOwnProperty(key)) {
            return this.additionalInformation[key];
        }

        return null;
    }
};

/**
 * This factory creates a mapped object of all possible parameters in the Deliverea v1 API parameters to camelcased properties that return
 * so that whenever it's requested in the API we can provide it regardless of the carrier / method it is
 */

function DelivereaApiMappingsFactory() {
    var mapping = {
        carrierCode: function () {
            return $('#carrier-code').val();
        },
        serviceCode: function () {
            return $('#service-code').val();
        },
        serviceType: function () {
            return null; // we don't do this in this module
        },
        serviceRegion: function () {
            return null; // we don't do this in this module
        },
        parcelNumber: function () {
            return 1;
        },
        parcelWidth: function () {
            return 1; // Use default
        },
        parcelWeight: function () {
            return 1; // Use default
        },
        parcelHeight: function () {
            return 1; // Use default
        },
        parcelLength: function () {
            return 1; // Use default
        },
        parcelVolume: function () {
            return 1; // Use default
        },
        shippingDate: function () {
            return $('#collection-date').val(); // use the collection as shipping date
        },
        collectionDate: function () {
            return $('#collection-date').val();
        },
        shippingClientRef: function () {
            return deliverea.currentOrder.shipping_client_ref;
        },
        dlvrRef: function () {
            return null; // not used in this module
        },
        shippingDlvrRef: function () {
            return deliverea.currentOrder.shipping_dlvr_ref;
        },
        collectionClientRef: function () {
            return deliverea.currentOrder.shipping_client_ref;
        },
        cashOnDelivery: function () {
            return null; // We don't do COD in this module
        },
        docsNumber: function () {
            return null; // We don't differentiate between doc / packages in this module
        },
        returnDlvrRef: function () {
            return null; // We don't do returns in this module
        },
        isReturn: function () {
            return null; // We don't do returns in this module
        },
        hourStart1: function () {
            return $('#hour-start-1').val();
        },
        hourEnd1: function () {
            return $('#hour-end-1').val();
        },
        hourStart2: function () {
            return null; // we don't do this in this module
        },
        hourEnd2: function () {
            return null; // we don't do this in this module
        },
        fromNif: function () {
            return deliverea.selectedPickupPoint.nif;
        },
        fromName: function () {
            return deliverea.selectedPickupPoint.alias;
        },
        fromPhone: function () {
            return deliverea.selectedPickupPoint.phone;
        },
        fromEmail: function () {
            return deliverea.selectedPickupPoint.email;
        },
        fromAttn: function () {
            return deliverea.selectedPickupPoint.attn;
        },
        fromAddress: function () {
            return deliverea.selectedPickupPoint.address;
        },
        fromCity: function () {
            return deliverea.selectedPickupPoint.city;
        },
        zipCode: function () {
            return mapping.fromZipCode();
        },
        fromZipCode: function () {
            return deliverea.selectedPickupPoint.zip_code;
        },
        countryCode: function () {
            return mapping.fromCountryCode();
        },
        fromCountryCode: function () {
            return deliverea.selectedPickupPoint.country;
        },
        fromObservations: function () {
            return deliverea.selectedPickupPoint.observations;
        },
        toNif: function () {
            return deliverea.currentOrder.nif;
        },
        toName: function () {
            return deliverea.currentOrder.name;
        },
        toAttn: function () {
            return deliverea.currentOrder.attn;
        },
        toPhone: function () {
            return deliverea.currentOrder.phone;
        },
        toEmail: function () {
            return deliverea.currentOrder.email;
        },
        toAddress: function () {
            return deliverea.currentOrder.address;
        },
        toCity: function () {
            return deliverea.currentOrder.city;
        },
        toZipCode: function () {
            return deliverea.currentOrder.zip_code;
        },
        toCountryCode: function () {
            return deliverea.currentOrder.country_code;
        },
        toObservations: function () {
            return deliverea.currentOrder.observations;
        }
    };

    return new DelivereaObject(mapping);
}