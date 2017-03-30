	<?php
	ob_start();
	//error_reporting(0);
	include_once('lib/session.lib.php');
	include_once('lib/shopify.php');
	include_once('lib/config.lib.php');
	$shop= $_SESSION['shop'];
	$sql = "SELECT * FROM tbl_usersettings where store_name = '$shop' LIMIT 1";
	$result = mysqli_query($conn,$sql) or die(mysqli_error($conn)."update failed");
	$row=mysqli_fetch_row($result);
	$token=$row[1];
	$api_key='f2bee041667a2edd64f21054c4190987';
	$secret='84cf04bde78151a0f40aea74e75f8afb';
	$sc = new ShopifyClient($shop, $token, $api_key, $secret);
	if (isset($_GET['charge_id']))
	{
		$charge_id = $_GET['charge_id'];
		$response = $sc->call('GET', '/admin/recurring_application_charges/' . $charge_id . '.json');
		if($response['status'] == 'accepted') {
		$activated = $sc->call('POST', '/admin/recurring_application_charges/' . $charge_id . '/activate.json');
	
		$sta= $activated['status'];
		$activated=$activated['trial_ends_on'];
	
		if(!empty($sta))
		{
			$sql="update tbl_usersettings set planstatus='$sta',trial_ends_on='$activated' where store_name = '$shop'";
			$result=mysqli_query($conn,$sql);
			$sql="select * FROM tbl_usersettings where store_name = '$shop'";
			$status=mysqli_query($conn,$sql);
			$acceptedstatus=mysqli_fetch_assoc($status);
			$finalstatus=$acceptedstatus['planstatus'];
			if($finalstatus=='active')
			{
				//header("Location:https://".$shop."/admin/apps/875c47856ca20da6efdeb7ed3d17d588/app/index.php");
				$url="https://".$shop."/admin/apps/f2bee041667a2edd64f21054c4190987/index.php";
				//$url="https://importifys-kindlebit.c9users.io/go.php";
				if (!headers_sent())
                {    
					header('Location: '.$url);
					exit;
                }
			   else
				{  
					echo '<script type="text/javascript">';
					echo 'window.location.href="'.$url.'";';
					echo '</script>';
					echo '<noscript>';
					echo '<meta http-equiv="refresh" content="0;url='.$url.'" />';
					echo '</noscript>'; exit;
			    } 
			}
		}
   }
   else
   {
	   $charge = array
        (
            "recurring_application_charge"=>array
            (
                "price"=>4.95,
                "name"=>"gluu",
                "return_url"=>"https://shopify.gluu.org/chargify.php",
                "trial_days"=>10,
                 "test"=>'true'
            )
        );
		try
		{
			   $recurring_application_charge = $sc->call('POST', '/admin/recurring_application_charges.json', $charge);
				$chargeid=$recurring_application_charge['id'];
			   $name=$recurring_application_charge['name'];
			   $price=$recurring_application_charge['price'];
			   $trial_days=$recurring_application_charge['trial_days'];
			   $confirmation_url=$recurring_application_charge['confirmation_url'];
			   $status=$recurring_application_charge['status'];
				$createdat=$recurring_application_charge['created_at'];
				$sql="update tbl_usersettings set `charge_id`='$chargeid',`planname`='$name',`planprice`='$price',`trial`='$trial_days',`planstatus`='$status' where store_name = '$shop'";
				$result=mysqli_query($conn,$sql);
				 //header('Location: '.$confirmation_url);
				if (!headers_sent())
               {    
				header('Location: '.$confirmation_url);
				exit;
              }
			else
				{  
				echo '<script type="text/javascript">';
				echo 'window.location.href="'.$confirmation_url.'";';
				echo '</script>';
				echo '<noscript>';
				echo '<meta http-equiv="refresh" content="0;url='.$confirmation_url.'" />';
				echo '</noscript>'; exit;
			} 
		}
		 catch (ShopifyApiException $e)
				{
					// If you're here, either HTTP status code was >= 400 or response contained the key 'errors'
				}
		//charge id
   }
}
	
else{

		$charge = array
				(
					"recurring_application_charge"=>array
					(
						"price"=>4.95,
						"name"=>"gluu",
						"return_url"=>"https://shopify.gluu.org/chargify.php",
						"trial_days"=>10,
						"test"=>'true'
					)
				);
		try
		{
			   $recurring_application_charge = $sc->call('POST', '/admin/recurring_application_charges.json', $charge);
			   $chargeid=$recurring_application_charge['id'];
			   $name=$recurring_application_charge['name'];
			   $price=$recurring_application_charge['price'];
			   $trial_days=$recurring_application_charge['trial_days'];
			   $confirmation_url=$recurring_application_charge['confirmation_url'];
			   $status=$recurring_application_charge['status'];
			   $createdat=$recurring_application_charge['created_at'];
				$sql="update tbl_usersettings set `charge_id`='$chargeid',`planname`='$name',`planprice`='$price',`trial`='$trial_days',`planstatus`='$status' where store_name = '$shop'";
				$result=mysqli_query($conn,$sql);
				 if (!headers_sent())
               {    
				header('Location: '.$confirmation_url);
				exit;
              }
			else
				{  
				echo '<script type="text/javascript">';
				echo 'window.location.href="'.$confirmation_url.'";';
				echo '</script>';
				echo '<noscript>';
				echo '<meta http-equiv="refresh" content="0;url='.$confirmation_url.'" />';
				echo '</noscript>'; exit;
			} 
				
		}
		 catch (ShopifyApiException $e)
				{
					// If you're here, either HTTP status code was >= 400 or response contained the key 'errors'
				}
		//charge id
}
       
?>
