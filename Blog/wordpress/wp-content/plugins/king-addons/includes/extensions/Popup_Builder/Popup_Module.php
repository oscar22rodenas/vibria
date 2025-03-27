<?php

namespace King_Addons;

use Elementor\Controls_Manager;
use Elementor\Core\Base\Document;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Plugin;
use Elementor\Utils;

if (!defined('ABSPATH')) {
    exit;
}

class King_Addons_Popup_Module extends Document
{

    public function get_name(): string
    {
        return 'king-addons-pb-popups';
    }

    public static function get_type(): string
    {
        return 'king-addons-pb-popups';
    }

    public static function get_title(): string
    {
        return esc_html__('Popup Builder by King Addons', 'king-addons');
    }

    public function get_css_wrapper_selector(): string
    {
        if (Plugin::$instance->editor->is_edit_mode()) {
            return '.king-addons-pb-template-popup';
        } else {
            return '#king-addons-pb-popup-id-' . $this->get_main_id();
        }
    }

    public function add_control_popup_trigger()
    {
        $this->add_control(
            'popup_trigger',
            [
                'label' => '<b>' . esc_html__('Open Popup Logic', 'king-addons') . '</b>',
                'type' => Controls_Manager::SELECT,
                'default' => 'load',
                'label_block' => false,
                'options' => [
                    'load' => esc_html__('On Page Load', 'king-addons'),
                    'pro-sc' => esc_html__('On Page Scroll (PRO)', 'king-addons'),
                    'pro-es' => esc_html__('On Scroll to Element (PRO)', 'king-addons'),
                    'pro-dt' => esc_html__('After a Specific Date (PRO)', 'king-addons'),
                    'pro-ia' => esc_html__('After User Inactivity (PRO)', 'king-addons'),
                    'pro-ex' => esc_html__('After User Exit Intent (PRO)', 'king-addons'),
                    'pro-cs' => esc_html__('Custom Trigger (Button Click, CSS Selector) (PRO)', 'king-addons'),
                ],
                'render_type' => 'template'
            ]
        );
    }

    public function add_control_popup_show_again_delay()
    {
        $this->add_control(
            'popup_show_again_delay',
            [
                'label' => esc_html__('Show Again Delay', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => '0',
                'options' => [
                    '0' => esc_html__('No Delay', 'king-addons'),
                    '60000' => esc_html__('1 Minute', 'king-addons'),
                    '180000' => esc_html__('3 Minute', 'king-addons'),
                    '300000' => esc_html__('5 Minute', 'king-addons'),
                    'pro-60' => esc_html__('10 Minute (PRO)', 'king-addons'),
                    'pro-180' => esc_html__('30 Minute (PRO)', 'king-addons'),
                    'pro-360' => esc_html__('1 Hour (PRO)', 'king-addons'),
                    'pro-1080' => esc_html__('3 Hour (PRO)', 'king-addons'),
                    'pro-2160' => esc_html__('6 Hour (PRO)', 'king-addons'),
                    'pro-4320' => esc_html__('12 Hour (PRO)', 'king-addons'),
                    'pro-8640' => esc_html__('1 Day (PRO)', 'king-addons'),
                    'pro-25920' => esc_html__('3 Days (PRO)', 'king-addons'),
                    'pro-43200' => esc_html__('5 Days (PRO)', 'king-addons'),
                    'pro-60480' => esc_html__('7 Days (PRO)', 'king-addons'),
                    'pro-864000' => esc_html__('10 Days (PRO)', 'king-addons'),
                    'pro-1296000' => esc_html__('15 Days (PRO)', 'king-addons'),
                    'pro-1728000' => esc_html__('20 Days (PRO)', 'king-addons'),
                    'pro-262800' => esc_html__('1 Month (PRO)', 'king-addons'),
                ],
                'description' => esc_html__('This option determines when to show popup again to a visitor after it is closed.', 'king-addons'),
                'separator' => 'before',
                'render_type' => 'template'
            ]
        );
    }

    public function add_controls_group_popup_settings()
    {
    }

    protected function register_controls()
    {

        $this->start_controls_section(
            'popup_settings',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Settings', 'king-addons'),
                'tab' => Controls_Manager::TAB_SETTINGS,
            ]
        );

        $this->add_control_popup_trigger();

        Core::renderUpgradeProNotice($this, Controls_Manager::RAW_HTML, 'popup-builder', 'popup_trigger', [
            'pro-sc',
            'pro-es',
            'pro-dt',
            'pro-ia',
            'pro-ex',
            'pro-cs'
        ]);

        $this->add_control(
            'popup_load_delay',
            [
                'label' => esc_html__('Delay after Page Load (sec)', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'default' => 1,
                'min' => 0,
                'condition' => [
                    'popup_trigger' => 'load',
                ]
            ]
        );

        $this->add_control(
            'popup_scroll_progress',
            [
                'label' => esc_html__('Scroll Progress (in %)', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'default' => 10,
                'min' => 1,
                'max' => 100,
                'condition' => [
                    'popup_trigger' => 'scroll',
                ]
            ]
        );

        $this->add_control(
            'popup_element_scroll',
            [
                'label' => esc_html__('Element Selector', 'king-addons'),
                'description' => 'CSS ID or class name, for example: .test for class and #test for ID, i.e. including dot or hash before the name.',
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => '',
                'condition' => [
                    'popup_trigger' => 'element-scroll',
                ]
            ]
        );

        $this->add_control(
            'popup_specific_date',
            [
                'label' => esc_html__('Select Date', 'king-addons'),
                'label_block' => false,
                'type' => Controls_Manager::DATE_TIME,
                'default' => date('Y-m-d H:i', strtotime('+1 day') + (get_option('gmt_offset') * HOUR_IN_SECONDS)),
                'description' => sprintf(__('Set according to your WordPress timezone: %s.', 'king-addons'), Utils::get_timezone_string()),
                'condition' => [
                    'popup_trigger' => 'date',
                ],
            ]
        );

        $this->add_control(
            'popup_custom_trigger',
            [
                'label' => esc_html__('Element Selector', 'king-addons'),
                'description' => 'CSS ID or class name, for example: .test for class and #test for ID, i.e. including dot or hash before the name.',
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => '',
                'render_type' => 'template',
                'condition' => [
                    'popup_trigger' => 'custom',
                ]
            ]
        );

        $this->add_control(
            'popup_inactivity_time',
            [
                'label' => esc_html__('Inactivity Time (sec)', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'default' => 15,
                'min' => 1,
                'condition' => [
                    'popup_trigger' => 'inactivity',
                ]
            ]
        );

        $this->add_control_popup_show_again_delay();

        Core::renderUpgradeProNotice($this, Controls_Manager::RAW_HTML, 'popup-builder', 'popup_show_again_delay', [
            'pro-60',
            'pro-180',
            'pro-360',
            'pro-1080',
            'pro-2160',
            'pro-4320',
            'pro-8640',
            'pro-25920',
            'pro-43200',
            'pro-60480',
            'pro-864000',
            'pro-1296000',
            'pro-1728000',
            'pro-262800'
        ]);

        $this->add_controls_group_popup_settings();

        $this->end_controls_section();

        $this->start_controls_section(
            'popup_layout',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Layout', 'king-addons'),
                'tab' => Controls_Manager::TAB_SETTINGS,
            ]
        );

        $this->add_control(
            'popup_display_as',
            [
                'label' => esc_html__('Display As', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'modal',
                'options' => [
                    'modal' => esc_html__('Modal Popup', 'king-addons'),
                    'notification' => esc_html__('Top Bar Banner', 'king-addons'),
                ],
            ]
        );

        $this->add_control(
            'popup_display_as_divider',
            [
                'type' => Controls_Manager::DIVIDER,
                'style' => 'thick',
            ]
        );

        $this->add_responsive_control(
            'popup_width',
            [
                'label' => esc_html__('Width', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 650,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-pb-popup-container' => 'width: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'popup_display_as!' => 'notification',
                ]
            ]
        );

        $this->add_control(
            'popup_height',
            [
                'label' => esc_html__('Height', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'auto',
                'options' => [
                    'auto' => esc_html__('Auto', 'king-addons'),
                    'custom' => esc_html__('Custom', 'king-addons'),
                ],
                'selectors_dictionary' => [
                    'auto' => 'height: auto; z-index: 13;',
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-pb-popup-container-inner' => '{{VALUE}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'popup_custom_height',
            [
                'label' => esc_html__('Custom Height', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'vh'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 500,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-pb-popup-container-inner' => 'height: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'popup_height' => 'custom'
                ]
            ]
        );

        $this->add_responsive_control(
            'popup_align_hr',
            [
                'label' => esc_html__('Horizontal Align', 'king-addons'),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'default' => 'center',
                'options' => [
                    'flex-start' => [
                        'title' => esc_html__('Left', 'king-addons'),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'king-addons'),
                        'icon' => 'eicon-h-align-center',
                    ],
                    'flex-end' => [
                        'title' => esc_html__('Right', 'king-addons'),
                        'icon' => 'eicon-h-align-right',
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-pb-template-popup-inner' => 'justify-content: {{VALUE}}',
                ],
                'separator' => 'before',
                'condition' => [
                    'popup_display_as!' => 'notification',
                ]
            ]
        );

        $this->add_responsive_control(
            'popup_align_vr',
            [
                'label' => esc_html__('Vertical Align', 'king-addons'),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'default' => 'center',
                'options' => [
                    'flex-start' => [
                        'title' => esc_html__('Top', 'king-addons'),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'center' => [
                        'title' => esc_html__('Middle', 'king-addons'),
                        'icon' => 'eicon-v-align-middle',
                    ],
                    'flex-end' => [
                        'title' => esc_html__('Bottom', 'king-addons'),
                        'icon' => 'eicon-v-align-bottom',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-pb-template-popup-inner' => 'align-items: {{VALUE}}',
                ],
                'condition' => [
                    'popup_display_as!' => 'notification',
                ]
            ]
        );

        $this->add_responsive_control(
            'popup_content_align',
            [
                'label' => esc_html__('Content Align', 'king-addons'),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'default' => 'flex-start',
                'options' => [
                    'flex-start' => [
                        'title' => esc_html__('Top', 'king-addons'),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'center' => [
                        'title' => esc_html__('Middle', 'king-addons'),
                        'icon' => 'eicon-v-align-middle',
                    ],
                    'flex-end' => [
                        'title' => esc_html__('Bottom', 'king-addons'),
                        'icon' => 'eicon-v-align-bottom',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-pb-popup-container-inner' => 'align-items: {{VALUE}}',
                ],
                'condition' => [
                    'popup_display_as!' => 'notification',
                ]
            ]
        );

        $this->add_control(
            'popup_animation',
            [
                'label' => esc_html__('Entance Animation', 'king-addons'),
                'type' => Controls_Manager::ANIMATION,
                'default' => 'fadeIn',
                'label_block' => true,
                'frontend_available' => true,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'popup_animation_duration',
            [
                'label' => esc_html__('Animation Duration', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'default' => 1,
                'min' => 0,
                'step' => 0.1,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-pb-popup-container' => 'animation-duration: {{SIZE}}s;',
                ],
                'condition' => [
                    'popup_animation!' => ['', 'none'],
                ]
            ]
        );

        $this->add_control(
            'popup_zindex',
            [
                'label' => esc_html__('Z Index', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'default' => 9999,
                'min' => 1,
                'selectors' => [
                    '{{WRAPPER}}' => 'z-index: {{VALUE}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'popup_disable_page_scroll',
            [
                'label' => esc_html__('Disable Page Scroll', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => true,
                'return_value' => true,
                'separator' => 'before',
                'condition' => [
                    'popup_display_as!' => 'notification',
                ]
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'popup_overlay',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Overlay', 'king-addons'),
                'tab' => Controls_Manager::TAB_SETTINGS,
                'condition' => [
                    'popup_display_as!' => 'notification',
                ]
            ]
        );

        $this->add_control(
            'popup_overlay_display',
            [
                'label' => esc_html__('Show Overlay', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'selectors_dictionary' => [
                    '' => 'display: none !important;',
                    'yes' => 'display: block;'
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-pb-popup-overlay' => '{{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'popup_overlay_disable_close',
            [
                'label' => esc_html__('Prevent Closing on Overlay Click', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'condition' => [
                    'popup_overlay_display' => 'yes'
                ]
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'popup_close_button',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Close Button', 'king-addons'),
                'tab' => Controls_Manager::TAB_SETTINGS,
            ]
        );

        $this->add_control(
            'popup_close_button_display',
            [
                'label' => esc_html__('Show Close Button', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'selectors_dictionary' => [
                    '' => 'display: none;',
                    'yes' => 'display: block;'
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-pb-popup-close-btn' => '{{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'popup_close_button_display_delay',
            [
                'label' => esc_html__('Show Up Delay (sec)', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'default' => 0,
                'min' => 0,
                'condition' => [
                    'popup_close_button_display' => 'yes',
                ]
            ]
        );

        $this->add_responsive_control(
            'popup_close_button_position_vr',
            [
                'label' => esc_html__('Vertical Position', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => -100,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 10,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-pb-popup-close-btn' => 'top: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'popup_close_button_display' => 'yes'
                ]
            ]
        );

        $this->add_responsive_control(
            'popup_close_button_position_hr',
            [
                'label' => esc_html__('Horizontal Position', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => -100,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 10,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-pb-popup-close-btn' => 'right: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'popup_close_button_display' => 'yes'
                ]
            ]
        );

        $this->end_controls_section();

        if (!king_addons_freemius()->can_use_premium_code__premium_only()) {
            $this->start_controls_section(
                'king_addons_pro_features_section',
                [
                    'label' => KING_ADDONS_ELEMENTOR_ICON_PRO . '<span class="king-addons-pro-features-heading">Pro Features</span>',
                    'tab' => Controls_Manager::TAB_SETTINGS,
                ]
            );

            $this->add_control(
                'king_addons_pro_features_list',
                [
                    'type' => Controls_Manager::RAW_HTML,
                    'raw' => '<ul>
						<li>Open Popup: On Page Scroll</li>
<li>Open Popup: After User Exit Intent</li>
<li>Open Popup: After User Inactivity</li>
<li>Open Popup: On Scroll to Element</li>
<li>Show/Hide Popup on Any Device</li>
<li>Show Popup for Specific Roles</li>
<li>Prevent Popup Closing on "ESC" Key</li>
<li>Open Popup: After a Specific Date</li>
<li>Show Again Delay: Set a specific time (in hours, days, or weeks). This option determines when the popup will reappear for a visitor after it is closed.</li>
<li>Stop Showing After a Specific Date</li>
<li>Automatic Closing Delay</li>
<li>Show According to URL Keyword - The popup will appear if the URL (referral) contains the chosen keyword.</li>
<li>Open Popup: Custom Trigger - Button Click, CSS Selector (class or ID)</li>
					</ul>' .
                        self::getUpgradeProLink('popup-builder'),
                    'content_classes' => 'king-addons-pro-features-list',
                ]
            );

            $this->end_controls_section();
        }

        parent::register_controls();

        $this->start_controls_section(
            'popup_container_styles',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Popup', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'popup_container_bg',
                'label' => esc_html__('Background', 'king-addons'),
                'types' => ['classic', 'gradient'],
                'fields_options' => [
                    'color' => [
                        'default' => '#ffffff',
                    ],
                ],
                'selector' => '{{WRAPPER}} .king-addons-pb-popup-container-inner'
            ]
        );

        $this->add_control(
            'popup_scrollbar_color',
            [
                'label' => esc_html__('ScrollBar Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#333333',
                'selectors' => [
                    '{{WRAPPER}} .ps-container > .ps-scrollbar-y-rail > .ps-scrollbar-y' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .ps > .ps__rail-y > .ps__thumb-y' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'popup_container_padding',
            [
                'label' => esc_html__('Padding', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'default' => [
                    'top' => 20,
                    'right' => 20,
                    'bottom' => 20,
                    'left' => 20,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-pb-popup-container-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before'
            ]
        );

        $this->add_control(
            'popup_container_radius',
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
                    '{{WRAPPER}} .king-addons-pb-popup-container-inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'popup_container_border',
                'label' => esc_html__('Border', 'king-addons'),
                'placeholder' => '1px',
                'default' => '1px',
                'selector' => '{{WRAPPER}} .king-addons-pb-popup-container-inner',
                'separator' => 'before'
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'popup_container_shadow',
                'selector' => '{{WRAPPER}} .king-addons-pb-popup-container-inner'
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'popup_overlay_styles',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Overlay', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'popup_overlay_display' => 'yes'
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'popup_overlay_bg',
                'label' => esc_html__('Background', 'king-addons'),
                'types' => ['classic', 'gradient'],
                'fields_options' => [
                    'color' => [
                        'default' => '#777777',
                    ],
                ],
                'selector' => '{{WRAPPER}} .king-addons-pb-popup-overlay'
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'popup_close_btn_styles',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Close Button', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            ]
        );

        $this->start_controls_tabs('tabs_popup_close_btn_style');

        $this->start_controls_tab(
            'tab_popup_close_btn_normal',
            [
                'label' => esc_html__('Normal', 'king-addons'),
            ]
        );

        $this->add_control(
            'popup_close_btn_color',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#333333',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-pb-popup-close-btn' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'popup_close_btn_bg_color',
            [
                'label' => esc_html__('Background Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-pb-popup-close-btn' => 'background-color: {{VALUE}}',
                ]
            ]
        );

        $this->add_control(
            'popup_close_btn_border_color',
            [
                'label' => esc_html__('Border Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-pb-popup-close-btn' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'popup_close_btn_box_shadow',
                'selector' => '{{WRAPPER}} .king-addons-pb-popup-close-btn',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_popup_close_btn_hover',
            [
                'label' => esc_html__('Hover', 'king-addons'),
            ]
        );

        $this->add_control(
            'popup_close_btn_color_hr',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#54595f',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-pb-popup-close-btn:hover' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'popup_close_btn_bg_color_hr',
            [
                'label' => esc_html__('Background Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-pb-popup-close-btn:hover' => 'background-color: {{VALUE}}',
                ]
            ]
        );

        $this->add_control(
            'popup_close_btn_border_color_hr',
            [
                'label' => esc_html__('Border Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-pb-popup-close-btn:hover' => 'border-color: {{VALUE}}',
                ]
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_control(
            'popup_close_btn_size',
            [
                'label' => esc_html__('Size', 'king-addons'),
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
                    '{{WRAPPER}} .king-addons-pb-popup-close-btn i' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .king-addons-pb-popup-close-btn svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};'
                ],
            ]
        );

        $this->add_control(
            'popup_close_btn_box_size',
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
                    'size' => 35,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-pb-popup-close-btn' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .king-addons-pb-popup-close-btn i' => 'line-height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .king-addons-pb-popup-close-btn svg' => 'line-height: {{SIZE}}{{UNIT}};'
                ],
            ]
        );

        $this->add_control(
            'popup_close_btn_border_type',
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
                    '{{WRAPPER}} .king-addons-pb-popup-close-btn' => 'border-style: {{VALUE}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'popup_close_btn_border_width',
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
                    '{{WRAPPER}} .king-addons-pb-popup-close-btn' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'popup_close_btn_border_type!' => 'none',
                ],
            ]
        );

        $this->add_control(
            'popup_close_btn_radius',
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
                    '{{WRAPPER}} .king-addons-pb-popup-close-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->end_controls_section();

    }

    public static function getUpgradeProLink($widget_name): string
    {
        return '<a class="king-addons-pro-features-cta-btn" href="https://kingaddons.com/pricing/?utm_source=kng-module-' . $widget_name . '-upgrade-pro&utm_medium=plugin&utm_campaign=kng" target="_blank">' . esc_html__('Learn More About Pro', 'king-addons') . '</a>';
    }

}