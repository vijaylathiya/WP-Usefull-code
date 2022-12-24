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

?>