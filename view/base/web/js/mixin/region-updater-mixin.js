define(['jquery'], function ($) {
    'use strict';

    var mixin = {
        /**
         * Mixin handles disabling the sibling text input region field when the
         * select region field is active.
         * @param country
         * @private
         */
        _updateRegion: function(country){
            this._super(country)

            var regionInput = $(this.options.regionInputId);

            if (!regionInput.is(':visible') && !regionInput.attr('disabled')) {
                regionInput.attr('disabled', 'disabled');
            } else {
                regionInput.removeAttr('disabled');
            }
        },
    };

    return function (target) {
        $.widget('mage.regionUpdater', target, mixin)
        return $.mage.regionUpdater;
    };
});
