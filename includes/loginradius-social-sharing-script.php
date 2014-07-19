<?php

/**
 * Adding LoginRadius Plugin Sharing script.
 */
function login_radius_sharing_get_sharing_script() {
    global $loginRadiusSettings;

    $sharingScript = '<script type="text/javascript">var islrsharing = true; var islrsocialcounter = true; var hybridsharing = true;</script> <script type="text/javascript" src="//share.loginradius.com/Content/js/LoginRadius.js" id="lrsharescript"></script>';
    $sharingScript .= '<script type="text/javascript">';

    if ( $loginRadiusSettings['horizontal_shareEnable'] == '1' ) {
        // check horizontal sharing enabled
        $sharingScript .= login_radius_sharing_get_sharing_script_horizontal( $loginRadiusSettings );
    }
    if ( $loginRadiusSettings['vertical_shareEnable'] == '1' ) {
        // check vertical sharing enabled
        $sharingScript .= login_radius_sharing_get_sharing_script_vertical( $loginRadiusSettings );
    }

    $sharingScript .= '</script>';
    echo $sharingScript;
}

/**
 * Function returns front script for horizontal sharing.
 */
function login_radius_sharing_get_sharing_script_horizontal() {
    global $loginRadiusSettings;
    $size = '';
    $interface = '';
    $sharingScript = '';
    $horizontalThemvalue = isset( $loginRadiusSettings['LoginRadius_sharingTheme'] ) ? $loginRadiusSettings['LoginRadius_sharingTheme'] : '';

    switch ( $horizontalThemvalue ) {
        case '32':
            $size = '32';
            $interface = 'horizontal';
            break;

        case '16':
            $size = '16';
            $interface = 'horizontal';
            break;

        case 'single_large':
            $size = '32';
            $interface = 'simpleimage';
            break;

        case 'single_small':
            $size = '16';
            $interface = 'simpleimage';
            break;

        case 'counter_vertical':
            $ishorizontal = 'true';
            $interface = 'simple';
            $countertype = 'vertical';
            break;

        case 'counter_horizontal':
            $ishorizontal = 'true';
            $interface = 'simple';
            $countertype = 'horizontal';
            break;

        default:
            $size = '32';
            $interface = 'horizontal';
            break;
    }
    if ( ! empty( $ishorizontal ) ) {
        $providers = (isset( $loginRadiusSettings['horizontal_counter_providers'] ) ) && is_array( $loginRadiusSettings['horizontal_counter_providers'] ) ? implode( '","', $loginRadiusSettings['horizontal_counter_providers'] ) : 'Facebook Like","Google+ +1","Pinterest Pin it","LinkedIn Share","Hybridshare';
        // prepare counter script
        $sharingScript .= 'LoginRadius.util.ready( function() { $SC.Providers.Selected = ["' . $providers . '"]; $S = $SC.Interface.' . $interface . '; $S.isHorizontal = ' . $ishorizontal . '; $S.countertype = \'' . $countertype . '\'; $u = LoginRadius.user_settings; $u.isMobileFriendly = true; $S.show( "loginRadiusHorizontalSharing" ); } );';
    } else {
        $providers = isset( $loginRadiusSettings['horizontal_rearrange_providers'] ) && count( $loginRadiusSettings['horizontal_rearrange_providers'] ) > 0 ? implode( '","', $loginRadiusSettings['horizontal_rearrange_providers'] ) : 'Facebook","Twitter","GooglePlus","LinkedIn","Pinterest","Print","Email';
        // prepare sharing script
        $sharingScript .= 'LoginRadius.util.ready( function() { $i = $SS.Interface.' . $interface . '; $SS.Providers.Top = ["' . $providers . '"]; $u = LoginRadius.user_settings;';
        $sharingScript .= '$u.apikey= \'' . trim( $loginRadiusSettings['LoginRadius_apikey'] ) . '\';';
        $sharingScript .= '$i.size = ' . $size . '; $u.sharecounttype="url"; $u.isMobileFriendly=true; $i.show( "loginRadiusHorizontalSharing" ); } );';
    }
    return $sharingScript;
}
/**
 * Function returns front script for vertical sharing.
 */
function login_radius_sharing_get_sharing_script_vertical() {
    global $loginRadiusSettings;
    $sharingScript = '';
    $verticalThemvalue = $loginRadiusSettings['verticalSharing_theme'];
    switch ( $verticalThemvalue ) {
        case '32':
            $size = '32';
            $interface = 'Simplefloat';
            $sharingVariable = 'i';
            break;

        case '16':
            $size = '16';
            $interface = 'Simplefloat';
            $sharingVariable = 'i';
            break;

        case 'counter_vertical':
            $sharingVariable = 'S';
            $ishorizontal = 'false';
            $interface = 'simple';
            $type = 'vertical';
            break;

        case 'counter_horizontal':
            $sharingVariable = 'S';
            $ishorizontal = 'false';
            $interface = 'simple';
            $type = 'horizontal';
            break;

        default:
            $size = '32';
            $interface = 'Simplefloat';
            $sharingVariable = 'i';
            break;
    }

    $verticalPosition = isset( $loginRadiusSettings['sharing_verticalPosition'] ) ? $loginRadiusSettings['sharing_verticalPosition'] : '';
    switch ( $verticalPosition ) {
        case "top_left":
            $position1 = 'top';
            $position2 = 'left';
            break;

        case "top_right":
            $position1 = 'top';
            $position2 = 'right';
            break;

        case "bottom_left":
            $position1 = 'bottom';
            $position2 = 'left';
            break;

        case "bottom_right":
            $position1 = 'bottom';
            $position2 = 'right';
            break;

        default:
            $position1 = 'top';
            $position2 = 'left';
            break;
    }

    $offset = '$' . $sharingVariable . '.' . $position1 . ' = \'0px\'; $' . $sharingVariable . '.' . $position2 . ' = \'0px\';';

    if ( empty( $size ) ) {
        $providers = isset( $loginRadiusSettings['vertical_counter_providers'] ) && is_array( $loginRadiusSettings['vertical_counter_providers'] ) ? implode( '","', $loginRadiusSettings['vertical_counter_providers'] ) : 'Facebook Like","Google+ +1","Pinterest Pin it","LinkedIn Share","Hybridshare';
        $sharingScript .= 'LoginRadius.util.ready( function() { $SC.Providers.Selected = ["' . $providers . '"]; $S = $SC.Interface.' . $interface . '; $S.isHorizontal = ' . $ishorizontal . '; $S.countertype = \'' . $type . '\'; ' . $offset . ' $u = LoginRadius.user_settings; $u.isMobileFriendly = true; $S.show( "loginRadiusVerticalSharing" ); } );';
    } else {
        $providers = isset( $loginRadiusSettings['vertical_rearrange_providers'] ) && count( $loginRadiusSettings['vertical_rearrange_providers'] ) > 0 ? implode( '","', $loginRadiusSettings['vertical_rearrange_providers'] ) : 'Facebook","Twitter","GooglePlus","LinkedIn","Pinterest","Print","Email';
        // prepare sharing script
        $sharingScript .= 'LoginRadius.util.ready( function() { $i = $SS.Interface.' . $interface . '; $SS.Providers.Top = ["' . $providers . '"]; $u = LoginRadius.user_settings;';
        $sharingScript .= '$u.apikey= \'' . trim( $loginRadiusSettings['LoginRadius_apikey'] ) . '\';';
        $sharingScript .= '$i.size = ' . $size . '; ' . $offset . ' $u.isMobileFriendly=true; $i.show( "loginRadiusVerticalSharing" ); } );';
    }
    return $sharingScript;
}
