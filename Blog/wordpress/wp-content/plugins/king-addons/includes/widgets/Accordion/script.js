"use strict";
(($) => {
    $(window).on("elementor/frontend/init", () => {
        elementorFrontend.hooks.addAction(
            "frontend/element_ready/king-addons-accordion.default",
            ($scope) => {
                elementorFrontend.elementsHandler.addHandler(
                    elementorModules.frontend.handlers.Base.extend({
                        onInit: function () {
                            const $accordion  = $scope.find(".king-addons-advanced-accordion");
                            const $accButtons = $scope.find(".king-addons-acc-button");
                            const $accItems   = $scope.find(".king-addons-accordion-item-wrap");
                            const accordionType    = $accordion.data("accordion-type");
                            const accordionTrigger = $accordion.data("accordion-trigger");
                            const interactionSpeed = +$accordion.data("interaction-speed") * 1000;
                            let   activeIndex      = +$accordion.data("active-index") - 1;

                            // Check URL for "active_panel"
                            const activeTabParamPos = window.location.href.indexOf("active_panel=");
                            if (activeTabParamPos > -1) {
                                activeIndex = +window.location.href
                                    .substring(activeTabParamPos, window.location.href.lastIndexOf("#"))
                                    .replace("active_panel=", "") - 1;
                            }

                            // Helper: toggles a single accordion panel
                            const togglePanel = ($btn) => {
                                $btn.toggleClass("king-addons-acc-active");
                                const $panel = $btn.next();
                                if (!$panel.hasClass("king-addons-acc-panel-active")) {
                                    $panel.slideDown(interactionSpeed).addClass("king-addons-acc-panel-active");
                                } else {
                                    $panel.slideUp(interactionSpeed).removeClass("king-addons-acc-panel-active");
                                }
                            };

                            // Accordion: click trigger
                            if (accordionTrigger === "click") {
                                if (accordionType === "accordion") {
                                    $accButtons.on("click", function () {
                                        const currentIndex = $accButtons.index(this);

                                        // Deactivate all except the current
                                        $accButtons.each((i, btn) => {
                                            if (i !== currentIndex) $(btn).removeClass("king-addons-acc-active");
                                        });
                                        $scope.find(".king-addons-acc-panel").each((i, panel) => {
                                            if (i !== currentIndex) {
                                                $(panel)
                                                    .removeClass("king-addons-acc-panel-active")
                                                    .slideUp(interactionSpeed);
                                            }
                                        });

                                        // Toggle the current
                                        togglePanel($(this));
                                    });
                                } else {
                                    // Accordion: toggle each panel independently
                                    $accButtons.each((_, btn) => {
                                        $(btn).on("click", function () {
                                            togglePanel($(this));
                                        });
                                    });
                                }
                                // Open active index if set
                                if (activeIndex > -1) $accButtons.eq(activeIndex).trigger("click");

                                // Accordion: hover trigger
                            } else if (accordionTrigger === "hover") {
                                $accItems.on("mouseenter", function () {
                                    const currentIndex = $accItems.index(this);
                                    const $btn   = $(this).find(".king-addons-acc-button");
                                    const $panel = $(this).find(".king-addons-acc-panel");

                                    // Activate hovered item
                                    $btn.addClass("king-addons-acc-active");
                                    $panel.slideDown(interactionSpeed).addClass("king-addons-acc-panel-active");

                                    // Deactivate others
                                    $accItems.each((i, item) => {
                                        if (i !== currentIndex) {
                                            const $otherBtn   = $(item).find(".king-addons-acc-button");
                                            const $otherPanel = $(item).find(".king-addons-acc-panel");
                                            $otherBtn.removeClass("king-addons-acc-active");
                                            $otherPanel.slideUp(interactionSpeed).removeClass("king-addons-acc-panel-active");
                                        }
                                    });
                                });
                                // Open active index if set
                                if (activeIndex > -1) $accItems.eq(activeIndex).trigger("mouseenter");
                            }

                            // Search input events
                            const $searchInput = $scope.find(".king-addons-acc-search-input");
                            $searchInput.on({
                                focus: () => $scope.addClass("king-addons-acc-search-input-focus"),
                                blur:  () => $scope.removeClass("king-addons-search-form-input-focus"),
                            });

                            // Clear icon
                            const $clearIcon = $scope.find(".king-addons-acc-search-input-wrap i.fa-times");
                            $clearIcon.on("click", () => {
                                $searchInput.val("").trigger("keyup");
                            });

                            // Handle icon box border settings
                            const $iconBoxes = $scope.find(".king-addons-acc-icon-box");
                            const setIconBoxBorders = () => {
                                $iconBoxes.each((_, box) => {
                                    const $box = $(box);
                                    $box.find(".king-addons-acc-icon-box-after").css({
                                        "border-top":    $box.height() / 2 + "px solid transparent",
                                        "border-bottom": $box.height() / 2 + "px solid transparent",
                                    });
                                });
                            };
                            setIconBoxBorders();
                            $(window).on("resize", setIconBoxBorders);

                            // Search filtering
                            const $allInAccordion = $accordion.children();
                            $searchInput.on("keyup", function () {
                                setTimeout(() => {
                                    const query = $(this).val().trim();
                                    if (query.length) {
                                        $clearIcon.css("display", "inline-block");
                                        $allInAccordion.each((_, el) => {
                                            const $item = $(el);
                                            if (!$item.hasClass("king-addons-accordion-item-wrap")) return;

                                            // Match text?
                                            if ($item.text().toUpperCase().indexOf(query.toUpperCase()) === -1) {
                                                // Hide and deactivate
                                                $item.hide();
                                                if (
                                                    $item.find(".king-addons-acc-button").hasClass("king-addons-acc-active") &&
                                                    $item.find(".king-addons-acc-panel").hasClass("king-addons-acc-panel-active")
                                                ) {
                                                    $item
                                                        .find(".king-addons-acc-button")
                                                        .removeClass("king-addons-acc-active");
                                                    $item
                                                        .find(".king-addons-acc-panel")
                                                        .removeClass("king-addons-acc-panel-active");
                                                }
                                            } else {
                                                // Show and activate
                                                $item.show();
                                                const $btn   = $item.find(".king-addons-acc-button");
                                                const $panel = $item.find(".king-addons-acc-panel");
                                                if (!$btn.hasClass("king-addons-acc-active")) {
                                                    $btn.addClass("king-addons-acc-active");
                                                    $panel.addClass("king-addons-acc-panel-active").slideDown(interactionSpeed);
                                                }
                                            }
                                        });
                                    } else {
                                        $clearIcon.css("display", "none");
                                        $allInAccordion.each((_, el) => {
                                            const $item = $(el);
                                            if ($item.hasClass("king-addons-accordion-item-wrap")) {
                                                $item.show();
                                                $item.find(".king-addons-acc-panel").removeClass("king-addons-acc-panel-active").slideUp(interactionSpeed);
                                                $item.find(".king-addons-acc-button").removeClass("king-addons-acc-active");
                                            }
                                        });
                                    }
                                }, 1000);
                            });
                        },
                    }),
                    { $element: $scope }
                );
            }
        );
    });
})(jQuery);