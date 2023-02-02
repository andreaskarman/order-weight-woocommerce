=== Order Weight for WooCommerce ===
Contributors: andreaskarman
Tags: woocommerce, woocommerce order, woocommerce order weight, woocommerce plugin, order, orders, weight, ecommerce, shop
Requires at least: 5.0
Tested up to: 6.1.1
Stable tag: 0.9
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This plugin makes it easy to manage and track the weight of your orders in WooCommerce. It calculates and saves the weight of each order automatically and displays it in the WordPress admin interface.

== Description ==
This plugin makes it easy to manage and track the weight of your orders in WooCommerce. It calculates and saves the weight of each order automatically and displays it in the WordPress admin interface.

It also provides customers with insight into their orders weights by displaying them in the customer dashboard.

To get started, simply  [add weight to your products](https://docs.woothemes.com/document/adding-dimensions-and-weights-to-products-for-shipping/) and the plugin will do the rest.

= What it does =
- Automatically calculates and updates total order weight for new and changed orders
- Allows tracking and comparing of order weights in the WordPress admin interface
- Enhances the REST API by adding order weight and unit as properties
- Provides customers with visibility of their order weights in the customer dashboard
- Allows bulk updates of order weights using a built-in tool or WP-CLI command
- Includes order weight data in admin emails
- Automatically includes order weight data when exporting orders using the [WooCommerce Customer / Order / Coupon Export](https://woocommerce.com/products/ordercustomer-csv-export/) plugin

= Credits =
The first idea for the plugin was inspired by [this blog post](http://www.remicorson.com/store-and-display-woocommerce-order-total-weight/) written by Remi Corson.

= Author =
This plugin is developed and maintained by [Andreas Karman](http://andreaskarman.se).

== Installation ==
= Plugin requirements =

* WordPress 5.0 or greater
* WooCommerce 5.0.0 or greater

= Automatic installation =

To do an automatic install of Order Weight for WooCommerce, log in to your WordPress dashboard, navigate to the Plugins menu and click Add New. Type "Order Weight for WooCommerce" in the search field and click search Plugins. Click "Install Now" on this plugin which should be the first result.

= Manual installation =

To manually install our plugin, you need to first download the plugin and then upload it to your web server via FTP/SFTP. You can find more [detailed instructions in the WordPress Codex](https://codex.wordpress.org/Managing_Plugins#Manual_Plugin_Installation).

== Frequently Asked Questions ==
= Where is the plugin settings? =

To access the plugin settings, go to the "Advanced" section of the WooCommerce settings and find the "Order Weight for WooCommerce" tab.

= Orders created prior to plugin activation is   =

 If you need to update the weight of orders created prior to activating the plugin, you can use the bulk update tool (in the plugin settings) or WP-CLI command to update them all at once.

= How do I report bugs? =

To report bugs, you can use the [support forum](https://wordpress.org/support/plugin/woo-order-weight/) or the [ GitHub repository](https://github.com/andreaskarman/order-weight-woocommerce) for the plugin.

== Screenshots ==
1. Order weight column when managing orders.
2. Order weight when managing a single order.
3. Order weight in the customer dashboard.
4. Bulk action to update order weight.
5. Plugin settings

== Changelog ==

= 0.9 - 2022/12/05 =
*  Added WP-CLI command to update all order weights

= 0.8.1 - 2022/12/05 =
* Added total order weight to admin e-mails

= 0.8 - 2022/11/24 =
* Added admin tool to update the weight of all orders

= 0.7 - 2022/11/14 =
* Added custom bulk action to update order weights

= 0.6.4 - 2022/11/09 =
* Fixed PHP notices and additional PHP 8 compatibility issues

= 0.6.2 - 2022/10/19 =
* Fixed PHP 8 compatibility issue

= 0.6.1 - 2022/10/03 =
* Added link to plugin settings in "Plugins" page

= 0.6 - 2022/09/13 =
* Added feature to display order weight in the customer dashboard

= 0.5.5 - 2021/12/26 =
* Added support for order weight in WooCommerce Customer / Order / Coupon Export

= 0.5 - 2021/12/17 =
* Added compatibility with the new WooCommerce Block Checkout and fixed sorting error in "Products" by weight

= 0.4.5 - 2021/11/20 =
*  Added support for updating order weight in the WordPress admin and added order notifications when order weight is updated, fixed display error in "Products" weight column

= 0.4.0 - 2021/11/10 =
* Fixed "Order properties should not be accessed directly" error and added weight and weight unit to API calls

= 0.3.5 - 2016/01/26 =
* Added weight and weight unit to orders in the WooCommerce REST API, renamed plugin meta keys, added activation function to rename old meta keys, protected plugin meta keys, and updated the uninstall method with all meta keys

= 0.3.0 - 2016/01/24 =
* Renamed plugin and added weight column to "Products"

= 0.2.0 - 2015/11/26 =
* Added WooCommerce headers to readme.txt, checked if WooCommerce is activated before activating plugin, and removed metadata when uninstalling plugin

= 0.1.0 - 2015/11/22 =
* Initial plugin release.
