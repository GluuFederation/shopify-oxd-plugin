<?php
include_once('./lib/config.lib.php');
include_once('./lib/session.lib.php');
include_once('./lib/shopify.php');
$sql="SELECT * FROM `tmp_store`";
$result = mysqli_query($conn,$sql);
$row = mysqli_fetch_assoc($result);
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
  <script>
  $(document).ready(function() {
  $('form').find('input[type="submit"]').trigger('click');	
});
</script>
<div style="display:none;">
<div class="col-sm-12">
 
<p style="padding-bottom: 1em;">
	<span class="hint">Don&rsquo;t have a shop to install your app in handy? <a href="https://app.shopify.com/services/partners/api_clients/test_shops">Create a test shop.</a></span>
</p> 
  
<form action="authorize.php" method="post" class="">
   <div class="form-group">
   <div class="col-xs-4 col-md-4 col-lg-4 input-resize">
    <label for='shop'><strong>The URL of the Shop</strong> 
    <span class="hint">(enter it exactly like this: myshop.myshopify.com)</span> 
  </label> 
  </div>
  <div class="col-xs-8 col-md-8 col-lg-8 input-resize">
    <input id="shop" name="shop" size="45" type="text" value="<?php echo $row['storename'];?>"  class="form-control" readonly /> 
  </div>
      </div>
     
    <input name="commit" type="submit" value="Install"  id="install"/> 
	

</form>
</div>
</div>
