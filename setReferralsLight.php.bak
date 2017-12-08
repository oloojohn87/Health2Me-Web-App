<?php
 
 require("environment_detail.php");
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];

$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }			

$ID = $_POST['ID'];
$res = 'success';

$result = $con->prepare("UPDATE doctorslinkdoctors SET estado=2 WHERE id=?");
$result->bindValue(1, $ID, PDO::PARAM_INT);
$result->execute();

if (!$result)
{
    $res = 'failed';
}
$result = $con->prepare("INSERT INTO ".$dbname.".referral_stage SET referral_id=?, stage=1");
$result->bindValue(1, $ID, PDO::PARAM_INT);
$result->execute();

if (!$result)
{
    $res = 'failed';
}

echo $res;

?>
