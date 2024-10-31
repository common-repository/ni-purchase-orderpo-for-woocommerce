// JavaScript Document
//alert(niwoopo_ajax_object.niwoopo_ajaxurl);
var obj;	
var purchase_row_number = 0; 
var po_product = [];
var _purchase_quantity 	= 0;
var _purchase_price 	= 0;
var _product_total 		= 0;
var _product_post_id 	= 0;
var _product_note 		= '';
var _is_validate = false;
var form_processing  =false;
var _message 				 	 = "";	
var _product_message  			 = "";
var row_count  = 1;
var po_header_id	= 0;
var _po_detail_id = 0;
var global_decimals		 = 2;

jQuery(document).ready(function($) {
	
	po_header_id = getUrlParam("po_id",0);
	
	
	if (po_header_id>0){
		get_po_order();
	}else{
		//add_row();
	}
	
	jQuery("._niwooims_datepicker").datepicker({
		changeMonth: true,
		changeYear: true,
		dateFormat: 'yy-mm-dd',
		maxDate: 0 
	});
	
	/*Add product row*/
	$("#btn_add_product_row").click(function(event){
		event.preventDefault();
		add_row();
	});
	jQuery(document).on("click","._remove_product", function(){
		jQuery(this).parent().parent().remove();
		calculate_product_total('calculat');
	});
	
	jQuery(document).on("input","._purchase_quantity, ._purchase_price", function(){		
   		calculate_product_total('calculat');
	});
	
	/*Numeric validation*/
	jQuery(document).on("keypress","._allownumericwithdecimal",function (event) {
		//this.value = this.value.replace(/[^0-9\.]/g,'');
		
		console.log(event.which);
		
		jQuery(this).val($(this).val().replace(/[^0-9\.]/g,''));
		if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
			event.preventDefault();
		}
	});

	jQuery(document).on("keypress","._allownumericwithoutdecimal",function (event) {  
	
		
	  
		jQuery(this).val($(this).val().replace(/[^\d].+/, ""));
		if ((event.which < 48 || event.which > 57)) {
			event.preventDefault();
		}
	});
	/*End Numeric validation*/
	
	/*Purchase order auto complete*/
	var cache = {};
	var acs_url = ajaxurl+'?action=niwoopo_ajax&call=autocomplete_product&page=niwoopo-manage-po&niwoopo_action=autocomplete_product';
	jQuery(document).on("focus.autocomplete", ".autocomplet._product_name, .autocomplet._product_sku", function () {
		
		var serach_type = jQuery(this).attr('data-serach');
		acs_url = acs_url+'&serach_type='+serach_type;
		
		jQuery(this).autocomplete({
			 source: function(request, response){
			   var term = request.term;
				if ( term in cache ) {
					response(cache[term ]);
					return;
				}

				jQuery.getJSON(acs_url, request, function( data, status, xhr ) {
					cache[ term ] = data;
					response( data );
				});
			},
			select: function(event, ui) {
				  console.log(ui.item);
				  //jQuery(this).parent().parent().find('._product_post_id').val(ui.item.product_post_id);
				  jQuery(this).parent().parent().find('._product_id').val(ui.item.product_post_id);
				  //jQuery(this).parent().parent().find('._variation_id').val(ui.item.variation_id);
				  jQuery(this).parent().parent().find('._product_name').val(ui.item.product_name);
				  jQuery(this).parent().parent().find('._product_sku').val(ui.item.product_sku);				  
				  jQuery(this).parent().parent().find('._purchase_quantity').val(ui.item.purchase_quantity);
				  //jQuery(this).parent().parent().find('._purchase_price').val(ui.item.purchase_price);				  
				  //jQuery(this).parent().parent().find('._product_type').val(ui.item.product_type);
				  //jQuery(this).parent().parent().find('._vendor_id').val(ui.item.vendor_id);
				  
				  //jQuery(this).parent().parent().find('._transaction_type').val(ui.item.transaction_type);
				  //jQuery(this).parent().parent().find('._transaction_status').val(ui.item.transaction_status);				  
				 
				 
				  //jQuery(this).parent().parent().find('._product_image_url').attr(ui.item.product_image_url);
				  
				  if ( ui.item.product_image_url != null){
				  	jQuery(this).parent().parent().find('._product_image_url').attr("src", ui.item.product_image_url);
				  }
					
				  
				   console.log(ui.item.product_image_url);
				 
				  calculate_product_total('calculate');
			},
			minLength: 1,
		});

		jQuery(this).autocomplete("search");
	});
	/*End autocomplete*/
	
	jQuery("#btn_create_purchase_order").click(function(event){		
			event.preventDefault();
		if(form_processing) return false;
		form_processing	  = true;
		jQuery("._validation").addClass("alert-info");
		jQuery("._validation").removeClass("alert-success");
		jQuery("._validation").removeClass("alert-danger");
		jQuery("._validation").show("fast").html(niwoopo_ajax_object.please_wait);
		
		calculate_product_total('validate');
		var interval_create_purchase_order =  setInterval(function(){
			if (_calculate_product_total){
				clearInterval(interval_create_purchase_order);
				if (_is_validate){
					if (po_header_id>0){
						update_po_order();
					}else{
						create_po_order();		
					}	
					
				}else{
					form_processing	  = false;
				}
			}
			 
		 }, 1000);
			
	});
	
	//btn_delete_purchase_order
	jQuery("#btn_delete_purchase_order").click(function(event){	
		event.preventDefault();
		try{
			
			$("#btn_create_purchase_order").hide();

			var data = {
				'action': 'niwoopo_ajax',
				'niwoopo_action': 'mange_po',
				'call': 'delete_po',
				'po_id': po_header_id
			};
			
			jQuery.ajax({
				url:niwoopo_ajax_object.niwoopo_ajaxurl, 
				data:data,
				type: "POST",
				success:function(response) {
					var data = JSON.parse(response);
					//alert(JSON.stringify(data));
					//onsole.log(data);
					
					if(data.status == 1 ){
					
					jQuery("._validation").removeClass("alert-info");
					jQuery("._validation").addClass("alert-success");
					jQuery("._validation").show().html( "<ul> <li>"+ data.message +"</li> </ul>" );
					window.setTimeout(function() {
						var admin_url =  niwoopo_ajax_object.niwoopo_admin_url						
						window.location.href = admin_url+ "?page=niwoopo-manage-po";
					}, 2000);
				}else{
					jQuery("._validation").addClass("alert-danger");
					jQuery("._validation").show().html( "<ul> <li>"+ data.message +"</li> </ul>" );
					window.setTimeout(function() {
						jQuery("._validation").hide();
					}, 2000);
					
				}
					
					
					
				
			},
			error: function(response){
				form_processing	  = false;
				console.log(response);
			}
		 });  
			
		
		}catch(e){
			alert(e.message);
		}	
	
	});
	
});

function calculate_product_total(action_type){
	_calculate_product_total	 = false;
	_message 				 	 = "";	
	_product_message  			 = "";
	row_count = 1;
	po_product = [];
	
		jQuery("#_table_product > tbody >tr").each(function() {
			_product_name 		= jQuery(this).find("._product_name").val();
			_product_post_id 	= jQuery(this).find("._product_id").val();
			
			_po_detail_id 	= jQuery(this).find("._po_detail_id").val();
			
			_purchase_quantity 	= jQuery(this).find("._purchase_quantity").val();
			_purchase_price 	= jQuery(this).find("._purchase_price").val();
			_product_note 	= 	  jQuery(this).find("._product_note").val();
			
			if (!jQuery.isNumeric(_product_post_id)){
				_product_post_id = 0;
			}
			if (!jQuery.isNumeric(_purchase_quantity)){
				_purchase_quantity = 0;
			}
			if (!jQuery.isNumeric(_purchase_price)){
				_purchase_price = 0;
			}
			
			_product_total =  (_purchase_quantity * _purchase_price).toFixed(2);
		
			
			
			obj =  new Object();
			obj.product_id 				= _product_post_id;
			obj.purchase_quantity  		= _purchase_quantity;
			obj.purchase_price 			= _purchase_price;
			obj.product_total 			= _product_total;
			obj.product_note 			= _product_note;
			obj.po_detail_id			= _po_detail_id;
			po_product.push(obj);
			
			jQuery(this).find("._product_total").val(_product_total);
			
			console.log(po_product);
			
			
			if (_product_name ==""){
					_product_message +="  <strong> "+niwoopo_ajax_object.product_name+"  </strong>";	
			}else{
				if (_product_post_id ==0 || _product_post_id =="" || _product_post_id.length==0 ){
					_product_message +="  <strong> "+niwoopo_ajax_object.product_name+"  </strong>";
				}
			}
			
			
			
			if (_purchase_quantity ==0 || _purchase_quantity ==""  ){
				_product_message +="  "+niwoopo_ajax_object.purchase+" Purchase <strong> "+niwoopo_ajax_object.quantity+" </strong>";
			}
			if (_purchase_price ==0 || _purchase_price ==""  ){
				
				_product_message +=" "+niwoopo_ajax_object.purchase+" <strong>  "+niwoopo_ajax_object.price+" </strong>";
			}
			
			if (_product_message!="" || _product_message.length != 0 ){
				
				_message += "<li> <strong>"+ row_count + "</strong> "+niwoopo_ajax_object.enter+" "  + _product_message +  "</li> " ;
			}
			
			row_count = row_count +1;
			
			
		});
	
		_calculate_product_total	 = true;
		if (_message.length>0 || _message !=""){
			_is_validate = false;
			if(action_type == 'validate'){
				jQuery("._validation").removeClass("alert-info");
				jQuery("._validation").removeClass("alert-success");
				jQuery("._validation").addClass("alert-danger");
				jQuery("._validation").show().html( "<ul>"+ _message +"</ul>" );				
			}
		}else{
			_is_validate  = true;
			if(action_type != 'validate'){
				jQuery("._validation").html('').hide();
			}
		}		
		return false;
		//alert(JSON.stringify(po_product));
	
}
function update_po_order(){
	try{
		var data				 = {};
		data["action"] 			 = "niwoopo_ajax";
		data["niwoopo_action"] 	 = "mange_po";
		data["call"] 			 = "update_po";
		data["po_product"] 		 = po_product;
		//data["po_header_id"] 	 = po_header_id;
		data["po_id"] 			 = po_header_id;
		
		
		data["po_date"] 			= jQuery("#po_date").val();
		data["po_no"] 				= jQuery("#po_no").val();
		data["vendor_id"]			= jQuery("#vendor_id").val();
		data["status_id"]			= jQuery("#status_id").val();
		data["supplier_id"]			= jQuery("#supplier_id").val();
		data["po_notes"]			= jQuery("#po_notes").val();
		
		
		
		
		
		
		form_processing	  = true;
		
		jQuery("._validation").addClass("alert-info");
		jQuery("._validation").removeClass("alert-success");
		jQuery("._validation").removeClass("alert-danger");
		jQuery("._validation").show().html( niwoopo_ajax_object.please_wait );
		
		
		
		// return false;
		jQuery.ajax({
			url:niwoopo_ajax_object.niwoopo_ajaxurl, 
			data:data,
			type: "POST",
			success:function(response) {
				var data = JSON.parse(response);
					//alert(JSON.stringify(data));
					console.log(data);
				
				if(data.status == 1 ){
					
					jQuery("._validation").removeClass("alert-info");
					jQuery("._validation").addClass("alert-success");
					jQuery("._validation").show().html( "<ul> <li>"+ data.message +"</li> </ul>" );
					window.setTimeout(function() {
						var admin_url =  niwoopo_ajax_object.niwoopo_admin_url						
						window.location.href = admin_url+ "?page=niwoopo-manage-po&niwoopo_action=edit&po_id=" +data.po_id ;
					}, 2000);
				}else{
					jQuery("._validation").addClass("alert-danger");
					jQuery("._validation").show().html( "<ul> <li>"+ data.message +"</li> </ul>" );
					window.setTimeout(function() {
						jQuery("._validation").hide();
					}, 2000);
					form_processing	  = false;
				}
				
			},
			error: function(response){
				form_processing	  = false;
				console.log(response);
			}
		 });  
		
	
	}catch(e){
		alert(e.message);	
	}
}
function create_po_order(){
	try{
		var data				 = {};
		data["action"] 			 = "niwoopo_ajax";
		data["niwoopo_action"] 	 = "mange_po";
		data["call"] 			 = "create_po";
		data["po_product"] 			= po_product;
		data["po_header_id"] 	 = po_header_id;
		
		
		data["po_date"] 			= jQuery("#po_date").val();
		data["po_no"] 				= jQuery("#po_no").val();
		data["vendor_id"]			= jQuery("#vendor_id").val();
		data["status_id"]			= jQuery("#status_id").val();
		data["supplier_id"]			= jQuery("#supplier_id").val();
		data["po_notes"]			= jQuery("#po_notes").val();
		
		
		
		
		form_processing	  = true;
		
		jQuery("._validation").addClass("alert-info");
		jQuery("._validation").removeClass("alert-success");
		jQuery("._validation").removeClass("alert-danger");
		jQuery("._validation").show().html( niwoopo_ajax_object.please_wait );
		
		
		
		// return false;
		jQuery.ajax({
			url:niwoopo_ajax_object.niwoopo_ajaxurl, 
			data:data,
			type: "POST",
			success:function(response) {
				var data = JSON.parse(response);
					//alert(JSON.stringify(data));
				
				if(data.status == 1 ){
					
					jQuery("._validation").removeClass("alert-info");
					jQuery("._validation").addClass("alert-success");
					jQuery("._validation").show().html( "<ul> <li>"+ data.message +"</li> </ul>" );
					window.setTimeout(function() {
						var admin_url =  niwoopo_ajax_object.niwoopo_admin_url						
						window.location.href = admin_url+ "?page=niwoopo-manage-po&niwoopo_action=edit&po_id=" +data.po_id ;
					}, 2000);
				}else{
					jQuery("._validation").addClass("alert-danger");
					jQuery("._validation").show().html( "<ul> <li>"+ data.message +"</li> </ul>" );
					window.setTimeout(function() {
						jQuery("._validation").hide();
					}, 2000);
					form_processing	  = false;
				}
				
			},
			error: function(response){
				form_processing	  = false;
				console.log(response);
			}
		 });  
		
	
	}catch(e){
		alert(e.message);	
	}
}
function add_row(){
	purchase_row_number = purchase_row_number +1;
	var row_id = 'purchase_row_'+purchase_row_number;
	location_id = jQuery("#location_id").val();
	try{
		strHtml  = '';
		strHtml  += '<tr id="'+row_id+'">';
			
			if (niwoopo_ajax_object.po_show_product_image_column ==='yes'){
				strHtml  += '<td><img src="" class="_product_image_url" width="50px" /></td>';
			}
			
		
			strHtml  += '<td scope="row">';
			strHtml  += '<input type="text" class="form-control autocomplet _product_sku" data-serach="product_sku" placeholder="'+niwoopo_ajax_object.placeholder_autocomplete_sku+'"   /> ';
			strHtml  += '</td>';
			strHtml  += '<td>';
				strHtml  += '<input type="text" class="form-control autocomplet _product_name" data-serach="product_name" placeholder="'+niwoopo_ajax_object.placeholder_autocomplete_product+'"   /> ';
				strHtml  += '<input type="hidden" class="_product_id" value="0"    />';
				strHtml  += '<input type="hidden" class="_po_detail_id" value="0"   />';
				
				
				
			strHtml  += '</td>';
			
			strHtml  += '<td><input type="text" class="form-control text-right _purchase_quantity _allownumericwithoutdecimal small-textbox"  maxlength="6" size="6"  /></td>';
			strHtml  += '<td><input type="text" class="form-control text-right _purchase_price _allownumericwithdecimal small-textbox"  maxlength="11" size="6" /></td>';

			strHtml  += '<td></td>';	
			strHtml  += '<td><input type="text" class="form-control text-right _product_total small-textbox" readonly="readonly" size="10"  /></td>';	
			
			strHtml  += '<td class="product_note"><input type="text" class="form-control _product_note" size="6" maxlength="250" placeholder="'+niwoopo_ajax_object.placeholder_product_notes+'" /></td>';
			
			strHtml  += '<td><i class="fa fa-trash fa-2x _remove_product"  aria-hidden="true" title="'+niwoopo_ajax_object.remove+'"></i></td>';		
		strHtml  += '</tr>';
		
		jQuery('#_table_product tbody').append(strHtml);
	}catch(e){
		alert("try catch error in function add_row: " + e.message);
	}
}
function get_po_order(){
	try{
		var data				 = {};
		data["action"] 			 = "niwoopo_ajax";
		data["niwoopo_action"] 	 = "mange_po";
		data["call"] 			 = "get_po_order";
		data["po_id"] 			 = po_header_id;
		
		
		form_processing	  = true;
		
		jQuery.ajax({
			url:niwoopo_ajax_object.niwoopo_ajaxurl, 
			data:data,
			type: "POST",
			success:function(response) {
				var jdata = JSON.parse(response);
				//alert(JSON.stringify(jdata));
				
				jQuery("._validation").removeClass("alert-info");
				jQuery("._validation").hide();
				
				update_po_form(jdata);
				form_processing	  = false;
				
				
			},
			error: function(response){
				jQuery("._validation").removeClass("alert-info");
				form_processing	  = false;
				console.log(response);
			}
		 });  
		
		
		
	}catch(e){
	alert(e.message);
	}
}
function update_po_form(jdata){
	var po_header = [];
	
	if(jdata["po_header"][0] != 'undefined'){
		po_header = jdata["po_header"][0];
		jQuery("#po_no").val(po_header["po_no"]);
		jQuery("#po_date").val(po_header["po_date"]);
		jQuery("#status_id").val(po_header["status_id"]);
		jQuery("#supplier_id").val(po_header["supplier_id"]);
		jQuery("#po_notes").val(po_header["notes"]);
		
		
	}else{
		jQuery("#po_no").val('');
		
		jQuery("#status_id").val(0);
		jQuery("#supplier_id").val(0);
		jQuery("#po_notes").val('');
	}
	
	purchase_row_number = 0;
	var row_id = 'purchase_row_'+purchase_row_number;
	strHtml = "";
	//purchase_order_no
	var jpurchase_detail = jdata["po_detail"];
	jQuery("#_table_product > tbody > tr").remove();
	jQuery.each(jpurchase_detail,function(key,value){
		
			
			purchase_row_number = purchase_row_number +1;
			var row_id = 'purchase_row_'+purchase_row_number;
			
			strHtml  += '<tr id="'+row_id+'">';
				if (niwoopo_ajax_object.po_show_product_image_column ==='yes'){
					strHtml  += '<td><img src="'+ value["product_image_url"]+'" class="_product_image_url" width="50px" /></td>';
				}
			
				strHtml  += '<td scope="row">';				
					strHtml  += '<input type="text" class="form-control _product_sku" data-serach="product_sku" value="'+value["product_sku"]+'" placeholder="'+niwoopo_ajax_object.placeholder_autocomplete_sku+'"     /> ';
				strHtml  += '</td>';
				strHtml  += '<td>';
					strHtml  += '<input type="text" class="form-control  _product_name" data-serach="product_name" value="'+ value["product_name"]+'"  placeholder="'+niwoopo_ajax_object.placeholder_autocomplete_product+'"     /> ';
				
					strHtml  += '<input type="hidden" class="_product_id" value="'+ value["product_id"]+'"  />';
					strHtml  += '<input type="hidden" class="_po_detail_id" value="'+ value["po_detail_id"]+'"    />';
					
					
				strHtml  += '</td>';
				
				
				
				strHtml  += '<td><input type="text" class="form-control text-right _purchase_quantity _allownumericwithoutdecimal small-textbox"  value="'+ value["po_quantity"]+'"  maxlength="6" size="6" /></td>';
				strHtml  += '<td><input type="text" class="form-control text-right _purchase_price _allownumericwithdecimal small-textbox"   value="'+ value["purchase_price"]+'" maxlength="11" size="6" /></td>';
					strHtml  += '<td></td>';		
			
				strHtml  += '<td><input type="text" class="form-control text-right _product_total small-textbox" readonly="readonly" value="'+ get_decimal_value(value["po_product_total"],global_decimals,'no')+'" size="10"  /></td>';	
				strHtml  += '<td class="product_note"><input type="text" class="form-control _product_note" value="'+ value["product_note"]+'" size="6" maxlength="250" placeholder="'+niwoopo_ajax_object.placeholder_product_notes+'" /></td>';
				strHtml  += '<td>';
				if(value["sold_quantity"] > 0){
					valid_for_delete = false;
				}else{
					strHtml  += '<i class="fa fa-trash fa-2x _remove_product"  aria-hidden="true" title="'+niwoopo_ajax_object.remove+'"></i>';	
				}	
				strHtml  += '</td>';		
			strHtml  += '</tr>';
	});
	
	jQuery('#_table_product tbody').append(strHtml);
	jQuery("#btn_delete_purchase_order").show();

}
function getUrlParam(parameter, defaultvalue){
    var urlparameter = defaultvalue;
    if(window.location.href.indexOf(parameter) > -1){
        urlparameter = getUrlVars()[parameter];
        }
    return urlparameter;
}
function getUrlVars() {
    var vars = {};
    var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {
        vars[key] = value;
    });
    return vars;
}
function get_decimal_value(number, decimals, parsed){
	number = number == "" ? 0 : number;
	if(parsed == 'no'){
		number = parseFloat(number);
	}	
	number = number.toFixed(decimals);
	
	return number;
}