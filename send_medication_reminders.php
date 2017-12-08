<?php
require "twilio-4_0_0/Services/Twilio.php";
require("environment_detailForLogin.php");
require("NotificationClass.php");

$dbhost = $env_var_db['dbhost'];
$dbname = $env_var_db['dbname'];
$dbuser = $env_var_db['dbuser'];
$dbpass = $env_var_db['dbpass'];
$hardcode = $env_var_db['hardcode'];
$local = $env_var_db['local'];

$AccountSid = "AC109c7554cf28cdfe596e4811c03495bd";
$AuthToken = "26b187fb3258d199a6d6edeb7256ecc1";

$client = new Services_Twilio($AccountSid, $AuthToken);

$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
{
    die('Could not connect: ' . mysql_error());
}

$notifications = new Notifications();

$reminders = $con->prepare("SELECT MR.*,U.telefono AS phone FROM (SELECT * FROM medication_reminders WHERE active = 1 AND (UNIX_TIMESTAMP(NOW()) + 150) > (last_sent + (3600 * frequency)) AND last_sent IS NOT NULL) AS MR LEFT JOIN (SELECT Identif,telefono FROM usuarios) AS U ON U.Identif = MR.user");
$reminders->execute();

$timestamp = time();
$from = '+19034018888';

while($row = $reminders->fetch(PDO::FETCH_ASSOC))
{
    echo 'SENDING '.$row['id'];
    $body = 'This is a reminder to take '.$row['name'].'. After taking it, please reply with "MED'.$row['id'].'"';
    $message = $client->account->messages->sendMessage($from, '+'.$row['phone'], $body, array($row['image']));
}

$update = $con->prepare("UPDATE medication_reminders SET last_sent = ? WHERE active = 1 AND ((UNIX_TIMESTAMP(NOW()) + 150) > (last_sent + (3600 * frequency))) AND last_sent IS NOT NULL");
$update->bindValue(1, $timestamp, PDO::PARAM_INT);
$update->execute();

$alert_update = $con->prepare("UPDATE medication_reminders SET misses = misses + 1 WHERE id IN(SELECT id FROM (SELECT MR.id, MAX(MRR.date) > MR.last_sent AS responded FROM medication_reminders MR LEFT JOIN medication_reminders_response MRR ON MRR.reminder_id = MR.id WHERE MR.last_sent > (UNIX_TIMESTAMP(NOW()) - 1020) AND MR.last_sent < (UNIX_TIMESTAMP(NOW()) - 780) GROUP BY MR.id) M WHERE responded = false)");
$alert_update->execute();

$alert = $con->prepare("SELECT MR.id, user, MAX(MRR.date) > MR.last_sent AS responded FROM medication_reminders MR LEFT JOIN medication_reminders_response MRR ON MRR.reminder_id = MR.id WHERE MR.last_sent > (UNIX_TIMESTAMP(NOW()) - 1020) AND MR.last_sent < (UNIX_TIMESTAMP(NOW()) - 780) AND MR.misses >= MR.alert GROUP BY MR.id");
$alert->execute();
while($row = $alert->fetch(PDO::FETCH_ASSOC))
{
    $notifications->add('MEDALR', 0, false, $row['user'], false, $row['id']);
}

?>