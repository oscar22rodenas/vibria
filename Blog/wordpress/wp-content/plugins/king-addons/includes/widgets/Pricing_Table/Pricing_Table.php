<?php

namespace King_Addons;

use Elementor\Controls_Manager;
use Elementor\Widget_Base;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Repeater;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class Pricing_Table extends Widget_Base
{
    public function get_name(): string
    {
        return 'king-addons-pricing-table';
    }

    public function get_title(): string
    {
        return esc_html__('Pricing Table', 'king-addons');
    }

    public function get_icon(): string
    {
        return 'king-addons-icon king-addons-pricing-table';
    }

    public function get_style_depends(): array
    {
        return [KING_ADDONS_ASSETS_UNIQUE_KEY . '-pricing-table-style', KING_ADDONS_ASSETS_UNIQUE_KEY . '-animation-button'];
    }

    public function get_categories(): array
    {
        return ['king-addons'];
    }

    public function get_keywords(): array
    {
        return ['price', 'pricing', 'table', 'pricing table', 'features', 'features table',
            'king addons', 'king', 'addons', 'kingaddons', 'king-addons'];
    }

    public function get_custom_help_url(): string
    {
        return 'https://kingaddons.com/elementor/pricing-table/';
    }

    public function add_repeater_args_feature_tooltip(): array
    {
        return [
            'label' => sprintf(__('Show Tooltip %s', 'king-addons'), '<i class="eicon-pro-icon"></i>'),
            'type' => Controls_Manager::SWITCHER,
            'classes' => 'king-addons-pro-control'
        ];
    }

    public function add_repeater_args_feature_tooltip_text(): array
    {
        return [
            'type' => Controls_Manager::HIDDEN,
            'default' => ''
        ];
    }

    public function add_repeater_args_feature_tooltip_show_icon(): array
    {
        return [
            'type' => Controls_Manager::HIDDEN,
            'default' => ''
        ];
    }

    public function add_control_stack_feature_tooltip_section()
    {
    }

    public function add_controls_group_feature_even_bg()
    {
        $this->add_control(
            'feature_even_bg',
            [
                'label' => sprintf(__('Enable Even Color %s', 'king-addons'), '<i class="eicon-pro-icon"></i>'),
                'type' => Controls_Manager::SWITCHER,
                'classes' => 'king-addons-pro-control no-distance'
            ]
        );
    }

    protected function register_controls()
    {
        $this->start_controls_section(
            'section_pricing_items',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Price Table', 'king-addons'),
            ]
        );

        if (!king_addons_freemius()->can_use_premium_code__premium_only()) {
            $this->add_control(
                'pricing_table_pro_notice',
                [
                    'type' => Controls_Manager::RAW_HTML,
                    'raw' => 'Feature Item Tooltip and Even/Odd Feature Item Background Color options are available in the <strong><a href="https://kingaddons.com/pricing/?utm_source=kng-module-pricing-table-settings-upgrade-pro&utm_medium=plugin&utm_campaign=kng" target="_blank">Pro version</a></strong>',
                    'content_classes' => 'king-addons-pro-notice',
                ]
            );
        }

        $repeater = new Repeater();

        $repeater->add_control(
            'type_select',
            [
                'label' => esc_html__('Type', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'heading',
                'options' => [
                    'heading' => esc_html__('Heading', 'king-addons'),
                    'price' => esc_html__('Price', 'king-addons'),
                    'feature' => esc_html__('Feature', 'king-addons'),
                    'text' => esc_html__('Text', 'king-addons'),
                    'button' => esc_html__('Button', 'king-addons'),
                    'divider' => esc_html__('Divider', 'king-addons'),
                ],
                'separator' => 'after',
            ]
        );

        $repeater->add_control(
            'heading_title',
            [
                'label' => esc_html__('Title', 'king-addons'),
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => 'Title',
                'condition' => [
                    'type_select' => 'heading',
                ],
            ]
        );

        $repeater->add_control(
            'heading_sub_title',
            [
                'label' => esc_html__('Sub Title', 'king-addons'),
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => 'Subtitle text',
                'condition' => [
                    'type_select' => 'heading',
                ],
            ]
        );

        $repeater->add_control(
            'heading_icon_type',
            [
                'label' => esc_html__('Icon Type', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'icon',
                'options' => [
                    'none' => esc_html__('None', 'king-addons'),
                    'icon' => esc_html__('Icon', 'king-addons'),
                    'image' => esc_html__('Image', 'king-addons'),
                ],
                'condition' => [
                    'type_select' => 'heading',
                ],

            ]
        );

        $repeater->add_control(
            'heading_image',
            [
                'label' => esc_html__('Image', 'king-addons'),
                'type' => Controls_Manager::MEDIA,
                'dynamic' => [
                    'active' => true,
                ],
                'condition' => [
                    'type_select' => 'heading',
                    'heading_icon_type' => 'image',
                ],
            ]
        );

        $repeater->add_control(
            'text',
            [
                'label' => '',
                'type' => Controls_Manager::TEXTAREA,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => 'Text Element',
                'condition' => [
                    'type_select' => 'text',
                ],
            ]
        );

        $repeater->add_control(
            'price',
            [
                'label' => esc_html__('Price', 'king-addons'),
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => '39',
                'condition' => [
                    'type_select' => 'price',
                ],
            ]
        );

        $repeater->add_control(
            'sub_price',
            [
                'label' => esc_html__('Sub Price', 'king-addons'),
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => '99',
                'condition' => [
                    'type_select' => 'price',
                ],
            ]
        );

        $repeater->add_control(
            'currency_symbol',
            [
                'label' => esc_html__('Currency Symbol', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '' => esc_html__('None', 'king-addons'),
                    'dollar' => '&#36; ' . _x('Dollar', 'Currency Symbol', 'king-addons'),
                    'euro' => '&#128; ' . _x('Euro', 'Currency Symbol', 'king-addons'),
                    'pound' => '&#163; ' . _x('Pound Sterling', 'Currency Symbol', 'king-addons'),
                    'ruble' => '&#8381; ' . _x('Ruble', 'Currency Symbol', 'king-addons'),
                    'peso' => '&#8369; ' . _x('Peso', 'Currency Symbol', 'king-addons'),
                    'krona' => 'kr ' . _x('Krona', 'Currency Symbol', 'king-addons'),
                    'lira' => '&#8356; ' . _x('Lira', 'Currency Symbol', 'king-addons'),
                    'franc' => '&#8355; ' . _x('Franc', 'Currency Symbol', 'king-addons'),
                    'baht' => '&#3647; ' . _x('Baht', 'Currency Symbol', 'king-addons'),
                    'shekel' => '&#8362; ' . _x('Shekel', 'Currency Symbol', 'king-addons'),
                    'won' => '&#8361; ' . _x('Won', 'Currency Symbol', 'king-addons'),
                    'yen' => '&#165; ' . _x('Yen/Yuan', 'Currency Symbol', 'king-addons'),
                    'guilder' => '&fnof; ' . _x('Guilder', 'Currency Symbol', 'king-addons'),
                    'peseta' => '&#8359 ' . _x('Peseta', 'Currency Symbol', 'king-addons'),
                    'real' => 'R$ ' . _x('Real', 'Currency Symbol', 'king-addons'),
                    'rupee' => '&#8360; ' . _x('Rupee', 'Currency Symbol', 'king-addons'),
                    'indian_rupee' => '&#8377; ' . _x('Rupee (Indian)', 'Currency Symbol', 'king-addons'),
                    'custom' => esc_html__('Custom', 'king-addons'),
                ],
                'default' => 'dollar',
                'condition' => [
                    'type_select' => 'price',
                ],
            ]
        );

        $repeater->add_control(
            'currency',
            [
                'label' => esc_html__('Currency', 'king-addons'),
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => '$',
                'condition' => [
                    'type_select' => 'price',
                    'currency_symbol' => 'custom',
                ],
            ]
        );

        $repeater->add_control(
            'sale',
            [
                'label' => esc_html__('Sale', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'condition' => [
                    'type_select' => 'price',
                ],
            ]
        );

        $repeater->add_control(
            'old_price',
            [
                'label' => esc_html__('Old Price', 'king-addons'),
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => '29',
                'condition' => [
                    'type_select' => 'price',
                    'sale' => 'yes',
                ],
            ]
        );

        $repeater->add_control(
            'period',
            [
                'label' => esc_html__('Period', 'king-addons'),
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => '/Year',
                'condition' => [
                    'type_select' => 'price',
                ],
            ]
        );

        $repeater->add_control(
            'feature_text',
            [
                'label' => esc_html__('Text', 'king-addons'),
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => 'Feature',
                'condition' => [
                    'type_select' => 'feature',
                ],
            ]
        );

        $repeater->add_control(
            'feature_icon_color',
            [
                'label' => esc_html__('Icon Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => 'rgba(84,89,95,1)',
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .king-addons-pricing-table-feature-icon' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'type_select' => 'feature',
                ],
            ]
        );

        $repeater->add_control(
            'btn_text',
            [
                'label' => esc_html__('Button Text', 'king-addons'),
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => 'Button',
                'condition' => [
                    'type_select' => 'button',
                ],
            ]
        );

        $repeater->add_control(
            'btn_id',
            [
                'label' => esc_html__('Button ID', 'king-addons'),
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'placeholder' => 'button-id',
                'condition' => [
                    'type_select' => 'button',
                ],
            ]
        );

        $repeater->add_control(
            'btn_url',
            [
                'type' => Controls_Manager::URL,
                'dynamic' => [
                    'active' => true,
                ],
                'placeholder' => esc_html__('https://example.com', 'king-addons'),
                'show_label' => false,
                'condition' => [
                    'type_select' => 'button',
                ],
            ]
        );

        $repeater->add_control(
            'select_icon',
            [
                'label' => esc_html__('Select Icon', 'king-addons'),
                'type' => Controls_Manager::ICONS,
                'skin' => 'inline',
                'label_block' => false,
                'conditions' => [
                    'relation' => 'or',
                    'terms' => [
                        [
                            'name' => 'type_select',
                            'operator' => '=',
                            'value' => 'feature',
                        ],
                        [
                            'name' => 'type_select',
                            'operator' => '=',
                            'value' => 'button',
                        ],
                        [
                            'relation' => 'and',
                            'terms' => [
                                [
                                    'name' => 'type_select',
                                    'operator' => '=',
                                    'value' => 'heading',
                                ],
                                [
                                    'name' => 'heading_icon_type',
                                    'operator' => '=',
                                    'value' => 'icon',
                                ],
                            ],
                        ],
                    ],
                ],
            ]
        );

        $repeater->add_control(
            'btn_icon_position',
            [
                'label' => esc_html__('Icon Position', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'after',
                'options' => [
                    'before' => esc_html__('Before', 'king-addons'),
                    'after' => esc_html__('After', 'king-addons'),
                ],
                'condition' => [
                    'type_select' => 'button',
                ],

            ]
        );

        $repeater->add_control(
            'feature_linethrough',
            [
                'label' => esc_html__('Line Through', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'condition' => [
                    'type_select' => 'feature',
                ],
            ]
        );

        $repeater->add_control(
            'feature_linethrough_text_color',
            [
                'label' => esc_html__('Text Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#222222',
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} span.king-addons-pricing-table-ftext-line-yes span' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'type_select' => 'feature',
                    'feature_linethrough' => 'yes',
                ],
            ]
        );

        $repeater->add_control(
            'feature_linethrough_color',
            [
                'label' => esc_html__('Line Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#222222',
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} span.king-addons-pricing-table-ftext-line-yes' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'type_select' => 'feature',
                    'feature_linethrough' => 'yes',
                ],
            ]
        );

        $repeater->add_control('feature_tooltip', $this->add_repeater_args_feature_tooltip());

        $repeater->add_control('feature_tooltip_show_icon', $this->add_repeater_args_feature_tooltip_show_icon());

        $repeater->add_control('feature_tooltip_text', $this->add_repeater_args_feature_tooltip_text());

        $repeater->add_control(
            'divider_style',
            [
                'label' => esc_html__('Style', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'solid' => esc_html__('Solid', 'king-addons'),
                    'double' => esc_html__('Double', 'king-addons'),
                    'dotted' => esc_html__('Dotted', 'king-addons'),
                    'dashed' => esc_html__('Dashed', 'king-addons'),
                    'groove' => esc_html__('Groove', 'king-addons'),
                ],
                'default' => 'dashed',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-pricing-table-divider' => 'border-top-style: {{VALUE}};',
                ],
                'condition' => [
                    'type_select' => 'divider',
                ],
            ]
        );

        $repeater->add_control(
            'divider_bg_color',
            [
                'label' => esc_html__('Background Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#f7f7f7',
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}' => 'background-color: {{VALUE}};',
                ],
                'condition' => [
                    'type_select' => 'divider',
                ],
            ]
        );

        $repeater->add_control(
            'divider_color',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#222222',
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .king-addons-pricing-table-divider' => 'border-top-color: {{VALUE}};',
                ],
                'condition' => [
                    'type_select' => 'divider',
                ],
            ]
        );

        $repeater->add_responsive_control(
            'divider_width',
            [
                'label' => esc_html__('Width', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 5,
                        'max' => 300,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 60,
                ],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .king-addons-pricing-table-divider' => 'width: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'type_select' => 'divider',
                ],
            ]
        );

        $repeater->add_responsive_control(
            'divider_height',
            [
                'label' => esc_html__('Height', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 10,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 1,
                ],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .king-addons-pricing-table-divider' => 'border-top-width: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'type_select' => 'divider',
                ],
            ]
        );

        $this->add_control(
            'pricing_items',
            [
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'type_select' => 'heading',
                        'select_icon' => ['value' => 'far fa-star', 'library' => 'fa-regular'],
                    ],
                    [
                        'type_select' => 'price',
                    ],
                    [
                        'type_select' => 'feature',
                        'feature_text' => 'Feature 1',
                        'feature_linethrough' => 'yes',
                        'feature_icon_color' => '#7a7a7a',
                        'feature_linethrough_text_color' => '#7a7a7a',
                        'feature_linethrough_color' => '#7a7a7a',
                        'select_icon' => ['value' => 'fas fa-check', 'library' => 'fa-solid'],
                    ],
                    [
                        'type_select' => 'feature',
                        'feature_text' => 'Feature 2',
                        'feature_icon_color' => 'rgba(84,89,95,1)',
                        'select_icon' => ['value' => 'fas fa-check', 'library' => 'fa-solid'],
                    ],
                    [
                        'type_select' => 'feature',
                        'feature_text' => 'Feature 3',
                        'feature_icon_color' => '#6DD400',
                        'select_icon' => ['value' => 'fas fa-check', 'library' => 'fa-solid'],
                    ],
                    [
                        'type_select' => 'feature',
                        'feature_text' => 'Feature 4',
                        'feature_icon_color' => '#6DD400',
                        'select_icon' => ['value' => 'fas fa-check', 'library' => 'fa-solid'],
                    ],
                    [
                        'type_select' => 'feature',
                        'feature_text' => 'Feature 5',
                        'feature_icon_color' => '#6DD400',
                        'select_icon' => ['value' => 'fas fa-check', 'library' => 'fa-solid'],
                    ],
                    [
                        'type_select' => 'button',
                        'select_icon' => '',
                    ],
                    [
                        'type_select' => 'text',
                    ],
                ],
                'title_field' => '<# if( "feature" === type_select ) { #> Feature - {{{ feature_text }}} <# } 
                else if( "heading" === type_select ) { #> Heading - {{{ heading_title }}} <# }
                else if( "price" === type_select ) { #> Price - {{{ price }}} <# }
                else if( "text" === type_select ) { #> Text - {{{ text }}} <# }
                else if( "button" === type_select ) { #> Button - {{{ btn_text }}} <# }
                else if( "divider" === type_select ) { #> Divider - {{{ divider_style }}} <# }
                else {#> {{{ type_select }}} <# } #>',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_badge',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Badge', 'king-addons'),
                'type' => Controls_Manager::SECTION,
            ]
        );

        $this->add_control(
            'badge_style',
            [
                'type' => Controls_Manager::SELECT,
                'label' => esc_html__('Style', 'king-addons'),
                'default' => 'corner',
                'options' => [
                    'none' => esc_html__('None', 'king-addons'),
                    'corner' => esc_html__('Corner', 'king-addons'),
                    'circle' => esc_html__('circle', 'king-addons'),
                    'flag' => esc_html__('Flag', 'king-addons'),
                ],
            ]
        );

        $this->add_control(
            'badge_title',
            [
                'label' => esc_html__(' Title', 'king-addons'),
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => 'Sale',
                'condition' => [
                    'badge_style!' => 'none',
                ],
            ]
        );

        $this->add_control(
            'badge_hr_position',
            [
                'label' => esc_html__('Horizontal Position', 'king-addons'),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'default' => 'right',
                'options' => [
                    'left' => [
                        'title' => esc_html__('Left', 'king-addons'),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'right' => [
                        'title' => esc_html__('Right', 'king-addons'),
                        'icon' => 'eicon-h-align-right',
                    ]
                ],
                'condition' => [
                    'badge_style!' => 'none',
                ],
            ]
        );

        $this->add_responsive_control(
            'badge_circle_size',
            [
                'label' => esc_html__('Size', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 60,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-pricing-table-badge-circle .king-addons-pricing-table-badge-inner' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'badge_style' => 'circle',
                    'badge_style!' => 'none',
                ],
            ]
        );

        $this->add_responsive_control(
            'badge_circle_top_distance',
            [
                'label' => esc_html__('Top Distance', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['%'],
                'range' => [
                    '%' => [
                        'min' => -50,
                        'max' => 50,
                    ],
                ],
                'default' => [
                    'unit' => '%',
                    'size' => 40,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-pricing-table-badge-circle' => 'transform: translateX({{badge_circle_side_distance.SIZE}}%) translateY({{SIZE}}%);',
                ],
                'condition' => [
                    'badge_style' => 'circle',
                    'badge_style!' => 'none',
                ],
            ]
        );

        $this->add_responsive_control(
            'badge_circle_side_distance',
            [
                'label' => esc_html__('Side Distance', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['%'],
                'range' => [
                    '%' => [
                        'min' => -50,
                        'max' => 50,
                    ],
                ],
                'default' => [
                    'unit' => '%',
                    'size' => 40,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-pricing-table-badge-circle' => 'transform: translateX({{SIZE}}%) translateY({{badge_circle_top_distance.SIZE}}%);',
                ],
                'condition' => [
                    'badge_style' => 'circle',
                    'badge_style!' => 'none',
                ],
            ]
        );

        $this->add_responsive_control(
            'badge_distance',
            [
                'label' => esc_html__('Distance', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 80,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 25,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-pricing-table-badge-corner .king-addons-pricing-table-badge-inner' => 'margin-top: {{SIZE}}{{UNIT}}; transform: translateY(-50%) translateX(-50%) translateX({{SIZE}}{{UNIT}}) rotate(-45deg);',
                    '{{WRAPPER}} .king-addons-pricing-table-badge-flag' => 'top: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'badge_style!' => ['none', 'circle'],
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'pr_table_section_hv_animation',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Hover Animation', 'king-addons'),
                'tab' => Controls_Manager::SECTION,
            ]
        );

        $this->add_control(
            'hv_animation',
            [
                'type' => Controls_Manager::SELECT,
                'label' => esc_html__('Effect', 'king-addons'),
                'default' => 'none',
                'options' => [
                    'none' => esc_html__('None', 'king-addons'),
                    'slide' => esc_html__('Slide', 'king-addons'),
                    'bounce' => esc_html__('Bounce', 'king-addons'),
                ],
                'prefix_class' => 'king-addons-pricing-table-animation-',
            ]
        );

        $this->add_control(
            'hv_animation_duration',
            [
                'label' => esc_html__('Animation Duration (ms)', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'default' => 200,
                'min' => 0,
                'step' => 50,
                'selectors' => [
                    '{{WRAPPER}}.king-addons-pricing-table-animation-slide' => 'transition-duration: {{VALUE}}ms;',
                    '{{WRAPPER}}.king-addons-pricing-table-animation-bounce' => 'animation-duration: {{VALUE}}ms;',
                    '{{WRAPPER}}.king-addons-pricing-table-animation-zoom' => 'transition-duration: {{VALUE}}ms;',
                ],

            ]
        );

        $this->end_controls_section();

        Core::renderProFeaturesSection($this, '', Controls_Manager::RAW_HTML, 'pricing-table', [
            'Tooltip Popup',
            'List Item Advanced Tooltip',
            'List Item Even/Odd Background Color',
            'Advanced Button Animations',
        ]);

        $this->start_controls_section(
            'pr_table_section_style_heading',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Heading', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'heading_bg_color',
                'types' => ['classic', 'gradient'],
                'fields_options' => [
                    'color' => [
                        'default' => '#f7f7f7',
                    ],
                    'background' => [
                        'default' => 'classic',
                    ]
                ],
                'selector' => '{{WRAPPER}} .king-addons-pricing-table-heading'
            ]
        );

        $this->add_responsive_control(
            'heading_section_padding',
            [
                'label' => esc_html__('Padding', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'default' => [
                    'top' => 25,
                    'right' => 20,
                    'bottom' => 25,
                    'left' => 20,
                ],
                'size_units' => ['px',],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-pricing-table-heading' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before'
            ]
        );

        $this->add_control(
            'title_section',
            [
                'label' => esc_html__('Title', 'king-addons'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'heading_title_color',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#2d2d2d',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-pricing-table-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'heading_title_typography',
                'selector' => '{{WRAPPER}} .king-addons-pricing-table-title',
            ]
        );

        $this->add_control(
            'heading_title_distance',
            [
                'label' => esc_html__('Distance', 'king-addons'),
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
                    'size' => 0,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-pricing-table-title' => 'margin: 0 0 {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'sub_title_section',
            [
                'label' => esc_html__('Sub Title', 'king-addons'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'heading_sub_title_color',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#919191',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-pricing-table-sub-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'heading_sub_title_typography',
                'selector' => '{{WRAPPER}} .king-addons-pricing-table-sub-title',
            ]
        );

        $this->add_control(
            'icon_section',
            [
                'label' => esc_html__('Icon / Image', 'king-addons'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'heading_icon_color',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#5B03FF',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-pricing-table-icon' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'heading_icon_position',
            [
                'label' => esc_html__('Icon Position', 'king-addons'),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
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
                    ]
                ],
                'prefix_class' => 'king-addons-pricing-table-heading-',
            ]
        );

        $this->add_responsive_control(
            'heading_icon_size',
            [
                'label' => esc_html__('Size', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 500,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 35,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-pricing-table-icon i' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .king-addons-pricing-table-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .king-addons-pricing-table-icon img' => 'max-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'heading_icon_distance',
            [
                'label' => esc_html__('Distance', 'king-addons'),
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
                    '{{WRAPPER}}.king-addons-pricing-table-heading-left .king-addons-pricing-table-icon' => 'margin-right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}}.king-addons-pricing-table-heading-center .king-addons-pricing-table-icon' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}}.king-addons-pricing-table-heading-right .king-addons-pricing-table-icon' => 'margin-left: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'pr_table_section_style_price',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Price', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'price_wrap_bg_color',
                'types' => ['classic', 'gradient'],
                'fields_options' => [
                    'color' => [
                        'default' => '#5B03FF',
                    ],
                    'background' => [
                        'default' => 'classic',
                    ]
                ],
                'selector' => '{{WRAPPER}} .king-addons-pricing-table-price'
            ]
        );

        $this->add_responsive_control(
            'price_wrap_padding',
            [
                'label' => esc_html__('Padding', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'default' => [
                    'top' => 40,
                    'right' => 20,
                    'bottom' => 30,
                    'left' => 20,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-pricing-table-price' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before'
            ]
        );

        $this->add_control(
            'price_section',
            [
                'label' => esc_html__('Price', 'king-addons'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'price_color',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-pricing-table-price' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'price_typography',
                'selector' => '{{WRAPPER}} .king-addons-pricing-table-price',
            ]
        );

        $this->add_control(
            'sub_price_section',
            [
                'label' => esc_html__('Sub Price', 'king-addons'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'sub_price_size',
            [
                'label' => esc_html__('Size', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 19,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-pricing-table-sub-price' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'sub_price_vr_position',
            [
                'label' => esc_html__('Vertical Position', 'king-addons'),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'options' => [
                    'top' => [
                        'title' => esc_html__('Top', 'king-addons'),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'middle' => [
                        'title' => esc_html__('Middle', 'king-addons'),
                        'icon' => 'eicon-v-align-middle',
                    ],
                    'bottom' => [
                        'title' => esc_html__('Bottom', 'king-addons'),
                        'icon' => 'eicon-v-align-bottom',
                    ],
                ],
                'default' => 'top',
                'selectors_dictionary' => [
                    'top' => 'flex-start',
                    'middle' => 'center',
                    'bottom' => 'flex-end',
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-pricing-table-sub-price' => '-webkit-align-self: {{VALUE}}; align-self: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'currency_section',
            [
                'label' => esc_html__('Currency Symbol', 'king-addons'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'currency_size',
            [
                'label' => esc_html__('Size', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 24,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-pricing-table-currency' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'currency_hr_position',
            [
                'label' => esc_html__('Alignment', 'king-addons'),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'default' => 'before',
                'options' => [
                    'before' => [
                        'title' => esc_html__('Before', 'king-addons'),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'after' => [
                        'title' => esc_html__('After', 'king-addons'),
                        'icon' => 'eicon-h-align-right',
                    ],
                ],
            ]
        );

        $this->add_control(
            'currency_vr_position',
            [
                'label' => esc_html__('Vertical Position', 'king-addons'),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'options' => [
                    'top' => [
                        'title' => esc_html__('Top', 'king-addons'),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'middle' => [
                        'title' => esc_html__('Middle', 'king-addons'),
                        'icon' => 'eicon-v-align-middle',
                    ],
                    'bottom' => [
                        'title' => esc_html__('Bottom', 'king-addons'),
                        'icon' => 'eicon-v-align-bottom',
                    ],
                ],
                'default' => 'top',
                'selectors_dictionary' => [
                    'top' => 'flex-start',
                    'middle' => 'center',
                    'bottom' => 'flex-end',
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-pricing-table-currency' => '-webkit-align-self: {{VALUE}}; align-self: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'old_price_section',
            [
                'label' => esc_html__('Old Price', 'king-addons'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'old_price_color',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-pricing-table-old-price' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'old_price_size',
            [
                'label' => esc_html__('Size', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 20,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-pricing-table-old-price' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'old_price_vr_position',
            [
                'label' => esc_html__('Vertical Position', 'king-addons'),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'options' => [
                    'top' => [
                        'title' => esc_html__('Top', 'king-addons'),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'middle' => [
                        'title' => esc_html__('Middle', 'king-addons'),
                        'icon' => 'eicon-v-align-middle',
                    ],
                    'bottom' => [
                        'title' => esc_html__('Bottom', 'king-addons'),
                        'icon' => 'eicon-v-align-bottom',
                    ],
                ],
                'default' => 'middle',
                'selectors_dictionary' => [
                    'top' => 'flex-start',
                    'middle' => 'center',
                    'bottom' => 'flex-end',
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-pricing-table-old-price' => '-webkit-align-self: {{VALUE}}; align-self: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'period_section',
            [
                'label' => esc_html__('Period', 'king-addons'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'period_color',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-pricing-table-period' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'period_typography',
                'selector' => '{{WRAPPER}} .king-addons-pricing-table-period',
            ]
        );

        $this->add_control(
            'period_hr_position',
            [
                'label' => esc_html__('Position', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'label_block' => false,
                'options' => [
                    'below' => esc_html__('Below', 'king-addons'),
                    'beside' => esc_html__('Beside', 'king-addons'),
                ],
                'default' => 'below',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'pr_table_section_style_features',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Features', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'feature_bg_color',
            [
                'label' => esc_html__('Background Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#f7f7f7',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-pricing-table section' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_controls_group_feature_even_bg();

        $this->add_responsive_control(
            'feature_section_padding',
            [
                'label' => esc_html__('Padding', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'default' => [
                    'top' => 15,
                    'right' => 15,
                    'bottom' => 15,
                    'left' => 15,
                ],
                'size_units' => ['px',],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-pricing-table-feature-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'feature_section_top_distance',
            [
                'label' => esc_html__('List Top Distance', 'king-addons'),
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
                    'size' => 0,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-pricing-table-feature:first-of-type' => 'padding-top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'feature_section_bot_distance',
            [
                'label' => esc_html__('List Bottom Distance', 'king-addons'),
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
                    'size' => 0,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-pricing-table-feature:last-of-type' => 'padding-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'feature_color',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#54595f',
                'separator' => 'before',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-pricing-table-feature span > span' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'feature_typography',
                'selector' => '{{WRAPPER}} .king-addons-pricing-table-feature',
            ]
        );

        $this->add_responsive_control(
            'feature_align',
            [
                'label' => esc_html__('Alignment', 'king-addons'),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'default' => 'center',
                'options' => [
                    'flex-start' => [
                        'title' => esc_html__('Left', 'king-addons'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'king-addons'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'flex-end' => [
                        'title' => esc_html__('Right', 'king-addons'),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'selectors_dictionary' => [
                    'flex-start' => 'justify-content: flex-start; text-align: left;',
                    'center' => 'justify-content: center; text-align: center;',
                    'flex-end' => 'justify-content: flex-end; text-align: right;',
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-pricing-table-feature-inner' => '{{VALUE}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'feature_width',
            [
                'label' => esc_html__('Width', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    '%' => [
                        'min' => 10,
                        'max' => 100,
                    ],
                    'px' => [
                        'min' => 100,
                        'max' => 1000,
                    ],
                ],
                'size_units' => ['%', 'px'],
                'default' => [
                    'unit' => 'px',
                    'size' => 357,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-pricing-table-feature-inner' => 'max-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'feature_icon_section',
            [
                'label' => esc_html__('Icon', 'king-addons'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'feature_icon_size',
            [
                'label' => esc_html__('Size', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 5,
                        'max' => 30,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 14,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-pricing-table-feature-icon' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control_stack_feature_tooltip_section();

        $this->add_control(
            'feature_divider',
            [
                'label' => esc_html__('Divider', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'label_off' => esc_html__('Off', 'king-addons'),
                'label_on' => esc_html__('On', 'king-addons'),
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'feature_divider_color',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#d6d6d6',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-pricing-table-feature:after' => 'border-bottom-color: {{VALUE}};',
                ],
                'condition' => [
                    'feature_divider' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'feature_divider_type',
            [
                'label' => esc_html__('Style', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'solid' => esc_html__('Solid', 'king-addons'),
                    'double' => esc_html__('Double', 'king-addons'),
                    'dotted' => esc_html__('Dotted', 'king-addons'),
                    'dashed' => esc_html__('Dashed', 'king-addons'),
                    'groove' => esc_html__('Groove', 'king-addons'),
                ],
                'default' => 'dashed',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-pricing-table-feature:after' => 'border-bottom-style: {{VALUE}};',
                ],
                'condition' => [
                    'feature_divider' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'feature_divider_weight',
            [
                'label' => esc_html__('Weight', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 5,
                    ],
                ],
                'default' => [
                    'size' => 1,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-pricing-table-feature:after' => 'border-bottom-width: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'feature_divider' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'feature_divider_width',
            [
                'label' => esc_html__('Width', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['%', 'px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                    ],
                    '%' => [
                        'min' => 1,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => '%',
                    'size' => 45,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-pricing-table-feature:after' => 'max-width: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'feature_divider' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'pr_table_section_style_btn',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Button', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'btn_section_bg_color',
                'types' => ['classic', 'gradient'],
                'fields_options' => [
                    'color' => [
                        'default' => '#f7f7f7',
                    ],
                    'background' => [
                        'default' => 'classic',
                    ]
                ],
                'selector' => '{{WRAPPER}} .king-addons-pricing-table-button'
            ]
        );

        $this->add_control(
            'btn_section_padding',
            [
                'label' => esc_html__('Section Padding', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'default' => [
                    'top' => 30,
                    'right' => 0,
                    'bottom' => 10,
                    'left' => 0,
                ],
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-pricing-table-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'btn_section_padding_divider',
            [
                'type' => Controls_Manager::DIVIDER,
                'style' => 'thick',
            ]
        );

        $this->start_controls_tabs('tabs_btn_style');

        $this->start_controls_tab(
            'tab_btn_normal',
            [
                'label' => esc_html__('Normal', 'king-addons'),
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'btn_bg_color',
                'types' => ['classic', 'gradient'],
                'fields_options' => [
                    'color' => [
                        'default' => '#000000',
                    ],
                    'background' => [
                        'default' => 'classic',
                    ]
                ],
                'selector' => '{{WRAPPER}} .king-addons-pricing-table-btn'
            ]
        );

        $this->add_control(
            'btn_color',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-pricing-table-btn' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'btn_border_color',
            [
                'label' => esc_html__('Border Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-pricing-table-btn' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'btn_box_shadow',
                'selector' => '{{WRAPPER}} .king-addons-pricing-table-btn',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_btn_hover',
            [
                'label' => esc_html__('Hover', 'king-addons'),
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'btn_hover_bg_color',
                'types' => ['classic', 'gradient'],
                'fields_options' => [
                    'color' => [
                        'default' => '#5b03ff',
                    ],
                    'background' => [
                        'default' => 'classic',
                    ]
                ],
                'selector' => '{{WRAPPER}} .king-addons-pricing-table-btn.king-addons-button-none:hover, {{WRAPPER}} .king-addons-pricing-table-btn:before, {{WRAPPER}} .king-addons-pricing-table-btn:after'
            ]
        );

        $this->add_control(
            'btn_hover_color',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-pricing-table-btn:hover' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'btn_hover_border_color',
            [
                'label' => esc_html__('Border Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-pricing-table-btn:hover' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'btn_hover_box_shadow',
                'selector' => '{{WRAPPER}} .king-addons-pricing-table-btn:hover',
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_control(
            'btn_section_anim_divider',
            [
                'type' => Controls_Manager::DIVIDER,
                'style' => 'thick',
            ]
        );

        $this->add_control(
            'btn_animation',
            [
                'label' => esc_html__('Select Animation', 'king-addons'),
                'type' => 'king-addons-button-animations',
                'default' => 'king-addons-button-sweep-to-top',
            ]
        );

        $this->add_control(
            'btn_transition_duration',
            [
                'label' => esc_html__('Transition Duration (ms)', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'default' => 300,
                'min' => 0,
                'step' => 50,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-pricing-table-btn' => 'transition-duration: {{VALUE}}ms',
                    '{{WRAPPER}} .king-addons-pricing-table-btn:before' => 'transition-duration: {{VALUE}}ms',
                    '{{WRAPPER}} .king-addons-pricing-table-btn:after' => 'transition-duration: {{VALUE}}ms',
                ],
            ]
        );

        $this->add_control(
            'btn_animation_height',
            [
                'label' => esc_html__('Animation Height', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                    'px' => [
                        'min' => 0,
                        'max' => 10,
                    ],
                ],
                'size_units' => ['px'],
                'default' => [
                    'unit' => 'px',
                    'size' => 3,
                ],
                'selectors' => [
                    '{{WRAPPER}} [class*="king-addons-button-underline"]:before' => 'height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} [class*="king-addons-button-overline"]:before' => 'height: {{SIZE}}{{UNIT}};',
                ],
                'render_type' => 'template',
                'condition' => [
                    'btn_animation' => [
                        'king-addons-button-underline-from-left',
                        'king-addons-button-underline-from-center',
                        'king-addons-button-underline-from-right',
                        'king-addons-button-underline-reveal',
                        'king-addons-button-overline-reveal',
                        'king-addons-button-overline-from-left',
                        'king-addons-button-overline-from-center',
                        'king-addons-button-overline-from-right'
                    ]
                ],
            ]
        );

        if (!king_addons_freemius()->can_use_premium_code__premium_only()) {
            $this->add_control(
                'team_member_pro_notice_2',
                [
                    'type' => Controls_Manager::RAW_HTML,
                    'raw' => 'Advanced button animations are available in the <strong><a href="https://kingaddons.com/pricing/?utm_source=kng-module-pricing-table-settings-upgrade-pro&utm_medium=plugin&utm_campaign=kng" target="_blank">Pro version</a></strong>',
                    'content_classes' => 'king-addons-pro-notice',
                ]
            );
        }

        $this->add_control(
            'btn_typography_divider',
            [
                'type' => Controls_Manager::DIVIDER,
                'style' => 'thick',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'btn_typography',
                'selector' => '{{WRAPPER}} .king-addons-pricing-table-btn',
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'btn_padding',
            [
                'label' => esc_html__('Padding', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'default' => [
                    'top' => 10,
                    'right' => 40,
                    'bottom' => 10,
                    'left' => 40,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-pricing-table-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'btn_border_type',
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
                'default' => 'none',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-pricing-table-btn' => 'border-style: {{VALUE}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'btn_border_width',
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
                    '{{WRAPPER}} .king-addons-pricing-table-btn' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'btn_border_type!' => 'none',
                ],
            ]
        );

        $this->add_responsive_control(
            'btn_border_radius',
            [
                'label' => esc_html__('Border Radius', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'default' => [
                    'top' => 2,
                    'right' => 2,
                    'bottom' => 2,
                    'left' => 2,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-pricing-table-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'pr_table_section_style_text',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Text', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'text_section_bg_color',
                'types' => ['classic', 'gradient'],
                'fields_options' => [
                    'color' => [
                        'default' => '#f7f7f7',
                    ],
                    'background' => [
                        'default' => 'classic',
                    ]
                ],
                'selector' => '{{WRAPPER}} .king-addons-pricing-table-text'
            ]
        );

        $this->add_control(
            'text_section_padding',
            [
                'label' => esc_html__('Padding', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'default' => [
                    'top' => 5,
                    'right' => 70,
                    'bottom' => 30,
                    'left' => 70,
                ],
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-pricing-table-text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before'
            ]
        );

        $this->add_control(
            'text_color',
            [
                'type' => Controls_Manager::COLOR,
                'label' => esc_html__('Color', 'king-addons'),
                'default' => '#a5a5a5',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-pricing-table-text' => 'color: {{VALUE}};',
                ],
                'separator' => 'before'
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'text_typography',
                'label' => esc_html__('Typography', 'king-addons'),
                'selector' => '{{WRAPPER}} .king-addons-pricing-table-text'
            ]
        );

        $this->add_control(
            'text_align',
            [
                'label' => esc_html__('Alignment', 'king-addons'),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'default' => 'center',
                'options' => [
                    'left' => [
                        'title' => esc_html__('Left', 'king-addons'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'king-addons'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__('Right', 'king-addons'),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-pricing-table-text' => 'text-align: {{VALUE}};',
                ],
            ]
        );


        $this->end_controls_section();

        $this->start_controls_section(
            'pr_table_section_style_badge',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Badge', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'badge_text_color',
            [
                'type' => Controls_Manager::COLOR,
                'label' => esc_html__('Color', 'king-addons'),
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-pricing-table-badge-inner' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'badge_bg_color',
            [
                'type' => Controls_Manager::COLOR,
                'label' => esc_html__('Background Color', 'king-addons'),
                'default' => '#e83d17',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-pricing-table-badge-inner' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .king-addons-pricing-table-badge-flag:before' => ' border-top-color: {{VALUE}};',
                ],
                'separator' => 'after',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'badge_typography',
                'label' => esc_html__('Typography', 'king-addons'),
                'selector' => '{{WRAPPER}} .king-addons-pricing-table-badge-inner'
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'badge_box_shadow',
                'selector' => '{{WRAPPER}} .king-addons-pricing-table-badge-inner'
            ]
        );

        $this->add_responsive_control(
            'badge_padding',
            [
                'label' => esc_html__('Padding', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'default' => [
                    'top' => 0,
                    'right' => 10,
                    'bottom' => 0,
                    'left' => 10,
                ],
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-pricing-table-badge .king-addons-pricing-table-badge-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_wrapper',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Wrapper', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->start_controls_tabs('tabs_wrapper_style');

        $this->start_controls_tab(
            'tab_wrapper_normal',
            [
                'label' => esc_html__('Normal', 'king-addons'),
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'wrapper_bg_color',
                'types' => ['classic', 'gradient'],
                'fields_options' => [
                    'color' => [
                        'default' => '#f7f7f7',
                    ],
                    'background' => [
                        'default' => 'classic',
                    ]
                ],
                'selector' => '{{WRAPPER}} .king-addons-pricing-table'
            ]
        );

        $this->add_control(
            'wrapper_border_color',
            [
                'label' => esc_html__('Border Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#E8E8E8',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-pricing-table' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'wrapper_box_shadow',
                'selector' => '{{WRAPPER}} .king-addons-pricing-table',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_wrapper_hover',
            [
                'label' => esc_html__('Hover', 'king-addons'),
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'wrapper_bg_hover_color',
                'types' => ['classic', 'gradient'],
                'fields_options' => [
                    'color' => [
                        'default' => '#f7f7f7',
                    ],
                    'background' => [
                        'default' => 'classic',
                    ]
                ],
                'selector' => '{{WRAPPER}} .king-addons-pricing-table:hover'
            ]
        );

        $this->add_control(
            'wrapper_hover_border_color',
            [
                'label' => esc_html__('Border Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#E8E8E8',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-pricing-table:hover' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'wrapper_hover_box_shadow',
                'selector' => '{{WRAPPER}} .king-addons-pricing-table:hover',
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_control(
            'wrapper_transition_duration',
            [
                'label' => esc_html__('Transition Duration', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'default' => 0.1,
                'min' => 0,
                'max' => 5,
                'step' => 0.1,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-pricing-table' => 'transition-duration: {{VALUE}}s',
                ],
                'separator' => 'before'
            ]
        );

        $this->add_control(
            'wrapper_padding',
            [
                'label' => esc_html__('Padding', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'default' => [
                    'top' => 0,
                    'right' => 0,
                    'bottom' => 0,
                    'left' => 0,
                ],
                'size_units' => ['px',],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-pricing-table' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'wrapper_border_type',
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
                'default' => 'none',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-pricing-table' => 'border-style: {{VALUE}};',
                ],
                'separator' => 'before'
            ]
        );

        $this->add_responsive_control(
            'wrapper_border_width',
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
                    '{{WRAPPER}} .king-addons-pricing-table' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'wrapper_border_type!' => 'none',
                ],
            ]
        );

        $this->add_responsive_control(
            'wrapper_border_radius',
            [
                'label' => esc_html__('Border Radius', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'default' => [
                    'top' => 0,
                    'right' => 0,
                    'bottom' => 0,
                    'left' => 0,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-pricing-table' => 'border-radius: calc({{SIZE}}{{UNIT}} + 2px);',
                    '{{WRAPPER}} .king-addons-pricing-table-item-first' => 'border-top-left-radius: {{SIZE}}{{UNIT}}; border-top-right-radius: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .king-addons-pricing-table-item-last' => 'border-bottom-left-radius: {{SIZE}}{{UNIT}}; border-bottom-right-radius: {{SIZE}}{{UNIT}};',
                ],
                'separator' => 'before'
            ]
        );

        $this->end_controls_section();

    }

    private function get_currency_symbol($symbol_name): string
    {
        $symbols = [
            'dollar' => '&#36;',
            'euro' => '&#128;',
            'pound' => '&#163;',
            'ruble' => '&#8381;',
            'peso' => '&#8369;',
            'krona' => 'kr',
            'lira' => '&#8356;',
            'franc' => '&#8355;',
            'shekel' => '&#8362;',
            'baht' => '&#3647;',
            'won' => '&#8361;',
            'yen' => '&#165;',
            'guilder' => '&fnof;',
            'peseta' => '&#8359',
            'real' => 'R$',
            'rupee' => '&#8360;',
            'indian_rupee' => '&#8377;',
        ];
        return $symbols[$symbol_name] ?? '';
    }

    protected function render()
    {
        $settings = $this->get_settings();
        if (empty($settings['pricing_items'])) {
            return;
        }
        ?>
        <div class="king-addons-pricing-table">
            <?php
            $item_count = 0;
            foreach ($settings['pricing_items'] as $key => $item) :
                // Disable premium tooltip if Freemius is not active
                if (!king_addons_freemius()->can_use_premium_code__premium_only()) {
                    $item['feature_tooltip'] = '';
                    $item['feature_tooltip_text'] = '';
                }

                // Figure out item’s “first” or “last” CSS class
                $item_class = '';
                if (0 === $key) {
                    $item_class = ' king-addons-pricing-table-item-first';
                } elseif ($key === count($settings['pricing_items']) - 1) {
                    $item_class = ' king-addons-pricing-table-item-last';
                }

                $full_css_class = 'elementor-repeater-item-' . esc_attr($item['_id']) .
                    ' king-addons-pricing-table-item king-addons-pricing-table-' .
                    esc_attr($item['type_select'] . $item_class);

                // Render FEATURE items separately (since they have different HTML structure)
                if ($item['type_select'] === 'feature') : ?>
                    <section class="<?php echo $full_css_class; ?>">
                        <div class="king-addons-pricing-table-feature-inner">
                            <?php if (!empty($item['select_icon']['value'])) : ?>
                                <i class="king-addons-pricing-table-feature-icon <?php echo esc_attr($item['select_icon']['value']); ?>"></i>
                            <?php endif; ?>
                            <span class="king-addons-pricing-table-feature-text king-addons-pricing-table-ftext-line-<?php echo esc_attr($item['feature_linethrough']); ?>">
                            <span>
                                <?php
                                // Feature text
                                echo wp_kses_post($item['feature_text']);
                                // Optional tooltip icon
                                if ('yes' === $item['feature_tooltip'] && 'yes' === $item['feature_tooltip_show_icon']) {
                                    echo '&nbsp;&nbsp;<i class="far fa-question-circle"></i>';
                                }
                                ?>
                            </span>
                        </span>
                            <?php
                            // Actual tooltip text box
                            if ('yes' === $item['feature_tooltip'] && !empty($item['feature_tooltip_text'])) : ?>
                                <div class="king-addons-pricing-table-feature-tooltip">
                                    <?php echo wp_kses_post($item['feature_tooltip_text']); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </section>
                <?php
                else :
                    // Otherwise handle (heading, price, text, button, divider) in one block:
                    ?>
                    <div class="<?php echo esc_attr($full_css_class); ?>">
                        <?php
                        switch ($item['type_select']) {
                            case 'heading': // -----------------------------------------
                                ?>
                                <div class="king-addons-pricing-table-heading-inner">
                                    <?php if ($item['heading_icon_type'] === 'icon' && !empty($item['select_icon']['value'])) : ?>
                                        <div class="king-addons-pricing-table-icon">
                                            <i class="<?php echo esc_attr($item['select_icon']['value']); ?>"></i>
                                        </div>
                                    <?php elseif ($item['heading_icon_type'] === 'image' && !empty($item['heading_image']['url'])) : ?>
                                        <div class="king-addons-pricing-table-icon">
                                            <img src="<?php echo esc_attr($item['heading_image']['url']); ?>"
                                                 alt="<?php echo esc_attr($item['heading_image']['alt'] ?? ''); ?>">
                                        </div>
                                    <?php endif; ?>

                                    <?php if (!empty($item['heading_title']) || !empty($item['heading_sub_title'])) : ?>
                                        <div class="king-addons-pricing-table-title-wrap">
                                            <?php if (!empty($item['heading_title'])) : ?>
                                                <h3 class="king-addons-pricing-table-title">
                                                    <?php echo wp_kses_post($item['heading_title']); ?>
                                                </h3>
                                            <?php endif; ?>
                                            <?php if (!empty($item['heading_sub_title'])) : ?>
                                                <span class="king-addons-pricing-table-sub-title">
                                                <?php echo wp_kses_post($item['heading_sub_title']); ?>
                                            </span>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <?php
                                break;

                            case 'price': // -------------------------------------------
                                ?>
                                <div class="king-addons-pricing-table-price-inner">
                                    <?php if ('yes' === $item['sale'] && !empty($item['old_price'])) : ?>
                                        <span class="king-addons-pricing-table-old-price">
                                        <?php echo esc_html($item['old_price']); ?>
                                    </span>
                                    <?php endif; ?>

                                    <?php
                                    // Currency "before"
                                    if ('none' !== $item['currency_symbol'] && 'custom' !== $item['currency_symbol'] && 'before' === $settings['currency_hr_position']) {
                                        echo '<span class="king-addons-pricing-table-currency">' . esc_html($this->get_currency_symbol($item['currency_symbol'])) . '</span>';
                                    } elseif ('custom' === $item['currency_symbol'] && !empty($item['currency']) && 'before' === $settings['currency_hr_position']) {
                                        echo '<span class="king-addons-pricing-table-currency">' . esc_html($item['currency']) . '</span>';
                                    }

                                    // Main price and sub-price
                                    if (!empty($item['price'])) {
                                        echo '<span class="king-addons-pricing-table-main-price">' . esc_html($item['price']) . '</span>';
                                    }
                                    if (!empty($item['sub_price'])) {
                                        echo '<span class="king-addons-pricing-table-sub-price">' . esc_html($item['sub_price']) . '</span>';
                                    }

                                    // Currency "after"
                                    if ('none' !== $item['currency_symbol'] && 'custom' !== $item['currency_symbol'] && 'after' === $settings['currency_hr_position']) {
                                        echo '<span class="king-addons-pricing-table-currency">' . esc_html($this->get_currency_symbol($item['currency_symbol'])) . '</span>';
                                    } elseif ('custom' === $item['currency_symbol'] && !empty($item['currency']) && 'after' === $settings['currency_hr_position']) {
                                        echo '<span class="king-addons-pricing-table-currency">' . esc_html($item['currency']) . '</span>';
                                    }

                                    // Period “beside”
                                    if (!empty($item['period']) && 'beside' === $settings['period_hr_position']) {
                                        echo '<div class="king-addons-pricing-table-period">' . esc_html($item['period']) . '</div>';
                                    }
                                    ?>
                                </div>
                                <?php
                                // Period “below”
                                if (!empty($item['period']) && 'below' === $settings['period_hr_position']) {
                                    echo '<div class="king-addons-pricing-table-period">' . esc_html($item['period']) . '</div>';
                                }
                                break;

                            case 'text': // --------------------------------------------
                                if (!empty($item['text'])) {
                                    echo wp_kses_post($item['text']);
                                }
                                break;

                            case 'button': // ------------------------------------------
                                if (!empty($item['btn_text']) || !empty($item['select_icon']['value'])) {
                                    // Build link attributes
                                    $attr_name = 'btn_attribute' . $item_count;
                                    if (!empty($item['btn_url']['url'])) {
                                        $this->add_render_attribute($attr_name, 'href', esc_url($item['btn_url']['url']));

                                        if ($item['btn_url']['is_external']) {
                                            $this->add_render_attribute($attr_name, 'target', '_blank');
                                            $this->add_render_attribute($attr_name, 'rel', 'noopener noreferrer');
                                        }
                                        if ($item['btn_url']['nofollow']) {
                                            $this->add_render_attribute($attr_name, 'rel', 'nofollow', true);
                                        }
                                    }
                                    if (!empty($item['btn_id'])) {
                                        $this->add_render_attribute($attr_name, 'id', esc_html($item['btn_id']));
                                    }
                                    ?>
                                    <a class="king-addons-pricing-table-btn king-addons-button-effect <?php echo esc_html($this->get_settings()['btn_animation']); ?>"
                                        <?php echo $this->get_render_attribute_string($attr_name); ?>>
                                    <span>
                                        <?php
                                        if (!empty($item['select_icon']['value']) && $item['btn_icon_position'] === 'before') {
                                            echo '<i class="' . esc_attr($item['select_icon']['value']) . '"></i>';
                                        }
                                        echo esc_html($item['btn_text']);
                                        if (!empty($item['select_icon']['value']) && $item['btn_icon_position'] === 'after') {
                                            echo '<i class="' . esc_attr($item['select_icon']['value']) . '"></i>';
                                        }
                                        ?>
                                    </span>
                                    </a>
                                    <?php
                                }
                                break;

                            case 'divider': // -----------------------------------------
                                echo '<div class="king-addons-pricing-table-divider"></div>';
                                break;
                        } // end switch
                        ?>
                    </div>
                <?php
                endif; // End if feature / else
                $item_count++;
            endforeach;

            // Badge
            if ($settings['badge_style'] !== 'none' && !empty($settings['badge_title'])) :
                $this->add_render_attribute(
                    'king-addons-pricing-table-badge-attr',
                    'class',
                    'king-addons-pricing-table-badge king-addons-pricing-table-badge-' . esc_attr($settings['badge_style'])
                );
                if (!empty($settings['badge_hr_position'])) {
                    $this->add_render_attribute(
                        'king-addons-pricing-table-badge-attr',
                        'class',
                        'king-addons-pricing-table-badge-' . esc_attr($settings['badge_hr_position'])
                    );
                }
                ?>
                <div <?php echo $this->get_render_attribute_string('king-addons-pricing-table-badge-attr'); ?>>
                    <div class="king-addons-pricing-table-badge-inner">
                        <?php echo esc_html($settings['badge_title']); ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        <?php
    }

}