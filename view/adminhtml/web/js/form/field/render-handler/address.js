define([
    'jquery',
    'mage/translate',
    'mage/template',
    'text!HappyMachines_CustomForms/template/notice.html',
], function ($, $t, mageTemplate, noticeTmpl) {
    'use strict';

    return function(field) {
        var disabledOptionsMessageTemplate = mageTemplate(noticeTmpl),
            disabledOptionsMessage = $t('Address Country and Region options will be automatically populated using your store\'s country options. ' +
                'Stores > Settings > Configuration > General > Country Options'),
            $formBuilderField = $(field).parents('.form-field'),
            $fieldSelectOptionsWrap = $formBuilderField.find('.sortable-options-wrap'),
            $conditionsWrap = $formBuilderField.find('.dataConditions-wrap');

        $conditionsWrap.hide();
        $fieldSelectOptionsWrap.html(disabledOptionsMessageTemplate({message: disabledOptionsMessage}));
    }
});
