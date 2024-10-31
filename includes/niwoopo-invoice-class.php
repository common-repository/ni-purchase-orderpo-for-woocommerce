<?php 
if ( !class_exists( 'NiWooPO_Invoice_Class' ) ) {
	include_once("niwoopo-function.php"); 	
	class NiWooPO_Invoice_Class extends NiWooPO_Function{
		var $niwoopo_constant = array();  
		function __construct($niwoopo_constant = array()){
			$this->niwoopo_constant = $niwoopo_constant;
			
			$this->niwoopo_constant['directory_name'] = "ni-purchase-order";
		}
		
		function get_invoice_html(){
			global $wpdb;
			
			$niwoopo_header	= $wpdb->prefix.'niwoopo_header';
			$niwoopo_detail	= $wpdb->prefix.'niwoopo_detail';
			
			$date_format 	 = get_option('date_format');
			$niwoopo_action	 = $this->get_request('niwoopo_action');
			$po_id  	 	 = $this->get_request('po_id');
			$page  		 	 = $this->get_request('page');
			 	
			$header = $wpdb->get_row("SELECT * FROM {$niwoopo_header} WHERE po_id IN ({$po_id})");
			$po_id = isset($header->po_id) ? $header->po_id : 0;
			if($po_id <= 0){
				return true;
			}
			
			$supplier_full_address = "";
			
			$supplier_id = $header->supplier_id;
			
			if($supplier_id > 0){
				$user_data = array();
				$user__data = get_user_meta($supplier_id);
				foreach($user__data as $key => $u_data){
					$user_data[$key] = isset($u_data[0]) ? $u_data[0] : '';
				}
				$first_name = isset($user_data['first_name']) ? $user_data['first_name'] : '';
				$last_name = isset($user_data['last_name']) ? $user_data['last_name'] : '';
								
				$header->first_name 	= $first_name;
				$header->last_name 		= $last_name;
				$header->supplier_name  = $last_name ." ". $first_name;
								
				$billing_company 	= isset($user_data['billing_company']) ? $user_data['billing_company'] : '';
				$billing_address_1 	= isset($user_data['billing_address_1']) ? $user_data['billing_address_1'] : '';
				$billing_address_2 	= isset($user_data['billing_address_2']) ? $user_data['billing_address_2'] : '';
				$billing_city		= isset($user_data['billing_city']) ? $user_data['billing_city'] : '';
				$billing_postcode	= isset($user_data['billing_postcode']) ? $user_data['billing_postcode'] : '';
				$billing_state 		= isset($user_data['billing_state']) ? $user_data['billing_state'] : '';
				$billing_country 	= isset($user_data['billing_country']) ? $user_data['billing_country'] : '';
								
				$supplier_address_args = array(
					'first_name' => $first_name,
					'last_name'  => $last_name,
					'company'    => $billing_company,
					'address_1'  => $billing_address_1,
					'address_2'  => $billing_address_2,
					'city'       => $billing_city,
					'state'      => $billing_state,
					'postcode'   => $billing_postcode,
					'country'    => $billing_country,
				);
				
				$supplier_full_address = WC()->countries->get_formatted_address($supplier_address_args);
			}
			
			$pdf_title = sprintf(esc_html__('Purchase order invoice #%s'),$po_id);
			
			$woocommerce_currency 	 = $this->get_currency_code();
			$header->order_currency  = isset($header->order_currency)  ? $header->order_currency   : $woocommerce_currency;
			$header->vendor_currency = isset($header->vendor_currency) ? $header->vendor_currency : $woocommerce_currency;
			$header->vendor_currency = $header->vendor_currency != ""  ? $header->vendor_currency  : $woocommerce_currency;
			
			$price_args = array();
			$price_args['currency'] = $header->order_currency;
			
			
			
			$items = $wpdb->get_results("SELECT * FROM {$niwoopo_detail} WHERE po_id IN ({$po_id}) ORDER BY po_detail_id ASC");
			
			$total = 0;
			
			foreach ($items as $k1 => $v2){
				$total = $total + $v2->po_product_total;
				$currency_id = $v2->currency_id;
				$product_id = $v2->product_id;
				if($currency_id <= 0){
					$v2->currency = $header->order_currency;
				}
				
				if($product_id > 0){
					$v2->product_name = get_the_title($product_id);
				}
			}
			
			$header->total_purchase_product = $total;
			
			$output = $this->get_grid('invoice_columns',$items);
			
			$options = get_option('niwoopo_options');
			
			//$this->prettyPrint($options);
			
			$uploads_dir = wp_upload_dir();
				
			$temp_dir = $uploads_dir['basedir'] . '/'.$this->niwoopo_constant['directory_name'].'/';
			
			$icon_url = $uploads_dir['baseurl'] . '/'.$this->niwoopo_constant['directory_name'].'/';
			
			$billing_address 			= isset($options['billing_address']) ? $options['billing_address'] : '';
			$shipping_address 			= isset($options['shipping_address']) ? $options['shipping_address'] : '';
			
			$shop_name 			= isset($options['shop_name']) ? $options['shop_name'] : '';
			$shop_address		= isset($options['shop_address']) ? $options['shop_address'] : '';
			$term_condition 	= isset($options['term_condition']) ? $options['term_condition'] : '';
			$footer_notes 		= isset($options['footer_notes']) ? $options['footer_notes'] : '';
			$shop_logo 			= isset($options['shop_logo']) ? $options['shop_logo'] : '';
			$shop_signature 	= isset($options['shop_signature']) ? $options['shop_signature'] : '';			
			$border_color 		= isset($options['border_color']) ? $options['border_color'] : "#fc4b6c";
			$background_color 	= isset($options['background_color']) ? $options['background_color'] : "#fc4b6c";
			
			if($niwoopo_action == 'print'){
				if($shop_logo) $shop_logo = $icon_url.$shop_logo;
				
				if($shop_signature) $shop_signature = $icon_url.$shop_signature;
				
				require_once('niwoopo-invoice-template.php');
			}else{
				if($shop_logo) $shop_logo = $temp_dir.$shop_logo;
				
				if($shop_signature) $shop_signature = $icon_url.$shop_signature;
				ob_start();				
				require_once('niwoopo-invoice-template.php');
				$output = ob_get_contents();
				ob_clean();
				$filename = 'PO-'.date_i18n("Y-m-d-H-i-s");
				$this->load_pdf_lib($output,$filename);
			}
			die;
		}
		
		function get_columns($report_id = '', $columns = array()){
			$columns = array();
			$po_show_product_image_column = $this->get_setting('po_show_product_image_column','no');
			if ($report_id=="invoice_columns"){
				
				$column["serial_number"] = esc_html__("#","niwoopo");
				if ($po_show_product_image_column  =="yes"):
					$column["product_image_url"] = esc_html__("","niwoopo");
				endif;
				
				$column["product_name"] = esc_html__("Product Name","niwoopo");
				$column["po_quantity"] = esc_html__("PO Quantity","niwoopo");
				$column["purchase_price"] = esc_html__("Price","niwoopo");
				$column["po_product_total"] = esc_html__("Total","niwoopo");
			}
			return $column;
		}
		
		function get_grid($report_id = "", $item_data = array(), $summary_data = array()){
		   $output = "";
		   
		   if(count($item_data) > 0){
			   $output .= $this->create_grid_table($report_id, $item_data);
		   }else{
		   		$output .='<div class="card" style="max-width:100%;">';
				$output .='<div class="card-body">';
				$output .=	__('Order not found',"niwooims_textdomain");
				$output .='</div>';
				$output .='</div>';
		   }
		   return $output;
	   }
	   function get_imge_base64($path){
	    	//$path = 'myfolder/myimage.png';
			$type = pathinfo($path, PATHINFO_EXTENSION);
			$data = file_get_contents($path);
			$base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
			
			return $base64;

	   }
	   public function create_grid_table($report_id = "", $data = array()){
			$columns 			 = $this->get_columns($report_id, array());			
			$price_decimals		 = $this->get_setting('price_decimals',3);
			$default_currency 	 = $this->get_currency_code();
			$price_args			 = array('decimals' => $price_decimals, 'currency' => $default_currency);			
			$table 				 = '';		
			
			//$this->prettyPrint($data);
			
				
			$table .='<div class="card" style="max-width:100%;">';
				$table .='<div class="card-body">';
					$table .="<div style=\"overflow-x:auto;\">";
							$table .="<table class=\"table\" id=\"_table_product\">";
							$table .="<thead class=\"niwooims-background text-white\">";
							$table .="<tr>";
							/*Create Table Columns*/
							foreach ($columns as $column_name => $column_label){
								$cell_class = $column_name;
								switch($column_name){				
									case "product_name":									
										$cell_class .= ' text-left';
										break;
									case "serial_number":	
									case "po_quantity":
									case "purchase_price":
									case "po_product_total":	
										$cell_class .= ' text-right';
										break;
								}
								$table .="<th scope=\"col\" class=\"{$cell_class}\">{$column_label}</th>";	
							}
							$table .="</tr>";
							$table .="</thead>";
							$table .="<tbody>";
							/*Create Data*/
							foreach ($data as $k1 => $v2){
								$table .="<tr>";
								$item_currency = isset($v2->currency) ? $v2->currency : $default_currency;								
								$price_args['currency'] = $item_currency;
								foreach ($columns as $column_name => $column_label){
									$cell_class = $column_name;
									$cell_data  = '';
									switch ($column_name) {
										case "serial_number":
											$cell_data = $k1+1;
											$cell_class .= ' text-right';
											break;										
										case "po_quantity":
											$cell_data = isset ($v2->$column_name) ? $v2->$column_name : "";
											$cell_class .= ' text-right';
											break;
										case "purchase_price":
										case "po_product_total":	
											$cell_data = isset ($v2->$column_name) ? $v2->$column_name : 0;
											$cell_data = wc_price($cell_data, $price_args);
											$cell_class .= ' text-right';
											break;
										case "product_name":
											$cell_data = isset ($v2->$column_name) ? $v2->$column_name : "";
											$product_note = isset ($v2->product_note) ? $v2->product_note : "";
											if(!empty($product_note)){
												$cell_data .= '<br /><span>'.$product_note.'</span>';
											}
											break;
										case "product_image_url":
											$product_image_url = $this->get_product_image_url( $v2->product_id,$this->niwoopo_constant["plugin_dir_url"]);
											$cell_data = '<img src='. $this->get_imge_base64($product_image_url ).' width="50px">';
											break;	
										default:
											$cell_data = isset ($v2->$column_name) ? $v2->$column_name : "";
									}
									$table .="<td  class=\"{$cell_class}\">{$cell_data}</td>";	
								}

								$table .="</tr>";
							}
							$table .="</tbody>";
							$table .="</table>";
					$table .="</div>";
				$table .="</div>";
			$table .='</div>';			
		   return $table;
		}
	   
	}
	/*End Class*/
}