<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://wun.se
 * @since      1.0.0
 *
 * @package    Woocommerce_Order_Weight
 * @subpackage Woocommerce_Order_Weight/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Woocommerce_Order_Weight
 * @subpackage Woocommerce_Order_Weight/admin
 * @author     WUN <andreas.karman@weupnorth.se>
 */
class Woocommerce_Order_Weight_Admin {

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

	private $meta_key;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->meta_key = '_order_weight';

	}

	/**
	 * @since     1.0.0
	 * @return    string    The order weight meta-key
	 */

	private function woo_get_weight_meta_key() {
		return $this->meta_key;
	}

	/**
	 * @since     1.0.0
	 * @return    string    The WooCommerce weight unit
	 */

	private function woo_get_woocommerce_weight_unit() {
		return esc_attr( get_option('woocommerce_weight_unit'));
	}

	/**
	 * When order is created, save the order weight using order metadata
	 *
	 * @since    1.0.0
	 */

	public function woo_add_order_weight( $order_id, $posted ) {
		global $woocommerce;
		$cart_weight = $woocommerce->cart->cart_contents_weight;
    	update_post_meta( $order_id, $this->woo_get_weight_meta_key(), $cart_weight );
	}

	/**
	 * Add order weight column to order management
	 *
	 * @since    1.0.0
	 */

	public function woo_add_column_weight($columns) {

		$offset = 8;
		$updated_columns = array_slice( $columns, 0, $offset, true) +
		array( 'order_weight' => esc_html__( 'Weight', 'woocommerce' ) ) +
		array_slice($columns, $offset, NULL, true);
		return $updated_columns;

	}

	/**
	 * Populate order weight column with order weight metadata
	 *
	 * @since    1.0.0
	 */

	public function woo_populate_weight_column( $column ) {

		global $post;
 
		if ( $column == 'order_weight' ) {
			$weight = get_post_meta( $post->ID, $this->woo_get_weight_meta_key(), true );
			if ( $weight > 0 )
				print $weight . ' ' . $this->woo_get_woocommerce_weight_unit();
			else print '<span aria-hidden="true">&#151;</span>';
			}
	}

	/**
	 * Make the added order weight column sortable in order management
	 *
	 * @since    1.0.0
	 */

	public function woo_make_weight_column_sortable( $columns ) {

    	$columns['order_weight'] = 'order_weight';
    	return $columns;

	}

	/**
	 * Make sure that order weight column sorts by correct metakey and metavalue
	 *
	 * @since    1.0.0
	 */

	public function woo_sortable_by_weight_query( $vars ) {
    	if ( isset( $vars['orderby'] ) && 'price' == $vars['orderby'] ) {
        	$vars = array_merge( $vars, array(
            	'meta_key' => $this->woo_get_weight_meta_key(),
            	'orderby' => 'meta_value_num'
        	) );
    	}
 		return $vars;
 	}

 	/**
	 * Add order weight to single order view
	 *
	 * @since    1.0.0
	 */

 	public function woo_add_weight_to_single_order( $order ) { ?>
    	<div class="order_data_column">
        <?php 
        	echo '<p>';
        	echo '<strong>' . esc_html__( 'Weight', 'woocommerce' ) . ':</strong><br>';
            print get_post_meta( $order->id, $this->woo_get_weight_meta_key(), true ) . ' ' . $this->woo_get_woocommerce_weight_unit();
            echo '</p>';
         ?>
    	</div>
 	<?php 
 	}

 	 /**
	 * Add order weight to New order emails
	 *
	 * @since    1.0.0
	 */
 
	public function woo_add_weight_to_order_email( $keys ) {

		$keys['Weight'] = $this->woo_get_weight_meta_key();
		return $keys;

	}

}
