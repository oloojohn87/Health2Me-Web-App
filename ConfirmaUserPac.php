<?php
define('INCLUDE_CHECK',1);
require "logger.php";
 

$CodigoBusca = $_GET['token'];

require("environment_detail.php");
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];
$tbl_name="doctors"; // Table name
					
// Connect to server and select databse.
$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }		

$ip = $_SERVER['REMOTE_ADDR'];
echo "<br> Your IP address : " . $ip;
echo "<br> Your hostname : " . GetHostByName($ip);


$q = $con->prepare("UPDATE usuarios SET FechaVer = NOW(), Verificado=1 ,confirmcode ='********', IPVALID=? WHERE confirmcode=? ");
$q->bindValue(1, $ip, PDO::PARAM_STR);
$q->bindValue(2, $CodigoBusca, PDO::PARAM_STR);
$q->execute();



echo 'CODIGO = ';
echo $CodigoBusca;
echo "CONFIRMATION";


$salto='location:SignInUSER.php';

header($salto);
    

?>