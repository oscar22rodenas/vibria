<?php /** @noinspection SpellCheckingInspection, DuplicatedCode, PhpUnused */

namespace King_Addons;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;
use Elementor\Utils;
use Elementor\Widget_Base;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class Testimonial extends Widget_Base
{
    public function get_name(): string
    {
        return 'king-addons-testimonial';
    }

    public function get_title(): string
    {
        return esc_html__('Testimonial & Review', 'king-addons');
    }

    public function get_icon(): string
    {
        return 'king-addons-icon king-addons-testimonial';
    }

    public function get_style_depends(): array
    {
        return [KING_ADDONS_ASSETS_UNIQUE_KEY . '-testimonial-style'];
    }

    public function get_categories(): array
    {
        return ['king-addons'];
    }

    public function get_keywords(): array
    {
        return ['testimonial', 'review', 'rating', 'stars', 'rate', 'star',
            'king addons', 'king', 'addons', 'kingaddons', 'king-addons'];
    }

    public function get_custom_help_url(): string
    {
        return 'https://kingaddons.com/elementor/testimonial-review/';
    }

    protected function register_controls(): void
    {

        $this->start_controls_section(
            'king_addons_testimonial_layout_section',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Layout', 'king-addons'),
                'tab' => Controls_Manager::TAB_CONTENT
            ]
        );

        // selector - testimonial layouts
        $this->add_control(
            'king_addons_testimonial_layout',
            array(
                'label' => '<b>' . esc_html__('Layout', 'king-addons') . '</b>',
                'type' => Controls_Manager::SELECT,
                'options' => array(
                    'layout-1' => esc_html__('Default - Image, Desc, Rating, Bio', 'king-addons'),
                    'layout-8' => esc_html__('Default Centered - Image, Desc, Rating, Bio', 'king-addons'),
                    'layout-2' => esc_html__('Classic - Desc, Bio, Rating, Image', 'king-addons'),
                    'layout-3' => esc_html__('Desc, Image, Bio, Rating', 'king-addons'),
                    'layout-4' => esc_html__('Image | Content', 'king-addons'),
                    'layout-5' => esc_html__('Content | Image', 'king-addons'),
                    'layout-6' => esc_html__('Desc | Bottom Bio', 'king-addons'),
                    'layout-7' => esc_html__('Top Bio | Desc', 'king-addons'),
                ),
                'render_type' => 'template',
                'default' => 'layout-1',
            )
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'king_addons_testimonial_image_section',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Image', 'king-addons'),
                'tab' => Controls_Manager::TAB_CONTENT
            ]
        );

        $this->add_control(
            'king_addons_testimonial_image_switcher',
            array(
                'label' => esc_html__('Show Image', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'return_value' => 'yes',
            )
        );
        
        $this->add_control(
            'king_addons_testimonial_image',
            [
                'label' => esc_html__('Upload Image', 'king-addons'),
                'type' => Controls_Manager::MEDIA,
                'separator' => 'before',
                'dynamic' => array('active' => true),
                'default' => array(
                    'url' => Utils::get_placeholder_image_src(),
                ),
                'show_external' => true,
                'condition' => array(
                    'king_addons_testimonial_image_switcher' => 'yes',
                ),
            ]
        );

        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name' => 'king_addons_testimonial_image_size',
                'default' => 'medium',
                'condition' => array(
                    'king_addons_testimonial_image_switcher' => 'yes',
                ),
            ]
        );

        $this->end_controls_section();

        // RATING =================================================
        $this->start_controls_section(
            'king_addons_testimonial_section_rating',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Rating', 'king-addons'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'king_addons_testimonial_rating_switcher',
            array(
                'label' => esc_html__('Show Rating', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'return_value' => 'yes',
            )
        );

        $this->add_control(
            'king_addons_testimonial_rating_scale',
            [
                'label' => esc_html__('Rating Scale', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 10,
                    ],
                ],
                'default' => [
                    'size' => 5,
                ],
                'condition' => array(
                    'king_addons_testimonial_rating_switcher' => 'yes',
                ),
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'king_addons_testimonial_rating_value',
            [
                'label' => esc_html__('Rating', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 10,
                'default' => 5,
                'step' => 0.25,
                'dynamic' => [
                    'active' => true,
                ],
                'condition' => array(
                    'king_addons_testimonial_rating_switcher' => 'yes',
                ),
            ]
        );

        $this->add_control(
            'king_addons_testimonial_rating_icon',
            [
                'label' => esc_html__('Icon', 'king-addons'),
                'type' => Controls_Manager::ICONS,
                'fa4compatibility' => 'icon',
                'skin' => 'inline',
                'label_block' => false,
                'skin_settings' => [
                    'inline' => [
                        'icon' => [
                            'icon' => 'eicon-star',
                        ],
                    ],
                ],
                'default' => [
                    'value' => 'eicon-star',
                    'library' => 'eicons',
                ],
                'separator' => 'before',
                'exclude_inline_options' => ['none'],
                'condition' => array(
                    'king_addons_testimonial_rating_switcher' => 'yes',
                ),
            ]
        );

        $this->end_controls_section();
        // END: RATING ============================================

        $this->start_controls_section(
            'king_addons_testimonial_content_section',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Content', 'king-addons'),
                'tab' => Controls_Manager::TAB_CONTENT
            ]
        );

        $this->add_control(
            'king_addons_testimonial_title',
            [
                'label' => '<b>' . esc_html__('Title', 'king-addons') . '</b>',
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
                'default' => esc_html__('Title', 'king-addons'),
            ]
        );

        $this->add_control(
            'king_addons_testimonial_subtitle',
            [
                'label' => '<b>' . esc_html__('Subtitle', 'king-addons') . '</b>',
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
                'default' => esc_html__('Subtitle', 'king-addons'),
                'separator' => 'before'
            ]
        );

        $this->add_control(
            'king_addons_testimonial_description_title',
            [
                'label' => '<b>' . esc_html__('Description', 'king-addons') . '</b>',
                'type' => Controls_Manager::RAW_HTML,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'king_addons_testimonial_description',
            [
                'label' => '<b>' . esc_html__('Description', 'king-addons') . '</b>',
                'type' => Controls_Manager::WYSIWYG,
                'show_label' => false,
                'default' => esc_html__('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'king-addons'),
                'placeholder' => esc_html__('Type the description here', 'king-addons'),
            ]
        );

        $this->end_controls_section();

        /** ========================================================== */
        /** ========================================================== */
        /** ========================================================== */
        /** STYLES */

        // IMAGE
        $this->start_controls_section(
            'king_addons_testimonial_section_image_style',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Image', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'king_addons_testimonial_image_width',
            [
                'label' => esc_html__('Width (px)', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'step' => 1,
                'default' => 70,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-testimonial-image img' => 'width: {{SIZE}}px;',
                    '{{WRAPPER}} .king-addons-testimonial-layout-4 .king-addons-testimonial-image' => 'flex: 0 0 {{SIZE}}px;',
                ],
            ]
        );

        $this->add_responsive_control(
            'king_addons_testimonial_image_height',
            [
                'label' => esc_html__('Height (px)', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'step' => 1,
                'default' => 70,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-testimonial-image img' => 'height: {{SIZE}}px;'
                ],
            ]
        );

        $this->add_responsive_control(
            'king_addons_testimonial_whole_testimonial_gap',
            [
                'label' => esc_html__('Gap between Image and Content (px)', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'step' => 1,
                'default' => 20,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-testimonial-layout-4, 
                    {{WRAPPER}} .king-addons-testimonial-layout-5' => 'gap: {{SIZE}}px;'
                ],
                'condition' => [
                    'king_addons_testimonial_layout' => ['layout-4', 'layout-5']
                ],
            ]
        );

        $this->add_responsive_control(
            'king_addons_testimonial_whole_testimonial_gap_2',
            [
                'label' => esc_html__('Gap between Image and Content (px)', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'step' => 1,
                'default' => 15,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-testimonial-layout-1, 
                    {{WRAPPER}} .king-addons-testimonial-layout-2,
                    {{WRAPPER}} .king-addons-testimonial-layout-3,
                    {{WRAPPER}} .king-addons-testimonial-layout-8' => 'gap: {{SIZE}}px;'
                ],
                'condition' => [
                    'king_addons_testimonial_layout' => ['layout-1', 'layout-2', 'layout-3', 'layout-8']
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Css_Filter::get_type(),
            [
                'label' => esc_html__('CSS Filter', 'king-addons'),
                'name' => 'king_addons_testimonial_image_css_filters',
                'selector' => '{{WRAPPER}} .king-addons-testimonial-image img',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'king_addons_testimonial_image_box_shadow',
                'selector' => '{{WRAPPER}} .king-addons-testimonial-image img',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'king_addons_testimonial_image_border',
                'selector' => '{{WRAPPER}} .king-addons-testimonial-image img',
                'fields_options' => [
                    'border' => [
                        'default' => 'none',
                    ],
                ],
            ]
        );

        $this->add_responsive_control(
            'king_addons_testimonial_image_border_radius',
            [
                'label' => esc_html__('Border Radius', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'default' => [
                    'top' => 100,
                    'right' => 100,
                    'bottom' => 100,
                    'left' => 100,
                    'unit' => '%',
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-testimonial-image img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->end_controls_section();

        // TITLE
        $this->start_controls_section(
            'king_addons_testimonial_section_title_style',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Title', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        // title - typography
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => esc_html__('Typography', 'king-addons'),
                'name' => 'king_addons_testimonial_title_typography',
                'fields_options' => [
                    'font_size' => [
                        'default' => [
                            'size' => 18,
                            'unit' => 'px'
                        ],
                    ],
                    'font_weight' => [
                        'default' => 600,
                    ],
                    'typography' => [
                        'default' => 'custom',
                    ]
                ],
                'selector' => '{{WRAPPER}} .king-addons-testimonial-title',
            ]
        );

        // title - color
        $this->add_control(
            'king_addons_testimonial_title_color',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#000000',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-testimonial-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        // title - margin
        $this->add_responsive_control(
            'king_addons_testimonial_title_margin',
            [
                'label' => esc_html__('Margin', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'default' => [
                    'top' => 0,
                    'right' => 5,
                    'bottom' => 0,
                    'left' => 0,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-testimonial-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'king_addons_testimonial_section_subtitle_style',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Subtitle', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        // subtitle - typography
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => esc_html__('Typography', 'king-addons'),
                'name' => 'king_addons_testimonial_subtitle_typography',
                'fields_options' => [
                    'font_size' => [
                        'default' => [
                            'size' => 15,
                            'unit' => 'px'
                        ],
                    ],
                    'font_weight' => [
                        'default' => 400,
                    ],
                    'typography' => [
                        'default' => 'custom',
                    ]
                ],
                'selector' => '{{WRAPPER}} .king-addons-testimonial-subtitle',
            ]
        );

        // subtitle - color
        $this->add_control(
            'king_addons_testimonial_subtitle_color',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#888888',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-testimonial-subtitle' => 'color: {{VALUE}};',
                ],
            ]
        );

        // subtitle - margin
        $this->add_responsive_control(
            'king_addons_testimonial_subtitle_margin',
            [
                'label' => esc_html__('Margin', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'default' => [
                    'top' => 0,
                    'right' => 0,
                    'bottom' => 0,
                    'left' => 0,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-testimonial-subtitle' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        // subtitle - margin
        $this->add_responsive_control(
            'king_addons_testimonial_title_subtitle_margin',
            [
                'label' => esc_html__('Title-Subtitle Block Margin', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'default' => [
                    'top' => 0,
                    'right' => 0,
                    'bottom' => 15,
                    'left' => 0,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-testimonial-person-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
                'condition' => [
                    'king_addons_testimonial_layout' => ['layout-2']
                ],
            ]
        );

        $this->add_responsive_control(
            'king_addons_testimonial_title_subtitle_margin_2',
            [
                'label' => esc_html__('Title-Subtitle Block Margin', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'default' => [
                    'top' => 0,
                    'right' => 0,
                    'bottom' => 10,
                    'left' => 0,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-testimonial-person-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
                'condition' => [
                    'king_addons_testimonial_layout' => ['layout-6', 'layout-7']
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'king_addons_testimonial_section_description_style',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Description', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        // description - typography
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => esc_html__('Typography', 'king-addons'),
                'name' => 'king_addons_testimonial_description_typography',
                'fields_options' => [
                    'font_size' => [
                        'default' => [
                            'size' => 16,
                            'unit' => 'px'
                        ],
                    ],
                    'font_weight' => [
                        'default' => 400,
                    ],
                    'typography' => [
                        'default' => 'custom',
                    ]
                ],
                'selector' => '{{WRAPPER}} .king-addons-testimonial-description',
            ]
        );

        // description - color
        $this->add_control(
            'king_addons_testimonial_description_color',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#000000',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-testimonial-description' => 'color: {{VALUE}};',
                ],
            ]
        );

        // description - margin
        $this->add_responsive_control(
            'king_addons_testimonial_description_margin',
            [
                'label' => esc_html__('Margin', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'default' => [
                    'top' => 0,
                    'right' => 0,
                    'bottom' => 10,
                    'left' => 0,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-testimonial-description' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'king_addons_testimonial_layout' => ['layout-1', 'layout-2', 'layout-3', 'layout-4', 'layout-5', 'layout-8']
                ],
            ]
        );

        $this->end_controls_section();

        // STYLE: RATING ============================================
        $this->start_controls_section(
            'king_addons_testimonial_section_icon_style',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Rating', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'king_addons_testimonial_icon_size',
            [
                'label' => esc_html__('Size', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', 'rem', 'vw', 'custom'],
                'range' => [
                    'px' => [
                        'max' => 100,
                    ],
                    'em' => [
                        'min' => 0,
                        'max' => 10,
                    ],
                    'rem' => [
                        'min' => 0,
                        'max' => 10,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-testimonial-rating-general' => '--king-addons-testimonial-rating-icon-font-size: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'king_addons_testimonial_icon_gap',
            [
                'label' => esc_html__('Spacing', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', 'rem', 'vw', 'custom'],
                'range' => [
                    'px' => [
                        'max' => 100,
                    ],
                    'em' => [
                        'min' => 0,
                        'max' => 10,
                    ],
                    'rem' => [
                        'min' => 0,
                        'max' => 10,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-testimonial-rating-general' => '--king-addons-testimonial-rating-gap: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_control(
            'king_addons_testimonial_icon_color',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-testimonial-rating-general' => '--king-addons-testimonial-rating-icon-marked-color: {{VALUE}}',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'king_addons_testimonial_icon_unmarked_color',
            [
                'label' => esc_html__('Unmarked Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-testimonial-rating-general' => '--king-addons-testimonial-rating-icon-color: {{VALUE}}',
                ],
            ]
        );

        // rating - margin
        $this->add_responsive_control(
            'king_addons_testimonial_icon_margin',
            [
                'label' => esc_html__('Margin', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'default' => [
                    'top' => 0,
                    'right' => 0,
                    'bottom' => 10,
                    'left' => 0,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-testimonial-rating-general' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'king_addons_testimonial_layout' => ['layout-1', 'layout-2', 'layout-4', 'layout-5', 'layout-8']
                ],
            ]
        );

        $this->end_controls_section();
        // END: RATING ============================================

        $this->start_controls_section(
            'king_addons_testimonial_section_whole_testimonial_style',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Whole Testimonial', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );


        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'king_addons_testimonial_whole_testimonial_bg',
                'fields_options' => [
                    'color' => [
                        'default' => '#FFFFFF',
                    ],
                    'background' => [
                        'default' => 'classic',
                    ]
                ],
                'selector' => '{{WRAPPER}} .king-addons-testimonial-layout'
            ]
        );

        $this->add_responsive_control(
            'king_addons_testimonial_whole_testimonial_gap_3',
            [
                'label' => esc_html__('Space between Bio and Description (px)', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'step' => 1,
                'default' => 25,
                'separator' => 'before',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-testimonial-layout-6, 
                    {{WRAPPER}} .king-addons-testimonial-layout-7' => 'gap: {{SIZE}}px;'
                ],
                'condition' => [
                    'king_addons_testimonial_layout' => ['layout-6', 'layout-7']
                ],
            ]
        );

        $this->add_responsive_control(
            'king_addons_testimonial_whole_testimonial_gap_4',
            [
                'label' => esc_html__('Space between Image and Title + Subtitle, Rating (px)', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'step' => 1,
                'default' => 20,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-testimonial-layout-6 .king-addons-testimonial-content, 
                    {{WRAPPER}} .king-addons-testimonial-layout-7 .king-addons-testimonial-content' => 'gap: {{SIZE}}px;'
                ],
                'condition' => [
                    'king_addons_testimonial_layout' => ['layout-6', 'layout-7']
                ],
            ]
        );

        $this->add_responsive_control(
            'king_addons_testimonial_whole_testimonial_padding',
            [
                'label' => esc_html__('Padding', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'default' => [
                    'top' => 30,
                    'right' => 30,
                    'bottom' => 30,
                    'left' => 30,
                    'unit' => 'px',
                ],
                'separator' => 'before',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-testimonial-layout' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->add_responsive_control(
            'king_addons_testimonial_whole_testimonial_margin',
            [
                'label' => esc_html__('Margin', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'default' => [
                    'top' => 0,
                    'right' => 0,
                    'bottom' => 0,
                    'left' => 0,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-testimonial-layout' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'king_addons_testimonial_whole_testimonial_box_shadow',
                'selector' => '{{WRAPPER}} .king-addons-testimonial-layout',
                'fields_options' => [
                    'box_shadow' => [
                        'default' => [
                            'horizontal' => 0,
                            'vertical' => 3,
                            'blur' => 40,
                            'spread' => 0,
                            'color' => 'rgba(36, 36, 36, 0.1)',
                        ],
                    ],
                    'box_shadow_type' => [
                        'default' => 'yes',
                    ],
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'king_addons_testimonial_whole_testimonial_border',
                'selector' => '{{WRAPPER}} .king-addons-testimonial-layout',
                'fields_options' => [
                    'border' => [
                        'default' => 'none',
                    ],
                ],
            ]
        );

        $this->add_responsive_control(
            'king_addons_testimonial_whole_testimonial_border_radius',
            [
                'label' => esc_html__('Border Radius', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'default' => [
                    'top' => 8,
                    'right' => 8,
                    'bottom' => 8,
                    'left' => 8,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-testimonial-layout' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->end_controls_section();

        // quote symbol
    }

    protected function render(): void
    {
        $settings = $this->get_settings();

        // Define allowed tags and attributes
        $allowed_tags = wp_kses_allowed_html('post');
        $allowed_tags['img']['srcset'] = true; // Allow srcset attribute for img tag
        $allowed_tags['img']['sizes'] = true; // Allow sizes attribute for img tag
        $allowed_tags['img']['decoding'] = true; // Allow decoding attribute for img tag
        $allowed_tags['img']['loading'] = true; // Allow loading attribute for img tag. It is for the lazy loading possibility.

        $layout = $settings['king_addons_testimonial_layout'];
        $title = $settings['king_addons_testimonial_title'];
        $subtitle = $settings['king_addons_testimonial_subtitle'];
        $description = $settings['king_addons_testimonial_description'];

        echo '<div class="king-addons-testimonial-layout king-addons-testimonial-' . esc_attr($layout) . '">';

        if ($layout === 'layout-1') {

            $this->renderImage($settings, $allowed_tags);

            echo '<div class="king-addons-testimonial-content">';

            $this->renderDescription($description);

            $this->renderStars($settings);

            $this->renderPerson($title, $subtitle);

            echo '</div>';
        }

        if ($layout === 'layout-2') {

            echo '<div class="king-addons-testimonial-content">';

            $this->renderDescription($description);

            $this->renderPerson($title, $subtitle);

            $this->renderStars($settings);

            echo '</div>';

            $this->renderImage($settings, $allowed_tags);
        }

        if ($layout === 'layout-3') {

            $this->renderDescription($description);

            $this->renderImage($settings, $allowed_tags);

            $this->renderPerson($title, $subtitle);

            $this->renderStars($settings);

        }

        if ($layout === 'layout-4') {

            $this->renderImage($settings, $allowed_tags);

            echo '<div class="king-addons-testimonial-content">';

            $this->renderDescription($description);

            $this->renderStars($settings);

            $this->renderPerson($title, $subtitle);

            echo '</div>';
        }

        if ($layout === 'layout-5') {

            echo '<div class="king-addons-testimonial-content">';

            $this->renderDescription($description);

            $this->renderStars($settings);

            $this->renderPerson($title, $subtitle);

            echo '</div>';

            $this->renderImage($settings, $allowed_tags);
        }

        if ($layout === 'layout-6') {

            $this->renderDescription($description);

            echo '<div class="king-addons-testimonial-content">';

            $this->renderImage($settings, $allowed_tags);

            echo '<div class="king-addons-testimonial-content-inner">';

            $this->renderPerson($title, $subtitle);

            $this->renderStars($settings);

            echo '</div>';

            echo '</div>';
        }

        if ($layout === 'layout-7') {

            echo '<div class="king-addons-testimonial-content">';

            $this->renderImage($settings, $allowed_tags);

            echo '<div class="king-addons-testimonial-content-inner">';

            $this->renderPerson($title, $subtitle);

            $this->renderStars($settings);

            echo '</div>';

            echo '</div>';

            $this->renderDescription($description);
        }

        if ($layout === 'layout-8') {

            $this->renderImage($settings, $allowed_tags);

            echo '<div class="king-addons-testimonial-content">';

            $this->renderDescription($description);

            $this->renderStars($settings);

            $this->renderPerson($title, $subtitle);

            echo '</div>';
        }

        echo '</div>';

    }

    public function renderImage($settings, $allowed_tags): void
    {
        if ($settings['king_addons_testimonial_image_switcher'] == 'yes') {
            $image = Group_Control_Image_Size::get_attachment_image_html($settings, 'king_addons_testimonial_image_size', 'king_addons_testimonial_image');
            echo '<div class="king-addons-testimonial-image">';
            echo wp_kses($image, $allowed_tags);
            echo '</div>';
        }
    }

    public function renderPerson($title, $subtitle): void
    {
        echo '<div class="king-addons-testimonial-person-wrapper">';
        
        echo '<div class="king-addons-testimonial-title">';
        echo esc_html($title);
        echo '</div>';

        echo '<div class="king-addons-testimonial-subtitle">';
        echo esc_html($subtitle);
        echo '</div>';

        echo '</div>';
    }

    public function renderDescription($description): void
    {
        echo '<div class="king-addons-testimonial-description">';
        echo wp_kses_post($description);
        echo '</div>';
    }

    public function renderStars($settings): void
    {
        if ($settings['king_addons_testimonial_rating_switcher'] == 'yes') {
            echo '<div class="king-addons-testimonial-stars">';
            echo '<div class="king-addons-testimonial-rating-general">';
            $this->renderRating();
            echo '</div>';
            echo '</div>';
        }
    }

    protected function get_rating_value(): float
    {
        $initial_value = $this->get_rating_scale();
        $rating_value = $this->get_settings_for_display('king_addons_testimonial_rating_value');

        if ('' === $rating_value) {
            $rating_value = $initial_value;
        }

        $rating_value = floatval($rating_value);

        return round($rating_value, 2);
    }

    protected function get_rating_scale(): int
    {
        return intval($this->get_settings_for_display('king_addons_testimonial_rating_scale')['size']);
    }

    protected function get_icon_marked_width($icon_index): string
    {
        $rating_value = $this->get_rating_value();

        $width = '0%';

        if ($rating_value >= $icon_index) {
            $width = '100%';
        } elseif (intval(ceil($rating_value)) === $icon_index) {
            $width = ($rating_value - ($icon_index - 1)) * 100 . '%';
        }

        return $width;
    }

    protected function get_icon_markup(): string
    {
        $icon = $this->get_settings_for_display('king_addons_testimonial_rating_icon');
        $rating_scale = $this->get_rating_scale();

        ob_start();

        for ($index = 1; $index <= $rating_scale; $index++) {
            $this->add_render_attribute('icon_marked_' . $index, [
                'class' => 'king-addons-testimonial-icon-wrapper king-addons-testimonial-icon-marked',
            ]);

            $icon_marked_width = $this->get_icon_marked_width($index);

            if ('100%' !== $icon_marked_width) {
                $this->add_render_attribute('icon_marked_' . $index, [
                    'style' => '--king-addons-testimonial-rating-icon-marked-width: ' . $icon_marked_width . ';',
                ]);
            }
            ?>
            <div class="king-addons-testimonial-icon">
                <div <?php $this->print_render_attribute_string('icon_marked_' . $index); ?>>
                    <?php echo Icons_Manager::try_get_icon_html($icon, ['aria-hidden' => 'true']); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                </div>
                <div class="king-addons-testimonial-icon-wrapper king-addons-testimonial-icon-unmarked">
                    <?php echo Icons_Manager::try_get_icon_html($icon, ['aria-hidden' => 'true']); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                </div>
            </div>
            <?php
        }

        return ob_get_clean();
    }

    protected function renderRating(): void
    {
        $this->add_render_attribute('widget', [
            'class' => 'king-addons-testimonial-rating',
            'itemtype' => 'https://schema.org/Rating',
            'itemscope' => '',
            'itemprop' => 'reviewRating',
        ]);

        $this->add_render_attribute('widget_wrapper', [
            'class' => 'king-addons-testimonial-rating-wrapper',
            'itemprop' => 'ratingValue',
            'content' => $this->get_rating_value(),
            'role' => 'img',
            /* translators: %1$s is rating value, %2$s is rating scale */
            'aria-label' => sprintf(esc_html__('Rated %1$s out of %2$s', 'king-addons'),
                $this->get_rating_value(),
                $this->get_rating_scale()
            ),
        ]);
        ?>
        <div <?php $this->print_render_attribute_string('widget'); ?>>
            <meta itemprop="worstRating" content="0">
            <meta itemprop="bestRating"
                  content="<?php echo $this->get_rating_scale(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped 
                  ?>">
            <div <?php $this->print_render_attribute_string('widget_wrapper'); ?>>
                <?php echo $this->get_icon_markup(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped 
                ?>
            </div>
        </div>
        <?php
    }
}