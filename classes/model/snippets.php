<?php

namespace wpbuddy\rich_snippets;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly


/**
 * Class Snippets.
 *
 * A model to handle snippets built with this plugin.
 *
 * @package wpbuddy\rich_snippets
 *
 * @since   2.0.0
 */
final class Snippets_Model {


	/**
	 * Saves Rich Snippets to a post.
	 *
	 * @param int                                   $post_id
	 * @param \wpbuddy\rich_snippets\Rich_Snippet[] $rich_snippets
	 *
	 * @return bool
	 */
	public static function update_snippets( int $post_id, array $rich_snippets ): bool {

		return false !== update_post_meta( $post_id, '_wpb_rs_schema', $rich_snippets );
	}


	/**
	 * Returns Rich_Snippet-Objects from a single post.
	 *
	 * @param int $post_id
	 *
	 * @since 2.0.0
	 *
	 * @return \wpbuddy\rich_snippets\Rich_Snippet[]
	 */
	public static function get_snippets( int $post_id ): array {

		$rich_snippets = get_post_meta( $post_id, '_wpb_rs_schema', true );

		if ( ! is_array( $rich_snippets ) ) {
			return array();
		}

		$rich_snippets = array_map( function ( $snippet ) {

			if ( ! $snippet instanceof Rich_Snippet ) {
				return new Rich_Snippet( [
					'_is_main_snippet' => true,
				] );
			}

			$snippet->set_is_main_snippet( true );

			return $snippet;
		}, $rich_snippets );


		return apply_filters( 'wpbuddy/rich_snippets/model/schemas/get', $rich_snippets, $post_id );
	}


	/**
	 * Returns a single snippet.
	 *
	 * @since 2.0.0
	 *
	 * @param string $snippet_id
	 * @param int    $post_id
	 *
	 * @return bool|\wpbuddy\rich_snippets\Rich_Snippet
	 */
	public static function get_snippet( string $snippet_id, int $post_id ) {

		$snippets = self::get_snippets( $post_id );

		if ( isset( $snippets[ $snippet_id ] ) ) {
			return $snippets[ $snippet_id ];
		}

		return false;
	}


	/**
	 * Deletes a snippet from a post.
	 *
	 * @param string $snippet_id
	 * @param int    $post_id
	 *
	 * @return bool|\WP_Error
	 */
	public static function delete_snippet( $snippet_id, $post_id ) {

		$snippets = self::get_snippets( $post_id );

		if ( ! isset( $snippets[ $snippet_id ] ) ) {
			return true;
		}

		unset( $snippets[ $snippet_id ] );
		$snippets_updated = self::update_snippets( $post_id, $snippets );

		if ( ! $snippets_updated ) {
			return new \WP_Error(
				'wpbuddy/rich_snippets/schemas/delete',
				__( 'Could not delete snippet.', 'rich-snippets-schema' )
			);
		}

		return true;

	}


	/**
	 * Get the first found snippet from a post.
	 *
	 * @since 2.0.0
	 *
	 * @param int $post_id
	 *
	 * @return \wpbuddy\rich_snippets\Rich_Snippet
	 */
	public static function get_first_snippet( int $post_id ) {

		$snippets = self::get_snippets( $post_id );
		$snippets = array_values( $snippets );

		if ( isset( $snippets[0] ) ) {
			return $snippets[0];
		}

		return new Rich_Snippet();
	}


	/**
	 * Gets the post ID where the snippet with a specific ID is saved.
	 *
	 * @since 2.2.0
	 *
	 * @param string $snippet_id
	 *
	 * @return int
	 */
	public static function get_post_id_by_snippet_id( $snippet_id ) {

		if ( empty( $snippet_id ) ) {
			return 0;
		}

		global $wpdb;

		$like = sprintf( '%%"%s"%%', $wpdb->esc_like( $snippet_id ) );
		$sql  = $wpdb->prepare(
			"SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key='_wpb_rs_schema' AND meta_value LIKE '%s' LIMIT 1",
			$like
		);

		$post_id = $wpdb->get_var( $sql );

		return absint( $post_id );
	}
}
