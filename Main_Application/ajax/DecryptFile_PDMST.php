<?php
 session_start();
require("environment_detailForLogin.php");
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];
 $domain = $env_var_db['hardcode'];
 $local = $env_var_db['local'];
// Connect to server and select databse.
$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }	

$rawimage = $_GET['rawimage'];
$result = $con->prepare("select pass from encryption_pass where id = (select max(id) from encryption_pass)");
$result->execute();
$row = $result->fetch(PDO::FETCH_ASSOC);
$pass = $row['pass'];

$ImageRaiz = substr($rawimage,0,strlen($rawimage)-4);
$extensionR = substr($rawimage,strlen($rawimage)-3,3);

$filename = $domain.'temp/'.$rawimage;
if (file_exists($filename)) 
{
    //do nothing
    //echo "The file $filename exists";
}
else 
{
    $out = shell_exec("echo '".$pass."' | openssl aes-256-cbc -pass stdin -d -in ".$local."Packages_Encrypted/".$rawimage." -out ".$local."temp/".$rawimage);
    //echo 'Decrypt.bat Packages_Encrypted '.$rawimage.' '.$queMed .' '.$pass.' 2>&1';	
}

if($extensionR=='jpg')
{
    //die("Found JPG Extension");
    $extension='jpg';
    //return;
}
else
{
    $extension='png';
}
$filename = $domain.'temp/'.$ImageRaiz.'.'.$extension;	
//echo $filename;
/*
if (file_exists($filename)) 
{
    //do nothing
    //echo "The file $filename exists";	
}
else 
{
    shell_exec("echo '".$pass."' | openssl aes-256-cbc -pass stdin -d -in PackagesTH_Encrypted/".$ImageRaiz.".".$extension." -out temp/".$queMed."/PackagesTH_Encrypted/".$ImageRaiz.".".$extension);
    //echo "Thumbnail Generated";
}
*/
	


?>