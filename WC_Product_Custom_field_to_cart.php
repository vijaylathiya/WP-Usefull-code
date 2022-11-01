<?php
//  https://wp-qa.com/dynamically-calculate-product-price-based-on-custom-fields-values-when-added-to-cart
// Add product custom fields inside the product add-to-cart form
// https://stackoverflow.com/questions/55432718/how-do-i-pass-multiple-custom-fields-to-cart-in-woocommerce
add_action('woocommerce_before_add_to_cart_button', 'custom_data_hidden_fields');
function custom_data_hidden_fields() {
    echo '<div class="imput_fields custom-imput-fields">
        <label class="age_prod">Age: <br><input type="text" id="age_prod" name="age_prod" value="" /></label>
        <label class="quality_prod">Quality: <br>
            <select name="quality_prod" id="quality_prod">
                <option value="1" selected="selected">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
            </select>
        </label>
    </div><br>';?>
     <div class="ls_banner_size-field">
        <label for="ls_banner_size"><?php _e( 'Banner Size', 'iconic' ); ?></label>
        <input type="text" id="ls_banner_size" name="ls_banner_size" placeholder="<?php _e( 'Enter Banner Size', 'iconic' ); ?>" >
    </div> <?php 
}
// Save product custom fields values after submission into the cart item data
add_filter( 'woocommerce_add_cart_item_data', 'save_custom_data_hidden_fields', 10, 2 );
function save_custom_data_hidden_fields( $cart_item_data, $product_id ) {

    $data = array();
    $ls_banner_size_text = filter_input( INPUT_POST, 'ls_banner_size' );
    if ( isset( $ls_banner_size_text ) ) {
         $cart_item_data['ls_banner_size'] = $ls_banner_size_text;
    }
    if( isset( $_REQUEST['age_prod'] ) ) {
        $cart_item_data['custom_data']['age'] = $_REQUEST['age_prod'];
    }

    if( isset( $_REQUEST['quality_prod'] ) ) {
        $cart_item_data['custom_data']['quality'] = $_REQUEST['quality_prod'];
    }

    // below statement make sure every add to cart action as unique line item
    $cart_item_data['custom_data']['unique_key'] = md5( microtime().rand() );

    return $cart_item_data;
}


//display it in cart and checkout pages
add_filter( 'woocommerce_get_item_data', 'ls_fn_display_ls_banner_size_text_cart', 10, 2 );
function ls_fn_display_ls_banner_size_text_cart( $item_data, $cart_item ) {
    if ( empty( $cart_item['ls_banner_size'])){return $item_data;}
    $item_data[] = array(
        'key'     => __( 'Banner Size', 'iconic' ),
        'value'   => wc_clean( $cart_item['ls_banner_size'] ),
        'display' => '',
    );
    
     // Additional displayed custom cat item data
    if ( isset($cart_item['custom_data']['age']) && ! empty($cart_item['custom_data']['age'])  ) {
        $item_data[] = array(
            'key'     => __( 'Age', 'test' ),
            'value'   => wc_clean( $cart_item['custom_data']['age'] ),
            'display' => '',
        );
    }

    if ( isset($cart_item['custom_data']['quality']) && ! empty($cart_item['custom_data']['quality'])  ) {
        $item_data[] = array(
            'key'     => __( 'Quantity', 'test' ),
            'value'   => wc_clean( $cart_item['custom_data']['quality'] ),
            'display' => '',
        );
    }

    return $item_data;
}


//To save that custom cart item data to the order (and display it on orders and email notifications, 
add_action( 'woocommerce_checkout_create_order_line_item', 'ls_fn_add_ls_banner_size_text_to_order_items', 10, 4 );
function ls_fn_add_ls_banner_size_text_to_order_items( $item, $cart_item_key, $values, $order ) {
    if ( empty( $values['ls_banner_size'] ) ) {
        return;
    }
    
    if ( isset($values['ls_banner_size']) && ! empty($values['ls_banner_size'])  ) 
        $item->add_meta_data( __( '_Banner Size', 'iconic' ), $values['ls_banner_size'] );
    
    if ( isset($values['custom_data']['age']) && ! empty($values['custom_data']['age'])  ) 
        $item->add_meta_data( __( '_Age', 'iconic' ), $values['custom_data']['age'] );
    
    if ( isset($values['custom_data']['quality']) && ! empty($cart_item['custom_data']['quality'])  ) {
        $item->add_meta_data( __( '_Quantity', 'iconic' ), $values['custom_data']['quality'] );

}


// Replace the item price by your custom calculation
add_action( 'woocommerce_before_calculate_totals', 'add_custom_item_price', 10 );
function add_custom_item_price( $cart ) {

    if ( is_admin() && ! defined( 'DOING_AJAX' ) )
        return;

    if ( did_action( 'woocommerce_before_calculate_totals' ) >= 2 )
        return;

    foreach ( $cart->get_cart() as $cart_item ) {
        // Product ID
        $product_id = version_compare( WC_VERSION, '3.0', '<' ) ? $cart_item['data']->id : $cart_item['data']->get_id();
        // Product original price
        $original_price = version_compare( WC_VERSION, '3.0', '<' ) ? $cart_item['data']->price : $cart_item['data']->get_price();
        $original_price = $item_values['data']->price; 

        ## --- Get your custom cart item data --- ##
        if( isset($cart_item['custom_data']['age']) )
            $age     = $cart_item['custom_data']['age'];
        if( isset($cart_item['custom_data']['quality']) )
            $quality = $cart_item['custom_data']['quality'];

        // CALCULATION FOR EACH ITEM:
        ## Make HERE your own calculation to feet your needs  <==  <==  <==  <==
        $new_price = $original_price + ( ($age * 0.1) + $quality );

        ## Set the new item price in cart
        if( version_compare( WC_VERSION, '3.0', '<' ) )
            $cart_item['data']->price = $new_price;
        else
            $cart_item['data']->set_price($new_price);
    }
}

//add_action( 'woocommerce_before_calculate_totals', 'add_custom_item_price', 10 );
function add_custom_item_price( $cart_object ) {

    foreach ( $cart_object->get_cart() as $item_values ) {

        ##  Get cart item data
        $item_id = $item_values['data']->id; // Product ID
        $original_price = $item_values['data']->price; // Product original price

        ## Get your custom fields values
        $age = $item_values['custom_data']['age'];
        $quality = $item_values['custom_data']['quality'];

        // CALCULATION FOR EACH ITEM:
        ## Make HERE your own calculation to feet your needs  <==  <==  <==  <==
        $new_price = $original_price + ( ($age * 0.1) + $quality );

        ## Set the new item price in cart
        $item_values['data']->price = $new_price;
    }
}