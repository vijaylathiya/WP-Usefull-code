<?php
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
    </div><br>';
}

add_action( 'woocommerce_add_cart_item_data', 'save_custom_data_hidden_fields', 10, 2 );
function save_custom_data_hidden_fields( $cart_item_data, $product_id ) {

    $data = array();

    if( isset( $_REQUEST['age_prod'] ) ) {
        $cart_item_data['custom_data']['age'] = $_REQUEST['age_prod'];
        $data['age'] = $_REQUEST['age_prod'];
    }

    if( isset( $_REQUEST['quality_prod'] ) ) {
        $cart_item_data['custom_data']['quality'] = $_REQUEST['quality_prod'];
        $data['quality'] = $_REQUEST['quality_prod'];
    }

    // below statement make sure every add to cart action as unique line item
    $cart_item_data['custom_data']['unique_key'] = md5( microtime().rand() );
    WC()->session->set( 'price_calculation', $data );

    return $cart_item_data;
}

add_filter( 'woocommerce_before_calculate_totals', 'custom_cart_items_prices', 10, 1 );
function custom_cart_items_prices( $cart_object ) {

    if ( is_admin() && ! defined( 'DOING_AJAX' ) )
        return;

    foreach ( $cart_object->get_cart() as $cart_item ) {

        // get the product id (or the variation id)
        $id = $cart_item['data']->get_id();
		// GET THE NEW PRICE (code to be replace by yours)
        $new_price = 500; // <== Add your code HERE
		
		
		 $original_price = $cart_item['data']->price; // Product original price
		$age = $cart_item['custom_data']['age'];
        $quality = $cart_item['custom_data']['quality'];

        // CALCULATION FOR EACH ITEM:
        ## Make HERE your own calculation to feet your needs  <==  <==  <==  <==
       // $new_price = $original_price + ( ($age * 0.1) + $quality );
	   
	   // $new_price = $original_price * $age;
	    $new_price = $quality * $age;
		
		//die;
        // Updated cart item price
        $cart_item['data']->set_price( $new_price ); 
    }
}

?>