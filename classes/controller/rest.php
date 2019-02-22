<?php

namespace wpbuddy\rich_snippets;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly


/**
 * Class Rest.
 *
 * Here for any REST things.
 *
 * @package wpbuddy\rich_snippets
 *
 * @since   2.0.0
 */
final class Rest_Controller {

	const RETURN_TYPES_ALLOWED = array( 'exact', 'all', 'required', 'parents' );


	/**
	 * The instance.
	 *
	 * @var \wpbuddy\rich_snippets\Rest_Controller
	 *
	 * @since 2.0.0
	 */
	protected static $_instance = null;


	/**
	 * Get the singleton instance.
	 *
	 * Creates a new instance of the class if it does not exists.
	 *
	 * @return \wpbuddy\rich_snippets\Rest_Controller
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
	 * Initializes admin stuff
	 *
	 * @since 2.0.0
	 */
	public static function init() {

		$instance = self::instance();

		# register routes
		$instance->register_routes();

		# load translations
		Admin_Controller::instance()->load_translations();

	}


	/**
	 * Registers the REST routes.
	 *
	 * @since 2.0.0
	 */
	private function register_routes() {

		register_rest_route( 'wpbuddy/rich_snippets/v1', '/admin/verify', array(
			'methods'             => \WP_REST_Server::READABLE,
			'callback'            => array( self::instance(), 'activate_plugin' ),
			'permission_callback' => function ( $request ) {

				return apply_filters(
					'wpbuddy/rich_snippets/rest/permission',
					current_user_can( 'manage_options' ),
					$request
				);
			},
			'args'                => array(
				'purchase_code' => array(
					'required'          => true,
					'sanitize_callback' => function ( $param ) {

						return sanitize_text_field( $param );
					},
				),
			),
		) );

		register_rest_route( 'wpbuddy/rich_snippets/v1', '/schemas/types', array(
			'methods'             => \WP_REST_Server::READABLE,
			'callback'            => array( self::instance(), 'get_schema_types' ),
			'permission_callback' => function ( $request ) {

				return apply_filters(
					'wpbuddy/rich_snippets/rest/permission',
					current_user_can( 'manage_options' ),
					$request
				);
			},
			'args'                => array(
				'q'    => array(
					'required'          => true,
					'sanitize_callback' => function ( $param ) {

						return sanitize_text_field( $param );
					},
				),
				'page' => array(
					'sanitize_callback' => function ( $param ) {

						return absint( $param );
					},
				),
			),
		) );

		register_rest_route( 'wpbuddy/rich_snippets/v1', '/schemas/properties', array(
			'methods'             => \WP_REST_Server::READABLE,
			'callback'            => array( self::instance(), 'get_properties' ),
			'permission_callback' => function ( $request ) {

				return apply_filters(
					'wpbuddy/rich_snippets/rest/permission',
					current_user_can( 'manage_options' ),
					$request
				);
			},
			'args'                => array(
				'schema_type' => array(
					'required'          => true,
					'sanitize_callback' => function ( $param ) {

						$v = sanitize_text_field( $param );
						if ( 1 !== preg_match( "#http(s)?:\/\/#", $v ) ) {
							$v = 'http://' . $v;
						}

						return $v;
					},
				),
				'return_type' => array(
					'required'          => true,
					'validate_callback' => function ( $param, $request, $key ) {

						return in_array( strtolower( $param ), self::RETURN_TYPES_ALLOWED );


					},
					'sanitize_callback' => function ( $param ) {

						return sanitize_text_field( strtolower( $param ) );
					},
				),
				'q'           => array(
					'sanitize_callback' => function ( $param ) {

						return sanitize_text_field( $param );
					},
				),
			),
		) );

		register_rest_route( 'wpbuddy/rich_snippets/v1', '/schemas/properties/html', array(
			'methods'             => \WP_REST_Server::CREATABLE,
			'callback'            => array( self::instance(), 'get_properties_html' ),
			'permission_callback' => function ( $request ) {

				return apply_filters(
					'wpbuddy/rich_snippets/rest/permission',
					current_user_can( 'manage_options' ),
					$request
				);
			},
			'args'                => array(
				'properties'     => array(
					'validate_callback' => function ( $param, $request, $key ) {

						if ( ! is_array( $param ) ) {
							return new \WP_Error(
								'wpbuddy/rich_snippets/rest/param',
								_x( 'Please provide a list of properties.', 'Thrown error on rest api when there was no list of properties found.', 'rich-snippets-schema' )
							);
						}

						return $param;

					},
					'sanitize_callback' => function ( $param ) {

						return array_map( function ( $v ) {

							$v = sanitize_text_field( $v );
							if ( 1 !== preg_match( "#http(s)?:\/\/#", $v ) ) {
								$v = 'http://' . $v;
							}

							return $v;
						}, $param );
					},
				),
				'include_table'  => array(
					'sanitize_callback' => function ( $param ) {

						return filter_var( $param, FILTER_VALIDATE_BOOLEAN );
					},
				),
				'schema_type'    => array(
					'sanitize_callback' => function ( $param ) {

						$v = sanitize_text_field( $param );
						if ( 1 !== preg_match( "#http(s)?:\/\/#", $v ) ) {
							$v = 'http://' . $v;
						}

						return $v;
					},
				),
				'parent_type_id' => array(
					'sanitize_callback' => function ( $param ) {

						return sanitize_text_field( $param );
					},
				),
				'post_id'        => array(
					'validate_callback' => function ( $param, $request, $key ) {

						# check if post exists
						return is_string( get_post_status( absint( $param ) ) );

					},
					'sanitize_callback' => function ( $param ) {

						return absint( $param );
					},
				),
				'snippet_id'     => array(
					'sanitize_callback' => function ( $param ) {

						return sanitize_text_field( $param );
					},
				),
				'is_main_schema' => array(
					'sanitize_callback' => function ( $param ) {

						return Helper_Model::instance()->string_to_bool( $param );
					},
				),
			),
		) );

		register_rest_route( 'wpbuddy/rich_snippets/v1', 'positions/value-select', array(
			'methods'             => \WP_REST_Server::READABLE,
			'callback'            => array( self::instance(), 'load_position_value_select' ),
			'permission_callback' => function ( $request ) {

				return apply_filters(
					'wpbuddy/rich_snippets/rest/permission',
					current_user_can( 'manage_options' ),
					$request
				);
			},
			'args'                => array(
				'param' => array(
					'validate_callback' => function ( $param, $request, $key ) {

						$param_groups = Admin_Position_Controller::instance()->get_params();

						foreach ( $param_groups as $param_list ) {
							if ( isset( $param_list['params'][ $param ] ) ) {
								return true;
							}
						}

						return false;

					},
					'sanitize_callback' => function ( $param ) {

						return sanitize_text_field( $param );
					},
				),
			),
		) );

		register_rest_route( 'wpbuddy/rich_snippets/v1', 'positions/value-possibilities', array(
			'methods'             => \WP_REST_Server::READABLE,
			'callback'            => array( self::instance(), 'load_position_value_possibilities' ),
			'permission_callback' => function ( $request ) {

				return apply_filters(
					'wpbuddy/rich_snippets/rest/permission',
					current_user_can( 'manage_options' ),
					$request
				);
			},
			'args'                => array(
				'q'     => array(
					'required'          => true,
					'sanitize_callback' => function ( $param ) {

						return sanitize_text_field( $param );
					},
				),
				'page'  => array(
					'sanitize_callback' => function ( $param ) {

						return absint( $param );
					},
				),
				'param' => array(
					'validate_callback' => function ( $param, $request, $key ) {

						$param_groups = Admin_Position_Controller::instance()->get_params();

						foreach ( $param_groups as $param_list ) {
							if ( isset( $param_list['params'][ $param ] ) ) {
								return true;
							}
						}

						return false;

					},
					'sanitize_callback' => function ( $param ) {

						return sanitize_text_field( $param );
					},
				),
			),
		) );


		register_rest_route( 'wpbuddy/rich_snippets/v1', 'form_new', array(
			'methods'             => \WP_REST_Server::READABLE,
			'callback'            => array( self::instance(), 'get_rich_snippets_form_new' ),
			'permission_callback' => function ( $request ) {

				return apply_filters(
					'wpbuddy/rich_snippets/rest/permission',
					current_user_can( 'manage_options' ),
					$request
				);
			},
			'args'                => array(
				'post_id' => array(
					'validate_callback' => function ( $param, $request, $key ) {

						# check if post exists
						return is_string( get_post_status( absint( $param ) ) );

					},
					'sanitize_callback' => function ( $param ) {

						return absint( $param );
					},
				),
			),
		) );


		register_rest_route( 'wpbuddy/rich_snippets/v1', 'snippets_forms', array(
			'methods'             => \WP_REST_Server::READABLE,
			'callback'            => array( self::instance(), 'get_rich_snippets_forms' ),
			'permission_callback' => function ( $request ) {

				return apply_filters(
					'wpbuddy/rich_snippets/rest/permission',
					current_user_can( 'manage_options' ),
					$request
				);
			},
			'args'                => array(
				'post_id' => array(
					'validate_callback' => function ( $param, $request, $key ) {

						# check if post exists
						return is_string( get_post_status( absint( $param ) ) );

					},
					'sanitize_callback' => function ( $param ) {

						return absint( $param );
					},
				),
			),
		) );


		register_rest_route( 'wpbuddy/rich_snippets/v1', 'snippets_delete', array(
			'methods'             => \WP_REST_Server::DELETABLE,
			'callback'            => array( self::instance(), 'delete_snippets' ),
			'permission_callback' => function ( $request ) {

				return apply_filters(
					'wpbuddy/rich_snippets/rest/permission',
					current_user_can( 'manage_options' ),
					$request
				);
			},
			'args'                => array(
				'post_id'     => array(
					'validate_callback' => function ( $param, $request, $key ) {

						# check if post exists
						return is_string( get_post_status( absint( $param ) ) );
					},
					'sanitize_callback' => function ( $param ) {

						return absint( $param );
					},
				),
				'snippet_ids' => array(
					'validate_callback' => function ( $param, $request, $key ) {

						return is_array( $param );
					},
					'sanitize_callback' => function ( $param ) {

						if ( ! is_array( $param ) ) {
							return array();
						}

						return array_map( function ( $v ) {

							if ( ! is_scalar( $v ) ) {
								return '';
							}

							return sanitize_text_field( $v );
						}, $param );
					},
				),
			),
		) );


		register_rest_route( 'wpbuddy/rich_snippets/v1', 'clear_cache', array(
			'methods'             => \WP_REST_Server::READABLE,
			'callback'            => array( self::instance(), 'clear_cache' ),
			'permission_callback' => function ( $request ) {

				return apply_filters(
					'wpbuddy/rich_snippets/rest/permission',
					current_user_can( 'manage_options' ),
					$request
				);
			},
		) );

		register_rest_route( 'wpbuddy/rich_snippets/v1', 'overwrite_form', array(
			'methods'             => \WP_REST_Server::READABLE,
			'callback'            => array( self::instance(), 'get_overwrite_form' ),
			'permission_callback' => function ( $request ) {

				return apply_filters(
					'wpbuddy/rich_snippets/rest/permission',
					current_user_can( 'manage_options' ),
					$request
				);
			},
			'args'                => array(
				'post_id'         => array(
					'required'          => true,
					'validate_callback' => function ( $param, $request, $key ) {

						# check if post exists
						return is_string( get_post_status( absint( $param ) ) );
					},
					'sanitize_callback' => function ( $param ) {

						return absint( $param );
					},
				),
				'snippet_post_id' => array(
					'required'          => true,
					'validate_callback' => function ( $param, $request, $key ) {

						# check if post exists
						return is_string( get_post_status( absint( $param ) ) );
					},
					'sanitize_callback' => function ( $param ) {

						return absint( $param );
					},
				),
			),
		) );


		register_rest_route( 'wpbuddy/rich_snippets/v1', 'overwrite', array(
			'methods'             => \WP_REST_Server::CREATABLE,
			'callback'            => array( self::instance(), 'overwrite_snippet_values' ),
			'permission_callback' => function ( $request ) {

				return apply_filters(
					'wpbuddy/rich_snippets/rest/permission',
					current_user_can( 'manage_options' ),
					$request
				);
			},
			'args'                => array(
				'post_id'  => array(
					'required'          => true,
					'validate_callback' => function ( $param, $request, $key ) {

						# check if post exists
						return is_string( get_post_status( absint( $param ) ) );
					},
					'sanitize_callback' => function ( $param ) {

						return absint( $param );
					},
				),
				'snippets' => array(
					'required'          => true,
					'validate_callback' => function ( $param, $request, $key ) {

						return is_array( $param );
					},
					'sanitize_callback' => function ( $param ) {

						$data = [];
						foreach ( $param as $snippet_id => $snippet ) {
							if ( ! isset( $snippet['id'] ) ) {
								continue;
							}

							if ( ! isset( $snippet['properties'] ) ) {
								continue;
							}

							if ( ! is_array( $snippet['properties'] ) ) {
								continue;
							}

							$data[ $snippet_id ]['id'] = sanitize_text_field( $snippet['id'] );

							foreach ( $snippet['properties'] as $prop_id => $prop_value ) {
								if ( is_scalar( $prop_value ) ) {
									$data[ $snippet_id ]['properties'][ $prop_id ] = sanitize_text_field( $prop_value );
								} else if ( is_array( $prop_value ) ) {
									foreach ( $prop_value as $pv ) {
										if ( ! is_scalar( $pv ) ) {
											continue;
										}
										$data[ $snippet_id ]['properties'][ $prop_id ][] = sanitize_text_field( $pv );
									}
								}
							}
						}

						return $data;
					},
				),
			),
		) );


		register_rest_route( 'wpbuddy/rich_snippets/v1', 'faq', array(
			'methods'             => \WP_REST_Server::READABLE,
			'callback'            => array( self::instance(), 'support_faq_search' ),
			'permission_callback' => function ( $request ) {

				return apply_filters(
					'wpbuddy/rich_snippets/rest/permission',
					current_user_can( 'manage_options' ),
					$request
				);
			},
			'args'                => array(
				'q' => array(
					'required'          => true,
					'validate_callback' => function ( $param, $request, $key ) {

						$str = sanitize_text_field( $param );

						return ! empty( $str );
					},
					'sanitize_callback' => function ( $param ) {

						return sanitize_text_field( $param );
					},
				),
			),
		) );


		register_rest_route( 'wpbuddy/rich_snippets/v1', 'feature-request/vote', array(
			'methods'             => \WP_REST_Server::CREATABLE,
			'callback'            => array( self::instance(), 'support_feature_vote' ),
			'permission_callback' => function ( $request ) {

				return apply_filters(
					'wpbuddy/rich_snippets/rest/permission',
					current_user_can( 'manage_options' ),
					$request
				);
			},
			'args'                => array(
				'comment_id' => array(
					'required'          => true,
					'validate_callback' => function ( $param, $request, $key ) {

						$comment_id = intval( $param );

						return ! empty( $comment_id );
					},
					'sanitize_callback' => function ( $param ) {

						return intval( $param );
					},
				),
				'direction'  => array(
					'required'          => true,
					'validate_callback' => function ( $param, $request, $key ) {

						return in_array( $param, array( 'up', 'down' ) );
					},
					'sanitize_callback' => function ( $param ) {

						return sanitize_text_field( $param );
					},
				),
			),
		) );


		register_rest_route( 'wpbuddy/rich_snippets/v1', 'feature-request', array(
			'methods'             => \WP_REST_Server::CREATABLE,
			'callback'            => array( self::instance(), 'support_feature_add' ),
			'permission_callback' => function ( $request ) {

				return apply_filters(
					'wpbuddy/rich_snippets/rest/permission',
					current_user_can( 'manage_options' ),
					$request
				);
			},
			'args'                => array(
				'content' => array(
					'required'          => true,
					'sanitize_callback' => function ( $param ) {

						return sanitize_textarea_field( $param );
					},
				),
			),
		) );


		register_rest_route( 'wpbuddy/rich_snippets/v1', 'deactivate-license', array(
			'methods'             => \WP_REST_Server::CREATABLE,
			'callback'            => array( self::instance(), 'deactivate_license' ),
			'permission_callback' => function ( $request ) {

				return apply_filters(
					'wpbuddy/rich_snippets/rest/permission/deactivate_license',
					current_user_can( 'manage_options' ),
					$request
				);
			},
		) );
	}


	/**
	 * Performs a search on schema types
	 *
	 * @since 2.0.0
	 *
	 * @param \WP_REST_Request $request
	 *
	 * @return mixed|\WP_REST_Response
	 */
	public function get_schema_types( $request ) {

		$q = $request->get_param( 'q' );

		$types = Schemas_Model::get_types( $q );

		if ( is_wp_error( $types ) ) {
			return $types;
		}

		return rest_ensure_response( array( 'schema_types' => $types ) );
	}


	/**
	 * Performs a search on schema properties.
	 *
	 * @since 2.0.0
	 *
	 * @param \WP_REST_Request $request
	 *
	 * @return mixed|\WP_REST_Response
	 */
	public function get_properties( $request ) {

		$properties = Schemas_Model::get_properties( array(
			'schema_type' => $request->get_param( 'schema_type' ),
			'return_type' => $request->get_param( 'return_type' ),
			'q'           => $request->get_param( 'q' ),
		) );

		if ( is_wp_error( $properties ) ) {
			return $properties;
		}

		$properties = wp_list_pluck( $properties, 'id' );

		return rest_ensure_response( array( 'properties' => $properties ) );
	}


	/**
	 * Builds a HTML form out of properties.
	 *
	 * @since 2.0.0
	 *
	 * @param \WP_REST_Request $request
	 *
	 * @return mixed|\WP_REST_Response
	 */
	public function get_properties_html( $request ) {

		$prop_ids       = (array) $request->get_param( 'properties' );
		$include_table  = $request->get_param( 'include_table' );
		$schema_type    = $request->get_param( 'schema_type' );
		$post_id        = $request->get_param( 'post_id' );
		$snippet_id     = $request->get_param( 'snippet_id' );
		$is_main_schema = $request->get_param( 'is_main_schema' );

		$snippet = Snippets_Model::get_snippet( $snippet_id, (int) $post_id );

		if ( ! $snippet instanceof Rich_Snippet ) {
			$snippet = new Rich_Snippet( [
				'_is_main_snippet' => $is_main_schema,
			] );
			if ( ! empty( $snippet_id ) ) {
				$snippet->id = $snippet_id;
			}
			$snippet->type = Helper_Model::instance()->remove_schema_url( $schema_type );
		}

		if ( $include_table ) {
			$result = Admin_Snippets_Controller::instance()->get_property_table( $snippet, $prop_ids, get_post( $post_id ) );
		} else {
			$result = Admin_Snippets_Controller::instance()->get_property_table_elements( $snippet, $prop_ids, get_post( $post_id ) );
		}

		return rest_ensure_response( $result );
	}


	/**
	 * Verifies a purchase.
	 *
	 * @since 2.0.0
	 *
	 * @param \WP_REST_Request $request
	 *
	 * @return mixed|\WP_REST_Response
	 */
	public function activate_plugin( $request ) {

		$purchase_code = $request->get_param( 'purchase_code' );

		update_option( 'wpb_rs/purchase_code', $purchase_code, false );

		$response = WPBuddy_Model::request(
			'/wpbuddy/rich_snippets_manager/v1/validate',
			array(
				'method'  => 'POST',
				'body'    => array(
					'purchase_code' => $purchase_code,
				),
				'timeout' => 20,
			),
			false,
			true
		);

		if ( is_wp_error( $response ) ) {
			return $response;
		}

		$verified = isset( $response->verified ) && $response->verified;

		update_option( base64_decode( 'd3BiX3JzL3ZlcmlmaWVk' ), $verified, true );
		update_option( 'd3BiX3JzL3ZlcmlmaWVk', $verified, true );

		return rest_ensure_response( array( 'verified' => $verified ) );
	}


	/**
	 * Loads a position value select box (HTML code).
	 *
	 * @since 2.0.0
	 *
	 * @param \WP_REST_Request $request
	 *
	 * @return mixed|\WP_REST_Response
	 */
	public function load_position_value_select( $request ) {

		$rule        = new Position_Rule();
		$rule->param = $request->get_param( 'param' );

		ob_start();
		Admin_Position_Controller::instance()->print_value_select( $rule );

		return rest_ensure_response( array(
			'select_html' => ob_get_clean(),
		) );
	}


	/**
	 * Loads values for a position value select2 box.
	 *
	 * @since 2.0.0
	 *
	 * @param \WP_REST_Request $request
	 *
	 * @return mixed|\WP_REST_Response
	 */
	public function load_position_value_possibilities( $request ) {

		$q     = $request->get_param( 'q' );
		$page  = $request->get_param( 'page' );
		$param = $request->get_param( 'param' );

		global $wpdb;

		$like = sprintf( '%%%s%%', $wpdb->esc_like( $q ) );

		$sql = $wpdb->prepare(
			"SELECT ID, post_title, post_type FROM {$wpdb->posts} WHERE (post_title LIKE '%s' OR ID = %d) AND post_status = 'publish'",
			esc_sql( $like ),
			$q
		);

		if ( 'page_parent' === $param ) {
			$sql .= ' AND post_type = "page"';
		}

		$posts = $wpdb->get_results( $sql );

		if ( ! is_array( $posts ) ) {
			return rest_ensure_response( array(
				'values' => array(),
			) );
		}

		$values = array();

		$i18n = _x(
			'%1$s (%2$s, %3$d)',
			'value possibilities: %1$s is the post title, %2$s is the post type, %3$d is the post ID',
			'rich-snippets-schema'
		);

		foreach ( $posts as $post ) {
			$post_title = empty( $post->post_title ) ? __( '(No post title)', 'rich-snippets-schema' ) : $post->post_title;

			$values[ $post->ID ] = sprintf(
				$i18n,
				esc_attr( $post_title ),
				esc_attr( $post->post_type ),
				$post->ID
			);
		}

		$values = apply_filters( 'wpbuddy/rich_snippets/position/value_possibilities', $values, $q, $page, $param );

		return rest_ensure_response( array(
			'values' => $values,
		) );
	}


	/**
	 * Returns the HTML form to create a rich snippet.
	 *
	 * @since 2.0.0
	 *
	 * @param \WP_REST_Request $request
	 *
	 * @return mixed|\WP_REST_Response
	 */
	public function get_rich_snippets_form_new( $request ) {

		$post_id = $request->get_param( 'post_id' );

		return rest_ensure_response( array(
			'form' => $this->get_rich_snippets_form( $post_id ),
		) );
	}


	/**
	 * Get the HTML code for a snippets form.
	 *
	 * @param int    $post_id
	 * @param string $snippet_id
	 *
	 * @since 2.0.0
	 *
	 * @return string
	 */
	private function get_rich_snippets_form( $post_id, $snippet_id = null ) {

		if ( is_null( $snippet_id ) ) {
			$snippet = new Rich_Snippet();
		} else {
			$snippet = Snippets_Model::get_snippet( $snippet_id, $post_id );
			if ( ! $snippet instanceof Rich_Snippet ) {
				$snippet = new Rich_Snippet();
			}
		}

		ob_start();
		View::admin_posts_snippet( get_post( $post_id ), $snippet );

		return ob_get_clean();
	}


	/**
	 * Returns the HTML forms from existing snippets.
	 *
	 * @since 2.0.0
	 *
	 * @param \WP_REST_Request $request
	 *
	 * @return mixed|\WP_REST_Response
	 */
	public function get_rich_snippets_forms( $request ) {

		$post_id = $request->get_param( 'post_id' );

		$forms = array();

		$rich_snippets = Snippets_Model::get_snippets( $post_id );

		foreach ( $rich_snippets as $snippet_id => $rich_snippet ) {
			$forms[] = $this->get_rich_snippets_form( $post_id, $snippet_id );
		}

		return rest_ensure_response( array(
			'forms' => $forms,
		) );
	}


	/**
	 * Clears internal caches.
	 *
	 * @since 2.0.0
	 *
	 * @param \WP_REST_Request $request
	 *
	 * @return mixed|\WP_REST_Response
	 */
	public function clear_cache( $request ) {

		$deleted = Cache_Model::clear_all_caches();

		return rest_ensure_response( array(
			'cache_cleared' => true,
			'cleared_items' => absint( $deleted ),
		) );
	}


	/**
	 * Deletes snippets from a post.
	 *
	 * @since 2.0.0
	 *
	 * @param \WP_REST_Request $request
	 *
	 * @return mixed|\WP_REST_Response
	 */
	public function delete_snippets( $request ) {

		foreach ( $request->get_param( 'snippet_ids' ) as $snippet_id ) {
			$deleted = Snippets_Model::delete_snippet(
				$snippet_id,
				$request->get_param( 'post_id' )
			);

			if ( is_wp_error( $deleted ) ) {
				return $deleted;
			}

		}

		return rest_ensure_response( array(
			'deleted' => true,
		) );
	}


	/**
	 * Outputs the overwrite-form for a snippet.
	 *
	 * @since 2.2.0
	 *
	 * @param \WP_REST_Request $request
	 *
	 * @return mixed|\WP_REST_Response
	 */
	public function get_overwrite_form( $request ) {

		$snippet_post_id = $request->get_param( 'snippet_post_id' );
		$post_id         = $request->get_param( 'post_id' );

		$rich_snippets = Snippets_Model::get_snippets( $snippet_post_id );

		if ( count( $rich_snippets ) <= 0 ) {
			return new \WP_Error( 'get_overwrite_form', __( 'Could not find snippets on this post.', 'rich-snippets-schema' ) );
		}

		$rich_snippet = array_values( $rich_snippets )[0];

		new Fields_Model();

		ob_start();
		View::admin_snippets_overwrite_form( get_post( $snippet_post_id ), $rich_snippet, $post_id );

		return rest_ensure_response( array(
			'form' => ob_get_clean(),
		) );
	}


	/**
	 * Saves overwrite-data to a post.
	 *
	 * @since 2.2.0
	 *
	 * @param \WP_REST_Request $request
	 *
	 * @return mixed|\WP_REST_Response
	 */
	public function overwrite_snippet_values( $request ) {

		$post_id  = $request->get_param( 'post_id' );
		$new_data = $request->get_param( 'snippets' );

		$old_data = get_post_meta( $post_id, '_wpb_rs_overwrite_data', true );

		if ( ! is_array( $old_data ) ) {
			$old_data = array();
		}

		$saved = update_post_meta( $post_id, '_wpb_rs_overwrite_data', array_replace( $old_data, $new_data ) );

		if ( false !== $saved ) {
			return rest_ensure_response( true );
		}

		return new \WP_Error(
			'wpbuddy/rich_snippets/overwrite',
			_x( 'Could not save this data. Maybe nothing has changed.', 'Thrown error on rest api when overwrite data could not be saved.', 'rich-snippets-schema' )
		);

	}


	/**
	 * Search rich-snippets.io for FAQ results.
	 *
	 * @since 2.3.0
	 *
	 * @param \WP_REST_Request $request
	 *
	 * @return mixed|\WP_REST_Response
	 */
	public function support_faq_search( $request ) {

		$faq_posts = WPBuddy_Model::request(
			'/wp/v2/posts/?categories=10&per_page=20&search=' . urlencode( $request->get_param( 'q' ) )
		);

		if ( is_wp_error( $faq_posts ) ) {
			return $faq_posts;
		}

		ob_start();

		if ( count( $faq_posts ) <= 0 ) {
			printf( '<li>%s</li>', _x( 'Sorry, nothing matched your search query.', 'No FAQ entries found.', 'rich-snippets-schema' ) );
		} else {

			foreach ( $faq_posts as $faq_post ) {
				printf(
					'<li><a href="%s" target="_blank">%s</a><p class="description">%s</p></li>',
					esc_url( $faq_post->link ),
					strip_tags( $faq_post->title->rendered ),
					wp_trim_words( strip_tags( $faq_post->excerpt->rendered ) )
				);
			}

		}

		return rest_ensure_response( [ 'html' => ob_get_clean() ] );
	}


	/**
	 * Vote for a feature.
	 *
	 * @since 2.3.0
	 *
	 * @param \WP_REST_Request $request
	 *
	 * @return mixed|\WP_REST_Response
	 */
	public function support_feature_vote( $request ) {

		$response = WPBuddy_Model::request(
			'/wpbuddy/rich_snippets_manager/v1/support/feature/vote',
			[
				'method' => 'POST',
				'body'   => $request->get_params(),
			],
			false,
			true
		);

		if ( is_wp_error( $response ) ) {
			return $response;
		}

		return rest_ensure_response( [ 'success' => true ] );
	}


	/**
	 * Add a new feature.
	 *
	 * @since 2.3.0
	 *
	 * @param \WP_REST_Request $request
	 *
	 * @return mixed|\WP_REST_Response
	 */
	public function support_feature_add( $request ) {

		$response = WPBuddy_Model::request(
			'/wp/v2/comments',
			[
				'method' => 'POST',
				'body'   => [
					'content' => $request->get_param( 'content' ),
					'post'    => defined( 'WPB_RS_REMOTE' ) ? 1 : 443,
				],
			],
			false,
			true
		);

		if ( is_wp_error( $response ) ) {
			return $response;
		}

		return rest_ensure_response( [ 'success' => true ] );
	}


	/**
	 * Deactivates a license.
	 *
	 * @since 2.5.0
	 *
	 * @param \WP_REST_Request $request
	 *
	 * @return \WP_REST_Response
	 */
	public function deactivate_license( $request ) {

		$response = WPBuddy_Model::request(
			'/wpbuddy/rich_snippets_manager/v1/deactivate-license',
			[ 'method' => 'POST' ],
			false,
			true
		);

		if ( is_wp_error( $response ) ) {
			return $response;
		}

		$deactivated = isset( $response->deactivated ) ? $response->deactivated : false;

		$redirect_url = '';

		if ( $deactivated ) {
			if ( ! function_exists( '\deactivate_plugins' ) && is_file( ABSPATH . 'wp-admin/includes/plugin.php' ) ) {
				include ABSPATH . 'wp-admin/includes/plugin.php';
			}

			deactivate_plugins( rich_snippets()->get_plugin_file() );

			$redirect_url = admin_url( 'index.php' );
		}

		return rest_ensure_response( [
			'deactivated'  => $deactivated,
			'redirect_url' => $redirect_url
		] );
	}
}
