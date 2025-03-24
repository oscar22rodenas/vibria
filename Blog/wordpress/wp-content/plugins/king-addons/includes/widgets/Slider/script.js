"use strict";

(function ($) {
    $(window).on("elementor/frontend/init", function () {
        elementorFrontend.hooks.addAction(
            "frontend/element_ready/king-addons-slider.default",
            function ($scope) {
                elementorFrontend.elementsHandler.addHandler(
                    elementorModules.frontend.handlers.Base.extend({
                        onInit: function onInit() {
                            const $slider = this.$element.find(".king-addons-advanced-slider");
                            const sliderData = $slider.data("slick");
                            const videoBtnSize = $slider.data("video-btn-size");
                            const dataSlideEffect = $slider.attr("data-slide-effect") || "slide";

                            // Capture class names
                            const sliderClass = this.$element.attr("class") || "";

                            // Helper to match columns or slides from class
                            const matchNumber = (pattern, fallback) => {
                                const match = sliderClass.match(pattern);
                                return match ? +match[0].replace(/\D/g, "") : fallback;
                            };

                            // Extract columns from class
                            const sliderColumnsDesktop = matchNumber(/king-addons-adv-slider-columns-\d/, 2);
                            const sliderColumnsWideScreen = matchNumber(/columns--widescreen\d/, sliderColumnsDesktop);
                            const sliderColumnsLaptop = matchNumber(/columns--laptop\d/, sliderColumnsDesktop);
                            const sliderColumnsTabletExtra = matchNumber(/columns--tablet_extra\d/, 2);
                            const sliderColumnsTablet = matchNumber(/columns--tablet\d/, sliderColumnsTabletExtra);
                            const sliderColumnsMobileExtra = matchNumber(/columns--mobile_extra\d/, sliderColumnsTablet);
                            const sliderColumnsMobile = matchNumber(/columns--mobile\d/, 1);
                            const sliderSlidesToScroll = matchNumber(/king-addons-adv-slides-to-scroll-\d/, 1);

                            // Helper to determine if 'fade' effect applies
                            const isFade = (cols) => cols === 1 && dataSlideEffect === "fade";

                            // Initialize slick
                            $slider.slick({
                                ...sliderData, // in case there are any relevant data options
                                appendArrows: this.$element.find(".king-addons-slider-controls"),
                                appendDots: this.$element.find(".king-addons-slider-dots"),
                                customPaging: () => '<span class="king-addons-slider-dot"></span>',
                                slidesToShow: sliderColumnsDesktop,
                                responsive: [
                                    {
                                        breakpoint: 10000,
                                        settings: {
                                            slidesToShow: sliderColumnsWideScreen,
                                            slidesToScroll: sliderSlidesToScroll > sliderColumnsWideScreen ? 1 : sliderSlidesToScroll,
                                            fade: isFade(sliderColumnsWideScreen),
                                        },
                                    },
                                    {
                                        breakpoint: 2399,
                                        settings: {
                                            slidesToShow: sliderColumnsDesktop,
                                            slidesToScroll: sliderSlidesToScroll > sliderColumnsDesktop ? 1 : sliderSlidesToScroll,
                                            fade: isFade(sliderColumnsDesktop),
                                        },
                                    },
                                    {
                                        breakpoint: 1221,
                                        settings: {
                                            slidesToShow: sliderColumnsLaptop,
                                            slidesToScroll: sliderSlidesToScroll > sliderColumnsLaptop ? 1 : sliderSlidesToScroll,
                                            fade: isFade(sliderColumnsLaptop),
                                        },
                                    },
                                    {
                                        breakpoint: 1200,
                                        settings: {
                                            slidesToShow: sliderColumnsTabletExtra,
                                            slidesToScroll: sliderSlidesToScroll > sliderColumnsTabletExtra ? 1 : sliderSlidesToScroll,
                                            fade: isFade(sliderColumnsTabletExtra),
                                        },
                                    },
                                    {
                                        breakpoint: 1024,
                                        settings: {
                                            slidesToShow: sliderColumnsTablet,
                                            slidesToScroll: sliderSlidesToScroll > sliderColumnsTablet ? 1 : sliderSlidesToScroll,
                                            fade: isFade(sliderColumnsTablet),
                                        },
                                    },
                                    {
                                        breakpoint: 880,
                                        settings: {
                                            slidesToShow: sliderColumnsMobileExtra,
                                            slidesToScroll: sliderSlidesToScroll > sliderColumnsMobileExtra ? 1 : sliderSlidesToScroll,
                                            fade: isFade(sliderColumnsMobileExtra),
                                        },
                                    },
                                    {
                                        breakpoint: 768,
                                        settings: {
                                            slidesToShow: sliderColumnsMobile,
                                            slidesToScroll: sliderSlidesToScroll > sliderColumnsMobile ? 1 : sliderSlidesToScroll,
                                            fade: isFade(sliderColumnsMobile),
                                        },
                                    },
                                ],
                            });

                            // Helper to update slider height
                            const updateSliderHeight = () => {
                                $slider.css("height", this.$element.find(".slick-current").outerHeight());
                            };

                            // If there's an image, adjust slider height on arrow click & resize
                            if (this.$element.find(".king-addons-slider-img").length) {
                                updateSliderHeight();
                                this.$element.find(".king-addons-slider-arrow").on("click", updateSliderHeight);
                                // noinspection JSUnresolvedReference
                                $(window).smartresize(updateSliderHeight);
                            }

                            // Handle sizing of video icons and iframes
                            const sliderVideoSize = () => {
                                const $items = this.$element.find(".king-addons-slider-item");
                                $slider.find("iframe").attr({
                                    width: $items.width(),
                                    height: $items.height(),
                                });

                                // Identify breakpoints from Elementor
                                const viewportWidth = $(window).outerWidth();
                                const {
                                    mobile,
                                    mobile_extra,
                                    tablet,
                                    tablet_extra,
                                    laptop,
                                    widescreen,
                                } = elementorFrontend.config.responsive.breakpoints;
                                const activeBPs = elementorFrontend.config.responsive.activeBreakpoints;

                                // Remove existing video-icon-size classes
                                [...this.$element[0].classList].forEach((className) => {
                                    if (className.startsWith("king-addons-slider-video-icon-size-")) {
                                        this.$element[0].classList.remove(className);
                                    }
                                });

                                // Add new video-icon-size class based on breakpoint
                                if (viewportWidth <= mobile.value && activeBPs.mobile) {
                                    this.$element.addClass("king-addons-slider-video-icon-size-" + videoBtnSize.mobile);
                                } else if (viewportWidth <= mobile_extra.value && activeBPs.mobile_extra) {
                                    this.$element.addClass("king-addons-slider-video-icon-size-" + videoBtnSize.mobile_extra);
                                } else if (viewportWidth <= tablet.value && activeBPs.tablet) {
                                    this.$element.addClass("king-addons-slider-video-icon-size-" + videoBtnSize.tablet);
                                } else if (viewportWidth <= tablet_extra.value && activeBPs.tablet_extra) {
                                    this.$element.addClass("king-addons-slider-video-icon-size-" + videoBtnSize.tablet_extra);
                                } else if (viewportWidth <= laptop.value && activeBPs.laptop) {
                                    this.$element.addClass("king-addons-slider-video-icon-size-" + videoBtnSize.laptop);
                                } else if (viewportWidth < widescreen.value) {
                                    this.$element.addClass("king-addons-slider-video-icon-size-" + videoBtnSize.desktop);
                                } else {
                                    this.$element.addClass("king-addons-slider-video-icon-size-" + videoBtnSize.widescreen);
                                }
                            };

                            $(window).on("load resize", sliderVideoSize);

                            // Autoplay for embedded videos
                            const autoplayVideo = () => {
                                $slider.find(".slick-current").each(function () {
                                    const $current = $(this).find(".king-addons-slider-item");
                                    const videoSrc = $current.attr("data-video-src") || "";
                                    const videoAutoplay = $current.attr("data-video-autoplay");

                                    if (
                                        $(this).find(".king-addons-slider-video").length < 1 &&
                                        videoAutoplay === "yes"
                                    ) {
                                        if (videoSrc.includes("vimeo") || videoSrc.includes("youtube")) {
                                            // If single column, embed in .king-addons-cv-inner
                                            if (sliderColumnsDesktop === 1) {
                                                $(this)
                                                    .find(".king-addons-cv-inner")
                                                    .prepend(
                                                        `<div class="king-addons-slider-video">
                              <iframe src="${videoSrc}" allow="autoplay" allowfullscreen></iframe>
                             </div>`
                                                    );
                                            } else {
                                                // Multiple columns
                                                $(this)
                                                    .find(".king-addons-cv-container")
                                                    .prepend(
                                                        `<div class="king-addons-slider-video">
                              <iframe src="${videoSrc}" width="100%" height="100%"
                                allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" 
                                allowfullscreen>
                              </iframe>
                             </div>`
                                                    );
                                            }
                                            sliderVideoSize();
                                        } else {
                                            // Custom video
                                            const videoMute = $current.attr("data-video-mute") || "";
                                            const videoControls = $current.attr("data-video-controls") || "";
                                            const videoLoop = $current.attr("data-video-loop") || "";
                                            $(this)
                                                .find(".king-addons-cv-inner")
                                                .prepend(
                                                    `<div class="king-addons-slider-video king-addons-custom-video">
                            <video autoplay ${videoLoop} ${videoMute} ${videoControls} 
                              src="${videoSrc}" width="100%" height="100%">
                            </video>
                           </div>`
                                                );

                                            $slider.find("video").attr({
                                                width: $(this).find(".king-addons-slider-item").width(),
                                                height: $(this).find(".king-addons-slider-item").height(),
                                            });
                                        }

                                        // Hide content if video is autoplaying
                                        if ($(this).find(".king-addons-slider-content")) {
                                            $(this).find(".king-addons-slider-content").fadeOut(300);
                                        }
                                    }
                                });
                            };

                            // Slide animations
                            const slideAnimationOff = () => {
                                if (sliderColumnsDesktop === 1) {
                                    $slider
                                        .find(".king-addons-slider-item")
                                        .not(".slick-active")
                                        .find(".king-addons-slider-animation")
                                        .removeClass("king-addons-animation-enter");
                                }
                            };

                            const slideAnimationOn = () => {
                                $slider.find(".slick-active, .slick-cloned, .slick-current").find(".king-addons-slider-content").fadeIn(0);
                                if (sliderColumnsDesktop === 1) {
                                    $slider.find(".slick-active .king-addons-slider-animation").addClass("king-addons-animation-enter");
                                }
                            };

                            // Initial setup
                            slideAnimationOn();
                            this.$element.find(".slick-current").addClass("king-addons-slick-visible");
                            $slider.css("opacity", 1);
                            autoplayVideo();

                            // Video play button
                            $slider.on("click", ".king-addons-slider-video-btn", function () {
                                const $currentSlide = $(this).closest(".slick-slide");
                                let videoSrc = $currentSlide.find(".king-addons-slider-item").attr("data-video-src") || "";

                                if (videoSrc.includes("youtube")) {
                                    videoSrc += "&autoplay=1";
                                }

                                if (videoSrc.includes("youtube") || videoSrc.includes("vimeo")) {
                                    const allowFullScreen = videoSrc.includes("youtube")
                                        ? 'allowfullscreen="allowfullscreen"'
                                        : "allowfullscreen";

                                    if ($currentSlide.find(".king-addons-slider-video").length < 1) {
                                        $currentSlide
                                            .find(".king-addons-cv-container")
                                            .prepend(
                                                `<div class="king-addons-slider-video">
                          <iframe src="${videoSrc}" width="100%" height="100%"
                            allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" 
                            ${allowFullScreen}>
                          </iframe>
                         </div>`
                                            );
                                        sliderVideoSize();
                                        // noinspection JSValidateTypes
                                        $currentSlide.find(".king-addons-slider-content").fadeOut(300);
                                    }
                                } else {
                                    // Custom video
                                    const videoMute = $currentSlide.find(".king-addons-slider-item").attr("data-video-mute") || "";
                                    const videoControls = $currentSlide.find(".king-addons-slider-item").attr("data-video-controls") || "";
                                    const videoLoop = $currentSlide.find(".king-addons-slider-item").attr("data-video-loop") || "";

                                    if ($currentSlide.find(".king-addons-slider-video").length < 1) {
                                        $currentSlide
                                            .find(".king-addons-cv-container")
                                            .prepend(
                                                `<div class="king-addons-slider-video king-addons-custom-video">
                          <video ${videoLoop} ${videoMute} ${videoControls} 
                            src="${videoSrc}" width="100%" height="100%">
                          </video>
                         </div>`
                                            );
                                        $slider.find("video").attr({
                                            width: $currentSlide.find(".king-addons-slider-item").width(),
                                            height: $currentSlide.find(".king-addons-slider-item").height(),
                                        });
                                        // noinspection JSValidateTypes
                                        $currentSlide.find(".king-addons-slider-content").fadeOut(300);
                                        $currentSlide.find("video")[0].play();
                                    }
                                }
                            });

                            // Slick events
                            $slider.on({
                                beforeChange: () => {
                                    $slider.find(".king-addons-slider-video").remove();
                                    $slider.find(".king-addons-animation-enter .king-addons-slider-content").fadeOut(300);
                                    slideAnimationOff();
                                },
                                afterChange: () => {
                                    slideAnimationOn();
                                    autoplayVideo();
                                    this.$element.find(".slick-slide").removeClass("king-addons-slick-visible");
                                    this.$element.find(".slick-current").addClass("king-addons-slick-visible");
                                    // noinspection JSUnresolvedReference
                                    this.$element
                                        .find(".slick-current")
                                        .nextAll()
                                        .slice(0, sliderColumnsDesktop - 1)
                                        .addClass("king-addons-slick-visible");
                                    updateSliderHeight();
                                },
                            });

                            // If dots exist and are horizontal
                            if (
                                this.$element.find(".slick-dots").length &&
                                this.$element.hasClass("king-addons-slider-dots-horizontal")
                            ) {
                                const setDotsWidth = () => {
                                    const $dots = this.$element.find(".slick-dots");
                                    const $dotsLi = $dots.find("li");
                                    if (!$dotsLi.length) return;
                                    // Each dot's width * total - margin-right
                                    const dotsWrapWidth =
                                        $dotsLi.outerWidth() * $dotsLi.length -
                                        parseInt($dotsLi.find("span").css("margin-right"), 10);
                                    $dots.css("width", dotsWrapWidth);
                                };

                                setDotsWidth();
                                // noinspection JSUnresolvedReference
                                $(window).smartresize(() => {
                                    setTimeout(setDotsWidth, 300);
                                });
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