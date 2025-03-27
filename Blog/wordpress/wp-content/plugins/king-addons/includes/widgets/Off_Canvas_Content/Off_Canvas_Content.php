<?php /** @noinspection SpellCheckingInspection, DuplicatedCode, PhpUnused */

namespace King_Addons;

use DOMDocument;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;
use Elementor\Plugin;
use Elementor\Widget_Base;

/** @noinspection SpellCheckingInspection */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class Off_Canvas_Content extends Widget_Base
{
    public function get_name(): string
    {
        return 'king-addons-off-canvas-content';
    }

    public function get_title(): string
    {
        return esc_html__('Off-Canvas Content', 'king-addons');
    }

    public function get_icon(): string
    {
        /** @noinspection SpellCheckingInspection */
        return 'king-addons-icon king-addons-off-canvas-content';
    }

    public function get_style_depends(): array
    {
        return [KING_ADDONS_ASSETS_UNIQUE_KEY . '-off-canvas-content-style'];
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
            'float', 'floating', 'sticky', 'click', 'target', 'point', 'king', 'addons', 'mouseover', 'page',
            'kingaddons', 'king-addons', 'off canvas', 'pop up', 'popup', 'lightbox', 'box', 'modal', 'window', 'tab',
            'appear', 'show', 'hide', 'center', 'up', 'frame', 'iframe', 'embed'];
    }

    public function get_custom_help_url(): string
    {
        return 'https://kingaddons.com/elementor/off-canvas-content/';
    }

    protected function register_controls(): void
    {
        /** START TAB: CONTENT ===================== */
        /** SECTION: General ===================== */
        $this->start_controls_section(
            'kng_off_canvas_section_general',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('General', 'king-addons'),
            ]
        );

        $this->add_control(
            'kng_off_canvas_template',
            [
                'label' => esc_html__('Select Template', 'king-addons'),
                'type' => 'king-addons-ajax-select2',
                'options' => 'ajaxselect2/getElementorTemplates',
                'label_block' => true,
            ]
        );

        $this->add_control(
            'kng_off_canvas_box_position',
            [
                'label' => esc_html__('Box Position', 'king-addons'),
                'type' => Controls_Manager::CHOOSE,
                'default' => 'left',
                'options' => [
                    'top' => [
                        'title' => esc_html__('Top', 'king-addons'),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'right' => [
                        'title' => esc_html__('Right', 'king-addons'),
                        'icon' => 'eicon-h-align-right',
                    ],
                    'bottom' => [
                        'title' => esc_html__('Bottom', 'king-addons'),
                        'icon' => 'eicon-v-align-bottom',
                    ],
                    'left' => [
                        'title' => esc_html__('Left', 'king-addons'),
                        'icon' => 'eicon-h-align-left',
                    ],
                ],
            ]
        );

        $this->add_responsive_control(
            'kng_off_canvas_box_max_width',
            [
                'label' => esc_html__('Maximum Width (px)', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'step' => 1,
                'default' => 300,
                'selectors' => [
                    '.king-addons-off-canvas-{{ID}}' => 'max-width: {{VALUE}}px;',
                ],
                'condition' => [
                    'kng_off_canvas_box_position' => ['left', 'right']
                ]
            ]
        );

        $this->add_control(
            'kng_off_canvas_box_max_height_switcher',
            [
                'label' => esc_html__('Set the Maximum Height of Off-Canvas Content', 'king-addons'),
                'type' => Controls_Manager::SWITCHER
            ]
        );

        $this->add_responsive_control(
            'kng_off_canvas_box_max_height',
            [
                'label' => esc_html__('Maximum Height (px)', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'step' => 1,
                'default' => 300,
                'selectors' => [
                    '.king-addons-off-canvas-{{ID}}' => 'max-height: {{VALUE}}px;',
                ],
                'condition' => [
                    'kng_off_canvas_box_max_height_switcher!' => '',
                    'kng_off_canvas_box_position' => ['top', 'bottom']
                ]
            ]
        );

        $this->add_control(
            'kng_off_canvas_btn_text',
            [
                'label' => esc_html__('Button Text', 'king-addons'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Open Off-Canvas Content', 'king-addons'),
            ]
        );

        $this->add_control(
            'kng_off_canvas_disable_btn',
            [
                'label' => esc_html__('Disable Button', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'description' => esc_html__('This is a useful feature if there is a need to set a custom element as the trigger instead of the default button.', 'king-addons'),
                'separator' => 'before'
            ]
        );

        $this->add_control(
            'kng_off_canvas_class',
            [
                'label' => esc_html__('CSS Class of trigger element (optional)', 'king-addons'),
                'description' => esc_html__('Apply a specific CSS class to your custom trigger element, such as a button, text, a menu item, etc. You can add the class in the Advanced tab of the new trigger element. Then, enter the same class in this field as well. This way, the off-canvas content will open when you click on the trigger element. Please check the result on the live website, due to limitations of the Elementor preview mode.', 'king-addons'),
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
            'kng_off_canvas_section_style_btn',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Button', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'kng_off_canvas_btn_h_position',
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

        $this->add_control(
            'kng_off_canvas_btn_tabs_title',
            [
                'label' => '<b>' . esc_html__('Button Styles', 'king-addons') . '</b>',
                'type' => Controls_Manager::RAW_HTML,
                'separator' => 'before'
            ]
        );

        $this->start_controls_tabs('kng_off_canvas_btn_tabs');

        $this->start_controls_tab(
            'kng_off_canvas_btn_tab_normal',
            [
                'label' => esc_html__('Normal', 'king-addons'),
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'kng_off_canvas_btn_typography',
                'selector' => '{{WRAPPER}} .king-addons-off-canvas-button',
            ]
        );

        $this->add_control(
            'kng_off_canvas_btn_txt_color',
            [
                'label' => esc_html__('Text Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-off-canvas-button' => 'color: {{VALUE}}'
                ],
            ]
        );

        $this->add_control(
            'kng_off_canvas_btn_bg_color',
            [
                'label' => esc_html__('Background Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#574ff7',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-off-canvas-button' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'kng_off_canvas_btn_padding',
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
                    '{{WRAPPER}} .king-addons-off-canvas-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'kng_off_canvas_btn_box_shadow',
                'selector' => '{{WRAPPER}} .king-addons-off-canvas-button',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'kng_off_canvas_btn_border',
                'selector' => '{{WRAPPER}} .king-addons-off-canvas-button',
                'fields_options' => [
                    'border' => [
                        'default' => 'none',
                    ],
                ],
            ]
        );

        $this->add_responsive_control(
            'kng_off_canvas_btn_border_radius',
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
                    '{{WRAPPER}} .king-addons-off-canvas-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'kng_off_canvas_btn_tab_hover',
            [
                'label' => esc_html__('Hover', 'king-addons'),
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'kng_off_canvas_btn_typography_hover',
                'selector' => '{{WRAPPER}} .king-addons-off-canvas-button:hover',
            ]
        );

        $this->add_control(
            'kng_off_canvas_btn_txt_color_hover',
            [
                'label' => esc_html__('Text Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-off-canvas-button:hover' => 'color: {{VALUE}}'
                ],
            ]
        );

        $this->add_control(
            'kng_off_canvas_btn_bg_color_hover',
            [
                'label' => esc_html__('Background Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-off-canvas-button:hover' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'kng_off_canvas_btn_padding_hover',
            [
                'label' => esc_html__('Padding', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-off-canvas-button:hover' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'kng_off_canvas_btn_box_shadow_hover',
                'selector' => '{{WRAPPER}} .king-addons-off-canvas-button:hover',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'kng_off_canvas_btn_border_hover',
                'selector' => '{{WRAPPER}} .king-addons-off-canvas-button:hover',
            ]
        );

        $this->add_responsive_control(
            'kng_off_canvas_btn_border_radius_hover',
            [
                'label' => esc_html__('Border Radius', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-off-canvas-button:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();
        /** END SECTION: Button ===================== */

        /** SECTION: Off-Canvas box ===================== */
        $this->start_controls_section(
            'kng_off_canvas_section_style_off_canvas_box',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Off-Canvas box', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'kng_off_canvas_box_bg_color',
            [
                'label' => esc_html__('Background Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '.king-addons-off-canvas-{{ID}}' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'kkng_off_canvas_box_padding',
            [
                'label' => esc_html__('Padding', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'default' => [
                    'top' => 10,
                    'right' => 10,
                    'bottom' => 10,
                    'left' => 10,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '.king-addons-off-canvas-{{ID}}' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->add_control(
            'kng_off_canvas_box_z_index',
            [
                'label' => esc_html__('Z-Index of Off-Canvas box (optional)', 'king-addons'),
                'description' => esc_html__('Note: The Off-Canvas box may not display over all elements in the Elementor preview mode here, due to limitations of the Elementor preview mode. However, the Off-Canvas box will display properly over all elements on the live website. Please check it there.', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'step' => 1,
                'default' => 999999,
                'separator' => 'before',
                'selectors' => [
                    '.king-addons-off-canvas-{{ID}}' => 'z-index: {{VALUE}};',
                    '.king-addons-off-canvas-overlay-{{ID}}' => 'z-index: calc({{VALUE}} - 1);',
                ],
            ]
        );

        $this->end_controls_section();
        /** END SECTION: Off-Canvas box ===================== */

        /** SECTION: Close Button ===================== */
        $this->start_controls_section(
            'kng_off_canvas_section_style_close_btn',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Close Button', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'kng_off_canvas_close_btn_icon',
            [
                'label' => esc_html__('Select Icon', 'king-addons'),
                'type' => Controls_Manager::ICONS,
                'skin' => 'inline',
                'label_block' => false,
                'exclude_inline_options' => ['svg'],
                'default' => [
                    'value' => 'fas fa-times',
                    'library' => 'fa-solid',
                ],
            ]
        );

        $this->add_control(
            'kng_off_canvas_close_btn_color',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#000000',
                'selectors' => [
                    '.king-addons-off-canvas-close-button-{{ID}}' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'kng_off_canvas_close_btn_size',
            [
                'label' => esc_html__('Size (px)', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'step' => 1,
                'default' => 20,
                'separator' => 'before',
                'selectors' => [
                    '.king-addons-off-canvas-close-button-{{ID}}' => 'font-size: {{VALUE}}px;',
                ],
            ]
        );

        $this->end_controls_section();
        /** END SECTION: Close Button ===================== */

        /** SECTION: Overlay ===================== */
        $this->start_controls_section(
            'kng_off_canvas_overlay',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Overlay', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'kng_off_canvas_overlay_color',
            [
                'label' => esc_html__('Overlay Background Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => 'rgba(0, 0, 0, 0.5)',
                'selectors' => [
                    '.king-addons-off-canvas-overlay-{{ID}}' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
        /** END SECTION: Overlay ===================== */
        /** END TAB: STYLE ===================== */
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
        $this_ID = $this->get_id();
        $class_ID = 'king-addons-off-canvas-' . $this_ID;
        $overlay_ID = 'king-addons-off-canvas-overlay-' . $this_ID;

        if (!empty($settings['kng_off_canvas_template'])) {

            echo '<div class="king-addons-off-canvas-overlay ' .
                esc_attr($overlay_ID) . '" onclick="';
            echo "document.querySelector('." .
                esc_attr($class_ID) . "').classList.toggle('king-addons-off-canvas-active'); document.querySelector('." .
                esc_attr($overlay_ID) . "').style.opacity = '0'; document.body.style.pointerEvents = 'none'; setTimeout(function () {document.querySelector('." .
                esc_attr($overlay_ID) . "').style.display = 'none'; document.body.style.pointerEvents = '';}, 300);";
            echo '"></div>';

            echo '<div class="king-addons-off-canvas ' .
                esc_attr($class_ID) . ' king-addons-off-canvas-position-' .
                esc_attr($settings['kng_off_canvas_box_position']) . ' king-addons-off-canvas-animation-slide"';
            if (!Plugin::$instance->editor->is_edit_mode()) {
                echo ' style="display: none;"';
            }
            echo '>';

            if ('' != $settings['kng_off_canvas_close_btn_icon']['value']) {

                echo '<div class="king-addons-off-canvas-close-button king-addons-off-canvas-close-button-' .
                    esc_attr($this_ID) . '" onclick="';
                echo "document.querySelector('." .
                    esc_attr($class_ID) . "').classList.toggle('king-addons-off-canvas-active'); document.querySelector('." .
                    esc_attr($overlay_ID) . "').style.opacity = '0'; document.body.style.pointerEvents = 'none'; setTimeout(function () {document.querySelector('." .
                    esc_attr($overlay_ID) . "').style.display = 'none'; document.body.style.pointerEvents = '';}, 300);";
                echo '">';

                Icons_Manager::render_icon($settings['kng_off_canvas_close_btn_icon']);
                echo '</div>';
            }

            $html = $this->getOffCanvasTemplate(esc_html($settings['kng_off_canvas_template']));
            $dom = new DOMDocument;
            libxml_use_internal_errors(true); // Disable libxml errors
            $dom->loadHTML($html);
            libxml_clear_errors();

            $tags = [];
            $this->getTagsAndAttributes($dom->documentElement, $tags);

            echo wp_kses($html, $tags);

            echo '</div>';

            if ('' == $settings['kng_off_canvas_disable_btn']) {

                echo '<button class="king-addons-off-canvas-button king-addons-off-canvas-button-' .
                    esc_attr($this_ID) . '" onclick="';
                echo "document.querySelector('." .
                    esc_attr($class_ID) . "').classList.toggle('king-addons-off-canvas-active'); document.querySelector('." .
                    esc_attr($overlay_ID) . "').style.display = 'block'; document.body.style.pointerEvents = 'none'; setTimeout(function () {document.querySelector('." .
                    esc_attr($overlay_ID) . "').style.opacity = '1';}, 1); setTimeout(function () {document.body.style.pointerEvents = '';}, 300);";
                echo '">' .
                    esc_html($settings['kng_off_canvas_btn_text']) . '</button>';

            }
        } else {
            echo '<p>' . esc_html__('Please select a template', 'king-addons') . '</p>';
        }

        $inline_js_1 = "
            document.addEventListener('DOMContentLoaded', function () {

                const offCanvas = document.querySelector('." . esc_js($class_ID) . "');
                const overlay = document.querySelector('." . esc_js($overlay_ID) . "');

                // Moves all off-canvases to right after the <body> opens
                document.body.insertBefore(overlay, document.body.firstChild);
                document.body.insertBefore(offCanvas, document.body.firstChild);

                // Change display from none to block to prevent dancing of the off-canvas before the DOM content loaded
                offCanvas.style.display = 'block';";

        $inline_js_2 = "";
        if ('' != $settings['kng_off_canvas_class']) {
            $inline_js_2 = "
                // Adds click listener for custom triggers that have the custom class
                const customOffCanvasTrigger = document.querySelectorAll('." . esc_js($settings['kng_off_canvas_class']) . "');
                customOffCanvasTrigger.forEach(element => element.addEventListener('click', () => {
                    offCanvas.classList.toggle('king-addons-off-canvas-active');
                    document.body.style.pointerEvents = 'none';
                    if (offCanvas.classList.contains('king-addons-off-canvas-active')) {
                        overlay.style.display = 'block';
                        setTimeout(function () {
                            overlay.style.opacity = '1';
                        }, 1);
                    }
                    setTimeout(function () {
                        document.body.style.pointerEvents = '';
                    }, 300);
                }));
                customOffCanvasTrigger.forEach(element => element.style.cursor = 'pointer'); ";
        }

        $inline_js_3 = "});";

        $inline_js = $inline_js_1 . $inline_js_2 . $inline_js_3;
        wp_print_inline_script_tag($inline_js);
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