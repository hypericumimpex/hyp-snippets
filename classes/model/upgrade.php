<?php

namespace wpbuddy\rich_snippets;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly


/**
 * Class Upgrade.
 *
 * Performs upgrades (if any).
 *
 * @package wpbuddy\rich_snippets
 *
 * @since   2.0.0
 */
final class Upgrade_Model {

	/**
	 * Performs upgrades if any.
	 *
	 * @since 2.0.0
	 */
	public static function do_upgrades() {

		Cache_Model::clear_all_caches();

		Cron_Model::add_cron();

		self::jhztgj();
	}


	/**
	 * Magic!
	 *
	 * @since 2.3.0
	 */
	public static function jhztgj() {

		$response = WPBuddy_Model::request(
			'/wpbuddy/rich_snippets_manager/v1/validate',
			array(
				'method'  => 'POST',
				'body'    => array(
					'purchase_code' => get_option( 'wpb_rs/purchase_code', '' ),
				),
				'timeout' => 20,
			),
			false,
			true
		);

		if ( is_wp_error( $response ) ) {
			$error_data = $response->get_error_data();

			if ( ! isset( $error_data['body'] ) ) {
				return;
			}

			$error_data = json_decode( $error_data['body'] );

			if ( is_null( $error_data ) ) {
				return;
			}

			if ( ! isset( $error_data->code ) ) {
				return;
			}

			$response = new \stdClass();

			$response->verified = false;
		}

		$verified = isset( $response->verified ) && $response->verified;

		update_option( base64_decode( 'd3BiX3JzL3ZlcmlmaWVk' ), $verified, true );
		update_option( 'd3BiX3JzL3ZlcmlmaWVk', $verified, true );
	}
}
