<?php

namespace King_Addons;

class ELHF_Render_On_Canvas
{
    private static ?ELHF_Render_On_Canvas $instance = null;

    public static function instance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new ELHF_Render_On_Canvas();

            add_action('wp', [self::$instance, 'hooks']);
        }
        return self::$instance;
    }

    public function hooks()
    {
        if (Header_Footer_Builder::isHeaderEnabled()) {
            if (version_compare(ELEMENTOR_VERSION, '1.4.1', '>=')) {
                add_action('elementor/page_templates/canvas/before_content', [$this, 'renderHeader']);
            } else {
                add_action('wp_head', [$this, 'renderHeader']);
            }
        }

        if (Header_Footer_Builder::isFooterEnabled()) {
            if (version_compare(ELEMENTOR_VERSION, '1.9.0', '>=')) {
                add_action('elementor/page_templates/canvas/after_content', [$this, 'renderFooter']);
            } else {
                add_action('wp_footer', [$this, 'renderFooter']);
            }
        }

    }

    public function renderHeader()
    {
        if ('elementor_canvas' !== get_page_template_slug()) {
            return;
        }

        if ('1' == get_post_meta(Header_Footer_Builder::getHeaderID(), 'king-addons-el-hf-display-on-canvas', true)) {
            Header_Footer_Builder::renderHeader();
        }
    }

    public function renderFooter()
    {
        if ('elementor_canvas' !== get_page_template_slug()) {
            return;
        }

        if ('1' == get_post_meta(Header_Footer_Builder::getFooterID(), 'king-addons-el-hf-display-on-canvas', true)) {
            Header_Footer_Builder::renderFooter();
        }
    }

}

ELHF_Render_On_Canvas::instance();