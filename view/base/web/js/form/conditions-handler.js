define([
    'jquery',
    'underscore',
], function ($, _) {
    'use strict';

    /**
     * Map comparison value to function
     */
    const comparisonMap = {
        'eq': isEqual,
        'neq': isNotEqual
    }

    /**
     * Compare values for equality
     * @param sourceVal
     * @param conditionVal
     * @returns {boolean}
     */
    function isEqual(sourceVal, conditionVal) {
        if (_.isArray(sourceVal) && _.isArray(conditionVal)) {
            return _.isEqual(sourceVal, conditionVal);
        }

        return sourceVal === conditionVal
    }

    /**
     * Compare values for inequality
     * @param sourceVal
     * @param conditionVal
     * @returns {boolean}
     */
    function isNotEqual(sourceVal, conditionVal) {
        if (_.isArray(sourceVal) && _.isArray(conditionVal)) {
            return !_.isEqual(sourceVal, conditionVal);
        }

        return sourceVal !== conditionVal
    }

    /**
     * Determine element visibility
     * @param $element
     * @returns {boolean}
     */
    function elementIsVisible($element) {
        var isVisible = false;

        if ($element.length) {
            if ($element.outerWidth()) {
                isVisible = true;
            }
        }

        return isVisible;
    }

    /**
     * Get the input value
     * @param input
     * @returns {*}
     */
    function getInputValue(input) {
        var $input = $(input),
            inputValue;

        switch ($input.attr('type')) {
            case 'checkbox':
                var checkboxValues = [],
                    $checkboxGroupInputs = $input.parents('.field').find(':input');

                _.each($checkboxGroupInputs, function(checkbox) {
                    if ($(checkbox).attr('checked')) {
                        checkboxValues.push($(checkbox).val());
                    }
                })
                inputValue = checkboxValues;
                break;
            case 'radio':
                if ($input.attr('checked')) {
                    inputValue = $input.val();
                } else {
                    inputValue = null;
                }
                break;
            default:
                inputValue = $input.val();
        }

        return inputValue;
    }

    /**
     * Handler for visibility field conditions
     * @param condition
     * @param $sourceInput
     * @param $targetInput
     */
    function handleVisibilityChange(condition, $sourceInput, $targetInput) {
        var $targetFieldWrap = $targetInput.parents('.field'),
            sourceFieldValue = getInputValue($sourceInput),
            conditionSourceFieldValue = condition.source.value,
            conditionSourceFieldName = condition.source.fieldName,
            visibilityDataAttribute = 'data-condition-visibility-rules-applied',
            appliedRules = $targetInput.attr(visibilityDataAttribute);

        if (!appliedRules) {
            appliedRules = [];
        } else {
            appliedRules = JSON.parse(appliedRules);
        }

        if (comparisonMap[condition.source.comparison](sourceFieldValue, conditionSourceFieldValue)) {
            if (condition.target.value === 'hidden' && (elementIsVisible($targetFieldWrap) || !_.contains(appliedRules, conditionSourceFieldName))) {
                $targetFieldWrap.hide();
                $targetInput.prop('disabled', true);
                appliedRules.push(conditionSourceFieldName);
            }
        } else {
            if (_.contains(appliedRules, conditionSourceFieldName)) {
                appliedRules = _.without(appliedRules, conditionSourceFieldName);
            }
        }

        $targetInput.attr(visibilityDataAttribute, JSON.stringify(appliedRules));

        if (!appliedRules.length) {
            $targetFieldWrap.show();
            $targetInput.prop('disabled', false);
            $targetInput.removeAttr(visibilityDataAttribute);
        }
    }

    /**
     * Handler for field conditions
     * @param condition
     * @param $sourceField
     * @param $targetField
     */
    function processTargetCondition(condition, $sourceField, $targetField) {
        handleVisibilityChange(condition, $sourceField, $targetField);
    }

    return function($form, formData) {
        var fieldConditions = [];

        _.each(formData, function(fieldData) {
            if (fieldData.dataConditions) {
                fieldConditions.push(JSON.parse(fieldData.dataConditions));
            }
        })
        _.each(fieldConditions, function(conditions) {
            _.each(conditions, function(condition) {
                var fieldSelector = '.field.%fieldName%',
                    sourceFieldSelector = fieldSelector.replace('%fieldName%', condition.source.fieldName),
                    targetFieldSelector = fieldSelector.replace('%fieldName%', condition.target.fieldName),
                    $sourceField = $form.find(sourceFieldSelector),
                    $sourceInput = $sourceField.find(':input'),
                    $targetField = $form.find(targetFieldSelector),
                    $targetInput = $targetField.find(':input');

                _.each($sourceInput, function(input) {
                    var $input = $(input);

                    processTargetCondition(condition, $input, $targetInput)

                    $input.change(function() {
                        processTargetCondition(condition, $input, $targetInput);
                    })
                })
            })
        });
    };
});
