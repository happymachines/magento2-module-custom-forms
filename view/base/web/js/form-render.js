define([
    'jquery',
    'mage/translate',
    'underscore',
    'HappyMachines_CustomForms/js/form/conditions-handler',
    'jquery.mask'
], function ($, $t, _, conditionsHandler) {
    'use strict';

    $.widget('happymachines.customFormRender', {
        options: {
            formData: []
        },

        /** @inheritdoc */
        _create: function () {
            this.renderForm();
        },

        /**
         * Render the form
         */
        renderForm: function() {
            this._initializeConditionsHandler($(this.element), this.options.formData)
        },

        /**
         *
         * @param $form
         * @param formData
         * @private
         */
        _initializeConditionsHandler($form, formData) {
            //todo display loader or mask to prevent fields being visible and then disappearing?
            conditionsHandler($form, formData);
        }
    });

    return $.happymachines.customFormRender;
});
