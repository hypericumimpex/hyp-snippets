<?php

namespace wpbuddy\rich_snippets;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly


/**
 * Class Settings.
 *
 * Admin settings actions.
 *
 * @package wpbuddy\rich_snippets
 *
 * @since   2.0.0
 */
final class Admin_Settings_Controller {


	/**
	 * Admin_Settings_Controller constructor.
	 *
	 * @since 2.0.0
	 */
	public function __construct() {

		# add scripts and styles to settings menu
		add_action( 'admin_enqueue_scripts', array( $this, 'scripts' ) );

		# setup settings
		$this->register_settings();

		# setup meta boxes
		$this->add_metaboxes();

		do_action_ref_array( 'wpbuddy/rich_snippets/backend/settings/init', array( $this ) );
	}


	/**
	 * Enqueues scripts and styles for the settings page.
	 *
	 * @since 2.0.0
	 */
	public function scripts() {

		wp_enqueue_style(
			'wpb-rs-admin-settings',
			plugins_url( 'css/admin-settings.css', rich_snippets()->get_plugin_file() ),
			[],
			filemtime( plugin_dir_path( rich_snippets()->get_plugin_file() ) . 'css/admin-settings.css' )
		);

		wp_enqueue_script(
			'wpb-rs-admin-settings',
			plugins_url( 'js/admin-settings.js', rich_snippets()->get_plugin_file() ),
			array( 'jquery' ),
			filemtime( plugin_dir_path( rich_snippets()->get_plugin_file() ) . 'js/admin-settings.js' ),
			true
		);

		$args = call_user_func( function () {

			$o           = new \stdClass();
			$o->nonce    = wp_create_nonce( 'wp_rest' );
			$o->rest_url = rest_url( 'wpbuddy/rich_snippets/v1' );

			$o->translations = new \stdClass();

			$o->translations->schema_updates_success = sprintf(
				_x( 'All done', 'Message when snippets have been updated.', 'rich-snippets-schema' ),
				'<span class="dashicons dashicons-smiley"></span>'
			);

			return $o;
		} );

		wp_add_inline_script( 'wpb-rs-admin-settings', "var WPB_RS_SETTINGS = " . \json_encode( $args ) . ";", 'before' );
	}


	/**
	 * Register all settings.
	 *
	 * @since 2.0.0
	 */
	public function register_settings() {

		$settings = self::get_settings();

		foreach ( $settings as $section ) {
			$section_id = sprintf( 'wpbrs_section_%s', $section->id );
			add_settings_section(
				$section_id,
				$section->title,
				'',
				Admin_Controller::instance()->menu_settings_hook
			);

			foreach ( $section->get_settings() as $s ) {
				$settings_id = empty( $s->name ) ? $s->id : $s->get_option_name();
				add_settings_field(
					$settings_id,
					$s->title,
					array( $s, 'render' ),
					Admin_Controller::instance()->menu_settings_hook,
					$section_id,
					array(
						'page_hook' => Admin_Controller::instance()->menu_settings_hook,
						'label_for' => $s->get_option_name(),
						'section'   => $section,
						'setting'   => $s,
					)
				);

				if ( empty( $s->name ) ) {
					continue;
				}

				register_setting(
					'rich-snippets-settings',
					$settings_id,
					array( 'sanitize_callback' => $s->sanitize_callback )
				);
			}
		}

	}


	/**
	 * Generate settings and their sections.
	 *
	 * @since 2.0.0
	 *
	 * @return \wpbuddy\rich_snippets\Settings_Section[]
	 */
	public static function get_settings() {

		$settings = [];

		/**
		 * Frontend settings
		 */
		$frontend = new Settings_Section( array(
			'title' => _x( 'Frontend', 'settings section title', 'rich-snippets-schema' ),
		) );

		$frontend->add_setting( array(
			'label'             => __( 'Try to remove "hentry" CSS class from posts.', 'rich-snippets-schema' ),
			'title'             => __( 'Posts', 'rich-snippets-schema' ),
			'type'              => 'checkbox',
			'name'              => 'remove_hentry',
			'default'           => true,
			'sanitize_callback' => array( Helper_Model::instance(), 'string_to_bool' ),
			'autoload'          => true,
			'description'       => sprintf( '<a href="https://rich-snippets.io/hentry-css-class/" target="_blank">%s</a>', __( 'Click here for more information.', 'rich-snippets-schema' ) ),
		) );

		$frontend->add_setting( array(
			'label'             => __( 'Try to remove "vcard" CSS class from comments.', 'rich-snippets-schema' ),
			'title'             => __( 'Comments', 'rich-snippets-schema' ),
			'type'              => 'checkbox',
			'name'              => 'remove_vcard',
			'default'           => true,
			'sanitize_callback' => array( Helper_Model::instance(), 'string_to_bool' ),
			'autoload'          => true,
			'description'       => sprintf( '<a href="https://rich-snippets.io/hentry-css-class/" target="_blank">%s</a>', __( 'Click here for more information.', 'rich-snippets-schema' ) ),
		) );

		$frontend->add_setting( array(
			'label'             => __( 'Move snippet output to footer', 'rich-snippets-schema' ),
			'title'             => __( 'Snippet Output', 'rich-snippets-schema' ),
			'type'              => 'checkbox',
			'name'              => 'snippets_in_footer',
			'default'           => false,
			'sanitize_callback' => array( Helper_Model::instance(), 'string_to_bool' ),
			'autoload'          => true,
			'description'       => __( 'If you\'re using a lot of snippet on one page it\'s probably a good idea to move the output to the footer.', 'rich-snippets-schema' ),
		) );

		if ( function_exists( 'WC' ) ) {
			$frontend->add_setting( array(
				'label'             => __( 'Remove WooCommerce schema', 'rich-snippets-schema' ),
				'title'             => __( 'WooCommerce', 'rich-snippets-schema' ),
				'type'              => 'checkbox',
				'name'              => 'remove_wc_schema',
				'default'           => false,
				'sanitize_callback' => array( Helper_Model::instance(), 'string_to_bool' ),
				'autoload'          => true,
				'description'       => __( 'WooCommerce adds its own schema.org syntax for products. If you don\'t want to use it, the plugin can try to remove it for you so that you can write your own Rich Snippets for products.', 'rich-snippets-schema' ),
			) );
		}


		$settings[] = $frontend;
		unset( $frontend );


		/**
		 * Backend
		 */
		$backend = new Settings_Section( array(
			'title' => _x( 'Backend', 'settings section title', 'rich-snippets-schema' ),
		) );

		$post_types = get_post_types( array(
			'public' => true,
		), 'objects' );

		$post_types = apply_filters( 'wpbuddy/rich_snippets/settings/allowed_post_types', $post_types );

		$backend->add_setting( array(
			'title'             => __( 'Post Types', 'rich-snippets-schema' ),
			'type'              => 'select',
			'name'              => 'post_types',
			'multiple'          => true,
			'default'           => array( 'post', 'page' ),
			'options'           => wp_list_pluck( $post_types, 'label', 'name' ),
			'sanitize_callback' => array( Helper_Model::instance(), 'sanitize_text_in_array' ),
			'autoload'          => true,
			'description'       => __( 'Please select the post types where you want to create Rich Snippets.', 'rich-snippets-schema' ),
		) );

		$settings[] = $backend;
		unset( $backend );


		/**
		 * Actions
		 */
		$actions = new Settings_Section( array(
			'title' => _x( 'Actions', 'settings section title', 'rich-snippets-schema' ),
		) );

		if ( true === (bool) get_option( 'wpb_rs/predefined/message/hidden', false ) ) {
			$actions->add_setting( array(
				'title'       => __( 'Reinstall predefined global snippets.', 'rich-snippets-schema' ),
				'label'       => __( 'Go for it!', 'rich-snippets-schema' ),
				'type'        => 'button',
				'href'        => admin_url( 'edit.php?post_type=wpb-rs-global&install_predefined=1&_wpnonce=' ) . wp_create_nonce( 'wpbrs_install_predefined' ),
				'description' => __( 'The plugin comes shipped with pre-installed snippets. If you messed them up, just hit the above button to re-install and/or repair them.', 'rich-snippets-schema' ),
			) );
		}

		$actions->add_setting( array(
			'title'       => __( 'Cache', 'rich-snippets-schema' ),
			'label'       => __( 'Clear cache', 'rich-snippets-schema' ),
			'type'        => 'button',
			'href'        => '#',
			'class'       => array( 'wpb-rs-clear-cache' ),
			'description' => __( 'The plugin uses WordPress\' internal caching mechanism to speed things up. If you experience weired behaviour, hit the above button to clear all caches.', 'rich-snippets-schema' ),
		) );

		$settings[] = $actions;
		unset( $actions );

		return $settings;
	}


	/**
	 * Add metaboxes for the settings page.
	 *
	 * @since 2.0.0
	 */
	public function add_metaboxes() {

		add_meta_box(
			'settings-general',
			__( 'Settings', 'rich-snippets-schema' ),
			array( '\wpbuddy\rich_snippets\View', 'admin_settings_metabox_general' ),
			'rich-snippets-settings',
			'normal'
		);

		add_meta_box(
			'settings-help',
			__( 'Help', 'rich-snippets-schema' ),
			array( '\wpbuddy\rich_snippets\View', 'admin_snippets_metabox_help' ),
			'rich-snippets-settings',
			'side'
		);

		add_meta_box(
			'settings-news',
			_x( 'News', 'metabox title', 'rich-snippets-schema' ),
			array( '\wpbuddy\rich_snippets\View', 'admin_snippets_metabox_news' ),
			'rich-snippets-settings',
			'side',
			'low'
		);
	}


	/**
	 * Prepare settings.
	 *
	 * This function is called during activation to make sure settings are autoloaded correctly.
	 *
	 * @see   Rich_Snippets_Plugin::on_activation()
	 *
	 * @since 2.0.0
	 */
	public static function prepare_settings() {

		$settings = self::get_settings();

		foreach ( $settings as $section ) {
			foreach ( $section->get_settings() as $s ) {
				if ( empty( $s->name ) ) {
					continue;
				}

				add_option( $s->name, $s->default, '', $s->autoload );
			}
		}
	}
}
