<?php

namespace wpbuddy\rich_snippets;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly


/**
 * Class WooCommerce_Model.
 *
 * Recognizes the WooCommerce plugin and provides new fields.
 *
 * @package wpbuddy\rich_snippets
 *
 * @since   2.2.0
 */
final class WooCommerce_Model {

	/**
	 * @param $values
	 *
	 * @return mixed
	 */
	public static function internal_subselect( $values ) {

		if ( ! function_exists( 'WC' ) ) {
			return $values;
		}

		$values['http://schema.org/AggregateRating'][] = array(
			'id'     => 'woocommerce_rating',
			'label'  => esc_html_x( 'Product Rating (WooCommerce)', 'subselect field', 'rich-snippets-schema' ),
			'method' => array( 'wpbuddy\rich_snippets\WooCommerce_Model', 'rating' ),
		);

		$values['http://schema.org/Rating'][] = array(
			'id'     => 'woocommerce_rating',
			'label'  => esc_html_x( 'Product Rating (WooCommerce)', 'subselect field', 'rich-snippets-schema' ),
			'method' => array( 'wpbuddy\rich_snippets\WooCommerce_Model', 'rating' ),
		);

		$values['http://schema.org/AggregateRating'][] = array(
			'id'     => 'woocommerce_review_rating',
			'label'  => esc_html_x( 'Product Review Rating (WooCommerce)', 'subselect field', 'rich-snippets-schema' ),
			'method' => array( 'wpbuddy\rich_snippets\WooCommerce_Model', 'review_rating' ),
		);

		$values['http://schema.org/Rating'][] = array(
			'id'     => 'woocommerce_review_rating',
			'label'  => esc_html_x( 'Product Review Rating (WooCommerce)', 'subselect field', 'rich-snippets-schema' ),
			'method' => array( 'wpbuddy\rich_snippets\WooCommerce_Model', 'review_rating' ),
		);

		$values['http://schema.org/Text'][] = array(
			'id'     => 'woocommerce_sku',
			'label'  => esc_html_x( 'Stock Keeping Unit (WooCommerce)', 'subselect field', 'rich-snippets-schema' ),
			'method' => array( 'wpbuddy\rich_snippets\WooCommerce_Model', 'sku' ),
		);

		$values['http://schema.org/Offer'][] = array(
			'id'     => 'woocommerce_offers',
			'label'  => esc_html_x( 'Offers (WooCommerce)', 'subselect field', 'rich-snippets-schema' ),
			'method' => array( 'wpbuddy\rich_snippets\WooCommerce_Model', 'offers' ),
		);

		$values['http://schema.org/QuantitativeValue'][] = array(
			'id'     => 'woocommerce_height',
			'label'  => esc_html_x( 'Product Height (WooCommerce)', 'subselect field', 'rich-snippets-schema' ),
			'method' => array( 'wpbuddy\rich_snippets\WooCommerce_Model', 'height' ),
		);

		$values['http://schema.org/QuantitativeValue'][] = array(
			'id'     => 'woocommerce_width',
			'label'  => esc_html_x( 'Product Width (WooCommerce)', 'subselect field', 'rich-snippets-schema' ),
			'method' => array( 'wpbuddy\rich_snippets\WooCommerce_Model', 'width' ),
		);

		$values['http://schema.org/QuantitativeValue'][] = array(
			'id'     => 'woocommerce_weight',
			'label'  => esc_html_x( 'Product Weight (WooCommerce)', 'subselect field', 'rich-snippets-schema' ),
			'method' => array( 'wpbuddy\rich_snippets\WooCommerce_Model', 'weight' ),
		);

		$values['http://schema.org/Text'][] = $values['http://schema.org/Thing'][] = array(
			'id'     => 'textfield_woocommerce_product_attribute',
			'label'  => esc_html_x( 'Product attribute (WooCommerce)', 'subselect field', 'rich-snippets-schema' ),
			'method' => array( 'wpbuddy\rich_snippets\WooCommerce_Model', 'attribute' ),
		);

		$values['http://schema.org/QuantitativeValue'][] = array(
			'id'     => 'textfield_woocommerce_product_attribute',
			'label'  => esc_html_x( 'Product attribute (WooCommerce)', 'subselect field', 'rich-snippets-schema' ),
			'method' => array( 'wpbuddy\rich_snippets\WooCommerce_Model', 'attribute' ),
		);

		$values['http://schema.org/Review'][] = array(
			'id'     => 'woocommerce_reviews',
			'label'  => esc_html_x( 'Product reviews (WooCommerce)', 'subselect field', 'rich-snippets-schema' ),
			'method' => array( 'wpbuddy\rich_snippets\WooCommerce_Model', 'reviews' ),
		);

		return $values;
	}


	/**
	 * Returns the value of the current rating.
	 *
	 * @param                                     $val
	 * @param \wpbuddy\rich_snippets\Rich_Snippet $rich_snippet
	 * @param array                               $meta_info
	 *
	 * @since 2.2.0
	 *
	 * @return string
	 */
	public static function rating( $val, Rich_Snippet $rich_snippet, array $meta_info ) {

		$product = wc_get_product( $meta_info['current_post_id'] );

		if ( ! $product instanceof \WC_Product ) {
			$rating_value = 0;
			$rating_count = 0;
		} else {
			$rating_value = floatval( $product->get_average_rating( 'raw' ) );
			$rating_count = floatval( $product->get_rating_count( 'raw' ) );
		}

		# force SNIP to not include aggregateRating at all because there are no ratings
		# This is because rating_count cannot be zero for Googles Structured Data Tester
		if ( $rating_count <= 0 ) {
			return '';
		}

		$rating_snippet       = new Rich_Snippet();
		$rating_snippet->type = 'AggregateRating';


		$rating_snippet->set_props( array(
			array(
				'name'  => 'ratingCount',
				'value' => $rating_count,
			),
			array(
				'name'  => 'bestRating',
				'value' => 5,
			),
			array(
				'name'  => 'ratingValue',
				'value' => $rating_value,
			),
			array(
				'name'  => 'worstRating',
				'value' => $rating_count <= 0 ? 0 : 1, # worstRating must be 0 if ratingCount is 0
			),
		) );

		$rating_snippet->prepare_for_output();

		return $rating_snippet;
	}


	/**
	 * Returns the value of the current review rating.
	 *
	 * @param                                     $val
	 * @param \wpbuddy\rich_snippets\Rich_Snippet $rich_snippet
	 * @param array                               $meta_info
	 *
	 * @since 2.6.0
	 *
	 * @return string
	 */
	public static function review_rating( $val, Rich_Snippet $rich_snippet, array $meta_info ) {
		$product = wc_get_product( $meta_info['current_post_id'] );

		if ( ! $product instanceof \WC_Product ) {
			$rating_value = 0;
			$review_count = 0;
		} else {
			$rating_value = floatval( $product->get_average_rating( 'raw' ) );
			$review_count = floatval( $product->get_review_count( 'raw' ) );
		}

		# force SNIP to not include aggregateRating at all because there are no ratings
		# This is because review_count cannot be zero for Googles Structured Data Tester
		if ( $review_count <= 0 ) {
			return '';
		}

		$rating_snippet       = new Rich_Snippet();
		$rating_snippet->type = 'AggregateRating';

		$rating_snippet->set_props( array(
			array(
				'name'  => 'reviewCount',
				'value' => $review_count,
			),
			array(
				'name'  => 'bestRating',
				'value' => 5,
			),
			array(
				'name'  => 'ratingValue',
				'value' => $rating_value,
			),
			array(
				'name'  => 'worstRating',
				'value' => $review_count <= 0 ? 0 : 1, # worstRating must be 0 if ratingCount is 0
			),
		) );

		$rating_snippet->prepare_for_output();

		return $rating_snippet;
	}


	/**
	 * Returns the value of the current SKU.
	 *
	 * @param                                     $val
	 * @param \wpbuddy\rich_snippets\Rich_Snippet $rich_snippet
	 * @param array                               $meta_info
	 *
	 * @since 2.2.0
	 *
	 * @return string
	 */
	public static function sku( $val, Rich_Snippet $rich_snippet, array $meta_info ) {

		$product = wc_get_product( $meta_info['current_post_id'] );

		if ( ! $product instanceof \WC_Product ) {
			return '';
		}

		return (string) $product->get_sku( 'raw' );
	}


	/**
	 * Returns the offer for a WooCommerce product.
	 *
	 * @since 2.2.0
	 *
	 * @param int $post_id
	 *
	 * @return \stdClass
	 */
	private static function offer( $post_id ) {

		/**
		 * @var \WC_Product_Variation
		 */
		$product = wc_get_product( $post_id );

		if ( ! $product instanceof \WC_Product ) {
			return new \stdClass();
		}

		$helper = Helper_Model::instance();

		$obj                = new \stdClass();
		$obj->{'@context'}  = 'http://schema.org';
		$obj->{'@type'}     = 'Offer';
		$obj->availability  = 'https://schema.org/' . ( $product->is_in_stock() ? 'InStock' : 'OutOfStock' );
		$obj->priceCurrency = get_woocommerce_currency();
		$obj->price         = wc_format_decimal( $product->get_price(), wc_get_price_decimals() );
		$obj->url           = $product->get_permalink();

		$sale_date = $product->get_date_on_sale_to( 'raw' );

		if ( ! $sale_date instanceof \DateTime ) {
			# If there is no sales date create a fake date to avoid Googles warnings
			# @see https://rich-snippets.io/offers-pricevaliduntil-recommended/
			$obj->priceValidUntil = (string) date_i18n( 'c', strtotime( 'NOW + 1 month' ) );
		} else {
			$obj->priceValidUntil = $sale_date->date( 'c' );
		}

		$item_offered               = new \stdClass();
		$item_offered->{'@context'} = 'http://schema.org';
		$item_offered->{'@type'}    = 'IndividualProduct';
		$item_offered->url          = $product->get_permalink();
		$item_offered->sku          = $product->get_sku();

		if ( wc_product_dimensions_enabled() ) {
			$item_offered->width  = self::get_product_quantitive_snippet( $post_id, 'width' );
			$item_offered->height = self::get_product_quantitive_snippet( $post_id, 'height' );
		}

		if ( wc_product_weight_enabled() ) {
			$item_offered_weight               = new \stdClass();
			$item_offered_weight->{'@context'} = 'http://schema.org';
			$item_offered_weight->{'@type'}    = 'QuantitativeValue';
			$item_offered_weight->value        = $product->get_weight();
			$item_offered_weight->unitCode     = get_option( 'woocommerce_weight_unit' );
			$item_offered->weight              = $item_offered_weight;
		}

		$item_offered->name        = $product->get_title();
		$item_offered->description = $product->get_description();

		$image               = new \stdClass();
		$image->{'@context'} = 'http://schema.org';
		$image->{'@type'}    = 'ImageObject';
		$image->url          = $helper->get_media_meta( 'url', (int) $product->get_image_id() );
		$image->width        = $helper->get_media_meta( 'width', (int) $product->get_image_id() );
		$image->height       = $helper->get_media_meta( 'height', (int) $product->get_image_id() );
		$item_offered->image = $image;

		$obj->itemOffered = $item_offered;

		return $obj;
	}

	/**
	 * Returns a snippet of all offers.
	 *
	 * @param                                     $val
	 * @param \wpbuddy\rich_snippets\Rich_Snippet $rich_snippet
	 * @param array                               $meta_info
	 *
	 * @since 2.2.0
	 *
	 * @return array
	 */
	public static function offers( $val, Rich_Snippet $rich_snippet, array $meta_info ) {

		$offers = [];

		$product = wc_get_product( $meta_info['current_post_id'] );

		if ( $product instanceof \WC_Product_Variable ) {
			foreach ( $product->get_visible_children() as $child_product_id ) {
				$offers[] = self::offer( $child_product_id );
			}

		} elseif ( $product instanceof \WC_Product ) {
			$offers[] = self::offer( $meta_info['current_post_id'] );
		}

		return $offers;
	}


	/**
	 * Returns the height of a product.
	 *
	 * @param                                     $val
	 * @param \wpbuddy\rich_snippets\Rich_Snippet $rich_snippet
	 * @param array                               $meta_info
	 *
	 * @since 2.2.0
	 *
	 * @return \stdClass
	 */
	public static function height( $val, Rich_Snippet $rich_snippet, array $meta_info ) {

		return self::get_product_quantitive_snippet( $meta_info['current_post_id'], 'height' );
	}


	/**
	 * Returns the width of a product.
	 *
	 * @param                                     $val
	 * @param \wpbuddy\rich_snippets\Rich_Snippet $rich_snippet
	 * @param array                               $meta_info
	 *
	 * @since 2.2.0
	 *
	 * @return \stdClass
	 */
	public static function width( $val, Rich_Snippet $rich_snippet, array $meta_info ) {

		return self::get_product_quantitive_snippet( $meta_info['current_post_id'], 'width' );
	}


	/**
	 * Get WooCommerce product dimension as a snippet.
	 *
	 * @param int    $product_id
	 * @param string $prop width|height|weight
	 *
	 * since 2.2.0
	 *
	 * @return \stdClass
	 */
	private static function get_product_quantitive_snippet( $product_id, $prop ) {

		$product = wc_get_product( $product_id );

		$item = new \stdClass();

		if ( $product instanceof \WC_Product ) {
			return $item;
		}

		$item->{'@context'} = 'http://schema.org';
		$item->{'@type'}    = 'QuantitativeValue';
		$item->value        = method_exists( $product, $prop ) ? $product->{$prop}() : '';
		$item->unitCode     = 'weight' === $prop ? get_option( 'woocommerce_weight_unit' ) : get_option( 'woocommerce_dimension_unit' );

		return $item;
	}


	/**
	 * Returns the eight of a product.
	 *
	 * @param                                     $val
	 * @param \wpbuddy\rich_snippets\Rich_Snippet $rich_snippet
	 * @param array                               $meta_info
	 *
	 * @since 2.2.0
	 *
	 * @return \stdClass
	 */
	public static function weight( $val, Rich_Snippet $rich_snippet, array $meta_info ) {

		return self::get_product_quantitive_snippet( $meta_info['current_post_id'], 'weight' );
	}


	/**
	 * Reads a product attribute from WooCommerce (serialized data).
	 *
	 * @param              $val
	 * @param Rich_Snippet $rich_snippet
	 * @param array        $meta_info
	 *
	 * @since 2.5.0
	 *
	 * @return string
	 */
	public static function attribute( $val, Rich_Snippet $rich_snippet, array $meta_info ) {
		if ( ! function_exists( '\wc_get_product' ) ) {
			return '';
		}

		if ( ! is_scalar( $val ) ) {
			return '';
		}

		if ( empty( $val ) ) {
			return '';
		}

		$product = \wc_get_product( $meta_info['current_post_id'] );

		if ( ! $product instanceof \WC_Product ) {
			return '';
		}

		return $product->get_attribute( $val );
	}


	/**
	 * Outputs product reviews from WooCommerce.
	 *
	 * @param              $val
	 * @param Rich_Snippet $rich_snippet
	 * @param array        $meta_info
	 *
	 * @since 2.7.0
	 *
	 * @return \stdClass[]
	 */
	public static function reviews( $val, Rich_Snippet $rich_snippet, array $meta_info ): array {

		if ( ! function_exists( '\wc_get_product' ) ) {
			return [];
		}

		/**
		 * @var \WP_Comment[] $comments
		 */

		$args = [
			'post_id'            => $meta_info['current_post_id'],
			'include_unapproved' => false,
			'number'             => 5,
			'type'               => 'review',
			'meta_query'         => array(
				'city_clause' => array(
					'key'     => 'rating',
					'compare' => 'EXISTS',
				),
			),
		];


		$comments = get_comments( apply_filters( 'wpbuddy/rich_snippets/woocommerce/reviews/args', $args ) );

		if ( ! is_array( $comments ) || count( $comments ) <= 0 ) {
			return [];
		}

		$reviews = [];

		foreach ( $comments as $comment ) {
			$review               = new \stdClass();
			$review->{'@context'} = 'http://schema.org';
			$review->{'@type'}    = 'Review';

			$review->author               = new \stdClass();
			$review->author->{'@context'} = 'http://schema.org';
			$review->author->{'@type'}    = 'Person';
			$review->author->name         = $comment->comment_author;

			$review->reviewRating               = new \stdClass();
			$review->reviewRating->{'@context'} = 'http://schema.org';
			$review->reviewRating->{'@type'}    = 'Rating';
			$review->reviewRating->bestRating   = 5;
			$review->reviewRating->worstRating  = 1;
			$review->reviewRating->ratingValue  = max( 1, absint( get_comment_meta( $comment->comment_ID, 'rating', true ) ) );

			$review->reviewBody    = strip_tags( $comment->comment_content );
			$review->datePublished = date_i18n( 'c', strtotime( $comment->comment_date ) );

			$reviews[] = $review;
		}

		return $reviews;
	}
}
