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
	add_menu_page('Wp Hook','Wp Hook','manage_options','lbbch-options','lbbch_settings_page' );
}

//Register styles
function lbbch_admin_style() {
  wp_register_style( 'lbbch_style', esc_url_raw( plugins_url( 'css/lbbch-style.css', __FILE__ ) ),"", '1.1', "all");
  wp_enqueue_style( 'lbbch_style' );
}
add_action( 'admin_enqueue_scripts', 'lbbch_admin_style' );

//Register scripts
wp_enqueue_script( 'lbbch-custom', esc_url_raw( plugins_url( 'js/lbbch-custom.js', __FILE__ ) ),"", '1.1', true );
wp_enqueue_script( 'jquery.validate-json', esc_url_raw( plugins_url( 'js/jquery.validate-json.js', __FILE__ ) ),"", '1.1', true );
//Handle pages request
function lbbch_settings_page(){
	global $wpdb; // this is how you get access to the database
	
	$tab = $_GET["tab"];
  switch ($tab) {
    case "":
      $records  = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."web_hooks where delete_status=0 order by id desc");
      $total    = count($records);
			$per_page = 10;
			$page     = isset( $_GET['cpage'] ) ? abs( (int) $_GET['cpage'] ) : 1;
			$offset   = ( $page * $per_page ) - $per_page;
			$data     = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."web_hooks where delete_status=0 ORDER BY id desc LIMIT ${offset}, ${per_page}",ARRAY_A);
			include "view/lbb_hook_list.php";
      break;
		case "hook-logs":
		  $records  = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."web_logs order by id desc");
			$total    = count($records);
			$per_page = 50;
			$page     = isset( $_GET['cpage'] ) ? abs( (int) $_GET['cpage'] ) : 1;
			$offset   = ( $page * $per_page ) - $per_page;
			$data     = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."web_logs ORDER BY id desc LIMIT ${offset}, ${per_page}",ARRAY_A);
			include "view/lbb_hook_logs.php";
      break;
	  case "hook-form":
			include "view/lbb_hook_form.php";
      break;
		case "edit-hook-form":
		  $id = $_GET["id"];
			if(empty($id)){
				echo "Id is required";
			}else{
		    $result = $wpdb->get_row("SELECT * FROM ".$wpdb->prefix."web_hooks where id=".$id." and delete_status=0",ARRAY_A);
				if(empty($result))
				{
					echo "No result found with this hook id";
				}else{
				  include "view/lbb_edit_hook_form.php";
				}
			}
      break;
		case "change-status":
			$id = $_GET["id"];
			if($_GET["status"] == 1){
			  $status =  0;
			}else{
				$status =  1;
			}
			$wpdb->update( $wpdb->prefix."web_hooks",
                     array('status'  => $status), 
											array( 'id' => $id ) 		
								   );
			echo "<script>window.location.href='?page=lbbch-options'</script>";
			exit;
      break;
		case "delete-hook":
		  $id = $_GET["id"];
			$wpdb->delete( $wpdb->prefix."web_hooks", 
		                 array("id" => $id)
								   );
			echo "<script>window.location.href='?page=lbbch-options'</script>";
			exit;
      break;
    default:
	}
		
	
}

//Hooks for adding data to database using ajax
add_action('wp_ajax_lbbhc_hit_url', 'lbbhc_hit_url_callback');

//Submitting the request of new hook to database
function lbbhc_hit_url_callback($url,$bodies,$headers) {
  global $wpdb; // this is how you get access to the database
	//$form = json_decode($_REQUEST["form"]);
	$request = $_REQUEST;
	$id      = !empty($request["id"]) ? $request["id"] : "";
	//Parse the url params
	$urlparams  = "";
	$urlskey    = "";
	$urlsvalue  = "";

	if(!empty($request["urlparams"])){
		foreach($request["urlparams"] as $urlkey => $urlvalue){
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
		$bodypost = $bodies;
		if($request["method"] == "DELETE"){
		  $bodies = json_encode($bodies);
	  }
	}else{
		$bodies = stripslashes($request["body-raw-value"]);
	}
	$bodyarray = json_encode(array("form-data" => $request["formdata_type"],"get" => $urlparams,"post" => $bodypost));
	
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
	curl_setopt($ch, CURLOPT_HEADER, true);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $request["method"]);
	
	//print_r($bodies);exit;
	if(isset($bodies) && $bodies != ''){
		curl_setopt($ch, CURLOPT_POSTFIELDS, $bodies);
	}
	
	// $output contains the output string
	$output = curl_exec($ch);
	$getinfo = curl_getinfo($ch);
	curl_close($ch);
	
	$response = explode("\r\n\r\n", $output, 2);
	$returnHeader = json_encode(get_headers_from_curl_response($response[0]));
	$returnBody   = $response[1];
	
	if($getinfo["http_code"] == 200){
		$responseArray = array("header" => $returnHeader,"body" => $returnBody);
		if(!empty($id)){
			$wpdb->update($wpdb->prefix."web_hooks",
			              array('hook_for'  => stripslashes($_POST['hook_for']), 
												 'call_type'  => stripslashes($_POST['method']),
												 'url'        => stripslashes($_POST['url']),
												 'data'       => stripslashes($bodyarray),
												 'headers'    => stripslashes(json_encode($headers)),
												 'response'   => json_encode($responseArray),
												),
										array( 'id' => $id ) 
										);
		}else{
		//Insert hook data to table
		$wpdb->insert( $wpdb->prefix."web_hooks", 
		               array('hook_for'   => stripslashes($_POST['hook_for']), 
												 'call_type'  => stripslashes($_POST['method']),
												 'url'        => stripslashes($_POST['url']),
												 'data'       => stripslashes($bodyarray),
												 'headers'    => stripslashes(json_encode($headers)),
												 'response'   => json_encode($responseArray),
												) 
								 );
		}
	}
  $message           = "";
	$message[0] = $returnHeader;
	$message[1] = $returnBody;
	
	echo implode("||||",$message);
	do_action ( "lbbhc_hit_url_callbacksas" );
	die();
}


//Hook function for post/page call
function lbbhc_get_post_page() {
  global $wpdb; // this is how you get access to the database
	//$form = json_decode($_REQUEST["form"]);
	$request = $_REQUEST;
	
	$resultdata    = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."web_hooks WHERE hook_for = '".$request["post_type"]."' and status=1 and delete_status=0",ARRAY_A);
	foreach($resultdata as $postdata){
		$data        = json_decode($postdata["data"],true);
		$method      = strtoupper($postdata["call_type"]);
		$headerdata  = json_decode($postdata["headers"],true);
		
		$bodyGetData  = $data["get"];
		$bodyPostData = $data["post"];
		//Parse the url params
		$urlparams  = "";
		
		if(!empty($bodyGetData)){
			foreach($bodyGetData as $bodygetkey => $bodygetvalue){
				if (strpos($bodygetvalue, '%%') !== false) {
					$val   = trim(str_replace("%%","",$bodygetvalue));
					$value = $_REQUEST[$val];
					$data["data"]["get"][$bodygetkey] = $value;
				}else{
					$data["data"]["get"][$bodygetkey] = $bodygetvalue;
				}
			}
			$urlparams = $data["data"]["get"];
		}
		
		$bodyparams  = "";
		
		if(!empty($bodyPostData)){
			foreach($bodyPostData as $bodypostkey => $bodypostvalue){
				if (strpos($bodypostvalue, '%%') !== false) {
					$val   = trim(str_replace("%%","",$bodypostvalue));
					$value = $_REQUEST[$val];
					$data["data"]["post"][$bodypostkey] = $value;
				}else{
					$data["data"]["post"][$bodypostkey] = $bodypostvalue;
				}
				$bodyparams = $data["data"]["post"];
				if($method == "POST"){
					$bodyparams = json_encode($bodyparams);
				}
			}
		}
		
		//Parse the header params
		$headers = "";
		if(!empty($headerdata)){
			foreach($headerdata as $headerkey => $headdervalue){
				$headers[] = $headerkey.':'.$headdervalue;
			}
		}

		$querystring = !empty($urlparams) ? "?".http_build_query($urlparams) : "";
		$url         = $postdata["url"].$querystring;
		$ch          = curl_init();
		// set url
		curl_setopt($ch, CURLOPT_URL, $url);
		//return the transfer as a string
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		//$headers[] = 'client_id: wpweb';
		//$headers[] = 'client_secret: 5dfe726a08c37e7dab97f6d02041766ca6008a24231c3797bf94761a90a19d7b';
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_HEADER, true);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
		
		//print_r($bodies);exit;
		if(isset($bodies) && $bodies != ''){
			curl_setopt($ch, CURLOPT_POSTFIELDS, $bodyparams);
		}
		
		// $output contains the output string
		$output  = curl_exec($ch);
		$getinfo = curl_getinfo($ch);
		curl_close($ch);
		$response     = explode("\r\n\r\n", $output, 2);
		$returnHeader = json_encode(get_headers_from_curl_response($response[0]));
		$returnBody   = $response[1];
		
		//Insert hook data to table
		$wpdb->insert( $wpdb->prefix."web_logs", 
									 array(
												 'hook_id'        => stripslashes($postdata['id']),
												 'post_id'        => stripslashes($request["post_ID"]),
												 'post_type'      => stripslashes($request["post_type"]),
												 'response_code'  => stripslashes($getinfo["http_code"]),
												 'date_added'     => date("Y-m-d H:i:s"),
												 'response'       => $output,
												) 
									);
		//do_action ( "lbbhc_hit_url_callbacksas" );
	}
	return true;
	//die();
}

//Convert headers data to array
function get_headers_from_curl_response($response)
{
    $headers = array();
    foreach (explode("\r\n", $response) as $i => $line)
        if ($i === 0)
            $headers['http_code'] = $line;
        else
        {
            list ($key, $value) = explode(': ', $line);

            $headers[$key] = $value;
        }

    return $headers;
}
//Custom hooh when performing action with post/page
add_action( 'transition_post_status', 'lbbhc_get_post_page', 10,3 );

function response_codes($code)
{
	$http_codes = array(
    100 => 'Continue',
    101 => 'Switching Protocols',
    102 => 'Processing',
    200 => 'OK',
    201 => 'Created',
    202 => 'Accepted',
    203 => 'Non-Authoritative Information',
    204 => 'No Content',
    205 => 'Reset Content',
    206 => 'Partial Content',
    207 => 'Multi-Status',
    300 => 'Multiple Choices',
    301 => 'Moved Permanently',
    302 => 'Found',
    303 => 'See Other',
    304 => 'Not Modified',
    305 => 'Use Proxy',
    306 => 'Switch Proxy',
    307 => 'Temporary Redirect',
    400 => 'Bad Request',
    401 => 'Unauthorized',
    402 => 'Payment Required',
    403 => 'Forbidden',
    404 => 'Not Found',
    405 => 'Method Not Allowed',
    406 => 'Not Acceptable',
    407 => 'Proxy Authentication Required',
    408 => 'Request Timeout',
    409 => 'Conflict',
    410 => 'Gone',
    411 => 'Length Required',
    412 => 'Precondition Failed',
    413 => 'Request Entity Too Large',
    414 => 'Request-URI Too Long',
    415 => 'Unsupported Media Type',
    416 => 'Requested Range Not Satisfiable',
    417 => 'Expectation Failed',
    418 => 'I\'m a teapot',
    422 => 'Unprocessable Entity',
    423 => 'Locked',
    424 => 'Failed Dependency',
    425 => 'Unordered Collection',
    426 => 'Upgrade Required',
    449 => 'Retry With',
    450 => 'Blocked by Windows Parental Controls',
    500 => 'Internal Server Error',
    501 => 'Not Implemented',
    502 => 'Bad Gateway',
    503 => 'Service Unavailable',
    504 => 'Gateway Timeout',
    505 => 'HTTP Version Not Supported',
    506 => 'Variant Also Negotiates',
    507 => 'Insufficient Storage',
    509 => 'Bandwidth Limit Exceeded',
    510 => 'Not Extended'
  );
	return $http_codes[$code];
}
//Activate plugin
function lbbch_wp_activate() {
	global $wpdb;
	//Create hook table
	$wpdb->query("CREATE TABLE IF NOT EXISTS ".$wpdb->prefix."web_hooks (
			`id` int(11) NOT NULL,
			`hook_for` varchar(50) NOT NULL,
			`call_type` varchar(10) NOT NULL,
			`url` text NOT NULL,
			`data` text NOT NULL,
			`headers` text,
			`response` text NOT NULL,
			`status` int(11) NOT NULL DEFAULT '1',
			`delete_status` int(11) NOT NULL DEFAULT '0'
		) ENGINE=InnoDB DEFAULT CHARSET=latin1;");
	$wpdb->query("ALTER TABLE `wp_hooks`
		ADD PRIMARY KEY (`id`);");
	$wpdb->query("ALTER TABLE `wp_hooks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;");
	
	//Create logs table
	$wpdb->query("CREATE TABLE ".$wpdb->prefix."web_logs (
		`id` int(11) NOT NULL,
		`hook_id` int(11) NOT NULL,
		`post_id` int(11) NOT NULL,
		`post_type` varchar(20) DEFAULT NULL,
		`response_code` int(11) NOT NULL,
		`date_added` datetime DEFAULT NULL,
		`response` text
	) ENGINE=InnoDB DEFAULT CHARSET=latin1;");
	$wpdb->query("ALTER TABLE ".$wpdb->prefix."web_logs
    ADD PRIMARY KEY (`id`);");
	$wpdb->query("ALTER TABLE ".$wpdb->prefix."web_logs
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;");
}
register_activation_hook( __FILE__, 'lbbch_wp_activate' );

//Deactivate plugin
function lbbch_wp_deactivate() {
	global $wpdb;
	$wpdb->query( "DROP TABLE IF EXISTS ".$wpdb->prefix."web_hooks" );
	$wpdb->query( "DROP TABLE IF EXISTS ".$wpdb->prefix."web_logs" );
}

register_deactivation_hook( __FILE__, 'lbbch_wp_deactivate' );

?>
