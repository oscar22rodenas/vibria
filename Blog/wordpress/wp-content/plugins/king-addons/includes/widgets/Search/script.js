// noinspection JSUnresolvedReference

"use strict";
(function ($) {
    $(window).on('elementor/frontend/init', function () {
        let AjaxSearchHandler = elementorModules.frontend.handlers.Base.extend({
            onInit: function onInit() {
                this.initAjaxSearch();
            },

            initAjaxSearch: function () {
                const $scope = this.$element;
                let isFound = false;
                $scope.find('.king-addons-search-form-input').on({
                    focus: function () {
                        $scope.addClass('king-addons-search-form-input-focus');
                    },
                    blur: function () {
                        $scope.removeClass('king-addons-search-form-input-focus');
                    }
                });

                if ($scope.find('.king-addons-category-select').length > 0) {

                    $(document).ready(function () {
                        let king_addons_SelectedCategory = localStorage.getItem('king_addons_SelectedCategory');
                        if (king_addons_SelectedCategory) {
                            $scope.find('.king-addons-category-select option').each(function () {
                                if ($(this).val() === king_addons_SelectedCategory) {
                                    isFound = true;
                                    $scope.find('.king-addons-category-select').val(king_addons_SelectedCategory);
                                    return false;
                                } else {
                                    $scope.find('.king-addons-category-select').val(0);
                                }
                            });
                        }
                    });

                    $scope.find('.king-addons-category-select').on('change', function (e) {

                        let selectedValue = $(this).val();
                        localStorage.setItem('king_addons_SelectedCategory', selectedValue);

                        if ($scope.find('.king-addons-search-form-input').attr('ajax-search') === 'yes') {
                            postsOffset = 0;
                            $scope.find('.king-addons-data-fetch').hide();
                            $scope.find('.king-addons-data-fetch ul').html('');
                            ajaxSearchCall($scope.find('.king-addons-search-form-input'), postsOffset, e);
                        }
                    });
                }

                let prevData;
                let searchTimeout = null;

                function ajaxSearchCall(thisObject, postsOffset, e) {
                    if (e.which === 13) {
                        return false;
                    }

                    if (searchTimeout != null) {
                        clearTimeout(searchTimeout);
                    }
                    let optionPostType = ($scope.find('.king-addons-category-select').length > 0 && $scope.find('.king-addons-category-select').find('option:selected').data('post-type'));
                    let king_addons_TaxonomyType = $scope.find('.king-addons-search-form-input').attr('king-addons-taxonomy-type');

                    if ($scope.find('.king-addons-category-select').length > 0) {
                        if (!king_addons_TaxonomyType) {
                            if ($scope.find('.king-addons-search-form-input').attr('king-addons-query-type') === 'product') {
                                king_addons_TaxonomyType = 'product_cat';
                            } else {
                                king_addons_TaxonomyType = 'category';
                            }
                        }
                    }

                    searchTimeout = setTimeout(() => {
                        let thisValue = thisObject.val();

                        $.ajax({
                            type: 'POST',
                            url: KingAddonsSearchData.ajaxUrl,
                            data: {
                                action: 'king_addons_data_fetch',
                                nonce: KingAddonsSearchData.nonce,
                                king_addons_keyword: $scope.find('.king-addons-search-form-input').val(),
                                king_addons_query_type: $scope.find('.king-addons-search-form-input').attr('king-addons-query-type'),
                                king_addons_option_post_type: optionPostType ? $scope.find('.king-addons-category-select').find('option:selected').data('post-type') : '',
                                king_addons_taxonomy_type: king_addons_TaxonomyType,
                                king_addons_category: $scope.find('.king-addons-category-select').length > 0 ? $scope.find('.king-addons-category-select').val() : '',
                                king_addons_number_of_results: $scope.find('.king-addons-search-form-input').attr('number-of-results'),
                                king_addons_search_results_offset: postsOffset,
                                king_addons_show_description: $scope.find('.king-addons-search-form-input').attr('show-description'),
                                king_addons_number_of_words: $scope.find('.king-addons-search-form-input').attr('number-of-words'),
                                king_addons_show_ajax_thumbnail: $scope.find('.king-addons-search-form-input').attr('show-ajax-thumbnails'),
                                king_addons_show_view_result_btn: $scope.find('.king-addons-search-form-input').attr('show-view-result-btn'),
                                king_addons_view_result_text: $scope.find('.king-addons-search-form-input').attr('view-result-text'),
                                king_addons_no_results: $scope.find('.king-addons-search-form-input').attr('no-results'),
                                king_addons_exclude_without_thumb: $scope.find('.king-addons-search-form-input').attr('exclude-without-thumb'),
                                king_addons_ajax_search_link_target: $scope.find('.king-addons-search-form-input').attr('link-target'),
                                king_addons_show_ps_pt: $scope.find('.king-addons-search-form-input').attr('password-protected')
                            },
                            success: function (data) {
                                $scope.closest('section').addClass('king-addons-section-z-index');
                                if ($scope.find('.king-addons-data-fetch ul').html() === '') {
                                    $scope.find('.king-addons-pagination-loading').hide();
                                    $scope.find('.king-addons-data-fetch ul').html(data);
                                    $scope.find('.king-addons-no-more-results').fadeOut(100);
                                    setTimeout(function () {
                                        if (!data.includes('king-addons-no-results')) {
                                            $scope.find('.king-addons-ajax-search-pagination').css('display', 'flex');
                                            if ($scope.find('.king-addons-data-fetch ul').find('li').length < $scope.find('.king-addons-search-form-input').attr('number-of-results') ||
                                                $scope.find('.king-addons-data-fetch ul').find('li').length === $scope.find('.king-addons-data-fetch ul').find('li').data('number-of-results')) {
                                                $scope.find('.king-addons-ajax-search-pagination').css('display', 'none');
                                                $scope.find('.king-addons-load-more-results').fadeOut(100);
                                            } else {
                                                $scope.find('.king-addons-ajax-search-pagination').css('display', 'flex');
                                                $scope.find('.king-addons-load-more-results').fadeIn(100);
                                            }
                                        } else {
                                            $scope.find('.king-addons-ajax-search-pagination').css('display', 'none');
                                        }
                                    }, 100);
                                    prevData = data;
                                } else {
                                    if (data !== prevData) {
                                        prevData = data;
                                        if (data.includes('king-addons-no-results')) {
                                            $scope.find('.king-addons-ajax-search-pagination').css('display', 'none');
                                            $scope.find('.king-addons-data-fetch ul').html('');
                                            $scope.closest('section').removeClass('king-addons-section-z-index');
                                        } else {
                                            $scope.find('.king-addons-ajax-search-pagination').css('display', 'flex');
                                        }

                                        $scope.find('.king-addons-data-fetch ul').append(data);

                                        if (data === '') {
                                            $scope.find('.king-addons-load-more-results').fadeOut(100);
                                            setTimeout(function () {
                                                $scope.find('.king-addons-pagination-loading').hide();
                                                $scope.find('.king-addons-no-more-results').fadeIn(100);
                                            }, 100);
                                        } else {
                                            $scope.find('.king-addons-pagination-loading').hide();
                                            $scope.find('.king-addons-load-more-results').show();
                                        }

                                        if ($scope.find('.king-addons-data-fetch ul').find('li').length < $scope.find('.king-addons-search-form-input').attr('number-of-results')) {
                                            $scope.find('.king-addons-load-more-results').fadeOut(100);
                                            setTimeout(function () {
                                                $scope.find('.king-addons-pagination-loading').hide();
                                                $scope.find('.king-addons-no-more-results').fadeIn(100);
                                            }, 100);
                                        } else {
                                            $scope.find('.king-addons-load-more-results').show();
                                        }

                                        if ($scope.find('.king-addons-data-fetch ul').find('li').length === $scope.find('.king-addons-data-fetch ul').find('li').data('number-of-results')) {
                                            $scope.find('.king-addons-load-more-results').fadeOut(100);
                                            setTimeout(function () {
                                                $scope.find('.king-addons-pagination-loading').hide();
                                                $scope.find('.king-addons-no-more-results').fadeIn(100);
                                            }, 100);
                                        } else {
                                            $scope.find('.king-addons-load-more-results').show();
                                        }
                                    }
                                }

                                if (data.includes('king-addons-no-results')) {
                                    $scope.find('.king-addons-ajax-search-pagination').css('display', 'none');
                                    $scope.find('.king-addons-load-more-results').fadeOut();
                                } else {
                                    $scope.find('.king-addons-ajax-search-pagination').css('display', 'flex');
                                }

                                if (thisValue.length > 2) {
                                    $scope.find('.king-addons-data-fetch').slideDown(200);
                                    $scope.find('.king-addons-data-fetch ul').fadeTo(200, 1);
                                } else {
                                    $scope.find('.king-addons-data-fetch').slideUp(200);
                                    $scope.find('.king-addons-data-fetch ul').fadeTo(200, 0);
                                    setTimeout(function () {
                                        $scope.find('.king-addons-data-fetch ul').html('');
                                        $scope.find('.king-addons-no-results').remove();
                                        $scope.closest('section').removeClass('king-addons-section-z-index');
                                    }, 600);
                                    postsOffset = 0;
                                }
                            },
                            error: function (error) {
                                console.log(error);
                            }
                        });
                    }, 400);
                }

                if ($scope.find('.king-addons-search-form-input').attr('ajax-search') === 'yes') {

                    $scope.find('.king-addons-search-form').attr('autocomplete', 'off');

                    var postsOffset = 0;

                    $scope.find('.king-addons-load-more-results').on('click', function (e) {
                        postsOffset += +$scope.find('.king-addons-search-form-input').attr('number-of-results');
                        $scope.find('.king-addons-load-more-results').hide();
                        $scope.find('.king-addons-pagination-loading').css('display', 'inline-block');
                        ajaxSearchCall($scope.find('.king-addons-search-form-input'), postsOffset, e);
                    });

                    $scope.find('.king-addons-search-form-input').on('keyup', function (e) {
                        postsOffset = 0;
                        $scope.find('.king-addons-data-fetch').hide();
                        $scope.find('.king-addons-data-fetch ul').html('');
                        ajaxSearchCall($(this), postsOffset, e);
                    });

                    $scope.find('.king-addons-data-fetch').on('click', '.king-addons-close-search', function () {
                        // noinspection DuplicatedCode
                        $scope.find('.king-addons-search-form-input').val('');
                        $scope.find('.king-addons-data-fetch').slideUp(200);
                        setTimeout(function () {
                            $scope.find('.king-addons-data-fetch ul').html('');
                            $scope.find('.king-addons-no-results').remove();
                            $scope.closest('section').removeClass('king-addons-section-z-index');
                        }, 400);
                        postsOffset = 0;
                    });

                    $('body').on('click', function (e) {
                        if (!e.target.classList.value.includes('king-addons-data-fetch') && !e.target.closest('.king-addons-data-fetch')) {
                            if (!e.target.classList.value.includes('king-addons-search-form') && !e.target.closest('.king-addons-search-form')) {
                                // noinspection DuplicatedCode
                                $scope.find('.king-addons-search-form-input').val('');
                                $scope.find('.king-addons-data-fetch').slideUp(200);
                                setTimeout(function () {
                                    $scope.find('.king-addons-data-fetch ul').html('');
                                    $scope.find('.king-addons-no-results').remove();
                                    $scope.closest('section').removeClass('king-addons-section-z-index');
                                }, 400);
                                postsOffset = 0;
                            }
                        }
                    });

                    let mutationObserver = new MutationObserver(function () {
                        $scope.find('.king-addons-data-fetch li').on('click', function () {
                            let itemUrl = $(this).find('a').attr('href');
                            let itemUrlTarget = $(this).find('a').attr('target');
                            window.open(itemUrl, itemUrlTarget).focus();
                        });
                    });

                    mutationObserver.observe($scope[0], {
                        childList: true,
                        subtree: true,
                    });
                }

            }
        });

        elementorFrontend.hooks.addAction('frontend/element_ready/king-addons-search.default', function ($scope) {
            elementorFrontend.elementsHandler.addHandler(AjaxSearchHandler, {
                $element: $scope
            });
        });
    });
})(jQuery);