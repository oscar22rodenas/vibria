<?php

namespace King_Addons;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;
use Elementor\Widget_Base;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class Share_Buttons extends Widget_Base
{
    public function get_name(): string
    {
        return 'king-addons-share-buttons';
    }

    public function get_title(): string
    {
        return esc_html__('Share Buttons', 'king-addons');
    }

    public function get_icon(): string
    {
        return 'king-addons-icon king-addons-share-buttons';
    }

    public function get_style_depends(): array
    {
        return [KING_ADDONS_ASSETS_UNIQUE_KEY . '-share-buttons-style'];
    }

    public function get_categories(): array
    {
        return ['king-addons'];
    }

    public function get_keywords(): array
    {
        return ['social', 'share', 'sharing', 'network', 'facebook', 'twitter', 'youtube', 'linkedin', 'x social', 'buttons', 'button',
            'social sharing', 'sharing buttons', 'share buttons', 'king addons', 'king', 'addons', 'kingaddons', 'king-addons'];
    }

    public function get_custom_help_url(): string
    {
        return 'https://kingaddons.com/elementor/share-buttons/';
    }

    public function add_repeater_args_share_custom_label(): array
    {
        return [
            'type' => Controls_Manager::HIDDEN,
            'default' => ''
        ];
    }

    public function add_control_share_show_icon()
    {
    }

    public function add_control_share_columns()
    {
        $this->add_responsive_control(
            'share_columns',
            [
                'label' => esc_html__('Columns', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '0' => esc_html__('Auto', 'king-addons'),
                    '1' => esc_html__('1', 'king-addons'),
                    '2' => esc_html__('2', 'king-addons'),
                    'pro-3' => esc_html__('3 (Pro)', 'king-addons'),
                    'pro-4' => esc_html__('4 (Pro)', 'king-addons'),
                    'pro-5' => esc_html__('5 (Pro)', 'king-addons'),
                    'pro-6' => esc_html__('6 (Pro)', 'king-addons'),
                ],
                'default' => '0',
                'prefix_class' => 'elementor-grid%s-',
            ]
        );
    }

    public function add_control_share_show_label()
    {
    }

    public function add_control_share_icon_border_radius()
    {
    }

    protected function register_controls()
    {

        $this->start_controls_section(
            'section_share_general',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('General', 'king-addons'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'share_icon',
            [
                'label' => esc_html__('Network', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'fab fa-facebook-f' => 'Facebook',
                    'fab fa-x-twitter' => 'X (Twitter)',
                    'fab fa-linkedin-in' => 'LinkedIn',
                    'fab fa-pinterest-p' => 'Pinterest',
                    'fab fa-reddit' => 'Reddit',
                    'fab fa-whatsapp' => 'WhatsApp',
                    'fab fa-telegram' => 'Telegram',
                    'fab fa-vk' => 'VK',
                    'fab fa-odnoklassniki' => 'OK',
                    'fab fa-skype' => 'Skype',
                    'fab fa-tumblr' => 'Tumblr',
                    'fab fa-get-pocket' => 'Pocket',
                    'fab fa-digg' => 'Digg',
                    'fab fa-xing' => 'Xing',
                    'fas fa-envelope' => 'Email',
                    'fas fa-print' => 'Print',
                ],
                'default' => 'fab fa-reddit',
            ]
        );

        $repeater->add_control('share_custom_label', $this->add_repeater_args_share_custom_label());

        $repeater->add_control(
            'show_whatsapp_title',
            [
                'label' => esc_html__('Show Title', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'separator' => 'before',
                'default' => 'yes',
                'condition' => [
                    'share_icon' => 'fab fa-whatsapp'
                ]
            ]
        );

        $repeater->add_control(
            'show_whatsapp_excerpt',
            [
                'label' => esc_html__('Show Excerpt', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'condition' => [
                    'share_icon' => 'fab fa-whatsapp'
                ]
            ]
        );

        $this->add_control(
            'share_buttons',
            [
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'share_icon' => 'fab fa-facebook-f',
                    ],
                    [
                        'share_icon' => 'fab fa-x-twitter',
                    ],
                    [
                        'share_icon' => 'fab fa-linkedin-in',
                    ],
                ],
                'title_field' => 'Social Icon',
            ]
        );

        if (!king_addons_freemius()->can_use_premium_code__premium_only()) {
            $this->add_control(
                'share_repeater_pro_notice',
                [
                    'type' => Controls_Manager::RAW_HTML,
                    'raw' => 'More than 3 buttons are available<br> in the <strong><a href="https://kingaddons.com/pricing/?utm_source=kng-module-share-buttons-settings-upgrade-pro&utm_medium=plugin&utm_campaign=kng" target="_blank">Pro version</a></strong>',
                    'content_classes' => 'king-addons-pro-notice',
                ]
            );
        }

        $this->end_controls_section();

        $this->start_controls_section(
            'section_share_layout',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Layout', 'king-addons'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control_share_columns();

        Core::renderUpgradeProNotice($this, Controls_Manager::RAW_HTML, 'share-buttons', 'share_columns', ['pro-3', 'pro-4', 'pro-5', 'pro-6']);

        $this->add_control_share_show_icon();

        $this->add_control_share_show_label();

        $this->end_controls_section();

        Core::renderProFeaturesSection($this, '', Controls_Manager::RAW_HTML, 'share-buttons', [
            'Add Unlimited Social Icons',
            'Custom Social Media Label',
            'Layout Columns: 1,2,3,4,5,6',
            'Only Labels: Show/Hide Icon',
            'Only Icons: Show/Hide Label',
            'Advanced Styling Options',
        ]);

        $this->start_controls_section(
            'section_styles_share_layout',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Layout', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'share_gutter_hr',
            [
                'label' => esc_html__('Horizontal Gutter', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 10,
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 25,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}}:not(.elementor-grid-0) .elementor-grid' => 'grid-column-gap: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}}.elementor-grid-0 .king-addons-share-buttons a' => 'margin-right: calc({{SIZE}}{{UNIT}} / 2); margin-left: calc({{SIZE}}{{UNIT}} / 2)',
                    '(tablet) {{WRAPPER}}.elementor-grid-tablet-0 .king-addons-share-buttons a' => 'margin-right: calc({{SIZE}}{{UNIT}} / 2); margin-left: calc({{SIZE}}{{UNIT}} / 2)',
                    '(mobile) {{WRAPPER}}.elementor-grid-mobile-0 .king-addons-share-buttons a' => 'margin-right: calc({{SIZE}}{{UNIT}} / 2); margin-left: calc({{SIZE}}{{UNIT}} / 2)',
                    '{{WRAPPER}}.elementor-grid-0 .elementor-grid' => 'margin-right: calc(-{{SIZE}}{{UNIT}} / 2); margin-left: calc(-{{SIZE}}{{UNIT}} / 2)',
                    '(tablet) {{WRAPPER}}.elementor-grid-tablet-0 .elementor-grid' => 'margin-right: calc(-{{SIZE}}{{UNIT}} / 2); margin-left: calc(-{{SIZE}}{{UNIT}} / 2)',
                    '(mobile) {{WRAPPER}}.elementor-grid-mobile-0 .elementor-grid' => 'margin-right: calc(-{{SIZE}}{{UNIT}} / 2); margin-left: calc(-{{SIZE}}{{UNIT}} / 2)',
                ],
            ]
        );

        $this->add_responsive_control(
            'share_gutter_vr',
            [
                'label' => esc_html__('Vertical Gutter', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 10,
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 25,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}}:not(.elementor-grid-0) .elementor-grid' => 'grid-row-gap: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}}.elementor-grid-0 .king-addons-share-buttons a' => 'margin-bottom: {{SIZE}}{{UNIT}}',
                    '(tablet) {{WRAPPER}}.elementor-grid-tablet-0 .king-addons-share-buttons a' => 'margin-bottom: {{SIZE}}{{UNIT}}',
                    '(mobile) {{WRAPPER}}.elementor-grid-mobile-0 .king-addons-share-buttons a' => 'margin-bottom: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'share_icon_width',
            [
                'label' => esc_html__('Icon Width', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 45,
                ],
                'range' => [
                    'px' => [
                        'min' => 15,
                        'max' => 200,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-share-buttons .king-addons-share-icon i' => 'width: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .king-addons-share-buttons .king-addons-share-icon svg' => 'width: {{SIZE}}{{UNIT}};',
                ],
                'separator' => 'before'
            ]
        );

        $this->add_responsive_control(
            'share_icon_height',
            [
                'label' => esc_html__('Icon Height', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 45,
                ],
                'range' => [
                    'px' => [
                        'min' => 15,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-share-buttons .king-addons-share-icon i' => 'height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .king-addons-share-buttons .king-addons-share-icon svg' => 'height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .king-addons-share-buttons .king-addons-share-label' => 'height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'share_icon_size',
            [
                'label' => esc_html__('Icon Size', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 18,
                ],
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-share-buttons .king-addons-share-icon i' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .king-addons-share-buttons .king-addons-share-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};'
                ],
            ]
        );

        $this->add_responsive_control(
            'share_label_spacing',
            [
                'label' => esc_html__('Label Spacing', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'size' => 20,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-share-buttons .king-addons-share-label' => 'padding: 0 {{SIZE}}{{UNIT}};',
                ],
                'separator' => 'before',
                'condition' => [
                    'share_show_label' => 'yes',
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'share_label_typography',
                'selector' => '{{WRAPPER}} .king-addons-share-buttons .king-addons-share-label',
                'condition' => [
                    'share_show_label' => 'yes',
                ]
            ]
        );

        $this->add_responsive_control(
            'share_border_width',
            [
                'label' => esc_html__('Border Width', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 0,
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 10,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-share-buttons .king-addons-share-icon' => 'border-width: {{SIZE}}{{UNIT}}; border-style: solid;',
                ],
                'separator' => 'before'
            ]
        );

        $this->add_control_share_icon_border_radius();

        $this->add_control(
            'share_button_border_radius',
            [
                'label' => esc_html__('Button Border Radius', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'default' => [
                    'top' => 0,
                    'right' => 0,
                    'bottom' => 0,
                    'left' => 0,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-share-buttons .king-addons-share-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->add_responsive_control(
            'share_button_align',
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
                    'space-between' => [
                        'title' => esc_html__('Justified', 'king-addons'),
                        'icon' => 'eicon-text-align-justify',
                    ],
                ],
                'default' => 'flex-start',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-share-buttons' => 'justify-content: {{VALUE}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_styles_share_styles',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Styles', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'share_custom_colors',
            [
                'label' => esc_html__('Use Custom Colors', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'separator' => 'after'
            ]
        );

        $this->add_control(
            'share_icon_bg_tr',
            [
                'label' => esc_html__('Icon Background Color', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'condition' => [
                    'share_custom_colors' => '',
                ]
            ]
        );

        $this->add_control(
            'share_label_bg',
            [
                'label' => esc_html__('Label Background Color', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'condition' => [
                    'share_show_label' => 'yes',
                    'share_custom_colors' => '',
                ]
            ]
        );

        $this->add_control(
            'share_label_bg_tr',
            [
                'label' => esc_html__('Label Background Transparency', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'selectors_dictionary' => [
                    '' => '1',
                    'yes' => '0.92'
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-share-buttons .king-addons-share-label' => 'opacity: {{VALUE}};',
                ],
                'condition' => [
                    'share_show_label' => 'yes',
                    'share_custom_colors' => '',
                    'share_label_bg' => 'yes',
                ]
            ]
        );

        $this->start_controls_tabs(
            'tabs_share_custom_colors', [
                'condition' => [
                    'share_custom_colors' => 'yes',
                ]
            ]
        );

        $this->start_controls_tab(
            'tab_share_custom_normal',
            [
                'label' => esc_html__('Normal', 'king-addons'),
            ]
        );

        $this->add_control(
            'share_icon_color',
            [
                'label' => esc_html__('Icon Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-share-buttons .king-addons-share-icon i' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .king-addons-share-buttons .king-addons-share-icon svg' => 'fill: {{VALUE}}'
                ],
            ]
        );

        $this->add_control(
            'share_icon_bg_color',
            [
                'label' => esc_html__('Icon Background Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#4D02D8',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-share-buttons .king-addons-share-icon i' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .king-addons-share-buttons .king-addons-share-icon svg' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'share_label_color',
            [
                'label' => esc_html__('Label Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-share-buttons .king-addons-share-label' => 'color: {{VALUE}}',
                ],
                'condition' => [
                    'share_show_label' => 'yes',
                ]
            ]
        );

        $this->add_control(
            'share_label_bg_color',
            [
                'label' => esc_html__('Label Background Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#5B03FF',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-share-buttons .king-addons-share-label' => 'background-color: {{VALUE}}',
                ],
                'condition' => [
                    'share_show_label' => 'yes',
                ]
            ]
        );

        $this->add_control(
            'share_label_border_color',
            [
                'label' => esc_html__('Border Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#E8E8E8',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-share-buttons .king-addons-share-icon' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_share_custom_hover',
            [
                'label' => esc_html__('Hover', 'king-addons'),
            ]
        );

        $this->add_control(
            'share_icon_color_hr',
            [
                'label' => esc_html__('Icon Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-share-buttons .king-addons-share-icon:hover i' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .king-addons-share-buttons .king-addons-share-icon:hover svg' => 'fill: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'share_icon_bg_color_hr',
            [
                'label' => esc_html__('Icon Background Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#5B03FF',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-share-buttons .king-addons-share-icon:hover i' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .king-addons-share-buttons .king-addons-share-icon:hover svg' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'share_label_color_hr',
            [
                'label' => esc_html__('Label Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-share-buttons .king-addons-share-icon:hover .king-addons-share-label' => 'color: {{VALUE}}',
                ],
                'condition' => [
                    'share_show_label' => 'yes',
                ]
            ]
        );

        $this->add_control(
            'share_label_bg_color_hr',
            [
                'label' => esc_html__('Label Background Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#4D02D8',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-share-buttons .king-addons-share-icon:hover .king-addons-share-label' => 'background-color: {{VALUE}}',
                ],
                'condition' => [
                    'share_show_label' => 'yes',
                ]
            ]
        );

        $this->add_control(
            'share_label_border_color_hr',
            [
                'label' => esc_html__('Border Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#E8E8E8',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-share-buttons .king-addons-share-icon:hover' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_control(
            'share_transition_duration',
            [
                'label' => esc_html__('Transition Duration (ms)', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'default' => 100,
                'min' => 0,
                'step' => 50,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-share-buttons .king-addons-share-icon' => 'transition-duration: {{VALUE}}ms',
                    '{{WRAPPER}} .king-addons-share-buttons .king-addons-share-icon i' => 'transition-duration: {{VALUE}}ms',
                    '{{WRAPPER}} .king-addons-share-buttons .king-addons-share-icon span' => 'transition-duration: {{VALUE}}ms',
                ],
                'separator' => 'before',
            ]
        );

        $this->end_controls_section();

    }

    protected function render()
    {
        $settings = $this->get_settings();

        if (!king_addons_freemius()->can_use_premium_code__premium_only()) {
            $settings['share_custom_colors'] = '';
            $settings['share_show_label'] = '';
            $settings['share_label_bg'] = '';
            $settings['share_show_icon'] = 'yes';
        }

        $class = '' === $settings['share_custom_colors'] ? ' king-addons-share-official' : '';
        $class .= '' === $settings['share_show_label'] ? ' king-addons-share-label-off' : '';
        $class .= '' === $settings['share_icon_bg_tr'] ? ' king-addons-share-icon-tr' : '';
        $class .= '' === $settings['share_label_bg'] ? ' king-addons-share-label-tr' : '';

        echo '<div class="king-addons-share-buttons elementor-grid' . esc_attr($class) . '">';

        $count = 0;
        foreach ($settings['share_buttons'] as $button) {
            if (!king_addons_freemius()->can_use_premium_code__premium_only() && $count === 3) {
                break;
            }

            $share_icon = str_replace('fab ', '', $button['share_icon']);
            $share_icon = str_replace('fas ', '', $share_icon);
            $share_icon = str_replace('fa-', '', $share_icon);

            $args = [
                'icons' => $settings['share_show_icon'],
                'network' => $share_icon,
                'labels' => $settings['share_show_label'],
                'custom_label' => $button['share_custom_label'],
                'tooltip' => 'no',
                'url' => esc_url(get_the_permalink()),
                'title' => esc_html(get_the_title()),
                'text' => esc_html(get_the_excerpt()),
                'image' => esc_url(get_the_post_thumbnail_url())
            ];

            if (isset($button['show_whatsapp_excerpt']) && isset($button['show_whatsapp_title'])) {
                $args['show_whatsapp_title'] = $button['show_whatsapp_title'];
                $args['show_whatsapp_excerpt'] = $button['show_whatsapp_excerpt'];
            }

            echo '<div class="elementor-grid-item">';
            echo Core::getShareIcon($args); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            echo '</div>';

            $count++;
        }

        echo '</div>';
    }
}