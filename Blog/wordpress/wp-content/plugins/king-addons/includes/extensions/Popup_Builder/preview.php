<?php

namespace King_Addons;

use Elementor\Plugin;

if (!defined('ABSPATH')) {
    exit;
}

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover"/>
    <?php if (!current_theme_supports('title-tag')) : ?>
        <title><?php echo esc_html(wp_get_document_title()); ?></title>
    <?php endif; ?>
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<div class="king-addons-pb-template-popup">
    <div class="king-addons-pb-template-popup-inner">
        <div class="king-addons-pb-popup-overlay"></div>
        <div class="king-addons-pb-popup-container">
            <div class="king-addons-pb-popup-close-btn"><i class="eicon-close"></i></div>
            <div class="king-addons-pb-popup-container-inner">
                <?php (Plugin::$instance)->modules_manager->get_modules('page-templates')->print_content(); ?>
            </div>
        </div>
    </div>
</div>
<?php wp_footer(); ?>
</body>
</html>
