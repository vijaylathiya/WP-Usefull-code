<?php
//ls custom user last-login date code custom code in Wp-admin > users list table 
function new_modify_user_table( $column ) {
    $column['last_login'] = 'Last Login';
    $column['is_activated'] = 'Is verified ?'; //ls custom added for verified status column
    return $column;
}
add_filter( 'manage_users_columns', 'new_modify_user_table' );
function new_modify_user_table_row( $val, $column_name, $user_id ) {
    switch ($column_name) {
        case 'last_login' :
            $lastlogin = get_the_author_meta( 'last_login', $user_id );
            if($lastlogin)
            return date('Y-m-d' ,$lastlogin);
        	break;
        case 'is_activated' : //ls custom added for verified status column
        	$is_verified = get_the_author_meta('is_activated', $user_id);
        	if($is_verified)
        		return 'Y';
        	else
        		return 'N';

            break;}
        

    return $val;
}
add_filter( 'manage_users_custom_column', 'new_modify_user_table_row', 10, 3 );


function user_last_login( $user_login, $user ) {
    update_user_meta( $user->ID, 'last_login', time() );
}
add_action( 'wp_login', 'user_last_login', 10, 2 );



// Show User Registered Date
add_filter('manage_users_columns', 'wdo_col_user_registered_date');
function wdo_col_user_registered_date($columns) {
    $columns['user_registered'] = 'Register date';
    return $columns;
}
 
add_action('manage_users_custom_column',  'wdo_col_user_registered_date_data', 10, 3);
function wdo_col_user_registered_date_data($value, $column_name, $user_id) {
    $user = get_userdata( $user_id );
    if ( 'user_registered' == $column_name )
        return $user->user_registered;
    return $value;
}

function add_sortable( $columns ) {
$columns['user_registered'] = 'user_registered';
return $columns;
}
add_filter( 'manage_users_sortable_columns','add_sortable' );



add_action( 'user_register', function ( $user_id ) {update_user_meta($user_id,'register_ip',$_SERVER['REMOTE_ADDR']);});

function add_users_register_ip_column($column) {
   $column['register_ip'] = 'Reg. IP Address';
   $column['ls_order'] = 'Order';
   return $column;}
add_filter('manage_users_columns','add_users_register_ip_column');

function display_user_register_ip($val,$column,$user_id) {
   switch ($column)
   { 
        case 'register_ip' : 
            $user = get_userdata($user_id);
            return $user->register_ip; 
            break;
        case 'ls_order' :
            $numorders = wc_get_customer_order_count( $user_id );
            return $numorders;
            break;
        default: 
   }
   return $return; 
}
add_filter('manage_users_custom_column','display_user_register_ip',10,3);


?>