<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('probeLogicClass.php');
require("environment_detailForLogin.php");

//SET DB CONNECTION/
$dbhost = $env_var_db["dbhost"];
$dbname = $env_var_db["dbname"];
$dbuser = $env_var_db["dbuser"];
$dbpass = $env_var_db["dbpass"];
		
$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
if (!$con)
{
	die('Could not connect: ' . mysql_error());
}
		
$probe = $con->prepare("SELECT * FROM probe WHERE scheduledEndDate < NOW() && patientPermission = 1 && doctorPermission = 1");
$probe_result = $probe->execute();
while($probe_row = $probe->fetch(PDO::FETCH_ASSOC)){
	
	$probe_rules = $con->prepare("SELECT * FROM probe_alerts WHERE probe = ?");
	$probe_rules->bindValue(1, $probe_row['probeID'], PDO::PARAM_INT);
	$probe_result = $probe_rules->execute();
	$user_id = $probe_row['patientID'];
	$med_id = $probe_row['doctorID'];
	
	while($probe_rules_row = $probe_rules->fetch(PDO::FETCH_ASSOC)){
	
		$question = $probe_rules_row['question'];
		$start_value = $probe_rules_row['start_value'];
		$expected_end_upper_date = $probe_rules_row['exp_date1'];
		$expected_end_lower_date = $probe_rules_row['exp_date2'];
		$probe_interval = $probe_row['probeInterval'];
		$expected_end_value = $probe_rules_row['exp_value_1'];
		$deviation_up = 1 + ($probe_rules_row['tolerance'] / 100);
		$deviation_down = 1 - ($probe_rules_row['tolerance'] / 100);
		$first_dev_alert = 1;
		$sub_dev_alert = 2;
		$lower_bound = 1;
		$lower_bound_repeat = 1;
		$upper_bound = 9;
		$upper_bound_repeat = 1;
		
		$probe_response_array = array();
		$probe_response = $con->prepare("SELECT * FROM probe_response WHERE probeID = ? && question = ?");
		$probe_response->bindValue(1, $probe_row['probeID'], PDO::PARAM_INT);
		$probe_response->bindValue(1, $question, PDO::PARAM_INT);
		$probe_response_result = $probe_response->execute();
		
		while($probe_response_row = $probe_response->fetch(PDO::FETCH_ASSOC)){
			$probe_response_array[] = $probe_response_row['response'];;
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
		$user_id,
		$med_id);
		
	}
}

/*if(isset($_GET['maketest'])){
	$start_value = $_GET['start_value'];
	$expected_end_upper_date = $_GET['expected_end_upper_date'];
	$expected_end_lower_date = $_GET['expected_end_lower_date'];
	$probe_interval = $_GET['probe_interval'];
	$expected_end_value = $_GET['expected_health'];
	$deviation_up = 1 + ($_GET['dev_up'] / 100);
	$deviation_down = 1 - ($_GET['dev_down'] / 100);
	$first_dev_alert = $_GET['first_dev_count'];
	$sub_dev_alert = $_GET['sub_dev_count'];
	$lower_bound = $_GET['lower_bound'];
	$lower_bound_repeat = $_GET['lower_repeat'];
	$upper_bound = $_GET['upper_bound'];
	$upper_bound_repeat = $_GET['upper_repeat'];
	
	$probe_data = $_GET['probe_data'];
}

if(isset($_GET['maketest'])){
$probe_logic = new probeLogicClass(
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
$probe_data);
}else{
	echo "This is for probe alert testing...
	</br><form method='get' action='probeLogicClassUnit.php' >
	Start Value <input type='number' name='start_value' placeholder='start value' /></br>
	Range where health is expected to end up<input type='date' name='expected_end_upper_date' /><input type='date' name='expected_end_lower_date' /></br>
	Probe interval <input type='number' name='probe_interval' placeholer='days between probes' /></br>
	Expected health value at end of probe regiment(Healthy value)<input type='text' name='expected_health' placeholder='Expected Health ' /></br>
	Alert Deviation Up %<input  type='text' name='dev_up' placeholder='Up Deviation in %'>Above expected value</br>
	Alert Deviation Down %<input  type='text' name='dev_down' placeholder='Down Deviation in %'>Below expected value</br> 
	First Deviation Alert Count<input type='text' name='first_dev_count' placeholder='First Deviation Count' /></br>
	Subsequent Deviation Alert Count<input type='text' name='sub_dev_count' placeholder='Subsequent Deviation Count' /></br>
	Lower Bound Alert @ <input type='text' name='lower_bound' placeholder='Lower Bound' /></br>
	Lower Bound Min Repeat <input type='text' name='lower_repeat' placeholder='Lower Repeat Count' /></br>
	Upper Bound Alert @ <input type='text' name='upper_bound' placeholder='Upper Bound' /></br>
	Upper Bound Min Repeat <input type='text' name='upper_repeat' placeholder='Upper Repeat Count' /></br>
	
	</br>Probe Data<input type='text' name='probe_data' placeholder='1,2,3,2,4,7,6,3,4...' /></br>

	<input type='submit' value='Submit'>
	<input type='hidden' name='maketest' value='yes' />
	</form>";
}*/
?>
