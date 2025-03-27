<?php /** @noinspection SpellCheckingInspection, DuplicatedCode */

namespace King_Addons;

use Exception;

if (!defined('ABSPATH')) {
    exit;
}

final class Templates
{
    private static ?Templates $instance = null;

    public static function instance(): ?Templates
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function render_template_catalog_page(): void
    {
        $templates = TemplatesMap::getTemplatesMapArray();
        $collections = CollectionsMap::getCollectionsMapArray();

        uasort($collections, function($a, $b) {
            return strcasecmp($a, $b);
        });

        $is_premium_active = king_addons_freemius()->can_use_premium_code();

        // TODO: TEST: For UI testing, it doesn't enable the real premium
//        $is_premium_active = false;

        // Arrays for categories and tags
        $categories = [];
        $tags = [];
        $category_counts = [];

        // Getting unique categories and tags
        foreach ($templates['templates'] as $template) {
            if (!in_array($template['category'], $categories)) {
                $categories[] = $template['category'];
            }

            foreach ($template['tags'] as $tag) {
                if (!in_array($tag, $tags)) {
                    $tags[] = $tag;
                }
            }

            $category = $template['category'];
            $category_counts[$category] = isset($category_counts[$category]) ? $category_counts[$category] + 1 : 1;
        }

        sort($categories);

        // Get filters from query
        $search_query = isset($_GET['s']) ? sanitize_text_field($_GET['s']) : '';
        $selected_category = isset($_GET['category']) ? sanitize_text_field($_GET['category']) : '';
        $selected_collection = isset($_GET['collection']) ? sanitize_text_field($_GET['collection']) : '';
        $selected_tags = isset($_GET['tags']) ? array_filter(explode(',', sanitize_text_field($_GET['tags']))) : [];
        $current_page = isset($_GET['paged']) ? max(1, intval($_GET['paged'])) : 1;

        // Use the common function to get filtered templates and pagination
        $result = $this->get_filtered_templates($templates, $search_query, $selected_category, $selected_tags, $selected_collection, $current_page);

        if (isset($_GET['ajax']) && $_GET['ajax']) {
            wp_send_json_success(['grid_html' => $result['grid_html'], 'pagination_html' => $result['pagination_html']]);
        }
        if ($is_premium_active) {
            ?>
            <script type="text/javascript">
                (function () {
                    window.kingAddons = window.kingAddons || {};
                    window.kingAddons.installId = <?php
                    echo json_encode(king_addons_freemius()->get_site()->id);
                    ?>;
                })();
            </script>
            <?php
        }
        ?>
        <div id="king-addons-templates-top"></div>
        <div id="king-addons-templates" class="king-addons-templates">
            <div class="kng-intro">
                <div class="kng-intro-wrap">
                    <div class="kng-intro-wrap-1">
                        <h1 class="kng-intro-title"><?php echo esc_html__('King Addons for Elementor', 'king-addons'); ?></h1>
                        <?php if ($is_premium_active): ?>
                            <span class="premium-active-txt"><?php echo esc_html__('PREMIUM', 'king-addons'); ?></h1></span>
                        <?php endif; ?>
                        <h2 class="kng-intro-subtitle"><?php echo esc_html__('Discover professionally designed, attention-grabbing, and SEO-optimized templates perfect for any site', 'king-addons'); ?></h2>
                    </div>
                    <div class="kng-intro-wrap-2">
                        <div class="kng-navigation">
                            <div class="kng-nav-item kng-nav-item-current">
                                <a href="<?php echo admin_url('admin.php?page=king-addons'); ?>">
                                    <div class="kng-nav-item-txt"><?php echo esc_html__('Free Widgets & Features', 'king-addons'); ?></div>
                                </a>
                            </div>
                            <?php if (KING_ADDONS_EXT_HEADER_FOOTER_BUILDER): ?>
                                <div class="kng-nav-item kng-nav-item-current">
                                    <a href="<?php echo admin_url('edit.php?post_type=king-addons-el-hf'); ?>">
                                        <div class="kng-nav-item-txt"><?php echo esc_html__('Free Header & Footer Builder', 'king-addons'); ?></div>
                                    </a>
                                </div>
                            <?php endif; ?>
                            <?php if (KING_ADDONS_EXT_POPUP_BUILDER): ?>
                                <div class="kng-nav-item kng-nav-item-current">
                                    <a href="<?php echo admin_url('admin.php?page=king-addons-popup-builder'); ?>">
                                        <div class="kng-nav-item-txt"><?php echo esc_html__('Free Popup Builder', 'king-addons'); ?></div>
                                    </a>
                                </div>
                            <?php endif; ?>
                            <?php if (!king_addons_freemius()->can_use_premium_code()): ?>
                                <div class="kng-nav-item kng-nav-item-current kng-nav-activate-license">
                                    <a id="activate-license-btn">
                                        <img src="<?php echo esc_url(KING_ADDONS_URL) . 'includes/admin/img/up.svg'; ?>"
                                             alt="<?php echo esc_html__('Activate License', 'king-addons'); ?>">
                                        <div class="kng-nav-item-txt"><?php echo esc_html__('Activate License', 'king-addons'); ?></div>
                                    </a>
                                </div>
                            <?php endif; ?>
                            <?php if (!king_addons_freemius()->can_use_premium_code()): ?>
                                <div class="kng-nav-item kng-nav-item-current kng-nav-activate-license">
                                    <a href="https://kingaddons.com/pricing/?utm_source=kng-templates-banner-top&utm_medium=plugin&utm_campaign=kng"
                                       target="_blank">
                                        <img src="<?php echo esc_url(KING_ADDONS_URL) . 'includes/admin/img/icon-for-admin.svg'; ?>"
                                             alt="<?php echo esc_html__('Get Premium', 'king-addons'); ?>">
                                        <div class="kng-nav-item-txt"><?php echo esc_html__('Get Premium', 'king-addons'); ?></div>
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <div id="templates-catalog">
                <div class="filters-wrapper">
                    <div class="filters">
                        <select id="template-category">
                            <option value=""><?php esc_html_e('All Categories', 'king-addons'); ?>
                                (<?php echo count($templates['templates']); ?>)
                            </option>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?php echo esc_attr($category); ?>" <?php selected($selected_category, $category); ?>>
                                    <?php echo esc_html(ucwords(str_replace('-', ' ', $category))); ?>
                                    (<?php echo $category_counts[$category]; ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <select id="template-collection">
                            <option value=""><?php esc_html_e('All Collections', 'king-addons'); ?> (<?php echo count($collections); ?>)</option>
                            <?php foreach ($collections as $id => $name): ?>
                                <option value="<?php echo esc_attr($id); ?>" <?php selected($selected_collection, $id); ?>>
                                    <?php echo esc_html($name); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <div id="template-tags">
                            <?php
                            shuffle($tags); ?>
                            <div class="tags-header">Tags</div>
                            <?php foreach ($tags as $tag): ?>
                                <label>
                                    <input type="checkbox"
                                           value="<?php echo esc_attr($tag); ?>" <?php echo in_array($tag, $selected_tags) ? 'checked' : ''; ?>> <?php echo esc_html(ucwords(str_replace('-', ' ', $tag))); ?>
                                </label>
                            <?php endforeach; ?>
                        </div>
                        <button id="reset-filters"><?php esc_html_e('Reset Search & Filters', 'king-addons'); ?></button>
                        <?php if (!$is_premium_active): ?>
                            <div class="promo-wrapper">
                                <div class="promo-txt"><?php
                                    esc_html_e('Unlock Premium Templates', 'king-addons');
                                    echo '<ul><li>$2/month</li>';
                                    echo '<li>Unlimited Downloads</li>';
                                    echo '<li>Keep Them Even After</li></ul>';
                                    ?></div>
                                <a class="purchase-btn"
                                   href="https://kingaddons.com/pricing/?utm_source=kng-templates-banner-side&utm_medium=plugin&utm_campaign=kng"
                                   target="_blank">
                                    <button class="promo-btn purchase-btn"
                                            style="display: flex;align-items: center;"
                                    >
                                        <img src="<?php echo esc_url(KING_ADDONS_URL) . 'includes/admin/img/icon-for-admin.svg'; ?>"
                                             style="margin-right: 5px;width: 14px;height: 14px;"
                                             alt="<?php echo esc_html__('Upgrade Now', 'king-addons'); ?>"><?php esc_html_e('Upgrade Now', 'king-addons'); ?>
                                    </button>
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="templates-grid-wrapper">
                    <div class="search-wrapper">
                        <input type="text" id="template-search" value="<?php echo esc_attr($search_query); ?>"
                               placeholder="<?php esc_attr_e('Search templates...', 'king-addons'); ?>">
                    </div>
                    <div class="templates-grid">
                        <?php echo $result['grid_html']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                        ?>
                    </div>
                    <div class="pagination">
                        <?php echo $result['pagination_html']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                        ?>
                    </div>
                </div>
            </div>
            <div id="template-preview-popup" style="display:none;">
                <div class="popup-content">
                    <div class="popup-content-nav">
                        <button data-plan-active="<?php echo ($is_premium_active) ? 'premium' : 'free'; ?>"
                                id="install-template">
                            <?php esc_html_e('Import Template', 'king-addons'); ?>
                        </button>
                        <a href="#" id="template-preview-link" target="_blank">
                            <?php esc_html_e('Live Preview', 'king-addons'); ?>
                        </a>
                        <div class="preview-mode-switcher">
                            <button data-mode="desktop" class="active" id="preview-desktop">
                                <span class="dashicons dashicons-desktop"></span>
                            </button>
                            <button data-mode="tablet" id="preview-tablet">
                                <span class="dashicons dashicons-tablet"></span>
                            </button>
                            <button data-mode="mobile" id="preview-mobile">
                                <span class="dashicons dashicons-smartphone"></span>
                            </button>
                        </div>
                        <button id="close-popup">
                            <?php esc_html_e('Close Preview X', 'king-addons'); ?>
                        </button>
                    </div>
                    <iframe id="template-preview-iframe" src="" frameborder="0"></iframe>
                </div>
            </div>
            <div id="template-installing-popup" style="display:none;">
                <div class="popup-content">
                    <div id="progress-container">
                        <div id="progress-bar">
                            0%
                        </div>
                    </div>
                    <div id="progress"></div>
                    <div id="image-list"></div>
                    <div id="final_response"></div>
                    <button id="close-installing-popup"
                            style="display:none;"><?php esc_html_e('Close', 'king-addons'); ?></button>
                    <a href="#" id="go-to-imported-page"
                       style="display:none;"><?php esc_html_e('Go to imported page', 'king-addons'); ?></a>
                </div>
            </div>
            <div id="license-activating-popup" style="display:none;">
                <div class="license-activating-popup-content">
                    <div class="license-activating-popup-txt"><?php esc_html_e('1. Download and install the premium version of the plugin - King Addons Pro. You can find the link in the email received after the license purchase.', 'king-addons'); ?></div>
                    <div class="license-activating-popup-txt"><?php esc_html_e('2. Go to the Plugins page.', 'king-addons'); ?></div>
                    <div class="license-activating-popup-txt"><?php esc_html_e('3. Find the King Addons Pro plugin.', 'king-addons'); ?></div>
                    <div class="license-activating-popup-txt"><?php esc_html_e('4. Click on Activate License link.', 'king-addons'); ?></div>
                    <div class="license-activating-popup-txt"><?php esc_html_e('5. Enter the License Key provided in the email. Done!', 'king-addons'); ?></div>
                    <button id="close-license-activating-popup"><?php esc_html_e('Close', 'king-addons'); ?></button>
                </div>
            </div>
            <div id="premium-promo-popup" style="display:none;">
                <div class="premium-promo-popup-content">
                    <div class="premium-promo-popup-wrapper">
                        <div class="premium-promo-popup-txt"><?php

                            echo '<span class="pr-popup-title">Want This Premium Template?</span>';
                            echo '<br><span class="pr-popup-desc">';
                            echo 'Get <span class="pr-popup-desc-b">unlimited downloads</span> for just';
                            echo ' <span class="pr-popup-desc-b">$2/month';
                            echo '</span> — keep them <span class="pr-popup-desc-b">even after</span> your subscription ends!';
                            echo '</span>';

                            ?></div>
                        <a class="purchase-btn"
                           href="https://kingaddons.com/pricing/?utm_source=kng-templates-banner-pro&utm_medium=plugin&utm_campaign=kng"
                           target="_blank">
                            <button class="premium-promo-popup-purchase-btn purchase-btn">
                                <img src="<?php echo esc_url(KING_ADDONS_URL) . 'includes/admin/img/icon-for-admin.svg'; ?>"
                                     style="margin-right: 7px;width: 16px;height: 16px;"
                                     alt="<?php echo esc_html__('Upgrade Now', 'king-addons'); ?>"><?php esc_html_e('Upgrade Now', 'king-addons'); ?>
                            </button>
                        </a>
                        <button id="close-premium-promo-popup"><?php esc_html_e('Close', 'king-addons'); ?></button>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }

    private function get_filtered_templates($templates, $search_query, $selected_category, $selected_tags, $selected_collection, $current_page): array
    {
        $search_terms = array_filter(explode(' ', $search_query));
        $has_search = !empty($search_terms);
//        $filtered_templates = [];

        // Filter templates based on search and selected filters
        if (!$has_search) {
            $filtered_templates = array_filter($templates['templates'], function ($template) use ($search_terms, $selected_category, $selected_tags, $selected_collection) {

                foreach ($search_terms as $term) {
                    $found_in_title = stripos($template['title'], $term) !== false;
                    $found_in_tags = false;

                    foreach ($template['tags'] as $tag) {
                        if (stripos($tag, $term) !== false) {
                            $found_in_tags = true;
                            break;
                        }
                    }

                    if (!$found_in_title && !$found_in_tags) {
                        return false;
                    }
                }

                if ($selected_category && $template['category'] !== $selected_category) {
                    return false;
                }

                if ($selected_tags) {
                    $template_tags = $template['tags'];
                    foreach ($selected_tags as $tag) {
                        if (!in_array($tag, $template_tags)) {
                            return false;
                        }
                    }
                }

                if ($selected_collection && $template['collection'] != $selected_collection) {
                    return false;
                }

                return true;
            });

            // Shuffle templates
            // shuffle($filtered_templates);

        } else {

            $filtered_templates = $templates['templates'];

            if (!empty($search_terms) || !empty($selected_category) || !empty($selected_tags) || !empty($selected_collection)) {
                $matched_by_title = [];
                $matched_by_tags = [];

                foreach ($filtered_templates as $key => $template) {

                    $found_in_title = false;
                    $found_in_tags = false;

                    foreach ($search_terms as $term) {
                        if ($term && stripos($template['title'], $term) !== false) {
                            $found_in_title = true;
                        }

                        foreach ($template['tags'] as $tag) {
                            if (stripos($tag, $term) !== false) {
                                $found_in_tags = true;
                                break;
                            }
                        }

//                        if (!$found_in_title && !$found_in_tags) {
//                            continue;
//                        }
                    }

                    if ($selected_category && $template['category'] !== $selected_category) {
                        continue;
                    }

                    if ($selected_tags) {
                        $template_tags = $template['tags'];
                        foreach ($selected_tags as $tag) {
                            if (!in_array($tag, $template_tags)) {
                                continue 2;
                            }
                        }
                    }

                    if ($selected_collection && $template['collection'] != $selected_collection) {
                        continue;
                    }

                    // Attach the template key for later use
                    $template['template_key'] = $key;

                    if ($found_in_title) {
                        $matched_by_title[] = $template;
                    } elseif ($found_in_tags) {
                        $matched_by_tags[] = $template;
                    }
                }

                // Combine the arrays, so templates matching by title come first
                $filtered_templates = array_merge($matched_by_title, $matched_by_tags);
            }
        }

        // Pagination setup
        $items_per_page = 20;
        $total_templates = count($filtered_templates);
        $offset = ($current_page - 1) * $items_per_page;
        $paged_templates = array_slice($filtered_templates, $offset, $items_per_page);

        ob_start();
        if (empty($paged_templates)) {
            echo '<p class="templates-not-found">' . esc_html__('No templates found.', 'king-addons') . '</p>';
        } else {
            foreach ($paged_templates as $key => $template) {
                $attr_key = ($has_search) ? $template['template_key'] : $key;
                ?>
                <div class="template-item"
                     data-category="<?php echo esc_attr($template['category']); ?>"
                     data-tags="<?php echo esc_attr(implode(',', $template['tags'])); ?>"
                     data-template-key="<?php echo esc_attr($attr_key); ?>"
                     data-template-plan="<?php echo esc_attr($template['plan']); ?>">
                    <img class="kng-addons-template-thumbnail" loading="lazy"
                         src="<?php echo esc_url("https://thumbnails.kingaddons.com/$attr_key.png?v=4"); ?>"
                         alt="<?php echo esc_attr($template['title']); ?>">
                    <h3><?php echo esc_html($template['title']); ?></h3>
                    <div class="template-plan template-plan-<?php echo esc_html($template['plan']); ?>"><?php echo esc_html($template['plan']); ?></div>
                </div>
                <?php
            }
        }
        $grid_html = ob_get_clean();

        ob_start();

        $pages = paginate_links(array(
            'base' => add_query_arg(array(
                'paged' => '%#%',
                's' => $search_query,
                'category' => $selected_category,
                'collection' => $selected_collection,
                'tags' => implode(',', $selected_tags),
            )),
            'format' => '?paged=%#%',
            'current' => $current_page,
            'total' => ceil($total_templates / $items_per_page),
            'prev_text' => __('&larr; Previous', 'king-addons'),
            'next_text' => __('Next &rarr;', 'king-addons'),
            'end_size' => 9,
            'mid_size' => 3,
        ));
        if ($pages) {
            echo '<div id="king-addons-pagination-inner-wrap" class="pagination-inner-wrap"><div class="pagination-inner">';
            echo $pages; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            echo '</div></div>';
        }

        $pagination_html = ob_get_clean();

        return ['grid_html' => $grid_html, 'pagination_html' => $pagination_html];
    }

    public function king_addons_enqueue_scripts(): void
    {
        $screen = get_current_screen();
        if ($screen->id === 'toplevel_page_king-addons-templates') {
            wp_enqueue_style('king-addons-templates-style', KING_ADDONS_URL . 'includes/admin/css/templates.css', '', KING_ADDONS_VERSION);

            if (!wp_script_is(KING_ADDONS_ASSETS_UNIQUE_KEY . '-' . 'jquery' . '-' . 'jquery')) {
                wp_enqueue_script(KING_ADDONS_ASSETS_UNIQUE_KEY . '-' . 'jquery' . '-' . 'jquery', '', '', KING_ADDONS_VERSION);
            }

            wp_enqueue_script('king-addons-templates-script', KING_ADDONS_URL . 'includes/admin/js/templates.js', '', KING_ADDONS_VERSION, true);

            wp_localize_script('king-addons-templates-script', 'kingAddonsData', array(
                'adminUrl' => admin_url('admin-post.php'),
                'ajaxUrl' => admin_url('admin-ajax.php'),
            ));
        }
        if ($screen->id === 'king-addons_page_king-addons-account') {
            wp_enqueue_style('king-addons-account-style', KING_ADDONS_URL . 'includes/admin/css/account.css', '', KING_ADDONS_VERSION);
        }
    }

    public function __construct()
    {
        add_action('admin_enqueue_scripts', array($this, 'king_addons_enqueue_scripts'));
        add_action('wp_ajax_import_elementor_page_with_images', array($this, 'import_elementor_page_with_images'));
        add_action('wp_ajax_process_import_images', array($this, 'process_import_images'));
        add_action('wp_ajax_filter_templates', array($this, 'handle_filter_templates'));
    }

    function handle_filter_templates(): void
    {
        if (!current_user_can('manage_options')) {
            return;
        }

        if (!isset($_POST['action']) || $_POST['action'] !== 'filter_templates') {
            wp_send_json_error('Invalid request');
            return;
        }

        $search_query = isset($_POST['s']) ? sanitize_text_field($_POST['s']) : '';
        $selected_category = isset($_POST['category']) ? sanitize_text_field($_POST['category']) : '';
        $selected_collection = isset($_POST['collection']) ? sanitize_text_field($_POST['collection']) : '';
        $selected_tags = isset($_POST['tags']) ? array_filter(explode(',', sanitize_text_field($_POST['tags']))) : [];
        $current_page = isset($_POST['paged']) ? max(1, intval($_POST['paged'])) : 1;

        $templates = TemplatesMap::getTemplatesMapArray();

        // Use the common function to get filtered templates and pagination
        $result = $this->get_filtered_templates($templates, $search_query, $selected_category, $selected_tags, $selected_collection, $current_page);

        wp_send_json_success(['grid_html' => $result['grid_html'], 'pagination_html' => $result['pagination_html']]);
    }

    public function import_elementor_page_with_images(): void
    {
        if (!current_user_can('manage_options')) {
            return;
        }

        $import_data = json_decode(stripslashes($_POST['data']), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            wp_send_json_error('JSON decode error: ' . json_last_error_msg());
            return;
        }

        if (isset($import_data['content']) && isset($import_data['images']) && isset($import_data['title'])) {
            $content = $import_data['content'];
            $image_data = $import_data['images'];

            $page_title = sanitize_text_field($import_data['title']);
            $elementor_version = sanitize_text_field($import_data['elementor_version']);

            delete_transient('elementor_import_content');
            delete_transient('elementor_import_images');
            delete_transient('elementor_import_total_images');
            delete_transient('elementor_import_images_processed');
            delete_transient('elementor_import_image_retry_count');
            delete_transient('elementor_import_page_title');
            delete_transient('elementor_import_elementor_version');

            set_transient('elementor_import_content', $content, 60 * 60);
            set_transient('elementor_import_images', $image_data, 60 * 60);
            set_transient('elementor_import_total_images', count($image_data), 60 * 60);
            set_transient('elementor_import_images_processed', 0, 60 * 60);
            set_transient('elementor_import_image_retry_count', [], 60 * 60);
            set_transient('elementor_import_page_title', $page_title, 60 * 60);
            set_transient('elementor_import_elementor_version', $elementor_version, 60 * 60);

            wp_send_json_success([
                'message' => 'Import initialized.',
                'images' => $image_data
            ]);
        } else {
            wp_send_json_error('Invalid import data.');
        }
    }

    public function process_import_images(): void
    {
        if (!current_user_can('manage_options')) {
            wp_send_json_error('The current user can not manage options and create pages. Please change it in the WordPress settings.');
            return;
        }

        $start_time = time();
        $timeout = 30;

        $content = get_transient('elementor_import_content');
        $image_data = get_transient('elementor_import_images');
        $total_images = get_transient('elementor_import_total_images');
        $images_processed = get_transient('elementor_import_images_processed');
        $image_retry_count = get_transient('elementor_import_image_retry_count');
        $page_title = get_transient('elementor_import_page_title');
        $elementor_version = get_transient('elementor_import_elementor_version');

        if (!is_array($image_retry_count)) {
            $image_retry_count = [];
        }

        if ($images_processed < $total_images) {
            $current_image = $image_data[$images_processed];
            $url = $current_image['url'];
            if (!isset($image_retry_count[$url])) {
                $image_retry_count[$url] = 0;
            }

            $new_image_id = $this->download_image_to_media_gallery($url, $image_retry_count[$url]);
            if ($new_image_id === false) {
                $image_retry_count[$url]++;
                set_transient('elementor_import_image_retry_count', $image_retry_count, 60 * 60);

                if ($image_retry_count[$url] > 3) {
                    $images_processed++;
                    set_transient('elementor_import_images_processed', $images_processed, 60 * 60);
                    $progress = round(($images_processed / $total_images) * 100);

                    wp_send_json_success([
                        'progress' => $progress,
                        'message' => "Skipped image $url after 3 attempts.",
                        'image_url' => $url,
                        'images_processed' => $images_processed,
                        'new_image_id' => 'SKIPPED'
                    ]);
                } else {
                    wp_send_json_error([
                        'retry' => true,
                        'image_url' => $url
                    ]);
                }
            } else {
                $new_url = wp_get_attachment_url($new_image_id);
                $images_processed++;
                set_transient('elementor_import_images_processed', $images_processed, 60 * 60);
                $progress = round(($images_processed / $total_images) * 100);

                array_walk_recursive($content, function (&$value, $key) use ($url, $new_url, $new_image_id, $current_image) {
                    if ($key === 'url' && $value === $url) {
                        $value = $new_url;
                    } elseif ($key === 'id' && $value === $current_image['id']) {
                        $value = $new_image_id;
                    }
                });

                set_transient('elementor_import_content', $content, 60 * 60);

                wp_send_json_success([
                    'progress' => $progress,
                    'message' => "Processed $images_processed of $total_images images.",
                    'image_url' => $url,
                    'new_image_url' => $new_url
                ]);
            }
        } else {
            $new_post_id = wp_insert_post([
                'post_title' => $page_title,
                'post_content' => '',
                'post_status' => 'publish',
                'post_type' => 'page',
            ]);

            if ($new_post_id) {
                update_post_meta($new_post_id, '_elementor_data', wp_slash(json_encode($content)));
                update_post_meta($new_post_id, '_elementor_edit_mode', 'builder');
                update_post_meta($new_post_id, '_elementor_template_type', 'wp-page');
                update_post_meta($new_post_id, '_elementor_version', $elementor_version);

                update_post_meta($new_post_id, '_wp_page_template', 'elementor_canvas');

                update_post_meta($new_post_id, '_wp_gutenberg_disable', '1');
                update_post_meta($new_post_id, '_wp_gutenberg_enabled', '0');

                delete_transient('elementor_import_content');
                delete_transient('elementor_import_images');
                delete_transient('elementor_import_total_images');
                delete_transient('elementor_import_images_processed');
                delete_transient('elementor_import_image_retry_count');
                delete_transient('elementor_import_page_title');
                delete_transient('elementor_import_elementor_version');

                wp_send_json_success([
                    'message' => "Page imported successfully!",
                    'page_url' => get_permalink($new_post_id),
                ]);
            } else {
                wp_send_json_error('Failed to import page.');
            }
        }

        if (time() >= $start_time + $timeout) {
            wp_send_json_error('Process timeout, please resume the import.');
        }
    }

    public function download_image_to_media_gallery($image_url, $image_retry_count)
    {
        try {
            $response = wp_remote_get($image_url);

            if (is_wp_error($response)) {
                throw new Exception('HTTP request error: ' . $response->get_error_message());
            }

            $image_data = wp_remote_retrieve_body($response);
            if (empty($image_data)) {
                throw new Exception('Failed to retrieve image data from URL: ' . $image_url);
            }

            $image_name = pathinfo(basename($image_url), PATHINFO_FILENAME);
            $image_extension = pathinfo(basename($image_url), PATHINFO_EXTENSION);
            $unique_image_name = $image_name . '-' . time() . '.' . $image_extension;

            $upload_dir = wp_upload_dir();
            $image_file = $upload_dir['path'] . '/' . $unique_image_name;

            if (file_put_contents($image_file, $image_data) === false) {
                throw new Exception('Failed to write image data to file: ' . $image_file);
            }

            $wp_filetype = wp_check_filetype($unique_image_name);
            $attachment = [
                'post_mime_type' => $wp_filetype['type'],
                'post_title' => sanitize_file_name($unique_image_name),
                'post_content' => '',
                'post_status' => 'inherit',
            ];

            $attach_id = wp_insert_attachment($attachment, $image_file);

            if ($image_retry_count > 1) {
                add_filter('intermediate_image_sizes', '__return_empty_array', 999);
            }

            require_once(ABSPATH . 'wp-admin/includes/image.php');
            $attach_data = wp_generate_attachment_metadata($attach_id, $image_file);
            wp_update_attachment_metadata($attach_id, $attach_data);

            if ($image_retry_count > 1) {
                remove_filter('intermediate_image_sizes', '__return_empty_array', 999);
            }

            return $attach_id;
        } catch (Exception $e) {
            return false;
        }
    }
}

Templates::instance();
