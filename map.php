<?php
session_start();

require("environment_detail.php");
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];

    
// Connect to server and select databse.
$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }		

$api_key="a5abdd8cdf06658ec19a37f85f594ab70f3c84e881fce3b63aa73d05052745ab"; // COMPLETE THIS WITH YOUR IPINFODB API KEY
$google_maps_api_key="AIzaSyBTZeRhmx1SaJl_n7kkI_2H2KSiqOy3SHQ"; // COMPLETE THIS WITH YOUR GOOGLE MAPS API KEY

$ip = $_GET['ipaddress'];
$id = $_GET['id'];
$xml = simplexml_load_file('http://api.ipinfodb.com/v2/ip_query.php?key='.$api_key.'&ip='.$ip.'&timezone=true');

$ip = $xml->Ip;
$status = $xml->Status;
$country = $xml->CountryName;
$region = $xml->RegionName;
$city = $xml->City;
$latitude = $xml->Latitude;
$longitude = $xml->Longitude;
$timezone = $xml->TimezoneName;


$query = $con->prepare("update bpinview set latitude=? , longitude=? , country=? , region=? , city=? where id=?");
$query->bindValue(1, $latitude, PDO::PARAM_STR);
$query->bindValue(2, $longitude, PDO::PARAM_STR);
$query->bindValue(3, $country, PDO::PARAM_STR);
$query->bindValue(4, $region, PDO::PARAM_STR);
$query->bindValue(5, $city, PDO::PARAM_STR);
$query->bindValue(6, $id, PDO::PARAM_INT);


$result = $query->execute();

/*

$my_file = 'location.txt';
$handle = fopen($my_file, 'w') or die('Cannot open file:  '.$my_file);
$data = $ip.' ; '.$country.' ; '.$region.' ; '.$city.' ; '.$latitude.' ; '.$longitude;
fwrite($handle, $query);
fclose($handle);
*/

?>


