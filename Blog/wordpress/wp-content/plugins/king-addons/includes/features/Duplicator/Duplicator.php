<?php /** @noinspection SpellCheckingInspection, DuplicatedCode, PhpUnused */

namespace King_Addons;

use Elementor\Core\Files\CSS\Post as Post_CSS;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class Duplicator
{
    const KNG_DUPLICATOR_ACTION = 'kng_duplicator_action';
    private static ?Duplicator $_instance = null;

    public static function instance(): Duplicator
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function __construct()
    {
        add_action('admin_action_' . self::KNG_DUPLICATOR_ACTION, array($this, 'doDuplicateAction'));
        add_filter('page_row_actions', array($this, 'addDuplicatorActionLink'), 10, 2);
        add_filter('post_row_actions', array($this, 'addDuplicatorActionLink'), 10, 2);
    }

    public static function addDuplicatorActionLink($action, $post)
    {
        if (current_user_can('edit_posts') && post_type_supports($post->post_type, 'elementor')) {

            /** @noinspection HtmlUnknownTarget */
            // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Already escaped
            $action[self::KNG_DUPLICATOR_ACTION] = sprintf(
                '<a href="%1$s" title="%2$s"><span class="screen-reader-text">%2$s</span>%3$s</a>',
                esc_url(self::getDuplicateActionURL($post->ID)),
                /* translators: 1: "King Addons" plus "Duplicator" word */
                sprintf(esc_attr__('Duplicate - %s', 'king-addons'), esc_attr($post->post_title)),
                esc_html__('King Addons Duplicator', 'king-addons')
            );

        }

        return $action;
    }

    public static function getDuplicateActionURL($post_id): string
    {
        return wp_nonce_url(
            add_query_arg(
                array(
                    'action' => self::KNG_DUPLICATOR_ACTION,
                    'post_id' => $post_id,
                    'paged' => (get_query_var('paged')) ? get_query_var('paged') : 1,
                ),
                admin_url('admin.php')
            ),
            self::KNG_DUPLICATOR_ACTION
        );
    }

    public static function doDuplicateAction(): void
    {
        if (!current_user_can('edit_posts')) {
            return;
        }

        /** @noinspection SpellCheckingInspection */
        $wp_nonce = isset($_GET['_wpnonce']) ? sanitize_text_field(wp_unslash($_GET['_wpnonce'])) : '';
        $post_id = isset($_GET['post_id']) ? absint($_GET['post_id']) : 0;

        if (!wp_verify_nonce($wp_nonce, self::KNG_DUPLICATOR_ACTION)) {
            return;
        }

        $post = get_post($post_id);
        if (is_null($post)) {
            return;
        }

        $post = sanitize_post($post, 'db');

        $duplicated_post_id = self::insertPost($post);

        $wp_redirect = add_query_arg(
            array(
                'post_type' => $post->post_type,
                'paged' => isset($_GET['paged']) ? absint($_GET['paged']) : 1,
            ),
            admin_url('edit.php')
        );

        if (!is_wp_error($duplicated_post_id)) {
            self::duplicatePostTaxonomies($post, $duplicated_post_id);
            self::duplicatePostMeta($post_id, $duplicated_post_id);

            $css = Post_CSS::create($duplicated_post_id);
            $css->update();
        }

        wp_safe_redirect($wp_redirect);
        die();
    }

    protected static function insertPost($post): int
    {
        $post_meta = get_post_meta($post->ID);

        $duplicating_post_args = array(
            'comment_status' => $post->comment_status,
            'menu_order' => $post->menu_order,
            'ping_status' => $post->ping_status,
            'post_author' => wp_get_current_user()->ID,
            'post_content' => $post->post_content,
            'post_excerpt' => $post->post_excerpt,
            'post_parent' => $post->post_parent,
            'post_password' => $post->post_password,
            'post_status' => 'draft',
            /* translators: 1: Duplicated post title */
            'post_title' => sprintf(esc_attr__('%1$s - Duplicated', 'king-addons'), esc_attr($post->post_title)),
            'post_type' => $post->post_type,
            'to_ping' => $post->to_ping,
        );

        if (isset($post_meta['_elementor_edit_mode'][0])) {
            $data = array(
                'meta_input' => array(
                    '_elementor_edit_mode' => $post_meta['_elementor_edit_mode'][0],
                    '_elementor_template_type' => $post_meta['_elementor_template_type'][0],
                ),
            );
            $duplicating_post_args = array_merge($duplicating_post_args, $data);
        }

        return wp_insert_post($duplicating_post_args);
    }

    public static function duplicatePostTaxonomies($post, $duplicated_post_id): void
    {
        $taxonomies = array_map('sanitize_text_field', get_object_taxonomies($post->post_type));
        if (!empty($taxonomies)) {
            foreach ($taxonomies as $taxonomy) {
                $wp_object_terms = wp_get_object_terms($post->ID, $taxonomy, array('fields' => 'slugs'));
                wp_set_object_terms($duplicated_post_id, $wp_object_terms, $taxonomy);
            }
        }
    }

    public static function duplicatePostMeta($previous_id, $new_id): void
    {
        $post_meta_keys = get_post_custom_keys($previous_id);
        if (!empty($post_meta_keys)) {
            foreach ($post_meta_keys as $meta_key) {
                $post_meta_values = get_post_custom_values($meta_key, $previous_id);
                foreach ($post_meta_values as $post_meta_value) {
                    $post_meta_value = maybe_unserialize($post_meta_value);
                    update_post_meta($new_id, $meta_key, wp_slash($post_meta_value));
                }
            }
        }
    }
}