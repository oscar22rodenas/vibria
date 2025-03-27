<?php
/** @noinspection SpellCheckingInspection, DuplicatedCode, PhpUnused */

namespace King_Addons;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;
use Elementor\Repeater;
use Elementor\Utils;
use Elementor\Widget_Base;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class Testimonial_Carousel extends Widget_Base
{
    public function get_name(): string
    {
        return 'king-addons-testimonial-carousel';
    }

    public function get_title(): string
    {
        return esc_html__('Testimonial & Review Carousel', 'king-addons');
    }

    public function get_icon(): string
    {
        return 'king-addons-icon king-addons-testimonial-carousel';
    }

    public function get_script_depends(): array
    {
        return [KING_ADDONS_ASSETS_UNIQUE_KEY . '-swiper-swiper'];
    }

    public function get_style_depends(): array
    {
        return [KING_ADDONS_ASSETS_UNIQUE_KEY . '-swiper-swiper', KING_ADDONS_ASSETS_UNIQUE_KEY . '-testimonial-carousel-style'];
    }

    public function get_categories(): array
    {
        return ['king-addons'];
    }

    public function get_keywords(): array
    {
        return ['testimonial', 'slider', 'carousel', 'testimonial carousel', 'testimonials',
            'review', 'reviews', 'rating', 'ratings', 'stars', 'rate', 'star',
            'king addons', 'king', 'addons', 'kingaddons', 'king-addons'];
    }

    public function get_custom_help_url(): string
    {
        return 'https://kingaddons.com/elementor/testimonial-review-carousel/';
    }

    protected function register_controls(): void
    {
        $this->start_controls_section(
            'king_addons_testimonial_carousel_layout_section',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Layout', 'king-addons'),
                'tab' => Controls_Manager::TAB_CONTENT
            ]
        );

        // selector - testimonial layouts
        $this->add_control(
            'king_addons_testimonial_carousel_layout',
            array(
                'label' => '<b>' . esc_html__('Layout', 'king-addons') . '</b>',
                'type' => Controls_Manager::SELECT,
                'options' => array(
                    'layout-1' => esc_html__('Default - Image, Desc, Rating, Bio', 'king-addons'),
                    'layout-8' => esc_html__('Default Centered - Image, Desc, Rating, Bio', 'king-addons'),
                    'layout-2' => esc_html__('Classic - Desc, Bio, Rating, Image', 'king-addons'),
                    'layout-3' => esc_html__('Desc, Image, Bio, Rating', 'king-addons'),
                    'layout-4' => esc_html__('Image | Content', 'king-addons'),
                    'layout-5' => esc_html__('Content | Image', 'king-addons'),
                    'layout-6' => esc_html__('Desc | Bottom Bio', 'king-addons'),
                    'layout-7' => esc_html__('Top Bio | Desc', 'king-addons'),
                ),
                'render_type' => 'template',
                'default' => 'layout-8',
            )
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'king_addons_testimonial_carousel_content_section',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Content', 'king-addons'),
                'tab' => Controls_Manager::TAB_CONTENT
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'king_addons_testimonial_carousel_image',
            [
                'label' => esc_html__('Image', 'king-addons'),
                'type' => Controls_Manager::MEDIA,
                'dynamic' => array('active' => true),
                'default' => array(
                    'url' => Utils::get_placeholder_image_src(),
                ),
                'show_external' => true,
            ]
        );

        $repeater->add_control(
            'king_addons_testimonial_carousel_title',
            [
                'label' => '<b>' . esc_html__('Title', 'king-addons') . '</b>',
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
                'default' => esc_html__('Title', 'king-addons'),
            ]
        );

        $repeater->add_control(
            'king_addons_testimonial_carousel_subtitle',
            [
                'label' => '<b>' . esc_html__('Subtitle', 'king-addons') . '</b>',
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
                'default' => esc_html__('Subtitle', 'king-addons'),
                'separator' => 'before'
            ]
        );

        $repeater->add_control(
            'king_addons_testimonial_carousel_rating_value',
            [
                'label' => '<b>' . esc_html__('Rating', 'king-addons') . '</b>',
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 10,
                'default' => 5,
                'step' => 0.25,
                'dynamic' => [
                    'active' => true,
                ],
            ]
        );

        $repeater->add_control(
            'king_addons_testimonial_carousel_description_title',
            [
                'label' => '<b>' . esc_html__('Description', 'king-addons') . '</b>',
                'type' => Controls_Manager::RAW_HTML,
                'separator' => 'before',
            ]
        );

        $repeater->add_control(
            'king_addons_testimonial_carousel_description',
            [
                'label' => '<b>' . esc_html__('Description', 'king-addons') . '</b>',
                'type' => Controls_Manager::WYSIWYG,
                'show_label' => false,
                'default' => esc_html__('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'king-addons'),
                'placeholder' => esc_html__('Type the description here', 'king-addons'),
            ]
        );

        $default_cards = [
            'king_addons_testimonial_carousel_image' => Utils::get_placeholder_image_src(),
            'king_addons_testimonial_carousel_title' => esc_html__('Title', 'king-addons'),
            'king_addons_testimonial_carousel_subtitle' => esc_html__('Subtitle', 'king-addons'),
            'king_addons_testimonial_carousel_rating_value' => 5,
            'king_addons_testimonial_carousel_description' => esc_html__('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'king-addons'),
        ];

        $this->add_control(
            'king_addons_testimonial_carousel_content_items',
            [
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => array_fill(0, 5, $default_cards),
                'title_field' => '{{king_addons_testimonial_carousel_title}}',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'king_addons_testimonial_carousel_image_section',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Image', 'king-addons'),
                'tab' => Controls_Manager::TAB_CONTENT
            ]
        );

        $this->add_control(
            'king_addons_testimonial_carousel_image_switcher',
            array(
                'label' => esc_html__('Show Image', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'return_value' => 'yes',
            )
        );

        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name' => 'king_addons_testimonial_carousel_image_size',
                'default' => 'medium',
                'condition' => array(
                    'king_addons_testimonial_carousel_image_switcher' => 'yes',
                ),
            ]
        );

        $this->end_controls_section();

        // RATING =================================================
        $this->start_controls_section(
            'king_addons_testimonial_carousel_section_rating',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Rating', 'king-addons'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'king_addons_testimonial_carousel_rating_switcher',
            array(
                'label' => esc_html__('Show Rating', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'return_value' => 'yes',
            )
        );

        $this->add_control(
            'king_addons_testimonial_carousel_rating_scale',
            [
                'label' => esc_html__('Rating Scale', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 10,
                    ],
                ],
                'default' => [
                    'size' => 5,
                ],
                'condition' => array(
                    'king_addons_testimonial_carousel_rating_switcher' => 'yes',
                ),
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'king_addons_testimonial_carousel_rating_icon',
            [
                'label' => esc_html__('Icon', 'king-addons'),
                'type' => Controls_Manager::ICONS,
                'fa4compatibility' => 'icon',
                'skin' => 'inline',
                'label_block' => false,
                'skin_settings' => [
                    'inline' => [
                        'icon' => [
                            'icon' => 'eicon-star',
                        ],
                    ],
                ],
                'default' => [
                    'value' => 'eicon-star',
                    'library' => 'eicons',
                ],
                'separator' => 'before',
                'exclude_inline_options' => ['none'],
                'condition' => array(
                    'king_addons_testimonial_carousel_rating_switcher' => 'yes',
                ),
            ]
        );

        $this->end_controls_section();
        // END: RATING ============================================

        /** SECTION: Carousel Settings */
        $this->start_controls_section(
            'king_addons_testimonial_carousel_sw_content_section_carousel_settings',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Carousel Settings', 'king-addons'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'king_addons_testimonial_carousel_sw_loop_switcher',
            [
                'label' => esc_html__('Loop Cards', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes'
            ]
        );

        $this->add_control(
            'king_addons_testimonial_carousel_sw_rewind_switcher',
            [
                'label' => esc_html__('Rewind Cards', 'king-addons'),
                'description' => esc_html__('When enabled, clicking the next navigation button when on last slide will slide back to the first slide. Clicking the prev navigation button when on first slide will slide forward to the last slide. Should not be used together with loop mode.', 'king-addons'),
                'type' => Controls_Manager::SWITCHER
            ]
        );

        $this->add_control(
            'king_addons_testimonial_carousel_sw_autoplay_switcher',
            [
                'label' => esc_html__('Autoplay Carousel Cards', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'king_addons_testimonial_carousel_sw_autoplay_delay',
            [
                'label' => esc_html__('Delay between transitions (ms)', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'step' => 10,
                'default' => 3000,
                'condition' => [
                    'king_addons_testimonial_carousel_sw_autoplay_switcher!' => '',
                    'king_addons_testimonial_carousel_sw_autoplay_like_ticker_switcher!' => 'yes',
                ]
            ]
        );

        $this->add_control(
            'king_addons_testimonial_carousel_sw_autoplay_disableoninteraction_switcher',
            [
                'label' => esc_html__('Disable on interaction', 'king-addons'),
                'description' => esc_html__('Autoplay will be disabled after user interactions (swipes), it will not be restarted every time after interaction.', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'condition' => [
                    'king_addons_testimonial_carousel_sw_autoplay_switcher!' => '',
                ]
            ]
        );

        $this->add_control(
            'king_addons_testimonial_carousel_sw_autoplay_pausedonmouseenter_switcher',
            [
                'label' => esc_html__('Pause on mouse enter', 'king-addons'),
                'description' => esc_html__('When enabled autoplay will be paused on pointer (mouse) enter over carousel container.', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'condition' => [
                    'king_addons_testimonial_carousel_sw_autoplay_switcher!' => '',
                ]
            ]
        );

        $this->add_control(
            'king_addons_testimonial_carousel_sw_autoplay_reversedirection_switcher',
            [
                'label' => esc_html__('Reverse Direction', 'king-addons'),
                'description' => esc_html__('Enables autoplay in reverse direction.', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'condition' => [
                    'king_addons_testimonial_carousel_sw_autoplay_switcher!' => '',
                ]
            ]
        );

        $this->add_control(
            'king_addons_testimonial_carousel_sw_autoplay_like_ticker_switcher',
            [
                'label' => esc_html__('Autoplay Like Ticker (marquee effect)', 'king-addons'),
                'description' => esc_html__('Autoscrolls cards as on ticker, also known as marquee effect.', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'condition' => [
                    'king_addons_testimonial_carousel_sw_autoplay_switcher!' => '',
                ],
            ]
        );

        $this->add_control(
            'king_addons_testimonial_carousel_sw_autoplay_like_ticker_autoplay_speed',
            [
                'label' => esc_html__('Autoplay Speed (ms)', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'step' => 10,
                'default' => 6000,
                'condition' => [
                    'king_addons_testimonial_carousel_sw_autoplay_like_ticker_switcher' => 'yes',
                ]
            ]
        );

        $this->add_control(
            'king_addons_testimonial_carousel_sw_scrolling_speed',
            [
                'label' => esc_html__('Scrolling Speed (ms)', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'step' => 10,
                'default' => 600,
                'separator' => 'before',
                'condition' => [
                    'king_addons_testimonial_carousel_sw_autoplay_like_ticker_switcher!' => 'yes',
                ]
            ]
        );

        $this->add_responsive_control(
            'king_addons_testimonial_carousel_sw_align_card_full_height',
            [
                'label' => esc_html__('Make testimonial full height', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'auto',
                'options' => [
                    '100%' => esc_html__('Yes', 'king-addons'),
                    'auto' => esc_html__('No', 'king-addons'),
                ],
                'separator' => 'before',
                'selectors' => [
                    '{{WRAPPER}} .swiper-slide' => 'height: auto;',
                    '{{WRAPPER}} .swiper-slide .king-addons-testimonial-carousel-layout' => 'height: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'king_addons_testimonial_carousel_sw_align_card',
            [
                'label' => esc_html__('Align Whole Card', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'unset' => esc_html__('Default', 'king-addons'),
                    'start' => esc_html__('Start', 'king-addons'),
                    'center' => esc_html__('Center', 'king-addons'),
                    'end' => esc_html__('End', 'king-addons'),
                ],
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}} .swiper-slide' => 'align-content: {{VALUE}};',
                ],
                'condition' => [
                    'king_addons_testimonial_carousel_sw_align_card_full_height' => 'auto'
                ],
            ]
        );

        $this->add_responsive_control(
            'king_addons_testimonial_carousel_sw_align_card_content',
            [
                'label' => esc_html__('Align Card Content', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'unset' => esc_html__('Default', 'king-addons'),
                    'start' => esc_html__('Start', 'king-addons'),
                    'center' => esc_html__('Center', 'king-addons'),
                    'end' => esc_html__('End', 'king-addons'),
                ],
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}} .swiper-slide .king-addons-testimonial-carousel-layout' => 'justify-content: {{VALUE}};'
                ],
                'condition' => [
                    'king_addons_testimonial_carousel_sw_align_card_full_height' => '100%'
                ],
            ]
        );

        $this->end_controls_section();
        /** END SECTION: Carousel Settings */

        /** SECTION: Responsive Settings */
        $this->start_controls_section(
            'king_addons_testimonial_carousel_sw_content_section_responsive_settings',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Responsive Settings', 'king-addons'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'king_addons_testimonial_carousel_sw_desktop_title',
            [
                'label' => '<b>' . esc_html__('Desktop', 'king-addons') . '</b>',
                'type' => Controls_Manager::RAW_HTML,
            ]
        );

        $this->add_control(
            'king_addons_testimonial_carousel_sw_desktop_cards_per_view',
            [
                'label' => esc_html__('Cards per view', 'king-addons'),
                'description' => esc_html__('The number of slides per view can be a fractional number like 4.5, 5.25, 6.45, etc., to indicate that further slides exist.', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'step' => 0.1,
                'default' => 3.0,
            ]
        );

        $this->add_control(
            'king_addons_testimonial_carousel_sw_desktop_space_between_cards',
            [
                'label' => esc_html__('Space between cards (px)', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'step' => 1,
                'default' => 30,
                'separator' => 'after',
            ]
        );

        $this->add_control(
            'king_addons_testimonial_carousel_sw_tablet_title',
            [
                'label' => '<b>' . esc_html__('Tablet', 'king-addons') . '</b>',
                'type' => Controls_Manager::RAW_HTML,
            ]
        );

        $this->add_control(
            'king_addons_testimonial_carousel_sw_tablet_cards_per_view',
            [
                'label' => esc_html__('Cards per view', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'step' => 0.1,
                'default' => 2.0,
            ]
        );

        $this->add_control(
            'king_addons_testimonial_carousel_sw_tablet_space_between_cards',
            [
                'label' => esc_html__('Space between cards (px)', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'step' => 1,
                'default' => 30,
                'separator' => 'after',
            ]
        );

        $this->add_control(
            'king_addons_testimonial_carousel_sw_tablet_breakpoint',
            [
                'label' => esc_html__('Breakpoint (px)', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'step' => 1,
                'default' => 1024,
            ]
        );

        $this->add_control(
            'king_addons_testimonial_carousel_sw_mobile_title',
            [
                'label' => '<b>' . esc_html__('Mobile', 'king-addons') . '</b>',
                'type' => Controls_Manager::RAW_HTML,
            ]
        );

        $this->add_control(
            'king_addons_testimonial_carousel_sw_mobile_cards_per_view',
            [
                'label' => esc_html__('Cards per view', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'step' => 0.1,
                'default' => 1.0,
            ]
        );

        $this->add_control(
            'king_addons_testimonial_carousel_sw_mobile_space_between_cards',
            [
                'label' => esc_html__('Space between cards (px)', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'step' => 1,
                'default' => 30,
            ]
        );

        $this->add_control(
            'king_addons_testimonial_carousel_sw_mobile_breakpoint',
            [
                'label' => esc_html__('Breakpoint (px)', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'step' => 1,
                'default' => 767,
            ]
        );

        $this->end_controls_section();
        /** END SECTION: Responsive Settings */

        /** START SECTION: Navigation */
        $this->start_controls_section(
            'king_addons_testimonial_carousel_sw_content_section_nav',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Navigation', 'king-addons'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'king_addons_testimonial_carousel_sw_nav_switcher',
            [
                'label' => esc_html__('Enable navigation', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_responsive_control(
            'king_addons_testimonial_carousel_sw_nav_display',
            [
                'label' => esc_html__('Display on different screen sizes', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'flex' => esc_html__('Show', 'king-addons'),
                    'none' => esc_html__('Hide', 'king-addons'),
                ],
                'desktop_default' => 'flex',
                'tablet_default' => 'none',
                'mobile_default' => 'none',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-testimonial-carousel-sw-nav' => 'display: {{VALUE}};'
                ],
                'condition' => [
                    'king_addons_testimonial_carousel_sw_nav_switcher' => 'yes'
                ]
            ]
        );

        $this->end_controls_section();
        /** END SECTION: Navigation */

        /** START SECTION: Pagination */
        $this->start_controls_section(
            'king_addons_testimonial_carousel_sw_content_section_pag',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Pagination', 'king-addons'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'king_addons_testimonial_carousel_sw_pag_switcher',
            [
                'label' => esc_html__('Enable pagination', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'king_addons_testimonial_carousel_sw_pag_type',
            [
                'label' => esc_html__('Type', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'bullets' => esc_html__('Bullets', 'king-addons'),
                    'progressbar' => esc_html__('Progress Bar', 'king-addons'),
                    'fraction' => esc_html__('Fraction', 'king-addons'),
                ],
                'default' => 'bullets',
                'condition' => [
                    'king_addons_testimonial_carousel_sw_pag_switcher' => 'yes'
                ]
            ]
        );

        // position top / bottom
        $this->add_responsive_control(
            'king_addons_testimonial_carousel_sw_pag_progressbar_position',
            [
                'label' => esc_html__('Progress bar position', 'king-addons'),
                'description' => esc_html__('Check margin settings to properly set the position of the progress bar.', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '0' => esc_html__('Top', 'king-addons'),
                    'unset' => esc_html__('Bottom', 'king-addons'),
                ],
                'default' => 'unset',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-testimonial-carousel-sw-pagination' => 'top: {{VALUE}}; bottom: 0;'
                ],
                'condition' => [
                    'king_addons_testimonial_carousel_sw_pag_type' => 'progressbar',
                ]
            ]
        );

        $this->add_control(
            'king_addons_testimonial_carousel_sw_pag_clickable_switcher',
            [
                'label' => esc_html__('Clickable Bullets', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'condition' => [
                    'king_addons_testimonial_carousel_sw_pag_type' => 'bullets',
                ]
            ]
        );

        $this->add_control(
            'king_addons_testimonial_carousel_sw_pag_dynamic_switcher',
            [
                'label' => esc_html__('Dynamic Bullets', 'king-addons'),
                'description' => esc_html__('Good to enable if you use bullets pagination with a lot of cards. So it will keep only few bullets visible at the same time.', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => '',
                'condition' => [
                    'king_addons_testimonial_carousel_sw_pag_type' => 'bullets',
                ]
            ]
        );

        $this->add_control(
            'king_addons_testimonial_carousel_sw_pag_dynamic_number',
            [
                'label' => esc_html__('Number of Dynamic Bullets', 'king-addons'),
                'description' => esc_html__('The number of main bullets visible when Dynamic Bullets enabled.', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'step' => 1,
                'default' => 1,
                'condition' => [
                    'king_addons_testimonial_carousel_sw_pag_dynamic_switcher' => 'yes',
                    'king_addons_testimonial_carousel_sw_pag_type' => 'bullets',
                ]
            ]
        );

        $this->add_responsive_control(
            'king_addons_testimonial_carousel_sw_pag_display',
            [
                'label' => esc_html__('Display on different screen sizes', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'block' => esc_html__('Show', 'king-addons'),
                    'none' => esc_html__('Hide', 'king-addons'),
                ],
                'desktop_default' => 'block',
                'tablet_default' => 'block',
                'mobile_default' => 'block',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-testimonial-carousel-sw-pagination' => 'display: {{VALUE}};'
                ],
                'condition' => [
                    'king_addons_testimonial_carousel_sw_pag_switcher' => 'yes'
                ]
            ]
        );

        $this->end_controls_section();
        /** END SECTION: Pagination */

        /** ========================================================== */
        /** ========================================================== */
        /** ========================================================== */
        /** STYLES */

        // IMAGE
        $this->start_controls_section(
            'king_addons_testimonial_carousel_section_image_style',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Image', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'king_addons_testimonial_carousel_image_width',
            [
                'label' => esc_html__('Width (px)', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'step' => 1,
                'default' => 70,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-testimonial-carousel-image img' => 'width: {{SIZE}}px;',
                    '{{WRAPPER}} .king-addons-testimonial-carousel-layout-4 .king-addons-testimonial-carousel-image' => 'flex: 0 0 {{SIZE}}px;',
                ],
            ]
        );

        $this->add_responsive_control(
            'king_addons_testimonial_carousel_image_height',
            [
                'label' => esc_html__('Height (px)', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'step' => 1,
                'default' => 70,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-testimonial-carousel-image img' => 'height: {{SIZE}}px;'
                ],
            ]
        );

        $this->add_responsive_control(
            'king_addons_testimonial_carousel_whole_testimonial_carousel_gap',
            [
                'label' => esc_html__('Gap between Image and Content (px)', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'step' => 1,
                'default' => 20,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-testimonial-carousel-layout-4, 
                    {{WRAPPER}} .king-addons-testimonial-carousel-layout-5' => 'gap: {{SIZE}}px;'
                ],
                'condition' => [
                    'king_addons_testimonial_carousel_layout' => ['layout-4', 'layout-5']
                ],
            ]
        );

        $this->add_responsive_control(
            'king_addons_testimonial_carousel_whole_testimonial_carousel_gap_2',
            [
                'label' => esc_html__('Gap between Image and Content (px)', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'step' => 1,
                'default' => 15,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-testimonial-carousel-layout-1, 
                    {{WRAPPER}} .king-addons-testimonial-carousel-layout-2,
                    {{WRAPPER}} .king-addons-testimonial-carousel-layout-3,
                    {{WRAPPER}} .king-addons-testimonial-carousel-layout-8' => 'gap: {{SIZE}}px;'
                ],
                'condition' => [
                    'king_addons_testimonial_carousel_layout' => ['layout-1', 'layout-2', 'layout-3', 'layout-8']
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Css_Filter::get_type(),
            [
                'label' => esc_html__('CSS Filter', 'king-addons'),
                'name' => 'king_addons_testimonial_carousel_image_css_filters',
                'selector' => '{{WRAPPER}} .king-addons-testimonial-carousel-image img',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'king_addons_testimonial_carousel_image_box_shadow',
                'selector' => '{{WRAPPER}} .king-addons-testimonial-carousel-image img',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'king_addons_testimonial_carousel_image_border',
                'selector' => '{{WRAPPER}} .king-addons-testimonial-carousel-image img',
                'fields_options' => [
                    'border' => [
                        'default' => 'none',
                    ],
                ],
            ]
        );

        $this->add_responsive_control(
            'king_addons_testimonial_carousel_image_border_radius',
            [
                'label' => esc_html__('Border Radius', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'default' => [
                    'top' => 100,
                    'right' => 100,
                    'bottom' => 100,
                    'left' => 100,
                    'unit' => '%',
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-testimonial-carousel-image img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->end_controls_section();

        // TITLE
        $this->start_controls_section(
            'king_addons_testimonial_carousel_section_title_style',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Title', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        // title - typography
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => esc_html__('Typography', 'king-addons'),
                'name' => 'king_addons_testimonial_carousel_title_typography',
                'fields_options' => [
                    'font_size' => [
                        'default' => [
                            'size' => 18,
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
                'selector' => '{{WRAPPER}} .king-addons-testimonial-carousel-title',
            ]
        );

        // title - color
        $this->add_control(
            'king_addons_testimonial_carousel_title_color',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#000000',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-testimonial-carousel-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        // title - margin
        $this->add_responsive_control(
            'king_addons_testimonial_carousel_title_margin',
            [
                'label' => esc_html__('Margin', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'default' => [
                    'top' => 0,
                    'right' => 5,
                    'bottom' => 0,
                    'left' => 0,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-testimonial-carousel-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'king_addons_testimonial_carousel_section_subtitle_style',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Subtitle', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        // subtitle - typography
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => esc_html__('Typography', 'king-addons'),
                'name' => 'king_addons_testimonial_carousel_subtitle_typography',
                'fields_options' => [
                    'font_size' => [
                        'default' => [
                            'size' => 15,
                            'unit' => 'px'
                        ],
                    ],
                    'font_weight' => [
                        'default' => 400,
                    ],
                    'typography' => [
                        'default' => 'custom',
                    ]
                ],
                'selector' => '{{WRAPPER}} .king-addons-testimonial-carousel-subtitle',
            ]
        );

        // subtitle - color
        $this->add_control(
            'king_addons_testimonial_carousel_subtitle_color',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#888888',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-testimonial-carousel-subtitle' => 'color: {{VALUE}};',
                ],
            ]
        );

        // subtitle - margin
        $this->add_responsive_control(
            'king_addons_testimonial_carousel_subtitle_margin',
            [
                'label' => esc_html__('Margin', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'default' => [
                    'top' => 0,
                    'right' => 0,
                    'bottom' => 0,
                    'left' => 0,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-testimonial-carousel-subtitle' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        // subtitle - margin
        $this->add_responsive_control(
            'king_addons_testimonial_carousel_title_subtitle_margin',
            [
                'label' => esc_html__('Title-Subtitle Block Margin', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'default' => [
                    'top' => 0,
                    'right' => 0,
                    'bottom' => 15,
                    'left' => 0,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-testimonial-carousel-person-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
                'condition' => [
                    'king_addons_testimonial_carousel_layout' => ['layout-2']
                ],
            ]
        );

        $this->add_responsive_control(
            'king_addons_testimonial_carousel_title_subtitle_margin_2',
            [
                'label' => esc_html__('Title-Subtitle Block Margin', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'default' => [
                    'top' => 0,
                    'right' => 0,
                    'bottom' => 10,
                    'left' => 0,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-testimonial-carousel-person-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
                'condition' => [
                    'king_addons_testimonial_carousel_layout' => ['layout-6', 'layout-7']
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'king_addons_testimonial_carousel_section_description_style',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Description', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        // description - typography
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => esc_html__('Typography', 'king-addons'),
                'name' => 'king_addons_testimonial_carousel_description_typography',
                'fields_options' => [
                    'font_size' => [
                        'default' => [
                            'size' => 16,
                            'unit' => 'px'
                        ],
                    ],
                    'font_weight' => [
                        'default' => 400,
                    ],
                    'typography' => [
                        'default' => 'custom',
                    ]
                ],
                'selector' => '{{WRAPPER}} .king-addons-testimonial-carousel-description',
            ]
        );

        // description - color
        $this->add_control(
            'king_addons_testimonial_carousel_description_color',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#000000',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-testimonial-carousel-description' => 'color: {{VALUE}};',
                ],
            ]
        );

        // description - margin
        $this->add_responsive_control(
            'king_addons_testimonial_carousel_description_margin',
            [
                'label' => esc_html__('Margin', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'default' => [
                    'top' => 0,
                    'right' => 0,
                    'bottom' => 10,
                    'left' => 0,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-testimonial-carousel-description' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'king_addons_testimonial_carousel_layout' => ['layout-1', 'layout-2', 'layout-3', 'layout-4', 'layout-5', 'layout-8']
                ],
            ]
        );

        $this->end_controls_section();

        // STYLE: RATING ============================================
        $this->start_controls_section(
            'king_addons_testimonial_carousel_section_icon_style',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Rating', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'king_addons_testimonial_carousel_icon_size',
            [
                'label' => esc_html__('Size', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', 'rem', 'vw', 'custom'],
                'range' => [
                    'px' => [
                        'max' => 100,
                    ],
                    'em' => [
                        'min' => 0,
                        'max' => 10,
                    ],
                    'rem' => [
                        'min' => 0,
                        'max' => 10,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-testimonial-carousel-rating-general' => '--king-addons-testimonial-carousel-rating-icon-font-size: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'king_addons_testimonial_carousel_icon_gap',
            [
                'label' => esc_html__('Spacing', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', 'rem', 'vw', 'custom'],
                'range' => [
                    'px' => [
                        'max' => 100,
                    ],
                    'em' => [
                        'min' => 0,
                        'max' => 10,
                    ],
                    'rem' => [
                        'min' => 0,
                        'max' => 10,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-testimonial-carousel-rating-general' => '--king-addons-testimonial-carousel-rating-gap: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_control(
            'king_addons_testimonial_carousel_icon_color',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-testimonial-carousel-rating-general' => '--king-addons-testimonial-carousel-rating-icon-marked-color: {{VALUE}}',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'king_addons_testimonial_carousel_icon_unmarked_color',
            [
                'label' => esc_html__('Unmarked Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-testimonial-carousel-rating-general' => '--king-addons-testimonial-carousel-rating-icon-color: {{VALUE}}',
                ],
            ]
        );

        // rating - margin
        $this->add_responsive_control(
            'king_addons_testimonial_carousel_icon_margin',
            [
                'label' => esc_html__('Margin', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'default' => [
                    'top' => 0,
                    'right' => 0,
                    'bottom' => 10,
                    'left' => 0,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-testimonial-carousel-rating-general' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'king_addons_testimonial_carousel_layout' => ['layout-1', 'layout-2', 'layout-4', 'layout-5', 'layout-8']
                ],
            ]
        );

        $this->end_controls_section();
        // END: RATING ============================================

        $this->start_controls_section(
            'king_addons_testimonial_carousel_section_whole_testimonial_carousel_style',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Testimonial Card', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );


        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'king_addons_testimonial_carousel_whole_testimonial_carousel_bg',
                'fields_options' => [
                    'color' => [
                        'default' => '#FFFFFF',
                    ],
                    'background' => [
                        'default' => 'classic',
                    ]
                ],
                'selector' => '{{WRAPPER}} .king-addons-testimonial-carousel-layout'
            ]
        );

        $this->add_responsive_control(
            'king_addons_testimonial_carousel_whole_testimonial_carousel_gap_3',
            [
                'label' => esc_html__('Space between Bio and Description (px)', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'step' => 1,
                'default' => 25,
                'separator' => 'before',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-testimonial-carousel-layout-6, 
                    {{WRAPPER}} .king-addons-testimonial-carousel-layout-7' => 'gap: {{SIZE}}px;'
                ],
                'condition' => [
                    'king_addons_testimonial_carousel_layout' => ['layout-6', 'layout-7']
                ],
            ]
        );

        $this->add_responsive_control(
            'king_addons_testimonial_carousel_whole_testimonial_carousel_gap_4',
            [
                'label' => esc_html__('Space between Image and Title + Subtitle, Rating (px)', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'step' => 1,
                'default' => 20,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-testimonial-carousel-layout-6 .king-addons-testimonial-carousel-content, 
                    {{WRAPPER}} .king-addons-testimonial-carousel-layout-7 .king-addons-testimonial-carousel-content' => 'gap: {{SIZE}}px;'
                ],
                'condition' => [
                    'king_addons_testimonial_carousel_layout' => ['layout-6', 'layout-7']
                ],
            ]
        );

        $this->add_responsive_control(
            'king_addons_testimonial_carousel_whole_testimonial_carousel_padding',
            [
                'label' => esc_html__('Padding', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'default' => [
                    'top' => 30,
                    'right' => 30,
                    'bottom' => 30,
                    'left' => 30,
                    'unit' => 'px',
                ],
                'separator' => 'before',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-testimonial-carousel-layout' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

//        $this->add_responsive_control(
//            'king_addons_testimonial_carousel_whole_testimonial_carousel_margin',
//            [
//                'label' => esc_html__('Margin', 'king-addons'),
//                'type' => Controls_Manager::DIMENSIONS,
//                'size_units' => ['px', '%'],
//                'default' => [
//                    'top' => 0,
//                    'right' => 0,
//                    'bottom' => 0,
//                    'left' => 0,
//                    'unit' => 'px',
//                ],
//                'selectors' => [
//                    '{{WRAPPER}} .king-addons-testimonial-carousel-layout' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
//                ]
//            ]
//        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'king_addons_testimonial_carousel_whole_testimonial_carousel_box_shadow',
                'selector' => '{{WRAPPER}} .king-addons-testimonial-carousel-layout',
                'fields_options' => [
                    'box_shadow' => [
                        'default' => [
                            'horizontal' => 0,
                            'vertical' => 3,
                            'blur' => 10,
                            'spread' => 0,
                            'color' => 'rgba(36, 36, 36, 0.1)',
                        ],
                    ],
                    'box_shadow_type' => [
                        'default' => 'yes',
                    ],
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'king_addons_testimonial_carousel_whole_testimonial_carousel_border',
                'selector' => '{{WRAPPER}} .king-addons-testimonial-carousel-layout',
                'fields_options' => [
                    'border' => [
                        'default' => 'none',
                    ],
                ],
            ]
        );

        $this->add_responsive_control(
            'king_addons_testimonial_carousel_whole_testimonial_carousel_border_radius',
            [
                'label' => esc_html__('Border Radius', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'default' => [
                    'top' => 8,
                    'right' => 8,
                    'bottom' => 8,
                    'left' => 8,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-testimonial-carousel-layout' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->end_controls_section();

        /** START SECTION: Navigation */
        $this->start_controls_section(
            'king_addons_testimonial_carousel_sw_style_section_nav',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Navigation', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'king_addons_testimonial_carousel_sw_nav_icon_prev_title',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Previous button', 'king-addons'),
                'type' => Controls_Manager::HEADING,
            ]
        );

        $this->add_control(
            'king_addons_testimonial_carousel_sw_nav_icon_prev',
            [
                'label' => esc_html__('Icon', 'king-addons'),
                'type' => Controls_Manager::ICONS,
                'skin' => 'inline',
                'label_block' => false,
                'default' => [
                    'value' => 'fas fa-chevron-left',
                    'library' => 'fa-solid',
                ],
            ]
        );

        $this->add_responsive_control(
            'king_addons_testimonial_carousel_sw_nav_margin_prev',
            [
                'label' => esc_html__('Margin', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'default' => [
                    'top' => 0,
                    'right' => 0,
                    'bottom' => 0,
                    'left' => 0,
                    'unit' => 'px',
                    'isLinked' => false
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-testimonial-carousel-sw-nav-prev .king-addons-testimonial-carousel-sw-nav-inner' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );


        $this->add_responsive_control(
            'king_addons_testimonial_carousel_sw_nav_prev_transform_scale',
            [
                'label' => esc_html__('Scale on hover (%)', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'step' => 0.1,
                'min' => 0,
                'default' => 1.3,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-testimonial-carousel-sw-nav-prev:hover .king-addons-testimonial-carousel-sw-nav-inner' => 'transform: scale({{VALUE}});',
                ],
            ]
        );

        $this->add_responsive_control(
            'king_addons_testimonial_carousel_sw_nav_position_v_prev',
            [
                'type' => Controls_Manager::SLIDER,
                'label' => esc_html__('Vertical position (%)', 'king-addons'),
                'size_units' => ['%'],
                'range' => [
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => '%',
                    'size' => 50,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-testimonial-carousel-sw-nav-prev' => 'top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'king_addons_testimonial_carousel_sw_nav_position_h_prev',
            [
                'type' => Controls_Manager::SLIDER,
                'label' => esc_html__('Horizontal position (%)', 'king-addons'),
                'size_units' => ['%'],
                'range' => [
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => '%',
                    'size' => 0,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-testimonial-carousel-sw-nav-prev' => 'left: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'king_addons_testimonial_carousel_sw_nav_icon_next_title',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Next button', 'king-addons'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'king_addons_testimonial_carousel_sw_nav_icon_next',
            [
                'label' => esc_html__('Icon', 'king-addons'),
                'type' => Controls_Manager::ICONS,
                'skin' => 'inline',
                'label_block' => false,
                'default' => [
                    'value' => 'fas fa-chevron-right',
                    'library' => 'fa-solid',
                ],
            ]
        );

        $this->add_responsive_control(
            'king_addons_testimonial_carousel_sw_nav_margin_next',
            [
                'label' => esc_html__('Margin', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'default' => [
                    'top' => 0,
                    'right' => 0,
                    'bottom' => 0,
                    'left' => 0,
                    'unit' => 'px',
                    'isLinked' => false
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-testimonial-carousel-sw-nav-next .king-addons-testimonial-carousel-sw-nav-inner' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->add_responsive_control(
            'king_addons_testimonial_carousel_sw_nav_next_transform_scale',
            [
                'label' => esc_html__('Scale on hover (%)', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'step' => 0.1,
                'min' => 0,
                'default' => 1.3,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-testimonial-carousel-sw-nav-next:hover .king-addons-testimonial-carousel-sw-nav-inner' => 'transform: scale({{VALUE}});',
                ],
            ]
        );

        $this->add_responsive_control(
            'king_addons_testimonial_carousel_sw_nav_position_v_next',
            [
                'type' => Controls_Manager::SLIDER,
                'label' => esc_html__('Vertical position (%)', 'king-addons'),
                'size_units' => ['%'],
                'range' => [
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => '%',
                    'size' => 50,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-testimonial-carousel-sw-nav-next' => 'top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'king_addons_testimonial_carousel_sw_nav_position_h_next',
            [
                'type' => Controls_Manager::SLIDER,
                'label' => esc_html__('Horizontal position (%)', 'king-addons'),
                'size_units' => ['%'],
                'range' => [
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => '%',
                    'size' => 100,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-testimonial-carousel-sw-nav-next' => 'right: calc(100% - {{SIZE}}{{UNIT}});',
                ],
            ]
        );

        // common
        $this->add_control(
            'king_addons_testimonial_carousel_sw_nav_icon_common_title',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Common settings', 'king-addons'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'king_addons_testimonial_carousel_sw_nav_icon_size',
            [
                'label' => esc_html__('Icon Size', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'step' => 1,
                'min' => 0,
                'default' => 32,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-testimonial-carousel-sw-nav i' => 'font-size: {{VALUE}}px; width: {{VALUE}}px;',
                    '{{WRAPPER}} .king-addons-testimonial-carousel-sw-nav img' => 'width: {{VALUE}}px; height: {{VALUE}}px;',
                    '{{WRAPPER}} .king-addons-testimonial-carousel-sw-nav svg' => 'width: {{VALUE}}px; height: {{VALUE}}px;'
                ],
            ]
        );

        $this->add_responsive_control(
            'king_addons_testimonial_carousel_sw_nav_transition_icon',
            [
                'label' => esc_html__('Transition duration for icon on hover (ms)', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'step' => 10,
                'default' => 300,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-testimonial-carousel-sw-nav i' => 'transition: all {{SIZE}}ms;',
                    '{{WRAPPER}} .king-addons-testimonial-carousel-sw-nav img' => 'transition: all {{SIZE}}ms;',
                    '{{WRAPPER}} .king-addons-testimonial-carousel-sw-nav svg' => 'transition: all {{SIZE}}ms;',
                    '{{WRAPPER}} .king-addons-testimonial-carousel-sw-nav .king-addons-testimonial-carousel-sw-nav-inner' => 'transition: all {{SIZE}}ms;',
                ],
            ]
        );

        $this->add_responsive_control(
            'king_addons_testimonial_carousel_sw_nav_padding',
            [
                'label' => esc_html__('Padding', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'default' => [
                    'top' => 10,
                    'right' => 10,
                    'bottom' => 10,
                    'left' => 10,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-testimonial-carousel-sw-nav-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->start_controls_tabs('king_addons_testimonial_carousel_sw_nav_tabs');

        $this->start_controls_tab(
            'king_addons_testimonial_carousel_sw_nav_tab_normal',
            [
                'label' => esc_html__('Normal', 'king-addons'),
            ]
        );

        $this->add_control(
            'king_addons_testimonial_carousel_sw_nav_color',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#00000033',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-testimonial-carousel-sw-nav i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .king-addons-testimonial-carousel-sw-nav svg' => 'fill: {{VALUE}};'
                ],
            ]
        );

        $this->add_control(
            'king_addons_testimonial_carousel_sw_nav_bg_color',
            [
                'label' => esc_html__('Background Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-testimonial-carousel-sw-nav-inner' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'king_addons_testimonial_carousel_sw_nav_box_shadow',
                'selector' => '{{WRAPPER}} .king-addons-testimonial-carousel-sw-nav-inner',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'king_addons_testimonial_carousel_sw_nav_border',
                'selector' => '{{WRAPPER}} .king-addons-testimonial-carousel-sw-nav-inner',
                'fields_options' => [
                    'border' => [
                        'default' => 'none',
                    ],
                ],
            ]
        );

        $this->add_responsive_control(
            'king_addons_testimonial_carousel_sw_nav_border_radius',
            [
                'label' => esc_html__('Border Radius', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'default' => [
                    'top' => 100,
                    'right' => 100,
                    'bottom' => 100,
                    'left' => 100,
                    'unit' => '%',
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-testimonial-carousel-sw-nav-inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );
        $this->end_controls_tab();

        $this->start_controls_tab(
            'king_addons_testimonial_carousel_sw_nav_tab_hover',
            [
                'label' => esc_html__('Hover', 'king-addons'),
            ]
        );

        $this->add_control(
            'king_addons_testimonial_carousel_sw_nav_color_hover',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-testimonial-carousel-sw-nav i:hover' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .king-addons-testimonial-carousel-sw-nav svg:hover' => 'fill: {{VALUE}};'
                ],
            ]
        );

        $this->add_control(
            'king_addons_testimonial_carousel_sw_nav_bg_color_hover',
            [
                'label' => esc_html__('Background Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-testimonial-carousel-sw-nav-inner:hover' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'king_addons_testimonial_carousel_sw_nav_box_shadow_hover',
                'selector' => '{{WRAPPER}} .king-addons-testimonial-carousel-sw-nav-inner:hover',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'king_addons_testimonial_carousel_sw_nav_border_hover',
                'selector' => '{{WRAPPER}} .king-addons-testimonial-carousel-sw-nav-inner:hover',
            ]
        );

        $this->add_responsive_control(
            'king_addons_testimonial_carousel_sw_nav_border_radius_hover',
            [
                'label' => esc_html__('Border Radius', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-testimonial-carousel-sw-nav-inner:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();
        $this->end_controls_section();
        /** END SECTION: Navigation */

        /** SECTION: Pagination */
        $this->start_controls_section(
            'king_addons_testimonial_carousel_sw_style_section_pag',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Pagination', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'king_addons_testimonial_carousel_sw_pag_color',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#000000',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-testimonial-carousel-sw-pagination' => '--swiper-theme-color: {{VALUE}}; --swiper-pagination-fraction-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'king_addons_testimonial_carousel_sw_pag_color_second',
            [
                'label' => esc_html__('Second Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#00000033',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-testimonial-carousel-sw-pagination' => '--swiper-pagination-progressbar-bg-color: {{VALUE}}; --swiper-pagination-bullet-inactive-color: {{VALUE}}; --swiper-pagination-bullet-inactive-opacity: 1;',
                ],
            ]
        );

        $this->add_control(
            'king_addons_testimonial_carousel_sw_pag_bullets_title',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Bullets type', 'king-addons'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'king_addons_testimonial_carousel_sw_pag_bullets_size',
            [
                'label' => esc_html__('Size', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'step' => 1,
                'default' => 8,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-testimonial-carousel-sw-pagination' => '--swiper-pagination-bullet-size: {{SIZE}}px;'
                ],
            ]
        );

        $this->add_responsive_control(
            'king_addons_testimonial_carousel_sw_pag_bullets_gap',
            [
                'label' => esc_html__('Bullets gap', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'step' => 1,
                'default' => 4,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-testimonial-carousel-sw-pagination' => '--swiper-pagination-bullet-horizontal-gap: {{SIZE}}px;'
                ],
            ]
        );

        $this->add_control(
            'king_addons_testimonial_carousel_sw_pag_progressbar_title',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Progress bar type', 'king-addons'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'king_addons_testimonial_carousel_sw_pag_progressbar_size',
            [
                'label' => esc_html__('Size', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'step' => 1,
                'default' => 4,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-testimonial-carousel-sw-pagination' => '--swiper-pagination-progressbar-size: {{SIZE}}px;'
                ],
            ]
        );

        $this->add_control(
            'king_addons_testimonial_carousel_sw_pag_fraction_title',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Fraction type', 'king-addons'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'king_addons_testimonial_carousel_sw_pag_fraction_typography',
                'selector' => '{{WRAPPER}} .king-addons-testimonial-carousel-sw-pagination.swiper-pagination-fraction',
            ]
        );

        $this->end_controls_section();
        /** END SECTION: Pagination */

        /** SECTION: Whole Carousel */
        $this->start_controls_section(
            'king_addons_testimonial_carousel_sw_style_section_whole_carousel',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Whole Carousel', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'king_addons_testimonial_carousel_sw_whole_carousel_bg',
                'selector' => '{{WRAPPER}} .king-addons-testimonial-carousel-sw-items'
            ]
        );

        $this->add_responsive_control(
            'king_addons_testimonial_carousel_sw_whole_carousel_margin',
            [
                'label' => esc_html__('Whole carousel margin', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'default' => [
                    'top' => 0,
                    'right' => 42,
                    'bottom' => 0,
                    'left' => 42,
                    'unit' => 'px',
                ],
                'tablet_default' => [
                    'top' => 0,
                    'right' => 0,
                    'bottom' => 0,
                    'left' => 0,
                    'unit' => 'px',
                ],
                'mobile_default' => [
                    'top' => 0,
                    'right' => 0,
                    'bottom' => 0,
                    'left' => 0,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-testimonial-carousel-sw-items' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->add_responsive_control(
            'king_addons_testimonial_carousel_sw_whole_carousel_margin_wrapper',
            [
                'label' => esc_html__('Cards wrapper margin', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'default' => [
                    'top' => 0,
                    'right' => 0,
                    'bottom' => 0,
                    'left' => 0,
                    'unit' => 'px',
                ],
                'tablet_default' => [
                    'top' => 0,
                    'right' => 0,
                    'bottom' => 0,
                    'left' => 0,
                    'unit' => 'px',
                ],
                'mobile_default' => [
                    'top' => 0,
                    'right' => 0,
                    'bottom' => 0,
                    'left' => 0,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-testimonial-carousel-sw-items .swiper-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->add_responsive_control(
            'king_addons_testimonial_carousel_sw_whole_carousel_padding',
            [
                'label' => esc_html__('Padding', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'default' => [
                    'top' => 15,
                    'right' => 15,
                    'bottom' => 56,
                    'left' => 15,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-testimonial-carousel-sw-items' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'king_addons_testimonial_carousel_sw_whole_carousel_box_shadow',
                'selector' => '{{WRAPPER}} .king-addons-testimonial-carousel-sw-items',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'king_addons_testimonial_carousel_sw_whole_carousel_border',
                'selector' => '{{WRAPPER}} .king-addons-testimonial-carousel-sw-items',
                'fields_options' => [
                    'border' => [
                        'default' => 'none',
                    ],
                ],
            ]
        );

        $this->add_responsive_control(
            'king_addons_testimonial_carousel_sw_whole_carousel_border_radius',
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
                    '{{WRAPPER}} .king-addons-testimonial-carousel-sw-items' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->end_controls_section();
        /** END SECTION: Whole Carousel */
    }

    protected function render(): void
    {
        $settings_all = $this->get_settings();
        $this_ID = $this->get_id();

        // Define allowed tags and attributes
        $allowed_tags = wp_kses_allowed_html('post');
        $allowed_tags['img']['srcset'] = true;
        $allowed_tags['img']['sizes'] = true;
        $allowed_tags['img']['decoding'] = true;
        $allowed_tags['img']['loading'] = true;

        $layout = $settings_all['king_addons_testimonial_carousel_layout'];

        /** START: Cards Container ===================== */
        ?>
        <div class="king-addons-testimonial-carousel-sw-items-wrap">
            <div class="king-addons-testimonial-carousel-sw-items king-addons-testimonial-carousel-sw-items-<?php echo esc_attr($this_ID); ?> swiper-container swiper">
                <div class="swiper-wrapper<?php if ('yes' === $settings_all['king_addons_testimonial_carousel_sw_autoplay_like_ticker_switcher']) {
                    echo ' king-addons-testimonial-carousel-sw-autoplay-like-ticker';
                } ?>">
                    <?php

                    foreach ($settings_all['king_addons_testimonial_carousel_content_items'] as $settings) {

                        $title = $settings['king_addons_testimonial_carousel_title'];
                        $subtitle = $settings['king_addons_testimonial_carousel_subtitle'];
                        $description = $settings['king_addons_testimonial_carousel_description'];

                        echo '<div class="swiper-slide">';
                        echo '<div class="king-addons-testimonial-carousel-layout king-addons-testimonial-carousel-' . esc_attr($layout) . '">';

                        if ($layout === 'layout-1') {

                            $this->renderImage($settings_all, $settings, $allowed_tags);

                            echo '<div class="king-addons-testimonial-carousel-content">';

                            $this->renderDescription($description);

                            $this->renderStars($settings_all, $settings);

                            $this->renderPerson($title, $subtitle);

                            echo '</div>';
                        }

                        if ($layout === 'layout-2') {

                            echo '<div class="king-addons-testimonial-carousel-content">';

                            $this->renderDescription($description);

                            $this->renderPerson($title, $subtitle);

                            $this->renderStars($settings_all, $settings);

                            echo '</div>';

                            $this->renderImage($settings_all, $settings, $allowed_tags);
                        }

                        if ($layout === 'layout-3') {

                            $this->renderDescription($description);

                            $this->renderImage($settings_all, $settings, $allowed_tags);

                            $this->renderPerson($title, $subtitle);

                            $this->renderStars($settings_all, $settings);

                        }

                        if ($layout === 'layout-4') {

                            $this->renderImage($settings_all, $settings, $allowed_tags);

                            echo '<div class="king-addons-testimonial-carousel-content">';

                            $this->renderDescription($description);

                            $this->renderStars($settings_all, $settings);

                            $this->renderPerson($title, $subtitle);

                            echo '</div>';
                        }

                        if ($layout === 'layout-5') {

                            echo '<div class="king-addons-testimonial-carousel-content">';

                            $this->renderDescription($description);

                            $this->renderStars($settings_all, $settings);

                            $this->renderPerson($title, $subtitle);

                            echo '</div>';

                            $this->renderImage($settings_all, $settings, $allowed_tags);
                        }

                        if ($layout === 'layout-6') {

                            $this->renderDescription($description);

                            echo '<div class="king-addons-testimonial-carousel-content">';

                            $this->renderImage($settings_all, $settings, $allowed_tags);

                            echo '<div class="king-addons-testimonial-carousel-content-inner">';

                            $this->renderPerson($title, $subtitle);

                            $this->renderStars($settings_all, $settings);

                            echo '</div>';

                            echo '</div>';
                        }

                        if ($layout === 'layout-7') {

                            echo '<div class="king-addons-testimonial-carousel-content">';

                            $this->renderImage($settings_all, $settings, $allowed_tags);

                            echo '<div class="king-addons-testimonial-carousel-content-inner">';

                            $this->renderPerson($title, $subtitle);

                            $this->renderStars($settings_all, $settings);

                            echo '</div>';

                            echo '</div>';

                            $this->renderDescription($description);
                        }

                        if ($layout === 'layout-8') {

                            $this->renderImage($settings_all, $settings, $allowed_tags);

                            echo '<div class="king-addons-testimonial-carousel-content">';

                            $this->renderDescription($description);

                            $this->renderStars($settings_all, $settings);

                            $this->renderPerson($title, $subtitle);

                            echo '</div>';
                        }

                        echo '</div>';
                        echo '</div>';
                        // END: foreach
                    } ?>
                </div>
            </div>
            <?php

            // pagination
            if ('yes' === $settings_all['king_addons_testimonial_carousel_sw_pag_switcher']) {
                echo '<div class="swiper-pagination king-addons-testimonial-carousel-sw-pagination king-addons-testimonial-carousel-sw-pagination-' .
                    esc_attr($this_ID) .
                    (('yes' === $settings_all['king_addons_testimonial_carousel_sw_autoplay_like_ticker_switcher']) ? ' king-addons-testimonial-carousel-sw-autoplay-like-ticker-pag' : '') .
                    '"></div>';
            }

            // navigation
            if ('yes' === $settings_all['king_addons_testimonial_carousel_sw_nav_switcher']) {

                echo '<div class="king-addons-testimonial-carousel-sw-nav-prev king-addons-testimonial-carousel-sw-nav-prev-' . esc_attr($this_ID) . ' king-addons-testimonial-carousel-sw-nav">';
                echo '<div class="king-addons-testimonial-carousel-sw-nav-inner">';
                Icons_Manager::render_icon($settings_all['king_addons_testimonial_carousel_sw_nav_icon_prev']);
                echo '</div>';
                echo '</div>';

                echo '<div class="king-addons-testimonial-carousel-sw-nav-next king-addons-testimonial-carousel-sw-nav-next-' . esc_attr($this_ID) . ' king-addons-testimonial-carousel-sw-nav">';
                echo '<div class="king-addons-testimonial-carousel-sw-nav-inner">';
                Icons_Manager::render_icon($settings_all['king_addons_testimonial_carousel_sw_nav_icon_next']);
                echo '</div>';
                echo '</div>';

            }
            ?>
        </div>
        <?php

        $js_swiper_check_if_DOM_already_loaded = "document.readyState === 'complete'";
        $js_swiper_check_if_DOM_not_already_loaded = "window.addEventListener('load', function () {";

        $js_swiper = "new Swiper('.king-addons-testimonial-carousel-sw-items-" . esc_js($this_ID) . "', {";
        $js_swiper .= "direction: 'horizontal',";

        $js_swiper .= "slidesPerView: " . esc_js($settings_all['king_addons_testimonial_carousel_sw_desktop_cards_per_view']) . ",";
        $js_swiper .= "spaceBetween: " . esc_js($settings_all['king_addons_testimonial_carousel_sw_desktop_space_between_cards']) . ",";

        // Responsive breakpoints
        $js_swiper .= 'breakpoints: {0: {slidesPerView: ' .
            esc_js($settings_all['king_addons_testimonial_carousel_sw_mobile_cards_per_view']) . ', spaceBetween: ' .
            esc_js($settings_all['king_addons_testimonial_carousel_sw_mobile_space_between_cards']) . '}, ';
        $js_swiper .=
            esc_js(($settings_all['king_addons_testimonial_carousel_sw_mobile_breakpoint'] + 1)) . ': {slidesPerView: ' .
            esc_js($settings_all['king_addons_testimonial_carousel_sw_tablet_cards_per_view']) . ', spaceBetween: ' .
            esc_js($settings_all['king_addons_testimonial_carousel_sw_tablet_space_between_cards']) . '}, ';
        $js_swiper .=
            esc_js(($settings_all['king_addons_testimonial_carousel_sw_tablet_breakpoint'] + 1)) . ': {slidesPerView: ' .
            esc_js($settings_all['king_addons_testimonial_carousel_sw_desktop_cards_per_view']) . ', spaceBetween: ' .
            esc_js($settings_all['king_addons_testimonial_carousel_sw_desktop_space_between_cards']) . '}},';

        // Scrolling speed
        if ('yes' !== $settings_all['king_addons_testimonial_carousel_sw_autoplay_like_ticker_switcher']) {
            $js_swiper .= "speed: " . esc_js($settings_all['king_addons_testimonial_carousel_sw_scrolling_speed']) . ",";
        } else {
            $js_swiper .= "speed: " . esc_js($settings_all['king_addons_testimonial_carousel_sw_autoplay_like_ticker_autoplay_speed']) . ",";
        }

        // Pagination
        if ('yes' === $settings_all['king_addons_testimonial_carousel_sw_pag_switcher']) {
            $js_swiper .= "pagination: {el: '.king-addons-testimonial-carousel-sw-pagination-" .
                esc_js($this_ID) . "', " .
                ('yes' === $settings_all['king_addons_testimonial_carousel_sw_pag_clickable_switcher'] ? 'clickable: true, ' : '');
            if ('yes' === $settings_all['king_addons_testimonial_carousel_sw_pag_dynamic_switcher']) {
                $js_swiper .= 'dynamicBullets: true, ';
                $js_swiper .= 'dynamicMainBullets: ' . esc_js($settings_all['king_addons_testimonial_carousel_sw_pag_dynamic_number'] . ', ');
            }
            $js_swiper .= "type: '" . esc_js($settings_all['king_addons_testimonial_carousel_sw_pag_type']) . "'},";
        }

        // Navigation
        if ('yes' === $settings_all['king_addons_testimonial_carousel_sw_nav_switcher']) {
            $js_swiper .= "navigation: {nextEl: '.king-addons-testimonial-carousel-sw-nav-next-" .
                esc_js($this_ID) . "', prevEl: '.king-addons-testimonial-carousel-sw-nav-prev-" .
                esc_js($this_ID) . "'},";
        }

        // Loop
        if ('yes' === $settings_all['king_addons_testimonial_carousel_sw_loop_switcher']) {
            $js_swiper .= "loop: true,";
        }

        // Rewind
        if ('yes' === $settings_all['king_addons_testimonial_carousel_sw_rewind_switcher']) {
            $js_swiper .= "rewind: true,";
        }

        // Autoplay
        if ('yes' === $settings_all['king_addons_testimonial_carousel_sw_autoplay_switcher']) {
            $js_swiper .= "autoplay: {";
            if ('yes' !== $settings_all['king_addons_testimonial_carousel_sw_autoplay_like_ticker_switcher']) {
                $js_swiper .= "delay: " . esc_js($settings_all['king_addons_testimonial_carousel_sw_autoplay_delay']) . ",";
            } else {
                $js_swiper .= "delay: 0,";
            }
            $js_swiper .= "disableOnInteraction: " . ('yes' === $settings_all['king_addons_testimonial_carousel_sw_autoplay_disableoninteraction_switcher'] ? 'true' : 'false') . ",";
            $js_swiper .= "pauseOnMouseEnter: " . ('yes' === $settings_all['king_addons_testimonial_carousel_sw_autoplay_pausedonmouseenter_switcher'] ? 'true' : 'false') . ",";
            $js_swiper .= "reverseDirection: " . ('yes' === $settings_all['king_addons_testimonial_carousel_sw_autoplay_reversedirection_switcher'] ? 'true' : 'false') . ",";
            $js_swiper .= "},";
        }

        $js_swiper .= "})";

        $js_swiper_full = $js_swiper_check_if_DOM_already_loaded . ' ?' . $js_swiper . ' : ' . $js_swiper_check_if_DOM_not_already_loaded . $js_swiper . '; });';

        wp_print_inline_script_tag($js_swiper_full);
        /** END: Cards Container ===================== */


    }

    public function renderImage($settings_all, $settings, $allowed_tags): void
    {
        if ($settings_all['king_addons_testimonial_carousel_image_switcher'] == 'yes') {
            $image = Group_Control_Image_Size::get_attachment_image_html($settings, 'king_addons_testimonial_carousel_image_size', 'king_addons_testimonial_carousel_image');
            echo '<div class="king-addons-testimonial-carousel-image">';
            echo wp_kses($image, $allowed_tags);
            echo '</div>';
        }
    }

    public function renderPerson($title, $subtitle): void
    {
        echo '<div class="king-addons-testimonial-carousel-person-wrapper">';

        echo '<div class="king-addons-testimonial-carousel-title">';
        echo esc_html($title);
        echo '</div>';

        echo '<div class="king-addons-testimonial-carousel-subtitle">';
        echo esc_html($subtitle);
        echo '</div>';

        echo '</div>';
    }

    public function renderDescription($description): void
    {
        echo '<div class="king-addons-testimonial-carousel-description">';
        echo wp_kses_post($description);
        echo '</div>';
    }

    public function renderStars($settings_all, $settings): void
    {
        if ($settings_all['king_addons_testimonial_carousel_rating_switcher'] == 'yes') {
            echo '<div class="king-addons-testimonial-carousel-stars">';
            echo '<div class="king-addons-testimonial-carousel-rating-general">';
            $this->renderRating($settings_all, $settings);
            echo '</div>';
            echo '</div>';
        }
    }

    protected function get_rating_value($settings_all, $settings): float
    {
        $initial_value = $this->get_rating_scale($settings_all);
        $rating_value = $settings['king_addons_testimonial_carousel_rating_value'];

        if ('' === $rating_value) {
            $rating_value = $initial_value;
        }

        $rating_value = floatval($rating_value);

        return round($rating_value, 2);
    }

    protected function get_rating_scale($settings_all): int
    {
        return intval($settings_all['king_addons_testimonial_carousel_rating_scale']['size']);
    }

    protected function get_icon_marked_width($settings_all, $settings, $icon_index): string
    {
        $rating_value = $this->get_rating_value($settings_all, $settings);

        $width = '0%';

        if ($rating_value >= $icon_index) {
            $width = '100%';
        } elseif (intval(ceil($rating_value)) === $icon_index) {
            $width = ($rating_value - ($icon_index - 1)) * 100 . '%';
        }

        return $width;
    }

    protected function get_icon_markup($settings_all, $settings): string
    {
        $icon = $settings_all['king_addons_testimonial_carousel_rating_icon'];
        $rating_scale = $this->get_rating_scale($settings_all);

        ob_start();

        for ($index = 1; $index <= $rating_scale; $index++) {
            echo '<div class="king-addons-testimonial-carousel-icon">';

            $icon_marked_width = $this->get_icon_marked_width($settings_all, $settings, $index);

            $style_attribute = $icon_marked_width !== '100%' ? 'style="--king-addons-testimonial-carousel-rating-icon-marked-width: ' . esc_attr($icon_marked_width) . ';"' : '';

            echo '<div class="king-addons-testimonial-carousel-icon-wrapper king-addons-testimonial-carousel-icon-marked" ' . $style_attribute . '>';
            echo Icons_Manager::try_get_icon_html($icon, ['aria-hidden' => 'true']);
            echo '</div>';

            echo '<div class="king-addons-testimonial-carousel-icon-wrapper king-addons-testimonial-carousel-icon-unmarked">';
            echo Icons_Manager::try_get_icon_html($icon, ['aria-hidden' => 'true']);
            echo '</div>';

            echo '</div>';
        }

        return ob_get_clean();
    }

    protected function renderRating($settings_all, $settings): void
    {
        $rating_value = $this->get_rating_value($settings_all, $settings);
        $rating_scale = $this->get_rating_scale($settings_all);
        $aria_label = sprintf(
            esc_html__('Rated %1$s out of %2$s', 'king-addons'),
            $rating_value,
            $rating_scale
        );

        echo '<div class="king-addons-testimonial-carousel-rating" itemtype="https://schema.org/Rating" itemscope itemprop="reviewRating">';
        ?>
        <meta itemprop="worstRating" content="0">
        <meta itemprop="bestRating"
              content="<?php echo esc_attr($rating_scale); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
              ?>">
        <div class="king-addons-testimonial-carousel-rating-wrapper" itemprop="ratingValue"
             content="<?php echo esc_attr($rating_value); ?>" role="img"
             aria-label="<?php echo esc_attr($aria_label); ?>">
            <?php echo $this->get_icon_markup($settings_all, $settings); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            ?>
        </div>
        <?php
        echo '</div>';
    }
}