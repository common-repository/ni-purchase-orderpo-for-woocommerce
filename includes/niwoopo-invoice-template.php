<!doctype html>
<html>
	<head>
	<meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<title><?php echo $pdf_title;?></title>
		<style type="text/css">
			
			body{
				font-size: 70%;
				font-family:"DeJaVu Sans Mono",Verdana, Geneva, sans-serif;
			}
			
			table.table_header, table.table {
			  border-collapse: collapse;
			  width: 100%;
			}

			table.table_header th,
			table.table_header td,
			table.table th,
			table.table td {
			  text-align: left;
			  padding: 8px;
			}

			table.table tr:nth-child(even){background-color: #f2f2f2}

			table.table th {
			  background-color: <?php echo esc_html($background_color)?>;
			  color: white;			  
			}
			
			table.table th,table.table td{border:1px solid #fc4b6c;}
			
			td.product_name span{ font-size:10px;}
			
			.total_purchase_product{ font-size:20px; margin-top:0px;}

			table.table th.text-right,
			table.table td.text-right{
				text-align: right;
			}

			.hide_pdf{ display: none;}
			
			.purchase_order{ font-family:Verdana, Geneva, sans-serif; padding:0; margin:0;}
			.purchase_order_content{ margin:0; padding:0; line-height:22px; font-family:Verdana, Geneva, sans-serif}
			.header {position: fixed; top: -40px; text-align:center;}
		  	.footer { position: fixed; bottom: 0px; text-align:center;}
			.pagenum:before { content: counter(page); }
			
			.invoice_wrap{ width:800px; text-align:center; margin:auto;}
			
			.print_button{ padding:5px 15px; border:1px solid <?php echo esc_html($border_color)?>;}
			
			@media print{
				.print_button{ display:none;}
				.footer{ display:none;}
			}
			
			.purchase_order_notes, .terms_and_conditions{ border:1px solid <?php echo esc_html($border_color)?>; margin:0; width:100%; margin-top:5px; text-align:left;}
			.purchase_order_notes p, .terms_and_conditions p{ margin:5px; padding:0; font-size:12px;}
			
			.width_hundred{ width:100%;}
			.vertical_top{ vertical-align:text-top;}
			.terms_and_conditions{ padding:0px;}
			.terms_and_conditions h5{ background-color:<?php echo esc_html($background_color)?>; padding:5px; color:#FFF; margin:0 0 5px 0;}
			.terms_and_conditions p{ margin:5px;}
			
			.company_logo{ max-height:100px;}
			
			<?php if($niwoopo_action == 'print'):?>
				.footer{ display:none;}
			<?php endif;?>
			<?php if($niwoopo_action == 'pdf'):?>
				.print_buttons{ display:none;}
			<?php endif;?>
		</style>
        <script type="text/javascript">
        	function back_page(){				
				window.location = "<?php echo admin_url('admin.php')."?page=".$page;?>";
			}
        </script>
	</head>

	<body>	
    	<div class="invoice_wrap">
            <table class="table_header" style="width:100%">
                <?php if($shop_logo != "" || $shop_name != ""):?>
                <tr>
                	<td colspan="2">
                    	<?php 
							$logo_conent = "";
							if($shop_logo):								
								if($niwoopo_action == 'pdf'){
									
									$file_contents =  file_get_contents($shop_logo);
									$logo_conent .= '<img src="data:image/png;base64,'.base64_encode($file_contents).'" class="company_logo" alt="'.esc_attr($shop_name).'" />';
								}else{
									$logo_conent .= '<img src="'.esc_url($shop_logo).'" class="company_logo" alt="'.esc_attr($shop_name).'" />';
								}								
							else:
								if($shop_name){
									$logo_conent .= "<h1>".esc_html($shop_name)."</h1>";
								}
							endif;
							
							if($shop_address){
								$logo_conent .= "<p>".wpautop($shop_address)."</p>";
							}
							echo $logo_conent;
						?>
                    </td>
                </tr>
                <?php endif;?>
                <tr>
                	<td style="width:50%; padding-left:0px; padding-bottom:0; vertical-align:top"><h2 class="purchase_order"><?php esc_html_e("Vendor","niwooims_textdomain");?></h2></td>
                    <td style="width:50%; padding-left:100px; padding-bottom:0; vertical-align:top"><h2 class="purchase_order"><?php esc_html_e("PO Details","niwooims_textdomain");?></h2></td>
                </tr>              
                <tr>
                    <td style="width:50%; padding-left:0px; vertical-align:top">                	
                        <?php print($supplier_full_address);?>
                    </td>
                    <td style="width:50%; padding-left:100px; vertical-align:top">      
                        <p class="purchase_order_content">                    	             
                            <strong><?php esc_html_e("Purchase Date:","niwooims_textdomain");?> </strong><span class="po_number"><?php echo date_i18n($date_format,strtotime($header->po_date));?></span>
                            <br /><strong><?php esc_html_e("Purchse No.:","niwooims_textdomain");?> </strong><span class="po_date"><?php echo $header->po_no;?></span>                            
                            <?php if($header->po_no){?>
                                <br /> <strong><?php esc_html_e("Invoice Number:","niwooims_textdomain");?> </strong><span class="po_date"><?php echo $header->po_no;?></span>                
                            <?php }?>
                        </p>
                    </td>
                </tr>
                <tr>
                	<td style="width:50%; padding-left:0px;  padding-bottom:0; vertical-align:top"><h2 class="purchase_order"><?php esc_html_e("Billing Address","niwooims_textdomain");?></h2></td>
                    <td style="width:50%; padding-left:100px;  padding-bottom:0; vertical-align:top"><h2 class="purchase_order"><?php esc_html_e("Shipping Address","niwooims_textdomain");?></h2></td>
                </tr>
                <tr>
                	 <?php if(!empty($billing_address)):?>
                         <td style="width:50%; padding-left:0px; vertical-align:top"> 
                            <?php echo wpautop($billing_address);?>
                         </td>
                     <?php else: ?>
                    	<td></td>     
                    <?php endif;?>
                    
                    <?php if(!empty($shipping_address)):?>
                         <td style="width:50%; padding-left:100px; vertical-align:top">   
                            <?php echo wpautop($shipping_address);?>
                         </td>
                    <?php else: ?>
                    	<td></td>     
                    <?php endif;?>                    
                </tr>
             </table>
            <?php echo $output;?>
            <table cellpadding="0" cellspacing="0" class="width_hundred">
            	<tr>
                	<td class="vertical_top">
                    	<p class="total_purchase_product" style="text-align:right">                    	             
                            <strong><?php esc_html_e("Total:","niwooims_textdomain");?> </strong><span class="po_date"><?php echo wc_price($header->total_purchase_product, $price_args);?></span>                
                        </p>
                    </td>
                </tr>
                <tr>
                	<td class="vertical_top" style="width:100%">                    
                    	
                        <?php if(!empty($header->notes)):?>
                        <div class="purchase_order_notes"><?php echo wpautop($header->notes);?></div>
                        <?php endif;?>
                         
                        <?php if(!empty($term_condition)):?>
                        <div class="terms_and_conditions">
                        	<h5><?php esc_html_e("Terms and Conditions","niwooims_textdomain");?></h5>
							<?php echo wpautop($term_condition);?>
                        </div>
                        <?php endif;?>
                        
                        <?php if(!empty($footer_notes)):?>
                        <div class="terms_and_conditions">
                        	<h5><?php esc_html_e("Footer Notes","niwooims_textdomain");?></h5>
							<?php echo wpautop($footer_notes);?>
                        </div>
                        <?php endif;?>                        
                    </td>                   
                </tr>
            </table>
            <?php if($shop_signature):	 ?> 	
            <table class="table_header" style="width:100%">
             	<tr>
                	<td style="text-align:right">
                    	<?php $signature_conent = ""; ?>
                        <?php 
							
					 	$file_contents_signature =  file_get_contents($shop_signature);
						$signature_conent .= '<img src="data:image/png;base64,'.base64_encode($file_contents_signature).'" class="'.esc_attr($shop_name).'" />';
						echo $signature_conent;
						?>
                    </td>
                </tr>
             </table>
             <?php endif; ?> 
            
            <p class="print_buttons">
            	<button type="button" class="print_button" onClick="javascript:window.print();"><?php esc_html_e("Print","niwooims_textdomain");?></button>
                <button type="button" class="print_button" onClick="back_page()"><?php esc_html_e("Back","niwooims_textdomain");?></button>
            </p>	
            <div class='footer'><span class='pagenum'></span></div>
        </div>
	</body>
</html>