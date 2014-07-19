<?php

/**
 * Rendering basic settings section for LoginRadius plugin settings page.
 */
function login_radius_render_basic_share_settings() {
    global $loginRadiusSettings;
    ?>
    <div class="stuffbox">
        <h3><label><?php _e( 'Basic Social Sharing Settings', 'LoginRadius' ); ?></label></h3>
        <div class="inside">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" class="form-table editcomment menu_content_table">
                <tr>
                    <td>
                        <label style="float:left; width:70px; margin-top:2px; font-weight:bold"><?php _e( 'API Key', 'LoginRadius' ); ?></label>
                        <input type="text" id="login_radius_api_key" name="LoginRadius_sharing_settings[LoginRadius_apikey]" value="<?php echo ( isset( $loginRadiusSettings['LoginRadius_apikey'] ) ? htmlspecialchars( $loginRadiusSettings['LoginRadius_apikey'] ) : '' ); ?>" autofill='off' autocomplete='off'  />
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="loginRadiusQuestion" style = "margin-top: 10px;">
                            <?php _e( 'Do you want the plugin Javascript code to be included in the footer for faster loading of website content?', 'LoginRadius' ); ?>
                            <a style="text-decoration:none" href="javascript:void ( 0 ) " title="<?php _e( 'It may break the functionality of the plugin if wp_footer hook does not exist in your theme', 'LoginRadius' ) ?>"> (?) </a>
                        </div>
                        <div class="loginRadiusYesRadio">
                            <input type="radio" name="LoginRadius_sharing_settings[scripts_in_footer]" value='1' <?php echo isset( $loginRadiusSettings['scripts_in_footer'] ) && $loginRadiusSettings['scripts_in_footer'] == 1 ? 'checked' : ''; ?>/><?php _e( 'Yes', 'LoginRadius' ); ?>
                        </div>
                        <input type="radio" name="LoginRadius_sharing_settings[scripts_in_footer]" value="0" <?php echo!isset( $loginRadiusSettings['scripts_in_footer'] ) || $loginRadiusSettings['scripts_in_footer'] == 0 ? 'checked' : ''; ?>/><?php _e( 'No', 'LoginRadius' ); ?>
                    </td>
                </tr>
            </table>
        </div>
    </div>
    <?php
}
