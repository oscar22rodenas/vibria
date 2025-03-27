jQuery(document).ready(function ($) {
    "use strict";

    let globalS = '.global-condition-select',
        archiveS = '.archives-condition-select',
        singleS = '.singles-condition-select',
        inputIDs = '.king-addons-pb-condition-input-ids';

    let conditionsID = $('#king_addons_pb_popup_conditions');
    let conditionPopup = $('.king-addons-pb-condition-popup-wrap');
    let template_title = $('.king-addons-pb-user-template-title');
    let currentTab = 'popup';

    function getActiveFilter() {
        let type = currentTab.replace(/\W+/g, '-').toLowerCase();
        if ($('.template-filters').length > 0) {
            type = $('.template-filters .active-filter').last().attr('data-class');
            type = type.substring(0, type.length - 1);
        }
        return type;
    }

    function renderUserTemplate(type, title, slug, id) {
        let html = '';

        html += '<li>';
        html += '<h3 class="king-addons-pb-title">' + title + '</h3>';
        html += '<div class="king-addons-pb-action-buttons">';
        html += '<span class="king-addons-pb-template-conditions button-primary" data-slug="' + slug + '">Manage Conditions</span>';
        html += '<a href="post.php?post=' + id + '&action=elementor" class="king-addons-pb-edit-template button-primary">Edit Popup</a>';
        html += '<span class="king-addons-pb-delete-template button-primary" data-slug="' + slug + '" data-warning="Are you sure you want to delete this popup?"><span class="dashicons dashicons-no-alt"></span></span>';
        html += '</div>';
        html += '</li>';

        $('.king-addons-pb-my-templates-list.king-addons-pb-' + getActiveFilter() + '-templates-list').prepend(html);

        let empty_msg = $('.king-addons-pb-empty-templates-message');
        if (empty_msg.length) {
            empty_msg.remove();
        }

        changeTemplateConditions();
        deleteTemplate();
    }

    function createUserTemplate() {
        let library = 'my_templates' === getActiveFilter() ? 'elementor_library' : 'king_addons_ext_pb';
        let title = template_title.val();
        let slug = 'user-' + getActiveFilter() + '-' + title.replace(/\W+/g, '-').toLowerCase();

        if ('elementor_library' === library) {
            slug = getActiveFilter() + '-' + title.replace(/\W+/g, '-').toLowerCase();
        }

        // noinspection JSUnresolvedReference
        let data = {
            action: 'king_addons_pb_create_template',
            nonce: KingAddonsPopupBuilderOptions.nonce,
            user_template_library: library,
            user_template_title: title,
            user_template_slug: slug,
            user_template_type: getActiveFilter(),
        };

        $.post(ajaxurl, data, function (response) {

            $('.king-addons-pb-user-template-popup-wrap').fadeOut();

            setTimeout(function () {
                let id = response.substring(0, response.length - 1);

                if ('my_templates' === currentTab.replace(/\W+/g, '-').toLowerCase()) {
                    window.location.href = 'post.php?post=' + id + '&action=elementor';
                    return;
                }

                $('.king-addons-pb-save-conditions').attr('data-slug', slug).attr('data-id', id);

                renderUserTemplate(getActiveFilter(), template_title.val(), slug, id);

                let no_templates = $('.king-addons-pb-no-templates');

                if (no_templates.length) {
                    no_templates.hide();
                }

                openConditionsPopup(slug);
                conditionPopup.addClass('editor-redirect');
            }, 500);
        });
    }


    $('.king-addons-pb-user-template').on('click', function () {
        if ($(this).find('div').length) {
            alert('Please Install/Activate WooCommerce plugin');
            return;
        }
        template_title.val('');
        $('.king-addons-pb-user-template-popup-wrap').fadeIn();
    });

    $('.king-addons-pb-user-template-popup').find('.close-popup').on('click', function () {
        $('.king-addons-pb-user-template-popup-wrap').fadeOut();
    });

    $('.king-addons-pb-create-template').on('click', function () {
        if ('' === template_title.val()) {
            template_title.css('border-color', '#FF4040');
            let fill_out_the_title = $('.king-addons-pb-fill-out-the-title');

            if (fill_out_the_title.length < 1) {
                $('.king-addons-pb-create-template').before('<p class="king-addons-pb-fill-out-the-title">Please fill Popup Title field</p>');
                fill_out_the_title.css('margin-top', '10px');
                fill_out_the_title.css({'color': '#FF4040', 'font-size': '14px', 'font-weight': '500'});
            }
        } else {
            template_title.removeAttr('style');
            $('.king-addons-pb-create-template + p').remove();

            createUserTemplate();
        }
    });

    template_title.keypress(function (e) {
        if (e.which === 13) {
            e.preventDefault();
            createUserTemplate();
        }
    });

    function deleteTemplate() {
        $('.king-addons-pb-delete-template').on('click', function () {

            let deleteButton = $(this);

            if (!confirm(deleteButton.data('warning'))) {
                return;
            }

            let library = 'my_templates' === getActiveFilter() ? 'elementor_library' : 'king_addons_ext_pb';
            let slug = deleteButton.attr('data-slug');
            let oneTimeNonce = deleteButton.attr('data-nonce');

            let data = {
                nonce: oneTimeNonce,
                action: 'king_addons_pb_delete_template',
                template_slug: slug,
                template_library: library,
            };

            $.post(ajaxurl, data, function () {
                deleteButton.closest('li').remove();
            });

            $.post(ajaxurl, data, function () {
                setTimeout(function () {
                    if ($('.king-addons-pb-my-templates-list li').length === 0) {
                        $('.king-addons-pb-my-templates-list').append('<li class="king-addons-pb-no-templates">You don\'t have any popups yet</li>');
                    }
                }, 500);
            });

            if ('my_templates' !== getActiveFilter()) {
                let conditions = JSON.parse(conditionsID.val());
                delete conditions[slug];

                conditionsID.val(JSON.stringify(conditions));

                // noinspection JSUnresolvedReference
                let data = {
                    action: 'king_addons_pb_save_template_conditions',
                    nonce: KingAddonsPopupBuilderOptions.nonce,
                };
                data['king_addons_pb_' + currentTab + '_conditions'] = JSON.stringify(conditions);
            }
        });
    }

    deleteTemplate();

    function changeTemplateConditions() {
        $('.king-addons-pb-template-conditions').on('click', function () {
            let template = $(this).attr('data-slug');

            $('.king-addons-pb-save-conditions').attr('data-slug', template);

            openConditionsPopup(template);
        });
    }

    changeTemplateConditions();

    conditionPopup.find('.close-popup').on('click', function () {
        // noinspection JSValidateTypes
        conditionPopup.fadeOut();
    });

    function popupCloneConditions() {
        $('.king-addons-pb-conditions-wrap').append('<div class="king-addons-pb-conditions">' + $('.king-addons-pb-conditions-sample').html() + '</div>');

        let cloneCond = $('.king-addons-pb-conditions');

        cloneCond.removeClass('king-addons-pb-tab-' + currentTab).addClass('king-addons-pb-tab-' + currentTab);
        let clone = cloneCond.last();

        clone.find('select').not(':first-child').hide();
        clone.hide().fadeIn();

        let currentFilter = $('.template-filters .active-filter').attr('data-class');

        if (clone.hasClass('king-addons-pb-tab-product_single')) {
            setTimeout(function () {
                clone.find('.king-addons-pb-condition-input-ids').each(function () {
                    if (!($(this).val())) {
                        $(this).val('all').show();
                    }
                });
            }, 600);
        }

        if ('blog-posts' === currentFilter || 'custom-posts' === currentFilter) {
            clone.find('.singles-condition-select').children(':nth-child(1),:nth-child(2),:nth-child(3)').remove();
            clone.find('.king-addons-pb-condition-input-ids').val('all').show();
        } else if ('woocommerce-products' === currentFilter) {
            // noinspection JSUnresolvedReference
            clone.find('.singles-condition-select').children().filter(function () {
                return 'product' !== $(this).val()
            }).remove();
            clone.find('.king-addons-pb-condition-input-ids').val('all').show();
        } else if ('404-pages' === currentFilter) {
            // noinspection JSUnresolvedReference
            clone.find('.singles-condition-select').children().filter(function () {
                return 'page_404' !== $(this).val()
            }).remove();
        } else if ('blog-archives' === currentFilter || 'custom-archives' === currentFilter) {
            // noinspection JSUnresolvedReference
            clone.find('.archives-condition-select').children().filter(function () {
                return 'products' === $(this).val() || 'product_cat' === $(this).val() || 'product_tag' === $(this).val();
            }).remove();
        } else if ('woocommerce-archives' === currentFilter) {
            // noinspection JSUnresolvedReference
            clone.find('.archives-condition-select').children().filter(function () {
                return 'products' !== $(this).val() && 'product_cat' !== $(this).val() && 'product_tag' !== $(this).val();
            }).remove();
        }
    }

    function popupAddConditions() {
        $('.king-addons-pb-add-conditions').on('click', function () {
            popupCloneConditions();

            $('.king-addons-pb-conditions').last().find('input').hide();

            popupDeleteConditions();
            popupMainConditionSelect();
            popupSubConditionSelect();
        });
    }

    popupAddConditions();

    function popupSetConditions(template) {
        let conditions = conditionsID.val();
        conditions = '' !== conditions ? JSON.parse(conditions) : {};

        let setCond = $('.king-addons-pb-conditions');

        setCond.remove();

        if (conditions[template] !== undefined && conditions[template].length > 0) {
            for (let i = 0; i < conditions[template].length; i++) {
                popupCloneConditions();
                setCond.find('select').hide();
            }

            if (setCond.length) {
                setCond.each(function (index) {
                    let path = conditions[template][index].split('/');

                    for (let s = 0; s < path.length; s++) {
                        // noinspection DuplicatedCode
                        if (s === 0) {
                            $(this).find(globalS).val(path[s]).trigger('change');
                            $(this).find('.' + path[s] + 's-condition-select').show();
                        } else if (s === 1) {
                            path[s - 1] = 'product_archive' === path[s - 1] ? 'archive' : path[s - 1];
                            $(this).find('.' + path[s - 1] + 's-condition-select').val(path[s]).trigger('change');
                        } else if (s === 2) {
                            $(this).find(inputIDs).val(path[s]).trigger('keyup').show();
                        }
                    }
                });
            }
        }

        let conditionsBtn = $('.king-addons-pb-template-conditions[data-slug=' + template + ']');

        if ('true' === conditionsBtn.attr('data-show-on-canvas')) {
            $('.king-addons-pb-canvas-condition').find('input[type=checkbox]').attr('checked', 'checked');
        } else {
            $('.king-addons-pb-canvas-condition').find('input[type=checkbox]').removeAttr('checked');
        }
    }

    function openConditionsPopup(template) {
        popupSetConditions(template);
        popupMainConditionSelect();
        popupSubConditionSelect();
        showOnCanvasSwitcher();
        popupDeleteConditions();

        let conditionsWrap = $('.king-addons-pb-conditions');
        let conditionCanvas = $('.king-addons-pb-canvas-condition')

        conditionCanvas.hide();

        if ('single' === currentTab || 'product_single' === currentTab) {
            conditionsWrap.find(singleS).show();
        } else if ('archive' === currentTab || 'product_archive' === currentTab) {
            conditionsWrap.find(archiveS).show();
        } else {
            conditionsWrap.find(globalS).show();

            if (conditionsWrap.length) {
                conditionCanvas.show();
            }
        }

        $('.king-addons-pb-conditions-wrap').addClass($('.template-filters .active-filter').attr('data-class'));

        // noinspection JSValidateTypes
        conditionPopup.fadeIn();
    }

    function popupDeleteConditions() {
        $('.king-addons-pb-delete-template-conditions').on('click', function () {
            let current = $(this).parent(),
                conditions = conditionsID.val();
            conditions = '' !== conditions ? JSON.parse(conditions) : {};

            conditionsID.val(JSON.stringify(removeConditions(conditions, getConditionsPath(current))));

            // noinspection JSValidateTypes
            current.fadeOut(500, function () {
                $(this).remove();

                if (0 === $('.king-addons-pb-conditions').length) {
                    $('.king-addons-pb-canvas-condition').hide();
                }
            });

        });
    }

    function popupMainConditionSelect() {
        $(globalS).on('change', function () {
            let current = $(this).parent();
            current.find(archiveS).hide();
            current.find(singleS).hide();
            current.find(inputIDs).hide();
            current.find('.' + $(this).val() + 's-condition-select').show();
        });
    }

    function popupSubConditionSelect() {
        $('.archives-condition-select, .singles-condition-select').on('change', function () {
            let current = $(this).parent(),
                selected = $('option:selected', this);

            if (selected.hasClass('custom-ids') || selected.hasClass('custom-type-ids')) {
                current.find(inputIDs).val('all').trigger('keyup').show();
            } else {
                current.find(inputIDs).hide();
            }

        });
    }

    function showOnCanvasSwitcher() {
        $('.king-addons-pb-canvas-condition input[type=checkbox]').on('change', function () {
            $('.king-addons-pb-template-conditions[data-slug=' + $('.king-addons-pb-save-conditions').attr('data-slug') + ']').attr('data-show-on-canvas', $(this).prop('checked'));
        });
    }

    function removeConditions(conditions, path) {
        let data = [];

        $('.king-addons-pb-template-conditions').each(function () {
            data.push($(this).attr('data-slug'))
        });

        for (let key in conditions) {
            if (conditions.hasOwnProperty(key)) {
                for (let i = 0; i < conditions[key].length; i++) {
                    if (path === conditions[key][i]) {
                        if ('popup' !== getActiveFilter()) {
                            conditions[key].splice(i, 1);
                        }
                    }
                }

                if (data.indexOf(key) === -1) {
                    delete conditions[key];
                }
            }
        }

        return conditions;
    }

    function getConditionsPath(current) {
        let path;
        let global = 'none' !== current.find(globalS).css('display') ? current.find(globalS).val() : currentTab,
            archive = current.find(archiveS).val(),
            single = current.find(singleS).val(),
            customIds = current.find(inputIDs);

        // noinspection DuplicatedCode
        if ('archive' === global || 'product_archive' === global) {
            if ('none' !== customIds.css('display')) {
                path = global + '/' + archive + '/' + customIds.val();
            } else {
                path = global + '/' + archive;
            }
        } else if ('single' === global || 'product_single' === global) {
            if ('none' !== customIds.css('display')) {
                path = global + '/' + single + '/' + customIds.val();
            } else {
                path = global + '/' + single;
            }
        } else {
            path = 'global';
        }

        return path;
    }

    function getConditions(template, conditions) {
        conditions = ('' === conditions || '[]' === conditions) ? {} : JSON.parse(conditions);
        conditions[template] = [];
        $('.king-addons-pb-conditions').each(function () {
            let path = getConditionsPath($(this));
            conditions = removeConditions(conditions, path);
            conditions[template].push(path);
        });
        return conditions;
    }

    function saveConditions() {
        $('.king-addons-pb-save-conditions').on('click', function () {

            let template = $(this).attr('data-slug'),
                TemplateID = $(this).attr('data-id');

            let conditions = getConditions(template, conditionsID.val());

            conditionsID.val(JSON.stringify(conditions));

            // noinspection JSUnresolvedReference
            let data = {
                action: 'king_addons_pb_save_template_conditions',
                nonce: KingAddonsPopupBuilderOptions.nonce,
                template: template
            };
            data['king_addons_pb_' + currentTab + '_conditions'] = JSON.stringify(conditions);

            let showOnCanvas = $('#king-addons-pb-show-on-canvas');

            if (showOnCanvas.length) {
                data['king_addons_pb_' + currentTab + '_show_on_canvas'] = showOnCanvas.prop('checked');
            }

            $.post(ajaxurl, data, function () {
                // noinspection JSValidateTypes
                conditionPopup.fadeOut();

                for (let key in conditions) {
                    if (conditions[key] && 0 !== conditions[key].length) {
                        $('.king-addons-pb-delete-template[data-slug="' + key + '"]').closest('li').addClass('king-addons-pb-active-conditions-template');
                    } else {
                        $('.king-addons-pb-delete-template[data-slug="' + key + '"]').closest('li').removeClass('king-addons-pb-active-conditions-template');
                    }
                }

                if (conditionPopup.hasClass('editor-redirect')) {
                    window.location.href = 'post.php?post=' + TemplateID + '&action=elementor';
                }
            });
        });
    }

    saveConditions();

    if ($('body').hasClass('toplevel_page_king-addons-popup-builder')) {
        let conditions = JSON.parse(conditionsID.val() || '{}');
        for (let key in conditions) {
            $('.king-addons-pb-delete-template[data-slug="' + key + '"]').closest('li').addClass('king-addons-pb-active-conditions-template');
        }
    }

});