define([
    'jquery',
    'ko',
], function ($, ko) {
    'use strict';

    var instance = ko.observable(),
        formData = ko.observable();

    /**
     * Updates model data with latest data
     * from the form builder instance
     */
    var updateFormData = function() {
        if (this.instance()) {
            this.formData(this.instance().actions.getData('json', true));
            this.formData.valueHasMutated();
        }
    }

    return {
        instance: instance,
        formData: formData,
        updateFormData: updateFormData
    };
});
