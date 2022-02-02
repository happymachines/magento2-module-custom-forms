/**
 * Define custom attributes that can be applied via the form builder
 *
 * @see https://formbuilder.readthedocs.io/en/latest/formBuilder/options/typeUserAttrs/
 */

define(['jquery'], function ($) {
    return {
        header: {
            fieldsetStart: {
                label: 'Fieldset Start',
                type: 'checkbox',
                value: true,
            },
        },
        paragraph: {
            fieldsetStart: {
                label: 'Fieldset Start',
                type: 'checkbox',
                value: false,
            },
        },
        text: {
            inputClassName: {
                label: 'Input Class',
                type: 'text',
                value: ''
            },
            fieldsetStart: {
                label: 'Fieldset Start',
                type: 'checkbox',
                value: false,
            },
            disabled: {
                label: 'disabled',
                type: 'checkbox',
                value: false,
            },
            readonly: {
                label: 'readonly',
                type: 'checkbox',
                value: false,
            },
            dataCustomerEmail: {
                label: 'Is Customer Email',
                type: 'checkbox',
                value: false
            },
            dataMask: {
                label: 'Field Mask',
                type: 'text',
                value: ''
            },
            dataTypeAddressField: {
                label: 'Address Field Type',
                type: 'hidden',
                value: ''
            },
            dataTypeAddressGroup: {
                label: 'Address Field Group',
                type: 'hidden',
                value: ''
            }
        },
        textarea: {
            inputClassName: {
                label: 'Input Class',
                type: 'text',
                value: ''
            },
            fieldsetStart: {
                label: 'Fieldset Start',
                type: 'checkbox',
                value: false,
            },
            disabled: {
                label: 'disabled',
                type: 'checkbox',
                value: false,
            },
            readonly: {
                label: 'readonly',
                type: 'checkbox',
                value: false,
            },
        },
        select: {
            inputClassName: {
                label: 'Input Class',
                type: 'text',
                value: ''
            },
            fieldsetStart: {
                label: 'Fieldset Start',
                type: 'checkbox',
                value: false,
            },
            disabled: {
                label: 'disabled',
                type: 'checkbox',
                value: false,
            },
            dataConditions: {
                label: 'Conditions',
                type: 'text',
                value: ''
            },
            /**
             * Work around since formbuilder doesn't support creating custom field types
             * extending select inputs
             */
            dataTypeAddressField: {
                label: 'Address Field Type',
                type: 'hidden',
                value: ''
            },
            dataTypeAddressGroup: {
                label: 'Address Field Group',
                type: 'hidden',
                value: ''
            }
        },
        'radio-group': {
            inputClassName: {
                label: 'Input Class',
                type: 'text',
                value: ''
            },
            fieldsetStart: {
                label: 'Fieldset Start',
                type: 'checkbox',
                value: false,
            },
            disabled: {
                label: 'disabled',
                type: 'checkbox',
                value: false,
            },
            dataConditions: {
                label: 'Conditions',
                type: 'text',
                value: ''
            },
        },
        checkbox: {
            inputClassName: {
                label: 'Input Class',
                type: 'text',
                value: ''
            },
            fieldsetStart: {
                label: 'Fieldset Start',
                type: 'checkbox',
                value: false,
            },
            disabled: {
                label: 'disabled',
                type: 'checkbox',
                value: false,
            },
            dataConditions: {
                label: 'Conditions',
                type: 'text',
                value: ''
            },
        },
        'checkbox-group': {
            inputClassName: {
                label: 'Input Class',
                type: 'text',
                value: ''
            },
            fieldsetStart: {
                label: 'Fieldset Start',
                type: 'checkbox',
                value: false,
            },
            disabled: {
                label: 'disabled',
                type: 'checkbox',
                value: false,
            },
            dataConditions: {
                label: 'Conditions',
                type: 'text',
                value: ''
            },
        },
        file: {
            inputClassName: {
                label: 'Input Class',
                type: 'text',
                value: ''
            },
            fieldsetStart: {
                label: 'Fieldset Start',
                type: 'checkbox',
                value: false,
            },
            disabled: {
                label: 'disabled',
                type: 'checkbox',
                value: false,
            },
            accept: {
                label: 'Accept',
                type: 'select',
                multiple: true,
                options: {
                    'true': 'All File Types',
                    '.csv': '.csv',
                    '.doc': '.doc',
                    '.docx': '.docx',
                    '.flac': '.flac',
                    '.gif': '.gif',
                    '.jpg': '.jpg',
                    '.jpeg': '.jpeg',
                    '.mov': '.mov',
                    '.mpg': '.mpg',
                    '.mpeg': '.mpeg',
                    '.mp3': '.mp3',
                    '.mp4': '.mp4',
                    '.ogg': '.ogg',
                    '.pdf': '.pdf',
                    '.png': '.png',
                    '.svg': '.svg',
                    '.wav': '.wav',
                    '.webp': '.webp',
                    '.webm': '.webm',
                    '.wmv': '.wmv',
                    '.xls': '.xls',
                    '.xlsx': '.xlsx',
                    '.xml': '.xml',
                }
            }
        },
        number: {
            inputClassName: {
                label: 'Input Class',
                type: 'text',
                value: ''
            },
            fieldsetStart: {
                label: 'Fieldset Start',
                type: 'checkbox',
                value: false,
            },
            disabled: {
                label: 'disabled',
                type: 'checkbox',
                value: false,
            },
        },
        date: {
            inputClassName: {
                label: 'Input Class',
                type: 'text',
                value: ''
            },
            fieldsetStart: {
                label: 'Fieldset Start',
                type: 'checkbox',
                value: false,
            },
            disabled: {
                label: 'disabled',
                type: 'checkbox',
                value: false,
            },
            readonly: {
                label: 'readonly',
                type: 'checkbox',
                value: true,
            },
            changeMonth: {
                label: 'Month Is Dropdown',
                type: 'checkbox',
                value: false
            },
            changeYear: {
                label: 'Year Is Dropdown',
                type: 'checkbox',
                value: false
            },
            showWeek: {
                label: 'Show Week',
                type: 'checkbox',
                value: false
            },
            showButtonPanel: {
                label: 'Show Buttons',
                type: 'checkbox',
                value: false
            },
            //todo implement
            // minDate: {
            //     label: 'Min Date',
            //     type: 'text',
            //     value: '',
            // },
            // maxDate: {
            //     label: 'Max Date',
            //     type: 'text',
            //     value: ''
            // },
            dateFormat: {
                label: 'Date Format',
                type: 'select',
                options: {
                    'EEEE, MMMM d, yy': $.datepicker.formatDate('DD, MM d, yy', new Date()),
                    'E, d MMM, yy': $.datepicker.formatDate('D, d M, yy', new Date()),
                    'MMM d, yy': $.datepicker.formatDate('M d, yy', new Date()),
                    'd MMM yy': $.datepicker.formatDate('d M yy', new Date()),
                    'yy-mm-dd': $.datepicker.formatDate('yy-mm-dd', new Date()),
                    'dd/mm/yy': $.datepicker.formatDate('dd/mm/yy', new Date()),
                    'mm/dd/yy': $.datepicker.formatDate('mm/dd/yy', new Date()),
                }
            }
        },
    }
});
