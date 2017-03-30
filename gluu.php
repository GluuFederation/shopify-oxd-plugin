
<script>
	$(document).ready(function(){
		$.ajax({
			type: "POST",
			url: "https://shopify.gluu.org/checkshop.php",
			data:'url={{ shop.permanent_domain }}',
			cache: false,
			success: function(data){
				console.log(data);
				if(data=='noappexist')
				{
					$("#gluulogin").hide();
				}
			}
		});
			
	});	

</script>



<input type="button" name="gluulogin" value="Login with gluu server" id="gluulogin">

