<?php /** @noinspection PhpUnused, DuplicatedCode,SpellCheckingInspection */

namespace King_Addons;

use Elementor\Controls_Manager;
use Elementor\Element_Base;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class Particles_Background
{
    private static ?Particles_Background $_instance = null;

    public static function instance(): Particles_Background
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function __construct()
    {
        add_action('elementor/preview/enqueue_scripts', [__CLASS__, 'enqueueScripts'], 1);
        add_action('elementor/element/section/section_advanced/after_section_end', [__CLASS__, 'addControls'], 1);
        add_action('elementor/frontend/section/after_render', [__CLASS__, 'renderAnimation'], 1);
        add_action('elementor/element/container/section_layout/after_section_end', array($this, 'addControls'), 10);
        add_action('elementor/frontend/container/after_render', array($this, 'renderAnimation'), 10);
    }

    public static function enqueueScripts(): void
    {
        if (!wp_script_is(KING_ADDONS_ASSETS_UNIQUE_KEY . '-' . 'particles' . '-' . 'particles')) {
            wp_enqueue_script(KING_ADDONS_ASSETS_UNIQUE_KEY . '-' . 'particles' . '-' . 'particles', '', array('jquery'), KING_ADDONS_VERSION);
        }

        if (!wp_script_is(KING_ADDONS_ASSETS_UNIQUE_KEY . '-' . 'particles-background' . '-' . 'preview-handler')) {
            wp_enqueue_script(KING_ADDONS_ASSETS_UNIQUE_KEY . '-' . 'particles-background' . '-' . 'preview-handler', '', array('jquery'), KING_ADDONS_VERSION);
        }
    }

    public static function addControls(Element_Base $element): void
    {
        $element->start_controls_section(
            'kng_particles_bg_section',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Particles Background', 'king-addons'),
                'tab' => Controls_Manager::TAB_ADVANCED
            ]
        );

        $element->add_control(
            'kng_particles_bg_switch',
            [
                'label' => esc_html__('Enable Particles', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'prefix_class' => 'kng-particles-bg-',
                'frontend_available' => true
            ]
        );

        $element->add_control(
            'kng_particles_bg_apply_changes',
            [
                'type' => Controls_Manager::RAW_HTML,
                'raw' => '<div class="elementor-update-preview king-addons-editor-preview-update"><span>' . esc_html__('After a few edits, the browser can slow down the animation to save resources. This is normal. To clean it up, just click on the Refresh button. It will refresh the Preview, so the animation will run smoothly again.', 'king-addons') . '</span><button class="elementor-button elementor-button-success" onclick="elementor.reloadPreview();">' . esc_html__('Refresh', 'king-addons') . '</button>',
                'separator' => 'before',
                'condition' => [
                    'kng_particles_bg_switch!' => ''
                ],
            ]
        );

        $element->add_control('kng_particles_bg_type',
            [
                'label' => esc_html__('Type', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'default' => esc_html__('Default', 'king-addons'),
                    'nasa' => esc_html__('NASA', 'king-addons'),
                    'bubble' => esc_html__('Bubble', 'king-addons'),
                    'snow' => esc_html__('Snow', 'king-addons'),
                    'nyan_cat' => esc_html__('Nyan Cat', 'king-addons'),
                    'custom_code' => esc_html__('Custom Code (JSON)', 'king-addons')
                ],
                'default' => 'default',
                'frontend_available' => true,
                'label_block' => 'true',
                'separator' => 'before',
                'condition' => [
                    'kng_particles_bg_switch!' => ''
                ],
            ]
        );

        $element->add_control(
            'kng_particles_bg_color',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'frontend_available' => true,
                'default' => '#0027FF',
                'condition' => [
                    'kng_particles_bg_switch!' => '',
                    'kng_particles_bg_type' => ['default', 'nasa', 'bubble', 'snow', 'nyan_cat']
                ],
            ]
        );

        $element->add_control(
            'kng_particles_bg_anim_speed_default',
            [
                'label' => esc_html__('Animation Speed', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'frontend_available' => true,
                'min' => 0,
                'step' => 0.1,
                'default' => 6,
                'condition' => [
                    'kng_particles_bg_switch!' => '',
                    'kng_particles_bg_type' => 'default'
                ]
            ]
        );

        $element->add_control(
            'kng_particles_bg_anim_speed_nasa',
            [
                'label' => esc_html__('Animation Speed', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'frontend_available' => true,
                'min' => 0,
                'step' => 0.1,
                'default' => 1,
                'condition' => [
                    'kng_particles_bg_switch!' => '',
                    'kng_particles_bg_type' => 'nasa'
                ]
            ]
        );

        $element->add_control(
            'kng_particles_bg_anim_speed_bubble',
            [
                'label' => esc_html__('Animation Speed', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'frontend_available' => true,
                'min' => 0,
                'step' => 0.1,
                'default' => 8,
                'condition' => [
                    'kng_particles_bg_switch!' => '',
                    'kng_particles_bg_type' => 'bubble'
                ]
            ]
        );

        $element->add_control(
            'kng_particles_bg_anim_speed_snow',
            [
                'label' => esc_html__('Animation Speed', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'frontend_available' => true,
                'min' => 0,
                'step' => 0.1,
                'default' => 6,
                'condition' => [
                    'kng_particles_bg_switch!' => '',
                    'kng_particles_bg_type' => 'snow'
                ]
            ]
        );

        $element->add_control(
            'kng_particles_bg_anim_speed_nyan_cat',
            [
                'label' => esc_html__('Animation Speed', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'frontend_available' => true,
                'min' => 0,
                'step' => 0.1,
                'default' => 14,
                'condition' => [
                    'kng_particles_bg_switch!' => '',
                    'kng_particles_bg_type' => 'nyan_cat'
                ]
            ]
        );

        $element->add_control(
            'kng_particles_bg_number_default',
            [
                'label' => esc_html__('Number of particles', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'frontend_available' => true,
                'min' => 0,
                'step' => 0.1,
                'default' => 160,
                'condition' => [
                    'kng_particles_bg_switch!' => '',
                    'kng_particles_bg_type' => 'default'
                ]
            ]
        );

        $element->add_control(
            'kng_particles_bg_number_nasa',
            [
                'label' => esc_html__('Number of particles', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'frontend_available' => true,
                'min' => 0,
                'step' => 0.1,
                'default' => 250,
                'condition' => [
                    'kng_particles_bg_switch!' => '',
                    'kng_particles_bg_type' => 'nasa'
                ]
            ]
        );

        $element->add_control(
            'kng_particles_bg_number_bubble',
            [
                'label' => esc_html__('Number of particles', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'frontend_available' => true,
                'min' => 0,
                'step' => 0.1,
                'default' => 15,
                'condition' => [
                    'kng_particles_bg_switch!' => '',
                    'kng_particles_bg_type' => 'bubble'
                ]
            ]
        );

        $element->add_control(
            'kng_particles_bg_number_snow',
            [
                'label' => esc_html__('Number of particles', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'frontend_available' => true,
                'min' => 0,
                'step' => 0.1,
                'default' => 450,
                'condition' => [
                    'kng_particles_bg_switch!' => '',
                    'kng_particles_bg_type' => 'snow'
                ]
            ]
        );

        $element->add_control(
            'kng_particles_bg_number_nyan_cat',
            [
                'label' => esc_html__('Number of particles', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'frontend_available' => true,
                'min' => 0,
                'step' => 0.1,
                'default' => 150,
                'condition' => [
                    'kng_particles_bg_switch!' => '',
                    'kng_particles_bg_type' => 'nyan_cat'
                ]
            ]
        );

        $element->add_control(
            'kng_particles_bg_size_default',
            [
                'label' => esc_html__('Size of particle', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'frontend_available' => true,
                'min' => 0,
                'step' => 0.1,
                'default' => 3,
                'condition' => [
                    'kng_particles_bg_switch!' => '',
                    'kng_particles_bg_type' => 'default'
                ]
            ]
        );

        $element->add_control(
            'kng_particles_bg_size_nasa',
            [
                'label' => esc_html__('Size of particle', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'frontend_available' => true,
                'min' => 0,
                'step' => 0.1,
                'default' => 3,
                'condition' => [
                    'kng_particles_bg_switch!' => '',
                    'kng_particles_bg_type' => 'nasa'
                ]
            ]
        );

        $element->add_control(
            'kng_particles_bg_size_bubble',
            [
                'label' => esc_html__('Size of particle', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'frontend_available' => true,
                'min' => 0,
                'step' => 0.1,
                'default' => 50,
                'condition' => [
                    'kng_particles_bg_switch!' => '',
                    'kng_particles_bg_type' => 'bubble'
                ]
            ]
        );

        $element->add_control(
            'kng_particles_bg_size_snow',
            [
                'label' => esc_html__('Size of particle', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'frontend_available' => true,
                'min' => 0,
                'step' => 0.1,
                'default' => 5,
                'condition' => [
                    'kng_particles_bg_switch!' => '',
                    'kng_particles_bg_type' => 'snow'
                ]
            ]
        );

        $element->add_control(
            'kng_particles_bg_size_nyan_cat',
            [
                'label' => esc_html__('Size of particle', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'frontend_available' => true,
                'min' => 0,
                'step' => 0.1,
                'default' => 4,
                'condition' => [
                    'kng_particles_bg_switch!' => '',
                    'kng_particles_bg_type' => 'nyan_cat'
                ]
            ]
        );

        $element->add_control(
            'kng_particles_bg_z_index',
            [
                'label' => esc_html__('Z-Index (optional)', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'frontend_available' => true,
                'step' => 1,
                'default' => 0,
                'condition' => [
                    'kng_particles_bg_switch!' => ''
                ]
            ]
        );

        $element->add_control('kng_particles_bg_shape_type',
            [
                'label' => esc_html__('Shape Type', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'circle' => esc_html__('Circle', 'king-addons'),
                    'edge' => esc_html__('Edge', 'king-addons'),
                    'triangle' => esc_html__('Triangle', 'king-addons'),
                    'polygon' => esc_html__('Polygon', 'king-addons'),
                    'star' => esc_html__('Star', 'king-addons')
                ],
                'default' => 'circle',
                'frontend_available' => true,
                'label_block' => 'true',
                'condition' => [
                    'kng_particles_bg_switch!' => '',
                    'kng_particles_bg_type' => ['default', 'nasa', 'snow']
                ],
            ]
        );

        $element->add_control('kng_particles_bg_shape_type_bubble',
            [
                'label' => esc_html__('Shape Type', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'circle' => esc_html__('Circle', 'king-addons'),
                    'edge' => esc_html__('Edge', 'king-addons'),
                    'triangle' => esc_html__('Triangle', 'king-addons'),
                    'polygon' => esc_html__('Polygon', 'king-addons')
                ],
                'default' => 'circle',
                'frontend_available' => true,
                'label_block' => 'true',
                'condition' => [
                    'kng_particles_bg_switch!' => '',
                    'kng_particles_bg_type' => 'bubble'
                ],
            ]
        );

        $element->add_control('kng_particles_bg_shape_type_nyan_cat',
            [
                'label' => esc_html__('Shape Type', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'circle' => esc_html__('Circle', 'king-addons'),
                    'edge' => esc_html__('Edge', 'king-addons'),
                    'triangle' => esc_html__('Triangle', 'king-addons'),
                    'polygon' => esc_html__('Polygon', 'king-addons'),
                    'star' => esc_html__('Star', 'king-addons')
                ],
                'default' => 'star',
                'frontend_available' => true,
                'label_block' => 'true',
                'condition' => [
                    'kng_particles_bg_switch!' => '',
                    'kng_particles_bg_type' => 'nyan_cat'
                ],
            ]
        );

        $element->add_control('kng_particles_bg_move_direction',
            [
                'label' => esc_html__('Move Direction', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'none' => esc_html__('None', 'king-addons'),
                    'top' => esc_html__('Top', 'king-addons'),
                    'top-right' => esc_html__('Top-right', 'king-addons'),
                    'bottom-right' => esc_html__('Bottom-right', 'king-addons'),
                    'bottom' => esc_html__('Bottom', 'king-addons'),
                    'bottom-left' => esc_html__('Bottom-left', 'king-addons'),
                    'left' => esc_html__('Left', 'king-addons'),
                    'top-left' => esc_html__('Top-left', 'king-addons')
                ],
                'default' => 'none',
                'frontend_available' => true,
                'label_block' => 'true',
                'condition' => [
                    'kng_particles_bg_switch!' => '',
                    'kng_particles_bg_type' => ['default', 'nasa', 'bubble']
                ],
            ]
        );

        $element->add_control('kng_particles_bg_move_direction_snow',
            [
                'label' => esc_html__('Move Direction', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'none' => esc_html__('None', 'king-addons'),
                    'top' => esc_html__('Top', 'king-addons'),
                    'top-right' => esc_html__('Top-right', 'king-addons'),
                    'bottom-right' => esc_html__('Bottom-right', 'king-addons'),
                    'bottom' => esc_html__('Bottom', 'king-addons'),
                    'bottom-left' => esc_html__('Bottom-left', 'king-addons'),
                    'left' => esc_html__('Left', 'king-addons'),
                    'top-left' => esc_html__('Top-left', 'king-addons'),
                ],
                'default' => 'bottom',
                'frontend_available' => true,
                'label_block' => 'true',
                'condition' => [
                    'kng_particles_bg_switch!' => '',
                    'kng_particles_bg_type' => 'snow'
                ],
            ]
        );

        $element->add_control('kng_particles_bg_move_direction_nyan_cat',
            [
                'label' => esc_html__('Move Direction', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'none' => esc_html__('None', 'king-addons'),
                    'top' => esc_html__('Top', 'king-addons'),
                    'top-right' => esc_html__('Top-right', 'king-addons'),
                    'bottom-right' => esc_html__('Bottom-right', 'king-addons'),
                    'bottom' => esc_html__('Bottom', 'king-addons'),
                    'bottom-left' => esc_html__('Bottom-left', 'king-addons'),
                    'left' => esc_html__('Left', 'king-addons'),
                    'top-left' => esc_html__('Top-left', 'king-addons'),
                ],
                'default' => 'left',
                'frontend_available' => true,
                'label_block' => 'true',
                'condition' => [
                    'kng_particles_bg_switch!' => '',
                    'kng_particles_bg_type' => 'nyan_cat'
                ],
            ]
        );

        $element->add_control(
            'kng_particles_bg_custom_code_guide',
            [
                'type' => Controls_Manager::RAW_HTML,
                'raw' => esc_html__('To create a custom animation, go to ', 'king-addons') . '<a href="https://vincentgarreau.com/particles.js/" target="_blank">' . esc_html__('HERE', 'king-addons') . '</a>, ' . esc_html__('then past the generated JSON code in the field (open the downloaded config file in any text editor).', 'king-addons'),
                'separator' => 'before',
                'condition' => [
                    'kng_particles_bg_switch!' => '',
                    'kng_particles_bg_type' => 'custom_code'
                ],
            ]
        );

        $element->add_control('kng_particles_bg_custom_code',
            [
                'label' => esc_html__('Custom Code (JSON)', 'king-addons'),
                'type' => Controls_Manager::CODE,
                'language' => 'json',
                'default' => 'CODE_FROM_JSON_FILE',
                'frontend_available' => true,
                'label_block' => 'true',
                'condition' => [
                    'kng_particles_bg_switch!' => '',
                    'kng_particles_bg_type' => 'custom_code'
                ],
            ]
        );

        $element->end_controls_section();
    }

    public static function renderAnimation(Element_Base $element): void
    {
        if (!empty($element->get_settings_for_display('kng_particles_bg_switch'))) {

            if (!wp_script_is(KING_ADDONS_ASSETS_UNIQUE_KEY . '-' . 'particles' . '-' . 'particles')) {
                wp_enqueue_script(KING_ADDONS_ASSETS_UNIQUE_KEY . '-' . 'particles' . '-' . 'particles', '', array('jquery'), KING_ADDONS_VERSION);
            }

            if (('container' === $element->get_type() || 'section' === $element->get_type()) && 'yes' === $element->get_settings('kng_particles_bg_switch')
            ) {

                $settings = $element->get_settings();
                $element_ID = $element->get_id();
                $type = esc_js($settings['kng_particles_bg_type']);
                $color = esc_js($settings['kng_particles_bg_color']);
                $config = '';

                switch ($type) {
                    case 'default':
                        $speed = esc_js($settings['kng_particles_bg_anim_speed_default']);
                        $shape_type = esc_js($settings['kng_particles_bg_shape_type']);
                        $move_direction = esc_js($settings['kng_particles_bg_move_direction']);
                        $number = esc_js($settings['kng_particles_bg_number_default']);
                        $size = esc_js($settings['kng_particles_bg_size_default']);
                        $config = self::getParticlesConfig($color, $speed, $shape_type, $move_direction, $number, $size)[$type];
                        break;
                    case 'nasa':
                        $speed = esc_js($settings['kng_particles_bg_anim_speed_nasa']);
                        $shape_type = esc_js($settings['kng_particles_bg_shape_type']);
                        $move_direction = esc_js($settings['kng_particles_bg_move_direction']);
                        $number = esc_js($settings['kng_particles_bg_number_nasa']);
                        $size = esc_js($settings['kng_particles_bg_size_nasa']);
                        $config = self::getParticlesConfig($color, $speed, $shape_type, $move_direction, $number, $size)[$type];
                        break;
                    case 'bubble':
                        $speed = esc_js($settings['kng_particles_bg_anim_speed_bubble']);
                        $shape_type = esc_js($settings['kng_particles_bg_shape_type_bubble']);
                        $move_direction = esc_js($settings['kng_particles_bg_move_direction']);
                        $number = esc_js($settings['kng_particles_bg_number_bubble']);
                        $size = esc_js($settings['kng_particles_bg_size_bubble']);
                        $config = self::getParticlesConfig($color, $speed, $shape_type, $move_direction, $number, $size)[$type];
                        break;
                    case 'snow':
                        $speed = esc_js($settings['kng_particles_bg_anim_speed_snow']);
                        $shape_type = esc_js($settings['kng_particles_bg_shape_type']);
                        $move_direction = esc_js($settings['kng_particles_bg_move_direction_snow']);
                        $number = esc_js($settings['kng_particles_bg_number_snow']);
                        $size = esc_js($settings['kng_particles_bg_size_snow']);
                        $config = self::getParticlesConfig($color, $speed, $shape_type, $move_direction, $number, $size)[$type];
                        break;
                    case 'nyan_cat':
                        $speed = esc_js($settings['kng_particles_bg_anim_speed_nyan_cat']);
                        $shape_type = esc_js($settings['kng_particles_bg_shape_type_nyan_cat']);
                        $move_direction = esc_js($settings['kng_particles_bg_move_direction_nyan_cat']);
                        $number = esc_js($settings['kng_particles_bg_number_nyan_cat']);
                        $size = esc_js($settings['kng_particles_bg_size_nyan_cat']);
                        $config = self::getParticlesConfig($color, $speed, $shape_type, $move_direction, $number, $size)[$type];
                        break;
                    case 'custom_code':
                        $config = $settings['kng_particles_bg_custom_code'];
                        break;
                }

                $inline_js_element = "document.querySelector('.elementor-element-" . esc_js($element_ID) . "').insertAdjacentHTML('afterbegin', '" .
                    '<div id="king-addons-particles-container-' . esc_js($element_ID) . '" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; z-index: ' . esc_js($settings['kng_particles_bg_z_index']) . ';"></div>' . "');";

                $inline_js_config = '';
                if (self::is_json($config)) {
                    $inline_js_config = 'document.addEventListener("DOMContentLoaded", function() {' . "particlesJS('king-addons-particles-container-" . esc_js($element_ID) . "'," . $config . ");" . '});';
                }

                $inline_js = $inline_js_element . $inline_js_config;
                wp_print_inline_script_tag($inline_js);
            }
        }
    }

    public static function is_json($string): bool
    {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }

    public static function getParticlesConfig($color, $speed, $shape_type, $move_direction, $number, $size): array
    {
        return [
            'default' => '{
        "particles": {
          "number": {
            "value": ' . $number . ',
            "density": {
              "enable": true,
              "value_area": 800
            }
          },
          "color": {
            "value": "' . $color . '"
          },
          "shape": {
            "type": "' . $shape_type . '",
            "stroke": {
              "width": 0,
              "color": "#000000"
            },
            "polygon": {
              "nb_sides": 5
            }
          },
          "opacity": {
            "value": 0.5,
            "random": false,
            "anim": {
              "enable": false,
              "speed": 1,
              "opacity_min": 0.1,
              "sync": false
            }
          },
          "size": {
            "value": ' . $size . ',
            "random": true,
            "anim": {
              "enable": false,
              "speed": 40,
              "size_min": 0.1,
              "sync": false
            }
          },
          "line_linked": {
            "enable": true,
            "distance": 150,
            "color": "' . $color . '",
            "opacity": 0.4,
            "width": 1
          },
          "move": {
            "enable": true,
            "speed": ' . $speed . ',
            "direction": "' . $move_direction . '",
            "random": false,
            "straight": false,
            "out_mode": "out",
            "bounce": false,
            "attract": {
              "enable": false,
              "rotateX": 600,
              "rotateY": 1200
            }
          }
        },
        "interactivity": {
          "detect_on": "canvas",
          "events": {
            "onhover": {
              "enable": false,
              "mode": "repulse"
            },
            "onclick": {
              "enable": false,
              "mode": "push"
            },
            "resize": true
          },
          "modes": {
            "grab": {
              "distance": 400,
              "line_linked": {
                "opacity": 1
              }
            },
            "bubble": {
              "distance": 400,
              "size": 40,
              "duration": 2,
              "opacity": 8,
              "speed": 3
            },
            "repulse": {
              "distance": 200,
              "duration": 0.4
            },
            "push": {
              "particles_nb": 4
            },
            "remove": {
              "particles_nb": 2
            }
          }
        },
        "retina_detect": true
    }',
            'nasa' => '{
        "particles": {
          "number": {
            "value": ' . $number . ',
            "density": {
              "enable": true,
              "value_area": 800
            }
          },
          "color": {
            "value": "' . $color . '"
          },
          "shape": {
            "type": "' . $shape_type . '",
            "stroke": {
              "width": 0,
              "color": "#000000"
            },
            "polygon": {
              "nb_sides": 5
            }
          },
          "opacity": {
            "value": 1,
            "random": true,
            "anim": {
              "enable": true,
              "speed": 1,
              "opacity_min": 0,
              "sync": false
            }
          },
          "size": {
            "value": ' . $size . ',
            "random": true,
            "anim": {
              "enable": false,
              "speed": 4,
              "size_min": 0.3,
              "sync": false
            }
          },
          "line_linked": {
            "enable": false,
            "distance": 150,
            "color": "#ffffff",
            "opacity": 0.4,
            "width": 1
          },
          "move": {
            "enable": true,
            "speed": ' . $speed . ',
            "direction": "' . $move_direction . '",
            "random": true,
            "straight": false,
            "out_mode": "out",
            "bounce": false,
            "attract": {
              "enable": false,
              "rotateX": 600,
              "rotateY": 600
            }
          }
        },
        "interactivity": {
          "detect_on": "canvas",
          "events": {
            "onhover": {
              "enable": false,
              "mode": "bubble"
            },
            "onclick": {
              "enable": false,
              "mode": "repulse"
            },
            "resize": true
          },
          "modes": {
            "grab": {
              "distance": 400,
              "line_linked": {
                "opacity": 1
              }
            },
            "bubble": {
              "distance": 250,
              "size": 0,
              "duration": 2,
              "opacity": 0,
              "speed": 3
            },
            "repulse": {
              "distance": 400,
              "duration": 0.4
            },
            "push": {
              "particles_nb": 4
            },
            "remove": {
              "particles_nb": 2
            }
          }
        },
        "retina_detect": true
    }',
            'bubble' => '{
        "particles": {
          "number": {
            "value": ' . $number . ',
            "density": {
              "enable": true,
              "value_area": 800
            }
          },
          "color": {
            "value": "' . $color . '"
          },
          "shape": {
            "type": "' . $shape_type . '",
            "stroke": {
              "width": 0,
              "color": "#000"
            },
            "polygon": {
              "nb_sides": 6
            }
          },
          "opacity": {
            "value": 0.3,
            "random": true,
            "anim": {
              "enable": false,
              "speed": 1,
              "opacity_min": 0.1,
              "sync": false
            }
          },
          "size": {
            "value": ' . $size . ',
            "random": false,
            "anim": {
              "enable": true,
              "speed": 10,
              "size_min": 40,
              "sync": false
            }
          },
          "line_linked": {
            "enable": false,
            "distance": 200,
            "color": "#ffffff",
            "opacity": 1,
            "width": 2
          },
          "move": {
            "enable": true,
            "speed": ' . $speed . ',
            "direction": "' . $move_direction . '",
            "random": false,
            "straight": false,
            "out_mode": "out",
            "bounce": false,
            "attract": {
              "enable": false,
              "rotateX": 600,
              "rotateY": 1200
            }
          }
        },
        "interactivity": {
          "detect_on": "canvas",
          "events": {
            "onhover": {
              "enable": false,
              "mode": "grab"
            },
            "onclick": {
              "enable": false,
              "mode": "push"
            },
            "resize": true
          },
          "modes": {
            "grab": {
              "distance": 400,
              "line_linked": {
                "opacity": 1
              }
            },
            "bubble": {
              "distance": 400,
              "size": 40,
              "duration": 2,
              "opacity": 8,
              "speed": 3
            },
            "repulse": {
              "distance": 200,
              "duration": 0.4
            },
            "push": {
              "particles_nb": 4
            },
            "remove": {
              "particles_nb": 2
            }
          }
        },
        "retina_detect": true
    }',
            'snow' => '{
        "particles": {
          "number": {
            "value": ' . $number . ',
            "density": {
              "enable": true,
              "value_area": 800
            }
          },
          "color": {
            "value": "' . $color . '"
          },
          "shape": {
            "type": "' . $shape_type . '",
            "stroke": {
              "width": 0,
              "color": "#000000"
            },
            "polygon": {
              "nb_sides": 5
            }
          },
          "opacity": {
            "value": 0.5,
            "random": true,
            "anim": {
              "enable": false,
              "speed": 1,
              "opacity_min": 0.1,
              "sync": false
            }
          },
          "size": {
            "value": ' . $size . ',
            "random": true,
            "anim": {
              "enable": false,
              "speed": 40,
              "size_min": 0.1,
              "sync": false
            }
          },
          "line_linked": {
            "enable": false,
            "distance": 500,
            "color": "#ffffff",
            "opacity": 0.4,
            "width": 2
          },
          "move": {
            "enable": true,
            "speed": ' . $speed . ',
            "direction": "' . $move_direction . '",
            "random": false,
            "straight": false,
            "out_mode": "out",
            "bounce": false,
            "attract": {
              "enable": false,
              "rotateX": 600,
              "rotateY": 1200
            }
          }
        },
        "interactivity": {
          "detect_on": "canvas",
          "events": {
            "onhover": {
              "enable": false,
              "mode": "bubble"
            },
            "onclick": {
              "enable": false,
              "mode": "repulse"
            },
            "resize": true
          },
          "modes": {
            "grab": {
              "distance": 400,
              "line_linked": {
                "opacity": 0.5
              }
            },
            "bubble": {
              "distance": 400,
              "size": 4,
              "duration": 0.3,
              "opacity": 1,
              "speed": 3
            },
            "repulse": {
              "distance": 200,
              "duration": 0.4
            },
            "push": {
              "particles_nb": 4
            },
            "remove": {
              "particles_nb": 2
            }
          }
        },
        "retina_detect": true
    }',
            'nyan_cat' => '{
        "particles": {
          "number": {
            "value": ' . $number . ',
            "density": {
              "enable": false,
              "value_area": 800
            }
          },
          "color": {
            "value": "' . $color . '"
          },
          "shape": {
            "type": "' . $shape_type . '",
            "stroke": {
              "width": 0,
              "color": "#000000"
            },
            "polygon": {
              "nb_sides": 5
            }
          },
          "opacity": {
            "value": 0.5,
            "random": false,
            "anim": {
              "enable": false,
              "speed": 1,
              "opacity_min": 0.1,
              "sync": false
            }
          },
          "size": {
            "value": ' . $size . ',
            "random": true,
            "anim": {
              "enable": false,
              "speed": 40,
              "size_min": 0.1,
              "sync": false
            }
          },
          "line_linked": {
            "enable": false,
            "distance": 150,
            "color": "#ffffff",
            "opacity": 0.4,
            "width": 1
          },
          "move": {
            "enable": true,
            "speed": ' . $speed . ',
            "direction": "' . $move_direction . '",
            "random": false,
            "straight": true,
            "out_mode": "out",
            "bounce": false,
            "attract": {
              "enable": false,
              "rotateX": 600,
              "rotateY": 1200
            }
          }
        },
        "interactivity": {
          "detect_on": "canvas",
          "events": {
            "onhover": {
              "enable": false,
              "mode": "grab"
            },
            "onclick": {
              "enable": false,
              "mode": "repulse"
            },
            "resize": true
          },
          "modes": {
            "grab": {
              "distance": 200,
              "line_linked": {
                "opacity": 1
              }
            },
            "bubble": {
              "distance": 400,
              "size": 40,
              "duration": 2,
              "opacity": 8,
              "speed": 3
            },
            "repulse": {
              "distance": 200,
              "duration": 0.4
            },
            "push": {
              "particles_nb": 4
            },
            "remove": {
              "particles_nb": 2
            }
          }
        },
        "retina_detect": true
    }'
        ];
    }
}