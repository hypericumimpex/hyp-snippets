<?php

namespace wpbuddy\rich_snippets;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly


/**
 * Class Posttypes.
 *
 * Creates posttypes.
 *
 * @package wpbuddy\rich_snippets
 *
 * @since   2.0.0
 */
final class Posttypes_Model {

	public static function create_post_types() {

		if ( ! Helper_Model::instance()->magic() ) {
			return;
		}

		$gl_args = array(
			'label'               => _x( 'Global Structured Data', 'Global post type label', 'rich-snippets-schema' ),
			'labels'              => array(
				'singular_name' => _x( 'Global Structured Data', 'Global post type singular name', 'rich-snippets-schema' ),
				'add_new'       => __( 'Add new global snippet', 'rich-snippets-schema' ),
				'add_new_item'  => __( 'Add new global snippet', 'rich-snippets-schema' ),
				'new_item'      => __( 'Add new global snippet', 'rich-snippets-schema' ),
				'edit_item'     => __( 'Edit global snippet', 'rich-snippets-schema' ),
				'menu_name'     => _x( 'Global Snippets', 'Global post type menu label', 'rich-snippets-schema' ),
			),
			'public'              => false,
			'exclude_from_search' => true,
			'publicly_queryable'  => false,
			'show_in_nav_menus'   => false,
			'show_in_admin_bar'   => false,
			'supports'            => array( 'title' ),
			'show_ui'             => true,
			'show_in_rest'        => true,
			'menu_icon'           => 'data:image/svg+xml;base64,' . base64_encode( '<svg xmlns="http://www.w3.org/2000/svg" width="1041" height="1013.031" viewBox="0 0 1041 1013.031"><path fill="#fff" d="M332.672,244.708q-97.679,78.74-97.667,206.327,0,127.611,80.175,184.456,80.152,56.867,236.151,71.448v35a907.63,907.63,0,0,1-175.656-16.769Q290.4,708.42,249.582,687.984V1004.4l33.528,13.12q33.516,13.125,125.364,26.25t209.912,13.12q221.564,0,310.5-71.445,88.9-71.427,88.919-191.017,0-119.544-67.053-164.77-67.067-45.18-247.813-62.7v-35q196.793,0,295.918,51.035V218.462l-33.528-13.124q-34.984-13.124-127.551-26.246-92.587-13.124-207.725-13.124Q430.317,165.968,332.672,244.708Z" transform="translate(-235 -165.969)"></path><rect fill="#fff" x="16" y="973.031" width="1025" height="40"></rect></svg>' ),
		);

		register_post_type( 'wpb-rs-global', apply_filters( 'wpbuddy/rich_snippets/posttype/global/args', $gl_args ) );
	}

}
