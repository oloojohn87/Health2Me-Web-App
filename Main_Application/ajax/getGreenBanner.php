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

$med_id = $_GET['med_id'];

$cadena = '';

$query = $con->prepare("SELECT * FROM consults WHERE Doctor=? && Status='In Progress' ORDER BY consultationId DESC LIMIT 1");
$query->bindValue(1, $med_id, PDO::PARAM_INT);
$result = $query->execute();
$count = $query->rowCount();

$row2 = $query->fetch(PDO::FETCH_ASSOC);

$PatId = $row2['Patient'];
$PatName = $row2['patientName'].' '.$row2['patientSurname'];
$DocName = $row2['doctorName'].' '.$row2['doctorSurname'];

$cadena.='
		{	
        "PatId":"'.$PatId.'",
        "name":"'.$PatName.'",
        "docName":"'.$DocName.'",
	"Description":"'.$row2['description'].'",
	"Type":"'.$row2['Type'].'",
	"Time":"'.$row2['DateTime'].'"
        }';    


$encode = json_encode($cadena);
echo '{"items":['.($cadena).']}'; 

?>
