<?php

namespace wpbuddy\rich_snippets;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly


/**
 * Class YoastSEO_Model.
 *
 * Recognizes Yoast SEO plugin and provides new fields.
 *
 * @package wpbuddy\rich_snippets
 *
 * @since   2.2.0
 */
final class YoastSEO_Model {

	/**
	 * @param $values
	 *
	 * @return mixed
	 */
	public static function internal_subselect( $values ) {

		if ( false === Helper_Model::instance()->is_yoast_seo_active() ) {
			return $values;
		}

		$values['http://schema.org/Text'][] = array(
			'id'     => 'yoast_seo_title',
			'label'  => esc_html_x( 'SEO title (Yoast)', 'subselect field', 'rich-snippets-schema' ),
			'method' => array( 'wpbuddy\rich_snippets\YoastSEO_Model', 'yoast_seo_title' ),
		);

		$values['http://schema.org/Text'][] = array(
			'id'     => 'yoast_seo_meta_desc',
			'label'  => esc_html_x( 'SEO meta description (Yoast)', 'subselect field', 'rich-snippets-schema' ),
			'method' => array( 'wpbuddy\rich_snippets\YoastSEO_Model', 'yoast_seo_meta_desc' ),
		);

		$values['http://schema.org/Text'][] = array(
			'id'     => 'yoast_seo_primary_category',
			'label'  => esc_html_x( 'Primary category (Yoast)', 'subselect field', 'rich-snippets-schema' ),
			'method' => array( 'wpbuddy\rich_snippets\YoastSEO_Model', 'yoast_seo_primary_category' ),
		);

		$values['http://schema.org/URL'][] = $values['http://schema.org/Thing'][] = array(
			'id'     => 'yoast_seo_primary_category_url',
			'label'  => esc_html_x( 'Primary category URL (Yoast)', 'subselect field', 'rich-snippets-schema' ),
			'method' => array( 'wpbuddy\rich_snippets\YoastSEO_Model', 'yoast_seo_primary_category_url' ),
		);

		return $values;
	}


	/**
	 * Returns the value of the SEO title.
	 *
	 * @param                                     $val
	 * @param \wpbuddy\rich_snippets\Rich_Snippet $rich_snippet
	 * @param array                               $meta_info
	 *
	 * @since 2.2.0
	 *
	 * @return string
	 */
	public static function yoast_seo_title( $val, Rich_Snippet $rich_snippet, array $meta_info ) {

		if ( ! class_exists( '\WPSEO_Frontend' ) ) {
			return '';
		}

		return \WPSEO_Frontend::get_instance()->title( '' );
	}


	/**
	 * Returns the value of a SEO meta description.
	 *
	 * @param                                     $val
	 * @param \wpbuddy\rich_snippets\Rich_Snippet $rich_snippet
	 * @param array                               $meta_info
	 *
	 * @since 2.2.0
	 *
	 * @return string
	 */
	public static function yoast_seo_meta_desc( $val, Rich_Snippet $rich_snippet, array $meta_info ) {

		if ( ! class_exists( '\WPSEO_Frontend' ) ) {
			return '';
		}

		return \WPSEO_Frontend::get_instance()->metadesc( false );
	}


	/**
	 * Returns the primary category name.
	 *
	 * @param                                     $val
	 * @param \wpbuddy\rich_snippets\Rich_Snippet $rich_snippet
	 * @param array                               $meta_info
	 *
	 * @since 2.2.0
	 *
	 * @return string
	 */
	public static function yoast_seo_primary_category( $val, Rich_Snippet $rich_snippet, array $meta_info ) {

		$primary_category_id = Helper_Model::instance()->get_primary_category($meta_info['current_post_id']);

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
	 * Returns the primary category URL.
	 *
	 * @param                                     $val
	 * @param \wpbuddy\rich_snippets\Rich_Snippet $rich_snippet
	 * @param array                               $meta_info
	 *
	 * @since 2.7.0
	 *
	 * @return string
	 */
	public static function yoast_seo_primary_category_url( $val, Rich_Snippet $rich_snippet, array $meta_info ) {

		$primary_category_id = Helper_Model::instance()->get_primary_category( $meta_info['current_post_id'] );

		if ( empty( $primary_category_id ) ) {
			return '';
		}

		$category_url = get_term_link( $primary_category_id );

		if ( is_wp_error( $category_url ) ) {
			return '';
		}

		return $category_url;
	}

}
