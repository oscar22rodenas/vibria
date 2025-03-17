<?php /** @noinspection SpellCheckingInspection, PhpUnused */

namespace King_Addons;

use Elementor\Controls_Manager;
use Elementor\Element_Base;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class Custom_CSS
{
    private static ?Custom_CSS $_instance = null;

    public static function instance(): Custom_CSS
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function __construct()
    {
        add_action('elementor/preview/enqueue_scripts', [__CLASS__, 'enqueueScripts'], 1);
        add_action('elementor/element/container/section_layout/after_section_end', [__CLASS__, 'addControls'], 1);
        add_action('elementor/element/column/section_advanced/after_section_end', [__CLASS__, 'addControls'], 1);
        add_action('elementor/element/section/section_advanced/after_section_end', [__CLASS__, 'addControls'], 1);
        add_action('elementor/element/common/_section_style/after_section_end', [__CLASS__, 'addControls'], 1);
        add_action('elementor/element/parse_css', [__CLASS__, 'renderCSS'], 10, 2);
    }

    public static function enqueueScripts(): void
    {
        if (!wp_script_is(KING_ADDONS_ASSETS_UNIQUE_KEY . '-' . 'custom-css' . '-' . 'preview-handler')) {
            wp_enqueue_script(KING_ADDONS_ASSETS_UNIQUE_KEY . '-' . 'custom-css' . '-' . 'preview-handler', '', array('jquery'), KING_ADDONS_VERSION);
        }
    }

    public static function addControls(Element_Base $element): void
    {
        $element->start_controls_section(
            'kng_custom_css_section',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Custom CSS', 'king-addons'),
                'tab' => Controls_Manager::TAB_ADVANCED
            ]
        );

        $element->add_control(
            'kng_custom_css_switch',
            [
                'label' => esc_html__('Enable Custom CSS', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'prefix_class' => 'kng-custom-css-',
                'frontend_available' => true
            ]
        );

        $element->add_control(
            'kng_custom_css',
            [
                'type' => Controls_Manager::CODE,
                'label' => esc_html__('Custom CSS', 'king-addons'),
                'description' => esc_html__('Use "[current-element]" keyword as selector to target the current element. You can also use all CSS pseudo-classes, for example "[current-element]:hover" adds action when mouse hover over the current element.', 'king-addons'),
                'default' => '[current-element] {  }',
                'language' => 'css',
                'render_type' => 'ui',
                'label_block' => true,
                'frontend_available' => true,
                'condition' => [
                    'kng_custom_css_switch!' => ''
                ]
            ]
        );

        $element->end_controls_section();
    }

    public static function renderCSS($raw_css, Element_Base $element): void
    {
        if (!empty($element->get_settings_for_display('kng_custom_css_switch'))) {
            $element_settings = $element->get_settings();

            if (empty($element_settings['kng_custom_css'])) {
                return;
            }

            $css = trim($element_settings['kng_custom_css']);

            if (empty($css)) {
                return;
            }

            $css = str_replace('[current-element]', $raw_css->get_element_unique_selector($element), $css);

            $raw_css->get_stylesheet()->add_raw_css($css);
        }
    }
}