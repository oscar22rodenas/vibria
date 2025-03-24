<?php /** @noinspection SpellCheckingInspection, PhpUnused */

namespace King_Addons;

use Elementor\Controls_Manager;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class Advanced_Border_Radius
{
    private static ?Advanced_Border_Radius $_instance = null;

    public static function instance(): Advanced_Border_Radius
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
            'kng_adv_border_radius_section',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Advanced Border Radius', 'king-addons'),
                'tab' => Controls_Manager::TAB_ADVANCED
            ]
        );

        $section->add_control(
            'kng_adv_border_radius_enable',
            [
                'label' => esc_html__('Enable Advanced Border Radius', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'description' => esc_html__('Apply custom radius values. Get the radius value from ', 'king-addons') . '<a href="https://9elements.github.io/fancy-border-radius/" target="_blank">here</a>',
                'default' => '',
                'return_value' => 'yes',
                'label_on' => esc_html__('Yes', 'king-addons'),
                'label_off' => esc_html__('No', 'king-addons'),
                'prefix_class' => 'king-addons-adv-border-radius-',
            ]
        );

        $section->add_responsive_control(
            'kng_adv_border_radius_value',
            [
                'label' => esc_html__('Border Radius', 'king-addons'),
                'type' => Controls_Manager::TEXT,
                'default' => '30% 70% 70% 30% / 30% 30% 70% 70%',
                'selectors' => [
                    '{{WRAPPER}}.king-addons-adv-border-radius-yes' => 'border-radius: {{VALUE}} !important;'
                ],
                'condition' => [
                    'kng_adv_border_radius_enable' => 'yes'
                ],
                'ai' => [
                    'active' => false,
                ],
            ]
        );

        $section->add_responsive_control(
            'kng_adv_border_radius_overflow',
            [
                'label' => esc_html__('Overflow', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => array(
                    'visible' => esc_html__('Visible', 'king-addons'),
                    'hidden' => esc_html__('Hidden', 'king-addons'),
                    'scroll' => esc_html__('Scroll', 'king-addons'),
                ),
                'default' => 'hidden',
                'selectors' => array(
                    '{{WRAPPER}}.king-addons-adv-border-radius-yes' => 'overflow: {{VALUE}} !important;',
                ),
                'condition' => [
                    'kng_adv_border_radius_enable' => 'yes'
                ],
            ]
        );

        $section->end_controls_section();
    }
}