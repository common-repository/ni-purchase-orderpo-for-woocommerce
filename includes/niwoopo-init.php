<?php 
if ( !class_exists( 'NiWooPO_Init' ) ) {
	include_once("niwoopo-function.php"); 	
	class NiWooPO_Init extends NiWooPO_Function{
		var $niwoopo_constant = array();  
		function __construct($niwoopo_constant = array()){
			$this->niwoopo_constant = $niwoopo_constant;
			add_action( 'admin_menu',  array(&$this,'admin_menu' ),99);
			add_action( 'admin_enqueue_scripts',  array(&$this,'admin_enqueue_scripts' ),99);
			add_action('wp_ajax_niwoopo_ajax', array(&$this,'niwoopo_ajax' ),99);
			
			add_action( 'admin_init',  array(&$this,'niwoopo_export_init' ),99);
		}
		
		function admin_menu(){
			
			add_menu_page(__(  'PO',  'niwoopo')
				,esc_html__( 'PO', 'niwoopo')
				,$this->niwoopo_constant["manage_options"]
				,$this->niwoopo_constant["menu"]
				,array(&$this,'add_po_page')
				,'dashicons-media-document'
				,62.361);
			add_submenu_page($this->niwoopo_constant["menu"]
				,esc_html__( 'Dashboard', 'niwoopo')
				,esc_html__( 'Dashboard', 'niwoopo')
				,$this->niwoopo_constant["manage_options"]
				,'niwoopo-dashboard' 
				,array(&$this,'add_po_page'));
			
			
			add_submenu_page($this->niwoopo_constant["menu"]
				,esc_html__( 'Manage PO', 'niwoopo')
				,esc_html__( 'Manage PO', 'niwoopo')
				,$this->niwoopo_constant["manage_options"]
				,'niwoopo-manage-po' 
				,array(&$this,'add_po_page'));
				
				
			add_submenu_page($this->niwoopo_constant["menu"]
				,esc_html__( 'Stock Report', 'niwoopo')
				,esc_html__( 'Stock Report', 'niwoopo')
				,$this->niwoopo_constant["manage_options"]
				,'niwoopo-stock-report' 
				,array(&$this,'add_po_page'));	
			
			
			add_submenu_page($this->niwoopo_constant["menu"]
				,esc_html__( 'Setting', 'niwoopo')
				,esc_html__( 'Setting', 'niwoopo')
				,$this->niwoopo_constant["manage_options"]
				,'niwoopo-setting' 
				,array(&$this,'add_po_page'));
		}
		
		function niwoopo_export_init(){			
			$niwoopo_export_action  = $this->get_request('niwoopo_export_action'); 
			if($niwoopo_export_action != 'niwoopo_export'){
				return false;
			}
			
			$niwoopo_action  = $this->get_request('niwoopo_action'); 
			if(!in_array($niwoopo_action,array('print','pdf'))){
				return false;
			}
			require_once('niwoopo-invoice-class.php');
			$obj = new NiWooPO_Invoice_Class($this->niwoopo_constant);
			$obj->get_invoice_html();
			die;
		}
		
		function niwoopo_ajax(){			
			$niwoopo_action  = $this->get_request('niwoopo_action'); 
		    $po_id			 = $this->get_request('po_id',0);
			
			if ($niwoopo_action  == "autocomplete_product"){
				include_once("niwoopo-manage-po.php");
				$obj = new  NiWooPO_Manage_PO ($this->niwoopo_constant);
				$obj->page_ajax();		
			}
			if ($niwoopo_action  == "mange_po"){
				include_once("niwoopo-manage-po.php");
				$obj = new  NiWooPO_Manage_PO ($this->niwoopo_constant);
				$obj->page_ajax();			
			}
			if ($niwoopo_action  == "niwoopo_setting"){
				include_once("niwoopo-setting.php");
				$obj = new  NiWooPO_Setting ($this->niwoopo_constant);
				$obj->page_ajax();			
			}
			if ($niwoopo_action  == "niwoopo_po_list"){
				
				include_once("niwoopo-po-list.php");
				$obj = new  NiWooPO_PO_List ($this->niwoopo_constant);
				$obj->page_ajax();			
			}
			if ($niwoopo_action == "niwoopo_stock_report"){
				include_once("niwoopo-stock-report.php");
				$obj = new  NiWooPO_Stock_Report ($this->niwoopo_constant);
				$obj->page_ajax();			
			}
			die;	
				
			/*
			if ($niwoopo_action  == "autocomplete_product"){
				
				include_once("niwoopo-manage-po.php");
				$obj = new  NiWooPO_Manage_PO ();
				$obj->page_ajax();		
			}elseif ($niwoopo_action =="add") {
				
				include_once("niwoopo-manage-po.php");
				$obj = new  NiWooPO_Manage_PO ();
				$obj->page_ajax();			
			}elseif ($niwoopo_action =="update") {
				
				include_once("niwoopo-manage-po.php");
				$obj = new  NiWooPO_Manage_PO ();
				$obj->page_ajax();			
			}else{
				include_once("niwoopo-manage-po.php");
				$obj = new  NiWooPO_Manage_PO ();
				$obj->page_ajax();			
					
			}*/
			die;
			
				
			
		}
		function admin_enqueue_scripts(){
				$page =	$this->get_request("page");
				$niwoopo_action  = $this->get_request('niwoopo_action'); 
				$po_show_product_image_column = $this->get_setting('po_show_product_image_column','no');
				
				$niwoopo_ajax_data=  array();
				
				if ($page == "niwoopo-manage-po" || $page =="niwoopo-setting" ||  $page =="niwoopo-dashboard" || $page  =="niwoopo-stock-report"){
					
					$niwoopo_ajax_data=  array();
					
					$niwooims_ajax_data['niwoopo_ajaxurl'] = admin_url('admin-ajax.php');
					$niwooims_ajax_data['niwoopo_admin_url']				= admin_url("admin.php");				
				    $niwooims_ajax_data['placeholder_autocomplete_product'] = __('Enter Product Name','niwooims_textdomain');
					$niwooims_ajax_data['placeholder_autocomplete_sku'] = __('Enter Product SKU','niwooims_textdomain');
					$niwooims_ajax_data['placeholder_product_notes'] = __('Product additional notes','niwooims_textdomain');
					$niwooims_ajax_data['purchase'] 						= __('Purchase','niwooims_textdomain');
					$niwooims_ajax_data['quantity'] 						= __('Quantity','niwooims_textdomain');
					$niwooims_ajax_data['price'] 							= __('Price','niwooims_textdomain');
					$niwooims_ajax_data['enter'] 							= __('Enter','niwooims_textdomain');
					$niwooims_ajax_data['please_wait'] 						= __('Please Wait!','niwooims_textdomain');
					$niwooims_ajax_data['product_name'] 					= __('Product','niwooims_textdomain');
					$niwooims_ajax_data['remove'] 					= __('Remove','niwooims_textdomain');
					
					$niwooims_ajax_data['po_show_product_image_column'] = $po_show_product_image_column;
					
					
					if ($page  =="niwoopo-manage-po" || $page   =="niwoopo-manage-po") {
						
						if ($niwoopo_action =="add" || $niwoopo_action =="edit"){
							wp_enqueue_script( 'niwoopo-po-script', plugins_url( '../admin/js/niwoopo-po.js', __FILE__ ), array('jquery') );	
						}else{
							wp_enqueue_script( 'niwoopo-po-script', plugins_url( '../admin/js/niwoopo-list.js', __FILE__ ), array('jquery') );	
						}
						
						
						wp_register_style('niwoopo-jquery-ui-css', plugins_url( '../admin/css/jquery-ui.css', __FILE__ ));
						wp_enqueue_style('niwoopo-jquery-ui-css' );
							
						
					 
						
					}
					if ($page  =="niwoopo-manage-po" || $page   =="niwoopo-manage-po" || $page   =="niwoopo-stock-report") {
					      wp_enqueue_script('jquery-ui-core');
						 wp_enqueue_script( 'jquery-ui-datepicker' );
						 wp_enqueue_script('jquery-ui-autocomplete');	
					}
					if ($page =="niwoopo-setting"){
						wp_enqueue_script( 'niwoopo-setting-script', plugins_url( '../admin/js/niwoopo-setting.js', __FILE__ ), array('jquery') );	
					}
					if ($page =="niwoopo-stock-report"){
						wp_enqueue_script( 'niwoopo-stock-report-script', plugins_url( '../admin/js/niwoopo-stock-report.js', __FILE__ ), array('jquery') );	
					}
					
					
					
					wp_register_style('niwoopo-fontawesome-style', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css' );
              		wp_enqueue_style('niwoopo-fontawesome-style');
					
					
					/*start bootstrap */
					wp_register_script('niwoopo-bootstrap-popper-script', plugins_url( '../admin/js/popper.min.js', __FILE__ ));
					wp_enqueue_script('niwoopo-bootstrap-popper-script');
					
					wp_register_script('niwoopo-bootstrap-script', plugins_url( '../admin/js/bootstrap.min.js', __FILE__ ));
					wp_enqueue_script('niwoopo-bootstrap-script');
						
					wp_register_style( 'niwoopo-bootstrap-popper-css', plugins_url( '../admin/css/bootstrap.min.css', __FILE__ ));
					wp_enqueue_style( 'niwoopo-bootstrap-popper-css' );
					
					/*end bootstrap */
										
					//wp_register_style('niwoopo-inventory-css', plugins_url( '../admin/css/niwooims-inventory.css', __FILE__ ));
					//wp_enqueue_style('niwoopo-inventory-css');
					
					wp_register_style('niwoopo-style-css', plugins_url( '../admin/css/ni-woopo.css', __FILE__ ));
					wp_enqueue_style('niwoopo-style-css');
					
					wp_enqueue_script( 'niwoopo-script', plugins_url( '../admin/js/script.js', __FILE__ ), array('jquery') );	
					wp_localize_script( 'niwoopo-script','niwoopo_ajax_object',$niwooims_ajax_data);
				}
				
		}
		function add_po_page(){
			
			$page 			 = $this->get_request('page');
			$niwoopo_action  = $this->get_request('niwoopo_action'); 
		    $po_id			 = $this->get_request('po_id',0); 
			
			if ($page == "niwoopo-dashboard"){
				include_once("niwoopo-dashboard.php");
				$obj = new  NiWooPO_Dashboard ();
				$obj->page_init();			
			}
			
			if ($page == "niwoopo-setting"){
				include_once("niwoopo-setting.php");
				$obj = new  NiWooPO_Setting ();
				$obj->page_init();			
			}/*
			if ($page == "niwoopo-manage-po"){
				include_once("niwoopo-manage-po.php");
				$obj = new  NiWooPO_Manage_PO ();
				$obj->page_init();			
			}*/
			if ($page == "niwoopo-manage-po"){
				if ($niwoopo_action =="add" || $niwoopo_action =="edit"){
					include_once("niwoopo-manage-po.php");
					$obj = new  NiWooPO_Manage_PO ($this->niwoopo_constant);
					$obj->page_init();	
				}else{
					include_once("niwoopo-po-list.php");
					$obj = new  NiWooPO_PO_List ();
					$obj->page_init();	
				}
			
			}
			if ($page == "niwoopo-stock-report"){
				include_once("niwoopo-stock-report.php");
				$obj = new  NiWooPO_Stock_Report ();
				$obj->page_init();			
			}
			
		}
	}
}
?>