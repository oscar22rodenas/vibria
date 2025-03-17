"use strict";
(function ($) {
    $(window).on('elementor/frontend/init', function () {
        let KngRotatingAnimationHandler = elementorModules.frontend.handlers.Base.extend({
            onInit: function onInit() {
                if (this.$element.hasClass('kng-rotating-animation-yes')) {
                    this.applyKngRotatingAnimation();
                }
            },
            getReadySettings: function getReadySettings() {
                let settings = {
                    animation_duration: this.getElementSettings('kng_rotating_animation_duration'),
                    animation_delay: this.getElementSettings('kng_rotating_animation_delay')
                };
                return $.extend({}, settings);
            },
            onElementChange: function onElementChange() {
                if (this.$element.hasClass('kng-rotating-animation-yes')) {
                    if (!this.$element.hasClass('kng-style-rotating-animation-applied')) {
                        this.applyKngRotatingAnimation();
                    }
                }
            },
            applyKngRotatingAnimation: function applyKngRotatingAnimation() {
                let options = this.getReadySettings();
                let element_ID = this.$element.data('id');
                $('.kng-style-rotating-animation-' + element_ID).remove();
                this.$element.addClass('kng-style-rotating-animation-applied');
                this.$element.before('<style>@keyframes rotating-animation-' + element_ID + ' {0% {transform: rotate(0deg);} 100% {transform: rotate(360deg)}}' +
                    '.elementor-element-' + element_ID + '.kng-rotating-animation-yes {animation: rotating-animation-' + element_ID + ' ' + options.animation_duration + 'ms linear infinite; animation-delay: ' + options.animation_delay + 'ms;' +
                    '</style>');
            }
        });

        elementorFrontend.hooks.addAction('frontend/element_ready/global', function ($scope) {
            elementorFrontend.elementsHandler.addHandler(KngRotatingAnimationHandler, {
                $element: $scope
            });
        });

    });
})(jQuery);