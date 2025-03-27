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

class Popup extends Widget_Base
{
    public function get_name(): string
    {
        return 'king-addons-popup';
    }

    public function get_title(): string
    {
        return esc_html__('Popup & Lightbox Modal', 'king-addons');
    }

    public function get_icon(): string
    {
        /** @noinspection SpellCheckingInspection */
        return 'king-addons-icon king-addons-popup';
    }

    public function get_style_depends(): array
    {
        return [KING_ADDONS_ASSETS_UNIQUE_KEY . '-popup-style'];
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
            'appear', 'show', 'hide', 'up', 'frame', 'iframe', 'embed'];
    }

    public function get_custom_help_url(): string
    {
        return 'https://kingaddons.com/elementor/popup-lightbox-modal/';
    }

    protected function register_controls(): void
    {
        /** START TAB: CONTENT ===================== */
        /** SECTION: General ===================== */
        $this->start_controls_section(
            'kng_popup_section_general',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('General', 'king-addons'),
            ]
        );

        $this->add_control(
            'kng_popup_template',
            [
                'label' => esc_html__('Select Template', 'king-addons'),
                'type' => 'king-addons-ajax-select2',
                'options' => 'ajaxselect2/getElementorTemplates',
                'label_block' => true,
            ]
        );

        $this->add_control(
            'kng_popup_box_position',
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
            ]
        );

        $this->add_control(
            'kng_popup_btn_text',
            [
                'label' => esc_html__('Button Text', 'king-addons'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Open Popup', 'king-addons'),
            ]
        );

        $this->add_control(
            'kng_popup_box_width_switcher',
            [
                'label' => esc_html__('Set Width of popup box', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'separator' => 'before',
                'default' => 'yes'
            ]
        );

        $this->add_responsive_control(
            'kng_popup_box_width',
            [
                'label' => esc_html__('Width (%)', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 100,
                'step' => 1,
                'default' => 100,
                'selectors' => [
                    '.king-addons-popup-{{ID}}' => 'width: {{VALUE}}% !important;',
                ],
                'condition' => [
                    'kng_popup_box_width_switcher!' => '',
                ]
            ]
        );

        $this->add_control(
            'kng_popup_box_max_width_switcher',
            [
                'label' => esc_html__('Set Maximum Width of popup box', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'separator' => 'before',
                'default' => 'yes'
            ]
        );

        $this->add_responsive_control(
            'kng_popup_box_max_width',
            [
                'label' => esc_html__('Maximum Width (px)', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'step' => 1,
                'default' => 855,
                'selectors' => [
                    '.king-addons-popup-{{ID}}' => 'max-width: {{VALUE}}px !important;',
                ],
                'condition' => [
                    'kng_popup_box_max_width_switcher!' => '',
                ]
            ]
        );

        $this->add_control(
            'kng_popup_box_max_height_switcher',
            [
                'label' => esc_html__('Set Maximum Height of popup box', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes'
            ]
        );

        $this->add_responsive_control(
            'kng_popup_box_max_height',
            [
                'label' => esc_html__('Maximum Height (px)', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'step' => 1,
                'default' => 385,
                'selectors' => [
                    '.king-addons-popup-{{ID}}' => 'max-height: {{VALUE}}px !important;',
                ],
                'condition' => [
                    'kng_popup_box_max_height_switcher!' => '',
                ]
            ]
        );

        $this->add_control(
            'kng_popup_disable_btn',
            [
                'label' => esc_html__('Disable Button', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'description' => esc_html__('This is a useful feature if there is a need to set a custom element as the trigger instead of the default button.', 'king-addons'),
                'separator' => 'before'
            ]
        );

        $this->add_control(
            'kng_popup_class',
            [
                'label' => esc_html__('CSS Class of trigger element (optional)', 'king-addons'),
                'description' => esc_html__('Apply a specific CSS class to your custom trigger element, such as a button, text, a menu item, etc. You can add the class in the Advanced tab of the new trigger element. Then, enter the same class in this field as well. This way, the Popup & Lightbox Modal will open when you click on the trigger element. Please check the result on the live website, due to limitations of the Elementor preview mode.', 'king-addons'),
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
            'kng_popup_section_style_btn',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Button', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'kng_popup_btn_h_position',
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
            'kng_popup_btn_tabs_title',
            [
                'label' => '<b>' . esc_html__('Button Styles', 'king-addons') . '</b>',
                'type' => Controls_Manager::RAW_HTML,
                'separator' => 'before'
            ]
        );

        $this->start_controls_tabs('kng_popup_btn_tabs');

        $this->start_controls_tab(
            'kng_popup_btn_tab_normal',
            [
                'label' => esc_html__('Normal', 'king-addons'),
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'kng_popup_btn_typography',
                'selector' => '{{WRAPPER}} .king-addons-popup-button',
            ]
        );

        $this->add_control(
            'kng_popup_btn_txt_color',
            [
                'label' => esc_html__('Text Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-popup-button' => 'color: {{VALUE}}'
                ],
            ]
        );

        $this->add_control(
            'kng_popup_btn_bg_color',
            [
                'label' => esc_html__('Background Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#574ff7',
                'selectors' => [
                    '{{WRAPPER}} .king-addons-popup-button' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'kng_popup_btn_padding',
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
                    '{{WRAPPER}} .king-addons-popup-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'kng_popup_btn_box_shadow',
                'selector' => '{{WRAPPER}} .king-addons-popup-button',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'kng_popup_btn_border',
                'selector' => '{{WRAPPER}} .king-addons-popup-button',
                'fields_options' => [
                    'border' => [
                        'default' => 'none',
                    ],
                ],
            ]
        );

        $this->add_responsive_control(
            'kng_popup_btn_border_radius',
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
                    '{{WRAPPER}} .king-addons-popup-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'kng_popup_btn_tab_hover',
            [
                'label' => esc_html__('Hover', 'king-addons'),
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'kng_popup_btn_typography_hover',
                'selector' => '{{WRAPPER}} .king-addons-popup-button:hover',
            ]
        );

        $this->add_control(
            'kng_popup_btn_txt_color_hover',
            [
                'label' => esc_html__('Text Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-popup-button:hover' => 'color: {{VALUE}}'
                ],
            ]
        );

        $this->add_control(
            'kng_popup_btn_bg_color_hover',
            [
                'label' => esc_html__('Background Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .king-addons-popup-button:hover' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'kng_popup_btn_padding_hover',
            [
                'label' => esc_html__('Padding', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-popup-button:hover' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'kng_popup_btn_box_shadow_hover',
                'selector' => '{{WRAPPER}} .king-addons-popup-button:hover',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'kng_popup_btn_border_hover',
                'selector' => '{{WRAPPER}} .king-addons-popup-button:hover',
            ]
        );

        $this->add_responsive_control(
            'kng_popup_btn_border_radius_hover',
            [
                'label' => esc_html__('Border Radius', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .king-addons-popup-button:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();
        /** END SECTION: Button ===================== */

        /** SECTION: Popup box ===================== */
        $this->start_controls_section(
            'kng_popup_section_style_off_canvas_box',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Popup box', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'kng_popup_box_bg_color',
            [
                'label' => esc_html__('Background Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '.king-addons-popup-{{ID}}' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'kkng_popup_box_padding',
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
                    '.king-addons-popup-{{ID}}' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'kng_popup_box_box_shadow',
                'selector' => '.king-addons-popup-{{ID}}',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'kng_popup_box_border',
                'selector' => '.king-addons-popup-{{ID}}',
                'fields_options' => [
                    'border' => [
                        'default' => 'none',
                    ],
                ],
            ]
        );

        $this->add_responsive_control(
            'kng_popup_box_border_radius',
            [
                'label' => esc_html__('Border Radius', 'king-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'default' => [
                    'top' => 5,
                    'right' => 5,
                    'bottom' => 5,
                    'left' => 5,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '.king-addons-popup-{{ID}}' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->add_control(
            'kng_popup_box_z_index',
            [
                'label' => esc_html__('Z-Index of Popup box (optional)', 'king-addons'),
                'description' => esc_html__('Note: The Popup box may not display over all elements in the Elementor preview mode here, due to limitations of the Elementor preview mode. However, the Popup box will display properly over all elements on the live website. Please check it there.', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'step' => 1,
                'default' => 999999,
                'separator' => 'before',
                'selectors' => [
                    '.king-addons-popup-{{ID}}' => 'z-index: {{VALUE}};',
                    '.king-addons-popup-overlay-{{ID}}' => 'z-index: calc({{VALUE}} - 1);',
                ],
            ]
        );

        $this->end_controls_section();
        /** END SECTION: Popup box ===================== */

        /** SECTION: Close Button ===================== */
        $this->start_controls_section(
            'kng_popup_section_style_close_btn',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Close Button', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'kng_popup_close_btn_icon',
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
            'kng_popup_close_btn_color',
            [
                'label' => esc_html__('Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#000000',
                'selectors' => [
                    '.king-addons-popup-close-button-{{ID}}' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'kng_popup_close_btn_size',
            [
                'label' => esc_html__('Size (px)', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'step' => 1,
                'default' => 20,
                'separator' => 'before',
                'selectors' => [
                    '.king-addons-popup-close-button-{{ID}}' => 'font-size: {{VALUE}}px;',
                ],
            ]
        );

        $this->end_controls_section();
        /** END SECTION: Close Button ===================== */

        /** SECTION: Overlay ===================== */
        $this->start_controls_section(
            'kng_popup_overlay',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Overlay', 'king-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'kng_popup_overlay_color',
            [
                'label' => esc_html__('Overlay Background Color', 'king-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => 'rgba(0, 0, 0, 0.5)',
                'selectors' => [
                    '.king-addons-popup-overlay-{{ID}}' => 'background-color: {{VALUE}};',
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
        $class_ID = 'king-addons-popup-' . $this_ID;
        $overlay_ID = 'king-addons-popup-overlay-' . $this_ID;

        if (!empty($settings['kng_popup_template'])) {

            echo '<div class="king-addons-popup-overlay ' .
                esc_attr($overlay_ID) . '" onclick="';
            echo "document.querySelector('." .
                esc_attr($class_ID) . "').classList.toggle('king-addons-popup-active'); document.querySelector('." .
                esc_attr($overlay_ID) . "').style.opacity = '0'; document.body.style.pointerEvents = 'none'; setTimeout(function () {document.querySelector('." .
                esc_attr($overlay_ID) . "').style.display = 'none'; document.body.style.pointerEvents = '';}, 500);";
            echo '"></div>';

            echo '<div class="king-addons-popup ' .
                esc_attr($class_ID) . ' king-addons-popup-position-' .
                esc_attr($settings['kng_popup_box_position']) . ' king-addons-popup-animation-slide"';
            if (!Plugin::$instance->editor->is_edit_mode()) {
                echo ' style="display: none;"';
            }
            echo '>';

            if ('' != $settings['kng_popup_close_btn_icon']['value']) {

                echo '<div class="king-addons-popup-close-button king-addons-popup-close-button-' .
                    esc_attr($this_ID) . '" onclick="';
                echo "document.querySelector('." .
                    esc_attr($class_ID) . "').classList.toggle('king-addons-popup-active'); document.querySelector('." .
                    esc_attr($overlay_ID) . "').style.opacity = '0'; document.body.style.pointerEvents = 'none'; setTimeout(function () {document.querySelector('." .
                    esc_attr($overlay_ID) . "').style.display = 'none'; document.body.style.pointerEvents = '';}, 500);";
                echo '">';

                Icons_Manager::render_icon($settings['kng_popup_close_btn_icon']);
                echo '</div>';
            }

            $html = $this->getOffCanvasTemplate(esc_html($settings['kng_popup_template']));
            $dom = new DOMDocument;
            libxml_use_internal_errors(true); // Disable libxml errors
            $dom->loadHTML($html);
            libxml_clear_errors();

            $tags = [];
            $this->getTagsAndAttributes($dom->documentElement, $tags);

            echo wp_kses($html, $tags);

            echo '</div>';

            if ('' == $settings['kng_popup_disable_btn']) {

                echo '<button class="king-addons-popup-button king-addons-popup-button-' .
                    esc_attr($this_ID) . '" onclick="';
                echo "document.querySelector('." .
                    esc_attr($class_ID) . "').classList.toggle('king-addons-popup-active'); document.querySelector('." .
                    esc_attr($overlay_ID) . "').style.display = 'block'; document.body.style.pointerEvents = 'none'; setTimeout(function () {document.querySelector('." .
                    esc_attr($overlay_ID) . "').style.opacity = '1';}, 1); setTimeout(function () {document.body.style.pointerEvents = '';}, 500);";
                echo '">' .
                    esc_html($settings['kng_popup_btn_text']) . '</button>';

            }
        } else {
            echo '<p>' . esc_html__('Please select a template', 'king-addons') . '</p>';
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
        if ('' != $settings['kng_popup_class']) {
            $inline_js_2 = "
                // Adds click listener for custom triggers that have the custom class
                const customOffCanvasTrigger = document.querySelectorAll('." . esc_js($settings['kng_popup_class']) . "');
                customOffCanvasTrigger.forEach(element => element.addEventListener('click', () => {
                    offCanvas.classList.toggle('king-addons-popup-active');
                    document.body.style.pointerEvents = 'none';
                    if (offCanvas.classList.contains('king-addons-popup-active')) {
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