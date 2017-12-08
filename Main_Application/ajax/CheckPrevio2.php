<?php
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

$valor = $_GET['valor'];
$queTipo = $_GET['queTipo'];

$result = $con->prepare("SELECT * FROM doctors WHERE IdMEDEmail=?  ");
$result->bindValue(1, $valor, PDO::PARAM_STR);
$result->execute();

$count=$result->rowCount();
$row = $result->fetch(PDO::FETCH_ASSOC);

echo $count;

/*
echo '#';
echo $row['IdMEDFIXED'];
echo '#';
echo $row['IdMEDFIXEDNAME'];
echo '#';
echo $row['IdMEDRESERV'];
echo '#';
*/

?>