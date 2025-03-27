<?php

namespace King_Addons;

use Elementor\Plugin;

if (!defined('ABSPATH')) {
    exit;
}

final class Popup_Builder
{
    private static ?Popup_Builder $instance = null;
    private static ?Plugin $elementor_instance;

    public static function instance(): ?Popup_Builder
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function __construct()
    {
        add_action('init', [$this, 'register_templates_library_cpt']);
        add_action('template_redirect', [$this, 'block_template_frontend']);
        add_action('current_screen', [$this, 'redirect_to_options_page']);

        add_action('elementor/documents/register', [$this, 'register_elementor_document_type']);

        add_action('wp_enqueue_scripts', [$this, 'enqueue_styles'], 998);
        add_action('elementor/editor/before_enqueue_scripts', [$this, 'enqueueScriptPreviewHandler'], 988);
        add_action('elementor/frontend/before_enqueue_scripts', [$this, 'enqueue_scripts'], 998);

        self::$elementor_instance = Plugin::instance();
        add_action('template_include', [$this, 'set_post_type_template'], 9999);
        add_action('wp_footer', [$this, 'render_popups']);

        add_action('wp_ajax_king_addons_pb_save_template_conditions', [$this, 'king_addons_pb_save_template_conditions']);
        add_action('wp_ajax_king_addons_pb_create_template', [$this, 'king_addons_pb_create_template']);
        add_action('wp_ajax_king_addons_pb_delete_template', [$this, 'king_addons_pb_delete_template']);

    }

    public function enqueueScriptPreviewHandler(): void
    {
        wp_enqueue_script('king-addons-popup-builder-popup-preview-script', KING_ADDONS_URL . 'includes/extensions/Popup_Builder/preview-handler.js', array('jquery'), KING_ADDONS_VERSION, true);
    }


    public function register_elementor_document_type($documents_manager): void
    {
        require_once(KING_ADDONS_PATH . 'includes/extensions/Popup_Builder/Popup_Module.php');

        if (king_addons_freemius()->can_use_premium_code__premium_only() && defined('KING_ADDONS_PRO_PATH')) {
            require_once(KING_ADDONS_PRO_PATH . 'includes/extensions/Popup_Builder_Pro/Popup_Module_Pro.php');
            $documents_manager->register_document_type('king-addons-pb-popups', 'King_Addons\King_Addons_Popup_Module_Pro');
        } else {
            $documents_manager->register_document_type('king-addons-pb-popups', 'King_Addons\King_Addons_Popup_Module');
        }
    }

    public function enqueue_styles(): void
    {
        wp_enqueue_style('king-addons-popup-builder-popup-module-style', KING_ADDONS_URL . 'includes/extensions/Popup_Builder/popup-module.css', '', KING_ADDONS_VERSION);
    }

    public function enqueue_scripts(): void
    {
        wp_enqueue_script('king-addons-popup-builder-popup-module-script', KING_ADDONS_URL . 'includes/extensions/Popup_Builder/popup-module.js', [
            'jquery',
            'elementor-frontend'
        ], KING_ADDONS_VERSION, true);
    }

    public function render_popups(): void
    {
        $conditions = json_decode(get_option('king_addons_pb_popup_conditions'), true);

        if (!empty($conditions)) {
            $conditions = self::reverse_template_conditions($conditions);

            if (isset($conditions['global'])) {
                self::display_popups_by_location($conditions, 'global');
            }

            if (king_addons_freemius()->can_use_premium_code__premium_only()) {
                self::archive_pages_popup_conditions($conditions);
                self::single_pages_popup_conditions($conditions);
            }

            if (!wp_script_is(KING_ADDONS_ASSETS_UNIQUE_KEY . '-' . 'perfectscrollbar' . '-' . 'perfectscrollbar')) {
                wp_enqueue_script(KING_ADDONS_ASSETS_UNIQUE_KEY . '-' . 'perfectscrollbar' . '-' . 'perfectscrollbar', '', '', KING_ADDONS_VERSION);
            }
        }
    }

    public static function display_popups_by_location($conditions, $page): void
    {
        foreach ($conditions[$page] as $popup) {
            self::render_popup_content($popup);
        }
    }

    public static function render_popup_content($slug): void
    {

        $template_id = self::get_template_id($slug);

        // Check if WPML is active. Find WPML Object ID for current page.
        /** @noinspection SpellCheckingInspection */
        if (defined('ICL_SITEPRESS_VERSION')) {
            $default_lang = apply_filters('wpml_default_language', '');
            $current_lang = apply_filters('wpml_current_language', '');

            if ($default_lang !== $current_lang) {
                $template_id = apply_filters('wpml_object_id', $template_id, 'king_addons_ext_pb', true, $current_lang);
            }
        }

        $get_settings = self::get_template_settings($slug);
        $get_elementor_content = self::$elementor_instance->frontend->get_builder_content($template_id);

        if ('' === $get_elementor_content) {
            return;
        }

        $get_encoded_settings = !empty($get_settings) ? wp_json_encode($get_settings) : '[]';
        $template_settings_attr = "data-settings='" . esc_attr($get_encoded_settings) . "'";

        if (!self::check_available_user_roles($get_settings['popup_show_for_roles'])) {
            return;
        }

        if (!self::$elementor_instance->preview->is_preview_mode()) {
            echo '<div id="king-addons-pb-popup-id-' . esc_attr($template_id) . '" class="king-addons-pb-template-popup" ' . $template_settings_attr . '>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            echo '<div class="king-addons-pb-template-popup-inner">';
            echo '<div class="king-addons-pb-popup-overlay"></div>';
            echo '<div class="king-addons-pb-popup-container">';

            if (Plugin::$instance->experiments->is_feature_active('e_font_icon_svg')) {
                echo '<div class="king-addons-pb-popup-close-btn"><i class="fa fa-times"></i></div>';
            } else {
                echo '<div class="king-addons-pb-popup-close-btn"><i class="eicon-close"></i></div>';
            }

            echo '<div class="king-addons-pb-popup-container-inner">';
            echo $get_elementor_content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            echo '</div>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
        }
    }

    public static function get_template_settings($slug): array
    {
        $settings = [];
        $defaults = [];

        $template_id = self::get_template_id($slug);
        /** @noinspection DuplicatedCode */
        $meta_settings = get_post_meta($template_id, '_elementor_page_settings', true);

        $popup_defaults = [
            'popup_trigger' => 'load',
            'popup_load_delay' => 1,
            'popup_scroll_progress' => 10,
            'popup_inactivity_time' => 15,
            'popup_element_scroll' => '',
            'popup_custom_trigger' => '',
            'popup_specific_date' => date('Y-m-d H:i', strtotime('+1 month') + (get_option('gmt_offset') * HOUR_IN_SECONDS)),
            'popup_stop_after_date' => false,
            'popup_stop_after_date_select' => date('Y-m-d H:i', strtotime('+1 day') + (get_option('gmt_offset') * HOUR_IN_SECONDS)),
            'popup_show_again_delay' => 1,
            'popup_disable_esc_key' => false,
            'popup_automatic_close_switch' => false,
            'popup_automatic_close_delay' => 10,
            'popup_animation' => 'fade',
            'popup_animation_duration' => 1,
            'popup_show_for_roles' => '',
            'popup_show_via_referral' => false,
            'popup_referral_keyword' => '',
            'popup_display_as' => 'modal',
            'popup_show_on_device' => true,
            'popup_show_on_device_mobile' => true,
            'popup_show_on_device_tablet' => true,
            'popup_disable_page_scroll' => true,
            'popup_overlay_disable_close' => false,
            'popup_close_button_display_delay' => 0,
        ];

        if (strpos($slug, 'popup')) {
            $defaults = $popup_defaults;
        }

        foreach ($defaults as $option => $value) {
            if (isset($meta_settings[$option])) {
                $settings[$option] = $meta_settings[$option];
            }
        }

        return array_merge($defaults, $settings);
    }

    public static function check_available_user_roles($selected_roles): bool
    {
        if (empty($selected_roles)) {
            return true;
        }

        $current_user = wp_get_current_user();

        if (!empty($current_user->roles)) {
            $role = $current_user->roles[0];
        } else {
            $role = 'guest';
        }

        if (in_array($role, $selected_roles)) {
            return true;
        }

        return false;
    }

    public function reverse_template_conditions($conditions): array
    {
        $reverse = [];

        foreach ($conditions as $key => $condition) {
            foreach ($condition as $location) {
                if (!isset($reverse[$location])) {
                    $reverse[$location] = [$key];
                } else {
                    $reverse[$location][] = $key;
                }
            }
        }

        return $reverse;
    }

    public function set_post_type_template($template)
    {
        if (is_singular('king_addons_ext_pb')) {
            if ('king-addons-pb-popups' === self::get_elementor_template_type(get_the_ID()) && self::$elementor_instance->preview->is_preview_mode()) {
                $template = KING_ADDONS_PATH . 'includes/extensions/Popup_Builder/preview.php';
            }
            return $template;
        }
        return $template;
    }

    public function block_template_frontend(): void
    {
        if (is_singular('king_addons_ext_pb') && !current_user_can('edit_posts')) {
            wp_redirect(site_url(), 301);
            die;
        }
    }

    public static function get_elementor_template_type($id)
    {
        $post_meta = get_post_meta($id);
        return $post_meta['_elementor_template_type'][0] ?? false;
    }

    public function redirect_to_options_page(): void
    {
        if (get_current_screen()->post_type == 'king_addons_ext_pb' && isset($_GET['action']) && $_GET['action'] == 'edit') {
            wp_redirect('admin.php?page=king-addons-popup-builder');
        }
    }

    public function register_templates_library_cpt(): void
    {

        $args = array(
            'label' => esc_html__('King Addons Templates', 'king-addons'),
            'public' => true,
            'rewrite' => false,
            'show_ui' => true,
            'show_in_menu' => false,
            'show_in_nav_menus' => false,
            'exclude_from_search' => true,
            'capability_type' => 'post',
            'supports' => ['title', 'elementor'],
            'hierarchical' => false,
        );

        register_post_type('king_addons_ext_pb', $args);

        $tax_args = [
            'hierarchical' => true,
            'show_ui' => true,
            'show_in_nav_menus' => false,
            'show_admin_column' => true,
            'query_var' => is_admin(),
            'rewrite' => false,
            'public' => false,
        ];

        register_taxonomy('king_addons_pb_template_type', 'king_addons_ext_pb', $tax_args);

    }

    public function king_addons_pb_delete_template(): void
    {
        /** @noinspection DuplicatedCode */
        $nonce = $_POST['nonce'];

        if (!wp_verify_nonce($nonce, 'delete_post-' . $_POST['template_slug']) || !current_user_can('manage_options')) {
            exit;
        }

        $template_slug = isset($_POST['template_slug']) ? sanitize_text_field(wp_unslash($_POST['template_slug'])) : '';
        $template_library = isset($_POST['template_library']) ? sanitize_text_field(wp_unslash($_POST['template_library'])) : '';

        $post = get_page_by_path($template_slug, OBJECT, $template_library);

        if (get_post_type($post->ID) == 'king_addons_ext_pb' || get_post_type($post->ID) == 'elementor_library') {
            wp_delete_post($post->ID, true);
        }
    }

    public function king_addons_pb_save_template_conditions(): void
    {

        $nonce = $_POST['nonce'];

        if (!wp_verify_nonce($nonce, 'king-addons-popup-builder-admin-script') || !current_user_can('manage_options')) {
            exit;
        }

        if (isset($_POST['king_addons_pb_popup_conditions'])) {
            update_option('king_addons_pb_popup_conditions', $this->sanitize_conditions($_POST['king_addons_pb_popup_conditions']));  // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        }
    }

    public function sanitize_conditions($data)
    {
        return wp_unslash(json_encode(array_filter(json_decode(stripcslashes($data), true))));
    }

    public function king_addons_pb_create_template(): void
    {
        $nonce = $_POST['nonce'];

        if (!wp_verify_nonce($nonce, 'king-addons-popup-builder-admin-script') || !current_user_can('manage_options')) {
            exit;
        }

        /** @noinspection DuplicatedCode */
        $user_template_type = isset($_POST['user_template_type']) ? sanitize_text_field(wp_unslash($_POST['user_template_type'])) : false;
        $user_template_library = isset($_POST['user_template_library']) ? sanitize_text_field(wp_unslash($_POST['user_template_library'])) : false;
        $user_template_title = isset($_POST['user_template_title']) ? sanitize_text_field(wp_unslash($_POST['user_template_title'])) : false;
        $user_template_slug = isset($_POST['user_template_slug']) ? sanitize_text_field(wp_unslash($_POST['user_template_slug'])) : false;

        $check_post_type = ($user_template_library == 'king_addons_ext_pb' || $user_template_library == 'elementor_library');

        if ($user_template_title && $check_post_type) {

            $template_id = wp_insert_post(array(
                'post_type' => $user_template_library,
                'post_title' => $user_template_title,
                'post_name' => $user_template_slug,
                'post_content' => '',
                'post_status' => 'publish'
            ));

            if ('king_addons_ext_pb' === $_POST['user_template_library']) {

                wp_set_object_terms($template_id, [$user_template_type, 'user'], 'king_addons_pb_template_type');

                if ('popup' === $_POST['user_template_type']) {
                    update_post_meta($template_id, '_elementor_template_type', 'king-addons-pb-popups');
                } else {
                    update_post_meta($template_id, '_elementor_template_type', 'king-addons-pb-theme-builder');
                    update_post_meta($template_id, '_king_addons_pb_template_type', $user_template_type);
                }
            } else {
                update_post_meta($template_id, '_elementor_template_type', 'page');
            }

            update_post_meta($template_id, '_wp_page_template', 'elementor_canvas');

            echo esc_html($template_id);
        }
    }

    public static function renderPopupBuilder(): void
    {
        $screen = get_current_screen();
        if ($screen->id === 'toplevel_page_king-addons-popup-builder') {
            wp_enqueue_style('king-addons-popup-builder-admin-style', KING_ADDONS_URL . 'includes/extensions/Popup_Builder/admin.css', '', KING_ADDONS_VERSION);
            wp_enqueue_script('jquery');
            wp_enqueue_script('king-addons-popup-builder-admin-script', KING_ADDONS_URL . 'includes/extensions/Popup_Builder/admin.js', array('jquery'), KING_ADDONS_VERSION);
            wp_localize_script(
                'king-addons-popup-builder-admin-script',
                'KingAddonsPopupBuilderOptions',
                [
                    'nonce' => wp_create_nonce('king-addons-popup-builder-admin-script'),
                ]
            );
        }
        ?>
        <div class="wrap king-addons-pb-settings-page-wrap">
            <div class="king-addons-pb-settings-page-header">
                <h1><?php esc_html_e('Elementor Popup Builder', 'king-addons'); ?></h1>
                <p><?php esc_html_e('Design and customize eye-catching popups, ideal for creating promotional messages, announcements, or subscription forms that capture visitor attention', 'king-addons'); ?></p>
                <div class="king-addons-pb-preview-buttons">
                    <div class="king-addons-pb-user-template">
                        <span><?php esc_html_e('Create Popup', 'king-addons'); ?></span>
                        <span class="plus-icon">+</span>
                    </div>
                    <?php if (!king_addons_freemius()->can_use_premium_code__premium_only()): ?>
                        <div class="kng-promo-btn-wrap">
                            <a href="https://kingaddons.com/pricing/?rel=king-addons-popup-builder" target="_blank">
                                <div class="kng-promo-btn-txt">
                                    <?php esc_html_e('Unlock Premium Features & 600+ Templates Today!', 'king-addons'); ?>
                                </div>
                                <img width="16px"
                                     src="<?php echo esc_url(KING_ADDONS_URL) . 'includes/admin/img/share-v2.svg'; ?>"
                                     alt="<?php echo esc_html__('Open link in the new tab', 'king-addons'); ?>">
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="king-addons-pb-settings-page">
                <!--suppress HtmlUnknownTarget -->
                <form method="post" action="options.php">
                    <input type="hidden" name="king_addons_pb_template" id="king_addons_pb_template" value="">
                    <?php self::render_conditions_popup(); ?>
                    <?php self::render_create_template_popup(); ?>
                    <input type="hidden" name="king_addons_pb_popup_conditions" id="king_addons_pb_popup_conditions"
                           value="<?php echo esc_attr(get_option('king_addons_pb_popup_conditions', '[]')); ?>">
                    <?php self::render_theme_builder_templates('popup'); ?>
                </form>
            </div>
        </div>
        <?php
    }

    public static function render_conditions_popup(): void
    {
        $active_tab = isset($_GET['tab']) ? sanitize_text_field(wp_unslash($_GET['tab'])) : 'king_addons_pb_tab_header';
        ?>
        <div class="king-addons-pb-condition-popup-wrap king-addons-pb-admin-popup-wrap">
            <div class="king-addons-pb-condition-popup king-addons-pb-admin-popup">
                <header>
                    <h2><?php esc_html_e('Where would you like the popup to be displayed?', 'king-addons'); ?></h2>
                    <p>
                        <?php esc_html_e('Define the conditions that specify where the popup will be applied across your site. ', 'king-addons'); ?>
                        <br>
                        <?php esc_html_e('For instance, select Entire Site to display the template on all pages.', 'king-addons');

                        if (!king_addons_freemius()->can_use_premium_code__premium_only()) {
                            echo '<span><br>';
                            echo esc_html__('All conditions are available in the ', 'king-addons');
                            echo '<strong><a href="https://kingaddons.com/pricing/?utm_source=kng-module-popup-builder-change-conditions-window" target="_blank">';
                            echo esc_html__('Pro version.', 'king-addons');
                            echo '</a></strong></span>';
                        }
                        ?>
                    </p>
                </header>
                <span class="close-popup dashicons dashicons-no-alt"></span>
                <div class="king-addons-pb-conditions-wrap">
                    <div class="king-addons-pb-conditions-sample">
                        <?php if (king_addons_freemius()->can_use_premium_code__premium_only()) : ?>

                            <!--suppress HtmlFormInputWithoutLabel -->
                            <select name="global_condition_select" class="global-condition-select">
                                <option value="global"><?php esc_html_e('Entire Site', 'king-addons'); ?></option>
                                <option value="archive"><?php esc_html_e('Archives', 'king-addons'); ?></option>
                                <option value="single"><?php esc_html_e('Singular', 'king-addons'); ?></option>
                            </select>

                            <!--suppress HtmlFormInputWithoutLabel -->
                            <select name="archives_condition_select" class="archives-condition-select">
                                <?php if ('king_addons_pb_tab_header' === $active_tab || 'king_addons_pb_tab_footer' === $active_tab) : ?>
                                    <optgroup label="<?php esc_html_e('Archives', 'king-addons'); ?>">
                                        <option value="all_archives"><?php esc_html_e('All Archives', 'king-addons'); ?></option>
                                        <option value="posts"><?php esc_html_e('Posts Archive', 'king-addons'); ?></option>
                                        <?php
                                        $custom_post_types = self::get_custom_types_of('post');
                                        foreach ($custom_post_types as $key => $value) {
                                            if ('e-landing-page' === $key) {
                                                continue;
                                            }

                                            if (king_addons_freemius()->can_use_premium_code__premium_only() || 'product' === $key) {
                                                echo '<option value="' . esc_attr($key) . '">' . $value . ' ' . esc_html__('Archive', 'king-addons') . '</option>';
                                            } else {
                                                echo '<option value="pro-' . esc_attr(substr($key, 0, 3)) . '">' . $value . ' ' . esc_html__('Archive (PRO)', 'king-addons') . '</option>';
                                            }
                                        }
                                        ?>
                                        <option value="author"><?php esc_html_e('Author Archive', 'king-addons'); ?></option>
                                        <option value="date"><?php esc_html_e('Date Archive', 'king-addons'); ?></option>
                                        <option value="search"><?php esc_html_e('Search Results', 'king-addons'); ?></option>
                                    </optgroup>

                                    <optgroup label="<?php esc_html_e('Taxonomy Archives', 'king-addons'); ?>">
                                        <option value="categories"
                                                class="custom-ids"><?php esc_html_e('Post Categories', 'king-addons'); ?></option>
                                        <option value="tags"
                                                class="custom-ids"><?php esc_html_e('Post Tags', 'king-addons'); ?></option>
                                        <?php
                                        $custom_taxonomies = self::get_custom_types_of('tax');
                                        foreach ($custom_taxonomies as $key => $value) {
                                            if (king_addons_freemius()->can_use_premium_code__premium_only() || 'product_cat' === $key || 'product_tag' === $key) {
                                                echo '<option value="' . esc_attr($key) . '" class="custom-type-ids">' . esc_html($value) . '</option>';
                                            } else {
                                                echo '<option value="pro-' . esc_attr(substr($key, 0, 3)) . '" class="custom-type-ids">' . esc_html($value) . ' (PRO)</option>';
                                            }
                                        }
                                        ?>
                                    </optgroup>
                                <?php else: ?>
                                    <?php if ('king_addons_pb_tab_archive' === $active_tab) : ?>
                                        <optgroup label="<?php esc_html_e('Archives', 'king-addons'); ?>">
                                            <option value="all_archives"><?php esc_html_e('All Archives', 'king-addons'); ?></option>
                                            <option value="posts"><?php esc_html_e('Posts Archive', 'king-addons'); ?></option>
                                            <?php
                                            $custom_post_types = self::get_custom_types_of('post');
                                            foreach ($custom_post_types as $key => $value) {
                                                if ('product' === $key || 'e-landing-page' === $key) {
                                                    continue;
                                                }

                                                if (king_addons_freemius()->can_use_premium_code__premium_only()) {
                                                    echo '<option value="' . esc_attr($key) . '">' . $value . ' ' . esc_html__('Archive', 'king-addons') . '</option>';
                                                } else {
                                                    echo '<option value="pro-' . esc_attr(substr($key, 0, 3)) . '">' . $value . ' ' . esc_html__('Archive (PRO)', 'king-addons') . '</option>';
                                                }
                                            }
                                            ?>
                                            <option value="author"><?php esc_html_e('Author Archive', 'king-addons'); ?></option>
                                            <option value="date"><?php esc_html_e('Date Archive', 'king-addons'); ?></option>
                                            <option value="search"><?php esc_html_e('Search Results', 'king-addons'); ?></option>
                                        </optgroup>

                                        <optgroup label="<?php esc_html_e('Taxonomy Archives', 'king-addons'); ?>">
                                            <option value="categories"
                                                    class="custom-ids"><?php esc_html_e('Post Categories', 'king-addons'); ?></option>
                                            <option value="tags"
                                                    class="custom-ids"><?php esc_html_e('Post Tags', 'king-addons'); ?></option>
                                            <?php
                                            $custom_taxonomies = self::get_custom_types_of('tax');
                                            foreach ($custom_taxonomies as $key => $value) {
                                                if ('product_cat' === $key || 'product_tag' === $key) {
                                                    continue;
                                                }

                                                if (king_addons_freemius()->can_use_premium_code__premium_only()) {
                                                    echo '<option value="' . esc_attr($key) . '" class="custom-type-ids">' . esc_html($value) . '</option>';
                                                } else {
                                                    echo '<option value="pro-' . esc_attr(substr($key, 0, 3)) . '" class="custom-type-ids">' . esc_html($value) . ' (PRO)</option>';
                                                }
                                            }
                                            ?>
                                        </optgroup>
                                    <?php elseif ('king_addons_pb_tab_product_archive' === $active_tab): ?>
                                        <option value="products"><?php esc_html_e('Products Archive', 'king-addons'); ?></option>
                                        <option value="product_cat"
                                                class="custom-type-ids"><?php esc_html_e('Products Categories', 'king-addons'); ?></option>
                                        <option value="product_tag"
                                                class="custom-type-ids"><?php esc_html_e('Products Tags', 'king-addons'); ?></option>
                                        <option value="product_search"><?php esc_html_e('Products Search', 'king-addons'); ?></option>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </select>

                            <!--suppress HtmlFormInputWithoutLabel -->
                            <select name="singles_condition_select" class="singles-condition-select">
                                <?php if ('king_addons_pb_tab_header' === $active_tab || 'king_addons_pb_tab_footer' === $active_tab) : ?>
                                    <option value="front_page"><?php esc_html_e('Front Page', 'king-addons'); ?></option>
                                    <option value="page_404"><?php esc_html_e('404 Page', 'king-addons'); ?></option>
                                    <option value="pages"
                                            class="custom-ids"><?php esc_html_e('Pages', 'king-addons'); ?></option>
                                    <option value="posts"
                                            class="custom-ids"><?php esc_html_e('Posts', 'king-addons'); ?></option>
                                    <?php
                                    $custom_post_types = self::get_custom_types_of('post');
                                    foreach ($custom_post_types as $key => $value) {
                                        if ('e-landing-page' === $key) {
                                            continue;
                                        }

                                        if (king_addons_freemius()->can_use_premium_code__premium_only() || 'product' === $key) {
                                            echo '<option value="' . esc_attr($key) . '" class="custom-type-ids">' . esc_html($value) . '</option>';
                                        } else {
                                            echo '<option value="pro-' . esc_attr(substr($key, 0, 3)) . '" class="custom-type-ids">' . esc_html($value) . ' (PRO)</option>';
                                        }
                                    }
                                    ?>
                                <?php else: ?>
                                    <?php if ('king_addons_pb_tab_single' === $active_tab) : ?>
                                        <option value="front_page"><?php esc_html_e('Front Page', 'king-addons'); ?></option>
                                        <option value="page_404"><?php esc_html_e('404 Page', 'king-addons'); ?></option>
                                        <option value="pages"
                                                class="custom-ids"><?php esc_html_e('Pages', 'king-addons'); ?></option>
                                        <option value="posts"
                                                class="custom-ids"><?php esc_html_e('Posts', 'king-addons'); ?></option>

                                        <?php
                                        $custom_post_types = self::get_custom_types_of('post');
                                        foreach ($custom_post_types as $key => $value) {
                                            if ('product' === $key || 'e-landing-page' === $key) {
                                                continue;
                                            }

                                            if (king_addons_freemius()->can_use_premium_code__premium_only()) {
                                                echo '<option value="' . esc_attr($key) . '" class="custom-type-ids">' . esc_html($value) . '</option>';
                                            } else {
                                                echo '<option value="pro-' . esc_attr(substr($key, 0, 3)) . '" class="custom-type-ids">' . esc_html($value) . ' (PRO)</option>';
                                            }
                                        }
                                        ?>
                                    <?php elseif ('king_addons_pb_tab_product_single' === $active_tab): ?>
                                        <option value="product"
                                                class="custom-product-ids custom-type-ids"><?php esc_html_e('Products', 'king-addons'); ?></option>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </select>

                            <!--suppress HtmlFormInputWithoutLabel -->
                            <input type="text"
                                   placeholder="<?php esc_html_e('Enter comma separated IDs', 'king-addons'); ?>"
                                   name="condition_input_ids" class="king-addons-pb-condition-input-ids">
                            <span class="king-addons-pb-delete-template-conditions dashicons dashicons-no-alt"></span>

                        <?php else: ?>
                            <!--suppress HtmlFormInputWithoutLabel -->
                            <select name="global_condition_select" class="global-condition-select">
                                <option value="global"><?php esc_html_e('Entire Site', 'king-addons'); ?></option>
                                <option value="archive"><?php esc_html_e('Archives (Pro)', 'king-addons'); ?></option>
                                <option value="single"><?php esc_html_e('Singular (Pro)', 'king-addons'); ?></option>
                            </select>

                            <!--suppress HtmlFormInputWithoutLabel -->
                            <select name="archives_condition_select" class="archives-condition-select">
                                <?php if ('king_addons_pb_tab_header' === $active_tab || 'king_addons_pb_tab_footer' === $active_tab) : ?>
                                    <optgroup label="<?php esc_html_e('Archives', 'king-addons'); ?>">
                                        <option value="all_archives"><?php esc_html_e('All Archives (Pro)', 'king-addons'); ?></option>
                                        <option value="posts"><?php esc_html_e('Posts Archive (Pro)', 'king-addons'); ?></option>
                                        <option value="author"><?php esc_html_e('Author Archive (Pro)', 'king-addons'); ?></option>
                                        <option value="date"><?php esc_html_e('Date Archive (Pro)', 'king-addons'); ?></option>
                                        <option value="search"><?php esc_html_e('Search Results (Pro)', 'king-addons'); ?></option>
                                        <option value="categories"
                                                class="custom-ids"><?php esc_html_e('Post Categories (Pro)', 'king-addons'); ?></option>
                                        <option value="tags"
                                                class="custom-ids"><?php esc_html_e('Post Tags (Pro)', 'king-addons'); ?></option>
                                    </optgroup>
                                    <optgroup label="<?php esc_html_e('WooCommerce Archives', 'king-addons'); ?>">
                                        <option value="products"
                                                class="custom-ids"><?php esc_html_e('Products Archive (Pro)', 'king-addons'); ?></option>
                                        <option value="products_cats"
                                                class="custom-ids"><?php esc_html_e('Product Categories (Pro)', 'king-addons'); ?></option>
                                        <option value="product_tags"
                                                class="custom-ids"><?php esc_html_e('Product Tags (Pro)', 'king-addons'); ?></option>
                                    </optgroup>
                                    <optgroup label="<?php esc_html_e('Custom Post Type Archives', 'king-addons'); ?>">
                                        <?php
                                        $custom_post_types = self::get_custom_types_of('post');
                                        foreach ($custom_post_types as $key => $value) {
                                            if ('product' === $key || 'e-landing-page' === $key) {
                                                continue;
                                            }

                                            echo '<option value="' . esc_attr(substr($key, 0, 3)) . '" class="custom-type-ids">' . esc_html($value) . ' (PRO)</option>';
                                        }

                                        $custom_taxonomies = self::get_custom_types_of('tax');
                                        foreach ($custom_taxonomies as $key => $value) {
                                            if ('product_cat' === $key || 'product_tag' === $key) {
                                                continue;
                                            }
                                            echo '<option value="' . esc_attr($key) . '" class="custom-type-ids">' . esc_html($value) . ' (PRO)</option>';
                                        }
                                        ?>
                                    </optgroup>
                                <?php else: ?>
                                    <?php if ('king_addons_pb_tab_archive' === $active_tab) : ?>
                                        <optgroup label="<?php esc_html_e('Archives', 'king-addons'); ?>">
                                            <option value="all_archives"><?php esc_html_e('All Archives', 'king-addons'); ?></option>
                                            <option value="posts"><?php esc_html_e('Posts Archive', 'king-addons'); ?></option>
                                            <option value="author"><?php esc_html_e('Author Archive', 'king-addons'); ?></option>
                                            <option value="date"><?php esc_html_e('Date Archive', 'king-addons'); ?></option>
                                            <option value="search"><?php esc_html_e('Search Results', 'king-addons'); ?></option>
                                            <option value="categories"
                                                    class="custom-ids"><?php esc_html_e('Post Categories', 'king-addons'); ?></option>
                                            <option value="tags"
                                                    class="custom-ids"><?php esc_html_e('Post Tags', 'king-addons'); ?></option>
                                        </optgroup>
                                        <optgroup
                                                label="<?php esc_html_e('Custom Post Type Archives', 'king-addons'); ?>">
                                            <?php
                                            $custom_post_types = self::get_custom_types_of('post');
                                            foreach ($custom_post_types as $key => $value) {
                                                if ('product' === $key || 'e-landing-page' === $key) {
                                                    continue;
                                                }

                                                echo '<option value="' . esc_attr(substr($key, 0, 3)) . '" class="custom-type-ids">' . esc_html($value) . ' (PRO)</option>';
                                            }
                                            ?>
                                            <?php
                                            $custom_taxonomies = self::get_custom_types_of('tax');
                                            foreach ($custom_taxonomies as $key => $value) {
                                                if ('product_cat' === $key || 'product_tag' === $key) {
                                                    continue;
                                                }

                                                echo '<option value="' . esc_attr($key) . '" class="custom-type-ids">' . esc_html($value) . ' (PRO)</option>';
                                            }
                                            ?>
                                        </optgroup>
                                    <?php elseif ('king_addons_pb_tab_product_archive' === $active_tab): ?>
                                        <option value="products"><?php esc_html_e('Products Archive', 'king-addons'); ?></option>
                                        <option value="product_cat"
                                                class="custom-type-ids"><?php esc_html_e('Products Categories (Pro)', 'king-addons'); ?></option>
                                        <option value="product_tag"
                                                class="custom-type-ids"><?php esc_html_e('Products Tags (Pro)', 'king-addons'); ?></option>
                                        <option value="product_search"><?php esc_html_e('Products Search (Pro)', 'king-addons'); ?></option>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </select>

                            <!--suppress HtmlFormInputWithoutLabel -->
                            <select name="singles_condition_select" class="singles-condition-select">
                                <?php if ('king_addons_pb_tab_header' === $active_tab || 'king_addons_pb_tab_footer' === $active_tab) : ?>
                                    <option value="front_page"><?php esc_html_e('Front Page (Pro)', 'king-addons'); ?></option>
                                    <option value="page_404"><?php esc_html_e('404 Page (Pro)', 'king-addons'); ?></option>
                                    <option value="pages"
                                            class="custom-ids"><?php esc_html_e('Pages (Pro)', 'king-addons'); ?></option>
                                    <option value="posts"
                                            class="custom-ids"><?php esc_html_e('Posts (Pro)', 'king-addons'); ?></option>
                                    <option value="product"
                                            class="custom-ids"><?php esc_html_e('Product (Pro)', 'king-addons'); ?></option>
                                    <?php
                                    $custom_post_types = self::get_custom_types_of('post');
                                    foreach ($custom_post_types as $key => $value) {
                                        if ('product' === $key || 'e-landing-page' === $key) {
                                            continue;
                                        }

                                        echo '<option value="' . esc_attr($key) . '" class="custom-type-ids">' . esc_html($value) . ' (PRO)</option>';
                                    }
                                    ?>
                                <?php else: ?>
                                    <?php if ('king_addons_pb_tab_single' === $active_tab) : ?>
                                        <option value="front_page"><?php esc_html_e('Front Page', 'king-addons'); ?></option>
                                        <option value="page_404"><?php esc_html_e('404 Page', 'king-addons'); ?></option>
                                        <option value="pages"
                                                class="custom-ids"><?php esc_html_e('Pages', 'king-addons'); ?></option>
                                        <option value="posts"
                                                class="custom-ids"><?php esc_html_e('Posts', 'king-addons'); ?></option>

                                        <?php
                                        $custom_post_types = self::get_custom_types_of('post');
                                        foreach ($custom_post_types as $key => $value) {
                                            if ('product' === $key || 'e-landing-page' === $key) {
                                                continue;
                                            }

                                            echo '<option value="' . esc_attr($key) . '" class="custom-type-ids">' . esc_html($value) . ' (PRO)</option>';
                                        }
                                        ?>
                                    <?php elseif ('king_addons_pb_tab_product_single' === $active_tab): ?>
                                        <option value="product"
                                                class="custom-type-ids"><?php esc_html_e('Products', 'king-addons'); ?></option>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </select>

                            <!--suppress HtmlFormInputWithoutLabel -->
                            <input type="text"
                                   placeholder="<?php esc_html_e('Enter comma separated IDs (Pro)', 'king-addons'); ?>"
                                   name="condition_input_ids" class="king-addons-pb-condition-input-ids">
                            <span class="king-addons-pb-delete-template-conditions dashicons dashicons-no-alt"></span>

                        <?php endif; ?>
                    </div>
                </div>
                <span class="king-addons-pb-add-conditions"><?php esc_html_e('+ Add Conditions', 'king-addons'); ?></span>
                <span class="king-addons-pb-save-conditions"><?php esc_html_e('Save Conditions', 'king-addons'); ?></span>
            </div>
        </div>

        <?php
    }

    public static function render_create_template_popup(): void
    {
        ?>
        <div class="king-addons-pb-user-template-popup-wrap king-addons-pb-admin-popup-wrap">
            <div class="king-addons-pb-user-template-popup king-addons-pb-admin-popup">
                <header>
                    <h2><?php esc_html_e('Creating Popup', 'king-addons'); ?></h2>
                    <p><?php esc_html_e('Design and customize eye-catching popups, ideal for creating promotional messages, announcements, or subscription forms that capture visitor attention', 'king-addons'); ?></p>
                </header>
                <!--suppress HtmlFormInputWithoutLabel -->
                <input type="text" name="user_template_title" class="king-addons-pb-user-template-title"
                       placeholder="<?php esc_html_e('Enter Popup Title', 'king-addons'); ?>">
                <input type="hidden" name="user_template_type" class="user-template-type">
                <span class="king-addons-pb-create-template"><?php esc_html_e('Create Popup', 'king-addons'); ?></span>
                <span class="close-popup dashicons dashicons-no-alt"></span>
            </div>
        </div>
        <?php
    }

    public static function get_custom_types_of($query, $exclude_defaults = true): array
    {
        /** @noinspection DuplicatedCode */
        if ('tax' === $query) {
            $custom_types = get_taxonomies(['show_in_nav_menus' => true], 'objects');
        } else {
            $custom_types = get_post_types(['show_in_nav_menus' => true], 'objects');
        }

        $custom_type_list = [];

        foreach ($custom_types as $key => $value) {
            if ($exclude_defaults) {
                if ($key != 'post' && $key != 'page' && $key != 'category' && $key != 'post_tag') {
                    $custom_type_list[$key] = $value->label;
                }
            } else {
                $custom_type_list[$key] = $value->label;
            }
        }

        return $custom_type_list;
    }

    public static function render_theme_builder_templates($template): void
    {
        $args = array(
            'post_type' => array('king_addons_ext_pb'),
            'post_status' => array('publish'),
            'posts_per_page' => -1,
            'tax_query' => array(
                array(
                    'taxonomy' => 'king_addons_pb_template_type',
                    'field' => 'slug',
                    'terms' => [$template, 'user'],
                    'operator' => 'AND'
                )
            )
        );

        $user_templates = get_posts($args);

        echo '<ul class="king-addons-pb-' . esc_attr($template) . '-templates-list king-addons-pb-my-templates-list" data-pro="' . esc_attr(king_addons_freemius()->can_use_premium_code__premium_only()) . '">';

        if (!empty($user_templates)) {
            foreach ($user_templates as $user_template) {
                $slug = $user_template->post_name;

                if (!str_contains($slug, 'user-')) {
                    continue;
                }

                $edit_url = str_replace('edit', 'elementor', get_edit_post_link($user_template->ID));
                $show_on_canvas = get_post_meta(self::get_template_id($slug), 'king_addons_pb_' . $template . '_show_on_canvas', true);

                echo '<li>';
                echo '<h3 class="king-addons-pb-title">' . esc_html($user_template->post_title) . '</h3>';
                echo '<div class="king-addons-pb-action-buttons">';
                echo '<span class="king-addons-pb-template-conditions button button-primary" data-slug="' . esc_attr($slug) . '" data-show-on-canvas="' . esc_attr($show_on_canvas) . '">' . esc_html__('Manage Conditions', 'king-addons') . '</span>';
                echo '<a href="' . esc_url($edit_url) . '" class="king-addons-pb-edit-template button button-primary">' . esc_html__('Edit Popup', 'king-addons') . '</a>';

                $one_time_nonce = wp_create_nonce('delete_post-' . $slug);
                echo '<span class="king-addons-pb-delete-template button button-primary"  data-nonce="' . esc_attr($one_time_nonce) . '" data-slug="' . esc_attr($slug) . '" data-warning="' . esc_html__('Are you sure you want to delete this popup?', 'king-addons') . '"><span class="dashicons dashicons-no-alt"></span></span>';

                echo '</div>';
                echo '</li>';
            }
        } else {
            echo '<li class="king-addons-pb-no-templates">Create the first popup by clicking the \'Create Popup\' button.</li>';
        }

        echo '</ul>';

        wp_reset_postdata();

    }

    public static function get_template_id($slug)
    {
        $template = get_page_by_path($slug, OBJECT, 'king_addons_ext_pb');
        return $template->ID ?? false;
    }

    public static function archive_pages_popup_conditions($conditions): void
    {
        $term_id = '';
        $term_name = '';
        $queried_object = get_queried_object();

        if (!is_null($queried_object)) {
            if (isset($queried_object->term_id) && isset($queried_object->taxonomy)) {
                $term_id = $queried_object->term_id;
                $term_name = $queried_object->taxonomy;
            }
        }

        if (is_archive() || is_search()) {
            if (is_archive() && !is_search()) {
                if (isset($conditions['archive/all_archives'])) {
                    self::display_popups_by_location($conditions, 'archive/all_archives');
                }

                if (is_author()) {
                    if (isset($conditions['archive/author'])) {
                        self::display_popups_by_location($conditions, 'archive/author');
                    }
                }

                if (is_date()) {
                    if (isset($conditions['archive/date'])) {
                        self::display_popups_by_location($conditions, 'archive/date');
                    }
                }

                if (is_category()) {
                    if (isset($conditions['archive/categories/' . $term_id])) {
                        self::display_popups_by_location($conditions, 'archive/categories/' . $term_id);
                    }

                    if (isset($conditions['archive/categories/all'])) {
                        self::display_popups_by_location($conditions, 'archive/categories/all');
                    }
                }

                if (is_tag()) {
                    if (isset($conditions['archive/tags/' . $term_id])) {
                        self::display_popups_by_location($conditions, 'archive/tags/' . $term_id);
                    }

                    if (isset($conditions['archive/tags/all'])) {
                        self::display_popups_by_location($conditions, 'archive/tags/all');
                    }
                }

                if (is_tax()) {
                    if (isset($conditions['archive/' . $term_name . '/' . $term_id])) {
                        self::display_popups_by_location($conditions, 'archive/' . $term_name . '/' . $term_id);
                    }

                    if (isset($conditions['archive/' . $term_name . '/all'])) {
                        self::display_popups_by_location($conditions, 'archive/' . $term_name . '/all');
                    }
                }

                if (class_exists('WooCommerce')) {
                    if (function_exists('is_shop')) {
                        /** @noinspection PhpUndefinedFunctionInspection */
                        if (is_shop()) {
                            if (isset($conditions['archive/product'])) {
                                self::display_popups_by_location($conditions, 'archive/product');
                            }
                        }
                    }
                }

            } else {
                if (isset($conditions['archive/search'])) {
                    self::display_popups_by_location($conditions, 'archive/search');
                }
            }

        } elseif (self::is_blog_archive()) {
            if (isset($conditions['archive/posts'])) {
                self::display_popups_by_location($conditions, 'archive/posts');
            }
        }
    }

    public static function is_blog_archive(): bool
    {
        /** @noinspection DuplicatedCode */
        $result = false;
        $front_page = get_option('page_on_front');
        $posts_page = get_option('page_for_posts');

        if (is_home() && '0' === $front_page && '0' === $posts_page || (intval($posts_page) === get_queried_object_id() && !is_404())) {
            $result = true;
        }

        return $result;
    }

    public static function single_pages_popup_conditions($conditions): void
    {
        global $post;

        $post_id = is_null($post) ? '' : $post->ID;
        $post_type = is_null($post) ? '' : $post->post_type;

        if (is_single() || is_front_page() || is_page() || is_404()) {

            if (is_single()) {
                if ('post' == $post_type) {
                    if (isset($conditions['single/posts/' . $post_id])) {
                        self::display_popups_by_location($conditions, 'single/posts/' . $post_id);
                    }

                    if (isset($conditions['single/posts/all'])) {
                        self::display_popups_by_location($conditions, 'single/posts/all');
                    }

                } else {
                    if (isset($conditions['single/' . $post_type . '/' . $post_id])) {
                        self::display_popups_by_location($conditions, 'single/' . $post_type . '/' . $post_id);
                    }

                    if (isset($conditions['single/' . $post_type . '/all'])) {
                        self::display_popups_by_location($conditions, 'single/' . $post_type . '/all');
                    }
                }
            } else {
                if (is_front_page()) {
                    if (isset($conditions['single/front_page'])) {
                        self::display_popups_by_location($conditions, 'single/front_page');
                    }
                } elseif (is_404()) {
                    if (isset($conditions['single/page_404'])) {
                        self::display_popups_by_location($conditions, 'single/page_404');
                    }
                } elseif (is_page()) {
                    if (isset($conditions['single/pages/' . $post_id])) {
                        self::display_popups_by_location($conditions, 'single/pages/' . $post_id);
                    }

                    if (isset($conditions['single/pages/all'])) {
                        self::display_popups_by_location($conditions, 'single/pages/all');
                    }
                }
            }

        }
    }

}