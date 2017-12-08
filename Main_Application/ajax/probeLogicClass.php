<?php

class probeLogicClass{

	//PDO
	private $con;
	
	//Testing probes
	public $test_probe = array(1,2,3,4,5,6,5,4,5,2,3,1,1,2,1,2,5,9,9,9,9,9,10);
	
	//Criteria
	public $deviation_up = 1.1;
	public $deviation_down = .9;
	public $max_recurrences = 5;
	public $min_recurrences = 2;
	public $expected_direction = 1;
	public $expected_end_value = 10;
	public $start_value;
	public $question;
	public $user_id;
	public $med_id;
    public $probe_id;
	
	//Notification Object
	public $notification;
	
	//Alert holder
	public $alert_holder = 0;
	public $total_responses;
	public $i_holder;
	
	//public $expected_period_count = 20;
	public $expected_period_count_start = 15;
	public $expected_period_count_end = 25;
	
	//public $expected_step;
	public $expected_step_upper;
	public $expected_step_lower;
	
	public $lower_bound_alert = 1;
	public $upper_bound_alert = 9;
	public $lower_bound_count = 2;
	public $upper_bound_count = 5;
	
	//Interval
	public $probe_interval;
	public function __construct($question, $start_value, $expected_end_upper_date, $expected_end_lower_date, $probe_interval, $expected_end_value, $deviation_up, $deviation_down, $first_dev_alert, $sub_dev_alert, $lower_bound, $lower_bound_repeat, $upper_bound, $upper_bound_repeat, $probe_data, $probe_date_data, $user_id, $med_id, $probe_id){
		require("environment_detailForLogin.php");
		include_once('NotificationClass.php');
        // $probe_data contains the array with all answers for the selected question (id of this question) in selected probe (see as example handle_probe_call.php line 80)
        // $probe_data is passed to $this->test_probe
        $this->notification = new Notifications();
		
		//SET DB CONNECTION/
		$this->dbhost = $env_var_db["dbhost"];
		$this->dbname = $env_var_db["dbname"];
		$this->dbuser = $env_var_db["dbuser"];
		$this->dbpass = $env_var_db["dbpass"];
		
		$this->con = new PDO('mysql:host='.$this->dbhost.';dbname='.$this->dbname.';charset=utf8', ''.$this->dbuser.'', ''.$this->dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
		if (!$this->con)
		{
			die('Could not connect: ' . mysql_error());
		}
		
		$this->deviation_up = $deviation_up;
		$this->deviation_down = $deviation_down;
		$this->max_recurrences = $sub_dev_alert;
		$this->min_recurrences = $first_dev_alert;
		$this->expected_end_value = $expected_end_value;
		$this->probe_interval = $probe_interval;
		$this->lower_bound_alert = $lower_bound;
		$this->upper_bound_alert = $upper_bound;
		$this->lower_bound_count = $lower_bound_repeat;
		$this->upper_bound_count = $upper_bound_repeat;
		$this->test_probe = $probe_data;
		$this->test_date_probe = $probe_date_data;
		$this->question = $question;
		$this->user_id = $user_id;
		$this->med_id = $med_id;
        
        $this->probe_id = $probe_id;
		
		$now = date('Y-m-d');
		$today = new DateTime($now);

		$this->expected_period_count_start = $expected_end_upper_date;
		$this->expected_period_count_end = $expected_end_lower_date;
		
		$this->start_value = $start_value;
		//$this->expected_step =  $this->expected_end_value / $this->expected_period_count;
		$this->expected_step_upper = ($this->expected_end_value - $this->start_value) / $this->expected_period_count_start;
		$this->expected_step_lower = ($this->expected_end_value - $this->start_value) / $this->expected_period_count_end;
		
		$this->doLogic();
	}
	
	private function doLogic(){
        require_once('push/push.php');
		$prev_probe_holder = '';
		$i = 0;
		$increase_alert = 0;
		$min_increase_holder = 0;
		$decrease_alert = 0;
		$min_decrease_holder = 0;
		$increase_sub_max_occurance = 0;
		$decrease_sub_max_occurance = 0;
		$upper_bound_min = 0;
		$lower_bound_min = 0;
		$lower_sub_count = 1;
		$upper_sub_count = 1;
		$tolerance = ($this->deviation_up - 1) * 100;
        
		$total_responses = count($this->test_probe) - 1;
		$this->total_responses = $total_responses;
		// This foreach traverses the array of responses for this question
        // $probe holds every individual response
        $latest_alert_snooze_limit_day = 0;  // Sets snooze period-limit-day to zero for a clean start
        $snooze_period = 3; // default snooze period (This is an arbitrary value; should be configured by doctor in the future and retrieved from database here)
        foreach($this->test_probe as $probe){
			if($probe > $prev_probe_holder && $prev_probe_holder != ''){
				//echo 'increase in health by'.($probe - $prev_probe_holder).'</br>';
			}elseif($probe == $prev_probe_holder && $prev_probe_holder != ''){
				//echo 'same</br>';
			}elseif($probe < $prev_probe_holder && $prev_probe_holder != ''){
				///echo 'decrease in health by'.($probe - $prev_probe_holder).'</br>';
			}
			
            // New calculations           
            $maximum_deviation = $this->expected_end_value - $this->start_value;
            if ($maximum_deviation >= 0) $inverted = 0; else $inverted = 1;
            $maximum_deviation = abs($maximum_deviation);
            $date1 = new DateTime($this->test_date_probe[0]);
            $date2 = new DateTime($this->test_date_probe[$i]);
            $day_since_start = $date2->diff($date1)->format("%a");
            $actual_predicted_minimum = $this->start_value + ($day_since_start  * (($this->expected_end_value - $this->start_value) / $this->expected_period_count_end ));              
            if ($inverted == 0){
                $actual_deviation = $probe - $actual_predicted_minimum ;  
            }else{
                $actual_deviation = $actual_predicted_minimum - $probe;       
            }
            $actual_deviation = (100 * $actual_deviation) / $maximum_deviation;
            if ($actual_deviation < 0 && abs($actual_deviation) > $tolerance && ($day_since_start >= $latest_alert_snooze_limit_day)) {
                $trigger_alert = 1;
                $latest_alert_snooze_limit_day = $day_since_start + $snooze_period;  // This sets up a "snooze" period for other potential further aterts that will disable any new triggering until it ends
            }else{
                $trigger_alert = 0;
            }
            if ($trigger_alert == 1 && $i == $total_responses) {
                $this->notification->add('PRBALR', $this->user_id, false, $this->med_id, true, $this->probe_id);
                // send a notification to the doctor of the new message
                //$app_key = 'd869a07d8f17a76448ed';
                //$app_secret = '92f67fb5b104260bbc02';
                //$app_id = '51379';
                //$pusher = new Pusher($app_key, $app_secret, $app_id);
                $push = new Push();

                $pat = $this->con->prepare("SELECT Name,Surname FROM usuarios WHERE Identif = ?");
                $pat->bindValue(1, $this->user_id, PDO::PARAM_INT);
                $pat->execute();
                $pat_row = $pat->fetch(PDO::FETCH_ASSOC);

                $push->send($this->med_id, 'notification', 'New Probe ALERT from '.$pat_row['Name'].' '.$pat_row['Surname'].' ');
                // send a notification to the doctor of the new message
            };
            $myoutput = 'ALERT: '.$trigger_alert.'     Tolerance: '.$tolerance.'     Probe value: '.$probe.'    Probe date: '.$this->test_date_probe[$i].'   Diferencia: '.$day_since_start.'  Actual Predicted Minimum: '.$actual_predicted_minimum.'   $i = '.$i.'  DEVIATION: '.$actual_deviation;
            error_log($myoutput);             
            // New calculations (end)
            
            
			$prev_probe_holder = $probe;
			$i++;
		}
		$this->i_holder = $i;
	}
}

?>
