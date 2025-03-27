<?php /** @noinspection PhpUndefinedFunctionInspection */

namespace King_Addons;

use Elementor\Group_Control_Image_Size;
use Elementor\Plugin;
use WP_Query;

if (!defined('ABSPATH')) {
    exit;
}

class Filter_WooCommerce_Products_Ajax
{
    public function __construct()
    {
        add_action('wp_ajax_king_addons_filter_woocommerce_products', [$this, 'king_addons_filter_woocommerce_products']);
        add_action('wp_ajax_nopriv_king_addons_filter_woocommerce_products', [$this, 'king_addons_filter_woocommerce_products']);

        add_action('wp_ajax_king_addons_get_woocommerce_filtered_count', [$this, 'king_addons_get_woocommerce_filtered_count']);
        add_action('wp_ajax_nopriv_king_addons_get_woocommerce_filtered_count', [$this, 'king_addons_get_woocommerce_filtered_count']);
    }

    public function get_related_taxonomies()
    {
        $relations = [];
        foreach (Core::getCustomTypes('post', false) as $slug => $title) {
            // Directly assign the array of taxonomies.
            $relations[$slug] = get_object_taxonomies($slug);
        }
        return json_encode($relations);
    }

    public function get_max_num_pages($settings)
    {
        $query = new WP_Query($this->get_main_query_args());
        $max_num_pages = (int) ceil($query->max_num_pages);

        $adjustedTotalPosts = max(0, $query->found_posts - $query->query_vars['offset']);
        $numberOfPages      = ceil($adjustedTotalPosts / $query->query_vars['posts_per_page']);

        wp_send_json_success([
            'page_count'     => $numberOfPages,
            'max_num_pages'  => $max_num_pages,
            'query_found'    => $query->found_posts,
            'query_offset'   => $query->query_vars['offset'],
            'query_num'      => $query->query_vars['posts_per_page']
        ]);

        wp_reset_postdata();
        return $max_num_pages;
    }

    public function get_main_query_args()
    {
        $settings = $_POST['grid_settings'];
        $taxonomy = $_POST['king_addons_taxonomy'];
        $term     = $_POST['king_addons_filter'];

        // Limit "Pro" options if user does not have premium.
        if (!king_addons_freemius()->can_use_premium_code__premium_only()) {
            if ($settings['query_selection'] === 'pro-cr') {
                $settings['query_selection'] = 'dynamic';
            }
            if ($settings['query_orderby'] === 'pro-rn') {
                $settings['query_orderby'] = 'date';
            }
        }

        // Determine current page.
        $paged = get_query_var('paged') ?: get_query_var('page') ?: 1;

        // Default offset and posts_per_page.
        $settings['query_offset'] = empty($settings['query_offset']) ? 0 : (int) $settings['query_offset'];
        $query_posts_per_page     = empty($settings['query_posts_per_page']) ? -1 : (int) $settings['query_posts_per_page'];

        // Final offset.
        $offset = ($paged - 1) * $query_posts_per_page + $settings['query_offset'];

        // Base args.
        $args = [
            'post_type'      => 'product',
            'tax_query'      => $this->get_tax_query_args(),
            'meta_query'     => $this->get_meta_query_args(),
            'post__not_in'   => $settings['query_exclude_products'],
            'posts_per_page' => $query_posts_per_page,
            'orderby'        => 'date',
            'paged'          => $paged,
            'offset'         => $offset,
        ];

        // Handle different query selections.
        switch ($settings['query_selection']) {
            case 'featured':
                $args['tax_query'][] = [
                    'taxonomy' => 'product_visibility',
                    'field'    => 'term_taxonomy_id',
                    'terms'    => wc_get_product_visibility_term_ids()['featured'],
                ];
                break;

            case 'onsale':
                $args['meta_query'] = [
                    'relation' => 'OR',
                    [
                        'key'     => '_sale_price',
                        'value'   => 0,
                        'compare' => '>',
                        'type'    => 'numeric'
                    ],
                    [
                        'key'     => '_min_variation_sale_price',
                        'value'   => 0,
                        'compare' => '>',
                        'type'    => 'numeric'
                    ]
                ];
                break;

            case 'upsell':
                $product = wc_get_product();
                if (!$product) {
                    return;
                }
                $meta_query         = WC()->query->get_meta_query();
                $this->my_upsells   = $product->get_upsell_ids();
                if (!empty($this->my_upsells)) {
                    $args = [
                        'post_type'           => 'product',
                        'post__not_in'        => $settings['query_exclude_products'],
                        'ignore_sticky_posts' => 1,
                        'posts_per_page'      => $query_posts_per_page,
                        'orderby'             => 'post__in',
                        'order'               => $settings['order_direction'],
                        'paged'               => $paged,
                        'post__in'            => $this->my_upsells,
                        'meta_query'          => $meta_query
                    ];
                } else {
                    $args['post_type'] = ['none'];
                }
                break;

            case 'cross-sell':
                $this->crossell_ids = [];
                if (is_cart()) {
                    foreach (WC()->cart->get_cart() as $values) {
                        $product            = $values['data'];
                        $cross_sell_ids     = $product->get_cross_sell_ids();
                        $this->crossell_ids = array_merge($this->crossell_ids, $cross_sell_ids);
                    }
                }
                if (is_single()) {
                    $product = wc_get_product();
                    if (!$product) {
                        return;
                    }
                    $this->crossell_ids = $product->get_cross_sell_ids();
                }
                if (!empty($this->crossell_ids)) {
                    $args = [
                        'post_type'           => 'product',
                        'post__not_in'        => $settings['query_exclude_products'],
                        'tax_query'           => $this->get_tax_query_args(),
                        'ignore_sticky_posts' => 1,
                        'posts_per_page'      => $query_posts_per_page,
                        'order'               => $settings['order_direction'],
                        'paged'               => $paged,
                        'post__in'            => $this->crossell_ids,
                    ];
                } else {
                    $args['post_type'] = 'none';
                }
                break;

            case 'manual':
                $post_ids = !empty($settings['query_manual_products']) ? $settings['query_manual_products'] : [''];
                $args = [
                    'post_type'      => 'product',
                    'post__in'       => $post_ids,
                    'posts_per_page' => $query_posts_per_page,
                    'orderby'        => $settings['query_randomize'],
                    'paged'          => $paged,
                ];
                break;

            case 'current':
                // If not in Elementor editor mode, use the global WP query's query_vars.
                if (true !== Plugin::$instance->editor->is_edit_mode()) {
                    global $wp_query;
                    $args                 = $wp_query->query_vars;
                    $args['tax_query']    = $this->get_tax_query_args();
                    $args['meta_query']   = $this->get_meta_query_args();
                    $args['posts_per_page'] = is_product_category() ?
                        (int) get_option('king_addons_woocommerce_shop_cat_ppp', 9) : (is_product_tag() ?
                            (int) get_option('king_addons_woocommerce_shop_tag_ppp', 9) :
                            (int) get_option('king_addons_woocommerce_shop_ppp', 9)
                        );
                    if (!empty($settings['query_randomize'])) {
                        $args['orderby'] = $settings['query_randomize'];
                    }
                }
                break;
        }

        // Handle ordering.
        switch ($settings['query_orderby']) {
            case 'sales':
                $args['meta_key'] = 'total_sales';
                $args['orderby']  = 'meta_value_num';
                break;
            case 'rating':
                $args['meta_key'] = '_wc_average_rating';
                $args['orderby']  = 'meta_value_num';
                break;
            case 'price-low':
            case 'price-high':
                $args['meta_key'] = '_price';
                $args['order']    = $settings['order_direction'];
                $args['orderby']  = 'meta_value_num';
                break;
            case 'random':
                $args['orderby'] = 'rand';
                break;
            case 'date':
                $args['orderby'] = 'date';
                break;
            default:
                $args['orderby'] = 'menu_order';
                $args['order']   = $settings['order_direction'];
                break;
        }

        // Exclude products with no images.
        if ('yes' === $settings['query_exclude_no_images']) {
            $args['meta_key'] = '_thumbnail_id';
        }

        // Exclude out of stock.
        if ('yes' === $settings['query_exclude_out_of_stock']) {
            $args['meta_query'] = [
                [
                    'key'     => '_stock_status',
                    'value'   => 'outofstock',
                    'compare' => 'NOT LIKE',
                ]
            ];
        }

        // Handle URL-based orderby (for front-end sorting).
        if (isset($_GET['orderby'])) {
            switch ($_GET['orderby']) {
                case 'popularity':
                    $args['meta_key'] = 'total_sales';
                    $args['orderby']  = 'meta_value_num';
                    break;
                case 'rating':
                    $args['meta_key'] = '_wc_average_rating';
                    $args['order']    = $settings['order_direction'];
                    $args['orderby']  = 'meta_value_num';
                    break;
                case 'price':
                    $args['meta_key'] = '_price';
                    $args['order']    = 'ASC';
                    $args['orderby']  = 'meta_value_num';
                    break;
                case 'price-desc':
                    $args['meta_key'] = '_price';
                    $args['order']    = 'DESC';
                    $args['orderby']  = 'meta_value_num';
                    break;
                case 'random':
                    $args['orderby'] = 'rand';
                    break;
                case 'date':
                    $args['orderby'] = 'date';
                    break;
                case 'title':
                    $args['orderby'] = 'title';
                    $args['order']   = 'ASC';
                    break;
                case 'title-desc':
                    $args['orderby'] = 'title';
                    $args['order']   = 'DESC';
                    break;
                default:
                    $args['order']   = $settings['order_direction'];
                    $args['orderby'] = 'menu_order';
            }
        }

        // Live search param.
        if (isset($_GET['psearch']) && !empty($_GET['psearch'])) {
            $args['s'] = $_GET['psearch'];
        }

        // If user selected a taxonomy filter from the widget
        if ($term !== '*') {
            if ($taxonomy === 'tag') {
                // Convert "tag" => "product_tag"
                $taxonomy = 'product_' . $_POST['king_addons_taxonomy'];
            }
            $args['tax_query'][] = [
                'taxonomy' => $taxonomy,
                'field'    => 'slug',
                'terms'    => $term
            ];
        }

        // If we receive an extra offset from Ajax
        if (isset($_POST['king_addons_offset'])) {
            $args['offset'] = $_POST['king_addons_offset'];
        }

        return $args;
    }

    public function get_tax_query_args()
    {
        $tax_query = [];

        // If using layered nav / filters
        if (isset($_GET['kingaddonsfilters'])) {
            $selected_filters = WC()->query->get_layered_nav_chosen_attributes();
            if (!empty($selected_filters)) {
                foreach ($selected_filters as $taxonomy => $data) {
                    $tax_query[] = [
                        'taxonomy'         => $taxonomy,
                        'field'            => 'slug',
                        'terms'            => $data['terms'],
                        'operator'         => ('and' === $data['query_type']) ? 'AND' : 'IN',
                        'include_children' => false,
                    ];
                }
            }

            if (isset($_GET['filter_product_cat'])) {
                $tax_query[] = [
                    'taxonomy'         => 'product_cat',
                    'field'            => 'slug',
                    'terms'            => explode(',', $_GET['filter_product_cat']),
                    'operator'         => 'IN',
                    'include_children' => true,
                ];
            }

            if (isset($_GET['filter_product_tag'])) {
                $tax_query[] = [
                    'taxonomy'         => 'product_tag',
                    'field'            => 'slug',
                    'terms'            => explode(',', $_GET['filter_product_tag']),
                    'operator'         => 'IN',
                    'include_children' => true,
                ];
            }
        } else {
            // Normal grid-based filter
            $settings = $_POST['grid_settings'];
            $taxonomy = $_POST['king_addons_taxonomy'];
            $term     = $_POST['king_addons_filter'];

            if (isset($_GET['king_addons_select_product_cat']) && $_GET['king_addons_select_product_cat'] !== '0') {
                $category     = sanitize_text_field($_GET['king_addons_select_product_cat']);
                $tax_query[] = [
                    'taxonomy' => 'product_cat',
                    'field'    => 'id',
                    'terms'    => $category
                ];
            }

            if (isset($_GET['product_cat']) && $_GET['product_cat'] !== '0') {
                $category     = sanitize_text_field($_GET['product_cat']);
                $tax_query[] = [
                    'taxonomy' => 'product_cat',
                    'field'    => 'id',
                    'terms'    => $category
                ];
            } else {
                // Check all product taxonomies for any we want to filter by ID
                foreach (get_object_taxonomies('product') as $tax) {
                    if (!empty($settings["query_taxonomy_{$tax}"])) {
                        $tax_query[] = [
                            'taxonomy' => $tax,
                            'field'    => 'id',
                            'terms'    => $settings["query_taxonomy_{$tax}"]
                        ];
                    }
                }
            }

            if ($term !== '*') {
                if ($taxonomy === 'tag') {
                    $taxonomy = 'product_' . $_POST['king_addons_taxonomy'];
                }
                $tax_query[] = [
                    'taxonomy' => $taxonomy,
                    'field'    => 'slug',
                    'terms'    => $term
                ];
            }
        }

        // Filter by rating
        if (isset($_GET['filter_rating'])) {
            $product_visibility_terms = wc_get_product_visibility_term_ids();
            $filter_rating            = array_filter(array_map('absint', explode(',', wp_unslash($_GET['filter_rating']))));
            $rating_terms            = [];
            for ($i = 1; $i <= 5; $i++) {
                if (in_array($i, $filter_rating, true) && isset($product_visibility_terms['rated-' . $i])) {
                    $rating_terms[] = $product_visibility_terms['rated-' . $i];
                }
            }
            if (!empty($rating_terms)) {
                $tax_query[] = [
                    'taxonomy' => 'product_visibility',
                    'field'    => 'term_taxonomy_id',
                    'terms'    => $rating_terms,
                    'operator' => 'IN',
                ];
            }
        }

        return $tax_query;
    }

    public function get_animation_class($data, $object)
    {
        $class = '';
        // If animation is disabled on mobile, skip it
        if ('overlay' !== $object && 'yes' === ($data[$object . '_animation_disable_mobile'] ?? '') && wp_is_mobile()) {
            return $class;
        }
        if (($data[$object . '_animation'] ?? 'none') !== 'none') {
            $class .= ' king-addons-' . $object . '-' . $data[$object . '_animation'];
            $class .= ' king-addons-anim-size-' . $data[$object . '_animation_size'];
            $class .= ' king-addons-animation-timing-' . $data[$object . '_animation_timing'];

            if ('yes' === ($data[$object . '_animation_tr'] ?? '')) {
                $class .= ' king-addons-anim-transparency';
            }
        }
        return $class;
    }

    public function get_image_effect_class($settings)
    {
        // Restrict pro effects if not premium
        if (!king_addons_freemius()->can_use_premium_code__premium_only()) {
            if (in_array($settings['image_effects'], ['pro-zi', 'pro-zo', 'pro-go', 'pro-bo'], true)) {
                $settings['image_effects'] = 'none';
            }
        }
        $class = '';
        if ($settings['image_effects'] !== 'none') {
            $class .= ' king-addons-' . $settings['image_effects'];
        }
        // Slide effect has a different prefix than the “size” style.
        if ($settings['image_effects'] !== 'slide') {
            $class .= ' king-addons-effect-size-' . $settings['image_effects_size'];
        } else {
            $class .= ' king-addons-effect-dir-' . $settings['image_effects_direction'];
        }
        return $class;
    }

    public function render_password_protected_input($settings)
    {
        if (!post_password_required()) {
            return;
        }
        add_filter('the_password_form', function () {
            $output  = '<form action="' . esc_url(home_url('wp-login.php?action=postpass')) . '" method="post">';
            $output .= '<i class="fas fa-lock"></i>';
            $output .= '<p>' . esc_html(get_the_title()) . '</p>';
            $output .= '<input type="password" name="post_password" id="post-' . esc_attr(get_the_id()) . '" placeholder="' . esc_html__('Type and hit Enter...', 'king-addons') . '">';
            $output .= '</form>';
            return $output;
        });
        echo '<div class="king-addons-grid-item-protected king-addons-cv-container">';
        echo '<div class="king-addons-cv-outer">';
        echo '<div class="king-addons-cv-inner">';
        echo get_the_password_form();
        echo '</div></div></div>';
    }

    public function render_product_thumbnail($settings)
    {
        $id  = get_post_thumbnail_id();
        $src = Group_Control_Image_Size::get_attachment_image_src($id, 'layout_image_crop', $settings);
        $alt = wp_get_attachment_caption($id) === '' ? get_the_title() : wp_get_attachment_caption($id);

        $src2 = '';
        if (get_post_meta(get_the_ID(), 'king_addons_secondary_image_id', true)) {
            $src2 = Group_Control_Image_Size::get_attachment_image_src(
                get_post_meta(get_the_ID(), 'king_addons_secondary_image_id', true),
                'layout_image_crop',
                $settings
            );
        }

        if (has_post_thumbnail()) {
            echo '<div class="king-addons-grid-image-wrap" data-src="' . esc_url($src) . '" data-img-on-hover="' . esc_attr($settings['secondary_img_on_hover']) . '" data-src-secondary="' . esc_url($src2) . '">';
            echo '<img src="' . esc_url($src) . '" alt="' . esc_attr($alt) . '" class="king-addons-animation-timing-' . esc_attr($settings['image_effects_animation_timing']) . '">';
            if ('yes' === $settings['secondary_img_on_hover']) {
                echo '<img src="' . esc_url($src2) . '" alt="' . esc_attr($alt) . '" class="king-addons-hidden-img king-addons-animation-timing-' . esc_attr($settings['image_effects_animation_timing']) . '">';
            }
            echo '</div>';
        }
    }

    public function render_media_overlay($settings)
    {
        echo '<div class="king-addons-grid-media-hover-bg ' . esc_attr($this->get_animation_class($settings, 'overlay')) . '" data-url="' . esc_url(get_the_permalink()) . '">';
        if (king_addons_freemius()->can_use_premium_code__premium_only() && !empty($settings['overlay_image']['url'])) {
            echo '<img src="' . esc_url($settings['overlay_image']['url']) . '" alt="' . esc_attr($settings['overlay_image']['alt']) . '">';
        }
        echo '</div>';
    }

    public function render_product_title($settings, $class)
    {
        // Fallback for freemium
        $title_pointer            = king_addons_freemius()->can_use_premium_code__premium_only() ? $_POST['grid_settings']['title_pointer'] : 'none';
        $title_pointer_animation  = king_addons_freemius()->can_use_premium_code__premium_only() ? $_POST['grid_settings']['title_pointer_animation'] : 'fade';
        $pointer_item_class       = ($title_pointer !== 'none') ? 'class="king-addons-pointer-item"' : '';
        $open_links_in_new_tab    = ('yes' === $_POST['grid_settings']['open_links_in_new_tab']) ? '_blank' : '_self';

        $class .= ' king-addons-pointer-' . $title_pointer;
        $class .= ' king-addons-pointer-line-fx king-addons-pointer-fx-' . $title_pointer_animation;

        $tags_whitelist      = ['h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'div', 'span', 'p'];
        $element_title_tag   = Core::validateHTMLTags($settings['element_title_tag'], 'h2', $tags_whitelist);

        echo '<' . esc_attr($element_title_tag) . ' class="' . esc_attr($class) . '">';
        echo '<div class="inner-block">';
        echo '<a target="' . esc_attr($open_links_in_new_tab) . '" ' . $pointer_item_class . ' href="' . esc_url(get_the_permalink()) . '">';
        if ('word_count' === $settings['element_trim_text_by']) {
            echo esc_html(wp_trim_words(get_the_title(), $settings['element_word_count']));
        } else {
            echo esc_html(mb_substr(html_entity_decode(get_the_title()), 0, $settings['element_letter_count'])) . '...';
        }
        echo '</a>';
        echo '</div>';
        echo '</' . esc_attr($element_title_tag) . '>';
    }

    public function render_product_excerpt($settings, $class)
    {
        $excerpt = get_the_excerpt();
        if ('' === $excerpt) {
            return;
        }
        echo '<div class="' . esc_attr($class) . '">';
        echo '<div class="inner-block">';
        if ('word_count' === $settings['element_trim_text_by']) {
            echo '<p>' . esc_html(wp_trim_words($excerpt, $settings['element_word_count'])) . '</p>';
        } else {
            echo '<p>' . esc_html(mb_substr($excerpt, 0, $settings['element_letter_count'])) . '...</p>';
        }
        echo '</div>';
        echo '</div>';
    }

    public function render_product_categories($settings, $class, $post_id)
    {
        $terms = wp_get_post_terms($post_id, $settings['element_select']);
        if (empty($terms) || is_wp_error($terms)) {
            return;
        }

        $count                       = 0;
        $categories_pointer          = king_addons_freemius()->can_use_premium_code__premium_only() ? $_POST['grid_settings']['categories_pointer'] : 'none';
        $categories_pointer_animation= king_addons_freemius()->can_use_premium_code__premium_only() ? $_POST['grid_settings']['categories_pointer_animation'] : 'fade';
        $pointer_item_class          = ($categories_pointer !== 'none') ? 'class="king-addons-pointer-item"' : '';

        $class .= ' king-addons-pointer-' . $categories_pointer;
        $class .= ' king-addons-pointer-line-fx king-addons-pointer-fx-' . $categories_pointer_animation;

        echo '<div class="' . esc_attr($class) . ' king-addons-grid-product-categories">';
        echo '<div class="inner-block">';

        // Extra text/icon (before)
        if ('before' === $settings['element_extra_text_pos']) {
            echo '<span class="king-addons-grid-extra-text-left">' . esc_html($settings['element_extra_text']) . '</span>';
        }
        if ('before' === $settings['element_extra_icon_pos']) {
            ob_start();
            \Elementor\Icons_Manager::render_icon($settings['element_extra_icon'], ['aria-hidden' => 'true']);
            $extra_icon = ob_get_clean();
            echo '<span class="king-addons-grid-extra-icon-left">' . $extra_icon . '</span>';
        }

        // Render each term
        foreach ($terms as $term) {
            echo '<a ' . $pointer_item_class . ' href="' . esc_url(get_term_link($term->term_id)) . '">';
            echo esc_html($term->name);
            if (++$count !== count($terms)) {
                echo '<span class="tax-sep">' . esc_html($settings['element_tax_sep']) . '</span>';
            }
            echo '</a>';
        }

        // Extra text/icon (after)
        if ('after' === $settings['element_extra_icon_pos']) {
            ob_start();
            \Elementor\Icons_Manager::render_icon($settings['element_extra_icon'], ['aria-hidden' => 'true']);
            $extra_icon = ob_get_clean();
            echo '<span class="king-addons-grid-extra-icon-right">' . $extra_icon . '</span>';
        }
        if ('after' === $settings['element_extra_text_pos']) {
            echo '<span class="king-addons-grid-extra-text-right">' . esc_html($settings['element_extra_text']) . '</span>';
        }

        echo '</div></div>';
    }

    public function render_product_tags($settings, $class, $post_id)
    {
        $terms = wp_get_post_terms($post_id, $settings['element_select']);
        if (empty($terms) || is_wp_error($terms)) {
            return;
        }

        $count                  = 0;
        $tags_pointer           = king_addons_freemius()->can_use_premium_code__premium_only() ? $_POST['grid_settings']['tags_pointer'] : 'none';
        $tags_pointer_animation = king_addons_freemius()->can_use_premium_code__premium_only() ? $_POST['grid_settings']['tags_pointer_animation'] : 'fade';
        $pointer_item_class     = ($tags_pointer !== 'none') ? 'class="king-addons-pointer-item"' : '';

        $class .= ' king-addons-pointer-' . $tags_pointer;
        $class .= ' king-addons-pointer-line-fx king-addons-pointer-fx-' . $tags_pointer_animation;

        echo '<div class="' . esc_attr($class) . ' king-addons-grid-product-tags">';
        echo '<div class="inner-block">';

        if ('before' === $settings['element_extra_text_pos']) {
            echo '<span class="king-addons-grid-extra-text-left">' . esc_html($settings['element_extra_text']) . '</span>';
        }
        if ('before' === $settings['element_extra_icon_pos']) {
            ob_start();
            \Elementor\Icons_Manager::render_icon($settings['element_extra_icon'], ['aria-hidden' => 'true']);
            $extra_icon = ob_get_clean();
            echo '<span class="king-addons-grid-extra-icon-left">' . $extra_icon . '</span>';
        }

        foreach ($terms as $term) {
            echo '<a ' . $pointer_item_class . ' href="' . esc_url(get_term_link($term->term_id)) . '">';
            echo esc_html($term->name);
            if (++$count !== count($terms)) {
                echo '<span class="tax-sep">' . esc_html($settings['element_tax_sep']) . '</span>';
            }
            echo '</a>';
        }

        if ('after' === $settings['element_extra_icon_pos']) {
            ob_start();
            \Elementor\Icons_Manager::render_icon($settings['element_extra_icon'], ['aria-hidden' => 'true']);
            $extra_icon = ob_get_clean();
            echo '<span class="king-addons-grid-extra-icon-right">' . $extra_icon . '</span>';
        }
        if ('after' === $settings['element_extra_text_pos']) {
            echo '<span class="king-addons-grid-extra-text-right">' . esc_html($settings['element_extra_text']) . '</span>';
        }
        echo '</div></div>';
    }

    /** @noinspection DuplicatedCode */
    public function render_product_likes($settings, $class, $post_id)
    {
        $post_likes = new Post_Likes_Ajax();
        echo '<div class="' . esc_attr($class) . '">';
        echo '<div class="inner-block">';
        if ('before' === $settings['element_extra_text_pos']) {
            echo '<span class="king-addons-grid-extra-text-left">' . esc_html($settings['element_extra_text']) . '</span>';
        }
        echo $post_likes->get_button($post_id, $settings);
        if ('after' === $settings['element_extra_text_pos']) {
            echo '<span class="king-addons-grid-extra-text-right">' . esc_html($settings['element_extra_text']) . '</span>';
        }
        echo '</div></div>';
    }

    public function render_product_sharing_icons($settings, $class)
    {
        $args = [
            'icons'   => 'yes',
            'tooltip' => $settings['element_sharing_tooltip'],
            'url'     => esc_url(get_the_permalink()),
            'title'   => esc_html(get_the_title()),
            'text'    => esc_html(get_the_excerpt()),
            'image'   => esc_url(get_the_post_thumbnail_url()),
        ];

        $hidden_class = '';
        echo '<div class="' . esc_attr($class) . '">';
        echo '<div class="inner-block">';
        if ('before' === $settings['element_extra_text_pos']) {
            echo '<span class="king-addons-grid-extra-text-left">' . esc_html($settings['element_extra_text']) . '</span>';
        }
        echo '<span class="king-addons-post-sharing">';
        if ('yes' === $settings['element_sharing_trigger']) {
            $hidden_class = ' king-addons-sharing-hidden';
            $attributes   = ' data-action="' . esc_attr($settings['element_sharing_trigger_action']) . '"';
            $attributes  .= ' data-direction="' . esc_attr($settings['element_sharing_trigger_direction']) . '"';
            echo '<a class="king-addons-sharing-trigger king-addons-sharing-icon"' . $attributes . '>';
            if ('yes' === $settings['element_sharing_tooltip']) {
                echo '<span class="king-addons-sharing-tooltip king-addons-tooltip">' . esc_html__('Share', 'king-addons') . '</span>';
            }
            echo Core::getIcon($settings['element_sharing_trigger_icon'], '');
            echo '</a>';
        }

        echo '<span class="king-addons-post-sharing-inner' . $hidden_class . '">';
        for ($i = 1; $i <= 6; $i++) {
            $args['network'] = $settings['element_sharing_icon_' . $i];
            echo Core::getShareIcon($args);
        }
        echo '</span>';
        echo '</span>';
        if ('after' === $settings['element_extra_text_pos']) {
            echo '<span class="king-addons-grid-extra-text-right">' . esc_html($settings['element_extra_text']) . '</span>';
        }
        echo '</div></div>';
    }

    public function render_product_lightbox($settings, $class, $post_id)
    {
        echo '<div class="' . esc_attr($class) . '">';
        echo '<div class="inner-block">';

        $lightbox_source = get_the_post_thumbnail_url($post_id);
        if (get_post_format() === 'audio' && $settings['element_lightbox_pfa_select'] === 'meta') {
            $meta_value = get_post_meta($post_id, $settings['element_lightbox_pfa_meta'], true);
            if (false === strpos($meta_value, '<iframe ')) {
                add_filter(
                    'oembed_result',
                    $filter_cb = function ($html) {
                        preg_match('/<iframe.*src=\"(.*)\".*><\/iframe>/isU', $html, $matches);
                        return $matches[1] . '&auto_play=true';
                    },
                    50,
                    3
                );
                $track_url      = wp_oembed_get($meta_value);
                remove_filter('oembed_result', $filter_cb, 50);
                $lightbox_source = $track_url;
            } else {
                // If it's an <iframe> itself
                $lightbox_source = Core::filterOembedResults($meta_value);
            }
        } elseif (get_post_format() === 'video' && $settings['element_lightbox_pfv_select'] === 'meta') {
            $meta_value = get_post_meta($post_id, $settings['element_lightbox_pfv_meta'], true);
            if (false === strpos($meta_value, '<iframe ')) {
                $video = \Elementor\Embed::get_video_properties($meta_value);
            } else {
                $video = \Elementor\Embed::get_video_properties(Core::filterOembedResults($meta_value));
            }
            if (!empty($video['provider']) && !empty($video['video_id'])) {
                if ($video['provider'] === 'youtube') {
                    $lightbox_source = 'https://www.youtube.com/embed/' . $video['video_id'] . '?feature=oembed&autoplay=1&controls=1';
                } elseif ($video['provider'] === 'vimeo') {
                    $lightbox_source = 'https://player.vimeo.com/video/' . $video['video_id'] . '?autoplay=1#t=0';
                }
            }
        }

        echo '<span data-src="' . esc_url($lightbox_source) . '">';
        if ('before' === $settings['element_extra_text_pos']) {
            echo '<span class="king-addons-grid-extra-text-left">' . esc_html($settings['element_extra_text']) . '</span>';
        }
        echo '<i class="' . esc_attr($settings['element_extra_icon']['value']) . '"></i>';
        if ('after' === $settings['element_extra_text_pos']) {
            echo '<span class="king-addons-grid-extra-text-right">' . esc_html($settings['element_extra_text']) . '</span>';
        }
        echo '</span>';

        if ('yes' === $settings['element_lightbox_overlay']) {
            echo '<div class="king-addons-grid-lightbox-overlay"></div>';
        }
        echo '</div></div>';
    }

    public function render_product_element_separator($settings, $class)
    {
        echo '<div class="' . esc_attr($class . ' ' . $settings['element_separator_style']) . '">';
        echo '<div class="inner-block"><span></span></div>';
        echo '</div>';
    }

    public function render_product_status($settings, $class)
    {
        global $product;
        if (!$product) {
            return;
        }
        echo '<div class="' . esc_attr($class) . '">';
        echo '<div class="inner-block">';

        if ($product->is_on_sale()) {
            echo '<span class="king-addons-woocommerce-onsale">' . esc_html__('Sale', 'king-addons') . '</span>';
        }
        if (
            'yes' === $settings['element_status_offstock'] &&
            !$product->is_in_stock() &&
            !($product->is_type('variable') && $product->get_stock_quantity() > 0)
        ) {
            echo '<span class="king-addons-woocommerce-outofstock">' . esc_html__('Out of Stock', 'king-addons') . '</span>';
        }
        if ('yes' === $settings['element_status_featured'] && $product->is_featured()) {
            echo '<span class="king-addons-woocommerce-featured">' . esc_html__('Featured', 'king-addons') . '</span>';
        }
        echo '</div></div>';
    }

    public function render_product_price($settings, $class)
    {
        global $product;
        if (!$product) {
            return;
        }
        echo '<div class="' . esc_attr($class) . '">';
        echo '<div class="inner-block">';
        echo '<span>' . wp_kses_post($product->get_price_html()) . '</span>';

        // Additional hooking for custom logic
        $sale_price_dates_to = '';
        if ($date = get_post_meta($product->get_id(), '_sale_price_dates_to', true)) {
            $sale_price_dates_to = date_i18n('Y-m-d', $date);
        }
        $sale_price_dates_to = apply_filters('king_addons_custom_sale_price_dates_to_filter', $sale_price_dates_to, $product);
        echo $sale_price_dates_to;

        echo '</div></div>';
    }

    public function render_product_sale_dates($settings, $class)
    {
        global $product;
        if (!$product) {
            return;
        }

        $sale_price_dates_from = '';
        if ($date = get_post_meta($product->get_id(), '_sale_price_dates_from', true)) {
            $sale_price_dates_from = date_i18n(get_option('date_format'), $date);
        }
        $sale_price_dates_to = '';
        if ($date = get_post_meta($product->get_id(), '_sale_price_dates_to', true)) {
            $sale_price_dates_to = date_i18n(get_option('date_format'), $date);
        }

        $show_start = ('yes' === $settings['show_sale_starts_date'] && !empty($sale_price_dates_from));
        $show_end   = ('yes' === $settings['show_sale_ends_date'] && !empty($sale_price_dates_to));
        if ($show_start || $show_end) {
            echo '<div class="' . esc_attr($class) . '">';
            echo '<div class="inner-block">';
            echo '<span class="king-addons-sale-dates">';

            if (!empty($settings['element_sale_starts_text']) && $show_start) {
                echo '<span class="king-addons-grid-extra-text-left">' . esc_html($settings['element_sale_starts_text']) . '</span> ';
            }
            if ($show_start) {
                echo '<span>' . $sale_price_dates_from . '</span>';
            }
            if (
                !empty($settings['element_sale_dates_sep']) &&
                $settings['element_sale_dates_layout'] === 'inline' &&
                $show_start && $show_end
            ) {
                echo esc_html($settings['element_sale_dates_sep']);
            }
            if ($settings['element_sale_dates_layout'] === 'block' && $show_start && $show_end) {
                echo '<br>';
            }
            if (!empty($settings['element_sale_ends_text']) && $show_end) {
                echo '<span class="king-addons-grid-extra-text-left">' . esc_html($settings['element_sale_ends_text']) . '</span> ';
            }
            if ($show_end) {
                echo '<span>' . $sale_price_dates_to . '</span>';
            }
            echo '</span>';
            echo '</div></div>';
        }
    }

    public function render_product_rating($settings, $class)
    {
        global $product;
        if (!$product) {
            return;
        }
        $rating_amount = floatval($product->get_average_rating());
        $round_rating  = (int) $rating_amount;
        $rating_icon   = '&#xE934;'; // default star icon

        if ($settings['element_rating_style'] === 'style-1') {
            if ($settings['element_rating_unmarked_style'] === 'outline') {
                $rating_icon = '&#xE933;';
            }
            $style_class = ' king-addons-woocommerce-rating-style-1';
        } elseif ($settings['element_rating_style'] === 'style-2') {
            if ($settings['element_rating_unmarked_style'] === 'outline') {
                $rating_icon = '&#9734;';
            } else {
                $rating_icon = '&#9733;';
            }
            $style_class = ' king-addons-woocommerce-rating-style-2';
        } else {
            $style_class = '';
        }

        echo '<div class="' . esc_attr($class . $style_class) . '">';
        echo '<div class="inner-block">';
        echo '<div class="king-addons-woocommerce-rating">';
        if ('yes' === $settings['element_rating_score']) {
            // Ensure format X.0 if integer
            if (in_array($rating_amount, [1,2,3,4,5], true)) {
                $rating_amount = $rating_amount . '.0';
            }
            echo '<i class="king-addons-rating-icon-10">' . $rating_icon . '</i>';
            echo '<span>' . esc_html($rating_amount) . '</span>';
        } else {
            // Star-by-star
            for ($i = 1; $i <= 5; $i++) {
                if ($i <= $rating_amount) {
                    echo '<i class="king-addons-rating-icon-full">' . $rating_icon . '</i>';
                } elseif ($i === ($round_rating + 1) && $rating_amount !== $round_rating) {
                    // partial star
                    $partial = ($rating_amount - $round_rating) * 10;
                    echo '<i class="king-addons-rating-icon-' . (int) $partial . '">' . $rating_icon . '</i>';
                } else {
                    echo '<i class="king-addons-rating-icon-empty">' . $rating_icon . '</i>';
                }
            }
        }
        echo '</div></div></div>';
    }

    public function render_product_add_to_cart($settings, $class)
    {
        global $product;
        if (!$product) {
            return;
        }

        $button_class = implode(' ', array_filter([
            'product_type_' . $product->get_type(),
            ($product->is_purchasable() && $product->is_in_stock()) ? 'add_to_cart_button' : '',
            $product->supports('ajax_add_to_cart') ? 'ajax_add_to_cart' : '',
        ]));

        // Freemium fallback
        $add_to_cart_animation = king_addons_freemius()->can_use_premium_code__premium_only()
            ? $_POST['grid_settings']['add_to_cart_animation']
            : 'king-addons-button-none';

        $popup_notification_animation          = $_POST['grid_settings']['popup_notification_animation'] ?? '';
        $popup_notification_fade_out_in        = $_POST['grid_settings']['popup_notification_fade_out_in'] ?? '';
        $popup_notification_animation_duration = $_POST['grid_settings']['popup_notification_animation_duration'] ?? '';

        $attributes = [
            'rel="nofollow"',
            'class="' . esc_attr("$button_class king-addons-button-effect $add_to_cart_animation") . (
            !$product->is_in_stock() && $product->get_type() === 'simple' ? ' king-addons-atc-not-clickable' : ''
            ) . '"',
            'aria-label="' . esc_attr($product->add_to_cart_description()) . '"',
            'data-product_id="' . esc_attr($product->get_id()) . '"',
            'data-product_sku="' . esc_attr($product->get_sku()) . '"',
            'data-atc-popup="' . esc_attr($settings['element_show_added_tc_popup']) . '"',
            'data-atc-animation="' . esc_attr($popup_notification_animation) . '"',
            'data-atc-fade-out-in="' . esc_attr($popup_notification_fade_out_in) . '"',
            'data-atc-animation-time="' . esc_attr($popup_notification_animation_duration) . '"'
        ];

        $button_HTML   = '';
        $page_id       = get_queried_object_id();

        // Icon before text
        if ('before' === $settings['element_extra_icon_pos']) {
            ob_start();
            \Elementor\Icons_Manager::render_icon($settings['element_extra_icon'], ['aria-hidden' => 'true']);
            $extra_icon   = ob_get_clean();
            $button_HTML .= '<span class="king-addons-grid-extra-icon-left">' . $extra_icon . '</span>';
        }

        switch ($product->get_type()) {
            case 'simple':
                $button_HTML .= $settings['element_addcart_simple_txt'];
                if ('yes' === get_option('woocommerce_enable_ajax_add_to_cart')) {
                    $attributes[] = 'href="' . esc_url(get_permalink($page_id) . '/?add-to-cart=' . get_the_ID()) . '"';
                } else {
                    $attributes[] = 'href="' . esc_url(get_permalink()) . '"';
                }
                break;
            case 'grouped':
                $button_HTML .= $settings['element_addcart_grouped_txt'];
                $attributes[]  = 'href="' . esc_url(get_permalink()) . '"';
                break;
            case 'variable':
                $button_HTML .= $settings['element_addcart_variable_txt'];
                $attributes[]  = 'href="' . esc_url(get_permalink()) . '"';
                break;
            case 'pw-gift-card':
            case 'ywf_deposit':
            case 'stm_lms_product':
            case 'redq_rental':
                $button_HTML .= esc_html__('View Product', 'king-addons');
                $attributes[]  = 'href="' . esc_url(get_permalink()) . '"';
                break;
            default:
                // External or custom product
                if (is_callable([$product, 'get_product_url'])) {
                    $attributes[] = 'href="' . esc_url($product->get_product_url()) . '"';
                    $text         = get_post_meta(get_the_ID(), '_button_text', true);
                    $button_HTML .= $text ? $text : esc_html__('Buy Product');
                } else {
                    // fallback
                    $button_HTML .= esc_html__('View Product', 'king-addons');
                    $attributes[] = 'href="' . esc_url(get_permalink()) . '"';
                }
        }

        // Icon after text
        if ('after' === $settings['element_extra_icon_pos']) {
            ob_start();
            \Elementor\Icons_Manager::render_icon($settings['element_extra_icon'], ['aria-hidden' => 'true']);
            $extra_icon   = ob_get_clean();
            $button_HTML .= '<span class="king-addons-grid-extra-icon-right">' . $extra_icon . '</span>';
        }

        echo '<div class="' . esc_attr($class) . '">';
        echo '<div class="inner-block">';
        $filtered_btn = apply_filters('woocommerce_loop_add_to_cart_link', $button_HTML, $product);
        if ($filtered_btn !== $button_HTML) {
            // If some plugin has replaced the entire button
            echo $filtered_btn;
        } else {
            // Output our link
            echo '<a ' . implode(' ', $attributes) . '><span>' . $button_HTML . '</span></a>';
        }
        echo '</div></div>';
    }

    public function get_compare_from_cookie()
    {
        // Merge both cookies into a single check
        $cookie = $_COOKIE['king_addons_compare'] ?? $_COOKIE['king_addons_compare_' . get_current_blog_id()] ?? '';
        return $cookie ? json_decode(stripslashes($cookie), true) : [];
    }

    public function get_wishlist_from_cookie()
    {
        $cookie = $_COOKIE['king_addons_wishlist'] ?? $_COOKIE['king_addons_wishlist_' . get_current_blog_id()] ?? '';
        return $cookie ? json_decode(stripslashes($cookie), true) : [];
    }

    public function render_product_wishlist_button($settings, $class)
    {
        if (!king_addons_freemius()->can_use_premium_code__premium_only()) {
            return;
        }
        global $product;
        if (!$product) {
            return;
        }
        $user_id = get_current_user_id();
        $wishlist = $user_id > 0
            ? get_user_meta($user_id, 'king_addons_wishlist', true)
            : $this->get_wishlist_from_cookie();

        if (!$wishlist) {
            $wishlist = [];
        }

        $popup_notification_animation          = $_POST['grid_settings']['popup_notification_animation'] ?? '';
        $popup_notification_fade_out_in        = $_POST['grid_settings']['popup_notification_fade_out_in'] ?? '';
        $popup_notification_animation_duration = $_POST['grid_settings']['popup_notification_animation_duration'] ?? '';

        $wishlist_attributes = [
            'data-wishlist-url="' . (get_option('king_addons_wishlist_page') ?: '') . '"',
            'data-atw-popup="' . esc_attr($settings['element_show_added_to_wishlist_popup']) . '"',
            'data-atw-animation="' . esc_attr($popup_notification_animation) . '"',
            'data-atw-fade-out-in="' . esc_attr($popup_notification_fade_out_in) . '"',
            'data-atw-animation-time="' . esc_attr($popup_notification_animation_duration) . '"',
            'data-open-in-new-tab="' . esc_attr($settings['element_open_links_in_new_tab']) . '"'
        ];

        $add_to_wishlist_content    = '';
        $remove_from_wishlist_content = '';
        $button_add_title           = '';
        $button_remove_title        = '';

        if ('yes' === $settings['show_icon']) {
            $add_to_wishlist_content    .= '<i class="far fa-heart"></i>';
            $remove_from_wishlist_content .= '<i class="fas fa-heart"></i>';
        }
        if ('yes' === $settings['show_text']) {
            $add_to_wishlist_content    .= ' <span>' . esc_html($settings['add_to_wishlist_text']) . '</span>';
            $remove_from_wishlist_content .= ' <span>' . esc_html($settings['remove_from_wishlist_text']) . '</span>';
        } else {
            $button_add_title    = 'title="' . esc_attr($settings['add_to_wishlist_text']) . '"';
            $button_remove_title = 'title="' . esc_attr($settings['remove_from_wishlist_text']) . '"';
        }

        echo '<div class="' . esc_attr($class) . '">';
        echo '<div class="inner-block">';

        $remove_btn_hidden = !in_array($product->get_id(), $wishlist) ? 'king-addons-button-hidden' : '';
        $add_btn_hidden    = in_array($product->get_id(), $wishlist) ? 'king-addons-button-hidden' : '';

        echo '<button class="king-addons-wishlist-add ' . $add_btn_hidden . '" ' . $button_add_title . ' data-product-id="' . esc_attr($product->get_id()) . '" ' . implode(' ', $wishlist_attributes) . '>';
        echo $add_to_wishlist_content;
        echo '</button>';

        echo '<button class="king-addons-wishlist-remove ' . $remove_btn_hidden . '" ' . $button_remove_title . ' data-product-id="' . esc_attr($product->get_id()) . '">';
        echo $remove_from_wishlist_content;
        echo '</button>';

        echo '</div></div>';
    }

    public function render_product_compare_button($settings, $class)
    {
        if (!king_addons_freemius()->can_use_premium_code__premium_only()) {
            return;
        }
        global $product;
        if (!$product) {
            return;
        }

        $user_id = get_current_user_id();
        $compare = [];
        if ($user_id > 0) {
            $compare = get_user_meta($user_id, 'king_addons_compare', true) ?: [];
        } else {
            $compare = $this->get_compare_from_cookie();
        }

        $popup_notification_animation          = $_POST['grid_settings']['popup_notification_animation'] ?? '';
        $popup_notification_fade_out_in        = $_POST['grid_settings']['popup_notification_fade_out_in'] ?? '';
        $popup_notification_animation_duration = $_POST['grid_settings']['popup_notification_animation_duration'] ?? '';

        $compare_attributes = [
            'data-compare-url="' . (get_option('king_addons_compare_page') ?: '') . '"',
            'data-atcompare-popup="' . esc_attr($settings['element_show_added_to_compare_popup']) . '"',
            'data-atcompare-animation="' . esc_attr($popup_notification_animation) . '"',
            'data-atcompare-fade-out-in="' . esc_attr($popup_notification_fade_out_in) . '"',
            'data-atcompare-animation-time="' . esc_attr($popup_notification_animation_duration) . '"',
            'data-open-in-new-tab="' . esc_attr($settings['element_open_links_in_new_tab']) . '"'
        ];

        $add_to_compare_content    = '';
        $remove_from_compare_content = '';
        $button_add_title          = '';
        $button_remove_title       = '';

        if ('yes' === $settings['show_icon']) {
            $add_to_compare_content    .= '<i class="fas fa-exchange-alt"></i>';
            $remove_from_compare_content .= '<i class="fas fa-exchange-alt"></i>';
        }
        if ('yes' === $settings['show_text']) {
            $add_to_compare_content    .= ' <span>' . esc_html($settings['add_to_compare_text']) . '</span>';
            $remove_from_compare_content .= ' <span>' . esc_html($settings['remove_from_compare_text']) . '</span>';
        } else {
            $button_add_title    = 'title="' . esc_attr($settings['add_to_compare_text']) . '"';
            $button_remove_title = 'title="' . esc_attr($settings['remove_from_compare_text']) . '"';
        }

        echo '<div class="' . esc_attr($class) . '">';
        echo '<div class="inner-block">';

        $remove_btn_hidden = !in_array($product->get_id(), $compare) ? 'king-addons-button-hidden' : '';
        $add_btn_hidden    = in_array($product->get_id(), $compare) ? 'king-addons-button-hidden' : '';

        echo '<button class="king-addons-compare-add ' . $add_btn_hidden . '" ' . $button_add_title . ' data-product-id="' . esc_attr($product->get_id()) . '" ' . implode(' ', $compare_attributes) . '>';
        echo $add_to_compare_content;
        echo '</button>';

        echo '<button class="king-addons-compare-remove ' . $remove_btn_hidden . '" ' . $button_remove_title . ' data-product-id="' . esc_attr($product->get_id()) . '">';
        echo $remove_from_compare_content;
        echo '</button>';

        echo '</div></div>';
    }

    public function render_product_custom_fields($settings, $class, $post_id)
    {
        $custom_field_value = get_post_meta($post_id, $settings['element_custom_field'], true);

        // If empty, check if it's a product attribute
        if (empty($custom_field_value)) {
            $product        = wc_get_product($post_id);
            $attribute_name = $settings['element_custom_field'];
            if ($product && $product->get_attribute($attribute_name)) {
                $custom_field_value = $product->get_attribute($attribute_name);
            }
        }

        if (has_filter('king_addons_update_custom_field_value')) {
            ob_start();
            do_action('king_addons_update_custom_field_value', $custom_field_value, $post_id, $settings['element_custom_field']);
            $custom_field_value = ob_get_clean();
        }

        // If single-item array, reduce to string
        if (is_array($custom_field_value) && count($custom_field_value) === 1) {
            $custom_field_value = $custom_field_value[0] ?? '';
        }

        // Must be string or numeric to render
        if (!is_string($custom_field_value) && !is_numeric($custom_field_value)) {
            $custom_field_value = '';
        }

        if ($custom_field_value === '') {
            return;
        }

        echo '<div class="' . esc_attr($class . ' ' . $settings['element_custom_field_style']) . '">';
        echo '<div class="inner-block">';
        if ('yes' === $settings['element_custom_field_btn_link']) {
            $target = ('yes' === $settings['element_custom_field_new_tab']) ? '_blank' : '_self';
            echo '<a href="' . esc_url($custom_field_value) . '" target="' . esc_attr($target) . '">';
        } else {
            echo '<span>';
        }

        // Extra text/icon (before)
        if ('before' === $settings['element_extra_text_pos']) {
            echo '<span class="king-addons-grid-extra-text-left">' . esc_html($settings['element_extra_text']) . '</span>';
        }
        if ('before' === $settings['element_extra_icon_pos']) {
            ob_start();
            \Elementor\Icons_Manager::render_icon($settings['element_extra_icon'], ['aria-hidden' => 'true']);
            $extra_icon = ob_get_clean();
            echo '<span class="king-addons-grid-extra-icon-left">' . $extra_icon . '</span>';
        }

        // If it's an image ID
        if (!empty($settings['element_custom_field_img_ID']) && $settings['element_custom_field_img_ID'] === 'yes') {
            $cf_img = wp_get_attachment_image_src($custom_field_value, 'full');
            if (!empty($cf_img)) {
                echo '<img src="' . esc_url($cf_img[0]) . '" alt="" width="' . esc_attr($cf_img[1]) . '" height="' . esc_attr($cf_img[2]) . '">';
            }
        } else {
            // Just output text
            if ('yes' !== $settings['element_custom_field_btn_link']) {
                echo '<span>' . wp_kses_post($custom_field_value) . '</span>';
            } else {
                echo wp_kses_post($custom_field_value);
            }
        }

        // Extra text/icon (after)
        if ('after' === $settings['element_extra_icon_pos']) {
            ob_start();
            \Elementor\Icons_Manager::render_icon($settings['element_extra_icon'], ['aria-hidden' => 'true']);
            $extra_icon = ob_get_clean();
            echo '<span class="king-addons-grid-extra-icon-right">' . $extra_icon . '</span>';
        }
        if ('after' === $settings['element_extra_text_pos']) {
            echo '<span class="king-addons-grid-extra-text-right">' . esc_html($settings['element_extra_text']) . '</span>';
        }

        if ('yes' === $settings['element_custom_field_btn_link']) {
            echo '</a>';
        } else {
            echo '</span>';
        }
        echo '</div></div>';
    }

    public function get_elements($type, $settings, $class, $post_id)
    {
        // Map certain "pro" placeholders to 'title'
        if (in_array($type, ['pro-lk', 'pro-shr', 'pro-sd', 'pro-ws', 'pro-cm', 'pro-cfa'], true)) {
            $type = 'title';
        }

        switch ($type) {
            case 'title':
                $this->render_product_title($settings, $class);
                break;
            case 'excerpt':
                $this->render_product_excerpt($settings, $class);
                break;
            case 'product_cat':
                $this->render_product_categories($settings, $class, $post_id);
                break;
            case 'product_tag':
                $this->render_product_tags($settings, $class, $post_id);
                break;
            case 'likes':
                $this->render_product_likes($settings, $class, $post_id);
                break;
            case 'sharing':
                $this->render_product_sharing_icons($settings, $class);
                break;
            case 'lightbox':
                $this->render_product_lightbox($settings, $class, $post_id);
                break;
            case 'separator':
                $this->render_product_element_separator($settings, $class);
                break;
            case 'status':
                $this->render_product_status($settings, $class);
                break;
            case 'price':
                $this->render_product_price($settings, $class);
                break;
            case 'sale_dates':
                $this->render_product_sale_dates($settings, $class);
                break;
            case 'rating':
                $this->render_product_rating($settings, $class);
                break;
            case 'add-to-cart':
                $this->render_product_add_to_cart($settings, $class);
                break;
            case 'wishlist-button':
                if (king_addons_freemius()->can_use_premium_code__premium_only()) {
                    $this->render_product_wishlist_button($settings, $class);
                }
                break;
            case 'compare-button':
                if (king_addons_freemius()->can_use_premium_code__premium_only()) {
                    $this->render_product_compare_button($settings, $class);
                }
                break;
            case 'custom-field':
                $this->render_product_custom_fields($settings, $class, $post_id);
                break;
            default:
                // fallback
                $this->render_product_categories($settings, $class, $post_id);
                break;
        }
    }

    public function get_meta_query_args()
    {
        $meta_query = WC()->query->get_meta_query();
        if (isset($_GET['min_price']) || isset($_GET['max_price'])) {
            $meta_query = array_merge(['relation' => 'AND'], $meta_query);
            $meta_query[] = [
                'key'     => '_price',
                'value'   => [$_GET['min_price'], $_GET['max_price']],
                'compare' => 'BETWEEN',
                'type'    => 'NUMERIC'
            ];
        }
        return $meta_query;
    }

    public function get_elements_by_location($location, $settings, $post_id)
    {
        $locations = [];
        if (!empty($settings['grid_elements'])) {
            foreach ($settings['grid_elements'] as $data) {
                $place   = $data['element_location'];
                $align_v = king_addons_freemius()->can_use_premium_code__premium_only()
                    ? ($data['element_align_vr'] ?? 'middle')
                    : 'middle';

                if (!isset($locations[$place])) {
                    $locations[$place] = [];
                }
                // If the elements are "over" the image, also group by vertical alignment
                if ($place === 'over') {
                    if (!isset($locations[$place][$align_v])) {
                        $locations[$place][$align_v] = [];
                    }
                    $locations[$place][$align_v][] = $data;
                } else {
                    $locations[$place][] = $data;
                }
            }
        }

        if (empty($locations[$location])) {
            return;
        }

        // If location is 'over', we might have multiple vertical align blocks
        if ($location === 'over') {
            foreach ($locations[$location] as $align => $elements) {
                if ($align === 'middle') {
                    echo '<div class="king-addons-cv-container"><div class="king-addons-cv-outer"><div class="king-addons-cv-inner">';
                }
                echo '<div class="king-addons-grid-media-hover-' . esc_attr($align) . ' elementor-clearfix">';
                foreach ($elements as $data) {
                    $class  = 'king-addons-grid-item-' . $data['element_select'];
                    $class .= ' elementor-repeater-item-' . $data['_id'];
                    $class .= ' king-addons-grid-item-display-' . $data['element_display'];
                    $class .= ' king-addons-grid-item-align-' . $data['element_align_hr'];
                    $class .= $this->get_animation_class($data, 'element');
                    $this->get_elements($data['element_select'], $data, $class, $post_id);
                }
                echo '</div>';
                if ($align === 'middle') {
                    echo '</div></div></div>';
                }
            }
        } else {
            // 'above' or 'below'
            echo '<div class="king-addons-grid-item-' . esc_attr($location) . '-content elementor-clearfix">';
            foreach ($locations[$location] as $data) {
                $class  = 'king-addons-grid-item-' . $data['element_select'];
                $class .= ' elementor-repeater-item-' . $data['_id'];
                $class .= ' king-addons-grid-item-display-' . $data['element_display'];
                $class .= ' king-addons-grid-item-align-' . $data['element_align_hr'];
                $this->get_elements($data['element_select'], $data, $class, $post_id);
            }
            echo '</div>';
        }
    }

    public function get_hidden_filter_class($slug, $settings)
    {
        $posts = new WP_Query($this->get_main_query_args());
        $visible_categories = [];
        if ($posts->have_posts()) {
            while ($posts->have_posts()) {
                $posts->the_post();
                $categories = get_the_category();
                foreach ($categories as $cat) {
                    $visible_categories[] = $cat->slug;
                }
            }
            $visible_categories = array_unique($visible_categories);
            wp_reset_postdata();
        }
        return (!in_array($slug, $visible_categories, true) && $settings['filters_hide_empty'] === 'yes')
            ? ' king-addons-hidden-element'
            : '';
    }

    public function render_grid_pagination($settings)
    {
        if ($settings['layout_pagination'] !== 'yes' || $this->get_max_num_pages($settings) === 1 || $settings['layout_select'] === 'slider') {
            return;
        }
        global $paged;
        $pages = $this->get_max_num_pages($settings);
        $paged = empty($paged) ? 1 : $paged;

        if (!king_addons_freemius()->can_use_premium_code__premium_only()) {
            if ($settings['pagination_type'] === 'pro-is') {
                $settings['pagination_type'] = 'default';
            }
        }

        echo '<div class="king-addons-grid-pagination elementor-clearfix king-addons-grid-pagination-' . esc_attr($settings['pagination_type']) . '">';
        switch ($settings['pagination_type']) {
            case 'default':
                // Older / Newer style
                if ($paged < $pages) {
                    echo '<a href="' . esc_url(get_pagenum_link($paged + 1, true)) . '" class="king-addons-prev-post-link">';
                    echo Core::getIcon($settings['pagination_on_icon'], 'left');
                    echo esc_html($settings['pagination_older_text']);
                    echo '</a>';
                } elseif ($settings['pagination_disabled_arrows'] === 'yes') {
                    echo '<span class="king-addons-prev-post-link king-addons-disabled-arrow">';
                    echo Core::getIcon($settings['pagination_on_icon'], 'left');
                    echo esc_html($settings['pagination_older_text']);
                    echo '</span>';
                }

                if ($paged > 1) {
                    echo '<a href="' . esc_url(get_pagenum_link($paged - 1, true)) . '" class="king-addons-next-post-link">';
                    echo esc_html($settings['pagination_newer_text']);
                    echo Core::getIcon($settings['pagination_on_icon'], 'right');
                    echo '</a>';
                } elseif ($settings['pagination_disabled_arrows'] === 'yes') {
                    echo '<span class="king-addons-next-post-link king-addons-disabled-arrow">';
                    echo esc_html($settings['pagination_newer_text']);
                    echo Core::getIcon($settings['pagination_on_icon'], 'right');
                    echo '</span>';
                }
                break;

            case 'numbered':
                $range     = $settings['pagination_range'];
                $showitems = ($range * 2) + 1;

                if ($pages > 1) {
                    if ($settings['pagination_prev_next'] === 'yes' || $settings['pagination_first_last'] === 'yes') {
                        echo '<div class="king-addons-grid-pagination-left-arrows">';

                        // First
                        if ($settings['pagination_first_last'] === 'yes') {
                            if ($paged >= 2) {
                                echo '<a href="' . esc_url(get_pagenum_link(1, true)) . '" class="king-addons-first-page">';
                                echo Core::getIcon($settings['pagination_fl_icon'], 'left');
                                echo '<span>' . esc_html($settings['pagination_first_text']) . '</span></a>';
                            } elseif ($settings['pagination_disabled_arrows'] === 'yes') {
                                echo '<span class="king-addons-first-page king-addons-disabled-arrow">';
                                echo Core::getIcon($settings['pagination_fl_icon'], 'left');
                                echo '<span>' . esc_html($settings['pagination_first_text']) . '</span></span>';
                            }
                        }

                        // Previous
                        if ($settings['pagination_prev_next'] === 'yes') {
                            if ($paged > 1) {
                                echo '<a href="' . esc_url(get_pagenum_link($paged - 1, true)) . '" class="king-addons-prev-page">';
                                echo Core::getIcon($settings['pagination_pn_icon'], 'left');
                                echo '<span>' . esc_html($settings['pagination_prev_text']) . '</span></a>';
                            } elseif ($settings['pagination_disabled_arrows'] === 'yes') {
                                echo '<span class="king-addons-prev-page king-addons-disabled-arrow">';
                                echo Core::getIcon($settings['pagination_pn_icon'], 'left');
                                echo '<span>' . esc_html($settings['pagination_prev_text']) . '</span></span>';
                            }
                        }
                        echo '</div>';
                    }

                    for ($i = 1; $i <= $pages; $i++) {
                        if (!( $i >= $paged + $range + 1 || $i <= $paged - $range - 1 ) || $pages <= $showitems) {
                            if ($paged === $i) {
                                echo '<span class="king-addons-grid-current-page">' . esc_html($i) . '</span>';
                            } else {
                                echo '<a href="' . esc_url(get_pagenum_link($i, true)) . '">' . esc_html($i) . '</a>';
                            }
                        }
                    }

                    if ($settings['pagination_prev_next'] === 'yes' || $settings['pagination_first_last'] === 'yes') {
                        echo '<div class="king-addons-grid-pagination-right-arrows">';

                        // Next
                        if ($settings['pagination_prev_next'] === 'yes') {
                            if ($paged < $pages) {
                                echo '<a href="' . esc_url(get_pagenum_link($paged + 1, true)) . '" class="king-addons-next-page">';
                                echo '<span>' . esc_html($settings['pagination_next_text']) . '</span>';
                                echo Core::getIcon($settings['pagination_pn_icon'], 'right');
                                echo '</a>';
                            } elseif ($settings['pagination_disabled_arrows'] === 'yes') {
                                echo '<span class="king-addons-next-page king-addons-disabled-arrow">';
                                echo '<span>' . esc_html($settings['pagination_next_text']) . '</span>';
                                echo Core::getIcon($settings['pagination_pn_icon'], 'right');
                                echo '</span>';
                            }
                        }

                        // Last
                        if ($settings['pagination_first_last'] === 'yes') {
                            if ($paged <= $pages - 1) {
                                echo '<a href="' . esc_url(get_pagenum_link($pages, true)) . '" class="king-addons-last-page">';
                                echo '<span>' . esc_html($settings['pagination_last_text']) . '</span>';
                                echo Core::getIcon($settings['pagination_fl_icon'], 'right');
                                echo '</a>';
                            } elseif ($settings['pagination_disabled_arrows'] === 'yes') {
                                echo '<span class="king-addons-last-page king-addons-disabled-arrow">';
                                echo '<span>' . esc_html($settings['pagination_last_text']) . '</span>';
                                echo Core::getIcon($settings['pagination_fl_icon'], 'right');
                                echo '</span>';
                            }
                        }
                        echo '</div>';
                    }
                }
                break;

            default:
                // "Load More" style
                echo '<a href="' . esc_url(get_pagenum_link($paged + 1, true)) . '" class="king-addons-load-more-btn" data-e-disable-page-transition>';
                echo esc_html($settings['pagination_load_more_text']);
                echo '</a>';
                echo '<div class="king-addons-pagination-loading">';
                switch ($settings['pagination_animation']) {
                    case 'loader-1':
                        echo '<div class="king-addons-double-bounce">';
                        echo '<div class="king-addons-child king-addons-double-bounce1"></div>';
                        echo '<div class="king-addons-child king-addons-double-bounce2"></div>';
                        echo '</div>';
                        break;
                    case 'loader-2':
                        echo '<div class="king-addons-wave">';
                        echo '<div class="king-addons-rect king-addons-rect1"></div>';
                        echo '<div class="king-addons-rect king-addons-rect2"></div>';
                        echo '<div class="king-addons-rect king-addons-rect3"></div>';
                        echo '<div class="king-addons-rect king-addons-rect4"></div>';
                        echo '<div class="king-addons-rect king-addons-rect5"></div>';
                        echo '</div>';
                        break;
                    case 'loader-3':
                        echo '<div class="king-addons-spinner king-addons-spinner-pulse"></div>';
                        break;
                    case 'loader-4':
                        echo '<div class="king-addons-chasing-dots">';
                        echo '<div class="king-addons-child king-addons-dot1"></div>';
                        echo '<div class="king-addons-child king-addons-dot2"></div>';
                        echo '</div>';
                        break;
                    case 'loader-5':
                        echo '<div class="king-addons-three-bounce">';
                        echo '<div class="king-addons-child king-addons-bounce1"></div>';
                        echo '<div class="king-addons-child king-addons-bounce2"></div>';
                        echo '<div class="king-addons-child king-addons-bounce3"></div>';
                        echo '</div>';
                        break;
                    case 'loader-6':
                        echo '<div class="king-addons-fading-circle">';
                        for ($c = 1; $c <= 12; $c++) {
                            echo '<div class="king-addons-circle king-addons-circle' . $c . '"></div>';
                        }
                        echo '</div>';
                        break;
                }
                echo '</div>';
                echo '<p class="king-addons-pagination-finish">' . esc_html($settings['pagination_finish_text']) . '</p>';
                break;
        }
        echo '</div>';
    }

    public function king_addons_get_woocommerce_filtered_count()
    {
        $settings   = $_POST['grid_settings'];
        $page_count = $this->get_max_num_pages($settings);

        wp_send_json_success(['page_count' => $page_count]);
        wp_die();
    }

    public function king_addons_filter_woocommerce_products()
    {
        $settings = $_POST['grid_settings'];
        $posts    = new WP_Query($this->get_main_query_args());

        if ($posts->have_posts()) {
            while ($posts->have_posts()) {
                $posts->the_post();
                $post_class = implode(' ', get_post_class('king-addons-grid-item elementor-clearfix', get_the_ID()));

                echo '<article class="' . esc_attr($post_class) . '">';
                $this->render_password_protected_input($settings);

                echo '<div class="king-addons-grid-item-inner">';
                $this->get_elements_by_location('above', $settings, get_the_ID());

                if (has_post_thumbnail()) {
                    echo '<div class="king-addons-grid-media-wrap' . esc_attr($this->get_image_effect_class($settings)) . '" data-overlay-link="' . esc_attr($settings['overlay_post_link']) . '">';
                    $this->render_product_thumbnail($settings, get_the_ID());
                    echo '<div class="king-addons-grid-media-hover king-addons-animation-wrap">';
                    $this->render_media_overlay($settings);
                    $this->get_elements_by_location('over', $settings, get_the_ID());
                    echo '</div></div>';
                }

                $this->get_elements_by_location('below', $settings, get_the_ID());
                echo '</div></article>';
            }
            wp_reset_postdata();
        }
        wp_die();
    }
}

new Filter_WooCommerce_Products_Ajax();