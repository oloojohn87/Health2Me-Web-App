<?php
session_start();
require("../environment_detail.php");
//require "logger.php";

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

$idpin = $_GET['idpin'];
$idusu = $_GET['user'];

/*
$month = strtok($fecha,"/");
$day = strtok("/");
$year = strtok("/");

$fecha = $year.'-'.$month.'-'.$day;
*/

if(isset($_GET['tipo'])) {
    $fecha = $_GET['fecha'];
    $tipo  = $_GET['tipo'];
    $query = $con->prepare("UPDATE lifepin SET Fecha=?, Tipo=?, vs=1 WHERE IdPin = ?");
    $query->bindValue(1, $fecha, PDO::PARAM_STR);
    $query->bindValue(2, $tipo, PDO::PARAM_INT);
    $query->bindValue(3, $idpin, PDO::PARAM_INT);
    $query->execute();
    
    if($query) echo $fecha;
}
else {
    $cmpquery = $con->prepare('SELECT Fecha, FechaInput FROM lifepin WHERE markfordelete IS NULL AND IdPin = ?');
    $cmpquery->bindValue(1, $idpin, PDO::PARAM_INT);
    $cmpquery->execute();
    $cmprow = $cmpquery->fetch(PDO::FETCH_ASSOC);
    
    //IF USER UPDATED DATE INFORMATION EXISTS RETURN THAT
    if($cmprow['Fecha'] != $cmprow['FechaInput']) {
        echo $cmprow['Fecha'];
    }
    else echo 'not updated';
}


?>