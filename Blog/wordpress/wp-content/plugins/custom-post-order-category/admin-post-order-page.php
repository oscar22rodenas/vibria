<?php
    if (!defined('ABSPATH')) {
        exit;
    }

    $allowed_tags = [
        'select' => ['name' => true, 'id' => true, 'class' => true],
        'option' => ['value' => true, 'selected' => true],
    ];

    $order_result = $order_data['order_result'];
    $order_result_incl = $order_data['order_result_incl'];
    $temp_order = $order_data['posts'];
?>

<div class="wrap">
	<h2><?php esc_html_e('Post order by category or taxonomy', 'custom-category-post-order'); ?></h2>
    <div class="notice notice-info" style="padding: 15px; margin: 20px 0;">
        <p>
            <strong><?php esc_html_e('⭐ Help us promote this plugin!'); ?></strong><br>
            <?php esc_html_e('If you like "Custom Category Post Order", please consider giving us a 5-star rating.'); ?>
            <a href="https://wordpress.org/support/plugin/custom-post-order-category/reviews/" target="_blank" class="button button-secondary" style="margin-left: 10px;">
                <?php esc_html_e('Rate Plugin'); ?>
            </a>
        </p>
        <hr>
        <p>
            <strong><?php esc_html_e('🚀 Unlock Pro Features'); ?></strong><br>
            <?php esc_html_e('Enable homepage sorting and advanced sort options with the Pro version.'); ?>
            <a href="https://scriptut.com/wordpress/advanced-custom-category-post-type-post-order/" target="_blank" class="button button-primary" style="margin-left: 10px;">
                <?php esc_html_e('Download Pro'); ?>
            </a>
        </p>
    </div>

	<form method="post">
		<?php wp_nonce_field('update-options'); ?>
        
		<table cellspacing="0" cellpadding="10" style="background: #f8f9fa; width: 100%; border: 1px solid #ccc; border-radius: 8px; font-family: Arial, sans-serif;">
            <tr valign="top">
                          
                <td style="padding: 10px;">
                    <strong style="display: block; margin-bottom: 5px;"><?php esc_html_e('Select Post Type', 'custom-category-post-order'); ?></strong>
                    <?php echo wp_kses('<select name="post_type" id="post_type" style="width: 100%; padding: 6px; border-radius: 4px; border: 1px solid #ccc;">' . implode("", $post_types_options) . '</select>', $allowed_tags); ?>
                </td>

                <td style="padding: 10px;">
                    <strong style="display: block; margin-bottom: 5px;"><?php esc_html_e('Select category/taxonomy', 'custom-category-post-order'); ?></strong>
                    <?php echo wp_kses('<select name="taxonomy" id="taxonomy" style="width: 100%; padding: 6px; border-radius: 4px; border: 1px solid #ccc;"><option value="">Select Taxonomy</option></select>', $allowed_tags); ?>
                </td>

                <td style="padding: 10px;">
                    <strong style="display: block; margin-bottom: 5px;"><?php esc_html_e('Select term:', 'custom-category-post-order'); ?></strong>
                    <?php echo wp_kses('<select name="term" id="term" style="width: 100%; padding: 6px; border-radius: 4px; border: 1px solid #ccc;"><option value="">Select Term</option></select>', $allowed_tags); ?>
                </td>
                <td style="padding: 10px;">
                    <strong style="display: block; margin-bottom: 5px;"><?php esc_html_e('Enable ordering:', 'custom-category-post-order'); ?></strong>
                    <label>
                        <input type="checkbox" name="category_ordering" rel="<?php echo esc_attr($term); ?>" id="user_ordering_category" value="1" <?php echo esc_attr($checked); ?> />
                        <?php esc_html_e(   'Enable', 'custom-category-post-order'); ?>
                    </label>
                </td>      
                <td style="padding: 10px; text-align: right;">
                    <input type="button" class="button button-primary" value="<?php esc_attr_e('Load Posts', 'custom-category-post-order'); ?>" id="load_posts_btn" />
                </td>
                
                <td style="padding: 10px; text-align: right;">  
                    <?php include plugin_dir_path(__FILE__) . 'option-popup.php'; ?>
                </td>
                
            </tr>
        </table>

		<div id="sortablewrapper">
            <div id="ccpo-post-list"></div>
		</div>
		<input type="hidden" name="action" value="update" />
	</form>
</div>
