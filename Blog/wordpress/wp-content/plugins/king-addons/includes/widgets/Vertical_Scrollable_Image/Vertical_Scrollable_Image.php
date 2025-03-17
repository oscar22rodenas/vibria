<?php /** @noinspection SpellCheckingInspection, DuplicatedCode */

/** @noinspection PhpUnused */

namespace King_Addons;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Image_Size;
use Elementor\Utils;
use Elementor\Widget_Base;

/** @noinspection SpellCheckingInspection */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class Vertical_Scrollable_Image extends Widget_Base
{
    public function get_name(): string
    {
        return 'king-addons-vertical-scrollable-image';
    }

    public function get_title(): string
    {
        return esc_html__('Vertical Scrollable Image', 'king-addons');
    }

    public function get_icon(): string
    {
        /** @noinspection SpellCheckingInspection */
        return 'king-addons-icon king-addons-vertical-scrollable-image';
    }

    public function get_style_depends(): array
    {
        return [KING_ADDONS_ASSETS_UNIQUE_KEY . '-vertical-scrollable-image-style'];
    }

    public function get_categories(): array
    {
        return ['king-addons'];
    }

    public function get_keywords(): array
    {
        return ['image', 'images', 'scroll', 'scrolling', 'scroller', 'scrollable', 'animation', 'effect', 'animated',
            'vertical', 'horizontal', 'mouse', 'hover', 'over', 'hover over', 'picture', 'king', 'addons', 'mouseover',
            'kingaddons', 'king-addons'];
    }

    public function get_custom_help_url(): string
    {
        return 'https://kingaddons.com/elementor/vertical-scrollable-image/';
    }

    protected function register_controls(): void
    {
        /** START TAB: CONTENT ===================== */
        /** SECTION: General ===================== */
        $this->start_controls_section(
            'kng_v_scroll_image_section_general',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('General', 'king-addons'),
            ]
        );

        $this->add_control(
            'kng_v_scroll_image_image',
            [
                'label' => esc_html__('Image', 'king-addons'),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
                'ai' => [
                    'active' => false,
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name' => 'kng_v_scroll_image_image_size',
                'default' => 'full',
            ]
        );

        $this->add_responsive_control(
            'kng_v_scroll_image_box_height',
            [
                'label' => esc_html__('Box Height (px)', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'description' => esc_html__('Image size should be more than the box width or height, otherwise scrolling will not work. You can also limit the box width in the container, section or column width settings accordingly.', 'king-addons'),
                'min' => 50,
                'step' => 1,
                'default' => 350,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-vertical-scrollable-image' => 'height: {{SIZE}}px;',
                    '{{WRAPPER}} .king-addons-vertical-scrollable-image:active img' => 'transform: translateY(calc(-100% + {{SIZE}}px));',
                    '{{WRAPPER}} .king-addons-vertical-scrollable-image:hover img' => 'transform: translateY(calc(-100% + {{SIZE}}px));',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'kng_v_scroll_image_scrolling_duration',
            [
                'label' => esc_html__('Scrolling Duration (milliseconds)', 'king-addons'),
                'description' =>  esc_html__('Greater is slower', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'min' => 100,
                'step' => 100,
                'default' => 1000,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-vertical-scrollable-image.king-addons-vertical-scrollable-image-hover img' => 'transition: transform {{SIZE}}ms linear;',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'kng_v_scroll_image_border_radius',
            [
                'label' => esc_html__('Border Radius', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}}, {{WRAPPER}} .king-addons-vertical-scrollable-image' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'kng_v_scroll_image_box_shadow',
                'label' => esc_html__('Box Shadow', 'king-addons'),
                'selector' => '{{WRAPPER}} .king-addons-vertical-scrollable-image',
            ]
        );

        $this->end_controls_section();
        /** END SECTION: General ===================== */
        /** END TAB: CONTENT ===================== */
    }

    protected function render(): void
    {
        $settings = $this->get_settings_for_display();
        echo '<div class="king-addons-vertical-scrollable-image king-addons-vertical-scrollable-image-hover">';

        $image_html = Group_Control_Image_Size::get_attachment_image_html($settings, 'kng_v_scroll_image_image_size', 'kng_v_scroll_image_image');

        // Define allowed tags and attributes
        $allowed_tags = wp_kses_allowed_html('post');
        $allowed_tags['img']['srcset'] = true; // Allow srcset attribute for img tag
        $allowed_tags['img']['sizes'] = true; // Allow sizes attribute for img tag
        $allowed_tags['img']['decoding'] = true; // Allow decoding attribute for img tag
        $allowed_tags['img']['loading'] = true; // Allow loading attribute for img tag. It is for the lazy loading possibility.

        // Sanitize the image HTML using the extended set of allowed tags and attributes
        echo wp_kses($image_html, $allowed_tags);
        echo '</div>';
    }
}