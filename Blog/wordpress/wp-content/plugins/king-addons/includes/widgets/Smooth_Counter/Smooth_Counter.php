<?php /** @noinspection SpellCheckingInspection, DuplicatedCode, PhpUnused */

namespace King_Addons;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Widget_Base;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class Smooth_Counter extends Widget_Base
{
    public function get_name(): string
    {
        return 'king-addons-smooth-counter';
    }

    public function get_title(): string
    {
        return esc_html__('Smooth Counter', 'king-addons');
    }

    public function get_icon(): string
    {
        return 'king-addons-icon king-addons-smooth-counter';
    }

    public function get_script_depends(): array
    {
        return [KING_ADDONS_ASSETS_UNIQUE_KEY . '-odometer-odometer'];
    }

    public function get_style_depends(): array
    {
        return [KING_ADDONS_ASSETS_UNIQUE_KEY . '-odometer-minimal', KING_ADDONS_ASSETS_UNIQUE_KEY . '-smooth-counter-style'];
    }

    public function get_categories(): array
    {
        return ['king-addons'];
    }

    public function get_keywords(): array
    {
        return ['counter', 'odometer', 'smooth counter', 'flip countdown', 'flipping countdown', 'flipping', 'flip',
            'countdown', 'clock', 'time', 'event',
            'timer', 'classic', 'circle', 'rotate', 'flip clock', 'flip', 'rounded', '24', '12', 'day', 'daily', 'days',
            'hour', 'hours', 'minute', 'minutes', 'second', 'seconds', 'counter', 'digits',
            'flip-countdown', 'king addons', 'king', 'addons', 'kingaddons', 'king-addons'];
    }

    public function get_custom_help_url(): string
    {
        return 'https://kingaddons.com/elementor/smooth-counter/';
    }

    protected function register_controls(): void
    {

        $this->start_controls_section(
            'king_addons_smooth_counter_section',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Smooth Counter', 'king-addons'),
                'tab' => Controls_Manager::TAB_CONTENT
            ]
        );

        $this->add_control(
            'king_addons_smooth_counter_start',
            [
                'label' => esc_html__('Start Number', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'step' => 0.01,
                'default' => 100,
            ]
        );

        $this->add_control(
            'king_addons_smooth_counter_finish',
            [
                'label' => esc_html__('Finish Number', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'step' => 0.01,
                'default' => 999,
            ]
        );

        $this->add_control(
            'king_addons_smooth_counter_anim_duration',
            [
                'label' => esc_html__('Animation Speed', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'min' => 50,
                'step' => 50,
                'default' => 600,
            ]
        );

        $this->add_control(
            'king_addons_smooth_counter_format',
            array(
                'label' => esc_html__('Format', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => array(
                    '(,ddd)' => esc_html__('12,345,678', 'king-addons'),
                    '(,ddd).dd' => esc_html__('12,345,678.09', 'king-addons'),
                    '(.ddd),dd' => esc_html__('12.345.678,09', 'king-addons'),
                    '( ddd),dd' => esc_html__('12 345 678,09', 'king-addons'),
                    'd' => esc_html__('12345678', 'king-addons'),
                ),
                'default' => '(,ddd).dd',
            )
        );

        $this->add_control(
            'king_addons_smooth_counter_before_text',
            [
                'label' => esc_html__('Before Text/Symbol', 'king-addons'),
                'type' => Controls_Manager::TEXT,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'king_addons_smooth_counter_after_text',
            [
                'label' => esc_html__('After Text/Symbol', 'king-addons'),
                'type' => Controls_Manager::TEXT,
            ]
        );

        $this->add_control(
            'king_addons_smooth_counter_title',
            [
                'label' => esc_html__('Title', 'king-addons'),
                'type' => Controls_Manager::TEXT,
                'separator' => 'before',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'king_addons_smooth_counter_style_section_number',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Number', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'king_addons_smooth_counter_typography',
                'selector' => '{{WRAPPER}} .king-addons-smooth-counter-number',
            ]
        );

        $this->add_control(
            'king_addons_smooth_counter_typography_color',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#000000',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-smooth-counter-number' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'king_addons_smooth_counter_align_number',
            [
                'label' => esc_html__('Align', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'start' => esc_html__('Start', 'king-addons'),
                    'center' => esc_html__('Center', 'king-addons'),
                    'end' => esc_html__('End', 'king-addons'),
                ],
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-smooth-counter-inner' => 'justify-content: {{VALUE}};'
                ],
            ]
        );

        $this->add_responsive_control(
            'king_addons_smooth_counter_margin_number',
            [
                'label' => esc_html__('Margin', 'king-addons'),
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
                    '{{WRAPPER}} .king-addons-smooth-counter-number' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'king_addons_smooth_counter_style_section_before_text',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Before Text/Symbol', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'king_addons_smooth_counter_typography_before_text',
                'selector' => '{{WRAPPER}} .king-addons-smooth-counter-before-text',
            ]
        );

        $this->add_control(
            'king_addons_smooth_counter_typography_color_before_text',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#000000',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-smooth-counter-before-text' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'king_addons_smooth_counter_margin_before_text',
            [
                'label' => esc_html__('Margin', 'king-addons'),
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
                    '{{WRAPPER}} .king-addons-smooth-counter-before-text' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'king_addons_smooth_counter_style_section_after_text',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('After Text/Symbol', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'king_addons_smooth_counter_typography_after_text',
                'selector' => '{{WRAPPER}} .king-addons-smooth-counter-after-text',
            ]
        );

        $this->add_control(
            'king_addons_smooth_counter_typography_color_after_text',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#000000',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-smooth-counter-after-text' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'king_addons_smooth_counter_margin_after_text',
            [
                'label' => esc_html__('Margin', 'king-addons'),
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
                    '{{WRAPPER}} .king-addons-smooth-counter-after-text' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'king_addons_smooth_counter_style_section_title',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Title', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'king_addons_smooth_counter_typography_title',
                'selector' => '{{WRAPPER}} .king-addons-smooth-counter-title',
            ]
        );

        $this->add_control(
            'king_addons_smooth_counter_typography_color_title',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#000000',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-smooth-counter-title' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'king_addons_smooth_counter_align_title',
            [
                'label' => esc_html__('Align', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'start' => esc_html__('Start', 'king-addons'),
                    'center' => esc_html__('Center', 'king-addons'),
                    'end' => esc_html__('End', 'king-addons'),
                ],
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-smooth-counter-title' => 'text-align: {{VALUE}};'
                ],
            ]
        );

        $this->add_responsive_control(
            'king_addons_smooth_counter_margin_title',
            [
                'label' => esc_html__('Margin', 'king-addons'),
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
                    '{{WRAPPER}} .king-addons-smooth-counter-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'king_addons_smooth_counter_style_section',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Whole Counter', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'king_addons_smooth_counter_background',
                'types' => [ 'classic', 'gradient', 'video' ],
                'selector' => '{{WRAPPER}} .king-addons-smooth-counter',
            ]
        );


        $this->add_responsive_control(
            'king_addons_smooth_counter_padding',
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
                    '{{WRAPPER}} .king-addons-smooth-counter' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->add_responsive_control(
            'king_addons_smooth_counter_margin',
            [
                'label' => esc_html__('Margin', 'king-addons'),
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
                    '{{WRAPPER}} .king-addons-smooth-counter' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'king_addons_smooth_counter_box_shadow',
                'selector' => '{{WRAPPER}} .king-addons-smooth-counter',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'king_addons_smooth_counter_border',
                'selector' => '{{WRAPPER}} .king-addons-smooth-counter',
                'fields_options' => [
                    'border' => [
                        'default' => 'none',
                    ],
                ],
            ]
        );

        $this->add_responsive_control(
            'king_addons_smooth_counter_border_radius',
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
                    '{{WRAPPER}} .king-addons-smooth-counter' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->end_controls_section();
    }

    protected function render(): void
    {
        $settings = $this->get_settings();
        $id = $this->get_id();

        echo '<div class="king-addons-smooth-counter">';

        echo '<div class="king-addons-smooth-counter-inner">';
        echo '<div class="king-addons-smooth-counter-before-text">';
        echo wp_kses_post($settings['king_addons_smooth_counter_before_text']);
        echo '</div>';
        echo '<div class="king-addons-smooth-counter-number king-addons-smooth-counter-' . esc_attr($id) . '">' . esc_html($settings['king_addons_smooth_counter_start']) . '</div>';
        echo '<div class="king-addons-smooth-counter-after-text">';
        echo wp_kses_post($settings['king_addons_smooth_counter_after_text']);
        echo '</div>';
        echo '</div>';

        echo '<div class="king-addons-smooth-counter-title">';
        echo wp_kses_post($settings['king_addons_smooth_counter_title']);
        echo '</div>';

        echo '</div>';

        $inline_js = "(function ($) {
                function doSmoothCounter() {
                    let od = new Odometer({
                        el: document.querySelector('.king-addons-smooth-counter-" . esc_js($id) . "'),
                        value: " . esc_js($settings['king_addons_smooth_counter_start']) . ",
                        format: '" . esc_js($settings['king_addons_smooth_counter_format']) . "',
                        duration: " . esc_js($settings['king_addons_smooth_counter_anim_duration']) . ",
                        theme: 'minimal',
                    });
                    od.update(" . esc_js($settings['king_addons_smooth_counter_finish']) . ")
                }

                function onIntersection(entries, observer) {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            doSmoothCounter();
                            observer.unobserve(entry.target); // Stop observing after the function is triggered
                        }
                    });
                }

                if (document.readyState === 'complete') {
                    let target = document.querySelector('.king-addons-smooth-counter-" . esc_js($id) . "');
                    if (target) {
                        let observer = new IntersectionObserver(onIntersection);
                        observer.observe(target);
                    }
                } else {
                    document.addEventListener('DOMContentLoaded', function() {
                    let target = document.querySelector('.king-addons-smooth-counter-" . esc_js($id) . "');
                    if (target) {
                        let observer = new IntersectionObserver(onIntersection);
                        observer.observe(target);
                    }
                });
                }
            })(jQuery);";

        wp_print_inline_script_tag($inline_js);

    }
}