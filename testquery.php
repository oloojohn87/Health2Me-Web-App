<?php

session_start();
require('environment_detailForLogin.php');

$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false,
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }


$query = $con->prepare("select * from monimed.usuarios order by identif limit 10");
$query->execute();

$row = $query->fetch(PDO::FETCH_ASSOC);

echo $row[0]['Name'];


?>