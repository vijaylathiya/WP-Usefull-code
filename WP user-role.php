<?php
//Give WooCommerce Products section access to The Editor Role Users
function ls_edit_user_role() {
    $role = get_role( 'editor' );
    $role->add_cap("edit_product");
    $role->add_cap("read_product");
    $role->add_cap("delete_product");
    $role->add_cap("edit_products");
    $role->add_cap("edit_others_products");
    $role->add_cap("publish_products");
    $role->add_cap("read_private_products");
    $role->add_cap("delete_products");
    $role->add_cap("delete_private_products");
    $role->add_cap("delete_published_products");
    $role->add_cap("delete_others_products");
    $role->add_cap("edit_private_products");
    $role->add_cap("edit_published_products");
    $role->add_cap("manage_product_terms");
    $role->add_cap("edit_product_terms");
    $role->add_cap("delete_product_terms");
    $role->add_cap("assign_product_terms");
}
add_action( 'admin_init', 'ls_edit_user_role');
?>