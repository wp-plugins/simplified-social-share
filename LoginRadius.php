<?php
/*
Plugin Name:Simplified Social Sharing  
Plugin URI: http://www.LoginRadius.com
Description: Add Social Sharing to your WordPress website.
Version: 1.9
Author: LoginRadius Team
Author URI: http://www.LoginRadius.com
License: GPL2+
*/
$loginRadiusIsBpActive = false;
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
	$page = @add_menu_page('LoginRadiusSharing', '<b>LoginRadius</b>', 'manage_options', 'LoginRadiusSharing', 'login_radius_sharing_option_page', plugins_url('images/favicon.ico', __FILE__));
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
    if (isset($_POST['login_radius_sharing_meta_nonce']) && !wp_verify_nonce($_POST['login_radius_sharing_meta_nonce'], __FILE__)){
		return $postId;
 	}
    // check user permissions
    if($_REQUEST['post_type'] == 'page'){
        if(!current_user_can('edit_page', $postId)){
			return $postId;
    	}
	}else{
        if(!current_user_can('edit_post', $postId)){
			return $postId;
    	}
	}
	if(isset($_POST['_login_radius_sharing_meta'])){
		$newData = $_POST['_login_radius_sharing_meta'];
    }else{
		$newData = 0;
	}
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
										   'horizontal_shareEnable' => '1',
										   'horizontal_shareTop' => '1',
										   'horizontal_shareBottom' => '1',
										   'horizontal_shareexcerpt' => '1',
										   'horizontal_sharepost' => '1',
										   'horizontal_sharepage' => '1',
										   'vertical_shareEnable' => '1',
										   'verticalSharing_theme' => 'counter_vertical',
										   'vertical_shareexcerpt' => '1',
										   'vertical_sharepost' => '1',
										   'vertical_sharepage' => '1',
										   'sharing_offset' => '200',
										   'delete_options' => '1',
										));
}
register_activation_hook(__FILE__, 'login_radius_sharing_activation');
/**
 * send post-registration emails to newly registered user at LR.
 */
function login_radius_send_registration_emails($email){
	global $user_ID;
	$adminName = get_user_meta($user_ID, 'first_name', true);
	// specify headers
	$headers = "MIME-Version: 1.0\n" .
	"Content-Type: text/html; charset=\"" .
	get_option('blog_charset') . "\"\n";
	// send welcome emails
	$loginRadiusSubject = 'Welcome to LoginRadius, leading social infrastructure provider';
	$loginRadiusMessage = '<html>
	<body style="margin: 0; padding: 0; background: #E3EEFA;">
	<table width="100%" cellpadding="0" cellspacing="0" border="0">
	  <tr>
		<td align="center" bgcolor="#E3EEFA" style="margin: 0;"><table cellpadding="0" cellspacing="0" border="0" align="center" width="600" style="font-family:\'proxima-nova\',sans-serif; color: #6f6f6f;">
			 <tr bgcolor="#fff;" style="background-color:#ffffff;">
			  <td height="6" bgcolor="#ffffff;" style="background-color:#ffffff;"><img src="https://www.loginradius.com/cdn/content/images/top.jpg" width="600" height="60" border="0" /></td>
			</tr>
			<tr bgcolor="#ffffff;" style="background-color:#ffffff;">
			  <td align="center" bgcolor="#ffffff;" style="background-color:#ffffff;border-bottom:1px solid #D7E1EE; padding-bottom: 20px;"><img src="https://www.loginradius.com/cdn/content/images/logo.png" alt="Logo" width="245" height="48" border="0" /></td>
			</tr>
			<tr>
			  <td height="20" bgcolor="ffffff"></td>
			</tr>
			<tr>
			  <td bgcolor="#ffffff" style="padding-left:40px;padding-right:40px;line-height:20px;padding-bottom:20px;">
			  <p style="color:#000; font-size:15px; margin: 0px; padding: 0px;">Hi '.$adminName.',<br />
				  <br />
				  Thank you for signing up with<a href="https://www.loginradius.com" target="_blank"> LoginRadius</a>. You can log into<a href="https://www.loginradius.com" target="_blank"> www.loginradius.com </a>
				  <span>with the following User Name :</span><br/>
				  <strong>Email:</strong> '.$email.'</p>
				  </p></td>
			</tr>
			<tr>
			  <td bgcolor="#ffffff" style="padding-left:40px;padding-right:40px;line-height:20px;padding-bottom:20px;">
			  <p style="color:#000; font-size:15px; margin: 0px; padding: 0px;">We will be sending another email in a few moments to explain how to get started with LoginRadius. </p></td>
			</tr>
			<tr>
			  <td bgcolor="#ffffff" style="padding-left:40px;padding-right:40px;line-height:20px;padding-bottom:20px;">
			  <p style="color:#000; font-size:15px; margin: 0px; padding: 0px;"> To stay tuned with LoginRadius updates, we highly recommend you connect with us on:<a href="https://www.facebook.com/pages/LoginRadius/119745918110130" target="_blank"> Facebook</a>, <a href="https://plus.google.com/114515662991809002122/" target="_blank">Google+</a>, <a href="https://twitter.com/LoginRadius">Twitter</a> and/or <a  href="http://www.linkedin.com/company/2748191?trk=tyah" target="_blank">Linkedin</a> </p></td>
			</tr>
			<tr>
			  <td bgcolor="#ffffff" style="padding:10px 0;"><table width="520" cellpadding="0" cellspacing="0" border="0" align="center" bgcolor="#ffffff" style="font-size: 13px;">
				  <tr>
					<td height="20"><p style="color:#000; font-size:14px; text-align: justify; padding-bottom:4px;margin: 0px;">Thank you,</p></td>
				  </tr>
				  <tr>
					<td height="20"><p style="color:#000; font-size:14px; text-align: justify; padding-bottom: 4px;margin: 0px;"><strong>LoginRadius Team</strong></p></td>
				  </tr>
				  <tr>
					<td height="20"><p style="color:#6c6c6c; font-size:14px; text-align: justify;margin: 0px; padding: 0px;"><a href="http://www.loginradius.com/" target="_blank">www.LoginRadius.com</a></p></td>
				  </tr>
				</table></td>
			</tr>
			<tr>
			  <td bgcolor="#ffffff"><table width="600" cellpadding="0" cellspacing="0" border="0" style="font-size: 11px;">
				  <tr>
					<td align="center">
					<p style="line-height: 18px; color: rgb(0, 0, 0); border-top: 1px solid rgb(215, 225, 238); padding-top: 20px; font-size: 12px;margin: 0px;">LoginRadius is <strong>Canada\'s Top 50 Startup </strong><br/>
						Partner with<strong> Mozilla, Microsoft, DynDNS, X-Cart </strong></b></p></td>
				  </tr>
				</table></td>
			</tr>
				  <tr>
			  <td bgcolor="#ffffff"><table width="600" cellpadding="0" cellspacing="0" border="0" style="font-size: 12px;">
				  <tr>
					<td align="center" style="padding-top:5px;"><table cellpadding="0" cellspacing="0" border="0">
						<tr>
						  <td><p style="line-height: 18px; color: rgb(0, 0, 0); font-size: 12px;margin: 0px; padding: 0px;"><strong>Connect to us :</strong> </p></td>
						  <td  style="padding-left:5px;"><a href="http://blog.LoginRadius.com"  target="_blank"><img src="https://www.loginradius.com/cdn/content/images/blog.png" border="0" alt="Blog" ></a></td>
						  <td  style="padding-left:5px;"><a href="https://www.facebook.com/pages/LoginRadius/119745918110130" target="_blank"><img src="https://www.loginradius.com/cdn/content/images/facebook.png" border="0" alt="Facebook" ></a></td>
						  <td  style="padding-left:5px;"><a href="https://plus.google.com/114515662991809002122/" target="_blank"><img src="https://www.loginradius.com/cdn/content/images/googleplus.png" border="0" alt="Google Plus" ></a></td>
						  <td  style="padding-left:5px;"><a href="https://twitter.com/LoginRadius"> <img src="https://www.loginradius.com/cdn/content/images/twitter.png" border="0" alt="Twitter" ></a></td>
						  <td  style="padding-left:5px;"><a  href="http://www.linkedin.com/company/2748191?trk=tyah" target="_blank"><img src="https://www.loginradius.com/cdn/content/images/linkedin.png" border="0" alt="Linkedin" ></a></td>
						</tr>
					  </table></td>
				  </tr>
				</table></td>
			</tr>
		   
	<tr>
			  <td height="6"><img src="https://www.loginradius.com/cdn/content/images/bottom.jpg" width="600" height="80" border="0" /></td>
			</tr>

		  </table></td>
	  </tr>
	</table>
	</body>
	</html>';
		wp_mail($email, $loginRadiusSubject, $loginRadiusMessage, $headers);
		$loginRadiusSubject = 'Getting started with LoginRadius - how to integrate social login';
		$loginRadiusMessage = '<html>
<body style="margin: 0; padding: 0; background: #E3EEFA;">
<table width="100%" cellpadding="0" cellspacing="0" border="0">
  <tr>
    <td align="center" bgcolor="#E3EEFA" style="margin: 0;"><table cellpadding="0" cellspacing="0" border="0" align="center" width="600" style="font-family:\'proxima-nova\',sans-serif; color: #6f6f6f;">
               <tr bgcolor="#fff;" style="background-color:#ffffff;">
          <td height="6"><img src="https://www.loginradius.com/cdn/content/images/top.jpg" width="600" height="60" border="0" /></td>
        </tr>
        <tr bgcolor="#fff;" style="background-color:#ffffff;">
          <td align="center" bgcolor="#ffffff;" style="background-color:#ffffff;border-bottom:1px solid #D7E1EE; padding-bottom: 20px;"><img src="https://www.loginradius.com/cdn/content/images/logo.png" alt="Logo" width="245" height="48" border="0" /></td>
        </tr>
        <tr>
          <td height="20" bgcolor="ffffff"></td>
        </tr>
        <tr>
          <td bgcolor="#ffffff" style="padding-left:40px;padding-right:40px;line-height:20px;padding-bottom:20px;">
		  <p style="color:#000; font-size:15px; margin: 0px; padding: 0px;">Hi '.$adminName.',<br />
              <br />
              To make sure that you successfully implement LoginRadius on your website, we want to share some important documents with you and to tell you how LoginRadius Support works. </p></td>
        </tr>
        <tr>
          <td bgcolor="#ffffff" style="padding-left:40px;padding-right:40px;line-height:20px;padding-bottom:20px;">
		  <p style="color:#000; font-size:15px; margin: 0px; padding: 0px;"> <strong>Getting started</strong> </p>
            <ul>
              <li style="color:#000; font-size:15px;"><a href="http://support.loginradius.com/customer/portal/articles/593958-how-do-i-implement-loginradius-on-my-website-" target="_blank">How to implement LoginRadius on a website?</a></li>
              <li style="color:#000; font-size:15px; padding-top: 7px;"><a href="http://support.loginradius.com/customer/portal/articles/677100-how-to-get-loginradius-api-key-and-secret" target="_blank">How to get LoginRadius API Key and Secret?</a></li>
              <li style="color:#000; font-size:15px; padding-top: 7px;"><a href="http://support.loginradius.com/customer/portal/topics/277795-id-providers-apps/articles" target="_blank">How to get API Key and Secret for various ID Providers?</a></li>
              <li style="color:#000; font-size:15px; padding-top: 7px;"><a href="http://support.loginradius.com/customer/portal/articles/594002-loginradius-add-ons" target="_blank">List of LoginRadius CMS Plugins</a></li>
              <li style="color:#000; font-size:15px; padding-top: 7px;"><a href="https://www.loginradius.com/loginradius-for-developers/loginRadius-sdks" target="_blank">List of LoginRadius Programming SDKs </a></li>
            </ul></td>
        </tr>
        <tr>
          <td bgcolor="#ffffff" style="padding-left:40px;padding-right:40px;line-height:20px;padding-bottom:20px;">
		  <p style="color:#000; font-size:15px;margin: 0px; padding: 0px; "> <strong>How does LoginRadius Support work? </strong> </p>
            <ul>
              <li style="color:#000; font-size:15px;">You can access &amp; search all of our help documents at our <a href="http://support.loginradius.com/" target="_blank">Support Centre.</a></li>
              <li style="color:#000; font-size:15px; padding-top: 7px;">For VIP customers, we provide <a href="http://support.loginradius.com/" target="_blank">24/7 email support.</a> (Click on \'Email Us\')</li>
              <li style="color:#000; font-size:15px; padding-top: 7px;">For Basic (FREE) customers, we have a <a href="http://community.loginradius.com/" target="_blank">LoginRadius Developer Community</a>.</li>
            </ul></td>
        </tr>
        <tr>
                        <td bgcolor="#ffffff" style="padding-left: 40px; padding-right: 40px; line-height: 20px;
                            padding-bottom: 0px;">
                            <p style="color: #000; font-size: 15px;">

                            To stay tuned with LoginRadius updates, we highly recommend you connect with us on:  <a target="_blank"
                                    href="https://www.facebook.com/pages/LoginRadius/119745918110130">Facebook, 
                                </a><a target="_blank" href="https://plus.google.com/114515662991809002122/">Google+</a>,
                                <a target="_blank" href="https://twitter.com/LoginRadius">Twitter</a> and <a
                                    target="_blank" href="http://www.linkedin.com/company/2748191?trk=tyah">LinkedIn</a>
                            </p>
                        </td>
                    </tr>
		<tr>
		  <td bgcolor="#ffffff" style="padding:10px 0;"><table width="520" cellpadding="0" cellspacing="0" border="0" align="center" bgcolor="#ffffff" style="font-size: 13px;">
			  <tr>
				<td height="20"><p style="color:#000; font-size:14px; text-align: justify; padding-bottom:4px;margin: 0px;">Thank you,</p></td>
			  </tr>
			  <tr>
				<td height="20"><p style="color:#000; font-size:14px; text-align: justify; padding-bottom: 4px;margin: 0px;"><strong>LoginRadius Team</strong></p></td>
			  </tr>
			  <tr>
				<td height="20"><p style="color:#6c6c6c; font-size:14px; text-align: justify;margin: 0px; padding: 0px;"><a href="http://www.LoginRadius.com/?utm_source=newsletter&utm_medium=email&utm_campaign=analytics" target="_blank">www.LoginRadius.com</a></p></td>
			  </tr> 
			  <tr>

				<td height="20">
               </td>
			  </tr>
			</table></td>
		</tr>
		<tr>
		  <td bgcolor="#ffffff"><table width="600" cellpadding="0" cellspacing="0" border="0" style="font-size: 11px;">
			  <tr>
				<td align="center">
				<p style="line-height: 18px; color: rgb(0, 0, 0); border-top: 1px solid rgb(215, 225, 238); padding-top: 20px; font-size: 12px;margin: 0px;">LoginRadius is among <strong>Canada\'s Top 50 Startup </strong><br/>
					Partner with<strong> Mozilla, Microsoft, DynDNS, X-Cart </strong></b></p></td>
			  </tr>
			</table></td>
		</tr>
			  <tr>
		  <td bgcolor="#ffffff"><table width="600" cellpadding="0" cellspacing="0" border="0" style="font-size: 12px;">
			  <tr>
				<td align="center" style="padding-top:5px;"><table cellpadding="0" cellspacing="0" border="0">
					<tr>
					  <td><p style="line-height: 18px; color: rgb(0, 0, 0); font-size: 12px;margin: 0px; padding: 0px;"><strong>Connect to us :</strong> </p></td>
					  <td  style="padding-left:5px;"><a href="http://blog.LoginRadius.com"  target="_blank"><img src="https://www.loginradius.com/cdn/content/images/blog.png" border="0" alt="Blog" ></a></td>
					  <td  style="padding-left:5px;"><a href="https://www.facebook.com/pages/LoginRadius/119745918110130" target="_blank"><img src="https://www.loginradius.com/cdn/content/images/facebook.png" border="0" alt="Facebook" ></a></td>
					  <td  style="padding-left:5px;"><a href="https://plus.google.com/114515662991809002122/" target="_blank"><img src="https://www.loginradius.com/cdn/content/images/googleplus.png" border="0" alt="Google Plus" ></a></td>
					  <td  style="padding-left:5px;"><a href="https://twitter.com/LoginRadius"> <img src="https://www.loginradius.com/cdn/content/images/twitter.png" border="0" alt="Twitter" ></a></td>
					  <td  style="padding-left:5px;"><a  href="http://www.linkedin.com/company/2748191?trk=tyah" target="_blank"><img src="https://www.loginradius.com/cdn/content/images/linkedin.png" border="0" alt="Linkedin" ></a></td>
					</tr>
				  </table></td>
			  </tr>
			</table></td>
		</tr>   
		<tr>
		  <td height="6"><img src="https://www.loginradius.com/cdn/content/images/bottom.jpg" width="600" height="80" border="0" /></td>
		</tr>
	  </table></td>
		</tr>
		</table>
	</body>
	</html>';
	wp_mail($email, $loginRadiusSubject, $loginRadiusMessage, $headers);
}
/**
 * call LR api to login/register user.
 */
function login_radius_lr_login(){
	$loginRadiusObject = new LoginRadiusSharing();
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
			if(!isset($result[0] -> apikey)){
				// error in login/registration
				die(
				   json_encode(
					   array(
						 'status' => 0,
						 'message' => 'Unexpected error occurred' 
					   )
				   )
				);
			}
			// if new user created at LR
			if(isset($_POST['lrsite'])){
				$loginRadiusSettings = get_option('LoginRadius_sharing_settings');
				$loginRadiusSettings['LoginRadius_apikey'] = $result[0] -> apikey;
				update_option('LoginRadius_sharing_settings', $loginRadiusSettings);
				// send post registration emails
				login_radius_send_registration_emails(trim($_POST["UserName"]));
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
	$loginRadiusSettings = get_option('LoginRadius_sharing_settings');
	if(isset($_POST['apikey']) && trim($_POST['apikey']) != ""){
		$loginRadiusSettings['LoginRadius_apikey'] = trim($_POST['apikey']);
		update_option('LoginRadius_sharing_settings', $loginRadiusSettings);
		die('success');
	}
	die('error');
}
add_action('wp_ajax_login_radius_save_lr_site', 'login_radius_save_lr_site');

function login_radius_sharing_bp_check(){
    global $loginRadiusIsBpActive;
	$loginRadiusIsBpActive = true;
}
add_action( 'bp_include', 'login_radius_sharing_bp_check' );
?>