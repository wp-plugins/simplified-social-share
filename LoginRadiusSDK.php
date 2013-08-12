<?php
/** 
 * This class contains functions to communicate with LoginRadius API. 
 */	 
class LoginRadius{ 
	/** 
	 * Check if CURL/FSOCKOPEN are working. 
	 */	 
	public function login_radius_check_connection($method){ 
		$ValidateUrl = "https://hub.loginradius.com/ping/ApiKey/ApiSecrete";
		$JsonResponse = $this->loginradius_call_api($ValidateUrl, $method);
		$UserAuth = json_decode($JsonResponse);
		if(isset($UserAuth->ok)){
			return "ok";
		}elseif($JsonResponse == "service connection timeout"){
			return "service connection timeout";
		}elseif($JsonResponse == "timeout"){
			return "timeout";
		}else{
			return "connection error";
		}
	}
	/** 
	 * Login/register user to LR. 
	 */	 
	public function login_radius_lr_login($ValidateUrl, $method){ 
		$JsonResponse = $this->loginradius_call_api($ValidateUrl, $method, true);
		$response = json_decode($JsonResponse);
		return $response;
	}
	/** 
	 * Fetch data from passed URL. 
	 */	 
	public function loginradius_call_api($ValidateUrl, $method = "", $post = false){
		if($method == "curl"){
			$curl_handle = curl_init(); 
			curl_setopt($curl_handle, CURLOPT_URL, $ValidateUrl); 
			curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 5);
			curl_setopt($curl_handle, CURLOPT_TIMEOUT, 5);
			if($post){
				curl_setopt($curl_handle, CURLOPT_POST, 1);
			}
			curl_setopt($curl_handle, CURLOPT_SSL_VERIFYPEER, false); 
			if(ini_get('open_basedir') == '' && (ini_get('safe_mode') == 'Off' or !ini_get('safe_mode'))){
				curl_setopt($curl_handle, CURLOPT_FOLLOWLOCATION, 1); 
				curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, true); 
			}else{
				curl_setopt($curl_handle, CURLOPT_HEADER, 1); 
				$url = curl_getinfo($curl_handle, CURLINFO_EFFECTIVE_URL);
				curl_close($curl_handle);
				$curl_handle = curl_init(); 
				$url = str_replace('?','/?',$url); 
				curl_setopt($curl_handle, CURLOPT_URL, $url); 
				curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, true);
			}
			$JsonResponse = curl_exec($curl_handle); 
			$httpCode = curl_getinfo($curl_handle, CURLINFO_HTTP_CODE);
			if(in_array($httpCode, array(400, 401, 404, 500, 503)) && $httpCode != 200){
				return "service connection timeout";
			}else{
				if(curl_errno($curl_handle) == 28){
					return "timeout";
				}
			}
			curl_close($curl_handle);
		}else{
			$JsonResponse = @file_get_contents($ValidateUrl);
			if(strpos(@$http_response_header[0], "400") !== false || strpos(@$http_response_header[0], "401") !== false || strpos(@$http_response_header[0], "403") !== false || strpos(@$http_response_header[0], "404") !== false || strpos(@$http_response_header[0], "500") !== false || strpos(@$http_response_header[0], "503") !== false){
				return "service connection timeout";
			}
		}
		return $JsonResponse; 
	} 
}
?>