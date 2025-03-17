<?php
/**
 * Plugin Name: King Addons
 * Description: 600+ Elementor templates, 60+ FREE widgets, and features like Live Search, Popups, Carousels, Image Hotspots, and Parallax Backgrounds.
 * Author URI: https://kingaddons.com/
 * Author: KingAddons.com
 * Version: 24.12.60
 * Text Domain: king-addons
 * Requires at least: 6.0
 * Requires PHP: 7.4
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 */

/** @noinspection SpellCheckingInspection */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/** PLUGIN VERSION */
const KING_ADDONS_VERSION = '24.12.60';

/** REQUIREMENTS */
const KING_ADDONS_MINIMUM_PHP_VERSION = '7.4';
const KING_ADDONS_MINIMUM_ELEMENTOR_VERSION = '3.19.0';

/** DEFINES */
define('KING_ADDONS_PATH', plugin_dir_path(__FILE__));
define('KING_ADDONS_URL', plugins_url('/', __FILE__));

/** ASSETS KEY - It's using to have the unique wp_register (style, script) handle */
const KING_ADDONS_ASSETS_UNIQUE_KEY = 'king-addons';

if (!version_compare(PHP_VERSION, KING_ADDONS_MINIMUM_PHP_VERSION, '>=')) {

    /** Admin notification when the site doesn't have a minimum required PHP version. */
    $message = sprintf(
    /* translators: %1$s is shortcut that puts required PHP version to the text */
        esc_html__('King Addons plugin requires PHP version %1$s or greater.', 'king-addons'),
        KING_ADDONS_MINIMUM_PHP_VERSION
    );

    echo '<div class="notice notice-error"><p>' . esc_html($message) . '</p></div>';

} else {

    if (!function_exists('king_addons_freemius')) {
        // Create a helper function for easy SDK access.
        function king_addons_freemius()
        {
            global $king_addons_freemius;

            if (!isset($king_addons_freemius)) {
                // Include Freemius SDK.
                require_once dirname(__FILE__) . '/freemius/start.php';

                /** @noinspection PhpUnhandledExceptionInspection */
                $king_addons_freemius = fs_dynamic_init(array(
                    'id' => '16154',
                    'slug' => 'king-addons',
                    'premium_slug' => 'king-addons-pro',
                    'type' => 'plugin',
                    'public_key' => 'pk_eac3624cbc14c1846cf1ab9abbd68',
                    'is_premium' => false, // temp
                    'premium_suffix' => 'pro',
                    // If your plugin is a serviceware, set this option to false.
                    'has_premium_version' => true,
                    'has_addons' => false,
                    'has_paid_plans' => false, // temp
                    'has_affiliation' => 'all',
                    'menu' => array(
                        'slug' => 'king-addons',
                        'first-path' => 'plugins.php',
                        'pricing' => false,
                        'contact' => false,
                        'support' => false,
                    ),
                ));
            }

            return $king_addons_freemius;
        }

        // Init Freemius.
        king_addons_freemius();
        // Signal that SDK was initiated.
        do_action('king_addons_freemius_loaded');
        king_addons_freemius()->add_filter('show_deactivation_subscription_cancellation', '__return_false');
        king_addons_freemius()->add_filter('deactivate_on_activation', '__return_false');
    }

    if (!function_exists('king_addons_doActivation')) {
        function king_addons_doActivation()
        {
            add_option('king_addons_plugin_activated', true);
            if (false === get_option('king_addons_optionActivationTime')) {
                add_option('king_addons_optionActivationTime', absint(intval(strtotime('now'))));
            }
        }

        register_activation_hook(__FILE__, 'king_addons_doActivation');
    }

    if (!function_exists('king_addons_doDectivation')) {
        function king_addons_doDectivation()
        {
            delete_option('king_addons_HFB_flushed_rewrite_rules');
            delete_option('king_addons_optionActivationTime');
        }

        register_deactivation_hook(__FILE__, 'king_addons_doDectivation');
    }

    if (!function_exists('king_addons_doRedirect_after_activation')) {
        function king_addons_doRedirect_after_activation()
        {
            if (did_action('elementor/loaded')) {
                if (get_option('king_addons_plugin_activated', false)) {
                    delete_option('king_addons_plugin_activated');
                    wp_redirect(admin_url('admin.php?page=king-addons'));
                    exit;
                }
            }
        }

        add_action('admin_init', 'king_addons_doRedirect_after_activation');
    }

    /**
     * Main function
     *
     * @return void
     * @since 1.0.0
     * @access public
     */
    if (!function_exists('king_addons_doPlugin')) {
        /** @noinspection PhpMissingReturnTypeInspection */
        function king_addons_doPlugin()
        {
            require_once(KING_ADDONS_PATH . 'includes/Core.php');
        }

        add_action('plugins_loaded', 'king_addons_doPlugin');
    }

    /**
     * Register Assets
     *
     * @return void
     * @since 1.0.0
     * @access public
     */
    if (!function_exists('king_addons_registerAssets')) {
        /** @noinspection PhpMissingReturnTypeInspection */
        function king_addons_registerAssets()
        {
            require_once(KING_ADDONS_PATH . 'includes/RegisterAssets.php');
        }

        add_action('wp_loaded', 'king_addons_registerAssets');
    }
}

/**
 * Hides spaming notices from another plugins on the plugin settings page
 *
 * @return void
 * @since 1.0.0
 * @access public
 */
if (!function_exists('king_addons_hideAnotherNotices')) {
    /** @noinspection PhpMissingReturnTypeInspection */
    function king_addons_hideAnotherNotices()
    {
        $current_screen = get_current_screen()->id;
//        error_log($current_screen);
        if (
            $current_screen == 'toplevel_page_king-addons' ||
            $current_screen == 'toplevel_page_king-addons-popup-builder' ||
            $current_screen == 'edit-king-addons-el-hf' ||
            $current_screen == 'header-footer_page_king-addons-el-hf-settings' ||
            $current_screen == 'king-addons_page_king-addons-settings' ||
            $current_screen == 'toplevel_page_king-addons-templates'
        ) {
            // Remove all notices
            remove_all_actions('user_admin_notices');
            remove_all_actions('admin_notices');
        }
    }

    add_action('in_admin_header', 'king_addons_hideAnotherNotices', 99);
}

/**
 * Apply styles to the plugin menu icon because some plugins broke the menu icon styles
 *
 * @return void
 * @since 24.8.25
 * @access public
 */
if (!function_exists('king_addons_styleMenuIcon')) {
    /** @noinspection PhpMissingReturnTypeInspection */
    function king_addons_styleMenuIcon()
    {
        wp_enqueue_style('king-addons-plugin-style-menu-icon', plugin_dir_url(__FILE__) . 'includes/admin/css/menu-icon.css', '', KING_ADDONS_VERSION);
    }

    add_action('admin_enqueue_scripts', 'king_addons_styleMenuIcon');
}