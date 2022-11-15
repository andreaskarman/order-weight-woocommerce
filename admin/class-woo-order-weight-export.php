<?php

/**
 * The export-specific functionality of the plugin.
 *
 * @link       http://andreaskarman.se
 * @since      0.5.5
 *
 * @package    Woocommerce_Order_Weight
 * @subpackage Woocommerce_Order_Weight/export
 */

class Woo_Order_Weight_Export {

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
	* Adding order weight column header
 	*
 	* @param array $column_headers the original column headers
 	* @param \CSV_Export_Generator $csv_generator the generator instance
 	* @return array the updated column headers
 	*/
	public function wc_csv_export_add_weight_column_header( $column_headers, $csv_generator ) {

		$column_headers['weight'] = 'order_weight';
		return $column_headers;

	}

	/**
	 * Adding order weight column data
	 *
	 * @param array $order_data the original column data
	 * @param \WC_Order $order the order object
	 * @param \CSV_Export_Generator $csv_generator the generator instance
	 * @return array the updated column data
	 */
	public function wc_csv_export_add_weight_column_data( $order_data, $order, $csv_generator ) {

		$order_weight_key = is_callable( array( $order, 'get_meta' ) ) ? $order->get_meta( 'order_weight' ) : $order->$order_weight_key;

		$custom_data = array(
			'weight' => $order_weight_key,
		);

		return $this->wc_csv_export_add_custom_data( $order_data, $custom_data, $csv_generator );
	}

	/**
	 * Helper function to add custom order data to CSV Export order data
	 *
	 * @param array $order_data the original column data that may be in One Row per Item format
	 * @param array $custom_data the custom column data being merged into the column data
	 * @param \CSV_Export_Generator $csv_generator the generator instance
	 * @return array the updated column data
	 */
	public function wc_csv_export_add_custom_data( $order_data, $custom_data, $csv_generator ) {

		$new_order_data   = array();

		if ( $this->wc_csv_export_is_one_row( $csv_generator ) ) {

			foreach ( $order_data as $data ) {
				$new_order_data[] = array_merge( (array) $data, $custom_data );
			}

		} else {
			$new_order_data = array_merge( $order_data, $custom_data );
		}

		return $new_order_data;
	}

	/**
	 * Helper function to check the export format
	 *
	 * @param \CSV_Export_Generator $csv_generator the generator instance
	 * @return bool - true if this is a one row per item format
	 */
	public function wc_csv_export_is_one_row( $csv_generator ) {

		$one_row_per_item = false;

		if ( version_compare( wc_customer_order_csv_export()->get_version(), '4.0.0', '<' ) ) {

			// pre 4.0 compatibility
			$one_row_per_item = ( 'default_one_row_per_item' === $csv_generator->order_format || 'legacy_one_row_per_item' === $csv_generator->order_format );

		} elseif ( isset( $csv_generator->format_definition ) ) {

			// post 4.0 (requires 4.0.3+)
			$one_row_per_item = 'item' === $csv_generator->format_definition['row_type'];
		}

		return $one_row_per_item;
	}

	/**
	 * Adding Weight to Orders XML Export
	 *
	 * @param \CSV_Export_Generator $csv_generator the generator instance
	 * @return bool - true if this is a one row per item format
	 */

	function wc_xml_order_export_weight( $format, $order ) {

		$new_format = array();
		$order_weight_key = is_callable( array( $order, 'get_meta' ) ) ? $order->get_meta( 'order_weight' ) : $order->$order_weight_key;

		foreach ( $format as $key => $data ) {

			$new_format[ $key ] = $data;
			$new_format['Weight'] = $order_weight_key;
		}

		return $new_format;
	}

}
