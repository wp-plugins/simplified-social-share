<?php

/**
 * Shortcode for social sharing.
 */
add_shortcode( 'LoginRadius_Share', 'login_radius_sharing_sharing_shortcode' );

/**
 * This function will be used to insert content while shortcode is found.
 */
function login_radius_sharing_sharing_shortcode( $params ) {
    //Default parameters for shortcode.
    $default = array(
        'style' => '',
        'type' => 'horizontal',
    );
    //Extracting parameters.
    extract( shortcode_atts( $default, $params ) );
    $returnedCode = $type == 'vertical' ? '<div class="loginRadiusVerticalSharing" ' : '<div class="loginRadiusHorizontalSharing" ';
    if ( $style != '' ) {
        // if style parameter is passed in shortcode, then add that style also.
        $returnedCode .= 'style="' . $style . '"';
    }
    $returnedCode .= '></div>';
    return $returnedCode;
}
