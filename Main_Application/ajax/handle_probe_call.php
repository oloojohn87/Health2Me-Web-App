<?php
session_start();
echo '<?xml version="1.0" encoding="UTF-8"?>';

require("environment_detailForLogin.php");
require("push_server.php");
//require_once('realtime-notifications/pusherlib/lib/Pusher.php');
require_once('push/push.php');
include('probeLogicClass.php');
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

$response = $_REQUEST['Digits'];
$probeID = $_GET['probeID'];



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
$push = new Push();

$pat = $con->prepare("SELECT Name,Surname,language FROM usuarios WHERE Identif = ?");
$pat->bindValue(1, $probe_row['patientID'], PDO::PARAM_INT);
$pat->execute();
$pat_row = $pat->fetch(PDO::FETCH_ASSOC);

$push->send($probe_row['doctorID'], 'notification', 'New Probe Response from '.$pat_row['Name'].' '.$pat_row['Surname'].' (Answered: '.$response.')');
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
				
                /*
				$test = $con->prepare("INSERT INTO test_table SET text = ?");
				$test->bindValue(1, ('question:'.$question.'startvalue:'.$start_value.'upperendincvalue:'.$expected_end_upper_date.'lowerendincvalue:'.$expected_end_lower_date.'probeinterval:'.$probe_interval.'expectedendvalue:'.$expected_end_value.'deviationup:'.$deviation_up.'deviationdown:'.$deviation_down.'ALERTCOUNT:'.$probe_logic->alert_holder.'RESPONSES:'.$probe_response_holder.'TotalRESPONSES:'.$probe_logic->total_responses.'I:'.$probe_logic->i_holder), PDO::PARAM_STR);
				$result5 = $test->execute();
                */
				
			}
            
            ///////////////////////////////////////////////////////////////////////////////

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

if($next_question != -1)
{
    $update_question = $con->prepare("UPDATE probe SET currentQuestion = ? WHERE probeID = ?");
    $update_question->bindValue(1, $next_question, PDO::PARAM_INT);
    $update_question->bindValue(2, $probeID, PDO::PARAM_INT);
    $update_question->execute();
    echo '<Response><Redirect method="GET">probe_call.php?probeID='.$probeID.'</Redirect></Response>';
}
else
{
    $update_question = $con->prepare("UPDATE probe SET currentQuestion = 1 WHERE probeID = ?");
    $update_question->bindValue(1, $probeID, PDO::PARAM_INT);
    $update_question->execute();
    
    $language = $probe_row['probeLanguage'];
    
    //$language = 'es';
    if($language == 'en'){
        echo '<Response><Say language="en" voice="man">Thank You for updating your health information. Goodbye.</Say></Response>';
    }else{
        echo '<Response><Say language="es" voice="woman">Gracias por actualizar su información médica. Adios!</Say></Response>';
	}
}



?>
