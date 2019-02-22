<?php

namespace wpbuddy\rich_snippets;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly


/**
 * Class Admin.
 *
 * Starts up all the admin things.
 *
 * @package wpbuddy\rich_snippets
 *
 * @since   2.0.0
 */
final class Admin_Controller {

	/**
	 * The instance.
	 *
	 * @var Admin_Controller
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
	 * The main menu hook.
	 *
	 * @var string
	 *
	 * @since 2.0.0
	 */
	public $intro_hook = '';


	/**
	 * The snippets menu hook.
	 *
	 * @var string
	 *
	 * @since 2.0.0
	 */
	public $menu_settings_hook = '';


	/**
	 * The support menu hook.
	 *
	 * @since 2.3.0
	 *
	 * @var string
	 */
	public $menu_support_hook = '';


	/**
	 * The globalsnippets menu hook.
	 *
	 * @since 2.4.0
	 *
	 * @var string
	 */
	public $menu_globalsnippets_hook = '';


	/**
	 * Get the singleton instance.
	 *
	 * Creates a new instance of the class if it does not exists.
	 *
	 * @return   Admin_Controller
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
	 * @param $name
	 * @param $arguments
	 *
	 * @since 2.0.0
	 *
	 * @return bool|mixed
	 */
	public static function __callStatic( $name, $arguments ) {

		$instance = self::instance();

		if ( method_exists( self::instance(), $name ) ) {
			return call_user_func_array( array( $instance, $name ), $arguments );
		}

		return false;
	}


	/**
	 * Initializes admin stuff
	 *
	 * @since 2.0.0
	 */
	public function init() {

		if ( $this->initialized ) {
			return;
		}

		# perform upgrades, if any.
		add_action( 'init', array( self::$_instance, 'check_upgrades' ) );

		# creates dashboard menu items
		# @note that this priority must be 9 or lower so that custom post types do not replace it.
		# @see show_in_menu arg @see https://codex.wordpress.org/Function_Reference/register_post_type
		add_action( 'admin_menu', array( self::$_instance, 'menu' ), 9 );

		# add scripts and styles to admin intro page
		add_action( 'wpbuddy/rich_snippets/admin_menu_intro', array( self::instance(), 'menu_intro_scripts' ) );

		add_action( 'admin_enqueue_scripts', array( self::instance(), 'admin_scripts' ) );

		add_action( base64_decode( 'YWRtaW5fbm90aWNlcw==' ), array(
			self::$_instance,
			base64_decode( 'Ym05MFgzWmxjbWxtYVdWa1gyMWxjM05oWjJV' ),
		) );

		add_filter( 'extra_plugin_headers', array( self::$_instance, 'extra_plugin_headers' ) );

		add_action( 'load-post.php', array( self::$_instance, 'load_controllers' ) );
		add_action( 'load-post-new.php', array( self::$_instance, 'load_controllers' ) );
		add_action( 'load-edit.php', array( self::$_instance, 'load_controllers' ) );
		add_action( 'load-options.php', array( self::$_instance, 'load_controllers' ) );
		add_action( 'wpbuddy/rich_snippets/admin_menu', function () {

			$admin = Admin_Controller::instance();
			add_action( 'load-' . $admin->menu_settings_hook, array( $admin, 'load_controllers' ) );
			add_action( 'load-' . $admin->menu_support_hook, array( $admin, 'load_controllers' ) );
		} );

		add_action( 'save_post', array( self::$_instance, 'save_snippets' ), 10, 1 );
		add_action( 'save_post_wpb-rs-global', array( self::$_instance, 'save_positions' ), 10, 1 );
		add_action( 'save_post_wpb-rs-global', array( self::$_instance, 'save_jsonld' ), 10, 1 );

		add_filter( 'plugins_api', array(
			'\wpbuddy\rich_snippets\Update_Model',
			'update_window_information',
		), 10, 3 );

		add_action( 'plugins_loaded', array( self::instance(), 'load_translations' ) );

		add_filter( 'add_menu_classes', array( self::$_instance, 'dashboard_menu_classes' ) );

		//		add_action( 'admin_init', array( self::instance(), 'setup_wizard' ) );

		add_action( 'admin_notices', array( self::$_instance, 'admin_global_snippets_notice' ) );

		add_action( 'admin_footer', array( self::$_instance, 'error_footer' ) );

		$this->initialized = true;
	}


	/**
	 * Checks for upgrades.
	 *
	 * @since 2.0.0
	 */
	public function check_upgrades() {

		if ( false !== boolval( get_option( 'wpb_rs/upgraded', false ) ) ) {
			return;
		}

		update_option( 'wpb_rs/upgraded', true, 'yes' );

		Upgrade_Model::do_upgrades();
	}


	/**
	 * Creates the main menu.
	 *
	 * @since 2.0.0
	 */
	public function menu() {

		$this->intro_hook = add_menu_page(
			_x( 'Rich Snippets', 'Main page title', 'rich-snippets-schema' ),
			_x( 'snip / Structured Data', 'Main menu title', 'rich-snippets-schema' ),
			apply_filters( 'wpbuddy/rich_snippets/capability_main_menu', 'manage_options' ),
			'rich-snippets-schema',
			array( 'wpbuddy\rich_snippets\View', 'admin-intro' ),
			'data:image/svg+xml;base64,' . base64_encode( '<svg xmlns="http://www.w3.org/2000/svg" width="1041" height="1013.031" viewBox="0 0 1041 1013.031"><path fill="#fff" d="M332.672,244.708q-97.679,78.74-97.667,206.327,0,127.611,80.175,184.456,80.152,56.867,236.151,71.448v35a907.63,907.63,0,0,1-175.656-16.769Q290.4,708.42,249.582,687.984V1004.4l33.528,13.12q33.516,13.125,125.364,26.25t209.912,13.12q221.564,0,310.5-71.445,88.9-71.427,88.919-191.017,0-119.544-67.053-164.77-67.067-45.18-247.813-62.7v-35q196.793,0,295.918,51.035V218.462l-33.528-13.124q-34.984-13.124-127.551-26.246-92.587-13.124-207.725-13.124Q430.317,165.968,332.672,244.708Z" transform="translate(-235 -165.969)"></path><rect fill="#fff" x="16" y="973.031" width="1025" height="40"></rect></svg>' )
		);

		add_submenu_page(
			'rich-snippets-schema',
			_x( 'Let\'s start', 'Main page title', 'rich-snippets-schema' ),
			_x( 'Let\'s start', 'First menu title: Lets start', 'rich-snippets-schema' ),
			apply_filters( 'wpbuddy/rich_snippets/capability_menu_activation', 'manage_options' ),
			'rich-snippets-schema',
			array( 'wpbuddy\rich_snippets\View', 'admin-intro' )
		);

		do_action( 'wpbuddy/rich_snippets/admin_menu_intro', $this->intro_hook );

		$this->menu_settings_hook = add_submenu_page(
			'rich-snippets-schema',
			_x( 'Settings', 'Settings page title', 'rich-snippets-schema' ),
			_x( 'Settings', 'Settings menu title', 'rich-snippets-schema' ),
			apply_filters( 'wpbuddy/rich_snippets/capability_menu_settings', 'manage_options' ),
			'rich-snippets-settings',
			array( 'wpbuddy\rich_snippets\View', 'admin-settings' )
		);

		do_action( 'wpbuddy/rich_snippets/admin_menu_settings', $this->menu_settings_hook );

		if ( Helper_Model::instance()->magic() ) {
			$this->menu_support_hook = add_submenu_page(
				'rich-snippets-schema',
				_x( 'Support', 'Support page title', 'rich-snippets-schema' ),
				_x( 'Support', 'Support menu title', 'rich-snippets-schema' ),
				apply_filters( 'wpbuddy/rich_snippets/capability_menu_support', 'manage_options' ),
				'rich-snippets-support',
				array( 'wpbuddy\rich_snippets\View', 'admin-support' )
			);

			do_action( 'wpbuddy/rich_snippets/admin_menu_support', $this->menu_support_hook );

			$this->menu_globalsnippets_hook = add_submenu_page(
				'rich-snippets-schema',
				__( 'Global Snippets', 'rich-snippets-schema' ),
				__( 'Global Snippets', 'rich-snippets-schema' ),
				apply_filters( 'wpbuddy/rich_snippets/capability_menu_globalsnippets', 'manage_options' ),
				'rich-snippets-globalsnippets',
				'__return_empty_string'
			);

			add_action( 'load-' . $this->menu_globalsnippets_hook, function () {

				# redirect to the global post section
				wp_redirect( admin_url( 'edit.php?post_type=wpb-rs-global' ) );
			} );

			do_action( 'wpbuddy/rich_snippets/admin_menu_globalsnippets', $this->menu_globalsnippets_hook );
		}

		do_action_ref_array( 'wpbuddy/rich_snippets/admin_menu', [ $this ] );

	}


	/**
	 * Adds scripts and styles to the intro admin page.
	 *
	 * @param string $hook
	 *
	 * @since 2.0.0
	 */
	public function menu_intro_scripts( $hook ) {

		add_action( 'admin_enqueue_scripts', function ( $hook_suffix ) {

			if ( 'toplevel_page_rich-snippets-schema' !== $hook_suffix ) {
				return;
			}

			wp_enqueue_style(
				'wpb-rs-admin-intro',
				plugins_url( 'css/admin-intro.css', rich_snippets()->get_plugin_file() ),
				array( 'common' ),
				filemtime( plugin_dir_path( rich_snippets()->get_plugin_file() ) . 'css/admin-intro.css' )
			);

			wp_enqueue_script(
				'wpb-rs-admin-intro',
				plugins_url( 'js/admin-intro.js', rich_snippets()->get_plugin_file() ),
				[],
				filemtime( plugin_dir_path( rich_snippets()->get_plugin_file() ) . 'js/admin-intro.js' )
			);

			$args = call_user_func( function () {

				$o               = new \stdClass();
				$o->nonce        = wp_create_nonce( 'wp_rest' );
				$o->rest_url     = rest_url( 'wpbuddy/rich_snippets/v1' );
				$o->redirect_url = admin_url( 'admin.php?page=rich-snippets-schema&tab=training' );

				$o->translations                            = new \stdClass();
				$o->translations->activated                 = __( 'Hurray! Your copy of this plugins is active! Please wait ...', 'rich-snippets-schema' );
				$o->translations->activation_no_content_err = __( 'The request did not return any data. Error code: %d.', 'rich-snippets-schema' );

				return $o;
			} );

			wp_add_inline_script( 'wpb-rs-admin-intro', "var WPB_RS_ADMIN = " . \json_encode( $args ) . ";", 'before' );

		} );
	}


	/**
	 * Loads other controllers, if necessary.
	 *
	 * @since 2.0.0
	 */
	public function load_controllers() {

		$post_type = Helper_Model::instance()->get_current_admin_post_type();

		$post_types = (array) get_option( 'wpb_rs/setting/post_types', array( 'post', 'page' ) );

		# on all post types but not the wpb-rs-global
		if ( in_array( $post_type, $post_types ) ) {
			Admin_Snippets_Overwrite_Controller::instance()->init();
		}

		$post_types[] = 'wpb-rs-global';

		# on all post types including wpb-rs-global
		if ( in_array( $post_type, $post_types ) ) {
			Admin_Snippets_Controller::instance()->init();
		}

		# only on wpb-rs-global
		if ( 'wpb-rs-global' === $post_type ) {
			Admin_Position_Controller::instance()->init();
		}

		$is_settings_page = call_user_func( function () {

			if ( ! function_exists( 'get_current_screen' ) ) {
				return false;
			}

			$screen = get_current_screen();

			if ( $screen->id === 'options' ) {
				return true;
			}

			if ( $screen->id === $this->menu_settings_hook ) {
				return true;
			}

			return false;
		} );

		# only on settings page
		if ( $is_settings_page ) {
			new Admin_Settings_Controller();
		}

		$is_support_page = call_user_func( function () {

			if ( ! function_exists( 'get_current_screen' ) ) {
				return false;
			}

			$screen = get_current_screen();

			if ( $screen->id === 'options' ) {
				return true;
			}

			if ( $screen->id === $this->menu_support_hook ) {
				return true;
			}

			return false;
		} );

		# only on support page
		if ( $is_support_page ) {
			new Admin_Support_Controller();
		}
	}


	/**
	 * A nice function.
	 *
	 * Before changing anything here, please consider that it was hard work to create this plugin.
	 *
	 * @since 2.0.0
	 */
	public function bm90X3ZlcmlmaWVkX21lc3NhZ2U() {

		if ( call_user_func( [ Helper_Model::instance(), base64_decode( 'bWFnaWM=' ) ] ) ) {
			return;
		}

		$screen = get_current_screen();

		if ( $screen instanceof \WP_Screen && base64_decode( 'dG9wbGV2ZWxfcGFnZV9yaWNoLXNuaXBwZXRzLXNjaGVtYQ==' ) === $screen->id ) {
			return;
		}

		$pd = call_user_func( base64_decode( 'Z2V0X3BsdWdpbl9kYXRh' ), rich_snippets()->get_plugin_file() );

		printf(
			'<div class="%s"><p>%s</p><p><a href="%s" class="button small">%s</a></p></div>',
			base64_decode( 'bm90aWNlIG5vdGljZS13YXJuaW5nIG5vdGljZS1hbHQgd3BiLXJzcy1ub3QtYWN0aXZlLWluZm8=' ),
			$pd[ base64_decode( 'Tm90QWN0aXZlV2FybmluZw==' ) ],
			esc_url( admin_url( 'admin.php?page=rich-snippets-schema' ) ),
			$pd[ base64_decode( 'QWN0aXZhdGVOb3c=' ) ]
		);
	}


	/**
	 * Adds extra plugin headers.
	 *
	 * @since 2.0.0
	 *
	 * @param array $headers
	 *
	 * @return array
	 */
	public function extra_plugin_headers( $headers ) {

		__( 'Your copy of SNIP has not yet been activated.' );
		$headers['NotActiveWarning'] = 'NotActiveWarning';

		__( 'Activate it now.' );
		$headers['ActivateNow'] = 'ActivateNow';

		__( 'Your copy is active.' );
		$headers['Active'] = 'Active';

		return $headers;
	}


	/**
	 * Adds scripts and styles for all admin pages.
	 *
	 * @since 2.0.0
	 */
	public function admin_scripts() {

		wp_enqueue_style(
			'wpb-rs-admin',
			plugins_url( 'css/admin.css', rich_snippets()->get_plugin_file() ),
			array( 'admin-menu' ),
			filemtime( plugin_dir_path( rich_snippets()->get_plugin_file() ) . 'css/admin.css' )
		);
	}


	/**
	 * Saves a schema.org post.
	 *
	 * @since 2.0.0
	 *
	 * @see   Admin_Snippets_Controller::save_snippets()
	 *
	 * @param int $post_id
	 */
	public function save_snippets( $post_id ) {

		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			return;
		}

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		Admin_Snippets_Controller::instance()->save_snippets( $post_id );
	}


	/**
	 * Saves a position metabox content.
	 *
	 * @since 2.0.0
	 *
	 * @see   Admin_Position_Controller::save_positions()
	 *
	 * @param int $post_id
	 */
	public function save_positions( $post_id ) {

		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			return;
		}

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		Admin_Position_Controller::instance()->save_positions( $post_id );
	}


	/**
	 * Saves the JSON+LD data.
	 *
	 * @since 2.4.0
	 *
	 * @param int $post_id
	 */
	public function save_jsonld( $post_id ) {

		Admin_Snippets_Controller::instance()->save_json_ld_data( $post_id );
	}


	/**
	 * Loads translation files.
	 *
	 * @since 2.0.0
	 */
	public function load_translations() {

		$rel_path = str_replace(
			WP_PLUGIN_DIR,
			'',
			dirname( rich_snippets()->get_plugin_file() )
		);

		load_plugin_textdomain( 'rich-snippets-schema', false, $rel_path . '/languages' );
	}


	/**
	 * Adds a CSS class to the Rich Snippet menu (to modify the SVG icon)
	 *
	 * @since 2.0.0
	 *
	 * @param array $menu
	 *
	 * @return array
	 */
	public function dashboard_menu_classes( $menu ) {

		foreach ( $menu as $order => $m ) {
			if ( ! isset( $m[2] ) ) {
				continue;
			}

			if ( 'rich-snippets-schema' === $m[2] ) {
				$menu[ $order ][4] .= ' wpb-rs-dashboard-menu ';
			}
		}

		return $menu;
	}


	/**
	 * Shows a help message on the global snippet edit screen.
	 *
	 * @since 2.0.0
	 */
	public function admin_global_snippets_notice() {

		if ( ! function_exists( 'get_current_screen' ) ) {
			return;
		}

		$screen = get_current_screen();

		if ( 'edit-wpb-rs-global' !== $screen->id ) {
			return;
		}

		printf(
			'<div class="notice notice-info"><p>%s</p></div>',
			sprintf(
				__( 'Use this global snippet section if you don\'t want to create a single snippet for each post. Instead you can define one snippet that is valid globally. Use the position metabox to define a ruleset where a global snippet should be integrated. You can <a href="https://rich-snippets.io/structured-data/module-2/lesson-2/?pk_campaign=global-snippets-overview&pk_source=%1$s" target="_blank">learn more about Global Snippets in module 2 / lesson 2</a> of the <a href="https://rich-snippets.io/structured-data-training-course/?pk_campaign=global-snippets-overview&pk_source=%1$s" target="_blank">Structured Data Training Course</a>.', 'rich-snippets-schema' ),
				Helper_Model::instance()->get_site_url_host()
			)
		);
	}


	/**
	 * Prints a DIV for error reporting.
	 *
	 * @since 2.0.0
	 * @since 2.2.0 Moved from Admin_Snippets_Controller class.
	 */
	public function error_footer() {

		printf( '<div class="wpb-rs-errors"></div>' );
	}
}
