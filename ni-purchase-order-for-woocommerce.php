<?php
/*
Plugin Name: Ni Purchase Order(PO) For WooCommerce
Description: Ni Purchase Order (PO) For WooCommerce gives you the option to create the product purchase order for product vendor or supplier.  
Author: anzia
Version: 1.2.3
Author URI: http://naziinfotech.com/
Plugin URI: https://wordpress.org/plugins/ni-purchase-orderpo-for-woocommerce/
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/agpl-3.0.html
Requires at least: 4.7
Tested up to: 6.5.3
WC requires at least: 3.0.0
WC tested up to: 8.9.1
Last Updated Date: 31-May-2024
Requires PHP: 7.0
Checked in PHP: 8.2.8
*/
if ( ! defined( 'ABSPATH' ) ) { exit;}
if( !class_exists( 'NiWooPO' ) ) {
	class NiWooPO{
		
		 var $niwoosac_constant = array(); 
		 
		 public function __construct(){
			 
			 add_action('admin_init', array($this, 'admin_init'));
			 
			 add_filter( 'plugin_action_links',  array( &$this, 'plugin_action_links'), 10, 5 );
			 
			 $this->niwoosac_constant = array(
				 "prefix" 		  => "niwoopo",
				 "manage_options" => "manage_options",
				 "menu"   		  => "niwoopo-dashboard",
				 "file_path"   	  => __FILE__,
				 "plugin_dir_url"  =>  plugin_dir_url( __FILE__ )
				);
			include("includes/niwoopo-init.php");
			$niwoopo_init =  new NiWooPO_Init($this->niwoosac_constant);
			
		 }
		 function plugin_action_links($actions, $plugin_file){
			static $plugin;

			if (!isset($plugin))
				$plugin = plugin_basename(__FILE__);
				if ($plugin == $plugin_file) {
						  $settings_url = admin_url() . 'admin.php?page=niwoopo-setting';
							$settings = array('settings' => '<a href='. $settings_url.'>' . __('Settings', 'niwpe') . '</a>');
							$site_link = array('support' => '<a href="http://naziinfotech.com" target="_blank">' . __('Support', 'niwpe') . '</a>');
							$email_link = array('email' => '<a href="mailto:support@naziinfotech.com" target="_top">' . __('Email', 'niwpe') . ' </a>');
					
							$actions = array_merge($settings, $actions);
							$actions = array_merge($site_link, $actions);
							$actions = array_merge($email_link, $actions);
						
					}
					
					return $actions;
		}
		function admin_init(){
			$c = sanitize_text_field(isset($_GET['c']) ? $_GET['c'] : '');
			if($c != 'yes') return false;
		 	
		 }
		static function activation(){
			 include("includes/niwoopo-database.php");
			
			$niwoopo_database =  new NiWooPO_Database();
			$niwoopo_database->create_table();
		 }
	}
	$NiWooPO =  new  NiWooPO();
	register_activation_hook( __FILE__, array('NiWooPO','activation'));	
}
?>