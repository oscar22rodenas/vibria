<?php

namespace King_Addons;

use Elementor\Controls_Manager;
use Elementor\Widget_Base;
use Elementor\Repeater;

if (!defined('ABSPATH')) {
    exit;
}

class Charts extends Widget_Base
{

    public function get_name()
    {
        return 'king-addons-charts';
    }

    public function get_title()
    {
        return esc_html__('Charts', 'king-addons');
    }

    public function get_icon()
    {
        return 'king-addons-icon king-addons-charts';
    }

    public function get_script_depends()
    {
        return [KING_ADDONS_ASSETS_UNIQUE_KEY . '-charts-charts', KING_ADDONS_ASSETS_UNIQUE_KEY . '-charts-script'];
    }

    public function get_style_depends()
    {
        return [
            KING_ADDONS_ASSETS_UNIQUE_KEY . '-animation-timing',
            KING_ADDONS_ASSETS_UNIQUE_KEY . '-animation-loading',
            KING_ADDONS_ASSETS_UNIQUE_KEY . '-charts-style'
        ];
    }

    public function get_categories()
    {
        return ['king-addons'];
    }

    public function get_keywords()
    {
        return ['charts', 'chart', 'polar', 'linear', 'graph', 'pie', 'radar', 'king addons', 'line charts',
            'radar charts', 'doughnut charts', 'pie charts', 'polararea charts', 'vertical', 'bar', 'polararea',
            'polar charts', 'bar charts', 'horizontal charts', 'vertical charts', 'horizontal',
            'king', 'addons', 'kingaddons', 'king-addons'];
    }

    public function get_custom_help_url()
    {
        return 'https://kingaddons.com/elementor/charts/';
    }

    public function add_control_choose_chart_data_source()
    {
        $this->add_control(
            'data_source',
            [
                'label' => esc_html__('Data Source', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'custom',
                'options' => [
                    'custom' => esc_html__('Custom', 'king-addons'),
                    'pro-csv' => 'CSV' . ' ' . esc_html__('File', 'king-addons') . ' (Pro)',
                ],
                'frontend_available' => true,
            ]
        );
    }

    protected function register_controls()
    {

        $this->start_controls_section(
            'section_chart_general',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('General', 'king-addons'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control_choose_chart_data_source();

        Core::renderUpgradeProNotice($this, Controls_Manager::RAW_HTML, 'charts', 'data_source', ['pro-csv']);

        $this->add_control(
            'csv_source',
            [
                'label' => esc_html__('CSV Source', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'url',
                'options' => [
                    'url' => esc_html__('Remote URL', 'king-addons'),
                    'file' => esc_html__('File', 'king-addons'),
                ],
                'condition' => [
                    'data_source' => 'csv',
                ],
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'data_csv_separator',
            [
                'label' => esc_html__('Separator', 'king-addons'),
                'type' => Controls_Manager::TEXT,
                'default' => ',',
                'label_block' => true,
                'condition' => [
                    'data_source' => 'csv',
                ],
            ]
        );

        $this->add_control(
            'data_source_csv_url',
            [
                'label' => esc_html__('Remote URL', 'king-addons'),
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'label_block' => true,
                'condition' => [
                    'data_source' => 'csv',
                    'csv_source' => 'url',
                ],
            ]
        );

        $this->add_control(
            'data_source_csv_file',
            [
                'label' => esc_html__('Upload CSV File', 'king-addons'),
                'type' => Controls_Manager::MEDIA,
                'dynamic' => ['
					active' => true,
                ],
                'media_type' => [],
                'label_block' => true,
                'condition' => [
                    'data_source' => 'csv',
                    'csv_source' => 'file',
                ],
            ]
        );

        $this->add_control(
            'chart_type',
            [
                'label' => esc_html__('Chart Styles', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'description' => esc_html__('Doughnut, pie and polar area charts only work with custom data source', 'king-addons'),
                'default' => 'bar',
                'options' => [
                    'bar' => esc_html__('Bar Vertical', 'king-addons'),
                    'bar_horizontal' => esc_html__('Bar Horizontal', 'king-addons'),
                    'line' => esc_html__('Line', 'king-addons'),
                    'radar' => esc_html__('Radar', 'king-addons'),
                    'doughnut' => esc_html__('Doughnut', 'king-addons'),
                    'pie' => esc_html__('Pie', 'king-addons'),
                    'polarArea' => esc_html__('Polar Area', 'king-addons'),
                ],
                'separator' => 'before'
            ]
        );

        $this->add_control(
            'data_type',
            [
                'label' => esc_html__('Data Grid Type', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'linear' => esc_html__('Linear', 'king-addons'),
                    'logarithmic' => esc_html__('Logarithmic', 'king-addons'),
                ],
                'default' => 'linear',
                'condition' => [
                    'chart_type' => ['bar', 'bar_horizontal', 'line']
                ],
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'column_width_x',
            [
                'label' => esc_html__('Column Width', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'default' => 0.5,
                'step' => 0.1,
                'min' => 0,
                'max' => 1,
                'frontend_available' => true,
                'separator' => 'before',
                'condition' => [
                    'chart_type' => ['bar', 'bar_horizontal'],
                ]
            ]
        );

        $this->add_control(
            'exclude_dataset_on_click',
            [
                'label' => esc_html__('Exclude Data on Legend Click', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'return_value' => 'yes',
                'separator' => 'before'
            ]
        );

        $this->add_control(
            'stacked_bar_chart',
            [
                'label' => esc_html__('Enable Stacked Chart', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'return_value' => 'yes',
                'condition' => [
                    'chart_type' => ['bar', 'bar_horizontal', 'radar', 'doughnut', 'pie']
                ]
            ]
        );

        $this->add_control(
            'inner_datalabels',
            [
                'label' => esc_html__('Show Data Values', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'false',
                'return_value' => 'true',
                'condition' => [
                    'chart_type' => ['bar', 'bar_horizontal', 'line', '']
                ]
            ]
        );

        $this->add_control(
            'enable_min_max',
            [
                'label' => esc_html__('Min-Max Values', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'no',
                'return_value' => 'yes',
                'separator' => 'before',
                'condition' => [
                    'chart_type!' => ['doughnut', 'polarArea', 'pie'],
                ]
            ]
        );

        $this->add_control(
            'min_value',
            [
                'label' => esc_html__('Min. Value', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'default' => -100,
                'condition' => [
                    'enable_min_max' => 'yes',
                ]
            ]
        );

        $this->add_control(
            'max_value',
            [
                'label' => esc_html__('Max. Value', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'default' => 100,
                'condition' => [
                    'enable_min_max' => 'yes',
                ]
            ]
        );

        $this->add_control(
            'animations_heading',
            [
                'label' => esc_html__('Animation', 'king-addons'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'chart_animation',
            [
                'label' => esc_html__('Animation', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'return_value' => 'yes',
                'chart_type' => ['line', 'radar']
            ]
        );

        $this->add_control(
            'chart_animation_loop',
            [
                'label' => esc_html__('Loop', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'return_value' => 'yes',
                'condition' => [
                    'chart_animation' => 'yes',
                    'chart_type' => ['line', 'radar']
                ]
            ]
        );

        $this->add_control(
            'chart_animation_duration',
            [
                'label' => esc_html__('Animation Duration (ms)', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'default' => 1000,
                'min' => 1,
                'condition' => [
                    'chart_animation' => 'yes',
                    'chart_type' => ['radar', 'line']
                ]
            ]
        );

        $this->add_control(
            'animation_transition_type',
            [
                'label' => esc_html__('Animation Timing', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'linear',
                'options' => [
                    'linear' => 'linear',
                    'easeInBack' => 'easeInBack',
                    'easeInBounce' => 'easeInBounce',
                    'easeInCirc' => 'easeInCirc',
                    'easeInCubic' => 'easeInCubic',
                    'easeInElastic' => 'easeInElastic',
                    'easeInExpo' => 'easeInExpo',
                    'easeInOutBack' => 'easeInOutBack',
                    'easeInOutBounce' => 'easeInOutBounce',
                    'easeInOutCirc' => 'easeInOutCirc',
                    'easeInOutCubic' => 'easeInOutCubic',
                    'easeInOutElastic' => 'easeInOutElastic',
                    'easeInOutExpo' => 'easeInOutExpo',
                    'easeInOutQuad' => 'easeInOutQuad',
                    'easeInOutQuart' => 'easeInOutQuart',
                    'easeInOutQuint' => 'easeInOutQuint',
                    'easeInOutSine' => 'easeInOutSine',
                    'easeInQuad' => 'easeInQuad',
                    'easeInQuart' => 'easeInQuart',
                    'easeInQuint' => 'easeInQuint',
                    'easeInSine' => 'easeInSine',
                    'easeOutBack' => 'easeOutBack',
                    'easeOutBounce' => 'easeOutBounce',
                    'easeOutCirc' => 'easeOutCirc',
                    'easeOutCubic' => 'easeOutCubic',
                    'easeOutElastic' => 'easeOutElastic',
                    'easeOutExpo' => 'easeOutExpo',
                    'easeOutQuad' => 'easeOutQuad',
                    'easeOutQuart' => 'easeOutQuart',
                    'easeOutQuint' => 'easeOutQuint',
                    'easeOutSine' => 'easeOutSine',
                ],
                'condition' => [
                    'chart_animation' => 'yes',
                    'chart_type' => ['radar', 'line']
                ]
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_chart_data',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Data', 'king-addons'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'charts_labels_data', [
                'label' => esc_html__('Data Labels', 'king-addons'),
                'type' => Controls_Manager::TEXTAREA,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => esc_html__('January, February, March', 'king-addons'),
                'description' => esc_html__('Enter the comma-separated list of values (Used only with custom data source)', 'king-addons'),
                'label_block' => true,
                'condition' => [
                    'data_source' => 'custom',

                ],
            ]
        );

        if (!king_addons_freemius()->can_use_premium_code__premium_only()) {
            $this->add_control(
                'charts_repeater_labels_data_pro_notice',
                [
                    'type' => Controls_Manager::RAW_HTML,
                    'raw' => 'More than 3 Data Labels are available<br> in the <strong><a href="https://kingaddons.com/pricing/?utm_source=kng-module-charts-settings-upgrade-pro&utm_medium=plugin&utm_campaign=kng" target="_blank">Pro version</a></strong>',
                    'content_classes' => 'king-addons-pro-notice',
                ]
            );
        }

        $chart_repeater_labels = new Repeater();

        $chart_repeater_labels->add_control(
            'chart_data_label', [
                'label' => esc_html__('Label', 'king-addons'),
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => esc_html__('Label #1', 'king-addons'),
                'label_block' => true,
            ]
        );

        $chart_repeater_labels->add_control(
            'chart_data_set', [
                'label' => esc_html__('Data', 'king-addons'),
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => '10,23,15',
                'label_block' => true,
                'description' => esc_html__("Only works with custom charts. Enter comma separated data values (Shouldn't Exceed number of values provided in Data Labels option).", 'king-addons'),
            ]
        );


        $chart_repeater_labels->start_controls_tabs(
            'chart_data_bar_background_tab'
        );

        $chart_repeater_labels->start_controls_tab(
            'chart_data_bar_background_normal',
            [
                'label' => esc_html__('Normal', 'king-addons'),
            ]
        );

        $chart_repeater_labels->add_control(
            'chart_data_background_color', [
                'label' => esc_html__('Background Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#5B03FF',
            ]
        );

        $chart_repeater_labels->add_control(
            'chart_data_border_color', [
                'label' => esc_html__('Border Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#5B03FF',
            ]
        );

        $chart_repeater_labels->end_controls_tab();


        $chart_repeater_labels->start_controls_tab(
            'chart_data_bar_background_hover',
            [
                'label' => esc_html__('Hover', 'king-addons'),
            ]
        );
        $chart_repeater_labels->add_control(
            'chart_data_background_color_hover', [
                'label' => esc_html__('Background Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#7A7A7ACC'
            ]
        );

        $chart_repeater_labels->add_control(
            'chart_data_border_color_hover', [
                'label' => esc_html__('Border Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#7A7A7ACC',
            ]
        );
        $chart_repeater_labels->end_controls_tab();

        $chart_repeater_labels->end_controls_tabs();


        $chart_repeater_labels->add_control(
            'chart_data_border_width', [
                'label' => esc_html__('Border Width', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'default' => '1',
            ]
        );

        $this->add_control(
            'charts_data_set',
            [
                'label' => esc_html__('Set Data', 'king-addons'),
                'type' => Controls_Manager::REPEATER,
                'default' => [
                    [
                        'chart_data_label' => esc_html__('Laptops', 'king-addons'),
                        'chart_data_set' => '13,20,15',
                        'chart_data_background_color' => '#5B03FFCC',
                        'chart_data_border_color' => '#5B03FFCC',
                        'chart_data_border_width' => 1,
                    ],
                    [
                        'chart_data_label' => esc_html__('Phones', 'king-addons'),
                        'chart_data_set' => '20,10,33',
                        'chart_data_background_color' => '#E5605BCC',
                        'chart_data_border_color' => '#E5605BCC',
                        'chart_data_border_width' => 1,
                    ],
                    [
                        'chart_data_label' => esc_html__('Other', 'king-addons'),
                        'chart_data_set' => '10,3,23',
                        'chart_data_background_color' => '#5BE560CC',
                        'chart_data_border_color' => '#5BE560CC',
                        'chart_data_border_width' => 1,
                    ],

                ],

                'fields' => $chart_repeater_labels->get_controls(),
                'title_field' => '{{{ chart_data_label }}}',

            ]
        );

        if (!king_addons_freemius()->can_use_premium_code__premium_only()) {
            $this->add_control(
                'charts_repeater_pro_notice',
                [
                    'type' => Controls_Manager::RAW_HTML,
                    'raw' => 'More than 3 Items are available<br> in the <strong><a href="https://kingaddons.com/pricing/?utm_source=kng-module-charts-settings-upgrade-pro&utm_medium=plugin&utm_campaign=kng" target="_blank">Pro version</a></strong>',
                    'content_classes' => 'king-addons-pro-notice',
                ]
            );
        }

        $this->end_controls_section();

        $this->start_controls_section(
            'section_chart_axis_r',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Axis', 'king-addons'),
                'tab' => Controls_Manager::TAB_CONTENT,
                'condition' => [
                    'chart_type' => ['radar', 'polarArea']
                ],
            ]
        );

        $this->add_control(
            'r_axis_conditions',
            [
                'label' => esc_html__('Axis', 'king-addons'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before'
            ]
        );

        $this->add_control(
            'display_r_axis',
            [
                'label' => esc_html__('Grid Lines', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'true',
                'return_value' => 'true'
            ]
        );

        $this->add_control(
            'grid_line_width_r', [
                'label' => esc_html__('Grid Line Width', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'default' => '1',
                'step' => 0.1,
                'condition' => [
                    'display_r_axis' => 'true'
                ]
            ]
        );

        $this->add_control(
            'display_r_ticks',
            [
                'label' => esc_html__('Ticks (Labels)', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'true',
                'return_value' => 'true'
            ]
        );

        $this->add_control(
            'r_step_size',
            [
                'label' => esc_html__('Step Size', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 360,
                'default' => 0,
                'frontend_available' => true,
                'condition' => [
                    'display_r_ticks' => 'true'
                ]
            ]
        );

        $this->add_control(
            'border_dash_length_r', [
                'label' => esc_html__('Border Dash length', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'default' => '1',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'border_dash_spacing_r', [
                'label' => esc_html__('Border Dash spacing', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'default' => '1'
            ]
        );

        $this->add_control(
            'border_dash_offset_r', [
                'label' => esc_html__('Border Dash offset', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'default' => '1'
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_chart_axis',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Axis', 'king-addons'),
                'tab' => Controls_Manager::TAB_CONTENT,
                'condition' => [
                    'chart_type' => ['bar', 'bar_horizontal', 'line']
                ],
            ]
        );

        $this->add_control(
            'x_axis_conditions',
            [
                'label' => esc_html__('X-Axis', 'king-addons'),
                'type' => Controls_Manager::HEADING,
            ]
        );

        $this->add_control(
            'display_x_axis',
            [
                'label' => esc_html__('Grid Lines', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'true',
                'return_value' => 'true',
            ]
        );

        $this->add_control(
            'grid_line_width_x', [
                'label' => esc_html__('Grid Line Width', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'default' => '1',
                'step' => 0.1,
                'condition' => [
                    'display_x_axis' => 'true'
                ]
            ]
        );

        $this->add_control(
            'display_x_ticks',
            [
                'label' => esc_html__('Ticks (Labels)', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'true',
                'return_value' => 'true'
            ]
        );

        $this->add_control(
            'x_step_size',
            [
                'label' => esc_html__('Step Size', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 360,
                'default' => 0,
                'frontend_available' => true,
                'condition' => [
                    'display_x_ticks' => 'true'
                ]
            ]
        );

        $this->add_control(
            'display_x_axis_title',
            [
                'label' => esc_html__('Show Title', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'true',
                'return_value' => 'true'
            ]
        );

        $this->add_control(
            'x_axis_title',
            [
                'label' => esc_html__('Title', 'king-addons'),
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'placeholder' => esc_html__('X-Axis', 'king-addons'),
                'default' => esc_html__('X-Axis', 'king-addons'),
                'condition' => [
                    'display_x_axis_title' => 'true'
                ]
            ]
        );

        $this->add_control(
            'labels_rotation_x_axis',
            [
                'label' => esc_html__('Ticks (Labels) Rotation', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 360,
                'default' => 0,
                'frontend_available' => true
            ]
        );

        $this->add_control(
            'y_axis_conditions',
            [
                'label' => esc_html__('Y-Axis', 'king-addons'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before'
            ]
        );

        $this->add_control(
            'display_y_axis',
            [
                'label' => esc_html__('Grid Lines', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'true',
                'return_value' => 'true'
            ]
        );

        $this->add_control(
            'grid_line_width_y', [
                'label' => esc_html__('Grid Line Width', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'default' => '1',
                'step' => 0.1,
                'condition' => [
                    'display_y_axis' => 'true'
                ]
            ]
        );

        $this->add_control(
            'display_y_ticks',
            [
                'label' => esc_html__('Ticks (Labels)', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'true',
                'return_value' => 'true'
            ]
        );

        $this->add_control(
            'y_step_size',
            [
                'label' => esc_html__('Step Size', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 360,
                'default' => 0,
                'frontend_available' => true,
                'condition' => [
                    'display_y_ticks' => 'true'
                ]
            ]
        );

        $this->add_control(
            'display_y_axis_title',
            [
                'label' => esc_html__('Show Title', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'true',
                'return_value' => 'true'
            ]
        );

        $this->add_control(
            'y_axis_title',
            [
                'label' => esc_html__('Title', 'king-addons'),
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'placeholder' => esc_html__('Y-Axis', 'king-addons'),
                'default' => esc_html__('Y-Axis', 'king-addons'),
                'condition' => [
                    'display_y_axis_title' => 'true'
                ]
            ]
        );

        $this->add_control(
            'labels_rotation_y_axis',
            [
                'label' => esc_html__('Ticks (Labels) Rotation', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 360,
                'default' => 0,
                'frontend_available' => true
            ]
        );

        $this->add_control(
            'reverse_x',
            [
                'label' => esc_html__('Reverse Charts', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'no',
                'return_value' => 'yes',
                'separator' => 'before',
                'condition' => [
                    'show_chart_legend' => 'yes',
                    'chart_type' => 'bar_horizontal'
                ]
            ]
        );

        $this->add_control(
            'reverse_y',
            [
                'label' => esc_html__('Reverse Charts', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'no',
                'return_value' => 'yes',
                'separator' => 'before',
                'condition' => [
                    'show_chart_legend' => 'yes',
                    'chart_type' => ['bar', 'line']
                ]
            ]
        );

        $this->add_control(
            'border_dash_length', [
                'label' => esc_html__('Border Dash length', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'default' => '1',
                'separator' => 'before',

                'conditions' => [
                    'relation' => 'or',
                    'terms' => [
                        [
                            'name' => 'display_x_axis',
                            'operator' => '==',
                            'value' => 'true'
                        ],
                        [
                            'name' => 'display_y_axis',
                            'operator' => '==',
                            'value' => 'true'
                        ]
                    ]
                ]
            ]
        );

        $this->add_control(
            'border_dash_spacing', [
                'label' => esc_html__('Border Dash spacing', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'default' => '1',

                'conditions' => [
                    'relation' => 'or',
                    'terms' => [
                        [
                            'name' => 'display_x_axis',
                            'operator' => '==',
                            'value' => 'true'
                        ],
                        [
                            'name' => 'display_y_axis',
                            'operator' => '==',
                            'value' => 'true'
                        ]
                    ]
                ]
            ]
        );

        $this->add_control(
            'border_dash_offset', [
                'label' => esc_html__('Border Dash offset', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'default' => '1',

                'conditions' => [
                    'relation' => 'or',
                    'terms' => [
                        [
                            'name' => 'display_x_axis',
                            'operator' => '==',
                            'value' => 'true'
                        ],
                        [
                            'name' => 'display_y_axis',
                            'operator' => '==',
                            'value' => 'true'
                        ]
                    ]
                ]
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_chart_title',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Title', 'king-addons'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'show_chart_title',
            [
                'label' => esc_html__('Show Title', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'yes'
            ]
        );

        $this->add_control(
            'chart_title',
            [
                'label' => esc_html__('Title', 'king-addons'),
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'placeholder' => esc_html__('To Be Applied', 'king-addons'),
                'default' => esc_html__('To Be Applied', 'king-addons'),
                'condition' => [
                    'show_chart_title' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'chart_title_position',
            [
                'label' => esc_html__('Position', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'top',
                'label_block' => false,
                'options' => [
                    'top' => esc_html__('Top', 'king-addons'),
                    'right' => esc_html__('Right', 'king-addons'),
                    'bottom' => esc_html__('Bottom', 'king-addons'),
                    'left' => esc_html__('Left', 'king-addons'),

                ],
                'condition' => [
                    'show_chart_title' => 'yes',
                ]
            ]
        );

        $this->add_control(
            'chart_title_align',
            [
                'label' => esc_html__('Align', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'center',
                'label_block' => false,
                'options' => [
                    'start' => esc_html__('Start', 'king-addons'),
                    'center' => esc_html__('Center', 'king-addons'),
                    'end' => esc_html__('End', 'king-addons'),
                ],
                'condition' => [
                    'show_chart_title' => 'yes',
                ]
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_chart_legend',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Legends', 'king-addons'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'show_chart_legend',
            [
                'label' => esc_html__('Show Legends', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'return_value' => 'yes',
            ]
        );

        $this->add_control(
            'reverse_legend',
            [
                'label' => esc_html__('Reverse', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'no',
                'return_value' => 'yes',
                'condition' => [
                    'show_chart_legend' => 'yes',
                ]
            ]
        );

        $this->add_control(
            'charts_legend_shape',
            [
                'label' => esc_html__('Shape', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'rectangle',
                'label_block' => false,
                'render_type' => 'template',
                'options' => [
                    'rectangle' => esc_html__('Rectangle', 'king-addons'),
                    'point' => esc_html__('Point', 'king-addons'),
                ],
                'condition' => [
                    'show_chart_legend' => 'yes',
                ]
            ]
        );

        $this->add_control(
            'charts_legend_position',
            [
                'label' => esc_html__('Position', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'top',
                'label_block' => false,
                'options' => [
                    'top' => esc_html__('Top', 'king-addons'),
                    'right' => esc_html__('Right', 'king-addons'),
                    'bottom' => esc_html__('Bottom', 'king-addons'),
                    'left' => esc_html__('Left', 'king-addons'),
                    'chartArea' => esc_html__('chartArea', 'king-addons'),
                ],
                'condition' => [
                    'show_chart_legend' => 'yes',
                ]
            ]
        );

        $this->add_control(
            'charts_legend_align',
            [
                'label' => esc_html__('Align', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'center',
                'label_block' => false,
                'options' => [
                    'start' => esc_html__('Start', 'king-addons'),
                    'center' => esc_html__('Center', 'king-addons'),
                    'end' => esc_html__('End', 'king-addons'),
                ],
                'condition' => [
                    'show_chart_legend' => 'yes',
                ]
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_chart_tooltip',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Tooltip', 'king-addons'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'show_chart_tooltip',
            [
                'label' => esc_html__('Show Tooltip', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'return_value' => 'yes'
            ]
        );

        $this->add_control(
            'tooltips_percent',
            [
                'label' => esc_html__('Convert Values to Percents', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'true',
                'default' => 'true',
                'frontend_available' => true,
                'condition' => [
                    'show_chart_tooltip' => 'yes'
                ]
            ]
        );

        $this->add_control(
            'trigger_tooltip_on',
            [
                'label' => esc_html__('Show Tooltip On', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'mousemove',
                'options' => [
                    'mousemove' => esc_html__('Hover', 'king-addons'),
                    'click' => esc_html__('Click', 'king-addons'),
                ],
                'condition' => [
                    'show_chart_tooltip' => 'yes'
                ]
            ]
        );

        $this->add_control(
            'chart_interaction_mode',
            [
                'label' => esc_html__('Interaction Mode', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'point',
                'options' => [

                    'point' => esc_html__('Point', 'king-addons'),
                    'index' => esc_html__('Index', 'king-addons'),
                    'dataset' => esc_html__('Dataset', 'king-addons'),
                ],
                'condition' => [
                    'show_chart_tooltip' => 'yes'
                ]
            ]
        );

        $this->add_control(
            'chart_tooltip_position',
            [
                'label' => esc_html__('Position', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'nearest',
                'options' => [
                    'nearest' => esc_html__('Nearest', 'king-addons'),
                    'average' => esc_html__('Average', 'king-addons'),
                ],
                'condition' => [
                    'show_chart_tooltip' => 'yes'
                ]
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_chart_lines',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Lines', 'king-addons'),
                'tab' => Controls_Manager::TAB_CONTENT,
                'condition' => [
                    'chart_type' => ['line', 'radar'],
                ]
            ]
        );

        $this->add_control(
            'show_lines',
            [
                'label' => esc_html__('Show Lines', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'return_value' => 'yes',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'chart_type_line_fill',
            [
                'label' => esc_html__('Show Background Fill', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'return_value' => 'yes',
                'condition' => [
                    'chart_type!' => ['bar', 'bar_horizontal'],
                ]
            ]
        );

        $this->add_control(
            'line_dots',
            [
                'label' => esc_html__('Show Dots', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'return_value' => 'yes',
                'condition' => [
                    'chart_type!' => ['bar', 'bar_horizontal'],
                ]
            ]
        );

        $this->add_responsive_control(
            'line_dots_radius',
            [
                'label' => esc_html__('Dots Size', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'devices' => ['desktop', 'mobile'],
                'exclude' => ['tablet'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'size_units' => ['px'],
                'default' => [
                    'unit' => 'px',
                    'size' => 10,
                ],
                'mobile_default' => [
                    'unit' => 'px',
                    'size' => 5
                ],
                'condition' => [
                    'chart_type!' => ['bar', 'bar_horizontal'],
                    'line_dots' => 'yes',
                ]
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'chart_section_style_res',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Responsive', 'king-addons'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'responsive_chart',
            [
                'label' => esc_html__('Responsive Layout', 'king-addons'),
                'description' => esc_html__('Enables scrollbar on tablet and mobile screens', 'king-addons'),
                'default' => 'yes',
                'type' => Controls_Manager::SWITCHER,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-charts-container' => 'overflow: auto;',
                    '{{WRAPPER}} .king-addons-charts-wrapper' => 'position: relative; margin: 0 auto;',
                ],
            ]
        );


        $this->add_responsive_control(
            'chart_res_width',
            [
                'label' => esc_html__('Min Width (px)', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1600
                    ]
                ],
                'default' => [
                    'size' => 800,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-charts-wrapper' => 'min-width: {{SIZE}}px;',
                ],
                'separator' => 'before',
                'condition' => [
                    'responsive_chart' => 'yes',
                ],
            ]
        );


        $this->add_responsive_control(
            'chart_res_height',
            [
                'label' => esc_html__('Min Height (px)', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1600
                    ]
                ],
                'default' => [
                    'size' => 400,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-charts-wrapper' => 'min-height: {{SIZE}}px;',
                ],
                'condition' => [
                    'responsive_chart' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();

        Core::renderProFeaturesSection($this, '', Controls_Manager::RAW_HTML, 'charts', [
            'Upload CSV File',
            'Import CSV File from URL',
            'Import Published Google Sheets',
            'Add Unlimited Chart Items',
            'Add Unlimited Data Labels'
        ]);

        $this->start_controls_section(
            'section_datalabels_styles',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Data Values', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'inner_datalabels' => 'true'
                ]
            ]
        );

        $this->add_control(
            'inner_datalabels_color', [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#FFF',
            ]
        );

        $this->add_control(
            'inner_datalabels_font_family',
            [
                'label' => esc_html__('Font Family', 'king-addons'),
                'type' => Controls_Manager::FONT,
                'description' => esc_html__('Use only the fonts located under System options group', 'king-addons'),
                'default' => 'Arial',
            ]
        );

        $this->add_control(
            'inner_datalabels_font_style',
            [
                'label' => esc_html__('Font Style', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'normal',
                'options' => [
                    'normal' => 'Normal',
                    'italic' => 'Italic',
                    'oblique' => 'Oblique',
                ],

            ]
        );

        $this->add_control(
            'inner_datalabels_font_weight',
            [
                'label' => esc_html__('Font Weight ', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'default' => 600,
                'frontend_available' => true
            ]
        );

        $this->add_control(
            'inner_datalabels_font_size',
            [
                'label' => esc_html__('Font Size', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 14,
                ]
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_styles_chart_axis_r',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Axis', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'chart_type' => ['radar', 'polarArea']
                ],
            ]
        );

        $this->add_control(
            'r_axis_angle_lines_heading',
            [
                'label' => esc_html__('Angle Lines', 'king-addons'),
                'type' => Controls_Manager::HEADING,
            ]
        );

        $this->add_control(
            'angle_lines_color', [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#E7E7E7'
            ]
        );

        $this->add_control(
            'r_axis_grid_lines_heading',
            [
                'label' => esc_html__('Grid Lines', 'king-addons'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'axis_grid_line_color_r', [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#E7E7E7'
            ]
        );

        $this->add_control(
            'axis_labels_heading',
            [
                'label' => esc_html__('Axis Labels', 'king-addons'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'axis_labels_color', [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#222222'
            ]
        );

        $this->add_control(
            'axis_labels_bg_color', [
                'label' => esc_html__('Background Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#B2B2B200'
            ]
        );

        $this->add_control(
            'axis_labels_padding',
            [
                'label' => esc_html__('Padding', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 5,
                ],
            ]
        );

        $this->add_control(
            'chart_point_labels_heading',
            [
                'label' => esc_html__('Ticks', 'king-addons'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before'
            ]
        );

        $this->add_control(
            'chart_point_labels_color_r', [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#B2B2B2'
            ]
        );

        $this->add_control(
            'point_labels_font_family_r',
            [
                'label' => esc_html__('Font Family', 'king-addons'),
                'type' => Controls_Manager::FONT,
                'description' => esc_html__('Use only the fonts located under System options group', 'king-addons'),
                'default' => 'Arial',
            ]
        );

        $this->add_control(
            'point_labels_font_style_r',
            [
                'label' => esc_html__('Font Style', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'normal',
                'options' => [
                    'normal' => 'Normal',
                    'italic' => 'Italic',
                    'oblique' => 'Oblique',
                ],

            ]
        );

        $this->add_control(
            'point_labels_font_weight_r',
            [
                'label' => esc_html__('Font Weight ', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'default' => 600,
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'point_labels_font_size_r',
            [
                'label' => esc_html__('Font Size', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 14,
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_chart_axis_styles',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Axis', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'chart_type' => ['bar', 'bar_horizontal', 'line']
                ],
            ]
        );

        $this->add_control(
            'x_axis_grid_lines_heading',
            [
                'label' => esc_html__('Grid Lines (X)', 'king-addons'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'axis_grid_line_color_x', [
                'label' => esc_html__('Color (X)', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#999999'
            ]
        );

        $this->add_control(
            'y_axis_grid_lines_heading',
            [
                'label' => esc_html__('Grid Lines (Y)', 'king-addons'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'axis_grid_line_color_y', [
                'label' => esc_html__('Color (Y)', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#999999'
            ]
        );

        $this->add_control(
            'x_axis_title_styles_heading',
            [
                'label' => esc_html__('X-Title', 'king-addons'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'axis_title_color_x', [
                'label' => esc_html__('Color (X)', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#C0C0C0'
            ]
        );

        $this->add_control(
            'axis_title_font_family_x',
            [
                'label' => esc_html__('Font Family', 'king-addons'),
                'type' => Controls_Manager::FONT,
                'description' => esc_html__('Use only the fonts located under System options group', 'king-addons'),
                'default' => 'Arial',
            ]
        );

        $this->add_control(
            'axis_title_font_style_x',
            [
                'label' => esc_html__('Font Style', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'normal',
                'options' => [
                    'normal' => 'Normal',
                    'italic' => 'Italic',
                    'oblique' => 'Oblique',
                ],

            ]
        );

        $this->add_control(
            'axis_title_font_weight_x',
            [
                'label' => esc_html__('Font Weight ', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'default' => 600,
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'axis_title_font_size_x',
            [
                'label' => esc_html__('Font Size', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 14,
                ],
            ]
        );

        $this->add_control(
            'y_axis_title_styles_heading',
            [
                'label' => esc_html__('Y-Title', 'king-addons'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'axis_title_color_y', [
                'label' => esc_html__('Color (Y)', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#C0C0C0'
            ]
        );

        $this->add_control(
            'axis_title_font_family_y',
            [
                'label' => esc_html__('Font Family', 'king-addons'),
                'type' => Controls_Manager::FONT,
                'description' => esc_html__('Use only the fonts located under System options group', 'king-addons'),
                'default' => 'Arial',
            ]
        );

        $this->add_control(
            'axis_title_font_style_y',
            [
                'label' => esc_html__('Font Style', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'normal',
                'options' => [
                    'normal' => 'Normal',
                    'italic' => 'Italic',
                    'oblique' => 'Oblique',
                ],

            ]
        );

        $this->add_control(
            'axis_title_font_weight_y',
            [
                'label' => esc_html__('Font Weight ', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'default' => 600,
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'axis_title_font_size_y',
            [
                'label' => esc_html__('Font Size', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 14,
                ],
            ]
        );

        $this->add_control(
            'x_ticks_styles_heading',
            [
                'label' => esc_html__('X-Ticks', 'king-addons'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'chart_ticks_color_x', [
                'label' => esc_html__('Ticks Color (X)', 'king-addons'),
                'type' => Controls_Manager::COLOR,
            ]
        );

        $this->add_control(
            'ticks_font_family_x',
            [
                'label' => esc_html__('Font Family', 'king-addons'),
                'type' => Controls_Manager::FONT,
                'description' => esc_html__('Use only the fonts located under System options group', 'king-addons'),
                'default' => 'Arial',
            ]
        );

        $this->add_control(
            'ticks_font_style_x',
            [
                'label' => esc_html__('Font Style', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'normal',
                'options' => [
                    'normal' => 'Normal',
                    'italic' => 'Italic',
                    'oblique' => 'Oblique',
                ],

            ]
        );

        $this->add_control(
            'ticks_font_weight_x',
            [
                'label' => esc_html__('Font Weight ', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'default' => 600,
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'ticks_font_size_x',
            [
                'label' => esc_html__('Font Size', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 17,
                ],
            ]
        );

        $this->add_control(
            'ticks_padding_x',
            [
                'label' => esc_html__('Distance', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'size_units' => ['px'],
                'default' => [
                    'unit' => 'px',
                    'size' => 10,
                ]
            ]
        );

        $this->add_control(
            'y_ticks_styles_heading',
            [
                'label' => esc_html__('Y-Ticks', 'king-addons'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'chart_ticks_color_y', [
                'label' => esc_html__('Ticks Color (Y)', 'king-addons'),
                'type' => Controls_Manager::COLOR,
            ]
        );

        $this->add_control(
            'ticks_font_family_y',
            [
                'label' => esc_html__('Font Family', 'king-addons'),
                'type' => Controls_Manager::FONT,
                'description' => esc_html__('Use only the fonts located under System options group', 'king-addons'),
                'default' => 'Arial',
            ]
        );

        $this->add_control(
            'ticks_font_style_y',
            [
                'label' => esc_html__('Font Style', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'normal',
                'options' => [
                    'normal' => 'Normal',
                    'italic' => 'Italic',
                    'oblique' => 'Oblique',
                ],

            ]
        );

        $this->add_control(
            'ticks_font_weight_y',
            [
                'label' => esc_html__('Font Weight ', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'default' => 600,
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'ticks_font_size_y',
            [
                'label' => esc_html__('Font Size', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 14,
                ],
            ]
        );

        $this->add_control(
            'ticks_padding_y',
            [
                'label' => esc_html__('Distance', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'size_units' => ['px'],
                'default' => [
                    'unit' => 'px',
                    'size' => 10,
                ]
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_chart_title_styles',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Title', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_chart_title' => 'yes'
                ]
            ]
        );

        $this->add_control(
            'chart_title_color', [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
            ]
        );

        $this->add_control(
            'title_font_family',
            [
                'label' => esc_html__('Font Family', 'king-addons'),
                'type' => Controls_Manager::FONT,
                'description' => esc_html__('Use only the fonts located under System options group', 'king-addons'),
                'default' => 'Arial',
            ]
        );

        $this->add_control(
            'title_font_style',
            [
                'label' => esc_html__('Font Style', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'normal',
                'options' => [
                    'normal' => 'Normal',
                    'italic' => 'Italic',
                    'oblique' => 'Oblique',
                ],

            ]
        );

        $this->add_control(
            'title_font_weight',
            [
                'label' => esc_html__('Font Weight ', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'default' => 600,
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'title_font_size',
            [
                'label' => esc_html__('Font Size', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 14,
                ],
            ]
        );

        $this->add_control(
            'chart_title_padding',
            [
                'label' => esc_html__('Padding', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'size_units' => ['px'],
                'default' => [
                    'unit' => 'px',
                    'size' => 10,
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_chart_legend_styles',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Legend', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'chart_legend_text_color', [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#222222'
            ]
        );

        $this->add_control(
            'legend_font_family',
            [
                'label' => esc_html__('Font Family', 'king-addons'),
                'type' => Controls_Manager::FONT,
                'description' => esc_html__('Use only the fonts located under System options group', 'king-addons'),
                'default' => 'Arial',
            ]
        );

        $this->add_control(
            'legend_font_style',
            [
                'label' => esc_html__('Font Style', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'normal',
                'options' => [
                    'normal' => 'Normal',
                    'italic' => 'Italic',
                    'oblique' => 'Oblique',
                ],

            ]
        );

        $this->add_control(
            'legend_font_weight',
            [
                'label' => esc_html__('Font Weight ', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'default' => 600,
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'legend_font_size',
            [
                'label' => esc_html__('Font Size', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 14,
                ],
            ]
        );

        $this->add_control(
            'legend_box_width',
            [
                'label' => esc_html__('Box Size', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 200,
                    ],
                ],
                'size_units' => ['px'],
                'default' => [
                    'unit' => 'px',
                    'size' => 40,
                ]
            ]
        );

        $this->add_control(
            'chart_legend_padding',
            [
                'label' => esc_html__('Gutter', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'size_units' => ['px'],
                'default' => [
                    'unit' => 'px',
                    'size' => 10,
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_chart_tooltip_styles',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Tooltip', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_chart_tooltip' => 'yes'
                ]
            ]
        );

        $this->add_control(
            'chart_tooltip_bg_color', [
                'label' => esc_html__('Background Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
            ]
        );

        $this->add_control(
            'tooltip_padding',
            [
                'label' => esc_html__('Padding', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 5,
                ],
            ]
        );

        $this->add_responsive_control(
            'tooltip_caret_size',
            [
                'label' => esc_html__('Triangle Size', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'devices' => ['desktop', 'mobile'],
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 10,
                ],
                'mobile_default' => [
                    'unit' => 'px',
                    'size' => 6,
                ],
            ]
        );

        $this->add_control(
            'tooltip_title_heading',
            [
                'label' => esc_html__('Tooltip Title', 'king-addons'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'chart_tooltip_title_color', [
                'label' => esc_html__('Title Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#FFF'
            ]
        );

        $this->add_control(
            'chart_tooltip_title_font',
            [
                'label' => esc_html__('Font Family', 'king-addons'),
                'type' => Controls_Manager::FONT,
                'description' => esc_html__('Use only the fonts located under System options group', 'king-addons'),
                'default' => 'Arial',
            ]
        );

        $this->add_control(
            'chart_tooltip_title_font_size',
            [
                'label' => esc_html__('Font Size', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 14,
                ],
                'separator' => 'before'
            ]
        );

        $this->add_control(
            'chart_tooltip_title_margin_bottom',
            [
                'label' => esc_html__('Title Spacing', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'size_units' => ['px'],
                'default' => [
                    'unit' => 'px',
                    'size' => 10,
                ],
            ]
        );

        $this->add_control(
            'chart_tooltip_title_align',
            [
                'label' => esc_html__('Title Alignment', 'king-addons'),
                'type' => Controls_Manager::CHOOSE,
                'default' => 'center',
                'label_block' => false,
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
                'separator' => 'before'
            ]
        );

        $this->add_control(
            'tooltip_item_heading',
            [
                'label' => esc_html__('Tooltip Item', 'king-addons'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'chart_tooltip_item_color', [
                'label' => esc_html__('Item Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#FFF'
            ]
        );

        $this->add_control(
            'chart_tooltip_item_font',
            [
                'label' => esc_html__('Font Family', 'king-addons'),
                'type' => Controls_Manager::FONT,
                'description' => esc_html__('Use only the fonts located under System options group', 'king-addons'),
                'default' => 'Arial',
            ]
        );

        $this->add_control(
            'chart_tooltip_item_font_size',
            [
                'label' => esc_html__('Font Size', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 14,
                ],
                'separator' => 'before'
            ]
        );

        $this->add_control(
            'chart_tooltip_item_spacing',
            [
                'label' => esc_html__('Item Spacing', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'size_units' => ['px'],
                'default' => [
                    'unit' => 'px',
                    'size' => 10,
                ],
            ]
        );

        $this->add_control(
            'chart_tooltip_item_align',
            [
                'label' => esc_html__('Item Alignment', 'king-addons'),
                'type' => Controls_Manager::CHOOSE,
                'default' => 'center',
                'label_block' => false,
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
                'separator' => 'before'
            ]
        );

        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        extract($settings);

        $premium = king_addons_freemius()->can_use_premium_code__premium_only();
        $data_charts_array = [];

        if (in_array($chart_type, ['bar', 'bar_horizontal', 'line', 'radar'])) {
            if (!empty($charts_labels_data)) {
                $labels = explode(',', trim($charts_labels_data));
                if (!$premium && count($labels) > 3) {
                    $labels = array_slice($labels, 0, 3);
                }
                $data_charts_array['labels'] = $labels;
            }

            if (is_array($charts_data_set) && count($charts_data_set)) {
                foreach ($charts_data_set as $charts_counter => $chart_data) {
                    if (!$premium && $charts_counter === 3) break;
                    $data_charts_array['datasets'][] = [
                        'label' => $chart_data['chart_data_label'],
                        'data' => array_map('floatval', explode(',', trim($chart_data['chart_data_set'], ','))),
                        'backgroundColor' => $chart_data['chart_data_background_color'],
                        'hoverBackgroundColor' => $chart_data['chart_data_background_color_hover'],
                        'borderColor' => $chart_data['chart_data_border_color'],
                        'hoverBorderColor' => $chart_data['chart_data_border_color_hover'],
                        'borderWidth' => $chart_data['chart_data_border_width'],
                        'barPercentage' => $settings['column_width_x'] ?? '',
                        'fill' => !empty($settings['chart_type_line_fill']) && 'yes' === $settings['chart_type_line_fill'],
                    ];
                }
            }
        } else {
            if (is_array($charts_data_set) && count($charts_data_set) && $settings['data_source'] !== 'csv') {
                $chart_data_number_values = $chart_background_colors = $chart_background_hover_colors = [];
                $chart_data_border_colors = $chart_data_border_hover_colors = $chart_data_border_width = $chart_data_bar_percentage = [];
                $data_charts_array['labels'] = [];

                foreach ($charts_data_set as $labels_data) {
                    if (!$premium && count($data_charts_array['labels']) === 3) break;
                    $data_charts_array['labels'][] = $labels_data['chart_data_label'];
                }

                $data_charts_array_test = !empty($charts_labels_data) ? explode(',', trim($charts_labels_data)) : [];

                foreach ($data_charts_array_test as $key => $test_data) {
                    if (!$premium && $key === 3) break;
                    $chart_data_number_values[$key] = [];
                    foreach ($charts_data_set as $key_inner => $chart_data) {
                        if (!$premium && $key_inner === 3) break;
                        $numbers = array_map('floatval', explode(',', trim($chart_data['chart_data_set'], ',')));
                        $chart_data_number_values[$key][] = $numbers[$key] ?? '0';
                    }
                }

                foreach ($charts_data_set as $key => $chart_data) {
                    if (!$premium && $key === 3) break;
                    $chart_background_colors[] = trim($chart_data['chart_data_background_color']);
                    $chart_background_hover_colors[] = trim($chart_data['chart_data_background_color_hover']);
                    $chart_data_border_colors[] = trim($chart_data['chart_data_border_color']);
                    $chart_data_border_hover_colors[] = trim($chart_data['chart_data_border_color_hover']);
                    $chart_data_border_width[] = trim($chart_data['chart_data_border_width']);
                    if (!empty($settings['column_width_x'])) {
                        $chart_data_bar_percentage[] = trim($chart_data['column_width_x']);
                    }
                }

                foreach ($data_charts_array_test as $key => $data_test) {
                    if (!$premium && $key === 3) break;
                    $data_charts_array['datasets'][] = [
                        'label' => $data_test,
                        'data' => $chart_data_number_values[$key],
                        'backgroundColor' => $chart_background_colors,
                        'hoverBackgroundColor' => $chart_background_hover_colors,
                        'borderColor' => $chart_data_border_colors,
                        'hoverBorderColor' => $chart_data_border_hover_colors,
                        'borderWidth' => $chart_data_border_width,
                        'barPercentage' => $chart_data_bar_percentage,
                    ];
                }
            }
        }

        $layout_settings = [
            'angle_lines_color' => $angle_lines_color ?? '',
            'animation_transition_type' => $animation_transition_type,
            'axis_grid_line_color_r' => $axis_grid_line_color_r,
            'axis_grid_line_color_x' => $axis_grid_line_color_x,
            'axis_grid_line_color_y' => $axis_grid_line_color_y,
            'axis_labels_bg_color' => $axis_labels_bg_color ?? '',
            'axis_labels_color' => $axis_labels_color ?? '',
            'axis_labels_padding' => $axis_labels_padding['size'] ?? '',
            'axis_title_color_x' => $axis_title_color_x,
            'axis_title_color_y' => $axis_title_color_y,
            'axis_title_font_family_x' => $axis_title_font_family_x,
            'axis_title_font_family_y' => $axis_title_font_family_y,
            'axis_title_font_size_x' => $axis_title_font_size_x['size'] ?? '',
            'axis_title_font_size_y' => $axis_title_font_size_y['size'] ?? '',
            'axis_title_font_style_x' => $axis_title_font_style_x,
            'axis_title_font_style_y' => $axis_title_font_style_y,
            'axis_title_font_weight_x' => $axis_title_font_weight_x,
            'axis_title_font_weight_y' => $axis_title_font_weight_y,
            'border_dash_length' => $border_dash_length,
            'border_dash_length_r' => $border_dash_length_r,
            'border_dash_offset' => $border_dash_offset,
            'border_dash_offset_r' => $border_dash_offset_r,
            'border_dash_spacing' => $border_dash_spacing,
            'border_dash_spacing_r' => $border_dash_spacing_r,
            'chart_animation' => $chart_animation ?? '',
            'chart_animation_duration' => $chart_animation_duration ?? '',
            'chart_animation_loop' => $chart_animation_loop ?? '',
            'chart_datasets' => !empty($data_charts_array['datasets']) ? wp_json_encode($data_charts_array['datasets']) : '',
            'chart_interaction_mode' => $chart_interaction_mode,
            'chart_labels' => $data_charts_array['labels'] ?? '',
            'chart_title' => $chart_title ?? '',
            'chart_title_align' => $chart_title_align ?? '',
            'chart_title_color' => $chart_title_color ?? '',
            'chart_title_position' => $chart_title_position ?? '',
            'chart_tooltip_bg_color' => $chart_tooltip_bg_color,
            'chart_tooltip_item_align' => $chart_tooltip_item_align,
            'chart_tooltip_item_color' => $chart_tooltip_item_color,
            'chart_tooltip_item_font' => $chart_tooltip_item_font,
            'chart_tooltip_item_font_size' => $chart_tooltip_item_font_size['size'] ?? 1,
            'chart_tooltip_item_spacing' => $chart_tooltip_item_spacing['size'] ?? 1,
            'chart_tooltip_title_align' => $chart_tooltip_title_align,
            'chart_tooltip_title_color' => $chart_tooltip_title_color,
            'chart_tooltip_title_font' => $chart_tooltip_title_font,
            'chart_tooltip_title_font_size' => $chart_tooltip_title_font_size['size'] ?? 1,
            'chart_tooltip_title_margin_bottom' => $chart_tooltip_title_margin_bottom['size'] ?? 1,
            'chart_type' => $chart_type,
            'data_source' => $data_source,
            'data_type' => $settings['data_type'] ?? 'linear',
            'display_r_axis' => $display_r_axis,
            'display_r_ticks' => $display_r_ticks,
            'display_x_axis' => $display_x_axis,
            'display_x_axis_title' => $display_x_axis_title,
            'display_x_ticks' => $display_x_ticks,
            'display_y_axis' => $display_y_axis,
            'display_y_axis_title' => $display_y_axis_title,
            'display_y_ticks' => $display_y_ticks,
            'exclude_dataset_on_click' => $exclude_dataset_on_click,
            'grid_line_width_r' => $grid_line_width_r,
            'grid_line_width_x' => $grid_line_width_x,
            'grid_line_width_y' => $grid_line_width_y,
            'inner_datalabels' => $inner_datalabels,
            'inner_datalabels_color' => $inner_datalabels_color,
            'inner_datalabels_font_family' => $inner_datalabels_font_family ?? '',
            'inner_datalabels_font_size' => $inner_datalabels_font_size['size'] ?? '',
            'inner_datalabels_font_style' => $inner_datalabels_font_style ?? '',
            'inner_datalabels_font_weight' => $inner_datalabels_font_weight ?? '',
            'legend_align' => $charts_legend_align,
            'legend_box_width' => $legend_box_width['size'],
            'legend_font_family' => $legend_font_family,
            'legend_font_size' => $legend_font_size['size'],
            'legend_font_style' => $legend_font_style,
            'legend_font_weight' => $legend_font_weight,
            'legend_padding' => $chart_legend_padding['size'],
            'legend_position' => $charts_legend_position,
            'legend_shape' => $charts_legend_shape,
            'legend_text_color' => $chart_legend_text_color,
            'line_dots' => $line_dots ?? '',
            'line_dots_radius' => $line_dots_radius['size'] ?? '',
            'line_dots_radius_mobile' => $line_dots_radius_mobile['size'] ?? 0,
            'max_value' => $max_value,
            'min_value' => $min_value,
            'point_labels_color_r' => $chart_point_labels_color_r ?? '',
            'point_labels_font_family_r' => $point_labels_font_family_r,
            'point_labels_font_size_r' => $point_labels_font_size_r['size'] ?? '',
            'point_labels_font_style_r' => $point_labels_font_style_r,
            'point_labels_font_weight_r' => $point_labels_font_weight_r,
            'r_step_size' => $r_step_size,
            'reverse_legend' => $reverse_legend,
            'reverse_x' => $reverse_x,
            'reverse_y' => $reverse_y,
            'rotation_x' => $labels_rotation_x_axis,
            'rotation_y' => $labels_rotation_y_axis,
            'separator' => $data_csv_separator,
            'show_chart_legend' => $show_chart_legend,
            'show_chart_title' => $show_chart_title,
            'show_chart_tooltip' => $show_chart_tooltip,
            'show_lines' => $show_lines ?? '',
            'stacked_bar_chart' => $stacked_bar_chart ?? '',
            'ticks_color_x' => $chart_ticks_color_x,
            'ticks_color_y' => $chart_ticks_color_y ?? '',
            'ticks_font_family_x' => $ticks_font_family_x,
            'ticks_font_family_y' => $ticks_font_family_y,
            'ticks_font_size_x' => $ticks_font_size_x['size'] ?? '',
            'ticks_font_size_y' => $ticks_font_size_y['size'] ?? '',
            'ticks_font_style_x' => $ticks_font_style_x,
            'ticks_font_style_y' => $ticks_font_style_y,
            'ticks_font_weight_x' => $ticks_font_weight_x,
            'ticks_font_weight_y' => $ticks_font_weight_y,
            'ticks_padding_x' => $ticks_padding_x['size'] ?? '',
            'ticks_padding_y' => $ticks_padding_y['size'] ?? '',
            'title_font_family' => $title_font_family,
            'title_font_size' => $title_font_size['size'] ?? '',
            'title_font_style' => $title_font_style ?? '',
            'title_font_weight' => $title_font_weight,
            'title_padding' => $chart_title_padding['size'] ?? '',
            'tooltips_percent' => $tooltips_percent,
            'tooltip_caret_size' => $tooltip_caret_size['size'] ?? 1,
            'tooltip_caret_size_mobile' => $tooltip_caret_size_mobile['size'] ?? 0,
            'tooltip_padding' => $tooltip_padding['size'] ?? 1,
            'tooltip_position' => $chart_tooltip_position,
            'trigger_tooltip_on' => $trigger_tooltip_on,
            'url' => !empty($data_source_csv_url) ? $data_source_csv_url : (!empty($data_source_csv_file['url']) ? $data_source_csv_file['url'] : ''),
            'x_axis_title' => $x_axis_title,
            'x_step_size' => $x_step_size,
            'y_axis_title' => $y_axis_title,
            'y_step_size' => $y_step_size,
        ];

        $this->add_render_attribute('chart-settings', [
            'class' => 'king-addons-charts-container',
            'data-settings' => wp_json_encode($layout_settings),
        ]);

        echo '<div ' . $this->get_render_attribute_string('chart-settings') . '>';
        if ($data_source === 'csv') {
            echo '<span class="king-addons-rotating-plane"></span>';
        }
        echo '<div class="king-addons-charts-wrapper">';
        echo '<canvas class="king-addons-chart"></canvas>';
        echo '</div></div>';
    }

}