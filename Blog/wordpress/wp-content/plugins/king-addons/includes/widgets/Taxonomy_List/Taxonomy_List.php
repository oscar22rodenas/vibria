<?php

namespace King_Addons;

use Elementor\Icons_Manager;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class Taxonomy_List extends Widget_Base
{

    public function get_name(): string
    {
        return 'king-addons-taxonomy-list';
    }

    public function get_title(): string
    {
        return esc_html__('Taxonomy List', 'king-addons');
    }

    public function get_icon(): string
    {
        return 'king-addons-icon king-addons-taxonomy-list';
    }

    public function get_style_depends(): array
    {
        return [KING_ADDONS_ASSETS_UNIQUE_KEY . '-taxonomy-list-style'];
    }

    public function get_categories(): array
    {
        return ['king-addons'];
    }

    public function get_keywords(): array
    {
        return ['click', 'target', 'point', 'builder', 'link', 'point', 'points', 'king', 'addons', 'kingaddons',
            'king-addons', 'heading', 'header', 'paragraph', 'section', 'heading', 'title', 'subtitle',
            'taxonomy-list', 'taxonomy', 'category', 'categories', 'tag', 'list', 'tax', 'custom', 'post', 'types',
            'custom post types', 'taxonomies', 'sub', 'sub child'];
    }

    public function get_custom_help_url(): string
    {
        return 'https://kingaddons.com/elementor/taxonomy-list/';
    }

    public function add_section_style_toggle_icon()
    {
    }

    public function get_post_taxonomies(): array
    {
        $post_taxonomies = [
            'category' => esc_html__('Categories', 'king-addons'),
            'post_tag' => esc_html__('Tags', 'king-addons'),
            'product_cat' => esc_html__('Product Categories', 'king-addons'),
            'product_tag' => esc_html__('Product Tags', 'king-addons'),
        ];

        foreach (Core::getCustomTypes('tax') as $slug => $title) {
            if (!in_array($slug, ['product_tag', 'product_cat'])) {
                $post_taxonomies['pro-' . substr($slug, 0, 2)] = esc_html($title) . ' (Pro)';
            }
        }

        return $post_taxonomies;
    }

    public function add_controls_group_sub_category_filters()
    {
        $this->add_control(
            'show_sub_categories',
            [
                'label' => sprintf(__('Show Sub Categories %s', 'king-addons'), '<i class="eicon-pro-icon"></i>'),
                'type' => Controls_Manager::SWITCHER,
                'classes' => 'king-addons-pro-control',
            ]
        );

        $this->add_control(
            'show_sub_children',
            [
                'label' => sprintf(__('Show Sub Child %s', 'king-addons'), '<i class="eicon-pro-icon"></i>'),
                'type' => Controls_Manager::SWITCHER,
                'classes' => 'king-addons-pro-control',
            ]
        );

        $this->add_control(
            'show_sub_categories_on_click',
            [
                'label' => sprintf(__('Show Child on Click %s', 'king-addons'), '<i class="eicon-pro-icon"></i>'),
                'type' => Controls_Manager::SWITCHER,
                'label_block' => false,
                'classes' => 'king-addons-pro-control',
            ]
        );
    }

    protected function register_controls()
    {
        $this->start_controls_section(
            'section_taxonomy_list_general',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('General', 'king-addons'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'query_heading',
            [
                'label' => esc_html__('Query', 'king-addons'),
                'type' => Controls_Manager::HEADING,
            ]
        );

        $this->add_control(
            'query_tax_selection',
            [
                'label' => esc_html__('Select Taxonomy', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'category',
                'options' => $this->get_post_taxonomies(),
            ]
        );

        if (!king_addons_freemius()->can_use_premium_code__premium_only()) {
            $this->add_control(
                'query_tax_selection_pro_notice',
                [
                    'raw' => 'This option is available<br> in the <strong><a href="https://kingaddons.com/pricing/?utm_source=kng-module-taxonomy-list-settings-upgrade-pro&utm_medium=plugin&utm_campaign=kng" target="_blank">Pro version</a></strong>',
                    'type' => Controls_Manager::RAW_HTML,
                    'content_classes' => 'king-addons-pro-notice',
                    'condition' => [
                        'query_tax_selection!' => ['category', 'post_tag', 'product_cat', 'product_tag'],
                    ]
                ]
            );
        }

        $this->add_control(
            'query_hide_empty',
            [
                'label' => esc_html__('Hide Empty', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default' => 'yes'
            ]
        );

        $this->add_controls_group_sub_category_filters();

        $this->add_control(
            'layout_heading',
            [
                'label' => esc_html__('Layout', 'king-addons'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'taxonomy_list_layout',
            [
                'label' => esc_html__('Select Layout', 'king-addons'),
                'type' => Controls_Manager::CHOOSE,
                'default' => 'vertical',
                'render_type' => 'template',
                'options' => [
                    'vertical' => [
                        'title' => esc_html__('Vertical', 'king-addons'),
                        'icon' => 'eicon-editor-list-ul',
                    ],
                    'horizontal' => [
                        'title' => esc_html__('Horizontal', 'king-addons'),
                        'icon' => 'eicon-ellipsis-h',
                    ],
                ],
                'prefix_class' => 'king-addons-tax-list-taxonomy-list-',
                'label_block' => false,
            ]
        );

        $this->add_control(
            'show_tax_list_icon',
            [
                'label' => esc_html__('Show Icon', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'label_block' => false,
                'default' => 'yes'
            ]
        );

        $this->add_control(
            'tax_list_icon',
            [
                'label' => esc_html__('Icon', 'king-addons'),
                'type' => Controls_Manager::ICONS,
                'skin' => 'inline',
                'label_block' => false,
                'exclude_inline_options' => 'svg',
                'condition' => [
                    'show_tax_list_icon' => 'yes'
                ]
            ]
        );

        $this->add_control(
            'show_tax_count',
            [
                'label' => esc_html__('Show Count', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'label_block' => false,
                'default' => 'yes'
            ]
        );

        $this->add_control(
            'show_count_brackets',
            [
                'label' => esc_html__('Count Brackets', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'label_block' => false,
                'default' => 'yes',
                'condition' => [
                    'show_tax_count' => 'yes'
                ]
            ]
        );

        $this->add_control(
            'disable_links',
            [
                'label' => esc_html__('Disable Links', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'label_block' => false,
                'default' => '',
                'separator' => 'before'
            ]
        );

        $this->add_control(
            'open_in_new_page',
            [
                'label' => esc_html__('Open in New Page', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'label_block' => false,
                'default' => 'yes',
                'condition' => [
                    'disable_links!' => 'yes'
                ]
            ]
        );

        $this->add_control(
            'highlight_active',
            [
                'label' => esc_html__('Highlight Active', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'label_block' => false,
                'default' => ''
            ]
        );

        $this->end_controls_section();

        Core::renderProFeaturesSection($this, '', Controls_Manager::RAW_HTML, 'taxonomy-list', [
            'Custom Post Type Taxonomies & Categories',
            'Show Sub Categories',
            'Show Sub Child',
            'Show Child On Click',
        ]);

        $this->start_controls_section(
            'section_style_tax',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Taxonomy Style', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            ]
        );

        $this->start_controls_tabs('tax_style');

        $this->start_controls_tab(
            'tax_normal',
            [
                'label' => esc_html__('Normal', 'king-addons'),
            ]
        );

        $this->add_control(
            'tax_color',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#5B03FF',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-tax-list-taxonomy-list li a' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .king-addons-tax-list-taxonomy-list li>span' => 'color: {{VALUE}}'
                ],
            ]
        );

        $this->add_control(
            'tax_bg_color',
            [
                'label' => esc_html__('Background Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-tax-list-taxonomy-list li a' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .king-addons-tax-list-taxonomy-list li>span' => 'background-color: {{VALUE}}'
                ]
            ]
        );

        $this->add_control(
            'tax_border_color',
            [
                'label' => esc_html__('Border Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#E8E8E8',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-tax-list-taxonomy-list li a' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .king-addons-tax-list-taxonomy-list li>span' => 'border-color: {{VALUE}}'
                ],
            ]
        );

        $this->add_control(
            'tax_transition_duration',
            [
                'label' => esc_html__('Transition Duration (ms)', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'default' => 300,
                'min' => 0,
                'step' => 10,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-tax-list-taxonomy-list li a' => 'transition-duration: {{VALUE}}ms',
                    '{{WRAPPER}} .king-addons-tax-list-taxonomy-list li>span' => 'transition-duration: {{VALUE}}ms'
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'tax_typography',
                'selector' => '{{WRAPPER}} .king-addons-tax-list-taxonomy-list li a, {{WRAPPER}} .king-addons-tax-list-taxonomy-list li>span',
                'fields_options' => [
                    'typography' => [
                        'default' => 'custom',
                    ],
                    'font_size' => [
                        'default' => [
                            'size' => '14',
                            'unit' => 'px',
                        ],
                    ]
                ]
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tax_hover',
            [
                'label' => esc_html__('Hover', 'king-addons'),
            ]
        );

        $this->add_control(
            'tax_color_hr',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-tax-list-taxonomy-list li a:hover' => 'color: {{VALUE}} !important',
                    '{{WRAPPER}} .king-addons-tax-list-taxonomy-list li>span:hover' => 'color: {{VALUE}} !important',
                    '{{WRAPPER}} .king-addons-tax-list-taxonomy-list li.king-addons-tax-list-taxonomy-active a' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .king-addons-tax-list-taxonomy-list li.king-addons-tax-list-taxonomy-active>span' => 'color: {{VALUE}}'
                ],
            ]
        );

        $this->add_control(
            'tax1_bg_color_hr',
            [
                'label' => esc_html__('Background Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-tax-list-taxonomy-list li a:hover' => 'background-color: {{VALUE}} !important',
                    '{{WRAPPER}} .king-addons-tax-list-taxonomy-list li>span:hover' => 'background-color: {{VALUE}} !important',
                    '{{WRAPPER}} .king-addons-tax-list-taxonomy-list li.king-addons-tax-list-taxonomy-active a' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .king-addons-tax-list-taxonomy-list li.king-addons-tax-list-taxonomy-active>span' => 'background-color: {{VALUE}}'
                ]
            ]
        );

        $this->add_control(
            'tax1_border_color_hr',
            [
                'label' => esc_html__('Border Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-tax-list-taxonomy-list li a:hover' => 'border-color: {{VALUE}} !important',
                    '{{WRAPPER}} .king-addons-tax-list-taxonomy-list li>span:hover' => 'border-color: {{VALUE}} !important',
                    '{{WRAPPER}} .king-addons-tax-list-taxonomy-list li.king-addons-tax-list-taxonomy-active a' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .king-addons-tax-list-taxonomy-list li.king-addons-tax-list-taxonomy-active>span' => 'border-color: {{VALUE}}'
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_responsive_control(
            'tax_padding',
            [
                'label' => esc_html__('Padding', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'default' => [
                    'top' => 5,
                    'right' => 0,
                    'bottom' => 5,
                    'left' => 0,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-tax-list-taxonomy-list li a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .king-addons-tax-list-taxonomy-list li>span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'tax_margin',
            [
                'label' => esc_html__('Margin', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'default' => [
                    'top' => 5,
                    'right' => 8,
                    'bottom' => 0,
                    'left' => 0,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-tax-list-taxonomy-list li' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'tax_border_type',
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
                    '{{WRAPPER}} .king-addons-tax-list-taxonomy-list li a' => 'border-style: {{VALUE}};',
                    '{{WRAPPER}} .king-addons-tax-list-taxonomy-list li>span' => 'border-style: {{VALUE}};'
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'tax_border_width',
            [
                'label' => esc_html__('Border Width', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'default' => [
                    'top' => 0,
                    'right' => 0,
                    'bottom' => 1,
                    'left' => 0,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-tax-list-taxonomy-list li a' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .king-addons-tax-list-taxonomy-list li>span' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
                'condition' => [
                    'tax_border_type!' => 'none',
                ],
            ]
        );

        $this->add_control(
            'tax_radius',
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
                    '{{WRAPPER}} .king-addons-tax-list-taxonomy-list li a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .king-addons-tax-list-taxonomy-list li>span' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ]
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_icon',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Icon', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_tax_list_icon' => 'yes'
                ]
            ]
        );

        $this->add_control(
            'icon_color',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#5B03FF',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-tax-list-taxonomy-list li i' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .king-addons-tax-list-taxonomy-list li svg' => 'fill: {{VALUE}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'icon_size',
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
                    'size' => 15,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-tax-list-taxonomy-list li i' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .king-addons-tax-list-taxonomy-list li svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
                'separator' => 'before'
            ]
        );

        $this->add_responsive_control(
            'icon_distance',
            [
                'label' => esc_html__('Space', 'king-addons'),
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
                    'size' => 5,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-tax-list-taxonomy-list li i:not(.king-addons-tax-list-tax-dropdown)' => 'margin-right: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->add_section_style_toggle_icon();
    }

    public function get_tax_wrapper_open_tag($settings, $term_id, $open_in_new_page)
    {
        if ('yes' == $settings['disable_links']) {
            echo '<span>';
        } else {
            echo '<a target="' . $open_in_new_page . '" href="' . esc_url(get_term_link($term_id)) . '">';
        }
    }

    public function get_tax_wrapper_close_tag($settings)
    {
        if ('yes' == $settings['disable_links']) {
            echo '</span>';
        } else {
            echo '</a>';
        }
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $open_in_new_page = $settings['open_in_new_page'] ? '_blank' : '_self';

        ob_start();
        Icons_Manager::render_icon($settings['tax_list_icon'], ['aria-hidden' => 'true']);
        $icon = ob_get_clean();
        $icon_wrapper = !empty($settings['tax_list_icon']) ? '<span>' . $icon . '</span>' : '';
        $brackets = $settings['show_count_brackets'] ?? '';

        $settings['query_tax_selection'] = str_contains($settings['query_tax_selection'], 'pro-') ? 'category' : $settings['query_tax_selection'];

        $terms = $this->get_terms_hierarchy($settings, $settings['query_tax_selection']);

        echo '<ul class="king-addons-tax-list-taxonomy-list" data-show-on-click="' . esc_attr($settings['show_sub_categories_on_click']) . '">';
        $this->render_terms($terms, $settings, $icon_wrapper, $brackets, $open_in_new_page);
        echo '</ul>';
    }

    private function get_terms_hierarchy($settings, $taxonomy, $parent = 0)
    {
        return get_terms($taxonomy, [
            'hide_empty' => 'yes' === $settings['query_hide_empty'],
            'parent' => $parent,
            'fields' => 'all'
        ]);
    }

    private function render_terms($terms, $settings, $icon_wrapper, $brackets, $open_in_new_page, $level = 0)
    {
        foreach ($terms as $term) {
            $class = $this->get_term_class($settings, $term, $level);
            $children = ('yes' === $settings['show_sub_categories']) ? $this->get_terms_hierarchy($settings, $settings['query_tax_selection'], $term->term_id) : [];

            echo '<li' . $class . ' data-term-id="' . esc_attr($term->term_id) . '">';
            $this->get_tax_wrapper_open_tag($settings, $term->term_id, $open_in_new_page);

            echo '<span class="king-addons-tax-list-tax-wrap">'
                . (!empty($children) && $settings['show_sub_categories_on_click'] === 'yes' ? '<i class="fas fa-caret-right king-addons-tax-list-tax-dropdown" aria-hidden="true"></i>' : '')
                . $icon_wrapper
                . '<span>' . esc_html($term->name) . '</span>'
                . '</span>'
                . ($settings['show_tax_count'] ? $this->render_term_count($term, $brackets) : '');

            $this->get_tax_wrapper_close_tag($settings);

            if (!empty($children)) {
                $this->render_terms($children, $settings, $icon_wrapper, $brackets, $open_in_new_page, $level + 1);
            }

            echo '</li>';
        }
    }

    private function get_term_class($settings, $term, $level): string
    {
        $is_active = isset(get_queried_object()->term_taxonomy_id) && $term->term_id == get_queried_object()->term_taxonomy_id && 'yes' === $settings['highlight_active'];
        $hidden_class = $settings['show_sub_categories_on_click'] === 'yes' ? ' king-addons-tax-list-sub-hidden' : '';

        $base_class = $level === 0 ? 'king-addons-tax-list-taxonomy' : "king-addons-tax-list-sub-taxonomy-$level";
        return ' class="' . $base_class . ($is_active ? ' king-addons-tax-list-taxonomy-active' : '') . $hidden_class . '"';
    }

    private function render_term_count($term, $brackets): string
    {
        $count = esc_html($term->count);
        return $brackets ? "<span><span class=\"king-addons-tax-list-term-count\">&nbsp;($count)</span></span>" : "<span><span class=\"king-addons-tax-list-term-count\">&nbsp;$count</span></span>";
    }
}