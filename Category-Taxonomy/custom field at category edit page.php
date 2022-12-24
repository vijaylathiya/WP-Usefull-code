<?php
// ls custom by SYN

add_action('product_cat_add_form_fields', 'wh_taxonomy_add_new_meta_field', 10, 1);
//Product Cat Create page
function wh_taxonomy_add_new_meta_field() {
    ?>   
    <div class="form-field">
        <label for="wh_meta_title"><?php _e('Slider Name', 'wh'); ?></label>
        <input type="text" name="wh_meta_title" id="wh_meta_title">
    </div>
    <?php
}


add_action('product_cat_edit_form_fields', 'wh_taxonomy_edit_meta_field', 10, 1);
//Product Cat Edit page
function wh_taxonomy_edit_meta_field($term) {

    //getting term ID
    $term_id = $term->term_id;

    // retrieve the existing value(s) for this meta field.
    $wh_meta_title = get_term_meta($term_id, 'wh_meta_title', true);
    ?>
    <tr class="form-field">
        <th scope="row" valign="top"><label for="wh_meta_title"><?php _e('Slider Name', 'wh'); ?></label></th>
        <td>
            <input type="text" name="wh_meta_title" id="wh_meta_title" value="<?php echo esc_attr($wh_meta_title) ? esc_attr($wh_meta_title) : ''; ?>">
        </td>
    </tr>
    <?php
}


//Handle the form request of cat
add_action('edited_product_cat', 'wh_save_taxonomy_custom_meta', 10, 1);
add_action('create_product_cat', 'wh_save_taxonomy_custom_meta', 10, 1);
// Save extra taxonomy fields callback function.
function wh_save_taxonomy_custom_meta($term_id) {
    $wh_meta_title = filter_input(INPUT_POST, 'wh_meta_title');
    update_term_meta($term_id, 'wh_meta_title', $wh_meta_title);
}


//Displaying Additional Columns
add_filter( 'manage_edit-product_cat_columns', 'wh_customFieldsListTitle' ); //Register Function
add_action( 'manage_product_cat_custom_column', 'wh_customFieldsListDisplay' , 10, 3); //Populating the Columns


function wh_customFieldsListTitle( $columns ) {
    $columns['pro_meta_title'] = __( 'Slider', 'woocommerce' );
    return $columns;
}

function wh_customFieldsListDisplay( $columns, $column, $id ) {
    if ( 'pro_meta_title' == $column ) {
        $columns = esc_html( get_term_meta($id, 'wh_meta_title', true) );
    }
    return $columns;
}


add_action( 'woocommerce_before_main_content', 'my_function_name' ); 
function my_function_name() { 
	if( is_product_category()  ) {	
		$category = get_queried_object();
		$tid = $category->term_id;
		$productCatMetaTitle = get_term_meta($tid, 'wh_meta_title', true);
		echo "<h1>". $productCatMetaTitle ."</h1>";
	} 
}