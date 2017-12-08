<?php 
session_start();
//error_reporting(E_ALL);
//ini_set('display_errors', '1');
require("environment_detail.php");
$dbhost = $env_var_db['dbhost'];
$dbname = $env_var_db['dbname'];
$dbuser = $env_var_db['dbuser'];
$dbpass = $env_var_db['dbpass'];
$local = $env_var_db['local'];

require("NotificationClass.php");

// Connect to server and select databse.
$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
		
if (!$con) die('Could not connect: ' . mysql_error());

$sender_id = htmlentities($_GET['sender_id']);
$receiver_id = htmlentities($_GET['receiver_id']);
$is_sender_doctor = (htmlentities($_GET['is_sender_doctor']) === 'true' ? true : false);
$is_receiver_doctor = (htmlentities($_GET['is_receiver_doctor']) === 'true' ? true : false);
$notifType = htmlentities($_GET['notifType']);
$pat_id = htmlentities($_GET['pat_id']);

$notifications = new Notifications();

$foundDocs = array();
                    
//IF THE PATIENT HAS BEEN REFERRED, NOTIFY THIS OCCURRENCE TO THE DOCTORS WHO REFERRED THIS PATIENT
$findRefDocs = $con->prepare('SELECT IdMED, IdMED2 FROM doctorslinkdoctors WHERE IdPac = ? AND (IdMED = ? OR IdMED2 = ?) UNION SELECT IdMED, null AS IdMED2 FROM doctorslinkusers WHERE IdUs = ?');

if($is_sender_doctor) {
    $findRefDocs->bindValue(1, $pat_id, PDO::PARAM_INT);
    $findRefDocs->bindValue(2, $sender_id, PDO::PARAM_INT);
}
else {
    $findRefDocs->bindValue(1, $pat_id, PDO::PARAM_INT);
    $findRefDocs->bindValue(2, $receiver_id, PDO::PARAM_INT);
}

if($is_receiver_doctor) {
    $findRefDocs->bindValue(3, $receiver_id, PDO::PARAM_INT);
    $findRefDocs->bindValue(4, $pat_id, PDO::PARAM_INT);
}
else {
    $findRefDocs->bindValue(3, $sender_id, PDO::PARAM_INT);
    $findRefDocs->bindValue(4, $pat_id, PDO::PARAM_INT);
}

$findRefDocs->execute();

//making an array of found doctor ids without null nor duplicate
while($RefDocRow = $findRefDocs->fetch(PDO::FETCH_ASSOC)) {
    if(!in_array($RefDocRow['IdMED'], $foundDocs)) array_push($foundDocs, $RefDocRow['IdMED']);
    else if($RefDocRow['IdMED2'] !== NULL) array_push($foundDocs, $RefDocRow['IdMED2']);
}


// doctor
if($is_sender_doctor)
{
    //to the patient
    if(!$is_receiver_doctor) $notifications->add($notifType, $sender_id, true, $receiver_id, false, null);
    //to the related doctors
    //error_log('founddocs: '.$foundDocs);
    foreach($foundDocs as $foundDoc) 
        if($foundDoc !== NULL && $foundDoc != $sender_id)
            $notifications->add($notifType, $sender_id, true, $foundDoc, true, $pat_id);
}
//patient
else
{
    //to the related doctors
    foreach($foundDocs as $foundDoc)
        if($foundDoc !== null)
            $notifications->add($notifType, $sender_id, false, $foundDoc, true, $pat_id);
}
?>