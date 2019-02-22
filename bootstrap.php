<?php
namespace wpbuddy\rich_snippets;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly


spl_autoload_register( '\wpbuddy\rich_snippets\autoloader', true );

/**
 * The autoloader function.
 *
 * @param string $class_name
 *
 * @return bool
 * @since 1.0.0
 * @since 2.0.0 renamed
 */
function autoloader( $class_name ) {

	if ( 0 !== stripos( $class_name, 'wpbuddy\\rich_snippets\\' ) ) {
		# not our files
		return false;
	}

	# make everything lowercase
	$file_name = strtolower( $class_name );

	# remove "wpbuddy\rich_snippets\"
	$file_name = str_replace( 'wpbuddy\\rich_snippets\\', '', $file_name );

	# find sub-paths
	$sub_path = strtolower( str_replace( '_', '', strrchr( $file_name, '_' ) ) );

	if ( ! in_array( $sub_path, array( 'model', 'view', 'controller' ) ) ) {
		$sub_path = '';
	}

	if ( ! empty( $sub_path ) ) {
		$file_name = str_replace( '_' . $sub_path, '', $file_name );
		$sub_path .= '/';
	}

	# replace "_" with "-"
	$file_name = str_replace( '_', '-', $file_name );

	# full file path
	$file_path = sprintf( '%s/classes/%s%s.php', __DIR__, $sub_path, $file_name );

	if ( ! is_file( $file_path ) ) {

		# check if this is an object
		$file_path = sprintf( '%s/classes/objects/%s.php', __DIR__, $file_name );

		if ( ! is_file( $file_path ) ) {
			return false;
		}


	}

	require_once $file_path;

	return false;
}


/**
 * Helper function to get an instance of the rich snippets class.
 *
 * @return \wpbuddy\rich_snippets\Rich_Snippets_Plugin
 */
function rich_snippets() {

	return Rich_Snippets_Plugin::instance();
}

rich_snippets()->init();
