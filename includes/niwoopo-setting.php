<?php 
if ( !class_exists( 'NiWooPO_Setting' ) ) {
	include_once("niwoopo-function.php"); 	
	class NiWooPO_Setting extends NiWooPO_Function{
		var $niwoopo_constant = array();  
		function __construct($niwoopo_constant = array()){
			$this->niwoopo_constant = $niwoopo_constant;
			
			$this->niwoopo_constant['directory_name'] = "ni-purchase-order";
			
		}
		function page_init(){
			$options = get_option('niwoopo_options');
			
			$shop_name = isset($options["shop_name"])?$options["shop_name"]:'';
			$shop_address = isset($options["shop_address"])?$options["shop_address"]:'';
			$shop_contact_no = isset($options["shop_contact_no"])?$options["shop_contact_no"]:'';
			$shop_contact_person = isset($options["shop_contact_person"])?$options["shop_contact_person"]:'';
			
			$shop_email_address = isset($options["shop_email_address"])?$options["shop_email_address"]:'';
			$billing_address = isset($options["billing_address"])?$options["billing_address"]:'';
			$shipping_address = isset($options["shipping_address"])?$options["shipping_address"]:'';
			$shop_logo = isset($options["shop_logo"])?$options["shop_logo"]:'';
			$shop_signature = isset($options["shop_signature"])?$options["shop_signature"]:'';
			$footer_notes = isset($options["footer_notes"])?$options["footer_notes"]:'';
			$term_condition = isset($options["term_condition"])?$options["term_condition"]:'';
			$user_role = isset($options["user_role"])?$options["user_role"]:'';
			
			$po_show_product_image_column = isset($options["po_show_product_image_column"])?$options["po_show_product_image_column"]:'';
			
			$role = $this->get_user_role();
			
			$uploads_dir = wp_upload_dir();			
			$icon_url = $uploads_dir['baseurl'] . '/'.$this->niwoopo_constant['directory_name'].'/';
			
		?>
        <div id="niwooims" class="ni-woopo-plug">
			<div class="ni-box ni-setting" style="max-width:800px">
				<div class="ni-box-header">
					<h4><?php esc_html_e("PO Settings","niwooims_textdomain"); ?></h4>
				</div>	
				<div class="ni-box-body">
					<form name="niwoopo_setting" id="niwoopo_setting" autocomplete="off">
						<div class="form-group row">
							<div class="col-sm-4 col-md-4 col-lg-4">
								<label class="col-form-label" for="shop_name"><?php esc_html_e("Shop Name","niwooims_textdomain"); ?></label>
							</div>
							<div class="col-sm-8 col-md-8 col-lg-8">
								<input type="text" class="form-control" name="shop_name" id="shop_name" value="<?php echo $shop_name; ?>" autocomplete="off" />
							</div>
							<div class="col-sm-4 col-md-4 col-lg-4">
								<label class="col-form-label" for="shop_address"><?php esc_html_e("Shop Address","niwooims_textdomain"); ?></label>
							</div>
							<div class="col-sm-8 col-md-8 col-lg-8">
								<textarea class="form-control" id="shop_address" name="shop_address" > <?php echo $shop_address ; ?> </textarea>
							</div>
							<div class="col-sm-4 col-md-4 col-lg-4">
								<label class="col-form-label" for="shop_contact_no"><?php esc_html_e("Shop Contact No","niwooims_textdomain"); ?></label>
							</div>
							<div class="col-sm-8 col-md-8 col-lg-8">
								<input type="text" class="form-control" name="shop_contact_no" id="shop_contact_no" value="<?php echo $shop_contact_no; ?>"   autocomplete="off" />
							</div>
							
							<div class="col-sm-4 col-md-4 col-lg-4">
								<label class="col-form-label" for="shop_email_address"><?php esc_html_e("Shop Email Address","niwooims_textdomain"); ?></label>
							</div>
							<div class="col-sm-8 col-md-8 col-lg-8">
								<input type="text" class="form-control" name="shop_email_address" id="shop_email_address" value="<?php echo $shop_email_address; ?>" autocomplete="off" />
							</div>
						 
						  	<div class="col-sm-4 col-md-4 col-lg-4">
								<label class="col-form-label" for="shop_contact_person"><?php esc_html_e("Shop Contact Person","niwooims_textdomain"); ?></label>
							</div>
							<div class="col-sm-8 col-md-8 col-lg-8">
								<input type="text" class="form-control" name="shop_contact_person" id="shop_contact_person" value="<?php echo $shop_contact_person; ?>" autocomplete="off" />
							</div>
						 
							<div class="col-sm-4 col-md-4 col-lg-4">
								<label class="col-form-label" for="billing_address"><?php _e("Billing Address","niwooims_textdomain"); ?></label>
							</div>
							<div class="col-sm-8 col-md-8 col-lg-8">
							  <textarea class="form-control" id="billing_address" name="billing_address"><?php echo $billing_address ; ?> </textarea>
							</div>
							
							<div class="col-sm-4 col-md-4 col-lg-4">
							   <label class="col-form-label" for="shipping_address"><?php esc_html_e("Shipping Address","niwooims_textdomain"); ?></label>
							</div>
							<div class="col-sm-8 col-md-8 col-lg-8">
							   <textarea class="form-control" id="shipping_address" name="shipping_address"><?php echo $shipping_address ; ?> </textarea>
							</div>
						
							<div class="col-sm-4 col-md-4 col-lg-4">
								<label class="col-form-label" for="_shop_logo"><?php esc_html_e("Logo","niwooims_textdomain"); ?></label>
							</div>
                            
							<div class="col-sm-8 col-md-8 col-lg-8">
							   <input type="file" class="form-control-file upload_image" data-field="shop_logo" id="_shop_logo" name="_shop_logo" accept="image/jpg,image/png,image/jpeg">
							   <input type="hidden" class="form-control-file" id="shop_logo" name="shop_logo" value="<?php echo $shop_logo; ?>">
							   <?php if($shop_logo):?>
							   <img src="<?php echo $icon_url.$shop_logo; ?>" class="uploaded_image shop_logo_image" height="100" />
							   <?php endif;?>
							</div>
							<div class="col-sm-4 col-md-4 col-lg-4">
								<label class="col-form-label" for="_shop_signature"><?php esc_html_e("Signature","niwooims_textdomain"); ?></label>
							</div>
							<div class="col-sm-8 col-md-8 col-lg-8">
								 <input type="file" class="form-control-file upload_image" data-field="shop_signature" id="_shop_signature" name="_shop_signature" accept="image/jpg,image/png,image/jpeg">
								 <input type="hidden" class="form-control-file" id="shop_signature" name="shop_signature" value="<?php echo $shop_signature; ?>">
								 <?php if($shop_logo):?>
								   <img src="<?php echo $icon_url.$shop_signature; ?>" class="uploaded_image shop_signature_image" height="100"  />
								   <?php endif;?>
							</div>
						
							<div class="col-sm-4 col-md-4 col-lg-4">
								<label class="col-form-label" for="footer_notes"><?php esc_html_e("Note to recipient","niwooims_textdomain"); ?></label>
							</div>
							<div class="col-sm-8 col-md-8 col-lg-8">
							   <textarea class="form-control" id="footer_notes" name="footer_notes" placeholder="For example, “Thank you for your business”"><?php echo $footer_notes ; ?></textarea>
							</div>
							
							<div class="col-sm-4 col-md-4 col-lg-4">
								<label class="col-form-label" for="term_condition"><?php esc_html_e("Terms and Conditions","niwooims_textdomain"); ?></label>
							</div>
							<div class="col-sm-8 col-md-8 col-lg-8">
							   <textarea class="form-control" id="term_condition" name="term_condition" placeholder="Include your return or cancellation policy" ><?php echo $term_condition ; ?></textarea>
							</div>
							
							<div class="col-sm-12 col-md-12 col-lg-12">
							<hr class="dashed">
							</div>
							
						  	<div class="col-sm-4 col-md-4 col-lg-4">
								<label class="col-form-label" for="shop_contact_person"><?php esc_html_e("Select Supplier Role","niwooims_textdomain"); ?></label>
							</div>
							<div class="col-sm-8 col-md-8 col-lg-8">
								<select name="user_role" id="user_role">	
									<?php foreach($role as $key=>$value): ?>
									<option value="<?php echo $key; ?>"    <?php if($key==$user_role) echo 'selected="selected"'; ?> ><?php echo $value; ?></option>
									<?php endforeach; ?>
								</select>
							</div>
                            
                            <div class="col-sm-12 col-md-12 col-lg-12">
								<hr class="dashed">
							</div>
                            
                            <div class="col-sm-4 col-md-4 col-lg-4">
								<label class="col-form-label" for="po_show_product_image_columns"><?php esc_html_e("PO show product image column","niwooims_textdomain"); ?></label>
							</div>
							<div class="col-sm-8 col-md-8 col-lg-8">
							   <input type="checkbox" id="po_show_product_image_column" name="po_show_product_image_column" <?php echo ($po_show_product_image_column =="yes")?"checked":""; ?>  />
							</div>
                            
                            
						</div>
						 
						<div class="row">
							<div class="col-sm-8">
								<div class="alert alert-success" style="display:none">
									<p class="please_wait"></p>
								</div>
							</div>
							<div class="col-sm-4 text-right">
								<input type="submit" value="<?php _e('Save Setting','niwooims_textdomain'); ?>" class="ni_button_form" />				   
							</div>
						</div>
						<input type="hidden" name="action" id="action" value="niwoopo_ajax" />
						<input type="hidden" name="niwoopo_action" value="niwoopo_setting" />
						<input type="hidden" name="niwoopo_sub_action" value="niwoopo_setting_options" />
					 </form>
				</div>
			</div>
		</div>
        <?php 
		}
		function page_ajax(){
			$niwoopo_sub_action	= sanitize_text_field(isset($_REQUEST["niwoopo_sub_action"])?$_REQUEST["niwoopo_sub_action"]: '');
			if($niwoopo_sub_action == 'niwoopo_setting_options'){
				$this->save_setting();
			}
			
			if($niwoopo_sub_action == 'niwoopo_setting_images'){
				$this->niwoopo_setting_images();
			}			
		}
		
		function save_setting(){
			$niwoopo_options = array();
			$niwoopo_options["shop_name"] 			= sanitize_text_field(isset($_REQUEST["shop_name"])?$_REQUEST["shop_name"]: '');
			$niwoopo_options["shop_address"] 		= sanitize_textarea_field(isset($_REQUEST["shop_address"])?$_REQUEST["shop_address"]: '');
			$niwoopo_options["shop_contact_no"] 	= sanitize_text_field(isset($_REQUEST["shop_contact_no"])?$_REQUEST["shop_contact_no"]: '');
			$niwoopo_options["shop_contact_person"] = sanitize_text_field(isset($_REQUEST["shop_contact_person"])?$_REQUEST["shop_contact_person"]: '');
			$niwoopo_options["shop_email_address"] 	= sanitize_text_field(isset($_REQUEST["shop_email_address"])?$_REQUEST["shop_email_address"]: '');
			$niwoopo_options["billing_address"] 	= sanitize_textarea_field(isset($_REQUEST["billing_address"])?$_REQUEST["billing_address"]: '');
			$niwoopo_options["shipping_address"] 	= sanitize_textarea_field(isset($_REQUEST["shipping_address"])?$_REQUEST["shipping_address"]: '');
			$niwoopo_options["shop_logo"] 			= sanitize_text_field(isset($_REQUEST["shop_logo"])?$_REQUEST["shop_logo"]: '');
			$niwoopo_options["shop_signature"] 		= sanitize_text_field(isset($_REQUEST["shop_signature"])?$_REQUEST["shop_signature"]: '');
			$niwoopo_options["footer_notes"] 		= sanitize_text_field(isset($_REQUEST["footer_notes"])?$_REQUEST["footer_notes"]: '');
			$niwoopo_options["term_condition"] 		= sanitize_text_field(esc_html(isset($_REQUEST["term_condition"])?$_REQUEST["term_condition"]: ''));
			$niwoopo_options["user_role"] 			= sanitize_text_field(isset($_REQUEST["user_role"])?$_REQUEST["user_role"]: '');
			
			if (isset($_REQUEST["po_show_product_image_column"])){
				$po_show_product_image_column = "yes";		
			}else{
				$po_show_product_image_column = "no";	
			}
			$niwoopo_options["po_show_product_image_column"] 			= sanitize_text_field($po_show_product_image_column);
			
			update_option("niwoopo_options",$niwoopo_options);
			echo "Setting saved.";
		}
		
		function custome_upload_dir($upload){
			$upload['path']   = $upload['basedir'] . '/'.$this->niwoopo_constant['directory_name'];
			$upload['url']    = $upload['baseurl'] . '/'.$this->niwoopo_constant['directory_name'];
			return $upload;
		}
		
		function niwoopo_setting_images(){			
			$message = "uploaded";
			$file_name = "";
			$valid = true;
			$moved_file = array();
			$return = array();
			$return['file_name'] = '';
			$return['upload_image_url'] = '';
			$return['upload_image_name'] = '';
			$return['error_message'] = '';
			$return['error_number'] = '';
			
			if(!function_exists('wp_handle_upload')){
				require_once(ABSPATH.'wp-admin/includes/file.php');
			}
			
			// for multiple file upload.
			$upload_overrides = array( 'test_form' => false );
			$uploaded_file = isset($_FILES['upload_image']) ? $_FILES['upload_image'] : array();
			$error = isset($uploaded_file['error']) ? $uploaded_file['error'] : 0;
			$type = isset($uploaded_file['type']) ? $uploaded_file['type'] : '';
			
			if($valid == true && !is_array($uploaded_file)){
				$return['error_message'] = esc_html__('Uploaded image not valid.','niwooims_textdomain');
				$valid = false;
			}
			
			if($valid == true && !in_array($type, array('image/png','image/jpeg','image/jpg'))){
				$return['error_message'] = esc_html__('Uploaded image file type not supported.','niwooims_textdomain');
				$valid = false;
			}
			
			if($valid){
				add_filter('upload_dir', array($this,'custome_upload_dir'),9);
				$moved_file = wp_handle_upload($uploaded_file,$upload_overrides);
				remove_filter('upload_dir', array($this,'custome_upload_dir'),9);
				
				if(isset($moved_file['file'])){
					$file_name = basename($moved_file['file']);
				}
				
				$return['file_name'] = $file_name;
				$return['upload_image_url'] = isset($moved_file['url']) ? $moved_file['url'] : '';
				$return['upload_image_name'] = $file_name;
			}
			
			$return['image_type'] = $type;
			$return['error_number'] = $error;
			
			echo json_encode($return);
			die;
		}
		
	}
}