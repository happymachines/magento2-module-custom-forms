/**
 * Define grouped inputs for use in the form builder
 *
 * @see https://formbuilder.readthedocs.io/en/latest/formBuilder/options/inputSets/
 */
define([
    'HappyMachines_CustomForms/js/form/builder-options/input-set/address',
], function (addressInputSet) {
    return [
        addressInputSet,
        {
            label: 'Phone (US)',
            showHeader: false,
            fields: [
                {
                    type: 'text',
                    subtype: 'tel',
                    label: 'Phone',
                    required: true,
                    className: 'input-text',
                    dataMask: '+1 (000) 000-0000'
                }
            ]
        }
    ]
});
