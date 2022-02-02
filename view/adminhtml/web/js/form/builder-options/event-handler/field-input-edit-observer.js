define([
    'jquery',
    'underscore',
    'HappyMachines_CustomForms/js/model/form-builder',
], function ($, _, formBuilderModel) {

    /**
     * Add change events to contenteditable elements in the form builder
     */
    var addContentEditableChangeEvent = function(field) {
        $(field).on('focus', '[contenteditable]', function() {
            var $this = $(this);
            $this.data('before', $this.html());
        }).on('blur', '[contenteditable]', function() {
            var $this = $(this);
            if ($this.data('before') !== $this.html()) {
                $this.data('before', $this.html());
                $this.trigger('change');
            }
        });
    }
    /**
     * Form builder does not provide events for select options being
     * add or removed from a field, so we setup a dom mutation observer
     * @private
     */
    return function(field) {
        addContentEditableChangeEvent(field);

        $(field).find(':input, [contenteditable]').change(function() {
            formBuilderModel.updateFormData();
        });
    };
})
