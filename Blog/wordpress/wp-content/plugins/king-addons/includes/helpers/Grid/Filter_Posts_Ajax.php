<?php

namespace King_Addons;

use Elementor\Group_Control_Image_Size;

if (!defined('ABSPATH')) {
    exit;
}

class Filter_Posts_Ajax
{

    public function __construct()
    {
        add_action('wp_ajax_king_addons_filter_grid_posts', [$this, 'king_addons_filter_grid_posts']);
        add_action('wp_ajax_nopriv_king_addons_filter_grid_posts', [$this, 'king_addons_filter_grid_posts']);
        add_action('wp_ajax_king_addons_get_filtered_count', [$this, 'king_addons_get_filtered_count']);
        add_action('wp_ajax_nopriv_king_addons_get_filtered_count', [$this, 'king_addons_get_filtered_count']);
    }

    public function get_related_taxonomies()
    {
        $relations = [];
        $post_types = Core::getCustomTypes('post', false);

        foreach ($post_types as $slug => $title) {
            $relations[$slug] = get_object_taxonomies($slug);
        }

        return json_encode($relations);
    }

    /**
     * Return the maximum number of pages based on the current query.
     */
    public function get_max_num_pages($settings)
    {
        $query = new \WP_Query($this->get_main_query_args());
        $max_num_pages = (int)ceil($query->max_num_pages);
        $adjustedTotal = max(0, $query->found_posts - $query->query_vars['offset']);
        $numberOfPages = (int)ceil($adjustedTotal / $query->query_vars['posts_per_page']);

        wp_send_json_success([
            'page_count' => $numberOfPages,
            'max_num_pages' => $max_num_pages,
            'query_found' => $query->found_posts,
            'query_offset' => $query->query_vars['offset'],
            'query_num' => $query->query_vars['posts_per_page']
        ]);

        wp_reset_postdata();
        return $max_num_pages;
    }

    /**
     * Build the main query args based on $_POST data and widget settings.
     */
    public function get_main_query_args()
    {
        $settings = $_POST['grid_settings'];
        $taxonomy = $_POST['king_addons_taxonomy'] ?? '';
        $term = $_POST['king_addons_filter'] ?? '';
        $author = !empty($settings['query_author']) ? implode(',', $settings['query_author']) : '';
        $paged = get_query_var('paged') ?: (get_query_var('page') ?: 1);
        $layout = $settings['layout_select'] ?? 'grid';

        // If slider layout and premium available, limit posts_per_page properly
        if ($layout === 'slider' && king_addons_freemius()->can_use_premium_code__premium_only()) {
            $settings['query_posts_per_page'] = min($settings['query_slides_to_show'] ?? 4, 4);
        }

        // Force page=1 for slider
        if ($layout === 'slider') {
            $paged = 1;
        }

        // Ensure offset & posts_per_page have sensible defaults
        $settings['query_offset'] = $settings['query_offset'] ?? 0;
        $settings['query_posts_per_page'] = $settings['query_posts_per_page'] ?? ($layout === 'slider' && king_addons_freemius()->can_use_premium_code__premium_only() ? 4 : 999);

        // If premium not available, remove randomize
        if (!king_addons_freemius()->can_use_premium_code__premium_only()) {
            $settings['query_randomize'] = '';
            $settings['order_posts'] = 'date';
        }

        $query_order_by = $settings['query_randomize'] ?: $settings['order_posts'];
        $offset = ($paged - 1) * (int)$settings['query_posts_per_page'] + (int)$settings['query_offset'];

        $args = [
            'post_type' => $settings['query_source'],
            'tax_query' => $this->get_tax_query_args(),
            'post__not_in' => $settings['query_exclude_' . $settings['query_source']] ?? [],
            'posts_per_page' => $settings['query_posts_per_page'],
            'orderby' => $query_order_by,
            'author' => $author,
            'paged' => $paged,
            'offset' => $offset
        ];

        // Order by meta value
        if ($query_order_by === 'meta_value') {
            $args['meta_key'] = $settings['order_posts_by_acf'] ?? '';
        }

        // Scheduled posts
        if (isset($settings['display_scheduled_posts']) && $settings['display_scheduled_posts'] === 'yes'
            && king_addons_freemius()->can_use_premium_code__premium_only()) {
            $args['post_status'] = 'future';
        } else {
            $args['post_status'] = 'publish';
        }

        // Exclude posts without featured image
        if (isset($settings['query_exclude_no_images']) && $settings['query_exclude_no_images'] === 'yes') {
            $args['meta_key'] = '_thumbnail_id';
        }

        // Manual selection
        if (isset($settings['query_selection']) && $settings['query_selection'] === 'manual') {
            $post_ids = $settings['query_manual_' . $settings['query_source']] ?? [];
            $args = [
                'post_type' => $settings['query_source'],
                'post__in' => $post_ids,
                'ignore_sticky' => 1,
                'posts_per_page' => $settings['query_posts_per_page'],
                'orderby' => $query_order_by,
                'paged' => $paged
            ];
        }

        // Current query
        if (isset($settings['query_source']) && $settings['query_source'] === 'current') {
            global $wp_query;
            $args = $wp_query->query_vars;
            $args['orderby'] = $query_order_by;

            // Adjust offset
            $posts_per_page = is_post_type_archive()
                ? (int)get_option('king_addons_cpt_ppp_' . $args['post_type'], 10)
                : (int)get_option('posts_per_page');

            $args['offset'] = ($paged - 1) * $posts_per_page + (int)$settings['query_offset'];

            // If categories passed via URL
            $tax_query = [];
            foreach (['category', 'king_addons_select_category'] as $cat_var) {
                if (isset($_GET[$cat_var]) && $_GET[$cat_var] !== '0') {
                    $cat_id = sanitize_text_field($_GET[$cat_var]);
                    $tax_query[] = [
                        'taxonomy' => 'category',
                        'field' => 'id',
                        'terms' => $cat_id
                    ];
                }
            }

            if (!empty($tax_query)) {
                $args['tax_query'] = $tax_query;
            }
        }

        // Related
        if (isset($settings['query_source']) && $settings['query_source'] === 'related') {
            $args = [
                'post_type' => get_post_type(get_the_ID()),
                'tax_query' => $this->get_tax_query_args(),
                'post__not_in' => [get_the_ID()],
                'ignore_sticky_posts' => 1,
                'posts_per_page' => $settings['query_posts_per_page'],
                'orderby' => $query_order_by,
                'offset' => $offset
            ];
        }

        // Set order direction if not random
        if ($query_order_by !== 'rand') {
            $args['order'] = $settings['order_direction'] ?? 'DESC';
        }

        // Handle front-end filter term (if user clicked a taxonomy link)
        if (!empty($term) && $term !== '*') {
            if ($taxonomy === 'tag') {
                $taxonomy = 'post_tag';
            }
            $args['tax_query'][] = [
                'taxonomy' => $taxonomy,
                'field' => 'slug',
                'terms' => $term
            ];
        }

        // Override offset if sent from front-end
        if (isset($_POST['king_addons_offset'])) {
            $args['offset'] = (int)$_POST['king_addons_offset'];
        }

        return $args;
    }

    /**
     * Build a tax_query array based on widget settings and front-end filter.
     */
    public function get_tax_query_args()
    {
        $settings = $_POST['grid_settings'];
        $tax_query = [];
        $taxonomy = $_POST['king_addons_taxonomy'] ?? '';
        $term = $_POST['king_addons_filter'] ?? '';

        // If user filtered via front-end
        if (!empty($term) && $term !== '*') {
            if ($taxonomy === 'tag') {
                $taxonomy = 'post_tag';
            }
            $tax_query[] = [
                'taxonomy' => $taxonomy,
                'field' => 'slug',
                'terms' => $term
            ];
        }

        // If "related" is chosen, use the current post's terms
        if (isset($settings['query_source']) && $settings['query_source'] === 'related') {
            return [[
                'taxonomy' => $settings['query_tax_selection'],
                'field' => 'term_id',
                'terms' => wp_get_object_terms(
                    get_the_ID(),
                    $settings['query_tax_selection'],
                    ['fields' => 'ids']
                )
            ]];
        }

        // Otherwise, gather terms from each registered taxonomy for this post type
        $post_type_taxonomies = get_object_taxonomies($settings['query_source'] ?? '');
        foreach ($post_type_taxonomies as $tax) {
            if (!empty($settings['query_taxonomy_' . $tax])) {
                $tax_query[] = [
                    'taxonomy' => $tax,
                    'field' => 'id',
                    'terms' => $settings['query_taxonomy_' . $tax]
                ];
            }
        }

        return $tax_query;
    }

    /**
     * Returns an animation-related class for a given element type.
     */
    public function get_animation_class($data, $object)
    {
        // Disable animation on mobile if set
        if ($object !== 'overlay' &&
            isset($data[$object . '_animation_disable_mobile']) &&
            $data[$object . '_animation_disable_mobile'] === 'yes' &&
            wp_is_mobile()
        ) {
            return '';
        }

        if (!isset($data[$object . '_animation']) || $data[$object . '_animation'] === 'none') {
            return '';
        }

        $class = ' king-addons-' . $object . '-' . $data[$object . '_animation'];
        $class .= ' king-addons-anim-size-' . $data[$object . '_animation_size'];
        $class .= ' king-addons-animation-timing-' . $data[$object . '_animation_timing'];

        if (isset($data[$object . '_animation_tr']) && $data[$object . '_animation_tr'] === 'yes') {
            $class .= ' king-addons-anim-transparency';
        }

        return $class;
    }

    /**
     * Returns image effect classes.
     */
    public function get_image_effect_class($settings)
    {
        $class = '';
        if (!king_addons_freemius()->can_use_premium_code__premium_only()) {
            // Fallback if no premium
            if (in_array($settings['image_effects'], ['pro-zi', 'pro-zo', 'pro-go', 'pro-bo'])) {
                $settings['image_effects'] = 'none';
            }
        }

        if (isset($settings['image_effects']) && $settings['image_effects'] !== 'none') {
            $class .= ' king-addons-' . $settings['image_effects'];
            $class .= $settings['image_effects'] !== 'slide'
                ? ' king-addons-effect-size-' . $settings['image_effects_size']
                : ' king-addons-effect-dir-' . $settings['image_effects_direction'];
        }

        return $class;
    }

    /**
     * Renders password-protected form if necessary.
     */
    public function render_password_protected_input($settings)
    {
        if (!post_password_required()) {
            return;
        }
        add_filter('the_password_form', function () {
            $url = esc_url(home_url('wp-login.php?action=postpass'));
            $title = esc_html(get_the_title());
            $ph = esc_html__('Type and hit Enter...', 'king-addons');
            $pid = esc_attr(get_the_id());
            return <<<HTML
<form action="{$url}" method="post">
    <i class="fas fa-lock"></i>
    <p>{$title}</p>
    <input type="password" name="post_password" id="post-{$pid}" placeholder="{$ph}">
</form>
HTML;
        });
        echo '<div class="king-addons-grid-item-protected king-addons-cv-container">'
            . '<div class="king-addons-cv-outer"><div class="king-addons-cv-inner">'
            . get_the_password_form()
            . '</div></div></div>';
    }

    /**
     * Renders post thumbnail.
     */
    public function render_post_thumbnail($settings)
    {
        $id = get_post_thumbnail_id();
        $src = Group_Control_Image_Size::get_attachment_image_src($id, 'layout_image_crop', $settings);
        $alt = wp_get_attachment_caption($id) ?: get_the_title();

        $secondary_id = get_post_meta(get_the_ID(), 'king_addons_secondary_image_id', true);
        $src2 = $secondary_id
            ? Group_Control_Image_Size::get_attachment_image_src($secondary_id, 'layout_image_crop', $settings)
            : '';

        if (!has_post_thumbnail()) return;

        $sec_hover = $settings['secondary_img_on_hover'] ?? '';
        echo '<div class="king-addons-grid-image-wrap" data-src="' . esc_url($src) . '"'
            . ' data-img-on-hover="' . esc_attr($sec_hover) . '" data-src-secondary="' . esc_url($src2) . '">';

        /** @noinspection PhpIfWithCommonPartsInspection */
        if (!empty($settings['grid_lazy_loading']) && $settings['grid_lazy_loading'] === 'yes') {
            echo '<img loading="lazy" src="' . esc_url($src) . '" '
                . 'alt="' . esc_attr($alt) . '" class="king-addons-hidden-image king-addons-animation-timing-'
                . esc_attr($settings['image_effects_animation_timing']) . '">';
            if ($sec_hover === 'yes') {
                echo '<img src="' . esc_url($src2) . '" alt="' . esc_attr($alt) . '" '
                    . 'class="king-addons-hidden-img king-addons-animation-timing-'
                    . esc_attr($settings['image_effects_animation_timing']) . '">';
            }
        } else {
            echo '<img src="' . esc_url($src) . '" alt="' . esc_attr($alt) . '" '
                . 'class="king-addons-animation-timing-' . esc_attr($settings['image_effects_animation_timing']) . '">';
            if ($sec_hover === 'yes') {
                echo '<img src="' . esc_url($src2) . '" alt="' . esc_attr($alt) . '" '
                    . 'class="king-addons-hidden-img king-addons-animation-timing-'
                    . esc_attr($settings['image_effects_animation_timing']) . '">';
            }
        }
        echo '</div>';
    }

    /**
     * Renders media overlay (hover effect image).
     */
    public function render_media_overlay($settings)
    {
        $overlay_class = $this->get_animation_class($settings, 'overlay');
        $permalink = esc_url(get_the_permalink(get_the_ID()));
        echo '<div class="king-addons-grid-media-hover-bg ' . $overlay_class . '" data-url="' . $permalink . '">';
        if (king_addons_freemius()->can_use_premium_code__premium_only() && !empty($settings['overlay_image']['url'])) {
            echo '<img src="' . esc_url($settings['overlay_image']['url']) . '" alt="' . esc_attr($settings['overlay_image']['alt']) . '">';
        }
        echo '</div>';
    }

    /**
     * Renders post title.
     */
    public function render_post_title($settings, $class)
    {
        $title_pointer = king_addons_freemius()->can_use_premium_code__premium_only()
            ? ($_POST['grid_settings']['title_pointer'] ?? 'none')
            : 'none';
        $title_pointer_animation = king_addons_freemius()->can_use_premium_code__premium_only()
            ? ($_POST['grid_settings']['title_pointer_animation'] ?? 'fade')
            : 'fade';

        $pointer_item_class = (!empty($_POST['grid_settings']['title_pointer'])
            && $_POST['grid_settings']['title_pointer'] !== 'none')
            ? 'class="king-addons-pointer-item"'
            : '';

        $new_tab = (!empty($_POST['grid_settings']['open_links_in_new_tab'])
            && $_POST['grid_settings']['open_links_in_new_tab'] === 'yes')
            ? '_blank'
            : '_self';

        $class .= " king-addons-pointer-{$title_pointer} king-addons-pointer-line-fx king-addons-pointer-fx-{$title_pointer_animation}";
        echo '<' . esc_attr($settings['element_title_tag']) . ' class="' . esc_attr($class) . '">'
            . '<div class="inner-block">'
            . '<a target="' . $new_tab . '" ' . $pointer_item_class . ' href="' . esc_url(get_the_permalink()) . '">';

        if (!empty($settings['element_trim_text_by']) && $settings['element_trim_text_by'] === 'word_count') {
            echo esc_html(wp_trim_words(get_the_title(), $settings['element_word_count'] ?? 10));
        } else {
            $limit = (int)($settings['element_letter_count'] ?? 50);
            echo esc_html(mb_strimwidth(html_entity_decode(get_the_title()), 0, $limit, '...'));
        }
        echo '</a></div></' . esc_attr($settings['element_title_tag']) . '>';
    }

    /**
     * Renders full post content.
     */
    public function render_post_content($settings, $class)
    {
        if (!get_the_content()) return;
        if (!empty($settings['element_dropcap']) && $settings['element_dropcap'] === 'yes') {
            $class .= ' king-addons-enable-dropcap';
        }
        echo '<div class="' . esc_attr($class) . '"><div class="inner-block">'
            . wp_kses_post(get_the_content())
            . '</div></div>';
    }

    /**
     * Renders post excerpt.
     */
    public function render_post_excerpt($settings, $class)
    {
        $excerpt = get_the_excerpt();
        if (!$excerpt) return;
        if (!empty($settings['element_dropcap']) && $settings['element_dropcap'] === 'yes') {
            $class .= ' king-addons-enable-dropcap';
        }
        echo '<div class="' . esc_attr($class) . '"><div class="inner-block"><p>';
        if (!empty($settings['element_trim_text_by']) && $settings['element_trim_text_by'] === 'word_count') {
            echo esc_html(wp_trim_words($excerpt, $settings['element_word_count'] ?? 20));
        } else {
            $limit = (int)($settings['element_letter_count'] ?? 100);
            echo esc_html(mb_strimwidth($excerpt, 0, $limit, '...'));
        }
        echo '</p></div></div>';
    }

    /**
     * Renders post date.
     */
    public function render_post_date($settings, $class)
    {
        echo '<div class="' . esc_attr($class) . '"><div class="inner-block"><span>';
        if (!empty($settings['element_extra_text_pos']) && $settings['element_extra_text_pos'] === 'before') {
            echo '<span class="king-addons-grid-extra-text-left">'
                . esc_html($settings['element_extra_text'] ?? '') . '</span>';
        }
        if (!empty($settings['element_extra_icon_pos']) && $settings['element_extra_icon_pos'] === 'before') {
            ob_start();
            \Elementor\Icons_Manager::render_icon($settings['element_extra_icon'] ?? [], ['aria-hidden' => 'true']);
            echo '<span class="king-addons-grid-extra-icon-left">' . ob_get_clean() . '</span>';
        }
        // Display modified time or published date
        echo esc_html(!empty($settings['show_last_update_date']) && $settings['show_last_update_date'] === 'yes'
            ? get_the_modified_time(get_option('date_format'))
            : get_the_date(get_option('date_format'))
        );
        if (!empty($settings['element_extra_icon_pos']) && $settings['element_extra_icon_pos'] === 'after') {
            ob_start();
            \Elementor\Icons_Manager::render_icon($settings['element_extra_icon'], ['aria-hidden' => 'true']);
            echo '<span class="king-addons-grid-extra-icon-right">' . ob_get_clean() . '</span>';
        }
        if (!empty($settings['element_extra_text_pos']) && $settings['element_extra_text_pos'] === 'after') {
            echo '<span class="king-addons-grid-extra-text-right">'
                . esc_html($settings['element_extra_text'] ?? '') . '</span>';
        }
        echo '</span></div></div>';
    }

    /**
     * Renders post time.
     */
    public function render_post_time($settings, $class)
    {
        echo '<div class="' . esc_attr($class) . '"><div class="inner-block"><span>';
        if (!empty($settings['element_extra_text_pos']) && $settings['element_extra_text_pos'] === 'before') {
            echo '<span class="king-addons-grid-extra-text-left">'
                . esc_html($settings['element_extra_text'] ?? '') . '</span>';
        }
        if (!empty($settings['element_extra_icon_pos']) && $settings['element_extra_icon_pos'] === 'before') {
            ob_start();
            \Elementor\Icons_Manager::render_icon($settings['element_extra_icon'] ?? [], ['aria-hidden' => 'true']);
            echo '<span class="king-addons-grid-extra-icon-left">' . ob_get_clean() . '</span>';
        }
        echo esc_html(get_the_time(get_option('time_format')));
        if (!empty($settings['element_extra_icon_pos']) && $settings['element_extra_icon_pos'] === 'after') {
            ob_start();
            \Elementor\Icons_Manager::render_icon($settings['element_extra_icon'], ['aria-hidden' => 'true']);
            echo '<span class="king-addons-grid-extra-icon-right">' . ob_get_clean() . '</span>';
        }
        if (!empty($settings['element_extra_text_pos']) && $settings['element_extra_text_pos'] === 'after') {
            echo '<span class="king-addons-grid-extra-text-right">'
                . esc_html($settings['element_extra_text'] ?? '') . '</span>';
        }
        echo '</span></div></div>';
    }

    /**
     * Renders post author info.
     */
    public function render_post_author($settings, $class)
    {
        $author_id = get_post_field('post_author');
        echo '<div class="' . esc_attr($class) . '"><div class="inner-block">';
        if (!empty($settings['element_extra_text_pos']) && $settings['element_extra_text_pos'] === 'before') {
            echo '<span class="king-addons-grid-extra-text-left">'
                . esc_html($settings['element_extra_text'] ?? '') . '</span>';
        }
        echo '<a href="' . esc_url(get_author_posts_url($author_id)) . '">';
        if (!empty($settings['element_extra_icon_pos']) && $settings['element_extra_icon_pos'] === 'before') {
            ob_start();
            \Elementor\Icons_Manager::render_icon($settings['element_extra_icon'] ?? [], ['aria-hidden' => 'true']);
            echo '<span class="king-addons-grid-extra-icon-left">' . ob_get_clean() . '</span>';
        }
        if (!empty($settings['element_show_avatar']) && $settings['element_show_avatar'] === 'yes') {
            echo get_avatar($author_id, $settings['element_avatar_size'] ?? 32);
        }
        echo '<span>' . esc_html(get_the_author_meta('display_name', $author_id)) . '</span>';
        if (!empty($settings['element_extra_icon_pos']) && $settings['element_extra_icon_pos'] === 'after') {
            ob_start();
            \Elementor\Icons_Manager::render_icon($settings['element_extra_icon'], ['aria-hidden' => 'true']);
            echo '<span class="king-addons-grid-extra-icon-right">' . ob_get_clean() . '</span>';
        }
        echo '</a>';
        if (!empty($settings['element_extra_text_pos']) && $settings['element_extra_text_pos'] === 'after') {
            echo '<span class="king-addons-grid-extra-text-right">'
                . esc_html($settings['element_extra_text'] ?? '') . '</span>';
        }
        echo '</div></div>';
    }

    /**
     * Renders post comments info.
     */
    public function render_post_comments($settings, $class)
    {
        if (!comments_open()) return;
        $count = get_comments_number();
        if ($count === 1) {
            $text = $count . ' ' . ($settings['element_comments_text_2'] ?? '');
        } elseif ($count > 1) {
            $text = $count . ' ' . ($settings['element_comments_text_3'] ?? '');
        } else {
            $text = $settings['element_comments_text_1'] ?? '';
        }
        echo '<div class="' . esc_attr($class) . '"><div class="inner-block">';
        if (!empty($settings['element_extra_text_pos']) && $settings['element_extra_text_pos'] === 'before') {
            echo '<span class="king-addons-grid-extra-text-left">'
                . esc_html($settings['element_extra_text'] ?? '') . '</span>';
        }
        echo '<a href="' . esc_url(get_comments_link()) . '">';
        if (!empty($settings['element_extra_icon_pos']) && $settings['element_extra_icon_pos'] === 'before') {
            ob_start();
            \Elementor\Icons_Manager::render_icon($settings['element_extra_icon'] ?? [], ['aria-hidden' => 'true']);
            echo '<span class="king-addons-grid-extra-icon-left">' . ob_get_clean() . '</span>';
        }
        echo '<span>' . esc_html($text) . '</span>';
        if (!empty($settings['element_extra_icon_pos']) && $settings['element_extra_icon_pos'] === 'after') {
            ob_start();
            \Elementor\Icons_Manager::render_icon($settings['element_extra_icon'] ?? [], ['aria-hidden' => 'true']);
            echo '<span class="king-addons-grid-extra-icon-right">' . ob_get_clean() . '</span>';
        }
        echo '</a>';
        if (!empty($settings['element_extra_text_pos']) && $settings['element_extra_text_pos'] === 'after') {
            echo '<span class="king-addons-grid-extra-text-right">'
                . esc_html($settings['element_extra_text'] ?? '') . '</span>';
        }
        echo '</div></div>';
    }

    /**
     * Renders "read more" button.
     */
    public function render_post_read_more($settings, $class)
    {
        $animation = king_addons_freemius()->can_use_premium_code__premium_only()
            ? ($_POST['grid_settings']['read_more_animation'] ?? 'king-addons-button-none')
            : 'king-addons-button-none';
        $new_tab = (!empty($_POST['grid_settings']['open_links_in_new_tab'])
            && $_POST['grid_settings']['open_links_in_new_tab'] === 'yes')
            ? '_blank'
            : '_self';

        echo '<div class="' . esc_attr($class) . '"><div class="inner-block">'
            . '<a target="' . $new_tab . '" href="' . esc_url(get_the_permalink()) . '" '
            . 'class="king-addons-button-effect ' . esc_attr($animation) . '">';
        if (!empty($settings['element_extra_icon_pos']) && $settings['element_extra_icon_pos'] === 'before') {
            ob_start();
            \Elementor\Icons_Manager::render_icon($settings['element_extra_icon'] ?? [], ['aria-hidden' => 'true']);
            echo '<span class="king-addons-grid-extra-icon-left">' . ob_get_clean() . '</span>';
        }
        echo '<span>' . esc_html($settings['element_read_more_text'] ?? 'Read More') . '</span>';
        if (!empty($settings['element_extra_icon_pos']) && $settings['element_extra_icon_pos'] === 'after') {
            ob_start();
            \Elementor\Icons_Manager::render_icon($settings['element_extra_icon'], ['aria-hidden' => 'true']);
            echo '<span class="king-addons-grid-extra-icon-right">' . ob_get_clean() . '</span>';
        }
        echo '</a></div></div>';
    }

    public function render_post_likes($settings, $class, $post_id)
    {
    }

    public function render_post_sharing_icons($settings, $class)
    {
    }

    /**
     * Renders a lightbox link/icon.
     */
    public function render_post_lightbox($settings, $class, $post_id)
    {
        echo '<div class="' . esc_attr($class) . '"><div class="inner-block">';

        $lightbox_source = get_the_post_thumbnail_url($post_id) ?: '';

        // Format-specific logic
        if (get_post_format($post_id) === 'audio' && !empty($settings['element_lightbox_pfa_select'])
            && $settings['element_lightbox_pfa_select'] === 'meta') {
            $meta_value = get_post_meta($post_id, $settings['element_lightbox_pfa_meta'] ?? '', true);
            $track_url = false === strpos($meta_value, '<iframe ')
                ? wp_oembed_get($meta_value)
                : Core::filterOembedResults($meta_value);
            $lightbox_source = $track_url ?: $lightbox_source;
        }
        if (get_post_format($post_id) === 'video' && !empty($settings['element_lightbox_pfv_select'])
            && $settings['element_lightbox_pfv_select'] === 'meta') {
            $meta_value = get_post_meta($post_id, $settings['element_lightbox_pfv_meta'] ?? '', true);
            $video_info = false === strpos($meta_value, '<iframe ')
                ? \Elementor\Embed::get_video_properties($meta_value)
                : \Elementor\Embed::get_video_properties(Core::filterOembedResults($meta_value));
            if (!empty($video_info['provider']) && !empty($video_info['video_id'])) {
                if ($video_info['provider'] === 'youtube') {
                    $lightbox_source = 'https://www.youtube.com/embed/' . $video_info['video_id'] . '?autoplay=1&controls=1';
                } elseif ($video_info['provider'] === 'vimeo') {
                    $lightbox_source = 'https://player.vimeo.com/video/' . $video_info['video_id'] . '?autoplay=1#t=0';
                }
            }
        }

        // Output icon + optional text
        echo '<span data-src="' . esc_url($lightbox_source) . '">';
        if (!empty($settings['element_extra_text_pos']) && $settings['element_extra_text_pos'] === 'before') {
            echo '<span class="king-addons-grid-extra-text-left">'
                . esc_html($settings['element_extra_text'] ?? '') . '</span>';
        }
        echo '<i class="' . esc_attr($settings['element_extra_icon']['value'] ?? '') . '"></i>';
        if (!empty($settings['element_extra_text_pos']) && $settings['element_extra_text_pos'] === 'after') {
            echo '<span class="king-addons-grid-extra-text-right">'
                . esc_html($settings['element_extra_text'] ?? '') . '</span>';
        }
        echo '</span>';

        // Add lightbox overlay if requested
        if (!empty($settings['element_lightbox_overlay']) && $settings['element_lightbox_overlay'] === 'yes') {
            echo '<div class="king-addons-grid-lightbox-overlay"></div>';
        }
        echo '</div></div>';
    }

    public function render_post_custom_field($settings, $class, $post_id)
    {
        // No changes. Implement if needed.
    }

    /**
     * Renders a separator line.
     */
    public function render_post_element_separator($settings, $class)
    {
        echo '<div class="' . esc_attr($class . ' ' . $settings['element_separator_style']) . '">'
            . '<div class="inner-block"><span></span></div></div>';
    }

    /**
     * Renders taxonomy lists (categories, tags, etc.).
     */
    public function render_post_taxonomies($settings, $class, $post_id)
    {
        $terms = wp_get_post_terms($post_id, $settings['element_select'] ?? '');
        if (!$terms) return;

        // Pointer style
        $tax1_pointer = king_addons_freemius()->can_use_premium_code__premium_only()
            ? ($_POST['grid_settings']['tax1_pointer'] ?? 'none')
            : 'none';
        $tax1_pointer_animation = king_addons_freemius()->can_use_premium_code__premium_only()
            ? ($_POST['grid_settings']['tax1_pointer_animation'] ?? 'fade')
            : 'fade';
        $tax2_pointer = king_addons_freemius()->can_use_premium_code__premium_only()
            ? ($_POST['grid_settings']['tax2_pointer'] ?? 'none')
            : 'none';
        $tax2_pointer_animation = king_addons_freemius()->can_use_premium_code__premium_only()
            ? ($_POST['grid_settings']['tax2_pointer_animation'] ?? 'fade')
            : 'fade';

        $tax_style = $settings['element_tax_style'] ?? 'king-addons-grid-tax-style-1';
        if ($tax_style === 'king-addons-grid-tax-style-1') {
            $class .= " king-addons-pointer-{$tax1_pointer} king-addons-pointer-line-fx king-addons-pointer-fx-{$tax1_pointer_animation}";
        } else {
            $class .= " king-addons-pointer-{$tax2_pointer} king-addons-pointer-line-fx king-addons-pointer-fx-{$tax2_pointer_animation}";
        }

        echo '<div class="' . esc_attr($class . ' ' . $tax_style) . '"><div class="inner-block">';

        // Extra text/icon (before)
        if (!empty($settings['element_extra_text_pos']) && $settings['element_extra_text_pos'] === 'before') {
            echo '<span class="king-addons-grid-extra-text-left">'
                . esc_html($settings['element_extra_text'] ?? '') . '</span>';
        }
        if (!empty($settings['element_extra_icon_pos']) && $settings['element_extra_icon_pos'] === 'before') {
            ob_start();
            \Elementor\Icons_Manager::render_icon($settings['element_extra_icon'] ?? [], ['aria-hidden' => 'true']);
            echo '<span class="king-addons-grid-extra-icon-left">' . ob_get_clean() . '</span>';
        }

        // Print each term
        $sep = $settings['element_tax_sep'] ?? ',';
        $count = 0;
        foreach ($terms as $term) {
            // Optional custom color if premium
            if (king_addons_freemius()->can_use_premium_code__premium_only()
                && !empty($_POST['grid_settings']['tax1_custom_color_switcher'])
                && $_POST['grid_settings']['tax1_custom_color_switcher'] === 'yes'
            ) {
                $text_color = get_term_meta($term->term_id, $_POST['grid_settings']['tax1_custom_color_field_text'] ?? '', true);
                $bg_color = get_term_meta($term->term_id, $_POST['grid_settings']['tax1_custom_color_field_bg'] ?? '', true);
                if ($text_color || $bg_color) {
                    $custom_style = "color:{$text_color}; background-color:{$bg_color}; border-color:{$bg_color};";
                    $selector = '.elementor-element' . $this->get_unique_selector() . ' .king-addons-grid-tax-style-1 .inner-block a.king-addons-tax-id-' . esc_attr($term->term_id);
                    echo '<style>' . $selector . '{' . $custom_style . '}</style>';
                }
            }

            $pointer_item_class = ($tax_style === 'king-addons-grid-tax-style-1' && $tax1_pointer !== 'none')
            || ($tax_style !== 'king-addons-grid-tax-style-1' && $tax2_pointer !== 'none')
                ? 'king-addons-pointer-item'
                : '';

            echo '<a class="' . $pointer_item_class . ' king-addons-tax-id-' . esc_attr($term->term_id) . '" '
                . 'href="' . esc_url(get_term_link($term->term_id)) . '">' . esc_html($term->name);
            if (++$count < count($terms)) {
                echo '<span class="tax-sep">' . esc_html($sep) . '</span>';
            }
            echo '</a>';
        }

        // Extra text/icon (after)
        if (!empty($settings['element_extra_icon_pos']) && $settings['element_extra_icon_pos'] === 'after') {
            ob_start();
            \Elementor\Icons_Manager::render_icon($settings['element_extra_icon'] ?? [], ['aria-hidden' => 'true']);
            echo '<span class="king-addons-grid-extra-icon-right">' . ob_get_clean() . '</span>';
        }
        if (!empty($settings['element_extra_text_pos']) && $settings['element_extra_text_pos'] === 'after') {
            echo '<span class="king-addons-grid-extra-text-right">'
                . esc_html($settings['element_extra_text'] ?? '') . '</span>';
        }
        echo '</div></div>';
    }

    /**
     * Helper to dispatch element rendering by type.
     */
    public function get_elements($type, $settings, $class, $post_id)
    {
        // Some pro-only placeholders
        if (in_array($type, ['pro-lk', 'pro-shr', 'pro-cf'])) {
            $type = 'title';
        }
        switch ($type) {
            case 'title':
                $this->render_post_title($settings, $class);
                break;
            case 'content':
                $this->render_post_content($settings, $class);
                break;
            case 'excerpt':
                $this->render_post_excerpt($settings, $class);
                break;
            case 'date':
                $this->render_post_date($settings, $class);
                break;
            case 'time':
                $this->render_post_time($settings, $class);
                break;
            case 'author':
                $this->render_post_author($settings, $class);
                break;
            case 'comments':
                $this->render_post_comments($settings, $class);
                break;
            case 'read-more':
                $this->render_post_read_more($settings, $class);
                break;
            case 'likes':
                $this->render_post_likes($settings, $class, $post_id);
                break;
            case 'sharing':
                $this->render_post_sharing_icons($settings, $class);
                break;
            case 'lightbox':
                $this->render_post_lightbox($settings, $class, $post_id);
                break;
            case 'custom-field':
                $this->render_post_custom_field($settings, $class, $post_id);
                break;
            case 'separator':
                $this->render_post_element_separator($settings, $class);
                break;
            default:
                // Taxonomies
                $this->render_post_taxonomies($settings, $class, $post_id);
                break;
        }
    }

    /**
     * Renders element blocks in their respective location (above, over, below).
     */
    public function get_elements_by_location($location, $settings, $post_id)
    {
        // Group elements by location
        $locations = [];
        foreach ($settings['grid_elements'] as $data) {
            $place = $data['element_location'];
            $alignV = $data['element_align_vr'] ?? 'middle';
            if ($place === 'over') {
                $locations[$place][$alignV][] = $data;
            } else {
                $locations[$place][] = $data;
            }
        }
        // If nothing to render at this location
        if (empty($locations[$location])) return;

        if ($location === 'over') {
            foreach ($locations[$location] as $align => $elements) {
                // Center container
                if ($align === 'middle') {
                    echo '<div class="king-addons-cv-container">'
                        . '<div class="king-addons-cv-outer"><div class="king-addons-cv-inner">';
                }
                echo '<div class="king-addons-grid-media-hover-' . esc_attr($align) . ' elementor-clearfix">';
                foreach ($elements as $data) {
                    $class = 'king-addons-grid-item-' . $data['element_select']
                        . ' elementor-repeater-item-' . $data['_id']
                        . ' king-addons-grid-item-display-' . $data['element_display']
                        . ' king-addons-grid-item-align-' . $data['element_align_hr']
                        . $this->get_animation_class($data, 'element');
                    $this->get_elements($data['element_select'], $data, $class, $post_id);
                }
                echo '</div>';
                if ($align === 'middle') {
                    echo '</div></div></div>';
                }
            }
        } else {
            // above / below
            echo '<div class="king-addons-grid-item-' . esc_attr($location) . '-content elementor-clearfix">';
            foreach ($locations[$location] as $data) {
                $class = 'king-addons-grid-item-' . $data['element_select']
                    . ' elementor-repeater-item-' . $data['_id']
                    . ' king-addons-grid-item-display-' . $data['element_display']
                    . ' king-addons-grid-item-align-' . $data['element_align_hr'];
                $this->get_elements($data['element_select'], $data, $class, $post_id);
            }
            echo '</div>';
        }
    }

    /**
     * Returns a class to hide filters with no posts if 'filters_hide_empty' is on.
     */
    public function get_hidden_filter_class($slug, $settings)
    {
        $posts = new \WP_Query($this->get_main_query_args());
        $visible_cats = [];

        if ($posts->have_posts()) {
            while ($posts->have_posts()) {
                $posts->the_post();
                $post_categories = get_the_category();
                foreach ($post_categories as $cat) {
                    $visible_cats[] = $cat->slug;
                }
            }
            wp_reset_postdata();
        }
        $visible_cats = array_unique($visible_cats);
        return (!in_array($slug, $visible_cats, true) && !empty($settings['filters_hide_empty'])
            && $settings['filters_hide_empty'] === 'yes')
            ? ' king-addons-hidden-element'
            : '';
    }

    /**
     * Renders pagination if enabled.
     */
    public function render_grid_pagination($settings)
    {
        if (empty($settings['layout_pagination']) || $settings['layout_pagination'] !== 'yes') {
            return;
        }
        $pages = $this->get_max_num_pages($settings);
        if ($pages <= 1 || (!empty($settings['layout_select']) && $settings['layout_select'] === 'slider')) {
            return;
        }

        global $paged;
        $paged = $paged ?: 1;

        // Fallback if premium is not available
        if (!king_addons_freemius()->can_use_premium_code__premium_only() && $settings['pagination_type'] === 'pro-is') {
            $settings['pagination_type'] = 'default';
        }

        $ptype = $settings['pagination_type'] ?? 'default';
        echo '<div class="king-addons-grid-pagination elementor-clearfix king-addons-grid-pagination-' . esc_attr($ptype) . '">';

        if ($ptype === 'default') {
            // Simple older/newer
            if ($paged < $pages) {
                echo '<a href="' . esc_url(get_pagenum_link($paged + 1, true)) . '" class="king-addons-prev-post-link">'
                    . Core::getIcon($settings['pagination_on_icon'] ?? '', 'left')
                    . esc_html($settings['pagination_older_text'] ?? 'Older')
                    . '</a>';
            } elseif (!empty($settings['pagination_disabled_arrows']) && $settings['pagination_disabled_arrows'] === 'yes') {
                echo '<span class="king-addons-prev-post-link king-addons-disabled-arrow">'
                    . Core::getIcon($settings['pagination_on_icon'] ?? '', 'left')
                    . esc_html($settings['pagination_older_text'] ?? 'Older')
                    . '</span>';
            }
            if ($paged > 1) {
                echo '<a href="' . esc_url(get_pagenum_link($paged - 1, true)) . '" class="king-addons-next-post-link">'
                    . esc_html($settings['pagination_newer_text'] ?? 'Newer')
                    . Core::getIcon($settings['pagination_on_icon'] ?? '', 'right')
                    . '</a>';
            } elseif (!empty($settings['pagination_disabled_arrows']) && $settings['pagination_disabled_arrows'] === 'yes') {
                echo '<span class="king-addons-next-post-link king-addons-disabled-arrow">'
                    . esc_html($settings['pagination_newer_text'] ?? 'Newer')
                    . Core::getIcon($settings['pagination_on_icon'] ?? '', 'right')
                    . '</span>';
            }
        } elseif ($ptype === 'numbered') {
            $range = $settings['pagination_range'] ?? 2;
            $showitems = ($range * 2) + 1;

            // Optionally show first, prev, next, last
            if (!empty($settings['pagination_prev_next']) || !empty($settings['pagination_first_last'])) {
                echo '<div class="king-addons-grid-pagination-left-arrows">';
                if (!empty($settings['pagination_first_last']) && $settings['pagination_first_last'] === 'yes') {
                    if ($paged >= 2) {
                        echo '<a href="' . esc_url(get_pagenum_link(1, true)) . '" class="king-addons-first-page">'
                            . Core::getIcon($settings['pagination_fl_icon'] ?? '', 'left')
                            . '<span>' . esc_html($settings['pagination_first_text'] ?? 'First') . '</span></a>';
                    } elseif (!empty($settings['pagination_disabled_arrows']) && $settings['pagination_disabled_arrows'] === 'yes') {
                        echo '<span class="king-addons-first-page king-addons-disabled-arrow">'
                            . Core::getIcon($settings['pagination_fl_icon'] ?? '', 'left')
                            . '<span>' . esc_html($settings['pagination_first_text'] ?? 'First') . '</span></span>';
                    }
                }
                if (!empty($settings['pagination_prev_next']) && $settings['pagination_prev_next'] === 'yes') {
                    if ($paged > 1) {
                        echo '<a href="' . esc_url(get_pagenum_link($paged - 1, true)) . '" class="king-addons-prev-page">'
                            . Core::getIcon($settings['pagination_pn_icon'] ?? '', 'left')
                            . '<span>' . esc_html($settings['pagination_prev_text'] ?? 'Prev') . '</span></a>';
                    } elseif (!empty($settings['pagination_disabled_arrows']) && $settings['pagination_disabled_arrows'] === 'yes') {
                        echo '<span class="king-addons-prev-page king-addons-disabled-arrow">'
                            . Core::getIcon($settings['pagination_pn_icon'] ?? '', 'left')
                            . '<span>' . esc_html($settings['pagination_prev_text'] ?? 'Prev') . '</span></span>';
                    }
                }
                echo '</div>';
            }

            for ($i = 1; $i <= $pages; $i++) {
                if ($pages !== 1 && (!($i >= $paged + $range + 1 || $i <= $paged - $range - 1) || $pages <= $showitems)) {
                    if ($paged === $i) {
                        echo '<span class="king-addons-grid-current-page">' . esc_html($i) . '</span>';
                    } else {
                        echo '<a href="' . esc_url(get_pagenum_link($i, true)) . '">' . esc_html($i) . '</a>';
                    }
                }
            }

            if (!empty($settings['pagination_prev_next']) || !empty($settings['pagination_first_last'])) {
                echo '<div class="king-addons-grid-pagination-right-arrows">';
                if (!empty($settings['pagination_prev_next']) && $settings['pagination_prev_next'] === 'yes') {
                    if ($paged < $pages) {
                        echo '<a href="' . esc_url(get_pagenum_link($paged + 1, true)) . '" class="king-addons-next-page">'
                            . '<span>' . esc_html($settings['pagination_next_text'] ?? 'Next') . '</span>'
                            . Core::getIcon($settings['pagination_pn_icon'] ?? '', 'right')
                            . '</a>';
                    } elseif (!empty($settings['pagination_disabled_arrows']) && $settings['pagination_disabled_arrows'] === 'yes') {
                        echo '<span class="king-addons-next-page king-addons-disabled-arrow">'
                            . '<span>' . esc_html($settings['pagination_next_text'] ?? 'Next') . '</span>'
                            . Core::getIcon($settings['pagination_pn_icon'] ?? '', 'right')
                            . '</span>';
                    }
                }
                if (!empty($settings['pagination_first_last']) && $settings['pagination_first_last'] === 'yes') {
                    if ($paged <= $pages - 1) {
                        echo '<a href="' . esc_url(get_pagenum_link($pages, true)) . '" class="king-addons-last-page">'
                            . '<span>' . esc_html($settings['pagination_last_text'] ?? 'Last') . '</span>'
                            . Core::getIcon($settings['pagination_fl_icon'] ?? '', 'right')
                            . '</a>';
                    } elseif (!empty($settings['pagination_disabled_arrows']) && $settings['pagination_disabled_arrows'] === 'yes') {
                        echo '<span class="king-addons-last-page king-addons-disabled-arrow">'
                            . '<span>' . esc_html($settings['pagination_last_text'] ?? 'Last') . '</span>'
                            . Core::getIcon($settings['pagination_fl_icon'] ?? '', 'right')
                            . '</span>';
                    }
                }
                echo '</div>';
            }
        } else {
            // Infinite Scroll or Load More
            echo '<a href="' . esc_url(get_pagenum_link($paged + 1, true)) . '" class="king-addons-load-more-btn" data-e-disable-page-transition>'
                . esc_html($settings['pagination_load_more_text'] ?? 'Load More') . '</a>';
            echo '<div class="king-addons-pagination-loading">';
            switch ($settings['pagination_animation'] ?? '') {
                case 'loader-1':
                    echo '<div class="king-addons-double-bounce">'
                        . '<div class="king-addons-child king-addons-double-bounce1"></div>'
                        . '<div class="king-addons-child king-addons-double-bounce2"></div>'
                        . '</div>';
                    break;
                case 'loader-2':
                    echo '<div class="king-addons-wave">'
                        . '<div class="king-addons-rect king-addons-rect1"></div>'
                        . '<div class="king-addons-rect king-addons-rect2"></div>'
                        . '<div class="king-addons-rect king-addons-rect3"></div>'
                        . '<div class="king-addons-rect king-addons-rect4"></div>'
                        . '<div class="king-addons-rect king-addons-rect5"></div>'
                        . '</div>';
                    break;
                case 'loader-3':
                    echo '<div class="king-addons-spinner king-addons-spinner-pulse"></div>';
                    break;
                case 'loader-4':
                    echo '<div class="king-addons-chasing-dots">'
                        . '<div class="king-addons-child king-addons-dot1"></div>'
                        . '<div class="king-addons-child king-addons-dot2"></div>'
                        . '</div>';
                    break;
                case 'loader-5':
                    echo '<div class="king-addons-three-bounce">'
                        . '<div class="king-addons-child king-addons-bounce1"></div>'
                        . '<div class="king-addons-child king-addons-bounce2"></div>'
                        . '<div class="king-addons-child king-addons-bounce3"></div>'
                        . '</div>';
                    break;
                case 'loader-6':
                    echo '<div class="king-addons-fading-circle">';
                    for ($i = 1; $i <= 12; $i++) {
                        echo '<div class="king-addons-circle king-addons-circle' . $i . '"></div>';
                    }
                    echo '</div>';
                    break;
            }
            echo '</div>';
            echo '<p class="king-addons-pagination-finish">'
                . esc_html($settings['pagination_finish_text'] ?? 'No more posts') . '</p>';
        }
        echo '</div>';
    }

    /**
     * Returns JSON with the page count for the current filter query.
     */
    public function king_addons_get_filtered_count()
    {
        $settings = $_POST['grid_settings'];
        $page_count = $this->get_max_num_pages($settings);
        wp_send_json_success(['page_count' => $page_count]);
        wp_die();
    }

    /**
     * Main AJAX handler for filtering and rendering posts.
     */
    public function king_addons_filter_grid_posts()
    {
        $settings = $_POST['grid_settings'];
        $posts = new \WP_Query($this->get_main_query_args());

        if ($posts->have_posts()) {
            while ($posts->have_posts()) {
                $posts->the_post();
                $post_class = implode(' ', get_post_class('king-addons-grid-item elementor-clearfix', get_the_ID()));
                echo '<article class="' . esc_attr($post_class) . '">';

                $this->render_password_protected_input($settings);

                echo '<div class="king-addons-grid-item-inner">';

                // Elements above image
                $this->get_elements_by_location('above', $settings, get_the_ID());

                // Media
                if (has_post_thumbnail()) {
                    echo '<div class="king-addons-grid-media-wrap'
                        . esc_attr($this->get_image_effect_class($settings))
                        . '" data-overlay-link="' . esc_attr($settings['overlay_post_link'] ?? '') . '">';
                    $this->render_post_thumbnail($settings);

                    // Hover overlay
                    echo '<div class="king-addons-grid-media-hover king-addons-animation-wrap">';
                    $this->render_media_overlay($settings);
                    $this->get_elements_by_location('over', $settings, get_the_ID());
                    echo '</div></div>';
                }

                // Elements below image
                $this->get_elements_by_location('below', $settings, get_the_ID());
                echo '</div></article>';
            }
            wp_reset_postdata();
        }
        die();
    }
}

new Filter_Posts_Ajax();