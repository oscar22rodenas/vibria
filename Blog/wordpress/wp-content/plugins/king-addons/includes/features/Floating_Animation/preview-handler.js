"use strict";
(function ($) {
    $(window).on('elementor/frontend/init', function () {
        let KngFloatingAnimationHandler = elementorModules.frontend.handlers.Base.extend({
            onInit: function onInit() {
                if (this.$element.hasClass('kng-floating-animation-yes')) {
                    this.applyKngFloatingAnimation();
                }
            },
            getReadySettings: function getReadySettings() {
                let settings = {
                    animation_value_X: this.getElementSettings('kng_floating_animation_value_X'),
                    animation_value: this.getElementSettings('kng_floating_animation_value'),
                    animation_duration: this.getElementSettings('kng_floating_animation_duration'),
                    animation_delay: this.getElementSettings('kng_floating_animation_delay')
                };
                return $.extend({}, settings);
            },
            onElementChange: function onElementChange() {
                if (this.$element.hasClass('kng-floating-animation-yes')) {
                    if (!this.$element.hasClass('kng-style-floating-animation-applied')) {
                        this.applyKngFloatingAnimation();
                    }
                }
            },
            applyKngFloatingAnimation: function applyKngFloatingAnimation() {
                let options = this.getReadySettings();
                let element_ID = this.$element.data('id');
                $('.kng-style-floating-animation-' + element_ID).remove();
                this.$element.addClass('kng-style-floating-animation-applied');
                this.$element.before('<style class="kng-style-floating-animation-' + element_ID + '">' +
                    '@keyframes floating-animation-' + element_ID + ' {0% {transform: translate(0, 0);} 50% {transform: translate(' + options.animation_value_X + 'px, ' + options.animation_value + 'px);} 100% {transform: translate(0, 0);}} ' +
                    '.elementor-element-' + element_ID + '.kng-floating-animation-yes {animation: floating-animation-' + element_ID + ' ' + options.animation_duration + 'ms ease-in-out infinite;' +
                    'animation-delay:' + options.animation_delay + 'ms;}' +
                    '</style>');
            }
        });

        elementorFrontend.hooks.addAction('frontend/element_ready/global', function ($scope) {
            elementorFrontend.elementsHandler.addHandler(KngFloatingAnimationHandler, {
                $element: $scope
            });
        });

    });
})(jQuery);