<?php

/**
 * Plugin Name:Simplified Social Share
 * Plugin URI: http://www.LoginRadius.com
 * Description: Add Social Sharing to your WordPress website.
 * Version: 2.1
 * Author: LoginRadius Team
 * Author URI: http://www.LoginRadius.com
 * License: GPL2+
 */

//If this file is called directly, abort.
defined( 'ABSPATH' ) or die();

//Define LoginRadius plugin constants.
define( 'LOGINRADIUS__MINIMUM_WP_VERSION', '2.9' );
define( 'LOGINRADIUS__VERSION', '2.1' );
define( 'LOGINRADIUS__PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'LOGINRADIUS__PLUGIN_URL', plugin_dir_url( __FILE__ ) );

register_activation_hook( __FILE__, 'login_radius_sharing_activation' );
add_filter( 'init', 'login_radius_sharing_init_locale' );
add_filter( 'plugin_action_links', 'login_radius_sharing_setting_links', 10, 2 );

//Get LoginRadius plugin settings
$loginRadiusSettings = get_option( 'LoginRadius_sharing_settings' );

//variable to check if buddypress is available.
$loginRadiusIsBuddyPressActive = false;

//Loading LoginRadius plugin main files.
require_once( 'includes/admin/loginradius-admin-actions.php' );
require_once( 'includes/admin/loginradius-admin-settings.php' );
require_once( 'includes/widgets/loginradius-horizontal-share-widget.php' );
require_once( 'includes/widgets/loginradius-vertical-share-widget.php' );
require_once( 'includes/loginradius-social-share.php' );
require_once( 'includes/loginradius-social-sharing-script.php' );
require_once( 'includes/loginradius-social-share-shortcode.php' );

/**
 * Function to register activation hook
 */
function login_radius_sharing_activation() {
    require_once LOGINRADIUS__PLUGIN_DIR . 'install.php';
    $install = new Login_Radius_Install();
    $install->init();
}

/**
 * Load the plugin's translated files
 */
function login_radius_sharing_init_locale() {
    load_plugin_textdomain( 'LoginRadius', false, basename( dirname( __FILE__ ) ) . '/languages' );
}

/**
 * Add a settings link to the Plugins page,
 * so people can go straight from the plugin page to the settings page.
 */
function login_radius_sharing_setting_links( $links, $file ) {
    static $thisPlugin = '';
    if ( empty( $thisPlugin ) ) {
        $thisPlugin = plugin_basename( __FILE__ );
    }
    if ( $file == $thisPlugin ) {
        $settingsLink = '<a href="admin.php?page=LoginRadiusSharing">' . __( 'Settings' ) . '</a>';
        array_unshift( $links, $settingsLink );
    }
    return $links;
}
