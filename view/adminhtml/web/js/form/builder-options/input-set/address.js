/**
 * Define grouped inputs for use in the form builder
 *
 * @see https://formbuilder.readthedocs.io/en/latest/formBuilder/options/inputSets/
 */
define([], function () {

    return {
        label: 'Address',
        name: 'address',
        showHeader: false,
        fields: [
            {
                type: 'text',
                label: 'Street',
                required: true,
                className: 'input-text',
                dataTypeAddressField: 'street',
            },
            {
                type: 'text',
                label: 'City',
                required: true,
                className: 'input-text',
                dataTypeAddressField: 'city',
            },
            {
                type: 'text',
                label: 'Zipcode',
                required: true,
                className: 'input-text',
                dataTypeAddressField: 'zipcode',
            },
            {
                type: 'select',
                label: 'State / Province / Region',
                className: 'input-select',
                required: true,
                values: [
                    {
                        label: 'Please Select',
                        value: '',
                    }
                ],
                dataTypeAddressField: 'region',
            },
            {
                type: 'select',
                label: 'Country',
                className: 'input-select',
                required: true,
                values: [
                    {
                        label: 'Please Select',
                        value: '',
                    }
                ],
                dataTypeAddressField: 'country',
            },
        ]
    };
});
