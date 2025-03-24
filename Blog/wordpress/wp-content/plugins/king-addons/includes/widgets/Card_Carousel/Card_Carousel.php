<?php /** @noinspection SpellCheckingInspection, DuplicatedCode, PhpUnused */

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

class Card_Carousel extends Widget_Base
{
    public function get_name(): string
    {
        return 'king-addons-card-carousel';
    }

    public function get_title(): string
    {
        return esc_html__('Card Carousel', 'king-addons');
    }

    public function get_icon(): string
    {
        return 'king-addons-icon king-addons-card-carousel';
    }

    public function get_script_depends(): array
    {
        return [KING_ADDONS_ASSETS_UNIQUE_KEY . '-swiper-swiper'];
    }

    public function get_style_depends(): array
    {
        return [KING_ADDONS_ASSETS_UNIQUE_KEY . '-swiper-swiper', KING_ADDONS_ASSETS_UNIQUE_KEY . '-card-carousel-style'];
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
            'mouseover', 'page', 'card carousel', 'kingaddons', 'king-addons', 'team', 'members', 'testimonial',
            'testimonials', 'reviews', ' team memebers', 'drag', 'scroll', 'scrolling', 'tabs', 'tab'];
    }

    public function get_custom_help_url(): string
    {
        return 'https://kingaddons.com/elementor/card-carousel/';
    }

    protected function register_controls(): void
    {
        /** START TAB: CONTENT ===================== */
        /** SECTION: Carousel Settings */
        $this->start_controls_section(
            'kng_card_carousel_content_section_caousel_settings',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Carousel Settings', 'king-addons'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'kng_card_carousel_loop_switcher',
            [
                'label' => esc_html__('Loop Cards', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes'
            ]
        );

        $this->add_control(
            'kng_card_carousel_rewind_switcher',
            [
                'label' => esc_html__('Rewind Cards', 'king-addons'),
                'description' => esc_html__('When enabled, clicking the next navigation button when on last slide will slide back to the first slide. Clicking the prev navigation button when on first slide will slide forward to the last slide. Should not be used together with loop mode.', 'king-addons'),
                'type' => Controls_Manager::SWITCHER
            ]
        );

        $this->add_control(
            'kng_card_carousel_autoplay_switcher',
            [
                'label' => esc_html__('Autoplay Carousel Cards', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'kng_card_carousel_autoplay_delay',
            [
                'label' => esc_html__('Delay between transitions (ms)', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'step' => 10,
                'default' => 2000,
                'condition' => [
                    'kng_card_carousel_autoplay_switcher!' => '',
                    'kng_card_carousel_autoplay_like_ticker_switcher!' => 'yes',
                ]
            ]
        );

        $this->add_control(
            'kng_card_carousel_autoplay_disableoninteraction_switcher',
            [
                'label' => esc_html__('Disable on interaction', 'king-addons'),
                'description' => esc_html__('Autoplay will be disabled after user interactions (swipes), it will not be restarted every time after interaction.', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'condition' => [
                    'kng_card_carousel_autoplay_switcher!' => '',
                ]
            ]
        );

        $this->add_control(
            'kng_card_carousel_autoplay_pausedonmouseenter_switcher',
            [
                'label' => esc_html__('Pause on mouse enter', 'king-addons'),
                'description' => esc_html__('When enabled autoplay will be paused on pointer (mouse) enter over carousel container.', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'condition' => [
                    'kng_card_carousel_autoplay_switcher!' => '',
                ]
            ]
        );

        $this->add_control(
            'kng_card_carousel_autoplay_reversedirection_switcher',
            [
                'label' => esc_html__('Reverse Direction', 'king-addons'),
                'description' => esc_html__('Enables autoplay in reverse direction.', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'condition' => [
                    'kng_card_carousel_autoplay_switcher!' => '',
                ]
            ]
        );

        $this->add_control(
            'kng_card_carousel_autoplay_like_ticker_switcher',
            [
                'label' => esc_html__('Autoplay Like Ticker (marquee effect)', 'king-addons'),
                'description' => esc_html__('Autoscrolls cards as on ticker, also known as marquee effect.', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'condition' => [
                    'kng_card_carousel_autoplay_switcher!' => '',
                ],
            ]
        );

        $this->add_control(
            'kng_card_carousel_autoplay_like_ticker_autoplay_speed',
            [
                'label' => esc_html__('Autoplay Speed (ms)', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'step' => 10,
                'default' => 6000,
                'condition' => [
                    'kng_card_carousel_autoplay_like_ticker_switcher' => 'yes',
                ]
            ]
        );

        $this->add_control(
            'kng_card_carousel_scrolling_speed',
            [
                'label' => esc_html__('Scrolling Speed (ms)', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'step' => 10,
                'default' => 600,
                'separator' => 'before',
                'condition' => [
                    'kng_card_carousel_autoplay_like_ticker_switcher!' => 'yes',
                ]
            ]
        );

        $this->end_controls_section();
        /** END SECTION: Carousel Settings */

        /** SECTION: Cards ===================== */
        $this->start_controls_section(
            'kng_card_carousel_content_section_cards',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Cards', 'king-addons'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'kng_card_carousel_image',
            [
                'label' => '<b>' . esc_html__('Image', 'king-addons') . '</b>',
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ]
            ]
        );

        $repeater->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name' => 'kng_card_carousel_image_size',
                'default' => 'full',
            ]
        );

        $repeater->add_control(
            'kng_card_carousel_image_link',
            [
                'label' => esc_html__('Image Link', 'king-addons'),
                'type' => Controls_Manager::URL,
            ]
        );

        $repeater->add_control(
            'kng_card_carousel_imagedescription_title',
            [
                'label' => '<b>' . esc_html__('Image Description', 'king-addons') . '</b>',
                'type' => Controls_Manager::RAW_HTML,
                'separator' => 'before'
            ]
        );

        $repeater->add_control(
            'kng_card_carousel_imagedescription',
            [
                'label' => esc_html__('Image Description', 'king-addons'),
                'description' => esc_html__('The text below the image.', 'king-addons'),
                'type' => Controls_Manager::TEXT,
            ]
        );

        $repeater->add_control(
            'kng_card_carousel_imagedescription_custom_color',
            [
                'label' => esc_html__('Text Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .king-addons-card-carousel-imagedescription' => 'color: {{VALUE}}'
                ],
                'condition' => [
                    'kng_card_carousel_imagedescription_custom_styles' => 'yes',
                ]
            ]
        );

        $repeater->add_control(
            'kng_card_carousel_whole_card_title',
            [
                'label' => '<b>' . esc_html__('Whole Card', 'king-addons') . '</b>',
                'type' => Controls_Manager::RAW_HTML,
                'separator' => 'before'
            ]
        );

        $repeater->add_control(
            'kng_card_carousel_whole_card_link',
            [
                'label' => esc_html__('Whole card link', 'king-addons'),
                'type' => Controls_Manager::URL,
            ]
        );

        $repeater->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'kng_card_carousel_card_bg_custom',
                'selector' => '{{WRAPPER}} {{CURRENT_ITEM}} .king-addons-card-carousel-card-inner',
            ]
        );

        $repeater->add_control(
            'kng_card_carousel_title',
            [
                'label' => '<b>' . esc_html__('Title', 'king-addons') . '</b>',
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
                'default' => esc_html__('Title', 'king-addons'),
                'separator' => 'before'
            ]
        );

        $repeater->add_control(
            'kng_card_carousel_subtitle',
            [
                'label' => '<b>' . esc_html__('Subtitle', 'king-addons') . '</b>',
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
                'default' => esc_html__('Subtitle', 'king-addons'),
                'separator' => 'before'
            ]
        );

        // Button
        $repeater->add_control(
            'kkng_card_carousel_btn_title',
            [
                'label' => '<b>' . esc_html__('Button', 'king-addons') . '</b>',
                'type' => Controls_Manager::RAW_HTML,
                'separator' => 'before'
            ]
        );

        $repeater->add_control(
            'kng_card_carousel_btn_text',
            [
                'label' => esc_html__('Button Text', 'king-addons'),
                'type' => Controls_Manager::TEXT,
                'default' => '',
            ]
        );

        $repeater->add_control(
            'kng_card_carousel_btn_link',
            [
                'label' => esc_html__('Link', 'king-addons'),
                'type' => Controls_Manager::URL,
            ]
        );

        $repeater->add_control(
            'kng_card_carousel_description_title',
            [
                'label' => '<b>' . esc_html__('Description', 'king-addons') . '</b>',
                'type' => Controls_Manager::RAW_HTML,
                'separator' => 'before',
            ]
        );

        $repeater->add_control(
            'kng_card_carousel_description',
            [
                'label' => '<b>' . esc_html__('Description', 'king-addons') . '</b>',
                'type' => Controls_Manager::WYSIWYG,
                'show_label' => false,
                'default' => esc_html__('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'king-addons'),
                'placeholder' => esc_html__('Type the description here', 'king-addons'),
            ]
        );

        $default_cards = [
            'kng_card_carousel_image' => Utils::get_placeholder_image_src(),
            'kng_card_carousel_title' => esc_html__('Title', 'king-addons'),
            'kng_card_carousel_subtitle' => esc_html__('Subtitle', 'king-addons'),
            'kng_card_carousel_description' => esc_html__('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'king-addons'),
        ];

        $this->add_control(
            'kng_card_carousel_content_items',
            [
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => array_fill(0, 5, $default_cards),
                'title_field' => '{{kng_card_carousel_title}}',
            ]
        );

        $this->end_controls_section();
        /** END SECTION: Cards ===================== */

        /** SECTION: Disable Card Items */
        $this->start_controls_section(
            'kng_card_carousel_content_section_disable_card_items',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Disable Card Items', 'king-addons'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'kng_card_carousel_cards_image_switcher',
            [
                'label' => esc_html__('Disable Image', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'kng_card_carousel_cards_imagedescription_switcher',
            [
                'label' => esc_html__('Disable Image Description', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
            ]
        );

        $this->add_control(
            'kng_card_carousel_cards_title_switcher',
            [
                'label' => esc_html__('Disable Title', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
            ]
        );

        $this->add_control(
            'kng_card_carousel_cards_subtitle_switcher',
            [
                'label' => esc_html__('Disable Subtitle', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
            ]
        );

        $this->add_control(
            'kng_card_carousel_cards_description_switcher',
            [
                'label' => esc_html__('Disable Description', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
            ]
        );

        $this->add_control(
            'kng_card_carousel_cards_whole_image_container_switcher',
            [
                'label' => esc_html__('Disable whole image container', 'king-addons'),
                'description' => esc_html__('Image container includes image and image description.', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'kng_card_carousel_cards_whole_text_container_switcher',
            [
                'label' => esc_html__('Disable whole text container', 'king-addons'),
                'description' => esc_html__('Text container includes title, subtitle, description and button.', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
            ]
        );

        $this->end_controls_section();
        /** END SECTION: Disable Card Items */

        /** SECTION: Card Layout */
        $this->start_controls_section(
            'kng_card_carousel_content_section_card_layout',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Card Layout', 'king-addons'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'kng_card_carousel_cards_whole_card_title',
            [
                'label' => '<b>' . esc_html__('Whole Card', 'king-addons') . '</b>',
                'type' => Controls_Manager::RAW_HTML,
            ]
        );

        $this->add_responsive_control(
            'kng_card_carousel_cards_whole_card_align_card_full_height',
            [
                'label' => esc_html__('Make card full height', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => '100%',
                'options' => [
                    '100%' => esc_html__('No', 'king-addons'),
                    'auto' => esc_html__('Yes', 'king-addons'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-card-carousel-card' => 'height: {{VALUE}};'
                ],
            ]
        );

        $this->add_responsive_control(
            'kng_card_carousel_cards_whole_card_align_card',
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
                    '{{WRAPPER}} .king-addons-card-carousel-card' => 'align-self: {{VALUE}};'
                ],
                'condition' => [
                    'kng_card_carousel_cards_whole_card_align_card_full_height' => '100%'
                ],
            ]
        );

        $this->add_responsive_control(
            'kng_card_carousel_cards_whole_card_align_card_content',
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
                    '{{WRAPPER}} .king-addons-card-carousel-card .king-addons-card-carousel-card-inner' => 'justify-content: {{VALUE}};'
                ],
                'condition' => [
                    'kng_card_carousel_cards_whole_card_align_card_full_height' => 'auto'
                ],
            ]
        );

        $this->add_control(
            'kng_card_carousel_cards_image_container_title',
            [
                'label' => '<b>' . esc_html__('Image Container', 'king-addons') . '</b>' .
                    '<div class="elementor-control-field-description">' .
                    esc_html__('Image container includes image and image description.', 'king-addons') .
                    '</div>',
                'type' => Controls_Manager::RAW_HTML,
            ]
        );

        $this->add_responsive_control(
            'kng_card_carousel_cards_image_container_position',
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
                    '{{WRAPPER}} .king-addons-card-carousel-card-inner' => 'flex-flow: {{VALUE}};'
                ],
            ]
        );

        $this->add_responsive_control(
            'kng_card_carousel_cards_image_container_width_type',
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
            'kng_card_carousel_cards_image_container_width',
            [
                'label' => esc_html__('Custom width (%)', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'step' => 1,
                'max' => 100,
                'default' => 100,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-card-carousel-card-container-1' => 'width: {{SIZE}}%;'
                ],
                'condition' => [
                    'kng_card_carousel_cards_image_container_width_type' => 'custom'
                ],
            ]
        );

        $this->add_responsive_control(
            'kng_card_carousel_cards_image_container_height_type',
            [
                'label' => esc_html__('Height', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'auto' => esc_html__('Auto', 'king-addons'),
                    'custom' => esc_html__('Custom (%)', 'king-addons'),
                ],
                'default' => 'auto',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-card-carousel-card-container-1, {{WRAPPER}} .king-addons-card-carousel-image' => 'height: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'kng_card_carousel_cards_image_container_height',
            [
                'label' => esc_html__('Custom height (%)', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'step' => 1,
                'max' => 100,
                'default' => 100,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-card-carousel-card-container-1, {{WRAPPER}} .king-addons-card-carousel-image' => 'height: {{SIZE}}%;'
                ],
                'condition' => [
                    'kng_card_carousel_cards_image_container_height_type' => 'custom'
                ],
            ]
        );

        $this->add_responsive_control(
            'kng_card_carousel_cards_image_container_align_content',
            [
                'label' => esc_html__('Align Content', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'unset' => esc_html__('Default', 'king-addons'),
                    'start' => esc_html__('Start', 'king-addons'),
                    'center' => esc_html__('Center', 'king-addons'),
                    'end' => esc_html__('End', 'king-addons'),
                ],
                'default' => 'unset',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-card-carousel-card-container-1' => 'justify-content: {{VALUE}}; align-items: {{VALUE}}; align-self: {{VALUE}};'
                ],
            ]
        );

        $this->add_control(
            'kng_card_carousel_cards_text_container_title',
            [
                'label' => '<b>' . esc_html__('Text Container', 'king-addons') . '</b>' .
                    '<div class="elementor-control-field-description">' .
                    esc_html__('Text container includes title, subtitle, description and button.', 'king-addons') .
                    '</div>',
                'type' => Controls_Manager::RAW_HTML,
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'kng_card_carousel_cards_text_container_width_type',
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
            'kng_card_carousel_cards_text_container_width',
            [
                'label' => esc_html__('Custom width (%)', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'step' => 1,
                'max' => 100,
                'default' => 100,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-card-carousel-card-container-2' => 'width: {{SIZE}}%;'
                ],
                'condition' => [
                    'kng_card_carousel_cards_text_container_width_type' => 'custom'
                ],
            ]
        );

        $this->add_responsive_control(
            'kng_card_carousel_cards_text_container_align_content',
            [
                'label' => esc_html__('Align Content', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'unset' => esc_html__('Default', 'king-addons'),
                    'start' => esc_html__('Start', 'king-addons'),
                    'center' => esc_html__('Center', 'king-addons'),
                    'end' => esc_html__('End', 'king-addons'),
                ],
                'default' => 'unset',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-card-carousel-card-container-2' => 'justify-content: {{VALUE}}; align-items: {{VALUE}}; align-self: {{VALUE}};'
                ],
            ]
        );

        $this->end_controls_section();
        /** END SECTION: Card Layout ===================== */

        /** SECTION: Responsive Settings */
        $this->start_controls_section(
            'kng_card_carousel_content_section_responsive_settings',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Responsive Settings', 'king-addons'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'kng_card_carousel_desktop_title',
            [
                'label' => '<b>' . esc_html__('Desktop', 'king-addons') . '</b>',
                'type' => Controls_Manager::RAW_HTML,
            ]
        );

        $this->add_control(
            'kng_card_carousel_desktop_cards_per_view',
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
            'kng_card_carousel_desktop_space_between_cards',
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
            'kng_card_carousel_tablet_title',
            [
                'label' => '<b>' . esc_html__('Tablet', 'king-addons') . '</b>',
                'type' => Controls_Manager::RAW_HTML,
            ]
        );

        $this->add_control(
            'kng_card_carousel_tablet_cards_per_view',
            [
                'label' => esc_html__('Cards per view', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'step' => 0.1,
                'default' => 3.0,
            ]
        );

        $this->add_control(
            'kng_card_carousel_tablet_space_between_cards',
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
            'kng_card_carousel_tablet_breakpoint',
            [
                'label' => esc_html__('Breakpoint (px)', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'step' => 1,
                'default' => 1024,
            ]
        );

        $this->add_control(
            'kng_card_carousel_mobile_title',
            [
                'label' => '<b>' . esc_html__('Mobile', 'king-addons') . '</b>',
                'type' => Controls_Manager::RAW_HTML,
            ]
        );

        $this->add_control(
            'kng_card_carousel_mobile_cards_per_view',
            [
                'label' => esc_html__('Cards per view', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'step' => 0.1,
                'default' => 1.0,
            ]
        );

        $this->add_control(
            'kng_card_carousel_mobile_space_between_cards',
            [
                'label' => esc_html__('Space between cards (px)', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'step' => 1,
                'default' => 30,
            ]
        );

        $this->add_control(
            'kng_card_carousel_mobile_breakpoint',
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
            'kng_card_carousel_content_section_nav',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Navigation', 'king-addons'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'kng_card_carousel_nav_switcher',
            [
                'label' => esc_html__('Enable navigation', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_responsive_control(
            'kng_card_carousel_nav_display',
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
                    '{{WRAPPER}} .king-addons-card-carousel-nav' => 'display: {{VALUE}};'
                ],
                'condition' => [
                    'kng_card_carousel_nav_switcher' => 'yes'
                ]
            ]
        );

        $this->end_controls_section();
        /** END SECTION: Navigation */

        /** START SECTION: Pagination */
        $this->start_controls_section(
            'kng_card_carousel_content_section_pag',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Pagination', 'king-addons'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'kng_card_carousel_pag_switcher',
            [
                'label' => esc_html__('Enable pagination', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'kng_card_carousel_pag_type',
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
                    'kng_card_carousel_pag_switcher' => 'yes'
                ]
            ]
        );

        // position top / bottom
        $this->add_responsive_control(
            'kng_card_carousel_pag_progressbar_position',
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
                    '{{WRAPPER}} .king-addons-card-carousel-pagination' => 'top: {{VALUE}}; bottom: 0;'
                ],
                'condition' => [
                    'kng_card_carousel_pag_type' => 'progressbar',
                ]
            ]
        );

        $this->add_control(
            'kng_card_carousel_pag_clickable_switcher',
            [
                'label' => esc_html__('Clickable Bullets', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'condition' => [
                    'kng_card_carousel_pag_type' => 'bullets',
                ]
            ]
        );

        $this->add_control(
            'kng_card_carousel_pag_dynamic_switcher',
            [
                'label' => esc_html__('Dynamic Bullets', 'king-addons'),
                'description' => esc_html__('Good to enable if you use bullets pagination with a lot of cards. So it will keep only few bullets visible at the same time.', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => '',
                'condition' => [
                    'kng_card_carousel_pag_type' => 'bullets',
                ]
            ]
        );

        $this->add_control(
            'kng_card_carousel_pag_dynamic_number',
            [
                'label' => esc_html__('Number of Dynamic Bullets', 'king-addons'),
                'description' => esc_html__('The number of main bullets visible when Dynamic Bullets enabled.', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'step' => 1,
                'default' => 1,
                'condition' => [
                    'kng_card_carousel_pag_dynamic_switcher' => 'yes',
                    'kng_card_carousel_pag_type' => 'bullets',
                ]
            ]
        );

        $this->add_responsive_control(
            'kng_card_carousel_pag_display',
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
                    '{{WRAPPER}} .king-addons-card-carousel-pagination' => 'display: {{VALUE}};'
                ],
                'condition' => [
                    'kng_card_carousel_pag_switcher' => 'yes'
                ]
            ]
        );

        $this->end_controls_section();
        /** END SECTION: Pagination */

        /** END TAB: CONTENT ===================== */

        /** START TAB: STYLE ===================== */
        $this->start_controls_section(
            'kng_card_carousel_style_section_image',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Image', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'kng_card_carousel_image_height_type',
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
            'kng_card_carousel_image_width_type',
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
            'kng_card_carousel_image_width_px',
            [
                'label' => esc_html__('Maximum Width (px)', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'step' => 1,
                'default' => 200,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-card-carousel-image img' => 'max-width: {{SIZE}}px;'
                ],
                'condition' => [
                    'kng_card_carousel_image_width_type' => 'custom-px'
                ]
            ]
        );

        $this->add_responsive_control(
            'kng_card_carousel_image_width_pct',
            [
                'label' => esc_html__('Width (%)', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 100,
                'step' => 1,
                'default' => 100,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-card-carousel-image img' => 'width: {{SIZE}}%;'
                ],
                'condition' => [
                    'kng_card_carousel_image_width_type' => 'custom-pct'
                ]
            ]
        );

        $this->add_responsive_control(
            'kng_card_carousel_image_height_px',
            [
                'label' => esc_html__('Height (px)', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'step' => 1,
                'default' => 200,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-card-carousel-image img' => 'height: {{SIZE}}px;'
                ],
                'condition' => [
                    'kng_card_carousel_image_height_type' => 'custom-px'
                ]
            ]
        );

        $this->add_responsive_control(
            'kng_card_carousel_image_height_pct',
            [
                'label' => esc_html__('Height (%)', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'step' => 1,
                'default' => 100,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-card-carousel-image img' => 'height: {{SIZE}}%;'
                ],
                'condition' => [
                    'kng_card_carousel_image_height_type' => 'custom-pct'
                ]
            ]
        );

        $this->add_responsive_control(
            'kng_card_carousel_image_fit',
            [
                'label' => esc_html__('Image Fit', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'cover' => esc_html__('Cover', 'king-addons'),
                    'contain' => esc_html__('Contain', 'king-addons'),
                ],
                'default' => 'cover',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-card-carousel-image img' => 'object-fit: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'kng_card_carousel_image_fit_position',
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
                    '{{WRAPPER}} .king-addons-card-carousel-image img' => 'object-position: {{VALUE}};',
                ],
                'condition' => [
                    'kng_card_carousel_image_fit' => 'cover'
                ]
            ]
        );

        $this->add_control(
            'kng_card_carousel_image_bg_color',
            [
                'label' => esc_html__('Background Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-card-carousel-image' => 'background-color: {{VALUE}}',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            Group_Control_Css_Filter::get_type(),
            [
                'label' => esc_html__('CSS Filter', 'king-addons'),
                'name' => 'kng_card_carousel_image_css_filters',
                'selector' => '{{WRAPPER}} .king-addons-card-carousel-image img',
            ]
        );

        $this->add_group_control(
            Group_Control_Css_Filter::get_type(),
            [
                'label' => esc_html__('CSS Filter for image on card hover', 'king-addons'),
                'name' => 'kng_card_carousel_image_css_filters_hover',
                'selector' => '{{WRAPPER}} .king-addons-card-carousel-card:hover .king-addons-card-carousel-image img',
            ]
        );

        $this->add_responsive_control(
            'kng_card_carousel_image_transition',
            [
                'label' => esc_html__('Transition duration on hover (ms)', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'step' => 10,
                'default' => 300,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-card-carousel-image img' => 'transition: all {{SIZE}}ms;'
                ],
            ]
        );

        $this->add_control(
            'kng_card_carousel_image_scale_switcher',
            [
                'label' => esc_html__('Scale image on card hover', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
            ]
        );

        $this->add_responsive_control(
            'kng_card_carousel_image_scale_hover',
            [
                'label' => esc_html__('Scale on hover', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'step' => 0.01,
                'default' => 1.3,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-card-carousel-card:hover .king-addons-card-carousel-image img' => 'transform: scale({{SIZE}});'
                ],
                'condition' => [
                    'kng_card_carousel_image_scale_switcher' => 'yes',
                ]
            ]
        );

        $this->add_responsive_control(
            'kng_card_carousel_image_padding',
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
                    '{{WRAPPER}} .king-addons-card-carousel-image' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->add_responsive_control(
            'kng_card_carousel_image_margin',
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
                    '{{WRAPPER}} .king-addons-card-carousel-image' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'kng_card_carousel_image_box_shadow',
                'selector' => '{{WRAPPER}} .king-addons-card-carousel-image',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'kng_card_carousel_image_border',
                'selector' => '{{WRAPPER}} .king-addons-card-carousel-image',
                'fields_options' => [
                    'border' => [
                        'default' => 'none',
                    ],
                ],
            ]
        );

        $this->add_responsive_control(
            'kng_card_carousel_image_border_radius',
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
                    '{{WRAPPER}} .king-addons-card-carousel-image' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'kng_card_carousel_style_section_imagedescription',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Image Description', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'kng_card_carousel_imagedescription_typography',
                'selector' => '{{WRAPPER}} .king-addons-card-carousel-imagedescription',
            ]
        );

        $this->add_control(
            'kng_card_carousel_imagedescription_txt_color',
            [
                'label' => esc_html__('Text Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-card-carousel-imagedescription' => 'color: {{VALUE}}'
                ],
            ]
        );

        $this->add_control(
            'kng_card_carousel_imagedescription_bg_color',
            [
                'label' => esc_html__('Background Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-card-carousel-imagedescription' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'kng_card_carousel_imagedescription_txt_align',
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
                    '{{WRAPPER}} .king-addons-card-carousel-imagedescription' => 'text-align: {{VALUE}};',
                ]
            ]
        );

        $this->add_responsive_control(
            'kng_card_carousel_imagedescription_padding',
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
                    '{{WRAPPER}} .king-addons-card-carousel-imagedescription' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->add_responsive_control(
            'kng_card_carousel_imagedescription_margin',
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
                    '{{WRAPPER}} .king-addons-card-carousel-imagedescription' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'kng_card_carousel_imagedescription_box_shadow',
                'selector' => '{{WRAPPER}} .king-addons-card-carousel-imagedescription',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'kng_card_carousel_imagedescription_border',
                'selector' => '{{WRAPPER}} .king-addons-card-carousel-imagedescription',
                'fields_options' => [
                    'border' => [
                        'default' => 'none',
                    ],
                ],
            ]
        );

        $this->add_responsive_control(
            'kng_card_carousel_imagedescription_border_radius',
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
                    '{{WRAPPER}} .king-addons-card-carousel-imagedescription' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'kng_card_carousel_style_section_title',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Title', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'kng_card_carousel_title_typography',
                'selector' => '{{WRAPPER}} .king-addons-card-carousel-title',
            ]
        );

        $this->add_control(
            'kng_card_carousel_title_txt_color',
            [
                'label' => esc_html__('Text Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-card-carousel-title' => 'color: {{VALUE}}'
                ],
            ]
        );

        $this->add_control(
            'kng_card_carousel_title_bg_color',
            [
                'label' => esc_html__('Background Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-card-carousel-title' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'kng_card_carousel_title_txt_align',
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
                    '{{WRAPPER}} .king-addons-card-carousel-title' => 'text-align: {{VALUE}};',
                ]
            ]
        );

        $this->add_responsive_control(
            'kng_card_carousel_title_padding',
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
                    '{{WRAPPER}} .king-addons-card-carousel-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->add_responsive_control(
            'kng_card_carousel_title_margin',
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
                    '{{WRAPPER}} .king-addons-card-carousel-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'kng_card_carousel_title_box_shadow',
                'selector' => '{{WRAPPER}} .king-addons-card-carousel-title',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'kng_card_carousel_title_border',
                'selector' => '{{WRAPPER}} .king-addons-card-carousel-title',
                'fields_options' => [
                    'border' => [
                        'default' => 'none',
                    ],
                ],
            ]
        );

        $this->add_responsive_control(
            'kng_card_carousel_title_border_radius',
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
                    '{{WRAPPER}} .king-addons-card-carousel-title' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'kng_card_carousel_style_section_subtitle',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Subtitle', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'kng_card_carousel_subtitle_typography',
                'selector' => '{{WRAPPER}} .king-addons-card-carousel-subtitle',
            ]
        );

        $this->add_control(
            'kng_card_carousel_subtitle_txt_color',
            [
                'label' => esc_html__('Text Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-card-carousel-subtitle' => 'color: {{VALUE}}'
                ],
            ]
        );

        $this->add_control(
            'kng_card_carousel_subtitle_bg_color',
            [
                'label' => esc_html__('Background Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-card-carousel-subtitle' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'kng_card_carousel_subtitle_txt_align',
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
                    '{{WRAPPER}} .king-addons-card-carousel-subtitle' => 'text-align: {{VALUE}};',
                ]
            ]
        );

        $this->add_responsive_control(
            'kng_card_carousel_subtitle_padding',
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
                    '{{WRAPPER}} .king-addons-card-carousel-subtitle' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->add_responsive_control(
            'kng_card_carousel_subtitle_margin',
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
                    '{{WRAPPER}} .king-addons-card-carousel-subtitle' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'kng_card_carousel_subtitle_box_shadow',
                'selector' => '{{WRAPPER}} .king-addons-card-carousel-subtitle',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'kng_card_carousel_subtitle_border',
                'selector' => '{{WRAPPER}} .king-addons-card-carousel-subtitle',
                'fields_options' => [
                    'border' => [
                        'default' => 'none',
                    ],
                ],
            ]
        );

        $this->add_responsive_control(
            'kng_card_carousel_subtitle_border_radius',
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
                    '{{WRAPPER}} .king-addons-card-carousel-subtitle' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'kng_card_carousel_style_section_description',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Description', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'kng_card_carousel_description_typography',
                'selector' => '{{WRAPPER}} .king-addons-card-carousel-description',
            ]
        );

        $this->add_control(
            'kng_card_carousel_description_txt_color',
            [
                'label' => esc_html__('Text Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-card-carousel-description' => 'color: {{VALUE}}'
                ],
            ]
        );

        $this->add_control(
            'kng_card_carousel_description_bg_color',
            [
                'label' => esc_html__('Background Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-card-carousel-description' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'kng_card_carousel_description_txt_align',
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
                    '{{WRAPPER}} .king-addons-card-carousel-description' => 'text-align: {{VALUE}};',
                ]
            ]
        );

        $this->add_responsive_control(
            'kng_card_carousel_description_padding',
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
                    '{{WRAPPER}} .king-addons-card-carousel-description' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->add_responsive_control(
            'kng_card_carousel_description_margin',
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
                    '{{WRAPPER}} .king-addons-card-carousel-description' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'kng_card_carousel_description_box_shadow',
                'selector' => '{{WRAPPER}} .king-addons-card-carousel-description',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'kng_card_carousel_description_border',
                'selector' => '{{WRAPPER}} .king-addons-card-carousel-description',
                'fields_options' => [
                    'border' => [
                        'default' => 'none',
                    ],
                ],
            ]
        );

        $this->add_responsive_control(
            'kng_card_carousel_description_border_radius',
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
                    '{{WRAPPER}} .king-addons-card-carousel-description' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'kng_card_carousel_style_section_button',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Button', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'kng_card_carousel_btn_h_position',
            [
                'label' => esc_html__('Button Horizontal Position', 'king-addons'),
                'type' => Controls_Manager::CHOOSE,
                'default' => 'center',
                'options' => [
                    'flex-start' => [
                        'title' => esc_html__('Left', 'king-addons'),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'king-addons'),
                        'icon' => 'eicon-h-align-center',
                    ],
                    'flex-end' => [
                        'title' => esc_html__('Right', 'king-addons'),
                        'icon' => 'eicon-h-align-right',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-card-carousel-button' => 'align-items: {{VALUE}};',
                ]
            ]
        );

        $this->add_control(
            'kng_card_carousel_btn_txt_align',
            [
                'label' => esc_html__('Button Text Align', 'king-addons'),
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
                    '{{WRAPPER}} .king-addons-card-carousel-button .king-addons-card-carousel-btn' => 'text-align: {{VALUE}};',
                ]
            ]
        );

        $this->add_control(
            'kng_card_carousel_btn_width_type',
            [
                'label' => esc_html__('Width', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'auto' => esc_html__('Auto', 'king-addons'),
                    'custom' => esc_html__('Custom', 'king-addons'),
                ],
                'default' => 'auto',
            ]
        );

        $this->add_responsive_control(
            'kng_card_carousel_btn_width',
            [
                'label' => esc_html__('Width (%)', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'step' => 1,
                'max' => 100,
                'default' => 100,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-card-carousel-button .king-addons-card-carousel-btn' => 'width: {{SIZE}}%;'
                ],
                'condition' => [
                    'kng_card_carousel_btn_width_type' => 'custom'
                ]
            ]
        );

        $this->add_responsive_control(
            'kng_card_carousel_btn_margin',
            [
                'label' => esc_html__('Margin (px)', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'default' => [
                    'top' => 10,
                    'right' => 0,
                    'bottom' => 10,
                    'left' => 0,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-card-carousel-button .king-addons-card-carousel-btn' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->start_controls_tabs('kng_card_carousel_btn_tabs');

        $this->start_controls_tab(
            'kng_card_carousel_btn_tab_normal',
            [
                'label' => esc_html__('Normal', 'king-addons'),
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'kng_card_carousel_btn_typography',
                'selector' => '{{WRAPPER}} .king-addons-card-carousel-btn',
            ]
        );

        $this->add_control(
            'kng_card_carousel_btn_txt_color',
            [
                'label' => esc_html__('Text Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-card-carousel-btn' => 'color: {{VALUE}}'
                ],
            ]
        );

        $this->add_control(
            'kng_card_carousel_btn_bg_color',
            [
                'label' => esc_html__('Background Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#574ff7',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-card-carousel-btn' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'kng_card_carousel_btn_padding',
            [
                'label' => esc_html__('Padding (px)', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'default' => [
                    'top' => 10,
                    'right' => 10,
                    'bottom' => 10,
                    'left' => 10,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-card-carousel-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'kng_card_carousel_btn_box_shadow',
                'selector' => '{{WRAPPER}} .king-addons-card-carousel-btn',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'kng_card_carousel_btn_border',
                'selector' => '{{WRAPPER}} .king-addons-card-carousel-btn',
                'fields_options' => [
                    'border' => [
                        'default' => 'none',
                    ],
                ],
            ]
        );

        $this->add_responsive_control(
            'kng_card_carousel_btn_border_radius',
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
                    '{{WRAPPER}} .king-addons-card-carousel-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'kng_card_carousel_btn_tab_hover',
            [
                'label' => esc_html__('Hover', 'king-addons'),
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'kng_card_carousel_btn_typography_hover',
                'selector' => '{{WRAPPER}} .king-addons-card-carousel-btn:hover',
            ]
        );

        $this->add_control(
            'kng_card_carousel_btn_txt_color_hover',
            [
                'label' => esc_html__('Text Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-card-carousel-btn:hover' => 'color: {{VALUE}}'
                ],
            ]
        );

        $this->add_control(
            'kng_card_carousel_btn_bg_color_hover',
            [
                'label' => esc_html__('Background Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-card-carousel-btn:hover' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'kng_card_carousel_btn_padding_hover',
            [
                'label' => esc_html__('Padding', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-card-carousel-btn:hover' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'kng_card_carousel_btn_box_shadow_hover',
                'selector' => '{{WRAPPER}} .king-addons-card-carousel-btn:hover',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'kng_card_carousel_btn_border_hover',
                'selector' => '{{WRAPPER}} .king-addons-card-carousel-btn:hover',
            ]
        );

        $this->add_responsive_control(
            'kng_card_carousel_btn_border_radius_hover',
            [
                'label' => esc_html__('Border Radius', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-card-carousel-btn:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();
        // END Button

        /** START SECTION: Navigation */
        $this->start_controls_section(
            'kng_card_carousel_style_section_nav',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Navigation', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'kng_card_carousel_nav_icon_prev_title',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Previous button', 'king-addons'),
                'type' => Controls_Manager::HEADING,
            ]
        );

        $this->add_control(
            'kng_card_carousel_nav_icon_prev',
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
            'kng_card_carousel_nav_margin_prev',
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
                    '{{WRAPPER}} .king-addons-card-carousel-nav-prev .king-addons-card-carousel-nav-inner' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );


        $this->add_responsive_control(
            'kng_card_carousel_nav_prev_transform_scale',
            [
                'label' => esc_html__('Scale on hover (%)', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'step' => 0.1,
                'min' => 0,
                'default' => 1.3,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-card-carousel-nav-prev:hover .king-addons-card-carousel-nav-inner' => 'transform: scale({{VALUE}});',
                ],
            ]
        );

        $this->add_responsive_control(
            'kng_card_carousel_nav_position_v_prev',
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
                    '{{WRAPPER}} .king-addons-card-carousel-nav-prev' => 'top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'kng_card_carousel_nav_position_h_prev',
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
                    '{{WRAPPER}} .king-addons-card-carousel-nav-prev' => 'left: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'kng_card_carousel_nav_icon_next_title',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Next button', 'king-addons'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'kng_card_carousel_nav_icon_next',
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
            'kng_card_carousel_nav_margin_next',
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
                    '{{WRAPPER}} .king-addons-card-carousel-nav-next .king-addons-card-carousel-nav-inner' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->add_responsive_control(
            'kng_card_carousel_nav_next_transform_scale',
            [
                'label' => esc_html__('Scale on hover (%)', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'step' => 0.1,
                'min' => 0,
                'default' => 1.3,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-card-carousel-nav-next:hover .king-addons-card-carousel-nav-inner' => 'transform: scale({{VALUE}});',
                ],
            ]
        );

        $this->add_responsive_control(
            'kng_card_carousel_nav_position_v_next',
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
                    '{{WRAPPER}} .king-addons-card-carousel-nav-next' => 'top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'kng_card_carousel_nav_position_h_next',
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
                    '{{WRAPPER}} .king-addons-card-carousel-nav-next' => 'right: calc(100% - {{SIZE}}{{UNIT}});',
                ],
            ]
        );

        // common
        $this->add_control(
            'kng_card_carousel_nav_icon_common_title',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Common settings', 'king-addons'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'kng_card_carousel_nav_icon_size',
            [
                'label' => esc_html__('Icon Size', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'step' => 1,
                'min' => 0,
                'default' => 32,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-card-carousel-nav i' => 'font-size: {{VALUE}}px; width: {{VALUE}}px;',
                    '{{WRAPPER}} .king-addons-card-carousel-nav img' => 'width: {{VALUE}}px; height: {{VALUE}}px;',
                    '{{WRAPPER}} .king-addons-card-carousel-nav svg' => 'width: {{VALUE}}px; height: {{VALUE}}px;'
                ],
            ]
        );

        $this->add_responsive_control(
            'kng_card_carousel_nav_transition_icon',
            [
                'label' => esc_html__('Transition duration for icon on hover (ms)', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'step' => 10,
                'default' => 300,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-card-carousel-nav i' => 'transition: all {{SIZE}}ms;',
                    '{{WRAPPER}} .king-addons-card-carousel-nav img' => 'transition: all {{SIZE}}ms;',
                    '{{WRAPPER}} .king-addons-card-carousel-nav svg' => 'transition: all {{SIZE}}ms;',
                    '{{WRAPPER}} .king-addons-card-carousel-nav .king-addons-card-carousel-nav-inner' => 'transition: all {{SIZE}}ms;',
                ],
            ]
        );

        $this->add_responsive_control(
            'kng_card_carousel_nav_padding',
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
                    '{{WRAPPER}} .king-addons-card-carousel-nav-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->start_controls_tabs('kng_card_carousel_nav_tabs');

        $this->start_controls_tab(
            'kng_card_carousel_nav_tab_normal',
            [
                'label' => esc_html__('Normal', 'king-addons'),
            ]
        );

        $this->add_control(
            'kng_card_carousel_nav_color',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#00000033',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-card-carousel-nav i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .king-addons-card-carousel-nav svg' => 'fill: {{VALUE}};'
                ],
            ]
        );

        $this->add_control(
            'kng_card_carousel_nav_bg_color',
            [
                'label' => esc_html__('Background Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-card-carousel-nav-inner' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'kng_card_carousel_nav_box_shadow',
                'selector' => '{{WRAPPER}} .king-addons-card-carousel-nav-inner',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'kng_card_carousel_nav_border',
                'selector' => '{{WRAPPER}} .king-addons-card-carousel-nav-inner',
                'fields_options' => [
                    'border' => [
                        'default' => 'none',
                    ],
                ],
            ]
        );

        $this->add_responsive_control(
            'kng_card_carousel_nav_border_radius',
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
                    '{{WRAPPER}} .king-addons-card-carousel-nav-inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );
        $this->end_controls_tab();

        $this->start_controls_tab(
            'kng_card_carousel_nav_tab_hover',
            [
                'label' => esc_html__('Hover', 'king-addons'),
            ]
        );

        $this->add_control(
            'kng_card_carousel_nav_color_hover',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-card-carousel-nav i:hover' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .king-addons-card-carousel-nav svg:hover' => 'fill: {{VALUE}};'
                ],
            ]
        );

        $this->add_control(
            'kng_card_carousel_nav_bg_color_hover',
            [
                'label' => esc_html__('Background Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-card-carousel-nav-inner:hover' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'kng_card_carousel_nav_box_shadow_hover',
                'selector' => '{{WRAPPER}} .king-addons-card-carousel-nav-inner:hover',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'kng_card_carousel_nav_border_hover',
                'selector' => '{{WRAPPER}} .king-addons-card-carousel-nav-inner:hover',
            ]
        );

        $this->add_responsive_control(
            'kng_card_carousel_nav_border_radius_hover',
            [
                'label' => esc_html__('Border Radius', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-card-carousel-nav-inner:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();
        $this->end_controls_section();
        /** END SECTION: Navigation */

        /** SECTION: Pagination */
        $this->start_controls_section(
            'kng_card_carousel_style_section_pag',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Pagination', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'kng_card_carousel_pag_color',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#000000',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-card-carousel-pagination' => '--swiper-theme-color: {{VALUE}}; --swiper-pagination-fraction-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'kng_card_carousel_pag_color_second',
            [
                'label' => esc_html__('Second Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#00000033',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-card-carousel-pagination' => '--swiper-pagination-progressbar-bg-color: {{VALUE}}; --swiper-pagination-bullet-inactive-color: {{VALUE}}; --swiper-pagination-bullet-inactive-opacity: 1;',
                ],
            ]
        );

        $this->add_control(
            'kng_card_carousel_pag_bullets_title',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Bullets type', 'king-addons'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'kng_card_carousel_pag_bullets_size',
            [
                'label' => esc_html__('Size', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'step' => 1,
                'default' => 8,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-card-carousel-pagination' => '--swiper-pagination-bullet-size: {{SIZE}}px;'
                ],
            ]
        );

        $this->add_responsive_control(
            'kng_card_carousel_pag_bullets_gap',
            [
                'label' => esc_html__('Bullets gap', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'step' => 1,
                'default' => 4,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-card-carousel-pagination' => '--swiper-pagination-bullet-horizontal-gap: {{SIZE}}px;'
                ],
            ]
        );

        $this->add_control(
            'kng_card_carousel_pag_progressbar_title',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Progress bar type', 'king-addons'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'kng_card_carousel_pag_progressbar_size',
            [
                'label' => esc_html__('Size', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'step' => 1,
                'default' => 4,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-card-carousel-pagination' => '--swiper-pagination-progressbar-size: {{SIZE}}px;'
                ],
            ]
        );

        $this->add_control(
            'kng_card_carousel_pag_fraction_title',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Fraction type', 'king-addons'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'kng_card_carousel_pag_fraction_typography',
                'selector' => '{{WRAPPER}} .king-addons-card-carousel-pagination.swiper-pagination-fraction',
            ]
        );

        $this->end_controls_section();
        /** END SECTION: Pagination */

        /** SECTION: Whole Carousel */
        $this->start_controls_section(
            'kng_card_carousel_style_section_whole_carousel',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Whole Carousel', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'kng_card_carousel_whole_carousel_bg',
                'selector' => '{{WRAPPER}} .king-addons-card-carousel-items'
            ]
        );

        $this->add_responsive_control(
            'kng_card_carousel_whole_carousel_margin',
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
                    '{{WRAPPER}} .king-addons-card-carousel-items' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->add_responsive_control(
            'kng_card_carousel_whole_carousel_margin_wrapper',
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
                    '{{WRAPPER}} .king-addons-card-carousel-items .swiper-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->add_responsive_control(
            'kng_card_carousel_whole_carousel_padding',
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
                    '{{WRAPPER}} .king-addons-card-carousel-items' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'kng_card_carousel_whole_carousel_box_shadow',
                'selector' => '{{WRAPPER}} .king-addons-card-carousel-items',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'kng_card_carousel_whole_carousel_border',
                'selector' => '{{WRAPPER}} .king-addons-card-carousel-items',
                'fields_options' => [
                    'border' => [
                        'default' => 'none',
                    ],
                ],
            ]
        );

        $this->add_responsive_control(
            'kng_card_carousel_whole_carousel_border_radius',
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
                    '{{WRAPPER}} .king-addons-card-carousel-items' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->end_controls_section();
        /** END SECTION: Whole Carousel */

        $this->start_controls_section(
            'kng_card_carousel_style_section_whole_card',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Whole Card', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'kng_card_carousel_whole_card_transition',
            [
                'label' => esc_html__('Transition duration on card hover (ms)', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'step' => 10,
                'default' => 300,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-card-carousel-card-inner' => 'transition: all {{SIZE}}ms;'
                ],
            ]
        );

        $this->start_controls_tabs('kng_card_carousel_whole_card_tabs');

        $this->start_controls_tab(
            'kng_card_carousel_whole_card_tab_normal',
            [
                'label' => esc_html__('Normal', 'king-addons'),
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'kng_card_carousel_whole_card_bg',
                'selector' => '{{WRAPPER}} .king-addons-card-carousel-card-inner'
            ]
        );

        $this->add_responsive_control(
            'kng_card_carousel_whole_card_padding',
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
                    '{{WRAPPER}} .king-addons-card-carousel-card-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'kng_card_carousel_whole_card_box_shadow',
                'selector' => '{{WRAPPER}} .king-addons-card-carousel-card-inner',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'kng_card_carousel_whole_card_border',
                'selector' => '{{WRAPPER}} .king-addons-card-carousel-card-inner',
                'fields_options' => [
                    'border' => [
                        'default' => 'none',
                    ],
                ],
            ]
        );

        $this->add_responsive_control(
            'kng_card_carousel_whole_card_border_radius',
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
                    '{{WRAPPER}} .king-addons-card-carousel-card-inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'kng_card_carousel_whole_card_tab_hover',
            [
                'label' => esc_html__('Hover', 'king-addons'),
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'kng_card_carousel_whole_card_bg_hover',
                'selector' => '{{WRAPPER}} .king-addons-card-carousel-card-inner:hover'
            ]
        );

        $this->add_responsive_control(
            'kng_card_carousel_whole_card_padding_hover',
            [
                'label' => esc_html__('Padding', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-card-carousel-card-inner:hover' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'kng_card_carousel_whole_card_box_shadow_hover',
                'selector' => '{{WRAPPER}} .king-addons-card-carousel-card-inner:hover',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'kng_card_carousel_whole_card_border_hover',
                'selector' => '{{WRAPPER}} .king-addons-card-carousel-card-inner:hover',
            ]
        );

        $this->add_responsive_control(
            'kng_card_carousel_whole_card_border_radius_hover',
            [
                'label' => esc_html__('Border Radius', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-card-carousel-card-inner:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();

        $this->start_controls_section(
            'kng_card_carousel_style_section_whole_image_container',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Whole Image Container', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'kng_card_carousel_style_section_whole_image_container_title',
            [
                'label' => '<span class="elementor-control-field-description">' .
                    esc_html__('Image container includes image and image description.', 'king-addons') .
                    '</span>',
                'type' => Controls_Manager::RAW_HTML,
            ]
        );

        $this->add_responsive_control(
            'kng_card_carousel_whole_image_container_bg_color',
            [
                'label' => esc_html__('Background Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-card-carousel-card-container-1' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'kng_card_carousel_whole_image_container_padding',
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
                    '{{WRAPPER}} .king-addons-card-carousel-card-container-1' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->add_responsive_control(
            'kng_card_carousel_whole_image_container_margin',
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
                    '{{WRAPPER}} .king-addons-card-carousel-card-container-1' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'kng_card_carousel_whole_image_container_box_shadow',
                'selector' => '{{WRAPPER}} .king-addons-card-carousel-card-container-1',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'kng_card_carousel_whole_image_container_border',
                'selector' => '{{WRAPPER}} .king-addons-card-carousel-card-container-1',
                'fields_options' => [
                    'border' => [
                        'default' => 'none',
                    ],
                ],
            ]
        );

        $this->add_responsive_control(
            'kng_card_carousel_whole_image_container_border_radius',
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
                    '{{WRAPPER}} .king-addons-card-carousel-card-container-1' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'kng_card_carousel_style_section_whole_text_container',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Whole Text Container', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'kng_card_carousel_style_section_whole_text_container_title',
            [
                'label' => '<span class="elementor-control-field-description">' .
                    esc_html__('Text container includes title, subtitle, description and button.', 'king-addons') .
                    '</span>',
                'type' => Controls_Manager::RAW_HTML,
            ]
        );

        $this->add_responsive_control(
            'kng_card_carousel_whole_text_container_bg_color',
            [
                'label' => esc_html__('Background Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-card-carousel-card-container-2' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'kng_card_carousel_whole_text_container_padding',
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
                    '{{WRAPPER}} .king-addons-card-carousel-card-container-2' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->add_responsive_control(
            'kng_card_carousel_whole_text_container_margin',
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
                    '{{WRAPPER}} .king-addons-card-carousel-card-container-2' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'kng_card_carousel_whole_text_container_box_shadow',
                'selector' => '{{WRAPPER}} .king-addons-card-carousel-card-container-2',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'kng_card_carousel_whole_text_container_border',
                'selector' => '{{WRAPPER}} .king-addons-card-carousel-card-container-2',
                'fields_options' => [
                    'border' => [
                        'default' => 'none',
                    ],
                ],
            ]
        );

        $this->add_responsive_control(
            'kng_card_carousel_whole_text_container_border_radius',
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
                    '{{WRAPPER}} .king-addons-card-carousel-card-container-2' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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

        // Define allowed tags and attributes
        $allowed_tags = wp_kses_allowed_html('post');
        $allowed_tags['img']['srcset'] = true; // Allow srcset attribute for img tag
        $allowed_tags['img']['sizes'] = true; // Allow sizes attribute for img tag
        $allowed_tags['img']['decoding'] = true; // Allow decoding attribute for img tag
        $allowed_tags['img']['loading'] = true; // Allow loading attribute for img tag. It is for the lazy loading possibility.

        /** START: Cards Container ===================== */
        ?>
        <div class="king-addons-card-carousel-items-wrap">
            <div class="king-addons-card-carousel-items king-addons-card-carousel-items-<?php echo esc_attr($this_ID); ?> swiper-container swiper">
                <div class="swiper-wrapper<?php if ('yes' === $settings['kng_card_carousel_autoplay_like_ticker_switcher']) {
                    echo ' king-addons-card-carousel-autoplay-like-ticker';
                } ?>">
                    <?php

                    $item_count = 0;

                    /** @noinspection PhpUnusedLocalVariableInspection */
                    foreach ($settings['kng_card_carousel_content_items'] as $key => $item) {

                        // open card div
                        echo '<div class="elementor-repeater-item-' . esc_attr($item['_id']) . ' swiper-slide king-addons-card-carousel-card' . (($item['kng_card_carousel_whole_card_link']['url']) ? ' king-addons-card-carousel-cursor-pointer' : '') . '">';

                        // open inner div
                        echo '<div class="king-addons-card-carousel-card-inner">';

                        // CONTAINER 1
                        if ('yes' !== $settings['kng_card_carousel_cards_whole_image_container_switcher']) {
                            echo '<div class="king-addons-card-carousel-card-container-1">';

                            // image
                            if ('yes' !== $settings['kng_card_carousel_cards_image_switcher']) {
                                echo '<div class="king-addons-card-carousel-image">';

                                // image link
                                if ($item['kng_card_carousel_image_link']['url']) {
                                    echo '<a href="' . esc_url($item['kng_card_carousel_image_link']['url']) . '"' .
                                        (($item['kng_card_carousel_image_link']['is_external']) ? ' target="_blank"' : '') .
                                        (($item['kng_card_carousel_image_link']['nofollow']) ? ' rel="nofollow"' : '') .
                                        '>';
                                }

                                $image_html = Group_Control_Image_Size::get_attachment_image_html($item, 'kng_card_carousel_image_size', 'kng_card_carousel_image');
                                echo wp_kses($image_html, $allowed_tags);

                                echo ($item['kng_card_carousel_image_link']['url']) ? '</a>' : '';

                                echo '</div>';
                            }

                            // image description
                            if ('yes' !== $settings['kng_card_carousel_cards_imagedescription_switcher']) {
                                if ('' !== $item['kng_card_carousel_imagedescription']) {
                                    echo '<div class="king-addons-card-carousel-imagedescription">';
                                    echo wp_kses_post($item['kng_card_carousel_imagedescription']);
                                    echo '</div>';
                                }
                            }

                            echo '</div>';
                        }
                        // END CONTAINER 1

                        // CONTAINER 2
                        if ('yes' !== $settings['kng_card_carousel_cards_whole_text_container_switcher']) {
                            echo '<div class="king-addons-card-carousel-card-container-2">';

                            // title
                            if ('yes' !== $settings['kng_card_carousel_cards_title_switcher']) {
                                if ('' !== $item['kng_card_carousel_title']) {
                                    echo '<div class="king-addons-card-carousel-title">';
                                    echo wp_kses_post($item['kng_card_carousel_title']);
                                    echo '</div>';
                                }
                            }

                            // subtitle
                            if ('yes' !== $settings['kng_card_carousel_cards_subtitle_switcher']) {
                                if ('' !== $item['kng_card_carousel_subtitle']) {
                                    echo '<div class="king-addons-card-carousel-subtitle">';
                                    echo wp_kses_post($item['kng_card_carousel_subtitle']);
                                    echo '</div>';
                                }
                            }

                            // description
                            if ('yes' !== $settings['kng_card_carousel_cards_description_switcher']) {
                                if ('' !== $item['kng_card_carousel_description']) {
                                    echo '<div class="king-addons-card-carousel-description">';
                                    echo wp_kses_post($item['kng_card_carousel_description']);
                                    echo '</div>';
                                }
                            }

                            // button
                            if ('' !== $item['kng_card_carousel_btn_text']) {
                                echo '<div class="king-addons-card-carousel-button">';

                                $item_tag = 'div';

                                if ('' !== $item['kng_card_carousel_btn_link']['url']) {

                                    $item_tag = 'a';

                                    $this->add_render_attribute('kng_card_carousel_btn_attribute' . $item_count, 'href', esc_url($item['kng_card_carousel_btn_link']['url']));

                                    if ($item['kng_card_carousel_btn_link']['is_external']) {
                                        $this->add_render_attribute('kng_card_carousel_btn_attribute' . $item_count, 'target', '_blank');
                                    }

                                    if ($item['kng_card_carousel_btn_link']['nofollow']) {
                                        $this->add_render_attribute('kng_card_carousel_btn_attribute' . $item_count, 'rel', 'nofollow');
                                    }
                                }

                                $this->add_render_attribute('kng_card_carousel_btn_attribute' . $item_count, 'class', 'king-addons-card-carousel-btn king-addons-card-carousel-btn-' . esc_attr($this_ID));

                                $html = '<' . esc_attr($item_tag) . ' ' . $this->get_render_attribute_string('kng_card_carousel_btn_attribute' . $item_count) . '>';

                                echo wp_kses($html, wp_kses_allowed_html('post'));
                                echo esc_html($item['kng_card_carousel_btn_text']);
                                echo '</' . esc_attr($item_tag) . '>';

                                echo '</div>';
                            }

                            echo '</div>';
                        }
                        // END CONTAINER 2

                        // close card inner div
                        echo '</div>';

                        // card link
                        if ($item['kng_card_carousel_whole_card_link']['url']) {
                            ?>
                            <a href="<?php echo esc_url($item['kng_card_carousel_whole_card_link']['url']); ?>"<?php
                            echo(($item['kng_card_carousel_whole_card_link']['is_external']) ? ' target="_blank"' : '');
                            echo(($item['kng_card_carousel_whole_card_link']['nofollow']) ? ' rel="nofollow"' : ''); ?>
                               class="king-addons-card-carousel-card-link"></a>
                        <?php }

                        // close card div
                        echo '</div>';
                        $item_count++;
                    } ?>
                </div>
            </div>
            <?php

            // pagination
            if ('yes' === $settings['kng_card_carousel_pag_switcher']) {
                echo '<div class="swiper-pagination king-addons-card-carousel-pagination king-addons-card-carousel-pagination-' .
                    esc_attr($this_ID) .
                    (('yes' === $settings['kng_card_carousel_autoplay_like_ticker_switcher']) ? ' king-addons-card-carousel-autoplay-like-ticker-pag' : '') .
                    '"></div>';
            }

            // navigation
            if ('yes' === $settings['kng_card_carousel_nav_switcher']) {

                echo '<div class="king-addons-card-carousel-nav-prev king-addons-card-carousel-nav-prev-' . esc_attr($this_ID) . ' king-addons-card-carousel-nav">';
                echo '<div class="king-addons-card-carousel-nav-inner">';
                Icons_Manager::render_icon($settings['kng_card_carousel_nav_icon_prev']);
                echo '</div>';
                echo '</div>';

                echo '<div class="king-addons-card-carousel-nav-next king-addons-card-carousel-nav-next-' . esc_attr($this_ID) . ' king-addons-card-carousel-nav">';
                echo '<div class="king-addons-card-carousel-nav-inner">';
                Icons_Manager::render_icon($settings['kng_card_carousel_nav_icon_next']);
                echo '</div>';
                echo '</div>';

            }
            ?>
        </div>
        <?php

        $js_swiper_check_if_DOM_already_loaded = "document.readyState === 'complete'";
        $js_swiper_check_if_DOM_not_already_loaded = "window.addEventListener('load', function () {";

        $js_swiper = "new Swiper('.king-addons-card-carousel-items-" . esc_js($this_ID) . "', {";
        $js_swiper .= "direction: 'horizontal',";

        $js_swiper .= "slidesPerView: " . esc_js($settings['kng_card_carousel_desktop_cards_per_view']) . ",";
        $js_swiper .= "spaceBetween: " . esc_js($settings['kng_card_carousel_desktop_space_between_cards']) . ",";

        // Responsive breakpoints
        $js_swiper .= 'breakpoints: {0: {slidesPerView: ' .
            esc_js($settings['kng_card_carousel_mobile_cards_per_view']) . ', spaceBetween: ' .
            esc_js($settings['kng_card_carousel_mobile_space_between_cards']) . '}, ';
        $js_swiper .=
            esc_js(($settings['kng_card_carousel_mobile_breakpoint'] + 1)) . ': {slidesPerView: ' .
            esc_js($settings['kng_card_carousel_tablet_cards_per_view']) . ', spaceBetween: ' .
            esc_js($settings['kng_card_carousel_tablet_space_between_cards']) . '}, ';
        $js_swiper .=
            esc_js(($settings['kng_card_carousel_tablet_breakpoint'] + 1)) . ': {slidesPerView: ' .
            esc_js($settings['kng_card_carousel_desktop_cards_per_view']) . ', spaceBetween: ' .
            esc_js($settings['kng_card_carousel_desktop_space_between_cards']) . '}},';

        // Scrolling speed
        if ('yes' !== $settings['kng_card_carousel_autoplay_like_ticker_switcher']) {
            $js_swiper .= "speed: " . esc_js($settings['kng_card_carousel_scrolling_speed']) . ",";
        } else {
            $js_swiper .= "speed: " . esc_js($settings['kng_card_carousel_autoplay_like_ticker_autoplay_speed']) . ",";
        }

        // Pagination
        if ('yes' === $settings['kng_card_carousel_pag_switcher']) {
            $js_swiper .= "pagination: {el: '.king-addons-card-carousel-pagination-" .
                esc_js($this_ID) . "', " .
                ('yes' === $settings['kng_card_carousel_pag_clickable_switcher'] ? 'clickable: true, ' : '');
            if ('yes' === $settings['kng_card_carousel_pag_dynamic_switcher']) {
                $js_swiper .= 'dynamicBullets: true, ';
                $js_swiper .= 'dynamicMainBullets: ' . esc_js($settings['kng_card_carousel_pag_dynamic_number'] . ', ');
            }
            $js_swiper .= "type: '" . esc_js($settings['kng_card_carousel_pag_type']) . "'},";
        }

        // Navigation
        if ('yes' === $settings['kng_card_carousel_nav_switcher']) {
            $js_swiper .= "navigation: {nextEl: '.king-addons-card-carousel-nav-next-" .
                esc_js($this_ID) . "', prevEl: '.king-addons-card-carousel-nav-prev-" .
                esc_js($this_ID) . "'},";
        }

        // Loop
        if ('yes' === $settings['kng_card_carousel_loop_switcher']) {
            $js_swiper .= "loop: true,";
        }

        // Rewind
        if ('yes' === $settings['kng_card_carousel_rewind_switcher']) {
            $js_swiper .= "rewind: true,";
        }

        // Autoplay
        if ('yes' === $settings['kng_card_carousel_autoplay_switcher']) {
            $js_swiper .= "autoplay: {";
            if ('yes' !== $settings['kng_card_carousel_autoplay_like_ticker_switcher']) {
                $js_swiper .= "delay: " . esc_js($settings['kng_card_carousel_autoplay_delay']) . ",";
            } else {
                $js_swiper .= "delay: 0,";
            }
            $js_swiper .= "disableOnInteraction: " . ('yes' === $settings['kng_card_carousel_autoplay_disableoninteraction_switcher'] ? 'true' : 'false') . ",";
            $js_swiper .= "pauseOnMouseEnter: " . ('yes' === $settings['kng_card_carousel_autoplay_pausedonmouseenter_switcher'] ? 'true' : 'false') . ",";
            $js_swiper .= "reverseDirection: " . ('yes' === $settings['kng_card_carousel_autoplay_reversedirection_switcher'] ? 'true' : 'false') . ",";
            $js_swiper .= "},";
        }

        $js_swiper .= "})";

        $js_swiper_full = $js_swiper_check_if_DOM_already_loaded . ' ?' . $js_swiper . ' : ' . $js_swiper_check_if_DOM_not_already_loaded . $js_swiper . '; });';

        wp_print_inline_script_tag($js_swiper_full);
        /** END: Cards Container ===================== */
    }
}