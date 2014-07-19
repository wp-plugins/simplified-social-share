<?php

/**
 * This file is responsible for generating Social Sharing Interface on front pages
 */

//Variables for counting Vertical interface
$loginRadiusSharingVerticalInterfaceContentCount = 0;
$loginRadiusSharingVerticalInterfaceExcerptCount = 0;

// include file for getting Login radius Script
require_once( 'loginradius-social-sharing-script.php' );

global $loginRadiusSettings;

if ( isset( $loginRadiusSettings['LoginRadius_apikey'] ) && ! empty( $loginRadiusSettings['LoginRadius_apikey'] ) ) {
    //Checking if API key is not empty
    if ( login_radius_sharing_scripts_in_footer_enabled() ) {
        //Adding Sharing script in footer
        add_action( 'wp_footer', 'login_radius_sharing_get_sharing_script' );
    } else {
        //By default adding script in header
        add_action( 'wp_enqueue_scripts', 'login_radius_sharing_get_sharing_script' );
    }
    add_filter( 'the_content', 'login_radius_sharing_content' );
    add_filter( 'get_the_excerpt', 'login_radius_sharing_content' );
}
/**
 * Function for adding Social Sharing Interface.
 */
function login_radius_sharing_content( $content ) {

    global $post, $loginRadiusSettings;
    $lrMeta = get_post_meta( $post->ID, '_login_radius_sharing_meta', true );

    // if sharing disabled on this page/post, return content unaltered
    if ( isset( $lrMeta['sharing'] ) && $lrMeta['sharing'] == 1 && ! is_front_page() ) {
        return $content;
    }
    if ( isset( $loginRadiusSettings['horizontal_shareEnable'] ) && $loginRadiusSettings['horizontal_shareEnable'] == '1' ) {
        // If horizontal sharing is enabled
        $loginRadiusHorizontalSharingDiv = '<div class="loginRadiusHorizontalSharing"';
        $loginRadiusHorizontalSharingDiv .= ' data-share-url="' . get_permalink( $post->ID ) . '" data-counter-url="' . get_permalink( $post->ID ) . '"';
        $loginRadiusHorizontalSharingDiv .= ' ></div>';
    } else {
        $loginRadiusHorizontalSharingDiv = '<div class="loginRadiusHorizontalSharing"';
        $loginRadiusHorizontalSharingDiv .= ' data-share-url="' . get_permalink( $post->ID ) . '"';
        $loginRadiusHorizontalSharingDiv .= ' ></div>';
    }
    $horizontalDiv = $loginRadiusHorizontalSharingDiv;
    $sharingFlag = '';
    //displaying sharing interface on home page
    if ( ( ( isset( $loginRadiusSettings['horizontal_sharehome'] ) && current_filter() == 'the_content' ) || ( isset( $loginRadiusSettings['horizontal_shareexcerpt'] ) && current_filter() == 'get_the_excerpt' ) ) && is_front_page() && isset( $loginRadiusSettings['horizontal_sharehome'] ) ) {
       //checking if current page is home page and sharing on home page is enabled.
        $sharingFlag = 'true';
    }
    //displaying sharing interface on Post and pages
    if ( ( isset( $loginRadiusSettings['horizontal_sharepost'] ) && is_single() ) || ( isset( $loginRadiusSettings['horizontal_sharepage'] ) && is_page() && ! is_front_page()) ) {
        $sharingFlag = 'true';
    }

    if ( ( ( isset( $loginRadiusSettings['horizontal_sharepost'] ) && current_filter() == 'the_content' ) || ( isset( $loginRadiusSettings['horizontal_shareexcerpt'] ) && current_filter() == 'get_the_excerpt' ) && is_page() ) ) {
        //checking if custom page is used for displaying posts
        $sharingFlag = 'true';
    }

    if ( is_page() && ! is_front_page() && isset( $loginRadiusSettings['horizontal_sharepage'] ) ) {
        //If not home page and sharing on pages is enabled.
        $sharingFlag = 'true';
    }

    if ( is_front_page() && ! isset( $loginRadiusSettings['horizontal_sharehome'] ) ) {
        //If sharing on front page disabled.
        if ( true == $sharingFlag ) {
            $sharingFlag = '';
        }
    }

    if ( isset( $loginRadiusSettings['horizontal_shareexcerpt'] ) && current_filter() == 'get_the_excerpt' ) {
        //If sharing on Post Excerpts is enabled.
        $sharingFlag = 'true';
    }

    if ( ! empty( $sharingFlag ) ) {
        if ( isset( $loginRadiusSettings['horizontal_shareTop'] ) && isset( $loginRadiusSettings['horizontal_shareBottom'] ) ) {
            $content = $horizontalDiv . '<br/>' . $content . '<br/>' . $horizontalDiv;
        } else {
            if ( isset( $loginRadiusSettings['horizontal_shareTop'] ) ) {
                $content = $horizontalDiv . $content;
            } elseif ( isset( $loginRadiusSettings['horizontal_shareBottom'] ) ) {
                $content = $content . $horizontalDiv;
            }
        }
    }

    if ( isset( $loginRadiusSettings['vertical_shareEnable'] ) && $loginRadiusSettings['vertical_shareEnable'] == '1' ) {
        $vertcalSharingFlag = '';
        $loginRadiusVerticalSharingDiv = '<div class="loginRadiusVerticalSharing" style="z-index: 10000000000"></div>';

        if ( ( ( isset( $loginRadiusSettings['vertical_sharehome'] ) && current_filter() == 'the_content' ) ) && is_front_page() && isset( $loginRadiusSettings['vertical_sharehome'] ) ) {
            // If vertical sharing on Home page enabled.
            $vertcalSharingFlag = 'true';
        }

        if ( ( isset( $loginRadiusSettings['vertical_sharepost'] ) && current_filter() == 'the_content' ) && is_page() ) {
            //checking if custom page is used for displaying posts.
            $vertcalSharingFlag = 'true';
        }

        if ( ( isset( $loginRadiusSettings['vertical_sharepost'] ) && is_single() ) || ( isset( $loginRadiusSettings['vertical_sharepage'] ) && is_page() ) ) {
            //displaying sharing interface on Post and pages.
            $vertcalSharingFlag = 'true';
        }

        if ( is_page() && ! is_front_page() && isset( $loginRadiusSettings['vertical_sharepage'] ) ) {
            //If not front page and vertical sharing on pages is enabled.
            $vertcalSharingFlag = 'true';
        }
        if ( is_front_page() && ! isset( $loginRadiusSettings['vertical_sharehome'] ) ) {
            //if page is front page and vertical sharing is disabled on home page.
            if ( true == $sharingFlag ) {
                $vertcalSharingFlag = '';
            }
        }
        if ( is_home() && isset( $loginRadiusSettings['vertical_sharehome'] ) ) {

            $vertcalSharingFlag = 'true';

        }
        if ( ! empty( $vertcalSharingFlag ) ) {
            //if Vertical sharing is needed on current page.
            global $loginRadiusSharingVerticalInterfaceContentCount, $loginRadiusSharingVerticalInterfaceExcerptCount;
            if ( current_filter() == 'the_content' ) {
                $compareVariable = 'loginRadiusSharingVerticalInterfaceContentCount';
            } elseif ( current_filter() == 'get_the_excerpt' ) {
                $compareVariable = 'loginRadiusSharingVerticalInterfaceExcerptCount';
            }
            if ( $$compareVariable == 0 ) {
                $content = $content . $loginRadiusVerticalSharingDiv;
                $$compareVariable++;
            } else {
                $content = $content . $loginRadiusVerticalSharingDiv;
            }
        }
    }
    //returnig the content with sharing interface.
    return $content;
}

/**
 * Check if scripts are to be loaded in footer
 */
function login_radius_sharing_scripts_in_footer_enabled() {
    global $loginRadiusSettings;

    if ( isset( $loginRadiusSettings['scripts_in_footer'] ) && $loginRadiusSettings['scripts_in_footer'] == 1 ) {
        return true;
    }
    return false;
}
