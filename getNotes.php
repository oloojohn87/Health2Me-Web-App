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

$doctorId = $_GET['doctorId'];
//$telemedType = $_GET['telemedType'];


//Getting the consultationId of the doctor where he is currently engaged in
$query = $con->prepare("select * from consults where Doctor=? and (status = 'In Progress' OR status='Completed') ORDER BY consultationId DESC");
$query->bindValue(1, $doctorId, PDO::PARAM_INT);
$query->execute();
$result = $query->fetch(PDO::FETCH_ASSOC);

$consultationId = $result['consultationId'];

//Added by Pallab

//echo "Consultation ID ".$consultationId;



$findings = "";
$recommendations = "";

$findings = "'".file_get_contents("temp/".$doctorId."/".$consultationId."_notes_findings.txt")."'";
$recommendations = "'".file_get_contents("temp/".$doctorId."/".$consultationId."_notes_recommendations.txt")."'";

echo json_encode(array("findings" => $findings, "recommendations" => $recommendations));

//Commented the below lines for testing by Pallab
//shell_exec('del temp/'.$doctorId."/".$consultationId.'_notes_findings.txt'); 
//shell_exec('del temp/'.$doctorId."/".$consultationId.'_notes_recommendations.txt');

?>
