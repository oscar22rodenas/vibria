<?php

namespace King_Addons;

use Elementor\Controls_Manager;
use Elementor\Icons_Manager;
use Elementor\Repeater;
use Elementor\Widget_Base;
use Elementor\Group_Control_Typography;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class Page_List extends Widget_Base
{

    public function get_name(): string
    {
        return 'king-addons-page-list';
    }

    public function get_title(): string
    {
        return esc_html__('Page List', 'king-addons');
    }

    public function get_icon(): string
    {
        return 'king-addons-icon king-addons-page-list';
    }

    public function get_style_depends(): array
    {
        return [KING_ADDONS_ASSETS_UNIQUE_KEY . '-page-list-style'];
    }

    public function get_categories(): array
    {
        return ['king-addons'];
    }

    public function get_keywords(): array
    {
        return ['page-list', 'list', 'page list', 'page', 'pages', 'items', 'navigation', 'nav',
            'king addons', 'king', 'addons', 'kingaddons', 'king-addons', 'item'];
    }

    public function get_custom_help_url(): string
    {
        return 'https://kingaddons.com/elementor/page-list/';
    }

    public function add_control_title_pointer_color_hr()
    {
    }

    public function add_control_title_pointer()
    {
    }

    public function add_control_title_pointer_height()
    {
    }

    public function add_control_title_pointer_animation()
    {
    }

    protected function register_controls()
    {
        $this->start_controls_section(
            'section_page_list_general',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('General', 'king-addons'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'taxonomy_list_layout',
            [
                'label' => esc_html__('Select Layout', 'king-addons'),
                'type' => Controls_Manager::CHOOSE,
                'default' => 'vertical',
                'options' => [
                    'vertical' => [
                        'title' => esc_html__('Vertical', 'king-addons'),
                        'icon' => 'eicon-editor-list-ul',
                    ],
                    'horizontal' => [
                        'title' => esc_html__('Horizontal', 'king-addons'),
                        'icon' => 'eicon-ellipsis-h',
                    ],
                ],
                'prefix_class' => 'king-addons-pl-page-list-',
                'label_block' => false,
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'page_list_item_type',
            [
                'label' => esc_html__('Page Type', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'label_block' => false,
                'default' => 'custom',
                'options' => [
                    'custom' => esc_html__('Custom', 'king-addons'),
                    'dynamic' => esc_html__('Dynamic', 'king-addons')
                ]
            ]
        );

        $repeater->add_control(
            'query_page_selection',
            [
                'label' => esc_html__('Select Page', 'king-addons'),
                'type' => 'king-addons-ajax-select2',
                'options' => 'ajaxselect2/getPostsByPostType',
                'query_slug' => 'page',
                'label_block' => true,
                'condition' => [
                    'page_list_item_type' => 'dynamic'
                ]
            ]
        );

        $repeater->add_control(
            'page_list_item_title',
            [
                'label' => esc_html__('Title', 'king-addons'),
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => esc_html__('New Page', 'king-addons'),
                'label_block' => true,
                'separator' => 'before',
                'condition' => [
                    'page_list_item_type' => 'custom'
                ]
            ]
        );

        $repeater->add_control(
            'page_list_item_sub_title',
            [
                'label' => esc_html__('Sub Title', 'king-addons'),
                'label_block' => true,
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => esc_html__('New Page Sub Title', 'king-addons'),
            ]
        );

        $repeater->add_control(
            'page_list_item_title_url',
            [
                'label' => esc_html__('Title Link', 'king-addons'),
                'type' => Controls_Manager::URL,
                'dynamic' => [
                    'active' => true,
                ],
                'placeholder' => esc_html__('https://example.com', 'king-addons'),
                'default' => [
                    'url' => '',
                    'is_external' => true,
                    'nofollow' => true,
                    'custom_attributes' => '',
                ],
                'condition' => [
                    'page_list_item_type' => 'custom'
                ],
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'open_in_new_page',
            [
                'label' => esc_html__('Open In New Page', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'render_type' => 'template',
                'default' => 'yes',
                'separator' => 'before',
                'condition' => [
                    'page_list_item_type' => 'dynamic'
                ]
            ]
        );

        $repeater->add_control(
            'page_list_item_icon',
            [
                'label' => esc_html__('Select Icon', 'king-addons'),
                'type' => Controls_Manager::ICONS,
                'skin' => 'inline',
                'label_block' => false,
                'separator' => 'before',
                'exclude_inline_options' => 'svg'
            ]
        );

        $repeater->add_control(
            'show_page_list_item_badge',
            [
                'label' => esc_html__('Show Badge', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'label_block' => false,
                'default' => '',
                'separator' => 'before'
            ]
        );

        $repeater->add_control(
            'show_page_list_item_badge_animation',
            [
                'label' => esc_html__('Enable Animation', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'label_block' => false,
                'condition' => [
                    'show_page_list_item_badge' => 'yes'
                ]
            ]
        );

        $repeater->add_control(
            'page_list_item_badge_text', [
                'label' => esc_html__('Badge Text', 'king-addons'),
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => esc_html__('Badge', 'king-addons'),
                'condition' => [
                    'show_page_list_item_badge' => 'yes'
                ]
            ]
        );

        $repeater->add_control(
            'badge_bg_color',
            [
                'label' => esc_html__('Background Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#5B03FF',
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .king-addons-pl-page-list-item-badge' => 'background-color: {{VALUE}}',
                ],
                'condition' => [
                    'show_page_list_item_badge' => 'yes'
                ]
            ]
        );

        $this->add_control(
            'page_list',
            [
                'label' => esc_html__('Repeater List', 'king-addons'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'page_list_item_title' => esc_html__('New Page 1', 'king-addons'),
                        'page_list_item_sub_title' => esc_html__('First Sub Title', 'king-addons'),
                    ],
                    [
                        'page_list_item_title' => esc_html__('New Page 2', 'king-addons'),
                        'page_list_item_sub_title' => esc_html__('Second Sub Title', 'king-addons'),
                    ],
                    [
                        'page_list_item_title' => esc_html__('New Page 3', 'king-addons'),
                        'page_list_item_sub_title' => esc_html__('Third Sub Title', 'king-addons'),
                    ],
                ],
                'title_field' => '{{{ page_list_item_title }}}',
            ]
        );

        $this->end_controls_section();

        Core::renderProFeaturesSection($this, '', Controls_Manager::RAW_HTML, 'page-list', [
            'Underline Page Title on Hover Animations',
        ]);

        $this->start_controls_section(
            'section_style_page_list_general',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('List Items', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            ]
        );

        $this->add_responsive_control(
            'page_list_item_padding',
            [
                'label' => esc_html__('Padding', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'default' => [
                    'top' => '5',
                    'right' => 0,
                    'bottom' => '5',
                    'left' => 0,
                    'isLinked' => ''
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-pl-page-list-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->add_responsive_control(
            'page_list_item_margin',
            [
                'label' => esc_html__('Margin', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'default' => [
                    'top' => '5',
                    'right' => '8',
                    'bottom' => 0,
                    'left' => 0,
                    'unit' => 'px',
                    'isLinked' => false
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-pl-page-list-item' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'page_list_item_border_type',
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
                    '{{WRAPPER}} .king-addons-pl-page-list-item' => 'border-style: {{VALUE}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'page_list_item_border_width',
            [
                'label' => esc_html__('Border Width', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'default' => [
                    'top' => 0,
                    'right' => 0,
                    'bottom' => 1,
                    'left' => 0,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-pl-page-list-item' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'page_list_item_border_type!' => 'none',
                ],
            ]
        );

        $this->add_control(
            'page_list_item_radius',
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
                    '{{WRAPPER}} .king-addons-pl-page-list-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_page_list_title',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Title', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            ]
        );

        $this->start_controls_tabs('list_item_style');

        $this->start_controls_tab(
            'page_list_item_normal',
            [
                'label' => esc_html__('Normal', 'king-addons'),
            ]
        );

        $this->add_control(
            'page_list_item_title_color',
            [
                'label' => esc_html__('Title Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#5B03FF',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-pl-page-list-item a' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'page_list_item_border_color',
            [
                'label' => esc_html__('Border Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#E8E8E8',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-pl-page-list-item' => 'border-color: {{VALUE}}',
                ]
            ]
        );

        $this->add_control(
            'page_list_item_transition_duration',
            [
                'label' => esc_html__('Transition Duration (ms)', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'default' => 300,
                'min' => 0,
                'step' => 10,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-pl-page-list-item' => 'transition-duration: {{VALUE}}ms',
                    '{{WRAPPER}} .king-addons-pl-page-list-item a' => 'transition-duration: {{VALUE}}ms'
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'page_list_item_title_typo',
                'selector' => '{{WRAPPER}} .king-addons-pl-page-list-item a',
                'fields_options' => [
                    'typography' => [
                        'default' => 'custom',
                    ],
                    'font_size' => [
                        'default' => [
                            'size' => '14',
                            'unit' => 'px',
                        ],
                    ],
                    'line_height' => [
                        'default' => [
                            'size' => '0.8',
                            'unit' => 'em',
                        ]
                    ],
                ]
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'page_list_item_hover',
            [
                'label' => esc_html__('Hover', 'king-addons'),
            ]
        );

        $this->add_control(
            'page_list_item_title_color_hr',
            [
                'label' => esc_html__('Title Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#5B03FF',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-pl-page-list-item a:hover' => 'color: {{VALUE}}',
                    '{{WRAPPER}} li.king-addons-pl-page-list-item a:hover' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control_title_pointer_color_hr();

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_control_title_pointer();

        $this->add_control_title_pointer_animation();

        $this->add_control_title_pointer_height();

        $this->add_responsive_control(
            'title_distance',
            [
                'label' => esc_html__('Bottom Distance', 'king-addons'),
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
                    'size' => 8,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-pl-page-list-item div a' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
                'separator' => 'before'
            ]
        );

        $this->end_controls_section();


        $this->start_controls_section(
            'section_style_sub_title',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Sub Title', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE
            ]
        );

        $this->add_control(
            'page_list_item_sub_title_color',
            [
                'label' => esc_html__('Sub Title Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#B6B6B6',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-pl-page-list-item p' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'page_list_item_sub_title_typo',
                'selector' => '{{WRAPPER}} .king-addons-pl-page-list-item p',
                'fields_options' => [
                    'typography' => [
                        'default' => 'custom',
                    ],
                    'font_size' => [
                        'default' => [
                            'size' => '12',
                            'unit' => 'px',
                        ],
                    ],
                    'line_height' => [
                        'default' => [
                            'size' => '0.8',
                            'unit' => 'em',
                        ]
                    ]
                ]
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_badge',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Badge', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE
            ]
        );

        $this->add_control(
            'badge_color',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#FFFFFF',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-pl-page-list-item-badge' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'badge_bg_color',
            [
                'label' => esc_html__('Background Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#5B03FF',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-pl-page-list-item-badge' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'badge_vertical_align',
            [
                'label' => esc_html__('Vertical Align', 'king-addons'),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'default' => 'center',
                'options' => [
                    'top' => [
                        'title' => esc_html__('Top', 'king-addons'),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'center' => [
                        'title' => esc_html__('Middle', 'king-addons'),
                        'icon' => 'eicon-v-align-middle',
                    ],
                    'bottom' => [
                        'title' => esc_html__('Bottom', 'king-addons'),
                        'icon' => 'eicon-v-align-bottom',
                    ]
                ],
                'prefix_class' => 'king-addons-pl-pl-badge-',
                'render_type' => 'template',
                'separator' => 'before'
            ]
        );

        $this->add_responsive_control(
            'badge_size',
            [
                'label' => esc_html__('Size', 'king-addons'),
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
                    'size' => 12,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-pl-page-list-item-badge' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
                'separator' => 'before'
            ]
        );

        $this->add_responsive_control(
            'badge_distance',
            [
                'label' => esc_html__('Space (px)', 'king-addons'),
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
                    '{{WRAPPER}} .king-addons-pl-page-list-item-badge' => 'margin-left: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'badge_border_padding',
            [
                'label' => esc_html__('Padding', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'default' => [
                    'top' => 2,
                    'right' => 5,
                    'bottom' => 2,
                    'left' => 5,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-pl-page-list-item-badge' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->add_control(
            'badge_border_radius',
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
                    '{{WRAPPER}} .king-addons-pl-page-list-item-badge' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_icon',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Icon', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE
            ]
        );

        $this->add_control(
            'icon_color',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#5B03FF',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-pl-page-list i' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .king-addons-pl-page-list svg' => 'fill: {{VALUE}}'
                ],
            ]
        );

        $this->add_control(
            'icon_vertical_align',
            [
                'label' => esc_html__('Vertical Align', 'king-addons'),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'default' => 'center',
                'options' => [
                    'top' => [
                        'title' => esc_html__('Top', 'king-addons'),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'center' => [
                        'title' => esc_html__('Middle', 'king-addons'),
                        'icon' => 'eicon-v-align-middle',
                    ],
                    'bottom' => [
                        'title' => esc_html__('Bottom', 'king-addons'),
                        'icon' => 'eicon-v-align-bottom',
                    ]
                ],
                'prefix_class' => 'king-addons-pl-pl-icon-',
                'render_type' => 'template',
                'separator' => 'before'
            ]
        );

        $this->add_responsive_control(
            'icon_size',
            [
                'label' => esc_html__('Size', 'king-addons'),
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
                    '{{WRAPPER}} .king-addons-pl-page-list i' => 'font-size: {{SIZE}}{{UNIT}}; max-height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .king-addons-pl-page-list svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .king-addons-pl-page-list i:before' => 'max-height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .king-addons-pl-page-list-item-icon' => 'max-height: {{SIZE}}{{UNIT}};',
                ],
                'separator' => 'before'
            ]
        );

        $this->add_responsive_control(
            'icon_distance',
            [
                'label' => esc_html__('Space (px)', 'king-addons'),
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
                    '{{WRAPPER}} .king-addons-pl-page-list .king-addons-pl-page-list-item-icon' => 'margin-right: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();

        $class = ' king-addons-pl-pointer-' .
            (!king_addons_freemius()->can_use_premium_code__premium_only() ? 'none' : $settings['title_pointer']) .
            ' king-addons-pl-pointer-line-anim king-addons-pl-pointer-anim-' .
            (!king_addons_freemius()->can_use_premium_code__premium_only() ? 'fade' : $settings['title_pointer_animation']);

        $pointer_item_class = isset($settings['title_pointer']) && 'none' !== $settings['title_pointer']
            ? 'king-addons-pl-pointer-item'
            : 'king-addons-pl-no-pointer';

        echo '<div class="king-addons-pl-page-list-wrap"><ul class="king-addons-pl-page-list">';

        foreach ($settings['page_list'] as $key => $item) {
            $target = $item['open_in_new_page'] === 'yes' ? '_blank' : '_self';
            $badge_anim_class = $item['show_page_list_item_badge_animation'] === 'yes' ? ' king-addons-pl-pl-badge-anim-yes' : '';
            $this->add_render_attribute('page_list_item' . $key, 'class', 'king-addons-pl-page-list-item elementor-repeater-item-' . $item['_id'] . $class . $badge_anim_class);

            $icon = '';
            if (!empty($item['page_list_item_icon']['value'])) {
                ob_start();
                Icons_Manager::render_icon($item['page_list_item_icon'], ['aria-hidden' => 'true']);
                $icon = ob_get_clean();
            }

            $link_content = !empty($icon) ? '<span class="king-addons-pl-page-list-item-icon">' . $icon . '</span>' : '';
            $link_content .= '<div><a class="' . $pointer_item_class . '" href="' .
                ($item['page_list_item_type'] === 'dynamic' ? get_the_permalink($item['query_page_selection']) : '#') .
                '" target="' . $target . '">' .
                ($item['page_list_item_type'] === 'dynamic' ? get_the_title($item['query_page_selection']) : $item['page_list_item_title']) .
                '</a>';
            if (!empty($item['page_list_item_sub_title'])) {
                $link_content .= '<p>' . $item['page_list_item_sub_title'] . '</p>';
            }
            $link_content .= '</div>';

            echo '<li ' . $this->get_render_attribute_string('page_list_item' . $key) . '>';
            echo $link_content;
            if ($item['show_page_list_item_badge'] === 'yes') {
                echo '<span class="king-addons-pl-page-list-item-badge">' . $item['page_list_item_badge_text'] . '</span>';
            }
            echo '</li>';
        }

        echo '</ul></div>';
    }
}