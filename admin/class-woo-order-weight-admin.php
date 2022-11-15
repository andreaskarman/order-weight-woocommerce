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
	 * @param   string    $plugin_name     The name of this plugin.
	 * @param   string    $version   The version of this plugin.
	 */

	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;
		$this->meta_key    = 'order_weight';

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
		return esc_attr( get_option( 'woocommerce_weight_unit' ) );
	}

	/**
	 * Protecting meta-keys used by plugin
	 *
	 * @since    0.3.5
	 */

	public function woo_protecting_meta_keys( $protected, $meta_key ) {
		if ( 'order_weight' === $meta_key ) {
			return true;
		};
		if ( 'order_weight_unit' === $meta_key ) {
			return true;
		}
		return $protected;
	}

	/**
	 * Save order weight when updating order in WordPress admin
	 *
	 * @since    0.4.5
	 */

	public function woo_update_order_weight($post_id){
		global $post, $woocommerce, $the_order;
		$the_order = new WC_Order( $post_id );
		$weight = null;

		foreach( $the_order->get_items() as $item ) {
			if ( $item['product_id'] > 0 ) {
				$_product = $item->get_product();
				if ( ! $_product->is_virtual() ) {
					$weight += (float)$_product->get_weight() * (float)$item['qty'];
				}
			}
		}
		$existing_weight = get_post_meta($post_id, $this->woo_get_weight_meta_key(), true);

		if($weight != $existing_weight) {
			$weight_unit = $this->woo_get_woocommerce_weight_unit();
			$note = sprintf(
				__( 'Order weight updated to %s %s.', 'woo-order-weight' ),
				$weight,
				$weight_unit
			);
			$the_order->add_order_note($note, false, false);
			update_post_meta( $post_id, $this->woo_get_weight_meta_key(), $weight );
		}

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

	public function woo_add_column_weight( $columns ) {
		$offset          = 6;
		$updated_columns = array_slice( $columns, 0, $offset, true ) +
		array( 'order_weight' => esc_html__( 'Weight', 'woocommerce' ) ) +
		array_slice( $columns, $offset, null, true );
		return $updated_columns;
	}

	/**
	 * Populate order weight column with order weight metadata
	 *
	 * @since    0.1.0
	 */

	public function woo_populate_weight_column( $column ) {
		global $post;
		if ( 'order_weight' === $column ) {
			$weight = get_post_meta( $post->ID, $this->woo_get_weight_meta_key(), true );
			if ( $weight > 0 ) {
				print esc_html( $weight . ' ' . $this->woo_get_woocommerce_weight_unit() );
			} else {
				print '<span aria-hidden="true">&#151;</span>';
			}
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
		if ( isset( $vars['orderby'] ) && 'order_weight' === $vars['orderby'] ) {
			$vars = array_merge(
				$vars,
				array(
					'meta_key' => $this->woo_get_weight_meta_key(),
					'orderby'  => 'meta_value_num',
				)
			);
		}
		return $vars;
	}

	/**
	 * Add order weight to single order view
	 *
	 * @since    0.1.0
	 */

	public function woo_add_weight_to_single_order( $order ) {
		echo '<div class="order_data_column">';
			echo '<p>';
			echo '<strong>' . esc_html__( 'Weight', 'woocommerce' ) . ':</strong><br>';
			print esc_attr( get_post_meta( $order->get_id(), $this->woo_get_weight_meta_key(), true ) . ' ' . $this->woo_get_woocommerce_weight_unit() );
			echo '</p>';
			echo '</div>';
	}

	/**
	 * Add product weight column to product management
	 *
	 * @since    0.3.0
	 */

	public function woo_add_product_column_weight( $columns ) {
		$offset          = 8;
		$updated_columns = array_slice( $columns, 0, $offset, true ) +
		array( 'product_weight' => esc_html__( 'Weight', 'woocommerce' ) ) +
		array_slice( $columns, $offset, null, true );
		return $updated_columns;
	}

	/**
	 * Populate product weight column with product weight
	 *
	 * @since    0.3.0
	 */

	public function woo_populate_product_weight_column( $column ) {

		global $post;
		if ( 'product_weight' === $column ) {
			global $product;
			if ( $product->has_weight() ) {
				echo esc_attr( $product->get_weight() . ' ' . $this->woo_get_woocommerce_weight_unit() );
			} else {
				print '<span aria-hidden="true">&#151;</span>';
			}
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
		if ( isset( $vars['post_type'] ) && 'product' === $vars['post_type'] ) {
			if ( isset( $vars['orderby'] ) && 'product_weight' === $vars['orderby'] ) {
				$vars = array_merge(
					$vars,
					array(
						'meta_key' => '_weight',
						'orderby'  => 'meta_value_num',
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

	public function woo_api_order_response( $response, $post ) {
		$response->data['weight']      = get_post_meta( $post->get_id(), 'order_weight', true );
		$response->data['weight_unit'] = $this->woo_get_woocommerce_weight_unit();
		return $response;
	}

	/**
	 * Creating orders in WooCommerce Orders API with weight
	 *
	 * @since    0.3.5
	 */

	public function woo_api_create_order( $order_id, $data ) {

		$this->data = $data;
		$has_weight = isset( $data['weight'] ) ? $data['weight'] : 0;

		if ( $has_weight ) {
			$weight = wc_format_decimal( $this->data['weight'] );
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

	public function woo_api_edit_order_data( $order_id, $data ) {
		return $this->woo_api_create_order( $order_id, $data );
	 }

		/**
		 * Add settings section for plugin
		 *
		 * @since    0.6.0
		 */

 	public function woo_add_settings_section( $sections ) {
 			$sections['orderweight'] = __( 'Order Weight for WooCommerce', 'woocommerce' );
 			return $sections;
 	}


	/**
	 * Add settings for plugin
	 *
	 * @since    0.6.0
	 */

	public function woo_add_settings( $settings, $current_section ) {

		if ( $current_section == 'orderweight' ) {
			$settings_slider = array();
			$settings_slider[] = array( 'name' => __( 'Settings', 'woocommerce' ), 'type' => 'title', 'desc' => __( 'The following settings are used to configure Order Weight for WooCommerce.', 'woocommerce' ), 'id' => 'orderweight' );
			$settings_slider[] = array(
				'name'     => __( 'Order Weight in My Account', 'woocommerce' ),
				//'desc_tip' => __( 'This will automatically display order weights in the customer dashboard.', 'text-domain' ),
				'id'       => 'orderweight_customer_dashboard',
				'type'     => 'checkbox',
				'css'      => 'min-width:300px;',
				'desc'     => __( 'Display the weight of each order in the customer dashboard.', 'woocommerce' ),
			);

			$settings_slider[] = array( 'type' => 'sectionend', 'id' => 'orderweight');

			return $settings_slider;

		} else {
			return $settings;
		}
	}

	/**
	 * Add settings link in "Plugins" list
	 *
	 * @since    0.6.1
	 */

	public function woo_plugin_settings_link( $links ) {
		$url = esc_url( add_query_arg(
			'page',
			'wc-settings&tab=advanced&section=orderweight',
			get_admin_url() . 'admin.php'
		) );
		$settings_link = "<a href='$url'>" . __( 'Settings' ) . '</a>';
		array_unshift(
			$links,
			$settings_link
		);
		return $links;
	}

	/**
	 * Add custom bulk action to action dropdown
	 *
	 * @since    0.7
	 */

	 public function woo_add_custom_bulk_action( $bulk_array ) {

	 	$bulk_array[ 'woo_update_order_weight' ] = 'Update order weight';
	 	return $bulk_array;

	 }

	 /**
 	 * Processing the custom bulk action
 	 *
 	 * @since    0.7
 	 */

 	 public function woo_process_custom_bulk_action( $redirect, $doaction, $object_ids ) {

			$redirect = remove_query_arg(
				array( 'woo_update_order_weight' ),
				$redirect
			);

			if ( 'woo_update_order_weight' === $doaction ) {

				foreach ( $object_ids as $post_id ) {
					$this->woo_update_order_weight($post_id);
				}

				$redirect = add_query_arg(
					'woo_update_order_weight',
					count( $object_ids ),
					$redirect
				);

			}

			return $redirect;

 	 }

	 /**
		 * Add custom bulk action confirmation message
		 *
		 * @since    0.7
		 */

		 public function woo_display_custom_bulk_action_message() {

 			if( ! empty( $_REQUEST[ 'woo_update_order_weight' ] ) ) {

 				$count = (int) $_REQUEST[ 'woo_update_order_weight' ];
 				$message = sprintf(
 					_n(
 						'The weight of %d order has been updated.',
 						'The weight of %d orders has been updated.',
 						$count
 					),
 					$count
 				);

 				echo "<div class=\"updated notice is-dismissible\"><p>{$message}</p></div>";

		 }

}
}
