=== Order Weight for WooCommerce ===
Contributors: weupnorth, andreaskarman
Tags: woocommerce, woocommerce order, woocommerce order weight, woocommerce plugin, order, orders, weight, ecommerce, shop
Requires at least: 4.3.0
Tested up to: 4.4.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This plugin extends WooCommerce by storing the weight of orders.

== Description ==
By using this plugin the weight of WooCommerce orders will be stored in the database and the order weight will be available in your WordPress Admin when managing orders. It also extends orders in the WooCommerce REST API with weight and weight unit.

To make the plugin work as intended, you need to [add weight to your products](https://docs.woothemes.com/document/adding-dimensions-and-weights-to-products-for-shipping/)

= What it does =
- Saving the weight of orders when a new order is created.
- Making order weight available in the WordPress Admin.
- Adding sortable weight columns to "Products" and "Orders".
- Extending orders in WooCommerce REST API with weight and weight unit.

= Credits =
The concepts of the plugin came from [this blog post](http://www.remicorson.com/store-and-display-woocommerce-order-total-weight/) by Remi Corson.

= Author =
This plugin is developed and maintained by [Andreas Karman](http://andreaskarman.se), [We Up North](http://wun.se). Follow Andreas Karman on Twitter.

== Installation ==
= Plugin requirements =

* WordPress 4.3.0 or greater
* WooCommerce 2.4.0 or greater

= Automatic installation =

To do an automatic install of Order Weight for WooCommerce, log in to your WordPress dashboard, navigate to the Plugins menu and click Add New. Type "Order Weight for WooCommerce" in the search field and click search Plugins. Click "Install Now" on this plugin which should be the first result.

= Manual installation =

To manually install our plugin, you need to first download the plugin and then upload it to your webserver via FTP/SFTP. You can find more [detailed instructions in the WordPress Codex](https://codex.wordpress.org/Managing_Plugins#Manual_Plugin_Installation).

== Frequently Asked Questions ==
= Where is the plugin settings? =

There is no settings available for this plugin. Just activate it and it will do what stated.

= Where can I report bugs? =

Bugs can be reported either in the support forum or preferably in the [plugin GitHub repository](https://github.com/weupnorth/Order-Weight-for-WooCommerce).

== Screenshots ==
1. Order weight column when managing orders.
2. Order weight when managing a single order.

== Changelog ==
= 0.3.5 - 2016/01/26 =
* Added weight and weight unit to orders in the WooCommerce REST API.
* Changed meta key for order weight (removed underscore prefix).
* Added activation function to rename old meta keys.
* Protected plugin meta keys using is_protected_meta filter.
* Updated the uninstall method with all meta keys.

= 0.3.0 - 2016/01/24 =
* Renamed plugin.
* Added weight column to "Products".

= 0.2.0 - 2015/11/26 =
* Removed metadata when un-installing plugin.
* Added WooCommerce headers to readme.txt.
* Check if WooCommerce is activated before activating plugin.

= 0.1.0 - 2015/11/22 =
* Initial plugin release.