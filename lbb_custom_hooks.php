<?php
/**
 * @package Lbb_Custom_Hooks
 * @version 1.0
 */
/*
Plugin Name: Lbb Custom Hooks
Plugin URI: http://wordpress.org/plugins/hello-dolly/
Description: This plugin is used for signle page hooks for hitting urls.
Author: Little Black Book(Jitendra Bansal)
Version: 1.0
*/

//Adding menu to admin panel
if ( is_admin() ){
	add_action( 'admin_menu', 'lbbch_admin_menu' );
	//add_action( 'admin_init', 'lbbch_settings_init' );
}

//Hook to add menu in admin panel
function lbbch_admin_menu() {
	global $rpco_settings_page;
	$rpco_settings_page = add_submenu_page( 'options-general.php', 'Custom Hook', 'Custom Hook', 'manage_options', 'lbbch-options', 'lbbch_settings_page' );
}

/**
 * Register API Javascript helpers.
 *
 * @see wp_register_scripts()
 */
 
wp_enqueue_script( 'lbbch-custom', esc_url_raw( plugins_url( 'lbbch-custom.js', __FILE__ ) ),"", '1.1', true );

//To render the menu dashboard page
function lbbch_settings_page(){
  include "view/lbb_hook_form.php";
}

add_action('wp_ajax_lbbhc_hit_url', 'lbbhc_hit_url_callback');

//Submitting the request
function lbbhc_hit_url_callback($url,$bodies,$headers) {
  global $wpdb; // this is how you get access to the database
	//$form = json_decode($_REQUEST["form"]);
	$request = $_REQUEST;
	
	//Parse the url params
	$urlparams  = "";
	$urlskey    = "";
	$urlsvalue  = "";
	if(!empty($request["urlparms"])){
		foreach($request["urlparms"] as $urlkey => $urlvalue){
			if($urlkey == "key"){
				$urlskey   = $urlvalue;
			}
			if($urlkey == "value"){
				$urlsvalue = $urlvalue;
			}
		}
	}
	
	$urlcount = count($urlskey);
	for($i=0;$i<$urlcount;$i++){
		if($urlskey[$i] != ""){
		  $urlparams[$urlskey[$i]] = $urlsvalue[$i];
		}
	}
	
	//Parse the header params
	$headers = "";
	$headerskey     = "";
	$headersvalue   = "";
	if(!empty($request["header"])){
		foreach($request["header"] as $headerkey => $headdervalue){
			if($headerkey == "key"){
				$headerskey   = $headdervalue;
			}
			if($headerkey == "value"){
				$headersvalue = $headdervalue;
			}
		}
	}
	
	$headercount = count($headerskey);
	for($i=0;$i<$headercount;$i++){
		if($headerskey[$i] != ""){
		  $headers[] = $headerskey[$i].':'.$headersvalue[$i];
		}
	}
	
	if($request["formdata_type"] == "form-data"){
		//Parse the body params
		$bodies         = "";
		$bodieskey      = "";
		$bodiesvalue    = "";
		$bodiesis_array = "";
		if(!empty($request["body"])){
			foreach($request["body"] as $bodykey => $bodyvalue){
				if($bodykey == "key"){
					$bodieskey   = $bodyvalue;
				}
				if($bodykey == "value"){
					$bodiesvalue = $bodyvalue;
				}
				if($bodykey == "is_array"){
					$bodiesis_array = $bodyvalue;
				}
			}
		}
		
		$bodiescount = count($bodieskey);
		for($i=0;$i<$bodiescount;$i++){
			if($bodieskey[$i] != ""){
				if($bodiesis_array[$i] == 1){
					$bodies[$bodieskey[$i]][] = $bodiesvalue[$i];
				}else{
					$bodies[$bodieskey[$i]] = $bodiesvalue[$i];
				}
			}
		}
		if($request["method"] == "DELETE"){
		  $bodies = json_encode($bodies);
	  }
	}else{
		$bodies = stripslashes($request["body-raw-value"]);
	}
	//die(); // this is required to return a proper result
	//$headers    = json_encode($request["header"]);
	//print_r($bodies);exit;
	$querystring = !empty($urlparams) ? "?".http_build_query($urlparams) : "";
	$url        = $_REQUEST["url"].$querystring;
	$ch         = curl_init();
	// set url
	curl_setopt($ch, CURLOPT_URL, $url);
	//return the transfer as a string
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	//$headers[] = 'client_id: wpweb';
	//$headers[] = 'client_secret: 5dfe726a08c37e7dab97f6d02041766ca6008a24231c3797bf94761a90a19d7b';
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $request["method"]);
	
	//print_r($bodies);exit;
	if(isset($bodies) && $bodies != ''){
		curl_setopt($ch, CURLOPT_POSTFIELDS, $bodies);
	}
	
	// $output contains the output string
	echo $output = curl_exec($ch);
	$response = curl_getinfo($ch);
	/*echo "<pre>";
	print_r($response);
	exit;*/
	//list($responseheader, $responsebody) = explode("\r\n\r\n", $output, 2);
	/*echo "<pre>";
	print_r(json_decode($output));
	echo "</pre>";*/
	//$result = curl_getinfo($ch);
	//print_r($result);
	// close curl resource to free up system resources
	curl_close($ch);
	die();
}

?>
