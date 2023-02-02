<?php

/**
 * WP-CLI-specific functionality of the plugin
 *
 * @link       https://andreaskarman.se
 * @since      0.9
 *
 * @package    Woocommerce_Order_Weight
 * @subpackage Woocommerce_Order_Weight/wp-cli
 */

class Woo_Order_Weight_WPCLI extends WP_CLI_Command {

	/**
	 * Add WP-CLI update command
	 *
	 * @since    0.9
	 */

   public function update( $args, $assoc_args ) {

     $admin_class = new Woo_Order_Weight_Admin( 'woo-order-weight', '0.9' );

     $orders = $admin_class->woo_get_all_orders_id();
     $total_orders = $admin_class->woo_get_total_order_count();
     WP_CLI::line( 'Updating order weights...' );

     $progress = \WP_CLI\Utils\make_progress_bar( 'Progress', $total_orders);
     foreach ( $orders as $order ){
       $admin_class->woo_update_order_weight($order);
       $progress->tick();
     }

     $progress->finish();

     WP_CLI::line( ''.$total_orders.' orders updated.' );

   }

}

WP_CLI::add_command( 'orderweight', 'Woo_Order_Weight_WPCLI' );
