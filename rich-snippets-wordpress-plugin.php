<?php
/*
Plugin Name: HYP Snippets
Plugin URI: https://github.com/hypericumimpex/hyp-snippets/
Description: Allows to create Rich Snippets and general structured data readable by search engines.
Version: 2.8.1
Author: wpbuddy
Author URI: https://github.com/hypericumimpex/
License: CodeCanyon Regular License
License URI: https://github.com/hypericumimpex/hyp-snippets
Text Domain: rich-snippets-schema
Domain Path: /languages
Requires PHP: 7.0.0
NotActiveWarning: Your copy of the Rich Snippets Plugin has not yet been activated.
ActivateNow: Activate it now.
Active: Your copy is active.

Copyright 2012-2019  HYPERICUM
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

define( 'WPB_RS_FILE', __FILE__ );

/**
 *
 * PHP Version check.
 *
 */
if ( ! call_user_func( function () {

	if ( version_compare( PHP_VERSION, '7.0', '<' ) ) {
		add_action( 'admin_notices', 'wpb_rs_old_php_notice' );

		function wpb_rs_old_php_notice() {

			printf(
				'<div class="notice error"><p>%s</p></div>',
				sprintf(
					__( 'Hey mate! Sorry for interrupting you. It seem\'s that you\'re using an old PHP version (your current version is %s). You should upgrade to at least 7.0 or higher in order to use SNIP. Thank you!', 'rich-snippets-schema' ),
					esc_html( PHP_VERSION )
				)
			);
		}

		$plugin_file = substr( str_replace( WP_PLUGIN_DIR, '', __FILE__ ), 1 );

		add_action( 'after_plugin_row_' . $plugin_file, 'wpb_rs_plugin_upgrade_notice', 10, 2 );

		function wpb_rs_plugin_upgrade_notice( $plugin_data, $status ) {

			printf(
				'<tr><td></td><td colspan="2"><div class="notice notice-error notice-error-alt inline"><p>%s</p></div></td></tr>',
				__( 'This plugin needs at least PHP version 7.0.x to run properly. Please ask your host on how to change PHP versions.', 'rich-snippets-schema' )
			);
		}

		# sorry. The plugin will not work with an old PHP version.
		return false;
	}

	global $wp_version;

	if ( version_compare( $wp_version, '4.8.0', '<' ) ) {
		add_action( 'admin_notices', 'wpb_rs_old_php_notice' );

		function wpb_rs_old_php_notice() {
			global $wp_version;

			printf(
				'<div class="notice error"><p>%s</p></div>',
				sprintf(
					__( 'Hey mate! Sorry for interrupting you. It seem\'s that you\'re using an old WordPress version (your current version is %s). You should upgrade to at least 4.8.0 or higher in order to use SNIP. Thank you!', 'rich-snippets-schema' ),
					esc_html( $wp_version )
				)
			);
		}

		return false;
	}

	return true;
} ) ) {
	return;
}


/**
 *
 * WP Version check.
 *
 */
if ( version_compare( get_bloginfo( 'version' ), '4.6', '<' ) ) {
	add_action( 'admin_notices', 'wpb_rss_old_php_notice' );

	function wpb_rss_old_php_notice() {

		printf(
			'<div class="notice error"><p>%s</p></div>',
			sprintf(
				__( 'Hey mate! Sorry for interrupting you. It seem\'s that you\'re using an old version WordPress (your current version is %s). You should upgrade to at least 4.6 or higher in order to use the Rich Snippets plugin. Thank you!', 'rich-snippets-schema' ),
				esc_html( get_bloginfo( 'version' ) )
			)
		);
	}

	# sorry. The plugin will not work with an old WP version.
	return;
}


/**
 *
 *
 * Bootstrapping
 *
 */
require_once( __DIR__ . '/bootstrap.php' );
