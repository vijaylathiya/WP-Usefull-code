<?php
add_shortcode('wdo_property', 'fn_get_wdo_property'); 
function fn_get_wdo_property($atts){
	extract(shortcode_atts(array(
		'is_avail_only'  => '',
		'post_per_page'  => '12',
		'category_slug'  => '',
		'offset'=>'',
        'limit'  => '',
        'title_excerpt'=>'',
        'post_id'=>'',
	),$atts));

	ob_start();
	
			$arg = array( 	 'post_type' => 'properties',
                			'post_status' => 'publish',
                            'posts_per_page'=>$post_per_page,
                            'orderby'=>'date', 
                            'update_post_term_cache' => false,
                            'update_post_meta_cache' => false,
                            'order'=>'DESC', 
                        );

			if($is_avail_only!='')
			$arg['meta_query'] = array(
                    array(
                        'key'     => 'ls_is_available',
                        'value'   => $is_avail_only,
                        'compare' => 'LIKE',
                ));


	    $query = new WP_Query();

  	$list = '<ul id="nm-blog-grid-ul" class="ls-recent-post ls_post">';
	while($query->have_posts()) : $query->the_post();
		$comments_count = wp_count_comments(get_the_ID());
        $cats = get_the_category(get_the_ID());

        $view_count = get_post_meta(get_the_id(), 'ls_post_views_count', true);
        
		$list .= '<li class="col-md-'. $post_per_page .'"><div class="ls-post-box"><div class="entry-content">';

        //if sidebar then change image size
        if($in_sidebar == 'yes')
            $list .= '<div class="nm-post-thumbnail"><a href="'. get_the_permalink(). '">'. get_the_post_thumbnail(get_the_ID(), 'sidebar_feature') .'</a></div>';
        else
		  $list .= '<div class="nm-post-thumbnail"><a href="'. get_the_permalink(). '">'. get_the_post_thumbnail(get_the_ID(), 'medium_feature') .'</a></div>';

		$list .= '<div class="post-desc">';
        $list .= '<div class="blog-cat"><a href="'. get_category_link($cats[0]->term_id) .'" class="'. $cats[0]->slug.'-bg'.'">'. $cats[0]->name .'</a></div>';
        if($title_excerpt)
            $list .= '<div class="blog-title"><a href="'. get_the_permalink(). '">'. mb_strimwidth(get_the_title(), 0, $title_excerpt, '...') .'</a></div>';
        else
            $list .= '<div class="blog-title"><a href="'. get_the_permalink(). '">'. get_the_title() .'</a></div>';

		/*$list .= '<div class="post-meta"><span class="date"><i class="fa fa-calendar"></i>  '. get_the_time( get_option( 'date_format' ) ) .'</span>';
        //$list .= '<span><i class="fa fa-eye"></i>'. $view_count .'</span>';
        $list .= '</div>';
		//<span class="comment"><i class="fa fa-commenting-o"></i>  '. $comments_count->total_comments .' comments</span>
		$list .= '<div class="blog-excerpt">'. get_the_excerpt() .'</div>';
		$list .= '<div class="blog-link"><a href="'. get_the_permalink() .'" class="ls-readmore">'. esc_html__( 'Continue Reading', 'nm-framework' ) .'</a></div>';*/
		$list .= '</div></div></div></li>';
	endwhile;

	wp_reset_query();
	$list .= '</ul>';
	return $list .= ob_get_clean();
	
}