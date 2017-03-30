$(document).ready(function(){
	var url = window.location.href;
	
	var shopname=$(location).attr('host');
	$( "#gluulogin" ).click(function() {
		
		var url="https://ce-prev-version.gluu.org";
		var width = 700;
		var height = 400;
		var left = parseInt((screen.availWidth/2) - (width/2));
		var top = parseInt((screen.availHeight/2) - (height/2));
		var windowFeatures = "width=" + width + ",height=" + height +   
        ",status,resizable,left=" + left + ",top=" + top + 
        "screenX=" + left + ",screenY=" + top + ",scrollbars=no";
			
    window.open(url, "subWind", windowFeatures, "POS");
		var dataString = 'url='+ url+ '&shopname='+ shopname;
		$.ajax({
			type: "POST",
			url: "https://shopify.gluu.org/oxd-glu/gluu.php",
			data: dataString,
			cache: false,
			success: function(result){
				console.log(result);
			window.open(result, "subWind", windowFeatures, "POS");
			
			}
		});
	});
	$.ajax({
			type: "POST",
			url: "https://shopify.gluu.org/info.php",
			data: 1,
			cache: false,
			success: function(data){
				console.log(data);
				
			  var data = {
				'customer[email]': data,
				'customer[password]': 'newpass',
				form_type: 'customer_login',
				utf8: '✓'
			  };
			var promise = $.ajax({
				url:url,
				method: 'post',
				data: data,
				dataType: 'html',
				async: true
			  });
				console.log(promise);
			}
		});
});
