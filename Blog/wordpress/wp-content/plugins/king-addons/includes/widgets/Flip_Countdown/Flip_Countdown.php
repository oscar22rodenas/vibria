<?php /** @noinspection SpellCheckingInspection, DuplicatedCode, PhpUnused */

namespace King_Addons;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Widget_Base;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class Flip_Countdown extends Widget_Base
{
    public function get_name(): string
    {
        return 'king-addons-flip-countdown';
    }

    public function get_title(): string
    {
        return esc_html__('Flip Countdown & Timer', 'king-addons');
    }

    public function get_icon(): string
    {
        return 'king-addons-icon king-addons-flip-countdown';
    }

    public function get_script_depends(): array
    {
        return [KING_ADDONS_ASSETS_UNIQUE_KEY . '-flipclock-flipclock'];
    }

    public function get_style_depends(): array
    {
        return [KING_ADDONS_ASSETS_UNIQUE_KEY . '-flipclock-flipclock'];
    }

    public function get_categories(): array
    {
        return ['king-addons'];
    }

    public function get_keywords(): array
    {
        return ['flip countdown', 'flipping countdown', 'flipping', 'flip', 'countdown', 'clock', 'time', 'event',
            'timer', 'classic', 'circle', 'rotate', 'flip clock', 'flip', 'rounded', '24', '12', 'day', 'daily', 'days',
            'hour', 'hours', 'minute', 'minutes', 'second', 'seconds', 'counter', 'digits',
            'flip-countdown', 'king addons', 'king', 'addons', 'kingaddons', 'king-addons'];
    }

    public function get_custom_help_url(): string
    {
        return 'https://kingaddons.com/elementor/flip-countdown-timer/';
    }

    protected function register_controls(): void
    {
        $this->start_controls_section(
            'king_addons_flip_countdown_section',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Flip Countdown', 'king-addons'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'king_addons_flip_countdown_date_time_notice',
            [
                'type' => Controls_Manager::NOTICE,
                'notice_type' => 'warning',
                'dismissible' => false,
                'heading' => esc_html__('Notice', 'king-addons'),
                'content' => esc_html__('If the current time looks wrong please check Timezone settings in WordPress Dashboard -> Settings -> General -> Timezone.', 'king-addons'),
                'condition' => array(
                    'king_addons_flip_countdown_type' => 'fixed',
                ),
            ]
        );

        $this->add_control(
            'king_addons_flip_countdown_type',
            array(
                'label' => esc_html__('Type', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => array(
                    'fixed' => esc_html__('Fixed Timer', 'king-addons'),
                    'evergreen' => esc_html__('Evergreen Timer', 'king-addons'),
                ),
                'render_type' => 'template',
                'default' => 'evergreen',
            )
        );

        $this->add_control(
            'king_addons_flip_countdown_date_time',
            array(
                'label' => esc_html__('Due Date', 'king-addons'),
                'type' => Controls_Manager::DATE_TIME,
                'condition' => array(
                    'king_addons_flip_countdown_type' => 'fixed',
                ),
            )
        );

        $this->add_control(
            'king_addons_flip_countdown_evergreen_days',
            array(
                'label' => esc_html__('Days', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'dynamic' => array('active' => true),
                'render_type' => 'template',
                'default' => 0,
                'condition' => array(
                    'king_addons_flip_countdown_type' => 'evergreen',
                ),
            )
        );

        $this->add_control(
            'king_addons_flip_countdown_evergreen_hours',
            array(
                'label' => esc_html__('Hours', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 23,
                'dynamic' => array('active' => true),
                'render_type' => 'template',
                'default' => 1,
                'condition' => array(
                    'king_addons_flip_countdown_type' => 'evergreen',
                ),
            )
        );

        $this->add_control(
            'king_addons_flip_countdown_evergreen_minutes',
            array(
                'label' => esc_html__('Minutes', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 59,
                'dynamic' => array('active' => true),
                'render_type' => 'template',
                'default' => 1,
                'condition' => array(
                    'king_addons_flip_countdown_type' => 'evergreen',
                ),
            )
        );

        $this->add_control(
            'king_addons_flip_countdown_evergreen_seconds',
            array(
                'label' => esc_html__('Seconds', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 59,
                'dynamic' => array('active' => true),
                'render_type' => 'template',
                'default' => 0,
                'condition' => array(
                    'king_addons_flip_countdown_type' => 'evergreen',
                ),
            )
        );

        $this->add_control(
            'king_addons_flip_clock_face',
            array(
                'label' => esc_html__('Clock Face', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => array(
                    'DailyCounter' => esc_html__('Daily Counter', 'king-addons'),
                    'HourlyCounter' => esc_html__('Hourly Counter', 'king-addons'),
                    'MinuteCounter' => esc_html__('Minutes Counter', 'king-addons'),
                    'Counter' => esc_html__('Seconds Counter', 'king-addons'),
                ),
                'default' => 'HourlyCounter',
            )
        );

        $this->add_control(
            'king_addons_flip_countdown_show_seconds',
            array(
                'label' => esc_html__('Show Seconds', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => true,
                'return_value' => true,
                'condition' => array(
                    'king_addons_flip_clock_face' => 'DailyCounter',
                ),
            )
        );

        $this->add_control(
            'king_addons_flip_language',
            array(
                'label' => esc_html__('Language', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => array(
                    'ar' => esc_html__('Arabic', 'king-addons'),
                    'zh' => esc_html__('Chinese', 'king-addons'),
                    'da' => esc_html__('Danish', 'king-addons'),
                    'nl' => esc_html__('Dutch', 'king-addons'),
                    'en' => esc_html__('English', 'king-addons'),
                    'fi' => esc_html__('Finnish', 'king-addons'),
                    'fr' => esc_html__('French', 'king-addons'),
                    'de' => esc_html__('German', 'king-addons'),
                    'it' => esc_html__('Italian', 'king-addons'),
                    'lv' => esc_html__('Latvian', 'king-addons'),
                    'no' => esc_html__('Norwegian', 'king-addons'),
                    'pt' => esc_html__('Portuguese', 'king-addons'),
                    'ru' => esc_html__('Russian', 'king-addons'),
                    'es' => esc_html__('Spanish', 'king-addons'),
                    'sv' => esc_html__('Swedish', 'king-addons'),
                ),
                'default' => 'en',
            )
        );

        $this->end_controls_section();

        /** TAB STYLE */

        $this->start_controls_section(
            'king_addons_flip_countdown_f_cardsection',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Flip Card', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'king_addons_flip_countdown_bg_color',
            [
                'label' => esc_html__('Background Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#333333',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-flip-clock-wrapper ul li a div div.inn' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'king_addons_flip_countdown_shadow',
                'selector' => '{{WRAPPER}} .king-addons-flip-clock-wrapper .king-addons-flip',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'king_addons_flip_countdown_titles_section',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Titles', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'king_addons_flip_countdown_titles_typography',
                'selector' => '{{WRAPPER}} .king-addons-flip-clock-divider .king-addons-flip-clock-label',
            ]
        );

        $this->add_control(
            'king_addons_flip_countdown_titles_typography_color',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#000000',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-flip-clock-divider .king-addons-flip-clock-label' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'king_addons_flip_countdown_numbers_section',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Numbers', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'king_addons_flip_countdown_numbers_typography',
                'selector' => '{{WRAPPER}} .king-addons-flip-clock-wrapper ul li a div div.inn',
            ]
        );

        $this->add_control(
            'king_addons_flip_countdown_numbers_typography_color',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#CCCCCC',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-flip-clock-wrapper ul li a div div.inn' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'king_addons_flip_countdown_dots_section',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Dots', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'king_addons_flip_countdown_dots_bg_color',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#333333',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-flip-clock-dot' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'king_addons_flip_countdown_dots_shadow',
                'selector' => '{{WRAPPER}} .king-addons-flip-clock-dot',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'king_addons_flip_countdown_separator_section',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Separator', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'king_addons_flip_countdown_separator_bg_color',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => 'rgba(0, 0, 0, 0.4)',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-flip-clock-wrapper ul li a div.up:after' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_section();

    }

    protected function render(): void
    {
        $settings = $this->get_settings();
        $id = $this->get_id();

        if ($settings['king_addons_flip_countdown_type'] == 'evergreen') {
            $days = $settings['king_addons_flip_countdown_evergreen_days'];
            $hours = $settings['king_addons_flip_countdown_evergreen_hours'];
            $minutes = $settings['king_addons_flip_countdown_evergreen_minutes'];
            $seconds = $settings['king_addons_flip_countdown_evergreen_seconds'];
            $time = $seconds + $minutes * 60 + $hours * 60 * 60 + $days * 24 * 60 * 60;
        } else {
            $time = strtotime($settings['king_addons_flip_countdown_date_time']) - current_time('timestamp');
        }

        echo '<div class="king-addons-countdown-' . esc_attr($id) . '"></div>';

        $inline_js = "(function ($) {
    function doFlipCountdown() {
               
    // Grab the current date
	let currentDate = new Date();
	// Set some date in the future. In this case, it's always Jan 1
	let futureDate  = new Date(currentDate.getFullYear() + 1, 0, 1);
	// Calculate the difference in seconds between the future and current date
	let diff = futureDate.getTime() / 1000 - currentDate.getTime() / 1000;
	// Instantiate a coutdown FlipClock
	let clock = $('.king-addons-countdown-" . esc_js($id) . "').FlipClock(" . esc_js($time) . ", {
		clockFace: '" . esc_js($settings['king_addons_flip_clock_face']) . "',
		language: '" . esc_js($settings['king_addons_flip_language']) . "',
		autoStart: true,
		countdown: true,
		showSeconds: " . esc_js(($settings['king_addons_flip_countdown_show_seconds']) ? 'true' : 'false') . "
	});
    }

    if (document.readyState === 'complete') {
        doFlipCountdown();
    } else {
        document.addEventListener('DOMContentLoaded', doFlipCountdown);
    }
    })(jQuery);";

        wp_print_inline_script_tag($inline_js);
    }
}