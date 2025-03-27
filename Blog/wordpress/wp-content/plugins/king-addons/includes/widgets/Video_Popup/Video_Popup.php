<?php /** @noinspection SpellCheckingInspection, DuplicatedCode, PhpUnused */

namespace King_Addons;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Icons_Manager;
use Elementor\Plugin;
use Elementor\Widget_Base;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class Video_Popup extends Widget_Base
{
    public function get_name(): string
    {
        return 'king-addons-video-popup';
    }

    public function get_title(): string
    {
        return esc_html__('Video Popup', 'king-addons');
    }

    public function get_icon(): string
    {
        /** @noinspection SpellCheckingInspection */
        return 'king-addons-icon king-addons-video-popup';
    }

    public function get_style_depends(): array
    {
        return [KING_ADDONS_ASSETS_UNIQUE_KEY . '-video-popup-style'];
    }

    public function get_categories(): array
    {
        return ['king-addons'];
    }

    public function get_keywords(): array
    {
        return ['off', 'canvas', 'offcanvas', 'ofcavas', 'off-canvas', 'content', 'button', 'sidebar', 'side', 'bar',
            'menu', 'popup', 'nav', 'navigation', 'animation', 'effect', 'animated', 'template', 'link', 'left',
            'right', 'top', 'bottom', 'vertical', 'horizontal', 'mouse', 'hover', 'over', 'hover over', 'picture',
            'float', 'floating', 'sticky', 'click', 'target', 'point', 'king', 'addons', 'mouseover', 'page', 'center',
            'kingaddons', 'king-addons', 'off canvas', 'pop up', 'popup', 'lightbox', 'box', 'modal', 'window', 'tab',
            'appear', 'show', 'hide', 'up', 'video', 'play', 'player', 'frame', 'iframe', 'embed', 'file', 'mp4', 'url',
            'youtube', 'vimeo', 'dailymotion', 'videopress', 'hosted', 'source', 'external', 'internal', 'hosting'];
    }

    public function get_custom_help_url(): string
    {
        return 'https://kingaddons.com/elementor/video-popup/';
    }

    protected function register_controls(): void
    {
        /** START TAB: CONTENT ===================== */
        /** SECTION: General ===================== */
        $this->start_controls_section(
            'kng_video_popup_section_general',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('General', 'king-addons'),
            ]
        );

        /** VIDEO */

        $this->add_control(
            'kng_video_popup_video_type',
            [
                'label' => '<b>' . esc_html__('Source', 'king-addons') . '</b>',
                'type' => Controls_Manager::SELECT,
                'default' => 'youtube',
                'options' => [
                    'youtube' => esc_html__('YouTube', 'king-addons'),
                    'vimeo' => esc_html__('Vimeo', 'king-addons'),
                    'hosted' => esc_html__('Self Hosted', 'king-addons'),
                    'external-hosted' => esc_html__('External Hosted', 'king-addons'),
                ],
            ]
        );

        $this->add_control(
            'kng_video_popup_youtube_url_notice',
            [
                'type' => Controls_Manager::NOTICE,
                'notice_type' => 'warning',
                'dismissible' => false,
                'heading' => esc_html__( 'Link code of the video', 'king-addons' ),
                'content' => esc_html__( 'For example for https://www.youtube.com/watch?v=XHOmBV4js_E it will be XHOmBV4js_E', 'king-addons' ),
                'condition' => [
                    'kng_video_popup_video_type' => 'youtube',
                ],
            ]
        );

        $this->add_control(
            'kng_video_popup_youtube_url',
            [
                'label' => '<b>' . esc_html__('Link code of the video', 'king-addons') . '</b>',
                'type' => Controls_Manager::TEXT,
                'placeholder' => esc_html__('Enter your video code', 'king-addons') . ' (YouTube)',
                'default' => 'XHOmBV4js_E',
                'label_block' => false,
                'condition' => [
                    'kng_video_popup_video_type' => 'youtube',
                ],
                'ai' => [
                    'active' => false,
                ],
            ]
        );

        $this->add_control(
            'kng_video_popup_vimeo_url_notice',
            [
                'type' => Controls_Manager::NOTICE,
                'notice_type' => 'warning',
                'dismissible' => false,
                'heading' => esc_html__( 'Link code of the video', 'king-addons' ),
                'content' => esc_html__( 'For example for https://vimeo.com/235215203 it will be 235215203', 'king-addons' ),
                'condition' => [
                    'kng_video_popup_video_type' => 'vimeo',
                ],
            ]
        );

        $this->add_control(
            'kng_video_popup_vimeo_url',
            [
                'label' => '<b>' . esc_html__('Link code of the video', 'king-addons') . '</b>',
                'type' => Controls_Manager::TEXT,
                'placeholder' => esc_html__('Enter your video code', 'king-addons') . ' (Vimeo)',
                'default' => '235215203',
                'label_block' => false,
                'condition' => [
                    'kng_video_popup_video_type' => 'vimeo',
                ],
                'ai' => [
                    'active' => false,
                ],
            ]
        );

        $this->add_control(
            'kng_video_popup_hosted_url',
            [
                'label' => esc_html__('Choose Video File', 'king-addons'),
                'type' => Controls_Manager::MEDIA,
                'media_types' => [
                    'video',
                ],
                'condition' => [
                    'kng_video_popup_video_type' => 'hosted',
                ],
            ]
        );

        $this->add_control(
            'kng_video_popup_external_url',
            [
                'label' => esc_html__('URL', 'king-addons'),
                'type' => Controls_Manager::URL,
                'label_block' => true,
                'placeholder' => esc_html__('Enter your URL', 'king-addons'),
                'condition' => [
                    'kng_video_popup_video_type' => 'external-hosted',
                ],
            ]
        );

        $this->add_control(
            'kng_video_popup_start_time',
            [
                'label' => esc_html__('Start Time', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'description' => esc_html__('Specify a start time (in seconds)', 'king-addons'),
                'condition' => [
                    'kng_video_popup_video_type' => 'youtube',
                ],
                'separator' => 'before',
            ]
        );

        /** END: VIDEO */

        $this->add_control(
            'kng_video_popup_box_position',
            [
                'label' => esc_html__('Box Animation Direction', 'king-addons'),
                'type' => Controls_Manager::CHOOSE,
                'default' => 'bottom',
                'options' => [
                    'top' => [
                        'title' => esc_html__('Top', 'king-addons'),
                        'icon' => 'eicon-v-align-bottom',
                    ],
                    'right' => [
                        'title' => esc_html__('Right', 'king-addons'),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'bottom' => [
                        'title' => esc_html__('Bottom', 'king-addons'),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'left' => [
                        'title' => esc_html__('Left', 'king-addons'),
                        'icon' => 'eicon-h-align-right',
                    ],
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'kng_video_popup_box_max_width_switcher',
            [
                'label' => esc_html__('Set Maximum Width of popup box', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'separator' => 'before'
            ]
        );

        $this->add_responsive_control(
            'kng_video_popup_box_max_width',
            [
                'label' => esc_html__('Maximum Width (px)', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'step' => 1,
                'default' => '',
                'selectors' => [
                    '.king-addons-video-popup-{{ID}}' => 'max-width: {{VALUE}}px;',
                ],
                'condition' => [
                    'kng_video_popup_box_max_width_switcher!' => '',
                ]
            ]
        );

        $this->add_control(
            'kng_video_popup_box_max_height_switcher',
            [
                'label' => esc_html__('Set Maximum Height of popup box', 'king-addons'),
                'type' => Controls_Manager::SWITCHER
            ]
        );

        $this->add_responsive_control(
            'kng_video_popup_box_max_height',
            [
                'label' => esc_html__('Maximum Height (px)', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'step' => 1,
                'default' => '',
                'selectors' => [
                    '.king-addons-video-popup-{{ID}}' => 'max-height: {{VALUE}}px;',
                ],
                'condition' => [
                    'kng_video_popup_box_max_height_switcher!' => '',
                ]
            ]
        );

        $this->add_control(
            'kng_video_popup_disable_btn',
            [
                'label' => esc_html__('Disable Button', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'description' => esc_html__('This is a useful feature if there is a need to set a custom element as the trigger instead of the default button.', 'king-addons'),
                'separator' => 'before'
            ]
        );

        $this->add_control(
            'kng_video_popup_class',
            [
                'label' => esc_html__('CSS Class of trigger element (optional)', 'king-addons'),
                'description' => esc_html__('Apply a specific CSS class to your custom trigger element, such as a button, text, a menu item, etc. You can add the class in the Advanced tab of the new trigger element. Then, enter the same class in this field as well. This way, the Popup will open when you click on the trigger element. Please check the result on the live website, due to limitations of the Elementor preview mode.', 'king-addons'),
                'type' => Controls_Manager::TEXT,
                'placeholder' => esc_html__('Type your class here', 'king-addons'),
                'label_block' => true,
                'ai' => [
                    'active' => false,
                ],
            ]
        );

        $this->end_controls_section();
        /** END SECTION: General ===================== */
        /** END TAB: CONTENT ===================== */

        /** TAB: STYLE ===================== */
        /** SECTION: Button ===================== */
        $this->start_controls_section(
            'kng_video_popup_section_style_btn',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Button', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'kng_video_popup_btn_icon',
            [
                'label' => esc_html__('Select Icon', 'king-addons'),
                'type' => Controls_Manager::ICONS,
                'skin' => 'inline',
                'label_block' => false,
                'exclude_inline_options' => ['svg'],
                'default' => [
                    'value' => 'fas fa-play',
                    'library' => 'fa-solid',
                ],
            ]
        );

        $this->add_responsive_control(
            'kng_video_popup_btn_h_position',
            [
                'label' => esc_html__('Button Horizontal Position', 'king-addons'),
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
                    '{{WRAPPER}}' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'kng_video_popup_btn_icon_size',
            [
                'label' => esc_html__('Icon Size', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'step' => 1,
                'min' => 0,
                'default' => 20,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-video-popup-button i' => 'font-size: {{VALUE}}px; width: {{VALUE}}px;',
                    '{{WRAPPER}} .king-addons-video-popup-button img' => 'width: {{VALUE}}px; height: {{VALUE}}px;',
                    '{{WRAPPER}} .king-addons-video-popup-button svg' => 'width: {{VALUE}}px; height: {{VALUE}}px;'
                ],
            ]
        );

        $this->add_responsive_control(
            'kng_video_popup_btn_transition_icon',
            [
                'label' => esc_html__('Transition duration for icon on hover (ms)', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'step' => 10,
                'default' => 300,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-video-popup-button i' => 'transition: all {{SIZE}}ms;',
                    '{{WRAPPER}} .king-addons-video-popup-button img' => 'transition: all {{SIZE}}ms;',
                    '{{WRAPPER}} .king-addons-video-popup-button svg' => 'transition: all {{SIZE}}ms;',
                    '{{WRAPPER}} .king-addons-video-popup-button-wrap .king-addons-video-popup-button' => 'transition: all {{SIZE}}ms;',
                ],
            ]
        );

        $this->add_control(
            'kng_video_popup_btn_scale_switcher',
            [
                'label' => esc_html__('Scale button on hover', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_responsive_control(
            'kng_video_popup_btn_scale_hover',
            [
                'label' => esc_html__('Scale on hover', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'step' => 0.01,
                'default' => 1.1,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-video-popup-button-wrap:hover .king-addons-video-popup-button' => 'transform: scale({{SIZE}});'
                ],
                'condition' => [
                    'kng_video_popup_btn_scale_switcher' => 'yes',
                ]
            ]
        );

        $this->add_control(
            'kng_video_popup_btn_effect_type',
            [
                'label' => esc_html__('Button Effect', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'ripple',
                'options' => [
                    'none' => esc_html__('None', 'king-addons'),
                    'ripple' => esc_html__('Ripple', 'king-addons'),
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'kng_video_popup_btn_effect_ripple_color',
            [
                'label' => esc_html__('Ripple Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#000000',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-video-popup-button.king-addons-video-popup-button-effect-ripple:before' => 'border: 1px solid {{VALUE}};',
                ],
                'condition' => [
                    'kng_video_popup_btn_effect_type' => 'ripple',
                ],
            ]
        );

        $this->add_responsive_control(
            'kng_video_popup_btn_padding',
            [
                'label' => esc_html__('Padding', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'default' => [
                    'top' => 30,
                    'right' => 30,
                    'bottom' => 30,
                    'left' => 30,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-video-popup-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'kng_video_popup_btn_margin',
            [
                'label' => esc_html__('Margin', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'default' => [
                    'top' => 30,
                    'right' => 30,
                    'bottom' => 30,
                    'left' => 30,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-video-popup-button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->add_control(
            'kng_video_popup_btn_tabs_title',
            [
                'label' => '<b>' . esc_html__('Button Styles', 'king-addons') . '</b>',
                'type' => Controls_Manager::RAW_HTML,
                'separator' => 'before'
            ]
        );

        $this->start_controls_tabs('kng_video_popup_btn_tabs');

        $this->start_controls_tab(
            'kng_video_popup_btn_tab_normal',
            [
                'label' => esc_html__('Normal', 'king-addons'),
            ]
        );

        $this->add_control(
            'kng_video_popup_btn_txt_color',
            [
                'label' => esc_html__('Icon Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#000000',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-video-popup-button i' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .king-addons-video-popup-button svg' => 'fill: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'kng_video_popup_btn_bg_color',
            [
                'label' => esc_html__('Background Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ededed',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-video-popup-button' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'kng_video_popup_btn_box_shadow',
                'selector' => '{{WRAPPER}} .king-addons-video-popup-button',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'kng_video_popup_btn_border',
                'selector' => '{{WRAPPER}} .king-addons-video-popup-button',
                'fields_options' => [
                    'border' => [
                        'default' => 'none',
                    ],
                ],
            ]
        );

        $this->add_responsive_control(
            'kng_video_popup_btn_border_radius',
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
                    '{{WRAPPER}} .king-addons-video-popup-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'kng_video_popup_btn_tab_hover',
            [
                'label' => esc_html__('Hover', 'king-addons'),
            ]
        );

        $this->add_control(
            'kng_video_popup_btn_txt_color_hover',
            [
                'label' => esc_html__('Icon Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-video-popup-button-wrap:hover .king-addons-video-popup-button i' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .king-addons-video-popup-button-wrap:hover .king-addons-video-popup-button svg' => 'fill: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'kng_video_popup_btn_bg_color_hover',
            [
                'label' => esc_html__('Background Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-video-popup-button:hover' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'kng_video_popup_btn_box_shadow_hover',
                'selector' => '{{WRAPPER}} .king-addons-video-popup-button:hover',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'kng_video_popup_btn_border_hover',
                'selector' => '{{WRAPPER}} .king-addons-video-popup-button:hover',
            ]
        );

        $this->add_responsive_control(
            'kng_video_popup_btn_border_radius_hover',
            [
                'label' => esc_html__('Border Radius', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-video-popup-button:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();
        /** END SECTION: Button ===================== */

        /** SECTION: Popup box ===================== */
        $this->start_controls_section(
            'kng_video_popup_section_style_off_canvas_box',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Popup box', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'kng_video_popup_box_bg_color',
            [
                'label' => esc_html__('Background Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '.king-addons-video-popup-{{ID}}' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'kkng_video_popup_box_padding',
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
                    '.king-addons-video-popup-{{ID}}' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'kng_video_popup_box_box_shadow',
                'selector' => '.king-addons-video-popup-{{ID}}',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'kng_video_popup_box_border',
                'selector' => '.king-addons-video-popup-{{ID}}',
                'fields_options' => [
                    'border' => [
                        'default' => 'none',
                    ],
                ],
            ]
        );

        $this->add_responsive_control(
            'kng_video_popup_box_border_radius',
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
                    '.king-addons-video-popup-{{ID}}' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->add_control(
            'kng_video_popup_box_z_index',
            [
                'label' => esc_html__('Z-Index of Popup box (optional)', 'king-addons'),
                'description' => esc_html__('Note: The Popup box may not display over all elements in the Elementor preview mode here, due to limitations of the Elementor preview mode. However, the Popup box will display properly over all elements on the live website. Please check it there.', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'step' => 1,
                'default' => 999999,
                'separator' => 'before',
                'selectors' => [
                    '.king-addons-video-popup-{{ID}}' => 'z-index: {{VALUE}};',
                    '.king-addons-video-popup-overlay-{{ID}}' => 'z-index: calc({{VALUE}} - 1);',
                ],
            ]
        );

        $this->end_controls_section();
        /** END SECTION: Popup box ===================== */

        /** SECTION: Overlay ===================== */
        $this->start_controls_section(
            'kng_video_popup_overlay',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Overlay', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'kng_video_popup_overlay_color',
            [
                'label' => esc_html__('Overlay Background Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => 'rgba(0, 0, 0, 0.85)',
                'selectors' => [
                    '.king-addons-video-popup-overlay-{{ID}}' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
        /** END SECTION: Overlay ===================== */
        /** END TAB: STYLE ===================== */
    }

    protected function render(): void
    {
        $settings = $this->get_settings_for_display();
        $this_ID = $this->get_id();
        $class_ID = 'king-addons-video-popup-' . $this_ID;
        $overlay_ID = 'king-addons-video-popup-overlay-' . $this_ID;

        // Overlay
        echo '<div class="king-addons-video-popup-overlay ' .
            esc_attr($overlay_ID) . '" onclick="';
        echo "document.querySelector('." .
            esc_attr($class_ID) . "').classList.toggle('king-addons-video-popup-active'); document.querySelector('." .
            esc_attr($overlay_ID) . "').style.opacity = '0'; document.body.style.pointerEvents = 'none'; setTimeout(function () {document.querySelector('." .
            esc_attr($overlay_ID) . "').style.display = 'none'; document.body.style.pointerEvents = '';}, 500);";
        echo '"></div>';

        // START: Popup box
        echo '<div class="king-addons-video-popup ' .
            esc_attr($class_ID) . ' king-addons-video-popup-position-' .
            esc_attr($settings['kng_video_popup_box_position']) . ' king-addons-video-popup-animation-slide"';
        if (!Plugin::$instance->editor->is_edit_mode()) {
            echo ' style="display: none;"';
        }
        echo '>';

        /** VIDEO */
        echo '<div class="king-addons-video-popup-container">';

        // YouTube
        if ('youtube' === $settings['kng_video_popup_video_type']) {
            echo '<iframe src="https://www.youtube.com/embed/' . esc_html($settings['kng_video_popup_youtube_url']) . (('' !== $settings['kng_video_popup_start_time']) ? '?start=' . esc_html($settings['kng_video_popup_start_time']) : '') . '" frameborder="0" allowfullscreen></iframe>';
        }

        // Vimeo
        if ('vimeo' === $settings['kng_video_popup_video_type']) {
            echo '<iframe src="https://player.vimeo.com/video/' . esc_html($settings['kng_video_popup_vimeo_url']) . '" frameborder="0" allowfullscreen ></iframe>';
        }

        // Hosted
        if ('hosted' === $settings['kng_video_popup_video_type']) {
            echo '<video src="' . esc_url($settings['kng_video_popup_hosted_url']['url']) . '" controls></video>';
        }

        // External Hosted
        if ('external-hosted' === $settings['kng_video_popup_video_type']) {
            echo '<video src="' . esc_url($settings['kng_video_popup_external_url']['url']) . '" controls></video>';
        }

        echo '</div>';
        /** END: VIDEO */

        // END: Popup box
        echo '</div>';

        if ('' == $settings['kng_video_popup_disable_btn']) {

            echo '<div class="king-addons-video-popup-button-wrap">';

            echo '<button class="king-addons-video-popup-button king-addons-video-popup-button-' .
                esc_attr($this_ID) . ' king-addons-video-popup-button-effect-' . esc_attr($settings['kng_video_popup_btn_effect_type']) . '" onclick="';
            echo "document.querySelector('." .
                esc_attr($class_ID) . "').classList.toggle('king-addons-video-popup-active'); document.querySelector('." .
                esc_attr($overlay_ID) . "').style.display = 'block'; document.body.style.pointerEvents = 'none'; setTimeout(function () {document.querySelector('." .
                esc_attr($overlay_ID) . "').style.opacity = '1';}, 1); setTimeout(function () {document.body.style.pointerEvents = '';}, 500);";
            echo '">';

            Icons_Manager::render_icon($settings['kng_video_popup_btn_icon']);

            echo '</button>';

            echo '</div>';

        }

        $inline_js_1 = "
            document.addEventListener('DOMContentLoaded', function () {

                const offCanvas = document.querySelector('." . esc_js($class_ID) . "');
                const overlay = document.querySelector('." . esc_js($overlay_ID) . "');

                // Moves all Popupes to right after the <body> opens
                document.body.insertBefore(overlay, document.body.firstChild);
                document.body.insertBefore(offCanvas, document.body.firstChild);

                // Change display from none to block to prevent dancing of the Popup before the DOM content loaded
                offCanvas.style.display = 'block';";

        $inline_js_2 = "";
        if ('' != $settings['kng_video_popup_class']) {
            $inline_js_2 = "
                // Adds click listener for custom triggers that have the custom class
                const customOffCanvasTrigger = document.querySelectorAll('." . esc_js($settings['kng_video_popup_class']) . "');
                customOffCanvasTrigger.forEach(element => element.addEventListener('click', () => {
                    offCanvas.classList.toggle('king-addons-video-popup-active');
                    document.body.style.pointerEvents = 'none';
                    if (offCanvas.classList.contains('king-addons-video-popup-active')) {
                        overlay.style.display = 'block';
                        setTimeout(function () {
                            overlay.style.opacity = '1';
                        }, 1);
                    }
                    setTimeout(function () {
                        document.body.style.pointerEvents = '';
                    }, 500);
                }));
                customOffCanvasTrigger.forEach(element => element.style.cursor = 'pointer'); ";
        }

        $inline_js_3 = "});";

        $inline_js = $inline_js_1 . $inline_js_2 . $inline_js_3;
        wp_print_inline_script_tag($inline_js);
    }
}