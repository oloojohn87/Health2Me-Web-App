<?php
 //   echo '<table><tr><td>TEST</td></tr></table>';
//require "identicon.php";
 
 require("../environment_detail.php");
 
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];

$tbl_name="MESSAGES"; // Table name

$link = mysql_connect("$dbhost", "$dbuser", "$dbpass")or die("cannot connect");
mysql_select_db("$dbname")or die("cannot select DB");	

$userID = $_POST['User_ID'];
$contactID = $_POST['CONTACT_ID'];
$update = 0;
if(isset($_POST['UPDATE']))
{
    $update = $_POST['UPDATE'];
}

//$setQuery = "SELECT * FROM ".$WhatDB." WHERE receiver_id=".$IdMED." AND DATEDIFF(NOW(), fecha) <=  60 AND status='new' ORDER BY fecha DESC";
//$setQuery = "SELECT * FROM ".$WhatDB." WHERE receiver_id=".$IdMED." ORDER BY fecha DESC LIMIT 10";
$setQuery = "SELECT * FROM message_infrasture WHERE receiver_id='".$userID."' AND sender_id='".$contactID."' ORDER BY fecha DESC";
$result = mysql_query($setQuery);

$num_msg = 0;
$cadena = array();
$num_messages = mysql_num_rows($result);

while ($row = mysql_fetch_array($result)) {
    $hasAttachments=false;
    $id=$row['message_id'];
    $subject=$row['Subject'];
    $content=$row['content'];
    $senderid=$row['sender_id'];
    $status=$row['status'];
    $reportids=$row['attachments'];
    
    // set message status to 'read'
    if($update == 1)
    {
        $update_q = "UPDATE TABLE message_infrasture SET status='read' WHERE message_id=".$id;
        $update_r = mysql_query($update_q);
    }
    
    
    if($reportids!=null and $reportids!=0){
    $hasAttachments=true;
    }
    $getname = mysql_query("select IdMEDFIXEDNAME,IdMEDEmail from doctors where id='$senderid'");
    $row11 = mysql_fetch_array($getname);
    $sendername = $row11['IdMEDFIXEDNAME'];
    $sendername = str_replace(".", " ", $sendername);
    if(strlen($sendername) <= 0)
    {
        $sendername = $row11['IdMEDEmail'];
    }
    if($id > 0 && $senderid > 0 && strlen($sendername) > 4)
    {
        $arr = array("ID" => $id, 
         "ID_sender" => $senderid, 
         "NAME_sender" => $sendername,
         "SUBJECT" => $subject, 
         "CONTENT" => $content, 
         "DATE" => $row['fecha'],
         "TYPE" => 'to'
        );
            array_push($cadena, $arr);
    }

}

$setQuery = "SELECT * FROM message_infrasture WHERE sender_id='".$userID."' AND receiver_id='".$contactID."' ORDER BY fecha DESC";
$result = mysql_query($setQuery);

while ($row = mysql_fetch_array($result)) {
    $hasAttachments=false;
    $id=$row['message_id'];
    $subject=$row['Subject'];
    $content=$row['content'];
    $senderid=$row['sender_id'];
    $status=$row['status'];
    $reportids=$row['attachments'];
    
    
    
    if($reportids!=null and $reportids!=0){
    $hasAttachments=true;
    }
    $getname = mysql_query("select IdMEDFIXEDNAME,IdMEDEmail from doctors where id='$senderid'");
    $row11 = mysql_fetch_array($getname);
    $sendername = $row11['IdMEDFIXEDNAME'];
    $sendername = str_replace(".", " ", $sendername);
    if(strlen($sendername) <= 0)
    {
        $sendername = $row11['IdMEDEmail'];
    }
    
    if($id > 0 && $senderid > 0 && strlen($sendername) > 4)
    {
        $arr = array("ID" => $id, 
         "ID_sender" => $senderid, 
         "NAME_sender" => $sendername,
         "SUBJECT" => $subject, 
         "CONTENT" => $content, 
         "DATE" => $row['fecha'],
         "TYPE" => 'from'
        );
            array_push($cadena, $arr);
    }

}

if(count($cadena) > 0)
{
    for($x = 0; $x < count($cadena) - 1; $x++)
    {
        for($y = $x + 1; $y < count($cadena); $y++)
        {
            if($cadena[$x]["DATE"] > $cadena[$y]["DATE"])
            {
                $temp_val = $cadena[$x];
                $cadena[$x] = $cadena[$y];
                $cadena[$y] = $temp_val;
            }
        }
    }
}

$encode = json_encode($cadena);
echo $encode; 




    
    

?>


