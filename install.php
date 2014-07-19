<?php

/**
 * class responsible for applying default settings for Simplified Social Share
 */
class Login_Radius_Install {
    public function init() {
        if( ! get_option( 'LoginRadius_sharing_settings' ) ) {
            // Adding LoginRadius plugin options if not available
            $options = array(
                'LoginRadius_apikey' => '',
                'LoginRadius_sharingTheme' => 'horizontal',
                'horizontal_shareEnable' => '1',
                'horizontal_shareTop' => '1',
                'horizontal_shareBottom' => '1',
                'horizontal_shareexcerpt' => '1',
                'horizontal_sharepost' => '1',
                'horizontal_sharepage' => '1',
                'vertical_shareEnable' => '1',
                'verticalSharing_theme' => 'counter_vertical',
                'vertical_sharepost' => '1',
                'vertical_sharepage' => '1',
                'delete_options' => '1',
                'scripts_in_footer' => '0',
                'loginradius_db_version' => '2.1',
            );
            add_option( 'LoginRadius_sharing_settings', $options );
        }
    }
}
