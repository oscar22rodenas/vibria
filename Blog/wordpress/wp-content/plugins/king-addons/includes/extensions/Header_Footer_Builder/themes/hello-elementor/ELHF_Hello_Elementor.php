<?php

namespace King_Addons;

if (!defined('ABSPATH')) {
    exit;
}

class ELHF_Hello_Elementor {

    private static ?ELHF_Hello_Elementor $instance = null;

    // phpcs:ignore
    public static function instance(): ELHF_Hello_Elementor {
        if ( ! isset( self::$instance ) ) {
            self::$instance = new ELHF_Hello_Elementor();

            if (!class_exists('ELHF_Default_Method_1')) {
                require_once(KING_ADDONS_PATH . 'includes/extensions/Header_Footer_Builder/themes/default/ELHF_Default_Method_1.php');
            }
        }

        return self::$instance;
    }
}

ELHF_Hello_Elementor::instance();
