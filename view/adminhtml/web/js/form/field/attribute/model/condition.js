define([
    'ko',
    'underscore',
    'HappyMachines_CustomForms/js/model/form-builder',
], function (ko, _, formBuilderModel) {
    'use strict';

    var formData = ko.observable(formBuilderModel.formData);

    formBuilderModel.formData.subscribe(function (updatedData) {
        formData(updatedData);
    });

    return function() {
        return {
            formData: formData,
            ifConditionSourceField: ko.observable(),
            ifConditionSourceFieldObject: ko.observable(),
            ifConditionTargetProperty: ko.observable(),
            ifConditionComparisonOption: ko.observable(),
            ifConditionTargetValue:  ko.observableArray(),
            thenConditionSourceField:  ko.observable(),
            thenConditionSourceFieldObject: ko.observable(),
            thenConditionTargetProperty:  ko.observable(),
            thenConditionComparisonOption:  ko.observable(),
            thenConditionTargetValue:  ko.observableArray(),
        }
    };
});
