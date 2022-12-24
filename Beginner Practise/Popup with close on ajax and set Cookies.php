<?php
// popup code

//other Site Link Popup
add_action('wp_footer', 'ls_other_site_popup');

function ls_other_site_popup(){

	if(!isset($_COOKIE['ls_othersitepupck']) && is_front_page()){
		echo '<div class="ls_popupmain" id="other_site_redirect">
			<div class="ls_popupbg"></div>
			<div class="ls_popupcntnt">
				<div class="ls_popupinr">
					<div class="ls_row">
						<div class="site_col">
							<div class="img"><img src="'. get_site_url() .'/wp-content/uploads/2020/06/3s-scanners-img.png" /></div>
							<div class="cntnt">
								<div class="cntnt_inr">
								<h3 class="title">'. __('3D Scanners', 'divi'). '</h3>
								<div class="desc">'. __('Desktop 3D Scanner, Handheld 3D Scanner, 3D Face Scanner and the Applications', 'divi'). '</div>
								</div>
							</div>
							<a href="javascript:void(0);" class="box_link samsite"></a>
						</div>
						<div class="site_col">
							<div class="img"><img src="'. get_site_url() .'/wp-content/uploads/2020/06/3d-camera-image.png" /></div>
							<div class="cntnt">
								<div class="cntnt_inr">
								<h3 class="title">'. __('3D Cameras', 'divi').'</h3>
								<div class="desc">'. __('High-precision 3D Camera and Module, Industrial 3D Cameras and the Applications', 'divi').'</div>
								</div>
							</div>
							<a href="https://3dcamera.revopoint3d.com" class="box_link"></a>
						</div>
					</div>
				</div>
				<a href="javascript:void(0);" class="ls_popupclose"><img src="'. get_site_url() .'/wp-content/uploads/2020/06/close-icon-img.png" /></a>
			</div>
		</div>';
		
		?>
		<script type="text/javascript">
	jQuery(document).ready(function(){
		//LS Custom Other site link Popup
		var ls_ajaxurl = '<?php echo admin_url( 'admin-ajax.php' ); ?>';
		jQuery(".ls_popupmain#other_site_redirect .ls_popupclose").click(function(){
			jQuery(this).parents(".ls_popupmain").addClass("close");
				jQuery.ajax({
		    		url:ls_ajaxurl,
		    		data:{
		    			action: 'ls_setcookie_othersitepopup',
		    		},
		    		method: "POST",
		    		dataType: "JSON",
		    		success: function( response ){}
		    	});	
		});
		jQuery(".samsite").click(function(){
			jQuery(this).parents(".ls_popupmain").addClass("close");
				jQuery.ajax({
		    		url:ls_ajaxurl,
		    		data:{
		    			action: 'ls_setcookie_othersitepopup',
		    		},
		    		method: "POST",
		    		dataType: "JSON",
		    		success: function( response ){//location.reload(true);
					}
		    	});	
		});
    });
</script>
		<?php
	
	}
}
//ls custom set cookie for USA site Popup
function ls_setcookie_othersitepopup(){
	
	setcookie('ls_othersitepupck', 'ls_othersitepupckk_val', time() + (86400), "/"); // 86400 = 1 day
	
	$ls_cookie = $_COOKIE['ls_othersitepupck'];
	if(isset($_COOKIE['ls_othersitepupck']))
		$status = 1;
	else
		$status = 0;

	echo json_encode(array('status'=>$status));
	die();
}
add_action('wp_ajax_ls_setcookie_othersitepopup', 'ls_setcookie_othersitepopup');
add_action('wp_ajax_nopriv_ls_setcookie_othersitepopup', 'ls_setcookie_othersitepopup');


?>