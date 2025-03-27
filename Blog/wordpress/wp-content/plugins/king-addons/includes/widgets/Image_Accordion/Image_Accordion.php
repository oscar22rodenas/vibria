<?php

namespace King_Addons;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;
use Elementor\Utils;
use Elementor\Widget_Base;

if (!defined('ABSPATH')) exit;

class Image_Accordion extends Widget_Base
{

    public function get_name()
    {
        return 'king-addons-image-accordion';
    }

    public function get_title()
    {
        return esc_html__('Image Accordion', 'king-addons');
    }

    public function get_icon()
    {
        return 'king-addons-icon king-addons-image-accordion';
    }

    public function get_categories()
    {
        return ['king-addons'];
    }

    public function get_keywords()
    {
        return ['king addons', 'king', 'addons', 'kingaddons', 'king-addons',
            'image accordion', 'image', 'accordion', 'slider', 'images', 'media', 'accor', 'carousel'];
    }

    public function get_script_depends()
    {
        return [
            KING_ADDONS_ASSETS_UNIQUE_KEY . '-image-accordion-script',
            KING_ADDONS_ASSETS_UNIQUE_KEY . '-lightgallery-lightgallery'
        ];
    }

    public function get_style_depends()
    {
        return [
            KING_ADDONS_ASSETS_UNIQUE_KEY . '-animation-button',
            KING_ADDONS_ASSETS_UNIQUE_KEY . '-animation-general',
            KING_ADDONS_ASSETS_UNIQUE_KEY . '-animation-timing',
            KING_ADDONS_ASSETS_UNIQUE_KEY . '-animation-loading',
            KING_ADDONS_ASSETS_UNIQUE_KEY . '-image-accordion-style',
            KING_ADDONS_ASSETS_UNIQUE_KEY . '-lightgallery-lightgallery',
            KING_ADDONS_ASSETS_UNIQUE_KEY . '-general-general',
        ];
    }

    public function get_custom_help_url()
    {
        return 'https://kingaddons.com/';
    }

    public ?string $item_bg_image_url;

    public function add_section_lightbox_popup()
    {
    }

    public function add_section_lightbox_styles()
    {
    }

    public function add_control_accordion_direction()
    {
        $this->add_responsive_control(
            'accordion_direction',
            [
                'label' => esc_html__('Layout', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'render_type' => 'template',
                'options' => [
                    'row' => esc_html__('Horizontal', 'king-addons'),
                    'pro-cl' => esc_html__('Vertical (Pro)', 'king-addons'),
                ],
                'prefix_class' => 'king-addons-image-accordion-',
                'default' => 'row',
                'selectors' => [
                    '{{WRAPPER}}.king-addons-image-accordion-row .king-addons-image-accordion-wrap .king-addons-image-accordion' => 'flex-direction: {{VALUE}};',
                    '{{WRAPPER}}.king-addons-image-accordion-column .king-addons-image-accordion-wrap .king-addons-image-accordion' => 'flex-direction: {{VALUE}};',
                ]
            ]
        );
    }

    public function add_control_accordion_interaction()
    {
        $this->add_control(
            'accordion_interaction',
            [
                'label' => esc_html__('Interaction', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'hover' => esc_html__('Hover', 'king-addons'),
                    'pro-ck' => esc_html__('Click (Pro)', 'king-addons'),
                ],
                'render_type' => 'template',
                'default' => 'hover',
                'prefix_class' => 'king-addons-image-accordion-interaction-',
            ]
        );
    }

    public function add_control_accordion_skew()
    {
        $this->add_control(
            'accordion_skew',
            [
                'label' => sprintf(__('Skew Images %s', 'king-addons'), '<i class="eicon-pro-icon"></i>'),
                'type' => Controls_Manager::SWITCHER,
                'separator' => 'before',
                'classes' => 'king-addons-pro-control'
            ]
        );
    }

    public function add_control_image_effects()
    {
        $this->add_control(
            'image_effects',
            [
                'label' => esc_html__('Select Effect', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'none' => esc_html__('None', 'king-addons'),
                    'pro-zi' => esc_html__('Zoom In (Pro)', 'king-addons'),
                    'pro-zo' => esc_html__('Zoom Out (Pro)', 'king-addons'),
                    'grayscale-in' => esc_html__('Grayscale In', 'king-addons'),
                    'pro-go' => esc_html__('Grayscale Out (Pro)', 'king-addons'),
                    'blur-in' => esc_html__('Blur In', 'king-addons'),
                    'pro-bo' => esc_html__('Blur Out (Pro)', 'king-addons'),
                    'slide' => esc_html__('Slide', 'king-addons')
                ],
                'default' => 'none',
            ]
        );
    }

    public function add_control_overlay_color()
    {
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'overlay_color',
                'label' => esc_html__('Background', 'king-addons'),
                'types' => ['classic', 'gradient'],
                'exclude' => ['image'],
                'fields_options' => [
                    'background' => [
                        'default' => 'classic',
                    ],
                    'color' => [
                        'default' => 'rgba(128, 128, 128, 0.5)',
                    ],
                ],
                'selector' => '{{WRAPPER}} .king-addons-img-accordion-hover-bg'
            ]
        );
    }

    public function add_control_overlay_blend_mode()
    {
    }

    public function add_option_element_select()
    {
        return [
            'title' => esc_html__('Title', 'king-addons'),
            'description' => esc_html__('Description', 'king-addons'),
            'pro-lbx' => esc_html__('Lightbox (Pro)', 'king-addons'),
            'button' => esc_html__('Button', 'king-addons'),
            'separator' => esc_html__('Separator', 'king-addons'),
        ];
    }

    public function add_control_button_animation()
    {
        $this->add_control(
            'button_animation',
            [
                'label' => esc_html__('Select Animation', 'king-addons'),
                'type' => 'king-addons-button-animations',
                'default' => 'king-addons-button-sweep-to-top',
            ]
        );
    }

    public function add_control_button_animation_height()
    {
        $this->add_control(
            'button_animation_height',
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
                        'max' => 30,
                    ],
                ],
                'size_units' => ['%', 'px'],
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
                    'button_animation' => [
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
    }

    public function add_control_lightbox_popup_thumbnails()
    {
    }

    public function add_control_lightbox_popup_thumbnails_default()
    {
    }

    public function add_control_lightbox_popup_sharing()
    {
    }

    public function add_repeater_args_element_align_hr()
    {
        return [
            'label' => esc_html__('Horizontal Align', 'king-addons'),
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
            'selectors' => [
                '{{WRAPPER}} {{CURRENT_ITEM}}' => 'text-align: center'
            ],
            'render_type' => 'template',
            'separator' => 'after'
        ];
    }

    protected function register_controls()
    {


        $this->start_controls_section(
            'accordion_settings',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Settings', 'king-addons'),
                'type' => Controls_Manager::SECTION,
            ]
        );


        $this->add_control_accordion_direction();


        Core::renderUpgradeProNotice($this, Controls_Manager::RAW_HTML, 'image-accordion', 'accordion_direction', ['pro-cl']);

        $this->add_control_accordion_interaction();


        Core::renderUpgradeProNotice($this, Controls_Manager::RAW_HTML, 'image-accordion', 'accordion_interaction', ['pro-ck']);

        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name' => 'accordion_image_size',
                'default' => 'full',
            ]
        );

        $this->add_control(
            'default_active',
            [
                'label' => __('Active Image By Default', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'default' => 0,
                'min' => 0,
            ]
        );

        $this->add_responsive_control(
            'accordion_height',
            [
                'type' => Controls_Manager::SLIDER,
                'label' => esc_html__('Height', 'king-addons'),
                'size_units' => ['px', 'vh'],
                'range' => [
                    'px' => [
                        'min' => 20,
                        'max' => 1500,
                    ],
                    'vh' => [
                        'min' => 20,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 500,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-image-accordion' => 'height: {{SIZE}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'accordion_active_item_style',
            [
                'label' => esc_html__('Grow (Active)', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 10,
                    ],
                ],
                'default' => [
                    'size' => 4,
                    'unit' => 'px'
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-image-accordion-wrap .king-addons-image-accordion-item.king-addons-image-accordion-item-grow' => 'flex: {{SIZE}};',
                ],
                'render_type' => 'template'
            ]
        );

        $this->add_responsive_control(
            'accordion_items_spacing',
            [
                'label' => esc_html__('Gutter', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'default' => [
                    'size' => 0,
                    'units' => 'px',
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}}.king-addons-image-accordion-row .king-addons-image-accordion-item:not(:last-child)' => 'margin-right: {{SIZE}}px;',
                    '{{WRAPPER}}.king-addons-image-accordion-column .king-addons-image-accordion-item:not(:last-child)' => 'margin-bottom: {{SIZE}}px;'
                ]
            ]
        );

        $this->add_responsive_control(
            'accordion_item_border',
            [
                'label' => esc_html__('Border Type', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'outer' => esc_html__('Outer', 'king-addons'),
                    'individual' => esc_html__('Individual', 'king-addons')
                ],
                'default' => 'outer',
                'prefix_class' => 'king-addons-acc-border-',
                'render_type' => 'template',
                'separator' => 'before'
            ]
        );

        $this->add_control(
            'accordion_item_border_radius',
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
                    '{{WRAPPER}}.king-addons-acc-border-outer.king-addons-image-accordion-row .king-addons-image-accordion-item:first-child' => 'border-radius: {{TOP}}{{UNIT}} 0 0 {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}}.king-addons-acc-border-outer.king-addons-image-accordion-row .king-addons-image-accordion-item:last-child' => 'border-radius: 0 {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} 0;',
                    '{{WRAPPER}}.king-addons-acc-border-outer.king-addons-image-accordion-column .king-addons-image-accordion-item:first-child' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} 0 0;',
                    '{{WRAPPER}}.king-addons-acc-border-outer.king-addons-image-accordion-column .king-addons-image-accordion-item:last-child' => 'border-radius: 0 0 {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}}.king-addons-acc-border-individual .king-addons-image-accordion-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->add_control_accordion_skew();

        $this->add_control(
            'accordion_item_transition',
            [
                'label' => esc_html__('Grow Transition', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'default' => 0.3,
                'min' => 0,
                'max' => 5,
                'step' => 0.1,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-image-accordion-item' => 'transition-duration: {{VALUE}}s;',
                    '{{WRAPPER}} .king-addons-image-accordion-item .king-addons-accordion-background' => 'transition-duration: {{VALUE}}s;'
                ],
                'separator' => 'before'
            ]
        );

        $this->end_controls_section();


        $this->start_controls_section(
            'section_accordion_items',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Content', 'king-addons'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'accordion_item_bg_image',
            [
                'label' => esc_html__('Image', 'king-addons'),
                'type' => Controls_Manager::MEDIA,
                'dynamic' => [
                    'active' => true,
                ],
                'render_type' => 'template',
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ]
            ]
        );

        $repeater->add_responsive_control(
            'bg_image_size',
            [
                'label' => esc_html__('Size', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'cover' => esc_html__('Cover', 'king-addons'),
                    'contain' => esc_html__('Contain', 'king-addons'),
                    'auto' => esc_html__('Auto', 'king-addons'),
                ],
                'default' => 'cover',
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}.king-addons-image-accordion-item .king-addons-accordion-background' => 'background-size: {{VALUE}}',
                ]
            ]
        );

        $repeater->add_responsive_control(
            'bg_image_position',
            [
                'label' => esc_html__('Position', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'center center' => esc_html__('Center Center', 'king-addons'),
                    'center left' => esc_html__('Center Left', 'king-addons'),
                    'center right' => esc_html__('Center Right', 'king-addons'),
                    'top center' => esc_html__('Top Center', 'king-addons'),
                    'top left' => esc_html__('Top Left', 'king-addons'),
                    'top right' => esc_html__('Top Right', 'king-addons'),
                    'bottom center' => esc_html__('Bottom Center', 'king-addons'),
                    'bottom left' => esc_html__('Bottom Left', 'king-addons'),
                    'bottom right' => esc_html__('Bottom Right', 'king-addons'),
                ],
                'default' => 'center center',
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}.king-addons-image-accordion-item .king-addons-accordion-background' => 'background-position: {{VALUE}}',
                ]
            ]
        );

        $repeater->add_responsive_control(
            'bg_image_repeat',
            [
                'label' => esc_html__('Repeat', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'repeat' => esc_html__('Repeat', 'king-addons'),
                    'no-repeat' => esc_html__('No-repeat', 'king-addons'),
                    'repeat-x' => esc_html__('Repeat-x', 'king-addons'),
                    'repeat-y' => esc_html__('Repeat-y', 'king-addons'),
                ],
                'default' => 'repeat',
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}.king-addons-image-accordion-item .king-addons-accordion-background' => 'background-repeat: {{VALUE}}',
                ]
            ]
        );


        $repeater->add_control(
            'accordion_item_title', [
                'label' => esc_html__('Title', 'king-addons'),
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => esc_html__('Item 1 Title', 'king-addons'),
                'label_block' => true,
                'separator' => 'before'
            ]
        );

        $repeater->add_control(
            'accordion_item_description', [
                'label' => esc_html__('Description', 'king-addons'),
                'type' => Controls_Manager::TEXTAREA,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => esc_html__('Lorem ipsum dolos ave nita', 'king-addons'),
                'label_block' => true
            ]
        );

        $repeater->add_control(
            'element_button_text',
            [
                'label' => esc_html__('Button Text', 'king-addons'),
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => 'Button'
            ]
        );

        $repeater->add_control(
            'accordion_btn_url',
            [
                'label' => esc_html__('Button URL', 'king-addons'),
                'type' => Controls_Manager::URL,
                'dynamic' => [
                    'active' => true,
                ]
            ]
        );

        $repeater->add_control(
            'wrapper_link',
            [
                'label' => esc_html__('Use Button URL as Image Link', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'label_block' => false,
                'render_type' => 'template',
            ]
        );

        $this->add_control(
            'accordion_items',
            [
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'accordion_item_title' => esc_html__('Item 1 Title', 'king-addons'),
                        'accordion_item_bg_image' => [
                            'url' => Utils::get_placeholder_image_src(),
                        ],
                    ],
                    [
                        'accordion_item_title' => esc_html__('Item 2 Title', 'king-addons'),
                    ],
                    [
                        'accordion_item_title' => esc_html__('Item 3 Title', 'king-addons'),
                        'accordion_item_bg_image' => [
                            'url' => Utils::get_placeholder_image_src(),
                        ],
                    ],
                ],
                'title_field' => '{{{ accordion_item_title }}}',
            ]
        );

        if (!king_addons_freemius()->can_use_premium_code__premium_only()) {
            $this->add_control(
                'accordion_repeater_pro_notice',
                [
                    'type' => Controls_Manager::RAW_HTML,
                    'raw' => 'More than 3 Items are available<br> in the <strong><a href="https://kingaddons.com/pricing/?utm_source=kng-module-image-accordion-settings-upgrade-pro&utm_medium=plugin&utm_campaign=kng" target="_blank">Pro version</a></strong>',
                    'content_classes' => 'king-addons-pro-notice',
                ]
            );
        }

        $this->end_controls_section();


        $this->start_controls_section(
            'section_accordion_elements',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Elements', 'king-addons'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'element_select',
            [
                'label' => esc_html__('Select Element', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'title',
                'options' => $this->add_option_element_select(),
                'separator' => 'after'
            ]
        );


        Core::renderUpgradeProNotice($repeater, Controls_Manager::RAW_HTML, 'image-accordion', 'element_select', ['pro-lbx']);

        $repeater->add_control(
            'element_display',
            [
                'label' => esc_html__('Display', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'block',
                'options' => [
                    'inline' => esc_html__('Inline', 'king-addons'),
                    'block' => esc_html__('Seperate Line', 'king-addons'),
                    'custom' => esc_html__('Custom Width', 'king-addons'),
                ],
            ]
        );

        $repeater->add_control(
            'element_custom_width',
            [
                'label' => esc_html__('Element Width', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['%'],
                'range' => [
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => '%',
                    'size' => 100,
                ],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}' => 'width: {{SIZE}}%;',
                ],
                'condition' => [
                    'element_display' => 'custom',
                ],
            ]
        );

        if (!king_addons_freemius()->can_use_premium_code__premium_only()) {
            $repeater->add_control(
                'element_align_pro_notice',
                [
                    'raw' => 'Vertical and Horizontal Align options are available in the <strong><a href="https://kingaddons.com/pricing/?utm_source=kng-module-image-accordion-settings-upgrade-pro&utm_medium=plugin&utm_campaign=kng" target="_blank">Pro version</a></strong>',

                    'type' => Controls_Manager::RAW_HTML,
                    'content_classes' => 'king-addons-pro-notice',
                ]
            );
        }

        $repeater->add_control(
            'element_align_vr',
            [
                'label' => esc_html__('Vertical Align', 'king-addons'),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'default' => 'middle',
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
                ]
            ]
        );

        $repeater->add_control('element_align_hr', $this->add_repeater_args_element_align_hr());

        $repeater->add_control(
            'element_title_tag',
            [
                'label' => esc_html__('Text HTML Tag', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'h1' => esc_html__('H1', 'king-addons'),
                    'h2' => esc_html__('H2', 'king-addons'),
                    'h3' => esc_html__('H3', 'king-addons'),
                    'h4' => esc_html__('H4', 'king-addons'),
                    'h5' => esc_html__('H5', 'king-addons'),
                    'h6' => esc_html__('H6', 'king-addons'),
                    'div' => esc_html__('div', 'king-addons'),
                    'span' => esc_html__('span', 'king-addons'),
                    'p' => esc_html__('p', 'king-addons'),
                ],
                'default' => 'h2',
                'condition' => [
                    'element_select' => 'title'
                ]
            ]
        );

        $repeater->add_control(
            'element_lightbox_icon',
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
                    'element_select' => 'lightbox'
                ]
            ]
        );

        $repeater->add_control(
            'element_extra_text_pos',
            [
                'label' => esc_html__('Extra Text Display', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'none' => esc_html__('None', 'king-addons'),
                    'before' => esc_html__('Before Element', 'king-addons'),
                    'after' => esc_html__('After Element', 'king-addons'),
                ],
                'default' => 'none',
                'condition' => [
                    'element_select!' => [
                        'title',
                        'description',
                        'button',
                        'separator',
                    ],
                ]
            ]
        );

        $repeater->add_control(
            'element_extra_text',
            [
                'label' => esc_html__('Extra Text', 'king-addons'),
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => '',
                'condition' => [
                    'element_select!' => [
                        'title',
                        'description',
                        'button',
                        'separator',
                    ],
                    'element_extra_text_pos!' => 'none'
                ]
            ]
        );

        $repeater->add_control(
            'element_extra_icon_pos',
            [
                'label' => esc_html__('Extra Icon Position', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'none' => esc_html__('None', 'king-addons'),
                    'before' => esc_html__('Before Element', 'king-addons'),
                    'after' => esc_html__('After Element', 'king-addons'),
                ],
                'default' => 'none',
                'condition' => [
                    'element_select!' => [
                        'title',
                        'separator',
                        'description',
                        'lightbox'
                    ],
                ]
            ]
        );

        $repeater->add_control(
            'element_extra_icon',
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
                    'element_select!' => [
                        'title',
                        'separator',
                        'description',
                        'lightbox'
                    ],
                    'element_extra_icon_pos!' => 'none'
                ]
            ]
        );

        $repeater->add_control(
            'animation_divider',
            [
                'type' => Controls_Manager::DIVIDER,
                'style' => 'thick',
                'condition' => [
                    'element_select' => [
                        'button',
                        'lightbox'
                    ],
                ]
            ]
        );


        $repeater->add_control(
            'element_animation',
            [
                'label' => esc_html__('Select Animation', 'king-addons'),
                'type' => 'king-addons-animations',
                'default' => 'fade-in'
            ]
        );


        Core::renderUpgradeProNotice($repeater, Controls_Manager::RAW_HTML, 'image-accordion', 'element_animation', ['pro-slrt', 'pro-slxrt', 'pro-slbt', 'pro-sllt', 'pro-sltp', 'pro-slxlt', 'pro-sktp', 'pro-skrt', 'pro-skbt', 'pro-sklt', 'pro-scup', 'pro-scdn', 'pro-rllt', 'pro-rlrt']);

        $repeater->add_control(
            'element_animation_duration',
            [
                'label' => esc_html__('Animation Duration', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'default' => 0.3,
                'min' => 0,
                'max' => 5,
                'step' => 0.1,
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}' => 'transition-duration: {{VALUE}}s;'
                ],
                'condition' => [
                    'element_animation!' => 'none'
                ],
            ]
        );

        $repeater->add_control(
            'element_animation_delay',
            [
                'label' => esc_html__('Animation Delay', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'default' => 0,
                'min' => 0,
                'max' => 5,
                'step' => 0.1,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-animation-wrap:hover {{CURRENT_ITEM}}' => 'transition-delay: {{VALUE}}s;'
                ],
                'condition' => [
                    'element_animation!' => 'none'
                ],
            ]
        );

        $repeater->add_control(
            'element_animation_timing',
            [
                'label' => esc_html__('Animation Timing', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => Core::getAnimationTimings(),
                'default' => 'ease-default',
                'condition' => [
                    'element_animation!' => 'none'
                ],
            ]
        );


        Core::renderUpgradeProNotice($repeater, Controls_Manager::RAW_HTML, 'image-accordion', 'element_animation_timing', ['pro-eio', 'pro-eiqd', 'pro-eicb', 'pro-eiqrt', 'pro-eiqnt', 'pro-eisn', 'pro-eiex', 'pro-eicr', 'pro-eibk', 'pro-eoqd', 'pro-eocb', 'pro-eoqrt', 'pro-eoqnt', 'pro-eosn', 'pro-eoex', 'pro-eocr', 'pro-eobk', 'pro-eioqd', 'pro-eiocb', 'pro-eioqrt', 'pro-eioqnt', 'pro-eiosn', 'pro-eioex', 'pro-eiocr', 'pro-eiobk',]);

        $repeater->add_control(
            'element_animation_size',
            [
                'label' => esc_html__('Animation Size', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'small' => esc_html__('Small', 'king-addons'),
                    'medium' => esc_html__('Medium', 'king-addons'),
                    'large' => esc_html__('Large', 'king-addons'),
                ],
                'default' => 'large',
                'condition' => [
                    'element_animation!' => 'none'
                ],
            ]
        );

        $repeater->add_control(
            'element_animation_tr',
            [
                'label' => esc_html__('Animation Transparency', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'return_value' => 'yes',
                'condition' => [
                    'element_animation!' => 'none'
                ],
            ]
        );

        $repeater->add_responsive_control(
            'element_show_on',
            [
                'label' => esc_html__('Show on this Device', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'widescreen_default' => 'yes',
                'laptop_default' => 'yes',
                'tablet_extra_default' => 'yes',
                'tablet_default' => 'yes',
                'mobile_extra_default' => 'yes',
                'mobile_default' => 'yes',
                'selectors_dictionary' => [
                    '' => 'position: absolute; left: -99999999px;',
                    'yes' => 'position: static; left: auto;'
                ],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}' => '{{VALUE}}',
                ],
                'render_type' => 'template',
            ]
        );

        $this->add_control(
            'accordion_elements',
            [
                'label' => esc_html__('Accordion Elements', 'king-addons'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'element_select' => 'title',
                    ],
                    [
                        'element_select' => 'description',
                        'element_display' => 'inline',
                    ],
                    [
                        'element_select' => 'button',
                    ],
                ],
                'title_field' => '{{{ element_select.charAt(0).toUpperCase() + element_select.slice(1) }}}',
            ]
        );

        $this->end_controls_section();


        $this->start_controls_section(
            'section_image_overlay',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Media Overlay', 'king-addons'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_responsive_control(
            'overlay_width',
            [
                'label' => esc_html__('Overlay Width', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['%', 'px'],
                'default' => [
                    'unit' => '%',
                    'size' => 100,
                ],
                'range' => [
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                    'px' => [
                        'min' => 0,
                        'max' => 500,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-img-accordion-hover-bg' => 'width: {{SIZE}}{{UNIT}};top:calc((100% - {{overlay_height.SIZE}}{{overlay_height.UNIT}})/2);left:calc((100% - {{SIZE}}{{UNIT}})/2);',
                    '{{WRAPPER}} .king-addons-img-accordion-hover-bg[class*="-top"]' => 'top:calc((100% - {{overlay_height.SIZE}}{{overlay_height.UNIT}})/2);left:calc((100% - {{SIZE}}{{UNIT}})/2);',
                    '{{WRAPPER}} .king-addons-img-accordion-hover-bg[class*="-bottom"]' => 'bottom:calc((100% - {{overlay_height.SIZE}}{{overlay_height.UNIT}})/2);left:calc((100% - {{SIZE}}{{UNIT}})/2);',
                    '{{WRAPPER}} .king-addons-img-accordion-hover-bg[class*="-right"]' => 'top:calc((100% - {{overlay_height.SIZE}}{{overlay_height.UNIT}})/2);right:calc((100% - {{SIZE}}{{UNIT}})/2);',
                    '{{WRAPPER}} .king-addons-img-accordion-hover-bg[class*="-left"]' => 'top:calc((100% - {{overlay_height.SIZE}}{{overlay_height.UNIT}})/2);left:calc((100% - {{SIZE}}{{UNIT}})/2);',
                ],
            ]
        );

        $this->add_responsive_control(
            'overlay_height',
            [
                'label' => esc_html__('Overlay Height', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['%', 'px'],
                'default' => [
                    'unit' => '%',
                    'size' => 100,
                ],
                'range' => [
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                    'px' => [
                        'min' => 0,
                        'max' => 500,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-img-accordion-hover-bg' => 'height: {{SIZE}}{{UNIT}};top:calc((100% - {{SIZE}}{{UNIT}})/2);left:calc((100% - {{overlay_width.SIZE}}{{overlay_width.UNIT}})/2);',
                    '{{WRAPPER}} .king-addons-img-accordion-hover-bg[class*="-top"]' => 'top:calc((100% - {{SIZE}}{{UNIT}})/2);left:calc((100% - {{overlay_width.SIZE}}{{overlay_width.UNIT}})/2);',
                    '{{WRAPPER}} .king-addons-img-accordion-hover-bg[class*="-bottom"]' => 'bottom:calc((100% - {{SIZE}}{{UNIT}})/2);left:calc((100% - {{overlay_width.SIZE}}{{overlay_width.UNIT}})/2);',
                    '{{WRAPPER}} .king-addons-img-accordion-hover-bg[class*="-right"]' => 'top:calc((100% - {{SIZE}}{{UNIT}})/2);right:calc((100% - {{overlay_width.SIZE}}{{overlay_width.UNIT}})/2);',
                    '{{WRAPPER}} .king-addons-img-accordion-hover-bg[class*="-left"]' => 'top:calc((100% - {{SIZE}}{{UNIT}})/2);left:calc((100% - {{overlay_width.SIZE}}{{overlay_width.UNIT}})/2);',
                ],
                'separator' => 'after',
            ]
        );

        $this->add_control(
            'overlay_animation',
            [
                'label' => esc_html__('Select Animation', 'king-addons'),
                'type' => 'king-addons-animations-alt',
                'default' => 'fade-in',
            ]
        );


        Core::renderUpgradeProNotice($this, Controls_Manager::RAW_HTML, 'image-accordion', 'overlay_animation', ['pro-slrt', 'pro-slxrt', 'pro-slbt', 'pro-sllt', 'pro-sltp', 'pro-slxlt', 'pro-sktp', 'pro-skrt', 'pro-skbt', 'pro-sklt', 'pro-scup', 'pro-scdn', 'pro-rllt', 'pro-rlrt']);

        $this->add_control(
            'overlay_animation_duration',
            [
                'label' => esc_html__('Animation Duration', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'default' => 0.3,
                'min' => 0,
                'max' => 5,
                'step' => 0.1,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-img-accordion-hover-bg' => 'transition-duration: {{VALUE}}s;'
                ],
                'condition' => [
                    'overlay_animation!' => 'none',
                ],
            ]
        );

        $this->add_control(
            'overlay_animation_delay',
            [
                'label' => esc_html__('Animation Delay', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'default' => 0,
                'min' => 0,
                'max' => 5,
                'step' => 0.1,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-animation-wrap:hover .king-addons-img-accordion-hover-bg' => 'transition-delay: {{VALUE}}s;'
                ],
                'condition' => [
                    'overlay_animation!' => 'none',
                ],
            ]
        );

        $this->add_control(
            'overlay_animation_timing',
            [
                'label' => esc_html__('Animation Timing', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => Core::getAnimationTimings(),
                'default' => 'ease-default',
                'condition' => [
                    'overlay_animation!' => 'none',
                ],
            ]
        );


        Core::renderUpgradeProNotice($this, Controls_Manager::RAW_HTML, 'image accordion', 'overlay_animation_timing', Core::getAnimationTimingsConditionsPro());

        $this->add_control(
            'overlay_animation_size',
            [
                'label' => esc_html__('Animation Size', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'small' => esc_html__('Small', 'king-addons'),
                    'medium' => esc_html__('Medium', 'king-addons'),
                    'large' => esc_html__('Large', 'king-addons'),
                ],
                'default' => 'large',
                'condition' => [
                    'overlay_animation!' => 'none',
                ],
            ]
        );

        $this->add_control(
            'overlay_animation_tr',
            [
                'label' => esc_html__('Animation Transparency', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'return_value' => 'yes',
                'condition' => [
                    'overlay_animation!' => 'none',
                ],
            ]
        );

        $this->end_controls_section();


        $this->start_controls_section(
            'section_image_effects',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Image Effects', 'king-addons'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control_image_effects();


        Core::renderUpgradeProNotice($this, Controls_Manager::RAW_HTML, 'image-accordion', 'image_effects', ['pro-zi', 'pro-zo', 'pro-go', 'pro-bo']);

        $this->add_control(
            'image_effects_duration',
            [
                'label' => esc_html__('Animation Duration', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'default' => 0.5,
                'min' => 0,
                'max' => 5,
                'step' => 0.1,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-image-accordion-item .king-addons-accordion-background' => 'transition-duration: {{VALUE}}s;'
                ],
                'condition' => [
                    'image_effects!' => 'none',
                ],
            ]
        );

        $this->add_control(
            'image_effects_delay',
            [
                'label' => esc_html__('Animation Delay', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'default' => 0,
                'min' => 0,
                'max' => 5,
                'step' => 0.1,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-image-accordion-item:hover>div' => 'transition-delay: {{VALUE}}s;'
                ],
                'condition' => [
                    'image_effects!' => 'none',
                ],
            ]
        );

        $this->add_control(
            'image_effects_animation_timing',
            [
                'label' => esc_html__('Animation Timing', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => Core::getAnimationTimings(),
                'default' => 'ease-default',
                'condition' => [
                    'image_effects!' => 'none',
                ],
            ]
        );


        Core::renderUpgradeProNotice($this, Controls_Manager::RAW_HTML, 'image-accordion', 'image_effects_animation_timing', Core::getAnimationTimingsConditionsPro());

        $this->add_control(
            'image_effects_size',
            [
                'label' => esc_html__('Animation Size', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'small' => esc_html__('Small', 'king-addons'),
                    'medium' => esc_html__('Medium', 'king-addons'),
                    'large' => esc_html__('Large', 'king-addons'),
                ],
                'default' => 'medium',
                'condition' => [
                    'image_effects!' => ['none', 'slide'],
                ]
            ]
        );

        $this->add_control(
            'image_effects_direction',
            [
                'label' => esc_html__('Animation Direction', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'top' => esc_html__('Top', 'king-addons'),
                    'right' => esc_html__('Right', 'king-addons'),
                    'bottom' => esc_html__('Bottom', 'king-addons'),
                    'left' => esc_html__('Left', 'king-addons'),
                ],
                'default' => 'bottom',
                'condition' => [
                    'image_effects!' => 'none',
                    'image_effects' => 'slide'
                ]
            ]
        );

        $this->end_controls_section();

        $this->add_section_lightbox_popup();


        Core::renderProFeaturesSection($this, '', Controls_Manager::RAW_HTML, 'image-accordion', [
            'Add Unlimited Images',
            'Trigger Images on Click',
            'Enable Image Lightbox',
            'Vertical Accordion Layout',
            'Image Effects: Zoom, Grayscale, Blur',
            'Image Overlay Blend Mode',
            'Skew Images by Default',
            'Advanced Elements Positioning'
        ]);


        $this->start_controls_section(
            'section_style_overlay',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Media Overlay', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            ]
        );

        $this->add_control_overlay_color();

        $this->add_control_overlay_blend_mode();

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'btn_box_shadow_1',
                'selector' => '{{WRAPPER}} .king-addons-image-accordion-item',
            ]
        );

        $this->add_control(
            'overlay_radius',
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
                    '{{WRAPPER}} .king-addons-img-accordion-hover-bg' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->end_controls_section();


        $this->start_controls_section(
            'section_style_title',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Title', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            ]
        );


        $this->add_control(
            'title_color',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#FFFFFF',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-img-accordion-item-title .inner-block a' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'title_bg_color',
            [
                'label' => esc_html__('Background Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-img-accordion-item-title .inner-block a' => 'background-color: {{VALUE}}',
                ]
            ]
        );

        $this->add_control(
            'title_border_color',
            [
                'label' => esc_html__('Border Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#E8E8E8',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-img-accordion-item-title .inner-block a' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .king-addons-img-accordion-item-title a'
            ]
        );

        $this->add_control(
            'title_transition_duration',
            [
                'label' => esc_html__('Transition Duration (seconds)', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'default' => 0.2,
                'min' => 0,
                'max' => 5,
                'step' => 0.1,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-img-accordion-item-title .inner-block a' => 'transition-duration: {{VALUE}}s',
                    '{{WRAPPER}} .king-addons-img-accordion-item-title .king-addons-pointer-item:before' => 'transition-duration: {{VALUE}}s',
                    '{{WRAPPER}} .king-addons-img-accordion-item-title .king-addons-pointer-item:after' => 'transition-duration: {{VALUE}}s',
                ],
            ]
        );

        $this->add_control(
            'title_border_type',
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
                    '{{WRAPPER}} .king-addons-img-accordion-item-title .inner-block a' => 'border-style: {{VALUE}};',
                ],
                'render_type' => 'template',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'title_border_width',
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
                    '{{WRAPPER}} .king-addons-img-accordion-item-title .inner-block a' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'render_type' => 'template',
                'condition' => [
                    'title_border_type!' => 'none',
                ],
            ]
        );

        $this->add_responsive_control(
            'title_padding',
            [
                'label' => esc_html__('Padding', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'default' => [
                    'top' => 0,
                    'right' => 0,
                    'bottom' => 0,
                    'left' => 0,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-img-accordion-item-title .inner-block a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'render_type' => 'template',
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'title_margin',
            [
                'label' => esc_html__('Margin', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'default' => [
                    'top' => 0,
                    'right' => 0,
                    'bottom' => 0,
                    'left' => 0,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-img-accordion-item-title .inner-block' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'render_type' => 'template',
            ]
        );

        $this->end_controls_section();


        $this->start_controls_section(
            'section_style_description',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Description', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            ]
        );

        $this->add_control(
            'description_color',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#DDDDDD',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-img-accordion-item-description .inner-block' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'description_bg_color',
            [
                'label' => esc_html__('Background Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-img-accordion-item-description .inner-block' => 'background-color: {{VALUE}}',
                ]
            ]
        );

        $this->add_control(
            'description_border_color',
            [
                'label' => esc_html__('Border Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#E8E8E8',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-img-accordion-item-description .inner-block' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'description_typography',
                'selector' => '{{WRAPPER}} .king-addons-img-accordion-item-description'
            ]
        );

        $this->add_responsive_control(
            'description_width',
            [
                'label' => esc_html__('Description Width', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['%'],
                'range' => [
                    '%' => [
                        'min' => 10,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => '%',
                    'size' => 100,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-img-accordion-item-description .inner-block' => 'width: {{SIZE}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'description_border_type',
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
                    '{{WRAPPER}} .king-addons-img-accordion-item-description .inner-block' => 'border-style: {{VALUE}};',
                ],
                'render_type' => 'template',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'description_border_width',
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
                    '{{WRAPPER}} .king-addons-img-accordion-item-description .inner-block' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'render_type' => 'template',
                'condition' => [
                    'description_border_type!' => 'none',
                ],
            ]
        );

        $this->add_responsive_control(
            'description_padding',
            [
                'label' => esc_html__('Padding', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'default' => [
                    'top' => 0,
                    'right' => 0,
                    'bottom' => 0,
                    'left' => 0,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-img-accordion-item-description .inner-block' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'render_type' => 'template',
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'description_margin',
            [
                'label' => esc_html__('Margin', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'default' => [
                    'top' => 0,
                    'right' => 0,
                    'bottom' => 0,
                    'left' => 0,
                ],
                'render_type' => 'template',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-img-accordion-item-description .inner-block' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();


        $this->start_controls_section(
            'accordion_button_styles',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Button', 'king-addons'),

                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->start_controls_tabs('tabs_accordion_button_style');

        $this->start_controls_tab(
            'tab_accordion_button_normal',
            [
                'label' => esc_html__('Normal', 'king-addons'),
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'button_bg_color',
                'label' => esc_html__('Background', 'king-addons'),
                'types' => ['classic', 'gradient'],
                'fields_options' => [
                    'background' => [
                        'default' => 'classic',
                    ],
                    'color' => [
                        'default' => '#5B03FF',
                    ],
                ],
                'selector' => '{{WRAPPER}} .king-addons-img-accordion-item-button .inner-block a'
            ]
        );

        $this->add_control(
            'button_color',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#FFFFFF',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-img-accordion-item-button .inner-block a' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'button_border_color',
            [
                'label' => esc_html__('Border Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#E8E8E8',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-img-accordion-item-button .inner-block a' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'button_typography',
                'selector' => '{{WRAPPER}} .king-addons-img-accordion-item-button a'
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'button_box_shadow',
                'selector' => '{{WRAPPER}} .king-addons-img-accordion-item-button .inner-block a',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_accordion_button_hover',
            [
                'label' => esc_html__('Hover', 'king-addons'),
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'button_bg_color_hr',
                'label' => esc_html__('Background', 'king-addons'),
                'types' => ['classic', 'gradient'],
                'fields_options' => [
                    'color' => [
                        'default' => '#000000',
                    ],
                    'background' => [
                        'default' => 'classic',
                    ]
                ],
                'selector' => '{{WRAPPER}} .king-addons-img-accordion-item-button .inner-block a.king-addons-button-none:hover, {{WRAPPER}} .king-addons-img-accordion-item-button .inner-block a:before, {{WRAPPER}} .king-addons-img-accordion-item-button .inner-block a:after'
            ]
        );

        $this->add_control(
            'button_color_hr',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-img-accordion-item-button .inner-block a:hover' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'button_border_color_hr',
            [
                'label' => esc_html__('Border Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#E8E8E8',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-img-accordion-item-button .inner-block a:hover' => 'border-color: {{VALUE}}',
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'button_box_shadow_hr',
                'selector' => '{{WRAPPER}} .king-addons-img-accordion-item-button .inner-block :hover a',
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_control(
            'button_divider',
            [
                'type' => Controls_Manager::DIVIDER,
                'style' => 'thick',
            ]
        );

        $this->add_control_button_animation();

        $this->add_control(
            'button_transition_duration',
            [
                'label' => esc_html__('Transition Duration (ms)', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'default' => 300,
                'min' => 0,
                'step' => 50,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-img-accordion-item-button .inner-block a' => 'transition-duration: {{VALUE}}ms',
                    '{{WRAPPER}} .king-addons-img-accordion-item-button .inner-block a:before' => 'transition-duration: {{VALUE}}ms',
                    '{{WRAPPER}} .king-addons-img-accordion-item-button .inner-block a:after' => 'transition-duration: {{VALUE}}ms',
                ],
            ]
        );

        $this->add_control_button_animation_height();

        if (!king_addons_freemius()->can_use_premium_code__premium_only()) {
            $this->add_control(
                'team_member_pro_notice_2',
                [
                    'type' => Controls_Manager::RAW_HTML,
                    'raw' => 'Advanced button animations are available in the <strong><a href="https://kingaddons.com/pricing/?utm_source=kng-module-image-accordion-settings-upgrade-pro&utm_medium=plugin&utm_campaign=kng" target="_blank">Pro version</a></strong>',
                    'content_classes' => 'king-addons-pro-notice',
                ]
            );
        }

        $this->add_control(
            'button_border_type',
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
                    '{{WRAPPER}} .king-addons-img-accordion-item-button .inner-block a' => 'border-style: {{VALUE}};',
                ],
                'render_type' => 'template',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'button_border_width',
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
                    '{{WRAPPER}} .king-addons-img-accordion-item-button .inner-block a' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'render_type' => 'template',
                'condition' => [
                    'button_border_type!' => 'none',
                ],
            ]
        );

        $this->add_control(
            'button_icon_spacing',
            [
                'label' => esc_html__('Extra Icon Spacing', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 25,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 5,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-img-accordion-item-button .king-addons-img-accordion-extra-icon-left' => 'padding-right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .king-addons-img-accordion-item-button .king-addons-img-accordion-extra-icon-right' => 'padding-left: {{SIZE}}{{UNIT}};',
                ],
                'separator' => 'before'
            ]
        );

        $this->add_responsive_control(
            'button_padding',
            [
                'label' => esc_html__('Padding', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'default' => [
                    'top' => 7,
                    'right' => 18,
                    'bottom' => 8,
                    'left' => 18,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-img-accordion-item-button .inner-block a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'render_type' => 'template',
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'button_margin',
            [
                'label' => esc_html__('Margin', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'default' => [
                    'top' => 15,
                    'right' => 0,
                    'bottom' => 0,
                    'left' => 0,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-img-accordion-item-button .inner-block' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'render_type' => 'template',
            ]
        );

        $this->add_control(
            'button_radius',
            [
                'label' => esc_html__('Border Radius', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'default' => [
                    'top' => 3,
                    'right' => 3,
                    'bottom' => 3,
                    'left' => 3,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-img-accordion-item-button .inner-block a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .king-addons-img-accordion-item-button .inner-block a:before' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->end_controls_section();

        $this->add_section_lightbox_styles();


        $this->start_controls_section(
            'section_style_separator2',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Separator', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            ]
        );

        $this->add_control(
            'separator2_color',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#5B03FF',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-img-accordion-sep-style-2 .inner-block > span' => 'border-bottom-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'separator2_width',
            [
                'label' => esc_html__('Width', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 300,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => '%',
                    'size' => 20,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-img-accordion-sep-style-2:not(.king-addons-img-accordion-item-display-inline) .inner-block > span' => 'width: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .king-addons-img-accordion-sep-style-2.king-addons-img-accordion-item-display-inline' => 'width: {{SIZE}}{{UNIT}};',
                ],
                'render_type' => 'template',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'separator2_height',
            [
                'label' => esc_html__('Height', 'king-addons'),
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
                    'size' => 2,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-img-accordion-sep-style-2 .inner-block > span' => 'border-bottom-width: {{SIZE}}{{UNIT}};',
                ],
                'render_type' => 'template',
            ]
        );

        $this->add_control(
            'separator2_border_type',
            [
                'label' => esc_html__('Type', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'solid' => esc_html__('Solid', 'king-addons'),
                    'double' => esc_html__('Double', 'king-addons'),
                    'dotted' => esc_html__('Dotted', 'king-addons'),
                    'dashed' => esc_html__('Dashed', 'king-addons'),
                    'groove' => esc_html__('Groove', 'king-addons'),
                ],
                'default' => 'solid',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-img-accordion-sep-style-2 .inner-block > span' => 'border-bottom-style: {{VALUE}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'separator2_margin',
            [
                'label' => esc_html__('Margin', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'default' => [
                    'top' => 15,
                    'right' => 0,
                    'bottom' => 15,
                    'left' => 0,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-img-accordion-sep-style-2 .inner-block' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'render_type' => 'template',
            ]
        );

        $this->add_control(
            'separator2_radius',
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
                    '{{WRAPPER}} .king-addons-img-accordion-sep-style-2 .inner-block > span' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->end_controls_section();
    }


    public function get_elements_by_location($location, $settings, $item)
    {
        $premium = king_addons_freemius()->can_use_premium_code__premium_only();
        $locations = [];
        foreach ($settings['accordion_elements'] as $data) {
            $place = 'over';
            $align_vr = $premium ? $data['element_align_vr'] : 'middle';
            if (!isset($locations[$place])) $locations[$place] = [];
            if (!isset($locations[$place][$align_vr])) $locations[$place][$align_vr] = [];
            $locations[$place][$align_vr][] = $data;
        }
        if (!empty($locations[$location])) {
            if ('over' === $location) {
                foreach ($locations[$location] as $align => $elements) {
                    if ('middle' === $align) echo '<div class="king-addons-cv-container"><div class="king-addons-cv-outer"><div class="king-addons-cv-inner">';
                    echo '<div class="king-addons-img-accordion-media-hover-' . $align . ' elementor-clearfix">';
                    foreach ($elements as $data) {
                        $class = 'king-addons-img-accordion-item-' . $data['element_select'];
                        $class .= ' elementor-repeater-item-' . $data['_id'];
                        $class .= ' king-addons-img-accordion-item-display-' . $data['element_display'];
                        $class .= $premium
                            ? ' king-addons-img-accordion-item-align-' . $data['element_align_hr']
                            : ' king-addons-img-accordion-item-align-center';
                        $class .= $this->get_animation_class($data, 'element');
                        $this->get_elements($data['element_select'], $data, $class, $item);
                    }
                    echo '</div>';
                    if ('middle' === $align) echo '</div></div></div>';
                }
            }
        }
    }

    public function render_media_overlay($settings)
    {
        echo '<div class="king-addons-img-accordion-hover-bg ' . $this->get_animation_class($settings, 'overlay') . '"></div>';
    }

    public function get_animation_class($data, $object)
    {
        $class = '';
        /** @noinspection DuplicatedCode */
        if ('none' !== $data[$object . '_animation']) {
            $class .= ' king-addons-' . $object . '-' . $data[$object . '_animation'];
            $class .= ' king-addons-anim-size-' . $data[$object . '_animation_size'];
            $class .= ' king-addons-animation-timing-' . $data[$object . '_animation_timing'];
            if ('yes' === $data[$object . '_animation_tr']) {
                $class .= ' king-addons-anim-transparency';
            }
        }
        return $class;
    }

    public function render_repeater_title($settings, $class, $item)
    {
        if (!empty($item['accordion_item_title'])) {
            $tags_whitelist = ['h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'div', 'span', 'p'];
            $element_title_tag = Core::validateHTMLTags($settings['element_title_tag'], 'h2', $tags_whitelist);
            echo '<' . $element_title_tag . ' class="' . esc_attr($class) . '">';
            echo '<div class="inner-block"><a class="king-addons-pointer-item">';
            echo $item['accordion_item_title'];
            echo '</a></div></' . $element_title_tag . '>';
        }
    }

    public function render_repeater_description($class, $item)
    {
        if ('' === $item['accordion_item_description']) return;
        echo '<div class="' . esc_attr($class) . '"><div class="inner-block"><p>' . $item['accordion_item_description'] . '</p></div></div>';
    }

    public function render_repeater_button($settings, $class, $item)
    {
        echo '<div class="' . esc_attr($class) . '"><div class="inner-block">';
        echo '<a ' . $this->get_render_attribute_string('accordion_btn_url' . $item['_id']) . ' class="king-addons-button-effect ' . $this->get_settings_for_display()['button_animation'] . '">';
        if ('before' === $settings['element_extra_icon_pos']) {
            echo '<i class="king-addons-img-accordion-extra-icon-left ' . esc_attr($settings['element_extra_icon']['value']) . '"></i>';
        }
        echo '<span>' . esc_html($item['element_button_text']) . '</span>';
        if ('after' === $settings['element_extra_icon_pos']) {
            echo '<i class="king-addons-img-accordion-extra-icon-right ' . esc_attr($settings['element_extra_icon']['value']) . '"></i>';
        }
        echo '</a></div></div>';
    }

    public function render_repeater_separator($class)
    {
        echo '<div class="' . esc_attr($class) . ' king-addons-img-accordion-sep-style-2"><div class="inner-block"><span></span></div></div>';
    }

    public function render_repeater_lightbox($settings, $class, $item)
    {
        echo '<div class="' . esc_attr($class) . '"><div class="inner-block">';
        $lightbox_source = $this->item_bg_image_url;
        echo '<div style="opacity:0;" class="king-addons-accordion-image-wrap" data-src="' . esc_attr($lightbox_source) . '">';
        echo '<img src="' . esc_url($lightbox_source) . '" alt="' . esc_attr($item['accordion_item_title']) . '"></div>';
        echo '<span data-src="' . esc_url($lightbox_source) . '">';
        if ('before' === $settings['element_extra_text_pos']) {
            echo '<span class="king-addons-img-accordion-extra-text-left">' . esc_html($settings['element_extra_text']) . '</span>';
        }
        if (!empty($settings['element_lightbox_icon'])) {
            echo '<i class="' . esc_attr($settings['element_lightbox_icon']['value']) . '"></i>';
        }
        if ('after' === $settings['element_extra_text_pos']) {
            echo '<span class="king-addons-img-accordion-extra-text-right">' . esc_html($settings['element_extra_text']) . '</span>';
        }
        echo '</span></div></div>';
    }

    public function get_elements($type, $settings, $class, $item)
    {
        switch ($type) {
            case 'title':
                $this->render_repeater_title($settings, $class, $item);
                break;
            case 'description':
                $this->render_repeater_description($class, $item);
                break;
            case 'button':
                $this->render_repeater_button($settings, $class, $item);
                break;
            case 'lightbox':
                $this->render_repeater_lightbox($settings, $class, $item);
                break;
            case 'separator':
                $this->render_repeater_separator($class);
                break;
            default:
                break;
        }
    }

    public function get_image_effect_class($settings)
    {
        $premium = king_addons_freemius()->can_use_premium_code__premium_only();
        if (!$premium) {
            if (in_array($settings['image_effects'], ['pro-zi', 'pro-zo', 'pro-go', 'pro-bo'])) {
                $settings['image_effects'] = 'none';
            }
        }
        $class = '';
        /** @noinspection DuplicatedCode */
        if ('none' !== $settings['image_effects']) {
            $class .= ' king-addons-' . $settings['image_effects'];
        }
        if ('slide' !== $settings['image_effects']) {
            $class .= ' king-addons-effect-size-' . $settings['image_effects_size'];
        } else {
            $class .= ' king-addons-effect-dir-' . $settings['image_effects_direction'];
        }
        return $class;
    }

    protected function render()
    {
        $premium = king_addons_freemius()->can_use_premium_code__premium_only();
        $settings = $this->get_settings_for_display();
        if (!$premium) {
            $settings['lightbox_popup_thumbnails'] = '';
            $settings['lightbox_popup_thumbnails_default'] = '';
            $settings['lightbox_popup_sharing'] = '';
        }
        if ($premium) {
            $lightbox_settings = [
                'selector' => '.king-addons-accordion-image-wrap',
                'iframeMaxWidth' => '60%',
                'hash' => false,
                'autoplay' => $settings['lightbox_popup_autoplay'],
                'pause' => $settings['lightbox_popup_pause'] * 1000,
                'progressBar' => $settings['lightbox_popup_progressbar'],
                'counter' => $settings['lightbox_popup_counter'],
                'controls' => $settings['lightbox_popup_arrows'],
                'getCaptionFromTitleOrAlt' => $settings['lightbox_popup_captions'],
                'thumbnail' => $settings['lightbox_popup_thumbnails'],
                'showThumbByDefault' => $settings['lightbox_popup_thumbnails_default'],
                'share' => $settings['lightbox_popup_sharing'],
                'zoom' => $settings['lightbox_popup_zoom'],
                'fullScreen' => $settings['lightbox_popup_fullscreen'],
                'download' => $settings['lightbox_popup_download']
            ];
            $this->add_render_attribute('lightbox-settings', ['lightbox' => wp_json_encode($lightbox_settings)]);
        }
        $no_column = ($settings['accordion_direction'] == 'column' && !$premium) ? ' king-addons-acc-no-column' : '';
        ?>
    <div class="king-addons-image-accordion-wrap <?php echo $no_column; ?>">
        <?php if (!$premium) : ?>
        <div class="king-addons-image-accordion">
        <?php else : ?>
        <div class="king-addons-image-accordion" <?php echo $this->get_render_attribute_string('lightbox-settings'); ?>>
    <?php endif; ?>
        <?php
        foreach ($settings['accordion_items'] as $key => $item) :
            if (!$premium && $key === 3) break;
            if (!empty($item['accordion_item_bg_image']['id'])) {
                $image_url = Group_Control_Image_Size::get_attachment_image_src($item['accordion_item_bg_image']['id'], 'accordion_image_size', $settings);
                $this->item_bg_image_url = esc_url_raw($image_url);
            } elseif (!empty($item['accordion_item_bg_image']['url'])) {
                $this->item_bg_image_url = esc_url_raw($item['accordion_item_bg_image']['url']);
            } else {
                $this->item_bg_image_url = esc_url(Utils::get_placeholder_image_src());
            }
            $layout['activeItem'] = [
                'activeWidth' => $settings['accordion_active_item_style']['size'],
                'defaultActive' => $settings['default_active'],
                'interaction' => $premium ? $settings['accordion_interaction'] : 'hover',
                'overlayLink' => ('yes' === $item['wrapper_link'] && isset($item['accordion_btn_url'])) ? $item['accordion_btn_url']['url'] : '',
                'overlayLinkTarget' => (isset($item['accordion_btn_url']) && $item['accordion_btn_url']['is_external'] === 'on') ? '_blank' : '_self'
            ];
            $this->add_render_attribute('accordion-settings' . $key, [
                'class' => ['king-addons-img-accordion-media-hover', 'king-addons-animation-wrap'],
                'data-settings' => wp_json_encode($layout),
                'data-src' => $this->item_bg_image_url
            ]);
            $render_attribute = $this->get_render_attribute_string('accordion-settings' . $key);
            if (!empty($item['accordion_btn_url']['url'])) {
                $this->add_link_attributes('accordion_btn_url' . esc_attr($item['_id']), $item['accordion_btn_url']);
            }
            ?>
            <div data-src="<?php echo esc_url($this->item_bg_image_url); ?>"
                 class="king-addons-image-accordion-item elementor-repeater-item-<?php echo esc_attr($item['_id']) . $this->get_image_effect_class($settings); ?>">
                <div class="king-addons-accordion-background"
                     style="background-image:url(<?php echo esc_attr($this->item_bg_image_url); ?>);"></div>
                <?php
                echo '<div ' . $render_attribute . '>';
                $this->render_media_overlay($settings);
                $this->get_elements_by_location('over', $settings, $item);
                echo '</div>';
                ?>
            </div>
        <?php endforeach; ?>
        </div>
        </div>
        <?php
    }
}