<?php
add_action( 'init', 'ls_register_post_types' ); 
function ls_register_post_types() { 
register_post_type( 'tutorial', 
					array( 'labels' => array(
								'name' => 'Tutorial',
								'singular_name' => 'tutorial'
						),
						'public'      => true,
						'has_archive' => true,
						'rewrite'     => array('slug' => 'tutorials'),
						'supports'    => array('title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'revisions', 'custom-fields', 'page-attributes', 'post-formats'),
										'can_export'  => true,
						)
					);
register_taxonomy('tutorial_cat', 'tutorial', array('hierarchical' => true, 'label' => 'Categories', 'query_var' => true, 'rewrite' =>array( 'slug' => 'cat-tutorial' )));

//ls custom Event CPT
register_post_type( 'cp_events', 
					array( 'labels' => array( 'name' => 'Events', 'singular_name' => 'event' ),
							'public'      => true,
							'has_archive' => true,
							'rewrite'     => array('slug' => 'event','with_front' => true),
							'supports'    => array('title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'revisions', 'custom-fields', 'page-attributes', 'post-formats'),
							'can_export'  => true,
							'menu_position' => 25,
							'menu_icon'     => 'dashicons-calendar-alt',
							'publicly_queryable' => false,
							'query_var' => false,
							'register_meta_box_cb' => 'ls_add_metaboxes', //for metabox
						));

register_taxonomy('events_cat', 'cp_events', array('hierarchical' => true, 'label' => 'Categories', 'query_var' => true, 'rewrite' =>array( 'slug' => 'cat-event' )));

}

/*----- Metabox start here -----*/
function ls_add_metaboxes() {
   add_meta_box( 'ls_event_metabox', 'Event Detail', 'ls_show_custom_meta_box', 'cp_events', 'normal', 'high');
}
add_action('add_meta_boxes', 'ls_add_metaboxes');

function ls_show_custom_meta_box($post) {
    global $post;

    // Use nonce for verification to secure data sending
    wp_nonce_field( basename( __FILE__ ), 'lsmf_our_nonce' );

    $ecountry = get_post_meta($post->ID, 'ecountry', true) ? get_post_meta($post->ID, 'ecountry', true) : '';
    $estartdate = get_post_meta($post->ID, 'estartdate', true) ? get_post_meta($post->ID, 'estartdate', true) : '';
    $eenddate = get_post_meta($post->ID, 'eenddate', true) ? get_post_meta($post->ID, 'eenddate', true) : '';
    $evenue = get_post_meta($post->ID, 'evenue', true) ? get_post_meta($post->ID, 'evenue', true) : '';
    $eexhibitor = get_post_meta($post->ID, 'eexhibitor', true) ? get_post_meta($post->ID, 'eexhibitor', true) : '';
    $ebooth = get_post_meta($post->ID, 'ebooth', true) ? get_post_meta($post->ID, 'ebooth', true) : '';
    $elink = get_post_meta($post->ID, 'elink', true) ? get_post_meta($post->ID, 'elink', true) : '';

    ?>
    <style>
    	.ls_row{ width: 100%; display: inline-block; margin-bottom: 10px; }
    	.ls_row .col-md-6{ width: 47%; float: left; padding: 15px; }
    	.ls_row .form-control{ width: 100%; margin-top: 5px; border-radius: 0; border-color: #cccccc; }
    	.ls_row .fielddesc { color: #929191; font-size: 12px; font-style: italic; }
    	@media(max-width: 992px){
    		.ls_row .col-md-6{ width: 100%; }	
    	}
    </style>
    <div class="ls_row">
    	<div class="col-md-6">
    		<div class="ls_row">
		    	<div>Venue :</div><input type="text" name="evenue" class="form-control" value="<?php echo $evenue; ?>" placeholder="e.g. Las Vegas" />
		    </div>
		    <div class="ls_row">
		    	<div>Country :</div><input type="text" name="ecountry" class="form-control" value="<?php echo $ecountry; ?>" placeholder="e.g. USA"/>
		    </div>
		    <div class="ls_row">
		    	<div>Exhibitor :</div><input type="text" name="eexhibitor" class="form-control" value="<?php echo $eexhibitor; ?>" placeholder="e.g. Revopoint 3d"/>
		    </div>
		    <div class="ls_row">
		    	<div>Booth :</div><input type="text" name="ebooth" class="form-control" value="<?php echo $ebooth; ?>" placeholder="e.g. 31818, South Hall 3"/>
		    </div>
    	</div>
    	<div class="col-md-6">
    		<div class="ls_row">
		    	<div>Start Date :</div><input type="datetime-local" name="estartdate" class="form-control" value="<?php echo $estartdate; ?>"/>
		    </div>
		    <div class="ls_row">
		    	<div>End Date :</div><input type="datetime-local" name="eenddate" class="form-control" value="<?php echo $eenddate; ?>"/>
		    </div>
		    <div class="ls_row">
		    	<div>Event Link :</div><input type="url" name="elink" class="form-control" value="<?php echo $elink; ?>" placeholder="https://example.com/christmas-party"/>
		    </div>
    	</div>
    </div>
    <?php
}
//save meta field
function ls_save_cmeta_fields( $post_id ) {

  if (!isset($_POST['lsmf_our_nonce']) || !wp_verify_nonce($_POST['lsmf_our_nonce'], basename(__FILE__))) return 'nonce not verified'; // verify nonce

  if ( wp_is_post_autosave( $post_id ) ) return 'autosave'; // check autosave

  if ( wp_is_post_revision( $post_id ) ) return 'revision'; //check post revision

  // check permissions
  if ( 'cp_events' == $_POST['post_type'] ) {
      if ( ! current_user_can( 'edit_page', $post_id ) )
          return 'cannot edit page';
      } elseif ( ! current_user_can( 'edit_post', $post_id ) ) {
          return 'cannot edit post';
  }

  //save Custom Meta Field
  if( isset($_POST['evenue']) ) update_post_meta($post_id, 'evenue', $_POST['evenue']);             // Venue
  if( isset($_POST['ecountry']) ) update_post_meta($post_id, 'ecountry', $_POST['ecountry']);       // country
  if( isset($_POST['eexhibitor']) ) update_post_meta($post_id, 'eexhibitor', $_POST['eexhibitor']); // Exhibitor
  if( isset($_POST['ebooth']) ) update_post_meta($post_id, 'ebooth', $_POST['ebooth']);             // Booth
  if( isset($_POST['estartdate']) ) update_post_meta($post_id, 'estartdate', $_POST['estartdate']); // Startdate
  if( isset($_POST['eenddate']) ) update_post_meta($post_id, 'eenddate', $_POST['eenddate']);       // Enddate
  if( isset($_POST['elink']) ) update_post_meta($post_id, 'elink', $_POST['elink']);                // Link
  

}
add_action( 'save_post', 'ls_save_cmeta_fields' );
add_action( 'new_to_publish', 'ls_save_cmeta_fields' );
/*----- Metabox over here -----*/

//ls custom shortcode for Events
add_shortcode('ls_events', 'ls_events_func');
function ls_events_func($atts){

	extract( shortcode_atts(array('title'=>'', 'past_events'=>false, 'posts_per_page'=>'-1'), $atts) );
	ob_start();

	global $wp_query;

	if($past_events){
		$order = 'DESC';

		if($title=='') $title = __('Past Events', 'avada');
		$stsclass = 'previous_events';
	}
	else{
		$order = 'ASC';

		if($title=='') $title = __('Events', 'avada');
		$stsclass = 'coming';
	}

	$equery = new WP_Query( array('post_type'=>'cp_events', 'posts_per_page' => $posts_per_page, 'meta_key' => 'estartdate', 'orderby' => 'meta_value', 'order' => $order) );
	

	$content = '';
	
	$content .= '<div class="ls_events '. $stsclass .'">';
	$content .= '<div class="title">'. $title .'</div>';

	$i = 0;

	if($equery->have_posts()) :
		while($equery->have_posts()) : $equery->the_post();

			$event_id = get_the_ID();

			$venue = get_post_meta($event_id, 'evenue', true);
			$country = get_post_meta($event_id, 'ecountry', true);
			$exhibitor = get_post_meta($event_id, 'eexhibitor', true);
			$booth = get_post_meta($event_id, 'ebooth', true);
			$link = get_post_meta($event_id, 'elink', true);
			$sdate = get_post_meta($event_id, 'estartdate', true);
			$edate = get_post_meta($event_id, 'eenddate', true);

			
			$ls_sdstrtime = strtotime($sdate);
			$ls_edstrtime = strtotime($edate);

	        $ls_sctime = date("Y-m-d G:i:s");
	        $current_time = strtotime($ls_sctime);

			if($past_events){
				if( $current_time<=$ls_edstrtime){
					continue;
				}
				$i++;
			}
			else{
				if($current_time>=$ls_edstrtime){
					continue;
					
				}
				$i++;
			}

			$class = '';
			$img_sec = '';
			if( has_post_thumbnail($event_id) ){

				$atch_id = get_post_thumbnail_id($event_id);
				$atchment = wp_get_attachment_image_src( $atch_id );
				$class = 'col-md-5';
				$img_sec = '<div class="'. $class .' img"><img src="'. $atchment[0] .'" /></div>';
				$dclass= 'col-md-7';
			}
			else
				$class = $dclass ='col-md-12';

			$content .= '<article class="ls_event post"><div class="post_inr">';
					$content .= '<div class="ecountry">'. $country .'</div>
						  <div class="post_content">
						  <div class="date">'. date('M', $ls_sdstrtime) .'. '. date('j', $ls_sdstrtime) .' - '. date('j', $ls_edstrtime) .', '. date('Y', $ls_sdstrtime) .'</div>
						  <div class="ptitle">'. get_the_title($event_id) .'</div>';

					$content .= '<div class="ls_row">
							'.$img_sec.'
							<div class="'. $dclass .' desc">'. get_the_content($event_id).'
							<div class="event_meta">';

							if($venue) $content .= '<div class="venue"><span class="title">'. __('Venue') .' : </span> '. $venue .'</div>';
							if($exhibitor) $content .= '<div class="exhibitor"><span class="title">'. __('Exhibitor') .' : </span> '. $exhibitor .'</div>';
							if($booth) $content .= '<div class="booth"><span class="title">'. __('Booth') .' : </span> '. $booth .'</div>';

						$content .= '</div>
						</div>
						</div>
				</div>';
			if($link) $content .= '<div class="linkbtn"><a href="'. $link .'" target="_blank">'. __('Read more') .'</a></div>';

			$content .= '</div></article>';
			
		endwhile;
		wp_reset_postdata();


	else :
		$content .= '<div>'. __('No Events found') .'</div>';

	endif;


	if($past_events && $i<1){
		$content .= '<div>'. __('No Events found') .'</div>';
	}

	$content .= '</div>';	
	$content .= ob_get_clean();

	return $content;
}
?>