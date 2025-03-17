"use strict";
(function ($) {
    $(window).on("elementor/frontend/init", function () {
        elementorFrontend.hooks.addAction(
            "frontend/element_ready/king-addons-content-ticker.default",
            function ($scope) {
                // Register a new handler extending Elementor’s Base handler
                elementorFrontend.elementsHandler.addHandler(
                    elementorModules.frontend.handlers.Base.extend({
                        onInit() {
                            const $element = this.$element;

                            const $contentTickerSlider = $element.find(".king-addons-ticker-slider"),
                                $contentTickerMarquee = $element.find(".king-addons-ticker-marquee"),
                                marqueeData = $contentTickerMarquee.data("options"),
                                sliderClass = $element.attr("class"),
                                dataSlideEffect = $contentTickerSlider.attr("data-slide-effect");

                            // Helper to capture a single digit at the end of a matched pattern
                            const getClassNumber = (regex, fallback) => {
                                const match = sliderClass.match(regex);
                                return match ? parseInt(match[1], 10) : fallback;
                            };

                            // Determine how many columns to use in different breakpoints
                            const sliderColumnsDesktop = getClassNumber(/king-addons-ticker-slider-columns-(\d)/, 2);
                            const sliderColumnsWideScreen = getClassNumber(/columns--widescreen(\d)/, sliderColumnsDesktop);
                            const sliderColumnsLaptop = getClassNumber(/columns--laptop(\d)/, sliderColumnsDesktop);
                            const sliderColumnsTablet = getClassNumber(/columns--tablet(\d)/, 2);
                            const sliderColumnsTabletExtra = getClassNumber(/columns--tablet_extra(\d)/, sliderColumnsTablet);
                            const sliderColumnsMobileExtra = getClassNumber(/columns--mobile_extra(\d)/, sliderColumnsTablet);
                            const sliderColumnsMobile = getClassNumber(/columns--mobile(\d)/, 1);

                            // Slides to scroll logic if the slider effect is "hr-slide"
                            const sliderSlidesToScroll =
                                dataSlideEffect === "hr-slide"
                                    ? getClassNumber(/king-addons-ticker-slides-to-scroll-(\d)/, 1)
                                    : 1;

                            // Check if slide effect is typing or fade
                            const isTypingOrFade = dataSlideEffect === "typing" || dataSlideEffect === "fade";

                            // Short helper to decide slidesToShow and slidesToScroll
                            const getSlidesToShow = columns => (isTypingOrFade ? 1 : columns);
                            const getSlidesToScroll = columns => (sliderSlidesToScroll > columns ? 1 : sliderSlidesToScroll);

                            // Slick settings with breakpoints
                            $contentTickerSlider.slick({
                                appendArrows: $element.find(".king-addons-ticker-slider-controls"),
                                slidesToShow: sliderColumnsDesktop,
                                responsive: [
                                    {
                                        breakpoint: 10000,
                                        settings: {
                                            slidesToShow: getSlidesToShow(sliderColumnsWideScreen),
                                            slidesToScroll: getSlidesToScroll(sliderColumnsWideScreen),
                                            fade: isTypingOrFade,
                                        },
                                    },
                                    {
                                        breakpoint: 2399,
                                        settings: {
                                            slidesToShow: getSlidesToShow(sliderColumnsDesktop),
                                            slidesToScroll: getSlidesToScroll(sliderColumnsDesktop),
                                            fade: isTypingOrFade,
                                        },
                                    },
                                    {
                                        breakpoint: 1221,
                                        settings: {
                                            slidesToShow: getSlidesToShow(sliderColumnsLaptop),
                                            slidesToScroll: getSlidesToScroll(sliderColumnsLaptop),
                                            fade: isTypingOrFade,
                                        },
                                    },
                                    {
                                        breakpoint: 1200,
                                        settings: {
                                            slidesToShow: getSlidesToShow(sliderColumnsTabletExtra),
                                            slidesToScroll: getSlidesToScroll(sliderColumnsTabletExtra),
                                            fade: isTypingOrFade,
                                        },
                                    },
                                    {
                                        breakpoint: 1024,
                                        settings: {
                                            slidesToShow: getSlidesToShow(sliderColumnsTablet),
                                            slidesToScroll: getSlidesToScroll(sliderColumnsTablet),
                                            fade: isTypingOrFade,
                                        },
                                    },
                                    {
                                        breakpoint: 880,
                                        settings: {
                                            slidesToShow: getSlidesToShow(sliderColumnsMobileExtra),
                                            slidesToScroll: getSlidesToScroll(sliderColumnsMobileExtra),
                                            fade: isTypingOrFade,
                                        },
                                    },
                                    {
                                        breakpoint: 768,
                                        settings: {
                                            slidesToShow: getSlidesToShow(sliderColumnsMobile),
                                            slidesToScroll: getSlidesToScroll(sliderColumnsMobile),
                                            fade: isTypingOrFade,
                                        },
                                    },
                                ],
                            });

                            // Initialize marquee
                            $contentTickerMarquee.marquee(marqueeData);

                            // If marquee is hidden, remove the hidden class
                            if ($element.find(".king-addons-marquee-hidden").length) {
                                $element
                                    .find(".king-addons-ticker-marquee")
                                    .removeClass("king-addons-marquee-hidden");
                            }
                        },
                    }),
                    {
                        $element: $scope,
                    }
                );
            }
        );
    });
})(jQuery);