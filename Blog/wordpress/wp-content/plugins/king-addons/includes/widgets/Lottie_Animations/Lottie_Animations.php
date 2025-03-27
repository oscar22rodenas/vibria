<?php /** @noinspection PhpUnused */

namespace King_Addons;

use Elementor\Controls_Manager;
use Elementor\Widget_Base;
use Elementor\Group_Control_Css_Filter;

if (!defined('ABSPATH')) {
    exit;
}

class Lottie_Animations extends Widget_Base
{

    public function get_name()
    {
        return 'king-addons-lottie-animations';
    }

    public function get_title()
    {
        return esc_html__('Lottie Animations', 'king-addons');
    }

    public function get_icon()
    {
        return 'king-addons-icon king-addons-lottie-animations';
    }

    public function get_categories()
    {
        return ['king-addons'];
    }

    public function get_keywords()
    {
        return ['king addons', 'king', 'addons', 'kingaddons', 'king-addons', 'lottie', 'animation', 'animations',
            'svg', 'anim', 'lotie', 'loty', 'lote', 'lotte', 'lot'];
    }

    public function get_script_depends()
    {
        return [KING_ADDONS_ASSETS_UNIQUE_KEY . '-lottie-animations-script', KING_ADDONS_ASSETS_UNIQUE_KEY . '-lottie-lottie'];
    }

    public function get_style_depends(): array
    {
        return [KING_ADDONS_ASSETS_UNIQUE_KEY . '-lottie-animations-style'];
    }

    public function get_custom_help_url()
    {
        return 'https://kingaddons.com/';
    }

    protected function register_controls()
    {

        $this->start_controls_section(
            'section_settings',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Settings', 'king-addons'),
            ]
        );

        $this->add_control(
            'source',
            [
                'label' => esc_html__('File Source', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'url' => esc_html__('External URL', 'king-addons'),
                    'file' => esc_html__('Media File', 'king-addons'),
                ],
                'default' => 'file',
            ]
        );

        $this->add_control(
            'json_url',
            [
                'label' => esc_html__('Animation JSON URL', 'king-addons'),
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => '',
                'description' => 'Get JSON code URL from <a href="https://lottiefiles.com/" target="_blank">here</a>',
                'label_block' => true,
                'condition' => [
                    'source' => 'url',
                ],
            ]
        );

        $this->add_control(
            'json_file',
            array(
                'label' => esc_html__('Upload JSON File', 'king-addons'),
                'type' => Controls_Manager::MEDIA,
                'media_type' => 'application/json',
                'frontend_available' => true,
                'condition' => [
                    'source' => 'file',
                ]
            )
        );

        $this->add_control(
            'autoplay',
            [
                'label' => esc_html__('Autoplay', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'return_value' => 'yes',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'loop',
            [
                'label' => esc_html__('Loop', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'return_value' => 'yes',
            ]
        );

        $this->add_control(
            'reverse',
            [
                'label' => esc_html__('Reverse', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'true',
                'condition' => [
                    'trigger!' => 'scroll'
                ]
            ]
        );

        $this->add_control(
            'speed',
            array(
                'label' => esc_html__('Animation Speed', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'default' => 1,
                'min' => 0.1,
                'max' => 3,
                'step' => 0.1,
            )
        );

        $this->add_control(
            'trigger',
            [
                'label' => esc_html__('Trigger', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'none',
                'options' => array(
                    'none' => esc_html__('None', 'king-addons'),
                    'viewport' => esc_html__('Viewport', 'king-addons'),
                    'hover' => esc_html__('Hover', 'king-addons'),
                    'scroll' => esc_html__('Scroll', 'king-addons'),
                ),
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'animate_view',
            array(
                'label' => esc_html__('Viewport', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'default' => array(
                    'sizes' => array(
                        'start' => 0,
                        'end' => 100,
                    ),
                    'unit' => '%',
                ),
                'labels' => array(
                    esc_html__('Bottom', 'king-addons'),
                    esc_html__('Top', 'king-addons'),
                ),
                'scales' => 1,
                'handles' => 'range',
                'condition' => array(
                    'trigger' => array('scroll', 'viewport'),
                ),
            )
        );

        $this->add_responsive_control(
            'animation_size',
            array(
                'label' => esc_html__('Size', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => array('px', '%'),
                'default' => array(
                    'unit' => '%',
                    'size' => 50,
                ),
                'range' => array(
                    'px' => array(
                        'min' => 1,
                        'max' => 800,
                    ),
                    'em' => array(
                        'min' => 1,
                        'max' => 30,
                    ),
                ),
                'render_type' => 'template',
                'separator' => 'before',
                'selectors' => array(
                    '{{WRAPPER}} .king-addons-lottie-animations svg' => 'width: 100% !important; height: 100% !important;',
                    '{{WRAPPER}} .king-addons-lottie-animations' => 'width: {{SIZE}}{{UNIT}} !important; height: {{SIZE}}{{UNIT}} !important;',
                ),
            )
        );

        $this->add_responsive_control(
            'rotate',
            array(
                'label' => esc_html__('Rotate (degrees)', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'description' => esc_html__('Set rotation value in degrees', 'king-addons'),
                'range' => array(
                    'px' => array(
                        'min' => -180,
                        'max' => 180,
                    ),
                ),
                'default' => array(
                    'size' => 0,
                ),
                'selectors' => array(
                    '{{WRAPPER}} .king-addons-lottie-animations' => 'transform: rotate({{SIZE}}deg)',
                ),
            )
        );

        $this->add_responsive_control(
            'animation_align',
            array(
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
                'default' => 'center',
                'toggle' => false,
                'separator' => 'before',
                'selectors' => array(
                    '{{WRAPPER}} .king-addons-lottie-animations-wrapper' => 'display: flex; justify-content: {{VALUE}}; align-items: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'lottie_renderer',
            [
                'label' => esc_html__('Render As', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => array(
                    'svg' => esc_html__('SVG', 'king-addons'),
                    'canvas' => esc_html__('Canvas', 'king-addons'),
                ),
                'default' => 'svg',
                'prefix_class' => 'king-addons-lottie-',
                'render_type' => 'template',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'render_notice',
            [
                'raw' => esc_html__('Set render type to canvas if you\'re having performance issues on the page.', 'king-addons'),
                'type' => Controls_Manager::RAW_HTML,
                'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
            ]
        );

        $this->add_control(
            'link_switcher',
            [
                'label' => esc_html__('Wrapper Link', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
            ]
        );

        $this->add_control(
            'link_selection',
            [
                'label' => esc_html__('Link Type', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => array(
                    'url' => esc_html__('URL', 'king-addons'),
                    'link' => esc_html__('Existing Page', 'king-addons'),
                ),
                'default' => 'url',
                'label_block' => true,
                'condition' => array(
                    'link_switcher' => 'yes',
                ),
            ]
        );

        $this->add_control(
            'link',
            array(
                'label' => esc_html__('Link', 'king-addons'),
                'type' => Controls_Manager::URL,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => array(
                    'url' => '#',
                ),
                'placeholder' => esc_html__('https://example.com', 'king-addons'),
                'label_block' => true,
                'condition' => array(
                    'link_switcher' => 'yes',
                    'link_selection' => 'url',
                ),
            )
        );

        $this->add_control(
            'existing_link',
            array(
                'label' => esc_html__('Existing Page', 'king-addons'),
                'type' => 'king-addons-ajax-select2',
                'options' => 'ajaxselect2/getPostsByPostType',
                'query_slug' => 'page',
                'multiple' => false,
                'label_block' => true,
                'condition' => array(
                    'link_switcher' => 'yes',
                    'link_selection' => 'link',
                ),
            )
        );


        $this->end_controls_section();

        $this->start_controls_section(
            'lottie_styles',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Animation', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->start_controls_tabs('tabs_lottie');

        $this->start_controls_tab(
            'tab_lottie_normal',
            [
                'label' => esc_html__('Normal', 'king-addons'),
            ]
        );

        $this->add_control(
            'opacity',
            [
                'label' => esc_html__('Opacity', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'range' => array(
                    'px' => array(
                        'max' => 1,
                        'min' => 0.10,
                        'step' => 0.01,
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} .king-addons-lottie-animations' => 'opacity: {{SIZE}}',
                ),
            ]
        );

        $this->add_control(
            'hover_transition',
            [
                'label' => esc_html__('Transition Duration', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'default' => 0.3,
                'min' => 0,
                'max' => 5,
                'step' => 0.1,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-lottie-animations' => 'transition-duration: {{VALUE}}s;'
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Css_Filter::get_type(),
            array(
                'name' => 'css_filters',
                'selector' => '{{WRAPPER}} .king-addons-lottie-animations',
            )
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_lottie_hover',
            [
                'label' => esc_html__('Hover', 'king-addons'),
            ]
        );

        $this->add_control(
            'hover_opacity',
            array(
                'label' => esc_html__('Opacity', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'range' => array(
                    'px' => array(
                        'max' => 1,
                        'min' => 0.10,
                        'step' => 0.01,
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} .king-addons-lottie-animations:hover' => 'opacity: {{SIZE}}',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Css_Filter::get_type(),
            [
                'name' => 'hover_css_filters',
                'selector' => '{{WRAPPER}} .king-addons-lottie-animations:hover',
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

    }

    public function lottie_attributes($settings)
    {
        $attributes = [
            'autoplay' => $settings['autoplay'],
            'loop' => $settings['loop'],
            'lottie_renderer' => $settings['lottie_renderer'],
            'reverse' => $settings['reverse'],
            'scroll_start' => $settings['animate_view']['sizes']['start'] ?? '0',
            'scroll_end' => $settings['animate_view']['sizes']['end'] ?? '100',
            'speed' => $settings['speed'],
            'trigger' => $settings['trigger'],
        ];

        return json_encode($attributes);
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();

        // Determine Lottie JSON
        $lottie_json = ($settings['source'] === 'url')
            ? esc_url($settings['json_url'])
            : ($settings['json_file']['url'] ?? '');
        $lottie_json = $lottie_json ?: KING_ADDONS_URL . 'includes/assets/libraries/lottie/default.json';

        // Determine link
        $lottie_link = ($settings['link_selection'] === 'url')
            ? ($settings['link']['url'] ?? '')
            : get_permalink($settings['existing_link']);

        // Prepare main Lottie container
        $lottie_settings = esc_attr($this->lottie_attributes($settings));
        $lottie_tag = sprintf(
            '<div class="king-addons-lottie-animations" data-settings="%s" data-json-url="%s"></div>',
            $lottie_settings,
            esc_url($lottie_json)
        );

        // Optionally wrap in a link
        if ($settings['link_switcher'] === 'yes') {
            /** @noinspection HtmlUnknownTarget */
            $lottie_tag = sprintf('<a href="%s">%s</a>', esc_url($lottie_link), $lottie_tag);
        }

        // Output
        echo sprintf(
            '<div class="king-addons-lottie-animations-wrapper">%s</div>',
            $lottie_tag
        );
    }
}
