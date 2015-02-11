<?php
if ( !class_exists( 'Admin_Helper' ) ) {

    class Admin_Helper {

        /*
         * Display notice on plugin page, if LR API Key and Secret are empty
         */
        public static function display_notice_to_insert_api_and_secret() {
            ?>
            <div id="loginRadiusKeySecretNotification" style="background-color: #FFFFE0; border:1px solid #E6DB55; padding:5px; margin-bottom:5px; width: 1050px;">
                <?php _e( 'To activate the <strong>Social Login</strong>, insert LoginRadius API Key and Secret in the <strong>API Settings</strong> section below. <strong>Social Sharing does not require API Key and Secret</strong>.', 'LoginRadius' ); ?>
            </div>
            <?php
        }

        /**
         * Check if LoginRadius API Key and Secret are saved
         *
         * global $loginradius_api_settings
         */
        public static function loginradius_api_secret_saved() {
            global $loginradius_api_settings;
            if ( !isset( $loginradius_api_settings['LoginRadius_apikey'] ) || trim( $loginradius_api_settings['LoginRadius_apikey'] ) == '' || !isset( $loginradius_api_settings['LoginRadius_secret'] ) || trim( $loginradius_api_settings['LoginRadius_secret'] ) == '' ) {
                return false;
            }
            return true;
        }
        /**
         * Add provider column on users list page
         *
         * global $loginRadiusSettings
         */
        public static function add_provider_column_in_users_list( $columns ) {
            global $loginRadiusSettings;
            if ( isset( $loginRadiusSettings['LoginRadius_noProvider'] ) && $loginRadiusSettings['LoginRadius_noProvider'] == '1' ) {
                $columns['loginradius_provider'] = 'LoginRadius Provider';
            }
            if ( isset( $loginRadiusSettings['LoginRadius_enableUserActivation'] ) && $loginRadiusSettings['LoginRadius_enableUserActivation'] == '1' ) {
                // Add active/inactive Staus column on users list page
                $columns['loginradius_status'] = 'Status';
            }
            return $columns;
        }

        /**
         * show social ID provider in the provider column
         *
         * global $loginRadiusSettings
         */
        public static function login_radius_show_provider( $value, $columnName, $userId ) {
            global $loginRadiusSettings;

            if( $value != '' ) {
                return $value;
            }
            if( 'loginradius_provider' ==  $columnName ) {
                if ( isset( $loginRadiusSettings['LoginRadius_noProvider'] ) && $loginRadiusSettings['LoginRadius_noProvider'] == '1' ) {
                    $lrProviderMeta = get_user_meta( $userId, 'loginradius_provider', true );
                    $lrProvider = ( $lrProviderMeta == false ) ? '-' : $lrProviderMeta;
                    if ( 'loginradius_provider' == $columnName ) {
                        return ucfirst( $lrProvider );
                    }
                }
            }
            if( 'loginradius_status' ==  $columnName ) {
                if ( isset( $loginRadiusSettings['LoginRadius_enableUserActivation'] ) && $loginRadiusSettings['LoginRadius_enableUserActivation'] == '1' ) {
                    if ( $userId == 1 ) {
                        return;
                    }
                    if ( ( $lrStatus = get_user_meta( $userId, 'loginradius_status', true ) ) == '' || $lrStatus == '1' ) {
                        $lrStatus = '1';
                    } else {
                        $lrStatus = '0';
                    }
                    if ( 'loginradius_status' == $columnName ) {
                        if ( $lrStatus == '1' ) {
                            return '<span id="loginRadiusStatus' . $userId . '"><a alt="Active ( Click to Disable ) " title="Active ( Click to Disable ) " href="javascript:void ( 0 ) " onclick="loginRadiusChangeStatus ( ' . $userId . ', ' . $lrStatus . ' ) " ><img height="20" width="20" src="' . LOGINRADIUS_PLUGIN_URL . 'assets/images/enable.png' . '" /></a></span>';
                        } else {
                            return '<span id="loginRadiusStatus' . $userId . '"><a alt="Inactive ( Click to Enable ) " title="Inactive ( Click to Enable ) " href="javascript:void ( 0 ) " onclick="loginRadiusChangeStatus ( ' . $userId . ', ' . $lrStatus . ' ) " ><img height="20" width="20" src="' . LOGINRADIUS_PLUGIN_URL . 'assets/images/disable.png' . '" /></a></span>';
                        }
                    }
                }
            }
        }

        /**
         * add javascript on users.php in admin for ajax call to activate/deactivate users
         *
         * global $parent_file;
         */
        public static function add_script_for_users_page() {
            global $parent_file;
            if ( $parent_file == 'users.php' ) {
                ?>
                <script type="text/javascript">
                    function loginRadiusChangeStatus(userId, currentStatus) {
                        jQuery('#loginRadiusStatus' + userId).html('<img width="20" height="20" title="<?php _e( 'Please wait', 'LoginRadius' ) ?>..." src="<?php echo LOGINRADIUS_PLUGIN_URL . 'assets/images/loading_icon.gif'; ?>" />');
                        jQuery.ajax({
                            type: 'POST',
                            url: '<?php echo get_admin_url() ?>admin-ajax.php',
                            data: {
                                action: 'login_radius_change_user_status',
                                user_id: userId,
                                current_status: currentStatus
                            },
                            success: function(data) {
                                if (data == 'done') {
                                    if (currentStatus == 0) {
                                        jQuery('#loginRadiusStatus' + userId).html('<span id="loginRadiusStatus' + userId + '"><a href="javascript:void ( 0 ) " alt="<?php _e( 'Active ( Click to Disable ) ', 'LoginRadius' ) ?>" title="<?php _e( 'Active ( Click to Disable ) ', 'LoginRadius' ) ?>" onclick="loginRadiusChangeStatus ( ' + userId + ', 1 ) " ><img width="20" height="20" src="<?php echo LOGINRADIUS_PLUGIN_URL . 'assets/images/enable.png'; ?>" /></a></span>');
                                    } else if (currentStatus == 1) {
                                        jQuery('#loginRadiusStatus' + userId).html('<span id="loginRadiusStatus' + userId + '"><a href="javascript:void ( 0 ) " alt="<?php _e( 'Inactive ( Click to Enable ) ', 'LoginRadius' ) ?>" title="<?php _e( 'Inactive ( Click to Enable ) ', 'LoginRadius' ) ?>" onclick="loginRadiusChangeStatus ( ' + userId + ', 0 ) " ><img width="20" height="20" src="<?php echo LOGINRADIUS_PLUGIN_URL . 'assets/images/disable.png'; ?>" /></a></span>');
                                    }
                                } else if (data == 'error') {
                                    jQuery('#loginRadiusStatus' + userId).html('<span id="loginRadiusStatus' + userId + '"><a href="javascript:void ( 0 ) " alt="<?php _e( 'Active ( Click to Disable ) ', 'LoginRadius' ) ?>" title="<?php _e( 'Active ( Click to Disable ) ', 'LoginRadius' ) ?>" onclick="loginRadiusChangeStatus ( ' + userId + ', 1 ) " ><img width="20" height="20" src="<?php echo plugins_url( 'images/enable.png', __FILE__ ) ?>" /></a></span>');
                                }
                            },
                            error: function(xhr, textStatus, errorThrown) {

                            }
                        });
                    }
                </script>
                <?php
            }
        }

        /**
         * Encoding LoginRadius Plugin settings
         */
        public static function get_encoded_settings_string( $loginradius_api_settings ) {

            $string = '~' . '1|';
            $string .= isset( $loginradius_api_settings['scripts_in_footer'] ) ? $loginradius_api_settings['scripts_in_footer'] . '|' : '|';

            return $string;

        }

        /**
         * Changing array to comma seperated string
         */
        public static function imploading_arrays( $array ) {
            $string = '|["' . implode( '","', $array ) . '"]';
            return $string;
        }

        /**
         * This function return checked="checked" if LoginRadius setting $optionName is the value of  $tempArray[$settingName],
         * else return blank string
         *
         * @global $loginRadiusSettings
         */
        public static function is_radio_checked( $settingName, $optionName ) {
            global $loginRadiusSettings;

            $tempArray = array(
                'login' => 'LoginRadius_redirect',
                'register' => 'LoginRadius_regRedirect',
                'avatar' => 'LoginRadius_socialavatar',
                'seperator' => 'username_separator',
                'send_email' => 'LoginRadius_sendemail',
                'dummy_email' => 'LoginRadius_dummyemail',
                'logoutUrl' => 'LoginRadius_loutRedirect'
            );

            if ( $loginRadiusSettings[$tempArray[$settingName]] == $optionName ) {
                return 'checked="checked"';
            } else {
                return '';
            }
        }

    }

}