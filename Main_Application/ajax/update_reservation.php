<?php
require("environment_detail.php");
require("NotificationClass.php");
$dbhost = $env_var_db['dbhost'];
$dbname = $env_var_db['dbname'];
$dbuser = $env_var_db['dbuser'];
$dbpass = $env_var_db['dbpass'];

$tbl_name="reservation"; // Table name

$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                             PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
{
    die('Could not connect: ' . mysql_error());
}	

$notifications = new Notifications();

$med_id = $_POST['medID'];
$jsonData = $_POST['pInfos'];
$patientsInfo = json_decode($jsonData, true);


    
$sql  = "UPDATE reservation SET date = ?, status = ? WHERE id= ? AND provider_id = ?";
$stmt = $con->prepare($sql);

foreach($patientsInfo as $rid => $infoVal) {
    if($infoVal) {
        $stmt->execute(array($infoVal['date'], $infoVal['status'], $rid, $med_id));
        if($infoVal['status'] == 'ACTIVE' || $infoVal['status'] == 'NEXT') $notifications->add('APPUPD', $med_id, true, $infoVal['pid'], false, $infoVal['date']);
    }
}



?>