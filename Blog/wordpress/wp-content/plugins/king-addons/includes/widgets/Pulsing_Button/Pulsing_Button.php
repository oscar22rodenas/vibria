<?php /** @noinspection SpellCheckingInspection, DuplicatedCode, PhpUnused */

namespace King_Addons;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;
use Elementor\Widget_Base;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class Pulsing_Button extends Widget_Base
{
    public function get_name(): string
    {
        return 'king-addons-pulsing-button';
    }

    public function get_title(): string
    {
        return esc_html__('Pulsing Button', 'king-addons');
    }

    public function get_icon(): string
    {
        /** @noinspection SpellCheckingInspection */
        return 'king-addons-icon king-addons-pulsing-button';
    }

    public function get_style_depends(): array
    {
        return [KING_ADDONS_ASSETS_UNIQUE_KEY . '-pulsing-button-style'];
    }

    public function get_categories(): array
    {
        return ['king-addons'];
    }

    public function get_keywords(): array
    {
        return ['pulsing button', 'animated button', 'cta button', 'button', 'sidebar', 'side', 'bar', 'ripple button',
            'cta', 'call to action', 'call', 'sale', 'animation', 'effect', 'animated', 'link', 'left', 'ripple',
            'right', 'top', 'bottom', 'vertical', 'horizontal', 'mouse', 'hover', 'over', 'hover over', 'border', 'splash',
            'float', 'floating', 'click', 'target', 'point', 'king', 'addons', 'mouseover', 'page', 'center', 'wave',
            'kingaddons', 'king-addons', 'appear', 'show', 'hide', 'up', 'video', 'play', 'player', 'url', 'waving',
            'youtube', 'link', 'source', 'external', 'internal', 'king addons', 'pulse button', 'pulsing', 'pulse',
            'btn'];
    }

    public function get_custom_help_url(): string
    {
        return 'https://kingaddons.com/elementor/pulsing-button/';
    }

    protected function register_controls(): void
    {
        /** SECTION: Button ===================== */
        $this->start_controls_section(
            'kng_pulsing_button_section_style_btn',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Button', 'king-addons'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'kng_pulsing_button_link',
            [
                'label' => esc_html__('Link', 'king-addons'),
                'type' => Controls_Manager::URL,
            ]
        );

        $this->add_control(
            'kng_pulsing_button_text',
            [
                'label' => esc_html__('Text', 'king-addons'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Text',
            ]
        );

        /** @noinspection SpellCheckingInspection */
        $this->add_control(
            'kng_pulsing_button_icon_reverse_switcher',
            [
                'label' => esc_html__('Reverse Icon Position', 'king-addons'),
                'description' => esc_html__('Left / Right', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
            ]
        );

        $this->add_control(
            'kng_pulsing_button_icon',
            [
                'label' => esc_html__('Select Icon', 'king-addons'),
                'type' => Controls_Manager::ICONS,
                'skin' => 'inline',
                'label_block' => false,
                'exclude_inline_options' => ['svg'],
                'default' => [
                    'value' => 'fas fa-angle-right',
                    'library' => 'fa-solid',
                ],
            ]
        );

        $this->add_responsive_control(
            'kng_img_hotspots_space_b_icon_title',
            [
                'label' => esc_html__('Space between icon and title (px)', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'step' => 1,
                'min' => 0,
                'default' => 10,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-pulsing-button-icon-reverse i' => 'margin-right: {{VALUE}}px;',
                    '{{WRAPPER}} .king-addons-pulsing-button-icon-reverse img' => 'margin-right: {{VALUE}}px;',
                    '{{WRAPPER}} .king-addons-pulsing-button-icon-reverse svg' => 'margin-right: {{VALUE}}px;',
                    '{{WRAPPER}} .king-addons-pulsing-button-icon-regular i' => 'margin-left: {{VALUE}}px;',
                    '{{WRAPPER}} .king-addons-pulsing-button-icon-regular img' => 'margin-left: {{VALUE}}px;',
                    '{{WRAPPER}} .king-addons-pulsing-button-icon-regular svg' => 'margin-left: {{VALUE}}px;',
                ],
            ]
        );

        $this->add_responsive_control(
            'kng_pulsing_button_h_position',
            [
                'label' => esc_html__('Button Horizontal Position', 'king-addons'),
                'type' => Controls_Manager::CHOOSE,
                'default' => 'center',
                'options' => [
                    'left' => [
                        'title' => esc_html__('Left', 'king-addons'),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'king-addons'),
                        'icon' => 'eicon-h-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__('Right', 'king-addons'),
                        'icon' => 'eicon-h-align-right',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}}' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'kng_pulsing_button_icon_size',
            [
                'label' => esc_html__('Icon Size', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'step' => 1,
                'min' => 0,
                'default' => 20,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-pulsing-button-button i' => 'font-size: {{VALUE}}px; width: {{VALUE}}px;',
                    '{{WRAPPER}} .king-addons-pulsing-button-button img' => 'width: {{VALUE}}px; height: {{VALUE}}px;',
                    '{{WRAPPER}} .king-addons-pulsing-button-button svg' => 'width: {{VALUE}}px; height: {{VALUE}}px;'
                ],
            ]
        );

        $this->add_responsive_control(
            'kng_pulsing_button_transition_icon',
            [
                'label' => esc_html__('Transition duration for icon on hover (ms)', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'step' => 10,
                'default' => 300,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-pulsing-button-button i' => 'transition: all {{SIZE}}ms;',
                    '{{WRAPPER}} .king-addons-pulsing-button-button img' => 'transition: all {{SIZE}}ms;',
                    '{{WRAPPER}} .king-addons-pulsing-button-button svg' => 'transition: all {{SIZE}}ms;',
                    '{{WRAPPER}} .king-addons-pulsing-button-button-wrap .king-addons-pulsing-button-button' => 'transition: all {{SIZE}}ms;',
                ],
            ]
        );

        $this->add_control(
            'kng_pulsing_button_scale_switcher',
            [
                'label' => esc_html__('Scale button on hover', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_responsive_control(
            'kng_pulsing_button_scale_hover',
            [
                'label' => esc_html__('Scale on hover', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'step' => 0.01,
                'default' => 1.1,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-pulsing-button-button-wrap:hover .king-addons-pulsing-button-button' => 'transform: scale({{SIZE}});'
                ],
                'condition' => [
                    'kng_pulsing_button_scale_switcher' => 'yes',
                ]
            ]
        );

        $this->add_control(
            'kng_pulsing_button_effect_type',
            [
                'label' => esc_html__('Button Effect', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'ripple',
                'options' => [
                    'none' => esc_html__('None', 'king-addons'),
                    'ripple' => esc_html__('Ripple', 'king-addons'),
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'kng_pulsing_button_effect_ripple_color',
            [
                'label' => esc_html__('Ripple Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#000000',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-pulsing-button-button.king-addons-pulsing-button-button-effect-ripple:before' => 'border: 1px solid {{VALUE}};',
                ],
                'condition' => [
                    'kng_pulsing_button_effect_type' => 'ripple',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'kng_pulsing_button_txt_typography',
                'label' => esc_html__('Typography', 'king-addons'),
                'selector' => '{{WRAPPER}} .king-addons-pulsing-button-button .king-addons-pulsing-button-text',
            ]
        );

        $this->add_responsive_control(
            'kng_pulsing_button_padding',
            [
                'label' => esc_html__('Padding', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'default' => [
                    'top' => 20,
                    'right' => 30,
                    'bottom' => 20,
                    'left' => 30,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-pulsing-button-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'kng_pulsing_button_margin',
            [
                'label' => esc_html__('Margin', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'default' => [
                    'top' => 30,
                    'right' => 30,
                    'bottom' => 30,
                    'left' => 30,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-pulsing-button-button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->add_control(
            'kng_pulsing_button_tabs_title',
            [
                'label' => '<b>' . esc_html__('Button Styles', 'king-addons') . '</b>',
                'type' => Controls_Manager::RAW_HTML,
                'separator' => 'before'
            ]
        );

        $this->start_controls_tabs('kng_pulsing_button_tabs');

        $this->start_controls_tab(
            'kng_pulsing_button_tab_normal',
            [
                'label' => esc_html__('Normal', 'king-addons'),
            ]
        );

        $this->add_control(
            'kng_pulsing_button_txt_color',
            [
                'label' => esc_html__('Text Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#000000',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-pulsing-button-button .king-addons-pulsing-button-text' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'kng_pulsing_button_icon_color',
            [
                'label' => esc_html__('Icon Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#000000',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-pulsing-button-button i' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .king-addons-pulsing-button-button svg' => 'fill: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'kng_pulsing_button_bg_color',
            [
                'label' => esc_html__('Background Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ededed',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-pulsing-button-button' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'kng_pulsing_button_box_shadow',
                'selector' => '{{WRAPPER}} .king-addons-pulsing-button-button',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'kng_pulsing_button_border',
                'selector' => '{{WRAPPER}} .king-addons-pulsing-button-button',
                'fields_options' => [
                    'border' => [
                        'default' => 'none',
                    ],
                ],
            ]
        );

        $this->add_responsive_control(
            'kng_pulsing_button_border_radius',
            [
                'label' => esc_html__('Border Radius', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'default' => [
                    'top' => 40,
                    'right' => 40,
                    'bottom' => 40,
                    'left' => 40,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-pulsing-button-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'kng_pulsing_button_tab_hover',
            [
                'label' => esc_html__('Hover', 'king-addons'),
            ]
        );

        $this->add_control(
            'kng_pulsing_button_txt_color_hover',
            [
                'label' => esc_html__('Text Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-pulsing-button-button-wrap:hover .king-addons-pulsing-button-button .king-addons-pulsing-button-text' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'kng_pulsing_button_icon_color_hover',
            [
                'label' => esc_html__('Icon Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-pulsing-button-button-wrap:hover .king-addons-pulsing-button-button i' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .king-addons-pulsing-button-button-wrap:hover .king-addons-pulsing-button-button svg' => 'fill: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'kng_pulsing_button_bg_color_hover',
            [
                'label' => esc_html__('Background Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-pulsing-button-button:hover' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'kng_pulsing_button_box_shadow_hover',
                'selector' => '{{WRAPPER}} .king-addons-pulsing-button-button:hover',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'kng_pulsing_button_border_hover',
                'selector' => '{{WRAPPER}} .king-addons-pulsing-button-button:hover',
            ]
        );

        $this->add_responsive_control(
            'kng_pulsing_button_border_radius_hover',
            [
                'label' => esc_html__('Border Radius', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-pulsing-button-button:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();
        /** END SECTION: Button ===================== */
    }

    protected function render(): void
    {
        $settings = $this->get_settings_for_display();
        $this_ID = $this->get_id();

        echo '<div class="king-addons-pulsing-button-button-wrap">';

        echo '<a href="' . esc_url($settings['kng_pulsing_button_link']['url']) . '"' .
            (($settings['kng_pulsing_button_link']['is_external']) ? ' target="_blank"' : '') .
            (($settings['kng_pulsing_button_link']['nofollow']) ? ' rel="nofollow"' : '') .
            '>';

        echo '<button class="king-addons-pulsing-button-button king-addons-pulsing-button-button-' .
            esc_attr($this_ID) . ' king-addons-pulsing-button-button-effect-' . esc_attr($settings['kng_pulsing_button_effect_type']) . (('yes' === $settings['kng_pulsing_button_icon_reverse_switcher']) ? ' king-addons-pulsing-button-icon-reverse' : ' king-addons-pulsing-button-icon-regular') . '">';

        if ('' !== $settings['kng_pulsing_button_text']) {
            echo '<span class="king-addons-pulsing-button-text">' . esc_html($settings['kng_pulsing_button_text']) . '</span>';
        }

        Icons_Manager::render_icon($settings['kng_pulsing_button_icon']);

        echo '</button>';

        echo '</a>';

        echo '</div>';

    }
}