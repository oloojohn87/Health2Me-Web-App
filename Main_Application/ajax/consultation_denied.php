<?php

require("push_server.php");
require "environment_detail.php";
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

//$app_key = 'd869a07d8f17a76448ed';
//$app_secret = '92f67fb5b104260bbc02';
//$app_id = '51379';
//$pusher = new Pusher($app_key, $app_secret, $app_id);
$push = new Push();

$doc = $_POST['doc'];
$pat = $_POST['pat'];

$query = $con->prepare("UPDATE doctors SET in_consultation=0,telemed_type=0 WHERE id=?");
$query->bindValue(1, $doc, PDO::PARAM_INT);
$query->execute();

$result = $con->prepare("SELECT consultationId FROM consults WHERE Doctor = ? ORDER BY consultationId DESC LIMIT 1");
$result->bindValue(1, $doc, PDO::PARAM_INT);
$result->execute();

$row = $result->fetch(PDO::FETCH_ASSOC);
$query = $con->prepare("UPDATE consults SET length = TIMESTAMPDIFF(SECOND,DateTime,now()), Status = 'Canceled' WHERE consultationId = ?");
$query->bindValue(1, $row['consultationId'], PDO::PARAM_INT);
$query->execute();

$push->send($pat, 'doc_response', 'n');
?>