<?php
//SHortcode for Newsletter form
add_shortcode('newsletter_form','newsletter_form');
function newsletter_form($atts){
    extract(shortcode_atts(array('page'=>'', 'btn_text' => 'Subscribe now','btn_class'=>'ls-border-btn'),$atts));
    ob_start();
	$btn_id='ls_newsleterrbtn';
	if(isset($page) && $page!='' && $page=='5poff')
		$btn_id = 'ls_5poff'
	
    //After use of sendin blue, removed action, 2 hidden field of emailoctopus
    ?>
    <div class="emailoctopus-form-wrapper newsletter-frm">
    <form method="post" action="#" class="emailoctopus-form" id="sendin_newsltrfrm">
        <div class="emailoctopus-form-copy-wrapper ls-newsletter-form">
        	<?php if(isset($page) && $page!='home'){ ?>
            <div class="frm-name"><input type="text" name="FirstName" class="emailoctopus-custom-fields" tabindex="100" placeholder="Name *"></div>
            <?php } ?>
            <?php if(isset($page) && $page=='giveaway'){ ?>
            <div class="frm-twitter"><input type="text" name="nsf_twitter" class="emailoctopus-custom-fields" tabindex="100" placeholder="Twitter handle *"></div>
            <?php } ?>
            <div class="frm-email"><input type="text" name="EmailAddress" class="emailoctopus-custom-fields" tabindex="101" placeholder="Email address *"></div>
            <div class="frm-data"><input type="hidden" name="p" value="<?php echo $page; ?>"/></div>
            <div class="emailoctopus-form-row-subscribe frm-btn">
                <button type="submit" tabindex="102" style="" class="<?php echo $btn_class ?>" id="<?php echo $btn_id;?>" ls_text="<?php _e('Subscribe now', 'nm-framework'); ?>"><?php echo $btn_text; ?></button>
            </div>
        </div>
        <p class="emailoctopus-success-message"></p>
        <p class="emailoctopus-error-message"></p>
    </form>
    </div>
    <?php
    return ob_get_clean();
}

function newsletter_form_submit(){
    $name = $_REQUEST['name'];
    $email = $_REQUEST['email'];
	$twitter = $_REQUEST['twitter'];
	$pg = $_REQUEST['p'];
	$email = strtolower($email);
	$emailval = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/';
	if(isset($email) && $email!='' && preg_match($emailval, $email))
    {
		$list_id = 19;
		if(isset($pg) && $pg!='')
		{
			if($pg=='intimacy')
                $list_id = 30;
            else if($pg=='calendar')
                $list_id = 40;
        }
		
		$res = sendto_SendInBlue($list_id,$email,$name,'',$twitter); // 3 List ID
		$status = true;
	}
	else{
		$err = 'Wrong email id';
		$status = false;
	}
    echo  json_encode(array('status'=>$status,'err'=>$err));
    die();
}
add_action( 'wp_ajax_newsletter_form_submit', 'newsletter_form_submit' );
add_action( 'wp_ajax_nopriv_newsletter_form_submit', 'newsletter_form_submit' );


function sendto_SendInBlue($ListID,$Email,$Fname, $Lname,$LTwitter)
{
$curl = curl_init();
curl_setopt_array($curl, array(
  CURLOPT_URL => "https://api.sendinblue.com/v3/contacts",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  //CURLOPT_POSTFIELDS => "{\"attributes\":{\"FIRSTNAME\":\"Vijay\",\"LASTNAME\":\"Lathiya\"},\"listIds\":[2],\"updateEnabled\":false,\"email\":\"lathiyavijay.1@gmail.com\"}",
  CURLOPT_POSTFIELDS => json_encode([
        'email' => $Email,
        'listIds' => [$ListID],
        'updateEnabled' => true,
        'attributes' => ['FIRSTNAME' => $Fname,'LASTNAME' => $Lname,'TWITTER' => $LTwitter],
		]),
		
  CURLOPT_HTTPHEADER => array(
    "accept: application/json",
    "api-key: {API-KEY}",
    "content-type: application/json"),
));

$response = curl_exec($curl);
//$err = curl_error($curl);
curl_close($curl);
//if ($err) {echo "cURL Error #:" . $err;} else {echo $response;}
}
?>
<script type="text/javascript">
	jQuery(document).ready(function(){
var ios_devices = user_agent.match(/(iphone|ipod|ipad)/)  ? "touchstart" : "click";
 jQuery("#ls_newsleterrbtn,#ls_btnwinoffer,#ls_5poff").bind(ios_devices, function(){
			var name = jQuery(this).parents("form").find(".frm-name input");
			var email = jQuery(this).parents("form").find(".frm-email input");
			
			var name_val = jQuery(this).parents("form").find(".frm-name input").val();
			var email_val = jQuery(this).parents("form").find(".frm-email input").val();
			var req_frm = jQuery(this).parents("form").find(".frm-data input").val();
          	jQuery(this).parents("form").find(".ls_frmmsg").remove();
			
			 if(name_val==''){
				jQuery("<div class='ls_frmmsg'><div class='ls_error'>Your name is required.</div></div>").appendTo(jQuery(this).parents("form")); 
				return false;
			 }
				 
			var twitter_val ='';
			if(jQuery(this).parents("form").find(".frm-data input").val()=='giveaway')
			{
				var twitter_val = jQuery(this).parents("form").find(".frm-twitter input").val();
				
				 if(twitter_val==''){
					jQuery("<div class='ls_frmmsg'><div class='ls_error'>Your Twitter handle is required.</div></div>").appendTo(jQuery(this).parents("form")); 
					return false;
				 }
			}
		
		  if(email_val==''){
            jQuery("<div class='ls_frmmsg'><div class='ls_error'>Your email address is required.</div></div>").appendTo(jQuery(this).parents("form"));
            return false;
          }

          jQuery.ajax({
                  url: ajaxurl,
                  data: {
                      action: 'newsletter_form_submit',
                      name: name_val,
                      email: email_val,
					  twitter: twitter_val,
					  p: jQuery(this).parents("form").find(".frm-data input").val(),
                      },
                  method: "POST",
                  dataType: "JSON",
				  beforeSend: function(){ if(req_frm=='5poff'){jQuery('#ls_5poff').html('Processing...');}},
                  success: function( response ){
                    if( response.status ){
						//jQuery("#sendin_newsltrfrm")[0].reset() 
						//jQuery("<div class='ls_frmmsg'><div class='ls_success'>Thanks for subscribing!</div></div>").appendTo("#sendin_newsltrfrm");
						
						if(req_frm=='winoffer2')
						{
							jQuery("#sendin_winfrm")[0].reset(); 
							jQuery("<div class='ls_frmmsg'><div class='ls_success'>Thanks for subscribing!</div></div>").appendTo("#sendin_winfrm");
							setTimeout(function(){jQuery('.winoffer').find('.ls_close').click();}, 1000);
						}
						else if(req_frm=='5poff')
						{
							jQuery('#ls_5poff').html('Get it now!');
							jQuery("#sendin_newsltrfrm")[0].reset();
							jQuery("<div class='ls_frmmsg'><div class='ls_success'>Yay! Check your inbox for the surprise.</div></div>").appendTo("#sendin_newsltrfrm"); 
							jQuery('#pc5offform').css("display", "none");
							jQuery('.pc5offsuccess').css("display", "block");
						}
						else
						{
							jQuery("#sendin_newsltrfrm")[0].reset();
							jQuery("<div class='ls_frmmsg'><div class='ls_success'>Thanks for subscribing!</div></div>").appendTo("#sendin_newsltrfrm"); 
						}
					}
					else
					{
						//jQuery("<div class='ls_frmmsg'><div class='ls_error'>Something wrong, Please check Email Field</div></div>").appendTo(jQuery(this).parents("form"));
						if(req_frm=='winoffer2')
							jQuery("<div class='ls_frmmsg'><div class='ls_error'>Something wrong, Please check Email Field</div></div>").appendTo("#sendin_winfrm");
						else if(req_frm=='5poff')
						{
							jQuery('#ls_5poff').html('Get it now!');
							jQuery("<div class='ls_frmmsg'><div class='ls_error'>Something wrong, Please check Email Field</div></div>").appendTo("#sendin_newsltrfrm"); 
						}
						else
							jQuery("<div class='ls_frmmsg'><div class='ls_error'>Something wrong, Please check Email Field</div></div>").appendTo("#sendin_newsltrfrm"); 
					}
                      
                  }
              });
      
          return false;

        });
      
    });
		</script>