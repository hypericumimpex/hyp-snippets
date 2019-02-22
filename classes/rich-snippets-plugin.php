<?php

namespace wpbuddy\rich_snippets;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly


/**
 * Class Rich_Snippets.
 *
 * Starts up all the good things.
 *
 * @package wpbuddy\rich_snippets
 *
 * @since   2.0.0
 */
final class Rich_Snippets_Plugin {

	/**
	 * The instance.
	 *
	 * @var Rich_Snippets_Plugin
	 *
	 * @since 2.0.0
	 */
	protected static $_instance = null;


	/**
	 * If the init method has been called.
	 *
	 * @var bool
	 *
	 * @since 2.0.0
	 */
	private $initialized = false;


	/**
	 * The plugin file path.
	 *
	 * @since 2.0.0
	 *
	 * @var string
	 */
	private $plugin_file = '';


	/**
	 * Activates debugging.
	 *
	 * @since 2.0.0
	 *
	 * @var bool
	 */
	private $debug = false;


	/**
	 * Get the singleton instance.
	 *
	 * Creates a new instance of the class if it does not exists.
	 *
	 * @return   Rich_Snippets_Plugin
	 *
	 * @since 2.0.0
	 */
	public static function instance() {

		if ( null === self::$_instance ) {
			self::$_instance = new self;
		}

		return self::$_instance;
	}


	/**
	 * Magic function for cloning.
	 *
	 * Disallow cloning as this is a singleton class.
	 *
	 * @since 2.0.0
	 */
	protected function __clone() {
	}


	/**
	 * Magic method for setting upt the class.
	 *
	 * Disallow external instances.
	 *
	 * @since 2.0.0
	 */
	protected function __construct() {
	}


	/**
	 * Init everything needed.
	 *
	 * @since 2.0.0
	 */
	public function init() {

		# Upps. This seems to be initialized already.
		if ( $this->initialized ) {
			return;
		}

		$this->debug = defined( 'WPB_RS_DEBUG' ) && WPB_RS_DEBUG;

		# set plugin path to file
		$this->plugin_file = WPB_RS_FILE;

		register_activation_hook( $this->get_plugin_file(), array( self::instance(), 'on_activation' ) );
		register_deactivation_hook( $this->get_plugin_file(), array( self::instance(), 'on_deactivation' ) );

		add_action( 'init', array( 'wpbuddy\rich_snippets\Posttypes_Model', 'create_post_types' ) );

		if ( is_admin() ) {
			Admin_Controller::instance()->init();
		} else {
			new Frontend_Controller();
		}

		add_action( 'rest_api_init', array( 'wpbuddy\rich_snippets\Rest_Controller', 'init' ) );

		$this->third_party_init();

		# allow other plugins to hook into
		do_action_ref_array( 'wpbuddy/rich_snippets/init', self::$_instance );

		# After plugins have been updated
		add_action( 'upgrader_process_complete', function ( $upgrader, $args ) {

			/**
			 * @var \WP_Upgrader $upgrader
			 * @var array        $args
			 */

			if ( ! isset( $args['type'] ) ) {
				return;
			}

			if ( 'plugin' !== $args['type'] ) {
				return;
			}

			if ( ! isset( $args['plugins'] ) ) {
				return;
			}

			if ( ! is_array( $args['plugins'] ) ) {
				return;
			}

			if ( in_array( plugin_basename( WPB_RS_FILE ), $args['plugins'] ) ) {
				update_option( 'wpb_rs/upgraded', false, 'yes' );
			}

		}, 10, 2 );

		Cron_Model::add_cron_hooks();

		add_filter( 'site_transient_update_plugins', array(
			'\wpbuddy\rich_snippets\Update_Controller',
			'transient_hook',
		) );

		add_filter( 'http_request_args', array(
			'\wpbuddy\rich_snippets\Update_Controller',
			'download_auth_headers',
		), 10, 2 );

		# done.
		$this->initialized = true;
	}


	/**
	 * Returns the plugin file path.
	 *
	 * @since 2.0.0
	 *
	 * @return string
	 */
	public function get_plugin_file() {

		return $this->plugin_file;
	}


	/**
	 * Checks if debugging is on.
	 *
	 * @since 2.0.0
	 *
	 * @return bool
	 */
	public function debug(): bool {

		return $this->debug;
	}


	/**
	 * Performs actions on plugin activation.
	 *
	 * @since 2.0.0
	 */
	public function on_activation() {

		Admin_Settings_Controller::prepare_settings();

		Cron_Model::add_cron();
	}


	/**
	 * Performs actions on plugin deactivation.
	 *
	 * @since 2.0.0
	 */
	public function on_deactivation() {

		Cron_Model::remove_cron();

		delete_option( base64_decode( 'd3BiX3JzL3ZlcmlmaWVk' ) );
		delete_option( 'd3BiX3JzL3ZlcmlmaWVk' );
	}


	/**
	 * Included third party stuff.
	 *
	 * @since 2.2.0
	 */
	public function third_party_init() {

		add_filter( 'wpbuddy/rich_snippets/fields/internal_subselect/values', array(
			'wpbuddy\rich_snippets\YoastSEO_Model',
			'internal_subselect',
		) );

		add_filter( 'wpbuddy/rich_snippets/fields/internal_subselect/values', array(
			'wpbuddy\rich_snippets\WooCommerce_Model',
			'internal_subselect',
		) );

		add_filter( 'wpbuddy/rich_snippets/fields/internal_subselect/values', array(
			'wpbuddy\rich_snippets\MiscFields_Model',
			'internal_subselect',
		) );

		add_action(
			'wpbuddy/rich_snippets/rest/property/html/fields',
			array(
				'wpbuddy\rich_snippets\MiscFields_Model',
				'fields',
			),
			10, 1
		);
	}

}
