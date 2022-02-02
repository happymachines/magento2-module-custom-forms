define([
    'jquery'
], function ($) {
    'use strict';

    return function(validator) {
        validator.addRule(
            'validate-formbuilder-has-customer-email-field',
            function(value, params, additionalParams) {
                var $formBuilder = $('#happymachines-custom-form-builder');

                if (!!parseInt(value) && $formBuilder.length) {
                    return $("[data-customer-email='true']").length;
                }

                return true;
            },
            $.mage.__('Please add a field to the form builder with the "Is Customer Email" option checked.')
        )

        return validator;
    }
});
