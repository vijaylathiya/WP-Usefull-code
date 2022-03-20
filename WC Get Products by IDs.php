<?php 
add_shortcode( 'ls_products_byid', 'ls_products_byid' );
function ls_products_byid( $atts ) {

        // Attributes
        $attribute = shortcode_atts( array( 'id' => '', 'single'=>'no', 'posts_per_page'=>-1), $atts );

        extract($attribute);

        $output = '';
        
        $no_whitespaces_ids = preg_replace( '/\s*,\s*/', ',', filter_var( $attribute['id'], FILTER_SANITIZE_STRING ) ); 
        $ids_array = explode( ',', $no_whitespaces_ids );

        ob_start();
        

        if($id){
            $q = new WP_Query( array( 'post_type' => 'product', 'post_status' => 'publish', 'orderby' => 'post__in', 'post__in' => $ids_array) );
        }
        else{
            return $output;   
        }

        $taxonomy = 'product_cat';
        $terms = get_terms($taxonomy);

        $i = 0;
        echo '<div class="ls_productlist">';

        if($single == 'no'){
            echo woocommerce_product_loop_start();

            while($q->have_posts()) : $q->the_post();

            $i++;
            $id = $q->get_the_ID();
            $product = wc_get_product( $id );
            echo wc_get_template_part( 'content', 'product' );       
            endwhile;
			wp_reset_query();
            echo woocommerce_product_loop_end();
        }
        else{
            while($q->have_posts()) : $q->the_post();
            $i++;
            $id = get_the_ID();
            
            echo '<div class="sngl-prdct col-xs-12">';
            if(has_post_thumbnail())
                echo '<div class="col-sm-3 image">'. get_the_post_thumbnail(get_the_ID(),'shop_catalog') .'</div>';
                $cnt = strip_tags(get_the_content());
                $desc = implode(' ', array_slice(explode(' ', $cnt), 0, 60));
                //echo $desc;
                echo '<div class="col-sm-9 content">
                        <div class="ls-small-title">'. get_the_title() .'</div>
                        <div class="desc">'. $desc .'</div>
                        <a href="'. get_the_permalink() .'">'. __('Read more', 'nm-framework') .'</a>
                     </div>';

            echo '</div>';
			endwhile;            
			wp_reset_query();
        }

        echo '</div>';
        return ob_get_clean();
}