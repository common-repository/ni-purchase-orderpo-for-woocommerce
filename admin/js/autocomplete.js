var cache = {};
var cache2 = {};
function get_search_data(textbox,cache_term,term){
	var hidden_value_obj = textbox.parent().parent().find(".autocomplete_hidden");
	var hidden_value  = hidden_value_obj.val();
	var hidden_value = hidden_value.split(',');
	var element_id = hidden_value_obj.attr('id');
	var temp = [];

	jQuery.each(cache_term,function(index,item){
		var item_found = jQuery.inArray(item.id, hidden_value ) 
		if(item_found == "-1"){
			temp.push(item);
		}
	});

	cache[term] = temp;
	cache2[element_id] = cache[term];
	return temp;
}

jQuery(document).ready(function(e) {
	var acs_url = ajaxurl+'?action=niwooims_ajax&call=autocomplete_product&page=niwooims-purchase-orders&sub_action=niwooims_purchase_orders';
	jQuery(document).on("click", ".autocomplete_close", function () {

		var item_id = jQuery(this).attr("data-item_id");
		var hidden_value_obj = jQuery(this).parent().parent().parent().parent().find(".autocomplete_hidden");
		var hidden_value  = hidden_value_obj.val();
		var element_id = hidden_value_obj.attr('id');

		jQuery(this).parent().remove();
		var new_value = new Array();
		var hidden_value = hidden_value.split(',');

		var i = 0;
		jQuery.each(hidden_value, function(index, value) {									  
			if(item_id != value){
				new_value[i] = value;
				i++;
			}
		});

		var hidden_value = new_value.join(',');

		hidden_value_obj.val(hidden_value);
		cache2[element_id] = {};
	});

	jQuery(document).on("focus.autocomplete", ".autocomplete_text", function () {
			var textbox = jQuery(this);
			var serach_type = jQuery(this).attr('data-serach');
			acs_url = acs_url+'&serach_type='+serach_type;

			textbox.autocomplete({
				 source: function(request, response){

					 //console.log(cache2)

					var hidden_value_obj = textbox.parent().parent().find(".autocomplete_hidden");
					var element_id = hidden_value_obj.attr('id');
					var term = request.term;

					cache = {};
					if(element_id in cache2){
						cache = cache2[element_id];	
					}

					if(term in cache){
						var c = get_search_data(textbox,cache[term],term);
						response(c);
						return;
					}
					 
					//console.log(cache2);

					jQuery.getJSON(acs_url, request, function( data, status, xhr ) {
						cache[term] = data;
						cache2[element_id] = cache[term];

						var c = get_search_data(textbox,cache[term],term);
						response(c);													
						//response( jQuery.ui.autocomplete.filter(data, extractLast( request.term ) ) );													
					});	
				},
				focus: function() {
					return false;
				},
				select: function(event, ui) {

					var hidden_value_obj = textbox.parent().parent().find(".autocomplete_hidden");
					var hidden_value  = hidden_value_obj.val();

					if(hidden_value == ""){
						hidden_value = ui.item.id;
					}else{
						hidden_value = hidden_value +","+ui.item.id;
					}

					var output = "";												
					output += '<span class="autocomplete_item">';
						output += ui.item.value;
						output += '<span class="autocomplete_close" data-item_id="'+ui.item.id+'">x</span>';
					output += '</span>';

					textbox.val("");
					textbox.parent().find(".autocomplete_items").append(output);												
					textbox.focus();
					jQuery(".autocomplete_hidden").val(hidden_value);												
					ui.item.value = "";	
					return false;
				},

				minLength: 1,
			});

			jQuery(this).autocomplete("search");
		});
});