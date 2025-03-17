<?php
/**
 * Core class do all things at the start of the plugin
 */

namespace King_Addons;

use Elementor\Plugin;
use Elementor\Widgets_Manager;
use Elementor\Controls_Manager;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

final class Core
{
    /**
     * Instance
     *
     * @var Core|null The single instance of the class.
     */
    private static ?Core $_instance = null;

    /**
     * Instance
     *
     * Ensures only one instance of the class is loaded or can be loaded.
     *
     * @return Core An instance of the class.
     * @since 1.0.0
     */
    public static function instance(): Core
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Constructor
     *
     * Perform some compatibility checks to make sure basic requirements are meet.
     * If all compatibility checks pass, initialize the functionality.
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        require_once(KING_ADDONS_PATH . 'includes/ModulesMap.php');
        require_once(KING_ADDONS_PATH . 'includes/LibrariesMap.php');

        if ($this->hasElementorCompatibility()) {

            require_once(KING_ADDONS_PATH . 'includes/helpers/Elementor/elementor-constants.php');

            // Initial requirements check
            require_once(KING_ADDONS_PATH . 'includes/helpers/Check_Requirements/Check_Requirements.php');

            // Templates Catalog
            if (KING_ADDONS_EXT_TEMPLATES_CATALOG) {
                require_once(KING_ADDONS_PATH . 'includes/TemplatesMap.php');
                require_once(KING_ADDONS_PATH . 'includes/extensions/Templates/CollectionsMap.php');
                require_once(KING_ADDONS_PATH . 'includes/extensions/Templates/Templates.php');
            }

            // Header & Footer Builder
            if (KING_ADDONS_EXT_HEADER_FOOTER_BUILDER) {
                require_once(KING_ADDONS_PATH . 'includes/extensions/Header_Footer_Builder/Header_Footer_Builder.php');
                Header_Footer_Builder::instance();
            }

            // Popup Builder
            if (KING_ADDONS_EXT_POPUP_BUILDER) {
                require_once(KING_ADDONS_PATH . 'includes/extensions/Popup_Builder/Popup_Builder.php');
                Popup_Builder::instance();
            }

            // Admin
            require_once(KING_ADDONS_PATH . 'includes/Admin.php');

            // Additional - Controls
            require_once(KING_ADDONS_PATH . 'includes/controls/Ajax_Select2/Ajax_Select2.php');
            require_once(KING_ADDONS_PATH . 'includes/controls/Ajax_Select2/Ajax_Select2_API.php');
            require_once(KING_ADDONS_PATH . 'includes/controls/Animations/Animations.php');
            require_once(KING_ADDONS_PATH . 'includes/controls/Animations/Button_Animations.php');

            // Additional - Widgets
            require_once(KING_ADDONS_PATH . 'includes/widgets/Search/Search_Ajax.php');
            require_once(KING_ADDONS_PATH . 'includes/widgets/MailChimp/MailChimp_Ajax.php');

            // Additional - Grids, Magazine Grid
            require_once(KING_ADDONS_PATH . 'includes/helpers/Grid/Filter_Posts_Ajax.php');
            require_once(KING_ADDONS_PATH . 'includes/helpers/Grid/Filter_WooCommerce_Products_Ajax.php');
            require_once(KING_ADDONS_PATH . 'includes/helpers/Grid/Post_Likes_Ajax.php');

            // Additional - Form Builder
//            require_once(KING_ADDONS_PATH . 'includes/helpers/Form_Builder/Create_Submission.php');
//            require_once(KING_ADDONS_PATH . 'includes/helpers/Form_Builder/Send_Email.php');
//            require_once(KING_ADDONS_PATH . 'includes/helpers/Form_Builder/Send_Webhook.php');
//            require_once(KING_ADDONS_PATH . 'includes/helpers/Form_Builder/Subscribe_Mailchimp.php');
//            require_once(KING_ADDONS_PATH . 'includes/helpers/Form_Builder/Update_Action_Meta.php');
//            require_once(KING_ADDONS_PATH . 'includes/helpers/Form_Builder/Upload_Email_File.php');
//            require_once(KING_ADDONS_PATH . 'includes/helpers/Form_Builder/Verify_Google_Recaptcha.php');
//            require_once(KING_ADDONS_PATH . 'includes/helpers/Form_Builder/View_Submissions_Pro.php');

            self::enableWidgetsByDefault();

            add_action('elementor/init', [$this, 'initElementor']);

            add_action('elementor/elements/categories_registered', [$this, 'addWidgetCategory']);
            add_action('elementor/controls/controls_registered', [$this, 'registerControls']);

            self::enableFeatures();

            new Admin();

            add_action('wp_enqueue_scripts', [$this, 'enqueueFrontendStyles']);
            add_action('wp_enqueue_scripts', [$this, 'enqueueLightboxDynamicStyles']);

            // Notice - Upgrade Suggestion
            if (!king_addons_freemius()->can_use_premium_code__premium_only()) {
                add_action('wp_ajax_king_addons_premium_notice_dismiss', [$this, 'king_addons_premium_notice_dismiss_callback']);
                add_action('admin_notices', [$this, 'showNoticeUpgrade']);
            }
        }
    }

    function king_addons_premium_notice_dismiss_callback()
    {
        // Check user capabilities
        if (!current_user_can('manage_options')) {
            wp_die();
        }
        $user_id = get_current_user_id();
        // Save the current time as the last dismissal time for the premium notice
        update_user_meta($user_id, 'king_addons_premium_notice_dismissed_time', time());
        wp_die(); // End AJAX request
    }

    function showNoticeUpgrade()
    {
        // Check user capabilities; show notice only to administrators as an example
        if (!current_user_can('manage_options')) {
            return;
        }

        $user_id = get_current_user_id();
        $now = time();
        // Retrieve the last time the premium notice was dismissed by the user
        $last_dismissed = get_user_meta($user_id, 'king_addons_premium_notice_dismissed_time', true);

        // If the premium notice was dismissed less than a week ago (604800 seconds), do not show it
        if ($last_dismissed && ($now - $last_dismissed) < 604800) {
//        if ($last_dismissed && ($now - $last_dismissed) < 60) {
            return;
        }
        ?>
        <div class="king-addons-upgrade-notice notice notice-info is-dismissible"
             style="border-left: 4px solid #0073aa;padding: 10px 15px;">
            <p style="font-size: 15px; margin:0; display: flex; align-items: center;">
                                <span>Get access to <strong style="font-weight: 700;">600+</strong> premium templates and <strong
                                            style="font-weight: 700;">200+</strong> features for only $<strong
                                            style="font-weight: 700;">2</strong>/month. Upgrade now and boost your website!</span>
                <span>
                <a style="margin-left: 10px;font-size: 14px;padding: 3px 20px;display: flex;align-items: center;"
                   href="https://kingaddons.com/pricing?utm_source=kng-notice-offer&utm_medium=plugin&utm_campaign=kng"
                   class="button button-primary"><img style="margin-right: 7px;width: 15px;height: 15px;"
                                                      src="<?php echo esc_url(KING_ADDONS_URL) . 'includes/admin/img/icon-for-admin.svg'; ?>"
                                                      alt="<?php echo esc_html__('Upgrade Now', 'king-addons'); ?>">Upgrade Now</a>
                </span>
            </p>
        </div>
        <script>
            (function ($) {
                // Wait for the document to be ready
                $(document).ready(function () {
                    // Attach click handler to the dismiss button of the premium notice
                    $('.king-addons-upgrade-notice.notice.is-dismissible').on('click', '.notice-dismiss', function () {
                        $.post(ajaxurl, {
                            action: 'king_addons_premium_notice_dismiss'
                        });
                    });
                });
            })(jQuery);
        </script>
        <?php
    }

    function enqueueFrontendStyles()
    {
        /**
         * It fixes the default Elementor SVG icon rendering feature (Settings -> Features -> Inline Font Icons)
         * because sometimes Elementor still renders Font Awesome icons but doesn't load the corresponding Font Awesome styles.
         * Therefore, we have to enqueue the styles.
         */
        wp_enqueue_style(
            'font-awesome-5-all',
            ELEMENTOR_ASSETS_URL . 'lib/font-awesome/css/all' . (defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '' : '.min') . '.css',
            false,
            KING_ADDONS_VERSION
        );
    }

    function hasElementorCompatibility(): bool
    {
        // Check if Elementor installed and activated
        if (!did_action('elementor/loaded')) {
            add_action('admin_notices', [$this, 'showAdminNotice_ElementorRequired']);
            return false;
        }

        // Check for required Elementor version
        if (!version_compare(ELEMENTOR_VERSION, KING_ADDONS_MINIMUM_ELEMENTOR_VERSION, '>=')) {
            add_action('admin_notices', [$this, 'showAdminNotice_ElementorMinimumVersion']);
            return false;
        }

        return true;
    }

    function showAdminNotice_ElementorRequired(): void
    {
        $screen = get_current_screen();
        if (isset($screen->parent_file) && 'plugins.php' === $screen->parent_file && 'update' === $screen->id) {
            return;
        }

        if (isset(get_plugins()['elementor/elementor.php'])) {
            if (!current_user_can('activate_plugins') || is_plugin_active('elementor/elementor.php')) {
                return;
            }
            $plugin = 'elementor/elementor.php';
            $activation_url = wp_nonce_url('plugins.php?action=activate&amp;plugin=' . $plugin . '&amp;plugin_status=all&amp;paged=1&amp;s', 'activate-plugin_' . $plugin);
            $message = '<div class="error"><p>' . esc_html__('King Addons plugin is not working because you need to activate the Elementor plugin.', 'king-addons') . '</p>';
            /** @noinspection HtmlUnknownTarget */
            $message .= '<p>' . sprintf('<a href="%s" class="button-primary">%s</a>', $activation_url, esc_html__('Activate Elementor now', 'king-addons')) . '</p></div>';
        } else {
            if (!current_user_can('install_plugins')) {
                return;
            }
            $install_url = wp_nonce_url(self_admin_url('update.php?action=install-plugin&plugin=elementor'), 'install-plugin_elementor');
            $message = '<div class="error"><p>' . esc_html__('King Addons plugin is not working because you need to install the Elementor plugin.', 'king-addons') . '</p>';
            /** @noinspection HtmlUnknownTarget */
            $message .= '<p>' . sprintf('<a href="%s" class="button-primary">%s</a>', $install_url, esc_html__('Install Elementor now', 'king-addons')) . '</p></div>';
        }
        echo $message;
    }

    function showAdminNotice_ElementorMinimumVersion(): void
    {
        $message = sprintf(
        /* translators: 1: Plugin name 2: Elementor 3: Required Elementor version */
            esc_html__('%1$s plugin requires %2$s plugin version %3$s or greater.', 'king-addons'),
            esc_html__('King Addons', 'king-addons'),
            esc_html__('Elementor', 'king-addons'),
            KING_ADDONS_MINIMUM_ELEMENTOR_VERSION
        );
        echo '<div class="notice notice-error"><p>' . esc_html($message) . '</p></div>';
    }

    public function initElementor(): void
    {
        add_action('elementor/widgets/register', [$this, 'registerWidgets']);
        add_action('elementor/editor/after_enqueue_styles', [$this, 'enqueueEditorStyles']);
        add_action('elementor/editor/after_enqueue_scripts', [$this, 'enqueueEditorScripts']);
    }

    function addWidgetCategory(): void
    {
        Plugin::instance()->elements_manager->add_category(
            'king-addons',
            [
                'title' => esc_html__('King Addons', 'king-addons'),
                'icon' => 'fa fa-plug'
            ]
        );
    }

    /**
     * Registers Elementor widgets with a mechanism to skip (and remember) broken widgets
     * that caused a fatal error previously, and try them again if the plugin version is updated.
     *
     * @param Widgets_Manager $widgets_manager
     * @return void
     */
    function registerWidgets(Widgets_Manager $widgets_manager): void
    {
        // Used to track which widget is currently being loaded when a fatal error occurs
        static $currentlyLoadingWidgetId = null;

        $currentPluginVersion = KING_ADDONS_VERSION;

        // Get plugin options to check if a widget is enabled
        $options = get_option('king_addons_options');

        /**
         * Retrieve the array of broken widgets from the WordPress options.
         * The structure is expected to be something like:
         *
         *   'widget_id' => [
         *       'version' => '1.2.0',
         *       'error'   => 'Some fatal error message'
         *   ],
         *   ...
         *
         */
        $brokenWidgets = get_option('king_addons_broken_widgets', []);

        /**
         * STEP 1: Clear out any "broken widgets" where the stored version is
         * less than the current plugin version. This gives them a second chance
         * after an update, assuming the issue may have been fixed.
         */
        foreach ($brokenWidgets as $brokenId => $brokenData) {
            if (
                isset($brokenData['version'])
                && version_compare($currentPluginVersion, $brokenData['version'], '>')
            ) {
                // If the plugin version is now higher, we remove the widget from the blacklist
                unset($brokenWidgets[$brokenId]);
            }
        }

        // Update the option after cleaning up
        update_option('king_addons_broken_widgets', $brokenWidgets);

        /**
         * STEP 2: Use register_shutdown_function to detect any fatal errors (E_ERROR, E_PARSE, etc.)
         * that might occur during the loading of a widget. If an error is detected, store that widget
         * in the "broken" list with the current plugin version and the error message.
         */
        register_shutdown_function(function () use (&$currentlyLoadingWidgetId, $currentPluginVersion) {
            $error = error_get_last();
            if ($error && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
                // If a fatal error occurred while loading a specific widget
                if (!empty($currentlyLoadingWidgetId)) {
                    $brokenWidgetsLocal = get_option('king_addons_broken_widgets', []);
                    $brokenWidgetsLocal[$currentlyLoadingWidgetId] = [
                        'version' => $currentPluginVersion,
                        'error' => $error['message'] ?? ''
                    ];
                    update_option('king_addons_broken_widgets', $brokenWidgetsLocal);
                }
            }
        });

        /**
         * STEP 3: Now we iterate through all widgets in our modules map and try to load them.
         * If a widget is in the broken list, we skip it to avoid repeated fatal errors.
         */
        foreach (ModulesMap::getModulesMapArray()['widgets'] as $widget_id => $widget) {
            // Check if the widget is enabled in the options
            if (!isset($options[$widget_id]) || $options[$widget_id] !== 'enabled') {
                continue;
            }

            // If this widget is listed as broken, skip it
            if (array_key_exists($widget_id, $brokenWidgets)) {
                // Log something here if needed:
                // error_log("Skipping widget {$widget_id}, it previously caused a fatal error.");
                continue;
            }

            // Track which widget we're loading
            $currentlyLoadingWidgetId = $widget_id;

            // Include the base widget class
            $widget_class = $widget['php-class'];
            $path_widget_class = "King_Addons\\" . $widget_class;
            require_once(KING_ADDONS_PATH . 'includes/widgets/' . $widget_class . '/' . $widget_class . '.php');

            // Check if we can load the Pro version
            if (
                function_exists('king_addons_freemius')
                && king_addons_freemius()->can_use_premium_code__premium_only()
                && defined('KING_ADDONS_PRO_PATH')
            ) {
                if (!empty($widget['has-pro'])) {
                    $pro_file_path = KING_ADDONS_PRO_PATH . 'includes/widgets/' . $widget_class . '_Pro/' . $widget_class . '_Pro.php';

                    if (file_exists($pro_file_path)) {
                        require_once($pro_file_path);
                        $path_widget_class_pro = "King_Addons\\" . $widget_class . '_Pro';
                        $widgets_manager->register(new $path_widget_class_pro);
                    } else {
                        // If Pro file doesn't exist, register the base widget
                        $widgets_manager->register(new $path_widget_class);
                    }
                } else {
                    // No 'has-pro', register the base widget
                    $widgets_manager->register(new $path_widget_class);
                }
            } else {
                // No Freemius Pro available, register the base widget
                $widgets_manager->register(new $path_widget_class);
            }

            // Clear the tracking variable after successful load
            $currentlyLoadingWidgetId = null;
        }
    }

    function enableWidgetsByDefault(): void
    {
        $options = get_option('king_addons_options');

        foreach (ModulesMap::getModulesMapArray()['widgets'] as $widget_id => $widget) {

            if (!($options[$widget_id] ?? null)) {
                $options[$widget_id] = 'enabled';
                update_option('king_addons_options', $options);
            }
        }
    }

    function enableFeatures(): void
    {
        $options = get_option('king_addons_options');

        foreach (ModulesMap::getModulesMapArray()['features'] as $feature_id => $feature) {

            if (!($options[$feature_id] ?? null)) {
                $options[$feature_id] = 'enabled';
                update_option('king_addons_options', $options);
            }

            if ($options[$feature_id] === 'enabled') {
                $feature_class = $feature['php-class'];
                $path_feature_class = "King_Addons\\" . $feature_class;
                require_once(KING_ADDONS_PATH . 'includes/features/' . $feature_class . '/' . $feature_class . '.php');
                new $path_feature_class;
            }
        }
    }

    public function registerControls(Controls_Manager $controls_manager): void
    {
        $controls_manager->register(new AJAX_Select2\Ajax_Select2());
        $controls_manager->register(new Animations\Animations());
        $controls_manager->register(new Animations\Animations_Alternative());
        $controls_manager->register(new Button_Animations\Button_Animations());
    }

    function enqueueEditorStyles(): void
    {
        wp_enqueue_style(KING_ADDONS_ASSETS_UNIQUE_KEY . '-elementor-editor', KING_ADDONS_URL . 'includes/admin/css/elementor-editor.css', '', KING_ADDONS_VERSION);
    }

    function enqueueEditorScripts(): void
    {
        wp_enqueue_script(KING_ADDONS_ASSETS_UNIQUE_KEY . '-elementor-editor', KING_ADDONS_URL . 'includes/admin/js/elementor-editor.js', '', KING_ADDONS_VERSION);
        wp_enqueue_script(KING_ADDONS_ASSETS_UNIQUE_KEY . '-data-table-export', KING_ADDONS_URL . 'includes/widgets/Data_Table/preview-handler.js', '', KING_ADDONS_VERSION);
//        wp_enqueue_script(KING_ADDONS_ASSETS_UNIQUE_KEY . '-form-builder-editor-handler', KING_ADDONS_URL . 'includes/widgets/Form_Builder/editor-handler.js', '', KING_ADDONS_VERSION);
    }

    public static function renderProFeaturesSection($module, $section, $type, $widget_name, $features): void
    {
        if (king_addons_freemius()->can_use_premium_code__premium_only()) {
            return;
        }

        $module->start_controls_section(
            'king_addons_pro_features_section',
            [
                'label' => KING_ADDONS_ELEMENTOR_ICON_PRO . '<span class="king-addons-pro-features-heading">' . esc_html__('Pro Features', 'king-addons') . '</span>',
                'tab' => $section ?: null,
            ]
        );

        $list_html = '<ul>' . implode('', array_map(fn($feature) => "<li>$feature</li>", $features)) . '</ul>';

        $module->add_control(
            'king_addons_pro_features_list',
            [
                'type' => $type,
                'raw' => $list_html . '<a class="king-addons-pro-features-cta-btn" href="https://kingaddons.com/pricing/?utm_source=kng-module-' . $widget_name . '-upgrade-pro&utm_medium=plugin&utm_campaign=kng" target="_blank">' . esc_html__('Upgrade Now', 'king-addons') . '</a>',
                'content_classes' => 'king-addons-pro-features-list',
            ]
        );

        $module->end_controls_section();
    }

    public static function renderUpgradeProNotice($module, $controls_manager, $widget_name, $option, $condition = []): void
    {
        if (king_addons_freemius()->can_use_premium_code__premium_only()) {
            return;
        }

        $module->add_control(
            $option . '_pro_notice_',
            [
                'raw' => 'Upgrade to the <strong><a href="https://kingaddons.com/pricing/?utm_source=kng-module-' . $widget_name . '-settings-upgrade-pro&utm_medium=plugin&utm_campaign=kng" target="_blank">Pro version</a></strong> now<br> and unlock this feature!',
                'type' => $controls_manager,
                'content_classes' => 'king-addons-pro-notice',
                'condition' => [
                    $option => $condition,
                ]
            ]
        );
    }

    public static function getCustomTypes($query, $exclude_defaults = true): array
    {
        $custom_types = $query === 'tax'
            ? get_taxonomies(['show_in_nav_menus' => true], 'objects')
            : get_post_types(['show_in_nav_menus' => true], 'objects');

        return array_filter(
            array_map(fn($type) => $type->label, $custom_types),
            fn($label, $key) => !$exclude_defaults || !in_array($key, ['post', 'page', 'category', 'post_tag']),
            ARRAY_FILTER_USE_BOTH
        );
    }

    public static function getShareIcon($args = []): string
    {
        $args = wp_parse_args($args, [
            'network' => '',
            'url' => '',
            'title' => '',
            'text' => '',
            'image' => '',
            'show_whatsapp_title' => 'no',
            'show_whatsapp_excerpt' => 'no',
            'tooltip' => 'no',
            'icons' => 'no',
            'labels' => 'no',
            'custom_label' => '',
        ]);

        $url = esc_url($args['url']);
        $title = wp_strip_all_tags($args['title']);
        $text = wp_strip_all_tags($args['text']);
        $image = esc_url($args['image']);
        $network = $args['network'];

        $get_whatsapp_url = function ($a) {
            if ('yes' === $a['show_whatsapp_title'] && 'yes' === $a['show_whatsapp_excerpt']) {
                return 'https://api.whatsapp.com/send?text=*' . $a['title'] . '*%0a' . $a['text'] . '%0a' . $a['url'];
            } elseif ('yes' === $a['show_whatsapp_title']) {
                return 'https://api.whatsapp.com/send?text=*' . $a['title'] . '*%0a' . $a['url'];
            } elseif ('yes' === $a['show_whatsapp_excerpt']) {
                return 'https://api.whatsapp.com/send?text=*' . $a['text'] . '%0a' . $a['url'];
            }
            return 'https://api.whatsapp.com/send?text=' . $a['url'];
        };

        $networks_map = [
            'facebook-f' => [
                'url' => "https://www.facebook.com/sharer.php?u=$url",
                'title' => esc_html__('Facebook', 'king-addons'),
                'icon' => 'fab',
            ],
            'x-twitter' => [
                'url' => "https://twitter.com/intent/tweet?url=$url",
                'title' => esc_html__('X (Twitter)', 'king-addons'),
                'icon' => 'fab',
            ],
            'linkedin-in' => [
                'url' => "https://www.linkedin.com/shareArticle?mini=true&url=$url&title=$title&summary=$text&source=$url",
                'title' => esc_html__('LinkedIn', 'king-addons'),
                'icon' => 'fab',
            ],
            'pinterest-p' => [
                'url' => "https://www.pinterest.com/pin/create/button/?url=$url&media=$image",
                'title' => esc_html__('Pinterest', 'king-addons'),
                'icon' => 'fab',
            ],
            'reddit' => [
                'url' => "https://reddit.com/submit?url=$url&title=$title",
                'title' => esc_html__('Reddit', 'king-addons'),
                'icon' => 'fab',
            ],
            'tumblr' => [
                'url' => "https://tumblr.com/share/link?url=$url",
                'title' => esc_html__('Tumblr', 'king-addons'),
                'icon' => 'fab',
            ],
            'digg' => [
                'url' => "https://digg.com/submit?url=$url",
                'title' => esc_html__('Digg', 'king-addons'),
                'icon' => 'fab',
            ],
            'xing' => [
                'url' => "https://www.xing.com/app/user?op=share&url=$url",
                'title' => esc_html__('Xing', 'king-addons'),
                'icon' => 'fab',
            ],
            'vk' => [
                'url' => "https://vk.ru/share.php?url=$url&title=$title&description=" . wp_trim_words($text, 250) . "&image=$image/",
                'title' => esc_html__('VK', 'king-addons'),
                'icon' => 'fab',
            ],
            'odnoklassniki' => [
                'url' => "https://connect.ok.ru/offer?url=$url",
                'title' => esc_html__('OK', 'king-addons'),
                'icon' => 'fab',
            ],
            'get-pocket' => [
                'url' => "https://getpocket.com/edit?url=$url",
                'title' => esc_html__('Pocket', 'king-addons'),
                'icon' => 'fab',
            ],
            'skype' => [
                'url' => "https://web.skype.com/share?url=$url",
                'title' => esc_html__('Skype', 'king-addons'),
                'icon' => 'fab',
            ],
            'whatsapp' => [
                'url' => $get_whatsapp_url($args),
                'title' => esc_html__('WhatsApp', 'king-addons'),
                'icon' => 'fab',
            ],
            'telegram' => [
                'url' => "https://telegram.me/share/url?url=$url&text=$text",
                'title' => esc_html__('Telegram', 'king-addons'),
                'icon' => 'fab',
            ],
            'envelope' => [
                'url' => "mailto:?subject=$title&body=$url",
                'title' => esc_html__('Email', 'king-addons'),
                'icon' => 'fas',
            ],
            'print' => [
                'url' => "javascript:window.print()",
                'title' => esc_html__('Print', 'king-addons'),
                'icon' => 'fas',
            ],
        ];

        if (!isset($networks_map[$network])) {
            return '';
        }

        $share_url = $networks_map[$network]['url'];
        $network_title = $networks_map[$network]['title'];
        $icon_category = $networks_map[$network]['icon'];

        $output = '<a href="' . esc_url($share_url) . '" class="king-addons-share-icon king-addons-share-' . esc_attr($network) . '" target="_blank">';

        if ('yes' === $args['tooltip']) {
            $output .= '<span class="king-addons-share-tooltip king-addons-tooltip">' . esc_html($network_title) . '</span>';
        }

        if ('yes' === $args['icons']) {
            $output .= '<i class="' . esc_attr($icon_category) . ' fa-' . esc_attr($network) . '"></i>';
        }

        if ('yes' === $args['labels']) {
            $label = !empty($args['custom_label']) ? $args['custom_label'] : $network_title;
            $output .= '<span class="king-addons-share-label">' . esc_html($label) . '</span>';
        }

        $output .= '</a>';

        return $output;
    }

    public static function validateHTMLTags($setting, $default, $tags_whitelist)
    {
        $value = $setting;
        if (!in_array($value, $tags_whitelist)) {
            $value = $default;
        }
        return $value;
    }

    public static function getIcon($icon, $dir)
    {
        if (empty($icon) || strpos($icon, 'fa-') === false) {
            return '';
        }

        $dir = $dir ? "-$dir" : '';
        return wp_kses(
            '<i class="' . esc_attr($icon . $dir) . '"></i>',
            ['i' => ['class' => []]]
        );
    }

    public static function getPluginName()
    {
        return 'King Addons';
    }

    public static function getAnimationTimings(): array
    {
        /** @noinspection DuplicatedCode */
        $timings = [
            'ease-default' => 'Default',
            'linear' => 'Linear',
            'ease-in' => 'Ease In',
            'ease-out' => 'Ease Out',
            'pro-eio' => 'EI Out (Pro)',
            'pro-eiqd' => 'EI Quad (Pro)',
            'pro-eicb' => 'EI Cubic (Pro)',
            'pro-eiqrt' => 'EI Quart (Pro)',
            'pro-eiqnt' => 'EI Quint (Pro)',
            'pro-eisn' => 'EI Sine (Pro)',
            'pro-eiex' => 'EI Expo (Pro)',
            'pro-eicr' => 'EI Circ (Pro)',
            'pro-eibk' => 'EI Back (Pro)',
            'pro-eoqd' => 'EO Quad (Pro)',
            'pro-eocb' => 'EO Cubic (Pro)',
            'pro-eoqrt' => 'EO Quart (Pro)',
            'pro-eoqnt' => 'EO Quint (Pro)',
            'pro-eosn' => 'EO Sine (Pro)',
            'pro-eoex' => 'EO Expo (Pro)',
            'pro-eocr' => 'EO Circ (Pro)',
            'pro-eobk' => 'EO Back (Pro)',
            'pro-eioqd' => 'EIO Quad (Pro)',
            'pro-eiocb' => 'EIO Cubic (Pro)',
            'pro-eioqrt' => 'EIO Quart (Pro)',
            'pro-eioqnt' => 'EIO Quint (Pro)',
            'pro-eiosn' => 'EIO Sine (Pro)',
            'pro-eioex' => 'EIO Expo (Pro)',
            'pro-eiocr' => 'EIO Circ (Pro)',
            'pro-eiobk' => 'EIO Back (Pro)',
        ];

        if (king_addons_freemius()->can_use_premium_code__premium_only()) {
            /** @noinspection DuplicatedCode */
            $timings = [
                'ease-default' => 'Default',
                'linear' => 'Linear',
                'ease-in' => 'Ease In',
                'ease-out' => 'Ease Out',
                'ease-in-out' => 'Ease In Out',
                'ease-in-quad' => 'Ease In Quad',
                'ease-in-cubic' => 'Ease In Cubic',
                'ease-in-quart' => 'Ease In Quart',
                'ease-in-quint' => 'Ease In Quint',
                'ease-in-sine' => 'Ease In Sine',
                'ease-in-expo' => 'Ease In Expo',
                'ease-in-circ' => 'Ease In Circ',
                'ease-in-back' => 'Ease In Back',
                'ease-out-quad' => 'Ease Out Quad',
                'ease-out-cubic' => 'Ease Out Cubic',
                'ease-out-quart' => 'Ease Out Quart',
                'ease-out-quint' => 'Ease Out Quint',
                'ease-out-sine' => 'Ease Out Sine',
                'ease-out-expo' => 'Ease Out Expo',
                'ease-out-circ' => 'Ease Out Circ',
                'ease-out-back' => 'Ease Out Back',
                'ease-in-out-quad' => 'Ease In Out Quad',
                'ease-in-out-cubic' => 'Ease In Out Cubic',
                'ease-in-out-quart' => 'Ease In Out Quart',
                'ease-in-out-quint' => 'Ease In Out Quint',
                'ease-in-out-sine' => 'Ease In Out Sine',
                'ease-in-out-expo' => 'Ease In Out Expo',
                'ease-in-out-circ' => 'Ease In Out Circ',
                'ease-in-out-back' => 'Ease In Out Back',
            ];
        }

        return $timings;
    }

    public static function getAnimationTimingsConditionsPro()
    {
        return [
            'pro-eibk',
            'pro-eicb',
            'pro-eicr',
            'pro-eiex',
            'pro-eio',
            'pro-eiobk',
            'pro-eiocb',
            'pro-eiocr',
            'pro-eioex',
            'pro-eioqd',
            'pro-eioqnt',
            'pro-eioqrt',
            'pro-eiosn',
            'pro-eiqd',
            'pro-eiqnt',
            'pro-eiqrt',
            'pro-eisn',
            'pro-eobk',
            'pro-eocb',
            'pro-eocr',
            'pro-eoex',
            'pro-eoqd',
            'pro-eoqnt',
            'pro-eoqrt',
            'pro-eosn',
        ];
    }

    public static function isBlogArchive()
    {
        return (
                is_home()
                && '0' === get_option('page_on_front')
                && '0' === get_option('page_for_posts')
            ) || (
                intval(get_option('page_for_posts')) === get_queried_object_id()
                && !is_404()
            );
    }

    public static function filterOembedResults($html)
    {
        preg_match('/src="([^"]+)"/', $html, $m);
        return $m[1] . '&auto_play=true';
    }

    public static function getWooCommerceTaxonomies()
    {
        $filtered = array_filter(get_object_taxonomies('product'), fn($t) => get_taxonomy($t)->show_ui);
        return array_combine($filtered, array_map(fn($t) => get_taxonomy($t)->label, $filtered));
    }

    public static function getCustomMetaKeysTaxonomies()
    {
        $data = [];
        $tax_types = Core::getCustomTypes('tax', false);

        foreach ($tax_types as $taxonomy_slug => $post_type_name) {
            $meta_keys = [];
            foreach (get_terms($taxonomy_slug) as $tax) {
                $keys = array_keys(get_term_meta($tax->term_id));
                $keys = array_filter($keys, fn($key) => '_' !== $key[0]);
                $meta_keys = array_merge($meta_keys, $keys);
            }
            $data[$taxonomy_slug] = array_unique($meta_keys);
        }


        $merged = call_user_func_array('array_merge', array_values($data));
        $merged_meta_keys = array_values(array_unique($merged));

        $options = array_combine($merged_meta_keys, $merged_meta_keys);

        return [$data, $options];
    }

    public static function getMailchimpLists()
    {
        $api_key = get_option('king_addons_mailchimp_api_key', '');
        $mailchimp_list = ['def' => esc_html__('Select List', 'king-addons')];

        if (!$api_key) {
            return $mailchimp_list;
        }

        $url = 'https://' . explode('-', $api_key)[1] . '.api.mailchimp.com/3.0/lists/';
        $response = wp_remote_get($url, [
            'headers' => ['Authorization' => 'Basic ' . base64_encode('user:' . $api_key)]
        ]);

        $body = json_decode(wp_remote_retrieve_body($response));
        if (!empty($body->lists)) {
            foreach ($body->lists as $list) {
                $mailchimp_list[$list->id] = $list->name . ' (' . $list->stats->member_count . ')';
            }
        }

        return $mailchimp_list;
    }

    public static function getMailchimpGroups()
    {
        $apiKey = get_option('king_addons_mailchimp_api_key');
        $domain = 'https://' . substr($apiKey, strpos($apiKey, '-') + 1) . '.api.mailchimp.com/3.0/';
        $authArgs = ['headers' => ['Authorization' => 'Basic ' . base64_encode('user:' . $apiKey)]];
        $groups = ['def' => 'Select Group'];
        $mailchimpIDs = Core::getMailchimpLists();

        foreach ($mailchimpIDs as $audience => $ignore) {
            if ($audience === 'def') {
                continue;
            }

            $cats = wp_remote_get("{$domain}lists/$audience/interest-categories", $authArgs);
            $cats = json_decode($cats['body'])->categories ?? [];

            foreach ($cats as $cat) {
                $interests = wp_remote_get("{$domain}lists/$audience/interest-categories/$cat->id/interests", $authArgs);
                $interests = json_decode($interests['body'])->interests ?? [];

                foreach ($interests as $int) {
                    $groups[$int->id] = $int->name;
                }
            }
        }

        return $groups;
    }

    public static function getShopURL($settings)
    {
        global $wp;
        $url = ('' === get_option('permalink_structure'))
            ? remove_query_arg(['page', 'paged'], add_query_arg($wp->query_string, '', home_url($wp->request)))
            : preg_replace('%/page/[0-9]+%', '', home_url(trailingslashit($wp->request)));
        $url = add_query_arg('kingaddonsfilters', '', $url);
        $single_params = [
            'min_price' => true,
            'max_price' => true,
            'orderby' => false,
            'psearch' => false,
            'filter_product_cat' => false,
            'filter_product_tag' => false,
            'filter_rating' => false,
        ];
        foreach ($single_params as $param => $needs_clean) {
            if (isset($_GET[$param])) {
                $value = wp_unslash($_GET[$param]);
                /** @noinspection PhpUndefinedFunctionInspection */
                $value = $needs_clean ? wc_clean($value) : $value;
                $url = add_query_arg($param, $value, $url);
            }
        }
        /** @noinspection DuplicatedCode */
        /** @noinspection PhpUndefinedFunctionInspection */
        if ($chosen_attrs = WC()->query->get_layered_nav_chosen_attributes()) {
            foreach ($chosen_attrs as $name => $data) {
                /** @noinspection PhpUndefinedFunctionInspection */
                $filter_name = wc_attribute_taxonomy_slug($name);
                if (!empty($data['terms'])) {
                    $url = add_query_arg('filter_' . $filter_name, implode(',', $data['terms']), $url);
                }
                if (!empty($settings)) {
                    if ('or' === $settings['tax_query_type'] || isset($_GET['query_type_' . $filter_name])) {
                        $url = add_query_arg('query_type_' . $filter_name, 'or', $url);
                    }
                }
            }
        }
        return $url;
    }

    public static function getClientIP()
    {
        $server_ip_keys = [
            'HTTP_CLIENT_IP',
            'HTTP_X_FORWARDED_FOR',
            'HTTP_X_FORWARDED',
            'HTTP_X_CLUSTER_CLIENT_IP',
            'HTTP_FORWARDED_FOR',
            'HTTP_FORWARDED',
            'REMOTE_ADDR',
        ];

        foreach ($server_ip_keys as $key) {
            if (isset($_SERVER[$key])) {
                $ip = wp_kses_post_deep(wp_unslash($_SERVER[$key]));
                if (filter_var($ip, FILTER_VALIDATE_IP)) {
                    return $ip;
                }
            }
        }

        return '127.0.0.1';
    }

    public static function getCustomMetaKeys()
    {
        // Get all custom post types (slug => name).
        $post_types = Core::getCustomTypes('post', false);

        // Build $data with each post type’s unique custom meta keys (excluding keys beginning with "_").
        $data = array_combine(
            array_keys($post_types),
            array_map(function ($slug) {
                $keys = [];
                foreach (get_posts(['post_type' => $slug, 'posts_per_page' => -1]) as $post) {
                    // get_post_custom_keys can return null, so cast to array:
                    foreach ((array)get_post_custom_keys($post->ID) as $meta_key) {
                        // Exclude protected keys (those beginning with "_").
                        if ($meta_key[0] !== '_') {
                            $keys[] = $meta_key;
                        }
                    }
                }
                return array_values(array_unique($keys));
            }, array_keys($post_types))
        );

        // Flatten all meta keys across all post types, remove duplicates, and reindex.
        $merged_meta_keys = array_values(array_unique(array_merge([], ...$data)));

        // Create an associative array where key == value (for convenient dropdowns, etc.).
        $options = array_combine($merged_meta_keys, $merged_meta_keys);

        // Return both the per-post-type data and the merged, deduplicated options.
        return [$data, $options];
    }

    public function enqueueLightboxDynamicStyles()
    {
        wp_register_style('king-addons-lightbox-dynamic-style', false);
        wp_enqueue_style('king-addons-lightbox-dynamic-style');

        $bg = esc_html(get_option('king_addons_lightbox_bg_color', 'rgba(0,0,0,0.6)'));
        $toolbar = esc_html(get_option('king_addons_lightbox_toolbar_color', 'rgba(0,0,0,0.8)'));
        $caption = esc_html(get_option('king_addons_lightbox_caption_color', 'rgba(0,0,0,0.8)'));
        $gallery = esc_html(get_option('king_addons_lightbox_gallery_color', '#444444'));
        $progress_bar = esc_html(get_option('king_addons_lightbox_pb_color', '#8a8a8a'));
        $ui_color = esc_html(get_option('king_addons_lightbox_ui_color', '#efefef'));
        $icon_size = floatval(get_option('king_addons_lightbox_icon_size', 20));
        $icon_size_big = $icon_size + 4;
        $ui_hover = esc_html(get_option('king_addons_lightbox_ui_hover_color', '#ffffff'));
        $text_color = esc_html(get_option('king_addons_lightbox_text_color', '#efefef'));
        $text_size = esc_html(get_option('king_addons_lightbox_text_size', 14));
        $arrow_size = esc_html(get_option('king_addons_lightbox_arrow_size', 35));

        $custom_css = "#lg-counter { color: $text_color !important; font-size: {$text_size}px !important; opacity: 0.9; } .lg-backdrop { background-color: $bg !important; } .lg-dropdown:after { border-bottom-color: $toolbar !important; } .lg-icon { color: $ui_color !important; font-size: {$icon_size}px !important; background-color: transparent !important; } .lg-icon.lg-toogle-thumb { font-size: {$icon_size_big}px !important; } .lg-icon:hover, .lg-dropdown-text:hover { color: $ui_hover !important; } .lg-prev, .lg-next { font-size: {$arrow_size}px !important; } .lg-progress { background-color: $progress_bar !important; } .lg-sub-html { background-color: $caption !important; } .lg-sub-html, .lg-dropdown-text { color: $text_color !important; font-size: {$text_size}px !important; } .lg-thumb-item { border-radius: 0 !important; border: none !important; opacity: 0.5; } .lg-thumb-item.active { opacity: 1; } .lg-thumb-outer, .lg-progress-bar { background-color: $gallery !important; } .lg-thumb-outer { padding: 0 10px; } .lg-toolbar, .lg-dropdown { background-color: $toolbar !important; }";

        wp_add_inline_style('king-addons-lightbox-dynamic-style', $custom_css);
    }

}

Core::instance();