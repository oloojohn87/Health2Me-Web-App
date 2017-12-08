<?php
session_start();
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




$findings = $_POST['findings'];
$recommendations = $_POST['recommendations'];
$doctorId = $_POST['doctorId'];

echo $findings.'  '.$recommendations.'  '.$doctorId;

//Getting the consultationId of the doctor where he is currently engaged in

$query = $con->prepare("select * from consults where doctor=? and status ='In Progress' ORDER BY consultationId DESC LIMIT 1");
$query->bindValue(1, $doctorId, PDO::PARAM_INT);
$query->execute();

$result = $query->fetch(PDO::FETCH_ASSOC);
$consultationId = $result['consultationId'];


//Checking if the file exists, if not then create the file and write into it
if(!file_exists('temp/'.$doctorId."/".$consultationId.'_notes_findings.txt'))
    {
       $notesFile = fopen("temp/".$doctorId."/".$consultationId."_notes_findings.txt","w");
       fwrite($notesFile,$findings);
       
    }
//Open the file and write to it
else
    {
       $notesFile = fopen("temp/".$doctorId."/".$consultationId."_notes_findings.txt","w");
       fwrite($notesFile,$findings);
    }

if(!file_exists('temp/'.$doctorId."/".$consultationId.'_notes_recommendations.txt'))
    {
       $notesFile = fopen("temp/".$doctorId."/".$consultationId."_notes_recommendations.txt","w");
       fwrite($notesFile,$recommendations);
       
    }
//Open the file and write to it
else
    {
       $notesFile = fopen("temp/".$doctorId."/".$consultationId."_notes_recommendations.txt","w");
       fwrite($notesFile,$recommendations);
    }

?>
