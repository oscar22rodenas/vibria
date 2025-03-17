<?php

namespace King_Addons;

use Elementor\Utils;
use WP_Query;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class Search_Ajax
{

    public function __construct()
    {
        add_action('wp_ajax_king_addons_data_fetch', [$this, 'data_fetch']);
        add_action('wp_ajax_nopriv_king_addons_data_fetch', [$this, 'data_fetch']);
    }

    public function data_fetch()
    {

        $nonce = $_POST['nonce'];

        if (!wp_verify_nonce($nonce, 'king_addons_search_nonce')) {
            return;
        }

        $all_post_types = [];
        foreach (Core::getCustomTypes('post', false) as $key => $value) {
            $all_post_types[] = $key;
        }

        $tax_query = '';

        if ($_POST['king_addons_category'] && $_POST['king_addons_category'] != '') {
            $tax_query = array(
                array(
                    'taxonomy' => $_POST['king_addons_option_post_type'],
                    'field' => 'term_id',
                    'terms' => sanitize_text_field($_POST['king_addons_category']),
                ),
            );
        } else if ($_POST['king_addons_category'] == 0 && $_POST['king_addons_query_type'] != 'all') {
            if (!empty($_POST['king_addons_option_post_type'])) {
                $tax_query = array(
                    array(
                        'taxonomy' => $_POST['king_addons_option_post_type'],
                        'field' => 'term_id',
                        'terms' => sanitize_text_field($_POST['king_addons_category']),
                    ),
                );
            } else {
                $taxonomy_type_string = $_POST['king_addons_taxonomy_type'];

                if (strpos($taxonomy_type_string, ' ') !== false) {
                    $taxonomy_types = explode(' ', $taxonomy_type_string);
                    $tax_query = [
                        'relation' => 'OR'
                    ];

                    foreach ($taxonomy_types as $taxonomy_type) {
                        $tax_query[] = [
                            'taxonomy' => $taxonomy_type,
                            'operator' => 'EXISTS'
                        ];
                    }
                } else {
                    $tax_query = array(
                        array(
                            'taxonomy' => $_POST['king_addons_taxonomy_type'],
                            'operator' => 'EXISTS',
                        ),
                    );
                }
            }
        }

        if ($_POST['king_addons_category'] == 0 || $_POST['king_addons_query_type'] === 'all') {
            $tax_query = [];
        }

        $can_view_protected_posts = current_user_can('read_private_posts');

        $query_args = [
            'posts_per_page' => sanitize_text_field($_POST['king_addons_number_of_results']),
            's' => sanitize_text_field($_POST['king_addons_keyword']),
            'post_type' => $_POST['king_addons_query_type'] === 'all' || !king_addons_freemius()->can_use_premium_code__premium_only() ? $all_post_types : [sanitize_text_field($_POST['king_addons_query_type'])],
            'offset' => sanitize_text_field($_POST['king_addons_search_results_offset']),
            'meta_query' => 'yes' === sanitize_text_field($_POST['king_addons_exclude_without_thumb']) ? [['key' => '_thumbnail_id']] : '',
            'tax_query' => $tax_query,
            'post_status' => 'publish',
        ];

        if (!$can_view_protected_posts || 'yes' !== sanitize_text_field($_POST['king_addons_show_ps_pt'])) {
            $query_args['post_password'] = '';
        }

        $the_query = new WP_Query($query_args);

        if ($the_query->have_posts()) :
            while ($the_query->have_posts()) : $the_query->the_post();

                $post_thumb = has_post_thumbnail() ? get_the_post_thumbnail(get_the_ID(), 'medium') : '<img src="' . Utils::get_placeholder_image_src() . '">';
                $post_url = esc_url(get_the_permalink());
                $target = esc_attr($_POST['king_addons_ajax_search_link_target']);
                $show_thumbnail = ('yes' === sanitize_text_field($_POST['king_addons_show_ajax_thumbnail']));
                $can_show_content = (!post_password_required() || $can_view_protected_posts);

                ?>
                <li data-number-of-results="<?php echo $the_query->found_posts; ?>">
                    <?php if ($show_thumbnail) : ?>
                        <a class="king-addons-ajax-img-wrap" target="<?php echo $target; ?>"
                           href="<?php echo $post_url; ?>">
                            <?php echo $post_thumb; ?>
                        </a>
                    <?php endif; ?>
                    <div class="king-addons-ajax-search-content">
                        <a target="<?php echo $target; ?>" class="king-addons-ajax-title"
                           href="<?php echo $post_url; ?>"><?php the_title(); ?></a>
                        <?php if ($can_show_content && 'yes' === sanitize_text_field($_POST['king_addons_show_description'])) : ?>
                            <p class="king-addons-ajax-desc">
                                <a target="<?php echo $target; ?>" href="<?php echo $post_url; ?>">
                                    <?php echo wp_trim_words(get_the_content(), sanitize_text_field($_POST['king_addons_number_of_words'])); ?>
                                </a>
                            </p>
                        <?php endif; ?>
                        <?php if ($can_show_content && sanitize_text_field($_POST['king_addons_show_view_result_btn'])) : ?>
                            <a target="<?php echo $target; ?>" class="king-addons-view-result"
                               href="<?php echo $post_url; ?>">
                                <?php echo sanitize_text_field($_POST['king_addons_view_result_text']); ?>
                            </a>
                        <?php endif; ?>
                    </div>
                </li>
            <?php
            endwhile;

            wp_reset_postdata();

        else :
            if (sanitize_text_field($_POST['king_addons_search_results_offset']) <= 0) {
                echo '<p class="king-addons-no-results">' . sanitize_text_field($_POST['king_addons_no_results']) . '</p>';
            }
        endif;

        die();
    }
}

new Search_Ajax();