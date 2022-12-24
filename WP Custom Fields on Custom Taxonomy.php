<?php
/* LS Custom for  Options of Manage 3 Sections on MSA Category pages mycustom_taxonomy */
add_action('mycustom_taxonomy_add_form_fields', 'ls_wcat_add_meta_field', 10, 2);
add_action('mycustom_taxonomy_edit_form_fields', 'ls_wcat_edit_meta_field', 10, 2);

function ls_wcat_add_meta_field() {
    ?>
    <style type="text/css">
        /* if want to add any CSS add here  */
    </style>
    <div class="form-field">
        <label for="lwcc_pt">Hide Section? </label>
        <span class="rfld"><input type="checkbox" name="lwcc_sec1" value="yes"> Section 1</span> 
        <span class="rfld"><input type="checkbox" name="lwcc_sec2" value="yes"> Section 2</span> 
        <span class="rfld"><input type="checkbox" name="lwcc_sec3" value="yes"> Section 3</span>
    </div>
	<?php    
}
function ls_wcat_edit_meta_field($term, $taxonomy) {

    $lscat_metas_ser = get_term_meta($term->term_id, 'lscat_metas', true);
    $lscat_metas = unserialize($lscat_metas_ser);
    $lwcc_sec1 = $lscat_metas['lwcc_sec1'];
	$lwcc_sec2 = $lscat_metas['lwcc_sec2'];
	$lwcc_sec3 = $lscat_metas['lwcc_sec3'];
	
?>
    <style type="text/css">
        .form-field .rfld:not(:first-of-type) { margin-left: 25px; }
    </style>
    <table class="form-table" style=" border-top: 1px solid #cccccc; border-bottom: 1px solid #cccccc; margin-bottom: 20px;">
        <tbody>
            <tr class="form-field term-name-wrap">
                <th><label for="lwcc_pt">Hide Section?</label></th>
                <td>
                <span class="rfld"><input type="checkbox" name="lwcc_sec1" value="yes" <?php echo ($lwcc_sec1=='yes') ? 'checked="checked"' : ''; ?>> Section 1</span> 
                <span class="rfld"><input type="checkbox" name="lwcc_sec2" value="yes" <?php echo ($lwcc_sec2=='yes') ? 'checked="checked"' : ''; ?>> Section 2</span> 
                <span class="rfld"><input type="checkbox" name="lwcc_sec3" value="yes" <?php echo ($lwcc_sec3=='yes') ? 'checked="checked"' : ''; ?>> Section 3</span></td>
            </tr>
            
        </tbody>    
    </table>
<?php
}

add_action('edited_mycustom_taxonomy', 'ls_save_taxonomy_custom_meta', 10, 2);
add_action('create_mycustom_taxonomy', 'ls_save_taxonomy_custom_meta', 10, 2);
function ls_save_taxonomy_custom_meta($term_id, $tt_id) {
    $lscat_metaarr = array();
    if (isset($_POST['lwcc_sec1']) && '' != $_POST['lwcc_sec1']) $lscat_metaarr['lwcc_sec1'] = $_POST['lwcc_sec1'];
    else $lscat_metaarr['lwcc_sec1'] = '';

    if (isset($_POST['lwcc_sec2']) && '' != $_POST['lwcc_sec2']) $lscat_metaarr['lwcc_sec2'] = $_POST['lwcc_sec2'];
    else $lscat_metaarr['lwcc_sec2'] = '';


    if (isset($_POST['lwcc_sec3']) && '' != $_POST['lwcc_sec3']) $lscat_metaarr['lwcc_sec3'] = $_POST['lwcc_sec3'];
    else $lscat_metaarr['lwcc_sec3'] = '';

 	$lscat_metaarr_ser = serialize($lscat_metaarr);
	update_term_meta($term_id, 'lscat_metas', $lscat_metaarr_ser);
}