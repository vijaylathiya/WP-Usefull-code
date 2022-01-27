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