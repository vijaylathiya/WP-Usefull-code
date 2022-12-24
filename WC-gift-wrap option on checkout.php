<?php 
//ls custom gift wrap field
add_action('woocommerce_after_order_notes', 'ls_woocommerce_after_order_notes');
function ls_woocommerce_after_order_notes($checkout){

    $gift_wrap = $checkout->get_value( 'ls_gift_wrap' ) ? $checkout->get_value( 'ls_gift_wrap' ) : 1;
    //ls custom added new div to add heading section
    echo '<div class="ls_giftwrap_sec"> 
            <div class="checkout_heading ls_row">
                <div class="heading_txt learn-bg1">'. __('Gift Wrap','nm-framework') .'</div>
            </div>'; 

    echo '<div class="ls_giftwrap_field">';
        //echo '<i class="fa fa-gift"></i> <span class="ls-small-title">'. __('Gift wrap', 'nm-framework') .'</span>'; //ls custom coment gift icon
        woocommerce_form_field( 'ls_gift_wrap', array(
            'type'          => 'checkbox',
            'label'         => '<span class="gift_lbl"> <span class="ttl">'. __('Giving it as a gift? We can giftwrap it for you.') .'</span><span>'.__('Wrap as a present for only Php 65 (optional). Each item will be individually gift wrapped.') .'</span></span>', //LS Custom updated
            'required'  => false,
            'label_class' => 'woocommerce-form__label-for-checkbox checkbox'
        ), $gift_box);

    echo '</div>';
    echo '</div>';
}


add_action('woocommerce_admin_order_data_after_shipping_address', 'ls_woocommerce_admin_order_data_after_shipping_address', 10, 1);
function ls_woocommerce_admin_order_data_after_shipping_address($order){
    
    if( get_post_meta( $order->id, 'ls_gift_wrap', true ) == 1){
        echo '<h3>'. __('Gift Wrap', 'woocommerce') .'</h3>';
        echo '<p><strong>Wrap as a present</strong> : '. __('yes');
    }

}


add_action( 'woocommerce_checkout_update_order_meta', 'my_custom_checkout_field_update_order_meta' );
function my_custom_checkout_field_update_order_meta( $order_id ) {
    if ( ! empty( $_POST['ls_gift_wrap'] ) )
        update_post_meta( $order_id, 'ls_gift_wrap', sanitize_text_field( $_POST['ls_gift_wrap'] ) );
    
 
}

//ls custom add additional fees to checkout page for
add_action( 'woocommerce_cart_calculate_fees', 'ls_calculate_cost');
function ls_calculate_cost( $cart ){
    if ( ! $_POST || ( is_admin() && ! is_ajax() ) )
        return;

    if ( isset( $_POST['post_data'] ) ) 
        parse_str( $_POST['post_data'], $post_data );
    else
        $post_data = $_POST;

    if (isset($post_data['ls_gift_wrap'])) {
        global $woocommerce;
        
        $total_item = WC()->cart->get_cart_contents_count();
        $giftwrap_charge_per_item = 65*$total_item;

        WC()->cart->add_fee( __('Gift Wrap') , $giftwrap_charge_per_item );
    }
}

in Order-details.php for email  https://prnt.sc/21hu8fl 

<?php
    //$ls_dob      = get_post_meta( $order->ID, 'ls_reg_dob' ); //ls custom comment Birthday Field
    $ls_giftwrap = get_post_meta( $order->ID, 'ls_gift_wrap' );
    echo '<div class="ls_custom_ordermeta">';
    /*
    // ls custom comment Birthday Field
    if($ls_dob[0])
        echo '<p><strong>'. __('Birthday') .' : </strong>'. $ls_dob[0] .'</p>';  */

    if($ls_giftwrap){
        echo '<div class="ls-gift-wrap">';
        echo '<i class="fa fa-gift"></i> <span class="ls-small-title">'. __('Gift wrap') .'</span>';
        echo '<p class="desc">'. __('Wrap as a present (+ P65) We will package each of our products individually.','nm-framework') .' : '. __('Yes') .'</p>';
        echo '</div>';
    }
    echo '</div>';
?>



Inside footer.php 

<!-- ls custom script ---> 
<script type="text/javascript">
 <?php
    if( is_checkout() ){ ?>
        jQuery('#ls_gift_wrap').click(function(){
          jQuery('body').trigger('update_checkout');
        });
    <?php } ?>
    
</script>