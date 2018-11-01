<?php
/**
 * Plugin Name: TMSM WooCommerce Payline by Monext Payment Gateway
 * Plugin URI: http://www.payline.com
 * Description: Integration of Payline by Monext payment gateway in your WooCommerce store
 * Version: 1.3.7
 * Requires at least: 4.4
 * Tested up to: 4.9.8
 * WC requires at least: 3.3
 * WC tested up to: 3.5
 * Author:            Nicolas Mollet
 * Author URI:        https://github.com/nicomollet
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain:       tmsm-woocommerce-payline
 * Domain Path:       /languages
 * Github Plugin URI: https://github.com/thermesmarins/tmsm-woocommerce-payline
 * Github Branch:     master
 * Requires PHP:      5.6
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'WCPAYLINE_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

function woocommerce_payline_activation() {
	if ( ! is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
		deactivate_plugins( plugin_basename( __FILE__ ) );

		load_plugin_textdomain( 'tmsm-woocommerce-payline', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

		$message = sprintf( __( 'Sorry! In order to use WooCommerce %s Payment plugin, you need to install and activate the WooCommerce plugin.',
			'tmsm-woocommerce-payline' ), 'Payline' );
		wp_die( $message, 'WooCommerce Payline Gateway Plugin', array( 'back_link' => true ) );
	}
}

register_activation_hook( __FILE__, 'woocommerce_payline_activation' );

// inserts class gateway
function woocommerce_payline_init() {
	// Load translation files
	load_plugin_textdomain( 'tmsm-woocommerce-payline', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

	if ( ! class_exists( 'WC_Gateway_Payline' ) ) {
		require_once 'class-wc-gateway-payline.php';
	}

	require_once 'vendor/autoload.php';
}

add_action( 'woocommerce_init', 'woocommerce_payline_init' );


// adds method to woocommerce methods
function woocommerce_payline_add_method( $methods ) {
	$methods[] = 'WC_Gateway_Payline';

	return $methods;
}

add_filter( 'woocommerce_payment_gateways', 'woocommerce_payline_add_method' );

// add a link from plugin list to parameters
function woocommerce_payline_add_link( $links, $file ) {
	$links[] = '<a href="' . admin_url( 'admin.php?page=wc-settings&tab=checkout&section=payline' ) . '">' . __( 'Settings',
			'tmsm-woocommerce-payline' ) . '</a>';

	return $links;
}

add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'woocommerce_payline_add_link', 10, 2 );