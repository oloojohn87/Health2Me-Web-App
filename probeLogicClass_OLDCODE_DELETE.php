			//echo "Expected Value Upper: ".$i * $this->expected_step_upper.'</br>';
			//echo "Expected Value Lower: ".$i * $this->expected_step_lower.'</br>';
			
            // OLDER CODE COMMENTED
            /*
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
			*/
             // OLDER CODE COMMENTED
