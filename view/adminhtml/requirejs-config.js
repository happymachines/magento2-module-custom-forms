var config = {
    map: {
        '*': {
            "jquery.formbuilder": 'HappyMachines_CustomForms/js/lib/formbuilder/form-builder.min',
            happymachinesCustomFormsBuilder: 'HappyMachines_CustomForms/js/form/form-builder',
            happymachinesCustomFormsConditions: 'HappyMachines_CustomForms/js/form/field/attribute/conditions/view',
        }
    },
    config: {
        mixins: {
            'Magento_Ui/js/form/form': {
                'HappyMachines_CustomForms/js/mixin/form-mixin': true
            },
            'Magento_Ui/js/lib/validation/validator': {
                'HappyMachines_CustomForms/js/mixin/validator-mixin': true
            }
        }
    }
};
