<?php

namespace King_Addons;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Widget_Base;
use Elementor\Icons_Manager;
use WP_Query;

if (!defined('ABSPATH')) {
    exit;
}

class Content_Ticker extends Widget_Base
{

    public function get_name()
    {
        return 'king-addons-content-ticker';
    }

    public function get_title()
    {
        return esc_html__('Content Ticker', 'king-addons');
    }

    public function get_icon()
    {
        return 'king-addons-icon king-addons-content-ticker';
    }

    public function get_categories()
    {
        return ['king-addons'];
    }

    public function get_keywords()
    {
        return ['king addons', 'king', 'addons', 'kingaddons', 'king-addons', 'blog', 'content ticker', 'content', 'ticker',
            'marquee', 'news', 'post', 'posts', 'news ticker', 'post ticker', 'posts ticker'];
    }

    public function get_script_depends()
    {
        return [KING_ADDONS_ASSETS_UNIQUE_KEY . '-slick-slick',
            KING_ADDONS_ASSETS_UNIQUE_KEY . '-marquee-marquee',
            KING_ADDONS_ASSETS_UNIQUE_KEY . '-content-ticker-script',
        ];
    }

    public function get_style_depends()
    {
        return [
            KING_ADDONS_ASSETS_UNIQUE_KEY . '-content-ticker-style',
            KING_ADDONS_ASSETS_UNIQUE_KEY . '-general-general',
        ];
    }

    public function get_custom_help_url()
    {
        return 'https://kingaddons.com/';
    }

    public function add_control_post_type()
    {
        $this->add_control(
            'post_type',
            [
                'label' => esc_html__('Select Type', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'dynamic',
                'options' => [
                    'dynamic' => esc_html__('Dynamic', 'king-addons'),
                    'pro-cm' => esc_html__('Custom (Pro)', 'king-addons'),
                ],
            ]
        );
    }

    public function add_control_slider_effect()
    {
        $this->add_control(
            'slider_effect',
            [
                'type' => Controls_Manager::SELECT,
                'label' => esc_html__('Effect', 'king-addons'),
                'default' => 'hr-slide',
                'options' => [
                    'hr-slide' => esc_html__('Horizontal Slide', 'king-addons'),
                    'pro-tp' => esc_html__('Typing (Pro)', 'king-addons'),
                    'pro-fd' => esc_html__('Fade (Pro)', 'king-addons'),
                    'pro-vs' => esc_html__('Vertical Slide (Pro)', 'king-addons'),
                ],
                'prefix_class' => 'king-addons-ticker-effect-',
                'render_type' => 'template',
                'separator' => 'before',
                'condition' => [
                    'type_select' => 'slider',
                ],
            ]
        );
    }

    public function add_control_slider_effect_cursor()
    {
    }

    public function add_control_heading_icon_type()
    {
        $this->add_control(
            'heading_icon_type',
            [
                'type' => Controls_Manager::SELECT,
                'label' => esc_html__('Select Type', 'king-addons'),
                'default' => 'fontawesome',
                'options' => [
                    'none' => esc_html__('None', 'king-addons'),
                    'fontawesome' => esc_html__('FontAwesome', 'king-addons'),
                    'pro-cc' => esc_html__('Circle (Pro)', 'king-addons'),
                ],
            ]
        );
    }

    public function add_control_type_select()
    {
        $this->add_control(
            'type_select',
            [
                'type' => Controls_Manager::SELECT,
                'label' => esc_html__('Select Type', 'king-addons'),
                'default' => 'slider',
                'options' => [
                    'slider' => esc_html__('Slider', 'king-addons'),
                    'pro-mq' => esc_html__('Marquee (Pro)', 'king-addons'),
                ],
                'render_type' => 'template',
                'separator' => 'before',
            ]
        );
    }

    public function add_control_marquee_direction()
    {
    }

    public function add_control_marquee_pause_on_hover()
    {
    }

    public function add_control_marquee_effect_duration()
    {
    }

    public function add_section_ticker_items()
    {
    }

    /** @noinspection PhpMissingFieldTypeInspection */
    public $post_types;

    public function add_control_query_source()
    {

        $this->post_types = [];
        $this->post_types['post'] = esc_html__('Posts', 'king-addons');
        $this->post_types['page'] = esc_html__('Pages', 'king-addons');

        $custom_post_types = Core::getCustomTypes('post');
        foreach ($custom_post_types as $slug => $title) {
            if ('product' === $slug || 'e-landing-page' === $slug) {
                continue;
            }

            if (!king_addons_freemius()->can_use_premium_code__premium_only()) {
                $this->post_types['pro-' . substr($slug, 0, 2)] = esc_html($title) . ' (Pro)';
            } else {
                $this->post_types[$slug] = esc_html($title);
            }
        }

        $this->post_types['pro-pd'] = 'Products (Pro)';
        $this->post_types['pro-ft'] = 'Featured (Pro)';
        $this->post_types['pro-sl'] = 'On Sale (Pro)';

        $this->add_control(
            'query_source',
            [
                'label' => esc_html__('Source', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'post',
                'options' => $this->post_types,
            ]
        );
    }

    protected function register_controls()
    {


        $this->start_controls_section(
            'section_general',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('General', 'king-addons'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );


        $this->add_control_post_type();


        Core::renderUpgradeProNotice($this, Controls_Manager::RAW_HTML, 'content-ticker', 'post_type', ['pro-cm']);

        $this->add_control(
            'link_type',
            [
                'label' => esc_html__('Link Type', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'none' => esc_html__('None', 'king-addons'),
                    'title' => esc_html__('Title', 'king-addons'),
                    'image' => esc_html__('Image', 'king-addons'),
                    'image-title' => esc_html__('Image & Title', 'king-addons'),
                    'box' => esc_html__('Box', 'king-addons'),
                ],
                'default' => 'image-title',
                'separator' => 'before',
            ]
        );

        $this->end_controls_section();


        $this->start_controls_section(
            'section_ticker_query',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Query', 'king-addons'),
                'tab' => Controls_Manager::TAB_CONTENT,
                'condition' => [
                    'post_type' => 'dynamic',
                ],
            ]
        );

        $this->add_control_query_source();


        Core::renderUpgradeProNotice($this, Controls_Manager::RAW_HTML, 'content-ticker', 'query_source', ['pro-pd', 'pro-ft', 'pro-sl']);

        if (!king_addons_freemius()->can_use_premium_code__premium_only()) {
            $this->add_control(
                'query_source_cpt_pro_notice',
                [
                    'raw' => 'This option is available<br> in the <strong><a href="https://kingaddons.com/pricing/?utm_source=kng-module-grid-upgrade-pro&utm_medium=plugin&utm_campaign=kng" target="_blank">Pro version</a></strong>',
                    'type' => Controls_Manager::RAW_HTML,
                    'content_classes' => 'king-addons-pro-notice',
                    'condition' => [
                        'query_source!' => ['post', 'page', 'pro-pd', 'pro-ft', 'pro-sl', 'product', 'featured', 'sale'],
                    ]
                ]
            );
        }


        $post_taxonomies = Core::getCustomTypes('tax', false);

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

        $this->add_control(
            'query_tax_selection',
            [
                'label' => esc_html__('Selection Taxonomy', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'category',
                'options' => $post_taxonomies,
                'condition' => [
                    'query_source' => 'related',
                ],
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


        foreach ($this->post_types as $slug => $title) {
            if ('featured' !== $slug && 'sale' !== $slug) {
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
        }


        foreach ($this->post_types as $slug => $title) {
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
                'default' => 10,
                'min' => 0,
                'condition' => [
                    'query_selection' => 'dynamic',
                ]
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
            'post_order',
            [
                'label' => esc_html__('Order', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'DESC',
                'options' => [
                    'ASC' => esc_html__('Ascending', 'king-addons'),
                    'DESC' => esc_html__('Descending', 'king-addons'),
                ],
            ]
        );

        $this->add_control(
            'post_orderby',
            [
                'label' => esc_html__('Order By', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'date',
                'options' => [
                    'date' => esc_html__('Date', 'king-addons'),
                    'modified' => esc_html__('Last Modified', 'king-addons'),
                    'rand' => esc_html__('Rand', 'king-addons'),
                    'title' => esc_html__('Title', 'king-addons'),
                    'ID' => esc_html__('Post ID', 'king-addons'),
                    'author' => esc_html__('Post Author', 'king-addons'),
                    'comment_count' => esc_html__('Comment Count', 'king-addons'),
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


        $this->add_section_ticker_items();


        $this->start_controls_section(
            'section_heading',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Heading', 'king-addons'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'heading_text',
            [
                'label' => esc_html__('Text', 'king-addons'),
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => 'Latest News',
            ]
        );

        $this->add_responsive_control(
            'heading_width',
            [
                'label' => esc_html__('Width', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
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
                'size_units' => ['%', 'px'],
                'default' => [
                    'unit' => 'px',
                    'size' => 120,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-ticker-heading' => 'min-width: {{SIZE}}{{UNIT}};',
                ],
                'render_type' => 'template',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'heading_position',
            [
                'label' => esc_html__('Position', 'king-addons'),
                'type' => Controls_Manager::CHOOSE,
                'default' => 'left',
                'label_block' => false,
                'options' => [
                    'left' => [
                        'title' => esc_html__('Left', 'king-addons'),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'right' => [
                        'title' => esc_html__('Right', 'king-addons'),
                        'icon' => 'eicon-h-align-right',
                    ],
                ],
                'prefix_class' => 'king-addons-ticker-heading-position-',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'heading_align',
            [
                'label' => esc_html__('Alignment', 'king-addons'),
                'type' => Controls_Manager::CHOOSE,
                'default' => 'center',
                'label_block' => false,
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
                'selectors_dictionary' => [
                    'left' => 'flex-start',
                    'center' => 'center',
                    'right' => 'flex-end'
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-ticker-heading' => '-webkit-justify-content: {{VALUE}};justify-content: {{VALUE}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'heading_icon_section',
            [
                'label' => esc_html__('Icon', 'king-addons'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control_heading_icon_type();


        Core::renderUpgradeProNotice($this, Controls_Manager::RAW_HTML, 'content-ticker', 'heading_icon_type', ['pro-cc']);

        $this->add_control(
            'heading_icon',
            [
                'label' => esc_html__('Select Icon', 'king-addons'),
                'type' => Controls_Manager::ICONS,
                'skin' => 'inline',
                'label_block' => false,
                'default' => [
                    'value' => 'far fa-star',
                    'library' => 'fa-regular',
                ],
                'condition' => [
                    'heading_icon_type' => 'fontawesome',
                ],
            ]
        );

        $this->add_control(
            'heading_icon_position',
            [
                'label' => esc_html__('Position', 'king-addons'),
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
                    ],
                ],
                'prefix_class' => 'king-addons-ticker-heading-icon-position-',
                'condition' => [
                    'heading_icon_type!' => 'none',
                ],
            ]
        );

        $this->add_responsive_control(
            'heading_icon_size',
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
                    'size' => 13,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-ticker-heading-icon' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .king-addons-ticker-heading-icon svg' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .king-addons-ticker-icon-circle' => 'height: {{SIZE}}{{UNIT}};width: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .king-addons-ticker-icon-circle:before' => 'height: {{SIZE}}{{UNIT}};width: {{SIZE}}{{UNIT}}; margin-top: calc(-{{SIZE}}{{UNIT}} / 2);margin-left: calc(-{{SIZE}}{{UNIT}} / 2);',
                    '{{WRAPPER}} .king-addons-ticker-icon-circle:after' => 'height: {{SIZE}}{{UNIT}};width: {{SIZE}}{{UNIT}}; margin-top: calc(-{{SIZE}}{{UNIT}} / 2);margin-left: calc(-{{SIZE}}{{UNIT}} / 2);',
                ],
                'condition' => [
                    'heading_icon_type!' => 'none',
                ],
            ]
        );

        $this->add_responsive_control(
            'heading_icon_distance',
            [
                'label' => esc_html__('Distance', 'king-addons'),
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
                    'size' => 7,
                ],
                'selectors' => [
                    '{{WRAPPER}}.king-addons-ticker-heading-icon-position-left .king-addons-ticker-heading-icon' => 'margin-right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}}.king-addons-ticker-heading-icon-position-right .king-addons-ticker-heading-icon' => 'margin-left: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'heading_icon_type!' => 'none',
                ],
            ]
        );


        $this->add_control(
            'heading_triangle',
            [
                'label' => esc_html__('Triangle', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'heading_triangle_position',
            [
                'label' => esc_html__('Vertical Position', 'king-addons'),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'default' => 'top',
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
                'prefix_class' => 'king-addons-ticker-heading-triangle-',
                'render_type' => 'template',
                'condition' => [
                    'heading_triangle' => 'yes',
                ],
            ]
        );

        $this->add_responsive_control(
            'heading_triangle_size',
            [
                'type' => Controls_Manager::SLIDER,
                'label' => esc_html__('Size', 'king-addons'),
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ]
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 8,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-ticker-heading:before' => 'border-width: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}}.king-addons-ticker-heading-position-left .king-addons-ticker-heading:before' => 'right: -{{SIZE}}{{UNIT}};',
                    '{{WRAPPER}}.king-addons-ticker-heading-position-right .king-addons-ticker-heading:before' => 'left: -{{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'heading_triangle' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'heading_link',
            [
                'label' => esc_html__('Link', 'king-addons'),
                'type' => Controls_Manager::URL,
                'dynamic' => [
                    'active' => true,
                ],
                'placeholder' => esc_html__('https://example.com', 'king-addons'),
                'separator' => 'before',

            ]
        );

        $this->end_controls_section();


        $this->start_controls_section(
            'section_settings',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Settings', 'king-addons'),
                'type' => Controls_Manager::SECTION,
            ]
        );

        $this->add_control(
            'image_switcher',
            [
                'label' => esc_html__('Show Image', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name' => 'image_size',
                'default' => 'full',
                'condition' => [
                    'image_switcher' => 'yes',
                ],
            ]
        );

        $this->add_responsive_control(
            'height',
            [
                'type' => Controls_Manager::SLIDER,
                'label' => esc_html__('Height', 'king-addons'),
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 20,
                        'max' => 300,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 30,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-ticker-slider' => 'height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .king-addons-ticker-item' => 'height: {{SIZE}}{{UNIT}};',
                ],
                'render_type' => 'template',
                'separator' => 'before',
            ]
        );

        $this->add_control_type_select();


        Core::renderUpgradeProNotice($this, Controls_Manager::RAW_HTML, 'content-ticker', 'type_select', ['pro-mq']);

        $this->add_responsive_control(
            'slider_amount',
            [
                'label' => esc_html__('Number of Slides', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'label_block' => false,
                'default' => 4,
                'widescreen_default' => 4,
                'laptop_default' => 4,
                'tablet_extra_default' => 4,
                'tablet_default' => 3,
                'mobile_extra_default' => 3,
                'mobile_default' => 1,
                'min' => 1,
                'max' => 10,
                'prefix_class' => 'king-addons-ticker-slider-columns-%s',
                'render_type' => 'template',
                'frontend_available' => true,
                'separator' => 'before',
                'condition' => [
                    'slider_effect' => 'hr-slide',
                    'type_select' => 'slider',
                ],
            ]
        );

        $this->add_control(
            'slides_to_scroll',
            [
                'label' => esc_html__('Slides to Scroll', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 10,
                'frontend_available' => true,
                'default' => 1,
                'widescreen_default' => 1,
                'laptop_default' => 1,
                'tablet_extra_default' => 1,
                'tablet_default' => 1,
                'mobile_extra_default' => 1,
                'mobile_default' => 1,
                'prefix_class' => 'king-addons-ticker-slides-to-scroll-',
                'render_type' => 'template',
                'condition' => [
                    'slider_effect' => 'hr-slide',
                    'type_select' => 'slider',
                ],
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
                        'max' => 100,
                    ]
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 30,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-ticker-slider .slick-slide' => 'margin-left: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .king-addons-ticker-slider .slick-list' => 'margin-left: -{{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .king-addons-ticker-marquee .king-addons-ticker-item' => 'margin-right: {{SIZE}}{{UNIT}};',
                ],
                'render_type' => 'template',
                'separator' => 'before',
                'conditions' => [
                    'relation' => 'or',
                    'terms' => [
                        [
                            'name' => 'type_select',
                            'operator' => '=',
                            'value' => 'marquee',
                        ],
                        [
                            'relation' => 'and',
                            'terms' => [
                                [
                                    'name' => 'type_select',
                                    'operator' => '=',
                                    'value' => 'slider',
                                ],
                                [
                                    'name' => 'slider_amount',
                                    'operator' => '!=',
                                    'value' => '1',
                                ],
                            ],
                        ],
                    ],
                ],
            ]
        );

        $this->add_control(
            'slider_nav',
            [
                'label' => esc_html__('Navigation', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'separator' => 'before',
                'condition' => [
                    'type_select' => 'slider',
                ],
            ]
        );

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
                    'type_select' => 'slider',
                    'slider_nav' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'slider_nav_style',
            [
                'label' => esc_html__('Style', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'horizontal',
                'options' => [
                    'horizontal' => esc_html__('Horizontal', 'king-addons'),
                    'vertical' => esc_html__('Vertical', 'king-addons'),
                ],
                'prefix_class' => 'king-addons-ticker-arrow-style-',
                'condition' => [
                    'type_select' => 'slider',
                    'slider_nav' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'slider_nav_position',
            [
                'label' => esc_html__('Position', 'king-addons'),
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
                    ],
                ],
                'prefix_class' => 'king-addons-ticker-arrow-position-',
                'render_type' => 'template',
                'condition' => [
                    'type_select' => 'slider',
                    'slider_nav' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'slider_autoplay',
            [
                'label' => esc_html__('Autoplay', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'frontend_available' => true,
                'separator' => 'before',
                'condition' => [
                    'type_select' => 'slider',
                ],
            ]
        );

        $this->add_control(
            'slider_autoplay_duration',
            [
                'label' => esc_html__('Autoplay Speed', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'default' => 3,
                'min' => 0,
                'max' => 15,
                'step' => 0.5,
                'frontend_available' => true,
                'condition' => [
                    'type_select' => 'slider',
                    'slider_autoplay' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'slider_pause_on_hover',
            [
                'label' => esc_html__('Pause Slide on Hover', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'condition' => [
                    'slider_autoplay' => 'yes',
                    'type_select' => 'slider',
                ],
            ]
        );

        $this->add_control(
            'slider_loop',
            [
                'label' => esc_html__('Infinite Loop', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'frontend_available' => true,
                'default' => 'yes',
                'separator' => 'before',
                'condition' => [
                    'type_select' => 'slider',
                ],
            ]
        );

        $this->add_control_slider_effect();


        Core::renderUpgradeProNotice($this, Controls_Manager::RAW_HTML, 'content-ticker', 'slider_effect', ['pro-tp', 'pro-fd', 'pro-vs']);

        $this->add_control_slider_effect_cursor();

        $this->add_control(
            'slider_effect_duration',
            [
                'label' => esc_html__('Transition Duration (seconds)', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'default' => 1,
                'min' => 0,
                'max' => 5,
                'step' => 0.1,
                'condition' => [
                    'type_select' => 'slider',
                ],
            ]
        );

        $this->add_control_marquee_direction();

        $this->add_control_marquee_pause_on_hover();

        $this->add_control_marquee_effect_duration();

        $this->end_controls_section();

        Core::renderProFeaturesSection($this, '', Controls_Manager::RAW_HTML, 'content-ticker', [
            'Add Custom Ticker Items (Instead of Loading Dynamically)',
            'Custom Post Types',
            'Marquee Animation - Smooth Animation with Direction Option',
            'Slider Animation Options - Typing, Fade & Vertical Slide',
            'Heading Icon Type - Animated Circle'
        ]);

        $this->start_controls_section(
            'section_style_button',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Heading', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->start_controls_tabs('tabs_heading_colors');

        $this->start_controls_tab(
            'tab_heading_normal_colors',
            [
                'label' => esc_html__('Normal', 'king-addons'),
            ]
        );

        $this->add_control(
            'heading_color',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-ticker-heading' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'heading_bg_color',
            [
                'label' => esc_html__('Background Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#5B03FF',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-ticker-heading' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .king-addons-ticker-heading::before' => 'border-right-color: {{VALUE}};background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'heading_icon_color',
            [
                'label' => esc_html__('Icon Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-ticker-heading-icon' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .king-addons-ticker-heading-icon svg' => 'fill: {{VALUE}}',
                    '{{WRAPPER}} .king-addons-ticker-icon-circle' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .king-addons-ticker-icon-circle::before' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .king-addons-ticker-icon-circle::after' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'heading_border_color',
            [
                'label' => esc_html__('Border Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-ticker-heading' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_heading_hover_colors',
            [
                'label' => esc_html__('Hover', 'king-addons'),
            ]
        );

        $this->add_control(
            'heading_hover_color',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-ticker-heading:hover' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'heading_hover_bg_color',
            [
                'label' => esc_html__('Background Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#5B03FF',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-ticker-heading:hover' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .king-addons-ticker-heading:hover:before' => 'border-right-color: {{VALUE}};background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'heading_hover_icon_color',
            [
                'label' => esc_html__('Icon Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-ticker-heading:hover .king-addons-ticker-heading-icon' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .king-addons-ticker-heading:hover .king-addons-ticker-heading-icon svg' => 'fill: {{VALUE}}',
                    '{{WRAPPER}} .king-addons-ticker-heading:hover .king-addons-ticker-icon-circle' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .king-addons-ticker-heading:hover .king-addons-ticker-icon-circle::before' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .king-addons-ticker-heading:hover .king-addons-ticker-icon-circle::after' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'heading_hover_border_color',
            [
                'label' => esc_html__('Border Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-ticker-heading:hover' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_control(
            'heading_transition_duration',
            [
                'label' => esc_html__('Transition Duration (seconds)', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'default' => 0.1,
                'min' => 0,
                'max' => 5,
                'step' => 0.1,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-ticker-heading' => '-webkit-transition-duration: {{VALUE}}s;transition-duration: {{VALUE}}s',
                    '{{WRAPPER}} .king-addons-ticker-heading svg' => '-webkit-transition-duration: {{VALUE}}s;transition-duration: {{VALUE}}s',

                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'heading_typography_divider',
            [
                'type' => Controls_Manager::DIVIDER,
                'style' => 'thick',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'heading_typography',
                'selector' => '{{WRAPPER}} .king-addons-ticker-heading-text',
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'heading_padding',
            [
                'label' => esc_html__('Padding', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px',],
                'default' => [
                    'top' => 0,
                    'right' => 10,
                    'bottom' => 0,
                    'left' => 10,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-ticker-heading' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'heading_border_type',
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
                    '{{WRAPPER}} .king-addons-ticker-heading' => 'border-style: {{VALUE}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'heading_border_width',
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
                    '{{WRAPPER}} .king-addons-ticker-heading' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'heading_border_type!' => 'none',
                ],
            ]
        );

        $this->add_responsive_control(
            'heading_border_radius',
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
                    '{{WRAPPER}} .king-addons-ticker-heading' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->end_controls_section();


        $this->start_controls_section(
            'section_style_input',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Content', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'content_bg_color',
            [
                'label' => esc_html__('Background Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-content-ticker-inner' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .king-addons-ticker-gradient:after' => 'background-image: linear-gradient(to right,rgba(255,255,255,0),{{VALUE}});',
                    '{{WRAPPER}} .king-addons-ticker-gradient:before' => 'background-image: linear-gradient(to left,rgba(255,255,255,0),{{VALUE}});',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'content_box_shadow',
                'selector' => '{{WRAPPER}} .king-addons-content-ticker',
            ]
        );

        $this->add_control(
            'content_gradient_position',
            [
                'label' => esc_html__('Gradient Position', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'right',
                'options' => [
                    'none' => esc_html__('None', 'king-addons'),
                    'left' => esc_html__('Left', 'king-addons'),
                    'right' => esc_html__('Right', 'king-addons'),
                    'both' => esc_html__('Both', 'king-addons'),
                ],
                'prefix_class' => 'king-addons-ticker-gradient-type-',
                'render_type' => 'template',
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'content_padding',
            [
                'label' => esc_html__('Padding', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'default' => [
                    'top' => 5,
                    'right' => 10,
                    'bottom' => 5,
                    'left' => 30,
                ],
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-content-ticker-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                'default' => 'solid',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-content-ticker' => 'border-style: {{VALUE}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'content_border_width',
            [
                'label' => esc_html__('Border Width', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'default' => [
                    'top' => 1,
                    'right' => 1,
                    'bottom' => 1,
                    'left' => 1,
                ],
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-content-ticker' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'content_border_type!' => 'none',
                ],
            ]
        );

        $this->add_control(
            'content_border_color',
            [
                'label' => esc_html__('Border Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#dbdbdb',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-content-ticker' => 'border-color: {{VALUE}};',
                ],
                'condition' => [
                    'content_border_type!' => 'none',
                ],
            ]
        );

        $this->add_control(
            'content_border_radius',
            [
                'label' => esc_html__('Border Radius', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'default' => [
                    'top' => 0,
                    'right' => 0,
                    'bottom' => 0,
                    'left' => 0,
                ],
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-content-ticker' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );


        $this->add_control(
            'content_title_section',
            [
                'label' => esc_html__('Title', 'king-addons'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'content_title_typography',
                'selector' => '{{WRAPPER}} .king-addons-ticker-title',
            ]
        );

        $this->start_controls_tabs('tabs_content_title_colors');

        $this->start_controls_tab(
            'tab_content_title_normal_colors',
            [
                'label' => esc_html__('Normal', 'king-addons'),
            ]
        );


        $this->add_control(
            'content_title_color',
            [
                'label' => esc_html__('Title Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#555555',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-ticker-title a' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .king-addons-ticker-title:after' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_content_title_hover_colors',
            [
                'label' => esc_html__('Hover', 'king-addons'),
            ]
        );

        $this->add_control(
            'content_hover_title_color',
            [
                'label' => esc_html__('Title Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-ticker-title:hover a' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .king-addons-ticker-title:after' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();


        $this->add_control(
            'content_image_section',
            [
                'label' => esc_html__('Image', 'king-addons'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'content_image_width',
            [
                'label' => esc_html__('Width', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 200,
                    ],
                ],
                'size_units' => ['px'],
                'default' => [
                    'unit' => 'px',
                    'size' => 50,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-ticker-image' => 'max-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();


        $this->start_controls_section(
            'section_style_nav',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Navigation', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'type_select' => 'slider',
                    'slider_nav' => 'yes',
                ],
            ]
        );

        $this->start_controls_tabs('tabs_nav_style');

        $this->start_controls_tab(
            'tab_nav_normal',
            [
                'label' => esc_html__('Normal', 'king-addons'),
            ]
        );

        $this->add_control(
            'nav_color',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-ticker-arrow' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'nav_bg_color',
            [
                'label' => esc_html__('Background Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#5B03FF',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-ticker-arrow' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'nav_border_color',
            [
                'label' => esc_html__('Border Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-ticker-arrow' => 'border-color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_nav_hover',
            [
                'label' => esc_html__('Hover', 'king-addons'),
            ]
        );

        $this->add_control(
            'nav_hover_color',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-ticker-arrow:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'nav_hover_bg_color',
            [
                'label' => esc_html__('Background Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#4D02D8',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-ticker-arrow:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'nav_hover_border_color',
            [
                'label' => esc_html__('Border Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-ticker-arrow:hover' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_control(
            'nav_transition_duration',
            [
                'label' => esc_html__('Transition Duration (seconds)', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'default' => 0.1,
                'min' => 0,
                'max' => 5,
                'step' => 0.1,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-ticker-arrow' => '-webkit-transition-duration: {{VALUE}}s;transition-duration: {{VALUE}}s',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'nav_font_size',
            [
                'label' => esc_html__('Font Size', 'king-addons'),
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
                    'size' => 14,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-ticker-arrow' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'nav_size',
            [
                'label' => esc_html__('Box Size', 'king-addons'),
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
                    'size' => 20,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-ticker-arrow' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'nav_gutter',
            [
                'type' => Controls_Manager::SLIDER,
                'label' => esc_html__('Gutter', 'king-addons'),
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ]
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 2,
                ],
                'selectors' => [
                    '{{WRAPPER}}.king-addons-ticker-arrow-style-vertical .king-addons-ticker-prev-arrow' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}}.king-addons-ticker-arrow-style-horizontal .king-addons-ticker-prev-arrow' => 'margin-right: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'nav_border_type',
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
                    '{{WRAPPER}} .king-addons-ticker-arrow' => 'border-style: {{VALUE}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'nav_border_width',
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
                    '{{WRAPPER}} .king-addons-ticker-arrow' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'nav_border_type!' => 'none',
                ],
            ]
        );

        $this->add_control(
            'nav_border_radius',
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
                    '{{WRAPPER}} .king-addons-ticker-arrow' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->end_controls_section();
    }


    public function get_related_taxonomies()
    {
        // Grab all custom post types once
        $this->post_types = Core::getCustomTypes('post', false);

        // Build relations array where each slug maps directly to its taxonomies
        $relations = [];
        foreach ($this->post_types as $slug => $title) {
            $relations[$slug] = get_object_taxonomies($slug);
        }

        return json_encode($relations);
    }

    public function get_main_query_args()
    {
        $settings = $this->get_settings();
        $author = !empty($settings['query_author'])
            ? implode(',', (array)$settings['query_author'])
            : '';

        // If query_source is one of the pro-* values, default it to 'post'
        if (in_array($settings['query_source'], ['pro-pd', 'pro-ft', 'pro-sl'], true)) {
            $settings['query_source'] = 'post';
        }

        // Default query args
        $args = [
            'post_type' => $settings['query_source'],
            'tax_query' => $this->get_tax_query_args(),
            'post__not_in' => $settings['query_exclude_' . $settings['query_source']] ?? '',
            'posts_per_page' => $settings['query_posts_per_page'],
            'orderby' => $settings['post_orderby'],
            'order' => $settings['post_order'],
            'author' => $author,
            'offset' => $settings['query_offset'],
        ];

        // Manual selection override
        if ($settings['query_selection'] === 'manual') {
            $post_ids = $settings['query_manual_' . $settings['query_source']] ?? [''];
            $args = [
                'post_type' => $settings['query_source'],
                'post__in' => $post_ids,
                'orderby' => $settings['post_orderby'],
                'order' => $settings['post_order'],
            ];
        }

        // Current post query override
        if ($settings['query_source'] === 'current') {
            global $wp_query;
            $args = $wp_query->query_vars;
            $args['posts_per_page'] = $settings['query_posts_per_page'];
            $args['orderby'] = $settings['post_orderby'];
        }

        // Related posts query override
        if ($settings['query_source'] === 'related') {
            $args = [
                'post_type' => get_post_type(get_the_ID()),
                'tax_query' => $this->get_tax_query_args(),
                'post__not_in' => [get_the_ID()],
                'ignore_sticky_posts' => 1,
                'posts_per_page' => $settings['query_posts_per_page'],
                'orderby' => $settings['post_orderby'],
                'order' => $settings['post_order'],
                'offset' => $settings['query_offset'],
            ];
        }

        // Featured products override
        if ($settings['query_source'] === 'featured') {
            $args['post_type'] = 'product';
            $args['tax_query'] = [[
                'taxonomy' => 'product_visibility',
                'field' => 'name',
                'terms' => 'featured',
                'operator' => 'IN',
            ]];
        }

        // Sale products override
        if ($settings['query_source'] === 'sale') {
            $args['post_type'] = 'product';
            $args['meta_query'] = [[
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
                ],
            ]];
        }

        return $args;
    }

    public function get_tax_query_args()
    {
        $settings = $this->get_settings();
        $tax_query = [];

        // Related query uses a special approach
        if ($settings['query_source'] === 'related') {
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

        // Otherwise build from normal taxonomies
        foreach (get_object_taxonomies($settings['query_source']) as $tax) {
            $field_name = 'query_taxonomy_' . $tax;
            if (!empty($settings[$field_name])) {
                $tax_query[] = [
                    'taxonomy' => $tax,
                    'field' => 'id',
                    'terms' => $settings[$field_name],
                ];
            }
        }

        return $tax_query;
    }

    public function king_addons_content_ticker_dynamic()
    {
        $settings = $this->get_settings();
        $posts = new WP_Query($this->get_main_query_args());

        if ($posts->have_posts()) :
            while ($posts->have_posts()) :
                $posts->the_post();

                $image_id = get_post_thumbnail_id();
                $image_src = Group_Control_Image_Size::get_attachment_image_src($image_id, 'image_size', $settings);
                $image_alt = wp_get_attachment_caption($image_id) ?: get_the_title();
                ?>
                <div class="king-addons-ticker-item">
                    <?php if ($settings['link_type'] === 'box') : ?>
                        <a class="king-addons-ticker-link" href="<?php echo esc_url(get_the_permalink()); ?>"></a>
                    <?php endif; ?>

                    <?php if (has_post_thumbnail()) : ?>
                        <div class="king-addons-ticker-image">
                            <?php
                            if (in_array($settings['link_type'], ['image', 'image-title'], true)) {
                                echo '<a href="' . esc_url(get_the_permalink()) . '">';
                            }

                            if ($settings['image_switcher'] === 'yes' && $image_src) {
                                echo '<img src="' . esc_url($image_src) . '" alt="' . esc_attr($image_alt) . '">';
                            }

                            if (in_array($settings['link_type'], ['image', 'image-title'], true)) {
                                echo '</a>';
                            }
                            ?>
                        </div>
                    <?php endif; ?>

                    <h3 class="king-addons-ticker-title">
                        <div class="king-addons-ticker-title-inner">
                            <?php
                            if (in_array($settings['link_type'], ['title', 'image-title'], true)) {
                                echo '<a href="' . esc_url(get_the_permalink()) . '">';
                            }

                            the_title();

                            if (in_array($settings['link_type'], ['title', 'image-title'], true)) {
                                echo '</a>';
                            }
                            ?>
                        </div>
                    </h3>
                </div>
            <?php
            endwhile;
            wp_reset_postdata();
        endif;
    }

    public function king_addons_content_ticker_custom()
    {
    }

    public function king_addons_content_ticker_heading()
    {
        $settings = $this->get_settings();
        $heading_element = 'div';
        $heading_link = $settings['heading_link']['url'] ?? '';
        $this->add_render_attribute('heading_attribute', 'class', 'king-addons-ticker-heading');

        // Turn heading into a link if the URL is set
        if ($heading_link !== '') {
            $heading_element = 'a';
            $this->add_render_attribute('heading_attribute', 'href', esc_url($heading_link));

            if (!empty($settings['heading_link']['is_external'])) {
                $this->add_render_attribute('heading_attribute', 'target', '_blank');
            }
            if (!empty($settings['heading_link']['nofollow'])) {
                $this->add_render_attribute('heading_attribute', 'rel', 'nofollow');
            }
        }

        // Fallback if the user cannot use certain premium features
        if (!king_addons_freemius()->can_use_premium_code__premium_only()) {
            $settings['heading_icon_type'] = ($settings['heading_icon_type'] === 'pro-cc') ? 'none' : $settings['heading_icon_type'];
        }
        ?>
        <<?php echo esc_html($heading_element) . ' ' . $this->get_render_attribute_string('heading_attribute'); ?>>
        <span class="king-addons-ticker-heading-text">
                <?php echo esc_html($settings['heading_text']); ?>
            </span>
        <span class="king-addons-ticker-heading-icon">
                <?php if ($settings['heading_icon_type'] === 'fontawesome') : ?>
                    <?php Icons_Manager::render_icon($settings['heading_icon']); ?>
                <?php elseif ($settings['heading_icon_type'] === 'circle') : ?>
                    <span class="king-addons-ticker-icon-circle"></span>
                <?php endif; ?>
            </span>
        </<?php echo esc_html($heading_element); ?>>
        <?php
    }

    public function king_addons_content_ticker_slider()
    {
        $settings = $this->get_settings();
        $slider_is_rtl = is_rtl();
        $slider_direction = $slider_is_rtl ? 'rtl' : 'ltr';

        // Fallback for premium code
        if (!king_addons_freemius()->can_use_premium_code__premium_only()) {
            $settings['slider_effect'] = 'hr-slide';
        }

        // Slider options
        $slider_options = [
            'arrows' => false,
            'autoplay' => ($settings['slider_autoplay'] === 'yes'),
            'autoplaySpeed' => absint($settings['slider_autoplay_duration'] * 1000),
            'infinite' => ($settings['slider_loop'] === 'yes'),
            'pauseOnHover' => $settings['slider_pause_on_hover'],
            'rtl' => $slider_is_rtl,
            'speed' => absint($settings['slider_effect_duration'] * 1000),
        ];

        if ($settings['slider_effect'] === 'vr-slide') {
            $slider_options['vertical'] = true;
        }

        if ($settings['slider_nav'] === 'yes') {
            $slider_options['arrows'] = true;
            $icon = esc_attr($settings['slider_nav_icon']);
            $slider_options['prevArrow'] = "<div class=\"king-addons-ticker-prev-arrow king-addons-ticker-arrow\"><i class=\"$icon\"></i></div>";
            $slider_options['nextArrow'] = "<div class=\"king-addons-ticker-next-arrow king-addons-ticker-arrow\"><i class=\"$icon\"></i></div>";
        }

        $this->add_render_attribute('ticker-slider-attribute', [
            'class' => 'king-addons-ticker-slider',
            'dir' => $slider_direction,
            'data-slick' => wp_json_encode($slider_options),
        ]);

        if ($settings['content_gradient_position'] !== 'none') {
            $this->add_render_attribute('ticker-slider-attribute', 'class', 'king-addons-ticker-gradient');
        }

        // Fallback for premium code
        if (!king_addons_freemius()->can_use_premium_code__premium_only()) {
            $settings['post_type'] = 'dynamic';
        }
        ?>
        <div <?php echo $this->get_render_attribute_string('ticker-slider-attribute'); ?>
                data-slide-effect="<?php echo esc_attr($settings['slider_effect']); ?>">
            <?php
            // Render either dynamic or custom content
            if ($settings['post_type'] === 'dynamic') {
                $this->king_addons_content_ticker_dynamic();
            } else {
                $this->king_addons_content_ticker_custom();
            }
            ?>
        </div>

        <div class="king-addons-ticker-slider-controls"></div>
        <?php
    }

    public function king_addons_content_ticker_marquee()
    {
    }

    protected function render()
    {
        $settings = $this->get_settings();
        ?>
        <div class="king-addons-content-ticker">
            <?php
            // Heading section (icon/text) if needed
            if ($settings['heading_text'] !== '' || $settings['heading_icon_type'] !== 'none') {
                $this->king_addons_content_ticker_heading();
            }
            ?>
            <div class="king-addons-content-ticker-inner">
                <?php
                // Fallback for premium code
                if (!king_addons_freemius()->can_use_premium_code__premium_only()) {
                    $settings['type_select'] = 'slider';
                }

                // Choose between slider or marquee
                if ($settings['type_select'] === 'slider') {
                    $this->king_addons_content_ticker_slider();
                } else {
                    $this->king_addons_content_ticker_marquee();
                }
                ?>
            </div>
        </div>
        <?php
    }
}