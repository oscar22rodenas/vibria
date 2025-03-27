<?php /** @noinspection SpellCheckingInspection, DuplicatedCode, PhpUnused */

namespace King_Addons;

use Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Utils;
use Elementor\Widget_Base;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class Image_Hover_Box extends Widget_Base
{
    public function get_name(): string
    {
        return 'king-addons-image-hover-box';
    }

    public function get_title(): string
    {
        return esc_html__('Image Hover Box', 'king-addons');
    }

    public function get_icon(): string
    {
        return 'king-addons-icon king-addons-image-hover-box';
    }

    public function get_style_depends(): array
    {
        return [KING_ADDONS_ASSETS_UNIQUE_KEY . '-image-hover-box-style'];
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
            'layout', 'animated box', 'hover text', 'text box', 'text banner'];
    }

    public function get_custom_help_url(): string
    {
        return 'https://kingaddons.com/elementor/image-hover-box/';
    }

    protected function register_controls(): void
    {
        $this->start_controls_section(
            'king_addons_image_hover_box_section_image',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Image', 'king-addons'),
            ]
        );

        $this->add_control(
            'king_addons_image_hover_box_image',
            [
                'label' => esc_html__('Upload Image', 'king-addons'),
                'type' => Controls_Manager::MEDIA,
                'dynamic' => array('active' => true),
                'default' => array(
                    'url' => Utils::get_placeholder_image_src(),
                ),
                'show_external' => true,
            ]
        );

        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name' => 'king_addons_image_hover_box_image_size',
                'default' => 'full',
            ]
        );

        $this->add_control(
            'king_addons_image_hover_box_image_custom_link',
            [
                'label' => esc_html__('Link', 'king-addons'),
                'type' => Controls_Manager::URL,
                'dynamic' => array('active' => true),
                'show_external' => false,
            ]
        );

        $this->add_control(
            'king_addons_image_hover_box_image_layout',
            [
                'label' => '<b>' . esc_html__('Layout (effect)', 'king-addons') . '</b>',
                'type' => Controls_Manager::SELECT,
                'default' => 'layout-1',
                'options' => array(
                    'layout-1' => esc_html__('Slide Up 1', 'king-addons'),
                    'layout-2' => esc_html__('Slide Up 2', 'king-addons'),
                    'layout-5' => esc_html__('Slide Up 3', 'king-addons'),
                    'layout-10' => esc_html__('Slide Line Up', 'king-addons'),
                    'layout-3' => esc_html__('Slide Line Right', 'king-addons'),
                    'layout-4' => esc_html__('Slide Border 1', 'king-addons'),
                    'layout-6' => esc_html__('Slide Border 2', 'king-addons'),
                    'layout-7' => esc_html__('Opacity Angles', 'king-addons'),
                    'layout-8' => esc_html__('Opacity Borders', 'king-addons'),
                    'layout-9' => esc_html__('Crossing', 'king-addons'),
                    'layout-11' => esc_html__('Magic Overlay', 'king-addons'),
                ),
            ]
        );

        $this->add_control(
            'king_addons_image_hover_box_active',
            [
                'label' => esc_html__('Always Hovered', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
            ]
        );

        $this->add_control(
            'king_addons_image_hover_box_hover_effect',
            [
                'label' => esc_html__('Hover Effect', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => array(
                    'none' => esc_html__('None', 'king-addons'),
                    'zoom-in' => esc_html__('Zoom In', 'king-addons'),
                    'zoom-out' => esc_html__('Zoom Out', 'king-addons'),
                    'scale' => esc_html__('Scale', 'king-addons'),
                    'grayscale' => esc_html__('Grayscale', 'king-addons'),
                    'blur' => esc_html__('Blur', 'king-addons'),
                    'bright' => esc_html__('Bright', 'king-addons'),
                    'sepia' => esc_html__('Sepia', 'king-addons'),
                ),
                'default' => 'none',
            ]
        );

        $this->add_control(
            'king_addons_image_hover_box_height',
            [
                'label' => esc_html__('Height', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => array(
                    'default' => esc_html__('Default (auto)', 'king-addons'),
                    'custom' => esc_html__('Custom', 'king-addons'),
                ),
                'default' => 'default',
            ]
        );

        $this->add_responsive_control(
            'king_addons_image_hover_box_custom_height',
            [
                'label' => esc_html__('Height', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => array('px', 'em', 'vh', 'custom'),
                'condition' => array(
                    'king_addons_image_hover_box_height' => 'custom',
                ),
                'default' => array(
                    'size' => 300,
                ),
                'range' => array(
                    'px' => array(
                        'min' => 1,
                        'max' => 600,
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} .king-addons-image-hover-box img' => 'height: {{SIZE}}{{UNIT}};',
                ),
            ]
        );

        $this->add_responsive_control(
            'king_addons_image_hover_box_image_fit',
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
                    '{{WRAPPER}} .king-addons-image-hover-box img' => 'object-fit: {{VALUE}}',
                ),
                'condition' => array(
                    'king_addons_image_hover_box_height' => 'custom',
                ),
            ]
        );

        $this->add_responsive_control(
            'king_addons_image_hover_box_image_fit_position',
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
                    '{{WRAPPER}} .king-addons-image-hover-box img' => 'object-position: {{VALUE}};',
                ],
                'condition' => array(
                    'king_addons_image_hover_box_height' => 'custom',
                ),
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'king_addons_image_hover_box_section_texts',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Texts', 'king-addons'),
            ]
        );

        $this->add_control(
            'king_addons_image_hover_box_title',
            [
                'label' => '<b>' . esc_html__('Title', 'king-addons') . '</b>',
                'placeholder' => esc_html__('Title', 'king-addons'),
                'type' => Controls_Manager::TEXT,
                'dynamic' => array('active' => true),
                'default' => esc_html__('Title', 'king-addons'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'king_addons_image_hover_box_title_tag',
            [
                'label' => esc_html__('HTML Tag', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'h3',
                'options' => array(
                    'h1' => 'H1',
                    'h2' => 'H2',
                    'h3' => 'H3',
                    'h4' => 'H4',
                    'h5' => 'H5',
                    'h6' => 'H6',
                    'div' => 'div',
                    'span' => 'span',
                    'p' => 'p',
                ),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'king_addons_image_hover_box_description_hint',
            [
                'label' => esc_html__('Description', 'king-addons'),
                'type' => Controls_Manager::HEADING,
            ]
        );

        $this->add_control(
            'king_addons_image_hover_box_description',
            [
                'label' => esc_html__('Description', 'king-addons'),
                'type' => Controls_Manager::WYSIWYG,
                'dynamic' => array('active' => true),
                'default' => esc_html__('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua', 'king-addons'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'king_addons_image_hover_box_title_text_align',
            [
                'label' => esc_html__('Alignment', 'king-addons'),
                'type' => Controls_Manager::CHOOSE,
                'options' => array(
                    'left' => array(
                        'title' => esc_html__('Left', 'king-addons'),
                        'icon' => 'eicon-text-align-left',
                    ),
                    'center' => array(
                        'title' => esc_html__('Center', 'king-addons'),
                        'icon' => 'eicon-text-align-center',
                    ),
                    'right' => array(
                        'title' => esc_html__('Right', 'king-addons'),
                        'icon' => 'eicon-text-align-right',
                    ),
                ),
                'default' => 'left',
                'toggle' => false,
                'selectors' => array(
                    '{{WRAPPER}} .king-addons-image-hover-box-text-title, {{WRAPPER}} .king-addons-image-hover-box-text-description' => 'text-align: {{VALUE}} ;',
                ),
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'king_addons_image_hover_box_opacity_style',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('General', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'king_addons_image_hover_box_image_bg_color',
            [
                'label' => esc_html__('Background Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .king-addons-image-hover-box' => 'background: {{VALUE}};',
                ),
            ]
        );

        $this->add_control(
            'king_addons_image_hover_box_image_opacity',
            [
                'label' => esc_html__('Image Opacity', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'default' => array(
                    'size' => 1,
                ),
                'range' => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 1,
                        'step' => .1,
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} .king-addons-image-hover-box img' => 'opacity: {{SIZE}};',
                ),
            ]
        );

        $this->add_control(
            'king_addons_image_hover_box_image_hover_opacity',
            [
                'label' => esc_html__('Hover Opacity', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'default' => array(
                    'size' => 1,
                ),
                'range' => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 1,
                        'step' => .1,
                    ),
                ),
                'separator' => 'after',
                'selectors' => array(
                    '{{WRAPPER}}:hover .king-addons-image-hover-box img' => 'opacity: {{SIZE}};',
                ),
            ]
        );

        $this->add_control(
            'king_addons_image_hover_box_title_border_width',
            [
                'label' => esc_html__('Hover Border Width', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => array('px', '%', 'em'),
                'default' => array(
                    'size' => 2,
                ),
                'condition' => array(
                    'king_addons_image_hover_box_image_layout' => array('layout-3', 'layout-9', 'layout-10'),
                ),
                'selectors' => array(
                    '{{WRAPPER}} .king-addons-image-hover-box-layout-3 .king-addons-image-hover-box-text-title::after' => 'height: {{size}}{{unit}};',
                    '{{WRAPPER}} .king-addons-image-hover-box-layout-9 .king-addons-image-hover-box-text::before, {{WRAPPER}} .king-addons-image-hover-box-layout-9 .king-addons-image-hover-box-text::after' => 'height: {{size}}{{unit}};',
                    '{{WRAPPER}} .king-addons-image-hover-box-layout-10 .king-addons-image-hover-box-text-title::after' => 'height: {{size}}{{unit}};',
                ),
            ]
        );

        $this->add_control(
            'king_addons_image_hover_box_style3_title_border',
            [
                'label' => esc_html__('Hover Border Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'condition' => array(
                    'king_addons_image_hover_box_image_layout' => array('layout-3', 'layout-9', 'layout-10'),
                ),
                'separator' => 'after',
                'selectors' => array(
                    '{{WRAPPER}} .king-addons-image-hover-box-layout-3 .king-addons-image-hover-box-text-title::after' => 'background: {{VALUE}};',
                    '{{WRAPPER}} .king-addons-image-hover-box-layout-9 .king-addons-image-hover-box-text::before, {{WRAPPER}} .king-addons-image-hover-box-layout-9 .king-addons-image-hover-box-text::after' => 'background: {{VALUE}};',
                    '{{WRAPPER}} .king-addons-image-hover-box-layout-10 .king-addons-image-hover-box-text-title::after' => 'background: {{VALUE}};',
                ),
            ]
        );

        $this->add_control(
            'king_addons_image_hover_box_inner_border_width',
            [
                'label' => esc_html__('Hover Border Width', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => array('px', '%', 'em'),
                'default' => array(
                    'size' => 2,
                ),
                'condition' => array(
                    'king_addons_image_hover_box_image_layout' => array('layout-4', 'layout-6', 'layout-7', 'layout-8'),
                ),
                'selectors' => array(
                    '{{WRAPPER}} .king-addons-image-hover-box-layout-4 .king-addons-image-hover-box-text::after, {{WRAPPER}} .king-addons-image-hover-box-layout-4 .king-addons-image-hover-box-text::before, {{WRAPPER}} .king-addons-image-hover-box-layout-6 .king-addons-image-hover-box-text::before' => 'border-width: {{size}}{{unit}};',
                    '{{WRAPPER}} .king-addons-image-hover-box-layout-7 .king-addons-image-hover-box-br.king-addons-image-hover-box-bleft, {{WRAPPER}} .king-addons-image-hover-box-layout-7 .king-addons-image-hover-box-br.king-addons-image-hover-box-bright , {{WRAPPER}} .king-addons-image-hover-box-layout-8 .king-addons-image-hover-box-br.king-addons-image-hover-box-bright,{{WRAPPER}} .king-addons-image-hover-box-layout-8 .king-addons-image-hover-box-br.king-addons-image-hover-box-bleft' => 'width: {{size}}{{unit}};',
                    '{{WRAPPER}} .king-addons-image-hover-box-layout-7 .king-addons-image-hover-box-br.king-addons-image-hover-box-btop, {{WRAPPER}} .king-addons-image-hover-box-layout-7 .king-addons-image-hover-box-br.king-addons-image-hover-box-bottom , {{WRAPPER}} .king-addons-image-hover-box-layout-8 .king-addons-image-hover-box-br.king-addons-image-hover-box-bottom,{{WRAPPER}} .king-addons-image-hover-box-layout-8 .king-addons-image-hover-box-br.king-addons-image-hover-box-btop ' => 'height: {{size}}{{unit}};',
                ),
            ]
        );

        $this->add_control(
            'king_addons_image_hover_box_scaled_border_color',
            [
                'label' => esc_html__('Hover Border Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'condition' => array(
                    'king_addons_image_hover_box_image_layout' => array('layout-4', 'layout-6', 'layout-7', 'layout-8'),
                ),
                'selectors' => array(
                    '{{WRAPPER}} .king-addons-image-hover-box-layout-4 .king-addons-image-hover-box-text::after, {{WRAPPER}} .king-addons-image-hover-box-layout-4 .king-addons-image-hover-box-text::before, {{WRAPPER}} .king-addons-image-hover-box-layout-6 .king-addons-image-hover-box-text::before' => 'border-color: {{VALUE}};',
                    '{{WRAPPER}} .king-addons-image-hover-box-layout-7 .king-addons-image-hover-box-br, {{WRAPPER}} .king-addons-image-hover-box-layout-8 .king-addons-image-hover-box-br' => 'background-color: {{VALUE}};',
                ),
            ]
        );

        $this->add_group_control(
            Group_Control_Css_Filter::get_type(),
            [
                'name' => 'king_addons_image_hover_box_css_filters',
                'selector' => '{{WRAPPER}} .king-addons-image-hover-box img',
            ]
        );

        $this->add_group_control(
            Group_Control_Css_Filter::get_type(),
            [
                'name' => 'king_addons_image_hover_box_hover_css_filters',
                'label' => esc_html__('Hover CSS Filters', 'king-addons'),
                'selector' => '{{WRAPPER}}:hover .king-addons-image-hover-box img',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'king_addons_image_hover_box_image_border',
                'selector' => '{{WRAPPER}} .king-addons-image-hover-box',
            ]
        );

        $this->add_control(
            'king_addons_image_hover_box_image_border_radius',
            [
                'label' => esc_html__('Border Radius', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => array('px', '%', 'em'),
                'selectors' => array(
                    '{{WRAPPER}} .king-addons-image-hover-box, {{WRAPPER}} .king-addons-image-hover-box-gradient' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
                'condition' => array(
                    'king_addons_image_hover_box_image_adv_radius!' => 'yes',
                ),
            ]
        );

        $this->add_control(
            'king_addons_image_hover_box_image_adv_radius',
            [
                'label' => esc_html__('Advanced Border Radius', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'description' => esc_html__('Apply custom radius values. Get the radius value from ', 'king-addons') . '<a href="https://9elements.github.io/fancy-border-radius/" target="_blank">here</a>',
            ]
        );

        $this->add_control(
            'king_addons_image_hover_box_image_adv_radius_value',
            [
                'label' => esc_html__('Border Radius', 'king-addons'),
                'type' => Controls_Manager::TEXT,
                'dynamic' => array('active' => true),
                'selectors' => array(
                    '{{WRAPPER}} .king-addons-image-hover-box, {{WRAPPER}} .king-addons-image-hover-box-gradient' => 'border-radius: {{VALUE}};',
                ),
                'condition' => array(
                    'king_addons_image_hover_box_image_adv_radius' => 'yes',
                ),
            ]
        );

        $this->add_control(
            'king_addons_image_hover_box_blend_mode',
            [
                'label' => esc_html__('Blend Mode', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => array(
                    '' => esc_html__('Normal', 'king-addons'),
                    'multiply' => 'Multiply',
                    'screen' => 'Screen',
                    'overlay' => 'Overlay',
                    'darken' => 'Darken',
                    'lighten' => 'Lighten',
                    'color-dodge' => 'Color Dodge',
                    'saturation' => 'Saturation',
                    'color' => 'Color',
                    'luminosity' => 'Luminosity',
                ),
                'separator' => 'before',
                'selectors' => array(
                    '{{WRAPPER}} .king-addons-image-hover-box' => 'mix-blend-mode: {{VALUE}}',
                ),
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'king_addons_image_hover_box_title_style',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Title', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'king_addons_image_hover_box_color_of_title',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'global' => array(
                    'default' => Global_Colors::COLOR_PRIMARY,
                ),
                'selectors' => array(
                    '{{WRAPPER}} .king-addons-image-hover-box-text .king-addons-image-hover-box-text-title' => 'color: {{VALUE}};',
                ),
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'king_addons_image_hover_box_title_typography',
                'selector' => '{{WRAPPER}} .king-addons-image-hover-box-text .king-addons-image-hover-box-text-title',
                'global' => array(
                    'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
                ),
            ]
        );

        $this->add_control(
            'king_addons_image_hover_box_style2_title_bg',
            [
                'label' => esc_html__('Background', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#f2f2f2',
                'description' => esc_html__('Choose a background color for the title', 'king-addons'),
                'condition' => array(
                    'king_addons_image_hover_box_image_layout' => 'layout-5',
                ),
                'selectors' => array(
                    '{{WRAPPER}} .king-addons-image-hover-box-layout-5 .king-addons-image-hover-box-text' => 'background: {{VALUE}};',
                ),
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'label' => esc_html__('Shadow', 'king-addons'),
                'name' => 'king_addons_image_hover_box_title_shadow',
                'selector' => '{{WRAPPER}} .king-addons-image-hover-box-text .king-addons-image-hover-box-text-title',
            ]
        );

        $this->add_responsive_control(
            'king_addons_image_hover_box_title_margin',
            [
                'label' => esc_html__('Margin', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => array('px', 'em', '%'),
                'selectors' => array(
                    '{{WRAPPER}} .king-addons-image-hover-box-text-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'king_addons_image_hover_box_styles_of_content',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Description', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'king_addons_image_hover_box_color_of_content',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'global' => array(
                    'default' => Global_Colors::COLOR_TEXT,
                ),
                'selectors' => array(
                    '{{WRAPPER}} .king-addons-image-hover-box-text-description' => 'color: {{VALUE}};',
                ),
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'king_addons_image_hover_box_content_typography',
                'selector' => '{{WRAPPER}} .king-addons-image-hover-box-text-description',
                'global' => array(
                    'default' => Global_Typography::TYPOGRAPHY_TEXT,
                ),
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'label' => esc_html__('Shadow', 'king-addons'),
                'name' => 'king_addons_image_hover_box_description_shadow',
                'selector' => '{{WRAPPER}} .king-addons-image-hover-box-text-description',
            ]
        );

        $this->add_responsive_control(
            'king_addons_image_hover_box_desc_margin',
            [
                'label' => esc_html__('Margin', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => array('px', 'em', '%'),
                'selectors' => array(
                    '{{WRAPPER}} .king-addons-image-hover-box-text-description' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'king_addons_image_hover_box_section_whole_box',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Whole Box', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'king_addons_image_hover_box_border',
                'selector' => '{{WRAPPER}}',
            ]
        );

        $this->add_control(
            'king_addons_image_hover_box_border_radius',
            [
                'label' => esc_html__('Border Radius', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => array('px', '%', 'em'),
                'selectors' => array(
                    '{{WRAPPER}}, {{WRAPPER}} .king-addons-image-hover-box-gradient' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
                'condition' => array(
                    'king_addons_image_hover_box_container_image_adv_radius!' => 'yes',
                ),
            ]
        );

        $this->add_control(
            'king_addons_image_hover_box_container_image_adv_radius',
            [
                'label' => esc_html__('Advanced Border Radius', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'description' => esc_html__('Apply custom radius values. Get the radius value from ', 'king-addons') . '<a href="https://9elements.github.io/fancy-border-radius/" target="_blank">here</a>',
            ]
        );

        $this->add_control(
            'king_addons_image_hover_box_container_image_adv_radius_value',
            [
                'label' => esc_html__('Border Radius', 'king-addons'),
                'type' => Controls_Manager::TEXT,
                'dynamic' => array('active' => true),
                'selectors' => array(
                    '{{WRAPPER}}' => 'border-radius: {{VALUE}};',
                ),
                'condition' => array(
                    'king_addons_image_hover_box_container_image_adv_radius' => 'yes',
                ),
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'king_addons_image_hover_box_shadow',
                'selector' => '{{WRAPPER}}',
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'king_addons_image_hover_box_gradient_color',
                'types' => array('gradient'),
                'separator' => 'before',
                'selector' => '{{WRAPPER}} .king-addons-image-hover-box-gradient:before, {{WRAPPER}} .king-addons-image-hover-box-gradient:after',
                'condition' => array(
                    'king_addons_image_hover_box_image_layout' => 'layout-11',
                ),
            ]
        );

        $this->add_control(
            'king_addons_image_hover_box_first_layer_speed',
            [
                'label' => esc_html__('First Layer Transition Speed (seconds)', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'default' => array(
                    'size' => 0.3,
                ),
                'range' => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 3,
                        'step' => 0.1,
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} .king-addons-image-hover-box-layout-11:hover .king-addons-image-hover-box-gradient:after' => 'transition-delay: {{SIZE}}s',
                    '{{WRAPPER}} .king-addons-image-hover-box-layout-11 .king-addons-image-hover-box-gradient:before' => 'transition: transform 0.3s ease-out {{SIZE}}s',
                ),
                'condition' => array(
                    'king_addons_image_hover_box_image_layout' => 'layout-11',
                ),
            ]
        );

        $this->add_control(
            'king_addons_image_hover_box_second_layer_speed',
            [
                'label' => esc_html__('Second Layer Transition Delay (seconds)', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'default' => array(
                    'size' => 0.15,
                ),
                'range' => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 3,
                        'step' => 0.1,
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} .king-addons-image-hover-box-layout-11:hover .king-addons-image-hover-box-gradient:before' => 'transition-delay: {{SIZE}}s',
                    '{{WRAPPER}} .king-addons-image-hover-box-layout-11 .king-addons-image-hover-box-gradient:after' => 'transition: transform 0.3s ease-out {{SIZE}}s',

                ),
                'condition' => array(
                    'king_addons_image_hover_box_image_layout' => 'layout-11',
                ),
            ]
        );

        $this->end_controls_section();
    }

    protected function render(): void
    {
        $settings = $this->get_settings_for_display();

        // Define allowed tags and attributes for img tag
        $allowed_tags = wp_kses_allowed_html('post');
        $allowed_tags['img']['srcset'] = true;
        $allowed_tags['img']['sizes'] = true;
        $allowed_tags['img']['decoding'] = true;
        $allowed_tags['img']['loading'] = true;

        $title = '<' . esc_html($settings['king_addons_image_hover_box_title_tag']) . ' class="king-addons-image-hover-box-text-title">' . esc_html($settings['king_addons_image_hover_box_title']) . '</' . esc_html($settings['king_addons_image_hover_box_title_tag']) . '>';

        $layout_class = 'king-addons-image-hover-box-' . $settings['king_addons_image_hover_box_image_layout'];
        $hover_class = ' king-addons-image-hover-box-' . $settings['king_addons_image_hover_box_hover_effect'];
        $is_active_class = 'yes' === $settings['king_addons_image_hover_box_active'] ? ' king-addons-image-hover-box-active' : '';

        ?>
        <div class="king-addons-image-hover-box king-addons-image-hover-box-min-height <?php echo esc_attr($layout_class . $hover_class . $is_active_class) ?>">
            <?php if ('layout-7' === $settings['king_addons_image_hover_box_image_layout'] || 'layout-8' === $settings['king_addons_image_hover_box_image_layout']) : ?>
                <div class="king-addons-image-hover-box-border">
                    <div class="king-addons-image-hover-box-br king-addons-image-hover-box-bleft king-addons-image-hover-box-border-lr"></div>
                    <div class="king-addons-image-hover-box-br king-addons-image-hover-box-bright king-addons-image-hover-box-border-lr"></div>
                    <div class="king-addons-image-hover-box-br king-addons-image-hover-box-btop king-addons-image-hover-box-border-tb"></div>
                    <div class="king-addons-image-hover-box-br king-addons-image-hover-box-bottom king-addons-image-hover-box-border-tb"></div>
                </div>
            <?php endif; ?>
            <?php if (!empty($settings['king_addons_image_hover_box_image']['url'])) : ?>
                <?php
                $image_html = Group_Control_Image_Size::get_attachment_image_html($settings, 'king_addons_image_hover_box_image_size', 'king_addons_image_hover_box_image');
                echo wp_kses($image_html, $allowed_tags);
                ?>
            <?php endif; ?>
            <?php if ('layout-11' === $settings['king_addons_image_hover_box_image_layout']) : ?>
                <div class="king-addons-image-hover-box-gradient"></div>
            <?php endif; ?>
            <div class="king-addons-image-hover-box-text">
                <?php if ('layout-7' === $settings['king_addons_image_hover_box_image_layout']) : ?>
                <div class="king-addons-image-hover-box-text-centered">
                    <?php endif; ?>
                    <?php
                    echo wp_kses_post($title);
                    if (!empty($settings['king_addons_image_hover_box_description'])) :
                        ?>
                        <div class="king-addons-image-hover-box-text-description">
                            <?php echo wp_kses_post($this->parse_text_editor($settings['king_addons_image_hover_box_description']));
                            ?>
                        </div>
                    <?php endif; ?>
                    <?php if ('layout-7' === $settings['king_addons_image_hover_box_image_layout']) : ?>
                </div>
            <?php endif; ?>
            </div>
            <?php
            if ($settings['king_addons_image_hover_box_image_custom_link']['url']) :
                ?>
                <a href="<?php echo esc_url($settings['king_addons_image_hover_box_image_custom_link']['url']); ?>"<?php
                echo(($settings['king_addons_image_hover_box_image_custom_link']['is_external']) ? ' target="_blank"' : '');
                echo(($settings['king_addons_image_hover_box_image_custom_link']['nofollow']) ? ' rel="nofollow"' : ''); ?>
                   class="king-addons-image-hover-box-link"></a>
            <?php endif; ?>
        </div>
        <?php
    }
}