<?php /** @noinspection SpellCheckingInspection, DuplicatedCode, PhpUnused */

namespace King_Addons;

use Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Widget_Base;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class Creative_Button extends Widget_Base
{
    public function get_name(): string
    {
        return 'king-addons-creative-button';
    }

    public function get_title(): string
    {
        return esc_html__('Creative Button', 'king-addons');
    }

    public function get_icon(): string
    {
        return 'king-addons-icon king-addons-creative-button';
    }

    public function get_style_depends(): array
    {
        return [KING_ADDONS_ASSETS_UNIQUE_KEY . '-creative-button-style'];
    }

    public function get_categories(): array
    {
        return ['king-addons'];
    }

    public function get_keywords(): array
    {
        return ['creative button', 'animated button', 'cta button', 'button', 'sidebar', 'side', 'bar', 'ripple button',
            'cta', 'call to action', 'call', 'sale', 'animation', 'effect', 'animated', 'link', 'left', 'ripple',
            'right', 'top', 'bottom', 'vertical', 'horizontal', 'mouse', 'hover', 'over', 'hover over', 'border', 'splash',
            'float', 'floating', 'click', 'target', 'point', 'king', 'addons', 'mouseover', 'page', 'center', 'wave',
            'kingaddons', 'king-addons', 'appear', 'show', 'hide', 'up', 'video', 'play', 'player', 'url', 'waving',
            'youtube', 'link', 'source', 'external', 'internal', 'king addons', 'pulse button', 'creative', 'pulse',
            'creativity', 'move', 'moving', 'fill', 'btn'];
    }

    public function get_custom_help_url(): string
    {
        return 'https://kingaddons.com/elementor/creative-button/';
    }

    public function has_widget_inner_wrapper(): bool {
        return true;
    }

    protected function register_controls(): void
    {

        $this->start_controls_section(
            'king_addons_creative_btn_section_button',
            [
                'label' => esc_html__('Creative Button', 'king-addons'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'king_addons_creative_btn_style',
            [
                'label' => esc_html__('Style', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'london',
                'options' => [
                    'london' => esc_html__('London', 'king-addons'),
                    'paris' => esc_html__('Paris', 'king-addons'),
                    'rome' => esc_html__('Rome', 'king-addons'),
                    'icon-slide' => esc_html__('Icon-Slide', 'king-addons'),
                    'icon-show' => esc_html__('Icon-Show', 'king-addons'),
                ],
            ]
        );

        $this->add_control(
            'king_addons_creative_btn_london_effect',
            [
                'label' => esc_html__('Effects', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'elevate',
                'options' => [
                    'elevate' => esc_html__('Elevate', 'king-addons'),
                    'glide' => esc_html__('Glide', 'king-addons'),
                    'fresh-start' => esc_html__('Fresh Start', 'king-addons'),
                    'illuminate' => esc_html__('Illuminate', 'king-addons'),
                    'morph' => esc_html__('Morph', 'king-addons'),
                    'cascade' => esc_html__('Cascade', 'king-addons'),
                    'flourish' => esc_html__('Flourish', 'king-addons'),
                    'radiate' => esc_html__('Radiate', 'king-addons'),
                ],
                'condition' => [
                    'king_addons_creative_btn_style' => 'london',
                ],
            ]
        );

        $this->add_control(
            'king_addons_creative_btn_paris_effect',
            [
                'label' => esc_html__('Effects', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'serene',
                'options' => [
                    'serene' => esc_html__('Serene', 'king-addons'),
                    'zephyr' => esc_html__('Zephyr', 'king-addons'),
                    'prism' => esc_html__('Prism', 'king-addons'),
                    'ember' => esc_html__('Ember', 'king-addons'),
                    'apex' => esc_html__('Apex', 'king-addons'),
                    'vortex' => esc_html__('Vortex', 'king-addons'),
                    'brilliance' => esc_html__('Brilliance', 'king-addons'),
                ],
                'condition' => [
                    'king_addons_creative_btn_style' => 'paris',
                ],
            ]
        );

        $this->add_control(
            'king_addons_creative_btn_rome_effect',
            [
                'label' => esc_html__('Effects', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'fill',
                'options' => [
                    'fill' => esc_html__('Fill', 'king-addons'),
                    'slide-down' => esc_html__('Slide In Down', 'king-addons'),
                    'slide-right' => esc_html__('Slide In Right', 'king-addons'),
                    'slide-x' => esc_html__('Slide Out X', 'king-addons'),
                    'slide-y' => esc_html__('Slide Out Y', 'king-addons'),
                    'diagonal' => esc_html__('Diagonal', 'king-addons'),
                ],
                'condition' => [
                    'king_addons_creative_btn_style' => 'rome',
                ],
            ]
        );

        $this->add_control(
            'king_addons_creative_btn_icon_show_effect',
            [
                'label' => esc_html__('Effects', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'back-in-left',
                'options' => [
                    'back-in-left' => esc_html__('Back In Left', 'king-addons'),
                    'back-in-right' => esc_html__('Back In Right', 'king-addons'),
                    'back-out-left' => esc_html__('Back Out Left', 'king-addons'),
                    'back-out-right' => esc_html__('Back Out Right', 'king-addons'),
                ],
                'condition' => [
                    'king_addons_creative_btn_style' => 'icon-show',
                ],
            ]
        );

        $this->add_control(
            'king_addons_creative_btn_icon_slide_effect',
            [
                'label' => esc_html__('Effects', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'slide-in-top',
                'options' => [
                    'slide-in-top' => esc_html__('Slide In Top', 'king-addons'),
                    'slide-in-down' => esc_html__('Slide In Down', 'king-addons'),
                    'slide-in-left' => esc_html__('Slide In Left', 'king-addons'),
                    'slide-in-right' => esc_html__('Slide In Right', 'king-addons'),
                ],
                'condition' => [
                    'king_addons_creative_btn_style' => 'icon-slide',
                ],
            ]
        );

        $this->add_control(
            'king_addons_creative_btn_button_text',
            [
                'label' => esc_html__('Text', 'king-addons'),
                'label_block' => true,
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Button Text', 'king-addons'),
                'dynamic' => [
                    'active' => true,
                ],
            ]
        );

        $this->add_control(
            'king_addons_creative_btn_button_link',
            array(
                'label' => esc_html__('Link', 'king-addons'),
                'type' => Controls_Manager::URL,
                'show_external' => true,
                'default' => array(
                    'url' => '#',
                    'is_external' => false,
                    'nofollow' => false,
                ),
                'dynamic' => [
                    'active' => true,
                ],
            )
        );

        $this->add_control(
            'king_addons_creative_btn_icon',
            [
                'label' => esc_html__('Icon for the button', 'king-addons'),
                'label_block' => false,
                'type' => Controls_Manager::ICONS,
                'skin' => 'inline',
                'render_type' => 'template',
                'exclude_inline_options' => ['svg'],
                'default' => [
                    'value' => 'fas fa-crown',
                    'library' => 'fa-solid',
                ],
                'conditions' => [
                    'relation' => 'or',
                    'terms' => [
                        [
                            'relation' => 'or',
                            'terms' => [
                                [
                                    'name' => 'king_addons_creative_btn_style',
                                    'operator' => '==',
                                    'value' => 'icon-show',
                                ],
                                [
                                    'name' => 'king_addons_creative_btn_style',
                                    'operator' => '==',
                                    'value' => 'icon-slide',
                                ],
                            ],
                        ],
                        [
                            'relation' => 'and',
                            'terms' => [
                                [
                                    'name' => 'king_addons_creative_btn_style',
                                    'operator' => '==',
                                    'value' => 'london',
                                ],
                                [
                                    'name' => 'king_addons_creative_btn_london_effect',
                                    'operator' => '==',
                                    'value' => 'morph',
                                ],
                            ],
                        ],
                    ],
                ],
            ]
        );

        $this->add_responsive_control(
            'king_addons_creative_btn_align_x',
            [
                'label' => esc_html__('Alignment', 'king-addons'),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
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
                'toggle' => true,
                'selectors' => [
                    '{{WRAPPER}} .elementor-widget-container' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'king_addons_creative_btn_rome_icon_show_icon_slide_style_section',
            [
                'label' => esc_html__('Common', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'king_addons_creative_btn_button_item_width',
            [
                'label' => esc_html__('Size', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-creative-btn.king-addons-creative-btn-effect-cascade' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .king-addons-creative-btn.king-addons-creative-btn-effect-radiate' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .king-addons-creative-btn.king-addons-creative-btn-effect-radiate .king-addons-creative-btn-progress' => 'width: calc({{SIZE}}{{UNIT}} - (({{SIZE}}{{UNIT}} / 100) * 20) ); height:auto;',
                ],
                'conditions' => [
                    'terms' => [
                        [
                            'relation' => 'or',
                            'terms' => [
                                [
                                    'name' => 'king_addons_creative_btn_london_effect',
                                    'operator' => '==',
                                    'value' => 'radiate',
                                ],
                                [
                                    'name' => 'king_addons_creative_btn_london_effect',
                                    'operator' => '==',
                                    'value' => 'cascade',
                                ],
                            ],
                        ],
                        [
                            'terms' => [
                                [
                                    'name' => 'king_addons_creative_btn_style',
                                    'operator' => '==',
                                    'value' => 'london',
                                ],
                            ],
                        ],
                    ],
                ],
            ]
        );

        $this->add_responsive_control(
            'king_addons_creative_btn_button_icon_size',
            [
                'label' => esc_html__('Icon Size', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 500,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 24,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-creative-btn i' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
                'conditions' => [
                    'relation' => 'or',
                    'terms' => [
                        [
                            'relation' => 'or',
                            'terms' => [
                                [
                                    'name' => 'king_addons_creative_btn_style',
                                    'operator' => '==',
                                    'value' => 'icon-show',
                                ],
                                [
                                    'name' => 'king_addons_creative_btn_style',
                                    'operator' => '==',
                                    'value' => 'icon-slide',
                                ],
                            ],
                        ],
                        [
                            'relation' => 'and',
                            'terms' => [
                                [
                                    'name' => 'king_addons_creative_btn_style',
                                    'operator' => '==',
                                    'value' => 'london',
                                ],
                                [
                                    'name' => 'king_addons_creative_btn_london_effect',
                                    'operator' => '==',
                                    'value' => 'morph',
                                ],
                            ],
                        ],
                    ],
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'king_addons_creative_btn_typography',
                'label' => esc_html__('Typography', 'king-addons'),
                'selector' => '{{WRAPPER}} .king-addons-creative-btn',
                'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_ACCENT,
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'king_addons_creative_btn_border',
                'exclude' => ['color'],
                'selector' => '{{WRAPPER}} .king-addons-creative-btn, {{WRAPPER}} .king-addons-creative-btn.king-addons-creative-btn-effect-flourish div',
                'conditions' => [
                    'terms' => [
                        [
                            'relation' => 'or',
                            'terms' => [
                                [
                                    'name' => 'king_addons_creative_btn_london_effect',
                                    'operator' => '!=',
                                    'value' => 'radiate',
                                ],
                            ],
                        ],
                        [
                            'terms' => [
                                [
                                    'name' => 'king_addons_creative_btn_style',
                                    'operator' => '!=',
                                    'value' => '',
                                ],
                            ],
                        ],
                    ],
                ],
            ]
        );

        $this->add_responsive_control(
            'king_addons_creative_btn_button_border_radius',
            [
                'label' => esc_html__('Border Radius', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-creative-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .king-addons-creative-btn.king-addons-creative-btn-style-london.king-addons-creative-btn-effect-flourish div' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'king_addons_creative_btn_button_london_radiate_stroke_width',
            [
                'label' => esc_html__('Stroke Width', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 10,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-creative-btn.king-addons-creative-btn-effect-radiate' => '--king-addons-creative-btn-stroke-width: {{SIZE}}{{UNIT}};',
                ],
                'conditions' => [
                    'terms' => [
                        [
                            'relation' => 'or',
                            'terms' => [
                                [
                                    'name' => 'king_addons_creative_btn_london_effect',
                                    'operator' => '==',
                                    'value' => 'radiate',
                                ],
                            ],
                        ],
                        [
                            'terms' => [
                                [
                                    'name' => 'king_addons_creative_btn_style',
                                    'operator' => '==',
                                    'value' => 'london',
                                ],
                            ],
                        ],
                    ],
                ],
            ]
        );

        $conditions = [
            'terms' => [
                [
                    'relation' => 'or',
                    'terms' => [
                        [
                            'name' => 'king_addons_creative_btn_london_effect',
                            'operator' => '!=',
                            'value' => 'radiate',
                        ],
                    ],
                ],
                [
                    'terms' => [
                        [
                            'name' => 'king_addons_creative_btn_style',
                            'operator' => '!=',
                            'value' => '',
                        ],
                    ],
                ],
            ],
        ];

        $this->start_controls_tabs('king_addons_creative_btn_tabs_button');
        $this->start_controls_tab(
            'king_addons_creative_btn__tab_button_normal',
            [
                'label' => esc_html__('Normal', 'king-addons'),
            ]
        );

        $this->add_control(
            'king_addons_creative_btn_button_text_color',
            [
                'label' => esc_html__('Text Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-creative-btn-wrap .king-addons-creative-btn' => '--king-addons-creative-btn-txt-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'king_addons_creative_btn_button_bg_color',
            [
                'label' => esc_html__('Background Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-creative-btn-wrap .king-addons-creative-btn' => '--king-addons-creative-btn-bg-color: {{VALUE}}',
                ],
                'conditions' => $conditions,
            ]
        );

        $this->add_control(
            'king_addons_creative_btn_button_border_color',
            [
                'label' => esc_html__('Border Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-creative-btn-wrap .king-addons-creative-btn' => '--king-addons-creative-btn-border-color: {{VALUE}}',
                ],
                'conditions' => [
                    'terms' => [
                        [
                            'relation' => 'or',
                            'terms' => [
                                [
                                    'name' => 'king_addons_creative_btn_london_effect',
                                    'operator' => '!=',
                                    'value' => 'radiate',
                                ],
                            ],
                        ],
                        [
                            'terms' => [
                                [
                                    'name' => 'king_addons_creative_btn_style',
                                    'operator' => '!=',
                                    'value' => '',
                                ],
                            ],
                        ],
                    ],
                ],
            ]
        );

        $this->add_control(
            'king_addons_creative_btn_button_radiate_circle_color',
            [
                'label' => esc_html__('Circle Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-creative-btn-wrap .king-addons-creative-btn.king-addons-creative-btn-effect-radiate' => '--king-addons-creative-btn-border-color: {{VALUE}}',
                ],
                'conditions' => [
                    'terms' => [
                        [
                            'relation' => 'or',
                            'terms' => [
                                [
                                    'name' => 'king_addons_creative_btn_london_effect',
                                    'operator' => '==',
                                    'value' => 'radiate',
                                ],
                            ],
                        ],
                        [
                            'terms' => [
                                [
                                    'name' => 'king_addons_creative_btn_style',
                                    'operator' => '==',
                                    'value' => 'london',
                                ],
                            ],
                        ],
                    ],
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'button_box_shadow',
                'selector' => '{{WRAPPER}} .king-addons-creative-btn',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'king_addons_creative_btn__tabs_button_hover',
            [
                'label' => esc_html__('Hover', 'king-addons'),
            ]
        );

        $this->add_control(
            'king_addons_creative_btn_button_hover_text_color',
            [
                'label' => esc_html__('Text Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-creative-btn-wrap .king-addons-creative-btn' => '--king-addons-creative-btn-txt-hover-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'king_addons_creative_btn_button_hover_bg_color',
            [
                'label' => esc_html__('Background Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-creative-btn-wrap .king-addons-creative-btn' => '--king-addons-creative-btn-bg-hover-color: {{VALUE}}',
                ],
                'conditions' => $conditions,
            ]
        );

        $this->add_control(
            'king_addons_creative_btn_button_hover_border_color',
            [
                'label' => esc_html__('Border Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-creative-btn-wrap .king-addons-creative-btn' => '--king-addons-creative-btn-border-hover-color: {{VALUE}}',
                ],
                'conditions' => [
                    'terms' => [
                        [
                            'relation' => 'or',
                            'terms' => [
                                [
                                    'name' => 'king_addons_creative_btn_london_effect',
                                    'operator' => '!=',
                                    'value' => 'radiate',
                                ],
                            ],
                        ],
                        [
                            'terms' => [
                                [
                                    'name' => 'king_addons_creative_btn_style',
                                    'operator' => '!=',
                                    'value' => '',
                                ],
                            ],
                        ],
                    ],
                ],
            ]
        );

        $this->add_control(
            'king_addons_creative_btn_button_hover_radiate_circle_color',
            [
                'label' => esc_html__('Circle Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-creative-btn-wrap .king-addons-creative-btn.king-addons-creative-btn-effect-radiate' => '--king-addons-creative-btn-border-hover-color: {{VALUE}}',
                ],
                'conditions' => [
                    'terms' => [
                        [
                            'relation' => 'or',
                            'terms' => [
                                [
                                    'name' => 'king_addons_creative_btn_london_effect',
                                    'operator' => '==',
                                    'value' => 'radiate',
                                ],
                            ],
                        ],
                        [
                            'terms' => [
                                [
                                    'name' => 'king_addons_creative_btn_style',
                                    'operator' => '==',
                                    'value' => 'london',
                                ],
                            ],
                        ],
                    ],
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'king_addons_creative_btn_hover_box_shadow',
                'selector' => '{{WRAPPER}} .king-addons-creative-btn:hover',
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_responsive_control(
            'king_addons_creative_btn_button_padding',
            [
                'label' => esc_html__('Padding', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-creative-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .king-addons-creative-btn.king-addons-creative-btn-style-icon-slide > span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .king-addons-creative-btn.king-addons-creative-btn-style-paris.king-addons-creative-btn-effect-serene > span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .king-addons-creative-btn.king-addons-creative-btn-style-paris.king-addons-creative-btn-effect-serene::after' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .king-addons-creative-btn.king-addons-creative-btn-style-paris.king-addons-creative-btn-effect-zephyr > span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .king-addons-creative-btn.king-addons-creative-btn-style-paris.king-addons-creative-btn-effect-zephyr::before' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .king-addons-creative-btn.king-addons-creative-btn-style-paris.king-addons-creative-btn-effect-apex' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .king-addons-creative-btn.king-addons-creative-btn-style-paris.king-addons-creative-btn-effect-apex::before' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .king-addons-creative-btn.king-addons-creative-btn-style-london.king-addons-creative-btn-effect-flourish span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->end_controls_section();
    }

    protected function render(): void
    {
        $settings = $this->get_settings();

        switch ($settings['king_addons_creative_btn_style']) {
            case 'london':
                $this->renderLondon($settings, $this->get_id());
                break;
            case 'paris':
                $this->renderParis($settings);
                break;
            case 'rome':
                $this->renderRome($settings);
                break;
            case 'icon-slide':
                $this->renderIconSlide($settings);
                break;
            case 'icon-show':
                $this->renderIconShow($settings);
                break;
        }
    }

    public function renderLondon($settings, $id): void
    {
        $effect = $settings['king_addons_creative_btn_london_effect'];

        echo '<div class="king-addons-creative-btn-wrap">';
        echo '<a href="' . esc_url($settings['king_addons_creative_btn_button_link']['url']) . '"' .
            (($settings['king_addons_creative_btn_button_link']['is_external']) ? ' target="_blank"' : '') .
            (($settings['king_addons_creative_btn_button_link']['nofollow']) ? ' rel="nofollow"' : '') .
            ' class ="king-addons-creative-btn king-addons-creative-btn-style-london king-addons-creative-btn-effect-' . esc_attr($effect) . ' king-addons-creative-btn-id-' . esc_attr($id) . '"';
        echo '>';

        $btn_txt = $settings['king_addons_creative_btn_button_text'];
        if ('glide' == $effect || 'illuminate' == $effect || 'elevate' == $effect) {
            echo '<span>' . esc_html($btn_txt) . '</span>';
        } elseif ('fresh-start' == $effect || 'cascade' == $effect) {
            echo '<span><span>' . esc_html($btn_txt) . '</span></span>';
        } elseif ('flourish' == $effect) {
            echo '<div></div><span>' . esc_html($btn_txt) . '</span>';
        } elseif ('radiate' == $effect) {
            echo '<svg aria-hidden="true" class="king-addons-creative-btn-progress" width="70" height="70" viewbox="0 0 70 70"><path class="king-addons-creative-btn-progress-circle" d="m35,2.5c17.955803,0 32.5,14.544199 32.5,32.5c0,17.955803 -14.544197,32.5 -32.5,32.5c-17.955803,0 -32.5,-14.544197 -32.5,-32.5c0,-17.955801 14.544197,-32.5 32.5,-32.5z" /><path class="king-addons-creative-btn-progress-path" d="m35,2.5c17.955803,0 32.5,14.544199 32.5,32.5c0,17.955803 -14.544197,32.5 -32.5,32.5c-17.955803,0 -32.5,-14.544197 -32.5,-32.5c0,-17.955801 14.544197,-32.5 32.5,-32.5z" pathLength="0.9"/></svg><span>' . esc_html($btn_txt) . '</span>';
        } elseif ('morph' == $effect) {
            echo '<span class="king-addons-creative-btn-morph-text">' . esc_html($btn_txt) . '</span><span class="king-addons-creative-btn-morph-icon"><i aria-hidden="true" class="' . esc_attr(($settings['king_addons_creative_btn_icon']['value'] ?: 'fas fa-crown')) . '"></i></span>';
        }

        echo '</a>';
        echo '</div>';

        if ('morph' == $effect) {
            $js = '
    (function() {
        function bindMorphAnimation() {
            let morph = document.querySelector(".king-addons-creative-btn-id-' . esc_js($id) . '");
            let text = morph.querySelector(".king-addons-creative-btn-morph-text");
            if (morph && text) {
                text.addEventListener("transitionend", function () {
                    if (text.style.width !== "") {
                        text.style.width = "auto";
                    }
                });

                morph.addEventListener("mouseenter", function () {
                    text.style.width = "auto";
                    let predictedWidth = text.offsetWidth;
                    text.style.width = "0";
                    window.getComputedStyle(text).transform; // Trigger reflow
                    text.style.width = `${predictedWidth}px`;
                });

                morph.addEventListener("mouseleave", function () {
                    text.style.width = `${text.offsetWidth}px`;
                    window.getComputedStyle(text).transform; // Trigger reflow
                    text.style.width = "";
                });
            }
        }
        if (document.readyState === "complete") {
         bindMorphAnimation();
        } else {
        document.addEventListener("DOMContentLoaded", bindMorphAnimation);
        }
    })();
    ';
            wp_print_inline_script_tag($js);
        }
    }

    public function renderParis($settings): void
    {
        $effect = $settings['king_addons_creative_btn_paris_effect'];

        echo '<div class="king-addons-creative-btn-wrap">';
        echo '<a href="' . esc_url($settings['king_addons_creative_btn_button_link']['url']) . '"' .
            (($settings['king_addons_creative_btn_button_link']['is_external']) ? ' target="_blank"' : '') .
            (($settings['king_addons_creative_btn_button_link']['nofollow']) ? ' rel="nofollow"' : '') .
            ' class ="king-addons-creative-btn king-addons-creative-btn-style-paris king-addons-creative-btn-effect-' . esc_attr($effect) . '"';
        if ('serene' == $effect || 'zephyr' == $effect || 'apex' == $effect) {
            echo ' data-text="' . esc_attr($settings['king_addons_creative_btn_button_text']) . '"';
        }
        echo '>';

        $btn_txt = esc_html($settings['king_addons_creative_btn_button_text']);
        if ('serene' == $effect || 'zephyr' == $effect || 'brilliance' == $effect) {
            $btn_txt = '<span>' . esc_html($btn_txt) . '</span>';
        } elseif ('apex' == $effect) {
            $btn_txt = $this->splitText($btn_txt);
        }

        // Define allowed tags and attributes
        $allowed_tags = wp_kses_allowed_html('post');
        $allowed_tags['span']['class'] = true;
        $allowed_tags['span']['style'] = true;

        echo wp_kses($btn_txt, $allowed_tags);

        echo '</a>';
        echo '</div>';
    }

    public function renderRome($settings): void
    {
        $effect = $settings['king_addons_creative_btn_rome_effect'];

        echo '<div class="king-addons-creative-btn-wrap">';
        echo '<a href="' . esc_url($settings['king_addons_creative_btn_button_link']['url']) . '"' .
            (($settings['king_addons_creative_btn_button_link']['is_external']) ? ' target="_blank"' : '') .
            (($settings['king_addons_creative_btn_button_link']['nofollow']) ? ' rel="nofollow"' : '') .
            ' class ="king-addons-creative-btn king-addons-creative-btn-style-rome king-addons-creative-btn-effect-' . esc_attr($effect) . '"';
        if ('serene' == $effect || 'zephyr' == $effect || 'apex' == $effect) {
            echo ' data-text="' . esc_attr($settings['king_addons_creative_btn_button_text']) . '"';
        }
        echo '>';

        echo esc_html($settings['king_addons_creative_btn_button_text']);

        echo '</a>';
        echo '</div>';
    }

    public function renderIconSlide($settings): void
    {
        $effect = $settings['king_addons_creative_btn_icon_slide_effect'];

        echo '<div class="king-addons-creative-btn-wrap">';
        echo '<a href="' . esc_url($settings['king_addons_creative_btn_button_link']['url']) . '"' .
            (($settings['king_addons_creative_btn_button_link']['is_external']) ? ' target="_blank"' : '') .
            (($settings['king_addons_creative_btn_button_link']['nofollow']) ? ' rel="nofollow"' : '') .
            ' class ="king-addons-creative-btn king-addons-creative-btn-style-icon-slide king-addons-creative-btn-effect-' . esc_attr($effect) . '"';
        echo '>';

        $btn_txt = $settings['king_addons_creative_btn_button_text'];
        $icon = $settings['king_addons_creative_btn_icon']['value'] ?: 'fas fa-crown';

        echo '<span>' . esc_html($btn_txt) . '</span><i aria-hidden="true" class="' . esc_attr($icon) . '"></i>';

        echo '</a>';
        echo '</div>';
    }

    public function renderIconShow($settings): void
    {
        $effect = $settings['king_addons_creative_btn_icon_show_effect'];

        echo '<div class="king-addons-creative-btn-wrap">';
        echo '<a href="' . esc_url($settings['king_addons_creative_btn_button_link']['url']) . '"' .
            (($settings['king_addons_creative_btn_button_link']['is_external']) ? ' target="_blank"' : '') .
            (($settings['king_addons_creative_btn_button_link']['nofollow']) ? ' rel="nofollow"' : '') .
            ' class ="king-addons-creative-btn king-addons-creative-btn-style-icon-show king-addons-creative-btn-effect-' . esc_attr($effect) . '"';
        echo '>';

        $btn_txt = $settings['king_addons_creative_btn_button_text'];
        $icon = $settings['king_addons_creative_btn_icon']['value'] ?: 'fas fa-crown';

        echo esc_html($btn_txt) . '<i aria-hidden="true" class="' . esc_attr($icon) . '"></i>';

        echo '</a>';
        echo '</div>';
    }

    public function splitText($text): string
    {
        $base = 0.045;
        $markup = '';
        foreach (str_split($text) as $key => $value) {
            $delay = $base * ($key + 1);
            $markup .= trim($value) ? '<span style="--king-addons-creative-btn-effect-apex-delay:' . esc_attr($delay) . 's">' . esc_html($value) . '</span>' : '<span>&nbsp;</span>';
        }
        return $markup;
    }
}