<?php
define('INCLUDE_CHECK',1);
require "logger.php";
 
/*$To = $_GET['To'];
$ToEmail = $_GET['ToEmail'];
$From = $_GET['From'];
$FromEmail = $_GET['FromEmail'];
$Subject = $_GET['Subject'];
$Content = $_GET['Content'];
$Leido = $_GET['Leido'];
$Push = $_GET['Push'];
$estado = $_GET['estado'];
*/

$CodigoBusca = $_GET['Confirm'];

$IdUsu = $_GET['User'];
 
$Fecha = date ('Y-m-d H:i:s');	

require("environment_detail.php");
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];
$tbl_name="messages"; // Table name
					
// Connect to server and select databse.
$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }		


$q = $con->prepare("UPDATE messages SET Fecha = NOW(), Leido=1 ,Confirm='********' WHERE Confirm=? ");
$q->bindValue(1, $CodigoBusca, PDO::PARAM_STR);
$q->execute();


$q2 = $con->prepare("UPDATE doctorslinkusers SET Fecha = NOW(),  estado = 2, Confirm='********' WHERE Confirm=? ");
$q2->bindValue(1, $CodigoBusca, PDO::PARAM_STR);
$q2->execute();

echo 'CODIGO = ';
echo $CodigoBusca;
echo "CONFIRMATION";


$salto='location:patientConnections.php?Acceso=23432&USERID='.$IdUsu.'&Nombre=&Password=&IdUsu='.$IdUsu;

header($salto);
    

?>