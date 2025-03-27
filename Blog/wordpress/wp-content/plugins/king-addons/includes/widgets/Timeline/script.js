"use strict";
(function ($) {
    $(window).on('elementor/frontend/init', () => {
        elementorFrontend.hooks.addAction(
            'frontend/element_ready/king-addons-timeline.default',
            ($scope) => {
                elementorFrontend.elementsHandler.addHandler(
                    elementorModules.frontend.handlers.Base.extend({
                        onInit() {
                            const $el = this.$element;

                            // Helper to safely find elements or return an empty jQuery object
                            const findOrEmpty = (sel) => {
                                const found = $el.find(sel);
                                return found.length ? found : $();
                            };

                            let iScrollTarget = findOrEmpty('.king-addons-timeline-centered'),
                                element = iScrollTarget,
                                pagination = findOrEmpty('.king-addons-grid-pagination'),
                                middleLine = findOrEmpty('.king-addons-middle-line'),
                                timelineFill = findOrEmpty('.king-addons-timeline-fill'),
                                lastIcon = findOrEmpty('.king-addons-main-line-icon.king-addons-icon:last'),
                                firstIcon = findOrEmpty('.king-addons-main-line-icon.king-addons-icon').first(),
                                scopeClass = '.elementor-element-' + $el.attr('data-id'),
                                aosOffset = +findOrEmpty('.king-addons-story-info-vertical').attr('data-animation-offset') || 0,
                                aosDuration = +findOrEmpty('.king-addons-story-info-vertical').attr('data-animation-duration') || 0;

                            // Remove "left aligned" class on smaller screens
                            const removeLeftAlignedClass = () => {
                                if ($el.find('.king-addons-centered').length) {
                                    if (window.innerWidth <= 767) {
                                        $el.find('.king-addons-wrapper .king-addons-timeline-centered')
                                            .removeClass('king-addons-both-sided-timeline')
                                            .addClass('king-addons-one-sided-timeline king-addons-remove-one-sided-later');
                                        $el.find('.king-addons-wrapper .king-addons-left-aligned')
                                            .removeClass('king-addons-left-aligned')
                                            .addClass('king-addons-right-aligned king-addons-remove-right-aligned-later');
                                    } else {
                                        $el.find('.king-addons-wrapper .king-addons-timeline-centered.king-addons-remove-one-sided-later')
                                            .removeClass('king-addons-one-sided-timeline king-addons-remove-one-sided-later')
                                            .addClass('king-addons-both-sided-timeline');
                                        $el.find('.king-addons-wrapper .king-addons-remove-right-aligned-later')
                                            .removeClass('king-addons-right-aligned king-addons-remove-right-aligned-later')
                                            .addClass('king-addons-left-aligned');
                                    }
                                }
                            };

                            // Fill the timeline line as the user scrolls
                            const postsTimelineFill = (last, first) => {
                                if (!timelineFill.length) return;

                                // If first item is preceded by a year label, use that label instead
                                if ($el.find('.king-addons-timeline-entry:eq(0)').prev('.king-addons-year-wrap').length) {
                                    first = $el.find('.king-addons-year-label').eq(0);
                                }

                                const fillHeight = parseFloat(timelineFill.css('height')),
                                    docScrollTop = document.documentElement.scrollTop,
                                    clientHeightHalf = document.documentElement.clientHeight / 2,
                                    offsetFirst = first.offset().top,
                                    offsetLast = last.offset().top,
                                    lastHeight = parseFloat(last.css('height'));

                                // Only update fill if we haven't scrolled past the last icon
                                if ((docScrollTop + clientHeightHalf - offsetFirst) < (offsetLast - offsetFirst + lastHeight)) {
                                    timelineFill.css(
                                        'height',
                                        (docScrollTop + clientHeightHalf - offsetFirst) + 'px'
                                    );
                                }

                                // Highlight icons if they are "behind" the fill
                                $el.find('.king-addons-main-line-icon.king-addons-icon').each(function () {
                                    const $icon = $(this);
                                    $icon.toggleClass(
                                        'king-addons-change-border-color',
                                        $icon.offset().top < offsetFirst + fillHeight
                                    );
                                });
                            };

                            // Adjust the vertical line in the center (and its fill) to match icons' positions
                            const adjustMiddleLineHeight = (midLine, tFill, last, first, el) => {
                                if (
                                    !$el.find('.king-addons-both-sided-timeline').length &&
                                    !$el.find('.king-addons-one-sided-timeline').length &&
                                    !$el.find('.king-addons-one-sided-timeline-left').length
                                ) {
                                    return;
                                }
                                if ($el.find('.king-addons-timeline-entry:eq(0)').prev('.king-addons-year-wrap').length) {
                                    first = $el.find('.king-addons-year-label').eq(0);
                                }

                                const offsetEl = el.offset().top,
                                    offsetFirst = first.offset().top,
                                    offsetLast = last.offset().top,
                                    lastHeight = parseFloat(last.css('height')),
                                    topPos = (offsetFirst - offsetEl) + 'px',
                                    heightVal = (offsetLast - offsetFirst + lastHeight);

                                midLine.css({
                                    top: topPos,
                                    height: heightVal
                                });
                                if (tFill.length) tFill.css('top', topPos);
                            };

                            // Only proceed if we have a timeline
                            if (iScrollTarget.length) {
                                // Combine redundant window resize bindings
                                $(window).on('resize smartresize', () => {
                                    removeLeftAlignedClass();
                                    adjustMiddleLineHeight(middleLine, timelineFill, lastIcon, firstIcon, element);
                                });

                                // Fire alignment and size adjustments with a short delay
                                setTimeout(() => {
                                    removeLeftAlignedClass();
                                    $(window).trigger('resize');
                                }, 500);

                                setTimeout(() => {
                                    adjustMiddleLineHeight(middleLine, timelineFill, lastIcon, firstIcon, element);
                                    $(window).trigger('resize');
                                }, 500);

                                // Hide pagination if not "load-more"
                                if (iScrollTarget.attr('data-pagination') !== 'load-more') {
                                    pagination.css('visibility', 'hidden');
                                }

                                // Initialize AOS
                                // noinspection JSUnresolvedReference
                                AOS.init({
                                    offset: aosOffset,
                                    duration: aosDuration,
                                    once: true,
                                });

                                // Fill the timeline on scroll
                                postsTimelineFill(lastIcon, firstIcon);
                                $(window).on('scroll', () => postsTimelineFill(lastIcon, firstIcon));

                                // Infinite scroll / load-more logic
                                // noinspection JSJQueryEfficiency
                                if (
                                    !$el.find('.elementor-repeater-items').length &&
                                    !$('body').hasClass('elementor-editor-active') &&
                                    (iScrollTarget.data('pagination') === 'load-more' ||
                                        iScrollTarget.data('pagination') === 'infinite-scroll')
                                ) {
                                    const threshold =
                                        iScrollTarget.data('pagination') === 'load-more' ? false : 10;

                                    // noinspection JSUnresolvedReference
                                    iScrollTarget.infiniteScroll({
                                        path: scopeClass + ' .king-addons-grid-pagination a',
                                        hideNav: false,
                                        append: scopeClass + '.king-addons-timeline-entry',
                                        history: false,
                                        scrollThreshold: threshold,
                                        status: scopeClass + ' .page-load-status',
                                    });

                                    iScrollTarget.on('request.infiniteScroll', () => {
                                        $el.find('.king-addons-load-more-btn').hide();
                                        $el.find('.king-addons-pagination-loading').css('display', 'inline-block');
                                    });

                                    let pagesLoaded = 0;
                                    iScrollTarget.on('load.infiniteScroll', (event, response) => {
                                        pagesLoaded++;
                                        const items = $(response).find(scopeClass).find('.king-addons-timeline-entry');
                                        // noinspection JSUnresolvedReference
                                        iScrollTarget.infiniteScroll('appendItems', items);

                                        // Re-align new items
                                        if (
                                            !$el.find('.king-addons-one-sided-timeline').length &&
                                            !$el.find('.king-addons-one-sided-timeline-left').length
                                        ) {
                                            $el.find('.king-addons-timeline-entry').each(function (idx) {
                                                const $entry = $(this);
                                                $entry.removeClass('king-addons-right-aligned king-addons-left-aligned');
                                                if (idx % 2 === 0) {
                                                    $entry.addClass('king-addons-left-aligned');
                                                    $entry
                                                        .find('.king-addons-story-info-vertical')
                                                        .attr('data-aos', $entry.find('.king-addons-story-info-vertical').attr('data-aos-left'));
                                                } else {
                                                    $entry.addClass('king-addons-right-aligned');
                                                    $entry
                                                        .find('.king-addons-story-info-vertical')
                                                        .attr('data-aos', $entry.find('.king-addons-story-info-vertical').attr('data-aos-right'));
                                                }
                                            });
                                        }

                                        // Re-init AOS for newly added items
                                        // noinspection JSUnresolvedReference
                                        AOS.init({ offset: aosOffset, duration: aosDuration, once: true });
                                        $(window).scroll();
                                        $el.find('.king-addons-pagination-loading').hide();

                                        // Show load more button or finish message
                                        if (iScrollTarget.data('max-pages') - 1 !== pagesLoaded) {
                                            if (iScrollTarget.attr('data-pagination') === 'load-more') {
                                                $el.find('.king-addons-load-more-btn').fadeIn();
                                            }
                                        } else {
                                            $el.find('.king-addons-pagination-finish').fadeIn(1000);
                                            pagination.delay(2000).fadeOut(1000);
                                        }

                                        // Update references after new items are appended
                                        middleLine = $el.find('.king-addons-middle-line');
                                        timelineFill = $el.find(".king-addons-timeline-fill");
                                        lastIcon = $el.find('.king-addons-main-line-icon.king-addons-icon:last');
                                        firstIcon = $el.find('.king-addons-main-line-icon.king-addons-icon').first();
                                        element = $el.find('.king-addons-timeline-centered');

                                        adjustMiddleLineHeight(middleLine, timelineFill, lastIcon, firstIcon, element);
                                        $(window).trigger('resize');
                                        postsTimelineFill(lastIcon, firstIcon);
                                    });

                                    // Manual load more
                                    if (!$('body').hasClass('elementor-editor-active')) {
                                        $el.find('.king-addons-load-more-btn').on('click', () => {
                                            // noinspection JSUnresolvedReference
                                            iScrollTarget.infiniteScroll('loadNextPage');
                                            return false;
                                        });
                                        if (iScrollTarget.attr('data-pagination') === 'infinite-scroll') {
                                            // noinspection JSUnresolvedReference
                                            iScrollTarget.infiniteScroll('loadNextPage');
                                        }
                                    }
                                }
                            }

                            // Swiper logic (horizontal timeline)
                            if ($el.find('.swiper-wrapper').length) {
                                const swiperLoader = (swiperElement, swiperConfig) => {
                                    const asyncSwiper = elementorFrontend.utils.swiper;
                                    return new asyncSwiper(swiperElement, swiperConfig).then(
                                        (instance) => instance
                                    );
                                };

                                // Identify correct horizontal selector
                                const horizontal = $el.find('.king-addons-horizontal-bottom').length
                                    ? '.king-addons-horizontal-bottom'
                                    : '.king-addons-horizontal';
                                const swiperSlider = $el.find(horizontal + ".swiper");
                                const slidesToShow = swiperSlider.data("slidestoshow");

                                swiperLoader(swiperSlider, {
                                    spaceBetween: +swiperSlider.data('swiper-space-between'),
                                    loop: swiperSlider.data('loop') === 'yes',
                                    autoplay:
                                        swiperSlider.data("autoplay") === 'yes'
                                            ? {
                                                delay: +swiperSlider.attr('data-swiper-delay'),
                                                disableOnInteraction: false,
                                                pauseOnMouseEnter:
                                                    swiperSlider.data('swiper-poh') === 'yes',
                                            }
                                            : false,
                                    on: {
                                        init: () => {
                                            const timelineOuter = $el.find('.king-addons-timeline-outer-container');
                                            if (timelineOuter.length) timelineOuter.css('opacity', 1);
                                        },
                                    },
                                    speed: +swiperSlider.attr('data-swiper-speed'),
                                    slidesPerView: slidesToShow,
                                    direction: 'horizontal',
                                    pagination: {
                                        el: '.king-addons-swiper-pagination',
                                        type: 'progressbar',
                                    },
                                    navigation: {
                                        nextEl: '.king-addons-button-next',
                                        prevEl: '.king-addons-button-prev',
                                    },
                                    breakpoints: {
                                        320: { slidesPerView: 1 },
                                        480: { slidesPerView: 2 },
                                        769: { slidesPerView: slidesToShow },
                                    },
                                });
                            } else {
                                // If no slider, just make timeline visible
                                $(document).ready(() => {
                                    const timelineOuter = $el.find('.king-addons-timeline-outer-container');
                                    if (timelineOuter.length) timelineOuter.css('opacity', 1);
                                });
                            }
                        },
                    }),
                    { $element: $scope }
                );
            }
        );
    });
})(jQuery);