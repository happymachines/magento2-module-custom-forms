/**
 * Define custom attributes that can be applied via the form builder
 *
 * @see https://formbuilder.online/docs/formBuilder/options/onAddField/
 */
define([
    'jquery',
    'HappyMachines_CustomForms/js/form/builder-options/event-handler/bind-conditions-component',
    'HappyMachines_CustomForms/js/form/builder-options/event-handler/field-input-edit-observer',
    'HappyMachines_CustomForms/js/form/builder-options/event-handler/field-select-options-edit-observer',
], function ($, bindConditionsComponent, fieldInputEditObserver, fieldSelectOptionsEditObserver) {

    /**
     * Handler for form builder field onAddField event
     * @param field
     */
    var typeInputOnAddHandler = function(field) {
        fieldInputEditObserver(field)
    }

    /**
     * Handler for form builder select field onAddField event
     * @param field
     */
    var typeSelectOnAddHandler = function(field) {
        bindConditionsComponent(field);
        fieldInputEditObserver(field)
        fieldSelectOptionsEditObserver(field);
    }

    return {
        header: {
            onadd: typeInputOnAddHandler
        },
        paragraph: {
            onadd: typeInputOnAddHandler
        },
        text: {
            onadd: typeInputOnAddHandler
        },
        textarea: {
            onadd: typeInputOnAddHandler
        },
        select: {
            onadd: typeSelectOnAddHandler
        },
        'radio-group': {
            onadd: typeSelectOnAddHandler
        },
        checkbox: {
            onadd: typeSelectOnAddHandler
        },
        'checkbox-group': {
            onadd: typeSelectOnAddHandler
        },
        file: {
            onadd: typeInputOnAddHandler
        },
        number: {
            onadd: typeInputOnAddHandler
        },
        date: {
            onadd: typeInputOnAddHandler
        },
    }
});
