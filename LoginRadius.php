<?php
/*
Plugin Name:Simplified Social Sharing  
Plugin URI: http://www.LoginRadius.com
Description: Add Social Sharing to your WordPress website.
Version: 1.5
Author: LoginRadius Team
Author URI: http://www.LoginRadius.com
License: GPL2+
*/
require_once('LoginRadiusSDK.php');
require_once('LoginRadius_function.php');
require_once('LoginRadius_widget.php');
require_once('LoginRadius_admin.php');

/**
 * Load the plugin's translated strings
 */
function login_radius_sharing_init_locale(){
	load_plugin_textdomain('LoginRadius', false, basename(dirname(__FILE__)) . '/i18n');
}
add_filter('init', 'login_radius_sharing_init_locale');

/**
 * Add the LoginRadius menu in the left sidebar in the admin
 */
function login_radius_sharing_admin_menu(){
	$page = @add_menu_page('LoginRadiusSharing', '<b>LoginRadius</b>', 8, 'LoginRadiusSharing', 'login_radius_sharing_option_page', plugins_url('images/favicon.ico', __FILE__));
	@add_action('admin_print_scripts-' . $page, 'login_radius_sharing_options_page_scripts');
	@add_action('admin_print_styles-' . $page, 'login_radius_sharing_options_page_style');
	@add_action('admin_print_styles-' . $page, 'login_radius_sharing_admin_css_custom_page');
}
@add_action('admin_menu', 'login_radius_sharing_admin_menu');

/**
 * Register LoginRadius_sharing_settings and its sanitization callback.
 */
function login_radius_sharing_meta_setup(){
	global $post;
	$postType = $post->post_type;
	$lrMeta = get_post_meta($post->ID, '_login_radius_sharing_meta', true);
	?>
	<p>
		<label for="login_radius_sharing">
			<input type="checkbox" name="_login_radius_sharing_meta[sharing]" id="login_radius_sharing" value="1" <?php checked('1', @$lrMeta['sharing']); ?> />
			<?php _e('Disable Social Sharing on this '.$postType, 'LoginRadius') ?>
		</label>
	</p>
	<?php
	// custom nonce for verification later
    echo '<input type="hidden" name="login_radius_sharing_meta_nonce" value="' . wp_create_nonce(__FILE__) . '" />';

}

/**
 * Save login radius meta fields.
 */
function login_radius_sharing_save_meta($postId){
    // make sure data came from our meta box
    if (!wp_verify_nonce($_POST['login_radius_sharing_meta_nonce'], __FILE__)){
		return $postId;
 	}
    // check user permissions
    if($_POST['post_type'] == 'page'){
        if(!current_user_can('edit_page', $postId)){
			return $postId;
    	}
	}else{
        if(!current_user_can('edit_post', $postId)){
			return $postId;
    	}
	}
    $newData = $_POST['_login_radius_sharing_meta'];
    update_post_meta($postId, '_login_radius_sharing_meta', $newData);
    return $postId;
}

/**
 * Register LoginRadius_sharing_settings and its sanitization callback. Add Login Radius meta box to pages and posts.
 */
function login_radius_sharing_options_init(){
	register_setting('LoginRadius_sharing_setting_options', 'LoginRadius_sharing_settings', 'login_radius_sharing_validate_options');
	// show sharing meta fields on pages and posts
	foreach(array('post', 'page') as $type){
        add_meta_box('login_radius_meta', 'LoginRadius', 'login_radius_sharing_meta_setup', $type);
    }
    // add a callback function to save any data a user enters in
    add_action('save_post', 'login_radius_sharing_save_meta');
}
add_action('admin_init', 'login_radius_sharing_options_init');

/**
 * Get wordpress version.
 */
function login_radius_sharing_get_wp_version(){
	return (float)substr(get_bloginfo('version'), 0, 3);
}

/**
 * Add js for enabling preview.
 */
function login_radius_sharing_options_page_scripts(){
  $script = (login_radius_sharing_get_wp_version() >= 3.2) ? 'loginradius_options-page32.js' : 'loginradius_options-page29.js';
  $scriptLocation = apply_filters('LoginRadius_files_uri', plugins_url('js/' . $script.'?t=4.0.0', __FILE__));
  wp_enqueue_script('LoginRadius_sharing_options_page_script', $scriptLocation, array('jquery-ui-tabs', 'thickbox'));
  wp_enqueue_script('LoginRadius_sharing_options_page_script2', plugins_url('js/loginRadiusAdmin.js?t=4.0.0', __FILE__), array(), false, false);
}

/**

 * Add option Settings css.
 */
function login_radius_sharing_options_page_style(){
	?>
	<!--[if IE]>
		<link href="<?php echo plugins_url('css/loginRadiusOptionsPageIE.css', __FILE__) ?>" rel="stylesheet" type="text/css" />
	<![endif]-->
	<?php
	$styleLocation = apply_filters('LoginRadius_files_uri', plugins_url('css/loginRadiusOptionsPage.css', __FILE__));
	wp_enqueue_style('login_radius_sharing_options_page_style', $styleLocation.'?t=4.0.0');
	wp_enqueue_style('thickbox');
}

/**
 * Add custom page Settings css.
 */
function login_radius_sharing_admin_css_custom_page() {
	wp_register_style('LoginRadius-sharing-plugin-page-css', plugins_url('css/loginRadiusStyle.css', __FILE__), array(), '4.0.0', 'all');
	wp_enqueue_style('LoginRadius-sharing-plugin-page-css');
}

/**
 * Add a settings link to the Plugins page, so people can go straight from the plugin page to the
 * settings page.
 */
function login_radius_sharing_filter_plugin_actions($links, $file){
    static $thisPlugin;
    if(!$thisPlugin){
        $thisPlugin = plugin_basename(__FILE__);
	}
    if ($file == $thisPlugin){
        $settingsLink = '<a href="options-general.php?page=LoginRadiusSharing">' . __('Settings') . '</a>';
        array_unshift($links, $settingsLink); // before other links
    }
    return $links;
}
add_filter('plugin_action_links', 'login_radius_sharing_filter_plugin_actions', 10, 2);

/**
 * Set Default options when plugin is activated first time.
 */
function login_radius_sharing_activation(){
    global $wpdb;
    // Set plugin default options
    add_option('LoginRadius_sharing_settings', array(
										   'LoginRadius_sharingTheme' => 'horizontal',
										   'LoginRadius_counterTheme' => 'horizontal',
										   'LoginRadius_shareEnable' => '1',
										   'horizontal_shareEnable' => '1',
										   'horizontal_shareTop' => '1',
										   'horizontal_shareBottom' => '1',
										   'horizontal_sharehome' => '1',
										   'horizontal_sharepost' => '1',
										   'horizontal_sharepage' => '1',
										   'vertical_shareEnable' => '1',
										   'verticalSharing_theme' => 'counter_vertical',
										   'vertical_sharehome' => '1',
										   'vertical_sharepost' => '1',
										   'vertical_sharepage' => '1',
										   'sharing_offset' => '200',
										));
}
register_activation_hook(__FILE__, 'login_radius_sharing_activation');

//$loginRadiusObject = new LoginRadius();
//$loginRadiusObject -> login_radius_lr_login('https://www.loginradius.com/api/v1/user.login?UserName=rajat7@gmail.com&password=123456&ip=127.0.0.1&Url=http://rajat.localdomain/wordpress&Useragent=Mozilla/5.0 (Windows NT 6.0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/28.0.1500.95 Safari/537.36&Technology=Wordpress', 'curl');
//login_radius_lr_login();
/**
 * call LR api to login/register user.
 */
function login_radius_lr_login(){
	$loginRadiusObject = new LoginRadius();
	$method = "";
	// api connection handler detection
	if(in_array('curl', get_loaded_extensions())){
		$response = $loginRadiusObject->login_radius_check_connection($method = "curl");
		if($response == "service connection timeout" || $response == "timeout"){
			die(
			   json_encode(
			   	   array(
				   	 'status' => 0,
				   	 'message' => 'Uh oh, looks like something went wrong. Try again in a sec!'
				   )
			   )
			);
		}elseif($response == "connection error"){
			die(
			   json_encode(
			   	   array(
				   	 'status' => 0,
				   	 'message' => 'Problem in communicating LoginRadius API. Please check if one of the API Connection method mentioned above is working.'
				   )
			   )
			);
		}
	}elseif(ini_get('allow_url_fopen') == 1){
		$response = $loginRadiusObject->login_radius_check_connection($method = "fopen");
		if($response == "service connection timeout" || $response == "timeout"){
			die(
			   json_encode(
			   	   array(
				   	 'status' => 0,
				   	 'message' => 'Uh oh, looks like something went wrong. Try again in a sec!'
				   )
			   )
			);
		}elseif($response == "connection error"){
			die(
			   json_encode(
			   	   array(
				   	 'status' => 0,
				   	 'message' => 'Problem in communicating LoginRadius API. Please check if one of the API Connection method mentioned above is working.'
				   )
			   )
			);
		}
	}else{
		die(
		   json_encode(
			   array(
				 'status' => 0,
				 'message' => 'Please check your php.ini settings to enable CURL or FSOCKOPEN'
			   )
		   )
		);
	}
	// call LR login/register API
	if(isset($_POST['action'])){
		// if any value posted is blank, halt
		foreach($_POST as $value){
			if(trim($value) == ""){
				die(
				   json_encode(
					   array(
						 'status' => 0,
						 'message' => 'Unexpected error occurred'
					   )
				   )
				);
			}
		}
		if(isset($_POST['lrsite'])){
			$append = 'create';
		}else{
			$append = 'login';
		}
		$queryString = array(
			'UserName' => trim($_POST["UserName"]),
			'password' => trim($_POST["password"]),
			'ip' => $_SERVER["REMOTE_ADDR"],
			'Url' => site_url(),
			'Useragent' => $_SERVER["HTTP_USER_AGENT"],
			'Technology' => 'Wordpress'
		);
		// append LR site name
		if(isset($_POST['lrsite'])){
			$queryString['AppName'] = trim($_POST['lrsite']);
		}
		$apiEndpoint = 'https://www.loginradius.com/api/v1/user.'.$append.'?'.http_build_query($queryString);
		// call LR api function
		$result = $loginRadiusObject -> login_radius_lr_login($apiEndpoint, $method);
		if(isset($result -> errorCode)){
			// error in login/registration
			die(
			   json_encode(
				   array(
					 'status' => 0,
					 'message' => $result -> message 
				   )
			   )
			);
		}else{
			// if new user created at LR
			if(isset($_POST['lrsite'])){
				update_option('LoginRadius_sharing_settings', array(
															   'LoginRadius_apikey' => $result[0] -> apikey,
															   'LoginRadius_sharingTheme' => 'horizontal',
															   'LoginRadius_counterTheme' => 'horizontal',
															   'LoginRadius_shareEnable' => '1',
															   'horizontal_shareEnable' => '1',
															   'horizontal_shareTop' => '1',
															   'horizontal_shareBottom' => '1',
															   'horizontal_sharehome' => '1',
															   'horizontal_sharepost' => '1',
															   'horizontal_sharepage' => '1',
															   'vertical_shareEnable' => '1',
															   'verticalSharing_theme' => 'counter_vertical',
															   'vertical_sharehome' => '1',
															   'vertical_sharepost' => '1',
															   'vertical_sharepage' => '1',
															   'sharing_offset' => '200'
															));
				die(
				   json_encode(
					   array(
						 'status' => 1,
						 'message' => 'registration successful'
					   )
				   )
				);
			}else{									// user login at LR
				// show APPs in admin
				die(
				   json_encode(
					   array(
						 'status' => 1,
						 'message' => 'login successful',
						 'result' => $result
					   )
				   )
				);
			}
		}
	}
}
add_action('wp_ajax_login_radius_lr_login', 'login_radius_lr_login');
function login_radius_save_lr_site(){
	if(isset($_POST['apikey']) && trim($_POST['apikey']) != ""){
		update_option('LoginRadius_sharing_settings', array(
															   'LoginRadius_apikey' => trim($_POST['apikey']),
															   'LoginRadius_sharingTheme' => 'horizontal',
															   'LoginRadius_counterTheme' => 'horizontal',
															   'LoginRadius_shareEnable' => '1',
															   'horizontal_shareEnable' => '1',
															   'horizontal_shareTop' => '1',
															   'horizontal_shareBottom' => '1',
															   'horizontal_sharehome' => '1',
															   'horizontal_sharepost' => '1',
															   'horizontal_sharepage' => '1',
															   'vertical_shareEnable' => '1',
															   'verticalSharing_theme' => 'counter_vertical',
															   'vertical_sharehome' => '1',
															   'vertical_sharepost' => '1',
															   'vertical_sharepage' => '1',
															   'sharing_offset' => '200'
															));
		die('success');
	}
	die('error');
}
add_action('wp_ajax_login_radius_save_lr_site', 'login_radius_save_lr_site');
?>