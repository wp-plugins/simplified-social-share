<?php
// Exit if called directly
if ( ! defined( 'ABSPATH' ) ) {
    exit();
}

/**
 * The activation settings class.
 */
if ( ! class_exists( 'LR_Activation_Settings' ) ) {

    class LR_Activation_Settings {

        public static function render_options_page() {
            global $loginradius_api_settings;
            $loginradius_api_settings = get_option( 'LoginRadius_API_settings' );
            ?>
            <!-- LR-wrap -->
            <div class="wrap lr-wrap cf">
                <header>
                    <h2 class="logo"><a href="//www.loginradius.com" target="_blank">LoginRadius</a><em>Activation</em></h2>
                </header>
                <div id="lr_options_tabs" class="cf">
                    <div class="cf">
                        <ul class="lr-options-tab-btns">
                            <li class="nav-tab lr-active" data-tab="lr_options_tab-1"><?php _e( 'Activation', 'LoginRadius' ) ?></li>
                            <li class="nav-tab" data-tab="lr_options_tab-2"><?php _e( 'Advanced Settings', 'LoginRadius' ) ?></li>
                        </ul>
                        <form action="options.php" method="post">
                        <?php
                            settings_fields( 'loginradius_api_settings' );
                            settings_errors();
                        ?>
                        <div id="lr_options_tab-1" class="lr-tab-frame lr-active">
                            <div class="lr_options_container">
                                <div class="lr-row">
                                    <?php if ( class_exists( 'LR_Social_Login' ) || class_exists( 'LR_Raas' ) ) { ?>
                                    <h6>To activate LoginRadius Plugin, insert the LoginRadius API Key and Secret in the section below. If you don't have them, please follow these <a href="http://ish.re/INI1" target="_blank">instructions</a>.</h6>
                                    <label >
                                        <span class="lr_property_title"><?php _e( 'LoginRadius Site Name', 'LoginRadius' ); ?>
                                            <span class="lr-tooltip" data-title="You can find the Site Name into your LoginRadius user account">
                                                <span class="dashicons dashicons-editor-help"></span>
                                            </span>
                                        </span>
                                        <input type="text" class="lr-row-field" name="LoginRadius_API_settings[sitename]" value="<?php echo $loginradius_api_settings['sitename']; ?>" autofill='off' autocomplete='off' />
                                    </label>
                                    <?php } else { ?>
                                    <h6>API Key is optional for Social Sharing, insert the LoginRadius API Key. If you don't have one, please follow the <a href="http://ish.re/INI1" target="_blank">instructions</a>.</h6>
                                    <?php } ?>
                                    <label>
                                        <span class="lr_property_title"><?php _e( 'LoginRadius API Key', 'LoginRadius' ); ?>
                                            <span class="lr-tooltip" data-title="Your unique LoginRadius API Key">
                                                        <span class="dashicons dashicons-editor-help"></span>
                                            </span>
                                        </span>
                                        <input type="text" class="lr-row-field" name="LoginRadius_API_settings[LoginRadius_apikey]" value="<?php echo ( isset( $loginradius_api_settings['LoginRadius_apikey'] ) && !empty($loginradius_api_settings['LoginRadius_apikey']) ) ? $loginradius_api_settings['LoginRadius_apikey'] : ''; ?>" autofill='off' autocomplete='off' />
                                    </label>
                                    
                                    <?php if ( class_exists( 'LR_Social_Login' ) || class_exists( 'LR_Raas' ) ) { ?>
                                    <label >
                                        <span class="lr_property_title"><?php _e( 'LoginRadius API Secret', 'LoginRadius' ); ?>
                                            <span class="lr-tooltip" data-title="Your unique LoginRadius API Secret">
                                                <span class="dashicons dashicons-editor-help"></span>
                                            </span>
                                        </span>
                                        <input type="text" class="lr-row-field" name="LoginRadius_API_settings[LoginRadius_secret]" value="<?php echo $loginradius_api_settings['LoginRadius_secret']; ?>" autofill='off' autocomplete='off' />
                                    </label>
                                    <?php } ?>
                                </div>
                            </div>
                            <p class="submit">
                                <?php submit_button( 'Save Settings', 'primary', 'submit', false ); ?>
                            </p>
                        </div>
                        <div id="lr_options_tab-2" class="lr-tab-frame">
                            <div class="lr_options_container">
                                <div class="lr-row" style="display: none;">
                                    <h3><?php _e( 'JavaScript options', 'LoginRadius' ); ?></h3>
                                    <label class="lr-toggle">
                                        <input type="checkbox" class="lr-toggle" name="LoginRadius_API_settings[scripts_in_footer]" value="1" <?php echo ( isset($loginradius_api_settings['scripts_in_footer'] ) && $loginradius_api_settings['scripts_in_footer'] == '1' ) ? 'checked' : ''; ?> />
                                        <span class="lr-toggle-name">
                                            For faster loading, do you want to load javascripts in the footer?
                                            <span class="lr-tooltip" data-title="The JavaScript will load in the footer by default, please disable it if your theme doesn't support footers or has issues with the plugin">
                                                <span class="dashicons dashicons-editor-help"></span>
                                            </span>
                                        </span>
                                    </label>
                                </div><!-- lr-row -->
                                <div class="lr-row">
                                    <h3><?php _e( 'Plug-in deletion options', 'LoginRadius' ); ?></h3>
                                    <div>
                                        <h4>
                                            <?php _e( 'Do you want to completely remove any plugin settings you have already set?', 'LoginRadius' ); ?>
                                            <span class="lr-tooltip" data-title="If you choose Yes, then you will not be able to recover those settings again">
                                                <span class="dashicons dashicons-editor-help"></span>
                                            </span>
                                        </h4>
                                        <label>
                                            <input type="radio" name="LoginRadius_API_settings[delete_options]" value='1' <?php echo ( !isset( $loginradius_api_settings['delete_options'] ) || $loginradius_api_settings['delete_options'] == '1' ) ? 'checked' : ''; ?> />
                                            <span><?php _e( 'Yes', 'LoginRadius' ) ?></span>
                                        </label>
                                        <label>
                                            <input type="radio" name="LoginRadius_API_settings[delete_options]" value="0" <?php echo ( isset( $loginradius_api_settings['delete_options'] ) && $loginradius_api_settings['delete_options'] == '0' ) ? 'checked' : ''; ?> />
                                            <span><?php _e( 'No', 'LoginRadius' ); ?></span>
                                        </label>
                                    </div>
                                </div>  
                            <?php
                                if ( is_multisite() && is_main_site() ) {
                                    ?>
                                    <div class="lr-row">

                                        <h3><?php _e( 'Multisite', 'LoginRadius' ); ?></h3>
                                            <div>
                                                <h4>
                                                    <?php _e( 'Do you want to apply the same changes when you update plugin settings in the main blog of multisite network?', 'LoginRadius' ); ?>
                                                    <span class="lr-tooltip" data-title="If enabled, it would apply plugin settings of your main site to all other sites under this multisite network.">
                                                        <span class="dashicons dashicons-editor-help"></span>
                                                    </span>
                                                </h4>
                                                <label>
                                                    <input type="radio" name="LoginRadius_API_settings[multisite_config]" value='1' <?php echo ( ( !isset( $loginradius_api_settings['multisite_config'] ) ) || ( isset( $loginradius_api_settings['multisite_config'] ) && $loginradius_api_settings['multisite_config'] == 1 ) ) ? 'checked' : '' ; ?>/>
                                                    <span><?php _e( 'Yes, apply the same changes to plugin settings of each blog in the multisite network when I update plugin settings.', 'LoginRadius' ); ?></span>
                                                </label>
                                                <label>
                                                    <input type="radio" name="LoginRadius_API_settings[multisite_config]" value="0" <?php echo ( isset( $loginradius_api_settings['multisite_config'] ) && $loginradius_api_settings['multisite_config'] == 0 ) ? 'checked' : ''; ?>/>
                                                    <span><?php _e( 'No, do not apply the changes to other blogs when I update plugin settings.', 'LoginRadius' ); ?></span>
                                                </label>
                                            </div>
                                    </div>
                                    <?php
                                }
                            ?>
                            </div>
                            <p class="submit">
                                <?php submit_button( 'Save Settings', 'primary', 'submit', false ); ?>
                            </p>
                        </div>
                        </form>
                   </div>
                </div>        
            </div>
            <?php
            }
        }
}

