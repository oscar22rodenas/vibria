<?php /** @noinspection SpellCheckingInspection, PhpUnused */

namespace King_Addons;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;
use Elementor\Repeater;
use Elementor\Utils;
use Elementor\Widget_Base;

/** @noinspection SpellCheckingInspection */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class Image_Hotspots extends Widget_Base
{
    public function get_name(): string
    {
        return 'king-addons-image-hotspots';
    }

    public function get_title(): string
    {
        return esc_html__('Image Hotspots', 'king-addons');
    }

    public function get_icon(): string
    {
        return 'king-addons-icon king-addons-image-hotspots';
    }

    public function get_style_depends(): array
    {
        return [KING_ADDONS_ASSETS_UNIQUE_KEY . '-image-hotspots-style'];
    }

    public function get_categories(): array
    {
        return ['king-addons'];
    }

    public function get_keywords(): array
    {
        return ['image', 'images', 'hotspot', 'hotspots', 'popup', 'animation', 'animated', 'spot', 'spots', 'effect',
            'interactive', 'click', 'target', 'point', 'dot', 'dots', 'point', 'points', 'pulsation', 'mouse', 'hover',
            'over', 'hover over', 'picture', 'king', 'addons', 'kingaddons', 'king-addons', 'hot', 'hot spot'];
    }

    public function get_custom_help_url(): string
    {
        return 'https://kingaddons.com/elementor/image-hotspots/';
    }

    protected function register_controls(): void
    {
        /** START TAB: CONTENT ===================== */
        /** SECTION: Image ===================== */
        $this->start_controls_section(
            'kng_img_hotspots_section_image',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Image', 'king-addons'),
            ]
        );

        $this->add_control(
            'kng_img_hotspots_image',
            [
                'label' => esc_html__('Image', 'king-addons'),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name' => 'kng_img_hotspots_image_size',
                'default' => 'full',
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'kng_img_hotspots_image_border',
                'selector' => '{{WRAPPER}} .king-addons-image-hotspots-image',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'kng_img_hotspots_image_shadow',
                'selector' => '{{WRAPPER}} .king-addons-image-hotspots-image',
            ]
        );

        $this->add_responsive_control(
            'kng_img_hotspots_image_border_radius',
            [
                'label' => esc_html__('Border Radius', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-image-hotspots-image' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->end_controls_section();
        /** END SECTION: Image ===================== */

        /** SECTION: Hotspots ===================== */
        $this->start_controls_section(
            'kng_img_hotspots_section_hotspots',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Hotspots', 'king-addons'),
            ]
        );

        $repeater = new Repeater();

        $repeater->start_controls_tabs('kng_img_hotspots_tabs_hotspot_item');

        $repeater->start_controls_tab(
            'kng_img_hotspots_tab_hotspot_item_content',
            [
                'label' => esc_html__('Content', 'king-addons'),
            ]
        );

        $repeater->add_control(
            'kng_img_hotspots_hotspot_icon',
            [
                'label' => esc_html__('Icon', 'king-addons'),
                'type' => Controls_Manager::ICONS,
                'skin' => 'inline',
                'label_block' => false,
                'default' => [
                    'value' => 'fas fa-plus',
                    'library' => 'fa-solid',
                ],
            ]
        );

        $repeater->add_control(
            'kng_img_hotspots_hotspot_icon_text',
            [
                'label' => esc_html__('Icon Text', 'king-addons'),
                'type' => Controls_Manager::TEXT,
            ]
        );

        $repeater->add_control(
            'kng_img_hotspots_hotspot_icon_link',
            [
                'label' => esc_html__('Icon Link', 'king-addons'),
                'type' => Controls_Manager::URL,
                'separator' => 'before',
            ]
        );

        /** @noinspection SpellCheckingInspection */
        $repeater->add_control(
            'kng_img_hotspots_icon_position_custom_reverse_switcher',
            [
                'label' => esc_html__('Reverse Icon Position', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'description' => esc_html__('Reverse icon position for this hotspot instead of global from the Style tab.', 'king-addons'),
                'separator' => 'before',
            ]
        );

        $repeater->add_control(
            'kng_img_hotspots_icon_size_custom_switcher',
            [
                'label' => esc_html__('Custom Icon Size', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'description' => esc_html__('Use custom icon size for this hotspot instead of global from the Style tab.', 'king-addons'),
                'separator' => 'before',
            ]
        );

        $repeater->add_responsive_control(
            'kng_img_hotspots_icon_size_custom',
            [
                'label' => esc_html__('Icon Size', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'step' => 1,
                'min' => 0,
                'default' => 15,
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}.king-addons-hotspot-content i' => 'font-size: {{VALUE}}px; width: {{VALUE}}px;',
                    '{{WRAPPER}} {{CURRENT_ITEM}}.king-addons-hotspot-content img' => 'width: {{VALUE}}px; height: {{VALUE}}px;',
                    '{{WRAPPER}} {{CURRENT_ITEM}}.king-addons-hotspot-content svg' => 'width: {{VALUE}}px; height: {{VALUE}}px;'
                ],
                'condition' => [
                    'kng_img_hotspots_icon_size_custom_switcher' => 'yes',
                ],
            ]
        );

        $repeater->add_control(
            'kng_img_hotspots_hotspot_custom_colors',
            [
                'label' => esc_html__('Custom Colors', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'description' => esc_html__('Use custom colors for this hotspot instead of global from the Style tab.', 'king-addons'),
                'separator' => 'before',
            ]
        );

        $repeater->add_control(
            'kng_img_hotspots_hotspot_custom_icon_text_color',
            [
                'label' => esc_html__('Icon & Text Color (optional)', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}.king-addons-hotspot-content' => 'color: {{VALUE}}',
                    '{{WRAPPER}} {{CURRENT_ITEM}}.king-addons-hotspot-content svg' => 'fill: {{VALUE}}',
                ],
                'condition' => [
                    'kng_img_hotspots_hotspot_custom_colors' => 'yes',
                ],
            ]
        );

        $repeater->add_control(
            'kng_img_hotspots_hotspot_custom_bg_color',
            [
                'label' => esc_html__('Background Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#574ff7',
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}.king-addons-hotspot-content' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} {{CURRENT_ITEM}}.king-addons-hotspot-item:before' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} {{CURRENT_ITEM}}.king-addons-hotspot-item:after' => 'background-color: {{VALUE}}'
                ],
                'condition' => [
                    'kng_img_hotspots_hotspot_custom_colors' => 'yes',
                ],
            ]
        );

        $repeater->add_control(
            'kng_img_hotspots_hotspot_tooltip',
            [
                'label' => esc_html__('Tooltip', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'separator' => 'before',
            ]
        );

        $repeater->add_control(
            'kng_img_hotspots_hotspot_tooltip_always_show',
            [
                'label' => esc_html__('Always show tooltip', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'no',
                'condition' => [
                    'kng_img_hotspots_hotspot_tooltip' => 'yes',
                ],
            ]
        );

        $repeater->add_control(
            'kng_img_hotspots_hotspot_tooltip_content',
            [
                'label' => '',
                'type' => Controls_Manager::WYSIWYG,
                'default' => esc_html__("Hotspot's Tooltip Text", "king-addons"),
                'condition' => [
                    'kng_img_hotspots_hotspot_tooltip' => 'yes',
                ],
            ]
        );

        $repeater->end_controls_tab();

        $repeater->start_controls_tab(
            'kng_img_hotspots_tab_hotspot_item_position',
            [
                'label' => esc_html__('Position', 'king-addons'),
            ]
        );

        $repeater->add_responsive_control(
            'kng_img_hotspots_hotspot_h_position',
            [
                'type' => Controls_Manager::SLIDER,
                'label' => esc_html__('Horizontal Position (%)', 'king-addons'),
                'size_units' => ['%'],
                'range' => [
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ]
                ],
                'default' => [
                    'unit' => '%',
                    'size' => 50,
                ],
                'selectors' => [
                    '{{CURRENT_ITEM}}.king-addons-hotspot-item' => 'left: {{SIZE}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $repeater->add_responsive_control(
            'kng_img_hotspots_hotspot_v_position',
            [
                'type' => Controls_Manager::SLIDER,
                'label' => esc_html__('Vertical Position (%)', 'king-addons'),
                'size_units' => ['%'],
                'range' => [
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ]
                ],
                'default' => [
                    'unit' => '%',
                    'size' => 50,
                ],
                'selectors' => [
                    '{{CURRENT_ITEM}}.king-addons-hotspot-item' => 'top: {{SIZE}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $repeater->end_controls_tab();

        $repeater->end_controls_tabs();

        $this->add_control(
            'kng_img_hotspots_hotspot_items',
            [
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'kng_img_hotspots_hotspot_icon_text' => '',
                        'kng_img_hotspots_hotspot_tooltip_content' => 'Hotspot #1',
                        'kng_img_hotspots_hotspot_h_position' => [
                            'unit' => '%',
                            'size' => 35,
                        ],
                        'kng_img_hotspots_hotspot_v_position' => [
                            'unit' => '%',
                            'size' => 40,
                        ],
                    ],
                    [
                        'kng_img_hotspots_hotspot_icon_text' => '',
                        'kng_img_hotspots_hotspot_tooltip_content' => 'Hotspot #2',
                        'kng_img_hotspots_hotspot_h_position' => [
                            'unit' => '%',
                            'size' => 55,
                        ],
                        'kng_img_hotspots_hotspot_v_position' => [
                            'unit' => '%',
                            'size' => 20,
                        ],
                    ],

                ],
                'title_field' => '{{{ kng_img_hotspots_hotspot_tooltip_content }}}',
            ]
        );

        $this->end_controls_section();
        /** END SECTION: Hotspots ===================== */
        /** END TAB: CONTENT ===================== */

        /** TAB: STYLE ===================== */
        /** SECTION: Hotspots ===================== */
        $this->start_controls_section(
            'kng_img_hotspots_section_style_hotspots',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Hotspots', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'kng_img_hotspots_hotspot_color',
            [
                'label' => esc_html__('Icon & Title Color (optional)', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-hotspot-content' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .king-addons-hotspot-content svg' => 'fill: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'kng_img_hotspots_hotspot_bg_color',
            [
                'label' => esc_html__('Background Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#574ff7',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-hotspot-content' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .king-addons-hotspot-item:before' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .king-addons-hotspot-item:after' => 'background-color: {{VALUE}}'
                ],
            ]
        );

        $this->add_control(
            'kng_img_hotspots_hotspot_item_animation',
            [
                'label' => esc_html__('Pulse Animation', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'prefix_class' => 'king-addons-hotspot-item-animation-',
                'frontend_available' => true
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'kng_img_hotspots_hotspot_box_shadow',
                'selector' => '{{WRAPPER}} .king-addons-hotspot-content',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => esc_html__('Title Typography', 'king-addons'),
                'name' => 'kng_img_hotspots_title_typography',
                'selector' => '{{WRAPPER}} .king-addons-hotspot-icon-text',
            ]
        );

        /** Icon ===================== */
        $this->add_control(
            'kng_img_hotspots_icon_section',
            [
                'label' => esc_html__('Icon', 'king-addons'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        /** @noinspection SpellCheckingInspection */
        $this->add_control(
            'kng_img_hotspots_icon_position',
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
                    'right' => [
                        'title' => esc_html__('Right', 'king-addons'),
                        'icon' => 'eicon-h-align-right',
                    ],
                ],
                'prefix_class' => 'king-addons-hotspot-icon-text-position-',
            ]
        );

        $this->add_responsive_control(
            'kng_img_hotspots_icon_size',
            [
                'label' => esc_html__('Icon Size', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'step' => 1,
                'min' => 0,
                'default' => 15,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-hotspot-content i' => 'font-size: {{VALUE}}px; width: {{VALUE}}px;',
                    '{{WRAPPER}} .king-addons-hotspot-content img' => 'width: {{VALUE}}px; height: {{VALUE}}px;',
                    '{{WRAPPER}} .king-addons-hotspot-content svg' => 'width: {{VALUE}}px; height: {{VALUE}}px;'
                ],
            ]
        );

        $this->add_responsive_control(
            'kng_img_hotspots_icon_box_w',
            [
                'label' => esc_html__('Box Minimum Width', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'step' => 1,
                'min' => 0,
                'default' => 40,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-hotspot-content' => 'min-width: {{VALUE}}px;',
                ],
            ]
        );

        $this->add_responsive_control(
            'kng_img_hotspots_icon_box_h',
            [
                'label' => esc_html__('Box Minimum Height', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'step' => 1,
                'min' => 0,
                'default' => 40,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-hotspot-content' => 'min-height: {{VALUE}}px;',
                ],
            ]
        );

        $this->add_responsive_control(
            'kng_img_hotspots_space_b_icon_title',
            [
                'label' => esc_html__('Space between icon and title (px)', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'step' => 1,
                'min' => 0,
                'default' => 10,
                'selectors' => [
                    '{{WRAPPER}}.king-addons-hotspot-icon-text-position-left .king-addons-has-hotspot-icon-text:not(.king-addons-has-hotspot-icon-reverse) i' => 'margin-right: {{VALUE}}px;',
                    '{{WRAPPER}}.king-addons-hotspot-icon-text-position-right .king-addons-has-hotspot-icon-text:not(.king-addons-has-hotspot-icon-reverse) i' => 'margin-left: {{VALUE}}px;',
                    '{{WRAPPER}}.king-addons-hotspot-icon-text-position-left .king-addons-has-hotspot-icon-text:not(.king-addons-has-hotspot-icon-reverse) img' => 'margin-right: {{VALUE}}px;',
                    '{{WRAPPER}}.king-addons-hotspot-icon-text-position-right .king-addons-has-hotspot-icon-text:not(.king-addons-has-hotspot-icon-reverse) img' => 'margin-left: {{VALUE}}px;',
                    '{{WRAPPER}}.king-addons-hotspot-icon-text-position-left .king-addons-has-hotspot-icon-text:not(.king-addons-has-hotspot-icon-reverse) svg' => 'margin-right: {{VALUE}}px;',
                    '{{WRAPPER}}.king-addons-hotspot-icon-text-position-right .king-addons-has-hotspot-icon-text:not(.king-addons-has-hotspot-icon-reverse) svg' => 'margin-left: {{VALUE}}px;',
                    '{{WRAPPER}}.king-addons-hotspot-icon-text-position-left .king-addons-has-hotspot-icon-text.king-addons-has-hotspot-icon-reverse i' => 'margin-left: {{VALUE}}px;',
                    '{{WRAPPER}}.king-addons-hotspot-icon-text-position-right .king-addons-has-hotspot-icon-text.king-addons-has-hotspot-icon-reverse i' => 'margin-right: {{VALUE}}px;',
                    '{{WRAPPER}}.king-addons-hotspot-icon-text-position-left .king-addons-has-hotspot-icon-text.king-addons-has-hotspot-icon-reverse img' => 'margin-left: {{VALUE}}px;',
                    '{{WRAPPER}}.king-addons-hotspot-icon-text-position-right .king-addons-has-hotspot-icon-text.king-addons-has-hotspot-icon-reverse img' => 'margin-right: {{VALUE}}px;',
                    '{{WRAPPER}}.king-addons-hotspot-icon-text-position-left .king-addons-has-hotspot-icon-text.king-addons-has-hotspot-icon-reverse svg' => 'margin-left: {{VALUE}}px;',
                    '{{WRAPPER}}.king-addons-hotspot-icon-text-position-right .king-addons-has-hotspot-icon-text.king-addons-has-hotspot-icon-reverse svg' => 'margin-right: {{VALUE}}px;',
                ],
            ]
        );

        $this->add_responsive_control(
            'kng_img_hotspots_icon_box_padding',
            [
                'label' => esc_html__('Box Padding', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'default' => [
                    'top' => 20,
                    'right' => 20,
                    'bottom' => 20,
                    'left' => 20,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-hotspot-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        /** END: Icon ===================== */

        /** Border ===================== */
        $this->add_control(
            'kng_img_hotspots_border_section',
            [
                'label' => esc_html__('Border', 'king-addons'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'kng_img_hotspots_hotspot_border_type',
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
                    '{{WRAPPER}} .king-addons-hotspot-content' => 'border-style: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'kng_img_hotspots_hotspot_border_color',
            [
                'label' => esc_html__('Border Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#574ff7',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-hotspot-content' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'kng_img_hotspots_hotspot_border_width',
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
                    '{{WRAPPER}} .king-addons-hotspot-content' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'kng_img_hotspots_hotspot_border_type!' => 'none',
                ],
            ]
        );

        $this->add_responsive_control(
            'kng_img_hotspots_hotspot_border_radius',
            [
                'label' => esc_html__('Border Radius', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'default' => [
                    'top' => 100,
                    'right' => 100,
                    'bottom' => 100,
                    'left' => 100,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-hotspot-content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .king-addons-hotspot-item:before' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .king-addons-hotspot-item:after' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );
        /** END: Border ===================== */

        $this->end_controls_section();
        /** END SECTION: Hotspots ===================== */

        /** SECTION: Tooltips ===================== */
        $this->start_controls_section(
            'kng_img_hotspots_section_style_tooltips',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Tooltips', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'kng_img_hotspots_tooltip_animation',
            [
                'label' => esc_html__('Appearing Animation', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'simple' => esc_html__('Simple', 'king-addons'),
                    'shake' => esc_html__('Shake', 'king-addons'),
                    'double-shake' => esc_html__('Double Shake', 'king-addons'),
                    'stretch' => esc_html__('Stretch', 'king-addons'),
                    'floating' => esc_html__('Floating', 'king-addons'),
                ],
                'default' => 'simple',
                'prefix_class' => 'king-addons-hotspot-animation-',
            ]
        );

        $this->add_control(
            'kng_img_hotspots_tooltip_color',
            [
                'label' => esc_html__('Text Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-hotspot-tooltip-content' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'kng_img_hotspots_tooltip_bg_color',
            [
                'type' => Controls_Manager::COLOR,
                'label' => esc_html__('Background Color', 'king-addons'),
                'default' => '#222222',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-hotspot-tooltip-content' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .king-addons-hotspot-tooltip-content:before' => 'border-top-color: {{VALUE}}'
                ],
            ]
        );

        $this->add_responsive_control(
            'kng_img_hotspots_tooltip_width',
            [
                'label' => esc_html__('Tooltip Width', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'min' => 50,
                'step' => 1,
                'default' => 150,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-hotspot-tooltip-content' => 'width: {{SIZE}}px;',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'kng_img_hotspots_tooltip_box_shadow',
                'selector' => '{{WRAPPER}} .king-addons-hotspot-tooltip-content',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'kng_img_hotspots_tooltip_typography',
                'label' => esc_html__('Typography', 'king-addons'),
                'selector' => '{{WRAPPER}} .king-addons-hotspot-tooltip-content',
            ]
        );

        $this->add_responsive_control(
            'kng_img_hotspots_tooltip_padding',
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
                    '{{WRAPPER}} .king-addons-hotspot-tooltip-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'kng_img_hotspots_tooltip_border_radius',
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
                    '{{WRAPPER}} .king-addons-hotspot-tooltip-content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->end_controls_section();
        /** END SECTION: Tooltips ===================== */
        /** END TAB: STYLE ===================== */
    }

    protected function render(): void
    {
        $settings = $this->get_settings();
        /** START: Hotspots Container ===================== */
        ?>
        <div class="king-addons-image-hotspots-container">
            <div class="king-addons-image-hotspots-image">
                <?php
                // Define allowed tags and attributes
                $allowed_tags = wp_kses_allowed_html('post');
                $allowed_tags['img']['srcset'] = true; // Allow srcset attribute for img tag
                $allowed_tags['img']['sizes'] = true; // Allow sizes attribute for img tag
                $allowed_tags['img']['decoding'] = true; // Allow decoding attribute for img tag
                $allowed_tags['img']['loading'] = true; // Allow loading attribute for img tag. It is for the lazy loading possibility.

                $image_html = Group_Control_Image_Size::get_attachment_image_html($settings, 'kng_img_hotspots_image_size', 'kng_img_hotspots_image');
                echo wp_kses($image_html, $allowed_tags);
                ?>
            </div>
            <div class="king-addons-image-hotspots-items">
                <?php

                $item_count = 0;

                /** @noinspection PhpUnusedLocalVariableInspection */
                foreach ($settings['kng_img_hotspots_hotspot_items'] as $key => $item) {

                    $hotspot_tag = 'div';
                    $tooltip_always_show = '';
                    $icon_reverse_position = '';

                    if ('yes' === $item['kng_img_hotspots_hotspot_tooltip_always_show']) {
                        $tooltip_always_show = ' king-addons-hotspot-tooltip-always-show';
                    }

                    if ('yes' === $item['kng_img_hotspots_icon_position_custom_reverse_switcher']) {
                        $icon_reverse_position = ' king-addons-has-hotspot-icon-reverse';
                    }

                    $this->add_render_attribute('kng_img_hotspots_hotspot_item_attribute' . $item_count, 'class', 'elementor-repeater-item-' . esc_attr($item['_id']) . ' king-addons-hotspot-item' . esc_attr($tooltip_always_show));
                    $this->add_render_attribute('kng_img_hotspots_hotspot_content_attribute' . $item_count, 'class', 'elementor-repeater-item-' . esc_attr($item['_id']) . ' king-addons-hotspot-content');

                    if ('' !== $item['kng_img_hotspots_hotspot_icon_text']) {
                        $this->add_render_attribute('kng_img_hotspots_hotspot_content_attribute' . $item_count, 'class', 'king-addons-has-hotspot-icon-text' . esc_attr($icon_reverse_position));
                    } else {
                        $this->add_render_attribute('kng_img_hotspots_hotspot_content_attribute' . $item_count, 'class', 'king-addons-has-not-hotspot-icon-text');
                    }

                    if ('' !== $item['kng_img_hotspots_hotspot_icon_link']['url']) {

                        $hotspot_tag = 'a';

                        $this->add_render_attribute('kng_img_hotspots_hotspot_content_attribute' . $item_count, 'href', esc_url($item['kng_img_hotspots_hotspot_icon_link']['url']));

                        if ($item['kng_img_hotspots_hotspot_icon_link']['is_external']) {
                            $this->add_render_attribute('kng_img_hotspots_hotspot_content_attribute' . $item_count, 'target', '_blank');
                        }

                        if ($item['kng_img_hotspots_hotspot_icon_link']['nofollow']) {
                            $this->add_render_attribute('kng_img_hotspots_hotspot_content_attribute' . $item_count, 'rel', 'nofollow');
                        }

                    }

                    /** START: Hotspot Item ===================== */
                    echo '<div class="elementor-repeater-item-' .
                        esc_attr($item['_id']) . ' king-addons-hotspot-item' .
                        esc_attr($tooltip_always_show) . '">';

                    $html = '<' . esc_attr($hotspot_tag) . ' ' . $this->get_render_attribute_string('kng_img_hotspots_hotspot_content_attribute' . $item_count) . '>';
                    echo wp_kses($html, wp_kses_allowed_html('post'));

                    Icons_Manager::render_icon($item['kng_img_hotspots_hotspot_icon']);

                    if ('' !== $item['kng_img_hotspots_hotspot_icon_text']) {
                        echo '<span class="king-addons-hotspot-icon-text">' . esc_html($item['kng_img_hotspots_hotspot_icon_text']) . '</span>';
                    }

                    echo '</' . esc_attr($hotspot_tag) . '>';

                    if ('yes' === $item['kng_img_hotspots_hotspot_tooltip'] && '' !== $item['kng_img_hotspots_hotspot_tooltip_content']) {
                        echo '<div class="king-addons-hotspot-tooltip-content">';
                        echo wp_kses_post($item['kng_img_hotspots_hotspot_tooltip_content']);
                        echo '</div>';
                    }

                    echo '</div>';
                    /** END: Hotspot Item ===================== */

                    $item_count++;
                } ?>
            </div>
        </div>
        <?php
        /** END: Hotspots Container ===================== */
    }
}