<?php
/** 
 * Validate plugin options.
 */ 
function login_radius_sharing_validate_options($loginRadiusSettings){
	$loginRadiusSettings['LoginRadius_apikey'] = $loginRadiusSettings['LoginRadius_apikey'];
	$loginRadiusSettings['LoginRadius_sharingTitle'] = $loginRadiusSettings['LoginRadius_sharingTitle'];
	return $loginRadiusSettings;
}

/** 
 * Display options page.
 */ 
function login_radius_sharing_option_page(){
	global $loginRadiusIsBpActive;
	$loginRadiusSettings = get_option('LoginRadius_sharing_settings');
	?>
  	<script src="http://code.jquery.com/ui/1.10.0/jquery-ui.js"></script>
	<script type="text/javascript">var islrsharing = true; var islrsocialcounter = true;</script>
	<script type="text/javascript" src="//share.loginradius.com/Content/js/LoginRadius.js" id="lrsharescript"></script>
	<script type="text/javascript">
	// get trim() working in IE
	if(typeof String.prototype.trim !== 'function'){
		String.prototype.trim = function(){
			return this.replace(/^\s+|\s+$/g, '');
		}
	}
	function loginRadiusAdminUI2(){
		// get selected horizontal sharing providers
		<?php
		if(isset($loginRadiusSettings['horizontal_rearrange_providers']) && is_array($loginRadiusSettings['horizontal_rearrange_providers']) && count($loginRadiusSettings['horizontal_rearrange_providers']) > 0){
			?>
			var selectedHorizontalSharingProviders = <?php echo json_encode($loginRadiusSettings['horizontal_rearrange_providers']); ?>;
			<?php
		}else{
			?>
			var selectedHorizontalSharingProviders = ["Facebook","Twitter","Googleplus","Linkedin","Pinterest","Email","Print"];
			<?php
		}
		// get selected vertical sharing providers
		if(isset($loginRadiusSettings['vertical_rearrange_providers']) && is_array($loginRadiusSettings['vertical_rearrange_providers']) && count($loginRadiusSettings['vertical_rearrange_providers']) > 0){
			?>
			var selectedVerticalSharingProviders = <?php echo json_encode($loginRadiusSettings['vertical_rearrange_providers']); ?>;
			<?php
		}else{
			?>
			var selectedVerticalSharingProviders = ["Facebook","Twitter","Googleplus","Linkedin","Pinterest","Email","Print"];
			<?php
		}
		// get selected horizontal counter providers
		if(isset($loginRadiusSettings['horizontal_counter_providers']) && is_array($loginRadiusSettings['horizontal_counter_providers']) && count($loginRadiusSettings['horizontal_counter_providers']) > 0){
			?>
			var selectedHorizontalCounterProviders = <?php echo json_encode($loginRadiusSettings['horizontal_counter_providers']); ?>;
			<?php
		}else{
			?>
			var selectedHorizontalCounterProviders = ["Facebook Like","Google+ +1","Pinterest Pin it","LinkedIn Share","Hybridshare"];
			<?php
		}
		// get selected vertical counter providers
		if(isset($loginRadiusSettings['vertical_counter_providers']) && is_array($loginRadiusSettings['vertical_counter_providers']) && count($loginRadiusSettings['vertical_counter_providers']) > 0){
			?>
			var selectedVerticalCounterProviders = <?php echo json_encode($loginRadiusSettings['vertical_counter_providers']); ?>;
			<?php
		}else{
			?>
			var selectedVerticalCounterProviders = ["Facebook Like","Google+ +1","Pinterest Pin it","LinkedIn Share","Hybridshare"];
			<?php
		}
		?>
		var loginRadiusSharingHtml = "";
		var checked = false;
		// prepare HTML to be shown as Horizontal Sharing Providers
		for(var i = 0; i < $SS.Providers.More.length; i++){
			checked = loginRadiusCheckElement(selectedHorizontalSharingProviders, $SS.Providers.More[i]);
			loginRadiusSharingHtml += '<div class="loginRadiusProviders"><input type="checkbox" onchange="loginRadiusHorizontalSharingLimit(this); loginRadiusRearrangeProviderList(this, \'Horizontal\')" ';
			if(checked){
				loginRadiusSharingHtml += 'checked="'+checked+'" ';
			}
			loginRadiusSharingHtml += 'name="LoginRadius_sharing_settings[horizontal_sharing_providers][]" value="'+$SS.Providers.More[i]+'"> <label>'+$SS.Providers.More[i]+'</label></div>';
		}
		// show horizontal sharing providers list
		jQuery('#login_radius_horizontal_sharing_providers_container').html(loginRadiusSharingHtml);
		
		loginRadiusSharingHtml = "";
		checked = false;
		// prepare HTML to be shown as Vertical Sharing Providers
		for(var i = 0; i < $SS.Providers.More.length; i++){
			checked = loginRadiusCheckElement(selectedVerticalSharingProviders, $SS.Providers.More[i]);
			loginRadiusSharingHtml += '<div class="loginRadiusProviders"><input type="checkbox" onchange="loginRadiusVerticalSharingLimit(this); loginRadiusRearrangeProviderList(this, \'Vertical\')" ';
			if(checked){
				loginRadiusSharingHtml += 'checked="'+checked+'" ';
			}
			loginRadiusSharingHtml += 'name="LoginRadius_sharing_settings[vertical_sharing_providers][]" value="'+$SS.Providers.More[i]+'"> <label>'+$SS.Providers.More[i]+'</label></div>';
		}
		// show vertical sharing providers list
		jQuery('#login_radius_vertical_sharing_providers_container').html(loginRadiusSharingHtml);
		
		loginRadiusSharingHtml = "";
		checked = false;
		// prepare HTML to be shown as Horizontal Counter Providers
		for(var i = 0; i < $SC.Providers.All.length; i++){
			checked = loginRadiusCheckElement(selectedHorizontalCounterProviders, $SC.Providers.All[i]);
			loginRadiusSharingHtml += '<div class="loginRadiusCounterProviders"><input type="checkbox" ';
			if(checked){
				loginRadiusSharingHtml += 'checked="'+checked+'" ';
			}
			loginRadiusSharingHtml += 'name="LoginRadius_sharing_settings[horizontal_counter_providers][]" value="'+$SC.Providers.All[i]+'"> <label>'+$SC.Providers.All[i]+'</label></div>';
		}
		// show horizontal counter providers list
		jQuery('#login_radius_horizontal_counter_providers_container').html(loginRadiusSharingHtml);
		
		loginRadiusSharingHtml = "";
		checked = false;
		// prepare HTML to be shown as Vertical Counter Providers
		for(var i = 0; i < $SC.Providers.All.length; i++){
			checked = loginRadiusCheckElement(selectedVerticalCounterProviders, $SC.Providers.All[i]);
			loginRadiusSharingHtml += '<div class="loginRadiusCounterProviders"><input type="checkbox" ';
			if(checked){
				loginRadiusSharingHtml += 'checked="'+checked+'" ';
			}
			loginRadiusSharingHtml += 'name="LoginRadius_sharing_settings[vertical_counter_providers][]" value="'+$SC.Providers.All[i]+'"> <label>'+$SC.Providers.All[i]+'</label></div>';
		}
		// show vertical counter providers list
			jQuery('#login_radius_vertical_counter_providers_container').html(loginRadiusSharingHtml);
		<?php
		if($loginRadiusSettings['LoginRadius_apikey'] == ''){
			?>
			// bind LR login/register API call to the form button
			document.getElementById('loginRadiusSubmit').onclick = function(){
				loginRadiusLRLogin(this);
			};
			<?php
		}
		?>
	};
	// confirm password validation
	function loginRadiusConfirmPasswordValidate(){
		var loginRadiusNotificationDiv = document.getElementById('loginRadiusMessage');
		if(document.getElementById('password').value.trim() != document.getElementById('confirm_password').value.trim()){
			loginRadiusNotificationDiv.innerHTML = 'Passwords do not match.';
			loginRadiusNotificationDiv.style.color = 'red';
			return false;
		}else{
			loginRadiusNotificationDiv.innerHTML = '';
			return true;
		}
	}
	// ajax for user registration/login to LR.com. Password validation
	function loginRadiusLRLogin(elem){
		// form validation
		var email = jQuery('#username').val().trim();
		if(email == "" || jQuery('#password').val().trim() == "" || (jQuery('#lrsiteRow').css('display') != 'none' && jQuery('#lrsite').val().trim() == "")){
			jQuery('#loginRadiusMessage').html('<?php _e('Please fill all the fields.', 'LoginRadius') ?>').css('color', 'red');
			return;
		}
		// email validation
		var atPosition = email.indexOf("@");
		var dotPosition = email.lastIndexOf(".");
		if(atPosition < 1 || dotPosition < atPosition+2 || dotPosition+2>=email.length){
			jQuery('#loginRadiusMessage').html('<?php _e('Please enter a valid email address.', 'LoginRadius') ?>').css('color', 'red');
			return;
		}
		//password length validation
		if(jQuery('#password').val().length < 6 || jQuery('#password').val().length > 32 ) {
			jQuery('#loginRadiusMessage').html('<?php _e('Password length should be minimum of 6 characters and maximum 32 characters', 'LoginRadius') ?>').css('color', 'red');
			return;
		}
		// confirm password validation
		if(jQuery('#confirmPasswordRow').css('display') != 'none' && !loginRadiusConfirmPasswordValidate()){
			return;
		}
		//Site Name validation
		if (jQuery('#lrsiteRow').css('display') != 'none' && jQuery('#lrsite').val().match(/[.]/g)) {
			jQuery('#loginRadiusMessage').html('<?php _e('Symbol "." not allowed in LoginRadius Site name.', 'LoginRadius') ?>').css('color', 'red');
			return;
		}
		if (jQuery('#lrsiteRow').css('display') != 'none' && jQuery('#lrsite').val().match(/[_]/g)) {
			jQuery('#loginRadiusMessage').html('<?php _e('Symbol "_" not allowed in LoginRadius Site name.', 'LoginRadius') ?>').css('color', 'red');
			return;
		}
		if(jQuery('#lrsiteRow').css('display') != 'none' && jQuery('#lrsite').val().length < 4 ) {
			jQuery('#loginRadiusMessage').html('<?php _e('Site name must be longer than three characters.', 'LoginRadius') ?>').css('color', 'red');
			return;
		}
		 var url = "https://" + jQuery('#lrsite').val().trim() + ".hub.loginradius.com";
         var regularExpression = "^(ht|f)tp(s?)\:\/\/[0-9a-zA-Z]([-.\w]*[0-9a-zA-Z])*(:(0-9)*)*(\/?)([a-zA-Z0-9\-\.\?\,\'\/\\\+&%\$#_]*)?$";
		if (jQuery('#lrsiteRow').css('display') != 'none' && !url.match(regularExpression)) {
			jQuery('#loginRadiusMessage').html('<?php _e('Site Name is not valid.', 'LoginRadius') ?>').css('color', 'red');
			return;
		}
		// processing message
		jQuery('#loginRadiusMessage').html('<img width="20" height="20" src="<?php echo plugins_url('images/loading_icon.gif', __FILE__); ?>" style="float:left;margin-right: 5px;" /><span style="color:blue; width:auto"><?php _e('Please wait. This may take a few minutes', 'LoginRadius') ?>...</span>');
		// create data object
		var dataObject = {
			action: 'login_radius_lr_login', 
			UserName: jQuery('#username').val().trim(),
			password: jQuery('#password').val().trim()
		};
		if(jQuery('#lrsiteRow').css('display') != 'none'){
			dataObject.lrsite = jQuery('#lrsite').val().trim();
		}
		jQuery.ajax({
		  type: 'POST',
		  url: '<?php echo get_admin_url() ?>admin-ajax.php',
		  data: dataObject,
		  dataType: 'json',
		  success: function(data, textStatus, XMLHttpRequest){
			if(data.status == 0){
				// show the message
				jQuery('#loginRadiusMessage').html(data.message).css('color', 'red');
			}else if(data.status == 1 && data.message == 'registration successful'){
				// refresh the page
				location.href = location.href;
			}else if(data.status == 1 && data.message == 'login successful'){
				// display the app list
				var html = '<h3 id="loginRadiusFormTitle">Site Selection</h3><table class="form-table"><tbody><tr><th><label for="lrSites"><?php _e('Select a LoginRadius site', 'LoginRadius') ?></label></th><td><select id="lrSites"><option value="">--Select a Site--</option>';
				for(var i = 0; i < data.result.length; i++){
					html += '<option value="'+data.result[i].apikey+'">'+data.result[i].appName+'</option>';
				}
				html += '</select>';
				html += '</td></tr><tr><td><input type="button" id="loginRadiusLRSiteSave" class="button button-primary" value="<?php _e('Save', 'LoginRadius') ?>" /></td><td><div id="loginRadiusMessage"></div></td></tr>';
				jQuery('#loginRadiusLoginForm').html(html);
				document.getElementById('loginRadiusLRSiteSave').onclick = function(){
					loginRadiusSaveLRSite();
				};
			}
		  },
		  error: function(a, b, c){
		  	alert(JSON.stringify(a, null, 4)+"\n"+b+"\n"+c)
		  }
		});
	}
	// save selected LR Site API Key
	function loginRadiusSaveLRSite(){
		if(jQuery('#lrSites').val().trim() == ""){
			jQuery('#loginRadiusMessage').html('<?php _e('Please select a site', 'LoginRadius') ?>').css('color', 'red');
			return;
		}
		// processing message
		jQuery('#loginRadiusMessage').html('<img width="20" height="20" src="<?php echo plugins_url('images/loading_icon.gif', __FILE__); ?>" style="float:left;margin-right: 5px;" /><span style="color:blue; width:auto"><?php _e('Please wait. This may take a few minutes', 'LoginRadius') ?>...</span>');
		jQuery.ajax({
		  type: 'POST',
		  url: '<?php echo get_admin_url() ?>admin-ajax.php',
		  data: {
			  action: 'login_radius_save_lr_site',
			  apikey: jQuery('#lrSites').val().trim()
		  },
		  success: function(data, textStatus, XMLHttpRequest){
			if(data == "success"){
				// refresh the page
				location.href = location.href;
			}else{
				// unexpected error
				jQuery('#loginRadiusMessage').html('<?php _e('Unexpected error occurred', 'LoginRadius') ?>').css('color', 'red');
			}
		  },
		  error: function(a, b, c){
		  	alert(JSON.stringify(a, null, 4)+"\n"+b+"\n"+c)
		  }
		});
	}
	</script>
	<div class="wrapper">
	<form action="options.php" method="post">
		<?php settings_fields('LoginRadius_sharing_setting_options'); ?>
		<div class="header_div">
		<h2>LoginRadius <?php _e('Simplified Social Sharing Settings', 'LoginRadius') ?></h2>
		<fieldset style="margin-right:13px; height:190px; background-color:#EAF7FF; border-color:rgb(195, 239, 250); padding-bottom:10px; width:751px">
		<h4 style="color:#000"><strong>Thank you for installing LoginRadius Simplified Social Share plugin!</strong></h4>
		<p>
			<a href="https://www.loginradius.com/">LoginRadius</a> provides <a target="_blank" href="https://www.loginradius.com/loginradius/product-overview#SocialLoginTab">Social Login</a>, <a target="_blank" href="https://www.loginradius.com/loginradius/product-overview#SocialSharingTab">Social Share</a>, <a target="_blank" href="https://www.loginradius.com/loginradius/product-overview#FriendsInviteTab">Friend Invite</a>, <a target="_blank" href="https://www.loginradius.com/loginradius/product-overview#UserProfileDataTab">User Social Profile Data</a>, <a target="_blank" href="https://www.loginradius.com/loginradius/product-overview#OnlineTab">User Profile Access</a>, <a target="_blank" href="https://www.loginradius.com/loginradius/product-overview#SingleSignOnTab">Single Sign-on</a> and <a target="_blank" href="https://www.loginradius.com/loginradius/product-overview#SocialAnalyticsTab">Social Analytics</a> as single Unified API.
		</p>
		<p>
			We also have ready to use plugins for <a href="https://www.loginradius.com/loginradius-for-developers/loginRadius-cms#magentoextension" target="_blank">Magento</a>, <a href="https://www.loginradius.com/loginradius-for-developers/loginRadius-cms#joomlaextension" target="_blank">Joomla</a>, <a href="https://www.loginradius.com/loginradius-for-developers/loginRadius-cms#drupalmodule" target="_blank">Drupal</a>, <a href="https://www.loginradius.com/loginradius-for-developers/loginRadius-cms#vBulletinplugin" target="_blank">vBulletin</a>, <a href="https://www.loginradius.com/loginradius-for-developers/loginRadius-cms#vanillaaddons" target="_blank">VanillaForum</a>, <a href="https://www.loginradius.com/loginradius-for-developers/loginRadius-cms#osCommerceaddons" target="_blank">OSCommerce</a>, <a href="https://www.loginradius.com/loginradius-for-developers/loginRadius-cms#prestashopmodule" target="_blank">PrestaShop</a>, <a href="https://www.loginradius.com/loginradius-for-developers/loginRadius-cms#xcartextension" target="_blank">X-Cart</a>, <a href="https://www.loginradius.com/loginradius-for-developers/loginRadius-cms#zencartplugin" target="_blank">Zen-Cart</a> and <a href="https://www.loginradius.com/loginradius-for-developers/loginRadius-cms#dotnetnukemodule" target="_blank">DotNetNuke</a>!
		</p>
		</fieldset>
		
		<fieldset style="width:25%; background-color: rgb(231, 255, 224); border: 1px solid rgb(191, 231, 176); padding-bottom:6px; height:195px; width:255px">
		<div style="margin:5px 0">
			<strong>Plugin Version:</strong> 1.9<br/>
			<strong>Author:</strong> LoginRadius<br/>
			<strong>Website:</strong> <a href="https://www.loginradius.com" target="_blank">www.loginradius.com</a> <br/>
			<strong>Community:</strong> <a href="http://community.loginradius.com" target="_blank">community.loginradius.com</a> <br/>
			To receive updates on new features, future releases and other updates, please connect with us on
			Facebook-
			<div>
				<div style="float:left">
					<iframe rel="tooltip" scrolling="no" frameborder="0" allowtransparency="true" style="border: none; overflow: hidden; width: 46px; height: 61px; margin-right:10px" src="//www.facebook.com/plugins/like.php?app_id=194112853990900&amp;href=http%3A%2F%2Fwww.facebook.com%2Fpages%2FLoginRadius%2F119745918110130&amp;send=false&amp;layout=box_count&amp;width=90&amp;show_faces=false&amp;action=like&amp;colorscheme=light&amp;font=arial&amp;height=90" data-original-title="Like us on Facebook"></iframe>
				</div>
				<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
			</div>
		</div>
		</fieldset>
		
		</div>
		<div class="clr"></div>
		<?php
		if(isset($loginRadiusSettings['LoginRadius_apikey']) && trim($loginRadiusSettings['LoginRadius_apikey']) != "" && !loginradiusValidateKey(trim($loginRadiusSettings['LoginRadius_apikey']))){
			?>
			<div class="error">
			<p>
				<?php _e('Your LoginRadius API key is not valid, please correct it or contact LoginRadius support at <b><a href ="http://www.loginradius.com" target = "_blank">www.LoginRadius.com</a></b>', 'LoginRadius'); ?>
			</p>
			</div>
			<?php
		}
		?>
		<!-- Login, registration form at the installation of the plugin -->
		<?php
		// if plugin option doesn't exist in db, show the login/registration options
		if($loginRadiusSettings['LoginRadius_apikey'] == ""){
			?>
			<div id="loginRadiusLoginForm">
				<h3 id="loginRadiusFormTitle">Register to LoginRadius</h3>
				<form id="loginRadiusLRForm">
				<table class="form-table">
					<tbody>
					<tr>
						<th><label for="username"><?php _e('Email', 'LoginRadius') ?></label></th>
						<td>
							<input type="text" name="username" id="username" class="regular-text">
						</td>
					</tr>
					<tr>
						<th><label for="password"><?php _e('Password', 'LoginRadius') ?></label></th>
						<td><input type="password" style="float: left; border: #acacac 1px solid; border-radius: 4px; width: 276px;" name="password" id="password" value="" class="regular-text"></td>
					</tr>
					<tr id="confirmPasswordRow">
						<th><label for="confirm_password"><?php _e('Confirm Password', 'LoginRadius') ?></label></th>
						<td><input onblur="loginRadiusConfirmPasswordValidate()" type="password" style="float: left; border: #acacac 1px solid; border-radius: 4px; width: 276px;" name="confirm_password" id="confirm_password" value="" class="regular-text"></td>
					</tr>
					<tr id="lrsiteRow">
						<th><label for="lrsite"><?php _e('LoginRadius Site', 'LoginRadius') ?></label></th>
						<td><input type="text" name="lrsite" id="lrsite" value="" class="regular-text"></td>
					</tr>
					<tr id="lrSiteMessageRow">
						<th></th>
						<td><span style="font-size:11px">(Your LoginRadius Site Name must not include periods ('.') or any other special symbols. Just use letters (A-Z), digits (0-9) or dash ( - )!)</span></td>
					</tr>
					<tr>
						<td><input type="button" id="loginRadiusSubmit" class="button button-primary" value="<?php _e('Register', 'LoginRadius') ?>" /></td>
						<td><div id="loginRadiusMessage"></div></td>
					</tr>
					<tr>
						<td colspan='2'>
						<a style="text-decoration:none" id="loginRadiusToggleFormLink" href="javascript:void(0)" onclick="loginRadiusToggleForm('login')"><?php _e('Already have an account?', 'LoginRadius') ?></a><br/>
						<a style="text-decoration:none" target="_blank" href="https://www.loginradius.com/login/forgotten" onclick="loginRadiusToggleForm('login')"><?php _e('Forgot your password?', 'LoginRadius') ?></a>
						</td>
					</tr>
					</tbody>
				</table>
				</form>
			</div>
			<?php
		}else{
			?>
			<!-- Login, registration form at the installation of the plugin end -->
			<div class="metabox-holder columns-2" id="post-body">
				<div class="menu_div" id="tabs">
					<h2 class="nav-tab-wrapper" style="height:36px">
					<ul>
						<li style="margin-left:9px"><a style="margin:0; height:23px" class="nav-tab" href="#tabs-1"><?php _e('API Settings', 'LoginRadius') ?></a></li>
						<li><a style="margin:0; height:23px" class="nav-tab" href="#tabs-2"><?php _e('Social Sharing', 'LoginRadius') ?></a></li>
						<li style="float:right; margin-right:8px"><a style="margin:0; height:23px" class="nav-tab" href="#tabs-3"><?php _e('Help', 'LoginRadius') ?></a></li>
					</ul>
					</h2>
				
					<div class="menu_containt_div" id="tabs-1">
						<div class="stuffbox">
						<h3><label><?php _e('LoginRadius API Key', 'LoginRadius');?>
						<a style="text-decoration:none" target="_blank" href="http://support.loginradius.com/customer/portal/articles/677100-how-to-get-loginradius-api-key-and-secret"><?php _e('(How to get it?)', 'LoginRadius') ?></a>
						</label></h3>
						<div class="inside">
							<table width="100%" border="0" cellspacing="0" cellpadding="0" class="form-table editcomment menu_content_table">
							<tr>
							<td><label style="float:left; width:100px; margin-top:2px; font-weight:bold"><?php _e('API Key', 'LoginRadius');?></label>
							<input type="text" id="login_radius_api_key" name="LoginRadius_sharing_settings[LoginRadius_apikey]" value="<?php echo (isset($loginRadiusSettings['LoginRadius_apikey']) ? htmlspecialchars ($loginRadiusSettings['LoginRadius_apikey']) : ''); ?>" autofill='off' autocomplete='off'  />										
							</td>
							</tr>
							</table>
						</div>
						</div>
					</div>
					<div class="menu_containt_div" id="tabs-2">
						<div class="stuffbox">
						<h3><label><?php _e('Basic Social Sharing Settings', 'LoginRadius'); ?></label></h3>
						<div class="inside">
						<table width="100%" border="0" cellspacing="0" cellpadding="0" class="form-table editcomment menu_content_table">
					<tr>
					<td>
					<div class="loginRadiusQuestion">
					<?php _e("Which page do you want to get shared when multiple social sharing interfaces are shown on a page/home page?", 'LoginRadius'); ?>
					</div>
					<div class="loginRadiusYesRadio">
					<input type="radio" name="LoginRadius_sharing_settings[sharingCount]" value="website" <?php echo isset($loginRadiusSettings['sharingCount']) && $loginRadiusSettings['sharingCount'] == 'website' ? 'checked' : ''; ?>/> <?php _e("Page where all the social sharing interfaces are shown", 'LoginRadius') ?>
					</div><br/>
					<input type="radio" name="LoginRadius_sharing_settings[sharingCount]" value="page" <?php echo !isset($loginRadiusSettings['sharingCount']) || $loginRadiusSettings['sharingCount'] == 'page' ? 'checked' : ''; ?>/> <?php _e("Individual page associated with that Social sharing interface", 'LoginRadius') ?>
					</td>
					</tr>
					</table>
					</div>
					</div>
					
					<div class="stuffbox">
					<h3><label><?php _e('Social Sharing Theme Selection', 'LoginRadius'); ?></label></h3>
					<div class="inside">
					<table width="100%" border="0" cellspacing="0" cellpadding="0" class="form-table editcomment menu_content_table">
					<tr>
					<td>
					<div class="loginRadiusQuestion">
					<?php _e("What Social Sharing widget theme would you like to use across your website? (Horizontal and Vertical themes can be enabled simultaneously)", 'LoginRadius'); ?>
					</div>
					<br />
					<a href="javascript:void(0)" style="text-decoration:none" onclick="document.getElementById('login_radius_vertical').style.display = 'none'; document.getElementById('login_radius_horizontal').style.display = 'block';">Horizontal</a> | <a href="javascript:void(0)" style="text-decoration:none" onclick="document.getElementById('login_radius_vertical').style.display = 'block'; document.getElementById('login_radius_horizontal').style.display = 'none';">Vertical</a>
					</td>
					</tr>
					<tr id="login_radius_horizontal">
					<td>
					<span class="lrsharing_spanwhite"></span>
					<span class="lrsharing_spangrey"></span>
					<div style="border:1px solid #ccc; padding:10px; border-radius:5px">
						<div class="loginRadiusQuestion">
							<?php _e('Do you want to enable Horizontal Social Sharing at your website?', 'LoginRadius'); ?>
						</div>
						<div class="loginRadiusYesRadio">
							<input type="radio" name="LoginRadius_sharing_settings[horizontal_shareEnable]" value="1" <?php echo !isset($loginRadiusSettings['horizontal_shareEnable']) || $loginRadiusSettings['horizontal_shareEnable'] == '1' ? 'checked="checked"' : '' ?> /> <?php _e('Yes', 'LoginRadius') ?>
						</div>
						<input type="radio" name="LoginRadius_sharing_settings[horizontal_shareEnable]" value="0" <?php echo isset($loginRadiusSettings['horizontal_shareEnable']) && $loginRadiusSettings['horizontal_shareEnable'] == '0' ? 'checked="checked"' : '' ?> /> <?php _e('No', 'LoginRadius') ?>
						<div class="loginRadiusBorder2"></div>
						
						<div class="loginRadiusQuestion" style="margin-top:10px">
							<?php _e('Choose a Sharing theme', 'LoginRadius'); ?>
						</div>
						<div class="login_radius_select_row" style="opacity: 1;">
							<span class="radio">
								<input style="margin-top:12px" <?php echo (isset($loginRadiusSettings['horizontalSharing_theme']) && $loginRadiusSettings['horizontalSharing_theme'] == '32') || !isset($loginRadiusSettings['horizontalSharing_theme']) ? 'checked="checked"' : '' ?> type="radio" checked="checked" id="login_radius_sharing_top_32" name="LoginRadius_sharing_settings[horizontalSharing_theme]" value="32" onclick="document.getElementById('login_radius_horizontal_rearrange_container').style.display = 'block'; document.getElementById('login_radius_horizontal_sharing_providers_container').style.display = 'block'; document.getElementById('login_radius_horizontal_counter_providers_container').style.display = 'none'; document.getElementById('login_radius_horizontal_providers_container').style.display = 'block';"  />
							</span>
							<label for="login_radius_sharing_top_32">
								<img src="<?php echo plugins_url('images/sharing/horizonSharing32.png', __FILE__); ?>" align="left" />
							</label>
							<div class="clear"></div>
						</div>
						<div class="login_radius_select_row" style="opacity: 1;">
							<span class="radio">
								<input <?php echo isset($loginRadiusSettings['horizontalSharing_theme']) && $loginRadiusSettings['horizontalSharing_theme'] == '16' ? 'checked="checked"' : '' ?> type="radio" name="LoginRadius_sharing_settings[horizontalSharing_theme]" id="login_radius_sharing_top_16" value="16" onclick="document.getElementById('login_radius_horizontal_rearrange_container').style.display = 'block'; document.getElementById('login_radius_horizontal_sharing_providers_container').style.display = 'block'; document.getElementById('login_radius_horizontal_counter_providers_container').style.display = 'none'; document.getElementById('login_radius_horizontal_providers_container').style.display = 'block';" />
							</span>
							<label for="login_radius_sharing_top_16">
								<img src="<?php echo plugins_url('images/sharing/horizonSharing16.png', __FILE__); ?>" />
							</label>
							<div class="clear"></div>
						</div>
						<div class="login_radius_select_row" style="opacity: 1;">
							<span class="radio">
								<input style="margin-top:6px" <?php echo isset($loginRadiusSettings['horizontalSharing_theme']) && $loginRadiusSettings['horizontalSharing_theme'] == 'single_large' ? 'checked="checked"' : '' ?> type="radio" name="LoginRadius_sharing_settings[horizontalSharing_theme]" value="single_large" id="login_radius_sharing_top_slarge" onclick="document.getElementById('login_radius_horizontal_rearrange_container').style.display = 'none'; document.getElementById('login_radius_horizontal_providers_container').style.display = 'none';" />
							</span>
							<label for="login_radius_sharing_top_slarge">
								<img src="<?php echo plugins_url('images/sharing/single-image-theme-large.png', __FILE__); ?>" />
							</label>
							<div class="clear"></div>
						</div>
						<div class="login_radius_select_row" style="opacity: 1;">
							<span class="radio">
								<input <?php echo isset($loginRadiusSettings['horizontalSharing_theme']) && $loginRadiusSettings['horizontalSharing_theme'] == 'single_small' ? 'checked="checked"' : '' ?> type="radio" name="LoginRadius_sharing_settings[horizontalSharing_theme]" id="login_radius_sharing_top_ssmall" value="single_small" onclick="document.getElementById('login_radius_horizontal_rearrange_container').style.display = 'none'; document.getElementById('login_radius_horizontal_providers_container').style.display = 'none';" />
							</span>
							<label for="login_radius_sharing_top_ssmall">
								<img src="<?php echo plugins_url('images/sharing/single-image-theme-small.png', __FILE__); ?>" />
							</label>
							<div class="clear"></div>
						</div>
						<div class="login_radius_select_row" style="opacity: 1;">
							<span class="radio">
								<input <?php echo isset($loginRadiusSettings['horizontalSharing_theme']) && $loginRadiusSettings['horizontalSharing_theme'] == 'counter_vertical' ? 'checked="checked"' : '' ?> type="radio" name="LoginRadius_sharing_settings[horizontalSharing_theme]" id="login_radius_counter_top_vertical" value="counter_vertical" onclick="document.getElementById('login_radius_horizontal_rearrange_container').style.display = 'none'; document.getElementById('login_radius_horizontal_sharing_providers_container').style.display = 'none'; document.getElementById('login_radius_horizontal_counter_providers_container').style.display = 'block'; document.getElementById('login_radius_horizontal_providers_container').style.display = 'block';" />
							</span>
							<label for="login_radius_counter_top_vertical">
								<img src="<?php echo plugins_url('images/counter/hybrid-horizontal-vertical.png', __FILE__); ?>" />
							</label>
							<div class="clear"></div>
						</div>
						<div class="login_radius_select_row" style="opacity: 1;">
							<span class="radio">
								<input <?php echo isset($loginRadiusSettings['horizontalSharing_theme']) && $loginRadiusSettings['horizontalSharing_theme'] == 'counter_horizontal' ? 'checked="checked"' : '' ?> type="radio" name="LoginRadius_sharing_settings[horizontalSharing_theme]" id="login_radius_counter_top_horizontal" value="counter_horizontal" onclick="document.getElementById('login_radius_horizontal_rearrange_container').style.display = 'none'; document.getElementById('login_radius_horizontal_sharing_providers_container').style.display = 'none'; document.getElementById('login_radius_horizontal_counter_providers_container').style.display = 'block'; document.getElementById('login_radius_horizontal_providers_container').style.display = 'block';" />
							</span>
							<label for="login_radius_counter_top_horizontal">
								<img src="<?php echo plugins_url('images/counter/hybrid-horizontal-horizontal.png', __FILE__); ?>" />
							</label>
							<div class="clear"></div>
						</div>
						<div class="loginRadiusBorder2"></div>
						
						<div class="loginRadiusQuestion" style="margin-top:10px">
						<?php _e("Enter the text that you wish to be displayed above the Social Sharing Interface. Leave the field blank if you don't want any text to be displayed.", 'LoginRadius'); ?>
						</div>
						<input type="text" name="LoginRadius_sharing_settings[LoginRadius_sharingTitle]" size="60" value="<?php if(isset($loginRadiusSettings['LoginRadius_sharingTitle'])){ echo htmlspecialchars ($loginRadiusSettings['LoginRadius_sharingTitle']); }else { _e('Share it now!', 'LoginRadius');} ?>" />
						
						<div id="login_radius_horizontal_providers_container">
						<div class="loginRadiusBorder2"></div>
						<div class="loginRadiusQuestion" style="margin-top:10px">
						<?php _e("What Sharing Networks do you want to show in the sharing widget? (All other sharing networks  will be shown as part of LoginRadius sharing icon)", 'LoginRadius') ?>
						</div>
						<div id="loginRadiusHorizontalSharingLimit" style="color:red; display:none; margin-bottom: 5px;"><?php _e('You can select only nine providers', 'LoginRadius') ?>.</div>
						<div style="width:420px" id="login_radius_horizontal_sharing_providers_container"></div>
						<div style="width:600px" id="login_radius_horizontal_counter_providers_container"></div>
						</div>
						
						<div id="login_radius_horizontal_rearrange_container">
						<div class="loginRadiusBorder2"></div>
					
						<div class="loginRadiusQuestion" style="margin-top:10px">
						<?php _e('In what order do you want your sharing networks listed?', 'LoginRadius') ?>
						</div>
						<ul id="loginRadiusHorizontalSortable">
							<?php
							if(isset($loginRadiusSettings['horizontal_rearrange_providers']) && count($loginRadiusSettings['horizontal_rearrange_providers']) > 0){
								foreach($loginRadiusSettings['horizontal_rearrange_providers'] as $provider){
									?>
									<li title="<?php echo $provider ?>" id="loginRadiusHorizontalLI<?php echo $provider ?>" class="lrshare_iconsprite32 lrshare_<?php echo strtolower($provider) ?>">
									<input type="hidden" name="LoginRadius_sharing_settings[horizontal_rearrange_providers][]" value="<?php echo $provider ?>" />
									</li>
									<?php
								}
							}
							?>
						</ul>
						</div>
						
						<div class="loginRadiusBorder2"></div>
						
						<div class="loginRadiusQuestion" style="margin-top:10px">
						<?php _e('Select the position of the Social sharing interface', 'LoginRadius'); ?> 
						</div>
						<input type="checkbox" name="LoginRadius_sharing_settings[horizontal_shareTop]" value="1" <?php echo isset($loginRadiusSettings['horizontal_shareTop']) && $loginRadiusSettings['horizontal_shareTop'] == 1 ? 'checked' : '' ?>/> <?php _e ('Show at the top of content', 'LoginRadius'); ?> <br /> 
						<input type="checkbox" name="LoginRadius_sharing_settings[horizontal_shareBottom]" value="1" <?php echo isset($loginRadiusSettings['horizontal_shareBottom']) && $loginRadiusSettings['horizontal_shareBottom'] == 1 ? 'checked' : '' ?>/> <?php _e ('Show at the bottom of content', 'LoginRadius'); ?> 					    <div class="loginRadiusBorder2"></div>
	
						<div class="loginRadiusQuestion" style="margin-top:10px">
						<?php _e("What area(s) do you want to show the social sharing widget?", 'LoginRadius'); ?>
						</div>
						<input type="checkbox" name="LoginRadius_sharing_settings[horizontal_sharehome]" value="1" <?php echo isset($loginRadiusSettings['horizontal_sharehome']) && $loginRadiusSettings['horizontal_sharehome'] == 1 ? 'checked' : '' ?>/> <?php _e ('Show on homepage', 'LoginRadius'); ?> <br /> 
						<input type="checkbox" name="LoginRadius_sharing_settings[horizontal_sharepost]" value="1" <?php echo isset($loginRadiusSettings['horizontal_sharepost']) && $loginRadiusSettings['horizontal_sharepost'] == 1 ? 'checked' : '' ?>/> <?php _e ('Show on posts', 'LoginRadius'); ?> 
						<br />
						<input type="checkbox" name="LoginRadius_sharing_settings[horizontal_sharepage]" value="1" <?php echo isset($loginRadiusSettings['horizontal_sharepage']) && $loginRadiusSettings['horizontal_sharepage'] == 1 ? 'checked' : '' ?>/> <?php _e ('Show on pages', 'LoginRadius'); ?> <br /> 
						<input type="checkbox" name="LoginRadius_sharing_settings[horizontal_shareexcerpt]" value="1" <?php checked('1', @$loginRadiusSettings['horizontal_shareexcerpt']); ?>/> <?php _e ('Show on post excerpts ', 'LoginRadius'); ?>
					</div>
					</td>
					</tr>
					<tr id="login_radius_vertical" style="display:none">
					<td>
					<span class="lrsharing_spanwhite" style="margin-left:77px"></span>
					<span class="lrsharing_spangrey" style="margin-left:77px"></span>
					<div style="border:1px solid #ccc; padding:10px; border-radius:5px">
						<div class="loginRadiusQuestion">
							<?php _e('Do you want to enable Vertical Social Sharing at your website?', 'LoginRadius'); ?>
						</div>
						<div class="loginRadiusYesRadio">
						<input type="radio" name="LoginRadius_sharing_settings[vertical_shareEnable]" value="1" <?php echo !isset($loginRadiusSettings['vertical_shareEnable']) || $loginRadiusSettings['vertical_shareEnable'] == '1' ? 'checked="checked"' : '' ?> /> <?php _e('Yes', 'LoginRadius') ?>
						</div>
						<input type="radio" name="LoginRadius_sharing_settings[vertical_shareEnable]" value="0" <?php echo isset($loginRadiusSettings['vertical_shareEnable']) && $loginRadiusSettings['vertical_shareEnable'] == '0' ? 'checked="checked"' : '' ?> /> <?php _e('No', 'LoginRadius') ?>
						<div class="loginRadiusBorder2"></div>
						
						<div class="loginRadiusQuestion" style="margin-top:10px">
						<?php _e("Choose a sharing theme", 'LoginRadius'); ?>
						</div>
						<div style="opacity: 1; float:left; width:100px">
							<span class="radio">
								<input <?php echo (isset($loginRadiusSettings['verticalSharing_theme']) && $loginRadiusSettings['verticalSharing_theme'] == '32') || !isset($loginRadiusSettings['verticalSharing_theme']) ? 'checked="checked"' : '' ?> type="radio" id="login_radius_sharing_vertical_32" name="LoginRadius_sharing_settings[verticalSharing_theme]" value="32" onclick="document.getElementById('login_radius_vertical_rearrange_container').style.display = 'block'; document.getElementById('login_radius_vertical_sharing_providers_container').style.display = 'block'; document.getElementById('login_radius_vertical_counter_providers_container').style.display = 'none';" />
							</span>
							<label for="login_radius_sharing_vertical_32">
								<img src="<?php echo plugins_url('images/sharing/vertical/32VerticlewithBox.png', __FILE__); ?>" align="left" />
							</label>
							<div class="clear"></div>
						</div>
						<div style="opacity: 1; float:left; width:100px">
							<span class="radio">
								<input <?php echo isset($loginRadiusSettings['verticalSharing_theme']) && $loginRadiusSettings['verticalSharing_theme'] == '16' ? 'checked="checked"' : '' ?> style="float:left" type="radio" name="LoginRadius_sharing_settings[verticalSharing_theme]" id="login_radius_sharing_vertical_16" value="16" onclick="document.getElementById('login_radius_vertical_rearrange_container').style.display = 'block'; document.getElementById('login_radius_vertical_sharing_providers_container').style.display = 'block'; document.getElementById('login_radius_vertical_counter_providers_container').style.display = 'none';" />
							</span>
							<label for="login_radius_sharing_vertical_16">
								<img src="<?php echo plugins_url('images/sharing/vertical/16VerticlewithBox.png', __FILE__); ?>" />
							</label>
							<div class="clear"></div>
						</div>
						
						<div style="opacity: 1; float:left; width:100px">
							<span class="radio">
								<input <?php echo isset($loginRadiusSettings['verticalSharing_theme']) && $loginRadiusSettings['verticalSharing_theme'] == 'counter_vertical' ? 'checked="checked"' : '' ?> style="float:left" type="radio" name="LoginRadius_sharing_settings[verticalSharing_theme]" id="login_radius_counter_vertical_vertical" value="counter_vertical" onclick="document.getElementById('login_radius_vertical_rearrange_container').style.display = 'none'; document.getElementById('login_radius_vertical_sharing_providers_container').style.display = 'none'; document.getElementById('login_radius_vertical_counter_providers_container').style.display = 'block';" />
							</span>
							<label for="login_radius_counter_vertical_vertical">
								<img src="<?php echo plugins_url('images/counter/hybrid-verticle-vertical.png', __FILE__); ?>" />
							</label>
							<div class="clear"></div>
						</div>
						
						<div style="opacity: 1;">
							<span class="radio">
								<input <?php echo isset($loginRadiusSettings['verticalSharing_theme']) && $loginRadiusSettings['verticalSharing_theme'] == 'counter_horizontal' ? 'checked="checked"' : '' ?> style="float:left" type="radio" name="LoginRadius_sharing_settings[verticalSharing_theme]" id="login_radius_counter_vertical_horizontal" value="counter_horizontal" onclick="document.getElementById('login_radius_vertical_rearrange_container').style.display = 'none'; document.getElementById('login_radius_vertical_sharing_providers_container').style.display = 'none'; document.getElementById('login_radius_vertical_counter_providers_container').style.display = 'block';" />
							</span>
							<label for="login_radius_counter_vertical_horizontal">
								<img src="<?php echo plugins_url('images/counter/hybrid-verticle-horizontal.png', __FILE__); ?>" />
							</label>
							<div class="clear"></div>
						</div>
						
						<div id="login_radius_vertical_providers_container">
						<div class="loginRadiusBorder2"></div>
						<div class="loginRadiusQuestion" style="margin-top:10px">
						<?php _e("What Sharing Networks do you want to show in the sharing widget? (All other sharing networks  will be shown as part of LoginRadius sharing icon)", 'LoginRadius') ?>
						</div>
						<div id="loginRadiusVerticalSharingLimit" style="color:red; display:none; margin-bottom: 5px;"><?php _e('You can select only nine providers', 'LoginRadius') ?>.</div>
						<div style="width:420px" id="login_radius_vertical_sharing_providers_container"></div>
						<div style="width:600px" id="login_radius_vertical_counter_providers_container"></div>
						</div>
						
						<div id="login_radius_vertical_rearrange_container">
						<div class="loginRadiusBorder2"></div>
					
						<div class="loginRadiusQuestion" style="margin-top:10px">
						<?php _e('In what order do you want your sharing networks listed?', 'LoginRadius') ?>
						</div>
						<ul id="loginRadiusVerticalSortable">
							<?php
							if(isset($loginRadiusSettings['vertical_rearrange_providers']) && count($loginRadiusSettings['vertical_rearrange_providers']) > 0){
								foreach($loginRadiusSettings['vertical_rearrange_providers'] as $provider){
									?>
									<li title="<?php echo $provider ?>" id="loginRadiusVerticalLI<?php echo $provider ?>" class="lrshare_iconsprite32 lrshare_<?php echo strtolower($provider) ?>">
									<input type="hidden" name="LoginRadius_sharing_settings[vertical_rearrange_providers][]" value="<?php echo $provider ?>" />
									</li>
									<?php
								}
							}
							?>
						</ul>
						</div>
						
						<div class="loginRadiusBorder2"></div>
						
						<div class="loginRadiusQuestion" style="margin-top:10px">
						<?php _e("Select the position of the Social Sharing widget", 'LoginRadius'); ?>
						</div>
						<div class="loginRadiusProviders">
							<input <?php echo (isset($loginRadiusSettings['sharing_verticalPosition']) && $loginRadiusSettings['sharing_verticalPosition'] == 'top_left') || !isset($loginRadiusSettings['sharing_verticalPosition']) ? 'checked="checked"' : '' ?> type="radio" name="LoginRadius_sharing_settings[sharing_verticalPosition]" value="top_left" /> <label>Top Left</label>
						</div>
						<div class="loginRadiusProviders">
							<input  <?php echo (isset($loginRadiusSettings['sharing_verticalPosition']) && $loginRadiusSettings['sharing_verticalPosition'] == 'top_right') ? 'checked="checked"' : '' ?> type="radio" name="LoginRadius_sharing_settings[sharing_verticalPosition]" value="top_right" /> <label>Top Right</label>
						</div>
						<div class="loginRadiusProviders">
							<input <?php echo (isset($loginRadiusSettings['sharing_verticalPosition']) && $loginRadiusSettings['sharing_verticalPosition'] == 'bottom_left') ? 'checked="checked"' : '' ?> type="radio" name="LoginRadius_sharing_settings[sharing_verticalPosition]" value="bottom_left" /> <label>Bottom Left</label>
						</div>
						<div class="loginRadiusProviders">
							<input <?php echo (isset($loginRadiusSettings['sharing_verticalPosition']) && $loginRadiusSettings['sharing_verticalPosition'] == 'bottom_right') ? 'checked="checked"' : '' ?> type="radio" name="LoginRadius_sharing_settings[sharing_verticalPosition]" value="bottom_right" /> <label>Bottom Right</label>
						</div>
						<div class="loginRadiusBorder2"></div>
						<div class="loginRadiusQuestion" style="margin-top:10px">
						<?php _e('Specify distance of vertical sharing interface from top (Leave empty for default behaviour)', 'LoginRadius'); ?>
							<a style="text-decoration:none" href="javascript:void(0)" title="<?php _e("Enter a number (For example - 200). It will set the 'top' CSS attribute of the interface to the value specified. Increase in the number pushes interface towards bottom.", "LoginRadius") ?>">(?)</a>
						</div>
						<input style="width:100px" type="text" name="LoginRadius_sharing_settings[sharing_offset]" value="<?php echo (isset($loginRadiusSettings['sharing_offset']) && $loginRadiusSettings['sharing_offset'] != '') ? $loginRadiusSettings['sharing_offset'] : '' ?>" onkeyup="if(this.value.trim() != '' && !loginRadiusSharingIsNumber(this.value.trim())){ document.getElementById('login_radius_offset_error').innerHTML = 'Please enter a valid number.'; }else{ document.getElementById('login_radius_offset_error').innerHTML = ''; } " />
						<div style="clear:both"></div>
						<div id="login_radius_offset_error" style="color:#FF0000"></div>
						<div class="loginRadiusBorder2"></div>
						
						<div class="loginRadiusQuestion" style="margin-top:10px">
						<?php _e("What area(s) do you want to show the social sharing widget?", 'LoginRadius'); ?>
						</div>
						<input type="checkbox" name="LoginRadius_sharing_settings[vertical_sharehome]" value="1" <?php echo isset($loginRadiusSettings['vertical_sharehome']) && $loginRadiusSettings['vertical_sharehome'] == 1 ? 'checked' : '' ?>/> <?php _e ('Show on homepage', 'LoginRadius'); ?> <br /> 
						<input type="checkbox" name="LoginRadius_sharing_settings[vertical_sharepost]" value="1" <?php echo isset($loginRadiusSettings['vertical_sharepost']) && $loginRadiusSettings['vertical_sharepost'] == 1 ? 'checked' : '' ?>/> <?php _e ('Show on posts', 'LoginRadius'); ?> 
						<br />
						<input type="checkbox" name="LoginRadius_sharing_settings[vertical_sharepage]" value="1" <?php echo isset($loginRadiusSettings['vertical_sharepage']) && $loginRadiusSettings['vertical_sharepage'] == 1 ? 'checked' : '' ?>/> <?php _e ('Show on pages', 'LoginRadius'); ?> <br /> 
						<input type="checkbox" name="LoginRadius_sharing_settings[vertical_shareexcerpt]" value="1" <?php checked('1', @$loginRadiusSettings['vertical_shareexcerpt']); ?>/> <?php _e ('Show on post excerpts ', 'LoginRadius'); ?>
						<div class="loginRadiusBorder2"></div>
					</div>
					</td>
					</tr>
					<?php
						if(is_multisite() && is_main_site()){
							?>
							<tr>
							<td>
							<div class="loginRadiusQuestion">
							<?php _e('Do you want to apply the same changes when you update plugin settings in the main blog of multisite network?', 'LoginRadius'); ?></div>
							<input type="radio" name="LoginRadius_sharing_settings[multisite_config]" value="1" <?php checked('1', @$loginRadiusSettings['multisite_config']); ?>/> <?php _e ('YES, apply the same changes to plugin settings of each blog in the multisite network when I update plugin settings.', 'LoginRadius'); ?> <br />
							<input type="radio" name="LoginRadius_sharing_settings[multisite_config]" value="0" <?php echo((isset($loginRadiusSettings['multisite_config']) && $loginRadiusSettings['multisite_config'] == 0) || !isset($loginRadiusSettings['multisite_config']))? "checked" : ""; ?>/> <?php _e ('NO, do not apply the changes to other blogs when I update plugin settings.', 'LoginRadius'); ?> 
							<div class="loginRadiusBorder"></div>
							</td>
							</tr>
							<?php
						}
					?>
					</table>
	
					</div>
					</div>
					
					<!-- Plugin deletion options -->
					<div class="stuffbox">
						<h3><label><?php _e('Plug-in deletion options', 'LoginRadius');?></label></h3>
						<div class="inside">
						<table width="100%" border="0" cellspacing="0" cellpadding="0" class="form-table editcomment menu_content_table">
						<tr>
						<td>
						<div class="loginRadiusQuestion">
						<?php _e('Do you want to completely remove the plugin settings and options on plugin deletion (If you choose yes, then you will not be able to recover settings again)?', 'LoginRadius'); ?>
						</div>
						<input type="radio" name="LoginRadius_sharing_settings[delete_options]" value="1" <?php echo(!isset($loginRadiusSettings['delete_options']) || $loginRadiusSettings['delete_options'] == 1) ? "checked" : ""; ?> /> <?php _e('YES', 'LoginRadius') ?> <br />
						<input type="radio" name="LoginRadius_sharing_settings[delete_options]" value="0" <?php echo (isset($loginRadiusSettings['delete_options']) && $loginRadiusSettings['delete_options'] == 0) ? "checked" : ""; ?>  /> <?php _e('NO', 'LoginRadius'); ?><br />
						</td>
						</tr>

						</table>
						</div>
					</div>
				</div>
					<div class="menu_containt_div" id="tabs-3">
						<div class="stuffbox">
						<h3><label><?php _e('Help & Documentations', 'LoginRadius'); ?></label></h3>
						<div class="inside">
						<table width="100%" border="0" cellspacing="0" cellpadding="0" class="form-table editcomment menu_content_table">
						<tr id="login_radius_vertical_position_counter">
						<td>
							<ul style="float:left; margin-right:86px">

								<li><a target="_blank" href="http://support.loginradius.com/customer/portal/articles/1189987-wordpress-social-sharing-installation-configuration-and-troubleshooting"><?php _e('Plugin Installation, Configuration and Troubleshooting', 'LoginRadius') ?></a></li>
								<li><a target="_blank" href="http://support.loginradius.com/customer/portal/articles/677100-how-to-get-loginradius-api-key-and-secret"><?php _e('How to get LoginRadius API Key', 'LoginRadius') ?></a></li>
								<li><a target="_blank" href="http://community.loginradius.com/"><?php _e('Discussion Forum', 'LoginRadius') ?></a></li>
								<li><a target="_blank" href="http://www.loginradius.com/loginradius/about"><?php _e('About LoginRadius', 'LoginRadius') ?></a></li>
							</ul>
							<ul style="float:left">
								<li><a target="_blank" href="https://www.loginradius.com/loginradius/product-overview"><?php _e('LoginRadius Products', 'LoginRadius') ?></a></li>
								<li><a target="_blank" href="https://www.loginradius.com/loginradius-for-developers/loginRadius-cms"><?php _e('Social Plugins', 'LoginRadius') ?></a></li>
								<li><a target="_blank" href="https://www.loginradius.com/loginradius-for-developers/loginRadius-sdks"><?php _e('Social SDKs', 'LoginRadius') ?></a></li>
							</ul>
						</td>
						</tr>
						</table>
						</div>
						</div>
					</div>
				</div>
				<p class="submit">   
					<?php   
					// Build Preview Link   
					$preview_link = esc_url(get_option('home' ) . '/' );   
					if(is_ssl()){ 
						$preview_link = str_replace('http://', 'https://', $preview_link ); 
					} 
					$stylesheet = get_option('stylesheet');   
					$template = get_option('template');   
					$preview_link = htmlspecialchars(add_query_arg(array('preview' => 1, 'template' => $template, 'stylesheet' => $stylesheet, 'preview_iframe' => true, 'TB_iframe' => 'true' ), $preview_link ) );   
					?>
					<input style="margin-left:8px" type="submit" name="save" class="button button-primary" value="<?php _e("Save Changes", 'LoginRadius'); ?>" />   
					<a href="<?php echo $preview_link; ?>" class="thickbox thickbox-preview" id="preview" ><?php _e('Preview', 'LoginRadius'); ?></a>
				</p>
			</div>
			<?php
		}
		?>
	</form>
	</div>
	<?php
}
?>