<?php
header('Access-Control-Allow-Origin: *');
header('P3P: CP="CAO PSA OUR"');
session_start();
$_POST['your_mail']='sumiti@ourdesignz.com';
$shopname=$_POST['shopname'];
include_once('/var/www/html/shopify.gluu.org/public_html/lib/config.lib.php');
require_once './Get_authorization_url.php';
require_once './Register_site.php';
        $register_site = new Register_site();
        $register_site->setRequestOpHost($_POST['url']);
        $register_site->setRequestAcrValues(Oxd_RP_config::$acr_values);
        $register_site->setRequestAuthorizationRedirectUri(Oxd_RP_config::$authorization_redirect_uri);
        $register_site->setRequestPostLogoutRedirectUri(Oxd_RP_config::$post_logout_redirect_uri);
        $register_site->setRequestContacts([$_POST['your_mail']]);
        $register_site->setRequestGrantTypes(Oxd_RP_config::$grant_types);
        $register_site->setRequestResponseTypes(Oxd_RP_config::$response_types);
        $register_site->setRequestScope(Oxd_RP_config::$scope);
        $register_site->request();

        if($register_site->getResponseOxdId()){
            //save in your database
            $_SESSION['oxd_id'] = $register_site->getResponseOxdId();
            require_once './Update_site_registration.php';
            $update_site_registration = new Update_site_registration();
            $update_site_registration->setRequestAcrValues(Oxd_RP_config::$acr_values);
            $update_site_registration->setRequestOxdId($_SESSION['oxd_id']);
            $update_site_registration->setRequestAuthorizationRedirectUri(Oxd_RP_config::$authorization_redirect_uri);
            $update_site_registration->setRequestPostLogoutRedirectUri(Oxd_RP_config::$post_logout_redirect_uri);
            $update_site_registration->setRequestContacts([$_POST['your_mail']]);
            $update_site_registration->setRequestGrantTypes(Oxd_RP_config::$grant_types);
            $update_site_registration->setRequestResponseTypes(Oxd_RP_config::$response_types);
            $update_site_registration->setRequestScope(Oxd_RP_config::$scope);
            $update_site_registration->request();
            $_SESSION['oxd_id'] = $update_site_registration->getResponseOxdId();
            $oxd_id=$_SESSION['oxd_id'];
            $selectoxd=mysqli_query($conn,"SELECT * FROM `glulogin`");
            $oxdids=mysqli_fetch_assoc($selectoxd);
            if(empty($oxdids))
            {
            $sql="INSERT INTO `glulogin` (`oxd_id`) VALUES ('$oxd_id')";
			$result=mysqli_query($conn,$sql);
		    }
		    else
		    {
				$sql="update `glulogin` set oxd_id ='$oxd_id',shopname='$shopname' where id=1";
				$result=mysqli_query($conn,$sql);
			}
			
        }
	$get_authorization_url = new Get_authorization_url();
    $get_authorization_url->setRequestOxdId($_SESSION['oxd_id']);
    $get_authorization_url->setRequestScope(Oxd_RP_config::$scope);
    $get_authorization_url->setRequestAcrValues(Oxd_RP_config::$acr_values);
    $get_authorization_url->request();
	echo $url=$get_authorization_url->getResponseAuthorizationUrl();
	

?>


