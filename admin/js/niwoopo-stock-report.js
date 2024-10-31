// JavaScript Document
jQuery(document).ready(function($) {
	
	jQuery("._niwooims_datepicker").datepicker({
		changeMonth: true,
		changeYear: true,
		dateFormat: 'yy-mm-dd',
		maxDate: 0 
	});
	//please_wait
	jQuery(document).on("submit","#stock_report", function(event){
		event.preventDefault();
		jQuery.ajax({           
            url:niwoopo_ajax_object.niwoopo_ajaxurl, 
            type: "POST",
            data: jQuery("#stock_report").serialize(),
			beforeSend: function(msg){
				 
				 $('.please_wait').show().html('Please wait..'); 
		    },
            success : function( response ) {
				
			  $('.please_wait').hide(); 
			   $('._po_table').html(response); 

            },
			error : function( response ) {
				//				alert("e");
            	alert(JSON.stringify(response));  

            }
        }); 
		
		
	});
	
	$('#stock_report').trigger('submit');
	
});