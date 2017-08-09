var cutoffHours = {
    startHour: 6,

    reset: function () {
        var output = '<option>--</option>';
        $('#hour-start-1').html(output);
        $('#hour-end-1').html(output);
    },

    renderCutoffHours: function (cutoffHour) {
        var output = '<option>--</option>';

        var endHour = cutoffHour.substr(0, 2);
        var endMinute = cutoffHour.substr(3, 2);

        for (var x = cutoffHours.startHour; x <= endHour; ++x) {
            var hour = cutoffHours.addZero(x);
            output += '<option value="' + hour + ':00">' + hour + ':00</option>';
            if (x != endHour || (x == endHour && endMinute == 30)) {
                output += '<option value="' + hour + ':30">' + hour + ':30</option>';
            }
        }

        $('#hour-start-1').html(output);
        $('#hour-end-1').html(output);

        cutoffHours.setCollectionHours();
    },

    addZero: function (val) {
        if (val < 10) {
            val = '0' + val;
        }

        return val;
    },

    setCollectionHours: function () {
        var date = new Date();
        var hour = date.getHours();
        var minute = date.getMinutes();

        if (minute > 30) {
            minute = 0;
            hour++;
        } else {
            minute = 30;
        }

        var lastOption = $('#hour-end-1>option:last').val();
        var endHour = lastOption.substr(0, 2);
        var endMinute = lastOption.substr(3, 2);

        // TODO: Change to use moment as well...
        var now = new Date(date.getYear(), date.getMonth(), date.getDay(), hour, minute);
        var end = new Date(date.getYear(), date.getMonth(), date.getDay(), endHour, endMinute);
        var difference = (end - now);

        // Set the collection date
        var collectionDate = $('#collection-date').val();

        collectionDate = moment(collectionDate);

        // Need two hour difference
        if (now < end && difference / 36e5 >= 2) {
            $('#hour-start-1').val(cutoffHours.addZero(now.getHours()) + ":" + cutoffHours.addZero(now.getMinutes())).change();
        } else {
            $('#hour-start-1').val(cutoffHours.addZero(cutoffHours.startHour) + ":00").change();
            collectionDate.add(1, 'd');
        }

        $('#hour-end-1').val(cutoffHours.addZero(end.getHours()) + ":" + cutoffHours.addZero(end.getMinutes())).change();
        $('#collection-date').val(collectionDate.format('YYYY-MM-DD'));
    }
};