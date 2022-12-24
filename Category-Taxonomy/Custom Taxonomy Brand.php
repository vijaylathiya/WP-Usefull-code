<?php
add_action('brand_add_form_fields', 'ls_taxonomy_add_new_meta_field', 10, 1);
//Product Cat Create page
function ls_taxonomy_add_new_meta_field() {
    ?>   
    <div class="form-field">
        <label for="lscf_brand_slider"><?php _e('Slider Shortcode', 'wh'); ?></label>
        <input type="text" name="lscf_brand_slider" id="lscf_brand_slider">
    </div>
    <?php
}


add_action('brand_edit_form_fields', 'ls_taxonomy_edit_meta_field', 10, 1);
//Product Cat Edit page
function ls_taxonomy_edit_meta_field($term) {

    //getting term ID
    $term_id = $term->term_id;

    // retrieve the existing value(s) for this meta field.
    $lscf_brand_slider = get_term_meta($term_id, 'lscf_brand_slider', true);
    ?>
    <tr class="form-field">
        <th scope="row" valign="top"><label for="lscf_brand_slider"><?php _e('Slider Shortcode', 'wh'); ?></label></th>
        <td>
            <input type="text" name="lscf_brand_slider" id="lscf_brand_slider" value="<?php echo esc_attr($lscf_brand_slider) ? esc_attr($lscf_brand_slider) : ''; ?>">
        </td>
    </tr>
    <?php
}
add_action('edited_brand', 'ls_save_taxonomy_brand_custom_meta', 10, 1);
add_action('create_brand', 'ls_save_taxonomy_brand_custom_meta', 10, 1);
// Save extra taxonomy fields callback function.
function ls_save_taxonomy_brand_custom_meta($term_id) {
    $lscf_brand_slider = filter_input(INPUT_POST, 'lscf_brand_slider');
    update_term_meta($term_id, 'lscf_brand_slider', $lscf_brand_slider);
}

//Displaying Additional Columns
add_filter( 'manage_edit-brand_columns', 'wh_customFieldsListTitle' ); //Register Function
add_action( 'manage_brand_custom_column', 'wh_customFieldsListDisplay' , 10, 3); //Populating the Columns


function wh_customFieldsListTitle( $columns ) {
    $columns['ls_brand_slider_title'] = __( 'Slider', 'woocommerce' );
    return $columns;
}

function wh_customFieldsListDisplay( $columns, $column, $id ) {
    if ( 'ls_brand_slider_title' == $column ) {
        $columns = esc_html( get_term_meta($id, 'lscf_brand_slider', true) );
    }
    return $columns;
}
add_action( 'woocommerce_before_main_content', 'ls_show_slider_on_brand_page' ); 
function ls_show_slider_on_brand_page() { 
	if( is_tax('brand')  ) {	
		$category = get_queried_object();
		$tid = $category->term_id;
		$productCatMetaTitle = get_term_meta($tid, 'lscf_brand_slider', true);
		echo do_shortcode($productCatMetaTitle);
	} 
}
?>