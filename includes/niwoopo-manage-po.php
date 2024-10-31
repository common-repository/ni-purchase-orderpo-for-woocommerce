<?php 
if ( !class_exists( 'NiWooPO_Manage_PO' ) ) {
	include_once("niwoopo-function.php"); 	
	class NiWooPO_Manage_PO extends NiWooPO_Function{
		var $niwoopo_constant = array();  
		function __construct($niwoopo_constant = array()){
			
			$this->niwoopo_constant = $niwoopo_constant;
			
		
			
			
			
			
		}
		function page_init(){

			$page  			= $this->get_request("page");
			$po_date  		= $this->get_request("po_date",date_i18n("Y-m-d"));
			$po_no 			= $this->get_request('po_no');
			$po_order_status 	= $this->get_po_order_status();
			$user_list 		= $this->get_user_list();
			$mange_url 		= admin_url('admin.php?page=niwoopo-manage-po&niwoopo_action=add');
			$po_show_product_image_column = $this->get_setting('po_show_product_image_column','no');
			?>
			<div id="niwooims" class="ni-woopo-plug">
				
					<div class="ni-box ni-search-form">
						<div class="ni-box-header">
							<h4><?php _e("Purchase Order","niwoopo"); ?></h4>
						</div>
						
						<div class="ni-box-body">
							<form name="frmSearch" id="frmSearch">
								<div class="ni-form-group ni-row">
									<div class="ni-form-label">
										<label class="col-form-label" for="po_date"><?php _e("Purchase Date:","niwoopo"); ?></label>
									</div>
									<div class="ni-form-field">
										<input type="text" class="form-control _niwooims_datepicker" name="po_date" id="po_date" value="<?php echo $po_date; ?>" />
									</div>
									
									<div class="ni-form-label">
										<label class="col-form-label" for="po_no"><?php _e("Purchse No.:","niwoopo"); ?></label>
									</div>
									<div class="ni-form-field">
										<input type="text" class="form-control" name="po_no" id="po_no" value="<?php echo $po_no; ?>" />
									</div>
								</div>
								
								<div class="ni-form-group ni-row">
									<div class="ni-form-label">
										<label class="col-form-label" for="supplier_id"><?php _e("Supplier:","niwoopo"); ?></label>
									</div>
									<div class="ni-form-field">
										<select  class="form-control" name="supplier_id" id="supplier_id">
											<option value="0"><?php _e('Select Supplier','niwoopo');?></option>
											<?php foreach( $user_list as $key=>$value):  ?>
											  <option value="<?php  echo $value->user_id ; ?>"><?php echo  $value->last_name ." ". $value->first_name  ; ?></option>
											<?php endforeach; ?>
										</select>
									</div>
									
									<div class="ni-form-label">
										<label class="col-form-label" for="status_id"><?php _e("Status:","niwoopo"); ?></label>
									</div>
									<div class="ni-form-field">
										<select class="form-control" id="status_id" name="status_id">
											<?php foreach( $po_order_status as $key=>$value):  ?>
											  <option value="<?php  echo $key; ?>"><?php echo $value ;?></option>
											<?php endforeach; ?>
										</select>
									</div>
								</div>
								
								<div class="ni-form-group ni-row">
									<div class="ni-form-label">
										<label class="col-form-label" for="po_notes"><?php _e("PO Notes","niwoopo"); ?></label>
									</div>
									<div class="ni-form-field">
										<textarea id="po_notes" class="form-control"></textarea>
									</div>
								</div>
								
								<div class="row row-space-margin">
									<div class="col-sm-12" style="text-align:right">
										<input type="hidden" name="action" id="action" value="niwooims_ajax" />
										<input type="hidden" name="page" id="page" value="<?php echo $page; ?>" />
									</div>
								</div>
							</form>
							
							<!-- Alert Messages -->
							<div class="alert _validation" style="display:none;"></div>
							
						</div>
					</div>
				

				<div class="row" style="text-align:right;">
					<div class="col-lg-12">
						<!-- Button trigger modal -->
						<input type="button" value="<?php _e("Add New Product","niwoopo"); ?>" id="btn_add_product_row" class="ni_button" />
					</div>
				</div>
				
				
				<div class="ni-box ni-search-data">
					<div class="ni-box-body">
						<div class="table-responsive">
							<table class="ni-table ni-product-table" cellspacing="0" cellpadding="0" id="_table_product">
								<thead>
									
                                    <tr>
										<?php if ($po_show_product_image_column  =="yes"): ?>
                                         <th scope="col" class="product_sku"><?php _e("Image","niwoopo"); ?></th>
                                        <?php endif;?>
                                        
                                        <th scope="col" class="product_sku"><?php _e("Product SKU","niwoopo"); ?></th>
										<th scope="col" class="product_name"><?php _e("Product Name","niwoopo"); ?></th>
										
										<th scope="col" class="purchase_quantity"><?php _e("Purchase Quantity","niwoopo"); ?></th>
										<th scope="col" class="purchase_price"><?php _e("Purchase Price","niwoopo"); ?></th>							 
										<th scope="col" class="adjustement_column"></th>
										
										<th scope="col" class="product_total"><?php _e("Product Total","niwoopo"); ?></th>
										<th scope="col" class="product_note"><?php _e("Product Note","niwoopo"); ?></th>
										<th scope="col" class="remove_product"></th>
									</tr>
								</thead>
								<tbody>
									<tr id="purchase_row_1">
                                    	<?php if ($po_show_product_image_column  =="yes"): ?>
                                        <td>
											<img src="" class="_product_image_url" width="50px" />
										</td>
                                        <?php endif;?>
										<td scope="row" class="product_sku">
											<input type="text" class="form-control autocomplet _product_sku" data-serach="product_sku" placeholder="<?php _e("Enter Product SKU","niwoopo"); ?>"   />
										</td>
										<td class="product_name">
											<input type="text" class="form-control autocomplet _product_name" data-serach="product_name" placeholder="<?php _e("Enter Product Name","niwoopo"); ?>"   />
											<input type="hidden" class="_product_post_id" value="0"   />
											<input type="hidden" class="_product_id" value="0"   />
											<input type="hidden" class="_variation_id" value="0"   />
											<input type="hidden" class="_product_location_id" value="0"   />
											<input type="hidden" class="_purchase_order_detail_id" value="0"   />
											<input type="hidden" class="_product_type" value="simple"   />
											<input type="hidden" class="_vendor_id" value="0"   />
											<input type="hidden" class="_transaction_type" value="active"   />
											<input type="hidden" class="_transaction_status" value="purchase"   />
										</td>
										
										<td class="purchase_quantity"><input type="text" class="form-control text-right _purchase_quantity _allownumericwithoutdecimal small-textbox" maxlength="6" size="4" placeholder="0" /></td>
										<td class="purchase_price"><input type="text" class="form-control text-right _purchase_price _allownumericwithdecimal small-textbox"  maxlength="10" size="4"  placeholder="0"  /></td>
																				
										<td class="adjustement_column"></td>
																				
										<td class="product_total"><input type="text" class="form-control text-right _product_total small-textbox" readonly="readonly" size="4" value="0"   /></td> 
										<td class="product_note"><input type="text" class="form-control _product_note" size="6" maxlength="250" placeholder="<?php _e("Product additional notes","niwoopo"); ?>" /></td> 
										<td class="remove_product"><i class="fa fa-trash fa-2x _remove_product" aria-hidden="true" title="<?php _e("Remove","niwoopo"); ?>"></i></td>
									</tr>
								</tbody>							  
							</table>
						</div>
						
						<div class="ni-po-btns text-center">
							<input type="button" value="<?php _e("Save","niwoopo"); ?>" id="btn_create_purchase_order" class="ni_button_form" />
							<input type="button" value="<?php _e("Delete","niwoopo"); ?>" id="btn_delete_purchase_order" data-target="#confirmationModalCenter" class="ni_button_form ni_delete_btn" style="display:none" />
                            <a href="<?php echo admin_url("admin.php")."?page=niwoopo-manage-po";?>" class="ni_button_form ni_close_btn" id="btn_close_purchase_order"><?php _e('Close','niwoopo'); ?></a>
						</div>
					</div>
				</div>

				<!--<div class="card text-center" style="max-width:100%">
					<div class="card-body">
						<div class="row">
							<div class="col">
								
							</div>
						</div>
					</div>
				</div>-->
			</div>

			
			<?php
		}
		function page_ajax(){
			$call = $this->get_request("call");
			if ($call=="autocomplete_product"){
				$term = $this->get_request("term",'');	
				$r = $this->autocomplete_product($term,$this->niwoopo_constant["plugin_dir_url"]);
				echo json_encode($r);
				die;
			}
			if ($call =="create_po"){
				$this->create_po();
				die;
			}
			if ($call =="update_po"){
				$this->update_po();
				die;
			}
			if ($call =="delete_po"){
				$this->delete_po();
				die;
			}
			if ($call =="get_po_order"){
				
				$message						= array();
				
				$message["message"] 						= __("something went wrong please contact your administrator",'niwoopo');
				$message["status"]   	 					= 0;
				$message["last_error"]   					= "";
				
				$po_id = $this->get_request("po_id",0);	
				if ($po_id >0){
					$po_header  = $this->get_po_header($po_id);
					$po_detail  =  $this->get_po_detail($po_id);
					$message["status"]   	 				= 1;
					$message["po_header"]   	 			= $po_header;
					$message["po_detail"]   				= $po_detail;
					$message["message"] 					="";
					
				}else{
					$message["message"] 						= __("PO ID not found",'niwoopo');
				}
				
				echo json_encode($message);
				die;
			}
			
		}
		function get_po_header($po_id = 0){
			global $wpdb;
			$table_niwoopo_header			= $wpdb->prefix.'niwoopo_header';
			$query 							= "";
			
			$query  = " SELECT * FROM {$table_niwoopo_header} WHERE 1 = 1  AND po_id = %s";
			
			$rows = $wpdb->get_results( $wpdb->prepare($query, $po_id));
			
			return $rows;  
		}
		
		function get_po_detail($po_id = 0, $po_detail_id = 0){			
			global $wpdb;
			$table_niwoopo_detail	= $wpdb->prefix.'niwoopo_detail';
			$posts							= $wpdb->posts;
			$query 							= ""; 
			
			$query  .= " SELECT ";
			$query  .= " niwoopo_detail.po_detail_id  ";
			$query  .= ", niwoopo_detail.po_id  ";	
			$query  .= ", niwoopo_detail.product_id  ";
			$query  .= ", niwoopo_detail.po_quantity  ";	
			$query  .= ", niwoopo_detail.po_received_quantity  ";	
			$query  .= ", niwoopo_detail.po_set_off_quantity  ";	
			$query  .= ", niwoopo_detail.po_balance_quantity  ";	
			$query  .= ", niwoopo_detail.purchase_price  ";	
			$query  .= ", niwoopo_detail.po_product_total  ";	
			$query  .= ", niwoopo_detail.currency_id  ";
			$query  .= ", niwoopo_detail.uom_id  ";
			$query  .= ", niwoopo_detail.product_note  ";
			$query 	.= ", product_post_id.post_title as product_name ";	
			$query 	.= ", '' as product_sku ";
					
			$query  .= " FROM " . $table_niwoopo_detail . " as niwoopo_detail"	;
			$query 	.= " LEFT JOIN {$posts} AS product_post_id ON product_post_id.ID = niwoopo_detail.product_id";
			
			$query  .= " WHERE 1 = 1 ";
			
			if ($po_id > 0)
				$query  .= "AND po_id = " . $po_id;
				
			if ($po_detail_id > 0)
				$query  .= " AND po_detail_id = " . $po_detail_id;
				
			$rows = $wpdb->get_results($query); 
			
			$rows = $this->get_items_postmeta($rows,array('_sku'));
			foreach($rows as $key => $item){
				$product_sku = isset($item->sku) ? $item->sku : '';
				$rows[$key]->product_sku = $product_sku;
				
				$product_image_url = $this->get_product_image_url($item->product_id,$this->niwoopo_constant["plugin_dir_url"]);
				
				if ($product_image_url != "")
					$rows[$key]->product_image_url = $product_image_url;
			}
			
			return $rows;  
		}
		function create_po(){
			global $wpdb;
			$table_niwoopo_header			= $wpdb->prefix.'niwoopo_header';
			$table_niwoopo_detail	= $wpdb->prefix.'niwoopo_detail';
			$data 							= array();
			$message						= array();
			$today_date						= date_i18n("Y-m-d H:i:s");	
			$po_id							= 0;
			$po_detail_id					= 0;
			
			$po_no				= $this->get_request("po_no","");
			$po_date			= $this->get_request("po_date",date_i18n("Y-m-d"));
			$supplier_id		= $this->get_request("supplier_id","0");
			$location_id		= $this->get_request("location_id","0");
			$status_id			= $this->get_request("status_id","0");
			$notes				= $this->get_request("po_notes","");
			
			$data["po_date"] 			= $po_date;
			$data["po_no"] 				= $po_no;
			$data["supplier_id"] 		= $supplier_id;
			$data["location_id"] 		= $location_id;
			$data["created_date"] 		= $today_date;
			$data["updated_date"] 		= $today_date;
			$data["created_user_id"] 	= 1;
			$data["updated_user_id"] 	= 1;
			$data["status_id"] 			= $status_id;
			$data["notes"] 				= $notes;
			
			$wpdb->insert( $table_niwoopo_header, $data);
			$po_id = $wpdb->insert_id;
			
			$products  				= array_map('sanitize_post', isset($_REQUEST["po_product"]) ? $_REQUEST["po_product"] : array());
			
			$data 									= array();
			foreach($products as $key=>$value){
				$data 								= array();
				$product_id							= 0;
				$po_quantity						= 0;
				$data["po_id"] 						= $po_id;
				
				$product_id							= isset($value["product_id"])?$value["product_id"]:0;
				$po_quantity						= isset($value["purchase_quantity"])?$value["purchase_quantity"]:0;
				$po_received_quantity				= isset($value["po_received_quantity"])?$value["po_received_quantity"]:0;
				$po_set_off_quantity				= isset($value["po_set_off_quantity"])?$value["po_set_off_quantity"]:0;
				$po_balance_quantity				= isset($value["po_balance_quantity"])?$value["po_balance_quantity"]:0;
				$purchase_price						= isset($value["purchase_price"])?$value["purchase_price"]:0;
				$po_product_total					= isset($value["product_total"])?$value["product_total"]:0;
				$currency_id						= isset($value["currency_id"])?$value["currency_id"]:0;
				$uom_id								= isset($value["uom_id"])?$value["uom_id"]:0;
				$product_note						= isset($value["product_note"])?$value["product_note"]:"";
				
				$data["product_id"] =$product_id;
				$data["po_quantity"] =$po_quantity;
				$data["po_received_quantity"] =$po_received_quantity;
				$data["po_set_off_quantity"] =$po_set_off_quantity;
				$data["po_balance_quantity"] =$po_quantity;
				$data["purchase_price"] =$purchase_price;
				$data["po_product_total"] =$po_product_total;
				$data["currency_id"] =$currency_id;
				$data["uom_id"] =$uom_id;
				$data["product_note"] =$product_note;
				$wpdb->insert( $table_niwoopo_detail, $data);
				$po_detail_id = $wpdb->insert_id;
			}
			
			$message["po_id"]  		= 0;
			$message["message"] 						= __("Purchase order not saved",'niwoopo');
			$message["status"]   	 					= 0;
			$message["last_error"]   					= "";
			
			if($wpdb->last_error !== '') {
				$message["last_error"]   			 	= $wpdb->last_error ;
			}else{
				$message["po_id"]  	= $po_id;
				$message["message"]  				 	= __("Purchase order <strong> saved successfully</strong>.",'niwoopo');
				$message["status"]   				  	= 1;
			}
			
			echo json_encode($message);
		}
		
		function update_po(){
			global $wpdb;
			$table_niwoopo_header			= $wpdb->prefix.'niwoopo_header';
			$table_niwoopo_detail			= $wpdb->prefix.'niwoopo_detail';
			$data 							= array();
			$message						= array();
			$today_date						= date_i18n("Y-m-d H:i:s");	
			$po_id							= 0;
			$po_detail_id					= 0;
			
			$po_id				= $this->get_request("po_id",0);
			$po_no				= $this->get_request("po_no","");
			$po_date			= $this->get_request("po_date",date_i18n("Y-m-d"));
			$supplier_id		= $this->get_request("supplier_id","0");
			$location_id		= $this->get_request("location_id","0");
			$status_id			= $this->get_request("status_id","0");
			$notes				= $this->get_request("po_notes","");
			
			$data["po_date"] 			= $po_date;
			$data["po_no"] 				= $po_no;
			$data["supplier_id"] 		= $supplier_id;
			$data["location_id"] 		= $location_id;
			$data["created_date"] 		= $today_date;
			$data["updated_date"] 		= $today_date;
			$data["created_user_id"] 	= 1;
			$data["updated_user_id"] 	= 1;
			$data["status_id"] 			= $status_id;
			$data["notes"] 				= $notes;
			
			$wpdb->update($table_niwoopo_header, $data, array('po_id'=>$po_id));
			
			/*Delete*/
			$rows = $wpdb->query($wpdb->prepare("DELETE FROM {$table_niwoopo_detail} WHERE po_id = %s",$po_id)); 
			/*End Delete*/
			
			$products  				= array_map('sanitize_post', isset($_REQUEST["po_product"]) ? $_REQUEST["po_product"] : array());
			
			$data 									= array();
			foreach($products as $key=>$value){
				$data 								= array();
				$product_id							= 0;
				$po_quantity						= 0;
				$data["po_id"] 						= $po_id ;
				$product_id							= isset($value["product_id"])?$value["product_id"]:0;
				$po_quantity						= isset($value["purchase_quantity"])?$value["purchase_quantity"]:0;
				$po_received_quantity				= isset($value["po_received_quantity"])?$value["po_received_quantity"]:0;
				$po_set_off_quantity				= isset($value["po_set_off_quantity"])?$value["po_set_off_quantity"]:0;
				$po_balance_quantity				= isset($value["po_balance_quantity"])?$value["po_balance_quantity"]:0;
				$purchase_price						= isset($value["purchase_price"])?$value["purchase_price"]:0;
				$po_product_total					= isset($value["product_total"])?$value["product_total"]:0;
				$currency_id						= isset($value["currency_id"])?$value["currency_id"]:0;
				$uom_id								= isset($value["uom_id"])?$value["uom_id"]:0;
				$product_note						= isset($value["product_note"])?$value["product_note"]:"";
				
				$data["product_id"] =$product_id;
				$data["po_quantity"] =$po_quantity;
				$data["po_received_quantity"] =$po_received_quantity;
				$data["po_set_off_quantity"] =$po_set_off_quantity;
				$data["po_balance_quantity"] =$po_quantity;
				$data["purchase_price"] =$purchase_price;
				$data["po_product_total"] =$po_product_total;
				$data["currency_id"] =$currency_id;
				$data["uom_id"] =$uom_id;
				$data["product_note"] =$product_note;
				$wpdb->insert( $table_niwoopo_detail, $data);
				$po_detail_id = $wpdb->insert_id;
			}
			
			$message["po_id"]  		= 0;
			$message["message"] 						= __("Purchase order not saved",'niwoopo');
			$message["status"]   	 					= 0;
			$message["last_error"]   					= "";
			if($wpdb->last_error !== '') {
				$message["last_error"]   			 	= $wpdb->last_error ;				
			}else{
				$message["po_id"]  	= $po_id;
				$message["message"]  				 	= __("Purchase order <strong> saved successfully</strong>.",'niwoopo');
				$message["status"]   				  	= 1;
			}
			
			echo json_encode($message);
		}
		
		function delete_po(){
			global $wpdb;
			$message						= array();
			$message["status"]   			= 0;
			$message["message"]				= '';
			$table_niwoopo_header	= $wpdb->prefix.'niwoopo_header';
			$table_niwoopo_detail	= $wpdb->prefix.'niwoopo_detail';
			$po_id					= $this->get_request("po_id",0);
			
			/*Delete Header*/
			$query  = " DELETE FROM {$table_niwoopo_header} WHERE po_id = %s";
			$rows = $wpdb->query($wpdb->prepare($query,$po_id)); 
			/*End Delete*/
			
			
			/*Delete Details*/
			$query  = "DELETE FROM {$table_niwoopo_detail} WHERE po_id = %s";
			$rows = $wpdb->query($wpdb->prepare($query,$po_id)); 
			/*End Delete*/
			
			if($wpdb->last_error !== '') {
				$message["status"]   				  	= 0;
				$message["message"]  				 	= __("Purchase order <strong> not deleted</strong>.",'niwoopo');
			}else{
				$message["message"]  				 	= __("Purchase order <strong> deleted successful</strong>.",'niwoopo');
				$message["status"]   				  	= 1;
			}
			
			echo json_encode($message);
			die;
		}
	}
}