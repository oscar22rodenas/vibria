<?php /** @noinspection PhpMissingFieldTypeInspection, DuplicatedCode */

namespace King_Addons;

use Elementor;
use WP_Query;

if (!defined('ABSPATH')) {
    exit;
}

final class Header_Footer_Builder
{
    private static ?Header_Footer_Builder $instance = null;
    private static ?string $current_page_type = null;
    private static array $current_page_data = array();
    private static $location_selection;
    private static $user_selection;
    private static $elementor_instance;

    public static function instance(): ?Header_Footer_Builder
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function __construct()
    {
        add_action('init', [$this, 'addPostType']);
        add_action('admin_notices', [$this, 'renderNoticeZeroPosts']);
        add_action('in_admin_header', [$this, 'renderAdminCustomHeader']);
        add_action('add_meta_boxes', [$this, 'registerMetabox']);
        add_action('save_post', [$this, 'saveMetaboxData']);
        add_action('template_redirect', [$this, 'checkUserCanEdit']);
        add_filter('screen_options_show_screen', [$this, 'disableScreenOptions'], 10, 2);

        require_once(KING_ADDONS_PATH . 'includes/extensions/Header_Footer_Builder/ELHF_Render_On_Canvas.php');
        add_filter('single_template', [$this, 'loadElementorCanvasTemplate']);

        self::setCompatibility();
        add_action('admin_enqueue_scripts', array($this, 'enqueueScripts'));
        add_action('admin_action_edit', array($this, 'initialize_options'));
        add_action('wp_ajax_king_addons_el_hf_get_posts_by_query', array($this, 'king_addons_el_hf_get_posts_by_query'));

        if (is_admin()) {
            add_action('manage_king-addons-el-hf_posts_custom_column', [$this, 'columnContent'], 10, 2);
            add_filter('manage_king-addons-el-hf_posts_columns', [$this, 'columnHeadings']);
        }
    }

    /**
     * Show an admin notice when there are zero "king-addons-el-hf" posts
     */
    function renderNoticeZeroPosts()
    {
        global $pagenow, $post_type;

        // Check if we are on the "All posts" page for the custom post type
        if ('edit.php' === $pagenow && 'edit-king-addons-el-hf' === $post_type) {

            // Count published posts of this CPT
            $count_posts = wp_count_posts('king-addons-el-hf');
            if ($count_posts->publish == 0) {
                echo '<div class="notice notice-info is-dismissible">';
                echo '<p>';
                echo esc_html__("Create the first header or footer by clicking the 'Create New' button above.", 'king addons');
                echo '</p>';
                echo '</div>';
            }
        }
    }

    public function disableScreenOptions($show_screen, $screen)
    {
        if ($screen->id === 'edit-king-addons-el-hf') {
            return false;
        }
        return $show_screen;
    }

    function king_addons_el_hf_get_posts_by_query()
    {

        check_ajax_referer('king-addons-el-hf-get-posts-by-query', 'nonce');

        $search_string = isset($_POST['q']) ? sanitize_text_field($_POST['q']) : '';
        $result = array();

        $args = array(
            'public' => true,
            '_builtin' => false,
        );

        $output = 'names';
        $operator = 'and';
        $post_types = get_post_types($args, $output, $operator);

        unset($post_types['elementor-hf']);

        $post_types['Posts'] = 'post';
        $post_types['Pages'] = 'page';

        foreach ($post_types as $key => $post_type) {
            $data = array();

            add_filter('posts_search', array($this, 'search_only_titles'), 10, 2);

            $query = new WP_Query(
                array(
                    's' => $search_string,
                    'post_type' => $post_type,
                    'posts_per_page' => -1,
                )
            );

            if ($query->have_posts()) {
                while ($query->have_posts()) {
                    $query->the_post();
                    $title = get_the_title();
                    $title .= (0 != $query->post->post_parent) ? ' (' . get_the_title($query->post->post_parent) . ')' : '';
                    $id = get_the_id();
                    $data[] = array(
                        'id' => 'post-' . $id,
                        'text' => $title,
                    );
                }
            }

            if (is_array($data) && !empty($data)) {
                $result[] = array(
                    'text' => $key,
                    'children' => $data,
                );
            }
        }

        wp_reset_postdata();

        $args = array(
            'public' => true,
        );

        $output = 'objects';
        $taxonomies = get_taxonomies($args, $output, $operator);

        foreach ($taxonomies as $taxonomy) {
            $terms = get_terms(
                $taxonomy->name,
                array(
                    'orderby' => 'count',
                    'hide_empty' => 0,
                    'name__like' => $search_string,
                )
            );

            $data = array();

            $label = ucwords($taxonomy->label);

            if (!empty($terms)) {
                foreach ($terms as $term) {

                    $data[] = array(
                        'id' => 'tax-' . $term->term_id,
                        'text' => $term->name . ' archive page',
                    );

                    $data[] = array(
                        'id' => 'tax-' . $term->term_id . '-single-' . $taxonomy->name,
                        'text' => 'All singulars from ' . $term->name,
                    );
                }
            }

            if (is_array($data) && !empty($data)) {
                $result[] = array(
                    'text' => $label,
                    'children' => $data,
                );
            }
        }

        wp_send_json($result);
    }

    public function initialize_options()
    {
        self::$user_selection = self::get_user_selections();
        self::$location_selection = self::getLocationSelections();
    }

    public function renderAdminCustomHeader()
    {
        $current_screen = get_current_screen()->id;
        if ($current_screen !== 'edit-king-addons-el-hf'
            && $current_screen !== 'header-footer_page_king-addons-el-hf-settings') {
            return;
        }

        ?>
        <div class="king-addons-pb-settings-page-header">
            <h1><?php esc_html_e('Elementor Header & Footer Builder', 'king-addons'); ?></h1>
            <p>
                <?php esc_html_e('Create fully customizable headers and footers with display conditions to control where they appear', 'king-addons'); ?>
            </p>
            <div class="king-addons-pb-preview-buttons">
                <a href="<?php echo admin_url('post-new.php?post_type=king-addons-el-hf'); ?>">
                    <div class="king-addons-pb-user-template">
                        <span><?php esc_html_e('Create New', 'king-addons'); ?></span>
                        <span class="plus-icon">+</span>
                    </div>
                </a>
                <?php if (!king_addons_freemius()->can_use_premium_code__premium_only()): ?>
                    <div class="kng-promo-btn-wrap">
                        <a href="https://kingaddons.com/pricing/?rel=king-addons-hf-builder" target="_blank">
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
        <?php

        $counts = wp_count_posts('king-addons-el-hf');
        $total = (int)$counts->publish + (int)$counts->draft;

        if (0 === $total) {
            echo '<div class="notice notice-info">';
            echo '<p>';
            echo esc_html__("Create the first header or footer by clicking the 'Create New' button above.", 'king addons');
            echo '</p>';
            echo '</div>';
        }

    }

    function addPostType(): void
    {
        if (!current_user_can('manage_options')) {
            return;
        }

        $labels = [
            'name' => esc_html__('Elementor Header & Footer Builder', 'king-addons'),
            'singular_name' => esc_html__('Elementor Header & Footer Builder', 'king-addons'),
            'menu_name' => esc_html__('Elementor Header & Footer Builder', 'king-addons'),
            'name_admin_bar' => esc_html__('Elementor Header & Footer Builder', 'king-addons'),
            'add_new' => esc_html__('Add New', 'king-addons'),
            'add_new_item' => esc_html__('Add New', 'king-addons'),
            'new_item' => esc_html__('New Template', 'king-addons'),
            'edit_item' => esc_html__('Edit Template', 'king-addons'),
            'view_item' => esc_html__('View Template', 'king-addons'),
            'all_items' => esc_html__('All Templates', 'king-addons'),
            'search_items' => esc_html__('Search Templates', 'king-addons'),
            'parent_item_colon' => esc_html__('Parent Templates:', 'king-addons'),
            'not_found' => esc_html__('No Templates found.', 'king-addons'),
            'not_found_in_trash' => esc_html__('No Templates found in Trash.', 'king-addons'),
        ];

        $args = [
            'labels' => $labels,
            'public' => true,
            'show_ui' => true,
            'show_in_menu' => false,
            'show_in_nav_menus' => false,
            'exclude_from_search' => true,
            'capability_type' => 'post',
            'hierarchical' => false,
            'menu_icon' => 'dashicons-editor-kitchensink',
            'supports' => ['title', 'thumbnail', 'elementor'],
            'show_in_rest' => true,
        ];

        register_post_type('king-addons-el-hf', $args);

        if (false === get_option('king_addons_HFB_flushed_rewrite_rules')) {
            add_option('king_addons_HFB_flushed_rewrite_rules', true);
            flush_rewrite_rules();
        }
    }

    function registerMetabox()
    {
        add_meta_box(
            'king-addons-el-hf-meta-box',
            esc_html__('Elementor Header & Footer Builder Options', 'king-addons'),
            [$this, 'renderMetabox'],
            'king-addons-el-hf',
            'normal',
            'high'
        );
    }

    function renderMetabox($post)
    {
        $values = get_post_custom($post->ID);
        $template_type = isset($values['king_addons_el_hf_template_type']) ? esc_attr(sanitize_text_field($values['king_addons_el_hf_template_type'][0])) : '';
        $display_on_canvas = isset($values['king-addons-el-hf-display-on-canvas']);

        wp_nonce_field('king_addons_el_hf_meta_nounce', 'king_addons_el_hf_meta_nounce');
        ?>
        <table class="king-addons-el-hf-options-table widefat">
            <tbody>
            <tr class="king-addons-el-hf-options-row type-of-template">
                <td class="king-addons-el-hf-options-row-heading">
                    <label for="king_addons_el_hf_template_type"><strong><?php esc_html_e('Type of Template', 'king-addons'); ?></strong></label>
                </td>
                <td class="king-addons-el-hf-options-row-content">
                    <select name="king_addons_el_hf_template_type" id="king_addons_el_hf_template_type">
                        <option value="king_addons_el_hf_not_selected" <?php selected($template_type, ''); ?>><?php esc_html_e('Select Option', 'king-addons'); ?></option>
                        <option value="king_addons_el_hf_type_header" <?php selected($template_type, 'king_addons_el_hf_type_header'); ?>><?php esc_html_e('Header', 'king-addons'); ?></option>
                        <option value="king_addons_el_hf_type_footer" <?php selected($template_type, 'king_addons_el_hf_type_footer'); ?>><?php esc_html_e('Footer', 'king-addons'); ?></option>
                    </select>
                </td>
            </tr>
            <?php
            $this->display_rules_tab();

            ?>
            <tr class="king-addons-el-hf-options-row enable-for-canvas">
                <td class="king-addons-el-hf-options-row-heading">
                    <label for="king-addons-el-hf-display-on-canvas">
                        <strong><?php esc_html_e('Enable Layout for Elementor Canvas Template?', 'king-addons'); ?></strong>
                    </label>
                    <p><?php esc_html_e('Enabling this option will display this layout on pages using Elementor Canvas Template', 'king-addons'); ?></p>
                </td>
                <td class="king-addons-el-hf-options-row-content">
                    <input type="checkbox" id="king-addons-el-hf-display-on-canvas"
                           name="king-addons-el-hf-display-on-canvas"
                           value="1" <?php checked($display_on_canvas); ?> />
                </td>
            </tr>
            </tbody>
        </table>
        <?php
    }


    public function admin_styles()
    {
        wp_enqueue_script('king-addons-el-hf-select2', KING_ADDONS_URL . 'includes/extensions/Header_Footer_Builder/select2.js', array('jquery'), KING_ADDONS_VERSION, true);

        wp_register_script(
            'king-addons-el-hf-target-rule',
            KING_ADDONS_URL . 'includes/extensions/Header_Footer_Builder/conditions-target.js',
            array(
                'jquery',
                'king-addons-el-hf-select2',
            ),
            KING_ADDONS_VERSION,
            true
        );

        wp_enqueue_script('king-addons-el-hf-target-rule');

        wp_register_script(
            'king-addons-el-hf-user-role',
            KING_ADDONS_URL . 'includes/extensions/Header_Footer_Builder/conditions-user.js',
            array(
                'jquery',
            ),
            KING_ADDONS_VERSION,
            true
        );

        wp_enqueue_script('king-addons-el-hf-user-role');

        wp_register_style('king-addons-el-hf-select2', KING_ADDONS_URL . 'includes/extensions/Header_Footer_Builder/select2.css', '', KING_ADDONS_VERSION);
        wp_enqueue_style('king-addons-el-hf-select2');
        wp_register_style('king-addons-el-hf-target-rule', KING_ADDONS_URL . 'includes/extensions/Header_Footer_Builder/conditions.css', '', KING_ADDONS_VERSION);
        wp_enqueue_style('king-addons-el-hf-target-rule');
        wp_enqueue_script('king-addons-el-hf-script', KING_ADDONS_URL . 'includes/extensions/Header_Footer_Builder/admin.js', array('jquery'), KING_ADDONS_VERSION);

        $localize_vars = array(
            'please_enter' => __('Please enter', 'king-addons'),
            'please_delete' => __('Please delete', 'king-addons'),
            'more_char' => __('or more characters', 'king-addons'),
            'character' => __('character', 'king-addons'),
            'loading' => __('Loading more results…', 'king-addons'),
            'only_select' => __('You can only select', 'king-addons'),
            'item' => __('item', 'king-addons'),
            'char_s' => __('s', 'king-addons'),
            'no_result' => __('No results found', 'king-addons'),
            'searching' => __('Searching…', 'king-addons'),
            'not_loader' => __('The results could not be loaded.', 'king-addons'),
            'search' => __('Search pages / post / categories', 'king-addons'),
            'ajax_nonce' => wp_create_nonce('king-addons-el-hf-get-posts-by-query'),
        );
        wp_localize_script('king-addons-el-hf-select2', 'kngRules', $localize_vars);

    }

    public function display_rules_tab()
    {
        $this->admin_styles();
        $include_locations = get_post_meta(get_the_id(), 'king_addons_el_hf_target_include_locations', true);
        $exclude_locations = get_post_meta(get_the_id(), 'king_addons_el_hf_target_exclude_locations', true);
        $users = get_post_meta(get_the_id(), 'king_addons_el_hf_target_user_roles', true);
        ?>
        <tr class="king-addons-el-hf-target-rules-row king-addons-el-hf-options-row">
            <td class="king-addons-el-hf-target-rules-row-heading king-addons-el-hf-options-row-heading">
                <label><strong><?php esc_html_e('Display On', 'king-addons'); ?></strong></label>
                <p><?php esc_html_e('Add locations for where this template should appear', 'king-addons'); ?></p>
            </td>
            <td class="king-addons-el-hf-target-rules-row-content king-addons-el-hf-options-row-content">
                <?php
                self::target_rule_settings_field(
                    'king-addons-el-hf-target-rules-location',
                    [
                        'title' => __('Display Rules', 'king-addons'),
                        'value' => '[{"type":"basic-global","specific":null}]',
                        'tags' => 'site,enable,target,pages',
                        'rule_type' => 'display',
                        'add_rule_label' => __('Add Display Rule', 'king-addons'),
                    ],
                    $include_locations
                );
                ?>
            </td>
        </tr>
        <tr class="king-addons-el-hf-target-rules-row king-addons-el-hf-options-row">
            <td class="king-addons-el-hf-target-rules-row-heading king-addons-el-hf-options-row-heading">
                <label><strong><?php esc_html_e('Do Not Display On', 'king-addons'); ?></strong></label>
                <p><?php esc_html_e('Add locations for where this template should not appear', 'king-addons'); ?></p>
            </td>
            <td class="king-addons-el-hf-target-rules-row-content king-addons-el-hf-options-row-content">
                <?php
                self::target_rule_settings_field(
                    'king-addons-el-hf-target-rules-exclusion',
                    [
                        'title' => __('Exclude On', 'king-addons'),
                        'value' => '[]',
                        'tags' => 'site,enable,target,pages',
                        'add_rule_label' => __('Add Exclusion Rule', 'king-addons'),
                        'rule_type' => 'exclude',
                    ],
                    $exclude_locations
                );
                ?>
            </td>
        </tr>
        <tr class="king-addons-el-hf-target-rules-row king-addons-el-hf-options-row">
            <td class="king-addons-el-hf-target-rules-row-heading king-addons-el-hf-options-row-heading">
                <label><strong><?php esc_html_e('User Roles', 'king-addons'); ?></strong></label>
                <p><?php esc_html_e('Display custom template based on user role', 'king-addons'); ?></p>
            </td>
            <td class="king-addons-el-hf-target-rules-row-content king-addons-el-hf-options-row-content">
                <?php
                self::target_user_role_settings_field(
                    'king-addons-el-hf-target-rules-users',
                    [
                        'title' => __('Users', 'king-addons'),
                        'value' => '[]',
                        'tags' => 'site,enable,target,pages',
                        'add_rule_label' => __('Add User Rule', 'king-addons'),
                    ],
                    $users
                );
                ?>
            </td>
        </tr>
        <?php
    }

    public static function get_user_selections()
    {
        $selection_options = array(
            'basic' => array(
                'label' => __('Basic', 'king-addons'),
                'value' => array(
                    'all' => __('All', 'king-addons'),
                    'logged-in' => __('Logged In', 'king-addons'),
                    'logged-out' => __('Logged Out', 'king-addons'),
                ),
            ),

            'advanced' => array(
                'label' => __('Advanced', 'king-addons'),
                'value' => array(),
            ),
        );

        /* User roles */
        $roles = get_editable_roles();

        foreach ($roles as $slug => $data) {
            $selection_options['advanced']['value'][$slug] = $data['name'];
        }

        /**
         * Filter options displayed in the user select field of Display conditions.
         *
         * @since 1.5.0
         */
        return apply_filters('king-addons-el-hf_user_roles_list', $selection_options);
    }

    public static function target_user_role_settings_field($name, $settings, $value)
    {
        $input_name = $name;
        $add_rule_label = $settings['add_rule_label'] ?? __('Add Rule', 'king-addons');
        $saved_values = $value;
        $output = '';

        if (!isset(self::$user_selection) || empty(self::$user_selection)) {
            self::$user_selection = self::get_user_selections();
        }
        $selection_options = self::$user_selection;

        $output .= '<script type="text/html" id="tmpl-king-addons-el-hf-user-role-condition">';
        $output .= '<div class="king-addons-el-hf-user-role-condition king-addons-el-hf-user-role-{{data.id}}" data-rule="{{data.id}}" >';
        $output .= '<span class="user_role-condition-delete dashicons dashicons-dismiss"></span>';

        $output .= '<div class="user_role-condition-wrap" >';
        $output .= '<select name="' . esc_attr($input_name) . '[{{data.id}}]" class="user_role-condition form-control king-addons-el-hf-input">';
        $output .= '<option value="">' . __('Select', 'king-addons') . '</option>';

        foreach ($selection_options as $group_data) {
            $output .= '<optgroup label="' . $group_data['label'] . '">';
            foreach ($group_data['value'] as $opt_key => $opt_value) {
                $output .= '<option value="' . $opt_key . '">' . $opt_value . '</option>';
            }
            $output .= '</optgroup>';
        }
        $output .= '</select>';
        $output .= '</div>';
        $output .= '</div> <!-- king-addons-el-hf-user-role-condition -->';
        $output .= '</script>';

        /** @noinspection PhpConditionAlreadyCheckedInspection */
        if (!is_array($saved_values) || (is_array($saved_values) && empty($saved_values))) {
            $saved_values = array();
            $saved_values[0] = '';
        }

        $index = 0;

        $output .= '<div class="king-addons-el-hf-user-role-wrapper king-addons-el-hf-user-role-display-on-wrap" data-type="display">';
        $output .= '<div class="king-addons-el-hf-user-role-selector-wrapper king-addons-el-hf-user-role-display-on">';
        $output .= '<div class="user_role-builder-wrap">';
        foreach ($saved_values as $index => $data) {
            $output .= '<div class="king-addons-el-hf-user-role-condition king-addons-el-hf-user-role-' . $index . '" data-rule="' . $index . '" >';
            $output .= '<span class="user_role-condition-delete dashicons dashicons-dismiss"></span>';
            /* Condition Selection */
            $output .= '<div class="user_role-condition-wrap" >';
            $output .= '<select name="' . esc_attr($input_name) . '[' . $index . ']" class="user_role-condition form-control king-addons-el-hf-input">';
            $output .= '<option value="">' . __('Select', 'king-addons') . '</option>';

            foreach ($selection_options as $group_data) {
                $output .= '<optgroup label="' . $group_data['label'] . '">';
                foreach ($group_data['value'] as $opt_key => $opt_value) {
                    $output .= '<option value="' . $opt_key . '" ' . selected($data, $opt_key, false) . '>' . $opt_value . '</option>';
                }
                $output .= '</optgroup>';
            }
            $output .= '</select>';
            $output .= '</div>';
            $output .= '</div> <!-- king-addons-el-hf-user-role-condition -->';
        }
        $output .= '</div>';
        /* Add new rule */
        $output .= '<div class="user_role-add-rule-wrap">';
        $output .= '<a href="#" class="button" data-rule-id="' . absint($index) . '">' . $add_rule_label . '</a>';
        $output .= '</div>';
        $output .= '</div>';
        $output .= '</div>';

        echo $output;
    }

    public static function target_rule_settings_field($name, $settings, $value)
    {
        $input_name = $name;
        $rule_type = $settings['rule_type'] ?? 'target_rule';
        $add_rule_label = $settings['add_rule_label'] ?? __('Add Rule', 'king-addons');
        $saved_values = $value;
        $output = '';

        if (isset(self::$location_selection) || empty(self::$location_selection)) {
            self::$location_selection = self::getLocationSelections();
        }
        $selection_options = self::$location_selection;

        $output .= '<script type="text/html" id="tmpl-king-addons-el-hf-target-rule-' . $rule_type . '-condition">';

        $output .= '<div class="king-addons-el-hf-target-rule-condition king-addons-el-hf-target-rule-{{data.id}}" data-rule="{{data.id}}" >';
        $output .= '<span class="target_rule-condition-delete dashicons dashicons-dismiss"></span>';
        $output .= '<div class="target_rule-condition-wrap" >';

        $output .= '<select name="' . esc_attr($input_name) . '[rule][{{data.id}}]" class="target_rule-condition form-control king-addons-el-hf-input">';
        $output .= '<option value="">' . __('Select', 'king-addons') . '</option>';

        foreach ($selection_options as $group_data) {
            $output .= '<optgroup label="' . $group_data['label'] . '">';
            foreach ($group_data['value'] as $opt_key => $opt_value) {
                $output .= '<option value="' . $opt_key . '">' . $opt_value . '</option>';
            }
            $output .= '</optgroup>';
        }
        $output .= '</select>';

        $output .= '</div>';
        $output .= '</div> <!-- king-addons-el-hf-target-rule-condition -->';

        $output .= '<div class="target_rule-specific-page-wrap" style="display:none">';
        $output .= '<select name="' . esc_attr($input_name) . '[specific][]" class="target-rule-select2 target_rule-specific-page form-control king-addons-el-hf-input " multiple="multiple">';
        $output .= '</select>';
        $output .= '</div>';

        $output .= '</script>';

        $output .= '<div class="king-addons-el-hf-target-rule-wrapper king-addons-el-hf-target-rule-' . $rule_type . '-on-wrap" data-type="' . $rule_type . '">';
        $output .= '<div class="king-addons-el-hf-target-rule-selector-wrapper king-addons-el-hf-target-rule-' . $rule_type . '-on">';
        $output .= self::generate_target_rule_selector($rule_type, $selection_options, $input_name, $saved_values, $add_rule_label);
        $output .= '</div>';
        $output .= '</div>';

        echo $output;
    }

    public static function generate_target_rule_selector($type, $selection_options, $input_name, $saved_values, $add_rule_label)
    {
        $output = '<div class="target_rule-builder-wrap">';

        /** @noinspection PhpConditionAlreadyCheckedInspection */
        if (!is_array($saved_values) || (is_array($saved_values) && empty($saved_values))) {
            $saved_values = array();
            $saved_values['rule'][0] = '';
            $saved_values['specific'][0] = '';
        }

        $index = 0;
        if (is_array($saved_values) && is_array($saved_values['rule'])) {
            foreach ($saved_values['rule'] as $index => $data) {
                $output .= '<div class="king-addons-el-hf-target-rule-condition king-addons-el-hf-target-rule-' . $index . '" data-rule="' . $index . '" >';

                $output .= '<span class="target_rule-condition-delete dashicons dashicons-dismiss"></span>';
                $output .= '<div class="target_rule-condition-wrap" >';
                $output .= '<select name="' . esc_attr($input_name) . '[rule][' . $index . ']" class="target_rule-condition form-control king-addons-el-hf-input">';
                $output .= '<option value="">' . __('Select', 'king-addons') . '</option>';

                foreach ($selection_options as $group_data) {
                    $output .= '<optgroup label="' . $group_data['label'] . '">';
                    foreach ($group_data['value'] as $opt_key => $opt_value) {

                        $selected = '';

                        if ($data == $opt_key) {
                            $selected = 'selected="selected"';
                        }

                        $output .= '<option value="' . $opt_key . '" ' . $selected . '>' . $opt_value . '</option>';
                    }
                    $output .= '</optgroup>';
                }
                $output .= '</select>';
                $output .= '</div>';

                $output .= '</div>';

                $output .= '<div class="target_rule-specific-page-wrap" style="display:none">';
                $output .= '<select name="' . esc_attr($input_name) . '[specific][]" class="target-rule-select2 target_rule-specific-page form-control king-addons-el-hf-input " multiple="multiple">';

                if ('specifics' == $data && isset($saved_values['specific']) && null != $saved_values['specific'] && is_array($saved_values['specific'])) {
                    foreach ($saved_values['specific'] as $sel_value) {

                        if (strpos($sel_value, 'post-') !== false) {
                            $post_id = (int)str_replace('post-', '', $sel_value);
                            $post_title = get_the_title($post_id);
                            $output .= '<option value="post-' . $post_id . '" selected="selected" >' . $post_title . '</option>';
                        }

                        if (strpos($sel_value, 'tax-') !== false) {
                            $tax_data = explode('-', $sel_value);

                            $tax_id = (int)str_replace('tax-', '', $sel_value);
                            $term = get_term($tax_id);
                            $term_name = '';

                            if (!is_wp_error($term)) {
                                $term_taxonomy = ucfirst(str_replace('_', ' ', $term->taxonomy));

                                if (isset($tax_data[2]) && 'single' === $tax_data[2]) {
                                    $term_name = 'All singulars from ' . $term->name;
                                } else {
                                    $term_name = $term->name . ' - ' . $term_taxonomy;
                                }
                            }

                            $output .= '<option value="' . $sel_value . '" selected="selected" >' . $term_name . '</option>';
                        }
                    }
                }
                $output .= '</select>';
                $output .= '</div>';
            }
        }

        $output .= '</div>';

        $output .= '<div class="target_rule-add-rule-wrap">';
        $output .= '<a href="#" class="button" data-rule-id="' . absint($index) . '" data-rule-type="' . $type . '">' . $add_rule_label . '</a>';
        $output .= '</div>';

        if ('display' == $type) {
            $output .= '<div class="target_rule-add-exclusion-rule">';
            $output .= '<a href="#" class="button">' . __('Add Exclusion Rule', 'king-addons') . '</a>';
            $output .= '</div>';
        }

        return $output;
    }

    function saveMetaboxData($post_id)
    {
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        if (!isset($_POST['king_addons_el_hf_meta_nounce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['king_addons_el_hf_meta_nounce'])), 'king_addons_el_hf_meta_nounce')) {
            return;
        }

        if (!current_user_can('edit_posts')) {
            return;
        }

        if (!isset($_POST['king-addons-el-hf-target-rules-location'])) {
            $target_locations = array(
                'rule' => array('basic-global'),
                'specific' => array(),
            );
        } else {
            $target_locations = self::getFormatRuleValue($_POST, 'king-addons-el-hf-target-rules-location');
            if (empty($target_locations)) {
                $target_locations = array(
                    'rule' => array('basic-global'),
                    'specific' => array(),
                );
            }
        }

        $target_exclusion = self::getFormatRuleValue($_POST, 'king-addons-el-hf-target-rules-exclusion');
        $target_users = [];

        if (isset($_POST['king-addons-el-hf-target-rules-users'])) {
            $target_users = array_map('sanitize_text_field', wp_unslash($_POST['king-addons-el-hf-target-rules-users']));
        }

        update_post_meta($post_id, 'king_addons_el_hf_target_include_locations', $target_locations);
        update_post_meta($post_id, 'king_addons_el_hf_target_exclude_locations', $target_exclusion);
        update_post_meta($post_id, 'king_addons_el_hf_target_user_roles', $target_users);

        if (isset($_POST['king_addons_el_hf_template_type'])) {
            update_post_meta($post_id, 'king_addons_el_hf_template_type', sanitize_text_field(wp_unslash($_POST['king_addons_el_hf_template_type'])));
        }

        if (isset($_POST['king-addons-el-hf-display-on-canvas'])) {
            update_post_meta($post_id, 'king-addons-el-hf-display-on-canvas', sanitize_text_field(wp_unslash($_POST['king-addons-el-hf-display-on-canvas'])));
        } else {
            delete_post_meta($post_id, 'king-addons-el-hf-display-on-canvas');
        }
    }

    function setCompatibility()
    {
        $template = get_template();
        $is_elementor_callable = defined('ELEMENTOR_VERSION') && is_callable('Elementor\Plugin::instance');

        if ($is_elementor_callable) {
            self::$elementor_instance = Elementor\Plugin::instance();

            // TODO: Add popular themes
            switch ($template) {
                case 'hello-elementor':
                    require_once(KING_ADDONS_PATH . 'includes/extensions/Header_Footer_Builder/themes/hello-elementor/ELHF_Hello_Elementor.php');
                    break;
                default:
                    add_action('init', [$this, 'setupSettingsPage']);
                    add_filter('king_addons_el_hf_settings_tabs', [$this, 'setupUnsupportedTheme']);
                    add_action('init', [$this, 'setupFallbackSupport']);
                    break;
            }
        }
    }

    public function setupUnsupportedTheme($settings_tabs = [])
    {
        if (!current_theme_supports('king-addons-elementor-header-footer')) {
            $settings_tabs['king_addons_el_hf_settings'] = [
                'name' => esc_html__('Display Settings', 'king-addons'),
                'url' => admin_url('edit.php?post_type=king-addons-el-hf&page=king-addons-el-hf-settings'),
            ];
        }
        return $settings_tabs;
    }

    public function setupFallbackSupport()
    {
        if (!current_theme_supports('king-addons-elementor-header-footer')) {
            $compatibility_option = get_option('king_addons_el_hf_compatibility_option', '1');

            if ('1' === $compatibility_option) {
                if (!class_exists('ELHF_Default_Method_1')) {
                    require_once(KING_ADDONS_PATH . 'includes/extensions/Header_Footer_Builder/themes/default/ELHF_Default_Method_1.php');
                }
            } elseif ('2' === $compatibility_option) {
                if (!class_exists('ELHF_Default_Method_2')) {
                    require_once(KING_ADDONS_PATH . 'includes/extensions/Header_Footer_Builder/themes/default/ELHF_Default_Method_2.php');
                }
            }
        }
    }

    function setupSettingsPage()
    {
        require_once(KING_ADDONS_PATH . 'includes/extensions/Header_Footer_Builder/ELHF_Settings_Page.php');
    }

    public static function renderHeader()
    {
        /** @noinspection SpellCheckingInspection */
        echo '<header id="masthead" itemscope="itemscope" itemtype="https://schema.org/WPHeader">';
        ?><p class="main-title" style="display: none;" itemprop="headline"><a
                href="<?php echo esc_url(get_bloginfo('url')); ?>"
                title="<?php echo esc_attr(get_bloginfo('name', 'display')); ?>"
                rel="home"><?php echo esc_html(get_bloginfo('name')); ?></a></p><?php
        self::getHeaderContent();
        echo '</header>';
    }

    public static function renderFooter()
    {
        /** @noinspection SpellCheckingInspection */
        echo '<footer id="colophon" itemscope="itemscope" itemtype="https://schema.org/WPFooter" role="contentinfo">';
        self::getFooterContent();
        echo '</footer>';
    }

    public static function getHeaderContent()
    {
        echo self::$elementor_instance->frontend->get_builder_content_for_display(self::getHeaderID()); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    }

    public static function getFooterContent()
    {
        echo '<div style="width: 100%;">';
        echo self::$elementor_instance->frontend->get_builder_content_for_display(self::getFooterID()); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        echo '</div>';
    }

    public static function getHeaderID()
    {
        $header_id = self::getSettings('king_addons_el_hf_type_header');

        if ('' === $header_id) {
            $header_id = false;
        }

        return apply_filters('king_addons_el_hf_get_header_id', $header_id);
    }

    public static function isHeaderEnabled()
    {
        $header_id = self::getSettings('king_addons_el_hf_type_header');
        $status = false;

        if ('' !== $header_id) {
            $status = true;
        }

        return apply_filters('king_addons_el_hf_header_enabled', $status);
    }

    public static function isFooterEnabled()
    {
        $footer_id = self::getSettings('king_addons_el_hf_type_footer');
        $status = false;

        if ('' !== $footer_id) {
            $status = true;
        }

        return apply_filters('king_addons_el_hf_footer_enabled', $status);
    }

    public static function getFooterID()
    {
        $footer_id = self::getSettings('king_addons_el_hf_type_footer');

        if ('' === $footer_id) {
            $footer_id = false;
        }

        return apply_filters('king_addons_el_hf_get_footer_id', $footer_id);
    }


    public static function getSettings($setting = '')
    {
        if ('king_addons_el_hf_type_header' == $setting || 'king_addons_el_hf_type_footer' == $setting) {
            $templates = self::getTemplateID($setting);
            $template = !is_array($templates) ? $templates : $templates[0];
            return apply_filters("king_addons_el_hf_get_settings_$setting", $template);
        }

        return null;
    }

    public static function getTemplateID($type)
    {
        $option = [
            'location' => 'king_addons_el_hf_target_include_locations',
            'exclusion' => 'king_addons_el_hf_target_exclude_locations',
            'users' => 'king_addons_el_hf_target_user_roles',
        ];

        $templates = self::getPostsByConditions('king-addons-el-hf', $option);

        foreach ($templates as $template) {
            if (get_post_meta(absint($template['id']), 'king_addons_el_hf_template_type', true) === $type) {
                // Polylang check - https://polylang.pro/doc/function-reference/
                if (function_exists('pll_current_language')) {
                    /** @noinspection PhpUndefinedFunctionInspection */
                    if (pll_current_language('slug') == pll_get_post_language($template['id'], 'slug')) {
                        return $template['id'];
                    }
                } else {
                    return $template['id'];
                }
            }
        }

        return '';
    }

    public static function getPostsByConditions($post_type, $option)
    {
        global $wpdb;
        global $post;

        $post_type = $post_type ? esc_sql($post_type) : esc_sql($post->post_type);

        if (is_array(self::$current_page_data) && isset(self::$current_page_data[$post_type])) {
            return apply_filters('king_addons_el_hf_get_display_posts_by_conditions', self::$current_page_data[$post_type], $post_type);
        }

        $current_page_type = self::getCurrentPageType();

        self::$current_page_data[$post_type] = array();

        $option['current_post_id'] = self::$current_page_data['ID'];
        $meta_header = self::getMetaOptionPost($post_type, $option);

        if (false === $meta_header) {
            $current_post_type = esc_sql(get_post_type());
            $current_post_id = false;
            $q_obj = get_queried_object();

            $current_id = esc_sql(get_the_id());

            // Check if WPML is active. Find WPML Object ID for current page.
            /** @noinspection SpellCheckingInspection */
            if (defined('ICL_SITEPRESS_VERSION')) {
                $default_lang = apply_filters('wpml_default_language', '');
                $current_lang = apply_filters('wpml_current_language', '');

                if ($default_lang !== $current_lang) {
                    $current_post_type = get_post_type($current_id);
                    $current_id = apply_filters('wpml_object_id', $current_id, $current_post_type, true, $default_lang);
                }
            }

            $location = isset($option['location']) ? esc_sql($option['location']) : '';

            $query = "SELECT p.ID, pm.meta_value FROM $wpdb->postmeta as pm
						INNER JOIN $wpdb->posts as p ON pm.post_id = p.ID
						WHERE pm.meta_key = '$location'
						AND p.post_type = '$post_type'
						AND p.post_status = 'publish'";

            $orderby = ' ORDER BY p.post_date DESC';

            $meta_args = "pm.meta_value LIKE '%\"basic-global\"%'";

            switch ($current_page_type) {
                case 'is_404':
                    $meta_args .= " OR pm.meta_value LIKE '%\"special-404\"%'";
                    break;
                case 'is_search':
                    $meta_args .= " OR pm.meta_value LIKE '%\"special-search\"%'";
                    break;
                case 'is_archive':
                case 'is_tax':
                case 'is_date':
                case 'is_author':
                    $meta_args .= " OR pm.meta_value LIKE '%\"basic-archives\"%'";
                    $meta_args .= " OR pm.meta_value LIKE '%\"$current_post_type|all|archive\"%'";
                    if ('is_tax' == $current_page_type && (is_category() || is_tag() || is_tax())) {
                        if (is_object($q_obj)) {
                            $meta_args .= " OR pm.meta_value LIKE '%\"$current_post_type|all|taxarchive|$q_obj->taxonomy\"%'";
                            $meta_args .= " OR pm.meta_value LIKE '%\"tax-$q_obj->term_id\"%'";
                        }
                    } elseif ('is_date' == $current_page_type) {
                        $meta_args .= " OR pm.meta_value LIKE '%\"special-date\"%'";
                    } elseif ('is_author' == $current_page_type) {
                        $meta_args .= " OR pm.meta_value LIKE '%\"special-author\"%'";
                    }
                    break;
                case 'is_home':
                    $meta_args .= " OR pm.meta_value LIKE '%\"special-blog\"%'";
                    break;
                case 'is_front_page':
                    $current_post_id = $current_id;
                    $meta_args .= " OR pm.meta_value LIKE '%\"special-front\"%'";
                    $meta_args .= " OR pm.meta_value LIKE '%\"$current_post_type|all\"%'";
                    $meta_args .= " OR pm.meta_value LIKE '%\"post-$current_id\"%'";
                    break;
                case 'is_singular':
                    $current_post_id = $current_id;
                    $meta_args .= " OR pm.meta_value LIKE '%\"basic-singulars\"%'";
                    $meta_args .= " OR pm.meta_value LIKE '%\"$current_post_type|all\"%'";
                    $meta_args .= " OR pm.meta_value LIKE '%\"post-$current_id\"%'";
                    $taxonomies = get_object_taxonomies($q_obj->post_type);
                    $terms = wp_get_post_terms($q_obj->ID, $taxonomies);
                    foreach ($terms as $term) {
                        $meta_args .= " OR pm.meta_value LIKE '%\"tax-$term->term_id-single-$term->taxonomy\"%'";
                    }
                    break;
                case 'is_woo_shop_page':
                    $meta_args .= " OR pm.meta_value LIKE '%\"special-woocommerce-shop\"%'";
                    break;
                case '':
                    $current_post_id = $current_id;
                    break;
            }

            // @codingStandardsIgnoreStart
            $posts = $wpdb->get_results($query . ' AND (' . $meta_args . ')' . $orderby);
            // @codingStandardsIgnoreEnd

            foreach ($posts as $local_post) {
                self::$current_page_data[$post_type][$local_post->ID] = array(
                    'id' => $local_post->ID,
                    'location' => unserialize($local_post->meta_value),
                );
            }

            $option['current_post_id'] = $current_post_id;

            self::removeExclusionRulePosts($post_type, $option);
            self::removeUserRulePosts($post_type, $option);
        }

        return apply_filters('king_addons_el_hf_get_display_posts_by_conditions', self::$current_page_data[$post_type], $post_type);
    }

    public static function getCurrentPageType(): ?string
    {
        if (null === self::$current_page_type) {
            $page_type = '';
            $current_id = false;

            if (is_404()) {
                $page_type = 'is_404';
            } elseif (is_search()) {
                $page_type = 'is_search';
            } elseif (is_archive()) {
                $page_type = 'is_archive';
                if (is_category() || is_tag() || is_tax()) {
                    $page_type = 'is_tax';
                } elseif (is_date()) {
                    $page_type = 'is_date';
                } elseif (is_author()) {
                    $page_type = 'is_author';
                } elseif (function_exists('is_shop')) {
                    /** @noinspection PhpUndefinedFunctionInspection */
                    if (is_shop()) {
                        $page_type = 'is_woo_shop_page';
                    }
                }
            } elseif (is_home()) {
                $page_type = 'is_home';
            } elseif (is_front_page()) {
                $page_type = 'is_front_page';
                $current_id = get_the_id();
            } elseif (is_singular()) {
                $page_type = 'is_singular';
                $current_id = get_the_id();
            } else {
                $current_id = get_the_id();
            }

            self::$current_page_data['ID'] = $current_id;
            self::$current_page_type = $page_type;
        }

        return self::$current_page_type;
    }

    public static function getMetaOptionPost($post_type, $option)
    {
        $page_meta = (isset($option['page_meta']) && '' != $option['page_meta']) ? $option['page_meta'] : false;

        if (false !== $page_meta) {
            $current_post_id = $option['current_post_id'] ?? false;
            $meta_id = get_post_meta($current_post_id, $option['page_meta'], true);

            if (false !== $meta_id && '' != $meta_id) {
                self::$current_page_data[$post_type][$meta_id] = array(
                    'id' => $meta_id,
                    'location' => '',
                );

                return self::$current_page_data[$post_type];
            }
        }

        return false;
    }

    public static function removeExclusionRulePosts($post_type, $option)
    {
        $exclusion = $option['exclusion'] ?? '';
        $current_post_id = $option['current_post_id'] ?? false;
        foreach (self::$current_page_data[$post_type] as $c_post_id => $c_data) {
            $exclusion_rules = get_post_meta($c_post_id, $exclusion, true);
            $is_exclude = self::parseLayoutDisplayCondition($current_post_id, $exclusion_rules);
            if ($is_exclude) {
                unset(self::$current_page_data[$post_type][$c_post_id]);
            }
        }
    }

    public static function removeUserRulePosts($post_type, $option)
    {
        $users = $option['users'] ?? '';

        foreach (self::$current_page_data[$post_type] as $c_post_id => $c_data) {
            $user_rules = get_post_meta($c_post_id, $users, true);
            $is_user = self::parseUserRoleCondition($user_rules);

            if (!$is_user) {
                unset(self::$current_page_data[$post_type][$c_post_id]);
            }
        }
    }

    public static function parseLayoutDisplayCondition($post_id, $rules): bool
    {
        $display = false;

        /** @noinspection PhpConditionCheckedByNextConditionInspection */
        if (isset($rules['rule']) && is_array($rules['rule']) && !empty($rules['rule'])) {
            foreach ($rules['rule'] as $rule) {
                if (strrpos($rule, 'all') !== false) {
                    $rule_case = 'all';
                } else {
                    $rule_case = $rule;
                }

                switch ($rule_case) {
                    case 'basic-global':
                        $display = true;
                        break;

                    case 'basic-singulars':
                        if (is_singular()) {
                            $display = true;
                        }
                        break;

                    case 'basic-archives':
                        if (is_archive()) {
                            $display = true;
                        }
                        break;

                    case 'special-404':
                        if (is_404()) {
                            $display = true;
                        }
                        break;

                    case 'special-search':
                        if (is_search()) {
                            $display = true;
                        }
                        break;

                    case 'special-blog':
                        if (is_home()) {
                            $display = true;
                        }
                        break;

                    case 'special-front':
                        if (is_front_page()) {
                            $display = true;
                        }
                        break;

                    case 'special-date':
                        if (is_date()) {
                            $display = true;
                        }
                        break;

                    case 'special-author':
                        if (is_author()) {
                            $display = true;
                        }
                        break;

                    case 'special-woocommerce-shop':
                        if (function_exists('is_shop')) {
                            /** @noinspection PhpUndefinedFunctionInspection */
                            if (is_shop()) {
                                $display = true;
                            }
                        }
                        break;

                    case 'all':
                        $rule_data = explode('|', $rule);

                        $post_type = $rule_data[0] ?? false;
                        $archive_type = $rule_data[2] ?? false;
                        $taxonomy = $rule_data[3] ?? false;
                        if (false === $archive_type) {
                            $current_post_type = get_post_type($post_id);
                            if (false !== $post_id && $current_post_type == $post_type) {
                                $display = true;
                            }
                        } else {
                            if (is_archive()) {
                                $current_post_type = get_post_type();
                                if ($current_post_type == $post_type) {
                                    if ('archive' == $archive_type) {
                                        $display = true;
                                    } elseif ('taxarchive' == $archive_type) {
                                        $obj = get_queried_object();
                                        $current_taxonomy = '';
                                        if ('' !== $obj && null !== $obj) {
                                            $current_taxonomy = $obj->taxonomy;
                                        }

                                        if ($current_taxonomy == $taxonomy) {
                                            $display = true;
                                        }
                                    }
                                }
                            }
                        }
                        break;

                    case 'specifics':
                        if (isset($rules['specific']) && is_array($rules['specific'])) {
                            foreach ($rules['specific'] as $specific_page) {
                                $specific_data = explode('-', $specific_page);
                                $specific_post_type = $specific_data[0] ?? false;
                                $specific_post_id = $specific_data[1] ?? false;
                                if ('post' == $specific_post_type) {
                                    if ($specific_post_id == $post_id) {
                                        $display = true;
                                    }
                                } elseif (isset($specific_data[2]) && ('single' == $specific_data[2]) && 'tax' == $specific_post_type) {
                                    if (is_singular()) {
                                        $term_details = get_term($specific_post_id);

                                        if (isset($term_details->taxonomy)) {
                                            $has_term = has_term((int)$specific_post_id, $term_details->taxonomy, $post_id);

                                            if ($has_term) {
                                                $display = true;
                                            }
                                        }
                                    }
                                } elseif ('tax' == $specific_post_type) {
                                    $tax_id = get_queried_object_id();
                                    if ($specific_post_id == $tax_id) {
                                        $display = true;
                                    }
                                }
                            }
                        }
                        break;

                    default:
                        break;
                }

                if ($display) {
                    break;
                }
            }
        }

        return $display;
    }

    public static function parseUserRoleCondition($rules): bool
    {
        $show_popup = true;

        if (is_array($rules) && !empty($rules)) {
            $show_popup = false;

            foreach ($rules as $rule) {
                switch ($rule) {
                    case '':
                    case 'all':
                        $show_popup = true;
                        break;

                    case 'logged-in':
                        if (is_user_logged_in()) {
                            $show_popup = true;
                        }
                        break;

                    case 'logged-out':
                        if (!is_user_logged_in()) {
                            $show_popup = true;
                        }
                        break;

                    default:
                        if (is_user_logged_in()) {
                            $current_user = wp_get_current_user();

                            if (isset($current_user->roles)
                                && is_array($current_user->roles)
                                && in_array($rule, $current_user->roles)
                            ) {
                                $show_popup = true;
                            }
                        }
                        break;
                }

                if ($show_popup) {
                    break;
                }
            }
        }

        return $show_popup;
    }

    public static function checkUserCanEdit()
    {
        if (is_singular('king-addons-el-hf') && !current_user_can('edit_posts')) {
            wp_redirect(site_url(), 301);
            die;
        }
    }

    public static function loadElementorCanvasTemplate($single_template)
    {
        global $post;

        if ('king-addons-el-hf' == $post->post_type) {
            if (defined('ELEMENTOR_VERSION')) {
                $elementor_2_0_canvas = ELEMENTOR_PATH . '/modules/page-templates/templates/canvas.php';

                if (file_exists($elementor_2_0_canvas)) {
                    return $elementor_2_0_canvas;
                } else {
                    return ELEMENTOR_PATH . '/includes/page-templates/canvas.php';
                }
            }
        }

        return $single_template;
    }

    public static function enqueueScripts(): void
    {
        $screen = get_current_screen();
        if ($screen->id === 'edit-king-addons-el-hf') {
            // todo - styles
//            wp_enqueue_style('king-addons-el-hf-style', KING_ADDONS_URL . 'includes/extensions/Header_Footer_Builder/header-footer-builder.css', '', KING_ADDONS_VERSION);
            wp_enqueue_style('king-addons-el-hf-style', KING_ADDONS_URL . 'includes/extensions/Header_Footer_Builder/admin.css', '', KING_ADDONS_VERSION);
        }
    }

    public static function columnHeadings($columns)
    {
        unset($columns['date']);
        $columns['king_addons_el_hf_edit_template'] = esc_html__('Edit Template', 'king-addons');
        $columns['king_addons_el_hf_type_of_template'] = esc_html__('Type of Template', 'king-addons');
        $columns['king_addons_el_hf_display_rules'] = esc_html__('Display Rules', 'king-addons');
        $columns['date'] = esc_html__('Date', 'king-addons');
        return $columns;
    }

    public static function columnContent($column, $post_id)
    {
        // Edit Template
        if ('king_addons_el_hf_edit_template' === $column) {
            echo '<a class="king-addons-el-hf-edit-template-btn" href="';
            echo './post.php?post=' . esc_attr($post_id) . '&action=edit';
            echo '">' . esc_html__('Edit Template', 'king-addons') . '</a>';
        }

        // Display Rules
        if ('king_addons_el_hf_display_rules' === $column) {

            $locations = get_post_meta($post_id, 'king_addons_el_hf_target_include_locations', true);
            if (!empty($locations)) {
                echo '<div style="margin-bottom: 5px;">';
                echo '<strong>';
                echo esc_html__('Display: ', 'king-addons');
                echo '</strong>';
                self::columnDisplayLocation($locations);
                echo '</div>';
            }

            $locations = get_post_meta($post_id, 'king_addons_el_hf_target_exclude_locations', true);
            if (!empty($locations)) {
                echo '<div style="margin-bottom: 5px;">';
                echo '<strong>';
                echo esc_html__('Exclusion: ', 'king-addons');
                echo '</strong>';
                self::columnDisplayLocation($locations);
                echo '</div>';
            }

            $users = get_post_meta($post_id, 'king_addons_el_hf_target_user_roles', true);
            if (isset($users) && is_array($users)) {
                if (!empty($users[0])) {
                    $user_label = [];
                    foreach ($users as $user) {
                        $user_label[] = self::get_user_by_key($user);
                    }
                    echo '<div>';
                    echo '<strong>Users: </strong>';
                    echo esc_html(join(', ', $user_label));
                    echo '</div>';
                }
            }

        }

        // Type of Template
        if ('king_addons_el_hf_type_of_template' === $column) {
            $template_type = get_post_meta($post_id, 'king_addons_el_hf_template_type', true);
            if (!empty($template_type)) {
                echo '<div style="margin-bottom: 5px;">';
                echo '<strong>';
                switch ($template_type) {
                    case 'king_addons_el_hf_type_header':
                        echo esc_html__('Header', 'king-addons');
                        break;
                    case 'king_addons_el_hf_type_footer':
                        echo esc_html__('Footer', 'king-addons');
                        break;
                    default:
                        echo esc_html__('Not selected', 'king-addons');
                        break;
                }
                echo '</strong>';
                echo '</div>';
            }
        }
    }

    public static function get_user_by_key($key)
    {
        if (!isset(self::$user_selection) || empty(self::$user_selection)) {
            self::$user_selection = self::get_user_selections();
        }
        $user_selection = self::$user_selection;

        if (isset($user_selection['basic']['value'][$key])) {
            return $user_selection['basic']['value'][$key];
        } elseif ($user_selection['advanced']['value'][$key]) {
            return $user_selection['advanced']['value'][$key];
        }
        return $key;
    }

    public static function columnDisplayLocation($locations)
    {
        $location_label = [];
        /** @noinspection PhpConditionAlreadyCheckedInspection */
        if (is_array($locations) && is_array($locations['rule']) && isset($locations['rule'])) {
            /** @noinspection PhpArraySearchInBooleanContextInspection */
            $index = array_search('specifics', $locations['rule']);
            /** @noinspection PhpConditionCheckedByNextConditionInspection */
            if (false !== $index && !empty($index)) {
                unset($locations['rule'][$index]);
            }
        }

        if (isset($locations['rule']) && is_array($locations['rule'])) {
            foreach ($locations['rule'] as $location) {
                $location_label[] = self::getLocation($location);
            }
        }

        if (isset($locations['specific']) && is_array($locations['specific'])) {
            foreach ($locations['specific'] as $location) {
                $location_label[] = self::getLocation($location);
            }
        }

        echo esc_html(join(', ', $location_label));
    }

    public static function getLocation($key)
    {
        if (!isset(self::$location_selection) || empty(self::$location_selection)) {
            self::$location_selection = self::getLocationSelections();
        }

        $location_selection = self::$location_selection;

        foreach ($location_selection as $location_grp) {
            if (isset($location_grp['value'][$key])) {
                return $location_grp['value'][$key];
            }
        }

        if (strpos($key, 'post-') !== false) {
            $post_id = (int)str_replace('post-', '', $key);
            return get_the_title($post_id);
        }

        if (strpos($key, 'tax-') !== false) {
            $tax_id = (int)str_replace('tax-', '', $key);
            $term = get_term($tax_id);

            if (!is_wp_error($term)) {
                $term_taxonomy = ucfirst(str_replace('_', ' ', $term->taxonomy));
                return $term->name . ' - ' . $term_taxonomy;
            } else {
                return '';
            }
        }

        return $key;
    }

    public static function getLocationSelections()
    {
        $args = array(
            'public' => true,
            '_builtin' => true,
        );

        $post_types = get_post_types($args, 'objects');
        unset($post_types['attachment']);

        $args['_builtin'] = false;
        $custom_post_type = get_post_types($args, 'objects');

        $post_types = apply_filters('king_addons_el_hf_location_rule_post_types', array_merge($post_types, $custom_post_type));

        if (!king_addons_freemius()->can_use_premium_code__premium_only()) {
            $special_pages = array(
                'special-404-none' => esc_html__('404 Page (Available in PRO)', 'king-addons'),
                'special-search' => esc_html__('Search Page', 'king-addons'),
                'special-blog-none' => esc_html__('Blog / Posts Page (Available in PRO)', 'king-addons'),
                'special-front' => esc_html__('Front Page', 'king-addons'),
                'special-date' => esc_html__('Date Archive', 'king-addons'),
                'special-author' => esc_html__('Author Archive', 'king-addons'),
            );
        } else {
            $special_pages = array(
                'special-404' => esc_html__('404 Page', 'king-addons'),
                'special-search' => esc_html__('Search Page', 'king-addons'),
                'special-blog' => esc_html__('Blog / Posts Page', 'king-addons'),
                'special-front' => esc_html__('Front Page', 'king-addons'),
                'special-date' => esc_html__('Date Archive', 'king-addons'),
                'special-author' => esc_html__('Author Archive', 'king-addons'),
            );
        }

        if (class_exists('WooCommerce')) {
            if (!king_addons_freemius()->can_use_premium_code__premium_only()) {
                $special_pages['special-woocommerce-shop-none'] = esc_html__('WooCommerce Shop Page (Available in PRO)', 'king-addons');
            } else {
                $special_pages['special-woocommerce-shop'] = esc_html__('WooCommerce Shop Page', 'king-addons');
            }
        }

        $selection_options = array(
            'basic' => array(
                'label' => esc_html__('Basic', 'king-addons'),
                'value' => array(
                    'basic-global' => esc_html__('Entire Website', 'king-addons'),
                    'basic-singulars' => esc_html__('All Singulars', 'king-addons'),
                    'basic-archives' => esc_html__('All Archives', 'king-addons'),
                ),
            ),

            'special-pages' => array(
                'label' => esc_html__('Special Pages', 'king-addons'),
                'value' => $special_pages,
            ),
        );

        if (!king_addons_freemius()->can_use_premium_code__premium_only()) {
            $selection_options['specific-target'] = array(
                'label' => esc_html__('Specific Target', 'king-addons'),
                'value' => array(
                    'specifics-none' => esc_html__('Specific Pages / Posts / Taxonomies, etc. (Available in PRO)', 'king-addons'),
                ),
            );
        } else {
            $selection_options['specific-target'] = array(
                'label' => esc_html__('Specific Target', 'king-addons'),
                'value' => array(
                    'specifics' => esc_html__('Specific Pages / Posts / Taxonomies, etc.', 'king-addons'),
                ),
            );
        }

        $args = array(
            'public' => true,
        );

        $taxonomies = get_taxonomies($args, 'objects');

        if (!empty($taxonomies)) {
            foreach ($taxonomies as $taxonomy) {

                if ('post_format' == $taxonomy->name) {
                    continue;
                }

                foreach ($post_types as $post_type) {
                    $post_opt = self::getPostTargetRuleOptions($post_type, $taxonomy);

                    if (isset($selection_options[$post_opt['post_key']])) {
                        if (!empty($post_opt['value']) && is_array($post_opt['value'])) {
                            foreach ($post_opt['value'] as $key => $value) {
                                if (!in_array($value, $selection_options[$post_opt['post_key']]['value'])) {
                                    $selection_options[$post_opt['post_key']]['value'][$key] = $value;
                                }
                            }
                        }
                    } else {
                        $selection_options[$post_opt['post_key']] = array(
                            'label' => $post_opt['label'],
                            'value' => $post_opt['value'],
                        );
                    }
                }
            }
        }

        return apply_filters('king_addons_el_hf_display_on_list', $selection_options);
    }

    public static function getPostTargetRuleOptions($post_type, $taxonomy): array
    {
        $post_key = str_replace(' ', '-', strtolower($post_type->label));
        $post_label = ucwords($post_type->label);
        $post_name = $post_type->name;
        $post_option = array();

        /* translators: %s is post label */
        $all_posts = sprintf(esc_html__('All %s', 'king-addons'), $post_label);
        $post_option[$post_name . '|all'] = $all_posts;

        if ('pages' != $post_key) {
            /* translators: %s is post label */
            $all_archive = sprintf(esc_html__('All %s Archive', 'king-addons'), $post_label);
            $post_option[$post_name . '|all|archive'] = $all_archive;
        }

        if (in_array($post_type->name, $taxonomy->object_type)) {
            $tax_label = ucwords($taxonomy->label);
            $tax_name = $taxonomy->name;

            /* translators: %s is taxonomy label */
            $tax_archive = sprintf(esc_html__('All %s Archive', 'king-addons'), $tax_label);

            $post_option[$post_name . '|all|taxarchive|' . $tax_name] = $tax_archive;
        }

        $post_output['post_key'] = $post_key;
        $post_output['label'] = $post_label;
        $post_output['value'] = $post_option;

        return $post_output;
    }

    public static function getFormatRuleValue($save_data, $key): array
    {
        $meta_value = array();

        if (isset($save_data[$key]['rule'])) {
            $save_data[$key]['rule'] = array_unique($save_data[$key]['rule']);
            if (isset($save_data[$key]['specific'])) {
                $save_data[$key]['specific'] = array_unique($save_data[$key]['specific']);
            }

            $index = array_search('', $save_data[$key]['rule']);
            if (false !== $index) {
                unset($save_data[$key]['rule'][$index]);
            }
            $index = array_search('specifics', $save_data[$key]['rule']);
            if (false !== $index) {
                unset($save_data[$key]['rule'][$index]);

                if (isset($save_data[$key]['specific']) && is_array($save_data[$key]['specific'])) {
                    $save_data[$key]['rule'][] = 'specifics';
                }
            }

            foreach ($save_data[$key] as $meta_key => $value) {
                if (!empty($value)) {
                    $meta_value[$meta_key] = array_map('esc_attr', $value);
                }
            }
            if (!isset($meta_value['rule']) || !in_array('specifics', $meta_value['rule'])) {
                $meta_value['specific'] = array();
            }

            if (empty($meta_value['rule'])) {
                $meta_value = array();
            }
        }

        return $meta_value;
    }
}