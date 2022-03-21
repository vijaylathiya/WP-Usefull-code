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



//Remove Woocommerce Shop order, coupon, page, user, theme option etc capabilities from shop manager role.
add_action( 'init', 'ls_update_shopmanager_role_caps', 11 );
function ls_update_shopmanager_role_caps() {
//Dev Importatn note : this caps maybe saveing in database so if need any cap back, then need to call add_cap instead of remove_cap and refresh page, then remove that cap from below array and call remove_cap.
$shop_manager = get_role( 'shop_manager' );
$caps = array(
            'manage_woocommerce',
            'view_woocommerce_reports',
            'edit_shop_order',
            'read_shop_order',
            'delete_shop_order',
            'edit_shop_orders',
            'edit_others_shop_orders',
            'publish_shop_orders',
            'read_private_shop_orders',
            'delete_shop_orders',
            'delete_private_shop_orders',
            'delete_published_shop_orders',
            'delete_others_shop_orders',
            'edit_private_shop_orders',
            'dit_published_shop_orders',
            'delete_others_shop_orders',
            'delete_others_shop_orders',
            'edit_shop_coupon',
            'read_shop_coupon',
            'delete_shop_coupon',
            'edit_shop_coupons',
            'edit_others_shop_coupons',
            'publish_shop_coupons',
            'read_private_shop_coupons',
            'delete_shop_coupons',
            'delete_private_shop_coupons',
            'delete_published_shop_coupons',
            'delete_others_shop_coupons',
            'edit_private_shop_coupons',
            'dit_published_shop_coupons',
            'delete_others_shop_coupons',
            'delete_others_shop_coupons',
            'edit_page',
            'read_page',
            'delete_page',
            'edit_pages',
            'edit_others_pages',
            'publish_pages',
            'read_private_pages',
            'delete_pages',
            'delete_private_pages',
            'delete_published_pages',
            'delete_others_pages',
            'edit_private_pages',
            'dit_published_pages',
            'delete_others_pages',
            'delete_others_pages',
            'list_users',
            'edit_theme_options'
        );
    foreach ( $caps as $cap ) {
        //$shop_manager->add_cap( $cap );
        $shop_manager->remove_cap( $cap );
    }
}

?>