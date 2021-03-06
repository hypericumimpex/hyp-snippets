<?php

namespace wpbuddy\rich_snippets;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly


/**
 * Class Predefined.
 *
 * Functions to install/update predefined snippets.
 *
 * @package wpbuddy\rich_snippets
 *
 * @since   2.0.0
 */
final class Predefined_Model {

	/**
	 * @since 2.0.0
	 *
	 * @return array
	 */
	public static function article() {

		$position = unserialize( 'O:38:"wpbuddy\rich_snippets\Position_Ruleset":1:{s:50:" wpbuddy\rich_snippets\Position_Ruleset rulegroups";a:2:{i:0;O:41:"wpbuddy\rich_snippets\Position_Rule_Group":1:{s:48:" wpbuddy\rich_snippets\Position_Rule_Group rules";a:1:{i:0;O:35:"wpbuddy\rich_snippets\Position_Rule":3:{s:5:"param";s:9:"post_type";s:8:"operator";s:2:"==";s:5:"value";s:4:"post";}}}i:1;O:41:"wpbuddy\rich_snippets\Position_Rule_Group":1:{s:48:" wpbuddy\rich_snippets\Position_Rule_Group rules";a:1:{i:0;O:35:"wpbuddy\rich_snippets\Position_Rule":3:{s:5:"param";s:9:"post_type";s:8:"operator";s:2:"==";s:5:"value";s:4:"page";}}}}}' );

		$snippet = unserialize( 'a:1:{s:19:"snip-global-article";O:34:"wpbuddy\rich_snippets\Rich_Snippet":13:{s:2:"id";s:19:"snip-global-article";s:7:"context";s:17:"http://schema.org";s:4:"type";s:7:"Article";s:45:" wpbuddy\rich_snippets\Rich_Snippet _is_ready";b:0;s:24:"image-prop-599be31c45dba";a:4:{i:0;s:29:"http://schema.org/ImageObject";i:1;O:34:"wpbuddy\rich_snippets\Rich_Snippet":7:{s:2:"id";s:18:"snip-599be1455dd51";s:7:"context";s:17:"http://schema.org";s:4:"type";s:11:"ImageObject";s:45:" wpbuddy\rich_snippets\Rich_Snippet _is_ready";b:0;s:22:"url-prop-599be31c45c95";a:4:{i:0;s:26:"current_post_thumbnail_url";i:1;N;s:11:"overridable";b:0;s:20:"overridable_multiple";b:0;}s:25:"height-prop-599be31c45cc2";a:4:{i:0;s:29:"current_post_thumbnail_height";i:1;N;s:11:"overridable";b:0;s:20:"overridable_multiple";b:0;}s:24:"width-prop-599be31c45ce8";a:4:{i:0;s:28:"current_post_thumbnail_width";i:1;N;s:11:"overridable";b:0;s:20:"overridable_multiple";b:0;}}s:11:"overridable";b:0;s:20:"overridable_multiple";b:0;}s:28:"publisher-prop-599be31c45de2";a:4:{i:0;s:39:"global_snippet_snip-global-organization";i:1;N;s:11:"overridable";b:0;s:20:"overridable_multiple";b:0;}s:31:"dateModified-prop-599be31c45e07";a:4:{i:0;s:26:"current_post_modified_date";i:1;N;s:11:"overridable";b:0;s:20:"overridable_multiple";b:0;}s:25:"author-prop-599be31c45e2d";a:4:{i:0;s:24:"http://schema.org/Person";i:1;O:34:"wpbuddy\rich_snippets\Rich_Snippet":6:{s:2:"id";s:18:"snip-599be16a975e1";s:7:"context";s:17:"http://schema.org";s:4:"type";s:6:"Person";s:45:" wpbuddy\rich_snippets\Rich_Snippet _is_ready";b:0;s:22:"url-prop-599be31c45d55";a:4:{i:0;s:23:"current_post_author_url";i:1;N;s:11:"overridable";b:0;s:20:"overridable_multiple";b:0;}s:23:"name-prop-599be31c45d7d";a:4:{i:0;s:24:"current_post_author_name";i:1;N;s:11:"overridable";b:0;s:20:"overridable_multiple";b:0;}}s:11:"overridable";b:0;s:20:"overridable_multiple";b:0;}s:35:"mainEntityOfPage-prop-599be31c45e4f";a:4:{i:0;s:16:"current_post_url";i:1;N;s:11:"overridable";b:0;s:20:"overridable_multiple";b:0;}s:30:"description-prop-599be31c45e70";a:4:{i:0;s:19:"yoast_seo_meta_desc";i:1;N;s:11:"overridable";b:0;s:20:"overridable_multiple";b:0;}s:32:"datePublished-prop-599be31c45e97";a:4:{i:0;s:17:"current_post_date";i:1;N;s:11:"overridable";b:0;s:20:"overridable_multiple";b:0;}s:27:"headline-prop-599be31c45f10";a:4:{i:0;s:18:"current_post_title";i:1;N;s:11:"overridable";b:0;s:20:"overridable_multiple";b:0;}s:30:"articleBody-prop-599be31c45f37";a:4:{i:0;s:20:"current_post_content";i:1;N;s:11:"overridable";b:0;s:20:"overridable_multiple";b:0;}}}' );

		return array(
			'position' => $position,
			'schema'   => $snippet,
		);
	}

	/**
	 * @since 2.0.0
	 *
	 * @return array
	 */
	public static function organization() {

		$position = unserialize( 'O:38:"wpbuddy\rich_snippets\Position_Ruleset":1:{s:50:" wpbuddy\rich_snippets\Position_Ruleset rulegroups";a:1:{i:0;O:41:"wpbuddy\rich_snippets\Position_Rule_Group":1:{s:48:" wpbuddy\rich_snippets\Position_Rule_Group rules";a:1:{i:0;O:35:"wpbuddy\rich_snippets\Position_Rule":3:{s:5:"param";s:9:"page_type";s:8:"operator";s:2:"==";s:5:"value";s:3:"all";}}}}}' );
		$snippet  = unserialize( 'a:1:{s:24:"snip-global-organization";O:34:"wpbuddy\rich_snippets\Rich_Snippet":9:{s:2:"id";s:24:"snip-global-organization";s:7:"context";s:17:"http://schema.org";s:4:"type";s:12:"Organization";s:45:" wpbuddy\rich_snippets\Rich_Snippet _is_ready";b:0;s:52:" wpbuddy\rich_snippets\Rich_Snippet _is_main_snippet";b:1;s:52:" wpbuddy\rich_snippets\Rich_Snippet _version_created";s:5:"2.7.0";s:22:"url-prop-599be6521135e";a:4:{i:0;s:8:"blog_url";i:1;N;s:11:"overridable";b:0;s:20:"overridable_multiple";b:0;}s:23:"name-prop-599be652113e9";a:4:{i:0;s:10:"blog_title";i:1;N;s:11:"overridable";b:0;s:20:"overridable_multiple";b:0;}s:23:"logo-prop-599be65211453";a:4:{i:0;s:29:"http://schema.org/ImageObject";i:1;O:34:"wpbuddy\rich_snippets\Rich_Snippet":8:{s:2:"id";s:18:"snip-599be6401df71";s:7:"context";s:17:"http://schema.org";s:4:"type";s:11:"ImageObject";s:45:" wpbuddy\rich_snippets\Rich_Snippet _is_ready";b:0;s:52:" wpbuddy\rich_snippets\Rich_Snippet _is_main_snippet";b:0;s:52:" wpbuddy\rich_snippets\Rich_Snippet _version_created";s:5:"2.7.0";s:22:"url-prop-599be652112f6";a:4:{i:0;s:13:"site_icon_url";i:1;N;s:11:"overridable";b:0;s:20:"overridable_multiple";b:0;}s:24:"width-prop-599be65211337";a:4:{i:0;s:15:"site_icon_width";i:1;N;s:11:"overridable";b:0;s:20:"overridable_multiple";b:0;}}s:11:"overridable";b:0;s:20:"overridable_multiple";b:0;}}}' );

		return array(
			'position' => $position,
			'schema'   => $snippet,
			'title'    => __( 'Organization', 'rich-snippets-schema' ),
			'status'   => 'publish',
		);
	}

	/**
	 * @since 2.3.1
	 *
	 * @return array
	 */
	public static function review_of_product() {

		$snippet = unserialize( 'a:1:{s:26:"snip-global-review-product";O:34:"wpbuddy\rich_snippets\Rich_Snippet":12:{s:2:"id";s:26:"snip-global-review-product";s:7:"context";s:17:"http://schema.org";s:4:"type";s:6:"Review";s:45:" wpbuddy\rich_snippets\Rich_Snippet _is_ready";b:0;s:28:"publisher-prop-5a97b109a0527";a:4:{i:0;s:39:"global_snippet_snip-global-organization";i:1;O:34:"wpbuddy\rich_snippets\Rich_Snippet":4:{s:2:"id";s:18:"snip-5a97ca6e75e69";s:7:"context";s:17:"http://schema.org";s:4:"type";s:5:"Thing";s:45:" wpbuddy\rich_snippets\Rich_Snippet _is_ready";b:0;}s:11:"overridable";b:0;s:20:"overridable_multiple";b:0;}s:31:"itemReviewed-prop-5a97b109a9106";a:4:{i:0;s:25:"http://schema.org/Product";i:1;O:34:"wpbuddy\rich_snippets\Rich_Snippet":13:{s:2:"id";s:18:"snip-5a97b11b0288c";s:7:"context";s:17:"http://schema.org";s:4:"type";s:7:"Product";s:45:" wpbuddy\rich_snippets\Rich_Snippet _is_ready";b:0;s:22:"mpn-prop-5a97b11b1a1a6";a:4:{i:0;s:9:"textfield";i:1;s:0:"";s:11:"overridable";b:1;s:20:"overridable_multiple";b:0;}s:24:"gtin8-prop-5a97b11b24fd1";a:4:{i:0;s:9:"textfield";i:1;s:0:"";s:11:"overridable";b:1;s:20:"overridable_multiple";b:0;}s:24:"image-prop-5a97b11b2ed96";a:4:{i:0;s:29:"http://schema.org/ImageObject";i:1;O:34:"wpbuddy\rich_snippets\Rich_Snippet":7:{s:2:"id";s:18:"snip-5a97c8ebb759d";s:7:"context";s:17:"http://schema.org";s:4:"type";s:11:"ImageObject";s:45:" wpbuddy\rich_snippets\Rich_Snippet _is_ready";b:0;s:25:"height-prop-5a97c8ebc0b33";a:4:{i:0;s:29:"current_post_thumbnail_height";i:1;N;s:11:"overridable";b:0;s:20:"overridable_multiple";b:0;}s:24:"width-prop-5a97c8ebcdc54";a:4:{i:0;s:28:"current_post_thumbnail_width";i:1;N;s:11:"overridable";b:0;s:20:"overridable_multiple";b:0;}s:22:"url-prop-5a97c8ebd823c";a:4:{i:0;s:26:"current_post_thumbnail_url";i:1;N;s:11:"overridable";b:0;s:20:"overridable_multiple";b:0;}}s:11:"overridable";b:0;s:20:"overridable_multiple";b:0;}s:23:"name-prop-5a97b11b38b83";a:4:{i:0;s:9:"textfield";i:1;s:0:"";s:11:"overridable";b:1;s:20:"overridable_multiple";b:0;}s:25:"offers-prop-5a97b11b535bf";a:4:{i:0;s:23:"http://schema.org/Offer";i:1;O:34:"wpbuddy\rich_snippets\Rich_Snippet":8:{s:2:"id";s:18:"snip-5a97c9103796a";s:7:"context";s:17:"http://schema.org";s:4:"type";s:5:"Offer";s:45:" wpbuddy\rich_snippets\Rich_Snippet _is_ready";b:0;s:32:"priceCurrency-prop-5a97c91050a3e";a:4:{i:0;s:9:"textfield";i:1;s:3:"EUR";s:11:"overridable";b:1;s:20:"overridable_multiple";b:0;}s:24:"price-prop-5a97c91068fd0";a:4:{i:0;s:0:"";i:1;N;s:11:"overridable";b:0;s:20:"overridable_multiple";b:0;}s:31:"availability-prop-5a97c91077fd1";a:4:{i:0;s:36:"descendant-http://schema.org/InStock";i:1;O:34:"wpbuddy\rich_snippets\Rich_Snippet":4:{s:2:"id";s:18:"snip-5a97ca6e76647";s:7:"context";s:17:"http://schema.org";s:4:"type";s:5:"Thing";s:45:" wpbuddy\rich_snippets\Rich_Snippet _is_ready";b:0;}s:11:"overridable";b:0;s:20:"overridable_multiple";b:0;}s:22:"url-prop-5a97ca2ec7f53";a:4:{i:0;s:9:"textfield";i:1;s:0:"";s:11:"overridable";b:1;s:20:"overridable_multiple";b:0;}}s:11:"overridable";b:1;s:20:"overridable_multiple";b:1;}s:25:"gtin14-prop-5a97b11b6330c";a:4:{i:0;s:9:"textfield";i:1;s:0:"";s:11:"overridable";b:1;s:20:"overridable_multiple";b:0;}s:25:"gtin13-prop-5a97b11b70b67";a:4:{i:0;s:9:"textfield";i:1;s:0:"";s:11:"overridable";b:1;s:20:"overridable_multiple";b:0;}s:30:"description-prop-5a97b11b8b265";a:4:{i:0;s:9:"textfield";i:1;s:0:"";s:11:"overridable";b:1;s:20:"overridable_multiple";b:0;}s:24:"brand-prop-5a97b11b96a34";a:4:{i:0;s:23:"http://schema.org/Brand";i:1;O:34:"wpbuddy\rich_snippets\Rich_Snippet":5:{s:2:"id";s:18:"snip-5a97c9ca704f4";s:7:"context";s:17:"http://schema.org";s:4:"type";s:5:"Brand";s:45:" wpbuddy\rich_snippets\Rich_Snippet _is_ready";b:0;s:23:"name-prop-5a97c9ca7ac67";a:4:{i:0;s:9:"textfield";i:1;s:0:"";s:11:"overridable";b:1;s:20:"overridable_multiple";b:0;}}s:11:"overridable";b:1;s:20:"overridable_multiple";b:0;}}s:11:"overridable";b:1;s:20:"overridable_multiple";b:0;}s:25:"author-prop-5a97b109b22c3";a:4:{i:0;s:24:"http://schema.org/Person";i:1;O:34:"wpbuddy\rich_snippets\Rich_Snippet":6:{s:2:"id";s:18:"snip-5a97bf5c0ced0";s:7:"context";s:17:"http://schema.org";s:4:"type";s:6:"Person";s:45:" wpbuddy\rich_snippets\Rich_Snippet _is_ready";b:0;s:23:"name-prop-5a97bf5c163fb";a:4:{i:0;s:24:"current_post_author_name";i:1;N;s:11:"overridable";b:0;s:20:"overridable_multiple";b:0;}s:22:"url-prop-5a97bf5c23b4a";a:4:{i:0;s:23:"current_post_author_url";i:1;N;s:11:"overridable";b:0;s:20:"overridable_multiple";b:0;}}s:11:"overridable";b:0;s:20:"overridable_multiple";b:0;}s:30:"description-prop-5a97b109c6003";a:4:{i:0;s:20:"current_post_excerpt";i:1;N;s:11:"overridable";b:0;s:20:"overridable_multiple";b:0;}s:32:"datePublished-prop-5a97b109d0961";a:4:{i:0;s:17:"current_post_date";i:1;N;s:11:"overridable";b:0;s:20:"overridable_multiple";b:0;}s:31:"reviewRating-prop-5a97bde8d91fc";a:4:{i:0;s:18:"misc_rating_5_star";i:1;i:4;s:11:"overridable";b:1;s:20:"overridable_multiple";b:0;}s:29:"reviewBody-prop-5a97bebcecb07";a:4:{i:0;s:20:"current_post_content";i:1;N;s:11:"overridable";b:0;s:20:"overridable_multiple";b:0;}s:31:"dateModified-prop-5a97bfb128a9c";a:4:{i:0;s:26:"current_post_modified_date";i:1;N;s:11:"overridable";b:0;s:20:"overridable_multiple";b:0;}}}' );

		return array(
			'title'    => __( 'Review of Product', 'rich-snippets-schema' ),
			'status'   => 'draft',
			'position' => '',
			'schema'   => $snippet,
		);
	}


	/**
	 * @since 2.3.1
	 *
	 * @return array
	 */
	public static function product_woocommerce() {

		$position = unserialize( 'O:38:"wpbuddy\rich_snippets\Position_Ruleset":1:{s:50:" wpbuddy\rich_snippets\Position_Ruleset rulegroups";a:1:{i:0;O:41:"wpbuddy\rich_snippets\Position_Rule_Group":1:{s:48:" wpbuddy\rich_snippets\Position_Rule_Group rules";a:1:{i:0;O:35:"wpbuddy\rich_snippets\Position_Rule":3:{s:5:"param";s:9:"post_type";s:8:"operator";s:2:"==";s:5:"value";s:7:"product";}}}}}' );

		$snippet = unserialize( 'a:1:{s:23:"snip-global-product-woo";O:34:"wpbuddy\rich_snippets\Rich_Snippet":19:{s:2:"id";s:23:"snip-global-product-woo";s:7:"context";s:17:"http://schema.org";s:4:"type";s:7:"Product";s:45:" wpbuddy\rich_snippets\Rich_Snippet _is_ready";b:0;s:52:" wpbuddy\rich_snippets\Rich_Snippet _is_main_snippet";b:1;s:52:" wpbuddy\rich_snippets\Rich_Snippet _version_created";s:5:"2.6.0";s:22:"sku-prop-5a90e4c190ec5";a:4:{i:0;s:15:"woocommerce_sku";i:1;N;s:11:"overridable";b:0;s:20:"overridable_multiple";b:0;}s:22:"mpn-prop-5a90e4c1a707e";a:4:{i:0;s:39:"textfield_woocommerce_product_attribute";i:1;s:3:"mpn";s:11:"overridable";b:1;s:20:"overridable_multiple";b:0;}s:24:"gtin8-prop-5a90e4c1bc50c";a:4:{i:0;s:39:"textfield_woocommerce_product_attribute";i:1;s:5:"gtin8";s:11:"overridable";b:1;s:20:"overridable_multiple";b:0;}s:24:"image-prop-5a90e4c1cf2ec";a:4:{i:0;s:29:"http://schema.org/ImageObject";i:1;O:34:"wpbuddy\rich_snippets\Rich_Snippet":9:{s:2:"id";s:18:"snip-5a97cc0643099";s:7:"context";s:17:"http://schema.org";s:4:"type";s:11:"ImageObject";s:45:" wpbuddy\rich_snippets\Rich_Snippet _is_ready";b:0;s:52:" wpbuddy\rich_snippets\Rich_Snippet _is_main_snippet";b:0;s:52:" wpbuddy\rich_snippets\Rich_Snippet _version_created";s:5:"2.6.0";s:25:"height-prop-5a97cc064cf42";a:4:{i:0;s:29:"current_post_thumbnail_height";i:1;N;s:11:"overridable";b:0;s:20:"overridable_multiple";b:0;}s:24:"width-prop-5a97cc065a42e";a:4:{i:0;s:28:"current_post_thumbnail_width";i:1;N;s:11:"overridable";b:0;s:20:"overridable_multiple";b:0;}s:22:"url-prop-5a97cc066871e";a:4:{i:0;s:26:"current_post_thumbnail_url";i:1;N;s:11:"overridable";b:0;s:20:"overridable_multiple";b:0;}}s:11:"overridable";b:0;s:20:"overridable_multiple";b:0;}s:23:"name-prop-5a90e4c1e3c44";a:4:{i:0;s:18:"current_post_title";i:1;N;s:11:"overridable";b:0;s:20:"overridable_multiple";b:0;}s:34:"aggregateRating-prop-5a90e4c20773c";a:4:{i:0;s:25:"woocommerce_review_rating";i:1;N;s:11:"overridable";b:0;s:20:"overridable_multiple";b:0;}s:25:"offers-prop-5a90e4c21d251";a:4:{i:0;s:18:"woocommerce_offers";i:1;N;s:11:"overridable";b:0;s:20:"overridable_multiple";b:0;}s:25:"gtin14-prop-5a90e4c23b1a4";a:4:{i:0;s:39:"textfield_woocommerce_product_attribute";i:1;s:6:"gtin14";s:11:"overridable";b:1;s:20:"overridable_multiple";b:0;}s:25:"gtin13-prop-5a90e4c261b5e";a:4:{i:0;s:39:"textfield_woocommerce_product_attribute";i:1;s:6:"gtin13";s:11:"overridable";b:1;s:20:"overridable_multiple";b:0;}s:30:"description-prop-5a90e4c297536";a:4:{i:0;s:20:"current_post_excerpt";i:1;N;s:11:"overridable";b:0;s:20:"overridable_multiple";b:0;}s:24:"brand-prop-5a90e4c2b5e88";a:4:{i:0;s:23:"http://schema.org/Brand";i:1;O:34:"wpbuddy\rich_snippets\Rich_Snippet":7:{s:2:"id";s:18:"snip-5a97cc562cbb3";s:7:"context";s:17:"http://schema.org";s:4:"type";s:5:"Brand";s:45:" wpbuddy\rich_snippets\Rich_Snippet _is_ready";b:0;s:52:" wpbuddy\rich_snippets\Rich_Snippet _is_main_snippet";b:0;s:52:" wpbuddy\rich_snippets\Rich_Snippet _version_created";s:5:"2.6.0";s:23:"name-prop-5a97cc563c122";a:4:{i:0;s:39:"textfield_woocommerce_product_attribute";i:1;s:5:"brand";s:11:"overridable";b:1;s:20:"overridable_multiple";b:0;}}s:11:"overridable";b:1;s:20:"overridable_multiple";b:0;}s:25:"gtin12-prop-5c0f9ac960039";a:4:{i:0;s:39:"textfield_woocommerce_product_attribute";i:1;s:6:"gtin12";s:11:"overridable";b:0;s:20:"overridable_multiple";b:0;}s:25:"review-prop-5c0f9ae8cd0bc";a:4:{i:0;s:19:"woocommerce_reviews";i:1;N;s:11:"overridable";b:0;s:20:"overridable_multiple";b:0;}}}' );

		return array(
			'title'    => __( 'Product (WooCommerce)', 'rich-snippets-schema' ),
			'position' => $position,
			'status'   => 'draft',
			'schema'   => $snippet,
		);
	}


	/**
	 * @since 2.7.0
	 *
	 * @return array
	 */
	public static function recipe() {

		$position = unserialize( 'O:38:"wpbuddy\rich_snippets\Position_Ruleset":1:{s:50:" wpbuddy\rich_snippets\Position_Ruleset rulegroups";a:1:{i:0;O:41:"wpbuddy\rich_snippets\Position_Rule_Group":1:{s:48:" wpbuddy\rich_snippets\Position_Rule_Group rules";a:1:{i:0;O:35:"wpbuddy\rich_snippets\Position_Rule":3:{s:5:"param";s:9:"post_type";s:8:"operator";s:2:"==";s:5:"value";s:4:"post";}}}}}' );

		$snippet = unserialize( 'a:1:{s:18:"snip-global-recipe";O:34:"wpbuddy\rich_snippets\Rich_Snippet":25:{s:2:"id";s:18:"snip-global-recipe";s:7:"context";s:17:"http://schema.org";s:4:"type";s:6:"Recipe";s:45:" wpbuddy\rich_snippets\Rich_Snippet _is_ready";b:0;s:52:" wpbuddy\rich_snippets\Rich_Snippet _is_main_snippet";b:1;s:52:" wpbuddy\rich_snippets\Rich_Snippet _version_created";s:5:"2.7.0";s:30:"recipeYield-prop-5c0d1a90b6931";a:4:{i:0;s:9:"textfield";i:1;s:0:"";s:11:"overridable";b:1;s:20:"overridable_multiple";b:0;}s:33:"recipeCategory-prop-5c0d1a90be968";a:4:{i:0;s:16:"current_category";i:1;N;s:11:"overridable";b:0;s:20:"overridable_multiple";b:0;}s:24:"image-prop-5c0d1a90c6e69";a:4:{i:0;s:29:"http://schema.org/ImageObject";i:1;O:34:"wpbuddy\rich_snippets\Rich_Snippet":9:{s:2:"id";s:18:"snip-5c0d2014cb753";s:7:"context";s:17:"http://schema.org";s:4:"type";s:11:"ImageObject";s:45:" wpbuddy\rich_snippets\Rich_Snippet _is_ready";b:0;s:52:" wpbuddy\rich_snippets\Rich_Snippet _is_main_snippet";b:0;s:52:" wpbuddy\rich_snippets\Rich_Snippet _version_created";s:5:"2.7.0";s:25:"height-prop-5c0d2014d6457";a:4:{i:0;s:28:"current_post_thumbnail_width";i:1;N;s:11:"overridable";b:0;s:20:"overridable_multiple";b:0;}s:24:"width-prop-5c0d2014df612";a:4:{i:0;s:29:"current_post_thumbnail_height";i:1;N;s:11:"overridable";b:0;s:20:"overridable_multiple";b:0;}s:22:"url-prop-5c0d2014e7fa0";a:4:{i:0;s:26:"current_post_thumbnail_url";i:1;N;s:11:"overridable";b:0;s:20:"overridable_multiple";b:0;}}s:11:"overridable";b:0;s:20:"overridable_multiple";b:0;}s:23:"name-prop-5c0d1a90cf5bf";a:4:{i:0;s:18:"current_post_title";i:1;N;s:11:"overridable";b:0;s:20:"overridable_multiple";b:0;}s:34:"aggregateRating-prop-5c0d1a90d7f15";a:4:{i:0;s:18:"misc_rating_5_star";i:1;i:0;s:11:"overridable";b:1;s:20:"overridable_multiple";b:0;}s:35:"recipeIngredient-prop-5c0d1a90e07c8";a:4:{i:0;s:9:"textfield";i:1;s:0:"";s:11:"overridable";b:1;s:20:"overridable_multiple";b:1;}s:37:"recipeInstructions-prop-5c0d1a90e9dae";a:4:{i:0;s:27:"http://schema.org/HowToStep";i:1;O:34:"wpbuddy\rich_snippets\Rich_Snippet":7:{s:2:"id";s:18:"snip-5c0d1e64a951e";s:7:"context";s:17:"http://schema.org";s:4:"type";s:9:"HowToStep";s:45:" wpbuddy\rich_snippets\Rich_Snippet _is_ready";b:0;s:52:" wpbuddy\rich_snippets\Rich_Snippet _is_main_snippet";b:0;s:52:" wpbuddy\rich_snippets\Rich_Snippet _version_created";s:5:"2.7.0";s:23:"text-prop-5c0d1e7c0dffc";a:4:{i:0;s:9:"textfield";i:1;s:0:"";s:11:"overridable";b:1;s:20:"overridable_multiple";b:0;}}s:11:"overridable";b:1;s:20:"overridable_multiple";b:1;}s:28:"nutrition-prop-5c0d1a910fedc";a:4:{i:0;s:38:"http://schema.org/NutritionInformation";i:1;O:34:"wpbuddy\rich_snippets\Rich_Snippet":7:{s:2:"id";s:18:"snip-5c0d1ba6d7b25";s:7:"context";s:17:"http://schema.org";s:4:"type";s:20:"NutritionInformation";s:45:" wpbuddy\rich_snippets\Rich_Snippet _is_ready";b:0;s:52:" wpbuddy\rich_snippets\Rich_Snippet _is_main_snippet";b:0;s:52:" wpbuddy\rich_snippets\Rich_Snippet _version_created";s:5:"2.7.0";s:27:"calories-prop-5c0d1ba6e056f";a:4:{i:0;s:9:"textfield";i:1;s:0:"";s:11:"overridable";b:1;s:20:"overridable_multiple";b:0;}}s:11:"overridable";b:1;s:20:"overridable_multiple";b:0;}s:27:"prepTime-prop-5c0d1a91234c0";a:4:{i:0;s:21:"misc_duration_minutes";i:1;i:30;s:11:"overridable";b:1;s:20:"overridable_multiple";b:0;}s:31:"dateModified-prop-5c0d1a912eac1";a:4:{i:0;s:26:"current_post_modified_date";i:1;N;s:11:"overridable";b:0;s:20:"overridable_multiple";b:0;}s:25:"author-prop-5c0d1a913d071";a:4:{i:0;s:24:"http://schema.org/Person";i:1;O:34:"wpbuddy\rich_snippets\Rich_Snippet":8:{s:2:"id";s:18:"snip-5c0d1addef3d0";s:7:"context";s:17:"http://schema.org";s:4:"type";s:6:"Person";s:45:" wpbuddy\rich_snippets\Rich_Snippet _is_ready";b:0;s:52:" wpbuddy\rich_snippets\Rich_Snippet _is_main_snippet";b:0;s:52:" wpbuddy\rich_snippets\Rich_Snippet _version_created";s:5:"2.7.0";s:23:"name-prop-5c0d1ade06d98";a:4:{i:0;s:24:"current_post_author_name";i:1;N;s:11:"overridable";b:0;s:20:"overridable_multiple";b:0;}s:22:"url-prop-5c0d1ade10859";a:4:{i:0;s:23:"current_post_author_url";i:1;N;s:11:"overridable";b:0;s:20:"overridable_multiple";b:0;}}s:11:"overridable";b:0;s:20:"overridable_multiple";b:0;}s:27:"cookTime-prop-5c0d1a914d109";a:4:{i:0;s:21:"misc_duration_minutes";i:1;i:30;s:11:"overridable";b:1;s:20:"overridable_multiple";b:0;}s:28:"totalTime-prop-5c0d1a9157585";a:4:{i:0;s:21:"misc_duration_minutes";i:1;i:30;s:11:"overridable";b:1;s:20:"overridable_multiple";b:0;}s:25:"review-prop-5c0d1a915fef0";a:4:{i:0;s:24:"http://schema.org/Review";i:1;O:34:"wpbuddy\rich_snippets\Rich_Snippet":9:{s:2:"id";s:18:"snip-5c0d1eb185c32";s:7:"context";s:17:"http://schema.org";s:4:"type";s:6:"Review";s:45:" wpbuddy\rich_snippets\Rich_Snippet _is_ready";b:0;s:52:" wpbuddy\rich_snippets\Rich_Snippet _is_main_snippet";b:0;s:52:" wpbuddy\rich_snippets\Rich_Snippet _version_created";s:5:"2.7.0";s:31:"reviewRating-prop-5c0d1eb18d4cb";a:4:{i:0;s:18:"misc_rating_5_star";i:1;i:0;s:11:"overridable";b:1;s:20:"overridable_multiple";b:0;}s:28:"publisher-prop-5c0d1eb19e2e5";a:4:{i:0;s:39:"global_snippet_snip-global-organization";i:1;N;s:11:"overridable";b:0;s:20:"overridable_multiple";b:0;}s:29:"reviewBody-prop-5c0d1eb1c5246";a:4:{i:0;s:9:"textfield";i:1;s:21:"A review of the dish.";s:11:"overridable";b:1;s:20:"overridable_multiple";b:0;}}s:11:"overridable";b:1;s:20:"overridable_multiple";b:0;}s:30:"description-prop-5c0d1a9166ba8";a:4:{i:0;s:9:"textfield";i:1;s:36:"A short summary describing the dish.";s:11:"overridable";b:1;s:20:"overridable_multiple";b:0;}s:32:"datePublished-prop-5c0d1a916c158";a:4:{i:0;s:17:"current_post_date";i:1;N;s:11:"overridable";b:0;s:20:"overridable_multiple";b:0;}s:27:"keywords-prop-5c0d1b851ceb7";a:4:{i:0;s:9:"textfield";i:1;s:0:"";s:11:"overridable";b:1;s:20:"overridable_multiple";b:0;}s:32:"recipeCuisine-prop-5c0d1e1e1b043";a:4:{i:0;s:9:"textfield";i:1;s:0:"";s:11:"overridable";b:1;s:20:"overridable_multiple";b:0;}s:24:"video-prop-5c0d1f2e823a4";a:4:{i:0;s:29:"http://schema.org/VideoObject";i:1;O:34:"wpbuddy\rich_snippets\Rich_Snippet":11:{s:2:"id";s:18:"snip-5c0d1f4a2ef2c";s:7:"context";s:17:"http://schema.org";s:4:"type";s:11:"VideoObject";s:45:" wpbuddy\rich_snippets\Rich_Snippet _is_ready";b:0;s:52:" wpbuddy\rich_snippets\Rich_Snippet _is_main_snippet";b:0;s:52:" wpbuddy\rich_snippets\Rich_Snippet _version_created";s:5:"2.7.0";s:23:"name-prop-5c0d1f4a3448e";a:4:{i:0;s:18:"current_post_title";i:1;N;s:11:"overridable";b:0;s:20:"overridable_multiple";b:0;}s:31:"thumbnailUrl-prop-5c0d1f4a41fb8";a:4:{i:0;s:9:"textfield";i:1;s:0:"";s:11:"overridable";b:1;s:20:"overridable_multiple";b:1;}s:30:"description-prop-5c0d1f4a46ad5";a:4:{i:0;s:9:"textfield";i:1;s:0:"";s:11:"overridable";b:1;s:20:"overridable_multiple";b:0;}s:29:"contentUrl-prop-5c0d1f87990e9";a:4:{i:0;s:9:"textfield";i:1;s:0:"";s:11:"overridable";b:1;s:20:"overridable_multiple";b:0;}s:27:"embedUrl-prop-5c0d1f96a595a";a:4:{i:0;s:16:"current_post_url";i:1;N;s:11:"overridable";b:0;s:20:"overridable_multiple";b:0;}}s:11:"overridable";b:1;s:20:"overridable_multiple";b:0;}}}' );

		return array(
			'title'    => __( 'Recipe', 'rich-snippets-schema' ),
			'position' => $position,
			'status'   => 'draft',
			'schema'   => $snippet,
		);
	}


	/**
	 * @since 2.8.0
	 *
	 * @return array
	 */
	public static function sitelink_serachbox() {
		$position = unserialize( 'O:38:"wpbuddy\rich_snippets\Position_Ruleset":1:{s:50:" wpbuddy\rich_snippets\Position_Ruleset rulegroups";a:1:{i:0;O:41:"wpbuddy\rich_snippets\Position_Rule_Group":1:{s:48:" wpbuddy\rich_snippets\Position_Rule_Group rules";a:1:{i:0;O:35:"wpbuddy\rich_snippets\Position_Rule":3:{s:5:"param";s:9:"page_type";s:8:"operator";s:2:"==";s:5:"value";s:3:"all";}}}}}' );

		$snippet = unserialize( 'a:1:{s:30:"snip-global-sitelink-searchbox";O:34:"wpbuddy\rich_snippets\Rich_Snippet":8:{s:2:"id";s:30:"snip-global-sitelink-searchbox";s:7:"context";s:17:"http://schema.org";s:4:"type";s:7:"WebSite";s:45:" wpbuddy\rich_snippets\Rich_Snippet _is_ready";b:0;s:52:" wpbuddy\rich_snippets\Rich_Snippet _is_main_snippet";b:1;s:52:" wpbuddy\rich_snippets\Rich_Snippet _version_created";s:5:"2.8.0";s:41:" wpbuddy\rich_snippets\Rich_Snippet _loop";N;s:34:"potentialAction-prop-5c616e94f2263";a:4:{i:0;s:30:"http://schema.org/SearchAction";i:1;O:34:"wpbuddy\rich_snippets\Rich_Snippet":9:{s:2:"id";s:18:"snip-5c616e9d6ca75";s:7:"context";s:17:"http://schema.org";s:4:"type";s:12:"SearchAction";s:45:" wpbuddy\rich_snippets\Rich_Snippet _is_ready";b:0;s:52:" wpbuddy\rich_snippets\Rich_Snippet _is_main_snippet";b:0;s:52:" wpbuddy\rich_snippets\Rich_Snippet _version_created";s:5:"2.8.0";s:41:" wpbuddy\rich_snippets\Rich_Snippet _loop";s:0:"";s:25:"target-prop-5c616ea8abfc2";a:4:{i:0;s:22:"search_url_search_term";i:1;N;s:11:"overridable";b:0;s:20:"overridable_multiple";b:0;}s:30:"query-input-prop-5c6171f30aebf";a:4:{i:0;s:9:"textfield";i:1;s:32:"required name=search_term_string";s:11:"overridable";b:0;s:20:"overridable_multiple";b:0;}}s:11:"overridable";b:0;s:20:"overridable_multiple";b:0;}}}' );

		return array(
			'title'    => __( 'Sitelink Searchbox', 'rich-snippets-schema' ),
			'position' => $position,
			'status'   => 'publish',
			'schema'   => $snippet,
		);
	}


	/**
	 * @since 2.8.0
	 *
	 * @return array
	 */
	public static function carousel_articles() {

		$position = unserialize( 'O:38:"wpbuddy\rich_snippets\Position_Ruleset":1:{s:50:" wpbuddy\rich_snippets\Position_Ruleset rulegroups";a:5:{i:0;O:41:"wpbuddy\rich_snippets\Position_Rule_Group":1:{s:48:" wpbuddy\rich_snippets\Position_Rule_Group rules";a:1:{i:0;O:35:"wpbuddy\rich_snippets\Position_Rule":3:{s:5:"param";s:9:"page_type";s:8:"operator";s:2:"==";s:5:"value";s:16:"archive_category";}}}i:1;O:41:"wpbuddy\rich_snippets\Position_Rule_Group":1:{s:48:" wpbuddy\rich_snippets\Position_Rule_Group rules";a:1:{i:0;O:35:"wpbuddy\rich_snippets\Position_Rule":3:{s:5:"param";s:9:"page_type";s:8:"operator";s:2:"==";s:5:"value";s:10:"posts_page";}}}i:2;O:41:"wpbuddy\rich_snippets\Position_Rule_Group":1:{s:48:" wpbuddy\rich_snippets\Position_Rule_Group rules";a:1:{i:0;O:35:"wpbuddy\rich_snippets\Position_Rule":3:{s:5:"param";s:9:"page_type";s:8:"operator";s:2:"==";s:5:"value";s:10:"front_page";}}}i:3;O:41:"wpbuddy\rich_snippets\Position_Rule_Group":1:{s:48:" wpbuddy\rich_snippets\Position_Rule_Group rules";a:1:{i:0;O:35:"wpbuddy\rich_snippets\Position_Rule":3:{s:5:"param";s:9:"page_type";s:8:"operator";s:2:"==";s:5:"value";s:6:"search";}}}i:4;O:41:"wpbuddy\rich_snippets\Position_Rule_Group":1:{s:48:" wpbuddy\rich_snippets\Position_Rule_Group rules";a:1:{i:0;O:35:"wpbuddy\rich_snippets\Position_Rule":3:{s:5:"param";s:9:"page_type";s:8:"operator";s:2:"==";s:5:"value";s:16:"archive_post_tag";}}}}}' );

		$snippet = unserialize( 'a:1:{s:26:"snip-global-carousel-posts";O:34:"wpbuddy\rich_snippets\Rich_Snippet":8:{s:2:"id";s:26:"snip-global-carousel-posts";s:7:"context";s:17:"http://schema.org";s:4:"type";s:8:"ItemList";s:45:" wpbuddy\rich_snippets\Rich_Snippet _is_ready";b:0;s:52:" wpbuddy\rich_snippets\Rich_Snippet _is_main_snippet";b:1;s:52:" wpbuddy\rich_snippets\Rich_Snippet _version_created";s:5:"2.7.4";s:41:" wpbuddy\rich_snippets\Rich_Snippet _loop";N;s:34:"itemListElement-prop-5c641bfb50334";a:4:{i:0;s:26:"http://schema.org/ListItem";i:1;O:34:"wpbuddy\rich_snippets\Rich_Snippet":9:{s:2:"id";s:18:"snip-5c641c1b22b5b";s:7:"context";s:17:"http://schema.org";s:4:"type";s:8:"ListItem";s:45:" wpbuddy\rich_snippets\Rich_Snippet _is_ready";b:0;s:52:" wpbuddy\rich_snippets\Rich_Snippet _is_main_snippet";b:0;s:52:" wpbuddy\rich_snippets\Rich_Snippet _version_created";s:5:"2.7.4";s:41:" wpbuddy\rich_snippets\Rich_Snippet _loop";s:10:"main_query";s:27:"position-prop-5c641c1b47139";a:4:{i:0;s:27:"textfield_sequential_number";i:1;s:23:"carousel_category_posts";s:11:"overridable";b:0;s:20:"overridable_multiple";b:0;}s:22:"url-prop-5c641cf01b059";a:4:{i:0;s:16:"current_post_url";i:1;N;s:11:"overridable";b:0;s:20:"overridable_multiple";b:0;}}s:11:"overridable";b:0;s:20:"overridable_multiple";b:0;}}}' );

		return array(
			'title'    => __( 'Carousel for Frontpage, Posts-Page, Search-Page & Archive pages', 'rich-snippets-schema' ),
			'position' => $position,
			'status'   => 'publish',
			'schema'   => $snippet,
		);
	}


	/**
	 * @since 2.8.0
	 *
	 * @return array
	 */
	public static function breadcrumbs_posts() {

		$position = unserialize( 'O:38:"wpbuddy\rich_snippets\Position_Ruleset":1:{s:50:" wpbuddy\rich_snippets\Position_Ruleset rulegroups";a:1:{i:0;O:41:"wpbuddy\rich_snippets\Position_Rule_Group":1:{s:48:" wpbuddy\rich_snippets\Position_Rule_Group rules";a:1:{i:0;O:35:"wpbuddy\rich_snippets\Position_Rule":3:{s:5:"param";s:9:"post_type";s:8:"operator";s:2:"==";s:5:"value";s:4:"post";}}}}}' );

		$snippet = unserialize( 'a:1:{s:29:"snip-global-breadcrumbs-posts";O:34:"wpbuddy\rich_snippets\Rich_Snippet":9:{s:2:"id";s:29:"snip-global-breadcrumbs-posts";s:7:"context";s:17:"http://schema.org";s:4:"type";s:14:"BreadcrumbList";s:45:" wpbuddy\rich_snippets\Rich_Snippet _is_ready";b:0;s:52:" wpbuddy\rich_snippets\Rich_Snippet _is_main_snippet";b:1;s:52:" wpbuddy\rich_snippets\Rich_Snippet _version_created";s:5:"2.7.4";s:41:" wpbuddy\rich_snippets\Rich_Snippet _loop";N;s:34:"itemListElement-prop-5c63f68381ee5";a:4:{i:0;s:26:"http://schema.org/ListItem";i:1;O:34:"wpbuddy\rich_snippets\Rich_Snippet":10:{s:2:"id";s:18:"snip-5c641556a573b";s:7:"context";s:17:"http://schema.org";s:4:"type";s:8:"ListItem";s:45:" wpbuddy\rich_snippets\Rich_Snippet _is_ready";b:0;s:52:" wpbuddy\rich_snippets\Rich_Snippet _is_main_snippet";b:0;s:52:" wpbuddy\rich_snippets\Rich_Snippet _version_created";s:5:"2.7.4";s:41:" wpbuddy\rich_snippets\Rich_Snippet _loop";s:17:"taxonomy_category";s:23:"name-prop-5c64155761709";a:4:{i:0;s:10:"term_title";i:1;N;s:11:"overridable";b:0;s:20:"overridable_multiple";b:0;}s:27:"position-prop-5c6415576a168";a:4:{i:0;s:27:"textfield_sequential_number";i:1;s:17:"breadcrumbs_posts";s:11:"overridable";b:0;s:20:"overridable_multiple";b:0;}s:23:"item-prop-5c64155778310";a:4:{i:0;s:8:"term_url";i:1;N;s:11:"overridable";b:0;s:20:"overridable_multiple";b:0;}}s:11:"overridable";b:0;s:20:"overridable_multiple";b:0;}s:34:"itemListElement-prop-5c6404b73450b";a:4:{i:0;s:26:"http://schema.org/ListItem";i:1;O:34:"wpbuddy\rich_snippets\Rich_Snippet":10:{s:2:"id";s:18:"snip-5c6404be55fb9";s:7:"context";s:17:"http://schema.org";s:4:"type";s:8:"ListItem";s:45:" wpbuddy\rich_snippets\Rich_Snippet _is_ready";b:0;s:52:" wpbuddy\rich_snippets\Rich_Snippet _is_main_snippet";b:0;s:52:" wpbuddy\rich_snippets\Rich_Snippet _version_created";s:5:"2.7.4";s:41:" wpbuddy\rich_snippets\Rich_Snippet _loop";s:0:"";s:23:"name-prop-5c6404be5f27d";a:4:{i:0;s:18:"current_post_title";i:1;N;s:11:"overridable";b:0;s:20:"overridable_multiple";b:0;}s:27:"position-prop-5c6404be6c368";a:4:{i:0;s:27:"textfield_sequential_number";i:1;s:17:"breadcrumbs_posts";s:11:"overridable";b:0;s:20:"overridable_multiple";b:0;}s:23:"item-prop-5c6404be79508";a:4:{i:0;s:16:"current_post_url";i:1;N;s:11:"overridable";b:0;s:20:"overridable_multiple";b:0;}}s:11:"overridable";b:0;s:20:"overridable_multiple";b:0;}}}' );

		return array(
			'title'    => __( 'Breadcrumbs for posts', 'rich-snippets-schema' ),
			'position' => $position,
			'status'   => 'publish',
			'schema'   => $snippet,
		);
	}


	/**
	 * @since 2.8.0
	 *
	 * @return array
	 */
	public static function breadcrumbs_pages() {

		$position = unserialize( 'O:38:"wpbuddy\rich_snippets\Position_Ruleset":1:{s:50:" wpbuddy\rich_snippets\Position_Ruleset rulegroups";a:1:{i:0;O:41:"wpbuddy\rich_snippets\Position_Rule_Group":1:{s:48:" wpbuddy\rich_snippets\Position_Rule_Group rules";a:1:{i:0;O:35:"wpbuddy\rich_snippets\Position_Rule":3:{s:5:"param";s:9:"post_type";s:8:"operator";s:2:"==";s:5:"value";s:4:"page";}}}}}' );

		$snippet = unserialize( 'a:1:{s:29:"snip-global-breadcrumbs-pages";O:34:"wpbuddy\rich_snippets\Rich_Snippet":9:{s:2:"id";s:29:"snip-global-breadcrumbs-pages";s:7:"context";s:17:"http://schema.org";s:4:"type";s:14:"BreadcrumbList";s:45:" wpbuddy\rich_snippets\Rich_Snippet _is_ready";b:0;s:52:" wpbuddy\rich_snippets\Rich_Snippet _is_main_snippet";b:1;s:52:" wpbuddy\rich_snippets\Rich_Snippet _version_created";s:5:"2.7.4";s:41:" wpbuddy\rich_snippets\Rich_Snippet _loop";N;s:34:"itemListElement-prop-5c62a14111e9c";a:4:{i:0;s:26:"http://schema.org/ListItem";i:1;O:34:"wpbuddy\rich_snippets\Rich_Snippet":10:{s:2:"id";s:18:"snip-5c62b1dc9c40e";s:7:"context";s:17:"http://schema.org";s:4:"type";s:8:"ListItem";s:45:" wpbuddy\rich_snippets\Rich_Snippet _is_ready";b:0;s:52:" wpbuddy\rich_snippets\Rich_Snippet _is_main_snippet";b:0;s:52:" wpbuddy\rich_snippets\Rich_Snippet _version_created";s:5:"2.7.4";s:41:" wpbuddy\rich_snippets\Rich_Snippet _loop";s:12:"page_parents";s:23:"name-prop-5c62b1dca84e0";a:4:{i:0;s:18:"current_post_title";i:1;N;s:11:"overridable";b:0;s:20:"overridable_multiple";b:0;}s:27:"position-prop-5c62b1dcb4b23";a:4:{i:0;s:27:"textfield_sequential_number";i:1;s:17:"breadcrumbs_pages";s:11:"overridable";b:0;s:20:"overridable_multiple";b:0;}s:23:"item-prop-5c62b1dcc6fe1";a:4:{i:0;s:16:"current_post_url";i:1;N;s:11:"overridable";b:0;s:20:"overridable_multiple";b:0;}}s:11:"overridable";b:0;s:20:"overridable_multiple";b:0;}s:34:"itemListElement-prop-5c64053c41463";a:4:{i:0;s:26:"http://schema.org/ListItem";i:1;O:34:"wpbuddy\rich_snippets\Rich_Snippet":10:{s:2:"id";s:18:"snip-5c640541f3c22";s:7:"context";s:17:"http://schema.org";s:4:"type";s:8:"ListItem";s:45:" wpbuddy\rich_snippets\Rich_Snippet _is_ready";b:0;s:52:" wpbuddy\rich_snippets\Rich_Snippet _is_main_snippet";b:0;s:52:" wpbuddy\rich_snippets\Rich_Snippet _version_created";s:5:"2.7.4";s:41:" wpbuddy\rich_snippets\Rich_Snippet _loop";s:0:"";s:23:"name-prop-5c6405420a089";a:4:{i:0;s:18:"current_post_title";i:1;N;s:11:"overridable";b:0;s:20:"overridable_multiple";b:0;}s:27:"position-prop-5c64054214640";a:4:{i:0;s:27:"textfield_sequential_number";i:1;s:17:"breadcrumbs_pages";s:11:"overridable";b:0;s:20:"overridable_multiple";b:0;}s:23:"item-prop-5c64054220672";a:4:{i:0;s:16:"current_post_url";i:1;N;s:11:"overridable";b:0;s:20:"overridable_multiple";b:0;}}s:11:"overridable";b:0;s:20:"overridable_multiple";b:0;}}}' );

		return array(
			'title'    => __( 'Breadcrumbs for pages', 'rich-snippets-schema' ),
			'position' => $position,
			'status'   => 'publish',
			'schema'   => $snippet,
		);
	}


	/**
	 * @since 2.8.0
	 *
	 * @return array
	 */
	public static function carousel_products() {
		$position = unserialize( 'O:38:"wpbuddy\rich_snippets\Position_Ruleset":1:{s:50:" wpbuddy\rich_snippets\Position_Ruleset rulegroups";a:2:{i:0;O:41:"wpbuddy\rich_snippets\Position_Rule_Group":1:{s:48:" wpbuddy\rich_snippets\Position_Rule_Group rules";a:1:{i:0;O:35:"wpbuddy\rich_snippets\Position_Rule":3:{s:5:"param";s:9:"page_type";s:8:"operator";s:2:"==";s:5:"value";s:3:"all";}}}i:1;O:41:"wpbuddy\rich_snippets\Position_Rule_Group":1:{s:48:" wpbuddy\rich_snippets\Position_Rule_Group rules";a:1:{i:0;O:35:"wpbuddy\rich_snippets\Position_Rule":3:{s:5:"param";s:9:"page_type";s:8:"operator";s:2:"==";s:5:"value";s:19:"archive_product_tag";}}}}}' );

		$snippet = unserialize( 'a:1:{s:29:"snip-global-carousel-products";O:34:"wpbuddy\rich_snippets\Rich_Snippet":8:{s:2:"id";s:29:"snip-global-carousel-products";s:7:"context";s:17:"http://schema.org";s:4:"type";s:8:"ItemList";s:45:" wpbuddy\rich_snippets\Rich_Snippet _is_ready";b:0;s:52:" wpbuddy\rich_snippets\Rich_Snippet _is_main_snippet";b:1;s:52:" wpbuddy\rich_snippets\Rich_Snippet _version_created";s:5:"2.7.4";s:41:" wpbuddy\rich_snippets\Rich_Snippet _loop";N;s:34:"itemListElement-prop-5c6529c924e4a";a:4:{i:0;s:26:"http://schema.org/ListItem";i:1;O:34:"wpbuddy\rich_snippets\Rich_Snippet":9:{s:2:"id";s:18:"snip-5c6529d7583b3";s:7:"context";s:17:"http://schema.org";s:4:"type";s:8:"ListItem";s:45:" wpbuddy\rich_snippets\Rich_Snippet _is_ready";b:0;s:52:" wpbuddy\rich_snippets\Rich_Snippet _is_main_snippet";b:0;s:52:" wpbuddy\rich_snippets\Rich_Snippet _version_created";s:5:"2.7.4";s:41:" wpbuddy\rich_snippets\Rich_Snippet _loop";s:10:"main_query";s:27:"position-prop-5c6529d776f58";a:4:{i:0;s:27:"textfield_sequential_number";i:1;s:29:"carousel_woocommerce_products";s:11:"overridable";b:0;s:20:"overridable_multiple";b:0;}s:22:"url-prop-5c652a11c053b";a:4:{i:0;s:16:"current_post_url";i:1;N;s:11:"overridable";b:0;s:20:"overridable_multiple";b:0;}}s:11:"overridable";b:0;s:20:"overridable_multiple";b:0;}}}' );

		return array(
			'title'    => __( 'Carousel for Product Archive pages (WooCommerce)', 'rich-snippets-schema' ),
			'position' => $position,
			'status'   => 'publish',
			'schema'   => $snippet,
		);
	}
}
