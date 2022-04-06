<?php 
// 1 With  Php MySql
// Step - 1 Create mysql table
CREATE TABLE `markers` (
  `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
  `name` VARCHAR( 60 ) NOT NULL ,
  `address` VARCHAR( 80 ) NOT NULL ,
  `lat` FLOAT( 10, 6 ) NOT NULL ,
  `lng` FLOAT( 10, 6 ) NOT NULL
) ENGINE = MYISAM ;


// Step -2  Add some dummy record in Table 

INSERT INTO `markers` (`name`, `address`, `lat`, `lng`) VALUES ('Frankie Johnnie & Luigo Too','939 W El Camino Real, Mountain View, CA','37.386339','-122.085823');
INSERT INTO `markers` (`name`, `address`, `lat`, `lng`) VALUES ('Amici\'s East Coast Pizzeria','790 Castro St, Mountain View, CA','37.38714','-122.083235');
INSERT INTO `markers` (`name`, `address`, `lat`, `lng`) VALUES ('Kapp\'s Pizza Bar & Grill','191 Castro St, Mountain View, CA','37.393885','-122.078916');
INSERT INTO `markers` (`name`, `address`, `lat`, `lng`) VALUES ('Round Table Pizza: Mountain View','570 N Shoreline Blvd, Mountain View, CA','37.402653','-122.079354');
INSERT INTO `markers` (`name`, `address`, `lat`, `lng`) VALUES ('Tony & Alba\'s Pizza & Pasta','619 Escuela Ave, Mountain View, CA','37.394011','-122.095528');
INSERT INTO `markers` (`name`, `address`, `lat`, `lng`) VALUES ('Oregano\'s Wood-Fired Pizza','4546 El Camino Real, Los Altos, CA','37.401724','-122.114646');


// Step - 3  Fetch list of records that comes between specific latitude & Longitude

$con=mysql_connect("localhost","root","")or die(mysql_error());
mysql_select_db("test",$con);
if(!$con)
	die('connect fail ' . mysql_error());

$clat = 37.387138;
$clng =-122.083237;
$radius = 10;

$query = "SELECT address, name, lat, lng, ( 3959 * acos( cos( radians('".$clat."') ) * cos( radians( lat ) ) * cos( radians( lng ) - radians(".$clng.") ) + sin( radians(".$clat.") ) * sin( radians( lat ) ) ) ) AS distance 
FROM markers HAVING distance < '".$radius."' ORDER BY distance LIMIT 0 , 20";
$result = mysql_query($query);
while ($row = @mysql_fetch_assoc($result)){
  echo "<pre>";
  print_r($row);
}


// 2 With Worpdress Post Meta Key Entry Records

include('wp-load.php');

$lat = 21.1702401;
$long = 72.83106070000008;
$radius = 30;
$sql = "SELECT ID, (
     6371 * acos (
     cos ( radians( $lat) )
     * cos( radians( latitude.meta_value ) )
     * cos( radians( longitude.meta_value ) - radians($long) )
     + sin ( radians($lat) )                       
     * sin( radians( latitude.meta_value ) )
    )
    ) AS distance
FROM $wpdb->posts
INNER JOIN $wpdb->postmeta latitude
    ON (ID = latitude.post_id AND latitude.meta_key = 'jv_item_lat')
INNER JOIN $wpdb->postmeta longitude
    ON (ID = longitude.post_id AND longitude.meta_key = 'jv_item_lng')
HAVING distance < $radius
ORDER BY distance
LIMIT 0,6";

$rw = $wpdb->get_results( $sql);
print_r($rw);

// Note - jv_item_lat & jv_item_long is post_meta key that hold latitude & longitude value in post_meta table

?>