<?php /** @noinspection PhpUnused, CssUnusedSymbol, DuplicatedCode, SpellCheckingInspection */

namespace King_Addons;

use Elementor\Controls_Manager;
use Elementor\Element_Base;
use Elementor\Group_Control_Image_Size;
use Elementor\Utils;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class Parallax_Background
{
    private static ?Parallax_Background $_instance = null;

    public static function instance(): Parallax_Background
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
        add_action('elementor/frontend/before_render', [__CLASS__, 'disableLazyLoading'], 1);
        add_action('elementor/frontend/container/after_render', array($this, 'renderAnimation'), 10);
    }

    public static function disableLazyLoading(Element_Base $element): void
    {
        if ($element->get_settings('kng_parallax_bg_switch')) {
            $element->add_render_attribute(
                '_wrapper',
                [
                    'class' => 'e-lazyloaded',
                ]
            );
        }
    }

    public static function enqueueScripts(): void
    {
        if (!wp_script_is(KING_ADDONS_ASSETS_UNIQUE_KEY . '-' . 'jarallax' . '-' . 'jarallax')) {
            wp_enqueue_script(KING_ADDONS_ASSETS_UNIQUE_KEY . '-' . 'jarallax' . '-' . 'jarallax', '', array('jquery'), KING_ADDONS_VERSION);
        }

        if (!wp_script_is(KING_ADDONS_ASSETS_UNIQUE_KEY . '-' . 'parallax-background' . '-' . 'preview-handler')) {
            wp_enqueue_script(KING_ADDONS_ASSETS_UNIQUE_KEY . '-' . 'parallax-background' . '-' . 'preview-handler', '', array('jquery'), KING_ADDONS_VERSION);
        }
    }

    public static function addControls(Element_Base $element): void
    {
        $element->start_controls_section(
            'kng_parallax_bg_section',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON . esc_html__('Parallax Background', 'king-addons'),
                'tab' => Controls_Manager::TAB_ADVANCED
            ]
        );

        $element->add_control(
            'kng_parallax_bg_switch',
            [
                'label' => esc_html__('Enable Parallax', 'king-addons'),
                'type' => Controls_Manager::SWITCHER,
                'prefix_class' => 'kng-parallax-bg-',
                'frontend_available' => true,
                'render_type' => 'template',
            ]
        );

        $element->add_control(
            'kng_parallax_bg_notice',
            [
                'type' => Controls_Manager::NOTICE,
                'notice_type' => 'info',
                'dismissible' => false,
                'heading' => esc_html__( 'Note', 'king-addons' ),
                'content' => esc_html__( 'If images are not showing, try disabling the Lazy Load Background Images option in Elementor settings', 'king-addons' ),
                'condition' => [
                    'kng_parallax_bg_switch!' => ''
                ],
            ]
        );

        $element->add_control(
            'kng_parallax_bg_image',
            [
                'label' => esc_html__('Image', 'king-addons'),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
                'frontend_available' => true,
                'condition' => [
                    'kng_parallax_bg_switch!' => ''
                ]
            ]
        );

        $element->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name' => 'kng_parallax_bg_image_size',
                'default' => 'full',
                'frontend_available' => true,
                'condition' => [
                    'kng_parallax_bg_switch!' => ''
                ]
            ]
        );

        $element->add_control(
            'kng_parallax_bg_speed',
            [
                'label' => esc_html__('Animation Speed and Moving Direction', 'king-addons'),
                'description' => esc_html__('From -1.0 to 2.0', 'king-addons'),
                'type' => Controls_Manager::NUMBER,
                'frontend_available' => true,
                'min' => -1,
                'max' => 2,
                'step' => 0.1,
                'default' => 1.3,
                'separator' => 'before',
                'condition' => [
                    'kng_parallax_bg_switch!' => ''
                ]
            ]
        );

        $element->add_control('kng_parallax_bg_type',
            [
                'label' => esc_html__('Type', 'king-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'scroll' => esc_html__('Scroll', 'king-addons'),
                    'scale' => esc_html__('Scale', 'king-addons'),
                    'opacity' => esc_html__('Opacity', 'king-addons'),
                    'scroll-opacity' => esc_html__('Scroll-Opacity', 'king-addons'),
                    'scale-opacity' => esc_html__('Scale-Opacity', 'king-addons'),
                ],
                'default' => 'scroll',
                'frontend_available' => true,
                'label_block' => 'true',
                'separator' => 'before',
                'condition' => [
                    'kng_parallax_bg_switch!' => ''
                ],
            ]
        );

        $element->end_controls_section();
    }

    public static function renderAnimation(Element_Base $element): void
    {
        if (!empty($element->get_settings_for_display('kng_parallax_bg_switch'))) {

            if (!wp_script_is(KING_ADDONS_ASSETS_UNIQUE_KEY . '-' . 'jarallax' . '-' . 'jarallax')) {
                wp_enqueue_script(KING_ADDONS_ASSETS_UNIQUE_KEY . '-' . 'jarallax' . '-' . 'jarallax', '', array('jquery'), KING_ADDONS_VERSION);
            }

            if (('container' === $element->get_type() || 'section' === $element->get_type()) && 'yes' === $element->get_settings('kng_parallax_bg_switch')) {

                $settings = $element->get_settings();

                $image_src = Group_Control_Image_Size::get_attachment_image_src($settings['kng_parallax_bg_image']['id'], 'kng_parallax_bg_image_size', $settings);

                if (!$image_src) {
                    $image_src = $settings['kng_parallax_bg_image']['url'];
                }

                $element_ID = $element->get_id();

                if ($image_src) {
                    $inline_style = ".king-addons-parallax-container {position: absolute;top: 0;left: 0;width: 100%;height: 100%;z-index: -100 !important;} .kng-parallax-bg-yes {background-image: none !important;} .jarallax div {will-change: transform;}";
                    wp_enqueue_style('king-addons-parallax-background-' . $element_ID, KING_ADDONS_URL . 'includes/features/Parallax_Background/style.css', '', KING_ADDONS_VERSION);
                    wp_add_inline_style('king-addons-parallax-background-' . $element_ID, $inline_style);

                    $inline_js = "document.querySelector('.elementor-element-" . esc_js($element_ID) . "').insertAdjacentHTML('beforeend', '" . '<div data-jarallax data-speed="' . esc_attr($settings['kng_parallax_bg_speed']) . '" data-type="' . esc_attr($settings['kng_parallax_bg_type']) . '" class="jarallax king-addons-parallax-container kng-parallax-bg-' . esc_js($element_ID) . '" style="background-image: url(' . esc_url($image_src) . ');"></div>' . "');";
                    wp_print_inline_script_tag($inline_js);
                }
            }
        }
    }
}