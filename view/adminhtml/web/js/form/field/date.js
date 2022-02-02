define([
    'jquery',
    'mage/translate',
    'mage/calendar'
], function ($, $t) {
    'use strict';

    /** Configure the class for runtime loading **/
    if (!window.fbControls) window.fbControls = [];
    window.fbControls.push(function (controlClass) {

        /**
         * Date picker control
         */
        class controlDate extends controlClass {
            /**
             * Class configuration - return label related to this control
             * @return definition object
             */
            static get definition() {
                return {
                    i18n: {
                        default: 'Date',
                    },
                }
            }

            /**
             * build a text DOM element, supporting other jquery text form-control's
             * @return DOM Element to be injected into the form.
             */
            build() {
                var config = {
                    id: this.config.name,
                    name: this.config.name,
                    className: this.config.className,
                    autocomplete: 'off',
                    type: 'text',
                };

                if (this.config.readonly) {
                    config.readonly = this.config.readonly;
                }

                if (this.config.disabled) {
                    config.disabled = this.config.disabled;
                }

                if (this.config.fieldsetStart) {
                    config.fieldsetStart = this.config.fieldsetStart;
                }

                this.input = this.markup('input', null, config);

                return this.input;
            }

            onRender() {
                var changeMonth = !!this.config.changeMonth,
                    changeYear = !!this.config.changeYear,
                    showButtonPanel = !!this.config.showButtonPanel,
                    showWeek = !!this.config.showWeek,
                    dateFormat = this.config.dateFormat,
                    minDate = this.config.minDate,
                    maxDate = this.config.maxDate;

                $(this.input).calendar({
                    changeMonth: changeMonth,
                    changeYear: changeYear,
                    showButtonPanel: showButtonPanel,
                    currentText: $t('Go To Today'),
                    closeText: $t('Close'),
                    showWeek: showWeek,
                    dateFormat: dateFormat,
                    minDate: minDate,
                    maxDate: maxDate
                });
            }
        }

        controlClass.register('date', controlDate);

        return controlDate;
    });
});
