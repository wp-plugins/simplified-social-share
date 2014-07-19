<?php
/**
 * This view file is actually responsible for rendering
 * LoginRadius plugin settings page
 */

//include all necessary files
include 'loginradius-basic-settings-view.php';
include 'loginradius-admin-theme-settings-view.php';
include 'loginradius-plugin-deletion-settings-view.php';
include 'loginradius-admin-help-view.php';

/**
 * Render Login Radius plugin Settings view
 */
function login_radius_render_settings() {
    global $loginRadiusSettings;
    ?>
    <div class="metabox-holder columns-2" id="post-body">
        <div class="menu_div" id="tabs">
            <h2 class="nav-tab-wrapper" style="height:36px">
                <ul>
                    <li><a class="nav-tab" href="#tabs-1"><?php _e( 'Social Sharing Settings', 'LoginRadius' ) ?></a></li>
                    <li><a class="nav-tab" href="#tabs-2"><?php _e( 'Help', 'LoginRadius' ) ?></a></li>
                </ul>
            </h2>
            <div id="tabs-1" class="menu_containt_div" >
                <?php
                login_radius_render_basic_share_settings();
                login_radius_render_theme_selection_view();
                login_radius_render_plugin_deletion_settings_view();
                ?>
            </div>

            <!-- Display help document -->
            <div class="menu_containt_div" id="tabs-2">
                <?php
                login_radius_render_help_document();
                ?>
            </div>

            <p class="submit">
                <?php
                // Build Preview Link
                $preview_link = get_option( 'home' ) . '/';
                if ( is_ssl() ) {
                    $preview_link = str_replace( 'http://', 'https://', $preview_link );
                }
                $stylesheet = get_option( 'stylesheet' );
                $template = get_option( 'template' );
                $preview_link = htmlspecialchars( add_query_arg( array('preview' => 1, 'template' => $template, 'stylesheet' => $stylesheet, 'preview_iframe' => true, 'TB_iframe' => 'true'), $preview_link ) );
                ?>
                <input style="margin-left:8px" type="submit" name="save" class="button button-primary" value="<?php _e( 'Save Changes', 'LoginRadius' ); ?>" />
                <a href="<?php echo $preview_link; ?>" class="thickbox thickbox-preview" id="preview" ><?php _e( 'Preview', 'LoginRadius' ); ?></a>
            </p>
        </div>
    </div>
    <?php
}
