<?php 
if ( !class_exists( 'NiWooPO_Function' ) ) {
	class NiWooPO_Function{
		
		var $niwoopo_constant = array();  
		
		var $vars = array();  
		
		function __construct($niwoopo_constant = array()){			
			$this->niwoopo_constant = $niwoopo_constant;
		}
		
		function get_settings($option_name = 'niwoopo_options'){
			if(!isset($this->vars[$option_name])){
				$this->vars[$option_name] = get_option($option_name, array());				
			}		
			return $this->vars[$option_name];		
		}
		
		function get_setting($field_name = '', $default = '', $option_name = 'niwoopo_options'){
			$settings = $this->get_settings($option_name);	
			$setting = isset($settings[$field_name]) ? $settings[$field_name] : $default;
			return $setting;
		}
		
		function get_currency_code(){
			if(function_exists('get_woocommerce_currency')){
				return get_woocommerce_currency();
			}			
			return 'USD';
		}
		
		function autocomplete_product($search_string = "",$plugin_dir_url=""){
			
			$wkwc_pos_addon_upc = '';	
					
			$wkwc_pos_addon_ean = '';
			
			$items = $this->get_products($search_string);
			
			$serach_type = $this->get_request('serach_type','product_name');
			
			$items = $this->get_items_postmeta($items,array('_sku','_ni_cost_goods','_ni_vendor_id','_ni_brand_id','jk_sku','_sku','_weight','wkwc_pos_addon_upc','_wkwc_pos_addon_upc','wkwc_pos_addon_ean','_wkwc_pos_addon_ean'));
			
			$search_results = array();
			
			foreach ($items as $key => $item){
				
				//error_log(	json_encode($item));
				//error_log(	json_encode($item->ni_cost_goods	));
				
				$product_post_id			  = isset($item->product_post_id) 	? $item->product_post_id   : 0;
				$product_parent_id			  = isset($item->product_parent_id) ? $item->product_parent_id : 0;
				$ni_cost_goods			  	  = isset($item->ni_cost_goods) 	? $item->ni_cost_goods 	   : '';
				$product_name 				  = isset($item->product_name) 		? $item->product_name 	   : '';
				$sku			 			  = isset($item->sku) 				? $item->sku 			   : '';
				$product_type	 			  = isset($item->product_type) 		? $item->product_type 	   : '';				
				$vendor_id	 			  	  = isset($item->ni_brand_id) 		? $item->ni_brand_id 	   : 0;
				$brand_id	 			  	  = isset($item->ni_vendor_id) 		? $item->ni_vendor_id 	   : 0;
				$jk_sku	 				  	  = isset($item->jk_sku) 			? $item->jk_sku 	 	   : '';
				$weight	 				  	  = isset($item->weight) 			? $item->weight 	 	   : '';
				
				$product_id 				  = isset($item->product_id) 		? $item->product_id 	   : 0;
				$variation_id 				  = isset($item->variation_id) 		? $item->variation_id 	   : 0;
				
				
				
				
				/*
				if($product_type == 'product_variation'){					
					$product_id 			  = $product_parent_id;
					$variation_id 			  = $product_post_id;
				}else{					
					$product_id 			  = $product_post_id;
					$variation_id 			  = 0;
				}					
				*/
					
				$search_result 				  = array();
				
				if($serach_type == 'product_name'){
					$value 	     = $product_name;
				}elseif($serach_type == 'jk_sku'){
					$value 	     = $jk_sku;
				}else{
					$value 	     = $sku;
				}				
								
				$search_result['id'] 		        	 = $product_id;	
				$search_result['value'] 		    	 = $value;				
				$search_result['product_name']      	 = $product_name;	
				$search_result['product_sku']       	 = $sku;	
				$search_result['product_post_id']   	 = $product_post_id;
				$search_result['product_id']         	 = $product_id;
				$search_result['variation_id']       	 = $variation_id;
				$search_result['purchase_quantity'] 	 = 1;
				$search_result['purchase_price']  	 	 = $ni_cost_goods;
				$search_result['product_type']  	 	 = $product_type;
				$search_result['jk_sku']  	 			 = $jk_sku;
				$search_result['weight']  	 		     = $weight;	
				$search_result['wkwc_pos_addon_upc']  	 = $wkwc_pos_addon_upc;			
				$search_result['wkwc_pos_addon_ean']  	 = $wkwc_pos_addon_ean;			
				$search_result['vendor_id']  	 		 = $vendor_id;
				
		
				$product_image_url	= $this->get_product_image_url($product_post_id,$plugin_dir_url);
				$search_result['product_image_url']  	 		 = $product_image_url;
				
		
				$search_results[]= $search_result;
				
				
				
				
			}
			
			return $search_results;
		}
		function get_product_image_url($product_post_id,$plugin_dir_url=""){
			$url= "";	
			$src = wp_get_attachment_image_src( get_post_thumbnail_id( $product_post_id	), 'thumbnail_size' );
			$product_image_url = $src[0];
			if ($product_image_url  ==""){
				$url = $plugin_dir_url."admin/img/no-product-image.png";
			}else{
				$url = $product_image_url;
			}
			return $url;
		}
		function get_post_meta($post_id, $meta_key =  array()){
			$order_post_meta  = array();
			global $wpdb;
			$query =  "";
			$query .=  " SELECT ";
			$query .=  " *  ";
			$query .=  " FROM  {$wpdb->prefix}postmeta as postmeta ";
			$query .=  " WHERE 1 = 1";
			$query .=  " AND postmeta.post_id = '{$post_id}'";
			if (count($meta_key)>0){
				$query .=  " AND postmeta.meta_key IN ('" . implode("','", $meta_key) . "')"; 
			}
			
			$rows =  $wpdb->get_results($query);
			foreach($rows as $k=>$v){
				$order_post_meta[ltrim ($v->meta_key,"_")] =$v->meta_value; 
			}
			
			return $order_post_meta;
			
		}
		function get_item_meta_key_list(){
			$meat_key = array("_sku","_manage_stock","_stock","_backorders","_visibility","_regular_price","_sale_price","_price","_stock_status");
			return $meat_key;
		 } 
		function get_product_parent(){
		    global $wpdb;
			$query = "";
			$query = " SELECT ";
			$query .= " posts.post_parent as post_parent ";
			$query .= " FROM  {$wpdb->prefix}posts as posts			";
			$query .= "	WHERE 1 = 1";
			$query .= "	AND posts.post_type  IN ('product_variation') ";
			$query .=" AND posts.post_status='publish'";
			
			$query .= " GROUP BY post_parent ";
			$row = $wpdb->get_results($query);		
			
			$post_parent_array = array();
			foreach($row as $key=>$value){
				$post_parent_array[] = $value->post_parent;
			}
			return $post_parent_array;
		}
		function get_products($search_string = ""){
			global $wpdb;
			
			$search_string 		= trim($search_string);			
			
			$translation 		= false;
			$default_language_code	= 'en';
			
			
			
			$serach_type = $this->get_request('serach_type','product_name');
			$call 		 = $this->get_request('call','');
			
			
			
			$sql = "SELECT  posts.post_parent";
			
			$sql .= " FROM {$wpdb->posts} AS posts";
			
			$sql .= " WHERE 1*1";
			
			$sql .= " AND posts.post_type IN ('product_variation')";			
			
			$sql .= " AND posts.post_parent > 0 ";
					
			$sql .= " GROUP BY posts.post_parent";
			
			$sql .= " ORDER BY posts.post_parent ASC";	
			
			$sql_post_parent = $sql;
			
			$sql = "SELECT  ";				
					
			$sql .= " post_title AS product_name ";
			
			$sql .= ", ID AS product_post_id ";

			$sql .= ", post_parent AS product_parent_id";

			$sql .= ", post_type AS product_type";
			
			
			if ($search_string!=NULL){
				if($serach_type == 'product_sku'){
					$sql .= ", product_sku.meta_value AS sku";
				}
				
				if($serach_type == 'jk_sku'){
					$sql .= ", jk_sku.meta_value AS jk_sku";
				}
			}
			
			if($translation){
				$sql .= ", icl_translations.language_code AS language_code";
			}
			
			$sql .= " FROM {$wpdb->posts} AS posts";
			
			if ($search_string!=NULL){
				if($serach_type == 'product_sku'){
					$sql .= " LEFT JOIN {$wpdb->postmeta} AS product_sku ON product_sku.post_id = posts.ID";
				}
				
				if($serach_type == 'jk_sku'){
					$sql .= " LEFT JOIN {$wpdb->postmeta} AS jk_sku ON jk_sku.post_id = posts.ID";
				}
			}
			
			
			
			$sql .= " WHERE 1*1";
			
			
			$sql .= " AND posts.post_type IN ('product','product_variation')";
			
			$sql .= " AND posts.post_status IN ('publish')";
			
			$sql .= " AND posts.ID NOT IN({$sql_post_parent})";
			
			if ($search_string!=NULL){				
				if($serach_type == 'product_sku'){
					$sql .= " AND product_sku.meta_key = '_sku'";
					$sql .= " AND product_sku.meta_value LIKE '%{$search_string}%'";
				}elseif($serach_type == 'jk_sku'){
					$sql .= " AND jk_sku.meta_key = 'jk_sku'";
					$sql .= " AND jk_sku.meta_value LIKE '%{$search_string}%'";
				}else{
					$sql .= " AND posts.post_title LIKE '%{$search_string}%'";	
				}
			}
			
			
					
			$sql .= " GROUP BY product_post_id";
			
			$sql .= " ORDER BY product_name ASC";			
			$items = $wpdb->get_results( $sql);
			
			
			
			foreach ($items as $key => $item){				
				$product_post_id			  = isset($item->product_post_id) 	? $item->product_post_id   : 0;
				$product_parent_id			  = isset($item->product_parent_id) ? $item->product_parent_id : 0;
				$product_type			 	  = isset($item->product_type) ? $item->product_type : 0;
				
				if($product_type == 'product_variation'){
					$items[$key]->product_id   = $product_parent_id;
					$items[$key]->variation_id = $product_post_id;
				}else{
					$items[$key]->product_id   = $product_post_id;
					$items[$key]->variation_id = 0;					
				}
			}
			
			return $items;
			
		}
		function get_items_postmeta($items, $meta_key = array('_sku')){
			$products = array();
			foreach($items as $key => $item){
				if(isset($item->product_id)){
					$products[] = $item->product_id;	
				}
				
				if(isset($item->product_post_id)){
					$products[] = $item->product_post_id;	
				}
				
			}					
			$product_ids =  implode(",",$products);					
			$product_metas = $this->get_product_meta($product_ids,$meta_key);
			
			foreach($items as $key => $item){
				$product_id = isset($item->product_id) ? $item->product_id : 0;
				
				$items[$key]->sku = isset($items[$key]->sku) ? $items[$key]->sku  :'';
				
				if(isset($product_metas[$product_id])){
					$product_meta = $product_metas[$product_id];
					foreach($product_meta as $meta_key => $meta_value){
						$items[$key]->$meta_key = $meta_value;
					}
				}
			}
			
			return $items;
		}
		function get_product_meta($product_ids = "", $meta_key = array('_sku')){
			global $wpdb;
			
			$sql = "SELECT post_id";
			$sql .= ", TRIM(LEADING '_' FROM meta_key) AS meta_key";
			$sql .= ", meta_value ";
			$sql .= "FROM {$wpdb->postmeta} AS postmeta";
			$sql .= " WHERE 1*1";
			
			if($product_ids != ""){
				$sql .= " AND post_id IN({$product_ids})";
			}
			
			if(count($meta_key) > 0){
				$meta_key = implode("','",$meta_key);
				$sql .= " AND postmeta.meta_key IN('{$meta_key}')";
			}
			
			$sql .= " AND LENGTH(TRIM(postmeta.meta_value))>0";
			
			$items = $wpdb->get_results( $sql);
			
			
			$results = array();
			foreach($items as $key => $item){
				$post_id = $item->post_id;
				$meta_key = $item->meta_key;
				$meta_value = $item->meta_value;
				$results[$post_id][$meta_key] = $meta_value;
			}
			
			return $results;
		}
		function get_request($name,$default = NULL,$set = false){
			if(isset($_REQUEST[$name])){
				$newRequest = sanitize_textarea_field($_REQUEST[$name]);
				
				if(is_array($newRequest)){
					$newRequest = implode(",", $newRequest);
				}else{
					$newRequest = trim($newRequest);
				}
				
				if($set) $_REQUEST[$name] = $newRequest;
				
				return $newRequest;
			}else{
				if($set) 	$_REQUEST[$name] = $default;
				return $default;
			}
		}
		function get_po_order_status(){
			$order_status = array();
			$order_status["0"]  = __("Select Status",'niwooims_textdomain');
			$order_status["1"]  = __("Pending",'niwooims_textdomain');
			$order_status["2"] 	= __("Received",'niwooims_textdomain');
			
			return $order_status;
		}
		function get_user_role(){
			global $wp_roles;
			$roles = $wp_roles->get_names();
		
			return $roles;
		}
		function get_user_list($user_id =NULL){
			global $wpdb;
			
			$options = get_option('niwoopo_options');
			$role = isset($options["user_role"])?$options["user_role"]:'';
			$query=  "";
			
			
			$query = " SELECT ";
			$query .= " users.ID as user_id  ";
			$query .= " ,users.user_email as user_email  ";
			$query .= " ,first_name.meta_value as first_name  ";
			$query .= " ,last_name.meta_value as last_name  ";
			
			$query .= " FROM	{$wpdb->prefix}users as users  ";
			
			
			$query .= " LEFT JOIN {$wpdb->prefix}usermeta  role ON role.user_id=users.ID ";
			$query .= " LEFT JOIN {$wpdb->prefix}usermeta  first_name ON first_name.user_id=users.ID ";
			$query .= " LEFT JOIN {$wpdb->prefix}usermeta  last_name ON last_name.user_id=users.ID ";
			
			$query .= " WHERE 1 = 1 ";
			$query .= " AND   role.meta_key='{$wpdb->prefix}capabilities'";
			$query .= " AND  role.meta_value   LIKE '%\"{$role}\"%' ";
			
			$query .= " AND   first_name.meta_key='first_name'";
			$query .= " AND   last_name.meta_key='last_name'";
				
			if ($user_id !=NULL){
				$query .= " AND  users.ID = '{$user_id }'";
			}
			$query .= "  ORDER BY first_name.meta_value ASC";
			
			
			$row = $wpdb->get_results($query);
			//$this->print_data($row);
			return $row;
		}	
		function prettyPrint($ar,$display = true) {
			if($ar){
				$output = "<pre>";
				$output .= print_r($ar,true);
				$output .= "</pre>";
			
			if($display){
				echo balanceTags($output,true);
			}else{
				return $output;
				}
			}
		}
		
		function load_pdf_lib($output = '',$filename = '', $stream = true){
			
			try {
				ini_set("memory_limit","100M");

				$path = dirname($this->niwoopo_constant['file_path']);
				
				if(!file_exists($path."/dompdf/dompdf.php")){
					$path = WP_CONTENT_DIR;
					if(!file_exists($path."/dompdf/dompdf.php")){
						wp_die(__('PDF Class Missing','niwooims_textdomain'));
						return false;
					}
				}

				require($path."/dompdf/dompdf.php");

				$dompdf->loadHtml($output);

				//$dompdf->loadHtml( utf8_decode( $output));
				
				// $dompdf->load_html(iconv("UTF-8", "CP1252", $output));
				
				$settings 		 		 	 = $this->get_settings();
				$orientation 		 		 = isset($settings['orientation']) ? $settings['orientation'] : 'landscape';
				$paper_size 		 		 = isset($settings['paper_size']) ? $settings['paper_size'] : 'A3';
				
				$orientation = $this->get_request('orientation',$orientation);
				
				$paper_size = $this->get_request('paper_size',$paper_size);

				// (Optional) Setup the paper size and orientation
				$dompdf->setPaper('A4', 'landscape');

				// Render the HTML as PDF
				$dompdf->render();

				// Output the generated PDF to Browser
				 if ($stream) {
					$dompdf->stream($filename);
				}

				//write_file("./tmp/".$filename, $dompdf->output());
			
			} catch (Exception $e) {
				 $this->request->params['ext'] = 'html';
				 throw $e;
			 }
			
			die;
			
		}
		
	}
}
?>