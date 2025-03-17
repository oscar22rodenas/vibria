"use strict";
(function ($) {
    $(window).on('elementor/frontend/init', function () {
        let ProgressBarHandler = elementorModules.frontend.handlers.Base.extend({
            onInit: function onInit() {
                this.initProgressBar();
            },

            initProgressBar: function initProgressBar() {
                let $scope = this.$element;

                // noinspection JSUnresolvedReference
                let $progressBar = $scope.find('.king-addons-progress-bar'),
                    prBarCircle = $scope.find('.king-addons-prbar-circle'),
                    $prBarCirclePrline = $scope.find('.king-addons-prbar-circle-prline'),
                    prBarHrLine = $progressBar.find('.king-addons-prbar-hr-line-inner'),
                    prBarVrLine = $progressBar.find('.king-addons-prbar-vr-line-inner'),
                    prBarOptions = $progressBar.data('options'),
                    prBarCircleOptions = prBarCircle.data('circle-options'),
                    prBarCounter = $progressBar.find('.king-addons-prbar-counter-value'),
                    prBarCounterValue = prBarOptions.counterValue,
                    prBarCounterValuePercent = prBarOptions.counterValuePercent,
                    prBarAnimDuration = prBarOptions.animDuration,
                    prBarAnimDelay = prBarOptions.animDelay,
                    prBarLoopDelay = +prBarOptions.loopDelay,
                    numeratorData = {
                        toValue: prBarCounterValue,
                        duration: prBarAnimDuration,
                    };

                // noinspection JSUnresolvedReference
                if ('yes' === prBarOptions.counterSeparator) {
                    numeratorData.delimiter = ',';
                }

                function isInViewport($selector) {
                    if ($selector.length) {
                        let elementTop = $selector.offset().top,
                            elementBottom = elementTop + $selector.outerHeight(),
                            viewportTop = $(window).scrollTop(),
                            viewportBottom = viewportTop + $(window).height();

                        if (elementTop > $(window).height()) {
                            elementTop += 50;
                        }

                        return elementBottom > viewportTop && elementTop < viewportBottom;
                    }
                }

                function progressBar() {

                    if (isInViewport(prBarVrLine)) {
                        prBarVrLine.css({
                            'height': prBarCounterValuePercent + '%'
                        });
                    }

                    if (isInViewport(prBarHrLine)) {
                        prBarHrLine.css({
                            'width': prBarCounterValuePercent + '%'
                        });
                    }

                    if (isInViewport(prBarCircle)) {
                        // noinspection JSUnresolvedReference
                        let circleDashOffset = prBarCircleOptions.circleOffset;

                        $prBarCirclePrline.css({
                            'stroke-dashoffset': circleDashOffset
                        });
                    }

                    if (isInViewport(prBarVrLine) || isInViewport(prBarHrLine) || isInViewport(prBarCircle)) {
                        setTimeout(function () {
                            prBarCounter.numerator(numeratorData);
                        }, prBarAnimDelay);
                    }

                }

                progressBar();

                if (prBarOptions.loop === 'yes') {
                    setInterval(function () {

                        if (isInViewport(prBarVrLine)) {
                            prBarVrLine.css({
                                'height': 0 + '%'
                            });
                        }

                        if (isInViewport(prBarHrLine)) {
                            prBarHrLine.css({
                                'width': 0 + '%'
                            });
                        }

                        if (isInViewport(prBarCircle)) {
                            $prBarCirclePrline.css({
                                'stroke-dashoffset': $prBarCirclePrline.css('stroke-dasharray')
                            });
                        }

                        if (isInViewport(prBarVrLine) || isInViewport(prBarHrLine) || isInViewport(prBarCircle)) {
                            setTimeout(function () {
                                prBarCounter.numerator({
                                    toValue: 0,
                                    duration: prBarAnimDuration,
                                });
                            }, prBarAnimDelay);
                        }

                        setTimeout(function () {
                            progressBar();
                        }, prBarAnimDuration + prBarAnimDelay);
                    }, (prBarAnimDuration + prBarAnimDelay) * prBarLoopDelay);
                }

                $(window).on('scroll', function () {
                    progressBar();
                });
            }
        });

        // Initialize the handler when the specific widget is ready in Elementor
        elementorFrontend.hooks.addAction('frontend/element_ready/king-addons-progress-bar.default', function ($scope) {
            elementorFrontend.elementsHandler.addHandler(ProgressBarHandler, {
                $element: $scope
            });
        });
    });
})(jQuery);