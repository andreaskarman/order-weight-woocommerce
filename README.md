# Order Weight for WooCommerce #
**Contributors:** andreaskarman

**Tags:** woocommerce, woocommerce order, woocommerce order weight, woocommerce plugin, order, orders, weight, ecommerce, shop

**Requires at least:** 5.0

**Tested up to:** 6.1.1

**Stable tag:** 0.8

**License:** GPLv2 or later

**License URI:** http://www.gnu.org/licenses/gpl-2.0.html

This WordPress plugin stores the weight of WooCommerce orders and displays the order weight when managing orders.

## Description ##
When a new order is created by a customer, the total weight of the order will be stored in the order metadata. For this to work as indented, your physical products needs a weight value.

To make the plugin work as intended, [add weight to your products](https://docs.woothemes.com/document/adding-dimensions-and-weights-to-products-for-shipping/).

### What it does ###
* When a new order is added, the total order weight is populated.
* When an order is changed, the order weight is updated and a order notification is added.
* Adding weight as a sortable column for ”Orders” and ”Products” in WordPress admin interface.
* Extends the REST API with order weight and weight unit as order properties
* Displays order weight in the customer dashboard
* Bulk action to update order weight on historical orders
* When exporting orders with [WooCommerce Customer / Order / Coupon Export](https://woocommerce.com/products/ordercustomer-csv-export/), order weight is included.


### Credits ###
The concepts of the plugin came from [this blog post](http://www.remicorson.com/store-and-display-woocommerce-order-total-weight/) by Remi Corson.

### Author ###
This plugin is developed and maintained by [Andreas Karman](http://andreaskarman.se).

## Installation ##
### Plugin requirements ###

* WordPress 5.0 or greater
* WooCommerce 5.0.0 or greater

### Automatic installation ###

To do an automatic install of Order Weight for WooCommerce, log in to your WordPress dashboard, navigate to the Plugins menu and click Add New. Type "Order Weight for WooCommerce" in the search field and click search Plugins. Click "Install Now" on this plugin which should be the first result.

### Manual installation ###

To manually install our plugin, you need to first download the plugin and then upload it to your webserver via FTP/SFTP. You can find more [detailed instructions in the WordPress Codex](https://codex.wordpress.org/Managing_Plugins#Manual_Plugin_Installation).

## Frequently Asked Questions ##
### Where is the plugin settings? ###

There is no settings available for this plugin. Just activate it and it will do what stated.

### How can I get the weight for orders created prior to plugin activation? ###Jag

In the WooCommerce Order interface, mark the orders and use the "Bulk action" -> "Update order weight". This will populate the order weight based on the content of the order and the current weight of the products.

### Where can I report bugs? ###

Bugs can be reported either in the support forum or preferably in the [plugin GitHub repository](https://github.com/andreaskarman/order-weight-woocommerce).


## Changelog ##

### 0.8 - 2022/11/24 ###
* New feature: Admin tool to update the weight on all orders.

### 0.7 - 2022/11/14 ###
* New feature: Bulk action to update order weights.

### 0.6.4 - 2022/11/09 ###
* Fixed PHP notices when API is called.
* Fixed additional PHP8 compatibility issues.

### 0.6.2 - 2022/10/19 ###
* Fixed PHP8 compatibility issue.

### 0.6.1 - 2022/10/03 ###
* Added link to plugin settings in "Plugins".

### 0.6 - 2022/09/13 ###
* Added feature to display order weight in the customer dashboard.

### = 0.5.5 - 2021/12/26 ###
* New feature: Added order weight support for WooCommerce Customer / Order / Coupon Export

### 0.5 - 2021/12/17 ###
* New feature: Compatibility added to the new WooCommerce Block Checkout
* Bug fix: Fixed sorting error of "Products" by weight

### 0.4.5 - 2021/11/20 ###
* New feature: Updating order weight in the WordPress admin when an order is updated.
* New feature: When order weight is updated, a order notifications is added.
* Bug fix: Display error in "Products" weight column

### 0.4.0 - 2021/11/10 ###
* Fixed "Order properties should not be accessed directly" error
* Fixed weight and weight unit in API calls not showing

### 0.3.5 - 2016/01/26 ###
* Added weight and weight unit to orders in the WooCommerce REST API.
* Changed meta key for order weight (removed underscore prefix).
* Added activation function to rename old meta keys.
* Protected plugin meta keys using is_protected_meta filter.
* Updated the uninstall method with all meta keys.

### 0.3.0 - 2016/01/24 ###
* Renamed plugin.
* Added weight column to "Products".

### 0.2.0 - 2015/11/26 ###
* Removed metadata when un-installing plugin.
* Added WooCommerce headers to readme.txt.
* Check if WooCommerce is activated before activating plugin.

### 0.1.0 - 2015/11/22 ###
* Initial plugin release.
