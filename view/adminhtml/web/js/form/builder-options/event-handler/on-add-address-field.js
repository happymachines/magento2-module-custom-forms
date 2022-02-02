define([
    'jquery',
    'underscore'
], function ($, _) {

    window.HAPPYMACHINES_CUSTOMFORMS_ADDRESS_GROUP_UUID = Date.now().toString();

    return function(fieldId, fieldData) {
        if (!fieldData.dataTypeAddressField) {
            return fieldData;
        }

        fieldData.dataTypeAddressGroup = window.HAPPYMACHINES_CUSTOMFORMS_ADDRESS_GROUP_UUID;

        if (fieldData.dataTypeAddressField === 'country') {
            /** Generate new unique group id after last address field from input set has been added **/
            window.HAPPYMACHINES_CUSTOMFORMS_ADDRESS_GROUP_UUID = Date.now().toString();
        }

        return fieldData;
    }
});
