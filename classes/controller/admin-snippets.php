<?php

namespace wpbuddy\rich_snippets;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly


/**
 * Class Admin_Snippets_Controller.
 *
 * Starts up all the admin things needed to control snippets.
 *
 * @package wpbuddy\rich_snippets
 *
 * @since   2.0.0
 */
final class Admin_Snippets_Controller {

	/**
	 * The instance.
	 *
	 * @var Admin_Snippets_Controller
	 *
	 * @since 2.0.0
	 */
	protected static $instance = null;


	/**
	 * If this instance has been initialized already.
	 *
	 * @var bool
	 */
	protected $initialized = false;

	/**
	 * Get the singleton instance.
	 *
	 * Creates a new instance of the class if it does not exists.
	 *
	 * @return   Admin_Snippets_Controller
	 *
	 * @since 2.0.0
	 */
	public static function instance() {

		if ( null === self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
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
	 * Magic method for setting up the class.
	 *
	 * Disallow external instances.
	 *
	 * @since 2.0.0
	 */
	protected function __construct() {
	}


	/**
	 * The fields model.
	 *
	 * @since 2.0.0
	 * @var Fields_Model|null
	 */
	private $fields = null;


	/**
	 * Init.
	 *
	 * @since 2.0.0
	 */
	public function init() {

		if ( $this->initialized ) {
			return;
		}

		add_action( 'add_meta_boxes', array( self::$instance, 'add_meta_boxes' ), 11, 2 );

		add_action( 'admin_enqueue_scripts', array( self::$instance, 'enqueue_scripts_styles' ), 20 );

		add_filter( 'wpbuddy/rich_snippets/save_snippet/property/sanitize', 'sanitize_text_field' );

		add_filter( 'post_row_actions', array( self::$instance, 'filter_row_actions' ), 10, 2 );

		add_action( 'post_submitbox_misc_actions', array( self::$instance, 'submitbox_js' ) );

		add_action( 'admin_notices', array( self::$instance, 'predefined_notice' ) );

		add_filter( 'manage_wpb-rs-global_posts_columns', [ self::$instance, 'manage_posts_columns' ] );

		add_action( 'manage_wpb-rs-global_posts_custom_column', [ self::$instance, 'print_custom_columns' ], 10, 2 );

		$this->install_predefined();

		$this->initialized = true;
	}


	/**
	 *
	 * Creates metaboxes.
	 *
	 * @param string   $post_type
	 * @param \WP_Post $post
	 *
	 * @since 2.0.0
	 */
	public function add_meta_boxes( $post_type, $post ) {

		# the main metabox
		add_meta_box(
			'wp-rs-mb-main',
			_x( 'Structured data', 'metabox title', 'rich-snippets-schema' ),
			array( self::$instance, 'render_snippets_meta_box' ),
			'wpb-rs-global',
			'advanced',
			'high'
		);

		if ( Helper_Model::instance()->magic() ) {
			add_meta_box(
				'wp-rs-mb-post',
				_x( 'Rich Snippets', 'metabox title', 'rich-snippets-schema' ),
				array( '\wpbuddy\rich_snippets\View', 'admin_posts_metabox' ),
				(array) get_option( 'wpb_rs/setting/post_types', array( 'post', 'page' ) ),
				'advanced',
				'low'
			);
		}

		add_meta_box(
			'wp-rs-mb-help',
			_x( 'Help', 'metabox title', 'rich-snippets-schema' ),
			array( '\wpbuddy\rich_snippets\View', 'admin_snippets_metabox_help' ),
			'wpb-rs-global',
			'side',
			'low'
		);

		add_meta_box(
			'wp-rs-mb-jsonld',
			_x( 'JSON+LD', 'metabox title', 'rich-snippets-schema' ),
			array( '\wpbuddy\rich_snippets\View', 'admin_snippets_metabox_jsonld' ),
			'wpb-rs-global',
			'side',
			'low'
		);

		add_meta_box(
			'wp-rs-mb-news',
			_x( 'News', 'metabox title', 'rich-snippets-schema' ),
			array( '\wpbuddy\rich_snippets\View', 'admin_snippets_metabox_news' ),
			'wpb-rs-global',
			'side',
			'low'
		);

		do_action( 'wpbuddy/rich_snippets/schemas/metaboxes', $post_type, $post );
	}


	/**
	 * Adds scripts and styles.
	 *
	 * @since 2.0.0
	 */
	public function enqueue_scripts_styles() {

		Admin_Scripts_Controller::instance()->enqueue_snippets_styles();
		Admin_Scripts_Controller::instance()->enqueue_snippets_scripts();

		$post_type = Helper_Model::instance()->get_current_admin_post_type();

		$post_types = (array) get_option( 'wpb_rs/setting/post_types', array( 'post', 'page' ) );

		if ( in_array( $post_type, $post_types ) ) {
			Admin_Scripts_Controller::instance()->enqueue_posts_forms_scripts();
		}

		$post_types[] = 'wpb-rs-global';

		if ( in_array( $post_type, $post_types ) ) {
			Admin_Scripts_Controller::instance()->enqueue_posts_scripts();
		}

		do_action( 'wpbuddy/rich_snippets/schemas/scripts' );
	}


	/**
	 * Renders the meta box.
	 *
	 * @since 2.0.0
	 *
	 * @param \WP_Post $post
	 * @param array    $metabox
	 */
	public function render_snippets_meta_box( $post, $metabox ) {

		$snippet = Snippets_Model::get_first_snippet( (int) $post->ID );

		View::admin_snippets_metabox_snippet( $snippet, $post );

	}


	/**
	 * Saves extra JSON+LD snippet data.
	 *
	 * @since 2.4.0
	 *
	 * @param int $post_id
	 */
	public function save_json_ld_data( $post_id ) {

		$json_ld_data = (array) get_post_meta( $post_id, '_wpb_rs_jsonld', true );

		if ( isset( $_POST['wpb_rs_jsonld_id'] ) ) {
			$json_ld_data['@id'] = sanitize_text_field( $_POST['wpb_rs_jsonld_id'] );
		} else {
			$json_ld_data['@id'] = '';
		}

		$json_ld_data = array_filter( $json_ld_data );

		update_post_meta( $post_id, '_wpb_rs_jsonld', $json_ld_data );
	}


	/**
	 * Generates a snippet from post data.
	 *
	 * @param array $post_data
	 *
	 * @since 2.5.4
	 *
	 * @return Rich_Snippet[]
	 */
	public function generate_snippets( $post_data ) {
		$built_snippets = array();

		array_walk( $post_data, array( $this, 'sanitize_schema' ) );

		$snippets = array_diff_key( $post_data, $this->fetch_references( $post_data ) );

		foreach ( $snippets as $snippet_id => $snippet ) {
			$built_snippets[ $snippet_id ] = $this->create_snippet( $post_data, $snippet_id, true );
		}

		return $built_snippets;
	}


	/**
	 * Saves a schema from the builder to the database.
	 *
	 * @param int $post_id
	 *
	 * @since 2.0.0
	 *
	 */
	public function save_snippets( $post_id ) {

		if ( ! isset( $_POST['snippets'] ) ) {
			return;
		}

		if ( ! is_array( $_POST['snippets'] ) ) {
			return;
		}

		/**
		 * Cache clearings
		 */

		if ( 'wpb-rs-global' === get_post_type( $post_id ) ) {
			Cache_Model::clear_global_snippets_ids();
			Cache_Model::clear_all_snippets();
		}

		Cache_Model::clear_singular_snippet( $post_id );

		/**
		 * Save snippets
		 */

		$snippets = $this->generate_snippets( $_POST['snippets'] );

		Snippets_Model::update_snippets( $post_id, $snippets );

		do_action( 'wpbuddy/rich_snippets/save_snippet' );
	}


	/**
	 * Fetches all reference snippets_ids.
	 *
	 * @param $snippets
	 *
	 * @since 2.0.0
	 *
	 * @return array Array of snippet ids.
	 */
	private function fetch_references( $snippets ) {

		$refs = array();

		foreach ( $snippets as $snippet ) {
			if ( ! isset( $snippet['properties'] ) ) {
				continue;
			}

			if ( ! is_array( $snippet['properties'] ) ) {
				continue;
			}

			foreach ( $snippet['properties'] as $prop ) {
				if ( ! isset( $prop['ref'] ) ) {
					continue;
				}

				if ( empty( $prop['ref'] ) ) {
					continue;
				}

				$refs[ $prop['ref'] ] = '';
			}
		}

		return $refs;

	}

	/**
	 * Sanitizes schema values sent via the form.
	 *
	 * @param array $schema
	 *
	 * @since 2.0.0
	 *
	 * @return array
	 */
	private function sanitize_schema( $schema ) {

		if ( ! isset( $schema['id'] ) ) {
			return array();
		}

		$schema['id'] = sanitize_text_field( $schema['id'] );

		if ( ! isset( $schema['properties'] ) ) {
			return array();
		}

		foreach ( $schema['properties'] as $property_uid => $property_values ) {
			$property_uid = sanitize_text_field( $property_uid );

			foreach ( $property_values as $property_label => $property_value ) {
				$property_label = sanitize_text_field( $property_label );

				$property_value = apply_filters( 'wpbuddy/rich_snippets/save_snippet/property/sanitize', $property_value );

				$schema['properties'][ $property_uid ][ $property_label ] = $property_value;
			}
		}


		return $schema;
	}


	/**
	 * Processes an array of classes to a single Rich_Snippet object.
	 *
	 * @param array  $schemas
	 * @param string $snippet_id
	 * @param bool   $is_parent If this is a parent snippet.
	 *
	 * @since 2.0.0
	 * @since 2.5.4 added $is_parent parameter.
	 *
	 * @return Rich_Snippet
	 */
	private function create_snippet( &$schemas, $snippet_id = 'main', $is_parent = false ) {

		$file = ABSPATH . '/wp-admin/includes/plugin.php';

		if ( ! function_exists( '\get_plugin_data' ) && is_file( $file ) ) {
			require_once $file;
		}

		$plugin_data = function_exists( '\get_plugin_data' )
			? get_plugin_data( rich_snippets()->get_plugin_file(), false, false )
			: null;

		$snippet = new Rich_Snippet( [
			'_is_main_snippet' => $is_parent,
			'_version_created' => $plugin_data['Version'] ?? null,
			'_loop'            => isset( $schemas[ $snippet_id ]['loop'] ) ? $schemas[ $snippet_id ]['loop'] : null
		] );

		if ( ! isset( $schemas[ $snippet_id ] ) ) {
			return $snippet;
		}

		$snippet->type = Helper_Model::instance()->remove_schema_url( $schemas[ $snippet_id ]['id'] );

		if ( ! isset( $schemas[ $snippet_id ]['properties'] ) ) {
			return $snippet;
		}

		$snippet->id = $snippet_id;

		$props = array();

		foreach ( $schemas[ $snippet_id ]['properties'] as $property_uid => $property_values ) {
			$p_label = Helper_Model::instance()->remove_schema_url( $property_values['id'] );

			$p_subfield = $property_values['subfield_select'];

			if ( isset( $property_values['ref'] ) && ! empty( $property_values['ref'] ) && isset( $schemas[ $property_values['ref'] ] ) ) {
				$p_value = $this->create_snippet( $schemas, $property_values['ref'] );
			} elseif ( false !== stripos( $p_subfield, 'textfield' ) ) {
				$p_value = sanitize_text_field( $property_values['textfield'] );
			} elseif ( false !== stripos( $p_subfield, 'misc_rating_5_star' ) ) {
				$p_value = absint( $property_values['rating5'] );
			} elseif ( false !== stripos( $p_subfield, 'misc_rating_100_points' ) ) {
				$p_value = absint( $property_values['rating100'] );
			} elseif ( false !== stripos( $p_subfield, 'misc_duration_minutes' ) ) {
				$p_value = absint( $property_values['duration_minutes'] );
			} else {
				$p_value = null;
			}

			$p_overwrite = $property_values['overridable'] ?? false;
			$p_overwrite = Helper_Model::instance()->string_to_bool( $p_overwrite );

			$p_overwrite_multiple = $property_values['overridable_multiple'] ?? false;
			$p_overwrite_multiple = Helper_Model::instance()->string_to_bool( $p_overwrite_multiple );

			$props[] = array(
				'id'    => $property_uid,
				'name'  => $p_label,
				'value' => array(
					$p_subfield,
					$p_value,
					'overridable'          => $p_overwrite,
					'overridable_multiple' => $p_overwrite_multiple,
				),
			);
		}

		$snippet->set_props( $props );

		return $snippet;
	}


	/**
	 * Returns the HTML code for the properties to use in the table.
	 *
	 * Uses Rich_Snippet:get_properties() if $prop_ids has no elements.
	 *
	 * @since 2.0.0
	 * @since 2.2.0 Added $post param.
	 *
	 * @param Rich_Snippet $snippet
	 * @param array        $property_ids
	 * @param \WP_Post     $post
	 *
	 * @return string[]
	 */
	public function get_property_table_elements( $snippet, $property_ids = array(), $post = null ) {

		$this->init_fields();

		$html_elements = array();

		if ( count( $property_ids ) > 0 ) {
			$props = array_map( function ( $val ) {

				return Schemas_Model::get_property_by_id( $val );
			}, $property_ids );
		} else {
			# load the properties from the snippet
			$props = $snippet->get_properties();
		}

		foreach ( $props as $prop ) {
			if ( ! $prop instanceof Schema_Property ) {
				continue;
			}

			ob_start();
			View::admin_snippets_properties_row( $prop, $snippet, $post );
			$html_elements[] = ob_get_clean();
		}

		return $html_elements;
	}


	/**
	 * Builds a property table.
	 *
	 * Uses Rich_Snippet:get_properties() if $prop_ids has no elements.
	 *
	 * @since 2.0.0
	 * @since 2.2.0 Added $post parameter
	 *
	 * @param Rich_Snippet $snippet
	 * @param array        $prop_ids
	 * @param \WP_Post     $post
	 *
	 * @return string
	 */
	public function get_property_table( $snippet, $prop_ids = array(), $post ) {

		$props_rendered = $this->get_property_table_elements( $snippet, $prop_ids, $post );

		ob_start();
		View::admin_snippets_properties_table( $props_rendered, $snippet, $post );

		return ob_get_clean();

	}


	/**
	 * Filters the row actions.
	 *
	 * @param array    $actions
	 * @param \WP_Post $post
	 *
	 * @since 2.0.0
	 *
	 * @return array
	 */
	public function filter_row_actions( $actions, $post ) {

		if ( 'wpb-rs-global' !== $post->post_type ) {
			return $actions;
		}

		# Remove the quick edit option.
		# Note that the quickedit option has a weird name ('inline hide-if-no-js')
		if ( isset( $actions['inline hide-if-no-js'] ) ) {
			unset( $actions['inline hide-if-no-js'] );
		}

		return array_merge( [ 'ID: ' . $post->ID ], $actions );
	}


	/**
	 * Adds JS to the submitbox to remove some fields.
	 *
	 * @since 2.0.0
	 */
	public function submitbox_js() {

		if ( ! function_exists( 'get_current_screen' ) ) {
			return;
		}

		$screen = get_current_screen();

		if ( 'wpb-rs-global' !== $screen->post_type ) {
			return;
		}


		?>
		<script type="text/javascript">
          jQuery( '#visibility, .misc-pub-curtime' ).remove();
		</script>
		<?php
	}


	/**
	 * Print an admin notice to ask if we should install examples.
	 *
	 * @since 2.0.0
	 */
	public function predefined_notice() {

		if ( ! function_exists( 'get_current_screen' ) ) {
			return;
		}

		$screen = get_current_screen();

		if ( ! $screen instanceof \WP_Screen ) {
			return;
		}

		if ( 'edit-wpb-rs-global' !== $screen->id ) {
			return;
		}

		if ( true === (bool) get_option( 'wpb_rs/predefined/message/hidden', false ) ) {
			return;
		}

		$user = wp_get_current_user();
		?>
		<div class="notice notice-info">
			<p><?php
				printf(
					__( 'Hey <strong>%s!</strong> The plugin can install some predefined global snippets for you!', 'rich-snippets-schema' ),
					$user->display_name
				);

				printf(
					' <a class="button" href="%s">%s</a>',
					admin_url( 'edit.php?post_type=wpb-rs-global&install_predefined=1&_wpnonce=' ) . wp_create_nonce( 'wpbrs_install_predefined' ),
					__( 'Awesome! Install them please.', 'rich-snippets-schema' )
				);
				?>
			</p>
		</div>
		<?php
	}


	/**
	 * Installs predefined global snippets.
	 *
	 * @since 2.0.0
	 */
	public function install_predefined() {

		$should_install = boolval( filter_input( INPUT_GET, 'install_predefined', FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE ) );

		if ( ! $should_install ) {
			return;
		}

		if ( false === check_admin_referer( 'wpbrs_install_predefined' ) ) {
			wp_die( __( 'It seems that you\'re not allowed to do this.', 'rich-snippets-schema' ) );
		}

		$methods = get_class_methods( '\wpbuddy\rich_snippets\Predefined_Model' );

		foreach ( $methods as $method ) {
			$v = call_user_func( array( '\wpbuddy\rich_snippets\Predefined_Model', $method ) );

			if ( ! is_array( $v ) ) {
				continue;
			}

			$snippet_id = array_keys( $v['schema'] )[0];

			$post_id = Helper_Model::instance()->get_post_id_by_snippet_uid( $snippet_id );

			wp_insert_post( array(
				'ID'          => $post_id, # this will update existing posts
				'post_title'  => ! isset( $v['title'] ) ? array_values( $v['schema'] )[0]->type : $v['title'],
				'post_status' => ! isset( $v['status'] ) ? 'publish' : $v['status'],
				'post_type'   => 'wpb-rs-global',
				'meta_input'  => array(
					'_wpb_rs_schema'   => $v['schema'],
					'_wpb_rs_position' => $v['position'],
				),
			) );

		}

		Cache_Model::clear_global_snippets_ids();

		update_option( 'wpb_rs/predefined/message/hidden', true, false );

		wp_redirect( admin_url( 'edit.php?post_type=wpb-rs-global' ) );
	}


	/**
	 * Initializes the fields.
	 *
	 * Prevents double-init.
	 *
	 * @since 2.0.0
	 */
	public function init_fields() {

		if ( ! $this->fields instanceof Fields_Model ) {
			$this->fields = new Fields_Model();
		}
	}


	/**
	 * Manage wpb-rs-global post columns.
	 *
	 * @since 2.8.0
	 *
	 * @param array $columns
	 *
	 * @return array
	 */
	public function manage_posts_columns( $columns ) {

		$columns = Helper_Model::instance()->integrate_into_array(
			$columns,
			2,
			[
				'predefined' => sprintf(
					__( 'Predefined %s', 'rich-snippets-schema' ),
					sprintf(
						'<a href="%s" target="_blank"><span class="dashicons dashicons-editor-help"></span></a>',
						esc_url( Helper_Model::instance()->get_campaignify( 'https://rich-snippets.io/predefined-snippets/', 'plugin-global-snippets-overview' ) )
					)
				),
				'sync'       => sprintf(
					__( 'In Sync: %s', 'rich-snippets-schema' ),
					sprintf(
						'<a href="%s" target="_blank"><span class="dashicons dashicons-editor-help"></span></a>',
						esc_url( Helper_Model::instance()->get_campaignify( 'https://rich-snippets.io/sync-functionality/', 'plugin-global-snippets-overview' ) )
					)
				),
			]
		);

		$columns = Helper_Model::instance()->integrate_into_array(
			$columns,
			4,
			[
				'snippet-ids' => __( 'Snippet IDs', 'rich-snippets-schema' )
			]
		);

		return $columns;
	}


	/**
	 * Print custom columns on wpb-rs-global post overview page.
	 *
	 * @param string $column_name
	 * @param int    $post_id
	 *
	 * @since 2.8.0
	 */
	public function print_custom_columns( $column_name, $post_id ) {
		if ( 'snippet-ids' === $column_name || 'predefined' === $column_name ) {
			$snippets = Snippets_Model::get_snippets( $post_id );

			foreach ( $snippets as $snippet ) {
				if ( 'predefined' === $column_name && false !== stripos( $snippet->id, 'snip-global-' ) ) {
					echo '<span class="dashicons dashicons-yes"></span>';
					break;
				} else {
					echo $snippet->id;
				}
			}

		}

		if ( 'sync' === $column_name ) {
			echo '<span class="dashicons dashicons-no wpb-rs-global-snippet-sync-no"></span>';
		}
	}

}
