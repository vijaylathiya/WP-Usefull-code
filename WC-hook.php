<?php
// On New user signup Send notification mail to specific Email ID
add_filter( 'wp_new_user_notification_email_admin', 'my_wp_new_user_notification_email_admin', 10, 3 );
function my_wp_new_user_notification_email_admin( $notification, $user, $blogname ) {
    $notification['to'] = 'phplathiya@gmail.com';
    return $notification;
}
//In WooCommerce On New User Signup Send notication mail to WordPress Admin Email ID 
add_action( 'woocommerce_created_customer', 'woocommerce_created_customer_admin_notification' );
function woocommerce_created_customer_admin_notification( $customer_id ) {
  wp_send_new_user_notifications( $customer_id, 'admin' );
}


?>