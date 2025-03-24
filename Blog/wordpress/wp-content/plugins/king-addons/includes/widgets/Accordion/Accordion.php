<?php

namespace King_Addons;

use Elementor;
use Elementor\Icons_Manager;
use Elementor\Repeater;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Typography;

if (!defined('ABSPATH')) exit;

class Accordion extends Widget_Base
{

    public function get_name()
    {
        return 'king-addons-accordion';
    }

    public function get_title()
    {
        return esc_html__('Accordion', 'king-addons');
    }

    public function get_icon()
    {
        return 'king-addons-icon king-addons-accordion';
    }

    public function get_categories()
    {
        return ['king-addons'];
    }

    public function get_keywords()
    {
        return ['king addons', 'king', 'addons', 'kingaddons', 'king-addons',
            'blog', 'accordion', 'slider', 'accor', 'carousel'];
    }

    public function get_script_depends()
    {
        return [KING_ADDONS_ASSETS_UNIQUE_KEY . '-accordion-script',
            KING_ADDONS_ASSETS_UNIQUE_KEY . '-lightgallery-lightgallery'];
    }

    public function get_style_depends()
    {
        return [
            KING_ADDONS_ASSETS_UNIQUE_KEY . '-animation-button',
            KING_ADDONS_ASSETS_UNIQUE_KEY . '-animation-general',
            KING_ADDONS_ASSETS_UNIQUE_KEY . '-animation-timing',
            KING_ADDONS_ASSETS_UNIQUE_KEY . '-animation-loading',
            KING_ADDONS_ASSETS_UNIQUE_KEY . '-accordion-style',
            KING_ADDONS_ASSETS_UNIQUE_KEY . '-lightgallery-lightgallery',
        ];
    }

    public function get_custom_help_url()
    {
        return 'https://kingaddons.com/';
    }

    public function add_repeater_args_accordion_content_type()
    {
        return [
            'editor' => esc_html__('Text Editor', 'king-addons'),
            'pro-tm' => esc_html__('Elementor Template (Pro)', 'king-addons')
        ];
    }

    public function add_control_show_acc_search()
    {
        $this->add_control(
            'show_acc_search',
            [
                'label' => sprintf(__('Show Search %s', 'king-addons'), '<i class="eicon-pro-icon"></i>'),
                'type' => Controls_Manager::SWITCHER,
                'separator' => 'before',
                'classes' => 'king-addons-pro-control'
            ]
        );
    }

    public function add_section_style_search_input()
    {
    }

    public function render_search_input($settings)
    {
    }

    protected function register_controls()
    {


        $this->start_controls_section(
            'section_accordion_content',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Content', 'king-addons'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );


        $repeater = new Repeater();

        $repeater->add_control(
            'accordion_title', [
                'label' => esc_html__('Title', 'king-addons'),
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => esc_html__('Acc Item Title', 'king-addons'),
            ]
        );

        $repeater->add_control(
            'accordion_content_type',
            [
                'label' => esc_html__('Content Type', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'editor',
                'options' => $this->add_repeater_args_accordion_content_type(),
                'render_type' => 'template',
            ]
        );


        Core::renderUpgradeProNotice($repeater, Controls_Manager::RAW_HTML, 'accordion', 'accordion_content_type', ['pro-tm']);

        $repeater->add_control(
            'accordion_content_template',
            [
                'label' => esc_html__('Select Template', 'king-addons'),
                'type' => 'king-addons-ajax-select2',
                'options' => 'ajaxselect2/getElementorTemplates',
                'label_block' => true,
                'condition' => [
                    'accordion_content_type' => 'template',
                ],
            ]
        );

        $repeater->add_control(
            'accordion_icon',
            [
                'label' => esc_html__('Select Icon', 'king-addons'),
                'type' => Controls_Manager::ICONS,
                'skin' => 'inline',
                'label_block' => false,
                'separator' => 'before',
                'default' => [
                    'value' => 'far fa-edit',
                    'library' => 'regular'
                ]
            ]
        );

        $repeater->add_control(
            'accordion_content',
            [
                'label' => esc_html__('Content', 'king-addons'),
                'type' => Controls_Manager::WYSIWYG,
                'placeholder' => esc_html__('Tab Content', 'king-addons'),
                'default' => 'Nobis atque id hic neque possimus voluptatum voluptatibus tenetur, perspiciatis consequuntur. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Minima incidunt voluptates nemo, dolor optio quia architecto quis delectus perspiciatis.',
                'condition' => [
                    'accordion_content_type!' => 'template'
                ]
            ]
        );

        $this->add_control(
            'advanced_accordion',
            [
                'label' => esc_html__('Accordion Items', 'king-addons'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'accordion_title' => esc_html__('Title #1', 'king-addons'),
                        'accordion_icon' => [
                            'value' => 'fas fa-star',
                            'library' => 'solid'
                        ],
                        'accordion_content' => esc_html__('Item content. Click the edit button to change this text.', 'king-addons'),
                    ],
                    [
                        'accordion_title' => esc_html__('Title #2', 'king-addons'),
                        'accordion_icon' => [
                            'value' => 'fas fa-crown',
                            'library' => 'brands'
                        ],
                        'accordion_content' => esc_html__('Item content. Click the edit button to change this text.', 'king-addons'),
                    ],
                    [
                        'accordion_title' => esc_html__('Title #3', 'king-addons'),
                        'accordion_icon' => [
                            'value' => 'fas fa-rocket',
                            'library' => 'solid'
                        ],
                        'accordion_content' => esc_html__('Item content. Click the edit button to change this text.', 'king-addons'),
                    ]
                ],
                'title_field' => '{{{ accordion_title }}}',
            ]
        );


        $this->end_controls_section();


        $this->start_controls_section(
            'section_accordion_settings',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Settings', 'king-addons'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'accordion_type',
            [
                'label' => esc_html__('Accordion Type', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'accordion',
                'label_block' => false,
                'options' => [
                    'accordion' => esc_html__('Accordion', 'king-addons'),
                    'toggle' => esc_html__('Toggle', 'king-addons'),
                ]
            ]
        );

        $this->add_control(
            'accordion_trigger',
            [
                'label' => esc_html__('Trigger', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'click',
                'label_block' => false,
                'options' => [
                    'click' => esc_html__('Click', 'king-addons'),
                    'hover' => esc_html__('Hover', 'king-addons'),
                ],
                'condition' => [
                    'accordion_type' => 'accordion'
                ]
            ]
        );

        $this->add_control(
            'accordion_title_tag',
            [
                'label' => esc_html__('Title HTML Tag', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'span' => esc_html__('Span', 'king-addons'),
                    'h1' => esc_html__('H1', 'king-addons'),
                    'h2' => esc_html__('H2', 'king-addons'),
                    'h3' => esc_html__('H3', 'king-addons'),
                    'h4' => esc_html__('H4', 'king-addons'),
                    'h5' => esc_html__('H5', 'king-addons'),
                    'h6' => esc_html__('H6', 'king-addons')
                ],
                'default' => 'span',
            ]
        );

        $this->add_control(
            'interaction_speed',
            [
                'label' => esc_html__('Animation Duration', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'default' => 0.4,
                'step' => 0.1,
                'min' => 0,
                'max' => 2,
                'render_type' => 'template',
            ]
        );

        $this->add_control(
            'active_item',
            [
                'label' => esc_html__('Active Item Index', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'label_block' => false,
                'default' => 1,
                'min' => 0,
                'render_type' => 'template',
                'frontend_available' => true,
                'separator' => 'before'
            ]
        );

        $this->add_control_show_acc_search();

        $this->add_control(
            'acc_search_icon',
            [
                'label' => esc_html__('Select Icon', 'king-addons'),
                'type' => Controls_Manager::ICONS,
                'skin' => 'inline',
                'label_block' => false,
                'default' => [
                    'value' => 'fas fa-search',
                    'library' => 'fa-solid',
                ],
                'condition' => [
                    'show_acc_search' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'acc_search_placeholder',
            [
                'label' => esc_html__('Placeholder', 'king-addons'),
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => esc_html__('Search...', 'king-addons'),
                'condition' => [
                    'show_acc_search' => 'yes'
                ]
            ]
        );

        $this->end_controls_section();


        $this->start_controls_section(
            'section_icon_settings',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Icons', 'king-addons'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'change_icons_position',
            [
                'label' => esc_html__('Position', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'default',
                'label_block' => false,
                'options' => [
                    'default' => esc_html__('Default', 'king-addons'),
                    'reverse' => esc_html__('Reverse', 'king-addons'),
                ]
            ]
        );

        $this->add_control(
            'accordion_title_icon_box_style',
            [
                'label' => esc_html__('Box Style', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'side-box',
                'options' => [
                    'no-box' => esc_html__('None', 'king-addons'),
                    'side-box' => esc_html__('Side Box', 'king-addons'),
                    'side-curve' => esc_html__('Side Curve', 'king-addons')
                ],
                'prefix_class' => 'king-addons-advanced-accordion-icon-',
                'render_type' => 'template'
            ]
        );

        $this->add_control(
            'accordion_title_icon_box_width',
            [
                'label' => esc_html__('Box Width', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'default' => [
                    'size' => 70,
                    'unit' => 'px'
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 200,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-acc-icon-box' => 'width: {{SIZE}}{{UNIT}};'
                ],
                'condition' => [
                    'accordion_title_icon_box_style!' => 'none'
                ]
            ]
        );

        $this->add_responsive_control(
            'accordion_title_icon_after_box_width',
            [
                'label' => esc_html__('Triangle Width', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'default' => [
                    'size' => 30,
                    'unit' => 'px'
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-advanced-accordion .king-addons-acc-button .king-addons-acc-icon-box-after' => 'border-left: {{SIZE}}{{UNIT}} solid {{icon_box_color.VALUE}};',
                    '{{WRAPPER}} .king-addons-advanced-accordion .king-addons-acc-button:hover .king-addons-acc-icon-box-after' => 'border-left: {{SIZE}}{{UNIT}} solid {{icon_box_hover_color.VALUE}};',
                    '{{WRAPPER}} .king-addons-advanced-accordion .king-addons-acc-button.king-addons-acc-active .king-addons-acc-icon-box-after' => 'border-left: {{SIZE}}{{UNIT}} solid {{icon_box_active_color.VALUE}};',
                ],
                'condition' => [
                    'accordion_title_icon_box_style' => 'side-curve'
                ]
            ]
        );

        $this->add_control(
            'toggle_icon',
            [
                'label' => esc_html__('Select Toggle Icon', 'king-addons'),
                'type' => Controls_Manager::ICONS,
                'skin' => 'inline',
                'label_block' => false,
                'default' => [
                    'value' => 'fas fa-plus',
                    'library' => 'fa-solid',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'toggle_icon_active',
            [
                'label' => esc_html__('Select Toggle Icon Active', 'king-addons'),
                'type' => Controls_Manager::ICONS,
                'skin' => 'inline',
                'label_block' => false,
                'default' => [
                    'value' => 'fas fa-minus',
                    'library' => 'fa-solid',
                ]
            ]
        );

        $this->add_control(
            'toggle_icon_rotation',
            [
                'label' => esc_html__('Active Icon Rotation', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'default' => [
                    'size' => 0,
                    'unit' => 'px'
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 360,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-advanced-accordion .king-addons-acc-active .king-addons-toggle-icon i' => 'transform: rotate({{SIZE}}deg); transform-origin: center;',
                    '{{WRAPPER}} .king-addons-advanced-accordion .king-addons-acc-active .king-addons-toggle-icon svg' => 'transform: rotate({{SIZE}}deg); transform-origin: center;'
                ]
            ]
        );

        $this->end_controls_section();


        Core::renderProFeaturesSection($this, '', Controls_Manager::RAW_HTML, 'advanced-accordion', [
            'The Elementor content can be placed inside.',
            'Enable Accordion Content Live Search'
        ]);

        $this->add_section_style_search_input();


        $this->start_controls_section(
            'section_style_switcher',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Title', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->start_controls_tabs('tab_style');

        $this->start_controls_tab(
            'tab_normal_style',
            [
                'label' => esc_html__('Normal', 'king-addons')
            ]
        );

        $this->add_control(
            'tab_color',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#5B03FF',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-advanced-accordion .king-addons-acc-button' => 'color: {{VALUE}}',
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'tab_bg_color',
                'label' => esc_html__('Background', 'king-addons'),
                'types' => ['classic', 'gradient'],
                'exclude' => ['image'],
                'fields_options' => [
                    'background' => [
                        'default' => 'classic',
                    ],
                    'color' => [
                        'default' => '#FFFFFF',
                    ],
                ],
                'selector' => '{{WRAPPER}} .king-addons-advanced-accordion .king-addons-acc-button'
            ]
        );

        $this->add_control(
            'tab_border_color',
            [
                'label' => esc_html__('Border Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#EAEAEA',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-advanced-accordion .king-addons-acc-button' => 'border-color: {{VALUE}}',
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'tab_box_shadow',
                'selector' => '{{WRAPPER}} .king-addons-advanced-accordion .king-addons-acc-button',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'tab_typography',
                'selector' => '{{WRAPPER}} .king-addons-advanced-accordion .king-addons-acc-button, {{WRAPPER}} .king-addons-advanced-accordion .king-addons-acc-button .king-addons-acc-title-text',
                'fields_options' => [
                    'typography' => [
                        'default' => 'custom',
                    ],
                    'font_weight' => [
                        'default' => '400',
                    ]
                ]
            ]
        );

        $this->add_control(
            'accordion_transition',
            [
                'label' => esc_html__('Transition', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'default' => 0.3,
                'min' => 0,
                'max' => 5,
                'step' => 0.1,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-advanced-accordion button.king-addons-acc-button' => 'transition: all {{VALUE}}s ease-in-out;',
                ]
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_hover_style',
            [
                'label' => esc_html__('Hover', 'king-addons')
            ]
        );

        $this->add_control(
            'tab_hover_color',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-advanced-accordion .king-addons-acc-button:hover' => 'color: {{VALUE}}',
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'tab_hover_bg_color',
                'label' => esc_html__('Background', 'king-addons'),
                'types' => ['classic', 'gradient'],
                'exclude' => ['image'],
                'selector' => '{{WRAPPER}} .king-addons-advanced-accordion .king-addons-acc-button:hover'
            ]
        );

        $this->add_control(
            'tab_hover_border_color',
            [
                'label' => esc_html__('Border Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-advanced-accordion .king-addons-acc-button:hover' => 'border-color: {{VALUE}}',
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'tab_hover_box_shadow',
                'selector' => '{{WRAPPER}} .king-addons-advanced-accordion .king-addons-acc-button:hover',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_active_style',
            [
                'label' => esc_html__('Active', 'king-addons')
            ]
        );

        $this->add_control(
            'tab_active_color',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-advanced-accordion .king-addons-acc-button.king-addons-acc-active' => 'color: {{VALUE}}',
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'tab_active_bg_color',
                'label' => esc_html__('Background', 'king-addons'),
                'types' => ['classic', 'gradient'],
                'exclude' => ['image'],
                'selector' => '{{WRAPPER}} .king-addons-advanced-accordion .king-addons-acc-button.king-addons-acc-active'
            ]
        );

        $this->add_control(
            'tab_active_border_color',
            [
                'label' => esc_html__('Border Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-advanced-accordion .king-addons-acc-button.king-addons-acc-active' => 'border-color: {{VALUE}}',
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'tab_active_box_shadow',
                'selector' => '{{WRAPPER}} .king-addons-advanced-accordion .king-addons-acc-button.king-addons-acc-active',
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_responsive_control(
            'tab_gutter',
            [
                'label' => esc_html__('Vertical Gutter', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 6,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-advanced-accordion .king-addons-acc-button' => 'margin-top: {{SIZE}}{{UNIT}}; margin-bottom: {{SIZE}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'tab_title_distance',
            [
                'label' => esc_html__('Title Left Distance', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 5,
                ],
                'selectors' => [
                    '{{WRAPPER}}.king-addons-advanced-accordion-icon-no-box .king-addons-acc-item-title .king-addons-acc-title-text' => 'margin-left: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}}.king-addons-advanced-accordion-icon-side-box .king-addons-acc-item-title .king-addons-acc-title-text' => 'margin-left: calc({{accordion_title_icon_box_width.SIZE}}{{accordion_title_icon_box_width.UNIT}} + {{SIZE}}{{UNIT}});',
                    '{{WRAPPER}}.king-addons-advanced-accordion-icon-side-curve .king-addons-acc-item-title .king-addons-acc-title-text' => 'margin-left: calc({{accordion_title_icon_box_width.SIZE}}{{accordion_title_icon_box_width.UNIT}} + {{accordion_title_icon_after_box_width.SIZE}}{{accordion_title_icon_after_box_width.UNIT}} + {{SIZE}}{{UNIT}});',
                ],
            ]
        );

        $this->add_responsive_control(
            'tab_padding',
            [
                'label' => esc_html__('Padding', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'default' => [
                    'top' => 18,
                    'right' => 18,
                    'bottom' => 18,
                    'left' => 18,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-advanced-accordion .king-addons-acc-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'tab_border_type',
            [
                'label' => esc_html__('Border Type', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'none' => esc_html__('None', 'king-addons'),
                    'solid' => esc_html__('Solid', 'king-addons'),
                    'double' => esc_html__('Double', 'king-addons'),
                    'dotted' => esc_html__('Dotted', 'king-addons'),
                    'dashed' => esc_html__('Dashed', 'king-addons'),
                    'groove' => esc_html__('Groove', 'king-addons'),
                ],
                'default' => 'solid',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-advanced-accordion .king-addons-acc-button' => 'border-style: {{VALUE}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'tab_border_width',
            [
                'label' => esc_html__('Border Width', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'default' => [
                    'top' => 1,
                    'right' => 1,
                    'bottom' => 1,
                    'left' => 1,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-advanced-accordion .king-addons-acc-button' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'tab_border_type!' => 'none',
                ],
            ]
        );

        $this->add_responsive_control(
            'tab_border_radius',
            [
                'label' => esc_html__('Border Radius', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'default' => [
                    'top' => 0,
                    'right' => 0,
                    'bottom' => 0,
                    'left' => 0,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-advanced-accordion .king-addons-acc-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->end_controls_section();


        $this->start_controls_section(
            'section_style_icon',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Icons', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE
            ]
        );

        $this->start_controls_tabs('tab_style_icon');

        $this->start_controls_tab(
            'tab_icon_normal_style',
            [
                'label' => esc_html__('Normal', 'king-addons')
            ]
        );

        $this->add_control(
            'tab_main_icon_color',
            [
                'label' => esc_html__('Main Icon Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#EDEDED',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-advanced-accordion .king-addons-acc-button .king-addons-title-icon i' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .king-addons-advanced-accordion .king-addons-acc-button .king-addons-title-icon svg' => 'fill: {{VALUE}}',
                ]
            ]
        );

        $this->add_control(
            'tab_toggle_icon_color',
            [
                'label' => esc_html__('Toggle Icon Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#333333',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-advanced-accordion .king-addons-acc-button .king-addons-toggle-icon i' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .king-addons-advanced-accordion .king-addons-acc-button .king-addons-toggle-icon svg' => 'fill: {{VALUE}}',
                ]
            ]
        );

        $this->add_control(
            'icon_box_color',
            [
                'label' => esc_html__('Icon Box Bg Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#5B03FF',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-advanced-accordion .king-addons-acc-button .king-addons-acc-icon-box' => 'background-color: {{VALUE}}',
                ],
                'condition' => [
                    'accordion_title_icon_box_style!' => 'none'
                ]
            ]
        );

        $this->add_control(
            'accordion_icon_transition',
            [
                'label' => esc_html__('Transition', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'default' => 0.3,
                'min' => 0,
                'max' => 5,
                'step' => 0.1,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-advanced-accordion .king-addons-toggle-icon i' => 'transition: all {{VALUE}}s ease-in-out;',
                    '{{WRAPPER}} .king-addons-advanced-accordion .king-addons-title-icon i' => 'transition: all {{VALUE}}s ease-in-out;',
                    '{{WRAPPER}} .king-addons-advanced-accordion .king-addons-toggle-icon svg' => 'transition: all {{VALUE}}s ease-in-out;',
                    '{{WRAPPER}} .king-addons-advanced-accordion .king-addons-title-icon svg' => 'transition: all {{VALUE}}s ease-in-out;',
                ]
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_icon_hover_style',
            [
                'label' => esc_html__('Hover', 'king-addons')
            ]
        );

        $this->add_control(
            'tab_main_hover_icon_color',
            [
                'label' => esc_html__('Main Icon Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-advanced-accordion .king-addons-acc-button:hover .king-addons-title-icon i' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .king-addons-advanced-accordion .king-addons-acc-button:hover .king-addons-title-icon svg' => 'fill: {{VALUE}}',
                ]
            ]
        );

        $this->add_control(
            'tab_toggle_hover_icon_color',
            [
                'label' => esc_html__('Toggle Icon Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-advanced-accordion .king-addons-acc-button:hover .king-addons-toggle-icon i' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .king-addons-advanced-accordion .king-addons-acc-button:hover .king-addons-toggle-icon svg' => 'fill: {{VALUE}}',
                ]
            ]
        );

        $this->add_control(
            'icon_box_hover_color',
            [
                'label' => esc_html__('Icon Box Bg Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-advanced-accordion .king-addons-acc-button:hover .king-addons-acc-icon-box' => 'background-color: {{VALUE}}',
                ],
                'condition' => [
                    'accordion_title_icon_box_style!' => 'none'
                ]
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_icon_active_style',
            [
                'label' => esc_html__('Active', 'king-addons')
            ]
        );

        $this->add_control(
            'tab_main_active_icon_color',
            [
                'label' => esc_html__('Main Icon Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-advanced-accordion .king-addons-acc-button.king-addons-acc-active .king-addons-title-icon i' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .king-addons-advanced-accordion .king-addons-acc-button.king-addons-acc-active .king-addons-title-icon svg' => 'fill: {{VALUE}}',
                ]
            ]
        );

        $this->add_control(
            'tab_toggle_active_icon_color',
            [
                'label' => esc_html__('Toggle Icon Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-advanced-accordion .king-addons-acc-button.king-addons-acc-active .king-addons-toggle-icon i' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .king-addons-advanced-accordion .king-addons-acc-button.king-addons-acc-active .king-addons-toggle-icon svg' => 'fill: {{VALUE}}',
                ]
            ]
        );

        $this->add_control(
            'icon_box_active_color',
            [
                'label' => esc_html__('Icon Box Bg Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-advanced-accordion .king-addons-acc-button.king-addons-acc-active .king-addons-acc-icon-box' => 'background-color: {{VALUE}}',
                ],
                'condition' => [
                    'accordion_title_icon_box_style!' => 'none'
                ]
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_responsive_control(
            'tab_main_icon_size',
            [
                'label' => esc_html__('Main Icon Size', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 18,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-advanced-accordion .king-addons-acc-button .king-addons-title-icon i' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .king-addons-advanced-accordion .king-addons-acc-button .king-addons-title-icon svg' => 'width: {{SIZE}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'tab_toggle_icon_size',
            [
                'label' => esc_html__('Toggle Icon Size', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 15,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-advanced-accordion .king-addons-acc-button .king-addons-toggle-icon i' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .king-addons-advanced-accordion .king-addons-acc-button .king-addons-toggle-icon svg' => 'width: {{SIZE}}{{UNIT}};',
                ]
            ]
        );

        $this->add_responsive_control(
            'icon_box_border_radius',
            [
                'label' => esc_html__('Icon Box Border Radius', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'default' => [
                    'top' => 0,
                    'right' => 0,
                    'bottom' => 0,
                    'left' => 0,
                ],
                'selectors' => [
                    '{{WRAPPER}}.king-addons-advanced-accordion-icon-side-box .king-addons-advanced-accordion .king-addons-acc-icon-box' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}}.king-addons-advanced-accordion-icon-side-curve .king-addons-advanced-accordion .king-addons-acc-icon-box' => 'border-radius: {{TOP}}{{UNIT}} 0 0 {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
                'condition' => [
                    'accordion_title_icon_box_style!' => 'no-box'
                ]
            ]
        );

        $this->end_controls_section();


        $this->start_controls_section(
            'section_style_content',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Content', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE
            ]
        );

        $this->add_control(
            'content_color',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#7a7a7a',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-advanced-accordion .king-addons-acc-panel .king-addons-acc-panel-content' => 'color: {{VALUE}};',
                ]
            ]
        );

        $this->add_control(
            'content_bg_color',
            [
                'label' => esc_html__('Background Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#FFFFFF',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-advanced-accordion .king-addons-acc-panel' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'content_border_color',
            [
                'label' => esc_html__('Border Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#E8E8E8',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-advanced-accordion .king-addons-acc-panel' => 'border-color: {{VALUE}}',
                ],


            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'content_box_shadow',
                'selector' => '{{WRAPPER}} .king-addons-advanced-accordion .king-addons-acc-panel',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'content_typography',
                'selector' => '{{WRAPPER}} .king-addons-advanced-accordion .king-addons-acc-panel .king-addons-acc-panel-content',
            ]
        );

        $this->add_responsive_control(
            'content_padding',
            [
                'label' => esc_html__('Padding', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'default' => [
                    'top' => 25,
                    'right' => 25,
                    'bottom' => 25,
                    'left' => 25,
                ],
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-advanced-accordion .king-addons-acc-panel' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'content_border_type',
            [
                'label' => esc_html__('Border Type', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'none' => esc_html__('None', 'king-addons'),
                    'solid' => esc_html__('Solid', 'king-addons'),
                    'double' => esc_html__('Double', 'king-addons'),
                    'dotted' => esc_html__('Dotted', 'king-addons'),
                    'dashed' => esc_html__('Dashed', 'king-addons'),
                    'groove' => esc_html__('Groove', 'king-addons'),
                ],
                'default' => 'solid',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-advanced-accordion .king-addons-acc-panel' => 'border-style: {{VALUE}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'content_border_width',
            [
                'label' => esc_html__('Border Width', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px',],
                'default' => [
                    'top' => 1,
                    'right' => 1,
                    'bottom' => 1,
                    'left' => 1,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-advanced-accordion .king-addons-acc-panel' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'content_border_type!' => 'none',
                ],
            ]
        );

        $this->add_responsive_control(
            'content_border_radius',
            [
                'label' => esc_html__('Border Radius', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'default' => [
                    'top' => 0,
                    'right' => 0,
                    'bottom' => 0,
                    'left' => 0,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-advanced-accordion .king-addons-acc-panel' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->end_controls_section();
    }

    public function king_addons_accordion_template($id)
    {
        if (empty($id)) return '';
        if (defined('ICL_LANGUAGE_CODE')) {
            $default_language_code = apply_filters('wpml_default_language', null);
            /** @noinspection PhpUndefinedConstantInspection */
            if (ICL_LANGUAGE_CODE !== $default_language_code) {
                /** @noinspection PhpUndefinedConstantInspection */
                /** @noinspection PhpUndefinedFunctionInspection */
                $id = icl_object_id($id, 'elementor_library', false, ICL_LANGUAGE_CODE);
            }
        }
        $edit_link = '<a class="king-addons-template-edit-btn" href="' . get_permalink($id) . '">Edit Template</a>';
        $type = get_post_meta(get_the_ID(), '_king_addons_template_type', true);
        $has_css = ('internal' === get_option('elementor_css_print_method') || '' !== $type);
        return Elementor\Plugin::instance()->frontend->get_builder_content_for_display($id, $has_css) . $edit_link;
    }

    public function render_first_icon($settings, $acc)
    {
        if ($settings['change_icons_position'] == 'reverse') {
            if (!empty($settings['toggle_icon'])) {
                echo '<span class="king-addons-toggle-icon king-addons-ti-close">';
                Icons_Manager::render_icon($settings['toggle_icon'], ['aria-hidden' => 'true']);
                echo '</span><span class="king-addons-toggle-icon king-addons-ti-open">';
                Icons_Manager::render_icon($settings['toggle_icon_active'], ['aria-hidden' => 'true']);
                echo '</span>';
            }
        } else {
            if (!empty($acc['accordion_icon'])) {
                echo '<span class="king-addons-title-icon">';
                Icons_Manager::render_icon($acc['accordion_icon'], ['aria-hidden' => 'true']);
                echo '</span>';
            }
        }
    }

    public function render_second_icon($settings, $acc)
    {
        if ($settings['change_icons_position'] == 'reverse') {
            if (!empty($acc['accordion_icon'])) {
                echo '<span class="king-addons-title-icon">';
                Icons_Manager::render_icon($acc['accordion_icon'], ['aria-hidden' => 'true']);
                echo '</span>';
            }
        } else {
            if (!empty($settings['toggle_icon'])) {
                echo '<span class="king-addons-toggle-icon king-addons-ti-close">';
                Icons_Manager::render_icon($settings['toggle_icon'], ['aria-hidden' => 'true']);
                echo '</span><span class="king-addons-toggle-icon king-addons-ti-open">';
                Icons_Manager::render_icon($settings['toggle_icon_active'], ['aria-hidden' => 'true']);
                echo '</span>';
            }
        }
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $tags_whitelist = ['h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'div', 'span', 'p'];
        $accordion_title_tag = in_array($settings['accordion_title_tag'], $tags_whitelist) ? $settings['accordion_title_tag'] : 'span';
        $this->add_render_attribute('accordion_attributes', [
            'class' => ['king-addons-advanced-accordion'],
            'data-accordion-type' => esc_attr($settings['accordion_type']),
            'data-active-index' => intval($settings['active_item']),
            'data-accordion-trigger' => isset($settings['accordion_trigger']) ? esc_attr($settings['accordion_trigger']) : 'click',
            'data-interaction-speed' => isset($settings['interaction_speed']) ? floatval($settings['interaction_speed']) : 0.4
        ]);
        if ('yes' === $settings['show_acc_search']) {
            $this->add_render_attribute('input', [
                'placeholder' => esc_attr($settings['acc_search_placeholder']),
                'class' => 'king-addons-acc-search-input',
                'type' => 'search',
                'title' => esc_html__('Search', 'king-addons')
            ]);
        }
        echo '<div ' . $this->get_render_attribute_string('accordion_attributes') . '>';
        $this->render_search_input($settings);
        foreach ($settings['advanced_accordion'] as $acc) {
            $acc_content_type = $acc['accordion_content_type'];
            if (!king_addons_freemius()->can_use_premium_code__premium_only()) {
                $acc_content_type = 'editor';
            }
            echo '<div class="king-addons-accordion-item-wrap"><button class="king-addons-acc-button"><span class="king-addons-acc-item-title">';
            if ('side-box' === $settings['accordion_title_icon_box_style']) {
                echo '<div class="king-addons-acc-icon-box">';
                $this->render_first_icon($settings, $acc);
                echo '</div>';
            } elseif ('side-curve' === $settings['accordion_title_icon_box_style']) {
                echo '<div class="king-addons-acc-icon-box">';
                $this->render_first_icon($settings, $acc);
                echo '<div class="king-addons-acc-icon-box-after"></div></div>';
            } else {
                $this->render_first_icon($settings, $acc);
            }
            echo '<' . $accordion_title_tag . ' class="king-addons-acc-title-text">' . $acc['accordion_title'] . '</' . $accordion_title_tag . '></span>';
            $this->render_second_icon($settings, $acc);
            echo '</button><div class="king-addons-acc-panel">';
            if ('editor' === $acc_content_type) {
                echo '<div class="king-addons-acc-panel-content">' . $acc['accordion_content'] . '</div>';
            } else {
                echo $this->king_addons_accordion_template($acc['accordion_content_template']);
            }
            echo '</div></div>';
        }
        echo '</div>';
    }
}