define([
    'underscore',
    'jquery',
    'ko',
    'HappyMachines_CustomForms/js/model/form-builder',
], function (_, $, ko, formBuilderModel) {
    'use strict';

    var mixin = {
        /**
         * Intercept save call to add form builder data before submitting form
         *
         * @param {String} redirect
         * @param {Object} data
         */
        save: function (redirect, data) {
            var submit = this._super.bind(this, redirect, data);

            if (formBuilderModel.instance()) {
                $('#happymachines-custom-form-builder-input').val(JSON.stringify(formBuilderModel.instance().actions.getData()));
            }

            submit();
        }
    };

    return function (target) {
        return target.extend(mixin);
    };
});
