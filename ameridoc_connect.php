<?php
require_once("Services/Twilio.php");
require("environment_detail.php");
require("PasswordHash.php");
require('ameridoc_util.php');
require("NotificationClass.php");
//require_once('realtime-notifications/pusherlib/lib/Pusher.php');
require_once('push/push.php');
$dbhost = $env_var_db["dbhost"];
$dbname = $env_var_db["dbname"];
$dbuser = $env_var_db["dbuser"];
$dbpass = $env_var_db["dbpass"];
// Connect to server and select databse.
$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }		

$notifications = new Notifications();

//$app_key = 'd869a07d8f17a76448ed';
//$app_secret = '92f67fb5b104260bbc02';
//$app_id = '51379';
//$pusher = new Pusher($app_key, $app_secret, $app_id);
$push = new Push();

$API_VERSION = '2010-04-01';
$AccountSid = "AC109c7554cf28cdfe596e4811c03495bd";
$AuthToken = "26b187fb3258d199a6d6edeb7256ecc1";
$client = new Services_Twilio($AccountSid, $AuthToken);

if(isset($_GET['connect']) && isset($_REQUEST['CallStatus']) && $_REQUEST['CallStatus'] == 'in-progress' )
{
    
    $info = explode("_", $_GET['connect']);
    
    //mysql_query("insert into ameridoc_calls (patientId,doctorId,startDate,startTime) values(".$info[1].",".$info[0].",curdate(),curtime())");
    
    //getting the name and surname of the doctor and patient
    $query1 = $con->prepare("select name,surname from doctors where id =?");
	$query1->bindValue(1, $info[0], PDO::PARAM_INT);
	$query1->execute();
	
    $query2 = $con->prepare("select name,surname from usuarios where identif=?");
    $query2->bindValue(1, $info[1], PDO::PARAM_INT);
	$query2->execute();
	
    $result1 = $query1->fetch(PDO::FETCH_ASSOC);
    $result2 = $query2->fetch(PDO::FETCH_ASSOC);
    
    $doctorName = "'".$result1['name']."'";
    $doctorSurname = "'".$result1['surname']."'";
    $patientName = "'".$result2['name']."'";
    $patientSurname = "'".$result2['surname']."'";
    
    //inserting data into the consults table for the above initiated consultation
    $callFrom = $_REQUEST['From'];
    $initial = substr($callFrom,3);
    $contract = "";
    if($initial == '+57')
      {
      
       $contract = 'HTI-COL';
      }
    else
        {
          $contract = 'HTI-AAR';
        }
    $ins_query = $con->prepare("insert into consults(contract,DateTime,status,type,Patient,Doctor,doctorName,doctorSurname,patientName,patientSurname) 
	values(?,now()".",'In Progress','phone',?,?,?,?,?,?)");
	$ins_query->bindValue(1, $contract, PDO::PARAM_STR);
	$ins_query->bindValue(2, $info[1], PDO::PARAM_INT);
	$ins_query->bindValue(3, $info[0], PDO::PARAM_INT);
	$ins_query->bindValue(4, $doctorName, PDO::PARAM_STR);
	$ins_query->bindValue(5, $doctorSurname, PDO::PARAM_STR);
	$ins_query->bindValue(6, $patientName, PDO::PARAM_STR);
	$ins_query->bindValue(7, $patientSurname, PDO::PARAM_STR);
	$ins_query->execute();
    
    $recent_id = $con->lastInsertId(); 
    //$callSID = $_REQUEST['SID'];
        
    echo '<?xml version="1.0" encoding="UTF-8"?>';
    echo '<Response>';
    echo '<Dial><Conference record="record-from-start" maxParticipants="2" endConferenceOnExit="true" eventCallbackUrl="phone_appointment_callback.php?info='.$info[0].'_'.$info[1].'_'.$recent_id.'">'.$info[0].'_'.$info[1].'</Conference></Dial>';
    echo '</Response>';
    $upd_query = $con->prepare("UPDATE doctors SET consultation_pat=?, cons_req_time=".time()." WHERE id=?");
	$upd_query->bindValue(1, $info[1], PDO::PARAM_INT);
	$upd_query->bindValue(2, $info[0], PDO::PARAM_INT);
	$upd_query->execute();
	
    $phone = $info[2];
    str_replace("-", "", $phone);
    
    $res = $con->prepare("SELECT id FROM doctorslinkusers WHERE IdMED = ? AND IdUs = ?");
	$res->bindValue(1, $info[0], PDO::PARAM_INT);
	$res->bindValue(2, $info[1], PDO::PARAM_INT);
	$res->execute();
	
    $rowCount = $res->rowCount();
    if($rowCount == 0)
    {
        $res = $con->prepare("insert into doctorslinkusers (IdMED,IdUs,Fecha,IdPIN,estado) values(?,?,NOW(),null,2)");
		$res->bindValue(1, $info[0], PDO::PARAM_INT);
		$res->bindValue(2, $info[1], PDO::PARAM_INT);
		$res->execute();
		
    }
    
    $client->account->calls->create('+19034018888', $phone, 'http://'.$_SERVER['HTTP_HOST'].'/ameridoc_call_doctor.php?info='.$info[0].'_'.$info[1].'_'.$recent_id);
    
}
else if(isset($_GET['other']) && isset($_REQUEST['CallStatus']) && $_REQUEST['CallStatus'] == 'in-progress' )
{
    $info = explode('_', $_GET['other']);
    if(isset($_GET['Digits']) && $_GET['Digits'] == '1')
    {
        // Go to the menu
        
        echo '<?xml version="1.0" encoding="UTF-8"?>';
        echo '<Response>';
        echo '<Gather timeout="5" action="http://'.$_SERVER['HTTP_HOST'].'/ameridoc_connect.php?option='.$info[0].'" method="GET" numDigits="1"><Say>If you would like to change your pin number, press one. If you would like to talk to a doctor, press two.</Say></Gather>';
        echo '<Redirect/>';
        
        echo '</Response>';
    }
    else if(isset($_GET['Digits']) && $_GET['Digits'] == '0')
    {
        // Find another doctor
        $count = 0;
        $doc_id = -1;
        $doc_ph = '';
        $doc_name = '';
        $arr = array();
        for($i = 1; $i < count($info); $i++)
        {
            array_push($arr, $info[$i]);
        }
        while($doc_id == -1 && $count < 5)
        {
            $info_doc = findNextAvailableDoctor($arr);
            if($info_doc["id"] != -1)
            {
                $doc_ph = $info_doc["phone"];
                $doc_name = $info_doc["name"];
                $doc_id = $info_doc["id"];
                
                
            }
            $count++;
        }
        if(strlen($doc_name) > 0)
        {
            $ids = '';
            for($k = 1; $k < count($info); $k++)
            {
                $ids .= $info[$k];
                if($k < (count($info) - 1))
                {
                    $ids .= '_';
                }
            }
            echo '<?xml version="1.0" encoding="UTF-8"?>';
            echo '<Response>';
            echo '<Gather timeout="5" action="http://'.$_SERVER['HTTP_HOST'].'/ameridoc_connect.php?other='.$info[0].'_'.$doc_id.'_'.$ids.'" method="GET" numDigits="1"><Say>We are about to connect you to doctor '.$doc_name.', please press zero if you would like another doctor, or press one to do something else.</Say></Gather>';
            echo '<Redirect>http://'.$_SERVER['HTTP_HOST'].'/ameridoc_connect.php?connect='.$doc_id.'_'.$info[0].'_'.$doc_ph.'</Redirect>';
            
            echo '</Response>';
        }
        else
        {
            $push->send('Telemedicine', 'ameridoc', 'No more doctors available at this time');
            echo '<?xml version="1.0" encoding="UTF-8"?>';
            echo '<Response>';
            echo '<Say>Sorry, no more doctors are available at this time. Please try again later.</Say>';
            
            echo '</Response>';
        }
    }
}
else if(isset($_GET['option']) && isset($_REQUEST['CallStatus']) && $_REQUEST['CallStatus'] == 'in-progress' )
{
    $info = explode("_", $_GET['option']);
    $result = $con->prepare("SELECT Identif,Name,Surname,pin_hash FROM usuarios WHERE Identif=?");
	$result->bindValue(1, $info[0], PDO::PARAM_INT);
	$result->execute();
	
    $num = $result->rowCount();
    $row = $result->fetch(PDO::FETCH_ASSOC);
    if(isset($_GET['Digits']) && $_GET['Digits'] == '1')
    {
        
        $push->send('Telemedicine', 'ameridoc', 'Changing pin number for user '.$row['Name'].' '.$row['Surname']);
        echo '<?xml version="1.0" encoding="UTF-8"?>';
        echo '<Response>';
        echo '<Gather timeout="5" action="http://'.$_SERVER['HTTP_HOST'].'/ameridoc_connect.php?pin_change='.$_GET['option'].'_1" method="GET" numDigits="4"><Say>Please enter your current pin number.</Say></Gather>';
        echo '<Redirect/>';
        
        echo '</Response>';
    }
}
//This is for pin change
else if(isset($_GET['pin_change']) && isset($_REQUEST['CallStatus']) && $_REQUEST['CallStatus'] == 'in-progress' )
{
    $info = explode("_", $_GET['pin_change']);
    $result = $con->prepare("SELECT Identif,Name,Surname,pin_hash FROM usuarios WHERE Identif=?");
	$result->bindValue(1, $info[0], PDO::PARAM_INT);
	$result->execute();
	
    $num = $result->rowCount();
    $row = $result->fetch(PDO::FETCH_ASSOC);
    if(isset($_GET['Digits']) && $info[1] == '1')
    {
        $cur_pin = $_GET['Digits'];
        
        $verified = 0;
        if($num > 0)
        {
            if(validate_password($_GET['Digits'], $row['pin_hash']))
            {
                $verified = 1;
            }
        }
        if($verified == 1)
        {
            echo '<?xml version="1.0" encoding="UTF-8"?>';
            echo '<Response>';
            echo '<Gather timeout="5" action="http://'.$_SERVER['HTTP_HOST'].'/ameridoc_connect.php?pin_change='.$info[0].'_2" method="GET" numDigits="4"><Say>Please enter a new pin number.</Say></Gather>';
            echo '<Redirect/>';
            
            echo '</Response>';
        }
        else
        {
            echo '<?xml version="1.0" encoding="UTF-8"?>';
            echo '<Response>';
            echo '<Gather timeout="5" action="http://'.$_SERVER['HTTP_HOST'].'/ameridoc_connect.php?pin_change='.$info[0].'_1" method="GET" numDigits="4"><Say>Sorry, the pin number is not correct. Please enter your current pin number.</Say></Gather>';
            echo '<Redirect/>';
            echo '</Response>';
        }

    }
    else if(isset($_GET['Digits']) && $info[1] == '2')
    {
        echo '<?xml version="1.0" encoding="UTF-8"?>';
        echo '<Response>';
        echo '<Gather timeout="5" action="http://'.$_SERVER['HTTP_HOST'].'/ameridoc_connect.php?pin_change='.$info[0].'_3_'.$_GET['Digits'].'" method="GET" numDigits="4"><Say>Please retype your new pin number.</Say></Gather>';
        echo '<Redirect/>';
        
        echo '</Response>';
    }
    else if(isset($_GET['Digits']) && $info[1] == '3')
    {
        if($_GET['Digits'] == $info[2])
        {
            
            $push->send('Telemedicine', 'ameridoc', 'Pin number changed successfuly for patient '.$row['Name'].' '.$row['Surname']);
            $upd_usu = $con->prepare('UPDATE usuarios SET pin_hash=? WHERE Identif=?');
			$upd_usu->bindValue(1, create_hash($info[2]), PDO::PARAM_STR);
			$upd_usu->bindValue(2, $info[0], PDO::PARAM_INT);
			$upd_usu->execute();
			
            echo '<?xml version="1.0" encoding="UTF-8"?>';
            echo '<Response>';
            echo '<Say>Pin number changed successfully.</Say>';
            echo '<Redirect method="GET">http://'.$_SERVER['HTTP_HOST'].'/ameridoc_connect.php?id='.$info[0].'</Redirect>';
            
            echo '</Response>';
        }
        else
        {
            echo '<?xml version="1.0" encoding="UTF-8"?>';
            echo '<Response>';
            echo '<Gather timeout="5" action="http://'.$_SERVER['HTTP_HOST'].'/ameridoc_connect.php?pin_change='.$info[0].'_2" method="GET" numDigits="4"><Say>New pin numbers did not match, please enter a new pin number</Say></Gather>';
            echo '<Redirect/>';
            
            echo '</Response>';
        }
    }
}
//This is if summary is complete
else if(isset($_GET['summary']) && isset($_REQUEST['CallStatus']) && $_REQUEST['CallStatus'] == 'in-progress' )
{
    $info = explode("_", $_GET['summary']);
    $result = $con->prepare("SELECT Identif,Name,Surname,pin_hash FROM usuarios WHERE Identif=?");
	$result->bindValue(1, $info[0], PDO::PARAM_INT);
	$result->execute();
	
    $num = $result->rowCount();
    $row = $result->fetch(PDO::FETCH_ASSOC);
    if(isset($_GET['Digits']))
    {
        $answer = intval($_GET['Digits']);
        
        if($answer == 1)
        {
		
		$push->send('Telemedicine', 'ameridoc', 'Redirecting user '.$row['Name'].' '.$row['Surname'].' to call center.');
		
		$conf_num = substr(str_shuffle(str_repeat("0123456789abcdefghijklmnopqrstuvwxyz", 5)), 0, 5);
		
		
			echo '<?xml version="1.0" encoding="UTF-8"?>';
            echo '<Response>';
            echo '<Say>Redirecting to the call center. please wait.</Say>';
            echo "<Redirect>http://".$_SERVER['HTTP_HOST']."/conference.php?type=patient_".$conf_num."</Redirect>";
            echo '</Response>';
		
  
  $call2 = $client->account->calls->create(
  '+19034018888', // From a valid Twilio number
  '+19723528648', // Number to call for call center
  "http://".$_SERVER['HTTP_HOST']."/conference.php?type=doctor_".$conf_num."");
		
            // redirect to call center
			if(!$call2){
            
            echo '<?xml version="1.0" encoding="UTF-8"?>';
            echo '<Response>';
            echo '<Say>Redirecting. please wait.</Say>';
             echo '<Gather timeout="10" action="http://'.$_SERVER['HTTP_HOST'].'/ameridoc.php?login=1" method="GET" numDigits="12"><Say>The call center is not available at the moment.  Please re-enter your phone number associated with your account to start again.</Say></Gather>';
            echo '</Response>';
        }
		}
        else
        {
            echo '<?xml version="1.0" encoding="UTF-8"?>';
            echo '<Response>';
            echo '<Gather timeout="5" action="http://'.$_SERVER['HTTP_HOST'].'/ameridoc_connect.php?option='.$_GET['summary'].'" method="GET" numDigits="1"><Say>If you would like to change your pin number, press one.</Say></Gather>';
            echo '<Redirect/>';
            
            echo '</Response>';
        }
        
    }
}
//This creates an appointment for a doctor
else if(isset($_GET['appointment']) && isset($_REQUEST['CallStatus']) && $_REQUEST['CallStatus'] == 'in-progress')
{
    $info = explode("_", $_GET['appointment']);
    $doc_id = $info[1];
    $pat_id = $info[0];
    if(isset($_GET['Digits']) && $_GET['Digits'] == '1')
    {
        // add the appointment
        $res = $con->prepare("SELECT Name,Surname FROM doctors WHERE id=?");
		$res->bindValue(1, $doc_id, PDO::PARAM_INT);
		$res->execute();
		
        $row = $res->fetch(PDO::FETCH_ASSOC);
        $doc_name = $row['Name'].' '.$row['Surname'];
        $app_info = explode(".", $info[(count($info) - 1)]);
        $query = $con->prepare("INSERT INTO appointments (med_id, pat_id, date, start_time, end_time, timezone, video) VALUES (?,?,?,?,?,?,0)");
		$query->bindValue(1, $doc_id, PDO::PARAM_INT);
		$query->bindValue(2, $pat_id, PDO::PARAM_INT);
		$query->bindValue(3, $app_info[2], PDO::PARAM_STR);
		$query->bindValue(4, $app_info[0], PDO::PARAM_STR);
		$query->bindValue(5, $app_info[1], PDO::PARAM_STR);
		$query->bindValue(6, $app_info[3], PDO::PARAM_STR);
		$result = $query->execute();
        
        $notifications->add('NEWAPP', $pat_id, false, $doc_id, true, null);
        
        $app_id = $con->lastInsertId(); 
        sendAppointmentEmail($app_id, 'patient');
        sendAppointmentEmail($app_id, 'doctor');
        echo '<?xml version="1.0" encoding="UTF-8"?>';
        echo '<Response>';
        echo '<Say>Your appointment with Doctor '.$doc_name.' has been created. We will call you at the time of your appointment. Thank you!</Say>';
        
        echo '</Response>';
    }
    else
    {
        $ignore = array();
        for($k = 2; $k < count($info); $k++)
        {
            $time_info = explode(".", $info[$k]);
            array_push($ignore, array("date" => $time_info[2], "start" => $time_info[0], "end" => $time_info[1]));
            
        }
        $time = findEarliestTime($doc_id, $ignore);
        $res = $con->prepare("SELECT Name,Surname FROM doctors WHERE id=?");
		$res->bindValue(1, $doc_id, PDO::PARAM_INT);
		$res->execute();
		
        $row = $res->fetch(PDO::FETCH_ASSOC);
        $doc_name = $row['Name'].' '.$row['Surname'];
        if($time != 'none')
        {
            $pre_date = new DateTime($time['date']);
            $date = $pre_date->format("F d");
            $pre_start_time = explode(":", $time['start']);
            $pre_end_time = explode(":", $time['end']);
            $start_time = intval($pre_start_time[0]);
            $end_time = intval($pre_end_time[0]);
            $start_zone = "A.M.";
            $end_zone = "A.M.";
            if($start_time > 12)
            {
                $start_time -= 12;
                $start_zone = "P.M.";
            }
            if($end_time > 12)
            {
                $end_time -= 12;
                $end_zone = "P.M.";
            }
            
            $res = $con->prepare("SELECT Name,Surname FROM doctors WHERE id=?");
			$res->bindValue(1, $doc_id, PDO::PARAM_INT);
			$res->execute();
			
            $row = $res->fetch(PDO::FETCH_ASSOC);
            $doc_name = $row['Name'].' '.$row['Surname'];
            echo '<?xml version="1.0" encoding="UTF-8"?>';
            echo '<Response>';
            echo '<Gather timeout="5" action="http://'.$_SERVER['HTTP_HOST'].'/ameridoc_connect.php?appointment='.$_GET['appointment'].'_'.$time['start'].'.'.$time['end'].'.'.$time['date'].'.'.$time['timezone'].'" method="GET" numDigits="1"><Say>The next available time for Doctor '.$doc_name.' is between '.$start_time.' '.$start_zone.' and '.$end_time.' '.$end_zone.' on '.$date.'. Would this be OK? Press one for yes or two for no.'.'</Say></Gather>';
            echo '<Redirect/>';
            
            echo '</Response>';
        }
        else
        {
            echo '<?xml version="1.0" encoding="UTF-8"?>';
            echo '<Response>';
            echo '<Say>There are no more time slots available for Doctor '.$doc_name.'. Please try again later. Goodbye.</Say>';
            echo '</Response>';
        }
    }
    
}


/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////THIS HANDLES INBOUND CALLS////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////

else if(isset($_REQUEST['CallStatus']) && $_REQUEST['CallStatus'] == 'in-progress' )
{
    $info = array();
    $pat_id = '';
    if(isset($_GET['id']))
    {
        $info = explode('_', $_GET['id']);
        $pat_id = $info[0];
    }
    else if(isset($_GET['id2']))
    {
        $info = explode('_', $_GET['id2']);
        $pat_id = $info[0];
    }
    else if(isset($_GET['override_summary']))
    {
        $pat_id = $_GET['override_summary'];
    }
    $doc_ph = '';
    $doc_name = '';
    $doc_id = -1;
    if(isset($_GET['id']) && isset($_GET['Digits']) && $_GET['Digits'] == '1')
    {
        echo '<?xml version="1.0" encoding="UTF-8"?>';
        echo '<Response>';
        echo '<Gather timeout="5" action="http://'.$_SERVER['HTTP_HOST'].'/ameridoc_connect.php?option='.$pat_id.'" method="GET" numDigits="1"><Say>If you would like to change your pin number, press one. If you would like to talk to a doctor, press two.</Say></Gather>';
        echo '<Redirect/>';
        
        echo '</Response>';
    }
    else
    {
        
        $result = $con->prepare("SELECT Identif,Name,Surname,pin_hash FROM usuarios WHERE Identif=?");
		$result->bindValue(1, $pat_id, PDO::PARAM_INT);
		$result->execute();
		
        $num = $result->rowCount();
        $row = $result->fetch(PDO::FETCH_ASSOC);
        // check if the patient has a summary filled out
        //$res = mysql_query("SELECT * FROM basicemrdata WHERE IdPatient=".$pat_id);
        $num_rows = 0;//mysql_num_rows($res);
        $override = 1;
        if(isset($_GET['override_summary']))
        {
            $override = 1;
        }
        if($num_rows == 0 && $override == 0 && count($info) <= 1)
        {
            // patient has no summary, redirect him/her to call center to have summary filled out
            $push->send('Telemedicine', 'ameridoc', 'Detected that user '.$row['Name'].' '.$row['Surname'].' has no summary.');
            echo '<?xml version="1.0" encoding="UTF-8"?>';
            echo '<Response>';
            echo '<Gather timeout="5" action="http://'.$_SERVER['HTTP_HOST'].'/ameridoc_connect.php?summary='.$pat_id.'" method="GET" numDigits="1"><Say>Our records indicate that you do not have a medical summary. In order to connect to a doctor, you must have a basic medical summary filled out. Would you like to do this now? press one for yes or two for no.</Say></Gather>';
            echo '<Redirect method="GET">http://'.$_SERVER['HTTP_HOST'].'/ameridoc_connect.php?id='.$pat_id.'</Redirect>';
//			echo '<Redirect>http://'.$_SERVER['HTTP_HOST'].'/ameridoc_connect.php?override_summary='.$pat_id.'</Redirect>';
            
            echo '</Response>';
            
        }
        else
        {
            $res = $con->prepare("SELECT * FROM usuarios WHERE Identif=?");
			$res->bindValue(1, $pat_id, PDO::PARAM_INT);
			$res->execute();
			
            $row = $res->fetch(PDO::FETCH_ASSOC);
            $pat_name = $row['Name'].' '.$row['Surname'];
            $push->send('Telemedicine', 'ameridoc', 'Finding a doctor for patient '.$pat_name);
            $count = 0;
            
            // check if there are recent doctors
            $hasRecent = hasRecentDoctors($pat_id);
            $docs = array();
            if($hasRecent)
            {
                for($i = 1; $i < count($info); $i++)
                {
                    array_push($docs, $info[$i]);
                }
                $recent = getMostRecentDoctor($pat_id, $docs, false);
            }
            if($hasRecent && $recent != 'none')
            {
                
                if($recent['available'] == true)
                {
                    $doctor_name = str_replace("_", " ", $recent['name']);
                    $push->send('Telemedicine', 'ameridoc', 'Found recent doctor '.$doctor_name.' for patient '.$pat_name);
                    echo '<?xml version="1.0" encoding="UTF-8"?>';
                    echo '<Response>';
                    echo '<Gather timeout="3" action="http://'.$_SERVER['HTTP_HOST'].'/ameridoc_connect.php?id='.$pat_id.'_'.$recent['id'].'" method="GET" numDigits="1"><Say>We are about to connect you to doctor '.$doctor_name.', please press zero if you would like another doctor, or press one to do something else.</Say></Gather>';
                    echo '<Redirect>http://'.$_SERVER['HTTP_HOST'].'/ameridoc_connect.php?connect='.$recent['id'].'_'.$pat_id.'_'.$recent['phone'].'</Redirect>';
                    
                    echo '</Response>';
                }
                else if($recent['available'] == false)
                {
                    $doctor_name = str_replace("_", " ", $recent['name']);
                    echo '<?xml version="1.0" encoding="UTF-8"?>';
                    echo '<Response>';
                    echo '<Gather timeout="3" action="http://'.$_SERVER['HTTP_HOST'].'/ameridoc_connect.php?id2='.$pat_id.'_'.$recent['id'].'" method="GET" numDigits="1"><Say>Doctor '.$doctor_name.' is not available right now. Please press one to set up an appointment with doctor '.$doctor_name.', or press two for another doctor.</Say></Gather>';
                    echo '<Redirect/>';
                    
                    echo '</Response>';
                }
            }
            else
            {
                if(isset($_GET['Digits']) && $_GET['Digits'] == '1' && isset($_GET['id2']))
                {
                    // set up an appointment
                    echo '<?xml version="1.0" encoding="UTF-8"?>';
                    echo '<Response>';
                    echo '<Redirect>http://'.$_SERVER['HTTP_HOST'].'/ameridoc_connect.php?appointment='.$info[0].'_'.$info[(count($info) - 1)].'</Redirect>';
                    
                    echo '</Response>';
                }
                else 
                {
                    // find doctor
                    $docs = array();
                    while($doc_id == -1 && $count < 5)
                    {
                        for($i = 1; $i < count($info); $i++)
                        {
                            array_push($docs, $info[$i]);
                        }
                        $info = findNextAvailableDoctor($docs);
                        if($info["id"] != -1)
                        {
                            $doc_ph = $info["phone"];
                            $doc_name = $info["name"];
                            $doc_id = $info["id"];
                            
                            
                        }
                        $count++;
                    }
                    
                    if(strlen($doc_ph) > 0)
                    {
                        $passed_ids = explode("_", $_GET['id']);
                        echo '<?xml version="1.0" encoding="UTF-8"?>';
                        echo '<Response>';
                        echo '<Gather timeout="3" action="http://'.$_SERVER['HTTP_HOST'].'/ameridoc_connect.php?other='.$pat_id.'_'.$doc_id.'" method="GET" numDigits="1"><Say>We are about to connect you to doctor '.$doc_name.', please press zero if you would like another doctor, or press one to do something else.</Say></Gather>';
                        
                        echo '<Redirect>http://'.$_SERVER['HTTP_HOST'].'/ameridoc_connect.php?connect='.$doc_id.'_'.$passed_ids[0].'_'.$doc_ph.'</Redirect>';
                        
                        echo '</Response>';
                    }
                    else
                    {
                        $push->send('Telemedicine', 'ameridoc', 'No more doctors available');
                        echo '<?xml version="1.0" encoding="UTF-8"?>';
                        echo '<Response>';
                        echo '<Say>Sorry, no doctors are available at this time. Please try again later.</Say>';
                        
                        echo '</Response>';
                    }
                }
                
            }
        }
    }
}




?>