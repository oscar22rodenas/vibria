<?php /** @noinspection SpellCheckingInspection, DuplicatedCode, PhpUnused */

namespace King_Addons;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Widget_Base;

/** @noinspection SpellCheckingInspection */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class Reading_Progress_Bar extends Widget_Base
{
    public function get_name(): string
    {
        return 'king-addons-reading-progress-bar';
    }

    public function get_title(): string
    {
        return esc_html__('Reading Progress Bar', 'king-addons');
    }

    public function get_icon(): string
    {
        /** @noinspection SpellCheckingInspection */
        return 'king-addons-icon king-addons-reading-progress-bar';
    }

    public function get_style_depends(): array
    {
        return [KING_ADDONS_ASSETS_UNIQUE_KEY . '-reading-progress-bar-style'];
    }

    public function get_categories(): array
    {
        return ['king-addons'];
    }

    public function get_keywords(): array
    {
        return ['reading', 'progress', 'bar', 'reading bar', 'reading progress', 'reading progress bar', 'line', 'slider', 'scroll',
            'top', 'bottom', 'scrolling', 'scroller', 'scrollable', 'animation', 'effect', 'animated',
            'vertical', 'horizontal', 'mouse', 'hover', 'over', 'hover over', 'article', 'king', 'addons', 'mouseover',
            'slide', 'blog', 'page', 'read', 'kingaddons', 'king-addons'];
    }

    public function get_custom_help_url(): string
    {
        return 'https://kingaddons.com/elementor/reading-progress-bar/';
    }

    protected function register_controls(): void
    {
        $this->start_controls_section(
            'kng_reading_progress_bar_section_general',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('General', 'king-addons'),
            ]
        );

        // bar position - top, bottom
        $this->add_control(
            'kng_reading_progress_bar_position',
            [
                'label' => esc_html__('Position', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'top' => esc_html__('Top', 'king-addons'),
                    'bottom' => esc_html__('Bottom', 'king-addons'),
                ],
                'default' => 'top',
            ]
        );

        // height - desktop, mobile
        $this->add_responsive_control(
            'kng_reading_progress_bar_height',
            [
                'label' => esc_html__('Height (px)', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'step' => 1,
                'min' => 1,
                'default' => 5,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-reading-progress-bar-container, {{WRAPPER}} .king-addons-reading-progress-bar' => 'height: {{SIZE}}px;',
                ],
            ]
        );

        // background - simple, gradient
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'kng_reading_progress_bar_bg',
                'types' => ['classic', 'gradient', 'image'],
                'fields_options' => [
                    'color' => [
                        'default' => '#574ff7',
                    ],
                    'background' => [
                        'default' => 'classic',
                    ],
                ],
                'selector' => '{{WRAPPER}} .king-addons-reading-progress-bar'
            ]
        );

        // z-index - 999
        $this->add_responsive_control(
            'kng_reading_progress_bar_z_index',
            [
                'label' => esc_html__('Z-Index', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'step' => 1,
                'default' => 999,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-reading-progress-bar-container' => 'z-index: {{SIZE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render(): void
    {
        $settings = $this->get_settings();
        $this_ID = $this->get_id();

        echo '<div class="king-addons-reading-progress-bar-container king-addons-reading-progress-bar-container-position-' . esc_attr($settings['kng_reading_progress_bar_position']) . '"><div class="king-addons-reading-progress-bar" id="king-addons-reading-progress-bar-' . esc_attr($this_ID) . '"></div></div>';

        $js = 'document.addEventListener("scroll", function() {
        let winScroll = document.body.scrollTop || document.documentElement.scrollTop;
        let height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
        let scrolled = (winScroll / height) * 100;
        document.getElementById("king-addons-reading-progress-bar-' . esc_attr($this_ID) . '").style.width = scrolled + "%";
        });';

        wp_print_inline_script_tag($js);
    }
}