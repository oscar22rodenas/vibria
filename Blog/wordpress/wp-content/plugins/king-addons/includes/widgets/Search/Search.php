<?php

namespace King_Addons;

use Elementor\Controls_Manager;
use Elementor\Icons_Manager;
use Elementor\Widget_Base;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class Search extends Widget_Base
{
    public function get_name(): string
    {
        return 'king-addons-search';
    }

    public function get_title(): string
    {
        return esc_html__('Search (AJAX, live results, filters)', 'king-addons');
    }

    public function get_icon(): string
    {
        return 'king-addons-icon king-addons-search';
    }

    public function get_script_depends(): array
    {
        return [KING_ADDONS_ASSETS_UNIQUE_KEY . '-search-script'];
    }

    public function get_style_depends(): array
    {
        return [
                KING_ADDONS_ASSETS_UNIQUE_KEY . '-search-style',
                KING_ADDONS_ASSETS_UNIQUE_KEY . '-general-general',
        ];
    }

    public function get_categories(): array
    {
        return ['king-addons'];
    }

    public function get_keywords(): array
    {
        return ['search', 'search bar', 'bar', 'field', 'site', 'ajax', 'live', 'ajax search', 'live search', 'results',
            'king addons', 'king', 'addons', 'kingaddons', 'king-addons'];
    }

    public function get_custom_help_url(): string
    {
        return 'https://kingaddons.com/elementor/ajax-search/';
    }

    public function add_section_style_ajax(): void
    {
        $this->start_controls_section(
            'section_style_ajax',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Ajax (live results)', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'ajax_search' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'heading_list',
            [
                'label' => esc_html__('Search List', 'king-addons'),
                'type' => Controls_Manager::HEADING,
            ]
        );

        $this->add_control(
            'background_color',
            [
                'label' => esc_html__('Background Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#FFFFFF',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-data-fetch' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'background_color_hover',
            [
                'label' => esc_html__('Background Color (Hover)', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#F6F6F6',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-data-fetch ul li:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'ajax_box_shadow',
                'selector' => '{{WRAPPER}} .king-addons-data-fetch'
            ]
        );

        $this->add_control(
            'search_list_item_transition_duration',
            [
                'label' => esc_html__('Transition Duration (ms)', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'default' => 500,
                'min' => 0,
                'step' => 50,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-data-fetch ul li' => 'transition-duration: {{VALUE}}ms',
                ],
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
                'selectors_dictionary' => [
                    'left' => 'left: 0; right: auto;',
                    'center' => 'left: 50%; transform: translateX(-50%)',
                    'right' => 'right: 0; left: auto;'
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-data-fetch' => '{{VALUE}};',
                ],
                'separator' => 'before'
            ]
        );

        $this->add_responsive_control(
            'search_list_width',
            [
                'label' => esc_html__('Container Width', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'vw'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 2000,
                    ],
                    '%' => [
                        'min' => 50,
                        'max' => 200,
                    ],
                    'vw' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => '%',
                    'size' => 100,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-data-fetch' => 'width: {{SIZE}}{{UNIT}};',
                ],
                'separator' => 'before'
            ]
        );

        $this->add_responsive_control(
            'search_list_max_height',
            [
                'label' => esc_html__('Max Height', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'vh'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                    ],
                    'vh' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 350,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-data-fetch ul' => 'max-height: {{SIZE}}{{UNIT}};',
                ],
                'separator' => 'before'
            ]
        );

        $this->add_responsive_control(
            'search_list_top_distance',
            [
                'label' => esc_html__('Top Distance', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'vh'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                    'vh' => [
                        'min' => 0,
                        'max' => 10,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 5,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-data-fetch' => 'margin-top: {{SIZE}}{{UNIT}};',
                ]
            ]
        );

        $this->add_control(
            'list_item_title',
            [
                'label' => esc_html__('List Item', 'king-addons'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before'
            ]
        );

        $this->add_responsive_control(
            'search_list_item_bottom_distance',
            [
                'label' => esc_html__('Bottom Distance', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'vh'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                    'vh' => [
                        'min' => 0,
                        'max' => 10,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 5,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-data-fetch ul li:not(:last-child)' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ]
            ]
        );

        $this->add_responsive_control(
            'search_list_item_padding',
            [
                'label' => esc_html__('Padding', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'default' => [
                    'top' => 2,
                    'right' => 2,
                    'bottom' => 2,
                    'left' => 2,
                ],
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-data-fetch ul li' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->add_control(
            'heading_title',
            [
                'label' => esc_html__('Title', 'king-addons'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before'
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#222222',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-data-fetch a.king-addons-ajax-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .king-addons-data-fetch a.king-addons-ajax-title',
            ]
        );

        $this->add_responsive_control(
            'title_distance',
            [
                'label' => esc_html__('Distance', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ]
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 2,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-ajax-search-content a.king-addons-ajax-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ]
            ]
        );

        $this->add_control(
            'heading_description',
            [
                'label' => esc_html__('Description', 'king-addons'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before'
            ]
        );

        $this->add_control(
            'description_color',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#757575',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-data-fetch p a' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .king-addons-search-admin-notice' => 'color: {{VALUE}};'
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'description_typography',
                'selector' => '{{WRAPPER}} .king-addons-data-fetch p a, {{WRAPPER}} .king-addons-search-admin-notice',
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
                ],
            ]
        );

        $this->add_responsive_control(
            'description_distance',
            [
                'label' => esc_html__('Distance', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ]
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 2,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-ajax-search-content p.king-addons-ajax-desc' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ]
            ]
        );

        $this->add_control(
            'heading_image',
            [
                'label' => esc_html__('Image', 'king-addons'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'show_ajax_thumbnails' => 'yes'
                ]
            ]
        );

        $this->add_responsive_control(
            'image_width',
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
                    'size' => 150,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-data-fetch a.king-addons-ajax-img-wrap' => 'width: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .king-addons-data-fetch .king-addons-ajax-search-content' => 'width: calc(100% - {{SIZE}}{{UNIT}});',
                ],
                'condition' => [
                    'show_ajax_thumbnails' => 'yes'
                ]
            ]
        );

        $this->add_responsive_control(
            'image_distance',
            [
                'label' => esc_html__('Distance', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
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
                    '{{WRAPPER}} .king-addons-data-fetch a.king-addons-ajax-img-wrap' => 'margin-right: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'show_ajax_thumbnails' => 'yes'
                ]
            ]
        );

        $this->add_control(
            'view_result_text_heading',
            [
                'label' => esc_html__('View Result', 'king-addons'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before'
            ]
        );

        $this->add_control(
            'view_result_text_color',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#FFFFFF',
                'selectors' => [
                    '{{WRAPPER}} a.king-addons-view-result' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'view_result_text_color_hr',
            [
                'label' => esc_html__('Color (Hover)', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#FFFFFF',
                'selectors' => [
                    '{{WRAPPER}} a.king-addons-view-result:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'view_result_text_bg_color',
            [
                'label' => esc_html__('Background Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#5B03FF',
                'selectors' => [
                    '{{WRAPPER}} a.king-addons-view-result' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'view_result_text_bg_color_hr',
            [
                'label' => esc_html__('Background Color (Hover)', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#5B03FF',
                'selectors' => [
                    '{{WRAPPER}} a.king-addons-view-result:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'view_result_typography',
                'selector' => '{{WRAPPER}} a.king-addons-view-result',
                'fields_options' => [
                    'typography' => [
                        'default' => 'custom',
                    ],
                    'font_family' => [
                        'default' => 'Roboto',
                    ],
                    'font_size' => [
                        'default' => [
                            'size' => '14',
                            'unit' => 'px',
                        ]
                    ]
                ]
            ]
        );

        $this->add_control(
            'view_result_transition_duration',
            [
                'label' => esc_html__('Transition Duration (ms)', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'default' => 500,
                'min' => 0,
                'step' => 50,
                'selectors' => [
                    '{{WRAPPER}} a.king-addons-view-result' => 'transition-duration: {{VALUE}}ms',
                ],
            ]
        );

        $this->add_control(
            'view_result_border_radius',
            [
                'label' => esc_html__('Border Radius', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'default' => [
                    'top' => 2,
                    'right' => 2,
                    'bottom' => 2,
                    'left' => 2,
                ],
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} a.king-addons-view-result' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ]
            ]
        );

        $this->add_responsive_control(
            'view_result_padding',
            [
                'label' => esc_html__('Padding', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'default' => [
                    'top' => 5,
                    'right' => 10,
                    'bottom' => 5,
                    'left' => 10,
                ],
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} a.king-addons-view-result' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->add_control(
            'heading_close_btn',
            [
                'label' => esc_html__('Close Button', 'king-addons'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before'
            ]
        );

        $this->add_control(
            'close_btn_color',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#E8E8E8',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-data-fetch .king-addons-close-search' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'close_btn_size',
            [
                'label' => esc_html__('Size', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ]
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 14,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-data-fetch .king-addons-close-search::before' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .king-addons-data-fetch .king-addons-close-search' => 'height: {{SIZE}}{{UNIT}};',
                ]
            ]
        );

        $this->add_responsive_control(
            'close_btn_position',
            [
                'label' => esc_html__('Distance', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
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
                    '{{WRAPPER}} .king-addons-data-fetch .king-addons-close-search' => 'top: {{SIZE}}{{UNIT}}; right: {{SIZE}}{{UNIT}};',
                ]
            ]
        );

        $this->add_control(
            'scrollbar_heading',
            [
                'label' => esc_html__('Scrollbar', 'king-addons'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before'
            ]
        );

        $this->add_control(
            'scrollbar_color',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#E8E8E8',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-data-fetch ul::-webkit-scrollbar-thumb' => 'border-left-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'scrollbar_width',
            [
                'label' => esc_html__('Width', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 10,
                    ]
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 3,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-data-fetch ul::-webkit-scrollbar-thumb' => 'border-left-width: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .king-addons-data-fetch ul::-webkit-scrollbar' => 'width: calc({{SIZE}}{{UNIT}} + 3px);',
                ]
            ]
        );

        $this->add_control(
            'no_results_heading',
            [
                'label' => esc_html__('No Results', 'king-addons'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before'
            ]
        );

        $this->add_control(
            'no_results_color',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#222222',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-data-fetch .king-addons-no-results' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'no_results_typography',
                'selector' => '{{WRAPPER}} .king-addons-data-fetch .king-addons-no-results',
            ]
        );

        $this->add_responsive_control(
            'no_results_height',
            [
                'label' => esc_html__('Height', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'vh'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                    ],
                    'vh' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'vh',
                    'size' => 20,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-data-fetch .king-addons-no-results' => 'height: {{SIZE}}{{UNIT}};',
                ]
            ]
        );

        $this->add_control(
            'search_results_box_border_size',
            [
                'label' => esc_html__('Border Size', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'default' => [
                    'top' => 1,
                    'right' => 1,
                    'bottom' => 1,
                    'left' => 1,
                ],
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-data-fetch' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'search_results_box_border_radius',
            [
                'label' => esc_html__('Border Radius', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'default' => [
                    'top' => 2,
                    'right' => 2,
                    'bottom' => 2,
                    'left' => 2,
                ],
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-data-fetch' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
                'separator' => 'after',
            ]
        );

        $this->add_responsive_control(
            'search_results_box_padding',
            [
                'label' => esc_html__('Padding', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'default' => [
                    'top' => 2,
                    'right' => 2,
                    'bottom' => 2,
                    'left' => 2,
                ],
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-data-fetch ul' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before'
            ]
        );

        $this->end_controls_section();
    }

    public function add_section_ajax_pagination()
    {
    }

    public function add_section_style_ajax_pagination()
    {
    }

    public function add_control_search_query(): void
    {
        $search_post_type = array_merge(['all' => esc_html__('All', 'king-addons')], Core::getCustomTypes('post', false));

        foreach ($search_post_type as $key => $value) {
            if ('all' != $key) {
                $search_post_type['pro-' . $key] = $value . ' (Pro)';

                if ('post' != $key && 'page' != $key && 'product' != $key && 'e-landing-page' != $key && !king_addons_freemius()->can_use_premium_code__premium_only()) {
                    $search_post_type['pro-' . $key] = $value . ' (Pro)';
                }

                unset($search_post_type[$key]);
            }
        }

        $this->add_control(
            'search_query',
            [
                'label' => esc_html__('Select Query', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'label_block' => false,
                'options' => $search_post_type,
                'default' => 'all',
            ]
        );
    }

    public function add_control_select_category(): void
    {
        $this->add_control(
            'select_category',
            [
                'label' => esc_html__('Category Filter', 'king-addons') . ' <i class="eicon-pro-icon"></i>',
                'type' => Controls_Manager::SWITCHER,
                'separator' => 'before',
                'classes' => 'king-addons-pro-control',
            ]
        );
    }

    public function add_control_all_cat_text()
    {
    }

    public function add_control_ajax_search(): void
    {
        $this->add_control(
            'ajax_search',
            [
                'label' => esc_html__('Enable Ajax Search (live results)', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'separator' => 'before'
            ]
        );
    }

    public function add_control_number_of_results()
    {
    }

    public function add_control_show_password_protected(): void
    {
        if (current_user_can('administrator')) {
            $this->add_control(
                'ajax_show_ps_pt',
                [
                    'label' => esc_html__('Show Password Protected', 'king-addons'),
                    'type' => Controls_Manager::SWITCHER,
                    'description' => esc_html__('Only for users with capability to read private posts', 'king-addons'),
                    'condition' => [
                        'ajax_search' => 'yes'
                    ],
                    'render_type' => 'template',
                ]
            );
        }
    }

    public function add_control_open_in_new_page(): void
    {
        $this->add_control(
            'ajax_search_link_target',
            [
                'label' => esc_html__('Open Link in New Tab', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'condition' => [
                    'ajax_search' => 'yes'
                ]
            ]
        );
    }

    public function add_control_show_ajax_thumbnails(): void
    {
        $this->add_control(
            'show_ajax_thumbnails',
            [
                'label' => esc_html__('Show Ajax Thumbnails', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'render_type' => 'template',
                'condition' => [
                    'ajax_search' => 'yes'
                ]
            ]
        );
    }

    public function add_control_exclude_posts_without_thumbnail(): void
    {
        $this->add_control(
            'exclude_posts_without_thumbnail',
            [
                'label' => esc_html__('Exclude Results without Thumbnails', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'render_type' => 'template',
                'condition' => [
                    'ajax_search' => 'yes',
                    'show_ajax_thumbnails' => 'yes'
                ]
            ]
        );
    }

    public function add_control_show_description(): void
    {
        $this->add_control(
            'show_description',
            [
                'label' => esc_html__('Show Description', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'render_type' => 'template',
                'condition' => [
                    'ajax_search' => 'yes'
                ]
            ]
        );
    }

    public function add_control_number_of_words_in_excerpt(): void
    {
        $this->add_control(
            'number_of_words_in_excerpt',
            [
                'label' => esc_html__('Description Number of Words', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'min' => 5,
                'step' => 1,
                'default' => 30,
                'render_type' => 'template',
                'condition' => [
                    'ajax_search' => 'yes',
                    'show_description' => 'yes'
                ]
            ]
        );
    }

    public function add_control_show_view_result_btn(): void
    {
        $this->add_control(
            'show_view_result_btn',
            [
                'label' => esc_html__('Show View Results Button', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'render_type' => 'template',
                'condition' => [
                    'ajax_search' => 'yes'
                ]
            ]
        );
    }

    public function add_control_view_result_text(): void
    {
        $this->add_control(
            'view_result_text',
            [
                'label' => esc_html__('View Results', 'king-addons'),
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => esc_html__('View Results', 'king-addons'),
                'condition' => [
                    'show_view_result_btn' => 'yes',
                    'ajax_search' => 'yes'
                ]
            ]
        );
    }

    public function add_control_no_results_text(): void
    {
        $this->add_control(
            'no_results_text',
            [
                'label' => esc_html__('No Results Text', 'king-addons'),
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => esc_html__('No Results Found', 'king-addons'),
                'condition' => [
                    'ajax_search' => 'yes'
                ]
            ]
        );
    }

    protected function register_controls(): void
    {

        $this->start_controls_section(
            'section_search',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Search', 'king-addons'),
            ]
        );

        $this->add_control_search_query();

        Core::renderUpgradeProNotice($this, Controls_Manager::RAW_HTML, 'search', 'search_query', ['pro-post', 'pro-page', 'pro-product', 'pro-product', 'pro-e-landing-page']);

        if (!king_addons_freemius()->can_use_premium_code__premium_only()) {
            $this->add_control(
                'search_query_up_pro_notice',
                [
                    'raw' => 'This option is available<br> in the <strong><a href="https://kingaddons.com/pricing/?utm_source=kng-module-search-settings-upgrade-pro&utm_medium=plugin&utm_campaign=kng" target="_blank">Pro version</a></strong>',
                    'type' => Controls_Manager::RAW_HTML,
                    'content_classes' => 'king-addons-pro-notice',
                    'condition' => [
                        'search_query!' => ['all', 'post', 'page', 'product', 'e-landing-page', 'pro-post', 'pro-page', 'pro-product', 'pro-product', 'pro-e-landing-page'],
                    ]
                ]
            );
        }

        /** @noinspection DuplicatedCode */
        $this->add_control_select_category();

        $this->add_control_all_cat_text();

        $this->add_control_ajax_search();

        $this->add_control_number_of_results();

        $this->add_control_show_password_protected();

        $this->add_control_open_in_new_page();

        $this->add_control_show_ajax_thumbnails();

        $this->add_control_exclude_posts_without_thumbnail();

        $this->add_control_show_view_result_btn();

        $this->add_control_view_result_text();

        $this->add_control_show_description();

        $this->add_control_number_of_words_in_excerpt();

        $this->add_control_no_results_text();

        $this->add_control(
            'search_placeholder',
            [
                'label' => esc_html__('Placeholder', 'king-addons'),
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => esc_html__('Search...', 'king-addons'),
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'search_aria_label',
            [
                'label' => esc_html__('Aria Label', 'king-addons'),
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => esc_html__('Search', 'king-addons'),
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'search_btn',
            [
                'label' => esc_html__('Button', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'search_btn_style',
            [
                'label' => esc_html__('Style', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'inner',
                'options' => [
                    'inner' => esc_html__('Inner', 'king-addons'),
                    'outer' => esc_html__('Outer', 'king-addons'),
                ],
                'prefix_class' => 'king-addons-search-form-style-',
                'render_type' => 'template',
                'condition' => [
                    'search_btn' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'search_btn_disable_click',
            [
                'label' => esc_html__('Disable Button Click', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'condition' => [
                    'search_btn_style' => 'inner',
                    'search_btn' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'search_btn_type',
            [
                'label' => esc_html__('Type', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'icon',
                'options' => [
                    'text' => esc_html__('Text', 'king-addons'),
                    'icon' => esc_html__('Icon', 'king-addons'),
                ],
                'render_type' => 'template',
                'condition' => [
                    'search_btn' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'search_btn_text',
            [
                'label' => esc_html__('Text', 'king-addons'),
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => 'Go',
                'condition' => [
                    'search_btn_type' => 'text',
                    'search_btn' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'search_btn_icon',
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
                    'search_btn_type' => 'icon',
                    'search_btn' => 'yes',
                ],
            ]
        );

        $this->add_responsive_control(
            'search_btn_icon_size',
            [
                'label' => esc_html__('Icon Size (px)', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'step' => 1,
                'min' => 0,
                'default' => 16,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-search-form-submit i' => 'font-size: {{VALUE}}px; width: {{VALUE}}px;',
                    '{{WRAPPER}} .king-addons-search-form-submit img' => 'width: {{VALUE}}px; height: {{VALUE}}px;',
                    '{{WRAPPER}} .king-addons-search-form-submit svg' => 'width: {{VALUE}}px; height: {{VALUE}}px;'
                ],
                'condition' => [
                    'search_btn_type' => 'icon',
                    'search_btn' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();

        $this->add_section_ajax_pagination();

        Core::renderProFeaturesSection($this, '', Controls_Manager::RAW_HTML, 'search', [
            'More Than 2 Results in Ajax Search',
            'Ajax Search Results Pagination (Load More)',
            'Enable Taxonomy & Category Filter',
            'Custom Search Query: Only Posts or Pages',
            'Custom Search Query: Only Custom Post Types',
        ]);

        $this->start_controls_section(
            'section_style_input',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Input Field', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->start_controls_tabs('tabs_input_colors');

        $this->start_controls_tab(
            'tab_input_normal_colors',
            [
                'label' => esc_html__('Normal', 'king-addons'),
            ]
        );

        $this->add_control(
            'input_color',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#333333',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-search-form-input' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'input_bg_color',
            [
                'label' => esc_html__('Background Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-search-form-input' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'input_placeholder_color',
            [
                'label' => esc_html__('Placeholder Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#9e9e9e',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-search-form-input::-webkit-input-placeholder' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .king-addons-search-form-input::-moz-placeholder' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .king-addons-search-form-input:-moz-placeholder' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .king-addons-search-form-input::placeholder' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'input_border_color',
            [
                'label' => esc_html__('Border Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#E8E8E8',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-search-form-input' => 'border-color: {{VALUE}};',
                    '{{WRAPPER}} .king-addons-data-fetch' => 'border-color: {{VALUE}};'
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'input_box_shadow',
                'selector' => '{{WRAPPER}} .king-addons-search-form-input-wrap'
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_input_focus_colors',
            [
                'label' => esc_html__('Focus', 'king-addons'),
            ]
        );

        $this->add_control(
            'input_focus_color',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#333333',
                'selectors' => [
                    '{{WRAPPER}}.king-addons-search-form-input-focus .king-addons-search-form-input' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'input_focus_bg_color',
            [
                'label' => esc_html__('Background Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}}.king-addons-search-form-input-focus .king-addons-search-form-input' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'input_focus_placeholder_color',
            [
                'label' => esc_html__('Placeholder Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#9e9e9e',
                'selectors' => [
                    '{{WRAPPER}}.king-addons-search-form-input-focus .king-addons-search-form-input::-webkit-input-placeholder' => 'color: {{VALUE}};',
                    '{{WRAPPER}}.king-addons-search-form-input-focus .king-addons-search-form-input::-moz-placeholder' => 'color: {{VALUE}};',
                    '{{WRAPPER}}.king-addons-search-form-input-focus .king-addons-search-form-input:-moz-placeholder' => 'color: {{VALUE}};',
                    '{{WRAPPER}}.king-addons-search-form-input-focus .king-addons-search-form-input::placeholder' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'input_focus_border_color',
            [
                'label' => esc_html__('Border Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#E8E8E8',
                'selectors' => [
                    '{{WRAPPER}}.king-addons-search-form-input-focus .king-addons-search-form-input' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'input_focus_box_shadow',
                'selector' => '{{WRAPPER}}.king-addons-search-form-input-focus .king-addons-search-form-input-wrap'
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_control(
            'input_typography_divider',
            [
                'type' => Controls_Manager::DIVIDER,
                'style' => 'thick',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'input_typography',
                'selector' => '{{WRAPPER}} .king-addons-search-form-input, {{WRAPPER}} .king-addons-category-select-wrap, {{WRAPPER}} .king-addons-category-select',
            ]
        );

        $this->add_responsive_control(
            'input_align',
            [
                'label' => esc_html__('Alignment', 'king-addons'),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'default' => 'left',
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
                    '{{WRAPPER}} .king-addons-search-form-input' => 'text-align: {{VALUE}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'input_border_size',
            [
                'label' => esc_html__('Border Size', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'default' => [
                    'top' => 1,
                    'right' => 1,
                    'bottom' => 1,
                    'left' => 1,
                ],
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-search-form-input' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .king-addons-data-fetch' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'input_border_radius',
            [
                'label' => esc_html__('Border Radius', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'default' => [
                    'top' => 2,
                    'right' => 2,
                    'bottom' => 2,
                    'left' => 2,
                ],
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-search-form-input' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                    '{{WRAPPER}} .king-addons-data-fetch' => 'border-radius: 0 0 {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
                'separator' => 'after',
            ]
        );

        $this->add_responsive_control(
            'input_padding',
            [
                'label' => esc_html__('Padding', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'default' => [
                    'top' => 15,
                    'right' => 15,
                    'bottom' => 15,
                    'left' => 15,
                ],
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-search-form-input' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .king-addons-category-select-wrap::before' => 'right: {{RIGHT}}{{UNIT}};',
                    '{{WRAPPER}} .king-addons-category-select' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_select',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Taxonomy Filter', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'select_category' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'select_color',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#333333',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-category-select-wrap' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .king-addons-category-select' => 'color: {{VALUE}};'
                ],
            ]
        );

        $this->add_control(
            'select_bg_color',
            [
                'label' => esc_html__('Background Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-category-select' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'select_border_color',
            [
                'label' => esc_html__('Border Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#E8E8E8',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-category-select' => 'border-color: {{VALUE}};'
                ],
            ]
        );

        $this->add_control(
            'select_border_size',
            [
                'label' => esc_html__('Border Size', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'default' => [
                    'top' => 1,
                    'right' => 1,
                    'bottom' => 1,
                    'left' => 1,
                ],
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-category-select' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'select_border_radius',
            [
                'label' => esc_html__('Border Radius', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'default' => [
                    'top' => 2,
                    'right' => 2,
                    'bottom' => 2,
                    'left' => 2,
                ],
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-category-select' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                    '{{WRAPPER}} .king-addons-category-select-wrap' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'after',
            ]
        );

        $this->add_responsive_control(
            'select_width',
            [
                'label' => esc_html__('Select Width', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 400,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 230,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-category-select-wrap' => 'width: {{SIZE}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'options_heading',
            [
                'label' => esc_html__('Options', 'king-addons'),
                'type' => Controls_Manager::HEADING,
            ]
        );

        $this->add_responsive_control(
            'option_font_size',
            [
                'label' => esc_html__('Font Size', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 25,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 12,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-category-select option' => 'font-size: {{SIZE}}{{UNIT}};',
                ]
            ]
        );

        $this->add_control(
            'optgroup_heading',
            [
                'label' => esc_html__('Options Group', 'king-addons'),
                'type' => Controls_Manager::HEADING,
            ]
        );

        $this->add_responsive_control(
            'optgroup_font_size',
            [
                'label' => esc_html__('Font Size', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 25,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 12,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-category-select optgroup' => 'font-size: {{SIZE}}{{UNIT}};',
                ]
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_btn',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Button', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'search_btn' => 'yes',
                ],
            ]
        );

        $this->start_controls_tabs('tabs_btn_colors');

        $this->start_controls_tab(
            'tab_btn_normal_colors',
            [
                'label' => esc_html__('Normal', 'king-addons'),
            ]
        );

        $this->add_control(
            'btn_text_color',
            [
                'type' => Controls_Manager::COLOR,
                'label' => esc_html__('Text/Icon Color', 'king-addons'),
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-search-form-submit' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .king-addons-search-form-submit svg' => 'fill: {{VALUE}};'
                ],
            ]
        );

        $this->add_control(
            'btn_bg_color',
            [
                'type' => Controls_Manager::COLOR,
                'label' => esc_html__('Background Color', 'king-addons'),
                'default' => '#5B03FF',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-search-form-submit' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'btn_border_color',
            [
                'type' => Controls_Manager::COLOR,
                'label' => esc_html__('Border Color', 'king-addons'),
                'default' => '#E8E8E8',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-search-form-submit' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'btn_box_shadow',
                'selector' => '{{WRAPPER}} .king-addons-search-form-submit',
                'condition' => [
                    'search_btn_style' => 'outer',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_btn_hover_colors',
            [
                'label' => esc_html__('Hover', 'king-addons'),
            ]
        );


        $this->add_control(
            'btn_hv_text_color',
            [
                'type' => Controls_Manager::COLOR,
                'label' => esc_html__('Text/Icon Color', 'king-addons'),
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-search-form-submit:hover' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .king-addons-search-form-submit:hover svg' => 'fill: {{VALUE}};'
                ],
            ]
        );

        $this->add_control(
            'btn_hv_bg_color',
            [
                'type' => Controls_Manager::COLOR,
                'label' => esc_html__('Background Color', 'king-addons'),
                'default' => '#4e00e0',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-search-form-submit:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'btn_hv_border_color',
            [
                'type' => Controls_Manager::COLOR,
                'label' => esc_html__('Border Color', 'king-addons'),
                'default' => '#E8E8E8',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-search-form-submit:hover' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'btn_hv_box_shadow',
                'selector' => '{{WRAPPER}} .king-addons-search-form-submit:hover',
                'condition' => [
                    'search_btn_style' => 'outer',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_responsive_control(
            'btn_width',
            [
                'label' => esc_html__('Width', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 300,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 125,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-search-form-submit' => 'min-width: {{SIZE}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'btn_height',
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
                    'size' => 50,
                ],
                'selectors' => [
                    '{{WRAPPER}}.king-addons-search-form-style-outer .king-addons-search-form-submit' => 'height: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'search_btn_style' => 'outer',
                ],
            ]
        );

        $this->add_control(
            'btn_gutter',
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
                    'size' => 10,
                ],
                'selectors' => [
                    '{{WRAPPER}}.king-addons-search-form-style-outer.king-addons-search-form-position-right .king-addons-search-form-submit' => 'margin-left: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}}.king-addons-search-form-style-outer.king-addons-search-form-position-left .king-addons-search-form-submit' => 'margin-right: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'search_btn_style' => 'outer',
                ],
            ]
        );

        $this->add_control(
            'btn_position',
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
                'prefix_class' => 'king-addons-search-form-position-',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'btn_typography_divider',
            [
                'type' => Controls_Manager::DIVIDER,
                'style' => 'thick',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'btn_typography',
                'label' => esc_html__('Typography', 'king-addons'),
                'selector' => '{{WRAPPER}} .king-addons-search-form-submit',
            ]
        );

        $this->add_control(
            'btn_border_size',
            [
                'label' => esc_html__('Border Size', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'default' => [
                    'top' => 0,
                    'right' => 0,
                    'bottom' => 0,
                    'left' => 0,
                ],
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-search-form-submit' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'btn_border_radius',
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
                    '{{WRAPPER}} .king-addons-search-form-submit' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
            ]
        );

        $this->add_control(
            'btn_padding',
            [
                'label' => esc_html__('Padding', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'default' => [
                    'top' => 0,
                    'right' => 0,
                    'bottom' => 0,
                    'left' => 0,
                ],
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-search-form-submit' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->end_controls_section();

        $this->add_section_style_ajax();

        $this->add_section_style_ajax_pagination();

    }

    public function render_search_pagination($settings)
    {
    }

    protected function render_search_submit_btn(): void
    {
        $settings = $this->get_settings();

        $this->add_render_attribute(
            'button', [
                'class' => 'king-addons-search-form-submit',
                'aria-label' => $settings['search_aria_label'],
                'type' => 'submit',
            ]
        );

        if ($settings['search_btn_disable_click']) {
            $this->add_render_attribute('button', 'disabled');
        }

        if ('yes' === $settings['search_btn']) : ?>

            <button <?php echo $this->get_render_attribute_string('button'); ?>>
                <?php if ('icon' === $settings['search_btn_type'] && '' !== $settings['search_btn_icon']['value']) : ?>
                    <?php Icons_Manager::render_icon($settings['search_btn_icon']); ?>
                <?php elseif ('text' === $settings['search_btn_type'] && '' !== $settings['search_btn_text']) : ?>
                    <?php echo esc_html($settings['search_btn_text']); ?>
                <?php endif; ?>
            </button>

        <?php
        endif;
    }

    protected function render(): void
    {
        $settings = $this->get_settings();
        $this->add_render_attribute(
            'input', [
                'ajax-search' => $settings['ajax_search'] ?? '',
                'class' => 'king-addons-search-form-input',
                'exclude-without-thumb' => $settings['exclude_posts_without_thumbnail'] ?? '',
                'link-target' => isset($settings['ajax_search_link_target']) && ('yes' === $settings['ajax_search_link_target']) ? '_blank' : '_self',
                'name' => 's',
                'no-results' => $settings['no_results_text'] ?? '',
                'number-of-results' => isset($settings['number_of_results']) && king_addons_freemius()->can_use_premium_code__premium_only() ? $settings['number_of_results'] : 2,
                'number-of-words' => $settings['number_of_words_in_excerpt'] ?? '',
                'password-protected' => $settings['ajax_show_ps_pt'] ?? 'no',
                'placeholder' => $settings['search_placeholder'],
                'show-ajax-thumbnails' => $settings['show_ajax_thumbnails'] ?? '',
                'show-description' => $settings['show_description'] ?? '',
                'show-view-result-btn' => $settings['show_view_result_btn'] ?? '',
                'title' => esc_html__('Search', 'king-addons'),
                'type' => 'search',
                'value' => get_search_query(),
                'view-result-text' => $settings['view_result_text'] ?? '',
                'king-addons-query-type' => $settings['search_query'],
                'king-addons-taxonomy-type' => $settings['query_taxonomy_' . $settings['search_query']] ?? '',
            ]
        );

        ?>
        <form role="search" method="get" class="king-addons-search-form" action="<?php echo esc_url(home_url()); ?>">
            <div class="king-addons-search-form-input-wrap elementor-clearfix">
                <!--suppress HtmlFormInputWithoutLabel -->
                <input <?php echo $this->get_render_attribute_string('input'); ?>>
                <?php
                if ($settings['search_btn_style'] === 'inner') {
                    $this->render_search_submit_btn();
                }
                ?>
            </div>
            <?php

            if ($settings['search_btn_style'] === 'outer') {
                $this->render_search_submit_btn();
            }

            ?>
        </form>
        <div class="king-addons-data-fetch">
            <span class="king-addons-close-search"></span>
            <ul></ul>
            <?php if (!king_addons_freemius()->can_use_premium_code__premium_only() && current_user_can('administrator')) : ?>
                <p class="king-addons-search-admin-notice"><?php
                    echo esc_html__('More than 2 results are available in the ', 'king-addons');
                    echo '<strong><a href="https://kingaddons.com/pricing/?utm_source=kng-module-search-settings-upgrade-pro&utm_medium=plugin&utm_campaign=kng" target="_blank">' . esc_html__('PRO version', 'king-addons') . '</a></strong>';
                    echo esc_html__(' of King Addons (this notice is visible to admin users only)', 'king-addons');
                    ?></p>
            <?php endif; ?>
        </div>
        <?php
    }

}