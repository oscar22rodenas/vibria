"use strict";
(function ($) {
    $(window).on('elementor/frontend/init', function () {
        // Define the custom handler for the Feature List line adjustments
        let FeatureListLineHandler = elementorModules.frontend.handlers.Base.extend({
            onInit: function onInit() {
                this.adjustFeatureListLines();
                $(window).resize(this.adjustFeatureListLines.bind(this));
            },

            adjustFeatureListLines: function adjustFeatureListLines() {
                let $scope = this.$element;

                $scope.find('.king-addons-feature-list-item:not(:last-of-type)').find('.king-addons-feature-list-icon-wrap').each(function (index) {
                    let nextOffsetTop = $scope.find('.king-addons-feature-list-item').eq(index + 1).find('.king-addons-feature-list-icon-wrap').offset().top;
                    let currentOffsetTop = $(this).offset().top;

                    // Calculate the line height
                    let lineHeight = nextOffsetTop - currentOffsetTop;

                    // Apply the calculated height to the line
                    $(this).find('.king-addons-feature-list-line').css('height', lineHeight + 'px');
                });
            }
        });

        // Initialize the handler when the specific widget is ready in Elementor
        elementorFrontend.hooks.addAction('frontend/element_ready/king-addons-feature-list.default', function ($scope) {
            elementorFrontend.elementsHandler.addHandler(FeatureListLineHandler, {
                $element: $scope
            });
        });
    });
})(jQuery);