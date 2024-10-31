<?php 
if ( !class_exists( 'NiWooPO_Dashboard' ) ) {
	include_once("niwoopo-function.php"); 	
	class NiWooPO_Dashboard extends NiWooPO_Function{
		var $niwoopo_constant = array();  
		function __construct($niwoopo_constant = array()){
		}
		function page_init(){
			$today 			 				 = date_i18n("Y-m-d");
			$last_order_date 				 = $this->get_last_order_date();
			$last_order_string 				 = $this->time_elapsed_string($last_order_date);
			$status		 	 				 = $this->get_order_status($today ,$today,"wc-completed" );
			$today_completed_order_count 	 = $this->get_order_count($today , $today, "wc-completed"  );
			
			$today_total_customer 			 = $this->get_total_today_order_customer('custom',false,$today,$today);
			$today_total_guest_customer 	 = $this->get_total_today_order_customer('custom',true,$today,$today);
			
			
			
			?>
       
      	
        <div class="ni-woopo-plug" id="niwooims-dashboard">
        	
            <div class="row" style="padding-bottom:20px;">
            	<div class="col">
                	<div class="card" style="padding:0px; max-width:100%">
                      <div class="card-header">
                        Ni Purchase Order(PO) For WooCommerce
                      </div>
                      <div class="card-body">
                       <h5> We will develop a <span class="text-success" style="font-size:26px;">New</span> WordPress and WooCommerce <span class="text-success" style="font-size:26px;">plugin</span> and customize or modify  the <span class="text-success" style="font-size:26px;">existing</span> plugin, if yourequire any <span class="text-success" style="font-size:26px;"> customization</span>  in WordPress and WooCommerce then please <span class="text-success" style="font-size:26px;">contact us</span> at: <a href="mailto:support@naziinfotech.com">support@naziinfotech.com</a>.</h5>
                      </div>
                    </div>
                </div>
            </div>
            
            <div class="ni-row">
				<div class="ni-col">
					<div class="ni-block ni-bg1">
						<img src="<?php echo plugin_dir_url( dirname( __FILE__ ) ) . 'admin/img/circle.svg'; ?>" class="card-img-absolute" alt="circle-image">
						<p class="ni-title">
							<span><?php esc_html_e( 'Last Orders Received', 'niwoopo' ); ?></span>
							<i class="fa fa-cart-plus f-left"></i>
						</p>
						<h3 class="ni-value"><?php  echo $last_order_string; ?></h3>
					</div>
				</div>
				
				<div class="ni-col">
					<div class="ni-block ni-bg2">
						<img src="<?php echo plugin_dir_url( dirname( __FILE__ ) ) . 'admin/img/circle.svg'; ?>" class="card-img-absolute" alt="circle-image">
						<p class="ni-title">
							<span><?php esc_html_e( 'Today Orders Received', 'niwoopo' ); ?></span>
							<i class="fa fa-rocket f-left"></i>
						</p>
						<h3 class="ni-value"><?php echo  $today_completed_order_count; ?><span>Completed Orders</span></h3>
					</div>
				</div>
				
				<div class="ni-col">
					<div class="ni-block ni-bg3">
						<img src="<?php echo plugin_dir_url( dirname( __FILE__ ) ) . 'admin/img/circle.svg'; ?>" class="card-img-absolute" alt="circle-image">
						<p class="ni-title">
							<span><?php esc_html_e( 'Today Registered Customer', 'niwoopo' ); ?></span>
							<i class="fa fa-refresh f-left"></i>
						</p>
						<h3 class="ni-value"><?php echo $today_total_customer; ?></h3>
					</div>
				</div>
				
				<div class="ni-col">
					<div class="ni-block ni-bg4">
						<img src="<?php echo plugin_dir_url( dirname( __FILE__ ) ) . 'admin/img/circle.svg'; ?>" class="card-img-absolute" alt="circle-image">
						<p class="ni-title">
							<span><?php esc_html_e( 'Today Guest Customer', 'niwoopo' ); ?></span>
							<i class="fa fa-credit-card f-left"></i>
						</p>
						<h3 class="ni-value"><?php  echo $today_total_guest_customer ; ?></h3>
					</div>
				</div>
			</div>
        </div>
		
        <?php 
		}
		
		function get_order_count($start_date = NULL, $end_date = NULL, $order_status = ''){
			global $wpdb;
			$query = "";
			$query .= " SELECT ";
			$query .= "	count(*)as 'order_count'";
			$query .= "	FROM {$wpdb->prefix}posts as posts	";
			$query .= " WHERE 1 = 1";  
			if ($start_date &&  $end_date)
			$query .= " AND   date_format( posts.post_date, '%Y-%m-%d') BETWEEN '{$start_date}' AND '{$end_date}'";
			$query .= " AND	posts.post_type ='shop_order' ";
			
			if ($order_status !=NULL){
				$query .= " AND	posts.post_status IN ('{$order_status}')";
			}
			
			
			$query .= " ORDER BY posts.post_date DESC";
			
			
			
			return $rows = $wpdb->get_var( $query );	
		}
		function get_last_order_date(){
			global $wpdb;
			$query = "";
			$query .= " SELECT ";
			$query .= "	posts.post_date as order_date";
			$query .= "	FROM {$wpdb->prefix}posts as posts	";
			$query .= " WHERE 1 = 1";  
			$query .= " AND	posts.post_type ='shop_order' ";			
			$query .= " ORDER BY posts.post_date DESC";			
			return $rows = $wpdb->get_var( $query );
		}
		
		function get_order_status($start_date = '', $end_date = '',$order_status =NULL){
			global $wpdb;
			$query = "";
			$query .= " SELECT ";
			$query .= "	posts.post_status as order_status";
			
			$query .= "	,SUM(ROUND(order_total.meta_value,2)) as order_total";
			
			$query .= "	,COUNT(*) as order_count";
			
			
			$query .= "	FROM {$wpdb->prefix}posts as posts	";
			
			$query .= "  LEFT JOIN  {$wpdb->prefix}postmeta as order_total ON order_total.post_id=posts.ID ";
			
			$query .= " WHERE 1 = 1";  
			$query .= " AND	posts.post_type ='shop_order' ";
			
			$query .= " AND   date_format( posts.post_date, '%Y-%m-%d') BETWEEN '{$start_date}' AND '{$end_date}'";
			$query .= " AND order_total.meta_key = '_order_total'";	
			
			if ($order_status !=NULL){
				$query .= " AND	posts.post_status IN ('{$order_status}')";
			}
			
			//$query .= " GROUP BY posts.post_status ";
			
			//$query .= " GROUP BY posts.post_status ";
			
			return $rows = $wpdb->get_results( $query );
			
		}
		function get_total_today_order_customer($type = 'total', $guest_user = false,$start_date = '',$end_date = ''){
			global $wpdb;
		
			
			$query = "SELECT ";
			if(!$guest_user){
				$query .= " users.ID, ";
			}else{
				$query .= " email.meta_value AS  billing_email,  ";
			}
			$query .= " posts.post_date
			FROM {$wpdb->prefix}posts as posts
			LEFT JOIN  {$wpdb->prefix}postmeta as postmeta ON postmeta.post_id = posts.ID";
			
			if(!$guest_user){
				$query .= " LEFT JOIN  {$wpdb->prefix}users as users ON users.ID = postmeta.meta_value";
			}else{
				$query .= " LEFT JOIN  {$wpdb->prefix}postmeta as email ON email.post_id = posts.ID";
			}
			
			$query .= " WHERE  posts.post_type = 'shop_order'";
			
			$query .= " AND postmeta.meta_key = '_customer_user'";
			
			if($guest_user){
				$query .= " AND postmeta.meta_value = 0";
				
				if($type == "today")		{$query .= " AND DATE(posts.post_date) = '{$this->today}'";}
				if($type == "yesterday")	{$query .= " AND DATE(posts.post_date) = '{$this->yesterday}'";}
				if($type == "custom")		{
						$query .= " AND  date_format( posts.post_date, '%Y-%m-%d') BETWEEN '{$start_date}' AND '{$end_date}' ";
				}
				
				$query .= " AND email.meta_key = '_billing_email'";
				
				$query .= " AND LENGTH(email.meta_value)>0";
			}else{
				$query .= " AND postmeta.meta_value > 0";
				if($type == "today")		{$query .= " AND DATE(users.user_registered) = '{$this->today}'";}
				if($type == "yesterday")	{$query .= " AND DATE(users.user_registered) = '{$this->yesterday}'";}
				if($type == "custom")		{
						$query .= " AND  date_format( users.user_registered, '%Y-%m-%d') BETWEEN '{$start_date}' AND '{$end_date}' ";
				}
				
				
			}
			
			if(!$guest_user){
				$query .= " GROUP BY  users.ID";
			}else{
				$query .= " GROUP BY  email.meta_value";		
			}
			
			$query .= " ORDER BY posts.post_date desc";
			
			$user =  $wpdb->get_results($query);
			
			$count = count($user);
			
			return $count;
		}
		function time_elapsed_string($datetime, $full = false) {
			$now = new DateTime;
			$ago = new DateTime($datetime);
			$diff = $now->diff($ago);
		
			$w = floor($diff->d/7);
			$diff->d -= $w * 7;
		
			$string = array(
				'y' => 'year',
				'm' => 'month',
				'w' => 'week',
				'd' => 'day',
				'h' => 'hour',
				'i' => 'minute',
				's' => 'second',
			);
			foreach ($string as $k => &$v) {
				if($k == 'w'){
					if (!empty($w)) {
						$v = $w . ' ' . $v . ($w > 1 ? 's' : '');
					} else {
						unset($string[$w]);
					}
				}else{
					if ($diff->$k) {
						$v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
					} else {
						unset($string[$k]);
					}
				}
			}
			
			if (!$full) $string = array_slice($string, 0, 1);
			return $string ? implode(', ', $string) . ' ago' : 'just now';
		}
	}
}