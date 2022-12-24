<?
add_filter( 'manage_edit-product_columns', 'ls_product_weight_column', 20 );
function ls_product_weight_column( $columns ) {
	 $new_columns = array();
    foreach( $columns as $key => $column ){
        $new_columns[$key] =  $columns[$key];
        if( $key === 'price' )
    	$new_columns['total_weight'] = esc_html__( 'Weight', 'woocommerce' );
	}
        return $new_columns;

}
// Populate weight column
add_action( 'manage_product_posts_custom_column', 'ls_product_weight_column_data', 10, 2 );
function ls_product_weight_column_data( $column ) {
    global $post;

    if ( $column == 'total_weight' ) {
        $product = wc_get_product($post->ID);
                $weight = $product->get_weight();
        if ( $weight > 0 )
            print $weight . ' ' . esc_attr( get_option('woocommerce_weight_unit' ) );
        else print 'N/A';
    }
}
add_action('admin_head', 'my_column_width');

function my_column_width() {
    echo '<style type="text/css">';
    echo 'table.wp-list-table .column-total_weight { width: 46px; text-align: left!important;padding: 5px;}';
    echo 'table.wp-list-table .column-is_Packed { width: 46px; text-align: left!important;padding: 5px;}';
    echo '</style>';
}


add_filter( 'manage_edit-product_columns', 'ls_product_shipping_column', 20 );
function ls_product_shipping_column( $columns ) {
	 $new_columns = array();
    foreach( $columns as $key => $column ){
        $new_columns[$key] =  $columns[$key];
        if( $key === 'total_weight' )
    	$new_columns['is_Packed'] = esc_html__( 'Is Packed?', 'woocommerce' );
	}
        return $new_columns;

}
// Populate shipping column
add_action( 'manage_product_posts_custom_column', 'ls_product_shipping_column_data', 10, 2 );
function ls_product_shipping_column_data( $column ) {
    global $post;
   
   if ( $column == 'is_Packed' ) {
     global $product;
    $Shipping = get_post_meta($post->ID, 'ls_product_type', true);
    if( $Shipping == 0) print 'No';
    else print 'Yes';
    }
}