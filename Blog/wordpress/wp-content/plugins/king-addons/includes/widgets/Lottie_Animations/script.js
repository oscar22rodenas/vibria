"use strict";
(($) => {
    $(window).on("elementor/frontend/init", () => {
        elementorFrontend.hooks.addAction(
            "frontend/element_ready/king-addons-lottie-animations.default",
            ($scope) => {
                elementorFrontend.elementsHandler.addHandler(
                    elementorModules.frontend.handlers.Base.extend({
                        onInit() {
                            // Grab the Lottie wrapper and parse its settings
                            const $lottie = this.$element.find(".king-addons-lottie-animations");
                            const settings = JSON.parse($lottie.attr("data-settings") || "{}");

                            // Create the Lottie animation
                            // noinspection JSUnresolvedReference
                            const animation = lottie.loadAnimation({
                                container: $lottie[0],
                                path: $lottie.attr("data-json-url"),
                                renderer: settings.lottie_renderer,
                                loop: settings.loop === "yes",
                                autoplay: settings.autoplay === "yes",
                            });

                            // Set speed and direction if necessary
                            animation.setSpeed(settings.speed || 1);
                            if (settings.reverse) animation.setDirection(-1);

                            // Listen for Lottie to be fully loaded
                            animation.addEventListener("DOMLoaded", () => {
                                // If trigger is not hover or none, initialize scroll behavior
                                if (!["hover", "none"].includes(settings.trigger)) {
                                    updateOnScroll();
                                    $(window).on("scroll", updateOnScroll);
                                }

                                // Handle hover trigger
                                if (settings.trigger === "hover") {
                                    animation.pause();
                                    $lottie.hover(
                                        () => animation.play(),
                                        () => animation.pause()
                                    );
                                }
                            });

                            // Function to handle viewport or scroll triggers
                            const updateOnScroll = () => {
                                animation.pause();
                                if (typeof $lottie[0].getBoundingClientRect === "function") {
                                    const rect = $lottie[0].getBoundingClientRect();
                                    const viewportHeight = document.documentElement.clientHeight;
                                    const scrollTopPercent = (rect.top / viewportHeight) * 100;
                                    const scrollBottomPercent = (rect.bottom / viewportHeight) * 100;
                                    // noinspection JSUnresolvedReference
                                    const inStart = scrollBottomPercent > settings.scroll_start;
                                    // noinspection JSUnresolvedReference
                                    const inEnd = scrollTopPercent < settings.scroll_end;

                                    // Viewport trigger
                                    if (settings.trigger === "viewport") {
                                        inStart && inEnd ? animation.play() : animation.pause();
                                    }

                                    // Scroll trigger
                                    else if (settings.trigger === "scroll" && inStart && inEnd) {
                                        const scrollPercent =
                                            (100 * $(window).scrollTop()) /
                                            ($(document).height() - $(window).height());
                                        animation.goToAndStop((Math.round(scrollPercent) / 100) * 4000);
                                    }
                                }
                            };
                        },
                    }),
                    {$element: $scope}
                );
            }
        );
    });
})(jQuery);