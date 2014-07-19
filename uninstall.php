<?php

//if uninstall not called from WordPress, abort
defined( 'WP_UNINSTALL_PLUGIN' ) or die();
//get LoginRadius options
$loginRadiusSettings = get_option( 'LoginRadius_sharing_settings' );

if ( isset( $loginRadiusSettings['delete_options'] ) || $loginRadiusSettings['delete_options'] == 1 ) {
    //If Detete options from LoginRadius plugin settings is selected
    if ( is_multisite() ) {
        //If it is Multisite, remove LoginRadius plugin options from all blogs
        global $wpdb;
        $blogs = $wpdb->get_results( "SELECT blog_id FROM {$wpdb->blogs}", ARRAY_A );
        if ( $blogs ) {
            foreach ( $blogs as $blog ) {
                switch_to_blog( $blog['blog_id'] );
                delete_option( 'LoginRadius_sharing_settings' );
            }
            restore_current_blog();
        }
    } else {
        // Delete options from Single Site
        delete_option( 'LoginRadius_sharing_settings' );
    }
}