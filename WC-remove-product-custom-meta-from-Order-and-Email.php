<?php

add_filter('woocommerce_order_item_get_formatted_meta_data', 'wdo_unset_specific_order_item_meta_attr_mail', 10, 2);
function wdo_unset_specific_order_item_meta_attr_mail($formatted_meta, $item){
    //if( is_admin() || is_wc_endpoint_url() )  return $formatted_meta;

    foreach( $formatted_meta as $key => $meta ){
        if( in_array( $meta->key, array('a','b','c','description','cat','type','ref-part') ) )//ark-part-number
            unset($formatted_meta[$key]);
    }
    return $formatted_meta;
}


//OR 
add_filter('woocommerce_order_item_get_formatted_meta_data', 'wdo_unset_specific_order_item_meta_attr_mail', 10, 2);
function wdo_unset_specific_order_item_meta_attr_mail($formatted_meta, $item){
    $new_meta = array();
    foreach ( $formatted_meta as $id => $meta_array ) {
        // We are removing the meta with the key 'something' from the whole array.
        if ( 'ark-part-number' === $meta_array->key ) { continue; }
        $new_meta[ $id ] = $meta_array;
    }
    return $new_meta;
}