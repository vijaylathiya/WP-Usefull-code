<?php

// 05 July 2098  In other language get default Language Category ID from Othrer Language Category ID
// used in Pimpmydoal D:\wamp\www\Direct\skygoal.net\pimpmydolls.com\wp-content\themes\atelier\swift-framework\core\ sf-page-heading.php
$category = $wp_query->get_queried_object();
// Retrieve original term ID: OK ls custom 
$ls_get_origtermid = apply_filters ( 'wpml_object_id', $category->term_id, 'category', true, 'en' ); 
//$hero_id = get_woocommerce_term_meta( $category->term_id, 'hero_id', true  ); ls custom
$hero_id = get_woocommerce_term_meta( $ls_get_origtermid, 'hero_id', true  );

					
					
// 1   
//If Translated custom post type slugs not working  
// Fetch problem in nosatu.com CPT story single page throw not found for other language except default language

'rewrite' => array('slug' => __('story', 'URL slug', 'lovestory')),    
//SOlutions here https://wpml.org/forums/topic/translated-custom-post-type-slugs-not-working/

// ICL_LANGUAGE_CODE  for get current language
// impof 
global $sitepress;
 
$current_lang = $sitepress->get_current_language();
$default_lang = $sitepress->get_default_language();
$sitepress->switch_lang($default_lang, true);
echo get_permalink('97');
$sitepress->switch_lang($current_lang, true);

echo  $redirect_url_id = wc_get_page_id('myaccount');
echo $other_lang_id = icl_object_id($redirect_url_id, 'page', true, 'en');
echo $url = get_permalink($other_lang_id);

// Use custom menu for language switcher

/* Ls code added start */
		if( has_nav_menu('ls_lang_top_menu') ){
		echo '<div class="top-navigation-right" style="margin: 0px 0px;"><div class="top-social-wrapper">	';
			wp_nav_menu( array(
				'theme_location'=>'ls_lang_top_menu', 
				'container'=> '', 
				'menu_class'=> 'ls-lang-top-menu'
			) );
		 echo '</div></div>';
		}
	/* Ls code added over */
	
	add_action( 'after_setup_theme', 'gdlr_theme_setup' );
	if( !function_exists('gdlr_theme_setup') ){
		function gdlr_theme_setup(){
		register_nav_menu( 'ls_lang_top_menu', __( 'Top Language Menu', 'gdlr_translate' ) );
		}
	}
	
	
	
// init include script class
if( !is_admin() ){ new gdlr_include_script(); }	
function wpml_shortcode_func(){
//do_action('icl_language_selector');
do_action('wpml_add_language_selector');
}
add_shortcode( 'wpml_lang_selector', 'wpml_shortcode_func' );


// *****  https://wpml.org/forums/topic/show-only-other-language-in-language-switcher/  ******/

// Filter wp_nav_menu() to add additional links and other output
add_filter('wp_nav_menu_items', 'new_nav_menu_items', 10, 2);
function new_nav_menu_items($items, $args) {
	
	
 // add $args->theme_location == 'primary-menu' in the conditional if we want to specify the menu location.
 if (function_exists('icl_get_languages') && $args->theme_location == 'main_menu') {
  $languages = icl_get_languages('skip_missing=0');
    
  if(!empty($languages)){
    
    foreach($languages as $l){
    if(!$l['active']){
     // flag with native name
     $items = $items . '<li class="menu-item"><a href="' . $l['url'] . '"><img src="' . $l['country_flag_url'] . '" height="12" alt="' . $l['language_code'] . '" width="18" /> ' . $l['native_name'] . '</a></li>';
     //only flag
     //$items = $items . '<li class="menu-item menu-item-language"><a href="' . $l['url'] . '"><img src="' . $l['country_flag_url'] . '" height="12" alt="' . $l['language_code'] . '" width="18" /></a></li>';
    }
   }
  }
 }
return $items;
}


/******* Get Post by Language ****/
// Add suppress_filters=0 as an argument to your get_posts() query  https://wpml.org/forums/topic/get_posts-by-language/#post-63119
$query_args = array('post_type' => 'portfolio', 'suppress_filters' => false);

/******* Language dependent IDs *******/
//https://wpml.org/documentation/support/creating-multilingual-wordpress-themes/language-dependent-ids/
//1) Automatically Adjust IDs
//2) Manually, using the icl_object_id function
icl_object_id(3, 'category', false);
icl_object_id(3, 'post', false);

?>