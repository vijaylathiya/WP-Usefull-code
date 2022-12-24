<?php 
// in WooCommerce  
// On Product Category page show same Category all Products in sidebar by shortcode
add_shortcode('wdo_category_products','fn_wdo_category_products');  // ls customize
function fn_wdo_category_products($atts, $content = null)
{
	ob_start();
	if ( is_product_category() ) {
	global $post;

	$cate = get_queried_object();
	$cateID = $cate->term_id;
	$query_args = array('posts_per_page' => 10, 'no_found_rows' => 1, 'post_status' => 'publish', 'post_type' => 'product', 
		'tax_query' => array( 
			array(
			  'taxonomy' => 'product_cat',
			  'field' => 'id',
			  'terms' => $cateID
			)));
	$r = new WP_Query($query_args);
		
	if ($r->have_posts()) {
	?>
	<ul class="product_list_widget">
	  <?php while ($r->have_posts()) : $r->the_post(); global $product; ?>
		<li><a href="<?php the_permalink() ?>" title="<?php echo esc_attr(get_the_title() ? get_the_title() : get_the_ID()); ?>">
		<?php if (has_post_thumbnail()) the_post_thumbnail('shop_thumbnail'); else echo '<img src="'. woocommerce_placeholder_img_src() .'" alt="Placeholder" width="'.$woocommerce->get_image_size('shop_thumbnail_image_width').'" height="'.$woocommerce->get_image_size('shop_thumbnail_image_height').'" />'; ?>
		<?php if ( get_the_title() ) the_title(); else the_ID(); ?>
		</a> <?php echo $product->get_price_html(); ?></li>
	  <?php endwhile; ?>
	</ul>
	<?php
	// Reset the global $the_post as this query will have stomped on it
	wp_reset_query();
	}
	}
	return ob_get_clean();
}