<?php /** @noinspection PhpUnused, SpellCheckingInspection */

namespace King_Addons;

use Elementor\Controls_Manager;
use Elementor\Element_Base;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class Rotating_Animation
{
    private static ?Rotating_Animation $_instance = null;

    public static function instance(): Rotating_Animation
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /** @noinspection DuplicatedCode */
    public function __construct()
    {
        add_action('elementor/element/container/section_layout/after_section_end', [__CLASS__, 'addControls'], 1);
        add_action('elementor/element/column/section_advanced/after_section_end', [__CLASS__, 'addControls'], 1);
        add_action('elementor/element/section/section_advanced/after_section_end', [__CLASS__, 'addControls'], 1);
        add_action('elementor/element/common/_section_style/after_section_end', [__CLASS__, 'addControls'], 1);
        add_action('elementor/preview/enqueue_scripts', [__CLASS__, 'enqueueScripts'], 1);
        add_action('elementor/frontend/before_render', [__CLASS__, 'renderAnimation'], 1);
    }

    public static function enqueueScripts(): void
    {
        if (!wp_script_is(KING_ADDONS_ASSETS_UNIQUE_KEY . '- ' . 'rotating-animation' . '-' . 'preview-handler')) {
            wp_enqueue_script(KING_ADDONS_ASSETS_UNIQUE_KEY . '-' . 'rotating-animation' . '-' . 'preview-handler', '', array('jquery'), KING_ADDONS_VERSION);
        }
    }

    public static function addControls(Element_Base $element): void
    {
        $element->start_controls_section(
            'kng_rotating_animation_section',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Rotating Animation', 'king-addons'),
                'tab' => Controls_Manager::TAB_ADVANCED
            ]
        );

        $element->add_control(
            'kng_rotating_animation_switch',
            [
                'label' => esc_html__('Enable Rotating Animation', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'prefix_class' => 'kng-rotating-animation-',
                'frontend_available' => true
            ]
        );

        $element->add_control(
            'kng_rotating_animation_duration',
            [
                'label' => esc_html__('Duration of single spin', 'king-addons') . ' (ms)',
                'type' => Controls_Manager::NUMBER,
                'frontend_available' => true,
                'min' => 0,
                'step' => 1,
                'default' => 8000,
                'condition' => [
                    'kng_rotating_animation_switch!' => ''
                ]
            ]
        );

        $element->add_control(
            'kng_rotating_animation_delay',
            [
                'label' => esc_html__('Animation delay', 'king-addons') . ' (ms)',
                'type' => Controls_Manager::NUMBER,
                'frontend_available' => true,
                'min' => 0,
                'step' => 1,
                'default' => 0,
                'condition' => [
                    'kng_rotating_animation_switch!' => ''
                ]
            ]
        );

        $element->end_controls_section();
    }

    public static function renderAnimation(Element_Base $element): void
    {
        if (!empty($element->get_settings_for_display('kng_rotating_animation_switch'))) {

            $kng_rotating_animation_duration = $element->get_settings_for_display('kng_rotating_animation_duration');
            $kng_rotating_animation_delay = $element->get_settings_for_display('kng_rotating_animation_delay');

            if (!empty($kng_rotating_animation_duration)) {
                $element_ID = $element->get_id();
                $inline_code = '@keyframes rotating-animation-' . esc_html($element_ID) . ' {0% {transform: rotate(0deg);} 100% {transform: rotate(360deg)}}' . '.elementor-element-' . esc_html($element_ID) . ' {animation: rotating-animation-' . esc_html($element_ID) . ' ' . esc_html($kng_rotating_animation_duration) . 'ms linear infinite; animation-delay: ' . esc_html($kng_rotating_animation_delay) . 'ms;';
                wp_enqueue_style('king-addons-rotating-animation-' . $element_ID, KING_ADDONS_URL . 'includes/features/Rotating_Animation/style.css', '', KING_ADDONS_VERSION);
                wp_add_inline_style('king-addons-rotating-animation-' . $element_ID, $inline_code);
            }
        }
    }
}