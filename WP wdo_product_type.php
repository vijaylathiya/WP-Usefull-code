<?php
/* LS Custom Add / Quick Edit Product badge field at backend listing with Ajax call*/

add_filter( 'manage_edit-product_columns', 'misha_brand_column', 20 );
function misha_brand_column( $columns_array ) {
 
	// I want to display Brand column just after the product name column
	return array_slice( $columns_array, 0, 4, true )
	+ array( 'ls_ctype' => __( 'Type' ) )
	+ array_slice( $columns_array, 4, NULL, true );
	
}
add_action( 'manage_product_posts_custom_column', 'ls_manage_product_posts_custom_column', 10, 2 );
function ls_manage_product_posts_custom_column( $column, $post_id ) {

	  if ( 'ls_ctype' === $column ) {
	  	$c_type = get_post_meta( $post_id, 'c_type', true );
	  	//$pbadge_id = $badge_ar['id_badge'];
	  	?>
	  		<style>
	  			table.wp-list-table .column-sku { width: 5%; }
	  		</style>

	  		<select class="select pbadgesel" pid="<?php echo $post_id; ?>" id="pbadgesel_<?php echo $post_id; ?>">
				<option value="0" <?php selected( $c_type,0); ?>>Paid</option>
                                <option value="1" <?php selected( $c_type,1); ?>>Free</option>
			</select>
                        <div class="loadingimg" style="display: none; text-align: center;"><img src="https://media4.giphy.com/media/xTk9ZvMnbIiIew7IpW/giphy.gif" width="25px" /></div>
                        
			<div class="msg" style="text-align: center;"></div>
	  	<?php
	  	
	  }
}
//edit product badge
add_action('wp_ajax_ls_change_pbadge', 'ls_change_pbadge');
add_action('wp_ajax_nopriv_ls_change_pbadge', 'ls_change_pbadge');
function ls_change_pbadge(){

	ob_start();
	$pid = $_POST['pid'];
	$badgeid = $_POST['val'];

	$status = false;
	
	
	$c_type = absint($badgeid);
		
	if(update_post_meta( $pid, 'c_type', $c_type )){
		$status = true;
		$msg = 'Type Updated';
	}

	ob_get_clean();
    echo json_encode(array('status'=>$status,'msg'=>$msg));
    die();
}

//script
//add JS to footer that updates quick edit dropdown:
add_action('admin_footer', 'ls_pbadge_quick_edit_JS');
function ls_pbadge_quick_edit_JS() {
    global $current_screen;
    if(($current_screen->id != 'edit-product') || ($current_screen->post_type != 'product')) return;
     
    ?>
    <script type="text/javascript">var ls_admnajaxurl = "<?php echo admin_url('admin-ajax.php'); ?>";</script>
    <script type="text/javascript">

    jQuery(document).ready(function(){
    	jQuery(".pbadgesel").change(function(){
    		var this_val = jQuery(this).val();
    		var pid = jQuery(this).attr("pid");

    		jQuery(this).next(".loadingimg").show();
    		jQuery("#pbadgesel_"+pid).parents(".ls_pbadge").find(".msg").html("");
    		jQuery.ajax({
            	url: ls_admnajaxurl,
                data: {
                	action: 'ls_change_pbadge',
                	pid: pid,
                	val: this_val,
                },
                method: "POST",
                dataType: "JSON",
                success: function( response ){
                	
                	jQuery("#pbadgesel_"+pid).next(".loadingimg").hide();

                	if(response.status)
                		jQuery("#pbadgesel_"+pid).parents(".ls_pbadge").find(".msg").html("<div style='color: green;'>"+response.msg+"</div>");

                }
	        });

    	});
    });
    </script>
    <?php
}
function wdo_meta_box_markup($object)
{
wp_nonce_field(basename(__FILE__), "meta-box-nonce");
$c_ctype=get_post_meta($object->ID, "c_type", true);
?><div>
<label for="meta-box-text">Type : </label>
    <select name="ctype-box-text" class="select pbadgesel" pid="<?php echo $post_id; ?>" id="pbadgesel_<?php echo $post_id; ?>">
	<option value="0" <?php selected( $c_ctype,0); ?>>Paid</option>
        <option value="1" <?php selected( $c_ctype,1); ?>>Free</option>
    </select>
</div>
<?php
$button_type=get_post_meta($object->ID, "p_button_type", true);
?>
<div style="margin-top:10px">
	<label for="meta-box-text">Free Tickets : </label>
    <select name="drd_freetickets" class="select" id="ft_<?php echo $post_id; ?>">
		<option value="0" <?php selected( $button_type,0); ?>>No</option>
        <option value="1" <?php selected( $button_type,1); ?>>Yes</option>
    </select>

</div>
<?php
$btn_type=get_post_meta($object->ID, "q_btn_type", true);
?>
<div style="margin-top:10px">
	<label for="meta-box-text">Button Type : </label>
    <select name="btn_type" class="select" id="bty_<?php echo $post_id; ?>">
		<option value="0" <?php selected( $btn_type,0); ?>>Default</option>
        <option value="1" <?php selected( $btn_type,1); ?>>Any Qty 5,10,20,50</option>
        <option value="2" <?php selected( $btn_type,2); ?>>Any Qty 1,2,5,10</option>
    </select>

</div>
<?php
}
function wdo_custom_meta_box(){add_meta_box("wdo-meta-box", "Competition Type", "wdo_meta_box_markup", "product", "side", "high", null);}
add_action("add_meta_boxes", "wdo_custom_meta_box");

function wdo_save_custom_meta_box($post_id, $post, $update)
{
	if (!isset($_POST["meta-box-nonce"]) || !wp_verify_nonce($_POST["meta-box-nonce"], basename(__FILE__)))
		return $post_id;
	
	if(!current_user_can("edit_post", $post_id))
		return $post_id;
	
	if(defined("DOING_AUTOSAVE") && DOING_AUTOSAVE)
		return $post_id;
	
	$slug = "product";
	if($slug != $post->post_type)
		return $post_id;
	
	$meta_box_text_value = "";
	if(isset($_POST["ctype-box-text"]))
		$meta_box_text_value = $_POST["ctype-box-text"];
	update_post_meta($post_id, "c_type", $meta_box_text_value);
	
	$pbt = "0";
	if(isset($_POST["drd_freetickets"]))
		$pbt = $_POST["drd_freetickets"];
	update_post_meta($post_id, "p_button_type", $pbt);
	
	$qbt = "0";
	if(isset($_POST["btn_type"]))
		$qbt = $_POST["btn_type"];
	update_post_meta($post_id, "q_btn_type", $qbt);
}
add_action("save_post", "wdo_save_custom_meta_box", 10, 3);
