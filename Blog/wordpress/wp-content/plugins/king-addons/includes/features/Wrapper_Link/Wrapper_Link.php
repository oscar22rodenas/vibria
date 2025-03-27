<?php /** @noinspection PhpUnused */

namespace King_Addons;

use Elementor\Controls_Manager;
use Elementor\Element_Base;

/** @noinspection SpellCheckingInspection */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class Wrapper_Link
{
    private static ?Wrapper_Link $_instance = null;

    public static function instance(): Wrapper_Link
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
        add_action('elementor/frontend/before_render', [__CLASS__, 'renderLink'], 1);
    }

    public static function addControls(Element_Base $element): void
    {
        $element->start_controls_section(
            'kng_wrapper_link_section',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Wrapper Link', 'king-addons'),
                'tab' => Controls_Manager::TAB_ADVANCED
            ]
        );

        $element->add_control(
            'kng_wrapper_link_switch',
            [
                'label' => esc_html__('Enable Wrapper Link', 'king-addons'),
                'type' => Controls_Manager::SWITCHER
            ]
        );

        $element->add_control(
            'kng_wrapper_link',
            [
                'label' => esc_html__('Link', 'king-addons'),
                'type' => Controls_Manager::URL,
                'placeholder' => esc_html__('https://example.com', 'king-addons'),
                'options' => [
                    'url',
                    'is_external'
                ],
                'condition' => [
                    'kng_wrapper_link_switch!' => ''
                ]
            ]
        );

        $element->end_controls_section();
    }

    public static function renderLink(Element_Base $element): void
    {
        if (!empty($element->get_settings_for_display('kng_wrapper_link_switch'))) {

            $wrapper_link_settings = $element->get_settings_for_display('kng_wrapper_link');

            if (!empty($wrapper_link_settings['url'])) {

                $link_target = ($wrapper_link_settings['is_external']) ? '_blank' : '_self';

                $element->add_render_attribute(
                    '_wrapper',
                    [
                        'style' => 'cursor: pointer;',
                        'onclick' => "window.open('" . esc_url($wrapper_link_settings['url']) . "', '" . esc_attr($link_target) . "');"
                    ]
                );
            }
        }
    }
}