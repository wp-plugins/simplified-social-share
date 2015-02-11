<?php
/**
 * Plugin Name: Simplified Social Sharing
 * Plugin URI: http://www.loginradius.com
 * Description: Simplified Social Sharing
 * Version: 2.7.1
 * Author: LoginRadius Team
 * Author URI: http://www.loginradius.com
 * License: GPL2+
 */

// If this file is called directly, abort.
defined( 'ABSPATH' ) or die();

define( 'LR_ROOT_DIR', plugin_dir_path( __FILE__ ) );
define( 'LR_ROOT_URL', plugin_dir_url( __FILE__ ) );

// Core Module
if ( file_exists( plugin_dir_path( __FILE__ ) . 'lr-core/lr-core.php' ) ){
	require_once( 'lr-core/lr-core.php' );
}

// Social Sharing Module
if ( file_exists( plugin_dir_path( __FILE__ ) . 'lr-social-sharing/lr-social-sharing.php' ) ){
	require_once( 'lr-social-sharing/lr-social-sharing.php' );
}

/**
 * Add a settings link to the Plugins page,
 * so people can go straight from the plugin page to the settings page.
 */
function loginradius_login_setting_links( $links, $file ) {
	static $thisPlugin = '';
	if ( empty( $thisPlugin ) ) {
		$thisPlugin = plugin_basename( __FILE__ );
	}
	if ( $file == $thisPlugin ) {
		
		if( ! class_exists('LR_Social_Login') || ! class_exists('LR_Raas') ) {
			$settingsLink = '<a href="admin.php?page=loginradius_share">' . __( 'Settings' ) . '</a>';
		} else {
			$settingsLink = '<a href="admin.php?page=LoginRadius">' . __( 'Settings' ) . '</a>';
		}
		
		array_unshift( $links, $settingsLink );
	}
	return $links;
}
add_filter( 'plugin_action_links', 'loginradius_login_setting_links', 10, 2 );