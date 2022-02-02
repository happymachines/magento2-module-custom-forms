define([
    'jquery',
    'ko',
    'HappyMachines_CustomForms/js/form/field/attribute/view/conditions',
], function ($, ko, conditions) {

    /**
     * Bind the conditions component to the conditions typeUserAttributes
     * @param field
     */
    return function(field) {
        var $field = $(field),
            $dataConditionsInput = $field.find('[name="dataConditions"]'),
            $dataConditionsInputWrap = $dataConditionsInput.parents('.dataConditions-wrap');

        $dataConditionsInput.hide();
        $dataConditionsInputWrap.append('<!-- ko template: getTemplate() --><!-- /ko -->')

        ko.applyBindings(new conditions({}, $dataConditionsInputWrap[0]), $dataConditionsInputWrap[0]);
    };
})
