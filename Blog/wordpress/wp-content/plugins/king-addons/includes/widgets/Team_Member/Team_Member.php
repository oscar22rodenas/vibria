<?php /** @noinspection PhpUnused */

namespace King_Addons;

use Elementor\Controls_Manager;
use Elementor\Icons_Manager;
use Elementor\Widget_Base;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Utils;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class Team_Member extends Widget_Base
{
    public function get_name(): string
    {
        return 'king-addons-team-member';
    }

    public function get_title(): string
    {
        return esc_html__('Team Member', 'king-addons');
    }

    public function get_icon(): string
    {
        return 'king-addons-icon king-addons-team-member';
    }

    public function get_style_depends(): array
    {
        return [
            KING_ADDONS_ASSETS_UNIQUE_KEY . '-team-member-style',
            KING_ADDONS_ASSETS_UNIQUE_KEY . '-animation-general',
            KING_ADDONS_ASSETS_UNIQUE_KEY . '-animation-button',
            KING_ADDONS_ASSETS_UNIQUE_KEY . '-general-general',
        ];
    }

    public function get_categories(): array
    {
        return ['king-addons'];
    }

    public function get_keywords(): array
    {
        return ['team member', 'team', 'member', 'social', 'names', 'roles', 'photos',
            'social media', 'links', 'social media links', 'king addons', 'king', 'addons', 'kingaddons', 'king-addons'];
    }

    public function get_custom_help_url(): string
    {
        return 'https://kingaddons.com/elementor/team-member/';
    }

    public function add_section_layout()
    {
        $this->start_controls_section(
            'tm_section_layout',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Layout', 'king-addons'),
            ]
        );

        $this->add_control(
            'member_name_location',
            [
                'type' => Controls_Manager::SELECT,
                'label' => esc_html__('Name Location', 'king-addons'),
                'default' => 'below',
                'options' => [
                    'over' => esc_html__('Over Image', 'king-addons'),
                    'below' => esc_html__('Below Image', 'king-addons'),
                ],
            ]
        );

        $this->add_control(
            'member_job_location',
            [
                'type' => Controls_Manager::SELECT,
                'label' => esc_html__('Job Location', 'king-addons'),
                'default' => 'below',
                'options' => [
                    'over' => esc_html__('Over Image', 'king-addons'),
                    'below' => esc_html__('Below Image', 'king-addons'),
                ],
            ]
        );

        $this->add_control(
            'member_divider_location',
            [
                'type' => Controls_Manager::SELECT,
                'label' => esc_html__('Divider Location', 'king-addons'),
                'default' => 'below',
                'options' => [
                    'over' => esc_html__('Over Image', 'king-addons'),
                    'below' => esc_html__('Below Image', 'king-addons'),
                ],
                'condition' => [
                    'member_divider' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'member_description_location',
            [
                'type' => Controls_Manager::SELECT,
                'label' => esc_html__('Description Location', 'king-addons'),
                'default' => 'below',
                'options' => [
                    'over' => esc_html__('Over Image', 'king-addons'),
                    'below' => esc_html__('Below Image', 'king-addons'),
                ],
            ]
        );

        $this->add_control(
            'member_social_media_location',
            [
                'type' => Controls_Manager::SELECT,
                'label' => esc_html__('Socials Location', 'king-addons'),
                'default' => 'below',
                'options' => [
                    'over' => esc_html__('Over Image', 'king-addons'),
                    'below' => esc_html__('Below Image', 'king-addons'),
                ],
                'condition' => [
                    'social_media' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'member_btn_location',
            [
                'type' => Controls_Manager::SELECT,
                'label' => esc_html__('Button Location', 'king-addons'),
                'default' => 'below',
                'options' => [
                    'over' => esc_html__('Over Image', 'king-addons'),
                    'below' => esc_html__('Below Image', 'king-addons'),
                ],
                'condition' => [
                    'member_btn' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'content_vertical_align',
            [
                'label' => esc_html__('Vertical Align', 'king-addons'),
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
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-cv-inner' => 'vertical-align: {{VALUE}}',
                ]
            ]
        );

        $this->end_controls_section();
    }

    public function add_section_image_overlay()
    {
        $this->start_controls_section(
            'tm_section_image_overlay',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Overlay', 'king-addons'),
            ]
        );

        $this->add_control(
            'overlay_animation',
            [
                'label' => esc_html__('Select Animation', 'king-addons'),
                'type' => 'king-addons-animations-alt',
                'default' => 'none',
            ]
        );

        $this->add_control(
            'overlay_anim_size',
            [
                'type' => Controls_Manager::SELECT,
                'label' => esc_html__('Animation Size', 'king-addons'),
                'default' => 'large',
                'options' => [
                    'small' => esc_html__('Small', 'king-addons'),
                    'medium' => esc_html__('Medium', 'king-addons'),
                    'large' => esc_html__('Large', 'king-addons'),
                ],
                'condition' => [
                    'overlay_animation!' => 'none',
                ],
            ]
        );

        $this->add_control(
            'overlay_anim_duration',
            [
                'label' => esc_html__('Animation Duration (ms)', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'default' => 300,
                'min' => 0,
                'step' => 50,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-team-member-animation .king-addons-cv-outer' => '-webkit-transition-duration: {{VALUE}}ms;transition-duration: {{VALUE}}ms;',
                ],
                'condition' => [
                    'overlay_animation!' => 'none',
                ],
            ]
        );

        if (!king_addons_freemius()->can_use_premium_code__premium_only()) {
            $this->add_control(
                'team_member_pro_notice',
                [
                    'type' => Controls_Manager::RAW_HTML,
                    'raw' => 'Advanced overlay animations are available in the <strong><a href="https://kingaddons.com/pricing/?utm_source=kng-module-team-member-settings-upgrade-pro&utm_medium=plugin&utm_campaign=kng" target="_blank">Pro version</a></strong>',
                    'content_classes' => 'king-addons-pro-notice',
                ]
            );
        }

        $this->end_controls_section();
    }

    public function add_section_style_overlay()
    {
        $this->start_controls_section(
            'section_style_overlay',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Overlay', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'image_overlay_section',
            [
                'label' => esc_html__('Image Overlay', 'king-addons'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'image_overlay_bg_color',
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .king-addons-member-overlay',
                'fields_options' => [
                    'color' => [
                        'default' => 'rgba(255, 255, 255, 0.8)',
                    ],
                    'background' => [
                        'default' => 'classic',
                    ]
                ],

            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'overlay_border',
                'label' => esc_html__('Border', 'king-addons'),
                'default' => 'solid',
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
                'selector' => '{{WRAPPER}} .king-addons-member-overlay',
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'image_overlay_padding',
            [
                'label' => esc_html__('Padding', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'default' => [
                    'top' => 10,
                    'right' => 10,
                    'bottom' => 10,
                    'left' => 10,
                ],
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-member-overlay-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->end_controls_section();
    }

    protected function register_controls()
    {

        $this->start_controls_section(
            'section_content',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Content', 'king-addons'),
            ]
        );

        $this->add_control(
            'member_image',
            [
                'label' => esc_html__('Image', 'king-addons'),
                'type' => Controls_Manager::MEDIA,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $this->add_control(
            'member_name',
            [
                'label' => esc_html__('Name', 'king-addons'),
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => esc_html__('John Doe', 'king-addons'),
            ]
        );

        $this->add_control(
            'member_name_tag',
            [
                'type' => Controls_Manager::SELECT,
                'label' => esc_html__('HTML Tag', 'king-addons'),
                'default' => 'h3',
                'options' => [
                    'h1' => esc_html__('H1', 'king-addons'),
                    'h2' => esc_html__('H2', 'king-addons'),
                    'h3' => esc_html__('H3', 'king-addons'),
                    'h4' => esc_html__('H4', 'king-addons'),
                    'h5' => esc_html__('H5', 'king-addons'),
                    'h6' => esc_html__('H6', 'king-addons'),
                    'div' => esc_html__('div', 'king-addons'),
                    'span' => esc_html__('span', 'king-addons'),
                    'p' => esc_html__('p', 'king-addons'),
                ],
            ]
        );

        $this->add_control(
            'member_job',
            [
                'label' => esc_html__('Job', 'king-addons'),
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => esc_html__('King Addons CEO', 'king-addons'),
            ]
        );

        $this->add_control(
            'member_description',
            [
                'label' => esc_html__('Description', 'king-addons'),
                'type' => Controls_Manager::TEXTAREA,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur laoreet cursus volutpat. Aliquam sit amet ligula et justo tincidunt laorsoet non vitae lorem.',
            ]
        );

        $this->add_control(
            'member_description_divider',
            [
                'type' => Controls_Manager::DIVIDER,
                'style' => 'thick',
            ]
        );

        $this->add_control(
            'member_divider',
            [
                'label' => esc_html__('Divider', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'member_divider_position',
            [
                'label' => esc_html__('Position', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'before_job' => esc_html__('Before Job', 'king-addons'),
                    'after_job' => esc_html__('After Job', 'king-addons'),
                ],
                'default' => 'after_job',
                'condition' => [
                    'member_divider' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();

        $this->add_section_layout();

        $this->start_controls_section(
            'section_social_media',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Social Media', 'king-addons'),
            ]
        );
        $this->add_control(
            'social_media',
            [
                'label' => esc_html__('Show Social Media', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes'
            ]
        );

        $this->add_control(
            'social_media_is_external',
            [
                'label' => esc_html__('Open in new window', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'condition' => [
                    'social_media' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'social_media_nofollow',
            [
                'label' => esc_html__('Add nofollow', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'condition' => [
                    'social_media' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'social_section_1',
            [
                'label' => esc_html__('Social 1', 'king-addons'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'social_media' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'social_icon_1',
            [
                'label' => esc_html__('Select Icon', 'king-addons'),
                'type' => Controls_Manager::ICONS,
                'skin' => 'inline',
                'label_block' => false,
                'default' => [
                    'value' => 'fab fa-facebook-f',
                    'library' => 'fa-brands',
                ],
                'condition' => [
                    'social_media' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'social_url_1',
            [
                'label' => esc_html__('Social URL', 'king-addons'),
                'type' => Controls_Manager::URL,
                'dynamic' => [
                    'active' => true,
                ],
                'show_external' => false,
                'placeholder' => esc_html__('https://example.com', 'king-addons'),
                'condition' => [
                    'social_media' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'social_section_2',
            [
                'label' => esc_html__('Social 2', 'king-addons'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'social_media' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'social_icon_2',
            [
                'label' => esc_html__('Select Icon', 'king-addons'),
                'type' => Controls_Manager::ICONS,
                'skin' => 'inline',
                'label_block' => false,
                'default' => [
                    'value' => 'fab fa-x-twitter',
                    'library' => 'fa-brands',
                ],
                'condition' => [
                    'social_media' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'social_url_2',
            [
                'label' => esc_html__('Social URL', 'king-addons'),
                'type' => Controls_Manager::URL,
                'dynamic' => [
                    'active' => true,
                ],
                'show_external' => false,
                'placeholder' => esc_html__('https://example.com', 'king-addons'),
                'condition' => [
                    'social_media' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'social_section_3',
            [
                'label' => esc_html__('Social 3', 'king-addons'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'social_media' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'social_icon_3',
            [
                'label' => esc_html__('Select Icon', 'king-addons'),
                'type' => Controls_Manager::ICONS,
                'skin' => 'inline',
                'label_block' => false,
                'default' => [
                    'value' => 'fab fa-instagram',
                    'library' => 'fa-brands',
                ],
                'condition' => [
                    'social_media' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'social_url_3',
            [
                'label' => esc_html__('Social URL', 'king-addons'),
                'type' => Controls_Manager::URL,
                'dynamic' => [
                    'active' => true,
                ],
                'show_external' => false,
                'placeholder' => esc_html__('https://example.com', 'king-addons'),
                'condition' => [
                    'social_media' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'social_section_4',
            [
                'label' => esc_html__('Social 4', 'king-addons'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'social_media' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'social_icon_4',
            [
                'label' => esc_html__('Select Icon', 'king-addons'),
                'type' => Controls_Manager::ICONS,
                'skin' => 'inline',
                'label_block' => false,
                'default' => [
                    'value' => 'fab fa-linkedin-in',
                    'library' => 'fa-brands',
                ],
                'condition' => [
                    'social_media' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'social_url_4',
            [
                'label' => esc_html__('Social URL', 'king-addons'),
                'type' => Controls_Manager::URL,
                'dynamic' => [
                    'active' => true,
                ],
                'show_external' => false,
                'placeholder' => esc_html__('https://example.com', 'king-addons'),
                'condition' => [
                    'social_media' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'social_section_5',
            [
                'label' => esc_html__('Social 5', 'king-addons'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'social_media' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'social_icon_5',
            [
                'label' => esc_html__('Select Icon', 'king-addons'),
                'type' => Controls_Manager::ICONS,
                'skin' => 'inline',
                'label_block' => false,
                'condition' => [
                    'social_media' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'social_url_5',
            [
                'label' => esc_html__('Social URL', 'king-addons'),
                'type' => Controls_Manager::URL,
                'dynamic' => [
                    'active' => true,
                ],
                'show_external' => false,
                'placeholder' => esc_html__('https://example.com', 'king-addons'),
                'condition' => [
                    'social_media' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_button',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Button', 'king-addons'),
            ]
        );

        $this->add_control(
            'member_btn',
            [
                'label' => esc_html__('Show Button', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
            ]
        );

        $this->add_control(
            'member_btn_text',
            [
                'label' => esc_html__('Button Text', 'king-addons'),
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => 'About Me',
                'condition' => [
                    'member_btn' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'member_btn_url',
            [
                'type' => Controls_Manager::URL,
                'dynamic' => [
                    'active' => true,
                ],
                'placeholder' => esc_html__('https://example.com', 'king-addons'),
                'condition' => [
                    'member_btn' => 'yes',
                ],
                'show_label' => false,
            ]
        );

        $this->end_controls_section();

        $this->add_section_image_overlay();

        Core::renderProFeaturesSection($this, '', Controls_Manager::RAW_HTML, 'team-member', [
            'Advanced Image Overlay Hover Animations',
            'Advanced Button Hover Animations',
        ]);

        $this->start_controls_section(
            'tm_section_style_image',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Image', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'image_width',
            [
                'label' => esc_html__('Width', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['%'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => '%',
                    'size' => 100,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-member-media' => 'max-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name' => 'image_size',
                'default' => 'full',
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'image_border',
                'label' => esc_html__('Border', 'king-addons'),
                'default' => 'solid',
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
                'selector' => '{{WRAPPER}} .king-addons-member-media',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'image_border_radius',
            [
                'label' => esc_html__('Border Radius', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-member-media' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_content',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Content', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'content_bg_color',
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .king-addons-member-content'
            ]
        );

        $this->add_responsive_control(
            'content_padding',
            [
                'label' => esc_html__('Padding', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'default' => [
                    'top' => 20,
                    'right' => 15,
                    'bottom' => 50,
                    'left' => 15,
                ],
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-member-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'name_section',
            [
                'label' => esc_html__('Name', 'king-addons'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'name_color',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#222222',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-member-name' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'name_typography',
                'selector' => '{{WRAPPER}} .king-addons-member-name',
            ]
        );

        $this->add_responsive_control(
            'name_distance',
            [
                'label' => esc_html__('Distance', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 8,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-member-name' => 'margin: 0 0 {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'name_align',
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
                'selectors' => [
                    '{{WRAPPER}} .king-addons-member-name' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'job_section',
            [
                'label' => esc_html__('Job', 'king-addons'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'job_color',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#9e9e9e',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-member-job' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'job_typography',
                'selector' => '{{WRAPPER}} .king-addons-member-job',
            ]
        );

        $this->add_responsive_control(
            'job_distance',
            [
                'label' => esc_html__('Distance', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 10,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-member-job' => 'margin: 0 0 {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'job_align',
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
                'selectors' => [
                    '{{WRAPPER}} .king-addons-member-job' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'description_section',
            [
                'label' => esc_html__('Description', 'king-addons'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'description_color',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#545454',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-member-description' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'description_typography',
                'selector' => '{{WRAPPER}} .king-addons-member-description',
            ]
        );

        $this->add_responsive_control(
            'description_distance',
            [
                'label' => esc_html__('Distance', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 18,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-member-description' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'description_align',
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
                'selectors' => [
                    '{{WRAPPER}} .king-addons-member-description' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'divider_section',
            [
                'label' => esc_html__('Divider', 'king-addons'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'member_divider' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'divider_color',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#d1d1d1',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-member-divider:after' => 'border-bottom-color: {{VALUE}};',
                ],
                'condition' => [
                    'member_divider' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'divider_type',
            [
                'label' => esc_html__('Style', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'solid' => esc_html__('Solid', 'king-addons'),
                    'double' => esc_html__('Double', 'king-addons'),
                    'dotted' => esc_html__('Dotted', 'king-addons'),
                    'dashed' => esc_html__('Dashed', 'king-addons'),
                    'groove' => esc_html__('Groove', 'king-addons'),
                ],
                'default' => 'solid',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-member-divider:after' => 'border-bottom-style: {{VALUE}};',
                ],
                'condition' => [
                    'member_divider' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'divider_weight',
            [
                'label' => esc_html__('Weight', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 10,
                    ],
                ],
                'default' => [
                    'size' => 1,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-member-divider:after' => 'border-bottom-width: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'member_divider' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'divider_width',
            [
                'label' => esc_html__('Width', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['%', 'px'],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 100,
                    ],
                    '%' => [
                        'min' => 1,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 30,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-member-divider:after' => 'max-width: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'member_divider' => 'yes',
                ],
            ]
        );

        $this->add_responsive_control(
            'divider_distance',
            [
                'label' => esc_html__('Distance', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 10,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-member-divider:after' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'member_divider' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'divider_align',
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
                'prefix_class' => 'king-addons-team-member-divider-',
                'condition' => [
                    'member_divider' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'tm_section_style_social_media',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Social Media', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
                'show_label' => false,
                'condition' => [
                    'social_media' => 'yes',
                ],
            ]
        );

        $this->start_controls_tabs('tabs_social_style');

        $this->start_controls_tab(
            'tab_social_normal',
            [
                'label' => esc_html__('Normal', 'king-addons'),
            ]
        );

        $this->add_control(
            'social_color',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#5B03FF',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-member-social' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .king-addons-member-social svg' => 'fill: {{VALUE}}'
                ],
            ]
        );

        $this->add_control(
            'social_bg_color',
            [
                'label' => esc_html__('Background Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-member-social' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'social_border_color',
            [
                'label' => esc_html__('Border Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#5B03FF',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-member-social' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_social_hover',
            [
                'label' => esc_html__('Hover', 'king-addons'),
            ]
        );

        $this->add_control(
            'social_hover_color',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#000000',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-member-social:hover' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .king-addons-member-social:hover svg' => 'fill: {{VALUE}}'
                ],
            ]
        );

        $this->add_control(
            'social_hover_bg_color',
            [
                'label' => esc_html__('Background Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-member-social:hover' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'social_hover_border_color',
            [
                'label' => esc_html__('Border Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#000000',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-member-social:hover' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_control(
            'social_trans_divider',
            [
                'type' => Controls_Manager::DIVIDER,
                'style' => 'thick',
            ]
        );

        $this->add_control(
            'social_transition_duration',
            [
                'label' => esc_html__('Transition Duration (ms)', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'default' => 300,
                'min' => 0,
                'step' => 50,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-member-social' => 'transition-duration: {{VALUE}}ms',
                ],
            ]
        );

        $this->add_responsive_control(
            'social_size',
            [
                'label' => esc_html__('Font Size', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 200,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 17,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-member-social' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .king-addons-member-social svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};'
                ],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'social_box_size',
            [
                'label' => esc_html__('Box Size', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 200,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 37,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-member-social' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .king-addons-member-social i' => 'line-height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .king-addons-member-social svg' => 'line-height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'social_gutter',
            [
                'label' => esc_html__('Gutter', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 25,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 5,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-member-social' => 'margin-right: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'social_distance',
            [
                'label' => esc_html__('Distance', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 20,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-member-social-media' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'social_align',
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
                'prefix_class' => 'king-addons-team-member-social-media-',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'social_border_type',
            [
                'label' => esc_html__('Border Type', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'none' => esc_html__('None', 'king-addons'),
                    'solid' => esc_html__('Solid', 'king-addons'),
                    'double' => esc_html__('Double', 'king-addons'),
                    'dotted' => esc_html__('Dotted', 'king-addons'),
                    'dashed' => esc_html__('Dashed', 'king-addons'),
                    'groove' => esc_html__('Groove', 'king-addons'),
                ],
                'default' => 'solid',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-member-social' => 'border-style: {{VALUE}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'social_border_width',
            [
                'label' => esc_html__('Border Width', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'default' => [
                    'top' => 2,
                    'right' => 2,
                    'bottom' => 2,
                    'left' => 2,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-member-social' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'social_border_type!' => 'none',
                ],
            ]
        );

        $this->add_responsive_control(
            'social_border_radius',
            [
                'label' => esc_html__('Border Radius', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'default' => [
                    'top' => 50,
                    'right' => 50,
                    'bottom' => 50,
                    'left' => 50,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-member-social' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'testimonial_style_social_divider',
            [
                'type' => Controls_Manager::DIVIDER,
                'style' => 'thick',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'social_box_shadow',
                'selector' => '{{WRAPPER}} .king-addons-member-social',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'tm_section_style_btn',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Button', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'member_btn' => 'yes',
                ],
            ]
        );

        $this->start_controls_tabs('tabs_btn_style');

        $this->start_controls_tab(
            'tab_btn_normal',
            [
                'label' => esc_html__('Normal', 'king-addons'),
            ]
        );

        $this->add_control(
            'btn_color',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-member-btn' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'btn_bg_color',
            [
                'label' => esc_html__('Background Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#5B03FF',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-member-btn' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'btn_border_color',
            [
                'label' => esc_html__('Border Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#5B03FF',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-member-btn' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'btn_box_shadow',
                'selector' => '{{WRAPPER}} .king-addons-member-btn',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_btn_hover',
            [
                'label' => esc_html__('Hover', 'king-addons'),
            ]
        );

        $this->add_control(
            'btn_hover_color',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-member-btn:hover' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'btn_hover_bg_color',
            [
                'label' => esc_html__('Background Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#000000',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-member-btn.king-addons-button-none:hover' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .king-addons-member-btn:before' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .king-addons-member-btn:after' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'btn_hover_border_color',
            [
                'label' => esc_html__('Border Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#000000',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-member-btn:hover' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'btn_hover_box_shadow',
                'selector' => '{{WRAPPER}} .king-addons-member-btn:hover',
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_control(
            'btn_section_anim_divider',
            [
                'type' => Controls_Manager::DIVIDER,
                'style' => 'thick',
            ]
        );

        $this->add_control(
            'btn_animation',
            [
                'label' => esc_html__('Select Animation', 'king-addons'),
                'type' => 'king-addons-button-animations',
                'default' => 'king-addons-button-sweep-to-top',
            ]
        );

        $this->add_control(
            'btn_transition_duration',
            [
                'label' => esc_html__('Transition Duration (ms)', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'default' => 300,
                'min' => 0,
                'step' => 50,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-member-btn' => 'transition-duration: {{VALUE}}ms',
                    '{{WRAPPER}} .king-addons-member-btn:before' => 'transition-duration: {{VALUE}}ms',
                    '{{WRAPPER}} .king-addons-member-btn:after' => 'transition-duration: {{VALUE}}ms',
                ],
            ]
        );

        $this->add_control(
            'btn_animation_height',
            [
                'label' => esc_html__('Animation Height', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                    'px' => [
                        'min' => 0,
                        'max' => 30,
                    ],
                ],
                'size_units' => ['%', 'px'],
                'default' => [
                    'unit' => 'px',
                    'size' => 3,
                ],
                'selectors' => [
                    '{{WRAPPER}} [class*="king-addons-button-underline"]:before' => 'height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} [class*="king-addons-button-overline"]:before' => 'height: {{SIZE}}{{UNIT}};',
                ],
                'render_type' => 'template',
                'condition' => [
                    'btn_animation' => [
                        'king-addons-button-underline-from-left',
                        'king-addons-button-underline-from-center',
                        'king-addons-button-underline-from-right',
                        'king-addons-button-underline-reveal',
                        'king-addons-button-overline-reveal',
                        'king-addons-button-overline-from-left',
                        'king-addons-button-overline-from-center',
                        'king-addons-button-overline-from-right'
                    ]
                ],
            ]
        );

        if (!king_addons_freemius()->can_use_premium_code__premium_only()) {
            $this->add_control(
                'team_member_pro_notice_2',
                [
                    'type' => Controls_Manager::RAW_HTML,
                    'raw' => 'Advanced button animations are available in the <strong><a href="https://kingaddons.com/pricing/?utm_source=kng-module-team-member-settings-upgrade-pro&utm_medium=plugin&utm_campaign=kng" target="_blank">Pro version</a></strong>',
                    'content_classes' => 'king-addons-pro-notice',
                ]
            );
        }

        $this->add_control(
            'btn_typography_divider',
            [
                'type' => Controls_Manager::DIVIDER,
                'style' => 'thick',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'btn_typography',
                'selector' => '{{WRAPPER}} .king-addons-member-btn',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'btn_align',
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
                'selectors' => [
                    '{{WRAPPER}} .king-addons-member-btn-wrap' => 'text-align: {{VALUE}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'btn_padding',
            [
                'label' => esc_html__('Padding', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px',],
                'default' => [
                    'top' => 8,
                    'right' => 35,
                    'bottom' => 8,
                    'left' => 35,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-member-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'btn_border_type',
            [
                'label' => esc_html__('Border Type', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'none' => esc_html__('None', 'king-addons'),
                    'solid' => esc_html__('Solid', 'king-addons'),
                    'double' => esc_html__('Double', 'king-addons'),
                    'dotted' => esc_html__('Dotted', 'king-addons'),
                    'dashed' => esc_html__('Dashed', 'king-addons'),
                    'groove' => esc_html__('Groove', 'king-addons'),
                ],
                'default' => 'none',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-member-btn' => 'border-style: {{VALUE}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'btn_border_width',
            [
                'label' => esc_html__('Border Width', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'default' => [
                    'top' => 1,
                    'right' => 1,
                    'bottom' => 1,
                    'left' => 1,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-member-btn' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'btn_border_type!' => 'none',
                ],
            ]
        );

        $this->add_responsive_control(
            'btn_border_radius',
            [
                'label' => esc_html__('Border Radius', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'default' => [
                    'top' => 2,
                    'right' => 2,
                    'bottom' => 2,
                    'left' => 2,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-member-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->end_controls_section();

        $this->add_section_style_overlay();
    }

    protected function team_member_social_media()
    {
        $settings = $this->get_settings();

        // Define which icon slots to loop over
        $icon_indexes = [1, 2, 3, 4, 5];

        // Check if at least one icon has a value
        $has_icon = false;
        foreach ($icon_indexes as $index) {
            if (!empty($settings["social_icon_$index"]['value'])) {
                $has_icon = true;
                break;
            }
        }

        if ($has_icon) {
            // Set up attributes once
            $this->add_render_attribute('social_attribute', 'class', 'king-addons-member-social');

            if (!empty($settings['social_media_is_external'])) {
                $this->add_render_attribute('social_attribute', 'target', '_blank');
            }

            if (!empty($settings['social_media_nofollow'])) {
                $this->add_render_attribute('social_attribute', 'nofollow', '');
            }

            echo '<div class="king-addons-member-social-media">';
            // Loop and render each social icon (and link) that is not empty
            foreach ($icon_indexes as $index) {
                if (!empty($settings["social_icon_$index"]['value'])) {
                    echo '<a href="' . esc_url($settings["social_url_$index"]['url']) . '" ' .
                        $this->get_render_attribute_string('social_attribute') . '>';
                    Icons_Manager::render_icon($settings["social_icon_$index"], ['aria-hidden' => 'true']);
                    echo '</a>';
                }
            }
            echo '</div>';
        }
    }

    protected function team_member_button()
    {
        $settings = $this->get_settings();

        if ('' !== $settings['member_btn_text']) {
            $this->add_render_attribute(
                'btn_attribute',
                'class',
                'king-addons-member-btn king-addons-button-effect ' . $settings['btn_animation']
            );
            $this->add_render_attribute('btn_attribute', 'href', esc_url($settings['member_btn_url']['url']));

            if (!empty($settings['member_btn_url']['is_external'])) {
                $this->add_render_attribute('btn_attribute', 'target', '_blank');
            }

            if (!empty($settings['member_btn_url']['nofollow'])) {
                $this->add_render_attribute('btn_attribute', 'nofollow', '');
            }

            echo '<div class="king-addons-member-btn-wrap">';
            echo '<a ' . $this->get_render_attribute_string('btn_attribute') . '>';
            echo '<span>' . esc_html($settings['member_btn_text']) . '</span>';
            echo '</a>';
            echo '</div>';
        }
    }

    protected function team_member_content()
    {
        $settings = $this->get_settings();

        /** @noinspection DuplicatedCode */
        if (
            ('' !== $settings['member_name'] && 'below' === $settings['member_name_location']) ||
            ('' !== $settings['member_job'] && 'below' === $settings['member_job_location']) ||
            ('' !== $settings['member_description'] && 'below' === $settings['member_description_location']) ||
            ('yes' === $settings['social_media'] && 'below' === $settings['member_social_media_location']) ||
            ('yes' === $settings['member_btn'] && 'below' === $settings['member_btn_location'])
        ) : ?>

            <div class="king-addons-member-content">
                <?php
                // Member name
                if ('' !== $settings['member_name'] && 'below' === $settings['member_name_location']) {
                    $tags_whitelist = ['h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'div', 'span', 'p'];
                    $member_name_tag = Core::validateHTMLTags($settings['member_name_tag'], 'h3', $tags_whitelist);

                    echo '<' . esc_attr($member_name_tag) . ' class="king-addons-member-name">';
                    echo wp_kses_post($settings['member_name']);
                    echo '</' . esc_attr($member_name_tag) . '>';
                }
                ?>

                <?php if (
                    'yes' === $settings['member_divider'] &&
                    'below' === $settings['member_divider_location'] &&
                    'before_job' === $settings['member_divider_position']
                ) : ?>
                    <div class="king-addons-member-divider"></div>
                <?php endif; ?>

                <?php if ('' !== $settings['member_job'] && 'below' === $settings['member_job_location']) : ?>
                    <div class="king-addons-member-job"><?php echo esc_html($settings['member_job']); ?></div>
                <?php endif; ?>

                <?php if (
                    'yes' === $settings['member_divider'] &&
                    'below' === $settings['member_divider_location'] &&
                    'after_job' === $settings['member_divider_position']
                ) : ?>
                    <div class="king-addons-member-divider"></div>
                <?php endif; ?>

                <?php if ('' !== $settings['member_description'] && 'below' === $settings['member_description_location']) : ?>
                    <div class="king-addons-member-description"><?php echo wp_kses_post($settings['member_description']); ?></div>
                <?php endif; ?>

                <?php
                // Social Media
                if ('yes' === $settings['social_media'] && 'below' === $settings['member_social_media_location']) {
                    $this->team_member_social_media();
                }

                // Button
                if ('yes' === $settings['member_btn'] && 'below' === $settings['member_btn_location']) {
                    $this->team_member_button();
                }
                ?>
            </div>
        <?php
        endif;
    }

    protected function team_member_overlay()
    {
        $settings = $this->get_settings();

        /** @noinspection DuplicatedCode */
        if (
            ('' !== $settings['member_name'] && 'over' === $settings['member_name_location']) ||
            ('' !== $settings['member_job'] && 'over' === $settings['member_job_location']) ||
            ('' !== $settings['member_description'] && 'over' === $settings['member_description_location']) ||
            ('yes' === $settings['social_media'] && 'over' === $settings['member_social_media_location']) ||
            ('yes' === $settings['member_btn'] && 'over' === $settings['member_btn_location'])
        ) {
            // Add base classes
            $this->add_render_attribute('overlay_container', 'class', 'king-addons-member-overlay-wrap king-addons-cv-container');
            $this->add_render_attribute('overlay_outer', 'class', 'king-addons-cv-outer');

            // Overlay animation classes
            if ('none' !== $settings['overlay_animation']) {
                $this->add_render_attribute('overlay_container', 'class', 'king-addons-team-member-animation king-addons-animation-wrap');
                $this->add_render_attribute(
                    'overlay_outer',
                    'class',
                    'king-addons-anim-transparency king-addons-anim-size-' . $settings['overlay_anim_size'] . ' king-addons-overlay-' . $settings['overlay_animation']
                );
            }
            ?>
            <div <?php echo $this->get_render_attribute_string('overlay_container'); ?>>
                <div <?php echo $this->get_render_attribute_string('overlay_outer'); ?>>
                    <div class="king-addons-cv-inner">
                        <div class="king-addons-member-overlay"></div>
                        <div class="king-addons-member-overlay-content">
                            <?php
                            // Member Name
                            if ('' !== $settings['member_name'] && 'over' === $settings['member_name_location']) {
                                echo '<' . esc_attr($settings['member_name_tag']) . ' class="king-addons-member-name">' .
                                    esc_html($settings['member_name']) .
                                    '</' . esc_attr($settings['member_name_tag']) . '>';
                            }

                            // Divider before Job
                            if ('yes' === $settings['member_divider'] && 'over' === $settings['member_divider_location'] && 'before_job' === $settings['member_divider_position']) {
                                echo '<div class="king-addons-member-divider"></div>';
                            }

                            // Member Job
                            if ('' !== $settings['member_job'] && 'over' === $settings['member_job_location']) {
                                echo '<div class="king-addons-member-job">' . esc_html($settings['member_job']) . '</div>';
                            }

                            // Divider after Job
                            if ('yes' === $settings['member_divider'] && 'over' === $settings['member_divider_location'] && 'after_job' === $settings['member_divider_position']) {
                                echo '<div class="king-addons-member-divider"></div>';
                            }

                            // Member Description
                            if ('' !== $settings['member_description'] && 'over' === $settings['member_description_location']) {
                                echo '<div class="king-addons-member-description">' . esc_html($settings['member_description']) . '</div>';
                            }

                            // Social Media
                            if ('yes' === $settings['social_media'] && 'over' === $settings['member_social_media_location']) {
                                $this->team_member_social_media();
                            }

                            // Button
                            if ('yes' === $settings['member_btn'] && 'over' === $settings['member_btn_location']) {
                                $this->team_member_button();
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        }
    }

    protected function render()
    {
        $settings = $this->get_settings(); ?>

        <div class="king-addons-team-member">
            <?php if (!empty($settings['member_image']['url'])) : ?>
                <?php
                $image_src = Group_Control_Image_Size::get_attachment_image_src(
                    $settings['member_image']['id'],
                    'image_size',
                    $settings
                );

                if (!$image_src) {
                    $image_src = $settings['member_image']['url'];
                }
                ?>
                <div class="king-addons-member-media">
                    <div class="king-addons-member-image">
                        <img src="<?php echo esc_url($image_src); ?>"
                             alt="<?php echo esc_attr($settings['member_name']); ?>">
                    </div>
                    <?php $this->team_member_overlay(); ?>
                </div>
            <?php endif; ?>

            <?php $this->team_member_content(); ?>
        </div>
        <?php
    }
}