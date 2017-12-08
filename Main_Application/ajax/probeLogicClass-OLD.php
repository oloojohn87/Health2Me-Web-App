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
	public function __construct($question, $start_value, $expected_end_upper_date, $expected_end_lower_date, $probe_interval, $expected_end_value, $deviation_up, $deviation_down, $first_dev_alert, $sub_dev_alert, $lower_bound, $lower_bound_repeat, $upper_bound, $upper_bound_repeat, $probe_data, $user_id, $med_id, $probe_id){
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
		
		$total_responses = count($this->test_probe) - 1;
		$this->total_responses = $total_responses;
		// This foreach traverses the array of responses for this question
        // $probe holds every individual response
        foreach($this->test_probe as $probe){
			if($probe > $prev_probe_holder && $prev_probe_holder != ''){
				//echo 'increase in health by'.($probe - $prev_probe_holder).'</br>';
			}elseif($probe == $prev_probe_holder && $prev_probe_holder != ''){
				//echo 'same</br>';
			}elseif($probe < $prev_probe_holder && $prev_probe_holder != ''){
				///echo 'decrease in health by'.($probe - $prev_probe_holder).'</br>';
			}
			
			//echo "Expected Value Upper: ".$i * $this->expected_step_upper.'</br>';
			//echo "Expected Value Lower: ".$i * $this->expected_step_lower.'</br>';
			
			$expected_upper_value = $i * $this->expected_step_upper * $this->deviation_up;
			
			if($expected_upper_value > $this->expected_end_value){
				$expected_upper_value = $this->expected_end_value;
			}
			
			if($expected_upper_value < $probe && $i != 0){
				//echo "<font color='green'>UP</font></br>";
				$increase_alert++;
				if($increase_alert >= $this->min_recurrences && $min_increase_holder == 0 && $i != 0){
					//echo "ALERT : UNEXPECTED <font color='green'>INCREASE</font> IN HEALTH{DEVIATION:".$this->deviation_up."}FIRST</br>";
					$alert_holder++;
					if($i == $total_responses){
						$this->notification->add('PRBALR', $this->user_id, false, $this->med_id, true, $this->probe_id);
					}
					$min_increase_holder = 1;
				}elseif(($increase_alert) >= (($this->max_recurrences * $upper_sub_count) + $this->min_recurrences) && $min_increase_holder == 1){
					$alert_holder++;
					//echo "ALERT : UNEXPECTED <font color='green'>INCREASE</font> IN HEALTH{DEVIATION:".$this->deviation_up."}SUB</br>";
					if($i == $total_responses){
						$this->notification->add('PRBALR', $this->user_id, false, $this->med_id, true, $this->probe_id);
					}
					$upper_sub_count++;
				}
				
			}
			
			$expected_lower_value = $i * $this->expected_step_lower * $this->deviation_down;
			
			if($expected_lower_value > $this->expected_end_value){
				$expected_lower_value = $this->expected_end_value;
			}
			
			if($expected_lower_value > $probe && $i != 0){
				//echo "<font color='red'>DOWN</font></br>";
				$decrease_alert++;
				if($decrease_alert >= $this->min_recurrences && $min_decrease_holder == 0 && $i != 0){
					$alert_holder++;
					//echo "ALERT : UNEXPECTED <font color='red'>DROP</font> IN HEALTH{DEVIATION:".$this->deviation_down."}FIRST</br>";
					if($i == $total_responses){
						$this->notification->add('PRBALR', $this->user_id, false, $this->med_id, true, $this->probe_id);
					}
					$min_decrease_holder = 1;
				}elseif(($decrease_alert) >= (($this->max_recurrences * $lower_sub_count) + $this->min_recurrences) && $min_decrease_holder == 1){
					$alert_holder++;
					//echo "ALERT : UNEXPECTED <font color='red'>DROP</font> IN HEALTH{DEVIATION:".$this->deviation_down."}SUB</br>";
					if($i == $total_responses){
						$this->notification->add('PRBALR', $this->user_id, false, $this->med_id, true, $this->probe_id);
					}
					$lower_sub_count++;
				}
				
			}
			
			if($probe == $this->lower_bound_alert && $i != 0 && $lower_bound_min == $this->lower_bound_count - 1){
				//echo "<font color='red'>LOWER BOUND ALERT!</font></br>";
				$lower_bound_min = 0;
			}elseif($probe == $this->lower_bound_alert && $i != 0){
				$lower_bound_min++;
			}
			
			if($probe == $this->upper_bound_alert && $i != 0 && $upper_bound_min == $this->upper_bound_count - 1){
				//echo "<font color='green'>UPPER</font> BOUND ALERT!</br>";
				$upper_bound_min = 0;
			}elseif($probe == $this->upper_bound_alert && $i != 0 ){
				$upper_bound_min++;
			}
			
			if($probe == $this->expected_end_value){
				//echo "<font color='blue'>PATIENT IS HEALTHY NOW!</font></br>";
			}
			
			//echo "Probe Value: ".$probe."</br><hr>";
			
			$prev_probe_holder = $probe;
			$i++;
		}
		$this->i_holder = $i;
	}
}

?>
