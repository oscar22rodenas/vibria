<?php

namespace King_Addons;

use Elementor\Embed;
use Elementor\Icons_Manager;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;
use Elementor\Group_Control_Image_Size;
use WP_Query;

if (!defined('ABSPATH')) {
    exit;
}

class Media_Grid extends Widget_Base
{

    public function get_name()
    {
        return 'king-addons-media-grid';
    }

    public function get_title()
    {
        return esc_html__('Image Grid & Slider/Carousel/Gallery', 'king-addons');
    }

    public function get_icon()
    {
        return 'king-addons-icon king-addons-media-grid';
    }

    public function get_categories()
    {
        return ['king-addons'];
    }

    public function get_keywords()
    {
        return ['king addons', 'king', 'addons', 'kingaddons', 'king-addons', 'carousel', 'slider', 'img', 'gr', 'filter', 'loop',
            'image gallery', 'image slider', 'image carousel', 'image grid', 'media grid', 'slick', 'tile', 'tiles', 'gallery',
            'masonry grid', 'isotope', 'masonry', 'grid', 'filterable grid', 'media', 'image', 'filterable'];
    }

    public function get_script_depends()
    {
        return [
            KING_ADDONS_ASSETS_UNIQUE_KEY . '-grid-media',
            KING_ADDONS_ASSETS_UNIQUE_KEY . '-isotope-kng',
            KING_ADDONS_ASSETS_UNIQUE_KEY . '-slick-slick',
            KING_ADDONS_ASSETS_UNIQUE_KEY . '-lightgallery-lightgallery'
        ];
    }

    public function get_style_depends()
    {
        return [
            KING_ADDONS_ASSETS_UNIQUE_KEY . '-grid-grid',
            KING_ADDONS_ASSETS_UNIQUE_KEY . '-slick-helper',
            KING_ADDONS_ASSETS_UNIQUE_KEY . '-animation-button',
            KING_ADDONS_ASSETS_UNIQUE_KEY . '-animation-general',
            KING_ADDONS_ASSETS_UNIQUE_KEY . '-animation-timing',
            KING_ADDONS_ASSETS_UNIQUE_KEY . '-animation-loading',
            KING_ADDONS_ASSETS_UNIQUE_KEY . '-lightgallery-lightgallery',
            KING_ADDONS_ASSETS_UNIQUE_KEY . '-general-general',
        ];
    }

    public function get_custom_help_url()
    {
        return 'https://kingaddons.com/';
    }

    public function has_widget_inner_wrapper(): bool
    {
        return true;
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
                    'pro-cc' => esc_html__('Comment Count (Pro)', 'king-addons')
                ],
                'condition' => [
                    'query_randomize!' => 'rand',
                    'query_selection!' => 'manual'
                ]
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
                'default' => 4,
                'widescreen_default' => 4,
                'laptop_default' => 4,
                'tablet_extra_default' => 4,
                'tablet_default' => 3,
                'mobile_extra_default' => 3,
                'mobile_default' => 1,
                'options' => [
                    1 => esc_html__('One', 'king-addons'),
                    2 => esc_html__('Two', 'king-addons'),
                    3 => esc_html__('Three', 'king-addons'),
                    4 => esc_html__('Four', 'king-addons'),
                    'pro-5' => esc_html__('Five (Pro)', 'king-addons'),
                    'pro-6' => esc_html__('Six (Pro)', 'king-addons'),
                    'pro-7' => esc_html__('Seven (Pro)', 'king-addons'),
                    'pro-8' => esc_html__('Eight (Pro)', 'king-addons'),
                ],
                'prefix_class' => 'king-addons-grid-columns-%s',
                'separator' => 'before',
                'render_type' => 'template',
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
                    'default' => esc_html__('Default', 'king-addons'),
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
                'frontend_available' => true,
                'render_type' => 'template',
                'separator' => 'before',
                'condition' => [
                    'layout_select' => 'slider',
                ],
            ]
        );
    }

    public function add_control_layout_slider_rows()
    {
        /** @noinspection PhpDuplicateArrayKeysInspection */
        $this->add_control(
            'layout_slider_rows',
            [
                'label' => esc_html__('Rows (Carousel)', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'label_block' => false,
                'default' => 1,
                'classes' => 'king-addons-pro-control no-distance',
                'options' => [
                    1 => esc_html__('One', 'king-addons'),
                    1 => esc_html__('Two (Pro)', 'king-addons'),
                    1 => esc_html__('Three (Pro)', 'king-addons'),
                ],
                'frontend_available' => true,
                'render_type' => 'template',
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
                'classes' => 'king-addons-pro-control no-distance'
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

    public function add_controls_group_layout_slider_autoplay()
    {
        $this->add_control(
            'layout_slider_autoplay',
            [
                'label' => sprintf(__('Autoplay %s', 'king-addons'), '<i class="eicon-pro-icon"></i>'),
                'type' => Controls_Manager::SWITCHER,
                'classes' => 'king-addons-pro-control no-distance'
            ]
        );
    }

    public function add_option_element_select()
    {
        return [
            'title' => esc_html__('Title', 'king-addons'),
            'caption' => esc_html__('Caption', 'king-addons'),
            'date' => esc_html__('Date', 'king-addons'),
            'time' => esc_html__('Time', 'king-addons'),
            'author' => esc_html__('Author', 'king-addons'),
            'lightbox' => esc_html__('Lightbox', 'king-addons'),
            'separator' => esc_html__('Separator', 'king-addons'),
            'pro-lk' => esc_html__('Likes (Pro)', 'king-addons'),
            'pro-shr' => esc_html__('Sharing (Pro)', 'king-addons'),
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
            'lightbox_popup_thumbnails_default',
            [
                'label' => sprintf(__('Show Thumbnails by Default %s', 'king-addons'), '<i class="eicon-pro-icon"></i>'),
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
        $this->add_control(
            'pagination_type',
            [
                'label' => esc_html__('Select Type', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'load-more',
                'options' => [
                    'default' => esc_html__('Default', 'king-addons'),
                    'numbered' => esc_html__('Numbered', 'king-addons'),
                    'load-more' => esc_html__('Load More Button', 'king-addons'),
                    'pro-is' => esc_html__('Infinite Scrolling (Pro)', 'king-addons'),
                ],
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


        $post_taxonomies = get_object_taxonomies('attachment', 'objects');
        $post_taxonomy_names = [];

        $this->add_control(
            'query_selection',
            [
                'label' => esc_html__('Selection', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'dynamic',
                'options' => [
                    'dynamic' => esc_html__('Auto', 'king-addons'),
                    'manual' => esc_html__('Manual', 'king-addons'),
                ],
            ]
        );

        $this->add_control_order_posts();

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
                    'query_selection!' => 'manual'
                ]
            ]
        );

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
                    'query_selection' => 'dynamic',
                ],
            ]
        );


        foreach ($post_taxonomies as $slug => $tax) {
            global $wp_taxonomies;
            $post_type = '';

            if (isset($wp_taxonomies[$slug]->object_type[0])) {
                $post_type = $wp_taxonomies[$slug]->object_type[0];
            }

            $post_taxonomy_names[$slug] = $tax->label;

            $this->add_control(
                'query_taxonomy_' . $slug,
                [
                    'label' => $tax,
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


        $this->add_control(
            'query_exclude_attachment',
            [
                'label' => esc_html__('Exclude Images', 'king-addons'),
                'type' => 'king-addons-ajax-select2',
                'options' => 'ajaxselect2/getPostsByPostType',
                'query_slug' => 'attachment',
                'multiple' => true,
                'label_block' => true,
                'condition' => [
                    'query_selection' => 'dynamic',
                ],
            ]
        );


        $this->add_control(
            'query_manual_attachment',
            [
                'label' => esc_html__('Add Images', 'king-addons'),
                'type' => Controls_Manager::GALLERY,
                'separator' => 'before',
                'dynamic' => [
                    'active' => true,
                ],
                'condition' => [
                    'query_selection' => 'manual',
                ],
            ]
        );

        $this->add_control(
            'query_posts_per_page',
            [
                'label' => esc_html__('Items Per Page', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'default' => 10,
                'min' => 0,
            ]
        );

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
                'default' => 'No Images Found!',
                'condition' => [
                    'query_selection' => 'dynamic',
                ]
            ]
        );

        $this->add_control_query_randomize();


        $this->end_controls_section();


        $this->start_controls_section(
            'section_grid_layout',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Layout', 'king-addons'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'layout_select',
            [
                'label' => esc_html__('Select Layout', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'masonry',
                'options' => [
                    'fitRows' => esc_html__('FitRows - Equal Height', 'king-addons'),
                    'masonry' => esc_html__('Masonry - Unlimited Height', 'king-addons'),
                    'slider' => esc_html__('Slider / Carousel', 'king-addons'),
                ],
                'render_type' => 'template',
                'label_block' => true
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
                    'raw' => 'Grid Columns option is fully supported<br> in the <strong><a href="https://kingaddons.com/pricing/?utm_source=kng-module-media-grid-settings-upgrade-pro&utm_medium=plugin&utm_campaign=kng" target="_blank">Pro version</a></strong>',

                    'content_classes' => 'king-addons-pro-notice',
                ]
            );
        }

        $this->add_responsive_control(
            'layout_gutter_hr',
            [
                'label' => esc_html__('Horizontal Gutter', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 10,
                ],
                'widescreen_default' => [
                    'size' => 10,
                ],
                'laptop_default' => [
                    'size' => 10,
                ],
                'tablet_extra_default' => [
                    'size' => 10,
                ],
                'tablet_default' => [
                    'size' => 10,
                ],
                'mobile_extra_default' => [
                    'size' => 10,
                ],
                'mobile_default' => [
                    'size' => 10,
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 150,
                    ],
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
                'default' => [
                    'size' => 10,
                ],
                'widescreen_default' => [
                    'size' => 10,
                ],
                'laptop_default' => [
                    'size' => 10,
                ],
                'tablet_extra_default' => [
                    'size' => 10,
                ],
                'tablet_default' => [
                    'size' => 10,
                ],
                'mobile_extra_default' => [
                    'size' => 10,
                ],
                'mobile_default' => [
                    'size' => 10,
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 150,
                    ],
                ],
                'condition' => [
                    'layout_select' => ['fitRows', 'masonry', 'list'],
                ],
            ]
        );

        $this->add_responsive_control(
            'layout_filters',
            [
                'label' => esc_html__('Show Filters', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => '',
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
                'description' => esc_html__('Please note that Pagination doesn\'t work in editor', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'return_value' => 'yes',
                'render_type' => 'template',
                'condition' => [
                    'layout_select!' => 'slider',
                ]
            ]
        );

        $this->add_control_layout_animation();


        Core::renderUpgradeProNotice($this, Controls_Manager::RAW_HTML, 'media-grid', 'layout_animation', ['pro-fd', 'pro-fs']);

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

        $this->add_control_layout_slider_rows();

        $this->add_control(
            'layout_slides_to_scroll',
            [
                'label' => esc_html__('Slides to Scroll', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 10,
                'default' => 2,
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
                    'size' => 0,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid .slick-slide' => 'margin-left: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}}.king-addons-grid-slider-rows-2 .king-addons-grid .king-addons-grid-item' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}}.king-addons-grid-slider-rows-3 .king-addons-grid .king-addons-grid-item' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .king-addons-grid .slick-list' => 'margin-left: -{{SIZE}}{{UNIT}};',
                    '{{WRAPPER}}.king-addons-grid-slider-rows-2 .king-addons-grid .slick-list' => 'margin-bottom: -{{SIZE}}{{UNIT}};',
                    '{{WRAPPER}}.king-addons-grid-slider-rows-3 .king-addons-grid .slick-list' => 'margin-bottom: -{{SIZE}}{{UNIT}};'
                ],
                'render_type' => 'template',
                'condition' => [
                    'layout_slider_amount!' => '1',
                    'layout_select' => 'slider',
                ]
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
                ]
            ]
        );

        $this->add_control_layout_slider_dots_position();


        Core::renderUpgradeProNotice($this, Controls_Manager::RAW_HTML, 'media-grid', 'layout_slider_dots_position', ['pro-vr']);

        $this->add_controls_group_layout_slider_autoplay();

        $this->add_control(
            'layout_slider_loop',
            [
                'label' => esc_html__('Infinite Loop', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'frontend_available' => true,
                'separator' => 'before',
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

        $repeater->add_control(
            'element_select',
            [
                'label' => esc_html__('Select Element', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'title',
                'options' => array_merge($this->add_option_element_select(), $post_taxonomy_names),
                'separator' => 'after'
            ]
        );


        Core::renderUpgradeProNotice($repeater, Controls_Manager::RAW_HTML, 'media-grid', 'element_select', ['pro-lk', 'pro-shr']);

        $repeater->add_control(
            'element_location',
            [
                'label' => esc_html__('Location', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'over',
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
                    'raw' => 'Vertical Align option is available<br> in the <strong><a href="https://kingaddons.com/pricing/?utm_source=kng-module-media-grid-settings-upgrade-pro&utm_medium=plugin&utm_campaign=kng" target="_blank">Pro version</a></strong>',

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
                    'element_select' => ['caption'],
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
                    'element_select' => ['title', 'caption'],
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
                'default' => 32,
                'min' => 8,
                'condition' => [
                    'element_select' => ['author'],
                    'element_show_avatar' => 'yes'
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
                        'caption',
                        'date',
                        'time',
                        'author',
                        'likes',
                        'sharing',
                        'lightbox',
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
                        'caption',
                        'date',
                        'time',
                        'author',
                        'likes',
                        'sharing',
                        'lightbox',
                        'separator',
                    ],
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
                        'caption',
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
                        'caption',
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
                        'caption',
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
                        'caption',
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


        Core::renderUpgradeProNotice($repeater, Controls_Manager::RAW_HTML, 'media-grid', 'element_animation', ['pro-slrt', 'pro-slxrt', 'pro-slbt', 'pro-sllt', 'pro-sltp', 'pro-slxlt', 'pro-sktp', 'pro-skrt', 'pro-skbt', 'pro-sklt', 'pro-scup', 'pro-scdn', 'pro-rllt', 'pro-rlrt']);

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


        Core::renderUpgradeProNotice($repeater, Controls_Manager::RAW_HTML, 'media-grid', 'element_animation_timing', Core::getAnimationTimingsConditionsPro());

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
            'element_disable_link',
            [
                'label' => esc_html__('Disable Link', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'condition' => [
                    'element_select' => 'title'
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
                        'element_select' => 'lightbox',
                        'element_location' => 'over',
                        'element_extra_icon_pos' => 'before',
                        'element_animation' => 'fade-in',
                        'element_animation_size' => 'large',
                        'element_lightbox_overlay' => 'yes',
                        'element_sharing_trigger_icon' => 'fas fa-share',
                    ],
                    [
                        'element_select' => 'title',
                        'element_location' => 'over',
                        'element_extra_icon_pos' => 'before',
                        'element_animation' => 'fade-in',
                        'element_animation_size' => 'large',
                        'element_sharing_trigger_icon' => 'fas fa-share',
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
            'overlay_animation',
            [
                'label' => esc_html__('Select Animation', 'king-addons'),
                'type' => 'king-addons-animations-alt',
                'default' => 'fade-in',
            ]
        );


        Core::renderUpgradeProNotice($this, Controls_Manager::RAW_HTML, 'media-grid', 'overlay_animation', ['pro-slrt', 'pro-slxrt', 'pro-slbt', 'pro-sllt', 'pro-sltp', 'pro-slxlt', 'pro-sktp', 'pro-skrt', 'pro-skbt', 'pro-sklt', 'pro-scup', 'pro-scdn', 'pro-rllt', 'pro-rlrt',]);

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


        Core::renderUpgradeProNotice($this, Controls_Manager::RAW_HTML, 'media-grid', 'overlay_animation_timing', Core::getAnimationTimingsConditionsPro());

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

        $this->add_control_image_effects();


        Core::renderUpgradeProNotice($this, Controls_Manager::RAW_HTML, 'media-grid', 'image_effects', ['pro-zi', 'pro-zo', 'pro-go', 'pro-bo']);

        $this->add_control(
            'image_effects_duration',
            [
                'label' => esc_html__('Animation Duration', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'default' => 0.3,
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


        Core::renderUpgradeProNotice($this, Controls_Manager::RAW_HTML, 'media-grid', 'image_effects_animation_timing', Core::getAnimationTimingsConditionsPro());

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
                'default' => 'small',
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
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Select Taxonomy', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => $post_taxonomy_names,
                'default' => 'media_category',
                'description' => 'If it is empty, add a taxonomy for media in the WordPress dashboard using Media Library Assistant or any CPT manager plugin. ',
                'separator' => 'after',
            ]
        );

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
                'default' => 'All Photos',
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
                'default' => 'left',
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

        Core::renderUpgradeProNotice($this, Controls_Manager::RAW_HTML, 'media-grid', 'filters_animation', ['pro-fd', 'pro-fs']);

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


        Core::renderUpgradeProNotice($this, Controls_Manager::RAW_HTML, 'media-grid', 'pagination_type', ['pro-is']);

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

        $this->add_control(
            'pagination_notice',
            [
                'raw' => sprintf(__('<strong>Performance Note:</strong> For grids that include a large number of images (100+), we recommend using the Default and Numbered pagination types because the browser tab can become heavy (especially in Chromium-based browsers).', 'king-addons'), Core::getPluginName()),
                'type' => Controls_Manager::RAW_HTML,
                'separator' => 'before',
                'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
            ]
        );

        $this->end_controls_section();

        Core::renderProFeaturesSection($this, '', Controls_Manager::RAW_HTML, 'media-grid', [
            'Grid Columns 1, 2, 3, 4, 5, 6, 7, 8',
            'Masonry Layout',
            'Image Slider Columns (Carousel) 1, 2, 3, 4, 5, 6, 7, 8',
            'Image Slider Autoplay Options',
            'Infinite Scrolling Pagination',
            'Random Images Query',
            'Image Order',
            'Grid Category Filter Deep Linking',
            'Grid Category Filter Icons Select',
            'Grid Category Filter Count',
            'Image Slider Advanced Navigation Positioning',
            'Image Slider Advanced Pagination Positioning',
            'Lightbox Thumbnail Gallery, Lightbox Image Sharing Button',
            'Advanced Grid Loading Animations (Fade In & Slide Up)',
            'Advanced Grid Elements Positioning',
            'Unlimited Image Overlay Animations',
            'Image Overlay GIF Upload Option',
            'Image Overlay Blend Mode',
            'Image Effects: Zoom, Grayscale, Blur',
            'Advanced Image Likes',
            'Advanced Image Sharing',
            'Grid Item Even/Odd Background Color',
            'Title & Category Advanced Link Hover Animations'
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
            'grid_item_bg_color',
            [
                'label' => esc_html__('Background Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-item-above-content' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .king-addons-grid-item-below-content' => 'background-color: {{VALUE}}',
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
                    '{{WRAPPER}} .king-addons-grid-item-above-content' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .king-addons-grid-item-below-content' => 'border-color: {{VALUE}}',
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
                'default' => 'solid',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-item-above-content' => 'border-style: {{VALUE}};',
                    '{{WRAPPER}} .king-addons-grid-item-below-content' => 'border-style: {{VALUE}};',
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
                    '{{WRAPPER}} .king-addons-grid-item-above-content' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .king-addons-grid-item-below-content' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                'size_units' => ['px', '%'],
                'default' => [
                    'top' => 10,
                    'right' => 10,
                    'bottom' => 10,
                    'left' => 10,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-item-above-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .king-addons-grid-item-below-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                ],
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
                ],
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
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-item-title .inner-block a' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .king-addons-grid-item-title .inner-block span' => 'color: {{VALUE}}'
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
                    '{{WRAPPER}} .king-addons-grid-item-title .inner-block span' => 'background-color: {{VALUE}}'
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
                    '{{WRAPPER}} .king-addons-grid-item-title .inner-block span' => 'border-color: {{VALUE}}',
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
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-item-title .inner-block a:hover' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .king-addons-grid-item-title .inner-block span:hover' => 'color: {{VALUE}}'
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
                    '{{WRAPPER}} .king-addons-grid-item-title .inner-block span:hover' => 'background-color: {{VALUE}}'
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
                    '{{WRAPPER}} .king-addons-grid-item-title .inner-block span:hover' => 'border-color: {{VALUE}}'
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
                'default' => 0.3,
                'min' => 0,
                'max' => 5,
                'step' => 0.1,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-item-title .inner-block a' => 'transition-duration: {{VALUE}}s',
                    '{{WRAPPER}} .king-addons-grid-item-title .inner-block span' => 'transition-duration: {{VALUE}}s',
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
                'selector' => '{{WRAPPER}} .king-addons-grid-item-title a, {{WRAPPER}} .king-addons-grid-item-title span'
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
                    '{{WRAPPER}} .king-addons-grid-item-title .inner-block span' => 'border-style: {{VALUE}};'
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
                    '{{WRAPPER}} .king-addons-grid-item-title .inner-block span' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
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
                    '{{WRAPPER}} .king-addons-grid-item-title .inner-block span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
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
            'section_style_caption',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Caption', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            ]
        );

        $this->add_control(
            'caption_color',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-item-caption .inner-block p' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'caption_bg_color',
            [
                'label' => esc_html__('Background Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-item-caption .inner-block' => 'background-color: {{VALUE}}',
                ]
            ]
        );

        $this->add_control(
            'caption_border_color',
            [
                'label' => esc_html__('Border Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#E8E8E8',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-item-caption .inner-block' => 'border-color: {{VALUE}}',
                ],
                'separator' => 'after',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'caption_typography',
                'selector' => '{{WRAPPER}} .king-addons-grid-item-caption'
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'caption_dropcap_typography',
                'label' => esc_html__('Drop Cap Typography', 'king-addons'),
                'selector' => '{{WRAPPER}} .king-addons-grid-item-caption.king-addons-enable-dropcap p:first-child:first-letter'
            ]
        );

        $this->add_responsive_control(
            'caption_justify',
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
                    '{{WRAPPER}} .king-addons-grid-item-caption .inner-block' => '{{VALUE}}',
                ],
                'render_type' => 'template',
            ]
        );

        $this->add_control(
            'caption_border_type',
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
                    '{{WRAPPER}} .king-addons-grid-item-caption .inner-block' => 'border-style: {{VALUE}};',
                ],
                'render_type' => 'template',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'caption_border_width',
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
                    '{{WRAPPER}} .king-addons-grid-item-caption .inner-block' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'render_type' => 'template',
                'condition' => [
                    'caption_border_type!' => 'none',
                ],
            ]
        );

        $this->add_responsive_control(
            'caption_padding',
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
                    '{{WRAPPER}} .king-addons-grid-item-caption .inner-block' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'render_type' => 'template',
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'caption_margin',
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
                    '{{WRAPPER}} .king-addons-grid-item-caption .inner-block' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                'default' => '#ffffff',
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
                'selector' => '{{WRAPPER}} .king-addons-grid-item-date'
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
                    'right' => 0,
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
                'default' => '#ffffff',
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
                'default' => '#ffffff',
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
                'selector' => '{{WRAPPER}} .king-addons-grid-item-author'
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


        $this->add_section_style_likes();


        $this->add_section_style_sharing();


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
                'default' => '#ffffff',
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
                    'bottom' => 10,
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
                    'top' => 0,
                    'right' => 0,
                    'bottom' => 0,
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
                    'top' => 0,
                    'right' => 0,
                    'bottom' => 0,
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
                'default' => '#ffffff',
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
                    'top' => 2,
                    'right' => 2,
                    'bottom' => 2,
                    'left' => 2,
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
                'default' => 'rgba(255,255,255,0.8)',
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
                'default' => '#E8E8E8',
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
                'default' => '#ffffff',
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
                'default' => '#E8E8E8',
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
                    'bottom' => 0,
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
                'default' => '#E8E8E8',
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
                'default' => '#222222',
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
                'default' => '#E8E8E8',
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
                    '{{WRAPPER}} .king-addons-pointer-item:before' => 'transition-duration: {{VALUE}}s',
                    '{{WRAPPER}} .king-addons-pointer-item:after' => 'transition-duration: {{VALUE}}s',
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
                    'layout_pagination' => 'yes',
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
                'selector' => '{{WRAPPER}} .king-addons-grid-pagination'
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
                    '{{WRAPPER}} .king-addons-grid-pagination span.king-addons-disabled-arrow' => 'border-style: {{VALUE}};',
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
                    '{{WRAPPER}} .king-addons-grid-pagination span.king-addons-disabled-arrow' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .king-addons-grid-pagination span.king-addons-grid-current-page' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    'size' => 35,
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
                    '{{WRAPPER}} .king-addons-grid-pagination a' => 'margin-right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .king-addons-grid-pagination > div > span' => 'margin-right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .king-addons-grid-pagination span.king-addons-disabled-arrow' => 'margin-right: {{SIZE}}{{UNIT}};',
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
                    'right' => 15,
                    'bottom' => 8,
                    'left' => 15,
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

    public function get_related_taxonomies()
    {
        $relations = [];
        foreach (Core::getCustomTypes('post', false) as $slug => $title) {
            $relations[$slug] = get_object_taxonomies($slug);
        }
        return json_encode($relations);
    }

    public function get_max_num_pages()
    {
        $query = new WP_Query($this->get_main_query_args());
        $pages = (int)ceil($query->max_num_pages);
        wp_reset_postdata();
        return $pages;
    }

    public function get_main_query_args()
    {
        $s = $this->get_settings_for_display();
        $author = !empty($s['query_author']) ? implode(',', $s['query_author']) : '';
        $paged = get_query_var('paged') ? get_query_var('paged') : (get_query_var('page') ? get_query_var('page') : 1);
        $offset = ($paged - 1) * $s['query_posts_per_page'] + (empty($s['query_offset']) ? 0 : $s['query_offset']);

        // Remove premium-only randomize if not available
        if (!king_addons_freemius()->can_use_premium_code__premium_only()) {
            $s['query_randomize'] = '';
            $s['order_posts'] = 'date';
        }
        $order_by = $s['query_randomize'] !== '' ? $s['query_randomize'] : $s['order_posts'];
        if ('manual' === $s['query_selection']) $order_by = 'post__in';

        $args = [
            'post_type' => 'attachment',
            'post_mime_type' => 'image',  // <-- Only images
            'post_status' => 'inherit',
            'tax_query' => $this->get_tax_query_args(),
            'post__not_in' => $s['query_exclude_attachment'],
            'posts_per_page' => $s['query_posts_per_page'],
            'orderby' => $order_by,
            'author' => $author,
            'paged' => $paged,
            'offset' => $offset
        ];

        if ('manual' === $s['query_selection']) {
            $post_ids = [];
            if (!empty($s['query_manual_attachment'])) {
                foreach ($s['query_manual_attachment'] as $attachment) {
                    $post_ids[] = $attachment['id'];
                }
            }
            $orderby = ('' === $s['query_randomize']) ? 'post__in' : 'rand';
            $args = [
                'post_type' => 'attachment',
                'post_mime_type' => 'image',  // <-- Only images
                'post_status' => 'inherit',
                'post__in' => $post_ids,
                'orderby' => $orderby,
                'posts_per_page' => $s['query_posts_per_page'],
                'paged' => $paged
            ];
        }
        if ('rand' !== $order_by && 'manual' !== $s['query_selection']) {
            $args['order'] = $s['order_direction'];
        }
        return $args;
    }

    public function get_tax_query_args()
    {
        $s = $this->get_settings();
        $tax_query = [];
        foreach (get_object_taxonomies('attachment') as $tax) {
            if (!empty($s['query_taxonomy_' . $tax])) {
                $tax_query[] = [
                    'taxonomy' => $tax,
                    'field' => 'id',
                    'terms' => $s['query_taxonomy_' . $tax]
                ];
            }
        }
        return $tax_query;
    }

    public function get_animation_class($data, $object)
    {
        $class = '';
        if ('none' !== $data[$object . '_animation']) {
            $class .= ' king-addons-' . $object . '-' . $data[$object . '_animation'];
            $class .= ' king-addons-anim-size-' . $data[$object . '_animation_size'];
            $class .= ' king-addons-animation-timing-' . $data[$object . '_animation_timing'];
            if ('yes' === $data[$object . '_animation_tr']) $class .= ' king-addons-anim-transparency';
        }
        return $class;
    }

    public function get_image_effect_class($s)
    {
        $class = '';
        if (!king_addons_freemius()->can_use_premium_code__premium_only()) {
            if (in_array($s['image_effects'], ['pro-zi', 'pro-zo', 'pro-go', 'pro-bo'])) {
                $s['image_effects'] = 'none';
            }
        }
        if ('none' !== $s['image_effects']) {
            $class .= ' king-addons-' . $s['image_effects'];
        }
        if ('slide' !== $s['image_effects']) {
            $class .= ' king-addons-effect-size-' . $s['image_effects_size'];
        } else {
            $class .= ' king-addons-effect-dir-' . $s['image_effects_direction'];
        }
        return $class;
    }

    public function render_post_thumbnail($settings)
    {
        $id = get_the_ID();
        $src = Group_Control_Image_Size::get_attachment_image_src($id, 'layout_image_crop', $settings);
        $alt = ('' === wp_get_attachment_caption($id)) ? get_the_title() : wp_get_attachment_caption($id);
        echo '<div class="king-addons-grid-image-wrap" data-src="' . esc_url(wp_get_attachment_url($id)) . '">';
        echo '<img src="' . esc_url($src) . '" alt="' . wp_kses_post($alt) . '" class="king-addons-animation-timing-' . esc_html($settings['image_effects_animation_timing']) . '">';
        echo '</div>';
    }

    public function render_media_overlay($s)
    {
        echo '<div class="king-addons-grid-media-hover-bg ' . $this->get_animation_class($s, 'overlay') . '" data-url="' . esc_url(get_the_permalink(get_the_ID())) . '">';
        if (king_addons_freemius()->can_use_premium_code__premium_only()) {
            if ('' !== $s['overlay_image']['url']) {
                echo '<img src="' . esc_url($s['overlay_image']['url']) . '">';
            }
        }
        echo '</div>';
    }

    public function render_post_title($settings, $class)
    {
        $title_pointer = !king_addons_freemius()->can_use_premium_code__premium_only() ? 'none' : $this->get_settings()['title_pointer'];
        $title_pointer_animation = !king_addons_freemius()->can_use_premium_code__premium_only() ? 'fade' : $this->get_settings()['title_pointer_animation'];
        $pointer_item_class = (isset($this->get_settings()['title_pointer']) && 'none' !== $this->get_settings()['title_pointer']) ? 'class="king-addons-pointer-item"' : '';
        $class .= ' king-addons-pointer-' . $title_pointer . ' king-addons-pointer-line-fx king-addons-pointer-fx-' . $title_pointer_animation;

        $tags_whitelist = ['h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'div', 'span', 'p'];
        $element_title_tag = Core::validateHTMLTags($settings['element_title_tag'], 'h2', $tags_whitelist);

        echo '<' . esc_attr($element_title_tag) . ' class="' . esc_attr($class) . '">';
        echo '<div class="inner-block">';
        if ('yes' === $settings['element_disable_link']) {
            echo '<span ' . $pointer_item_class . '>' . esc_html(wp_trim_words(get_the_title(), $settings['element_word_count'])) . '</span>';
        } else {
            echo '<a ' . $pointer_item_class . ' href="' . esc_url(get_the_permalink()) . '">';
            echo esc_html(wp_trim_words(get_the_title(), $settings['element_word_count']));
            echo '</a>';
        }
        echo '</div>';
        echo '</' . esc_attr($element_title_tag) . '>';
    }

    public function render_post_excerpt($s, $class)
    {
        $dropcap_class = ('yes' === $s['element_dropcap']) ? ' king-addons-enable-dropcap' : '';
        $class .= $dropcap_class;
        if ('' === get_the_excerpt()) return;
        echo '<div class="' . esc_attr($class) . '"><div class="inner-block">';
        echo '<p>' . esc_html(wp_trim_words(get_the_excerpt(), $s['element_word_count'])) . '</p>';
        echo '</div></div>';
    }

    public function render_post_date($s, $class)
    {
        echo '<div class="' . esc_attr($class) . '"><div class="inner-block"><span>';
        if ('before' === $s['element_extra_text_pos']) {
            echo '<span class="king-addons-grid-extra-text-left">' . esc_html($s['element_extra_text']) . '</span>';
        }
        if ('before' === $s['element_extra_icon_pos']) {
            ob_start();
            Icons_Manager::render_icon($s['element_extra_icon'], ['aria-hidden' => 'true']);
            $extra_icon = ob_get_clean();
            echo '<span class="king-addons-grid-extra-icon-left">' . $extra_icon . '</span>';
        }
        echo esc_html(apply_filters('the_date', get_the_date(), get_option('date_format'), '', ''));
        if ('after' === $s['element_extra_icon_pos']) {
            ob_start();
            Icons_Manager::render_icon($s['element_extra_icon'], ['aria-hidden' => 'true']);
            $extra_icon = ob_get_clean();
            echo '<span class="king-addons-grid-extra-icon-right">' . $extra_icon . '</span>';
        }
        if ('after' === $s['element_extra_text_pos']) {
            echo '<span class="king-addons-grid-extra-text-right">' . esc_html($s['element_extra_text']) . '</span>';
        }
        echo '</span></div></div>';
    }

    public function render_post_time($s, $class)
    {
        echo '<div class="' . esc_attr($class) . '"><div class="inner-block"><span>';
        if ('before' === $s['element_extra_text_pos']) {
            echo '<span class="king-addons-grid-extra-text-left">' . esc_html($s['element_extra_text']) . '</span>';
        }
        if ('before' === $s['element_extra_icon_pos']) {
            ob_start();
            Icons_Manager::render_icon($s['element_extra_icon'], ['aria-hidden' => 'true']);
            $extra_icon = ob_get_clean();
            echo '<span class="king-addons-grid-extra-icon-left">' . $extra_icon . '</span>';
        }
        echo esc_html(get_the_time());
        if ('after' === $s['element_extra_icon_pos']) {
            ob_start();
            Icons_Manager::render_icon($s['element_extra_icon'], ['aria-hidden' => 'true']);
            $extra_icon = ob_get_clean();
            echo '<span class="king-addons-grid-extra-icon-right">' . $extra_icon . '</span>';
        }
        if ('after' === $s['element_extra_text_pos']) {
            echo '<span class="king-addons-grid-extra-text-right">' . esc_html($s['element_extra_text']) . '</span>';
        }
        echo '</span></div></div>';
    }

    public function render_post_author($s, $class)
    {
        $author_id = get_post_field('post_author');
        echo '<div class="' . esc_attr($class) . '"><div class="inner-block">';
        if ('before' === $s['element_extra_text_pos']) {
            echo '<span class="king-addons-grid-extra-text-left">' . esc_html($s['element_extra_text']) . '</span>';
        }
        echo '<a href="' . esc_url(get_author_posts_url($author_id)) . '">';
        if ('before' === $s['element_extra_icon_pos']) {
            ob_start();
            Icons_Manager::render_icon($s['element_extra_icon'], ['aria-hidden' => 'true']);
            $extra_icon = ob_get_clean();
            echo '<span class="king-addons-grid-extra-icon-left">' . $extra_icon . '</span>';
        }
        if ('yes' === $s['element_show_avatar']) {
            echo get_avatar($author_id, $s['element_avatar_size']);
        }
        echo '<span>' . esc_html(get_the_author_meta('display_name', $author_id)) . '</span>';
        if ('after' === $s['element_extra_icon_pos']) {
            ob_start();
            Icons_Manager::render_icon($s['element_extra_icon'], ['aria-hidden' => 'true']);
            $extra_icon = ob_get_clean();
            echo '<span class="king-addons-grid-extra-icon-right">' . $extra_icon . '</span>';
        }
        echo '</a>';
        if ('after' === $s['element_extra_text_pos']) {
            echo '<span class="king-addons-grid-extra-text-right">' . esc_html($s['element_extra_text']) . '</span>';
        }
        echo '</div></div>';
    }

    public function render_post_likes($s, $class, $post_id)
    {
        // Intentionally empty in the original
    }

    public function render_post_sharing_icons($s, $class)
    {
        // Intentionally empty in the original
    }

    public function render_post_lightbox($s, $class, $post_id)
    {
        echo '<div class="' . esc_attr($class) . '"><div class="inner-block">';
        $lightbox_source = get_the_post_thumbnail_url($post_id);
        if ('audio' === get_post_format()) {
            if ('meta' === $s['element_lightbox_pfa_select']) {
                $meta_value = get_post_meta($post_id, $s['element_lightbox_pfa_meta'], true);
                if (false === strpos($meta_value, '<iframe ')) {
                    add_filter('oembed_result', $media_grid_filter = function ($html) {
                        preg_match('/<iframe.*src=\"(.*)\".*><\/iframe>/isU', $html, $m);
                        return $m[1] . '&auto_play=true';
                    }, 50, 3);
                    $track_url = wp_oembed_get($meta_value);
                    remove_filter('oembed_result', $media_grid_filter, 50);
                } else {
                    $track_url = Core::filterOembedResults($meta_value);
                }
                $lightbox_source = $track_url;
            }
        } elseif ('video' === get_post_format()) {
            if ('meta' === $s['element_lightbox_pfv_select']) {
                $meta_value = get_post_meta($post_id, $s['element_lightbox_pfv_meta'], true);
                if (false === strpos($meta_value, '<iframe ')) {
                    $video = Embed::get_video_properties($meta_value);
                } else {
                    $video = Embed::get_video_properties(Core::filterOembedResults($meta_value));
                }
                if ('youtube' === $video['provider']) {
                    $video_url = 'https://www.youtube.com/embed/' . $video['video_id'] . '?feature=oembed&autoplay=1&controls=1';
                } elseif ('vimeo' === $video['provider']) {
                    $video_url = 'https://player.vimeo.com/video/' . $video['video_id'] . '?autoplay=1#t=0';
                }
                if (isset($video_url)) $lightbox_source = $video_url;
            }
        }
        if (!$lightbox_source) $lightbox_source = wp_get_attachment_url($post_id);
        echo '<span data-src="' . esc_url($lightbox_source) . '">';
        if ('before' === $s['element_extra_text_pos']) {
            echo '<span class="king-addons-grid-extra-text-left">' . esc_html($s['element_extra_text']) . '</span>';
        }
        echo '<i class="' . esc_attr($s['element_extra_icon']['value']) . '"></i>';
        if ('after' === $s['element_extra_text_pos']) {
            echo '<span class="king-addons-grid-extra-text-right">' . esc_html($s['element_extra_text']) . '</span>';
        }
        echo '</span>';
        if ('yes' === $s['element_lightbox_overlay']) {
            echo '<div class="king-addons-grid-lightbox-overlay"></div>';
        }
        echo '</div></div>';
    }

    public function render_post_element_separator($s, $class)
    {
        echo '<div class="' . esc_attr($class . ' ' . $s['element_separator_style']) . '">';
        echo '<div class="inner-block"><span></span></div></div>';
    }

    public function render_post_taxonomies($settings, $class, $post_id)
    {
        $terms = wp_get_post_terms($post_id, $settings['element_select']);
        $count = 0;

        $tax1_pointer = !king_addons_freemius()->can_use_premium_code__premium_only() ? 'none' : $this->get_settings()['tax1_pointer'];
        $tax1_pointer_animation = !king_addons_freemius()->can_use_premium_code__premium_only() ? 'fade' : $this->get_settings()['tax1_pointer_animation'];
        $tax2_pointer = !king_addons_freemius()->can_use_premium_code__premium_only() ? 'none' : $this->get_settings()['tax2_pointer'];
        $tax2_pointer_animation = !king_addons_freemius()->can_use_premium_code__premium_only() ? 'fade' : $this->get_settings()['tax2_pointer_animation'];
        $pointer_item_class = ('none' !== $tax1_pointer || 'none' !== $tax2_pointer) ? 'class="king-addons-pointer-item"' : '';

        if ('king-addons-grid-tax-style-1' === $settings['element_tax_style']) {
            $class .= ' king-addons-pointer-' . $tax1_pointer . ' king-addons-pointer-line-fx king-addons-pointer-fx-' . $tax1_pointer_animation;
        } else {
            $class .= ' king-addons-pointer-' . $tax2_pointer . ' king-addons-pointer-line-fx king-addons-pointer-fx-' . $tax2_pointer_animation;
        }

        echo '<div class="' . esc_attr($class . ' ' . $settings['element_tax_style']) . '">';
        echo '<div class="inner-block">';

        if ('before' === $settings['element_extra_text_pos']) {
            echo '<span class="king-addons-grid-extra-text-left">' . esc_html($settings['element_extra_text']) . '</span>';
        }
        if ('before' === $settings['element_extra_icon_pos']) {
            ob_start();
            Icons_Manager::render_icon($settings['element_extra_icon'], ['aria-hidden' => 'true']);
            $extra_icon = ob_get_clean();
            echo '<span class="king-addons-grid-extra-icon-left">' . $extra_icon . '</span>';
        }

        foreach ($terms as $term) {
            echo '<a ' . $pointer_item_class . ' href="' . esc_url(get_term_link($term->term_id)) . '">' . esc_html($term->name);
            if (++$count !== count($terms)) {
                echo '<span class="tax-sep">' . esc_html($settings['element_tax_sep']) . '</span>';
            }
            echo '</a>';
        }

        if ('after' === $settings['element_extra_icon_pos']) {
            ob_start();
            Icons_Manager::render_icon($settings['element_extra_icon'], ['aria-hidden' => 'true']);
            $extra_icon = ob_get_clean();
            echo '<span class="king-addons-grid-extra-icon-right">' . $extra_icon . '</span>';
        }
        if ('after' === $settings['element_extra_text_pos']) {
            echo '<span class="king-addons-grid-extra-text-right">' . esc_html($settings['element_extra_text']) . '</span>';
        }
        echo '</div></div>';
    }

    public function get_elements($type, $settings, $class, $post_id)
    {
        if ('pro-lk' === $type || 'pro-shr' === $type) $type = 'title';
        switch ($type) {
            case 'title':
                $this->render_post_title($settings, $class);
                break;
            case 'caption':
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
            case 'likes':
                $this->render_post_likes($settings, $class, $post_id);
                break;
            case 'sharing':
                $this->render_post_sharing_icons($settings, $class);
                break;
            case 'lightbox':
                $this->render_post_lightbox($settings, $class, $post_id);
                break;
            case 'separator':
                $this->render_post_element_separator($settings, $class);
                break;
            default:
                $this->render_post_taxonomies($settings, $class, $post_id);
                break;
        }
    }

    public function get_elements_by_location($location, $settings, $post_id)
    {
        $locations = [];
        foreach ($settings['grid_elements'] as $data) {
            $place = $data['element_location'];
            $alignV = $data['element_align_vr'];
            if (!king_addons_freemius()->can_use_premium_code__premium_only()) $alignV = 'middle';
            if (!isset($locations[$place])) $locations[$place] = [];
            if ('over' === $place) {
                if (!isset($locations[$place][$alignV])) $locations[$place][$alignV] = [];
                $locations[$place][$alignV][] = $data;
            } else {
                $locations[$place][] = $data;
            }
        }
        if (!empty($locations[$location])) {
            if ('over' === $location) {
                foreach ($locations[$location] as $align => $elements) {
                    if ('middle' === $align) {
                        echo '<div class="king-addons-cv-container"><div class="king-addons-cv-outer"><div class="king-addons-cv-inner">';
                    }
                    echo '<div class="king-addons-grid-media-hover-' . esc_attr($align) . ' elementor-clearfix">';
                    foreach ($elements as $data) {
                        $class = 'king-addons-grid-item-' . $data['element_select'] . ' elementor-repeater-item-' . $data['_id'];
                        $class .= ' king-addons-grid-item-display-' . $data['element_display'];
                        $class .= ' king-addons-grid-item-align-' . $data['element_align_hr'];
                        $class .= $this->get_animation_class($data, 'element');
                        $this->get_elements($data['element_select'], $data, $class, $post_id);
                    }
                    echo '</div>';
                    if ('middle' === $align) echo '</div></div></div>';
                }
            } else {
                echo '<div class="king-addons-grid-item-' . esc_attr($location) . '-content elementor-clearfix">';
                foreach ($locations[$location] as $data) {
                    $class = 'king-addons-grid-item-' . $data['element_select'] . ' elementor-repeater-item-' . $data['_id'];
                    $class .= ' king-addons-grid-item-display-' . $data['element_display'];
                    $class .= ' king-addons-grid-item-align-' . $data['element_align_hr'];
                    $this->get_elements($data['element_select'], $data, $class, $post_id);
                }
                echo '</div>';
            }
        }
    }

    public function render_grid_filters($s)
    {
        $taxonomy = $s['filters_select'];
        if ('' === $taxonomy || !isset($s['query_taxonomy_' . $taxonomy])) return;
        $custom_filters = $s['query_taxonomy_' . $taxonomy];

        if (!king_addons_freemius()->can_use_premium_code__premium_only()) {
            $s['filters_default_filter'] = '';
            $s['filters_icon_align'] = '';
            $s['filters_count'] = '';
            $s['filters_pointer'] = 'none';
            $s['filters_pointer_animation'] = 'none';
        }

        $left_icon = ('left' === $s['filters_icon_align']) ? '<i class="' . esc_attr($s['filters_icon']['value']) . ' king-addons-grid-filters-icon-left"></i>' : '';
        $right_icon = ('right' === $s['filters_icon_align']) ? '<i class="' . esc_attr($s['filters_icon']['value']) . ' king-addons-grid-filters-icon-right"></i>' : '';
        $l_sep = ('left' === $s['filters_separator_align']) ? '<em class="king-addons-grid-filters-sep">' . esc_attr($s['filters_separator']) . '</em>' : '';
        $r_sep = ('right' === $s['filters_separator_align']) ? '<em class="king-addons-grid-filters-sep">' . esc_attr($s['filters_separator']) . '</em>' : '';
        $post_count = ('yes' === $s['filters_count']) ? '<sup data-brackets="' . esc_attr($s['filters_count_brackets']) . '"></sup>' : '';
        $p_class = ' king-addons-pointer-' . $s['filters_pointer'] . ' king-addons-pointer-line-fx king-addons-pointer-fx-' . $s['filters_pointer_animation'];
        $p_item_class = ('none' !== $s['filters_pointer']) ? 'king-addons-pointer-item' : '';

        echo '<ul class="king-addons-grid-filters elementor-clearfix king-addons-grid-filters-sep-' . esc_attr($s['filters_separator_align']) . '">';
        if ('yes' === $s['filters_all'] && 'yes' !== $s['filters_linkable']) {
            echo '<li class="' . esc_attr($p_class) . '">';
            echo '<span data-filter="*" class="king-addons-active-filter ' . $p_item_class . '">' . $left_icon . esc_html($s['filters_all_text']) . $right_icon . $post_count . '</span>' . $r_sep;
            echo '</li>';
        }

        if ($s['query_selection'] === 'dynamic' && !empty($custom_filters)) {
            $parent_filters = [];
            foreach ($custom_filters as $term_id) {
                $filter = get_term_by('id', $term_id, $taxonomy);
                if (!$filter) continue;
                $dataAttr = ('post_tag' === $taxonomy) ? 'tag-' . $filter->slug : $taxonomy . '-' . $filter->slug;
                if (0 === $filter->parent) {
                    $children = get_term_children($filter->term_id, $taxonomy);
                    $dataRole = (!empty($children)) ? ' data-role="parent"' : '';
                    echo '<li' . $dataRole . ' class="' . esc_attr($p_class) . '">';
                    if ('yes' !== $s['filters_linkable']) {
                        echo $l_sep . '<span class="' . $p_item_class . '" data-filter=".' . esc_attr(urldecode($dataAttr)) . '">' . $left_icon . esc_html($filter->name) . $right_icon . $post_count . '</span>' . $r_sep;
                    } else {
                        echo $l_sep . '<a class="' . $p_item_class . '" href="' . esc_url(get_term_link($filter->term_id, $taxonomy)) . '">' . $left_icon . esc_html($filter->name) . $right_icon . $post_count . '</a>' . $r_sep;
                    }
                    echo '</li>';
                } else {
                    $parent_filters[] = $filter->parent;
                }
            }
        } else {
            $all_filters = get_terms($taxonomy);
            $parent_filters = [];
            if (!is_wp_error($all_filters)) {
                foreach ($all_filters as $filter) {
                    $dataAttr = ('post_tag' === $taxonomy) ? 'tag-' . $filter->slug : $taxonomy . '-' . $filter->slug;
                    if (0 === $filter->parent) {
                        $children = get_term_children($filter->term_id, $taxonomy);
                        $dataRole = (!empty($children)) ? ' data-role="parent"' : '';
                        echo '<li' . $dataRole . ' class="' . esc_attr($p_class) . '">';
                        if ('yes' !== $s['filters_linkable']) {
                            echo $l_sep . '<span class="' . $p_item_class . '" data-filter=".' . esc_attr(urldecode($dataAttr)) . '">' . $left_icon . esc_html($filter->name) . $right_icon . $post_count . '</span>' . $r_sep;
                        } else {
                            echo $l_sep . '<a class="' . $p_item_class . '" href="' . esc_url(get_term_link($filter->term_id, $taxonomy)) . '" data-filter=".' . esc_attr(urldecode($dataAttr)) . '">' . $left_icon . esc_html($filter->name) . $right_icon . $post_count . '</a>' . $r_sep;
                        }
                        echo '</li>';
                    } else {
                        $parent_filters[] = $filter->parent;
                    }
                }
            }
        }

        if ('yes' !== $s['filters_linkable']) {
            foreach (array_unique($parent_filters) as $parent_filter) {
                $parent = get_term_by('id', $parent_filter, $taxonomy);
                if (!$parent) continue;
                $children = get_term_children($parent_filter, $taxonomy);
                $dataAttr = ('post_tag' === $taxonomy) ? 'tag-' . $parent->slug : $taxonomy . '-' . $parent->slug;
                echo '<ul data-parent=".' . esc_attr(urldecode($dataAttr)) . '" class="king-addons-sub-filters">';
                echo '<li data-role="back" class="' . esc_attr($p_class) . '">';
                echo '<span class="king-addons-back-filter" data-filter=".' . esc_attr(urldecode($dataAttr)) . '">';
                echo '<i class="fas fa-long-arrow-alt-left"></i>&nbsp;&nbsp;' . esc_html__('Back', 'king-addons') . '</span></li>';
                foreach ($children as $child) {
                    $sub_filter = get_term_by('id', $child, $taxonomy);
                    if (!$sub_filter) continue;
                    $dataAttr = ('post_tag' === $taxonomy) ? 'tag-' . $sub_filter->slug : $taxonomy . '-' . $sub_filter->slug;
                    echo '<li data-role="sub" class="' . esc_attr($p_class) . '">';
                    echo $l_sep . '<span class="' . $p_item_class . '" data-filter=".' . esc_attr(urldecode($dataAttr)) . '">' . $left_icon . esc_html($sub_filter->name) . $right_icon . $post_count . '</span>' . $r_sep;
                    echo '</li>';
                }
                echo '</ul>';
            }
        }
        echo '</ul>';
    }

    public function render_grid_pagination($s)
    {
        if ('yes' !== $s['layout_pagination'] || 1 === $this->get_max_num_pages() || 'slider' === $s['layout_select']) return;
        global $paged;
        $pages = $this->get_max_num_pages();
        $paged = empty($paged) ? 1 : $paged;

        if (!king_addons_freemius()->can_use_premium_code__premium_only()) {
            if ('pro-is' === $s['pagination_type']) $s['pagination_type'] = 'default';
        }
        echo '<div class="king-addons-grid-pagination elementor-clearfix king-addons-grid-pagination-' . esc_attr($s['pagination_type']) . '">';

        if ('default' === $s['pagination_type']) {
            if ($paged < $pages) {
                echo '<a href="' . esc_url(get_pagenum_link($paged + 1)) . '" class="king-addons-prev-post-link">';
                echo Core::getIcon($s['pagination_on_icon'], 'left') . esc_html($s['pagination_older_text']) . '</a>';
            } elseif ('yes' === $s['pagination_disabled_arrows']) {
                echo '<span class="king-addons-prev-post-link king-addons-disabled-arrow">';
                echo Core::getIcon($s['pagination_on_icon'], 'left') . esc_html($s['pagination_older_text']) . '</span>';
            }
            if ($paged > 1) {
                echo '<a href="' . esc_url(get_pagenum_link($paged - 1)) . '" class="king-addons-next-post-link">';
                echo esc_html($s['pagination_newer_text']) . Core::getIcon($s['pagination_on_icon'], 'right') . '</a>';
            } elseif ('yes' === $s['pagination_disabled_arrows']) {
                echo '<span class="king-addons-next-post-link king-addons-disabled-arrow">';
                echo esc_html($s['pagination_newer_text']) . Core::getIcon($s['pagination_on_icon'], 'right') . '</span>';
            }
        } elseif ('numbered' === $s['pagination_type']) {
            $range = $s['pagination_range'];
            $showitems = ($range * 2) + 1;
            if (1 !== $pages) {
                if ('yes' === $s['pagination_prev_next'] || 'yes' === $s['pagination_first_last']) {
                    echo '<div class="king-addons-grid-pagination-left-arrows">';
                    if ('yes' === $s['pagination_first_last']) {
                        if ($paged >= 2) {
                            echo '<a href="' . esc_url(get_pagenum_link()) . '" class="king-addons-first-page">';
                            echo Core::getIcon($s['pagination_fl_icon'], 'left') . '<span>' . esc_html($s['pagination_first_text']) . '</span></a>';
                        } elseif ('yes' === $s['pagination_disabled_arrows']) {
                            echo '<span class="king-addons-first-page king-addons-disabled-arrow">';
                            echo Core::getIcon($s['pagination_fl_icon'], 'left') . '<span>' . esc_html($s['pagination_first_text']) . '</span></span>';
                        }
                    }
                    if ('yes' === $s['pagination_prev_next']) {
                        if ($paged > 1) {
                            echo '<a href="' . esc_url(get_pagenum_link($paged - 1)) . '" class="king-addons-prev-page">';
                            echo Core::getIcon($s['pagination_pn_icon'], 'left') . '<span>' . esc_html($s['pagination_prev_text']) . '</span></a>';
                        } elseif ('yes' === $s['pagination_disabled_arrows']) {
                            echo '<span class="king-addons-prev-page king-addons-disabled-arrow">';
                            echo Core::getIcon($s['pagination_pn_icon'], 'left') . '<span>' . esc_html($s['pagination_prev_text']) . '</span></span>';
                        }
                    }
                    echo '</div>';
                }
                for ($i = 1; $i <= $pages; $i++) {
                    if (1 !== $pages && (!($i >= $paged + $range + 1 || $i <= $paged - $range - 1) || $pages <= $showitems)) {
                        if ($paged === $i) {
                            echo '<span class="king-addons-grid-current-page">' . $i . '</span>';
                        } else {
                            echo '<a href="' . esc_url(get_pagenum_link($i)) . '">' . $i . '</a>';
                        }
                    }
                }
                if ('yes' === $s['pagination_prev_next'] || 'yes' === $s['pagination_first_last']) {
                    echo '<div class="king-addons-grid-pagination-right-arrows">';
                    if ('yes' === $s['pagination_prev_next']) {
                        if ($paged < $pages) {
                            echo '<a href="' . esc_url(get_pagenum_link($paged + 1)) . '" class="king-addons-next-page">';
                            echo '<span>' . esc_html($s['pagination_next_text']) . '</span>' . Core::getIcon($s['pagination_pn_icon'], 'right') . '</a>';
                        } elseif ('yes' === $s['pagination_disabled_arrows']) {
                            echo '<span class="king-addons-next-page king-addons-disabled-arrow">';
                            echo '<span>' . esc_html($s['pagination_next_text']) . '</span>' . Core::getIcon($s['pagination_pn_icon'], 'right') . '</span>';
                        }
                    }
                    if ('yes' === $s['pagination_first_last']) {
                        if ($paged <= $pages - 1) {
                            echo '<a href="' . esc_url(get_pagenum_link($pages)) . '" class="king-addons-last-page">';
                            echo '<span>' . esc_html($s['pagination_last_text']) . '</span>' . Core::getIcon($s['pagination_fl_icon'], 'right') . '</a>';
                        } elseif ('yes' === $s['pagination_disabled_arrows']) {
                            echo '<span class="king-addons-last-page king-addons-disabled-arrow">';
                            echo '<span>' . esc_html($s['pagination_last_text']) . '</span>' . Core::getIcon($s['pagination_fl_icon'], 'right') . '</span>';
                        }
                    }
                    echo '</div>';
                }
            }
        } else {
            echo '<a href="' . esc_url(get_pagenum_link($paged + 1)) . '" class="king-addons-load-more-btn" data-e-disable-page-transition>';
            echo esc_html($s['pagination_load_more_text']) . '</a>';
            echo '<div class="king-addons-pagination-loading">';
            switch ($s['pagination_animation']) {
                case 'loader-1':
                    echo '<div class="king-addons-double-bounce"><div class="king-addons-child king-addons-double-bounce1"></div><div class="king-addons-child king-addons-double-bounce2"></div></div>';
                    break;
                case 'loader-2':
                    echo '<div class="king-addons-wave"><div class="king-addons-rect king-addons-rect1"></div><div class="king-addons-rect king-addons-rect2"></div><div class="king-addons-rect king-addons-rect3"></div><div class="king-addons-rect king-addons-rect4"></div><div class="king-addons-rect king-addons-rect5"></div></div>';
                    break;
                case 'loader-3':
                    echo '<div class="king-addons-spinner king-addons-spinner-pulse"></div>';
                    break;
                case 'loader-4':
                    echo '<div class="king-addons-chasing-dots"><div class="king-addons-child king-addons-dot1"></div><div class="king-addons-child king-addons-dot2"></div></div>';
                    break;
                case 'loader-5':
                    echo '<div class="king-addons-three-bounce"><div class="king-addons-child king-addons-bounce1"></div><div class="king-addons-child king-addons-bounce2"></div><div class="king-addons-child king-addons-bounce3"></div></div>';
                    break;
                case 'loader-6':
                    echo '<div class="king-addons-fading-circle">';
                    for ($i = 1; $i <= 12; $i++) {
                        echo '<div class="king-addons-circle king-addons-circle' . $i . '"></div>';
                    }
                    echo '</div>';
                    break;
            }
            echo '</div>';
            echo '<p class="king-addons-pagination-finish">' . esc_html($s['pagination_finish_text']) . '</p>';
        }
        echo '</div>';
    }

    public function add_grid_settings($s)
    {
        if (!king_addons_freemius()->can_use_premium_code__premium_only()) {
            $s['filters_deeplinking'] = '';
            $s['filters_count'] = '';
            $s['filters_default_filter'] = '';
            if (in_array($s['filters_animation'], ['pro-fd', 'pro-fs'])) {
                $s['filters_animation'] = 'zoom';
            }
        }
        $ghrD = $s['layout_gutter_hr']['size'];
        $ghrM = $s['layout_gutter_hr_mobile']['size'] ?? $ghrD;
        $ghrME = $s['layout_gutter_hr_mobile_extra']['size'] ?? $ghrM;
        $ghrT = $s['layout_gutter_hr_tablet']['size'] ?? $ghrME;
        $ghrTE = $s['layout_gutter_hr_tablet_extra']['size'] ?? $ghrT;
        $ghrL = $s['layout_gutter_hr_laptop']['size'] ?? $ghrD;
        $ghrW = $s['layout_gutter_hr_widescreen']['size'] ?? $ghrD;

        $gvrD = $s['layout_gutter_vr']['size'];
        $gvrM = $s['layout_gutter_vr_mobile']['size'] ?? $gvrD;
        $gvrME = $s['layout_gutter_vr_mobile_extra']['size'] ?? $gvrM;
        $gvrT = $s['layout_gutter_vr_tablet']['size'] ?? $gvrME;
        $gvrTE = $s['layout_gutter_vr_tablet_extra']['size'] ?? $gvrT;
        $gvrL = $s['layout_gutter_vr_laptop']['size'] ?? $gvrD;
        $gvrW = $s['layout_gutter_vr_widescreen']['size'] ?? $gvrD;

        $layout_settings = [
            'layout' => $s['layout_select'],
            'columns_desktop' => $s['layout_columns'],
            'gutter_hr' => $ghrD, 'gutter_hr_mobile' => $ghrM, 'gutter_hr_mobile_extra' => $ghrME, 'gutter_hr_tablet' => $ghrT, 'gutter_hr_tablet_extra' => $ghrTE, 'gutter_hr_laptop' => $ghrL, 'gutter_hr_widescreen' => $ghrW,
            'gutter_vr' => $gvrD, 'gutter_vr_mobile' => $gvrM, 'gutter_vr_mobile_extra' => $gvrME, 'gutter_vr_tablet' => $gvrT, 'gutter_vr_tablet_extra' => $gvrTE, 'gutter_vr_laptop' => $gvrL, 'gutter_vr_widescreen' => $gvrW,
            'animation' => $s['layout_animation'], 'animation_duration' => $s['layout_animation_duration'], 'animation_delay' => $s['layout_animation_delay'],
            'deeplinking' => $s['filters_deeplinking'],
            'filters_default_filter' => $s['filters_default_filter'],
            'filters_linkable' => $s['filters_linkable'],
            'filters_count' => $s['filters_count'],
            'filters_hide_empty' => $s['filters_hide_empty'],
            'filters_animation' => $s['filters_animation'],
            'filters_animation_duration' => $s['filters_animation_duration'],
            'filters_animation_delay' => $s['filters_animation_delay'],
            'pagination_type' => $s['pagination_type'],
            'pagination_max_pages' => $this->get_max_num_pages(),
            'lightbox' => [
                'selector' => '.king-addons-grid-image-wrap',
                'iframeMaxWidth' => '60%',
                'hash' => false,
                'autoplay' => $s['lightbox_popup_autoplay'],
                'pause' => $s['lightbox_popup_pause'] * 1000,
                'progressBar' => $s['lightbox_popup_progressbar'],
                'counter' => $s['lightbox_popup_counter'],
                'controls' => $s['lightbox_popup_arrows'],
                'getCaptionFromTitleOrAlt' => $s['lightbox_popup_captions'],
                'thumbnail' => $s['lightbox_popup_thumbnails'],
                'showThumbByDefault' => $s['lightbox_popup_thumbnails_default'],
                'share' => $s['lightbox_popup_sharing'],
                'zoom' => $s['lightbox_popup_zoom'],
                'fullScreen' => $s['lightbox_popup_fullscreen'],
                'download' => $s['lightbox_popup_download'],
            ]
        ];
        $this->add_render_attribute('grid-settings', ['data-settings' => wp_json_encode($layout_settings)]);
    }

    public function add_slider_settings($s)
    {
        $slider_is_rtl = is_rtl();
        $slider_dir = $slider_is_rtl ? 'rtl' : 'ltr';
        if (!king_addons_freemius()->can_use_premium_code__premium_only()) {
            $s['layout_slider_autoplay'] = '';
            $s['layout_slider_autoplay_duration'] = 0;
            $s['layout_slider_pause_on_hover'] = '';
        }
        if (in_array($s['layout_slider_amount'], ['pro-3', 'pro-4', 'pro-5', 'pro-6'])) {
            $s['layout_slider_amount'] = 2; // fallback for free version
        }
        $slider_options = [
            'rtl' => $slider_is_rtl,
            'infinite' => ($s['layout_slider_loop'] === 'yes'),
            'speed' => absint($s['layout_slider_effect_duration'] * 1000),
            'arrows' => true,
            'dots' => true,
            'autoplay' => ($s['layout_slider_autoplay'] === 'yes'),
            'autoplaySpeed' => absint($s['layout_slider_autoplay_duration'] * 1000),
            'pauseOnHover' => $s['layout_slider_pause_on_hover'],
            'prevArrow' => '#king-addons-grid-slider-prev-' . $this->get_id(),
            'nextArrow' => '#king-addons-grid-slider-next-' . $this->get_id(),
            'sliderSlidesToScroll' => +$s['layout_slides_to_scroll'],
            'sliderRows' => $s['layout_slider_rows'] ?? 1,
            'lightbox' => [
                'selector' => 'article:not(.slick-cloned) .king-addons-grid-image-wrap',
                'iframeMaxWidth' => '60%',
                'hash' => false,
                'autoplay' => $s['lightbox_popup_autoplay'],
                'pause' => $s['lightbox_popup_pause'] * 1000,
                'progressBar' => $s['lightbox_popup_progressbar'],
                'counter' => $s['lightbox_popup_counter'],
                'controls' => $s['lightbox_popup_arrows'],
                'getCaptionFromTitleOrAlt' => $s['lightbox_popup_captions'],
                'thumbnail' => $s['lightbox_popup_thumbnails'],
                'showThumbByDefault' => $s['lightbox_popup_thumbnails_default'],
                'share' => $s['lightbox_popup_sharing'],
                'zoom' => $s['lightbox_popup_zoom'],
                'fullScreen' => $s['lightbox_popup_fullscreen'],
                'download' => $s['lightbox_popup_download']
            ]
        ];
        if ($s['layout_slider_amount'] === 1 && $s['layout_slider_effect'] === 'fade') {
            $slider_options['fade'] = true;
        }
        $this->add_render_attribute('slider-settings', [
            'dir' => esc_attr($slider_dir),
            'data-slick' => wp_json_encode($slider_options),
        ]);
    }

    protected function render()
    {
        $s = $this->get_settings();
        $posts = new WP_Query($this->get_main_query_args());
        if ($posts->have_posts()) {
            if ('slider' !== $s['layout_select']) {
                $this->render_grid_filters($s);
                $this->add_grid_settings($s);
                $render_attribute = $this->get_render_attribute_string('grid-settings');
            } else {
                $this->add_slider_settings($s);
                $render_attribute = $this->get_render_attribute_string('slider-settings');
            }
            echo '<section class="king-addons-grid king-addons-media-grid elementor-clearfix" ' . $render_attribute . '>';
            while ($posts->have_posts()) {
                $posts->the_post();
                // Skip non-image attachments (in case)
                if (!wp_attachment_is_image(get_the_ID())) continue;

                $post_class = implode(' ', get_post_class('king-addons-grid-item elementor-clearfix', get_the_ID()));
                echo '<article class="' . esc_attr($post_class) . '">';
                echo '<div class="king-addons-grid-item-inner">';
                $this->get_elements_by_location('above', $s, get_the_ID());
                echo '<div class="king-addons-grid-media-wrap' . $this->get_image_effect_class($s) . ' ">';
                $this->render_post_thumbnail($s);
                echo '<div class="king-addons-grid-media-hover king-addons-animation-wrap">';
                $this->render_media_overlay($s);
                $this->get_elements_by_location('over', $s, get_the_ID());
                echo '</div></div>';
                $this->get_elements_by_location('below', $s, get_the_ID());
                echo '</div></article>';
            }
            wp_reset_postdata();
            echo '</section>';

            if ('slider' === $s['layout_select']) {
                echo '<div class="king-addons-grid-slider-arrow-container">';
                echo '<div class="king-addons-grid-slider-prev-arrow king-addons-grid-slider-arrow" id="king-addons-grid-slider-prev-' . esc_attr($this->get_id()) . '">' . Core::getIcon($s['layout_slider_nav_icon'], '') . '</div>';
                echo '<div class="king-addons-grid-slider-next-arrow king-addons-grid-slider-arrow" id="king-addons-grid-slider-next-' . esc_attr($this->get_id()) . '">' . Core::getIcon($s['layout_slider_nav_icon'], '') . '</div>';
                echo '</div><div class="king-addons-grid-slider-dots"></div>';
            }
            $this->render_grid_pagination($s);
        } else {
            echo '<h2>' . esc_html($s['query_not_found_text']) . '</h2>';
        }
    }


}