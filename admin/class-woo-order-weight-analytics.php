<?php

/**
 * Functionality for WooCommerce Analytics
 *
 * @link       https://andreaskarman.se
 * @since      1.2
 *
 * @package    Woocommerce_Order_Weight
 * @subpackage Woocommerce_Order_Weight/analytics
 */

class Woo_Order_Weight_Analytics {

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

	public function woo_order_weight_add_analytics_scripts(){
		if (
			!method_exists('\Automattic\WooCommerce\Admin\PageController', 'is_admin_or_embed_page') ||
			!\Automattic\WooCommerce\Admin\PageController::is_admin_or_embed_page()
		) {
			return;
		}
		$script_path = plugin_dir_url( __DIR__ ).'javascript/analytics/analytics.js';

		$script_asset_path = plugin_dir_url( __DIR__ ).'javascript/analytics/analytics.asset.php';
		$script_asset      = file_exists($script_asset_path)
		? require($script_asset_path)
		: array('dependencies' => array(), 'version' => '1.2');
		//$script_url = plugins_url($script_path, __FILE__);

		wp_register_script(
			'reports',
			$script_path,
			$script_asset['dependencies'],
			$script_asset['version'],
			true
		);
		$weight_unit = get_option('woocommerce_weight_unit');

    $pluginData = array(
        'weight_unit' => $weight_unit,
    );
		wp_enqueue_script('reports');
		wp_localize_script('reports', 'woo_order_weight', $pluginData);

}

	public function woo_order_weight_reports_column_types( $types ) {
		$types['order_weight'] = 'floatval';
		$types['avg_order_weight'] = 'floatval';
		return $types;
	}

	public function woo_order_weight_select_orders_stats_total( $clauses ) {
		$clauses[] = ', ROUND(AVG(order_weight_postmeta.meta_value) ,1) AS avg_order_weight';
		return $clauses;
	}

	public function woo_order_weight_select_orders_subquery( $clauses ) {
		$clauses[] = ', IFNULL(order_weight_postmeta.meta_value, 0) AS order_weight';
		return $clauses;
	}

	public function woo_order_weight_join_orders( $clauses ) {
		global $wpdb;
		$clauses[] = "LEFT JOIN {$wpdb->postmeta} order_weight_postmeta ON {$wpdb->prefix}wc_order_stats.order_id = order_weight_postmeta.post_id AND order_weight_postmeta.meta_key = 'order_weight'";
    return $clauses;
	}

	public function woo_order_weight_columns_names_to_export( $columns, $exporter ) {
		$columns['order_weight'] = __( 'Weight', 'woocommerce' );
		return $columns;
	}

	public function woo_order_weight_row_data_to_export( $row, $item ) {
		$weight_unit = get_option('woocommerce_weight_unit');
		$row['order_weight'] = $item['order_weight'];
		return $row;
	}


}
