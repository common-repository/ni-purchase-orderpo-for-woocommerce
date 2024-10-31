// JavaScript Document
jQuery(document).ready(function($) {
	
	jQuery("._niwooims_datepicker").datepicker({
		changeMonth: true,
		changeYear: true,
		dateFormat: 'yy-mm-dd',
		maxDate: 0 
	});
	
	jQuery(document).on("submit","#po_list", function(event){
		event.preventDefault();
		jQuery.ajax({           
            url:niwoopo_ajax_object.niwoopo_ajaxurl, 
            type: "POST",
            data: jQuery("#po_list").serialize(),
            success : function( response ) {
				//alert("s");
               //alert(JSON.stringify(response)); 
			   
			   $('._po_table').html(response); 

            },
			error : function( response ) {
				//				alert("e");
            	alert(JSON.stringify(response));  

            }
        }); 
		
		
	});
	
	$('#po_list').trigger('submit');
	
	jQuery(document).on("click","._edit", function(event){
		event.preventDefault();
		var tmp_po_id = $(this).attr("data-po_id");
		var tmp_url  = niwoopo_ajax_object.niwoopo_admin_url + '?page=niwoopo-manage-po&niwoopo_action=edit&po_id=' + tmp_po_id;
		window.location = tmp_url;
		
	});
	jQuery(document).on("click","._download", function(event){
		//event.preventDefault();
		//var tmp_po_id = $(this).attr("data-po_id");
		//alert(tmp_po_id);
		
	});
	jQuery(document).on("click","._print", function(event){
		//event.preventDefault();
		//var tmp_po_id = $(this).attr("data-po_id");
		//alert(tmp_po_id);		
	});
	
	
	
});