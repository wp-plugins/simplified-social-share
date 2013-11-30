<?php
//if uninstall not called from WordPress, exit
if( !defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit();
}
$loginRadiusSettings = get_option('LoginRadius_sharing_settings');
if(!isset($loginRadiusSettings['delete_options']) || $loginRadiusSettings['delete_options'] == 1){
	$loginRadiusOption = 'LoginRadius_sharing_settings';
	// For Single site
	if( !is_multisite() ){
		delete_option( $loginRadiusOption );
	}else{
		// For Multisite
		global $wpdb;
		$login_radius_blog_ids = $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs" );
		$original_blog_id = get_current_blog_id();
		foreach ( $blog_ids as $blog_id ){
			switch_to_blog( $blog_id );
			delete_site_option($loginRadiusOption);
		}
		switch_to_blog( $original_blog_id );
	}
}