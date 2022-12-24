<?php
add_role( 'coach', 'Coach', array( 'read' => true, 'level_0' => true ));

//LS Custom Roles and Caps
add_action('init', 'ls_custom_roles_caps');
function ls_custom_roles_caps(){

    //Blog
    remove_role('ls_rblog');
    add_role(
        'ls_rblog',
        'LS Blog Role',
        [
            // list of capabilities for this role
            'read'         => true,
            'edit_posts'   => true,
            'edit_published_posts' => true,
            'edit_others_posts' => true,
            'edit_private_posts' => true,
            'delete_others_posts' => true,
            'delete_posts' => true,
            'delete_private_posts'=>true,
            'delete_published_posts' => true,
            'manage_categories' => true,
            'manage_links' => true,
            'publish_posts' => true,
            'upload_files' => true,
        ]
    );

    //Products
    remove_role('ls_rproduct');
    add_role(
        'ls_rproduct',
        'LS Product Role',
        [
            'read' => true,
            'edit_dashboard' =>true,
            //'manage_woocommerce' =>true,
           // 'view_woocommerce_reports' =>true,
            'edit_product' =>true,
            'read_product' =>true,
            'delete_product' =>true,
            'edit_products' =>true,
            'edit_others_products' =>true,
            'publish_products' =>true,
            'read_private_products' =>true,
            'delete_products' =>true,
            'delete_private_products' =>true,
            'delete_published_products' =>true,
            'delete_others_products' =>true,
            'edit_private_products' =>true,
            'edit_published_products' =>true,
            'manage_product_terms' =>true,
            'edit_product_terms' =>true,
            'delete_product_terms' =>true,
            'assign_product_terms' =>true,
            'upload_files' => true,
        ]
    );


    //Video
    remove_role('ls_rvideo');
    add_role(
        'ls_rvideo',
        'LS Video Role',
        [
            'read' => true,
            'edit_dashboard' =>true,

            'edit_ls_video' => true,
            'edit_ls_videos' => true,
            'edit_others_ls_videos' => true,
            'edit_published_ls_videos' => true,
            'publish_ls_videos' => true,
            'delete_ls_video' => true,
            'delete_ls_videos' => true,
            'delete_published_ls_videos' => true,
            'delete_others_ls_videos' => true,
            'read_ls_video' => true,
            'read_private_ls_videos' => true,
            'manage_cat-videos' => true,
            'edit_cat-videos' => true,
            'delete_cat-videos' => true,
            'assign_cat-videos' => true,
            'upload_files' => true,
        ]
    );

    //Assign LS Video Roles to admin
    $admin_role = get_role( 'administrator' );
   
    $admin_role->add_cap( 'edit_ls_video', true );
    $admin_role->add_cap( 'edit_ls_videos', true );
    $admin_role->add_cap( 'edit_others_ls_videos', true );
    $admin_role->add_cap( 'edit_published_ls_videos', true );
    $admin_role->add_cap( 'publish_ls_videos', true );
    $admin_role->add_cap( 'delete_ls_video', true );
    $admin_role->add_cap( 'delete_ls_videos', true );
    $admin_role->add_cap( 'delete_published_ls_videos', true );
    $admin_role->add_cap( 'delete_others_ls_videos', true );
    $admin_role->add_cap( 'read_ls_video', true );
    $admin_role->add_cap( 'read_private_ls_videos', true );
    $admin_role->add_cap( 'manage_cat-videos', true );
    $admin_role->add_cap( 'edit_cat-videos', true );
    $admin_role->add_cap( 'delete_cat-videos', true );
    $admin_role->add_cap( 'assign_cat-videos', true );
    
    //Allow wp admin to specific roles
    $user = wp_get_current_user();
	if ( in_array( 'ls_rvideo', (array) $user->roles ) || in_array( 'ls_rproduct', (array) $user->roles ) ) {
		add_filter( 'woocommerce_prevent_admin_access', '__return_false' );
		add_filter( 'woocommerce_disable_admin_bar', '__return_false' );		
	}
    
}

// MAP META CAPABILITIES
add_filter( 'map_meta_cap', 'ls_video_map_meta_cap', 10, 4 );
function ls_video_map_meta_cap( $caps, $cap, $user_id, $args )
{

    if ( 'edit_ls_video' == $cap || 'delete_ls_video' == $cap || 'read_ls_video' == $cap ) {
        $post = get_post( $args[0] );
        $post_type = get_post_type_object( $post->post_type );
        $caps = array();
    }

    if ( 'edit_ls_video' == $cap ) {
        if ( $user_id == $post->post_author )
            $caps[] = $post_type->cap->edit_posts;
        else
            $caps[] = $post_type->cap->edit_others_posts;
    }

    elseif ( 'delete_ls_video' == $cap ) {
        if ( $user_id == $post->post_author )
            $caps[] = $post_type->cap->delete_posts;
        else
            $caps[] = $post_type->cap->delete_others_posts;
    }

    elseif ( 'read_ls_video' == $cap ) {
        if ( 'private' != $post->post_status )
            $caps[] = 'read';
        elseif ( $user_id == $post->post_author )
            $caps[] = 'read';
        else
            $caps[] = $post_type->cap->read_private_posts;
    }

    return $caps;
}