<?php

/**
 * Fired during plugin activation
 *
 * @link       http://wun.se
 * @since      0.1.0
 *
 * @package    Woo_Order_Weight
 * @subpackage Woo_Order_Weight/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      0.1.0
 * @package    Woo_Order_Weight
 * @subpackage Woo_Order_Weight/includes
 * @author     WUN <andreas.karman@weupnorth.se>
 */
class Woo_Order_Weight_Activator {

	/**
	 * Checking if WooCommerce is activated
	 *
	 *
	 * @since    0.3.5
	 */

	public static function woocommerce_check() {
		// Check if WooCommerce is activated
			if ( !in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
				
				// Deactivate the plugin
				deactivate_plugins(__FILE__);
				
				// Throw an error in the wordpress admin console
				$error_message = __('This plugin requires <a href="http://wordpress.org/extend/plugins/woocommerce/">WooCommerce</a>.', 'woocommerce');
				die($error_message);
			}
	}

	/**
	 * Clean-up order meta data from versions before 0.3.5
	 *
	 *
	 * @since    0.3.5
	 */

	public static function meta_cleanup() {

		$args = array(
			'posts_per_page'   => '-1',
			'meta_key'         => '_order_weight',
			'post_type'       => 'shop_order',
			'post_status' => array_keys( wc_get_order_statuses() )
		);

	    $posts = get_posts($args);

	    foreach ($posts as $post ) {

	        $current_weight = get_post_meta($post->ID, '_order_weight', true);
	        update_post_meta($post->ID, 'order_weight', $current_weight);
	        update_post_meta($post->ID, 'order_weight_unit', get_option('woocommerce_weight_unit'));
	        delete_post_meta( $post->ID, '_order_weight' );

	    }

	   }

	}
