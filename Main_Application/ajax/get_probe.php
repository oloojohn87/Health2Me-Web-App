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

if($doc == 0)
{
    $user = $con->prepare("SELECT personal_doctor FROM usuarios WHERE Identif = ?");
    $user->bindValue(1, $pat, PDO::PARAM_INT);
    $user->execute();
    
    $user_row = $user->fetch(PDO::FETCH_ASSOC);
    
    if($user_row['personal_doctor'] != NULL)
    {
        $doc = $user_row['personal_doctor'];
    }
    else
    {
        $probe = $con->prepare("SELECT doctorID FROM probe WHERE patientID = ? ORDER BY creationDate DESC LIMIT 1");
        $probe->bindValue(1, $pat, PDO::PARAM_INT);
        $probe->execute();
        
        $probe_row = $probe->fetch(PDO::FETCH_ASSOC);
        
        $doc = $probe_row['doctorID'];
    }
}

$res = $con->prepare("SELECT p.*, pa.start_value AS start_val, pa.exp_value AS exp_val, pa.exp_day_1, pa.exp_day_2, pa.tolerance FROM (SELECT * FROM probe  WHERE doctorID = ? AND patientID = ?) AS p LEFT JOIN probe_alerts pa ON pa.probe = p.probeID AND pa.question = 1");
$res->bindValue(1, $doc, PDO::PARAM_INT);
$res->bindValue(2, $pat, PDO::PARAM_INT);
$res->execute();
$row = $res->fetch(PDO::FETCH_ASSOC);


echo json_encode($row);

?>