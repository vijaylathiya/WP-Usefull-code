<?php
//https://iconicwp.com/blog/add-custom-cart-item-data-woocommerce/

function ls_fn_output_banner_field() {
    global $product;
    ?>
    <div class="ls_banner_size-field">
        <label for="ls_banner_size"><?php _e( 'Banner Size', 'iconic' ); ?></label>
        <input type="text" id="ls_banner_size" name="ls_banner_size" placeholder="<?php _e( 'Enter Banner Size', 'iconic' ); ?>" >
    </div>
    <?php
}
add_action( 'woocommerce_before_add_to_cart_button', 'ls_fn_output_banner_field', 10 );

function ls_fn_add_ls_banner_size_text_to_cart_item( $cart_item_data, $product_id, $variation_id ) {
    $ls_banner_size_text = filter_input( INPUT_POST, 'ls_banner_size' );
    if ( empty( $ls_banner_size_text ) ) {
        return $cart_item_data;
    }
    $cart_item_data['ls_banner_size'] = $ls_banner_size_text;
    return $cart_item_data;
}
add_filter( 'woocommerce_add_cart_item_data', 'ls_fn_add_ls_banner_size_text_to_cart_item', 10, 3 );

function ls_fn_display_ls_banner_size_text_cart( $item_data, $cart_item ) {
    if ( empty( $cart_item['ls_banner_size'])){return $item_data;}
    $item_data[] = array(
        'key'     => __( 'Banner Size', 'iconic' ),
        'value'   => wc_clean( $cart_item['ls_banner_size'] ),
        'display' => '',
    );
    return $item_data;
}
add_filter( 'woocommerce_get_item_data', 'ls_fn_display_ls_banner_size_text_cart', 10, 2 );

function ls_fn_add_ls_banner_size_text_to_order_items( $item, $cart_item_key, $values, $order ) {
    if ( empty( $values['ls_banner_size'] ) ) {
        return;
    }
    $item->add_meta_data( __( 'Banner Size', 'iconic' ), $values['ls_banner_size'] );
}
add_action( 'woocommerce_checkout_create_order_line_item', 'ls_fn_add_ls_banner_size_text_to_order_items', 10, 4 );