<?php

namespace King_Addons;

use Elementor\Icons_Manager;
use Elementor\Widget_Base;
use Elementor\Utils;
use Elementor\Repeater;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Typography;
use WP_Query;

class Timeline extends Widget_Base
{

    public function get_name()
    {
        return 'king-addons-timeline';
    }

    public function get_title()
    {
        return esc_html__('Timeline Post/Story', 'king-addons');
    }

    public function get_icon()
    {
        return 'king-addons-icon king-addons-timeline';
    }

    public function get_categories()
    {
        return ['king-addons'];
    }

    public function get_keywords()
    {
        return ['king addons', 'king', 'addons', 'kingaddons', 'king-addons', 'post timeline', 'time',
            'line', 'story', 'telling', 'storytelling', 'content', 'grid', 'scroll', 'events', 'event',
            'blog', 'post', 'posts', 'timeline', 'posts timeline', 'story timeline', 'content timeline'];
    }

    public function get_script_depends()
    {

        return [
            'swiper',
            KING_ADDONS_ASSETS_UNIQUE_KEY . '-infinitescroll-infinitescroll',
            KING_ADDONS_ASSETS_UNIQUE_KEY . '-aos-aos',
            KING_ADDONS_ASSETS_UNIQUE_KEY . '-timeline-script'
        ];
    }

    public function get_style_depends()
    {
        return [
            'swiper',
            'e-swiper',
            KING_ADDONS_ASSETS_UNIQUE_KEY . '-animation-button',
            KING_ADDONS_ASSETS_UNIQUE_KEY . '-animation-general',
            KING_ADDONS_ASSETS_UNIQUE_KEY . '-animation-timing',
            KING_ADDONS_ASSETS_UNIQUE_KEY . '-animation-loading',
            KING_ADDONS_ASSETS_UNIQUE_KEY . '-aos-aos',
            KING_ADDONS_ASSETS_UNIQUE_KEY . '-timeline-style'
        ];
    }

    public function get_custom_help_url()
    {
        return 'https://kingaddons.com/';
    }

    /** @noinspection PhpMissingFieldTypeInspection */
    public $alt,
        $animation,
        $animation_class,
        $animation_loadmore_left,
        $animation_loadmore_right,
        $background_class,
        $background_image,
        $date_label_key,
        $description_key,
        $extra_label_key,
        $horizontal_inner_class,
        $horizontal_timeline_class,
        $image,
        $item_url_count,
        $my_query,
        $pagination_max_pages,
        $pagination_type,
        $show_readmore,
        $show_year_label,
        $slides_to_show,
        $src,
        $story_date_label,
        $story_extra_label,
        $swiper_class,
        $thumbnail_custom_dimension,
        $thumbnail_size,
        $timeline_description,
        $timeline_fill,
        $timeline_layout,
        $timeline_layout_wrapper,
        $timeline_story_title,
        $timeline_year,
        $title_key,
        $year_key;

    public function king_addons_aos_animation_array()
    {
        return [
            'none' => 'None',
            'fade' => 'Fade',
            'pro-fd' => 'Fade Down (Pro)',
            'pro-fdl' => 'Fade Down Left (Pro)',
            'pro-fdr' => 'Fade Down Right (Pro)',
            'pro-fl' => 'Fade Left (Pro)',
            'pro-fr' => 'Fade Right (Pro)',
            'pro-fu' => 'Fade Up (Pro)',
            'pro-ful' => 'Fade Up Left (Pro)',
            'pro-fur' => 'Fade Up Right (Pro)',
            'pro-fld' => 'Flip Down (Pro)',
            'pro-fll' => 'Flip Left (Pro)',
            'pro-flr' => 'Flip Right (Pro)',
            'pro-flu' => 'Flip Up (Pro)',
            'pro-sld' => 'Slide Down (Pro)',
            'pro-sll' => 'Slide Left (Pro)',
            'pro-slr' => 'Slide Right (Pro)',
            'pro-slu' => 'Slide Up (Pro)',
            'pro-zmi' => 'Zoom In (Pro)',
            'pro-zmid' => 'Zoom In Down (Pro)',
            'pro-zmil' => 'Zoom In Left (Pro)',
            'pro-zmir' => 'Zoom In Right (Pro)',
            'pro-zmiu' => 'Zoom In Up (Pro)',
            'pro-zmo' => 'Zoom Out (Pro)',
            'pro-zmod' => 'Zoom Out Down (Pro)',
            'pro-zmol' => 'Zoom Out Left (Pro)',
            'pro-zmor' => 'Zoom Out Right (Pro)',
            'pro-zmou' => 'Zoom Out Up (Pro)',
        ];
    }

    public function add_control_slides_to_show()
    {
        $this->add_control(
            'slides_to_show',
            [
                'label' => esc_html__('Slides To Show', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'default' => '3',
                'max' => '4',
                'separator' => 'before',
                'render_type' => 'template',
                'condition' => [
                    'timeline_layout' => [
                        'horizontal',
                        'horizontal-bottom'
                    ],
                ]
            ]
        );
    }

    public function add_control_swiper_loop()
    {
        $this->add_control(
            'swiper_loop',
            [
                'label' => sprintf(__('Loop %s', 'king-addons'), '<i class="eicon-pro-icon"></i>'),
                'type' => Controls_Manager::SWITCHER,
                'separator' => 'before',
                'classes' => 'king-addons-pro-control'
            ]
        );
    }

    public function add_control_group_autoplay()
    {
        $this->add_control(
            'swiper_autoplay',
            [
                'label' => sprintf(__('Autoplay %s', 'king-addons'), '<i class="eicon-pro-icon"></i>'),
                'type' => Controls_Manager::SWITCHER,
                'separator' => 'before',
                'classes' => 'king-addons-pro-control'
            ]
        );
    }

    public function add_control_show_pagination()
    {
        $this->add_control(
            'show_pagination',
            [
                'label' => sprintf(__('Show Pagination %s', 'king-addons'), '<i class="eicon-pro-icon"></i>'),
                'type' => Controls_Manager::SWITCHER,
                'separator' => 'before',
                'classes' => 'king-addons-pro-control'
            ]
        );
    }

    public function add_control_posts_per_page()
    {
        $this->add_control(
            'posts_per_page',
            [
                'label' => esc_html__('Posts Per Page', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'render_type' => 'template',
                'default' => 3,
                'max' => 4,
                'min' => 0,
                'label_block' => false,
            ]
        );
    }

    protected function register_controls()
    {

        $this->start_controls_section(
            'general_section',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Layout', 'king-addons'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );


        $this->add_control(
            'timeline_content',
            [
                'label' => esc_html__('Timeline Content', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'custom',
                'options' => [
                    'custom' => esc_html__('Custom', 'king-addons'),
                    'dynamic' => esc_html__('Dynamic', 'king-addons')
                ],
                'render_type' => 'template',
            ]
        );

        $this->add_control(
            'timeline_layout',
            [
                'label' => esc_html__('Layout', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'centered',
                'options' => [
                    'centered' => esc_html__('Zig-Zag', 'king-addons'),
                    'one-sided' => esc_html__('Line Left', 'king-addons'),
                    'one-sided-left' => esc_html__('Line Right', 'king-addons'),
                    'horizontal-bottom' => esc_html__('Line Top - Carousel', 'king-addons'),
                    'horizontal' => esc_html__('Line Bottom - Carousel', 'king-addons'),
                ],
                'render_type' => 'template',
            ]
        );

        $this->add_control(
            'content_layout',
            [
                'label' => esc_html__('Media Position', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'image-top',
                'options' => [
                    'image-top' => esc_html__('Top', 'king-addons'),
                    'image-bottom' => esc_html__('Bottom', 'king-addons'),

                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name' => 'king_addons_thumbnail_dynamic',
                'default' => 'full',
                'separator' => 'none',
                'condition' => [
                    'timeline_content' => 'dynamic'
                ]
            ]
        );

        $this->add_control(
            'date_format',
            [
                'label' => esc_html__('Date Format', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'F j, Y',
                'options' => [
                    'F j, Y' => esc_html(date('F j, Y')),
                    'Y-m-d' => esc_html(date('Y-m-d')),
                    'Y, M, D' => esc_html(date('Y, M, D')),
                    'm/d/Y' => esc_html(date('m/d/Y')),
                    'd/m/Y' => esc_html(date('d/m/Y')),
                    'j. F Y' => esc_html(date('j. F y'))
                ],
                'condition' => [
                    'timeline_content' => 'dynamic',
                ]
            ]
        );

        $this->add_control(
            'timeline_fill',
            [
                'label' => esc_html__('Main Line Fill', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default' => 'yes',
                'label_block' => false,
                'render_type' => 'template',
                'condition' => [
                    'timeline_layout!' => ['horizontal', 'horizontal-bottom']
                ]
            ]
        );

        $this->add_control(
            'posts_icon',
            [
                'label' => esc_html__('Main Line Icon', 'king-addons'),
                'type' => Controls_Manager::ICONS,
                'skin' => 'inline',
                'label_block' => false,
                'default' => [
                    'value' => 'fab fa-apple',
                    'library' => 'solid',
                ],
                'condition' => [
                    'timeline_content' => 'dynamic'
                ]
            ]
        );

        $this->add_control(
            'show_extra_label',
            [
                'label' => esc_html__('Show Extra Label', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default' => 'yes',
                'label_block' => false,
                'separator' => 'before',
                'condition' => [
                    'timeline_content' => 'dynamic'
                ]
            ]
        );

        $this->add_control_slides_to_show();

        if (!king_addons_freemius()->can_use_premium_code__premium_only()) {
            $this->add_control(
                'slides_to_show_pro_notice',
                [
                    'type' => Controls_Manager::RAW_HTML,
                    'raw' => 'More than 4 Slides are available<br> in the <strong><a href="https://kingaddons.com/pricing/?utm_source=kng-module-posts-timeline-settings-upgrade-pro&utm_medium=plugin&utm_campaign=kng" target="_blank">Pro version</a></strong>',

                    'content_classes' => 'king-addons-pro-notice',
                    'condition' => [
                        'timeline_layout' => [
                            'horizontal',
                            'horizontal-bottom'
                        ],
                    ]
                ]
            );
        }

        $this->add_control(
            'story_info_gutter',
            [
                'label' => esc_html__('Gutter', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'default' => 5,
                'condition' => [
                    'timeline_layout' => [
                        'horizontal',
                        'horizontal-bottom'
                    ],
                ]
            ]
        );

        $this->add_control(
            'equal_height_slides',
            [
                'label' => esc_html__('Equal Height Slides', 'king-addons'),
                'description' => esc_html__('Make all slides the same height', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'auto-height',
                'default' => 'no',
                'label_block' => false,
                'render_type' => 'template',
                'condition' => [
                    'timeline_layout' => [
                        'horizontal-bottom',
                        'horizontal'
                    ],
                ]
            ]
        );


        $this->add_control_swiper_loop();

        $this->add_control_group_autoplay();

        $this->add_control(
            'swiper_speed',
            [
                'label' => esc_html__('Carousel Speed', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 5000,
                'frontend_available' => true,
                'default' => 500,
                'condition' => [
                    'timeline_layout' => ['horizontal', 'horizontal-bottom']
                ]
            ]
        );

        $this->add_control(
            'swiper_nav_icon',
            [
                'label' => esc_html__('Carousel Icon', 'king-addons'),
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
                    'timeline_layout' => ['horizontal', 'horizontal-bottom'],
                ],

            ]
        );

        $this->add_control(
            'timeline_animation',
            [
                'label' => esc_html__('Entrance Animation', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'fade',
                'separator' => 'before',
                'options' => $this->king_addons_aos_animation_array(),
                'condition' => [
                    'timeline_layout!' => [
                        'horizontal',
                        'horizontal-bottom'
                    ],
                ]
            ]
        );


        Core::renderUpgradeProNotice($this, Controls_Manager::RAW_HTML, 'timeline', 'timeline_animation', [
            'pro-fu',
            'pro-fd',
            'pro-fl',
            'pro-fr',
            'pro-fur',
            'pro-ful',
            'pro-fdr',
            'pro-fdl',
            'pro-flu',
            'pro-fld',
            'pro-flr',
            'pro-fll',
            'pro-slu',
            'pro-sll',
            'pro-slr',
            'pro-sld',
            'pro-zmi',
            'pro-zmo',
            'pro-zmiu',
            'pro-zmid',
            'pro-zmil',
            'pro-zmir',
            'pro-zmou',
            'pro-zmod',
            'pro-zmol',
            'pro-zmor',
        ]);

        $this->add_control(
            'animation_offset',
            [
                'label' => esc_html__('Animation Offset', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 500,
                'frontend_available' => true,
                'default' => 150,
                'condition' => [
                    'timeline_layout!' => ['horizontal', 'horizontal-bottom']
                ]
            ]
        );

        $this->add_control(
            'aos_animation_duration',
            [
                'label' => esc_html__('Animation Duration', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 2000,
                'frontend_available' => true,
                'default' => 600,
                'condition' => [
                    'timeline_layout!' => ['horizontal', 'horizontal-bottom']
                ]
            ]
        );

        $this->add_control_show_pagination();

        $this->end_controls_section();

        $this->start_controls_section(
            'repeater_content_section',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Timeline Items', 'king-addons'),
                'tab' => Controls_Manager::TAB_CONTENT,
                'condition' => [
                    'timeline_content' => 'custom'
                ]
            ]
        );

        $repeater = new Repeater();

        $repeater->start_controls_tabs(
            'story_tabs'
        );

        $repeater->start_controls_tab(
            'content_tab',
            [
                'label' => esc_html__('Content', 'king-addons'),
            ]
        );

        $repeater->add_control(
            'main_line_label_heading',
            [
                'label' => esc_html__('Main Line Label', 'king-addons'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $repeater->add_control(
            'repeater_show_year_label',
            [
                'label' => esc_html__('Show Label', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'king-addons'),
                'label_off' => esc_html__('Hide', 'king-addons'),
                'return_value' => 'yes',
                'default' => 'no',
            ]
        );

        $repeater->add_control(
            'repeater_year',
            [
                'label' => esc_html__('Label Text', 'king-addons'),
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => '2022',
                'condition' => [
                    'repeater_show_year_label' => 'yes'
                ]
            ]
        );

        $repeater->add_control(
            'main_line_label_icon',
            [
                'label' => esc_html__('Main Line Icon', 'king-addons'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $repeater->add_control(
            'repeater_story_icon',
            [
                'label' => esc_html__('Select Icon', 'king-addons'),
                'type' => Controls_Manager::ICONS,
                'skin' => 'inline',
                'label_block' => false,
                'default' => [
                    'value' => 'fab fa-apple',
                    'library' => 'solid',
                ],
            ]
        );

        $repeater->add_control(
            'extra_label_heading',
            [
                'label' => esc_html__('Extra Label', 'king-addons'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $repeater->add_control(
            'repeater_show_extra_label',
            [
                'label' => esc_html__('Show Extra Label', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default' => 'yes',
                'label_block' => false,
            ]
        );

        $repeater->add_control(
            'repeater_date_label',
            [
                'label' => esc_html__('Primary Label', 'king-addons'),
                'label_block' => true,
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => '01 Jan 2022',
                'condition' => [
                    'repeater_show_extra_label' => 'yes'
                ]
            ]
        );

        $repeater->add_control(
            'repeater_extra_label',
            [
                'label' => esc_html__('Secondary Label', 'king-addons'),
                'label_block' => true,
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => 'Secondary Label',
                'condition' => [
                    'repeater_show_extra_label' => 'yes'
                ]
            ]
        );

        $repeater->add_control(
            'repeater_media',
            [
                'label' => esc_html__('Display Media', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'image',
                'options' => [
                    'image' => esc_html__('Image', 'king-addons'),
                    'icon' => esc_html__('Icon', 'king-addons'),
                    'video' => esc_html__('Video', 'king-addons'),
                ],
                'render_type' => 'template',
                'separator' => 'before'
            ]
        );

        $repeater->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name' => 'king_addons_thumbnail',
                'default' => 'full',
                'separator' => 'none',
                'condition' => [
                    'repeater_media' => 'image'
                ]
            ]
        );

        $repeater->add_control(
            'repeater_youtube_video_url',
            [
                'label' => esc_html__('Youtube Video Link', 'king-addons'),
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'label_block' => true,
                'default' => '',
                'condition' => [
                    'repeater_media' => 'video',
                ]
            ]
        );

        $repeater->add_control(
            'repeater_image',
            [
                'label' => esc_html__('Choose Image', 'king-addons'),
                'type' => Controls_Manager::MEDIA,
                'dynamic' => [
                    'active' => true
                ],
                'description' => esc_html__('Image Size will not work with default image', 'king-addons'),
                'condition' => [
                    'repeater_media' => 'image'
                ]
            ]
        );

        $repeater->add_control(
            'repeater_timeline_item_icon',
            [
                'label' => esc_html__('Media Icon', 'king-addons'),
                'type' => Controls_Manager::ICONS,
                'skin' => 'inline',
                'label_block' => false,
                'default' => [
                    'value' => 'far fa-star',
                    'library' => 'solid',
                ],
                'condition' => [
                    'repeater_media' => 'icon'
                ]
            ]
        );

        $repeater->add_control(
            'repeater_story_title',
            [
                'label' => esc_html__('Item Title', 'king-addons'),
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => 'Timeline Story',
                'label_block' => true,
                'separator' => 'before'
            ]
        );

        $repeater->add_control(
            'repeater_title_link',
            [
                'label' => esc_html__('Item Title URL', 'king-addons'),
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
            ]
        );

        $repeater->add_control(
            'repeater_description',
            [
                'label' => esc_html__('Description', 'king-addons'),
                'type' => Controls_Manager::WYSIWYG,
                'default' => 'Add Description Here',
            ]
        );

        $repeater->end_controls_tab();

        $repeater->start_controls_tab(
            'repeater_advanced_tab',
            [
                'label' => esc_html__('Style', 'king-addons'),
            ]
        );

        $repeater->add_control(
            'show_custom_styles',
            [
                'label' => esc_html__('Custom Colors', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'label_block' => false
            ]
        );

        $repeater->add_control(
            'item_main_styles',
            [
                'label' => esc_html__('Item', 'king-addons'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'show_custom_styles' => 'yes'
                ]
            ]
        );

        $repeater->add_control(
            'item_bg_color',
            [
                'label' => esc_html__('Background Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#FFF',
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .king-addons-story-info-vertical.king-addons-data-wrap' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .king-addons-horizontal-bottom-timeline {{CURRENT_ITEM}} .king-addons-story-info' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .king-addons-horizontal {{CURRENT_ITEM}} .king-addons-story-info' => 'background-color: {{VALUE}}'
                ],
                'condition' => [
                    'show_custom_styles' => 'yes'
                ]
            ]
        );

        $repeater->add_control(
            'repeater_story_border_color',
            [
                'label' => esc_html__('Border Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .king-addons-story-info' => 'border-color: {{VALUE}} !important;',
                    '{{WRAPPER}} {{CURRENT_ITEM}}.king-addons-left-aligned .king-addons-story-info-vertical' => 'border-color: {{VALUE}} !important;',
                    '{{WRAPPER}} {{CURRENT_ITEM}}.king-addons-right-aligned .king-addons-story-info-vertical' => 'border-color: {{VALUE}} !important;',


                    '{{WRAPPER}} {{CURRENT_ITEM}}.swiper-slide-line-top .king-addons-story-info:before' => 'border-bottom-color: {{VALUE}} !important;',
                    '{{WRAPPER}} {{CURRENT_ITEM}}.swiper-slide-line-bottom .king-addons-story-info:before' => 'border-top-color: {{VALUE}} !important',
                    '{{WRAPPER}} {{CURRENT_ITEM}}.king-addons-left-aligned .king-addons-story-info-vertical:after' => 'border-left-color: {{VALUE}} !important',
                    'body[data-elementor-device-mode=mobile] {{WRAPPER}} .king-addons-wrapper .king-addons-both-sided-timeline .king-addons-left-aligned .king-addons-data-wrap:after' => 'border-right-color: {{VALUE}} !important; border-left-color: transparent !important;',
                    '{{WRAPPER}} .king-addons-centered .king-addons-one-sided-timeline .king-addons-right-aligned-aligned .king-addons-data-wrap:after' => 'border-right-color: {{VALUE}} !important; border-left-color: transparent !important;',
                ],
                'default' => '#5B03FF',
                'condition' => [
                    'show_custom_styles' => 'yes'
                ]
            ]
        );

        $repeater->add_control(
            'repeater_triangle_color',
            [
                'label' => esc_html__('Triangle', 'king-addons'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'show_custom_styles' => 'yes'
                ]
            ]
        );

        $repeater->add_control(
            'repeater_triangle_bgcolor',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-wrapper .king-addons-one-sided-timeline {{CURRENT_ITEM}}.king-addons-right-aligned .king-addons-data-wrap:after' => 'border-right-color: {{icon_bgcolor}}',
                    '{{WRAPPER}} .king-addons-wrapper .king-addons-one-sided-timeline-left {{CURRENT_ITEM}}.king-addons-left-aligned .king-addons-data-wrap:after' => 'border-left-color: {{VALUE}}',
                    '{{WRAPPER}} .king-addons-wrapper {{CURRENT_ITEM}}.king-addons-right-aligned .king-addons-data-wrap:after' => 'border-right-color: {{VALUE}}',
                    '{{WRAPPER}} .king-addons-horizontal {{CURRENT_ITEM}} .king-addons-story-info:before' => 'border-top-color: {{VALUE}} !important',
                    '{{WRAPPER}} .king-addons-horizontal-bottom {{CURRENT_ITEM}} .king-addons-story-info:before' => 'border-bottom-color: {{VALUE}} !important',
                    '{{WRAPPER}} .king-addons-wrapper {{CURRENT_ITEM}}.king-addons-left-aligned .king-addons-data-wrap:after' => 'border-left-color: {{VALUE}} !important',
                    '{{WRAPPER}} .king-addons-centered {{CURRENT_ITEM}} .king-addons-one-sided-timeline .king-addons-right-aligned .king-addons-data-wrap:after' => 'border-right-color: {{VALUE}} !important; border-left-color: transparent !important;',
                    '{{WRAPPER}} .king-addons-wrapper {{CURRENT_ITEM}} .king-addons-one-sided-timeline-left .king-addons-left-aligned .king-addons-data-wrap:after' => 'border-left-color: {{VALUE}} !important',
                ],
                'default' => '#5B03FF',
                'condition' => [
                    'show_custom_styles' => 'yes'
                ]

            ]
        );

        $repeater->add_control(
            'repeater_media_styles',
            [
                'label' => esc_html__('Media', 'king-addons'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'show_custom_styles' => 'yes'
                ]
            ]
        );

        $repeater->add_control(
            'repeater_overlay_bgcolor',
            [
                'label' => esc_html__('Overlay Background Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-wrapper {{CURRENT_ITEM}} .king-addons-timeline-story-overlay' => 'background-color: {{VALUE}}',
                ],
                'default' => '#0000005E',
                'condition' => [
                    'show_custom_styles' => 'yes'
                ]
            ]
        );

        $repeater->add_control(
            'repeater_media_item_border_color',
            [
                'label' => esc_html__('Border Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#E8E8E8',
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .king-addons-timeline-media' => 'border-color: {{VALUE}}',
                ],
                'condition' => [
                    'show_custom_styles' => 'yes'
                ]
            ]
        );

        $repeater->add_control(
            'repeater_item_content_styles',
            [
                'label' => esc_html__('Content', 'king-addons'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'show_custom_styles' => 'yes'
                ]
            ]
        );


        $repeater->add_control(
            'repeater_story_title_color',
            [
                'label' => esc_html__('Title Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .king-addons-title' => 'color: {{VALUE}} !important;',
                ],
                'default' => '#444444',
                'condition' => [
                    'show_custom_styles' => 'yes'
                ]
            ]
        );

        $repeater->add_control(
            'repeater_description_color',
            [
                'label' => esc_html__('Description Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-wrapper {{CURRENT_ITEM}} .king-addons-description' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .king-addons-wrapper {{CURRENT_ITEM}} .king-addons-description p' => 'color: {{VALUE}};'
                ],
                'default' => '#333333',
                'condition' => [
                    'show_custom_styles' => 'yes'
                ]
            ]
        );

        $repeater->add_control(
            'item_content_border_color',
            [
                'label' => esc_html__('Border Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#E8E8E8',
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .king-addons-timeline-content-wrapper' => 'border-color: {{VALUE}}',
                ],
                'condition' => [
                    'show_custom_styles' => 'yes'
                ]
            ]
        );

        $repeater->add_control(
            'repeater_main_line_content_styles',
            [
                'label' => esc_html__('Main Line', 'king-addons'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'show_custom_styles' => 'yes'
                ]
            ]
        );

        $repeater->add_control(
            'repeater_timeline_icon_color',
            [
                'label' => esc_html__('Icon Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}  .king-addons-icon i' => 'color: {{VALUE}}',
                    '{{WRAPPER}} {{CURRENT_ITEM}}  .king-addons-icon svg' => 'fill: {{VALUE}}'
                ],
                'default' => '#000',
                'condition' => [
                    'show_custom_styles' => 'yes'
                ]
            ]
        );

        $repeater->add_control(
            'repeater_icon_timeline_fill_color',
            [
                'label' => esc_html__('Icon Fill Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#fff',
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .king-addons-change-border-color.king-addons-icon i' => 'color: {{VALUE}} !important;',
                    '{{WRAPPER}} {{CURRENT_ITEM}} .king-addons-change-border-color.king-addons-icon svg' => 'fill: {{VALUE}} !important;',
                ],
                'condition' => [
                    'show_custom_styles' => 'yes'
                ]
            ]
        );

        $repeater->add_control(
            'repeater_timeline_icon_bg_color',
            [
                'label' => esc_html__('Icon Background Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-wrapper {{CURRENT_ITEM}} .king-addons-icon' => 'background-color: {{VALUE}} !important;',
                ],
                'default' => '#FFFFF',
                'condition' => [
                    'show_custom_styles' => 'yes'
                ]
            ]
        );

        $repeater->add_control(
            'repeater_icon_timeline_background_fill_color',
            [
                'label' => esc_html__('Icon Background Fill Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#FFFFFF',
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .king-addons-change-border-color.king-addons-icon' => 'background-color: {{VALUE}} !important;',
                ],
                'condition' => [
                    'show_custom_styles' => 'yes'
                ],
            ]
        );

        $repeater->add_control(
            'repeater_icon_border_color',
            [
                'label' => esc_html__('Icon Border Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#000',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-wrapper {{CURRENT_ITEM}} .king-addons-icon' => 'border-color: {{VALUE}}',
                ],
                'condition' => [
                    'show_custom_styles' => 'yes'
                ]
            ]
        );

        $repeater->add_control(
            'item_icon_styles',
            [
                'label' => esc_html__('Media Icon', 'king-addons'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'repeater_media' => 'icon',
                ],
            ]
        );

        $repeater->add_control(
            'repeater_timeline_item_icon_color',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}  .king-addons-timeline-media i' => 'color: {{VALUE}}',
                    '{{WRAPPER}} {{CURRENT_ITEM}}  .king-addons-timeline-media svg' => 'fill: {{VALUE}}'
                ],
                'condition' => [
                    'repeater_media' => 'icon',
                ],
                'default' => '#000',
            ]
        );

        $repeater->add_control(
            'repeater_timeline_item_icon_bgcolor',
            [
                'label' => esc_html__('Background Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .king-addons-timeline-media' => 'background-color: {{VALUE}}',
                ],
                'condition' => [
                    'repeater_media' => 'icon',
                ],
                'default' => '#FFF',
            ]
        );

        $repeater->add_responsive_control(
            'repeater_timeline_item_icon_size',
            [
                'label' => esc_html__('Size', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 600,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 40,
                ],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .king-addons-timeline-media i' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} {{CURRENT_ITEM}} .king-addons-timeline-media svg' => 'width: {{SIZE}}{{UNIT}};',

                ],
                'condition' => [
                    'repeater_media' => 'icon',
                ]
            ]
        );

        $repeater->add_responsive_control(
            'repeater_timeline_item_icon_padding',
            [
                'label' => esc_html__('Padding', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'default' => [
                    'top' => 20,
                    'right' => 20,
                    'bottom' => 20,
                    'left' => 20,
                ],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .king-addons-timeline-media' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [


                    'repeater_media' => 'icon'
                ],
            ]
        );

        $repeater->add_responsive_control(
            'repeater_timeline_item_icon_alignment',
            [
                'label' => esc_html__('Align', 'king-addons'),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'default' => 'center',
                'options' => [
                    'left' => [
                        'title' => esc_html__('Start', 'king-addons'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'king-addons'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__('End', 'king-addons'),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .king-addons-timeline-media i' => 'display: block; text-align: {{VALUE}};',
                    '{{WRAPPER}} {{CURRENT_ITEM}} .king-addons-timeline-media svg' => 'text-align: {{VALUE}};'
                ],
                'condition' => [

                    'repeater_media' => 'icon'
                ]
            ]
        );

        $repeater->end_controls_tab();

        $repeater->end_controls_tabs();

        $this->add_control(
            'timeline_repeater_list',
            [
                'label' => esc_html__('Content', 'king-addons'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'repeater_story_title' => esc_html__('Timeline Item 1', 'king-addons'),
                        'repeater_description' => esc_html__('Lorem ipsum dolor sit amet, consectetur adipisicing elit. Minima incidunt voluptates nemo.', 'king-addons'),
                        'repeater_year' => esc_html__('2023', 'king-addons'),
                        'repeater_date_label' => esc_html__('January 2023', 'king-addons'),
                        'repeater_extra_label' => esc_html__('Company Established', 'king-addons'),
                        'repeater_story_icon' => [
                            'value' => 'far fa-star',
                            'library' => 'solid'
                        ],
                        'repeater_show_year_label' => 'yes',
                        'repeater_image' => [
                            'url' => Utils::get_placeholder_image_src(),
                            'id' => '',
                        ],
                        'repeater_youtube_video_url' => '',
                        'item_bg_color' => '#f44771',
                        'repeater_triangle_bgcolor' => '#f44771',
                        'repeater_overlay_bgcolor' => '#0000005E',
                        'repeater_story_title_color' => '#FCFCFC',
                        'repeater_description_color' => '#ECECEC',
                        'repeater_timeline_icon_bg_color' => '',
                        'item_content_border_color' => '#E8E8E8',
                        'repeater_timeline_icon_color' => '#E8E8E8',
                        'repeater_icon_timeline_fill_color' => '#f44771',
                        'repeater_icon_timeline_background_fill_color' => '#FFFFFF',
                        'repeater_icon_border_color' => '#E8E8E8'
                    ],
                    [
                        'repeater_story_title' => esc_html__('Timeline Item 2', 'king-addons'),
                        'repeater_description' => esc_html__('Lorem ipsum dolor sit amet, consectetur adipisicing elit. Minima incidunt voluptates nemo.', 'king-addons'),
                        'repeater_year' => esc_html__('2023', 'king-addons'),
                        'repeater_date_label' => esc_html__('February 2023', 'king-addons'),
                        'repeater_extra_label' => esc_html__('New office in California', 'king-addons'),
                        'repeater_story_icon' => [
                            'value' => 'fas fa-crown',
                            'library' => 'solid'
                        ],
                        'repeater_image' => [
                            'url' => Utils::get_placeholder_image_src(),
                            'id' => '',
                        ],
                        'repeater_youtube_video_url' => '',
                        'item_bg_color' => '#f44771',
                        'repeater_triangle_bgcolor' => '#f44771',
                        'repeater_overlay_bgcolor' => '#0000005E',
                        'repeater_story_title_color' => '#FCFCFC',
                        'repeater_description_color' => '#ECECEC',
                        'repeater_timeline_icon_bg_color' => '',
                        'item_content_border_color' => '#E8E8E8',
                        'repeater_timeline_icon_color' => '#E8E8E8',
                        'repeater_icon_timeline_fill_color' => '#f44771',
                        'repeater_icon_timeline_background_fill_color' => '#FFFFFF',
                        'repeater_icon_border_color' => '#E8E8E8'
                    ],
                    [
                        'repeater_story_title' => esc_html__('Timeline Item 3', 'king-addons'),
                        'repeater_description' => esc_html__('Lorem ipsum dolor sit amet, consectetur adipisicing elit. Minima incidunt voluptates nemo.', 'king-addons'),
                        'repeater_year' => esc_html__('2023', 'king-addons'),
                        'repeater_date_label' => esc_html__('March 2023', 'king-addons'),
                        'repeater_extra_label' => esc_html__('First Product Launch', 'king-addons'),
                        'repeater_story_icon' => [
                            'value' => 'fas fa-rocket',
                            'library' => 'solid'
                        ],
                        'repeater_show_year_label' => 'yes',
                        'repeater_image' => [
                            'url' => Utils::get_placeholder_image_src(),
                            'id' => '',
                        ],
                        'repeater_youtube_video_url' => '',
                        'item_bg_color' => '#f44771',
                        'repeater_triangle_bgcolor' => '#f44771',
                        'repeater_overlay_bgcolor' => '#0000005E',
                        'repeater_story_title_color' => '#FCFCFC',
                        'repeater_description_color' => '#FDFDFD',
                        'item_content_border_color' => '#E8E8E8',
                        'repeater_timeline_icon_bg_color' => '',
                        'repeater_timeline_icon_color' => '#E8E8E8',
                        'repeater_icon_timeline_fill_color' => '#f44771',
                        'repeater_icon_timeline_background_fill_color' => '#FFFFFF',
                        'repeater_icon_border_color' => '#E8E8E8'
                    ],
                    [
                        'repeater_story_title' => esc_html__('Timeline Item 4', 'king-addons'),
                        'repeater_description' => esc_html__('Lorem ipsum dolor sit amet, consectetur adipisicing elit. Minima incidunt voluptates nemo.', 'king-addons'),
                        'repeater_year' => esc_html__('2023', 'king-addons'),
                        'repeater_date_label' => esc_html__('April 2023', 'king-addons'),
                        'repeater_extra_label' => esc_html__('Entering Stock Market', 'king-addons'),
                        'repeater_story_icon' => [
                            'value' => 'fas fa-bolt',
                            'library' => 'solid'
                        ],
                        'repeater_image' => [
                            'url' => Utils::get_placeholder_image_src(),
                            'id' => '',
                        ],
                        'repeater_youtube_video_url' => '',
                        'item_bg_color' => '#f44771',
                        'repeater_triangle_bgcolor' => '#f44771',
                        'repeater_overlay_bgcolor' => '#0000005E',
                        'repeater_story_title_color' => '#FCFCFC',
                        'repeater_description_color' => '#F3F3F3',
                        'item_content_border_color' => '#E8E8E8',
                        'repeater_timeline_icon_bg_color' => '',
                        'repeater_timeline_icon_color' => '#E8E8E8',
                        'repeater_icon_timeline_fill_color' => '#f44771',
                        'repeater_icon_timeline_background_fill_color' => '#FFFFFF',
                        'repeater_icon_border_color' => '#E8E8E8'
                    ],
                ],
                'title_field' => '{{{ repeater_story_title }}}',
            ]
        );

        if (!king_addons_freemius()->can_use_premium_code__premium_only()) {
            $this->add_control(
                'timeline_repeater_pro_notice',
                [
                    'type' => Controls_Manager::RAW_HTML,
                    'raw' => 'More than 4 Slides are available<br> in the <strong><a href="https://kingaddons.com/pricing/?utm_source=kng-module-posts-timeline-settings-upgrade-pro&utm_medium=plugin&utm_campaign=kng" target="_blank">Pro version</a></strong>',

                    'content_classes' => 'king-addons-pro-notice',
                ]
            );
        }

        $this->end_controls_section();


        $post_types = $this->add_option_query_source();


        $post_taxonomies = Core::getCustomTypes('tax', false);

        $this->start_controls_section(
            'query_section',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Query', 'king-addons'),
                'condition' => [
                    'timeline_content' => 'dynamic',
                ]
            ]
        );

        $this->add_control(
            'timeline_post_types',
            [
                'label' => esc_html__('Post Type', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'post',
                'label_block' => false,
                'options' => $post_types,
            ]
        );

        Core::renderUpgradeProNotice($this, Controls_Manager::RAW_HTML, 'timeline', 'timeline_post_types', ['pro-rl']);

        if (!king_addons_freemius()->can_use_premium_code__premium_only()) {
            $this->add_control(
                'query_source_cpt_pro_notice',
                [
                    'raw' => 'This option is available<br> in the <strong><a href="https://kingaddons.com/pricing/?utm_source=kng-module-grid-upgrade-pro&utm_medium=plugin&utm_campaign=kng" target="_blank">Pro version</a></strong>',
                    'type' => Controls_Manager::RAW_HTML,
                    'content_classes' => 'king-addons-pro-notice',
                    'condition' => [
                        'timeline_post_types!' => ['post', 'page', 'related', 'current', 'pro-rl'],
                    ]
                ]
            );
        }

        $this->add_control(
            'query_selection',
            [
                'label' => esc_html__('Selection', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'dynamic',
                'options' => [
                    'dynamic' => esc_html__('Dynamic', 'king-addons'),
                    'manual' => esc_html__('Manual', 'king-addons'),
                ],
                'condition' => [
                    'timeline_post_types!' => ['current', 'related'],
                ],
            ]
        );

        $this->add_control(
            'query_tax_selection',
            [
                'label' => esc_html__('Selection Taxonomy', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'category',
                'options' => $post_taxonomies,
                'condition' => [
                    'timeline_post_types' => 'related',
                ],
            ]
        );


        foreach ($post_types as $slug => $title) {
            $this->add_control(
                'query_manual_' . $slug,
                [
                    'label' => esc_html__('Select ', 'king-addons') . $title,
                    'type' => 'king-addons-ajax-select2',
                    'options' => 'ajaxselect2/getPostsByPostType',
                    'query_slug' => $slug,
                    'multiple' => true,
                    'label_block' => true,
                    'condition' => [
                        'timeline_post_types' => $slug,
                        'query_selection' => 'manual',
                    ],
                    'separator' => 'before',
                ]
            );
        }

        $this->add_control(
            'query_author',
            [
                'label' => esc_html__('Authors', 'king-addons'),
                'type' => 'king-addons-ajax-select2',
                'options' => 'ajaxselect2/getUsers',
                'multiple' => true,
                'label_block' => true,
                'separator' => 'before',
                'condition' => [
                    'timeline_post_types!' => ['current', 'related'],
                    'query_selection' => 'dynamic',
                ],
            ]
        );

        foreach ($post_taxonomies as $slug => $title) {
            global $wp_taxonomies;
            $post_type = '';
            if (isset($wp_taxonomies[$slug]->object_type[0])) {
                $post_type = $wp_taxonomies[$slug]->object_type[0];
            }

            $this->add_control(
                'query_taxonomy_' . $slug,
                [
                    'label' => $title,
                    'type' => 'king-addons-ajax-select2',
                    'options' => 'ajaxselect2/getTaxonomies',
                    'query_slug' => $slug,
                    'multiple' => true,
                    'label_block' => true,
                    'condition' => [
                        'timeline_post_types' => $post_type,
                        'query_selection' => 'dynamic',
                    ],
                ]
            );
        }

        foreach ($post_types as $slug => $title) {
            $this->add_control(
                'query_exclude_' . $slug,
                [
                    'label' => esc_html__('Exclude ', 'king-addons') . $title,
                    'type' => 'king-addons-ajax-select2',
                    'options' => 'ajaxselect2/getPostsByPostType',
                    'query_slug' => $slug,
                    'multiple' => true,
                    'label_block' => true,
                    'condition' => [
                        'timeline_content' => 'dynamic',
                        'timeline_post_types' => $slug,
                        'timeline_post_types!' => ['current', 'related'],
                        'query_selection' => 'dynamic',
                    ],
                ]
            );
        }

        $this->add_control_posts_per_page();

        if (!king_addons_freemius()->can_use_premium_code__premium_only()) {
            $this->add_control(
                'posts_per_page_pro_notice',
                [
                    'type' => Controls_Manager::RAW_HTML,
                    'raw' => 'More than 4 Posts are available<br> in the <strong><a href="https://kingaddons.com/pricing/?utm_source=kng-module-posts-timeline-settings-upgrade-pro&utm_medium=plugin&utm_campaign=kng" target="_blank">Pro version</a></strong>',

                    'content_classes' => 'king-addons-pro-notice',
                ]
            );
        }

        $this->add_control(
            'order_posts',
            [
                'label' => esc_html__('Order By', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'date',
                'label_block' => false,
                'options' => [
                    'title' => esc_html__('Title', 'king-addons'),
                    'date' => esc_html__('Date', 'king-addons'),
                ],
                'condition' => [
                    'query_selection' => 'dynamic',
                ]
            ]
        );

        $this->add_control(
            'order_direction',
            [
                'label' => esc_html__('Order', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'DESC',
                'label_block' => false,
                'options' => [
                    'ASC' => esc_html__('Ascending', 'king-addons'),
                    'DESC' => esc_html__('Descending', 'king-addons'),
                ],
                'condition' => [
                    'query_selection' => 'dynamic',
                ]
            ]
        );

        $this->add_control(
            'query_not_found_text',
            [
                'label' => esc_html__('Not Found Text', 'king-addons'),
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => 'No Posts Found!',
                'condition' => [


                ]
            ]
        );

        $this->add_control(
            'query_exclude_no_images',
            [
                'label' => esc_html__('Exclude Items without Thumbnail', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'label_block' => false
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'content_section',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Content', 'king-addons'),
                'condition' => [

                ]
            ]
        );

        $this->add_responsive_control(
            'content_alignment_left',
            [
                'label' => esc_html__('Content Align', 'king-addons'),
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
                    '{{WRAPPER}} .king-addons-story-info' => 'text-align: {{VALUE}};',
                    '{{WRAPPER}} .king-addons-left-aligned .king-addons-story-info-vertical' => 'text-align: {{VALUE}};',
                    '{{WRAPPER}} .king-addons-left-aligned .king-addons-title-wrap' => 'text-align: {{VALUE}};',
                    '{{WRAPPER}} .king-addons-left-aligned .king-addons-description' => 'text-align: {{VALUE}};',
                    '{{WRAPPER}} .king-addons-left-aligned .king-addons-inner-date-label' => 'text-align: {{VALUE}};',
                    '{{WRAPPER}} .swiper-wrapper .king-addons-title-wrap' => 'text-align: {{VALUE}};',
                    '{{WRAPPER}} .swiper-wrapper .king-addons-description' => 'text-align: {{VALUE}};',
                    '{{WRAPPER}} .swiper-wrapper .king-addons-inner-date-label' => 'text-align: {{VALUE}};',
                    '{{WRAPPER}} .king-addons-title-wrap' => 'text-align: {{VALUE}};',
                ],
                'condition' => [
                    'timeline_layout' => ['centered', 'one-sided-left', 'horizontal', 'horizontal-bottom']
                ]
            ]
        );

        $this->add_responsive_control(
            'content_alignment',
            [
                'label' => esc_html__('Content Align (Right)', 'king-addons'),
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
                    '{{WRAPPER}} .king-addons-story-info' => 'text-align: {{VALUE}};',
                    '{{WRAPPER}} .king-addons-right-aligned .king-addons-story-info-vertical' => 'text-align: {{VALUE}};',
                    '{{WRAPPER}} .king-addons-right-aligned .king-addons-title-wrap' => 'text-align: {{VALUE}};',
                    '{{WRAPPER}} .king-addons-right-aligned .king-addons-description' => 'text-align: {{VALUE}};',
                    '{{WRAPPER}} .king-addons-right-aligned .king-addons-inner-date-label' => 'text-align: {{VALUE}};',
                    '{{WRAPPER}} .king-addons-title-wrap' => 'text-align: {{VALUE}};',
                ],
                'condition' => [
                    'timeline_layout' => ['centered', 'one-sided']
                ]
            ]
        );

        $this->add_control(
            'show_overlay',
            [
                'label' => esc_html__('Show Image Overlay', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default' => 'no',
                'label_block' => false,
                'separator' => 'before',
                'render_type' => 'template',
                'condition' => [
                    'content_layout' => 'image-top'
                ],
            ]
        );

        $this->add_control(
            'show_on_hover',
            [
                'label' => esc_html__('Show Items on Hover', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'label_block' => false,
                'selectors_dictionary' => [
                    'yes' => 'opacity: 0; transform: translateY(-50%); transition: all 0.5s ease',
                    'no' => 'visibility: visible;',
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-story-info' => '{{VALUE}}',
                    '{{WRAPPER}} .king-addons-horizontal-timeline .swiper-slide:hover .king-addons-story-info' => 'opacity: 1; transform: translateY(0%);'
                ],
                'condition' => [
                    'timeline_layout' => ['horizontal']
                ]
            ]
        );

        $this->add_control(
            'show_title',
            [
                'label' => esc_html__('Show Title', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default' => 'yes',
                'label_block' => false,
                'render_type' => 'template',
                'separator' => 'before'
            ]
        );

        $this->add_control(
            'title_overlay',
            [
                'label' => esc_html__('Title Over Image', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default' => 'no',
                'label_block' => false,
                'render_type' => 'template',
                'condition' => [
                    'show_overlay' => 'yes',
                    'content_layout' => 'image-top',
                    'show_title' => 'yes'
                ]
            ]
        );

        $this->add_control(
            'show_date',
            [
                'label' => esc_html__('Show Date', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default' => 'yes',
                'label_block' => false,
                'render_type' => 'template',
                'separator' => 'before',
                'condition' => [
                    'timeline_content' => 'dynamic'
                ]
            ]
        );

        $this->add_control(
            'date_overlay',
            [
                'label' => esc_html__('Date Over Image', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default' => 'no',
                'label_block' => false,

                'condition' => [
                    'show_overlay' => 'yes',
                    'content_layout' => 'image-top',
                    'timeline_content' => 'dynamic',
                    'show_date' => 'yes'
                ]
            ]
        );

        $this->add_control(
            'show_description',
            [
                'label' => esc_html__('Show Description', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default' => 'yes',
                'label_block' => false,
                'render_type' => 'template',
                'separator' => 'before',
                'condition' => [

                ]
            ]
        );

        $this->add_control(
            'description_overlay',
            [
                'label' => esc_html__('Description Over Image', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default' => 'no',
                'label_block' => false,
                'condition' => [
                    'show_overlay' => 'yes',
                    'content_layout' => 'image-top',
                    'show_description' => 'yes'
                ]
            ]
        );

        $this->add_control(
            'excerpt_count',
            [
                'label' => esc_html__('Excerpt Count', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 200,
                'render_type' => 'template',
                'frontend_available' => true,
                'default' => 10,
                'condition' => [
                    'timeline_content' => 'dynamic',
                    'show_description' => 'yes'
                ]
            ]
        );


        $this->add_control(
            'show_readmore',
            [
                'label' => esc_html__('Show Read More', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default' => 'yes',
                'label_block' => false,
                'render_type' => 'template',
                'separator' => 'before',
                'condition' => [
                    'timeline_content' => ['dynamic']
                ]
            ]
        );

        $this->add_control(
            'readmore_overlay',
            [
                'label' => esc_html__('Read More Over Image', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default' => 'no',
                'label_block' => false,
                'render_type' => 'template',
                'condition' => [
                    'show_overlay' => 'yes',
                    'show_readmore' => 'yes',
                    'content_layout' => 'image-top',
                    'timeline_content' => ['dynamic']
                ]
            ]
        );

        $this->add_responsive_control(
            'readmore_content_alignment_left',
            [
                'label' => esc_html__('Align', 'king-addons'),
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
                    '{{WRAPPER}} .king-addons-left-aligned .king-addons-read-more-wrap' => 'text-align: {{VALUE}};',
                    '{{WRAPPER}} .king-addons-left-aligned .king-addons-read-more-button' => 'text-align: center;',
                    '{{WRAPPER}} .swiper-wrapper .king-addons-read-more-wrap' => 'text-align: {{VALUE}};',
                    '{{WRAPPER}} .swiper-wrapper .king-addons-read-more-button' => 'text-align: center;',
                ],
                'condition' => [
                    'show_readmore' => 'yes',
                    'timeline_content' => ['dynamic'],
                    'timeline_layout!' => 'one-sided',
                ]
            ]
        );

        $this->add_responsive_control(
            'readmore_content_alignment',
            [
                'label' => esc_html__('Align (Right)', 'king-addons'),
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
                    '{{WRAPPER}} .king-addons-right-aligned .king-addons-read-more-wrap' => 'text-align: {{VALUE}};',
                    '{{WRAPPER}} .king-addons-right-aligned .king-addons-read-more-button' => 'text-align: center;',
                ],
                'condition' => [
                    'show_readmore' => 'yes',
                    'timeline_content' => ['dynamic'],
                    'timeline_layout' => ['centered', 'one-sided']
                ]
            ]
        );

        $this->add_control(
            'read_more_text',
            [
                'label' => esc_html__('Read More', 'king-addons'),
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => 'Read More',
                'condition' => [
                    'show_readmore' => 'yes',
                    'timeline_content' => 'dynamic'
                ]
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'overlay_section',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Overlay', 'king-addons'),
                'tab' => Controls_Manager::TAB_CONTENT,
                'condition' => [
                    'content_layout' => 'image-top',
                    'show_overlay' => 'yes'
                ]
            ]
        );

        $this->add_control(
            'overlay_width',
            [
                'label' => esc_html__('Width', 'king-addons'),
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
                    '{{WRAPPER}} .king-addons-timeline-story-overlay' => 'width: {{SIZE}}{{UNIT}};top:calc((100% - {{overlay_height.SIZE}}{{overlay_height.UNIT}})/2);left:calc((100% - {{SIZE}}{{UNIT}})/2);',


                ],
            ]
        );

        $this->add_responsive_control(
            'overlay_height',
            [
                'label' => esc_html__('Height', 'king-addons'),
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
                    '{{WRAPPER}} .king-addons-timeline-story-overlay' => 'height: {{SIZE}}{{UNIT}};top:calc((100% - {{SIZE}}{{UNIT}})/2);left:calc((100% - {{overlay_width.SIZE}}{{overlay_width.UNIT}})/2);',


                ],
                'separator' => 'after',
            ]
        );

        $this->add_responsive_control(
            'overlay_content_alignment_vertical',
            [
                'label' => esc_html__('Content Vertical Align', 'king-addons'),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'default' => 'center',
                'options' => [
                    'flex-start' => [
                        'title' => esc_html__('Start', 'king-addons'),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'king-addons'),
                        'icon' => 'eicon-v-align-middle',
                    ],
                    'flex-end' => [
                        'title' => esc_html__('End', 'king-addons'),
                        'icon' => 'eicon-v-align-bottom',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-timeline-story-overlay' => 'justify-content: {{VALUE}};',
                ],
                'condition' => [
                    'show_overlay' => 'yes',
                    'content_layout' => 'image-top'
                ]
            ]
        );

        $this->add_responsive_control(
            'overlay_content_alignment_horizontal',
            [
                'label' => esc_html__('Content Horizontal Align', 'king-addons'),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'default' => 'center',
                'options' => [
                    'flex-start' => [
                        'title' => esc_html__('Start', 'king-addons'),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'king-addons'),
                        'icon' => 'eicon-h-align-center',
                    ],
                    'flex-end' => [
                        'title' => esc_html__('End', 'king-addons'),
                        'icon' => 'eicon-h-align-right',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-timeline-story-overlay p' => 'display: flex; justify-content: {{VALUE}};',
                    '{{WRAPPER}} .king-addons-timeline-story-overlay div' => 'display: flex; justify-content: {{VALUE}};',
                ],
                'condition' => [
                    'show_overlay' => 'yes',
                    'content_layout' => 'image-top'
                ]
            ]
        );

        $this->add_control(
            'overlay_animation',
            [
                'label' => esc_html__('Select Animation', 'king-addons'),
                'type' => 'king-addons-animations',
                'default' => 'none',
            ]
        );

        Core::renderUpgradeProNotice($this, Controls_Manager::RAW_HTML, 'timeline', 'overlay_animation', ['pro-slrt', 'pro-slxrt', 'pro-slbt', 'pro-sllt', 'pro-sltp', 'pro-slxlt', 'pro-sktp', 'pro-skrt', 'pro-skbt', 'pro-sklt', 'pro-scup', 'pro-scdn', 'pro-rllt', 'pro-rlrt']);

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
                    '{{WRAPPER}} .king-addons-timeline-story-overlay' => 'transition-duration: {{VALUE}}s;'
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
                    '{{WRAPPER}} .king-addons-animation-wrap:hover .king-addons-timeline-story-overlay' => 'transition-delay: {{VALUE}}s;'
                ],
                'condition' => [
                    'overlay_animation!' => 'none',
                ],
            ]
        );

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
            'pagination_section',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Pagination', 'king-addons'),
                'tab' => Controls_Manager::TAB_CONTENT,
                'condition' => [
                    'timeline_content' => 'dynamic',
                    'timeline_layout' => ['centered', 'one-sided', 'one-sided-left'],
                    'show_pagination' => 'yes'
                ]
            ]
        );

        $this->add_control(
            'pagination_type',
            [
                'label' => esc_html__('Pagination Type', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'render_type' => 'template',
                'default' => 'load-more',
                'options' => [
                    'load-more' => esc_html__('Load More'),
                    'infinite-scroll' => esc_html__('Infinite Scroll')
                ],
                'condition' => [
                    'show_pagination' => 'yes',
                ]
            ]
        );

        $this->add_control(
            'pagination_load_more_text',
            [
                'label' => esc_html__('Load More Text', 'king-addons'),
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => esc_html__('Load More', 'king-addons'),
                'condition' => [
                    'pagination_type' => 'load-more',
                ]
            ]
        );

        $this->add_control(
            'pagination_finish_text',
            [
                'label' => esc_html__('Finish Text', 'king-addons'),
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => 'End of Content.',
                'condition' => [
                    'pagination_type' => ['load-more', 'infinite-scroll'],
                ]
            ]
        );

        $this->add_control(
            'pagination_animation',
            [
                'label' => esc_html__('Select Animation', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'loader-1',
                'options' => [
                    'none' => esc_html__('None', 'king-addons'),
                    'loader-1' => esc_html__('Loader 1', 'king-addons'),
                    'loader-2' => esc_html__('Loader 2', 'king-addons'),
                    'loader-3' => esc_html__('Loader 3', 'king-addons'),
                    'loader-4' => esc_html__('Loader 4', 'king-addons'),
                    'loader-5' => esc_html__('Loader 5', 'king-addons'),
                    'loader-6' => esc_html__('Loader 6', 'king-addons'),
                ],
                'condition' => [
                    'pagination_type' => ['load-more', 'infinite-scroll'],
                    'timeline_layout' => ['centered', 'one-sided', 'one-sided-left']
                ],
            ]
        );

        $this->add_responsive_control(
            'pagination_alignment',
            [
                'label' => esc_html__('Align', 'king-addons'),
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
                    '{{WRAPPER}} .king-addons-grid-pagination' => 'text-align: {{VALUE}};',
                    '{{WRAPPER}} .king-addons-pagination-loading' => 'text-align: {{VALUE}};',
                ],
                'condition' => [
                    'timeline_content' => ['dynamic'],
                    'timeline_layout' => ['centered', 'one-sided']
                ]
            ]
        );

        $this->end_controls_section();

        Core::renderProFeaturesSection($this, '', Controls_Manager::RAW_HTML, 'posts-timeline', [
            'Add Unlimited Custom Timeline Items',
            'Unlimited Posts Per Page Option',
            'Custom Post Types Support',
            'Unlimited Slides to Show Option',
            'Carousel Autoplay and Autoplay Speed',
            'Pause on Hover',
            'Advanced Pagination - Load More Button or Infinite Scroll Options',
            'Advanced Entrance Animation Options'
        ]);

        $this->start_controls_section(
            'content_styles_section',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Timeline Items', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'story_bgcolor',
            [
                'label' => esc_html__('Background Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-wrapper .king-addons-data-wrap' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .king-addons-horizontal .king-addons-story-info' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .king-addons-horizontal-bottom .king-addons-story-info' => 'background-color: {{VALUE}}',
                ],
                'default' => '#FFF',
            ]
        );

        $this->add_control(
            'story_border_color',
            [
                'label' => esc_html__('Border Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-story-info' => 'border-color: {{VALUE}} !important;',
                    '{{WRAPPER}} .king-addons-story-info-vertical' => 'border-color: {{VALUE}} !important;',
                ],
                'condition' => [
                    'timeline_layout!' => 'centered'
                ],
                'default' => '#5B03FF',
            ]
        );

        $this->add_control(
            'story_border_color_left',
            [
                'label' => esc_html__('Border Color (Left Aligned)', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-left-aligned .king-addons-story-info-vertical' => 'border-color: {{VALUE}};',
                ],
                'condition' => [
                    'timeline_layout' => 'centered',
                ],
                'default' => '#5B03FF',
            ]
        );

        $this->add_control(
            'story_border_color_right',
            [
                'label' => esc_html__('Border Color (Right Aligned)', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-right-aligned .king-addons-story-info-vertical' => 'border-color: {{VALUE}} !important;',
                ],
                'condition' => [
                    'timeline_layout' => 'centered',
                ],
                'default' => '#5B03FF',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'timeline_item_shadow',
                'selector' => '{{WRAPPER}} .king-addons-story-info',
                'fields_options' => [
                    'box_shadow_type' =>
                        [
                            'default' => 'yes'
                        ],
                    'box_shadow' => [
                        'default' =>
                            [
                                'horizontal' => 0,
                                'vertical' => 0,
                                'blur' => 20,
                                'spread' => 1,
                                'color' => 'rgba(0,0,0,0.1)'
                            ]
                    ]
                ],
                'condition' => [
                    'timeline_layout' => ['horizontal', 'horizontal-bottom']
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'timeline_item_shadow_vertical',
                'selector' => '{{WRAPPER}} .king-addons-story-info-vertical',
                'fields_options' => [
                    'box_shadow_type' =>
                        [
                            'default' => 'yes'
                        ],
                    'box_shadow' => [
                        'default' =>
                            [
                                'horizontal' => 0,
                                'vertical' => 0,
                                'blur' => 20,
                                'spread' => 1,
                                'color' => 'rgba(0,0,0,0.1)'
                            ]
                    ]
                ],
                'condition' => [
                    'timeline_layout' => ['centered', 'one-sided', 'one-sided-left']
                ]
            ]
        );


        $this->add_responsive_control(
            'item_distance_from_line',
            [
                'label' => esc_html__('Distance From Line', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'devices' => ['desktop', 'tablet', 'mobile'],
                'default' => [
                    'size' => 40,
                    'unit' => 'px',
                ],
                'tablet_default' => [
                    'size' => 40,
                    'unit' => 'px',
                ],
                'mobile_default' => [
                    'size' => 15,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-timeline-centered.king-addons-one-sided-timeline-left .king-addons-data-wrap' => 'margin-right: calc({{main_line_side_distance.SIZE}}px/2 + {{SIZE}}px);',
                    '{{WRAPPER}} .king-addons-timeline-centered.king-addons-one-sided-timeline .king-addons-data-wrap' => 'margin-left: calc({{main_line_side_distance.SIZE}}px/2 + {{SIZE}}px);',

                    '{{WRAPPER}} .king-addons-centered .king-addons-left-aligned .king-addons-timeline-entry-inner .king-addons-data-wrap' => 'margin-right: {{SIZE}}px;',
                    '{{WRAPPER}} .king-addons-centered .king-addons-right-aligned .king-addons-timeline-entry-inner .king-addons-data-wrap' => 'margin-left: {{SIZE}}px;',
                    '{{WRAPPER}} .king-addons-centered .king-addons-one-sided-timeline .king-addons-right-aligned .king-addons-timeline-entry-inner .king-addons-data-wrap' => 'margin-left: calc({{main_line_side_distance.SIZE}}px/2 + {{SIZE}}px);',

                    '{{WRAPPER}} .king-addons-centered .king-addons-one-sided-timeline .king-addons-extra-label' => 'margin-left: calc({{main_line_side_distance.SIZE}}px/2 + {{SIZE}}px);',
                    '{{WRAPPER}} .king-addons-one-sided-wrapper .king-addons-one-sided-timeline .king-addons-extra-label' => 'margin-left: calc({{main_line_side_distance.SIZE}}px/2 + {{SIZE}}px);',
                    '{{WRAPPER}} .king-addons-timeline-centered.king-addons-one-sided-timeline-left .king-addons-timeline-entry .king-addons-extra-label' => 'margin-right: calc({{main_line_side_distance.SIZE}}px/2 + {{SIZE}}px);',
                ],
                'condition' => [
                    'timeline_layout' => ['centered', 'one-sided', 'one-sided-left']
                ],
                'render_type' => 'template',
                'separator' => 'before'
            ]
        );

        $this->add_responsive_control(
            'item_distance_vertical',
            [
                'label' => esc_html__('Vertical Distance', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 200,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 40,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-timeline-centered .king-addons-year-wrap' => 'margin-bottom: {{SIZE}}px;',
                    '{{WRAPPER}} .king-addons-timeline-centered .king-addons-timeline-entry' => 'margin-bottom: {{SIZE}}px;',
                ],
                'condition' => [
                    'timeline_layout' => ['centered', 'one-sided', 'one-sided-left']
                ],
                'render_type' => 'template',
            ]
        );

        $this->add_responsive_control(
            'timeline_item_position',
            [
                'type' => Controls_Manager::SLIDER,
                'label' => esc_html__('Item Bottom Distance', 'king-addons'),
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 150,
                    ]
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 50,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-story-info' => 'margin-bottom: calc({{SIZE}}{{UNIT}} + {{swiper_pagination_progressbar_bottom.SIZE}}{{swiper_pagination_progressbar_bottom.UNIT}}) !important;',
                ],
                'condition' => [
                    'timeline_layout' => ['horizontal'],
                    'equal_height_slides!' => 'auto-height',
                ],
                'separator' => 'before'
            ]
        );

        $this->add_responsive_control(
            'timeline_item_position_equal_heights',
            [
                'type' => Controls_Manager::SLIDER,
                'label' => esc_html__('Item Bottom Distance', 'king-addons'),
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 150,
                    ]
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 50,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-horizontal-timeline .swiper-slide.swiper-slide-line-bottom.auto-height .king-addons-story-info' => 'margin-bottom: calc({{SIZE}}{{UNIT}} + {{swiper_pagination_progressbar_bottom.SIZE}}{{swiper_pagination_progressbar_bottom.UNIT}}) !important; max-height: calc(100% - {{SIZE}}{{UNIT}} - {{swiper_pagination_progressbar_bottom.SIZE}}{{swiper_pagination_progressbar_bottom.UNIT}}) !important; height: calc(100% - {{SIZE}}{{UNIT}} - {{swiper_pagination_progressbar_bottom.SIZE}}{{swiper_pagination_progressbar_bottom.UNIT}}) !important;'
                ],
                'condition' => [
                    'timeline_layout' => 'horizontal',
                    'equal_height_slides' => 'auto-height',
                ],
                'separator' => 'before'
            ]
        );

        $this->add_responsive_control(
            'story_info_margin_top',
            [
                'label' => esc_html__('Item Top Distance', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'unit' => 'px',
                    'size' => 50,
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 150,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-horizontal-bottom-timeline .king-addons-story-info' => 'margin-top: calc({{SIZE}}{{UNIT}} + {{swiper_pagination_progressbar_top.SIZE}}{{swiper_pagination_progressbar_top.UNIT}}) !important; max-height: calc(100% - {{SIZE}}{{UNIT}}) !important;',
                    '{{WRAPPER}} .king-addons-horizontal-bottom-timeline .swiper-slide.auto-height .king-addons-story-info' => 'margin-top: calc({{SIZE}}{{UNIT}} + {{swiper_pagination_progressbar_top.SIZE}}{{swiper_pagination_progressbar_top.UNIT}}) !important; max-height: calc(100% - {{SIZE}}{{UNIT}}) !important; height: calc(100% - ({{SIZE}}{{UNIT}} + {{swiper_pagination_progressbar_top.SIZE}}{{swiper_pagination_progressbar_top.UNIT}})) !important'
                ],
                'separator' => 'before',
                'condition' => [
                    'timeline_layout' => ['horizontal-bottom'],
                ],
            ]
        );

        $this->add_responsive_control(
            'story_padding',
            [
                'label' => esc_html__('Item Padding', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'default' => [
                    'top' => 20,
                    'right' => 20,
                    'bottom' => 20,
                    'left' => 20,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-story-info' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .king-addons-data-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                ],
                'condition' => [
                ],
            ]
        );

        $this->add_responsive_control(
            'story_container_padding',
            [
                'label' => esc_html__('Container Padding', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'description' => esc_html__('Apply this option to fix Box Shadow issue.', 'king-addons'),
                'size_units' => ['px'],
                'devices' => ['desktop', 'tablet', 'mobile'],
                'default' => [
                    'top' => 10,
                    'right' => 10,
                    'left' => 10,
                    'bottom' => 10,
                    'unit' => 'px'
                ],
                'tablet_default' => [
                    'top' => 10,
                    'right' => 10,
                    'left' => 10,
                    'bottom' => 10,
                    'unit' => 'px',
                ],
                'mobile_default' => [
                    'top' => 10,
                    'right' => 10,
                    'left' => 10,
                    'bottom' => 10,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-vertical' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .king-addons-wrapper .swiper-slide' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'timeline_item_border_type',
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
                    '{{WRAPPER}} .king-addons-story-info' => 'border-style: {{VALUE}} !important;',
                    '{{WRAPPER}} .king-addons-story-info-vertical' => 'border-style: {{VALUE}} !important;',
                ],
                'separator' => 'before',
            ]
        );


        $this->add_control(
            'timeline_item_border_width',
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
                    '{{WRAPPER}} .king-addons-story-info' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                    '{{WRAPPER}} .king-addons-story-info-vertical' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                    '{{WRAPPER}} .king-addons-horizontal-timeline .king-addons-story-info:before' => 'top: calc( 100% + {{BOTTOM}}{{UNIT}} ) !important;',
                    '{{WRAPPER}} .king-addons-horizontal-bottom-timeline .king-addons-story-info:before' => 'bottom: calc( 100% + {{TOP}}{{UNIT}} ) !important;',
                    '{{WRAPPER}} .king-addons-right-aligned .king-addons-story-info-vertical.king-addons-data-wrap:after' => 'right: calc( 100% + {{LEFT}}{{UNIT}} ) !important;',
                    '{{WRAPPER}} .king-addons-left-aligned .king-addons-story-info-vertical.king-addons-data-wrap:after' => 'left: calc( 100% + {{LEFT}}{{UNIT}} ) !important;'
                ],
                'condition' => [
                    'timeline_layout!' => 'centered',
                    'timeline_item_border_type!' => 'none'
                ],
            ]
        );

        $this->add_control(
            'timeline_item_border_width_left',
            [
                'label' => esc_html__('Border Width (Left Aligned)', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'default' => [
                    'top' => 1,
                    'right' => 1,
                    'bottom' => 1,
                    'left' => 1,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-story-info' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                    '{{WRAPPER}} .king-addons-story-info-vertical' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                    'body[data-elementor-device-mode=desktop] {{WRAPPER}} .king-addons-both-sided-timeline .king-addons-left-aligned .king-addons-data-wrap:after' => 'left: calc( 100% + {{RIGHT}}{{UNIT}} ) !important;',
                    'body[data-elementor-device-mode=tablet] {{WRAPPER}} .king-addons-both-sided-timeline .king-addons-left-aligned .king-addons-data-wrap:after' => 'left: calc( 100% + {{RIGHT}}{{UNIT}} ) !important;',
                    'body[data-elementor-device-mode=mobile] {{WRAPPER}} .king-addons-both-sided-timeline .king-addons-left-aligned .king-addons-data-wrap:after' => 'right: calc( 103% + {{LEFT}}{{UNIT}} ) !important; left: auto !important',
                ],
                'condition' => [
                    'timeline_layout' => 'centered',
                    'timeline_item_border_type!' => 'none'
                ]
            ]
        );

        $this->add_control(
            'timeline_item_border_width_right',
            [
                'label' => esc_html__('Border Width (Right Aligned)', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'default' => [
                    'top' => 1,
                    'right' => 1,
                    'bottom' => 1,
                    'left' => 1,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-right-aligned .king-addons-story-info-vertical' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                    'body[data-elementor-device-mode=desktop] {{WRAPPER}} .king-addons-right-aligned .king-addons-data-wrap:after' => 'right: calc( 100% + {{LEFT}}{{UNIT}} ) !important;',
                    'body[data-elementor-device-mode=tablet] {{WRAPPER}} .king-addons-right-aligned .king-addons-data-wrap:after' => 'right: calc( 100% + {{LEFT}}{{UNIT}} ) !important;',
                    'body[data-elementor-device-mode=mobile] {{WRAPPER}} .king-addons-right-aligned .king-addons-data-wrap:after' => 'right: calc( 100% + {{LEFT}}{{UNIT}} ) !important;',
                ],
                'condition' => [
                    'timeline_layout' => 'centered',
                    'timeline_item_border_type!' => 'none'
                ]
            ]
        );

        $this->add_control(
            'story_border_radius',
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
                    '{{WRAPPER}} .king-addons-story-info' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                    '{{WRAPPER}} .king-addons-story-info-vertical' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                ],

            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'media_style_section',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Media', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'width',
            [
                'label' => esc_html__('Image Width', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 100,
                    'unit' => '%',
                ],
                'size_units' => ['%', 'px'],
                'range' => [
                    '%' => [
                        'min' => 10,
                        'max' => 100,
                    ],
                    'px' => [
                        'min' => 10,
                        'max' => 200,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-timeline-media' => 'width: {{SIZE}}{{UNIT}};',
                ],
                'separator' => 'after',
            ]
        );

        $this->add_control(
            'media_item_border_color',
            [
                'label' => esc_html__('Border Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#E8E8E8',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-timeline-media' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'media_item_border_type',
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
                    '{{WRAPPER}} .king-addons-timeline-media' => 'border-style: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'media_item_border_width',
            [
                'label' => esc_html__('Border Width', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'default' => [
                    'top' => 0,
                    'right' => 0,
                    'bottom' => 0,
                    'left' => 0,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-timeline-media' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'media_item_border_type!' => 'none'
                ]

            ]
        );

        $this->add_control(
            'media_item_radius',
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
                    '{{WRAPPER}} .king-addons-timeline-media' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'media_item_padding',
            [
                'label' => esc_html__('Padding', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'default' => [
                    'top' => 0,
                    'right' => 0,
                    'bottom' => 0,
                    'left' => 0,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-timeline-media' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before'

            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'content_style_section',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Content', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [

                ],
            ]
        );

        $this->add_control(
            'item_content_border_color',
            [
                'label' => esc_html__('Border Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#E8E8E8',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-timeline-content-wrapper' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'item_content_border_type',
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
                    '{{WRAPPER}} .king-addons-timeline-content-wrapper' => 'border-style: {{VALUE}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'item_content_border_width',
            [
                'label' => esc_html__('Border Width', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'default' => [
                    'top' => 0,
                    'right' => 0,
                    'bottom' => 0,
                    'left' => 0,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-timeline-content-wrapper' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'item_content_border_type!' => 'none'
                ],
                'separator' => 'before'

            ]
        );

        $this->add_control(
            'item_content_border_radius',
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
                    '{{WRAPPER}} .king-addons-timeline-content-wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden',
                ],
            ]
        );

        $this->add_responsive_control(
            'content_item_padding',
            [
                'label' => esc_html__('Padding', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'default' => [
                    'top' => 0,
                    'right' => 0,
                    'bottom' => 0,
                    'left' => 0,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-timeline-content-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',

            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'overlay_style_section',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Overlay', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'content_layout' => 'image-top',
                    'show_overlay' => 'yes'
                ],
            ]
        );

        $this->add_control(
            'overlay_bgcolor',
            [
                'label' => esc_html__('Background Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-wrapper .king-addons-timeline-story-overlay' => 'background-color: {{VALUE}}',
                ],
                'default' => '#0000005E',
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'overlay_background',
                'label' => esc_html__('Background', 'king-addons'),
                'types' => ['classic', 'gradient', 'video'],
                'selector' => '{{WRAPPER}} .king-addons-timeline-story-overlay',
            ]
        );

        $this->add_control(
            'overlay_border_radius',
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
                    '{{WRAPPER}} .king-addons-timeline-story-overlay' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                ],

            ]
        );

        $this->add_responsive_control(
            'timeline_overlay_padding',
            [
                'label' => esc_html__('Overlay Padding', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'separator' => 'before',
                'default' => [
                    'top' => 25,
                    'right' => 25,
                    'bottom' => 25,
                    'left' => 25,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-timeline-story-overlay' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'show_overlay' => 'yes',
                    'content_layout' => 'image-top',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'title_styles_section',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Title', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );


        $this->add_control(
            'story_title_color',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-title' => 'color: {{VALUE}}',
                ],
                'default' => '#444444',
            ]
        );

        $this->add_control(
            'story_title_bg_color',
            [
                'label' => esc_html__('Background Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-wrapper .king-addons-title-wrap' => 'background-color: {{VALUE}} !important',
                ],
                'default' => '#FFFFFF00',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'label' => esc_html__('Typography', 'king-addons'),
                'selector' => '{{WRAPPER}} .king-addons-wrapper .king-addons-title',
            ]
        );

        $this->add_responsive_control(
            'title_padding',
            [
                'label' => esc_html__('Padding', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'default' => [
                    'top' => 0,
                    'right' => 0,
                    'bottom' => 0,
                    'left' => 0,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-wrapper .king-addons-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'title_margin',
            [
                'label' => esc_html__('Margin', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'default' => [
                    'top' => 10,
                    'right' => 0,
                    'bottom' => 0,
                    'left' => 0,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-wrapper .king-addons-title-wrap' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                ],
                'separator' => 'before',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_date',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Date', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
                'show_label' => false,
                'condition' => [
                    'timeline_content' => 'dynamic'
                ]
            ]
        );

        $this->add_control(
            'date_color',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#9C9C9C',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-inner-date-label' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'date_border_color',
            [
                'label' => esc_html__('Border Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#E8E8E8',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-inner-date-label' => 'border-color: {{VALUE}}',
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'date_typography',
                'label' => esc_html__('Typography', 'king-addons'),
                'selector' => '{{WRAPPER}} .king-addons-inner-date-label',
                'fields_options' => [
                    'typography' => [
                        'default' => 'custom',
                    ],
                    'font_weight' => [
                        'default' => '300',
                    ],
                    'font_family' => [
                        'default' => 'Roboto',
                    ],
                    'font_size' => [
                        'default' => [
                            'size' => '15',
                            'unit' => 'px',
                        ]
                    ]
                ]
            ]
        );

        $this->add_control(
            'date_border_type',
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
                    '{{WRAPPER}} .king-addons-inner-date-label' => 'border-style: {{VALUE}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'date_border_width',
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
                    '{{WRAPPER}} .king-addons-inner-date-label' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'date_border_type!' => 'none',
                ],
            ]
        );

        $this->add_responsive_control(
            'date_margin',
            [
                'label' => esc_html__('Margin', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'default' => [
                    'top' => 5,
                    'right' => 0,
                    'bottom' => 0,
                    'left' => 0,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-inner-date-label' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'description_styles_section',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Description', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'description_color',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-wrapper .king-addons-description' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .king-addons-wrapper .king-addons-description p' => 'color: {{VALUE}}'
                ],
                'default' => '#808080',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'description_typography_description',
                'label' => esc_html__('Typography', 'king-addons'),
                'selector' => '{{WRAPPER}} .king-addons-wrapper .king-addons-description',
            ]
        );

        $this->add_control(
            'timeline_list_types',
            [
                'label' => esc_html__('List Style', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'none',
                'label_block' => false,
                'description' => esc_html__('Apply this option for WYSIWYG lists', 'king-addons'),
                'options' => [
                    'none' => esc_html__('None', 'king-addons'),
                    'disc' => esc_html__('Disc', 'king-addons'),
                    'decimal' => esc_html__('Number', 'king-addons')
                ],
                'prefix_class' => 'king-addons-list-style-',
            ]
        );

        $this->add_responsive_control(
            'description_margin',
            [
                'label' => esc_html__('Margin', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'default' => [
                    'top' => 5,
                    'right' => 0,
                    'bottom' => 5,
                    'left' => 0,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-wrapper .king-addons-description' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                ],
                'separator' => 'before',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'readmore_styles_section',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Read More', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'timeline_content' => ['dynamic']
                ]
            ]
        );

        $this->start_controls_tabs(
            'readmore_style_tabs'
        );

        $this->start_controls_tab(
            'readmore_style_normal_tab',
            [
                'label' => esc_html__('Normal', 'king-addons'),
            ]
        );

        $this->add_control(
            'readmore_color',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-wrapper .king-addons-read-more-button' => 'color: {{VAlUE}}',
                ],
                'default' => '#fff',
            ]
        );

        $this->add_control(
            'readmore_bg_color',
            [
                'label' => esc_html__('Background Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-wrapper .king-addons-read-more-button' => 'background-color: {{VAlUE}}',
                ],
                'default' => '#5B03FF',
            ]
        );

        $this->add_control(
            'readmore_border_color',
            [
                'label' => esc_html__('Border Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#E8E8E8',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-read-more-button' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'read_more_box_shadow',
                'selector' => '{{WRAPPER}} .king-addons-read-more-button',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'readmore_typography',
                'label' => esc_html__('Typography', 'king-addons'),
                'selector' => '{{WRAPPER}} .king-addons-wrapper .king-addons-read-more-button',
            ]
        );

        $this->add_control(
            'read_more_transition_duration',
            [
                'label' => esc_html__('Transition Duration (seconds)', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'default' => 0.1,
                'min' => 0,
                'max' => 5,
                'step' => 0.1,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-read-more-button' => 'transition-duration: {{VALUE}}s',
                ],
                'separator' => 'before'
            ]
        );

        $this->add_control(
            'readmore_border_type',
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
                    '{{WRAPPER}} .king-addons-read-more-button' => 'border-style: {{VALUE}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'readmore_item_border_width',
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
                    '{{WRAPPER}} .king-addons-read-more-button' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'readmore_border_type!' => 'none',
                ],
                'render_type' => 'template'
            ]
        );

        $this->add_responsive_control(
            'readmore_padding',
            [
                'label' => esc_html__('Padding', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'default' => [
                    'top' => 6,
                    'right' => 13,
                    'bottom' => 7,
                    'left' => 13,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-wrapper .king-addons-read-more-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'readmore_margin',
            [
                'label' => esc_html__('Margin', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'default' => [
                    'top' => 15,
                    'right' => 0,
                    'bottom' => 15,
                    'left' => 0,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-wrapper .king-addons-read-more-wrap' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                ],
            ]
        );

        $this->add_control(
            'readmore_border_radius',
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
                    '{{WRAPPER}} .king-addons-wrapper .king-addons-read-more-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                ],
                'separator' => 'before',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'style_hover_tab',
            [
                'label' => esc_html__('Hover', 'king-addons'),
            ]
        );

        $this->add_control(
            'readmore_color_hover',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-wrapper .king-addons-read-more-button:hover' => 'color: {{VAlUE}}',
                ],
                'default' => '#ffffff',
            ]
        );

        $this->add_control(
            'readmore_bg_color_hover',
            [
                'label' => esc_html__('Background Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-wrapper .king-addons-read-more-button:hover' => 'background-color: {{VAlUE}}',
                ],
                'default' => '#000000',
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

        $this->start_controls_section(
            'middle_line_styles_section',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Main Line', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'line_color',
            [
                'label' => esc_html__('Line Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-wrapper .king-addons-line::before' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .king-addons-wrapper .king-addons-middle-line' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .king-addons-wrapper .king-addons-timeline-centered .king-addons-year' => 'border-color: {{VALUE}}',

                    '{{WRAPPER}} .king-addons-wrapper:before' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .king-addons-wrapper:after' => 'background-color: {{VALUE}}',

                    '{{WRAPPER}} .king-addons-horizontal .king-addons-swiper-pagination.swiper-pagination-progressbar' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .king-addons-horizontal-bottom .king-addons-swiper-pagination.swiper-pagination-progressbar' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .king-addons-horizontal .king-addons-button-prev' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .king-addons-horizontal .king-addons-button-next' => 'color: {{VALUE}}',
                ],
                'default' => '#D6D6D6',
            ]
        );

        $this->add_control(
            'swiper_progressbar_color',
            [
                'label' => esc_html__('Progress(Fill) Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-horizontal .king-addons-swiper-pagination.swiper-pagination-progressbar .swiper-pagination-progressbar-fill' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .king-addons-horizontal-bottom .king-addons-swiper-pagination.swiper-pagination-progressbar .swiper-pagination-progressbar-fill' => 'background-color: {{VALUE}}',
                ],
                'condition' => [
                    'timeline_layout' => ['horizontal', 'horizontal-bottom']
                ],
                'default' => '#5B03FF',
            ]
        );

        $this->add_control(
            'timeline_fill_color',
            [
                'label' => esc_html__('Line Fill Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#5B03FF',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-timeline-fill' => 'background-color: {{VALUE}} !important;',
                    '{{WRAPPER}} .king-addons-change-border-color' => 'border-color: {{VALUE}} !important;',
                    '{{WRAPPER}} .king-addons-vertical:before' => 'background-color: {{VALUE}} !important;',
                    '{{WRAPPER}} .king-addons-vertical:after' => 'background-color: {{VALUE}} !important;',
                ],
                'condition' => [
                    'timeline_layout!' => ['horizontal', 'horizontal-bottom']
                ],
            ]
        );

        $this->add_control(
            'middle_line_width',
            [
                'label' => esc_html__('Line Width', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 50,
                    ]
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 4,
                ],
                'selectors' => [

                    '{{WRAPPER}} .king-addons-wrapper .king-addons-middle-line' => 'width: {{SIZE}}px; transform: translate(-50%) !important',
                    '{{WRAPPER}} .king-addons-wrapper .king-addons-timeline-fill' => 'width: {{SIZE}}px; transform: translate(-50%)  !important;',


                    '{{WRAPPER}} .king-addons-wrapper .king-addons-one-sided-timeline-left .king-addons-middle-line' => 'width: {{SIZE}}px; transform: translate(50%) !important;',
                    '{{WRAPPER}} .king-addons-wrapper .king-addons-one-sided-timeline-left .king-addons-timeline-fill' => 'width: {{SIZE}}px; transform: translate(50%) !important;',


                    '{{WRAPPER}} .king-addons-wrapper .king-addons-one-sided-timeline .king-addons-middle-line' => 'width: {{SIZE}}px; transform: translate(-50%)  !important;',
                    '{{WRAPPER}} .king-addons-wrapper .king-addons-one-sided-timeline .king-addons-timeline-fill' => 'width: {{SIZE}}px; transform: translate(-50%) !important;',
                ],
                'condition' => [
                    'timeline_layout' => ['centered', 'one-sided', 'one-sided-left']
                ],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'swiper_pagination_progressbar_height',
            [
                'type' => Controls_Manager::NUMBER,
                'label' => esc_html__('Height', 'king-addons'),
                'default' => 0.7,
                'step' => 0.1,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-swiper-pagination.swiper-pagination-progressbar' => 'transform: scaleY({{SIZE}}) translateX(-50%);',
                ],
                'separator' => 'before',
                'condition' => [
                    'timeline_layout' => ['horizontal-bottom', 'horizontal']
                ],
            ]
        );

        $this->add_responsive_control(
            'swiper_pagination_progressbar_bottom',
            [
                'type' => Controls_Manager::SLIDER,
                'label' => esc_html__('Bottom Distance', 'king-addons'),
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ]
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 100,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-horizontal .king-addons-swiper-pagination.swiper-pagination-progressbar' => 'top: auto; bottom: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .king-addons-horizontal .king-addons-icon' => 'bottom: calc({{SIZE}}{{UNIT}} + 1px) !important;',
                    '{{WRAPPER}} .king-addons-button-prev' => 'top: auto; bottom: calc({{SIZE}}{{UNIT}});',
                    '{{WRAPPER}} .king-addons-button-next' => 'top: auto; bottom: calc({{SIZE}}{{UNIT}});',
                ],
                'condition' => [
                    'timeline_layout' => ['horizontal']
                ],
            ]
        );

        $this->add_responsive_control(
            'swiper_pagination_progressbar_top',
            [
                'type' => Controls_Manager::SLIDER,
                'label' => esc_html__('Top Distance', 'king-addons'),
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ]
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 100,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-horizontal-bottom .king-addons-swiper-pagination.swiper-pagination-progressbar' => 'bottom: auto; top: {{SIZE}}{{UNIT}} !important',
                    '{{WRAPPER}} .king-addons-horizontal-bottom .king-addons-icon' => 'position: absolute; top: calc({{SIZE}}{{UNIT}} + 1px) !important; left: 50%; transform: translate(-50%, -50%);',
                    '{{WRAPPER}} .king-addons-button-prev' => 'bottom: auto; top: calc({{SIZE}}{{UNIT}} + 2px);',
                    '{{WRAPPER}} .king-addons-button-next' => 'bottom: auto; top: calc({{SIZE}}{{UNIT}} + 2px);',
                ],
                'condition' => [
                    'timeline_layout' => ['horizontal-bottom']
                ],
            ]
        );

        $this->add_responsive_control(
            'main_line_side_distance',
            [
                'type' => Controls_Manager::SLIDER,
                'label' => esc_html__('Side Distance', 'king-addons'),
                'description' => esc_html__('This option for Zig-Zag layout only works on mobile devices.', 'king-addons'),
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ]
                ],
                'devices' => ['desktop', 'tablet', 'mobile'],
                'default' => [
                    'size' => 100,
                    'unit' => 'px',
                ],
                'tablet_default' => [
                    'size' => 100,
                    'unit' => 'px',
                ],
                'mobile_default' => [
                    'size' => 50,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-timeline-centered.king-addons-one-sided-timeline .king-addons-year-label' => 'left: calc({{SIZE}}px/2);',
                    '{{WRAPPER}} .king-addons-timeline-centered.king-addons-one-sided-timeline .king-addons-middle-line' => 'left: calc({{SIZE}}px/2);',
                    '{{WRAPPER}} .king-addons-timeline-centered.king-addons-one-sided-timeline .king-addons-timeline-fill' => 'left: calc({{SIZE}}px/2);',
                    '{{WRAPPER}} .king-addons-timeline-centered.king-addons-one-sided-timeline .king-addons-icon' => 'left: calc({{SIZE}}px/2);',

                    '{{WRAPPER}} .king-addons-timeline-centered.king-addons-one-sided-timeline-left .king-addons-year-label' => 'right: calc({{SIZE}}px/2);',
                    '{{WRAPPER}} .king-addons-timeline-centered.king-addons-one-sided-timeline-left .king-addons-middle-line' => 'right: calc({{SIZE}}px/2);',
                    '{{WRAPPER}} .king-addons-timeline-centered.king-addons-one-sided-timeline-left .king-addons-timeline-fill' => 'right: calc({{SIZE}}px/2);',
                    '{{WRAPPER}} .king-addons-timeline-centered.king-addons-one-sided-timeline-left .king-addons-icon' => 'right: calc({{SIZE}}px/2);',

                    'body[data-elementor-device-mode=mobile] {{WRAPPER}} .king-addons-both-sided-timeline .king-addons-year-label' => 'position: absolute; left: calc({{SIZE}}px/2);',
                    'body[data-elementor-device-mode=mobile] {{WRAPPER}} .king-addons-both-sided-timeline .king-addons-middle-line' => 'left: calc({{SIZE}}px/2);',
                    'body[data-elementor-device-mode=mobile] {{WRAPPER}} .king-addons-both-sided-timeline .king-addons-timeline-fill' => 'left: calc({{SIZE}}px/2);',
                    'body[data-elementor-device-mode=mobile] {{WRAPPER}} .king-addons-both-sided-timeline .king-addons-icon' => 'left: calc({{SIZE}}px/2); transform: translate(-50%, -50%) !important;',
                ],
                'render_type' => 'template',
                'condition' => [
                    'timeline_layout' => ['centered', 'one-sided', 'one-sided-left']
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'year_label_section',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Main Line Label', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'timeline_content' => 'custom'
                ]
            ]
        );

        $this->add_control(
            'year_label_color',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-wrapper .king-addons-year' => 'color: {{VALUE}}',
                ],
                'default' => '#222222',
                'condition' => [
                    'timeline_content' => ['custom'],
                ]
            ]
        );

        $this->add_control(
            'year_label_bgcolor',
            [
                'label' => esc_html__('Background Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-wrapper .king-addons-year' => 'background-color: {{VALUE}}',
                ],
                'default' => '#fff',
                'condition' => [
                    'timeline_content' => ['custom'],
                ]
            ]
        );

        $this->add_control(
            'year_label_border_color',
            [
                'label' => esc_html__('Border Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-wrapper .king-addons-year.king-addons-year-label' => 'border-color: {{VALUE}}',
                ],
                'default' => '#E0E0E0',
                'condition' => [
                    'timeline_content' => ['custom'],
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'year_typography',
                'label' => esc_html__('Typography', 'king-addons'),
                'selector' => '{{WRAPPER}} .king-addons-wrapper .king-addons-year',
                'condition' => [
                    'timeline_content' => ['custom'],
                ]
            ]
        );

        $this->add_responsive_control(
            'year_label_width',
            [
                'type' => Controls_Manager::SLIDER,
                'label' => esc_html__('Width', 'king-addons'),
                'size_units' => ['px'],
                'default' => [
                    'size' => 70,
                    'unit' => 'px'
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-year-label' => 'width: {{SIZE}}{{UNIT}};',
                ],
                'separator' => 'before',
                'render_type' => 'template',
            ]
        );

        $this->add_responsive_control(
            'year_label_height',
            [
                'type' => Controls_Manager::SLIDER,
                'label' => esc_html__('Height', 'king-addons'),
                'size_units' => ['px'],
                'default' => [
                    'size' => 41,
                    'unit' => 'px'
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-year-label' => 'height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .king-addons-year-wrap' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'year_label_border_type',
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
                    '{{WRAPPER}} .king-addons-year-label' => 'border-style: {{VALUE}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'year_label_border_size',
            [
                'label' => esc_html__('Border Width', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 10,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 3,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-year-label' => 'border-width: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'year_label_border_type!' => 'none'
                ]
            ]
        );

        $this->add_control(
            'year_label_radius',
            [
                'label' => esc_html__('Border Radius', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'default' => [
                    'top' => 5,
                    'right' => 5,
                    'bottom' => 5,
                    'left' => 5,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-year-label' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'icon_styles_section',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Main Line Icon', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'icon_color',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-icon i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .king-addons-wrapper .king-addons-icon' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .king-addons-wrapper .king-addons-icon svg' => 'fill: {{VALUE}};',
                ],
                'default' => '#666666',
            ]
        );

        $this->add_control(
            'icon_timeline_fill_color',
            [
                'label' => esc_html__('Fill Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#333333',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-change-border-color.king-addons-icon i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .king-addons-change-border-color.king-addons-icon svg' => 'fill: {{VALUE}};',
                ],
                'condition' => [
                    'timeline_layout!' => ['horizontal', 'horizontal-bottom']
                ],
            ]
        );

        $this->add_control(
            'icon_bgcolor',
            [
                'label' => esc_html__('Background Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-wrapper .king-addons-icon' => 'background-color: {{VALUE}}',
                ],
                'default' => '#FFFFFF',
            ]
        );

        $this->add_control(
            'icon_timeline_background_fill_color',
            [
                'label' => esc_html__('Background Fill Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#FFFFFF',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-change-border-color.king-addons-icon' => 'background-color: {{VALUE}};',
                ],
                'condition' => [
                    'timeline_layout!' => ['horizontal', 'horizontal-bottom']
                ],
            ]
        );

        $this->add_control(
            'icon_border_color',
            [
                'label' => esc_html__('Border Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#EAEAEA',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-wrapper .king-addons-icon' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'icon_transition_duration',
            [
                'label' => esc_html__('Transition Duration (seconds)', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'default' => 0.1,
                'min' => 0,
                'max' => 5,
                'step' => 0.1,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-icon' => 'transition-duration: {{VALUE}}s',
                ],
                'separator' => 'before'
            ]
        );

        $this->add_responsive_control(
            'icon_size',
            [
                'label' => esc_html__('Icon Size', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'size' => 17,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-wrapper .king-addons-icon i' => 'font-size: {{SIZE}}{{UNIT}} !important',
                    '{{WRAPPER}} .king-addons-wrapper .king-addons-icon svg' => 'width: {{SIZE}}{{UNIT}} !important; height: {{SIZE}}{{UNIT}} !important;'
                ],
                'separator' => 'before'
            ]
        );

        $this->add_responsive_control(
            'icon_bg_size',
            [
                'label' => esc_html__('Background Size', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'unit' => 'px',
                    'size' => 45,
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-wrapper .king-addons-icon i' => 'display: block;',
                    '{{WRAPPER}} .king-addons-wrapper .king-addons-icon' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}}; display: flex !important; justify-content: center !important; align-items: center !important;',
                ],
                'render_type' => 'template',
            ]
        );

        $this->add_control(
            'icon_border_type',
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
                    '{{WRAPPER}} .king-addons-icon' => 'border-style: {{VALUE}}',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'icon_border_width',
            [
                'label' => esc_html__('Border Width', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'default' => [
                    'top' => 2,
                    'right' => 2,
                    'bottom' => 2,
                    'left' => 2,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-icon' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                ],
                'condition' => [
                    'icon_border_type!' => 'none'
                ]
            ]
        );

        $this->add_control(
            'icon_border_radius',
            [
                'label' => esc_html__('Border Radius', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'default' => [
                    'top' => 50,
                    'right' => 50,
                    'bottom' => 50,
                    'left' => 50,
                    'unit' => '%'
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'label_styles_section',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Extra Label', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_extra_label' => 'yes',
                ]
            ]
        );

        $this->add_control(
            'extra_label_bg_color_dynamic',
            [
                'label' => esc_html__('Background Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-extra-label' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'label_bg_size',
            [
                'label' => esc_html__('Background Size', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'unit' => 'px',
                    'size' => 180,
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 300,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-wrapper .king-addons-extra-label' => 'width: {{SIZE}}{{UNIT}}; height: auto;',

                ],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'label_right',
            [
                'label' => esc_html__('Label Distance', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 25,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-timeline-centered.king-addons-both-sided-timeline .king-addons-timeline-entry.king-addons-left-aligned .king-addons-extra-label' => 'left: calc(100% + {{SIZE}}{{UNIT}})',
                    '{{WRAPPER}} .king-addons-timeline-centered.king-addons-both-sided-timeline .king-addons-timeline-entry.king-addons-right-aligned .king-addons-extra-label' => 'right: calc(100% + {{SIZE}}{{UNIT}})',
                ],
                'condition' => [
                    'timeline_layout' => ['centered'],
                ]
            ]
        );

        $this->add_responsive_control(
            'label_padding',
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
                    '{{WRAPPER}} .king-addons-wrapper .king-addons-extra-label' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'label_border_radius',
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
                    '{{WRAPPER}} .king-addons-wrapper .king-addons-extra-label' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'label_section',
            [
                'label' => esc_html__('Primary Label', 'king-addons'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'date_label_color',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-wrapper span.king-addons-label' => 'color: {{VALUE}}',
                ],
                'default' => '#5B03FF',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'label_typography',
                'label' => esc_html__('Typography', 'king-addons'),
                'selector' => '{{WRAPPER}} .king-addons-extra-label span.king-addons-label',
            ]
        );


        $this->add_control(
            'secondary_label_section',
            [
                'label' => esc_html__('Secondary Label', 'king-addons'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'timeline_content' => 'custom'
                ]
            ]
        );

        $this->add_control(
            'secondary_label_color',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,

                'selectors' => [
                    '{{WRAPPER}} .king-addons-wrapper span.king-addons-sub-label' => 'color: {{VALUE}}',
                ],
                'condition' => [
                    'timeline_content' => 'custom'
                ],
                'default' => '#7A7A7A',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'secondary_label_typography',
                'label' => esc_html__('Typography', 'king-addons'),
                'selector' => '{{WRAPPER}} .king-addons-wrapper span.king-addons-sub-label',
                'condition' => [
                    'timeline_content' => 'custom'
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'triangle_styles',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Triangle', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'triangle_bgcolor',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-wrapper .king-addons-one-sided-timeline .king-addons-data-wrap:after' => 'border-right-color: {{icon_bgcolor}}',
                    '{{WRAPPER}} .king-addons-wrapper .king-addons-one-sided-timeline-left .king-addons-data-wrap:after' => 'border-left-color: {{VALUE}}',
                    '{{WRAPPER}} .king-addons-wrapper .king-addons-right-aligned .king-addons-data-wrap:after' => 'border-right-color: {{VALUE}}',
                    '{{WRAPPER}} .king-addons-horizontal .king-addons-story-info:before' => 'border-top-color: {{VALUE}} !important',
                    '{{WRAPPER}} .king-addons-horizontal-bottom .king-addons-story-info:before' => 'border-bottom-color: {{VALUE}} !important',
                    '{{WRAPPER}} .king-addons-wrapper .king-addons-left-aligned .king-addons-data-wrap:after' => 'border-left-color: {{VALUE}}',
                    'body[data-elementor-device-mode=mobile] {{WRAPPER}} .king-addons-wrapper .king-addons-both-sided-timeline .king-addons-left-aligned .king-addons-data-wrap:after' => 'border-right-color: {{VALUE}} !important; border-left-color: transparent !important;',
                    '{{WRAPPER}} .king-addons-centered .king-addons-one-sided-timeline .king-addons-right-aligned .king-addons-data-wrap:after' => 'border-right-color: {{VALUE}} !important; border-left-color: transparent !important;',
                ],
                'default' => '#FFFFFF',
                'render_type' => 'template'
            ]
        );

        $this->add_responsive_control(
            'story_triangle_size',
            [
                'label' => esc_html__('Size', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'default' => [
                    'unit' => 'px',
                    'size' => 11,
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 25,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-horizontal .king-addons-story-info:before' => 'border-width: {{size}}{{UNIT}}; top: 100%; left: 50%; transform: translate(-50%);',
                    '{{WRAPPER}} .king-addons-horizontal-bottom .king-addons-story-info:before' => 'border-width: {{size}}{{UNIT}}; bottom: 100%; left: 50%; transform: translate(-50%);',
                    '{{WRAPPER}} .king-addons-one-sided-timeline .king-addons-data-wrap:after' => 'border-width: {{size}}{{UNIT}}; top: {{triangle_onesided_position_top.SIZE}}%; transform: translateY(-50%);',
                    '{{WRAPPER}} .king-addons-one-sided-timeline-left .king-addons-data-wrap:after' => 'border-width: {{size}}{{UNIT}}; top: {{triangle_onesided_position_top.SIZE}}%; transform: translateY(-50%);',
                    '{{WRAPPER}} .king-addons-both-sided-timeline .king-addons-right-aligned .king-addons-data-wrap:after' => 'border-width: {{size}}{{UNIT}}; top: {{arrow_bothsided_position_top.SIZE}}{{arrow_bothsided_position_top.UNIT}}; transform: translateY(-50%);',
                    '{{WRAPPER}} .king-addons-both-sided-timeline .king-addons-left-aligned .king-addons-data-wrap:after' => 'border-width: {{size}}{{UNIT}}; top: {{arrow_bothsided_position_top.SIZE}}{{arrow_bothsided_position_top.UNIT}}; transform: translateY(-50%);',
                ],
                'separator' => 'before'
            ]
        );

        $this->add_control(
            'triangle_onesided_position_top',
            [
                'label' => esc_html__('Position Top', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['%', 'px'],
                'range' => [
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                    'px' => [
                        'min' => 0,
                        'max' => 150,
                    ],
                ],
                'default' => [
                    'unit' => '%',
                    'size' => 30,
                ],
                'selectors' => [

                    '{{WRAPPER}} .king-addons-one-sided-timeline .king-addons-data-wrap:after' => 'top: {{size}}{{UNIT}}; transform: translateY(-50%) !important;',
                    '{{WRAPPER}} .king-addons-one-sided-timeline-left .king-addons-data-wrap:after' => 'top: {{size}}{{UNIT}}; transform: translateY(-50%) !important;',
                    '{{WRAPPER}} .king-addons-one-sided-timeline .king-addons-icon' => 'position: absolute; top: {{size}}{{UNIT}}; transform: translate(-50%, -50%) !important;',
                    '{{WRAPPER}} .king-addons-one-sided-timeline-left .king-addons-icon' => 'position: absolute; top: {{size}}{{UNIT}}; transform: translate(50%,-50%) !important;',
                ],
                'condition' => [
                    'timeline_layout' => ['one-sided', 'one-sided-left']
                ],
                'render_type' => 'template',
            ]
        );

        $this->add_control(
            'arrow_bothsided_position_top',
            [
                'label' => esc_html__('Position Top', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['%', 'px'],
                'range' => [
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                    'px' => [
                        'min' => 0,
                        'max' => 150,
                    ],
                ],
                'default' => [
                    'size' => 30,
                    'unit' => '%',
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-timeline-centered .king-addons-data-wrap:after' => 'top: {{size}}{{UNIT}}; transform: translateY(-50%) !important;',
                    '{{WRAPPER}} .king-addons-timeline-centered.king-addons-both-sided-timeline .king-addons-right-aligned .king-addons-icon' => 'position: absolute; top: {{size}}{{UNIT}}; transform: translate(50%, -50%) !important;',
                    '{{WRAPPER}} .king-addons-timeline-centered.king-addons-one-sided-timeline  .king-addons-right-aligned .king-addons-icon' => 'position: absolute; top: {{size}}{{UNIT}}; transform: translate(-50%, -50%) !important;',
                    '{{WRAPPER}} .king-addons-timeline-centered  .king-addons-left-aligned .king-addons-icon' => 'position: absolute; top: {{size}}{{UNIT}}; transform: translate(-50%, -50%) !important;',
                    '{{WRAPPER}} .king-addons-timeline-centered .king-addons-extra-label' => 'top: {{size}}{{UNIT}};',
                    '{{WRAPPER}} .king-addons-centered .king-addons-one-sided-timeline .king-addons-data-wrap:after' => 'top: {{size}}{{UNIT}}; transform: translateY(-50%) !important;',
                ],
                'condition' => [
                    'timeline_layout' => ['centered']
                ],
                'render_type' => 'template'
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'navigation_button_styles',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Navigation', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'timeline_layout' => ['horizontal', 'horizontal-bottom']
                ]
            ]
        );

        $this->start_controls_tabs(
            'navigation_style_tabs'
        );

        $this->start_controls_tab(
            'navigation_style_normal_tab',
            [
                'label' => esc_html__('Normal', 'king-addons'),
            ]
        );

        $this->add_control(
            'navigation_button_bg_color',
            [
                'label' => esc_html__('Background Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-button-prev' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .king-addons-button-next' => 'background-color: {{VALUE}}',
                ],
                'condition' => [
                    'timeline_layout' => ['horizontal', 'horizontal-bottom']
                ]
            ]
        );

        $this->add_control(
            'navigation_button_color',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#5B03FF',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-button-prev i' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .king-addons-button-next i' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .king-addons-button-prev svg' => 'fill: {{VALUE}}; cursor: pointer; z-index: 11;',
                    '{{WRAPPER}} .king-addons-button-next svg' => 'fill: {{VALUE}}; cursor: pointer; z-index: 11;',
                ],
                'condition' => [
                    'timeline_layout' => ['horizontal', 'horizontal-bottom']
                ]
            ]
        );

        $this->add_control(
            'navigation_transition_duration',
            [
                'label' => esc_html__('Transition Duration (seconds)', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'default' => 0.1,
                'min' => 0,
                'max' => 5,
                'step' => 0.1,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-button-prev' => 'transition-duration: {{VALUE}}s',
                    '{{WRAPPER}} .king-addons-button-next' => 'transition-duration: {{VALUE}}s',
                    '{{WRAPPER}} .king-addons-button-prev i' => 'transition-duration: {{VALUE}}s',
                    '{{WRAPPER}} .king-addons-button-next i' => 'transition-duration: {{VALUE}}s',
                    '{{WRAPPER}} .king-addons-button-prev svg' => 'transition-duration: {{VALUE}}s',
                    '{{WRAPPER}} .king-addons-button-next svg' => 'transition-duration: {{VALUE}}s',
                ],
                'separator' => 'before'
            ]
        );

        $this->add_responsive_control(
            'navigation_icon_size',
            [
                'label' => esc_html__('Icon Size', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 40,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-horizontal-bottom .king-addons-button-next' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .king-addons-horizontal-bottom .king-addons-button-prev' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .king-addons-horizontal .king-addons-button-next' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .king-addons-horizontal .king-addons-button-prev' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .king-addons-horizontal-bottom .king-addons-button-next svg' => 'width: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .king-addons-horizontal-bottom .king-addons-button-prev svg' => 'width: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .king-addons-horizontal .king-addons-button-next svg' => 'width: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .king-addons-horizontal .king-addons-button-prev svg' => 'width: {{SIZE}}{{UNIT}};',

                ],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'navigation_icon_bg_size',
            [
                'label' => esc_html__('Box Size', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 20,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 40,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-horizontal-bottom .king-addons-button-next' => 'width: {{SIZE}}{{UNIT}}; text-align: center; height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .king-addons-horizontal-bottom .king-addons-button-prev' => 'width: {{SIZE}}{{UNIT}}; text-align: center; height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .king-addons-horizontal .king-addons-button-next' => 'width: {{SIZE}}{{UNIT}}; text-align: center; height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .king-addons-horizontal .king-addons-button-prev' => 'width: {{SIZE}}{{UNIT}}; text-align: center; height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .king-addons-horizontal .king-addons-button-next i' => 'width: {{SIZE}}{{UNIT}}; text-align: center; line-height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .king-addons-horizontal .king-addons-button-prev i' => 'width: {{SIZE}}{{UNIT}}; text-align: center; line-height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .king-addons-horizontal .king-addons-button-next svg' => ' text-align: center; line-height: 1.5;',
                    '{{WRAPPER}} .king-addons-horizontal .king-addons-button-prev svg' => ' text-align: center; line-height: 1.5;',
                    '{{WRAPPER}} .king-addons-horizontal-bottom .king-addons-button-next i' => 'width: {{SIZE}}{{UNIT}}; text-align: center; line-height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .king-addons-horizontal-bottom .king-addons-button-prev i' => 'width: {{SIZE}}{{UNIT}}; text-align: center; line-height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .king-addons-horizontal-bottom .king-addons-button-next svg' => 'text-align: center; line-height: 1.5;',
                    '{{WRAPPER}} .king-addons-horizontal-bottom .king-addons-button-prev svg' => 'text-align: center; line-height: 1.5;',
                    '{{WRAPPER}} .king-addons-swiper-pagination.swiper-pagination-progressbar' => 'width: calc(100% - ({{SIZE}}px + 15px)*2);',
                    '{{WRAPPER}} .king-addons-horizontal-bottom.swiper' => 'margin-left: {{SIZE}}px; margin-right: {{SIZE}}px;',
                    '{{WRAPPER}} .king-addons-horizontal.swiper' => 'margin-left: {{SIZE}}px; margin-right: {{SIZE}}px;',
                ],
                'render_type' => 'template'
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'navigation_style_hover_tab',
            [
                'label' => esc_html__('Hover', 'king-addons'),
            ]
        );

        $this->add_control(
            'navigation_button_bg_color_hover',
            [
                'label' => esc_html__('Background Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-button-prev:hover' => 'background-color: {{VALUE}}; cursor: pointer;',
                    '{{WRAPPER}} .king-addons-button-next:hover' => 'background-color: {{VALUE}}; cursor: pointer;',
                ],
                'condition' => [
                    'timeline_layout' => ['horizontal', 'horizontal-bottom']
                ]
            ]
        );

        $this->add_control(
            'navigation_button_color_hover',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#605BE1',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-button-prev:hover i' => 'color: {{VALUE}}; cursor: pointer; z-index: 11;',
                    '{{WRAPPER}} .king-addons-button-next:hover i' => 'color: {{VALUE}}; cursor: pointer; z-index: 11;',
                    '{{WRAPPER}} .king-addons-button-prev:hover svg' => 'fill: {{VALUE}}; cursor: pointer; z-index: 11;',
                    '{{WRAPPER}} .king-addons-button-next:hover svg' => 'fill: {{VALUE}}; cursor: pointer; z-index: 11;',
                ],
                'condition' => [
                    'timeline_layout' => ['horizontal', 'horizontal-bottom']
                ]
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();


        $this->start_controls_section(
            'section_style_pagination',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Pagination', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
                'show_label' => false,
                'condition' => [
                    'timeline_content' => 'dynamic',
                    'timeline_layout' => ['centered', 'one-sided', 'one-sided-left']
                ],
            ]
        );

        $this->start_controls_tabs('tabs_grid_pagination_style');

        $this->start_controls_tab(
            'tab_grid_pagination_normal',
            [
                'label' => esc_html__('Normal', 'king-addons'),
            ]
        );

        $this->add_control(
            'pagination_color',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-pagination a' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .king-addons-grid-pagination svg' => 'fill: {{VALUE}}',
                    '{{WRAPPER}} .king-addons-grid-pagination > div > span' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .king-addons-grid-pagination span.king-addons-disabled-arrow' => 'color: {{VALUE}}',
                ],
                'condition' => [
                    'timeline_layout' => ['centered', 'one-sided', 'one-sided-left']
                ]
            ]
        );

        $this->add_control(
            'pagination_bg_color',
            [
                'label' => esc_html__('Background Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#5B03FF',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-pagination a' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .king-addons-grid-pagination > div > span' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .king-addons-grid-pagination span.king-addons-disabled-arrow' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .king-addons-pagination-finish' => 'color: {{VALUE}}',
                ]
            ]
        );

        $this->add_control(
            'pagination_border_color',
            [
                'label' => esc_html__('Border Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#E8E8E8',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-pagination a' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .king-addons-grid-pagination > div > span' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .king-addons-grid-pagination span.king-addons-disabled-arrow' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'pagination_box_shadow',
                'selector' => '{{WRAPPER}} .king-addons-grid-pagination a, {{WRAPPER}} .king-addons-grid-pagination > div > span',
            ]
        );

        $this->add_control(
            'pagination_loader_color',
            [
                'label' => esc_html__('Loader Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#5B03FF',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-double-bounce .king-addons-child' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .king-addons-wave .king-addons-rect' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .king-addons-spinner-pulse' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .king-addons-chasing-dots .king-addons-child' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .king-addons-three-bounce .king-addons-child' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .king-addons-fading-circle .king-addons-circle:before' => 'background-color: {{VALUE}}',
                ],
                'condition' => [
                    'pagination_type' => ['load-more', 'infinite-scroll']
                ]
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_grid_pagination_hover',
            [
                'label' => esc_html__('Hover', 'king-addons'),
            ]
        );

        $this->add_control(
            'pagination_color_hr',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-pagination a:hover' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .king-addons-grid-pagination a:hover svg' => 'fill: {{VALUE}}',
                    '{{WRAPPER}} .king-addons-grid-pagination > div > span:not(.king-addons-disabled-arrow):hover' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .king-addons-grid-pagination span.king-addons-grid-current-page' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'pagination_bg_color_hr',
            [
                'label' => esc_html__('Background Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#4D02D8',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-pagination a:hover' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .king-addons-grid-pagination > div > span:not(.king-addons-disabled-arrow):hover' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .king-addons-grid-pagination span.king-addons-grid-current-page' => 'background-color: {{VALUE}}',
                ]
            ]
        );

        $this->add_control(
            'pagination_border_color_hr',
            [
                'label' => esc_html__('Border Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#E8E8E8',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-pagination a:hover' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .king-addons-grid-pagination > div > span:not(.king-addons-disabled-arrow):hover' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .king-addons-grid-pagination span.king-addons-grid-current-page' => 'border-color: {{VALUE}}',
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'pagination_box_shadow_hr',
                'selector' => '{{WRAPPER}} .king-addons-grid-pagination a:hover, {{WRAPPER}} .king-addons-grid-pagination > div > span:not(.king-addons-disabled-arrow):hover',
                'separator' => 'after',
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_control(
            'pagination_transition_duration',
            [
                'label' => esc_html__('Transition Duration (seconds)', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'default' => 0.1,
                'min' => 0,
                'max' => 5,
                'step' => 0.1,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-pagination a' => 'transition-duration: {{VALUE}}s',
                    '{{WRAPPER}} .king-addons-grid-pagination svg' => 'transition-duration: {{VALUE}}s',
                    '{{WRAPPER}} .king-addons-grid-pagination > div > span' => 'transition-duration: {{VALUE}}s',
                ],
                'separator' => 'after',
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'loadmore_typography',
                'label' => esc_html__('Typography', 'king-addons'),
                'selector' => '{{WRAPPER}} .king-addons-load-more-btn',
            ]
        );

        $this->add_control(
            'pagination_border_type',
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
                    '{{WRAPPER}} .king-addons-grid-pagination a' => 'border-style: {{VALUE}};',
                    '{{WRAPPER}} .king-addons-grid-pagination > div > span' => 'border-style: {{VALUE}};',
                    '{{WRAPPER}} .king-addons-grid-pagination span.king-addons-grid-current-page' => 'border-style: {{VALUE}};',
                    '{{WRAPPER}} .king-addons-grid-pagination span.king-addons-disabled-arrow' => 'border-style: {{VALUE}}',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'pagination_border_width',
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
                    '{{WRAPPER}} .king-addons-grid-pagination a' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .king-addons-grid-pagination > div > span' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .king-addons-grid-pagination span.king-addons-grid-current-page' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .king-addons-grid-pagination span.king-addons-disabled-arrow' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'pagination_border_type!' => 'none',
                ],
            ]
        );

        $this->add_responsive_control(
            'pagination_distance_from_grid',
            [
                'label' => esc_html__('Distance From Timeline', 'king-addons'),
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
                    'size' => 25,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-pagination' => 'margin-top: {{SIZE}}{{UNIT}};',
                ],
                'separator' => 'before'
            ]
        );

        $this->add_responsive_control(
            'pagination_padding',
            [
                'label' => esc_html__('Padding', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'default' => [
                    'top' => 8,
                    'right' => 20,
                    'bottom' => 8,
                    'left' => 20,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-pagination a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .king-addons-grid-pagination > div > span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .king-addons-grid-pagination span.king-addons-disabled-arrow' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .king-addons-grid-pagination span.king-addons-grid-current-page' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'pagination_wrapper_padding',
            [
                'label' => esc_html__('Wrapper Padding', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'default' => [
                    'top' => 0,
                    'right' => 0,
                    'bottom' => 0,
                    'left' => 0,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-pagination' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'pagination_radius',
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
                    '{{WRAPPER}} .king-addons-grid-pagination a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .king-addons-grid-pagination > div > span' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .king-addons-grid-pagination span.king-addons-grid-current-page' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->end_controls_section();

    }

    public function get_tax_query_args()
    {
        $settings = $this->get_settings();
        $tax_query = [];

        if ('related' === $settings['timeline_post_types']) {
            $tax_query[] = [
                'taxonomy' => $settings['query_tax_selection'],
                'field' => 'term_id',
                'terms' => wp_get_object_terms(
                    get_the_ID(),
                    $settings['query_tax_selection'],
                    ['fields' => 'ids']
                ),
            ];
        } else {
            foreach (get_object_taxonomies($settings['timeline_post_types']) as $tax) {
                $term_setting = $settings['query_taxonomy_' . $tax] ?? [];
                if (!empty($term_setting)) {
                    $tax_query[] = [
                        'taxonomy' => $tax,
                        'field' => 'id',
                        'terms' => $term_setting,
                    ];
                }
            }
        }

        return $tax_query;
    }

    public function get_main_query_args()
    {
        $settings = $this->get_settings();
        $author = !empty($settings['query_author']) ? implode(',', $settings['query_author']) : '';
        $paged = get_query_var('paged') ?: (get_query_var('page') ?: 1);
        $posts_per_page = !king_addons_freemius()->can_use_premium_code__premium_only() && $settings['posts_per_page'] > 4
            ? 4
            : ($settings['posts_per_page'] ?: 4);

        $args = [
            'post_type' => $settings['timeline_post_types'],
            'tax_query' => $this->get_tax_query_args(),
            'post__not_in' => $settings['query_exclude_' . $settings['timeline_post_types']] ?? '',
            'posts_per_page' => $posts_per_page,
            'orderby' => $settings['order_posts'],
            'order' => $settings['order_direction'],
            'author' => $author,
            'paged' => $paged,
        ];

        if ('yes' === $settings['query_exclude_no_images']) {
            $args['meta_key'] = '_thumbnail_id';
        }

        // Overwrite main query args if manual selection
        if ('manual' === $settings['query_selection']) {
            $post_ids = $settings['query_manual_' . $settings['timeline_post_types']] ?? [''];
            $args = [
                'post_type' => $settings['timeline_post_types'],
                'post__in' => $post_ids,
                'posts_per_page' => $posts_per_page,
                'orderby' => '',
                'paged' => $paged,
            ];
        }

        return $args;
    }

    public function get_max_num_pages()
    {
        $query = new WP_Query($this->get_main_query_args());
        $max_num_pages = (int)ceil($query->max_num_pages);
        wp_reset_postdata();
        return $max_num_pages;
    }

    /** @noinspection PhpMissingFieldTypeInspection */
    public $content_alignment = '';

    public function content_and_animation_alignment($layout, $countItem, $settings)
    {
        // Default alignment
        $this->content_alignment = ($layout === 'one-sided-left')
            ? 'king-addons-left-aligned'
            : 'king-addons-right-aligned';

        // Special case for centered
        if ($layout === 'centered') {
            if ($countItem % 2 === 0) {
                $this->content_alignment = 'king-addons-left-aligned';
            }
            // Handle swapping animations
            if (preg_match('/right/i', $settings['timeline_animation'])) {
                if ('king-addons-left-aligned' === $this->content_alignment) {
                    $this->animation = preg_match('/right/i', $settings['timeline_animation'])
                        ? str_replace('right', 'left', $settings['timeline_animation'])
                        : $settings['timeline_animation'];
                } else {
                    $this->animation = preg_match('/left/i', $settings['timeline_animation'])
                        ? str_replace('left', 'right', $settings['timeline_animation'])
                        : $settings['timeline_animation'];
                }
            }
            if (preg_match('/left/i', $settings['timeline_animation'])) {
                if ('king-addons-left-aligned' === $this->content_alignment) {
                    $this->animation = preg_match('/left/i', $settings['timeline_animation'])
                        ? str_replace('left', 'right', $settings['timeline_animation'])
                        : $settings['timeline_animation'];
                } elseif ('king-addons-right-aligned' === $this->content_alignment) {
                    $this->animation = preg_match('/right/i', $settings['timeline_animation'])
                        ? str_replace('right', 'left', $settings['timeline_animation'])
                        : $settings['timeline_animation'];
                }
            }
        }

        // Handle animation for load more
        if (preg_match('/right/i', $settings['timeline_animation'])) {
            $this->animation_loadmore_left = preg_match('/right/i', $settings['timeline_animation'])
                ? str_replace('right', 'left', $settings['timeline_animation'])
                : $settings['timeline_animation'];
            $this->animation_loadmore_right = preg_match('/left/i', $settings['timeline_animation'])
                ? str_replace('left', 'right', $settings['timeline_animation'])
                : $settings['timeline_animation'];
        } elseif (preg_match('/left/i', $settings['timeline_animation'])) {
            $this->animation_loadmore_left = preg_match('/left/i', $settings['timeline_animation'])
                ? str_replace('left', 'right', $settings['timeline_animation'])
                : $settings['timeline_animation'];
            $this->animation_loadmore_right = preg_match('/right/i', $settings['timeline_animation'])
                ? str_replace('right', 'left', $settings['timeline_animation'])
                : $settings['timeline_animation'];
        }
    }

    public function add_custom_horizontal_timeline_attributes($content, $settings, $index)
    {
        $this->timeline_description = $content['repeater_description'];
        $this->story_date_label = esc_html($content['repeater_date_label']);
        $this->story_extra_label = esc_html($content['repeater_extra_label']);
        $this->timeline_story_title = wp_kses_post($content['repeater_story_title']);
        $this->thumbnail_size = $content['king_addons_thumbnail_size'];
        $this->thumbnail_custom_dimension = $content['king_addons_thumbnail_custom_dimension'];
        $this->show_year_label = esc_html($content['repeater_show_year_label']);
        $this->timeline_year = esc_html($content['repeater_year']);

        $this->title_key = $this->get_repeater_setting_key('repeater_story_title', 'timeline_repeater_list', $index);
        $this->year_key = $this->get_repeater_setting_key('repeater_year', 'timeline_repeater_list', $index);
        $this->date_label_key = $this->get_repeater_setting_key('repeater_date_label', 'timeline_repeater_list', $index);
        $this->extra_label_key = $this->get_repeater_setting_key('repeater_extra_label', 'timeline_repeater_list', $index);
        $this->description_key = $this->get_repeater_setting_key('repeater_description', 'timeline_repeater_list', $index);

        $this->background_image = ($settings['content_layout'] === 'background')
            ? $content['repeater_image']['url']
            : '';
        $this->background_class = ($settings['content_layout'] === 'background')
            ? 'story-with-background'
            : '';

        $this->add_inline_editing_attributes($this->title_key, 'none');
        $this->add_inline_editing_attributes($this->year_key, 'none');
        $this->add_inline_editing_attributes($this->date_label_key, 'none');
        $this->add_inline_editing_attributes($this->extra_label_key, 'none');
        $this->add_inline_editing_attributes($this->description_key, 'advanced');

        $this->add_render_attribute($this->title_key, ['class' => 'king-addons-title']);
        $this->add_render_attribute($this->year_key, ['class' => 'king-addons-year-label king-addons-year']);
        $this->add_render_attribute($this->date_label_key, ['class' => 'king-addons-label']);
        $this->add_render_attribute($this->extra_label_key, ['class' => 'king-addons-sub-label']);
        $this->add_render_attribute($this->description_key, ['class' => 'king-addons-description']);
    }

    public function render_image_or_icon($content)
    {
        $img_id = $content['repeater_image']['id'] ?? '';
        $img_url = $content['repeater_image']['url'] ?? '';
        $icon = $content['repeater_timeline_item_icon'] ?? '';

        if (!empty($img_id)) {
            if ($this->thumbnail_size === 'custom') {
                $size = [$this->thumbnail_custom_dimension['width'], $this->thumbnail_custom_dimension['height']];
                $this->image = wp_get_attachment_image($img_id, $size, true);
            } else {
                $this->image = wp_get_attachment_image($img_id, $this->thumbnail_size, true);
            }
        } elseif (!empty($img_url)) {
            $this->image = '<img src="' . esc_url($img_url) . '">';
        } elseif (!empty($icon)) {
            ob_start();
            Icons_Manager::render_icon($icon, ['aria-hidden' => 'true']);
            $this->image = ob_get_clean();
        } else {
            $this->image = '';
        }
    }

    public function king_addons_render_swiper_navigation($settings)
    {
        echo '</div>        
        <div class="king-addons-swiper-pagination"></div>
        <div class="king-addons-button-prev king-addons-timeline-prev-arrow king-addons-timeline-prev-' . esc_attr($this->get_id()) . '">
            ' . Core::getIcon($settings['swiper_nav_icon'], '') . '
        </div>
        <div class="king-addons-button-next king-addons-timeline-next-arrow king-addons-timeline-next-' . esc_attr($this->get_id()) . '">
            ' . Core::getIcon($settings['swiper_nav_icon'], '') . '
        </div>
    </div>';
    }

    public function render_pagination($settings, $paged)
    {
    }

    public function get_animation_class($data, $object)
    {
        $class = '';
        $anim = $data[$object . '_animation'] ?? 'none';

        if ('none' !== $anim) {
            $class .= ' king-addons-' . $object . '-' . $anim;
            $class .= ' king-addons-anim-size-' . $data[$object . '_animation_size'];
            $class .= ' king-addons-animation-timing-' . $data[$object . '_animation_timing'];
            if ('yes' === $data[$object . '_animation_tr']) {
                $class .= ' king-addons-anim-transparency';
            }
        }
        return $class;
    }

    public static function youtube_url($story_settings)
    {
        $url = $story_settings['repeater_youtube_video_url'] ?? '';
        $media = '';
        if (!empty($url)) {
            preg_match(
                '%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i',
                $url,
                $matches
            );
            if (!empty($matches[1])) {
                $id = $matches[1];
                $media = '<iframe width="100%" height="auto" src="https://www.youtube.com/embed/' . esc_attr($id) . '" allowfullscreen></iframe>';
            } else {
                $media = __("Wrong URL", "king-addons-addons");
            }
        }
        return $media;
    }

    public function horizontal_timeline_classes($settings)
    {
        $this->slides_to_show = $settings['slides_to_show'] ?? 2;
        if (!king_addons_freemius()->can_use_premium_code__premium_only() && $this->slides_to_show > 4) {
            $this->slides_to_show = 4;
        }

        $horizontal_class = '';
        if ($settings['timeline_layout'] === 'horizontal') {
            $horizontal_class = 'king-addons-horizontal-wrapper';
        } elseif ($settings['timeline_layout'] === 'horizontal-bottom') {
            $horizontal_class = 'king-addons-horizontal-bottom-wrapper';
        }

        $this->horizontal_inner_class = ($horizontal_class === 'king-addons-horizontal-wrapper')
            ? 'king-addons-horizontal'
            : 'king-addons-horizontal-bottom';
        $this->horizontal_timeline_class = ($this->horizontal_inner_class === 'king-addons-horizontal')
            ? 'king-addons-horizontal-timeline'
            : 'king-addons-horizontal-bottom-timeline';
        $this->swiper_class = ($this->horizontal_timeline_class === 'king-addons-horizontal-timeline')
            ? 'swiper-slide-line-bottom'
            : 'swiper-slide-line-top';
    }

    public function render_custom_vertical_timeline($layout, $settings, $data, $countItem)
    {
        echo '<div class="king-addons-wrapper king-addons-vertical ' . esc_attr($this->timeline_layout_wrapper) . '">
            <div class="king-addons-timeline-centered king-addons-line ' . esc_attr($this->timeline_layout) . '">
            <div class="king-addons-middle-line"></div>';
        echo ('yes' === $this->timeline_fill)
            ? '<div class="king-addons-timeline-fill" data-layout="' . esc_attr($layout) . '"></div>'
            : '';

        foreach ($data as $index => $content) {
            if (!king_addons_freemius()->can_use_premium_code__premium_only() && $index === 4) {
                break;
            }
            $repeater_title_link = $content['repeater_title_link'] ?? '';
            /** @noinspection PhpIllegalStringOffsetInspection */
            if (!empty($repeater_title_link['url'])) {
                $this->add_link_attributes('repeater_title_link' . $this->item_url_count, $repeater_title_link);
            }

            $this->content_and_animation_alignment($layout, $countItem, $settings);
            $this->render_image_or_icon($content);

            $background_class = ($settings['content_layout'] === 'background') ? 'story-with-background' : '';

            if ($content['repeater_show_year_label'] === 'yes') {
                echo '<span class="king-addons-year-wrap">
                    <span class="king-addons-year-label king-addons-year">' . esc_html($content['repeater_year']) . '</span>
                  </span>';
            }

            echo '<article class="king-addons-timeline-entry ' . esc_attr($this->content_alignment) . ' elementor-repeater-item-' . esc_attr($content['_id']) . '" data-item-id="elementor-repeater-item-' . esc_attr($content['_id']) . '">';

            if ('yes' === $content['repeater_show_extra_label']) {
                if (!empty($content['repeater_date_label']) || !empty($content['repeater_extra_label'])) {
                    echo '<time class="king-addons-extra-label" data-aos="' . esc_attr($this->animation) . '" data-aos-left="' . esc_attr($this->animation_loadmore_left) . '" data-aos-right="' . esc_attr($this->animation_loadmore_right) . '" data-animation-offset="' . esc_attr($settings['animation_offset']) . '" data-animation-duration="' . esc_attr($settings['aos_animation_duration']) . '">';
                    echo !empty($content['repeater_date_label'])
                        ? '<span class="king-addons-label">' . esc_html($content['repeater_date_label']) . '</span>'
                        : '';
                    echo !empty($content['repeater_extra_label'])
                        ? '<span class="king-addons-sub-label">' . wp_kses_post($content['repeater_extra_label']) . '</span>'
                        : '';
                    echo '</time>';
                }
            }

            echo '<div class="king-addons-timeline-entry-inner">
                <div class="king-addons-main-line-icon king-addons-icon">';
            Icons_Manager::render_icon($content['repeater_story_icon'], ['aria-hidden' => 'true']);
            echo '</div>
              <div class="king-addons-story-info-vertical king-addons-data-wrap ' . esc_attr($background_class) . '" data-aos="' . esc_attr($this->animation) . '" data-aos-left="' . esc_attr($this->animation_loadmore_left) . '" data-aos-right="' . esc_attr($this->animation_loadmore_right) . '" data-animation-offset="' . esc_attr($settings['animation_offset']) . '" data-animation-duration="' . esc_attr($settings['aos_animation_duration']) . '">';

            // IMAGE TOP
            if ($settings['content_layout'] === 'image-top' && (!empty($this->image) || !empty($content['repeater_youtube_video_url']))) {
                echo '<div class="king-addons-animation-wrap king-addons-timeline-media">' . $this->image;
                if (!empty($content['repeater_youtube_video_url'])) {
                    echo '<div class="king-addons-timeline-iframe-wrapper"> ' . $this->youtube_url($content) . ' </div>';
                }
                if ('yes' === $settings['show_overlay'] && (!empty($this->image) || !empty($content['repeater_youtube_video_url']))) {
                    echo '<div class="king-addons-timeline-story-overlay ' . esc_attr($this->animation_class) . '">';
                    if (!empty($content['repeater_story_title']) && 'yes' === $settings['show_title'] && 'yes' === $settings['title_overlay']) {
                        /** @noinspection PhpIllegalStringOffsetInspection */
                        if (!empty($repeater_title_link['url'])) {
                            echo '<p class="king-addons-title-wrap"><a ' . $this->get_render_attribute_string('repeater_title_link' . $this->item_url_count) . ' class="king-addons-title">' . esc_html($content['repeater_story_title']) . '</a></p>';
                        } else {
                            echo '<p class="king-addons-title-wrap"><span class="king-addons-title">' . esc_html($content['repeater_story_title']) . '</span></p>';
                        }
                    }
                    if (!empty($content['repeater_description']) && 'yes' === $settings['show_description'] && 'yes' === $settings['description_overlay']) {
                        echo '<div class="king-addons-description">' . wp_kses_post($content['repeater_description']) . '</div>';
                    }
                    echo '</div>';
                }
                echo '</div>';
            }

            // NON-OVERLAY TITLE & DESCRIPTION
            if (
                ('yes' !== $settings['title_overlay'] && 'yes' === $settings['show_title'] && !empty($content['repeater_story_title'])) ||
                ('yes' !== $settings['description_overlay'] && 'yes' === $settings['show_description'] && !empty($content['repeater_description']))
            ) {
                echo '<div class="king-addons-timeline-content-wrapper">
                    <div class="king-addons-content-wrapper">';
                if (!empty($content['repeater_story_title']) && 'yes' === $settings['show_title'] && 'yes' !== $settings['title_overlay']) {
                    /** @noinspection PhpIllegalStringOffsetInspection */
                    if (!empty($repeater_title_link['url'])) {
                        echo '<p class="king-addons-title-wrap"><a ' . $this->get_render_attribute_string('repeater_title_link' . $this->item_url_count) . ' class="king-addons-title">' . esc_html($content['repeater_story_title']) . '</a></p>';
                    } else {
                        echo '<p class="king-addons-title-wrap"><span class="king-addons-title">' . esc_html($content['repeater_story_title']) . '</span></p>';
                    }
                }
                if (!empty($content['repeater_description']) && 'yes' === $settings['show_description'] && 'yes' !== $settings['description_overlay']) {
                    echo '<div class="king-addons-description">' . wp_kses_post($content['repeater_description']) . '</div>';
                }
                echo '</div>';
                if (!empty($content['repeater_youtube_video_url']) && $settings['content_layout'] !== 'image-top') {
                    echo '<div class="king-addons-timeline-iframe-wrapper"> ' . $this->youtube_url($content) . ' </div>';
                }
                echo '</div>';
            }

            // IMAGE BOTTOM
            if ($settings['content_layout'] === 'image-bottom' && !empty($this->image)) {
                echo '<div class="king-addons-animation-wrap king-addons-timeline-media">' . $this->image . '</div>';
            }

            echo '</div>
            </div>
        </article>';

            $countItem++;
            $this->item_url_count++;
        }
        echo '</div></div>';
    }

    public function render_dynamic_vertical_timeline($settings, $arrow_bgcolor, $layout, $countItem, $paged)
    {
        $layout_settings = ['pagination_type' => $settings['pagination_type']];
        $this->add_render_attribute('grid-settings', ['data-settings' => wp_json_encode($layout_settings)]);
        wp_reset_postdata();

        if (!$this->my_query->have_posts()) {
            echo '<div>' . esc_html($settings['query_not_found_text']) . '</div>';
            return;
        }

        echo '<div class="king-addons-wrapper king-addons-vertical ' . esc_attr($this->timeline_layout_wrapper) . '">
            <div class="king-addons-timeline-centered king-addons-line ' . esc_attr($this->timeline_layout) . '"
                 data-pagination="' . esc_attr($this->pagination_type) . '"
                 data-max-pages="' . esc_attr($this->pagination_max_pages) . '"
                 data-arrow-bgcolor="' . esc_attr($arrow_bgcolor) . '">
            <div class="king-addons-middle-line"></div>';
        echo ('yes' === $this->timeline_fill)
            ? '<div class="king-addons-timeline-fill" data-layout="' . esc_attr($layout) . '"></div>'
            : '';

        while ($this->my_query->have_posts()) {
            $this->my_query->the_post();
            $id = get_post_thumbnail_id();
            $this->src = Group_Control_Image_Size::get_attachment_image_src($id, 'king_addons_thumbnail_dynamic', $settings);
            $this->alt = get_post_meta($id, '_wp_attachment_image_alt', true);
            if (empty($this->alt)) {
                $this->alt = get_the_title($id);
            }
            $this->content_and_animation_alignment($layout, $countItem, $settings);
            $background_class = ($settings['content_layout'] === 'background') ? 'story-with-background' : '';

            echo '<article class="king-addons-timeline-entry ' . esc_attr($this->content_alignment) . '" data-counter="' . esc_attr($countItem) . '">';

            // Extra Label (date)
            if ('yes' === $settings['show_extra_label']) {
                echo '<time class="king-addons-extra-label"
                         data-aos="' . esc_attr($this->animation) . '"
                         data-aos-left="' . esc_attr($this->animation_loadmore_left) . '"
                         data-aos-right="' . esc_attr($this->animation_loadmore_right) . '"
                         data-animation-offset="' . esc_attr($settings['animation_offset']) . '"
                         data-animation-duration="' . esc_attr($settings['aos_animation_duration']) . '">
                    <span class="king-addons-label">' . esc_html(get_the_date($settings['date_format'])) . '</span>
                  </time>';
            }

            echo '<div class="king-addons-timeline-entry-inner">
                <div class="king-addons-main-line-icon king-addons-icon">';
            Icons_Manager::render_icon($settings['posts_icon'], ['aria-hidden' => 'true']);
            echo '</div>
              <div class="king-addons-story-info-vertical king-addons-data-wrap animated ' . esc_attr($background_class) . '"
                   data-aos="' . esc_attr($this->animation) . '"
                   data-aos-left="' . esc_attr($this->animation_loadmore_left) . '"
                   data-aos-right="' . esc_attr($this->animation_loadmore_right) . '"
                   data-animation-offset="' . esc_attr($settings['animation_offset']) . '"
                   data-animation-duration="' . esc_attr($settings['aos_animation_duration']) . '">';

            // IMAGE / OVERLAY
            if (($settings['content_layout'] === 'image-top' && !empty($this->src)) ||
                ('yes' === $settings['show_overlay'] && !empty($this->src))
            ) {
                echo '<div class="king-addons-animation-wrap king-addons-timeline-media">
                    <img class="king-addons-thumbnail-image" alt="' . esc_attr($this->alt) . '" src="' . esc_url($this->src) . '">';
                if ('yes' === $settings['show_overlay'] && !empty(get_the_post_thumbnail_url())) {
                    echo '<div class="king-addons-timeline-story-overlay ' . esc_attr($this->animation_class) . '">';
                    if ('yes' === $settings['show_title'] && 'yes' === $settings['title_overlay']) {
                        echo '<p class="king-addons-title-wrap"><a class="king-addons-title" href="' . esc_url(get_the_permalink()) . '">' . esc_html(get_the_title()) . '</a></p>';
                    }
                    if ('yes' === $settings['show_date'] && 'yes' === $settings['date_overlay']) {
                        echo '<div class="king-addons-inner-date-label">' . esc_html(get_the_date($settings['date_format'])) . '</div>';
                    }
                    if (!empty(get_the_content()) && 'yes' === $settings['show_description'] && 'yes' === $settings['description_overlay']) {
                        echo '<div class="king-addons-description">' . esc_html(wp_trim_words(get_the_content(), $settings['excerpt_count'])) . '</div>';
                    }
                    if ('yes' === $this->show_readmore && 'yes' === $settings['readmore_overlay']) {
                        echo '<div class="king-addons-read-more-wrap"><a class="king-addons-read-more-button" href="' . esc_url(get_the_permalink()) . '">' . esc_html($settings['read_more_text']) . '</a></div>';
                    }
                    echo '</div>';
                }
                echo '</div>';
            }

            // Non-Overlay
            $hasNonOverlayContent = (
                ('yes' === $settings['show_title'] && 'yes' !== $settings['title_overlay']) ||
                ('yes' === $settings['show_description'] && 'yes' !== $settings['description_overlay']) ||
                ('yes' === $settings['show_date'] && 'yes' !== $settings['date_overlay']) ||
                ('yes' === $this->show_readmore && 'yes' !== $settings['readmore_overlay'])
            );

            if ($hasNonOverlayContent) {
                echo '<div class="king-addons-timeline-content-wrapper">';
                if ('yes' === $settings['show_title'] && 'yes' !== $settings['title_overlay']) {
                    echo '<p class="king-addons-title-wrap"><a class="king-addons-title" href="' . esc_url(get_the_permalink()) . '">' . esc_html(get_the_title()) . '</a></p>';
                }
                if ('yes' === $settings['show_date'] && 'yes' !== $settings['date_overlay']) {
                    echo '<div class="king-addons-inner-date-label">' . esc_html(get_the_date($settings['date_format'])) . '</div>';
                }
                if (!empty(get_the_content()) && 'yes' === $settings['show_description'] && 'yes' !== $settings['description_overlay']) {
                    echo '<div class="king-addons-description">' . esc_html(wp_trim_words(get_the_content(), $settings['excerpt_count'])) . '</div>';
                }
                if ('yes' === $this->show_readmore && 'yes' !== $settings['readmore_overlay']) {
                    echo '<div class="king-addons-read-more-wrap"><a class="king-addons-read-more-button" href="' . esc_url(get_the_permalink()) . '">' . esc_html($settings['read_more_text']) . '</a></div>';
                }
                echo '</div>';
            }

            // IMAGE BOTTOM
            if ($settings['content_layout'] === 'image-bottom' && !empty($this->src)) {
                echo '<div class="king-addons-animation-wrap king-addons-timeline-media"><img class="king-addons-thumbnail-image" alt="' . esc_attr($this->alt) . '" src="' . esc_url($this->src) . '"></div>';
            }
            echo '</div></div></article>';
            $countItem++;
        }
        echo '</div></div>';

        if (!($settings['posts_per_page'] >= wp_count_posts($settings['timeline_post_types'])->publish)) {
            $this->render_pagination($settings, $paged);
        }
    }

    public function render_custom_horizontal_timeline($settings, $autoplay, $loop, $dir, $data, $slidesHeight, $swiper_speed, $swiper_delay, $swiper_pause_on_hover)
    {
        $this->horizontal_timeline_classes($settings);
        echo '<div class="king-addons-timeline-outer-container">
            <div class="king-addons-wrapper swiper ' . esc_attr($this->horizontal_inner_class) . '"
                 dir="' . esc_attr($dir) . '"
                 data-slidestoshow="' . esc_attr($this->slides_to_show) . '"
                 data-autoplay="' . esc_attr($autoplay) . '"
                 data-loop="' . esc_attr($loop) . '"
                 data-swiper-speed="' . esc_attr($swiper_speed) . '"
                 data-swiper-delay="' . esc_attr($swiper_delay) . '"
                 data-swiper-poh="' . esc_attr($swiper_pause_on_hover) . '"
                 data-swiper-space-between="' . esc_attr($settings['story_info_gutter']) . '">
                <div class="swiper-wrapper ' . esc_attr($this->horizontal_timeline_class) . '">';

        if (is_array($data)) {
            foreach ($data as $index => $content) {
                if (!king_addons_freemius()->can_use_premium_code__premium_only() && $index === 4) {
                    break;
                }
                $repeater_title_link = $content['repeater_title_link'] ?? '';
                if (!empty($repeater_title_link['url'])) {
                    $this->add_link_attributes('repeater_title_link' . $this->item_url_count, $repeater_title_link);
                }
                $this->add_custom_horizontal_timeline_attributes($content, $settings, $index);
                $this->render_image_or_icon($content);

                echo '<div class="swiper-slide ' . esc_attr($this->swiper_class) . ' ' . esc_attr($slidesHeight) . ' elementor-repeater-item-' . esc_attr($content['_id']) . '">';

                // Extra label
                if ('yes' === $content['repeater_show_extra_label']) {
                    if (!empty($this->story_date_label) || !empty($this->story_extra_label)) {
                        echo '<div class="king-addons-extra-label">';
                        if (!empty($this->story_date_label)) {
                            echo '<span ' . $this->get_render_attribute_string($this->date_label_key) . '>' . esc_html($this->story_date_label) . '</span>';
                        }
                        if (!empty($this->story_extra_label)) {
                            echo '<span ' . $this->get_render_attribute_string($this->extra_label_key) . '>' . wp_kses_post($this->story_extra_label) . '</span>';
                        }
                        echo '</div>';
                    }
                }

                echo '<div class="king-addons-main-line-icon king-addons-icon">';
                Icons_Manager::render_icon($content['repeater_story_icon'], ['aria-hidden' => 'true']);
                echo '</div>
                  <div class="king-addons-story-info ' . esc_attr($this->background_class) . '">';

                // IMAGE TOP
                if (($settings['content_layout'] === 'image-top' && !empty($this->image)) ||
                    ($settings['content_layout'] === 'image-top' && !empty($content['repeater_youtube_video_url']))) {
                    echo '<div class="king-addons-animation-wrap king-addons-timeline-media">';
                    echo $this->image;
                    if (!empty($content['repeater_youtube_video_url'])) {
                        echo '<div class="king-addons-timeline-iframe-wrapper"> ' . $this->youtube_url($content) . ' </div>';
                    }
                    if ('yes' === $settings['show_overlay'] && (!empty($this->image) || !empty($content['repeater_youtube_video_url']))) {
                        echo '<div class="king-addons-timeline-story-overlay ' . esc_attr($this->animation_class) . '">';
                        if (!empty($this->timeline_story_title) && 'yes' === $settings['show_title'] && 'yes' === $settings['title_overlay']) {
                            if (!empty($repeater_title_link['url'])) {
                                echo '<p class="king-addons-title-wrap"><a ' . $this->get_render_attribute_string('repeater_title_link' . $this->item_url_count) . $this->get_render_attribute_string($this->title_key) . '>' . esc_html($this->timeline_story_title) . '</a></p>';
                            } else {
                                echo '<p class="king-addons-title-wrap"><span ' . $this->get_render_attribute_string($this->title_key) . '>' . esc_html($this->timeline_story_title) . '</span></p>';
                            }
                        }
                        if (!empty($this->timeline_description) && 'yes' === $settings['show_description'] && 'yes' === $settings['description_overlay']) {
                            echo '<div ' . $this->get_render_attribute_string($this->description_key) . '>' . wp_kses_post($this->timeline_description) . '</div>';
                        }
                        echo '</div>';
                    }
                    echo '</div>';
                }

                // NON-OVERLAY TITLE & DESCRIPTION
                if (
                    ('yes' === $settings['show_title'] && 'yes' !== $settings['title_overlay'] && !empty($content['repeater_story_title'])) ||
                    ('yes' === $settings['show_description'] && 'yes' !== $settings['description_overlay'] && !empty($content['repeater_description']))
                ) {
                    echo '<div class="king-addons-timeline-content-wrapper">';
                    if (!empty($this->timeline_story_title) && 'yes' === $settings['show_title'] && 'yes' !== $settings['title_overlay']) {
                        if (!empty($repeater_title_link['url'])) {
                            echo '<p class="king-addons-title-wrap"><a ' . $this->get_render_attribute_string($this->title_key) . $this->get_render_attribute_string('repeater_title_link' . $this->item_url_count) . '>' . esc_html($this->timeline_story_title) . '</a></p>';
                        } else {
                            echo '<p class="king-addons-title-wrap"><span ' . $this->get_render_attribute_string($this->title_key) . '>' . esc_html($this->timeline_story_title) . '</span></p>';
                        }
                    }
                    if (!empty($this->timeline_description) && 'yes' === $settings['show_description'] && 'yes' !== $settings['description_overlay']) {
                        echo '<div ' . $this->get_render_attribute_string($this->description_key) . '>' . wp_kses_post($this->timeline_description) . '</div>';
                    }
                    if (!empty($content['repeater_youtube_video_url']) && $settings['content_layout'] !== 'image-top') {
                        echo '<div class="king-addons-timeline-iframe-wrapper"> ' . $this->youtube_url($content) . ' </div>';
                    }
                    echo '</div>';
                }

                // IMAGE BOTTOM
                if ($settings['content_layout'] === 'image-bottom' && !empty($this->image)) {
                    echo '<div class="king-addons-animation-wrap king-addons-timeline-media">' . $this->image . '</div>';
                }
                echo '</div></div>';
                $this->item_url_count++;
            }
        }
        $this->king_addons_render_swiper_navigation($settings);
        echo '</div>';
    }

    public function render_dynamic_horizontal_timeline($settings, $dir, $autoplay, $loop, $slidesHeight, $swiper_speed, $swiper_delay, $swiper_pause_on_hover)
    {
        wp_reset_postdata();
        $this->horizontal_timeline_classes($settings);

        if (!$this->my_query->have_posts()) {
            echo '<div>' . esc_html($settings['query_not_found_text']) . '</div>';
            return;
        }

        echo '<div class="king-addons-timeline-outer-container">
            <div class="king-addons-wrapper swiper ' . esc_attr($this->horizontal_inner_class) . '"
                 dir="' . esc_attr($dir) . '"
                 data-slidestoshow="' . esc_attr($this->slides_to_show) . '"
                 data-autoplay="' . esc_attr($autoplay) . '"
                 data-loop="' . esc_attr($loop) . '"
                 data-swiper-speed="' . esc_attr($swiper_speed) . '"
                 data-swiper-delay="' . esc_attr($swiper_delay) . '"
                 data-swiper-poh="' . esc_attr($swiper_pause_on_hover) . '"
                 data-swiper-space-between="' . esc_attr($settings['story_info_gutter']) . '">
                <div class="' . esc_attr($this->horizontal_timeline_class) . ' swiper-wrapper">';

        while ($this->my_query->have_posts()) {
            $this->my_query->the_post();
            $id = get_post_thumbnail_id();
            $this->src = Group_Control_Image_Size::get_attachment_image_src($id, 'king_addons_thumbnail_dynamic', $settings);
            $this->alt = get_post_meta($id, '_wp_attachment_image_alt', true);
            if (empty($this->alt)) {
                $this->alt = get_the_title($id);
            }
            $background_class = ($settings['content_layout'] === 'background') ? 'story-with-background' : '';

            echo '<div class="swiper-slide ' . esc_attr($this->swiper_class) . ' ' . esc_attr($slidesHeight) . '">
                <div class="king-addons-story-info ' . esc_attr($background_class) . '">';

            if (($settings['content_layout'] === 'image-top' && !empty($this->src)) ||
                ('yes' === $settings['show_overlay'] && !empty($this->src))
            ) {
                echo '<div class="king-addons-animation-wrap king-addons-timeline-media">';
                if ($settings['content_layout'] === 'image-top') {
                    echo '<img class="king-addons-thumbnail-image" src="' . esc_url($this->src) . '">';
                }
                if ('yes' === $settings['show_overlay'] && !empty(get_the_post_thumbnail_url())) {
                    echo '<div class="king-addons-timeline-story-overlay ' . esc_attr($this->animation_class) . '">';
                    if ('yes' === $settings['show_title'] && 'yes' === $settings['title_overlay']) {
                        echo '<p class="king-addons-title-wrap"><a class="king-addons-title" href="' . esc_url(get_the_permalink()) . '">' . esc_html(get_the_title()) . '</a></p>';
                    }
                    if ('yes' === $settings['show_date'] && 'yes' === $settings['date_overlay']) {
                        echo '<div class="king-addons-inner-date-label">' . esc_html(get_the_date($settings['date_format'])) . '</div>';
                    }
                    if (!empty(get_the_content()) && 'yes' === $settings['show_description'] && 'yes' === $settings['description_overlay']) {
                        echo '<div class="king-addons-description">' . esc_html(wp_trim_words(get_the_content(), $settings['excerpt_count'])) . '</div>';
                    }
                    if ('yes' === $this->show_readmore && 'yes' === $settings['readmore_overlay']) {
                        echo '<div class="king-addons-read-more-wrap"><a class="king-addons-read-more-button" href="' . esc_url(get_the_permalink()) . '">' . esc_html($settings['read_more_text']) . '</a></div>';
                    }
                    echo '</div>';
                }
                echo '</div>';
            }

            // Non-overlay blocks
            $hasNonOverlayContent = (
                ('yes' === $settings['show_title'] && 'yes' !== $settings['title_overlay']) ||
                ('yes' === $settings['show_description'] && 'yes' !== $settings['description_overlay']) ||
                ('yes' === $settings['show_date'] && 'yes' !== $settings['date_overlay']) ||
                ('yes' === $this->show_readmore && 'yes' !== $settings['readmore_overlay'])
            );

            if ($hasNonOverlayContent) {
                echo '<div class="king-addons-timeline-content-wrapper">';
                if ('yes' === $settings['show_title'] && 'yes' !== $settings['title_overlay']) {
                    echo '<p class="king-addons-title-wrap"><a class="king-addons-title" href="' . esc_url(get_the_permalink()) . '">' . esc_html(get_the_title()) . '</a></p>';
                }
                if ('yes' === $settings['show_date'] && 'yes' !== $settings['date_overlay']) {
                    echo '<div class="king-addons-inner-date-label">' . esc_html(get_the_date($settings['date_format'])) . '</div>';
                }
                if (!empty(get_the_content()) && 'yes' === $settings['show_description'] && 'yes' !== $settings['description_overlay']) {
                    echo '<div class="king-addons-description">' . esc_html(wp_trim_words(get_the_content(), $settings['excerpt_count'])) . '</div>';
                }
                if ('yes' === $this->show_readmore && 'yes' !== $settings['readmore_overlay']) {
                    echo '<div class="king-addons-read-more-wrap"><a class="king-addons-read-more-button" href="' . esc_url(get_the_permalink()) . '">' . esc_html($settings['read_more_text']) . '</a></div>';
                }
                echo '</div>';
            }

            // IMAGE BOTTOM
            if ($settings['content_layout'] === 'image-bottom' && !empty($this->src)) {
                echo '<div class="king-addons-animation-wrap king-addons-timeline-media"><img class="king-addons-thumbnail-image" alt="' . esc_attr($this->alt) . '" src="' . esc_url($this->src) . '"></div>';
            }
            echo '</div>';

            // Extra Label
            if ('yes' === $settings['show_extra_label']) {
                echo '<div class="king-addons-extra-label">
                    <span class="king-addons-label">' . esc_html(get_the_date($settings['date_format'])) . '</span>
                  </div>';
            }
            echo '<div class="king-addons-main-line-icon king-addons-icon">';
            Icons_Manager::render_icon($settings['posts_icon'], ['aria-hidden' => 'true']);
            echo '</div></div>';
        }
        $this->king_addons_render_swiper_navigation($settings);
        echo '</div>';
    }

    public function add_option_query_source()
    {
        $post_types = [
            'post' => esc_html__('Posts', 'king-addons'),
            'page' => esc_html__('Pages', 'king-addons'),
        ];

        $custom_post_types = Core::getCustomTypes('post');
        foreach ($custom_post_types as $slug => $title) {
            if ('product' === $slug || 'e-landing-page' === $slug) {
                continue;
            }
            if (!king_addons_freemius()->can_use_premium_code__premium_only()) {
                $post_types['pro-' . substr($slug, 0, 2)] = esc_html($title) . ' (Pro)';
            } else {
                $post_types[$slug] = esc_html($title);
            }
        }

        $post_types['current'] = esc_html__('Current Query', 'king-addons');
        $post_types['pro-rl'] = esc_html__('Related Query (Pro)', 'king-addons');
        return $post_types;
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        global $paged;
        $paged = 1;
        $this->my_query = ('dynamic' === $settings['timeline_content'])
            ? new WP_Query($this->get_main_query_args())
            : '';

        $layout = $settings['timeline_layout'];
        $this->animation = $settings['timeline_animation'];
        $this->animation_loadmore_left = '';
        $this->animation_loadmore_right = '';
        $this->timeline_fill = $settings['timeline_fill'];
        $this->show_readmore = $settings['show_readmore'] ?? '';
        $data = $settings['timeline_repeater_list'];
        $loop = (!king_addons_freemius()->can_use_premium_code__premium_only() && !isset($settings['swiper_loop']))
            ? ''
            : $settings['swiper_loop'];
        $autoplay = (!king_addons_freemius()->can_use_premium_code__premium_only() && !isset($settings['swiper_autoplay']))
            ? ''
            : $settings['swiper_autoplay'];
        $swiper_delay = (!king_addons_freemius()->can_use_premium_code__premium_only() && !isset($settings['swiper_delay']))
            ? 0
            : $settings['swiper_delay'];
        $swiper_pause_on_hover = (!king_addons_freemius()->can_use_premium_code__premium_only() && !isset($settings['swiper_pause_on_hover']))
            ? ''
            : $settings['swiper_pause_on_hover'];
        $swiper_speed = $settings['swiper_speed'];
        $slidesHeight = $settings['equal_height_slides'];
        $this->pagination_type = $settings['pagination_type'] ?? '';
        $this->pagination_max_pages = $this->get_max_num_pages() ?: '';
        $arrow_bgcolor = $settings['triangle_bgcolor'];

        $animation_settings = [
            'overlay_animation' => $settings['overlay_animation'],
            'overlay_animation_size' => $settings['overlay_animation_size'],
            'overlay_animation_timing' => $settings['overlay_animation_timing'],
            'overlay_animation_tr' => $settings['overlay_animation_tr'],
        ];
        $this->animation_class = $this->get_animation_class($animation_settings, 'overlay');

        $dir = is_rtl() ? 'rtl' : '';

        if ('one-sided' === $layout) {
            $this->timeline_layout = "king-addons-one-sided-timeline";
            $this->timeline_layout_wrapper = "king-addons-one-sided-wrapper";
        } elseif ('centered' === $layout) {
            $this->timeline_layout = 'king-addons-both-sided-timeline';
            $this->timeline_layout_wrapper = 'king-addons-centered';
        } elseif ('one-sided-left' === $layout) {
            $this->timeline_layout = "king-addons-one-sided-timeline-left";
            $this->timeline_layout_wrapper = "king-addons-one-sided-wrapper-left";
        } elseif ('horizontal' === $layout) {
            $this->timeline_layout = "king-addons-horizontal-timeline";
            $this->timeline_layout_wrapper = "king-addons-horizontal-wrapper";
        }

        $countItem = $countItem ?? 0;
        $this->item_url_count = 0;

        // Dynamic / Horizontal
        if ('dynamic' === $settings['timeline_content'] && ('horizontal' === $layout || 'horizontal-bottom' === $layout)) {
            $this->render_dynamic_horizontal_timeline($settings, $dir, $autoplay, $loop, $slidesHeight, $swiper_speed, $swiper_delay, $swiper_pause_on_hover);
        } // Custom / Horizontal
        elseif ('custom' === $settings['timeline_content'] && ('horizontal' === $layout || 'horizontal-bottom' === $layout)) {
            $this->render_custom_horizontal_timeline($settings, $autoplay, $loop, $dir, $data, $slidesHeight, $swiper_speed, $swiper_delay, $swiper_pause_on_hover);
        } // Dynamic / Vertical
        else {
            if ('dynamic' === $settings['timeline_content']) {
                $this->render_dynamic_vertical_timeline($settings, $arrow_bgcolor, $layout, $countItem, $paged);
            } else {
                $this->render_custom_vertical_timeline($layout, $settings, $data, $countItem);
            }
        }
    }


}

new Timeline();