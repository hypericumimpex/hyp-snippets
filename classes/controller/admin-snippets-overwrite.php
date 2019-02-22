<?php

namespace wpbuddy\rich_snippets;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly


/**
 * Class Admin_Snippets_Forms_Controller.
 *
 * Manages creation and processing of Rich Snippets that can be overwritten.
 *
 * @package wpbuddy\rich_snippets
 *
 * @since   2.2.0
 */
final class Admin_Snippets_Overwrite_Controller {

	/**
	 * The instance.
	 *
	 * @var Admin_Snippets_Overwrite_Controller
	 *
	 * @since 2.2.0
	 */
	protected static $instance = null;


	/**
	 * If this instance has been initialized already.
	 *
	 * @since 2.2.0
	 *
	 * @var bool
	 */
	protected $initialized = false;


	/**
	 * Get the singleton instance.
	 *
	 * Creates a new instance of the class if it does not exists.
	 *
	 * @return   Admin_Snippets_Overwrite_Controller
	 *
	 * @since 2.2.0
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
	 * @since 2.2.0
	 */
	protected function __clone() {
	}


	/**
	 * Magic method for setting up the class.
	 *
	 * Disallow external instances.
	 *
	 * @since 2.2.0
	 */
	protected function __construct() {
	}


	/**
	 * Init.
	 *
	 * @since 2.2.0
	 */
	public function init() {

		if ( $this->initialized ) {
			return;
		}

		add_action( 'admin_footer', [ self::instance(), 'print_modal_window' ] );

		$this->add_metaboxes();

		$this->initialized = true;
	}


	/**
	 * @since 2.2.0
	 */
	public function print_modal_window() {

		View::admin_posts_modalwindow();
	}


	/**
	 * Add metaboxes.
	 *
	 * @since 2.2.0
	 */
	public function add_metaboxes() {

		if ( ! Helper_Model::instance()->magic() ) {
			return;
		}

		add_meta_box(
			'rswp-overwrite-mb',
			__( 'Global Snippets Values', 'rich-snippets-schema' ),
			array( '\wpbuddy\rich_snippets\View', 'admin_posts_metabox_overwrites' ),
			null,
			'side',
			'high'
		);
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
	 * @param int          $overwrite_post_id The post_id where the overwrite data is stored.
	 *
	 * @return string[]
	 */
	public function get_property_table_elements( $snippet, $property_ids = array(), $post, $overwrite_post_id ) {

		$html_elements = array();

		if ( count( $property_ids ) > 0 ) {
			$props = array_map( function ( $val ) {

				return Schemas_Model::get_property_by_id( $val );
			}, $property_ids );
		} else {
			# load the properties from the snippet
			$props = $snippet->get_properties();
		}

		$overwrite_data = get_post_meta( $overwrite_post_id, '_wpb_rs_overwrite_data', true );

		foreach ( $props as $prop ) {
			if ( ! $prop instanceof Schema_Property ) {
				continue;
			}

			if ( ! $prop->is_overridable() ) {
				continue;
			}

			# overwrite
			if ( isset( $overwrite_data[ $snippet->id ]['properties'][ $prop->uid ] ) ) {
				$value = $overwrite_data[ $snippet->id ]['properties'][ $prop->uid ];

				# check if this value can be overwritten multiple times.
				if ( is_array( $value ) ) {
					foreach ( $value as $v ) {
						$prop->value[1] = $v;

						ob_start();
						View::admin_snippets_overwrite_row( $prop, $snippet, $post, $overwrite_post_id );
						$html_elements[] = ob_get_clean();
					}

					continue;
				}

				$prop->value[1] = $value;
			}

			ob_start();
			View::admin_snippets_overwrite_row( $prop, $snippet, $post, $overwrite_post_id );
			$html_elements[] = ob_get_clean();
		}

		if ( count( $html_elements ) <= 0 ) {
			$html_elements[] = sprintf( '<tr><td colspan="2">%s</td></tr>', __( 'No overridable properties found.', 'rich-snippets-schema' ) );
			$html_elements[] = sprintf(
				'<tr><td colspan="2"><img src="%s" alt="%s" width="809" height="573" /></td></tr>',
				plugins_url( '/img/make-them-editable.jpg', WPB_RS_FILE ),
				_x( 'How to make a property overridable.', 'Image alt text', 'rich-snippets-schema' )
			);
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
	 * @param int          $overwrite_post_id The post_id where the overwrite data is stored.
	 *
	 * @return string
	 */
	public function get_property_table( $snippet, $prop_ids, $post, $overwrite_post_id ) {

		$props_rendered = $this->get_property_table_elements( $snippet, $prop_ids, $post, $overwrite_post_id );

		ob_start();
		View::admin_snippets_overwrite_table( $props_rendered, $snippet, $post );

		return ob_get_clean();

	}
}
