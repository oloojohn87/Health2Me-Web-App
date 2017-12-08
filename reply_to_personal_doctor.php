<?php

require("environment_detail.php");
require("NotificationClass.php");
//require_once('realtime-notifications/pusherlib/lib/Pusher.php');
require_once('push/push.php');
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

$notifications = new Notifications();

$id = $_POST['id'];

if(isset($_POST['setRead']) && $_POST['setRead'] == 1)
{
    // if setRead is set to 1, then we are only setting this message as 'read'
    $result = $con->prepare("UPDATE message_infrastructureuser SET status = 'read' WHERE message_id = ?");
    $result->bindValue(1, $id, PDO::PARAM_INT);
    $result->execute();
}
else
{
    $user = $_POST['user'];
    $message = $_POST['message'];
    
    // first obtain the original message that the user is replying to
    $get = $con->prepare("SELECT * FROM message_infrastructureuser WHERE message_id = ?");
    $get->bindValue(1, $id, PDO::PARAM_INT);
    $get->execute();
    $get_row = $get->fetch(PDO::FETCH_ASSOC);

    // add the new message to the message_infrastructureuser table 
    $insert = $con->prepare("INSERT INTO message_infrastructureuser SET Subject = ?, content = ?, tofrom = 'to', sender_id = ?, receiver_id = ? , fecha = NOW(), status = 'new', patient_id = ?, replied = 0");
    $insert->bindValue(1, "RE: ".$get_row['Subject'], PDO::PARAM_STR);
    $insert->bindValue(2, $message, PDO::PARAM_STR);
    $insert->bindValue(3, $get_row['sender_id'], PDO::PARAM_INT);
    $insert->bindValue(4, $get_row['receiver_id'], PDO::PARAM_INT);
    $insert->bindValue(5, $get_row['patient_id'], PDO::PARAM_INT);
    $insert->execute();
    
    $notifications->add('NEWMES', $get_row['patient_id'], false, $get_row['sender_id'], true, $con->lastInsertId());
    
    // send a notification to the doctor of the new message
    //$app_key = 'd869a07d8f17a76448ed';
    //$app_secret = '92f67fb5b104260bbc02';
    //$app_id = '51379';
    //$pusher = new Pusher($app_key, $app_secret, $app_id);
    $push = new Push();
    
    $pat = $con->prepare("SELECT Name,Surname FROM usuarios WHERE Identif = ?");
    $pat->bindValue(1, $get_row['patient_id'], PDO::PARAM_INT);
    $pat->execute();
    $pat_row = $pat->fetch(PDO::FETCH_ASSOC);
    
    $push->send($get_row['sender_id'], 'notification', 'New message from '.$pat_row['Name'].' '.$pat_row['Surname']);

    // set the original message that the user is replying to as replied and read in the data table
    $update = $con->prepare("UPDATE message_infrastructureuser SET replied = 1, status = 'read' WHERE message_id = ?");
    $update->bindValue(1, $id, PDO::PARAM_INT);
    $update->execute();

    echo $id;
}
?>