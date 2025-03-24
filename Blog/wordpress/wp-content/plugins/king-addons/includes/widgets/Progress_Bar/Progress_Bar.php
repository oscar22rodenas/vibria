<?php

namespace King_Addons;

use Elementor\Controls_Manager;
use Elementor\Widget_Base;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class Progress_Bar extends Widget_Base
{
    public function get_name(): string
    {
        return 'king-addons-progress-bar';
    }

    public function get_title(): string
    {
        return esc_html__('Progress Bar', 'king-addons');
    }

    public function get_icon(): string
    {
        return 'king-addons-icon king-addons-progress-bar';
    }

    public function get_script_depends(): array
    {
        return [KING_ADDONS_ASSETS_UNIQUE_KEY . '-jquerynumerator-jquerynumerator', KING_ADDONS_ASSETS_UNIQUE_KEY . '-progress-bar-script'];
    }

    public function get_style_depends(): array
    {
        return [KING_ADDONS_ASSETS_UNIQUE_KEY . '-animation-timing', KING_ADDONS_ASSETS_UNIQUE_KEY . '-progress-bar-style'];
    }

    public function get_categories(): array
    {
        return ['king-addons'];
    }

    public function get_keywords(): array
    {
        return ['progress bar', 'bar', 'skill bar', 'skills bar', 'percentage bar', 'charts', 'chart', 'graph',
            'bar chart', 'line', 'progress', 'king addons', 'king', 'addons', 'kingaddons', 'king-addons'];
    }

    public function get_custom_help_url(): string
    {
        return 'https://kingaddons.com/elementor/progress-bar/';
    }

    public function add_control_layout()
    {
        $this->add_control(
            'layout',
            [
                'label' => esc_html__('Layout', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'hr-line',
                'options' => [
                    'circle' => esc_html__('Circle', 'king-addons'),
                    'hr-line' => esc_html__('Horizontal Line', 'king-addons'),
                    'pro-vr' => esc_html__('Vertical Line (Pro)', 'king-addons'),
                ],
                'prefix_class' => 'king-addons-prbar-layout-',
                'render_type' => 'template',
            ]
        );
    }

    public function add_control_line_width()
    {
    }

    public function add_control_prline_width()
    {
    }

    public function add_control_stripe_switcher()
    {
        $this->add_control(
            'stripe_switcher',
            [
                'label' => sprintf(__('Stripe Background %s', 'king-addons'), '<i class="eicon-pro-icon"></i>'),
                'type' => Controls_Manager::SWITCHER,
                'classes' => 'king-addons-pro-control no-distance'
            ]
        );
    }

    public function add_control_stripe_anim()
    {
    }

    public function add_control_anim_loop()
    {
        $this->add_control(
            'anim_loop',
            [
                'label' => sprintf(__('Animation Loop %s', 'king-addons'), '<i class="eicon-pro-icon"></i>'),
                'type' => Controls_Manager::SWITCHER,
                'classes' => 'king-addons-pro-control no-distance',
                'separator' => 'before',
            ]
        );
    }

    public function add_control_anim_loop_delay()
    {
    }

    protected function register_controls()
    {

        $this->start_controls_section(
            'section_general',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('General', 'king-addons'),
            ]
        );

        $this->add_control_layout();

        Core::renderUpgradeProNotice($this, Controls_Manager::RAW_HTML, 'progress-bar', 'layout', ['pro-vr']);

        $this->add_control(
            'max_value',
            [
                'label' => esc_html__('Max Value', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'default' => 100,
                'min' => 0,
                'step' => 1,
                'separator' => 'before',
                'dynamic' => [
                    'active' => true,
                ],
            ]
        );

        $this->add_control(
            'counter_value',
            [
                'label' => esc_html__('Counter Value', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'default' => 70,
                'min' => 0,
                'step' => 1,
                'dynamic' => [
                    'active' => true,
                ],
            ]
        );

        $this->add_control(
            'title',
            [
                'label' => esc_html__('Title', 'king-addons'),
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => 'Title',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'title_position',
            [
                'label' => esc_html__('Title Position', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'inside',
                'options' => [
                    'inside' => esc_html__('Inside', 'king-addons'),
                    'outside' => esc_html__('Outside', 'king-addons'),
                ],
                'prefix_class' => 'king-addons-pbar-title-pos-',
                'render_type' => 'template',
                'condition' => [
                    'layout!' => 'vr-line',
                ],
            ]
        );

        $this->add_control(
            'subtitle',
            [
                'label' => esc_html__('Subtitle', 'king-addons'),
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => '',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'counter_switcher',
            [
                'label' => esc_html__('Show Counter', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'separator' => 'before'
            ]
        );

        $this->add_control(
            'counter_position',
            [
                'label' => esc_html__('Counter Position', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'inside',
                'options' => [
                    'inside' => esc_html__('Inside', 'king-addons'),
                    'outside' => esc_html__('Outside', 'king-addons'),
                ],
                'prefix_class' => 'king-addons-pbar-counter-pos-',
                'render_type' => 'template',
                'condition' => [
                    'counter_switcher' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'counter_follow_line',
            [
                'label' => esc_html__('Follow Pr. Line', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'condition' => [
                    'counter_switcher' => 'yes',
                    'counter_position' => 'inside',
                    'layout' => 'hr-line',
                ],
            ]
        );

        $this->add_control(
            'counter_prefix',
            [
                'label' => esc_html__('Counter Prefix', 'king-addons'),
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => '',
                'condition' => [
                    'counter_switcher' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'counter_suffix',
            [
                'label' => esc_html__('Counter Suffix', 'king-addons'),
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => '%',
                'condition' => [
                    'counter_switcher' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'counter_separator',
            [
                'label' => esc_html__('Show Thousand Separator', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'condition' => [
                    'counter_switcher' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_settings',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Settings', 'king-addons'),
            ]
        );

        $this->add_responsive_control(
            'circle_size',
            [
                'label' => esc_html__('Circle Size', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 100,
                        'max' => 1000,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 200,
                ],
                'widescreen_default' => [
                    'unit' => 'px',
                    'size' => 200,
                ],
                'laptop_default' => [
                    'unit' => 'px',
                    'size' => 200,
                ],
                'tablet_default' => [
                    'unit' => 'px',
                    'size' => 200,
                ],
                'tablet_extra_default' => [
                    'unit' => 'px',
                    'size' => 200,
                ],
                'mobile_extra_default' => [
                    'unit' => 'px',
                    'size' => 200,
                ],
                'mobile_default' => [
                    'unit' => 'px',
                    'size' => 200,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-prbar-circle' => 'max-width: {{SIZE}}{{UNIT}};',
                ],
                'render_type' => 'template',
                'condition' => [
                    'layout' => 'circle',
                ],
            ]
        );

        $this->add_control_line_width();

        $this->add_control_prline_width();

        $this->add_responsive_control(
            'line_size',
            [
                'label' => esc_html__('Line Size', 'king-addons'),
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
                    'size' => 27,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-prbar-hr-line' => 'height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .king-addons-prbar-vr-line' => 'width: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'layout!' => 'circle',
                ],
            ]
        );

        $this->add_responsive_control(
            'vr_line_height',
            [
                'label' => esc_html__('Vertical Line Height', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 277,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-prbar-vr-line' => 'height: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'layout' => 'vr-line',
                ],
            ]
        );

        $this->add_control(
            'anim_duration',
            [
                'label' => esc_html__('Animation Duration', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'default' => 1,
                'min' => 0,
                'max' => 10,
                'step' => 0.1,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-prbar-circle-prline' => '-webkit-transition-duration: {{VALUE}}s; transition-duration: {{VALUE}}s;',
                    '{{WRAPPER}} .king-addons-prbar-hr-line-inner' => '-webkit-transition-duration: {{VALUE}}s; transition-duration: {{VALUE}}s;',
                    '{{WRAPPER}} .king-addons-prbar-vr-line-inner' => '-webkit-transition-duration: {{VALUE}}s; transition-duration: {{VALUE}}s;',
                ],
                'render_type' => 'template',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'anim_delay',
            [
                'label' => esc_html__('Animation Delay', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'default' => 0,
                'min' => 0,
                'max' => 5,
                'step' => 0.1,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-prbar-circle-prline' => '-webkit-transition-delay: {{VALUE}}s; transition-delay: {{VALUE}}s;',
                    '{{WRAPPER}} .king-addons-prbar-hr-line-inner' => '-webkit-transition-delay: {{VALUE}}s; transition-delay: {{VALUE}}s;',
                    '{{WRAPPER}} .king-addons-prbar-vr-line-inner' => '-webkit-transition-delay: {{VALUE}}s; transition-delay: {{VALUE}}s;',
                ],
                'render_type' => 'template',
            ]
        );

        $this->add_control(
            'anim_timing',
            [
                'label' => esc_html__('Animation Timing', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => Core::getAnimationTimings(),
                'default' => 'ease-default',
            ]
        );

        Core::renderUpgradeProNotice($this, Controls_Manager::RAW_HTML, 'progress-bar', 'anim_timing', ['pro-eio', 'pro-eiqd', 'pro-eicb', 'pro-eiqrt', 'pro-eiqnt', 'pro-eisn', 'pro-eiex', 'pro-eicr', 'pro-eibk', 'pro-eoqd', 'pro-eocb', 'pro-eoqrt', 'pro-eoqnt', 'pro-eosn', 'pro-eoex', 'pro-eocr', 'pro-eobk', 'pro-eioqd', 'pro-eiocb', 'pro-eioqrt', 'pro-eioqnt', 'pro-eiosn', 'pro-eioex', 'pro-eiocr', 'pro-eiobk']);

        $this->add_control_anim_loop();

        $this->add_control_anim_loop_delay();

        $this->end_controls_section();

        Core::renderProFeaturesSection($this, '', Controls_Manager::RAW_HTML, 'progress-bar', [
            'Vertical Progress Bar',
            'Stripe Background',
            'Stripe Background Animation',
            'Stripe Animation Direction',
            'Advanced Animation Timing',
            'Animation Loop',
        ]);

        $this->start_controls_section(
            'section_style_general',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('General', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'wrapper_section',
            [
                'label' => esc_html__('Wrapper', 'king-addons'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'counter_switcher' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'general_bg_color',
            [
                'label' => esc_html__('Background Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#f4f4f4',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-prbar-circle-line' => 'fill: {{VALUE}}',
                    '{{WRAPPER}} .king-addons-prbar-hr-line' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .king-addons-prbar-vr-line' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'circle_line_bg_color',
            [
                'label' => esc_html__('Inactive Line Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#dddddd',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-prbar-circle-line' => 'stroke: {{VALUE}}',
                ],
                'condition' => [
                    'layout' => 'circle',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'general_box_shadow',
                'label' => esc_html__('Box Shadow', 'king-addons'),
                'selector' => '{{WRAPPER}} .king-addons-prbar-hr-line, {{WRAPPER}} .king-addons-prbar-vr-line, {{WRAPPER}} .king-addons-prbar-circle svg',
            ]
        );

        $this->add_control(
            'general_border_type',
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
                    '{{WRAPPER}} .king-addons-prbar-hr-line' => 'border-style: {{VALUE}};',
                    '{{WRAPPER}} .king-addons-prbar-vr-line' => 'border-style: {{VALUE}};',
                ],
                'condition' => [
                    'layout!' => 'circle'
                ]
            ]
        );

        $this->add_responsive_control(
            'general_border_width',
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
                    '{{WRAPPER}} .king-addons-prbar-hr-line' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .king-addons-prbar-vr-line' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'general_border_type!' => 'none',
                    'layout!' => 'circle'
                ],
            ]
        );

        $this->add_control(
            'general_border_color',
            [
                'label' => esc_html__('Border Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#e5e5e5',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-prbar-hr-line' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .king-addons-prbar-vr-line' => 'border-color: {{VALUE}}',
                ],
                'condition' => [
                    'general_border_type!' => 'none',
                    'layout!' => 'circle'
                ],
            ]
        );

        $this->add_control(
            'general_border_radius',
            [
                'label' => esc_html__('Border Radius', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-prbar-hr-line' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}}.king-addons-prbar-prline-rounded-yes .king-addons-prbar-hr-line-inner' => 'border-top-right-radius: calc({{RIGHT}}{{UNIT}} - {{general_border_width.RIGHT}}{{general_border_width.UNIT}});border-bottom-right-radius: calc({{BOTTOM}}{{UNIT}} - {{general_border_width.BOTTOM}}{{general_border_width.UNIT}});',
                    '{{WRAPPER}} .king-addons-prbar-vr-line' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}}.king-addons-prbar-prline-rounded-yes .king-addons-prbar-vr-line-inner' => 'border-top-right-radius: calc({{RIGHT}}{{UNIT}} - {{general_border_width.RIGHT}}{{general_border_width.UNIT}});border-top-left-radius: calc({{TOP}}{{UNIT}} - {{general_border_width.TOP}}{{general_border_width.UNIT}});',
                ],
                'default' => [
                    'top' => 5,
                    'right' => 5,
                    'bottom' => 5,
                    'left' => 5,
                ],
                'condition' => [
                    'layout!' => 'circle',
                ],
            ]
        );

        $this->add_control(
            'prline_section',
            [
                'label' => esc_html__('Progress Line', 'king-addons'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'circle_prline_bg_type',
            [
                'label' => esc_html__('Background Type', 'king-addons'),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'default' => 'color',
                'options' => [
                    'color' => [
                        'title' => esc_html__('Classic', 'king-addons'),
                        'icon' => 'fa fa-paint-brush',
                    ],
                    'gradient' => [
                        'title' => esc_html__('Gradient', 'king-addons'),
                        'icon' => 'fa fa-barcode',
                    ],
                ],
                'condition' => [
                    'layout' => 'circle',
                ],
            ]
        );

        $this->add_control(
            'circle_prline_bg_color',
            [
                'label' => esc_html__('Background Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#5B03FF',
                'condition' => [
                    'circle_prline_bg_type' => 'color',
                    'layout' => 'circle',
                ],
            ]
        );

        $this->add_control(
            'circle_prline_bg_color_a',
            [
                'label' => esc_html__('Background Color A', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#61ce70',
                'condition' => [
                    'circle_prline_bg_type' => 'gradient',
                    'layout' => 'circle',
                ],
            ]
        );

        $this->add_control(
            'circle_prline_bg_color_b',
            [
                'label' => esc_html__('Background Color B', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#4054b2',
                'condition' => [
                    'circle_prline_bg_type' => 'gradient',
                    'layout' => 'circle',
                ],
            ]
        );

        $this->add_control(
            'circle_prline_grad_angle',
            [
                'label' => esc_html__('Gradient Angle', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 360,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 0,
                ],
                'condition' => [
                    'circle_prline_bg_type' => 'gradient',
                    'layout' => 'circle',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'prline_bg_color',
                'types' => ['classic', 'gradient'],
                'fields_options' => [
                    'color' => [
                        'default' => '#5B03FF',
                    ],
                    'background' => [
                        'default' => 'classic',
                    ]
                ],
                'selector' => '{{WRAPPER}} .king-addons-prbar-hr-line-inner, {{WRAPPER}} .king-addons-prbar-vr-line-inner',
                'condition' => [
                    'layout!' => 'circle',
                ],
            ]
        );

        $this->add_control(
            'prline_round',
            [
                'label' => esc_html__('Rounded Line', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-prbar-circle-prline' => 'stroke-linecap: round;',
                ],
                'prefix_class' => 'king-addons-prbar-prline-rounded-',
                'render_type' => 'template',
            ]
        );

        $this->add_control_stripe_switcher();

        $this->add_control_stripe_anim();

        $this->add_control(
            'title_section',
            [
                'label' => esc_html__('Title', 'king-addons'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'title!' => '',
                ],
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#C7C6C6',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-prbar-title' => 'color: {{VALUE}}',
                ],
                'condition' => [
                    'title!' => '',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .king-addons-prbar-title',
                'condition' => [
                    'title!' => '',
                ],
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
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 0,
                ],
                'selectors' => [
                    '{{WRAPPER}}.king-addons-prbar-layout-hr-line .king-addons-prbar-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}}.king-addons-prbar-layout-circle.king-addons-pbar-title-pos-inside .king-addons-prbar-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}}.king-addons-prbar-layout-circle.king-addons-pbar-title-pos-outside .king-addons-prbar-title' => 'margin-top: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}}.king-addons-prbar-layout-vr-line .king-addons-prbar-title' => 'margin-top: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'title!' => '',
                ],
            ]
        );

        $this->add_control(
            'subtitle_section',
            [
                'label' => esc_html__('Subtitle', 'king-addons'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'subtitle!' => '',
                ],
            ]
        );

        $this->add_control(
            'subtitle_color',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#C7C6C6',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-prbar-subtitle' => 'color: {{VALUE}}',
                ],
                'condition' => [
                    'subtitle!' => '',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'subtitle_typography',
                'selector' => '{{WRAPPER}} .king-addons-prbar-subtitle',
                'condition' => [
                    'subtitle!' => '',
                ],
            ]
        );

        $this->add_responsive_control(
            'subtitle_distance',
            [
                'label' => esc_html__('Distance', 'king-addons'),
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
                    'size' => 12,
                ],
                'selectors' => [
                    '{{WRAPPER}}.king-addons-prbar-layout-hr-line .king-addons-prbar-subtitle' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}}.king-addons-prbar-layout-circle.king-addons-pbar-title-pos-inside .king-addons-prbar-subtitle' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}}.king-addons-prbar-layout-circle.king-addons-pbar-title-pos-outside .king-addons-prbar-subtitle' => 'margin-top: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}}.king-addons-prbar-layout-vr-line .king-addons-prbar-subtitle' => 'margin-top: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'subtitle!' => '',
                ],
            ]
        );

        $this->add_control(
            'counter_section',
            [
                'label' => esc_html__('Counter', 'king-addons'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'counter_switcher' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'counter_color',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#C7C6C6',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-prbar-counter' => 'color: {{VALUE}}',
                ],
                'condition' => [
                    'counter_switcher' => 'yes',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'counter_typography',
                'selector' => '{{WRAPPER}} .king-addons-prbar-counter',
                'condition' => [
                    'counter_switcher' => 'yes',
                ],
            ]
        );

        $this->add_responsive_control(
            'counter_distance',
            [
                'label' => esc_html__('Distance', 'king-addons'),
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
                    'size' => 12,
                ],
                'selectors' => [
                    '{{WRAPPER}}.king-addons-prbar-layout-hr-line .king-addons-prbar-counter' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}}.king-addons-prbar-layout-vr-line .king-addons-prbar-counter' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}}.king-addons-prbar-layout-circle.king-addons-pbar-counter-pos-outside .king-addons-prbar-counter' => 'margin-top: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'counter_switcher' => 'yes',
                    'counter_position!' => 'inside',
                    'layout!' => 'hr-line'
                ],
            ]
        );

        $this->add_control(
            'counter_prefix_section',
            [
                'label' => esc_html__('Counter Prefix', 'king-addons'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'counter_switcher' => 'yes',
                    'counter_prefix!' => ''
                ],
            ]
        );

        $this->add_control(
            'counter_prefix_vr_position',
            [
                'label' => esc_html__('Vertical Position', 'king-addons'),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
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
                'default' => 'middle',
                'selectors_dictionary' => [
                    'top' => 'flex-start',
                    'middle' => 'center',
                    'bottom' => 'flex-end',
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-prbar-counter-value-prefix' => '-webkit-align-self: {{VALUE}}; align-self: {{VALUE}};',
                ],
                'condition' => [
                    'counter_switcher' => 'yes',
                    'counter_prefix!' => ''
                ],
            ]
        );

        $this->add_responsive_control(
            'counter_prefix_size',
            [
                'label' => esc_html__('Font Size', 'king-addons'),
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
                    'size' => 12,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-prbar-counter-value-prefix' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'counter_switcher' => 'yes',
                    'counter_prefix!' => ''
                ],
            ]
        );

        $this->add_responsive_control(
            'counter_prefix_distance',
            [
                'label' => esc_html__('Distance', 'king-addons'),
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
                    '{{WRAPPER}} .king-addons-prbar-counter-value-prefix' => 'margin-right: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'counter_switcher' => 'yes',
                    'counter_prefix!' => ''
                ],
            ]
        );

        $this->add_control(
            'counter_suffix_section',
            [
                'label' => esc_html__('Counter Suffix', 'king-addons'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'counter_switcher' => 'yes',
                    'counter_suffix!' => ''
                ],
            ]
        );

        $this->add_control(
            'counter_suffix_vr_position',
            [
                'label' => esc_html__('Vertical Position', 'king-addons'),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
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
                'default' => 'middle',
                'selectors_dictionary' => [
                    'top' => 'flex-start',
                    'middle' => 'center',
                    'bottom' => 'flex-end',
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-prbar-counter-value-suffix' => '-webkit-align-self: {{VALUE}}; align-self: {{VALUE}};',
                ],
                'condition' => [
                    'counter_switcher' => 'yes',
                    'counter_suffix!' => ''
                ],
            ]
        );

        $this->add_responsive_control(
            'counter_suffix_size',
            [
                'label' => esc_html__('Font Size', 'king-addons'),
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
                    'size' => 12,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-prbar-counter-value-suffix' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'counter_switcher' => 'yes',
                    'counter_suffix!' => ''
                ],
            ]
        );

        $this->add_responsive_control(
            'counter_suffix_distance',
            [
                'label' => esc_html__('Distance', 'king-addons'),
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
                    '{{WRAPPER}} .king-addons-prbar-counter-value-suffix' => 'margin-left: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'counter_switcher' => 'yes',
                    'counter_suffix!' => ''
                ],
            ]
        );

        $this->end_controls_section();

    }

    protected function render_progress_bar_circle($percent)
    {
        $settings = $this->get_settings();

        $circle_stocke_bg = $settings['circle_prline_bg_color'];
        $circle_size = $settings['circle_size']['size'];
        $circle_half_size = ($circle_size / 2);
        $circle_viewbox = sprintf('0 0 %1$s %1$s', $circle_size);
        $circle_line_width = king_addons_freemius()->can_use_premium_code__premium_only() ? $settings['line_width']['size'] : 15;
        $circle_prline_width = king_addons_freemius()->can_use_premium_code__premium_only() ? $settings['prline_width']['size'] : 15;
        /** @noinspection DuplicatedCode */
        $circle_radius = $circle_half_size - ($circle_prline_width / 2);

        if ($circle_line_width > $circle_prline_width) {
            $circle_radius = $circle_half_size - ($circle_line_width / 2);
        }

        if ($circle_prline_width > $circle_half_size) {
            $circle_radius = $circle_half_size / 2;
            $circle_prline_width = $circle_half_size;
        }

        if ($circle_line_width > $circle_half_size) {
            $circle_radius = $circle_half_size / 2;
            $circle_line_width = $circle_half_size;
        }

        $circle_perimeter = 2 * M_PI * $circle_radius;
        $circle_offset = $circle_perimeter - (($circle_perimeter / 100) * $percent);

        $circle_options = [
            'circleSize' => $circle_size,
            'circleViewbox' => $circle_viewbox,
            'circleRadius' => $circle_radius,
            'circleLineWidth' => $circle_line_width,
            'circlePrlineWidth' => $circle_prline_width,
            'circleOffset' => $circle_offset,
            'circleDasharray' => $circle_perimeter,
        ];

        $this->add_render_attribute('king-addons-prbar-circle', [
            'class' => 'king-addons-prbar-circle',
            'data-circle-options' => wp_json_encode($circle_options),
        ]);

        ?>
        <div <?php echo $this->get_render_attribute_string('king-addons-prbar-circle'); ?>>
            <svg class="king-addons-prbar-circle-svg" viewBox="<?php echo esc_attr($circle_viewbox); ?>">
                <?php if ('gradient' === $settings['circle_prline_bg_type']) : ?>
                    <?php $circle_stocke_bg = 'url( #king-addons-prbar-circle-gradient-' . esc_attr($this->get_id()) . ' )'; ?>
                    <linearGradient id="king-addons-prbar-circle-gradient-<?php echo esc_attr($this->get_id()); ?>"
                                    gradientTransform="rotate(<?php echo esc_html($settings['circle_prline_grad_angle']['size']); ?> 0.5 0.5)"
                                    gradientUnits="objectBoundingBox" x1="-0.5" y1="0.5" x2="1.5" y2="0.5">
                        <stop offset="0%"
                              stop-color="<?php echo esc_attr($settings['circle_prline_bg_color_a']); ?>"></stop>
                        <stop offset="100%"
                              stop-color="<?php echo esc_attr($settings['circle_prline_bg_color_b']); ?>"></stop>
                    </linearGradient>
                <?php endif; ?>
                <circle class="king-addons-prbar-circle-line"
                        cx="<?php echo esc_attr($circle_half_size); ?>"
                        cy="<?php echo esc_attr($circle_half_size); ?>"
                        r="<?php echo esc_attr($circle_radius); ?>"
                        stroke-width="<?php echo esc_attr($circle_line_width); ?>"
                />
                <circle class="king-addons-prbar-circle-prline king-addons-animation-timing-<?php echo esc_attr($settings['anim_timing']); ?>"
                        cx="<?php echo esc_attr($circle_half_size); ?>"
                        cy="<?php echo esc_attr($circle_half_size); ?>"
                        r="<?php echo esc_attr($circle_radius); ?>"
                        stroke="<?php echo esc_attr($circle_stocke_bg); ?>"
                        fill="none"
                        stroke-width="<?php echo esc_attr($circle_prline_width); ?>"
                        style="stroke-dasharray: <?php echo esc_attr($circle_perimeter); ?>; stroke-dashoffset: <?php echo esc_attr($circle_perimeter); ?>;"
                />
            </svg>
            <?php $this->render_progress_bar_content('inside'); ?>
        </div>
        <?php

        $this->render_progress_bar_content('outside');

    }

    protected function render_progress_bar_content($position)
    {
        /** @noinspection DuplicatedCode */
        $settings = $this->get_settings();
        $is_counter = ('yes' === $settings['counter_switcher'] && $position === $settings['counter_position']);
        $is_title = ('' !== $settings['title'] && $position === $settings['title_position']);
        $is_subtitle = ('' !== $settings['subtitle'] && $position === $settings['title_position']);
        $do_follow = ('yes' === $this->get_settings_for_display('counter_follow_line') && 'inside' === $settings['counter_position']);

        if (!$is_title && !$is_subtitle && !$is_counter) {
            return;
        }

        echo '<div class="king-addons-prbar-content elementor-clearfix">';

        if ($is_title || $is_subtitle) {
            echo '<div class="king-addons-prbar-title-wrap">';
            if ($is_title) {
                echo '<div class="king-addons-prbar-title">' . esc_html($settings['title']) . '</div>';
            }
            if ($is_subtitle) {
                echo '<div class="king-addons-prbar-subtitle">' . esc_html($settings['subtitle']) . '</div>';
            }
            echo '</div>';
        }

        if ($is_counter && !$do_follow) {
            $this->render_progress_bar_counter();
        }

        echo '</div>';
    }

    protected function render_progress_bar_counter()
    {
        $settings = $this->get_settings();
        ?>
        <div class="king-addons-prbar-counter">
            <?php
            foreach (['counter_prefix' => 'value-prefix', 'counter_value' => 'value', 'counter_suffix' => 'value-suffix'] as $key => $class) {
                if (!empty($settings[$key])) {
                    echo sprintf(
                        '<span class="king-addons-prbar-counter-%s">%s</span>',
                        esc_attr($class),
                        $key === 'counter_value' ? '0' : esc_html($settings[$key])
                    );
                }
            }
            ?>
        </div>
        <?php
    }

    protected function render_progress_bar_hr_line()
    {
        $settings = $this->get_settings();

        $this->render_progress_bar_content('outside');

        echo '<div class="king-addons-prbar-hr-line"><div class="king-addons-prbar-hr-line-inner king-addons-animation-timing-' . esc_attr($settings['anim_timing']) . '">';

        if ('yes' === $this->get_settings_for_display('counter_follow_line') && 'inside' === $settings['counter_position']) {
            $this->render_progress_bar_counter();
        }

        echo '</div>';
        $this->render_progress_bar_content('inside');
        echo '</div>';
    }

    public function render_progress_bar_vr_line()
    {
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $counter_percent = round(($settings['counter_value'] / $settings['max_value']) * 100);

        $this->add_render_attribute('king-addons-progress-bar', [
            'class' => 'king-addons-progress-bar',
            'data-options' => wp_json_encode([
                'counterValue' => $settings['counter_value'],
                'counterValuePercent' => $counter_percent,
                'counterSeparator' => $settings['counter_separator'],
                'animDuration' => $settings['anim_duration'] * 1000,
                'animDelay' => $settings['anim_delay'] * 1000,
                'loop' => $settings['anim_loop'] ?? '',
                'loopDelay' => $settings['anim_loop_delay'] ?? '',
            ]),
        ]);

        $layout = (!king_addons_freemius()->can_use_premium_code__premium_only() && $settings['layout'] === 'pro-vr') ? 'hr-line' : $settings['layout'];

        echo '<div ' . $this->get_render_attribute_string('king-addons-progress-bar') . '>';

        switch ($layout) {
            case 'circle':
                $this->render_progress_bar_circle($counter_percent);
                break;
            case 'hr-line':
                $this->render_progress_bar_hr_line();
                break;
            case 'vr-line':
                $this->render_progress_bar_vr_line();
                break;
        }

        echo '</div>';
    }
}