<?php

//define URL for Loginradius API
define( 'LOGINRADIUS_VALIDATION_API_URL', 'https://api.loginradius.com/api/v2/app/validatekey' );

/**
 * Callback function for validating LoginRadius plugin settings
 */
function login_radius_validate_settings( $loginRadiusSettings ) {
    if ( empty( $loginRadiusSettings['LoginRadius_apikey'] ) ) {
         //if empty API Key, then return settings
        return $loginRadiusSettings;
    } else {
        // Encodeing plugin settings string
        require_once( 'helpers/options-validation-helper.php' );
        $settingsString = login_radius_encoding_settings_string( $loginRadiusSettings );
        // get resonse from LoginRadius api
        if ( login_readius_response_from_api( $settingsString, $loginRadiusSettings['LoginRadius_apikey'] ) ) {
            //if api is valid, then return settings to be saved.
            return $loginRadiusSettings;
        } else {
            //if API Key is not valid, Display admin notice, and save previous settings
            $loginRadiusSettingsold = get_option( 'LoginRadius_sharing_settings' );
            add_settings_error( 'LoginRadius_sharing_settings', esc_attr( 'settings_updated' ), __( 'Settings not saved. Please insert valid API Key...', 'LoginRadius' ), 'error' );
            add_action( 'admin_notices', 'login_radius_print_admin_errors' );
            return $loginRadiusSettingsold;
        }
    }
}

/**
 * Display error notice to admin.
 */
function login_radius_print_admin_errors() {
    settings_errors( 'LoginRadius_sharing_settings' );
}

/**
 * Function to get response from LoginRadius api
 */
function login_readius_response_from_api( $string, $apiKey ) {
    $url = LOGINRADIUS_VALIDATION_API_URL . '?apikey=' . $apiKey;
    $response = wp_remote_post(
            $url, array(
        'method' => 'POST',
        'headers' => array('Content-Type' => 'application/x-www-form-urlencoded'),
        'body' => array('addon' => 'WordPress', 'version' => '2.1', 'agentstring' => $_SERVER['HTTP_USER_AGENT'], 'clientip' => $_SERVER['REMOTE_ADDR'], 'configuration' => $string),
        'cookies' => array(),
            )
    );

    if ( ! is_wp_error( $response ) ) {
        //if response retrieved successfully, return status
        return json_decode( $response['body'] )->Status;
    } else {
        //Error occurred while retrieving response from Loginradius API.
        return false;
    }
}
