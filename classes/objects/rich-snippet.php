<?php

namespace wpbuddy\rich_snippets;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly


/**
 * Json object.
 *
 * @package wpbuddy\rich_snippets
 *
 * @since   2.0.0
 */
class Rich_Snippet {

	/**
	 * The snippet ID.
	 *
	 * @since 2.0.0
	 *
	 * @var string
	 */
	public $id = '';


	/**
	 * The context.
	 *
	 * @since 2.0.0
	 *
	 * @var string
	 */
	public $context = 'http://schema.org';


	/**
	 * The type.
	 *
	 * @since 2.0.0
	 *
	 * @var string
	 */
	public $type = 'Thing';


	/**
	 * If the object has been prepared for output.
	 *
	 * @since 2.0.0
	 *
	 * @var bool
	 */
	private $_is_ready = false;


	/**
	 * Shows if the current snippet is the main/parent snippet.
	 *
	 * @since 2.5.4
	 *
	 * @var bool
	 */
	private $_is_main_snippet = false;


	/**
	 * The plugin version the snippet was created with.
	 *
	 * @since 2.5.4
	 *
	 * @var string
	 */
	private $_version_created = null;


	/**
	 * If SNIP should iterate over this snippet with a loop.
	 *
	 * The output will then create an array of multiple snippets of the same item as this one.
	 *
	 * @since 2.8.0
	 *
	 * @var string
	 */
	private $_loop = null;


	/**
	 * Rich_Snippet constructor.
	 *
	 * @since 2.0.0
	 * @since 2.5.4 Added $args parameter.
	 *
	 * @param array
	 */
	public function __construct( $args = [] ) {

		foreach ( $args as $arg_key => $arg_value ) {
			$this->{$arg_key} = $arg_value;
		}

		$this->id = uniqid( 'snip-' );
	}


	/**
	 * Sets properties.
	 *
	 * @since 2.0.0
	 *
	 * @param array $props
	 */
	public function set_props( $props = array() ) {

		foreach ( $props as $prop ) {
			$this->set_prop( $prop['name'], $prop['value'], isset( $prop['id'] ) ? $prop['id'] : null );
		}
	}


	/**
	 * Sets a single property.
	 *
	 * Will add a '-prop-xxx' unique ID to each property that is not a class var.
	 *
	 * @since 2.0.0
	 *
	 * @param string      $name
	 * @param mixed       $value
	 * @param string|null $id A unique ID for this prop (without the '-prop-' prefix)
	 */
	public function set_prop( $name, $value, $id = null ) {

		if ( empty( $id ) ) {
			$id = uniqid( '-prop-' );
		} else {
			if ( false !== stripos( $id, 'prop-' ) ) {
				$id = '-' . $id;
			} else {
				$id = '-prop-' . $id;
			}
		}

		$this->{$name . $id} = $value;
	}


	/**
	 * Returns an array of properties.
	 *
	 * @since 2.0.0
	 *
	 * @return Schema_Property[]
	 */
	public function get_properties() {

		$object_vars = get_object_vars( $this );

		$class_vars = get_class_vars( __CLASS__ );

		$object_props = array_diff_key( $object_vars, $class_vars );

		$props = array();

		foreach ( $object_props as $k => $v ) {
			$prop_id  = $this->normalize_property_name( $k );
			$prop_uid = str_replace( $prop_id . '-prop-', '', $k );
			$prop_id  = 'http://schema.org/' . $prop_id;

			$prop = Schemas_Model::get_property_by_id( $prop_id );

			if ( $prop instanceof Schema_Property ) {
				$prop->value                = $v;
				$prop->overridable          = $v['overridable'] ?? false;
				$prop->overridable_multiple = $v['overridable_multiple'] ?? false;
				$prop->uid                  = $prop_uid;

				$props[] = $prop;
			}
		}

		return $props;
	}


	/**
	 * Removes "-prop-*****" names from property names.
	 *
	 * @since 2.0.0
	 *
	 * @param string $prop
	 *
	 * @return string
	 */
	private function normalize_property_name( $prop ) {

		$prop_id = strstr( $prop, '-prop-', true );

		return str_replace( '-prop-', '', $prop_id );
	}


	/**
	 * Returns a property value for a given full url (e.g. https://schema.org/image )
	 *
	 * @since 2.0.0
	 *
	 * @param string $url
	 *
	 * @return mixed|null Null if value does not exist.
	 */
	public function get_property_value_by_path( $url ) {

		$url      = untrailingslashit( $url );
		$val_name = Helper_Model::instance()->remove_schema_url( $url );

		if ( isset( $this->{$val_name} ) ) {
			return $this->{$val_name};
		}

		return null;
	}


	/**
	 * Outputs a JSON-String of the object.
	 *
	 * @since 2.0.0
	 *
	 * @return string
	 */
	public function __toString(): string {

		if ( ! $this->_is_ready ) {
			return sprintf( '<!--%s-->',
				__( 'Object is not ready for output, yet. Please call \wpbuddy\rich_snippets\Rich_Snippet::prepare_for_output() first.', 'rich-snippets-schema' )
			);
		}

		return json_encode( $this );
	}


	/**
	 * Iterates over all items in a loop and prepares the items.
	 *
	 * @since 2.8.0
	 *
	 * @param array $meta_info
	 */
	private function prepare_loop_items( $meta_info ) {
		$vars = get_object_vars( $this );

		$class_vars = get_class_vars( __CLASS__ );

		$props = array_diff_key( $vars, $class_vars );

		foreach ( $props as $prop_name_with_id => $prop_value ) {
			if ( ! isset( $prop_value[1] ) ) {
				continue;
			}

			/**
			 * @var Rich_Snippet $child_snippet
			 */
			$child_snippet = $prop_value[1];

			if ( ! $child_snippet instanceof Rich_Snippet ) {
				continue;
			}

			if ( ! $child_snippet->is_loop() ) {
				continue;
			}

			unset( $this->{$prop_name_with_id} );

			$prop_name_without_id = $this->normalize_property_name( $prop_name_with_id );

			$items = $child_snippet->get_items_for_loop( $meta_info['current_post_id'] );

			foreach ( $items as $loop_item_id => $loop_item ) {
				$snippet = clone $child_snippet;
				$snippet->reset_loop();

				$item_meta_info                    = $meta_info;
				$item_meta_info['current_post_id'] = $loop_item_id;
				$item_meta_info['object']          = $loop_item;

				$this->set_prop( $prop_name_without_id, $snippet->prepare_for_output( $item_meta_info ) );
			}
		}

	}


	/**
	 * Prepares object for output.
	 *
	 * @since 2.0.0
	 *
	 * @param array $meta_info
	 *
	 * @return \wpbuddy\rich_snippets\Rich_Snippet
	 *
	 */
	public function prepare_for_output( array $meta_info = array() ): Rich_Snippet {

		$meta_info = wp_parse_args( $meta_info, array(
			'current_post_id' => 0,
			'snippet_post_id' => 0,
		) );

		if ( $this->_is_ready ) {
			return $this;
		}

		# overwrite values, if any
		$this->overwrite_values( $meta_info );

		# prepare loop items
		$this->prepare_loop_items( $meta_info );

		# merge multiple properties together
		$this->merge_multiple_props();

		# fill all values
		$this->fill_values( $meta_info );

		# rename some properties
		$this->{'@context'} = $this->context;
		$this->{'@type'}    = $this->type;

		# inject custom JSON+LD data
		$this->inject_custom_json_ld( $meta_info );

		# delete all internal object vars
		foreach ( array_keys( get_class_vars( __CLASS__ ) ) as $k ) {
			unset( $this->{$k} );
		}

		# filter empty props if they are not integers or floats
		foreach ( array_keys( get_object_vars( $this ) ) as $k ) {
			if ( ! ( is_int( $this->{$k} ) || is_float( $this->{$k} ) ) && empty( $this->{$k} ) ) {
				unset( $this->{$k} );
			}

			/**
			 * Workaround: Scalar values need to be transformed to strings.
			 * This is because the structured data test tools don't like integer values.
			 */
			if ( isset( $this->{$k} ) && is_scalar( $this->{$k} ) ) {
				$this->{$k} = (string) $this->{$k};
			}
		}

		do_action_ref_array(
			'wpbuddy/rich_snippets/rich_snippet/prepare',
			array( &$this )
		);

		$this->_is_ready = true;

		return $this;
	}


	/**
	 * Returns the value.
	 *
	 * @param mixed $var A key-value pair where the first is the field type and the second is the value itself.
	 * @param array $meta_info
	 *
	 * @see   \wpbuddy\rich_snippets\Admin_Snippets_Controller::search_value_by_id()
	 *
	 * @since 2.0.0
	 *
	 * @return mixed
	 */
	private function get_the_value( $var, array $meta_info ) {

		if ( is_array( $var ) ) {
			if ( isset( $var[1] ) && ( $var[1] instanceof Rich_Snippet ) ) {
				$var = $var[1]->prepare_for_output( $meta_info );
			} else {
				$field_type = $var[0];

				if ( empty( $field_type ) ) {
					return '';
				}

				$var = $var[1];

				/**
				 * Rich_Snippet value filter.
				 *
				 * Allows plugins to hook into the value that will be outputted later.
				 *
				 * @since 2.0.0
				 *
				 * @param mixed        $value      The value.
				 * @param string       $field_type The field type (ie. textfield).
				 * @param Rich_Snippet $object     The current Rich_Snippet object.
				 *
				 */
				$var = apply_filters( 'wpbuddy/rich_snippets/rich_snippet/value', $var, $field_type, $this, $meta_info );

				/**
				 * Rich_Snippet value type filter.
				 *
				 * Allows plugins to hook into the value. The last parameter is the $field_type (ie. textfield).
				 *
				 * @since 2.0.0
				 *
				 * @param mixed        $value  The value.
				 * @param Rich_Snippet $object The current Rich_Snippet object.
				 *
				 */
				$var = apply_filters( 'wpbuddy/rich_snippets/rich_snippet/value/' . $field_type, $var, $this, $meta_info );

			}
		}

		return $var;

	}


	/**
	 * Gets the main type i.e. http://schema.org/Thing
	 *
	 * @since 2.0.0
	 *
	 * @return string
	 */
	public function get_type(): string {

		return trailingslashit( $this->context ) . $this->type;
	}


	/**
	 * Checks if a snippet has properties.
	 *
	 * @since 2.0.0
	 *
	 * @return bool
	 */
	public function has_properties(): bool {

		$object_vars = get_object_vars( $this );

		$class_vars = get_class_vars( __CLASS__ );

		$object_props = array_diff_key( $object_vars, $class_vars );

		return count( $object_props ) > 1;
	}


	/**
	 * Merges multiple props together.
	 *
	 * @since 2.0.0
	 */
	private function merge_multiple_props() {

		$vars = get_object_vars( $this );

		$class_vars = get_class_vars( __CLASS__ );

		$props = array_diff_key( $vars, $class_vars );

		foreach ( $props as $prop_key => $prop_value ) {
			$real_prop_name = $this->normalize_property_name( $prop_key );

			if ( ! isset( $this->{$real_prop_name} ) ) {
				$this->{$real_prop_name} = $prop_value;
				unset( $this->{$prop_key} );
				continue;
			}

			if ( ! $this->{$real_prop_name} instanceof Multiple_Property ) {
				# create new Multiple_Property
				$mp = new Multiple_Property();

				# copy the previous value
				$mp[] = $this->{$real_prop_name};

				# replace the previous value
				$this->{$real_prop_name} = $mp;
			}

			$this->{$real_prop_name}[] = $prop_value;

			unset( $this->{$prop_key} );
		}
	}


	/**
	 * Fills property values.
	 *
	 * @param array $meta_info
	 */
	private function fill_values( $meta_info ) {

		$vars = get_object_vars( $this );

		$class_vars = get_class_vars( __CLASS__ );

		$props = array_diff_key( $vars, $class_vars );

		foreach ( $props as $name => $var ) {
			if ( ! $this->{$name} instanceof Multiple_Property ) {
				$this->{$name} = $this->get_the_value( $var, $meta_info );
			} else {
				$sub_props = array();
				foreach ( $this->{$name} as $sub_prop_key => $sub_prop ) {
					$sub_props[ $sub_prop_key ] = $this->get_the_value( $sub_prop, $meta_info );
				}
				$this->{$name} = $sub_props;
			}
		}
	}


	/**
	 * Checks if the snippet has properties that can be overwritten.
	 *
	 * @since 2.2.0
	 *
	 * @return bool
	 */
	public function has_overridable_props() {

		$vars = get_object_vars( $this );

		$class_vars = get_class_vars( __CLASS__ );

		$props = array_diff_key( $vars, $class_vars );

		foreach ( $props as $name => $var ) {
			if ( ! isset( $var['overridable'] ) ) {
				continue;
			}

			if ( $var['overridable'] ) {
				return true;
			}
		}

		return false;
	}


	/**
	 * Overwrites values, if any.
	 *
	 * @since 2.2.0
	 *
	 * @param array $meta_info
	 *
	 */
	public function overwrite_values( $meta_info ) {

		if ( empty( $meta_info['current_post_id'] ) ) {
			return;
		}

		$overwrite_data = get_post_meta( $meta_info['current_post_id'], '_wpb_rs_overwrite_data', true );

		$vars = get_object_vars( $this );

		$class_vars = get_class_vars( __CLASS__ );

		$props = array_diff_key( $vars, $class_vars );

		foreach ( $props as $prop_name_with_id => $prop_value ) {

			if ( ! isset( $prop_value['overridable'] ) ) {
				continue;
			}

			if ( ! $prop_value['overridable'] ) {
				continue;
			}

			$prop_id = str_replace( 'prop-', '', strstr( $prop_name_with_id, 'prop-' ) );

			if ( ! isset( $overwrite_data[ $this->id ]['properties'][ $prop_id ] ) ) {
				continue;
			}

			$overwrite_value = $overwrite_data[ $this->id ]['properties'][ $prop_id ];

			if ( isset( $prop_value['overridable_multiple'] ) && $prop_value['overridable_multiple'] && is_array( $overwrite_value ) ) {
				# Add the first value to the current property
				$this->{$prop_name_with_id}[1] = array_shift( $overwrite_value );

				# Create new properties for the rest of the values.
				foreach ( $overwrite_value as $k => $ov ) {
					$new_prop_name_with_id             = $prop_name_with_id . '-' . $k;
					$this->{$new_prop_name_with_id}    = $this->{$prop_name_with_id};
					$this->{$new_prop_name_with_id}[1] = $ov;
				}

			} else {
				$this->{$prop_name_with_id}[1] = $overwrite_value;
			}
		}
	}


	/**
	 * Integrates custom JSON+LD values for the main snippet.
	 *
	 * @param array $meta_info
	 *
	 * @since 2.4.0
	 */
	private function inject_custom_json_ld( $meta_info ) {

		if ( ! isset( $meta_info['snippet_post_id'] ) ) {
			return;
		}

		if ( empty( $meta_info['snippet_post_id'] ) ) {
			return;
		}

		if ( ! $this->is_main_snippet() ) {
			return;
		}

		$json_ld_data = (array) get_post_meta( $meta_info['snippet_post_id'], '_wpb_rs_jsonld', true );

		$json_ld_data = apply_filters( 'wpbuddy/rich_snippets/rich_snippet/json+ld', $json_ld_data, $meta_info );

		$json_ld_data = array_filter( $json_ld_data );

		foreach ( $json_ld_data as $key => $value ) {
			$value = apply_filters( 'wpbuddy/rich_snippets/rich_snippet/json+ld/value', $value, $meta_info );
			$value = apply_filters( 'wpbuddy/rich_snippets/rich_snippet/json+ld/value/' . $key, $value, $meta_info );

			if ( empty( $value ) ) {
				continue;
			}

			$this->{$key} = $value;
		}
	}


	/**
	 * Get the plugin version the snippet was created with.
	 *
	 * @since 2.5.4
	 *
	 * @return string x.x.x format.
	 */
	public function get_version_created() {
		return $this->_version_created ?? null;
	}


	/**
	 * Sets the _is_main_snippet property. But only if the snippet was created with plugin version number 2.5.3 or
	 * lower (meaning, the version string is empty).
	 *
	 * @since      2.5.4
	 *
	 * @deprecated 2.4.5
	 *
	 * @param $val
	 */
	public function set_is_main_snippet( $val ) {
		$plugin_version = $this->get_version_created();

		if ( empty( $plugin_version ) ) {
			$this->_is_main_snippet = $val;
		}
	}


	/**
	 * If the current snippet is the main/parent snippet.
	 *
	 * @since 2.5.4
	 *
	 * @return bool
	 */
	public function is_main_snippet() {
		return $this->_is_main_snippet ?? false;
	}


	/**
	 * If a loop is configured for this snippet.
	 *
	 * @since 2.8.0
	 *
	 * @return bool
	 */
	public function is_loop() {
		return ! empty( $this->_loop );
	}


	/**
	 * The type of the loop.
	 *
	 * @since 2.8.0
	 *
	 * @return string
	 */
	public function get_loop_type() {
		return $this->_loop;
	}


	/**
	 * Returns the loop items.
	 *
	 * @since 2.8.0
	 *
	 * @return mixed[] Array of items (could be objects)
	 */
	public function get_items_for_loop( $post_id ) {

		$items = [];

		if ( 'main_query' === $this->_loop ) {
			global $wp_the_query;

			if ( isset( $wp_the_query ) && $wp_the_query instanceof \WP_Query ) {
				$items = $wp_the_query->get_posts();
			}

			$items = array_combine( wp_list_pluck( $items, 'ID' ), $items );

		} elseif ( 'page_parents' === $this->_loop ) {
			$items = get_post_ancestors( $post_id ); #@todo test this again
			$items = array_reverse( $items );
			$items = array_combine( $items, $items );

		} elseif ( 0 === stripos( $this->_loop, 'menu_' ) ) {

			$menu_name = str_replace( 'menu_', '', $this->_loop );

			$items = wp_get_nav_menu_items( $menu_name );

			$menu_id = call_user_func( function ( $items, $id ) {
				foreach ( $items as $item ) {
					if ( isset( $item->object_id ) && $item->object_id == $id ) {
						return $item->ID;
					}
				}

				return $id;
			}, $items, $post_id );

			$items = Helper_Model::instance()->filter_item_hierarchy(
				$items,
				$menu_id,
				'menu_item_parent',
				'ID'
			);

			$items = array_reverse( $items );
			$items = array_combine( wp_list_pluck( $items, 'object_id' ), $items );

		} elseif ( 0 === stripos( $this->_loop, 'taxonomy_' ) ) {
			$taxonomy = str_replace( 'taxonomy_', '', $this->_loop );

			if ( 'category' === $taxonomy ) {
				$term_id = Helper_Model::instance()->get_primary_category( $post_id );
			} else {
				$term_id = Helper_Model::instance()->get_primary_term( $taxonomy, $post_id );
			}

			$items   = get_ancestors( $term_id, $taxonomy, 'taxonomy' );
			$items   = array_reverse( $items );
			$items[] = $term_id;

			array_walk( $items, function ( &$term_id, $key, $taxonomy ) {
				$term = get_term( $term_id, $taxonomy );

				if ( ! $term instanceof \WP_Term ) {
					$term_id = null;

					return null;
				}

				$term_id = $term;
			}, $taxonomy );

			$items = array_filter( $items );
		}

		return apply_filters( 'wpbuddy/rich_snippets/rich_snippet/loop/items', $items, $this );
	}


	/**
	 * Resets the loop to NULL.
	 *
	 * @since 2.8.0
	 * @return void
	 */
	public function reset_loop() {
		$this->_loop = null;
	}
}
