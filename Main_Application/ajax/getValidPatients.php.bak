<?php
 
 require("environment_detail.php");
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];

$tbl_name="doctors"; // Table name

$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }		

$queUsu = $_GET['Doctor'];
$NReports = $_GET['NReports'];
$daysExplore=empty($_GET['days']) ? 30: $_GET['days'];


$cadena = '';
// First create an array with all Patient Ids that have activity (report created, messages, stages)
// This is "MyPatients" array: A) Patients created by me, plus B) Patients referred to me, plus C) Patients created by other members of my group
$result = $con->prepare("SELECT IdGroup FROM doctorsgroups WHERE IdDoctor = ?");
$result->bindValue(1, $queUsu, PDO::PARAM_INT);
$result->execute();

$row = $result->fetch(PDO::FETCH_ASSOC);
$MyGroup = $row['IdGroup'];
$query = $con->prepare("SELECT * FROM usuarios WHERE (IdCreator = ? or Identif IN (SELECT IdPac FROM doctorslinkdoctors WHERE IdMED2 = ?)) OR (IdCreator IN (SELECT IdDoctor FROM doctorsgroups WHERE IdGroup = ?)) ORDER BY Surname ASC");
$query->bindValue(1, $queUsu, PDO::PARAM_INT);
$query->bindValue(2, $queUsu, PDO::PARAM_INT);
$query->bindValue(3, $MyGroup, PDO::PARAM_INT);
$result = $query->execute();

$count = $query->rowCount();
$counter1 = 0 ;
while ($row2 = $query->fetch(PDO::FETCH_ASSOC)) {
	$UsIds[$counter1] = $row2['Identif'];
	$UsName[$counter1] = $row2['Name'];
	$UsSurname[$counter1] = $row2['Surname'];
	$UsEmail[$counter1] = $row2['email'];

	if ($counter1>0) $cadena.=',';    
	$cadena.='
    {
        "id":"'.$UsIds[$counter1].'",
        "name":"'.$UsName[$counter1].'",
        "surname":"'.$UsSurname[$counter1].'",
        "value":"'.$UsName[$counter1].' '.$UsSurname[$counter1].'",
        "label":"'.$UsName[$counter1].' '.$UsSurname[$counter1].'"
        }';    

	$counter1++;
}

$encode = json_encode($cadena);
echo '{"items":['.($cadena).']}'; 

function SanDate($entrydate)
{
	$newDate = date("m/d/Y H:i:s", strtotime($entrydate));
	return $newDate;
}

?>