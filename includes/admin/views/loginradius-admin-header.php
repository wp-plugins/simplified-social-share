<?php

/**
 * Rendering loginradius plugin setting page header.
 */
 function login_radius_render_header() {
    ?>
   <div class="header_div">
        <h2>
            <?php _e( 'LoginRadius Simplified Social Share Settings', 'LoginRadius' ) ?>
        </h2>
        <fieldset id = "welcome-message-fieldset" style = "height: 210px !important;">
            <h4>
                <strong>
                    <?php _e( 'Thank you for installing the LoginRadius Simplified Social Share plugin!', 'LoginRadius' ); ?>
                </strong>
            </h4>
            <p>
                <a href="http://www.loginradius.com">LoginRadius</a> <?php _e( ' provides ','LoginRadius' ); ?><a target="_blank" href="http://ish.re/AQ58">Social Login</a>, <a target="_blank" href="http://ish.re/AQ5H">Social Share</a>, <a target="_blank" href="http://ish.re/AQ5I">Social Invite</a>, <a target="_blank" href="http://ish.re/AQ5N">User Social Profile Data</a>, <a target="_blank" href="http://ish.re/AQ5N">User Profile Access</a>, <a target="_blank" href="http://ish.re/AQ5Q">Single Sign-on</a> and <a target="_blank" href="http://ish.re/AQ5L">Social Analytics </a><?php _e(' as single Unified API.', 'LoginRadius' ); ?>
            </p>
            <p>
                <?php _e( 'We also have ready to use plugins for  ', 'LoginRadius' ); ?><a href="http://ish.re/8PE9" target="_blank"> Drupal</a>, <a href="http://ish.re/8PE6" target="_blank">Joomla</a>, <a href="http://ish.re/8PEC" target="_blank">Magento</a>, <a href="http://ish.re/8PEG" target="_blank">osCommerce</a>,  <a href="http://ish.re/8PFR" target="_blank">Zen-Cart</a>, <a href="http://ish.re/8PFQ" target="_blank">X-Cart</a>, <a href="http://ish.re/8PEH" target="_blank">PrestaShop</a>, <a href="http://ish.re/8PEE" target="_blank">VanillaForum</a>, <a href="http://ish.re/8PED" target="_blank">vBulletin</a>, <a href="http://ish.re/8PFV" target="_blank">phpBB</a>, <a href="http://ish.re/8PFT" target="_blank">SMF</a>  <?php echo _e( 'and' ) ?>  <a href="http://ish.re/8PFS" target="_blank">DotNetNuke</a> !
            </p>
        </fieldset>
        <!--Displaying LoginRadius plugin  details-->
        <fieldset id = "plugin-details" style = "height: 215px;">
            <div style="margin:5px 0;">
                <strong><?php _e('Plugin Version', 'LoginRadius' ); ?>:</strong> 2.1<br/>
                <strong><?php _e('Author', 'LoginRadius' ); ?>:</strong> LoginRadius<br/>
                <strong><?php _e('Website', 'LoginRadius' ); ?>:</strong> <a href="https://www.loginradius.com" target="_blank">www.loginradius.com</a> <br/>
                <strong><?php _e('Community', 'LoginRadius' ); ?>:</strong> <a href="http://community.loginradius.com" target="_blank">community.loginradius.com</a> <br/>
                <div id="sociallogin_get_update" style="float:left;">
                    <b><?php _e('Get Updates', 'LoginRadius' ); ?></b><br><?php _e('To receive updates on new features, releases, etc. Please connect to one of our social media pages', 'LoginRadius' ); ?>
                </div>
                <div style = "text-align: center">
                        <a target="_blank" href="https://www.facebook.com/loginradius"><img src="<?php echo LOGINRADIUS__PLUGIN_URL. 'assets/images/media-pages/facebook.png';?>" alt = "Facebook"></a>
                        <a target="_blank" href="https://twitter.com/LoginRadius"><img src="<?php echo LOGINRADIUS__PLUGIN_URL. 'assets/images/media-pages/twitter.png'; ?>" alt = "Twitter"></a>
                        <a target="_blank" href="https://plus.google.com/+Loginradius"> <img src="<?php echo LOGINRADIUS__PLUGIN_URL. 'assets/images/media-pages/google.png'; ?>" alt = "Google"></a>
                        <a target="_blank" href="http://www.linkedin.com/company/loginradius"> <img src="<?php echo LOGINRADIUS__PLUGIN_URL. 'assets/images/media-pages/linkedin.png'; ?>" alt = "LinkedIn"></a>
                        <a target="_blank" href="https://www.youtube.com/user/LoginRadius"> <img src="<?php echo LOGINRADIUS__PLUGIN_URL. 'assets/images/media-pages/youtube.png'; ?>" alt = "Youtube"></a>
                </div>
            </div>
        </fieldset>
    </div>
    <?php
}
