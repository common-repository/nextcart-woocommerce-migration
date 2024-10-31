<?php

/** 
 * Plugin Name: Next-Cart Store to WooCommerce Migration
 * Description: Migrate products, categories, customers, orders, reviews, blog posts, and other data to your WooCommerce store.
 * Version: 3.9.1
 * Author: Next-Cart
 * Author URI: https://next-cart.com
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * 
 * WC requires at least: 3.0
 * WC tested up to: 9.3.1
 */

if ( !defined('ABSPATH') || !function_exists('add_action')) {
    exit; // Exit if accessed directly or error.
}

if (!defined('NCWM_PLUGIN_FILE')) {
    define('NCWM_PLUGIN_FILE', __FILE__);
}

if (!defined('NCWM_PLUGIN_DIR')) {
    define('NCWM_PLUGIN_DIR', __DIR__);
}
if (!defined('NCWM_PLUGIN_URL')) {
    define('NCWM_PLUGIN_URL', plugins_url() . '/' . trim(dirname(plugin_basename(__FILE__)), '/'));
}

if (!defined('NCWM_PLUGIN_VERSION')) {
    define('NCWM_PLUGIN_VERSION', '3.9.1');
}


add_action('before_woocommerce_init', function () {
    if (class_exists(\Automattic\WooCommerce\Utilities\FeaturesUtil::class)) {
        \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility('custom_order_tables', __FILE__, true);
    }
});

include_once NCWM_PLUGIN_DIR . '/includes/main.php';