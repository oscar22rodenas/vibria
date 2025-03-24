"use strict";
(function ($) {
    $(window).on('elementor/frontend/init', function () {
        let KngCustomCSSHandler = elementorModules.frontend.handlers.Base.extend({
            onInit: function onInit() {
                if (this.$element.hasClass('kng-custom-css-yes')) {
                    this.applyKngCustomCSS();
                }
            },
            getReadySettings: function getReadySettings() {
                let settings = {
                    kng_custom_css: this.getElementSettings('kng_custom_css')
                };
                return $.extend({}, settings);
            },
            onElementChange: function onElementChange() {
                if (this.$element.hasClass('kng-custom-css-yes')) {
                    this.applyKngCustomCSS();
                } else {
                    let element_ID = this.$element.data('id');
                    $('.kng-custom-css-' + element_ID).remove();
                }
            },
            applyKngCustomCSS: function applyKngCustomCSS() {
                let options = this.getReadySettings();
                let element_ID = this.$element.data('id');
                $('.kng-custom-css-' + element_ID).remove();
                this.$element.before('<style class="kng-custom-css-' + element_ID + '">' + options.kng_custom_css.replace("[current-element]", '.elementor-element-' + element_ID) + '</style>');
            }
        });

        elementorFrontend.hooks.addAction('frontend/element_ready/global', function ($scope) {
            elementorFrontend.elementsHandler.addHandler(KngCustomCSSHandler, {
                $element: $scope
            });
        });

    });
})(jQuery);