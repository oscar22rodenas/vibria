<?php

namespace King_Addons;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;

if (!defined('ABSPATH')) {
    exit;
}

class Mailchimp extends Widget_Base
{

    public function get_name()
    {
        return 'king-addons-mailchimp';
    }

    public function get_title()
    {
        return esc_html__('Mailchimp', 'king-addons');
    }

    public function get_icon()
    {
        return 'king-addons-icon king-addons-mailchimp';
    }

    public function get_categories()
    {
        return ['king-addons'];
    }

    public function get_keywords()
    {
        return ['king addons', 'king', 'addons', 'kingaddons', 'king-addons', 'subscribe', 'subscription form',
            'subscription', 'subscribe', 'form', 'forms', 'signin',
            'signup', 'sign', 'sign up', 'sign in', 'register',
            'email subscription', 'sing up form', 'signup form', 'newsletter',
            'mailchimp', 'mail', 'email', 'chimp'];
    }

    public function get_script_depends()
    {
        return [KING_ADDONS_ASSETS_UNIQUE_KEY . '-mailchimp-script'];
    }

    public function get_style_depends()
    {
        return [KING_ADDONS_ASSETS_UNIQUE_KEY . '-mailchimp-style'];
    }

    public function get_custom_help_url()
    {
        return 'https://kingaddons.com/';
    }

    public function add_control_clear_fields_on_submit()
    {
        $this->add_control(
            'clear_fields_on_submit',
            [
                'label' => sprintf(__('Clear Fields On Submit %s', 'king-addons'), '<i class="eicon-pro-icon"></i>'),
                'type' => Controls_Manager::SWITCHER,
                'classes' => 'king-addons-pro-control'
            ]
        );
    }

    public function add_control_extra_fields()
    {
        $this->add_control(
            'extra_fields',
            [
                'label' => sprintf(__('Show Extra Fields %s', 'king-addons'), '<i class="eicon-pro-icon"></i>'),
                'type' => Controls_Manager::SWITCHER,
                'separator' => 'before',
                'classes' => 'king-addons-pro-control'
            ]
        );
    }

    public function add_control_name_label()
    {
    }

    public function add_control_name_placeholder()
    {
    }

    public function add_control_last_name_label()
    {
    }

    public function add_control_last_name_placeholder()
    {
    }

    public function add_control_phone_number_label_and_placeholder()
    {
    }

    protected function register_controls()
    {


        $this->start_controls_section(
            'section_mailchimp_settings',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Settings', 'king-addons'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );


        $this->add_control(
            'mailchimp_audience',
            [
                'label' => esc_html__('Select Audience', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'def',
                'options' => Core::getMailchimpLists(),
            ]
        );

        if ('' == get_option('king_addons_mailchimp_api_key')) {
            /** @noinspection HtmlUnknownTarget */
            $this->add_control(
                'mailchimp_key_notice',
                [
                    'type' => Controls_Manager::RAW_HTML,
                    'raw' => sprintf(__('Navigate to <strong><a href="%s" target="_blank">Dashboard > %s > Integrations</a></strong> to set up <strong>MailChimp API Key</strong>.', 'king-addons'), admin_url('admin.php?page=king-addons-settings'), Core::getPluginName()),
                    'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
                ]
            );
        }

        $this->end_controls_section();


        $this->start_controls_section(
            'section_mailchimp_general',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('General', 'king-addons'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control_clear_fields_on_submit();

        $this->add_control(
            'show_form_header',
            [
                'label' => esc_html__('Show Form Header', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
            ]
        );

        $this->add_control(
            'form_title',
            [
                'label' => esc_html__('Form Title', 'king-addons'),
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => 'Form Title',
                'condition' => [
                    'show_form_header' => 'yes',
                ]
            ]
        );

        $this->add_control(
            'form_description',
            [
                'label' => esc_html__('Form Description', 'king-addons'),
                'type' => Controls_Manager::TEXTAREA,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => 'Subscribe to our newsletter.',
                'condition' => [
                    'show_form_header' => 'yes',
                ]
            ]
        );

        $this->add_control(
            'form_icon',
            [
                'label' => esc_html__('Select Icon', 'king-addons'),
                'type' => Controls_Manager::ICONS,
                'skin' => 'inline',
                'label_block' => false,
                'default' => [
                    'value' => 'far fa-envelope',
                    'library' => 'fa-regular',
                ],
                'condition' => [
                    'show_form_header' => 'yes',
                ]
            ]
        );

        $this->add_control(
            'form_icon_display',
            [
                'label' => esc_html__('Icon Position', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'top',
                'options' => [
                    'top' => esc_html__('Top', 'king-addons'),
                    'left' => esc_html__('Left', 'king-addons'),
                ],
                'selectors_dictionary' => [
                    'top' => 'display: block;',
                    'left' => 'display: inline; margin-right: 5px;'
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-mailchimp-header i' => '{{VALUE}}',
                    '{{WRAPPER}} .king-addons-mailchimp-header svg' => '{{VALUE}}'
                ],
                'condition' => [
                    'show_form_header' => 'yes',
                ]
            ]
        );

        $this->add_control(
            'email_label',
            [
                'label' => esc_html__('Email Label', 'king-addons'),
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => 'Email',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'email_placeholder',
            [
                'label' => esc_html__('Email Placeholder', 'king-addons'),
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => 'example@example.com',
            ]
        );

        if (!king_addons_freemius()->can_use_premium_code__premium_only()) {
            $this->add_control(
                'mailchimp_pro_notice',
                [
                    'type' => Controls_Manager::RAW_HTML,
                    'raw' => 'Name and Last Name Field options are available in the <strong><a href="https://kingaddons.com/pricing/?utm_source=kng-module-mailchimp-settings-upgrade-pro&utm_medium=plugin&utm_campaign=kng" target="_blank">Pro version</a></strong>',

                    'content_classes' => 'king-addons-pro-notice',
                ]
            );
        }

        $this->add_control(
            'subscribe_btn_text',
            [
                'label' => esc_html__('Button Text', 'king-addons'),
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => 'Subscribe',
            ]
        );

        $this->add_control(
            'subscribe_button_loading_text',
            [
                'label' => esc_html__('Button Loading Text', 'king-addons'),
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => 'Subscribing...',
                'separator' => 'after'
            ]
        );

        $this->add_control(
            'success_message',
            [
                'label' => esc_html__('Success Message', 'king-addons'),
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => 'You have been successfully Subscribed!',
            ]
        );

        $this->add_control(
            'error_message',
            [
                'label' => esc_html__('Error Message', 'king-addons'),
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => 'Ops! Something went wrong, please try again.',
            ]
        );

        $this->add_control_extra_fields();

        $this->add_control_name_label();

        $this->add_control_name_placeholder();

        $this->add_control_last_name_label();

        $this->add_control_last_name_placeholder();

        $this->add_control_phone_number_label_and_placeholder();

        $this->end_controls_section();

        Core::renderProFeaturesSection($this, '', Controls_Manager::RAW_HTML, 'mailchimp', [
            'Add Extra Fields - Name, Last Name, Phone Number',
            'Clear Fields After Form Submission'
        ]);

        $this->start_controls_section(
            'section_style_container',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Container', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            ]
        );

        $this->add_control(
            'container_align',
            [
                'label' => esc_html__('Alignment', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'hr',
                'options' => [
                    'hr' => esc_html__('Horizontal', 'king-addons'),
                    'vr' => esc_html__('Vertical', 'king-addons'),
                ],
                'prefix_class' => 'king-addons-mailchimp-layout-',
                'render_type' => 'template',
                'separator' => 'after'
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'container_background',
                'label' => esc_html__('Background', 'king-addons'),
                'types' => ['classic', 'gradient'],
                'fields_options' => [
                    'color' => [
                        'default' => '',
                    ],
                ],
                'selector' => '{{WRAPPER}} .king-addons-mailchimp-form'
            ]
        );

        $this->add_control(
            'container_border_color',
            [
                'label' => esc_html__('Border Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#E8E8E8',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-mailchimp-form' => 'border-color: {{VALUE}}',
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'container_box_shadow',
                'selector' => '{{WRAPPER}} .king-addons-mailchimp-form',
            ]
        );

        $this->add_control(
            'container_border_type',
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
                    '{{WRAPPER}} .king-addons-mailchimp-form' => 'border-style: {{VALUE}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'container_border_width',
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
                    '{{WRAPPER}} .king-addons-mailchimp-form' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'container_border_type!' => 'none',
                ],
            ]
        );

        $this->add_responsive_control(
            'container_padding',
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
                    '{{WRAPPER}} .king-addons-mailchimp-form' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'container_radius',
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
                    '{{WRAPPER}} .king-addons-mailchimp-form' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();


        $this->start_controls_section(
            'section_style_header',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Form Header', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            ]
        );

        $this->add_control(
            'header_align',
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
                ],
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-mailchimp-header' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'header_align_divider',
            [
                'type' => Controls_Manager::DIVIDER,
                'style' => 'thick',
            ]
        );

        $this->add_control(
            'header_icon_color',
            [
                'label' => esc_html__('Icon Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#5B03FF',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-mailchimp-header i' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .king-addons-mailchimp-header svg' => 'fill: {{VALUE}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'header_icon_size',
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
                    'size' => 28,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-mailchimp-header i' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .king-addons-mailchimp-header svg' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
                ],
                'separator' => 'after'
            ]
        );

        $this->add_control(
            'header_title_color',
            [
                'label' => esc_html__('Title Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#424242',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-mailchimp-header h3' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'header_title_typography',
                'selector' => '{{WRAPPER}} .king-addons-mailchimp-header h3',
            ]
        );

        $this->add_control(
            'header_description_color',
            [
                'label' => esc_html__('Description Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#606060',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-mailchimp-header p' => 'color: {{VALUE}}',
                ],
                'separator' => 'before'
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'header_description_typography',
                'selector' => '{{WRAPPER}} .king-addons-mailchimp-header p',
            ]
        );

        $this->add_responsive_control(
            'header_title_distance',
            [
                'label' => esc_html__('Title Bottom Distance', 'king-addons'),
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
                    'size' => 10,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-mailchimp-header h3' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
                'separator' => 'before'
            ]
        );

        $this->add_responsive_control(
            'header_desc_distance',
            [
                'label' => esc_html__('Description Bottom Distance', 'king-addons'),
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
                    '{{WRAPPER}} .king-addons-mailchimp-header' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
                'separator' => 'before'
            ]
        );

        $this->end_controls_section();


        $this->start_controls_section(
            'section_style_labels',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Labels', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            ]
        );

        $this->add_control(
            'labels_color',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#818181',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-mailchimp-fields label' => 'color: {{VALUE}}',
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'labels_typography',
                'selector' => '{{WRAPPER}} .king-addons-mailchimp-fields label',
            ]
        );

        $this->add_responsive_control(
            'labels_spacing',
            [
                'label' => esc_html__('Spacing', 'king-addons'),
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
                    'size' => 4,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-mailchimp-fields label' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
                'separator' => 'before'
            ]
        );

        $this->end_controls_section();


        $this->start_controls_section(
            'section_style_inputs',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Fields', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            ]
        );

        $this->start_controls_tabs('tabs_forms_inputs_style');

        $this->start_controls_tab(
            'tab_inputs_normal',
            [
                'label' => esc_html__('Normal', 'king-addons'),
            ]
        );

        $this->add_control(
            'input_color',
            [
                'label' => esc_html__('Text Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#474747',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-mailchimp-fields input' => 'color: {{VALUE}}',
                ]
            ]
        );

        $this->add_control(
            'input_placeholder_color',
            [
                'label' => esc_html__('Placeholder Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ADADAD',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-mailchimp-fields input::placeholder' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'input_background_color',
            [
                'label' => esc_html__('Background Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#FFFFFF',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-mailchimp-fields input' => 'background-color: {{VALUE}}',
                ]
            ]
        );

        $this->add_control(
            'input_border_color',
            [
                'label' => esc_html__('Border Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#e8e8e8',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-mailchimp-fields input' => 'border-color: {{VALUE}}',
                ]
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_inputs_hover',
            [
                'label' => esc_html__('Focus', 'king-addons'),
            ]
        );

        $this->add_control(
            'input_color_fc',
            [
                'label' => esc_html__('Text Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#333333',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-mailchimp-fields input:focus' => 'color: {{VALUE}}',
                ]
            ]
        );

        $this->add_control(
            'input_placeholder_color_fc',
            [
                'label' => esc_html__('Placeholder Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#FFFFFF',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-mailchimp-fields input:focus::placeholder' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'input_background_color_fc',
            [
                'label' => esc_html__('Background Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#FFFFFF',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-mailchimp-fields input:focus' => 'background-color: {{VALUE}}',
                ]
            ]
        );

        $this->add_control(
            'input_border_color_fc',
            [
                'label' => esc_html__('Border Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#e8e8e8',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-mailchimp-fields input:focus' => 'border-color: {{VALUE}}',
                ]
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'input_box_shadow',
                'selector' => '{{WRAPPER}} .king-addons-mailchimp-fields input',
                'separator' => 'after',
            ]
        );

        $this->add_control(
            'input_transition_duration',
            [
                'label' => esc_html__('Transition Duration (seconds)', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'default' => 0.1,
                'min' => 0,
                'max' => 5,
                'step' => 0.1,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-mailchimp-fields input' => 'transition-duration: {{VALUE}}s',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'input_typography',
                'selector' => '{{WRAPPER}} .king-addons-mailchimp-fields input',
            ]
        );

        $this->add_responsive_control(
            'input_height',
            [
                'label' => esc_html__('Input Height', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 30,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 45,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-mailchimp-fields input' => 'height: {{SIZE}}px; line-height: {{SIZE}}px;',
                ],
                'separator' => 'before'
            ]
        );

        $this->add_responsive_control(
            'input_spacing',
            [
                'label' => esc_html__('Gutter', 'king-addons'),
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
                    'size' => 10,
                ],
                'selectors' => [
                    '{{WRAPPER}}.king-addons-mailchimp-layout-vr .king-addons-mailchimp-email, {{WRAPPER}}.king-addons-mailchimp-layout-vr .king-addons-mailchimp-first-name, {{WRAPPER}}.king-addons-mailchimp-layout-vr .king-addons-mailchimp-last-name, {{WRAPPER}}.king-addons-mailchimp-layout-vr .king-addons-mailchimp-phone-number' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}}.king-addons-mailchimp-layout-hr .king-addons-mailchimp-email, {{WRAPPER}}.king-addons-mailchimp-layout-hr .king-addons-mailchimp-first-name, {{WRAPPER}}.king-addons-mailchimp-layout-hr .king-addons-mailchimp-last-name, {{WRAPPER}}.king-addons-mailchimp-layout-hr .king-addons-mailchimp-phone-number' => 'margin-right: {{SIZE}}{{UNIT}};',
                ],
                'separator' => 'after'
            ]
        );

        $this->add_control(
            'input_border_type',
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
                    '{{WRAPPER}} .king-addons-mailchimp-fields input' => 'border-style: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'input_border_width',
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
                    '{{WRAPPER}} .king-addons-mailchimp-fields input' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'input_border_type!' => 'none',
                ],
            ]
        );

        $this->add_responsive_control(
            'input_padding',
            [
                'label' => esc_html__('Padding', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'default' => [
                    'top' => 0,
                    'right' => 15,
                    'bottom' => 0,
                    'left' => 15,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-mailchimp-fields input' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'input_radius',
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
                    '{{WRAPPER}} .king-addons-mailchimp-fields input' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();


        $this->start_controls_section(
            'section_style_subscribe_btn',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Button', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            ]
        );

        $this->add_control(
            'subscribe_btn_align',
            [
                'label' => esc_html__('Alignment', 'king-addons'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'flex-start' => [
                        'title' => esc_html__('Left', 'king-addons'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'king-addons'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'flex-end' => [
                        'title' => esc_html__('Right', 'king-addons'),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'left',
                'selectors' => [
                    '{{WRAPPER}}.king-addons-mailchimp-layout-vr .king-addons-mailchimp-subscribe' => 'align-self: {{VALUE}};',
                ],
                'condition' => [
                    'container_align' => 'vr'
                ]
            ]
        );

        $this->add_control(
            'subscribe_btn_divider1',
            [
                'type' => Controls_Manager::DIVIDER,
                'style' => 'thick',
                'condition' => [
                    'container_align' => 'vr'
                ]
            ]
        );

        $this->start_controls_tabs('tabs_subscribe_btn_style');

        $this->start_controls_tab(
            'tab_subscribe_btn_normal',
            [
                'label' => esc_html__('Normal', 'king-addons'),
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'subscribe_btn_bg_color',
                'label' => esc_html__('Background', 'king-addons'),
                'types' => ['classic', 'gradient'],
                'fields_options' => [
                    'color' => [
                        'default' => '#5B03FF',
                    ],
                ],
                'selector' => '{{WRAPPER}} .king-addons-mailchimp-subscribe-btn'
            ]
        );

        $this->add_control(
            'subscribe_btn_color',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-mailchimp-subscribe-btn' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'subscribe_btn_border_color',
            [
                'label' => esc_html__('Border Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#E6E2E2',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-mailchimp-subscribe-btn' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'subscribe_btn_box_shadow',
                'selector' => '{{WRAPPER}} .king-addons-mailchimp-subscribe-btn',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_subscribe_btn_hover',
            [
                'label' => esc_html__('Hover', 'king-addons'),
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'subscribe_btn_bg_color_hr',
                'label' => esc_html__('Background', 'king-addons'),
                'types' => ['classic', 'gradient'],
                'fields_options' => [
                    'color' => [
                        'default' => '#4D02D8',
                    ],
                ],
                'selector' => '{{WRAPPER}} .king-addons-mailchimp-subscribe-btn:hover'
            ]
        );

        $this->add_control(
            'subscribe_btn_color_hr',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#FFFFFF',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-mailchimp-subscribe-btn:hover' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'subscribe_btn_border_color_hr',
            [
                'label' => esc_html__('Border Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-mailchimp-subscribe-btn:hover' => 'border-color: {{VALUE}}',
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'subscribe_btn_box_shadow_hr',
                'selector' => '{{WRAPPER}} .king-addons-mailchimp-subscribe-btn:hover',
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_control(
            'subscribe_btn_divider2',
            [
                'type' => Controls_Manager::DIVIDER,
                'style' => 'thick',
            ]
        );

        $this->add_control(
            'subscribe_btn_transition_duration',
            [
                'label' => esc_html__('Transition Duration (seconds)', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'default' => 0.1,
                'min' => 0,
                'max' => 5,
                'step' => 0.1,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-mailchimp-subscribe-btn' => 'transition-duration: {{VALUE}}s',
                ],
                'separator' => 'after',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'subscribe_btn_typography',
                'selector' => '{{WRAPPER}} .king-addons-mailchimp-subscribe-btn'
            ]
        );

        $this->add_responsive_control(
            'subscribe_btn_width',
            [
                'label' => esc_html__('Width', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 30,
                        'max' => 300,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 130,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-mailchimp-subscribe' => 'width: {{SIZE}}{{UNIT}};',
                ],
                'separator' => 'before'
            ]
        );

        $this->add_responsive_control(
            'subscribe_btn_height',
            [
                'label' => esc_html__('Height', 'king-addons'),
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
                    'size' => 45,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-mailchimp-subscribe-btn' => 'height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'subscribe_btn_spacing',
            [
                'label' => esc_html__('Top Distance', 'king-addons'),
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
                    '{{WRAPPER}}.king-addons-mailchimp-layout-vr .king-addons-mailchimp-subscribe-btn' => 'margin-top: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'container_align' => 'vr'
                ]
            ]
        );

        $this->add_control(
            'subscribe_btn_border_type',
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
                    '{{WRAPPER}} .king-addons-mailchimp-subscribe-btn' => 'border-style: {{VALUE}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'subscribe_btn_border_width',
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
                    '{{WRAPPER}} .king-addons-mailchimp-subscribe-btn' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'subscribe_btn_border_type!' => 'none',
                ],
            ]
        );

        $this->add_control(
            'subscribe_btn_radius',
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
                    '{{WRAPPER}} .king-addons-mailchimp-subscribe-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->end_controls_section();


        $this->start_controls_section(
            'section_style_message',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Message', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            ]
        );

        $this->add_control(
            'success_message_color',
            [
                'label' => esc_html__('Success Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#333333',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-mailchimp-success-message' => 'color: {{VALUE}}',
                ]
            ]
        );

        $this->add_control(
            'error_message_color',
            [
                'label' => esc_html__('Error Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#FF348B',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-mailchimp-error-message' => 'color: {{VALUE}}',
                ]
            ]
        );

        $this->add_control(
            'message_color_bg',
            [
                'label' => esc_html__('Background Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#FFFFFF',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-mailchimp-message' => 'background-color: {{VALUE}}',
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'message_typography',
                'selector' => '{{WRAPPER}} .king-addons-mailchimp-message',
            ]
        );

        $this->add_responsive_control(
            'message_padding',
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
                    '{{WRAPPER}} .king-addons-mailchimp-message' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'message_spacing',
            [
                'label' => esc_html__('Spacing', 'king-addons'),
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
                    'size' => 10,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-mailchimp-message' => 'margin-top: {{SIZE}}{{UNIT}};',
                ],
                'separator' => 'before'
            ]
        );

        $this->end_controls_section();
    }

    public function render_pro_element_extra_fields()
    {
    }

    protected function render()
    {
        $settings = $this->get_settings();

        // Conditionally set the clear_fields_on_submit value
        $clear_fields_on_submit = king_addons_freemius()->can_use_premium_code__premium_only()
            ? esc_attr($settings['clear_fields_on_submit'])
            : '';

        // Pre-escape frequently used values
        $id = esc_attr($this->get_id());
        $audience = esc_attr($settings['mailchimp_audience']);
        $form_icon_val = !empty($settings['form_icon']['value']) ? esc_attr($settings['form_icon']['value']) : '';
        $form_icon = $form_icon_val ? "<i class=\"$form_icon_val\"></i>" : '';
        $email_label = !empty($settings['email_label']) ? '<label>' . esc_html($settings['email_label']) . '</label>' : '';
        ?>

        <form
                class="king-addons-mailchimp-form"
                id="king-addons-mailchimp-form-<?php echo $id; ?>"
                method="POST"
                data-list-id="<?php echo $audience; ?>"
                data-clear-fields="<?php echo $clear_fields_on_submit; ?>"
        >
            <?php if ('yes' === $settings['show_form_header']) : ?>
                <div class="king-addons-mailchimp-header">
                    <h3>
                        <?php
                        echo $form_icon;
                        echo esc_html($settings['form_title']);
                        ?>
                    </h3>
                    <p><?php echo wp_kses($settings['form_description'], ['br' => [], 'em' => [], 'strong' => []]); ?></p>
                </div>
            <?php endif; ?>

            <div class="king-addons-mailchimp-fields">
                <div class="king-addons-mailchimp-email">
                    <?php echo $email_label; ?>
                    <input
                            type="email"
                            name="king_addons_mailchimp_email"
                            placeholder="<?php echo esc_attr($settings['email_placeholder']); ?>"
                            required
                    />
                </div>

                <?php $this->render_pro_element_extra_fields(); ?>

                <div class="king-addons-mailchimp-subscribe">
                    <button
                            type="submit"
                            id="king-addons-subscribe-<?php echo $id; ?>"
                            class="king-addons-mailchimp-subscribe-btn"
                            data-loading="<?php echo esc_attr($settings['subscribe_button_loading_text']); ?>"
                    >
                        <?php echo esc_html($settings['subscribe_btn_text']); ?>
                    </button>
                </div>
            </div>

            <div class="king-addons-mailchimp-message">
            <span class="king-addons-mailchimp-success-message">
                <?php echo esc_html($settings['success_message']); ?>
            </span>
                <span class="king-addons-mailchimp-error-message">
                <?php echo esc_html($settings['error_message']); ?>
            </span>
            </div>
        </form>
        <?php
    }
}