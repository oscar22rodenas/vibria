<?php

namespace King_Addons;

use Elementor\Controls_Manager;
use Elementor\Widget_Base;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class Test extends Widget_Base
{
    public function get_name(): string
    {
        return 'king-addons-test';
    }

    public function get_title(): string
    {
        return esc_html__('Test', 'king-addons');
    }

    public function get_icon(): string
    {
        return 'king-addons-icon king-addons-test';
    }

    public function get_script_depends(): array
    {
        return [KING_ADDONS_ASSETS_UNIQUE_KEY . '-libfolder-libfile', KING_ADDONS_ASSETS_UNIQUE_KEY . '-test-script'];
    }

    public function get_style_depends(): array
    {
        return [KING_ADDONS_ASSETS_UNIQUE_KEY . '-libfolder-libfile', KING_ADDONS_ASSETS_UNIQUE_KEY . '-test-style'];
    }

    public function get_categories(): array
    {
        return ['king-addons'];
    }

    public function get_keywords(): array
    {
        return ['test', 'king addons', 'king', 'addons', 'kingaddons', 'king-addons'];
    }

    public function get_custom_help_url(): string
    {
        return 'https://kingaddons.com/elementor/test/';
    }

    protected function register_controls(): void
    {

        $this->start_controls_section(
            'king_addons_test_section',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Test', 'king-addons'),
                'tab' => Controls_Manager::TAB_CONTENT
            ]
        );


        $this->end_controls_section();
    }

    protected function render(): void
    {
        $settings = $this->get_settings();
        $id = $this->get_id();


    }
}