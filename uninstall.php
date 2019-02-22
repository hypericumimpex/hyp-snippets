<?php
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit();
}

if ( ! WP_UNINSTALL_PLUGIN ) {
	exit();
}

/* from the old deprecated version */
call_user_func( function () {

	$file = __DIR__ . '/uninstall-deprecated.php';
	if ( ! is_file( $file ) ) {
		return;
	}

	require_once $file;
} );

/*
 * From the new version
 */
call_user_func( function () {

	global $wpdb;

	# delete user meta that is no longer needed
	$wpdb->query( "DELETE FROM {$wpdb->usermeta} WHERE meta_key LIKE '%wpb-rs-global%' " );

	# delete options
	$wpdb->query( "DELETE FROM {$wpdb->options} WHERE option_name LIKE 'wpb_rs%' " );

	# delete transients and transient timeouts
	$wpdb->query( "DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient%wpb_rs%' " );

	/**
	 * Delete posts and post meta
	 */
	$global_snippet_ids = $wpdb->get_results( "SELECT ID FROM {$wpdb->posts} WHERE post_type = 'wpb-rs-global'" );
	if ( $global_snippet_ids && is_array( $global_snippet_ids ) && count( $global_snippet_ids ) > 0 ) {
		$global_snippet_ids = wp_list_pluck( $global_snippet_ids, 'ID' );

		foreach ( $global_snippet_ids as $global_snippet_id ) {
			wp_delete_post( $global_snippet_id, true );
		}
	}

	# Delete rich snippets from other post meta
	$wpdb->query( "DELETE FROM {$wpdb->postmeta} WHERE meta_key LIKE '_wpb_rs%s' " );

	# delete the magic variable
	delete_option( 'd3BiX3JzL3ZlcmlmaWVk' );

} );
