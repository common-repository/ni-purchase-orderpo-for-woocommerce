<?php 
if ( !class_exists( 'NiWooPO_PO_List' ) ) {
	include_once("niwoopo-function.php"); 	
	class NiWooPO_PO_List extends NiWooPO_Function{
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
				<form name="po_list" id="po_list"  autocomplete="off">
					<div class="ni-box ni-search-form">
						<div class="ni-box-header">
							<h4><?php _e("Purchase Order","niwooims_textdomain"); ?></h4>
						</div>
						
						<div class="ni-box-body">
							<div class="ni-form-group ni-row">
								<div class="ni-form-label">
									<label class="col-form-label" for="range_start_date"><?php _e("Start Date:","niwooims_textdomain"); ?></label>
								</div>
								<div class="ni-form-field">
									<input type="text" class="form-control _niwooims_datepicker" name="start_date" id="range_start_date" value="<?php echo $start_date; ?>" autocomplete="off" />
								</div>
								<div class="ni-form-label">
									<label class="col-form-label" for="range_end_date"><?php _e("End Date:","niwooims_textdomain"); ?></label>
								</div>
								<div class="ni-form-field">
									<input type="text" class="form-control _niwooims_datepicker" name="end_date" id="range_end_date" value="<?php echo $end_date; ?>" autocomplete="off" />
								</div>
							</div>
							
							<p class="please_wait" style="display:none;"><?php _e('Please Wait!','niwooims_textdomain'); ?></p>
							
							<div class="ni-btns ni-text-right">
								<a href="<?php echo $mange_url;?>" class="ni_button_form ni_button"><i class="fa fa-plus" aria-hidden="true"></i> <?php _e('Add Purchase Order','niwooims_textdomain'); ?></a>
								<input type="hidden" name="action" id="action" value="niwoopo_ajax" />
								<input type="hidden" name="niwoopo_action" id="niwoopo_action" value="niwoopo_po_list" />
								<input type="hidden" name="call" id="call" value="po_list" />
								<input type="hidden" name="page" id="page" value="<?php echo $page; ?>" />
								<input type="hidden" name="id" id="id" value="<?php echo $this->get_request("id",0); ?>" />
								<input type="hidden" name="p" id="p" value="<?php echo $this->get_request("p",1); ?>" />
								<input type="hidden" name="limit" id="limit" value="<?php echo $this->get_request("limit",10); ?>" />
								<input type="submit"  value="<?php _e('Search','niwooims_textdomain'); ?>" class="ni_button_form" />
							</div>
						</div>
					</div>
				</form>
				
				<div class="commanSearchResults" style="display:none">
                	<div class="card" style="max-width:100%">
                      <div class="card-body">
                            <div style="overflow-x:auto;">						  								
                            <?php _e('Please Wait!','niwooims_textdomain'); ?>
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
			if ($call == "po_list"){
						$this->get_purchase_list();
			}

			die;
		}
		function get_purchase_list(){
			$page  			= $this->get_request("page");
			$rows 	 		= $this->get_po_header();
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
								case "order_total":
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
		function get_po_header(){
			global $wpdb;
			$table_niwoopo_header			= $wpdb->prefix.'niwoopo_header';
		    $start_date  	= $this->get_request("start_date",date_i18n("Y-m-d"));
		    $end_date  	= $this->get_request("end_date",date_i18n("Y-m-d"));
		
			$query 							= ""; 
			$query  .= " SELECT * FROM "	;
			$query  .= $table_niwoopo_header	;
			$query  .= " WHERE 1 = 1 ";
			$query .= " AND   date_format( po_date, '%Y-%m-%d') BETWEEN '{$start_date}' AND '{$end_date}'";
			$query  .= " Order BY  po_id DESC";
			
			
			 $rows = $wpdb->get_results($query);
			
			 foreach($rows as $key=>$value){
				 $supplier_id = $value->supplier_id;
				 $status_id = $value->status_id;
				 $supplier_name = esc_html__("Not selected","niwoopo");
				 $status_name  = esc_html__("Not selected","niwoopo");
				 if ($supplier_id > 0){
				    $supplier_name = '';
				    $supplier =  $this->get_user_list($supplier_id );
				  	$supplier_last_name = isset($supplier[0]->last_name)?$supplier[0]->last_name:'';
					$supplier_first_name = isset($supplier[0]->first_name)?$supplier[0]->first_name:'';
				  	$supplier_name = 	$supplier_last_name .', '.$supplier_first_name ;
				 }
				 
				 $rows[$key]->supplier_name = $supplier_name;
				 
				 if ($status_id>0){
					$status=  $this->get_po_order_status();
					$status_name = isset($status[$status_id])?$status[$status_id]:'';
				 }
				 $rows[$key]->status_name = $status_name;
			}
		 	
			//$this->prettyPrint($rows);
			return $rows;  
		}
		function get_columns(){
			$column = array();
			//$column ["po_id"] = esc_html__("po_id","niwoopo");
			$column ["po_no"] = esc_html__("PO#","niwoopo");
			$column ["po_date"] = esc_html__("PO Date","niwoopo");
			$column ["status_name"] = esc_html__("Status","niwoopo");
			$column ["supplier_name"] = esc_html__("Supplier","niwoopo");
			$column ["edit"] = esc_html__("Edit","niwoopo");
			$column ["print"] = esc_html__("Print","niwoopo");
			$column ["download"] = esc_html__("Download","niwoopo");
			
		
			return $column;
		}
	}
}
?>