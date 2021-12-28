<?php

// To Remove - Rename Default Sorting Options
function wc_customize_product_sorting($sorting_options){
    $sorting_options = array(
        //'popularity' => __( 'Popularity', 'woocommerce' ),
        //'rating'     => __( 'Rating', 'woocommerce' ),
        'date'       => __( 'Relevance - Recent Items First', 'woocommerce' ),
		'date-desc'  => __( 'Relevance - Oldest Items First', 'woocommerce' ),
		'price'      => __( 'Price - Low to High', 'woocommerce' ),
        'price-desc' => __( 'Price - High to Low', 'woocommerce' ),
    );
    return $sorting_options;
}
add_filter('woocommerce_catalog_orderby', 'wc_customize_product_sorting');

// WooCommerce - Change default catalog sort order
add_filter('woocommerce_default_catalog_orderby', 'custom_default_catalog_orderby');
function custom_default_catalog_orderby() {
     return 'price'; // Can also use popularity, date, title ect default optionds or any custom Order added by custom code 
}

// WooCommerce - Change default catalog sort order for Specific Category only 
add_filter('woocommerce_default_catalog_orderby', 'custom_default_catalog_orderby');
function custom_default_catalog_orderby() {
	if(is_product_category('category_slug or id')){ 
     	return 'price'; // Can also use popularity, date, title ect default optionds or any custom Order added by custom code 
	}
}



//Display Outofstock product at last and sorting Based on product menu order 
add_filter('woocommerce_get_catalog_ordering_args', 'bbloomer_sort_by_stock_status_then_alpha');
function bbloomer_sort_by_stock_status_then_alpha( $args ) {
    $args['meta_key'] = '_stock_status';
    $args['orderby'] = array( 'menu_order'=>'ASC', 'meta_value' => 'ASC', 'date' => 'DESC' ) ;

    return $args;
}


// Add custom sorting options random_list (Random) (asc/desc)   
// Ref.  https://woocommerce.com/document/custom-sorting-options-ascdesc/
add_filter( 'woocommerce_get_catalog_ordering_args', 'custom_woocommerce_get_catalog_ordering_args' );
function custom_woocommerce_get_catalog_ordering_args( $args ) {
	$orderby_value = isset( $_GET['orderby'] ) ? wc_clean( $_GET['orderby'] ) : apply_filters( 'woocommerce_default_catalog_orderby', get_option( 'woocommerce_default_catalog_orderby' ) );
	if ( 'random_list' == $orderby_value ) {
		$args['orderby'] = 'rand';
		$args['order'] = '';
		$args['meta_key'] = '';
	}
	return $args;
}

add_filter( 'woocommerce_default_catalog_orderby_options', 'custom_woocommerce_catalog_orderby' );
add_filter( 'woocommerce_catalog_orderby', 'custom_woocommerce_catalog_orderby' );
function custom_woocommerce_catalog_orderby( $sortby ) {
	$sortby['random_list'] = 'Random';
	return $sortby;
}


?>