// Exclude particular category Products from Display on the shop and Products Categories archive page

function custom_pre_get_posts_query( $q ) {
    $tax_query = (array) $q->get( 'tax_query' );
    $tax_query[] = array(
        'taxonomy' => 'product_cat',
        'field' => 'slug',
        'terms' => array( 'clothing' ), // Don't display products in the clothing category on the shop page and on any Product Archive page ie even it will not display on Clothing Archive page.
        'operator' => 'NOT IN'
    );
    $q->set( 'tax_query', $tax_query );

}
add_action( 'woocommerce_product_query', 'custom_pre_get_posts_query' ); 

// Show particular Meta value Products only on the Shop and Products Categories archive page

function custom_pre_get_posts_query( $q ) {
    $meta_query = (array) $q->get( 'meta_query' );
    $meta_query[] = array(
            'relation' => 'OR',
            array(
                'key'       => 'c_type',
                'compare'   => '==',
                'value'     =>  0,
            ),
            array(
                'key'       => 'c_type',
                'compare'   => 'NOT EXISTS',
            )
    );
    $q->set('meta_query', $meta_query);
}
add_action( 'woocommerce_product_query', 'custom_pre_get_posts_query' ); 

