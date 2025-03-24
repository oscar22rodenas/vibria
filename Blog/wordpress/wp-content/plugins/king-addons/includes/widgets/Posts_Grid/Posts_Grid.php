<?php

namespace King_Addons;

use Elementor\Embed;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Image_Size;
use WP_Query;

if (!defined('ABSPATH')) {
    exit;
}

class Posts_Grid extends Widget_Base
{

    public function get_name()
    {
        return 'king-addons-grid';
    }

    public function get_title()
    {
        return esc_html__('Posts Grid & Slider/Carousel', 'king-addons');
    }

    public function get_icon()
    {
        return 'king-addons-icon king-addons-posts-grid';
    }

    public function get_categories()
    {
        return ['king-addons'];
    }

    public function get_keywords()
    {
        return ['king addons', 'king', 'addons', 'kingaddons', 'king-addons', 'gr', 'carousel', 'portfolio', 'loop', 'filterable',
            'blog', 'portfolio grid', 'posts', 'post grid', 'posts grid', 'filter', 'post', 'posts', 'masonry', 'grid',
            'post slider', 'posts slider', 'post carousel', 'posts carousel', 'masonry grid', 'gallery', 'slider',
            'isotope', 'post gallery', 'posts gallery', 'filterable grid', 'loop grid'];
    }

    public function get_script_depends()
    {
        return [
            KING_ADDONS_ASSETS_UNIQUE_KEY . '-imagesloaded-imagesloaded',
            KING_ADDONS_ASSETS_UNIQUE_KEY . '-infinitescroll-infinitescroll',
            KING_ADDONS_ASSETS_UNIQUE_KEY . '-isotope-kng',
            KING_ADDONS_ASSETS_UNIQUE_KEY . '-slick-slick',
//            KING_ADDONS_ASSETS_UNIQUE_KEY . '-lightgallery-lightgallery',
            KING_ADDONS_ASSETS_UNIQUE_KEY . '-grid-grid',
        ];
    }

    public function get_style_depends()
    {
        return [
            KING_ADDONS_ASSETS_UNIQUE_KEY . '-slick-helper',
            KING_ADDONS_ASSETS_UNIQUE_KEY . '-animation-button',
            KING_ADDONS_ASSETS_UNIQUE_KEY . '-animation-general',
            KING_ADDONS_ASSETS_UNIQUE_KEY . '-animation-timing',
            KING_ADDONS_ASSETS_UNIQUE_KEY . '-animation-loading',
//            KING_ADDONS_ASSETS_UNIQUE_KEY . '-lightgallery-lightgallery',
            KING_ADDONS_ASSETS_UNIQUE_KEY . '-grid-grid',
            KING_ADDONS_ASSETS_UNIQUE_KEY . '-general-general',
        ];
    }

    public function get_custom_help_url()
    {
        return 'https://kingaddons.com/';
    }

    public function is_reload_preview_required()
    {
        return true;
    }

    public function has_widget_inner_wrapper(): bool
    {
        return true;
    }

    public function add_option_query_source()
    {
        $post_types = [];
        $post_types['post'] = esc_html__('Posts', 'king-addons');
        $post_types['page'] = esc_html__('Pages', 'king-addons');

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

    public function get_available_taxonomies()
    {
        $post_taxonomies = [];
        $post_taxonomies['category'] = esc_html__('Categories', 'king-addons');
        $post_taxonomies['post_tag'] = esc_html__('Tags', 'king-addons');

        $custom_post_taxonomies = Core::getCustomTypes('tax');
        foreach ($custom_post_taxonomies as $slug => $title) {
            if ('product_tag' === $slug || 'product_cat' === $slug) {
                continue;
            }

            if (!king_addons_freemius()->can_use_premium_code__premium_only()) {
                $post_taxonomies['pro-' . substr($slug, 0, 2)] = esc_html($title) . ' (Pro)';
            } else {
                $post_taxonomies[$slug] = esc_html($title);
            }
        }

        return $post_taxonomies;
    }

    public function add_control_secondary_img_on_hover()
    {
        $this->add_control(
            'secondary_img_on_hover',
            [
                'label' => sprintf(__('Secondary Image on Hover %s', 'king-addons'), '<i class="eicon-pro-icon"></i>'),
                'type' => Controls_Manager::SWITCHER,
                'classes' => 'king-addons-pro-control no-distance'
            ]
        );
    }

    public function add_control_open_links_in_new_tab()
    {
        $this->add_control(
            'open_links_in_new_tab',
            [
                'label' => sprintf(__('Open Links in New Tab %s', 'king-addons'), '<i class="eicon-pro-icon"></i>'),
                'type' => Controls_Manager::SWITCHER,
                'classes' => 'king-addons-pro-control no-distance'
            ]
        );
    }

    public function add_control_grid_lazy_loading()
    {
        $this->add_control(
            'grid_lazy_loading',
            [
                'label' => sprintf(__('Lazy Loading %s', 'king-addons'), '<i class="eicon-pro-icon"></i>'),
                'type' => Controls_Manager::SWITCHER,
                'classes' => 'king-addons-pro-control no-distance'
            ]
        );
    }

    public function add_control_display_scheduled_posts()
    {
        $this->add_control(
            'display_scheduled_posts',
            [
                'label' => sprintf(__('Display Only Scheduled %s', 'king-addons'), '<i class="eicon-pro-icon"></i>'),
                'type' => Controls_Manager::SWITCHER,
                'classes' => 'king-addons-pro-control no-distance'
            ]
        );
    }

    public function add_control_query_randomize()
    {
        $this->add_control(
            'query_randomize',
            [
                'label' => sprintf(__('Randomize Query %s', 'king-addons'), '<i class="eicon-pro-icon"></i>'),
                'type' => Controls_Manager::SWITCHER,
                'classes' => 'king-addons-pro-control no-distance'
            ]
        );
    }

    public function add_control_order_posts()
    {
        $this->add_control(
            'order_posts',
            [
                'label' => esc_html__('Order By', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'date',
                'label_block' => false,
                'options' => [
                    'date' => esc_html__('Date', 'king-addons'),
                    'pro-tl' => esc_html__('Title (Pro)', 'king-addons'),
                    'pro-mf' => esc_html__('Last Modified (Pro)', 'king-addons'),
                    'pro-d' => esc_html__('Post ID (Pro)', 'king-addons'),
                    'pro-ar' => esc_html__('Post Author (Pro)', 'king-addons'),
                    'pro-cc' => esc_html__('Comment Count (Pro)', 'king-addons'),
                    'pro-mv' => esc_html__('Custom Field (Pro)', 'king-addons')
                ],
                'condition' => [
                    'query_randomize!' => 'rand',
                ]
            ]
        );
    }

    public function add_control_order_posts_by_acf($meta)
    {
    }

    public function add_control_query_slides_to_show()
    {
        $this->add_control(
            'query_slides_to_show',
            [
                'label' => esc_html__('Slides to Show', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'default' => 4,
                'min' => 0,
                'max' => 4,
                'condition' => [
                    'layout_select' => 'slider',
                ],
            ]
        );
    }

    public function add_control_layout_select()
    {
        $this->add_control(
            'layout_select',
            [
                'label' => esc_html__('Select Layout', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'fitRows',
                'options' => [
                    'fitRows' => esc_html__('FitRows - Equal Height', 'king-addons'),
                    'list' => esc_html__('List Style', 'king-addons'),
                    'slider' => esc_html__('Slider / Carousel', 'king-addons'),
                    'pro-ms' => esc_html__('Masonry - Unlimited Height (Pro)', 'king-addons'),
                ],
                'render_type' => 'template'
            ]
        );
    }

    public function add_control_layout_columns()
    {
        $this->add_responsive_control(
            'layout_columns',
            [
                'label' => esc_html__('Columns', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 3,
                'widescreen_default' => 3,
                'laptop_default' => 3,
                'tablet_extra_default' => 3,
                'tablet_default' => 2,
                'mobile_extra_default' => 2,
                'mobile_default' => 1,
                'options' => [
                    1 => esc_html__('One', 'king-addons'),
                    2 => esc_html__('Two', 'king-addons'),
                    3 => esc_html__('Three', 'king-addons'),
                    'pro-4' => esc_html__('Four (Pro)', 'king-addons'),
                    'pro-5' => esc_html__('Five (Pro)', 'king-addons'),
                    'pro-6' => esc_html__('Six (Pro)', 'king-addons'),
                ],
                'prefix_class' => 'king-addons-grid-columns-%s',
                'render_type' => 'template',
                'separator' => 'before',
                'condition' => [
                    'layout_select' => ['fitRows', 'masonry', 'list'],
                ]
            ]
        );
    }

    public function add_control_layout_animation()
    {
        $this->add_control(
            'layout_animation',
            [
                'label' => esc_html__('Select Animation', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'default',
                'options' => [
                    'default' => esc_html__('None', 'king-addons'),
                    'zoom' => esc_html__('Zoom', 'king-addons'),
                    'pro-fd' => esc_html__('Fade (Pro)', 'king-addons'),
                    'pro-fs' => esc_html__('Fade + SlideUp (Pro)', 'king-addons'),
                ],
                'selectors_dictionary' => [
                    'default' => '',
                    'zoom' => 'opacity: 0; transform: scale(0.01)',
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-item-inner' => '{{VALUE}}',
                ],
                'render_type' => 'template',
                'separator' => 'before',
                'condition' => [
                    'layout_select!' => 'slider',
                ]
            ]
        );
    }

    public function add_control_layout_slider_amount()
    {
        $this->add_responsive_control(
            'layout_slider_amount',
            [
                'label' => esc_html__('Columns (Carousel)', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'label_block' => false,
                'default' => 2,
                'widescreen_default' => 2,
                'laptop_default' => 2,
                'tablet_extra_default' => 2,
                'tablet_default' => 2,
                'mobile_extra_default' => 2,
                'mobile_default' => 1,
                'options' => [
                    1 => esc_html__('One', 'king-addons'),
                    2 => esc_html__('Two', 'king-addons'),
                    'pro-3' => esc_html__('Three (Pro)', 'king-addons'),
                    'pro-4' => esc_html__('Four (Pro)', 'king-addons'),
                    'pro-5' => esc_html__('Five (Pro)', 'king-addons'),
                    'pro-6' => esc_html__('Six (Pro)', 'king-addons'),
                ],
                'prefix_class' => 'king-addons-grid-slider-columns-%s',
                'render_type' => 'template',
                'frontend_available' => true,
                'separator' => 'before',
                'condition' => [
                    'layout_select' => 'slider',
                ],
            ]
        );
    }

    public function add_control_layout_slider_nav_hover()
    {
        $this->add_control(
            'layout_slider_nav_hover',
            [
                'label' => sprintf(__('Show on Hover %s', 'king-addons'), '<i class="eicon-pro-icon"></i>'),
                'type' => Controls_Manager::SWITCHER,
                'classes' => 'king-addons-pro-control no-distance',
                'condition' => [
                    'layout_slider_nav' => 'yes',
                    'layout_select' => 'slider',

                ],
            ]
        );
    }

    public function add_control_layout_slider_dots_position()
    {
        $this->add_control(
            'layout_slider_dots_position',
            [
                'label' => esc_html__('Pagination Layout', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'horizontal',
                'options' => [
                    'horizontal' => esc_html__('Horizontal', 'king-addons'),
                    'pro-vr' => esc_html__('Vertical (Pro)', 'king-addons'),
                ],
                'prefix_class' => 'king-addons-grid-slider-dots-',
                'render_type' => 'template',
                'condition' => [
                    'layout_slider_dots' => 'yes',
                    'layout_select' => 'slider',
                ],
            ]
        );
    }

    public function add_control_layout_slider_autoplay()
    {
        $this->add_control(
            'layout_slider_autoplay',
            [
                'label' => sprintf(__('Autoplay %s', 'king-addons'), '<i class="eicon-pro-icon"></i>'),
                'type' => Controls_Manager::SWITCHER,
                'classes' => 'king-addons-pro-control',
                'separator' => 'before',
                'condition' => [
                    'layout_select' => 'slider',
                ],
            ]
        );
    }

    public function add_controls_group_layout_slider_autoplay()
    {
    }

    public function add_option_element_select()
    {
        return [
            'title' => esc_html__('Title', 'king-addons'),
            'content' => esc_html__('Content', 'king-addons'),
            'excerpt' => esc_html__('Excerpt', 'king-addons'),
            'date' => esc_html__('Date', 'king-addons'),
            'time' => esc_html__('Time', 'king-addons'),
            'author' => esc_html__('Author', 'king-addons'),
            'comments' => esc_html__('Comments', 'king-addons'),
            'read-more' => esc_html__('Read More', 'king-addons'),
//            TODO: Lightbox
//            'lightbox' => esc_html__('Lightbox', 'king-addons'),
            'separator' => esc_html__('Separator', 'king-addons'),
            'pro-lk' => esc_html__('Likes (Pro)', 'king-addons'),
            'pro-shr' => esc_html__('Sharing (Pro)', 'king-addons'),
            'pro-cf' => esc_html__('Custom Field (Pro)', 'king-addons'),
        ];
    }

    public function add_repeater_args_element_like_icon()
    {
        return [
            'type' => Controls_Manager::HIDDEN,
            'default' => ''
        ];
    }

    public function add_repeater_args_element_like_text()
    {
        return [
            'type' => Controls_Manager::HIDDEN,
            'default' => ''
        ];
    }

    public function add_repeater_args_element_like_show_count()
    {
        return [
            'type' => Controls_Manager::HIDDEN,
            'default' => ''
        ];
    }

    public function add_repeater_args_element_sharing_icon_1()
    {
        return [
            'type' => Controls_Manager::HIDDEN,
            'default' => ''
        ];
    }

    public function add_repeater_args_element_sharing_icon_2()
    {
        return [
            'type' => Controls_Manager::HIDDEN,
            'default' => ''
        ];
    }

    public function add_repeater_args_element_sharing_icon_3()
    {
        return [
            'type' => Controls_Manager::HIDDEN,
            'default' => ''
        ];
    }

    public function add_repeater_args_element_sharing_icon_4()
    {
        return [
            'type' => Controls_Manager::HIDDEN,
            'default' => ''
        ];
    }

    public function add_repeater_args_element_sharing_icon_5()
    {
        return [
            'type' => Controls_Manager::HIDDEN,
            'default' => ''
        ];
    }

    public function add_repeater_args_element_sharing_icon_6()
    {
        return [
            'type' => Controls_Manager::HIDDEN,
            'default' => ''
        ];
    }

    public function add_repeater_args_element_sharing_trigger()
    {
        return [
            'type' => Controls_Manager::HIDDEN,
            'default' => ''
        ];
    }

    public function add_repeater_args_element_sharing_trigger_icon()
    {
        return [
            'type' => Controls_Manager::HIDDEN,
            'default' => ''
        ];
    }

    public function add_repeater_args_element_sharing_trigger_action()
    {
        return [
            'type' => Controls_Manager::HIDDEN,
            'default' => ''
        ];
    }

    public function add_repeater_args_element_sharing_trigger_direction()
    {
        return [
            'type' => Controls_Manager::HIDDEN,
            'default' => ''
        ];
    }

    public function add_repeater_args_element_sharing_tooltip()
    {
        return [
            'type' => Controls_Manager::HIDDEN,
            'default' => ''
        ];
    }

    public function add_repeater_args_element_custom_field()
    {
        return [
            'type' => Controls_Manager::HIDDEN,
            'default' => ''
        ];
    }

    public function add_repeater_args_element_custom_field_img_ID()
    {
        return [
            'type' => Controls_Manager::HIDDEN,
            'default' => ''
        ];
    }

    public function add_repeater_args_element_custom_field_btn_link()
    {
        return [
            'type' => Controls_Manager::HIDDEN,
            'default' => ''
        ];
    }

    public function add_repeater_args_element_custom_field_new_tab()
    {
        return [
            'type' => Controls_Manager::HIDDEN,
            'default' => ''
        ];
    }

    public function add_repeater_args_custom_field_wrapper_html_divider1()
    {
        return [
            'type' => Controls_Manager::HIDDEN,
            'default' => ''
        ];
    }

    public function add_repeater_args_element_custom_field_wrapper()
    {
        return [
            'type' => Controls_Manager::HIDDEN,
            'default' => ''
        ];
    }

    public function add_repeater_args_element_custom_field_wrapper_html()
    {
        return [
            'type' => Controls_Manager::HIDDEN,
            'default' => ''
        ];
    }

    public function add_repeater_args_custom_field_wrapper_html_divider2()
    {
        return [
            'type' => Controls_Manager::HIDDEN,
            'default' => ''
        ];
    }

    public function add_repeater_args_element_cf_tag()
    {
        return [
            'type' => Controls_Manager::HIDDEN,
            'default' => ''
        ];
    }

    public function add_repeater_args_element_custom_field_style()
    {
        return [
            'type' => Controls_Manager::HIDDEN,
            'default' => ''
        ];
    }

    public function add_repeater_args_element_trim_text_by()
    {
        return [
            'word_count' => esc_html__('Word Count', 'king-addons'),
            'pro-lc' => esc_html__('Letter Count (Pro)', 'king-addons')
        ];
    }

    public function add_control_overlay_animation_divider()
    {
    }

    public function add_control_overlay_image()
    {
    }

    public function add_control_overlay_image_width()
    {
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
                    'slide' => esc_html__('Slide', 'king-addons'),
                ],
                'default' => 'none',
            ]
        );
    }

    /** @noinspection PhpUnused */
    public function add_section_general_lightbox() {
        $this->start_controls_section(
            'section_lightbox_popup',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Lightbox Popup', 'king-addons'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'lightbox_popup_autoplay',
            [
                'label' => esc_html__('Autoplay Slides', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'true',
                'return_value' => 'true',
            ]
        );

        $this->add_control(
            'lightbox_popup_progressbar',
            [
                'label' => esc_html__('Show Progress Bar', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'true',
                'return_value' => 'true',
                'condition' => [
                    'lightbox_popup_autoplay' => 'true'
                ]
            ]
        );

        $this->add_control(
            'lightbox_popup_pause',
            [
                'label' => esc_html__('Autoplay Speed', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'default' => 5,
                'min' => 1,
                'max' => 10,
                'step' => 1,
                'condition' => [
                    'lightbox_popup_autoplay' => 'true',
                ],
            ]
        );

        $this->add_control(
            'lightbox_popup_counter',
            [
                'label' => esc_html__('Show Counter', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'true',
                'return_value' => 'true',
            ]
        );

        $this->add_control(
            'lightbox_popup_arrows',
            [
                'label' => esc_html__('Show Arrows', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'true',
                'return_value' => 'true',
            ]
        );

        $this->add_control(
            'lightbox_popup_captions',
            [
                'label' => esc_html__('Show Captions', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'true',
                'return_value' => 'true',
            ]
        );

        $this->add_control_lightbox_popup_thumbnails();

        $this->add_control_lightbox_popup_thumbnails_default();

        $this->add_control_lightbox_popup_sharing();

        $this->add_control(
            'lightbox_popup_zoom',
            [
                'label' => esc_html__('Show Zoom Button', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'true',
                'return_value' => 'true',
            ]
        );

        $this->add_control(
            'lightbox_popup_fullscreen',
            [
                'label' => esc_html__('Show Full Screen Button', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'true',
                'return_value' => 'true',
            ]
        );

        $this->add_control(
            'lightbox_popup_download',
            [
                'label' => esc_html__('Show Download Button', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'true',
                'return_value' => 'true',
            ]
        );

        $this->add_control(
            'lightbox_popup_description',
            [
                'raw' => sprintf(__('You can change Lightbox Popup styling options globally. Navigate to <strong>Dashboard > %s > Settings</strong>.', 'king-addons'), Core::getPluginName()),
                'type' => Controls_Manager::RAW_HTML,
                'separator' => 'before',
                'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
            ]
        );

        $this->end_controls_section();
    }

    /** @noinspection PhpUnused */
    public function add_section_style_lightbox() {
        $this->start_controls_section(
            'section_style_lightbox',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Lightbox', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            ]
        );

        $this->start_controls_tabs('tabs_grid_lightbox_style');

        $this->start_controls_tab(
            'tab_grid_lightbox_normal',
            [
                'label' => esc_html__('Normal', 'king-addons'),
            ]
        );

        $this->add_control(
            'lightbox_color',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-item-lightbox .inner-block > span' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'lightbox_bg_color',
            [
                'label' => esc_html__('Background Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-item-lightbox .inner-block > span' => 'background-color: {{VALUE}}',
                ]
            ]
        );

        $this->add_control(
            'lightbox_border_color',
            [
                'label' => esc_html__('Border Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#E8E8E8',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-item-lightbox .inner-block > span' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'lightbox_shadow',
                'selector' => '{{WRAPPER}} .king-addons-grid-item-lightbox i',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_grid_lightbox_hover',
            [
                'label' => esc_html__('Hover', 'king-addons'),
            ]
        );

        $this->add_control(
            'lightbox_color_hr',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#5B03FF',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-item-lightbox .inner-block > span:hover' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'lightbox_bg_color_hr',
            [
                'label' => esc_html__('Background Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-item-lightbox .inner-block > span:hover' => 'background-color: {{VALUE}}',
                ]
            ]
        );

        $this->add_control(
            'lightbox_border_color_hr',
            [
                'label' => esc_html__('Border Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#E8E8E8',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-item-lightbox .inner-block > span:hover' => 'border-color: {{VALUE}}',
                ],
                'separator' => 'after',
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_control(
            'lightbox_shadow_divider',
            [
                'type' => Controls_Manager::DIVIDER,
                'style' => 'thick',
            ]
        );

        $this->add_control(
            'lightbox_transition_duration',
            [
                'label' => esc_html__('Transition Duration (seconds)', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'default' => 0.1,
                'min' => 0,
                'max' => 5,
                'step' => 0.1,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-item-lightbox .inner-block > span' => 'transition-duration: {{VALUE}}s',
                ],
                'separator' => 'after',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'lightbox_typography',
                'selector' => '{{WRAPPER}} .king-addons-grid-item-lightbox'
            ]
        );

        $this->add_control(
            'lightbox_border_type',
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
                    '{{WRAPPER}} .king-addons-grid-item-lightbox .inner-block > span' => 'border-style: {{VALUE}};',
                ],
                'render_type' => 'template',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'lightbox_border_width',
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
                    '{{WRAPPER}} .king-addons-grid-item-lightbox .inner-block > span' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'render_type' => 'template',
                'condition' => [
                    'lightbox_border_type!' => 'none',
                ],
            ]
        );

        $this->add_control(
            'lightbox_text_spacing',
            [
                'label' => esc_html__('Extra Text Spacing', 'king-addons'),
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
                    '{{WRAPPER}} .king-addons-grid-item-lightbox .king-addons-grid-extra-text-left' => 'padding-right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .king-addons-grid-item-lightbox .king-addons-grid-extra-text-right' => 'padding-left: {{SIZE}}{{UNIT}};',
                ],
                'separator' => 'before'
            ]
        );

        $this->add_responsive_control(
            'lightbox_padding',
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
                    '{{WRAPPER}} .king-addons-grid-item-lightbox .inner-block > span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'render_type' => 'template',
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'lightbox_margin',
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
                    '{{WRAPPER}} .king-addons-grid-item-lightbox .inner-block' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'lightbox_radius',
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
                    '{{WRAPPER}} .king-addons-grid-item-lightbox .inner-block > span' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->end_controls_section();
    }
        
    public function add_control_lightbox_popup_thumbnails()
    {
        $this->add_control(
            'lightbox_popup_thumbnails',
            [
                'label' => sprintf(__('Show Thumbnails %s', 'king-addons'), '<i class="eicon-pro-icon"></i>'),
                'type' => Controls_Manager::SWITCHER,
                'classes' => 'king-addons-pro-control no-distance'
            ]
        );
    }

    public function add_control_lightbox_popup_thumbnails_default()
    {
        $this->add_control(
            'popup_thumbnails_default',
            [
                'label' => sprintf(__('Show Thumbs by Default %s', 'king-addons'), '<i class="eicon-pro-icon"></i>'),
                'type' => Controls_Manager::SWITCHER,
                'classes' => 'king-addons-pro-control no-distance'
            ]
        );
    }

    public function add_control_lightbox_popup_sharing()
    {
        $this->add_control(
            'lightbox_popup_sharing',
            [
                'label' => sprintf(__('Show Sharing Button %s', 'king-addons'), '<i class="eicon-pro-icon"></i>'),
                'type' => Controls_Manager::SWITCHER,
                'classes' => 'king-addons-pro-control no-distance'
            ]
        );
    }

    public function add_control_filters_deeplinking()
    {
        $this->add_control(
            'filters_deeplinking',
            [
                'label' => sprintf(__('Enable Deep Linking %s', 'king-addons'), '<i class="eicon-pro-icon"></i>'),
                'type' => Controls_Manager::SWITCHER,
                'classes' => 'king-addons-pro-control no-distance'
            ]
        );
    }

    public function add_control_filters_animation()
    {
        $this->add_control(
            'filters_animation',
            [
                'label' => esc_html__('Select Animation', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'default',
                'options' => [
                    'default' => esc_html__('Default', 'king-addons'),
                    'zoom' => esc_html__('Zoom', 'king-addons'),
                    'pro-fd' => esc_html__('Fade (Pro)', 'king-addons'),
                    'pro-fs' => esc_html__('Fade + SlideUp (Pro)', 'king-addons'),
                ],
                'separator' => 'before',
            ]
        );
    }

    public function add_control_filters_icon()
    {
    }

    public function add_control_filters_icon_align()
    {
    }

    public function add_control_filters_count()
    {
        $this->add_control(
            'filters_count',
            [
                'label' => sprintf(__('Show Count %s', 'king-addons'), '<i class="eicon-pro-icon"></i>'),
                'type' => Controls_Manager::SWITCHER,
                'classes' => 'king-addons-pro-control no-distance'
            ]
        );
    }

    public function add_control_filters_count_superscript()
    {
    }

    public function add_control_filters_count_brackets()
    {
    }

    public function add_control_filters_default_filter()
    {
    }

    public function add_control_pagination_type()
    {
        $options = [
            'default' => esc_html__('Default', 'king-addons'),
            'numbered' => esc_html__('Numbered', 'king-addons'),
            'load-more' => esc_html__('Load More Button', 'king-addons'),
            'pro-is' => esc_html__('Infinite Scrolling (Pro)', 'king-addons'),
        ];

        if (king_addons_freemius()->can_use_premium_code__premium_only()) {
            $options = [
                'default' => esc_html__('Default', 'king-addons'),
                'load-more' => esc_html__('Load More Button', 'king-addons'),
                'pro-nb' => esc_html__('Numbered (Pro)', 'king-addons'),
                'pro-is' => esc_html__('Infinite Scrolling (Pro)', 'king-addons'),
            ];
        }

        $this->add_control(
            'pagination_type',
            [
                'label' => esc_html__('Select Type', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'load-more',
                'options' => $options,
                'separator' => 'after'
            ]
        );
    }

    public function add_section_style_likes()
    {
    }

    public function add_section_style_sharing()
    {
    }

    public function add_section_style_custom_field1()
    {
    }

    public function add_section_style_custom_field2()
    {
    }

    public function add_section_style_custom_field3()
    {
    }

    public function add_section_style_custom_field4()
    {
    }

    public function add_control_grid_item_even_bg_color()
    {
    }

    public function add_control_grid_item_even_border_color()
    {
    }

    public function add_control_overlay_color()
    {
        $this->add_control(
            'overlay_color',
            [
                'label' => esc_html__('Background Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => 'rgba(0, 0, 0, 0.25)',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-media-hover-bg' => 'background-color: {{VALUE}}',
                ],
            ]
        );
    }

    public function add_control_overlay_blend_mode()
    {
    }

    public function add_control_overlay_border_color()
    {
    }

    public function add_control_overlay_border_type()
    {
    }

    public function add_control_overlay_border_width()
    {
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

    public function add_control_tax1_custom_colors($meta)
    {
    }

    public function add_control_tax1_pointer_color_hr()
    {
    }

    public function add_control_tax1_pointer()
    {
    }

    public function add_control_tax1_pointer_height()
    {
    }

    public function add_control_tax1_pointer_animation()
    {
    }

    public function add_control_tax2_pointer_color_hr()
    {
    }

    public function add_control_tax2_pointer()
    {
    }

    public function add_control_tax2_pointer_height()
    {
    }

    public function add_control_tax2_pointer_animation()
    {
    }

    public function add_control_read_more_animation()
    {
    }

    public function add_control_read_more_animation_height()
    {
    }

    public function add_control_filters_pointer_color_hr()
    {
    }

    public function add_control_filters_pointer()
    {
    }

    public function add_control_filters_pointer_height()
    {
    }

    public function add_control_filters_pointer_animation()
    {
    }

    public function add_control_stack_grid_slider_nav_position()
    {
    }

    public function add_control_grid_slider_dots_hr()
    {
    }

    protected function register_controls()
    {


        $this->start_controls_section(
            'section_grid_query',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Query', 'king-addons'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );


        $post_types = $this->add_option_query_source();

        $post_taxonomies = $this->get_available_taxonomies();

        $tax_meta_keys = Core::getCustomMetaKeysTaxonomies();

        $this->add_control(
            'query_source',
            [
                'label' => esc_html__('Source', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'post',
                'options' => $post_types,
            ]
        );

        Core::renderUpgradeProNotice($this, Controls_Manager::RAW_HTML, 'grid', 'query_source', ['pro-rl']);

        if (!king_addons_freemius()->can_use_premium_code__premium_only()) {
            $this->add_control(
                'query_source_cpt_pro_notice',
                [
                    'raw' => 'This option is available<br> in the <strong><a href="https://kingaddons.com/pricing/?utm_source=kng-module-grid-upgrade-pro&utm_medium=plugin&utm_campaign=kng" target="_blank">Pro version</a></strong>',
                    'type' => Controls_Manager::RAW_HTML,
                    'content_classes' => 'king-addons-pro-notice',
                    'condition' => [
                        'query_source!' => ['post', 'page', 'related', 'current', 'pro-rl'],
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
                    'query_source!' => ['current', 'related'],
                ],
            ]
        );

        $this->add_control_order_posts();

        $this->add_control_order_posts_by_acf([]);

        Core::renderUpgradeProNotice($this, Controls_Manager::RAW_HTML, 'grid', 'order_posts', ['pro-tl', 'pro-mf', 'pro-d', 'pro-ar', 'pro-cc']);

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
                    'query_randomize!' => 'rand',
                ]
            ]
        );

        $this->add_control(
            'query_tax_selection',
            [
                'label' => esc_html__('Select Taxonomy', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'category',
                'options' => $post_taxonomies,
                'condition' => [
                    'query_source' => 'related',
                ],
            ]
        );

        if (!king_addons_freemius()->can_use_premium_code__premium_only()) {
            $this->add_control(
                'query_tax_selection_pro_notice',
                [
                    'raw' => 'This option is available<br> in the <strong><a href="https://kingaddons.com/pricing/?utm_source=kng-module-grid-upgrade-pro&utm_medium=plugin&utm_campaign=kng" target="_blank">Pro version</a></strong>',
                    'type' => Controls_Manager::RAW_HTML,
                    'content_classes' => 'king-addons-pro-notice',
                    'condition' => [
                        'query_tax_selection!' => ['category', 'post_tag'],
                    ]
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
                    'query_source!' => ['current', 'related'],
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
                        'query_source' => $post_type,
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
                        'query_source' => $slug,
                        'query_source!' => ['current', 'related'],
                        'query_selection' => 'dynamic',
                    ],
                ]
            );
        }


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
                        'query_source' => $slug,
                        'query_selection' => 'manual',
                    ],
                    'separator' => 'before',
                ]
            );
        }

        $this->add_control(
            'query_posts_per_page',
            [
                'label' => esc_html__('Items Per Page', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'default' => 9,
                'min' => 0,
                'condition' => (king_addons_freemius()->can_use_premium_code__premium_only() ? ['query_source!' => 'current', 'layout_select!' => 'slider',] : ['query_source!' => 'current']),
            ]
        );

        if (king_addons_freemius()->can_use_premium_code__premium_only()) {
            $this->add_control_query_slides_to_show();
        } else {
            $this->add_control(
                'limit_slides_to_show_pro_notice',
                [
                    'type' => Controls_Manager::RAW_HTML,
                    'raw' => 'More than <strong>4 Slides</strong> are available<br>in the <strong><a href="https://kingaddons.com/pricing/?utm_source=kng-module-grid-settings-upgrade-pro&utm_medium=plugin&utm_campaign=kng" target="_blank">Pro version</a></strong>',

                    'content_classes' => 'king-addons-pro-notice',
                    'condition' => [
                        'layout_select' => 'slider',
                    ]
                ]
            );
        }

        $this->add_control(
            'query_offset',
            [
                'label' => esc_html__('Offset', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'default' => 0,
                'min' => 0,
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
                    'query_selection' => 'dynamic',
                    'query_source!' => 'related',
                ]
            ]
        );

        $this->add_control_display_scheduled_posts();

        // TODO: Under development feature.
//        $this->add_control_query_randomize();

        $this->add_control(
            'query_exclude_no_images',
            [
                'label' => esc_html__('Exclude Items without Thumbnail', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'label_block' => false
            ]
        );

        /** @noinspection HtmlUnknownTarget */
        $this->add_control(
            'current_query_notice',
            [
                'type' => Controls_Manager::RAW_HTML,
                'raw' => sprintf(__('To set Posts per Page for all Blog <strong>Archive Pages</strong>, navigate to <strong><a href="%s" target="_blank">Settings > Reading</a></strong>.', 'king-addons'), admin_url('options-reading.php')),
                'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
                'condition' => [
                    'query_source' => 'current',
                ],
            ]
        );


        $this->add_control(
            'element_select_filter',
            [
                'type' => Controls_Manager::HIDDEN,
                'default' => $this->get_related_taxonomies(),
            ]
        );

        $this->end_controls_section();


        $this->start_controls_section(
            'section_grid_layout',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Layout', 'king-addons'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control_layout_select();


        Core::renderUpgradeProNotice($this, Controls_Manager::RAW_HTML, 'grid', 'layout_select', ['pro-ms']);

        $this->add_control(
            'stick_last_element_to_bottom',
            [
                'label' => esc_html__('Last Element to Bottom', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => '',
                'render_type' => 'template',

                'condition' => [
                    'layout_select' => 'fitRows',
                ]
            ]
        );

        $this->add_control(
            'last_element_position',
            [
                'label' => esc_html__('Last Element Position', 'king-addons'),
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
                'selectors_dictionary' => [
                    'left' => 'left: 0; right: auto;',
                    'center' => 'left: 50%; transform: translateX(-50%);',
                    'right' => 'left: auto; right: 0;'
                ],
                'selectors' => [
                    '{{WRAPPER}}.king-addons-grid-last-element-yes .king-addons-grid-item-below-content>div:last-child' => '{{VALUE}}',
                ],
                'render_type' => 'template',
                'separator' => 'after'
            ]
        );

        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name' => 'layout_image_crop',
                'default' => 'full',
            ]
        );

        $this->add_control_layout_columns();

        if (!king_addons_freemius()->can_use_premium_code__premium_only()) {
            $this->add_control(
                'grid_columns_pro_notice',
                [
                    'type' => Controls_Manager::RAW_HTML,
                    'raw' => 'Grid Columns option is fully supported<br> in the <strong><a href="https://kingaddons.com/pricing/?utm_source=kng-module-grid-settings-upgrade-pro&utm_medium=plugin&utm_campaign=kng" target="_blank">Pro version</a></strong>',

                    'content_classes' => 'king-addons-pro-notice',
                ]
            );
        }


        $this->add_control(
            'layout_list_media_section',
            [
                'label' => esc_html__('Media', 'king-addons'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'layout_select' => 'list',
                ],
            ]
        );

        $this->add_control(
            'layout_list_align',
            [
                'label' => esc_html__('Alignment', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'left',
                'options' => [
                    'left' => esc_html__('Left', 'king-addons'),
                    'right' => esc_html__('Right', 'king-addons'),
                    'zigzag' => esc_html__('ZigZag', 'king-addons'),
                ],
                'condition' => [
                    'layout_select' => 'list',
                ],
            ]
        );

        $this->add_control(
            'layout_list_media_width',
            [
                'label' => esc_html__('Width', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 30,
                ],
                'range' => [
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'condition' => [
                    'layout_select' => 'list',
                ]
            ]
        );

        $this->add_control(
            'layout_list_media_distance',
            [
                'label' => esc_html__('Distance', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 20,
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'condition' => [
                    'layout_select' => 'list',
                ]
            ]
        );

        $this->add_responsive_control(
            'layout_gutter_hr',
            [
                'label' => esc_html__('Horizontal Gutter', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 150,
                    ],
                ],
                'default' => [
                    'size' => 15,
                ],
                'widescreen_default' => [
                    'size' => 15,
                ],
                'laptop_default' => [
                    'size' => 15,
                ],
                'tablet_extra_default' => [
                    'size' => 15,
                ],
                'tablet_default' => [
                    'size' => 15,
                ],
                'mobile_extra_default' => [
                    'size' => 15,
                ],
                'mobile_default' => [
                    'size' => 15,
                ],
                'condition' => [
                    'layout_select' => ['fitRows', 'masonry', 'list'],
                ],
                'separator' => 'before'
            ]
        );

        $this->add_responsive_control(
            'layout_gutter_vr',
            [
                'label' => esc_html__('Vertical Gutter', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 150,
                    ],
                ],
                'default' => [
                    'size' => 15,
                ],
                'condition' => [
                    'layout_select' => ['fitRows', 'masonry', 'list'],
                ]
            ]
        );

        $this->add_responsive_control(
            'layout_filters',
            [
                'label' => esc_html__('Show Filters', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'selectors_dictionary' => [
                    '' => 'none',
                    'yes' => 'block'
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-filters' => 'display:{{VALUE}};',
                ],
                'render_type' => 'template',
                'separator' => 'before',
                'condition' => [
                    'layout_select!' => 'slider',
                ]
            ]
        );

        $this->add_control(
            'layout_pagination',
            [
                'label' => esc_html__('Show Pagination', 'king-addons'),
                'description' => esc_html__('Please note that Pagination doesn\'t work in editor.', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'return_value' => 'yes',
                'render_type' => 'template',
                'condition' => [
                    'layout_select!' => 'slider',
                ]
            ]
        );

        $this->add_control(
            'filters_experiment_hr_before',
            [
                'type' => Controls_Manager::DIVIDER,
            ]
        );

        // TODO: Implement the Randomize Query feature.
        $this->add_control(
            'filters_experiment',
            [
                'label' => esc_html__('Filters & Load More Dynamic Loading (beta feature)', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'no',
                'label_block' => false,
                'return_value' => 'yes',
                'render_type' => 'template',
                'separator' => 'after',
// TODO: Later

//                'conditions' => [
//                    'relation' => 'and',
//                    'terms' => [
//                        [
//                            'name' => 'layout_filters',
//                            'operator' => '!=',
//                            'value' => '',
//                        ],
//                        [
//                            'relation' => 'or',
//                            'terms' => [
//                                [
//                                    'relation' => 'and',
//                                    'terms' => [
//                                        [
//                                            'name' => 'layout_pagination',
//                                            'operator' => '!=',
//                                            'value' => '',
//                                        ],
//                                        [
//                                            'name' => 'pagination_type',
//                                            'operator' => 'in',
//                                            'value' => ['load-more', 'infinite'],
//                                        ],
//                                    ]
//                                ],
//                                [
//                                    'name' => 'layout_pagination',
//                                    'operator' => '==',
//                                    'value' => '',
//                                ]
//                            ],
//                        ],
//                    ]
//                ]
            ]
        );

        $this->add_control_open_links_in_new_tab();

        $this->add_control_grid_lazy_loading();

        $this->add_control_layout_animation();

        Core::renderUpgradeProNotice($this, Controls_Manager::RAW_HTML, 'grid', 'layout_animation', ['pro-fd', 'pro-fs']);

        $this->add_control(
            'layout_animation_duration',
            [
                'label' => esc_html__('Animation Duration', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'default' => 0.3,
                'min' => 0,
                'max' => 5,
                'step' => 0.1,
                'condition' => [
                    'layout_animation!' => 'default',
                    'layout_select!' => 'slider',
                ],
            ]
        );

        $this->add_control(
            'layout_animation_delay',
            [
                'label' => esc_html__('Animation Delay', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'default' => 0.1,
                'min' => 0,
                'max' => 5,
                'step' => 0.05,
                'condition' => [
                    'layout_animation!' => 'default',
                    'layout_select!' => 'slider',
                ],
            ]
        );

        $this->add_control_layout_slider_amount();

        $this->add_control(
            'layout_slides_to_scroll',
            [
                'label' => esc_html__('Slides to Scroll', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 10,
                'default' => 1,
                'render_type' => 'template',
                'frontend_available' => true,
                'separator' => 'before',
                'condition' => [
                    'layout_select' => 'slider',
                ],
            ]
        );

        $this->add_responsive_control(
            'layout_slider_gutter',
            [
                'type' => Controls_Manager::SLIDER,
                'label' => esc_html__('Gutter', 'king-addons'),
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ]
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 10,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid .slick-slide' => 'margin-left: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .king-addons-grid .slick-list' => 'margin-left: -{{SIZE}}{{UNIT}};',
                ],
                'render_type' => 'template',
                'condition' => [
                    'layout_slider_amount!' => '1',
                    'layout_select' => 'slider',
                ],
            ]
        );

        $this->add_responsive_control(
            'layout_slider_nav',
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
                    '{{WRAPPER}} .king-addons-grid-slider-arrow' => 'display:{{VALUE}} !important;',
                ],
                'separator' => 'before',
                'condition' => [
                    'layout_select' => 'slider',
                ]
            ]
        );

        $this->add_control_layout_slider_nav_hover();


        $this->add_control(
            'layout_slider_nav_icon',
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
                'separator' => 'after',
                'condition' => [
                    'layout_slider_nav' => 'yes',
                    'layout_select' => 'slider',
                ],
            ]
        );

        $this->add_responsive_control(
            'layout_slider_dots',
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
                    '{{WRAPPER}} .king-addons-grid-slider-dots' => 'display:{{VALUE}};',
                ],
                'render_type' => 'template',
                'condition' => [
                    'layout_select' => 'slider',
                ],
            ]
        );

        $this->add_control_layout_slider_dots_position();


        Core::renderUpgradeProNotice($this, Controls_Manager::RAW_HTML, 'grid', 'layout_slider_dots_position', ['pro-vr']);

        $this->add_control_layout_slider_autoplay();

        $this->add_controls_group_layout_slider_autoplay();

        $this->add_control(
            'layout_slider_loop',
            [
                'label' => esc_html__('Infinite Loop', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'separator' => 'before',
                'frontend_available' => true,
                'condition' => [
                    'layout_select' => 'slider',
                ],
            ]
        );

        $this->add_control(
            'layout_slider_effect',
            [
                'type' => Controls_Manager::SELECT,
                'label' => esc_html__('Effect', 'king-addons'),
                'default' => 'slide',
                'options' => [
                    'slide' => esc_html__('Slide', 'king-addons'),
                    'fade' => esc_html__('Fade', 'king-addons'),
                ],
                'condition' => [
                    'layout_slider_amount' => 1,
                    'layout_select' => 'slider',
                ],
            ]
        );

        $this->add_control(
            'layout_slider_effect_duration',
            [
                'label' => esc_html__('Effect Duration', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'default' => 0.7,
                'min' => 0,
                'max' => 5,
                'step' => 0.1,
                'condition' => [
                    'layout_slider_amount' => 1,
                    'layout_select' => 'slider',
                ],
            ]
        );

        $this->end_controls_section();


        $this->start_controls_section(
            'section_grid_elements',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Elements', 'king-addons'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new Repeater();

        $element_select = $this->add_option_element_select();

        $repeater->add_control(
            'element_select',
            [
                'label' => esc_html__('Select Element', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'title',
                'options' => array_merge($element_select, $post_taxonomies),
                'separator' => 'after'
            ]
        );

        $repeater->add_control(
            'show_last_update_date',
            [
                'label' => esc_html__('Show Last Update Date', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'separator' => 'after',
                'condition' => [
                    'element_select' => 'date',
                ]
            ]
        );

        Core::renderUpgradeProNotice($repeater, Controls_Manager::RAW_HTML, 'grid', 'element_select', ['pro-lk', 'pro-shr']);
        Core::renderUpgradeProNotice($repeater, Controls_Manager::RAW_HTML, 'grid', 'element_select', ['pro-cf']);

        $repeater->add_control(
            'element_location',
            [
                'label' => esc_html__('Location', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'below',
                'options' => [
                    'above' => esc_html__('Above Media', 'king-addons'),
                    'over' => esc_html__('Over Media', 'king-addons'),
                    'below' => esc_html__('Below Media', 'king-addons'),
                ]
            ]
        );

        $repeater->add_control(
            'element_display',
            [
                'label' => esc_html__('Display', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'block',
                'options' => [
                    'inline' => esc_html__('Inline', 'king-addons'),
                    'block' => esc_html__('Separate Line', 'king-addons'),
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
                    'raw' => 'Vertical Align option is available<br> in the <strong><a href="https://kingaddons.com/pricing/?utm_source=kng-module-grid-settings-upgrade-pro&utm_medium=plugin&utm_campaign=kng" target="_blank">Pro version</a></strong>',
                    'type' => Controls_Manager::RAW_HTML,
                    'content_classes' => 'king-addons-pro-notice',
                    'condition' => [
                        'element_location' => 'over',
                    ],
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
                ],
                'condition' => [
                    'element_location' => 'over',
                ],
            ]
        );

        $repeater->add_control(
            'element_align_hr',
            [
                'label' => esc_html__('Horizontal Align', 'king-addons'),
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
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}' => 'text-align: {{VALUE}}',
                ],
                'render_type' => 'template',
                'separator' => 'after'
            ]
        );

        $repeater->add_control(
            'element_title_tag',
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
                'default' => 'h2',
                'condition' => [
                    'element_select' => 'title',
                ]
            ]
        );

        $repeater->add_control(
            'element_dropcap',
            [
                'label' => esc_html__('Enable Drop Cap', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'condition' => [
                    'element_select' => ['content', 'excerpt'],
                ]
            ]
        );

        $repeater->add_control(
            'element_trim_text_by',
            [
                'label' => esc_html__('Trim Text By', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'word_count',
                'options' => $this->add_repeater_args_element_trim_text_by(),
                'separator' => 'after',
                'condition' => [
                    'element_select' => ['title', 'excerpt'],
                ]
            ]
        );

        $repeater->add_control(
            'element_word_count',
            [
                'label' => esc_html__('Word Count', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'default' => 20,
                'min' => 1,
                'condition' => [
                    'element_select' => ['title', 'excerpt'],
                    'element_trim_text_by' => 'word_count'
                ]
            ]
        );

        $repeater->add_control(
            'element_letter_count',
            [
                'label' => esc_html__('Letter Count', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'default' => 40,
                'min' => 1,
                'condition' => [
                    'element_select' => ['title', 'excerpt'],
                    'element_trim_text_by' => 'letter_count'
                ]
            ]
        );

        $repeater->add_control(
            'element_show_avatar',
            [
                'label' => esc_html__('Show Avatar', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'condition' => [
                    'element_select' => ['author']
                ]
            ]
        );

        $repeater->add_control(
            'element_avatar_size',
            [
                'label' => esc_html__('Avatar Size', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'default' => 16,
                'min' => 8,
                'condition' => [
                    'element_select' => ['author'],
                    'element_show_avatar' => 'yes'
                ],
                'separator' => 'after'
            ]
        );

        $repeater->add_control(
            'element_read_more_text',
            [
                'label' => esc_html__('Read More Text', 'king-addons'),
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => 'Read More',
                'condition' => [
                    'element_select' => ['read-more'],
                ],
                'separator' => 'after'
            ]
        );

        $repeater->add_control(
            'element_tax_sep',
            [
                'label' => esc_html__('Separator', 'king-addons'),
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => ', ',
                'condition' => [
                    'element_select!' => [
                        'title',
                        'content',
                        'excerpt',
                        'date',
                        'time',
                        'author',
                        'comments',
                        'read-more',
                        'likes',
                        'sharing',
                        'lightbox',
                        'custom-field',
                        'separator',
                        'post_format',
                    ],
                ],
                'separator' => 'after'
            ]
        );

        $repeater->add_control(
            'element_tax_style',
            [
                'label' => esc_html__('Select Styling', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'king-addons-grid-tax-style-1',
                'options' => [
                    'king-addons-grid-tax-style-1' => esc_html__('Taxonomy Style 1', 'king-addons'),
                    'king-addons-grid-tax-style-2' => esc_html__('Taxonomy Style 2', 'king-addons'),
                ],
                'condition' => [
                    'element_select!' => [
                        'title',
                        'content',
                        'excerpt',
                        'date',
                        'time',
                        'author',
                        'comments',
                        'read-more',
                        'likes',
                        'sharing',
                        'lightbox',
                        'custom-field',
                        'separator',
                    ],
                ],
                'separator' => 'after'
            ]
        );

        $repeater->add_control(
            'element_comments_text_1',
            [
                'label' => esc_html__('No Comments', 'king-addons'),
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => 'No Comments',
                'condition' => [
                    'element_select' => ['comments'],
                ]
            ]
        );

        $repeater->add_control(
            'element_comments_text_2',
            [
                'label' => esc_html__('One Comment', 'king-addons'),
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => 'Comment',
                'condition' => [
                    'element_select' => ['comments'],
                ]
            ]
        );

        $repeater->add_control(
            'element_comments_text_3',
            [
                'label' => esc_html__('Multiple Comments', 'king-addons'),
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => 'Comments',
                'condition' => [
                    'element_select' => ['comments'],
                ],
                'separator' => 'after'
            ]
        );

        $repeater->add_control('element_like_icon', $this->add_repeater_args_element_like_icon());

        $repeater->add_control('element_like_show_count', $this->add_repeater_args_element_like_show_count());

        $repeater->add_control('element_like_text', $this->add_repeater_args_element_like_text());

        $repeater->add_control('element_sharing_icon_1', $this->add_repeater_args_element_sharing_icon_1());

        $repeater->add_control('element_sharing_icon_2', $this->add_repeater_args_element_sharing_icon_2());

        $repeater->add_control('element_sharing_icon_3', $this->add_repeater_args_element_sharing_icon_3());

        $repeater->add_control('element_sharing_icon_4', $this->add_repeater_args_element_sharing_icon_4());

        $repeater->add_control('element_sharing_icon_5', $this->add_repeater_args_element_sharing_icon_5());

        $repeater->add_control('element_sharing_icon_6', $this->add_repeater_args_element_sharing_icon_6());

        $repeater->add_control('element_sharing_trigger', $this->add_repeater_args_element_sharing_trigger());

        $repeater->add_control('element_sharing_trigger_icon', $this->add_repeater_args_element_sharing_trigger_icon());

        $repeater->add_control('element_sharing_trigger_action', $this->add_repeater_args_element_sharing_trigger_action());

        $repeater->add_control('element_sharing_trigger_direction', $this->add_repeater_args_element_sharing_trigger_direction());

        $repeater->add_control('element_sharing_tooltip', $this->add_repeater_args_element_sharing_tooltip());

        $repeater->add_control(
            'element_lightbox_pfa_select',
            [
                'label' => esc_html__('Post Format Audio', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'default',
                'options' => [
                    'default' => esc_html__('Default', 'king-addons'),
                    'meta' => esc_html__('Meta Value', 'king-addons'),
                ],
                'condition' => [
                    'element_select' => 'lightbox',
                ],
            ]
        );

        $repeater->add_control(
            'element_lightbox_pfa_meta',
            [
                'label' => esc_html__('Audio Meta Value', 'king-addons'),
                'type' => 'king-addons-ajax-select2',
                'label_block' => true,
                'default' => 'default',
                'options' => 'ajaxselect2/getCustomMetaKeys',
                'condition' => [
                    'element_select' => 'lightbox',
                    'element_lightbox_pfa_select' => 'meta',
                ],
            ]
        );

        $repeater->add_control(
            'element_lightbox_pfv_select',
            [
                'label' => esc_html__('Post Format Video', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'default',
                'options' => [
                    'default' => esc_html__('Default', 'king-addons'),
                    'meta' => esc_html__('Meta Value', 'king-addons'),
                ],
                'condition' => [
                    'element_select' => 'lightbox',
                ],
            ]
        );

        $repeater->add_control(
            'element_lightbox_pfv_meta',
            [
                'label' => esc_html__('Video Meta Value', 'king-addons'),
                'type' => 'king-addons-ajax-select2',
                'label_block' => true,
                'default' => 'default',
                'options' => 'ajaxselect2/getCustomMetaKeys',
                'condition' => [
                    'element_select' => 'lightbox',
                    'element_lightbox_pfv_select' => 'meta',
                ],
            ]
        );

        $repeater->add_control(
            'element_lightbox_overlay',
            [
                'label' => esc_html__('Media Overlay', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'separator' => 'after',
                'condition' => [
                    'element_select' => ['lightbox'],
                ],
            ]
        );

        $repeater->add_control('element_custom_field', $this->add_repeater_args_element_custom_field());

        $repeater->add_control('element_custom_field_img_ID', $this->add_repeater_args_element_custom_field_img_ID());

        $repeater->add_control('element_custom_field_btn_link', $this->add_repeater_args_element_custom_field_btn_link());

        $repeater->add_control('element_custom_field_new_tab', $this->add_repeater_args_element_custom_field_new_tab());

        $repeater->add_control('custom_field_wrapper_html_divider1', $this->add_repeater_args_custom_field_wrapper_html_divider1());

        $repeater->add_control('element_custom_field_wrapper', $this->add_repeater_args_element_custom_field_wrapper());

        $repeater->add_control('element_custom_field_wrapper_html', $this->add_repeater_args_element_custom_field_wrapper_html());

        $repeater->add_control('custom_field_wrapper_html_divider2', $this->add_repeater_args_custom_field_wrapper_html_divider2());

        $repeater->add_control('element_cf_tag', $this->add_repeater_args_element_cf_tag());

        $repeater->add_control('element_custom_field_style', $this->add_repeater_args_element_custom_field_style());

        $repeater->add_control(
            'element_separator_style',
            [
                'label' => esc_html__('Select Styling', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'king-addons-grid-sep-style-1',
                'options' => [
                    'king-addons-grid-sep-style-1' => esc_html__('Separator Style 1', 'king-addons'),
                    'king-addons-grid-sep-style-2' => esc_html__('Separator Style 2', 'king-addons'),
                ],
                'condition' => [
                    'element_select' => 'separator',
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
                        'content',
                        'excerpt',
                        'read-more',
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
                        'content',
                        'excerpt',
                        'read-more',
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
                        'content',
                        'excerpt',
                        'separator',
                        'likes',
                        'sharing',
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
                        'content',
                        'excerpt',
                        'separator',
                        'likes',
                        'sharing',
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
                    'element_location' => 'over'
                ],
            ]
        );

        $repeater->add_control(
            'element_animation',
            [
                'label' => esc_html__('Select Animation', 'king-addons'),
                'type' => 'king-addons-animations',
                'default' => 'none',
                'condition' => [
                    'element_location' => 'over'
                ],
            ]
        );


        Core::renderUpgradeProNotice($repeater, Controls_Manager::RAW_HTML, 'grid', 'element_animation', ['pro-slrt', 'pro-slxrt', 'pro-slbt', 'pro-sllt', 'pro-sltp', 'pro-slxlt', 'pro-sktp', 'pro-skrt', 'pro-skbt', 'pro-sklt', 'pro-scup', 'pro-scdn', 'pro-rllt', 'pro-rlrt']);

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
                    'element_animation!' => 'none',
                    'element_location' => 'over',
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
                    'element_animation!' => 'none',
                    'element_location' => 'over'
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
                    'element_animation!' => 'none',
                    'element_location' => 'over'
                ],
            ]
        );


        Core::renderUpgradeProNotice($repeater, Controls_Manager::RAW_HTML, 'grid', 'element_animation_timing', ['pro-eio', 'pro-eiqd', 'pro-eicb', 'pro-eiqrt', 'pro-eiqnt', 'pro-eisn', 'pro-eiex', 'pro-eicr', 'pro-eibk', 'pro-eoqd', 'pro-eocb', 'pro-eoqrt', 'pro-eoqnt', 'pro-eosn', 'pro-eoex', 'pro-eocr', 'pro-eobk', 'pro-eioqd', 'pro-eiocb', 'pro-eioqrt', 'pro-eioqnt', 'pro-eiosn', 'pro-eioex', 'pro-eiocr', 'pro-eiobk',]);

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
                    'element_animation!' => 'none',
                    'element_location' => 'over'
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
                    'element_animation!' => 'none',
                    'element_location' => 'over'
                ],
            ]
        );

        $repeater->add_control(
            'element_animation_disable_mobile',
            [
                'label' => esc_html__('Disable on Mobile/Tablet', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'condition' => [
                    'element_animation!' => 'none',
                    'element_location' => 'over'
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
            'grid_elements',
            [
                'label' => esc_html__('Grid Elements', 'king-addons'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'element_select' => 'title',
                    ],
                    [
                        'element_select' => 'date',
                        'element_display' => 'inline',
                        'element_extra_text_pos' => 'after',
                        'element_extra_text' => '/',
                    ],
                    [
                        'element_select' => 'comments',
                        'element_display' => 'inline',
                    ],
                    [
                        'element_select' => 'excerpt',
                    ],
                    [
                        'element_select' => 'read-more',
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
                    '{{WRAPPER}} .king-addons-grid-media-hover-bg' => 'width: {{SIZE}}{{UNIT}};top:calc((100% - {{overlay_height.SIZE}}{{overlay_height.UNIT}})/2);left:calc((100% - {{SIZE}}{{UNIT}})/2);',
                    '{{WRAPPER}} .king-addons-grid-media-hover-bg[class*="-top"]' => 'top:calc((100% - {{overlay_height.SIZE}}{{overlay_height.UNIT}})/2);left:calc((100% - {{SIZE}}{{UNIT}})/2);',
                    '{{WRAPPER}} .king-addons-grid-media-hover-bg[class*="-bottom"]' => 'bottom:calc((100% - {{overlay_height.SIZE}}{{overlay_height.UNIT}})/2);left:calc((100% - {{SIZE}}{{UNIT}})/2);',
                    '{{WRAPPER}} .king-addons-grid-media-hover-bg[class*="-right"]' => 'top:calc((100% - {{overlay_height.SIZE}}{{overlay_height.UNIT}})/2);right:calc((100% - {{SIZE}}{{UNIT}})/2);',
                    '{{WRAPPER}} .king-addons-grid-media-hover-bg[class*="-left"]' => 'top:calc((100% - {{overlay_height.SIZE}}{{overlay_height.UNIT}})/2);left:calc((100% - {{SIZE}}{{UNIT}})/2);',
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
                    '{{WRAPPER}} .king-addons-grid-media-hover-bg' => 'height: {{SIZE}}{{UNIT}};top:calc((100% - {{SIZE}}{{UNIT}})/2);left:calc((100% - {{overlay_width.SIZE}}{{overlay_width.UNIT}})/2);',
                    '{{WRAPPER}} .king-addons-grid-media-hover-bg[class*="-top"]' => 'top:calc((100% - {{SIZE}}{{UNIT}})/2);left:calc((100% - {{overlay_width.SIZE}}{{overlay_width.UNIT}})/2);',
                    '{{WRAPPER}} .king-addons-grid-media-hover-bg[class*="-bottom"]' => 'bottom:calc((100% - {{SIZE}}{{UNIT}})/2);left:calc((100% - {{overlay_width.SIZE}}{{overlay_width.UNIT}})/2);',
                    '{{WRAPPER}} .king-addons-grid-media-hover-bg[class*="-right"]' => 'top:calc((100% - {{SIZE}}{{UNIT}})/2);right:calc((100% - {{overlay_width.SIZE}}{{overlay_width.UNIT}})/2);',
                    '{{WRAPPER}} .king-addons-grid-media-hover-bg[class*="-left"]' => 'top:calc((100% - {{SIZE}}{{UNIT}})/2);left:calc((100% - {{overlay_width.SIZE}}{{overlay_width.UNIT}})/2);',
                ],
                'separator' => 'after',
            ]
        );

        $this->add_control(
            'overlay_post_link',
            [
                'label' => esc_html__('Link to Single Page', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'return_value' => 'yes',
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


        Core::renderUpgradeProNotice($this, Controls_Manager::RAW_HTML, 'grid', 'overlay_animation', ['pro-slrt', 'pro-slxrt', 'pro-slbt', 'pro-sllt', 'pro-sltp', 'pro-slxlt', 'pro-sktp', 'pro-skrt', 'pro-skbt', 'pro-sklt', 'pro-scup', 'pro-scdn', 'pro-rllt', 'pro-rlrt']);

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
                    '{{WRAPPER}} .king-addons-grid-media-hover-bg' => 'transition-duration: {{VALUE}}s;'
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
                    '{{WRAPPER}} .king-addons-animation-wrap:hover .king-addons-grid-media-hover-bg' => 'transition-delay: {{VALUE}}s;'
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


        Core::renderUpgradeProNotice($this, Controls_Manager::RAW_HTML, 'grid', 'overlay_animation_timing', Core::getAnimationTimingsConditionsPro());

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

        $this->add_control_overlay_animation_divider();

        $this->add_control_overlay_image();

        $this->add_control_overlay_image_width();

        $this->end_controls_section();


        $this->start_controls_section(
            'section_image_effects',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Image Effects', 'king-addons'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control_secondary_img_on_hover();

        $this->add_control_image_effects();

        Core::renderUpgradeProNotice($this, Controls_Manager::RAW_HTML, 'grid', 'image_effects', ['pro-zi', 'pro-zo', 'pro-go', 'pro-bo']);

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
                    '{{WRAPPER}} .king-addons-grid-media-wrap img' => 'transition-duration: {{VALUE}}s;'
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
                    '{{WRAPPER}} .king-addons-grid-media-wrap:hover img' => 'transition-delay: {{VALUE}}s;'
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


        Core::renderUpgradeProNotice($this, Controls_Manager::RAW_HTML, 'grid', 'image_effects_animation_timing', Core::getAnimationTimingsConditionsPro());

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

        // TODO: Check for issues.
//        $this->add_section_general_lightbox();

        $this->start_controls_section(
            'section_grid_filters',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Filters', 'king-addons'),
                'tab' => Controls_Manager::TAB_CONTENT,
                'condition' => [
                    'layout_select!' => 'slider',
                    'layout_filters' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'filters_select',
            [
                'label' => esc_html__('Select Taxonomy', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => $post_taxonomies,
                'default' => 'category',
            ]
        );

        if (!king_addons_freemius()->can_use_premium_code__premium_only()) {
            $this->add_control(
                'filters_select_pro_notice',
                [
                    'raw' => 'This option is available<br> in the <strong><a href="https://kingaddons.com/pricing/?utm_source=kng-module-grid-upgrade-pro&utm_medium=plugin&utm_campaign=kng" target="_blank">Pro version</a></strong>',
                    'type' => Controls_Manager::RAW_HTML,
                    'content_classes' => 'king-addons-pro-notice',
                    'condition' => [
                        'filters_select!' => ['category', 'post_tag'],
                    ]
                ]
            );
        }

        $this->add_control(
            'filters_linkable',
            [
                'label' => esc_html__('Set Linkable Filters', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'no',
                'return_value' => 'yes',
            ]
        );

        $this->add_control(
            'filters_hide_empty',
            [
                'label' => esc_html__('Hide Empty Filters', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'no',
                'return_value' => 'yes',
                'condition' => [
                    'filters_linkable!' => 'yes',
                ],
            ]
        );

        $this->add_control_filters_deeplinking();

        $this->add_control(
            'filters_all',
            [
                'label' => esc_html__('Show "All" Filter', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'return_value' => 'yes',
                'condition' => [
                    'filters_linkable!' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'filters_all_text',
            [
                'label' => esc_html__('"All" Filter Text', 'king-addons'),
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => 'All Posts',
                'condition' => [
                    'filters_all' => 'yes',
                    'filters_linkable!' => 'yes',
                ],
            ]
        );

        $this->add_control_filters_count();

        $this->add_control_filters_count_superscript();

        $this->add_control_filters_count_brackets();

        $this->add_control_filters_default_filter();

        $this->add_control_filters_icon();

        $this->add_control_filters_icon_align();

        $this->add_control(
            'filters_separator',
            [
                'label' => esc_html__('Separator', 'king-addons'),
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => '',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'filters_separator_align',
            [
                'label' => esc_html__('Separator Position', 'king-addons'),
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
                    'filters_separator!' => '',
                ],
            ]
        );

        $this->add_responsive_control(
            'filters_align',
            [
                'label' => esc_html__('Align', 'king-addons'),
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
                    '{{WRAPPER}} .king-addons-grid-filters' => 'text-align: {{VALUE}}',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control_filters_animation();


        Core::renderUpgradeProNotice($this, Controls_Manager::RAW_HTML, 'grid', 'filters_animation', ['pro-fd', 'pro-fs']);

        $this->add_control(
            'filters_animation_duration',
            [
                'label' => esc_html__('Animation Duration', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'default' => 0.3,
                'min' => 0,
                'max' => 5,
                'step' => 0.1,
                'condition' => [
                    'filters_animation!' => 'default',
                ],
            ]
        );

        $this->add_control(
            'filters_animation_delay',
            [
                'label' => esc_html__('Animation Delay', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'default' => 0.1,
                'min' => 0,
                'max' => 5,
                'step' => 0.05,
                'condition' => [
                    'filters_animation!' => 'default'
                ],
            ]
        );

        $this->end_controls_section();


        $this->start_controls_section(
            'section_grid_pagination',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Pagination', 'king-addons'),
                'tab' => Controls_Manager::TAB_CONTENT,
                'condition' => [
                    'layout_select!' => 'slider',
                    'layout_pagination' => 'yes',
                ],
            ]
        );

        $this->add_control_pagination_type();


        Core::renderUpgradeProNotice($this, Controls_Manager::RAW_HTML, 'grid', 'pagination_type', ['pro-is', 'pro-nb']);

        $this->add_control(
            'pagination_older_text',
            [
                'label' => esc_html__('Older Posts Text', 'king-addons'),
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => 'Older Posts',
                'condition' => [
                    'pagination_type' => 'default',
                ],
            ]
        );

        $this->add_control(
            'pagination_newer_text',
            [
                'label' => esc_html__('Newer Posts Text', 'king-addons'),
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => 'Newer Posts',
                'condition' => [
                    'pagination_type' => 'default',
                ]
            ]
        );

        $this->add_control(
            'pagination_on_icon',
            [
                'label' => esc_html__('Select Icon', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'fas fa-angle',
                'options' => [
                    'fas fa-angle' => esc_html__('Angle', 'king-addons'),
                    'fas fa-angle-double' => esc_html__('Angle Double', 'king-addons'),
                    'fas fa-arrow' => esc_html__('Arrow', 'king-addons'),
                    'fas fa-arrow-alt-circle' => esc_html__('Arrow Circle', 'king-addons'),
                    'far fa-arrow-alt-circle' => esc_html__('Arrow Circle Alt', 'king-addons'),
                    'fas fa-long-arrow-alt' => esc_html__('Long Arrow', 'king-addons'),
                    'fas fa-chevron' => esc_html__('Chevron', 'king-addons'),
                ],
                'condition' => [
                    'pagination_type' => 'default'
                ],
            ]
        );

        $this->add_control(
            'pagination_prev_next',
            [
                'label' => esc_html__('Previous & Next Buttons', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'return_value' => 'yes',
                'condition' => [
                    'pagination_type' => 'numbered',
                ],
            ]
        );

        $this->add_control(
            'pagination_prev_text',
            [
                'label' => esc_html__('Prev Page Text', 'king-addons'),
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => 'Previous Page',
                'condition' => [
                    'pagination_type' => 'numbered',
                    'pagination_prev_next' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'pagination_next_text',
            [
                'label' => esc_html__('Next Page Text', 'king-addons'),
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => 'Next Page',
                'condition' => [
                    'pagination_type' => 'numbered',
                    'pagination_prev_next' => 'yes',
                ]
            ]
        );

        $this->add_control(
            'pagination_pn_icon',
            [
                'label' => esc_html__('Select Icon', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'fas fa-angle',
                'options' => [
                    'fas fa-angle' => esc_html__('Angle', 'king-addons'),
                    'fas fa-angle-double' => esc_html__('Angle Double', 'king-addons'),
                    'fas fa-arrow' => esc_html__('Arrow', 'king-addons'),
                    'fas fa-arrow-alt-circle' => esc_html__('Arrow Circle', 'king-addons'),
                    'far fa-arrow-alt-circle' => esc_html__('Arrow Circle Alt', 'king-addons'),
                    'fas fa-long-arrow-alt' => esc_html__('Long Arrow', 'king-addons'),
                    'fas fa-chevron' => esc_html__('Chevron', 'king-addons'),
                ],
                'condition' => [
                    'pagination_type' => 'numbered',
                    'pagination_prev_next' => 'yes'
                ],
            ]
        );

        $this->add_control(
            'pagination_first_last',
            [
                'label' => esc_html__('First & Last Buttons', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'return_value' => 'yes',
                'condition' => [
                    'pagination_type' => 'numbered',
                ],
            ]
        );

        $this->add_control(
            'pagination_first_text',
            [
                'label' => esc_html__('First Page Text', 'king-addons'),
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => 'First Page',
                'condition' => [
                    'pagination_type' => 'numbered',
                    'pagination_first_last' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'pagination_last_text',
            [
                'label' => esc_html__('Last Page Text', 'king-addons'),
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => 'Last Page',
                'condition' => [
                    'pagination_type' => 'numbered',
                    'pagination_first_last' => 'yes',
                ]
            ]
        );

        $this->add_control(
            'pagination_fl_icon',
            [
                'label' => esc_html__('Select Icon', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'fas fa-angle-double',
                'options' => [
                    'fas fa-angle' => esc_html__('Angle', 'king-addons'),
                    'fas fa-angle-double' => esc_html__('Angle Double', 'king-addons'),
                    'fas fa-arrow' => esc_html__('Arrow', 'king-addons'),
                    'fas fa-arrow-alt-circle' => esc_html__('Arrow Circle', 'king-addons'),
                    'far fa-arrow-alt-circle' => esc_html__('Arrow Circle Alt', 'king-addons'),
                    'fas fa-long-arrow-alt' => esc_html__('Long Arrow', 'king-addons'),
                    'fas fa-chevron' => esc_html__('Chevron', 'king-addons'),
                ],
                'condition' => [
                    'pagination_type' => 'numbered',
                    'pagination_first_last' => 'yes'
                ],
            ]
        );

        $this->add_control(
            'pagination_disabled_arrows',
            [
                'label' => esc_html__('Show Disabled Buttons', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'return_value' => 'yes',
                'condition' => [
                    'pagination_type' => ['default', 'numbered'],
                ],
            ]
        );

        $this->add_control(
            'pagination_range',
            [
                'label' => esc_html__('Range', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'default' => 2,
                'min' => 1,
                'condition' => [
                    'pagination_type' => 'numbered',
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
                'default' => 'Load More',
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
                ]
            ]
        );

        $this->add_control(
            'pagination_align',
            [
                'label' => esc_html__('Alignment', 'king-addons'),
                'type' => Controls_Manager::CHOOSE,
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
                    'justify' => [
                        'title' => esc_html__('Justified', 'king-addons'),
                        'icon' => 'eicon-text-align-justify',
                    ],
                ],
                'default' => 'center',
                'prefix_class' => 'king-addons-grid-pagination-',
                'render_type' => 'template',
                'separator' => 'before',
                'condition' => [
                    'pagination_type!' => 'infinite-scroll',
                ]
            ]
        );

        $this->end_controls_section();

        Core::renderProFeaturesSection($this, '', Controls_Manager::RAW_HTML, 'grid', [
            'Grid Columns 1, 2, 3, 4, 5, 6',
            'Masonry Layout',
            'Posts Slider Columns (Carousel) 1, 2, 3, 4, 5, 6',
            'Post Slider Autoplay Options',
            'Infinite Scrolling Pagination',
            'Related Posts Query, Current Page Query, Random Posts Query',
            'Custom Post Types Support',
            'Custom Fields Support',
            'Trim Title & Excerpt By Letter Count',
            'Posts Order',
            'Display Scheduled Posts',
            'Post Slider Advanced Navigation Positioning',
            'Post Slider Advanced Pagination Positioning',
            'Grid Category Filter Deep Linking',
            'Grid Category Filter Icons Select',
            'Grid Category Filter Count',
            'Grid Item Even/Odd Background Color',
            'Lightbox Thumbnail Gallery, Lightbox Image Sharing Button',
            'Open Links in New Tab',
            'Lazy Loading',
            'Advanced Grid Loading Animations (Fade In & Slide Up)',
            'Advanced Grid Elements Positioning',
            'Title, Category, Read More Advanced Link Hover Animations',
            'Advanced Post Likes',
            'Advanced Post Sharing',
            'Unlimited Image Overlay Animations',
            'Image Overlay GIF Upload Option',
            'Image Overlay Blend Mode',
            'Image Effects: Zoom, Grayscale, Blur',
            'List Layout Zig-zag',
            'Secondary Featured Image'
        ]);

        $this->start_controls_section(
            'section_style_grid_item',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Grid Item', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            ]
        );

        $this->add_control(
            'grid_item_styles_selector',
            [
                'label' => esc_html__('Apply Styles To', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'inner' => esc_html__('Inner Elements', 'king-addons'),
                    'wrapper' => esc_html__('Wrapper', 'king-addons')
                ],
                'default' => 'inner',
                'prefix_class' => 'king-addons-item-styles-',
                'render_type' => 'template'
            ]
        );

        $this->add_control(
            'grid_item_bg_color',
            [
                'label' => esc_html__('Background Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-item-above-content' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .king-addons-grid-item-below-content' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .king-addons-grid[data-settings*="fitRows"] .king-addons-grid-item' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}}.king-addons-item-styles-wrapper .king-addons-grid-item' => 'background-color: {{VALUE}}'
                ],
            ]
        );

        $this->add_control_grid_item_even_bg_color();

        $this->add_control(
            'grid_item_border_color',
            [
                'label' => esc_html__('Border Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#E8E8E8',
                'selectors' => [
                    '{{WRAPPER}}.king-addons-item-styles-inner .king-addons-grid-item-above-content' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}}.king-addons-item-styles-inner .king-addons-grid-item-below-content' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}}.king-addons-item-styles-wrapper .king-addons-grid-item' => 'border-color: {{VALUE}}'
                ],
            ]
        );

        $this->add_control_grid_item_even_border_color();

        $this->add_control(
            'grid_item_border_type',
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
                    '{{WRAPPER}}.king-addons-item-styles-inner .king-addons-grid-item-above-content' => 'border-style: {{VALUE}};',
                    '{{WRAPPER}}.king-addons-item-styles-inner .king-addons-grid-item-below-content' => 'border-style: {{VALUE}};',
                    '{{WRAPPER}}.king-addons-item-styles-wrapper .king-addons-grid-item' => 'border-style: {{VALUE}}'
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'grid_item_border_width',
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
                    '{{WRAPPER}}.king-addons-item-styles-inner .king-addons-grid-item-above-content' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}}.king-addons-item-styles-inner .king-addons-grid-item-below-content' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}}.king-addons-item-styles-wrapper .king-addons-grid-item' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
                'condition' => [
                    'grid_item_border_type!' => 'none',
                ],
                'render_type' => 'template'
            ]
        );

        $this->add_responsive_control(
            'grid_item_padding',
            [
                'label' => esc_html__('Padding', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'default' => [
                    'top' => 10,
                    'right' => 0,
                    'bottom' => 0,
                    'left' => 0,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-item-above-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .king-addons-grid-item-below-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}}.king-addons-item-styles-wrapper .king-addons-grid-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
                'condition' => [
                    'grid_item_styles_selector' => 'inner'
                ],
                'render_type' => 'template'
            ]
        );


        $this->add_responsive_control(
            'grid_item_wrap_padding',
            [
                'label' => esc_html__('Padding', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'default' => [
                    'top' => 10,
                    'right' => 0,
                    'bottom' => 0,
                    'left' => 0,
                ],
                'selectors' => [
                    '{{WRAPPER}}.king-addons-item-styles-wrapper .king-addons-grid-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
                'condition' => [
                    'grid_item_styles_selector' => 'wrapper'
                ],
                'render_type' => 'template'
            ]
        );

        $this->add_control(
            'grid_item_radius',
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
                    '{{WRAPPER}} .king-addons-grid-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .king-addons-grid-item-above-content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .king-addons-grid-item-below-content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'grid_item_shadow',
                'selector' => '{{WRAPPER}} .king-addons-grid-item',
            ]
        );

        $this->end_controls_section();


        $this->start_controls_section(
            'section_style_grid_media',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Grid Media', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            ]
        );

        $this->add_control(
            'grid_media_border_color',
            [
                'label' => esc_html__('Border Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#E8E8E8',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-image-wrap' => 'border-color: {{VALUE}}',
                ]
            ]
        );

        $this->add_control(
            'grid_media_border_type',
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
                    '{{WRAPPER}} .king-addons-grid-image-wrap' => 'border-style: {{VALUE}};',
                ]
            ]
        );

        $this->add_control(
            'grid_media_border_width',
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
                    '{{WRAPPER}} .king-addons-grid-image-wrap' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'grid_media_border_type!' => 'none',
                ],
            ]
        );

        $this->add_control(
            'grid_media_radius',
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
                    '{{WRAPPER}} .king-addons-grid-image-wrap' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->end_controls_section();


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

        $this->add_control_overlay_border_color();

        $this->add_control_overlay_border_type();

        $this->add_control_overlay_border_width();

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
                    '{{WRAPPER}} .king-addons-grid-media-hover-bg' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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

        $this->start_controls_tabs('tabs_grid_title_style');

        $this->start_controls_tab(
            'tab_grid_title_normal',
            [
                'label' => esc_html__('Normal', 'king-addons'),
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#333333',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-item-title .inner-block a' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'title_bg_color',
            [
                'label' => esc_html__('Background Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-item-title .inner-block a' => 'background-color: {{VALUE}}',
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
                    '{{WRAPPER}} .king-addons-grid-item-title .inner-block a' => 'border-color: {{VALUE}}',
                ],
                'separator' => 'after',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_grid_title_hover',
            [
                'label' => esc_html__('Hover', 'king-addons'),
            ]
        );

        $this->add_control(
            'title_color_hr',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#54595f',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-item-title .inner-block a:hover' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'title_bg_color_hr',
            [
                'label' => esc_html__('Background Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-item-title .inner-block a:hover' => 'background-color: {{VALUE}}',
                ]
            ]
        );

        $this->add_control(
            'title_border_color_hr',
            [
                'label' => esc_html__('Border Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#E8E8E8',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-item-title .inner-block a:hover' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control_title_pointer_color_hr();

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_control_title_pointer();

        $this->add_control_title_pointer_height();

        $this->add_control_title_pointer_animation();

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
                    '{{WRAPPER}} .king-addons-grid-item-title .inner-block a' => 'transition-duration: {{VALUE}}s',
                    '{{WRAPPER}} .king-addons-grid-item-title .king-addons-pointer-item:before' => 'transition-duration: {{VALUE}}s',
                    '{{WRAPPER}} .king-addons-grid-item-title .king-addons-pointer-item:after' => 'transition-duration: {{VALUE}}s',
                ],
                'separator' => 'after',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .king-addons-grid-item-title a'
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
                    '{{WRAPPER}} .king-addons-grid-item-title .inner-block a' => 'border-style: {{VALUE}};',
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
                    '{{WRAPPER}} .king-addons-grid-item-title .inner-block a' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .king-addons-grid-item-title .inner-block a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .king-addons-grid-item-title .inner-block' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'render_type' => 'template',
            ]
        );

        $this->end_controls_section();


        $this->start_controls_section(
            'section_style_content',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Content', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            ]
        );

        $this->add_control(
            'content_color',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#6A6A6A',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-item-content .inner-block' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'content_dropcap_color',
            [
                'label' => esc_html__('DropCap Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#3a3a3a',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-item-content.king-addons-enable-dropcap p:first-child:first-letter' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'content_bg_color',
            [
                'label' => esc_html__('Background Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-item-content .inner-block' => 'background-color: {{VALUE}}',
                ]
            ]
        );

        $this->add_control(
            'content_border_color',
            [
                'label' => esc_html__('Border Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#E8E8E8',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-item-content .inner-block' => 'border-color: {{VALUE}}',
                ],
                'separator' => 'after',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'content_typography',
                'selector' => '{{WRAPPER}} .king-addons-grid-item-content'
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'content_dropcap_typography',
                'label' => esc_html__('Drop Cap Typography', 'king-addons'),
                'selector' => '{{WRAPPER}} .king-addons-grid-item-content.king-addons-enable-dropcap p:first-child:first-letter'
            ]
        );

        $this->add_responsive_control(
            'content_justify',
            [
                'label' => esc_html__('Justify Text', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => '',
                'widescreen_default' => '',
                'laptop_default' => '',
                'tablet_extra_default' => '',
                'tablet_default' => '',
                'mobile_extra_default' => '',
                'mobile_default' => '',
                'selectors_dictionary' => [
                    '' => '',
                    'yes' => 'text-align: justify;'
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-item-content .inner-block' => '{{VALUE}}',
                ],
                'render_type' => 'template',
            ]
        );

        $this->add_control(
            'content_width',
            [
                'label' => esc_html__('Content Width', 'king-addons'),
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
                    '{{WRAPPER}} .king-addons-grid-item-content .inner-block' => 'width: {{SIZE}}{{UNIT}};',
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
                'default' => 'none',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-item-content .inner-block' => 'border-style: {{VALUE}};',
                ],
                'render_type' => 'template',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'content_border_width',
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
                    '{{WRAPPER}} .king-addons-grid-item-content .inner-block' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'render_type' => 'template',
                'condition' => [
                    'content_border_type!' => 'none',
                ],
            ]
        );

        $this->add_responsive_control(
            'content_padding',
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
                    '{{WRAPPER}} .king-addons-grid-item-content .inner-block' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'render_type' => 'template',
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'content_margin',
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
                    '{{WRAPPER}} .king-addons-grid-item-content .inner-block' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();


        $this->start_controls_section(
            'section_style_excerpt',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Excerpt', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            ]
        );

        $this->add_control(
            'excerpt_color',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#6A6A6A',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-item-excerpt .inner-block' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'excerpt_dropcap_color',
            [
                'label' => esc_html__('DropCap Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#3a3a3a',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-item-excerpt.king-addons-enable-dropcap p:first-child:first-letter' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'excerpt_bg_color',
            [
                'label' => esc_html__('Background Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-item-excerpt .inner-block' => 'background-color: {{VALUE}}',
                ]
            ]
        );

        $this->add_control(
            'excerpt_border_color',
            [
                'label' => esc_html__('Border Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#E8E8E8',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-item-excerpt .inner-block' => 'border-color: {{VALUE}}',
                ],
                'separator' => 'after',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'excerpt_typography',
                'selector' => '{{WRAPPER}} .king-addons-grid-item-excerpt'
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'excerpt_dropcap_typography',
                'label' => esc_html__('Drop Cap Typography', 'king-addons'),
                'selector' => '{{WRAPPER}} .king-addons-grid-item-excerpt.king-addons-enable-dropcap p:first-child:first-letter'
            ]
        );

        $this->add_responsive_control(
            'excerpt_justify',
            [
                'label' => esc_html__('Justify Text', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => '',
                'widescreen_default' => '',
                'laptop_default' => '',
                'tablet_extra_default' => '',
                'tablet_default' => '',
                'mobile_extra_default' => '',
                'mobile_default' => '',
                'selectors_dictionary' => [
                    '' => '',
                    'yes' => 'text-align: justify;'
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-item-excerpt .inner-block' => '{{VALUE}}',
                ],
                'render_type' => 'template',
            ]
        );

        $this->add_control(
            'excerpt_width',
            [
                'label' => esc_html__('Excerpt Width', 'king-addons'),
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
                    '{{WRAPPER}} .king-addons-grid-item-excerpt .inner-block' => 'width: {{SIZE}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'excerpt_border_type',
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
                    '{{WRAPPER}} .king-addons-grid-item-excerpt .inner-block' => 'border-style: {{VALUE}};',
                ],
                'render_type' => 'template',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'excerpt_border_width',
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
                    '{{WRAPPER}} .king-addons-grid-item-excerpt .inner-block' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'render_type' => 'template',
                'condition' => [
                    'excerpt_border_type!' => 'none',
                ],
            ]
        );

        $this->add_responsive_control(
            'excerpt_padding',
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
                    '{{WRAPPER}} .king-addons-grid-item-excerpt .inner-block' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'render_type' => 'template',
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'excerpt_margin',
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
                    '{{WRAPPER}} .king-addons-grid-item-excerpt .inner-block' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();


        $this->start_controls_section(
            'section_style_date',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Date', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            ]
        );

        $this->add_control(
            'date_color',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#9C9C9C',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-item-date .inner-block' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'date_bg_color',
            [
                'label' => esc_html__('Background Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-item-date .inner-block > span' => 'background-color: {{VALUE}}',
                ]
            ]
        );

        $this->add_control(
            'date_border_color',
            [
                'label' => esc_html__('Border Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#E8E8E8',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-item-date .inner-block > span' => 'border-color: {{VALUE}}',
                ]
            ]
        );

        $this->add_control(
            'date_extra_text_color',
            [
                'label' => esc_html__('Extra Text Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#9C9C9C',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-item-date .inner-block span[class*="king-addons-grid-extra-text"]' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'date_extra_icon_color',
            [
                'label' => esc_html__('Extra Icon Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#9C9C9C',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-item-date .inner-block [class*="king-addons-grid-extra-icon"] i' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .king-addons-grid-item-date .inner-block [class*="king-addons-grid-extra-icon"] svg' => 'fill: {{VALUE}}'
                ],
                'separator' => 'after',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'date_typography',
                'selector' => '{{WRAPPER}} .king-addons-grid-item-date, {{WRAPPER}} .king-addons-grid-item-date span'
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
                    '{{WRAPPER}} .king-addons-grid-item-date .inner-block > span' => 'border-style: {{VALUE}};',
                ],
                'render_type' => 'template',
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
                    '{{WRAPPER}} .king-addons-grid-item-date .inner-block > span' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'render_type' => 'template',
                'condition' => [
                    'date_border_type!' => 'none',
                ],
            ]
        );

        $this->add_control(
            'date_text_spacing',
            [
                'label' => esc_html__('Extra Text Spacing', 'king-addons'),
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
                    '{{WRAPPER}} .king-addons-grid-item-date .king-addons-grid-extra-text-left' => 'padding-right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .king-addons-grid-item-date .king-addons-grid-extra-text-right' => 'padding-left: {{SIZE}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'date_icon_spacing',
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
                    '{{WRAPPER}} .king-addons-grid-item-date .king-addons-grid-extra-icon-left' => 'padding-right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .king-addons-grid-item-date .king-addons-grid-extra-icon-right' => 'padding-left: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'date_padding',
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
                    '{{WRAPPER}} .king-addons-grid-item-date .inner-block > span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'render_type' => 'template',
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'date_margin',
            [
                'label' => esc_html__('Margin', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'default' => [
                    'top' => 0,
                    'right' => 7,
                    'bottom' => 0,
                    'left' => 0,
                ],
                'render_type' => 'template',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-item-date .inner-block' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();


        $this->start_controls_section(
            'section_style_time',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Time', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            ]
        );

        $this->add_control(
            'time_color',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#9C9C9C',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-item-time .inner-block' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'time_bg_color',
            [
                'label' => esc_html__('Background Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-item-time .inner-block > span' => 'background-color: {{VALUE}}',
                ]
            ]
        );

        $this->add_control(
            'time_border_color',
            [
                'label' => esc_html__('Border Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#E8E8E8',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-item-time .inner-block > span' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'time_extra_text_color',
            [
                'label' => esc_html__('Extra Text Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#9C9C9C',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-item-time .inner-block span[class*="king-addons-grid-extra-text"]' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'time_extra_icon_color',
            [
                'label' => esc_html__('Extra Icon Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#9C9C9C',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-item-time .inner-block [class*="king-addons-grid-extra-icon"] i' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .king-addons-grid-item-time .inner-block [class*="king-addons-grid-extra-icon"] svg' => 'fill: {{VALUE}}'
                ],
                'separator' => 'after',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'time_typography',
                'selector' => '{{WRAPPER}} .king-addons-grid-item-time'
            ]
        );

        $this->add_control(
            'time_border_type',
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
                    '{{WRAPPER}} .king-addons-grid-item-time .inner-block > span' => 'border-style: {{VALUE}};',
                ],
                'render_type' => 'template',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'time_border_width',
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
                    '{{WRAPPER}} .king-addons-grid-item-time .inner-block > span' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'render_type' => 'template',
                'condition' => [
                    'time_border_type!' => 'none',
                ],
            ]
        );

        $this->add_control(
            'time_text_spacing',
            [
                'label' => esc_html__('Extra Text Spacing', 'king-addons'),
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
                    '{{WRAPPER}} .king-addons-grid-item-time .king-addons-grid-extra-text-left' => 'padding-right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .king-addons-grid-item-time .king-addons-grid-extra-text-right' => 'padding-left: {{SIZE}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'time_icon_spacing',
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
                    '{{WRAPPER}} .king-addons-grid-item-time .king-addons-grid-extra-icon-left' => 'padding-right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .king-addons-grid-item-time .king-addons-grid-extra-icon-right' => 'padding-left: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'time_padding',
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
                    '{{WRAPPER}} .king-addons-grid-item-time .inner-block > span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'render_type' => 'template',
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'time_margin',
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
                    '{{WRAPPER}} .king-addons-grid-item-time .inner-block' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'render_type' => 'template',
            ]
        );

        $this->end_controls_section();


        $this->start_controls_section(
            'section_style_author',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Author', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            ]
        );

        $this->start_controls_tabs('tabs_grid_author_style');

        $this->start_controls_tab(
            'tab_grid_author_normal',
            [
                'label' => esc_html__('Normal', 'king-addons'),
            ]
        );

        $this->add_control(
            'author_color',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#9C9C9C',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-item-author .inner-block a' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'author_bg_color',
            [
                'label' => esc_html__('Background Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-item-author .inner-block a' => 'background-color: {{VALUE}}',
                ]
            ]
        );

        $this->add_control(
            'author_border_color',
            [
                'label' => esc_html__('Border Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#E8E8E8',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-item-author .inner-block a' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'author_extra_text_color',
            [
                'label' => esc_html__('Extra Text Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#9C9C9C',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-item-author .inner-block span[class*="king-addons-grid-extra-text"]' => 'color: {{VALUE}}',
                ],
                'separator' => 'after',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_grid_author_hover',
            [
                'label' => esc_html__('Hover', 'king-addons'),
            ]
        );

        $this->add_control(
            'author_color_hr',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#5B03FF',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-item-author .inner-block a:hover' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'author_bg_color_hr',
            [
                'label' => esc_html__('Background Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-item-author .inner-block a:hover' => 'background-color: {{VALUE}}',
                ]
            ]
        );

        $this->add_control(
            'author_border_color_hr',
            [
                'label' => esc_html__('Border Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#E8E8E8',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-item-author .inner-block a:hover' => 'border-color: {{VALUE}}',
                ],
                'separator' => 'after',
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_control(
            'author_transition_duration',
            [
                'label' => esc_html__('Transition Duration (seconds)', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'default' => 0.1,
                'min' => 0,
                'max' => 5,
                'step' => 0.1,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-item-author .inner-block a' => 'transition-duration: {{VALUE}}s',
                ],
                'separator' => 'after',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'author_typography',
                'selector' => '{{WRAPPER}} .king-addons-grid-item-author, {{WRAPPER}} .king-addons-grid-item-author .inner-block a'
            ]
        );

        $this->add_control(
            'author_border_type',
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
                    '{{WRAPPER}} .king-addons-grid-item-author .inner-block a' => 'border-style: {{VALUE}};',
                ],
                'render_type' => 'template',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'author_border_width',
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
                    '{{WRAPPER}} .king-addons-grid-item-author .inner-block a' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'render_type' => 'template',
                'condition' => [
                    'author_border_type!' => 'none',
                ],
            ]
        );

        $this->add_control(
            'author_border_radius',
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
                    '{{WRAPPER}} .king-addons-grid-item-author .inner-block a img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->add_control(
            'author_text_spacing',
            [
                'label' => esc_html__('Extra Text Spacing', 'king-addons'),
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
                    '{{WRAPPER}} .king-addons-grid-item-author .king-addons-grid-extra-text-left' => 'padding-right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .king-addons-grid-item-author .king-addons-grid-extra-text-right' => 'padding-left: {{SIZE}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'author_icon_spacing',
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
                    '{{WRAPPER}} .king-addons-grid-item-author .king-addons-grid-extra-icon-left' => 'padding-right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .king-addons-grid-item-author .king-addons-grid-extra-icon-right' => 'padding-left: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'author_avatar_spacing',
            [
                'label' => esc_html__('Avatar Spacing', 'king-addons'),
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
                    '{{WRAPPER}} .king-addons-grid-item-author img' => 'margin-right: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'author_padding',
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
                    '{{WRAPPER}} .king-addons-grid-item-author .inner-block a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'render_type' => 'template',
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'author_margin',
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
                    '{{WRAPPER}} .king-addons-grid-item-author .inner-block' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();


        $this->start_controls_section(
            'section_style_comments',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Comments', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            ]
        );

        $this->start_controls_tabs('tabs_grid_comments_style');

        $this->start_controls_tab(
            'tab_grid_comments_normal',
            [
                'label' => esc_html__('Normal', 'king-addons'),
            ]
        );

        $this->add_control(
            'comments_color',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#9C9C9C',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-item-comments .inner-block a' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'comments_bg_color',
            [
                'label' => esc_html__('Background Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-item-comments .inner-block a' => 'background-color: {{VALUE}}',
                ]
            ]
        );

        $this->add_control(
            'comments_border_color',
            [
                'label' => esc_html__('Border Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#E8E8E8',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-item-comments .inner-block a' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'comments_extra_text_color',
            [
                'label' => esc_html__('Extra Text Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#9C9C9C',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-item-comments .inner-block span[class*="king-addons-grid-extra-text"]' => 'color: {{VALUE}}',
                ],
                'separator' => 'after',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_grid_comments_hover',
            [
                'label' => esc_html__('Hover', 'king-addons'),
            ]
        );

        $this->add_control(
            'comments_color_hr',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#5B03FF',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-item-comments .inner-block a:hover' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'comments_bg_color_hr',
            [
                'label' => esc_html__('Background Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-item-comments .inner-block a:hover' => 'background-color: {{VALUE}}',
                ]
            ]
        );

        $this->add_control(
            'comments_border_color_hr',
            [
                'label' => esc_html__('Border Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#E8E8E8',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-item-comments .inner-block a:hover' => 'border-color: {{VALUE}}',
                ],
                'separator' => 'after',
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_control(
            'comments_transition_duration',
            [
                'label' => esc_html__('Transition Duration (seconds)', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'default' => 0.1,
                'min' => 0,
                'max' => 5,
                'step' => 0.1,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-item-comments .inner-block a' => 'transition-duration: {{VALUE}}s',
                ],
                'separator' => 'after',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'comments_typography',
                'selector' => '{{WRAPPER}} .king-addons-grid-item-comments'
            ]
        );

        $this->add_control(
            'comments_border_type',
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
                    '{{WRAPPER}} .king-addons-grid-item-comments .inner-block a' => 'border-style: {{VALUE}};',
                ],
                'render_type' => 'template',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'comments_border_width',
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
                    '{{WRAPPER}} .king-addons-grid-item-comments .inner-block a' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'render_type' => 'template',
                'condition' => [
                    'comments_border_type!' => 'none',
                ],
            ]
        );

        $this->add_control(
            'comments_text_spacing',
            [
                'label' => esc_html__('Extra Text Spacing', 'king-addons'),
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
                    '{{WRAPPER}} .king-addons-grid-item-comments .king-addons-grid-extra-text-left' => 'padding-right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .king-addons-grid-item-comments .king-addons-grid-extra-text-right' => 'padding-left: {{SIZE}}{{UNIT}};',
                ],
                'separator' => 'before'
            ]
        );

        $this->add_control(
            'comments_icon_spacing',
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
                    '{{WRAPPER}} .king-addons-grid-item-comments .king-addons-grid-extra-icon-left' => 'padding-right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .king-addons-grid-item-comments .king-addons-grid-extra-icon-right' => 'padding-left: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'comments_padding',
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
                    '{{WRAPPER}} .king-addons-grid-item-comments .inner-block a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'render_type' => 'template',
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'comments_margin',
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
                    '{{WRAPPER}} .king-addons-grid-item-comments .inner-block' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'render_type' => 'template',
            ]
        );

        $this->add_control(
            'comments_radius',
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
                    '{{WRAPPER}} .king-addons-grid-item-comments .inner-block a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->end_controls_section();


        $this->start_controls_section(
            'section_style_read_more',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Read More', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            ]
        );

        $this->start_controls_tabs('tabs_grid_read_more_style');

        $this->start_controls_tab(
            'tab_grid_read_more_normal',
            [
                'label' => esc_html__('Normal', 'king-addons'),
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'read_more_bg_color',
                'label' => esc_html__('Background', 'king-addons'),
                'types' => ['classic', 'gradient'],
                'fields_options' => [
                    'color' => [
                        'default' => '#434900',
                    ],
                ],
                'selector' => '{{WRAPPER}} .king-addons-grid-item-read-more .inner-block a'
            ]
        );

        $this->add_control(
            'read_more_color',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#5B03FF',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-item-read-more .inner-block a' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'read_more_border_color',
            [
                'label' => esc_html__('Border Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#E8E8E8',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-item-read-more .inner-block a' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'read_more_box_shadow',
                'selector' => '{{WRAPPER}} .king-addons-grid-item-read-more .inner-block a',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_grid_read_more_hover',
            [
                'label' => esc_html__('Hover', 'king-addons'),
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'read_more_bg_color_hr',
                'label' => esc_html__('Background', 'king-addons'),
                'types' => ['classic', 'gradient'],
                'fields_options' => [
                    'color' => [
                        'default' => '#434900',
                    ],
                ],
                'selector' => '{{WRAPPER}} .king-addons-grid-item-read-more .inner-block a.king-addons-button-none:hover, {{WRAPPER}} .king-addons-grid-item-read-more .inner-block a:before, {{WRAPPER}} .king-addons-grid-item-read-more .inner-block a:after'
            ]
        );

        $this->add_control(
            'read_more_color_hr',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#4D02D8',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-item-read-more .inner-block a:hover' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'read_more_border_color_hr',
            [
                'label' => esc_html__('Border Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#E8E8E8',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-item-read-more .inner-block a:hover' => 'border-color: {{VALUE}}',
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'read_more_box_shadow_hr',
                'selector' => '{{WRAPPER}} .king-addons-grid-item-read-more .inner-block :hover a',
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_control(
            'read_more_divider',
            [
                'type' => Controls_Manager::DIVIDER,
                'style' => 'thick',
            ]
        );

        $this->add_control_read_more_animation();

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
                    '{{WRAPPER}} .king-addons-grid-item-read-more .inner-block a' => 'transition-duration: {{VALUE}}s',
                    '{{WRAPPER}} .king-addons-grid-item-read-more .inner-block a:before' => 'transition-duration: {{VALUE}}s',
                    '{{WRAPPER}} .king-addons-grid-item-read-more .inner-block a:after' => 'transition-duration: {{VALUE}}s',
                ],
            ]
        );

        $this->add_control_read_more_animation_height();

        $this->add_control(
            'read_more_typo_divider',
            [
                'type' => Controls_Manager::DIVIDER,
                'style' => 'thick',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'read_more_typography',
                'selector' => '{{WRAPPER}} .king-addons-grid-item-read-more a'
            ]
        );

        $this->add_control(
            'read_more_border_type',
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
                    '{{WRAPPER}} .king-addons-grid-item-read-more .inner-block a' => 'border-style: {{VALUE}};',
                ],
                'render_type' => 'template',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'read_more_border_width',
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
                    '{{WRAPPER}} .king-addons-grid-item-read-more .inner-block a' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'render_type' => 'template',
                'condition' => [
                    'read_more_border_type!' => 'none',
                ],
            ]
        );

        $this->add_control(
            'read_more_icon_spacing',
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
                    '{{WRAPPER}} .king-addons-grid-item-read-more .king-addons-grid-extra-icon-left' => 'padding-right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .king-addons-grid-item-read-more .king-addons-grid-extra-icon-right' => 'padding-left: {{SIZE}}{{UNIT}};',
                ],
                'separator' => 'before'
            ]
        );

        $this->add_responsive_control(
            'read_more_padding',
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
                    '{{WRAPPER}} .king-addons-grid-item-read-more .inner-block a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'render_type' => 'template',
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'read_more_margin',
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
                    '{{WRAPPER}} .king-addons-grid-item-read-more .inner-block' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'render_type' => 'template',
            ]
        );

        $this->add_control(
            'read_more_radius',
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
                    '{{WRAPPER}} .king-addons-grid-item-read-more .inner-block a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->end_controls_section();


        $this->add_section_style_likes();


        $this->add_section_style_sharing();
        
        // TODO
//        $this->add_section_style_lightbox();

        $this->start_controls_section(
            'section_style_filters',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Filters', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
                'show_label' => false,
                'condition' => [
                    'layout_select!' => 'slider',
                    'layout_filters' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'active_styles_notice',
            [
                'type' => Controls_Manager::RAW_HTML,
                'raw' => esc_html__('Apply active filter styles from the hover tab.', 'king-addons'),
                'content_classes' => 'elementor-panel-alert elementor-panel-alert-info'
            ]
        );

        $this->start_controls_tabs('tabs_grid_filters_style');

        $this->start_controls_tab(
            'tab_grid_filters_normal',
            [
                'label' => esc_html__('Normal', 'king-addons'),
            ]
        );

        $this->add_control(
            'filters_color',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#7A7A7A',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-filters li' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .king-addons-grid-filters li a' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'filters_bg_color',
            [
                'label' => esc_html__('Background Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-filters li > a' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .king-addons-grid-filters li > span' => 'background-color: {{VALUE}}',
                ]
            ]
        );

        $this->add_control(
            'filters_border_color',
            [
                'label' => esc_html__('Border Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#E8E8E8',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-filters li > a' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .king-addons-grid-filters li > span' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'filters_box_shadow',
                'selector' => '{{WRAPPER}} .king-addons-grid-filters li > a, {{WRAPPER}} .king-addons-grid-filters li > span',
            ]
        );

        $this->add_control(
            'filters_wrapper_color',
            [
                'label' => esc_html__('Wrapper Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-filters' => 'background-color: {{VALUE}}',
                ],
                'separator' => 'after',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_grid_filters_hover',
            [
                'label' => esc_html__('Hover', 'king-addons'),
            ]
        );

        $this->add_control(
            'filters_color_hr',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-filters li > a:hover' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .king-addons-grid-filters li > span:hover' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .king-addons-grid-filters li > .king-addons-active-filter' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'filters_bg_color_hr',
            [
                'label' => esc_html__('Background Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#5B03FF',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-filters li > a:hover' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .king-addons-grid-filters li > span:hover' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .king-addons-grid-filters li > .king-addons-active-filter' => 'background-color: {{VALUE}}',
                ]
            ]
        );

        $this->add_control(
            'filters_border_color_hr',
            [
                'label' => esc_html__('Border Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#E8E8E8',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-filters li > a:hover' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .king-addons-grid-filters li > span:hover' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .king-addons-grid-filters li > .king-addons-active-filter' => 'border-color: {{VALUE}}',
                ]
            ]
        );

        $this->add_control_filters_pointer_color_hr();

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'filters_box_shadow_hr',
                'selector' => '{{WRAPPER}} .king-addons-grid-filters li > a:hover, {{WRAPPER}} .king-addons-grid-filters li > span:hover',
                'separator' => 'after',
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_control_filters_pointer();

        $this->add_control_filters_pointer_height();

        $this->add_control_filters_pointer_animation();

        $this->add_control(
            'filters_transition_duration',
            [
                'label' => esc_html__('Transition Duration (seconds)', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'default' => 0.1,
                'min' => 0,
                'max' => 5,
                'step' => 0.1,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-filters li > a' => 'transition-duration: {{VALUE}}s',
                    '{{WRAPPER}} .king-addons-grid-filters li > span' => 'transition-duration: {{VALUE}}s',
                    '{{WRAPPER}} .king-addons-grid-filters .king-addons-pointer-item:before' => 'transition-duration: {{VALUE}}s',
                    '{{WRAPPER}} .king-addons-grid-filters .king-addons-pointer-item:after' => 'transition-duration: {{VALUE}}s',
                ],
                'separator' => 'after',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'filters_typography',
                'selector' => '{{WRAPPER}} .king-addons-grid-filters li'
            ]
        );

        $this->add_control(
            'filters_border_type',
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
                    '{{WRAPPER}} .king-addons-grid-filters li > a' => 'border-style: {{VALUE}};',
                    '{{WRAPPER}} .king-addons-grid-filters li > span' => 'border-style: {{VALUE}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'filters_border_width',
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
                    '{{WRAPPER}} .king-addons-grid-filters li > a' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .king-addons-grid-filters li > span' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'filters_border_type!' => 'none',
                ],
            ]
        );

        $this->add_responsive_control(
            'filters_distance_from_grid',
            [
                'label' => esc_html__('Distance From Grid', 'king-addons'),
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
                    '{{WRAPPER}} .king-addons-grid-filters' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
                'separator' => 'before'
            ]
        );

        $this->add_control(
            'filters_icon_spacing',
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
                    'size' => 0,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-filters-icon-left' => 'padding-right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .king-addons-grid-filters-icon-right' => 'padding-left: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'filters_margin',
            [
                'label' => esc_html__('Margin', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'default' => [
                    'top' => 0,
                    'right' => 5,
                    'bottom' => 0,
                    'left' => 0,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-filters li' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'filters_padding',
            [
                'label' => esc_html__('Padding', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px',],
                'default' => [
                    'top' => 3,
                    'right' => 15,
                    'bottom' => 3,
                    'left' => 15,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-filters li > a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .king-addons-grid-filters li > span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'filters_wrapper_padding',
            [
                'label' => esc_html__('Wrapper Padding', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px',],
                'default' => [
                    'top' => 0,
                    'right' => 0,
                    'bottom' => 0,
                    'left' => 0,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-filters' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'filters_radius',
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
                    '{{WRAPPER}} .king-addons-grid-filters li > a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .king-addons-grid-filters li > span' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->end_controls_section();


        $this->start_controls_section(
            'section_style_pagination',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Pagination', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
                'show_label' => false,
                'condition' => [
                    'layout_select!' => 'slider',
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
                    '{{WRAPPER}} .king-addons-ring div' => 'border-color: {{VALUE}}  transparent transparent transparent',
                ],
                'condition' => [
                    'pagination_type' => ['load-more', 'infinite-scroll']
                ]
            ]
        );

        $this->add_control(
            'pagination_wrapper_color',
            [
                'label' => esc_html__('Wrapper Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-pagination' => 'background-color: {{VALUE}}',
                ],
                'separator' => 'after',
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
                'name' => 'pagination_typography',
                'selector' => '{{WRAPPER}} .king-addons-grid-pagination, {{WRAPPER}} .king-addons-grid-pagination a'
            ]
        );

        $this->add_responsive_control(
            'pagination_icon_size',
            [
                'label' => esc_html__('Icon Size', 'king-addons'),
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
                    'size' => 15,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-pagination i' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .king-addons-grid-pagination svg' => 'width: {{SIZE}}{{UNIT}};',
                ],
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
                'label' => esc_html__('Distance From Grid', 'king-addons'),
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

        $this->add_control(
            'pagination_gutter',
            [
                'label' => esc_html__('Gutter', 'king-addons'),
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
                    'size' => 10,
                ],
                'selectors' => [

                    '{{WRAPPER}} .king-addons-grid-pagination a:not(:last-child)' => 'margin-right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .king-addons-grid-pagination > div > span' => 'margin-right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .king-addons-grid-pagination > div > a.king-addons-prev-page' => 'margin-right: {{SIZE}}{{UNIT}};',

                    '{{WRAPPER}} .king-addons-grid-pagination span.king-addons-disabled-arrow:not(:last-child)' => 'margin-right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .king-addons-grid-pagination span.king-addons-grid-current-page' => 'margin-right: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'pagination_icon_spacing',
            [
                'label' => esc_html__('Icon Spacing', 'king-addons'),
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
                    'size' => 10,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-pagination .king-addons-prev-post-link i' => 'padding-right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .king-addons-grid-pagination .king-addons-next-post-link i' => 'padding-left: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .king-addons-grid-pagination .king-addons-first-page i' => 'padding-right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .king-addons-grid-pagination .king-addons-prev-page i' => 'padding-right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .king-addons-grid-pagination .king-addons-next-page i' => 'padding-left: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .king-addons-grid-pagination .king-addons-last-page i' => 'padding-left: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .king-addons-grid-pagination .king-addons-prev-post-link svg' => 'margin-right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .king-addons-grid-pagination .king-addons-next-post-link svg' => 'margin-left: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .king-addons-grid-pagination .king-addons-first-page svg' => 'margin-right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .king-addons-grid-pagination .king-addons-prev-page svg' => 'margin-right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .king-addons-grid-pagination .king-addons-next-page svg' => 'margin-left: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .king-addons-grid-pagination .king-addons-last-page svg' => 'margin-left: {{SIZE}}{{UNIT}};',
                ],
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


        $this->start_controls_section(
            'section_style_pwd_protected',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Password Protected', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            ]
        );

        $this->add_control(
            'pwd_protected_color',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-item-protected' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'pwd_protected_bg_color',
            [
                'label' => esc_html__('Background Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#5B03FF',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-item-protected' => 'background-color: {{VALUE}}',
                ]
            ]
        );

        $this->add_control(
            'pwd_protected_input_color',
            [
                'label' => esc_html__('Input Background Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-item-protected input' => 'background-color: {{VALUE}}',
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'pwd_protected_typography',
                'selector' => '{{WRAPPER}} .king-addons-grid-item-protected p'
            ]
        );

        $this->end_controls_section();


        $this->start_controls_section(
            'section_style_separator1',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Separator Style 1', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            ]
        );

        $this->add_control(
            'separator1_color',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#E8E8E8',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-sep-style-1 .inner-block > span' => 'border-bottom-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'separator1_width',
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
                    'size' => 100,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-sep-style-1:not(.king-addons-grid-item-display-inline) .inner-block > span' => 'width: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .king-addons-grid-sep-style-1.king-addons-grid-item-display-inline' => 'width: {{SIZE}}{{UNIT}};',
                ],
                'render_type' => 'template',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'separator1_height',
            [
                'label' => esc_html__('Height', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 2,
                ],
                'render_type' => 'template',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-sep-style-1 .inner-block > span' => 'border-bottom-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'separator1_border_type',
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
                    '{{WRAPPER}} .king-addons-grid-sep-style-1 .inner-block > span' => 'border-bottom-style: {{VALUE}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'separator1_margin',
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
                'render_type' => 'template',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-sep-style-1 .inner-block' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'separator1_radius',
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
                    '{{WRAPPER}} .king-addons-grid-sep-style-1 .inner-block > span' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->end_controls_section();


        $this->start_controls_section(
            'section_style_separator2',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Separator Style 2', 'king-addons'),
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
                    '{{WRAPPER}} .king-addons-grid-sep-style-2 .inner-block > span' => 'border-bottom-color: {{VALUE}}',
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
                    '{{WRAPPER}} .king-addons-grid-sep-style-2:not(.king-addons-grid-item-display-inline) .inner-block > span' => 'width: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .king-addons-grid-sep-style-2.king-addons-grid-item-display-inline' => 'width: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .king-addons-grid-sep-style-2 .inner-block > span' => 'border-bottom-width: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .king-addons-grid-sep-style-2 .inner-block > span' => 'border-bottom-style: {{VALUE}};',
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
                    '{{WRAPPER}} .king-addons-grid-sep-style-2 .inner-block' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .king-addons-grid-sep-style-2 .inner-block > span' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->end_controls_section();


        $this->start_controls_section(
            'section_style_tax1',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Taxonomy Style 1', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            ]
        );

        $this->start_controls_tabs('tabs_grid_tax1_style');

        $this->start_controls_tab(
            'tab_grid_tax1_normal',
            [
                'label' => esc_html__('Normal', 'king-addons'),
            ]
        );

        $this->add_control(
            'tax1_color',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#9C9C9C',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-tax-style-1 .inner-block a' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'tax1_bg_color',
            [
                'label' => esc_html__('Background Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-tax-style-1 .inner-block a' => 'background-color: {{VALUE}}',
                ]
            ]
        );

        $this->add_control(
            'tax1_border_color',
            [
                'label' => esc_html__('Border Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#E8E8E8',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-tax-style-1 .inner-block a' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'tax1_extra_text_color',
            [
                'label' => esc_html__('Extra Text Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#9C9C9C',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-tax-style-1 .inner-block span[class*="king-addons-grid-extra-text"]' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'tax1_extra_icon_color',
            [
                'label' => esc_html__('Extra Icon Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#9C9C9C',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-tax-style-1 .inner-block [class*="king-addons-grid-extra-icon"] i' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .king-addons-grid-tax-style-1 .inner-block [class*="king-addons-grid-extra-icon"] svg' => 'fill: {{VALUE}}'
                ],
                'separator' => 'after',
            ]
        );

        $this->add_control_tax1_custom_colors($tax_meta_keys[1]);

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_grid_tax1_hover',
            [
                'label' => esc_html__('Hover', 'king-addons'),
            ]
        );

        $this->add_control(
            'tax1_color_hr',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#5B03FF',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-tax-style-1 .inner-block a:hover' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .king-addons-grid-tax-style-1 .king-addons-pointer-item:before' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .king-addons-grid-tax-style-1 .king-addons-pointer-item:after' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'tax1_bg_color_hr',
            [
                'label' => esc_html__('Background Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-tax-style-1 .inner-block a:hover' => 'background-color: {{VALUE}}',
                ]
            ]
        );

        $this->add_control(
            'tax1_border_color_hr',
            [
                'label' => esc_html__('Border Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#E8E8E8',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-tax-style-1 .inner-block a:hover' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control_tax1_pointer_color_hr();

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_control_tax1_pointer();

        $this->add_control_tax1_pointer_height();

        $this->add_control_tax1_pointer_animation();

        $this->add_control(
            'tax1_transition_duration',
            [
                'label' => esc_html__('Transition Duration (seconds)', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'default' => 0.1,
                'min' => 0,
                'max' => 5,
                'step' => 0.1,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-tax-style-1 .inner-block a' => 'transition-duration: {{VALUE}}s',
                    '{{WRAPPER}} .king-addons-grid-tax-style-1 .king-addons-pointer-item:before' => 'transition-duration: {{VALUE}}s',
                    '{{WRAPPER}} .king-addons-grid-tax-style-1 .king-addons-pointer-item:after' => 'transition-duration: {{VALUE}}s',
                ],
                'separator' => 'after',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'tax1_typography',
                'selector' => '{{WRAPPER}} .king-addons-grid-tax-style-1'
            ]
        );

        $this->add_control(
            'tax1_border_type',
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
                    '{{WRAPPER}} .king-addons-grid-tax-style-1 .inner-block a' => 'border-style: {{VALUE}};',
                ],
                'render_type' => 'template',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'tax1_border_width',
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
                    '{{WRAPPER}} .king-addons-grid-tax-style-1 .inner-block a' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'render_type' => 'template',
                'condition' => [
                    'tax1_border_type!' => 'none',
                ],
            ]
        );

        $this->add_control(
            'tax1_text_spacing',
            [
                'label' => esc_html__('Extra Text Spacing', 'king-addons'),
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
                    '{{WRAPPER}} .king-addons-grid-tax-style-1 .king-addons-grid-extra-text-left' => 'padding-right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .king-addons-grid-tax-style-1 .king-addons-grid-extra-text-right' => 'padding-left: {{SIZE}}{{UNIT}};',
                ],
                'render_type' => 'template',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'tax1_icon_spacing',
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
                    '{{WRAPPER}} .king-addons-grid-tax-style-1 .king-addons-grid-extra-icon-left' => 'padding-right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .king-addons-grid-tax-style-1 .king-addons-grid-extra-icon-right' => 'padding-left: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'tax1_gutter',
            [
                'label' => esc_html__('Gutter', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 20,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 3,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-tax-style-1 .inner-block a' => 'margin-right: {{SIZE}}{{UNIT}};',
                ],
                'render_type' => 'template',
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'tax1_padding',
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
                    '{{WRAPPER}} .king-addons-grid-tax-style-1 .inner-block a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'render_type' => 'template',
            ]
        );

        $this->add_responsive_control(
            'tax1_margin',
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
                    '{{WRAPPER}} .king-addons-grid-tax-style-1 .inner-block' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'render_type' => 'template',
            ]
        );

        $this->add_control(
            'tax1_radius',
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
                    '{{WRAPPER}} .king-addons-grid-tax-style-1 .inner-block a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->end_controls_section();


        $this->start_controls_section(
            'section_style_tax2',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Taxonomy Style 2', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            ]
        );

        $this->start_controls_tabs('tabs_grid_tax2_style');

        $this->start_controls_tab(
            'tab_grid_tax2_normal',
            [
                'label' => esc_html__('Normal', 'king-addons'),
            ]
        );

        $this->add_control(
            'tax2_color',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-tax-style-2 .inner-block a' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'tax2_bg_color',
            [
                'label' => esc_html__('Background Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#5B03FF',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-tax-style-2 .inner-block a' => 'background-color: {{VALUE}}',
                ]
            ]
        );

        $this->add_control(
            'tax2_border_color',
            [
                'label' => esc_html__('Border Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#E8E8E8',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-tax-style-2 .inner-block a' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'tax2_extra_text_color',
            [
                'label' => esc_html__('Extra Text Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#9C9C9C',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-tax-style-2 .inner-block span[class*="king-addons-grid-extra-text"]' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'tax2_extra_icon_color',
            [
                'label' => esc_html__('Extra Icon Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#9C9C9C',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-tax-style-2 .inner-block [class*="king-addons-grid-extra-icon"] i' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .king-addons-grid-tax-style-2 .inner-block [class*="king-addons-grid-extra-icon"] svg' => 'fill: {{VALUE}}'
                ],
                'separator' => 'after',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_grid_tax2_hover',
            [
                'label' => esc_html__('Hover', 'king-addons'),
            ]
        );

        $this->add_control(
            'tax2_color_hr',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-tax-style-2 .inner-block a:hover' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .king-addons-grid-tax-style-2 .king-addons-pointer-item:before' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .king-addons-grid-tax-style-2 .king-addons-pointer-item:after' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'tax2_bg_color_hr',
            [
                'label' => esc_html__('Background Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#4D02D8',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-tax-style-2 .inner-block a:hover' => 'background-color: {{VALUE}}',
                ]
            ]
        );

        $this->add_control(
            'tax2_border_color_hr',
            [
                'label' => esc_html__('Border Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#E8E8E8',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-tax-style-2 .inner-block a:hover' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control_tax2_pointer_color_hr();

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_control_tax2_pointer();

        $this->add_control_tax2_pointer_height();

        $this->add_control_tax2_pointer_animation();

        $this->add_control(
            'tax2_transition_duration',
            [
                'label' => esc_html__('Transition Duration (seconds)', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'default' => 0.1,
                'min' => 0,
                'max' => 5,
                'step' => 0.1,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-tax-style-2 .inner-block a' => 'transition-duration: {{VALUE}}s',
                    '{{WRAPPER}} .king-addons-grid-tax-style-2 .king-addons-pointer-item:before' => 'transition-duration: {{VALUE}}s',
                    '{{WRAPPER}} .king-addons-grid-tax-style-2 .king-addons-pointer-item:after' => 'transition-duration: {{VALUE}}s',
                ],
                'separator' => 'after',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'tax2_typography',
                'selector' => '{{WRAPPER}} .king-addons-grid-tax-style-2'
            ]
        );

        $this->add_control(
            'tax2_border_type',
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
                    '{{WRAPPER}} .king-addons-grid-tax-style-2 .inner-block a' => 'border-style: {{VALUE}};',
                ],
                'render_type' => 'template',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'tax2_border_width',
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
                    '{{WRAPPER}} .king-addons-grid-tax-style-2 .inner-block a' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'render_type' => 'template',
                'condition' => [
                    'tax2_border_type!' => 'none',
                ],
            ]
        );

        $this->add_control(
            'tax2_text_spacing',
            [
                'label' => esc_html__('Extra Text Spacing', 'king-addons'),
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
                    '{{WRAPPER}} .king-addons-grid-tax-style-2 .king-addons-grid-extra-text-left' => 'padding-right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .king-addons-grid-tax-style-2 .king-addons-grid-extra-text-right' => 'padding-left: {{SIZE}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'tax2_icon_spacing',
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
                    '{{WRAPPER}} .king-addons-grid-tax-style-2 .king-addons-grid-extra-icon-left' => 'padding-right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .king-addons-grid-tax-style-2 .king-addons-grid-extra-icon-right' => 'padding-left: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'tax2_gutter',
            [
                'label' => esc_html__('Gutter', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 20,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 3,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-tax-style-2 .inner-block a' => 'margin-right: {{SIZE}}{{UNIT}};',
                ],
                'render_type' => 'template',
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'tax2_padding',
            [
                'label' => esc_html__('Padding', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'default' => [
                    'top' => 0,
                    'right' => 10,
                    'bottom' => 0,
                    'left' => 10,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-tax-style-2 .inner-block a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'render_type' => 'template',
            ]
        );

        $this->add_responsive_control(
            'tax2_margin',
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
                    '{{WRAPPER}} .king-addons-grid-tax-style-2 .inner-block' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'render_type' => 'template',
            ]
        );

        $this->add_control(
            'tax2_radius',
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
                    '{{WRAPPER}} .king-addons-grid-tax-style-2 .inner-block a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->end_controls_section();


        $this->add_section_style_custom_field1();


        $this->add_section_style_custom_field2();


        $this->add_section_style_custom_field3();


        $this->add_section_style_custom_field4();


        $this->start_controls_section(
            'settings_section_style_grid_slider_nav',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Slider Navigation', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'layout_select' => 'slider',
                ],
            ]
        );

        $this->start_controls_tabs('tabs_grid_slider_nav_style');

        $this->start_controls_tab(
            'tab_grid_slider_nav_normal',
            [
                'label' => esc_html__('Normal', 'king-addons'),
            ]
        );

        $this->add_control(
            'grid_slider_nav_color',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#5B03FF',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-slider-arrow' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .king-addons-grid-slider-arrow svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'grid_slider_nav_bg_color',
            [
                'label' => esc_html__('Background Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-slider-arrow' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'grid_slider_nav_border_color',
            [
                'label' => esc_html__('Border Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => 'rgba(255,255,255,0.8)',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-slider-arrow' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_grid_slider_nav_hover',
            [
                'label' => esc_html__('Hover', 'king-addons'),
            ]
        );

        $this->add_control(
            'grid_slider_nav_hover_color',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#4D02D8',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-slider-arrow:hover' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .king-addons-grid-slider-arrow:hover svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'grid_slider_nav_hover_bg_color',
            [
                'label' => esc_html__('Background Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-slider-arrow:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'grid_slider_nav_hover_border_color',
            [
                'label' => esc_html__('Border Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-slider-arrow:hover' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_control(
            'grid_slider_nav_transition_duration',
            [
                'label' => esc_html__('Transition Duration (seconds)', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'default' => 0.1,
                'min' => 0,
                'max' => 5,
                'step' => 0.1,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-slider-arrow' => 'transition-duration: {{VALUE}}s',
                    '{{WRAPPER}} .king-addons-grid-slider-arrow svg' => 'transition-duration: {{VALUE}}s',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'grid_slider_nav_font_size',
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
                    '{{WRAPPER}} .king-addons-grid-slider-arrow' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .king-addons-grid-slider-arrow svg' => 'width: {{SIZE}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'grid_slider_nav_size',
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
                    '{{WRAPPER}} .king-addons-grid-slider-arrow' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
                'separator' => 'after',
            ]
        );

        $this->add_control(
            'grid_slider_nav_border_type',
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
                    '{{WRAPPER}} .king-addons-grid-slider-arrow' => 'border-style: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'grid_slider_nav_border_width',
            [
                'label' => esc_html__('Border Width', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'default' => [
                    'top' => 2,
                    'right' => 2,
                    'bottom' => 2,
                    'left' => 2,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-slider-arrow' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'grid_slider_nav_border_type!' => 'none',
                ],
            ]
        );

        $this->add_control(
            'grid_slider_nav_border_radius',
            [
                'label' => esc_html__('Border Radius', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-slider-arrow' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'after',
            ]
        );

        $this->add_control_stack_grid_slider_nav_position();

        $this->end_controls_section();


        $this->start_controls_section(
            'settings_section_style_grid_slider_dots',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Slider Pagination', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'layout_select' => 'slider',
                ],
            ]
        );

        $this->start_controls_tabs('tabs_grid_slider_dots');

        $this->start_controls_tab(
            'tab_grid_slider_dots_normal',
            [
                'label' => esc_html__('Normal', 'king-addons'),
            ]
        );

        $this->add_control(
            'grid_slider_dots_bg_color',
            [
                'label' => esc_html__('Background Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => 'rgba(0,0,0,0.35)',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-slider-dot' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'grid_slider_dots_border_color',
            [
                'label' => esc_html__('Border Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-slider-dot' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_grid_slider_dots_active',
            [
                'label' => esc_html__('Active', 'king-addons'),
            ]
        );

        $this->add_control(
            'grid_slider_dots_active_bg_color',
            [
                'label' => esc_html__('Background Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#5B03FF',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-slider-dots .slick-active .king-addons-grid-slider-dot' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'grid_slider_dots_active_border_color',
            [
                'label' => esc_html__('Border Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-slider-dots .slick-active .king-addons-grid-slider-dot' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_responsive_control(
            'grid_slider_dots_width',
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
                    '{{WRAPPER}} .king-addons-grid-slider-dot' => 'width: {{SIZE}}{{UNIT}};',
                ],
                'separator' => 'before',
                'render_type' => 'template'
            ]
        );

        $this->add_responsive_control(
            'grid_slider_dots_height',
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
                    '{{WRAPPER}} .king-addons-grid-slider-dot' => 'height: {{SIZE}}{{UNIT}};',
                ],
                'render_type' => 'template'
            ]
        );

        $this->add_control(
            'grid_slider_dots_border_type',
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
                    '{{WRAPPER}} .king-addons-grid-slider-dot' => 'border-style: {{VALUE}};',
                ],
                'render_type' => 'template',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'grid_slider_dots_border_width',
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
                    '{{WRAPPER}} .king-addons-grid-slider-dot' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'render_type' => 'template',
                'condition' => [
                    'grid_slider_dots_border_type!' => 'none',
                ],
            ]
        );

        $this->add_control(
            'grid_slider_dots_border_radius',
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
                    '{{WRAPPER}} .king-addons-grid-slider-dot' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'grid_slider_dots_gutter',
            [
                'type' => Controls_Manager::SLIDER,
                'label' => esc_html__('Gutter', 'king-addons'),
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 7,
                ],
                'selectors' => [
                    '{{WRAPPER}}.king-addons-grid-slider-dots-horizontal .king-addons-grid-slider-dot' => 'margin-right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}}.king-addons-grid-slider-dots-vertical .king-addons-grid-slider-dot' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
                'render_type' => 'template',
                'separator' => 'before',
            ]
        );

        $this->add_control_grid_slider_dots_hr();

        $this->add_responsive_control(
            'grid_slider_dots_vr',
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
                    '{{WRAPPER}} .king-addons-grid-slider-dots' => 'top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

    }

    public function get_related_taxonomies()
    {
        $post_types = Core::getCustomTypes('post', false);
        $relations = [];

        // Simply assign the array returned by get_object_taxonomies() to avoid extra loops
        foreach ($post_types as $slug => $title) {
            $relations[$slug] = get_object_taxonomies($slug);
        }

        return json_encode($relations);
    }

    public function get_max_num_pages()
    {
        $query = new WP_Query($this->get_main_query_args());
        $max_num_pages = (int)ceil($query->max_num_pages);
        wp_reset_postdata();
        return $max_num_pages;
    }

    public function get_main_query_args()
    {
        $settings = $this->get_settings();

        // Detect paged
        $paged = get_query_var('paged') ?: (get_query_var('page') ?: 1);

        // Build author string if needed
        $author = !empty($settings['query_author']) ? implode(',', $settings['query_author']) : '';

        // Adjust posts_per_page for slider (premium check)
        if (
            'slider' === $settings['layout_select'] &&
            king_addons_freemius()->can_use_premium_code__premium_only()
        ) {
            $slidesToShow = $settings['query_slides_to_show'] ?: -1;
            $settings['query_posts_per_page'] = min($slidesToShow, 4);
        }

        // Slider forces paged to 1
        if ('slider' === $settings['layout_select']) {
            $paged = 1;
        }

        // Ensure offset is set
        $settings['query_offset'] = $settings['query_offset'] ?? 0;

        // Calculate offset
        $postsPerPage = $settings['query_posts_per_page'] ?? 0;
        if (empty($postsPerPage) && !(
                'slider' === $settings['layout_select'] &&
                king_addons_freemius()->can_use_premium_code__premium_only()
            )) {
            $postsPerPage = 999;
        }

        $offset = ($paged - 1) * (int)$postsPerPage + (int)$settings['query_offset'];

        // If premium not available, reset certain settings
        if (!king_addons_freemius()->can_use_premium_code__premium_only()) {
            $settings['query_randomize'] = '';
            $settings['order_posts'] = 'date';
        }

        // Determine "orderby"
        $query_order_by = !empty($settings['query_randomize'])
            ? $settings['query_randomize']
            : $settings['order_posts'];

        // Determine excluded posts array
        $excludeKey = 'query_exclude_' . $settings['query_source'];
        $post__not_in = !empty($settings[$excludeKey]) ? $settings[$excludeKey] : [];

        // Default query args
        $args = [
            'post_type' => $settings['query_source'],
            'tax_query' => $this->get_tax_query_args(),
            'post__not_in' => $post__not_in,
            'posts_per_page' => $postsPerPage,
            'orderby' => $query_order_by,
            'author' => $author,
            'paged' => $paged,
            'offset' => $offset,
        ];

        // Handle meta_value ordering
        if ('meta_value' === $query_order_by) {
            $args['meta_key'] = $settings['order_posts_by_acf'];
        }

        // Handle scheduled/future posts
        $args['post_status'] = (
            'yes' === $settings['display_scheduled_posts'] &&
            king_addons_freemius()->can_use_premium_code__premium_only()
        )
            ? 'future'
            : 'publish';

        // Exclude posts with no thumbnail if requested
        if ('yes' === $settings['query_exclude_no_images']) {
            $args['meta_key'] = '_thumbnail_id';
        }

        // MANUAL selection overrides everything else
        if ('manual' === $settings['query_selection']) {
            $manualKey = 'query_manual_' . $settings['query_source'];
            $post_ids = !empty($settings[$manualKey]) ? $settings[$manualKey] : [''];

            $args = [
                'post_type' => $settings['query_source'],
                'post__in' => $post_ids,
                'ignore_sticky_posts' => 1,
                'posts_per_page' => $postsPerPage,
                'orderby' => $query_order_by,
                'paged' => $paged,
            ];
        }

        // "CURRENT" uses global $wp_query
        if ('current' === $settings['query_source']) {
            global $wp_query;
            $args = $wp_query->query_vars;
            $args['orderby'] = $query_order_by;

            if (is_post_type_archive()) {
                $args['posts_per_page'] = (int)get_option('king_addons_cpt_ppp_' . $args['post_type'], 10);
            } else {
                $args['posts_per_page'] = (int)get_option('posts_per_page');
            }

            $args['offset'] = ($paged - 1) * $args['posts_per_page'] + (int)$settings['query_offset'];

            // Build a small tax_query if categories are passed in GET
            $tax_query = [];
            foreach (['category', 'king_addons_select_category'] as $catParam) {
                if (!empty($_GET[$catParam]) && $_GET[$catParam] !== '0') {
                    $tax_query[] = [
                        'taxonomy' => 'category',
                        'field' => 'id',
                        'terms' => sanitize_text_field($_GET[$catParam]),
                    ];
                }
            }
            if (!empty($tax_query)) {
                $args['tax_query'] = $tax_query;
            }
        }

        // "RELATED" posts
        if ('related' === $settings['query_source']) {
            $args = [
                'post_type' => get_post_type(get_the_ID()),
                'tax_query' => $this->get_tax_query_args(),
                'post__not_in' => [get_the_ID()],
                'ignore_sticky_posts' => 1,
                'posts_per_page' => $postsPerPage,
                'orderby' => $query_order_by,
                'offset' => $offset,
            ];
        }

        // Order direction (unless random)
        if ('rand' !== $query_order_by) {
            $args['order'] = $settings['order_direction'];
        }

        return $args;
    }

    public function get_tax_query_args()
    {
        $settings = $this->get_settings();

        // "RELATED" means match the same terms as current post in a chosen taxonomy
        if ('related' === $settings['query_source']) {
            return [[
                'taxonomy' => $settings['query_tax_selection'],
                'field' => 'term_id',
                'terms' => wp_get_object_terms(
                    get_the_ID(),
                    $settings['query_tax_selection'],
                    ['fields' => 'ids']
                ),
            ]];
        }

        // Otherwise, gather chosen taxonomies
        $tax_query = [];
        $taxonomies = get_object_taxonomies($settings['query_source']);

        foreach ($taxonomies as $tax) {
            $fieldKey = 'query_taxonomy_' . $tax;
            if (!empty($settings[$fieldKey])) {
                $tax_query[] = [
                    'taxonomy' => $tax,
                    'field' => 'id',
                    'terms' => $settings[$fieldKey],
                ];
            }
        }
        return $tax_query;
    }

    public function get_animation_class($data, $object)
    {
        // Disable if mobile + disable-mobile setting
        if (
            'overlay' !== $object &&
            'yes' === ($data[$object . '_animation_disable_mobile'] ?? '') &&
            wp_is_mobile()
        ) {
            return '';
        }

        $class = '';
        $anim = $data[$object . '_animation'] ?? 'none';

        if ('none' !== $anim) {
            $class .= ' king-addons-' . $object . '-' . $anim;
            $class .= ' king-addons-anim-size-' . ($data[$object . '_animation_size'] ?? '');
            $class .= ' king-addons-animation-timing-' . ($data[$object . '_animation_timing'] ?? '');
            if ('yes' === ($data[$object . '_animation_tr'] ?? '')) {
                $class .= ' king-addons-anim-transparency';
            }
        }
        return $class;
    }

    public function get_image_effect_class($settings)
    {
        // Restrict effects if premium not available
        if (
            !king_addons_freemius()->can_use_premium_code__premium_only() &&
            in_array($settings['image_effects'], ['pro-zi', 'pro-zo', 'pro-go', 'pro-bo'])
        ) {
            $settings['image_effects'] = 'none';
        }

        $class = '';
        $effect = $settings['image_effects'] ?? 'none';

        if ('none' !== $effect) {
            $class .= ' king-addons-' . $effect;
        }

        // For 'slide' vs other effect directions
        if ('slide' !== $effect) {
            $class .= ' king-addons-effect-size-' . ($settings['image_effects_size'] ?? '');
        } else {
            $class .= ' king-addons-effect-dir-' . ($settings['image_effects_direction'] ?? '');
        }

        return $class;
    }

    public function render_password_protected_input()
    {
        if (!post_password_required()) {
            return;
        }

        add_filter('the_password_form', function () {
            $output = '<form action="' . esc_url(home_url('wp-login.php?action=postpass')) . '" method="post">';
            $output .= '<i class="fas fa-lock"></i>';
            $output .= '<p>' . esc_html(get_the_title()) . '</p>';
            $output .= '<input type="password" name="post_password" id="post-' . esc_attr(get_the_id()) . '" placeholder="' . esc_html__('Type and hit Enter...', 'king-addons') . '">';
            $output .= '</form>';
            return $output;
        });

        echo '<div class="king-addons-grid-item-protected king-addons-cv-container">';
        echo '  <div class="king-addons-cv-outer">';
        echo '      <div class="king-addons-cv-inner">';
        echo get_the_password_form();
        echo '      </div>';
        echo '  </div>';
        echo '</div>';
    }

    public function render_post_thumbnail($settings)
    {
        $id = get_post_thumbnail_id();
        $src = Group_Control_Image_Size::get_attachment_image_src($id, 'layout_image_crop', $settings);

        // Secondary image
        $sec_meta = get_post_meta(get_the_ID(), 'king_addons_secondary_image_id', true);
        $src2 = !empty($sec_meta)
            ? Group_Control_Image_Size::get_attachment_image_src($sec_meta, 'layout_image_crop', $settings)
            : '';

        // Determine alt
        $alt = '' === wp_get_attachment_caption($id)
            ? get_the_title()
            : wp_get_attachment_caption($id);

        if (has_post_thumbnail()) {
            echo '<div class="king-addons-grid-image-wrap" data-src="' . esc_url($src) . '" data-img-on-hover="' . esc_attr($settings['secondary_img_on_hover']) . '"  data-src-secondary="' . esc_url($src2) . '">';

            // Lazy loading vs normal
            /** @noinspection PhpIfWithCommonPartsInspection */
            if ('yes' === $settings['grid_lazy_loading']) {
                echo '<img loading="lazy" src="'.esc_url($src).'" 
                alt="' . esc_attr($alt) . '" class="king-addons-hidden-image king-addons-animation-timing-' . esc_attr($settings['image_effects_animation_timing']) . '">';
                if ('yes' === $settings['secondary_img_on_hover']) {
                    echo '<img src="' . esc_url($src2) . '" alt="' . esc_attr($alt) . '" class="king-addons-hidden-img king-addons-animation-timing-' . esc_attr($settings['image_effects_animation_timing']) . '">';
                }
            } else {
                echo '<img src="' . esc_url($src) . '" alt="' . esc_attr($alt) . '" class="king-addons-animation-timing-' . esc_attr($settings['image_effects_animation_timing']) . '">';
                if ('yes' === $settings['secondary_img_on_hover']) {
                    echo '<img src="' . esc_url($src2) . '" alt="' . esc_attr($alt) . '" class="king-addons-hidden-img king-addons-animation-timing-' . esc_attr($settings['image_effects_animation_timing']) . '">';
                }
            }
            echo '</div>';
        }
    }

    public function render_media_overlay($settings)
    {
        echo '<div class="king-addons-grid-media-hover-bg ' . esc_attr($this->get_animation_class($settings, 'overlay')) . '" data-url="' . esc_url(get_the_permalink()) . '">';
        if (king_addons_freemius()->can_use_premium_code__premium_only() && !empty($settings['overlay_image']['url'])) {
            echo '<img src="' . esc_url($settings['overlay_image']['url']) . '" alt="' . esc_attr($settings['overlay_image']['alt']) . '">';
        }
        echo '</div>';
    }

    public function render_post_title($settings, $class)
    {
        // Fallback pointer for non-premium
        $title_pointer = king_addons_freemius()->can_use_premium_code__premium_only()
            ? ($this->get_settings()['title_pointer'] ?? 'none')
            : 'none';
        $title_pointer_animation = king_addons_freemius()->can_use_premium_code__premium_only()
            ? ($this->get_settings()['title_pointer_animation'] ?? 'fade')
            : 'fade';

        $pointer_item_class = ($title_pointer !== 'none')
            ? 'class="king-addons-pointer-item"'
            : '';

        $new_tab = ('yes' === $this->get_settings()['open_links_in_new_tab']) ? '_blank' : '_self';

        $class .= ' king-addons-pointer-' . $title_pointer;
        $class .= ' king-addons-pointer-line-fx king-addons-pointer-fx-' . $title_pointer_animation;

        // Validate allowed HTML tags
        $tags_whitelist = ['h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'div', 'span', 'p'];
        $element_title_tag = Core::validateHTMLTags($settings['element_title_tag'], 'h2', $tags_whitelist);

        echo '<' . esc_attr($element_title_tag) . ' class="' . esc_attr($class) . '">';
        echo '  <div class="inner-block">';
        echo '      <a target="' . esc_attr($new_tab) . '" ' . $pointer_item_class . ' href="' . esc_url(get_the_permalink()) . '">';
        if ('word_count' === $settings['element_trim_text_by']) {
            echo esc_html(wp_trim_words(get_the_title(), $settings['element_word_count']));
        } else {
            echo esc_html(mb_substr(html_entity_decode(get_the_title()), 0, $settings['element_letter_count'])) . '...';
        }
        echo '      </a>';
        echo '  </div>';
        echo '</' . esc_attr($element_title_tag) . '>';
    }

    public function render_post_content($settings, $class)
    {
        if ('' === get_the_content()) {
            return;
        }
        if ('yes' === $settings['element_dropcap']) {
            $class .= ' king-addons-enable-dropcap';
        }

        echo '<div class="' . esc_attr($class) . '">';
        echo '  <div class="inner-block">';
        echo wp_kses_post(get_the_content());
        echo '  </div>';
        echo '</div>';
    }

    public function render_post_excerpt($settings, $class)
    {
        if ('' === get_the_excerpt()) {
            return;
        }
        if ('yes' === $settings['element_dropcap']) {
            $class .= ' king-addons-enable-dropcap';
        }

        echo '<div class="' . esc_attr($class) . '">';
        echo '  <div class="inner-block">';
        if ('word_count' === $settings['element_trim_text_by']) {
            echo '<p>' . esc_html(wp_trim_words(get_the_excerpt(), $settings['element_word_count'])) . '</p>';
        } else {
            // slice letters
            $excerptLetters = implode('', array_slice(str_split(get_the_excerpt()), 0, $settings['element_letter_count']));
            echo '<p>' . esc_html($excerptLetters) . '...</p>';
        }
        echo '  </div>';
        echo '</div>';
    }

    public function render_post_date($settings, $class)
    {
        echo '<div class="' . esc_attr($class) . '">';
        echo '  <div class="inner-block">';
        echo '      <span>';

        if ('before' === $settings['element_extra_text_pos']) {
            echo '<span class="king-addons-grid-extra-text-left">' . esc_html($settings['element_extra_text']) . '</span>';
        }
        if ('before' === $settings['element_extra_icon_pos']) {
            ob_start();
            Icons_Manager::render_icon($settings['element_extra_icon'], ['aria-hidden' => 'true']);
            $icon = ob_get_clean();
            echo '<span class="king-addons-grid-extra-icon-left">' . $icon . '</span>';
        }

        if ('yes' === $settings['show_last_update_date']) {
            echo esc_html(get_the_modified_time(get_option('date_format')));
        } else {
            echo esc_html(apply_filters('the_date', get_the_date(), get_option('date_format'), '', ''));
        }

        if ('after' === $settings['element_extra_icon_pos']) {
            ob_start();
            Icons_Manager::render_icon($settings['element_extra_icon'], ['aria-hidden' => 'true']);
            $icon = ob_get_clean();
            echo '<span class="king-addons-grid-extra-icon-right">' . $icon . '</span>';
        }
        if ('after' === $settings['element_extra_text_pos']) {
            echo '<span class="king-addons-grid-extra-text-right">' . esc_html($settings['element_extra_text']) . '</span>';
        }

        echo '      </span>';
        echo '  </div>';
        echo '</div>';
    }

    public function render_post_time($settings, $class)
    {
        echo '<div class="' . esc_attr($class) . '">';
        echo '  <div class="inner-block">';
        echo '      <span>';

        if ('before' === $settings['element_extra_text_pos']) {
            echo '<span class="king-addons-grid-extra-text-left">' . esc_html($settings['element_extra_text']) . '</span>';
        }
        if ('before' === $settings['element_extra_icon_pos']) {
            ob_start();
            Icons_Manager::render_icon($settings['element_extra_icon'], ['aria-hidden' => 'true']);
            $icon = ob_get_clean();
            echo '<span class="king-addons-grid-extra-icon-left">' . $icon . '</span>';
        }

        echo esc_html(get_the_time());

        if ('after' === $settings['element_extra_icon_pos']) {
            ob_start();
            Icons_Manager::render_icon($settings['element_extra_icon'], ['aria-hidden' => 'true']);
            $icon = ob_get_clean();
            echo '<span class="king-addons-grid-extra-icon-right">' . $icon . '</span>';
        }
        if ('after' === $settings['element_extra_text_pos']) {
            echo '<span class="king-addons-grid-extra-text-right">' . esc_html($settings['element_extra_text']) . '</span>';
        }

        echo '      </span>';
        echo '  </div>';
        echo '</div>';
    }

    public function render_post_author($settings, $class)
    {
        $author_id = get_post_field('post_author');

        echo '<div class="' . esc_attr($class) . '">';
        echo '  <div class="inner-block">';

        if ('before' === $settings['element_extra_text_pos']) {
            echo '<span class="king-addons-grid-extra-text-left">' . esc_html($settings['element_extra_text']) . '</span>';
        }

        echo '<a href="' . esc_url(get_author_posts_url($author_id)) . '">';

        if ('before' === $settings['element_extra_icon_pos']) {
            ob_start();
            Icons_Manager::render_icon($settings['element_extra_icon'], ['aria-hidden' => 'true']);
            $icon = ob_get_clean();
            echo '<span class="king-addons-grid-extra-icon-left">' . $icon . '</span>';
        }
        if ('yes' === $settings['element_show_avatar']) {
            echo get_avatar($author_id, $settings['element_avatar_size']);
        }

        echo '<span>' . esc_html(get_the_author_meta('display_name', $author_id)) . '</span>';

        if ('after' === $settings['element_extra_icon_pos']) {
            ob_start();
            Icons_Manager::render_icon($settings['element_extra_icon'], ['aria-hidden' => 'true']);
            $icon = ob_get_clean();
            echo '<span class="king-addons-grid-extra-icon-right">' . $icon . '</span>';
        }

        echo '</a>';

        if ('after' === $settings['element_extra_text_pos']) {
            echo '<span class="king-addons-grid-extra-text-right">' . esc_html($settings['element_extra_text']) . '</span>';
        }

        echo '  </div>';
        echo '</div>';
    }

    public function render_post_comments($settings, $class)
    {
        if (!comments_open()) {
            return;
        }

        $count = get_comments_number();
        if ($count == 1) {
            $text = $count . ' ' . $settings['element_comments_text_2'];
        } elseif ($count > 1) {
            $text = $count . ' ' . $settings['element_comments_text_3'];
        } else {
            $text = $settings['element_comments_text_1'];
        }

        echo '<div class="' . esc_attr($class) . '">';
        echo '  <div class="inner-block">';

        if ('before' === $settings['element_extra_text_pos']) {
            echo '<span class="king-addons-grid-extra-text-left">' . esc_html($settings['element_extra_text']) . '</span>';
        }

        echo '<a href="' . esc_url(get_comments_link()) . '">';

        if ('before' === $settings['element_extra_icon_pos']) {
            ob_start();
            Icons_Manager::render_icon($settings['element_extra_icon'], ['aria-hidden' => 'true']);
            $icon = ob_get_clean();
            echo '<span class="king-addons-grid-extra-icon-left">' . $icon . '</span>';
        }

        echo '<span>' . esc_html($text) . '</span>';

        if ('after' === $settings['element_extra_icon_pos']) {
            ob_start();
            Icons_Manager::render_icon($settings['element_extra_icon'], ['aria-hidden' => 'true']);
            $icon = ob_get_clean();
            echo '<span class="king-addons-grid-extra-icon-right">' . $icon . '</span>';
        }

        echo '</a>';

        if ('after' === $settings['element_extra_text_pos']) {
            echo '<span class="king-addons-grid-extra-text-right">' . esc_html($settings['element_extra_text']) . '</span>';
        }

        echo '  </div>';
        echo '</div>';
    }

    public function render_post_read_more($settings, $class)
    {
        // Fallback animation for non-premium
        $read_more_animation = king_addons_freemius()->can_use_premium_code__premium_only()
            ? $this->get_settings()['read_more_animation']
            : 'king-addons-button-none';

        $new_tab = ('yes' === $this->get_settings()['open_links_in_new_tab']) ? '_blank' : '_self';

        echo '<div class="' . esc_attr($class) . '">';
        echo '  <div class="inner-block">';
        echo '      <a target="' . esc_attr($new_tab) . '" href="' . esc_url(get_the_permalink()) . '" class="king-addons-button-effect ' . esc_attr($read_more_animation) . '">';

        if ('before' === $settings['element_extra_icon_pos']) {
            ob_start();
            Icons_Manager::render_icon($settings['element_extra_icon'], ['aria-hidden' => 'true']);
            $icon = ob_get_clean();
            echo '<span class="king-addons-grid-extra-icon-left">' . $icon . '</span>';
        }

        echo '<span>' . esc_html($settings['element_read_more_text']) . '</span>';

        if ('after' === $settings['element_extra_icon_pos']) {
            ob_start();
            Icons_Manager::render_icon($settings['element_extra_icon'], ['aria-hidden' => 'true']);
            $icon = ob_get_clean();
            echo '<span class="king-addons-grid-extra-icon-right">' . $icon . '</span>';
        }

        echo '      </a>';
        echo '  </div>';
        echo '</div>';
    }

    // Placeholder for premium-protected items
    public function render_post_likes($settings, $class, $post_id)
    {
    }

    public function render_post_sharing_icons($settings, $class)
    {
    }

    public function render_post_custom_field($settings, $class, $post_id)
    {
    }

    public function render_post_lightbox($settings, $class, $post_id)
    {
        echo '<div class="' . esc_attr($class) . '">';
        echo '  <div class="inner-block">';

        // Default to post thumbnail if set
        $lightbox_source = get_the_post_thumbnail_url($post_id);

        // Handle Audio/Video meta
        if ('audio' === get_post_format()) {
            if ('meta' === $settings['element_lightbox_pfa_select']) {
                $meta_value = get_post_meta($post_id, $settings['element_lightbox_pfa_meta'], true);
                // Attempt to oEmbed
                if (false === strpos($meta_value, '<iframe ')) {
                    add_filter('oembed_result', $posts_grid_filter = function ($html) {
                        preg_match('/<iframe.*src=\"(.*)\".*><\/iframe>/isU', $html, $matches);
                        return $matches[1] . '&auto_play=true';
                    }, 50, 3);
                    $track_url = wp_oembed_get($meta_value);
                    remove_filter('oembed_result', $posts_grid_filter, 50);
                } else {
                    $track_url = Core::filterOembedResults($meta_value);
                }
                $lightbox_source = $track_url;
            }
        } elseif ('video' === get_post_format()) {
            if ('meta' === $settings['element_lightbox_pfv_select']) {
                $meta_value = get_post_meta($post_id, $settings['element_lightbox_pfv_meta'], true);
                if (false === strpos($meta_value, '<iframe ')) {
                    $video = Embed::get_video_properties($meta_value);
                } else {
                    $video = Embed::get_video_properties(Core::filterOembedResults($meta_value));
                }

                if (isset($video['provider'], $video['video_id'])) {
                    if ('youtube' === $video['provider']) {
                        $lightbox_source = 'https://www.youtube.com/embed/' . $video['video_id'] . '?feature=oembed&autoplay=1&controls=1';
                    } elseif ('vimeo' === $video['provider']) {
                        $lightbox_source = 'https://player.vimeo.com/video/' . $video['video_id'] . '?autoplay=1#t=0';
                    }
                }
            }
        }

        echo '      <span data-src="' . esc_url($lightbox_source) . '">';

        if ('before' === $settings['element_extra_text_pos']) {
            echo '<span class="king-addons-grid-extra-text-left">' . esc_html($settings['element_extra_text']) . '</span>';
        }

        echo '          <i class="' . esc_attr($settings['element_extra_icon']['value']) . '"></i>';

        if ('after' === $settings['element_extra_text_pos']) {
            echo '<span class="king-addons-grid-extra-text-right">' . esc_html($settings['element_extra_text']) . '</span>';
        }

        echo '      </span>';

        if ('yes' === $settings['element_lightbox_overlay']) {
            echo '<div class="king-addons-grid-lightbox-overlay"></div>';
        }

        echo '  </div>';
        echo '</div>';
    }

    public function render_post_element_separator($settings, $class)
    {
        echo '<div class="' . esc_attr($class . ' ' . $settings['element_separator_style']) . '">';
        echo '  <div class="inner-block"><span></span></div>';
        echo '</div>';
    }

    public function render_post_taxonomies($settings, $class, $post_id)
    {
        $terms = wp_get_post_terms($post_id, $settings['element_select']);
        if (empty($terms)) {
            return;
        }

        // Handle pointer classes (premium or fallback)
        $tax1_pointer = king_addons_freemius()->can_use_premium_code__premium_only()
            ? ($this->get_settings()['tax1_pointer'] ?? 'none')
            : 'none';
        $tax1_pointer_animation = king_addons_freemius()->can_use_premium_code__premium_only()
            ? ($this->get_settings()['tax1_pointer_animation'] ?? 'fade')
            : 'fade';
        $tax2_pointer = king_addons_freemius()->can_use_premium_code__premium_only()
            ? ($this->get_settings()['tax2_pointer'] ?? 'none')
            : 'none';
        $tax2_pointer_animation = king_addons_freemius()->can_use_premium_code__premium_only()
            ? ($this->get_settings()['tax2_pointer_animation'] ?? 'fade')
            : 'fade';

        // Decide which pointer to use
        if ('king-addons-grid-tax-style-1' === $settings['element_tax_style']) {
            $class .= ' king-addons-pointer-' . $tax1_pointer;
            $class .= ' king-addons-pointer-line-fx king-addons-pointer-fx-' . $tax1_pointer_animation;
        } else {
            $class .= ' king-addons-pointer-' . $tax2_pointer;
            $class .= ' king-addons-pointer-line-fx king-addons-pointer-fx-' . $tax2_pointer_animation;
        }

        echo '<div class="' . esc_attr($class . ' ' . $settings['element_tax_style']) . '">';
        echo '  <div class="inner-block">';

        if ('before' === $settings['element_extra_text_pos']) {
            echo '<span class="king-addons-grid-extra-text-left">' . esc_html($settings['element_extra_text']) . '</span>';
        }
        if ('before' === $settings['element_extra_icon_pos']) {
            ob_start();
            Icons_Manager::render_icon($settings['element_extra_icon'], ['aria-hidden' => 'true']);
            $icon = ob_get_clean();
            echo '<span class="king-addons-grid-extra-icon-left">' . $icon . '</span>';
        }

        $count = 0;
        $sep = esc_html($settings['element_tax_sep'] ?? '');
        $pointer = (($tax1_pointer !== 'none') || ($tax2_pointer !== 'none')) ? 'king-addons-pointer-item' : '';

        foreach ($terms as $term) {
            // Possibly set custom colors (premium) for tax style 1
            if (
                'yes' === ($this->get_settings()['tax1_custom_color_switcher'] ?? '') &&
                'king-addons-grid-tax-style-1' === $settings['element_tax_style']
            ) {
                $cfc_text = get_term_meta($term->term_id, $this->get_settings()['tax1_custom_color_field_text'], true);
                $cfc_bg = get_term_meta($term->term_id, $this->get_settings()['tax1_custom_color_field_bg'], true);
                $color_css = "color:$cfc_text; background-color:$cfc_bg; border-color:$cfc_bg;";
                $custom_selector = '.elementor-element' . $this->get_unique_selector() .
                    ' .king-addons-grid-tax-style-1 .inner-block a.king-addons-tax-id-' . esc_attr($term->term_id);
                echo '<style>' . $custom_selector . '{' . $color_css . '}</style>';
            }

            echo '<a class="' . $pointer . ' king-addons-tax-id-' . esc_attr($term->term_id) . '" href="' . esc_url(get_term_link($term->term_id)) . '">';
            echo esc_html($term->name);
            if (++$count !== count($terms)) {
                echo '<span class="tax-sep">' . $sep . '</span>';
            }
            echo '</a>';
        }

        if ('after' === $settings['element_extra_icon_pos']) {
            ob_start();
            Icons_Manager::render_icon($settings['element_extra_icon'], ['aria-hidden' => 'true']);
            $icon = ob_get_clean();
            echo '<span class="king-addons-grid-extra-icon-right">' . $icon . '</span>';
        }
        if ('after' === $settings['element_extra_text_pos']) {
            echo '<span class="king-addons-grid-extra-text-right">' . esc_html($settings['element_extra_text']) . '</span>';
        }

        echo '  </div>';
        echo '</div>';
    }

    public function get_elements($type, $settings, $class, $post_id)
    {
        // Fallback for certain pro elements
        if (in_array($type, ['pro-lk', 'pro-shr', 'pro-cf'])) {
            $type = 'title';
        }

        switch ($type) {
            case 'title':
                $this->render_post_title($settings, $class);
                break;
            case 'content':
                $this->render_post_content($settings, $class);
                break;
            case 'excerpt':
                $this->render_post_excerpt($settings, $class);
                break;
            case 'date':
                $this->render_post_date($settings, $class);
                break;
            case 'time':
                $this->render_post_time($settings, $class);
                break;
            case 'author':
                $this->render_post_author($settings, $class);
                break;
            case 'comments':
                $this->render_post_comments($settings, $class);
                break;
            case 'read-more':
                $this->render_post_read_more($settings, $class);
                break;
            case 'likes':
                $this->render_post_likes($settings, $class, $post_id);
                break;
            case 'sharing':
                $this->render_post_sharing_icons($settings, $class);
                break;
            case 'lightbox':
                $this->render_post_lightbox($settings, $class, $post_id);
                break;
            case 'custom-field':
                $this->render_post_custom_field($settings, $class, $post_id);
                break;
            case 'separator':
                $this->render_post_element_separator($settings, $class);
                break;
            default:
                $this->render_post_taxonomies($settings, $class, $post_id);
        }
    }

    public function get_elements_by_location($location, $settings, $post_id)
    {
        $locations = [];

        // Group elements by location; also group "over" by vertical alignment
        foreach ($settings['grid_elements'] as $data) {
            $place = $data['element_location'];
            $alignV = $data['element_align_vr'];

            if (!king_addons_freemius()->can_use_premium_code__premium_only()) {
                $alignV = 'middle';
            }

            if (!isset($locations[$place])) {
                $locations[$place] = [];
            }

            if ('over' === $place) {
                if (!isset($locations[$place][$alignV])) {
                    $locations[$place][$alignV] = [];
                }
                $locations[$place][$alignV][] = $data;
            } else {
                $locations[$place][] = $data;
            }
        }

        // Render each group of elements
        if (!empty($locations[$location])) {
            if ('over' === $location) {
                foreach ($locations[$location] as $align => $elements) {
                    if ('middle' === $align) {
                        echo '<div class="king-addons-cv-container"><div class="king-addons-cv-outer"><div class="king-addons-cv-inner">';
                    }
                    echo '<div class="king-addons-grid-media-hover-' . esc_attr($align) . ' elementor-clearfix">';

                    foreach ($elements as $data) {
                        $itemClass = 'king-addons-grid-item-' . $data['element_select']
                            . ' elementor-repeater-item-' . $data['_id']
                            . ' king-addons-grid-item-display-' . $data['element_display']
                            . ' king-addons-grid-item-align-' . $data['element_align_hr']
                            . $this->get_animation_class($data, 'element');
                        $this->get_elements($data['element_select'], $data, $itemClass, $post_id);
                    }
                    echo '</div>';

                    if ('middle' === $align) {
                        echo '</div></div></div>';
                    }
                }
            } else {
                echo '<div class="king-addons-grid-item-' . esc_attr($location) . '-content elementor-clearfix">';
                foreach ($locations[$location] as $data) {
                    $itemClass = 'king-addons-grid-item-' . $data['element_select']
                        . ' elementor-repeater-item-' . $data['_id']
                        . ' king-addons-grid-item-display-' . $data['element_display']
                        . ' king-addons-grid-item-align-' . $data['element_align_hr'];
                    $this->get_elements($data['element_select'], $data, $itemClass, $post_id);
                }
                echo '</div>';
            }
        }
    }

    public function render_grid_filters($settings)
    {
        $taxonomy = $settings['filters_select'] ?? '';

        // Ensure we have some taxonomy selected
        if ('' === $taxonomy || !isset($settings['query_taxonomy_' . $taxonomy])) {
            return;
        }

        $custom_filters = $settings['query_taxonomy_' . $taxonomy];

        // Fall back for non-premium
        if (!king_addons_freemius()->can_use_premium_code__premium_only()) {
            $settings['filters_default_filter'] = '';
            $settings['filters_icon_align'] = '';
            $settings['filters_count'] = '';
            $settings['filters_pointer'] = 'none';
            $settings['filters_pointer_animation'] = 'none';
        }

        $left_icon = ('left' === $settings['filters_icon_align'])
            ? '<i class="' . esc_attr($settings['filters_icon']['value']) . ' king-addons-grid-filters-icon-left"></i>' : '';
        $right_icon = ('right' === $settings['filters_icon_align'])
            ? '<i class="' . esc_attr($settings['filters_icon']['value']) . ' king-addons-grid-filters-icon-right"></i>' : '';
        $left_separator = ('left' === $settings['filters_separator_align'])
            ? '<em class="king-addons-grid-filters-sep">' . esc_html($settings['filters_separator']) . '</em>' : '';
        $right_separator = ('right' === $settings['filters_separator_align'])
            ? '<em class="king-addons-grid-filters-sep">' . esc_html($settings['filters_separator']) . '</em>' : '';
        $post_count = ('yes' === $settings['filters_count'])
            ? '<sup data-brackets="' . esc_attr($settings['filters_count_brackets']) . '"></sup>'
            : '';

        $pointer_class = ' king-addons-pointer-' . $settings['filters_pointer'];
        $pointer_class .= ' king-addons-pointer-line-fx king-addons-pointer-fx-' . $settings['filters_pointer_animation'];
        $pointer_item_class = ('none' !== $settings['filters_pointer']) ? 'king-addons-pointer-item' : '';

        echo '<ul class="king-addons-grid-filters elementor-clearfix king-addons-grid-filters-sep-' . esc_attr($settings['filters_separator_align']) . '">';

        // "Show All" button if needed
        if ('yes' === $settings['filters_all'] && 'yes' !== $settings['filters_linkable']) {
            echo '<li class="' . esc_attr($pointer_class) . '">';
            echo '  <span data-filter="*" class="king-addons-grid-filters-item king-addons-active-filter ' . esc_attr($pointer_item_class) . '">';
            echo $left_icon . esc_html($settings['filters_all_text']) . $right_icon . $post_count;
            echo '  </span>' . $right_separator;
            echo '</li>';
        }

        $q = get_queried_object();
        $categoryName = (is_category() && $q) ? strtolower($q->name) : 'no-category';
        $parent_filters = [];

        // Dynamic
        if ($settings['query_selection'] === 'dynamic' && !empty($custom_filters)) {
            foreach ($custom_filters as $term_id) {
                $filter = get_term_by('id', $term_id, $taxonomy);
                $data_attr = ('post_tag' === $taxonomy) ? 'tag-' . $filter->slug : $taxonomy . '-' . $filter->slug;
                $tax_data = ('post_tag' === $taxonomy) ? 'tag' : $taxonomy;
                $active = (strpos($data_attr, $categoryName) !== false) ? 'king-addons-active-filter' : '';

                if (0 === $filter->parent) {
                    $children = get_term_children($filter->term_id, $taxonomy);
                    $data_role = !empty($children) ? ' data-role="parent"' : '';

                    echo '<li' . $data_role . ' class="' . esc_attr($pointer_class) . '">';
                    if ('yes' !== $settings['filters_linkable']) {
                        echo $left_separator . '<span class="' . $pointer_item_class . '"  data-ajax-filter=\'' . json_encode([$tax_data, $filter->slug]) . '\' data-filter=".' . esc_attr(urldecode($data_attr)) . '">';
                        echo $left_icon . esc_html($filter->name) . $right_icon . $post_count . '</span>' . $right_separator;
                    } else {
                        echo $left_separator . '<a class="' . $active . ' ' . $pointer_item_class . '" href="' . esc_url(get_term_link($filter->term_id, $taxonomy)) . '">';
                        echo $left_icon . esc_html($filter->name) . $right_icon . $post_count . '</a>' . $right_separator;
                    }
                    echo '</li>';
                } else {
                    $parent_filters[] = $filter->parent;
                }
            }
        } else {
            // All terms from taxonomy
            $all_filters = get_terms($taxonomy);
            foreach ((array)$all_filters as $filter) {
                $data_attr = ('post_tag' === $taxonomy) ? 'tag-' . $filter->slug : $taxonomy . '-' . $filter->slug;
                $tax_data = ('post_tag' === $taxonomy) ? 'tag' : $taxonomy;
                $active = (strpos($data_attr, $categoryName) !== false) ? 'king-addons-active-filter' : '';
                $hidden = $this->get_hidden_filter_class($filter->slug, $settings);

                if (0 === $filter->parent) {
                    $children = get_term_children($filter->term_id, $taxonomy);
                    $data_role = !empty($children) ? ' data-role="parent"' : '';

                    echo '<li' . $data_role . ' class="' . esc_attr($pointer_class . $hidden) . '">';
                    if ('yes' !== $settings['filters_linkable']) {
                        echo $left_separator . '<span class="' . $pointer_item_class . '" data-ajax-filter=\'' . json_encode([$tax_data, $filter->slug]) . '\' data-filter=".' . esc_attr(urldecode($data_attr)) . '">';
                        echo $left_icon . esc_html($filter->name) . $right_icon . $post_count . '</span>' . $right_separator;
                    } else {
                        echo $left_separator . '<a class="' . $active . ' ' . $pointer_item_class . '" href="' . esc_url(get_term_link($filter->term_id, $taxonomy)) . '" data-ajax-filter=\'' . json_encode([$tax_data, $filter->slug]) . '\' data-filter=".' . esc_attr(urldecode($data_attr)) . '">';
                        echo $left_icon . esc_html($filter->name) . $right_icon . $post_count . '</a>' . $right_separator;
                    }
                    echo '</li>';
                } else {
                    $parent_filters[] = $filter->parent;
                }
            }
        }

        // Child terms as sub-filters if not linkable
        if ('yes' !== $settings['filters_linkable']) {
            foreach (array_unique($parent_filters) as $parent_filter) {
                $parent = get_term_by('id', $parent_filter, $taxonomy);
                $children = get_term_children($parent_filter, $taxonomy);
                $data_attr = ('post_tag' === $taxonomy) ? 'tag-' . $parent->slug : $taxonomy . '-' . $parent->slug;
                $tax_data = ('post_tag' === $taxonomy) ? 'tag' : $taxonomy;

                echo '<ul data-parent=".' . esc_attr(urldecode($data_attr)) . '" class="king-addons-sub-filters">';
                echo '  <li data-role="back" class="' . esc_attr($pointer_class) . '">';
                echo '      <span class="king-addons-back-filter" data-ajax-filter=\'' . json_encode([$tax_data, $parent->slug]) . '\' data-filter=".' . esc_attr(urldecode($data_attr)) . '">';
                echo '          <i class="fas fa-long-arrow-alt-left"></i>&nbsp;&nbsp;' . esc_html__('Back', 'king-addons');
                echo '      </span>';
                echo '  </li>';

                foreach ($children as $child) {
                    $sub_filter = get_term_by('id', $child, $taxonomy);
                    $child_attr = ('post_tag' === $taxonomy)
                        ? 'tag-' . $sub_filter->slug
                        : $taxonomy . '-' . $sub_filter->slug;

                    echo '<li data-role="sub" class="' . esc_attr($pointer_class) . '">';
                    echo $left_separator . '<span class="' . $pointer_item_class . '" data-ajax-filter=\'' . json_encode([$tax_data, $sub_filter->slug]) . '\' data-filter=".' . esc_attr(urldecode($child_attr)) . '">';
                    echo $left_icon . esc_html($sub_filter->name) . $right_icon . $post_count;
                    echo '</span>' . $right_separator;
                    echo '</li>';
                }
                echo '</ul>';
            }
        }

        echo '</ul>';
    }

    public function get_hidden_filter_class($slug, $settings)
    {
        // Query posts & see if the category is used
        $posts = new WP_Query($this->get_main_query_args());
        $visible_slugs = [];

        if ($posts->have_posts()) {
            while ($posts->have_posts()) {
                $posts->the_post();
                $cats = get_the_category();
                foreach ($cats as $c) {
                    $visible_slugs[] = $c->slug;
                }
            }
            $visible_slugs = array_unique($visible_slugs);
            wp_reset_postdata();
        }

        return (!in_array($slug, $visible_slugs) && 'yes' === $settings['filters_hide_empty'])
            ? ' king-addons-hidden-element'
            : '';
    }

    public function render_grid_pagination($settings)
    {
        // Bail if pagination off, or only one page, or in slider mode
        if (
            'yes' !== $settings['layout_pagination'] ||
            1 === $this->get_max_num_pages() ||
            'slider' === $settings['layout_select']
        ) {
            return;
        }

        global $paged;
        $pages = $this->get_max_num_pages();

        $paged = get_query_var('paged') ?: (get_query_var('page') ?: 1);

        // Ensure no premium pagination type if not premium
        if (!king_addons_freemius()->can_use_premium_code__premium_only()) {
            if ('pro-is' === $settings['pagination_type']) {
                $settings['pagination_type'] = 'default';
            }
        }

        echo '<div class="king-addons-grid-pagination elementor-clearfix king-addons-grid-pagination-' . esc_attr($settings['pagination_type']) . '">';

        // 1) Default older/newer
        if ('default' === $settings['pagination_type']) {
            if ($paged < $pages) {
                echo '<a href="' . esc_url(get_pagenum_link($paged + 1)) . '" class="king-addons-prev-post-link">';
                echo Core::getIcon($settings['pagination_on_icon'], 'left');
                echo esc_html($settings['pagination_older_text']);
                echo '</a>';
            } elseif ('yes' === $settings['pagination_disabled_arrows']) {
                echo '<span class="king-addons-prev-post-link king-addons-disabled-arrow">';
                echo Core::getIcon($settings['pagination_on_icon'], 'left');
                echo esc_html($settings['pagination_older_text']);
                echo '</span>';
            }

            if ($paged > 1) {
                echo '<a href="' . esc_url(get_pagenum_link($paged - 1)) . '" class="king-addons-next-post-link">';
                echo esc_html($settings['pagination_newer_text']);
                echo Core::getIcon($settings['pagination_on_icon'], 'right');
                echo '</a>';
            } elseif ('yes' === $settings['pagination_disabled_arrows']) {
                echo '<span class="king-addons-next-post-link king-addons-disabled-arrow">';
                echo esc_html($settings['pagination_newer_text']);
                echo Core::getIcon($settings['pagination_on_icon'], 'right');
                echo '</span>';
            }

            // 2) Numbered pagination
        } elseif ('numbered' === $settings['pagination_type']) {
            $range = $settings['pagination_range'];
            $showitems = ($range * 2) + 1;

            if (1 !== $pages) {
                // Prev / First arrows if needed
                if ('yes' === $settings['pagination_prev_next'] || 'yes' === $settings['pagination_first_last']) {
                    echo '<div class="king-addons-grid-pagination-left-arrows">';
                    if ('yes' === $settings['pagination_first_last']) {
                        if ($paged >= 2) {
                            echo '<a href="' . esc_url(get_pagenum_link()) . '" class="king-addons-first-page">';
                            echo Core::getIcon($settings['pagination_fl_icon'], 'left');
                            echo '<span>' . esc_html($settings['pagination_first_text']) . '</span>';
                            echo '</a>';
                        } elseif ('yes' === $settings['pagination_disabled_arrows']) {
                            echo '<span class="king-addons-first-page king-addons-disabled-arrow">';
                            echo Core::getIcon($settings['pagination_fl_icon'], 'left');
                            echo '<span>' . esc_html($settings['pagination_first_text']) . '</span>';
                            echo '</span>';
                        }
                    }
                    if ('yes' === $settings['pagination_prev_next']) {
                        if ($paged > 1) {
                            echo '<a href="' . esc_url(get_pagenum_link($paged - 1)) . '" class="king-addons-prev-page">';
                            echo Core::getIcon($settings['pagination_pn_icon'], 'left');
                            echo '<span>' . esc_html($settings['pagination_prev_text']) . '</span>';
                            echo '</a>';
                        } elseif ('yes' === $settings['pagination_disabled_arrows']) {
                            echo '<span class="king-addons-prev-page king-addons-disabled-arrow">';
                            echo Core::getIcon($settings['pagination_pn_icon'], 'left');
                            echo '<span>' . esc_html($settings['pagination_prev_text']) . '</span>';
                            echo '</span>';
                        }
                    }
                    echo '</div>';
                }

                // Loop through pages
                for ($i = 1; $i <= $pages; $i++) {
                    if (1 !== $pages && (
                            !($i >= $paged + $range + 1 || $i <= $paged - $range - 1) ||
                            $pages <= $showitems
                        )) {
                        if ($paged === $i) {
                            echo '<span class="king-addons-grid-current-page">' . esc_html($i) . '</span>';
                        } else {
                            echo '<a href="' . esc_url(get_pagenum_link($i)) . '">' . esc_html($i) . '</a>';
                        }
                    }
                }

                // Next / Last arrows if needed
                if ('yes' === $settings['pagination_prev_next'] || 'yes' === $settings['pagination_first_last']) {
                    echo '<div class="king-addons-grid-pagination-right-arrows">';
                    if ('yes' === $settings['pagination_prev_next']) {
                        if ($paged < $pages) {
                            echo '<a href="' . esc_url(get_pagenum_link($paged + 1)) . '" class="king-addons-next-page">';
                            echo '<span>' . esc_html($settings['pagination_next_text']) . '</span>';
                            echo Core::getIcon($settings['pagination_pn_icon'], 'right');
                            echo '</a>';
                        } elseif ('yes' === $settings['pagination_disabled_arrows']) {
                            echo '<span class="king-addons-next-page king-addons-disabled-arrow">';
                            echo '<span>' . esc_html($settings['pagination_next_text']) . '</span>';
                            echo Core::getIcon($settings['pagination_pn_icon'], 'right');
                            echo '</span>';
                        }
                    }
                    if ('yes' === $settings['pagination_first_last']) {
                        if ($paged <= $pages - 1) {
                            echo '<a href="' . esc_url(get_pagenum_link($pages)) . '" class="king-addons-last-page">';
                            echo '<span>' . esc_html($settings['pagination_last_text']) . '</span>';
                            echo Core::getIcon($settings['pagination_fl_icon'], 'right');
                            echo '</a>';
                        } elseif ('yes' === $settings['pagination_disabled_arrows']) {
                            echo '<span class="king-addons-last-page king-addons-disabled-arrow">';
                            echo '<span>' . esc_html($settings['pagination_last_text']) . '</span>';
                            echo Core::getIcon($settings['pagination_fl_icon'], 'right');
                            echo '</span>';
                        }
                    }
                    echo '</div>';
                }
            }

            // 3) "Load More" infinite style
        } else {
            echo '<a href="' . esc_url(get_pagenum_link($paged + 1)) . '" class="king-addons-load-more-btn" data-e-disable-page-transition>';
            echo esc_html($settings['pagination_load_more_text']);
            echo '</a>';

            echo '<div class="king-addons-pagination-loading">';
            switch ($settings['pagination_animation']) {
                case 'loader-1':
                    echo '<div class="king-addons-double-bounce">';
                    echo '  <div class="king-addons-child king-addons-double-bounce1"></div>';
                    echo '  <div class="king-addons-child king-addons-double-bounce2"></div>';
                    echo '</div>';
                    break;
                case 'loader-2':
                    echo '<div class="king-addons-wave">';
                    echo '  <div class="king-addons-rect king-addons-rect1"></div>';
                    echo '  <div class="king-addons-rect king-addons-rect2"></div>';
                    echo '  <div class="king-addons-rect king-addons-rect3"></div>';
                    echo '  <div class="king-addons-rect king-addons-rect4"></div>';
                    echo '  <div class="king-addons-rect king-addons-rect5"></div>';
                    echo '</div>';
                    break;
                case 'loader-3':
                    echo '<div class="king-addons-spinner king-addons-spinner-pulse"></div>';
                    break;
                case 'loader-4':
                    echo '<div class="king-addons-chasing-dots">';
                    echo '  <div class="king-addons-child king-addons-dot1"></div>';
                    echo '  <div class="king-addons-child king-addons-dot2"></div>';
                    echo '</div>';
                    break;
                case 'loader-5':
                    echo '<div class="king-addons-three-bounce">';
                    echo '  <div class="king-addons-child king-addons-bounce1"></div>';
                    echo '  <div class="king-addons-child king-addons-bounce2"></div>';
                    echo '  <div class="king-addons-child king-addons-bounce3"></div>';
                    echo '</div>';
                    break;
                case 'loader-6':
                    echo '<div class="king-addons-fading-circle">';
                    for ($i = 1; $i <= 12; $i++) {
                        echo '<div class="king-addons-circle king-addons-circle' . $i . '"></div>';
                    }
                    echo '</div>';
                    break;
                default:
                    break;
            }
            echo '</div>';

            echo '<p class="king-addons-pagination-finish">' . esc_html($settings['pagination_finish_text']) . '</p>';
        }

        echo '</div>';
    }

    public function add_grid_settings($settings)
    {
        // If premium not available, fallback
        if (!king_addons_freemius()->can_use_premium_code__premium_only()) {
            if ('pro-ms' === $settings['layout_select']) {
                $settings['layout_select'] = 'fitRows';
            }
            $settings['filters_deeplinking'] = '';
            $settings['filters_count'] = '';
            $settings['filters_default_filter'] = '';
            if (in_array($settings['filters_animation'], ['pro-fd', 'pro-fs'])) {
                $settings['filters_animation'] = 'zoom';
            }
        }

        // Only allow "stick_last_element_to_bottom" if layout_select == fitRows
        $stick_last_element = ('fitRows' === $settings['layout_select'])
            ? $settings['stick_last_element_to_bottom']
            : 'no';

        // Prepare gutter sizes
        $gutter_hr_widescreen = $settings['layout_gutter_hr_widescreen']['size'] ?? $settings['layout_gutter_hr']['size'];
        $gutter_hr_desktop = $settings['layout_gutter_hr']['size'] ?? 0;
        $gutter_hr_laptop = $settings['layout_gutter_hr_laptop']['size'] ?? $gutter_hr_desktop;
        $gutter_hr_tablet_ex = $settings['layout_gutter_hr_tablet_extra']['size'] ?? $gutter_hr_laptop;
        $gutter_hr_tablet = $settings['layout_gutter_hr_tablet']['size'] ?? $gutter_hr_tablet_ex;
        $gutter_hr_mobile_ex = $settings['layout_gutter_hr_mobile_extra']['size'] ?? $gutter_hr_tablet;
        $gutter_hr_mobile = $settings['layout_gutter_hr_mobile']['size'] ?? $gutter_hr_mobile_ex;
        $gutter_vr_widescreen = $settings['layout_gutter_vr_widescreen']['size'] ?? $settings['layout_gutter_vr']['size'];
        $gutter_vr_desktop = $settings['layout_gutter_vr']['size'] ?? 0;
        $gutter_vr_laptop = $settings['layout_gutter_vr_laptop']['size'] ?? $gutter_vr_desktop;
        $gutter_vr_tablet_ex = $settings['layout_gutter_vr_tablet_extra']['size'] ?? $gutter_vr_laptop;
        $gutter_vr_tablet = $settings['layout_gutter_vr_tablet']['size'] ?? $gutter_vr_tablet_ex;
        $gutter_vr_mobile_ex = $settings['layout_gutter_vr_mobile_extra']['size'] ?? $gutter_vr_tablet;
        $gutter_vr_mobile = $settings['layout_gutter_vr_mobile']['size'] ?? $gutter_vr_mobile_ex;

        $layout_settings = [
            'layout' => $settings['layout_select'],
            'stick_last_element_to_bottom' => $stick_last_element,
            'columns_desktop' => $settings['layout_columns'],
            'gutter_hr' => $gutter_hr_desktop,
            'gutter_hr_mobile' => $gutter_hr_mobile,
            'gutter_hr_mobile_extra' => $gutter_hr_mobile_ex,
            'gutter_hr_tablet' => $gutter_hr_tablet,
            'gutter_hr_tablet_extra' => $gutter_hr_tablet_ex,
            'gutter_hr_laptop' => $gutter_hr_laptop,
            'gutter_hr_widescreen' => $gutter_hr_widescreen,
            'gutter_vr' => $gutter_vr_desktop,
            'gutter_vr_mobile' => $gutter_vr_mobile,
            'gutter_vr_mobile_extra' => $gutter_vr_mobile_ex,
            'gutter_vr_tablet' => $gutter_vr_tablet,
            'gutter_vr_tablet_extra' => $gutter_vr_tablet_ex,
            'gutter_vr_laptop' => $gutter_vr_laptop,
            'gutter_vr_widescreen' => $gutter_vr_widescreen,
            'animation' => $settings['layout_animation'],
            'animation_duration' => $settings['layout_animation_duration'],
            'animation_delay' => $settings['layout_animation_delay'],
            'deeplinking' => $settings['filters_deeplinking'],
            'filters_linkable' => $settings['filters_linkable'],
            'filters_default_filter' => $settings['filters_default_filter'],
            'filters_count' => $settings['filters_count'],
            'filters_hide_empty' => $settings['filters_hide_empty'],
            'filters_animation' => $settings['filters_animation'],
            'filters_animation_duration' => $settings['filters_animation_duration'],
            'filters_animation_delay' => $settings['filters_animation_delay'],
            'pagination_type' => $settings['pagination_type'],
            'pagination_max_pages' => $this->get_max_num_pages(),
        ];

        if ('list' === $settings['layout_select']) {
            $layout_settings['media_align'] = $settings['layout_list_align'];
            $layout_settings['media_width'] = $settings['layout_list_media_width']['size'];
            $layout_settings['media_distance'] = $settings['layout_list_media_distance']['size'];
        }

        // Restrict pop-up if not premium
        if (!king_addons_freemius()->can_use_premium_code__premium_only()) {
            $settings['lightbox_popup_thumbnails'] = '';
            $settings['lightbox_popup_thumbnails_default'] = '';
            $settings['lightbox_popup_sharing'] = '';
        }

        // Lightbox config
        $layout_settings['lightbox'] = [
            'selector' => '.king-addons-grid-image-wrap',
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
            'download' => $settings['lightbox_popup_download'],
        ];

        if ('yes' === ($settings['filters_experiment'] ?? '')) {
            // Potentially store entire $settings if needed
            $layout_settings['grid_settings'] = $settings;
        }

        $this->add_render_attribute('grid-settings', [
            'data-settings' => wp_json_encode($layout_settings),
        ]);
    }

    public function add_slider_settings($settings)
    {
        $slider_is_rtl = is_rtl();
        $slider_dir = $slider_is_rtl ? 'rtl' : 'ltr';

        // Fall back for non-premium
        if (!king_addons_freemius()->can_use_premium_code__premium_only()) {
            $settings['layout_slider_autoplay'] = '';
            $settings['layout_slider_autoplay_duration'] = 0;
            $settings['layout_slider_pause_on_hover'] = '';
        }

        if (in_array($settings['layout_slider_amount'], ['pro-3', 'pro-4', 'pro-5', 'pro-6'])) {
            $settings['layout_slider_amount'] = 1; // fallback
        }

        $slider_options = [
            'rtl' => $slider_is_rtl,
            'infinite' => ('yes' === $settings['layout_slider_loop']),
            'speed' => absint($settings['layout_slider_effect_duration'] * 1000),
            'arrows' => true,
            'dots' => true,
            'autoplay' => ('yes' === $settings['layout_slider_autoplay']),
            'autoplaySpeed' => absint($settings['layout_slider_autoplay_duration'] * 1000),
            'pauseOnHover' => $settings['layout_slider_pause_on_hover'],
            'prevArrow' => '#king-addons-grid-slider-prev-' . $this->get_id(),
            'nextArrow' => '#king-addons-grid-slider-next-' . $this->get_id(),
            'sliderSlidesToScroll' => $settings['layout_slides_to_scroll'] ? absint($settings['layout_slides_to_scroll']) : 1,
        ];

        // Restrict pop-up for non-premium
        if (!king_addons_freemius()->can_use_premium_code__premium_only()) {
            $settings['lightbox_popup_thumbnails'] = '';
            $settings['lightbox_popup_thumbnails_default'] = '';
            $settings['lightbox_popup_sharing'] = '';
        }

        // Lightbox config
        $slider_options['lightbox'] = [
            'selector' => 'article:not(.slick-cloned) .king-addons-grid-image-wrap',
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
            'download' => $settings['lightbox_popup_download'],
        ];

        // Enable fade effect for single-slide
        if ($settings['layout_slider_amount'] === 1 && $settings['layout_slider_effect'] === 'fade') {
            $slider_options['fade'] = true;
        }

        $this->add_render_attribute('slider-settings', [
            'dir' => esc_attr($slider_dir),
            'data-slick' => wp_json_encode($slider_options),
        ]);
    }

    protected function render()
    {
        $settings = $this->get_settings();
        $posts = new WP_Query($this->get_main_query_args());

        if ($posts->have_posts()) {
            // Render filters if not slider
            if ('slider' !== $settings['layout_select']) {
                $this->render_grid_filters($settings);
                $this->add_grid_settings($settings);
                $render_attribute = $this->get_render_attribute_string('grid-settings');
            } else {
                // If slider
                $this->add_slider_settings($settings);
                $render_attribute = $this->get_render_attribute_string('slider-settings');
            }

            echo '<section class="king-addons-grid elementor-clearfix" ' . $render_attribute . '>';

            while ($posts->have_posts()) {
                $posts->the_post();
                $post_class = implode(' ', get_post_class('king-addons-grid-item elementor-clearfix', get_the_ID()));

                echo '<article class="' . esc_attr($post_class) . '">';
                // Check password
                $this->render_password_protected_input();

                echo '<div class="king-addons-grid-item-inner">';
                // Location: ABOVE
                $this->get_elements_by_location('above', $settings, get_the_ID());

                // If has featured image, render
                if (has_post_thumbnail()) {
                    echo '<div class="king-addons-grid-media-wrap' . esc_attr($this->get_image_effect_class($settings)) . '" data-overlay-link="' . esc_attr($settings['overlay_post_link']) . '">';
                    $this->render_post_thumbnail($settings);

                    echo '<div class="king-addons-grid-media-hover king-addons-animation-wrap">';
                    $this->render_media_overlay($settings);
                    $this->get_elements_by_location('over', $settings, get_the_ID());
                    echo '</div>';

                    echo '</div>';
                }

                // Location: BELOW
                $this->get_elements_by_location('below', $settings, get_the_ID());
                echo '</div>';

                echo '</article>';
            }
            echo '</section>';

            // Reset
            wp_reset_postdata();

            // If slider, render nav
            if ('slider' === $settings['layout_select']) {
                echo '<div class="king-addons-grid-slider-arrow-container">';
                echo '  <div class="king-addons-grid-slider-prev-arrow king-addons-grid-slider-arrow" id="king-addons-grid-slider-prev-' . esc_attr($this->get_id()) . '">' . Core::getIcon($settings['layout_slider_nav_icon'], '') . '</div>';
                echo '  <div class="king-addons-grid-slider-next-arrow king-addons-grid-slider-arrow" id="king-addons-grid-slider-next-' . esc_attr($this->get_id()) . '">' . Core::getIcon($settings['layout_slider_nav_icon'], '') . '</div>';
                echo '</div>';

                echo '<div class="king-addons-grid-slider-dots"></div>';
            }

            // Render pagination if needed
            $this->render_grid_pagination($settings);
        } else {
            // If no posts found
            if ('dynamic' === $settings['query_selection']) {
                echo '<h2>' . esc_html($settings['query_not_found_text']) . '</h2>';
            }
        }
    }
}