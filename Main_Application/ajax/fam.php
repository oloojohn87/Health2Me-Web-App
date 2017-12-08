<?php
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

$fam1=$_GET['falive'];
$fam2=$_GET['fcod'];
$fam3=$_GET['faod'];
$fam4=$_GET['frd'];
$fam5=$_GET['malive'];
$fam6=$_GET['mcod'];
$fam7=$_GET['maod'];
$fam8=$_GET['mrd'];
$fam9=$_GET['srd'];
$sql=$con->prepare("INSERT INTO personal VALUES (?,?,?,?,?,?,?,?,?)");
$sql->bindValue(1, $fam1, PDO::PARAM_STR);
$sql->bindValue(2, $fam2, PDO::PARAM_STR);
$sql->bindValue(3, $fam3, PDO::PARAM_STR);
$sql->bindValue(4, $fam4, PDO::PARAM_STR);
$sql->bindValue(5, $fam5, PDO::PARAM_STR);
$sql->bindValue(6, $fam6, PDO::PARAM_STR);
$sql->bindValue(7, $fam7, PDO::PARAM_STR);
$sql->bindValue(8, $fam8, PDO::PARAM_STR);
$sql->bindValue(9, $fam9, PDO::PARAM_STR);




if(!$sql->execute())
{
die('Error: ' .mysql_error());
}
?>
