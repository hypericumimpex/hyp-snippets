<?php

namespace wpbuddy\rich_snippets;

use wpbuddy\plugins\CommentRating\Frontend;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly


/**
 * Class Values.
 *
 * Prepares and fills registered values.
 *
 * @package wpbuddy\rich_snippets
 *
 * @since   2.0.0
 */
final class Values_Model {

	/**
	 * Magic method for setting up the class.
	 *
	 * @since 2.0.0
	 */
	public function __construct() {

		$methods = Fields_Model::get_internal_values_methods();
		$methods = array_merge( $methods, Fields_Model::get_reference_values_ids() );

		foreach ( $methods as $id => $function_or_method ) {

			if ( is_string( $function_or_method )
			     && method_exists( $this, $function_or_method )
			     && is_callable( [ $this, $function_or_method ] )
			) {
				add_filter(
					'wpbuddy/rich_snippets/rich_snippet/value/' . $id,
					[ $this, $function_or_method ],
					10,
					3
				);
			} else if ( is_array( $function_or_method )
			            && is_callable( $function_or_method )
			) {
				add_filter( 'wpbuddy/rich_snippets/rich_snippet/value/' . $id, $function_or_method, 10, 3 );
			} else if ( is_callable( $function_or_method ) ) {
				add_filter( 'wpbuddy/rich_snippets/rich_snippet/value/' . $id, $function_or_method, 10, 3 );
			} else {
				add_filter( 'wpbuddy/rich_snippets/rich_snippet/value/' . $id, [ $this, $function_or_method ], 10, 3 );
			}

		}

		add_filter( 'wpbuddy/rich_snippets/rich_snippet/value', array( $this, 'prepare_descendants' ), 10, 2 );

		do_action_ref_array( 'wpbuddy/rich_snippets/rich_snippet/values/init', array( &$this ) );
	}


	/**
	 * Fetches a call to function that doesn't exist.
	 *
	 * @since 2.0.0
	 *
	 * @param string $name
	 * @param array  $args
	 *
	 * @return mixed
	 */
	public function __call( $name, $args ) {

		if ( false !== stripos( $name, 'global_snippet_' ) ) {
			$args[2]['snippet_uid'] = str_replace( 'global_snippet_', '', $name );

			return $this->global_snippet( $args[0], $args[1], $args[2] );
		}

		return $args[0];
	}


	/**
	 * Returns the current post URL.
	 *
	 * @param                                     $val
	 * @param \wpbuddy\rich_snippets\Rich_Snippet $rich_snippet
	 * @param array                               $meta_info
	 *
	 * @since 2.0.0
	 *
	 * @return string
	 */
	public function current_post_url( $val, Rich_Snippet $rich_snippet, array $meta_info ): string {

		return (string) get_permalink( $meta_info['current_post_id'] );
	}


	/**
	 * Returns the current post content.
	 *
	 * @param                                     $val
	 * @param \wpbuddy\rich_snippets\Rich_Snippet $rich_snippet
	 * @param array                               $meta_info
	 *
	 * @since 2.0.0
	 *
	 * @return string
	 */
	public function current_post_content( $val, Rich_Snippet $rich_snippet, array $meta_info ): string {

		$post = get_post( $meta_info['current_post_id'] );

		if ( ! $post instanceof \WP_Post ) {
			return '';
		}

		ob_start();
		$content = do_shortcode( $post->post_content );
		ob_end_clean();

		return (string) esc_attr( strip_tags( $content ) );
	}


	/**
	 * Returns the current post thumbnail URL.
	 *
	 * @param                                     $val
	 * @param \wpbuddy\rich_snippets\Rich_Snippet $rich_snippet
	 * @param array                               $meta_info
	 *
	 * @since 2.0.0
	 *
	 * @return string
	 */
	public function current_post_thumbnail_url( $val, Rich_Snippet $rich_snippet, array $meta_info ): string {

		return (string) Helper_Model::instance()->get_thumbnail_meta(
			'url',
			$meta_info['current_post_id']
		);
	}


	/**
	 * Returns the current post thumbnail width.
	 *
	 * @param                                     $val
	 * @param \wpbuddy\rich_snippets\Rich_Snippet $rich_snippet
	 * @param array                               $meta_info
	 *
	 * @since 2.0.0
	 *
	 * @return int
	 */
	public function current_post_thumbnail_width( $val, Rich_Snippet $rich_snippet, array $meta_info ): int {

		return (int) Helper_Model::instance()->get_thumbnail_meta(
			'width',
			$meta_info['current_post_id']
		);

	}


	/**
	 * Returns the current post thumbnail height.
	 *
	 * @param                                     $val
	 * @param \wpbuddy\rich_snippets\Rich_Snippet $rich_snippet
	 * @param array                               $meta_info
	 *
	 * @since 2.0.0
	 *
	 * @return int
	 */
	public function current_post_thumbnail_height( $val, Rich_Snippet $rich_snippet, array $meta_info ): int {

		return (int) Helper_Model::instance()->get_thumbnail_meta(
			'height',
			$meta_info['current_post_id']
		);
	}


	/**
	 * Returns the current post title.
	 *
	 * @param                                     $val
	 * @param \wpbuddy\rich_snippets\Rich_Snippet $rich_snippet
	 * @param array                               $meta_info
	 *
	 * @since 2.0.0
	 *
	 * @return string
	 */
	public function current_post_title( $val, Rich_Snippet $rich_snippet, array $meta_info ): string {

		return strip_tags( get_the_title( $meta_info['current_post_id'] ) );
	}


	/**
	 * Returns the current post excerpt.
	 *
	 * @param                                     $val
	 * @param \wpbuddy\rich_snippets\Rich_Snippet $rich_snippet
	 * @param array                               $meta_info
	 *
	 * @since 2.0.0
	 *
	 * @return string
	 */
	public function current_post_excerpt( $val, Rich_Snippet $rich_snippet, array $meta_info ): string {

		$post = get_post( $meta_info['current_post_id'] );

		if ( ! $post instanceof \WP_Post ) {
			return '';
		}

		if ( post_password_required( $meta_info['current_post_id'] ) ) {
			return '';
		}

		if ( ! empty( $post->post_excerpt ) ) {
			return strip_tags( $post->post_excerpt );
		}

		return strip_tags( get_the_excerpt( $meta_info['current_post_id'] ) );
	}


	/**
	 * Returns the current post date.
	 *
	 * @param                                     $val
	 * @param \wpbuddy\rich_snippets\Rich_Snippet $rich_snippet
	 * @param array                               $meta_info
	 *
	 * @since 2.0.0
	 *
	 * @return string A date value in ISO 8601 date format.
	 */
	public function current_post_date( $val, Rich_Snippet $rich_snippet, array $meta_info ): string {

		return (string) get_the_date( 'c', $meta_info['current_post_id'] );
	}


	/**
	 * Returns the current post modified date.
	 *
	 * @param                                     $val
	 * @param \wpbuddy\rich_snippets\Rich_Snippet $rich_snippet
	 * @param array                               $meta_info
	 *
	 * @return string A date value in ISO 8601 date format.
	 */
	public function current_post_modified_date( $val, Rich_Snippet $rich_snippet, array $meta_info ): string {

		return (string) get_the_modified_date( 'c', $meta_info['current_post_id'] );
	}


	/**
	 * Returns the current post author name.
	 *
	 * @param                                     $val
	 * @param \wpbuddy\rich_snippets\Rich_Snippet $rich_snippet
	 * @param array                               $meta_info
	 *
	 * @since 2.0.0
	 *
	 * @return string
	 */
	public function current_post_author_name( $val, Rich_Snippet $rich_snippet, array $meta_info ): string {

		return (string) Helper_Model::instance()->get_author_meta_by_post_id(
			'display_name',
			$meta_info['current_post_id']
		);

	}


	/**
	 * Returns the current post author url.
	 *
	 * @param                                     $val
	 * @param \wpbuddy\rich_snippets\Rich_Snippet $rich_snippet
	 * @param array                               $meta_info
	 *
	 * @since 2.0.0
	 *
	 * @return string
	 */
	public function current_post_author_url( $val, Rich_Snippet $rich_snippet, array $meta_info ): string {

		$author_url = (string) Helper_Model::instance()->get_author_meta_by_post_id(
			'user_url',
			$meta_info['current_post_id']
		);

		if ( ! empty( $author_url ) ) {
			return $author_url;
		}

		return (string) get_author_posts_url(
			Helper_Model::instance()->get_author_id( $meta_info['current_post_id'] )
		);
	}


	/**
	 * Returns the blog title.
	 *
	 * @param                                     $val
	 * @param \wpbuddy\rich_snippets\Rich_Snippet $rich_snippet
	 * @param array                               $meta_info
	 *
	 * @since 2.0.0
	 *
	 * @return string
	 */
	public function blog_title( $val, Rich_Snippet $rich_snippet, array $meta_info ): string {

		return (string) get_bloginfo( 'name' );
	}


	/**
	 * Returns the blog description.
	 *
	 * @param                                     $val
	 * @param \wpbuddy\rich_snippets\Rich_Snippet $rich_snippet
	 * @param array                               $meta_info
	 *
	 * @since 2.0.0
	 *
	 * @return string
	 */
	public function blog_description( $val, Rich_Snippet $rich_snippet, array $meta_info ): string {

		return (string) get_bloginfo( 'description' );
	}


	/**
	 * Returns the blog URL.
	 *
	 * @param                                     $val
	 * @param \wpbuddy\rich_snippets\Rich_Snippet $rich_snippet
	 * @param array                               $meta_info
	 *
	 * @since 2.0.0
	 *
	 * @return string
	 */
	public function blog_url( $val, Rich_Snippet $rich_snippet, array $meta_info ): string {

		return (string) site_url();
	}


	/**
	 * Returns the site icon image URL.
	 *
	 * @param                                     $val
	 * @param \wpbuddy\rich_snippets\Rich_Snippet $rich_snippet
	 * @param array                               $meta_info
	 *
	 * @since 2.0.0
	 *
	 * @return string
	 */
	public function site_icon_url( $val, Rich_Snippet $rich_snippet, array $meta_info ): string {

		if ( ! has_site_icon() ) {
			return '';
		}

		return (string) Helper_Model::instance()->get_media_meta(
			'url',
			get_option( 'site_icon' )
		);
	}


	/**
	 * Returns the site icon width.
	 *
	 * @param                                     $val
	 * @param \wpbuddy\rich_snippets\Rich_Snippet $rich_snippet
	 * @param array                               $meta_info
	 *
	 * @since 2.0.0
	 *
	 * @return string
	 */
	public function site_icon_width( $val, Rich_Snippet $rich_snippet, array $meta_info ): string {

		if ( ! has_site_icon() ) {
			return '';
		}

		return (string) Helper_Model::instance()->get_media_meta(
			'width',
			get_option( 'site_icon' )
		);
	}


	/**
	 * Returns the site icon height.
	 *
	 * @param                                     $val
	 * @param \wpbuddy\rich_snippets\Rich_Snippet $rich_snippet
	 * @param array                               $meta_info
	 *
	 * @since 2.0.0
	 *
	 * @return string
	 */
	public function site_icon_height( $val, Rich_Snippet $rich_snippet, array $meta_info ): string {

		if ( ! has_site_icon() ) {
			return '';
		}

		return (string) Helper_Model::instance()->get_media_meta(
			'height',
			get_option( 'site_icon' )
		);
	}


	/**
	 * Returns the ID to the current post content.
	 *
	 * @param                                     $val
	 * @param \wpbuddy\rich_snippets\Rich_Snippet $rich_snippet
	 * @param array                               $meta_info
	 *
	 * @since      2.0.0
	 *
	 * @deprecated 2.2.0 Return post content instead.
	 *
	 * @return string
	 */
	public function current_post_content_id( $val, Rich_Snippet $rich_snippet, array $meta_info ) {

		return self::current_post_content( $val, $rich_snippet, $meta_info );
	}


	/**
	 * Returns a sub element to be included into JSON-LD.
	 *
	 * @param                                     $val
	 * @param \wpbuddy\rich_snippets\Rich_Snippet $rich_snippet
	 * @param array                               $meta_info
	 *
	 * @since 2.0.0
	 *
	 * @return null|\wpbuddy\rich_snippets\Rich_Snippet
	 */
	public function global_snippet( $val, Rich_Snippet $rich_snippet, array $meta_info ) {

		$post_id = Helper_Model::instance()->get_post_id_by_snippet_uid( $meta_info['snippet_uid'] );

		$rich_snippets = Snippets_Model::get_snippets( $post_id );

		if ( count( $rich_snippets ) <= 0 ) {
			return null;
		}

		if ( ! isset( $rich_snippets[ $meta_info['snippet_uid'] ] ) ) {
			return null;
		}

		/**
		 * @var \wpbuddy\rich_snippets\Rich_Snippet $child_snippet
		 */
		$child_snippet = $rich_snippets[ $meta_info['snippet_uid'] ];

		$child_snippet->prepare_for_output( array(
			'current_post_id' => $meta_info['current_post_id'],
			'snippet_post_id' => $post_id,
		) );

		return $child_snippet;

	}


	/**
	 * Prepares descendants for output.
	 *
	 * @since 2.0.0
	 *
	 * @param mixed  $var
	 * @param string $name
	 *
	 * @return mixed
	 */
	public function prepare_descendants( $var, $name ) {

		if ( 0 === stripos( $name, 'descendant-' ) ) {
			return str_replace( 'descendant-', '', $name );
		}

		return $var;
	}


	/**
	 * Returns the value of a meta field.
	 *
	 * @param                                     $val
	 * @param \wpbuddy\rich_snippets\Rich_Snippet $rich_snippet
	 * @param array                               $meta_info
	 *
	 * @since 2.0.0
	 *
	 * @return string
	 */
	public function textfield_meta( $val, Rich_Snippet $rich_snippet, array $meta_info ) {

		if ( ! is_scalar( $val ) ) {
			return '';
		}

		if ( empty( $val ) ) {
			return '';
		}

		$meta_value = get_post_meta( $meta_info['current_post_id'], $val, true );

		if ( ! is_scalar( $meta_value ) ) {
			return '';
		}

		return (string) $meta_value;

	}


	/**
	 * Returns the ID to a reference.
	 *
	 * @param                                     $val
	 * @param \wpbuddy\rich_snippets\Rich_Snippet $rich_snippet
	 * @param array                               $meta_info
	 *
	 * @since 2.2.0
	 *
	 * @return \stdClass
	 */
	public function textfield_id( $val, Rich_Snippet $rich_snippet, array $meta_info ): \stdClass {

		$obj          = new \stdClass();
		$obj->{'@id'} = Helper_Model::instance()->sanitize_html_id( $val );

		return $obj;
	}


	/**
	 * Returns the current post ID.
	 *
	 * @param                                     $val
	 * @param \wpbuddy\rich_snippets\Rich_Snippet $rich_snippet
	 * @param array                               $meta_info
	 *
	 * @since 2.6.0
	 *
	 * @return \string
	 */
	public function current_post_id( $val, Rich_Snippet $rich_snippet, array $meta_info ) {
		return (string) $meta_info['current_post_id'];
	}


	/**
	 * Returns the name of the current category.
	 *
	 * @param                                     $val
	 * @param \wpbuddy\rich_snippets\Rich_Snippet $rich_snippet
	 * @param array                               $meta_info
	 *
	 * @since 2.7.0
	 *
	 * @return \string
	 */
	public function current_category( $val, Rich_Snippet $rich_snippet, array $meta_info ) {
		$primary_category_id = Helper_Model::instance()->get_primary_category( $meta_info['current_post_id'] );

		if ( empty( $primary_category_id ) ) {
			return '';
		}

		$category_name = get_the_category_by_ID( $primary_category_id );

		if ( is_wp_error( $category_name ) ) {
			return '';
		}

		return $category_name;
	}


	/**
	 * Returns the URL of the current category.
	 *
	 * @param                                     $val
	 * @param \wpbuddy\rich_snippets\Rich_Snippet $rich_snippet
	 * @param array                               $meta_info
	 *
	 * @since 2.7.0
	 *
	 * @return \string
	 */
	public function current_category_url( $val, Rich_Snippet $rich_snippet, array $meta_info ) {
		$primary_category_id = Helper_Model::instance()->get_primary_category( $meta_info['current_post_id'] );

		if ( empty( $primary_category_id ) ) {
			return '';
		}

		$category_url = get_term_link( $primary_category_id );

		if ( is_wp_error( $category_url ) ) {
			return '';
		}

		return esc_url_raw( $category_url );
	}


	/**
	 * Returns the search URL.
	 *
	 * @param                                     $val
	 * @param \wpbuddy\rich_snippets\Rich_Snippet $rich_snippet
	 * @param array                               $meta_info
	 *
	 * @since 2.8.0
	 *
	 * @return \string
	 */
	public function search_url( $val, Rich_Snippet $rich_snippet, array $meta_info ) {
		return get_search_link();
	}


	/**
	 * Returns the search URL with {search_url_search_term} parameter.
	 *
	 * @param                                     $val
	 * @param \wpbuddy\rich_snippets\Rich_Snippet $rich_snippet
	 * @param array                               $meta_info
	 *
	 * @since 2.8.0
	 *
	 * @return \string
	 */
	public function search_url_search_term( $val, Rich_Snippet $rich_snippet, array $meta_info ) {
		return add_query_arg( [ 's' => '{search_term_string}' ], home_url() );
	}


	/**
	 * Returns a sequential number.
	 *
	 * @param                                     $val
	 * @param \wpbuddy\rich_snippets\Rich_Snippet $rich_snippet
	 * @param array                               $meta_info
	 *
	 * @since 2.8.0
	 *
	 * @return string
	 */
	public function textfield_sequential_number( $val, Rich_Snippet $rich_snippet, array $meta_info ) {
		static $sequences;

		if ( ! isset( $sequences ) ) {
			$sequences = [];
		}

		if ( ! is_scalar( $val ) ) {
			return '';
		}

		if ( empty( $val ) ) {
			$val = 'global';
		}

		if ( ! isset( $sequences[ $val ] ) ) {

			# Check if we have a starting number
			$val_arr = explode( ':', $val );
			if ( isset( $val_arr[1] ) ) {
				$sequences[ $val ] = absint( $val_arr[1] );
			} else {
				$sequences[ $val ] = 0;
			}
		}

		$sequences[ $val ] = intval( $sequences[ $val ] ) + 1;

		return $sequences[ $val ];
	}


	/**
	 * Returns the term title.
	 *
	 * @param                                     $val
	 * @param \wpbuddy\rich_snippets\Rich_Snippet $rich_snippet
	 * @param array                               $meta_info
	 *
	 * @since 2.8.0
	 *
	 * @return string
	 */
	public function term_title( $val, Rich_Snippet $rich_snippet, array $meta_info ) {
		if ( ! isset( $meta_info['object'] ) ) {
			return '';
		}

		$term = $meta_info['object'];

		if ( ! $term instanceof \WP_Term ) {
			return '';
		}


		return $term->name;
	}

	/**
	 * Returns the term URL.
	 *
	 * @param                                     $val
	 * @param \wpbuddy\rich_snippets\Rich_Snippet $rich_snippet
	 * @param array                               $meta_info
	 *
	 * @since 2.8.0
	 *
	 * @return string
	 */
	public function term_url( $val, Rich_Snippet $rich_snippet, array $meta_info ) {
		if ( ! isset( $meta_info['object'] ) ) {
			return '';
		}

		$term = $meta_info['object'];

		if ( ! $term instanceof \WP_Term ) {
			return '';
		}

		$link = get_term_link( $term->term_id, $term->taxonomy );

		if ( is_wp_error( $link ) ) {
			return '';
		}

		return $link;
	}

}
