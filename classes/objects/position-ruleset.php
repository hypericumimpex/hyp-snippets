<?php

namespace wpbuddy\rich_snippets;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

/**
 * Class Position_Ruleset
 *
 * @package wpbuddy\rich_snippets
 *
 * @since   2.0.0
 */
final class Position_Ruleset {

	/**
	 * @var \wpbuddy\rich_snippets\Position_Rule_Group[]
	 */
	private $rulegroups = [];


	/**
	 * Adds a new rulegroup.
	 *
	 * @since 2.0.0
	 *
	 * @param \wpbuddy\rich_snippets\Position_Rule_Group $group
	 */
	public function add_rulegroup( Position_Rule_Group $group ) {

		$this->rulegroups[] = $group;
	}


	/**
	 * Checks if the Ruleset has roulegroups.
	 *
	 * @since 2.0.0
	 *
	 * @return bool
	 */
	public function has_rulegroups(): bool {

		return $this->count() > 0;
	}


	/**
	 * Get the rulegroup count.
	 *
	 * @since 2.0.0
	 *
	 * @return int
	 */
	public function count(): int {

		return count( $this->rulegroups );
	}


	/**
	 * Returns rule groups.
	 *
	 * @return \wpbuddy\rich_snippets\Position_Rule_Group[]
	 */
	public function get_rulegroups(): array {

		return $this->rulegroups;
	}


	/**
	 * Checks if the rule groups matches.
	 *
	 * @since 2.0.0
	 *
	 * @return bool
	 */
	public function match(): bool {

		$bail_early = apply_filters( 'wpbuddy/rich_snippets/ruleset/match', null, $this );

		if ( is_bool( $bail_early ) ) {
			return $bail_early;
		}

		if ( ! $this->has_rulegroups() ) {
			return false;
		}

		foreach ( $this->get_rulegroups() as $rule_group ) {

			/**
			 * Every rule group is connected with OR.
			 * This means we can return true immaterially if one rule group returns true.
			 */
			if ( $rule_group->match() ) {
				return true;
			}
		}

		return false;
	}
}