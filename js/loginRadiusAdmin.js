// get trim() working in IE 
if(typeof String.prototype.trim !== 'function') {
      String.prototype.trim = function() {
        return this.replace(/^\s+|\s+$/g, ''); 
      }
}
var loginRadiusSharingHorizontalSharingTheme = document.getElementsByName('LoginRadius_sharing_settings[horizontalSharing_theme]');
var loginRadiusSharingVerticalSharingTheme = document.getElementsByName('LoginRadius_sharing_settings[verticalSharing_theme]');
var loginRadiusSharingHorizontalSharingProviders;
var loginRadiusSharingVerticalSharingProviders;
// validate numeric data 
function loginRadiusSharingIsNumber(n){
  return !isNaN(parseFloat(n)) && isFinite(n);
}

function loginRadiusCheckElement(arr, obj){
	for(var i=0; i<arr.length; i++) {
		if (arr[i] == obj) return true;
	}
	return false
}

window.onload = function(){
	loginRadiusAdminUI2();
	loginRadiusSharingHorizontalSharingProviders = document.getElementsByName('LoginRadius_sharing_settings[horizontal_sharing_providers][]');
	loginRadiusSharingVerticalSharingProviders = document.getElementsByName('LoginRadius_sharing_settings[vertical_sharing_providers][]');
	loginRadiusAdminUI();
}
// toggle between login and registration form
function loginRadiusToggleForm(val){
	if(val == 'login'){
		document.getElementById('lrsiteRow').style.display = 'none';
		document.getElementById('lrSiteMessageRow').style.display = 'none';
		document.getElementById('confirmPasswordRow').style.display = 'none';
		document.getElementById('loginRadiusToggleFormLink').innerHTML = 'New to LoginRadius, Register Now!';
		document.getElementById('loginRadiusToggleFormLink').setAttribute('onclick', 'loginRadiusToggleForm("register")');
		document.getElementById('loginRadiusSubmit').value = 'Login';
		document.getElementById('loginRadiusFormTitle').innerHTML = 'Login to your LoginRadius Account to change settings as per your requirements!';
	}else{
		document.getElementById('lrsiteRow').style.display = 'table-row';
		document.getElementById('lrSiteMessageRow').style.display = 'table-row';
		document.getElementById('confirmPasswordRow').style.display = 'table-row';
		document.getElementById('loginRadiusToggleFormLink').innerHTML = 'Already have an account?';
		document.getElementById('loginRadiusToggleFormLink').setAttribute('onclick', 'loginRadiusToggleForm("login")');
		document.getElementById('loginRadiusSubmit').value = 'Register';
		document.getElementById('loginRadiusFormTitle').innerHTML = 'Register LoginRadius Account to change settings as per your requirements!';
	}
	document.getElementById('loginRadiusMessage').innerHTML = '';
}
function loginRadiusAdminUI(){
	for(var key in loginRadiusSharingHorizontalSharingTheme){
		if(loginRadiusSharingHorizontalSharingTheme[key].checked){
			loginRadiusToggleHorizontalShareTheme(loginRadiusSharingHorizontalSharingTheme[key].value);
			break;
		}
	}
	for(var key in loginRadiusSharingVerticalSharingTheme){
		if(loginRadiusSharingVerticalSharingTheme[key].checked){
			loginRadiusToggleVerticalShareTheme(loginRadiusSharingVerticalSharingTheme[key].value);
			break;
		}
	}
	// if rearrange horizontal sharing icons option is empty, show seleted icons to rearrange
	if(document.getElementsByName('LoginRadius_sharing_settings[horizontal_rearrange_providers][]').length == 0){
		for(var i = 0; i < loginRadiusSharingHorizontalSharingProviders.length; i++){
			if(loginRadiusSharingHorizontalSharingProviders[i].checked){
				loginRadiusRearrangeProviderList(loginRadiusSharingHorizontalSharingProviders[i], 'Horizontal');
			}
		}
	}
	// if rearrange vertical sharing icons option is empty, show seleted icons to rearrange
	if(document.getElementsByName('LoginRadius_sharing_settings[vertical_rearrange_providers][]').length == 0){
		for(var i = 0; i < loginRadiusSharingVerticalSharingProviders.length; i++){
			if(loginRadiusSharingVerticalSharingProviders[i].checked){
				loginRadiusRearrangeProviderList(loginRadiusSharingVerticalSharingProviders[i], 'Vertical');
			}
		}
	}
}

jQuery(function(){
    jQuery("#loginRadiusHorizontalSortable, #loginRadiusVerticalSortable").sortable({
      revert: true
    });
});
// prepare rearrange provider list
function loginRadiusRearrangeProviderList(elem, sharingType){
	var ul = document.getElementById('loginRadius'+sharingType+'Sortable');
	if(elem.checked){
		var listItem = document.createElement('li');
		listItem.setAttribute('id', 'loginRadius'+sharingType+'LI'+elem.value);
		listItem.setAttribute('title', elem.value);
		listItem.setAttribute('class', 'lrshare_iconsprite32 lrshare_'+elem.value.toLowerCase());
		// append hidden field
		var provider = document.createElement('input');
		provider.setAttribute('type', 'hidden');
		provider.setAttribute('name', 'LoginRadius_sharing_settings['+sharingType.toLowerCase()+'_rearrange_providers][]');
		provider.setAttribute('value', elem.value);
		listItem.appendChild(provider);
		ul.appendChild(listItem);
	}else{
		if(document.getElementById('loginRadius'+sharingType+'LI'+elem.value)){
			ul.removeChild(document.getElementById('loginRadius'+sharingType+'LI'+elem.value));
		}
	}
}
// limit maximum number of providers selected in horizontal sharing
function loginRadiusHorizontalSharingLimit(elem){
	var checkCount = 0;
	for(var i = 0; i < loginRadiusSharingHorizontalSharingProviders.length; i++){
		if(loginRadiusSharingHorizontalSharingProviders[i].checked){
			// count checked providers
			checkCount++;
			if(checkCount >= 10){
				elem.checked = false;
				document.getElementById('loginRadiusHorizontalSharingLimit').style.display = 'block';
				setTimeout(function(){ document.getElementById('loginRadiusHorizontalSharingLimit').style.display = 'none'; }, 2000);
				return;
			}
		}
	}
}
// limit maximum number of providers selected in vertical sharing
function loginRadiusVerticalSharingLimit(elem){
	var checkCount = 0;
	for(var i = 0; i < loginRadiusSharingVerticalSharingProviders.length; i++){
		if(loginRadiusSharingVerticalSharingProviders[i].checked){
			// count checked providers
			checkCount++;
			if(checkCount >= 10){
				elem.checked = false;
				document.getElementById('loginRadiusVerticalSharingLimit').style.display = 'block';
				setTimeout(function(){ document.getElementById('loginRadiusVerticalSharingLimit').style.display = 'none'; }, 2000);
				return;
			}
		}
	}
}
// show/hide options according to the selected horizontal sharing theme
function loginRadiusToggleHorizontalShareTheme(theme){
	switch(theme){
		case '32':
		document.getElementById('login_radius_horizontal_rearrange_container').style.display = 'block';
		document.getElementById('login_radius_horizontal_sharing_providers_container').style.display = 'block';
		document.getElementById('login_radius_horizontal_counter_providers_container').style.display = 'none';
		document.getElementById('login_radius_horizontal_providers_container').style.display = 'block';
		break;
		case '16':
		document.getElementById('login_radius_horizontal_rearrange_container').style.display = 'block';
		document.getElementById('login_radius_horizontal_sharing_providers_container').style.display = 'block';
		document.getElementById('login_radius_horizontal_counter_providers_container').style.display = 'none';
		document.getElementById('login_radius_horizontal_providers_container').style.display = 'block';
		break;
		case 'single_large':
		document.getElementById('login_radius_horizontal_rearrange_container').style.display = 'none';
		document.getElementById('login_radius_horizontal_providers_container').style.display = 'none';
		break;
		case 'single_small':
		document.getElementById('login_radius_horizontal_rearrange_container').style.display = 'none';
		document.getElementById('login_radius_horizontal_providers_container').style.display = 'none';
		break;
		case 'counter_vertical':
		document.getElementById('login_radius_horizontal_rearrange_container').style.display = 'none';
		document.getElementById('login_radius_horizontal_sharing_providers_container').style.display = 'none';
		document.getElementById('login_radius_horizontal_counter_providers_container').style.display = 'block';
		document.getElementById('login_radius_horizontal_providers_container').style.display = 'block';
		break;
		case 'counter_horizontal':
		document.getElementById('login_radius_horizontal_rearrange_container').style.display = 'none';
		document.getElementById('login_radius_horizontal_sharing_providers_container').style.display = 'none';
		document.getElementById('login_radius_horizontal_counter_providers_container').style.display = 'block';
		document.getElementById('login_radius_horizontal_providers_container').style.display = 'block';
	}
}

// display options according to the selected counter theme
function loginRadiusToggleVerticalShareTheme(theme){
	switch(theme){
		case '32':
		document.getElementById('login_radius_vertical_rearrange_container').style.display = 'block';
		document.getElementById('login_radius_vertical_sharing_providers_container').style.display = 'block';
		document.getElementById('login_radius_vertical_counter_providers_container').style.display = 'none';
		break;
		case '16':
		document.getElementById('login_radius_vertical_rearrange_container').style.display = 'block';
		document.getElementById('login_radius_vertical_sharing_providers_container').style.display = 'block';
		document.getElementById('login_radius_vertical_counter_providers_container').style.display = 'none';
		break;
		case 'counter_vertical':
		document.getElementById('login_radius_vertical_rearrange_container').style.display = 'none';
		document.getElementById('login_radius_vertical_sharing_providers_container').style.display = 'none';
		document.getElementById('login_radius_vertical_counter_providers_container').style.display = 'block';
		break;
		case 'counter_horizontal':
		document.getElementById('login_radius_vertical_rearrange_container').style.display = 'none';
		document.getElementById('login_radius_vertical_sharing_providers_container').style.display = 'none';
		document.getElementById('login_radius_vertical_counter_providers_container').style.display = 'block';
	}
}

// assign update code function onchange event of elements
function loginRadiusAttachFunction(elems){
	for(var i = 0; i < elems.length; i++){
		elems[i].onchange = loginRadiusToggleTheme;
	}
}
function loginRadiusGetChecked(elems){
	var checked = [];
	// loop over all 
	for(var i=0; i<elems.length; i++){
		if(elems[i].checked){
			checked.push(elems[i].value);
		}
	}
	return checked;
}