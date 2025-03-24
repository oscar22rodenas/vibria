<?php

/** @noinspection SpellCheckingInspection */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- This page can only be accessed by admins, so nonce verification is not required. Check Admin.php, showAdminPage function.
if (isset($_GET['settings-updated'])) {
    add_settings_error('king_addons_messages', 'king_addons_message', esc_html__('Settings Saved', 'king-addons'), 'updated');
}

// show error/update messages
settings_errors('king_addons_messages');

$options = get_option('king_addons_options');
?>
<style>
    #wpcontent {
        min-height: 100vh;
        overflow-x: hidden;
    }

    html, #wpcontent {
        background: #101112;
    }

    .king-addons-admin {
        display: none;
        max-width: 1660px;
        margin: 10px 20px 0 0;
        padding: 20px;
    }
</style>
<div class="king-addons-admin">
    <?php
    $promo_enabled = false;
    if (!king_addons_freemius()->can_use_premium_code__premium_only()):
        if ($promo_enabled):
            ?>
            <div class="kng-promo">
                <div class="kng-promo-wrap">
                    <div class="kng-promo-wrap-icon">
                        <img width="50px"
                             src="<?php echo esc_url(KING_ADDONS_URL) . 'includes/admin/img/unlock-v2.svg'; ?>"
                             alt="<?php echo esc_html__('Unlock Icon', 'king-addons'); ?>">
                    </div>
                    <div class="kng-promo-wrap-1">
                        <h1 class="kng-promo-title"><?php echo esc_html__('Unlock Premium Features &amp; 600+ Templates Today!', 'king-addons'); ?></h1>
                        <h2 class="kng-promo-subtitle">Upgrade to Premium and take your website design to the next
                            level.
                            Get advanced tools like Live Search, Popup Builder,
                            Pricing Table, Timeline, and more. All for just $2/month!
                        </h2>
                    </div>
                    <div class="kng-promo-wrap-2">
                        <div class="kng-promo-navigation">
                            <div class="kng-promo-btn-wrap">
                                <a href="https://kingaddons.com/pricing/?rel=king-addons-dashboard" target="_blank">
                                    <img width="16px"
                                         src="<?php echo esc_url(KING_ADDONS_URL) . 'includes/admin/img/share.svg'; ?>"
                                         alt="<?php echo esc_html__('Open link in the new tab', 'king-addons'); ?>">
                                    <div class="kng-promo-btn-txt"><?php echo esc_html__('Learn More', 'king-addons'); ?></div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="king-addons-special-animation">
                <div class="king-addons-special-animation-container">
                    <div class="king-addons-special-animation-colors">
                        <div class="king-addons-special-animation-yellow king-addons-special-animation-color"></div>
                        <div class="king-addons-special-animation-pink-one king-addons-special-animation-color"></div>
                        <div class="king-addons-special-animation-pink-two king-addons-special-animation-color"></div>
                        <div class="king-addons-special-animation-blue king-addons-special-animation-color"></div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    <?php endif; ?>
    <div class="kng-intro">
        <div class="kng-intro-wrap">
            <div class="kng-intro-wrap-1">
                <h1 class="kng-intro-title"><?php echo esc_html(get_admin_page_title()); ?></h1>
                <h2 class="kng-intro-subtitle"><?php echo esc_html__('Free, lightweight, super-fast Elementor addons that do not affect website performance', 'king-addons'); ?></h2>
            </div>
            <div class="kng-intro-wrap-2">
                <div class="kng-navigation">
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
                    <?php if (KING_ADDONS_EXT_TEMPLATES_CATALOG): ?>
                        <div class="kng-nav-item kng-nav-item-current-active">
                            <a href="<?php echo admin_url('admin.php?page=king-addons-templates'); ?>">
                                <img src="<?php echo esc_url(KING_ADDONS_URL) . 'includes/admin/img/icon-for-templates.svg'; ?>"
                                     alt="<?php echo (!king_addons_freemius()->can_use_premium_code() ?  esc_html__('Free Templates', 'king-addons') :  esc_html__('Templates Pro', 'king-addons')); ?>">
                                <div class="kng-nav-item-txt"><?php echo (!king_addons_freemius()->can_use_premium_code() ?  esc_html__('Free Templates', 'king-addons') :  esc_html__('Templates Pro', 'king-addons')); ?></div>
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <?php
    if (king_addons_freemius()->can_use_premium_code__premium_only()):
        ?>
        <div class="king-addons-special-animation">
            <div class="king-addons-special-animation-container">
                <div class="king-addons-special-animation-colors">
                    <div class="king-addons-special-animation-yellow king-addons-special-animation-color"></div>
                    <div class="king-addons-special-animation-pink-one king-addons-special-animation-color"></div>
                    <div class="king-addons-special-animation-pink-two king-addons-special-animation-color"></div>
                    <div class="king-addons-special-animation-blue king-addons-special-animation-color"></div>
                </div>
            </div>
        </div>
    <?php
    endif;
    ?>
    <!--suppress HtmlUnknownTarget -->
    <form action="options.php" method="post">
        <?php

        // output security fields for the registered setting "king_addons"
        settings_fields('king_addons');

        // output setting sections and their fields
        // (sections are registered for "king-addons", each field is registered to a specific section)

        $page = 'king-addons';

        global $wp_settings_sections, $wp_settings_fields;

        $first_item = true;

        foreach ((array)$wp_settings_sections[$page] as $section) {

            $section_current = $section['id'];

            if (!isset($wp_settings_fields[$page][$section_current])) {
                continue;
            }

            if ($section['callback']) {
                call_user_func($section['callback'], $section);
            }

            echo '<table class="kng-form-table"><tbody class="kng-tbody">';

            foreach ((array)$wp_settings_fields[$page][$section_current] as $field) {

                $args = $field['args'];
                $class = '';

                if (!empty($field['args']['class'])) {
                    $class = $field['args']['class'];
                }
                ?>
                <tr class="<?php echo esc_attr($class); ?>">
                    <td>
                        <div class="kng-td-wrap-before-1"></div>
                        <div class="kng-td-wrap-before-2"></div>
                        <div class="kng-td-wrap">
                            <div class="kng-td">
                                <div class="kng-td-icon">
                                    <img alt="<?php echo esc_attr($args['label_for']); ?>"
                                         src="<?php echo esc_attr(KING_ADDONS_URL) . 'includes/admin/img/' . esc_attr($args['label_for']); ?>.svg?v=<?php echo esc_attr(KING_ADDONS_VERSION); ?>"
                                         class="kng-item-icon"
                                         width="80px"/>
                                </div>
                                <div class="kng-td-content">
                                    <h3><?php echo esc_attr($field['title']); ?></h3>
                                    <p class="kng-td-description">
                                        <?php echo esc_attr($args['description']); ?>
                                    </p>
                                    <div class="kng-settings-wrap">
                                        <div class="kng-td-link-wrap">
                                            <?php
                                            $demo_link = $args['demo_link'];
                                            if (!empty($demo_link)) {
                                                echo '<a class="kng-td-link" href="' . esc_url($demo_link) . '?utm_source=kng-dashboard&utm_medium=plugin&utm_campaign=kng' . '" target="_blank"><div class="kng-td-link-label-wrap"><div class="kng-td-link-label">' . esc_html__('View Demo', 'king-addons') . '</div></div></a>';
                                            }
                                            ?>
                                            <?php
                                            if ($first_item) : ?>
                                                <div class="kng-settings-switch-notice-2">
                                                    &lt;- check how it looks and its features
                                                </div>
                                            <?php
                                            endif;
                                            ?>
                                        </div>
                                        <div class="kng-settings-switch-box-wrap">
                                            <div class="kng-settings-switch-box">
                                                <input type="hidden"
                                                       name="king_addons_options[<?php echo esc_attr($args['label_for']); ?>]"
                                                       value="disabled"/>
                                                <input type="checkbox"
                                                       class="kng-settings-switch"
                                                       id="<?php echo esc_attr($args['label_for']); ?>"
                                                       name="king_addons_options[<?php echo esc_attr($args['label_for']); ?>]"
                                                       value="enabled"
                                                    <?php checked(isset($options[$args['label_for']]) && $options[$args['label_for']] === 'enabled'); ?>
                                                />
                                                <label for="<?php echo esc_attr($args['label_for']); ?>"
                                                       class="kng-settings-switch-label"></label>
                                            </div>

                                            <?php
                                            if ($first_item) : ?>
                                                <div class="kng-settings-switch-notice">
                                                    &lt;- enable/disable this module
                                                </div>
                                                <?php
                                                $first_item = false;
                                            endif;
                                            ?>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                <?php

            }

            echo '</tbody></table>';

        }
        ?>
        <div class="kng-btn-submit-wrap">
            <button type="submit" name="submit" id="submit" class="kng-btn-submit"
                    value="submit"><?php echo esc_html__('SAVE SETTINGS', 'king-addons'); ?></button>
        </div>
    </form>
</div>