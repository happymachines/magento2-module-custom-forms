define([
    'jquery',
    'HappyMachines_CustomForms/js/form/field/render-handler/address',
], function ($, addressRenderer) {
    'use strict';

    /** Configure the class for runtime loading **/
    if (!window.fbControls) window.fbControls = [];
    window.fbControls.push(function (controlClass, allControlClasses) {
        const controlSelect = allControlClasses.select;

        class controlSelectExtended extends controlSelect {
            onRender() {
                if (this.config.dataTypeAddressField) {
                    addressRenderer(this.dom, this.config);
                }
            }
        }

        controlClass.register('select', controlSelectExtended);

        return controlSelectExtended;
    });
});
