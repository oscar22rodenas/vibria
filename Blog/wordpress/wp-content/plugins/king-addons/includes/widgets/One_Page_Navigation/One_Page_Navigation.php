<?php /** @noinspection SpellCheckingInspection, PhpUnused */

namespace King_Addons;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;
use Elementor\Repeater;
use Elementor\Widget_Base;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class One_Page_Navigation extends Widget_Base
{
    public function get_name(): string
    {
        return 'king-addons-one-page-navigation';
    }

    public function get_title(): string
    {
        return esc_html__('One Page Navigation', 'king-addons');
    }

    public function get_icon(): string
    {
        /** @noinspection SpellCheckingInspection */
        return 'king-addons-icon king-addons-one-page-navigation';
    }

    public function get_style_depends(): array
    {
        return [KING_ADDONS_ASSETS_UNIQUE_KEY . '-one-page-navigation-style'];
    }

    public function get_categories(): array
    {
        return ['king-addons'];
    }

    public function get_keywords(): array
    {
        return ['one page', 'one', 'page', 'onepage', 'sinle', 'nav', 'navigation', 'interactive', 'anchor', 'link',
            'links', 'href', 'scroll', 'scrolling', 'scroller', 'scrollable', 'animation', 'effect', 'animated',
            'floating', 'sticky', 'click', 'target', 'point', 'vertical', 'horizontal', 'mouse', 'hover', 'over',
            'hover over', 'icon', 'king', 'addons', 'mouseover', 'kingaddons', 'king-addons'];
    }

    public function get_custom_help_url(): string
    {
        return 'https://kingaddons.com/elementor/one-page-navigation/';
    }

    protected function register_controls(): void
    {
        /** START TAB: CONTENT ===================== */
        /** SECTION: General ===================== */
        $this->start_controls_section(
            'kng_one_page_nav_section_nav_items',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Navigation Items', 'king-addons'),
            ]
        );

        $this->add_control(
            'kng_one_page_nav_items_p_h',
            [
                'label' => esc_html__('Horizontal Position', 'king-addons'),
                'type' => Controls_Manager::CHOOSE,
                'default' => 'right',
                'options' => [
                    'left' => [
                        'title' => esc_html__('Left', 'king-addons'),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'right' => [
                        'title' => esc_html__('Right', 'king-addons'),
                        'icon' => 'eicon-h-align-right',
                    ]
                ],
                'prefix_class' => 'king-addons-one-page-nav-items-p-h-'
            ]
        );

        $this->add_control(
            'kng_one_page_nav_items_p_v',
            [
                'label' => esc_html__('Vertical Position', 'king-addons'),
                'type' => Controls_Manager::CHOOSE,
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
                'prefix_class' => 'king-addons-one-page-nav-items-p-v-'
            ]
        );

        $this->add_control(
            'kng_one_page_nav_show_tooltip',
            [
                'label' => esc_html__('Show Tooltip', 'king-addons'),
                'type' => Controls_Manager::SWITCHER
            ]
        );

        $this->add_control(
            'kng_one_page_nav_v_stretch',
            [
                'label' => esc_html__('Stretch Vertically', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'prefix_class' => 'king-addons-one-page-nav-v-stretch-',
                'frontend_available' => true,
                'separator' => 'after'
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'kng_one_page_nav_item_id',
            [
                'label' => esc_html__('Section ID', 'king-addons'),
                'type' => Controls_Manager::TEXT,
                'description' => esc_html__('Section ID without #', 'king-addons'),
                'default' => 'section-id',
            ]
        );

        $repeater->add_control(
            'kng_one_page_nav_item_name',
            [
                'label' => esc_html__('Section Name (Optional)', 'king-addons'),
                'type' => Controls_Manager::TEXT,
                'description' => esc_html__('It is used for tooltip', 'king-addons'),
            ]
        );

        $repeater->add_control(
            'kng_one_page_nav_item_icon',
            [
                'label' => esc_html__('Select Icon', 'king-addons'),
                'type' => Controls_Manager::ICONS,
                'skin' => 'inline',
                'label_block' => false,
                'default' => [
                    'value' => 'fas fa-home',
                    'library' => 'fa-solid',
                ],
            ]
        );

        $repeater->add_control(
            'kng_one_page_nav_item_custom_colors',
            [
                'label' => esc_html__('Custom Colors', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'description' => esc_html__('Use custom colors for this icon instead of global from the Style tab.', 'king-addons'),
                'separator' => 'before',
            ]
        );

        /** CONTROLS TABS ===================== */
        $repeater->start_controls_tabs(
            'kng_one_page_nav_item_style_custom',
            [
                'condition' => [
                    'kng_one_page_nav_item_custom_colors' => 'yes',
                ],
            ]
        );

        /** CONTROLS TAB: Normal ===================== */
        $repeater->start_controls_tab(
            'kng_one_page_nav_item_normal_custom',
            [
                'label' => esc_html__('Normal', 'king-addons'),
            ]
        );

        $repeater->add_control(
            'kng_one_page_nav_item_color_custom',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}.king-addons-one-page-nav-item i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} {{CURRENT_ITEM}}.king-addons-one-page-nav-item svg' => 'fill: {{VALUE}};'
                ],
            ]
        );

        $repeater->add_control(
            'kng_one_page_nav_item_bg_color_custom',
            [
                'label' => esc_html__('Background Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}.king-addons-one-page-nav-item i' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} {{CURRENT_ITEM}}.king-addons-one-page-nav-item svg' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $repeater->add_control(
            'kng_one_page_nav_item_border_color_custom',
            [
                'label' => esc_html__('Border Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}.king-addons-one-page-nav-item i' => 'border-color: {{VALUE}};',
                    '{{WRAPPER}} {{CURRENT_ITEM}}.king-addons-one-page-nav-item svg' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $repeater->end_controls_tab();
        /** END CONTROLS TAB: Normal ===================== */

        /** CONTROLS TAB: Hover ===================== */
        $repeater->start_controls_tab(
            'kng_one_page_nav_item_hover_custom',
            [
                'label' => esc_html__('Hover', 'king-addons'),
            ]
        );

        $repeater->add_control(
            'kng_one_page_nav_item_hover_color_custom',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}.king-addons-one-page-nav-item:hover i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} {{CURRENT_ITEM}}.king-addons-one-page-nav-item:hover svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $repeater->add_control(
            'kng_one_page_nav_item_hover_bg_color_custom',
            [
                'label' => esc_html__('Background Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}.king-addons-one-page-nav-item:hover i' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} {{CURRENT_ITEM}}.king-addons-one-page-nav-item:hover svg' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $repeater->add_control(
            'kng_one_page_nav_item_hover_border_color_custom',
            [
                'label' => esc_html__('Border Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}.king-addons-one-page-nav-item:hover i' => 'border-color: {{VALUE}};',
                    '{{WRAPPER}} {{CURRENT_ITEM}}.king-addons-one-page-nav-item:hover svg' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $repeater->end_controls_tab();
        $repeater->end_controls_tabs();
        /** END CONTROLS TAB: Hover ===================== */
        /** END CONTROLS TABS ===================== */

        $this->add_control(
            'kng_one_page_nav_items',
            [
                'label' => esc_html__('Navigation Items', 'king-addons'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'kng_one_page_nav_item_id' => 'first-section',
                        'kng_one_page_nav_item_name' => 'First Section',
                        'kng_one_page_nav_item_icon' => [
                            'value' => 'fas fa-home',
                            'library' => 'fa-solid',
                        ],
                    ],
                    [
                        'kng_one_page_nav_item_id' => 'second-section',
                        'kng_one_page_nav_item_name' => 'Second Section',
                        'kng_one_page_nav_item_icon' => [
                            'value' => 'far fa-star',
                            'library' => 'fa-solid',
                        ],
                    ],
                    [
                        'kng_one_page_nav_item_id' => 'third-section',
                        'kng_one_page_nav_item_name' => 'Third Section',
                        'kng_one_page_nav_item_icon' => [
                            'value' => 'fas fa-cat',
                            'library' => 'fa-solid',
                        ],
                    ],
                ],
                'title_field' => '{{{ kng_one_page_nav_item_name }}}',
            ]
        );

        $this->end_controls_section();
        /** END SECTION: General ===================== */
        /** END TAB: CONTENT ===================== */

        /** TAB: Style ===================== */
        /** SECTION: Navigation Box ===================== */
        $this->start_controls_section(
            'kng_one_page_nav_section_nav_box',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Navigation Box', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'kng_one_page_nav_box_bg_color',
                'types' => ['classic', 'gradient'],
                'fields_options' => [
                    'color' => [
                        'default' => '#574ff7',
                    ],
                    'background' => [
                        'default' => 'classic',
                    ],
                ],
                'exclude' => ['image'],
                'selector' => '{{WRAPPER}} .king-addons-one-page-nav'
            ]
        );

        $this->add_control(
            'kng_one_page_nav_box_border_color',
            [
                'label' => esc_html__('Border Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#000000',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-one-page-nav' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'kng_one_page_nav_box_box_shadow',
                'selector' => '{{WRAPPER}} .king-addons-one-page-nav',
            ]
        );

        $this->add_responsive_control(
            'kng_one_page_nav_box_items_gap',
            [
                'label' => esc_html__('Items Gap', 'king-addons'),
                'description' => esc_html__('Space between navigation items', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'step' => 1,
                'default' => 10,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-one-page-nav-item' => 'margin-bottom: {{SIZE}}px;',
                ],
                'separator' => 'before'
            ]
        );

        $this->add_responsive_control(
            'kng_one_page_nav_box_padding',
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
                    '{{WRAPPER}} .king-addons-one-page-nav' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'kng_one_page_nav_box_margin',
            [
                'label' => esc_html__('Margin', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'default' => [
                    'top' => 0,
                    'right' => 0,
                    'bottom' => 0,
                    'left' => 0,
                ],
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-one-page-nav' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'kng_one_page_nav_v_stretch!' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'kng_one_page_nav_box_border_type',
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
                    '{{WRAPPER}} .king-addons-one-page-nav' => 'border-style: {{VALUE}};',
                ],
                'separator' => 'before'
            ]
        );

        $this->add_responsive_control(
            'kng_one_page_nav_box_border_width',
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
                    '{{WRAPPER}} .king-addons-one-page-nav' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'kng_one_page_nav_box_border_type!' => 'none',
                ],
            ]
        );

        $this->add_responsive_control(
            'kng_one_page_nav_box_border_radius',
            [
                'label' => esc_html__('Border Radius', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'default' => [
                    'top' => 5,
                    'right' => 0,
                    'bottom' => 0,
                    'left' => 5,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-one-page-nav' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before'
            ]
        );

        $this->end_controls_section();
        /** END SECTION: Navigation Box ===================== */

        /** SECTION: Navigation Item ===================== */
        $this->start_controls_section(
            'kng_one_page_nav_section_nav_item',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Navigation Item', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
                'show_label' => false
            ]
        );

        /** CONTROLS TABS ===================== */
        $this->start_controls_tabs('kng_one_page_nav_item_style');

        /** CONTROLS TAB: Normal ===================== */
        $this->start_controls_tab(
            'kng_one_page_nav_item_normal',
            [
                'label' => esc_html__('Normal', 'king-addons'),
            ]
        );

        $this->add_control(
            'kng_one_page_nav_item_color',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-one-page-nav-item i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .king-addons-one-page-nav-item svg' => 'fill: {{VALUE}};'
                ],
            ]
        );

        $this->add_control(
            'kng_one_page_nav_item_bg_color',
            [
                'label' => esc_html__('Background Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-one-page-nav-item i' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .king-addons-one-page-nav-item svg' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'kng_one_page_nav_item_border_color',
            [
                'label' => esc_html__('Border Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#E8E8E8',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-one-page-nav-item i' => 'border-color: {{VALUE}};',
                    '{{WRAPPER}} .king-addons-one-page-nav-item svg' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'kng_one_page_nav_item_box_shadow',
                'selector' => '{{WRAPPER}} .king-addons-one-page-nav-item i, {{WRAPPER}} .king-addons-one-page-nav-item svg',
            ]
        );

        $this->end_controls_tab();
        /** END CONTROLS TAB: Normal ===================== */

        /** CONTROLS TAB: Hover ===================== */
        $this->start_controls_tab(
            'kng_one_page_nav_item_hover',
            [
                'label' => esc_html__('Hover', 'king-addons'),
            ]
        );

        $this->add_control(
            'kng_one_page_nav_item_hover_color',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#01FD0D',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-one-page-nav-item:hover i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .king-addons-one-page-nav-item:hover svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'kng_one_page_nav_item_hover_bg_color',
            [
                'label' => esc_html__('Background Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-one-page-nav-item:hover i' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .king-addons-one-page-nav-item:hover svg' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'kng_one_page_nav_item_hover_border_color',
            [
                'label' => esc_html__('Border Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-one-page-nav-item:hover i' => 'border-color: {{VALUE}};',
                    '{{WRAPPER}} .king-addons-one-page-nav-item:hover svg' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'kng_one_page_nav_item_hover_box_shadow',
                'selector' => '{{WRAPPER}} .king-addons-one-page-nav-item:hover i, {{WRAPPER}} .king-addons-one-page-nav-item:hover svg',
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();
        /** END CONTROLS TAB: Hover ===================== */
        /** END CONTROLS TABS ===================== */


        $this->add_control(
            'kng_one_page_nav_item_transition_duration',
            [
                'label' => esc_html__('Transition Duration (ms)', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'default' => 100,
                'min' => 0,
                'max' => 5000,
                'step' => 100,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-one-page-nav-item' => 'transition-duration: {{SIZE}}ms',
                    '{{WRAPPER}} .king-addons-one-page-nav-item i' => 'transition-duration: {{SIZE}}ms',
                    '{{WRAPPER}} .king-addons-one-page-nav-item svg' => 'transition-duration: {{SIZE}}ms',
                ],
                'separator' => 'before'
            ]
        );

        $this->add_responsive_control(
            'kng_one_page_nav_item_icon_size',
            [
                'label' => esc_html__('Icon Size', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'default' => 16,
                'min' => 0,
                'step' => 1,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-one-page-nav-item i' => 'font-size: {{SIZE}}px;',
                    '{{WRAPPER}} .king-addons-one-page-nav-item svg' => 'width: {{SIZE}}px; height: {{SIZE}}px;',
                ],
                'separator' => 'before'
            ]
        );

        $this->add_responsive_control(
            'kng_one_page_nav_item_padding',
            [
                'label' => esc_html__('Padding', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'default' => [
                    'top' => 3,
                    'right' => 3,
                    'bottom' => 3,
                    'left' => 3,
                ],
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-one-page-nav-item i' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .king-addons-one-page-nav-item svg' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'kng_one_page_nav_item_border_type',
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
                    '{{WRAPPER}} .king-addons-one-page-nav-item i' => 'border-style: {{VALUE}};',
                    '{{WRAPPER}} .king-addons-one-page-nav-item svg' => 'border-style: {{VALUE}};',
                ],
                'separator' => 'before'
            ]
        );

        $this->add_responsive_control(
            'kng_one_page_nav_item_border_width',
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
                    '{{WRAPPER}} .king-addons-one-page-nav-item i' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .king-addons-one-page-nav-item svg' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'kng_one_page_nav_item_border_type!' => 'none',
                ],
            ]
        );

        $this->add_responsive_control(
            'kng_one_page_nav_item_border_radius',
            [
                'label' => esc_html__('Border Radius', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'default' => 0,
                'min' => 0,
                'step' => 1,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-one-page-nav-item i' => 'border-radius: {{SIZE}}px;',
                    '{{WRAPPER}} .king-addons-one-page-nav-item svg' => 'border-radius: {{SIZE}}px;',
                ],
                'separator' => 'before'
            ]
        );

        $this->end_controls_section();
        /** END SECTION: Navigation Item ===================== */

        /** SECTION: Navigation Tooltip ===================== */
        $this->start_controls_section(
            'kng_one_page_nav_section_nav_tooltip',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Navigation Tooltip (optional)', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
                'show_label' => false
            ]
        );

        $this->add_control(
            'kng_one_page_nav_tooltip_txt_color',
            [
                'label' => esc_html__('Text Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#f2f2f2',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-one-page-nav-item .king-addons-one-page-nav-tooltip' => 'color: {{VALUE}};'
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => esc_html__('Typography', 'king-addons'),
                'name' => 'kng_one_page_nav_tooltip_txt_typography',
                'selector' => '{{WRAPPER}} .king-addons-one-page-nav-item .king-addons-one-page-nav-tooltip',
            ]
        );

        $this->add_control(
            'kng_one_page_nav_tooltip_bg_color',
            [
                'label' => esc_html__('Background Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#303030',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-one-page-nav-item .king-addons-one-page-nav-tooltip' => 'background: {{VALUE}};',
                    '{{WRAPPER}} .king-addons-one-page-nav-item .king-addons-one-page-nav-tooltip:before' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .king-addons-one-page-nav-item .king-addons-one-page-nav-tooltip:after' => 'color: {{VALUE}};'
                ],
            ]
        );

        $this->add_responsive_control(
            'kng_one_page_nav_tooltip_border_radius',
            [
                'label' => esc_html__('Border Radius', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'default' => 4,
                'min' => 0,
                'step' => 1,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-one-page-nav-item .king-addons-one-page-nav-tooltip' => 'border-radius: {{SIZE}}px;',
                ],
            ]
        );

        $this->end_controls_section();
        /** END SECTION: Navigation Tooltip ===================== */
        /** END TAB: STYLE ===================== */
    }

    protected function render(): void
    {
        $settings = $this->get_settings();

        echo '<div class="king-addons-one-page-nav">';

        foreach ($settings['kng_one_page_nav_items'] as $item) {
            echo '<div class="king-addons-one-page-nav-item elementor-repeater-item-' . esc_attr($item['_id']) . '">';
            echo '<a href="#' . esc_attr($item['kng_one_page_nav_item_id']) . '">';
            echo ('yes' === esc_html($settings['kng_one_page_nav_show_tooltip'])) ? '<span class="king-addons-one-page-nav-tooltip">' . esc_html($item['kng_one_page_nav_item_name']) . '</span>' : '';
            Icons_Manager::render_icon($item['kng_one_page_nav_item_icon']);
            echo '</a>';
            echo '</div>';
        }

        echo '</div>';
    }
}