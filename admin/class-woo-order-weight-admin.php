<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://wun.se
 * @since      0.1.0
 *
 * @package    Woocommerce_Order_Weight
 * @subpackage Woocommerce_Order_Weight/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 *
 * @package    Woocommerce_Order_Weight
 * @subpackage Woocommerce_Order_Weight/admin
 * @author     andreaskarman <andreas.karman@weupnorth.se>
 */
class Woo_Order_Weight_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    0.1.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    0.1.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	private $meta_key;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    0.1.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    		The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->meta_key = 'order_weight';

	}

	/**
	 * @since     0.1.0
	 * @return    string    The order weight meta-key
	 */

	private function woo_get_weight_meta_key() {
		return $this->meta_key;
	}

	/**
	 * @since     0.1.0
	 * @return    string    The WooCommerce weight unit
	 */

	private function woo_get_woocommerce_weight_unit() {
		return esc_attr( get_option('woocommerce_weight_unit'));
	}

	/**
	 * Protecting meta-keys used by plugin
	 *
	 * @since    0.3.5
	 */

	public function woo_protecting_meta_keys( $protected, $meta_key ) {
		if ( 'order_weight' == $meta_key ) return true;
		if ( 'order_weight_unit' == $meta_key ) return true;
			return $protected;
	}

	/**
	 * When order is created, save the order weight using order metadata
	 *
	 * @since    0.1.0
	 */

	public function woo_add_order_weight( $order_id, $posted ) {
		global $woocommerce;
		$cart_weight = $woocommerce->cart->cart_contents_weight;
    	update_post_meta( $order_id, $this->woo_get_weight_meta_key(), $cart_weight );
	}

	/**
	 * Add order weight column to order management
	 *
	 * @since    0.1.0
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
	 * @since    0.1.0
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
	 * @since    0.1.0
	 */

	public function woo_make_weight_column_sortable( $columns ) {

    	$columns['order_weight'] = 'order_weight';
    	return $columns;

	}

	/**
	 * Make sure that order weight column sorts by correct metakey and metavalue
	 *
	 * @since    0.1.0
	 */

	public function woo_sortable_by_weight_query( $vars ) {
    	if ( isset( $vars['orderby'] ) && 'order_weight' == $vars['orderby'] ) {
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
	 * @since    0.1.0
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
	 * Add product weight column to product management
	 *
	 * @since    0.3.0
	 */

	public function woo_add_product_column_weight($columns) {

		$offset = 8;
		$updated_columns = array_slice( $columns, 0, $offset, true) +
		array( 'product_weight' => esc_html__( 'Weight', 'woocommerce' ) ) +
		array_slice($columns, $offset, NULL, true);
		return $updated_columns;

	}

	/**
	 * Populate product weight column with product weight
	 *
	 * @since    0.3.0
	 */

	public function woo_populate_product_weight_column( $column ) {

		global $post;
		if ( $column == 'product_weight' ) {
			global $product;
			if ( $product->has_weight() ) {
				echo $product->get_weight() . ' ' . $this->woo_get_woocommerce_weight_unit();
			}
			else print '<span aria-hidden="true">&#151;</span>';
		}
	}

	/**
	 * Make the added product weight column sortable in products view
	 *
	 * @since    0.3.0
	 */

	public function woo_make_product_weight_column_sortable( $columns ) {

    	$columns['product_weight'] = 'product_weight';
    	return $columns;

	}

	/**
	 * Make sure that product weight column sorts by correct metakey and metavalue
	 *
	 * @since    0.3.0
	 */

	public function woo_sortable_by_product_weight_query( $vars ) {
		if ( isset( $vars['post_type'] ) && 'product' == $vars['post_type'] ) {
			if ( isset( $vars['orderby'] ) && 'product_weight' == $vars['orderby'] ) {
				$vars = array_merge(
					$vars,
						array(
							'meta_key' => '_weight',
							'orderby' => 'meta_value_num'
							)
						);
					}
				}
		return $vars;
	}

	/**
	 * Adding weight and weight unit to WooCommerce Order API response
	 *
	 * @since    0.3.5
	 */

	public function woo_api_order_response( $order_data, $order ) {
    	$order_data['weight'] = get_post_meta($order->id, 'order_weight', true);
    	$order_data['weight_unit'] = $this->woo_get_woocommerce_weight_unit();
    	return $order_data;
  	}

  	/**
	 * Creating orders in WooCommerce Orders API with weight
	 *
	 * @since    0.3.5
	 */

  	public function woo_api_create_order($order_id, $data) {

    	    $this->data = $data;
    		$has_weight = isset( $data['weight'] ) ? $data['weight'] : 0 ;

    		if($has_weight){
    			$weight = wc_format_decimal($this->data['weight']);
    			update_post_meta( $order_id, 'order_weight', $weight );
    			update_post_meta( $order_id, 'order_weight_unit', $this->woo_get_woocommerce_weight_unit() );
    		}

    		return $this->data;
  	}

  	/**
	 * Editing of orders in WooCommerce Orders API with weight
	 *
	 * @since    0.3.5
	 */

  	public function woo_api_edit_order_data($order_id, $data) {
 		 return $this->woo_api_create_order($order_id, $data);
  	}

  }