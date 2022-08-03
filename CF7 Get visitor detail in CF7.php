function getUserIP()
{
    $client  = @$_SERVER['HTTP_CLIENT_IP'];
    $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
    $remote  = $_SERVER['REMOTE_ADDR'];

    if(filter_var($client, FILTER_VALIDATE_IP))
    {
        $ip = $client;
    }
    elseif(filter_var($forward, FILTER_VALIDATE_IP))
    {
        $ip = $forward;
    }
    else
    {
        $ip = $remote;
    }

    return $ip;
}
add_filter( 'wpcf7_special_mail_tags', 'country_custom_ip_location', 10, 2 );
function country_custom_ip_location( $output, $name ){
  $name = preg_replace( '/^wpcf7\./', '_', $name );
  if ( '_custom_ip_location' == $name ) {
  $ip = getUserIP();
  $query = @unserialize(file_get_contents('http://ip-api.com/php/'.$ip));
	if($query && $query['status'] == 'success') {
  		$output =  'Hello visitor from '.$query['country'].', '.$query['city'].'!';
	} else {
  		$output = 'Unable to get location';
	}
  }
  return $output;
}