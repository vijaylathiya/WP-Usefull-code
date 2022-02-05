<?php

add_action('admin_menu', 'awesome_page_create');
function awesome_page_create() {
    $page_title = 'Custom Setting';
    $menu_title = 'Custom Setting';
    $capability = 'edit_posts';
    $menu_slug = 'ls_custom_setting';
    $function = 'ls_custom_setting_page_display';
    $icon_url = '';
    $position = 24;

    add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position );
}

function ls_custom_setting_page_display() {
    if (isset($_POST['lsw_footer_url'])) {
        update_option('lsw_footer_url', $_POST['lsw_footer_url']);
        $value = $_POST['lsw_footer_url'];
    } 

    $value = get_option('lsw_footer_url', '');
	?>
    
	<h2>Home Banner Settings Page</h2>

<form method="POST">
    <label for="lsw_footer_url">Footer URL</label>
    <input type="text" name="lsw_footer_url" id="lsw_footer_url" value="<?php echo $value; ?>" placeholder="Url on Footer Reservation Button">
    <input type="submit" value="Save" class="button button-primary button-large">
</form>
	<?php
	
	
}
