<?php /** @noinspection SpellCheckingInspection, DuplicatedCode, PhpUnused */

namespace King_Addons;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;
use Elementor\Widget_Base;
use WP_Query;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class Blog_Posts extends Widget_Base
{
    public function get_name(): string
    {
        return 'king-addons-blog-posts';
    }

    public function get_title(): string
    {
        return esc_html__('Blog Posts Carousel', 'king-addons');
    }

    public function get_icon(): string
    {
        return 'king-addons-icon king-addons-blog-posts';
    }

    public function get_script_depends(): array
    {
        return [KING_ADDONS_ASSETS_UNIQUE_KEY . '-swiper-swiper'];
    }

    public function get_style_depends(): array
    {
        return [KING_ADDONS_ASSETS_UNIQUE_KEY . '-swiper-swiper', KING_ADDONS_ASSETS_UNIQUE_KEY . '-blog-posts-style'];
    }

    public function get_categories(): array
    {
        return ['king-addons'];
    }

    public function get_keywords(): array
    {
        return ['card', 'carousel', 'slider', 'scroller', 'swiper', 'content', 'button', 'dot', 'dots', 'navigation',
            'cards', 'wheel', 'touch', 'nav', 'navigation', 'animation', 'effect', 'animated', 'template', 'link',
            'left', 'right', 'top', 'bottom', 'vertical', 'horizontal', 'mouse', 'dragging', 'hover', 'over',
            'hover over', 'picture', 'float', 'floating', 'sticky', 'click', 'target', 'point', 'king', 'addons',
            'mouseover', 'page', 'blog posts', 'kingaddons', 'king-addons', 'team', 'members', 'testimonial',
            'testimonials', 'reviews', ' team memebers', 'drag', 'scroll', 'scrolling', 'tabs', 'tab'];
    }

    public function get_custom_help_url(): string
    {
        return 'https://kingaddons.com/elementor/blog-posts-carousel/';
    }

    protected function register_controls(): void
    {
        /** START TAB: CONTENT ===================== */
        /** SECTION: Carousel Settings */
        $this->start_controls_section(
            'kng_blog_posts_content_section_caousel_settings',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Carousel Settings', 'king-addons'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'kng_blog_posts_number_of_posts',
            [
                'label' => esc_html__('Number of latest posts', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'step' => 1,
                'default' => 5,
            ]
        );

        $this->add_control(
            'kng_blog_posts_number_of_words',
            [
                'label' => esc_html__('Trim description to a certain number of words.', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'step' => 1,
                'default' => 30,
            ]
        );

        $this->add_control(
            'kng_blog_posts_loop_switcher',
            [
                'label' => esc_html__('Loop Cards', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes'
            ]
        );

        $this->add_control(
            'kng_blog_posts_rewind_switcher',
            [
                'label' => esc_html__('Rewind Cards', 'king-addons'),
                'description' => esc_html__('When enabled, clicking the next navigation button when on last slide will slide back to the first slide. Clicking the prev navigation button when on first slide will slide forward to the last slide. Should not be used together with loop mode.', 'king-addons'),
                'type' => Controls_Manager::SWITCHER
            ]
        );

        $this->add_control(
            'kng_blog_posts_ignore_sticky_posts_switcher',
            [
                'label' => esc_html__('Ignore sticky posts', 'king-addons'),
                'description' => esc_html__('Check the page on live preview to see sticky posts.', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'no'
            ]
        );

        $this->add_control(
            'kng_blog_posts_autoplay_switcher',
            [
                'label' => esc_html__('Autoplay Carousel Cards', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'kng_blog_posts_autoplay_delay',
            [
                'label' => esc_html__('Delay between transitions (ms)', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'step' => 10,
                'default' => 2000,
                'condition' => [
                    'kng_blog_posts_autoplay_switcher!' => '',
                    'kng_blog_posts_autoplay_like_ticker_switcher!' => 'yes',
                ]
            ]
        );

        $this->add_control(
            'kng_blog_posts_autoplay_disableoninteraction_switcher',
            [
                'label' => esc_html__('Disable on interaction', 'king-addons'),
                'description' => esc_html__('Autoplay will be disabled after user interactions (swipes), it will not be restarted every time after interaction.', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'condition' => [
                    'kng_blog_posts_autoplay_switcher!' => '',
                ]
            ]
        );

        $this->add_control(
            'kng_blog_posts_autoplay_pausedonmouseenter_switcher',
            [
                'label' => esc_html__('Pause on mouse enter', 'king-addons'),
                'description' => esc_html__('When enabled autoplay will be paused on pointer (mouse) enter over carousel container.', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'condition' => [
                    'kng_blog_posts_autoplay_switcher!' => '',
                ]
            ]
        );

        $this->add_control(
            'kng_blog_posts_autoplay_reversedirection_switcher',
            [
                'label' => esc_html__('Reverse Direction', 'king-addons'),
                'description' => esc_html__('Enables autoplay in reverse direction.', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'condition' => [
                    'kng_blog_posts_autoplay_switcher!' => '',
                ]
            ]
        );

        $this->add_control(
            'kng_blog_posts_autoplay_like_ticker_switcher',
            [
                'label' => esc_html__('Autoplay Like Ticker (marquee effect)', 'king-addons'),
                'description' => esc_html__('Autoscrolls cards as on ticker, also known as marquee effect.', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'condition' => [
                    'kng_blog_posts_autoplay_switcher!' => '',
                ],
            ]
        );

        $this->add_control(
            'kng_blog_posts_autoplay_like_ticker_autoplay_speed',
            [
                'label' => esc_html__('Autoplay Speed (ms)', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'step' => 10,
                'default' => 6000,
                'condition' => [
                    'kng_blog_posts_autoplay_like_ticker_switcher' => 'yes',
                ]
            ]
        );

        $this->add_control(
            'kng_blog_posts_scrolling_speed',
            [
                'label' => esc_html__('Scrolling Speed (ms)', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'step' => 10,
                'default' => 600,
                'separator' => 'before',
                'condition' => [
                    'kng_blog_posts_autoplay_like_ticker_switcher!' => 'yes',
                ]
            ]
        );

        $this->end_controls_section();
        /** END SECTION: Carousel Settings */


        /** SECTION: Disable Card Items */
        $this->start_controls_section(
            'kng_blog_posts_content_section_disable_card_items',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Disable Card Items', 'king-addons'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'kng_blog_posts_cards_image_switcher',
            [
                'label' => esc_html__('Disable image', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'kng_blog_posts_cards_title_switcher',
            [
                'label' => esc_html__('Disable Title', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
            ]
        );

        $this->add_control(
            'kng_blog_posts_cards_description_switcher',
            [
                'label' => esc_html__('Disable Description', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
            ]
        );

        $this->add_control(
            'kng_blog_posts_cards_whole_text_container_switcher',
            [
                'label' => esc_html__('Disable whole text container', 'king-addons'),
                'description' => esc_html__('Text container includes title and description.', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
            ]
        );

        $this->end_controls_section();
        /** END SECTION: Disable Card Items */

        /** SECTION: Card Layout */
        $this->start_controls_section(
            'kng_blog_posts_content_section_card_layout',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Card Layout', 'king-addons'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'kng_blog_posts_cards_whole_card_title',
            [
                'label' => '<b>' . esc_html__('Whole Card', 'king-addons') . '</b>',
                'type' => Controls_Manager::RAW_HTML,
            ]
        );

        $this->add_responsive_control(
            'kng_blog_posts_cards_whole_card_align_card_full_height',
            [
                'label' => esc_html__('Make card full height', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => '100%',
                'options' => [
                    '100%' => esc_html__('No', 'king-addons'),
                    'auto' => esc_html__('Yes', 'king-addons'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-blog-posts-card' => 'height: {{VALUE}};'
                ],
            ]
        );

        $this->add_responsive_control(
            'kng_blog_posts_cards_whole_card_align_card',
            [
                'label' => esc_html__('Align Whole Card', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'unset' => esc_html__('Default', 'king-addons'),
                    'start' => esc_html__('Start', 'king-addons'),
                    'center' => esc_html__('Center', 'king-addons'),
                    'end' => esc_html__('End', 'king-addons'),
                ],
                'default' => 'unset',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-blog-posts-card' => 'align-self: {{VALUE}};'
                ],
                'condition' => [
                    'kng_blog_posts_cards_whole_card_align_card_full_height' => '100%'
                ],
            ]
        );

        $this->add_responsive_control(
            'kng_blog_posts_cards_whole_card_align_card_content',
            [
                'label' => esc_html__('Align Card Content', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'unset' => esc_html__('Default', 'king-addons'),
                    'start' => esc_html__('Start', 'king-addons'),
                    'center' => esc_html__('Center', 'king-addons'),
                    'end' => esc_html__('End', 'king-addons'),
                ],
                'default' => 'start',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-blog-posts-card .king-addons-blog-posts-card-inner' => 'justify-content: {{VALUE}};'
                ],
                'condition' => [
                    'kng_blog_posts_cards_whole_card_align_card_full_height' => 'auto'
                ],
            ]
        );

        $this->add_control(
            'kng_blog_posts_cards_image_container_title',
            [
                'label' => '<b>' . esc_html__('Image Container', 'king-addons') . '</b>',
                'description' => esc_html__('Image container includes paddings and background color.', 'king-addons'),
                'type' => Controls_Manager::RAW_HTML,
            ]
        );

        $this->add_responsive_control(
            'kng_blog_posts_cards_image_container_position',
            [
                'label' => esc_html__('Position', 'king-addons'),
                'type' => Controls_Manager::CHOOSE,
                'default' => 'column',
                'options' => [
                    'row' => [
                        'title' => esc_html__('Left', 'king-addons'),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'column' => [
                        'title' => esc_html__('Top', 'king-addons'),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'column-reverse' => [
                        'title' => esc_html__('Bottom', 'king-addons'),
                        'icon' => 'eicon-v-align-bottom',
                    ],
                    'row-reverse' => [
                        'title' => esc_html__('Right', 'king-addons'),
                        'icon' => 'eicon-h-align-right',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-blog-posts-card-inner a' => 'flex-flow: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'kng_blog_posts_cards_image_container_height_type',
            [
                'label' => esc_html__('Height', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'auto' => esc_html__('Auto', 'king-addons'),
                    'custom' => esc_html__('Custom (%)', 'king-addons'),
                ],
                'default' => 'auto',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-blog-posts-card-container-1, {{WRAPPER}} .king-addons-blog-posts-image' => 'height: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'kng_blog_posts_cards_image_container_height',
            [
                'label' => esc_html__('Custom height (%)', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 100,
                'step' => 1,
                'default' => 100,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-blog-posts-card-container-1, {{WRAPPER}} .king-addons-blog-posts-image' => 'height: {{SIZE}}%;'
                ],
                'condition' => [
                    'kng_blog_posts_cards_image_container_height_type' => 'custom'
                ],
            ]
        );

        $this->add_responsive_control(
            'kng_blog_posts_cards_image_container_align_content',
            [
                'label' => esc_html__('Align Content', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'unset' => esc_html__('Default', 'king-addons'),
                    'start' => esc_html__('Start', 'king-addons'),
                    'center' => esc_html__('Center', 'king-addons'),
                    'end' => esc_html__('End', 'king-addons'),
                ],
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-blog-posts-card-container-1' => 'justify-content: {{VALUE}}; align-items: {{VALUE}}; align-self: {{VALUE}};',
                    '{{WRAPPER}} .king-addons-blog-posts-image' => 'justify-content: {{VALUE}}; align-items: {{VALUE}}; align-self: {{VALUE}};'
                ],
            ]
        );

        $this->add_control(
            'kng_blog_posts_cards_text_container_title',
            [
                'label' => '<b>' . esc_html__('Text Container', 'king-addons') . '</b>' .
                    '<div class="elementor-control-field-description">' .
                    esc_html__('Text container includes title and description.', 'king-addons') .
                    '</div>',
                'type' => Controls_Manager::RAW_HTML,
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'kng_blog_posts_cards_text_container_width_type',
            [
                'label' => esc_html__('Width', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'auto' => esc_html__('Auto', 'king-addons'),
                    'custom' => esc_html__('Custom (%)', 'king-addons'),
                ],
                'default' => 'custom',
            ]
        );

        $this->add_responsive_control(
            'kng_blog_posts_cards_text_container_width',
            [
                'label' => esc_html__('Custom width (%)', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'step' => 1,
                'max' => 100,
                'default' => 100,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-blog-posts-card-container-2' => 'width: {{SIZE}}%;'
                ],
                'condition' => [
                    'kng_blog_posts_cards_text_container_width_type' => 'custom'
                ],
            ]
        );

        $this->add_responsive_control(
            'kng_blog_posts_cards_text_container_align_content',
            [
                'label' => esc_html__('Align Content', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'unset' => esc_html__('Default', 'king-addons'),
                    'start' => esc_html__('Start', 'king-addons'),
                    'center' => esc_html__('Center', 'king-addons'),
                    'end' => esc_html__('End', 'king-addons'),
                ],
                'default' => 'start',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-blog-posts-card-container-2' => 'justify-content: {{VALUE}}; align-items: {{VALUE}}; align-self: {{VALUE}};'
                ],
            ]
        );

        $this->end_controls_section();
        /** END SECTION: Card Layout ===================== */

        /** SECTION: Responsive Settings */
        $this->start_controls_section(
            'kng_blog_posts_content_section_responsive_settings',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Responsive Settings', 'king-addons'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'kng_blog_posts_desktop_title',
            [
                'label' => '<b>' . esc_html__('Desktop', 'king-addons') . '</b>',
                'type' => Controls_Manager::RAW_HTML,
            ]
        );

        $this->add_control(
            'kng_blog_posts_desktop_cards_per_view',
            [
                'label' => esc_html__('Cards per view', 'king-addons'),
                'description' => esc_html__('The number of slides per view can be a fractional number like 4.5, 5.25, 6.45, etc., to indicate that further slides exist.', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'step' => 0.1,
                'default' => 4.0,
            ]
        );

        $this->add_control(
            'kng_blog_posts_desktop_space_between_cards',
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
            'kng_blog_posts_tablet_title',
            [
                'label' => '<b>' . esc_html__('Tablet', 'king-addons') . '</b>',
                'type' => Controls_Manager::RAW_HTML,
            ]
        );

        $this->add_control(
            'kng_blog_posts_tablet_cards_per_view',
            [
                'label' => esc_html__('Cards per view', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'step' => 0.1,
                'default' => 3.0,
            ]
        );

        $this->add_control(
            'kng_blog_posts_tablet_space_between_cards',
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
            'kng_blog_posts_tablet_breakpoint',
            [
                'label' => esc_html__('Breakpoint (px)', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'step' => 1,
                'default' => 1024,
            ]
        );

        $this->add_control(
            'kng_blog_posts_mobile_title',
            [
                'label' => '<b>' . esc_html__('Mobile', 'king-addons') . '</b>',
                'type' => Controls_Manager::RAW_HTML,
            ]
        );

        $this->add_control(
            'kng_blog_posts_mobile_cards_per_view',
            [
                'label' => esc_html__('Cards per view', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'step' => 0.1,
                'default' => 1.0,
            ]
        );

        $this->add_control(
            'kng_blog_posts_mobile_space_between_cards',
            [
                'label' => esc_html__('Space between cards (px)', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'step' => 1,
                'default' => 30,
            ]
        );

        $this->add_control(
            'kng_blog_posts_mobile_breakpoint',
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
            'kng_blog_posts_content_section_nav',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Navigation', 'king-addons'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'kng_blog_posts_nav_switcher',
            [
                'label' => esc_html__('Enable navigation', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_responsive_control(
            'kng_blog_posts_nav_display',
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
                    '{{WRAPPER}} .king-addons-blog-posts-nav' => 'display: {{VALUE}};'
                ],
                'condition' => [
                    'kng_blog_posts_nav_switcher' => 'yes'
                ]
            ]
        );

        $this->end_controls_section();
        /** END SECTION: Navigation */

        /** START SECTION: Pagination */
        $this->start_controls_section(
            'kng_blog_posts_content_section_pag',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Pagination', 'king-addons'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'kng_blog_posts_pag_switcher',
            [
                'label' => esc_html__('Enable pagination', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'kng_blog_posts_pag_type',
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
                    'kng_blog_posts_pag_switcher' => 'yes'
                ]
            ]
        );

        // position top / bottom
        $this->add_responsive_control(
            'kng_blog_posts_pag_progressbar_position',
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
                    '{{WRAPPER}} .king-addons-blog-posts-pagination' => 'top: {{VALUE}}; bottom: 0;'
                ],
                'condition' => [
                    'kng_blog_posts_pag_type' => 'progressbar',
                ]
            ]
        );

        $this->add_control(
            'kng_blog_posts_pag_clickable_switcher',
            [
                'label' => esc_html__('Clickable Bullets', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'condition' => [
                    'kng_blog_posts_pag_type' => 'bullets',
                ]
            ]
        );

        $this->add_control(
            'kng_blog_posts_pag_dynamic_switcher',
            [
                'label' => esc_html__('Dynamic Bullets', 'king-addons'),
                'description' => esc_html__('Good to enable if you use bullets pagination with a lot of cards. So it will keep only few bullets visible at the same time.', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => '',
                'condition' => [
                    'kng_blog_posts_pag_type' => 'bullets',
                ]
            ]
        );

        $this->add_control(
            'kng_blog_posts_pag_dynamic_number',
            [
                'label' => esc_html__('Number of Dynamic Bullets', 'king-addons'),
                'description' => esc_html__('The number of main bullets visible when Dynamic Bullets enabled.', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'step' => 1,
                'default' => 1,
                'condition' => [
                    'kng_blog_posts_pag_dynamic_switcher' => 'yes',
                    'kng_blog_posts_pag_type' => 'bullets',
                ]
            ]
        );

        $this->add_responsive_control(
            'kng_blog_posts_pag_display',
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
                    '{{WRAPPER}} .king-addons-blog-posts-pagination' => 'display: {{VALUE}};'
                ],
                'condition' => [
                    'kng_blog_posts_pag_switcher' => 'yes'
                ]
            ]
        );

        $this->end_controls_section();
        /** END SECTION: Pagination */

        /** END TAB: CONTENT ===================== */

        /** START TAB: STYLE ===================== */
        $this->start_controls_section(
            'kng_blog_posts_style_section_image',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Image', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'kng_blog_posts_image_size',
            [
                'label' => esc_html__('Image Resolution', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'thumbnail' => esc_html__('Thumbnail', 'king-addons'),
                    'medium' => esc_html__('Medium', 'king-addons'),
                    'large' => esc_html__('Large', 'king-addons'),
                    'full' => esc_html__('Full', 'king-addons'),
                ],
                'default' => 'large',
            ]
        );

        $this->add_responsive_control(
            'kng_blog_posts_image_height_type',
            [
                'label' => esc_html__('Height', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'auto' => esc_html__('Auto', 'king-addons'),
                    'custom-px' => esc_html__('Custom (px)', 'king-addons'),
                    'custom-pct' => esc_html__('Custom (%)', 'king-addons'),
                ],
                'default' => 'custom-px',
            ]
        );

        $this->add_responsive_control(
            'kng_blog_posts_image_width_type',
            [
                'label' => esc_html__('Width', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'auto' => esc_html__('Auto', 'king-addons'),
                    'custom-pct' => esc_html__('Width (%)', 'king-addons'),
                    'custom-px' => esc_html__('Maximum Width (px)', 'king-addons'),
                ],
                'default' => 'auto',
            ]
        );

        $this->add_responsive_control(
            'kng_blog_posts_image_width_pct',
            [
                'label' => esc_html__('Width (%)', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 100,
                'step' => 1,
                'default' => 100,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-blog-posts-image img' => 'width: {{SIZE}}%;'
                ],
                'condition' => [
                    'kng_blog_posts_image_width_type' => 'custom-pct'
                ]
            ]
        );

        $this->add_responsive_control(
            'kng_blog_posts_image_width_px',
            [
                'label' => esc_html__('Maximum Width (px)', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'step' => 1,
                'default' => 200,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-blog-posts-image img' => 'max-width: {{SIZE}}px;'
                ],
                'condition' => [
                    'kng_blog_posts_image_width_type' => 'custom-px'
                ]
            ]
        );

        $this->add_responsive_control(
            'kng_blog_posts_image_height_px',
            [
                'label' => esc_html__('Height (px)', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'step' => 1,
                'default' => 200,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-blog-posts-image img' => 'height: {{SIZE}}px;'
                ],
                'condition' => [
                    'kng_blog_posts_image_height_type' => 'custom-px'
                ]
            ]
        );

        $this->add_responsive_control(
            'kng_blog_posts_image_height_pct',
            [
                'label' => esc_html__('Height (%)', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'step' => 1,
                'default' => 100,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-blog-posts-image img' => 'height: {{SIZE}}%;'
                ],
                'condition' => [
                    'kng_blog_posts_image_height_type' => 'custom-pct'
                ]
            ]
        );

        $this->add_responsive_control(
            'kng_blog_posts_image_fit',
            [
                'label' => esc_html__('Image Fit', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'cover' => esc_html__('Cover', 'king-addons'),
                    'contain' => esc_html__('Contain', 'king-addons'),
                ],
                'default' => 'cover',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-blog-posts-image img' => 'object-fit: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'kng_blog_posts_image_fit_position',
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
                    '{{WRAPPER}} .king-addons-blog-posts-image img' => 'object-position: {{VALUE}};',
                ],
                'condition' => [
                    'kng_blog_posts_image_fit' => 'cover'
                ]
            ]
        );

        $this->add_control(
            'kng_blog_posts_image_bg_color',
            [
                'label' => esc_html__('Background Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-blog-posts-image' => 'background-color: {{VALUE}}',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            Group_Control_Css_Filter::get_type(),
            [
                'label' => esc_html__('CSS Filter', 'king-addons'),
                'name' => 'kng_blog_posts_image_css_filters',
                'selector' => '{{WRAPPER}} .king-addons-blog-posts-image img',
            ]
        );

        $this->add_group_control(
            Group_Control_Css_Filter::get_type(),
            [
                'label' => esc_html__('CSS Filter for image on card hover', 'king-addons'),
                'name' => 'kng_blog_posts_image_css_filters_hover',
                'selector' => '{{WRAPPER}} .king-addons-blog-posts-card:hover .king-addons-blog-posts-image img',
            ]
        );

        $this->add_responsive_control(
            'kng_blog_posts_image_transition',
            [
                'label' => esc_html__('Transition duration on hover (ms)', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'step' => 10,
                'default' => 300,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-blog-posts-image img' => 'transition: all {{SIZE}}ms;'
                ],
            ]
        );

        $this->add_control(
            'kng_blog_posts_image_scale_switcher',
            [
                'label' => esc_html__('Scale image on card hover', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
            ]
        );

        $this->add_responsive_control(
            'kng_blog_posts_image_scale_hover',
            [
                'label' => esc_html__('Scale on hover', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'step' => 0.01,
                'default' => 1.3,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-blog-posts-card:hover .king-addons-blog-posts-image img' => 'transform: scale({{SIZE}});'
                ],
                'condition' => [
                    'kng_blog_posts_image_scale_switcher' => 'yes',
                ]
            ]
        );

        $this->add_responsive_control(
            'kng_blog_posts_image_padding',
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
                    '{{WRAPPER}} .king-addons-blog-posts-image' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->add_responsive_control(
            'kng_blog_posts_image_margin',
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
                    '{{WRAPPER}} .king-addons-blog-posts-image' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'kng_blog_posts_image_box_shadow',
                'selector' => '{{WRAPPER}} .king-addons-blog-posts-image',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'kng_blog_posts_image_border',
                'selector' => '{{WRAPPER}} .king-addons-blog-posts-image',
                'fields_options' => [
                    'border' => [
                        'default' => 'none',
                    ],
                ],
            ]
        );

        $this->add_responsive_control(
            'kng_blog_posts_image_border_radius',
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
                    '{{WRAPPER}} .king-addons-blog-posts-image' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'kng_blog_posts_style_section_title',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Title', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'kng_blog_posts_title_typography',
                'selector' => '{{WRAPPER}} .king-addons-blog-posts-title',
            ]
        );

        $this->add_control(
            'kng_blog_posts_title_txt_color',
            [
                'label' => esc_html__('Text Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-blog-posts-title' => 'color: {{VALUE}}'
                ],
            ]
        );

        $this->add_control(
            'kng_blog_posts_title_txt_color_hover',
            [
                'label' => esc_html__('Text Color on hover', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-blog-posts-title:hover' => 'color: {{VALUE}}'
                ],
            ]
        );

        $this->add_responsive_control(
            'kng_blog_posts_title_txt_color_transition',
            [
                'label' => esc_html__('Transition duration on hover (ms)', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'step' => 10,
                'default' => 300,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-blog-posts-title' => 'transition: color {{SIZE}}ms;'
                ],
            ]
        );

        $this->add_control(
            'kng_blog_posts_title_bg_color',
            [
                'label' => esc_html__('Background Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-blog-posts-title' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'kng_blog_posts_title_txt_align',
            [
                'label' => esc_html__('Text Align', 'king-addons'),
                'type' => Controls_Manager::CHOOSE,
                'default' => 'center',
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
                'selectors' => [
                    '{{WRAPPER}} .king-addons-blog-posts-title' => 'text-align: {{VALUE}};',
                ]
            ]
        );

        $this->add_responsive_control(
            'kng_blog_posts_title_padding',
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
                    '{{WRAPPER}} .king-addons-blog-posts-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->add_responsive_control(
            'kng_blog_posts_title_margin',
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
                    '{{WRAPPER}} .king-addons-blog-posts-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'kng_blog_posts_title_box_shadow',
                'selector' => '{{WRAPPER}} .king-addons-blog-posts-title',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'kng_blog_posts_title_border',
                'selector' => '{{WRAPPER}} .king-addons-blog-posts-title',
                'fields_options' => [
                    'border' => [
                        'default' => 'none',
                    ],
                ],
            ]
        );

        $this->add_responsive_control(
            'kng_blog_posts_title_border_radius',
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
                    '{{WRAPPER}} .king-addons-blog-posts-title' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'kng_blog_posts_style_section_description',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Description', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'kng_blog_posts_description_typography',
                'selector' => '{{WRAPPER}} .king-addons-blog-posts-description, {{WRAPPER}} .king-addons-blog-posts-description:hover',
            ]
        );

        $this->add_control(
            'kng_blog_posts_description_txt_color',
            [
                'label' => esc_html__('Text Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#000000',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-blog-posts-description, {{WRAPPER}} .king-addons-blog-posts-description:hover' => 'color: {{VALUE}}'
                ],
            ]
        );

        $this->add_control(
            'kng_blog_posts_description_bg_color',
            [
                'label' => esc_html__('Background Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-blog-posts-description, {{WRAPPER}} .king-addons-blog-posts-description:hover' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'kng_blog_posts_description_txt_align',
            [
                'label' => esc_html__('Text Align', 'king-addons'),
                'type' => Controls_Manager::CHOOSE,
                'default' => 'center',
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
                'selectors' => [
                    '{{WRAPPER}} .king-addons-blog-posts-description, {{WRAPPER}} .king-addons-blog-posts-description:hover' => 'text-align: {{VALUE}};',
                ]
            ]
        );

        $this->add_responsive_control(
            'kng_blog_posts_description_padding',
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
                    '{{WRAPPER}} .king-addons-blog-posts-description, {{WRAPPER}} .king-addons-blog-posts-description:hover' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->add_responsive_control(
            'kng_blog_posts_description_margin',
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
                    '{{WRAPPER}} .king-addons-blog-posts-description, {{WRAPPER}} .king-addons-blog-posts-description:hover' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'kng_blog_posts_description_box_shadow',
                'selector' => '{{WRAPPER}} .king-addons-blog-posts-description, {{WRAPPER}} .king-addons-blog-posts-description:hover',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'kng_blog_posts_description_border',
                'selector' => '{{WRAPPER}} .king-addons-blog-posts-description, {{WRAPPER}} .king-addons-blog-posts-description:hover',
                'fields_options' => [
                    'border' => [
                        'default' => 'none',
                    ],
                ],
            ]
        );

        $this->add_responsive_control(
            'kng_blog_posts_description_border_radius',
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
                    '{{WRAPPER}} .king-addons-blog-posts-description, {{WRAPPER}} .king-addons-blog-posts-description:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->end_controls_section();

        /** START SECTION: Navigation */
        $this->start_controls_section(
            'kng_blog_posts_style_section_nav',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Navigation', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'kng_blog_posts_nav_icon_prev_title',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Previous button', 'king-addons'),
                'type' => Controls_Manager::HEADING,
            ]
        );

        $this->add_control(
            'kng_blog_posts_nav_icon_prev',
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
            'kng_blog_posts_nav_margin_prev',
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
                    '{{WRAPPER}} .king-addons-blog-posts-nav-prev .king-addons-blog-posts-nav-inner' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );


        $this->add_responsive_control(
            'kng_blog_posts_nav_prev_transform_scale',
            [
                'label' => esc_html__('Scale on hover (%)', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'step' => 0.1,
                'min' => 0,
                'default' => 1.3,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-blog-posts-nav-prev:hover .king-addons-blog-posts-nav-inner' => 'transform: scale({{VALUE}});',
                ],
            ]
        );

        $this->add_responsive_control(
            'kng_blog_posts_nav_position_v_prev',
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
                    '{{WRAPPER}} .king-addons-blog-posts-nav-prev' => 'top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'kng_blog_posts_nav_position_h_prev',
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
                    '{{WRAPPER}} .king-addons-blog-posts-nav-prev' => 'left: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'kng_blog_posts_nav_icon_next_title',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Next button', 'king-addons'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'kng_blog_posts_nav_icon_next',
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
            'kng_blog_posts_nav_margin_next',
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
                    '{{WRAPPER}} .king-addons-blog-posts-nav-next .king-addons-blog-posts-nav-inner' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->add_responsive_control(
            'kng_blog_posts_nav_next_transform_scale',
            [
                'label' => esc_html__('Scale on hover (%)', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'step' => 0.1,
                'min' => 0,
                'default' => 1.3,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-blog-posts-nav-next:hover .king-addons-blog-posts-nav-inner' => 'transform: scale({{VALUE}});',
                ],
            ]
        );

        $this->add_responsive_control(
            'kng_blog_posts_nav_position_v_next',
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
                    '{{WRAPPER}} .king-addons-blog-posts-nav-next' => 'top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'kng_blog_posts_nav_position_h_next',
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
                    '{{WRAPPER}} .king-addons-blog-posts-nav-next' => 'right: calc(100% - {{SIZE}}{{UNIT}});',
                ],
            ]
        );

        // common
        $this->add_control(
            'kng_blog_posts_nav_icon_common_title',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Common settings', 'king-addons'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'kng_blog_posts_nav_icon_size',
            [
                'label' => esc_html__('Icon Size', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'step' => 1,
                'min' => 0,
                'default' => 32,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-blog-posts-nav i' => 'font-size: {{VALUE}}px; width: {{VALUE}}px;',
                    '{{WRAPPER}} .king-addons-blog-posts-nav img' => 'width: {{VALUE}}px; height: {{VALUE}}px;',
                    '{{WRAPPER}} .king-addons-blog-posts-nav svg' => 'width: {{VALUE}}px; height: {{VALUE}}px;'
                ],
            ]
        );

        $this->add_responsive_control(
            'kng_blog_posts_nav_transition_icon',
            [
                'label' => esc_html__('Transition duration for icon on hover (ms)', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'step' => 10,
                'default' => 300,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-blog-posts-nav i' => 'transition: all {{SIZE}}ms;',
                    '{{WRAPPER}} .king-addons-blog-posts-nav img' => 'transition: all {{SIZE}}ms;',
                    '{{WRAPPER}} .king-addons-blog-posts-nav svg' => 'transition: all {{SIZE}}ms;',
                    '{{WRAPPER}} .king-addons-blog-posts-nav .king-addons-blog-posts-nav-inner' => 'transition: all {{SIZE}}ms;',
                ],
            ]
        );

        $this->add_responsive_control(
            'kng_blog_posts_nav_padding',
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
                    '{{WRAPPER}} .king-addons-blog-posts-nav-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->start_controls_tabs('kng_blog_posts_nav_tabs');

        $this->start_controls_tab(
            'kng_blog_posts_nav_tab_normal',
            [
                'label' => esc_html__('Normal', 'king-addons'),
            ]
        );

        $this->add_control(
            'kng_blog_posts_nav_color',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#00000033',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-blog-posts-nav i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .king-addons-blog-posts-nav svg' => 'fill: {{VALUE}};'
                ],
            ]
        );

        $this->add_control(
            'kng_blog_posts_nav_bg_color',
            [
                'label' => esc_html__('Background Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-blog-posts-nav-inner' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'kng_blog_posts_nav_box_shadow',
                'selector' => '{{WRAPPER}} .king-addons-blog-posts-nav-inner',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'kng_blog_posts_nav_border',
                'selector' => '{{WRAPPER}} .king-addons-blog-posts-nav-inner',
                'fields_options' => [
                    'border' => [
                        'default' => 'none',
                    ],
                ],
            ]
        );

        $this->add_responsive_control(
            'kng_blog_posts_nav_border_radius',
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
                    '{{WRAPPER}} .king-addons-blog-posts-nav-inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );
        $this->end_controls_tab();

        $this->start_controls_tab(
            'kng_blog_posts_nav_tab_hover',
            [
                'label' => esc_html__('Hover', 'king-addons'),
            ]
        );

        $this->add_control(
            'kng_blog_posts_nav_color_hover',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-blog-posts-nav i:hover' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .king-addons-blog-posts-nav svg:hover' => 'fill: {{VALUE}};'
                ],
            ]
        );

        $this->add_control(
            'kng_blog_posts_nav_bg_color_hover',
            [
                'label' => esc_html__('Background Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-blog-posts-nav-inner:hover' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'kng_blog_posts_nav_box_shadow_hover',
                'selector' => '{{WRAPPER}} .king-addons-blog-posts-nav-inner:hover',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'kng_blog_posts_nav_border_hover',
                'selector' => '{{WRAPPER}} .king-addons-blog-posts-nav-inner:hover',
            ]
        );

        $this->add_responsive_control(
            'kng_blog_posts_nav_border_radius_hover',
            [
                'label' => esc_html__('Border Radius', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-blog-posts-nav-inner:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();
        $this->end_controls_section();
        /** END SECTION: Navigation */

        /** SECTION: Pagination */
        $this->start_controls_section(
            'kng_blog_posts_style_section_pag',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Pagination', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'kng_blog_posts_pag_color',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#000000',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-blog-posts-pagination' => '--swiper-theme-color: {{VALUE}}; --swiper-pagination-fraction-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'kng_blog_posts_pag_color_second',
            [
                'label' => esc_html__('Second Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#00000033',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-blog-posts-pagination' => '--swiper-pagination-progressbar-bg-color: {{VALUE}}; --swiper-pagination-bullet-inactive-color: {{VALUE}}; --swiper-pagination-bullet-inactive-opacity: 1;',
                ],
            ]
        );

        $this->add_control(
            'kng_blog_posts_pag_bullets_title',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Bullets type', 'king-addons'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'kng_blog_posts_pag_bullets_size',
            [
                'label' => esc_html__('Size', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'step' => 1,
                'default' => 8,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-blog-posts-pagination' => '--swiper-pagination-bullet-size: {{SIZE}}px;'
                ],
            ]
        );

        $this->add_responsive_control(
            'kng_blog_posts_pag_bullets_gap',
            [
                'label' => esc_html__('Bullets gap', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'step' => 1,
                'default' => 4,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-blog-posts-pagination' => '--swiper-pagination-bullet-horizontal-gap: {{SIZE}}px;'
                ],
            ]
        );

        $this->add_control(
            'kng_blog_posts_pag_progressbar_title',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Progress bar type', 'king-addons'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'kng_blog_posts_pag_progressbar_size',
            [
                'label' => esc_html__('Size', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'step' => 1,
                'default' => 4,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-blog-posts-pagination' => '--swiper-pagination-progressbar-size: {{SIZE}}px;'
                ],
            ]
        );

        $this->add_control(
            'kng_blog_posts_pag_fraction_title',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Fraction type', 'king-addons'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'kng_blog_posts_pag_fraction_typography',
                'selector' => '{{WRAPPER}} .king-addons-blog-posts-pagination.swiper-pagination-fraction',
            ]
        );

        $this->end_controls_section();
        /** END SECTION: Pagination */

        /** SECTION: Whole Carousel */
        $this->start_controls_section(
            'kng_blog_posts_style_section_whole_carousel',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Whole Carousel', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'kng_blog_posts_whole_carousel_bg',
                'selector' => '{{WRAPPER}} .king-addons-blog-posts-items'
            ]
        );

        $this->add_responsive_control(
            'kng_blog_posts_whole_carousel_margin',
            [
                'label' => esc_html__('Whole carousel margin', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'default' => [
                    'top' => 0,
                    'right' => 42,
                    'bottom' => 10,
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
                    '{{WRAPPER}} .king-addons-blog-posts-items' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->add_responsive_control(
            'kng_blog_posts_whole_carousel_margin_wrapper',
            [
                'label' => esc_html__('Cards wrapper margin', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'default' => [
                    'top' => 10,
                    'right' => 0,
                    'bottom' => 42,
                    'left' => 0,
                    'unit' => 'px',
                ],
                'tablet_default' => [
                    'top' => 10,
                    'right' => 0,
                    'bottom' => 42,
                    'left' => 0,
                    'unit' => 'px',
                ],
                'mobile_default' => [
                    'top' => 10,
                    'right' => 0,
                    'bottom' => 42,
                    'left' => 0,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-blog-posts-items .swiper-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->add_responsive_control(
            'kng_blog_posts_whole_carousel_padding',
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
                    '{{WRAPPER}} .king-addons-blog-posts-items' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'kng_blog_posts_whole_carousel_box_shadow',
                'selector' => '{{WRAPPER}} .king-addons-blog-posts-items',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'kng_blog_posts_whole_carousel_border',
                'selector' => '{{WRAPPER}} .king-addons-blog-posts-items',
                'fields_options' => [
                    'border' => [
                        'default' => 'none',
                    ],
                ],
            ]
        );

        $this->add_responsive_control(
            'kng_blog_posts_whole_carousel_border_radius',
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
                    '{{WRAPPER}} .king-addons-blog-posts-items' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->end_controls_section();
        /** END SECTION: Whole Carousel */

        $this->start_controls_section(
            'kng_blog_posts_style_section_whole_card',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Whole Card', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'kng_blog_posts_whole_card_transition',
            [
                'label' => esc_html__('Transition duration on card hover (ms)', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'step' => 10,
                'default' => 300,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-blog-posts-card-inner' => 'transition: all {{SIZE}}ms;'
                ],
            ]
        );

        $this->start_controls_tabs('kng_blog_posts_whole_card_tabs');

        $this->start_controls_tab(
            'kng_blog_posts_whole_card_tab_normal',
            [
                'label' => esc_html__('Normal', 'king-addons'),
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'kng_blog_posts_whole_card_bg',
                'selector' => '{{WRAPPER}} .king-addons-blog-posts-card-inner'
            ]
        );

        $this->add_responsive_control(
            'kng_blog_posts_whole_card_padding',
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
                    '{{WRAPPER}} .king-addons-blog-posts-card-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'kng_blog_posts_whole_card_box_shadow',
                'selector' => '{{WRAPPER}} .king-addons-blog-posts-card-inner',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'kng_blog_posts_whole_card_border',
                'selector' => '{{WRAPPER}} .king-addons-blog-posts-card-inner',
                'fields_options' => [
                    'border' => [
                        'default' => 'none',
                    ],
                ],
            ]
        );

        $this->add_responsive_control(
            'kng_blog_posts_whole_card_border_radius',
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
                    '{{WRAPPER}} .king-addons-blog-posts-card-inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'kng_blog_posts_whole_card_tab_hover',
            [
                'label' => esc_html__('Hover', 'king-addons'),
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'kng_blog_posts_whole_card_bg_hover',
                'selector' => '{{WRAPPER}} .king-addons-blog-posts-card-inner:hover'
            ]
        );

        $this->add_responsive_control(
            'kng_blog_posts_whole_card_padding_hover',
            [
                'label' => esc_html__('Padding', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-blog-posts-card-inner:hover' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'kng_blog_posts_whole_card_box_shadow_hover',
                'selector' => '{{WRAPPER}} .king-addons-blog-posts-card-inner:hover',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'kng_blog_posts_whole_card_border_hover',
                'selector' => '{{WRAPPER}} .king-addons-blog-posts-card-inner:hover',
            ]
        );

        $this->add_responsive_control(
            'kng_blog_posts_whole_card_border_radius_hover',
            [
                'label' => esc_html__('Border Radius', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-blog-posts-card-inner:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();

        $this->start_controls_section(
            'kng_blog_posts_style_section_whole_text_container',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Whole Text Container', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'kng_blog_posts_style_section_whole_text_container_title',
            [
                'label' => '<span class="elementor-control-field-description">' .
                    esc_html__('Text container includes title and description.', 'king-addons') .
                    '</span>',
                'type' => Controls_Manager::RAW_HTML,
            ]
        );

        $this->add_responsive_control(
            'kng_blog_posts_whole_text_container_bg_color',
            [
                'label' => esc_html__('Background Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-blog-posts-card-container-2' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'kng_blog_posts_whole_text_container_padding',
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
                    '{{WRAPPER}} .king-addons-blog-posts-card-container-2' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->add_responsive_control(
            'kng_blog_posts_whole_text_container_margin',
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
                    '{{WRAPPER}} .king-addons-blog-posts-card-container-2' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'kng_blog_posts_whole_text_container_box_shadow',
                'selector' => '{{WRAPPER}} .king-addons-blog-posts-card-container-2',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'kng_blog_posts_whole_text_container_border',
                'selector' => '{{WRAPPER}} .king-addons-blog-posts-card-container-2',
                'fields_options' => [
                    'border' => [
                        'default' => 'none',
                    ],
                ],
            ]
        );

        $this->add_responsive_control(
            'kng_blog_posts_whole_text_container_border_radius',
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
                    '{{WRAPPER}} .king-addons-blog-posts-card-container-2' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->end_controls_section();

        /** END TAB: STYLE ===================== */
    }

    protected function render(): void
    {
        $settings = $this->get_settings();
        $this_ID = $this->get_id();

        $query_args = [
            'posts_per_page' => $settings['kng_blog_posts_number_of_posts'],
            'post_type' => 'post',
            'post_status' => 'publish',
            'ignore_sticky_posts' => 'yes' === $settings['kng_blog_posts_ignore_sticky_posts_switcher'],
        ];

        $query = new WP_Query($query_args);

        /** START: BLOG POSTS===================== */
        ?>
        <div class="king-addons-blog-posts-items-wrap">
            <div class="king-addons-blog-posts-items king-addons-blog-posts-items-<?php echo esc_attr($this_ID); ?> swiper-container swiper">
                <div class="swiper-wrapper<?php if ('yes' === $settings['kng_blog_posts_autoplay_like_ticker_switcher']) {
                    echo ' king-addons-blog-posts-autoplay-like-ticker';
                } ?>">
                    <?php
                    /** Blog Item */

                    if ($query->have_posts()) {

                        while ($query->have_posts()) {

                            $query->the_post();

                            global $post;

                            $excerpt = $post->post_excerpt ? wp_trim_words($post->post_excerpt, $settings['kng_blog_posts_number_of_words'], '...') : wp_trim_words($post->post_content, $settings['kng_blog_posts_number_of_words'], '...');

                            /** open card div  */
                            echo '<div class="swiper-slide king-addons-blog-posts-card king-addons-blog-posts-cursor-pointer">';

                            /** open inner div  */
                            echo '<div class="king-addons-blog-posts-card-inner">';
                            echo '<a href="' . esc_url(get_permalink($post->ID)) . '">';

                            // CONTAINER 1
                            if ('yes' !== $settings['kng_blog_posts_cards_image_switcher']) {
                                if (has_post_thumbnail($post->ID)) {
                                    echo '<div class="king-addons-blog-posts-card-container-1">';

                                    // image
                                    echo '<div class="king-addons-blog-posts-image">';
                                    echo '<img src="' . esc_url(get_the_post_thumbnail_url($post->ID, $settings['kng_blog_posts_image_size'])) . '" alt="' . esc_attr($post->post_title) . '">';
                                    echo '</div>';

                                    echo '</div>';
                                }
                            }
                            // END CONTAINER 1

                            // CONTAINER 2
                            if ('yes' !== $settings['kng_blog_posts_cards_whole_text_container_switcher']) {
                                echo '<div class="king-addons-blog-posts-card-container-2">';

                                // post title
                                if ('yes' !== $settings['kng_blog_posts_cards_title_switcher']) {
                                    echo '<div class="king-addons-blog-posts-title">';
                                    echo esc_html($post->post_title);
                                    echo '</div>';
                                }

                                // post description
                                if ('yes' !== $settings['kng_blog_posts_cards_description_switcher']) {
                                    echo '<div class="king-addons-blog-posts-description">';
                                    echo esc_html($excerpt);
                                    echo '</div>';
                                }
                                echo '</div>';
                            }
                            // END CONTAINER 2

                            echo '</a>';
                            /** close card inner div */
                            echo '</div>';

                            /** close card div  */
                            echo '</div>';
                        }
                        wp_reset_postdata();
                    }
                    /** END: Blog Item */
                    ?>
                </div>
            </div>
            <?php

            // pagination
            if ('yes' === $settings['kng_blog_posts_pag_switcher']) {
                echo '<div class="swiper-pagination king-addons-blog-posts-pagination king-addons-blog-posts-pagination-' .
                    esc_attr($this_ID) .
                    (('yes' === $settings['kng_blog_posts_autoplay_like_ticker_switcher']) ? ' king-addons-blog-posts-autoplay-like-ticker-pag' : '') .
                    '"></div>';
            }

            // navigation
            if ('yes' === $settings['kng_blog_posts_nav_switcher']) {

                echo '<div class="king-addons-blog-posts-nav-prev king-addons-blog-posts-nav-prev-' . esc_attr($this_ID) . ' king-addons-blog-posts-nav">';
                echo '<div class="king-addons-blog-posts-nav-inner">';
                Icons_Manager::render_icon($settings['kng_blog_posts_nav_icon_prev']);
                echo '</div>';
                echo '</div>';

                echo '<div class="king-addons-blog-posts-nav-next king-addons-blog-posts-nav-next-' . esc_attr($this_ID) . ' king-addons-blog-posts-nav">';
                echo '<div class="king-addons-blog-posts-nav-inner">';
                Icons_Manager::render_icon($settings['kng_blog_posts_nav_icon_next']);
                echo '</div>';
                echo '</div>';

            }
            ?>
        </div>
        <?php

        $js_swiper_check_if_DOM_already_loaded = "document.readyState === 'complete'";
        $js_swiper_check_if_DOM_not_already_loaded = "window.addEventListener('load', function () {";

        $js_swiper = "new Swiper('.king-addons-blog-posts-items-" . esc_js($this_ID) . "', {";
        $js_swiper .= "direction: 'horizontal',";

        $js_swiper .= "slidesPerView: " . esc_js($settings['kng_blog_posts_desktop_cards_per_view']) . ",";
        $js_swiper .= "spaceBetween: " . esc_js($settings['kng_blog_posts_desktop_space_between_cards']) . ",";

        // Responsive breakpoints
        $js_swiper .= 'breakpoints: {0: {slidesPerView: ' .
            esc_js($settings['kng_blog_posts_mobile_cards_per_view']) . ', spaceBetween: ' .
            esc_js($settings['kng_blog_posts_mobile_space_between_cards']) . '}, ';
        $js_swiper .=
            esc_js(($settings['kng_blog_posts_mobile_breakpoint'] + 1)) . ': {slidesPerView: ' .
            esc_js($settings['kng_blog_posts_tablet_cards_per_view']) . ', spaceBetween: ' .
            esc_js($settings['kng_blog_posts_tablet_space_between_cards']) . '}, ';
        $js_swiper .=
            esc_js(($settings['kng_blog_posts_tablet_breakpoint'] + 1)) . ': {slidesPerView: ' .
            esc_js($settings['kng_blog_posts_desktop_cards_per_view']) . ', spaceBetween: ' .
            esc_js($settings['kng_blog_posts_desktop_space_between_cards']) . '}},';

        // Scrolling speed
        if ('yes' !== $settings['kng_blog_posts_autoplay_like_ticker_switcher']) {
            $js_swiper .= "speed: " . esc_js($settings['kng_blog_posts_scrolling_speed']) . ",";
        } else {
            $js_swiper .= "speed: " . esc_js($settings['kng_blog_posts_autoplay_like_ticker_autoplay_speed']) . ",";
        }

        // Pagination
        if ('yes' === $settings['kng_blog_posts_pag_switcher']) {
            $js_swiper .= "pagination: {el: '.king-addons-blog-posts-pagination-" .
                esc_js($this_ID) . "', " .
                ('yes' === $settings['kng_blog_posts_pag_clickable_switcher'] ? 'clickable: true, ' : '');
            if ('yes' === $settings['kng_blog_posts_pag_dynamic_switcher']) {
                $js_swiper .= 'dynamicBullets: true, ';
                $js_swiper .= 'dynamicMainBullets: ' . esc_js($settings['kng_blog_posts_pag_dynamic_number'] . ', ');
            }
            $js_swiper .= "type: '" . esc_js($settings['kng_blog_posts_pag_type']) . "'},";
        }

        // Navigation
        if ('yes' === $settings['kng_blog_posts_nav_switcher']) {
            $js_swiper .= "navigation: {nextEl: '.king-addons-blog-posts-nav-next-" .
                esc_js($this_ID) . "', prevEl: '.king-addons-blog-posts-nav-prev-" .
                esc_js($this_ID) . "'},";
        }

        // Loop
        if ('yes' === $settings['kng_blog_posts_loop_switcher']) {
            $js_swiper .= "loop: true,";
        }

        // Rewind
        if ('yes' === $settings['kng_blog_posts_rewind_switcher']) {
            $js_swiper .= "rewind: true,";
        }

        // Autoplay
        if ('yes' === $settings['kng_blog_posts_autoplay_switcher']) {
            $js_swiper .= "autoplay: {";
            if ('yes' !== $settings['kng_blog_posts_autoplay_like_ticker_switcher']) {
                $js_swiper .= "delay: " . esc_js($settings['kng_blog_posts_autoplay_delay']) . ",";
            } else {
                $js_swiper .= "delay: 0,";
            }
            $js_swiper .= "disableOnInteraction: " . ('yes' === $settings['kng_blog_posts_autoplay_disableoninteraction_switcher'] ? 'true' : 'false') . ",";
            $js_swiper .= "pauseOnMouseEnter: " . ('yes' === $settings['kng_blog_posts_autoplay_pausedonmouseenter_switcher'] ? 'true' : 'false') . ",";
            $js_swiper .= "reverseDirection: " . ('yes' === $settings['kng_blog_posts_autoplay_reversedirection_switcher'] ? 'true' : 'false') . ",";
            $js_swiper .= "},";
        }

        $js_swiper .= "})";

        $js_swiper_full = $js_swiper_check_if_DOM_already_loaded . ' ?' . $js_swiper . ' : ' . $js_swiper_check_if_DOM_not_already_loaded . $js_swiper . '; });';

        wp_print_inline_script_tag($js_swiper_full);
        /** END: BLOG POSTS ===================== */
    }
}