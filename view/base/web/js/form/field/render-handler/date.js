define([
    'jquery',
    'mage/translate',
    'mage/calendar'
], function ($, $t) {
    'use strict';

    $.widget('happymachines.dateField', {
        options: {
            changeMonth: false,
            changeYear: false,
            showButtonPanel: false,
            currentText: $t('Go To Today'),
            closeText: $t('Close'),
            showWeek: false,
            dateFormat: "EEEE, MMMM d, yy",
            minDate: null,
            maxDate: null,
            changeMonthAttr: 'change-month',
            changeYearAttr: 'change-year',
            showWeekAttr: 'show-week',
            showButtonPanelAttr: 'show-button-panel',
            dateFormatAttr: 'date-format',
            minDateAttr: 'min-date',
            maxDateAttr: 'max-date',
        },

        /**
         *
         * @private
         */
        _create: function () {
            $(this.element).calendar({
                changeMonth: this._getOptionAttribute(this.options.changeMonthAttr, 'changeMonth'),
                changeYear: this._getOptionAttribute(this.options.changeYearAttr, 'changeYear'),
                showButtonPanel: this._getOptionAttribute(this.options.showButtonPanelAttr, 'showButtonPanel'),
                currentText: $t('Go To Today'),
                closeText: $t('Close'),
                showWeek: this._getOptionAttribute(this.options.showWeekAttr, 'showWeek'),
                dateFormat: this._getOptionAttribute(this.options.dateFormatAttr, 'dateFormat'),
                minDate: this._getOptionAttribute(this.options.minDateAttr, 'minDate'),
                maxDate: this._getOptionAttribute(this.options.maxDateAttr, 'maxDate')
            });
        },

        /**
         * Get the configuration option from the bound element's attribute
         * @param attribute
         * @param defaultOption
         * @returns {jQuery|*}
         * @private
         */
        _getOptionAttribute: function(attribute, defaultOption) {
            return !!$(this.element).attr(attribute) ? $(this.element).attr(attribute) : this.options[defaultOption];
        },
    });

    return $.happymachines.dateField;
});
