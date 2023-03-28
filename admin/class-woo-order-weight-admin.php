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

			$check_public_setting = get_option( 'orderweight_enable_order_notes' );
			if ($check_public_setting == 'yes') {
				$note = sprintf(
					__( 'Order weight updated to %s %s.', 'woo-order-weight' ),
					$weight,
					$weight_unit
				);
				$the_order->add_order_note($note, false, false);
			}
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
			$settings_slider[] = array( 'name' => __( 'Settings', 'woocommerce' ), 'type' => 'title', 'desc' => __( 'The following settings are used to configure Order Weight for WooCommerce.', 'woo-order-weight' ), 'id' => 'orderweight' );
			$settings_slider[] = array(
				'name'     => __( 'My account', 'woo-order-weight' ),
				'id'       => 'orderweight_customer_dashboard',
				'type'     => 'checkbox',
				'css'      => 'min-width:300px;',
				'desc'     => __( 'Display the weight of each order in the customer dashboard', 'woo-order-weight' ),
			);

			$settings_slider[] = array(
				'title'         => __( 'Emails', 'woocommerce' ),
				'desc'          => __( 'Add order weight to admin emails', 'woo-order-weight' ),
				'id'            => 'orderweight_enable_admin_emails',
				'default'       => 'yes',
				'type'          => 'checkbox',
				'checkboxgroup' => 'start',
				'autoload'      => true,
			);

			$settings_slider[] = array(
				'title'         => __( 'Customer emails', 'woocommerce' ),
				'desc'          => __( 'Add order weight to customer emails', 'woo-order-weight' ),
				'id'            => 'orderweight_enable_customer_emails',
				'default'       => 'no',
				'type'          => 'checkbox',
				'checkboxgroup' => 'end',
				'autoload'      => false,
			);

			$settings_slider[] = array(
				'title'         => __( 'Order notes', 'woocommerce' ),
				'desc'          => __( 'Enable order notes when a order weight is changed', 'woo-order-weight' ),
				'id'            => 'orderweight_enable_order_notes',
				'default'       => 'yes',
				'type'          => 'checkbox',
				'checkboxgroup' => 'start',
				'autoload'      => true,
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
					'woo_update_order_weight', // just a parameter for URL
					count( $object_ids ), // how many posts have been selected
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

	 /**
		* Adding settings for bulk tool
		*
		* @since    0.8
		*/

		public function woo_add_tool_settings(  ) {

			if( strpos( $_SERVER["REQUEST_URI"], "orderweight" ) !== false ){
				$order_count = $this->woo_get_total_order_count();
				$nonce = wp_create_nonce( 'orderweight-nonce' );

				echo '<hr></hr>';
				echo '<h2>'. esc_html__( 'Update all order weights', 'woo-order-weight' ) .'</h2>';
				echo '<p>'. esc_html__( 'Use this to calculate and set the weight of all orders in your WooCommerce installation, including those created before activating the plugin. The order weight will be based on the current product weights.', 'woo-order-weight' ) .'</p>';
				echo '<p class="status"><strong><span class="orderweight-processed-orders">0</span> '. esc_html__( 'of', 'woo-order-weight' ) .' <span class="orderweight-total-orders">'.$order_count.'</span> orders updated</strong></p>';
				echo '<div class="orderweight-progress-wrapper"><div class="orderweight-progress-bar progress-bar-striped progress-bar-animated"></div></div>';
				echo '<button id="woo_update_orders" name="woo_update_orders" class="button-primary woocommerce-save-button orderweight-submit-button" data-nonce="' . esc_attr( $nonce ) . '" type="submit" value="'. esc_html__( 'Update all orders', 'woo-order-weight' ) .'">'. esc_html__( 'Update all orders', 'woo-order-weight' ) .'</button>';
			}

	}

	/**
	 * Get total order count for all statuses
	 *
	 * @since    0.8
	 */

		public function woo_get_total_order_count(){
			$statuses    = array_keys( wc_get_order_statuses() );
			$order_count = 0;

			foreach ( $statuses as $status ) {

				if ( 0 !== strpos( $status, 'wc-' ) ) {
					$status = 'wc-' . $status;
				}

				$order_count += wp_count_posts( 'shop_order' )->$status;
			}
			return number_format($order_count);
		}

	 /**
		* Adding plugin CSS and JavaScript
		*
		* @since    0.8
		*/

	 public function woo_add_admin_assets($hook) {
  		wp_enqueue_script('order-weight-js', plugin_dir_url(__FILE__) . '../javascript/orderweight-admin.js');
			wp_enqueue_style('order-weight-css', plugin_dir_url(__FILE__) . '../css/orderweight-admin.css');
		}

	 	/**
		 * Processing AJAX bulk action for all orders
		 *
		 * @since    0.8
		 */

		 public function woo_process_bulk_orders() {

			 if( ! wp_verify_nonce( $_POST['nonce'], 'orderweight-nonce' ) ) {
				 die();
			 }

			 $offset = absint( $_POST['offset'] );
			 $increment = 25;
 			 $order_data = array();

 			if( $offset == 0 ) {
	 			delete_transient( 'order_weight_update_process' );
	 			$order_data['order_ids'] = $this->woo_get_all_orders_id();
	 			$order_data['total_orders'] = count( $order_data['order_ids'] );
 			}
			else {
	 			$order_data['order_ids'] = $this->woo_get_all_orders_id();
	 			$order_data['total_orders'] = count( $order_data['order_ids'] );
 			}

 			if( $offset > $order_data['total_orders'] ) {
	 			$offset = 'done';
			}
			else
			{

			 $args = array(
				 'post_type'         => 'shop_order',
		 	 	 'post_status'       =>  array_keys( wc_get_order_statuses() ),
				 'posts_per_page' 	 => $increment,
				 'offset' 					 => $offset,
				 'fields' 					 => 'ids',
				 'no_found_rows' 		 => true,
				 'post__in' 				 => $order_data['order_ids']
			 );
	 	 	$orders = get_posts ( $args );
	 		foreach( $orders as $order_id ) {
				 $this->woo_update_order_weight($order_id);
				 $clicks = get_transient( 'order_weight_update_process' );
				 $clicks++;
				 set_transient( 'order_weight_update_process', $clicks, DAY_IN_SECONDS );
	 	 	}
	 		$offset += $increment;
 		}
 		echo json_encode( array( 'offset' => $offset, 'count' => get_transient( 'order_weight_update_process' )) );
 		exit;

	}

	/**
	* Get all order ID's
	*
	* @since    0.8
	*/


	public function woo_get_all_orders_id(  ) {
		$args = array(
    	'return' => 'ids',
			'limit' => -1,
		);
		$orders = wc_get_orders( $args );
		return $orders;
	}

	/**
	 * Add plugin help informatino
	 *
	 * @since    0.8
	 */

	 public function woo_add_plugin_help(  ) {

		 if( strpos( $_SERVER["REQUEST_URI"], "orderweight" ) !== false ){
			 $text = sprintf(
			     esc_html__( 'Do you need help or do you have suggestions for improvement? Please use the %s', 'woo-order-weight' ),
			     '<a href="https://wordpress.org/support/plugin/woo-order-weight/" target="_blank"><strong>' . esc_html__( 'plugin support forum', 'woo-order-weight' ) . '</strong></a>.'
			 );

			 echo '<hr class="woo-support"></hr>';
			 echo '<p><i>'. $text .'</i></p>';
		 }

 }

 /**
 * Add order weight to customer emails
 *
 * @since    1.0
 */

 public function woo_add_order_weight_to_emails( $fields, $sent_to_admin, $order ) {

	 $orderweight_admin_emails = get_option( 'orderweight_enable_admin_emails' );
	 $orderweight_customer_emails = get_option( 'orderweight_enable_customer_emails' );

	 if ($orderweight_admin_emails == 'yes' && $orderweight_customer_emails == 'yes') {
		 $fields['meta_key'] = array(
				'label' => __( 'Total weight of the order', 'woo-order-weight' ),
				'value' => get_post_meta( $order->get_id(), $this->woo_get_weight_meta_key(), true ) . ' ' . $this->woo_get_woocommerce_weight_unit(),
		);
		return $fields;
	 }
	 elseif ($orderweight_admin_emails == 'no' && $orderweight_customer_emails == 'yes'){
		 if( !$sent_to_admin ):
			 $fields['meta_key'] = array(
					 'label' => __( 'Total weight of the order', 'woo-order-weight' ),
					 'value' => get_post_meta( $order->get_id(), $this->woo_get_weight_meta_key(), true ) . ' ' . $this->woo_get_woocommerce_weight_unit(),
			 );
		 		return $fields;
	 	 endif;
	 }
	 elseif ($orderweight_admin_emails == 'yes' && $orderweight_customer_emails == 'no'){
		 if( $sent_to_admin ):
			 $fields['meta_key'] = array(
					 'label' => __( 'Total weight of the order', 'woo-order-weight' ),
					 'value' => get_post_meta( $order->get_id(), $this->woo_get_weight_meta_key(), true ) . ' ' . $this->woo_get_woocommerce_weight_unit(),
			 );
				return $fields;
		 endif;
	 }
 }

}
