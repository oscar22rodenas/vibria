<?php /** @noinspection SpellCheckingInspection, DuplicatedCode, PhpUnused */

namespace King_Addons;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Image_Size;
use Elementor\Icons_Manager;
use Elementor\Utils;
use Elementor\Widget_Base;

/** @noinspection SpellCheckingInspection */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class Image_Comparison extends Widget_Base
{
    public function get_name(): string
    {
        return 'king-addons-image-comparison';
    }

    public function get_title(): string
    {
        return esc_html__('Image Comparison', 'king-addons');
    }

    public function get_icon(): string
    {
        /** @noinspection SpellCheckingInspection */
        return 'king-addons-icon king-addons-image-comparison';
    }

    public function get_style_depends(): array
    {
        return [KING_ADDONS_ASSETS_UNIQUE_KEY . '-image-comparison-style'];
    }

    public function get_categories(): array
    {
        return ['king-addons'];
    }

    public function get_keywords(): array
    {
        return ['image', 'images', 'before', 'after', 'before-after', 'compare', 'comparison', 'slider', 'scroll',
            'photo', 'photos', 'picture', 'scrolling', 'scroller', 'scrollable', 'animation', 'effect', 'animated',
            'vertical', 'horizontal', 'mouse', 'hover', 'over', 'hover over', 'picture', 'king', 'addons', 'mouseover',
            'slide', 'background', 'arrow', 'arrows', 'kingaddons', 'king-addons'];
    }

    public function get_custom_help_url(): string
    {
        return 'https://kingaddons.com/elementor/image-comparison/';
    }

    protected function register_controls(): void
    {
        /** START TAB: CONTENT ===================== */
        /** SECTION: General ===================== */
        $this->start_controls_section(
            'kng_image_comparison_section_general',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('General', 'king-addons'),
            ]
        );

        $this->add_control(
            'kng_image_comparison_info',
            [
                'type' => Controls_Manager::ALERT,
                'alert_type' => 'info',
                'content' => esc_html__('The comparison box uses the height from the Image After by default. It is always good practice to use images with the same width and height for comparison.', 'king-addons'),
            ]
        );

        $this->add_control(
            'kng_image_comparison_image_before',
            [
                'label' => '<b>' . esc_html__('Image Before', 'king-addons') . '</b>',
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
                'name' => 'kng_image_comparison_image_before_size',
                'default' => 'full',
            ]
        );

        $this->add_responsive_control(
            'kng_image_comparison_image_before_fit',
            [
                'label' => esc_html__('Image-Fit', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'none' => esc_html__('None', 'king-addons'),
                    'cover' => esc_html__('Cover', 'king-addons'),
                    'contain' => esc_html__('Contain', 'king-addons'),
                    'fill' => esc_html__('Fill', 'king-addons'),
                ],
                'default' => 'cover',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-image-comparison-container .king-addons-image-comparison-image-before img' => 'object-fit: {{VALUE}};',
                ],
                'separator' => 'after',
            ]
        );

        $this->add_control(
            'kng_image_comparison_image_after',
            [
                'label' => '<b>' . esc_html__('Image After', 'king-addons') . '</b>',
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
                'name' => 'kng_image_comparison_image_after_size',
                'default' => 'full',
            ]
        );

        $this->add_responsive_control(
            'kng_image_comparison_image_after_fit',
            [
                'label' => esc_html__('Image-Fit', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'none' => esc_html__('None', 'king-addons'),
                    'cover' => esc_html__('Cover', 'king-addons'),
                    'contain' => esc_html__('Contain', 'king-addons'),
                    'fill' => esc_html__('Fill', 'king-addons'),
                ],
                'default' => 'none',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-image-comparison-container .king-addons-image-comparison-image-after img' => 'object-fit: {{VALUE}};',
                ],
                'separator' => 'after',
            ]
        );

        $this->add_responsive_control(
            'kng_image_comparison_box_max_height',
            [
                'label' => esc_html__('Box Maximum Height (px)', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'min' => 50,
                'step' => 1,
                'default' => 600,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-image-comparison' => 'max-height: {{SIZE}}px;'
                ],
            ]
        );

        $this->add_control(
            'kng_image_comparison_CSS_filters_head',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Image overlay filters', 'king-addons'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            Group_Control_Css_Filter::get_type(),
            [
                'label' => esc_html__('CSS Filter for Image Before', 'king-addons'),
                'name' => 'kng_image_comparison_image_before_css_filters',
                'selector' => '{{WRAPPER}} .king-addons-image-comparison-image-before',
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            Group_Control_Css_Filter::get_type(),
            [
                'label' => esc_html__('CSS Filter for Image After', 'king-addons'),
                'name' => 'kng_image_comparison_image_after_css_filters',
                'selector' => '{{WRAPPER}} .king-addons-image-comparison-image-after',
            ]
        );

        $this->add_control(
            'kng_image_comparison_slider_line_head',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Slider', 'king-addons'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'kng_image_comparison_slider_line_color',
            [
                'label' => esc_html__('Slider Line Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#FFFFFF',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-image-comparison-slider-line' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'kng_image_comparison_slider_btn_color',
            [
                'label' => esc_html__('Slider Button Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#FFFFFF',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-image-comparison-slider-button' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'kng_image_comparison_box_max_width',
            [
                'label' => esc_html__('Slider Initial Position', 'king-addons'),
                'description' => esc_html__('If there were previous moves of slider, then check the live site for updates on the slider initial position.', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
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
                    '{{WRAPPER}} .king-addons-image-comparison' => '--position: {{SIZE}}{{UNIT}};'
                ],
            ]
        );

        $this->add_control(
            'kng_image_comparison_icon_head',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Icon', 'king-addons'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'kng_image_comparison_btn_icon',
            [
                'label' => esc_html__('Select Icon', 'king-addons'),
                'type' => Controls_Manager::ICONS,
                'skin' => 'inline',
                'label_block' => false,
                'default' => [
                    'value' => 'fas fa-arrows-alt-h',
                    'library' => 'fa-solid',
                ],
            ]
        );

        $this->add_control(
            'kng_image_comparison_btn_color',
            [
                'label' => esc_html__('Icon Color (optional)', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#000000',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-image-comparison-slider-button' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .king-addons-image-comparison-slider-button svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'kng_image_comparison_btn_size',
            [
                'label' => esc_html__('Icon Size (px)', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'step' => 1,
                'default' => 22,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-image-comparison-slider-button' => 'font-size: {{VALUE}}px;',
                    '{{WRAPPER}} .king-addons-image-comparison-slider-button svg' => 'width: {{VALUE}}px; height: {{VALUE}}px;',
                ],
            ]
        );

        $this->add_control(
            'kng_image_comparison_icon_box_head',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Icon Box', 'king-addons'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'kng_image_comparison_btn_border',
                'label' => esc_html__('Border', 'king-addons'),
                'selector' => '{{WRAPPER}} .king-addons-image-comparison-slider-button',
                'fields_options' => [
                    'border' => [
                        'default' => 'none',
                    ],
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'kng_image_comparison_btn_shadow',
                'label' => esc_html__('Shadow', 'king-addons'),
                'selector' => '{{WRAPPER}} .king-addons-image-comparison-slider-button',
            ]
        );

        $this->add_responsive_control(
            'kng_image_comparison_btn_padding',
            [
                'label' => esc_html__('Padding', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['em', 'px'],
                'default' => [
                    'top' => 15,
                    'right' => 15,
                    'bottom' => 15,
                    'left' => 15,
                    'unit' => 'px',
                    'isLinked' => true,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-image-comparison-slider-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->add_responsive_control(
            'kng_image_comparison_btn_border_radius',
            [
                'label' => esc_html__('Border Radius', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'vw', 'vh'],
                'default' => [
                    'top' => 100,
                    'right' => 100,
                    'bottom' => 100,
                    'left' => 100,
                    'unit' => 'vw',
                    'isLinked' => true,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-image-comparison-slider-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->add_control(
            'kng_image_comparison_box_shadow_head',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Main Box', 'king-addons'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'kng_image_comparison_box_border',
                'label' => esc_html__('Border', 'king-addons'),
                'selector' => '{{WRAPPER}} .king-addons-image-comparison',
                'fields_options' => [
                    'border' => [
                        'default' => 'none',
                    ],
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'kng_image_comparison_box_shadow',
                'label' => esc_html__('Shadow', 'king-addons'),
                'selector' => '{{WRAPPER}} .king-addons-image-comparison',
            ]
        );

        $this->add_responsive_control(
            'kng_image_comparison_border_radius',
            [
                'label' => esc_html__('Border Radius', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-image-comparison' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->end_controls_section();
        /** END SECTION: General ===================== */
        /** END TAB: CONTENT ===================== */
    }

    protected function render(): void
    {
        $settings = $this->get_settings_for_display();
        $element_ID = $this->get_id();

        // Define allowed tags and attributes
        $allowed_tags = wp_kses_allowed_html('post');
        $allowed_tags['img']['srcset'] = true; // Allow srcset attribute for img tag
        $allowed_tags['img']['sizes'] = true; // Allow sizes attribute for img tag
        $allowed_tags['img']['decoding'] = true; // Allow decoding attribute for img tag
        $allowed_tags['img']['loading'] = true; // Allow loading attribute for img tag. It is for the lazy loading possibility.

        ?>
        <div class="king-addons-image-comparison king-addons-image-comparison-<?php echo esc_attr($element_ID); ?>">
            <div class="king-addons-image-comparison-container">
                <div class="king-addons-image-comparison-image-before">
                    <?php
                    $image_html_before = Group_Control_Image_Size::get_attachment_image_html($settings, 'kng_image_comparison_image_before_size', 'kng_image_comparison_image_before');
                    echo wp_kses($image_html_before, $allowed_tags);
                    ?>
                </div>
                <div class="king-addons-image-comparison-image-after">
                    <?php
                    $image_html_after = Group_Control_Image_Size::get_attachment_image_html($settings, 'kng_image_comparison_image_after_size', 'kng_image_comparison_image_after');
                    echo wp_kses($image_html_after, $allowed_tags);
                    ?>
                </div>
            </div>
            <input type="range" min="0" max="100" value="50"
                   aria-label="<?php echo esc_html__('Percentage of the before image shown.', 'king-addons'); ?>"
                   class="king-addons-image-comparison-slider king-addons-image-comparison-slider-<?php echo esc_attr($element_ID); ?>"
            />
            <div class="king-addons-image-comparison-slider-line" aria-hidden="true"></div>
            <div class="king-addons-image-comparison-slider-button" aria-hidden="true">
                <?php Icons_Manager::render_icon($settings['kng_image_comparison_btn_icon']); ?>
            </div>
        </div>
        <?php
        $inline_js = "const container_" . esc_js($element_ID) . " = document.querySelector('.king-addons-image-comparison-" . esc_js($element_ID) . "');
            document.querySelector('.king-addons-image-comparison-slider-" . esc_js($element_ID) . "').addEventListener('input', (e) => {
                container_" . esc_js($element_ID) . ".style.setProperty('--position', " . '`${e.target.value}%`' . ");
            });";
        wp_print_inline_script_tag($inline_js);
    }
}