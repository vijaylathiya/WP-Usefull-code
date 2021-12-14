<?php

//HOW TO ADD rel="nofollow" TO WORDPRESS NEXT_POSTS_LINK &  PREVIOUS_POSTS_LINK
add_filter('next_posts_link_attributes', 'get_next_posts_link_attributes');
add_filter('previous_posts_link_attributes', 'get_previous_posts_link_attributes');
if (!function_exists('get_next_posts_link_attributes')){
	function get_next_posts_link_attributes($attr){
		$attr = 'rel="nofollow"';
		return $attr;
	}
}
if (!function_exists('get_previous_posts_link_attributes')){
	function get_previous_posts_link_attributes($attr){
		$attr = 'rel="myrel"';
		return $attr;
	}
}
?>