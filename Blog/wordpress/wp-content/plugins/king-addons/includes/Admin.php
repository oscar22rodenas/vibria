<?php
/**
 * Admin class do all things for admin menu
 */

namespace King_Addons;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

final class Admin
{
    public function __construct()
    {
        if (is_admin()) {
            add_action('admin_menu', [$this, 'addAdminMenu']);
            add_action('admin_init', [$this, 'createSettings']);
        }
    }

    function addAdminMenu(): void
    {
        add_menu_page(
            'King Addons for Elementor',
            'King Addons',
            'manage_options',
            'king-addons',
            [$this, 'showAdminPage'],
            KING_ADDONS_URL . 'includes/admin/img/icon-for-admin.svg',
            58.7
        );

        add_submenu_page(
            'king-addons',              // parent slug
            'King Addons Settings',     // page title
            'Settings',                 // menu title
            'manage_options',           // capability
            'king-addons-settings',     // submenu slug
            [$this, 'showSettingsPage'] // callback function to render the page
        );

        if (KING_ADDONS_EXT_TEMPLATES_CATALOG) {
            add_menu_page(
                'King Addons for Elementor',
                (!king_addons_freemius()->can_use_premium_code() ?  esc_html__('Free Templates', 'king-addons') :  esc_html__('Templates Pro', 'king-addons')),
                'manage_options',
                'king-addons-templates',
                [Templates::instance(), 'render_template_catalog_page'],
                KING_ADDONS_URL . 'includes/admin/img/icon-for-menu-templates.svg',
                58.71
            );
        }

        if (KING_ADDONS_EXT_HEADER_FOOTER_BUILDER) {
            self::showHeaderFooterBuilder();
        }

        if (KING_ADDONS_EXT_POPUP_BUILDER) {
            self::showPopupBuilder();
        }
    }

    function showPopupBuilder(): void
    {
        add_menu_page(
            'Popup Builder',
            'Popup Builder',
            'manage_options',
            'king-addons-popup-builder',
            [Popup_Builder::instance(), 'renderPopupBuilder'],
            KING_ADDONS_URL . 'includes/admin/img/icon-for-popup-builder.svg',
            58.73
        );
    }

    function showHeaderFooterBuilder(): void
    {
        $post_type = 'king-addons-el-hf';
        $menu_slug = 'edit.php?post_type=' . $post_type;

        // Add Main Menu
        add_menu_page(
            esc_html__('Elementor Header & Footer Builder', 'king-addons'),
            esc_html__('Header & Footer', 'king-addons'),
            'manage_options',
            $menu_slug, // Menu slug points to the custom post type edit screen
            '', // No callback function needed
            KING_ADDONS_URL . 'includes/admin/img/icon-for-header-footer-builder.svg',
            58.72
        );

        // Add 'All Templates' Submenu - this will be the first submenu item
        add_submenu_page(
            $menu_slug, // Parent slug matches the main menu slug
            esc_html__('All Templates', 'king-addons'),
            esc_html__('All Templates', 'king-addons'),
            'edit_posts',
            $menu_slug
        );
    }

    function showAdminPage(): void
    {
        if (!current_user_can('manage_options')) {
            return;
        }

        self::enqueueAdminAssets();

        require_once(KING_ADDONS_PATH . 'includes/admin/layouts/admin-page.php');
    }

    function showSettingsPage(): void
    {
        if (!current_user_can('manage_options')) {
            return;
        }

        require_once(KING_ADDONS_PATH . 'includes/admin/layouts/settings-page.php');

        self::enqueueSettingsAssets();
    }

    function createSettings(): void
    {
        // Register a new setting for "king-addons" page.
        register_setting('king_addons', 'king_addons_options');

        // Register a new section in the "king-addons" page.
        add_settings_section(
            'king_addons_section_widgets',
            '',
            [$this, 'king_addons_section_widgets_callback'],
            'king-addons'
        );

        // Register a new section in the "king-addons" page.
        add_settings_section(
            'king_addons_section_features',
            '',
            [$this, 'king_addons_section_features_callback'],
            'king-addons'
        );

        foreach (ModulesMap::getModulesMapArray()['widgets'] as $widget_id => $widget_array) {
            add_settings_field(
                $widget_id,
                $widget_array['title'],
                '',
                'king-addons',
                'king_addons_section_widgets',
                array(
                    'label_for' => $widget_id,
                    'description' => $widget_array['description'],
                    'docs_link' => $widget_array['docs-link'],
                    'demo_link' => $widget_array['demo-link'],
                    'class' => 'kng-tr kng-tr-' . $widget_id . (!empty($widget_array['has-pro']) ? ' kng-tr-freemium' : '')
                )
            );
        }

        foreach (ModulesMap::getModulesMapArray()['features'] as $feature_id => $feature_array) {
            add_settings_field(
                $feature_id,
                $feature_array['title'],
                '',
                'king-addons',
                'king_addons_section_features',
                array(
                    'label_for' => $feature_id,
                    'description' => $feature_array['description'],
                    'docs_link' => $feature_array['docs-link'],
                    'demo_link' => $feature_array['demo-link'],
                    'class' => 'kng-tr kng-tr-' . $feature_id
                )
            );
        }
    }

    function king_addons_section_widgets_callback($args): void
    {
        ?>
        <h2 id="<?php echo esc_attr($args['id']); ?>"
            class="kng-section-title"><?php esc_html_e('Elements', 'king-addons'); ?></h2>
        <?php
    }

    function king_addons_section_features_callback($args): void
    {
        ?>
        <div class="kng-section-separator"></div>
        <h2 id="<?php echo esc_attr($args['id']); ?>"
            class="kng-section-title"><?php esc_html_e('Features', 'king-addons'); ?></h2>
        <?php
    }

    function enqueueAdminAssets(): void
    {
        wp_enqueue_style('king-addons-admin', KING_ADDONS_URL . 'includes/admin/css/admin.css', '', KING_ADDONS_VERSION);
    }

    function enqueueSettingsAssets(): void
    {
        wp_enqueue_style('king-addons-settings', KING_ADDONS_URL . 'includes/admin/css/settings.css', '', KING_ADDONS_VERSION);
        wp_enqueue_style('wp-color-picker');
        wp_enqueue_script('jquery');
        wp_enqueue_script('wp-color-picker');
        wp_enqueue_script(KING_ADDONS_ASSETS_UNIQUE_KEY . '-wpcolorpicker-wpcolorpicker');
        wp_enqueue_script('king-addons-settings', KING_ADDONS_URL . 'includes/admin/js/settings.js', '', KING_ADDONS_VERSION);
    }
}