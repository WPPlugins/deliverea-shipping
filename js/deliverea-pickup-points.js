/**
 * This class handles all interface logic for the module
 */
var delivereaPickupPoints = {
    errorMessages: {},

    modal: function () {
        return $('#pickup-point-modal');
    },

    init: function () {
        var modal = delivereaPickupPoints.modal();

        modal.find("#alert").hide();

        modal.find('.has-error').removeClass('.has-error');

        modal.find(":input").each(function () {
            $(this).val('').removeAttr('selected');
        });

        delivereaPickupPoints.modal().find('#main-content').show();
        delivereaPickupPoints.modal().find('#loader').hide();

        modal.find('.btn-primary').off('click').click(function () {
            delivereaPickupPoints.newPickupPoint();
        });
    },

    newPickupPoint: function () {
        if (!delivereaPickupPoints.validatePickupPoint()) {
            return;
        }

        delivereaPickupPoints.modal().find('#main-content').toggle();
        delivereaPickupPoints.modal().find('#loader').show();

        delivereaPickupPoints.modal().find('button').attr('disabled', 'disabled');
        var serialized = delivereaPickupPoints.modal().find(':input').serializeArray();

        var data = {};

        $(serialized).each(function (key, item) {
            data[item.name] = item.value;
        });

        deliverea.newPickupPoint(data).then(function (response) {
            delivereaPickupPoints.modal().modal('hide');
            delivereaPickupPoints.modal().find('button').removeAttr('disabled');

            var row = '<tr data-id="' + response.id + '">' +
                '<td>' + data.alias + '</td>' +
                '<td>' + data.attn + '</td>' +
                '<td>' + data.address + '</td>' +
                '<td>' + data.city + '</td>' +
                '<td>' + data.zip_code + '</td>' +
                '<td>' + data.country + '</td>' +
                '<td>' + data.observations + '</td>' +
                '<td><i class="fa fa-times fa-2 btn-delete" aria-hidden="true"></i></td>' +
                '</tr>';

            $('#pickup-points-table').find('tbody').append(row);
        });
    },

    removePickupPoint: function (id) {
        deliverea.removePickupPoint(id).then(function () {
            $('#pickup-points-table').find('[data-id=' + id + ']').remove();
        });
    },

    validatePickupPoint: function () {
        var filledOut = true;

        delivereaPickupPoints.modal().find('[data-required=true]').each(function () {
            if ($(this).val() == '' || $(this).val() == undefined) {
                filledOut = false;
                $(this).closest('div').addClass('has-error');
            } else {
                $(this).closest('div').removeClass('has-error');
            }
        });

        return filledOut;
    }
};

$(document).ready(function () {
    $('.btn-add').click(function () {
        delivereaPickupPoints.modal().modal();
    });

    $('#pickup-points-table').delegate('.btn-delete', 'click', function () {
        delivereaPickupPoints.removePickupPoint($(this).closest('tr').data('id'));
    });

    delivereaPickupPoints.modal().on('show.bs.modal', function () {
        delivereaPickupPoints.init();
    });
});