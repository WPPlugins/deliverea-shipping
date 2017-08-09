delivereaHelpers = {
    /**
     * Converts dash or underscored strings to camelCase
     * @param string
     * @returns {XML|string|*|void}
     */
    toCamelCase: function (string) {
        return string.replace(/[_|-]([\w])/g, function (m, w) {
            return w.toUpperCase();
        });
    },
    /**
     * Converts camelCase to whatever separator you want, default separator _
     * Caveat: test1L => test_1_l
     * @param string
     * @param separator
     * @returns {XML|string|*|void}
     */
    fromCamelCase: function (string, separator) {
        if (!separator) {
            separator = '_';
        }

        return string.replace(/([A-Z|\d])/g, function (m, w) {
            return separator + w.toLowerCase();
        });
    },
    /**
     * Return if value is of empty type (undefined, null or '')
     * @param value
     * @returns {boolean}
     */
    isEmpty: function (value) {
        if (value instanceof Function) {
            value = value();
        }

        return (typeof value === 'undefined' || value === null || value === '');
    }
};