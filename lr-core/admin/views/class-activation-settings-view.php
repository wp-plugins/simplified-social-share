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
                <form action="options.php" method="post">
                    <?php
                        settings_fields( 'loginradius_api_settings' );
                        settings_errors();
                    ?>
                    <div class="lr_options_container">
                        <div class="lr-row">
                            <?php if ( class_exists( 'LR_Social_Login' ) || class_exists( 'LR_Raas' ) ) { ?>
                            <h3>To activate Social Login, insert the LoginRadius API Key and Secret in the section below. If you don't have them, please follow the <a href="http://support.loginradius.com/hc/en-us/articles/201894526-How-do-I-get-a-LoginRadius-API-key-and-secret-" target="_blank">instructions</a>.</h3>
                            <label >
                                <span class="lr_property_title"><?php _e( 'Sitename', 'LoginRadius' ); ?>
                                    <span class="lr-tooltip" data-title="Your LoginRadius Sitename">
                                        <span class="dashicons dashicons-editor-help"></span>
                                    </span>
                                </span>
                                <input type="text" class="lr-row-field" name="LoginRadius_API_settings[sitename]" value="<?php echo $loginradius_api_settings['sitename']; ?>" autofill='off' autocomplete='off' />
                            </label>
                            <?php } else { ?>
                            <h3>API Key is optional for Social Sharing, insert the LoginRadius API Key. If you don't have one, please follow the <a href="http://support.loginradius.com/hc/en-us/articles/201894526-How-do-I-get-a-LoginRadius-API-key-and-secret-" target="_blank">instructions</a>.</h3>
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
                        <div class="lr-row">
                            <label class="lr-toggle">
                                <input type="checkbox" class="lr-toggle" name="LoginRadius_API_settings[scripts_in_footer]" value="1" <?php echo ( isset($loginradius_api_settings['scripts_in_footer'] ) && $loginradius_api_settings['scripts_in_footer'] == '1' ) ? 'checked' : ''; ?> />
                                <span class="lr-toggle-name">
                                    For faster loading, do you want to load sharing javascript in the footer?
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
                    </div>
                    <p class="submit">
                        <?php submit_button( 'Save Settings', 'primary', 'submit', false ); ?>
                    </p>
                </form>
            </div>
            <?php
            }
        }

    }

