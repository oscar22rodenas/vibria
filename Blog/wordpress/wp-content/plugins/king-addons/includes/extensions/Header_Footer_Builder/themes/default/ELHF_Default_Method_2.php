<?php

namespace King_Addons;

/** @noinspection SpellCheckingInspection */
if (!defined('ABSPATH')) {
    exit;
}

final class ELHF_Default_Method_2
{
    private static ?ELHF_Default_Method_2 $instance = null;

    public static function instance(): ?ELHF_Default_Method_2
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function __construct()
    {
        add_action('wp', [$this, 'doHooks']);
    }

    // phpcs:ignore
    public function doHooks(): void
    {
        if (Header_Footer_Builder::isHeaderEnabled()) {
            add_action('king_addons_el_hf_fallback_header', ['King_Addons\Header_Footer_Builder', 'getHeaderContent']);
            add_action('get_header', [$this, 'overrideHeader']);
            add_action('wp_body_open', ['King_Addons\Header_Footer_Builder', 'getHeaderContent']);
            add_action('wp_enqueue_scripts', [$this, 'addStyleHeader']);
        }

        if (Header_Footer_Builder::isFooterEnabled()) {
            add_action('wp_footer', ['King_Addons\Header_Footer_Builder', 'getFooterContent'], 50);
            add_action('wp_enqueue_scripts', [$this, 'addStyleFooter']);
        }
    }

    // phpcs:ignore
    public function addStyleHeader(): void
    {
        wp_add_inline_style('king-addons-el-hf-header-style', '.king-addons-el-hf-forcefully-stretched-header {width: 100vw; position: relative; margin-left: -50vw; left: 50%;} header#masthead {display: none;}');
    }

    public function addStyleFooter(): void
    {
        wp_add_inline_style('king-addons-el-hf-footer-style', 'footer#colophon {display: none;}');
    }

    // phpcs:ignore
    public function overrideHeader(): void
    {
        $templates = [];
        $templates[] = 'header.php';
        locate_template($templates, true);

        if (!did_action('wp_body_open')) {
            echo '<div class="king-addons-el-hf-forcefully-stretched-header">';
            do_action('king_addons_el_hf_fallback_header');
            echo '</div>';
        }
    }
}

new ELHF_Default_Method_2();