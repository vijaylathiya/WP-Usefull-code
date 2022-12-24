<?php 
/* LS Custom Send Coupon code mail*/

add_action('woocommerce_order_status_changed', 'ls_admin_change_order_status');
function ls_admin_change_order_status($order_id, $checkout=null){
    global $woocommerce;
    $order = new WC_Order( $order_id );
    $items = $order->get_items();
    foreach ( $items as $item ) {
        $product_id = $item->get_product_id();
    }

    if($order->status === 'completed' ) {
        $rndm_num = rand(10,10000);
        $coupon_code = 'thankyou'.$rndm_num.$order_id; // Code - perhaps generate this from the txt + randomnumber + the order ID
        $amount = '10'; // Amount
        $discount_type = 'percent';

        $coupon = array(
            'post_title' => $coupon_code,
            'post_content' => '',
            'post_status' => 'publish',
            'post_author' => 1,
            'post_type'     => 'shop_coupon'
        );    

        $new_coupon_id = wp_insert_post( $coupon );

        // Add meta
        update_post_meta( $new_coupon_id, 'discount_type', $discount_type );
        update_post_meta( $new_coupon_id, 'coupon_amount', $amount );
        update_post_meta( $new_coupon_id, 'individual_use', 'no' );
        update_post_meta( $new_coupon_id, 'product_ids', $product_id );
        update_post_meta( $new_coupon_id, 'exclude_product_ids', '' );
        update_post_meta( $new_coupon_id, 'usage_limit', '1' );
        update_post_meta( $new_coupon_id, 'expiry_date', '' );
        update_post_meta( $new_coupon_id, 'apply_before_tax', 'yes' );
        update_post_meta( $new_coupon_id, 'free_shipping', 'no' );

        $cpn_code = get_post_meta( $order_id, 'ls_coupon', true );
        if( !$cpn_code )
            update_post_meta( $order_id, 'ls_coupon', $coupon_code );

        update_post_meta( $order_id, 'ls_coupon_status', "0" );
        update_post_meta( $order_id, 'ls_complete_date', date('Y-m-d') );
    }   
}

/*----- all Mail send at a time once every day -----*/
// create a scheduled event (if it does not exist already)
register_activation_hook(__FILE__, 'ls_cronstarter_activation');
function ls_cronstarter_activation() {
    if( !wp_next_scheduled( 'ls_thnaku_coupn_mailcronjob' ) ) {  
       wp_schedule_event( time(), 'daily', 'ls_thnaku_coupn_mailcronjob' );  
    }
}
// and make sure it's called whenever WordPress loads
add_action('wp', 'ls_cronstarter_activation');

// hook that function onto our scheduled event:
add_action ('ls_thnaku_coupn_mailcronjob', 'ls_onetime_check_order_time');
function ls_onetime_check_order_time() {
    //global $sitepress;
    //$sitepress->switch_lang($user_lang);
    global $woocommerce;
    $tdate = date('Y-m-d');
    $d = 14;
    $fortnago_date = date('Y-m-d', strtotime($tdate . " -".$d." days"));
    if( $_SERVER['REMOTE_ADDR'] == '103.37.183.246'){
        //echo $fortnago_date;
    }
    

    $args = array(
        'post_type' => 'shop_order',
        'post_status' => 'completed',
        'meta_query' => array(
            'relation' => 'AND',
            array(
                'key'     => 'ls_coupon_status',
                'value'   => 0,
                'compare' => '=',
            ),
            array(
                'key'     => 'ls_complete_date',
                'value'   => $fortnago_date,
                'compare' => '<=',
                'type' => 'DATE',
            ),
        ),
    );

    $ls_query = new WP_Query( $args );

    $headers_v[] = 'From:Lauvette <info@lauvette.com>';
    $headers_v[] = 'Content-Type: text/html; charset=UTF-8';
    $ls_orders = $ls_query->posts;
    $mailer = $woocommerce->mailer();

    if(count($ls_orders)>0){
     foreach($ls_orders as $oids){
        $orderids[] = $oids->ID;

        foreach( $orderids as $orderid ) {

         $orderid = $orderid;
         $coupun_status = get_post_meta( $orderid, 'ls_coupon_status', true );
            if($coupun_status == '0'){
                $order = wc_get_order( $orderid );

                $order_billing_email = $order->get_billing_email();
                $order_billing_fn = $order->get_billing_first_name();
                $order_billing_ln = $order->get_billing_last_name();
		$shipping_email = get_post_meta( $orderid, 'shipping_email', true );   //ls custom added shipping email for couponcode mail
                $coupun_code = get_post_meta($orderid, 'ls_coupon', true);

                $message_body = '';
                $message_body .= '<p>'. __( 'Hi' ). ' '. $order_billing_fn .',</p>';
                $message_body .= '<p>'. __( "Thanks for purchasing from our Lovetoy selection! We want you to keep exploring and enjoying your bedroom life so we're giving you a coupon for your next purchase..",'tapusin' ) .'</p>';
                $message_body .= '<p style="text-align:center; padding:10px;background-color:#222222;color:#fff">'. __( 'Your Coupon code is' ) .' : '. $coupun_code .'</p>';
                $message_body .= '<p>'. __( 'Thanks a lot, and remember to keep things sexy!', 'woocommerce') .'</p>';
				$message_body .= '<p>'. __( 'Sincerely,<br/>Lauvette', 'woocommerce') .'</p>';
				$message = $mailer->wrap_message(
                // Message head and message body.
                sprintf( __( 'Thank you for purchased from Tapusin.' )), $message_body );
                
		//ls custom added code to also send coupon code mail to shipping mail
                if( $mailer->send( $order_billing_email, __( 'Thank you for purchased from Tapusin','woocommerce'), $message, $headers_v ) && $mailer->send( $shipping_email, __( 'Thank you for purchased from Tapusin','woocommerce'), $message, $headers_v ) ){
                    update_post_meta( $orderid, 'ls_coupon_status', "1" );
                }
            }
        }
        
     }
     
    }
}

register_deactivation_hook(__FILE__, 'ls_cronstarter_deactivation');

function ls_cronstarter_deactivation() {
    wp_clear_scheduled_hook('ls_onetime_check_order_time');
}

?>