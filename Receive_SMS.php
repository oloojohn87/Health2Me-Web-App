<?php
header("content-type: text/xml");
echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
require("environment_detailForLogin.php");
require("push_server.php");
//require_once('realtime-notifications/pusherlib/lib/Pusher.php');
require_once('push/push.php');
include('probeLogicClass.php');
$dbhost = $env_var_db['dbhost'];
$dbname = $env_var_db['dbname'];
$dbuser = $env_var_db['dbuser'];
$dbpass = $env_var_db['dbpass'];

//$myoutput = 'ALERT: '.$trigger_alert.'     Tolerance: '.$tolerance.'     Probe value: '.$probe.'    Probe date: '.$this->test_date_probe[$i].'   Diferencia: '.$day_since_start.'  Actual Predicted Minimum: '.$actual_predicted_minimum.'   $i = '.$i.'  DEVIATION: '.$actual_deviation;
$myoutput = '************************************* Receive_SMS.PHP **********************************************';
error_log($myoutput); 

$myoutput = '****************************************************************************';
error_log($myoutput); 

$entryvalue = $_GET['Body'];
$myoutput = 'Body :'.$entryvalue;
error_log($myoutput); 


$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
{
    die('Could not connect: ' . mysql_error());
}

$push = new Push();

if(strtolower($_GET['Body']) == 'yes' || strtolower($_GET['Body']) == 'no')
{
    
    $body = strtolower($_GET['Body']);
    $responder = $_GET['From'];
    
    $result = $con->prepare("SELECT id,consultation_pat from doctors where phone like ?");
    $result->bindValue(1, "%".substr($responder,strlen($responder)-10,strlen($responder)), PDO::PARAM_STR);
    $result->execute();
    $row = $result->fetch(PDO::FETCH_ASSOC);
    
    $doc = $row['id'];
    $pat = $row['consultation_pat'];
    //$app_key = 'd869a07d8f17a76448ed';
    //$app_secret = '92f67fb5b104260bbc02';
    //$app_id = '51379';
    //$pusher = new Pusher($app_key, $app_secret, $app_id);
    
    $myoutput = '********************* Inside if loop **********************************************';
    error_log($myoutput); 
    $myoutput = 'Doctor: '.$doc.'     Patient:'.$pat;
    error_log($myoutput); 

    
    if($body == 'no')
    {
        $push->send($pat, 'doc_response', 'n');
        $push->send($doc, 'doc_response', 'n');
        //$pusher->trigger($pat, 'doc_response', 'n');
        //$pusher->trigger($doc, 'doc_response', 'n');
        echo '<Response></Response>';
    }
    else
    {
        $push->send($pat, 'doc_response', 'y');
        //$pusher->trigger($pat, 'doc_response', 'y');
        echo '<Response><Message>Please go to the Health2Me Home page to connect to your patient</Message></Response>';
    }
    
    if($body == 'no')
    {
        $result = $con->prepare("UPDATE doctors SET in_consultation=0,telemed_type=0 WHERE id = ?");
        $result->bindValue(1, $doc, PDO::PARAM_INT);
        $result->execute();
        
        $result = $con->prepare("SELECT consultationId FROM consults WHERE Doctor = ? ORDER BY consultationId DESC LIMIT 1");
        $result->bindValue(1, $doc, PDO::PARAM_INT);
        $result->execute();
        $row = $result->fetch(PDO::FETCH_ASSOC);
        
        $result = $con->prepare("UPDATE consults SET length = TIMESTAMPDIFF(SECOND,DateTime,now()), Status = 'Canceled' WHERE consultationId = ?");
        $result->bindValue(1, $row['consultationId'], PDO::PARAM_INT);
        $result->execute();
        
    }
}
else if(($_GET['Body'][0] == 'm' || $_GET['Body'][0] == 'M') && ($_GET['Body'][1] == 'e' || $_GET['Body'][1] == 'E') && ($_GET['Body'][2] == 'd' || $_GET['Body'][2] == 'D'))
{
    // this is a response to a medication reminder
    $response = str_replace('"', '', strtolower($_GET['Body']));
    $id = substr($response, 3, strlen($response) - 3);
    $insert = $con->prepare("INSERT INTO medication_reminders_response SET reminder_id = ?, date = ?");
    $insert->bindValue(1, $id, PDO::PARAM_INT);
    $insert->bindValue(2, time(), PDO::PARAM_STR);
    $insert->execute();
    
    $update = $con->prepare("UPDATE medication_reminders SET misses = 0 WHERE id = ?");
    $update->bindValue(1, $id, PDO::PARAM_INT);
    $update->execute();
}
else
{ 
    $body = $_GET['Body'];
    $responder = $_GET['From'];
    $response = $body;
    
    $myoutput = '**** SMS RECEIVER  ****    Responder :'.$responder;
    error_log($myoutput); 

    
    if (strlen($body) > 0) 
    {  
        $lbody = strtolower($body);
        $msgBody = str_replace(' ','',$lbody);
        if($msgBody=='verybad')
        {
            $response = 1;
        }
        else if($msgBody=='bad')
        {
            $response = 2;
        }
        else if($msgBody=='normal')
        {
            $response = 3;
        }
        else if($msgBody=='good')
        {
            $response = 4;
        }
        else if($msgBody=='verygood')
        {
            $response = 5;
        }
        else if($msgBody=='y')
        {
            $response = 1;
        }
        else if($msgBody=='n')
        {
            $response = 2;
        }
        
        $probeID = 	getProbeIdFromPhoneNumber($responder, $con);
        
        $myoutput = '**** SMS RECEIVER  ****    Probe id :'.$probeID;
        error_log($myoutput); 

        
        if($probeID=="NOT_FOUND")
        {
            writeERRORLog($responder,$probeID);
        }
        else
        {
            $probe = $con->prepare("SELECT * FROM probe WHERE probeID = ?");
            $probe->bindValue(1, $probeID, PDO::PARAM_INT);
            $probe->execute();
            $probe_row = $probe->fetch(PDO::FETCH_ASSOC);
            
            $protocol = $con->prepare("SELECT * FROM probe_protocols WHERE protocolID = ?");
            $protocol->bindValue(1, $probe_row['protocolID'], PDO::PARAM_INT);
            $protocol->execute();
            $protocol_row = $protocol->fetch(PDO::FETCH_ASSOC);
            
            $p_response = $con->prepare("INSERT INTO proberesponse SET probeID = ?, response = ?, question = ?, responseTime = NOW()");
            $p_response->bindValue(1, $probeID, PDO::PARAM_INT);
            $p_response->bindValue(2, $response, PDO::PARAM_INT);
            $p_response->bindValue(3, $probe_row['currentQuestion'], PDO::PARAM_INT);
            $p_response->execute();
            
            
            // send a push notification to the doctor of the new message
            //$app_key = 'd869a07d8f17a76448ed';
            //$app_secret = '92f67fb5b104260bbc02';
            //$app_id = '51379';
            //$pusher = new Pusher($app_key, $app_secret, $app_id);

            $pat = $con->prepare("SELECT Name,Surname FROM usuarios WHERE Identif = ?");
            $pat->bindValue(1, $probe_row['patientID'], PDO::PARAM_INT);
            $pat->execute();
            $pat_row = $pat->fetch(PDO::FETCH_ASSOC);
            
            $push->send($probe_row['doctorID'], 'notification', 'New Probe Response from '.$pat_row['Name'].' '.$pat_row['Surname'].' (Answered: '.$response.')');
            //$pusher->trigger($probe_row['doctorID'], 'notification', 'New Probe Response from '.$pat_row['Name'].' '.$pat_row['Surname'].' (Answered: '.$response.')');
            // send a push notification to the doctor of the new message

            ///////////////////////PROBE ALERT LOGIC////////////////////////////////////////
            
            $probe_rules = $con->prepare("SELECT * FROM probe_alerts WHERE probe = ?");
			$probe_rules->bindValue(1, $probeID, PDO::PARAM_INT);
			$probe_result = $probe_rules->execute();
			$user_id = $probe_row['patientID'];
			$med_id = $probe_row['doctorID'];
			
			while($probe_rules_row = $probe_rules->fetch(PDO::FETCH_ASSOC)){
			
				$question = $probe_rules_row['question'];
				$start_value = $probe_rules_row['start_value'];
				$expected_end_upper_date = $probe_rules_row['exp_day_1'];
				$expected_end_lower_date = $probe_rules_row['exp_day_2'];
				$probe_interval = $probe_row['probeInterval'];
				$expected_end_value = $probe_rules_row['exp_value'];
				$deviation_up = 1 + ($probe_rules_row['tolerance'] / 100);
				$deviation_down = 1 - ($probe_rules_row['tolerance'] / 100);
				$first_dev_alert = 1;
				$sub_dev_alert = 2;
				$lower_bound = 1;
				$lower_bound_repeat = 1;
				$upper_bound = 9;
				$upper_bound_repeat = 1;
				
				$probe_response_array = array();
				$probe_date_array = array();
                $probe_response = $con->prepare("SELECT * FROM proberesponse WHERE probeID = ? && question = ?");
				$probe_response->bindValue(1, $probe_row['probeID'], PDO::PARAM_INT);
				$probe_response->bindValue(2, $question, PDO::PARAM_INT);
				$probe_response_result = $probe_response->execute();
				
				$probe_response_holder = 'HOLDER';
				while($probe_response_row = $probe_response->fetch(PDO::FETCH_ASSOC)){
					$probe_response_array[] = $probe_response_row['response'];
                    $probe_date_array[] = $probe_response_row['responseTime'];
					$probe_response_holder = $probe_response_holder.$probe_response_row['response'];
				}
				
				$probe_logic = new probeLogicClass(
				$question,
				$start_value, 
				$expected_end_upper_date, 
				$expected_end_lower_date, 
				$probe_interval,  
				$expected_end_value, 
				$deviation_up, 
				$deviation_down, 
				$first_dev_alert, 
				$sub_dev_alert, 
				$lower_bound, 
				$lower_bound_repeat, 
				$upper_bound, 
				$upper_bound_repeat,
				$probe_response_array,
				$probe_date_array,
                $user_id,
				$med_id,
                $probeID);
				
				//$test = $con->prepare("INSERT INTO test_table SET text = ?");
				//$test->bindValue(1, ('question:'.$question.'startvalue:'.$start_value.'upperendincvalue:'.$expected_end_upper_date.'lowerendincvalue:'.$expected_end_lower_date.'probeinterval:'.$probe_interval.'expectedendvalue:'.$expected_end_value.'deviationup:'.$deviation_up.'deviationdown:'.$deviation_down.'ALERTCOUNT:'.$probe_logic->alert_holder.'RESPONSES:'.$probe_response_holder), PDO::PARAM_STR);
				//$result5 = $test->execute();
				
			}
            
            ///////////////////////////////////////////////////////////////////////////////
            
            //Get Name for Timezone
            $result = $con->prepare("SELECT name FROM timezones WHERE id = (SELECT timezone FROM probe WHERE probeID = ?)");
            $result->bindValue(1, $probeID, PDO::PARAM_INT);
            $result->execute();
            $row = $result->fetch(PDO::FETCH_ASSOC);
            $timezone = $row['name'];
            
            $result = $con->prepare("SELECT NOW() AS currentDateTime");
            $result->execute();
            $row = $result->fetch(PDO::FETCH_ASSOC);
            $date_str = $row['currentDateTime'];	
            
            //Convert current GMT to Patient Timezone bacause we are storing the patient response time in the database
            $PatientDateTime = convertFromGMT($timezone,$date_str);
            
            $found = false;
            $next_question = -1;
            for($i = $probe_row['currentQuestion'] + 1; $i <= 5; $i++)
            {
                if(!$found && $protocol_row['question'.$i] != NULL)
                {
                    $found = true;
                    $next_question = $i;
                }
            }
            
            $doc = $con->prepare("SELECT Surname FROM doctors WHERE id = ?");
            $doc->bindValue(1, $probe_row['doctorID'], PDO::PARAM_INT);
            $doc->execute();
            $doc_row = $doc->fetch(PDO::FETCH_ASSOC);
            
            $pat = $con->prepare("SELECT Name,Surname,telefono FROM usuarios WHERE Identif = ?");
            $pat->bindValue(1, $probe_row['patientID'], PDO::PARAM_INT);
            $pat->execute();
            $pat_row = $pat->fetch(PDO::FETCH_ASSOC);
            
            if($next_question != -1)
            {
                $update_question = $con->prepare("UPDATE probe SET currentQuestion = ? WHERE probeID = ?");
                $update_question->bindValue(1, $next_question, PDO::PARAM_INT);
                $update_question->bindValue(2, $probeID, PDO::PARAM_INT);
                $update_question->execute();
                
                Send_Feedback_SMS($doc_row['Surname'],$pat_row['Name'].' '.$pat_row['Surname'],$pat_row['telefono'],$probeID,0, $next_question);
            }
            else
            {
                $update_question = $con->prepare("UPDATE probe SET currentQuestion = 1 WHERE probeID = ?");
                $update_question->bindValue(1, $probeID, PDO::PARAM_INT);
                $update_question->execute();
                $language = 'en';
                if($language == 'en')
                    echo '<Response><Message>Thank You for updating your health information.</Message></Response>';
                else if($language == 'es')
                    echo '<Response><Message>Gracias por actualizar su información médica.</Message></Response>';
            }
            
            if($msgBody=='stop')
            {
                $result = $con->prepare("UPDATE probe SET patientPermission=0 WHERE probeID = ?");
                $result->bindValue(1, $probeID, PDO::PARAM_INT);
                $result->execute();
            }
            else if($msgBody=='start')
            {
                $result = $con->prepare("UPDATE probe SET patientPermission=1 WHERE probeID = ?");
                $result->bindValue(1, $probeID, PDO::PARAM_INT);
                $result->execute();
                return;
            }
            
            $result = $con->prepare("SELECT max(id) AS maxid FROM sentprobelog WHERE probeID = ? and method=3");
            $result->bindValue(1, $probeID, PDO::PARAM_INT);
            $result->execute();
            $row = $result->fetch(PDO::FETCH_ASSOC);
            $targetid=$row['maxid'];
            
            $result = $con->prepare("UPDATE sentprobelog SET result = ?,requestTime = ? WHERE id = ?");
            $result->bindValue(1, $response, PDO::PARAM_INT);
            $result->bindValue(2, $PatientDateTime, PDO::PARAM_STR);
            $result->bindValue(3, $targetid, PDO::PARAM_INT);
            $result->execute();
        }
    }
    
}

function getDoctorIDPatientName($probeID)
{
    require("environment_detail.php");
            
    $dbhost = $env_var_db['dbhost'];
    $dbname = $env_var_db['dbname'];
    $dbuser = $env_var_db['dbuser'];
    $dbpass = $env_var_db['dbpass'];

    $con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

    if (!$con)
    {
        die('Could not connect: ' . mysql_error());
    }
    
    $result = $con->prepare("SELECT p.doctorID,concat(u.Name,' ',u.Surname) AS fullname FROM probe p,usuarios u WHERE p.patientID = u.identif AND p.probeID = ?");
    $result->bindValue(1, $probeID, PDO::PARAM_INT);
    $result->execute();
    $row = $result->fetch(PDO::FETCH_ASSOC);
    $string=$row['doctorID']."::".$row['fullname'];
    return $string;
    
    

}


function getProbeIdFromPhoneNumber($phonenumber, $con)
{
    //$searchPhoneQuery = "select identif from usuarios where length(telefono)>0 and instr('".$phonenumber."',telefono)>0" ;
    $result = $con->prepare("SELECT Identif FROM usuarios WHERE telefono LIKE ?");
    $result->bindValue(1, "%".substr($phonenumber,strlen($phonenumber)-6,strlen($phonenumber)), PDO::PARAM_STR);
    $result->execute();
    $num_rows = $result->rowCount();
    
    $searchstring = "%".substr($phonenumber,strlen($phonenumber)-6,strlen($phonenumber));
    $myoutput = '**** SMS RECEIVER  ****    Search String :'.$searchstring;
    error_log($myoutput); 

    
    if($num_rows==1)
    {
            $row = $result->fetch(PDO::FETCH_ASSOC);
        

            $result = $con->prepare("SELECT probeID FROM probe WHERE smsRequest=1 AND patientID = ?");
            $result->bindValue(1, $row['Identif'], PDO::PARAM_INT);

            $result->execute();
            $num_rows = $result->rowCount();
        
            if($num_rows==1)
            {
                $row1 = $result->fetch(PDO::FETCH_ASSOC);
                return $row1['probeID'];
            }
    
    }
    return "NOT_FOUND";
}

function writeERRORLog($phonenumber,$body)
{
    $file = 'ReceivedSMSErrorLog.txt';
    // Open the file to get existing content
    $current = file_get_contents($file);
    // Append a new person to the file
    $current .= '\n'.$phonenumber.' '.$body;
    // Write the contents back to the file
    file_put_contents($file, $current);

}


?>

