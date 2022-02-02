define([
    'jquery',
    'underscore',
    'HappyMachines_CustomForms/js/model/form-builder',
], function ($, _, formBuilderModel) {

    /**
     * DOM Mutation handler for field select option
     * add or removal
     * @param mutationsList
     */
    var selectOptionAddedHandler = function(mutationsList) {
        _.each(mutationsList, function(mutation) {
            /**
             * A select option has been added or removed
             */
            if (mutation.type === 'childList') {
                var $newOption = $(mutation.addedNodes[0]);

                /**
                 * Bind change handler to dynamically added input
                 */
                $newOption.change(function() {
                    formBuilderModel.updateFormData();
                })

                formBuilderModel.updateFormData();
            }
        })
    }

    /**
     * Form builder does not provide events for select options being
     * add or removed from a field, so we setup a dom mutation observer
     * @private
     */
    return function(field) {
        var $fieldOptions = $(field).find('.field-options ol'),
            mutationConfig = {attributes: true, childList: true, subtree: true},
            observer = new MutationObserver(selectOptionAddedHandler);

        $(document).on('formBuilderRendered fieldAdded fieldRemoved', $(field).selector, function fieldSelectHandler(event) {
            if (event.type === "fieldRemoved") {
                $(document).off('fieldAdded fieldRemoved', fieldSelectHandler);
                observer.disconnect();
            }

            if (event.type === 'fieldAdded' || event.type === 'fieldRendered' || event.type === 'formBuilderRendered') {
                if ($fieldOptions.get(0)) {
                    observer.observe($fieldOptions.get(0), mutationConfig);
                }
            }
        })
    };
})
