<?php
/**
 * Adding script for login/registration from LoginRadius plugin setting page.
 */
function login_radius_render_aia() {
    ?>
    <!-- Login, registration form at the installation of the plugin -->
    <script src="http://hub.loginradius.com/cdn/Include/js/LoginRadius.1.0.js"></script>
    <script src='<?php echo LOGINRADIUS__PLUGIN_URL . "assets/js/loginradius-aia.js"; ?>'></script>
    <script>
    // hooks on start and end of process
        LoginRadiusAIA.$hooks.setProcessHook(function() {
            jQuery('.loginradius-aia-submit').next('#loginradius_error_msg').remove();
            jQuery('.loginradius-aia-submit').after('<div id = "loginradius_error_msg" style = "float:left; margin-top:10px; color: blue; font-weight: 11px;"><img width="20" height="20" style = "float:left; margin-right: 5px;" src="<?php echo LOGINRADIUS__PLUGIN_URL . 'assets/images/loading_icon.gif'; ?>" /><span>Please wait. This may take few minutes...</span>');
        }, function() {
        });
        var siteSaveAjaxOptions ={
                type: 'POST',
                url: '<?php echo get_admin_url() ?>admin-ajax.php',

                success: function(data) {
                    if ( data.trim() == 'error') {
                        jQuery('#loginradius_error_msg').html('');
                        jQuery('#loginRadiusMessage').html('Unexpected error occurred, Please refresh the page and try again..').css('color', 'red');
                        return;
                    } else {
                        // options updated successfully, refresh location
                        location.reload();
                        return;
                    }
                },
                error: function() {
                    jQuery('#loginradius_error_msg').html('');
                        jQuery('#loginRadiusMessage').html('Unexpected error occurred, Please refresh the page and try again..').css('color', 'red');
                        return;
                }
            };
        // save selected LR Site API Key
        function loginRadiusSaveLRSite() {
            //If Site selection is empty
            if ( jQuery('#lrSites').val().trim() == '') {
                jQuery('#loginRadiusMessage').html('Please select a site').css('color', 'red');
                return;
            }
            // processing message
            jQuery('#loginRadiusLRSiteSave').html('');
            jQuery('#loginradius_error_msg').html('<img width="20" height="20" style = "float:left; margin-right: 5px;" src="<?php echo LOGINRADIUS__PLUGIN_URL . 'assets/images/loading_icon.gif'; ?>" /><span>Please wait. This may take few minutes...</span>');
            siteSaveAjaxOptions.data = {
                    action: 'login_radius_sharing_save_site',
                    apikey: jQuery('#lrSites').val().trim()
                };
            jQuery.ajax(siteSaveAjaxOptions);
        }

        function loginRadiusNotification(message) {
            jQuery('#loginRadiusMessage').remove();
            jQuery('#loginRadiusFormTitle').after("<div id='loginRadiusMessage' style='color:red; margin-bottom: 10px;'>" + message + "</div>");
        }

        $SL.util.ready(function() {
            <?php global $current_user; get_currentuserinfo(); ?>
            var aiaOptions = {};
            aiaOptions.inFormvalidationMessage = true;
            aiaOptions.website = '<?php echo site_url() ?>';
            aiaOptions.WebTechnology = 'wordpress';
            aiaOptions.Emailid = '<?php echo $current_user->user_email ?>';

            //Registering LoginRadius Account through plugin admin
            LoginRadiusAIA.init(aiaOptions, 'registration', function(response) {

                jQuery('.loginradius-aia-submit').next('div').remove();
                if ( response[0].apikey ) {
                    //if valid API Key is returned, Make ajax request for saving API Key
                    siteSaveAjaxOptions.data =  {
                            action: 'login_radius_sharing_save_site',
                            apikey: response[0].apikey,
                        }

                    jQuery.ajax( siteSaveAjaxOptions );
                }
            }, function( errors ) {
                if ( errors[0].description != null ) {
                    loginRadiusNotification(errors[0].description);
                }
                jQuery('.loginradius-aia-submit').next('div').remove();
            }, "loginRadiusRegisterForm");

            //Logging into LoginRadius Account through plugin admin
            LoginRadiusAIA.init(aiaOptions, 'login', function(response) {
                jQuery('.loginradius-aia-submit').next('div').remove();
                if ( response == '' ) {
                    loginRadiusNotification('You have not created any Site on www.loginradius.com. Please Login to create a Site');
                    return;
                }
                jQuery('#loginRadiusMessage').html('');
                jQuery('#login-registration-links').remove();

                if ( response[0].apikey ) {
                    // display the LoginRadius Site list
                    jQuery('#loginRadiusFormTitle').html('Site Selection');
                    var html = '<div id="loginRadiusMessage"></div><table class="form-table"><tbody><tr><th><label for="lrSites"><?php _e( 'Select a LoginRadius site', 'LoginRadius' ) ?></label></th><td><select id="lrSites"><option value="">--Select a Site--</option>';
                    for (var i = 0; i < response.length; i++) {
                        html += '<option value="' + response[i].apikey + '">' + response[i].appName + '</option>';
                    }
                    html += '</select>';
                    html += '</td></tr><tr><td><input type="button" id="loginRadiusLRSiteSave" class="button button-primary" style="border-style: solid;border-width: 1px;width: 90px;font-size: 14px;line-height: 15px;" value="<?php _e( 'Save', 'LoginRadius' ) ?>" /></td><td><div id = "loginradius_error_msg" style = "color: blue; margin-left: -105px;"></div></td></tr>';
                    jQuery('#loginRadiusLoginForm').html(html);
                    document.getElementById('loginRadiusLRSiteSave').onclick = function() {
                        loginRadiusSaveLRSite();
                    };
                } else {
                    loginRadiusNotification('You have not created any Site on www.loginradius.com. Please Login to create a Site');
                }
            }, function(errors) {
                if ( errors[0].description != null ) {
                    loginRadiusNotification( errors[0].description );
                }
                jQuery('.loginradius-aia-submit').next('div').remove();
            }, "loginRadiusLoginForm");
        });
    </script>

    <h3 id="loginRadiusFormTitle">
        <?php _e( 'Create a LoginRadius Account', 'LoginRadius' ) ?>
    </h3>
    <div id="loginRadiusLoginForm"></div>
    <div id="loginRadiusRegisterForm"></div>
    <div class = "clr"></div>
    <div id = "login-registration-links">
        <a id="loginRadiusToggleFormLink" href="javascript:void( 0 ) " onclick="loginRadiusToggleForm('login')"><?php _e( 'Already have an account?', 'LoginRadius' ) ?></a></br>
        <a style="margin-top: 5px; " target="_blank" href="https://secure.loginradius.com/login/forgotten" onclick="loginRadiusToggleForm('login')"><?php _e( 'Forgot your password?', 'LoginRadius' ) ?></a>
    </div>
    <?php
}
