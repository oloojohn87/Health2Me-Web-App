<?php
if(function_exists('xdebug_disable')) { xdebug_disable(); }
//THIS CREATES SESSION HASHING//////////////
include_once("checkSessionHash.php");
$checkSessionHash = new checkSessionHash();
$checkSessionHash->setters();
$ignore = 'no';
$checkSessionHash->hashChecker($ignore);
////////////////////////////////////////////
$envi_type = getenv('SERVER_NAME');
$file_path_db = "environment_details/";
$file_path_ftp = "environment_details/";
$domain="";
if( $envi_type == "health2.me" or $envi_type == "www.health2.me"){
//$file_path_db .= "Database_prod.txt";
//$file_path_ftp .= "ftp_prod.txt";
$env_var_db =array("dbhost"=>"health2.me",  
"dbname"=>"monimed4",
"dbuser"=>"monimed",
"dbpass"=>"ardiLLA98",
"hardcode"=>"https://www.health2.me/live/",
"local"=>"/var/www/vhost1/live/");
$env_var_ftp=array("server"=>"www.health2.me",
"user" => "ITGroup",
"pass" => "stream");
$domain = "https://www.health2.me";
}
else if( $envi_type == "dev.health2.me") 
{
//$file_path_db .= "Database_dev.txt";
//$file_path_ftp .= "ftp_dev.txt";
$env_var_db =array("dbhost"=>"dev.health2.me",  
"dbname"=>"monimed",
"dbuser"=>"monimed",
"dbpass"=>"ardiLLA98",
"hardcode"=>"http://dev.health2.me/",
"local"=>"/var/www/html/");
$env_var_ftp=array("server"=>"dev.health2.me",
"user" => "ITGroup",
"pass" => "stream");
$domain = "http://dev.health2.me";
//THIS IS FOR BETA SERVER
}elseif($envi_type == "beta.health2.me"){

$env_var_db =array("dbhost"=>"beta.health2.me",  
"dbname"=>"monimed",
"dbuser"=>"monimed",
"dbpass"=>"ardiLLA98",
"hardcode"=>"http://beta.health2.me/",
"local"=>"/var/www/vhost1/");
$env_var_ftp=array("server"=>"beta.health2.me",
"user" => "ITGroup",
"pass" => "stream");
$domain = "http://beta.health2.me";
}else {
// Added this to handle specific local connection to server
/*
$env_var_db =array("dbhost"=>"dev.health2.me",  
"dbname"=>"monimed",
"dbuser"=>"monimed",
"dbpass"=>"ardiLLA98");
$env_var_ftp=array("server"=>"dev.health2.me",
"user" => "ITGroup",
"pass" => "stream");
$domain = "http://localhost";
*/
//$env_var_db =array("dbhost"=>"172.16.120.131",  
//"dbname"=>"monimed",
//"dbuser"=>"monimed",
//"dbpass"=>"ardiLLA98");
//$env_var_ftp=array("server"=>"172.16.120.131/H2M",
//"user" => "ITGroup",
//"pass" => "stream");
//$domain = "http://172.16.120.131/H2M";


}

?>
