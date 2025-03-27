<?php /** @noinspection SpellCheckingInspection, DuplicatedCode, PhpUnused */

namespace King_Addons;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Image_Size;
use Elementor\Repeater;
use Elementor\Utils;
use Elementor\Widget_Base;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class Hovering_Image_Stack extends Widget_Base
{
    public function get_name(): string
    {
        return 'king-addons-hovering-image-stack';
    }

    public function get_title(): string
    {
        return esc_html__('Hovering Image Stack', 'king-addons');
    }

    public function get_icon(): string
    {
        return 'king-addons-icon king-addons-hovering-image-stack';
    }

    public function get_style_depends(): array
    {
        return [KING_ADDONS_ASSETS_UNIQUE_KEY . '-hovering-image-stack-style'];
    }

    public function get_categories(): array
    {
        return ['king-addons'];
    }

    public function get_keywords(): array
    {
        return ['hovering image stack', 'image stack', 'image', 'images', 'before', 'after', 'hovering', 'compare',
            'hover', 'slider', 'scroll', 'box',
            'photo', 'photos', 'picture', 'scrolling', 'scroller', 'scrollable', 'animation', 'effect', 'animated',
            'image hover over', 'image hover', 'hover box', 'image box', 'image layout', 'layout', 'image hover box',
            'vertical', 'horizontal', 'mouse', 'hover', 'over', 'hover over', 'picture', 'king', 'addons', 'mouseover',
            'slide', 'background', 'kingaddons', 'king-addons', 'info box', 'info', 'cta', 'banner',
            'layout', 'animated box', 'hover text', 'text box', 'text banner'];
    }

    public function get_custom_help_url(): string
    {
        return 'https://kingaddons.com/elementor/hovering-image-stack/';
    }

    public function has_widget_inner_wrapper(): bool {
        return true;
    }

    protected function register_controls(): void
    {
        /** SECTION: CONTENT */
        $this->start_controls_section(
            'king_addons_hovering_image_stack_section_image_stack',
            [
                'label' => esc_html__('Image Stack', 'king-addons'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'king_addons_hovering_image_stack_image',
            [
                'label' => esc_html__('Image', 'king-addons'),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
                'dynamic' => [
                    'active' => true,
                ],
            ]
        );
        $repeater->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name' => 'king_addons_hovering_image_stack_image_size',
                'default' => 'full',
                'separator' => 'before',
                'dynamic' => [
                    'active' => true,
                ],
            ]
        );

        $repeater->add_control(
            'king_addons_hovering_image_stack_link',
            [
                'label' => esc_html__('Link', 'king-addons'),
                'type' => Controls_Manager::URL,
                'label_block' => true,
                'placeholder' => esc_html__('https://example.com', 'king-addons'),
                'dynamic' => [
                    'active' => true,
                ]
            ]
        );

        $repeater->add_responsive_control(
            'king_addons_hovering_image_stack_image_offset_y',
            [
                'label' => esc_html__('Offset Y', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => -1000,
                        'max' => 1000,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => -100,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 0,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-hovering-image-stack-item{{CURRENT_ITEM}}' => 'top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $repeater->add_responsive_control(
            'king_addons_hovering_image_stack_image_offset_x',
            [
                'label' => esc_html__('Offset X', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => -1000,
                        'max' => 1000,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => -100,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 0,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-hovering-image-stack-item{{CURRENT_ITEM}}' => 'left: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $repeater->add_responsive_control(
            'king_addons_hovering_image_stack_image_width',
            [
                'label' => esc_html__('Width (px)', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'step' => 1,
                'min' => 1,
                'default' => 200,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-hovering-image-stack-item{{CURRENT_ITEM}} img' => 'width: {{VALUE}}px;',
                ],
                'separator' => 'before',
            ]
        );

        $repeater->add_responsive_control(
            'king_addons_hovering_image_stack_image_height',
            [
                'label' => esc_html__('Height (px)', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'step' => 1,
                'min' => 1,
                'default' => 200,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-hovering-image-stack-item{{CURRENT_ITEM}} img' => 'height: {{VALUE}}px;',
                ],
            ]
        );

        $repeater->add_responsive_control(
            'king_addons_hovering_image_stack_image_fit',
            [
                'label' => esc_html__('Image Fit', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => array(
                    'cover' => esc_html__('Cover', 'king-addons'),
                    'fill' => esc_html__('Fill', 'king-addons'),
                    'contain' => esc_html__('Contain', 'king-addons'),
                ),
                'default' => 'cover',
                'selectors' => array(
                    '{{WRAPPER}} .king-addons-hovering-image-stack-item{{CURRENT_ITEM}} img' => 'object-fit: {{VALUE}}',
                ),
            ]
        );

        $repeater->add_responsive_control(
            'king_addons_hovering_image_stack_image_fit_position',
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
                    '{{WRAPPER}} .king-addons-hovering-image-stack-item{{CURRENT_ITEM}} img' => 'object-position: {{VALUE}};',
                ],
            ]
        );

        $repeater->add_responsive_control(
            'king_addons_hovering_image_stack_image_z_index',
            [
                'label' => esc_html__('Z-Index', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'step' => 1,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-hovering-image-stack-item{{CURRENT_ITEM}}' => 'z-index: {{VALUE}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'king_addons_hovering_image_stack_image_list',
            [
                'show_label' => true,
                'label' => esc_html__('Items', 'king-addons'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'title_field' => '{{{ name }}}',
                'default' => [
                    [
                        'king_addons_hovering_image_stack_image' => [
                            'url' => Utils::get_placeholder_image_src(),
                        ],
                        'king_addons_hovering_image_stack_image_offset_y' => [
                            'size' => 0,
                            'unit' => 'px',
                        ],
                        'king_addons_hovering_image_stack_image_offset_x' => [
                            'size' => 35,
                            'unit' => 'px',
                        ],
                    ],
                    [
                        'king_addons_hovering_image_stack_image' => [
                            'url' => Utils::get_placeholder_image_src(),
                        ],
                        'king_addons_hovering_image_stack_image_offset_y' => [
                            'size' => 250,
                            'unit' => 'px',
                        ],
                        'king_addons_hovering_image_stack_image_offset_x' => [
                            'size' => 0,
                            'unit' => 'px',
                        ],
                    ],
                    [
                        'king_addons_hovering_image_stack_image' => [
                            'url' => Utils::get_placeholder_image_src(),
                        ],
                        'king_addons_hovering_image_stack_image_offset_y' => [
                            'size' => 100,
                            'unit' => 'px',
                        ],
                        'king_addons_hovering_image_stack_image_offset_x' => [
                            'size' => 180,
                            'unit' => 'px',
                        ],
                    ],
                ],
            ]
        );

        $this->add_control(
            'king_addons_hovering_image_stack_image_infinite_animation',
            [
                'label' => esc_html__('Infinite Animation', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '' => esc_html__('None', 'king-addons'),
                    'king-addons-hovering-image-stack-bounce-sm' => esc_html__('Bounce Small', 'king-addons'),
                    'king-addons-hovering-image-stack-bounce-md' => esc_html__('Bounce Medium', 'king-addons'),
                    'king-addons-hovering-image-stack-bounce-lg' => esc_html__('Bounce Large', 'king-addons'),
                    'king-addons-hovering-image-stack-fade' => esc_html__('Fade', 'king-addons'),
                    'king-addons-hovering-image-stack-rotating' => esc_html__('Rotating', 'king-addons'),
                    'king-addons-hovering-image-stack-rotating-inverse' => esc_html__('Rotating inverse', 'king-addons'),
                    'king-addons-hovering-image-stack-scale-sm' => esc_html__('Scale Small', 'king-addons'),
                    'king-addons-hovering-image-stack-scale-md' => esc_html__('Scale Medium', 'king-addons'),
                    'king-addons-hovering-image-stack-scale-lg' => esc_html__('Scale Large', 'king-addons'),
                ],
                'default' => 'king-addons-hovering-image-stack-bounce-sm',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'king_addons_hovering_image_stack_animation_speed',
            [
                'label' => esc_html__('Animation speed (ms)', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'step' => 100,
                'default' => 6000,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-hovering-image-stack-wrapper' => '--king-addons-hovering-image-animation-speed:{{SIZE}}ms',
                ],
            ]
        );

        $this->add_control(
            'king_addons_hovering_image_stack_hover_animation_style',
            [
                'label' => esc_html__('Hover Animation', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'none' => esc_html__('None', 'king-addons'),
                    'king-addons-hovering-image-img-stack-fly-sm' => esc_html__('Fly Small', 'king-addons'),
                    'king-addons-hovering-image-img-stack-fly' => esc_html__('Fly Medium', 'king-addons'),
                    'king-addons-hovering-image-img-stack-fly-lg' => esc_html__('Fly Large', 'king-addons'),
                    'king-addons-hovering-image-img-stack-scale-sm' => esc_html__('Scale Small', 'king-addons'),
                    'king-addons-hovering-image-img-stack-scale' => esc_html__('Scale Medium', 'king-addons'),
                    'king-addons-hovering-image-img-stack-scale-lg' => esc_html__('Scale Large', 'king-addons'),
                    'king-addons-hovering-image-img-stack-scale-inverse-sm' => esc_html__('Scale Inverse Small', 'king-addons'),
                    'king-addons-hovering-image-img-stack-scale-inverse' => esc_html__('Scale Inverse Medium', 'king-addons'),
                    'king-addons-hovering-image-img-stack-scale-inverse-lg' => esc_html__('Scale Inverse Large', 'king-addons'),
                ],
                'default' => 'king-addons-hovering-image-img-stack-scale-sm',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'king_addons_hovering_image_stack_hover_animation_speed',
            [
                'label' => esc_html__('Hover animation speed (ms)', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'step' => 10,
                'default' => 300,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-hovering-image-stack-wrapper' => '--king-addons-hovering-image-hover-animation-speed:{{SIZE}}ms',
                ],
            ]
        );

        $this->end_controls_section();
        /** END SECTION: CONTENT */

        /** SECTION: STYLE */
        $this->start_controls_section(
            'king_addons_hovering_image_stack_section_image_stack_style',
            [
                'label' => esc_html__('General', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'king_addons_hovering_image_stack_image_container_width',
            [
                'label' => esc_html__('Width', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 550,
                    'unit' => 'px',
                ],
                'tablet_default' => [
                    'unit' => '%',
                ],
                'mobile_default' => [
                    'unit' => '%',
                ],
                'size_units' => ['px', '%', 'vw'],
                'range' => [
                    '%' => [
                        'min' => 1,
                        'max' => 100,
                    ],
                    'px' => [
                        'min' => 1,
                        'max' => 2000,
                    ],
                    'vw' => [
                        'min' => 1,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-hovering-image-stack-wrapper' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'king_addons_hovering_image_stack_image_container_height',
            [
                'label' => esc_html__('Minimum Height', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'vh', 'em'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    'em' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                    'vh' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'size' => 550,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-hovering-image-stack-wrapper' => 'min-height: {{SIZE}}{{UNIT}}',
                ]
            ]
        );

        $this->add_responsive_control(
            'king_addons_hovering_image_stack_image_layers_overflow',
            [
                'label' => esc_html__('Overflow', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => array(
                    'visible' => esc_html__('Visible', 'king-addons'),
                    'hidden' => esc_html__('Hidden', 'king-addons'),
                    'scroll' => esc_html__('Scroll', 'king-addons'),
                ),
                'default' => 'visible',
                'selectors' => array(
                    '{{WRAPPER}} .king-addons-hovering-image-stack-wrapper' => 'overflow: {{VALUE}}',
                ),
            ]
        );

        $this->add_responsive_control(
            'king_addons_hovering_image_stack_image_container_align',
            [
                'label' => esc_html__('Alignment', 'king-addons'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__('Left', 'king-addons'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'king-addons'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__('Right', 'king-addons'),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'toggle' => true,
                'default' => 'center',
                'separator' => 'before',
                'selectors' => [
                    '{{WRAPPER}} .elementor-widget-container' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'king_addons_hovering_image_stack_hr',
            [
                'type' => Controls_Manager::DIVIDER,
            ]
        );

        $this->start_controls_tabs('king_addons_hovering_image_stack_tabs_hover_style');

        $this->start_controls_tab(
            'king_addons_hovering_image_stack_tab_button_normal',
            [
                'label' => esc_html__('Normal', 'king-addons'),
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'king_addons_hovering_image_stack_img_box_shadow',
                'label' => esc_html__('Box Shadow', 'king-addons'),
                'selector' => '{{WRAPPER}} .king-addons-hovering-image-stack-item img',

            ]
        );
        $this->end_controls_tab();

        $this->start_controls_tab(
            'king_addons_hovering_image_stack_tab_button_hover',
            [
                'label' => esc_html__('Hover', 'king-addons'),
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'king_addons_hovering_image_stack_img_box_shadow_hover',
                'label' => esc_html__('Box Shadow on Hover', 'king-addons'),
                'selector' => '{{WRAPPER}} .king-addons-hovering-image-stack-item img:hover',

            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'king_addons_hovering_image_stack_border',
                'label' => esc_html__('Border', 'king-addons'),
                'selector' => '{{WRAPPER}} .king-addons-hovering-image-stack-item img',
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'king_addons_hovering_image_stack_image_border_radius',
            [
                'label' => esc_html__('Border Radius', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'default' => [
                    'top' => 5,
                    'right' => 5,
                    'bottom' => 5,
                    'left' => 5,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-hovering-image-stack-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .king-addons-hovering-image-stack-item img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
        /** END SECTION: STYLE */
    }

    protected function render(): void
    {
        $settings = $this->get_settings_for_display();
        if (empty($settings['king_addons_hovering_image_stack_image_list'])) {
            return;
        }

        echo '<div class="king-addons-hovering-image-stack-wrapper">';

        foreach ($settings['king_addons_hovering_image_stack_image_list'] as $item) {
            $dynamic_class = 'elementor-repeater-item-' . esc_attr($item['_id']);
            $infinite_animation_class = esc_attr($settings['king_addons_hovering_image_stack_image_infinite_animation']);
            $hover_animation_class = esc_attr($settings['king_addons_hovering_image_stack_hover_animation_style']);

            if (isset($item['king_addons_hovering_image_stack_link']) && !empty($item['king_addons_hovering_image_stack_link']['url'])) {
                echo '<a class="king-addons-hovering-image-stack-item ' . esc_attr($dynamic_class) . ' ' . esc_attr($infinite_animation_class) . '" href="' . esc_url($item['king_addons_hovering_image_stack_link']['url']) . '"';
                if (!empty($item['king_addons_hovering_image_stack_link']['is_external'])) {
                    echo ' target="_blank"';
                }
                if (!empty($item['king_addons_hovering_image_stack_link']['nofollow'])) {
                    echo ' rel="nofollow"';
                }
                echo '>';
            } else {
                echo '<div class="king-addons-hovering-image-stack-item ' . esc_attr($dynamic_class) . ' ' . esc_attr($infinite_animation_class) . '">';
            }

            if (!empty($item['king_addons_hovering_image_stack_image']['id'])) {
                $image_src = Group_Control_Image_Size::get_attachment_image_src($item['king_addons_hovering_image_stack_image']['id'], 'king_addons_hovering_image_stack_image_size', $item);
                $image_alt = $item['king_addons_hovering_image_stack_image']['alt'];
                echo '<img src="' . esc_url($image_src) . '" alt="' . esc_attr($image_alt) . '" class="king-addons-hovering-image-stack-img ' . esc_attr($hover_animation_class) . '"/>';
            } else {
                if ('custom' !== $item['king_addons_hovering_image_stack_image_size_size']) {
                    $width = esc_attr(get_option($item['king_addons_hovering_image_stack_image_size_size'] . '_size_w'));
                    $height = esc_attr(get_option($item['king_addons_hovering_image_stack_image_size_size'] . '_size_h'));
                } else {
                    $width = esc_attr($item['king_addons_hovering_image_stack_image_size_custom_dimension']['width']);
                    $height = esc_attr($item['king_addons_hovering_image_stack_image_size_custom_dimension']['height']);
                }
                $height = '0' == $height ? 'auto' : $height . 'px';

                $image_url = !empty($item['king_addons_hovering_image_stack_image']['url']) ? esc_url($item['king_addons_hovering_image_stack_image']['url']) : esc_url(Utils::get_placeholder_image_src());
                echo '<img src="' . esc_url($image_url) . '" style="width: ' . esc_attr($width) . 'px; height: ' . esc_attr($height) . ';" class="king-addons-hovering-image-stack-img ' . esc_attr($hover_animation_class) . '"/>';
            }

            if (isset($item['king_addons_hovering_image_stack_link']) && !empty($item['king_addons_hovering_image_stack_link']['url'])) {
                echo '</a>';
            } else {
                echo '</div>';
            }
        }

        echo '</div>';
    }
}