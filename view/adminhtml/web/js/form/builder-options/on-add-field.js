define([
    'jquery',
    'underscore',
    'HappyMachines_CustomForms/js/form/builder-options/event-handler/on-add-address-field',
], function ($, _, onAddAddressFieldHandler) {
    const onAddFieldHandlers = [
        onAddAddressFieldHandler
    ];

    return function(fieldId, fieldData) {
        _.each(onAddFieldHandlers, function(fieldHandler) {
            fieldData = fieldHandler(fieldId, fieldData);
        })

        return fieldData;
    }
});
