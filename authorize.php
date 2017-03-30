	<?php
	ob_start();
	include_once('lib/config.lib.php');
	include_once('lib/session.lib.php');
	include_once('lib/shopify.php');
	$storeurl=$_GET['shop'];
	//create table
	$val = mysqli_query($conn,'select 1 from `tmp_store` LIMIT 1');
	
	if($val !== FALSE)
	{
	   
	    $deletetable=mysqli_query($conn,"DROP TABLE tmp_store") ;
		$sql = "CREATE TABLE tmp_store (
		id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
		storename VARCHAR(255) NOT NULL)";
		mysqli_query($conn,$sql);
		$surl = "INSERT INTO tmp_store SET storename = '$storeurl'";
		$fsurl = mysqli_query($conn,$surl);
	}
	else
	{
		
		$sql = "CREATE TABLE tmp_store (
		id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
		storename VARCHAR(255) NOT NULL)";
		mysqli_query($conn,$sql);
		$surl = "INSERT INTO tmp_store SET storename = '$storeurl'";
		$fsurl = mysqli_query($conn,$surl);
	}	

// if the code param has been sent to this page... we are in Step 2
	if (isset($_GET['code']) || isset($_GET['shop']) ) 
	{
		
	$shop=$_GET['shop'];
	$sql = "SELECT * FROM tbl_usersettings where store_name = '$shop' LIMIT 1";
	$result = mysqli_query($conn,$sql);
	$row1=mysqli_fetch_assoc($result);
	$charge_id=$row1['charge_id'];
	$planstatus=$row1['planstatus'];
	// Step 2: do a form POST to get the access token
	$shopifyClient = new ShopifyClient($_GET['shop'], "", SHOPIFY_API_KEY, SHOPIFY_SECRET);
	session_unset();
	 $_SESSION['token'] = $shopifyClient->getAccessToken($_GET['code']);
	
	if($_SESSION['token'] == '')
	{
		$sql = "SELECT * FROM tbl_usersettings where store_name = '$shop' LIMIT 1";
		$result = mysqli_query($conn,$sql);
		$row=mysqli_fetch_row($result);
		$_SESSION['token']=$row[1];
	}
	if ($_SESSION['token'] != '' && $charge_id=='')
	{
		$_SESSION['shop'] = $_GET['shop'];
		// Insert Shop info to database.
		$token = $_SESSION['token'];
		$shop = $_SESSION['shop'];
		$sql = "SELECT * FROM tbl_usersettings where store_name = '$shop' LIMIT 1";

		$result = mysqli_query($conn,$sql);
			$res=mysqli_num_rows($result);
		if ($res > 0) {
			$sql1 = "UPDATE `tbl_usersettings` SET `access_token` = '$token' WHERE `store_name` = '$shop'"; 
		}
		else
		{
		echo $sql1 = "INSERT INTO tbl_usersettings 
		     SET access_token = '$token',
		     store_name = '$shop'";
		}
		if (mysqli_query($conn,$sql1) === TRUE) {
		$_SESSION['message'] = "New record created successfully";
		
		} else {
		$_SESSION['message'] = "Error: " . $sql . "<br>" . mysqli_error($conn);
		}
		
		header("Location:https://shopify.gluu.org/chargify.php");
		exit;
	}
	else
	{
		
		$_SESSION['shop'] = $_GET['shop'];
		header("Location:https://shopify.gluu.org/index.php");
		exit;
		
    }
   
	
	} 
	else if (isset($_POST['shop']) || isset($_GET['shop'])) 
	{
	 $deletetable=mysqli_query($conn,"DROP TABLE tmp_store") ;
	 $shop=$_GET['shop'];
	// Step 1: get the shopname from the user and redirect the user to the
	// shopify authorization page where they can choose to authorize this app
	$shop = isset($_POST['shop']) ? $_POST['shop'] : $_GET['shop'];
	$shopifyClient = new ShopifyClient($shop, "", SHOPIFY_API_KEY, SHOPIFY_SECRET);

	// get the URL to the current page
	$pageURL = 'http';
	if ($_SERVER["HTTPS"] == "on") { $pageURL .= "s"; }
	$pageURL .= "://";
	if ($_SERVER["SERVER_PORT"] != "80") {
		$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
	} else {
		$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
	}
	
	// redirect to authorize url
	header("Location: " . $shopifyClient->getAuthorizeUrl(SHOPIFY_SCOPE, $pageURL));
	exit;
	}

	// Show the form to ask the user for their shop name
	include('templates/header.php');
	include('templates/authorize.php');

	?>
