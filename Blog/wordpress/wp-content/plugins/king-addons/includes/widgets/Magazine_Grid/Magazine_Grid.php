<?php

namespace King_Addons;

use Elementor\Icons_Manager;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Repeater;
use Elementor\Group_Control_Image_Size;
use WP_Query;

if (!defined('ABSPATH')) {
    exit;
}

class Magazine_Grid extends Widget_Base
{

    public function get_name()
    {
        return 'king-addons-magazine-grid';
    }

    public function get_title()
    {
        return esc_html__('Magazine Grid & Slider/Carousel', 'king-addons');
    }

    public function get_icon()
    {
        return 'king-addons-icon king-addons-magazine-grid';
    }

    public function get_categories()
    {
        return ['king-addons'];
    }

    public function get_keywords()
    {
        return ['king addons', 'king', 'addons', 'kingaddons', 'king-addons', 'blog', 'grid', 'posts', 'magazine', 'masonry',
            'magazine grid', 'magazine slider', 'isotope', 'slick', 'post tiles', 'posts tiles', 'post', 'journal', 'gr',
            'tile', 'tiles', 'carousel', 'loop', 'filterable'];
    }

    public function get_script_depends()
    {
        return [
            KING_ADDONS_ASSETS_UNIQUE_KEY . '-magazine-grid-script',
            KING_ADDONS_ASSETS_UNIQUE_KEY . '-isotope-kng',
            KING_ADDONS_ASSETS_UNIQUE_KEY . '-slick-slick',
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
            KING_ADDONS_ASSETS_UNIQUE_KEY . '-grid-grid',
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

    public function add_option_query_source()
    {
        $post_types = [
            'post' => esc_html__('Posts', 'king-addons'),
            'page' => esc_html__('Pages', 'king-addons'),
        ];

        $excluded_slugs = ['product', 'e-landing-page'];
        $can_use_premium = king_addons_freemius()->can_use_premium_code__premium_only();
        $custom_post_types = Core::getCustomTypes('post');

        foreach ($custom_post_types as $slug => $title) {
            // Skip excluded slugs
            if (in_array($slug, $excluded_slugs, true)) {
                continue;
            }

            // Add either as-is or with "Pro" label
            if ($can_use_premium) {
                $post_types[$slug] = esc_html($title);
            } else {
                $post_types['pro-' . substr($slug, 0, 2)] = esc_html($title) . ' (Pro)';
            }
        }

        // Append special "Pro" keys
        $post_types['pro-cr'] = esc_html__('Current Query (Pro)', 'king-addons');
        $post_types['pro-rl'] = esc_html__('Related Query (Pro)', 'king-addons');

        return $post_types;
    }

    public function get_available_taxonomies()
    {
        $post_taxonomies = [
            'category' => esc_html__('Categories', 'king-addons'),
            'post_tag' => esc_html__('Tags', 'king-addons'),
        ];

        $excluded_slugs = ['product_tag', 'product_cat'];
        $can_use_premium = king_addons_freemius()->can_use_premium_code__premium_only();
        $custom_post_taxonomies = Core::getCustomTypes('tax');

        foreach ($custom_post_taxonomies as $slug => $title) {
            // Skip excluded slugs
            if (in_array($slug, $excluded_slugs, true)) {
                continue;
            }

            // Add either as-is or with "Pro" label
            if ($can_use_premium) {
                $post_taxonomies[$slug] = esc_html($title);
            } else {
                $post_taxonomies['pro-' . substr($slug, 0, 2)] = esc_html($title) . ' (Pro)';
            }
        }

        return $post_taxonomies;
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
                    'pro-d' => esc_html__('Post ID', 'king-addons'),
                    'pro-ar' => esc_html__('Post Author', 'king-addons'),
                    'pro-cc' => esc_html__('Comment Count', 'king-addons')
                ],
                'condition' => [
                    'query_randomize!' => 'rand',
                ]
            ]
        );
    }

    public function add_control_force_responsive_one_column()
    {
        $this->add_control(
            'force_responsive_one_column',
            [
                'label' => sprintf(__('Force Responsive One Column %s', 'king-addons'), '<i class="eicon-pro-icon"></i>'),
                'type' => Controls_Manager::SWITCHER,
                'classes' => 'king-addons-pro-control'
            ]
        );
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

    public function add_section_slider()
    {
        $this->start_controls_section(
            'section_slider',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Posts Slider', 'king-addons'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'slider_section_pro_notice',
            [
                'raw' => 'Tiled Post Slider is available<br> in the <strong><a href="https://kingaddons.com/pricing/?utm_source=kng-module-magazine-grid-settings-upgrade-pro&utm_medium=plugin&utm_campaign=kng" target="_blank">Pro version</a></strong>',

                'type' => Controls_Manager::RAW_HTML,
                'content_classes' => 'king-addons-pro-notice',
            ]
        );

        $this->end_controls_section();
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

    public function add_section_style_custom_field1()
    {
    }

    public function add_section_style_custom_field2()
    {
    }

    public function add_section_style_grid_slider_nav()
    {
    }

    public function add_section_style_likes()
    {
    }

    public function add_section_style_sharing()
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

        unset($post_types['product']);
        unset($post_types['e-landing-page']);
        
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

        Core::renderUpgradeProNotice($this, Controls_Manager::RAW_HTML, 'magazine-grid', 'query_source', ['pro-rl', 'pro-cr']);

        if (!king_addons_freemius()->can_use_premium_code__premium_only()) {
            $this->add_control(
                'query_source_cpt_pro_notice',
                [
                    'raw' => 'This option is available<br> in the <strong><a href="https://kingaddons.com/pricing/?utm_source=kng-module-grid-upgrade-pro&utm_medium=plugin&utm_campaign=kng" target="_blank">Pro version</a></strong>',
                    'type' => Controls_Manager::RAW_HTML,
                    'content_classes' => 'king-addons-pro-notice',
                    'condition' => [
                        'query_source!' => ['post', 'page', 'related', 'current', 'pro-rl', 'pro-cr'],
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

        $this->add_control_query_randomize();

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

        $this->add_control(
            'king_addons_grid_layout_select',
            [
                'label' => esc_html__('Select Layout', 'king-addons'),
                'type' => Controls_Manager::CHOOSE,
                'default' => '1-1-3',
                'options' => [
                    '1-2' => [
                        'title' => esc_html__('Layout 1 (3 posts) (Pro)', 'king-addons'),
                    ],
                    '1-3' => [
                        'title' => esc_html__('Layout 2 (4 posts) (Pro)', 'king-addons'),
                    ],
                    '1-4' => [
                        'title' => esc_html__('Layout 3 (5 posts) (Pro)', 'king-addons'),
                    ],
                    '1-1-2' => [
                        'title' => esc_html__('Layout 4 (4 posts) (Pro)', 'king-addons'),
                    ],
                    '2-1-2' => [
                        'title' => esc_html__('Layout 5 (5 posts) (Pro)', 'king-addons'),
                    ],
                    '1vh-3h' => [
                        'title' => esc_html__('Layout 5.0 (4 posts) (Pro)', 'king-addons'),
                    ],
                    '1-1-1' => [
                        'title' => esc_html__('Layout 5.1 (3 posts)', 'king-addons'),
                    ],
                    '1-1-3' => [
                        'title' => esc_html__('Layout 5.2 (5 posts)', 'king-addons'),
                    ],
                    '2-3' => [
                        'title' => esc_html__('Layout 6 (5 posts)', 'king-addons'),
                    ],
                    '2-h' => [
                        'title' => esc_html__('Layout 7 (2, 4, 6 posts)', 'king-addons'),
                    ],
                    '3-h' => [
                        'title' => esc_html__('Layout 8 (3, 6, 9 posts)', 'king-addons'),
                    ],
                    '4-h' => [
                        'title' => esc_html__('Layout 8 (4, 8, 12 posts)', 'king-addons'),
                    ]
                ],
            ]
        );

        if (!king_addons_freemius()->can_use_premium_code__premium_only()) {
            $this->add_control(
                'layout_select_pro_notice',
                [
                    'raw' => 'These Layout options are available<br> in the <strong><a href="https://kingaddons.com/pricing/?utm_source=kng-module-magazine-grid-settings-upgrade-pro&utm_medium=plugin&utm_campaign=kng" target="_blank">Pro version</a></strong>',

                    'type' => Controls_Manager::RAW_HTML,
                    'content_classes' => 'king-addons-pro-notice',
                    'condition' => [
                        'king_addons_grid_layout_select' => ['1-2', '1-3', '1-4', '1-1-2', '2-1-2', '1vh-3h']
                    ]
                ]
            );
        }

        $this->add_control_force_responsive_one_column();

        $this->add_control_open_links_in_new_tab();

        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name' => 'layout_image_crop',
                'default' => 'full',
            ]
        );

        $this->add_control(
            'layout_rows_number',
            [
                'label' => esc_html__('Rows Number', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'render_type' => 'template',
                'default' => '1',
                'options' => [
                    '1' => esc_html__('1 Row', 'king-addons'),
                    '2' => esc_html__('2 Rows', 'king-addons'),
                    '3' => esc_html__('3 Rows', 'king-addons'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-magazine-grid-add-2-h' => 'grid-template-rows: repeat({{VALUE}}, 1fr);',
                    '{{WRAPPER}} .king-addons-magazine-grid-add-3-h' => 'grid-template-rows: repeat({{VALUE}}, 1fr);',
                    '{{WRAPPER}} .king-addons-magazine-grid-add-4-h' => 'grid-template-rows: repeat({{VALUE}}, 1fr);',
                ],
                'condition' => [
                    'king_addons_grid_layout_select' => ['2-h', '3-h', '4-h']
                ],
            ]
        );

        $this->add_responsive_control(
            'layout_container_height',
            [
                'type' => Controls_Manager::SLIDER,
                'label' => esc_html__('Container Height', 'king-addons'),
                'range' => [
                    'px' => [
                        'min' => 100,
                        'max' => 1500,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 520,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-magazine-grid' => 'min-height: {{SIZE}}px;',
                ],
                'separator' => 'before'
            ]
        );

        $this->add_control(
            'layout_big_post_width',
            [
                'type' => Controls_Manager::SLIDER,
                'label' => esc_html__('Featured Box Width', 'king-addons'),
                'range' => [
                    '%' => [
                        'min' => 10,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => '%',
                    'size' => 50,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-magazine-grid-add-1-2' => 'grid-template-columns: {{SIZE}}% 1fr; -ms-grid-columns: {{SIZE}}% 1fr;',
                    '{{WRAPPER}} .king-addons-magazine-grid-add-1-3' => 'grid-template-columns: {{SIZE}}% 1fr 1fr; -ms-grid-columns: {{SIZE}}% 1fr 1fr;',
                    '{{WRAPPER}} .king-addons-magazine-grid-add-1-4' => 'grid-template-columns: {{SIZE}}% 1fr 1fr; -ms-grid-columns: {{SIZE}}% 1fr 1fr;',
                    '{{WRAPPER}} .king-addons-magazine-grid-add-1-1-2' => 'grid-template-columns: {{SIZE}}% 1fr 1fr; -ms-grid-columns: {{SIZE}}% 1fr 1fr;',
                    '{{WRAPPER}} .king-addons-magazine-grid-add-1vh-3h' => 'grid-template-columns: {{SIZE}}% 1fr; -ms-grid-columns: {{SIZE}}% 1fr;',
                    '{{WRAPPER}} .king-addons-magazine-grid-add-2-1-2' => 'grid-template-columns: 1fr {{SIZE}}% 1fr; -ms-grid-columns: 1fr {{SIZE}}% 1fr;',
                    '{{WRAPPER}} .king-addons-magazine-grid-add-1-1-1' => 'grid-template-columns: 1fr {{SIZE}}% 1fr; -ms-grid-columns: 1fr {{SIZE}}% 1fr;',
                ],
                'condition' => [
                    'king_addons_grid_layout_select' => ['1-2', '1-3', '1-4', '1-1-2', '1vh-3h', '2-1-2', '1-1-1']
                ]
            ]
        );

        $this->add_responsive_control(
            'layout_gutter_hr',
            [
                'label' => esc_html__('Horizontal Gutter', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 4,
                ],
                'widescreen_default' => [
                    'size' => 4,
                ],
                'laptop_default' => [
                    'size' => 4,
                ],
                'tablet_extra_default' => [
                    'size' => 4,
                ],
                'tablet_default' => [
                    'size' => 4,
                ],
                'mobile_extra_default' => [
                    'size' => 4,
                ],
                'mobile_default' => [
                    'size' => 4,
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-magazine-grid' => 'grid-column-gap: {{SIZE}}px;',
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
                    'size' => 4,
                ],
                'widescreen_default' => [
                    'size' => 4,
                ],
                'laptop_default' => [
                    'size' => 4,
                ],
                'tablet_extra_default' => [
                    'size' => 4,
                ],
                'tablet_default' => [
                    'size' => 4,
                ],
                'mobile_extra_default' => [
                    'size' => 4,
                ],
                'mobile_default' => [
                    'size' => 4,
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-magazine-grid' => 'grid-row-gap: {{SIZE}}px;',
                ],
                'separator' => 'after'
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

        Core::renderUpgradeProNotice($repeater, Controls_Manager::RAW_HTML, 'magazine-grid', 'element_select', ['pro-lk', 'pro-shr']);
        Core::renderUpgradeProNotice($repeater, Controls_Manager::RAW_HTML, 'magazine-grid', 'element_select', ['pro-cf']);

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
                    'raw' => 'Vertical Align option is available<br> in the <strong><a href="https://kingaddons.com/pricing/?utm_source=kng-module-magazine-grid-settings-upgrade-pro&utm_medium=plugin&utm_campaign=kng" target="_blank">Pro version</a></strong>',

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

        $repeater->add_control('element_custom_field', $this->add_repeater_args_element_custom_field());

        $repeater->add_control('element_custom_field_btn_link', $this->add_repeater_args_element_custom_field_btn_link());

        $repeater->add_control('element_custom_field_new_tab', $this->add_repeater_args_element_custom_field_new_tab());

        $repeater->add_control('custom_field_wrapper_html_divider1', $this->add_repeater_args_custom_field_wrapper_html_divider1());

        $repeater->add_control('element_custom_field_wrapper', $this->add_repeater_args_element_custom_field_wrapper());

        $repeater->add_control('element_custom_field_wrapper_html', $this->add_repeater_args_element_custom_field_wrapper_html());

        $repeater->add_control('custom_field_wrapper_html_divider2', $this->add_repeater_args_custom_field_wrapper_html_divider2());

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
            ]
        );

        $repeater->add_control(
            'element_animation',
            [
                'label' => esc_html__('Select Animation', 'king-addons'),
                'type' => 'king-addons-animations',
                'default' => 'none',
            ]
        );


        Core::renderUpgradeProNotice($repeater, Controls_Manager::RAW_HTML, 'magazine-grid', 'element_animation', ['pro-slrt', 'pro-slxrt', 'pro-slbt', 'pro-sllt', 'pro-sltp', 'pro-slxlt', 'pro-sktp', 'pro-skrt', 'pro-skbt', 'pro-sklt', 'pro-scup', 'pro-scdn', 'pro-rllt', 'pro-rlrt']);

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
                ],
            ]
        );


        Core::renderUpgradeProNotice($repeater, Controls_Manager::RAW_HTML, 'magazine-grid', 'element_animation_timing', ['pro-eio', 'pro-eiqd', 'pro-eicb', 'pro-eiqrt', 'pro-eiqnt', 'pro-eisn', 'pro-eiex', 'pro-eicr', 'pro-eibk', 'pro-eoqd', 'pro-eocb', 'pro-eoqrt', 'pro-eoqnt', 'pro-eosn', 'pro-eoex', 'pro-eocr', 'pro-eobk', 'pro-eioqd', 'pro-eiocb', 'pro-eioqrt', 'pro-eioqnt', 'pro-eiosn', 'pro-eioex', 'pro-eiocr', 'pro-eiobk',]);

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
                'default' => 'medium',
                'condition' => [
                    'element_animation!' => 'none',
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
                        'element_select' => 'category',
                        'element_align_vr' => 'bottom',
                        'element_align_hr' => 'left',
                    ],
                    [
                        'element_select' => 'title',
                        'element_align_vr' => 'bottom',
                        'element_align_hr' => 'left',
                    ],
                    [
                        'element_select' => 'date',
                        'element_align_vr' => 'bottom',
                        'element_display' => 'inline',
                        'element_align_hr' => 'left',
                    ],
                ],
                'title_field' => '{{{ element_select.charAt(0).toUpperCase() + element_select.slice(1) }}}',
            ]
        );

        $this->end_controls_section();


        $this->add_section_slider();


        $this->start_controls_section(
            'section_image_overlay',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Media Overlay', 'king-addons'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
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
                'default' => 'fade-out',
            ]
        );


        Core::renderUpgradeProNotice($this, Controls_Manager::RAW_HTML, 'magazine-grid', 'overlay_animation', ['pro-slrt', 'pro-slxrt', 'pro-slbt', 'pro-sllt', 'pro-sltp', 'pro-slxlt', 'pro-sktp', 'pro-skrt', 'pro-skbt', 'pro-sklt', 'pro-scup', 'pro-scdn', 'pro-rllt', 'pro-rlrt']);

        $this->add_control(
            'overlay_animation_duration',
            [
                'label' => esc_html__('Animation Duration', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'default' => 0.7,
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


        Core::renderUpgradeProNotice($this, Controls_Manager::RAW_HTML, 'magazine-grid', 'overlay_animation_timing', ['pro-eio', 'pro-eiqd', 'pro-eicb', 'pro-eiqrt', 'pro-eiqnt', 'pro-eisn', 'pro-eiex', 'pro-eicr', 'pro-eibk', 'pro-eoqd', 'pro-eocb', 'pro-eoqrt', 'pro-eoqnt', 'pro-eosn', 'pro-eoex', 'pro-eocr', 'pro-eobk', 'pro-eioqd', 'pro-eiocb', 'pro-eioqrt', 'pro-eioqnt', 'pro-eiosn', 'pro-eioex', 'pro-eiocr', 'pro-eiobk',]);

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
                'default' => 'medium',
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
                'separator' => 'after',
                'condition' => [
                    'overlay_animation!' => 'none',
                ],
            ]
        );

        $this->add_control_overlay_animation_divider();

        $this->add_control_overlay_image();

        $this->add_control_overlay_image_width();

        $this->end_controls_section();

        Core::renderProFeaturesSection($this, '', Controls_Manager::RAW_HTML, 'magazine-grid', [
            'Magazine Grid Slider',
            '+6 Magazine Grid Layouts',
            'Magazine Grid Slider Autoplay Options',
            'Magazine Grid Slider Advanced Navigation Positioning',
            'Magazine Grid Slider Advanced Pagination Positioning',
            'Random Posts Query',
            'Posts Order',
            'Trim Title & Excerpt By Letter Count',
            'Custom Post Types Support',
            'Custom Fields Support',
            'Advanced Grid Elements Positioning',
            'Advanced Post Likes',
            'Advanced Post Sharing',
            'Unlimited Image Overlay Animations',
            'Image Overlay GIF Upload Option',
            'Title, Category, Read More Advanced Link Hover Animations',
            'Open Links in New Tab'
        ]);


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

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'overlay_color',
                'label' => esc_html__('Background', 'king-addons'),
                'types' => ['classic', 'gradient'],
                'fields_options' => [
                    'background' => [
                        'default' => 'gradient',
                    ],
                    'color' => [
                        'default' => 'rgba(255, 255, 255, 0)',
                    ],
                    'color_stop' => [
                        'default' => [
                            'unit' => '%',
                            'size' => 0,
                        ]
                    ],
                    'color_b' => [
                        'default' => 'rgba(0, 0, 0, 1)',
                    ]
                ],
                'selector' => '{{WRAPPER}} .king-addons-grid-media-hover-bg'
            ]
        );

        $this->add_control(
            'overlay_border_color',
            [
                'label' => esc_html__('Border Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#E8E8E8',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-grid-media-hover-bg' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'overlay_border_type',
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
                    '{{WRAPPER}} .king-addons-grid-media-hover-bg' => 'border-style: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'overlay_border_width',
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
                    '{{WRAPPER}} .king-addons-grid-media-hover-bg' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'overlay_border_type!' => 'none',
                ],
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
                'default' => '#FFFFFF',
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
                'default' => 0.1,
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

        $this->add_responsive_control(
            'title_featured_box_size',
            [
                'type' => Controls_Manager::SLIDER,
                'label' => esc_html__('Featured Box Title Font Size', 'king-addons'),
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 38,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-magazine-grid-add-1-2 article:nth-child(1) .king-addons-grid-item-title a' => 'font-size: {{SIZE}}px;',
                    '{{WRAPPER}} .king-addons-magazine-grid-add-1-3 article:nth-child(1) .king-addons-grid-item-title a' => 'font-size: {{SIZE}}px;',
                    '{{WRAPPER}} .king-addons-magazine-grid-add-1-4 article:nth-child(1) .king-addons-grid-item-title a' => 'font-size: {{SIZE}}px;',
                    '{{WRAPPER}} .king-addons-magazine-grid-add-1-1-2 article:nth-child(1) .king-addons-grid-item-title a' => 'font-size: {{SIZE}}px;',
                    '{{WRAPPER}} .king-addons-magazine-grid-add-2-1-2 article:nth-child(2) .king-addons-grid-item-title a' => 'font-size: {{SIZE}}px;',
                    '{{WRAPPER}} .king-addons-magazine-grid-add-1vh-3h article:nth-child(1) .king-addons-grid-item-title a' => 'font-size: {{SIZE}}px;',
                    '{{WRAPPER}} .king-addons-magazine-grid-add-1-1-1 article:nth-child(2) .king-addons-grid-item-title a' => 'font-size: {{SIZE}}px;',
                ],
                'condition' => [
                    'king_addons_grid_layout_select' => ['1-2', '1-3', '1-4', '1-1-2', '2-1-2', '1vh-3h', '1-1-1']
                ]
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
                    'bottom' => 5,
                    'left' => 20,
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
                'default' => '#ffffff',
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
                'default' => '#ffffff',
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
                'default' => '#ffffff',
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
                'default' => '#ffffff',
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
                    'top' => 2,
                    'right' => 0,
                    'bottom' => 20,
                    'left' => 22,
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
                'default' => '#ffffff',
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
                'default' => '#ffffff',
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
                'default' => '#ffffff',
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
                    'size' => 10,
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
                    'top' => 1,
                    'right' => 10,
                    'bottom' => 0,
                    'left' => 22,
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
                'default' => '#ffffff',
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
                'default' => '#ffffff',
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
                'default' => '#ffffff',
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
                'default' => '#5B03FF',
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
                    'top' => 2,
                    'right' => 2,
                    'bottom' => 2,
                    'left' => 2,
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
                'default' => '#ffffff',
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
                    'unit' => 'px',
                    'size' => 10,
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
                    'size' => 1,
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
                    'top' => 13,
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
                'default' => '#ffffff',
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
                    'top' => 13,
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
                    'left' => 22,
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
                    'top' => 2,
                    'right' => 5,
                    'bottom' => 2,
                    'left' => 5,
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
                    'left' => 20,
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
                    'top' => 2,
                    'right' => 2,
                    'bottom' => 2,
                    'left' => 2,
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


        $this->add_section_style_grid_slider_nav();


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
                'default' => '#9c9c9c',
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

    }

    public function get_related_taxonomies()
    {
        $relations = [];
        foreach (Core::getCustomTypes('post', false) as $slug => $title) {
            // `get_object_taxonomies` already returns an array; assign it directly
            $relations[$slug] = get_object_taxonomies($slug);
        }
        return json_encode($relations);
    }

    public function get_max_num_pages()
    {
        $query = new WP_Query($this->get_main_query_args(0));
        wp_reset_postdata();
        return (int)ceil($query->max_num_pages);
    }

    public function get_main_query_args($slide_offset)
    {
        $settings = $this->get_settings();
        $author = !empty($settings['query_author']) ? implode(',', $settings['query_author']) : '';
        $paged = get_query_var('paged') ?: (get_query_var('page') ?: 1);

        // Handle no-premium settings
        if (!king_addons_freemius()->can_use_premium_code__premium_only()) {
            $restricted_layouts = ['1-2', '1-3', '1-4', '1-1-2', '2-1-2', '1vh-3h'];
            if (in_array($settings['king_addons_grid_layout_select'], $restricted_layouts, true)) {
                $settings['king_addons_grid_layout_select'] = '1-1-3';
            }
            $settings['slider_enable'] = '';
            $settings['query_randomize'] = '';
            $settings['order_posts'] = 'date';
        }

        // Determine posts per page based on layout
        switch ($settings['king_addons_grid_layout_select']) {
            case '1-2':
            case '1-1-1':
                $query_posts_per_page = 3;
                break;
            case '1-3':
            case '1-1-2':
            case '1vh-3h':
                $query_posts_per_page = 4;
                break;
            case '1-4':
            case '2-1-2':
            case '1-1-3':
            case '2-3':
                $query_posts_per_page = 5;
                break;
            case '2-h':
                $query_posts_per_page = 2 * (int)$settings['layout_rows_number'];
                break;
            case '3-h':
                $query_posts_per_page = 3 * (int)$settings['layout_rows_number'];
                break;
            case '4-h':
                $query_posts_per_page = 4 * (int)$settings['layout_rows_number'];
                break;
            default:
                $query_posts_per_page = 3; // Fallback if something else
                break;
        }

        $settings['query_offset'] = !empty($settings['query_offset']) ? $settings['query_offset'] : 0;
        $offset = ($paged - 1) * $query_posts_per_page + $settings['query_offset'];
        $query_order_by = (!empty($settings['query_randomize'])) ? $settings['query_randomize'] : $settings['order_posts'];

        // Override random on front page if not a blog archive
        if (is_front_page() && !Core::isBlogArchive()) {
            $query_order_by = $settings['order_posts'];
        }

        // Adjust offset for slider
        if ('yes' === $settings['slider_enable']) {
            $offset += $query_posts_per_page * $slide_offset;
        }

        // Default arguments
        $args = [
            'post_type' => $settings['query_source'],
            'tax_query' => $this->get_tax_query_args(),
            'post__not_in' => $settings['query_exclude_' . $settings['query_source']],
            'posts_per_page' => $query_posts_per_page,
            'orderby' => $query_order_by,
            'author' => $author,
            'paged' => $paged,
            'offset' => $offset,
        ];

        // Exclude posts with no images
        if ('yes' === $settings['query_exclude_no_images']) {
            $args['meta_key'] = '_thumbnail_id';
        }

        // Manual selection override
        if ('manual' === $settings['query_selection']) {
            $post_ids = !empty($settings['query_manual_' . $settings['query_source']])
                ? $settings['query_manual_' . $settings['query_source']]
                : [''];

            $args = [
                'post_type' => $settings['query_source'],
                'post__in' => $post_ids,
                'orderby' => $query_order_by,
                'posts_per_page' => $query_posts_per_page,
                'offset' => $query_posts_per_page * $slide_offset,
            ];
        }

        // Use current query
        if ('current' === $settings['query_source']) {
            global $wp_query;
            $args = $wp_query->query_vars;
            $args['posts_per_page'] = $query_posts_per_page;
            $args['orderby'] = $query_order_by;
            $args['offset'] = ($paged - 1) * $query_posts_per_page + (int)$settings['query_offset'];
        }

        // Related posts
        if ('related' === $settings['query_source']) {
            $args = [
                'post_type' => get_post_type(get_the_ID()),
                'tax_query' => $this->get_tax_query_args(),
                'post__not_in' => [get_the_ID()],
                'ignore_sticky_posts' => 1,
                'posts_per_page' => $query_posts_per_page,
                'orderby' => $query_order_by,
                'offset' => $offset,
            ];
        }

        // Add 'order' unless random
        if ('rand' !== $query_order_by) {
            $args['order'] = $settings['order_direction'];
        }

        return $args;
    }

    public function get_tax_query_args()
    {
        $settings = $this->get_settings();

        // If "related" source
        if ('related' === $settings['query_source']) {
            return [
                [
                    'taxonomy' => $settings['query_tax_selection'],
                    'field' => 'term_id',
                    'terms' => wp_get_object_terms(
                        get_the_ID(),
                        $settings['query_tax_selection'],
                        ['fields' => 'ids']
                    ),
                ]
            ];
        }

        // Else build from normal taxonomies
        $tax_query = [];
        foreach (get_object_taxonomies($settings['query_source']) as $tax) {
            $key = 'query_taxonomy_' . $tax;
            if (!empty($settings[$key])) {
                $tax_query[] = [
                    'taxonomy' => $tax,
                    'field' => 'id',
                    'terms' => $settings[$key],
                ];
            }
        }
        return $tax_query;
    }

    private function get_animation_class($data, $object)
    {
        if ('none' === $data[$object . '_animation']) {
            return '';
        }

        $class = ' king-addons-' . $object . '-' . $data[$object . '_animation'];
        $class .= ' king-addons-anim-size-' . $data[$object . '_animation_size'];
        $class .= ' king-addons-animation-timing-' . $data[$object . '_animation_timing'];

        if ('yes' === $data[$object . '_animation_tr']) {
            $class .= ' king-addons-anim-transparency';
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
            $output .= '<input type="password" name="post_password" id="post-' . esc_attr(get_the_ID()) . '" placeholder="' . esc_html__('Type and hit Enter...', 'king-addons') . '">';
            $output .= '</form>';
            return $output;
        });

        echo '<div class="king-addons-grid-item-protected king-addons-cv-container">';
        echo '<div class="king-addons-cv-outer">';
        echo '<div class="king-addons-cv-inner">';
        echo get_the_password_form();
        echo '</div></div></div>';
    }

    public function render_post_thumbnail($settings, $post_id = null)
    {
        $id = $post_id ? get_post_thumbnail_id($post_id) : get_post_thumbnail_id();
        $src = Group_Control_Image_Size::get_attachment_image_src($id, 'layout_image_crop', $settings);

        if (has_post_thumbnail()) {
            echo '<div class="king-addons-grid-image-wrap" data-src="' . esc_url($src) . '" style="background-image: url(' . esc_url($src) . ')"></div>';
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
        $open_links_in_new_tab = ('yes' === $settings['open_links_in_new_tab']) ? '_blank' : '_self';

        // If premium unavailable, fallback pointer settings
        $title_pointer = king_addons_freemius()->can_use_premium_code__premium_only() ? $settings['title_pointer'] : 'none';
        $title_pointer_animation = king_addons_freemius()->can_use_premium_code__premium_only() ? $settings['title_pointer_animation'] : 'fade';

        $pointer_item_class = ('none' !== $title_pointer) ? 'class="king-addons-pointer-item"' : '';
        $class .= ' king-addons-pointer-' . $title_pointer;
        $class .= ' king-addons-pointer-line-fx king-addons-pointer-fx-' . $title_pointer_animation;

        $tags_whitelist = ['h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'div', 'span', 'p'];
        $element_title_tag = Core::validateHTMLTags($settings['element_title_tag'], 'h2', $tags_whitelist);
        $trim_type = $settings['element_trim_text_by'];
        $limit = $trim_type === 'word_count' ? $settings['element_word_count'] : $settings['element_letter_count'];
        $title_text = ($trim_type === 'word_count')
            ? wp_trim_words(get_the_title(), $limit)
            : mb_substr(html_entity_decode(get_the_title()), 0, $limit) . '...';

        echo "<$element_title_tag class=\"$class\">";
        echo '<div class="inner-block">';
        echo "<a target=\"$open_links_in_new_tab\" $pointer_item_class href=\"" . esc_url(get_the_permalink()) . "\">";
        echo esc_html($title_text);
        echo '</a></div>';
        echo "</$element_title_tag>";
    }

    public function render_post_content($settings, $class)
    {
        if ('' === get_the_content()) {
            return;
        }
        $class .= ('yes' === $settings['element_dropcap']) ? ' king-addons-enable-dropcap' : '';

        echo "<div class=\"$class\">";
        echo '<div class="inner-block">';
        echo wp_kses_post(get_the_content());
        echo '</div></div>';
    }

    public function render_post_excerpt($settings, $class)
    {
        if ('' === get_the_excerpt()) {
            return;
        }
        $class .= ('yes' === $settings['element_dropcap']) ? ' king-addons-enable-dropcap' : '';

        echo "<div class=\"$class\">";
        echo '<div class="inner-block">';
        if ('word_count' === $settings['element_trim_text_by']) {
            echo '<p>' . esc_html(wp_trim_words(get_the_excerpt(), $settings['element_word_count'])) . '</p>';
        } else {
            $trimmed = implode('', array_slice(str_split(get_the_excerpt()), 0, $settings['element_letter_count']));
            echo '<p>' . esc_html($trimmed) . '...</p>';
        }
        echo '</div></div>';
    }

    public function render_post_date($settings, $class)
    {
        echo "<div class=\"$class\"><div class=\"inner-block\"><span>";
        $this->render_extra_text_and_icon($settings, 'before');

        // Show modified time or published time
        echo ('yes' === $settings['show_last_update_date'])
            ? esc_html(get_the_modified_time(get_option('date_format')))
            : esc_html(apply_filters('the_date', get_the_date(), get_option('date_format'), '', ''));

        $this->render_extra_text_and_icon($settings, 'after');
        echo '</span></div></div>';
    }

    public function render_post_time($settings, $class)
    {
        echo "<div class=\"$class\"><div class=\"inner-block\"><span>";
        $this->render_extra_text_and_icon($settings, 'before');

        echo esc_html(get_the_time());

        $this->render_extra_text_and_icon($settings, 'after');
        echo '</span></div></div>';
    }

    public function render_post_author($settings, $class)
    {
        $author_id = get_post_field('post_author');

        echo "<div class=\"$class\"><div class=\"inner-block\">";
        $this->render_extra_text_and_icon($settings, 'before');

        echo '<a href="' . esc_url(get_author_posts_url($author_id)) . '">';
        $this->render_extra_text_and_icon($settings, 'before', true);

        if ('yes' === $settings['element_show_avatar']) {
            echo get_avatar($author_id, $settings['element_avatar_size']);
        }
        echo '<span>' . esc_html(get_the_author_meta('display_name', $author_id)) . '</span>';
        $this->render_extra_text_and_icon($settings, 'after', true);
        echo '</a>';

        $this->render_extra_text_and_icon($settings, 'after');
        echo '</div></div>';
    }

    public function render_post_comments($settings, $class)
    {
        if (!comments_open()) {
            return;
        }
        $count = get_comments_number();
        if ($count === 1) {
            $text = $count . ' ' . $settings['element_comments_text_2'];
        } elseif ($count > 1) {
            $text = $count . ' ' . $settings['element_comments_text_3'];
        } else {
            $text = $settings['element_comments_text_1'];
        }

        echo "<div class=\"$class\"><div class=\"inner-block\">";
        $this->render_extra_text_and_icon($settings, 'before');

        echo '<a href="' . esc_url(get_comments_link()) . '">';
        $this->render_extra_text_and_icon($settings, 'before', true);
        echo '<span>' . esc_html($text) . '</span>';
        $this->render_extra_text_and_icon($settings, 'after', true);
        echo '</a>';

        $this->render_extra_text_and_icon($settings, 'after');
        echo '</div></div>';
    }

    public function render_post_read_more($settings, $class)
    {
        $open_links_in_new_tab = ('yes' === $settings['open_links_in_new_tab']) ? '_blank' : '_self';
        $read_more_animation = king_addons_freemius()->can_use_premium_code__premium_only()
            ? $settings['read_more_animation']
            : 'king-addons-button-none';

        echo "<div class=\"$class\"><div class=\"inner-block\">";
        echo "<a target=\"$open_links_in_new_tab\" href=\"" . esc_url(get_the_permalink()) . "\" class=\"king-addons-button-effect " . esc_attr($read_more_animation) . "\">";
        $this->render_extra_text_and_icon($settings, 'before', true);
        echo '<span>' . esc_html($settings['element_read_more_text']) . '</span>';
        $this->render_extra_text_and_icon($settings, 'after', true);
        echo '</a>';
        echo '</div></div>';
    }

    public function render_post_likes($settings, $class, $post_id)
    {
        // Intentionally empty; placeholder for likes
    }

    public function render_post_sharing_icons($settings, $class)
    {
        // Intentionally empty; placeholder for sharing icons
    }

    public function render_post_custom_field($settings, $class, $post_id)
    {
        // Intentionally empty; placeholder for custom fields
    }

    public function render_post_element_separator($settings, $class)
    {
        echo "<div class=\"$class {$settings['element_separator_style']}\">";
        echo '<div class="inner-block"><span></span></div>';
        echo '</div>';
    }

    public function render_post_taxonomies($settings, $class, $post_id)
    {
        $terms = wp_get_post_terms($post_id, $settings['element_select']);
        $count = 0;
        $pointer = king_addons_freemius()->can_use_premium_code__premium_only();

        // Pointer settings
        $tax1_pointer = $pointer ? $this->get_settings()['tax1_pointer'] : 'none';
        $tax1_pointer_animation = $pointer ? $this->get_settings()['tax1_pointer_animation'] : 'fade';
        $tax2_pointer = $pointer ? $this->get_settings()['tax2_pointer'] : 'none';
        $tax2_pointer_animation = $pointer ? $this->get_settings()['tax2_pointer_animation'] : 'fade';

        if ($settings['element_tax_style'] === 'king-addons-grid-tax-style-1') {
            $class .= " king-addons-pointer-$tax1_pointer king-addons-pointer-fx-$tax1_pointer_animation";
        } else {
            $class .= " king-addons-pointer-$tax2_pointer king-addons-pointer-fx-$tax2_pointer_animation";
        }
        $class .= ' king-addons-pointer-line-fx';

        $pointer_item_class = (
            (isset($this->get_settings()['tax1_pointer']) && $this->get_settings()['tax1_pointer'] !== 'none') ||
            (isset($this->get_settings()['tax2_pointer']) && $this->get_settings()['tax2_pointer'] !== 'none')
        ) ? 'king-addons-pointer-item' : '';

        echo "<div class=\"$class {$settings['element_tax_style']}\">";
        echo '<div class="inner-block">';

        $this->render_extra_text_and_icon($settings, 'before');

        foreach ($terms as $term) {
            // Check if premium color styling
            $enable_custom_colors = $pointer ? $this->get_settings()['tax1_custom_color_switcher'] : '';
            if ('yes' === $enable_custom_colors) {
                $cfc_text = get_term_meta($term->term_id, $this->get_settings()['tax1_custom_color_field_text'], true);
                $cfc_bg = get_term_meta($term->term_id, $this->get_settings()['tax1_custom_color_field_bg'], true);

                if ($cfc_text || $cfc_bg) {
                    $style_block = "color:$cfc_text; background-color:$cfc_bg; border-color:$cfc_bg;";
                    $css_selector = '.elementor-element' . $this->get_unique_selector() . " .king-addons-grid-tax-style-1 .inner-block a.king-addons-tax-id-$term->term_id";
                    echo "<style>$css_selector{{$style_block}}</style>";
                }
            }

            echo "<a class=\"$pointer_item_class king-addons-tax-id-$term->term_id\" href=\"" . esc_url(get_term_link($term->term_id)) . "\">";
            echo esc_html($term->name);
            if (++$count !== count($terms)) {
                echo '<span class="tax-sep">' . esc_html($settings['element_tax_sep']) . '</span>';
            }
            echo '</a>';
        }

        $this->render_extra_text_and_icon($settings, 'after');

        echo '</div></div>';
    }

    /**
     * Helper function to render extra text/icon before or after
     *
     * @param array $settings
     * @param string $position 'before'|'after'
     * @param bool $iconOnly Render icon only if true
     * @noinspection PhpMissingParamTypeInspection
     */
    private function render_extra_text_and_icon($settings, $position, $iconOnly = false)
    {
        if ($iconOnly) {
            // Render icon
            if ($position === $settings['element_extra_icon_pos']) {
                ob_start();
                Icons_Manager::render_icon($settings['element_extra_icon'], ['aria-hidden' => 'true']);
                $extra_icon = ob_get_clean();

                echo '<span class="king-addons-grid-extra-icon-' . esc_attr($position) . '">';
                echo $extra_icon;
                echo '</span>';
            }
        } else {
            // Render text
            if ($position === $settings['element_extra_text_pos']) {
                echo '<span class="king-addons-grid-extra-text-' . esc_attr($position) . '">';
                echo esc_html($settings['element_extra_text']);
                echo '</span>';
            }
        }
    }

    public function get_elements($type, $settings, $class, $post_id)
    {
        // Fallback for any pro-only items
        if (in_array($type, ['pro-lk', 'pro-shr', 'pro-cf'], true)) {
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

        // Build the elements array by location
        foreach ($settings['grid_elements'] as $data) {
            // Force it to 'over' in original code
            $place = 'over';
            $align_vr = king_addons_freemius()->can_use_premium_code__premium_only()
                ? $data['element_align_vr']
                : 'bottom';

            if (!isset($locations[$place])) {
                $locations[$place] = [];
            }
            /** @noinspection PhpConditionAlreadyCheckedInspection */
            if ('over' === $place) {
                if (!isset($locations[$place][$align_vr])) {
                    $locations[$place][$align_vr] = [];
                }
                $locations[$place][$align_vr][] = $data;
            } else {
                $locations[$place][] = $data;
            }
        }

        // Render elements in the correct location
        if (empty($locations[$location])) {
            return;
        }

        if ('over' === $location) {
            foreach ($locations[$location] as $align => $elements) {
                if ('middle' === $align) {
                    echo '<div class="king-addons-cv-container"><div class="king-addons-cv-outer"><div class="king-addons-cv-inner">';
                }
                echo '<div class="king-addons-grid-media-hover-' . esc_attr($align) . ' elementor-clearfix">';
                foreach ($elements as $data) {
                    $class = 'king-addons-grid-item-' . $data['element_select'] .
                        ' elementor-repeater-item-' . $data['_id'] .
                        ' king-addons-grid-item-display-' . $data['element_display'] .
                        ' king-addons-grid-item-align-' . $data['element_align_hr'] .
                        $this->get_animation_class($data, 'element');
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
                $class = 'king-addons-grid-item-' . $data['element_select'] .
                    ' elementor-repeater-item-' . $data['_id'] .
                    ' king-addons-grid-item-display-' . $data['element_display'] .
                    ' king-addons-grid-item-align-' . $data['element_align_hr'];
                $this->get_elements($data['element_select'], $data, $class, $post_id);
            }
            echo '</div>';
        }
    }

    public function add_slider_settings($settings)
    {
        $slider_is_rtl = is_rtl();
        $slider_direction = $slider_is_rtl ? 'rtl' : 'ltr';

        $slider_options = [
            'rtl' => $slider_is_rtl,
            'slidesToShow' => 1,
            'infinite' => ('yes' === $settings['slider_loop']),
            'speed' => absint($settings['slider_effect_duration'] * 1000),
            'arrows' => true,
            'autoplay' => ('yes' === $settings['slider_autoplay']),
            'autoplaySpeed' => absint($settings['slider_autoplay_duration'] * 1000),
            'pauseOnHover' => $settings['slider_pause_on_hover'],
            'prevArrow' => '#king-addons-grid-slider-prev-' . $this->get_id(),
            'nextArrow' => '#king-addons-grid-slider-next-' . $this->get_id(),
        ];

        $this->add_render_attribute('slider-settings', [
            'dir' => esc_attr($slider_direction),
            'data-slick' => wp_json_encode($slider_options),
        ]);
    }

    public function render_magazine_grid($settings, $slide_offset)
    {
        $posts = new WP_Query($this->get_main_query_args($slide_offset));

        // Check premium fallback for layout
        if (!king_addons_freemius()->can_use_premium_code__premium_only()) {
            $restricted_layouts = ['1-2', '1-3', '1-4', '1-1-2', '2-1-2', '1vh-3h'];
            if (in_array($settings['king_addons_grid_layout_select'], $restricted_layouts, true)) {
                $settings['king_addons_grid_layout_select'] = '1-1-3';
            }
        }

        echo '<section class="king-addons-magazine-grid king-addons-magazine-grid-add-' . esc_attr($settings['king_addons_grid_layout_select']) .
            ' king-addons-magazine-grid-add-rows-' . esc_attr($settings['layout_rows_number']) . '">';

        if ($posts->have_posts()) :
            // Randomize if needed
            if ($settings['query_randomize'] === 'rand' && is_front_page() && !Core::isBlogArchive()) {
                shuffle($posts->posts);
            }

            while ($posts->have_posts()) :
                $posts->the_post();
                $post_class = implode(' ', get_post_class('king-addons-magazine-grid-item elementor-clearfix', get_the_ID()));

                echo "<article class=\"$post_class\">";
                $this->render_password_protected_input();

                echo '<div class="king-addons-grid-item-inner">';
                echo '<div class="king-addons-grid-media-wrap" data-overlay-link="' . esc_attr($settings['overlay_post_link']) . '">';

                $this->render_post_thumbnail($settings, get_the_ID());

                echo '<div class="king-addons-grid-media-hover king-addons-animation-wrap">';
                $this->render_media_overlay($settings);
                $this->get_elements_by_location('over', $settings, get_the_ID());
                echo '</div></div></div></article>';

            endwhile;
            wp_reset_postdata();
        else:
            echo '<h2>' . esc_html($settings['query_not_found_text']) . '</h2>';
        endif;

        echo '</section>';
    }

    protected function render()
    {
        $settings = $this->get_settings();
        $render_attribute = '';

        // If no premium, reset slider options
        if (!king_addons_freemius()->can_use_premium_code__premium_only()) {
            $settings['slider_enable'] = '';
            $settings['slider_effect'] = '';
        }

        // Add slider settings if enabled
        if ('yes' === $settings['slider_enable']) {
            $this->add_slider_settings($settings);
            $render_attribute = $this->get_render_attribute_string('slider-settings');
        }

        echo '<div class="king-addons-magazine-grid-wrap" ' . $render_attribute .
            ' data-slide-effect="' . esc_attr($settings['slider_effect']) . '">';

        if ('yes' === $settings['slider_enable']) {
            for ($i = 0; $i < $settings['slider_amount']; $i++) {
                echo '<div class="king-addons-magazine-slide">';
                $this->render_magazine_grid($settings, $i);
                echo '</div>';
            }
        } else {
            $this->render_magazine_grid($settings, 0);
        }

        echo '</div>';

        // Slider arrows
        if ('yes' === $settings['slider_enable']) {
            echo '<div class="king-addons-grid-slider-arrow-container">';
            echo '<div class="king-addons-grid-slider-prev-arrow king-addons-grid-slider-arrow" id="king-addons-grid-slider-prev-' . esc_html($this->get_id()) . '">';
            echo Core::getIcon($settings['slider_nav_icon'], '');
            echo '</div>';
            echo '<div class="king-addons-grid-slider-next-arrow king-addons-grid-slider-arrow" id="king-addons-grid-slider-next-' . esc_html($this->get_id()) . '">';
            echo Core::getIcon($settings['slider_nav_icon'], '');
            echo '</div>';
            echo '</div>';
        }
    }
}