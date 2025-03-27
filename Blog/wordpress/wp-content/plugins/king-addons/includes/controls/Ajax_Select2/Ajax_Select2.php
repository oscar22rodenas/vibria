<?php /** @noinspection PhpUnused, SpellCheckingInspection, DuplicatedCode */

namespace King_Addons\AJAX_Select2;

use Elementor\Base_Data_Control;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class Ajax_Select2 extends Base_Data_Control
{
    public function get_type(): string
    {
        return 'king-addons-ajax-select2';
    }

    public function enqueue(): void
    {
        wp_enqueue_script(KING_ADDONS_ASSETS_UNIQUE_KEY . '-ajax-select2', KING_ADDONS_URL . 'includes/controls/Ajax_Select2/ajax-select2.js', array('jquery'), KING_ADDONS_VERSION);
    }

    protected function get_default_settings(): array
    {
        return [
            'options' => [],
            'multiple' => false,
            'select2options' => [],
            'query_slug' => '',
        ];
    }

    public function content_template(): void
    {
        $control_uid = $this->get_control_uid();
        ?>
        <div class="elementor-control-field">
            <label for="<?php echo esc_attr($control_uid); ?>" class="elementor-control-title">{{{ data.label }}}</label>
            <div class="elementor-control-input-wrapper">
                <# var multiple = ( data.multiple ) ? 'multiple' : ''; #>
                <!--suppress HtmlUnknownAttribute -->
                <select id="<?php echo esc_attr($control_uid); ?>"
                        class="elementor-control-type-king-addons-ajaxselect2" {{
                        multiple }} data-query-slug="{{data.query_slug}}" data-setting="{{ data.name }}"
                        data-rest-url="<?php echo esc_attr(get_rest_url() . 'kingaddons/v1' . '/{{data.options}}/'); ?>">
                </select>
            </div>
        </div>
        <# if ( data.description ) { #>
        <div class="elementor-control-field-description">{{{ data.description }}}</div>
        <# } #>
        <?php
    }
}