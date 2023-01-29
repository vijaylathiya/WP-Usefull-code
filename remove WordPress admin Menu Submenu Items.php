<?php
add_action( 'admin_menu', 'remove_admin_menu_items', 999 );
function remove_admin_menu_items() {

    remove_menu_page('index.php'); // Main menu page
    remove_submenu_page('index.php','update-core.php' ); // Sub menu page under Index.php main menu 

    // to find out Page name OR submenu item Indexing 
    global $submenu;
    print('<pre>');
	print_r($submenu);
	print('<pre>');
	die();

	// Remove the main menu item together with the subpages using unset
	global $submenu;
	unset($submenu['index.php'][0]); //remove top level menu index.php (dashboard menu - Home menu )
    unset($submenu['index.php'][10]); // remove the submenu update-core.php (updates menu)
}