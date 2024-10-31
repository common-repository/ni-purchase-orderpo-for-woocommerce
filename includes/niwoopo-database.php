<?php 
if ( !class_exists( 'NiWooPO_Database' ) ) {

	class NiWooPO_Database{
		var $niwoopo_constant = array();  
		function __construct($niwoopo_constant = array()){
			$this->niwoopo_constant = $niwoopo_constant;
		}
		function create_table(){
			global $wpdb;
			$niwoopo_header	= $wpdb->prefix.'niwoopo_header';
			$niwoopo_detail	= $wpdb->prefix.'niwoopo_detail';
			
			
			$charset_collate = $wpdb->get_charset_collate();
			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			if($wpdb->get_var("SHOW TABLES LIKE '$niwoopo_header'") != $niwoopo_header) {

				$sql = "CREATE TABLE IF NOT EXISTS `{$niwoopo_header}`  (
				  `po_id` bigint(20) NOT NULL AUTO_INCREMENT,
				  `po_date` date NOT NULL,
				  `po_no` varchar(100) NOT NULL,
				  `supplier_id` bigint(20) NOT NULL,
				  `location_id` bigint(20) NOT NULL,
				  `created_date` date NOT NULL,
				  `updated_date` date NOT NULL,
				  `created_user_id` bigint(20) NOT NULL,
				  `updated_user_id` bigint(20) NOT NULL,
				  `status_id` int(11) NOT NULL,
				  `notes` text NOT NULL,
				  PRIMARY KEY (`po_id`)
				) $charset_collate;";					
				dbDelta( $sql );
			}
			if($wpdb->get_var("SHOW TABLES LIKE '$niwoopo_detail'") != $niwoopo_detail) {

				$sql = "CREATE TABLE IF NOT EXISTS `{$niwoopo_detail}`  (
				  `po_detail_id` bigint(20) NOT NULL AUTO_INCREMENT,
				  `po_id` bigint(20) NOT NULL,
				  `product_id` bigint(20) NOT NULL,
				  `po_quantity` bigint(20) NOT NULL,
				  `po_received_quantity` bigint(20) NOT NULL,
				  `po_set_off_quantity` bigint(20) NOT NULL,
				  `po_balance_quantity` int(11) NOT NULL,
				  `purchase_price` decimal(10,0) NOT NULL,
				  `po_product_total` decimal(10,0) NOT NULL,
				  `currency_id` int(11) NOT NULL,
				  `uom_id` int(11) NOT NULL,
				  `product_note` text NOT NULL,
				  PRIMARY KEY (`po_detail_id`)
				) $charset_collate;";					
				dbDelta( $sql );
			}
		}
	}
}