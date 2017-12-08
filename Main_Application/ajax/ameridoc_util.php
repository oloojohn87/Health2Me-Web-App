<?php
require_once("environment_detail.php");
require_once("Services/Twilio.php");
require("NotificationClass.php");
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

function addAppointment($info, $pat_id)
{
    $notifications = new Notifications();
    $query = $con->prepare("INSERT INTO appointments (med_id, pat_id, date, start_time, end_time, timezone, video) VALUES (?,?,?,?,?,?,0)");
	$query->bindValue(1, $info['doc'], PDO::PARAM_INT);
	$query->bindValue(2, $pat_id, PDO::PARAM_INT);
	$query->bindValue(3, $info['date'], PDO::PARAM_STR);
	$query->bindValue(4, $info['start'], PDO::PARAM_STR);
	$query->bindValue(5, $info['end'], PDO::PARAM_STR);
	$query->bindValue(6, $info['timezone'], PDO::PARAM_STR);
	$result = $query->execute();
    
    $notifications->add('NEWAPP', $pat_id, false, $info['doc'], true, null);
}

function findEarliestTime($doc_id, $ignore = array())
{
    $found = false;
    $res_date = '';
    $res_start_time = '';
    $res_end_time = '';
    date_default_timezone_set('GMT'); 
    $date = new DateTime('now');
    $result = $con->prepare("SELECT * FROM timeslots WHERE doc_id=? ORDER BY week, week_day, start_time");
	$result->bindValue(1, $doc_id, PDO::PARAM_INT);
	$result->execute();
	
    while(($row = $result->fetch(PDO::FETCH_ASSOC)) && !$found)
    {
        $start = new DateTime($row['week'].' '.$row['start_time']);
        $end = new DateTime($row['week'].' '.$row['end_time']);
        $date_interval = new DateInterval('P'.$row['week_day'].'D');
        $time_interval = new DateInterval('PT'.intval(substr($row['timezone'], strlen($row['timezone']) - 8, 2)).'H'.intval(substr($row['timezone'], strlen($row['timezone']) - 5, 2)).'M');
        if(substr($row['timezone'], 0 , 1) != '-')
        {
            $time_interval->invert = 1;
        }
        $start->add($date_interval);
        $end->add($date_interval);
        $start->add($time_interval);
        $end->add($time_interval);
        
        $start_orig = new DateTime($row['week'].' '.$row['start_time']);
        $end_orig = new DateTime($row['week'].' '.$row['end_time']);
        $start_orig->add($date_interval);
        $end_orig->add($date_interval);
        $timeslot_date = $start_orig->format("Y-m-d");
        $start_time = $start_orig->format("H:i:s");
        $end_time = $end_orig->format("H:i:s");
        
        $date_copy = clone($date);
        $time_interval_copy = $time_interval;
        if($time_interval_copy->invert == 1)
        {
            $time_interval_copy->invert = 0;
        }
        else
        {
            $time_interval_copy->invert = 1;
        }
        $date_copy->add($time_interval_copy);
        $now = $date_copy->format("H:i:s");
        
        if($end > $date)
        {
            if($start < $date)
            {
                // take the current time
                if($now > "08:00:00" && $now < "10:00:00" && !$found)
                {
                    $ign = false;
                    for($i = 0; $i < count($ignore); $i++)
                    {
                        if($ignore[$i]['start'] == "08:00:00" && $ignore[$i]['date'] == $timeslot_date)
                        {
                            $ign = true;
                            break;
                        }
                    }
                    if(!$ign)
                    {
                        $res_date = $timeslot_date;
                        $res_start_time = "08:00:00";
                        $res_end_time = "10:00:00";
                        $found = true;
                    }
                }
                if($now > "10:00:00" && $now < "12:00:00" && !$found)
                {
                    $ign = false;
                    for($i = 0; $i < count($ignore); $i++)
                    {
                        if($ignore[$i]['start'] == "10:00:00" && $ignore[$i]['date'] == $timeslot_date)
                        {
                            $ign = true;
                            break;
                        }
                    }
                    if(!$ign)
                    {
                        $res_date = $timeslot_date;
                        $res_start_time = "10:00:00";
                        $res_end_time = "12:00:00";
                        $found = true;
                    }
                }
                if($now > "12:00:00" && $now < "14:00:00" && !$found)
                {
                    $ign = false;
                    for($i = 0; $i < count($ignore); $i++)
                    {
                        if($ignore[$i]['start'] == "12:00:00" && $ignore[$i]['date'] == $timeslot_date)
                        {
                            $ign = true;
                            break;
                        }
                    }
                    if(!$ign)
                    {
                        $res_date = $timeslot_date;
                        $res_start_time = "12:00:00";
                        $res_end_time = "14:00:00";
                        $found = true;
                    }
                }
                if($now > "14:00:00" && $now < "16:00:00" && !$found)
                {
                    $ign = false;
                    for($i = 0; $i < count($ignore); $i++)
                    {
                        if($ignore[$i]['start'] == "14:00:00" && $ignore[$i]['date'] == $timeslot_date)
                        {
                            $ign = true;
                            break;
                        }
                    }
                    if(!$ign)
                    {
                        $res_date = $timeslot_date;
                        $res_start_time = "14:00:00";
                        $res_end_time = "16:00:00";
                        $found = true;
                    }
                }
               if($now > "16:00:00" && $now < "18:00:00" && !$found)
                {
                   $ign = false;
                    for($i = 0; $i < count($ignore); $i++)
                    {
                        if($ignore[$i]['start'] == "16:00:00" && $ignore[$i]['date'] == $timeslot_date)
                        {
                            $ign = true;
                            break;
                        }
                    }
                    if(!$ign)
                    {
                        $res_date = $timeslot_date;
                        $res_start_time = "16:00:00";
                        $res_end_time = "18:00:00";
                        $found = true;
                    }
                }
                if($now > "18:00:00" && $now < "20:00:00" && !$found)
                {
                    $ign = false;
                    for($i = 0; $i < count($ignore); $i++)
                    {
                        if($ignore[$i]['start'] == "18:00:00" && $ignore[$i]['date'] == $timeslot_date)
                        {
                            $ign = true;
                            break;
                        }
                    }
                    if(!$ign)
                    {
                        $res_date = $timeslot_date;
                        $res_start_time = "18:00:00";
                        $res_end_time = "20:00:00";
                        $found = true;
                    }
                }
                if($now > "20:00:00" && $now < "22:00:00" && !$found)
                {
                    $ign = false;
                    for($i = 0; $i < count($ignore); $i++)
                    {
                        if($ignore[$i]['start'] == "20:00:00" && $ignore[$i]['date'] == $timeslot_date)
                        {
                            $ign = true;
                            break;
                        }
                    }
                    if(!$ign)
                    {
                        $res_date = $timeslot_date;
                        $res_start_time = "20:00:00";
                        $res_end_time = "22:00:00";
                        $found = true;
                    }
                }
                
            }
            else
            {
                if($end_time > "08:00:00" && $start_time < "10:00:00" && !$found)
                {
                    $ign = false;
                    for($i = 0; $i < count($ignore); $i++)
                    {
                        if($ignore[$i]['start'] == "08:00:00" && $ignore[$i]['date'] == $timeslot_date)
                        {
                            $ign = true;
                            break;
                        }
                    }
                    if(!$ign)
                    {
                        $res_date = $timeslot_date;
                        $res_start_time = "08:00:00";
                        $res_end_time = "10:00:00";
                        $found = true;
                    }
                }
                if($end_time > "10:00:00" && $start_time < "12:00:00" && !$found)
                {
                    $ign = false;
                    for($i = 0; $i < count($ignore); $i++)
                    {
                        if($ignore[$i]['start'] == "10:00:00" && $ignore[$i]['date'] == $timeslot_date)
                        {
                            $ign = true;
                            break;
                        }
                    }
                    if(!$ign)
                    {
                        $res_date = $timeslot_date;
                        $res_start_time = "10:00:00";
                        $res_end_time = "12:00:00";
                        $found = true;
                    }
                }
                if($end_time > "12:00:00" && $start_time < "14:00:00" && !$found)
                {
                    $ign = false;
                    for($i = 0; $i < count($ignore); $i++)
                    {
                        if($ignore[$i]['start'] == "12:00:00" && $ignore[$i]['date'] == $timeslot_date)
                        {
                            $ign = true;
                            break;
                        }
                    }
                    if(!$ign)
                    {
                        $res_date = $timeslot_date;
                        $res_start_time = "12:00:00";
                        $res_end_time = "14:00:00";
                        $found = true;
                    }
                }
                if($end_time > "14:00:00" && $start_time < "16:00:00" && !$found)
                {
                    $ign = false;
                    for($i = 0; $i < count($ignore); $i++)
                    {
                        if($ignore[$i]['start'] == "14:00:00" && $ignore[$i]['date'] == $timeslot_date)
                        {
                            $ign = true;
                            break;
                        }
                    }
                    if(!$ign)
                    {
                        $res_date = $timeslot_date;
                        $res_start_time = "14:00:00";
                        $res_end_time = "16:00:00";
                        $found = true;
                    }
                }
                if($end_time > "16:00:00" && $start_time < "18:00:00" && !$found)
                {
                    $ign = false;
                    for($i = 0; $i < count($ignore); $i++)
                    {
                        if($ignore[$i]['start'] == "16:00:00" && $ignore[$i]['date'] == $timeslot_date)
                        {
                            $ign = true;
                            break;
                        }
                    }
                    if(!$ign)
                    {
                        $res_date = $timeslot_date;
                        $res_start_time = "16:00:00";
                        $res_end_time = "18:00:00";
                        $found = true;
                    }
                }
                if($end_time > "18:00:00" && $start_time < "20:00:00" && !$found)
                {
                    $ign = false;
                    for($i = 0; $i < count($ignore); $i++)
                    {
                        if($ignore[$i]['start'] == "18:00:00" && $ignore[$i]['date'] == $timeslot_date)
                        {
                            $ign = true;
                            break;
                        }
                    }
                    if(!$ign)
                    {
                        $res_date = $timeslot_date;
                        $res_start_time = "18:00:00";
                        $res_end_time = "20:00:00";
                        $found = true;
                    }
                }
                if($end_time > "20:00:00" && $start_time < "22:00:00" && !$found)
                {
                    $ign = false;
                    for($i = 0; $i < count($ignore); $i++)
                    {
                        if($ignore[$i]['start'] == "20:00:00" && $ignore[$i]['date'] == $timeslot_date)
                        {
                            $ign = true;
                            break;
                        }
                    }
                    if(!$ign)
                    {
                        $res_date = $timeslot_date;
                        $res_start_time = "20:00:00";
                        $res_end_time = "22:00:00";
                        $found = true;
                    }
                }
            }
        }
        if($found)
        {
            return array("start" => $res_start_time, "end" => $res_end_time, "date" => $res_date, "doc" => $doc_id, "timezone" => $row['timezone']);
        }
    }
    return "none";
}

function hasRecentDoctors($pat_id)
{
    $result = $con->prepare("SELECT most_recent_doc FROM usuarios WHERE Identif=?");
	$result->bindValue(1, $pat_id, PDO::PARAM_INT);
	$result->execute();
	
    $row = $result->fetch(PDO::FETCH_ASSOC);
    $most_recent_docs = $row['most_recent_doc'];
    if($most_recent_docs != -1 && $most_recent_docs != '-1' && strlen($most_recent_docs) > 2)
    {
        return true;
    }
    else
    {
        return false;
    }
}

function getMostRecentDoctor($pat_id, $ignore, $must_be_available)
{
    $API_VERSION = '2010-04-01';
    $AccountSid = "AC109c7554cf28cdfe596e4811c03495bd";
    $AuthToken = "26b187fb3258d199a6d6edeb7256ecc1";
    $client = new Services_Twilio($AccountSid, $AuthToken);
    
    $found = false;
    $result_id = -1;
    $result_phone = '';
    $result_name = '';
    $result_availability = false;
    $result = $con->prepare("SELECT most_recent_doc FROM usuarios WHERE Identif=?");
	$result->bindValue(1, $pat_id, PDO::PARAM_INT);
	$result->execute();
	
    $row = $result->fetch(PDO::FETCH_ASSOC);
    $most_recent_docs = $row['most_recent_doc'];
    date_default_timezone_set('GMT'); 
    $date = new DateTime('now');
    $docs_in_consultation = array();
    foreach ($client->account->conferences->getIterator(0, 50, array("Status" => "in-progress")) as $conference)
    {
        $conference_name = explode("_", $conference->friendly_name);
        $doc_id = intval($conference_name[0]);
        if(!in_array($doc_id, $docs_in_consultation))
        {
            array_push($docs_in_consultation, $doc_id);
        }
    }
    if($most_recent_docs != -1 && $most_recent_docs != '-1' && strlen($most_recent_docs) > 2)
    {
        $most_recent_docs = str_replace(array("[", "]"), "", $most_recent_docs);
        $docs = explode(',', $most_recent_docs);
        for($i = 0; $i < count($docs); $i++)
        {
			$s = "SELECT * FROM doctors WHERE id=?";
            $q = $con->prepare("SELECT * FROM doctors WHERE id=?");
			$q->bindValue(1, $docs[$i], PDO::PARAM_INT);
			$q->execute();
			
			$res = $q->fetch(PDO::FETCH_ASSOC);
			if($docs[$i] == $res['id']){
            if($must_be_available)
            {
                $s .= " AND in_consultation!=1";
            }
            $res = $con->prepare($s);
			$res->bindValue(1, $docs[$i], PDO::PARAM_INT);
			$res->execute();
            $num_rows = $res->rowCount();
            if($num_rows >= 1)
            {
                $row = $res->fetch(PDO::FETCH_ASSOC);
                if($must_be_available == true && !in_array(intval($docs[$i]), $docs_in_consultation))
                {
                    $result2 = $con->prepare("SELECT * FROM timeslots WHERE doc_id=?");
					$result2->bindValue(1, $row['id'], PDO::PARAM_INT);
					$result2->execute();
					
                    while(($row2 = $result2->fetch(PDO::FETCH_ASSOC)) && !$found)
                    {
                        $start = new DateTime($row2['week'].' '.$row2['start_time']);
                        $end = new DateTime($row2['week'].' '.$row2['end_time']);
                        $date_interval = new DateInterval('P'.$row2['week_day'].'D');
                        $time_interval = new DateInterval('PT'.intval(substr($row2['timezone'], strlen($row2['timezone']) - 8, 2)).'H'.intval(substr($row2['timezone'], strlen($row2['timezone']) - 5, 2)).'M');
                        if(substr($row2['timezone'], 0 , 1) != '-')
                        {
                            $time_interval->invert = 1;
                        }
                        $start->add($date_interval);
                        $end->add($date_interval);
                        $start->add($time_interval);
                        $end->add($time_interval);
                        if($start <= $date && $end >= $date)
                        {
                            // doctor is available
                            $valid = true;
                            for($i = 0; $i < count($ignore); $i++)
                            {
                                if($ignore[$i] == $row['id'])
                                {
                                    $valid = false;
                                }
                            }
                            if($valid)
                            {
                                $result_id = $row['id'];
                                $result_phone = $row['phone'];
                                $result_name = $row['Name'].'_'.$row['Surname'];
                                $result_availability = true;
                                $found = true;
                            }
                        }
                        
                    }
                }
                else
                {
                    $available = false;
                    $result2 = $con->prepare("SELECT * FROM timeslots WHERE doc_id=?");
					$result2->bindValue(1, $row['id'], PDO::PARAM_INT);
					$result2->execute();
					
                    while(($row2 = $result2->fetch(PDO::FETCH_ASSOC)) && !$found)
                    {
                        $start = new DateTime($row2['week'].' '.$row2['start_time']);
                        $end = new DateTime($row2['week'].' '.$row2['end_time']);
                        $date_interval = new DateInterval('P'.$row2['week_day'].'D');
                        $time_interval = new DateInterval('PT'.intval(substr($row2['timezone'], strlen($row2['timezone']) - 8, 2)).'H'.intval(substr($row2['timezone'], strlen($row2['timezone']) - 5, 2)).'M');
                        if(substr($row2['timezone'], 0 , 1) != '-')
                        {
                            $time_interval->invert = 1;
                        }
                        $start->add($date_interval);
                        $end->add($date_interval);
                        $start->add($time_interval);
                        $end->add($time_interval);
                        if($start <= $date && $end >= $date)
                        {
                            // doctor is available
                            $available = true;
                        }
                        
                    }
                    
                    
                    $valid = true;
                    for($i = 0; $i < count($ignore); $i++)
                    {
                        if($ignore[$i] == $row['id'])
                        {
                            $valid = false;
                        }
                    }
                    if($valid)
                    {
                        $result_id = $row['id'];
                        $result_phone = $row['phone'];
                        $result_name = $row['Name'].'_'.$row['Surname'];
                        $result_availability = $available;
                        $found = true;
                    }
                }
            }
            }
        }
        if($found)
        {
            return array("name" => $result_name, "id" => $result_id, "phone" => $result_phone, "available" => $result_availability);
        }
        else
        {
            return 'none';
        }
		
    }
    else
    {
        return 'none';
    }
}

function findNextAvailableDoctor($ignore = '')
{
    $API_VERSION = '2010-04-01';
    $AccountSid = "AC109c7554cf28cdfe596e4811c03495bd";
    $AuthToken = "26b187fb3258d199a6d6edeb7256ecc1";
    $client = new Services_Twilio($AccountSid, $AuthToken);
    
    $found = false;
    $result_id = -1;
    $result_phone = '';
    $result_name = '';
    date_default_timezone_set('UTC'); 
    $date = new DateTime('now');
    $docs_in_consultation = array();
    foreach ($client->account->conferences->getIterator(0, 50, array("Status" => "in-progress")) as $conference)
    {
        $conference_name = explode("_", $conference->friendly_name);
        $doc_id = intval($conference_name[0]);
        if(!in_array($doc_id, $docs_in_consultation))
        {
            array_push($docs_in_consultation, $doc_id);
        }
    }

    $result = $con->prepare("SELECT phone,id,Name,Surname FROM doctors WHERE telemed=1 AND in_consultation!=1 ORDER BY cons_req_time");
	$result->execute();
    while(($row = $result->fetch(PDO::FETCH_ASSOC)) && !$found)
    {
        if(!in_array(intval($row['id']), $docs_in_consultation))
        {
            $result2 = $con->prepare("SELECT * FROM timeslots WHERE doc_id=?");
			$result2->bindValue(1, $row['id'], PDO::PARAM_INT);
			$result2->execute();
			
            while(($row2 = $result2->fetch(PDO::FETCH_ASSOC)) && !$found)
            {
                $start = new DateTime($row2['week'].' '.$row2['start_time']);
                $end = new DateTime($row2['week'].' '.$row2['end_time']);
                $date_interval = new DateInterval('P'.$row2['week_day'].'D');
                $time_interval = new DateInterval('PT'.intval(substr($row2['timezone'], strlen($row2['timezone']) - 8, 2)).'H'.intval(substr($row2['timezone'], strlen($row2['timezone']) - 5, 2)).'M');
                if(substr($row2['timezone'], 0 , 1) != '-')
                {
                    $time_interval->invert = 1;
                }
                $start->add($date_interval);
                $end->add($date_interval);
                $start->add($time_interval);
                $end->add($time_interval);
                if($start <= $date && $end >= $date)
                {
                    // doctor is available
                    $accept = 1;
                    if($ignore != '')
                    {
                        for($i = 0; $i < count($ignore); $i++)
                        {
                            if($ignore[$i] == $row['id'])
                            {
                                $accept = 0;
                            }
                        }
                    }
                    if($accept == 1)
                    {
                        $result_id = $row['id'];
                        $result_phone = $row['phone'];
                        $result_name = $row['Name'].' '.$row['Surname'];
                        $found = true;
                    }
                }
                
            }
        }
    }
    return array("name" => $result_name, "id" => $result_id, "phone" => $result_phone);
}

function sendAppointmentEmail($id, $type)
{
    require_once 'lib/swift_required.php';
    $res = $con->prepare("SELECT * FROM appointments WHERE id=?");
	$res->bindValue(1, $id, PDO::PARAM_INT);
	$res->execute();
	
    $row = $res->fetch(PDO::FETCH_ASSOC);
    $res = $con->prepare("SELECT * FROM usuarios WHERE Identif=?");
	$res->bindValue(1, $row['pat_id'], PDO::PARAM_INT);
	$res->execute();
	
    $pat_row = $res->fetch(PDO::FETCH_ASSOC);
    $res = $con->prepare("SELECT * FROM doctors WHERE id=?");
	$res->bindValue(1, $row['med_id'], PDO::PARAM_INT);
	$res->execute();
	
    $doc_row = $res->fetch(PDO::FETCH_ASSOC);
    
    $to = '';
    $Var3 = $type;
    $var4 = '';
    if($Var3 == 'doctor')
    {
        $Var4 = $doc_row['Name'].' '.$doc_row['Surname'];
        $to = $pat_row['email'];
    }
    else
    {
        $Var4 = $pat_row['Name'].' '.$pat_row['Surname'];
        $to = $doc_row['IdMEDEmail'];
    }
    
    $date = new DateTime($row['date']);
    $Var5 = $date->format('F j, Y');
    
    $start_time = new DateTime($row['start_time']);
    $Var6 = $start_time->format('g:i A');
    $end_time = new DateTime($row['end_time']);
    $Var7 = $end_time->format('g:i A');
    
    $domain = 'http://'.strval($dbhost);
    $Var10 = $domain;
    
    $Content = file_get_contents('http://'.$_SERVER['HTTP_HOST'].'/templates/AppointmentMessage.html');
    
    $Content = str_replace("**Var3**",$Var3,$Content);
    $Content = str_replace("**Var4**",$Var4,$Content);
    $Content = str_replace("**Var5**",$Var5,$Content);
    $Content = str_replace("**Var6**",$Var6,$Content);
    $Content = str_replace("**Var7**",$Var7,$Content);
    $Content = str_replace("**Var10**",$Var10,$Content);
    
    if(strlen($to) > 0)
    {
        $body = $Content;
        
        $domain = 'http://'.strval($dbhost);
        
        $subject = 'New Appointment Created';
        
        $transporter = Swift_SmtpTransport::newInstance('smtp.gmail.com', 465, 'ssl')
        ->setUsername('dev.health2me@gmail.com')
        ->setPassword('health2me');
        
        $mailer = Swift_Mailer::newInstance($transporter);
        
        
        $message = Swift_Message::newInstance()
        ->setSubject($subject)
        ->setFrom(array('hub@inmers.us' => 'Health2Me Clinical Support Center'))
        ->setTo(array($to))
        ->setBody($body, 'text/html')
        ;
        
        $result = $mailer->send($message);
        return '1';
    }
    else
    {
        return '0';
    }
}

?>