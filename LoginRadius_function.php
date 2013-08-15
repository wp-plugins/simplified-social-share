<?php
// plugin options
$loginRadiusSettings = get_option('LoginRadius_sharing_settings');

// social share 
if(isset($loginRadiusSettings['LoginRadius_shareEnable']) && $loginRadiusSettings['LoginRadius_shareEnable'] == '1'){ 
	require_once('LoginRadius_socialShare.php'); 
} 

/** 
 * Return Sharing code. 
 */	   
function login_radius_sharing_get_sharing_code(){
	global $loginRadiusSettings, $post;
	$sharingScript = '<script type="text/javascript">var islrsharing = true; var islrsocialcounter = true; var hybridsharing = true;</script> <script type="text/javascript" src="//share.loginradius.com/Content/js/LoginRadius.js" id="lrsharescript"></script>';
	$sharingScript .= '<script type="text/javascript">';
	// horizontal sharing theme
	if(isset($loginRadiusSettings['horizontal_shareEnable']) && $loginRadiusSettings['horizontal_shareEnable'] == "1"){
		// interface
		if($loginRadiusSettings['horizontalSharing_theme'] == "32" || $loginRadiusSettings['horizontalSharing_theme'] == "16"){
			$interface = 'horizontal';
		}elseif($loginRadiusSettings['horizontalSharing_theme'] == "single_large" || $loginRadiusSettings['horizontalSharing_theme'] == "single_small"){
			$interface = 'simpleimage';
		}elseif($loginRadiusSettings['horizontalSharing_theme'] == "counter_horizontal" || $loginRadiusSettings['horizontalSharing_theme'] == "counter_vertical"){
			// set counter variables
			$interface = 'simple';
			$isHorizontal = "true";
			// interface
			if($loginRadiusSettings['horizontalSharing_theme'] == "counter_vertical"){
				$type = 'vertical';
			}else{
				$type = 'horizontal';
			}
		}else{
			$interface = 'horizontal';
		}
		// size
		if($loginRadiusSettings['horizontalSharing_theme'] == "32" || $loginRadiusSettings['horizontalSharing_theme'] == "single_large"){
			$size = '32';
		}elseif($loginRadiusSettings['horizontalSharing_theme'] == "16" || $loginRadiusSettings['horizontalSharing_theme'] == "single_small"){
			$size = '16';
		}else{
			$size = '32';
		}
		// counter providers
		if($loginRadiusSettings['horizontalSharing_theme'] == "counter_horizontal" || $loginRadiusSettings['horizontalSharing_theme'] == "counter_vertical"){
			if(isset($loginRadiusSettings['horizontal_counter_providers']) && is_array($loginRadiusSettings['horizontal_counter_providers'])){
				$providers = implode('","', $loginRadiusSettings['horizontal_counter_providers']);
			}else{
				$providers = 'Facebook Like","Google+ +1","Pinterest Pin it","LinkedIn Share","Hybridshare';
			}
			// prepare counter script
			$sharingScript .= 'LoginRadius.util.ready(function () { $SC.Providers.Selected = ["'.$providers.'"]; $S = $SC.Interface.'.$interface.'; $S.isHorizontal = '.$isHorizontal.'; $S.countertype = \''.$type.'\'; $S.show("loginRadiusHorizontalSharing"); });';
		}else{
			// sharing providers
			if(isset($loginRadiusSettings['horizontal_rearrange_providers']) && count($loginRadiusSettings['horizontal_rearrange_providers']) > 0){
				$providers = implode('","', $loginRadiusSettings['horizontal_rearrange_providers']);
			}else{
				$providers = 'Facebook","Twitter","GooglePlus","LinkedIn","Pinterest","Print","Email';
			}
			// prepare sharing script
			$sharingScript .= 'LoginRadius.util.ready(function() { $i = $SS.Interface.'.$interface.'; $SS.Providers.Top = ["'.$providers.'"]; $u = LoginRadius.user_settings;';
			if(isset($loginRadiusSettings["LoginRadius_apikey"]) && trim($loginRadiusSettings["LoginRadius_apikey"]) != ""){
				$sharingScript .= '$u.apikey= \''.trim($loginRadiusSettings["LoginRadius_apikey"]).'\';';
			}
			$sharingScript .= '$i.size = '.$size.'; $u.sharecounttype="url"; $i.show("loginRadiusHorizontalSharing"); });';
		}
	}
	// vertical sharing interface
	if(isset($loginRadiusSettings['vertical_shareEnable']) && $loginRadiusSettings['vertical_shareEnable'] == "1"){
		// relative vertical position
		if($loginRadiusSettings['sharing_verticalPosition'] == 'top_left'){
			$position1 = 'top';
			$position2 = 'left';
		}elseif($loginRadiusSettings['sharing_verticalPosition'] == 'top_right'){
			$position1 = 'top';
			$position2 = 'right';
		}elseif($loginRadiusSettings['sharing_verticalPosition'] == 'bottom_left'){
			$position1 = 'bottom';
			$position2 = 'left';
		}else{
			$position1 = 'bottom';
			$position2 = 'right';
		}
		// interface top offset
		if($loginRadiusSettings['verticalSharing_theme'] == "counter_horizontal" || $loginRadiusSettings['verticalSharing_theme'] == "counter_vertical"){
			$sharingVariable = 'S';
		}else{
			$sharingVariable = 'i';
		}
		$offset = "";
		if(isset($loginRadiusSettings['sharing_offset']) && trim($loginRadiusSettings['sharing_offset']) != ""){
			$offset = '$'.$sharingVariable.'.top = \''.trim($loginRadiusSettings['sharing_offset']).'px\'; $'.$sharingVariable.'.'.$position2.' = \'0px\';';
		}else{
			$offset = '$'.$sharingVariable.'.'.$position1.' = \'0px\'; $'.$sharingVariable.'.'.$position2.' = \'0px\';';
		}
		
		$interface = 'Simplefloat';
		if($loginRadiusSettings['verticalSharing_theme'] == "32"){
			$size = '32';
		}elseif($loginRadiusSettings['verticalSharing_theme'] == "16"){
			$size = '16';
		}elseif($loginRadiusSettings['verticalSharing_theme'] == "counter_horizontal" || $loginRadiusSettings['verticalSharing_theme'] == "counter_vertical"){
			$interface = 'simple';
			$isHorizontal = "false";
			if($loginRadiusSettings['verticalSharing_theme'] == "counter_vertical"){
				$type = 'vertical';
			}elseif($loginRadiusSettings['verticalSharing_theme'] == "counter_horizontal"){
				$type = 'horizontal';
			}
		}else{
			$size = '16';
		}
		
		// counter providers
		if($loginRadiusSettings['verticalSharing_theme'] == "counter_horizontal" || $loginRadiusSettings['verticalSharing_theme'] == "counter_vertical"){
			if(isset($loginRadiusSettings['vertical_counter_providers']) && is_array($loginRadiusSettings['vertical_counter_providers'])){
				$providers = implode('","', $loginRadiusSettings['vertical_counter_providers']);
			}else{
				$providers = 'Facebook Like","Google+ +1","Pinterest Pin it","LinkedIn Share","Hybridshare';
			}
			// prepare counter script
			$sharingScript .= 'LoginRadius.util.ready(function () { $SC.Providers.Selected = ["'.$providers.'"]; $S = $SC.Interface.'.$interface.'; $S.isHorizontal = '.$isHorizontal.'; $S.countertype = \''.$type.'\'; '.$offset.' $S.show("loginRadiusVerticalSharing"); });';
		}else{
			// sharing providers
			if(isset($loginRadiusSettings['vertical_rearrange_providers']) && count($loginRadiusSettings['vertical_rearrange_providers']) > 0){
				$providers = implode('","', $loginRadiusSettings['vertical_rearrange_providers']);
			}else{
				$providers = 'Facebook","Twitter","GooglePlus","LinkedIn","Pinterest","Print","Email';
			}
			// prepare sharing script
			$sharingScript .= 'LoginRadius.util.ready(function() { $i = $SS.Interface.'.$interface.'; $SS.Providers.Top = ["'.$providers.'"]; $u = LoginRadius.user_settings;';
			if(isset($loginRadiusSettings["LoginRadius_apikey"]) && trim($loginRadiusSettings["LoginRadius_apikey"]) != ""){
				$sharingScript .= '$u.apikey= \''.trim($loginRadiusSettings["LoginRadius_apikey"]).'\';';
			}
			$sharingScript .= '$i.size = '.$size.'; '.$offset.' $i.show("loginRadiusVerticalSharing"); });';
		}
	}
	$sharingScript .= '</script>';
	echo $sharingScript;
}

/** 
 * Shortcode for social sharing.
 */ 
function login_radius_sharing_sharing_shortcode($params){
	extract(shortcode_atts(array(
		'style' => '',
		'type' => 'horizontal'
	), $params));
	$return = '<div ';
	// sharing theme type
	if($type == "vertical"){
		$return .= 'class="loginRadiusVerticalSharing" ';
	}else{
		$return .= 'class="loginRadiusHorizontalSharing" ';
	}
	// style 
	if($style != ""){
		$return .= 'style="'.$style.'"';
	}
	$return .= '></div>';
	return $return;
}
add_shortcode('LoginRadius_Share', 'login_radius_sharing_sharing_shortcode');

// replicate Social Login configuration to the subblogs in the multisite network
if(is_multisite() && is_main_site()){
	// replicate the social login config to the new blog created in the multisite network
	function login_radius_sharing_replicate_settings($blogId){
		global $loginRadiusSettings;
		add_blog_option($blogId, 'LoginRadius_sharing_settings', $loginRadiusSettings);
	}
	add_action('wpmu_new_blog', 'login_radius_sharing_replicate_settings');
	// update the social login options in all the old blogs
	function login_radius_sharing_update_old_blogs($oldConfig){
	    $newConfig = get_option('LoginRadius_sharing_settings');
		if(isset($newConfig['multisite_config']) && $newConfig['multisite_config'] == "1"){
			$blogs = get_blog_list(0, 'all');
			foreach($blogs as $blog){
				update_blog_option($blog['blog_id'], 'LoginRadius_sharing_settings', $newConfig);
			}
		}
	}
    add_action('update_option_LoginRadius_sharing_settings', 'login_radius_sharing_update_old_blogs');
}

/** 
 * Validate data against GUID format. 
 */	 
function loginradiusValidateKey($key){ 
	if(empty($key) || !preg_match('/^\{?[A-Z0-9]{8}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{12}\}?$/i', $key)){ 
		return false; 
	}else{ 
		return true; 
	} 
}