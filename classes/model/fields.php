<?php

namespace wpbuddy\rich_snippets;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly


/**
 * Class Fields.
 *
 * Prepares HTML fields to use.
 *
 * @package wpbuddy\rich_snippets
 *
 * @since   2.0.0
 */
final class Fields_Model {

	/**
	 * Magic method for setting up the class.
	 *
	 * @since 2.0.0
	 */
	public function __construct() {

		add_action(
			'wpbuddy/rich_snippets/rest/property/html/fields',
			array( $this, 'textfield' )
		);

		add_action(
			'wpbuddy/rich_snippets/rest/property/html/fields',
			array( $this, 'the_descendants_select' )
		);

		do_action_ref_array( 'wpbuddy/rich_snippets/fields/hooks_init', array( &$this ) );
	}


	/**
	 * All possible internal values.
	 *
	 * @since 2.0.0
	 * @since 2.7.0 public
	 *
	 * @return array
	 */
	public static function get_internal_values() {

		$values = array(
			'http://schema.org/Text'              => array(
				array(
					'id'    => 'textfield',
					'label' => esc_html_x( 'Direct text input', 'subselect field', 'rich-snippets-schema' ),
				),
				array(
					'id'    => 'textfield_meta',
					'label' => esc_html_x( 'Post meta field', 'subselect field', 'rich-snippets-schema' ),
				),
				array(
					'id'    => 'current_post_title',
					'label' => esc_html_x( 'Post title', 'subselect field', 'rich-snippets-schema' ),
				),
				array(
					'id'    => 'current_post_excerpt',
					'label' => esc_html_x( 'Post excerpt', 'subselect field', 'rich-snippets-schema' ),
				),
				array(
					'id'    => 'current_post_author_name',
					'label' => esc_html_x( 'Post author name', 'subselect field', 'rich-snippets-schema' ),
				),
				array(
					'id'    => 'blog_title',
					'label' => esc_html_x( 'Blog title', 'subselect field', 'rich-snippets-schema' ),
				),
				array(
					'id'    => 'blog_description',
					'label' => esc_html_x( 'Blog description', 'subselect field', 'rich-snippets-schema' ),
				),
				array(
					'id'    => 'current_post_content',
					'label' => esc_html_x( 'Post content', 'subselect field', 'rich-snippets-schema' ),
				),
				array(
					'id'    => 'current_post_id',
					'label' => esc_html_x( 'Post ID', 'subselect field', 'rich-snippets-schema' ),
				),
				array(
					'id'    => 'current_category',
					'label' => esc_html_x( 'Category', 'subselect field', 'rich-snippets-schema' ),
				),
				array(
					'id'    => 'term_title',
					'label' => esc_html_x( 'Term title (loop only)', 'subselect field', 'rich-snippets-schema' ),
				),
			),
			'http://schema.org/Integer'           => array(
				array(
					'id'    => 'textfield',
					'label' => esc_html_x( 'Direct text input', 'subselect field', 'rich-snippets-schema' ),
				),
				array(
					'id'    => 'textfield_meta',
					'label' => esc_html_x( 'Post meta field', 'subselect field', 'rich-snippets-schema' ),
				),
				array(
					'id'    => 'current_post_id',
					'label' => esc_html_x( 'Post ID', 'subselect field', 'rich-snippets-schema' ),
				),
				array(
					'id'    => 'textfield_sequential_number',
					'label' => esc_html_x( 'Sequential Number', 'subselect field', 'rich-snippets-schema' ),
				),
			),
			'http://schema.org/Time'              => array(
				array(
					'id'    => 'textfield',
					'label' => esc_html_x( 'Direct text input', 'subselect field', 'rich-snippets-schema' ),
				),
				array(
					'id'    => 'textfield_meta',
					'label' => esc_html_x( 'Post meta field', 'subselect field', 'rich-snippets-schema' ),
				),
			),
			'http://schema.org/DayOfWeek'         => array(
				array(
					'id'    => 'textfield',
					'label' => esc_html_x( 'Direct text input', 'subselect field', 'rich-snippets-schema' ),
				),
				array(
					'id'    => 'textfield_meta',
					'label' => esc_html_x( 'Post meta field', 'subselect field', 'rich-snippets-schema' ),
				),
			),
			'http://schema.org/Date'              => array(
				array(
					'id'    => 'textfield',
					'label' => esc_html_x( 'Direct text input', 'subselect field', 'rich-snippets-schema' ),
				),
				array(
					'id'    => 'textfield_meta',
					'label' => esc_html_x( 'Post meta field', 'subselect field', 'rich-snippets-schema' ),
				),
				array(
					'id'    => 'current_post_date',
					'label' => esc_html_x( 'Post published date', 'subselect field', 'rich-snippets-schema' ),
				),
				array(
					'id'    => 'current_post_modified_date',
					'label' => esc_html_x( 'Post modified date', 'subselect field', 'rich-snippets-schema' ),
				),
			),
			'http://schema.org/ImageObject'       => array(
				array(
					'id'    => 'current_post_thumbnail_url',
					'label' => esc_html_x( 'Post thumbnail url', 'subselect field', 'rich-snippets-schema' ),
				),
			),
			'http://schema.org/URL'               => array(
				array(
					'id'    => 'textfield',
					'label' => esc_html_x( 'Direct text input', 'subselect field', 'rich-snippets-schema' ),
				),
				array(
					'id'    => 'textfield_meta',
					'label' => esc_html_x( 'Post meta field', 'subselect field', 'rich-snippets-schema' ),
				),
				array(
					'id'    => 'current_post_url',
					'label' => esc_html_x( 'Post URL', 'subselect field', 'rich-snippets-schema' ),
				),
				array(
					'id'    => 'current_post_thumbnail_url',
					'label' => esc_html_x( 'Post thumbnail url', 'subselect field', 'rich-snippets-schema' ),
				),
				array(
					'id'    => 'current_post_author_url',
					'label' => esc_html_x( 'Post author url', 'subselect field', 'rich-snippets-schema' ),
				),
				array(
					'id'    => 'blog_url',
					'label' => esc_html_x( 'Blog url', 'subselect field', 'rich-snippets-schema' ),
				),
				array(
					'id'    => 'site_icon_url',
					'label' => esc_html_x( 'Site icon URL', 'subselect field', 'rich-snippets-schema' ),
				),
				array(
					'id'    => 'current_category_url',
					'label' => esc_html_x( 'Category URL', 'subselect field', 'rich-snippets-schema' ),
				),
				array(
					'id'    => 'term_url',
					'label' => esc_html_x( 'Term URL (loop only)', 'subselect field', 'rich-snippets-schema' ),
				),
				array(
					'id'    => 'search_url',
					'label' => esc_html_x( 'Search URL', 'subselect field', 'rich-snippets-schema' ),
				),
				array(
					'id'    => 'search_url_search_term',
					'label' => esc_html_x( 'Search URL (with {search_term_string} placeholder)', 'subselect field', 'rich-snippets-schema' ),
				),
			),
			'http://schema.org/Duration'          => array(
				array(
					'id'    => 'textfield',
					'label' => esc_html_x( 'Direct text input', 'subselect field', 'rich-snippets-schema' ),
				),
				array(
					'id'    => 'textfield_meta',
					'label' => esc_html_x( 'Post meta field', 'subselect field', 'rich-snippets-schema' ),
				),
			),
			'http://schema.org/Distance'          => array(
				array(
					'id'    => 'textfield',
					'label' => esc_html_x( 'Direct text input', 'subselect field', 'rich-snippets-schema' ),
				),
				array(
					'id'    => 'textfield_meta',
					'label' => esc_html_x( 'Post meta field', 'subselect field', 'rich-snippets-schema' ),
				),
				array(
					'id'    => 'current_post_thumbnail_width',
					'label' => esc_html_x( 'Post thumbnail width', 'subselect field', 'rich-snippets-schema' ),
				),
				array(
					'id'    => 'current_post_thumbnail_height',
					'label' => esc_html_x( 'Post thumbnail height', 'subselect field', 'rich-snippets-schema' ),
				),
			),
			'http://schema.org/QuantitativeValue' => array(
				array(
					'id'    => 'textfield',
					'label' => esc_html_x( 'Direct text input', 'subselect field', 'rich-snippets-schema' ),
				),
				array(
					'id'    => 'textfield_meta',
					'label' => esc_html_x( 'Post meta field', 'subselect field', 'rich-snippets-schema' ),
				),
				array(
					'id'    => 'current_post_thumbnail_width',
					'label' => esc_html_x( 'Post thumbnail width', 'subselect field', 'rich-snippets-schema' ),
				),
				array(
					'id'    => 'current_post_thumbnail_height',
					'label' => esc_html_x( 'Post thumbnail height', 'subselect field', 'rich-snippets-schema' ),
				),
				array(
					'id'    => 'site_icon_width',
					'label' => esc_html_x( 'Site icon width', 'subselect field', 'rich-snippets-schema' ),
				),
				array(
					'id'    => 'site_icon_height',
					'label' => esc_html_x( 'Site icon height', 'subselect field', 'rich-snippets-schema' ),
				),
			),
			'http://schema.org/Intangible'        => array(
				array(
					'id'    => 'textfield',
					'label' => esc_html_x( 'Direct text input', 'subselect field', 'rich-snippets-schema' ),
				),
				array(
					'id'    => 'textfield_meta',
					'label' => esc_html_x( 'Post meta field', 'subselect field', 'rich-snippets-schema' ),
				),
			),
			'http://schema.org/Qantity'           => array(
				array(
					'id'    => 'textfield',
					'label' => esc_html_x( 'Direct text input', 'subselect field', 'rich-snippets-schema' ),
				),
				array(
					'id'    => 'textfield_meta',
					'label' => esc_html_x( 'Post meta field', 'subselect field', 'rich-snippets-schema' ),
				),
			),
			'http://schema.org/Energy'            => array(
				array(
					'id'    => 'textfield',
					'label' => esc_html_x( 'Direct text input', 'subselect field', 'rich-snippets-schema' ),
				),
				array(
					'id'    => 'textfield_meta',
					'label' => esc_html_x( 'Post meta field', 'subselect field', 'rich-snippets-schema' ),
				),
			),
			'http://schema.org/CssSelectorType'   => array(
				array(
					'id'    => 'textfield',
					'label' => esc_html_x( 'Direct text input', 'subselect field', 'rich-snippets-schema' ),
				),
				array(
					'id'    => 'textfield_meta',
					'label' => esc_html_x( 'Post meta field', 'subselect field', 'rich-snippets-schema' ),
				),
			),
		);

		$values['http://schema.org/Thing']      = $values['http://schema.org/URL'];
		$values['http://schema.org/EntryPoint'] = $values['http://schema.org/URL'];

		/**
		 * Internal subselect values.
		 *
		 * This filter can be used to add additional options to the subselect item.
		 *
		 * @since 2.0.0
		 *
		 * @param array $var The return parameter: an array of values.
		 *
		 */
		return apply_filters(
			'wpbuddy/rich_snippets/fields/internal_subselect/values',
			$values
		);
	}


	/**
	 * Fetches internal subselect values.
	 *
	 * @param Schema_Property $prop
	 * @param string          $schema
	 * @param string          $selected The selected item.
	 *
	 * @since 2.0.0
	 *
	 * @return string[] Array of HTML <option> fields.
	 */
	public static function get_internal_subselect_options( $prop, $schema, $selected ) {

		$values = self::get_internal_values();

		$options = [];

		foreach ( $values as $value_schema => $fields ) {
			if ( ! in_array( $value_schema, $prop->range_includes ) ) {
				continue;
			}
			foreach ( $fields as $field ) {
				$options[ $field['id'] ] = sprintf(
					'<option value="%s" %s>%s</option>',
					esc_attr( $field['id'] ),
					selected( $selected, $field['id'], false ),
					esc_html( Helper_Model::instance()->remove_schema_url( $field['label'] ) )
				);
			}

		}

		/**
		 * Internal subselect values.
		 *
		 * This filter can be used to add additional options to the subselect item.
		 *
		 * @since 2.0.0
		 *
		 * @param array                                  $var      The return parameter: an array of options.
		 * @param \wpbuddy\rich_snippets\Schema_Property $prop     The current property.
		 * @param string                                 $schema   The current schema class.
		 * @param string                                 $selected The current selected item.
		 *
		 */
		return apply_filters(
			'wpbuddy/rich_snippets/fields/internal_subselect/options',
			$options,
			$prop,
			$schema,
			$selected
		);
	}


	/**
	 * Prints a simple text field.
	 *
	 * @since 2.0.0
	 *
	 * @param array $args
	 */
	public function textfield( $args ) {

		/**
		 * @var Schema_Property $property
		 * @var string          $current_type
		 * @var string          $html_id
		 * @var string          $property_id
		 * @var string          $input_name
		 * @var string          $selected
		 * @var mixed           $value
		 * @var string          $screen
		 */
		extract( $args );

		if ( 'overwrite' === $screen && false === stripos( $selected, 'textfield' ) ) {
			return;
		}

		$textvalue = false !== stripos( $selected, 'textfield' ) && is_scalar( $value ) ? $value : '';

		$input_name = 'edit' === $screen ? $input_name . '[textfield]' : $input_name;
		$input_name .= $property->overridable_multiple && 'overwrite' === $screen ? '[]' : '';

		printf(
			'<textarea data-name="textfield" class="wpb-rs-schema-property-field-text wpb-rs-schema-property-field-text-%s regular-text %s" name="%s">%s</textarea>',
			$html_id,
			false !== stripos( $selected, 'textfield' ) ? '' : 'wpb-rs-hidden',
			$input_name,
			esc_textarea( $textvalue )
		);
	}


	/**
	 * Returns all Methods that can be used internally to fill values.
	 *
	 * @see   Values_Model::init()
	 *
	 * @since 2.0.0
	 * @since 2.2.0 Renamed from 'get_internal_values_ids'.
	 *
	 * @return callable[] Array of callables where the array key is the value ID like textfield | current_post_title |
	 *     current_post_thumbnail |...
	 */
	public static function get_internal_values_methods() {

		$ret_array = array();

		foreach ( self::get_internal_values() as $el ) {
			foreach ( $el as $e ) {
				$ret_array[ $e['id'] ] = isset( $e['method'] ) ? $e['method'] : $e['id'];
			}
		}

		return $ret_array;
	}


	/**
	 * Returns all 'reference' values.
	 *
	 * @since 2.0.0
	 *
	 * @return array
	 */
	public static function get_reference_values() {

		$values = array(
			'textfield_id'            => _x( 'Custom ID', 'Link to a custom ID on a page.', 'rich-snippets-schema' ),
			'current_post_content_id' => __( 'Post Content (as CreativeWork, deprecated)', 'rich-snippets-schema' ),
		);

		$global_posts = call_user_func( function () {

			$cache = wp_cache_get( 'post_reference_values', 'wpb_rs' );

			if ( is_array( $cache ) ) {
				return $cache;
			}

			global $wpdb, $post;

			$sql = "SELECT pm.meta_value as global_schemas, p.post_title, p.ID "
			       . " FROM {$wpdb->posts} p "
			       . " LEFT JOIN {$wpdb->postmeta} as pm ON (p.ID = pm.post_id AND pm.meta_key = '_wpb_rs_schema') "
			       . " WHERE p.post_status = 'publish' AND p.post_type = 'wpb-rs-global' ";

			if ( isset( $post ) && $post instanceof \WP_Post ) {
				# do not include Post ID
				$sql .= sprintf( ' AND p.ID != "%d" ', $post->ID );
			}

			$results = $wpdb->get_results( $sql );

			if ( ! is_array( $results ) ) {
				wp_cache_set( 'post_reference_values', array(), 'wpb_rs', MINUTE_IN_SECONDS );

				return array();
			}

			$values = array();

			foreach ( $results as $result ) {
				$global_schemas = maybe_unserialize( $result->global_schemas );
				if ( ! is_array( $global_schemas ) ) {
					continue;
				}

				/**
				 * @var \wpbuddy\rich_snippets\Rich_Snippet $global_schema
				 */
				foreach ( $global_schemas as $snippet_uid => $global_schema ) {
					$values[ 'global_snippet_' . $snippet_uid ] = sprintf(
						_x( 'Global snippet: %2$s/%1$s (%3$d)', '%1$s is the title of the global schema. %2$s is the schema class name. %3$d is the post ID', 'rich-snippets-schema' ),
						$result->post_title,
						$global_schema->type,
						$result->ID
					);
				}
			}

			wp_cache_set( 'post_reference_values', $values, 'wpb_rs', MINUTE_IN_SECONDS );

			return $values;

		} );

		$values = array_merge( $values, $global_posts );

		/**
		 * 'link to' subselect values filter.
		 *
		 * This filter can be used to add additional options to the 'link to' subfield select.
		 *
		 * @since 2.0.0
		 *
		 * @param array $var The return parameter: an array of values.
		 *
		 */
		$values = apply_filters(
			'wpbuddy/rich_snippets/fields/link_to_subselect/values',
			$values
		);

		return $values;
	}


	/**
	 * Returns all 'link_to' IDs that can be used to fill values.
	 *
	 * @return array
	 */
	public static function get_reference_values_ids() {

		$values = self::get_reference_values();
		$values = array_keys( $values );

		return array_combine( $values, $values );
	}


	/**
	 * Returns a list of values available for loops.
	 *
	 * @since 2.8.0
	 *
	 * @return array
	 */
	public static function get_loop_values() {
		$values = [
			''             => __( 'No loop', 'rich-snippets-schema' ),
			'main_query'   => __( 'Main query items (ie. for archive pages)', 'rich-snippets-schema' ),
			'page_parents' => __( 'Page parents', 'rich-snippets-schema' ),
		];

		$menus = call_user_func( function () {
			$values = [];

			$menus = wp_get_nav_menus();

			foreach ( $menus as $menu ) {
				if ( ! is_object( $menu ) ) {
					continue;
				}

				if ( ! isset( $menu->term_id ) ) {
					continue;
				}

				if ( ! isset( $menu->name ) ) {
					continue;
				}

				$values[ 'menu_' . $menu->term_id ] = sprintf(
					_x( 'Menu: %s', 'Menu label', 'rich-snippets-schema' ),
					esc_html( $menu->name )
				);
			}

			return $values;
		} );

		$values = array_merge( $values, $menus );

		$taxonomies = call_user_func( function () {
			$values = [];

			/**
			 * @var \WP_Taxonomy[]
			 */
			$taxonomies = get_taxonomies(
				[ 'public' => true ],
				'objects'
			);

			foreach ( $taxonomies as $tax_key => $tax ) {
				$values[ 'taxonomy_' . $tax_key ] = sprintf(
					_x( 'Taxonomy: %s', 'Taxonomy label', 'rich-snippets-schema' ),
					esc_html( $tax->label )
				);
			}

			return $values;
		} );

		$values = array_merge( $values, $taxonomies );

		/**
		 * 'loop' subselect values filter.
		 *
		 * This filter can be used to add additional options to the 'link to' subfield select.
		 *
		 * @since 2.8.0
		 *
		 * @param array $var The return parameter: an array of values.
		 *
		 */
		$values = apply_filters(
			'wpbuddy/rich_snippets/fields/loop_subselect/values',
			$values
		);

		return $values;
	}


	/**
	 * Fetches 'loop' subselect options.
	 *
	 * @param Schema_Property $prop
	 * @param string          $schema
	 * @param string          $selected The selected item.
	 *
	 * @since 2.8.0
	 *
	 * @return string[] Array of HTML <option> fields.
	 */
	public static function get_loop_subselect_options( $schema, $selected ) {
		$options = array();

		$values = self::get_loop_values();

		foreach ( $values as $value => $label ) {
			$options[] = sprintf(
				'<option data-use-textfield="%s" value="%s" %s>%s</option>',
				false !== stripos( $value, 'textfield' ) ? 1 : 0,
				$value,
				selected( $selected, $value, false ),
				esc_html( $label )
			);
		}

		/**
		 * Internal 'loop' values.
		 *
		 * This filter can be used to add additional options to the 'loop' subselect.
		 *
		 * @since 2.8.0
		 *
		 * @param array  $var      The return parameter: an array of options.
		 * @param string $schema   The current schema class.
		 * @param string $selected The current selected item.
		 *
		 */
		return apply_filters(
			'wpbuddy/rich_snippets/fields/loop_subselect/options',
			$options,
			$schema,
			$selected
		);
	}


	/**
	 * Fetches 'reference' subselect options.
	 *
	 * @param Schema_Property $prop
	 * @param string          $schema
	 * @param string          $selected The selected item.
	 *
	 * @since 2.0.0
	 *
	 * @return string[] Array of HTML <option> fields.
	 */
	public static function get_reference_subselect_options( $prop, $schema, $selected ) {

		$values = self::get_reference_values();

		$options = array();

		foreach ( $values as $value => $label ) {
			$options[] = sprintf(
				'<option data-use-textfield="%s" value="%s" %s>%s</option>',
				false !== stripos( $value, 'textfield' ) ? 1 : 0,
				$value,
				selected( $selected, $value, false ),
				esc_html( $label )
			);
		}

		/**
		 * Internal 'reference' values.
		 *
		 * This filter can be used to add additional options to the 'reference' subselect.
		 *
		 * @since 2.0.0
		 * @since 2.1.1 Renamed from 'wpbuddy/rich_snippets/fields/link_to_subselect/options'
		 *
		 * @param array                                  $var      The return parameter: an array of options.
		 * @param \wpbuddy\rich_snippets\Schema_Property $prop     The current property.
		 * @param string                                 $schema   The current schema class.
		 * @param string                                 $selected The current selected item.
		 *
		 */
		return apply_filters(
			'wpbuddy/rich_snippets/fields/reference_subselect/options',
			$options,
			$prop,
			$schema,
			$selected
		);

	}


	/**
	 * Fetches 'related' subselect options.
	 *
	 * @param Schema_Property $prop
	 * @param string          $schema
	 * @param string          $selected The selected item.
	 *
	 * @since 2.0.0
	 *
	 * @return string[] Array of HTML <option> fields.
	 */
	public static function get_related_subselect_options( $prop, $schema, $selected ) {

		/**
		 * 'related' subselect values filter.
		 *
		 * This filter can be used to add additional options to the 'related' subfield select.
		 *
		 * @since 2.0.0
		 *
		 * @param                 array
		 * @param array           $var    The return parameter: an array of values.
		 * @param Schema_Property $prop   The current property.
		 * @param string          $schema The current schema class.
		 *
		 */
		$values = apply_filters(
			'wpbuddy/rich_snippets/fields/related_subselect/values',
			$prop->range_includes,
			$prop,
			$schema
		);

		$children = [];
		foreach ( $values as $schema_type ) {
			$c = Schemas_Model::get_children( $schema_type );
			if ( is_wp_error( $c ) ) {
				continue;
			}
			$children = array_merge( $children, $c );
		}

		$children = array_unique( $children );
		$values   = array_merge( $values, $children );
		unset( $children );

		sort( $values, SORT_NATURAL );

		$options = array();

		foreach ( $values as $schema_class ) {
			$options[ $schema_class ] = sprintf(
				'<option data-has_schema="1" value="%1$s" %2$s>%3$s</option>',
				esc_attr( $schema_class ),
				selected( $selected, $schema_class, false ),
				esc_html( Helper_Model::instance()->remove_schema_url( $schema_class ) )
			);
		}

		/**
		 * Related values.
		 *
		 * This filter can be used to add additional options to the 'related' subselect.
		 *
		 * @since 2.0.0
		 *
		 * @param array                                  $var      The return parameter: an array of options.
		 * @param \wpbuddy\rich_snippets\Schema_Property $prop     The current property.
		 * @param string                                 $schema   The current schema class.
		 * @param string                                 $selected The current selected item.
		 *
		 */
		return apply_filters(
			'wpbuddy/rich_snippets/fields/related_subselect/options',
			$options,
			$prop,
			$schema,
			$selected
		);
	}


	/**
	 * Fetches schema types that can be included directly.
	 *
	 * @param Schema_Property $prop
	 * @param string          $schema_type
	 * @param string          $selected The selected item.
	 *
	 * @since 2.0.0
	 *
	 * @return string[] Array of HTML <option> fields.
	 */
	public static function get_descendants_types_subselect_options( $prop, $schema_type, $selected ) {

		$values = Schemas_Model::get_type_descendants( $prop->range_includes );

		if ( is_wp_error( $values ) ) {
			$values = array();
		}

		/**
		 * 'direct descendants' subselect values filter.
		 *
		 * This filter can be used to add additional options to the 'direct descendants' subfield select.
		 *
		 * @since 2.0.0
		 *
		 * @param                 array
		 * @param array           $var    The return parameter: an array of values.
		 * @param Schema_Property $prop   The current property.
		 * @param string          $schema The current schema class.
		 *
		 */
		$values = apply_filters(
			'wpbuddy/rich_snippets/fields/descendants_subselect/values',
			$values,
			$prop,
			$schema_type
		);

		$options = array();

		foreach ( $values as $type ) {
			$options[ $type ] = sprintf(
				'<option value="descendant-%1$s" %2$s>%3$s</option>',
				esc_attr( $type ),
				selected( $selected, 'descendant-' . $type, false ),
				esc_html( Helper_Model::instance()->remove_schema_url( $type ) )
			);
		}

		/**
		 * Descendants values.
		 *
		 * This filter can be used to add additional options to the 'direct descendants' subselect.
		 *
		 * @since 2.0.0
		 *
		 * @param array                                  $var      The return parameter: an array of options.
		 * @param \wpbuddy\rich_snippets\Schema_Property $prop     The current property.
		 * @param string                                 $schema   The current schema class.
		 * @param string                                 $selected The current selected item.
		 *
		 */
		return apply_filters(
			'wpbuddy/rich_snippets/fields/descendants_subselect/options',
			$options,
			$prop,
			$schema_type,
			$selected
		);
	}


	/**
	 * Prints a select box for the descendant values
	 *
	 * @since 2.7.0
	 *
	 * @param $args
	 */
	public static function the_descendants_select( $args ) {

		if ( 'overwrite' !== $args['screen'] ) {
			return;
		}

		if ( ! empty( $args['selected'] ) ) {
			return;
		}

		$descendants_select_options = Fields_Model::get_descendants_types_subselect_options(
			$args['property'],
			$args['current_type'],
			$args['value']
		);

		if ( count( $descendants_select_options ) <= 0 ) {
			return;
		}

		printf(
			'<select name="%s">%s</select>',
			$args['input_name'],
			implode( '', $descendants_select_options )
		);
	}


	/**
	 * Checks if a field is selectable.
	 *
	 * @param Schema_Property
	 * @param string $field_name
	 *
	 * @since 2.7.0
	 *
	 * @return bool
	 */
	public static function is_field_selectable( $prop, $field_name ) {
		$values = self::get_internal_values();

		foreach ( $values as $value_schema => $fields ) {
			if ( ! in_array( $value_schema, $prop->range_includes ) ) {
				continue;
			}
			foreach ( $fields as $field ) {
				if ( isset( $field['id'] ) && $field['id'] === $field_name ) {
					return true;
				}
			}
		}

		return false;
	}

}
