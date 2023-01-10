<?php

// WC checkout restrict for specific Email to place Order
add_action( 'woocommerce_after_checkout_validation' , 'wdo_user_checkout_email_restriction', 10, 2 );
function wdo_user_checkout_email_restriction( $data, $errors ) {
    $notallow_emails = array('email@domain.com','email1@domain.com');
    if( isset( $data[ 'shipping_email' ] ) && !empty( $data[ 'shipping_email' ] ) ) {
        if( in_array( $data[ 'shipping_email' ], $notallow_emails ) ) {
            $errors->add( 'email','Something wrong with your entered data.');
        }
    }
}