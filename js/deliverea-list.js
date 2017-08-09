/**
 * This class handles all interface logic for the module
 */
var delivereaList = {
    selectPrompt: "-- Selecciona Uno --",
    pickupPoints: [],
    errorMessages: {
        getClientCarriers: "No se ha podido cargar tus operadors por favor intentalo mas tarde.",
        getClientServices: 'Ha ocurrido un error cargando los servicios, por favor intentelo mas tarde.',
        getServiceInfo: "Ha ocurrido un error inesperado, por favor intentalo mas tarde.",
        getCollectionCutoffHour: "Ha ocurrido un error cargando las horas de corte, por favor intentelo mas tarde."
    },

    modal: function () {
        return $('#shipments-modal');
    },

    selectedItems: function () {
        return $('#shipments-table').find('input[type=checkbox]:checked');
    },

    refresh: function () {
        if (delivereaList.selectedItems().length > 0) {
            $('.btn-send').removeAttr('disabled');
        } else {
            $('.btn-send').attr('disabled', 'disabled');
        }
    },

    prepareCurrentOrder: function (data) {
        return {
            shipping_client_ref: data.shippingReference,
            shipping_dlvr_ref: data.shippingDlvrRef,
            nif: '',
            name: data.shippingFirstName + " " + data.shippingLastName,
            attn: '',
            phone: data.billingPhone,
            email: data.billingEmail,
            address: data.shippingAddressOne + " " + data.shippingAddressTwo,
            city: data.shippingCity,
            zip_code: data.shippingPostcode,
            country_code: data.shippingCountry,
            observations: '',
            customs_value: parseInt(data.customsValue).toFixed(2)
        };
    },

    init: function () {
        var modal = delivereaList.modal();

        modal.find("#from-address-id").val('').change();
        modal.find("#alert").hide();
        modal.find('#collection-date').val(moment().format('YYYY-MM-DD'));
        delivereaList.modal().find('#main-content').show();
        delivereaList.modal().find('#loader').hide();

        modal.find('.btn-primary').off('click').click(function () {
            modal.find('#main-content').toggle();
            modal.find('#loader').show();
            delivereaList.generateShipments();
        });
    },

    servicesReset: function () {
        var output = "<option value=''>" + delivereaList.selectPrompt + "</option>";
        $('#service-code').html(output).change();
    },

    addressChanged: function (e) {
        if (!e.target.value) {
            deliverea.selectedPickupPoint = null;
        }

        deliverea.selectedPickupPoint = delivereaList.pickupPoints[e.target.value];
        var firstOrder = delivereaList.selectedItems().first().closest('tr');
        deliverea.currentOrder = delivereaList.prepareCurrentOrder(firstOrder.data());
        delivereaList.getServiceInfo();
    },

    getServiceInfo: function () {
        if (!deliverea.selectedPickupPoint || !deliverea.currentOrder || !deliverea.delivereaApiMapping.carrierCode() || !deliverea.delivereaApiMapping.serviceCode()) {
            return;
        }

        deliverea.getServiceInfo().then(function () {
            delivereaList.getCollectionCutoffHour();
        }).fail(function () {
            delivereaList.renderModalError(delivereaList.errorMessages.getServiceInfo);
        });
    },

    getClientCarriers: function () {
        deliverea.getClientCarriers().then(function (data) {
            var carriers = data.carriers;
            for (var x = 0; x < carriers.length; x++) {
                if (carriers[x].status == 1) {
                    $('#carrier-code').append("<option value='" + carriers[x].carrier_code + "'>" + carriers[x].carrier_name + "</option>");
                }
            }
        }).fail(function () {
            delivereaList.renderListError(delivereaList.errorMessages.getClientCarriers);
        });
    },

    getCollectionCutoffHour: function () {
        deliverea.getCollectionCutoffHour().then(function (data) {
            cutoffHours.renderCutoffHours(data.cutoff);
        }).fail(function () {
            delivereaList.renderModalError(delivereaList.errorMessages.getCollectionCutoffHour);
        });
    },

    carrierChanged: function () {
        var output = "<option value=''>" + delivereaList.selectPrompt + "</option>";
        delivereaList.servicesReset();

        deliverea.getClientServices().then(function (data) {
            var services = data.services;
            for (var x = 0; x < services.length; ++x) {
                var service = services[x];
                output += "<option value=\"" + service.service_code + "\">" + service.service_name + "</option>";
            }
            $('#service-code').html(output);
        }).fail(function () {
            delivereaList.renderModalError(delivereaList.errorMessages.getClientServices);
        });
    },

    serviceChanged: function () {
        cutoffHours.reset();
        delivereaList.getServiceInfo();
    },

    exportPDFs: function (references) {
        window.location.href = "/wp-admin/admin.php?page=deliverea-shipping&export=true&filter_references=" + references;
    },

    generateShipments: function () {
        delivereaList.modal().find('button').attr('disabled', 'disabled');

        var selectedItems = delivereaList.selectedItems();

        var currentIndex = 0;
        var references = '';

        selectedItems.each(function (key, item) {
            var data = $(item).closest('tr').data();
            var order = delivereaList.prepareCurrentOrder(data);

            deliverea.queueShipment(order).then(function (responses) {
                var row = $(item).closest('tr');
                var shipment = responses.shipment;

                references += (key ? ',' : '') + shipment.shipping_dlvr_ref;

                row.data('shipping-dlvr-ref', shipment.shipping_dlvr_ref);
                row.find('[name=shipping-reference]').html(shipment.shipping_dlvr_ref);
                row.find('[name=carrier-reference]').html(shipment.shipping_carrier_ref);
                row.find('[name=collection-date]').html(deliverea.delivereaApiMapping.shippingDate());
                row.find('input[type=checkbox]').remove();
                row.find('.btn-print').removeClass('hidden');

                row.removeClass('danger');
                row.addClass('success');
            }).fail(function () {
                var row = $(item).closest('tr');

                row.addClass('danger');
                row.find("[data-toggle='tooltip']").removeClass('hidden');
            }).always(function () {
                delivereaList.refresh();
                deliverea.currentOrder = null;
                currentIndex++;
                if (currentIndex === selectedItems.length) {
                    if (references && deliverea.usesGetShipmentLabel()) {
                        delivereaList.exportPDFs(references);
                    }

                    delivereaList.ajaxFinished();
                }
            });
        });
    },

    ajaxFinished: function () {
        delivereaList.modal().modal('hide');
        delivereaList.modal().find('button').removeAttr('disabled');
    },

    generatePdf: function (e) {
        var reference = $(e.target).closest('tr').data('shipping-dlvr-ref');

        deliverea.getShipmentLabel(reference).then(function (response) {
            delivereaList.downloadPDF(response.data);
        });
    },

    downloadPDF: function (data) {
        var blob = new Blob([delivereaList.b64toBlob(data.label)]);
        var link = document.createElement('a');
        link.href = window.URL.createObjectURL(blob);
        link.download = data.shipping_dlvr_ref + ".pdf";
        link.click();
    },

    b64toBlob: function (b64Data, contentType, sliceSize) {
        contentType = contentType || '';
        sliceSize = sliceSize || 512;

        var byteCharacters = atob(b64Data);
        var byteArrays = [];

        for (var offset = 0; offset < byteCharacters.length; offset += sliceSize) {
            var slice = byteCharacters.slice(offset, offset + sliceSize);

            var byteNumbers = new Array(slice.length);
            for (var i = 0; i < slice.length; i++) {
                byteNumbers[i] = slice.charCodeAt(i);
            }

            var byteArray = new Uint8Array(byteNumbers);

            byteArrays.push(byteArray);
        }

        return new Blob(byteArrays, {type: contentType});
    },

    renderListError: function (text) {
        $('#list-error-alert').show().find('span').html(text);
    },

    renderModalError: function (text) {
        $('#modal-error-alert').show().find('span').html(text);
    }
};

$(document).ready(function () {
    delivereaList.getClientCarriers();

    $('#list-error-alert .close').on('click', function (e) {
        $(this).closest('#list-error-alert').hide();
    });

    $('#modal-error-alert .close').on('click', function (e) {
        $(this).closest('#modal-error-alert').hide();
    });

    $('[data-toggle="tooltip"]').tooltip();

    $("#collection-date").datepicker({"dateFormat": "yy-mm-dd"});

    $('#from-address-id').change(delivereaList.addressChanged);

    $('.btn-send').click(function () {
        $('#shipments-modal').modal();
    });

    $('#shipments-table').delegate('.btn-print', 'click', function (e) {
        delivereaList.generatePdf(e);
    }).delegate('input[type=checkbox]', 'click', function () {
        delivereaList.refresh();
    });

    $('#shipments-modal').on('show.bs.modal', function () {
        delivereaList.init();
    });

    $('#carrier-code').change(delivereaList.carrierChanged);

    $('#service-code').change(delivereaList.serviceChanged);
});