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
add_action('woocommerce_process_product_meta', function($post_id) {
	$product = wc_get_product($post_id);
	$num_package = isset($_POST['ls_product_type']) ? $_POST['ls_product_type'] : '0';
	$product->update_meta_data('ls_product_type', sanitize_text_field($num_package));
	$product->save();
});



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
<?php  } ?>