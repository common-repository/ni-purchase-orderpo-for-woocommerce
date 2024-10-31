var form_processing  = true;
jQuery(document).ready(function ($) {
	$("#niwoopo_setting").submit(function (event) {
		event.preventDefault();
		
		$.ajax({
			url:niwoopo_ajax_object.niwoopo_ajaxurl, 
			data: $(this).serialize(),
			success: function (response) {
				//alert(JSON.stringify(response));
				$('.alert').show();
				$('.please_wait').html(response);
				
				setTimeout(function() {
					$('.alert').hide();
				}, 3000);
				
			},
			error: function (response) {
				alert(JSON.stringify(response));
				
			}
		});
	});
	
	jQuery(".upload_image").change(function(){
		var field_name = jQuery(this).attr("data-field");
		var formData = new FormData();
		formData.append('upload_image', 		jQuery(this)[0].files[0]);
		formData.append('action',				jQuery("#action").val());
		formData.append('niwoopo_action', 		'niwoopo_setting');
		formData.append('niwoopo_sub_action', 	'niwoopo_setting_images');
		formData.append('field_name', 			field_name);
		
		form_processing = true;
		jQuery.ajax({
			   url	: niwoopo_ajax_object.niwoopo_ajaxurl,	
			   type : 'POST',
			   data : formData,
			   processData: false,  // tell jQuery not to process the data
			   contentType: false,  // tell jQuery not to set contentType
			   success : function(response) {				    
				    var obj = JSON.parse(response);				   
					form_processing = false;					
					console.log("Done");					
					if(obj.error_message == ""){
						jQuery("img."+field_name+"_image").attr('src',obj.upload_image_url);					
						jQuery("#"+field_name).val(obj.upload_image_name);
					}else{
						alert(obj.error_message);
					}
					
			   },
				error: function(errorThrown){
					console.log(errorThrown);
					form_processing = false;
				}
		});
	});
	
});