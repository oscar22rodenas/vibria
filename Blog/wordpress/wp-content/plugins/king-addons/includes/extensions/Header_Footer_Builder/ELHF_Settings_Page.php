<?php

namespace King_Addons;

if (!defined('ABSPATH')) {
    exit;
}

final class ELHF_Settings_Page
{
    public static array $settings_tabs;

    private static ?ELHF_Settings_Page $instance = null;

    public static function instance(): ?ELHF_Settings_Page
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function __construct()
    {
        if (is_admin() && current_user_can('manage_options')) {
            add_action('admin_menu', [$this, 'registerSettingsPage']);
        }
        add_action('admin_init', [$this, 'initSettings']);
        add_filter('views_edit-king-addons-el-hf', [$this, 'addTabs'], 10, 1);
    }

    public function registerSettingsPage()
    {
        add_submenu_page(
            'edit.php?post_type=king-addons-el-hf',
            esc_html__('Display Settings', 'king-addons'),
            esc_html__('Display Settings', 'king-addons'),
            'manage_options',
            'king-addons-el-hf-settings',
            [$this, 'renderSettingsPage']
        );
    }

    function renderSettingsPage()
    {
        echo '<div class="wrap">';
        echo '<div class="header-wrap">';

        echo '<h1 class="wp-heading-inline">';
        echo esc_html__('Elementor Header & Footer Builder ', 'king-addons');
        echo '</h1>';

        $this->renderTabs();
        echo '</div>';

        if (isset($_GET['page'])) { // PHPCS:Ignore WordPress.Security.NonceVerification.Recommended
            switch ($_GET['page']) { // PHPCS:Ignore WordPress.Security.NonceVerification.Recommended
                case 'king-addons-el-hf-settings':
                    $this->renderSettingsTab();
                    break;
                case 'default':
                    break;
            }
        }

        echo '</div>';
    }

    function renderTabs()
    {
        echo '<div class="nav-tab-wrapper">';

        if (!isset(self::$settings_tabs)) {
            self::$settings_tabs['king_addons_el_hf_templates'] = [
                'name' => esc_html__('All Templates', 'king-addons'),
                'url' => admin_url('edit.php?post_type=king-addons-el-hf'),
            ];
        }

        $tabs = apply_filters('king_addons_el_hf_settings_tabs', self::$settings_tabs);

        foreach ($tabs as $tab_id => $tab) {

            $active_tab = ((isset($_GET['page']) && (str_replace('_', '-', $tab_id)) == $_GET['page']) || (!isset($_GET['page']) && 'king_addons_el_hf_templates' == $tab_id)) ? $tab_id : ''; // PHPCS:Ignore WordPress.Security.NonceVerification.Recommended

            $active = ($active_tab == $tab_id) ? ' nav-tab-active' : '';

            echo '<a href="' . esc_url($tab['url']) . '" class="nav-tab' . esc_attr($active) . '">';
            echo esc_html($tab['name']);
            echo '</a>';
        }

        echo '</div>';
    }

    function renderSettingsTab()
    {
        echo '<form action="options.php" method="post">';
        settings_fields('king-addons-el-hf-ext-options');
        do_settings_sections('king-addons-el-hf-settings');
        submit_button();
        echo '</form>';
    }

    function initSettings()
    {
        register_setting('king-addons-el-hf-ext-options', 'king_addons_el_hf_compatibility_option');
        add_settings_section('king-addons-el-hf-options', esc_html__('Display Settings', 'king-addons'), [$this, 'renderCompatibilityOptionsDescription'], 'king-addons-el-hf-settings');
        add_settings_field('king-addons-el-hf-methods', esc_html__('Methods to display header and footer', 'king-addons'), [$this, 'renderCompatibilityOptionsForm'], 'king-addons-el-hf-settings', 'king-addons-el-hf-options');
    }

    function renderCompatibilityOptionsDescription()
    {
        echo esc_html__('To ensure compatibility with the current theme, two methods are available:', 'king-addons');
    }

    function renderCompatibilityOptionsForm()
    {
        $chosen_option = get_option('king_addons_el_hf_compatibility_option', '1');
        wp_enqueue_style('king-addons-el-hf-admin', KING_ADDONS_URL . 'includes/extensions/Header_Footer_Builder/admin.css', '', KING_ADDONS_VERSION);
        ?>
        <label>
            <input type="radio" name="king_addons_el_hf_compatibility_option"
                   value=1 <?php checked($chosen_option, 1); ?>>
            <!--suppress HtmlUnknownTag -->
            <div class="king-addons-el-hf-radio-options"><?php esc_html_e('Method 1 (Recommended)', 'king-addons'); ?></div>
            <!--suppress HtmlUnknownTag -->
            <p class="description"><?php esc_html_e('This method replaces the theme header (header.php) and footer (footer.php) templates with custom templates. This option works well with most themes by default.', 'king-addons'); ?></p>
            <br>
        </label>
        <label>
            <input type="radio" name="king_addons_el_hf_compatibility_option"
                   value=2 <?php checked($chosen_option, 2); ?>>
            <!--suppress HtmlUnknownTag -->
            <div class="king-addons-el-hf-radio-options"><?php esc_html_e('Method 2', 'king-addons'); ?></div>
            <!--suppress HtmlUnknownTag -->
            <p class="description">
                <?php echo esc_html__('If there are issues with the header or footer templates, this alternative method can be used. It hides the theme header and footer using CSS (display: none;) and displays custom templates instead.', 'king-addons'); ?>
            </p>
        </label>
        <?php
    }

    function addTabs($views)
    {
        $this->renderTabs();
        return $views;
    }
}

new ELHF_Settings_Page();