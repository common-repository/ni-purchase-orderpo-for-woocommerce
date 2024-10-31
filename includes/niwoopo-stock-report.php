<?php 
if ( !class_exists( 'NiWooPO_Stock_Report' ) ) {
	include_once("niwoopo-function.php"); 	
	class NiWooPO_Stock_Report extends NiWooPO_Function{
		var $niwoopo_constant = array();  
		function __construct($niwoopo_constant = array()){
			$this->niwoopo_constant = $niwoopo_constant;
			
		}
		function page_init(){
			  $page  		= $this->get_request("page");
			  $start_date  	= $this->get_request("start_date",date_i18n("Y-m-d"));
			  $end_date  	= $this->get_request("end_date",date_i18n("Y-m-d"));
			  $mange_url 	= admin_url('admin.php?page=niwoopo-manage-po&niwoopo_action=add');
			?>
			<div id="niwooims" class="ni-woopo-plug">
				<form name="stock_report" id="stock_report"  autocomplete="off">
					<div class="ni-box ni-search-form">
						<div class="ni-box-header">
							<h4><?php esc_html_e("Stock Report","niwooims_textdomain"); ?></h4>
						</div>
						
						<div class="ni-box-body">
							
                            <div class="ni-form-group ni-row">
                            	<div class="ni-form-label">
									<label  class="col-form-label" for="manage_stock"> <?php esc_html_e("Manage Stock","niwooims_textdomain") ?></label>
								</div>
								<div class="ni-form-field">
                                    <select id="manage_stock" name="manage_stock" class="form-control">
                                        <option value="-1" selected="selected"><?php echo esc_attr("Select One","niwoosrpro_textdomain") ?> </option>
                                        <option value="yes"> <?php echo esc_attr("Yes","niwoosrpro_textdomain"); ?>  </option>
                                        <option value="no"><?php echo esc_attr("No","niwoosrpro_textdomain"); ?>  </option>
                                    </select>
								</div>
                                
                                <div class="ni-form-label">
									<label class="col-form-label"  for="stock_status"><?php esc_html_e("Stock Status","niwoosrpro_textdomain") ?></label>
								</div>
								<div class="ni-form-field">
                                    <select id="stock_status" name="stock_status" class="form-control">
										 <option value="-1" selected="selected"><?php echo esc_attr("Select One","niwoosrpro_textdomain"); ?> </option>
										<option value="instock"> <?php echo esc_attr("In stock","niwoosrpro_textdomain"); ?></option>
										<option value="outofstock"> <?php echo esc_attr("Out of stock","niwoosrpro_textdomain"); ?></option>
									</select>
								</div>
                                
                            </div>
                            
                             <div class="ni-form-group ni-row">
                            	<div class="ni-form-label">
									<label  class="col-form-label" for="backorders"><?php esc_html_e("Backorders","niwooims_textdomain");?></label>
								</div>
								<div class="ni-form-field">
                                    <select id="backorders" name="backorders" class="form-control">
                                        <option value="-1" selected="selected"><?php echo esc_attr("Select One","niwoosrpro_textdomain") ?> </option>
                                        <option value="no"> <?php echo esc_attr("Do not allow","niwoosrpro_textdomain") ?>  </option>
                                        <option value="notify">  <?php echo esc_attr(" Allow, but notify customer","niwoosrpro_textdomain") ?> </option>
                                        <option value="yes"> <?php echo esc_attr(" Allow","niwoosrpro_textdomain") ?> </option>
                                    </select>
								</div>
                                
                                <div class="ni-form-label">
										<label class="col-form-label" for="product_name"><?php esc_html_e("Product Name","niwoosrpro_textdomain") ?></label>
								</div>
								<div class="ni-form-field">
                                   <input id="product_name" name="product_name" type="text" class="form-control">
								</div>
                                
                            </div>
                            
                             <div class="ni-form-group ni-row">
                             	<div class="ni-form-label">
										<label class="col-form-label" for="product_sku"><?php esc_html_e("Product SKU","niwoosrpro_textdomain") ?></label>
								</div>
								<div class="ni-form-field">
                                   <input id="product_sku" name="product_sku" type="text" class="form-control">
								</div>
                                
                                
                                <div class="ni-form-label">
										<label class="col-form-label" for="per_page"><?php esc_html_e("Show Product","niwoosrpro_textdomain") ?></label>
								</div>
								<div class="ni-form-field">
                                 	 <select id="per_page" name="per_page" class="form-control">
                                    	 <option value="10"> <?php echo esc_attr("10","niwoosrpro_textdomain") ?>  </option>
                                         <option value="25"> <?php echo esc_attr("25","niwoosrpro_textdomain") ?>  </option>
                                         <option value="50"> <?php echo esc_attr("50","niwoosrpro_textdomain") ?>  </option>
                                         <option value="100"> <?php echo esc_attr("100","niwoosrpro_textdomain") ?>  </option>
                                         <option value="200"> <?php echo esc_attr("200","niwoosrpro_textdomain") ?>  </option>
                                         <option value="400"> <?php echo esc_attr("400","niwoosrpro_textdomain") ?>  </option>
                                         <option value="600"> <?php echo esc_attr("600","niwoosrpro_textdomain") ?>  </option>
                                         <option value="800"> <?php echo esc_attr("800","niwoosrpro_textdomain") ?>  </option>
                                           <option value="100"> <?php echo esc_attr("1000","niwoosrpro_textdomain") ?>  </option>
                                    </select>
								</div>
                                
                                
                             </div>
							
						
							 <div class="ni-form-group ni-row">
                             	<div class="ni-form-field" >
                                	<p class="please_wait" style="display:none; font-weight:bold;color:#ffa726"><?php esc_html_e('Please Wait!','niwooims_textdomain'); ?></p>
                                </div>
                                <div class="ni-form-field" >
                                  
								</div>
                                <div class="ni-form-field" style="text-align:right;">
                                    <input type="hidden" name="action" id="action" value="niwoopo_ajax" />
                                    <input type="hidden" name="niwoopo_action" id="niwoopo_action" value="niwoopo_stock_report" />
                                    <input type="hidden" name="call" id="call" value="stock_report" />
                                    <input type="hidden" name="page" id="page" value="<?php echo $page; ?>" />
                                    <input type="hidden" name="id" id="id" value="<?php echo $this->get_request("id",0); ?>" />
                                    <input type="hidden" name="p" id="p" value="<?php echo $this->get_request("p",1); ?>" />
                                    <input type="hidden" name="limit" id="limit" value="<?php echo $this->get_request("limit",10); ?>" />
                                    <input type="submit"  value="<?php esc_html_e('Search','niwooims_textdomain'); ?>" class="ni_button_form" />
								</div>
                             </div>
                            
							
						</div>
					</div>
				</form>
				
				<div class="commanSearchResults" style="display:none">
                	<div class="card" style="max-width:100%">
                      <div class="card-body">
                            <div style="overflow-x:auto;">						  								
                            <?php esc_html_e('Please Wait!','niwooims_textdomain'); ?>
                            </div>
                      </div>
                    </div>
                </div>
                
                <div class="commanSearchResults">
                	<div class="ni-box ni-search-data">
						<div class="ni-box-body">
                            <div class="table-responsive">
                            	<div class="_po_table"></div>
                            </div>
                      	</div>
                    </div>
                </div>
			</div>

			
			<?php
		}
		function page_ajax(){
						$call = $this->get_request("call");
			if ($call == "stock_report"){
				//$this->get_purchase_list();
				$this->get_stock_report();
			}

			die;
		}
		function get_stock_report(){
			$page  			= $this->get_request("page");
			$rows 	 		= $this->get_query();
			$columns 		= $this->get_columns();
			$mange_url 		= admin_url('admin.php?page='.$page.'&niwoopo_export_action=niwoopo_export&niwoopo_action=');			
			?>
            <table class="ni-table" cellspacing="0" cellpadding="0">
				<thead>
					<tr>
						<?php foreach($columns as $key=>$value): ?>
							<th><?php echo $value; ?></th>	
						<?php endforeach; ?>
					</tr>
				</thead>
				<tbody>
                	<?php if (count($rows)==0): ?>
                    <tr>
                    	<td colspan="<?php echo count($columns ); ?>">  <?php _e('no record found','niwooims_textdomain'); ?></td>
                    </tr>
                    <?php return; ?>
                    <?php endif ; ?>
					<?php foreach ($rows as $rk => $rv): ?>
					<tr>
						<?php foreach ($columns as $ck => $cv): ?>
							<?php 
							switch ($ck) {
								case "stock_value":
								case "regular_price":
								case "price":
									$value  = isset ($rv->$ck)?$rv->$ck:0;
									?>
									<td><?php echo wc_price($value); ?></td>
									<?php
									break;
								case "order_total":
									$value  = isset ($rv->$ck)?$rv->$ck:0;
									?>
									<td><?php echo wc_price($value); ?></td>
									<?php
									break;
								case "order_status":
									$value  = isset ($rv->$ck)?$rv->$ck:"";
									$value =ucfirst ( str_replace("wc-","", $value));
									?>
									<td><?php echo $value; ?></td>
									<?php
									break;
								case "edit":
									$po_id = $rv->po_id;
									$value  = '<a href="'.$mange_url.'edit&&po_id='.$po_id.'" class="edit-icon _edit" data-po_id='.$po_id.'><i class="fa fa-pencil" aria-hidden="true"></i></a>';
									?>
									<td class="ni-center"><?php echo $value; ?></td>
									<?php
									break;
								case "print":
									$po_id = $rv->po_id;
									$value  = '<a href="'.$mange_url.'print&&po_id='.$po_id.'" class="print-icon _print" data-po_id='.$po_id.'><i class="fa fa-print" aria-hidden="true"></i></a>';
									?>
									<td class="ni-center"><?php echo $value; ?></td>
									<?php
									break;
								case "download":
									$po_id = $rv->po_id;
									$value  = '<a href="'.$mange_url.'pdf&&po_id='.$po_id.'" class="download-icon _download"  data-po_id='.$po_id.'><i class="fa fa-download" aria-hidden="true"></i></a>';
									?>
									<td class="ni-center"><?php echo $value; ?></td>
									<?php
									break;	
								default:
								$value  = isset ($rv->$ck)?$rv->$ck:"";
								?>
								<td><?php echo $value; ?></td>
								<?php		
							}
							?>
						<?php endforeach; ?>	
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
            <?php
			
			
		}
		 function get_query($type="DEFAULT"){
			 
		 	global $wpdb;
			
			 
			$stock_status			= $this->get_request("stock_status","-1");
			$backorders				= $this->get_request("backorders","-1");
			$manage_stock			= $this->get_request("manage_stock","-1");
		    $product_name			= $this->get_request("product_name");
			$product_sku			= $this->get_request("product_sku");
			 
			$product_parent 		= $this->get_product_parent();
			$meta_key				=  $this->get_item_meta_key_list() ; 
			
			
			$start = 0;
		    $per_page   			= $this->get_request("per_page",100);
			
			$p   					= $this->get_request("p");
			if($p > 1){	$start = ($p - 1) * $per_page;}
			
			$query  = "";
			
			
			$query .=" SELECT    ";
			$query .=" post.ID as product_id ";
			$query .=", post.post_title as product_name ";
		
			$query .=" FROM {$wpdb->prefix}posts as post  ";
			
			
			
			if ($stock_status !="-1"){
				$query .=" LEFT JOIN {$wpdb->prefix}postmeta as stock_status ON stock_status.post_id=post.ID ";
			}
			if ($backorders !="-1"){
				$query .=" LEFT JOIN {$wpdb->prefix}postmeta as backorders ON backorders.post_id=post.ID ";
			}
			if ($manage_stock !="-1"){
				$query .=" LEFT JOIN {$wpdb->prefix}postmeta as manage_stock ON manage_stock.post_id=post.ID ";
			}
			
			if ($product_sku !=''){
				$query .=" LEFT JOIN {$wpdb->prefix}postmeta as sku ON sku.post_id=post.ID ";
			}
			

			$query .=" WHERE 1=1 ";
			$query .= "	AND post.post_type  IN ('product_variation','product') ";
			$query .= "	AND post.ID NOT IN ('".  implode("','", $product_parent). "') ";
			$query .=" AND post.post_status='publish'";
			
			$query .= " AND post.post_status NOT IN ('trash','auto-draft')	";
			
			
			if ($stock_status !="-1"){
				$query .=" AND stock_status.meta_key='_stock_status'";
				$query .=" AND stock_status.meta_value='{$stock_status}'";
			}
			
			if ($backorders !="-1"){
				$query .=" AND backorders.meta_key='_backorders'";
				$query .=" AND backorders.meta_value='{$backorders}'";
			}
			
			if ($manage_stock !="-1"){
				$query .=" AND manage_stock.meta_key='_manage_stock'";
				$query .=" AND manage_stock.meta_value='{$manage_stock}'";
			}
			
			if (strlen($product_name)>0){
				$query .=" AND post.post_title LIKE '%{$product_name}%'";
			}
			if ($product_sku !=''){
				$query .=" AND sku.meta_key='_sku'";
				$query .=" AND sku.meta_value LIKE '%{$product_sku}%'";
			}
			
			$query .=" ORDER BY post.post_title "; 
			 
			if ($type=="ARRAY_A") /*Export*/
				$results = $wpdb->get_results( $query, ARRAY_A );
			if($type=="DEFAULT"){
				 /*default*/
				$query .= " LIMIT {$start} , {$per_page}";
				$results = $wpdb->get_results( $query);	
			}
			if($type=="EXPORT"){
				$results = $wpdb->get_results( $query);	
			}
			if($type=="COUNT"){ /*Count only*/	
				$results = $wpdb->get_results($query);		
			    $results = 	count($results);	
			}	
			if ($type =="DEFAULT" || $type=="EXPORT"){
				foreach($results as $key=>$value){
					$product_id =$value->product_id ;
					$all_meta = $this->get_post_meta($product_id,$meta_key);
					foreach($all_meta as $k=>$v){
						$results[$key]->$k =$v;
					}
				}
				foreach($results as $key=>$value){
					$qty = ($value->stock == '') ? 0 : $value->stock;
					$qty = is_numeric($qty) ? $qty : 0;
					
					$results[$key]->stock = $qty ;
					
					$price = ($value->price == '') ? 0 : $value->price;
					$price = is_numeric($price) ? $price : 0;
					
					$results[$key]->stock_value = $price * $qty ;
				}
			}
			return $results;						
		}
		function get_columns(){
			$column = array();
			//$column ["po_id"] = esc_html__("po_id","niwoopo");
			$column ["product_id"] = esc_html__("ID#","niwoopo");
			$column ["product_name"] = esc_html__("Product Name","niwoopo");
			$column ["sku"] = esc_html__("SKU","niwoopo");
			$column ["manage_stock"] = esc_html__("Manage Stock","niwoopo");
			$column ["backorders"] = esc_html__("Backorders","niwoopo");
			$column ["stock_status"] = esc_html__("Stock Status","niwoopo");
			$column ["stock"] = esc_html__("Stock","niwoopo");
			$column ["regular_price"] = esc_html__("Regular Price","niwoopo");
			$column ["price"] = esc_html__("Price","niwoopo");
			$column ["stock_value"] = esc_html__("Stock Value","niwoopo");
			
		
			return $column;
		}
	}
}
?>