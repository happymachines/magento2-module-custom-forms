var config = {
    map: {
        '*': {
            "jquery.mask": 'HappyMachines_CustomForms/js/lib/jquery.mask/jquery.mask.min',
            "happymachines/customforms/render": "HappyMachines_CustomForms/js/form-render",
            "happymachines/customforms/date": "HappyMachines_CustomForms/js/form/field/render-handler/date"
        }
    },
    config: {
        mixins: {
            'Magento_Checkout/js/region-updater': {
                'HappyMachines_CustomForms/js/mixin/region-updater-mixin': true
            }
        }
    }
};
