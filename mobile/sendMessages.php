<?php
 //   echo '<table><tr><td>TEST</td></tr></table>';
//require "identicon.php";
 
 require("../environment_detail.php");
require_once('..realtime-notifications/pusherlib/lib/Pusher.php');
 
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];

$tbl_name="MESSAGES"; // Table name

$link = mysql_connect("$dbhost", "$dbuser", "$dbpass")or die("cannot connect");
mysql_select_db("$dbname")or die("cannot select DB");	

$IdMED = $_POST['IdMED'];
$scenario = 0;
if (isset($_POST['scenario']))
{
    $scenario =  $_POST['scenario'];
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

if ($scenario == 'patient') 
{
    $q = "INSERT INTO message_infrastructureuser SET Subject='".$subject."', content='".$content."', tofrom='from', sender_id=".$IdMED.", receiver_id=".$IdMED.", fecha=NOW(), status='new', patient_id=".$IdRECEIVER.", inbox=1, outbox=1, connection_id=0";
}
else
{
    $q = "INSERT INTO message_infrasture SET Subject='".$subject."', content='".$content."', sender_id=".$IdMED.", receiver_id=".$IdRECEIVER.", fecha=NOW(), status='new', inbox=1, outbox=1, connection_id=0, is_mobile=1";
    
}
echo $q;
$result = mysql_query($q);

$app_key = 'd869a07d8f17a76448ed';
$app_secret = '92f67fb5b104260bbc02';
$app_id = '51379';
$pusher = new Pusher($app_key, $app_secret, $app_id);
$pusher->trigger($IdRECEIVER, 'notification', 'refresh');

$ch = curl_init();

if($ch)
{
    $getname = mysql_query("select IdMEDFIXEDNAME from doctors where id=".$IdMED);
    $row11 = mysql_fetch_array($getname);
    $sendername = $row11['IdMEDFIXEDNAME'];
    $sendername = str_replace(".", " ", $sendername);
    
    $push_payload = json_encode(array(
            "channels" => array("C-".$IdRECEIVER),
            "data" => array("alert" => $sendername.' - '.$content, "badge" => "Increment")
    ));
    echo $push_payload;
    
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
    curl_setopt($CH,CURLOPT_HEADER, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $push_payload);
    
    $res = curl_exec($ch);
    echo $res;
    curl_close($ch);
}

echo $result;

?>