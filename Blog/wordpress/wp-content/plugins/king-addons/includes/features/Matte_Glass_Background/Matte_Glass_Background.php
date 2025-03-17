<?php /** @noinspection SpellCheckingInspection, PhpUnused */

namespace King_Addons;

use Elementor\Controls_Manager;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class Matte_Glass_Background
{
    private static ?Matte_Glass_Background $_instance = null;

    public static function instance(): Matte_Glass_Background
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function __construct()
    {
        add_action('elementor/element/container/section_layout/after_section_end', [__CLASS__, 'addControls'], 1);
        add_action('elementor/element/column/section_advanced/after_section_end', [__CLASS__, 'addControls'], 1);
        add_action('elementor/element/section/section_advanced/after_section_end', [__CLASS__, 'addControls'], 1);
        add_action('elementor/element/common/_section_style/after_section_end', [__CLASS__, 'addControls'], 1);
    }

    public static function addControls($section): void
    {
        $section->start_controls_section(
            'kng_glass_bg_section',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Matte Glass Background', 'king-addons'),
                'tab' => Controls_Manager::TAB_ADVANCED
            ]
        );

        $section->add_control(
            'kng_glass_bg_enable',
            [
                'label' => esc_html__('Enable Matte Glass Background', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => '',
                'return_value' => 'yes',
                'label_on' => esc_html__('Yes', 'king-addons'),
                'label_off' => esc_html__('No', 'king-addons'),
                'prefix_class' => 'kng-glass-bg-',
            ]
        );

        $section->add_control(
            'kng_glass_bg_blur_value',
            [
                'label' => esc_html__('Blur Value', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 100,
                'step' => 1,
                'default' => 20,
                'selectors' => [
                    '{{WRAPPER}}.kng-glass-bg-yes' => 'backdrop-filter: blur({{SIZE}}px); -webkit-backdrop-filter: blur({{SIZE}}px);'
                ],
                'condition' => [
                    'kng_glass_bg_enable' => 'yes'
                ],
            ]
        );

        $section->add_control(
            'kng_glass_bg_color',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => 'rgba(0, 0, 0, 0)',
                'selectors' => [
                    '{{WRAPPER}}.kng-glass-bg-yes' => 'background-color: {{VALUE}};',
                ],
                'condition' => [
                    'kng_glass_bg_enable' => 'yes'
                ],
            ]
        );

        $section->end_controls_section();
    }
}