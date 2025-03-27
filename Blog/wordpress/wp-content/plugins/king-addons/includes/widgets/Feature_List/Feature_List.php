<?php /** @noinspection PhpUnused */

namespace King_Addons;

use Elementor\Icons_Manager;
use Elementor\Repeater;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Image_Size;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class Feature_List extends Widget_Base
{

    public function get_name(): string
    {
        return 'king-addons-feature-list';
    }

    public function get_title(): string
    {
        return esc_html__('Feature List', 'king-addons');
    }

    public function get_icon(): string
    {
        return 'king-addons-icon king-addons-feature-list';
    }

    public function get_script_depends(): array
    {
        return [KING_ADDONS_ASSETS_UNIQUE_KEY . '-feature-list-script'];
    }

    public function get_style_depends(): array
    {
        return [KING_ADDONS_ASSETS_UNIQUE_KEY . '-feature-list-style'];
    }

    public function get_categories(): array
    {
        return ['king-addons'];
    }

    public function get_keywords(): array
    {
        return ['rows', 'kingaddons', 'king-addons', 'king', 'addons', 'schedule', 'service', 'services', 'list',
            'title', 'titles', 'description', 'row', 'column', 'prices', 'columns', 'subtitles', 'menu', 'sub', 'order',
            'sorting', 'rate', 'sheet', 'catalog', 'guide', 'sort', 'schedule', 'feature', 'features', 'options',
            'option', 'fare', 'meal', 'selection', 'board', 'review', 'icon', 'icons', 'feature list', 'icon list',
            'features list', 'icons list', 'headings', 'text'
        ];
    }

    public function get_custom_help_url(): string
    {
        return 'https://kingaddons.com/elementor/feature-list/';
    }

    protected function register_controls()
    {
        $this->start_controls_section(
            'king_addons_section_feature_list_general',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('General', 'king-addons'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_responsive_control(
            'king_addons_feature_list_layout',
            [
                'label' => esc_html__('Layout', 'king-addons'),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'default' => 'left',
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
                'prefix_class' => 'king-addons-feature-list-',
                'render_type' => 'template',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-feature-list-item' => 'justify-content: {{VALUE}}',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'king_addons_feature_list_icon_vertical_align',
            [
                'label' => esc_html__('Vertical Align', 'king-addons'),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'default' => 'center',
                'options' => [
                    'flex-start' => [
                        'title' => esc_html__('Top', 'king-addons'),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'center' => [
                        'title' => esc_html__('Middle', 'king-addons'),
                        'icon' => 'eicon-v-align-middle',
                    ],
                    'flex-end' => [
                        'title' => esc_html__('Bottom', 'king-addons'),
                        'icon' => 'eicon-v-align-bottom',
                    ]
                ],
                'render_type' => 'template',
                'selectors' => [
                    '{{WRAPPER}}.king-addons-feature-list-left .king-addons-feature-list-item' => 'align-items: {{VALUE}}',
                    '{{WRAPPER}}.king-addons-feature-list-right .king-addons-feature-list-item' => 'align-items: {{VALUE}}'
                ],
                'condition' => [
                    'king_addons_feature_list_layout!' => 'center',
                ]
            ]
        );

        $this->add_control(
            'king_addons_feature_list_content_alignment',
            [
                'label' => esc_html__('Alignment', 'king-addons'),
                'type' => Controls_Manager::CHOOSE,
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
                    ]
                ],
                'prefix_class' => 'king-addons-feature-list-align-',
                'render_type' => 'template',
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-feature-list-item' => 'align-items: {{VALUE}};',
                ],
                'condition' => [
                    'king_addons_feature_list_layout' => 'center',
                ]
            ]
        );

        $this->add_control(
            'king_addons_feature_list_icon_shape',
            [
                'label' => esc_html__('Icon Shape', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'square',
                'label_block' => false,
                'options' => [
                    'square' => esc_html__('Square', 'king-addons'),
                    'rhombus' => esc_html__('Rhombus', 'king-addons')
                ],
                'separator' => 'before',
                'prefix_class' => 'king-addons-feature-list-'
            ]
        );

        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name' => 'king_addons_feature_list_thumbnail',
                'exclude' => ['custom'],
                'include' => [],
                'default' => 'large',
            ]
        );

        $this->add_control(
            'king_addons_feature_list_title_tag',
            [
                'label' => esc_html__('Title HTML Tag', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'h1' => 'H1',
                    'h2' => 'H2',
                    'h3' => 'H3',
                    'h4' => 'H4',
                    'h5' => 'H5',
                    'h6' => 'H6',
                    'div' => 'div',
                    'span' => 'span',
                    'P' => 'p'
                ],
                'default' => 'h2'
            ]
        );

        $this->add_control(
            'king_addons_feature_list_line',
            [
                'label' => esc_html__('Show Line', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'render_type' => 'template',
                'prefix_class' => 'king-addons-feature-list-line-',
                'separator' => 'before',
                'default' => 'yes',
                'condition' => [
                    'king_addons_feature_list_layout' => ['left', 'right']
                ]
            ]
        );

        $this->add_responsive_control(
            'king_addons_feature_list_item_spacing_v',
            [
                'label' => esc_html__('Vertical Spacing', 'king-addons'),
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
                    'size' => 35,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-feature-list-item:not(:last-child)' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
                'separator' => 'before'
            ]
        );

        $this->add_responsive_control(
            'king_addons_feature_list_item_spacing_h',
            [
                'label' => esc_html__('Horizontal Spacing', 'king-addons'),
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
                    '{{WRAPPER}}.king-addons-feature-list-left .king-addons-feature-list-icon-wrap' => 'margin-right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}}.king-addons-feature-list-right .king-addons-feature-list-icon-wrap' => 'margin-left: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'king_addons_feature_list_layout!' => 'center'
                ]
            ]
        );

        $this->add_responsive_control(
            'king_addons_feature_list_item_title_distance',
            [
                'label' => esc_html__('Title Distance', 'king-addons'),
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
                    'size' => 5,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-feature-list-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'king_addons_feature_list_item_media_distance',
            [
                'label' => esc_html__('Media Distance', 'king-addons'),
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
                    'size' => 0,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-feature-list-icon-wrap' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'king_addons_feature_list_layout' => 'center'
                ]
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'king_addons_feature_list_content_section',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Content', 'king-addons'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new Repeater();

        $repeater->start_controls_tabs(
            'king_addons_feature_list_tabs'
        );

        $repeater->start_controls_tab(
            'king_addons_feature_list_content_tab',
            [
                'label' => esc_html__('Content', 'king-addons'),
            ]
        );

        $repeater->add_control(
            'king_addons_feature_list_media_type',
            [
                'label' => esc_html__('Media Type', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'icon' => esc_html__('Icon', 'king-addons'),
                    'image' => esc_html__('Image', 'king-addons')
                ],
                'default' => 'icon',
                'label_block' => false,
            ]
        );

        $repeater->add_control(
            'king_addons_feature_list_icon',
            [
                'label' => esc_html__('Select Icon', 'king-addons'),
                'type' => Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-star',
                    'library' => 'solid',
                ],
                'label_block' => false,
                'skin' => 'inline',
                'condition' => [
                    'king_addons_feature_list_media_type' => 'icon'
                ]
            ]
        );

        $repeater->add_control(
            'king_addons_feature_list_image',
            [
                'label' => esc_html__('Choose Image', 'king-addons'),
                'type' => Controls_Manager::MEDIA,
                'skin' => 'inline',
                'dynamic' => [
                    'active' => true,
                ],
                'condition' => [
                    'king_addons_feature_list_media_type' => 'image'
                ]
            ]
        );

        $repeater->add_control(
            'king_addons_feature_list_list_title', [
                'label' => esc_html__('Title', 'king-addons'),
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => esc_html__('Title', 'king-addons'),
                'separator' => 'before',
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'king_addons_feature_list_list_title_url',
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
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'king_addons_feature_list_description',
            [
                'label' => esc_html__('Description', 'king-addons'),
                'type' => Controls_Manager::TEXTAREA,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => esc_html__('Description', 'king-addons'),
                'placeholder' => esc_html__('Type the description here', 'king-addons'),
                'rows' => 10,
            ]
        );

        $repeater->end_controls_tab();

        $repeater->start_controls_tab(
            'king_addons_feature_list_styles_tab',
            [
                'label' => esc_html__('Style', 'king-addons'),
            ]
        );

        $repeater->add_control(
            'king_addons_feature_list_custom_styles',
            [
                'label' => esc_html__('Custom Styles', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
            ]
        );

        $repeater->add_control(
            'king_addons_feature_list_title_color_unique',
            [
                'label' => esc_html__('Title Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .king-addons-feature-list-title a.king-addons-feature-list-url' => 'color: {{VALUE}}',
                    '{{WRAPPER}} {{CURRENT_ITEM}} .king-addons-feature-list-title' => 'color: {{VALUE}}'
                ],
                'condition' => [
                    'king_addons_feature_list_custom_styles' => 'yes'
                ]
            ]
        );

        $repeater->add_control(
            'king_addons_feature_list_icon_color_unique',
            [
                'label' => esc_html__('Icon Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .king-addons-feature-list-icon-inner-wrap i' => 'color: {{VALUE}}',
                    '{{WRAPPER}} {{CURRENT_ITEM}} .king-addons-feature-list-icon-inner-wrap svg' => 'fill: {{VALUE}}',
                ],
                'condition' => [
                    'king_addons_feature_list_custom_styles' => 'yes'
                ]
            ]
        );

        $repeater->add_control(
            'king_addons_feature_list_icon_wrapper_bg_color_unique',
            [
                'label' => esc_html__('Icon Bg Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#9B62FF',
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .king-addons-feature-list-icon-inner-wrap' => 'background-color: {{VALUE}}',
                ],
                'condition' => [
                    'king_addons_feature_list_custom_styles' => 'yes'
                ]
            ]
        );

        $repeater->add_control(
            'king_addons_feature_list_icon_wrapper_border_color_unique',
            [
                'label' => esc_html__('Icon Border Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#5B03FF',
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .king-addons-feature-list-icon-inner-wrap' => 'border-color: {{VALUE}}',
                ],
                'condition' => [
                    'king_addons_feature_list_custom_styles' => 'yes'
                ]
            ]
        );

        $repeater->end_controls_tab();

        $repeater->end_controls_tabs();

        $this->add_control(
            'king_addons_feature_repeater_list',
            [
                'label' => esc_html__('Repeater List', 'king-addons'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'king_addons_feature_list_list_title' => esc_html__('Easy Integration', 'king-addons'),
                        'king_addons_feature_list_description' => esc_html__('Seamlessly integrate with various tools and platforms, enhancing functionality without complexity.', 'king-addons'),
                        'king_addons_feature_list_icon' => [
                            'value' => 'fas fa-plug',
                            'library' => 'solid'
                        ],
                    ],
                    [
                        'king_addons_feature_list_list_title' => esc_html__('Versatile Layout Options', 'king-addons'),
                        'king_addons_feature_list_description' => esc_html__('Choose from a variety of layout designs and icon shapes to suit your unique style.', 'king-addons'),
                        'king_addons_feature_list_icon' => [
                            'value' => 'fas fa-th-large',
                            'library' => 'solid'
                        ],
                        'king_addons_feature_list_custom_styles' => 'yes',
                        'king_addons_feature_list_icon_wrapper_bg_color_unique' => '#4CAF50'
                    ],
                    [
                        'king_addons_feature_list_list_title' => esc_html__('Custom Colors & Styles', 'king-addons'),
                        'king_addons_feature_list_description' => esc_html__('Easily apply custom colors, fonts, and backgrounds to make each feature align with your brand.', 'king-addons'),
                        'king_addons_feature_list_icon' => [
                            'value' => 'fas fa-palette',
                            'library' => 'solid'
                        ],
                    ],
                    [
                        'king_addons_feature_list_list_title' => esc_html__('Interactive Animations', 'king-addons'),
                        'king_addons_feature_list_description' => esc_html__('Add engaging animations that activate on hover to keep users engaged and improve interactivity.', 'king-addons'),
                        'king_addons_feature_list_icon' => [
                            'value' => 'fas fa-magic',
                            'library' => 'solid'
                        ],
                    ],
                ],
                'title_field' => '{{{ king_addons_feature_list_list_title }}}',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'king_addons_section_feature_list_icon_styles',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Media', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'king_addons_feature_list_icon_color',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#FFFFFF',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-feature-list-icon-inner-wrap i' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .king-addons-feature-list-icon-inner-wrap svg' => 'fill: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'king_addons_feature_list_icon_wrapper_bg_color',
            [
                'label' => esc_html__('Background Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#5B03FF',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-feature-list-icon-inner-wrap' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'king_addons_feature_list_icon_wrapper_border_color',
            [
                'label' => esc_html__('Border Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-feature-list-icon-inner-wrap' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'king_addons_feature_list_icon_size',
            [
                'label' => esc_html__('Icon Size', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 5,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 25,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-feature-list-icon-wrap i' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .king-addons-feature-list-icon-wrap svg' => 'width: {{SIZE}}{{UNIT}}; height: auto;',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'king_addons_feature_list_icon_wrapper_size',
            [
                'label' => esc_html__('Box Size', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'render_type' => 'template',
                'range' => [
                    'px' => [
                        'min' => 5,
                        'max' => 200,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 75,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-feature-list-icon-inner-wrap' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}'
                ]
            ]
        );

        $this->add_control(
            'king_addons_feature_list_icon_wrapper_border_type',
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
                    '{{WRAPPER}} .king-addons-feature-list-icon-inner-wrap' => 'border-style: {{VALUE}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'king_addons_feature_list_icon_wrapper_border_width',
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
                    '{{WRAPPER}} .king-addons-feature-list-icon-inner-wrap' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'king_addons_feature_list_icon_wrapper_border_type!' => 'none',
                ]
            ]
        );

        $this->add_control(
            'king_addons_feature_list_icon_wrapper_border_radius',
            [
                'label' => esc_html__('Border Radius', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'default' => [
                    'top' => 5,
                    'right' => 5,
                    'bottom' => 5,
                    'left' => 5,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-feature-list-icon-inner-wrap' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'king_addons_section_feature_list_line_styles',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Line', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'king_addons_feature_list_line' => 'yes'
                ]
            ]
        );

        $this->add_control(
            'king_addons_feature_list_line_color',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#5B03FF',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-feature-list-line' => 'border-color: {{VALUE}}'
                ],
            ]
        );

        $this->add_control(
            'king_addons_feature_list_line_width',
            [
                'label' => esc_html__('Width', 'king-addons'),
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
                    'size' => 2,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-feature-list-line' => 'border-left-width: {{SIZE}}{{UNIT}};',
                ],
                'separator' => 'before'
            ]
        );

        $this->add_control(
            'king_addons_feature_list_line_border_type',
            [
                'label' => esc_html__('Type', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'solid',
                'label_block' => false,
                'options' => [
                    'solid' => esc_html__('Solid', 'king-addons'),
                    'dashed' => esc_html__('Dashed', 'king-addons'),
                    'dotted' => esc_html__('Dotted', 'king-addons'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-feature-list-line' => 'border-left-style: {{VALUE}};',
                ]
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'king_addons_section_feature_list_title_description_styles',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Title & Description', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'king_addons_feature_list_title_heading',
            [
                'label' => esc_html__('Title', 'king-addons'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before'
            ]
        );

        $this->add_control(
            'king_addons_feature_list_title_color',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#000000',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-feature-list-title' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .king-addons-feature-list-title a.king-addons-feature-list-url' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'king_addons_feature_list_title',
                'selector' => '{{WRAPPER}} .king-addons-feature-list-title',
                'fields_options' => [
                    'typography' => [
                        'default' => 'custom',
                    ],
                    'font_weight' => [
                        'default' => '600',
                    ],
                    'font_family' => [
                        'default' => 'Inter',
                    ],
                    'font_size' => [
                        'default' => [
                            'size' => '20',
                            'unit' => 'px',
                        ]
                    ]
                ]
            ]
        );

        $this->add_control(
            'king_addons_feature_list_description_heading',
            [
                'label' => esc_html__('Description', 'king-addons'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before'
            ]
        );

        $this->add_control(
            'king_addons_feature_list_description_color',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#6e6e73',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-feature-list-description' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'king_addons_feature_list_typography_description',
                'selector' => '{{WRAPPER}} .king-addons-feature-list-description',
                'fields_options' => [
                    'typography' => [
                        'default' => 'custom',
                    ],
                    'font_weight' => [
                        'default' => '400',
                    ],
                    'font_family' => [
                        'default' => 'Inter',
                    ],
                    'font_size' => [
                        'default' => [
                            'size' => '14',
                            'unit' => 'px',
                        ]
                    ]
                ]
            ]
        );

        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();

        if ($settings['king_addons_feature_repeater_list']) {
            $tags_whitelist = ['h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'div', 'span', 'p'];
            $feature_list_title_tag = in_array($settings['king_addons_feature_list_title_tag'], $tags_whitelist) ? $settings['king_addons_feature_list_title_tag'] : 'h2';

            $count_items = 0;

            echo '<div class="king-addons-feature-list-wrap">';
            echo '<ul class="king-addons-feature-list">';

            foreach ($settings['king_addons_feature_repeater_list'] as $item) {
                $this->add_link_attributes('king_addons_feature_list_list_title_url' . $count_items, $item['king_addons_feature_list_list_title_url']);
                echo '<li class="king-addons-feature-list-item elementor-repeater-item-' . esc_attr($item['_id']) . '">';
                echo '<div class="king-addons-feature-list-icon-wrap">';
                echo '<span class="king-addons-feature-list-line"></span>';
                echo '<div class="king-addons-feature-list-icon-inner-wrap">';
                if ('icon' === $item['king_addons_feature_list_media_type']) {
                    Icons_Manager::render_icon($item['king_addons_feature_list_icon'], ['aria-hidden' => 'true']);
                } else {
                    $src = Group_Control_Image_Size::get_attachment_image_src($item['king_addons_feature_list_image']['id'], 'king_addons_feature_list_thumbnail', $settings);
                    echo '<img src="' . esc_url($src) . '">';
                }
                echo '</div>';
                echo '</div>';
                echo '<div class="king-addons-feature-list-content-wrap">';
                if (empty($item['king_addons_feature_list_list_title_url'])) {
                    echo '<' . esc_attr($feature_list_title_tag) . ' class="king-addons-feature-list-title">' . wp_kses_post($item['king_addons_feature_list_list_title']) . '</' . esc_attr($feature_list_title_tag) . '>';
                } else {
                    echo '<' . esc_attr($feature_list_title_tag) . ' class="king-addons-feature-list-title"><a class="king-addons-feature-list-url" ' . $this->get_render_attribute_string('king_addons_feature_list_list_title_url' . $count_items) . '>' . wp_kses_post($item['king_addons_feature_list_list_title']) . '</a></' . esc_attr($feature_list_title_tag) . '>';
                }
                echo '<p class="king-addons-feature-list-description">' . wp_kses_post($item['king_addons_feature_list_description']) . '</p>';
                echo '</div>';
                echo '</li>';
                $count_items++;
            }

            echo '</ul>';
            echo '</div>';
        }
    }
}