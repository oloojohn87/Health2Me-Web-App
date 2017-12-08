<?php
 //   echo '<table><tr><td>TEST</td></tr></table>';
//require "identicon.php";
 
require("environment_detail.php");
//require_once('realtime-notifications/pusherlib/lib/Pusher.php');
require_once('push/push.php');
require("NotificationClass.php");
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];

$tbl_name="messages"; // Table name

	

$senderid = $_GET['sender'];
$receiverid = $_GET['receiver'];
$content= $_GET['content'];
$subject= $_GET['subject'];
$patientid=$_GET['patient'];
$message_type = empty($_GET['type'])?null:$_GET['type'];
$attachments=empty($_GET['attachments'])?0:$_GET['attachments'];
$connection_id=$_GET['connection_id'];


$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }	

$notifications = new Notifications();
  
    
$unamesql = $con->prepare('SELECT Name, Surname FROM usuarios WHERE Identif = ?');
$unamesql->bindValue(1, $patientid, PDO::PARAM_INT);
$unamesql->execute();
$unameres = $unamesql->fetch(PDO::FETCH_ASSOC);

if($subject == "I referred a new member") $subject .= ': '.$unameres["Name"].' '.$unameres["Surname"].' to you.';
else if($subject == "Referral stage information") {
    if($content == "referal stage information review is completed") $content .= ': '.$unameres["Name"].' '.$unameres["Surname"].'. This is a System Generated Message.';
    else if($content == "The referral has been rejected") $content .= ': '.$unameres["Name"].' '.$unameres["Surname"].'. This is a System Generated Message.';
    else if($content == "referal stage Acknowledgement is completed") $content .= ': '.$unameres["Name"].' '.$unameres["Surname"].'. This is a System Generated Message.';
    else if($content == "referal stage visit is completed") $content .= ': '.$unameres["Name"].' '.$unameres["Surname"].'. This is a System Generated Message.';
    else if($content == "referal stage Comment is completed") $content .= ': '.$unameres["Name"].' '.$unameres["Surname"].'. This is a System Generated Message.';
    else if($content == "referal stage appointment is completed") $content .= ': '.$unameres["Name"].' '.$unameres["Surname"].'. This is a System Generated Message.';
}        

$sql_que = $con->prepare("Insert into message_infrasture set Subject=?,content=?,sender_id=?,receiver_id=?,patient_id=?,connection_id=?,attachments=?,fecha=now(),status='new', message_type=?");

$sql_que->bindValue(1, $subject, PDO::PARAM_STR);
$sql_que->bindValue(2, $content, PDO::PARAM_STR);
$sql_que->bindValue(3, $senderid, PDO::PARAM_INT);
$sql_que->bindValue(4, $receiverid, PDO::PARAM_INT);
$sql_que->bindValue(5, $patientid, PDO::PARAM_INT);
$sql_que->bindValue(6, $connection_id, PDO::PARAM_INT);
$sql_que->bindValue(7, $attachments, PDO::PARAM_STR);
$sql_que->bindValue(8, $message_type, PDO::PARAM_STR);

$res = $sql_que->execute();

$notifications->add('NEWMES', $senderid, true, $receiverid, true, $con->lastInsertId());

if($res)
echo 'Message Sent';
else
echo 'Message sending error';

//$app_key = 'd869a07d8f17a76448ed';
//$app_secret = '92f67fb5b104260bbc02';
//$app_id = '51379';
//$pusher = new Pusher($app_key, $app_secret, $app_id);
$push = new Push();
$push->send($receiverid, 'notification', $subject);


$ch = curl_init();

if($ch)
{
    $getname = $con->prepare("select IdMEDFIXEDNAME from doctors where id=?");
	$getname->bindValue(1, $senderid, PDO::PARAM_INT);
	$getname->execute();
	
    $row11 = $getname->fetch(PDO::FETCH_ASSOC);
    $sendername = $row11['IdMEDFIXEDNAME'];
    $sendername = str_replace(".", " ", $sendername);
    
    $push_payload = json_encode(array(
            "channels" => array("C-".$receiverid),
            "data" => array("alert" => $sendername.' - '.$content, "badge" => "Increment", "sound" => "")
    ));
    
    curl_setopt($ch,CURLOPT_HTTPHEADER,
        array("X-Parse-Application-Id: seKhmZv38COzZXeen83xHysTR719zkyVtlbhZSjh",
                "X-Parse-REST-API-Key: wo9jZMBCLuBxtYb3CsM5k9m65M7UgqnKBkRZhSEm",
                "Content-Type: application/json"));
    //curl_setopt($ch, CURLOPT_HTTPHEADER, $arr);
    curl_setopt($ch, CURLOPT_URL, 'https://api.parse.com/1/push');
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch,CURLOPT_PORT,443);
    curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch,CURLOPT_HEADER, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $push_payload);
    
    $res = curl_exec($ch);
    curl_close($ch);
}




?>
