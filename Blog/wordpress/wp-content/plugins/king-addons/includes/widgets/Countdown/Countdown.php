<?php

namespace King_Addons;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Plugin;
use Elementor\Widget_Base;
use Elementor\Utils;

if (!defined('ABSPATH')) {
    exit;
}

class Countdown extends Widget_Base
{

    public function get_name()
    {
        return 'king-addons-countdown';
    }

    public function get_title()
    {
        return esc_html__('Countdown & Timer', 'king-addons');
    }

    public function get_icon()
    {
        return 'king-addons-icon king-addons-countdown';
    }

    public function get_categories()
    {
        return ['king-addons'];
    }

    public function get_keywords()
    {
        return ['king addons', 'king', 'addons', 'kingaddons', 'king-addons', 'evergreen', 'countdown', 'timer',
            'time', 'counter', 'clock', 'date', 'evergreen countdown timer'];
    }

    public function get_script_depends()
    {
        return [KING_ADDONS_ASSETS_UNIQUE_KEY . '-countdown-script'];
    }

    public function get_style_depends()
    {
        return [
            KING_ADDONS_ASSETS_UNIQUE_KEY . '-countdown-style',
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

    public function has_widget_inner_wrapper(): bool {
        return true;
    }

    public function add_control_countdown_type()
    {
        $this->add_control(
            'countdown_type',
            [
                'label' => esc_html__('Type', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'due-date',
                'options' => [
                    'due-date' => esc_html__('Due Date', 'king-addons'),
                    'pro-eg' => esc_html__('Evergreen (Pro)', 'king-addons'),
                ],
            ]
        );
    }

    public function add_control_evergreen_days()
    {
    }

    public function add_control_evergreen_hours()
    {
    }

    public function add_control_evergreen_minutes()
    {
    }

    public function add_control_evergreen_seconds()
    {
    }

    public function add_control_evergreen_show_again_delay()
    {
    }

    public function add_control_evergreen_stop_after_date()
    {
        $this->add_control(
            'evergreen_stop_after_date',
            [
                'label' => sprintf(__('Stop Showing After Date %s', 'king-addons'), '<i class="eicon-pro-icon"></i>'),
                'type' => Controls_Manager::SWITCHER,
                'separator' => 'before',
                'classes' => 'king-addons-pro-control'
            ]
        );
    }

    public function add_control_evergreen_stop_after_date_select()
    {
    }

    protected function register_controls()
    {


        $this->start_controls_section(
            'section_countdown',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('General', 'king-addons'),
            ]
        );


        $this->add_control_countdown_type();


        Core::renderUpgradeProNotice($this, Controls_Manager::RAW_HTML, 'countdown', 'countdown_type', ['pro-eg']);

        $due_date_default = date(
            'Y-m-d H:i', strtotime('+1 month') + (get_option('gmt_offset') * HOUR_IN_SECONDS)
        );

        $this->add_control(
            'due_date',
            [
                'label' => esc_html__('Due Date', 'king-addons'),
                'type' => Controls_Manager::DATE_TIME,
                'default' => $due_date_default,
                'description' => sprintf(
                    esc_html__('Date set according to your timezone: %s.', 'king-addons'),
                    Utils::get_timezone_string()
                ),
                'dynamic' => [
                    'active' => true,
                ],
                'separator' => 'before',
                'condition' => [
                    'countdown_type' => 'due-date',
                ],
            ]
        );

        $this->add_control_evergreen_days();

        $this->add_control_evergreen_hours();

        $this->add_control_evergreen_minutes();

        $this->add_control_evergreen_seconds();

        $this->add_control_evergreen_show_again_delay();

        $this->add_control_evergreen_stop_after_date();

        $this->add_control_evergreen_stop_after_date_select();

        $this->add_control(
            'countdown_editor_notice',
            [
                'type' => Controls_Manager::RAW_HTML,
                'raw' => '<strong>Please Note:</strong> Countdown timer does not work in the Editor, please click on Preview Changes icon to see it in action.',
                'separator' => 'before',
                'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
            ]
        );

        $this->end_controls_section();


        $this->start_controls_section(
            'section_content',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Content', 'king-addons'),
            ]
        );

        $this->add_control(
            'show_days',
            [
                'label' => esc_html__('Show Days', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'show_hours',
            [
                'label' => esc_html__('Show Hours', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes'
            ]
        );

        $this->add_control(
            'show_minutes',
            [
                'label' => esc_html__('Show Minutes', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes'
            ]
        );

        $this->add_control(
            'show_seconds',
            [
                'label' => esc_html__('Show Seconds', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes'
            ]
        );

        $this->add_control(
            'show_labels',
            [
                'label' => esc_html__('Show Labels', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'labels_position',
            [
                'label' => esc_html__('Display', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'block',
                'options' => [
                    'block' => esc_html__('Block', 'king-addons'),
                    'inline' => esc_html__('Inline', 'king-addons'),
                ],
                'selectors_dictionary' => [
                    'inline' => 'inline-block',
                    'block' => 'block'
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-countdown-number' => 'display: {{VALUE}}',
                    '{{WRAPPER}} .king-addons-countdown-label' => 'display: {{VALUE}}',
                ],
                'separator' => 'after',
                'condition' => [
                    'show_labels' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'labels_days_singular',
            [
                'label' => esc_html__('Day', 'king-addons'),
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => 'Day',
                'condition' => [
                    'show_labels' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'labels_days_plural',
            [
                'label' => esc_html__('Days', 'king-addons'),
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => 'Days',
                'condition' => [
                    'show_labels' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'labels_hours_singular',
            [
                'label' => esc_html__('Hour', 'king-addons'),
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => 'Hour',
                'condition' => [
                    'show_labels' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'labels_hours_plural',
            [
                'label' => esc_html__('Hours', 'king-addons'),
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => 'Hours',
                'condition' => [
                    'show_labels' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'labels_minutes_singular',
            [
                'label' => esc_html__('Minute', 'king-addons'),
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => 'Minute',
                'condition' => [
                    'show_labels' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'labels_minutes_plural',
            [
                'label' => esc_html__('Minutes', 'king-addons'),
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => 'Minutes',
                'condition' => [
                    'show_labels' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'labels_seconds_plural',
            [
                'label' => esc_html__('Seconds', 'king-addons'),
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => 'Seconds',
                'condition' => [
                    'show_labels' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'show_separator',
            [
                'label' => esc_html__('Show Separators', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'separator' => 'before',
            ]
        );

        $this->end_controls_section();


        $this->start_controls_section(
            'section_actions',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Expire Actions', 'king-addons'),
            ]
        );

        $this->add_control(
            'timer_actions',
            [
                'label' => esc_html__('Actions After Timer Expires', 'king-addons'),
                'label_block' => true,
                'type' => Controls_Manager::SELECT2,
                'options' => [
                    'hide-timer' => esc_html__('Hide Timer', 'king-addons'),
                    'hide-element' => esc_html__('Hide Element', 'king-addons'),
                    'message' => esc_html__('Display Message', 'king-addons'),
                    'redirect' => esc_html__('Redirect', 'king-addons'),
                    'load-template' => esc_html__('Load Template', 'king-addons'),
                ],
                'multiple' => true,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'hide_element_selector',
            [
                'label' => esc_html__('CSS Selector to Hide Element', 'king-addons'),
                'label_block' => true,
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => '',
                'separator' => 'before',
                'condition' => [
                    'timer_actions' => 'hide-element',
                ],
            ]
        );

        $this->add_control(
            'display_message_text',
            [
                'label' => esc_html__('Display Message', 'king-addons'),
                'type' => Controls_Manager::WYSIWYG,
                'default' => '',
                'separator' => 'before',
                'condition' => [
                    'timer_actions' => 'message',
                ],
            ]
        );

        $this->add_control(
            'redirect_url',
            [
                'label' => esc_html__('Redirect URL', 'king-addons'),
                'label_block' => true,
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => '',
                'separator' => 'before',
                'condition' => [
                    'timer_actions' => 'redirect',
                ],
            ]
        );

        $this->add_control(
            'load_template',
            [
                'label' => esc_html__('Select Template', 'king-addons'),
                'type' => 'king-addons-ajax-select2',
                'options' => 'ajaxselect2/getElementorTemplates',
                'label_block' => true,
                'separator' => 'before',
                'condition' => [
                    'timer_actions' => 'load-template',
                ],
            ]
        );


        wp_reset_postdata();

        $this->end_controls_section();

        Core::renderProFeaturesSection($this, '', Controls_Manager::RAW_HTML, 'countdown', [
            'Evergreen Timer (Endless Timer)',
            'Stop Showing After Date',
        ]);

        $this->start_controls_section(
            'section_style_general',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('General', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'general_bg_color',
                'label' => esc_html__('Background', 'king-addons'),
                'types' => ['classic', 'gradient'],
                'fields_options' => [
                    'color' => [
                        'default' => '#5B03FF',
                    ],
                ],
                'selector' => '{{WRAPPER}} .king-addons-countdown-item'
            ]
        );

        $this->add_responsive_control(
            'general_width',
            [
                'label' => esc_html__('Width', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['%', 'px'],
                'range' => [
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                    'px' => [
                        'min' => 0,
                        'max' => 800,
                    ],
                ],
                'default' => [
                    'unit' => '%',
                    'size' => 100,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-countdown-wrap' => 'max-width: {{SIZE}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'general_gutter',
            [
                'label' => esc_html__('Gutter', 'king-addons'),
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
                    'size' => 20,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-countdown-item' => 'margin-left: calc({{SIZE}}px/2);margin-right: calc({{SIZE}}px/2);',
                ],
            ]
        );

        $this->add_responsive_control(
            'general_padding',
            [
                'label' => esc_html__('Padding', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'default' => [
                    'top' => 5,
                    'right' => 5,
                    'bottom' => 5,
                    'left' => 5,
                ],
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-countdown-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'general_border',
                'label' => esc_html__('Border', 'king-addons'),
                'fields_options' => [
                    'color' => [
                        'default' => '#E8E8E8',
                    ],
                    'width' => [
                        'default' => [
                            'top' => '1',
                            'right' => '1',
                            'bottom' => '1',
                            'left' => '1',
                            'isLinked' => true,
                        ],
                    ],
                ],
                'selector' => '{{WRAPPER}} .king-addons-countdown-item',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'general_border_radius',
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
                    '{{WRAPPER}} .king-addons-countdown-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'general_box_shadow_divider',
            [
                'type' => Controls_Manager::DIVIDER,
                'style' => 'thick',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'general_box_shadow',
                'selector' => '{{WRAPPER}} .king-addons-countdown-item',
            ]
        );

        $this->end_controls_section();


        $this->start_controls_section(
            'section_style_content',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Content', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'content_align',
            [
                'label' => esc_html__('Alignment', 'king-addons'),
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
                    '{{WRAPPER}} .king-addons-countdown-item' => 'text-align: {{VALUE}}',
                ],
                'separator' => 'after'
            ]
        );

        $this->add_control(
            'numbers_color',
            [
                'label' => esc_html__('Numbers Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-countdown-number' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'numbers_bg_color',
            [
                'label' => esc_html__('Numbers Background Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-countdown-number' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'numbers_typography',
                'selector' => '{{WRAPPER}} .king-addons-countdown-number',
            ]
        );

        $this->add_responsive_control(
            'numbers_padding',
            [
                'label' => esc_html__('Numbers Padding', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'default' => [
                    'top' => 40,
                    'right' => 10,
                    'bottom' => 10,
                    'left' => 10,
                ],
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-countdown-number' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'labels_color',
            [
                'label' => esc_html__('Labels Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-countdown-label' => 'color: {{VALUE}};',
                ],
                'separator' => 'before'
            ]
        );

        $this->add_control(
            'labels_bg_color',
            [
                'label' => esc_html__('Labels Background Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-countdown-label' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'labels_typography',
                'selector' => '{{WRAPPER}} .king-addons-countdown-label',
            ]
        );

        $this->add_responsive_control(
            'labels_padding',
            [
                'label' => esc_html__('Labels Padding', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'default' => [
                    'top' => 10,
                    'right' => 10,
                    'bottom' => 10,
                    'left' => 10,
                ],
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-countdown-label' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'separator_color',
            [
                'label' => esc_html__('Separator Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#5B03FF',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-countdown-separator span' => 'background: {{VALUE}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'separator_size',
            [
                'label' => esc_html__('Separator Size', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 50,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 6,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-countdown-separator span' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'show_separator' => 'yes',
                ],
            ]
        );

        $this->add_responsive_control(
            'separator_margin',
            [
                'label' => esc_html__('Dots Margin', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 50,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 12,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-countdown-separator span:first-child' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'show_separator' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'separator_circle',
            [
                'label' => esc_html__('Separator Border Radius', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'selectors_dictionary' => [
                    'yes' => '50%;',
                    '' => 'none'
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-countdown-separator span' => 'border-radius: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();


        $this->start_controls_section(
            'section_style_message',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Message', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'timer_actions' => 'message',
                ],
            ]
        );

        $this->add_responsive_control(
            'message_align',
            [
                'label' => esc_html__('Alignment', 'king-addons'),
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
                    '{{WRAPPER}} .king-addons-countdown-message' => 'text-align: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'message_color',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-countdown-message' => 'color: {{VALUE}};',
                ],
                'separator' => 'before'
            ]
        );

        $this->add_control(
            'message_bg_color',
            [
                'label' => esc_html__('Background Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-countdown-message' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'message_typography',
                'selector' => '{{WRAPPER}} .king-addons-countdown-message',
            ]
        );

        $this->add_responsive_control(
            'message_padding',
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
                    '{{WRAPPER}} .king-addons-countdown-message' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'message_top_distance',
            [
                'label' => esc_html__('Top Distance', 'king-addons'),
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
                    'size' => 20,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-countdown-message' => 'margin-top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

    }

    public function get_due_date_interval($date)
    {
        return strtotime($date) - (get_option('gmt_offset') * HOUR_IN_SECONDS);
    }

    public function get_evergreen_interval($settings)
    {
        // Returning '0' by default
        return '0';
    }

    public function get_countdown_attributes($settings)
    {
        // Force non-premium users into 'due-date' mode
        if (!king_addons_freemius()->can_use_premium_code__premium_only()) {
            $settings['countdown_type'] = 'due-date';
            $settings['evergreen_show_again_delay'] = '0';
        }

        $type = esc_attr($settings['countdown_type']);
        $showAgain = esc_attr($settings['evergreen_show_again_delay']);
        $actions = esc_attr($this->get_expired_actions_json($settings));
        $interval = ($type === 'evergreen')
            ? $this->get_evergreen_interval($settings)
            : $this->get_due_date_interval($settings['due_date']);

        return sprintf(
            ' data-type="%s" data-show-again="%s" data-actions="%s" data-interval="%s"',
            $type,
            $showAgain,
            $actions,
            esc_attr($interval)
        );
    }

    public function get_countdown_class($settings)
    {
        // Force non-premium users to disable "stop after date"
        if (!king_addons_freemius()->can_use_premium_code__premium_only()) {
            $settings['evergreen_stop_after_date'] = '';
            $settings['evergreen_stop_after_date_select'] = '';
        }

        $class = 'king-addons-countdown-wrap elementor-clearfix';

        if ($settings['evergreen_stop_after_date'] === 'yes') {
            $current_time = time() + get_option('gmt_offset') * HOUR_IN_SECONDS;
            if ($current_time > strtotime($settings['evergreen_stop_after_date_select'])) {
                $class = ' king-addons-hidden-element';
            }
        }

        return $class;
    }

    public function sanitize_no_js($input)
    {
        // Remove potential JS injection points
        $patterns = [
            '#<script(.*?)>(.*?)</script>#is',
            '#<iframe(.*?)>(.*?)</iframe>#is',
            '#on\w+="[^"]*"#i',
            "#on\w+='[^']*'#i",
            '#on\w+=\S+#i',
            '#style="[^"]*javascript:[^"]*"#i',
            "#style='[^']*javascript:[^']*'#i",
        ];

        foreach ($patterns as $pattern) {
            $input = preg_replace($pattern, '', $input);
        }

        return $input;
    }

    public function get_expired_actions_json($settings)
    {
        $actions = [];
        $allowed_html = [
            'a' => ['href' => [], 'title' => [], 'target' => []],
            'h1' => [], 'h2' => [], 'h3' => [], 'h4' => [], 'h5' => [], 'h6' => [],
            'b' => [], 'strong' => [], 'i' => [], 'em' => [], 'p' => [], 'br' => [],
            'ul' => [], 'ol' => [], 'li' => [], 'span' => [],
            'div' => ['class' => []],
            'img' => ['src' => [], 'alt' => [], 'width' => [], 'height' => []],
        ];

        if (!empty($settings['timer_actions'])) {
            foreach ($settings['timer_actions'] as $value) {
                switch ($value) {
                    case 'hide-timer':
                        $actions['hide-timer'] = '';
                        break;
                    case 'hide-element':
                        $actions['hide-element'] = $settings['hide_element_selector'];
                        break;
                    case 'message':
                        $actions['message'] = $this->sanitize_no_js(
                            wp_kses($settings['display_message_text'], $allowed_html)
                        );
                        break;
                    case 'redirect':
                        // Use '#' as fallback if `redirect_url` is empty
                        $actions['redirect'] = esc_url($settings['redirect_url'] ?: '#');
                        break;
                    case 'load-template':
                        $actions['load-template'] = $settings['load_template'];
                        break;
                }
            }
        }

        return json_encode($actions);
    }

    public function render_countdown_item($settings, $item)
    {
        $html = '<div class="king-addons-countdown-item">';
        $html .= '<span class="king-addons-countdown-number king-addons-countdown-' . esc_attr($item)
            . '" data-item="' . esc_attr($item) . '"></span>';

        if ($settings['show_labels'] === 'yes') {
            if ($item !== 'seconds') {
                $labels = [
                    'singular' => $settings['labels_' . $item . '_singular'],
                    'plural' => $settings['labels_' . $item . '_plural'],
                ];
                $html .= '<span class="king-addons-countdown-label" data-text="'
                    . esc_attr(json_encode($labels)) . '">'
                    . esc_html($settings['labels_' . $item . '_plural']) . '</span>';
            } else {
                $html .= '<span class="king-addons-countdown-label">'
                    . esc_html($settings['labels_' . $item . '_plural']) . '</span>';
            }
        }
        $html .= '</div>';

        if (!empty($settings['show_separator'])) {
            $html .= '<span class="king-addons-countdown-separator">'
                . '<span></span><span></span></span>';
        }

        echo $html;
    }

    public function render_countdown_items($settings)
    {
        // More concise iteration over possible segments
        $items = [
            'days' => $settings['show_days'],
            'hours' => $settings['show_hours'],
            'minutes' => $settings['show_minutes'],
            'seconds' => $settings['show_seconds'],
        ];

        foreach ($items as $item => $showIt) {
            if ($showIt) {
                $this->render_countdown_item($settings, $item);
            }
        }
    }

    public function load_elementor_template($settings)
    {
        if (Plugin::$instance->editor->is_edit_mode()) {
            return;
        }

        // Only load the template if 'redirect' isn't in actions and 'load-template' is
        if (!empty($settings['timer_actions'])
            && !in_array('redirect', $settings['timer_actions'], true)
            && in_array('load-template', $settings['timer_actions'], true)
        ) {
            echo Plugin::instance()->frontend->get_builder_content($settings['load_template']);
        }
    }

    protected function render()
    {
        $settings = $this->get_settings();
        echo '<div class="' . esc_attr($this->get_countdown_class($settings)) . '"'
            . $this->get_countdown_attributes($settings) . '>';
        $this->render_countdown_items($settings);
        echo '</div>';

        $this->load_elementor_template($settings);
    }
}