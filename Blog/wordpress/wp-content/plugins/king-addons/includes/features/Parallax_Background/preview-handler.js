// noinspection SpellCheckingInspection

"use strict";
(function ($) {
    $(window).on('elementor/frontend/init', function () {
        let KngParallaxBackgroundHandler = elementorModules.frontend.handlers.Base.extend({
            onInit: function onInit() {
                if (this.$element.hasClass('kng-parallax-bg-yes')) {
                    this.applyKngParallaxBackground();
                }
            },
            getReadySettings: function getReadySettings() {
                let settings = {
                    kng_parallax_bg_image: this.getElementSettings('kng_parallax_bg_image'),
                    kng_parallax_bg_speed: this.getElementSettings('kng_parallax_bg_speed'),
                    kng_parallax_bg_type: this.getElementSettings('kng_parallax_bg_type')
                };
                return $.extend({}, settings);
            },
            onElementChange: function onElementChange() {
                if (this.$element.hasClass('kng-parallax-bg-yes')) {
                    if (!this.$element.hasClass('kng-parallax-bg-applied')) {
                        this.applyKngParallaxBackground();
                    }
                }
            },
            applyKngParallaxBackground: function applyKngParallaxBackground() {
                let options = this.getReadySettings();
                let element_ID = this.$element.data('id');
                this.$element.addClass('kng-parallax-bg-applied');
                this.$element.before('<style>.king-addons-parallax-container {position: absolute;top: 0;left: 0;width: 100%;height: 100%;z-index: -100 !important;} .jarallax div {will-change: transform;}</style>');
                this.$element.before('<style>.kng-parallax-bg-yes {background-image: none !important;} section:not(.kng-parallax-bg-yes) .king-addons-parallax-container {display: none;}</style>');
                this.$element.append('<div data-jarallax data-speed="' + options.kng_parallax_bg_speed + '" data-type="' + options.kng_parallax_bg_type + '" class="jarallax king-addons-parallax-container ' + 'kng-parallax-bg-' + element_ID + '" style="background-image: url(' + options.kng_parallax_bg_image.url + ');"></div>');
                this.$element.after('<script>jarallax(document.querySelectorAll(".kng-parallax-bg-' + element_ID + '"), {});</script>');
            }
        });

        elementorFrontend.hooks.addAction('frontend/element_ready/global', function ($scope) {
            elementorFrontend.elementsHandler.addHandler(KngParallaxBackgroundHandler, {
                $element: $scope
            });
        });

    });
})(jQuery);