<?php

namespace King_Addons;

use Elementor;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Plugin;
use Elementor\Repeater;
use Elementor\Widget_Base;

if (!defined('ABSPATH')) {
    exit;
}

class Tabs extends Widget_Base
{

    public function get_name()
    {
        return 'king-addons-tabs';
    }

    public function get_title()
    {
        return esc_html__('Tabs', 'king-addons');
    }

    public function get_icon()
    {
        return 'king-addons-icon king-addons-tabs';
    }

    public function get_categories()
    {
        return ['king-addons'];
    }

    public function get_keywords()
    {
        return ['king addons', 'king', 'addons', 'kingaddons', 'king-addons', 'vertical',
            'horizontal', 'accor', 'tab', 'tabs', 'vertical tabs', 'horizontal tabs', 'accordion'];
    }

    public function get_script_depends()
    {
        return [
            KING_ADDONS_ASSETS_UNIQUE_KEY . '-tabs-script'
        ];
    }

    public function get_style_depends()
    {
        return [
            KING_ADDONS_ASSETS_UNIQUE_KEY . '-animation-button',
            KING_ADDONS_ASSETS_UNIQUE_KEY . '-animation-general',
            KING_ADDONS_ASSETS_UNIQUE_KEY . '-animation-timing',
            KING_ADDONS_ASSETS_UNIQUE_KEY . '-animation-loading',
            KING_ADDONS_ASSETS_UNIQUE_KEY . '-tabs-style'
        ];
    }

    public function get_custom_help_url()
    {
        return 'https://kingaddons.com/';
    }

    public function has_widget_inner_wrapper(): bool {
        return true;
    }

    public function add_repeater_args_tab_custom_color()
    {
        return [
            'label' => sprintf(__('Use custom colors for this tab %s', 'king-addons'), '<i class="eicon-pro-icon"></i>'),
            'type' => Controls_Manager::SWITCHER,
            'separator' => 'before',
            'classes' => 'king-addons-pro-control'
        ];
    }

    public function add_repeater_args_tab_content_type()
    {
        return [
            'label' => esc_html__('Content Type', 'king-addons'),
            'type' => Controls_Manager::SELECT,
            'default' => 'editor',
            'options' => [
                'editor' => esc_html__('Editor', 'king-addons'),
                'pro-cf' => esc_html__('Custom Field (Pro)', 'king-addons'),
                'pro-tmp' => esc_html__('Elementor Template (Pro)', 'king-addons'),
            ],
            'separator' => 'before',
        ];
    }

    public function add_control_tabs_hr_position()
    {
        $this->add_control(
            'tabs_hr_position',
            [
                'label' => esc_html__('Horizontal Align', 'king-addons'),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'default' => 'justify',
                'options' => [
                    'pro-lt' => [
                        'title' => esc_html__('Left (Pro)', 'king-addons'),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'pro-ct' => [
                        'title' => esc_html__('Center (Pro)', 'king-addons'),
                        'icon' => 'eicon-h-align-center',
                    ],
                    'pro-rt' => [
                        'title' => esc_html__('Right (Pro)', 'king-addons'),
                        'icon' => 'eicon-h-align-right',
                    ],
                    'justify' => [
                        'title' => esc_html__('Stretch', 'king-addons'),
                        'icon' => 'eicon-h-align-stretch',
                    ],
                ],
                'prefix_class' => 'king-addons-tabs-hr-position-',
                'render_type' => 'template',
                'condition' => [
                    'tabs_position' => 'above',
                ],
            ]
        );
    }

    public function add_section_settings()
    {
    }

    protected function register_controls()
    {


        $css_selector = [
            'general' => '> .elementor-widget-container > .king-addons-tabs',
            'control_list' => '> .elementor-widget-container > .king-addons-tabs > .king-addons-tabs-wrap > .king-addons-tab',
            'content_wrap' => '> .elementor-widget-container > .king-addons-tabs > .king-addons-tabs-content-wrap',
            'content_list' => '> .elementor-widget-container > .king-addons-tabs > .king-addons-tabs-content-wrap > .king-addons-tab-content',
            'control_icon' => '.king-addons-tab-icon',
            'control_image' => '.king-addons-tab-image',
        ];


        $this->start_controls_section(
            'section_tabs',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Tabs', 'king-addons'),
            ]
        );


        $repeater = new Repeater();

        $repeater->add_control(
            'tab_title',
            [
                'label' => esc_html__('Label', 'king-addons'),
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => 'Tab 1',
            ]
        );

        $repeater->add_control(
            'tab_icon_type',
            [
                'label' => esc_html__('Icon Type', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'none',
                'options' => [
                    'none' => esc_html__('None', 'king-addons'),
                    'icon' => esc_html__('Icon', 'king-addons'),
                    'image' => esc_html__('Image', 'king-addons'),
                ],
                'separator' => 'before',
            ]
        );

        $repeater->add_control(
            'tab_image',
            [
                'label' => esc_html__('Upload Image', 'king-addons'),
                'type' => Controls_Manager::MEDIA,
                'dynamic' => [
                    'active' => true,
                ],
                'condition' => [
                    'tab_icon_type' => 'image',
                ],
            ]
        );

        $repeater->add_control(
            'tab_icon',
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
                    'tab_icon_type' => 'icon',
                ],
            ]
        );

        $repeater->add_control('tab_content_type', $this->add_repeater_args_tab_content_type());

        Core::renderUpgradeProNotice($repeater, Controls_Manager::RAW_HTML, 'tabs', 'tab_content_type', ['pro-tmp']);
        Core::renderUpgradeProNotice($repeater, Controls_Manager::RAW_HTML, 'tabs', 'tab_content_type', ['pro-cf']);

        if (king_addons_freemius()->can_use_premium_code__premium_only()) {
            $repeater->add_control(
                'tab_custom_field',
                [
                    'label' => esc_html__('Select Custom Field', 'king-addons'),
                    'type' => 'king-addons-ajax-select2',
                    'label_block' => true,
                    'default' => 'default',
                    'description' => '<strong>Note:</strong> This option only accepts String(Text) or Numeric Custom Field Values.',
                    'options' => 'ajaxselect2/getCustomMetaKeys',
                    'condition' => [
                        'tab_content_type' => 'acf'
                    ],
                ]
            );
        }

        $repeater->add_control(
            'select_template',
            [
                'label' => esc_html__('Select Template', 'king-addons'),
                'type' => 'king-addons-ajax-select2',
                'options' => 'ajaxselect2/getElementorTemplates',
                'label_block' => true,
                'condition' => [
                    'tab_content_type' => 'template',
                ],
            ]
        );

        $repeater->add_control('tab_custom_color', $this->add_repeater_args_tab_custom_color());

        $repeater->add_control(
            'tab_custom_text_color',
            [
                'label' => esc_html__('Text Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} ' . $css_selector['control_list'] . '{{CURRENT_ITEM}} .king-addons-tab-title' => 'color: {{VALUE}} !important;',
                    '{{WRAPPER}} ' . $css_selector['control_list'] . '{{CURRENT_ITEM}} .king-addons-tab-icon' => 'color: {{VALUE}} !important;',
                    '{{WRAPPER}} ' . $css_selector['content_list'] . '{{CURRENT_ITEM}}' => 'color: {{VALUE}} !important;',
                    '{{WRAPPER}} ' . $css_selector['control_list'] . '{{CURRENT_ITEM}}:before' => 'display: none !important;',
                ],
                'condition' => [
                    'tab_custom_color' => 'yes',
                ],
            ]
        );

        $repeater->add_control(
            'tab_custom_bg_color',
            [
                'label' => esc_html__('Background Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#61ce70',
                'selectors' => [
                    '{{WRAPPER}} ' . $css_selector['control_list'] . '{{CURRENT_ITEM}}' => 'background-color: {{VALUE}} !important;',
                    '{{WRAPPER}} ' . $css_selector['content_list'] . '{{CURRENT_ITEM}}' => 'background-color: {{VALUE}} !important;',
                ],
                'condition' => [
                    'tab_custom_color' => 'yes',
                ],
            ]
        );

        $repeater->add_control(
            'tab_content',
            [
                'label' => esc_html__('Content', 'king-addons'),
                'type' => Controls_Manager::WYSIWYG,
                'placeholder' => esc_html__('Tab Content', 'king-addons'),
                'separator' => 'before',
                'default' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Minima incidunt voluptates nemo, dolor optio quia architecto quis delectus perspiciatis. Nobis atque id hic neque possimus voluptatum voluptatibus tenetur, perspiciatis consequuntur.',
                'condition' => [
                    'tab_content_type' => 'editor',
                ],
            ]
        );

        $this->add_control(
            'tabs',
            [
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'tab_title' => 'Tab 1',
                        'tab_custom_bg_color' => '#000000',
                        'tab_content' => 'Tab 1 content. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Minima incidunt voluptates nemo, dolor optio quia architecto quis delectus perspiciatis. Nobis atque id hic neque possimus voluptatum voluptatibus tenetur, perspiciatis consequuntur.',
                    ],
                    [
                        'tab_title' => 'Tab 2',
                        'tab_custom_bg_color' => '#FF3F62',
                        'tab_content' => 'Tab two. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Minima incidunt voluptates nemo, dolor optio quia architecto quis delectus perspiciatis. Nobis atque id hic neque possimus voluptatum voluptatibus tenetur, perspiciatis consequuntur.',
                    ],
                    [
                        'tab_title' => 'Tab 3',
                        'tab_custom_bg_color' => '#384FFF',
                        'tab_content' => 'Tab three content. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Minima incidunt voluptates nemo, dolor optio quia architecto quis delectus perspiciatis. Nobis atque id hic neque possimus voluptatum voluptatibus tenetur, perspiciatis consequuntur.',
                    ]
                ],
                'title_field' => '{{{ tab_title }}}',
            ]
        );


        if (!king_addons_freemius()->can_use_premium_code__premium_only()) {
            $this->add_control(
                'tabs_repeater_pro_notice',
                [
                    'type' => Controls_Manager::RAW_HTML,
                    'raw' => 'More than 3 Tabs are available<br> in the <strong><a href="https://kingaddons.com/pricing/?utm_source=kng-module-tabs-settings-upgrade-pro&utm_medium=plugin&utm_campaign=kng" target="_blank">Pro version</a></strong>',

                    'content_classes' => 'king-addons-pro-notice',
                ]
            );
        }

        $this->add_control(
            'tabs_position',
            [
                'type' => Controls_Manager::SELECT,
                'label' => esc_html__('Label Position', 'king-addons'),
                'default' => 'above',
                'options' => [
                    'above' => esc_html__('Default', 'king-addons'),
                    'left' => esc_html__('Left', 'king-addons'),
                    'right' => esc_html__('Right', 'king-addons'),
                ],
                'prefix_class' => 'king-addons-tabs-position-',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'tabs_invert_responsive',
            [
                'label' => esc_html__('Invert on Mobile', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'prefix_class' => 'king-addons-tabs-responsive-',
            ]
        );

        $this->add_control_tabs_hr_position();

        if (!king_addons_freemius()->can_use_premium_code__premium_only()) {
            $this->add_control(
                'tabs_align_pro_notice',
                [
                    'raw' => 'Horizontal Align option is fully supported in the <strong><a href="https://kingaddons.com/pricing/?utm_source=kng-module-tabs-settings-upgrade-pro&utm_medium=plugin&utm_campaign=kng" target="_blank">Pro version</a></strong>',
                    'type' => Controls_Manager::RAW_HTML,
                    'content_classes' => 'king-addons-pro-notice',
                    'condition' => [
                        'tabs_hr_position!' => 'justify',
                    ],
                ]
            );
        }

        $this->add_control(
            'tabs_vr_position',
            [
                'label' => esc_html__('Vertical Align', 'king-addons'),
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
                'selectors_dictionary' => [
                    'top' => 'flex-start',
                    'middle' => 'center',
                    'bottom' => 'flex-end'
                ],
                'selectors' => [
                    '{{WRAPPER}} ' . $css_selector['general'] => '-webkit-align-items: {{VALUE}};align-items: {{VALUE}};',
                ],
                'condition' => [
                    'tabs_position!' => 'above',
                ],
            ]
        );

        $this->add_control(
            'text_align',
            [
                'label' => esc_html__('Label Alignment', 'king-addons'),
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
                    '{{WRAPPER}}.king-addons-tabs-icon-position-left ' . $css_selector['control_list'] => '-webkit-justify-content: {{VALUE}};justify-content: {{VALUE}};',
                    '{{WRAPPER}}.king-addons-tabs-icon-position-center ' . $css_selector['control_list'] => '-webkit-align-items: {{VALUE}};align-items: {{VALUE}};',
                    '{{WRAPPER}}.king-addons-tabs-icon-position-right ' . $css_selector['control_list'] => '-webkit-justify-content: {{VALUE}};justify-content: {{VALUE}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'tabs_width',
            [
                'label' => esc_html__('Label Width', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 600,
                    ],
                    '%' => [
                        'min' => 10,
                        'max' => 100
                    ]
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 70,
                ],
                'selectors' => [
                    '{{WRAPPER}} ' . $css_selector['control_list'] => 'min-width: {{SIZE}}{{UNIT}};',
                ],
                'separator' => 'before'
            ]
        );

        $this->add_control(
            'tabs_icon_section',
            [
                'label' => esc_html__('Icon', 'king-addons'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'tabs_icon_position',
            [
                'label' => esc_html__('Position', 'king-addons'),
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
                    ],
                ],
                'prefix_class' => 'king-addons-tabs-icon-position-',
            ]
        );

        $this->add_responsive_control(
            'tabs_icon_distance',
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
                    'size' => 5,
                ],
                'selectors' => [
                    '{{WRAPPER}}.king-addons-tabs-icon-position-left ' . $css_selector['control_list'] . ' ' . $css_selector['control_icon'] => 'margin-right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}}.king-addons-tabs-icon-position-right ' . $css_selector['control_list'] . ' ' . $css_selector['control_icon'] => 'margin-left: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}}.king-addons-tabs-icon-position-center ' . $css_selector['control_list'] . ' ' . $css_selector['control_icon'] => 'margin-bottom: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}}.king-addons-tabs-icon-position-left ' . $css_selector['control_list'] . ' ' . $css_selector['control_image'] => 'margin-right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}}.king-addons-tabs-icon-position-right ' . $css_selector['control_list'] . ' ' . $css_selector['control_image'] => 'margin-left: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}}.king-addons-tabs-icon-position-center ' . $css_selector['control_list'] . ' ' . $css_selector['control_image'] => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name' => 'tabs_image_size',
                'default' => 'full',
                'separator' => 'before'
            ]
        );

        $this->end_controls_section();


        $this->add_section_settings();


        Core::renderProFeaturesSection($this, '', Controls_Manager::RAW_HTML, 'tabs', [
            'Add Unlimited Tabs',
            'Tab Content Type - Elementor Template',
            'Tab Content Type - Custom Fields',
            'Set Active Tab by Default',
            'Switch Tabs on Hover Option',
            'Tabs Autoplay Option',
            'Custom Tab Colors',
            'Tab Label Align',
            'Advanced Tab Content Animations'
        ]);


        $this->start_controls_section(
            'section_style_tabs',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Labels', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->start_controls_tabs('tab_style');

        $this->start_controls_tab(
            'tab_normal_style',
            [
                'label' => esc_html__('Normal', 'king-addons'),
            ]
        );

        $this->add_control(
            'tab_color',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#7a7a7a',
                'selectors' => [
                    '{{WRAPPER}} ' . $css_selector['control_list'] . ' .king-addons-tab-title' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'tab_icon_color',
            [
                'label' => esc_html__('Icon Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#7a7a7a',
                'selectors' => [
                    '{{WRAPPER}} ' . $css_selector['control_list'] . ' ' . $css_selector['control_icon'] => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'tab_bg_color',
            [
                'label' => esc_html__('Background Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} ' . $css_selector['control_list'] => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'tab_border_color',
            [
                'label' => esc_html__('Border Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} ' . $css_selector['control_list'] => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'tab_box_shadow',
                'selector' => '{{WRAPPER}} ' . $css_selector['control_list'],
            ]
        );

        $this->add_control(
            'tab_typography_divider',
            [
                'type' => Controls_Manager::DIVIDER,
                'style' => 'thick',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'tab_typography',
                'selector' => '{{WRAPPER}} ' . $css_selector['control_list'] . ' .king-addons-tab-title',
            ]
        );

        $this->add_responsive_control(
            'tab_icon_size',
            [
                'label' => esc_html__('Icon Size', 'king-addons'),
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
                    'size' => 15,
                ],
                'selectors' => [
                    '{{WRAPPER}} ' . $css_selector['control_list'] . ' .king-addons-tab-icon i' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} ' . $css_selector['control_list'] . ' .king-addons-tab-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} ' . $css_selector['control_list'] . ' .king-addons-tab-image' => 'width: {{SIZE}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'tab_padding',
            [
                'label' => esc_html__('Padding', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'default' => [
                    'top' => 10,
                    'right' => 10,
                    'bottom' => 10,
                    'left' => 10,
                ],
                'selectors' => [
                    '{{WRAPPER}} ' . $css_selector['control_list'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'tab_margin',
            [
                'label' => esc_html__('Margin', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px',],
                'default' => [
                    'top' => 0,
                    'right' => 0,
                    'bottom' => 0,
                    'left' => 0,
                ],
                'selectors' => [
                    '{{WRAPPER}} ' . $css_selector['control_list'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'tab_border_type',
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
                    '{{WRAPPER}} ' . $css_selector['control_list'] => 'border-style: {{VALUE}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'tab_border_width',
            [
                'label' => esc_html__('Border Width', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'default' => [
                    'top' => 1,
                    'right' => 1,
                    'bottom' => 0,
                    'left' => 1,
                ],
                'selectors' => [
                    '{{WRAPPER}} ' . $css_selector['control_list'] => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'tab_border_type!' => 'none',
                ],
            ]
        );

        $this->add_responsive_control(
            'tab_border_radius',
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
                    '{{WRAPPER}} ' . $css_selector['control_list'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_hover_style',
            [
                'label' => esc_html__('Hover', 'king-addons'),
            ]
        );

        $this->add_control(
            'tab_hover_color',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#333333',
                'selectors' => [
                    '{{WRAPPER}} ' . $css_selector['control_list'] . ':hover .king-addons-tab-title' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'tab_hover_icon_color',
            [
                'label' => esc_html__('Icon Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#333333',
                'selectors' => [
                    '{{WRAPPER}} ' . $css_selector['control_list'] . ':hover .king-addons-tab-icon' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'tab_hover_bg_color',
            [
                'label' => esc_html__('Background Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} ' . $css_selector['control_list'] . ':hover' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'tab_hover_border_color',
            [
                'label' => esc_html__('Border Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} ' . $css_selector['control_list'] . ':hover' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'tab_hover_box_shadow',
                'selector' => '{{WRAPPER}} ' . $css_selector['control_list'] . ':hover',
            ]
        );

        $this->add_control(
            'tab_hover_typography_divider',
            [
                'type' => Controls_Manager::DIVIDER,
                'style' => 'thick',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'tab_hover_typography',
                'selector' => '{{WRAPPER}} ' . $css_selector['control_list'] . ':hover .king-addons-tab-title',
            ]
        );

        $this->add_responsive_control(
            'tab_hover_icon_size',
            [
                'label' => esc_html__('Icon Size', 'king-addons'),
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
                    'size' => 15,
                ],
                'selectors' => [
                    '{{WRAPPER}} ' . $css_selector['control_list'] . ':hover .king-addons-tab-icon i' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} ' . $css_selector['control_list'] . ':hover .king-addons-tab-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} ' . $css_selector['control_list'] . ':hover .king-addons-tab-image' => 'width: {{SIZE}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'tab_hover_padding',
            [
                'label' => esc_html__('Padding', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px',],
                'default' => [
                    'top' => 10,
                    'right' => 10,
                    'bottom' => 10,
                    'left' => 10,
                ],
                'selectors' => [
                    '{{WRAPPER}} ' . $css_selector['control_list'] . ':hover' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'tab_hover_margin',
            [
                'label' => esc_html__('Margin', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px',],
                'default' => [
                    'top' => 0,
                    'right' => 0,
                    'bottom' => 0,
                    'left' => 0,
                ],
                'selectors' => [
                    '{{WRAPPER}} ' . $css_selector['control_list'] . ':hover' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'tab_hover_border_type',
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
                    '{{WRAPPER}} ' . $css_selector['control_list'] . ':hover' => 'border-style: {{VALUE}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'tab_hover_border_width',
            [
                'label' => esc_html__('Border Width', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'default' => [
                    'top' => 1,
                    'right' => 1,
                    'bottom' => 0,
                    'left' => 1,
                ],
                'selectors' => [
                    '{{WRAPPER}} ' . $css_selector['control_list'] . ':hover' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'tab_hover_border_type!' => 'none',
                ],
            ]
        );

        $this->add_responsive_control(
            'tab_hover_border_radius',
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
                    '{{WRAPPER}} ' . $css_selector['control_list'] . ':hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_active_style',
            [
                'label' => esc_html__('Active', 'king-addons'),
            ]
        );

        $this->add_control(
            'tab_active_color',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#5B03FF',
                'selectors' => [
                    '{{WRAPPER}} ' . $css_selector['control_list'] . '.king-addons-tab-active .king-addons-tab-title' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'tab_active_icon_color',
            [
                'label' => esc_html__('Icon Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#333333',
                'selectors' => [
                    '{{WRAPPER}} ' . $css_selector['control_list'] . '.king-addons-tab-active .king-addons-tab-icon' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'tab_active_bg_color',
            [
                'label' => esc_html__('Background Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} ' . $css_selector['control_list'] . '.king-addons-tab-active' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}}.king-addons-tabs-position-above.king-addons-tabs-triangle-type-outer ' . $css_selector['control_list'] . ':before' => 'border-top-color: {{VALUE}}',


                ],
            ]
        );

        $this->add_control(
            'tab_active_border_color',
            [
                'label' => esc_html__('Border Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#e5e5e5',
                'selectors' => [
                    '{{WRAPPER}} ' . $css_selector['control_list'] . '.king-addons-tab-active' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'tab_active_box_shadow',
                'selector' => '{{WRAPPER}} ' . $css_selector['control_list'] . '.king-addons-tab-active',
            ]
        );

        $this->add_control(
            'tab_active_typography_divider',
            [
                'type' => Controls_Manager::DIVIDER,
                'style' => 'thick',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'tab_active_typography',
                'selector' => '{{WRAPPER}} ' . $css_selector['control_list'] . '.king-addons-tab-active .king-addons-tab-title',
            ]
        );

        $this->add_responsive_control(
            'tab_active_icon_size',
            [
                'label' => esc_html__('Icon Size', 'king-addons'),
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
                    'size' => 15,
                ],
                'selectors' => [
                    '{{WRAPPER}} ' . $css_selector['control_list'] . '.king-addons-tab-active .king-addons-tab-icon i' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} ' . $css_selector['control_list'] . '.king-addons-tab-active .king-addons-tab-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} ' . $css_selector['control_list'] . '.king-addons-tab-active .king-addons-tab-image' => 'width: {{SIZE}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'tab_active_padding',
            [
                'label' => esc_html__('Padding', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px',],
                'default' => [
                    'top' => 10,
                    'right' => 10,
                    'bottom' => 10,
                    'left' => 10,
                ],
                'selectors' => [
                    '{{WRAPPER}} ' . $css_selector['control_list'] . '.king-addons-tab-active' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'tab_active_margin',
            [
                'label' => esc_html__('Margin', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px',],
                'default' => [
                    'top' => 0,
                    'right' => 0,
                    'bottom' => -1,
                    'left' => 0,
                ],
                'selectors' => [
                    '{{WRAPPER}} ' . $css_selector['control_list'] . '.king-addons-tab-active' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'tab_active_border_type',
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
                    '{{WRAPPER}} ' . $css_selector['control_list'] . '.king-addons-tab-active' => 'border-style: {{VALUE}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'tab_active_border_width',
            [
                'label' => esc_html__('Border Width', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px',],
                'default' => [
                    'top' => 1,
                    'right' => 1,
                    'bottom' => 0,
                    'left' => 1,
                ],
                'selectors' => [
                    '{{WRAPPER}} ' . $css_selector['control_list'] . '.king-addons-tab-active' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'tab_active_border_type!' => 'none',
                ],
            ]
        );

        $this->add_responsive_control(
            'tab_active_border_radius',
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
                    '{{WRAPPER}} ' . $css_selector['control_list'] . '.king-addons-tab-active' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_control(
            'tab_transition_duration',
            [
                'label' => esc_html__('Transition Duration (seconds)', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'default' => 0.1,
                'min' => 0,
                'max' => 5,
                'step' => 0.1,
                'frontend_available' => true,
                'selectors' => [
                    '{{WRAPPER}} ' . $css_selector['control_list'] => '-webkit-transition-duration: {{VALUE}}s; transition-duration: {{VALUE}}s',
                    '{{WRAPPER}}.king-addons-tabs-triangle-type-outer ' . $css_selector['control_list'] . ':before' => '-webkit-transition-duration: {{VALUE}}s; transition-duration: {{VALUE}}s',
                ],
                'separator' => 'after',
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

        $this->add_control(
            'content_color',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#5B03FF',
                'selectors' => [
                    '{{WRAPPER}} ' . $css_selector['content_list'] => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'content_bg_color',
            [
                'label' => esc_html__('Background Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} ' . $css_selector['content_wrap'] => 'background-color: {{VALUE}}',
                    '{{WRAPPER}}.king-addons-tabs-position-above.king-addons-tabs-triangle-type-inner ' . $css_selector['control_list'] . ':before' => 'border-top-color: {{VALUE}}',
                    '{{WRAPPER}}.king-addons-tabs-position-right.king-addons-tabs-triangle-type-inner ' . $css_selector['control_list'] . ':before' => 'border-right-color: {{VALUE}}',
                    '{{WRAPPER}}.king-addons-tabs-position-left.king-addons-tabs-triangle-type-inner ' . $css_selector['control_list'] . ':before' => 'border-right-color: {{VALUE}}',
                ],
                'separator' => 'after',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'content_box_shadow',
                'selector' => '{{WRAPPER}} ' . $css_selector['content_wrap'],
            ]
        );

        $this->add_control(
            'content_box_shadow_divider',
            [
                'type' => Controls_Manager::DIVIDER,
                'style' => 'thick',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'content_typography',
                'selector' => '{{WRAPPER}} ' . $css_selector['content_list'] . ', {{WRAPPER}} ' . $css_selector['content_list'] . ' ul',
            ]
        );

        $this->add_responsive_control(
            'content_padding',
            [
                'label' => esc_html__('Padding', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'default' => [
                    'top' => 25,
                    'right' => 25,
                    'bottom' => 25,
                    'left' => 25,
                ],
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} ' . $css_selector['content_list'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} ' . $css_selector['content_wrap'] => 'border-style: {{VALUE}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'content_border_width',
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
                    '{{WRAPPER}} ' . $css_selector['content_wrap'] => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                'default' => '#E8E8E8',
                'selectors' => [
                    '{{WRAPPER}} ' . $css_selector['content_wrap'] => 'border-color: {{VALUE}}',
                ],
                'condition' => [
                    'content_border_type!' => 'none',
                ],
            ]
        );

        $this->add_responsive_control(
            'content_border_radius',
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
                    '{{WRAPPER}} ' . $css_selector['content_wrap'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_triangle',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Triangle', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'tab_triangle',
            [
                'label' => esc_html__('Triangle', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'prefix_class' => 'king-addons-tabs-triangle-',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'tab_triangle_type',
            [
                'label' => esc_html__('Triangle Points to', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'outer',
                'options' => [
                    'inner' => esc_html__('Tab', 'king-addons'),
                    'outer' => esc_html__('Content', 'king-addons'),
                ],
                'prefix_class' => 'king-addons-tabs-triangle-type-',
                'render_type' => 'template',
                'condition' => [
                    'tab_triangle' => 'yes',
                ],
            ]
        );

        $this->add_responsive_control(
            'tab_triangle_size',
            [
                'type' => Controls_Manager::SLIDER,
                'label' => esc_html__('Size', 'king-addons'),
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 30,
                    ]
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 7,
                ],
                'selectors' => [
                    '{{WRAPPER}} ' . $css_selector['control_list'] . ':before' => 'border-width: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}}.king-addons-tabs-position-above.king-addons-tabs-triangle-type-outer ' . $css_selector['control_list'] . ':before' => 'bottom: -{{SIZE}}{{UNIT}};',
                    '{{WRAPPER}}.king-addons-tabs-position-right.king-addons-tabs-triangle-type-outer ' . $css_selector['control_list'] . ':before' => 'left: -{{SIZE}}{{UNIT}};',
                    '{{WRAPPER}}.king-addons-tabs-position-left.king-addons-tabs-triangle-type-outer ' . $css_selector['control_list'] . ':before' => 'right: -{{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'tab_triangle' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();

    }

    public function king_addons_tabs_template($id)
    {
        if (empty($id)) return '';
        if (defined('ICL_LANGUAGE_CODE')) {
            $default_language_code = apply_filters('wpml_default_language', null);
            /** @noinspection PhpUndefinedConstantInspection */
            if (ICL_LANGUAGE_CODE !== $default_language_code) {
                /** @noinspection PhpUndefinedConstantInspection */
                /** @noinspection PhpUndefinedFunctionInspection */
                $id = icl_object_id($id, 'elementor_library', false, ICL_LANGUAGE_CODE);
            }
        }
        $edit_link = '<a class="king-addons-template-edit-btn" href="' . esc_url(get_permalink($id)) . '">Edit Template</a>';
        $type = get_post_meta(get_the_ID(), '_king_addons_template_type', true);
        $has_css = ('internal' === get_option('elementor_css_print_method') || '' !== $type);
        return Plugin::instance()->frontend->get_builder_content_for_display($id, $has_css) . $edit_link;
    }

    protected function render()
    {
        $settings = $this->get_settings();

        // Force fallback settings if premium code isn't available
        if (!king_addons_freemius()->can_use_premium_code__premium_only()) {
            $settings['active_tab'] = 1;
            $settings['tabs_trigger'] = 'click';
            $settings['autoplay'] = '';
            $settings['autoplay_duration'] = 0;
            $settings['content_animation'] = 'fade-in';
            $settings['content_anim_size'] = 'large';
        }

        $tabs = $this->get_settings_for_display('tabs');
        $id_int = substr($this->get_id_int(), 0, 3);
        $options = [
            'activeTab' => $settings['active_tab'],
            'trigger' => $settings['tabs_trigger'],
            'autoplay' => $settings['autoplay'] ?? '',
            'autoplaySpeed' => absint($settings['autoplay_duration'] * 1000),
        ];

        $this->add_render_attribute('tabs-attribute', [
            'class' => 'king-addons-tabs',
            'data-options' => wp_json_encode($options),
        ]);

        echo '<div ' . $this->get_render_attribute_string('tabs-attribute') . '>';
        echo '<div class="king-addons-tabs-wrap">';

        foreach ($tabs as $index => $item):
            if (!king_addons_freemius()->can_use_premium_code__premium_only()) {
                // Switch 'pro-tmp' to 'editor' in free version and limit tabs
                $item['tab_content_type'] = ('pro-tmp' === $item['tab_content_type']) ? 'editor' : $item['tab_content_type'];
                if ($index === 3) break;
            }
            $tab_count = $index + 1;
            $tab_setting_key = $this->get_repeater_setting_key('tab_control', 'tabs', $index);
            $tab_image_src = !empty($item['tab_image']['id'])
                ? Group_Control_Image_Size::get_attachment_image_src($item['tab_image']['id'], 'tabs_image_size', $settings)
                : false;
            if (!$tab_image_src && !empty($item['tab_image']['url'])) {
                $tab_image_src = $item['tab_image']['url'];
            }

            $this->add_render_attribute($tab_setting_key, [
                'id' => 'king-addons-tab-' . $id_int . $tab_count,
                'class' => ['king-addons-tab', 'elementor-repeater-item-' . $item['_id']],
                'data-tab' => $tab_count,
            ]);

            echo '<div ' . $this->get_render_attribute_string($tab_setting_key) . '>';
            if (!empty($item['tab_title'])) {
                echo '<div class="king-addons-tab-title">' . esc_html($item['tab_title']) . '</div>';
            }
            if ('icon' === $item['tab_icon_type'] && !empty($item['tab_icon']['value'])) {
                echo '<div class="king-addons-tab-icon"><i class="' . esc_attr($item['tab_icon']['value']) . '"></i></div>';
            } elseif ('image' === $item['tab_icon_type'] && $tab_image_src) {
                echo '<div class="king-addons-tab-image"><img alt="' . esc_attr($item['tab_image']['alt'] ?? '') . '" src="' . esc_url($tab_image_src) . '"></div>';
            }
            echo '</div>';
        endforeach;

        echo '</div><div class="king-addons-tabs-content-wrap">';

        foreach ($tabs as $index => $item):
            $tab_count = $index + 1;
            $content_key = $this->get_repeater_setting_key('tab_content', 'tabs', $index);
            $this->add_render_attribute($content_key, [
                'id' => 'king-addons-tab-content-' . $id_int . $tab_count,
                'class' => ['king-addons-tab-content', 'elementor-repeater-item-' . $item['_id']],
                'data-tab' => $tab_count,
            ]);

            echo '<div ' . $this->get_render_attribute_string($content_key) . '>';
            echo '<div class="king-addons-tab-content-inner elementor-clearfix king-addons-anim-size-' . esc_attr($settings['content_anim_size']) . ' king-addons-overlay-' . esc_attr($settings['content_animation']) . '">';

            if ('template' === $item['tab_content_type']) {
                echo $this->king_addons_tabs_template($item['select_template']);
            } elseif ('editor' === $item['tab_content_type']) {
                echo wp_kses_post($item['tab_content']);
            } elseif ('acf' === $item['tab_content_type']) {
                echo wp_kses_post(get_post_meta(get_the_ID(), $item['tab_custom_field'], true));
            }

            echo '</div></div>';
        endforeach;

        echo '</div></div>';
    }
}