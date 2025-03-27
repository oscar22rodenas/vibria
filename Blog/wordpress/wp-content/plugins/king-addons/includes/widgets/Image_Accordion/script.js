"use strict";

(function ($) {
    $(window).on("elementor/frontend/init", function () {
        elementorFrontend.hooks.addAction(
            "frontend/element_ready/king-addons-image-accordion.default",
            function ($scope) {
                elementorFrontend.elementsHandler.addHandler(
                    elementorModules.frontend.handlers.Base.extend({
                        onInit() {
                            const $elem = this.$element,
                                $wrap = $elem.find(".king-addons-image-accordion"),
                                $wrapContainer = $elem.find(".king-addons-image-accordion-wrap"),
                                settings = JSON.parse(
                                    $elem
                                        .find(".king-addons-img-accordion-media-hover")
                                        .attr("data-settings")
                                ),
                                lightboxAttr = $wrap.attr("lightbox"),
                                lightboxSettings = lightboxAttr ? JSON.parse(lightboxAttr) : "",
                                $accordionItems = $elem.find(".king-addons-image-accordion-item");

                            // Adjust row/column layout if necessary
                            if ($wrapContainer.hasClass("king-addons-acc-no-column")) {
                                if (!$elem.hasClass("king-addons-image-accordion-row")) {
                                    $elem
                                        .removeClass("king-addons-image-accordion-column")
                                        .addClass("king-addons-image-accordion-row");
                                    $wrap.css("flex-direction", "row");
                                }
                            }

                            // Initialize lightbox if settings exist
                            if (lightboxSettings) {
                                $wrap.lightGallery(lightboxSettings);

                                // Adjust thumbnails in lightbox
                                $wrap.on("onAfterOpen.lg", function () {
                                    const $lgOuter = $(".lg-outer");
                                    if ($lgOuter.find(".lg-thumb-item").length) {
                                        $lgOuter.find(".lg-thumb-item").each(function () {
                                            const $img = $(this).find("img");
                                            const imgSrc = $img.attr("src");
                                            const extIndex = imgSrc.lastIndexOf(".");
                                            const ext = imgSrc.slice(extIndex);
                                            const cropIndex = imgSrc.lastIndexOf("-");
                                            let cropSize = /\d{3,}x\d{3,}/.test(
                                                imgSrc.substring(extIndex, cropIndex)
                                            )
                                                ? imgSrc.substring(extIndex, cropIndex)
                                                : false;
                                            let newImgSrc = imgSrc;

                                            if (cropSize && cropSize.length >= 42) {
                                                cropSize = "";
                                            }

                                            if (cropSize !== "") {
                                                if (cropSize !== false) {
                                                    newImgSrc = imgSrc.replace(cropSize, "-150x150");
                                                } else {
                                                    newImgSrc = [
                                                        imgSrc.slice(0, extIndex),
                                                        "-150x150",
                                                        ext
                                                    ].join("");
                                                }
                                            }

                                            $img.attr("src", newImgSrc);

                                            // Restore original if invalid crop size
                                            if (cropSize === false || cropSize === "-450x450") {
                                                $img.attr("src", imgSrc);
                                            }
                                        });
                                    }
                                });

                                // Control certain lightbox behaviors
                                $wrap.on("onAferAppendSlide.lg onAfterSlide.lg", function () {
                                    const $lightboxDownload = $("#lg-download"),
                                        lightboxControls = $(
                                            "#lg-actual-size, #lg-zoom-in, #lg-zoom-out, #lg-download"
                                        ),
                                        downloadHref = $lightboxDownload.attr("href");

                                    if ($lightboxDownload.length) {
                                        if (downloadHref.indexOf("wp-content") === -1) {
                                            lightboxControls.addClass("king-addons-hidden-element");
                                        } else {
                                            lightboxControls.removeClass("king-addons-hidden-element");
                                        }
                                    }

                                    // Hide autoplay if not enabled
                                    if (!lightboxSettings.autoplay) {
                                        $(".lg-autoplay-button").css({
                                            width: "0",
                                            height: "0",
                                            overflow: "hidden"
                                        });
                                    }
                                });
                            }

                            // Make the entire accordion item clickable
                            $wrap.css("cursor", "pointer");

                            // Handle hover interactions for clickable links
                            function mediaHoverLink() {
                                // Avoid linking in the Elementor editor
                                if (!$("body").hasClass("elementor-editor-active")) {
                                    $elem
                                        .find(".king-addons-img-accordion-media-hover")
                                        .on("click", function (event) {
                                            const isTarget = event.target.className.includes(
                                                "king-addons-img-accordion-media-hover"
                                            );
                                            const thisSettings = JSON.parse(
                                                isTarget
                                                    ? $(this).attr("data-settings")
                                                    : $(this)
                                                        .closest(".king-addons-img-accordion-media-hover")
                                                        .attr("data-settings")
                                            );
                                            if (
                                                !$(event.target).hasClass(
                                                    "king-addons-img-accordion-item-lightbox"
                                                ) &&
                                                !$(event.target).closest(
                                                    ".king-addons-img-accordion-item-lightbox"
                                                ).length
                                            ) {
                                                const itemUrl = thisSettings.activeItem.overlayLink;
                                                if (itemUrl) {
                                                    if (
                                                        thisSettings.activeItem.overlayLinkTarget ===
                                                        "_blank"
                                                    ) {
                                                        window.open(itemUrl, "_blank").focus();
                                                    } else {
                                                        window.location.href = itemUrl;
                                                    }
                                                }
                                            }
                                        });
                                }
                            }

                            // Interaction type: "hover"
                            if (settings.activeItem.interaction === "hover") {
                                mediaHoverLink();

                                $accordionItems
                                    .on("mouseenter", function () {
                                        $accordionItems.removeClass(
                                            "king-addons-image-accordion-item-grow"
                                        );
                                        $accordionItems
                                            .find(".king-addons-animation-wrap")
                                            .removeClass("king-addons-animation-wrap-active");

                                        $(this).addClass("king-addons-image-accordion-item-grow");
                                        $(this)
                                            .find(".king-addons-animation-wrap")
                                            .addClass("king-addons-animation-wrap-active");
                                    })
                                    .on("mouseleave", function () {
                                        $(this).removeClass("king-addons-image-accordion-item-grow");
                                        $(this)
                                            .find(".king-addons-animation-wrap")
                                            .removeClass("king-addons-animation-wrap-active");
                                    });

                                // Interaction type: "click"
                            } else if (settings.activeItem.interaction === "click") {
                                $elem
                                    .find(".king-addons-img-accordion-media-hover")
                                    .removeClass("king-addons-animation-wrap");

                                $accordionItems.on(
                                    "click",
                                    ".king-addons-img-accordion-media-hover",
                                    function (event) {
                                        const isTarget = event.target.className.includes(
                                            "king-addons-img-accordion-media-hover"
                                        );
                                        const hasActiveClass = isTarget
                                            ? event.target.className.includes(
                                                "king-addons-animation-wrap-active"
                                            )
                                            : $(this)
                                                .closest(".king-addons-img-accordion-media-hover")
                                                .hasClass("king-addons-animation-wrap-active");

                                        // If this item is already active, redirect if a link is set
                                        if (
                                            hasActiveClass &&
                                            !$("body").hasClass("elementor-editor-active")
                                        ) {
                                            const thisSettings = JSON.parse(
                                                isTarget
                                                    ? $(this).attr("data-settings")
                                                    : $(this)
                                                        .closest(".king-addons-img-accordion-media-hover")
                                                        .attr("data-settings")
                                            );

                                            if (
                                                !$(event.target).hasClass(
                                                    "king-addons-img-accordion-item-lightbox"
                                                ) &&
                                                !$(event.target).closest(
                                                    ".king-addons-img-accordion-item-lightbox"
                                                ).length
                                            ) {
                                                const itemUrl = thisSettings.activeItem.overlayLink;
                                                if (itemUrl) {
                                                    if (
                                                        thisSettings.activeItem.overlayLinkTarget ===
                                                        "_blank"
                                                    ) {
                                                        window.open(itemUrl, "_blank").focus();
                                                    } else {
                                                        window.location.href = itemUrl;
                                                    }
                                                }
                                            }
                                        } else {
                                            // Activate this item
                                            $elem
                                                .find(".king-addons-img-accordion-media-hover")
                                                .removeClass("king-addons-animation-wrap king-addons-animation-wrap-active");
                                            $accordionItems.removeClass(
                                                "king-addons-image-accordion-item-grow"
                                            );

                                            $(this)
                                                .closest(".king-addons-image-accordion-item")
                                                .addClass("king-addons-image-accordion-item-grow");
                                            $(this)
                                                .closest(".king-addons-img-accordion-media-hover")
                                                .addClass("king-addons-animation-wrap-active");
                                        }
                                    }
                                );
                            } else {
                                // If interaction is neither hover nor click
                                $elem
                                    .find(".king-addons-img-accordion-media-hover")
                                    .removeClass("king-addons-animation-wrap");
                            }

                            // Set default active item
                            $accordionItems.each(function () {
                                if ($(this).index() === settings.activeItem.defaultActive - 1) {
                                    setTimeout(() => {
                                        const action =
                                            settings.activeItem.interaction === "click"
                                                ? "click"
                                                : "mouseenter";
                                        $(this)
                                            .find(".king-addons-img-accordion-media-hover")
                                            .trigger(action);
                                    }, 400);
                                }
                            });

                            // Show the accordion after initialization
                            $wrapContainer.css("opacity", 1);
                        }
                    }),
                    {
                        $element: $scope
                    }
                );
            }
        );
    });
})(jQuery);