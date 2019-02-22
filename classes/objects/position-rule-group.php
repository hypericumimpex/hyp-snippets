<?php

namespace wpbuddy\rich_snippets;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

/**
 * Class Position_Rule_Group
 *
 * @package wpbuddy\rich_snippets
 *
 * @since   2.0.0
 */
final class Position_Rule_Group {

	/**
	 * @var \wpbuddy\rich_snippets\Position_Rule[]
	 */
	private $rules = [];

	/**
	 * Adds a rule to the array of ruels.
	 *
	 * @since 2.0.0
	 *
	 * @param \wpbuddy\rich_snippets\Position_Rule $rule
	 */
	public function add_rule( Position_Rule $rule ) {

		$this->rules[] = $rule;
	}


	/**
	 * Get all rules.
	 *
	 * @since 2.0.0
	 *
	 * @return \wpbuddy\rich_snippets\Position_Rule[]
	 */
	public function get_rules(): array {

		return $this->rules;
	}


	/**
	 * Returns the number of rules.
	 *
	 * @since 2.0.0
	 *
	 * @return int
	 */
	public function count(): int {

		return count( $this->rules );
	}


	/**
	 * Checks if all rules matches.
	 *
	 * @since 2.0.0
	 *
	 * @return bool
	 */
	public function match(): bool {

		$bail_early = apply_filters( 'wpbuddy/rich_snippets/rulegroup/match', null, $this );

		if ( is_bool( $bail_early ) ) {
			return $bail_early;
		}

		/**
		 * All rules are connected with AND.
		 * This means we don't need to run through every rule.
		 * We can return false immediately if one rule returns false.
		 */
		foreach ( $this->get_rules() as $rule ) {
			if ( ! $rule->match() ) {
				return false;
			}
		}

		return true;
	}
}