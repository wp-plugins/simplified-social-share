<?php

/**
 * File responsible for adding all admin actions and hooks
 */
add_action( 'admin_menu', 'login_radius_sharing_admin_menu' );
add_action( 'admin_init', 'login_radius_sharing_options_init' );
add_action( 'bp_include', 'login_radius_sharing_buddy_press_check' );
add_action( 'wp_ajax_login_radius_sharing_save_site', 'login_radius_sharing_save_site' );
add_action( 'wp_ajax_login_radius_sharing_save_keys', 'login_radius_sharing_save_keys' );

/**
 * replicate Social Login configuration to the subblogs in the multisite network
 */
if ( is_multisite() && is_main_site() ) {
    add_action( 'wpmu_new_blog', 'login_radius_sharing_replicate_settings' );
    add_action( 'update_option_LoginRadius_sharing_settings', 'login_radius_sharing_update_old_blogs' );
}

/**
 * Add LoginRadius menu in the left sidebar in the admin
 */
function login_radius_sharing_admin_menu() {
    $page = add_menu_page( 'LoginRadiusSharing', '<b>LoginRadius</b>', 'manage_options', 'LoginRadiusSharing', 'login_radius_sharing_option_page', LOGINRADIUS__PLUGIN_URL . 'assets/images/favicon.ico' );
    add_action( 'admin_print_scripts-' . $page, 'login_radius_sharing_options_page_scripts' );
    add_action( 'admin_print_styles-' . $page, 'login_radius_sharing_options_page_style' );
}

/**
 * Add js admin page and for enabling preview.
 */
function login_radius_sharing_options_page_scripts() {
    wp_enqueue_script( 'jquery-ui-sortable' );
    $script = ( login_radius_sharing_get_wp_version() >= 3.2 ) ? 'loginradius-options-page32.js' : 'loginradius-options-page29.js';
    $scriptLocation = apply_filters( 'LoginRadius_files_uri', LOGINRADIUS__PLUGIN_URL . 'assets/js/' . $script . '?t=2.1' );
    wp_enqueue_script( 'LoginRadius_sharing_options_page_script', $scriptLocation, array('jquery-ui-tabs', 'thickbox') );
    wp_enqueue_script( 'LoginRadius_sharing_options_page_script2', LOGINRADIUS__PLUGIN_URL . 'assets/js/loginRadiusAdmin.js?t=2.1', array(), false, false );
    wp_enqueue_script( 'LoginRadius_sharing_settings_script', LOGINRADIUS__PLUGIN_URL . 'assets/js/loginRadiusAdminSettings.js?t=2.1', array(), false, false );
}

/**
 * Add option Settings css.
 */
function login_radius_sharing_options_page_style() {
    $styleLocation = apply_filters( 'LoginRadius_files_uri', LOGINRADIUS__PLUGIN_URL . 'assets/css/loginRadiusOptionsPage.css' );
    wp_enqueue_style( 'login_radius_sharing_options_page_style', $styleLocation . '?t=4.0.0' );
    wp_enqueue_style( 'thickbox' );
}

/**
 * Add Login Radius meta box to pages and posts for disabling Sharing on posts and pages.
 */
function login_radius_sharing_options_init() {
    register_setting( 'LoginRadius_sharing_setting_options', 'LoginRadius_sharing_settings', 'login_radius_sharing_validate_options' );
    // show sharing meta fields on pages and posts
    foreach ( array('post', 'page') as $type ) {
        add_meta_box( 'login_radius_meta', 'LoginRadius', 'login_radius_sharing_meta_setup', $type );
    }
    // add a callback function to save any data a user enters in
    add_action( 'save_post', 'login_radius_sharing_save_meta' );
}

/**
 * Check if Buddypress is active
 */
function login_radius_sharing_buddy_press_check() {
    global $loginRadiusIsBuddyPressActive;
    $loginRadiusIsBuddyPressActive = true;
}

/**
 * Save LR Site in the database
 */
function login_radius_sharing_save_site() {
    if ( isset( $_POST['apikey'] ) && trim( $_POST['apikey'] ) != '' ) {
          $loginRadiusSettings = get_option( 'LoginRadius_sharing_settings' );
        $loginRadiusSettings['LoginRadius_apikey'] = trim( $_POST['apikey'] );
        if ( update_option( 'LoginRadius_sharing_settings', $loginRadiusSettings ) ) {
            //if option updated successfully
            die('success');
        }
    }
    die('error');
}

/**
 * Save login radius meta fields.
 */
function login_radius_sharing_save_meta( $postId ) {
    // make sure data came from our meta box
    if ( ! isset( $_POST['login_radius_sharing_meta_nonce'] ) || !wp_verify_nonce( $_POST['login_radius_sharing_meta_nonce'], __FILE__ ) ) {
        return $postId;
    }
    // check user permissions
    if ( $_POST['post_type'] == 'page' ) {
        if ( ! current_user_can( 'edit_page', $postId ) ) {
            return $postId;
        }
    } else {
        if ( ! current_user_can( 'edit_post', $postId ) ) {
            return $postId;
        }
    }
    if ( isset( $_POST['_login_radius_sharing_meta'] ) ) {
        $newData = $_POST['_login_radius_sharing_meta'];
    } else {
        $newData = array('sharing' => 0);
    }
    update_post_meta( $postId, '_login_radius_sharing_meta', $newData );
    return $postId;
}

/**
 * Register LoginRadius plugin settings and its sanitization callback.
 */
function login_radius_sharing_meta_setup() {
    global $post;
    $postType = $post->post_type;
    $lrMeta = get_post_meta( $post->ID, '_login_radius_sharing_meta', true );
    ?>
    <p>
        <label for="login_radius_sharing">
            <input type="checkbox" name="_login_radius_sharing_meta[sharing]" id="login_radius_sharing" value='1' <?php checked( '1', @$lrMeta['sharing'] ); ?> />
            <?php _e( 'Disable Social Sharing on this ' . $postType, 'LoginRadius' ) ?>
        </label>
    </p>
    <?php
    // custom nonce for verification later
    echo '<input type="hidden" name="login_radius_sharing_meta_nonce" value="' . wp_create_nonce( __FILE__ ) . '" />';
}

/**
 * Function to validate plugin options.
 */
function login_radius_sharing_validate_options( $loginRadiusSettings ) {
    require_once( 'loginradius-options-validator.php' );
    return login_radius_validate_settings( $loginRadiusSettings );
}

/**
 * replicate the social share settings in multisite network
 */
function login_radius_sharing_replicate_settings( $blogId ) {
    global $loginRadiusSettings;
    add_blog_option( $blogId, 'LoginRadius_sharing_settings', $loginRadiusSettings );
}

/**
 * update social share options in all the old blogs
 */
function login_radius_sharing_update_old_blogs() {
    $newConfig = get_option( 'LoginRadius_sharing_settings' );
    if ( isset( $newConfig['multisite_config'] ) && $newConfig['multisite_config'] == '1' ) {
        $blogs = get_blog_list( 0, 'all' );
        foreach ( $blogs as $blog ) {
            update_blog_option( $blog['blog_id'], 'LoginRadius_sharing_settings', $newConfig );
        }
    }
}

/**
 * Get wordpress version.
 */
function login_radius_sharing_get_wp_version() {
    return ( float ) substr( get_bloginfo( 'version' ), 0, 3 );
}
