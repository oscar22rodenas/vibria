<?php /** @noinspection SpellCheckingInspection, DuplicatedCode, PhpUnused */

namespace King_Addons;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;
use Elementor\Utils;
use Elementor\Widget_Base;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class Price_List extends Widget_Base
{
    public function get_name(): string
    {
        return 'king-addons-price-list';
    }

    public function get_title(): string
    {
        return esc_html__('Price List & Menu', 'king-addons');
    }

    public function get_icon(): string
    {
        return 'king-addons-icon king-addons-price-list';
    }

    public function get_style_depends(): array
    {
        return [KING_ADDONS_ASSETS_UNIQUE_KEY . '-price-list-style'];
    }

    public function get_categories(): array
    {
        return ['king-addons'];
    }

    public function get_keywords(): array
    {
        return ['rows', 'kingaddons', 'king-addons', 'king', 'addons', 'business price', 'opening', 'working', 'office',
            'store', 'price of operation', 'schedule', 'time', 'availability', 'service', 'services', 'operating', 'shop',
            'list', 'business', 'price', 'times', 'operation', 'list', 'title', 'titles', 'description', 'row', 'column',
            'prices', 'columns', 'pricing', 'menu', 'store', 'cafe', 'restaurant', 'coffee', 'lunch', 'dish', 'dishes',
            'kitchen', 'cook', 'cooking', 'order', 'book', 'rate', 'sheet', 'catalog', 'guide', 'chart', 'schedule',
            'cost', 'food', 'bill', 'dining', 'options', 'option', 'fare', 'meal', 'selection', 'board', 'fee', 'tax',
            'income', 'review', 'taxes'
        ];
    }

    public function get_custom_help_url(): string
    {
        return 'https://kingaddons.com/elementor/price-list-menu/';
    }

    protected function register_controls(): void
    {

        /** CONTENT SECTION: List Settings ===================== */
        $this->start_controls_section(
            'kng_price_list_content_section_list_settings',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('List Settings', 'king-addons'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'kng_price_list_image_disable_image_swithcer',
            [
                'label' => esc_html__('Disable Image', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
            ]
        );

        $this->end_controls_section();
        /** END CONTENT SECTION: List Settings ===================== */

        /** CONTENT SECTION: Items ===================== */
        $this->start_controls_section(
            'kng_price_list_content_section_items',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Items', 'king-addons'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'kng_price_list_image',
            [
                'label' => esc_html__('Image', 'king-addons'),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ]
            ]
        );

        $repeater->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name' => 'kng_price_list_image_size',
                'default' => 'full'
            ]
        );

        $repeater->add_control(
            'kng_price_list_title',
            [
                'label' => esc_html__('Title', 'king-addons'),
                'default' => esc_html__('Title', 'king-addons'),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'kng_price_list_description',
            [
                'label' => esc_html__('Description', 'king-addons'),
                'default' => esc_html__('Description', 'king-addons'),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'kng_price_list_price',
            [
                'label' => esc_html__('Price', 'king-addons'),
                'default' => esc_html__('Price', 'king-addons'),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
            ]
        );

        $this->add_control(
            'kng_price_list_content_items',
            [
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'kng_price_list_image' => Utils::get_placeholder_image_src(),
                        'kng_price_list_title' => esc_html__('English Breakfast', 'king-addons'),
                        'kng_price_list_description' => esc_html__('Lorem ipsum dolor', 'king-addons'),
                        'kng_price_list_price' => esc_html__('$18', 'king-addons'),
                    ],
                    [
                        'kng_price_list_image' => Utils::get_placeholder_image_src(),
                        'kng_price_list_title' => esc_html__('Italian Pasta', 'king-addons'),
                        'kng_price_list_description' => esc_html__('Lorem ipsum dolor', 'king-addons'),
                        'kng_price_list_price' => esc_html__('$25', 'king-addons'),
                    ],
                    [
                        'kng_price_list_image' => Utils::get_placeholder_image_src(),
                        'kng_price_list_title' => esc_html__('Sandwich', 'king-addons'),
                        'kng_price_list_description' => esc_html__('Lorem ipsum dolor', 'king-addons'),
                        'kng_price_list_price' => esc_html__('$13', 'king-addons'),
                    ],
                    [
                        'kng_price_list_image' => Utils::get_placeholder_image_src(),
                        'kng_price_list_title' => esc_html__('Spring Salad', 'king-addons'),
                        'kng_price_list_description' => esc_html__('Lorem ipsum dolor', 'king-addons'),
                        'kng_price_list_price' => esc_html__('$14', 'king-addons'),
                    ],
                ],
                'title_field' => '{{kng_price_list_title}}',
            ]
        );

        $this->end_controls_section();
        /** END CONTENT SECTION: Items ===================== */

        /** STYLE SECTION: Items ===================== */
        $this->start_controls_section(
            'kng_price_list_style_section_items',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Items', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => esc_html__('Title Typography', 'king-addons'),
                'name' => 'kng_price_list_typography_title',
                'fields_options' => [
                    'font_size' => [
                        'default' => [
                            'size' => 20,
                            'unit' => 'px'
                        ],
                    ],
                    'font_weight' => [
                        'default' => 600,
                    ],
                    'typography' => [
                        'default' => 'custom',
                    ]
                ],
                'selector' => '{{WRAPPER}} .king-addons-price-list-item-title',
            ]
        );

        $this->add_control(
            'kng_price_list_color_title',
            [
                'label' => esc_html__('Title Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#1E1E1E',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-price-list-item-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => esc_html__('Description Typography', 'king-addons'),
                'name' => 'kng_price_list_typography_description',
                'fields_options' => [
                    'font_size' => [
                        'default' => [
                            'size' => 17,
                            'unit' => 'px'
                        ],
                    ],
                    'typography' => [
                        'default' => 'custom',
                    ]
                ],
                'selector' => '{{WRAPPER}} .king-addons-price-list-item-description',
            ]
        );

        $this->add_control(
            'kng_price_list_color_description',
            [
                'label' => esc_html__('Description Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#1E1E1E',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-price-list-item-description' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => esc_html__('Price Typography', 'king-addons'),
                'name' => 'kng_price_list_typography_price',
                'fields_options' => [
                    'font_size' => [
                        'default' => [
                            'size' => 20,
                            'unit' => 'px'
                        ],
                    ],
                    'font_weight' => [
                        'default' => 600,
                    ],
                    'typography' => [
                        'default' => 'custom',
                    ]
                ],
                'selector' => '{{WRAPPER}} .king-addons-price-list-item-price',
            ]
        );

        $this->add_control(
            'kng_price_list_color_price',
            [
                'label' => esc_html__('Price Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#1E1E1E',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-price-list-item-price' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'kng_price_list_space_between_items',
            [
                'label' => esc_html__('Space between items (px)', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'default' => 30,
                'step' => 1,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-price-list-item' => 'padding-top: calc({{SIZE}}px / 2); padding-bottom: calc({{SIZE}}px / 2);',
                ],
            ]
        );

        $this->add_control(
            'kng_price_list_vertical_align',
            [
                'label' => esc_html__('Vertical Align', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'start' => esc_html__('Start', 'king-addons'),
                    'center' => esc_html__('Center', 'king-addons'),
                    'end' => esc_html__('End', 'king-addons'),
                ],
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-price-list-item' => 'align-items: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
        /** END STYLE SECTION: Items ===================== */

        /** STYLE SECTION: Lines ===================== */
        $this->start_controls_section(
            'kng_card_carousel_style_section_lines',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Lines', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'kng_price_list_line_heading_1',
            [
                'label' => esc_html__('Title --- Price', 'king-addons'),
                'type' => Controls_Manager::HEADING,
            ]
        );

        $this->add_control(
            'kng_price_list_line_type',
            [
                'label' => esc_html__('Line Type', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'none' => esc_html__('None', 'king-addons'),
                    'solid' => esc_html__('Solid', 'king-addons'),
                    'dotted' => esc_html__('Dotted', 'king-addons'),
                    'dashed' => esc_html__('Dashed', 'king-addons'),
                ],
                'default' => 'solid',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-price-list-item-line' => 'border-bottom-style: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'kng_price_list_line_color',
            [
                'label' => esc_html__('Line Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#1E1E1E33',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-price-list-item-line' => 'border-bottom-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'kng_price_list_line_width',
            [
                'label' => esc_html__('Line Width', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'default' => 1,
                'step' => 1,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-price-list-item-line' => 'border-bottom-width: {{SIZE}}px;',
                ],
            ]
        );

        $this->add_control(
            'kng_price_list_line_heading_2',
            [
                'label' => esc_html__('Under Title', 'king-addons'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'kng_price_list_line_type_under',
            [
                'label' => esc_html__('Line Type', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'none' => esc_html__('None', 'king-addons'),
                    'solid' => esc_html__('Solid', 'king-addons'),
                    'dotted' => esc_html__('Dotted', 'king-addons'),
                    'dashed' => esc_html__('Dashed', 'king-addons'),
                ],
                'default' => 'none',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-price-list-item-heading' => 'border-bottom-style: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'kng_price_list_line_color_under',
            [
                'label' => esc_html__('Line Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#1E1E1E33',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-price-list-item-heading' => 'border-bottom-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'kng_price_list_line_width_under',
            [
                'label' => esc_html__('Line Width', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'default' => 1,
                'step' => 1,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-price-list-item-heading' => 'border-bottom-width: {{SIZE}}px;',
                ],
            ]
        );

        $this->add_responsive_control(
            'kng_price_list_line_under_space',
            [
                'label' => esc_html__('Space (px)', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'default' => 20,
                'step' => 1,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-price-list-item-heading' => 'margin-bottom: calc({{SIZE}}px / 2); padding-bottom: calc({{SIZE}}px / 2);',
                ],
                'condition' => [
                    'kng_price_list_line_type_under' => ['solid', 'dotted', 'dashed']
                ]
            ]
        );

        $this->add_control(
            'kng_price_list_line_heading_3',
            [
                'label' => esc_html__('Between Items', 'king-addons'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'kng_price_list_line_type_items',
            [
                'label' => esc_html__('Line Type', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'none' => esc_html__('None', 'king-addons'),
                    'solid' => esc_html__('Solid', 'king-addons'),
                    'dotted' => esc_html__('Dotted', 'king-addons'),
                    'dashed' => esc_html__('Dashed', 'king-addons'),
                ],
                'default' => 'none',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-price-list-item' => 'border-bottom-style: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'kng_price_list_line_color_items',
            [
                'label' => esc_html__('Line Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#1E1E1E33',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-price-list-item' => 'border-bottom-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'kng_price_list_line_width_items',
            [
                'label' => esc_html__('Line Width', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'default' => 1,
                'step' => 1,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-price-list-item' => 'border-bottom-width: {{SIZE}}px;',
                ],
            ]
        );

        $this->end_controls_section();
        /** END STYLE SECTION: Lines ===================== */

        /** STYLE SECTION: Image ===================== */
        $this->start_controls_section(
            'kng_card_carousel_style_section_image',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Image', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'kng_price_list_image_width_type',
            [
                'label' => esc_html__('Width', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'auto' => esc_html__('Auto', 'king-addons'),
                    'custom-pct' => esc_html__('Custom (%)', 'king-addons'),
                    'custom-px' => esc_html__('Custom (px)', 'king-addons'),
                ],
                'default' => 'custom-px',
            ]
        );

        $this->add_responsive_control(
            'kng_price_list_image_height_type',
            [
                'label' => esc_html__('Height', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'auto' => esc_html__('Auto', 'king-addons'),
                    'custom-px' => esc_html__('Custom (px)', 'king-addons'),
                ],
                'default' => 'custom-px',
            ]
        );

        $this->add_responsive_control(
            'kng_price_list_image_width_px',
            [
                'label' => esc_html__('Width (px)', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'step' => 1,
                'default' => 60,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-price-list-item-image' => 'width: {{SIZE}}px;'
                ],
                'condition' => [
                    'kng_price_list_image_width_type' => 'custom-px'
                ]
            ]
        );

        $this->add_responsive_control(
            'kng_price_list_image_width_pct',
            [
                'label' => esc_html__('Width (%)', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 100,
                'step' => 1,
                'default' => 20,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-price-list-item-image' => 'width: {{SIZE}}%;'
                ],
                'condition' => [
                    'kng_price_list_image_width_type' => 'custom-pct'
                ]
            ]
        );

        $this->add_responsive_control(
            'kng_price_list_image_height_px',
            [
                'label' => esc_html__('Height (px)', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'step' => 1,
                'default' => 60,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-price-list-item-image' => 'height: {{SIZE}}px;'
                ],
                'condition' => [
                    'kng_price_list_image_height_type' => 'custom-px'
                ]
            ]
        );

        $this->add_responsive_control(
            'kng_price_list_image_fit',
            [
                'label' => esc_html__('Image Fit', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'cover' => esc_html__('Cover', 'king-addons'),
                    'contain' => esc_html__('Contain', 'king-addons'),
                ],
                'default' => 'cover',
                'separator' => 'before',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-price-list-item-image img' => 'object-fit: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'kng_price_list_image_fit_position',
            [
                'label' => esc_html__('Image Fit Position', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'top' => esc_html__('Top', 'king-addons'),
                    'bottom' => esc_html__('Bottom', 'king-addons'),
                    'left' => esc_html__('Left', 'king-addons'),
                    'right' => esc_html__('Right', 'king-addons'),
                    'center' => esc_html__('Center', 'king-addons'),
                ],
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-price-list-item-image img' => 'object-position: {{VALUE}};',
                ],
                'condition' => [
                    'kng_price_list_image_fit' => 'cover'
                ]
            ]
        );

        $this->add_control(
            'kng_price_list_image_bg_color',
            [
                'label' => esc_html__('Background Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-price-list-item-image' => 'background-color: {{VALUE}}',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'kng_price_list_image_move_to_top_on_mobile',
            [
                'label' => esc_html__('Move image to the top on mobile', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
            ]
        );

        $this->add_group_control(
            Group_Control_Css_Filter::get_type(),
            [
                'label' => esc_html__('CSS Filter', 'king-addons'),
                'name' => 'kng_price_list_image_css_filters',
                'selector' => '{{WRAPPER}} .king-addons-price-list-item-image',
            ]
        );

        $this->add_group_control(
            Group_Control_Css_Filter::get_type(),
            [
                'label' => esc_html__('CSS Filter for image on card hover', 'king-addons'),
                'name' => 'kng_price_list_image_css_filters_hover',
                'selector' => '{{WRAPPER}} .king-addons-price-list-item:hover .king-addons-price-list-item-image',
            ]
        );

        $this->add_responsive_control(
            'kng_price_list_image_transition',
            [
                'label' => esc_html__('Transition duration on hover (ms)', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'step' => 10,
                'default' => 300,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-price-list-item-image' => 'transition: all {{SIZE}}ms;'
                ],
            ]
        );

        $this->add_responsive_control(
            'kng_price_list_image_padding',
            [
                'label' => esc_html__('Padding', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'default' => [
                    'top' => 0,
                    'right' => 0,
                    'bottom' => 0,
                    'left' => 0,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-price-list-item-image' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->add_responsive_control(
            'kng_price_list_image_margin',
            [
                'label' => esc_html__('Margin', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'default' => [
                    'top' => 0,
                    'right' => 20,
                    'bottom' => 0,
                    'left' => 0,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-price-list-item-image' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'kng_price_list_image_box_shadow',
                'selector' => '{{WRAPPER}} .king-addons-price-list-item-image',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'kng_price_list_image_border',
                'selector' => '{{WRAPPER}} .king-addons-price-list-item-image',
                'fields_options' => [
                    'border' => [
                        'default' => 'none',
                    ],
                ],
            ]
        );

        $this->add_responsive_control(
            'kng_price_list_image_border_radius',
            [
                'label' => esc_html__('Border Radius', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'default' => [
                    'top' => 0,
                    'right' => 0,
                    'bottom' => 0,
                    'left' => 0,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-price-list-item-image' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->end_controls_section();
        /** END: Image */
    }

    protected function render(): void
    {
        $settings = $this->get_settings();

        // Define allowed tags and attributes
        $allowed_tags = wp_kses_allowed_html('post');
        $allowed_tags['img']['srcset'] = true;
        $allowed_tags['img']['sizes'] = true;
        $allowed_tags['img']['decoding'] = true;
        $allowed_tags['img']['loading'] = true;

        echo '<div class="king-addons-price-list-items' . (('yes' === $settings['kng_price_list_image_move_to_top_on_mobile']) ? ' king-addons-price-list-mobile-collapse' : '') . '">';
        foreach ($settings['kng_price_list_content_items'] as $item) {
            echo '<div class="king-addons-price-list-item elementor-repeater-item-' . esc_attr($item['_id']) . '">';

            /** Image */
            if ('yes' !== $settings['kng_price_list_image_disable_image_swithcer']) {
                echo '<div class="king-addons-price-list-item-image">';
                $image_html = Group_Control_Image_Size::get_attachment_image_html($item, 'kng_price_list_image_size', 'kng_price_list_image');
                echo wp_kses($image_html, $allowed_tags);
                echo '</div>';
            }
            /** END: Image */

            echo '<div class="king-addons-price-list-item-holder">';

            echo '<div class="king-addons-price-list-item-heading">';
            echo '<div class="king-addons-price-list-item-title">' . wp_kses_post($item['kng_price_list_title']) . '</div>';
            echo '<div class="king-addons-price-list-item-line"></div>';
            echo '<div class="king-addons-price-list-item-price">' . wp_kses_post($item['kng_price_list_price']) . '</div>';
            echo '</div>';

            echo '<div class="king-addons-price-list-item-description">' . wp_kses_post($item['kng_price_list_description']) . '</div>';

            echo '</div>'; // close holder

            echo '</div>';
        }
        echo '</div>';
    }
}