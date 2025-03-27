"use strict";
(function ($) {
    $(window).on("elementor/frontend/init", function () {
        elementorFrontend.hooks.addAction(
            "frontend/element_ready/king-addons-tabs.default",
            function ($scope) {
                elementorFrontend.elementsHandler.addHandler(
                    elementorModules.frontend.handlers.Base.extend({
                        onInit: function () {
                            const $scope = this.$element;
                            const $tabs = $(".king-addons-tabs", $scope).first();
                            const $tabsWrap = $(".king-addons-tabs-wrap", $tabs).first();
                            const $contentWrap = $(".king-addons-tabs-content-wrap", $tabs).first();
                            const $tabItems = $tabsWrap.children(".king-addons-tab");
                            const $contentItems = $contentWrap.children(".king-addons-tab-content");
                            const tabsData = $tabs.data("options");

                            // Determine active tab from settings or URL
                            let activeTabIndex = tabsData.activeTab - 1;
                            const urlActiveTabPos = window.location.href.indexOf("active_tab=");
                            if (urlActiveTabPos > -1) {
                                activeTabIndex = parseInt(
                                    window.location.href
                                        .substring(urlActiveTabPos, window.location.href.lastIndexOf("#"))
                                        .replace("active_tab=", "")
                                ) - 1;
                            }

                            // Mark initial active tab and content
                            $tabItems.eq(activeTabIndex).addClass("king-addons-tab-active");
                            $contentItems.eq(activeTabIndex).addClass(
                                "king-addons-tab-content-active king-addons-animation-enter"
                            );

                            // Optional autoplay
                            let autoplayInterval;
                            if (tabsData.autoplay === "yes") {
                                let startIndex = activeTabIndex;
                                autoplayInterval = setInterval(() => {
                                    startIndex = startIndex < $tabItems.length - 1 ? startIndex + 1 : 0;
                                    switchTab(startIndex);
                                }, tabsData.autoplaySpeed);
                            }

                            // Hover or click trigger
                            const eventName = tabsData.trigger === "hover" ? "mouseenter" : "click";
                            $tabItems.on(eventName, function () {
                                const index = $(this).data("tab") - 1;
                                clearInterval(autoplayInterval);
                                switchTab(index);
                            });

                            // Switch tab utility
                            const switchTab = (index) => {
                                const $activeTab = $tabItems.eq(index);
                                const $activeContent = $contentItems.eq(index);

                                // Fix wrapper height during transition
                                $contentWrap.css("height", $contentWrap.outerHeight(true));

                                $tabItems.removeClass("king-addons-tab-active");
                                $activeTab.addClass("king-addons-tab-active");
                                $contentItems.removeClass("king-addons-tab-content-active king-addons-animation-enter");

                                // Calculate target height including container borders
                                const targetHeight =
                                    $activeContent.outerHeight(true) +
                                    parseInt($contentWrap.css("border-top-width"), 10) +
                                    parseInt($contentWrap.css("border-bottom-width"), 10);

                                // Apply active classes and animate
                                $activeContent.addClass(
                                    "king-addons-tab-content-active king-addons-animation-enter"
                                );
                                $contentWrap.css("height", targetHeight);

                                // Set back to auto after transition
                                setTimeout(() => {
                                    $contentWrap.css("height", "auto");
                                }, 500);
                            };
                        },
                    }),
                    { $element: $scope }
                );
            }
        );
    });
})(jQuery);