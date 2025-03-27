;(function ($) {

    const ELHF_Admin = {

        _init: function () {
            const elhf_hide_field = function () {
                const selected = $('#king_addons_el_hf_template_type').val() || 'none';
                $('.king-addons-el-hf-options-table').removeClass().addClass('king-addons-el-hf-options-table widefat king-addons-el-hf-selected-template-type-' + selected);
            }

            $(document).on('change', '#king_addons_el_hf_template_type', function () {
                elhf_hide_field();
            });

            elhf_hide_field();
        }
    }

    $(document).ready(function () {
        ELHF_Admin._init();
    });

    window.ELHF_Admin = ELHF_Admin;

})(jQuery);