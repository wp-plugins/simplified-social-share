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
	};
	
	</script>
	<div class="wrapper">
	<form action="options.php" method="post">
		<?php settings_fields('LoginRadius_sharing_setting_options'); ?>
		<div class="header_div">
		<h2>LoginRadius <?php _e('Simplified Social Sharing Settings', 'LoginRadius') ?></h2>
		<div id="loginRadiusError" style="background-color: #FFFFE0; border:1px solid #E6DB55; padding:5px; margin-bottom:5px; width: 1050px;">
			 <?php _e('Please clear your browser cache, if you have trouble loading the plugin interface. For more information', 'LoginRadius') ?> <a target="_blank" href="http://www.wikihow.com/Clear-Your-Browser's-Cache" >  <?php _e('click here', 'LoginRadius') ?> </a>.
		</div>
		<fieldset style="margin-right:13px; height:170px; background-color:#EAF7FF; border-color:rgb(195, 239, 250); padding-bottom:10px; width:751px">
		<h4 style="color:#000"><strong><?php _e('Thank you for installing the Simplified Social Sharing!', 'LoginRadius') ?></strong></h4>
		<p>
		<?php _e('We also offer Social Plugins for ', 'LoginRadius') ?><a href="https://www.loginradius.com/loginradius-for-developers/loginRadius-cms#joomlaextension" target="_blank">Joomla</a>, <a href="https://www.loginradius.com/loginradius-for-developers/loginRadius-cms#drupalmodule" target="_blank">Drupal</a>, <a href="https://www.loginradius.com/loginradius-for-developers/loginRadius-cms#vBulletinplugin" target="_blank">vBulletin</a>, <a href="https://www.loginradius.com/loginradius-for-developers/loginRadius-cms#vanillaaddons" target="_blank">VanillaForum</a>, <a href="https://www.loginradius.com/loginradius-for-developers/loginRadius-cms#magentoextension" target="_blank">Magento</a>, <a href="https://www.loginradius.com/loginradius-for-developers/loginRadius-cms#osCommerceaddons" target="_blank">OSCommerce</a>, <a href="https://www.loginradius.com/loginradius-for-developers/loginRadius-cms#prestashopmodule" target="_blank">PrestaShop</a>, <a href="https://www.loginradius.com/loginradius-for-developers/loginRadius-cms#xcartextension" target="_blank">X-Cart</a>, <a href="https://www.loginradius.com/loginradius-for-developers/loginRadius-cms#zencartplugin" target="_blank">Zen-Cart</a>, <a href="https://www.loginradius.com/loginradius-for-developers/loginRadius-cms#dotnetnukemodule" target="_blank">DotNetNuke</a> <?php _e('and', 'LoginRadius') ?> <a href="https://www.loginradius.com/loginradius-for-developers/loginRadius-cms#blogengineextension" target="_blank">BlogEngine</a>!
		</p>
		<a style="text-decoration:none;" href="https://www.loginradius.com/" target="_blank">
			<input style="margin-top:10px" class="button" type="button" value="<?php _e('Set up my FREE account!', 'LoginRadius'); ?>" />
		</a><br />

		<a class="loginRadiusHow" target="_blank" href="http://support.loginradius.com/customer/portal/articles/593954">(<?php _e('How to set up an account', 'LoginRadius') ?>?)</a>
		</fieldset>
		
		<fieldset style="width:25%; background-color: rgb(231, 255, 224); border: 1px solid rgb(191, 231, 176); padding-bottom:6px; height:173px; width:255px">
		<h4 style="border-bottom:#d7d7d7 1px solid;"><strong><?php _e('Get Updates', 'LoginRadius') ?></strong></h4>
		<p><?php _e('To receive updates on new features, future releases, etc, please connect with us via Facebook', 'LoginRadius') ?>-</p>
		<div>
			<div style="float:left">
				<iframe rel="tooltip" scrolling="no" frameborder="0" allowtransparency="true" style="border: none; overflow: hidden; width: 46px;
							height: 61px; margin-right:10px" src="//www.facebook.com/plugins/like.php?app_id=194112853990900&amp;href=http%3A%2F%2Fwww.facebook.com%2Fpages%2FLoginRadius%2F119745918110130&amp;send=false&amp;layout=box_count&amp;width=90&amp;show_faces=false&amp;action=like&amp;colorscheme=light&amp;font=arial&amp;height=90" data-original-title="Like us on Facebook"></iframe>
				</div>
		</div>
		</fieldset>
		
		<fieldset class="help_div" style="margin-right:13px; height:145px; width:751px">
		<h4 style="border-bottom:#d7d7d7 1px solid;"><strong><?php _e('Help & Documentations', 'LoginRadius') ?></strong></h4>
		<ul style="float:left; margin-right:43px">
			<li><a target="_blank" href="http://support.loginradius.com/customer/portal/articles/1189987-wordpress-social-sharing-installation-configuration-and-troubleshooting"><?php _e('Plugin Installation, Configuration and Troubleshooting', 'LoginRadius') ?></a></li>
			<li><a target="_blank" href="http://support.loginradius.com/customer/portal/articles/677100-how-to-get-loginradius-api-key-and-secret"><?php _e('How to get LoginRadius API Key', 'LoginRadius') ?></a></li>
			<li><a target="_blank" href="http://community.loginradius.com/"><?php _e('Discussion Forum', 'LoginRadius') ?></a></li>
		</ul>
		<ul style="float:left; margin-right:43px">
			<li><a target="_blank" href="http://www.loginradius.com/loginradius/about"><?php _e('About LoginRadius', 'LoginRadius') ?></a></li>
			<li><a target="_blank" href="http://www.loginradius.com/product/sociallogin"><?php _e('LoginRadius Products', 'LoginRadius') ?></a></li>
		</ul>
		<ul style="float:left">
			<li><a target="_blank" href="https://www.loginradius.com/loginradius-for-developers/loginRadius-cms"><?php _e('Social Plugins', 'LoginRadius') ?></a></li>
			<li><a target="_blank" href="https://www.loginradius.com/loginradius-for-developers/loginRadius-sdks"><?php _e('Social SDKs', 'LoginRadius') ?></a></li>
		</ul>
		</fieldset>
		
		<fieldset style="margin-right:5px; background-color: rgb(231, 255, 224); border: 1px solid rgb(191, 231, 176); width:255px">
		<h4 style="border-bottom:#d7d7d7 1px solid;"><strong><?php _e('Support Us', 'LoginRadius') ?></strong></h4>
		<p>
		<?php _e('If you liked our FREE open-source plugin, please send your feedback/testimonial to ', 'LoginRadius') ?><a href="mailto:feedback@loginradius.com">feedback@loginradius.com</a> !
		<?php _e('Please help us to ', 'LoginRadius') ?><a target="_blank" href="http://docs.loginradius.com/wordpress.htm"><?php _e('translate', 'LoginRadius') ?> </a><?php _e('the plugin content in your language.', 'LoginRadius') ?>
		</p>
		</fieldset>
		
		</div>
		<div class="clr"></div>
		<?php
		if(trim($loginRadiusSettings['LoginRadius_apikey']) != "" && !loginradiusValidateKey(trim($loginRadiusSettings['LoginRadius_apikey']))){
			?>
			<div class="error">
			<p>
				<?php _e('Your LoginRadius API key is not valid, please correct it or contact LoginRadius support at <b><a href ="http://www.loginradius.com" target = "_blank">www.LoginRadius.com</a></b>', 'LoginRadius'); ?>
			</p>
			</div>
			<?php
		}
		?>
		<div class="metabox-holder columns-2" id="post-body">
				<div class="menu_div" id="tabs">
					<h2 class="nav-tab-wrapper" style="height:36px">
					<ul>
						<li style="margin-left:9px"><a style="margin:0" class="nav-tab" href="#tabs-1"><?php _e('API Settings', 'LoginRadius') ?></a></li>
						<li><a style="margin:0" class="nav-tab" href="#tabs-2"><?php _e('Social Sharing', 'LoginRadius') ?></a></li>
						<li style="float:right; margin-right:8px"><a style="margin:0" class="nav-tab" href="#tabs-3"><?php _e('Help', 'LoginRadius') ?></a></li>
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
					<?php _e("Do you want to enable Social Sharing for your website", 'LoginRadius'); ?>?
					</div>
					<div class="loginRadiusYesRadio">
					<input type="radio" name="LoginRadius_sharing_settings[LoginRadius_shareEnable]" value="1" <?php echo isset($loginRadiusSettings['LoginRadius_shareEnable']) && $loginRadiusSettings['LoginRadius_shareEnable'] == 1 ? 'checked' : ''; ?>/> <?php _e("Yes", 'LoginRadius') ?>
					</div>
					<input type="radio" name="LoginRadius_sharing_settings[LoginRadius_shareEnable]" value="0" <?php echo isset($loginRadiusSettings['LoginRadius_shareEnable']) && $loginRadiusSettings['LoginRadius_shareEnable'] == 0 ? 'checked' : ''; ?>/> <?php _e("No", 'LoginRadius') ?> 
					</td>
					</tr>
					<tr>
					<td>
					<div class="loginRadiusQuestion">
					<?php _e("How do you want the sharing count to be displayed in the sharing widgets at the home page of your website if it lists multiple pages/posts?", 'LoginRadius'); ?>
					</div>
					<div class="loginRadiusYesRadio">
					<input type="radio" name="LoginRadius_sharing_settings[sharingCount]" value="website" <?php echo isset($loginRadiusSettings['sharingCount']) && $loginRadiusSettings['sharingCount'] == 'website' ? 'checked' : ''; ?>/> <?php _e("Website Level", 'LoginRadius') ?> <a style="text-decoration:none" href="javascript:void(0)" title="All the sharing interfaces at the home page will share your website url." >(?)</a>
					</div>
					<input type="radio" name="LoginRadius_sharing_settings[sharingCount]" value="page" <?php echo !isset($loginRadiusSettings['sharingCount']) || $loginRadiusSettings['sharingCount'] == 'page' ? 'checked' : ''; ?>/> <?php _e("Page Level", 'LoginRadius') ?> <a style="text-decoration:none" href="javascript:void(0)" title="All the sharing interfaces at the home page will share the page/post url above/below which those are placed." >(?)</a>
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
						<input type="checkbox" name="LoginRadius_sharing_settings[horizontal_shareexcerpt]" value="1" <?php checked('1', @$loginRadiusSettings['horizontal_shareexcerpt']); ?>/> <?php _e ('Show on post excerpts ', 'LoginRadius'); ?> <br /> 
						<input type="checkbox" name="LoginRadius_sharing_settings[horizontal_sharearchive]" value="1" <?php checked('1', @$loginRadiusSettings['horizontal_sharearchive']); ?>/> <?php _e ('Show on archive pages', 'LoginRadius'); ?> 
						<br />
						<input type="checkbox" name="LoginRadius_sharing_settings[horizontal_sharefeed]" value="1" <?php checked('1', @$loginRadiusSettings['horizontal_sharefeed']); ?>/> <?php _e ('Show on feed', 'LoginRadius'); ?> 
					
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
						<input style="width:100px" type="text" name="LoginRadius_sharing_settings[sharing_offset]" value="<?php echo (isset($loginRadiusSettings['sharing_offset']) && $loginRadiusSettings['sharing_offset'] != '') ? $loginRadiusSettings['sharing_offset'] : '' ?>" onkeyup="if(!loginRadiusSharingIsNumber(this.value.trim())){ document.getElementById('login_radius_offset_error').innerHTML = 'Please enter a valid number.'; }else{ document.getElementById('login_radius_offset_error').innerHTML = ''; } " />
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
						<input type="checkbox" name="LoginRadius_sharing_settings[vertical_shareexcerpt]" value="1" <?php checked('1', @$loginRadiusSettings['vertical_shareexcerpt']); ?>/> <?php _e ('Show on post excerpts ', 'LoginRadius'); ?> <br /> 
						<input type="checkbox" name="LoginRadius_sharing_settings[vertical_sharearchive]" value="1" <?php checked('1', @$loginRadiusSettings['vertical_sharearchive']); ?>/> <?php _e ('Show on archive pages', 'LoginRadius'); ?> 
						<br />
						<input type="checkbox" name="LoginRadius_sharing_settings[vertical_sharefeed]" value="1" <?php checked('1', @$loginRadiusSettings['vertical_sharefeed']); ?>/> <?php _e ('Show on feed', 'LoginRadius'); ?>
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
								<li><a target="_blank" href="http://www.loginradius.com/product/sociallogin"><?php _e('LoginRadius Products', 'LoginRadius') ?></a></li>
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
	</form>
	</div>
	<?php
}
?>