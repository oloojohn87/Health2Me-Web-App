<?php
/*KYLE
require("environment_detail.php");
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];

$link = mysql_connect("$dbhost", "$dbuser", "$dbpass")or die("cannot connect");
mysql_select_db("$dbname")or die("cannot select DB");

$userId = $_POST['Patient'];

$cadena = array();
$cadena_added = 0;
$query1 = mysql_query("SELECT * FROM message_infrastructureuser WHERE tofrom = 'from' and patient_id='".$userId."' and status = 'new'");


while($row1 = mysql_fetch_assoc($query1))
{
    $patientId = $row1['patient_id'];
    $patient_name = '';
    $doctor_name = '';
    $messageForUser = $row1['content'];
    
    $query2 = mysql_query("SELECT Name,Surname FROM usuarios WHERE Identif='".$patientId."'");
    if ($row2 = mysql_fetch_assoc($query2))
    {
        $patient_name = $row2['Name'].' '.$row2['Surname'];
    }
    //$query3 = mysql_query("SELECT id FROM doctors WHERE id='".$doctorId."'");
   // if ($row3 = mysql_fetch_assoc($query3))
    //{
       // $doctor_name = $row3['Name'].' '.$row3['Surname'];
    //}
    $arr = array("CONTENT" => $messageForUser,"PATIENT" => $patient_name);
    array_push($cadena, $arr);
}


$encode = json_encode($cadena);
echo $encode; 

?>