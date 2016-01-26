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
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    0.2.0
	 */
	public static function activate() {
		// Check if WooCommerce is activated
			if ( !in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
				
				// Deactivate the plugin
				deactivate_plugins(__FILE__);
				
				// Throw an error in the wordpress admin console
				$error_message = __('This plugin requires <a href="http://wordpress.org/extend/plugins/woocommerce/">WooCommerce</a>.', 'woocommerce');
				die($error_message);
				
			}
	}

}
