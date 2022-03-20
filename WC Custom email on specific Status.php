<?php


add_action('woocommerce_order_status_changed', 'ls_woocommerce_order_status_changed');
function ls_woocommerce_order_status_changed($order_id, $checkout=null){
    global $woocommerce;
    $order = new WC_Order( $order_id );
   

	if($order->status === 'pending' ){
     	$ls_status = 'Pending';
        
		$mailer = $woocommerce->mailer();
        $headers_v[] = 'From:Infor <info@domain.com>';
        $headers_v[] = 'Content-Type: text/html; charset=UTF-8';
		
		
		$message_body = '';
        $message_body .= '<p style="padding-top:0px"><strong>'. __( 'Hi' ).' '. $order->billing_first_name.' </strong>ðŸ˜Š</p>';
        $message_body .= '<p>'. __( 'Thank you for your purchase! â¤ï¸' ).'</p>';
		$message_body .= '<p>'. __( 'Since you chose to pay via bank deposit, kindly settle your total bill by transferring said amount to the either of the following bank accounts within 24 hours after the order is placed to avoid cancellation:' ).'</p>';
        $message_body .= '<p style="padding-top:15px">'. __( 'Bank: Metropolitan Bank and Co.' ) .'</p>';
        $message_body .= '<p>'. __( 'Account name: ABC Bank ac' ) .'</p>';
        $message_body .= '<p>'. __( 'Account number: XXX XX XX XXXX ' ) .'</p>';
		$message_body .= '<p style="padding-top:15px">'. __( 'Bank: Name of the Bank' ) .'</p>';
        $message_body .= '<p>'. __( 'Account name: Bank Account holer name' ) .'</p>';
        $message_body .= '<p>'. __( 'Account number: XXXX XXXX XXXX' ) .'</p>';
        $message_body .='<p style="padding-top:15px">'.__('Email your proof of deposit or transaction receipt with your name and order number to info@domain.com. Our Finance Team will respond to your email within 24 hours and during business hours to acknowledge your payment. ').'</p>';
    	$message_body .= '<p>'.__( 'In the meantime, you can check out our Blog - www.domain.com/blog - to read on topics about love toys, sex, relationships, sexual health and many more!' ).'</p>';
        $message_body .= '<p>'.__( 'REMINDERS: Keep your phone line open so that our courier can contact you on the date of delivery. Track your package using the tracking number from the courier and please avoid rescheduling the delivery.' ).'</p>';
		}

		ob_start();
		do_action( 'woocommerce_email_order_details', $order, $sent_to_admin, $plain_text, $email );
		do_action( 'woocommerce_email_order_meta', $order, $sent_to_admin, $plain_text, $email );
		do_action( 'woocommerce_email_customer_details', $order, $sent_to_admin, $plain_text, $email );
		$message_body .= ob_get_clean();
		$message = $mailer->wrap_message(sprintf( __( 'Order ' ).$ls_status ), $message_body );
        $mailer->send( $order->billing_email, __( 'Order ' ).$ls_status, $message, $headers_v );
   
}