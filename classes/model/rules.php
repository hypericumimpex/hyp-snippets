<?php

namespace wpbuddy\rich_snippets;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly


/**
 * Class Rules.
 *
 * Functions to read and write Rulesets.
 *
 * @package wpbuddy\rich_snippets
 *
 * @since   2.0.0
 */
final class Rules_Model {

	/**
	 * Fetches the Rulset.
	 *
	 * This is an array of groups where each array is connected as OR.
	 * Ex.:
	 * array(
	 *      0 => Rule connected with AND
	 *      1 => array( Ruleset connected with OR
	 *              1 => Rule connected with AND
	 *              2 => Rule connected with AND
	 *      )
	 * )
	 *
	 * @since 2.0.0
	 *
	 * @param int $post_id
	 *
	 * @return Position_Ruleset
	 */
	public static function get_ruleset( int $post_id ): Position_Ruleset {

		$ruleset = apply_filters( 'wpbuddy/rich_snippets/ruleset/get', null, $post_id );

		if ( $ruleset instanceof Position_Ruleset ) {
			return $ruleset;
		}

		$ruleset = get_post_meta( $post_id, '_wpb_rs_position', true );

		if ( $ruleset instanceof Position_Ruleset ) {
			return $ruleset;
		}

		return new Position_Ruleset();
	}


	/**
	 * Updates a ruleset.
	 *
	 * @since 2.0.0
	 *
	 * @param int              $post_id
	 * @param Position_Ruleset $ruleset
	 *
	 * @return bool
	 */
	public static function update_ruleset( int $post_id, Position_Ruleset $ruleset ): bool {

		return false !== update_post_meta( $post_id, '_wpb_rs_position', $ruleset );
	}

}
