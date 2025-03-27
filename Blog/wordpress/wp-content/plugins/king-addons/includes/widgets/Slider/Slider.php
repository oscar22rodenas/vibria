<?php

namespace King_Addons;

use Elementor;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Repeater;
use Elementor\Icons_Manager;
use Elementor\Modules\DynamicTags\Module as TagsModule;

if (!defined('ABSPATH')) {
    exit;
}

class Slider extends Widget_Base
{

    public function get_name()
    {
        return 'king-addons-slider';
    }

    public function get_title()
    {
        return esc_html__('Slider/Carousel', 'king-addons');
    }

    public function get_icon()
    {
        return 'king-addons-icon king-addons-slider';
    }

    public function get_categories()
    {
        return ['king-addons'];
    }

    public function get_keywords()
    {
        return ['king addons', 'king', 'addons', 'kingaddons', 'king-addons', 'image', 'images', 'media', 'content',
            'slider', 'carousel', 'post', 'posts', 'image slider', 'slideshow', 'slick',
            'image carousel', 'template slider', 'posts slider'];
    }

    public function get_script_depends()
    {
        return [
            KING_ADDONS_ASSETS_UNIQUE_KEY . '-imagesloaded-imagesloaded',
            KING_ADDONS_ASSETS_UNIQUE_KEY . '-slick-slick',
            KING_ADDONS_ASSETS_UNIQUE_KEY . '-slider-script'
        ];
    }

    public function get_style_depends()
    {
        return [
            KING_ADDONS_ASSETS_UNIQUE_KEY . '-slick-helper',
            KING_ADDONS_ASSETS_UNIQUE_KEY . '-animation-general',
            KING_ADDONS_ASSETS_UNIQUE_KEY . '-animation-timing',
            KING_ADDONS_ASSETS_UNIQUE_KEY . '-slider-style',
            KING_ADDONS_ASSETS_UNIQUE_KEY . '-general-general',
        ];
    }

    public function get_custom_help_url()
    {
        return 'https://kingaddons.com/';
    }

    public function add_control_slider_effect()
    {
        $this->add_control(
            'slider_effect',
            [
                'type' => Controls_Manager::SELECT,
                'label' => esc_html__('Effect', 'king-addons'),
                'default' => 'slide',
                'options' => [
                    'slide' => esc_html__('Slide', 'king-addons'),
                    'sl_vl' => esc_html__('Sl Vertical (Pro)', 'king-addons'),
                    'fade' => esc_html__('Fade', 'king-addons'),
                ],
                'separator' => 'before'
            ]
        );
    }

    public function add_control_slider_nav_hover()
    {
        $this->add_control(
            'slider_nav_hover',
            [
                'label' => sprintf(__('Show on Hover %s', 'king-addons'), '<i class="eicon-pro-icon"></i>'),
                'type' => Controls_Manager::SWITCHER,
                'classes' => 'king-addons-pro-control no-distance'
            ]
        );
    }

    public function add_control_slider_dots_layout()
    {
        $this->add_control(
            'slider_dots_layout',
            [
                'label' => esc_html__('Pagination Layout', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'horizontal',
                'options' => [
                    'horizontal' => esc_html__('Horizontal', 'king-addons'),
                    'pro-vr' => esc_html__('Vertical (Pro)', 'king-addons'),
                ],
                'prefix_class' => 'king-addons-slider-dots-',
                'render_type' => 'template',
            ]
        );
    }

    public function add_control_slider_autoplay()
    {
        $this->add_control(
            'slider_autoplay',
            [
                'label' => sprintf(__('Autoplay %s', 'king-addons'), '<i class="eicon-pro-icon"></i>'),
                'type' => Controls_Manager::SWITCHER,
                'separator' => 'before',
                'classes' => 'king-addons-pro-control'
            ]
        );
    }

    public function add_control_slider_autoplay_duration()
    {
    }

    public function add_control_slider_pause_on_hover()
    {
        $this->add_control(
            'pause_on_hover',
            [
                'label' => sprintf(__('Pause on Hover %s', 'king-addons'), '<i class="eicon-pro-icon"></i>'),
                'type' => Controls_Manager::SWITCHER,
                'classes' => 'king-addons-pro-control no-distance'
            ]
        );
    }

    public function add_control_slider_scroll_btn()
    {
        $this->add_control(
            'slider_scroll_btn',
            [
                'label' => sprintf(__('Scroll to Section Button %s', 'king-addons'), '<i class="eicon-pro-icon"></i>'),
                'type' => Controls_Manager::SWITCHER,
                'separator' => 'before',
                'classes' => 'king-addons-pro-control'
            ]
        );
    }

    public function add_repeater_args_slider_item_bg_kenburns()
    {
        return [
            'label' => sprintf(__('Ken Burn Effect %s', 'king-addons'), '<i class="eicon-pro-icon"></i>'),
            'type' => Controls_Manager::SWITCHER,
            'separator' => 'before',
            'conditions' => [
                'terms' => [
                    [
                        'name' => 'slider_item_bg_image[url]',
                        'operator' => '!=',
                        'value' => '',
                    ],
                ],
            ],
            'classes' => 'king-addons-pro-control'
        ];
    }

    public function add_repeater_args_slider_item_bg_zoom()
    {
        return [
            'type' => Controls_Manager::HIDDEN,
            'default' => ''
        ];
    }

    public function add_repeater_args_slider_content_type()
    {
        return [
            'custom' => esc_html__('Custom', 'king-addons'),
            'pro-tm' => esc_html__('Elementor Template (Pro)', 'king-addons'),
        ];
    }

    public function add_repeater_args_slider_select_template()
    {
        return [
            'type' => Controls_Manager::HIDDEN,
            'default' => ''
        ];
    }

    public function add_repeater_args_slider_item_link_type()
    {
        return [
            'label' => esc_html__('Link Type', 'king-addons'),
            'type' => Controls_Manager::SELECT,
            'default' => 'none',
            'options' => [
                'none' => esc_html__('None', 'king-addons'),
                'pro-cstm' => esc_html__('Custom URL (Pro)', 'king-addons'),
                'pro-yt' => esc_html__('Youtube (Pro)', 'king-addons'),
                'pro-vm' => esc_html__('Vimeo (Pro)', 'king-addons'),
                'pro-md' => esc_html__('Custom Video (Pro)', 'king-addons')
            ],
            'condition' => [
                'slider_content_type' => 'custom'
            ],
            'separator' => 'before'
        ];
    }

    public function add_section_style_scroll_btn()
    {
    }

    public function add_control_slider_amount()
    {
        $this->add_responsive_control(
            'slider_amount',
            [
                'label' => esc_html__('Columns (Carousel)', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'label_block' => false,
                'default' => 1,
                'widescreen_default' => 1,
                'laptop_default' => 1,
                'tablet_extra_default' => 1,
                'tablet_default' => 1,
                'mobile_extra_default' => 1,
                'mobile_default' => 1,
                'options' => [
                    1 => esc_html__('One', 'king-addons'),
                    2 => esc_html__('Two', 'king-addons'),
                    'pro-3' => esc_html__('Three (Pro)', 'king-addons'),
                    'pro-4' => esc_html__('Four (Pro)', 'king-addons'),
                    'pro-5' => esc_html__('Five (Pro)', 'king-addons'),
                    'pro-6' => esc_html__('Six (Pro)', 'king-addons'),
                ],
                'prefix_class' => 'king-addons-adv-slider-columns-%s',
                'render_type' => 'template',
                'frontend_available' => true,
                'separator' => 'before',
                'condition' => [
                    'slider_effect!' => 'slide_vertical'
                ]
            ]
        );
    }

    public function add_control_slides_to_scroll()
    {
        $this->add_control(
            'slides_to_scroll',
            [
                'label' => esc_html__('Slides to Scroll', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 2,
                'prefix_class' => 'king-addons-adv-slides-to-scroll-',
                'render_type' => 'template',
                'frontend_available' => true,
                'default' => 1,
                'condition' => [
                    'slider_effect!' => 'slide_vertical'
                ]
            ]
        );
    }

    public function add_control_stack_slider_nav_position()
    {
    }

    public function add_control_slider_dots_hr()
    {
    }

    protected function register_controls()
    {


        $this->start_controls_section(
            'settings_section_slides',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Slides', 'king-addons'),
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'slider_content_type',
            [
                'label' => esc_html__('Content Type', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'custom',
                'options' => $this->add_repeater_args_slider_content_type(),
                'render_type' => 'template'
            ]
        );

        Core::renderUpgradeProNotice($repeater, Controls_Manager::RAW_HTML, 'slider', 'slider_content_type', ['pro-tm']);

        $repeater->add_control('slider_select_template', $this->add_repeater_args_slider_select_template());

        $repeater->add_control(
            'slider_content_type_divider',
            [
                'type' => Controls_Manager::DIVIDER,
                'style' => 'thick',
            ]
        );

        $repeater->start_controls_tabs('tabs_slider_item');

        $repeater->start_controls_tab(
            'tab_slider_item_background',
            [
                'label' => esc_html__('Background', 'king-addons'),
            ]
        );

        $repeater->add_control(
            'slider_item_bg_image',
            [
                'label' => esc_html__('Image', 'king-addons'),
                'type' => Controls_Manager::MEDIA,
                'dynamic' => [
                    'active' => true,
                ]
            ]
        );

        $repeater->add_control(
            'slider_item_bg_size',
            [
                'label' => esc_html__('Size', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'cover',
                'options' => [
                    'cover' => esc_html__('Cover', 'king-addons'),
                    'contain' => esc_html__('Contain', 'king-addons'),
                    'auto' => esc_html__('Auto', 'king-addons'),
                ],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .king-addons-slider-item-bg' => 'background-size: {{VALUE}}',
                ],


            ]
        );

        $repeater->add_control('slider_item_link_type', $this->add_repeater_args_slider_item_link_type());


        Core::renderUpgradeProNotice($repeater, Controls_Manager::RAW_HTML, 'slider', 'slider_item_link_type', ['pro-cstm', 'pro-yt', 'pro-vm', 'pro-md']);

        $repeater->add_control(
            'vimeo_notice',
            [
                'type' => Controls_Manager::RAW_HTML,
                'raw' => 'Please Upload Background Image',
                'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
                'condition' => [
                    'slider_item_link_type' => 'video-vimeo'
                ]
            ]
        );

        $repeater->add_control(
            'slider_item_bg_image_url',
            [
                'type' => Controls_Manager::URL,
                'dynamic' => [
                    'active' => true,
                ],
                'placeholder' => esc_html__('https://example.com', 'king-addons'),
                'show_label' => false,
                'condition' => [
                    'slider_item_link_type' => 'custom',
                ],
            ]
        );

        $repeater->add_control(
            'hosted_url',
            [
                'label' => esc_html__('Choose File', 'elementor'),
                'type' => Controls_Manager::MEDIA,
                'dynamic' => [
                    'active' => true,
                    'categories' => [
                        TagsModule::MEDIA_CATEGORY,
                    ],
                ],
                'media_type' => 'video',
                'condition' => [
                    'slider_content_type' => 'custom',
                    'slider_item_link_type' => 'video-media',
                ],
                'ai' => [
                    'active' => false,
                ],
            ]
        );

        $repeater->add_control(
            'slider_item_video_src',
            [
                'label' => esc_html__('Video URL', 'king-addons'),
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'placeholder' => esc_html__('https://example.com', 'king-addons'),
                'condition' => [
                    'slider_content_type' => 'custom',
                    'slider_item_link_type' => ['video-youtube', 'video-vimeo'],
                ],
            ]
        );

        $repeater->add_control(
            'slider_item_video_autoplay',
            [
                'label' => esc_html__('Autoplay', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'condition' => [
                    'slider_content_type' => 'custom',
                    'slider_item_link_type' => ['video-youtube', 'video-vimeo', 'video-media'],
                ],
            ]
        );

        $repeater->add_control(
            'slider_item_video_loop',
            [
                'label' => esc_html__('Loop', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'condition' => [
                    'slider_content_type' => 'custom',
                    'slider_item_link_type' => ['video-youtube', 'video-vimeo', 'video-media'],
                ],
            ]
        );

        $repeater->add_control(
            'slider_item_video_mute',
            [
                'label' => esc_html__('Mute', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'condition' => [
                    'slider_content_type' => 'custom',
                    'slider_item_link_type' => ['video-youtube', 'video-vimeo', 'video-media'],
                ],
            ]
        );

        $repeater->add_control(
            'slider_item_video_controls',
            [
                'label' => esc_html__('Controls', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'condition' => [
                    'slider_content_type' => 'custom',
                    'slider_item_link_type' => ['video-youtube', 'video-vimeo', 'video-media'],
                ],
            ]
        );

        $repeater->add_control(
            'slider_item_video_start',
            [
                'label' => esc_html__('Start Time', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'description' => esc_html__('Specify a start time (in seconds)', 'king-addons'),
                'frontend_available' => true,
                'condition' => [
                    'slider_content_type' => 'custom',
                    'slider_item_link_type' => ['video-youtube', 'video-vimeo'],
                    'slider_item_video_loop!' => 'yes',
                ],
            ]
        );

        $repeater->add_control(
            'slider_item_video_end',
            [
                'label' => esc_html__('End Time', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'description' => esc_html__('Specify an end time (in seconds)', 'king-addons'),
                'frontend_available' => true,
                'condition' => [
                    'slider_content_type' => 'custom',
                    'slider_item_link_type' => 'video-youtube',
                    'slider_item_video_loop!' => 'yes',
                ],
            ]
        );

        $repeater->add_control('slider_item_bg_kenburns', $this->add_repeater_args_slider_item_bg_kenburns());

        $repeater->add_control('slider_item_bg_zoom', $this->add_repeater_args_slider_item_bg_zoom());

        $repeater->add_control(
            'overlay_divider',
            [
                'type' => Controls_Manager::DIVIDER,
                'style' => 'thick',
            ]
        );

        $repeater->add_control(
            'slider_item_overlay',
            [
                'label' => esc_html__('Background Overlay', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'render_type' => 'template',
                'condition' => [
                    'slider_content_type' => 'custom'
                ]
            ]
        );

        $repeater->add_control(
            'slider_item_overlay_bg',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => 'rgba(236,64,122,0.8)',
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .king-addons-slider-item-overlay' => 'background-color: {{VALUE}}',
                ],
                'condition' => [
                    'slider_item_overlay' => 'yes',
                    'slider_content_type' => 'custom'
                ],
            ]
        );

        $repeater->add_control(
            'slider_item_blend_mode',
            [
                'label' => esc_html__('Blend Mode', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'normal',
                'options' => [
                    'normal' => esc_html__('Normal', 'king-addons'),
                    'multiply' => esc_html__('Multiply', 'king-addons'),
                    'screen' => esc_html__('Screen', 'king-addons'),
                    'overlay' => esc_html__('Overlay', 'king-addons'),
                    'darken' => esc_html__('Darken', 'king-addons'),
                    'lighten' => esc_html__('Lighten', 'king-addons'),
                    'color-dodge' => esc_html__('Color-dodge', 'king-addons'),
                    'color-burn' => esc_html__('Color-burn', 'king-addons'),
                    'hard-light' => esc_html__('Hard-light', 'king-addons'),
                    'soft-light' => esc_html__('Soft-light', 'king-addons'),
                    'difference' => esc_html__('Difference', 'king-addons'),
                    'exclusion' => esc_html__('Exclusion', 'king-addons'),
                    'hue' => esc_html__('Hue', 'king-addons'),
                    'saturation' => esc_html__('Saturation', 'king-addons'),
                    'color' => esc_html__('Color', 'king-addons'),
                    'luminosity' => esc_html__('luminosity', 'king-addons'),
                ],
                'render_type' => 'template',
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .king-addons-slider-item-overlay' => 'mix-blend-mode: {{VALUE}}',
                ],
                'condition' => [
                    'slider_item_overlay' => 'yes',
                    'slider_content_type' => 'custom'
                ],
            ]
        );

        $repeater->end_controls_tab();

        $repeater->start_controls_tab(
            'tab_slider_item_content',
            [
                'label' => esc_html__('Content', 'king-addons'),
            ]
        );

        $repeater->add_control(
            'slider_show_content',
            [
                'label' => esc_html__('Show Sldier Content', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'separator' => 'after'
            ]
        );

        $repeater->add_control(
            'slider_title_tag',
            [
                'label' => esc_html__('Title HTML Tag', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'h1' => esc_html__('H1', 'king-addons'),
                    'h2' => esc_html__('H2', 'king-addons'),
                    'h3' => esc_html__('H3', 'king-addons'),
                    'h4' => esc_html__('H4', 'king-addons'),
                    'h5' => esc_html__('H5', 'king-addons'),
                    'h6' => esc_html__('H6', 'king-addons'),
                    'div' => 'div',
                    'span' => 'span',
                    'P' => 'p'
                ],
                'default' => 'h2',
                'condition' => [
                    'slider_show_content' => 'yes',
                ]
            ]
        );

        $repeater->add_control(
            'slider_item_title',
            [
                'label' => esc_html__('Title', 'king-addons'),
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => 'Slide Title',
                'condition' => [
                    'slider_show_content' => 'yes',
                ],
            ]
        );

        $repeater->add_control(
            'slider_sub_title_tag',
            [
                'label' => esc_html__('Sub Title HTML Tag', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'h1' => esc_html__('H1', 'king-addons'),
                    'h2' => esc_html__('H2', 'king-addons'),
                    'h3' => esc_html__('H3', 'king-addons'),
                    'h4' => esc_html__('H4', 'king-addons'),
                    'h5' => esc_html__('H5', 'king-addons'),
                    'h6' => esc_html__('H6', 'king-addons'),
                    'div' => 'div',
                    'span' => 'span',
                    'P' => 'p'
                ],
                'default' => 'h3',
                'condition' => [
                    'slider_show_content' => 'yes',
                ]
            ]
        );

        $repeater->add_control(
            'slider_item_sub_title',
            [
                'label' => esc_html__('Sub Title', 'king-addons'),
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => 'Slide Sub Title',
                'condition' => [
                    'slider_show_content' => 'yes',
                ],
            ]
        );

        $repeater->add_control(
            'slider_item_description',
            [
                'label' => esc_html__('Description', 'king-addons'),
                'type' => Controls_Manager::TEXTAREA,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => 'Slider Description Text, Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur laoreet cursus volutpat. Aliquam sit amet ligula et justo tincidunt laoreet non vitae lorem. ',
                'condition' => [
                    'slider_show_content' => 'yes',
                ],
            ]
        );

        $repeater->add_control(
            'slider_item_btn_1_divider',
            [
                'type' => Controls_Manager::DIVIDER,
                'style' => 'thick',
                'condition' => [
                    'slider_show_content' => 'yes',
                ],
            ]
        );

        $repeater->add_responsive_control(
            'slider_item_btn_1',
            [
                'label' => esc_html__('Button Primary', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'widescreen_default' => 'yes',
                'laptop_default' => 'yes',
                'tablet_extra_default' => 'yes',
                'tablet_default' => 'yes',
                'mobile_extra_default' => 'yes',
                'mobile_default' => 'yes',
                'selectors_dictionary' => [
                    '' => 'none',
                    'yes' => 'inline-block'
                ],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .king-addons-slider-primary-btn' => 'display:{{VALUE}};',
                ],
                'condition' => [
                    'slider_show_content' => 'yes',
                ],
                'render_type' => 'template'
            ]
        );

        $repeater->add_control(
            'slider_item_btn_text_1',
            [
                'label' => esc_html__('Text', 'king-addons'),
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => 'Button 1',
                'condition' => [
                    'slider_item_btn_1' => 'yes',
                    'slider_show_content' => 'yes',
                ],
            ]
        );

        $repeater->add_control(
            'slider_item_btn_icon_1',
            [
                'label' => esc_html__('Select Icon', 'king-addons'),
                'type' => Controls_Manager::ICONS,
                'skin' => 'inline',
                'label_block' => false,
                'condition' => [
                    'slider_item_btn_1' => 'yes',
                    'slider_show_content' => 'yes',
                ],
            ]
        );

        $repeater->add_control(
            'slider_item_btn_url_1',
            [
                'label' => esc_html__('Link', 'king-addons'),
                'type' => Controls_Manager::URL,
                'dynamic' => [
                    'active' => true,
                ],
                'label_block' => false,
                'placeholder' => esc_html__('https://example.com', 'king-addons'),
                'condition' => [
                    'slider_item_btn_1' => 'yes',
                    'slider_show_content' => 'yes',
                ],
            ]
        );

        $repeater->add_control(
            'slider_item_btn_2_divider',
            [
                'type' => Controls_Manager::DIVIDER,
                'style' => 'thick',
                'condition' => [
                    'slider_show_content' => 'yes',
                ],
            ]
        );

        $repeater->add_responsive_control(
            'slider_item_btn_2',
            [
                'label' => esc_html__('Button Secondary', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'widescreen_default' => 'yes',
                'laptop_default' => 'yes',
                'tablet_extra_default' => 'yes',
                'tablet_default' => 'yes',
                'mobile_extra_default' => 'yes',
                'mobile_default' => 'yes',
                'selectors_dictionary' => [
                    '' => 'none',
                    'yes' => 'inline-block'
                ],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .king-addons-slider-secondary-btn' => 'display:{{VALUE}};',
                ],
                'condition' => [
                    'slider_show_content' => 'yes',
                ],
                'render_type' => 'template'
            ]
        );

        $repeater->add_control(
            'slider_item_btn_text_2',
            [
                'label' => esc_html__('Text', 'king-addons'),
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => 'Button 2',
                'condition' => [
                    'slider_item_btn_2' => 'yes',
                    'slider_show_content' => 'yes',
                ],
            ]
        );

        $repeater->add_control(
            'slider_item_btn_icon_2',
            [
                'label' => esc_html__('Select Icon', 'king-addons'),
                'type' => Controls_Manager::ICONS,
                'skin' => 'inline',
                'label_block' => false,
                'condition' => [
                    'slider_item_btn_2' => 'yes',
                    'slider_show_content' => 'yes',
                ],
            ]
        );

        $repeater->add_control(
            'slider_item_btn_url_2',
            [
                'label' => esc_html__('Link', 'king-addons'),
                'type' => Controls_Manager::URL,
                'dynamic' => [
                    'active' => true,
                ],
                'label_block' => false,
                'placeholder' => esc_html__('https://example.com', 'king-addons'),
                'condition' => [
                    'slider_item_btn_2' => 'yes',
                    'slider_show_content' => 'yes',
                ],
            ]
        );

        $repeater->end_controls_tab();

        $repeater->end_controls_tabs();

        $this->add_control(
            'slider_items',
            [
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'slider_item_title' => esc_html__('Slide 1 Title', 'king-addons'),
                        'slider_item_sub_title' => esc_html__('Slide 1 Sub Title', 'king-addons'),
                        'slider_item_description' => esc_html__('Slider 1 Description Text, Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur laoreet cursus volutpat. Aliquam sit amet ligula et justo tincidunt laoreet non vitae lorem. ', 'king-addons'),
                        'slider_item_btn_text_1' => esc_html__('Button 1', 'king-addons'),
                        'slider_item_btn_text_2' => esc_html__('Button 2', 'king-addons'),
                        'slider_item_overlay_bg' => '#1300B19C',
                    ],
                    [
                        'slider_item_title' => esc_html__('Slide 2 Title', 'king-addons'),
                        'slider_item_sub_title' => esc_html__('Slide 2 Sub Title', 'king-addons'),
                        'slider_item_description' => esc_html__('Slider 2 Description Text, Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur laoreet cursus volutpat. Aliquam sit amet ligula et justo tincidunt laoreet non vitae lorem. ', 'king-addons'),
                        'slider_item_btn_text_1' => esc_html__('Button 1', 'king-addons'),
                        'slider_item_btn_text_2' => esc_html__('Button 2', 'king-addons'),
                        'slider_item_overlay_bg' => '#0D7C00AB',
                    ],
                    [
                        'slider_item_title' => esc_html__('Slide 3 Title', 'king-addons'),
                        'slider_item_sub_title' => esc_html__('Slide 3 Sub Title', 'king-addons'),
                        'slider_item_description' => esc_html__('Slider 3 Description Text, Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur laoreet cursus volutpat. Aliquam sit amet ligula et justo tincidunt laoreet non vitae lorem. ', 'king-addons'),
                        'slider_item_btn_text_1' => esc_html__('Button 1', 'king-addons'),
                        'slider_item_btn_text_2' => esc_html__('Button 2', 'king-addons'),
                        'slider_item_overlay_bg' => '#D5040094',
                    ],
                ],
                'title_field' => '{{{ slider_item_title }}}',
            ]
        );

        if (!king_addons_freemius()->can_use_premium_code__premium_only()) {
            $this->add_control(
                'slider_repeater_pro_notice',
                [
                    'type' => Controls_Manager::RAW_HTML,
                    'raw' => 'More than 4 Slides are available<br> in the <strong><a href="https://kingaddons.com/pricing/?utm_source=kng-module-slider-settings-upgrade-pro&utm_medium=plugin&utm_campaign=kng" target="_blank">Pro version</a></strong>',

                    'content_classes' => 'king-addons-pro-notice',
                ]
            );
        }

        $this->end_controls_section();


        $this->start_controls_section(
            'settings_section_slider_options',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Settings', 'king-addons'),
                'type' => Controls_Manager::SECTION,
            ]
        );

        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name' => 'slider_image_size',
                'default' => 'full',
            ]
        );

        $this->add_control(
            'slider_image_type',
            [
                'label' => esc_html__('Media Type', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'background',
                'options' => [
                    'background' => esc_html__('Background', 'king-addons'),
                    'image' => esc_html__('Image', 'king-addons')
                ]
            ]
        );

        $this->add_responsive_control(
            'slider_height',
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
                    '{{WRAPPER}} .king-addons-advanced-slider' => 'height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .king-addons-slider-item' => 'height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .slick-list' => 'height: {{SIZE}}{{UNIT}};'
                ],
                'separator' => 'before',
                'condition' => [
                    'slider_image_type' => 'background'
                ]
            ]
        );

        $this->add_control_slider_amount();

        if (!king_addons_freemius()->can_use_premium_code__premium_only()) {
            $this->add_control(
                'slider_columns_pro_notice',
                [
                    'type' => Controls_Manager::RAW_HTML,
                    'raw' => 'Slider Columns option is fully supported<br> in the <strong><a href="https://kingaddons.com/pricing/?utm_source=kng-module-advanced-slider-settings-upgrade-pro&utm_medium=plugin&utm_campaign=kng" target="_blank">Pro version</a></strong>',

                    'content_classes' => 'king-addons-pro-notice',
                ]
            );
        }

        $this->add_control_slides_to_scroll();

        $this->add_control(
            'slides_amount_hidden',
            [
                'type' => Controls_Manager::HIDDEN,
                'prefix_class' => 'king-addons-adv-slider-columns-',
                'default' => 1,
                'condition' => [
                    'slider_effect' => 'slide_vertical'
                ]
            ]
        );

        $this->add_control(
            'slides_to_scroll_hidden',
            [
                'type' => Controls_Manager::HIDDEN,
                'prefix_class' => 'king-addons-adv-slides-to-scroll-',
                'default' => 1,
                'condition' => [
                    'slider_effect' => 'slide_vertical'
                ]
            ]
        );

        $this->add_responsive_control(
            'slider_gutter',
            [
                'type' => Controls_Manager::SLIDER,
                'label' => esc_html__('Gutter', 'king-addons'),
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 300,
                    ]
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 0,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-advanced-slider .slick-slide' => 'margin-left: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .king-addons-advanced-slider .slick-list' => 'margin-left: -{{SIZE}}{{UNIT}};',
                ],
                'render_type' => 'template',
                'condition' => [
                    'slider_amount!' => '1',
                ],
            ]
        );

        $this->add_responsive_control(
            'slider_title',
            [
                'label' => esc_html__('Title', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'widescreen_default' => 'yes',
                'laptop_default' => 'yes',
                'tablet_extra_default' => 'yes',
                'tablet_default' => 'yes',
                'mobile_extra_default' => 'yes',
                'mobile_default' => 'yes',
                'selectors_dictionary' => [
                    '' => 'none',
                    'yes' => 'block'
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-slider-title' => 'display:{{VALUE}};',
                ],
                'separator' => 'before',
                'render_type' => 'template'
            ]
        );

        $this->add_control(
            'slider_title_tag',
            [
                'label' => esc_html__('Title HTML Tag', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'h1' => esc_html__('H1', 'king-addons'),
                    'h2' => esc_html__('H2', 'king-addons'),
                    'h3' => esc_html__('H3', 'king-addons'),
                    'h4' => esc_html__('H4', 'king-addons'),
                    'h5' => esc_html__('H5', 'king-addons'),
                    'h6' => esc_html__('H6', 'king-addons')
                ],
                'default' => 'h2',
                'condition' => [
                    'slider_title' => 'yes'
                ]
            ]
        );

        $this->add_responsive_control(
            'slider_sub_title',
            [
                'label' => esc_html__('Sub Title', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'widescreen_default' => 'yes',
                'laptop_default' => 'yes',
                'tablet_extra_default' => 'yes',
                'tablet_default' => 'yes',
                'mobile_extra_default' => 'yes',
                'mobile_default' => 'yes',
                'selectors_dictionary' => [
                    '' => 'none',
                    'yes' => 'block'
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-slider-sub-title' => 'display:{{VALUE}};',
                ],
                'render_type' => 'template'
            ]
        );

        $this->add_control(
            'slider_sub_title_tag',
            [
                'label' => esc_html__('Sub Title HTML Tag', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'h1' => esc_html__('H1', 'king-addons'),
                    'h2' => esc_html__('H2', 'king-addons'),
                    'h3' => esc_html__('H3', 'king-addons'),
                    'h4' => esc_html__('H4', 'king-addons'),
                    'h5' => esc_html__('H5', 'king-addons'),
                    'h6' => esc_html__('H6', 'king-addons')
                ],
                'default' => 'h3',
                'condition' => [
                    'slider_sub_title' => 'yes'
                ]
            ]
        );

        $this->add_responsive_control(
            'slider_description',
            [
                'label' => esc_html__('Description', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'widescreen_default' => 'yes',
                'laptop_default' => 'yes',
                'tablet_extra_default' => 'yes',
                'tablet_default' => 'yes',
                'mobile_extra_default' => 'yes',
                'mobile_default' => 'yes',
                'selectors_dictionary' => [
                    '' => 'none',
                    'yes' => 'block'
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-slider-description' => 'display:{{VALUE}};',
                ],
                'separator' => 'after',
                'render_type' => 'template'
            ]
        );

        $this->add_responsive_control(
            'slider_nav',
            [
                'label' => esc_html__('Navigation', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'widescreen_default' => 'yes',
                'laptop_default' => 'yes',
                'tablet_extra_default' => 'yes',
                'tablet_default' => 'yes',
                'mobile_extra_default' => 'yes',
                'mobile_default' => 'yes',
                'selectors_dictionary' => [
                    '' => 'none',
                    'yes' => 'flex'
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-slider-arrow' => 'display:{{VALUE}} !important;',
                ],
                'render_type' => 'template'
            ]
        );

        $this->add_control_slider_nav_hover();

        $this->add_control(
            'slider_nav_icon',
            [
                'label' => esc_html__('Select Icon', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'fas fa-angle-left',
                'options' => [
                    'fas fa-angle-left' => esc_html__('Angle', 'king-addons'),
                    'fas fa-angle-double-left' => esc_html__('Angle Double', 'king-addons'),
                    'fas fa-arrow-left' => esc_html__('Arrow', 'king-addons'),
                    'fas fa-arrow-alt-circle-left' => esc_html__('Arrow Circle', 'king-addons'),
                    'far fa-arrow-alt-circle-left' => esc_html__('Arrow Circle Alt', 'king-addons'),
                    'fas fa-long-arrow-alt-left' => esc_html__('Long Arrow', 'king-addons'),
                    'fas fa-chevron-left' => esc_html__('Chevron', 'king-addons'),
                ],
                'condition' => [
                    'slider_nav' => 'yes',
                ],
                'separator' => 'after',
                'render_type' => 'template'
            ]
        );

        $this->add_responsive_control(
            'slider_dots',
            [
                'label' => esc_html__('Pagination', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'widescreen_default' => 'yes',
                'laptop_default' => 'yes',
                'tablet_extra_default' => 'yes',
                'tablet_default' => 'yes',
                'mobile_extra_default' => 'yes',
                'mobile_default' => 'yes',
                'selectors_dictionary' => [
                    '' => 'none',
                    'yes' => 'inline-table'
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-slider-dots' => 'display:{{VALUE}};',
                ],
                'render_type' => 'template',
            ]
        );

        $this->add_control_slider_dots_layout();

        Core::renderUpgradeProNotice($this, Controls_Manager::RAW_HTML, 'slider', 'slider_dots_layout', ['pro-vr']);

        $this->add_control_slider_scroll_btn();

        $this->add_control_slider_autoplay();

        $this->add_control_slider_autoplay_duration();

        $this->add_control_slider_pause_on_hover();

        $this->add_control(
            'slider_loop',
            [
                'label' => esc_html__('Infinite Loop', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'frontend_available' => true,
                'separator' => 'before',
            ]
        );

        $this->add_control_slider_effect();

        $this->add_control(
            'slider_effect_duration',
            [
                'label' => esc_html__('Effect Duration', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'default' => 0.7,
                'min' => 0,
                'max' => 5,
                'step' => 0.1,
            ]
        );

        $this->add_control(
            'slider_content_animation',
            [
                'label' => esc_html__('Content Animation', 'king-addons'),
                'type' => 'king-addons-animations-alt',
                'default' => 'none',
                'condition' => [
                    'slider_effect' => 'fade',
                ],
            ]
        );


        Core::renderUpgradeProNotice($this, Controls_Manager::RAW_HTML, 'slider', 'slider_content_animation', ['pro-slrt', 'pro-slxrt', 'pro-slbt', 'pro-sllt', 'pro-sltp', 'pro-slxlt', 'pro-sktp', 'pro-skrt', 'pro-skbt', 'pro-sklt', 'pro-scup', 'pro-scdn', 'pro-rllt', 'pro-rlrt']);

        $this->add_control(
            'slider_content_anim_size',
            [
                'type' => Controls_Manager::SELECT,
                'label' => esc_html__('Animation Size', 'king-addons'),
                'default' => 'large',
                'options' => [
                    'small' => esc_html__('Small', 'king-addons'),
                    'medium' => esc_html__('Medium', 'king-addons'),
                    'large' => esc_html__('Large', 'king-addons'),
                ],
                'condition' => [
                    'slider_content_animation!' => 'none',
                    'slider_effect' => 'fade',
                ],
            ]
        );

        $this->add_control(
            'slider_content_anim_duration',
            [
                'label' => esc_html__('Animation Duration', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'default' => 1,
                'min' => 0,
                'max' => 5,
                'step' => 0.1,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-slider-animation .king-addons-cv-outer' => '-webkit-transition-duration: {{VALUE}}s;transition-duration: {{VALUE}}s;',
                ],
                'condition' => [
                    'slider_content_animation!' => 'none',
                    'slider_effect' => 'fade',
                ],
            ]
        );

        $this->end_controls_section();

        Core::renderProFeaturesSection($this, '', Controls_Manager::RAW_HTML, 'slider', [
            'Add Unlimited Slides',
            'Unlimited Slides to Scroll Option',
            'Columns (Carousel) 1, 2, 3, 4, 5, 6',
            'Slider/Carousel Autoplay Options',
            'Youtube & Vimeo Video Support',
            'Custom Video Support',
            'Elementor Templates Slider Option',
            'Scroll to Section Button',
            'Ken Burn Effect',
            'Vertical Sliding',
            'Advanced Navigation Positioning',
            'Advanced Pagination Positioning'
        ]);

        $this->start_controls_section(
            'settings_section_style_slider_content',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Slider Content', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'slider_content_bg_color',
            [
                'label' => esc_html__('Background Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-slider-content' => 'background-color: {{VALUE}};',
                ],
                'separator' => 'after',
            ]
        );

        $this->add_responsive_control(
            'slider_content_hr',
            [
                'label' => esc_html__('Horizontal Position', 'king-addons'),
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
                    ]
                ],
                'default' => 'center',
                'widescreen_default' => 'center',
                'laptop_default' => 'center',
                'tablet_extra_default' => 'center',
                'tablet_default' => 'center',
                'mobile_extra_default' => 'center',
                'mobile_default' => 'center',
                'selectors_dictionary' => [
                    'left' => 'float: left',
                    'center' => 'margin: 0 auto',
                    'right' => 'float: right'
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-slider-content' => '{{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'slider_content_vr',
            [
                'label' => esc_html__('Vertical Position', 'king-addons'),
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
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-cv-inner' => 'vertical-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'slider_content_align',
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
                    '{{WRAPPER}} .king-addons-slider-content' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'slider_content_width',
            [
                'label' => esc_html__('Width', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    '%' => [
                        'min' => 20,
                        'max' => 100,
                    ],
                    'px' => [
                        'min' => 200,
                        'max' => 1500,
                    ],
                ],
                'size_units' => ['%', 'px'],
                'default' => [
                    'unit' => 'px',
                    'size' => 750,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-slider-content' => 'max-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'slider_content_padding',
            [
                'label' => esc_html__('Padding', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'default' => [
                    'top' => 10,
                    'right' => 50,
                    'bottom' => 50,
                    'left' => 50,
                ],
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-slider-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'after',
            ]
        );

        $this->end_controls_section();


        $this->start_controls_section(
            'settings_section_style_slider_title',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Title', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            ]
        );

        $this->add_control(
            'slider_title_color',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-slider-title *' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'slider_title_bg_color',
            [
                'label' => esc_html__('Background Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-slider-title *' => 'background-color: {{VALUE}}',
                ],
                'separator' => 'after',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'slider_title_typography',
                'selector' => '{{WRAPPER}} .king-addons-slider-title *',
            ]
        );

        $this->add_responsive_control(
            'slider_title_padding',
            [
                'label' => esc_html__('Padding', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'default' => [
                    'top' => 5,
                    'right' => 10,
                    'bottom' => 5,
                    'left' => 10,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-slider-title *' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'slider_title_margin',
            [
                'label' => esc_html__('Margin', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'default' => [
                    'top' => 0,
                    'right' => 0,
                    'bottom' => 0,
                    'left' => 0,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-slider-title *' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();


        $this->start_controls_section(
            'settings_section_style_slider_sub_title',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Sub Title', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            ]
        );

        $this->add_control(
            'slider_sub_title_color',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-slider-sub-title *' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'slider_sub_title_bg_color',
            [
                'label' => esc_html__('Background Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-slider-sub-title *' => 'background-color: {{VALUE}}',
                ],
                'separator' => 'after',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'slider_sub_title_typography',
                'selector' => '{{WRAPPER}} .king-addons-slider-sub-title *',
            ]
        );

        $this->add_responsive_control(
            'slider_sub_title_padding',
            [
                'label' => esc_html__('Padding', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'default' => [
                    'top' => 5,
                    'right' => 10,
                    'bottom' => 5,
                    'left' => 10,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-slider-sub-title *' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'slider_sub_title_margin',
            [
                'label' => esc_html__('Margin', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'default' => [
                    'top' => 0,
                    'right' => 0,
                    'bottom' => 5,
                    'left' => 0,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-slider-sub-title *' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();


        $this->start_controls_section(
            'settings_section_style_slider_description',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Description', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            ]
        );

        $this->add_control(
            'slider_description_color',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-slider-description p' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'slider_description_bg_color',
            [
                'label' => esc_html__('Background Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-slider-description p' => 'background-color: {{VALUE}}',
                ],
                'separator' => 'after',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'slider_description_typography',
                'selector' => '{{WRAPPER}} .king-addons-slider-description p',
            ]
        );

        $this->add_responsive_control(
            'slider_description_padding',
            [
                'label' => esc_html__('Padding', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'default' => [
                    'top' => 5,
                    'right' => 10,
                    'bottom' => 5,
                    'left' => 10,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-slider-description p' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'slider_description_margin',
            [
                'label' => esc_html__('Margin', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'default' => [
                    'top' => 0,
                    'right' => 0,
                    'bottom' => 30,
                    'left' => 0,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-slider-description p' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();


        $this->start_controls_section(
            'settings_section_style_btn_1',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Button Primary', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            ]
        );

        $this->start_controls_tabs('tabs_btn_style_1');

        $this->start_controls_tab(
            'tab_btn_normal_1',
            [
                'label' => esc_html__('Normal', 'king-addons'),
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'btn_bg_color_1',
                'label' => esc_html__('Background', 'king-addons'),
                'types' => ['classic', 'gradient'],
                'fields_options' => [
                    'color' => [
                        'default' => '#434900',
                    ],
                ],
                'selector' => '{{WRAPPER}} .king-addons-slider-primary-btn'
            ]
        );

        $this->add_control(
            'btn_color_1',
            [
                'label' => esc_html__('Text Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-slider-primary-btn' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .king-addons-slider-primary-btn svg' => 'fill: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'btn_border_color_1',
            [
                'label' => esc_html__('Border Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-slider-primary-btn' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'btn_box_shadow_1',
                'selector' => '{{WRAPPER}} .king-addons-slider-primary-btn',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_btn_hover_1',
            [
                'label' => esc_html__('Hover', 'king-addons'),
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'btn_hover_bg_color_1',
                'label' => esc_html__('Background', 'king-addons'),
                'types' => ['classic', 'gradient'],
                'fields_options' => [
                    'color' => [
                        'default' => '#434900',
                    ],
                ],
                'selector' => '{{WRAPPER}} .king-addons-slider-primary-btn:hover'
            ]
        );

        $this->add_control(
            'btn_hover_color_1',
            [
                'label' => esc_html__('Text Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-slider-primary-btn:hover' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .king-addons-slider-primary-btn:hover svg' => 'fill: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'btn_hover_border_color_1',
            [
                'label' => esc_html__('Border Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-slider-primary-btn:hover' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'btn_hover_box_shadow_1',
                'selector' => '{{WRAPPER}} .king-addons-slider-primary-btn:hover',
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_control(
            'btn_transition_duration_1',
            [
                'label' => esc_html__('Transition Duration (seconds)', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'default' => 0.1,
                'min' => 0,
                'max' => 5,
                'step' => 0.1,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-slider-primary-btn' => '-webkit-transition-duration: {{VALUE}}s;transition-duration: {{VALUE}}s',
                    '{{WRAPPER}} .king-addons-slider-primary-btn svg' => '-webkit-transition-duration: {{VALUE}}s;transition-duration: {{VALUE}}s',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'btn_typography_1_divider',
            [
                'type' => Controls_Manager::DIVIDER,
                'style' => 'thick',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'btn_typography_1',
                'selector' => '{{WRAPPER}} .king-addons-slider-primary-btn',
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'btn_icon_size_1',
            [
                'label' => esc_html__('Icon Size', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 5,
                        'max' => 30,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 13,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-slider-primary-btn i' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .king-addons-slider-primary-btn svg' => 'width: {{SIZE}}{{UNIT}}; height: auto;',
                ],
            ]
        );

        $this->add_responsive_control(
            'btn_padding_1',
            [
                'label' => esc_html__('Padding', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'default' => [
                    'top' => 12,
                    'right' => 25,
                    'bottom' => 12,
                    'left' => 25,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-slider-primary-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'btn_margin_1',
            [
                'label' => esc_html__('Margin', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'default' => [
                    'top' => 0,
                    'right' => 10,
                    'bottom' => 0,
                    'left' => 10,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-slider-primary-btn' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'after',
            ]
        );

        $this->add_control(
            'btn_border_type_1',
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
                    '{{WRAPPER}} .king-addons-slider-primary-btn' => 'border-style: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'btn_border_width_1',
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
                    '{{WRAPPER}} .king-addons-slider-primary-btn' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'btn_border_type_1!' => 'none',
                ],
            ]
        );

        $this->add_responsive_control(
            'btn_border_radius_1',
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
                    '{{WRAPPER}} .king-addons-slider-primary-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->end_controls_section();


        $this->start_controls_section(
            'settings_section_style_btn_2',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Button Secondary', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            ]
        );

        $this->start_controls_tabs('tabs_btn_style_2');

        $this->start_controls_tab(
            'tab_btn_normal_2',
            [
                'label' => esc_html__('Normal', 'king-addons'),
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'btn_bg_color_2',
                'label' => esc_html__('Background', 'king-addons'),
                'types' => ['classic', 'gradient'],
                'fields_options' => [
                    'color' => [
                        'default' => '#434900',
                    ],
                ],
                'selector' => '{{WRAPPER}} .king-addons-slider-secondary-btn'
            ]
        );

        $this->add_control(
            'btn_color_2',
            [
                'label' => esc_html__('Text Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-slider-secondary-btn' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .king-addons-slider-secondary-btn svg' => 'fill: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'btn_border_color_2',
            [
                'label' => esc_html__('Border Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-slider-secondary-btn' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'btn_box_shadow_2',
                'selector' => '{{WRAPPER}} .king-addons-slider-secondary-btn',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_btn_hover_2',
            [
                'label' => esc_html__('Hover', 'king-addons'),
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'btn_hover_bg_color_2',
                'label' => esc_html__('Background', 'king-addons'),
                'types' => ['classic', 'gradient'],
                'fields_options' => [
                    'color' => [
                        'default' => '#434900',
                    ],
                ],
                'selector' => '{{WRAPPER}} .king-addons-slider-secondary-btn:hover'
            ]
        );

        $this->add_control(
            'btn_hover_color_2',
            [
                'label' => esc_html__('Text Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-slider-secondary-btn:hover' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .king-addons-slider-secondary-btn:hover svg' => 'fill: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'btn_hover_border_color_2',
            [
                'label' => esc_html__('Border Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-slider-secondary-btn:hover' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'btn_hover_box_shadow_2',
                'selector' => '{{WRAPPER}} .king-addons-slider-secondary-btn:hover',
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_control(
            'btn_transition_duration_2',
            [
                'label' => esc_html__('Transition Duration (seconds)', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'default' => 0.1,
                'min' => 0,
                'max' => 5,
                'step' => 0.1,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-slider-secondary-btn' => '-webkit-transition-duration: {{VALUE}}s;transition-duration: {{VALUE}}s',
                    '{{WRAPPER}} .king-addons-slider-secondary-btn svg' => '-webkit-transition-duration: {{VALUE}}s;transition-duration: {{VALUE}}s',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'btn_typography_2_divider',
            [
                'type' => Controls_Manager::DIVIDER,
                'style' => 'thick',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'btn_typography_2',
                'selector' => '{{WRAPPER}} .king-addons-slider-secondary-btn',
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'btn_icon_size_2',
            [
                'label' => esc_html__('Icon Size', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 5,
                        'max' => 30,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 13,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-slider-secondary-btn i' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .king-addons-slider-secondary-btn svg' => 'width: {{SIZE}}{{UNIT}}; height: auto;',
                ],
            ]
        );


        $this->add_responsive_control(
            'btn_padding_2',
            [
                'label' => esc_html__('Padding', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px',],
                'default' => [
                    'top' => 12,
                    'right' => 25,
                    'bottom' => 12,
                    'left' => 25,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-slider-secondary-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'btn_margin_2',
            [
                'label' => esc_html__('Margin', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px',],
                'default' => [
                    'top' => 0,
                    'right' => 10,
                    'bottom' => 0,
                    'left' => 10,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-slider-secondary-btn' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'after',
            ]
        );

        $this->add_control(
            'btn_border_type_2',
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
                    '{{WRAPPER}} .king-addons-slider-secondary-btn' => 'border-style: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'btn_border_width_2',
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
                    '{{WRAPPER}} .king-addons-slider-secondary-btn' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'btn_border_type_2!' => 'none',
                ],
            ]
        );

        $this->add_responsive_control(
            'btn_border_radius_2',
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
                    '{{WRAPPER}} .king-addons-slider-secondary-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->end_controls_section();


        $this->add_section_style_scroll_btn();


        $this->start_controls_section(
            'settings_section_style_slider_video_btn',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Video Icon', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'slider_video_btn_size',
            [
                'label' => esc_html__('Video Icon Size', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'medium',
                'options' => [
                    'none' => esc_html__('None', 'king-addons'),
                    'small' => esc_html__('Small', 'king-addons'),
                    'medium' => esc_html__('Medium', 'king-addons'),
                    'large' => esc_html__('Large', 'king-addons'),
                ],
                'frontend_available' => true,

            ]
        );

        $this->add_control(
            'slider_video_btn_color',
            [
                'label' => esc_html__('Text Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-slider-video-btn' => 'color: {{VALUE}}; border-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();


        $this->start_controls_section(
            'settings_section_style_slider_nav',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Navigation', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->start_controls_tabs('tabs_slider_nav_style');

        $this->start_controls_tab(
            'tab_slider_nav_normal',
            [
                'label' => esc_html__('Normal', 'king-addons'),
            ]
        );

        $this->add_control(
            'slider_nav_color',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => 'rgba(255,255,255,0.8)',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-slider-arrow' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .king-addons-slider-arrow svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'slider_nav_bg_color',
            [
                'label' => esc_html__('Background Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-slider-arrow' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'slider_nav_border_color',
            [
                'label' => esc_html__('Border Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => 'rgba(255,255,255,0.8)',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-slider-arrow' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_slider_nav_hover',
            [
                'label' => esc_html__('Hover', 'king-addons'),
            ]
        );

        $this->add_control(
            'slider_nav_hover_color',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-slider-arrow:hover' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .king-addons-slider-arrow:hover svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'slider_nav_hover_bg_color',
            [
                'label' => esc_html__('Background Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-slider-arrow:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'slider_nav_hover_border_color',
            [
                'label' => esc_html__('Border Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-slider-arrow:hover' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_control(
            'slider_nav_transition_duration',
            [
                'label' => esc_html__('Transition Duration (seconds)', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'default' => 0.1,
                'min' => 0,
                'max' => 5,
                'step' => 0.1,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-slider-arrow' => '-webkit-transition-duration: {{VALUE}}s;transition-duration: {{VALUE}}s',
                    '{{WRAPPER}} .king-addons-slider-arrow svg' => '-webkit-transition-duration: {{VALUE}}s;transition-duration: {{VALUE}}s',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'slider_nav_font_size',
            [
                'label' => esc_html__('Font Size', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px',],
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 200,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 25,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-slider-arrow' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .king-addons-slider-arrow svg' => 'width: {{SIZE}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'slider_nav_size',
            [
                'label' => esc_html__('Box Size', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px',],
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 200,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 60,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-slider-arrow' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
                'separator' => 'after',
            ]
        );

        $this->add_control(
            'slider_nav_border_type',
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
                    '{{WRAPPER}} .king-addons-slider-arrow' => 'border-style: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'slider_nav_border_width',
            [
                'label' => esc_html__('Border Width', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px',],
                'default' => [
                    'top' => 2,
                    'right' => 2,
                    'bottom' => 2,
                    'left' => 2,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-slider-arrow' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'slider_nav_border_type!' => 'none',
                ],
            ]
        );

        $this->add_control(
            'slider_nav_border_radius',
            [
                'label' => esc_html__('Border Radius', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-slider-arrow' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'after',
            ]
        );

        $this->add_control_stack_slider_nav_position();

        $this->end_controls_section();


        $this->start_controls_section(
            'settings_section_style_slider_dots',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Pagination', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->start_controls_tabs('tabs_slider_dots');

        $this->start_controls_tab(
            'tab_slider_dots_normal',
            [
                'label' => esc_html__('Normal', 'king-addons'),
            ]
        );

        $this->add_control(
            'slider_dots_bg_color',
            [
                'label' => esc_html__('Background Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => 'rgba(0,0,0,0.35)',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-slider-dot' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'slider_dots_border_color',
            [
                'label' => esc_html__('Border Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-slider-dot' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_slider_dots_active',
            [
                'label' => esc_html__('Active', 'king-addons'),
            ]
        );

        $this->add_control(
            'slider_dots_active_bg_color',
            [
                'label' => esc_html__('Background Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#222222',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-slider-dots .slick-active .king-addons-slider-dot' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'slider_dots_active_border_color',
            [
                'label' => esc_html__('Border Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-slider-dots .slick-active .king-addons-slider-dot' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_responsive_control(
            'slider_dots_width',
            [
                'label' => esc_html__('Box Width', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px',],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 8,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-slider-dot' => 'width: {{SIZE}}{{UNIT}};',
                ],
                'separator' => 'before',
                'render_type' => 'template'
            ]
        );

        $this->add_responsive_control(
            'slider_dots_height',
            [
                'label' => esc_html__('Box Height', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px',],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 8,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-slider-dot' => 'height: {{SIZE}}{{UNIT}};',
                ],
                'render_type' => 'template'
            ]
        );

        $this->add_control(
            'slider_dots_border_type',
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
                    '{{WRAPPER}} .king-addons-slider-dot' => 'border-style: {{VALUE}};',
                ],
                'separator' => 'before',
                'render_type' => 'template'
            ]
        );

        $this->add_responsive_control(
            'slider_dots_border_width',
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
                    '{{WRAPPER}} .king-addons-slider-dot' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'render_type' => 'template',
                'condition' => [
                    'slider_dots_border_type!' => 'none',
                ],
            ]
        );

        $this->add_responsive_control(
            'slider_dots_border_radius',
            [
                'label' => esc_html__('Border Radius', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'default' => [
                    'top' => 50,
                    'right' => 50,
                    'bottom' => 50,
                    'left' => 50,
                    'unit' => '%',
                ],
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-slider-dot' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'slider_dots_gutter',
            [
                'type' => Controls_Manager::SLIDER,
                'label' => esc_html__('Gutter', 'king-addons'),
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 7,
                ],
                'selectors' => [
                    '{{WRAPPER}}.king-addons-slider-dots-horizontal .king-addons-slider-dot' => 'margin-right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}}.king-addons-slider-dots-vertical .king-addons-slider-dot' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
                'separator' => 'before',
                'render_type' => 'template'
            ]
        );

        $this->add_control_slider_dots_hr();

        $this->add_responsive_control(
            'slider_dots_vr',
            [
                'type' => Controls_Manager::SLIDER,
                'label' => esc_html__('Vertical Position', 'king-addons'),
                'size_units' => ['%', 'px'],
                'range' => [
                    '%' => [
                        'min' => -20,
                        'max' => 120,
                    ],
                    'px' => [
                        'min' => -200,
                        'max' => 2000,
                    ],
                ],
                'default' => [
                    'unit' => '%',
                    'size' => 96,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-slider-dots' => 'top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

    }

    public function load_slider_template($id)
    {
        if (empty($id)) {
            return '';
        }

        // WPML / Polylang language handling
        if (defined('ICL_LANGUAGE_CODE')) {
            $default_language_code = apply_filters('wpml_default_language', null);
            /** @noinspection PhpUndefinedConstantInspection */
            if (ICL_LANGUAGE_CODE !== $default_language_code) {
                /** @noinspection PhpUndefinedConstantInspection */
                /** @noinspection PhpUndefinedFunctionInspection */
                $id = icl_object_id($id, 'elementor_library', false, ICL_LANGUAGE_CODE);
            }
        }

        $edit_link = sprintf(
            '<span class="king-addons-template-edit-btn" data-permalink="%s">Edit Template</span>',
            esc_url(get_permalink($id))
        );

        $type = get_post_meta(get_the_ID(), '_king_addons_template_type', true);
        $has_css = (
            'internal' === get_option('elementor_css_print_method')
            || '' !== $type
        );

        // Return the rendered Elementor template plus an "Edit" link
        return Elementor\Plugin::instance()->frontend->get_builder_content_for_display($id, $has_css) . $edit_link;
    }

    // Stub left intentionally for compatibility with the original code
    public function render_pro_element_slider_scroll_btn()
    {
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $slider_html = '';
        $item_count = 0;

        // Early exit if no slider items
        if (empty($settings['slider_items'])) {
            return;
        }

        $tags_whitelist = ['h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'div', 'span', 'p'];

        $settings_slider_title_tag = Core::validateHTMLTags($settings['slider_title_tag'], 'h2', $tags_whitelist);
        $settings_slider_sub_title_tag = Core::validateHTMLTags($settings['slider_sub_title_tag'], 'h3', $tags_whitelist);

        // Check if premium code is allowed
        $is_premium = king_addons_freemius()->can_use_premium_code__premium_only();

        /**
         * If the slider effect is "slide_vertical", force the slider_amount to 1
         * (this matches the original code logic).
         * Otherwise, use the slider_amount from the settings.
         */
        $slider_amount = ($settings['slider_effect'] === 'slide_vertical')
            ? 1
            : (int)$settings['slider_amount'];

        // In free version, override some settings if needed
        if (!$is_premium) {
            if (in_array($settings['slider_amount'], ['pro-3', 'pro-4', 'pro-5', 'pro-6'])) {
                $settings['slider_amount'] = 2; // Force fallback for free version
            }
        }

        // Helper closure to get the background image URL
        $get_bg_image_url = function ($item) use ($settings) {
            if (
                !empty($item['slider_item_bg_image']['source'])
                && $item['slider_item_bg_image']['source'] === 'url'
            ) {
                return $item['slider_item_bg_image']['url'] ?? '';
            }
            return Group_Control_Image_Size::get_attachment_image_src(
                $item['slider_item_bg_image']['id'],
                'slider_image_size',
                $settings
            );
        };

        // Helper closure to render icons
        $render_icon_html = function ($icon) {
            if (!empty($icon['value'])) {
                ob_start();
                Icons_Manager::render_icon($icon, ['aria-hidden' => 'true']);
                return ob_get_clean();
            }
            return '';
        };

        foreach ($settings['slider_items'] as $key => $item) {
            // Free version: limit to 4 slides
            if (!$is_premium && $key === 4) {
                break;
            }

            // In free version, force content type to custom (if template is not allowed)
            if (!$is_premium) {
                $item['slider_content_type'] = 'custom';
            }

            // If this slide is loaded from another Elementor template
            if ($item['slider_content_type'] === 'template') {
                $slider_html .= sprintf(
                    '<div class="king-addons-slider-item elementor-repeater-item-%s">%s</div>',
                    esc_attr($item['_id']),
                    $this->load_slider_template($item['slider_select_template'])
                );
                continue; // proceed to the next item
            }

            // --- Below is the logic for "custom" slide content ---
            if (!$is_premium) {
                $item['slider_item_link_type'] = 'none';
            }

            $this->add_render_attribute("slider_item$item_count", 'class', [
                'king-addons-slider-item',
                'elementor-repeater-item-' . esc_attr($item['_id'])
            ]);

            $item_type = $item['slider_item_link_type'];
            $item_bg_image_url = $get_bg_image_url($item);
            $ken_burn_class = ($item['slider_item_bg_kenburns'] === 'yes')
                ? ' king-addons-ken-burns-' . $item['slider_item_bg_zoom']
                : '';

            // --- VIDEO logic ---
            if (strpos($item_type, 'video') !== false && !empty($item['slider_item_video_src'])) {
                $this->add_render_attribute("slider_item$item_count", 'class', 'king-addons-slider-video-item');
                $this->add_render_attribute(
                    "slider_item$item_count",
                    'data-video-autoplay',
                    esc_attr($item['slider_item_video_autoplay'])
                );

                $item_video_src = $item['slider_item_video_src'];
                $start_time = esc_attr($item['slider_item_video_start']);
                $end_time = esc_attr($item['slider_item_video_end']);
                $autoplay = ($item['slider_item_video_autoplay'] === 'yes');
                $mute = ($item['slider_item_video_mute'] === 'yes');
                $controls = ($item['slider_item_video_controls'] === 'yes');
                $loop = ($item['slider_item_video_loop'] === 'yes');

                // YouTube
                if ($item_type === 'video-youtube') {
                    preg_match('![?&]v=([^&]+)!', $item_video_src, $video_id);
                    $youtube_id = $video_id[1] ?? '';

                    if (!$item_bg_image_url) {
                        $item_bg_image_url = "https://i.ytimg.com/vi_webp/$youtube_id/maxresdefault.webp";
                    }
                    $item_video_src = $autoplay
                        ? "https://www.youtube.com/embed/$youtube_id?autoplay=1"
                        : "https://www.youtube.com/embed/$youtube_id?enablejsapi=1";

                    if ($mute) $item_video_src .= '&mute=1';
                    if (!$controls) $item_video_src .= '&controls=0';

                    if ($loop) {
                        $item_video_src .= '&loop=1&playlist=' . $youtube_id;
                    } else {
                        if ($start_time) $item_video_src .= '&start=' . $start_time;
                        if ($end_time) $item_video_src .= '&end=' . $end_time;
                    }
                } // Vimeo
                elseif ($item_type === 'video-vimeo') {
                    $item_video_src = str_replace('vimeo.com', 'player.vimeo.com/video', $item_video_src);
                    $item_video_src .= '?autoplay=1&title=0&portrait=0&byline=0';
                    if ($mute) $item_video_src .= '&muted=1';
                    if (!$controls) $item_video_src .= '&controls=0';
                    if ($loop) {
                        $item_video_src .= '&loop=1';
                    } elseif ($start_time) {
                        // Convert the start time into HhMmSs format
                        $item_video_src .= '&#t='
                            . gmdate('H', $start_time) . 'h'
                            . gmdate('i', $start_time) . 'm'
                            . gmdate('s', $start_time) . 's';
                    }
                } // Self-hosted
                elseif ($item_type === 'video-media') {
                    $item_video_src = $item['hosted_url']['url'] ?? '';
                    $video_mute = $mute ? 'muted' : '';
                    $video_loop = $loop ? 'loop' : '';
                    $video_controls = $controls ? 'controls' : '';
                    $this->add_render_attribute("slider_item$item_count", 'data-video-mute', $video_mute);
                    $this->add_render_attribute("slider_item$item_count", 'data-video-loop', $video_loop);
                    $this->add_render_attribute("slider_item$item_count", 'data-video-controls', $video_controls);
                }
                $this->add_render_attribute(
                    "slider_item$item_count",
                    'data-video-src',
                    esc_url($item_video_src)
                );
            }

            // Begin wrapper
            $slider_html .= '<div ' . $this->get_render_attribute_string("slider_item$item_count") . '>';

            // Display as an <img> tag or via background
            if ($settings['slider_image_type'] === 'image') {
                $slider_html .= '<img class="king-addons-slider-img" src="' . esc_url($item_bg_image_url) . '"/>';
            } else {
                $slider_html .= '<div class="king-addons-slider-item-bg ' . esc_attr($ken_burn_class) . '" '
                    . 'style="background-image:url(' . esc_url($item_bg_image_url) . ')"></div>';
            }

            /**
             * Processing the overlay:
             * - If the slider has only 1 slide or blend_mode != normal, insert overlay immediately.
             * - If multiple slides and blend_mode = normal, store overlay in a variable and place it later.
             */
            $slider_overlay_html = '';
            if ($item['slider_item_overlay'] === 'yes') {
                if ($slider_amount === 1 || $item['slider_item_blend_mode'] !== 'normal') {
                    $slider_html .= '<div class="king-addons-slider-item-overlay"></div>';
                } else {
                    // for multi-slide + normal blend mode, insert the overlay later
                    $slider_overlay_html = '<div class="king-addons-slider-item-overlay"></div>';
                }
            }

            // Prepare containers
            $this->add_render_attribute("slider_container$item_count", 'class', 'king-addons-cv-container');
            $this->add_render_attribute("slider_outer$item_count", 'class', 'king-addons-cv-outer');

            // If the effect is not "fade", disable the content animation
            if ($settings['slider_effect'] !== 'fade') {
                $settings['slider_content_animation'] = 'none';
            }

            // If content animation is enabled
            if ($settings['slider_content_animation'] !== 'none') {
                $anim_size = esc_attr($settings['slider_content_anim_size']);
                $anim_type = esc_attr($settings['slider_content_animation']);

                $this->add_render_attribute("slider_container$item_count", 'class', 'king-addons-slider-animation');
                $this->add_render_attribute("slider_outer$item_count", 'class', [
                    'king-addons-anim-transparency',
                    'king-addons-anim-size-' . $anim_size,
                    'king-addons-overlay-' . $anim_type
                ]);

                /**
                 * For multi-slide + manual video start (non-autoplay),
                 * add an additional .king-addons-animation-wrap class
                 */
                if (
                    $slider_amount !== 1
                    && !empty($item_bg_image_url)
                    && $item['slider_item_video_autoplay'] !== 'yes'
                ) {
                    $this->add_render_attribute("slider_container$item_count", 'class', 'king-addons-animation-wrap');
                }
            }

            $slider_html .= '<div ' . $this->get_render_attribute_string("slider_container$item_count") . '>';

            // Optional link wrapping the background
            $bg_link = $item['slider_item_bg_image_url']['url'] ?? '';
            if ($bg_link && $item_type === 'custom') {
                $this->add_render_attribute("slider_item_url$item_count", 'href', esc_url($bg_link));
                if (!empty($item['slider_item_bg_image_url']['is_external'])) {
                    $this->add_render_attribute("slider_item_url$item_count", 'target', '_blank');
                }
                if (!empty($item['slider_item_bg_image_url']['nofollow'])) {
                    $this->add_render_attribute("slider_item_url$item_count", 'rel', 'nofollow');
                }
                $slider_html .= '<a class="king-addons-slider-item-url" '
                    . $this->get_render_attribute_string("slider_item_url$item_count") . '></a>';
            }

            $slider_html .= '<div ' . $this->get_render_attribute_string("slider_outer$item_count") . '>';
            $slider_html .= '<div class="king-addons-cv-inner">';

            // If multiple slides + normal blend mode, insert the overlay here
            $slider_html .= $slider_overlay_html;

            // Slide content
            if ($item['slider_show_content'] === 'yes') {
                $slider_html .= '<div class="king-addons-slider-content">';

                // If it's video without autoplay, display the play button
                if (strpos($item_type, 'video') !== false && $item['slider_item_video_autoplay'] !== 'yes') {
                    $slider_html .= '<div class="king-addons-slider-video-btn"><i class="fas fa-play"></i></div>';
                }

                // Title
                if (
                    $settings['slider_title'] === 'yes'
                    && !empty($item['slider_item_title'])
                ) {
                    /**
                     * Use the item's slider_title_tag if valid,
                     * otherwise fallback to the global setting
                     */
                    $tag = $item['slider_title_tag']
                        ? Core::validateHTMLTags($item['slider_title_tag'], 'h2', $tags_whitelist)
                        : $settings_slider_title_tag;

                    $slider_html .= '<div class="king-addons-slider-title">';
                    $slider_html .= "<$tag>" . wp_kses_post($item['slider_item_title']) . "</$tag>";
                    $slider_html .= '</div>';
                }

                // Sub Title
                if (
                    $settings['slider_sub_title'] === 'yes'
                    && !empty($item['slider_item_sub_title'])
                ) {
                    $tag = $item['slider_sub_title_tag']
                        ? Core::validateHTMLTags($item['slider_sub_title_tag'], 'h3', $tags_whitelist)
                        : $settings_slider_sub_title_tag;

                    $slider_html .= '<div class="king-addons-slider-sub-title">';
                    $slider_html .= "<$tag>" . wp_kses_post($item['slider_item_sub_title']) . "</$tag>";
                    $slider_html .= '</div>';
                }

                // Description
                if (
                    $settings['slider_description'] === 'yes'
                    && !empty($item['slider_item_description'])
                ) {
                    $slider_html .= '<div class="king-addons-slider-description"><p>'
                        . wp_kses_post($item['slider_item_description']) . '</p></div>';
                }

                // Buttons
                $icon_html_1 = esc_attr($item['slider_item_btn_text_1']) . $render_icon_html($item['slider_item_btn_icon_1']);
                $icon_html_2 = esc_attr($item['slider_item_btn_text_2']) . $render_icon_html($item['slider_item_btn_icon_2']);

                $btn1_url = $item['slider_item_btn_url_1']['url'] ?? '';
                $btn2_url = $item['slider_item_btn_url_2']['url'] ?? '';

                $slider_html .= '<div class="king-addons-slider-btns">';

                // First button
                if ($item['slider_item_btn_1'] === 'yes' && $icon_html_1) {
                    $tag_btn1 = 'div';
                    $btn1_attribs = '';
                    if (!empty($btn1_url)) {
                        $tag_btn1 = 'a';
                        $this->add_render_attribute("btn1_$item_count", 'href', esc_url($btn1_url));
                        if (!empty($item['slider_item_btn_url_1']['is_external'])) {
                            $this->add_render_attribute("btn1_$item_count", 'target', '_blank');
                        }
                        if (!empty($item['slider_item_btn_url_1']['nofollow'])) {
                            $this->add_render_attribute("btn1_$item_count", 'rel', 'nofollow');
                        }
                        $btn1_attribs = $this->get_render_attribute_string("btn1_$item_count");
                    }
                    $slider_html .= "<$tag_btn1 class=\"king-addons-slider-primary-btn\" $btn1_attribs>$icon_html_1</$tag_btn1>";
                }

                // Second button
                if ($item['slider_item_btn_2'] === 'yes' && $icon_html_2) {
                    $tag_btn2 = 'div';
                    $btn2_attribs = '';
                    if (!empty($btn2_url)) {
                        $tag_btn2 = 'a';
                        $this->add_render_attribute("btn2_$item_count", 'href', esc_url($btn2_url));
                        if (!empty($item['slider_item_btn_url_2']['is_external'])) {
                            $this->add_render_attribute("btn2_$item_count", 'target', '_blank');
                        }
                        if (!empty($item['slider_item_btn_url_2']['nofollow'])) {
                            $this->add_render_attribute("btn2_$item_count", 'rel', 'nofollow');
                        }
                        $btn2_attribs = $this->get_render_attribute_string("btn2_$item_count");
                    }
                    $slider_html .= "<$tag_btn2 class=\"king-addons-slider-secondary-btn\" $btn2_attribs>$icon_html_2</$tag_btn2>";
                }

                $slider_html .= '</div>'; // .king-addons-slider-btns
                $slider_html .= '</div>'; // .king-addons-slider-content
            } else {
                // If content is hidden, but it is a video without autoplay, show the "Play" button
                if (strpos($item_type, 'video') !== false && $item['slider_item_video_autoplay'] !== 'yes') {
                    $slider_html .= '<div class="king-addons-slider-video-btn"><i class="fas fa-play"></i></div>';
                }
            }

            $slider_html .= '</div>'; // .king-addons-cv-inner
            $slider_html .= '</div>'; // slider_outer
            $slider_html .= '</div>'; // slider_container
            $slider_html .= '</div>'; // slider_item
            $item_count++;
        }

        // Free version overrides
        if (!$is_premium) {
            $settings['slider_autoplay'] = '';
            $settings['slider_autoplay_duration'] = 0;
            $settings['slider_pause_on_hover'] = '';
        }

        // If slider_effect == 'sl_vl', convert it to 'slide' (matching the original logic)
        if ($settings['slider_effect'] === 'sl_vl') {
            $settings['slider_effect'] = 'slide';
        }

        $slider_is_rtl = is_rtl();
        $slider_dir = $slider_is_rtl ? 'rtl' : 'ltr';

        // Video button sizes for different devices
        $slider_video_btn_widescreen = $settings['slider_video_btn_size_widescreen'] ?? $settings['slider_video_btn_size'];
        $slider_video_btn_desktop = $settings['slider_video_btn_size'] ?? $slider_video_btn_widescreen;
        $slider_video_btn_laptop = $settings['slider_video_btn_size_laptop'] ?? $slider_video_btn_desktop;
        $slider_video_btn_tablet_ext = $settings['slider_video_btn_size_tablet_extra'] ?? $slider_video_btn_laptop;
        $slider_video_btn_tablet = $settings['slider_video_btn_size_tablet'] ?? $slider_video_btn_tablet_ext;
        $slider_video_btn_mobile_ext = $settings['slider_video_btn_size_mobile_extra'] ?? $slider_video_btn_tablet;
        $slider_video_btn_mobile = $settings['slider_video_btn_size_mobile'] ?? $slider_video_btn_mobile_ext;

        // Slick slider options
        $slider_options = [
            'rtl' => $slider_is_rtl,
            'infinite' => ($settings['slider_loop'] === 'yes'),
            'speed' => absint($settings['slider_effect_duration'] * 1000),
            'arrows' => true,
            'dots' => true,
            'autoplay' => ($settings['slider_autoplay'] === 'yes'),
            'autoplaySpeed' => absint($settings['slider_autoplay_duration'] * 1000),
            'pauseOnHover' => esc_attr($settings['slider_pause_on_hover']),
            'prevArrow' => '#king-addons-slider-prev-' . $this->get_id(),
            'nextArrow' => '#king-addons-slider-next-' . $this->get_id(),
            'vertical' => ($settings['slider_effect'] === 'slide_vertical'),
            'adaptiveHeight' => true,
        ];

        // Pass data to HTML attributes
        $this->add_render_attribute('advanced-slider-attribute', [
            'class' => 'king-addons-advanced-slider',
            'dir' => esc_attr($slider_dir),
            'data-slick' => wp_json_encode($slider_options),
            'data-video-btn-size' => wp_json_encode([
                'widescreen' => esc_attr($slider_video_btn_widescreen),
                'desktop' => esc_attr($slider_video_btn_desktop),
                'laptop' => esc_attr($slider_video_btn_laptop),
                'tablet_extra' => esc_attr($slider_video_btn_tablet_ext),
                'tablet' => esc_attr($slider_video_btn_tablet),
                'mobile_extra' => esc_attr($slider_video_btn_mobile_ext),
                'mobile' => esc_attr($slider_video_btn_mobile),
            ]),
        ]);
        ?>

        <div class="king-addons-advanced-slider-wrap">
            <div
                <?php echo $this->get_render_attribute_string('advanced-slider-attribute'); ?>
                    data-slide-effect="<?php echo esc_attr($settings['slider_effect']); ?>"
            >
                <?php
                // Output the constructed HTML string for slides
                echo $slider_html;
                ?>
            </div>

            <div class="king-addons-slider-controls">
                <div class="king-addons-slider-dots"></div>
            </div>

            <div class="king-addons-slider-arrow-container">
                <div class="king-addons-slider-prev-arrow king-addons-slider-arrow"
                     id="<?php echo 'king-addons-slider-prev-' . esc_attr($this->get_id()); ?>">
                    <?php echo Core::getIcon($settings['slider_nav_icon'], ''); ?>
                </div>
                <div class="king-addons-slider-next-arrow king-addons-slider-arrow"
                     id="<?php echo 'king-addons-slider-next-' . esc_attr($this->get_id()); ?>">
                    <?php echo Core::getIcon($settings['slider_nav_icon'], ''); ?>
                </div>
            </div>

            <?php
            if ($settings['slider_scroll_btn'] === 'yes') {
                $this->render_pro_element_slider_scroll_btn();
            }
            ?>
        </div>
        <?php
    }
}