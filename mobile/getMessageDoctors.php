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

//$setQuery = "SELECT * FROM ".$WhatDB." WHERE receiver_id=".$IdMED." AND DATEDIFF(NOW(), fecha) <=  60 AND status='new' ORDER BY fecha DESC";
//$setQuery = "SELECT * FROM ".$WhatDB." WHERE receiver_id=".$IdMED." ORDER BY fecha DESC LIMIT 10";
$setQuery = "SELECT DISTINCT sender_id,receiver_id FROM message_infrasture WHERE receiver_id='".$userID."' OR sender_id='".$userID."' ORDER BY fecha DESC";
$result = mysql_query($setQuery);

$num_msg = 0;
$cadena = array();
$already_in = array();
$num_messages = mysql_num_rows($result);

while ($row = mysql_fetch_array($result)) {
    $senderid=$row['sender_id'];
    $receiverid=$row['receiver_id'];
    
    $getname = mysql_query("select IdMEDFIXEDNAME,IdMEDEmail from doctors where id='$senderid'");
    $row11 = mysql_fetch_array($getname);
    $sendername = $row11['IdMEDFIXEDNAME'];
    $sendername = str_replace(".", " ", $sendername);
    if(strlen($sendername) <= 0)
    {
        $sendername = $row11['IdMEDEmail'];
    }
    
    $getname = mysql_query("select IdMEDFIXEDNAME,IdMEDEmail from doctors where id='$receiverid'");
    $row11 = mysql_fetch_array($getname);
    $receivername = $row11['IdMEDFIXEDNAME'];
    $receivername = str_replace(".", " ", $receivername);
    if(strlen($receivername) <= 0)
    {
        $receivername = $row11['IdMEDEmail'];
    }
    
    if($senderid != $userID && !in_array($senderid, $already_in))
    {
        if($senderid > 0 && $sendername != NULL)
        {
            $setQ = "SELECT status FROM message_infrasture WHERE sender_id='".$senderid."' AND receiver_id='".$userID."' AND status='new'";
            $res = mysql_query($setQ);
            $num = 0;
            if($res)
            {
                $num = mysql_num_rows($res);
            }
            $arr = array("Doctor_ID" => $senderid, "Doctor_Name" => $sendername, "New" => $num);
            array_push($cadena, $arr);
            array_push($already_in, $senderid);
        }
        
    }
    if($receiverid != $userID && !in_array($receiverid, $already_in))
    {
        if($receiverid > 0 && $receivername != NULL)
        {
            $setQ = "SELECT status FROM message_infrasture WHERE sender_id='".$receiverid."' AND receiver_id='".$userID."' AND status='new'";
            $res = mysql_query($setQ);
            $num = 0;
            if($res)
            {
                $num = mysql_num_rows($res);
            }
            $arr = array("Doctor_ID" => $receiverid, "Doctor_Name" => $receivername, "New" => $num);
            array_push($cadena, $arr);
            array_push($already_in, $receiverid);
        }
        
    }

}

for($i = 1; $i < count($cadena); $i++)
{
    for($k = $i - 1; $k >= 0; $k--)
    {
        if(strcmp($cadena[$k]["Doctor_Name"], $cadena[$i]["Doctor_Name"]) > 0)
        {
            $temp = $cadena[$k];
            $cadena[$k] = $cadena[$i];
            $cadena[$i] = $temp;
        }
    }
}

$encode = json_encode($cadena);
echo $encode; 




    
    

?>


