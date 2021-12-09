<?php
require 'wp-load.php';
global $wpdb; 

$tables = $wpdb->get_results( 'SHOW TABLES', ARRAY_N ); 
$return = '';
foreach ( $tables as $table ) {
    $num_fields = sizeof( $wpdb->get_results( 'DESCRIBE ' . $table[0] . ';' ) );
    $return .= 'DROP TABLE IF EXISTS ' . $table[0] . ';';
    $row2 = $wpdb->get_row( 'SHOW CREATE TABLE ' . $table[0] . ';', ARRAY_N );
    $return .= PHP_EOL . PHP_EOL . $row2[1] . ";" . PHP_EOL . PHP_EOL;

        $result = $wpdb->get_results( 'SELECT * FROM ' . $table[0] . ';', ARRAY_N );
        foreach ( $result as $row ) {
            $return .= 'INSERT INTO ' . $table[0] . ' VALUES(';
            for ( $j = 0; $j < $num_fields; $j ++ ) {
                $row[$j] = addslashes( $row[$j] );
                $row[$j] = preg_replace( '#' . PHP_EOL . '#', "\n", $row[$j] );
                if ( isset( $row[$j] ) ) {
                    $return .= '"' . $row[$j] . '"';
                } else {
                    $return .= '""';
                }
                if ( $j < ( $num_fields - 1 ) ) {
                    $return .= ',';
                }
            }
            $return .= ");" . PHP_EOL;
        }
    $return .= PHP_EOL . PHP_EOL;
}
$return .= PHP_EOL . PHP_EOL;
$current_time = current_time( 'timestamp' );
//save file
$file = 'backup-' . substr( sanitize_title( get_bloginfo( 'name' ) ), 0, 20 ) . '-' . $current_time . '-' .  mt_rand( 5, 10 ) ;
$handle = @fopen(  $file . '.sql', 'w+' );
@fwrite( $handle, $return );
@fclose( $handle );
//zip the file
    if ( ! class_exists( 'PclZip' ) ) {
        require( ABSPATH . 'wp-admin/includes/class-pclzip.php' );
    }
    $zip = new PclZip(  $file . '.zip' );
    if ( 0 != $zip->create(  $file . '.sql' ) ) {
        //delete .sql 
        @unlink(  $file . '.sql' );
        $fileext = '.zip';
    }
?>