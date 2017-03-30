<?php
	header('Access-Control-Allow-Origin: *');
	header('P3P: CP="CAO PSA OUR"');
	session_start();
	include_once('/var/www/html/shopify.gluu.org/public_html/lib/config.lib.php');
	include_once('/var/www/html/shopify.gluu.org/public_html/lib/shopify.php');
	require_once '/var/www/html/shopify.gluu.org/public_html/oxd-glu/Get_tokens_by_code.php';
	require_once '/var/www/html/shopify.gluu.org/public_html/oxd-glu/Get_user_info.php';
	$code=$_REQUEST['code'];
	$api_key='f2bee041667a2edd64f21054c4190987';
	$state=$_REQUEST['state'];
	$val = mysqli_query($conn,'select * from `glulogin` LIMIT 1');
	$row1=mysqli_fetch_assoc($val);
	$oxd_id=trim($row1['oxd_id']);
	$shop=$row1['shopname'];
	$shopacesstoken=mysqli_query($conn,"SELECT * FROM `tbl_usersettings` WHERE `store_name` LIKE '$shop'");
	$getfinalshoptoken=mysqli_fetch_assoc($shopacesstoken);
	$token=$getfinalshoptoken['access_token'];
    $get_tokens_by_code = new Get_tokens_by_code();
    $get_tokens_by_code->setRequestOxdId($oxd_id);
    $get_tokens_by_code->setRequestCode($code);
    $get_tokens_by_code->setRequestState($state);
    $get_tokens_by_code->request();
    $_SESSION['user_oxd_id_token'] = $get_tokens_by_code->getResponseIdToken();
    $_SESSION['state'] = $_REQUEST['state'];
    $_SESSION['session_state'] = $_REQUEST['session_state'];
    $get_user_info = new Get_user_info();
    $get_user_info->setRequestOxdId($oxd_id);
    $get_user_info->setRequestAccessToken($get_tokens_by_code->getResponseAccessToken());
    $get_user_info->request();
    $allinfomation=$get_user_info->getResponseObject();
  $email=$allinfomation->data->claims->email[0];
   $username=$allinfomation->data->claims->preferred_username[0];
   $sql="update `glulogin` set name ='$username',email='$email' where oxd_id ='$oxd_id'";
   $result=mysqli_query($conn,$sql);
   $selectoxd=mysqli_query($conn,"SELECT * FROM `glulogin`");
	$oxdids=mysqli_fetch_assoc($selectoxd);
	$name=$oxdids['name'];
	$email=$oxdids['email'];
	
	$data=array(
	  "customer" => array(
		"first_name"=> $name,
		"last_name"=> $name,
		"email"=> $email,
		"verified_email"=> true,
		"password"=> "newpass",
		"password_confirmation"=> "newpass",
		"send_email_welcome"=> false
		),
	  );

        $url = "https://f2bee041667a2edd64f21054c4190987:' . $token . '@' . $shop . '/admin/customers.json";

        $session = curl_init($url);
    
    curl_setopt($session, CURLOPT_HEADER, false);
    curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($session, CURLOPT_HTTPHEADER, array('X-HTTP-Method-Override: POST') );
    curl_setopt($session, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($session, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($session, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    if(ereg("^(https)",$url)) curl_setopt($session,CURLOPT_SSL_VERIFYPEER,false);
    
    $response = curl_exec($session);
	if(!empty($allinfomation))
   {
   echo '<script type="text/javascript">self.close(); opener.location.href = "https://' . $shop . '/account/";</script>';
	}

  
   

