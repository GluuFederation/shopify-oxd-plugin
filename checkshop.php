<?php
header('Access-Control-Allow-Origin: *');
include_once('lib/config.lib.php');
$shop=$_POST['url'];
$sql = "SELECT * FROM tbl_usersettings where store_name = '$shop' LIMIT 1";
$result = mysqli_query($conn,$sql);
$row1=mysqli_fetch_assoc($result);
if($row1=='')
{
	echo 'noappexist';
}
?>
