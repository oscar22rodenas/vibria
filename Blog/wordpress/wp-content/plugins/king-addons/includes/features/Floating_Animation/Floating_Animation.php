<?php /** @noinspection SpellCheckingInspection, PhpUnused */

namespace King_Addons;

use Elementor\Controls_Manager;
use Elementor\Element_Base;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class Floating_Animation
{
    private static ?Floating_Animation $_instance = null;

    public static function instance(): Floating_Animation
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
        if (!wp_script_is(KING_ADDONS_ASSETS_UNIQUE_KEY . '-' . 'floating-animation' . '-' . 'preview-handler')) {
            wp_enqueue_script(KING_ADDONS_ASSETS_UNIQUE_KEY . '-' . 'floating-animation' . '-' . 'preview-handler', '', array('jquery'), KING_ADDONS_VERSION);
        }
    }

    public static function addControls(Element_Base $element): void
    {
        $element->start_controls_section(
            'kng_floating_animation_section',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Floating Animation', 'king-addons'),
                'tab' => Controls_Manager::TAB_ADVANCED
            ]
        );

        $element->add_control(
            'kng_floating_animation_switch',
            [
                'label' => esc_html__('Enable Floating Animation', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'prefix_class' => 'kng-floating-animation-',
                'frontend_available' => true
            ]
        );

        $element->add_control(
            'kng_floating_animation_value_X',
            [
                'label' => esc_html__('Offset X', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'frontend_available' => true,
                'step' => 1,
                'default' => 0,
                'condition' => [
                    'kng_floating_animation_switch!' => ''
                ]
            ]
        );

        $element->add_control(
            'kng_floating_animation_value',
            [
                'label' => esc_html__('Offset Y', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'frontend_available' => true,
                'step' => 1,
                'default' => -20,
                'condition' => [
                    'kng_floating_animation_switch!' => ''
                ]
            ]
        );

        $element->add_control(
            'kng_floating_animation_duration',
            [
                'label' => esc_html__('Duration', 'king-addons') . ' (ms)',
                'type' => Controls_Manager::NUMBER,
                'frontend_available' => true,
                'min' => 0,
                'step' => 1,
                'default' => 6000,
                'condition' => [
                    'kng_floating_animation_switch!' => ''
                ]
            ]
        );

        $element->add_control(
            'kng_floating_animation_delay',
            [
                'label' => esc_html__('Animation delay', 'king-addons') . ' (ms)',
                'type' => Controls_Manager::NUMBER,
                'frontend_available' => true,
                'min' => 0,
                'step' => 1,
                'default' => 0,
                'condition' => [
                    'kng_floating_animation_switch!' => ''
                ]
            ]
        );

        $element->end_controls_section();
    }

    public static function renderAnimation(Element_Base $element): void
    {
        if (!empty($element->get_settings_for_display('kng_floating_animation_switch'))) {
            $kng_floating_animation_value_X = $element->get_settings_for_display('kng_floating_animation_value_X');
            $kng_floating_animation_value = $element->get_settings_for_display('kng_floating_animation_value');
            $kng_floating_animation_duration = $element->get_settings_for_display('kng_floating_animation_duration');
            $kng_floating_animation_delay = $element->get_settings_for_display('kng_floating_animation_delay');

            if (('' != $kng_floating_animation_value_X) && ('' != $kng_floating_animation_value) && ('' != $kng_floating_animation_duration)) {
                $element_ID = $element->get_id();
                $inline_code = '@keyframes floating-animation-' . esc_html($element_ID) . ' {0% {transform: translate(0, 0);} 50% {transform: translate(' . esc_html($kng_floating_animation_value_X) . 'px, ' . esc_html($kng_floating_animation_value) . 'px);} 100% {transform: translate(0, 0);}} ' . '.elementor-element-' . esc_html($element_ID) . ' {animation: floating-animation-' . esc_html($element_ID) . ' ' . esc_html($kng_floating_animation_duration) . 'ms ease-in-out infinite; animation-delay: ' . esc_html($kng_floating_animation_delay) . 'ms;}';
                wp_enqueue_style('king-addons-floating-animation-' . $element_ID, KING_ADDONS_URL . 'includes/features/Floating_Animation/style.css', '', KING_ADDONS_VERSION);
                wp_add_inline_style('king-addons-floating-animation-' . $element_ID, $inline_code);
            }
        }
    }
}