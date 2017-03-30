<?php
include_once('lib/config.lib.php');
include_once('lib/session.lib.php');
include_once('lib/shopify.php');
$domain = $_GET['shop'];
$sql="DELETE FROM  `tbl_usersettings` WHERE  `store_name` ='$domain'";
$result = mysqli_query($conn,$sql);
?>
