<?php /** @noinspection SpellCheckingInspection, DuplicatedCode, PhpUnused */

namespace King_Addons;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;
use Elementor\Utils;
use Elementor\Widget_Base;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class Styled_Text_Builder extends Widget_Base
{
    public function get_name(): string
    {
        return 'king-addons-styled-text-builder';
    }

    public function get_title(): string
    {
        return esc_html__('Styled Text Builder', 'king-addons');
    }

    public function get_icon(): string
    {
        /** @noinspection SpellCheckingInspection */
        return 'king-addons-icon king-addons-styled-text-builder';
    }

    public function get_style_depends(): array
    {
        return [KING_ADDONS_ASSETS_UNIQUE_KEY . '-styled-text-builder-style'];
    }

    public function get_categories(): array
    {
        return ['king-addons'];
    }

    public function get_keywords(): array
    {
        return ['image', 'images', 'txt', 'typography', 'text', 'animation', 'animated', 'build', 'complex', 'effect',
            'interactive', 'click', 'target', 'point', 'builder', 'link', 'point', 'points', 'color', 'mouse', 'hover',
            'over', 'hover over', 'picture', 'king', 'addons', 'kingaddons', 'king-addons', 'font', 'size', 'heading',
            'header', 'paragraph', 'section', 'article', 'particle', 'document', 'writing', 'style', 'format', 'complex',
            'auto', 'scroll', 'scrolling', 'auto-scroll', 'auto-scrolling', 'head', 'heading', 'title', 'subtitle'];
    }

    public function get_custom_help_url(): string
    {
        return 'https://kingaddons.com/elementor/styled-text-builder/';
    }

    protected function register_controls(): void
    {
        /** START TAB: CONTENT ===================== */
        /** SECTION: Texts ===================== */
        $this->start_controls_section(
            'kng_styled_txt_content_section_content',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Content', 'king-addons'),
                'tab' => Controls_Manager::TAB_CONTENT,
                'frontend_available' => true,
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'kng_styled_txt_content_type',
            [
                'label' => '<b>' . esc_html__('Type of content', 'king-addons') . '</b>',
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'text' => esc_html__('Text', 'king-addons'),
                    'image' => esc_html__('Image', 'king-addons'),
                ],
                'default' => 'text',
                'separator' => 'after',
            ]
        );

        $repeater->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => esc_html__('Typography', 'king-addons'),
                'name' => 'kng_styled_txt_typography',
                'selector' => '{{WRAPPER}} {{CURRENT_ITEM}}.king-addons-styled-text',
                'condition' => [
                    'kng_styled_txt_content_type' => 'text'
                ]
            ]
        );

        $repeater->add_control(
            'kng_styled_txt_color',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}.king-addons-styled-text' => 'color: {{VALUE}};',
                    '{{WRAPPER}} {{CURRENT_ITEM}}.king-addons-styled-text a' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'kng_styled_txt_content_type' => 'text'
                ]
            ]
        );

        $repeater->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'kng_styled_txt_bg_color',
                'selector' => '{{WRAPPER}} {{CURRENT_ITEM}}.king-addons-styled-text',
                'condition' => [
                    'kng_styled_txt_content_type' => 'text'
                ],
                'separator' => 'before',
            ]
        );

        $repeater->add_control(
            'kng_styled_txt_custom_vertical_align',
            [
                'label' => esc_html__('Custom Vertical Align', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'condition' => [
                    'kng_styled_txt_content_type' => 'text'
                ],
                'separator' => 'before',
            ]
        );

        $repeater->add_responsive_control(
            'kng_styled_txt_vertical_align',
            [
                'label' => esc_html__('Vertical Align', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'baseline' => esc_html__('baseline', 'king-addons'),
                    'top' => esc_html__('top', 'king-addons'),
                    'bottom' => esc_html__('bottom', 'king-addons'),
                    'sub' => esc_html__('sub', 'king-addons'),
                    'super' => esc_html__('super', 'king-addons'),
                    'text-top' => esc_html__('text-top', 'king-addons'),
                    'text-bottom' => esc_html__('text-bottom', 'king-addons'),
                    'middle' => esc_html__('middle', 'king-addons'),
                ],
                'default' => 'baseline',
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}.king-addons-styled-text' => 'vertical-align: {{VALUE}};',
                ],
                'condition' => [
                    'kng_styled_txt_custom_vertical_align!' => '',
                    'kng_styled_txt_content_type' => 'text'
                ]
            ]
        );

        $repeater->add_control(
            'kng_styled_txt_padding_switcher',
            [
                'label' => esc_html__('Custom Padding', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'condition' => [
                    'kng_styled_txt_content_type' => 'text'
                ]
            ]
        );

        $repeater->add_responsive_control(
            'kng_styled_txt_padding',
            [
                'label' => esc_html__('Padding', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'default' => [
                    'top' => 0,
                    'right' => 0,
                    'bottom' => 0,
                    'left' => 0,
                ],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}.king-addons-styled-text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'kng_styled_txt_padding_switcher!' => '',
                    'kng_styled_txt_content_type' => 'text'
                ]
            ]
        );

        $repeater->add_control(
            'kng_styled_txt_margin_switcher',
            [
                'label' => esc_html__('Custom Margin', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'condition' => [
                    'kng_styled_txt_content_type' => 'text'
                ]
            ]
        );

        $repeater->add_responsive_control(
            'kng_styled_txt_margin_left',
            [
                'label' => esc_html__('Margin Left', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'step' => 1,
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}.king-addons-styled-text' => 'margin-left: {{SIZE}}px;',
                ],
                'condition' => [
                    'kng_styled_txt_margin_switcher!' => '',
                    'kng_styled_txt_content_type' => 'text'
                ]
            ]
        );

        $repeater->add_responsive_control(
            'kng_styled_txt_margin_right',
            [
                'label' => esc_html__('Margin Right', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'step' => 1,
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}.king-addons-styled-text' => 'margin-right: {{SIZE}}px;',
                ],
                'condition' => [
                    'kng_styled_txt_margin_switcher!' => '',
                    'kng_styled_txt_content_type' => 'text'
                ]
            ]
        );

        $repeater->add_control(
            'kng_styled_txt_margin_info',
            [
                'type' => Controls_Manager::ALERT,
                'alert_type' => 'info',
                'content' => esc_html__('For vertical spacing use the line-height parameter in Typography, or optionally Custom Padding - top and bottom. Margin top and bottom parameters are available when Custom Width value is set. This is because of current HTML/CSS limitations for multiline text.', 'king-addons'),
                'condition' => [
                    'kng_styled_txt_margin_switcher!' => '',
                    'kng_styled_txt_content_type' => 'text'
                ]
            ]
        );

        $repeater->add_control(
            'kng_styled_txt_custom_border',
            [
                'label' => esc_html__('Custom Border', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'condition' => [
                    'kng_styled_txt_content_type' => 'text'
                ]
            ]
        );

        $repeater->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'kng_styled_txt_border',
                'selector' => '{{WRAPPER}} {{CURRENT_ITEM}}.king-addons-styled-text',
                'condition' => [
                    'kng_styled_txt_custom_border!' => '',
                    'kng_styled_txt_content_type' => 'text'
                ]
            ]
        );

        $repeater->add_responsive_control(
            'kng_styled_txt_border_radius',
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
                    '{{WRAPPER}} {{CURRENT_ITEM}}.king-addons-styled-text' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'kng_styled_txt_custom_border!' => '',
                    'kng_styled_txt_content_type' => 'text'
                ]
            ]
        );

        $repeater->add_control(
            'kng_styled_txt_custom_width_switcher',
            [
                'label' => esc_html__('Custom Width', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'condition' => [
                    'kng_styled_txt_content_type' => 'text'
                ]
            ]
        );

        $repeater->add_responsive_control(
            'kng_styled_txt_custom_width',
            [
                'label' => esc_html__('Width (px)', 'king-addons'),
                'description' => esc_html__('The custom width of the current content item is useful for creating multiline text snippets or limiting the image size.', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'step' => 1,
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}.king-addons-styled-text' => 'width: {{SIZE}}px; display: inline-block;',
                ],
                'condition' => [
                    'kng_styled_txt_custom_width_switcher!' => '',
                    'kng_styled_txt_content_type' => 'text'
                ]
            ]
        );

        $repeater->add_responsive_control(
            'kng_styled_txt_margin_top',
            [
                'label' => esc_html__('Margin Top', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'step' => 1,
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}.king-addons-styled-text' => 'margin-top: {{SIZE}}px;',
                ],
                'condition' => [
                    'kng_styled_txt_custom_width_switcher!' => '',
                    'kng_styled_txt_content_type' => 'text'
                ]
            ]
        );

        $repeater->add_responsive_control(
            'kng_styled_txt_margin_bottom',
            [
                'label' => esc_html__('Margin Bottom', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'step' => 1,
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}.king-addons-styled-text' => 'margin-bottom: {{SIZE}}px;',
                ],
                'condition' => [
                    'kng_styled_txt_custom_width_switcher!' => '',
                    'kng_styled_txt_content_type' => 'text'
                ]
            ]
        );

        /** ====================================================== */
        /** TEXT EFFECTS ========================================== */
        $repeater->add_control(
            'kng_styled_txt_effect',
            [
                'label' => '<b>' . esc_html__('Text Effect', 'king-addons') . '</b>',
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'none' => esc_html__('None', 'king-addons'),
                    'text-gradient' => esc_html__('Text Gradient', 'king-addons'),
                    'underline-gradient' => esc_html__('Underline Gradient', 'king-addons'),
                    'outline-stroke' => esc_html__('Outline Stroke', 'king-addons'),
                ],
                'label_block' => true,
                'default' => 'none',
                'separator' => 'before',
                'condition' => [
                    'kng_styled_txt_content_type' => 'text'
                ]
            ]
        );

        /** Outline Stroke ========================================== */
        $repeater->add_control(
            'kng_styled_txt_effect_stroke_txt_color',
            [
                'label' => esc_html__('Text Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#0000',
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}.king-addons-styled-text .king-addons-styled-text-inner span' => 'color: {{VALUE}};'
                ],
                'condition' => [
                    'kng_styled_txt_effect' => 'outline-stroke',
                    'kng_styled_txt_content_type' => 'text'
                ]
            ]
        );

        $repeater->add_control(
            'kng_styled_txt_effect_stroke_stroke_color',
            [
                'label' => esc_html__('Stroke Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#dbdbdb',
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}.king-addons-styled-text .king-addons-styled-text-inner span' => '-webkit-text-stroke-color: {{VALUE}};'
                ],
                'condition' => [
                    'kng_styled_txt_effect' => 'outline-stroke',
                    'kng_styled_txt_content_type' => 'text'
                ]
            ]
        );

        $repeater->add_control(
            'kng_styled_txt_effect_stroke_width',
            [
                'label' => esc_html__('Stroke Width (px)', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'step' => 1,
                'default' => 1,
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}.king-addons-styled-text .king-addons-styled-text-inner span' => '-webkit-text-stroke-width: {{SIZE}}px;',
                ],
                'condition' => [
                    'kng_styled_txt_effect' => 'outline-stroke',
                    'kng_styled_txt_content_type' => 'text'
                ]
            ]
        );
        /** END Outline Stroke ========================================== */

        /** Text Gradient ========================================== */
        $repeater->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'kng_styled_txt_effect_gradient',
                'types' => ['gradient'],
                'fields_options' => [
                    'color' => [
                        'default' => '#574ff7',
                    ],
                    'background' => [
                        'default' => 'gradient',
                    ]
                ],
                'exclude' => ['classic', 'image'],
                'selector' => '{{WRAPPER}} {{CURRENT_ITEM}}.king-addons-styled-text .king-addons-styled-text-inner',
                'condition' => [
                    'kng_styled_txt_effect' => 'text-gradient',
                    'kng_styled_txt_content_type' => 'text'
                ]
            ]
        );
        /** END: Text Gradient ========================================== */

        /** Underline Gradient ========================================== */
        $repeater->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'kng_styled_txt_underline_effects_gradient',
                'types' => ['gradient'],
                'fields_options' => [
                    'color' => [
                        'default' => '#84fab0',
                    ],
                    'color_b' => [
                        'default' => '#8fd3f4',
                    ],
                    'background' => [
                        'default' => 'gradient',
                    ],
                    'gradient_type' => [
                        'default' => 'linear'
                    ],
                    'gradient_angle' => [
                        'default' => [
                            'unit' => 'deg',
                            'size' => 120,
                        ],
                    ]
                ],
                'exclude' => ['classic', 'image'],
                'selector' => '{{WRAPPER}} {{CURRENT_ITEM}}.king-addons-styled-text .king-addons-styled-text-inner',
                'condition' => [
                    'kng_styled_txt_effect' => 'underline-gradient',
                    'kng_styled_txt_content_type' => 'text'
                ]
            ]
        );

        $repeater->add_control(
            'kng_styled_txt_underline_effects_bg_position',
            [
                'label' => esc_html__('Vertical Position (%)', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['%'],
                'range' => [
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => '%',
                    'size' => 88,
                ],
                'separator' => 'before',
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}.king-addons-styled-text .king-addons-styled-text-inner' => 'background-position: 0 {{SIZE}}{{UNIT}}; background-repeat: no-repeat;',
                ],
                'condition' => [
                    'kng_styled_txt_effect' => 'underline-gradient',
                    'kng_styled_txt_content_type' => 'text'
                ]
            ]
        );

        $repeater->add_control(
            'kng_styled_txt_underline_effects_bg_height',
            [
                'label' => esc_html__('Height (%)', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['%'],
                'range' => [
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => '%',
                    'size' => 20,
                ],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}.king-addons-styled-text .king-addons-styled-text-inner' => 'background-size: 100% {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'kng_styled_txt_effect' => 'underline-gradient',
                    'kng_styled_txt_content_type' => 'text'
                ]
            ]
        );

        $repeater->add_control(
            'kng_styled_txt_underline_effects_bg_height_hover',
            [
                'label' => esc_html__('Height on mouse hover (%)', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['%'],
                'range' => [
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => '%',
                    'size' => 88,
                ],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}.king-addons-styled-text .king-addons-styled-text-inner:hover' => 'background-size: 100% {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'kng_styled_txt_effect' => 'underline-gradient',
                    'kng_styled_txt_content_type' => 'text'
                ]
            ]
        );

        /** END: Underline Gradient ========================================== */

        /** END: TEXT EFFECTS ========================================== */
        /** =========================================================== */

        $repeater->add_control(
            'kng_styled_txt_content',
            [
                'label' => '<b>' . esc_html__('Text', 'king-addons') . '</b>',
                'type' => Controls_Manager::TEXTAREA,
                'default' => esc_html__('Text', 'king-addons'),
                'condition' => [
                    'kng_styled_txt_content_type' => 'text'
                ],
                'separator' => 'before',
            ]
        );
        /** END: Content Type TEXT ========================================== */

        /** Content Type IMAGE ========================================== */
        $repeater->add_control(
            'kng_styled_txt_image',
            [
                'label' => esc_html__('Image', 'king-addons'),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
                'condition' => [
                    'kng_styled_txt_content_type' => 'image'
                ]
            ]
        );

        $repeater->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name' => 'kng_styled_txt_image_size',
                'default' => 'full',
                'separator' => 'before',
                'condition' => [
                    'kng_styled_txt_content_type' => 'image'
                ]
            ]
        );

        $repeater->add_responsive_control(
            'kng_image_comparison_box_max_width',
            [
                'label' => esc_html__('Width (px)', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'step' => 1,
                'default' => 50,
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}.king-addons-styled-text img' => 'width: {{SIZE}}px;'
                ],
                'condition' => [
                    'kng_styled_txt_content_type' => 'image'
                ]
            ]
        );

        $repeater->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'kng_styled_txt_image_shadow',
                'selector' => '{{WRAPPER}} {{CURRENT_ITEM}}.king-addons-styled-text img',
                'condition' => [
                    'kng_styled_txt_content_type' => 'image'
                ]
            ]
        );

        $repeater->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'kng_styled_txt_image_border',
                'selector' => '{{WRAPPER}} {{CURRENT_ITEM}}.king-addons-styled-text img',
                'condition' => [
                    'kng_styled_txt_content_type' => 'image'
                ]
            ]
        );

        $repeater->add_responsive_control(
            'kng_styled_txt_image_border_radius',
            [
                'label' => esc_html__('Border Radius', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}.king-addons-styled-text img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'kng_styled_txt_content_type' => 'image'
                ]
            ]
        );

        $repeater->add_control(
            'kng_styled_txt_image_custom_vertical_align',
            [
                'label' => esc_html__('Custom Vertical Align', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'condition' => [
                    'kng_styled_txt_content_type' => 'image'
                ]
            ]
        );

        $repeater->add_responsive_control(
            'kng_styled_txt_image_vertical_align',
            [
                'label' => esc_html__('Vertical Align', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'baseline' => esc_html__('baseline', 'king-addons'),
                    'top' => esc_html__('top', 'king-addons'),
                    'bottom' => esc_html__('bottom', 'king-addons'),
                    'sub' => esc_html__('sub', 'king-addons'),
                    'super' => esc_html__('super', 'king-addons'),
                    'text-top' => esc_html__('text-top', 'king-addons'),
                    'text-bottom' => esc_html__('text-bottom', 'king-addons'),
                    'middle' => esc_html__('middle', 'king-addons'),
                ],
                'default' => 'baseline',
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}.king-addons-styled-text img' => 'vertical-align: {{VALUE}};',
                ],
                'condition' => [
                    'kng_styled_txt_image_custom_vertical_align!' => '',
                    'kng_styled_txt_content_type' => 'image'
                ]
            ]
        );

        $repeater->add_control(
            'kng_styled_txt_image_margin_switcher',
            [
                'label' => esc_html__('Custom Margin', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'condition' => [
                    'kng_styled_txt_content_type' => 'image'
                ]
            ]
        );

        $repeater->add_responsive_control(
            'kng_styled_txt_image_margin',
            [
                'label' => esc_html__('Margin', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'default' => [
                    'top' => 0,
                    'right' => 0,
                    'bottom' => 0,
                    'left' => 0,
                ],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}.king-addons-styled-text' => 'margin: 0;',
                    '{{WRAPPER}} {{CURRENT_ITEM}}.king-addons-styled-text img' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'kng_styled_txt_image_margin_switcher!' => '',
                    'kng_styled_txt_content_type' => 'image'
                ]
            ]
        );
        /** END: Content Type IMAGE ========================================== */

        $repeater->add_control(
            'kng_styled_txt_content_common_for_all_types_head',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Common for all types', 'king-addons'),
                'type' => Controls_Manager::HEADING,
            ]
        );

        $repeater->add_control(
            'kng_styled_txt_custom_css_class_switcher',
            [
                'label' => esc_html__('Custom CSS Class', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
            ]
        );

        $repeater->add_control(
            'kng_styled_txt_custom_css_class',
            [
                'label' => esc_html__('CSS Class', 'king-addons'),
                'type' => Controls_Manager::TEXT,
                'placeholder' => esc_html__('Type or paste the class', 'king-addons'),
                'ai' => [
                    'active' => false,
                ],
                'separator' => 'after',
                'condition' => [
                    'kng_styled_txt_custom_css_class_switcher!' => '',
                ]
            ]
        );

        $repeater->add_control(
            'kng_styled_txt_transform_switcher',
            [
                'label' => esc_html__('Transform', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
            ]
        );

        $repeater->add_responsive_control(
            'kng_styled_txt_transform_rotate',
            [
                'label' => esc_html__('Rotate', 'king-addons') . ' (deg)',
                'type' => Controls_Manager::NUMBER,
                'min' => -360,
                'max' => 360,
                'step' => 5,
                'default' => 0,
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}.king-addons-styled-text .king-addons-styled-text-inner' => 'transform: rotateZ({{SIZE}}deg); display: inline-block;',
                ],
                'condition' => [
                    'kng_styled_txt_transform_switcher!' => '',
                ],
                'frontend_available' => true,
            ]
        );

        $repeater->add_responsive_control(
            'kng_styled_txt_transform_offset_X',
            [
                'label' => esc_html__('Offset X', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em', 'rem', 'vw', 'custom'],
                'range' => [
                    '%' => [
                        'min' => -100,
                        'max' => 100,
                    ],
                    'px' => [
                        'min' => -1000,
                        'max' => 1000,
                    ],
                ],
                'condition' => [
                    'kng_styled_txt_transform_switcher!' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}.king-addons-styled-text .king-addons-styled-text-inner span' => 'transform: translateX({{SIZE}}{{UNIT}}); display: inline-block;',
                    '{{WRAPPER}} {{CURRENT_ITEM}}.king-addons-styled-text .king-addons-styled-text-inner img' => 'transform: translateX({{SIZE}}{{UNIT}}); display: inline-block;',
                ],
                'frontend_available' => true,
            ]
        );

        $repeater->add_responsive_control(
            'kng_styled_txt_transform_offset_Y',
            [
                'label' => esc_html__('Offset Y', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em', 'rem', 'vh', 'custom'],
                'range' => [
                    '%' => [
                        'min' => -100,
                        'max' => 100,
                    ],
                    'px' => [
                        'min' => -1000,
                        'max' => 1000,
                    ],
                ],
                'condition' => [
                    'kng_styled_txt_transform_switcher!' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}.king-addons-styled-text' => 'transform: translateY({{SIZE}}{{UNIT}}); display: inline-block;',
                ],
                'frontend_available' => true,
            ]
        );

        $repeater->add_control(
            'kng_styled_txt_link_switcher',
            [
                'label' => esc_html__('Link', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
            ]
        );

        $repeater->add_control(
            'kng_styled_txt_link',
            [
                'label' => esc_html__('Link', 'king-addons'),
                'type' => Controls_Manager::URL,
                'condition' => [
                    'kng_styled_txt_link_switcher!' => '',
                ]
            ]
        );

        $this->add_control(
            'kng_styled_txt_content_items',
            [
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'kng_styled_txt_content_type' => 'text',
                        'kng_styled_txt_content' => esc_html__('King', 'king-addons'),
                    ],
                    [
                        'kng_styled_txt_content_type' => 'image',
                        'kng_styled_txt_image' => Utils::get_placeholder_image_src(),
                    ],
                    [
                        'kng_styled_txt_content_type' => 'text',
                        'kng_styled_txt_content' => esc_html__('Addons', 'king-addons'),
                    ],
                ],
                'title_field' => '<# if( "text" === kng_styled_txt_content_type ) { #> {{kng_styled_txt_content}} <# } else if( "image" === kng_styled_txt_content_type) {#> <img class="king-addons-repeater-list-img-icon" src="{{kng_styled_txt_image.url}}"> <# } #>',
            ]
        );

        $this->end_controls_section();
        /** END SECTION: Texts ===================== */
        /** END TAB: CONTENT ===================== */

        /** START TAB: STYLE ===================== */
        /** SECTION: Common Styles ===================== */
        $this->start_controls_section(
            'kng_styled_txt_style_section_commmon_styles',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Common Styles', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => esc_html__('Typography', 'king-addons'),
                'name' => 'kng_styled_txt_common_typography',
                'selector' => '{{WRAPPER}} .king-addons-styled-text',
                'fields_options' => [
                    'font_size' => [
                        'default' => [
                            'size' => 50,
                            'unit' => 'px'
                        ],
                    ],
                    'typography' => [
                        'default' => 'custom',
                    ]
                ],
            ]
        );

        $this->add_control(
            'kng_styled_txt_common_color',
            [
                'label' => esc_html__('Text Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-styled-text' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'kng_styled_txt_common_content_align',
            [
                'label' => esc_html__('Content Align', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'left' => esc_html__('Left', 'king-addons'),
                    'center' => esc_html__('Center', 'king-addons'),
                    'right' => esc_html__('Right', 'king-addons'),
                ],
                'default' => 'left',
                'separator' => 'before',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-styled-text-builder-items' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'kng_styled_txt_common_items_margin',
            [
                'label' => esc_html__('Items Margin', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'default' => [
                    'top' => 0,
                    'right' => 10,
                    'bottom' => 0,
                    'left' => 0,
                    'unit' => 'px',
                    'isLinked' => false
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-styled-text' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'kng_styled_txt_common_items_padding',
            [
                'label' => esc_html__('Items Padding', 'king-addons'),
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
                    '{{WRAPPER}} .king-addons-styled-text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->end_controls_section();
        /** END SECTION: Common Styles ===================== */
        /** END TAB: STYLE ===================== */
    }

    protected function render(): void
    {
        $settings = $this->get_settings();
        ?>
        <div class="king-addons-styled-text-builder-items">
            <?php

            $item_count = 0;

            /** @noinspection PhpUnusedLocalVariableInspection */
            foreach ($settings['kng_styled_txt_content_items'] as $key => $item) {

                $content_type = $item['kng_styled_txt_content_type'];
                $item_tag = 'span';

                $this->add_render_attribute('kng_styled_txt_content_inner_attribute' . $item_count, 'class', 'king-addons-styled-text-inner');

                // If the item has link
                if ('yes' === $item['kng_styled_txt_link_switcher']) {
                    if ('' !== $item['kng_styled_txt_link']['url']) {

                        $item_tag = 'a';

                        $this->add_render_attribute('kng_styled_txt_content_inner_attribute' . $item_count, 'href', esc_url($item['kng_styled_txt_link']['url']));

                        if ($item['kng_styled_txt_link']['is_external']) {
                            $this->add_render_attribute('kng_styled_txt_content_inner_attribute' . $item_count, 'target', '_blank');
                        }

                        if ($item['kng_styled_txt_link']['nofollow']) {
                            $this->add_render_attribute('kng_styled_txt_content_inner_attribute' . $item_count, 'rel', 'nofollow');
                        }

                    }
                }

                // If the item has custom CSS class
                if ('yes' === $item['kng_styled_txt_custom_css_class_switcher']) {
                    if ('' !== $item['kng_styled_txt_custom_css_class']) {
                        $this->add_render_attribute('kng_styled_txt_content_inner_attribute' . $item_count, 'class', esc_attr($item['kng_styled_txt_custom_css_class']));
                    }
                }

                /** START: Text Item ===================== */
                if ('text' === $content_type) {

                    $txt_effect = $item['kng_styled_txt_effect'];
                    $txt_effect_class = '';

                    switch ($txt_effect) {
                        case 'text-gradient':
                            $txt_effect_class = ' king-addons-styled-text-gradient';
                            break;
                        case 'underline-gradient':
                            $txt_effect_class = ' king-addons-styled-text-underline-gradient';
                            break;
                    }

                    if ('' !== $item['kng_styled_txt_content']) {

                        echo '<span class="elementor-repeater-item-' .
                            esc_attr($item['_id']) .
                            esc_attr($txt_effect_class) . ' king-addons-styled-text">';

                        $html = '<' . esc_attr($item_tag) . ' ' . $this->get_render_attribute_string('kng_styled_txt_content_inner_attribute' . $item_count) . '>';
                        echo wp_kses($html, wp_kses_allowed_html('post'));

                        echo '<span>' . wp_kses_post($item['kng_styled_txt_content']) . '</span>';
                        echo '</' . esc_attr($item_tag) . '></span>';

                    }

                }
                /** END: Text Item ===================== */

                /** START: Image Item ===================== */
                if ('image' === $content_type) {

                    if ('' !== $item['kng_styled_txt_image']) {

                        echo '<span class="elementor-repeater-item-' .
                            esc_attr($item['_id']) . ' king-addons-styled-text">';

                        $html = '<' . esc_attr($item_tag) . ' ' . $this->get_render_attribute_string('kng_styled_txt_content_inner_attribute' . $item_count) . '>';
                        echo wp_kses($html, wp_kses_allowed_html('post'));

                        $image_html = Group_Control_Image_Size::get_attachment_image_html($item, 'kng_styled_txt_image_size', 'kng_styled_txt_image');

                        // Define allowed tags and attributes
                        $allowed_tags = wp_kses_allowed_html('post');
                        $allowed_tags['img']['srcset'] = true; // Allow srcset attribute for img tag
                        $allowed_tags['img']['sizes'] = true; // Allow sizes attribute for img tag
                        $allowed_tags['img']['decoding'] = true; // Allow decoding attribute for img tag
                        $allowed_tags['img']['loading'] = true; // Allow loading attribute for img tag. It is for the lazy loading possibility.

                        // Sanitize the image HTML using the extended set of allowed tags and attributes
                        echo wp_kses($image_html, $allowed_tags);

                        echo '</' . esc_attr($item_tag) . '></span>';

                    }

                }
                /** END: Image Item ===================== */

                $item_count++;
            } ?>
        </div>
        <?php
    }
}