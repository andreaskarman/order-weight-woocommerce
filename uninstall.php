<?php

/**
 * Fired when the plugin is uninstalled.
 *
 * @link       http://wun.se
 * @since      0.1.0
 *
 * @package    Woo_Order_Weight
 */

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

// Remove old metadata
$old_order_weight_meta_key = '_order_weight';
delete_post_meta_by_key($old_order_weight_meta_key);

// Remove new metadata (since 0.3.5)
$order_weight_meta_key = 'order_weight';
delete_post_meta_by_key($order_weight_meta_key);

$order_weight_unit_meta_key = 'order_weight_unit';
delete_post_meta_by_key($order_weight_unit_meta_key);
