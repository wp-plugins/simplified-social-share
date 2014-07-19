<?php

/**
 * Rendering help document on LoginRadius plugin settings page
 */
function login_radius_render_help_document() {
    global $loginRadiusSettings;
    ?>
    <div class="stuffbox">
        <h3><label><?php _e( 'Help & Documentations', 'LoginRadius' ); ?></label></h3>
        <div class="inside">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" class="form-table editcomment menu_content_table">
                <tr id="login_radius_vertical_position_counter">
                    <td>
                        <ul style="margin-right:86px">
                            <li><a target="_blank" href="http://ish.re/9WBX"><?php _e( 'Plugin Installation, Configuration and Troubleshooting', 'LoginRadius' ) ?></a></li>
                            <li><a target="_blank" href="http://ish.re/BENQ"><?php _e( 'How to get LoginRadius API Key', 'LoginRadius' ) ?></a></li>
                            <li><a target="_blank" href="http://ish.re/8PG2"><?php _e( 'Discussion Forum', 'LoginRadius' ) ?></a></li>
                            <li><a target="_blank" href="http://ish.re/8PJ7"><?php _e( 'About LoginRadius', 'LoginRadius' ) ?></a></li>
                        </ul>
                        <ul>
                            <li><a target="_blank" href="http://ish.re/5P2D"><?php _e( 'LoginRadius Products', 'LoginRadius' ) ?></a></li>
                            <li><a target="_blank" href="http://ish.re/C8E7"><?php _e( 'CMS Plugins', 'LoginRadius' ) ?></a></li>
                            <li><a target="_blank" href="http://ish.re/C9F7"><?php _e( 'API Libraries', 'LoginRadius' ) ?></a></li>
                        </ul>
                    </td>
                </tr>
            </table>
        </div>
    </div>
    <?php
}
