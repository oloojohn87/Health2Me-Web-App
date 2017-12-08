<?php
 //   echo '<table><tr><td>TEST</td></tr></table>';
//require "identicon.php";
 session_start();
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

$scenario = 0;
if (isset($_POST['scenario']))
{
    $scenario =  $_POST['scenario'];
}
$id = 1;
if (isset($_POST['id']))
{
    $id =  $_POST['id'];
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

$medid = $_SESSION['MEDID'];

//$setQuery = "SELECT * FROM ".$WhatDB." WHERE receiver_id=".$IdMED." AND DATEDIFF(NOW(), fecha) <=  60 AND status='new' ORDER BY fecha DESC";
$setQuery = $con->prepare("SELECT * FROM ".$WhatDB." WHERE message_id=? AND receiver_id=? ORDER BY fecha DESC");
$setQuery->bindValue(1, $id, PDO::PARAM_INT);
$setQuery->bindValue(2, $medid, PDO::PARAM_INT);
$result = $setQuery->execute();


$num_msg = 0;
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
    
            
        $cadena = array("ID" => $id, 
                     "ID_pat" => $patientid, 
                     "NAME_pat" => $NamePatient, 
                     "INIT_pat" => $initialsP,
                     "SENDER_ID" => $senderID, 
                     "SENDER" => $NameSender,
                     "INIT_sender" => $initials,
                     "SUBJECT" => $subject, 
                     "MESSAGE" => $content, 
                     "DATE" => $rep_date,
                     "PIC" => $PicLocation,
                     "ATT" => $hasAttachments,
                    "ATTACHMENTS" => $reportids,
                     "NUM" => $num_messages
                    );
    //}
    $num_msg++;

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
    
    

?>


