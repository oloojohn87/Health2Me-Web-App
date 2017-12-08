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

//$setQuery = "SELECT * FROM ".$WhatDB." WHERE receiver_id=".$IdMED." AND DATEDIFF(NOW(), fecha) <=  60 AND status='new' ORDER BY fecha DESC";
$setQuery = '';
if ($scenario == 'patient') 
{
    $setQuery = $con->prepare("SELECT MES.*,U.Name AS PatName, U.Surname AS PatSurname FROM
                                (
                                    SELECT * FROM message_infrastructureuser WHERE receiver_id=? AND tofrom='to' AND status='new' ORDER BY fecha DESC
                                ) AS MES
                                    LEFT JOIN
                                usuarios U ON MES.patient_id = U.Identif");
	$setQuery->bindValue(1, $IdMED, PDO::PARAM_INT);
}
else
{
    $setQuery = $con->prepare("SELECT MES.*, D.Name AS DocName, D.Surname AS DocSurname, D.IdMEDFIXEDNAME, U.Name AS PatName, U.Surname AS PatSurname  FROM
                                (
                                    SELECT * FROM message_infrasture WHERE receiver_id= ? AND status='new' ORDER BY fecha DESC
                                ) AS MES
                                    LEFT JOIN
                                doctors D ON MES.sender_id = D.id
                                    LEFT JOIN
                                usuarios U ON MES.patient_id = U.Identif");
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
        $hasAttachments=false;
        
        $senderID = $senderid;
        
        if($reportids != null && $reportids != 0)
        {
            $hasAttachments=true;
        }
        
        if ($SwitchDr == 1)
        {
			//$senderid = $row['IdMEDFIXEDNAME'];
            $initials = strtoupper(substr($row['DocName'], 0, 1)).' '.strtoupper(substr($row['DocSurname'], 0, 1));
            $NameSender = strtoupper(substr($row['DocName'], 0, 1)).substr($row['DocName'], 1).' '.strtoupper(substr($row['DocSurname'], 0, 1)).substr($row['DocSurname'], 1) ;
        };
        

        $initialsP = strtoupper(substr($row['PatName'], 0, 1)).' '.strtoupper(substr($row['PatSurname'], 0, 1));
        $NamePatient = strtoupper(substr($row['PatName'], 0, 1)).substr($row['PatName'], 1).' '.strtoupper(substr($row['PatSurname'], 0, 1)).substr($row['PatSurname'], 1) ;
        if ($SwitchDr == 0)
        {
            $senderID = $patientid;
            $initials = $initialsP;
            $NameSender = $NamePatient;
        };
        
        $rep_date = substr($row['fecha'],0,10);
        $year=strtok($rep_date,"-");
        $month=strtok("-");
        $day = strtok("-");
        $rep_date = $month."-".$day."-".$year;
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
        if ($SwitchDr == 1)
        {
			$PicLocation1 = 'DoctorImage/'.$senderID.'.jpg';
			$PicLocation2 = 'DoctorImage/'.$senderID.'.png';
			$PicLocation3 = 'DoctorImage/'.$senderID.'.gif';
			$PicLocation = '';
		}else{
			$PicLocation1 = 'PatientImage/'.$senderID.'.jpg';
			$PicLocation2 = 'PatientImage/'.$senderID.'.png';
			$PicLocation3 = 'PatientImage/'.$senderID.'.gif';
			$PicLocation = '';
		}
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
                     "NUM" => $num_messages
                    );
        array_push($cadena, $arr);
    //}
    $num_msg++;

}

$encode = json_encode($cadena);
echo $encode; 




    
    

?>


