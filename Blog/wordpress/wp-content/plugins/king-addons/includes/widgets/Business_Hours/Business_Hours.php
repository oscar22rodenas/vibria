<?php /** @noinspection SpellCheckingInspection, DuplicatedCode, PhpUnused */

namespace King_Addons;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;
use Elementor\Widget_Base;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class Business_Hours extends Widget_Base
{
    public function get_name(): string
    {
        return 'king-addons-business-hours';
    }

    public function get_title(): string
    {
        return esc_html__('Business Hours', 'king-addons');
    }

    public function get_icon(): string
    {
        return 'king-addons-icon king-addons-business-hours';
    }

    public function get_style_depends(): array
    {
        return [KING_ADDONS_ASSETS_UNIQUE_KEY . '-business-hours-style'];
    }

    public function get_categories(): array
    {
        return ['king-addons'];
    }

    public function get_keywords(): array
    {
        return ['rows', 'kingaddons', 'king-addons', 'king', 'addons', 'business hours', 'opening', 'working', 'office',
            'store', 'hours of operation', 'schedule', 'time', 'availability', 'service', 'schedule', 'operating',
            'opening', 'working times', 'office times', 'store times', 'operational', 'timings', 'shop', 'list',
            'business', 'hours', 'times', 'time', 'operation', 'calendar', 'week', 'day', 'days', 'weekend', 'weekends',
            'holidays', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday', 'January',
            'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December',
        ];
    }

    public function get_custom_help_url(): string
    {
        return 'https://kingaddons.com/elementor/business-hours/';
    }

    protected function register_controls(): void
    {
        /** CONTENT SECTION: Items ===================== */
        $this->start_controls_section(
            'kng_business_hours_content_section_items',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Items', 'king-addons'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'kng_business_hours_day',
            [
                'label' => esc_html__('Day', 'king-addons'),
                'default' => esc_html__('Day', 'king-addons'),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'kng_business_hours_hours',
            [
                'label' => esc_html__('Hours', 'king-addons'),
                'default' => esc_html__('Hours', 'king-addons'),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'kng_business_hours_custom_styles_switcher',
            [
                'label' => esc_html__('Custom styles', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
            ]
        );

        $repeater->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => esc_html__('Day Typography', 'king-addons'),
                'name' => 'kng_business_hours_custom_day_typography',
                'selector' => '{{WRAPPER}} {{CURRENT_ITEM}} .king-addons-business-hours-item-day',
                'condition' => [
                    'kng_business_hours_custom_styles_switcher' => 'yes'
                ]
            ]
        );

        $repeater->add_control(
            'kng_business_hours_custom_day_color',
            [
                'label' => esc_html__('Day Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#1E1E1E59',
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .king-addons-business-hours-item-day' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'kng_business_hours_custom_styles_switcher' => 'yes'
                ]
            ]
        );

        $repeater->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => esc_html__('Hours Typography', 'king-addons'),
                'name' => 'kng_business_hours_custom_hours_typography',
                'selector' => '{{WRAPPER}} {{CURRENT_ITEM}} .king-addons-business-hours-item-hours',
                'condition' => [
                    'kng_business_hours_custom_styles_switcher' => 'yes'
                ]
            ]
        );

        $repeater->add_control(
            'kng_business_hours_custom_hours_color',
            [
                'label' => esc_html__('Hours Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#1E1E1E59',
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .king-addons-business-hours-item-hours' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'kng_business_hours_custom_styles_switcher' => 'yes'
                ]
            ]
        );

        $repeater->add_control(
            'kng_business_hours_custom_line_color',
            [
                'label' => esc_html__('Line Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#1E1E1E59',
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .king-addons-business-hours-item-line' => 'border-color: {{VALUE}};',
                ],
                'condition' => [
                    'kng_business_hours_custom_styles_switcher' => 'yes'
                ]
            ]
        );

        $this->add_control(
            'kng_business_hours_content_items',
            [
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'kng_business_hours_day' => esc_html__('Monday', 'king-addons'),
                        'kng_business_hours_hours' => esc_html__('9:00 AM - 5:00 PM', 'king-addons'),
                    ],
                    [
                        'kng_business_hours_day' => esc_html__('Tuesday', 'king-addons'),
                        'kng_business_hours_hours' => esc_html__('9:00 AM - 5:00 PM', 'king-addons'),
                    ],
                    [
                        'kng_business_hours_day' => esc_html__('Wednesday', 'king-addons'),
                        'kng_business_hours_hours' => esc_html__('9:00 AM - 5:00 PM', 'king-addons'),
                    ],
                    [
                        'kng_business_hours_day' => esc_html__('Thursday', 'king-addons'),
                        'kng_business_hours_hours' => esc_html__('9:00 AM - 5:00 PM', 'king-addons'),
                    ],
                    [
                        'kng_business_hours_day' => esc_html__('Friday', 'king-addons'),
                        'kng_business_hours_hours' => esc_html__('9:00 AM - 5:00 PM', 'king-addons'),
                    ],
                    [
                        'kng_business_hours_custom_styles_switcher' => 'yes',
                        'kng_business_hours_day' => esc_html__('Saturday', 'king-addons'),
                        'kng_business_hours_hours' => esc_html__('Closed', 'king-addons'),
                    ],
                    [
                        'kng_business_hours_custom_styles_switcher' => 'yes',
                        'kng_business_hours_day' => esc_html__('Sunday', 'king-addons'),
                        'kng_business_hours_hours' => esc_html__('Closed', 'king-addons'),
                    ],
                ],
                'title_field' => '{{kng_business_hours_day}}',
            ]
        );

        $this->end_controls_section();
        /** END CONTENT SECTION: Items ===================== */

        /** STYLE SECTION: Items ===================== */
        $this->start_controls_section(
            'kng_business_hours_style_section_items',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Items', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => esc_html__('Day Typography', 'king-addons'),
                'name' => 'kng_business_hours_typography_day',
                'fields_options' => [
                    'font_size' => [
                        'default' => [
                            'size' => 20,
                            'unit' => 'px'
                        ],
                    ],
                    'typography' => [
                        'default' => 'custom',
                    ]
                ],
                'selector' => '{{WRAPPER}} .king-addons-business-hours-item-day',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => esc_html__('Hours Typography', 'king-addons'),
                'name' => 'kng_business_hours_typography_hours',
                'fields_options' => [
                    'font_size' => [
                        'default' => [
                            'size' => 20,
                            'unit' => 'px'
                        ],
                    ],
                    'typography' => [
                        'default' => 'custom',
                    ]
                ],
                'selector' => '{{WRAPPER}} .king-addons-business-hours-item-hours',
            ]
        );

        $this->add_control(
            'kng_business_hours_color_day',
            [
                'label' => esc_html__('Day Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#1E1E1E',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-business-hours-item-day' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'kng_business_hours_color_hours',
            [
                'label' => esc_html__('Hours Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#1E1E1E',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-business-hours-item-hours' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'kng_business_hours_space_between_items',
            [
                'label' => esc_html__('Space between items (px)', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'default' => 23,
                'step' => 1,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-business-hours-item:not(:last-child)' => 'margin-bottom: {{SIZE}}px;',
                ],
            ]
        );

        $this->add_control(
            'kng_business_hours_line_color',
            [
                'label' => esc_html__('Line Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#1E1E1E59',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-business-hours-item-line' => 'border-bottom-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'kng_business_hours_line_type',
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
                    '{{WRAPPER}} .king-addons-business-hours-item-line' => 'border-bottom-style: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'kng_business_hours_line_width',
            [
                'label' => esc_html__('Line Width', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'default' => 1,
                'step' => 1,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-business-hours-item-line' => 'border-bottom-width: {{SIZE}}px;',
                ],
            ]
        );

        $this->add_control(
            'kng_business_hours_column_position',
            [
                'label' => esc_html__('Column Position', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'row' => esc_html__('Default', 'king-addons'),
                    'row-reverse' => esc_html__('Reverse', 'king-addons'),
                ],
                'default' => 'row',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-business-hours-item' => 'flex-direction: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
        /** END STYLE SECTION: Items ===================== */
    }

    protected function render(): void
    {
        $settings = $this->get_settings();
        echo '<div class="king-addons-business-hours-items">';
        foreach ($settings['kng_business_hours_content_items'] as $item) {
            echo '<div class="king-addons-business-hours-item elementor-repeater-item-' . esc_attr($item['_id']) . '">';

            echo '<div class="king-addons-business-hours-item-day">' . wp_kses_post($item['kng_business_hours_day']) . '</div>';
            echo '<div class="king-addons-business-hours-item-line"></div>';
            echo '<div class="king-addons-business-hours-item-hours">' . wp_kses_post($item['kng_business_hours_hours']) . '</div>';

            echo '</div>';
        }
        echo '</div>';
    }
}