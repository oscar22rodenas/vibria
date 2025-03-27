<?php /** @noinspection PhpUnused, DuplicatedCode, SpellCheckingInspection */

namespace King_Addons;

use DOMDocument;
use Elementor\Controls_Manager;
use Elementor\Plugin;
use Elementor\Widget_Base;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class Global_Section_Container extends Widget_Base
{
    public function get_name(): string
    {
        return 'king-addons-global-section-container';
    }

    public function get_title(): string
    {
        return esc_html__('Global Section & Container', 'king-addons');
    }

    public function get_icon(): string
    {
        return 'king-addons-icon king-addons-global-section-container';
    }

    public function get_categories(): array
    {
        return ['king-addons'];
    }

    public function get_keywords(): array
    {
        return ['content', 'template', 'templates', 'container', 'section', 'column', 'widget', 'module', 'connect',
            'global widget', 'global', 'reusable', 'across', 'multiple', 'multi', 'page', 'pages',
            'king', 'addons', 'kingaddons', 'king-addons', 'off canvas', 'embed'];
    }

    public function get_custom_help_url(): string
    {
        return 'https://kingaddons.com/elementor/global-section-container/';
    }

    protected function register_controls(): void
    {
        $this->start_controls_section(
            'kng_global_section_container_general',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('General', 'king-addons'),
            ]
        );

        $this->add_control(
            'kng_global_section_container_template',
            [
                'label' => esc_html__('Select Template', 'king-addons'),
                'type' => 'king-addons-ajax-select2',
                'options' => 'ajaxselect2/getElementorTemplates',
                'label_block' => true,
            ]
        );

        $this->add_control(
            'kng_global_section_container_alert_info',
            [
                'type' => Controls_Manager::ALERT,
                'alert_type' => 'info',
                'heading' => esc_html__('Note for Entrance Animations in Editor mode', 'king-addons'),
                'content' => esc_html__('If any content within the global template has Entrance Animations configured in the Advanced -> Motion Effects tab, it may not be displayed after selecting the template in Editor mode (Elementor editor). This occurs because the Entrance Animations are triggered immediately after the page loads. To address this issue, try saving your changes and reloading this editor page; following this, the global template should fully appear in most cases. The problem does not affect the appearance of the live website and is only present in the editor.', 'king-addons'),
            ]
        );

        $this->end_controls_section();
    }

    public function getOffCanvasTemplate($template_id): ?string
    {
        if (empty($template_id)) {
            return null;
        }

        $type = get_post_meta(get_the_ID(), '_king_addons_template_type', true);
        $has_css = 'internal' === get_option('elementor_css_print_method') || '' !== $type;

        return Plugin::instance()->frontend->get_builder_content_for_display($template_id, $has_css);
    }

    protected function render(): void
    {
        $settings = $this->get_settings_for_display();
        if (!empty($settings['kng_global_section_container_template'])) {
            $html = $this->getOffCanvasTemplate(esc_html($settings['kng_global_section_container_template']));
            $dom = new DOMDocument;
            libxml_use_internal_errors(true); // Disable libxml errors
            $dom->loadHTML($html);
            libxml_clear_errors();

            $tags = [];
            $this->getTagsAndAttributes($dom->documentElement, $tags);

            echo wp_kses($html, $tags);
        } else {
            echo '<p>' . esc_html__('Please select a template', 'king-addons') . '</p>';
        }
    }

    public function getTagsAndAttributes($node, &$tags): void
    {
        if ($node->nodeType == XML_ELEMENT_NODE) {
            $tagName = $node->nodeName;
            if (!isset($tags[$tagName])) {
                $tags[$tagName] = [];
            }

            foreach ($node->attributes as $attr) {
                if (!isset($tags[$tagName][$attr->nodeName])) {
                    $tags[$tagName][$attr->nodeName] = true;
                }
            }
        }

        foreach ($node->childNodes as $child) {
            $this->getTagsAndAttributes($child, $tags);
        }
    }
}