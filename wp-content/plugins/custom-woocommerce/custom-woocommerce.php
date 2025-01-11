<?php
/*
Plugin Name: Custom WooCommerce
Description: Override admin templates for WooCommerce.
Version: 1.0
Author: Son Le
*/

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

// Hook để ghi đè file template admin WooCommerce.
add_filter('woocommerce_locate_template', 'custom_admin_override_template', 10, 3);

function custom_admin_override_template($template, $template_name, $template_path) {

    if ($template_name === 'admin/meta-boxes/views/html-order-item.php') {
        $custom_template = get_template_directory() . '/woocommerce/admin/html-order-item.php';

        if (file_exists($custom_template)) {
            return $custom_template;
        }
    }

    return $template;
}
