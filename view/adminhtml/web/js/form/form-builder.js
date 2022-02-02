define([
    'jquery',
    'uiElement',
    'HappyMachines_CustomForms/js/model/form-builder',
    'HappyMachines_CustomForms/js/form/builder-options/type-user-attributes',
    'HappyMachines_CustomForms/js/form/builder-options/input-sets',
    'HappyMachines_CustomForms/js/form/builder-options/layout-templates',
    'HappyMachines_CustomForms/js/form/builder-options/type-user-events',
    'HappyMachines_CustomForms/js/form/builder-options/on-add-field',
    'HappyMachines_CustomForms/js/form/fields',
    'jquery.formbuilder',
    'jquery.mask'
], function ($, Element, formBuilderModel, typeUserAttrs, inputSets, adminLayoutTemplates, typeUserEvents, onAddField) {
    'use strict';

    return Element.extend({
        defaults: {
            formData: [],
            formBuilderOptions: {
                controlOrder: [
                    'header',
                    'paragraph',
                    'text',
                    'textarea',
                    'select',
                    'radio-group',
                    'checkbox-group',
                    'checkbox',
                    'date',
                    'file',
                    'number',
                    'hidden',
                ],
                controlPosition: 'left',
                editOnAdd: true,
                disabledAttrs: ['access', 'other', 'description', 'toggle', 'inline'],
                disabledActionButtons: ['save'],
                disableFields: ['autocomplete', 'button'],
                disabledFieldButtons: {
                    'checkbox': ['copy'],
                    'checkbox-group': ['copy'],
                    'date': ['copy'],
                    'file': ['copy'],
                    'hidden': ['copy'],
                    'number': ['copy'],
                    'radio': ['copy'],
                    'radio-group': ['copy'],
                    'select': ['copy'],
                    'text': ['copy'],
                    'textarea': ['copy']
                },
                disabledSubtypes: {
                    file: ['fineuploader'],
                    paragraph: ['canvas', 'output', 'address', 'blockquote'],
                    text: ['color'],
                    textarea: ['quill', 'tinymce']
                },
                fieldRemoveWarn: true,
                inputSets: inputSets,
                layoutTemplates: adminLayoutTemplates,
                onAddField: onAddField,
                scrollToFieldOnAdd: false,
                stickyControls: {
                    enable: true,
                    offset: {
                        top: 100
                    }
                },
                typeUserAttrs: typeUserAttrs,
                typeUserEvents: typeUserEvents,
            }
        },
        initialize: function (config, element) {
            this._super();
            this._initializeFormBuilder($(element));
            return this;
        },
        _initializeFormBuilder: function(inputElement) {
            var self = this;

            $('body').trigger('processStart');

            $(inputElement).formBuilder(this.formBuilderOptions).promise.then(function(formBuilder) {
                formBuilder.actions.setData(self.formData);
                formBuilderModel.instance(formBuilder);
                self._dispatchRenderEvent();
                self._bindEvents(inputElement, formBuilder);
                self._disableEditorDoubleClick(inputElement);
                self._rearrangeFieldActions(inputElement);

                $('body').trigger('processStop');
            })
        },
        _bindEvents: function(inputElement, formBuilder) {
            var self = this;
            /**
             * Listen for dispatched form builder events, update form builder model
             * with the latest form data.
             */
            $(document).on('formBuilderRendered fieldAdded fieldRemoved fieldEditOpened fieldEditClosed', function formBuilderMainListener(event) {
                formBuilderModel.updateFormData();
            })

            $(document).on('fieldAdded', function formBuilderMainFieldAdded(event) {
                self._rearrangeFieldActions(inputElement);
            })
        },
        _dispatchRenderEvent: function() {
            document.dispatchEvent(new Event('formBuilderRendered'));
        },
        _disableEditorDoubleClick: function(inputElement) {
            $(inputElement).find('.frmb.stage-wrap').off('dblclick');
        },
        /**
         * Form builder does not provide an option to rearrange field actions,
         * this manually moves the field remove element to the end of the actions
         */
        _rearrangeFieldActions: function(inputElement) {
            var $fieldRemove = $(inputElement).find('.field-actions a[type="remove"]');

            $fieldRemove.each(function(index, element) {
                $(element).parent('.field-actions').append(element);
            })
        }
    });
});
