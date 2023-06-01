<?php
// If uninstall not called from WordPress, then exit.
function kleverlist_cleanup() {
	global $wpdb, $wp_version;
	// Delete options.
	$wpdb->query( "DELETE FROM $wpdb->options WHERE option_name LIKE 'kleverlist\_%' OR option_name LIKE 'mapping_user\_%';" );

	// Delete postmeta.
	$wpdb->query( "DELETE FROM {$wpdb->postmeta} WHERE meta_key LIKE '_special\_%' OR meta_key LIKE '_unsubscribe\_%';" );
}
add_action('fs_uninstall_cleanup', 'kleverlist_cleanup');
