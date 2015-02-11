<?php

// Define LoginRadius domain
define( 'LR_DOMAIN', 'api.loginradius.com' );

/**
 * Class for Social Authentication.
 *
 * This is the main class to communicate with LogiRadius Unified Social API. It contains functions for Social Authentication with User Profile Data (Basic and Extended)
 *
 * Copyright 2014 LoginRadius Inc. - www.LoginRadius.com
 *
 * This file is part of the LoginRadius SDK package.
 *
 */ 
class LoginRadius {

/**
 * LoginRadius function - It validate against GUID format of keys.
 *
 * @param string $key data to validate.
 *
 * @return boolean. If valid - true, else - false.
 */
public function loginradius_validate_key( $key ) {
    if ( empty( $key ) || !preg_match( '/^\{?[A-Z0-9]{8}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{12}\}?$/i', $key ) ) {
        return false;
    } else {
        return true;
    }
}

/**
 * LoginRadius function - It validates against GUID format of keys.
 * 
 * @param string $value data to validate.
 *
 * @return boolean If valid - true, else - false
 */ 
public function loginradius_is_valid_guid($value){
	return preg_match('/^\{?[A-Z0-9]{8}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{12}\}?$/i', $value);
}

/**
 * LoginRadius function - To fetch social login providers
 *
 * @param string $key data to validate.
 *
 * @return object Social Login Providers.
 */ 
public function loginradius_get_providers( $apikey ){
		if ( empty( $apikey ) || !preg_match( '/^\{?[A-Z0-9]{8}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{12}\}?$/i', $apikey ) ) {
			return json_encode( array( 'isValid' => false, 'error' => 'API KEY not valid') );
		}

    	$ValidateUrl = 'https://cdn.loginradius.com/interface/json/' . $apikey . '.json';

    	$args = array(
	        'timeout' => 15,
	        'sslverify' => 'false'
	    );

    	$loginRadiusResponse = wp_remote_get( $ValidateUrl, $args );


    	if ( is_wp_error( $loginRadiusResponse ) ) {
	        return json_encode( array( 'isValid' => false, 'error' => $loginRadiusResponse->get_error_message() ) );
	    } else {
	        $JsonResponse = $loginRadiusResponse['body'];
	        $JsonResponse = explode ( '(' , $JsonResponse);
	        $JsonResponse =	str_replace(')', '', $JsonResponse[1]);
	        return json_decode( $JsonResponse, TRUE );
	    }
}

/**
 * LoginRadius function - Check, if it is a valid callback i.e. LoginRadius authentication token is set
 *
 * @return boolean True, if a valid callback.
 */ 
public function loginradius_is_callback(){
	if(isset($_REQUEST['token'])){
		return true;
	}else{
		return false;
	}
}

/**
 * LoginRadius function - Fetch LoginRadius access token after authentication. It will be valid for the specific duration of time specified in the response.
 *
 * @param string LoginRadius API Secret
 *
 * @return string LoginRadius access token.
 */ 
public function loginradius_fetch_access_token($secret, $token = false ){
	
	if( ! $this -> loginradius_is_valid_guid($secret)){
		die('Invalid API secret');
	}
	if( ! $token ) {
		$token = $_REQUEST['token'];
	}

	$ValidateUrl = "https://" . LR_DOMAIN . "/api/v2/access_token?token=" . $token . "&secret=" . $secret;
	$Response = json_decode($this->loginradius_call_api($ValidateUrl));

	if(isset($Response -> access_token) && $Response -> access_token != ''){
		return $Response -> access_token;
	}else{
		die('Error in fetching access token.');
	}
}

/**
 * LoginRadius function - To fetch social profile data from the user's social account after authentication. The social profile will be retrieved via oAuth and OpenID protocols. The data is normalized into LoginRadius' standard data format.
 *
 * @param string $accessToken LoginRadius access token
 * @param boolean $raw        If true, raw data is fetched
 *
 * @return object User profile data.
 */ 
public function loginradius_get_user_profiledata($accessToken, $raw = false){
        $ValidateUrl = 'https://' . LR_DOMAIN . '/api/v2/userprofile?access_token=' . $accessToken;
	if($raw){
            $ValidateUrl = 'https://' . LR_DOMAIN . '/api/v2/userprofile/raw?access_token=' . $accessToken;
		return $this->loginradius_call_api($ValidateUrl);
	}
	return json_decode($this->loginradius_call_api($ValidateUrl));
}

/**
 * LoginRadius function - To get the Albums data from the user's social account. The data will be normalized into LoginRadius' data format.
 *
 * @param string $accessToken LoginRadius access token
 * @param boolean $raw If true, raw data is fetched
 *
 * @return object User's albums data.
 */ 
public function loginradius_get_photo_albums($accessToken, $raw = false){
	$ValidateUrl = "https://".LR_DOMAIN."/api/v2/album?access_token=".$accessToken;
	if($raw){
		$ValidateUrl = "https://".LR_DOMAIN."/api/v2/album/raw?access_token=".$accessToken;
		return $this->loginradius_call_api($ValidateUrl);
	}
	return json_decode($this->loginradius_call_api($ValidateUrl));
}

/**
 * LoginRadius function - To fetch photo data from the user's social account. The data will be normalized into LoginRadius' data format.
 *
 * @param string $accessToken LoginRadius access token
 * @param string $albumId ID of the album to fetch photos from
 * @param boolean $raw If true, raw data is fetched
 *
 * @return object User's photo data.
 */ 
public function loginradius_get_photos($accessToken, $albumId, $raw = false){
	$ValidateUrl = "https://".LR_DOMAIN."/api/v2/photo?access_token=".$accessToken."&albumid=".$albumId;
	if($raw){
		$ValidateUrl = "https://".LR_DOMAIN."/api/v2/photo/raw?access_token=".$accessToken."&albumid=".$albumId;
		return $this->loginradius_call_api($ValidateUrl);
	}
	return json_decode($this->loginradius_call_api($ValidateUrl));
}

/**
 * LoginRadius function - To fetch check-ins data from the user's social account. The data will be normalized into LoginRadius' data format.
 *
 * @param string $accessToken LoginRadius access token
 * @param boolean $raw If true, raw data is fetched
 *
 * @return object User's check-ins.
 */ 
public function loginradius_get_checkins($accessToken, $raw = false){
	$ValidateUrl = "https://".LR_DOMAIN."/api/v2/checkin?access_token=".$accessToken;
	if($raw){
		$ValidateUrl = "https://".LR_DOMAIN."/api/v2/checkin/raw?access_token=".$accessToken;
		return $this->loginradius_call_api($ValidateUrl);
	}
	return json_decode($this->loginradius_call_api($ValidateUrl));
}

/**
 * LoginRadius function - To fetch user's audio files data from the user's social account. The data will be normalized into LoginRadius' data format.
 *
 * @param string $accessToken LoginRadius access token
 * @param boolean $raw If true, raw data is fetched
 *
 * @return object User's audio files data.
 */ 
public function loginradius_get_audio($accessToken, $raw = false){
	$ValidateUrl = "https://".LR_DOMAIN."/api/v2/audio?access_token=".$accessToken;
	if($raw){
		$ValidateUrl = "https://".LR_DOMAIN."/api/v2/audio/raw?access_token=".$accessToken;
		return $this->loginradius_call_api($ValidateUrl);
	}
	return json_decode($this->loginradius_call_api($ValidateUrl));
}

/**
 * LoginRadius function - Post messages to the user's contacts. After using the Contact API, you can send messages to the retrieved contacts.
 *
 * @param string $accessToken LoginRadius access token
 * @param string $to          Social ID of the receiver
 * @param string $subject     Subject of the message
 * @param string $message     Message
 *
 * @return bool True on success, false otherwise
 */ 
public function loginradius_send_message($accessToken, $to, $subject, $message){
	$Url = "https://".LR_DOMAIN."/api/v2/message?".http_build_query(array(
		'access_token' => $accessToken,
		'to' => $to,
		'subject' => $subject,
		'message' => $message
	));
	return json_decode($this->loginradius_call_api($Url, true));
}

/**
 * LoginRadius function - To fetch user's contacts/friends/connections data from the user's social account. The data will normalized into LoginRadius' data format.
 *
 * @param string $accessToken LoginRadius access token
 * @param integer $nextCursor Offset to start fetching contacts from
 * @param boolean $raw If true, raw data is fetched
 *
 * @return object User's contacts/friends/followers.
 */ 
public function loginradius_get_contacts($accessToken, $nextCursor = '', $raw = false){
	$ValidateUrl = "https://".LR_DOMAIN."/api/v2/contact?access_token=".$accessToken."&nextcursor=".$nextCursor;
	if($raw){
		$ValidateUrl = "https://".LR_DOMAIN."/api/v2/contact/raw?access_token=".$accessToken."&nextcursor=".$nextCursor;
		return $this->loginradius_call_api($ValidateUrl);
	}
	return json_decode($this->loginradius_call_api($ValidateUrl));
}

/**
 * LoginRadius function - To get mention data from the user's social account. The data will be normalized into LoginRadius' data format.
 *
 * @param string $accessToken LoginRadius access token
 * @param boolean $raw If true, raw data is fetched
 *
 * @return object User's twitter mentions.
 */ 
public function loginradius_get_mentions($accessToken, $raw = false){
	$ValidateUrl = "https://".LR_DOMAIN."/api/v2/mention?access_token=".$accessToken;
	if($raw){
		$ValidateUrl = "https://".LR_DOMAIN."/api/v2/mention/raw?access_token=".$accessToken;
		return $this->loginradius_call_api($ValidateUrl);
	}
	return json_decode($this->loginradius_call_api($ValidateUrl));
}

/**
 * LoginRadius function - To fetch information of the people, user is following on Twitter.
 *
 * @param string $accessToken LoginRadius access token
 * @param boolean $raw If true, raw data is fetched
 *
 * @return object Information of the people, user is following.
 */ 
public function loginradius_get_following($accessToken, $raw = false){
	$ValidateUrl = "https://".LR_DOMAIN."/api/v2/following?access_token=".$accessToken;
	if($raw){
		$ValidateUrl = "https://".LR_DOMAIN."/api/v2/following/raw?access_token=".$accessToken;
		return $this->loginradius_call_api($ValidateUrl);
	}
	return json_decode($this->loginradius_call_api($ValidateUrl));
}

/**
 * LoginRadius function - To get the event data from the user's social account. The data will be normalized into LoginRadius' data format.
 *
 * @param string $accessToken LoginRadius access token
 * @param boolean $raw If true, raw data is fetched
 *
 * @return object User's event data.
 */ 
public function loginradius_get_events($accessToken, $raw = false){
	$ValidateUrl = "https://".LR_DOMAIN."/api/v2/event?access_token=".$accessToken;
	if($raw){
		$ValidateUrl = "https://".LR_DOMAIN."/api/v2/event/raw?access_token=".$accessToken;
		return $this->loginradius_call_api($ValidateUrl);
	}
	return json_decode($this->loginradius_call_api($ValidateUrl));
}

/**
 * LoginRadius function - To get posted messages from the user's social account. The data will be normalized into LoginRadius' data format.
 *
 * @param string $accessToken LoginRadius access token
 * @param boolean $raw If true, raw data is fetched
 *
 * @return object User's posted messages.
 */ 
public function loginradius_get_posts($accessToken, $raw = false){
	$ValidateUrl = "https://".LR_DOMAIN."/api/v2/post?access_token=".$accessToken;
	if($raw){
		$ValidateUrl = "https://".LR_DOMAIN."/api/v2/post/raw?access_token=".$accessToken;
		return $this->loginradius_call_api($ValidateUrl);
	}
	return json_decode($this->loginradius_call_api($ValidateUrl));
}

/**
 * LoginRadius function - To get the followed company's data in the user's social account. The data will be normalized into LoginRadius' data format.
 *
 * @param string $accessToken LoginRadius access token
 * @param boolean $raw If true, raw data is fetched
 *
 * @return object Companies followed by user.
 */ 
public function loginradius_get_followed_companies($accessToken, $raw = false){
	$ValidateUrl = "https://".LR_DOMAIN."/api/v2/company?access_token=".$accessToken;
	if($raw){
		$ValidateUrl = "https://".LR_DOMAIN."/api/v2/company/raw?access_token=".$accessToken;
		return $this->loginradius_call_api($ValidateUrl);
	}
	return json_decode($this->loginradius_call_api($ValidateUrl));
}

/**
 * LoginRadius function - To get group data from the user's social account. The data will be normalized into LoginRadius' data format.
 *
 * @param string $accessToken LoginRadius access token
 * @param boolean $raw If true, raw data is fetched
 *
 * @return object Group data.
 */ 
public function loginradius_get_groups($accessToken, $raw = false){
	$ValidateUrl = "https://".LR_DOMAIN."/api/v2/group?access_token=".$accessToken;
	if($raw){
		$ValidateUrl = "https://".LR_DOMAIN."/api/v2/group/raw?access_token=".$accessToken;
		return $this->loginradius_call_api($ValidateUrl);
	}
	return json_decode($this->loginradius_call_api($ValidateUrl));
}

/**
 * LoginRadius function - To get the status messages from the user's social account. The data will be normalized into LoginRadius' data format.
 *
 * @param string $accessToken LoginRadius access token
 * @param boolean $raw If true, raw data is fetched
 *
 * @return object Status messages.
 */ 
public function loginradius_get_status($accessToken, $raw = false){
	$ValidateUrl = "https://".LR_DOMAIN."/api/v2/status?access_token=".$accessToken;
	if($raw){
		$ValidateUrl = "https://".LR_DOMAIN."/api/v2/status/raw?access_token=".$accessToken;
		return $this->loginradius_call_api($ValidateUrl);
	}
	return json_decode($this->loginradius_call_api($ValidateUrl));
}

/**
 * LoginRadius function - To update the status on the user's wall.
 * 
 * @param string $accessToken LoginRadius access token
 * @param string $title       Title for status message (Optional).
 * @param string $url         A web link of the status message (Optional).
 * @param string $imageurl    An image URL of the status message (Optional).
 * @param string $status      The status message text (Required).
 * @param string $caption     Caption of the status message (Optional).
 * @param string $description Description of the status message (Optional).
 *
 * @return boolean Returns true if successful, false otherwise.
 */ 
public function loginradius_post_status($accessToken, $title, $url, $imageurl, $status, $caption, $description){
	$Url = "https://".LR_DOMAIN."/api/v2/status?" . http_build_query(array(
		'access_token' => $accessToken,
		'title' => $title,
		'url' => $url,
		'imageurl' => $imageurl,
		'status' => $status,
		'caption' => $caption,
		'description' => $description
	));
	return json_decode($this->loginradius_call_api($Url, true));
}

/**
 * LoginRadius function - To get videos data from the user's social account. The data will be normalized into LoginRadius' data format.
 *
 * @param string $accessToken LoginRadius access token
 * @param boolean $raw If true, raw data is fetched
 *
 * @return object Videos data.
 */ 
public function loginradius_get_videos($accessToken, $raw = false){
	$ValidateUrl = "https://".LR_DOMAIN."/api/v2/video?access_token=".$accessToken;
	if($raw){
		$ValidateUrl = "https://".LR_DOMAIN."/api/v2/video/raw?access_token=".$accessToken;
		return $this->loginradius_call_api($ValidateUrl);
	}
	return json_decode($this->loginradius_call_api($ValidateUrl));
}

/**
 * LoginRadius function - To get likes data from the user's social account. The data will be normalized into LoginRadius' data format.
 *
 * @param string $accessToken LoginRadius access token
 * @param boolean $raw If true, raw data is fetched
 *
 * @return object Videos data.
 */ 
public function loginradius_get_likes($accessToken, $raw = false){
	$ValidateUrl = "https://".LR_DOMAIN."/api/v2/like?access_token=".$accessToken;
	if($raw){
		$ValidateUrl = "https://".LR_DOMAIN."/api/v2/like/raw?access_token=".$accessToken;
		return $this->loginradius_call_api($ValidateUrl);
	}
	return json_decode($this->loginradius_call_api($ValidateUrl));
}

/**
 * LoginRadius function - To get the page data from the user's social account. The data will be normalized into LoginRadius' data format.
 *
 * @param string $accessToken LoginRadius access token
 * @param string $pageName Page name
 * @param boolean $raw If true, raw data is fetched
 *
 * @return object Page data.
 */ 
public function loginradius_get_pages($accessToken, $pageName, $raw = false){
	$ValidateUrl = "https://".LR_DOMAIN."/api/v2/page?access_token=".$accessToken."&pagename=".$pageName;
	if($raw){
		$ValidateUrl = "https://".LR_DOMAIN."/api/v2/page/raw?access_token=".$accessToken."&pagename=".$pageName;
		return $this->loginradius_call_api($ValidateUrl);
	}
	return json_decode($this->loginradius_call_api($ValidateUrl));
}

/**
 * LoginRadius function - To fetch data from the LoginRadius API URL.
 *
 * @param string $ValidateUrl - Target URL to fetch data from.
 *
 * @return string - data fetched from the LoginRadius API.
 */
public function loginradius_call_api( $ValidateUrl, $post = false ) {
    $args = array(
        'timeout' => 30,
        'sslverify' => 'false',
    );
    if( $post == true){
    	$loginRadiusResponse = wp_remote_post( $ValidateUrl, $args );
    }else{
    	$loginRadiusResponse = wp_remote_get( $ValidateUrl, $args );
    }
    if ( is_wp_error( $loginRadiusResponse ) ) {
        $currentErrorResponse = "Something went wrong: " . $loginRadiusResponse->get_error_message();
        return $currentErrorResponse;
    } else {
        $JsonResponse = $loginRadiusResponse['body'];
        return $JsonResponse;
    }
}

}
?>