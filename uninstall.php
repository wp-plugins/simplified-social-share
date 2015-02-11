<?php

//if uninstall not called from WordPress, exit
if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit();
}

if( !is_multisite()) {
    $loginradius_api_settings = get_option( 'LoginRadius_API_settings' );
    if ( $loginradius_api_settings['delete_options'] == '1' ) {
        delete_loginradius_options();
    }
} else {
    global $wpdb;
    $blog_ids = $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs" );
    $original_blog_id = get_current_blog_id();
    foreach ( $blog_ids as $blog_id ) {
        switch_to_blog( $blog_id );
        $loginradius_api_settings = get_option( 'LoginRadius_API_settings' );
        if ( $loginradius_api_settings['delete_options'] == '1' ) {
            delete_loginradius_options();
        }
    }
    switch_to_blog( $original_blog_id );
}   

function delete_loginradius_options() {
    global $wpdb;
    delete_option( 'LoginRadius_API_settings' );
    delete_option( 'LoginRadius_settings' );
    delete_option( 'LoginRadius_share_settings' );
    delete_option( 'loginradius_db_version' );
    delete_option( 'loginradius_version' );

}