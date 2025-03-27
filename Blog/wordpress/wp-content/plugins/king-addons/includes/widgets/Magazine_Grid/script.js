"use strict";
(function ($) {
    $(window).on("elementor/frontend/init", () => {
        elementorFrontend.hooks.addAction(
            "frontend/element_ready/king-addons-magazine-grid.default",
            ($scope) => {
                elementorFrontend.elementsHandler.addHandler(
                    elementorModules.frontend.handlers.Base.extend({
                        onInit() {
                            const $element = this.$element;
                            const $grid = $element.find(".king-addons-magazine-grid-wrap");
                            const slickSettings = $grid.attr("data-slick");
                            const slideEffect = $grid.attr("data-slide-effect");

                            // Initialize Slick if settings exist.
                            if (typeof slickSettings !== "undefined" && slickSettings !== false) {
                                $grid.slick({ fade: slideEffect === "fade" });
                            }
                            // Make grid visible once the document is ready.
                            $(document).ready(() => $grid.css("opacity", 1));

                            // --- Overlay Link Handling ---
                            const $mediaWrap = $grid.find(".king-addons-grid-media-wrap");
                            if (
                                $mediaWrap.attr("data-overlay-link") === "yes" &&
                                !$("body").hasClass("elementor-editor-active")
                            ) {
                                $mediaWrap.css("cursor", "pointer").on("click", function (event) {
                                    const targetClass = event.target.className;
                                    if (
                                        targetClass.includes("inner-block") ||
                                        targetClass.includes("king-addons-cv-inner") ||
                                        targetClass.includes("king-addons-grid-media-hover")
                                    ) {
                                        event.preventDefault();
                                        const itemUrl = $(this)
                                            .find(".king-addons-grid-media-hover-bg")
                                            .attr("data-url");
                                        const $link = $grid.find(".king-addons-grid-item-title a");
                                        if ($link.length && itemUrl) {
                                            try {
                                                const url = new URL(itemUrl);
                                                const allowedProtocols = ["http:", "https:"];
                                                if (allowedProtocols.includes(url.protocol)) {
                                                    const safeUrl = url.href;
                                                    if ($link.attr("target") === "_blank") {
                                                        window.open(safeUrl, "_blank").focus();
                                                    } else {
                                                        window.location.href = safeUrl;
                                                    }
                                                } else {
                                                    console.error("Invalid URL scheme:", url.protocol);
                                                }
                                            } catch (e) {
                                                console.error("Invalid URL:", itemUrl);
                                            }
                                        }
                                    }
                                });
                            }

                            // --- Sharing Trigger Handling ---
                            const $sharingTrigger = $element.find(".king-addons-sharing-trigger");
                            if ($sharingTrigger.length) {
                                const $sharingInner = $element.find(".king-addons-post-sharing-inner");
                                let totalWidth = 5;
                                $sharingInner
                                    .first()
                                    .find("a")
                                    .each(function () {
                                        const $link = $(this);
                                        totalWidth += $link.outerWidth() + parseInt($link.css("margin-right"), 10);
                                    });
                                const sharingMargin = parseInt($sharingInner.find("a").css("margin-right"), 10);
                                const direction = $sharingTrigger.attr("data-direction");

                                switch (direction) {
                                    case "left":
                                        $sharingInner.css({
                                            width: `${totalWidth}px`,
                                            left: `-${sharingMargin + totalWidth}px`
                                        });
                                        break;
                                    case "right":
                                        $sharingInner.css({
                                            width: `${totalWidth}px`,
                                            right: `-${sharingMargin + totalWidth}px`
                                        });
                                        break;
                                    case "top":
                                        $sharingInner.find("a").css({
                                            "margin-right": "0",
                                            "margin-top": `${sharingMargin}px`
                                        });
                                        $sharingInner.css({
                                            top: `-${sharingMargin}px`,
                                            left: "50%",
                                            "-webkit-transform": "translate(-50%, -100%)",
                                            transform: "translate(-50%, -100%)"
                                        });
                                        break;
                                    case "bottom":
                                        $sharingInner.find("a").css({
                                            "margin-right": "0",
                                            "margin-bottom": `${sharingMargin}px`
                                        });
                                        $sharingInner.css({
                                            bottom: `-${sharingMargin}px`,
                                            left: "50%",
                                            "-webkit-transform": "translate(-50%, 100%)",
                                            transform: "translate(-50%, 100%)"
                                        });
                                        break;
                                }

                                // Helper function to toggle sharing visibility.
                                const toggleSharing = ($inner) => {
                                    const $links = $inner.find("a");
                                    if ($inner.css("visibility") === "hidden") {
                                        $inner.css("visibility", "visible");
                                        $links.css({ opacity: 1, top: 0 });
                                        setTimeout(() => $links.addClass("king-addons-no-transition-delay"), $links.length * 100);
                                    } else {
                                        $links.removeClass("king-addons-no-transition-delay").css({ opacity: 0, top: "-5px" });
                                        setTimeout(() => $inner.css("visibility", "hidden"), $links.length * 100);
                                    }
                                };

                                if ($sharingTrigger.attr("data-action") === "click") {
                                    $sharingTrigger.on("click", function () {
                                        toggleSharing($(this).next());
                                    });
                                } else {
                                    $sharingTrigger.on("mouseenter", function () {
                                        const $inner = $(this).next();
                                        $inner.css("visibility", "visible");
                                        $inner.find("a").css({ opacity: 1, top: 0 });
                                        setTimeout(
                                            () => $inner.find("a").addClass("king-addons-no-transition-delay"),
                                            $inner.find("a").length * 100
                                        );
                                    });
                                    $element.find(".king-addons-grid-item-sharing").on("mouseleave", function () {
                                        const $inner = $(this).find(".king-addons-post-sharing-inner");
                                        $inner.find("a").removeClass("king-addons-no-transition-delay").css({ opacity: 0, top: "-5px" });
                                        setTimeout(() => $inner.css("visibility", "hidden"), $inner.find("a").length * 100);
                                    });
                                }
                            }

                            // --- Post Like Button Handling ---
                            const $likeButton = $element.find(".king-addons-post-like-button");
                            if ($likeButton.length) {
                                $likeButton.on("click", function () {
                                    const $btn = $(this);
                                    const postId = $btn.attr("data-post-id");
                                    if (postId !== "") {
                                        $.ajax({
                                            type: "POST",
                                            url: $btn.attr("data-ajax"),
                                            data: {
                                                action: "king_addons_likes_init",
                                                post_id: postId,
                                                nonce: $btn.attr("data-nonce")
                                            },
                                            beforeSend: () => $btn.fadeTo(500, 0.5),
                                            success(response) {
                                                const iconClass = $btn.attr("data-icon");
                                                let countHTML = response.count;
                                                if (!countHTML.replace(/<\/?[^>]+(>|$)/g, "")) {
                                                    countHTML = `<span class="king-addons-post-like-count">${$btn.attr("data-text")}</span>`;
                                                    if (!$btn.hasClass("king-addons-likes-zero")) {
                                                        $btn.addClass("king-addons-likes-zero");
                                                    }
                                                } else {
                                                    $btn.removeClass("king-addons-likes-zero");
                                                }

                                                if ($btn.hasClass("king-addons-already-liked")) {
                                                    $btn.prop("title", "Like")
                                                        .removeClass("king-addons-already-liked")
                                                        .html(`<i class="${iconClass.replace("fas", "far")}"></i>${countHTML}`);
                                                } else {
                                                    $btn.prop("title", "Unlike")
                                                        .addClass("king-addons-already-liked")
                                                        .html(`<i class="${iconClass.replace("far", "fas")}"></i>${countHTML}`);
                                                }
                                                $btn.fadeTo(500, 1);
                                            }
                                        });
                                    }
                                    return false;
                                });
                            }
                        }
                    }),
                    { $element: $scope }
                );
            }
        );
    });
})(jQuery);