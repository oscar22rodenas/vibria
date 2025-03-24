// noinspection JSUnresolvedReference,DuplicatedCode

"use strict";
(function ($) {
    $(window).on("elementor/frontend/init", () => {

        const gridHooks = [
            "frontend/element_ready/king-addons-grid.default"
        ];

        const gridHandler = ($scope) => {
                elementorFrontend.elementsHandler.addHandler(
                    elementorModules.frontend.handlers.Base.extend({
                        onInit() {
                            const $body = $("body");
                            const isEditor = $body.hasClass("elementor-editor-active");
                            const $grid = this.$element.find(".king-addons-grid");
                            if (!$grid.length) return;

                            /* ──────────────────────────────────────
                             *  UTILITY FUNCTIONS
                             * ────────────────────────────────────── */

                            // Simple debounce helper.
                            const debounce = (func, threshold = 100, execAsap) => {
                                let timeout;
                                return function (...args) {
                                    const context = this;
                                    const delayed = () => {
                                        if (!execAsap) func.apply(context, args);
                                        timeout = null;
                                    };
                                    if (timeout) clearTimeout(timeout);
                                    else if (execAsap) func.apply(context, args);
                                    timeout = setTimeout(delayed, threshold);
                                };
                            };

                            // Attach "smartresize" to jQuery.
                            $.fn.smartresize = function (fn) {
                                return fn ? this.on("resize", debounce(fn)) : this.trigger("smartresize");
                            };

                            // Parse grid settings.
                            const settingsData = $grid.attr("data-settings");
                            const settings = settingsData ? JSON.parse(settingsData) : false;
                            let pagesLoaded = 0;

                            /* ──────────────────────────────────────
                             *  INITIAL EVENT HANDLERS
                             * ────────────────────────────────────── */

                            // Handle WooCommerce orderby form.
                            if ($scope.find(".king-addons-grid-orderby form").length) {
                                const $orderbyForm = $scope.find(".king-addons-grid-orderby form");
                                $scope.find(".orderby").on("change", () => $orderbyForm.trigger("submit"));
                            }

                            // Adjust result count text if WooCommerce count element exists.
                            if (settings && $scope.find(".woocommerce-result-count").length) {
                                adjustResultCount($scope, $grid, settings, isEditor);
                            }

                            // Helper to schedule a delayed isotope layout.
                            const scheduleIsotopeLayout = (delay) =>
                                setTimeout(() => isotopeLayout(settings), delay);

                            if (settings) {
                                // Perform initial layout.
                                isotopeLayout(settings);
                                scheduleIsotopeLayout(100);
                                if (isEditor) {
                                    scheduleIsotopeLayout(500);
                                    scheduleIsotopeLayout(1000);
                                }
                                $(window).on("load", () => scheduleIsotopeLayout(100));
                                $(document).ready(() => scheduleIsotopeLayout(100));
                                $(window).smartresize(() => scheduleIsotopeLayout(200));

                                // If dynamic grid settings exist, set up filtering and “load more” experiments.
                                if (settings.grid_settings) {
                                    loadMoreExperiment();
                                    filtersExperiment(settings);
                                }
                                isotopeFilters(settings);

                                let initialItems = 0;
                                $grid.on("arrangeComplete", (e, filteredItems) => {
                                    animateGridItems(filteredItems, settings, $grid);
                                    initialItems = filteredItems.length;
                                });

                                // Once images load, set grid opacity and equalize item heights.
                                $grid.imagesLoaded(() => {
                                    if ($grid.css("opacity") !== "1") $grid.css("opacity", "1");
                                    setTimeout(() => $grid.addClass("grid-images-loaded"), 500);
                                    setEqualHeight(settings);
                                });

                                // Pagination: load-more or infinite-scroll.
                                if (["load-more", "infinite-scroll"].includes(settings.pagination_type)) {
                                    if ($scope.find(".king-addons-grid-pagination").length && !isEditor) {
                                        setupInfiniteScroll(settings);
                                    } else {
                                        $scope.find(".king-addons-load-more-btn").on("click", () =>
                                            alert(
                                                "Load More is Disabled in the Editor! Please Preview this Page to see it in action"
                                            )
                                        );
                                    }
                                }
                            } else {
                                // Fallback: initialize Slick slider.
                                $grid.animate({ opacity: "1" }, 1000);
                                initSlickSlider();
                            }

                            // Additional features.
                            if ($grid.find(".king-addons-grid-item-add-to-cart").length) {
                                setupAddToCart();
                            }
                            postSharing();
                            mediaHoverLink();
                            if (
                                !$scope.hasClass("elementor-widget-king-addons-woocommerce-category-grid-pro") &&
                                !$scope.hasClass("elementor-widget-king-addons-category-grid-pro")
                            ) {
                                // lightboxPopup(settings);
                            }
                            postLikes(settings);

                            /* ──────────────────────────────────────
                             *  FUNCTION DEFINITIONS
                             * ────────────────────────────────────── */

                            // Adjust WooCommerce result count.
                            function adjustResultCount($scope, $grid, s, isEditor) {
                                const $resultCount = $scope.find(".woocommerce-result-count");
                                let text = $resultCount.text();
                                const scopeId = $scope.data("id");
                                const storageKey = `king-addons-cached-items-length-${scopeId}`;
                                let cachedLength = localStorage.getItem(storageKey);
                                if (!cachedLength || isEditor) {
                                    cachedLength = $scope.find(".king-addons-grid-item").length;
                                    localStorage.setItem(storageKey, cachedLength);
                                }
                                const itemsPerPage = s.query_posts_per_page || parseInt(cachedLength, 10);
                                if (isNaN(itemsPerPage) || itemsPerPage <= 0) return;
                                let currentPage = 1;
                                const $currentPage = $scope.find(".king-addons-grid-current-page");
                                if ($currentPage.length) {
                                    currentPage = parseInt($currentPage.text().trim(), 10) || 1;
                                }
                                const totalMatch = text.match(/of (\d+) results/);
                                const totalItems = totalMatch ? parseInt(totalMatch[1].trim(), 10) : itemsPerPage;
                                if (isNaN(totalItems) || totalItems <= 0) return;
                                const startItem = (currentPage - 1) * itemsPerPage + 1;
                                const endItem = Math.min(startItem + itemsPerPage - 1, totalItems);
                                text = text.replace(/\d+\u2013\d+/, `${startItem}\u2013${endItem}`);
                                $resultCount.text(text);
                            }

                            // Layout the grid using Isotope (or its custom wrapper).
                            function isotopeLayout(s, $response = "") {
                                const layout = s.layout;
                                const columnsDesktop = parseInt(s.columns_desktop, 10);
                                let items = $response ? $response : $grid.find(".king-addons-grid-item"),
                                    contWidth = $grid.width() + s.gutter_hr - 0.3,
                                    viewportWidth = $(window).outerWidth(),
                                    columns,
                                    gutterHr,
                                    gutterVr;

                                // Breakpoints.
                                const { mobile, mobile_extra, tablet, tablet_extra, laptop, widescreen } =
                                    elementorFrontend.config.responsive.breakpoints;
                                const activeBps = elementorFrontend.config.responsive.activeBreakpoints;
                                let cMobile = 1,
                                    cMobileExtra,
                                    cTablet = 2,
                                    cTabletExtra,
                                    cLaptop,
                                    cWideScreen;
                                $scope
                                    .attr("class")
                                    .split(" ")
                                    .forEach((cl) => {
                                        if (/mobile\d/.test(cl)) cMobile = parseInt(cl.slice(-1), 10);
                                        if (/mobile_extra\d/.test(cl)) cMobileExtra = parseInt(cl.slice(-1), 10);
                                        if (/tablet\d/.test(cl)) cTablet = parseInt(cl.slice(-1), 10);
                                        if (/tablet_extra\d/.test(cl)) cTabletExtra = parseInt(cl.slice(-1), 10);
                                        if (/widescreen\d/.test(cl)) cWideScreen = parseInt(cl.slice(-1), 10);
                                        if (/laptop\d/.test(cl)) cLaptop = parseInt(cl.slice(-1), 10);
                                    });

                                if (viewportWidth <= mobile.value && activeBps.mobile) {
                                    columns = cMobile;
                                    gutterHr = s.gutter_hr_mobile;
                                    gutterVr = s.gutter_vr_mobile;
                                } else if (viewportWidth <= mobile_extra.value && activeBps.mobile_extra) {
                                    columns = cMobileExtra || cTablet;
                                    gutterHr = s.gutter_hr_mobile_extra;
                                    gutterVr = s.gutter_vr_mobile_extra;
                                } else if (viewportWidth <= tablet.value && activeBps.tablet) {
                                    columns = cTablet;
                                    gutterHr = s.gutter_hr_tablet;
                                    gutterVr = s.gutter_vr_tablet;
                                } else if (viewportWidth <= tablet_extra.value && activeBps.tablet_extra) {
                                    columns = cTabletExtra || cTablet;
                                    gutterHr = s.gutter_hr_tablet_extra;
                                    gutterVr = s.gutter_vr_tablet_extra;
                                } else if (viewportWidth <= laptop.value && activeBps.laptop) {
                                    columns = cLaptop || columnsDesktop;
                                    gutterHr = s.gutter_hr_laptop;
                                    gutterVr = s.gutter_vr_laptop;
                                } else if (viewportWidth <= widescreen.value) {
                                    columns = columnsDesktop;
                                    gutterHr = s.gutter_hr;
                                    gutterVr = s.gutter_vr;
                                } else {
                                    columns = cWideScreen || columnsDesktop;
                                    gutterHr = s.gutter_hr_widescreen;
                                    gutterVr = s.gutter_vr_widescreen;
                                }
                                if (columns > 8) columns = 8;
                                items.outerWidth(Math.floor(contWidth / columns - gutterHr));
                                items.css("margin-bottom", `${gutterVr}px`);

                                if (layout === "list") {
                                    handleListLayout(s, items);
                                }

                                const isoLayoutMode = layout === "list" ? "fitRows" : layout;
                                const transDuration = s.filters_animation === "default" ? 400 : 0;

                                $grid.isotopekng({
                                    layoutMode: isoLayoutMode,
                                    masonry: { gutter: gutterHr },
                                    fitRows: { gutter: gutterHr },
                                    transitionDuration: transDuration,
                                    percentPosition: true,
                                });
                            }

                            // Handle "list" layout adjustments.
                            function handleListLayout(s, items) {
                                const imageHeight = items.find(".king-addons-grid-image-wrap").outerHeight();
                                items.find(".king-addons-grid-item-below-content").css("min-height", `${imageHeight}px`);

                                if ($body.width() < 480) {
                                    items.find(".king-addons-grid-media-wrap").css({ float: "none", width: "100%" });
                                    items.find(".king-addons-grid-item-below-content").css({
                                        float: "none",
                                        width: "100%",
                                        "min-height": "0",
                                    });
                                } else {
                                    const { media_align: align, media_width: mWidth, media_distance: mDistance } = s;
                                    if (align === "zigzag") {
                                        handleZigzag(items, mWidth, mDistance);
                                    } else {
                                        items.find(".king-addons-grid-media-wrap").css({
                                            float: align,
                                            width: `${mWidth}%`,
                                            [`margin-${align === "left" ? "right" : "left"}`]: `${mDistance}px`,
                                        });
                                        items.find(".king-addons-grid-item-below-content").css({
                                            float: align,
                                            width: `calc((100% - ${mWidth}%) - ${mDistance}px)`,
                                        });
                                    }
                                }
                            }

                            // Alternate ("zigzag") layout for list items.
                            function handleZigzag(items, mWidth, mDistance) {
                                items.filter(":even").each(function () {
                                    $(this).find(".king-addons-grid-media-wrap").css({
                                        float: "left",
                                        width: `${mWidth}%`,
                                        "margin-right": `${mDistance}px`,
                                    });
                                    $(this).find(".king-addons-grid-item-below-content").css({
                                        float: "left",
                                        width: `calc((100% - ${mWidth}%) - ${mDistance}px)`,
                                    });
                                });
                                items.filter(":odd").each(function () {
                                    $(this).find(".king-addons-grid-media-wrap").css({
                                        float: "right",
                                        width: `${mWidth}%`,
                                        "margin-left": `${mDistance}px`,
                                    });
                                    $(this).find(".king-addons-grid-item-below-content").css({
                                        float: "right",
                                        width: `calc((100% - ${mWidth}%) - ${mDistance}px)`,
                                    });
                                });
                            }

                            // Equalize grid item heights for "fitRows" layout.
                            function setEqualHeight(s) {
                                if (s.layout === "fitRows") {
                                    const $items = $grid.children("article");
                                    const columns = Math.floor($grid.outerWidth() / $items.outerWidth());
                                    if (columns > 1) {
                                        const maxH = Math.max(...$items.map((_, el) => $(el).outerHeight()).get());
                                        $items.css("height", `${maxH}px`);
                                        if (s.stick_last_element_to_bottom === "yes") {
                                            $scope.addClass("king-addons-grid-last-element-yes");
                                        }
                                    }
                                }
                            }

                            // Update filter counts and set up filtering behavior.
                            function isotopeFilters(s, ev = "load") {

                                console.log("isotopeFilters");

                                if (s.filters_count === "yes") {
                                    $scope.find(".king-addons-grid-filters a, .king-addons-grid-filters span").each(function () {
                                        const $el = $(this);
                                        if (s.grid_settings && ev === "load") {
                                            const thisTaxonomy =
                                                $el.attr("data-filter") !== "*" ? $el.data("ajax-filter")[0] : "*";
                                            const thisFilter =
                                                $el.attr("data-filter") !== "*" ? $el.data("ajax-filter")[1] : "*";
                                            console.log('CURRENT OFFSET = ' + (+s.grid_settings.query_offset + $grid.find(".king-addons-grid-item").length))
                                            $.ajax({
                                                type: "POST",
                                                url: KingAddonsGridData.ajaxUrl,
                                                data: {
                                                    action: s.grid_settings
                                                        ? "king_addons_get_filtered_count"
                                                        : "king_addons_get_filtered_count",
                                                    // king_addons_offset: 0,
                                                    king_addons_offset: +s.grid_settings.query_offset + $grid.find(".king-addons-grid-item").length,
                                                    king_addons_filter: thisFilter,
                                                    king_addons_taxonomy: thisTaxonomy,
                                                    grid_settings: s.grid_settings,
                                                },
                                                success: (response) => {
                                                    console.log('ISOTOPE GRID');
                                                    $el.find("sup").text(response.data.query_found);
                                                },
                                            });
                                        } else {
                                            if ($el.attr("data-filter") === "*") {
                                                $el.find("sup").text(
                                                    $scope.find(".king-addons-grid-filters").next().find("article").length
                                                );
                                            } else {
                                                $el.find("sup").text($scope.find($el.attr("data-filter")).length);
                                            }
                                        }
                                    });
                                }

                                if (s.filters_linkable === "yes") return;

                                // Deeplinking support.
                                if (s.deeplinking === "yes") {
                                    let deepLink = window.location.hash.replace("#filter:", ".");
                                    if (window.location.hash.match("#filter:all")) deepLink = "*";
                                    const activeFilter = $scope
                                        .find(`.king-addons-grid-filters span[data-filter="${deepLink}"]`)
                                        .not(".king-addons-back-filter");
                                    $scope.find(".king-addons-grid-filters span").removeClass("king-addons-active-filter");
                                    activeFilter.addClass("king-addons-active-filter");
                                    $grid.isotopekng({ filter: deepLink });
                                    s.lightbox.selector =
                                        deepLink === "*" ? ".king-addons-grid-image-wrap" : `${deepLink} .king-addons-grid-image-wrap`;
                                    // lightboxPopup(s);
                                }

                                // Hide empty filters.
                                if (s.filters_hide_empty === "yes" && !s.grid_settings) {
                                    $scope.find(".king-addons-grid-filters span").each(function () {
                                        const filterClass = $(this).attr("data-filter");
                                        if (filterClass !== "*" && $grid.find(filterClass).length === 0) {
                                            $(this).parent("li").addClass("king-addons-hidden-element");
                                        } else {
                                            $(this).parent("li").removeClass("king-addons-hidden-element");
                                        }
                                    });
                                }

                                // Default filter.
                                if (
                                    !$scope.hasClass("elementor-widget-king-addons-woocommerce-category-grid-pro") &&
                                    !$scope.hasClass("elementor-widget-king-addons-category-grid-pro") &&
                                    s.filters_default_filter
                                ) {
                                    setTimeout(() => {
                                        const filterEl = $scope
                                            .find(".king-addons-grid-filters")
                                            .find(`span[data-filter*="-${s.filters_default_filter}"]`);
                                        if (filterEl.length) filterEl[0].click();
                                    }, 100);
                                }

                                // Filter click behavior.
                                if (!s.grid_settings) {
                                    $scope.find(".king-addons-grid-filters span").on("click", function () {
                                        const filterClass = $(this).data("filter");
                                        $scope.find(".king-addons-grid-filters span").removeClass("king-addons-active-filter");
                                        $(this).addClass("king-addons-active-filter");
                                        if (s.deeplinking === "yes") {
                                            const filterHash =
                                                filterClass === "*" ? "#filter:all" : `#filter:${filterClass.replace(".", "")}`;
                                            window.location.href =
                                                window.location.pathname + window.location.search + filterHash;
                                        }
                                        if (["infinite-scroll", "load-more"].includes(s.pagination_type)) {
                                            if ($grid.find($(this).attr("data-filter")).length === 0) {
                                                $grid.infiniteScroll("loadNextPage");
                                            }
                                        }
                                        if (s.filters_animation !== "default") {
                                            $scope.find(".king-addons-grid-item-inner").css({
                                                opacity: "0",
                                                transition: "none",
                                            });
                                            if (s.filters_animation === "fade-slide") {
                                                $scope.find(".king-addons-grid-item-inner").css("top", "20px");
                                            } else if (s.filters_animation === "zoom") {
                                                $scope.find(".king-addons-grid-item-inner").css("transform", "scale(0.01)");
                                            }
                                        }
                                        $grid.isotopekng({ filter: filterClass });
                                        s.lightbox.selector =
                                            filterClass === "*" ? ".king-addons-grid-image-wrap" : `${filterClass} .king-addons-grid-image-wrap`;
                                        $grid.data("lightGallery").destroy(true);
                                        $grid.lightGallery(s.lightbox);
                                    });
                                }
                            }

                            // Experiment: dynamic filtering via AJAX.
                            function filtersExperiment(settings) {
                                const countAction = $scope.hasClass("elementor-widget-king-addons-woocommerce-grid")
                                    ? "king_addons_get_woocommerce_filtered_count"
                                    : "king_addons_get_filtered_count";
                                const contentAction = $scope.hasClass("elementor-widget-king-addons-woocommerce-grid")
                                    ? "king_addons_filter_woocommerce_products"
                                    : "king_addons_filter_grid_posts";
                                const $pagination = $scope.find(".king-addons-grid-pagination");

                                let isLoading = false;

                                $scope.find(".king-addons-grid-filters").on("click", "span", function (e) {
                                    e.preventDefault();
                                    e.stopPropagation();
                                    e.stopImmediatePropagation();

                                    if (isLoading) {
                                        e.preventDefault();
                                        return;
                                    }

                                    isLoading = true;

                                    const filterClass = $(this).data("filter");
                                    const thisTaxonomy = filterClass !== "*" ? $(this).data("ajax-filter")[0] : "*";
                                    const thisFilter = filterClass !== "*" ? $(this).data("ajax-filter")[1] : "*";
                                    const loader = `<div class="king-addons-grid-loader-wrap"><div class="king-addons-ring"><div></div><div></div><div></div><div></div></div></div>`;


                                    // let thisTaxonomy = "*";
                                    // let thisFilter = "*";
                                    // const $activeFilter = $scope.find(".king-addons-active-filter");
                                    // if ($activeFilter.length && $activeFilter.data("filter") !== "*") {
                                    //     thisTaxonomy = $activeFilter.data("ajax-filter")[0];
                                    //     thisFilter = $activeFilter.data("ajax-filter")[1];
                                    // }

                                    $scope.find(".king-addons-grid-filters span").removeClass("king-addons-active-filter");
                                    $(this).addClass("king-addons-active-filter");

                                    $pagination.find(".king-addons-load-more-btn").hide();

                                    $grid.infiniteScroll("destroy");
                                    // setupInfiniteScroll(settings);
                                    // $grid.isotopekng("destroy");
                                    pagesLoaded = 0;
                                    // $grid.isotopekng("destroy");

                                    $grid.html(loader);

                                    console.log('FILTER');

                                    $.ajax({
                                        type: "POST",
                                        url: KingAddonsGridData.ajaxUrl,
                                        data: {
                                            action: countAction,
                                            king_addons_offset:
                                                +settings.grid_settings.query_offset +
                                                $scope.find(".king-addons-grid-item").length,
                                            king_addons_filter: thisFilter,
                                            king_addons_taxonomy: thisTaxonomy,
                                            grid_settings: settings.grid_settings,
                                        },
                                        success: function (res) {
                                            console.log("settings.grid_settings.query_offset = " + settings.grid_settings.query_offset);
                                            $.ajax({
                                                type: "POST",
                                                url: KingAddonsGridData.ajaxUrl,
                                                data: {
                                                    action: contentAction,
                                                    king_addons_item_length:
                                                        +settings.grid_settings.query_offset +
                                                        $scope.find(".king-addons-grid-item").length,
                                                    king_addons_filter: thisFilter,
                                                    king_addons_taxonomy: thisTaxonomy,
                                                    grid_settings: settings.grid_settings,
                                                },
                                                success: function (resp) {
                                                    // setTimeout(() => {
                                                        $grid.addClass("king-addons-zero-opacity");

                                                    console.log("FILTER pagesLoaded = " + pagesLoaded);
                                                        // $grid.infiniteScroll("destroy");
                                                        $grid.isotopekng("destroy");

                                                        $grid.html($(resp));

                                                        isotopeLayout(settings, $(resp));
                                                        $grid.imagesLoaded().progress(() => {
                                                            isotopeLayout(settings);
                                                            window.dispatchEvent(new Event("resize"));
                                                            window.dispatchEvent(new Event("scroll"));

                                                            $pagination.find(".king-addons-pagination-finish").hide();
                                                            if (res.data.page_count > 1) {
                                                                $pagination.find(".king-addons-load-more-btn").show();
                                                                $pagination.show();
                                                            } else {
                                                                $pagination.find(".king-addons-pagination-finish").fadeIn(1000);
                                                                $pagination.delay(2000).fadeOut(1000);
                                                                setTimeout(() => $pagination.find(".king-addons-pagination-loading").hide(), 500);
                                                            }

                                                            setupInfiniteScroll(settings);
                                                            loadMoreExperiment();

                                                            mediaHoverLink();
                                                            $grid.removeClass("king-addons-zero-opacity");
                                                        });
                                                    // }, 800);
                                                },
                                            });
                                        },
                                        complete: function () {
                                            isLoading = false;
                                        }
                                    });
                                });
                            }

                            // Experiment: load-more via AJAX.
                            function loadMoreExperiment() {
                                const countAction = $scope.hasClass("elementor-widget-king-addons-woocommerce-grid")
                                    ? "king_addons_get_woocommerce_filtered_count"
                                    : "king_addons_get_filtered_count";
                                const contentAction = $scope.hasClass("elementor-widget-king-addons-woocommerce-grid")
                                    ? "king_addons_filter_woocommerce_products"
                                    : "king_addons_filter_grid_posts";
                                const $pagination = $scope.find(".king-addons-grid-pagination");

                                let isLoading = false;

                                $scope.find(".king-addons-load-more-btn").on("click", function (e) {
                                    e.preventDefault();
                                    e.stopPropagation();
                                    e.stopImmediatePropagation();

                                    if (isLoading) {
                                        e.preventDefault();
                                        return;
                                    }

                                    isLoading = true;

                                    let thisTaxonomy = "*";
                                    let thisFilter = "*";
                                    const $activeFilter = $scope.find(".king-addons-active-filter");
                                    if ($activeFilter.length && $activeFilter.data("filter") !== "*") {
                                        thisTaxonomy = $activeFilter.data("ajax-filter")[0];
                                        thisFilter = $activeFilter.data("ajax-filter")[1];
                                    }

                                    $pagination.find(".king-addons-load-more-btn").hide();
                                    $pagination.find(".king-addons-pagination-loading").css("display", "inline-block");

                                    $.ajax({
                                        type: "POST",
                                        url: KingAddonsGridData.ajaxUrl,
                                        data: {
                                            action: countAction,
                                            king_addons_offset:
                                                +settings.grid_settings.query_offset +
                                                $grid.find(".king-addons-grid-item").length,
                                            king_addons_filter: thisFilter,
                                            king_addons_taxonomy: thisTaxonomy,
                                            grid_settings: settings.grid_settings,
                                        },
                                        success: function (responseCountAction) {
                                            $.ajax({
                                                type: "POST",
                                                url: KingAddonsGridData.ajaxUrl,
                                                data: {
                                                    action: contentAction,
                                                    king_addons_offset:
                                                        +settings.grid_settings.query_offset +
                                                        $grid.find(".king-addons-grid-item").length,
                                                    king_addons_filter: thisFilter,
                                                    king_addons_taxonomy: thisTaxonomy,
                                                    grid_settings: settings.grid_settings,
                                                },
                                                success: function (responseContentAction) {
                                                    const $items = $(responseContentAction);
                                                    $grid.infiniteScroll("appendItems", $items);
                                                    $grid.isotopekng("appended", $items);
                                                    $items.imagesLoaded().progress(() => {
                                                        isotopeLayout(settings);
                                                        setTimeout(() => isotopeLayout(settings), 100);
                                                        setTimeout(() => $grid.addClass("grid-images-loaded"), 500);
                                                    });
                                                    $pagination.find(".king-addons-pagination-loading").hide();
                                                    if (responseCountAction.data.page_count > 1) {
                                                        $pagination.find(".king-addons-load-more-btn").fadeIn();
                                                    } else {
                                                        $pagination.find(".king-addons-pagination-finish").fadeIn(1000);
                                                        $pagination.delay(2000).fadeOut(1000);
                                                        setTimeout(() => $pagination.find(".king-addons-pagination-loading").hide(), 500);
                                                    }
                                                    // lightboxPopup(settings);
                                                    $grid.data("lightGallery").destroy(true);
                                                    $grid.lightGallery(settings.lightbox);
                                                    mediaHoverLink();
                                                    postSharing();
                                                    window.dispatchEvent(new Event("resize"));
                                                    window.dispatchEvent(new Event("scroll"));
                                                },
                                            });
                                        },
                                        complete: function () {
                                            isLoading = false;
                                        }
                                    });


                                });
                            }

                            // Setup Infinite Scroll.
                            function setupInfiniteScroll(s) {


                                const countAction = $scope.hasClass("elementor-widget-king-addons-woocommerce-grid")
                                    ? "king_addons_get_woocommerce_filtered_count"
                                    : "king_addons_get_filtered_count";
                                const contentAction = $scope.hasClass("elementor-widget-king-addons-woocommerce-grid")
                                    ? "king_addons_filter_woocommerce_products"
                                    : "king_addons_filter_grid_posts";

                                const $pagination = $scope.find(".king-addons-grid-pagination");

                                const scopeClass = `.elementor-element-${$scope.attr("data-id")}`;

                                let navClass = false,
                                    threshold = false;
                                if (s.pagination_type === "infinite-scroll") {
                                    threshold = 300;
                                    navClass = `${scopeClass} .king-addons-load-more-btn`;
                                }
                                // todo
                                $grid.infiniteScroll({
                                    path: `${scopeClass} .king-addons-grid-pagination a`,
                                    hideNav: navClass,
                                    append: false,
                                    history: false,
                                    scrollThreshold: threshold,
                                    status: `${scopeClass} .page-load-status`,
                                    onInit() {
                                        this.on("load", () => $grid.removeClass("grid-images-loaded"));
                                    },
                                });
                                $grid.on("request.infiniteScroll", () => {
                                    $pagination.find(".king-addons-load-more-btn").hide();
                                    $pagination.find(".king-addons-pagination-loading").css("display", "inline-block");
                                });

                                $grid.on("load.infiniteScroll", (event, response) => {
                                    console.log("pagesLoaded = " + pagesLoaded);

                                    pagesLoaded++;

                                    const $items = $(response).find(scopeClass).find(".king-addons-grid-item");

                                    console.log("$items = " + $items);
                                    console.log("$items.length = " + $items.length);

                                    if ($scope.find(".woocommerce-result-count").length) {
                                        let updatedCount = $scope.find(".woocommerce-result-count").text();
                                        updatedCount = updatedCount.replace(
                                            /\d\u2013\d+/,
                                            `1–${$scope.find(".king-addons-grid-item").length + $items.length}`
                                        );
                                        $scope.find(".woocommerce-result-count").text(updatedCount);
                                    }


                                    $grid.infiniteScroll("appendItems", $items);
                                    $grid.isotopekng("appended", $items);
                                    $items.imagesLoaded().progress(() => {
                                        isotopeLayout(s);
                                        setTimeout(() => {
                                            // isotopeLayout(s);
                                            // isotopeFilters(s);
                                        //     ===============================

                                        //     ================================
                                        }, 10);
                                        setTimeout(() => $grid.addClass("grid-images-loaded"), 500);
                                    });

                                    // =====================


                                    // let thisTaxonomy = "*";
                                    // let thisFilter = "*";
                                    // const $activeFilter = $scope.find(".king-addons-active-filter");
                                    // if ($activeFilter.length && $activeFilter.data("filter") !== "*") {
                                    //     thisTaxonomy = $activeFilter.data("ajax-filter")[0];
                                    //     thisFilter = $activeFilter.data("ajax-filter")[1];
                                    // }
                                    //
                                    // $pagination.find(".king-addons-load-more-btn").hide();
                                    // $pagination.find(".king-addons-pagination-loading").css("display", "inline-block");
                                    //
                                    // $.ajax({
                                    //     type: "POST",
                                    //     url: KingAddonsGridData.ajaxUrl,
                                    //     data: {
                                    //         action: countAction,
                                    //         king_addons_offset:
                                    //             +settings.grid_settings.query_offset +
                                    //             $grid.find(".king-addons-grid-item").length,
                                    //         king_addons_filter: thisFilter,
                                    //         king_addons_taxonomy: thisTaxonomy,
                                    //         grid_settings: settings.grid_settings,
                                    //     },
                                    //     success: function (responseCountAction) {
                                    //         console.log('responseCountAction = ' + responseCountAction.data.page_count);
                                    //         $.ajax({
                                    //             type: "POST",
                                    //             url: KingAddonsGridData.ajaxUrl,
                                    //             data: {
                                    //                 action: contentAction,
                                    //                 king_addons_offset:
                                    //                     +settings.grid_settings.query_offset +
                                    //                     $grid.find(".king-addons-grid-item").length,
                                    //                 king_addons_filter: thisFilter,
                                    //                 king_addons_taxonomy: thisTaxonomy,
                                    //                 grid_settings: settings.grid_settings,
                                    //             },
                                    //             success: function (responseContentAction) {
                                    //                 const $items = $(responseContentAction);
                                    //                 $grid.infiniteScroll("appendItems", $items);
                                    //                 $grid.isotopekng("appended", $items);
                                    //                 $items.imagesLoaded().progress(() => {
                                    //                     isotopeLayout(settings);
                                    //                     setTimeout(() => isotopeLayout(settings), 100);
                                    //                     setTimeout(() => $grid.addClass("grid-images-loaded"), 500);
                                    //                 });
                                    //                 $pagination.find(".king-addons-pagination-loading").hide();
                                    //                 if (responseCountAction.data.page_count > 1) {
                                    //                     $pagination.find(".king-addons-load-more-btn").fadeIn();
                                    //                 } else {
                                    //                     $pagination.find(".king-addons-pagination-finish").fadeIn(1000);
                                    //                     $pagination.delay(2000).fadeOut(1000);
                                    //                     setTimeout(() => $pagination.find(".king-addons-pagination-loading").hide(), 500);
                                    //                 }
                                    //                 lightboxPopup(settings);
                                    //                 $grid.data("lightGallery").destroy(true);
                                    //                 $grid.lightGallery(settings.lightbox);
                                    //                 mediaHoverLink();
                                    //                 postSharing();
                                    //                 window.dispatchEvent(new Event("resize"));
                                    //                 window.dispatchEvent(new Event("scroll"));
                                    //
                                    //             },
                                    //         });
                                    //     },
                                    //     // complete: function () {
                                    //     //     isLoading = false;
                                    //     // }
                                    // });

                                    // =====================
                                    $pagination.find(".king-addons-pagination-loading").hide();
                                    if (pagesLoaded < s.pagination_max_pages) {
                                        if (s.pagination_type === "load-more") {
                                            $pagination.find(".king-addons-load-more-btn").fadeIn();
                                            if ($scope.find(".king-addons-grid-filters").length) {
                                                const activeF = $scope.find(".king-addons-active-filter");
                                                if (activeF.length && activeF.attr("data-filter") !== "*") {
                                                    const filterClass = activeF.attr("data-filter").slice(1);
                                                    let foundOne = false;
                                                    $items.each(function () {
                                                        if ($(this).hasClass(filterClass)) {
                                                            foundOne = true;
                                                            return false;
                                                        }
                                                    });
                                                    if (!foundOne) $grid.infiniteScroll("loadNextPage");
                                                }
                                            }
                                        }
                                    } else {
                                        $pagination.find(".king-addons-pagination-finish").fadeIn(1000);
                                        $pagination.delay(2000).fadeOut(1000);
                                        setTimeout(() => $pagination.find(".king-addons-pagination-loading").hide(), 500);
                                    }
                                    // lightboxPopup(s);
                                    $grid.data("lightGallery").destroy(true);
                                    $grid.lightGallery(s.lightbox);
                                    mediaHoverLink();
                                    postSharing();
                                    setTimeout(() => {
                                        setEqualHeight(s);
                                        window.dispatchEvent(new Event("resize"));
                                    }, 500);
                                });
                                $pagination.find(".king-addons-load-more-btn").on("click", () => {
                                    $grid.infiniteScroll("loadNextPage");
                                    return false;
                                });
                            }

                            // Initialize the Slick slider.
                            function initSlickSlider() {
                                const settingsSlick = JSON.parse($grid.attr("data-slick") || "{}");
                                $grid.slick({
                                    appendDots: $scope.find(".king-addons-grid-slider-dots"),
                                    rows: settingsSlick.sliderRows,
                                    customPaging: () => `<span class="king-addons-grid-slider-dot"></span>`,
                                    slidesToShow: getSliderColumns("desktop"),
                                    responsive: getSliderResponsiveOptions(settingsSlick),
                                });
                                handleSlickArrows();
                                handleSlickDots();
                            }

                            // Get the number of slider columns.
                            function getSliderColumns(type) {
                                const match = $scope.attr("class").match(/king-addons-grid-slider-columns-(\d)/);
                                return type === "desktop" ? (match ? parseInt(match[1], 10) : 2) : 2;
                            }

                            // Get responsive slider options.
                            function getSliderResponsiveOptions(sl) {
                                const className = $scope.attr("class");
                                const colDesktop = getSliderColumns("desktop");
                                const colWide = extractColumns(className, "widescreen") || colDesktop;
                                const colLaptop = extractColumns(className, "laptop") || colDesktop;
                                const colTablet = extractColumns(className, "tablet") || 2;
                                const colTabletExtra = extractColumns(className, "tablet_extra") || colTablet;
                                const colMobileExtra = extractColumns(className, "mobile_extra") || colTablet;
                                const colMobile = extractColumns(className, "mobile") || 1;
                                const slidesToScroll = sl.sliderSlidesToScroll;
                                const adjust = (cols) => (slidesToScroll > cols ? 1 : slidesToScroll);
                                return [
                                    { breakpoint: 10000, settings: { slidesToShow: colWide, slidesToScroll: adjust(colWide) } },
                                    { breakpoint: 2399, settings: { slidesToShow: colDesktop, slidesToScroll: adjust(colDesktop) } },
                                    { breakpoint: 1221, settings: { slidesToShow: colLaptop, slidesToScroll: adjust(colLaptop) } },
                                    { breakpoint: 1200, settings: { slidesToShow: colTabletExtra, slidesToScroll: adjust(colTabletExtra) } },
                                    { breakpoint: 1024, settings: { slidesToShow: colTablet, slidesToScroll: adjust(colTablet) } },
                                    { breakpoint: 880, settings: { slidesToShow: colMobileExtra, slidesToScroll: adjust(colMobileExtra) } },
                                    { breakpoint: 768, settings: { slidesToShow: colMobile, slidesToScroll: adjust(colMobile) } },
                                ];
                            }

                            // Extract a number of columns from a class name.
                            function extractColumns(className, type) {
                                const match = className.match(new RegExp(`columns--${type}(\\d)`));
                                return match ? parseInt(match[1], 10) : false;
                            }

                            // Adjust the slider arrows based on available space.
                            function handleSlickArrows() {
                                const $prevArrow = $scope.find(".king-addons-grid-slider-prev-arrow");
                                const $nextArrow = $scope.find(".king-addons-grid-slider-next-arrow");
                                if (!$prevArrow.length || !$nextArrow.length) return;
                                const positionSum = $prevArrow.position().left * -2;
                                $(window).on("load", checkArrows);
                                $(window).smartresize(checkArrows);
                                function checkArrows() {
                                    if (
                                        $(window).width() <=
                                        $scope.outerWidth() +
                                        $prevArrow.outerWidth() +
                                        $nextArrow.outerWidth() +
                                        positionSum
                                    ) {
                                        $prevArrow.addClass("king-addons-adjust-slider-prev-arrow");
                                        $nextArrow.addClass("king-addons-adjust-slider-next-arrow");
                                    } else {
                                        $prevArrow.removeClass("king-addons-adjust-slider-prev-arrow");
                                        $nextArrow.removeClass("king-addons-adjust-slider-next-arrow");
                                    }
                                }
                            }

                            // Adjust slider dots if needed.
                            function handleSlickDots() {
                                if (
                                    $scope.find(".slick-dots").length &&
                                    $scope.hasClass("king-addons-grid-slider-dots-horizontal")
                                ) {
                                    resizeDots();
                                    $(window).smartresize(() => setTimeout(resizeDots, 300));
                                }
                                function resizeDots() {
                                    const $dots = $scope.find(".slick-dots li");
                                    const marginRight = parseInt($dots.find("span").css("margin-right"), 10);
                                    const width = $dots.outerWidth() * $dots.length - marginRight;
                                    $scope.find(".slick-dots").css("width", width);
                                }
                            }

                            // Setup "Add to Cart" behavior.
                            function setupAddToCart() {
                                const $addCartIcon = $grid.find(".king-addons-grid-item-add-to-cart i");
                                let addCartIconClass = $addCartIcon.attr("class") || "";
                                if (addCartIconClass) {
                                    addCartIconClass = addCartIconClass.substring(
                                        addCartIconClass.indexOf("fa-")
                                    );
                                }
                                $body.on("adding_to_cart", (ev, btn) => btn.fadeTo("slow", 0));
                                $body.on("added_to_cart", (ev, fragments, hash, btn) => {
                                    const productId = btn.data("product_id");
                                    btn.next().fadeTo(700, 1).css("display", "inline-block");
                                    btn.css("display", "none");
                                    if (btn.data("atc-popup") === "sidebar") {
                                        $(".king-addons-mini-cart-toggle-wrap a").each(function () {
                                            const $miniCart = $(this)
                                                .closest(".king-addons-mini-cart-inner")
                                                .find(".king-addons-mini-cart");
                                            if ($miniCart.css("display") === "none") $(this).trigger("click");
                                        });
                                    } else if (btn.data("atc-popup") === "popup") {
                                        addToCartPopup(btn, productId);
                                    }
                                    if (addCartIconClass) {
                                        btn.find("i").removeClass(addCartIconClass).addClass("fa-check");
                                        setTimeout(() => {
                                            btn.find("i").removeClass("fa-check").addClass(addCartIconClass);
                                        }, 3500);
                                    }
                                });

                                function addToCartPopup(btn, productId) {
                                    const $popupItem = btn.closest(".king-addons-grid-item");
                                    const popupText = $popupItem.find(".king-addons-grid-item-title").text();
                                    const popupLink = btn.next().attr("href");
                                    const popupImageSrc = $popupItem.find(".king-addons-grid-image-wrap").data("src");
                                    const popupAnimation = btn.data("atc-animation");
                                    const fadeOutIn = btn.data("atc-fade-out-in");
                                    const animTime = btn.data("atc-animation-time");
                                    let animationClass = "king-addons-added-to-cart-default",
                                        removeAnimationClass = "king-addons-added-to-cart-popup-hide";
                                    const popupImage = popupImageSrc
                                        ? `<div class="king-addons-added-tc-popup-img"><img src="${popupImageSrc}" alt="" /></div>`
                                        : "";
                                    switch (popupAnimation) {
                                        case "slide-left":
                                            animationClass = "king-addons-added-to-cart-slide-in-left";
                                            removeAnimationClass = "king-addons-added-to-cart-slide-out-left";
                                            break;
                                        case "scale-up":
                                            animationClass = "king-addons-added-to-cart-scale-up";
                                            removeAnimationClass = "king-addons-added-to-cart-scale-down";
                                            break;
                                        case "skew":
                                            animationClass = "king-addons-added-to-cart-skew";
                                            removeAnimationClass = "king-addons-added-to-cart-skew-off";
                                            break;
                                        case "fade":
                                            animationClass = "king-addons-added-to-cart-fade";
                                            removeAnimationClass = "king-addons-added-to-cart-fade-out";
                                            break;
                                    }
                                    if (!$scope.find(`#king-addons-added-to-cart-${productId}`).length) {
                                        $scope
                                            .find(".king-addons-grid")
                                            .append(
                                                `<div id="king-addons-added-to-cart-${productId}" class="king-addons-added-to-cart-popup ${animationClass}">
                          ${popupImage}
                          <div class="king-addons-added-tc-title">
                            <p>${popupText} ${KingAddonsGridData.addedToCartText}</p>
                            <p><a href="${popupLink}">${KingAddonsGridData.viewCart}</a></p>
                          </div>
                        </div>`
                                            );
                                        setTimeout(() => {
                                            $scope
                                                .find(`#king-addons-added-to-cart-${productId}`)
                                                .addClass(removeAnimationClass);
                                            setTimeout(() => {
                                                $scope.find(`#king-addons-added-to-cart-${productId}`).remove();
                                            }, animTime * 1000);
                                        }, fadeOutIn * 1000);
                                    }
                                }
                            }

                            // Setup post sharing behavior.
                            function postSharing() {
                                if (!$scope.find(".king-addons-sharing-trigger").length) return;
                                const $sharingTrigger = $scope.find(".king-addons-sharing-trigger");
                                const $sharingInner = $scope.find(".king-addons-post-sharing-inner");
                                let sharingWidth = 5;
                                $sharingInner.first().find("a").each(function () {
                                    sharingWidth += $(this).outerWidth() + parseInt($(this).css("margin-right"), 10);
                                });
                                const direction = $sharingTrigger.attr("data-direction");
                                if (direction === "left" || direction === "right") {
                                    $sharingInner.css("width", `${sharingWidth}px`);
                                    if (direction === "left") {
                                        $sharingInner.css(
                                            "left",
                                            -(parseInt($sharingInner.find("a").css("margin-right"), 10) + sharingWidth) + "px"
                                        );
                                    } else {
                                        $sharingInner.css({ left: $sharingTrigger.css("margin-right") });
                                    }
                                } else if (direction === "top") {
                                    const margin = parseInt($sharingInner.find("a").css("margin-right"), 10);
                                    $sharingInner.find("a").css({ "margin-right": "0", "margin-top": `${margin}px` });
                                    $sharingInner.css({
                                        top: `-${margin}px`,
                                        left: "50%",
                                        transform: "translate(-50%, -100%)",
                                    });
                                } else if (direction === "bottom") {
                                    const margin = parseInt($sharingInner.find("a").css("margin-right"), 10);
                                    $sharingInner.find("a").css({ "margin-right": "0", "margin-bottom": `${margin}px` });
                                    $sharingInner.css({
                                        bottom: `-${margin}px`,
                                        left: "50%",
                                        transform: "translate(-50%, 100%)",
                                    });
                                }
                                if ($sharingTrigger.attr("data-action") === "click") {
                                    $sharingTrigger.on("click", function () {
                                        const $inner = $(this).next();
                                        if ($inner.css("visibility") === "hidden") {
                                            $inner.css("visibility", "visible").find("a").css({ opacity: "1", top: "0" });
                                            setTimeout(() => $inner.find("a").addClass("king-addons-no-transition-delay"), $inner.find("a").length * 100);
                                        } else {
                                            $inner.find("a").removeClass("king-addons-no-transition-delay").css({
                                                opacity: "0",
                                                top: "-5px",
                                            });
                                            setTimeout(() => $inner.css("visibility", "hidden"), $inner.find("a").length * 100);
                                        }
                                    });
                                } else {
                                    $sharingTrigger.on("mouseenter", function () {
                                        const $inner = $(this).next();
                                        $inner.css("visibility", "visible").find("a").css({ opacity: "1", top: "0" });
                                        setTimeout(() => $inner.find("a").addClass("king-addons-no-transition-delay"), $inner.find("a").length * 100);
                                    });
                                    $scope.find(".king-addons-grid-item-sharing").on("mouseleave", function () {
                                        const $inner = $(this).find(".king-addons-post-sharing-inner");
                                        $inner.find("a").removeClass("king-addons-no-transition-delay").css({
                                            opacity: "0",
                                            top: "-5px",
                                        });
                                        setTimeout(() => $inner.css("visibility", "hidden"), $inner.find("a").length * 100);
                                    });
                                }
                            }

                            // Setup media hover behavior and overlay clickable links.
                            function mediaHoverLink() {
                                const $wrap = $grid.find(".king-addons-grid-image-wrap");
                                if ($wrap.data("img-on-hover") === "yes") {
                                    $grid.find(".king-addons-grid-media-wrap").hover(
                                        function () {
                                            const $secondImg = $(this).find("img:nth-of-type(2)");
                                            if ($secondImg.attr("src")) {
                                                $(this).find("img:first-of-type").addClass("king-addons-hidden-img");
                                                $secondImg.removeClass("king-addons-hidden-img");
                                            }
                                        },
                                        function () {
                                            const $secondImg = $(this).find("img:nth-of-type(2)");
                                            if ($secondImg.attr("src")) {
                                                $secondImg.addClass("king-addons-hidden-img");
                                                $(this).find("img:first-of-type").removeClass("king-addons-hidden-img");
                                            }
                                        }
                                    );
                                }
                                if ($wrap.attr("data-overlay-link") === "yes" && !isEditor) {
                                    $wrap.css("cursor", "pointer").on("click", function (e) {
                                        const cn = e.target.className;
                                        if (
                                            cn.indexOf("inner-block") !== -1 ||
                                            cn.indexOf("king-addons-cv-inner") !== -1 ||
                                            cn.indexOf("king-addons-grid-media-hover") !== -1
                                        ) {
                                            e.preventDefault();
                                            let itemUrl = $(this)
                                                .find(".king-addons-grid-media-hover-bg")
                                                .attr("data-url")
                                                .replace("#new_tab", "");
                                            if ($grid.find(".king-addons-grid-item-title a").attr("target") === "_blank") {
                                                window.open(itemUrl, "_blank").focus();
                                            } else {
                                                window.location.href = itemUrl;
                                            }
                                        }
                                    });
                                }
                            }

                            // Initialize lightbox popups.
                            function lightboxPopup(s) {
                                if ($scope.find(".king-addons-grid-item-lightbox").length < 0) return;
                                $grid.find(".king-addons-grid-item-lightbox").each(function () {
                                    const source = $(this).find(".inner-block > span").attr("data-src");
                                    const $article = $(this).closest("article").not(".slick-cloned");
                                    if (!$grid.hasClass("king-addons-media-grid")) {
                                        $article.find(".king-addons-grid-image-wrap").attr("data-src", source);
                                    }
                                });
                                $grid.lightGallery(s.lightbox);
                                $grid.on("onAfterOpen.lg", () => {
                                    $(".lg-outer")
                                        .find(".lg-thumb-item")
                                        .each(function () {
                                            const $img = $(this).find("img");
                                            let src = $img.attr("src");
                                            const extIndex = src.lastIndexOf(".");
                                            const ext = src.slice(extIndex);
                                            const cropIndex = src.lastIndexOf("-");
                                            const cropCandidate = src.substring(cropIndex, extIndex);
                                            const isStandardCrop = /\d{3,}x\d{3,}/.test(cropCandidate);
                                            if (!isStandardCrop && cropCandidate.length > 0) {
                                                src = src.slice(0, extIndex) + "-150x150" + ext;
                                            } else {
                                                src = src.replace(cropCandidate, "-150x150");
                                            }
                                            $img.attr("src", src);
                                        });
                                });
                                $grid.on("onAferAppendSlide.lg onAfterSlide.lg", () => {
                                    const download = $("#lg-download").attr("href");
                                    const $controls = $("#lg-actual-size, #lg-zoom-in, #lg-zoom-out, #lg-download");
                                    if (download && download.indexOf("wp-content") === -1) {
                                        $controls.addClass("king-addons-hidden-element");
                                    } else {
                                        $controls.removeClass("king-addons-hidden-element");
                                    }
                                    if (!s.lightbox.autoplay) {
                                        $(".lg-autoplay-button").css({ width: "0", height: "0", overflow: "hidden" });
                                    }
                                });
                                const $overlay = $scope.find(".king-addons-grid-lightbox-overlay");
                                if ($overlay.length) {
                                    $scope.find(".king-addons-grid-media-hover-bg").after($overlay.remove());
                                    $scope.find(".king-addons-grid-lightbox-overlay").on("click", function () {
                                        if (!isEditor) {
                                            $(this).closest("article").find(".king-addons-grid-image-wrap").trigger("click");
                                        } else {
                                            alert(
                                                "Lightbox is Disabled in the Editor! Please Preview this Page to see it in action."
                                            );
                                        }
                                    });
                                } else {
                                    $scope.find(".king-addons-grid-item-lightbox .inner-block > span").on("click", function () {
                                        if (!isEditor) {
                                            $(this).closest("article").find(".king-addons-grid-image-wrap").trigger("click");
                                        } else {
                                            alert(
                                                "Lightbox is Disabled in the Editor! Please Preview this Page to see it in action."
                                            );
                                        }
                                    });
                                }
                            }

                            // Handle post likes.
                            function postLikes() {
                                if (!$scope.find(".king-addons-post-like-button").length) return;
                                $scope.on("click", ".king-addons-post-like-button", function (e) {
                                    e.preventDefault();
                                    const $btn = $(this);
                                    if (!$btn.attr("data-post-id")) return false;
                                    $.ajax({
                                        type: "POST",
                                        url: $btn.attr("data-ajax"),
                                        data: {
                                            action: "king_addons_likes_init",
                                            post_id: $btn.attr("data-post-id"),
                                            nonce: $btn.attr("data-nonce"),
                                        },
                                        beforeSend: () => $btn.fadeTo(500, 0.5),
                                        success: function (response) {
                                            let iconClass = $btn.attr("data-icon");
                                            let countHTML = response.count;
                                            if (!countHTML.replace(/<\/?[^>]+(>|$)/g, "")) {
                                                countHTML = `<span class="king-addons-post-like-count">${$btn.attr("data-text")}</span>`;
                                                $btn.addClass("king-addons-likes-zero");
                                            } else {
                                                $btn.removeClass("king-addons-likes-zero");
                                            }
                                            if ($btn.hasClass("king-addons-already-liked")) {
                                                $btn.prop("title", "Like").removeClass("king-addons-already-liked");
                                                $btn.html(`<i class="${iconClass.replace("fas", "far")}"></i>${countHTML}`);
                                            } else {
                                                $btn.prop("title", "Unlike").addClass("king-addons-already-liked");
                                                $btn.html(`<i class="${iconClass.replace("far", "fas")}"></i>${countHTML}`);
                                            }
                                            $btn.fadeTo(500, 1);
                                        },
                                    });
                                    return false;
                                });
                            }

                            // Animate grid items after layout.
                            function animateGridItems(filteredItems, s, $grid) {
                                let initStager = 0,
                                    filterStager = 0,
                                    deepLinkStager = 0;
                                if (!$grid.hasClass("grid-images-loaded")) $grid.css("opacity", "1");
                                filteredItems.forEach((item) => {
                                    initStager += s.animation_delay;
                                    $(item.element)
                                        .find(".king-addons-grid-item-inner")
                                        .css({
                                            opacity: "1",
                                            top: "0",
                                            transform: "scale(1)",
                                            transition: `all ${s.animation_duration}s ease-in ${initStager}s`,
                                        });
                                    filterStager += s.filters_animation_delay;
                                    if ($grid.hasClass("grid-images-loaded")) {
                                        $(item.element)
                                            .find(".king-addons-grid-item-inner")
                                            .css({
                                                transition: `all ${s.filters_animation_duration}s ease-in ${filterStager}s`,
                                            });
                                    }
                                    let deepLink = window.location.hash;
                                    if (deepLink.includes("#filter:") && !deepLink.includes("#filter:*")) {
                                        deepLink = deepLink.replace("#filter:", "");
                                        if ($(item.element).hasClass(deepLink)) {
                                            deepLinkStager += s.filters_animation_delay;
                                            $(item.element)
                                                .find(".king-addons-grid-item-inner")
                                                .css({ "transition-delay": `${deepLinkStager}s` });
                                        }
                                    }
                                });
                            }
                        },
                    }),
                    { $element: $scope }
                );
            }


        gridHooks.forEach((hook) => {
            elementorFrontend.hooks.addAction(hook, gridHandler);
        });

    });
})(jQuery);
