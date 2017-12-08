<?php
 //   echo '<table><tr><td>TEST</td></tr></table>';
//require "identicon.php";
 
 require("environment_detail.php");
 
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

$IdMED = $_POST['IdMED'];
$scenario = 0;
if (isset($_POST['scenario']))
{
    $scenario =  $_POST['scenario'];
}
$search = '';
if (isset($_POST['search']))
{
    $search = $_POST['search'];
}
$page = 1;
if (isset($_POST['page']))
{
    $page =  intval($_POST['page']);
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

str_replace('"', "", $search);
str_replace("'", "", $search);
$search_words = explode(" ", $search);
$num_search_words = count($search_words);

//$setQuery = "SELECT * FROM ".$WhatDB." WHERE receiver_id=".$IdMED." AND DATEDIFF(NOW(), fecha) <=  60 AND status='new' ORDER BY fecha DESC";
$setQuery = '';
if ($scenario == 'patient') 
{
    $setQuery = $con->prepare("SELECT * FROM message_infrastructureuser WHERE receiver_id=? AND tofrom='to' ORDER BY fecha DESC");
	$setQuery->bindValue(1, $IdMED, PDO::PARAM_INT);
	
}
else
{
    $setQuery = $con->prepare("SELECT * FROM message_infrasture WHERE receiver_id=? ORDER BY fecha DESC");
	$setQuery->bindValue(1, $IdMED, PDO::PARAM_INT);
}
$result = $setQuery->execute();

$num_msg = 0;
$cadena = array();
$num_messages = $setQuery->rowCount();

while ($row = $setQuery->fetch(PDO::FETCH_ASSOC)) {
    //if($num_msg < 5)
    //{
        $id = $row["message_id"];
        $subject = $row['Subject'];
        $content = $row['content'];
        $senderid = $row['sender_id'];
        $patientid = $row['patient_id'];
        $status = $row['status'];
        $reportids = $row['attachments'];
        $mobile = false;
        if(isset($row['is_mobile']))
        {
            if($row['is_mobile'] == 1)
            {
                $mobile = true;
            }
        }
        $hasAttachments=false;
        
        $senderID = $senderid;
        
        if($reportids != null && $reportids != 0)
        {
            $hasAttachments=true;
        }
        
        if ($SwitchDr == 1)
        {
            $getname = $con->prepare("select * from doctors where id=?");
			$getname->bindValue(1, $senderid, PDO::PARAM_INT);
			$getname->execute();
			
            $row11 = $getname->fetch(PDO::FETCH_ASSOC);
            $senderid = $row11['IdMEDFIXEDNAME'];
            $initials = strtoupper(substr($row11['Name'], 0, 1)).' '.strtoupper(substr($row11['Surname'], 0, 1));
            $NameSender = strtoupper(substr($row11['Name'], 0, 1)).substr($row11['Name'], 1).' '.strtoupper(substr($row11['Surname'], 0, 1)).substr($row11['Surname'], 1) ;
        };
        
        $getname = $con->prepare("select * from usuarios where Identif=?");
		$getname->bindValue(1, $patientid, PDO::PARAM_INT);
		$getname->execute();
		
        $row11 = $getname->fetch(PDO::FETCH_ASSOC);
        $initialsP = strtoupper(substr($row11['Name'], 0, 1)).' '.strtoupper(substr($row11['Surname'], 0, 1));
        $NamePatient = strtoupper(substr($row11['Name'], 0, 1)).substr($row11['Name'], 1).' '.strtoupper(substr($row11['Surname'], 0, 1)).substr($row11['Surname'], 1) ;
        if ($SwitchDr == 0)
        {
            $senderid = $patientid;
            $initials = $initialsP;
            $NameSender = $NamePatient;
        };
        
        $rep_date = $row['fecha'];
        $year=strtok($rep_date,"-");
        $month=strtok("-");
        $day = strtok(" ");
        $month = getMonth(intval($month));
        $rep_date = $month." ".$day.", ".$year;
        // unread 0 means ALL MESSAGES, unread 1 means ONLY NEW MESSAGES
        
        $queini = ord($initials); 
        $whatcolor = '#0066CC';
        switch (true)
        {
            case ($queini == 0):
                $result = "Equals Zero.";
                break;
            case (($queini >= 64) && ($queini <= 78)):		
                $whatcolor = '#0066CC';
                break;
            case (($queini >= 79) && ($queini <= 100)):		
                $whatcolor = '#00CC66';
                break;
            case (($queini >= 101) ):		
                $whatcolor = '#663300';
                break;
        }
        $Picfound = 0;
		$PicLocation1 = 'DoctorImage/'.$senderID.'.jpg';
		$PicLocation2 = 'DoctorImage/'.$senderID.'.png';
		$PicLocation3 = 'DoctorImage/'.$senderID.'.gif';
        $PicLocation = '';
        if(file_exists($PicLocation1))
        {
            $Picfound = 1;
            $PicLocation = $PicLocation1;
        }
        else if(file_exists($PicLocation2))
        {
            $Picfound = 1;
            $PicLocation = $PicLocation2;
        }
        else if(file_exists($PicLocation3)) 
        {
            $Picfound = 1;
            $PicLocation = PicLocation3;										
        }
        if($Picfound == 0)
        {
            $PicLocation = '<div class="LetterCircleON" style="float:none; margin:0 auto; margin-top: 5px; border:none; width:30px; height:30px; font-size:12px; background-color:'.$whatcolor.';"><p style="margin:0px; padding:0px; margin-top:8px;">'.$initials.'</p></div>';
        }
        
        if($num_search_words > 0)
        {
            if(match($NameSender, $subject, $content, $search_words, $num_search_words))
            {
                if($num_msg >= (($page - 1) * 20) && $num_msg < ($page * 20))
                {
                    
                    $arr = array("ID" => $id, 
                                 "ID_pat" => $patientid, 
                                 "NAME_pat" => $NamePatient, 
                                 "INIT_pat" => $initialsP,
                                 "ID_sender" => $senderID, 
                                 "NAME_sender" => $NameSender,
                                 "INIT_sender" => $initials,
                                 "SUBJECT" => $subject, 
                                 "CONTENT" => $content, 
                                 "DATE" => $rep_date,
                                 "PIC" => $PicLocation,
                                 "ATT" => $hasAttachments,
                                 "ATTACHMENTS" => $reportids,
                                 "STATUS" => $status,
                                 "NUM" => $num_messages,
                                 "MOBILE" => $mobile
                                );
                    array_push($cadena, $arr);
                }
                $num_msg++;
            }
        }
        else
        {
            if($num_msg >= (($page - 1) * 20) && $num_msg < ($page * 20))
            {
                
                $arr = array("ID" => $id, 
                             "ID_pat" => $patientid, 
                             "NAME_pat" => $NamePatient, 
                             "INIT_pat" => $initialsP,
                             "ID_sender" => $senderID, 
                             "NAME_sender" => $NameSender,
                             "INIT_sender" => $initials,
                             "SUBJECT" => $subject, 
                             "CONTENT" => $content, 
                             "DATE" => $rep_date,
                             "PIC" => $PicLocation,
                             "ATT" => $hasAttachments,
                             "ATTACHMENTS" => $reportids,
                             "STATUS" => $status,
                             "NUM" => $num_messages,
                             "MOBILE" => $mobile
                            );
                array_push($cadena, $arr);
            }
            $num_msg++;
        }

}

for($k = 0; $k < count($cadena); $k++)
{
    $cadena[$k]['NUM'] = $num_msg;
}

$encode = json_encode($cadena);
echo $encode;



function getMonth($mon)
{
    $result = '';
    switch($mon)
    {
        case 1:
            $result = "Jan.";
            break;
        case 2:
            $result = "Feb.";
            break;
        case 3:
            $result = "Mar.";
            break;
        case 4:
            $result = "Apr.";
            break;
        case 5:
            $result = "May";
            break;
        case 6:
            $result = "Jun.";
            break;
        case 7:
            $result = "Jul.";
            break;
        case 8:
            $result = "Aug.";
            break;
        case 9:
            $result = "Sep.";
            break;
        case 10:
            $result = "Oct.";
            break;
        case 11:
            $result = "Nov.";
            break;
        case 12:
            $result = "Dec.";
            break;
        default:
            $result = "Jan.";
    
            
    };
    return $result;
}

function match($sender, $subject, $content, $words, $numWords)
{
    $isMatch = true;
    for($i = 0; $i < $numWords; $i++)
    {
        if (!preg_match("/".$words[$i]."/i", $sender) && !preg_match("/".$words[$i]."/i", $subject) && !preg_match("/".$words[$i]."/i", $content)) 
        {
            $isMatch = false;
            break;
        }
    }
    return $isMatch;
}
    
    

?>


