<?php
require("environment_detail.php");
$dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];
 
 
 
$idpins = '';
$idpins_array = array();
$user = $_GET['user'];
$bill_id = $_GET['billid'];
if(isset($_GET['idpins']))
{
    $idpins = $_GET['idpins'];
    $idpins_array = explode("_", $idpins);
}


// Connect to server and select databse.
$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }	
  
  for($i = 0; $i < count($idpins_array); $i++)
         {
            $results = $con->prepare("UPDATE lifepin SET bill_id = ? WHERE IdPin = ?");
            $results->bindValue(1, $bill_id, PDO::PARAM_INT);
            $results->bindValue(2, $idpins_array[$i], PDO::PARAM_INT);
            $results->execute();
         }
  
?>