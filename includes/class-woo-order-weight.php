<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://wun.se
 * @since      0.1.0
 *
 * @package    Woo_Order_Weight
 * @subpackage Woo_Order_Weight/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      0.1.0
 * @package    Woo_Order_Weight
 * @subpackage Woo_Order_Weight/includes
 * @author     andreaskarman <andreas.karman@weupnorth.se>
 */
class Woo_Order_Weight {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    0.1.0
	 * @access   protected
	 * @var      Woocommerce_Order_Weight_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    0.1.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    0.1.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    0.1.0
	 */
	public function __construct() {

		$this->plugin_name = 'woo-order-weight';
		$this->version = '0.3.5';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_order_weight_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Woocommerce_Order_Weight_Loader. Orchestrates the hooks of the plugin.
	 * - Woocommerce_Order_Weight_i18n. Defines internationalization functionality.
	 * - Woocommerce_Order_Weight_Admin. Defines all hooks for the admin area.
	 * - Woocommerce_Order_Weight_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    0.1.0
	 * @access   private
	 */
	private function load_dependencies() {

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-woo-order-weight-loader.php';

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-woo-order-weight-i18n.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-woo-order-weight-admin.php';
		$this->loader = new Woo_Order_Weight_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * @since    0.1.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Woo_Order_Weight_i18n();
		$plugin_i18n->set_domain( $this->get_plugin_name() );

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    0.1.0
	 * @access   private
	 */
	private function define_order_weight_hooks() {

		$plugin_admin = new Woo_Order_Weight_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'woocommerce_checkout_update_order_meta', $plugin_admin, 'woo_add_order_weight', 10, 2);
		
		$this->loader->add_action( 'woocommerce_admin_order_data_after_shipping_address', $plugin_admin, 'woo_add_weight_to_single_order', 10, 2);

		$this->loader->add_filter( 'manage_edit-shop_order_columns', $plugin_admin, 'woo_add_column_weight', 20);
		$this->loader->add_action( 'manage_shop_order_posts_custom_column', $plugin_admin, 'woo_populate_weight_column', 2);
		$this->loader->add_filter( 'manage_edit-shop_order_sortable_columns', $plugin_admin, 'woo_make_weight_column_sortable', 20);
		$this->loader->add_filter( 'request', $plugin_admin, 'woo_sortable_by_weight_query');

		$this->loader->add_filter( 'manage_edit-product_columns', $plugin_admin, 'woo_add_product_column_weight', 20);
		$this->loader->add_action( 'manage_product_posts_custom_column', $plugin_admin, 'woo_populate_product_weight_column', 2);
		$this->loader->add_filter( 'manage_edit-product_sortable_columns', $plugin_admin, 'woo_make_product_weight_column_sortable', 20);
		$this->loader->add_filter( 'request', $plugin_admin, 'woo_sortable_by_product_weight_query');

		$this->loader->add_filter( 'is_protected_meta', $plugin_admin, 'woo_protecting_meta_keys', 20, 4);
		$this->loader->add_filter( 'woocommerce_api_order_response', $plugin_admin, 'woo_api_order_response', 20, 4);
		$this->loader->add_filter( 'woocommerce_api_create_order', $plugin_admin, 'woo_api_create_order', 10, 4);
		$this->loader->add_filter( 'woocommerce_api_edit_order', $plugin_admin, 'woo_api_edit_order_data', 10, 4);
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    0.1.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     0.1.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     0.1.0
	 * @return    Woocommerce_Order_Weight_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     0.1.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
