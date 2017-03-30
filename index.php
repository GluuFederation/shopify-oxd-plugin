<?php
//error_reporting(0);
include_once('lib/config.lib.php');
include_once('lib/session.lib.php');
include_once('lib/shopify.php');
if(isset($_GET['shop']))
{
	 $shop = $_GET['shop'];
	 $sql = "SELECT * FROM tbl_usersettings where store_name = '$shop' LIMIT 1";
	$result = mysqli_query($conn,$sql);
	if ($result->num_rows > 0) {
		// output data of each row
		$row = mysqli_fetch_assoc($result);

	  $_SESSION['shop'] = $row['store_name'];
		$_SESSION['token'] = $row['access_token'];
		$_SESSION['charge_id']=$row['charge_id'];
		
	}
}
$domain = $_SESSION['shop'];
$action = (isset($_GET['action'])) ? $_GET['action'] : 'index';

// Check for shopify authentication
if (isset($_SESSION['shop']) && isset($_SESSION['token'])){
	$shopifyClient = new ShopifyClient($_SESSION['shop'], $_SESSION['token'], SHOPIFY_API_KEY, SHOPIFY_SECRET);	
	// setup links in view
	$returnURL = 'https://' . $shopifyClient->shop_domain . '/admin'; 
	$mainnav = 	array(array('name' => 'Return to My Store', 	'href' => $returnURL, 'class' => ''));
	$shopURL = $shopifyClient->shop_domain;
}else{

	if($action=='site'){
		$action = "site";
		$mainnav = array(
			array('name' => 'Install', 'href' => getLink('site'), 'class' => ''));
	}else {
		// not authorized to get into the app so show them the authorization form
		$action = "authorize";
		$mainnav = array(
			array('name' => 'Install', 'href' => getLink('authorize'), 'class' => ''));
	}
}

/* based on the action, get a url */
function getLink($action='') {
	
	if (strlen($action) == 0){
		return 'index.php';
        }else {
		return 'index.php?action=' . $action;
	}
}

if (file_exists('core/' . $action . '.php'))
	include('core/' . $action . '.php');

if($action=='site'){
	include('templates/'.$action.'.php');
}else {
	$title = 'Product Description Tab';
	include('templates/header.php');
	include('templates/'.$action.'.php');
	//include('templates/footer.php');
}
?>
