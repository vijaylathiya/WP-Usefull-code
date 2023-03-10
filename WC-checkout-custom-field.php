<?php

/* LS Custom dob field for checkout page */
add_filter( 'woocommerce_checkout_fields' , 'custom_override_checkout_fields' );
function custom_override_checkout_fields( $fields ) {

	  $fields['shipping']['shipping_phone'] = array(
        'label'     => __('Mobile Number', 'nm-framework'),
        //'placeholder'   => _x('Mobile Number', 'placeholder', 'woocommerce'),
        'required'  => true,
        'class'     => array('form-row-wide'),
        'clear'     => true,
	'description' => __('Enter your correct number so that our courier can contact you.','nm-framework'),  //ls custom added description to shipping phone
     );

	   return $fields;
}



// * Update the order meta and user meta with field value
add_action( 'woocommerce_checkout_update_order_meta', 'my_custom_checkout_field_update_order_meta' );
function my_custom_checkout_field_update_order_meta( $order_id ) {

	 if ( ! empty( $_POST['shipping_phone'] ) )
        update_post_meta( $order_id, 'shipping_phone', sanitize_text_field( $_POST['shipping_phone'] ) );
}



add_action('woocommerce_admin_order_data_after_shipping_address', 'ls_woocommerce_admin_order_data_after_shipping_address', 10, 1);
function ls_woocommerce_admin_order_data_after_shipping_address($order){
    echo '<p><strong>'.__('Email Address', 'woocommerce').':</strong> <a href="mailto:'. get_post_meta( $order->id, 'shipping_email', true ) .'"> ' . get_post_meta( $order->id, 'shipping_email', true ) . '</a></p>';
    echo '<p><strong>'.__('Phone', 'woocommerce').':</strong> <a href="tel: '. get_post_meta( $order->id, 'shipping_phone', true ) .'">' . get_post_meta( $order->id, 'shipping_phone', true ) . '</a></p>';

}


///

add_action('woocommerce_before_order_notes', 'wps_add_select_checkout_field');
function wps_add_select_checkout_field( $checkout ) {
 
  woocommerce_form_field( 'myquestion', array(
      'type'          => 'select',
      'class'         => array( 'wps-drop' ),
      'label'         => __( 'How Did You Hear About Color Guru?' ),
      'options'       => array(
        'blank'   => __( 'Please Select', 'wps' ),
          'Friends/family' => __( 'Friend/Family member', 'wps' ),
          'Gift' => __( 'Received as gift', 'wps' ),
          'Instagram'   => __( 'Instagram', 'wps' ),
          'TikTok'   => __( 'TikTok', 'wps' ),
          'Google'   => __( 'Google search', 'wps' ),
          'Podcast'   => __( 'Podcast', 'wps' ),
          'Other'   => __( 'Other', 'wps' )
      )
 ),
 
  $checkout->get_value( 'myquestion' ));
}

//* Update the order meta with field value
 add_action('woocommerce_checkout_update_order_meta', 'wps_select_checkout_field_update_order_meta');
 function wps_select_checkout_field_update_order_meta( $order_id ) {
 
   if ($_POST['myquestion']) update_post_meta( $order_id, 'myquestion', esc_attr($_POST['myquestion']));
 
}

//* Display field value on the order edition page
add_action( 'woocommerce_admin_order_data_after_billing_address', 'wps_select_checkout_field_display_admin_order_meta', 10, 1 );
function wps_select_checkout_field_display_admin_order_meta($order){
 
  echo '<p><strong>'.__('Found by').':</strong> ' . get_post_meta( $order->id, 'myquestion', true ) . '</p>';
 
}


//* Add selection field value to emails
add_filter('woocommerce_email_order_meta_keys', 'wps_select_order_meta_keys');
function wps_select_order_meta_keys( $keys ) {
 
  $keys['How did you hear about Color Guru'] = 'myquestion';
  return $keys;
  
}
	