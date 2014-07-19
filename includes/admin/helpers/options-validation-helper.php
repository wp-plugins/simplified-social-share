<?php

/**
 * This file contains helper functions for admin
 */


/**
 * Encoding LoginRadius Plugin settings
 */
function login_radius_encoding_settings_string( $loginRadiusSettings ) {
    $string = $loginRadiusSettings['scripts_in_footer'] . '|' . ($loginRadiusSettings['horizontal_shareEnable']) . '|';
    $horizontalSharingTheme = $loginRadiusSettings['horizontalSharing_theme'];

    switch ( $horizontalSharingTheme ) {
        // switch will test for various values for horizontalSharing_theme
        case "32":
            $string .= '0|';
            break;
        case "16":
            $string .= '1|';
            break;
        case "single_large":
            $string .= '2|';
            break;
        case "single_small2":
            $string .= '3|';
            break;
        case "counter_vertical":
            $string .= '4|';
            break;
        case "counter_horizontal":
            $string .= '5|';
            break;
        default:
    }
    //generating string for horizontal sharing providers, counter providers and reaarange providers
    $string .= login_radius_get_horizontal_networks_providers( $loginRadiusSettings );

    $string .= isset( $loginRadiusSettings['horizontal_shareTop'] ) ? '|1|' : '|0|';
    $string .= isset( $loginRadiusSettings['horizontal_shareBottom'] ) ? '|1|' : '|0|';
    $string .= isset( $loginRadiusSettings['horizontal_sharehome'] ) ? '|1|' : '|0|';
    $string .= isset( $loginRadiusSettings['horizontal_sharepost'] ) ? '|1|' : '|0|';
    $string .= isset( $loginRadiusSettings['horizontal_sharepage'] ) ? '|1|' : '|0|';
    $string .= isset( $loginRadiusSettings['horizontal_shareexcerpt'] ) ? '|1|' : '|0|';


    //Starting Vertical Sharing string encodeing
    $string .= $loginRadiusSettings['vertical_shareEnable'] . '|';
    $verticalSharingTheme = $loginRadiusSettings['verticalSharing_theme'];
    switch ( $verticalSharingTheme ) { // switch will test for various values against variable $favcolor
        case "32":
            $string .= '0|';
            break;
        case "16":
            $string .= '1|';
            break;
        case "counter_vertical":
            $string .= '2|';
            break;
        case "counter_horizontal":
            $string .= '3|';
            break;
        default:
    }
//generating string for vertical sharing providers, counter providers and reaarange providers
    $string .= login_radius_get_vertical_networks_providers( $loginRadiusSettings );

    switch ( $loginRadiusSettings['sharing_verticalPosition'] ) {
        case "top_left":
            $string .= '|0|';
            break;

        case "top_right":
            $string .= '|1|';
            break;
        case "bottom_left":
            $string .= '|2|';
            break;
        case "bottom_right":
            $string .= '|3|';
            break;
    }
    $string .= isset( $loginRadiusSettings['vertical_sharehome'] ) ? '|1|' : '|0|';
    $string .= isset( $loginRadiusSettings['vertical_sharepost'] ) ? '|1|' : '|0|';
    $string .= isset( $loginRadiusSettings['vertical_sharepage'] ) ? '|1|' : '|0|';
    $string .= $loginRadiusSettings['delete_options'] . '~';
    return $string;
}

/**
 * Changing array to comma seperated string
 */
function login_radius_imploading_arrays( $array ) {
    $string = '|["' . implode( '","', $array ) . '"]';
    return $string;
}
/**
 * Get comma seperated horizontal network providers
 */
function login_radius_get_horizontal_networks_providers( $loginRadiusSettings ) {
    $string = '';

    if ( isset( $loginRadiusSettings['horizontal_sharing_providers'] ) ) {
        $string .= login_radius_imploading_arrays( $loginRadiusSettings['horizontal_sharing_providers'] );
    }

    if ( isset( $loginRadiusSettings['horizontal_counter_providers'] ) ) {
        $string .= login_radius_imploading_arrays( $loginRadiusSettings['horizontal_counter_providers'] );
    }

    if ( isset( $loginRadiusSettings['horizontal_rearrange_providers'] ) ) {
        $string .= login_radius_imploading_arrays( $loginRadiusSettings['horizontal_rearrange_providers'] );
    }
    return $string;
}

function login_radius_get_vertical_networks_providers( $loginRadiusSettings ) {
    $string = '';

    if ( isset( $loginRadiusSettings['vertical_sharing_providers'] ) ) {
        $string .= login_radius_imploading_arrays( $loginRadiusSettings['vertical_sharing_providers'] );
    }

    if ( isset( $loginRadiusSettings['vertical_counter_providers'] ) ) {
        $string .= login_radius_imploading_arrays( $loginRadiusSettings['vertical_counter_providers'] );
    }

    if ( isset( $loginRadiusSettings['vertical_rearrange_providers'] ) ) {
        $string .= login_radius_imploading_arrays( $loginRadiusSettings['vertical_rearrange_providers'] );
    }
    return $string;
}
