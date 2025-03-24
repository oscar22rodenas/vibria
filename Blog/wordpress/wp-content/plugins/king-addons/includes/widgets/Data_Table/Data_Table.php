<?php

namespace King_Addons;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Icons_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;

defined('ABSPATH') || die();

class Data_Table extends Widget_Base
{
    public function get_name()
    {
        return 'king-addons-data-table';
    }

    public function get_title()
    {
        return esc_html__('Data Table', 'king-addons');
    }

    public function get_icon()
    {
        return 'king-addons-icon king-addons-data-table';
    }

    public function get_categories()
    {
        return ['king-addons'];
    }

    public function get_keywords()
    {
        return ['king addons', 'king', 'addons', 'kingaddons', 'king-addons', 'data table', 'table',
            'comparison', 'data', 'comparison table', 'table comparison', 'tab', 'excel', 'csv', 'excel table',
            'xls', 'xls table'];
    }

    public function get_script_depends()
    {
        return [
            KING_ADDONS_ASSETS_UNIQUE_KEY . '-perfectscrollbar-perfectscrollbar',
            KING_ADDONS_ASSETS_UNIQUE_KEY . '-tabletoexcel-tabletoexcel',
            KING_ADDONS_ASSETS_UNIQUE_KEY . '-data-table-script'
        ];
    }

    public function get_style_depends(): array
    {
        return [
            KING_ADDONS_ASSETS_UNIQUE_KEY . '-data-table-style',
            KING_ADDONS_ASSETS_UNIQUE_KEY . '-general-general',
        ];
    }

    public function get_custom_help_url()
    {
        return 'https://kingaddons.com/';
    }

    public function add_control_choose_table_type()
    {
        $this->add_control(
            'choose_table_type',
            [
                'label' => esc_html__('Data Type', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'custom',
                'render_type' => 'template',
                'options' => [
                    'custom' => esc_html__('Custom', 'king-addons'),
                    'pro-cv' => esc_html__('CSV (Pro)', 'king-addons'),
                ],
                'prefix_class' => 'king-addons-data-table-type-'
            ]
        );
    }

    public function add_control_enable_table_export()
    {
        $this->add_control(
            'enable_table_export',
            [
                'label' => sprintf(__('Show Export Buttons %s', 'king-addons'), '<i class="eicon-pro-icon"></i>'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'no',
                'separator' => 'before',
                'classes' => 'king-addons-pro-control'
            ]
        );
    }

    public function add_control_export_excel_text()
    {
    }

    public function add_control_export_buttons_distance()
    {
    }

    public function add_control_table_search_input_padding()
    {
    }

    public function add_control_export_csv_text()
    {
    }

    public function add_section_export_buttons_styles()
    {
    }

    public function add_control_enable_table_search()
    {
        $this->add_control(
            'enable_table_search',
            [
                'label' => sprintf(__('Show Search %s', 'king-addons'), '<i class="eicon-pro-icon"></i>'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'no',
                'separator' => 'before',
                'classes' => 'king-addons-pro-control'
            ]
        );
    }

    public function add_section_search_styles()
    {
    }

    public function add_control_enable_table_sorting()
    {
        $this->add_control(
            'enable_table_sorting',
            [
                'label' => sprintf(__('Show Sorting %s', 'king-addons'), '<i class="eicon-pro-icon"></i>'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'no',
                'separator' => 'before',
                'classes' => 'king-addons-pro-control'
            ]
        );
    }

    public function add_control_active_td_bg_color()
    {
    }

    public function add_control_enable_custom_pagination()
    {
        $this->add_control(
            'enable_custom_pagination',
            [
                'label' => sprintf(__('Show Pagination %s', 'king-addons'), '<i class="eicon-pro-icon"></i>'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'no',
                'separator' => 'before',
                'classes' => 'king-addons-pro-control'
            ]
        );
    }

    public function add_section_pagination_styles()
    {
    }

    public function add_control_stack_content_tooltip_section()
    {
    }

    public function add_repeater_args_content_tooltip()
    {
        return [
            'label' => sprintf(__('Show Tooltip %s', 'king-addons'), '<i class="eicon-pro-icon"></i>'),
            'type' => Controls_Manager::SWITCHER,
            'separator' => 'before',
            'classes' => 'king-addons-pro-control'
        ];
    }

    public function add_repeater_args_content_tooltip_text()
    {
        return [
            'type' => Controls_Manager::HIDDEN,
            'default' => ''
        ];
    }

    public function add_repeater_args_content_tooltip_show_icon()
    {
        return [
            'type' => Controls_Manager::HIDDEN,
            'default' => ''
        ];
    }

    public function register_controls()
    {

        $this->start_controls_section(
            'section_preview',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('General', 'king-addons'),
            ]
        );


        $this->add_control_choose_table_type();

        Core::renderUpgradeProNotice($this, Controls_Manager::RAW_HTML, 'data-table', 'choose_table_type', ['pro-cv']);

        $this->add_control_enable_table_export();

        $this->add_control_export_excel_text();

        $this->add_control_export_csv_text();

        $this->add_control_enable_table_search();

        $this->add_control_enable_table_sorting();

        $this->add_control_enable_custom_pagination();

        $this->add_control(
            'equal_column_width',
            [
                'label' => esc_html__('Equal Column Width', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'separator' => 'before',
                'prefix_class' => 'king-addons-equal-column-width-'
            ]
        );

        $this->add_control(
            'enable_row_pagination',
            [
                'label' => esc_html__('Table Row Index', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default' => 'yes',
                'separator' => 'before'
            ]
        );

        $this->add_control(
            'white_space_text',
            [
                'label' => esc_html__('Prevent Word Wrap', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'prefix_class' => 'king-addons-table-text-nowrap-',
                'separator' => 'before'
            ]
        );


        $this->add_control(
            'table_export_csv_button',
            [
                'label' => esc_html__('Export table as CSV file', 'king-addons'),
                'type' => Controls_Manager::BUTTON,
                'text' => esc_html__('Export', 'king-addons'),
                'event' => 'king-addons-data-table-export',
                'separator' => 'before'
            ]
        );

        $this->end_controls_section();


        $this->start_controls_section(
            'section_header',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Header', 'king-addons'),
                'condition' => [
                    'choose_table_type' => 'custom'
                ]
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'table_th', [
                'label' => esc_html__('Title', 'king-addons'),
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => esc_html__('Table Title', 'king-addons'),
                'label_block' => true
            ]
        );

        $repeater->add_responsive_control(
            'header_icon',
            [
                'label' => esc_html__('Media', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default' => 'no',
                'separator' => 'before'
            ]
        );

        $repeater->add_control(
            'header_icon_type',
            [
                'label' => esc_html__('Media Type', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'label_block' => false,
                'default' => 'icon',
                'options' => [
                    'icon' => esc_html__('Icon', 'king-addons'),
                    'image' => esc_html__('Image', 'king-addons'),
                ],
                'condition' => [
                    'header_icon' => 'yes'
                ]
            ]
        );

        $repeater->add_control(
            'header_icon_position',
            [
                'label' => esc_html__('Media Position', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'label_block' => false,
                'default' => 'left',
                'options' => [
                    'left' => esc_html__('Left', 'king-addons'),
                    'right' => esc_html__('Right', 'king-addons'),
                    'top' => esc_html__('Top', 'king-addons'),
                    'bottom' => esc_html__('Bottom', 'king-addons'),
                ],
                'condition' => [
                    'header_icon' => 'yes'
                ]
            ]
        );

        $repeater->add_control(
            'choose_header_col_icon',
            [
                'label' => esc_html__('Select Icon', 'king-addons'),
                'type' => Controls_Manager::ICONS,
                'skin' => 'inline',
                'label_block' => false,
                'default' => [
                    'value' => 'fas fa-star',
                    'library' => 'solid',
                ],
                'condition' => [
                    'header_icon' => 'yes',
                    'header_icon_type' => 'icon',
                ]

            ]
        );

        $repeater->add_control(
            'header_col_img',
            [
                'label' => esc_html__('Image', 'king-addons'),
                'type' => Controls_Manager::MEDIA,
                'dynamic' => [
                    'active' => true,
                ],
                'condition' => [
                    'header_icon_type' => 'image'
                ]
            ]
        );

        $repeater->add_responsive_control(
            'header_col_img_size',
            [
                'label' => esc_html__('Image Size', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                    'px' => [
                        'min' => 0,
                        'max' => 500
                    ]
                ],
                'devices' => ['desktop', 'tablet', 'mobile'],
                'default' => [
                    'size' => 100,
                    'unit' => 'px'
                ],
                'desktop_default' => [
                    'size' => 100,
                    'unit' => '%',
                ],
                'tablet_default' => [
                    'size' => 100,
                    'unit' => '%',
                ],
                'mobile_default' => [
                    'size' => 100,
                    'unit' => '%',
                ],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .king-addons-data-table-th-img' => 'width: {{SIZE}}{{UNIT}} !important; height: auto !important;',
                ],
                'condition' => [
                    'header_icon_type' => 'image'
                ]
            ]
        );

        $repeater->add_control(
            'header_icon_color',
            [
                'label' => esc_html__('Icon Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#fff',
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} i' => 'color: {{VALUE}}',
                    '{{WRAPPER}} {{CURRENT_ITEM}} svg' => 'fill: {{VALUE}}'
                ],
                'condition' => [
                    'header_icon' => 'yes',
                    'header_icon_type' => 'icon'
                ]
            ]
        );

        $repeater->add_control(
            'header_th_color',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'separator' => 'before',
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}' => 'color: {{VALUE}}'
                ],
            ]
        );

        $repeater->add_control(
            'header_th_background_color',
            [
                'label' => esc_html__('Background Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}' => 'background-color: {{VALUE}} !important'
                ],
            ]
        );

        $repeater->add_control(
            'header_colspan',
            [
                'label' => esc_html__('Col Span', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'default' => 1,
                'min' => 1,
                'separator' => 'before'
            ]
        );

        $repeater->add_responsive_control(
            'th_individual_align',
            [
                'label' => esc_html__('Alignment', 'king-addons'),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'separator' => 'before',
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
                    '{{WRAPPER}} {{CURRENT_ITEM}}' => 'text-align: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_control(
            'table_header',
            [
                'label' => esc_html__('Repeater Table Header', 'king-addons'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'table_th' => esc_html__('TABLE HEADER 1', 'king-addons'),
                    ],
                    [
                        'table_th' => esc_html__('TABLE HEADER 2', 'king-addons'),
                    ],
                    [
                        'table_th' => esc_html__('TABLE HEADER 3', 'king-addons'),
                    ],
                    [
                        'table_th' => esc_html__('TABLE HEADER 4', 'king-addons'),
                    ],
                ],
                'title_field' => '{{{ table_th }}}',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_content',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Content', 'king-addons'),
                'condition' => [
                    'choose_table_type' => 'custom'
                ]
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'table_content_row_type',
            [
                'label' => esc_html__('Row Type', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'row',
                'label_block' => false,
                'options' => [
                    'row' => esc_html__('Row', 'king-addons'),
                    'col' => esc_html__('Column', 'king-addons'),
                ]
            ]
        );

        $repeater->add_control(
            'table_td',
            [
                'label' => esc_html__('Content', 'king-addons'),
                'type' => Controls_Manager::TEXTAREA,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => esc_html__('Content', 'king-addons'),
                'show_label' => true,
                'separator' => 'before',
                'condition' => [
                    'table_content_row_type' => 'col',
                ]
            ]
        );

        $repeater->add_control(
            'cell_link',
            [
                'label' => esc_html__('Content URL', 'king-addons'),
                'type' => Controls_Manager::URL,
                'dynamic' => [
                    'active' => true,
                ],
                'placeholder' => esc_html__('https://example.com', 'king-addons'),
                'show_external' => true,
                'condition' => [
                    'table_content_row_type' => 'col',
                ]
            ]
        );

        $repeater->add_responsive_control(
            'td_icon',
            [
                'label' => esc_html__('Media', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default' => 'no',
                'separator' => 'before',
                'condition' => [
                    'table_content_row_type' => 'col'
                ]
            ]
        );
        $repeater->add_control(
            'td_icon_type',
            [
                'label' => esc_html__('Media Type', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'label_block' => false,
                'default' => 'icon',
                'options' => [
                    'icon' => esc_html__('Icon', 'king-addons'),
                    'image' => esc_html__('Image', 'king-addons')
                ],
                'condition' => [
                    'td_icon' => 'yes'
                ]
            ]
        );

        $repeater->add_control(
            'td_icon_position',
            [
                'label' => esc_html__('Media Position', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'label_block' => false,
                'default' => 'left',
                'options' => [
                    'left' => esc_html__('Left', 'king-addons'),
                    'right' => esc_html__('Right', 'king-addons'),
                    'top' => esc_html__('Top', 'king-addons'),
                    'bottom' => esc_html__('Bottom', 'king-addons'),
                ],
                'condition' => [
                    'td_icon' => 'yes'
                ]
            ]
        );

        $repeater->add_control(
            'choose_td_icon',
            [
                'label' => esc_html__('Select Icon', 'king-addons'),
                'type' => Controls_Manager::ICONS,
                'skin' => 'inline',
                'label_block' => false,
                'default' => [
                    'value' => 'fas fa-star',
                    'library' => 'solid',
                ],
                'condition' => [
                    'td_icon' => 'yes',
                    'td_icon_type' => 'icon'
                ]

            ]
        );

        $repeater->add_control(
            'td_col_img',
            [
                'label' => esc_html__('Image', 'king-addons'),
                'type' => Controls_Manager::MEDIA,
                'dynamic' => [
                    'active' => true,
                ],
                'condition' => [
                    'td_icon' => 'yes',
                    'td_icon_type!' => ['none', 'icon']
                ]
            ]
        );

        $repeater->add_responsive_control(
            'td_col_img_size',
            [
                'label' => esc_html__('Image Size', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                    'px' => [
                        'min' => 0,
                        'max' => 500
                    ]
                ],
                'devices' => ['desktop', 'tablet', 'mobile'],
                'default' => [
                    'size' => 100,
                    'unit' => 'px'
                ],
                'desktop_default' => [
                    'size' => 100,
                    'unit' => '%',
                ],
                'tablet_default' => [
                    'size' => 100,
                    'unit' => '%',
                ],
                'mobile_default' => [
                    'size' => 100,
                    'unit' => '%',
                ],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} img' => 'width: {{SIZE}}{{UNIT}} !important; height: auto !important;',
                ],
                'condition' => [
                    'td_icon' => 'yes',
                    'td_icon_type!' => ['none', 'icon']
                ]
            ]
        );

        $repeater->add_responsive_control(
            'td_col_icon_size',
            [
                'label' => esc_html__('Icon Size', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [
                    'px', 'em', 'rem',
                ],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .king-addons-td-content-wrapper i:not(.fa-question-circle)' => 'font-size: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'td_icon' => 'yes',
                    'td_icon_type!' => ['none', 'image']
                ]
            ]
        );

        $repeater->add_control(
            'td_icon_color',
            [
                'label' => esc_html__('Icon Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#7A7A7A',
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} i' => 'color: {{VALUE}}',
                    '{{WRAPPER}} {{CURRENT_ITEM}} svg' => 'fill: {{VALUE}}'
                ],
                'condition' => [
                    'table_content_row_type' => 'col',
                    'td_icon' => 'yes',
                    'td_icon_type' => 'icon'
                ]
            ]
        );

        $repeater->add_control('content_tooltip', $this->add_repeater_args_content_tooltip());

        $repeater->add_control('content_tooltip_text', $this->add_repeater_args_content_tooltip_text());

        $repeater->add_control('content_tooltip_show_icon', $this->add_repeater_args_content_tooltip_show_icon());

        $repeater->add_control(
            'td_color',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'separator' => 'before',
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .king-addons-table-text' => 'color: {{VALUE}} !important'
                ],
            ]
        );

        $repeater->add_control(
            'td_background_color',
            [
                'label' => esc_html__('Background Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}' => 'background-color: {{VALUE}} !important'
                ],
            ]
        );

        $repeater->add_control(
            'td_background_color_hover',
            [
                'label' => esc_html__('Background Color (Hover)', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}:hover' => 'background-color: {{VALUE}} !important'
                ],
                'condition' => [
                    'table_content_row_type' => 'col'
                ]
            ]
        );

        $repeater->add_control(
            'table_content_row_colspan',
            [
                'label' => esc_html__('Col Span', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'default' => 1,
                'min' => 1,
                'label_block' => false,
                'separator' => 'before',
                'condition' => [
                    'table_content_row_type' => 'col'
                ]
            ]
        );

        $repeater->add_control(
            'table_content_row_rowspan',
            [
                'label' => esc_html__('Row Span', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'default' => 1,
                'min' => 1,
                'label_block' => false,
                'condition' => [
                    'table_content_row_type' => 'col'
                ]
            ]
        );

        $repeater->add_responsive_control(
            'td_individual_align',
            [
                'label' => esc_html__('Alignment', 'king-addons'),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'separator' => 'before',
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
                    '{{WRAPPER}} {{CURRENT_ITEM}}' => 'text-align: {{VALUE}} !important;',
                ],
            ]
        );

        /** @noinspection DuplicatedCode */
        $this->add_control(
            'table_content_rows',
            [
                'label' => esc_html__('Repeater Table Rows', 'king-addons'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    ['table_content_row_type' => 'row'],
                    [
                        'table_content_row_type' => 'col',
                        'table_td' => 'Content 1'
                    ],
                    [
                        'table_content_row_type' => 'col',
                        'table_td' => 'Content 2'
                    ],
                    [
                        'table_content_row_type' => 'col',
                        'table_td' => 'Content 3'
                    ],
                    [
                        'table_content_row_type' => 'col',
                        'table_td' => 'Content 4'
                    ],
                    ['table_content_row_type' => 'row'],
                    [
                        'table_content_row_type' => 'col',
                        'table_td' => 'Content 1'
                    ],
                    [
                        'table_content_row_type' => 'col',
                        'table_td' => 'Content 2'
                    ],
                    [
                        'table_content_row_type' => 'col',
                        'table_td' => 'Content 3'
                    ],
                    [
                        'table_content_row_type' => 'col',
                        'table_td' => 'Content 4'
                    ],
                ],
                'title_field' => '{{table_content_row_type}}::{{table_td}}',
            ]
        );

        $this->end_controls_section();

        Core::renderProFeaturesSection($this, '', Controls_Manager::RAW_HTML, 'data-table', [
            'Import Table Data from CSV File Upload or URL',
            'Enable Live Search for Tables',
            'Enable Table Sorting Option',
            'Enable Table Pagination. Divide Table Items by Pages',
            'Show/Hide Export Table Data Buttons',
            'Enable Tooltips on Each Cell'
        ]);

        $this->start_controls_section(
            'style_section',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('General', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'table_responsive_width',
            [
                'label' => esc_html__('Table Min Width', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'render_type' => 'template',
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1500
                    ]
                ],

                'default' => [
                    'size' => 600,
                    'unit' => 'px'
                ],


                'selectors' => [
                    '{{WRAPPER}} .king-addons-table-container .king-addons-data-table' => 'min-width: {{SIZE}}{{UNIT}};',


                    '{{WRAPPER}} .king-addons-table-inner-container' => 'width: 100%;',
                    '{{WRAPPER}} .king-addons-data-table' => 'width: 100%;',
                ],

            ]
        );

        $this->add_control(
            'all_border_type',
            [
                'label' => esc_html__('Border', 'king-addons'),
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
                    '{{WRAPPER}} .king-addons-table-inner-container' => 'border-style: {{VALUE}};',
                    '{{WRAPPER}} th.king-addons-table-th' => 'border-style: {{VALUE}};',
                    '{{WRAPPER}} th.king-addons-table-th-pag' => 'border-style: {{VALUE}};',
                    '{{WRAPPER}} td.king-addons-table-td' => 'border-style: {{VALUE}};',
                    '{{WRAPPER}} td.king-addons-table-td-pag' => 'border-style: {{VALUE}};'
                ],
                'separator' => 'before'
            ]
        );

        $this->add_control(
            'all_border_color',
            [
                'label' => esc_html__('Border Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#E4E4E4',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-table-inner-container' => 'border-color: {{VALUE}};',
                    '{{WRAPPER}} th.king-addons-table-th' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} th.king-addons-table-th-pag' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} td.king-addons-table-td' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} td.king-addons-table-td-pag' => 'border-color: {{VALUE}}'
                ],
                'condition' => [
                    'all_border_type!' => 'none',
                ]
            ]
        );

        $this->add_control(
            'all_border_width',
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
                    '{{WRAPPER}} .king-addons-table-inner-container' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} th.king-addons-table-th' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} th.king-addons-table-th-pag' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} td.king-addons-table-td' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} td.king-addons-table-td-pag' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'all_border_type!' => 'none',
                ]
            ]
        );

        $this->add_responsive_control(
            'header_border_radius',
            [
                'label' => esc_html__('Border Radius', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 0,
                    'unit' => 'px'
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 200,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-table-inner-container' => 'border-radius: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} table' => 'border-radius: {{SIZE}}{{UNIT}};',


                ]
            ]
        );

        $this->add_control_export_buttons_distance();

        $this->add_control_table_search_input_padding();

        $this->add_control(
            'hover_transition',
            [
                'label' => esc_html__('Transition Duration (seconds)', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'default' => 0.3,
                'min' => 0,
                'max' => 5,
                'step' => 0.1,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-table-th' => '-webkit-transition-duration:  {{VALUE}}s; transition-duration:  {{VALUE}}s; transition-property: background-color color font-size',
                    '{{WRAPPER}} .king-addons-table-th-pag' => '-webkit-transition-duration:  {{VALUE}}s; transition-duration:  {{VALUE}}s; transition-property: background-color color font-size',
                    '{{WRAPPER}} .king-addons-table-th i' => '-webkit-transition-duration:  {{VALUE}}s; transition-duration:  {{VALUE}}s; transition-property: background-color color font-size',
                    '{{WRAPPER}} .king-addons-table-th svg' => '-webkit-transition-duration:  {{VALUE}}s; transition-duration:  {{VALUE}}s; transition-property: background-color color font-size',
                    '{{WRAPPER}} .king-addons-table-td' => '-webkit-transition-duration:  {{VALUE}}s; transition-duration:  {{VALUE}}s; transition-property: background-color color font-size',
                    '{{WRAPPER}} .king-addons-table-td-pag' => '-webkit-transition-duration:  {{VALUE}}s; transition-duration:  {{VALUE}}s; transition-property: background-color color font-size',
                    '{{WRAPPER}} .king-addons-table-td i' => '-webkit-transition-duration:  {{VALUE}}s; transition-duration:  {{VALUE}}s; transition-property: background-color color font-size',
                    '{{WRAPPER}} .king-addons-table-td svg' => '-webkit-transition-duration:  {{VALUE}}s; transition-duration:  {{VALUE}}s; transition-property: background-color color font-size',
                    '{{WRAPPER}} .king-addons-table-text' => '-webkit-transition-duration:  {{VALUE}}s; transition-duration:  {{VALUE}}s; transition-property: background-color color font-size'
                ],
                'separator' => 'before'
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'header_style',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Header', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE
            ]
        );

        $this->start_controls_tabs(
            'style_tabs'
        );

        $this->start_controls_tab(
            'style_normal_tab',
            [
                'label' => esc_html__('Normal', 'king-addons'),
            ]
        );

        $this->add_control(
            'th_color',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#FFFFFF',
                'selectors' => [
                    '{{WRAPPER}} th' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'th_bg_color',
            [
                'label' => esc_html__('Background Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#5B03FF',
                'selectors' => [
                    '{{WRAPPER}} tr th' => 'background-color: {{VALUE}}',
                ],
                'separator' => 'after',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'style_hover_tab',
            [
                'label' => esc_html__('Hover', 'king-addons'),
            ]
        );

        $this->add_control(
            'th_color_hover',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#FFFFFF',
                'selectors' => [
                    '{{WRAPPER}} th:hover' => 'color: {{VALUE}}; cursor: pointer;',
                ],
            ]
        );

        $this->add_control(
            'th_bg_color_hover',
            [
                'label' => esc_html__('Background Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#5B03FF',
                'selectors' => [
                    '{{WRAPPER}} tr th:hover' => 'background-color: {{VALUE}}',
                ],
                'separator' => 'after',
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'th_typography',
                'selector' => '{{WRAPPER}} th',
                'fields_options' => [
                    'typography' => [
                        'default' => 'custom',
                    ],
                    'font_weight' => [
                        'default' => '400',
                    ]
                ],
            ]
        );

        $this->add_responsive_control(
            'header_icon_size',
            [
                'label' => esc_html__('Icon Size', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 70,
                    ],
                ],
                'default' => [
                    'size' => 15,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-data-table thead i' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .king-addons-data-table thead svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}',
                ],
                'separator' => 'before'
            ]
        );

        $this->add_responsive_control(
            'header_sorting_icon_size',
            [
                'label' => esc_html__('Sorting Icon Size', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 70,
                    ],
                ],
                'default' => [
                    'size' => 12,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-data-table thead .king-addons-sorting-icon i' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .king-addons-data-table thead .king-addons-sorting-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'enable_table_sorting' => 'yes'
                ]
            ]
        );

        $this->add_responsive_control(
            'header_padding',
            [
                'label' => esc_html__('Padding', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'default' => [
                    'top' => 10,
                    'right' => 10,
                    'bottom' => 10,
                    'left' => 10,
                ],
                'selectors' => [
                    '{{WRAPPER}} th' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before'
            ]
        );

        $this->add_responsive_control(
            'header_image_space',
            [
                'label' => esc_html__('Image Margin', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'range' => [


                    'default' => [
                        'top' => 0,
                        'right' => 0,
                        'bottom' => 0,
                        'left' => 0,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-data-table th img' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
            ]
        );

        $this->add_responsive_control(
            'header_icon_space',
            [
                'label' => esc_html__('Icon Margin', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'range' => [


                    'default' => [
                        'top' => 0,
                        'right' => 0,
                        'bottom' => 0,
                        'left' => 0,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-data-table th i' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .king-addons-data-table th svg' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
            ]
        );

        $this->add_responsive_control(
            'th_align',
            [
                'label' => esc_html__('Alignment', 'king-addons'),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'default' => 'left',
                'separator' => 'before',
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
                'prefix_class' => 'king-addons-table-align-items-',
                'selectors' => [
                    '{{WRAPPER}} th:not(".king-addons-table-th-pag")' => 'text-align: {{VALUE}};',
                    '{{WRAPPER}} .king-addons-th-inner-cont' => 'text-align: {{VALUE}};',
                    '{{WRAPPER}} .king-addons-flex-column span' => 'text-align: {{VALUE}};',
                    '{{WRAPPER}} .king-addons-flex-column-reverse span' => 'text-align: {{VALUE}};',
                    '{{WRAPPER}} .king-addons-table-th' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'content_styles',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Content', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE
            ]
        );

        $this->start_controls_tabs(
            'cells_style_tabs'
        );

        $this->start_controls_tab(
            'cells_style_normal_tab',
            [
                'label' => esc_html__('Normal', 'king-addons'),
            ]
        );

        $this->add_control(
            'odd_cell_styles',
            [
                'label' => esc_html__('Odd Rows', 'king-addons'),
                'type' => Controls_Manager::HEADING
            ]
        );

        $this->add_control(
            'odd_row_td_color',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#7A7A7A',
                'selectors' => [


                    '{{WRAPPER}} tbody tr:nth-child(odd) td.king-addons-table-text' => 'color: {{VALUE}}',
                    '{{WRAPPER}} tbody tr:nth-child(odd) td a' => 'color: {{VALUE}} !important',
                    '{{WRAPPER}} tbody tr:nth-child(odd) td span' => 'color: {{VALUE}} !important',
                    '{{WRAPPER}} tbody tr:nth-child(odd) td' => 'color: {{VALUE}}'
                ],
            ]
        );

        $this->add_control(
            'odd_row_td_bg_color',
            [
                'label' => esc_html__('Background Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#FFFFFF',
                'selectors' => [

                    '{{WRAPPER}} tbody tr:nth-child(odd) td' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'even_cell_styles',
            [
                'label' => esc_html__('Even Rows', 'king-addons'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'even_row_td_color',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#7A7A7A',
                'selectors' => [


                    '{{WRAPPER}} tbody tr:nth-child(even) td a .king-addons-table-text' => 'color: {{VALUE}}',
                    '{{WRAPPER}} tbody tr:nth-child(even) td.king-addons-table-text' => 'color: {{VALUE}}',
                    '{{WRAPPER}} tbody tr:nth-child(even) td.king-addons-table-td-pag' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'even_row_td_bg_color',
            [
                'label' => esc_html__('Background Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#F3F3F3',
                'selectors' => [

                    '{{WRAPPER}} tbody tr:nth-child(even) td' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'cells_style_hover_tab',
            [
                'label' => esc_html__('Hover', 'king-addons'),
            ]
        );

        $this->add_control(
            'odd_cell_hover_styles',
            [
                'label' => esc_html__('Odd Rows', 'king-addons'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'odd_row_td_color_hover',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#7A7A7A',
                'selectors' => [


                    '{{WRAPPER}} tbody tr:nth-child(odd) td:hover a' => 'color: {{VALUE}} !important',
                    '{{WRAPPER}} tbody tr:nth-child(odd) td:hover span' => 'color: {{VALUE}} !important',
                    '{{WRAPPER}} tbody tr:nth-child(odd) td:hover.king-addons-table-text' => 'color: {{VALUE}} !important',
                    '{{WRAPPER}} tbody tr:nth-child(odd) td:hover i' => 'color: {{VALUE}}',
                    '{{WRAPPER}} tbody tr:nth-child(odd) td:hover svg' => 'fill: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'odd_row_td_bg_color_hover',
            [
                'label' => esc_html__('Background Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [

                    '{{WRAPPER}} tbody tr:nth-child(odd):hover td' => 'background-color: {{VALUE}}; cursor: pointer;',
                ],
            ]
        );

        $this->add_control(
            'even_cell_hover_styles',
            [
                'label' => esc_html__('Even Rows', 'king-addons'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'even_row_td_color_hover',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#7A7A7A',


                'selectors' => [
                    '{{WRAPPER}} tbody tr:nth-child(even) td:hover.king-addons-table-text' => 'color: {{VALUE}}',
                    '{{WRAPPER}} tbody tr:nth-child(even) td:hover a .king-addons-table-text' => 'color: {{VALUE}} !important',
                    '{{WRAPPER}} tbody tr:nth-child(even) td:hover i' => 'color: {{VALUE}}',
                    '{{WRAPPER}} tbody tr:nth-child(even) td:hover svg' => 'fill: {{VALUE}}'
                ],
            ]
        );

        $this->add_control(
            'even_row_td_bg_color_hover',
            [
                'label' => esc_html__('Background Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [

                    '{{WRAPPER}} tbody tr:nth-child(even):hover td' => 'background-color: {{VALUE}}; cursor: pointer;',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_control_active_td_bg_color();

        $this->add_control(
            'typograpphy_divider',
            [
                'type' => Controls_Manager::DIVIDER,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'td_typography',
                'selector' => '{{WRAPPER}} td, {{WRAPPER}} i.fa-question-circle',
                'fields_options' => [
                    'typography' => [
                        'default' => 'custom',
                    ],
                    'font_weight' => [
                        'default' => '400',
                    ]
                ],
            ]
        );

        $this->add_responsive_control(
            'tbody_icon_size',
            [
                'label' => esc_html__('Icon Size', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'separator' => 'before',
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 70,
                    ],
                ],
                'default' => [
                    'size' => 15,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-data-table tbody i:not(.fa-question-circle)' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .king-addons-data-table tbody svg' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .king-addons-data-table tbody span:has(>svg)' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};'
                ],
            ]
        );

        $this->add_responsive_control(
            'tbody_image_size',
            [
                'label' => esc_html__('Image Size', 'king-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 150,
                    ],
                ],
                'default' => [
                    'size' => 50,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-data-table-th-img' => 'width: {{SIZE}}{{UNIT}}; height: auto;',
                ],
            ]
        );

        $this->add_responsive_control(
            'td_padding',
            [
                'label' => esc_html__('Padding', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'separator' => 'before',
                'default' => [
                    'top' => 10,
                    'right' => 10,
                    'bottom' => 10,
                    'left' => 10,
                ],
                'selectors' => [
                    '{{WRAPPER}} td' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'tbody_image_border_radius',
            [
                'label' => esc_html__('Image Border Radius', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'default' => [
                    'top' => 2,
                    'right' => 2,
                    'bottom' => 2,
                    'left' => 2,
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-data-table-th-img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'td_img_space',
            [
                'label' => esc_html__('Image Margin', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'range' => [


                    'default' => [
                        'top' => 0,
                        'right' => 0,
                        'bottom' => 0,
                        'left' => 0,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-data-table td img' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
            ]
        );

        $this->add_responsive_control(
            'td_icon_space',
            [
                'label' => esc_html__('Icon Margin', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'range' => [


                    'default' => [
                        'top' => 0,
                        'right' => 0,
                        'bottom' => 0,
                        'left' => 0,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-data-table td i' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .king-addons-data-table td svg' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
            ]
        );

        $this->add_responsive_control(
            'td_align',
            [
                'label' => esc_html__('Alignment', 'king-addons'),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'default' => 'left',
                'separator' => 'before',
                'options' => [
                    'left' => [
                        'title' => esc_html__('Left', 'king-addons'),
                        'icon' => ' eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'king-addons'),
                        'icon' => ' eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__('Right', 'king-addons'),
                        'icon' => ' eicon-text-align-right',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} td:not(".king-addons-table-td-pag")' => 'text-align: {{VALUE}};',
                    '{{WRAPPER}} .king-addons-td-content-wrapper span' => 'text-align: {{VALUE}};',
                    '{{WRAPPER}} .king-addons-table-td' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->add_section_export_buttons_styles();

        $this->add_section_pagination_styles();

        $this->add_control_stack_content_tooltip_section();

    }

    public function render_content_tooltip($item)
    {
    }

    public function render_tooltip_icon($item)
    {
    }

    public function render_custom_pagination($settings, $countRows)
    {
    }

    protected function render_csv_data($url, $sorting_icon, $settings)
    {
        // Extract path info just once
        $parsed_url = pathinfo($url);
        $extension = $parsed_url['extension'] ?? '';
        $dirname = $parsed_url['dirname'] ?? '';

        ob_start();

        // Check if it's a CSV or Google Sheets URL
        if ($extension === 'csv' || str_contains($dirname, 'docs.google.com/spreadsheets')) {
            // If Google Sheets, override URL with the one from settings
            if (str_contains($dirname, 'docs.google.com/spreadsheets')) {
                $url = $settings['table_insert_url']['url'];
            }
            $this->king_addons_parse_csv_to_table($url, $settings, $sorting_icon);
        } else {
            echo '<p class="king-addons-no-csv-file-found">' . esc_html__('Please provide a CSV file.', 'king-addons') . '</p>';
        }

        return ob_get_clean();
    }

    protected function king_addons_parse_csv_to_table($filename, $settings, $sorting_icon)
    {
        $allowed_html = [
            'a' => [
                'href' => [],
                'title' => [],
                'target' => [],
            ],
            'b' => [],
            'strong' => [],
            'i' => [],
            'em' => [],
            'p' => [],
            'br' => [],
            'ul' => [],
            'ol' => [],
            'li' => [],
            'span' => [],
            'div' => ['class' => []],
            'img' => [
                'src' => [],
                'alt' => [],
                'width' => [],
                'height' => [],
            ],
        ];

        $handle = fopen($filename, 'r');
        $delimiter = $this->detect_csv_delimiter($filename);

        echo '<table class="king-addons-append-to-scope king-addons-data-table">';

        // Optional CSV header row
        if ('yes' === $settings['display_header']) {
            $csv_header = fgetcsv($handle, 0, $delimiter);
            echo '<thead><tr class="king-addons-table-head-row king-addons-table-row">';
            foreach ((array)$csv_header as $header_cell) {
                echo '<th class="king-addons-table-th king-addons-table-text">'
                    . wp_kses($header_cell, $allowed_html)
                    . $sorting_icon
                    . '</th>';
            }
            echo '</tr></thead>';
        }

        echo '<tbody>';

        // CSV body rows
        $row_count = 0;
        while ($row = fgetcsv($handle, 0, $delimiter)) {
            $row_count++;
            $odd_even_class = ($row_count % 2 === 0) ? 'king-addons-even' : 'king-addons-odd';
            echo '<tr class="king-addons-table-row ' . esc_attr($odd_even_class) . '">';
            foreach ($row as $cell) {
                echo '<td class="king-addons-table-td king-addons-table-text">'
                    . wp_kses($cell, $allowed_html)
                    . '</td>';
            }
            echo '</tr>';
        }
        echo '</tbody></table>';

        // Close any extra divs (if needed by your layout)
        echo '</div></div>';

        // Custom pagination
        if ('yes' === $settings['enable_custom_pagination']) {
            $this->render_custom_pagination($settings, $row_count);
        }

        fclose($handle);
    }

    protected function detect_csv_delimiter($filename)
    {
        $delimiters = [',', ';'];
        $best_delimiter = ',';
        $max_count = 0;

        $handle = fopen($filename, 'r');
        $first_line = fgets($handle);
        fclose($handle);

        foreach ($delimiters as $delimiter) {
            $count = count(str_getcsv($first_line, $delimiter));
            if ($count > $max_count) {
                $max_count = $count;
                $best_delimiter = $delimiter;
            }
        }

        return $best_delimiter;
    }

    public function render_th_icon($item)
    {
        ob_start();
        Icons_Manager::render_icon($item['choose_header_col_icon'], ['aria-hidden' => 'true']);
        return ob_get_clean();
    }

    public function render_th_icon_or_image($item, $i)
    {
        $header_icon = '';

        if ($item['header_icon'] === 'yes' && $item['header_icon_type'] === 'icon') {
            $header_icon = '<span style="display: inline-block; vertical-align: middle;">'
                . $this->render_th_icon($item)
                . '</span>';
        }

        if ($item['header_icon'] === 'yes' && $item['header_icon_type'] === 'image') {
            $this->add_render_attribute('king_addons_table_th_img' . $i, [
                'src' => esc_url($item['header_col_img']['url']),
                'class' => 'king-addons-data-table-th-img',
                'alt' => esc_attr(get_post_meta($item['header_col_img']['id'], '_wp_attachment_image_alt', true)),
            ]);
            $header_icon = '<img ' . $this->get_render_attribute_string('king_addons_table_th_img' . $i) . '>';
        }

        echo $header_icon;
    }

    public function render_td_icon($table_td, $j)
    {
        ob_start();
        Icons_Manager::render_icon($table_td[$j]['icon_item'], ['aria-hidden' => 'true']);
        return ob_get_clean();
    }

    public function render_td_icon_or_image($table_td, $j)
    {
        $tbody_icon = '';

        if ($table_td[$j]['icon'] === 'yes' && $table_td[$j]['icon_type'] === 'icon') {
            $tbody_icon = '<span style="display: inline-block; vertical-align: middle;">'
                . $this->render_td_icon($table_td, $j)
                . '</span>';
        }

        if ($table_td[$j]['icon'] === 'yes' && $table_td[$j]['icon_type'] === 'image') {
            $this->add_render_attribute('king_addons_table_td_img' . esc_attr($j), [
                'src' => esc_url($table_td[$j]['col_img']['url']),
                'class' => 'king-addons-data-table-th-img',
                'alt' => esc_attr(get_post_meta($table_td[$j]['col_img']['id'], '_wp_attachment_image_alt', true)),
            ]);
            $tbody_icon = '<img ' . $this->get_render_attribute_string('king_addons_table_td_img' . esc_attr($j)) . '>';
        }

        echo $tbody_icon;
    }

    public function render_search_export()
    {
        // Intentionally empty (as in original code)
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();

        // Additional variables
        $sorting_icon = (
            'yes' === $settings['enable_table_sorting']
            && king_addons_freemius()->can_use_premium_code__premium_only()
        ) ? '<span class="king-addons-sorting-icon"><i class="fas fa-sort"></i></span>' : '';

        $this->add_render_attribute('king_addons_table_inner_container_attributes', [
            'class' => [
                'king-addons-table-inner-container',
                ('yes' === $settings['enable_custom_pagination'] ? 'king-addons-hide-table-before-arrange' : ''),
            ],
            'data-table-sorting' => $settings['enable_table_sorting'],
            'data-custom-pagination' => $settings['enable_custom_pagination'],
            'data-row-pagination' => $settings['enable_row_pagination'],
            'data-entry-info' => king_addons_freemius()->can_use_premium_code__premium_only()
                ? $settings['enable_entry_info']
                : 'no',
            'data-rows-per-page' => $settings['table_items_per_page'] ?? '',
        ]);

        ?>
        <div class="king-addons-table-container">
    <div <?php echo $this->get_render_attribute_string('king_addons_table_inner_container_attributes'); ?>>
        <?php
        $this->render_search_export();

        // If CSV file from media library
        if (isset($settings['choose_csv_type']) && 'file' === $settings['choose_csv_type']) {
            echo $this->render_csv_data(
                $settings['table_upload_csv']['url'],
                $sorting_icon,
                $settings
            );
        } // If external CSV/Google Sheets URL
        elseif (isset($settings['choose_csv_type']) && 'url' === $settings['choose_csv_type']) {
            echo $this->render_csv_data(
                esc_url($settings['table_insert_url']['url']),
                $sorting_icon,
                $settings
            );
        } // Else build table from repeater rows
        else {
            $table_tr = [];
            $table_td = [];
            $countRows = 0;

            // Build arrays for rows and columns
            foreach ($settings['table_content_rows'] as $content_row) {
                $countRows++;
                $oddEven = ($countRows % 2 === 0) ? 'king-addons-even' : 'king-addons-odd';
                $row_id = uniqid();

                if ($content_row['table_content_row_type'] === 'row') {
                    $table_tr[] = [
                        'id' => $row_id,
                        'type' => $content_row['table_content_row_type'],
                        'class' => [
                            'king-addons-table-body-row',
                            'king-addons-table-row',
                            'elementor-repeater-item-' . esc_attr($content_row['_id']),
                            esc_attr($oddEven),
                        ],
                    ];
                } elseif ($content_row['table_content_row_type'] === 'col') {
                    $last_key = array_key_last($table_tr);
                    $table_td[] = [
                        'row_id' => $table_tr[$last_key]['id'] ?? '',
                        'type' => $content_row['table_content_row_type'],
                        'content' => $content_row['table_td'],
                        'colspan' => $content_row['table_content_row_colspan'],
                        'rowspan' => $content_row['table_content_row_rowspan'],
                        'link' => $content_row['cell_link'],
                        'external' => $content_row['cell_link']['is_external'] ? '_blank' : '_self',
                        'icon_type' => $content_row['td_icon_type'],
                        'icon' => $content_row['td_icon'],
                        'icon_position' => $content_row['td_icon_position'],
                        'icon_item' => $content_row['choose_td_icon'],
                        'col_img' => $content_row['td_col_img'],
                        'class' => [
                            'elementor-repeater-item-' . esc_attr($content_row['_id']),
                            'king-addons-table-td',
                        ],
                        'content_tooltip' => $content_row['content_tooltip'],
                        'content_tooltip_text' => $content_row['content_tooltip_text'],
                        'content_tooltip_show_icon' => $content_row['content_tooltip_show_icon'],
                    ];
                }
            }
            ?>

            <table class="king-addons-data-table" id="king-addons-data-table">
                <?php if (!empty($settings['table_header'])) : ?>
                    <thead>
                    <tr class="king-addons-table-head-row king-addons-table-row">
                        <?php
                        $i = 0;
                        foreach ($settings['table_header'] as $item) {
                            $this->add_render_attribute('th_class_' . $i, [
                                'class' => [
                                    'king-addons-table-th',
                                    'elementor-repeater-item-' . esc_attr($item['_id']),
                                ],
                                'colspan' => $item['header_colspan'],
                            ]);
                            $this->add_render_attribute('th_inner_class_' . $i, [
                                'class' => (
                                $item['header_icon_position'] === 'top'
                                    ? 'king-addons-flex-column-reverse'
                                    : (
                                $item['header_icon_position'] === 'bottom'
                                    ? 'king-addons-flex-column'
                                    : ''
                                )
                                ),
                            ]);
                            ?>
                            <th <?php echo $this->get_render_attribute_string('th_class_' . $i); ?>>
                                <div <?php echo $this->get_render_attribute_string('th_inner_class_' . $i); ?>>
                                    <?php
                                    if ($item['header_icon'] === 'yes' && $item['header_icon_position'] === 'left') {
                                        $this->render_th_icon_or_image($item, $i);
                                    }
                                    if ('' !== $item['table_th']) {
                                        echo '<span class="king-addons-table-text">'
                                            . esc_html($item['table_th'])
                                            . '</span>';
                                    }
                                    if ($item['header_icon'] === 'yes' && $item['header_icon_position'] === 'right') {
                                        $this->render_th_icon_or_image($item, $i);
                                    }
                                    echo $sorting_icon;

                                    if ($item['header_icon'] === 'yes' && in_array($item['header_icon_position'], ['top', 'bottom'], true)) {
                                        $this->render_th_icon_or_image($item, $i);
                                    }
                                    echo $sorting_icon;
                                    ?>
                                </div>
                            </th>
                            <?php
                            $i++;
                        }
                        ?>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $total_tr = count($table_tr);
                    for ($i = 0; $i < $total_tr; $i++) {
                        $this->add_render_attribute('table_row_attributes_' . $i, [
                            'class' => $table_tr[$i]['class'],
                        ]);
                        ?>
                        <tr <?php echo $this->get_render_attribute_string('table_row_attributes_' . $i); ?>>
                            <?php
                            $total_td = count($table_td);
                            for ($j = 0; $j < $total_td; $j++) {
                                if ($table_tr[$i]['id'] === $table_td[$j]['row_id']) {
                                    $this->add_render_attribute('tbody_td_attributes_' . $i . '_' . $j, [
                                        'colspan' => $table_td[$j]['colspan'] > 1 ? $table_td[$j]['colspan'] : '',
                                        'rowspan' => $table_td[$j]['rowspan'] > 1 ? $table_td[$j]['rowspan'] : '',
                                        'class' => $table_td[$j]['class'],
                                    ]);
                                    ?>
                                    <td <?php echo $this->get_render_attribute_string('tbody_td_attributes_' . $i . '_' . $j); ?>>
                                        <div class="king-addons-td-content-wrapper <?php
                                        echo esc_attr(
                                            ($table_td[$j]['icon_position'] === 'top')
                                                ? 'king-addons-flex-column'
                                                : (
                                            ($table_td[$j]['icon_position'] === 'bottom')
                                                ? 'king-addons-flex-column-reverse'
                                                : ''
                                            )
                                        );
                                        ?>">
                                            <?php
                                            if ($table_td[$j]['icon'] === 'yes'
                                                && in_array($table_td[$j]['icon_position'], ['left', 'top', 'bottom'], true)
                                            ) {
                                                $this->render_td_icon_or_image($table_td, $j);
                                            }

                                            if ('' !== $table_td[$j]['content']) {
                                                if ('' !== $table_td[$j]['link']['url']) {
                                                    echo '<a href="' . esc_url($table_td[$j]['link']['url']) . '" target="' . esc_attr($table_td[$j]['external']) . '">';
                                                } else {
                                                    echo '<span>';
                                                }

                                                echo '<span class="king-addons-table-text">';
                                                echo wp_kses_post($table_td[$j]['content']);
                                                // Render tooltip icon/more info if needed
                                                $this->render_tooltip_icon($table_td[$j]);
                                                $this->render_content_tooltip($table_td[$j]);
                                                echo '</span>';

                                                if ('' !== $table_td[$j]['link']['url']) {
                                                    echo '</a>';
                                                } else {
                                                    echo '</span>';
                                                }
                                            }

                                            if ($table_td[$j]['icon'] === 'yes' && $table_td[$j]['icon_position'] === 'right') {
                                                $this->render_td_icon_or_image($table_td, $j);
                                            }
                                            ?>
                                        </div>
                                    </td>
                                    <?php
                                }
                            } // end for td
                            ?>
                        </tr>
                        <?php
                    } // end for tr
                    ?>
                    </tbody>
                <?php endif; ?>
            </table>
            </div> <!-- .king-addons-table-inner-container -->
            </div> <!-- .king-addons-table-container -->
            <?php
            if ('yes' === $settings['enable_custom_pagination']) {
                $this->render_custom_pagination($settings, null);
            }
        }
    }
}