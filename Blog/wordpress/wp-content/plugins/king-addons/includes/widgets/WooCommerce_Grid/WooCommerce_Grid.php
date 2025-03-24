<?php

namespace King_Addons;

use Elementor\Embed;
use Elementor\Icons_Manager;
use Elementor\Plugin;
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

class WooCommerce_Grid extends Widget_Base
{

    public function get_name()
    {
        return 'king-addons-woocommerce-grid';
    }

    public function get_title()
    {
        return esc_html__('WooCommerce Grid & Slider/Carousel', 'king-addons');
    }

    public function get_icon()
    {
        return 'king-addons-icon king-addons-woocommerce-grid';
    }

    public function get_categories()
    {
        return ['king-addons'];
    }

    public function get_keywords()
    {
        return ['king addons', 'king', 'addons', 'kingaddons', 'king-addons', 'shop grid', 'product grid', 'slick', 'loop', 'sales', 'filter',
            'woo', 'commerce', 'woocommerce', 'shop', 'product', 'products', 'carousel', 'slider', 'grid', 'masonry', 'store', 'sale',
            'woocommerce', 'product slider', 'product carousel', 'isotope', 'masonry grid', 'filterable grid', 'loop grid'];
    }

    public function get_script_depends()
    {
        return [
            KING_ADDONS_ASSETS_UNIQUE_KEY . '-grid-woocommerce',
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

    public function add_control_secondary_img_on_hover()
    {
        $this->add_control(
            'secondary_img_on_hover',
            [
                'label' => sprintf(__('2nd Image on Hover %s', 'king-addons'), '<i class="eicon-pro-icon"></i>'),
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

    public function add_control_query_selection()
    {
        $this->add_control(
            'query_selection',
            [
                'label' => esc_html__('Query Products', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'dynamic',
                'options' => [
                    'dynamic' => esc_html__('Dynamic', 'king-addons'),
                    'manual' => esc_html__('Manual', 'king-addons'),
                    'current' => esc_html__('Current Query', 'king-addons'),
                    'pro-fr' => esc_html__('Featured (Pro)', 'king-addons'),
                    'pro-os' => esc_html__('On Sale (Pro)', 'king-addons'),
                    'pro-us' => esc_html__('Upsell (Pro)', 'king-addons'),
                    'pro-cs' => esc_html__('Cross-sell (Pro)', 'king-addons'),
                ],
            ]
        );
    }

    public function add_control_query_orderby()
    {
        $this->add_control(
            'query_orderby',
            [
                'label' => esc_html__('Order By', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'date',
                'options' => [
                    'default' => esc_html__('Default', 'king-addons'),
                    'date' => esc_html__('Date', 'king-addons'),
                    'sales' => esc_html__('Sales', 'king-addons'),
                    'rating' => esc_html__('Rating', 'king-addons'),
                    'price-low' => esc_html__('Price - Low to High', 'king-addons'),
                    'price-high' => esc_html__('Price - High to Low', 'king-addons'),
                    'pro-rn' => esc_html__('Random (Pro)', 'king-addons'),
                ],
                'condition' => [
                    'query_selection' => ['dynamic', 'onsale', 'featured', 'upsell', 'cross-sell'],
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
                'label_block' => true,
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

    public function add_control_sort_and_results_count()
    {
        $this->add_control(
            'layout_sort_and_results_count',
            [
                'label' => sprintf(__('Show Sorting %s', 'king-addons'), '<i class="eicon-pro-icon"></i>'),
                'type' => Controls_Manager::SWITCHER,
                'separator' => 'before',
                'classes' => 'king-addons-pro-control',
                'condition' => [
                    'layout_select!' => 'slider',
                ]
            ]
        );
    }

    public function add_section_grid_sorting()
    {
    }

    public function add_section_style_sort_and_results()
    {
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

    public function add_control_stack_layout_slider_autoplay()
    {
    }

    public function add_option_element_select()
    {
        return [
            'title' => esc_html__('Title', 'king-addons'),
            'excerpt' => esc_html__('Excerpt', 'king-addons'),
            'product_cat' => esc_html__('Categories', 'king-addons'),
            'product_tag' => esc_html__('Tags', 'king-addons'),
            'pro-cfa' => esc_html__('Custom Fields/Attributes', 'king-addons'),
            'status' => esc_html__('Status', 'king-addons'),
            'price' => esc_html__('Price', 'king-addons'),
            'pro-sd' => esc_html__('Sale Dates (Pro)', 'king-addons'),
            'rating' => esc_html__('Rating', 'king-addons'),
            'add-to-cart' => esc_html__('Add to Cart', 'king-addons'),
            'pro-ws' => esc_html__('Wishlist Button (Pro)', 'king-addons'),
            'pro-cm' => esc_html__('Compare Button (Pro)', 'king-addons'),
            'lightbox' => esc_html__('Lightbox', 'king-addons'),
            'separator' => esc_html__('Separator', 'king-addons'),
            'pro-lk' => esc_html__('Likes (Pro)', 'king-addons'),
            'pro-shr' => esc_html__('Sharing (Pro)', 'king-addons'),
        ];
    }

    public function add_repeater_args_element_custom_field()
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

    public function add_repeater_args_element_custom_field_style()
    {
        return [
            'type' => Controls_Manager::HIDDEN,
            'default' => ''
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

    public function add_repeater_args_element_show_added_tc_popup()
    {
        return [
            'type' => Controls_Manager::HIDDEN,
            'default' => ''
        ];
    }

    public function add_repeater_args_element_show_added_to_wishlist_popup()
    {
        return [
            'type' => Controls_Manager::HIDDEN,
            'default' => ''
        ];
    }

    public function add_repeater_args_element_show_added_to_compare_popup()
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

    public function add_control_lightbox_popup_thumbnails()
    {
    }

    public function add_control_lightbox_popup_thumbnails_default()
    {
    }

    public function add_control_lightbox_popup_sharing()
    {
    }

    public function add_control_filters_deeplinking()
    {
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
                'render_type' => 'template',
                'separator' => 'after'
            ]
        );
    }

    public function add_section_added_to_cart_popup()
    {
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

    public function add_control_categories_pointer_color_hr()
    {
    }

    public function add_control_categories_pointer()
    {
    }

    public function add_control_categories_pointer_height()
    {
    }

    public function add_control_categories_pointer_animation()
    {
    }

    public function add_control_tags_pointer_color_hr()
    {
    }

    public function add_control_tags_pointer()
    {
    }

    public function add_control_tags_pointer_height()
    {
    }

    public function add_control_tags_pointer_animation()
    {
    }

    public function add_control_add_to_cart_animation()
    {
    }

    public function add_control_add_to_cart_animation_height()
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

        $this->add_control_query_selection();

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


        Core::renderUpgradeProNotice($this, Controls_Manager::RAW_HTML, 'woocommerce-grid', 'query_selection', ['pro-fr', 'pro-os', 'pro-us', 'pro-cs']);

        $this->add_control_query_orderby();


        Core::renderUpgradeProNotice($this, Controls_Manager::RAW_HTML, 'woocommerce-grid', 'query_orderby', ['pro-rn']);


        $this->add_control(
            'query_taxonomy_product_cat',
            [
                'label' => esc_html__('Categories', 'king-addons'),
                'type' => 'king-addons-ajax-select2',
                'options' => 'ajaxselect2/getTaxonomies',
                'query_slug' => 'product_cat',
                'multiple' => true,
                'label_block' => true,
                'condition' => [
                    'query_selection' => ['dynamic', 'onsale', 'featured'],
                ],
            ]
        );


        $this->add_control(
            'query_taxonomy_product_tag',
            [
                'label' => esc_html__('Tags', 'king-addons'),
                'type' => 'king-addons-ajax-select2',
                'options' => 'ajaxselect2/getTaxonomies',
                'query_slug' => 'product_tag',
                'multiple' => true,
                'label_block' => true,
                'condition' => [
                    'query_selection' => ['dynamic', 'onsale', 'featured'],
                ],
            ]
        );


        $this->add_control(
            'query_exclude_products',
            [
                'label' => esc_html__('Exclude Products', 'king-addons'),
                'type' => 'king-addons-ajax-select2',
                'options' => 'ajaxselect2/getPostsByPostType',
                'query_slug' => 'product',
                'multiple' => true,
                'label_block' => true,
                'condition' => [
                    'query_selection!' => ['manual', 'onsale', 'current', 'upsell', 'cross-sell'],
                ],
            ]
        );


        $this->add_control(
            'query_manual_products',
            [
                'label' => esc_html__('Select Products', 'king-addons'),
                'type' => 'king-addons-ajax-select2',
                'options' => 'ajaxselect2/getPostsByPostType',
                'query_slug' => 'product',
                'multiple' => true,
                'label_block' => true,
                'condition' => [
                    'query_selection' => 'manual',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'query_posts_per_page',
            [
                'label' => esc_html__('Products Per Page', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'default' => 9,
                'min' => 0,
                'condition' => [
                    'query_selection!' => 'current',
                ],
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
                    'query_selection' => ['dynamic', 'current'],
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
                'default' => esc_html__('No Products Found!', 'king-addons'),
                'condition' => [
                    'query_selection' => ['dynamic', 'current'],
                ]
            ]
        );

        $this->add_control(
            'query_randomize',
            [
                'label' => esc_html__('Randomize Query', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'rand',
                'condition' => [
                    'query_selection' => ['manual', 'current'],
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

        $this->add_control(
            'query_exclude_out_of_stock',
            [
                'label' => esc_html__('Exclude Out Of Stock', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'label_block' => false
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


        Core::renderUpgradeProNotice($this, Controls_Manager::RAW_HTML, 'woocommerce-grid', 'layout_select', ['pro-ms']);

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
                    'raw' => 'Grid Columns option is fully supported<br> in the <strong><a href="https://kingaddons.com/pricing/?utm_source=kng-module-woocommerce-grid-settings-upgrade-pro&utm_medium=plugin&utm_campaign=kng" target="_blank">Pro version</a></strong>',
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
                'default' => [
                    'size' => 20,
                ],
                'widescreen_default' => [
                    'size' => 20,
                ],
                'laptop_default' => [
                    'size' => 20,
                ],
                'tablet_extra_default' => [
                    'size' => 20,
                ],
                'tablet_default' => [
                    'size' => 20,
                ],
                'mobile_extra_default' => [
                    'size' => 20,
                ],
                'mobile_default' => [
                    'size' => 20,
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
                    'size' => 30,
                ],
                'widescreen_default' => [
                    'size' => 30,
                ],
                'laptop_default' => [
                    'size' => 30,
                ],
                'tablet_extra_default' => [
                    'size' => 30,
                ],
                'tablet_default' => [
                    'size' => 30,
                ],
                'mobile_extra_default' => [
                    'size' => 30,
                ],
                'mobile_default' => [
                    'size' => 30,
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

        $this->add_control_sort_and_results_count();

        if (!king_addons_freemius()->can_use_premium_code__premium_only()) {
            $this->add_control(
                'sort_and_results_count_pro_notice',
                [
                    'type' => Controls_Manager::RAW_HTML,
                    'raw' => 'Grid Sorting option is available<br> in the <strong><a href="https://kingaddons.com/pricing/?utm_source=kng-module-woocommerce-grid-settings-upgrade-pro&utm_medium=plugin&utm_campaign=kng" target="_blank">Pro version</a></strong>',
                    'content_classes' => 'king-addons-pro-notice',
                ]
            );
        }

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

        $this->add_control(
            'filters_experiment',
            [
                'label' => esc_html__('Filters & Load More Experiment', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'no',
                'return_value' => 'yes',
                'render_type' => 'template',
                'conditions' => [
                    'relation' => 'and',
                    'terms' => [
                        [
                            'name' => 'layout_filters',
                            'operator' => '!=',
                            'value' => '',
                        ],
                        [
                            'relation' => 'or',
                            'terms' => [
                                [
                                    'relation' => 'and',
                                    'terms' => [
                                        [
                                            'name' => 'layout_pagination',
                                            'operator' => '!=',
                                            'value' => '',
                                        ],
                                        [
                                            'name' => 'pagination_type',
                                            'operator' => 'in',
                                            'value' => ['load-more', 'infinite'],
                                        ],
                                    ]
                                ],
                                [
                                    'name' => 'layout_pagination',
                                    'operator' => '==',
                                    'value' => '',
                                ]
                            ],
                        ],
                    ]
                ]
            ]
        );

        $this->add_control_open_links_in_new_tab();

        $this->add_control_layout_animation();


        Core::renderUpgradeProNotice($this, Controls_Manager::RAW_HTML, 'woocommerce-grid', 'layout_animation', ['pro-fd', 'pro-fs']);

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
                'frontend_available' => true,
                'default' => 2,
                'render_type' => 'template',
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


        Core::renderUpgradeProNotice($this, Controls_Manager::RAW_HTML, 'woocommerce-grid', 'layout_slider_dots_position', ['pro-vr']);

        $this->add_control_stack_layout_slider_autoplay();

        $this->add_control(
            'layout_slider_loop',
            [
                'label' => esc_html__('Infinite Loop', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'frontend_available' => true,
                'separator' => 'after',
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
            'section_grid_linked_products',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Upsell / Cross-sell Title', 'king-addons'),
                'tab' => Controls_Manager::TAB_CONTENT,
                'condition' => [
                    'query_selection' => ['upsell', 'cross-sell'],

                ]
            ]
        );

        $this->add_control(
            'grid_linked_products_heading',
            [
                'label' => esc_html__('Heading', 'king-addons'),
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => 'You may be interested in...',
                'condition' => [
                    'query_selection' => ['upsell', 'cross-sell'],
                ]
            ]
        );

        $this->add_control(
            'grid_linked_products_heading_tag',
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
                    'query_selection' => ['upsell'],
                    'grid_linked_products_heading!' => ''
                ]
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
                'options' => $element_select + Core::getWooCommerceTaxonomies(),
                'separator' => 'after'
            ]
        );

        Core::renderUpgradeProNotice($repeater, Controls_Manager::RAW_HTML, 'woocommerce-grid', 'element_select', ['pro-lk', 'pro-shr', 'pro-sd']);

        Core::renderUpgradeProNotice($repeater, Controls_Manager::RAW_HTML, 'woocommerce-grid', 'element_select', ['pro-ws', 'pro-cm']);

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
                    'raw' => 'Vertical Align option is available<br> in the <strong><a href="https://kingaddons.com/pricing/?utm_source=kng-module-woocommerce-grid-settings-upgrade-pro&utm_medium=plugin&utm_campaign=kng" target="_blank">Pro version</a></strong>',

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
                        'likes',
                        'sharing',
                        'lightbox',
                        'separator',
                        'post_format',
                        'status',
                        'price',
                        'rating',
                        'add-to-cart',
                        'wishlist-button',
                        'compare-button'
                    ],
                ],
                'separator' => 'after'
            ]
        );

        $repeater->add_control(
            'element_sale_dates_layout',
            [
                'label' => esc_html__('Layout', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'inline',
                'options' => [
                    'inline' => esc_html__('Inline', 'king-addons'),
                    'block' => esc_html__('Block', 'king-addons'),
                ],
                'condition' => [
                    'element_select' => [
                        'sale_dates',
                    ]
                ]
            ]
        );

        $repeater->add_control(
            'element_sale_dates_sep',
            [
                'label' => esc_html__('Separator', 'king-addons'),
                'type' => Controls_Manager::TEXT,
                'default' => ' - ',
                'condition' => [
                    'element_select' => [
                        'sale_dates',
                    ],
                    'element_sale_dates_layout' => 'inline'
                ],
                'separator' => 'after'
            ]
        );

        $repeater->add_control('element_custom_field', $this->add_repeater_args_element_custom_field());

        $repeater->add_control('element_custom_field_btn_link', $this->add_repeater_args_element_custom_field_btn_link());

        $repeater->add_control('element_custom_field_style', $this->add_repeater_args_element_custom_field_style());

        $repeater->add_control('element_custom_field_new_tab', $this->add_repeater_args_element_custom_field_new_tab());

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
                'query_slug' => 'product_cat',
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
                'query_slug' => 'product_cat',
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
            'element_rating_style',
            [
                'label' => esc_html__('Select Icon', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'style-1' => 'Icon 1',
                    'style-2' => 'Icon 2',
                ],
                'default' => 'style-2',
                'condition' => [
                    'element_select' => 'rating',
                ]
            ]
        );

        $repeater->add_control(
            'element_rating_score',
            [
                'label' => esc_html__('Show Score', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'condition' => [
                    'element_select' => 'rating',
                ],
            ]
        );

        $repeater->add_control(
            'element_rating_unmarked_style',
            [
                'label' => esc_html__('Unmarked Style', 'king-addons'),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'options' => [
                    'solid' => [
                        'title' => esc_html__('Solid', 'king-addons'),
                        'icon' => 'eicon-star',
                    ],
                    'outline' => [
                        'title' => esc_html__('Outline', 'king-addons'),
                        'icon' => 'eicon-star-o',
                    ],
                ],
                'default' => 'outline',
                'condition' => [
                    'element_select' => 'rating',
                    'element_rating_score!' => 'yes',
                ],
            ]
        );

        $repeater->add_control(
            'element_status_offstock',
            [
                'label' => esc_html__('Show Out of Stock Badge', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'condition' => [
                    'element_select' => 'status',
                ],
            ]
        );

        $repeater->add_control(
            'element_status_featured',
            [
                'label' => esc_html__('Show Featured Badge', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'return_value' => 'yes',
                'condition' => [
                    'element_select' => 'status',
                ],
            ]
        );

        $repeater->add_control(
            'element_addcart_simple_txt',
            [
                'label' => esc_html__('Simple Item Text', 'king-addons'),
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => 'Add to Cart',
                'condition' => [
                    'element_select' => 'add-to-cart',
                ]
            ]
        );

        $repeater->add_control(
            'element_addcart_grouped_txt',
            [
                'label' => esc_html__('Grouped Item Text', 'king-addons'),
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => 'Select Options',
                'condition' => [
                    'element_select' => 'add-to-cart',
                ]
            ]
        );

        $repeater->add_control(
            'element_addcart_variable_txt',
            [
                'label' => esc_html__('Variable Item Text', 'king-addons'),
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => 'View Products',
                'separator' => 'after',
                'condition' => [
                    'element_select' => 'add-to-cart',
                ]
            ]
        );

        $repeater->add_control('element_show_added_tc_popup', $this->add_repeater_args_element_show_added_tc_popup());

        $repeater->add_control('element_show_added_to_wishlist_popup', $this->add_repeater_args_element_show_added_to_wishlist_popup());

        $repeater->add_control('element_show_added_to_compare_popup', $this->add_repeater_args_element_show_added_to_compare_popup());

        $repeater->add_control(
            'element_open_links_in_new_tab',
            [
                'label' => esc_html__('Open Links in New Tab', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'condition' => [
                    'element_select' => ['wishlist-button', 'compare-button']
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
                        'separator',
                        'status',
                        'price',
                        'sale_dates',
                        'rating',
                        'add-to-cart',
                        'wishlist-button',
                        'compare-button',
                        'excerpt'
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
                        'separator',
                        'status',
                        'price',
                        'sale_dates',
                        'rating',
                        'add-to-cart',
                        'wishlist-button',
                        'compare-button',
                        'excerpt'
                    ],
                    'element_extra_text_pos!' => 'none'
                ]
            ]
        );

        $repeater->add_control(
            'show_sale_starts_date',
            [
                'label' => esc_html__('Sale Starts Date', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'return_value' => 'yes',
                'condition' => [
                    'element_select' => [
                        'sale_dates'
                    ]
                ],
            ]
        );

        $repeater->add_control(
            'element_sale_starts_text',
            [
                'label' => esc_html__('Text', 'king-addons'),
                'type' => Controls_Manager::TEXT,
                'default' => '',
                'condition' => [
                    'element_select' => [
                        'sale_dates'
                    ],
                    'show_sale_starts_date' => 'yes'
                ]
            ]
        );

        $repeater->add_control(
            'show_sale_ends_date',
            [
                'label' => esc_html__('Sale Ends Date', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'return_value' => 'yes',
                'condition' => [
                    'element_select' => [
                        'sale_dates'
                    ]
                ],
            ]
        );

        $repeater->add_control(
            'element_sale_ends_text',
            [
                'label' => esc_html__('Text', 'king-addons'),
                'type' => Controls_Manager::TEXT,
                'default' => '',
                'condition' => [
                    'element_select' => [
                        'sale_dates'
                    ],
                    'show_sale_ends_date' => 'yes'
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
                        'likes',
                        'sharing',
                        'status',
                        'price',
                        'sale_dates',
                        'rating',
                        'excerpt',
                        'wishlist-button',
                        'compare-button'
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
                        'likes',
                        'sharing',
                        'status',
                        'price',
                        'rating',
                        'wishlist-button',
                        'compare-button'
                    ],
                    'element_extra_icon_pos!' => 'none'
                ]
            ]
        );

        $repeater->add_control(
            'show_icon',
            [
                'label' => esc_html__('Show Icon', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'return_value' => 'yes',
                'separator' => 'before',
                'condition' => [
                    'element_select' => [
                        'wishlist-button',
                        'compare-button'
                    ]
                ]
            ]
        );

        $repeater->add_control(
            'show_text',
            [
                'label' => esc_html__('Show Text', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'condition' => [
                    'element_select' => [
                        'wishlist-button',
                        'compare-button'
                    ]
                ]
            ]
        );

        $repeater->add_control(
            'add_to_wishlist_text',
            [
                'label' => esc_html__('Add Text', 'king-addons'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Add to Wishlist',
                'condition' => [
                    'element_select' => [
                        'wishlist-button'
                    ]
                ]
            ]
        );

        $repeater->add_control(
            'add_to_compare_text',
            [
                'label' => esc_html__('Add Text', 'king-addons'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Add to Compare',
                'condition' => [
                    'element_select' => [
                        'compare-button'
                    ]
                ]
            ]
        );

        $repeater->add_control(
            'remove_from_wishlist_text',
            [
                'label' => esc_html__('Remove Text', 'king-addons'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Remove from Wishlist',
                'condition' => [
                    'element_select' => [
                        'wishlist-button'
                    ]
                ]
            ]
        );

        $repeater->add_control(
            'remove_from_compare_text',
            [
                'label' => esc_html__('Remove Text', 'king-addons'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Remove from Compare',
                'condition' => [
                    'element_select' => [
                        'compare-button'
                    ]
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


        Core::renderUpgradeProNotice($repeater, Controls_Manager::RAW_HTML, 'woocommerce-grid', 'element_animation', ['pro-slrt', 'pro-slxrt', 'pro-slbt', 'pro-sllt', 'pro-sltp', 'pro-slxlt', 'pro-sktp', 'pro-skrt', 'pro-skbt', 'pro-sklt', 'pro-scup', 'pro-scdn', 'pro-rllt', 'pro-rlrt',]);

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


        Core::renderUpgradeProNotice($repeater, Controls_Manager::RAW_HTML, 'woocommerce-grid', 'element_animation_timing', Core::getAnimationTimingsConditionsPro());

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
                        'element_select' => 'status',
                        'element_location' => 'over',
                        'element_align_vr' => 'middle',
                        'element_align_hr' => 'middle',
                        'element_animation' => 'fade-in',
                    ],
                    [
                        'element_select' => 'product_cat',
                    ],
                    [
                        'element_select' => 'title',
                    ],
                    [
                        'element_select' => 'rating',
                    ],
                    [
                        'element_select' => 'price',
                    ],
                    [
                        'element_select' => 'add-to-cart',
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


        Core::renderUpgradeProNotice($this, Controls_Manager::RAW_HTML, 'woocommerce-grid', 'overlay_animation', ['pro-slrt', 'pro-slxrt', 'pro-slbt', 'pro-sllt', 'pro-sltp', 'pro-slxlt', 'pro-sktp', 'pro-skrt', 'pro-skbt', 'pro-sklt', 'pro-scup', 'pro-scdn', 'pro-rllt', 'pro-rlrt',]);

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


        Core::renderUpgradeProNotice($this, Controls_Manager::RAW_HTML, 'woocommerce-grid', 'overlay_animation_timing', Core::getAnimationTimingsConditionsPro());

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

        Core::renderUpgradeProNotice($this, Controls_Manager::RAW_HTML, 'woocommerce-grid', 'image_effects', ['pro-zi', 'pro-zo', 'pro-go', 'pro-bo']);

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


        Core::renderUpgradeProNotice($this, Controls_Manager::RAW_HTML, 'woocommerce-grid', 'image_effects_animation_timing', Core::getAnimationTimingsConditionsPro());

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
                'raw' => sprintf(__('You can change Lightbox Popup styling options globaly. Navigate to <strong>Dashboard > %s > Settings</strong>.', 'king-addons'), Core::getPluginName()),
                'type' => Controls_Manager::RAW_HTML,
                'separator' => 'before',
                'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
            ]
        );

        $this->end_controls_section();

        $this->add_section_grid_sorting();


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
                'options' => [
                    'product_cat' => esc_html__('Categories', 'king-addons'),
                    'product_tag' => esc_html__('Tags', 'king-addons'),
                ],
                'default' => 'product_cat',
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
                'default' => 'All',
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


        Core::renderUpgradeProNotice($this, Controls_Manager::RAW_HTML, 'woocommerce-grid', 'filters_animation', ['pro-fd', 'pro-fs']);

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


        Core::renderUpgradeProNotice($this, Controls_Manager::RAW_HTML, 'woocommerce-grid', 'pagination_type', ['pro-is']);

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
                'default' => 'no',
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
                'default' => 'no',
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

        Core::renderProFeaturesSection($this, '', Controls_Manager::RAW_HTML, 'woocommerce-grid', [
            'Grid Columns 1, 2, 3, 4, 5, 6',
            'Masonry Layout',
            'Products Slider Columns (Carousel) 1, 2, 3, 4, 5, 6',
            'Products Slider Autoplay Options',
            'Products Slider Advanced Navigation Positioning',
            'Products Slider Advanced Pagination Positioning',
            'Infinite Scrolling Pagination',
            'Current Page Query, Random Products Query',
            'Custom Fields/Attributes Support',
            'Wishlist & Compare Buttons',
            'Advanced Grid Loading Animations (Fade In & Slide Up)',
            'Advanced Grid Elements Positioning',
            'Advanced Products Likes',
            'Advanced Products Sharing',
            'Unlimited Image Overlay Animations',
            'Image Overlay GIF Upload Option',
            'Image Overlay Blend Mode',
            'Image Effects: Zoom, Grayscale, Blur',
            'Lightbox Thumbnail Gallery, Lightbox Image Sharing Button',
            'Grid Category Filter Deeplinking',
            'Grid Category Filter Icons Select',
            'Grid Category Filter Count',
            'Grid Item Even/Odd Background Color',
            'Title, Category, Read More Advanced Link Hover Animation',
            'Secondary Featured Image',
            'Open Links in New Tab'
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
                'prefix_class' => 'king-addons-item-styles-'
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
                    '{{WRAPPER}}.king-addons-item-styles-wrapper .king-addons-grid-item' => 'background-color: {{VALUE}}'
                ]
            ]
        );

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
                ]
            ]
        );

        $this->add_control_grid_item_even_bg_color();

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
                'size_units' => ['px', '%'],
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
                'default' => 0.3,
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
                'default' => '#333333',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-item-excerpt .inner-block' => 'color: {{VALUE}}',
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
            'section_style_categories',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Categories', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            ]
        );

        $this->start_controls_tabs('tabs_grid_categories_style');

        $this->start_controls_tab(
            'tab_grid_categories_normal',
            [
                'label' => esc_html__('Normal', 'king-addons'),
            ]
        );

        $this->add_control(
            'categories_color',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#9C9C9C',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-product-categories .inner-block a' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'categories_bg_color',
            [
                'label' => esc_html__('Background Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-product-categories .inner-block a' => 'background-color: {{VALUE}}',
                ]
            ]
        );

        $this->add_control(
            'categories_border_color',
            [
                'label' => esc_html__('Border Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#E8E8E8',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-product-categories .inner-block a' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'categories_extra_text_color',
            [
                'label' => esc_html__('Extra Text Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#9C9C9C',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-product-categories .inner-block span[class*="king-addons-grid-extra-text"]' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'categories_extra_icon_color',
            [
                'label' => esc_html__('Extra Icon Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#9C9C9C',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-product-categories .inner-block [class*="king-addons-grid-extra-icon"] i' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .king-addons-grid-product-categories .inner-block [class*="king-addons-grid-extra-icon"] svg' => 'fill: {{VALUE}}'
                ],
                'separator' => 'after',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_grid_categories_hover',
            [
                'label' => esc_html__('Hover', 'king-addons'),
            ]
        );

        $this->add_control(
            'categories_color_hr',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#5B03FF',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-product-categories .inner-block a:hover' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .king-addons-grid-product-categories .king-addons-pointer-item:before' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .king-addons-grid-product-categories .king-addons-pointer-item:after' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'categories_bg_color_hr',
            [
                'label' => esc_html__('Background Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-product-categories .inner-block a:hover' => 'background-color: {{VALUE}}',
                ]
            ]
        );

        $this->add_control(
            'categories_border_color_hr',
            [
                'label' => esc_html__('Border Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#E8E8E8',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-product-categories .inner-block a:hover' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control_categories_pointer_color_hr();

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_control_categories_pointer();

        $this->add_control_categories_pointer_height();

        $this->add_control_categories_pointer_animation();

        $this->add_control(
            'categories_transition_duration',
            [
                'label' => esc_html__('Transition Duration (seconds)', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'default' => 0.1,
                'min' => 0,
                'max' => 5,
                'step' => 0.1,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-product-categories .inner-block a' => 'transition-duration: {{VALUE}}s',
                    '{{WRAPPER}} .king-addons-grid-product-categories .king-addons-pointer-item:before' => 'transition-duration: {{VALUE}}s',
                    '{{WRAPPER}} .king-addons-grid-product-categories .king-addons-pointer-item:after' => 'transition-duration: {{VALUE}}s',
                ],
                'separator' => 'after',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'categories_typography',
                'selector' => '{{WRAPPER}} .king-addons-grid-product-categories'
            ]
        );

        $this->add_control(
            'categories_border_type',
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
                    '{{WRAPPER}} .king-addons-grid-product-categories .inner-block a' => 'border-style: {{VALUE}};',
                ],
                'render_type' => 'template',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'categories_border_width',
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
                    '{{WRAPPER}} .king-addons-grid-product-categories .inner-block a' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'render_type' => 'template',
                'condition' => [
                    'categories_border_type!' => 'none',
                ],
            ]
        );

        $this->add_control(
            'categories_text_spacing',
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
                    'size' => 10,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-product-categories .king-addons-grid-extra-text-left' => 'padding-right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .king-addons-grid-product-categories .king-addons-grid-extra-text-right' => 'padding-left: {{SIZE}}{{UNIT}};',
                ],
                'render_type' => 'template',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'categories_icon_spacing',
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
                    'size' => 10,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-product-categories .king-addons-grid-extra-icon-left' => 'padding-right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .king-addons-grid-product-categories .king-addons-grid-extra-icon-right' => 'padding-left: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'categories_gutter',
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
                    '{{WRAPPER}} .king-addons-grid-product-categories .inner-block a' => 'margin-right: {{SIZE}}{{UNIT}};',
                ],
                'render_type' => 'template',
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'categories_padding',
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
                    '{{WRAPPER}} .king-addons-grid-product-categories .inner-block a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'render_type' => 'template',
            ]
        );

        $this->add_responsive_control(
            'categories_margin',
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
                    '{{WRAPPER}} .king-addons-grid-product-categories .inner-block' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'render_type' => 'template',
            ]
        );

        $this->add_control(
            'categories_radius',
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
                    '{{WRAPPER}} .king-addons-grid-product-categories .inner-block a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->end_controls_section();


        $this->start_controls_section(
            'section_style_tags',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Tags', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            ]
        );

        $this->start_controls_tabs('tabs_grid_tags_style');

        $this->start_controls_tab(
            'tab_grid_tags_normal',
            [
                'label' => esc_html__('Normal', 'king-addons'),
            ]
        );

        $this->add_control(
            'tags_color',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#9C9C9C',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-product-tags .inner-block a' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'tags_bg_color',
            [
                'label' => esc_html__('Background Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-product-tags .inner-block a' => 'background-color: {{VALUE}}',
                ]
            ]
        );

        $this->add_control(
            'tags_border_color',
            [
                'label' => esc_html__('Border Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#E8E8E8',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-product-tags .inner-block a' => 'border-color: {{VALUE}}',
                ]
            ]
        );

        $this->add_control(
            'tags_extra_text_color',
            [
                'label' => esc_html__('Extra Text Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#9C9C9C',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-product-tags .inner-block span[class*="king-addons-grid-extra-text"]' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'tags_extra_icon_color',
            [
                'label' => esc_html__('Extra Icon Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#9C9C9C',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-product-tags .inner-block [class*="king-addons-grid-extra-icon"] i' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .king-addons-grid-product-tags .inner-block [class*="king-addons-grid-extra-icon"] svg' => 'fill: {{VALUE}}'
                ],
                'separator' => 'after',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_grid_tags_hover',
            [
                'label' => esc_html__('Hover', 'king-addons'),
            ]
        );

        $this->add_control(
            'tags_color_hr',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#5B03FF',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-product-tags .inner-block a:hover' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .king-addons-grid-product-tags .king-addons-pointer-item:before' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .king-addons-grid-product-tags .king-addons-pointer-item:after' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'tags_bg_color_hr',
            [
                'label' => esc_html__('Background Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-product-tags .inner-block a:hover' => 'background-color: {{VALUE}}',
                ]
            ]
        );

        $this->add_control(
            'tags_border_color_hr',
            [
                'label' => esc_html__('Border Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#E8E8E8',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-product-tags .inner-block a:hover' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control_tags_pointer_color_hr();

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_control_tags_pointer();

        $this->add_control_tags_pointer_height();

        $this->add_control_tags_pointer_animation();

        $this->add_control(
            'tags_transition_duration',
            [
                'label' => esc_html__('Transition Duration (seconds)', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'default' => 0.1,
                'min' => 0,
                'max' => 5,
                'step' => 0.1,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-product-tags .inner-block a' => 'transition-duration: {{VALUE}}s',
                    '{{WRAPPER}} .king-addons-grid-product-tags .king-addons-pointer-item:before' => 'transition-duration: {{VALUE}}s',
                    '{{WRAPPER}} .king-addons-grid-product-tags .king-addons-pointer-item:after' => 'transition-duration: {{VALUE}}s',
                ],
                'separator' => 'after',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'tags_typography',
                'selector' => '{{WRAPPER}} .king-addons-grid-product-tags'
            ]
        );

        $this->add_control(
            'tags_border_type',
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
                    '{{WRAPPER}} .king-addons-grid-product-tags .inner-block a' => 'border-style: {{VALUE}};',
                ],
                'render_type' => 'template',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'tags_border_width',
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
                    '{{WRAPPER}} .king-addons-grid-product-tags .inner-block a' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'render_type' => 'template',
                'condition' => [
                    'tags_border_type!' => 'none',
                ],
            ]
        );

        $this->add_control(
            'tags_text_spacing',
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
                    'size' => 10,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-product-tags .king-addons-grid-extra-text-left' => 'padding-right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .king-addons-grid-product-tags .king-addons-grid-extra-text-right' => 'padding-left: {{SIZE}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'tags_icon_spacing',
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
                    'size' => 10,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-product-tags .king-addons-grid-extra-icon-left' => 'padding-right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .king-addons-grid-product-tags .king-addons-grid-extra-icon-right' => 'padding-left: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'tags_gutter',
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
                    '{{WRAPPER}} .king-addons-grid-product-tags .inner-block a' => 'margin-right: {{SIZE}}{{UNIT}};',
                ],
                'render_type' => 'template',
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'tags_padding',
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
                    '{{WRAPPER}} .king-addons-grid-product-tags .inner-block a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'render_type' => 'template',
            ]
        );

        $this->add_responsive_control(
            'tags_margin',
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
                    '{{WRAPPER}} .king-addons-grid-product-tags .inner-block' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'render_type' => 'template',
            ]
        );

        $this->add_control(
            'tags_radius',
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
                    '{{WRAPPER}} .king-addons-grid-product-tags .inner-block a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->end_controls_section();


        $this->start_controls_section(
            'section_style_product_rating',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Rating', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            ]
        );

        $this->add_control(
            'product_rating_color',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffd726',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-woocommerce-rating i:before' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'product_rating_unmarked_color',
            [
                'label' => esc_html__('Unmarked Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#D2CDCD',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-woocommerce-rating i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .king-addons-woocommerce-rating .king-addons-rating-unmarked svg' => 'fill: {{VALUE}};'
                ],
            ]
        );

        $this->add_control(
            'product_rating_score_color',
            [
                'label' => esc_html__('Score Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffd726',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-woocommerce-rating span' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .king-addons-woocommerce-rating .king-addons-rating-marked svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'product_rating_size',
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
                    'size' => 22,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-woocommerce-rating i' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .king-addons-woocommerce-rating svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};'
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'product_rating_gutter',
            [
                'type' => Controls_Manager::SLIDER,
                'label' => esc_html__('Gutter', 'king-addons'),
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 25,
                    ]
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 0,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-woocommerce-rating i' => 'margin-right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .king-addons-woocommerce-rating .king-addons-rating-icon' => 'margin-right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .king-addons-woocommerce-rating span.king-addons-rating-icon' => 'margin-right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .king-addons-woocommerce-rating span:not(.king-addons-rating-icon, .king-addons-rating-icon span)' => 'margin-left: {{SIZE}}{{UNIT}};',
                ],
                'separator' => 'after'
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'product_rating_typography',
                'selector' => '{{WRAPPER}} .king-addons-woocommerce-rating span'
            ]
        );

        $this->add_responsive_control(
            'product_rating_margin',
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
                    '{{WRAPPER}} .king-addons-grid-item-rating .inner-block' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before'
            ]
        );

        $this->end_controls_section();


        $this->start_controls_section(
            'section_style_product_status',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Status', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            ]
        );

        $this->add_control(
            'product_status_os_color',
            [
                'label' => esc_html__('On Sale Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-item-status .inner-block > .king-addons-woocommerce-onsale' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'product_status_os_bg_color',
            [
                'label' => esc_html__('On Sale BG Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#5B03FF',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-item-status .inner-block > .king-addons-woocommerce-onsale' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'product_status_os_border_color',
            [
                'label' => esc_html__('On Sale Border Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#E8E8E8',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-item-status .inner-block > .king-addons-woocommerce-onsale' => 'border-color: {{VALUE}}',
                ],
                'separator' => 'after'
            ]
        );

        $this->add_control(
            'product_status_ft_color',
            [
                'label' => esc_html__('Featured Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-item-status .inner-block > .king-addons-woocommerce-featured' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'product_status_ft_bg_color',
            [
                'label' => esc_html__('Featured BG Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#5B03FF',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-item-status .inner-block > .king-addons-woocommerce-featured' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'product_status_ft_border_color',
            [
                'label' => esc_html__('Featured Border Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#E8E8E8',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-item-status .inner-block > .king-addons-woocommerce-featured' => 'border-color: {{VALUE}}',
                ],
                'separator' => 'after'
            ]
        );

        $this->add_control(
            'product_status_oos_color',
            [
                'label' => esc_html__('Out of Stock Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#9C9C9C',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-item-status .inner-block > .king-addons-woocommerce-outofstock' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'product_status_oos_bg_color',
            [
                'label' => esc_html__('Out of Stock BG Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-item-status .inner-block > .king-addons-woocommerce-outofstock' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'product_status_oos_border_color',
            [
                'label' => esc_html__('Out of Stock Border Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#E8E8E8',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-item-status .inner-block > .king-addons-woocommerce-outofstock' => 'border-color: {{VALUE}}',
                ],
                'separator' => 'after'
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'product_status_typography',
                'selector' => '{{WRAPPER}} .king-addons-grid-item-status .inner-block > span'
            ]
        );

        $this->add_control(
            'product_status_border_type',
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
                    '{{WRAPPER}} .king-addons-grid-item-status .inner-block > span' => 'border-style: {{VALUE}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'product_status_border_width',
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
                    '{{WRAPPER}} .king-addons-grid-item-status .inner-block > span' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'product_status_border_type!' => 'none',
                ],
                'render_type' => 'template'
            ]
        );

        $this->add_responsive_control(
            'product_status_padding',
            [
                'label' => esc_html__('Padding', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'default' => [
                    'top' => 3,
                    'right' => 10,
                    'bottom' => 3,
                    'left' => 10,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-item-status .inner-block > span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'render_type' => 'template',
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'product_status_margin',
            [
                'label' => esc_html__('Margin', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'default' => [
                    'top' => 0,
                    'right' => 5,
                    'bottom' => 0,
                    'left' => 0,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-item-status .inner-block > span' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'render_type' => 'template',
            ]
        );

        $this->add_control(
            'product_status_radius',
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
                    '{{WRAPPER}} .king-addons-grid-item-status .inner-block > span' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'after',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'product_status_shadow',
                'selector' => '{{WRAPPER}} .king-addons-grid-item-status .inner-block > span',
            ]
        );

        $this->end_controls_section();


        $this->start_controls_section(
            'section_style_product_price',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Price', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            ]
        );

        $this->add_control(
            'product_price_color',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#9C9C9C',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-item-price .inner-block > span' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'product_price_old_color',
            [
                'label' => esc_html__('Old Price Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#9C9C9C',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-item-price .inner-block > span del' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'product_price_bg_color',
            [
                'label' => esc_html__('Background Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-item-price .inner-block > span' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'product_price_border_color',
            [
                'label' => esc_html__('Border Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#E8E8E8',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-item-price .inner-block > span' => 'border-color: {{VALUE}}',
                ],
                'separator' => 'after'
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'product_price_typography',
                'selector' => '{{WRAPPER}} .king-addons-grid-item-price .inner-block > span'
            ]
        );

        $this->add_control(
            'product_price_old_font_size',
            [
                'type' => Controls_Manager::SLIDER,
                'label' => esc_html__('Old Price Font Size', 'king-addons'),
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 100,
                    ]
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 14,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-item-price .inner-block > span del' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
                'separator' => 'before'
            ]
        );

        $this->add_control(
            'product_price_border_type',
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
                    '{{WRAPPER}} .king-addons-grid-item-price .inner-block > span' => 'border-style: {{VALUE}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'product_price_border_width',
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
                    '{{WRAPPER}} .king-addons-grid-item-price .inner-block > span' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'product_price_border_type!' => 'none',
                ],
                'render_type' => 'template'
            ]
        );

        $this->add_responsive_control(
            'product_price_padding',
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
                    '{{WRAPPER}} .king-addons-grid-item-price .inner-block > span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'render_type' => 'template',
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'product_price_margin',
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
                    '{{WRAPPER}} .king-addons-grid-item-price .inner-block > span' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'render_type' => 'template',
            ]
        );

        $this->add_control(
            'product_price_radius',
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
                    '{{WRAPPER}} .king-addons-grid-item-price .inner-block > span' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'after',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'product_price_shadow',
                'selector' => '{{WRAPPER}} .king-addons-grid-item-price .inner-block > span',
            ]
        );

        $this->end_controls_section();


        $this->start_controls_section(
            'section_style_product_sale_dates',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Sale Dates', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            ]
        );

        $this->add_control(
            'product_sale_dates_color',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#9C9C9C',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-item-sale_dates .inner-block > span' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'product_sale_dates_old_color',
            [
                'label' => esc_html__('Text Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#9C9C9C',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-item-sale_dates .inner-block span.king-addons-grid-extra-text-left' => 'color: {{VALUE}} !important',
                ],
            ]
        );

        $this->add_control(
            'product_sale_dates_bg_color',
            [
                'label' => esc_html__('Background Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-item-sale_dates .inner-block > span' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'product_sale_dates_border_color',
            [
                'label' => esc_html__('Border Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#E8E8E8',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-item-sale_dates .inner-block > span.king-addons-sale-dates' => 'border-color: {{VALUE}}',
                ],
                'separator' => 'after'
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'product_sale_dates_typography',
                'selector' => '{{WRAPPER}} .king-addons-grid-item-sale_dates .inner-block > .king-addons-sale-dates'
            ]
        );

        $this->add_control(
            'product_sale_dates_border_type',
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
                    '{{WRAPPER}} .king-addons-grid-item-sale_dates .inner-block > .king-addons-sale-dates' => 'border-style: {{VALUE}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'product_sale_dates_border_width',
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
                    '{{WRAPPER}} .king-addons-grid-item-sale_dates .inner-block > .king-addons-sale-dates' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'product_sale_dates_border_type!' => 'none',
                ],
                'render_type' => 'template'
            ]
        );

        $this->add_responsive_control(
            'product_sale_dates_padding',
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
                    '{{WRAPPER}} .king-addons-grid-item-sale_dates .inner-block > .king-addons-sale-dates' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'render_type' => 'template',
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'product_sale_dates_margin',
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
                    '{{WRAPPER}} .king-addons-grid-item-sale_dates .inner-block > .king-addons-sale-dates' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'render_type' => 'template',
            ]
        );

        $this->add_control(
            'product_sale_dates_radius',
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
                    '{{WRAPPER}} .king-addons-grid-item-sale_dates .inner-block > .king-addons-sale-dates' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'after',
            ]
        );

        $this->end_controls_section();


        $this->start_controls_section(
            'section_style_add_to_cart',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Add to Cart', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            ]
        );

        $this->start_controls_tabs('tabs_grid_add_to_cart_style');

        $this->start_controls_tab(
            'tab_grid_add_to_cart_normal',
            [
                'label' => esc_html__('Normal', 'king-addons'),
            ]
        );

        $this->add_control(
            'add_to_cart_color',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#333333',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-item-add-to-cart .inner-block a' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'add_to_cart_bg_color',
            [
                'label' => esc_html__('Background Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-item-add-to-cart .inner-block a' => 'background-color: {{VALUE}}',
                ]
            ]
        );

        $this->add_control(
            'add_to_cart_border_color',
            [
                'label' => esc_html__('Border Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#E8E8E8',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-item-add-to-cart .inner-block a' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'add_to_cart_box_shadow',
                'selector' => '{{WRAPPER}} .king-addons-grid-item-add-to-cart .inner-block a',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_grid_add_to_cart_hover',
            [
                'label' => esc_html__('Hover', 'king-addons'),
            ]
        );

        $this->add_control(
            'add_to_cart_color_hr',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#5B03FF',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-item-add-to-cart .inner-block a:hover' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'add_to_cart_bg_color_hr',
            [
                'label' => esc_html__('Background Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-item-add-to-cart .inner-block a.king-addons-button-none:hover' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .king-addons-grid-item-add-to-cart .inner-block a.added_to_cart:hover' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .king-addons-grid-item-add-to-cart .inner-block a:before' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .king-addons-grid-item-add-to-cart .inner-block a:after' => 'background-color: {{VALUE}}',
                ]
            ]
        );

        $this->add_control(
            'add_to_cart_border_color_hr',
            [
                'label' => esc_html__('Border Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#5B03FF',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-item-add-to-cart .inner-block a:hover' => 'border-color: {{VALUE}}',
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'add_to_cart_box_shadow_hr',
                'selector' => '{{WRAPPER}} .king-addons-grid-item-add-to-cart .inner-block :hover a',
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_control(
            'add_to_cart_divider',
            [
                'type' => Controls_Manager::DIVIDER,
                'style' => 'thick',
            ]
        );

        $this->add_control_add_to_cart_animation();

        $this->add_control(
            'add_to_cart_transition_duration',
            [
                'label' => esc_html__('Transition Duration (seconds)', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'default' => 0.1,
                'min' => 0,
                'max' => 5,
                'step' => 0.1,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-item-add-to-cart .inner-block a' => 'transition-duration: {{VALUE}}s',
                    '{{WRAPPER}} .king-addons-grid-item-add-to-cart .inner-block a:before' => 'transition-duration: {{VALUE}}s',
                    '{{WRAPPER}} .king-addons-grid-item-add-to-cart .inner-block a:after' => 'transition-duration: {{VALUE}}s',
                ],
            ]
        );

        $this->add_control_add_to_cart_animation_height();

        $this->add_control(
            'add_to_cart_typo_divider',
            [
                'type' => Controls_Manager::DIVIDER,
                'style' => 'thick',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'add_to_cart_typography',
                'selector' => '{{WRAPPER}} .king-addons-grid-item-add-to-cart a'
            ]
        );

        $this->add_control(
            'add_to_cart_icon_spacing',
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
                    '{{WRAPPER}} .king-addons-grid-item-add-to-cart .king-addons-grid-extra-icon-left' => 'padding-right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .king-addons-grid-item-add-to-cart .king-addons-grid-extra-icon-right' => 'padding-left: {{SIZE}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'add_to_cart_border_type',
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
                    '{{WRAPPER}} .king-addons-grid-item-add-to-cart .inner-block a' => 'border-style: {{VALUE}};',
                ],
                'render_type' => 'template',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'add_to_cart_border_width',
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
                    '{{WRAPPER}} .king-addons-grid-item-add-to-cart .inner-block a' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'render_type' => 'template',
                'condition' => [
                    'add_to_cart_border_type!' => 'none',
                ],
            ]
        );

        $this->add_responsive_control(
            'add_to_cart_padding',
            [
                'label' => esc_html__('Padding', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'default' => [
                    'top' => 5,
                    'right' => 15,
                    'bottom' => 5,
                    'left' => 15,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-item-add-to-cart .inner-block a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'add_to_cart_margin',
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
                    '{{WRAPPER}} .king-addons-grid-item-add-to-cart .inner-block' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'render_type' => 'template',
            ]
        );

        $this->add_control(
            'add_to_cart_radius',
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
                    '{{WRAPPER}} .king-addons-grid-item-add-to-cart .inner-block a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'render_type' => 'template',
                'separator' => 'before',
            ]
        );

        $this->end_controls_section();


        $this->start_controls_section(
            'section_wishlist_button_styles',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Add to Wishlist', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->start_controls_tabs('tabs_btn_styles');

        $this->start_controls_tab(
            'tab_btn_normal',
            [
                'label' => esc_html__('Normal', 'king-addons'),
            ]
        );

        $this->add_control(
            'btn_color',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#333333',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-wishlist-add span' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .king-addons-wishlist-add i' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .king-addons-wishlist-add svg' => 'fill: {{VALUE}}'
                ]
            ]
        );

        $this->add_control(
            'btn_border_color',
            [
                'label' => esc_html__('Border Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#E8E8E8',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-wishlist-add' => 'border-color: {{VALUE}}'
                ]
            ]
        );

        $this->add_control(
            'btn_bg_color',
            [
                'label' => esc_html__('Background Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#FFF',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-wishlist-add' => 'background-color: {{VALUE}}'
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'btn_box_shadow',
                'selector' => '{{WRAPPER}} .king-addons-wishlist-add, {{WRAPPER}} .king-addons-wishlist-remove',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'btn_typography',
                'selector' => '{{WRAPPER}} .king-addons-wishlist-add span, {{WRAPPER}} .king-addons-wishlist-add i, .king-addons-wishlist-remove span, {{WRAPPER}} .king-addons-wishlist-remove i',
                'fields_options' => [
                    'typography' => [
                        'default' => 'custom',
                    ],
                    'font_size' => [
                        'default' => [
                            'size' => '16',
                            'unit' => 'px',
                        ],
                    ],
                ]
            ]
        );

        $this->add_control(
            'btn_transition_duration',
            [
                'label' => esc_html__('Transition Duration (seconds)', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'default' => 0.5,
                'min' => 0,
                'max' => 5,
                'step' => 0.1,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-wishlist-add' => 'transition-duration: {{VALUE}}s',
                    '{{WRAPPER}} .king-addons-wishlist-add span' => 'transition-duration: {{VALUE}}s',
                    '{{WRAPPER}} .king-addons-wishlist-add i' => 'transition-duration: {{VALUE}}s',
                    '{{WRAPPER}} .king-addons-wishlist-remove' => 'transition-duration: {{VALUE}}s',
                    '{{WRAPPER}} .king-addons-wishlist-remove span' => 'transition-duration: {{VALUE}}s',
                    '{{WRAPPER}} .king-addons-wishlist-remove i' => 'transition-duration: {{VALUE}}s'
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_btn_hover',
            [
                'label' => esc_html__('Hover', 'king-addons'),
            ]
        );

        $this->add_control(
            'btn_hover_color',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#FF4400',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-wishlist-add:hover i' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .king-addons-wishlist-add:hover svg' => 'fill: {{VALUE}}',
                    '{{WRAPPER}} .king-addons-wishlist-add:hover span' => 'color: {{VALUE}}'
                ]
            ]
        );

        $this->add_control(
            'btn_hover_border_color',
            [
                'label' => esc_html__('Border Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#FF4400',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-wishlist-add:hover' => 'border-color: {{VALUE}}'
                ]
            ]
        );

        $this->add_control(
            'btn_hover_bg_color',
            [
                'label' => esc_html__('Background Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#FFF',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-wishlist-add:hover' => 'background-color: {{VALUE}}'
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'btn_box_shadow_hr',
                'selector' => '{{WRAPPER}} .king-addons-wishlist-add:hover, WRAPPER}} .king-addons-wishlist-remove:hover',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_remove_btn',
            [
                'label' => esc_html__('Remove', 'king-addons'),
            ]
        );

        $this->add_control(
            'remove_btn_text_color',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#FF4400',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-wishlist-remove span' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .king-addons-wishlist-remove i' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .king-addons-wishlist-remove svg' => 'fill: {{VALUE}}',
                    '{{WRAPPER}} .king-addons-wishlist-remove:hover span' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .king-addons-wishlist-remove:hover i' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .king-addons-wishlist-remove:hover svg' => 'fill: {{VALUE}}'
                ]
            ]
        );

        $this->add_control(
            'remove_btn_border_color',
            [
                'label' => esc_html__('Border Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#FF4F40',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-wishlist-remove' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .king-addons-wishlist-remove:hover' => 'border-color: {{VALUE}}'
                ]
            ]
        );

        $this->add_control(
            'remove_btn_bg_color',
            [
                'label' => esc_html__('Background Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#FFF',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-wishlist-remove' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .king-addons-wishlist-remove:hover' => 'background-color: {{VALUE}}'
                ]
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_responsive_control(
            'button_padding',
            [
                'label' => esc_html__('Padding', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'default' => [
                    'top' => 5,
                    'right' => 15,
                    'bottom' => 5,
                    'left' => 15,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-wishlist-add' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .king-addons-wishlist-remove' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'button_margin',
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


                    '{{WRAPPER}} .king-addons-grid-item-wishlist-button .inner-block' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

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
                    '{{WRAPPER}} .king-addons-wishlist-add' => 'border-style: {{VALUE}};',
                    '{{WRAPPER}} .king-addons-wishlist-remove' => 'border-style: {{VALUE}};'
                ],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'button_border_width',
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
                    '{{WRAPPER}} .king-addons-wishlist-add' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .king-addons-wishlist-remove' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'button_border_type!' => 'none',
                ],
            ]
        );

        $this->add_responsive_control(
            'button_border_radius',
            [
                'label' => esc_html__('Border Radius', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'default' => [
                    'top' => 1,
                    'right' => 1,
                    'bottom' => 1,
                    'left' => 1,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-wishlist-add' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .king-addons-wishlist-remove' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->end_controls_section();


        $this->start_controls_section(
            'section_compare_button_styles',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Add to Compare', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->start_controls_tabs('comp_tabs_btn_styles');

        $this->start_controls_tab(
            'comp_tab_btn_normal',
            [
                'label' => esc_html__('Normal', 'king-addons'),
            ]
        );

        $this->add_control(
            'comp_btn_text_color',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#333333',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-compare-add span' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .king-addons-compare-add i' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .king-addons-compare-add svg' => 'fill: {{VALUE}}'
                ]
            ]
        );

        $this->add_control(
            'comp_btn_border_color',
            [
                'label' => esc_html__('Border Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#E8E8E8',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-compare-add' => 'border-color: {{VALUE}}'
                ]
            ]
        );

        $this->add_control(
            'comp_btn_bg_color',
            [
                'label' => esc_html__('Background Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#FFF',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-compare-add' => 'background-color: {{VALUE}}'
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'comp_btn_box_shadow',
                'selector' => '{{WRAPPER}} .king-addons-compare-add, {{WRAPPER}} .king-addons-compare-remove',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'comp_btn_typography',
                'selector' => '{{WRAPPER}} .king-addons-compare-add span, {{WRAPPER}} .king-addons-compare-add i, .king-addons-compare-remove span, {{WRAPPER}} .king-addons-compare-remove i',
                'fields_options' => [
                    'typography' => [
                        'default' => 'custom',
                    ],
                    'font_size' => [
                        'default' => [
                            'size' => '16',
                            'unit' => 'px',
                        ],
                    ],
                ]
            ]
        );

        $this->add_control(
            'comp_btn_transition_duration',
            [
                'label' => esc_html__('Transition Duration (seconds)', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'default' => 0.5,
                'min' => 0,
                'max' => 5,
                'step' => 0.1,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-compare-add' => 'transition-duration: {{VALUE}}s',
                    '{{WRAPPER}} .king-addons-compare-add span' => 'transition-duration: {{VALUE}}s',
                    '{{WRAPPER}} .king-addons-compare-add i' => 'transition-duration: {{VALUE}}s',
                    '{{WRAPPER}} .king-addons-compare-remove' => 'transition-duration: {{VALUE}}s',
                    '{{WRAPPER}} .king-addons-compare-remove span' => 'transition-duration: {{VALUE}}s',
                    '{{WRAPPER}} .king-addons-compare-remove i' => 'transition-duration: {{VALUE}}s'
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'comp_tab_btn_hover',
            [
                'label' => esc_html__('Hover', 'king-addons'),
            ]
        );

        $this->add_control(
            'comp_btn_hover_color',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#FF4400',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-compare-add:hover i' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .king-addons-compare-add:hover svg' => 'fill: {{VALUE}}',
                    '{{WRAPPER}} .king-addons-compare-add:hover span' => 'color: {{VALUE}}'
                ]
            ]
        );

        $this->add_control(
            'comp_btn_hover_border_color',
            [
                'label' => esc_html__('Border Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#FF4400',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-compare-add:hover' => 'border-color: {{VALUE}}'
                ]
            ]
        );

        $this->add_control(
            'comp_btn_hover_bg_color',
            [
                'label' => esc_html__('Background Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#FFF',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-compare-add:hover' => 'background-color: {{VALUE}}'
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'comp_btn_box_shadow_hr',
                'selector' => '{{WRAPPER}} .king-addons-compare-add:hover, WRAPPER}} .king-addons-compare-remove:hover',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'comp_tab_remove_btn',
            [
                'label' => esc_html__('Remove', 'king-addons'),
            ]
        );

        $this->add_control(
            'comp_remove_btn_color',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#FF4400',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-compare-remove span' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .king-addons-compare-remove i' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .king-addons-compare-remove svg' => 'fill: {{VALUE}}',
                    '{{WRAPPER}} .king-addons-compare-remove:hover span' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .king-addons-compare-remove:hover i' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .king-addons-compare-remove:hover svg' => 'fill: {{VALUE}}'
                ]
            ]
        );

        $this->add_control(
            'comp_remove_btn_border_color',
            [
                'label' => esc_html__('Border Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#FF4F40',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-compare-remove' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .king-addons-compare-remove:hover' => 'border-color: {{VALUE}}'
                ]
            ]
        );

        $this->add_control(
            'comp_remove_btn_bg_color',
            [
                'label' => esc_html__('Background Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#FFF',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-compare-remove' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .king-addons-compare-remove:hover' => 'background-color: {{VALUE}}'
                ]
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_responsive_control(
            'comp_button_padding',
            [
                'label' => esc_html__('Padding', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'default' => [
                    'top' => 5,
                    'right' => 15,
                    'bottom' => 5,
                    'left' => 15,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-compare-add' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .king-addons-compare-remove' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'comp_button_margin',
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


                    '{{WRAPPER}} .king-addons-grid-item-compare-button .inner-block' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->add_control(
            'comp_button_border_type',
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
                    '{{WRAPPER}} .king-addons-compare-add' => 'border-style: {{VALUE}};',
                    '{{WRAPPER}} .king-addons-compare-remove' => 'border-style: {{VALUE}};'
                ],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'comp_button_border_width',
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
                    '{{WRAPPER}} .king-addons-compare-add' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .king-addons-compare-remove' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'button_border_type!' => 'none',
                ],
            ]
        );

        $this->add_responsive_control(
            'comp_button_border_radius',
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
                    '{{WRAPPER}} .king-addons-compare-add' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .king-addons-compare-remove' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->end_controls_section();

        $this->add_section_added_to_cart_popup();


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
                'default' => '#FFFFFF',
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
                    'size' => 10,
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
                'default' => '#9C9C9C',
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
            'section_style_linked_products',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Upsell / Cross-sell Title', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
                'show_label' => false,
                'condition' => [
                    'query_selection' => ['upsell', 'cross-sell'],

                ]
            ]
        );

        $this->add_control(
            'linked_products_color',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-linked-products-heading' => 'color: {{VALUE}}',
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'linked_products',
                'selector' => '{{WRAPPER}} .king-addons-grid-linked-products-heading *'
            ]
        );

        $this->add_responsive_control(
            'linked_products_padding',
            [
                'label' => esc_html__('Padding', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'default' => [
                    'top' => 3,
                    'right' => 15,
                    'bottom' => 3,
                    'left' => 15,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-linked-products-heading' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->add_responsive_control(
            'linked_products_distance_from_grid',
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
                    '{{WRAPPER}} .king-addons-grid-linked-products-heading' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ]

            ]
        );

        $this->add_control(
            'linked_products_alignment',
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
                    ]
                ],
                'default' => 'left',
                'separator' => 'before',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-linked-products-heading *' => 'text-align: {{VALUE}};',
                ]
            ]
        );

        $this->end_controls_section();

        $this->add_section_style_sort_and_results();


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
                'default' => '#FFFFFF',
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
                'size_units' => ['px'],
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
                'default' => '#FFFFFF',
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
                'default' => '#FFFFFF',
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
                    'size' => 30,
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


        $this->add_section_style_custom_field1();


        $this->add_section_style_custom_field2();
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
        $max_num_pages = intval(ceil($query->max_num_pages));
        wp_reset_postdata();
        return $max_num_pages;
    }

    public function get_main_query_args()
    {
        $settings = $this->get_settings();

        if (!king_addons_freemius()->can_use_premium_code__premium_only()) {
            if ('pro-cr' === $settings['query_selection']) {
                $settings['query_selection'] = 'dynamic';
            }
            if ('pro-rn' === $settings['query_orderby']) {
                $settings['query_orderby'] = 'date';
            }
        }

        $paged = max(1, get_query_var('paged'), get_query_var('page'));
        if (empty($settings['query_offset'])) {
            $settings['query_offset'] = 0;
        }
        $query_posts_per_page = empty($settings['query_posts_per_page']) ? -1 : $settings['query_posts_per_page'];
        $offset = ($paged - 1) * $query_posts_per_page + $settings['query_offset'];

        $args = [
            'post_type' => 'product',
            'tax_query' => $this->get_tax_query_args(),
            'meta_query' => $this->get_meta_query_args(),
            'post__not_in' => $settings['query_exclude_products'],
            'posts_per_page' => $query_posts_per_page,
            'orderby' => 'date',
            'paged' => $paged,
            'offset' => $offset,
        ];

        // Handle special query_selection
        if ('featured' === $settings['query_selection']) {
            $args['tax_query'][] = [
                'taxonomy' => 'product_visibility',
                'field' => 'term_taxonomy_id',
                'terms' => wc_get_product_visibility_term_ids()['featured'],
            ];
        } elseif ('onsale' === $settings['query_selection']) {
            $args['meta_query'] = [
                'relation' => 'OR',
                [
                    'key' => '_sale_price',
                    'value' => 0,
                    'compare' => '>',
                    'type' => 'numeric',
                ],
                [
                    'key' => '_min_variation_sale_price',
                    'value' => 0,
                    'compare' => '>',
                    'type' => 'numeric',
                ]
            ];
        } elseif ('upsell' === $settings['query_selection']) {
            $product = wc_get_product();
            if (!$product) return null;
            $meta_query = WC()->query->get_meta_query();
            $this->my_upsells = $product->get_upsell_ids();
            if (!empty($this->my_upsells)) {
                /** @noinspection PhpArrayIndexImmediatelyRewrittenInspection */
                $args = [
                    'post_type' => 'product',
                    'post__not_in' => $settings['query_exclude_products'],
                    'ignore_sticky_posts' => 1,
                    'posts_per_page' => $settings['query_posts_per_page'],
                    'orderby' => 'post__in',
                    'order' => $settings['order_direction'],
                    'paged' => $paged,
                    'post__in' => $this->my_upsells,
                    'meta_query' => $meta_query,
                ];
            } else {
                $args['post_type'] = ['none'];
            }
        } elseif ('cross-sell' === $settings['query_selection']) {
            $this->crossell_ids = [];
            if (is_cart()) {
                foreach (WC()->cart->get_cart() as $values) {
                    foreach ($values['data']->get_cross_sell_ids() as $cs_product) {
                        $this->crossell_ids[] = $cs_product;
                    }
                }
            }
            if (is_single()) {
                $product = wc_get_product();
                if (!$product) return null;
                $this->crossell_ids = $product->get_cross_sell_ids();
            }
            if (!empty($this->crossell_ids)) {
                $args = [
                    'post_type' => 'product',
                    'post__not_in' => $settings['query_exclude_products'],
                    'tax_query' => $this->get_tax_query_args(),
                    'ignore_sticky_posts' => 1,
                    'posts_per_page' => $settings['query_posts_per_page'],
                    'order' => $settings['order_direction'],
                    'paged' => $paged,
                    'post__in' => $this->crossell_ids,
                ];
            } else {
                $args['post_type'] = 'none';
            }
        }

        // Handle query_orderby
        switch ($settings['query_orderby']) {
            case 'sales':
                $args['meta_key'] = 'total_sales';
                $args['orderby'] = 'meta_value_num';
                break;
            case 'rating':
                $args['meta_key'] = '_wc_average_rating';
                $args['orderby'] = 'meta_value_num';
                break;
            case 'price-low':
            case 'price-high':
                $args['meta_key'] = '_price';
                $args['order'] = $settings['order_direction'];
                $args['orderby'] = 'meta_value_num';
                break;
            case 'random':
                $args['orderby'] = 'rand';
                break;
            case 'date':
                $args['orderby'] = 'date';
                break;
            default:
                $args['orderby'] = 'menu_order';
                $args['order'] = $settings['order_direction'];
                break;
        }

        // Exclude no images
        if ('yes' === $settings['query_exclude_no_images']) {
            $args['meta_key'] = '_thumbnail_id';
        }

        // Exclude out of stock
        if ('yes' === $settings['query_exclude_out_of_stock']) {
            $args['meta_query'] = [
                [
                    'key' => '_stock_status',
                    'value' => 'outofstock',
                    'compare' => 'NOT LIKE',
                ]
            ];
        }

        // Manual selection
        if ('manual' === $settings['query_selection']) {
            $post_ids = !empty($settings['query_manual_products']) ? $settings['query_manual_products'] : [''];
            $args = [
                'post_type' => 'product',
                'post__in' => $post_ids,
                'posts_per_page' => $settings['query_posts_per_page'],
                'orderby' => $settings['query_randomize'],
                'paged' => $paged,
            ];
        }

        // Current archive query
        if ('current' === $settings['query_selection'] && true !== Plugin::$instance->editor->is_edit_mode()) {
            global $wp_query;
            if (is_product_category()) {
                $posts_per_page = intval(get_option('king_addons_woocommerce_shop_cat_ppp', 9));
            } elseif (is_product_tag()) {
                $posts_per_page = intval(get_option('king_addons_woocommerce_shop_tag_ppp', 9));
            } else {
                $posts_per_page = intval(get_option('king_addons_woocommerce_shop_ppp', 9));
            }
            $args = $wp_query->query_vars;
            $args['tax_query'] = $this->get_tax_query_args();
            $args['meta_query'] = $this->get_meta_query_args();
            $args['posts_per_page'] = $posts_per_page;
            if (!empty($settings['query_randomize'])) {
                $args['orderby'] = $settings['query_randomize'];
            }
            $args['post_type'] = 'product';
        }

        // Handle GET orderby
        if (isset($_GET['orderby'])) {
            switch ($_GET['orderby']) {
                case 'popularity':
                    $args['meta_key'] = 'total_sales';
                    $args['orderby'] = 'meta_value_num';
                    break;
                case 'rating':
                    $args['meta_key'] = '_wc_average_rating';
                    $args['order'] = $settings['order_direction'];
                    $args['orderby'] = 'meta_value_num';
                    break;
                case 'price':
                    $args['meta_key'] = '_price';
                    $args['order'] = 'ASC';
                    $args['orderby'] = 'meta_value_num';
                    break;
                case 'price-desc':
                    $args['meta_key'] = '_price';
                    $args['order'] = 'DESC';
                    $args['orderby'] = 'meta_value_num';
                    break;
                case 'random':
                    $args['orderby'] = 'rand';
                    break;
                case 'date':
                    $args['orderby'] = 'date';
                    break;
                case 'title':
                    $args['orderby'] = 'title';
                    $args['order'] = 'ASC';
                    break;
                case 'title-desc':
                    $args['orderby'] = 'title';
                    $args['order'] = 'DESC';
                    break;
                default:
                    $args['order'] = $settings['order_direction'];
                    $args['orderby'] = 'menu_order';
                    break;
            }
        }

        // Handle GET psearch
        if (isset($_GET['psearch'])) {
            $args['s'] = $_GET['psearch'];
        }
        return $args;
    }

    public function get_tax_query_args()
    {
        $tax_query = [];
        if (isset($_GET['kingaddonsfilters'])) {
            $selected_filters = WC()->query->get_layered_nav_chosen_attributes();
            if (!empty($selected_filters)) {
                foreach ($selected_filters as $taxonomy => $data) {
                    $tax_query[] = [
                        'taxonomy' => $taxonomy,
                        'field' => 'slug',
                        'terms' => $data['terms'],
                        'operator' => ('and' === $data['query_type']) ? 'AND' : 'IN',
                        'include_children' => false,
                    ];
                }
            }
            if (isset($_GET['filter_product_cat'])) {
                $tax_query[] = [
                    'taxonomy' => 'product_cat',
                    'field' => 'slug',
                    'terms' => explode(',', $_GET['filter_product_cat']),
                    'operator' => 'IN',
                    'include_children' => true,
                ];
            }
            if (isset($_GET['filter_product_tag'])) {
                $tax_query[] = [
                    'taxonomy' => 'product_tag',
                    'field' => 'slug',
                    'terms' => explode(',', $_GET['filter_product_tag']),
                    'operator' => 'IN',
                    'include_children' => true,
                ];
            }
        } else {
            $settings = $this->get_settings();
            if (isset($_GET['king_addons_select_product_cat']) && '0' !== $_GET['king_addons_select_product_cat']) {
                $tax_query[] = [
                    'taxonomy' => 'product_cat',
                    'field' => 'id',
                    'terms' => sanitize_text_field($_GET['king_addons_select_product_cat'])
                ];
            }
            if (isset($_GET['product_cat']) && '0' !== $_GET['product_cat']) {
                $tax_query[] = [
                    'taxonomy' => 'product_cat',
                    'field' => 'id',
                    'terms' => sanitize_text_field($_GET['product_cat'])
                ];
            } else {
                foreach (get_object_taxonomies('product') as $tax) {
                    if (!empty($settings['query_taxonomy_' . $tax])) {
                        $tax_query[] = [
                            'taxonomy' => $tax,
                            'field' => 'id',
                            'terms' => $settings['query_taxonomy_' . $tax]
                        ];
                    }
                }
            }
        }

        if (isset($_GET['filter_rating'])) {
            $product_visibility_terms = wc_get_product_visibility_term_ids();
            $filter_rating = array_filter(array_map('absint', explode(',', wp_unslash($_GET['filter_rating']))));
            $rating_terms = [];
            for ($i = 1; $i <= 5; $i++) {
                if (in_array($i, $filter_rating, true) && isset($product_visibility_terms['rated-' . $i])) {
                    $rating_terms[] = $product_visibility_terms['rated-' . $i];
                }
            }
            if (!empty($rating_terms)) {
                $tax_query[] = [
                    'taxonomy' => 'product_visibility',
                    'field' => 'term_taxonomy_id',
                    'terms' => $rating_terms,
                    'operator' => 'IN',
                ];
            }
        }
        return $tax_query;
    }

    public function get_meta_query_args()
    {
        $meta_query = WC()->query->get_meta_query();
        if (isset($_GET['min_price']) || isset($_GET['max_price'])) {
            $meta_query = array_merge(['relation' => 'AND'], $meta_query);
            $meta_query[] = [
                [
                    'key' => '_price',
                    'value' => [$_GET['min_price'], $_GET['max_price']],
                    'compare' => 'BETWEEN',
                    'type' => 'NUMERIC'
                ],
            ];
        }
        return $meta_query;
    }

    public function get_animation_class($data, $object)
    {
        $class = '';
        if ('overlay' !== $object && 'yes' === $data[$object . '_animation_disable_mobile'] && wp_is_mobile()) {
            return $class;
        }
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

    public function get_image_effect_class($settings)
    {
        $class = '';
        if (!king_addons_freemius()->can_use_premium_code__premium_only()) {
            if (in_array($settings['image_effects'], ['pro-zi', 'pro-zo', 'pro-go', 'pro-bo'])) {
                $settings['image_effects'] = 'none';
            }
        }
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

    public function render_password_protected_input()
    {
        if (!post_password_required()) return;
        add_filter('the_password_form', function () {
            $output = '<form action="' . esc_url(home_url('wp-login.php?action=postpass')) . '" method="post">';
            $output .= '<i class="fas fa-lock"></i><p>' . esc_html(get_the_title()) . '</p>';
            $output .= '<input type="password" name="post_password" id="post-' . esc_attr(get_the_id()) . '" placeholder="' . esc_html__('Type and hit Enter...', 'king-addons') . '">';
            $output .= '</form>';
            return $output;
        });
        echo '<div class="king-addons-grid-item-protected king-addons-cv-container"><div class="king-addons-cv-outer"><div class="king-addons-cv-inner">';
        echo get_the_password_form();
        echo '</div></div></div>';
    }

    public function render_product_thumbnail($settings)
    {
        $id = get_post_thumbnail_id();
        $src = Group_Control_Image_Size::get_attachment_image_src($id, 'layout_image_crop', $settings);
        $alt = '' === wp_get_attachment_caption($id) ? get_the_title() : wp_get_attachment_caption($id);
        $src2 = '';
        if (get_post_meta(get_the_ID(), 'king_addons_secondary_image_id')) {
            $second_id = get_post_meta(get_the_ID(), 'king_addons_secondary_image_id', true);
            if (!empty($second_id)) {
                $src2 = Group_Control_Image_Size::get_attachment_image_src($second_id, 'layout_image_crop', $settings);
            }
        }
        if (has_post_thumbnail()) {
            echo '<div class="king-addons-grid-image-wrap" data-src="' . esc_url($src) . '" data-img-on-hover="' . esc_attr($settings['secondary_img_on_hover']) . '" data-src-secondary="' . esc_url($src2) . '">';
            echo '<img src="' . esc_url($src) . '" alt="' . esc_attr($alt) . '" class="king-addons-animation-timing-' . esc_attr($settings['image_effects_animation_timing']) . '">';
            if ('yes' === $settings['secondary_img_on_hover']) {
                echo '<img src="' . esc_url($src2) . '" alt="' . esc_attr($alt) . '" class="king-addons-hidden-img king-addons-animation-timing-' . esc_attr($settings['image_effects_animation_timing']) . '">';
            }
            echo '</div>';
        }
    }

    public function render_media_overlay($settings)
    {
        echo '<div class="king-addons-grid-media-hover-bg ' . esc_attr($this->get_animation_class($settings, 'overlay')) . '" data-url="' . esc_url(get_the_permalink()) . '">';
        if (king_addons_freemius()->can_use_premium_code__premium_only() && '' !== $settings['overlay_image']['url']) {
            echo '<img src="' . esc_url($settings['overlay_image']['url']) . '" alt="' . esc_attr($settings['overlay_image']['alt']) . '">';
        }
        echo '</div>';
    }

    public function render_product_title($settings, $class)
    {
        $title_pointer = !king_addons_freemius()->can_use_premium_code__premium_only() ? 'none' : $this->get_settings()['title_pointer'];
        $title_pointer_animation = !king_addons_freemius()->can_use_premium_code__premium_only() ? 'fade' : $this->get_settings()['title_pointer_animation'];
        $pointer_item_class = ('none' !== $title_pointer) ? 'class="king-addons-pointer-item"' : '';
        $open_links_in_new_tab = ('yes' === $this->get_settings()['open_links_in_new_tab']) ? '_blank' : '_self';
        $class .= ' king-addons-pointer-' . $title_pointer . ' king-addons-pointer-line-fx king-addons-pointer-fx-' . $title_pointer_animation;
        $tags_whitelist = ['h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'div', 'span', 'p'];
        $element_title_tag = Core::validateHTMLTags($settings['element_title_tag'], 'h2', $tags_whitelist);
        echo '<' . esc_attr($element_title_tag) . ' class="' . esc_attr($class) . '"><div class="inner-block"><a target="' . $open_links_in_new_tab . '" ' . $pointer_item_class . ' href="' . esc_url(get_the_permalink()) . '">';
        if ('word_count' === $settings['element_trim_text_by']) {
            echo esc_html(wp_trim_words(get_the_title(), $settings['element_word_count']));
        } else {
            echo esc_html(mb_substr(html_entity_decode(get_the_title()), 0, $settings['element_letter_count'])) . '...';
        }
        echo '</a></div></' . esc_attr($element_title_tag) . '>';
    }

    public function render_product_excerpt($settings, $class)
    {
        if ('' === get_the_excerpt()) return;
        echo '<div class="' . esc_attr($class) . '"><div class="inner-block">';
        if ('word_count' === $settings['element_trim_text_by']) {
            echo '<p>' . esc_html(wp_trim_words(get_the_excerpt(), $settings['element_word_count'])) . '</p>';
        } else {
            echo '<p>' . esc_html(mb_substr(get_the_excerpt(), 0, $settings['element_letter_count'])) . '...</p>';
        }
        echo '</div></div>';
    }

    public function render_product_categories($settings, $class, $post_id)
    {
        $terms = wp_get_post_terms($post_id, $settings['element_select']);
        if (!$terms || is_wp_error($terms)) return;
        $count = 0;
        $categories_pointer = !king_addons_freemius()->can_use_premium_code__premium_only() ? 'none' : $this->get_settings()['categories_pointer'];
        $categories_pointer_animation = !king_addons_freemius()->can_use_premium_code__premium_only() ? 'fade' : $this->get_settings()['categories_pointer_animation'];
        $pointer_item_class = ('none' !== $categories_pointer) ? 'class="king-addons-pointer-item"' : '';
        $class .= ' king-addons-pointer-' . $categories_pointer . ' king-addons-pointer-line-fx king-addons-pointer-fx-' . $categories_pointer_animation;
        echo '<div class="' . esc_attr($class) . ' king-addons-grid-product-categories"><div class="inner-block">';
        if ('before' === $settings['element_extra_text_pos']) {
            echo '<span class="king-addons-grid-extra-text-left">' . esc_html($settings['element_extra_text']) . '</span>';
        }
        if ('before' === $settings['element_extra_icon_pos']) {
            ob_start();
            Icons_Manager::render_icon($settings['element_extra_icon'], ['aria-hidden' => 'true']);
            echo '<span class="king-addons-grid-extra-icon-left">' . ob_get_clean() . '</span>';
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
            echo '<span class="king-addons-grid-extra-icon-right">' . ob_get_clean() . '</span>';
        }
        if ('after' === $settings['element_extra_text_pos']) {
            echo '<span class="king-addons-grid-extra-text-right">' . esc_html($settings['element_extra_text']) . '</span>';
        }
        echo '</div></div>';
    }

    public function render_product_tags($settings, $class, $post_id)
    {
        $terms = wp_get_post_terms($post_id, $settings['element_select']);
        if (!$terms || is_wp_error($terms)) return;
        $count = 0;
        $tags_pointer = !king_addons_freemius()->can_use_premium_code__premium_only() ? 'none' : $this->get_settings()['tags_pointer'];
        $tags_pointer_animation = !king_addons_freemius()->can_use_premium_code__premium_only() ? 'fade' : $this->get_settings()['tags_pointer_animation'];
        $pointer_item_class = ('none' !== $tags_pointer) ? 'class="king-addons-pointer-item"' : '';
        $class .= ' king-addons-pointer-' . $tags_pointer . ' king-addons-pointer-line-fx king-addons-pointer-fx-' . $tags_pointer_animation;
        echo '<div class="' . esc_attr($class) . ' king-addons-grid-product-tags"><div class="inner-block">';
        if ('before' === $settings['element_extra_text_pos']) {
            echo '<span class="king-addons-grid-extra-text-left">' . esc_html($settings['element_extra_text']) . '</span>';
        }
        if ('before' === $settings['element_extra_icon_pos']) {
            ob_start();
            Icons_Manager::render_icon($settings['element_extra_icon'], ['aria-hidden' => 'true']);
            echo '<span class="king-addons-grid-extra-icon-left">' . ob_get_clean() . '</span>';
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
            echo '<span class="king-addons-grid-extra-icon-right">' . ob_get_clean() . '</span>';
        }
        if ('after' === $settings['element_extra_text_pos']) {
            echo '<span class="king-addons-grid-extra-text-right">' . esc_html($settings['element_extra_text']) . '</span>';
        }
        echo '</div></div>';
    }

// Stub for custom fields, left empty as in original
    public function render_product_custom_fields($settings, $class, $post_id)
    {
    }

// Stub for likes, left empty as in original
    public function render_product_likes($settings, $class, $post_id)
    {
    }

// Stub for sharing icons, left empty as in original
    public function render_product_sharing_icons($settings, $class)
    {
    }

    public function render_product_lightbox($settings, $class, $post_id)
    {
        echo '<div class="' . esc_attr($class) . '"><div class="inner-block">';
        $lightbox_source = get_the_post_thumbnail_url($post_id);
        if ('audio' === get_post_format()) {
            if ('meta' === $settings['element_lightbox_pfa_select']) {
                $meta_value = get_post_meta($post_id, $settings['element_lightbox_pfa_meta'], true);
                if (false === strpos($meta_value, '<iframe ')) {
                    add_filter('oembed_result', $woocommerce_grid_filter = function ($html) {
                        preg_match('/<iframe.*src=\"(.*)\".*><\/iframe>/isU', $html, $matches);
                        return $matches[1] . '&auto_play=true';
                    }, 50, 3);
                    $track_url = wp_oembed_get($meta_value);
                    remove_filter('oembed_result', $woocommerce_grid_filter, 50);
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
                if ('youtube' === $video['provider']) {
                    $lightbox_source = 'https://www.youtube.com/embed/' . $video['video_id'] . '?feature=oembed&autoplay=1&controls=1';
                } elseif ('vimeo' === $video['provider']) {
                    $lightbox_source = 'https://player.vimeo.com/video/' . $video['video_id'] . '?autoplay=1#t=0';
                }
            }
        }
        echo '<span data-src="' . esc_url($lightbox_source) . '">';
        if ('before' === $settings['element_extra_text_pos']) {
            echo '<span class="king-addons-grid-extra-text-left">' . esc_html($settings['element_extra_text']) . '</span>';
        }
        echo '<i class="' . esc_attr($settings['element_extra_icon']['value']) . '"></i>';
        if ('after' === $settings['element_extra_text_pos']) {
            echo '<span class="king-addons-grid-extra-text-right">' . esc_html($settings['element_extra_text']) . '</span>';
        }
        echo '</span>';
        if ('yes' === $settings['element_lightbox_overlay']) {
            echo '<div class="king-addons-grid-lightbox-overlay"></div>';
        }
        echo '</div></div>';
    }

    public function render_product_element_separator($settings, $class)
    {
        echo '<div class="' . esc_attr($class . ' ' . $settings['element_separator_style']) . '"><div class="inner-block"><span></span></div></div>';
    }

    public function render_product_status($settings, $class)
    {
        global $product;
        if (is_null($product)) return;
        echo '<div class="' . esc_attr($class) . '"><div class="inner-block">';
        if ($product->is_on_sale()) {
            echo '<span class="king-addons-woocommerce-onsale">' . esc_html__('Sale', 'king-addons') . '</span>';
        }
        if ('yes' === $settings['element_status_offstock'] && !$product->is_in_stock() && !($product->is_type('variable') && $product->get_stock_quantity() > 0)) {
            echo '<span class="king-addons-woocommerce-outofstock">' . esc_html__('Out of Stock', 'king-addons') . '</span>';
        }
        if ('yes' === $settings['element_status_featured'] && $product->is_featured()) {
            echo '<span class="king-addons-woocommerce-featured">' . esc_html__('Featured', 'king-addons') . '</span>';
        }
        echo '</div></div>';
    }

    public function render_product_add_to_cart($settings, $class)
    {
        global $product;
        if (is_null($product)) return;
        $button_class = implode(' ', array_filter([
            'product_type_' . $product->get_type(),
            ($product->is_purchasable() && $product->is_in_stock()) ? 'add_to_cart_button' : '',
            $product->supports('ajax_add_to_cart') ? 'ajax_add_to_cart' : '',
        ]));
        $add_to_cart_animation = !king_addons_freemius()->can_use_premium_code__premium_only() ? 'king-addons-button-none' : $this->get_settings()['add_to_cart_animation'];
        $popup_animation = $this->get_settings_for_display()['popup_notification_animation'] ?? '';
        $fade_out_in = $this->get_settings_for_display()['popup_notification_fade_out_in'] ?? '';
        $animation_duration = $this->get_settings_for_display()['popup_notification_animation_duration'] ?? '';
        $attributes = [
            'rel="nofollow"',
            'class="' . esc_attr($button_class) . ' king-addons-button-effect ' . esc_attr($add_to_cart_animation) . ($product->is_in_stock() ? '' : ' king-addons-atc-not-clickable') . '"',
            'aria-label="' . esc_attr($product->add_to_cart_description()) . '"',
            'data-product_id="' . esc_attr($product->get_id()) . '"',
            'data-product_sku="' . esc_attr($product->get_sku()) . '"',
            'data-atc-popup="' . esc_attr($settings['element_show_added_tc_popup']) . '"',
            'data-atc-animation="' . esc_attr($popup_animation) . '"',
            'data-atc-fade-out-in="' . esc_attr($fade_out_in) . '"',
            'data-atc-animation-time="' . esc_attr($animation_duration) . '"'
        ];
        $button_HTML = '';
        $page_id = get_queried_object_id();
        if ('before' === $settings['element_extra_icon_pos']) {
            ob_start();
            Icons_Manager::render_icon($settings['element_extra_icon'], ['aria-hidden' => 'true']);
            $button_HTML .= '<span class="king-addons-grid-extra-icon-left">' . ob_get_clean() . '</span>';
        }
        switch ($product->get_type()) {
            case 'simple':
                $button_HTML .= $settings['element_addcart_simple_txt'];
                if ('yes' === get_option('woocommerce_enable_ajax_add_to_cart')) {
                    $attributes[] = 'href="' . esc_url(get_permalink($page_id) . '/?add-to-cart=' . get_the_ID()) . '"';
                } else {
                    $attributes[] = 'href="' . esc_url(get_permalink()) . '"';
                }
                break;
            case 'grouped':
                $button_HTML .= $settings['element_addcart_grouped_txt'];
                $attributes[] = 'href="' . esc_url(get_permalink()) . '"';
                break;
            case 'variable':
                $button_HTML .= $settings['element_addcart_variable_txt'];
                $attributes[] = 'href="' . esc_url(get_permalink()) . '"';
                break;
            case 'pw-gift-card':
            case 'ywf_deposit':
                $button_HTML .= esc_html__('Select Amount', 'king-addons');
                $attributes[] = 'href="' . esc_url(get_permalink()) . '"';
                break;
            case 'stm_lms_product':
            case 'redq_rental':
                $button_HTML .= esc_html__('View Product', 'king-addons');
                $attributes[] = 'href="' . esc_url(get_permalink()) . '"';
                break;
            default:
                if (is_callable([$product, 'get_product_url'])) {
                    $attributes[] = 'href="' . esc_url($product->get_product_url()) . '"';
                    $custom_text = get_post_meta(get_the_ID(), '_button_text', true);
                    $button_HTML .= $custom_text ?: esc_html__('Buy Product', 'king-addons');
                } else {
                    $button_HTML .= esc_html__('View Product', 'king-addons');
                    $attributes[] = 'href="' . esc_url(get_permalink()) . '"';
                }
                break;
        }
        if ('after' === $settings['element_extra_icon_pos']) {
            ob_start();
            Icons_Manager::render_icon($settings['element_extra_icon'], ['aria-hidden' => 'true']);
            $button_HTML .= '<span class="king-addons-grid-extra-icon-right">' . ob_get_clean() . '</span>';
        }
        echo '<div class="' . esc_attr($class) . '"><div class="inner-block">';
        $final = apply_filters('woocommerce_loop_add_to_cart_link', $button_HTML, $product);
        if ($final !== $button_HTML) {
            echo $final;
        } else {
            echo '<a ' . implode(' ', $attributes) . '><span>' . $button_HTML . '</span></a>';
        }
        echo '</div></div>';
    }

// Retrieve wishlist from cookie if user not logged in
    public function get_wishlist_from_cookie()
    {
        if (isset($_COOKIE['king_addons_wishlist'])) {
            return json_decode(stripslashes($_COOKIE['king_addons_wishlist']), true);
        } elseif (isset($_COOKIE['king_addons_wishlist_' . get_current_blog_id()])) {
            return json_decode(stripslashes($_COOKIE['king_addons_wishlist_' . get_current_blog_id()]), true);
        }
        return [];
    }

    public function render_product_wishlist_button($settings, $class)
    {
        if (!king_addons_freemius()->can_use_premium_code__premium_only()) return;
        global $product;
        if (is_null($product)) return;
        $user_id = get_current_user_id();
        $wishlist = $user_id > 0 ? get_user_meta($user_id, 'king_addons_wishlist', true) : $this->get_wishlist_from_cookie();
        if (!$wishlist) $wishlist = [];
        $popup_animation = $this->get_settings_for_display()['popup_notification_animation'] ?? '';
        $fade_out_in = $this->get_settings_for_display()['popup_notification_fade_out_in'] ?? '';
        $animation_duration = $this->get_settings_for_display()['popup_notification_animation_duration'] ?? '';
        $wishlist_attrs = [
            'data-wishlist-url="' . (get_option('king_addons_wishlist_page') ?: '') . '"',
            'data-atw-popup="' . $settings['element_show_added_to_wishlist_popup'] . '"',
            'data-atw-animation="' . $popup_animation . '"',
            'data-atw-fade-out-in="' . $fade_out_in . '"',
            'data-atw-animation-time="' . $animation_duration . '"',
            'data-open-in-new-tab="' . $settings['element_open_links_in_new_tab'] . '"'
        ];
        $button_add_title = '';
        $button_remove_title = '';
        $add_content = '';
        $remove_content = '';
        if ('yes' === $settings['show_icon']) {
            $add_content .= '<i class="far fa-heart"></i>';
            $remove_content .= '<i class="fas fa-heart"></i>';
        }
        if ('yes' === $settings['show_text']) {
            $add_content .= ' <span>' . esc_html($settings['add_to_wishlist_text']) . '</span>';
            $remove_content .= ' <span>' . esc_html($settings['remove_from_wishlist_text']) . '</span>';
        } else {
            $button_add_title = 'title="' . esc_html($settings['add_to_wishlist_text']) . '"';
            $button_remove_title = 'title="' . esc_html($settings['remove_from_wishlist_text']) . '"';
        }
        echo '<div class="' . esc_attr($class) . '"><div class="inner-block">';
        $rm_hidden = !in_array($product->get_id(), $wishlist) ? 'king-addons-button-hidden' : '';
        $add_hidden = in_array($product->get_id(), $wishlist) ? 'king-addons-button-hidden' : '';
        echo '<button class="king-addons-wishlist-add ' . $add_hidden . '" ' . $button_add_title . ' data-product-id="' . $product->get_id() . '" ' . implode(' ', $wishlist_attrs) . '>' . $add_content . '</button>';
        echo '<button class="king-addons-wishlist-remove ' . $rm_hidden . '" ' . $button_remove_title . ' data-product-id="' . $product->get_id() . '">' . $remove_content . '</button>';
        echo '</div></div>';
    }

// Retrieve compare from cookie if user not logged in
    public function get_compare_from_cookie()
    {
        if (isset($_COOKIE['king_addons_compare'])) {
            return json_decode(stripslashes($_COOKIE['king_addons_compare']), true);
        } elseif (isset($_COOKIE['king_addons_compare_' . get_current_blog_id()])) {
            return json_decode(stripslashes($_COOKIE['king_addons_compare_' . get_current_blog_id()]), true);
        }
        return [];
    }

    public function render_product_compare_button($settings, $class)
    {
        if (!king_addons_freemius()->can_use_premium_code__premium_only()) return;
        global $product;
        if (is_null($product)) return;
        $user_id = get_current_user_id();
        $compare = $user_id > 0 ? get_user_meta($user_id, 'king_addons_compare', true) : $this->get_compare_from_cookie();
        if (!$compare) $compare = [];
        $popup_animation = $this->get_settings_for_display()['popup_notification_animation'] ?? '';
        $fade_out_in = $this->get_settings_for_display()['popup_notification_fade_out_in'] ?? '';
        $animation_duration = $this->get_settings_for_display()['popup_notification_animation_duration'] ?? '';
        $compare_attrs = [
            'data-compare-url="' . (get_option('king_addons_compare_page') ?: '') . '"',
            'data-atcompare-popup="' . $settings['element_show_added_to_compare_popup'] . '"',
            'data-atcompare-animation="' . $popup_animation . '"',
            'data-atcompare-fade-out-in="' . $fade_out_in . '"',
            'data-atcompare-animation-time="' . $animation_duration . '"',
            'data-open-in-new-tab="' . $settings['element_open_links_in_new_tab'] . '"'
        ];
        $add_content = '';
        $remove_content = '';
        $button_add_title = '';
        $button_remove_title = '';
        if ('yes' === $settings['show_icon']) {
            $add_content .= '<i class="fas fa-exchange-alt"></i>';
            $remove_content .= '<i class="fas fa-exchange-alt"></i>';
        }
        if ('yes' === $settings['show_text']) {
            $add_content .= ' <span>' . esc_html($settings['add_to_compare_text']) . '</span>';
            $remove_content .= ' <span>' . esc_html($settings['remove_from_compare_text']) . '</span>';
        } else {
            $button_add_title = 'title="' . esc_html($settings['add_to_compare_text']) . '"';
            $button_remove_title = 'title="' . esc_html($settings['remove_from_compare_text']) . '"';
        }
        echo '<div class="' . esc_attr($class) . '"><div class="inner-block">';
        $rm_hidden = !in_array($product->get_id(), $compare) ? 'king-addons-button-hidden' : '';
        $add_hidden = in_array($product->get_id(), $compare) ? 'king-addons-button-hidden' : '';
        echo '<button class="king-addons-compare-add ' . $add_hidden . '" ' . $button_add_title . ' data-product-id="' . $product->get_id() . '" ' . implode(' ', $compare_attrs) . '>' . $add_content . '</button>';
        echo '<button class="king-addons-compare-remove ' . $rm_hidden . '" ' . $button_remove_title . ' data-product-id="' . $product->get_id() . '">' . $remove_content . '</button>';
        echo '</div></div>';
    }

    public function render_rating_icon($class, $unmarked_style)
    {
        ?>
        <span class="king-addons-rating-icon <?php echo esc_attr($class); ?>">
        <span class="king-addons-rating-marked">
            <?php Icons_Manager::render_icon(['value' => 'fas fa-star', 'library' => 'fa-solid'], ['aria-hidden' => 'true']); ?>
        </span>
        <span class="king-addons-rating-unmarked">
            <?php
            if ('outline' === $unmarked_style) {
                Icons_Manager::render_icon(['value' => 'far fa-star', 'library' => 'fa-regular'], ['aria-hidden' => 'true']);
            } else {
                Icons_Manager::render_icon(['value' => 'fas fa-star', 'library' => 'fa-solid'], ['aria-hidden' => 'true']);
            }
            ?>
        </span>
    </span>
        <?php
    }

    public function render_product_rating($settings, $class)
    {
        global $product;
        if (is_null($product)) return;
        /** @noinspection PhpCastIsUnnecessaryInspection */
        $rating_amount = floatval($product->get_average_rating());
        $round_rating = (int)$rating_amount;
        $rating_icon = '&#xE934;';
        $style_class = '';
        if ('style-1' === $settings['element_rating_style']) {
            $style_class = ' king-addons-woocommerce-rating-style-1';
            if ('outline' === $settings['element_rating_unmarked_style']) {
                $rating_icon = '&#xE933;';
            }
        } elseif ('style-2' === $settings['element_rating_style']) {
            $rating_icon = '&#9733;';
            $style_class = ' king-addons-woocommerce-rating-style-2';
            if ('outline' === $settings['element_rating_unmarked_style']) {
                $rating_icon = '&#9734;';
            }
        }
        echo '<div class="' . esc_attr($class . $style_class) . '"><div class="inner-block"><div class="king-addons-woocommerce-rating">';
        if ('yes' === $settings['element_rating_score']) {
            if (in_array($rating_amount, [1, 2, 3, 4, 5])) {
                $rating_amount = $rating_amount . '.0';
            }
            echo '<i class="king-addons-rating-icon-10">' . $rating_icon . '</i><span>' . esc_html($rating_amount) . '</span>';
        } else {
            if (Plugin::$instance->experiments->is_feature_active('e_font_icon_svg') && 'style-1' === $settings['element_rating_style']) {
                for ($i = 1; $i <= 5; $i++) {
                    if ($i <= $rating_amount) {
                        $this->render_rating_icon('king-addons-rating-icon-full', $settings['element_rating_unmarked_style']);
                    } elseif ($i === $round_rating + 1 && $rating_amount !== $round_rating) {
                        $this->render_rating_icon('king-addons-rating-icon-' . (($rating_amount - $round_rating) * 10), $settings['element_rating_unmarked_style']);
                    } else {
                        $this->render_rating_icon('king-addons-rating-icon-0', $settings['element_rating_unmarked_style']);
                    }
                }
            } else {
                for ($i = 1; $i <= 5; $i++) {
                    if ($i <= $rating_amount) {
                        echo '<i class="king-addons-rating-icon-full">' . $rating_icon . '</i>';
                    } elseif ($i === $round_rating + 1 && $rating_amount !== $round_rating) {
                        echo '<i class="king-addons-rating-icon-' . (($rating_amount - $round_rating) * 10) . '">' . $rating_icon . '</i>';
                    } else {
                        echo '<i class="king-addons-rating-icon-empty">' . $rating_icon . '</i>';
                    }
                }
            }
        }
        echo '</div></div></div>';
    }

    public function render_product_price($class)
    {
        global $product;
        if (is_null($product)) return;
        echo '<div class="' . esc_attr($class) . '"><div class="inner-block"><span>' . wp_kses_post($product->get_price_html()) . '</span>';
        $sale_price_dates_to = ($date = get_post_meta($product->get_id(), '_sale_price_dates_to', true)) ? date_i18n('Y-m-d', $date) : '';
        $sale_price_dates_to = apply_filters('king_addons_custom_sale_price_dates_to_filter', $sale_price_dates_to, $product);
        echo $sale_price_dates_to;
        echo '</div></div>';
    }

    public function render_product_sale_dates($settings, $class)
    {
        global $product;
        if (is_null($product)) return;
        $from_date = ($date = get_post_meta($product->get_id(), '_sale_price_dates_from', true)) ? date_i18n(get_option('date_format'), $date) : '';
        $to_date = ($date = get_post_meta($product->get_id(), '_sale_price_dates_to', true)) ? date_i18n(get_option('date_format'), $date) : '';
        if (('yes' === $settings['show_sale_starts_date'] && $from_date) || ('yes' === $settings['show_sale_ends_date'] && $to_date)) {
            echo '<div class="' . esc_attr($class) . '"><div class="inner-block"><span class="king-addons-sale-dates">';
            if ($from_date && '' !== $settings['element_sale_starts_text']) {
                echo '<span class="king-addons-grid-extra-text-left">' . esc_html($settings['element_sale_starts_text']) . '</span> ';
            }
            if ($from_date) {
                echo '<span>' . $from_date . '</span>';
            }
            if ($from_date && $to_date && 'inline' === $settings['element_sale_dates_layout']) {
                echo esc_html($settings['element_sale_dates_sep']);
            }
            if ($from_date && $to_date && 'block' === $settings['element_sale_dates_layout']) {
                echo '<br>';
            }
            if ($to_date && '' !== $settings['element_sale_ends_text']) {
                echo '<span class="king-addons-grid-extra-text-left">' . esc_html($settings['element_sale_ends_text']) . '</span> ';
            }
            if ($to_date) {
                echo '<span>' . $to_date . '</span>';
            }
            echo '</span></div></div>';
        }
    }

    public function get_elements($type, $settings, $class, $post_id)
    {
        if (in_array($type, ['pro-lk', 'pro-shr', 'pro-sd', 'pro-ws', 'pro-cm', 'pro-cfa'])) {
            $type = 'title';
        }
        switch ($type) {
            case 'title':
                $this->render_product_title($settings, $class);
                break;
            case 'excerpt':
                $this->render_product_excerpt($settings, $class);
                break;
            case 'product_tag':
                $this->render_product_tags($settings, $class, $post_id);
                break;
            case 'likes':
                $this->render_product_likes($settings, $class, $post_id);
                break;
            case 'sharing':
                $this->render_product_sharing_icons($settings, $class);
                break;
            case 'lightbox':
                $this->render_product_lightbox($settings, $class, $post_id);
                break;
            case 'separator':
                $this->render_product_element_separator($settings, $class);
                break;
            case 'status':
                $this->render_product_status($settings, $class);
                break;
            case 'price':
                $this->render_product_price($class);
                break;
            case 'sale_dates':
                $this->render_product_sale_dates($settings, $class);
                break;
            case 'rating':
                $this->render_product_rating($settings, $class);
                break;
            case 'add-to-cart':
                $this->render_product_add_to_cart($settings, $class);
                break;
            case 'wishlist-button':
                if (king_addons_freemius()->can_use_premium_code__premium_only()) {
                    $this->render_product_wishlist_button($settings, $class);
                }
                break;
            case 'compare-button':
                if (king_addons_freemius()->can_use_premium_code__premium_only()) {
                    $this->render_product_compare_button($settings, $class);
                }
                break;
            case 'custom-field':
                $this->render_product_custom_fields($settings, $class, $post_id);
                break;
            case 'product_cat':
            default:
                $this->render_product_categories($settings, $class, $post_id);
                break;
        }
    }

    public function get_elements_by_location($location, $settings, $post_id)
    {
        $locations = [];
        foreach ($settings['grid_elements'] as $data) {
            $place = $data['element_location'];
            $align_vr = !king_addons_freemius()->can_use_premium_code__premium_only() ? 'middle' : $data['element_align_vr'];
            if (!isset($locations[$place])) {
                $locations[$place] = [];
            }
            if ('over' === $place) {
                if (!isset($locations[$place][$align_vr])) {
                    $locations[$place][$align_vr] = [];
                }
                $locations[$place][$align_vr][] = $data;
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
                        $class = 'king-addons-grid-item-' . $data['element_select'] . ' elementor-repeater-item-' . $data['_id'] . ' king-addons-grid-item-display-' . $data['element_display'] . ' king-addons-grid-item-align-' . $data['element_align_hr'] . $this->get_animation_class($data, 'element');
                        $this->get_elements($data['element_select'], $data, $class, $post_id);
                    }
                    echo '</div>';
                    if ('middle' === $align) {
                        echo '</div></div></div>';
                    }
                }
            } else {
                echo '<div class="king-addons-grid-item-' . esc_attr($location) . '-content elementor-clearfix">';
                foreach ($locations[$location] as $data) {
                    $class = 'king-addons-grid-item-' . $data['element_select'] . ' elementor-repeater-item-' . $data['_id'] . ' king-addons-grid-item-display-' . $data['element_display'] . ' king-addons-grid-item-align-' . $data['element_align_hr'];
                    $this->get_elements($data['element_select'], $data, $class, $post_id);
                }
                echo '</div>';
            }
        }
    }

// Stub for sorting, left empty as in original
    public function render_grid_sorting($settings, $posts)
    {
    }

// Renders filters
    public function render_grid_filters($settings)
    {
        $taxonomy = $settings['filters_select'];
        if ('' === $taxonomy || !isset($settings['query_taxonomy_' . $taxonomy])) return;
        $custom_filters = $settings['query_taxonomy_' . $taxonomy];
        if (!king_addons_freemius()->can_use_premium_code__premium_only()) {
            $settings['filters_default_filter'] = '';
            $settings['filters_icon_align'] = '';
            $settings['filters_count'] = '';
            $settings['filters_pointer'] = 'none';
            $settings['filters_pointer_animation'] = 'none';
        }
        $left_icon = ('left' === $settings['filters_icon_align']) ? '<i class="' . esc_attr($settings['filters_icon']['value']) . ' king-addons-grid-filters-icon-left"></i>' : '';
        $right_icon = ('right' === $settings['filters_icon_align']) ? '<i class="' . esc_attr($settings['filters_icon']['value']) . ' king-addons-grid-filters-icon-right"></i>' : '';
        $left_sep = ('left' === $settings['filters_separator_align']) ? '<em class="king-addons-grid-filters-sep">' . esc_html($settings['filters_separator']) . '</em>' : '';
        $right_sep = ('right' === $settings['filters_separator_align']) ? '<em class="king-addons-grid-filters-sep">' . esc_html($settings['filters_separator']) . '</em>' : '';
        $post_count = ('yes' === $settings['filters_count']) ? '<sup data-brackets="' . esc_attr($settings['filters_count_brackets']) . '"></sup>' : '';
        $pointer_class = ' king-addons-pointer-' . $settings['filters_pointer'] . ' king-addons-pointer-line-fx king-addons-pointer-fx-' . $settings['filters_pointer_animation'];
        $pointer_item_class = ('none' !== $settings['filters_pointer']) ? 'class="king-addons-pointer-item"' : '';
        $pointer_item_name = ('none' !== $settings['filters_pointer']) ? 'king-addons-pointer-item' : '';
        echo '<ul class="king-addons-grid-filters elementor-clearfix king-addons-grid-filters-sep-' . esc_attr($settings['filters_separator_align']) . '">';
        if ('yes' === $settings['filters_all'] && 'yes' !== $settings['filters_linkable']) {
            echo '<li class="' . esc_attr($pointer_class) . '"><span data-filter="*" class="king-addons-active-filter ' . $pointer_item_name . '">' . $left_icon . esc_html($settings['filters_all_text']) . $right_icon . $post_count . '</span>' . $right_sep . '</li>';
        }
        if ('dynamic' === $settings['query_selection'] && !empty($custom_filters)) {
            $parent_filters = [];
            foreach ($custom_filters as $term_id) {
                $filter = get_term_by('id', $term_id, $taxonomy);
                if (!$filter) continue;
                $data_attr = ('post_tag' === $taxonomy) ? 'tag-' . $filter->slug : $taxonomy . '-' . $filter->slug;
                $tax_data_attr = ('post_tag' === $taxonomy) ? 'tag' : $taxonomy;
                $term_data = $filter->slug;
                if (0 === $filter->parent) {
                    $children = get_term_children($filter->term_id, $taxonomy);
                    $data_role = !empty($children) ? ' data-role="parent"' : '';
                    echo '<li' . $data_role . ' class="' . esc_attr($pointer_class) . '">';
                    if ('yes' !== $settings['filters_linkable']) {
                        echo $left_sep . '<span ' . $pointer_item_class . ' data-ajax-filter=' . json_encode([$tax_data_attr, $term_data]) . ' data-filter=".' . esc_attr(urldecode($data_attr)) . '">' . $left_icon . esc_html($filter->name) . $right_icon . $post_count . '</span>' . $right_sep;
                    } else {
                        echo $left_sep . '<a ' . $pointer_item_class . ' href="' . esc_url(get_term_link($filter->term_id, $taxonomy)) . '">' . $left_icon . esc_html($filter->name) . $right_icon . $post_count . '</a>' . $right_sep;
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
                    $data_attr = ('post_tag' === $taxonomy) ? 'tag-' . $filter->slug : $taxonomy . '-' . $filter->slug;
                    $tax_data_attr = ('post_tag' === $taxonomy) ? 'tag' : $taxonomy;
                    if (0 === $filter->parent) {
                        $children = get_term_children($filter->term_id, $taxonomy);
                        $data_role = !empty($children) ? ' data-role="parent"' : '';
                        $hidden_class = $this->get_hidden_filter_class($filter->slug, $settings);
                        echo '<li' . $data_role . ' class="' . esc_attr($pointer_class . $hidden_class) . '">';
                        if ('yes' !== $settings['filters_linkable']) {
                            echo $left_sep . '<span ' . $pointer_item_class . ' data-ajax-filter=' . json_encode([$tax_data_attr, $filter->slug]) . ' data-filter=".' . esc_attr(urldecode($data_attr)) . '">' . $left_icon . esc_html($filter->name) . $right_icon . $post_count . '</span>' . $right_sep;
                        } else {
                            echo $left_sep . '<a ' . $pointer_item_class . ' href="' . esc_url(get_term_link($filter->term_id, $taxonomy)) . '" data-ajax-filter=' . json_encode([$tax_data_attr, $filter->slug]) . ' data-filter=".' . esc_attr(urldecode($data_attr)) . '">' . $left_icon . esc_html($filter->name) . $right_icon . $post_count . '</a>' . $right_sep;
                        }
                        echo '</li>';
                    } else {
                        $parent_filters[] = $filter->parent;
                    }
                }
            }
        }
        if ('yes' !== $settings['filters_linkable']) {
            foreach (array_unique($parent_filters) as $pf) {
                $parent = get_term_by('id', $pf, $taxonomy);
                if (!$parent) continue;
                $children = get_term_children($pf, $taxonomy);
                $data_attr = ('post_tag' === $taxonomy) ? 'tag-' . $parent->slug : $taxonomy . '-' . $parent->slug;
                echo '<ul data-parent=".' . esc_attr(urldecode($data_attr)) . '" class="king-addons-sub-filters">';
                echo '<li data-role="back" class="' . esc_attr($pointer_class) . '">';
                echo '<span class="king-addons-back-filter" data-ajax-filter=' . json_encode([$taxonomy, $parent->slug]) . ' data-filter=".' . esc_attr(urldecode($data_attr)) . '"><i class="fas fa-long-arrow-alt-left"></i>&nbsp;&nbsp;' . esc_html__('Back', 'king-addons') . '</span>';
                echo '</li>';
                foreach ($children as $child_id) {
                    $sub_filter = get_term_by('id', $child_id, $taxonomy);
                    if (!$sub_filter) continue;
                    $data_attr2 = ('post_tag' === $taxonomy) ? 'tag-' . $sub_filter->slug : $taxonomy . '-' . $sub_filter->slug;
                    echo '<li data-role="sub" class="' . esc_attr($pointer_class) . '">';
                    echo $left_sep . '<span ' . $pointer_item_class . ' data-ajax-filter=' . json_encode([$taxonomy, $sub_filter->slug]) . ' data-filter=".' . esc_attr(urldecode($data_attr2)) . '">' . $left_icon . esc_html($sub_filter->name) . $right_icon . $post_count . '</span>' . $right_sep;
                    echo '</li>';
                }
                echo '</ul>';
            }
        }
        echo '</ul>';
    }

    public function get_hidden_filter_class($slug, $settings)
    {
        $posts = new WP_Query($this->get_main_query_args());
        $visible_categories = [];
        if ($posts->have_posts()) {
            while ($posts->have_posts()) {
                $posts->the_post();
                foreach (get_the_category() as $category) {
                    $visible_categories[] = $category->slug;
                }
            }
            $visible_categories = array_unique($visible_categories);
            wp_reset_postdata();
        }
        return (!in_array($slug, $visible_categories) && 'yes' === $settings['filters_hide_empty']) ? ' king-addons-hidden-element' : '';
    }

    public function render_grid_pagination($settings)
    {
        if ('yes' !== $settings['layout_pagination'] || 1 === $this->get_max_num_pages() || 'slider' === $settings['layout_select']) {
            return;
        }
        // In upsell/cross-sell: if total upsell/cross-sell <= per_page, no pagination
        if ((isset($this->my_upsells) && count($this->my_upsells) <= $settings['query_posts_per_page']) ||
            (isset($this->crossell_ids) && count($this->crossell_ids) <= $settings['query_posts_per_page'])) {
            return;
        }
        global $paged;
        $pages = $this->get_max_num_pages();
        $paged = empty($paged) ? 1 : $paged;
        if (!king_addons_freemius()->can_use_premium_code__premium_only()) {
            if ('pro-is' === $settings['pagination_type']) {
                $settings['pagination_type'] = 'default';
            }
        }
        echo '<div class="king-addons-grid-pagination elementor-clearfix king-addons-grid-pagination-' . esc_attr($settings['pagination_type']) . '">';
        if ('default' === $settings['pagination_type']) {
            if ($paged < $pages) {
                echo '<a href="' . esc_url(get_pagenum_link($paged + 1)) . '" class="king-addons-prev-post-link">' . Core::getIcon($settings['pagination_on_icon'], 'left') . esc_html($settings['pagination_older_text']) . '</a>';
            } elseif ('yes' === $settings['pagination_disabled_arrows']) {
                echo '<span class="king-addons-prev-post-link king-addons-disabled-arrow">' . Core::getIcon($settings['pagination_on_icon'], 'left') . esc_html($settings['pagination_older_text']) . '</span>';
            }
            if ($paged > 1) {
                echo '<a href="' . esc_url(get_pagenum_link($paged - 1)) . '" class="king-addons-next-post-link">' . esc_html($settings['pagination_newer_text']) . Core::getIcon($settings['pagination_on_icon'], 'right') . '</a>';
            } elseif ('yes' === $settings['pagination_disabled_arrows']) {
                echo '<span class="king-addons-next-post-link king-addons-disabled-arrow">' . esc_html($settings['pagination_newer_text']) . Core::getIcon($settings['pagination_on_icon'], 'right') . '</span>';
            }
        } elseif ('numbered' === $settings['pagination_type']) {
            $range = $settings['pagination_range'];
            $showitems = ($range * 2) + 1;
            if (1 !== $pages) {
                if ('yes' === $settings['pagination_prev_next'] || 'yes' === $settings['pagination_first_last']) {
                    echo '<div class="king-addons-grid-pagination-left-arrows">';
                    if ('yes' === $settings['pagination_first_last']) {
                        if ($paged >= 2) {
                            echo '<a href="' . esc_url(get_pagenum_link()) . '" class="king-addons-first-page">' . Core::getIcon($settings['pagination_fl_icon'], 'left') . '<span>' . esc_html($settings['pagination_first_text']) . '</span></a>';
                        } elseif ('yes' === $settings['pagination_disabled_arrows']) {
                            echo '<span class="king-addons-first-page king-addons-disabled-arrow">' . Core::getIcon($settings['pagination_fl_icon'], 'left') . '<span>' . esc_html($settings['pagination_first_text']) . '</span></span>';
                        }
                    }
                    if ('yes' === $settings['pagination_prev_next']) {
                        if ($paged > 1) {
                            echo '<a href="' . esc_url(get_pagenum_link($paged - 1)) . '" class="king-addons-prev-page">' . Core::getIcon($settings['pagination_pn_icon'], 'left') . '<span>' . esc_html($settings['pagination_prev_text']) . '</span></a>';
                        } elseif ('yes' === $settings['pagination_disabled_arrows']) {
                            echo '<span class="king-addons-prev-page king-addons-disabled-arrow">' . Core::getIcon($settings['pagination_pn_icon'], 'left') . '<span>' . esc_html($settings['pagination_prev_text']) . '</span></span>';
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
                if ('yes' === $settings['pagination_prev_next'] || 'yes' === $settings['pagination_first_last']) {
                    echo '<div class="king-addons-grid-pagination-right-arrows">';
                    if ('yes' === $settings['pagination_prev_next']) {
                        if ($paged < $pages) {
                            echo '<a href="' . esc_url(get_pagenum_link($paged + 1)) . '" class="king-addons-next-page"><span>' . esc_html($settings['pagination_next_text']) . '</span>' . Core::getIcon($settings['pagination_pn_icon'], 'right') . '</a>';
                        } elseif ('yes' === $settings['pagination_disabled_arrows']) {
                            echo '<span class="king-addons-next-page king-addons-disabled-arrow"><span>' . esc_html($settings['pagination_next_text']) . '</span>' . Core::getIcon($settings['pagination_pn_icon'], 'right') . '</span>';
                        }
                    }
                    if ('yes' === $settings['pagination_first_last']) {
                        if ($paged <= $pages - 1) {
                            echo '<a href="' . esc_url(get_pagenum_link($pages)) . '" class="king-addons-last-page"><span>' . esc_html($settings['pagination_last_text']) . '</span>' . Core::getIcon($settings['pagination_fl_icon'], 'right') . '</a>';
                        } elseif ('yes' === $settings['pagination_disabled_arrows']) {
                            echo '<span class="king-addons-last-page king-addons-disabled-arrow"><span>' . esc_html($settings['pagination_last_text']) . '</span>' . Core::getIcon($settings['pagination_fl_icon'], 'right') . '</span>';
                        }
                    }
                    echo '</div>';
                }
            }
        } else {
            echo '<a href="' . esc_url(get_pagenum_link($paged + 1)) . '" class="king-addons-load-more-btn" data-e-disable-page-transition>';
            echo esc_html($settings['pagination_load_more_text']) . '</a>';
            echo '<div class="king-addons-pagination-loading">';
            switch ($settings['pagination_animation']) {
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
            echo '</div><p class="king-addons-pagination-finish">' . esc_html($settings['pagination_finish_text']) . '</p>';
        }
        echo '</div>';
    }

    public function add_grid_settings($settings)
    {
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
        $stick_last = ('fitRows' === $settings['layout_select']) ? $settings['stick_last_element_to_bottom'] : 'no';
        $ghr_wide = $settings['layout_gutter_hr_widescreen']['size'] ?? $settings['layout_gutter_hr']['size'];
        $ghr_desktop = $settings['layout_gutter_hr']['size'];
        $ghr_laptop = $settings['layout_gutter_hr_laptop']['size'] ?? $ghr_desktop;
        $ghr_tab_ex = $settings['layout_gutter_hr_tablet_extra']['size'] ?? $ghr_laptop;
        $ghr_tab = $settings['layout_gutter_hr_tablet']['size'] ?? $ghr_tab_ex;
        $ghr_mob_ex = $settings['layout_gutter_hr_mobile_extra']['size'] ?? $ghr_tab;
        $ghr_mobile = $settings['layout_gutter_hr_mobile']['size'] ?? $ghr_mob_ex;

        $gvr_wide = $settings['layout_gutter_vr_widescreen']['size'] ?? $settings['layout_gutter_vr']['size'];
        $gvr_desktop = $settings['layout_gutter_vr']['size'];
        $gvr_laptop = $settings['layout_gutter_vr_laptop']['size'] ?? $gvr_desktop;
        $gvr_tab_ex = $settings['layout_gutter_vr_tablet_extra']['size'] ?? $gvr_laptop;
        $gvr_tab = $settings['layout_gutter_vr_tablet']['size'] ?? $gvr_tab_ex;
        $gvr_mob_ex = $settings['layout_gutter_vr_mobile_extra']['size'] ?? $gvr_tab;
        $gvr_mobile = $settings['layout_gutter_vr_mobile']['size'] ?? $gvr_mob_ex;

        if (!king_addons_freemius()->can_use_premium_code__premium_only()) {
            $settings['lightbox_popup_thumbnails'] = '';
            $settings['lightbox_popup_thumbnails_default'] = '';
            $settings['lightbox_popup_sharing'] = '';
        }
        $layout_settings = [
            'layout' => $settings['layout_select'],
            'stick_last_element_to_bottom' => $stick_last,
            'columns_desktop' => $settings['layout_columns'],
            'gutter_hr' => $ghr_desktop,
            'gutter_hr_mobile' => $ghr_mobile,
            'gutter_hr_mobile_extra' => $ghr_mob_ex,
            'gutter_hr_tablet' => $ghr_tab,
            'gutter_hr_tablet_extra' => $ghr_tab_ex,
            'gutter_hr_laptop' => $ghr_laptop,
            'gutter_hr_widescreen' => $ghr_wide,
            'gutter_vr' => $gvr_desktop,
            'gutter_vr_mobile' => $gvr_mobile,
            'gutter_vr_mobile_extra' => $gvr_mob_ex,
            'gutter_vr_tablet' => $gvr_tab,
            'gutter_vr_tablet_extra' => $gvr_tab_ex,
            'gutter_vr_laptop' => $gvr_laptop,
            'gutter_vr_widescreen' => $gvr_wide,
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
            'lightbox' => [
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
            ],
        ];
        if ('current' !== $settings['query_selection']) {
            $layout_settings['query_posts_per_page'] = $settings['query_posts_per_page'];
        }
        if ('list' === $settings['layout_select']) {
            $layout_settings['media_align'] = $settings['layout_list_align'];
            $layout_settings['media_width'] = $settings['layout_list_media_width']['size'];
            $layout_settings['media_distance'] = $settings['layout_list_media_distance']['size'];
        }
        if ('yes' === $settings['filters_experiment']) {
            $layout_settings['grid_settings'] = $settings;
        }
        $this->add_render_attribute('grid-settings', ['data-settings' => wp_json_encode($layout_settings)]);
    }

    public function add_slider_settings($settings)
    {
        $slider_is_rtl = is_rtl();
        $slider_dir = $slider_is_rtl ? 'rtl' : 'ltr';
        if (!king_addons_freemius()->can_use_premium_code__premium_only()) {
            $settings['layout_slider_autoplay'] = '';
            $settings['layout_slider_autoplay_duration'] = 0;
            $settings['layout_slider_pause_on_hover'] = '';
            $settings['lightbox_popup_thumbnails'] = '';
            $settings['lightbox_popup_thumbnails_default'] = '';
            $settings['lightbox_popup_sharing'] = '';
        }
        $slider_opts = [
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
            'sliderSlidesToScroll' => +$settings['layout_slides_to_scroll'],
            'lightbox' => [
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
            ]
        ];
        if ($settings['layout_slider_amount'] === 1 && $settings['layout_slider_effect'] === 'fade') {
            $slider_opts['fade'] = true;
        }
        $this->add_render_attribute('slider-settings', [
            'dir' => esc_attr($slider_dir),
            'data-slick' => wp_json_encode($slider_opts),
        ]);
    }
    
    // For storing upsells/cross-sells

    /** @noinspection PhpMissingFieldTypeInspection */
    public $my_upsells;
    /** @noinspection PhpMissingFieldTypeInspection */
    public $crossell_ids;

    protected function render()
    {
        $settings = $this->get_settings();
        if (!class_exists('WooCommerce')) {
            echo '<h2>' . esc_html__('WooCommerce is NOT active!', 'king-addons') . '</h2>';
            return;
        }
        $posts = new WP_Query($this->get_main_query_args());
        if ($posts->have_posts()) :
            $tags_whitelist = ['h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'div', 'span', 'p'];
            $linked_tag = Core::validateHTMLTags($settings['grid_linked_products_heading_tag'], 'h2', $tags_whitelist);
            if (('upsell' === $settings['query_selection'] || 'cross-sell' === $settings['query_selection']) && '' !== $settings['grid_linked_products_heading']) {
                echo '<div class="king-addons-grid-linked-products-heading"><' . $linked_tag . '>' . esc_html($settings['grid_linked_products_heading']) . '</' . $linked_tag . '></div>';
            }
            if ('slider' !== $settings['layout_select']) {
                if (!in_array($settings['query_selection'], ['upsell', 'cross-sell'])) {
                    $this->render_grid_sorting($settings, $posts);
                    if (!((is_product_category() || is_product_tag()) && !king_addons_freemius()->can_use_premium_code__premium_only())) {
                        $this->render_grid_filters($settings);
                    }
                }
                $this->add_grid_settings($settings);
                $render_attr = $this->get_render_attribute_string('grid-settings');
            } else {
                $this->add_slider_settings($settings);
                $render_attr = $this->get_render_attribute_string('slider-settings');
            }
            echo '<section class="king-addons-grid elementor-clearfix" ' . $render_attr . ' data-found-posts="' . $posts->found_posts . '">';
            while ($posts->have_posts()): $posts->the_post();
                $post_class = implode(' ', get_post_class('king-addons-grid-item elementor-clearfix', get_the_ID()));
                echo '<article class="' . esc_attr($post_class) . '">';
                $this->render_password_protected_input();
                echo '<div class="king-addons-grid-item-inner">';
                $this->get_elements_by_location('above', $settings, get_the_ID());
                echo '<div class="king-addons-grid-media-wrap' . esc_attr($this->get_image_effect_class($settings)) . '" data-overlay-link="' . esc_attr($settings['overlay_post_link']) . '">';
                $this->render_product_thumbnail($settings);
                echo '<div class="king-addons-grid-media-hover king-addons-animation-wrap">';
                echo apply_filters('king_addons_grid_media_hover_content', '', get_the_ID());
                $this->render_media_overlay($settings);
                $this->get_elements_by_location('over', $settings, get_the_ID());
                echo '</div></div>';
                $this->get_elements_by_location('below', $settings, get_the_ID());
                echo '</div></article>';
            endwhile;
            wp_reset_postdata();
            echo '</section>';
            if ('slider' === $settings['layout_select']) {
                if ($posts->found_posts > (int)$settings['layout_slider_amount'] && (int)$settings['layout_slider_amount'] < $settings['query_posts_per_page']) {
                    echo '<div class="king-addons-grid-slider-arrow-container">';
                    echo '<div class="king-addons-grid-slider-prev-arrow king-addons-grid-slider-arrow" id="king-addons-grid-slider-prev-' . esc_attr($this->get_id()) . '">' . Core::getIcon($settings['layout_slider_nav_icon'], '') . '</div>';
                    echo '<div class="king-addons-grid-slider-next-arrow king-addons-grid-slider-arrow" id="king-addons-grid-slider-next-' . esc_attr($this->get_id()) . '">' . Core::getIcon($settings['layout_slider_nav_icon'], '') . '</div>';
                    echo '</div><div class="king-addons-grid-slider-dots"></div>';
                }
            }
            $this->render_grid_pagination($settings);
        else:
            if (!in_array($settings['query_selection'], ['upsell', 'cross-sell'])) {
                echo '<h2>' . esc_html($settings['query_not_found_text']) . '</h2>';
            }
        endif;
    }


}