<?php

namespace wpbuddy\rich_snippets;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly


/**
 * Class Helper.
 *
 * Helps to fetch some data.
 *
 * @package wpbuddy\rich_snippets
 *
 * @since   2.0.0
 */
final class Helper_Model {


	/**
	 * Media metadata cache.
	 *
	 * @var array
	 */
	private $media_meta = array();


	/**
	 * Author meta cache.
	 *
	 * @var array
	 */
	private $author_meta = array();


	/**
	 * The instance.
	 *
	 * @var Helper_Model
	 *
	 * @since 2.0.0
	 */
	protected static $_instance = null;


	/**
	 * If this instance has been initialized already.
	 *
	 * @since 2.0.0
	 *
	 * @var bool
	 */
	protected $_initialized = false;

	/**
	 *
	 * Get the singleton instance.
	 *
	 * Creates a new instance of the class if it does not exists.
	 *
	 * @return   Helper_Model
	 *
	 * @since 2.0.0
	 */
	public static function instance() {

		if ( null === self::$_instance ) {
			self::$_instance = new self;
		}

		return self::$_instance;
	}


	/**
	 * Magic function for cloning.
	 *
	 * Disallow cloning as this is a singleton class.
	 *
	 * @since 2.0.0
	 */
	protected function __clone() {
	}


	/**
	 * Magic method for setting upt the class.
	 *
	 * Disallow external instances.
	 *
	 * @since 2.0.0
	 */
	protected function __construct() {
	}


	/**
	 * Fetches the current post ID.
	 *
	 * @since 2.0.0
	 *
	 * @return int
	 */
	public function get_current_post_id(): int {

		/**
		 * @var \WP_Query $wp_the_query ;
		 */
		global $wp_the_query;

		if ( ! isset( $wp_the_query ) ) {
			return 0;
		}

		if ( ! is_a( $wp_the_query, '\WP_Query' ) ) {
			return 0;
		}

		if ( ! $wp_the_query->is_singular ) {
			return 0;
		}

		if ( isset( $wp_the_query->queried_object_id ) && ! empty( $wp_the_query->queried_object_id ) ) {
			return intval( $wp_the_query->queried_object_id );
		}

		if ( isset( $wp_the_query->post ) && $wp_the_query->post instanceof \WP_Post ) {
			return intval( $wp_the_query->post->ID );
		}

		if ( isset( $wp_the_query->posts ) && is_array( $wp_the_query->posts ) && 1 === count( $wp_the_query->posts ) ) {
			$post = array_values( $wp_the_query->posts )[0];
			if ( $post instanceof \WP_Post ) {
				return intval( $post->ID );
			}
		}

		return 0;
	}


	/**
	 * Returns meta information for the thumbnail.
	 *
	 * @param string $info
	 * @param int    $post_id
	 *
	 * @since 2.0.0
	 *
	 * @return mixed
	 */
	public function get_thumbnail_meta( string $info = 'url', int $post_id ) {

		return $this->get_media_meta( $info, (int) get_post_thumbnail_id( $post_id ) );
	}


	/**
	 * Returns meta information for the media item.
	 *
	 * @param string $info
	 * @param int    $media_id
	 *
	 * @since 2.0.0
	 *
	 * @return mixed
	 */
	public function get_media_meta( string $info = 'url', int $media_id ) {

		# fetch from cache
		if ( isset( $this->media_meta[ $media_id ] ) ) {
			$media = $this->media_meta[ $media_id ];
		} else {
			$media                         = wp_get_attachment_image_src( $media_id, 'full' );
			$this->media_meta[ $media_id ] = $media;
		}

		if ( ! is_array( $media ) ) {
			return null;
		}

		list( $url, $width, $height ) = $media;

		if ( ! isset( ${$info} ) ) {
			return null;
		}

		return ${$info};
	}


	/**
	 * Returns author meta if a post ID is given.
	 *
	 * @param string $meta
	 * @param int    $post_id
	 *
	 * @since 2.0.0
	 *
	 * @return mixed
	 */
	public function get_author_meta_by_post_id( string $meta, int $post_id ) {

		$author_id = $this->get_author_id( $post_id );

		return get_the_author_meta( $meta, $author_id );
	}


	/**
	 * Returns the author ID when a post_id is given.
	 *
	 * @param int $post_id
	 *
	 * @since 2.0.0
	 *
	 * @return int The author user ID.
	 */
	public function get_author_id( int $post_id ): int {

		$post = get_post( $post_id );

		if ( ! is_a( $post, '\WP_Post' ) ) {
			return 0;
		}

		return $post->post_author;
	}


	/**
	 * Fetches the current post type on an admin screen (if any).
	 *
	 * @since 2.0.0
	 *
	 * @return string Post Type or empty string.
	 */
	public function get_current_admin_post_type(): string {

		$screen = get_current_screen();

		if ( is_a( $screen, '\WP_Screen' ) ) {
			if ( isset( $screen->post_type ) ) {
				return (string) $screen->post_type;
			}
		}

		$post_id   = (int) filter_input( INPUT_GET, 'post', FILTER_SANITIZE_NUMBER_INT );
		$post_type = get_post_type( $post_id );

		if ( false !== $post_type ) {
			return (string) $post_type;
		}

		return (string) filter_input( INPUT_GET, 'post_type', FILTER_SANITIZE_STRING );
	}


	/**
	 * Returns the slug when a basename is given.
	 *
	 * @since 2.0.0
	 *
	 * @param string $basename
	 *
	 * @return string
	 */
	public function get_slug_from_basename( $basename ) {

		return str_replace( array( '/', '.php' ), '', strrchr( $basename, '/' ) );
	}


	/**
	 * Searches the database for a snippet ID.
	 *
	 * @since 2.0.0
	 *
	 * @param string $snippet_uid
	 *
	 * @return int
	 */
	public function get_post_id_by_snippet_uid( $snippet_uid ) {

		global $wpdb;

		$q     = $wpdb->esc_like( $snippet_uid );
		$regex = sprintf( 'a:[0-9]+:{s:%d:"%s"', strlen( $q ), $q );

		$sql = "SELECT post_ID FROM {$wpdb->postmeta} WHERE meta_key = '_wpb_rs_schema' AND meta_value REGEXP '{$regex }' LIMIT 1";

		$post_id = $wpdb->get_var( $sql );

		return absint( $post_id );

	}


	/**
	 * Removes schema.org from an URL.
	 *
	 * @param string $url
	 *
	 * @since 2.0.0
	 *
	 * @return string
	 */
	public function remove_schema_url( $url ) {

		return str_replace( array(
			'http://schema.org/',
			'https://schema.org/',
		), '', $url );
	}


	/**
	 * Transforms a string to a bool.
	 *
	 * @since 2.0.0
	 *
	 * @param mixed $v
	 *
	 * @return bool
	 */
	public function string_to_bool( $v ) {

		return filter_var( $v, FILTER_CALLBACK, array(
			'options' => function ( $v ) {

				if ( 'y' === strtolower( $v ) ) {
					return true;
				}

				return boolval( filter_var( $v, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE ) );
			},
		) );
	}


	/**
	 * Sanitizes single scalar elements in an array.
	 *
	 * @since 2.0.0
	 *
	 * @param $arr
	 *
	 * @return array
	 */
	public function sanitize_text_in_array( $arr ) {

		if ( ! is_array( $arr ) ) {
			return array();
		}

		foreach ( $arr as $k => $v ) {
			if ( ! is_scalar( $v ) ) {
				unset( $arr[ $k ] );
				continue;
			}

			$arr[ sanitize_text_field( $k ) ] = sanitize_text_field( $v );
		}

		return $arr;
	}


	/**
	 * Returns the users first name (if any).
	 *
	 * @since 2.0.0
	 *
	 * @return string
	 */
	public function get_current_user_firstname() {

		$user = wp_get_current_user();

		$first_name = get_user_meta( $user->ID, 'first_name', true );

		if ( ! empty( $first_name ) ) {
			return $first_name;
		}

		return $user->display_name;
	}


	/**
	 * Checks if Yoast SEO is active.
	 *
	 * @since 2.2.0
	 *
	 * @return string|bool 'premium' if Yoast SEO premium is active. Otherwise true or false.
	 */
	public function is_yoast_seo_active() {

		if ( defined( 'WPSEO_PREMIUM_PLUGIN_FILE' ) ) {
			return 'premium';
		}
		if ( defined( 'WPSEO_FILE' ) ) {
			return true;
		}

		return false;
	}


	/**
	 * Sanitizes a HTML ID.
	 *
	 * @since 2.2.0
	 *
	 * @param string $val
	 *
	 * @return string
	 */
	public function sanitize_html_id( $val ): string {

		return strtolower( sanitize_title( sanitize_text_field( $val ) ) );
	}


	/**
	 * Magic function.
	 *
	 * @return bool
	 *
	 * @since 2.3.0
	 */
	public function magic() {

		if ( true !== boolval( get_option( base64_decode( 'd3BiX3JzL3ZlcmlmaWVk' ), false ) ) ) {
			return false;
		}

		if ( true !== boolval( get_option( 'd3BiX3JzL3ZlcmlmaWVk', false ) ) ) {
			return false;
		}

		return true;
	}


	/**
	 * Searches for the primary category ID. If Yoast SEO did not set one, it will return the first one in the list.
	 *
	 * @param int $post_id
	 *
	 * @since 2.6.0
	 * @since 2.7.0 moved from YoastSEO_Model class
	 *
	 * @return int
	 */
	public function get_primary_category( $post_id ) {

		if ( $this->is_yoast_seo_active() ) {
			$cat_id = absint( get_post_meta( $post_id, '_yoast_wpseo_primary_category', true ) );

			if ( $cat_id > 0 ) {
				return $cat_id;
			}
		}

		return $this->get_primary_term( 'category', $post_id );
	}


	/**
	 * Search or the first term in a set of terms and return it.
	 *
	 * @since 2.8.0
	 *
	 * @param string $taxonomy
	 * @param int    $post_id
	 *
	 * @return int
	 */
	public function get_primary_term( $taxonomy, $post_id ) {
		/**
		 * @var \WP_Term[] $terms
		 */
		$terms = get_terms( [
			'taxonomy' => $taxonomy
		] );

		if ( ! isset( $terms[0] ) ) {
			return 0;
		}

		if ( ! $terms[0] instanceof \WP_Term ) {
			return 0;
		}

		return $terms[0]->term_id;
	}


	/**
	 * Translate a field type and returns the label.
	 *
	 * @param string $field_type
	 *
	 * @since 2.7.0
	 *
	 * @return string
	 */
	public function get_field_type_label( $field_type ) {
		if ( empty( $field_type ) ) {
			return __( 'not selected', 'rich-snippets-schema' );
		}

		$internal_values = Fields_Model::get_internal_values();

		foreach ( $internal_values as $type => $fields ) {
			foreach ( $fields as $field ) {
				if ( ! isset( $field['id'] ) ) {
					continue;
				}
				if ( ! isset( $field['label'] ) ) {
					continue;
				}
				if ( $field_type === $field['id'] ) {
					return $field['label'];
				}
			}
		}

		$reference_values = Fields_Model::get_reference_values();

		if ( isset( $reference_values[ $field_type ] ) ) {
			return $reference_values[ $field_type ];
		}

		return $field_type;
	}


	/**
	 * Returns the URL host part from the current WordPress site.
	 *
	 * @since 2.7.0
	 *
	 * @return string
	 */
	public function get_site_url_host() {
		return parse_url( site_url(), PHP_URL_HOST );
	}


	/**
	 * Adds campaign parameters to an URL.
	 *
	 * @param $url
	 *
	 * @since 2.7.0
	 *
	 * @return string
	 */
	public function get_campaignify( $url, $campaign ) {
		return add_query_arg( [
			'pk_campaign' => $campaign,
			'pk_source'   => $this->get_site_url_host()
		], $url );
	}


	/**
	 * Generates a short hash out of a string.
	 *
	 * @since 2.8.0
	 *
	 * @param $sth
	 *
	 * @return string
	 */
	public function get_short_hash( $sth ): string {


		if ( ! function_exists( '\hash_algos' ) ) {
			return $sth;
		}

		if ( ! function_exists( '\hash_hmac' ) ) {
			return $sth;
		}

		$possible_hash_algos = hash_algos();

		# Search for an algorithm that produces short output
		if ( version_compare( PHP_VERSION, '7.2.0', '<' ) ) {
			$hash_algos_to_use = array(
				'crc32',
				'adler32',
				'crc32b',
				'fnv132',
				'fnv1a32',
				'fnv164',
				'joaat',
				'fnv164',
				'fnv1a64',
				'md5',
			);
		} else {
			$hash_algos_to_use = array(
				'haval128,4',
				'md4',
				'tiger128,4',
				'tiger128,3',
				'haval128,3',
				'md2',
				'ripemd128',
				'haval128,5',
				'haval160,5',
				'sha1',
				'tiger160,3',
				'tiger160,4',
				'ripemd160',
				'haval160,3',
				'haval192,4',
				'tiger192,3',
				'haval192,5',
				'tiger192,4',
				'tiger192,3',
				'sha224',
				'haval224,5',
				'haval224,5',
				'haval224,3',
				'sha512/224',
				'sha3-224',
				'haval224,4',
				'haval254,4',
				'haval256,3',
				'snefru256',
				'gost-crypto',
				'gost',
				'snefru',
				'ripemd256',
				'sha3-256',
				'sha512/256',
				'sha256',
				'haval256,5',
			);
		}

		$algo = 'sha256';

		# search for the first algo available
		foreach ( $hash_algos_to_use as $hash_algo_to_use ) {
			if ( false !== $k = array_search( $hash_algo_to_use, $possible_hash_algos ) ) {
				$algo = $hash_algo_to_use;
				break;
			}
		}

		return (string) hash_hmac( $algo, $sth, wp_salt( 'wpb_rs' ) );
	}


	/**
	 * Filters array items by hierarchy.
	 *
	 * The $items array is filter so that only the items that are the parents of a certain sub-item
	 * are still in the returned array.
	 *
	 * @since 2.8.0
	 *
	 * @param object[] $items
	 * @param int      $id
	 * @param string   $object_param_name
	 * @param string   $object_id_param_name
	 *
	 * @return object[] Filtered item list.
	 */
	public function filter_item_hierarchy( $items, $id, $object_parent_param_name, $object_id_param_name, $new_items = [] ) {

		foreach ( $items as $key => $item ) {
			if ( ! is_object( $item ) ) {
				continue;
			}

			if ( ! isset( $item->{$object_id_param_name} ) ) {
				continue;
			}


			if ( $id == $item->{$object_id_param_name} ) {
				$new_items[] = $item;

				if ( ! isset( $item->{$object_parent_param_name} ) ) {
					continue;
				}

				if ( empty( $item->{$object_parent_param_name} ) ) {
					continue;
				}

				$new_items = $this->filter_item_hierarchy(
					$items,
					$item->{$object_parent_param_name},
					$object_parent_param_name,
					$object_id_param_name,
					$new_items
				);
			}

		}

		return $new_items;

	}


	/**
	 * Integrates an array into another array on a certain place represented by $no.
	 *
	 * @since 2.8.0
	 *
	 * @param array $before
	 * @param int   $no
	 * @param array $insert_arr
	 *
	 * @return array
	 */
	public function integrate_into_array( $before, $no, $insert_arr ) {
		$after2 = array_slice( $before, $no, null, true );
		$after1 = array_diff_key( $before, $after2 );

		return array_merge( $after1, $insert_arr, $after2 );
	}

}
