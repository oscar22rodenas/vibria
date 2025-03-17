<?php /** @noinspection PhpUnused, SpellCheckingInspection, DuplicatedCode */

namespace King_Addons\AJAX_Select2;

use King_Addons\Core;
use WP_Query;
use WP_User_Query;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class Ajax_Select2_API
{
    public function __construct()
    {
        $this->init();
    }

    public function init(): void
    {
        add_action('rest_api_init', function () {
            register_rest_route(
                'kingaddons/v1/ajaxselect2',
                '/(?P<action>\w+)/',
                [
                    'methods' => 'GET',
                    'callback' => [$this, 'callback'],
                    'permission_callback' => '__return_true'
                ]
            );
        });
    }

    public function callback($request)
    {
        return $this->{$request['action']}($request);
    }

    public function getElementorTemplates($request): ?array
    {
        if (!current_user_can('edit_posts')) return null;

        $args = [
            'post_type' => 'elementor_library',
            'post_status' => 'publish',
            'meta_key' => '_elementor_template_type',
            'meta_value' => ['page', 'section', 'container'],
            'numberposts' => 10
        ];

        if (isset($request['s'])) {
            $args['s'] = $request['s'];
        }

        $options = [];
        $the_query = new WP_Query($args);

        if ($the_query->have_posts()) {
            while ($the_query->have_posts()) {
                $the_query->the_post();
                $options[] = [
                    'id' => get_the_ID(),
                    'text' => html_entity_decode(get_the_title()),
                ];
            }
        }

        wp_reset_postdata();

        return ['results' => $options];
    }

    public function getPostsByPostType($request): ?array
    {
        if (!current_user_can('edit_posts')) return null;

        $post_type = $request['query_slug'] ?? '';

        $args = [
            'post_type' => $post_type,
            'post_status' => $post_type === 'attachment' ? 'any' : 'publish',
            'posts_per_page' => 15,
        ];

        if (isset($request['ids'])) {
            $args['post__in'] = explode(',', $request['ids']);
        }

        if (isset($request['s'])) {
            $args['s'] = $request['s'];
        }

        $query = new WP_Query($args);
        $options = [];

        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                $options[] = [
                    'id' => get_the_ID(),
                    'text' => html_entity_decode(get_the_title()),
                ];
            }
        }

        wp_reset_postdata();
        return ['results' => $options];
    }

    public function getPostTypeTaxonomies($request): ?array
    {
        if (!current_user_can('edit_posts')) return null;

        $post_type = $request['query_slug'] ?? '';

        $taxonomies = get_object_taxonomies($post_type, 'objects');
        $options = [];

        if ($taxonomies) {
            foreach ($taxonomies as $taxonomy) {

                if (isset($request['s']) && stripos($taxonomy->label, $request['s']) === false) {
                    continue;
                }

                if (isset($request['ids'])) {
                    $ids = explode(',', $request['ids'] ?: '99999999');
                    if (!in_array($taxonomy->name, $ids)) {
                        continue;
                    }
                }

                $options[] = [
                    'id' => $taxonomy->name,
                    'text' => $taxonomy->label,
                ];
            }
        }

        return ['results' => $options];
    }

    public function getCustomMetaKeys($request)
    {
        if (!current_user_can('edit_posts')) return null;

        $post_types = Core::getCustomTypes('post', false);
        $data = [];

        foreach ($post_types as $slug => $name) {
            $posts = get_posts(['post_type' => $slug, 'posts_per_page' => -1]);
            $metaKeys = [];

            foreach ($posts as $post) {
                $keys = get_post_custom_keys($post->ID) ?: [];
                $keys = array_filter($keys, fn($k) => $k[0] !== '_');
                $metaKeys = array_merge($metaKeys, $keys);
            }

            $data[$slug] = array_unique($metaKeys);
        }

        $mergedKeys = array_values(
            array_unique(
                array_merge([], ...array_values($data))
            )
        );

        $filtered = array_filter($mergedKeys, function ($key) use ($request) {
            return !isset($request['s']) || strpos($key, $request['s']) !== false;
        });

        $options = array_map(fn($k) => ['id' => $k, 'text' => $k], $filtered);

        return ['results' => $options];
    }

    public function getUsers($request)
    {
        if (!current_user_can('edit_posts')) return null;

        $args = [
            'number' => 15,
            'blog_id' => 0,
        ];

        if (!empty($request['ids'])) {
            $args['include'] = array_map('intval', explode(',', $request['ids']));
        }

        if (!empty($request['s'])) {
            $args['search'] = '*' . $request['s'] . '*';
        }

        $results = (new WP_User_Query($args))->get_results();

        $options = array_map(
            fn($user) => ['id' => $user->ID, 'text' => $user->display_name],
            $results ?: []
        );

        wp_reset_postdata();

        return ['results' => $options];
    }

    public function getTaxonomies($request)
    {
        if (!current_user_can('edit_posts')) return null;

        $tax = $request['query_slug'] ?? '';
        $args = [
            'orderby' => 'name',
            'order' => 'DESC',
            'hide_empty' => true,
            'number' => 10,
        ];

        if (isset($request['ids'])) {
            $args['include'] = explode(',', $request['ids'] ?: '99999999');
        }

        if (!empty($request['s'])) {
            $args['name__like'] = $request['s'];
        }

        $terms = get_terms($tax, $args);
        $options = array_map(function ($term) {
            return [
                'id' => $term->term_id,
                'text' => $term->name,
            ];
        }, $terms);

        wp_reset_postdata();

        return ['results' => $options];
    }

    public function getCustomMetaKeysProduct($request)
    {
        if (!current_user_can('edit_posts')) return null;

        $options = [];
        $merged_meta_keys = [];
        $post_types = Core::getCustomTypes('post', false);

        foreach ($post_types as $slug => $name) {
            $posts = get_posts(['post_type' => $slug, 'posts_per_page' => -1]);
            foreach ($posts as $post) {
                $meta_keys = get_post_custom_keys($post->ID);
                if ($meta_keys) {
                    foreach ($meta_keys as $key) {
                        if ('_' !== substr($key, 0, 1)) {
                            $merged_meta_keys[] = $key;
                        }
                    }
                }
            }
        }

        $merged_meta_keys = array_values(array_unique($merged_meta_keys));
        foreach ($merged_meta_keys as $key) {
            if (empty($request['s']) || false !== strpos($key, $request['s'])) {
                $options[] = [
                    'id' => $key,
                    'text' => $key,
                ];
            }
        }

        $product_attributes = [];
        $products_query = new WP_Query([
            'post_type' => 'product',
            'posts_per_page' => -1,
        ]);

        if ($products_query->have_posts()) {
            while ($products_query->have_posts()) {
                $products_query->the_post();

                if (class_exists('WooCommerce')) {
                    if (function_exists('wc_get_product')) {
                        /** @noinspection PhpUndefinedFunctionInspection */

                        $product = wc_get_product(get_the_ID());

                        foreach ($product->get_attributes() as $attribute) {
                            $product_attributes[$attribute->get_name()] = true;
                        }

                    }
                }

            }
            wp_reset_postdata();
        }

        foreach (array_keys($product_attributes) as $attribute_name) {
            $options[] = [
                'id' => $attribute_name,
                'text' => $attribute_name,
            ];
        }

        return [
            'results' => $options,
        ];
    }

}

new Ajax_Select2_API();