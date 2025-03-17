<?php

namespace King_Addons;

if (!defined('ABSPATH')) {
    exit;
}

final class RegisterAssets
{
    private static ?RegisterAssets $_instance = null;

    public static function instance(): RegisterAssets
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function __construct()
    {
        // Register styles and scripts for Elementor widgets and features
        self::registerElementorStyles();
        self::registerElementorScripts();

        // Register general files
        self::registerLibrariesFiles();
    }

    /**
     * Register CSS files
     */
    function registerElementorStyles(): void
    {
        foreach (ModulesMap::getModulesMapArray()['widgets'] as $widget_id => $widget_array) {
            foreach ($widget_array['css'] as $css) {
                wp_register_style(KING_ADDONS_ASSETS_UNIQUE_KEY . '-' . $widget_id . '-' . $css, KING_ADDONS_URL . 'includes/widgets/' . $widget_array['php-class'] . '/' . $css . '.css', null, KING_ADDONS_VERSION);
            }
        }
    }

    /**
     * Register JS files
     */
    function registerElementorScripts(): void
    {
        foreach (ModulesMap::getModulesMapArray()['widgets'] as $widget_id => $widget_array) {
            foreach ($widget_array['js'] as $js) {
                wp_register_script(KING_ADDONS_ASSETS_UNIQUE_KEY . '-' . $widget_id . '-' . $js, KING_ADDONS_URL . 'includes/widgets/' . $widget_array['php-class'] . '/' . $js . '.js', array('jquery'), KING_ADDONS_VERSION, true);
            }
        }

        wp_localize_script(KING_ADDONS_ASSETS_UNIQUE_KEY . '-search-script', 'KingAddonsSearchData', [
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('king_addons_search_nonce'),
        ]);

        wp_localize_script(KING_ADDONS_ASSETS_UNIQUE_KEY . '-mailchimp-script', 'KingAddonsMailChimpData', [
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('king_addons_mailchimp_nonce'),
        ]);

//        wp_localize_script(KING_ADDONS_ASSETS_UNIQUE_KEY . '-form-builder-script', 'KingAddonsFormBuilderData', [
//            'ajaxUrl' => admin_url('admin-ajax.php'),
//            'nonce' => wp_create_nonce('king_addons_fb_nonce'),
//            'input_empty' => esc_html__('Please fill out this field', 'king-addons'),
//            'select_empty' => esc_html__('Nothing selected', 'king-addons'),
//            'file_empty' => esc_html__('Please upload a file', 'king-addons'),
//            'recaptcha_v3_site_key' => get_option('king_addons_recaptcha_v3_site_key'),
//            'recaptcha_error' => esc_html__('Recaptcha Error', 'king-addons'),
//        ]);

        foreach (ModulesMap::getModulesMapArray()['features'] as $feature_id => $feature_array) {
            foreach ($feature_array['js'] as $js) {
                wp_register_script(KING_ADDONS_ASSETS_UNIQUE_KEY . '-' . $feature_id . '-' . $js, KING_ADDONS_URL . 'includes/features/' . $feature_array['php-class'] . '/' . $js . '.js', null, KING_ADDONS_VERSION);
            }
        }
    }

    /**
     * Register libraries files
     */
    function registerLibrariesFiles(): void
    {
        foreach (LibrariesMap::getLibrariesMapArray()['libraries'] as $library_id => $library_array) {
            foreach ($library_array['css'] as $css) {
                wp_register_style(KING_ADDONS_ASSETS_UNIQUE_KEY . '-' . $library_id . '-' . $css, KING_ADDONS_URL . 'includes/assets/libraries/' . $library_id . '/' . $css . '.css', null, KING_ADDONS_VERSION);
            }
            foreach ($library_array['js'] as $js) {
                wp_register_script(KING_ADDONS_ASSETS_UNIQUE_KEY . '-' . $library_id . '-' . $js, KING_ADDONS_URL . 'includes/assets/libraries/' . $library_id . '/' . $js . '.js', null, KING_ADDONS_VERSION);
            }
        }

        wp_localize_script(KING_ADDONS_ASSETS_UNIQUE_KEY . '-grid-grid', 'KingAddonsGridData', [
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('king_addons_grid_nonce'),
            'viewCart' => esc_html__('View Cart', 'king-addons'),
            'addedToCartText' => esc_html__('was added to cart', 'king-addons'),
            'comparePageURL' => get_permalink(get_option('king_addons_compare_page')),
            'wishlistPageURL' => get_permalink(get_option('king_addons_wishlist_page')),
        ]);

    }
}

RegisterAssets::instance();