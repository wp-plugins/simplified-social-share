<?php

/**
 * Function for adding script to display theme specific settings dynamically.
 */
function login_radius_render_admin_ui_script() {
?>
<script type="text/javascript">var islrsharing = true; var islrsocialcounter = true;</script>
        <?php wp_enqueue_script( 'LoginRadius_admin_sharing_script', '//share.loginradius.com/Content/js/LoginRadius.js' ); ?>
        <script type="text/javascript">

            function loginRadiusAdminUI2() {
                var selectedHorizontalSharingProviders = <?php echo login_radius_get_sharing_providers_josn_arrays( 'horizontal' ); ?>;
                var selectedVerticalSharingProviders = <?php echo login_radius_get_sharing_providers_josn_arrays( 'vertical' ); ?>;
                var selectedHorizontalCounterProviders = <?php echo login_radius_get_counters_providers_json_array( 'horizontal' ); ?>;
                var selectedVerticalCounterProviders = <?php echo login_radius_get_counters_providers_json_array( 'vertical' ); ?>;

                var loginRadiusSharingHtml = '';
                var checked = false;

                // prepare HTML to be shown as Horizontal Sharing Providers
                for (var i = 0; i < $SS.Providers.More.length; i++) {
                    checked = loginRadiusCheckElement(selectedHorizontalSharingProviders, $SS.Providers.More[i]);
                    loginRadiusSharingHtml += '<div class="loginRadiusProviders"><input type="checkbox" onchange="loginRadiusHorizontalSharingLimit( this ); loginRadiusRearrangeProviderList( this, \'Horizontal\' ) " ';
                    if (checked) {
                        loginRadiusSharingHtml += 'checked="' + checked + '" ';
                    }
                    loginRadiusSharingHtml += 'name="LoginRadius_sharing_settings[horizontal_sharing_providers][]" value="' + $SS.Providers.More[i] + '"> <label>' + $SS.Providers.More[i] + '</label></div>';
                }

                // show horizontal sharing providers list
                jQuery('#login_radius_horizontal_sharing_providers_container').html(loginRadiusSharingHtml);

                loginRadiusSharingHtml = '';
                checked = false;
                // prepare HTML to be shown as Vertical Sharing Providers
                for (var i = 0; i < $SS.Providers.More.length; i++) {
                    checked = loginRadiusCheckElement(selectedVerticalSharingProviders, $SS.Providers.More[i]);
                    loginRadiusSharingHtml += '<div class="loginRadiusProviders"><input type="checkbox" onchange="loginRadiusVerticalSharingLimit( this ); loginRadiusRearrangeProviderList( this, \'Vertical\' ) " ';
                    if (checked) {
                        loginRadiusSharingHtml += 'checked="' + checked + '" ';
                    }
                    loginRadiusSharingHtml += 'name="LoginRadius_sharing_settings[vertical_sharing_providers][]" value="' + $SS.Providers.More[i] + '"> <label>' + $SS.Providers.More[i] + '</label></div>';
                }
                // show vertical sharing providers list
                jQuery('#login_radius_vertical_sharing_providers_container').html(loginRadiusSharingHtml);
                loginRadiusSharingHtml = '';
                checked = false;
                // prepare HTML to be shown as Horizontal Counter Providers
                for (var i = 0; i < $SC.Providers.All.length; i++) {
                    checked = loginRadiusCheckElement(selectedHorizontalCounterProviders, $SC.Providers.All[i]);
                    loginRadiusSharingHtml += '<div class="loginRadiusCounterProviders"><input type="checkbox" ';
                    if (checked) {
                        loginRadiusSharingHtml += 'checked="' + checked + '" ';
                    }
                    loginRadiusSharingHtml += 'name="LoginRadius_sharing_settings[horizontal_counter_providers][]" value="' + $SC.Providers.All[i] + '"> <label>' + $SC.Providers.All[i] + '</label></div>';
                }
                // show horizontal counter providers list
                jQuery('#login_radius_horizontal_counter_providers_container').html(loginRadiusSharingHtml);

                loginRadiusSharingHtml = '';
                checked = false;
                // prepare HTML to be shown as Vertical Counter Providers
                for (var i = 0; i < $SC.Providers.All.length; i++) {
                    checked = loginRadiusCheckElement(selectedVerticalCounterProviders, $SC.Providers.All[i]);
                    loginRadiusSharingHtml += '<div class="loginRadiusCounterProviders"><input type="checkbox" ';
                    if (checked) {
                        loginRadiusSharingHtml += 'checked="' + checked + '" ';
                    }
                    loginRadiusSharingHtml += 'name="LoginRadius_sharing_settings[vertical_counter_providers][]" value="' + $SC.Providers.All[i] + '"> <label>' + $SC.Providers.All[i] + '</label></div>';
                }
                // show vertical counter providers list
                jQuery('#login_radius_vertical_counter_providers_container').html(loginRadiusSharingHtml);
            }
        </script>
        <?php
    }