// noinspection JSUnresolvedReference,CommaExpressionJS,SpellCheckingInspection

jQuery(window).on('elementor:init', (function () {
    "use strict";
    let kingaddonsAjaxSelect2 = elementor.modules.controls.BaseData.extend({
        onReady: function () {
            let $this = this,
                $select = $this.ui.select,
                restUrl = $select.attr('data-rest-url'),
                nonce = window.wpApiSettings.nonce;

            let querySlug = '' !== $select.attr('data-query-slug') ? $select.attr('data-query-slug') : '';

            $select.select2({
                ajax: {
                    url: restUrl,
                    dataType: 'json',
                    headers: {
                        'X-WP-Nonce': nonce
                    },
                    data: function (response) {
                        return {
                            s: response.term,
                            query_slug: querySlug
                        }
                    }
                },
                cache: !0
            });

            let controlValue = void 0 !== $this.getControlValue() ? $this.getControlValue() : '';

            controlValue.isArray && (controlValue = $this.getControlValue().join()), jQuery.ajax({
                url: restUrl,
                dataType: 'json',
                headers: {
                    'X-WP-Nonce': nonce
                },
                data: {
                    ids: String(controlValue),
                    query_slug: querySlug
                }
            }).then((function (response) {
                if ('' !== controlValue) {
                    null !== response && response.results.length > 0 && (jQuery.each(response.results, (function (index, element) {
                        let option = new Option(element.text, element.id, !0, !0);
                        $select.append(option);
                        $select.append(option).trigger('change.select2');
                        $select.val(controlValue).trigger('change.select2');
                    })), $select.trigger({
                        type: 'select2:select',
                        params: {
                            data: response
                        }
                    }))
                }
            }))
        },
        onBeforeDestroy: function () {
            this.ui.select.data('select2') && this.ui.select.select2('destroy'), this.el.remove()
        }
    });

    elementor.addControlView('king-addons-ajax-select2', kingaddonsAjaxSelect2)
}));