/**
 * Customizes the form builder element templates to use Magento admin styles
 * @see https://formbuilder.online/docs/formBuilder/overview/#customising-main-layouts
 */
define([
    'jquery',
    'mage/translate',
    'mage/template',
    'text!HappyMachines_CustomForms/template/form/layout-templates/default.html'
], function ($, $t, mageTemplate, defaultTmpl) {
    return {
        default: function(field, label, help, data) {
            var defaultTemplate = mageTemplate(defaultTmpl),
                adminBaseClass = "admin__",
                requiredFieldWrapClass = data.required ? '_required' : '',
                fieldWrapClasses = [requiredFieldWrapClass].join(' '),
                requiredFieldClass = data.required ? 'required-entry' : '',
                fieldClass = adminBaseClass.concat('control-', data.type),
                fieldClasses = [requiredFieldClass, fieldClass].join(" "),
                fieldTemplateData = {
                    outerClass: fieldWrapClasses,
                },
                fieldTemplate = $(defaultTemplate({data: fieldTemplateData}));

            $(field).addClass(fieldClasses);
            $(fieldTemplate).find('.admin__field-control').append(field);

            return $(fieldTemplate);
        }
    }
});
