<?php
header('Access-Control-Allow-Origin: *');
include_once('lib/config.lib.php');
$selectoxd=mysqli_query($conn,"SELECT * FROM `glulogin`");
$oxdids=mysqli_fetch_assoc($selectoxd);

echo $email=$oxdids['email'];


 

?>
