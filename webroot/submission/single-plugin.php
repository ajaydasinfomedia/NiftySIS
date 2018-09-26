<?php 
/**
 * Plugin Name:Single SignIn-plugin
 * Plugin URI: http://Login-plugin.com
 * Description: It is just simple created plugin for sign In from single site.
 * Version: 1.0
 * Author: Dasinfomedia
 * Author URI: http://dasinfomedai.com/wordpress-plugins
 * Text Domain: single_signin
 * Domain Path: Optional. Plugin's relative directory path to .mo files. Example: /locale/
 * Network: Optional. Whether the plugin can only be activated network wide. Example: true
 * License: GPL2
 */

 
define( 'LEARN_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
define( 'LEARN_PLUGIN_DIR', untrailingslashit( dirname( __FILE__ ) ) );
require_once(ABSPATH.'wp-admin/includes/user.php');
if(is_admin())
{
	function learn_install()
	{
		add_action( 'admin_menu', 'login_plugin_admin_menu' );
	}
	
   register_activation_hook(LEARN_PLUGIN_BASENAME, 'learn_install' );						
							
}

    function login_plugin_admin_menu() {
		
		add_menu_page('Learn Plugin', __('Login Plugin','learn_plugin'),'manage_options','learn_system','learn_system_dashboard'); 

		add_submenu_page('learn_system', 'Dashboard', __( 'Dashboard', 'learn_plugin' ), 'administrator', 'learn_system', 'learn_system_dashboard');
		
		
}	
function app_output_buffer() {
	session_start();
	ob_start();
} // soi_output_buffer

add_action('init', 'app_output_buffer');

/* add_action('wp_head','singleSingInAPIs');
function singleSingInAPIs()
{
	
	if(isset($_REQUEST['page']) && $_REQUEST['page']=='singlesing-api-login')
	{
		loginUserByApi($_REQUEST);
	}
	if(isset($_REQUEST['page']) && $_REQUEST['page']=='singlesing-register-api')
	{
		registerUserByApi($_REQUEST);
	}
	if(isset($_REQUEST['page']) && $_REQUEST['page']=='singlesing-passwordreset-api')
	{
		passwordResetUserByApi($_REQUEST);
	}
	if(isset($_REQUEST['page']) && $_REQUEST['page']=='singlesing-profileupdate-api')
	{
		profileUpdateByApi($_REQUEST);
	}
	if(isset($_REQUEST['page']) && $_REQUEST['page']=='singlesing-test')
	{
		testpageApi($_REQUEST);
	}
}
function testpageApi($data)
{
	// var_dump("1");exit;
		$url='http://166.78.47.59/spinac_api/isuserregister.php';
		$data1 = 'jid='.$data['country_code'].''.$data['phone'].'@spinac.com';
		// $url="https://www.google.co.in";
		
		$result=curlRequestSend($url,$data1);
		
}  */

function sitelisting_array()
{
	/* $site_array=array('http://192.168.1.22/wordpress/demo/spinacdirectory/',
					 'http://192.168.1.22/bhaskar/wordpress/spinaccareers/'); */
					 
	$site_array=array('https://spinac-homes.property/',
					  'https://spinacplaces.events/',
					  'https://spinacjobs.careers/',
					  'https://spinacclassified.directory/');				 
	return $site_array;
}

# Fires immediately after a new user is registered.

add_action('user_register','user_registration_fn');
function user_registration_fn($user_id)
{
echo "123";die();
exit;
	$_SESSION['password'] = $_POST['pwd'];
	$_SESSION['first_name'] = $_POST['first_name'];
	$_SESSION['last_name'] = $_POST['last_name'];
	
	update_user_meta( $user_id, 'first_name', $_POST['first_name'] );
	update_user_meta( $user_id, 'last_name', $_POST['last_name'] );
	add_user_meta( $user_id, 'country_code', $_POST['country_code'] );
	add_user_meta( $user_id, 'phone', $_POST['phone'] );
	
	$userdata=get_userdata($user_id);
	$userdata->country_code;
	$userdata->phone;
	$checkuserdata['country_code']=$userdata->country_code;
	$checkuserdata['phone']=$userdata->phone;
	var_dump($userdata);
	var_dump($checkuserdata);
	$result=checkUserIsRegistred($checkuserdata);
var_dump($result);
	if($result){
		$site_array=sitelisting_array();
		$actual_link = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]/";
		$site_new_array = array_diff($site_array, array($actual_link));
		$url2='http://166.78.47.59/spinac_api/insert_updatedata.php';
		$data2 = 'Wtable=tbl_user&Waction=insert&jid="'.$userdata->country_code.''.$userdata->phone.'@spinac.com"&email="'.$userdata->user_email.'"&password="'.$_SESSION['password'].'"&ostype=0&c2callId="test"';
		// var_dump($url2);
		// var_dump($data2);
		$curlresult=curlRequestSend($url2,$data2);
		// var_dump($curlresult);
		var_dump($site_new_array);
		foreach($site_new_array as $site)
		{
			$url = $site;
			$fields = 'page=singlesing-register-api&user_login='.$userdata->user_login.'&password='.$_SESSION['password'].'&email='.$userdata->user_email.'&first_name='.$userdata->first_name.'&last_name='.$userdata->last_name.'&country_code='.$userdata->country_code.'&phone='.$userdata->phone.'';
			$url = $site.''.$fields;
			var_dump($url);
			var_dump($fields);
			
			$curlresult=curlRequestSend($url,$fields);
			// echo "<br>------------";		
			// var_dump($curlresult);
				
		}
		exit;
	}
		
}
// check user is registred or not in mail DB
function checkUserIsRegistred($data)
{
	// var_dump("3");exit;
		$url='http://166.78.47.59/spinac_api/isuserregister.php';
		$data_chk = 'jid='.$data['country_code'].''.$data['phone'].'@spinac.com';
		//$url="https://www.google.co.in";
		return $result=curlRequestSend($url,$data_chk);	
}
// login by Api function
/* function loginUserByApi($data)
{
	
	$users=wp_authenticate($data['user_login'],$data['password'] );
	//$users = get_user_by( 'login', $_REQUEST['username']);
	$user_id=$users->ID;
	if($users){
		  wp_set_current_user( $user_id, $users->user_login );
		  wp_set_auth_cookie( $user_id );
		  do_action( 'wp_login', $users->user_login);
		  if(is_user_logged_in()){
			   wp_redirect ($data['rurl']);
			   exit;
		   } 
	 }
	 else
	 {
		 wp_redirect(site_url());
		 exit;
	 }	
		 
} */

function curlRequestSend($url,$data)
{
	
	$curl = curl_init();

		curl_setopt_array($curl, array(
		CURLOPT_URL => $url,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_SSL_VERIFYPEER => FALSE,
		CURLOPT_SSL_VERIFYHOST, false,
		CURLOPT_ENCODING => "",
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 600,
		CURLOPT_FOLLOWLOCATION=>0,
		CURLOPT_COOKIEFILE=>'',
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_POST=>8,
		CURLOPT_COOKIEJAR=> 'cookie.txt',
		CURLOPT_POSTFIELDS=>$data,
		CURLOPT_HTTPHEADER => array(
			"cache-control: no-cache",
			"postman-token: b6493915-57c6-6a3b-6c77-e444490f5f7e",
			"Cookie:langcookie=en; currentcurr=USD"
		  ),
		));
		
		// if(!curl_exec($curl)){
			// die('Error: "' . curl_error($curl) . '" - Code: ' . curl_errno($curl));
		// }
		
		$response = curl_exec($curl);
		$err = curl_error($curl);
	
		curl_close($curl);

		if ($err) {
		  echo "cURL Error #:" . $err;
		} else {
		// echo $response;
		}

		return $response;
	
}
?>