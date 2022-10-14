<?php
/*
 * Plugin Name: Woo - ShopBettero - Credit Card
 * Plugin URI: https://shopbettero.com/
 * Description: Add Shopbettero Credit Card Processing to your WooCommerce store.
 * Version: 1.0
 * Author: shopbettero.com
 */
if ( ! defined( 'ABSPATH' ) ) exit;
/**
 * Shopbettero - Credit Card INIT
*/
function init_woo_shopbettero_gateway_cc() {
	if ( ! class_exists( 'WooCommerce' ) ) {
		add_action( 'admin_notices', 'shopbettero_cc_woocommerce_missing_wc_notice' );
		return;
	}
	/**
	 * Action hook to add ShopBetteroGateway Method to WooCommerce
	 */
	function add_woo_shopbettero_gateway_cc_class( $methods ) {
		$methods[] = 'WC_Gateway_Shopbettero_CC'; 
		return $methods;
	}
	add_filter( 'woocommerce_payment_gateways', 'add_woo_shopbettero_gateway_cc_class' );
	
	if( class_exists( 'WC_Payment_Gateway' ) ) {
		class WC_Gateway_Shopbettero_CC extends WC_Payment_Gateway {
			public static $log_enabled = false;
			public static $log = false;
			public function __construct() {
				$this->id					= 'shopbetterocc';
				$this->method_title			= __('Shopbettero Credit Card', 'wooicc');
				$this->method_description	= __("ShopBetteroGateway uses the Transparent Redirect method to process payments so cardholder data is never stored or handled by your WooCommerce store.  Order processing takes place automatically with your existing checkout process so that the user experience is seamless.", 'wooicc');				
				$this->icon					= apply_filters( 'woocommerce_shopbettero_icon_cc', plugins_url( 'images/credit-cards.png' , __FILE__ ) );
				$this->has_fields			= true;
				
				$this->init_form_fields();
				$this->init_settings();
				$this->title		= $this->settings['title'];
				$this->description	= $this->settings['description'];
				$this->testmode		= $this->settings['testmode'];
				$this->debug        = 'yes' === $this->get_option( 'debug', 'no' );
				
				$this->notify_url	= WC()->api_request_url( get_class() );							
				self::$log_enabled    = $this->debug;

				add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
			}
			
	public function admin_options() {
		?>
            <h3><?php _e( 'Shopbettero Credit Card', 'wooicc' ); ?></h3>
            <p><?php _e( 'ShopBetteroGateway uses the Transparent Redirect method to process payments so cardholder data is never stored or handled by your WooCommerce store. Order processing takes place automatically with your existing checkout process so that the user experience is seamless.', 'wooicc' ); ?></p>
            <table class="form-table">
                <?php $this->generate_settings_html(); ?>
               
            </table>
			<?php
			}
			/**
			 * Initialise Gateway Settings Form Fields
			 */
			function init_form_fields() {
				$this->form_fields = array(
					'enabled'		=> array(
						'title'			=> __('Enable/Disable', 'wooicc'),
						'type'			=> 'checkbox',
						'label'			=> __('Enable Shopbettero Payment Module', 'wooicc'),
						'default'		=> 'no'
					),
					'title'			=> array(
						'title'			=> __('Title:', 'wooicc'),
						'type'			=> 'text',
						'description'	=> __('This controls the title which the user sees during checkout.', 'wooicc'),
						'default'		=> __('Secure Credit Card', 'wooicc'),
						'desc_tip'		=> true,
					),
					'description'	=> array(
						'title'			=> __('Description:', 'wooicc'),
						'type'			=> 'textarea',
						'description'	=> __('This controls the discription which the user  sees the during checkout.', 'wooicc'),
						'default'		=> __("All cards are charged by ShopBettero Gateway", 'wooicc'),
						'desc_tip'		=> true,
					),
					'testmode'		=> array(
						'title'			=> __('shopbettero Sandbox:', 'wooicc'),
						'type'			=> 'checkbox',
						'label'			=> __( 'Enable Shopbettero sandbox', 'wooicc' ),
						'description'	=> __('Shopbettero sandbox can be used to test payments.', 'wooicc'),
						'default'		=> 'no',
						'desc_tip'		=> true,
					),
					'debug'                 => array(
						'title'       => __( 'Debug log', 'woocommerce' ),
						'type'        => 'checkbox',
						'label'       => __( 'Enable logging', 'woocommerce' ),
						'default'     => 'no',						
						'description' => sprintf( __( 'Log ShopBetteroGateway events, such as IPN requests, inside %s Note: this may log personal information. We recommend using this for debugging purposes only and deleting the logs when finished.', 'woocommerce' ), '<code>' . WC_Log_Handler_File::get_log_file_path( 'ShopBetteroGatewayCreditCard' ) . '</code>' ),						
					)
				);
			}
			/**
			 * Check if this gateway is enabled
			 */
			public function is_available() {			
				return parent::is_available();
			}
			public static function log( $message, $level = 'info' ) {
				if ( self::$log_enabled ) {
					if ( empty( self::$log ) ) {
						self::$log = wc_get_logger();
					}
					self::$log->log( $level, $message, array( 'source' => 'ShopBetteroGatewayCreditCard' ) );
				}
			}
			/**
			 * Process the payment and return the result - this will redirect the customer to the pay page
			 */
			public function process_payment( $order_id ) {
				global $woocommerce;

				$this->order = new WC_Order( $order_id );
				$order = $this->order;

				$gatewayRequestData	 = $this->create_shopbettero_cc_request();
				
				$verify_payment = $this->verify_shopbettero_cc_payment( $gatewayRequestData );
				
				if ( $gatewayRequestData AND !empty($verify_payment)) {
					$message = $verify_payment['msg'];

        			if($verify_payment['status'] == 'success'){

        				$IsSuccess = $order->payment_complete();
						if ($IsSuccess == '0') {
							$this->log( 'Order Details : ' . $order .' Response From API ' . $response, 'error' );
						}									
						
						$order->add_order_note( __('Payment successful.', 'wooicc') );
						$woocommerce->cart->empty_cart();

        			}
        			else{ //Failed Order Notice
        			
						if( $order_id ) {
							$order->update_status('failed');
							$order->add_order_note( __('Payment failed.', 'wooicc') );
							$order->add_order_note( $message['msg'] );
						}							
					}
					//Front end message
        			if( !empty($message) ) 	{
						if ( function_exists( 'wc_add_notice' ) ) {
							wc_add_notice( $message['msg'], $message['class'] );
						} else {
							if( $message['class'] == 'success' ) {
								$woocommerce->add_message( $message['msg'] );
							} else {
								$woocommerce->add_error( $message['msg'] );
							}
							$woocommerce->set_messages();
						}
					}

					if($verify_payment['status'] == 'success'){
						return array(
							'result' => 'success',
							'redirect' => $this->get_return_url( $order )
						);
					}

				} else {
				    $this->order->add_order_note( 'Payment failed', 'wooicc' );
				    wc_add_notice( __( '(Transaction Error) something is wrong.', 'wooicc' ), 'error' );
				}
			}
			protected function create_shopbettero_cc_request(){
				

				if ( $this->order AND $this->order != null ) {
					
					$order = $this->order;

					$order_id = $order->get_id();
			        $txn_description = 'WooCommerce Order ID: ' . $order->get_order_number();//Used as a description for the transaction.
			        $Amount = $order->get_total(); 
				    $ccdata = array(
						'CCNumber'          => $_POST[ 'CCNumber' ],
						'CC_cardtype'       => $_POST[ 'cc_cardtype' ],
						//'CCExpire'        => $_POST[ 'CCExpire' ],
						'CCCVC'         	=> $_POST[ 'CCCVC' ],
						'cc_expdatemonth'	=> $_POST[ 'cc_expdatemonth' ],
						'cc_expdateyear'    => $_POST[ 'cc_expdateyear' ]
				    );
					update_post_meta($order_id, 'cc_details', sanitize_text_field(serialize($ccdata)));
					 return true;
				}
				return false;
			}
			protected function verify_shopbettero_cc_payment( $gatewayRequestData ) {
				global $woocommerce;
				
				$ls_return_array = array();
				$message = array();
				
				$message['msg'] = __('Thank you for shopping with us. Your payment request under process.', 'wooicc'); 
				$message['class'] = 'success';
				
				$ls_return_array['msg'] = $message;
				$ls_return_array['status'] = 'success';;
				return $ls_return_array;
			}
			
			//Replaced receipt_page to payment_fields
			function payment_fields() {	
				?>			
					<p><?php _e( 'Enter your Credit or Debit Card details below to securely pay for your order.', 'wooicc' ); ?></p>					
					<div>					
						<p class="form-row form-row-wide form-row-ccnumber">
							<label><?php _e( 'Credit Card Number', 'wooicc' ); ?> <span class="required">*</span></label>
							<input type="text" class="input-text" size="16" maxlength="16" name="CCNumber" value="" autocomplete="off" />
						</p><div class="clear"></div>
                        <p class="form-row form-row-wide">
                            <label><?php _e( 'Card Type', 'wooicc' ); ?> <span class="required">*</span></label>
                            <select name="cc_cardtype">
                            <option value="Visa" selected="selected">Visa</option>
                            <option value="MasterCard">MasterCard</option>
                            </select>
                            </p>
                            
                            <!-- <p class="form-row form-row-wide form-row-ccexpire">
                                <label><?php // _e( 'Expiration Date (MMYY)', 'wooicc' ); ?></label>
                                <input type="text" class="input-text" size="4" maxlength="4" name="CCExpire" value="" autocomplete="off" />
                            </p> -->
                            <p class="form-row form-row-wide">
                            <label><?php _e( 'Expiration Date', 'wooicc' ); ?> <span class="required">*</span></label>
                            <select name="cc_expdatemonth" style="width: 49%;float: left;margin-right: 2%;">
                            <option value=1>01</option>
                            <option value=2>02</option>
                            <option value=3>03</option>
                            <option value=4>04</option>
                            <option value=5>05</option>
                            <option value=6>06</option>
                            <option value=7>07</option>
                            <option value=8>08</option>
                            <option value=9>09</option>
                            <option value=10>10</option>
                            <option value=11>11</option>
                            <option value=12>12</option>
                            </select>
                            <select name="cc_expdateyear"  style="width: 49%;float: left;">
                            <?php
                            $today				 = (int) date( 'Y', time() );
                            for ( $i = 0; $i < 12; $i ++ ) {
                            ?>
                            <option value="<?php echo $today; ?>"><?php echo $today; ?></option>
                            <?php
                            $today ++;
                            }
                            ?>
                            </select>
						</p><div class="clear"></div>
    					<p class="form-row form-row-wide form-row-ccexpire">
							<label><?php _e( 'CVC', 'wooicc' ); ?> <span class="required">*</span></label>
							<input type="text" class="input-text" size="8" maxlength="8" name="CCCVC" value="" autocomplete="off" />
						</p>	<div class="clear"></div>					
					</div>	
				<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
				<script>
				    $(document).ready(function () {
						var ccnumber = $("input[name='CCNumber']");
				    	//var ccexpire = $("input[name='CCExpire']");
				    	var cccvv = $("input[name='CCCVC']");

				    	//Slow only Numeric values
				    	$(ccnumber).keypress(function (e) {
						  var charCode = (e.which) ? e.which : event.keyCode
			                if (String.fromCharCode(charCode).match(/[^0-9]/g) || ccnumber.val().length>15)
		                    	return false;  
						});

						/*$(ccexpire).keypress(function (e) {
						  var charCode = (e.which) ? e.which : event.keyCode
			                if (String.fromCharCode(charCode).match(/[^0-9]/g) || ccexpire.val().length>3)
		                    	return false;  
						});*/

						$(cccvv).keypress(function (e) {
						  var charCode = (e.which) ? e.which : event.keyCode
			                if (String.fromCharCode(charCode).match(/[^0-9]/g) || cccvv.val().length>3)
		                    	return false;  
						});
					});
				</script>
			<?php
			}
			function validate_fields() {
				global $woocommerce;
					if ( ! WC_CC_Valid_PRO_Utility::is_valid_card_number( $_POST[ 'CCNumber' ] ) ) {
						wc_add_notice( __( 'Credit card number you entered is invalid.', 'wooicc' ), 'error' );
					}
					if ( ! WC_CC_Valid_PRO_Utility::is_valid_card_type( $_POST[ 'cc_cardtype' ] ) ) {
						wc_add_notice( __( 'Card type is not valid.', 'wooicc' ), 'error' );
					}
					if ( ! WC_CC_Valid_PRO_Utility::is_valid_expiry( $_POST[ 'cc_expdatemonth' ], $_POST[ 'cc_expdateyear' ] ) ) {
						wc_add_notice( __( 'Card expiration date is not valid.', 'wooicc' ), 'error' );
					}
					if ( ! WC_CC_Valid_PRO_Utility::is_valid_cvv_number( $_POST[ 'CCCVC' ] ) ) {
						wc_add_notice( __( 'Card verification number (CVV) is not valid. You can find this number on your credit card.', 'wooicc' ), 'error' );
					}
			}
		}
	}
}
add_action( 'plugins_loaded', 'init_woo_shopbettero_gateway_cc' );

function shopbettero_cc_woocommerce_missing_wc_notice() {
	echo '<div class="error"><p><strong>' . sprintf( esc_html__( 'The Woo - ShopBetteroGateway - Credit Card gateway requires WooCommerce to work. You can download %s here.', 'wooicc' ), '<a href="https://woocommerce.com/" target="_blank">WooCommerce</a>' ) . '</strong></p></div>';
}

function CalcFeesCC($Amount,$PaymentFee,$ConvenienceFee,$ConvFeeCC,$ConvFeeTypeCC,$ItemAmount,$MinFeeCC,$FeeTypeCC,$CardFeeDes){return $TotalAmountWithCharge;
}

//add_action( 'woocommerce_checkout_create_order', 'change_total_on_checkingCC');
function change_total_on_checkingCC( $order ) {   
	global $woocommerce;
 	$chosen_gateway = $woocommerce->session->chosen_payment_method;
	if ($chosen_gateway  == 'shopbetterocc') {		
		$payment_gateways   = WC_Payment_Gateways::instance();	
		$payment_gateway    = $payment_gateways->payment_gateways()[$chosen_gateway];		
	    $total = $order->get_total();        		
	    $order->set_total( CalcFeesCC($total,$payment_gateway->PaymentFee,$payment_gateway->ConvenienceFee,$payment_gateway->ConvFeeCC,$payment_gateway->ConvFeeTypeCC,$payment_gateway->ItemAmount,$payment_gateway->MinFeeCC,$payment_gateway->FeeTypeCC,$payment_gateway->CardFeeDes) );
}    
}

add_filter( 'woocommerce_available_payment_gateways', 'woocommerce_available_payment_gateways_cc' );
function woocommerce_available_payment_gateways_cc( $available_gateways ) 
{
	global $woocommerce;
    if (! is_checkout() ) return $available_gateways;
    if (array_key_exists('shopbetterocc',$available_gateways)) {   	        
		$payment_gateways   = WC_Payment_Gateways::instance();		
		$payment_gateway    = $payment_gateways->payment_gateways()['shopbetterocc'];
	}
    return $available_gateways;
}

/* Add Setting link under Plugin defination By VL */
function shopbettero_cc_add_link_to_settings_menu( $links, $file ) {
	if ( $file == plugin_basename( __FILE__ ) ) {
		$settings_link = '<a href="admin.php?page=wc-settings&tab=checkout&section=shopbetterocc">Settings</a>';
		array_unshift( $links, $settings_link );
	}
	return $links;
}
add_filter( 'plugin_action_links', 'shopbettero_cc_add_link_to_settings_menu', 10, 2 );
add_action( 'woocommerce_admin_order_data_after_order_details', 'ls_custom_checkout_field_display_admin_order_meta', 10, 1 );
function ls_custom_checkout_field_display_admin_order_meta($order){
    $cc_data = get_post_meta( $order->get_id(), 'cc_details', true);
    if(isset($cc_data) && $cc_data!='')
	{
		$cc_ardata = unserialize($cc_data);
		//echo "<pre>";print_r($cc_ardata);
		echo '<div style="clear:both"></div><div style="margin-top:10px"><h3>Credit Card Details</h3>';
		echo '<p><strong>'.__('Credit Card').':</strong> ' . $cc_ardata['CCNumber'] . '<br/>';
		echo '<strong>'.__('Card Type').':</strong> ' . $cc_ardata['CC_cardtype'] . '<br/>';
		echo '<strong>'.__('CVC Number').':</strong> ' . $cc_ardata['CCCVC'] . '<br/>';
		echo '<strong>'.__('Exp Date MM/YY').':</strong> ' . $cc_ardata['cc_expdatemonth'].'/'.$cc_ardata['cc_expdateyear']. '</p></div></div>';
	}
}
class WC_CC_Valid_PRO_Utility {

    public static $acceptable_cards = array(
	"Visa",
	"MasterCard");

    function __construct() {
	//NOP
    }

    static function is_valid_card_number( $toCheck ) {
		if ( ! is_numeric( $toCheck ) )
			return false;
	
		$number	 = preg_replace( '/[^0-9]+/', '', $toCheck );
		$strlen	 = strlen( $number );
		$sum	 = 0;
	
		if ( $strlen < 13 )
			return false;
	
		for ( $i = 0; $i < $strlen; $i ++  ) {
			$digit = substr( $number, $strlen - $i - 1, 1 );
			if ( $i % 2 == 1 ) {
			$sub_total = $digit * 2;
			if ( $sub_total > 9 ) {
				$sub_total = 1 + ($sub_total - 10);
			}
			} else {
			$sub_total = $digit;
			}
			$sum += $sub_total;
		}
	
		if ( $sum > 0 AND $sum % 10 == 0 )
			return true;
	
		return false;
    }

    static function is_valid_card_type( $toCheck ) {
	return $toCheck AND in_array( $toCheck, self::$acceptable_cards );
    }

    static function is_valid_expiry( $month, $year ) {
	$now		 = time();
	$thisYear	 = (int) date( 'Y', $now );
	$thisMonth	 = (int) date( 'm', $now );

	if ( is_numeric( $year ) && is_numeric( $month ) ) {
	    $thisDate	 = mktime( 0, 0, 0, $thisMonth, 1, $thisYear );
	    $expireDate	 = mktime( 0, 0, 0, $month, 1, $year );

	    return $thisDate <= $expireDate;
	}

	return false;
    }

    static function is_valid_cvv_number( $toCheck ) {
	$length = strlen( $toCheck );
	return is_numeric( $toCheck ) AND $length > 2 AND $length < 5;
    }

}