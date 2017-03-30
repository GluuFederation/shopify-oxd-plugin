<html>
<head>
<script src="https://cdn.shopify.com/s/assets/external/app.js"></script>
  <script type="text/javascript">
    ShopifyApp.init({
      apiKey: "<?php echo SHOPIFY_API_KEY;?>",
      shopOrigin: "https://<?php echo $_SESSION['shop']; ?>",
      debug: true,
      forceRedirect: false
    });
  </script>
   <script type="text/javascript">
  ShopifyApp.ready(function(){
  ShopifyApp.Bar.initialize({      
      title: 'Settings',               
          callback: function(){ 
            ShopifyApp.Bar.loadingOff();
            
          },
          buttons: {
                  primary: { label: "Save", message: 'tab_form_submit' },
           
          },
       
      
    });
  });
  </script>
  </head>
<body class="adminz">
	
<div class="container">
	<div class="row">
		
