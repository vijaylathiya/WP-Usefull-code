<?php
	include('wp-config.php');
	global $woocommerce;
	$mailer = $woocommerce->mailer();
	$orderid=75760;
	$order = wc_get_order( $orderid );

	$order_billing_email = $order->get_billing_email();
	$order_billing_fn = $order->get_billing_first_name();
	$order_billing_ln = $order->get_billing_last_name();
	$coupun_code = 'TestOFCoupon';
	
	$message_body = '';
	$message_body .= '<p>'. __( 'Hi' ). ' '. $order_billing_fn .',</p>';
	$message_body .= '<p>'. __( "Thanks for purchasing from our Top selection! We want you to keep exploring and enjoyig your bedroom life so we're giving you a coupon for your next purchase..",'woocommerce' ) .'</p>';
	$message_body .= '<p style="text-align:center; padding:10px;background-color:#222222; color:#fff">'. __( 'Your Coupon code is' ) .' : '. $coupun_code .'</p>';
	$message_body .= '<p>'. __( 'Thanks a lot, and remember to keep things sexy!', 'woocommerce') .'</p>';
	$message_body .= '<p>'. __( 'Sincerely,<br/>Our Shop', 'woocommerce') .'</p>';
	$message = $mailer->wrap_message(sprintf( __( 'Thank you for purchased from Website.' )), $message_body );
	
	if( $mailer->send( 'lathiyasolutions@gmail.com', __( 'Thank you for purchased from Our Website','woocommerce'), $message, $headers_v ) ){
		echo 'mail send';
	}
	else
		echo 'mail was not send';