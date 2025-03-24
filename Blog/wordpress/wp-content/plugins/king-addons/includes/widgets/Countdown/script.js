"use strict";
(($) => {
    $(window).on("elementor/frontend/init", () => {
        elementorFrontend.hooks.addAction("frontend/element_ready/king-addons-countdown.default", ($scope) => {
            elementorFrontend.elementsHandler.addHandler(
                elementorModules.frontend.handlers.Base.extend({
                    onInit() {
                        const $element = this.$element;
                        const countDownWrap = $element
                            .children(".elementor-widget-container")
                            .children(".king-addons-countdown-wrap");

                        let countDownInterval = null;
                        const dataInterval = countDownWrap.data("interval");
                        const dataShowAgain = countDownWrap.data("show-again");
                        let endTime = new Date(dataInterval * 1000).getTime();

                        // Handle evergreen type
                        if (countDownWrap.data("type") === "evergreen") {
                            const widgetID = $element.attr("data-id");
                            let now = new Date();
                            const settings =
                                JSON.parse(localStorage.getItem("KingAddonsCountdownSettings")) || {};

                            // If this widget is saved in localStorage and intervals match, reuse its endTime.
                            if (
                                settings[widgetID] &&
                                dataInterval === settings[widgetID].interval
                            ) {
                                endTime = settings[widgetID].endTime;
                            } else {
                                endTime = now.setSeconds(now.getSeconds() + dataInterval);
                            }

                            // Check if show-again time has passed
                            if (endTime + dataShowAgain < Date.now()) {
                                now = new Date();
                                endTime = now.setSeconds(now.getSeconds() + dataInterval);
                            }

                            // Save updated settings
                            settings[widgetID] = { interval: dataInterval, endTime };
                            localStorage.setItem(
                                "KingAddonsCountdownSettings",
                                JSON.stringify(settings)
                            );
                        }

                        // Initialize and run the countdown
                        initCountDown();
                        countDownInterval = setInterval(initCountDown, 1000);

                        function initCountDown() {
                            const timeLeft = endTime - Date.now();

                            let numbers = {
                                days: Math.floor(timeLeft / (1000 * 60 * 60 * 24)),
                                hours: Math.floor((timeLeft / (1000 * 60 * 60)) % 24),
                                minutes: Math.floor((timeLeft / 1000 / 60) % 60),
                                seconds: Math.floor((timeLeft / 1000) % 60),
                            };

                            // If time is already up
                            if (timeLeft < 0) {
                                numbers = { days: 0, hours: 0, minutes: 0, seconds: 0 };
                            }

                            // Update DOM
                            $element.find(".king-addons-countdown-number").each(function () {
                                let number = numbers[$(this).attr("data-item")] || 0;

                                if (number.toString().length === 1) {
                                    number = `0${number}`;
                                }
                                $(this).text(number);

                                // Update label singular/plural
                                const labels = $(this).next();
                                if (
                                    labels.length &&
                                    !$(this).hasClass("king-addons-countdown-seconds")
                                ) {
                                    const labelText = labels.data("text");
                                    labels.text(number === "01" ? labelText.singular : labelText.plural);
                                }
                            });

                            // When countdown expires
                            if (timeLeft < 0) {
                                clearInterval(countDownInterval);
                                expiredActions();
                            }
                        }

                        function expiredActions() {
                            const dataActions = countDownWrap.data("actions");
                            // Skip if in elementor editor
                            if ($("body").hasClass("elementor-editor-active")) return;

                            // Hide the timer
                            if (dataActions.hasOwnProperty("hide-timer")) {
                                countDownWrap.hide();
                            }

                            // Hide a specific element
                            if (dataActions.hasOwnProperty("hide-element")) {
                                $(dataActions["hide-element"]).hide();
                            }

                            // Show a message
                            if (dataActions.hasOwnProperty("message")) {
                                const messageSelector = ".king-addons-countdown-message";
                                if (
                                    !$element
                                        .children(".elementor-widget-container")
                                        .children(messageSelector).length
                                ) {
                                    countDownWrap.after(
                                        `<div class="king-addons-countdown-message">${dataActions["message"]}</div>`
                                    );
                                }
                            }

                            // Redirect
                            if (dataActions.hasOwnProperty("redirect")) {
                                window.location.href = dataActions["redirect"];
                            }

                            // Load a template (show the next .elementor section)
                            if (dataActions.hasOwnProperty("load-template")) {
                                countDownWrap.next(".elementor").show();
                            }
                        }
                    },
                }),
                { $element: $scope }
            );
        });
    });
})(jQuery);