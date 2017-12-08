<?php
 //   echo '<table><tr><td>TEST</td></tr></table>';
//require "identicon.php";
 
 require("environment_detail.php");
require("NotificationClass.php");
//require_once('realtime-notifications/pusherlib/lib/Pusher.php');
require_once('../../push/push.php');

 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];

$tbl_name="messages"; // Table name

$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
{
    die('Could not connect: ' . mysql_error());
}		

$notifications = new Notifications();

$IdMED = $_POST['IdMED'];
$scenario = 0;
if (isset($_POST['scenario']))
{
    $scenario =  $_POST['scenario'];
}
$mes_id = null;
if (isset($_POST['mes']))
{
    $mes_id =  $_POST['mes'];
}
$subject = '';
if (isset($_POST['SUBJECT']))
{
    $subject =  $_POST['SUBJECT'];
}
$content = '';
if (isset($_POST['MESSAGE']))
{
    $content =  $_POST['MESSAGE'];
}
$IdRECEIVER = '';
if (isset($_POST['IdRECEIVER']))
{
    $IdRECEIVER =  $_POST['IdRECEIVER'];
}
$status = '';
if (isset($_POST['status']))
{
    $status =  $_POST['status'];
}
$type = '';
if (isset($_POST['type']))
{
    $type =  $_POST['type'];
}
$WhatDB = '';
if ($scenario == 'patient') 
{
	$WhatDB = 'message_infrastructureuser'; 
	$SwitchDr = 0;
} else 
{ 
	$WhatDB = 'message_infrasture'; 
	$SwitchDr = 1;
}
if($type == 'read')
{
    if(strlen($status) > 0 && $mes_id != null)
    {
        $updateQuery = "UPDATE ".$WhatDB." ";
        if(strcmp($status, 'read') == 0)
        {
            $updateQuery .= "SET status='read' ";
        }
        else if(strcmp($status, 'new') == 0)
        {
            $updateQuery .= "SET status='new' ";
        }
        $updateQuery .= "WHERE message_id=?";
        
        $query = $con->prepare($updateQuery);
        $query->bindValue(1, $mes_id, PDO::PARAM_INT);
        $result = $query->execute();
        
        echo $result;
    }
}
else if($type == 'delete')
{
    if(count($mes_id) > 0)
    {
        $q = "DELETE FROM ".$WhatDB." WHERE ";
        for($i = 0; $i < count($mes_id); $i++)
        {
            if($i > 0)
            {
                $q .= " OR ";
            }
            $q .= "message_id=?";
        }
        
        $query = $con->prepare($q);
        for($i = 0; $i < count($mes_id); $i++)
        {
            $query->bindValue(($i + 1), $mes_id[$i], PDO::PARAM_INT);
        }
        $result = $query->execute();
        echo $result;
    }
}
else if($type == 'unread')
{
    if(strlen($status) > 0 && $mes_id != null)
    {
        $updateQuery = "UPDATE ".$WhatDB." ";
        if(strcmp($status, 'read') == 0)
        {
            $updateQuery .= "SET status='read' ";
        }
        else if(strcmp($status, 'new') == 0)
        {
            $updateQuery .= "SET status='new' ";
        }
        $updateQuery .= "WHERE ";
        for($i = 0; $i < count($mes_id); $i++)
        {
            if($i > 0)
            {
                $updateQuery .= " OR ";
            }
            $updateQuery .= "message_id=?";
        }
        
        $query = $con->prepare($updateQuery);
        for($i = 0; $i < count($mes_id); $i++)
        {
            $query->bindValue(($i + 1), $mes_id[$i], PDO::PARAM_INT);
        }
        $result = $query->execute();
        echo $result;
    }
}
else if($type == 'send')
{
    if ($scenario == 'patient') 
    {
        $q = "INSERT INTO message_infrastructureuser SET Subject=?, content=?, tofrom='from', sender_id=?, receiver_id=?, fecha=NOW(), status='new', patient_id=?, inbox=1, outbox=1, connection_id=0";
        $query = $con->prepare($q);
        $query->bindValue(1, $subject, PDO::PARAM_STR);
        $query->bindValue(2, $content, PDO::PARAM_STR);
        $query->bindValue(3, $IdMED, PDO::PARAM_INT);
        $query->bindValue(4, $IdMED, PDO::PARAM_INT);
        $query->bindValue(5, $IdRECEIVER, PDO::PARAM_INT);
        $query->execute();
        
        $notifications->add('NEWMES', $IdMED, true, $IdRECEIVER, false, $con->lastInsertId());
        
        $query = $con->prepare("SELECT Name,Surname FROM doctors WHERE id=?");
        $query->bindValue(1, $IdMED, PDO::PARAM_INT);
        $query->execute();
        $row_receiver = $query->fetch(PDO::FETCH_ASSOC);
        
        //$app_key = 'd869a07d8f17a76448ed';
        //$app_secret = '92f67fb5b104260bbc02';
        //$app_id = '51379';
        //$pusher = new Pusher($app_key, $app_secret, $app_id);
        $push = new Push();
        $push->send($IdRECEIVER, 'notification', 'New message from Dr. '.$row_receiver['Name'].' '.$row_receiver['Surname']);
    }
    else
    {
        $q = "INSERT INTO message_infrasture SET Subject=?, content=?, sender_id=?, receiver_id=?, fecha=NOW(), status='new', inbox=1, outbox=1, connection_id=0";
        $query = $con->prepare($q);
        $query->bindValue(1, $subject, PDO::PARAM_STR);
        $query->bindValue(2, $content, PDO::PARAM_STR);
        $query->bindValue(3, $IdMED, PDO::PARAM_INT);
        $query->bindValue(4, $IdRECEIVER, PDO::PARAM_INT);
        $query->execute();
        
        $notifications->add('NEWMES', $IdMED, true, $IdRECEIVER, true, $con->lastInsertId());
        
        $query = $con->prepare("SELECT Name,Surname FROM doctors WHERE id=?");
        $query->bindValue(1, $IdRECEIVER, PDO::PARAM_INT);
        $query->execute();
        $row_receiver = $query->fetch(PDO::FETCH_ASSOC);
        
        
        //$app_key = 'd869a07d8f17a76448ed';
        //$app_secret = '92f67fb5b104260bbc02';
        //$app_id = '51379';
        //$pusher = new Pusher($app_key, $app_secret, $app_id);
        $push = new Push();
        $push->send($IdRECEIVER, 'notification', 'New message from Dr. '.$row_receiver['Name'].' '.$row_receiver['Surname']);
        
        
        $ch = curl_init();

        if($ch)
        {
            
            $query = $con->prepare("select IdMEDFIXEDNAME from doctors where id=?");
            $query->bindValue(1, $IdMED, PDO::PARAM_INT);
            $query->execute();
            $row11 = $query->fetch(PDO::FETCH_ASSOC);
            
            $sendername = $row11['IdMEDFIXEDNAME'];
            $sendername = str_replace(".", " ", $sendername);
            
            $push_payload = json_encode(array(
                    "channels" => array("C-".$IdRECEIVER),
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
        
        
    }
    
}





    
    

?>


