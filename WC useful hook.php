<?php // WooCommerce Default Quantity value in 
add_filter( 'woocommerce_quantity_input_args', 'ls_woocommerce_quantity_input_args', 10, 2 );
function ls_woocommerce_quantity_input_args( $args, $product ){
	$productID = $product->id;
	if( $product->is_type( 'simple' ) && get_post_meta($productID, 'q_btn_type', true)=='1' ){
		//$args['input_value'] = 5;
		//$args['min_value'] = 5;
	    //$args['step'] = 5; // Increment/decrement by this value (default = 1)	
		foreach( WC()->cart->get_cart() as $key => $item ){
			if( $item['product_id'] == $productID ){    
				$args['input_value'] = $item['quantity'];   
				return $args;       
			}
		} 
		$args['input_value'] = 5;
		return $args;
	}
	return $args;
}
?>