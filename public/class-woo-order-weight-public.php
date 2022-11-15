<?php

/**
 * The file that defines public class
 *
 *
 * @link       http://wun.se
 * @since      0.6.0
 *
 * @package    Woo_Order_Weight
 * @subpackage Woo_Order_Weight/includes
 */

/**
 * The public-facing functionality of the plugin.
 *
 *
 * @package    Plugin_Name
 * @subpackage Plugin_Name/public
 * @author     Your Name <email@example.com>
 */
class Woo_Order_Weight_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Add weight column to "My Orders" under "My Account" for logged-in users
	 *
	 * @since    0.6
	 */
	public function add_my_account_my_orders_weight_column( $columns ) {

    $new_columns = [];

    foreach ( $columns as $key => $name ) {
        $new_columns[ $key ] = $name;

        if ( 'order-status' === $key ) {
            $new_columns['order-weight'] = __( 'Weight', 'woocommerce' );
        }
    }
    return $new_columns;

	}

  /**
	* Add weight column content to "My Orders" under "My Account" for logged-in users
	*
	* @since    0.6
	*/
	public function add_my_account_my_orders_weight_column_content( $order ) {
    $weight = get_post_meta( $order->get_id(), 'order_weight', true );
    if ( $weight > 0 ) {
      print esc_html( $weight . ' ' . esc_attr( get_option( 'woocommerce_weight_unit' ) ) );
    } else {
      print '<span aria-hidden="true">&#151;</span>';
    }
	}

}
