<?php /** @noinspection SpellCheckingInspection, DuplicatedCode, PhpUnused */

namespace King_Addons;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Border;
use Elementor\Widget_Base;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class Content_Flip_Box extends Widget_Base
{
    public function get_name(): string
    {
        return 'king-addons-content-flip-box';
    }

    public function get_title(): string
    {
        return esc_html__('Content Flip Box', 'king-addons');
    }

    public function get_icon(): string
    {
        return 'king-addons-icon king-addons-content-flip-box';
    }

    public function get_style_depends(): array
    {
        return [KING_ADDONS_ASSETS_UNIQUE_KEY . '-content-flip-box-style'];
    }

    public function get_categories(): array
    {
        return ['king-addons'];
    }

    public function get_keywords(): array
    {
        return ['image', 'images', 'before', 'after', 'before-after', 'compare', 'hover', 'slider', 'scroll', 'box',
            'photo', 'photos', 'picture', 'scrolling', 'scroller', 'scrollable', 'animation', 'effect', 'animated',
            'image hover over', 'image hover', 'hover box', 'image box', 'image layout', 'layout', 'image hover box',
            'vertical', 'horizontal', 'mouse', 'hover', 'over', 'hover over', 'picture', 'king', 'addons', 'mouseover',
            'slide', 'background', 'arrow', 'arrows', 'kingaddons', 'king-addons', 'info box', 'info', 'cta', 'banner',
            'layout', 'animated box', 'hover text', 'text box', 'text banner', 'flip', 'content', 'flip box',
            'content flip', 'content flip box'];
    }

    public function get_custom_help_url(): string
    {
        return 'https://kingaddons.com/elementor/hovering-image-stack/';
    }

    protected function register_controls(): void
    {

        $this->start_controls_section(
            'king_addons_content_flip_box_section_front',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Front Side', 'king-addons'),
            ]
        );

        $this->add_control(
            'king_addons_content_flip_box_front_icon_type',
            [
                'label' => esc_html__('Select Icon Type', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'icon',
                'options' => [
                    'none' => esc_html__('None', 'king-addons'),
                    'icon' => esc_html__('Icon', 'king-addons'),
                    'image' => esc_html__('Image', 'king-addons'),
                ],
            ]
        );

        $this->add_control(
            'king_addons_content_flip_box_front_image',
            [
                'label' => esc_html__('Image', 'king-addons'),
                'type' => Controls_Manager::MEDIA,
                'dynamic' => [
                    'active' => true,
                ],
                'condition' => [
                    'king_addons_content_flip_box_front_icon_type' => 'image',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name' => 'king_addons_content_flip_box_front_image_size',
                'default' => 'full',
                'condition' => [
                    'king_addons_content_flip_box_front_icon_type' => 'image',
                ],
            ]
        );

        $this->add_control(
            'king_addons_content_flip_box_front_icon',
            [
                'label' => esc_html__('Icon', 'king-addons'),
                'type' => Controls_Manager::ICONS,
                'skin' => 'inline',
                'label_block' => false,
                'default' => [
                    'value' => 'fas fa-crown',
                    'library' => 'fa-solid',
                ],
                'condition' => [
                    'king_addons_content_flip_box_front_icon_type' => 'icon',
                ],
            ]
        );

        $this->add_control(
            'king_addons_content_flip_box_front_trigger',
            [
                'label' => esc_html__('Trigger', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'hover',
                'options' => [
                    'hover' => esc_html__('Hover on box', 'king-addons'),
                    'box' => esc_html__('Click on box', 'king-addons'),
                    'button' => esc_html__('Click on button', 'king-addons'),
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'king_addons_content_flip_box_front_button_text',
            [
                'label' => esc_html__('Front Button', 'king-addons'),
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => esc_html__('Front Button', 'king-addons'),
                'condition' => [
                    'king_addons_content_flip_box_front_trigger' => 'button',
                ],
            ]
        );

        $this->add_control(
            'king_addons_content_flip_box_front_button_icon',
            [
                'label' => esc_html__('Button Icon', 'king-addons'),
                'type' => Controls_Manager::ICONS,
                'skin' => 'inline',
                'label_block' => false,
                'default' => [
                    'value' => 'fas fa-crown',
                    'library' => 'fa-solid',
                ],
                'condition' => [
                    'king_addons_content_flip_box_front_trigger' => 'button',
                ],
            ]
        );

        $this->add_control(
            'king_addons_content_flip_box_front_title',
            [
                'label' => esc_html__('Front Title', 'king-addons'),
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => esc_html__('Front Title', 'king-addons'),
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'king_addons_content_flip_box_front_description',
            [
                'label' => esc_html__('Description', 'king-addons'),
                'type' => Controls_Manager::WYSIWYG,
                'default' => esc_html__('Hover over the mouse here to see the back side content. Lorem ipsum dolor sit amet, consectetur adipiscing elit. ', 'king-addons'),
                'separator' => 'before',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'king_addons_content_flip_box_section_back',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Back Side', 'king-addons'),
            ]
        );

        $this->add_control(
            'king_addons_content_flip_box_back_icon_type',
            [
                'label' => esc_html__('Select Icon Type', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'icon',
                'options' => [
                    'none' => esc_html__('None', 'king-addons'),
                    'icon' => esc_html__('Icon', 'king-addons'),
                    'image' => esc_html__('Image', 'king-addons'),
                ],
            ]
        );

        $this->add_control(
            'king_addons_content_flip_box_back_image',
            [
                'label' => esc_html__('Image', 'king-addons'),
                'type' => Controls_Manager::MEDIA,
                'dynamic' => [
                    'active' => true,
                ],
                'condition' => [
                    'king_addons_content_flip_box_back_icon_type' => 'image',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name' => 'king_addons_content_flip_box_back_image_size',
                'default' => 'full',
                'condition' => [
                    'king_addons_content_flip_box_back_icon_type' => 'image',
                ],
            ]
        );

        $this->add_control(
            'king_addons_content_flip_box_back_icon',
            [
                'label' => esc_html__('Icon', 'king-addons'),
                'type' => Controls_Manager::ICONS,
                'skin' => 'inline',
                'label_block' => false,
                'default' => [
                    'value' => 'fas fa-crown',
                    'library' => 'fa-solid',
                ],
                'condition' => [
                    'king_addons_content_flip_box_back_icon_type' => 'icon',
                ],
            ]
        );

        $this->add_control(
            'king_addons_content_flip_box_back_link_type',
            [
                'label' => esc_html__('Link on element', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'none' => esc_html__('None', 'king-addons'),
                    'title' => esc_html__('Title', 'king-addons'),
                    'box' => esc_html__('Whole Box', 'king-addons'),
                    'button' => esc_html__('Button', 'king-addons'),
                ],
                'default' => 'none',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'king_addons_content_flip_box_back_link',
            [
                'type' => Controls_Manager::URL,
                'dynamic' => [
                    'active' => true,
                ],
                'label' => esc_html__('Link URL', 'king-addons'),
                'placeholder' => esc_html__('https://example.com', 'king-addons'),
                'default' => [
                    'url' => '#',
                ],
                'condition' => [
                    'king_addons_content_flip_box_back_link_type!' => 'none',
                ],
            ]
        );

        $this->add_control(
            'king_addons_content_flip_box_back_button_text',
            [
                'label' => esc_html__('Back Button', 'king-addons'),
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => esc_html__('Back Button', 'king-addons'),
                'condition' => [
                    'king_addons_content_flip_box_back_link_type' => ['button'],
                ],
            ]
        );

        $this->add_control(
            'king_addons_content_flip_box_back_button_icon',
            [
                'label' => esc_html__('Button Icon', 'king-addons'),
                'type' => Controls_Manager::ICONS,
                'skin' => 'inline',
                'label_block' => false,
                'default' => [
                    'value' => 'fas fa-crown',
                    'library' => 'fa-solid',
                ],
                'condition' => [
                    'king_addons_content_flip_box_back_link_type' => ['button'],
                ],
            ]
        );

        $this->add_control(
            'king_addons_content_flip_box_back_title',
            [
                'label' => esc_html__('Back Title', 'king-addons'),
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => esc_html__('Back Title', 'king-addons'),
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'king_addons_content_flip_box_back_description',
            [
                'label' => esc_html__('Description', 'king-addons'),
                'type' => Controls_Manager::WYSIWYG,
                'default' => esc_html__('Back side content. Lorem ipsum dolor sit amet, consectetur adipiscing elit.', 'king-addons'),
                'separator' => 'before',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'king_addons_content_flip_box_section_settings',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Box Settings', 'king-addons'),
            ]
        );

        $this->add_responsive_control(
            'king_addons_content_flip_box_box_height',
            [
                'type' => Controls_Manager::SLIDER,
                'label' => esc_html__('Height', 'king-addons'),
                'size_units' => ['px', 'vh'],
                'range' => [
                    'px' => [
                        'min' => 20,
                        'max' => 1000,
                    ],
                    'vh' => [
                        'min' => 20,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 350,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-content-flip-box' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'king_addons_content_flip_box_box_border_radius',
            [
                'type' => Controls_Manager::SLIDER,
                'label' => esc_html__('Border Radius', 'king-addons'),
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 700,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 0,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-content-flip-box' => 'border-radius: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .king-addons-content-flip-box-item' => 'border-radius: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .king-addons-content-flip-box-overlay' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'king_addons_content_flip_box_box_animation',
            [
                'label' => esc_html__('Animation', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'flip',
                'options' => [
                    'fade' => esc_html__('Fade', 'king-addons'),
                    'flip' => esc_html__('Flip', 'king-addons'),
                    'slide' => esc_html__('Slide', 'king-addons'),
                    'push' => esc_html__('Push', 'king-addons'),
                    'zoom-out' => esc_html__('Zoom Out', 'king-addons'),
                    'zoom-in' => esc_html__('Zoom In', 'king-addons'),
                ],
                'prefix_class' => 'king-addons-content-flip-box-animation-',
                'render_type' => 'template',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'king_addons_content_flip_box_box_anim_3d',
            [
                'label' => esc_html__('3D Animation', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'prefix_class' => 'king-addons-content-flip-box-animation-3d-',
                'render_type' => 'template',
                'condition' => [
                    'king_addons_content_flip_box_box_animation' => 'flip',
                ],
            ]
        );

        $this->add_control(
            'king_addons_content_flip_box_box_anim_direction',
            [
                'label' => esc_html__('Animation Direction', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'right',
                'options' => [
                    'left' => esc_html__('Left', 'king-addons'),
                    'right' => esc_html__('Right', 'king-addons'),
                    'up' => esc_html__('Top', 'king-addons'),
                    'down' => esc_html__('Bottom', 'king-addons'),
                ],
                'prefix_class' => 'king-addons-content-flip-box-anim-direction-',
                'render_type' => 'template',
                'condition' => [
                    'king_addons_content_flip_box_box_animation!' => ['fade', 'zoom-in', 'zoom-out'],
                ],
            ]
        );

        $this->add_control(
            'king_addons_content_flip_box_box_anim_duration',
            [
                'label' => esc_html__('Animation Duration (ms)', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'default' => 500,
                'min' => 0,
                'step' => 10,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-content-flip-box-item' => 'transition-duration: {{VALUE}}ms;',
                ],
                'render_type' => 'template',
            ]
        );

        $this->add_control(
            'king_addons_content_flip_box_box_animation_timing',
            [
                'label' => esc_html__('Animation Timing', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'ease' => 'Ease',
                    'linear' => 'Linear',
                    'ease-in' => 'Ease In',
                    'ease-out' => 'Ease Out',
                ],
                'default' => 'ease',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'king_addons_content_flip_box_section_style_front',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Front Side', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'king_addons_content_flip_box_front_bg_color',
                'types' => ['classic', 'gradient'],
                'fields_options' => [
                    'color' => [
                        'default' => '#9B62FF',
                    ],
                    'background' => [
                        'default' => 'classic',
                    ]
                ],
                'selector' => '{{WRAPPER}} .king-addons-content-flip-box-front',
            ]
        );

        $this->add_control(
            'king_addons_content_flip_box_front_overlay_color',
            [
                'label' => esc_html__('Overlay Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#c1c1c1',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-content-flip-box-front .king-addons-content-flip-box-overlay' => 'background-color: {{VALUE}};',
                ],
                'separator' => 'before',
                'condition' => [
                    'king_addons_content_flip_box_front_bg_color_image[id]!' => '',
                ],
            ]
        );

        $this->add_responsive_control(
            'king_addons_content_flip_box_front_padding',
            [
                'label' => esc_html__('Padding', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'default' => [
                    'top' => 20,
                    'right' => 20,
                    'bottom' => 20,
                    'left' => 20,
                ],
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-content-flip-box-front .king-addons-content-flip-box-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'king_addons_content_flip_box_front_vr_position',
            [
                'label' => esc_html__('Vertical Position', 'king-addons'),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'default' => 'middle',
                'options' => [
                    'top' => [
                        'title' => esc_html__('Top', 'king-addons'),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'middle' => [
                        'title' => esc_html__('Middle', 'king-addons'),
                        'icon' => 'eicon-v-align-middle',
                    ],
                    'bottom' => [
                        'title' => esc_html__('Bottom', 'king-addons'),
                        'icon' => 'eicon-v-align-bottom',
                    ],
                ],
                'selectors_dictionary' => [
                    'top' => 'flex-start',
                    'middle' => 'center',
                    'bottom' => 'flex-end'
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-content-flip-box-front .king-addons-content-flip-box-content' => 'justify-content: {{VALUE}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'king_addons_content_flip_box_front_align',
            [
                'label' => esc_html__('Alignment', 'king-addons'),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'default' => 'center',
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
                'prefix_class' => 'king-addons-content-flip-box-front-align-',
                'render_type' => 'template',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-content-flip-box-front .king-addons-content-flip-box-content' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'king_addons_content_flip_box_front_border',
                'label' => esc_html__('Border', 'king-addons'),
                'fields_options' => [
                    'color' => [
                        'default' => '#E8E8E8',
                    ],
                    'width' => [
                        'default' => [
                            'top' => '1',
                            'right' => '1',
                            'bottom' => '1',
                            'left' => '1',
                            'isLinked' => true,
                        ],
                    ],
                ],
                'selector' => '{{WRAPPER}} .king-addons-content-flip-box-front',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'king_addons_content_flip_box_front_image_section',
            [
                'label' => esc_html__('Image', 'king-addons'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'king_addons_content_flip_box_front_icon_type' => 'image',
                ],
            ]
        );

        $this->add_responsive_control(
            'king_addons_content_flip_box_front_image_width',
            [
                'label' => esc_html__('Width', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['%', 'px'],
                'range' => [
                    '%' => [
                        'min' => 1,
                        'max' => 100,
                    ],
                    'px' => [
                        'min' => 1,
                        'max' => 300,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 50,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-content-flip-box-front .king-addons-content-flip-box-image img' => 'max-width: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'king_addons_content_flip_box_front_icon_type' => 'image',
                ],
            ]
        );

        $this->add_responsive_control(
            'king_addons_content_flip_box_front_image_distance',
            [
                'label' => esc_html__('Distance', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 300,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 5,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-content-flip-box-front .king-addons-content-flip-box-image' => 'margin-bottom:{{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'king_addons_content_flip_box_front_icon_type' => 'image',
                ],
            ]
        );

        $this->add_control(
            'king_addons_content_flip_box_front_image_border_radius',
            [
                'type' => Controls_Manager::SLIDER,
                'label' => esc_html__('Border Radius', 'king-addons'),
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 150,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 0,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-content-flip-box-front .king-addons-content-flip-box-image img' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'king_addons_content_flip_box_front_icon_type' => 'image',
                ],
            ]
        );

        $this->add_control(
            'king_addons_content_flip_box_front_icon_section',
            [
                'label' => esc_html__('Icon', 'king-addons'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'king_addons_content_flip_box_front_icon_type' => 'icon',
                ],
            ]
        );

        $this->add_control(
            'king_addons_content_flip_box_front_icon_color',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-content-flip-box-front .king-addons-content-flip-box-icon' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'king_addons_content_flip_box_front_icon_type' => 'icon',
                ],
            ]
        );

        $this->add_control(
            'king_addons_content_flip_box_front_icon_size',
            [
                'label' => esc_html__('Font Size', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 300,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 40,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-content-flip-box-front .king-addons-content-flip-box-icon' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'king_addons_content_flip_box_front_icon_type' => 'icon',
                ],
            ]
        );

        $this->add_control(
            'king_addons_content_flip_box_front_icon_distance',
            [
                'label' => esc_html__('Distance', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 150,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 7,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-content-flip-box-front .king-addons-content-flip-box-icon' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'king_addons_content_flip_box_front_icon_type' => 'icon',
                ],
            ]
        );

        $this->add_control(
            'king_addons_content_flip_box_front_title_section',
            [
                'label' => esc_html__('Title', 'king-addons'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'king_addons_content_flip_box_front_title_color',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-content-flip-box-front .king-addons-content-flip-box-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'king_addons_content_flip_box_front_title_typography',
                'selector' => '{{WRAPPER}} .king-addons-content-flip-box-front .king-addons-content-flip-box-title',
            ]
        );

        $this->add_control(
            'king_addons_content_flip_box_front_title_distance',
            [
                'label' => esc_html__('Distance', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 150,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 12,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-content-flip-box-front .king-addons-content-flip-box-title' => 'margin: 0 0 {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'king_addons_content_flip_box_front_description_section',
            [
                'label' => esc_html__('Description', 'king-addons'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'king_addons_content_flip_box_front_description_color',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-content-flip-box-front .king-addons-content-flip-box-description' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'king_addons_content_flip_box_front_description_typography',
                'selector' => '{{WRAPPER}} .king-addons-content-flip-box-front .king-addons-content-flip-box-description',
            ]
        );

        $this->add_control(
            'king_addons_content_flip_box_front_description_distance',
            [
                'label' => esc_html__('Distance', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 150,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 20,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-content-flip-box-front .king-addons-content-flip-box-description' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'king_addons_content_flip_box_section_style_back',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Back Side', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'king_addons_content_flip_box_back_bg_color',
                'types' => ['classic', 'gradient'],
                'fields_options' => [
                    'color' => [
                        'default' => '#5B03FF',
                    ],
                    'background' => [
                        'default' => 'classic',
                    ]
                ],
                'selector' => '{{WRAPPER}} .king-addons-content-flip-box-back',
            ]
        );

        $this->add_control(
            'king_addons_content_flip_box_back_overlay_color',
            [
                'label' => esc_html__('Overlay Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#c1c1c1',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-content-flip-box-back .king-addons-content-flip-box-overlay' => 'background-color: {{VALUE}};',
                ],
                'separator' => 'before',
                'condition' => [
                    'king_addons_content_flip_box_back_bg_color_image[id]!' => '',
                ],
            ]
        );

        $this->add_responsive_control(
            'king_addons_content_flip_box_back_padding',
            [
                'label' => esc_html__('Padding', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'default' => [
                    'top' => 20,
                    'right' => 20,
                    'bottom' => 20,
                    'left' => 20,
                ],
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-content-flip-box-back .king-addons-content-flip-box-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'king_addons_content_flip_box_back_vr_position',
            [
                'label' => esc_html__('Vertical Position', 'king-addons'),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'default' => 'middle',
                'options' => [
                    'top' => [
                        'title' => esc_html__('Top', 'king-addons'),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'middle' => [
                        'title' => esc_html__('Middle', 'king-addons'),
                        'icon' => 'eicon-v-align-middle',
                    ],
                    'bottom' => [
                        'title' => esc_html__('Bottom', 'king-addons'),
                        'icon' => 'eicon-v-align-bottom',
                    ],
                ],
                'selectors_dictionary' => [
                    'top' => 'flex-start',
                    'middle' => 'center',
                    'bottom' => 'flex-end'
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-content-flip-box-back .king-addons-content-flip-box-content' => 'justify-content: {{VALUE}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'king_addons_content_flip_box_back_align',
            [
                'label' => esc_html__('Alignment', 'king-addons'),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'default' => 'center',
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
                'prefix_class' => 'king-addons-content-flip-box-back-align-',
                'render_type' => 'template',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-content-flip-box-back .king-addons-content-flip-box-content' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'king_addons_content_flip_box_back_border',
                'label' => esc_html__('Border', 'king-addons'),
                'fields_options' => [
                    'color' => [
                        'default' => '#E8E8E8',
                    ],
                    'width' => [
                        'default' => [
                            'top' => '1',
                            'right' => '1',
                            'bottom' => '1',
                            'left' => '1',
                            'isLinked' => true,
                        ],
                    ],
                ],
                'selector' => '{{WRAPPER}} .king-addons-content-flip-box-back',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'king_addons_content_flip_box_back_image_section',
            [
                'label' => esc_html__('Image', 'king-addons'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'king_addons_content_flip_box_back_icon_type' => 'image',
                ],
            ]
        );

        $this->add_responsive_control(
            'king_addons_content_flip_box_back_image_width',
            [
                'label' => esc_html__('Width', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['%', 'px'],
                'range' => [
                    '%' => [
                        'min' => 1,
                        'max' => 100,
                    ],
                    'px' => [
                        'min' => 1,
                        'max' => 300,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 50,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-content-flip-box-back .king-addons-content-flip-box-image img' => 'max-width: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'king_addons_content_flip_box_back_icon_type' => 'image',
                ],
            ]
        );

        $this->add_responsive_control(
            'king_addons_content_flip_box_back_image_distance',
            [
                'label' => esc_html__('Distance', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 150,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 5,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-content-flip-box-back .king-addons-content-flip-box-image' => 'margin-bottom:{{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'king_addons_content_flip_box_back_icon_type' => 'image',
                ],
            ]
        );

        $this->add_control(
            'king_addons_content_flip_box_back_image_border_radius',
            [
                'type' => Controls_Manager::SLIDER,
                'label' => esc_html__('Border Radius', 'king-addons'),
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 150,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 0,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-content-flip-box-back .king-addons-content-flip-box-image img' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'king_addons_content_flip_box_back_icon_type' => 'image',
                ],
            ]
        );

        $this->add_control(
            'king_addons_content_flip_box_back_icon_section',
            [
                'label' => esc_html__('Icon', 'king-addons'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'king_addons_content_flip_box_back_icon_type' => 'icon',
                ],
            ]
        );

        $this->add_control(
            'king_addons_content_flip_box_back_icon_color',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-content-flip-box-back .king-addons-content-flip-box-icon' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'king_addons_content_flip_box_back_icon_type' => 'icon',
                ],
            ]
        );

        $this->add_control(
            'king_addons_content_flip_box_back_icon_size',
            [
                'label' => esc_html__('Font Size', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 300,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 40,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-content-flip-box-back .king-addons-content-flip-box-icon' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'king_addons_content_flip_box_back_icon_type' => 'icon',
                ],
            ]
        );

        $this->add_control(
            'king_addons_content_flip_box_back_icon_distance',
            [
                'label' => esc_html__('Distance', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 150,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 7,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-content-flip-box-back .king-addons-content-flip-box-icon' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'king_addons_content_flip_box_back_icon_type' => 'icon',
                ],
            ]
        );

        $this->add_control(
            'king_addons_content_flip_box_back_title_section',
            [
                'label' => esc_html__('Title', 'king-addons'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'king_addons_content_flip_box_back_title_color',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-content-flip-box-back .king-addons-content-flip-box-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'king_addons_content_flip_box_back_title_typography',
                'selector' => '{{WRAPPER}} .king-addons-content-flip-box-back .king-addons-content-flip-box-title',
            ]
        );

        $this->add_control(
            'king_addons_content_flip_box_back_title_distance',
            [
                'label' => esc_html__('Distance', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 150,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 12,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-content-flip-box-back .king-addons-content-flip-box-title' => 'margin: 0 0 {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'king_addons_content_flip_box_back_description_section',
            [
                'label' => esc_html__('Description', 'king-addons'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'king_addons_content_flip_box_back_description_color',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-content-flip-box-back .king-addons-content-flip-box-description' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'king_addons_content_flip_box_back_description_typography',
                'selector' => '{{WRAPPER}} .king-addons-content-flip-box-back .king-addons-content-flip-box-description',
            ]
        );

        $this->add_control(
            'king_addons_content_flip_box_back_description_distance',
            [
                'label' => esc_html__('Distance', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 150,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 20,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-content-flip-box-back .king-addons-content-flip-box-description' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'king_addons_content_flip_box_section_style_button',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Front Button', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'king_addons_content_flip_box_front_trigger' => 'button',
                ],
            ]
        );

        $this->start_controls_tabs(
            'king_addons_content_flip_box_section_style_button_tabs'
        );

        $this->start_controls_tab(
            'king_addons_content_flip_box_section_style_button_tab',
            [
                'label' => esc_html__('Normal', 'king-addons'),
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'king_addons_content_flip_box_front_button_typography',
                'selector' => '{{WRAPPER}} .king-addons-content-flip-box-button',
            ]
        );

        $this->add_control(
            'king_addons_content_flip_box_front_button_color',
            [
                'label' => esc_html__('Text Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-content-flip-box-button' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'king_addons_content_flip_box_front_button_bg_color',
            [
                'label' => esc_html__('Background Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#8C56E2',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-content-flip-box-button' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'king_addons_content_flip_box_front_button_border',
                'selector' => '{{WRAPPER}} .king-addons-content-flip-box-button',
            ]
        );

        $this->add_control(
            'king_addons_content_flip_box_front_button_border_radius',
            [
                'label' => esc_html__('Border Radius', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 3,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-content-flip-box-button' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'king_addons_content_flip_box_front_button_padding',
            [
                'label' => esc_html__('Padding', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'default' => [
                    'top' => 12,
                    'right' => 24,
                    'bottom' => 12,
                    'left' => 24,
                    'isLinked' => true,
                ],
                'size_units' => ['px', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-content-flip-box-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'king_addons_content_flip_box_section_style_button_tab_hover',
            [
                'label' => esc_html__('Hover', 'king-addons'),
            ]
        );

        $this->add_control(
            'king_addons_content_flip_box_front_button_color_hover',
            [
                'label' => esc_html__('Text Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-content-flip-box-button:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'king_addons_content_flip_box_front_button_bg_color_hover',
            [
                'label' => esc_html__('Background Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#413BE4',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-content-flip-box-button:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'king_addons_content_flip_box_front_button_border_color_hover',
            [
                'label' => esc_html__('Border Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-content-flip-box-button:hover' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'king_addons_content_flip_box_front_button_animation_hover',
            [
                'label' => esc_html__('Animation', 'king-addons'),
                'type' => Controls_Manager::HOVER_ANIMATION,
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

        $this->start_controls_section(
            'king_addons_content_flip_box_section_style_backside_button',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Back Button', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'king_addons_content_flip_box_back_link_type' => 'button',
                ],
            ]
        );

        $this->start_controls_tabs(
            'king_addons_content_flip_box_section_style_backside_button_tabs'
        );

        $this->start_controls_tab(
            'king_addons_content_flip_box_section_style_backside_button_tab',
            [
                'label' => esc_html__('Normal', 'king-addons'),
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'king_addons_content_flip_box_backside_button_typography',
                'selector' => '{{WRAPPER}} .king-addons-content-flip-box-button-backside',
            ]
        );

        $this->add_control(
            'king_addons_content_flip_box_backside_button_color',
            [
                'label' => esc_html__('Text Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-content-flip-box-button-backside' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'king_addons_content_flip_box_backside_button_bg_color',
            [
                'label' => esc_html__('Background Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#8C56E2',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-content-flip-box-button-backside' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'king_addons_content_flip_box_backside_button_border',
                'selector' => '{{WRAPPER}} .king-addons-content-flip-box-button-backside',
            ]
        );

        $this->add_control(
            'king_addons_content_flip_box_backside_button_border_radius',
            [
                'label' => esc_html__('Border Radius', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 3,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-content-flip-box-button-backside' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'king_addons_content_flip_box_backside_button_padding',
            [
                'label' => esc_html__('Padding', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'default' => [
                    'top' => 12,
                    'right' => 24,
                    'bottom' => 12,
                    'left' => 24,
                    'isLinked' => true,
                ],
                'size_units' => ['px', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-content-flip-box-button-backside' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'king_addons_content_flip_box_section_style_backside_button_tab_hover',
            [
                'label' => esc_html__('Hover', 'king-addons'),
            ]
        );

        $this->add_control(
            'king_addons_content_flip_box_backside_button_color_hover',
            [
                'label' => esc_html__('Text Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-content-flip-box-button-backside:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'king_addons_content_flip_box_backside_button_bg_color_hover',
            [
                'label' => esc_html__('Background Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#413BE4',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-content-flip-box-button-backside:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'king_addons_content_flip_box_backside_button_border_color_hover',
            [
                'label' => esc_html__('Border Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-content-flip-box-button-backside:hover' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'king_addons_content_flip_box_backside_button_animation_hover',
            [
                'label' => esc_html__('Animation', 'king-addons'),
                'type' => Controls_Manager::HOVER_ANIMATION,
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

    }

    protected function render(): void
    {
        $settings = $this->get_settings();
        $id = $this->get_id();

        $front_image_src = Group_Control_Image_Size::get_attachment_image_src($settings['king_addons_content_flip_box_front_image']['id'], 'king_addons_content_flip_box_front_image_size', $settings);
        if (!$front_image_src) {
            $front_image_src = $settings['king_addons_content_flip_box_front_image']['url'];
        }
        $front_alt_text = $settings['king_addons_content_flip_box_front_image']['alt'] ?? '';

        $back_image_src = Group_Control_Image_Size::get_attachment_image_src($settings['king_addons_content_flip_box_back_image']['id'], 'king_addons_content_flip_box_back_image_size', $settings);
        if (!$back_image_src) {
            $back_image_src = $settings['king_addons_content_flip_box_back_image']['url'];
        }
        $back_alt_text = $settings['king_addons_content_flip_box_back_image']['alt'] ?? '';

        $back_button_element = 'div';
        $back_link = $settings['king_addons_content_flip_box_back_link']['url'];
        if ($back_link !== '') {
            $back_button_element = 'a';
        }

        echo '<div class="king-addons-content-flip-box king-addons-content-flip-box-' . esc_attr($id) . '" data-trigger="' . esc_attr($settings['king_addons_content_flip_box_front_trigger']) . '">';

        echo '<div class="king-addons-content-flip-box-item king-addons-content-flip-box-front king-addons-content-flip-animation-timing-' . esc_attr($settings['king_addons_content_flip_box_box_animation_timing']) . '">';
        echo '<div class="king-addons-content-flip-box-overlay"></div>';
        echo '<div class="king-addons-content-flip-box-content">';

        if ($settings['king_addons_content_flip_box_front_icon_type'] === 'icon' && !empty($settings['king_addons_content_flip_box_front_icon']['value'])) {
            echo '<div class="king-addons-content-flip-box-icon">';
            echo '<i class="' . esc_attr($settings['king_addons_content_flip_box_front_icon']['value']) . '"></i>';
            echo '</div>';
        } elseif ($settings['king_addons_content_flip_box_front_icon_type'] === 'image' && !empty($front_image_src)) {
            echo '<div class="king-addons-content-flip-box-image">';
            echo '<img alt="' . esc_attr($front_alt_text) . '" src="' . esc_url($front_image_src) . '">';
            echo '</div>';
        }

        if (!empty($settings['king_addons_content_flip_box_front_title'])) {
            echo '<h3 class="king-addons-content-flip-box-title">' . wp_kses_post($settings['king_addons_content_flip_box_front_title']) . '</h3>';
        }

        if (!empty($settings['king_addons_content_flip_box_front_description'])) {
            echo '<div class="king-addons-content-flip-box-description">' . wp_kses_post($settings['king_addons_content_flip_box_front_description']) . '</div>';
        }

        if ($settings['king_addons_content_flip_box_front_trigger'] === 'button') {
            echo '<div class="king-addons-content-flip-box-button-wrap' . esc_attr($settings['king_addons_content_flip_box_front_button_animation_hover'] ? ' elementor-animation-' . $settings['king_addons_content_flip_box_front_button_animation_hover'] : '') . '">';
            echo '<div class="king-addons-content-flip-box-button">';
            if (!empty($settings['king_addons_content_flip_box_front_button_text'])) {
                echo '<span class="king-addons-content-flip-box-button-text">' . esc_html($settings['king_addons_content_flip_box_front_button_text']) . '</span>';
            }
            if (!empty($settings['king_addons_content_flip_box_front_button_icon']['value'])) {
                echo '<span class="king-addons-content-flip-box-button-icon">';
                echo '<i class="' . esc_attr($settings['king_addons_content_flip_box_front_button_icon']['value']) . '"></i>';
                echo '</span>';
            }
            echo '</div>';
            echo '</div>';
        }

        echo '</div>';
        echo '</div>';

        echo '<div class="king-addons-content-flip-box-item king-addons-content-flip-box-back king-addons-content-flip-animation-timing-' . esc_attr($settings['king_addons_content_flip_box_box_animation_timing']) . '">';
        echo '<div class="king-addons-content-flip-box-overlay"></div>';
        echo '<div class="king-addons-content-flip-box-content">';

        if ($settings['king_addons_content_flip_box_back_link_type'] === 'box') {
            echo '<a class="king-addons-content-flip-box-link" href="' . esc_url($settings['king_addons_content_flip_box_back_link']['url']) . '"';
            if ($settings['king_addons_content_flip_box_back_link']['is_external']) {
                echo ' target="_blank"';
            }
            if ($settings['king_addons_content_flip_box_back_link']['nofollow']) {
                echo ' rel="nofollow"';
            }
            echo '></a>';
        }

        if ($settings['king_addons_content_flip_box_back_icon_type'] === 'icon' && !empty($settings['king_addons_content_flip_box_back_icon']['value'])) {
            echo '<div class="king-addons-content-flip-box-icon">';
            echo '<i class="' . esc_attr($settings['king_addons_content_flip_box_back_icon']['value']) . '"></i>';
            echo '</div>';
        } elseif ($settings['king_addons_content_flip_box_back_icon_type'] === 'image' && !empty($back_image_src)) {
            echo '<div class="king-addons-content-flip-box-image">';
            echo '<img alt="' . esc_attr($back_alt_text) . '" src="' . esc_url($back_image_src) . '">';
            echo '</div>';
        }

        if (!empty($settings['king_addons_content_flip_box_back_title'])) {
            echo '<h3 class="king-addons-content-flip-box-title">';
            if ($settings['king_addons_content_flip_box_back_link_type'] === 'title' || $settings['king_addons_content_flip_box_back_link_type'] === 'button-title') {
                echo '<a href="' . esc_url($settings['king_addons_content_flip_box_back_link']['url']) . '"';
                if ($settings['king_addons_content_flip_box_back_link']['is_external']) {
                    echo ' target="_blank"';
                }
                if ($settings['king_addons_content_flip_box_back_link']['nofollow']) {
                    echo ' rel="nofollow"';
                }
                echo '>';
            }
            echo wp_kses_post($settings['king_addons_content_flip_box_back_title']);
            if ($settings['king_addons_content_flip_box_back_link_type'] === 'title' || $settings['king_addons_content_flip_box_back_link_type'] === 'button-title') {
                echo '</a>';
            }
            echo '</h3>';
        }

        if (!empty($settings['king_addons_content_flip_box_back_description'])) {
            echo '<div class="king-addons-content-flip-box-description">' . wp_kses_post($settings['king_addons_content_flip_box_back_description']) . '</div>';
        }

        if ($settings['king_addons_content_flip_box_back_link_type'] === 'button' || $settings['king_addons_content_flip_box_back_link_type'] === 'button-title') {
            echo '<div class="king-addons-content-flip-box-button-wrap">';
            echo '<' . esc_html($back_button_element) . ' class="king-addons-content-flip-box-button-backside' . esc_attr($settings['king_addons_content_flip_box_backside_button_animation_hover'] ? ' elementor-animation-' . $settings['king_addons_content_flip_box_backside_button_animation_hover'] : '') . '" href="' . esc_url($settings['king_addons_content_flip_box_back_link']['url']) . '"';
            if ($settings['king_addons_content_flip_box_back_link']['is_external']) {
                echo ' target="_blank"';
            }
            if ($settings['king_addons_content_flip_box_back_link']['nofollow']) {
                echo ' rel="nofollow"';
            }
            echo '>';
            if (!empty($settings['king_addons_content_flip_box_back_button_text'])) {
                echo '<span class="king-addons-content-flip-box-button-text">' . esc_html($settings['king_addons_content_flip_box_back_button_text']) . '</span>';
            }
            if (!empty($settings['king_addons_content_flip_box_back_button_icon']['value'])) {
                echo '<span class="king-addons-content-flip-box-button-icon">';
                echo '<i class="' . esc_attr($settings['king_addons_content_flip_box_back_button_icon']['value']) . '"></i>';
                echo '</span>';
            }
            echo '</' . esc_html($back_button_element) . '>';
            echo '</div>';
        }

        echo '</div>';
        echo '</div>';
        echo '</div>';

        $inline_js = "(function () {
    function doFlipBox() {
        let flipBox = document.querySelector('.king-addons-content-flip-box-" . esc_js($id) . "');
        if (!flipBox) return;

        let flipBoxTrigger = flipBox.getAttribute('data-trigger');

        if (flipBoxTrigger === 'box') {
            flipBox.querySelector('.king-addons-content-flip-box-front').addEventListener('click', function () {
                flipBox.classList.add('king-addons-content-flip-box-active');
            });

            window.addEventListener('click', function (event) {
                if (!event.target.closest('.king-addons-content-flip-box-" . esc_js($id) . "')) {
                    flipBox.classList.remove('king-addons-content-flip-box-active');
                }
            });

        } else if (flipBoxTrigger === 'button') {
            flipBox.querySelector('.king-addons-content-flip-box-button').addEventListener('click', function () {
                flipBox.classList.add('king-addons-content-flip-box-active');
            });

            window.addEventListener('click', function (event) {
                if (!event.target.closest('.king-addons-content-flip-box-" . esc_js($id) . "')) {
                    flipBox.classList.remove('king-addons-content-flip-box-active');
                }
            });

        } else if (flipBoxTrigger === 'hover') {
            flipBox.addEventListener('mouseenter', function () {
                flipBox.classList.add('king-addons-content-flip-box-active');
            });

            flipBox.addEventListener('mouseleave', function () {
                flipBox.classList.remove('king-addons-content-flip-box-active');
            });
        }
    }

    if (document.readyState === 'complete') {
        doFlipBox();
    } else {
        document.addEventListener('DOMContentLoaded', doFlipBox);
    }
})();";

        wp_print_inline_script_tag($inline_js);
    }
}
