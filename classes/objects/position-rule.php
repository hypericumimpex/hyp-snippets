<?php

namespace wpbuddy\rich_snippets;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

/**
 * Class Position_Rule
 *
 * @package wpbuddy\rich_snippets
 *
 * @since   2.0.0
 */
final class Position_Rule {

	/**
	 * The param.
	 *
	 * @since 2.0.0
	 *
	 * @var null|string
	 */
	public $param = null;


	/**
	 * The operator.
	 *
	 * @since 2.0.0
	 *
	 * @var null|string
	 */
	public $operator = null;


	/**
	 * The value.
	 *
	 * @since 2.0.0
	 *
	 * @var mixed
	 */
	public $value = null;


	/**
	 * Checks if the rule matches with the current page.
	 *
	 * @return bool
	 */
	public function match(): bool {

		$bail_early = apply_filters( 'wpbuddy/rich_snippets/rule/match/bail_early', null, $this );

		if ( is_bool( $bail_early ) ) {
			return $bail_early;
		}

		$ret = false;

		$method_name = sprintf( 'match_%s', $this->param );

		if ( method_exists( $this, $method_name ) ) {
			$ret = $this->{$method_name}();
		}

		$ret = boolval( apply_filters( 'wpbuddy/rich_snippets/rule/' . $method_name, $ret, $this ) );

		return boolval( apply_filters( 'wpbuddy/rich_snippets/rule/match', $ret, $this ) );
	}


	/**
	 * Returns the current main query.
	 *
	 * @since 2.0.0
	 *
	 * @return \WP_Query
	 */
	private function get_query() {

		# are we in the main query?
		if ( is_main_query() ) {
			global $wp_query;

			return $wp_query;
		}

		global $wp_the_query;

		return $wp_the_query;
	}


	/**
	 * Compares a value with the internal value of the Rule.
	 *
	 * @param mixed $value
	 *
	 * @return bool
	 */
	private function compare( $value ): bool {

		# non-scalar types are not supported.
		if ( ! is_scalar( $value ) ) {
			return false;
		}

		switch ( $this->operator ) {
			case '!=':
				return $this->value !== $value;
				break;
			case '==':
			default:
				return $this->value === $value;
		}
	}


	/**
	 * Checks if the post ID matches.
	 *
	 * @since 2.0.0
	 *
	 * @return bool
	 */
	private function match_post(): bool {

		$query = $this->get_query();

		if ( ! $query->is_singular() ) {
			return false;
		}

		$current_post = $query->post;

		if ( ! is_a( $current_post, '\WP_Post' ) ) {
			return false;
		}

		# allow identical comparison
		$this->value = absint( $this->value );

		return $this->compare( $current_post->ID );
	}


	/**
	 * Checks if the current post matches a post type.
	 *
	 * @since 2.0.0
	 *
	 * @return bool
	 */
	private function match_post_type(): bool {

		$query = $this->get_query();

		if ( ! $query->is_singular() ) {
			return false;
		}

		$current_post = $query->post;

		if ( ! is_a( $current_post, '\WP_Post' ) ) {
			return false;
		}

		return $this->compare( $current_post->post_type );
	}


	/**
	 * Checks if the current post matches a specific template.
	 *
	 * @since 2.0.0
	 *
	 * @param string $post_type
	 *
	 * @return bool
	 */
	private function match_post_template( string $post_type = '' ): bool {

		$query = $this->get_query();

		if ( empty( $post_type ) ) {
			# extract the post type
			$post_type = strstr( $this->value, ':', true );
		}

		if ( ! $query->is_singular( $post_type ) ) {
			return false;
		}

		$file = get_page_template_slug( $query->post->ID );

		# if the default template is in use, get_page_template_slug() will return an empty string
		if ( empty( $file ) ) {
			$file = 'default';
		}

		return $this->compare(
			sprintf(
				'%s%s%s',
				$post_type,
				! empty( $post_type ) ? ':' : '',
				$file
			)
		);
	}


	/**
	 * Match a page template.
	 *
	 * @since 2.0.0
	 *
	 * @return bool
	 */
	private function match_page_template(): bool {

		$this->value = 'page:' . $this->value;

		return $this->match_post_template( 'page' );
	}


	/**
	 * Checks if the current post has a special post status.
	 *
	 * @since 2.0.0
	 *
	 * @return bool
	 */
	private function match_post_status(): bool {

		$query = $this->get_query();

		if ( ! $query->is_singular() ) {
			return false;
		}

		$current_post = $query->post;

		if ( ! is_a( $current_post, '\WP_Post' ) ) {
			return false;
		}

		return $this->compare( get_post_status( $current_post ) );

	}


	/**
	 * Checks if the current post has a special post format.
	 *
	 * @since 2.0.0
	 *
	 * @return bool
	 */
	private function match_post_format(): bool {

		$query = $this->get_query();

		if ( ! $query->is_singular() ) {
			return false;
		}

		$current_post = $query->post;

		if ( ! is_a( $current_post, '\WP_Post' ) ) {
			return false;
		}

		return $this->compare( get_post_format( $current_post ) );
	}


	/**
	 * Checks if the current page is a term.
	 *
	 * @param string $taxonomy
	 *
	 * @since 2.0.0
	 *
	 * @return bool
	 */
	private function match_term( string $taxonomy ) {

		$query = $this->get_query();

		# categories are only allowed in 'post' post types
		if ( $query->is_singular( 'post' ) ) {
			$current_post = $query->post;

			if ( ! is_a( $current_post, '\WP_Post' ) ) {
				return false;
			}

			$comp = has_term( $this->value, $taxonomy, $current_post ) ? "{$taxonomy}:{$this->value}" : '';

			$this->value = "{$taxonomy}:{$this->value}";

			return $this->compare( $comp );

		} elseif ( $query->is_category() || $query->is_tag() || $query->is_tax() ) {
			return $this->compare( $query->queried_object_id );
		}

		return false;

	}


	/**
	 * Checks if the post has a specific category.
	 *
	 * @since 2.0.0
	 *
	 * @return bool
	 */
	private function match_post_category(): bool {

		return $this->match_term( 'category' );

	}


	/**
	 * Checks if a current post has a specific taxonomy.
	 *
	 * @since 2.0.0
	 *
	 * @return bool
	 */
	private function match_post_taxonomy(): bool {

		# extract the post type
		$taxonomy = strstr( $this->value, ':', true );

		# remove taxonomy from the value and make to INT
		$this->value = absint( str_replace( $taxonomy . ':', '', $this->value ) );

		return $this->match_term( $taxonomy );
	}


	/**
	 * Compares a page type (and any special pages).
	 *
	 * @since 2.0.0
	 *
	 * @return bool
	 */
	private function match_page_type(): bool {

		$query = $this->get_query();

		$current_page = '';

		if ( 'all' === $this->value ) {
			$current_page = $this->value;
		} elseif ( 'top_level' === $this->value ) {
			# check if current post is a top level post

			$current_post = $query->post;

			if ( ! is_a( $current_post, '\WP_Post' ) ) {
				return false;
			}

			$current_page = empty( $current_post->post_parent ) ? 'top_level' : 'sub_level';

		} elseif ( 'parent' === $this->value ) {
			# check if current post has children

			$current_post = $query->post;

			if ( ! is_a( $current_post, '\WP_Post' ) ) {
				return false;
			}

			$children = get_posts( array(
				'post_type'      => $current_post->post_type,
				'post_parent'    => $current_post->ID,
				'posts_per_page' => 1,
				'fields'         => 'ids',
			) );

			$current_page = count( $children ) > 0 ? 'parent' : 'no_parent';
		} elseif ( 'child' === $this->value ) {
			# check if the current post has a parent

			$current_post = $query->post;

			if ( ! is_a( $current_post, '\WP_Post' ) ) {
				return false;
			}

			$current_page = ! empty( $current_post->post_parent ) ? 'child' : 'no_child';
		} elseif ( $query->is_front_page() ) {
			$current_page = 'front_page';
		} elseif ( $query->is_home() ) {
			$current_page = 'posts_page';
		} elseif ( $query->is_archive() && 'archive' === $this->value ) {
			$current_page = 'archive';
		} elseif ( $query->is_tax() ) {
			$taxonomy = $query->get_queried_object();
			if ( $taxonomy instanceof \WP_Term ) {
				$current_page = 'archive_' . $taxonomy->taxonomy;
			}
		} elseif ( $query->is_search() ) {
			$current_page = 'search';
		}

		return $this->compare( $current_page );
	}


	/**
	 * Checks if the current page has a particular parent page.
	 *
	 * @since 2.0.0
	 *
	 * @return bool
	 */
	private function match_page_parent(): bool {

		$query = $this->get_query();

		if ( ! $query->is_singular() ) {
			return false;
		}

		$current_post = $query->post;

		if ( ! is_a( $current_post, '\WP_Post' ) ) {
			return false;
		}

		$this->value = absint( $this->value );

		return $this->compare( $current_post->post_parent );
	}
}