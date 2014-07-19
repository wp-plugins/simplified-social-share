<?php

/**
 * This file is responsible for creating LoginRadius plugin admin settings page.
 */


/**
 * This is the callback function which will be called
 * while navagating to LoginRadius plugin settings page
 */
function login_radius_sharing_option_page() {
    //Including all necessary files
    require 'views/loginradius-admin-header.php';
    require 'views/loginradius-admin-ui-script.php';
    require 'views/loginradius-admin-login-registration-form.php';
    require 'views/loginradius-settings-view.php' ;

    global $loginRadiusSettings, $loginRadiusIsBuddyPressActive;

    // rendering LoginRadius plugin admin header
    login_radius_render_header();
    // Loading LoginRadius Plugin options script
    login_radius_render_admin_ui_script();
    if ( ! isset( $loginRadiusSettings['LoginRadius_apikey'] ) || $loginRadiusSettings['LoginRadius_apikey'] == '' ) {
        //Display registration form if API key is not set
        login_radius_render_aia();
    } else {
        ?>
        <!--Start displaying LoginRadius plugin settings page -->
        <div class="wrapper">
            <form action="options.php" method="post">
                <?php
                settings_fields( 'LoginRadius_sharing_setting_options' );
                settings_errors();
                login_radius_render_settings();
                ?>
            </form>
        </div>
        <?php
    }
}

/**
 * function returns json array of sharing providers on the basis of theme( provided as argument).
 */
function login_radius_get_sharing_providers_josn_arrays( $themeType ) {
    global $loginRadiusSettings;

    switch ( $themeType ) {
        case 'vertical':
            if ( isset( $loginRadiusSettings['vertical_rearrange_providers'] ) && is_array( $loginRadiusSettings['vertical_rearrange_providers'] ) && count( $loginRadiusSettings['vertical_rearrange_providers'] ) > 0 ) {
                return json_encode( $loginRadiusSettings['vertical_rearrange_providers'] );
            } else {
                return login_radius_get_default_sharing_providers_josn_array();
            }
            break;

        case 'horizontal':
            if ( isset( $loginRadiusSettings['horizontal_rearrange_providers'] ) && is_array( $loginRadiusSettings['horizontal_rearrange_providers'] ) && count( $loginRadiusSettings['horizontal_rearrange_providers'] ) > 0 ) {
                return json_encode( $loginRadiusSettings['horizontal_rearrange_providers'] );
            } else {
                return login_radius_get_default_sharing_providers_josn_array();
            }
            break;
    }
}

/**
 * function returns json array of counter providers on the basis of theme( provided as argument).
 */
function login_radius_get_counters_providers_json_array( $themeType ) {
    global $loginRadiusSettings;

    switch ( $themeType ) {
        case 'horizontal':
            if ( isset( $loginRadiusSettings['horizontal_counter_providers'] ) && is_array( $loginRadiusSettings['horizontal_counter_providers'] ) && count( $loginRadiusSettings['vertical_rearrange_providers'] ) > 0 ) {
                return json_encode( $loginRadiusSettings['horizontal_counter_providers'] );
            } else {
                return login_radius_get_default_counters_providers_josn_array();
            }
            break;

        case 'vertical':
            if ( isset( $loginRadiusSettings['vertical_counter_providers'] ) && is_array( $loginRadiusSettings['vertical_counter_providers'] ) && count( $loginRadiusSettings['vertical_rearrange_providers'] ) > 0 ) {
                return json_encode( $loginRadiusSettings['vertical_counter_providers'] );
            } else {
                return login_radius_get_default_counters_providers_josn_array();
            }
            break;
    }
}
/**
 * function returns default json array of sharing providers.
 */
function login_radius_get_default_sharing_providers_josn_array() {
    return '["Facebook", "Twitter", "Googleplus", "Linkedin", "Pinterest", "Email", "Print"]';
}

/**
 * function returns default json array of counter providers.
 */
function login_radius_get_default_counters_providers_josn_array() {
    return '["Facebook Like", "Google+ +1", "Pinterest Pin it", "LinkedIn Share", "Hybridshare"]';
}
