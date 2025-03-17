<?php

namespace King_Addons;

/** @noinspection SpellCheckingInspection */
if (!defined('ABSPATH')) {
    exit;
}

final class ELHF_Default_Method_1
{
    private static ?ELHF_Default_Method_1 $instance = null;

    public static function instance(): ?ELHF_Default_Method_1
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

    public function doHooks(): void
    {
        if (Header_Footer_Builder::isHeaderEnabled()) {
            // Replace header.php
            add_action('king_addons_el_hf_header', ['King_Addons\Header_Footer_Builder', 'renderHeader']);
            add_action('get_header', [$this, 'overrideHeader']);
        }

        if (Header_Footer_Builder::isFooterEnabled()) {
            // Replace footer.php
            add_action('king_addons_el_hf_footer', ['King_Addons\Header_Footer_Builder', 'renderFooter']);
            add_action('get_footer', [$this, 'overrideFooter']);
        }
    }

    // phpcs:ignore
    public function overrideHeader(): void
    {
        require_once(KING_ADDONS_PATH . 'includes/extensions/Header_Footer_Builder/themes/default/ELHF_Default_Header.php');
        $templates = [];
        $templates[] = 'header.php';
        // Avoid running wp_head hooks again.
        remove_all_actions('wp_head');
        ob_start();
        locate_template($templates, true);
        ob_get_clean();
    }

    // phpcs:ignore
    public function overrideFooter(): void
    {
        require_once(KING_ADDONS_PATH . 'includes/extensions/Header_Footer_Builder/themes/default/ELHF_Default_Footer.php');
        $templates = [];
        $templates[] = 'footer.php';
        // Avoid running wp_footer hooks again.
        remove_all_actions('wp_footer');
        ob_start();
        locate_template($templates, true);
        ob_get_clean();
    }
}

new ELHF_Default_Method_1();