define([
    'jquery',
    'ko',
    'uiComponent',
    'underscore',
    'HappyMachines_CustomForms/js/model/form-builder',
    'HappyMachines_CustomForms/js/form/field/attribute/model/condition'
], function ($, ko, Component, _, formBuilderModel, conditionModel) {
    'use strict';

    const disallowedFieldTypes = [
        'header',
        'paragraph'
    ];

    return Component.extend({
        defaults: {
            template: 'HappyMachines_CustomForms/form/field/attribute/conditions'
        },
        initialize: function (config, element) {
            this._super();

            this.$element = $(element);
            this.$dataConditionsInput = this.$element.find("[name='dataConditions']");

            this._initializeObservables();
            this._initializeSubscribers();

            return this;
        },
        _initializeObservables: function() {
            var self = this;

            /**
             * Observables
             */
            this.conditions = ko.observableArray();
            this.conditionsDataInitialized = ko.observable(false);
            this.fieldOptionCaption = ko.observable('Choose a field');
            this.comparisonOptionCaption = ko.observable('Choose a comparison');
            this.propertyOptionCaption = ko.observable('Choose a property');
            this.valueOptionCaption = ko.observable('Choose a value');
            this.ifConditionComparisonOptions = ko.observableArray(
                [
                    {'label': 'Is', 'type': 'eq'},
                    {'label': 'Is not', 'type': 'neq'}
                ]
            );
            this.thenConditionComparisonOptions = ko.observableArray(
                [
                    {'label': 'Is', 'type': 'eq'}
                ]
            );
            this.ifConditionTargetPropertyOptions = ko.observableArray(
                [
                    {'label': 'Value', 'type': 'value'}
                ]
            );
            this.thenConditionTargetPropertyOptions = ko.observableArray(
                [
                    {'label': 'Visibility', 'type': 'visibility'}
                ]
            );
            this.thenConditionTargetValueSelectOptions = ko.observableArray(
                [
                    {'label': 'Hidden', 'type': 'hidden'}
                ]
            );
            /**
             * Dynamic list of all form builder fields as condition
             * target field options
             * @type {computedObservable}
             */
            this.thenConditionFieldOptions = ko.computed(function() {
                var formData = formBuilderModel.formData(),
                    fieldOptions = [],
                    thisFormFieldName = self._getElementFieldName();

                if (formData) {
                    _.each(JSON.parse(formData), function(field) {
                        if (!_.contains(disallowedFieldTypes, field.type) && field.name !== thisFormFieldName) {
                            fieldOptions.push(field);
                        }
                    })
                }

                return fieldOptions;
            });
            this.isMultiselect = ko.computed(function() {
                var formData = formBuilderModel.formData(),
                    thisFormFieldName = self._getElementFieldName(),
                    fieldElement = null;

                if (formData) {
                    fieldElement = _.find(JSON.parse(formData), function(field) {
                        return field.name === thisFormFieldName
                    })

                    if (fieldElement) {
                        return fieldElement.multiple || fieldElement.type === 'checkbox-group'
                    }
                }

                return false;
            })
            this.conditionsData = ko.computed(function() {
                var conditionsData = [];

                _.each(self.conditions(), function(condition) {
                    /**
                     * Check condition data has been completely set
                     */
                    if (condition.ifConditionSourceField() && condition.thenConditionSourceField() &&
                        condition.ifConditionTargetProperty() && condition.thenConditionTargetProperty() &&
                        condition.ifConditionTargetValue() && condition.thenConditionTargetValue()) {
                        var conditionData = {
                            source: {
                                fieldName: condition.ifConditionSourceField(),
                                property: condition.ifConditionTargetProperty(),
                                comparison: condition.ifConditionComparisonOption(),
                                value: condition.ifConditionTargetValue(),
                            },
                            target: {
                                fieldName: condition.thenConditionSourceField(),
                                property: condition.thenConditionTargetProperty(),
                                comparison: condition.thenConditionComparisonOption(),
                                value: condition.thenConditionTargetValue()
                            }
                        }
                        conditionsData.push(conditionData);
                    }
                })

                return conditionsData;
            })
        },
        /**
         * Initializes data subscribers
         * @private
         */
        _initializeSubscribers: function() {
            var self = this;

            formBuilderModel.formData.subscribe(function() {
                if (!self.conditionsDataInitialized()) {
                    /**
                     * Initialization occurs here because the json data may not be ready for initialization
                     * upon component initial load, the subscription ensures the data is available
                     */
                    self._initializeConditionsData();
                }
            });

            self.conditionsData.subscribe(function() {
                if (self.conditionsDataInitialized()) {
                    self.$dataConditionsInput.val(JSON.stringify(self.conditionsData()));
                }
            })
        },
        /**
         * Initializes conditions data from the saved form builder data if available
         * @private
         */
        _initializeConditionsData: function () {
            var self = this,
                formData = formBuilderModel.formData(),
                fieldName = this._getElementFieldName();

            if (formData) {
                var fieldObject = _.find(JSON.parse(formData), function(field) {
                    return field.name === fieldName;
                })

                if (fieldObject && fieldObject.dataConditions) {
                    var dataConditions = JSON.parse(fieldObject.dataConditions);

                    _.each(dataConditions, function(conditionData) {
                        var condition = new conditionModel();

                        condition.ifConditionSourceField(conditionData.source.fieldName);
                        condition.ifConditionTargetProperty(conditionData.source.property);
                        condition.ifConditionComparisonOption(conditionData.source.comparison);
                        condition.ifConditionTargetValue(conditionData.source.value);
                        condition.thenConditionSourceField(conditionData.target.fieldName);
                        condition.thenConditionTargetProperty(conditionData.target.property);
                        condition.thenConditionComparisonOption(conditionData.target.comparison);
                        condition.thenConditionTargetValue(conditionData.target.value);

                        self.conditions.push(condition);
                    })
                }
            }

            self.conditionsDataInitialized(true);
        },
        /**
         * Get the form builder input field name that is associated with the condition component
         * @returns {string}
         * @private
         */
        _getElementFieldName: function() {
            var formBuilderFieldSuffix = '-preview';

            /**
             * Traverses up from the conditional field editor to the parent form editor,
             * then moves to the sibling form field preview element, finally grabs the input element
             */
            var fieldInput = this.$element.parents('.frm-holder').prev('.prev-holder').find(':input'),
                fieldName = fieldInput.attr('name'),
                suffixIndex = fieldName.lastIndexOf(formBuilderFieldSuffix);

            return fieldName.substring(0, suffixIndex);
        },
        /**
         * Adds a new condition
         * @returns {boolean}
         */
        addNewCondition: function() {
            var newCondition = new conditionModel;

            /** New condition, source field defaults to using the current field element **/
            newCondition.ifConditionSourceField(this._getElementFieldName());

            this.conditions.push(newCondition);

            return false;
        },
        /**
         * Removes the condition
         * @param condition
         * @returns {boolean}
         */
        removeCondition: function(condition) {
            this.conditions.remove(condition);

            return false;
        },
        getIfConditionLabel: function(fieldName) {
            var formData = formBuilderModel.formData(),
                fieldObject = null,
                label = 'If This Field';

            if (formData && fieldName) {
                fieldObject = _.find(JSON.parse(formData), function(field) {
                    return field.name === fieldName;
                })
            }

            if (fieldObject) {
                label = 'If ' + fieldObject.label;
            }

            return label;
        },
        /**
         * Get a dynamic list of value options for the condition
         * based on the current source field
         * @param condition
         * @returns {[]}
         */
        getIfConditionTargetValueSelectOptions: function(condition) {
            var formData = formBuilderModel.formData(),
                fieldObject = null,
                fieldOptions = [];

            if (formData && condition.ifConditionSourceField()) {
                fieldObject = _.find(JSON.parse(formData), function(field) {
                    return field.name === condition.ifConditionSourceField();
                })
            }

            condition.ifConditionSourceFieldObject(fieldObject);

            if (condition.ifConditionSourceFieldObject()) {
                _.each(condition.ifConditionSourceFieldObject().values, function(value) {
                    fieldOptions.push(value);
                })
            }

            return fieldOptions;
        },
        toggleDelete: function(condition, event) {
            var $target = $(event.target),
                $condition = $target.parents('.condition');

            if (!$condition.hasClass('delete')) {
                $condition.addClass('delete');
            } else {
                $condition.removeClass('delete');
            }
        }
    });
});
