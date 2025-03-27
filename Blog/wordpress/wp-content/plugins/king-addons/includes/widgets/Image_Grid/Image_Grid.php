<?php /** @noinspection SpellCheckingInspection, DuplicatedCode, PhpUnused */

namespace King_Addons;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Image_Size;
use Elementor\Repeater;
use Elementor\Utils;
use Elementor\Widget_Base;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class Image_Grid extends Widget_Base
{
    public function get_name(): string
    {
        return 'king-addons-image-grid';
    }

    public function get_title(): string
    {
        return esc_html__('Image Grid', 'king-addons');
    }

    public function get_icon(): string
    {
        return 'king-addons-icon king-addons-image-grid';
    }

    public function get_script_depends(): array
    {
        return [KING_ADDONS_ASSETS_UNIQUE_KEY . '-isotope-isotope', KING_ADDONS_ASSETS_UNIQUE_KEY . '-imagesloaded-imagesloaded'];
    }

    public function get_style_depends(): array
    {
        return [KING_ADDONS_ASSETS_UNIQUE_KEY . '-image-grid-style'];
    }

    public function get_categories(): array
    {
        return ['king-addons'];
    }

    public function get_keywords(): array
    {
        return ['media', 'image', 'video', 'gallery', 'grid', 'masonry', 'fit', 'fitRows', 'rows', 'images', 'even',
            'card', 'carousel', 'slider', 'scroller', 'swiper', 'content', 'button', 'dot', 'dots', 'navigation',
            'cards', 'wheel', 'touch', 'nav', 'navigation', 'animation', 'effect', 'animated', 'template', 'link',
            'left', 'right', 'top', 'bottom', 'vertical', 'horizontal', 'mouse', 'dragging', 'hover', 'over',
            'hover over', 'picture', 'float', 'floating', 'sticky', 'click', 'target', 'point', 'king', 'addons',
            'mouseover', 'page', 'blog posts', 'kingaddons', 'king-addons', 'team', 'members', 'testimonial',
            'king addons', 'testimonials', 'reviews', ' team memebers', 'drag', 'scroll', 'scrolling', 'tabs', 'tab'];
    }

    public function get_custom_help_url(): string
    {
        return 'https://kingaddons.com/elementor/image-grid/';
    }

    protected function register_controls(): void
    {
        $this->start_controls_section(
            'kng_image_grid_content_section_gallery_settings',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Grid Settings', 'king-addons'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'kng_image_grid_grid_layout',
            array(
                'label' => esc_html__('Grid Layout', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'fitRows' => esc_html__('Fit Rows', 'king-addons'),
                    'masonry' => esc_html__('Masonry', 'king-addons'),
                ],
                'default' => 'masonry',
            )
        );

        $this->add_responsive_control(
            'kng_image_grid_fitrows_image_height',
            [
                'label' => esc_html__('Image Height (px)', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'step' => 1,
                'min' => 0,
                'default' => 200,
                'render_type' => 'template',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-image-grid-item' => 'height: {{SIZE}}px;',
                ],
                'condition' => [
                    'kng_image_grid_grid_layout' => 'fitRows'
                ]
            ]
        );

        $this->add_responsive_control(
            'kng_image_grid_fitrows_image_fit',
            [
                'label' => esc_html__('Image Fit', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'cover' => esc_html__('Cover', 'king-addons'),
                    'contain' => esc_html__('Contain', 'king-addons'),
                ],
                'default' => 'cover',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-image-grid-item img' => 'object-fit: {{VALUE}};',
                ],
                'condition' => [
                    'kng_image_grid_grid_layout' => 'fitRows'
                ]
            ]
        );

        $this->add_responsive_control(
            'kng_image_grid_fitrows_image_fit_position',
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
                    '{{WRAPPER}} .king-addons-image-grid-item img' => 'object-position: {{VALUE}};',
                ],
                'condition' => [
                    'kng_image_grid_grid_layout' => 'fitRows'
                ]
            ]
        );

        $this->add_responsive_control(
            'kng_image_grid_number_of_columns',
            [
                'label' => esc_html__('Number of columns', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'step' => 1,
                'min' => 1,
                'render_type' => 'template',
                'desktop_default' => 4,
                'tablet_default' => 3,
                'mobile_default' => 2,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-image-grid-item' => 'width: calc(100% / {{SIZE}});',
                ],
            ]
        );

        $this->add_responsive_control(
            'kng_image_grid_space_between',
            [
                'label' => esc_html__('Space between (px)', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'step' => 1,
                'min' => 0,
                'desktop_default' => 5,
                'tablet_default' => 5,
                'mobile_default' => 5,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-image-grid-item' => 'padding: {{SIZE}}px;',
                ],
            ]
        );

        $this->end_controls_section();

        /** SECTION: Images ===================== */
        $this->start_controls_section(
            'kng_image_grid_content_section_items',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Items', 'king-addons'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'kng_image_grid_image',
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
                'name' => 'kng_image_grid_image_size',
                'default' => 'full',
            ]
        );

        $default_cards = [
            'kng_image_grid_image' => Utils::get_placeholder_image_src(),
        ];

        /** @noinspection HtmlUnknownTarget */
        $this->add_control(
            'kng_image_grid_content_items',
            [
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => array_fill(0, 8, $default_cards),
                'title_field' => '<img alt="" class="king-addons-repeater-list-img-icon" src="{{kng_image_grid_image.url}}"> ',
            ]
        );

        $this->end_controls_section();
        /** END SECTION: Images ===================== */

        /** STYLE SECTION: Images ===================== */
        $this->start_controls_section(
            'kng_image_grid_style_section_image',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Image', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'kng_image_grid_image_scale_switcher',
            [
                'label' => esc_html__('Scale image on hover', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_responsive_control(
            'kng_image_grid_image_scale_hover',
            [
                'label' => esc_html__('Scale size', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'step' => 0.01,
                'default' => 1.1,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-image-grid-item img:hover' => 'transform: scale({{SIZE}});'
                ],
                'condition' => [
                    'kng_image_grid_image_scale_switcher' => 'yes',
                ]
            ]
        );

        $this->add_responsive_control(
            'kng_image_grid_image_transition',
            [
                'label' => esc_html__('Transition duration on hover (ms)', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'step' => 10,
                'default' => 300,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-image-grid-item img' => 'transition: all {{SIZE}}ms cubic-bezier(.25,.46,.45,.94);'
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Css_Filter::get_type(),
            [
                'label' => esc_html__('CSS Filter', 'king-addons'),
                'name' => 'kng_image_grid_image_css_filters',
                'selector' => '{{WRAPPER}} .king-addons-image-grid-item img',
            ]
        );

        $this->add_group_control(
            Group_Control_Css_Filter::get_type(),
            [
                'label' => esc_html__('CSS Filter for image on hover', 'king-addons'),
                'name' => 'kng_image_grid_image_css_filters_hover',
                'selector' => '{{WRAPPER}} .king-addons-image-grid-item img:hover',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'kng_image_grid_image_box_shadow',
                'selector' => '{{WRAPPER}} .king-addons-image-grid-item .king-addons-image-grid-item-inner',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'kng_image_grid_image_border',
                'selector' => '{{WRAPPER}} .king-addons-image-grid-item .king-addons-image-grid-item-inner',
                'fields_options' => [
                    'border' => [
                        'default' => 'none',
                    ],
                ],
            ]
        );

        $this->add_responsive_control(
            'kng_image_grid_image_border_radius',
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
                    '{{WRAPPER}} .king-addons-image-grid-item .king-addons-image-grid-item-inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );
        
        $this->end_controls_section();
        /** END SECTION: Images ===================== */

    }

    protected function render(): void
    {
        $settings = $this->get_settings();
        $this_ID = $this->get_id();

        // Define allowed tags and attributes
        $allowed_tags = wp_kses_allowed_html('post');
        $allowed_tags['img']['srcset'] = true;
        $allowed_tags['img']['sizes'] = true;
        $allowed_tags['img']['decoding'] = true;
        $allowed_tags['img']['loading'] = true;

        echo '<div id="king-addons-image-grid-' . esc_attr($this_ID) . '" class="king-addons-image-grid">';

        /** @noinspection PhpUnusedLocalVariableInspection */
        foreach ($settings['kng_image_grid_content_items'] as $key => $item) {
            echo '<div class="king-addons-image-grid-item king-addons-image-grid-item-' . esc_attr($this_ID) . '">';
            echo '<div class="king-addons-image-grid-item-inner">';

            $image_html = Group_Control_Image_Size::get_attachment_image_html($item, 'kng_image_grid_image_size', 'kng_image_grid_image');
            echo wp_kses($image_html, $allowed_tags);

            echo '</div>';
            echo '</div>';
        }

        echo '</div>';

        $js_grid = "
            (function ($) {
                if (document.readyState === 'complete') {
                    let " . '$grid' . "
                    = $('#king-addons-image-grid-" . esc_js($this_ID) . "').isotope({
                        itemSelector: '.king-addons-image-grid-item-" . esc_js($this_ID) . "',
                        layoutMode: '" . esc_js($settings['kng_image_grid_grid_layout']) . "',
                    });

                    " . '$grid' . ".imagesLoaded().progress(function () {
                        " . '$grid' . ".isotope('layout');
                    });

                } else {
                    window.addEventListener('load', function () {
                        
                        let " . '$grid' . "
                        = $('#king-addons-image-grid-" . esc_js($this_ID) . "').isotope({
                            itemSelector: '.king-addons-image-grid-item-" . esc_js($this_ID) . "',
                            layoutMode: '" . esc_js($settings['kng_image_grid_grid_layout']) . "',
                        });

                        " . '$grid' . ".imagesLoaded().progress(function () {
                            " . '$grid' . ".isotope('layout');
                        });

                    });
                }
            })(jQuery);
        ";
        wp_print_inline_script_tag($js_grid);
    }
}