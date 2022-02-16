<?php
//add custom package option at shipping tab in product
add_action( 'woocommerce_product_options_shipping', 'ls_shipping_option_to_products');
function ls_shipping_option_to_products(){
	$args = array(
		'id' => 'ls_product_type', // required. The meta_key ID for the stored value
		'wrapper_class' => 'ls_product_type', // a custom wrapper class if needed
		'desc_tip' => true, // makes your description show up with a "?" symbol and as a tooltip
		'description' => 'Select "YES" if product type is package.',
		'label' => 'Is Product Package?',
		'options' => array(
		  '0' => 'No',
		  '1' => 'Yes'
		)
	  );
	  woocommerce_wp_select( $args );
}
add_action('woocommerce_process_product_meta','wdo_save_product_meta_fields', 10, 2 ); 
function wdo_save_product_meta_fields( $post_id, $post ){
	//$product = wc_get_product($post_id);
	$num_package = isset($_POST['ls_product_type']) ? $_POST['ls_product_type'] : '0';
	//$product->update_meta_data('ls_product_type', sanitize_text_field($num_package));
	//$product->save();
	update_post_meta( $post_id, 'ls_product_type', wc_clean($num_package));
	// If site using WPML for Multi Languages
	if ( defined( 'ICL_LANGUAGE_CODE' ) ) {
		$ls_clang =  ICL_LANGUAGE_CODE;
		if($ls_clang=='en')
		{
			$pid_ar = apply_filters( 'wpml_object_id', $post_id, 'product',true,'ar' );
			update_post_meta( $pid_ar, 'ls_product_type', wc_clean( $num_package ) );
		}
		if($ls_clang=='ar')
		{
			$pid_en = apply_filters( 'wpml_object_id', $post_id, 'product',true,'en' );
			update_post_meta( $pid_en, 'ls_product_type', wc_clean( $num_package ) );
		}
	}
}



add_action( 'woocommerce_product_quick_edit_end', function(){
	?><div style="clear:both"></div>
    <div class="custom_field_demo">
        <label class="alignleft">
            <div class="title"><?php _e('Is Product Package?', 'woocommerce' ); ?></div>
            <select name="ls_product_type" class="text">
            	<option value="0">No</option>
                <option value="1">Yes</option>
            </select>
        </label>
    </div>
    <?php
});

// Product Quick Edit 
add_action('woocommerce_product_quick_edit_save', function($product){
	if ( $product->is_type('simple')) {
		$post_id = $product->id;
		if ( isset( $_REQUEST['ls_product_type'] ) ) {
			$customFieldDemo = trim(esc_attr( $_REQUEST['ls_product_type'] ));
			update_post_meta( $post_id, 'ls_product_type', wc_clean( $customFieldDemo ) );
		}
	}
}, 10, 1);


add_action( 'manage_product_posts_custom_column', function($column,$post_id){
	switch ( $column ) {
		case 'name' :
	
			?>
			<div class="hidden custom_field_demo_inline" id="custom_field_demo_inline_<?php echo $post_id; ?>">
				<div id="ls_product_type"><?php echo get_post_meta($post_id,'ls_product_type',true); ?></div>
			</div>
			<?php
	
			break;
	
		default :
			break;
	}
}, 99, 2);

add_action('admin_footer','fn_product_quick_edit');
function fn_product_quick_edit(){ ?>
<script type="text/javascript">jQuery(function(){
	jQuery('#the-list').on('click', '.editinline', function(){
		inlineEditPost.revert();
		var post_id = jQuery(this).closest('tr').attr('id');
		post_id = post_id.replace("post-", "");
	
		var $cfd_inline_data = jQuery('#custom_field_demo_inline_' + post_id),
			$wc_inline_data = jQuery('#woocommerce_inline_' + post_id );
		//console.log("aa"+$cfd_inline_data.find("#ls_product_type").text());
	   // jQuery('input[name="ls_product_type"]', '.inline-edit-row').val($cfd_inline_data.find("#ls_product_type").text());
	   jQuery('select[name^="ls_product_type"] option:selected', '.inline-edit-row').attr("selected",null);
		jQuery('select[name^="ls_product_type"] option[value="'+$cfd_inline_data.find("#ls_product_type").text()+'"]', '.inline-edit-row').attr("selected","selected");
	
		var product_type = $wc_inline_data.find('.product_type').text();
		if (product_type=='simple' || product_type=='external') {
			jQuery('.custom_field_demo', '.inline-edit-row').show();
		} else {
			jQuery('.custom_field_demo', '.inline-edit-row').hide();
		}
	});
});</script>
<?php  } 


// For WooCommerce Product Bulk EDIT 
add_action( 'woocommerce_product_bulk_edit_start', 'wdo_wc_product_custom_field_bulk_edit' );
function wdo_wc_product_custom_field_bulk_edit() {
    ?>
    <div class="inline-edit-group">
      <label class="alignleft">
         <span class="title"><?php _e( 'Is Product Package?', 'woocommerce' ); ?></span>
         <span class="input-text-wrap">
            <select name="ls_product_type" class="text">
            	<option value="0">No</option>
                <option value="1">Yes</option>
            </select>
         </span>
        </label>
    </div>
    <?php
}
 add_action( 'woocommerce_product_bulk_edit_save', 'wdo_wc_product_custom_field_bulk_edit_save' );
function wdo_wc_product_custom_field_bulk_edit_save( $product ) {
    $post_id = $product->get_id();    
   	if ( isset( $_REQUEST['ls_product_type'] ) ) {
        $ls_product_type = $_REQUEST['ls_product_type'];
        update_post_meta( $post_id, 'ls_product_type', wc_clean( $ls_product_type ) );
		if ( defined( 'ICL_LANGUAGE_CODE' ) ) 
		{
  				$ls_clang =  ICL_LANGUAGE_CODE;
				if($ls_clang=='en')
				{
					$pid_ar = apply_filters( 'wpml_object_id', $post_id, 'product',true,'ar' );
					update_post_meta( $pid_ar, 'ls_product_type', wc_clean( $ls_product_type ) );
				}
				if($ls_clang=='ar')
				{
					$pid_en = apply_filters( 'wpml_object_id', $post_id, 'product',true,'en' );
					update_post_meta( $pid_en, 'ls_product_type', wc_clean( $ls_product_type ) );
				}
		}
    }
}


// Other use Full Code for Options inside Different TAB
add_action( 'woocommerce_product_options_advanced', 'wdo_wc_options_inside_adv_setting_tab');
function wdo_wc_options_inside_adv_setting_tab(){
 	echo '<div class="options_group">';
 	woocommerce_wp_checkbox( array(
		'id'      => 'super_product',
		'value'   => get_post_meta( get_the_ID(), 'super_product', true ),
		'label'   => 'This is a super product',
		'desc_tip' => true,
		'description' => 'If it is not a regular WooCommerce product',
	) );
 	echo '</div>';
}
 
add_action( 'woocommerce_process_product_meta', 'wdo_save_custom_fields', 10, 2 );
function wdo_save_custom_fields( $id, $post ){
 	update_post_meta( $id, 'super_product', $_POST['super_product'] );
}

// Add New Tab inside WooCommerce Product Edit Screen Setting <br />
add_filter('woocommerce_product_data_tabs', 'wdo_wc_product_settings_tabs' );
function wdo_wc_product_settings_tabs( $tabs ){
 
	//unset( $tabs['inventory'] );
 
	$tabs['wdo'] = array(
		'label'    => 'WDO TAB',
		'target'   => 'wdo_product_data',
		'class'    => array('show_if_virtual'),
		'priority' => 21,
	);
	return $tabs;
 
}
 
/*
 * Tab content
 */
add_action( 'woocommerce_product_data_panels', 'wdo_wc_new_product_tab' );
function wdo_wc_new_product_tab(){
 
	echo '<div id="wdo_product_data" class="panel woocommerce_options_panel hidden">';
 
	woocommerce_wp_text_input( array(
		'id'                => 'wdo_plugin_version',
		'value'             => get_post_meta( get_the_ID(), 'wdo_plugin_version', true ),
		'label'             => 'Plugin version',
		'description'       => 'Description when desc_tip param is not true'
	) );
 
	woocommerce_wp_textarea_input( array(
		'id'          => 'wdo_changelog',
		'value'       => get_post_meta( get_the_ID(), 'wdo_changelog', true ),
		'label'       => 'Changelog',
		'desc_tip'    => true,
		'description' => 'Prove the plugin changelog here',
	) );
 
	woocommerce_wp_select( array(
		'id'          => 'wdo_ext',
		'value'       => get_post_meta( get_the_ID(), 'wdo_ext', true ),
		'wrapper_class' => 'show_if_downloadable',
		'label'       => 'File extension',
		'options'     => array( '' => 'Please select', 'zip' => 'Zip', 'gzip' => 'Gzip'),
	) );
 
	echo '</div>';
 
}
 
add_action('woocommerce_process_product_meta', 'similar code of above done for save custom field.... 