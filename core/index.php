<?php
include_once('lib/config.lib.php');
include_once('lib/shopify.php');
//----------------------uintsall==========================/
    $api_key = 'f2bee041667a2edd64f21054c4190987';
    //$SECRET = 'b68d1c85fbf7db96f234fa357808d5a7';
    $STORE_URL = $_SESSION['shop'];
    $sql="SELECT * FROM `tbl_usersettings` WHERE `store_name`='$STORE_URL'";
    $result=mysqli_query($conn,$sql);
    $row = mysqli_fetch_assoc($result);
    //$row = $result->fetch_assoc();
    $TOKEN=$row['access_token'];
    $flag=$row['flag'];
    //$TOKEN = 'f226d3fa48e6f65ed73c173c0a278171';
    $url = 'https://'.$STORE_URL.'/admin/webhooks.json';
    $params = '{"webhook": {
        "topic": "app/uninstalled",
        "address": "https://shopify.gluu.org/uninstall.php?shop='.$STORE_URL.'",
        "format": "json"
        
    }}';
   
    $session = curl_init();
    curl_setopt($session, CURLOPT_URL, $url);
    curl_setopt($session, CURLOPT_POST, 1);
// Tell curl that this is the body of the POST
    curl_setopt($session, CURLOPT_POSTFIELDS, $params);
    curl_setopt($session, CURLOPT_HEADER, false);
    curl_setopt($session, CURLOPT_HTTPHEADER, array('Accept: application/json', 'Content-Type: application/json', 'X-Shopify-Access-Token: '.$TOKEN));
    curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
    if(preg_match("/^(https)/",$url)) curl_setopt($session,CURLOPT_SSL_VERIFYPEER,false);
    $response = curl_exec($session);
	curl_close($session);
    $createscripttag = $shopifyClient->call('POST', '/admin/script_tags.json', array('script_tag' => array('event' => 'onload','src' => 'https://shopify.gluu.org/gluu.js','display_scope'=>'online_store')),true);
	$themesList = $shopifyClient->call('GET', '/admin/themes.json',array('role'=>'main'));
	$activeThemeId = $themesList[0]['id'];
	$themePost='admin/themes/'.$activeThemeId.'/assets.json';
	//$stringFile = "<input type='button' name='gluulogin' value='Login with gluu server' id='gluulogin'>";
	$stringFile=file_get_contents("https://shopify.gluu.org/gluu.php");
	//$stringFile=base64_encode($stringFile);
	$filesAttach2= array
                (

                "asset"=>array
                (

               "key"=>"snippets/gluu.liquid",
               "value"=>$stringFile
                  )

     );
     $createsnippet=$shopifyClient->call('PUT',$themePost,$filesAttach2);
	 $url = 'https://' . $api_key . ':' . $TOKEN . '@' . $STORE_URL .'/admin/themes/'.$activeThemeId.'/assets.json?asset[key]=templates/customers/login.liquid&theme_id='.$activeThemeId;
     $ch = curl_init();
     curl_setopt($ch,CURLOPT_URL,$url);
	 curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
	 $output=curl_exec($ch);
	 $decodedoutput=json_decode($output,true);
	 $updatedvalue=$decodedoutput['asset']['value'];
     if($flag==0)
     {
		
     $themeupdated = str_replace("{% form 'customer_login' %} {% include 'gluu' %}", "{% form 'customer_login' %} {% include 'gluu' %}", $updatedvalue);
	 $update = $shopifyClient->call('PUT', '/admin/themes/'.$activeThemeId.'/assets.json',array('asset' => array('key' => 'templates/customers/login.liquid','value'=>$themeupdated)));
	 $sql="update tbl_usersettings set `flag`='1' where store_name = '$STORE_URL'";
	 $result=mysqli_query($conn,$sql);
	}
	
?>




    
