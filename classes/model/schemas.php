<?php

namespace wpbuddy\rich_snippets;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly


/**
 * Class Schemas_Model.
 *
 * The model to handle schema types and properties from schema.org.
 *
 * Any schema.org related stuff.
 *
 * @package wpbuddy\rich_snippets
 *
 * @since   2.0.0
 */
final class Schemas_Model {

	/**
	 * Returns an array of popular classes.
	 *
	 * @since 2.0.0
	 *
	 * @return array
	 */
	public static function get_popular_types() {

		return apply_filters(
			'wpbuddy/rich_snippets/schemas/types/popular',
			array(
				'http://schema.org/Product'       => __( 'Product', 'rich-snippets-schema' ),
				'http://schema.org/Event'         => __( 'Event', 'rich-snippets-schema' ),
				'http://schema.org/LocalBusiness' => __( 'Local Business', 'rich-snippets-schema' ),
				'http://schema.org/Restaurant'    => __( 'Restaurant', 'rich-snippets-schema' ),
				'http://schema.org/Recipe'        => __( 'Recipe', 'rich-snippets-schema' ),
				'http://schema.org/Article'       => __( 'Article', 'rich-snippets-schema' ),
				'http://schema.org/Review'        => __( 'Review', 'rich-snippets-schema' ),
				'http://schema.org/Movie'         => __( 'Movie', 'rich-snippets-schema' ),
				'http://schema.org/Video'         => __( 'Video', 'rich-snippets-schema' ),
			)
		);
	}


	/**
	 * Returns settings from a single property.
	 *
	 * @param string $id
	 *
	 * @since 2.0.0
	 *
	 * @return bool|\wpbuddy\rich_snippets\Schema_Property Returns a Schema_Property object if property was found.
	 *     Otherwise false.
	 */
	public static function get_property_by_id( $id ) {

		$all_props = self::get_properties( array( 'return_type' => 'all' ) );

		if ( is_wp_error( $all_props ) ) {
			return false;
		}

		foreach ( $all_props as $prop ) {
			if ( $prop->id === $id ) {
				return $prop;
			}
		}

		return false;
	}


	/**
	 * Searches for properties.
	 *
	 * If no class param is given, this will return all properties.
	 *
	 * @since 2.0.0
	 *
	 * @note $type = all can return more than 800+ items at once. You should consider adding a search term ($q).
	 *
	 * @param        array ['class']
	 *
	 * @param array $args [] {
	 * Array of arguments.
	 *
	 * @type string $class The schema.org class type (ie. http://schema.org/Product).
	 * @type string $type Could exact|all|required|guess. Exact means "only properties that can be attached to a
	 *     specific class-only".
	 * @type string $q The search term.
	 * }
	 *
	 * @return Schema_Property[]|\WP_Error
	 */
	public static function get_properties( $args ) {

		$args = wp_parse_args( $args, array(
			'schema_type' => '',
			'return_type' => '',
			'q'           => '',
		) );

		$params = http_build_query( array(
			'schema_type' => $args['schema_type'],
			'return_type' => $args['return_type'],
			'q'           => $args['q'],
		) );

		$response = WPBuddy_Model::request(
			'/wpbuddy/rich_snippets_manager/v1/schemas/properties?' . $params
		);

		if ( is_wp_error( $response ) ) {
			return $response;
		}

		if ( ! isset( $response->properties ) || ( isset( $response->properties ) && ! is_array( $response->properties ) ) ) {
			return new \WP_Error(
				'wpbuddy/rich_snippets/rest/properties',
				_x( 'The WP-Buddy API did not return any properties.', 'Thrown error on rest api when there was no list of properties found.', 'rich-snippets-schema' )
			);
		}

		$response->properties = array_map( function ( $obj ) {

			return new Schema_Property( $obj );
		}, $response->properties );

		return $response->properties;
	}


	/**
	 * Returns all types.
	 *
	 * If a search parameter is given, this will only return those results.
	 *
	 * @since 2.0.0
	 *
	 * @param string $q Search parameter
	 *
	 * @return array|\WP_Error
	 */
	public static function get_types( $q = null ) {

		$params = http_build_query( array(
			'q' => $q,
		) );

		$response = WPBuddy_Model::request(
			'/wpbuddy/rich_snippets_manager/v1/schemas/types?' . $params
		);

		if ( ! isset( $response->classes ) ) {
			return new \WP_Error(
				'wpbuddy/rich_snippets/rest/types',
				_x( 'The WP-Buddy API did not return any schema types.', 'Thrown error on rest api when there was no list of classes found.', 'rich-snippets-schema' )
			);
		}

		return $response->classes;
	}


	/**
	 * Return all children for a schema type.
	 *
	 * @since 2.3.0
	 *
	 * @param string $schema_type
	 *
	 * @return array|\WP_Error
	 */
	public static function get_children( string $schema_type ) {

		$params = http_build_query( array(
			'parent_schema_type' => $schema_type,
		) );

		$response = WPBuddy_Model::request(
			'/wpbuddy/rich_snippets_manager/v1/schemas/type_children?' . $params
		);

		if ( ! isset( $response->children ) ) {
			return new \WP_Error(
				'wpbuddy/rich_snippets/rest/type_children',
				_x( 'The WP-Buddy API did not return any children.', 'Thrown error on rest api when there was no list of children found.', 'rich-snippets-schema' )
			);
		}

		return (array) $response->children;
	}

	/**
	 * Returns all types.
	 *
	 * If a search parameter is given, this will only return those results.
	 *
	 * @since 2.0.0
	 *
	 * @param array $schema_types
	 *
	 * @return array|\WP_Error
	 */
	public static function get_type_descendants( $schema_types ) {

		$params = http_build_query( array(
			'schema_types' => $schema_types,
		) );

		$response = WPBuddy_Model::request(
			'/wpbuddy/rich_snippets_manager/v1/schemas/type_descendants?' . $params
		);

		if ( ! isset( $response->descendants ) ) {
			return new \WP_Error(
				'wpbuddy/rich_snippets/rest/type_descendants',
				_x( 'The WP-Buddy API did not return any descendants.', 'Thrown error on rest api when there was no list of type descendants found.', 'rich-snippets-schema' )
			);
		}

		return $response->descendants;
	}
}
