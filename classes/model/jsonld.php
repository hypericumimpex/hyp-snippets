<?php

namespace wpbuddy\rich_snippets;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly


/**
 * Class JSON+LD.
 *
 * Filters for custom JSON+LD data.
 *
 * @package wpbuddy\rich_snippets
 *
 * @since   2.4.0
 */
final class JSONLD_Model {

	/**
	 * The instance.
	 *
	 * @var JSONLD_Model
	 *
	 * @since 2.4.0
	 */
	protected static $_instance = null;


	/**
	 * Get the singleton instance.
	 *
	 * Creates a new instance of the class if it does not exists.
	 *
	 * @return   JSONLD_Model
	 *
	 * @since 2.4.0
	 */
	public static function instance() {

		if ( null === self::$_instance ) {
			self::$_instance = new self;
			self::$_instance->init();
		}

		return self::$_instance;
	}


	/**
	 * Magic function for cloning.
	 *
	 * Disallow cloning as this is a singleton class.
	 *
	 * @since 2.4.0
	 */
	protected function __clone() {
	}


	/**
	 * Magic method for setting upt the class.
	 *
	 * Disallow external instances.
	 *
	 * @since 2.4.0
	 */
	protected function __construct() {
	}

	/**
	 * Init hooks.
	 *
	 * @since 2.4.0
	 */
	private function init() {

		add_filter( 'wpbuddy/rich_snippets/rich_snippet/json+ld/value/@id', [ $this, 'add_url_to_id' ], 10, 2 );
	}


	/**
	 * Replaces {url} with the current URL.
	 *
	 * @param string $value
	 * @param array  $meta_info
	 *
	 * @return string
	 */
	public function add_url_to_id( $value, $meta_info ) {

		if ( false === stripos( $value, '{url}' ) ) {
			return $value;
		}

		if ( isset( $meta_info['current_post_id'] )
		     && ! empty( $meta_info['current_post_id'] )
		     && false !== $permalink = get_permalink( $meta_info['current_post_id'] )
		) {
			return str_replace( '{url}', esc_url( $permalink ), $value );
		}

		return str_replace( '{url}', esc_url( wp_guess_url() ), $value );

	}

}
