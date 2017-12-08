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

$doc = $_POST['doctor'];
$pat = $_POST['patient'];

$probe = $con->prepare("SELECT probeID FROM probe WHERE doctorID = ? AND patientID = ?");
$probe->bindValue(1, $doc, PDO::PARAM_INT);
$probe->bindValue(2, $pat, PDO::PARAM_INT);
$probe->execute();
$probe_row = $probe->fetch(PDO::FETCH_ASSOC);


$alerts = $con->prepare("SELECT * FROM probe_alerts WHERE probe = ?");
$alerts->bindValue(1, $probe_row['probeID'], PDO::PARAM_INT);
$alerts->execute();

$result = array();
while($alerts_row = $alerts->fetch(PDO::FETCH_ASSOC))
{
    array_push($result, $alerts_row);
}

echo json_encode($result);

?>