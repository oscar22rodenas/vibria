<?php /** @noinspection PhpUnused */

namespace King_Addons;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;

if (!defined('ABSPATH')) {
    exit;
}

class Google_Maps extends Widget_Base
{

    public function get_name()
    {
        return 'king-addons-google-maps';
    }

    public function get_title()
    {
        return esc_html__('Google Maps', 'king-addons');
    }

    public function get_icon()
    {
        return 'king-addons-icon king-addons-google-maps';
    }

    public function get_categories()
    {
        return ['king-addons'];
    }

    public function get_keywords()
    {
        return ['king addons', 'king', 'addons', 'kingaddons', 'king-addons', 'google maps', 'location', 'gmap',
            'cluster', 'google', 'maps', 'map', 'point', 'hotspot', 'spot'];
    }

    public function get_script_depends()
    {
        return [KING_ADDONS_ASSETS_UNIQUE_KEY . '-google-maps-script', KING_ADDONS_ASSETS_UNIQUE_KEY . '-markerclusterer-markerclusterer'];
    }

    public function get_style_depends(): array
    {
        return [KING_ADDONS_ASSETS_UNIQUE_KEY . '-google-maps-style'];
    }

    public function get_custom_help_url()
    {
        return 'https://kingaddons.com/';
    }

    protected function register_controls()
    {
        $this->start_controls_section(
            'section_google_map_general',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('General', 'king-addons'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        if ('' == get_option('king_addons_google_map_api_key')) {
            /** @noinspection HtmlUnknownTarget */
            $this->add_control(
                'gm_api_notice',
                [
                    'type' => Controls_Manager::RAW_HTML,
                    'raw' => sprintf(__('Please enter the <strong>Google Map API Key</strong> in the <br><a href="%s" target="_blank">Dashboard > %s > Settings</a> tab to activate this widget.', 'king-addons'), admin_url('admin.php?page=king-addons-settings'), Core::getPluginName()),
                    'separator' => 'after',
                    'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
                ]
            );
        }

        $this->add_control(
            'gm_type',
            [
                'label' => esc_html__('Select Map Type', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'roadmap' => esc_html__('Road Map', 'king-addons'),
                    'satellite' => esc_html__('Satellite', 'king-addons'),
                    'hybrid' => esc_html__('Hybrid', 'king-addons'),
                    'terrain' => esc_html__('Terrain', 'king-addons'),
                ],
                'default' => 'roadmap',
            ]
        );

        $this->add_control(
            'gm_color_scheme',
            [
                'label' => esc_html__('Color Scheme', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'default' => esc_html__('Default', 'king-addons'),
                    'blue-essence' => esc_html__('Blue Essence', 'king-addons'),
                    'blue-water' => esc_html__('Blue Water', 'king-addons'),
                    'golden-brown' => esc_html__('Golden Brown', 'king-addons'),
                    'light-grayscale' => esc_html__('Light Grayscale', 'king-addons'),
                    'light-silver' => esc_html__('Light Silver', 'king-addons'),
                    'midnight-commander' => esc_html__('Midnight Commander', 'king-addons'),
                    'mostly-green' => esc_html__('Mostly Green', 'king-addons'),
                    'mostly-white' => esc_html__('Mostly White', 'king-addons'),
                    'neutral-blue' => esc_html__('Neutral Blue', 'king-addons'),
                    'shades-of-grey' => esc_html__('Shades of Grey', 'king-addons'),
                    'simple' => esc_html__('Simple', 'king-addons'),
                    'subtle-grayscale' => esc_html__('Subtle Grayscale', 'king-addons'),
                    'white-black' => esc_html__('White Black', 'king-addons'),
                    'yellow-black' => esc_html__('Yellow Black', 'king-addons'),
                    'custom' => esc_html__('Custom', 'king-addons'),
                ],
                'default' => 'default',
                'condition' => [
                    'gm_type!' => 'satellite',
                ]
            ]
        );

        $this->add_control(
            'gm_custom_color_scheme',
            [
                'label' => esc_html__('Custom Style', 'king-addons'),
                'description' => __('Get custom map style code from <a href="https://snazzymaps.com/explore" target="_blank">Snazzy Maps</a> or <a href="https://mapstyle.withgoogle.com/" target="_blank">GM Styling Wizard</a> and copy/paste in this field.', 'king-addons'),
                'type' => Controls_Manager::TEXTAREA,
                'dynamic' => [
                    'active' => true,
                ],
                'condition' => [
                    'gm_color_scheme' => 'custom',
                ]
            ]
        );

        $this->add_responsive_control(
            'gm_height',
            [
                'label' => esc_html__('Map Height', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 500,
                ],
                'range' => [
                    'px' => [
                        'min' => 100,
                        'max' => 1000,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-google-map' => 'height: {{SIZE}}px;',
                ],
                'separator' => 'before'
            ]
        );

        $this->add_control(
            'gm_zoom_depth',
            [
                'label' => esc_html__('Zoom Depth', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 10,
                ],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 20,
                    ],
                ],
            ]
        );

        $this->add_control(
            'gm_zoom_on_scroll',
            [
                'label' => esc_html__('Disable Zoom on Scroll', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'return_value' => 'cooperative',
                'separator' => 'before'
            ]
        );

        $this->add_control(
            'gm_cluster_markers',
            [
                'label' => esc_html__('Cluster Markers', 'king-addons'),
                'description' => esc_html__('Combine markers in close proximity into clusters to simplify the display of markers on the map.', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_google_map_locations',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Locations', 'king-addons'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'gm_location_helper',
            [
                'type' => Controls_Manager::RAW_HTML,
                'raw' => '<a href="https://www.latlong.net/" target="_blank">' . esc_html__('Click Here', 'king-addons') . '</a> ' . esc_html__('to find Coordinates of your location.', 'king-addons'),
                'separator' => 'after'
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'gm_latitude',
            [
                'label' => esc_html__('Latitude', 'king-addons'),
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
            ]
        );

        $repeater->add_control(
            'gm_longitude',
            [
                'label' => esc_html__('Longitude', 'king-addons'),
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
            ]
        );

        $repeater->add_control(
            'gm_show_info_window',
            [
                'label' => esc_html__('Show Info Window', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'none' => esc_html__('None', 'king-addons'),
                    'load' => esc_html__('on Load', 'king-addons'),
                    'click' => esc_html__('on Click', 'king-addons'),
                ],
                'default' => 'load',
                'separator' => 'before'
            ]
        );

        $repeater->add_control(
            'gm_location_title',
            [
                'label' => esc_html__('Location Title', 'king-addons'),
                'default' => esc_html__('Location Title', 'king-addons'),
                'label_block' => true,
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'condition' => [
                    'gm_show_info_window!' => 'none',
                ]
            ]
        );

        $repeater->add_control(
            'gm_location_description',
            [
                'label' => esc_html__('Location Description', 'king-addons'),
                'default' => esc_html__('Location Description', 'king-addons'),
                'type' => Controls_Manager::TEXTAREA,
                'dynamic' => [
                    'active' => true,
                ],
                'condition' => [
                    'gm_show_info_window!' => 'none',
                ]
            ]
        );

        $repeater->add_control(
            'gm_info_window_width',
            [
                'label' => esc_html__('Info Window Width', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 300,
                ],
                'range' => [
                    'px' => [
                        'min' => 50,
                        'max' => 500,
                    ],
                ],
                'condition' => [
                    'gm_show_info_window!' => 'none',
                ]
            ]
        );

        $repeater->add_control(
            'gm_marker_animation',
            [
                'label' => esc_html__('Marker Animation', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'none' => esc_html__('None', 'king-addons'),
                    'DROP' => esc_html__('Drop', 'king-addons'),
                    'BOUNCE' => esc_html__('Bounce', 'king-addons'),
                ],
                'default' => 'none',
                'separator' => 'before'
            ]
        );

        $repeater->add_control(
            'gm_custom_marker',
            [
                'label' => esc_html__('Use Custom Marker', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'separator' => 'before'
            ]
        );

        $repeater->add_control(
            'gm_marker_icon',
            [
                'label' => esc_html__('Upload Marker Icon', 'king-addons'),
                'type' => Controls_Manager::MEDIA,
                'dynamic' => [
                    'active' => true,
                ],
                'condition' => [
                    'gm_custom_marker' => 'yes',
                ]
            ]
        );

        $repeater->add_control(
            'gm_marker_icon_size_width',
            [
                'label' => esc_html__('Marker Icon Size Width', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 35,
                ],
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 150,
                    ],
                ],
                'condition' => [
                    'gm_custom_marker' => 'yes',
                ]
            ]
        );

        $repeater->add_control(
            'gm_marker_icon_size_height',
            [
                'label' => esc_html__('Marker Icon Size Height', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 35,
                ],
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 150,
                    ],
                ],
                'condition' => [
                    'gm_custom_marker' => 'yes',
                ]
            ]
        );

        $this->add_control(
            'google_map_locations',
            [
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'gm_location_title' => 'Central Park, New York, USA',
                        'gm_location_description' => 'Our Office, call 234-567-COFFEE to get a coffee.',
                        'gm_latitude' => '40.782864',
                        'gm_longitude' => '-73.965355',
                    ],
                ],
                'title_field' => '{{{ gm_location_title }}}',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_google_map_controls',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Controls', 'king-addons'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'gm_controls_map_type',
            [
                'label' => esc_html__('Show Map Type Control', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes'
            ]
        );

        $this->add_control(
            'gm_controls_fullscreen',
            [
                'label' => esc_html__('Show FullScreen Control', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes'
            ]
        );

        $this->add_control(
            'gm_controls_zoom',
            [
                'label' => esc_html__('Show Zoom Control', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes'
            ]
        );

        $this->add_control(
            'gm_controls_street_view',
            [
                'label' => esc_html__('Show Street View Control', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes'
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_info_window',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Info Window', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            ]
        );

        $this->add_control(
            'infow_window_align',
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
                    'justify' => [
                        'title' => esc_html__('Justified', 'king-addons'),
                        'icon' => 'eicon-text-align-justify',
                    ],
                ],
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-google-map .gm-style-iw-c' => 'text-align: {{VALUE}}',
                ],
                'separator' => 'after',
            ]
        );

        $this->add_control(
            'infow_window_title_color',
            [
                'label' => esc_html__('Title Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-google-map .gm-style-iw-c .king-addons-gm-iwindow h3' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'infow_window_description_color',
            [
                'label' => esc_html__('Description Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-google-map .gm-style-iw-c .king-addons-gm-iwindow p' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'infow_window_background_color',
            [
                'label' => esc_html__('Background Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-google-map .gm-style-iw-d' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .king-addons-google-map .gm-style .gm-style-iw-c' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .king-addons-google-map .gm-style-iw-t:after' => 'background: {{VALUE}}',
                    '{{WRAPPER}} .king-addons-google-map .gm-style-iw-tc:after' => 'background: {{VALUE}}'
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'infow_window_title_typography',
                'label' => esc_html__('Title Typography', 'king-addons'),
                'fields_options' => [
                    'typography' => [
                        'default' => 'custom',
                    ],
                    'font_size' => [
                        'default' => [
                            'size' => '19',
                            'unit' => 'px',
                        ],
                    ],
                    'font_weight' => [
                        'default' => '600',
                    ]
                ],
                'selector' => '{{WRAPPER}} .king-addons-google-map .gm-style-iw-c .king-addons-gm-iwindow h3'
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'infow_window_desc_typography',
                'label' => esc_html__('Description Typography', 'king-addons'),
                'fields_options' => [
                    'typography' => [
                        'default' => 'custom',
                    ],
                    'font_size' => [
                        'default' => [
                            'size' => '14',
                            'unit' => 'px',
                        ],
                    ],
                    'font_weight' => [
                        'default' => '400',
                    ]
                ],
                'selector' => '{{WRAPPER}} .king-addons-google-map .gm-style-iw-c .king-addons-gm-iwindow p'
            ]
        );

        $this->add_responsive_control(
            'infow_window_padding',
            [
                'label' => esc_html__('Padding', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'default' => [
                    'top' => 0,
                    'right' => 15,
                    'bottom' => 15,
                    'left' => 15,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-google-map .gm-style-iw-c .king-addons-gm-iwindow' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'infow_window_radius',
            [
                'label' => esc_html__('Border Radius', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'default' => [
                    'top' => 5,
                    'right' => 5,
                    'bottom' => 5,
                    'left' => 5,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-google-map .gm-style-iw-c' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'infow_window_distance',
            [
                'label' => esc_html__('Distance from Marker', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 10,
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 30,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-google-map .gm-style-iw-a' => 'transform: translateY(-{{SIZE}}px);',
                ],
                'separator' => 'before'
            ]
        );

        $this->end_controls_section();

    }

    public function get_map_settings($settings) {
        $map_settings = [
            'type'           => $settings['gm_type'],
            'style'          => $settings['gm_color_scheme'],
            'zoom_depth'     => $settings['gm_zoom_depth']['size'],
            'zoom_on_scroll' => $settings['gm_zoom_on_scroll'],
            'cluster_markers'=> $settings['gm_cluster_markers'],
            'clusters_url'   => KING_ADDONS_URL . 'includes/assets/libraries/markerclusterer/clusters/m',
        ];

        // Only set custom_style if it's not an array
        if (!is_array($settings['gm_custom_color_scheme'])) {
            $map_settings['custom_style'] = preg_replace(
                '/\s/',
                '',
                strip_tags($settings['gm_custom_color_scheme'])
            );
        }

        return $map_settings;
    }

    public function get_map_controls($settings) {
        return [
            'type'       => $settings['gm_controls_map_type'],
            'fullscreen' => $settings['gm_controls_fullscreen'],
            'zoom'       => $settings['gm_controls_zoom'],
            'streetview' => $settings['gm_controls_street_view'],
        ];
    }

    protected function render() {
        $settings = $this->get_settings();
        $locations = $settings['google_map_locations'] ?? [];

        // Sanitize the first location's title (if it exists)
        if (!empty($locations[0]['gm_location_title'])) {
            $title = $locations[0]['gm_location_title'];
            $title = preg_replace('/<\s*(img|script)[^>]*>/i', '', $title);
            $title = preg_replace('/\s*on\w+="[^"]*"/i', '', $title);
            $title = htmlspecialchars($title, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
            $locations[0]['gm_location_title'] = $title;
        }

        // Convert arrays to JSON
        $map_settings_json = json_encode(
            $this->get_map_settings($settings),
            JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT
        );
        $map_locations_json = json_encode(
            $locations,
            JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT
        );
        $map_controls_json = json_encode(
            $this->get_map_controls($settings),
            JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT
        );

        // Build attributes for the map container
        $attributes = sprintf(
            ' data-settings="%s" data-locations="%s" data-controls="%s"',
            esc_attr($map_settings_json),
            esc_attr($map_locations_json),
            esc_attr($map_controls_json)
        );

        echo '<div class="king-addons-google-map"' . $attributes . '></div>';

        // Alert if no Google Map API Key is set
        if (current_user_can('manage_options') && !get_option('king_addons_google_map_api_key')) {
            /** @noinspection HtmlUnknownTarget */
            printf(
                '<p class="king-addons-api-key-missing">Please go to the plugin <a href="%s" target="_blank">Settings</a> and insert a Google Map API Key to enable Google Maps.</p>',
                esc_url(admin_url('admin.php?page=king-addons-settings'))
            );
        }
    }
}