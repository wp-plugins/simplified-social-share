<?php

/**
 * Rendering plugin deletion settings section for LoginRadius plugin settings page.
 */
function login_radius_render_plugin_deletion_settings_view() {
    global $loginRadiusSettings;
    ?>
    <div class="stuffbox">
        <h3><label><?php _e( 'Plugin deletion options', 'LoginRadius' ); ?></label></h3>
        <div class="inside">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" class="form-table editcomment menu_content_table">
                <tr>
                    <td>
                        <div class="loginRadiusQuestion">
                            <?php _e( 'Do you want to completely remove the plugin settings and options on plugin deletion (If you choose yes, then you will not be able to recover settings again) ?', 'LoginRadius' ); ?>
                        </div>
                        <div class="loginRadiusYesRadio">
                            <input type="radio" name="LoginRadius_sharing_settings[delete_options]" value='1' <?php echo(!isset( $loginRadiusSettings['delete_options'] ) || $loginRadiusSettings['delete_options'] == 1 ) ? 'checked' : ''; ?> /> <?php _e( 'Yes', 'LoginRadius' ) ?> <br />
                        </div>
                        <input type="radio" name="LoginRadius_sharing_settings[delete_options]" value="0" <?php echo ( isset( $loginRadiusSettings['delete_options'] ) && $loginRadiusSettings['delete_options'] == 0 ) ? 'checked' : ''; ?>  /> <?php _e( 'No', 'LoginRadius' ); ?><br />
                    </td>
                </tr>
            </table>
        </div>
    </div>
    <?php
}
