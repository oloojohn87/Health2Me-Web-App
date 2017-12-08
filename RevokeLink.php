<?php
 //   echo '<table><tr><td>TEST</td></tr></table>';
 

 require("environment_detail.php");
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];

$tbl_name="usuarios"; // Table name

$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }	

$IdPatient =  $_GET['IdPatient'];
$IdDoctor =  $_GET['IdDoctor'];
$IdType =  $_GET['IdType'];

$result = $con->prepare("SELECT IdGroup FROM doctorsgroups WHERE IdDoctor = ?");
$result->bindValue(1, $IdDoctor, PDO::PARAM_INT);
$result->execute();

$row = $result->fetch(PDO::FETCH_ASSOC);
$MyGroup = $row['IdGroup'];


switch ($IdType)
{
    case '1':   // Revoke Link for connection (waiting for confirm on table DLU)
                $result = $con->prepare("DELETE FROM doctorslinkusers WHERE IdMED=? AND IdUs=? AND estado='1' ");
				$result->bindValue(1, $IdDoctor, PDO::PARAM_INT);
				$result->bindValue(2, $IdPatient, PDO::PARAM_INT);
				$result->execute();
				
                $count = $result->rowCount();
                break;
    case '2':   // Revoke Invitation (TempoPass is positive in usuarios)
                $result = $con->prepare("UPDATE usuarios SET TempoPass=NULL AND Password=NULL WHERE (IdCreator=? AND Identif=?) OR (Identif=? AND IdCreator IN (SELECT IdDoctor FROM doctorsgroups WHERE IdGroup = ?))");
                $result->bindValue(1, $IdDoctor, PDO::PARAM_INT);
				$result->bindValue(2, $IdPatient, PDO::PARAM_INT);
				$result->bindValue(3, $IdPatient, PDO::PARAM_INT);
				$result->bindValue(4, $MyGroup, PDO::PARAM_INT);
				$result->execute();
				
				$count = $result->rowCount();
                break;
}

echo 'Operation on User= '.$IdPatient.', Doctor= '.$IdDoctor.', Type= '.$IdType.'   ... gives --> '.$count;

function SCaps ($cadena)
{
return strtoupper(substr($cadena,0,1)).substr($cadena,1);
}    

?>