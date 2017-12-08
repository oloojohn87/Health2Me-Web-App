<?php
$MEDID = $_GET['docid'];
$member = $_GET['member'];
?>
<div><div style="width: 23%; height: 100%; float: left;">
                        <button onclick="setTimeSlot('8am - 10am');" class="slots_button" id="8_10" style="width:100%">8 to 10 am</button>
                        <button onclick="setTimeSlot('10am - 12pm');" class="slots_button" id="10_12" style="width:100%">10am to 12 pm</button>
                        <button onclick="setTimeSlot('12pm - 2pm');" class="slots_button" id="12_2" style="width:100%">12pm to 2 pm</button>
						<button onclick="setTimeSlot('2pm - 4pm');" class="slots_button" id="2_4" style="width:100%">2pm to 4 pm</button>
						<button onclick="setTimeSlot('4pm - 6pm');" class="slots_button" id="4_6" style="width:100%">4pm to 6 pm</button>
						<button onclick="setTimeSlot('6pm - 8pm');" class="slots_button" id="6_8" style="width:100%">6pm to 8 pm</button>
						<button onclick="setTimeSlot('8pm - 10pm');" class="slots_button" id="8_10" style="width:100%">8pm to 10 pm</button>
						<button id="button_appointment_phone" onclick="createDoctorAppointmentPhone(<?php echo $MEDID; ?>, <?php echo $member; ?>);" style="width:100%;" disabled><i class="icon-phone icon-5x"></i></button>
						<button id="button_appointment_video" onclick="createDoctorAppointmentVideo(<?php echo $MEDID; ?>, <?php echo $member; ?>);" style="width:100%;" disabled><i class="icon-facetime-video icon-5x"></i></button>
                    </div>
					
					<div>
					<div style="float:left;margin-left:5%;" onclick="pullCalendar(<?php echo $_GET['holder'] - 1; ?>, <?php echo $_GET['member']; ?>);">
					<--
					</div>
					
					<div style="float:right;margin-right:4%;" onclick="pullCalendar(<?php echo $_GET['holder'] + 1; ?>, <?php echo $_GET['member']; ?>);">
					-->
					</div>
					</div>
					
					<?php
					require("environment_detail.php");
					$dbhost = $env_var_db['dbhost'];
					$dbname = $env_var_db['dbname'];
					$dbuser = $env_var_db['dbuser'];
					$dbpass = $env_var_db['dbpass'];
					
					$pagination = $_GET['holder'] * 28;
					
					$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }	

$result = $con->prepare("SELECT date FROM appointments WHERE med_id = ? && pat_id = ?");
$result->bindValue(1, $MEDID, PDO::PARAM_INT);
$result->bindValue(2, $member, PDO::PARAM_INT);
$result->execute();
$arr = array();
$arr_count = 0;

while($row = $result->fetch(PDO::FETCH_ASSOC)){
$arr[$arr_count] = $row['date'];
$arr_count++;
}
  
					for($i=0; $i<=3; $i++){ ?>
					
                    <div style="width: 70%; height: 75px; float: right;margin-left:5%;margin-top:2%;">
                        
                            <?php $today = new DateTime('now'); $today_dow = intval($today->format('N')); $dow = 0 + ($i * 7) + $pagination;
                            if($today_dow > $dow)
                            {
                                $date_interval = new DateInterval('P'.strval(($today_dow) - $dow).'D');
								$date_interval->invert = 1;
                                $today->add($date_interval);
								$background_color = 'background-color:#E74C3C;';
								$make_disable = 'disabled';
								$date_holder = $today->format('Y-m-d');
								$make_onclick = '';
								
								foreach($arr as $date){
								if($date == $date_holder){
								$background_color = 'background-color:#8181F7;';
								}
								}
								
                            }
                            else if($today_dow < $dow)
                            {
                                $date_interval = new DateInterval('P'.strval($dow - $today_dow).'D');
                                $today->add($date_interval);
                                $background_color = '';
								$make_disable = '';
								$date_holder = $today->format('Y-m-d');
								$make_onclick = "setDateSlot('$date_holder');";
								
								foreach($arr as $date){
								if($date == $date_holder){
								$background_color = 'background-color:#8181F7;';
								}
								}
								
                            }elseif($today_dow == $dow){
								$background_color = 'background-color:#48a25a;';
								$make_disable = '';
								$date_holder = $today->format('Y-m-d');
								$make_onclick = "setDateSlot('$date_holder');";
								
								foreach($arr as $date){
								if($date == $date_holder){
								$background_color = 'background-color:#8181F7;';
								}
								}
								
							}
							echo '<button onclick="'.$make_onclick.'" class="days_button" id="sun" style="width:13%;'.$background_color.'" '.$make_disable.'">Sun<br/>';
                            echo '<span style="font-size: 10px;">'.$today->format('d M').'</span>';
                            echo '<input type="hidden" value="'.$today->format('Y-m-d').'" />';
                            ?>
                        <input type="hidden" value="" />
                        <input type="hidden" value="" />
                        </button>
                        
                            <?php $today = new DateTime('now'); $today_dow = intval($today->format('N')); $dow = 1 + ($i * 7) + $pagination;
                            if($today_dow > $dow)
                            {
                                $date_interval = new DateInterval('P'.strval(($today_dow) - $dow).'D');
								$date_interval->invert = 1;
                                $today->add($date_interval);
								$background_color = 'background-color:#E74C3C;';
								$make_disable = 'disabled';
								$date_holder = $today->format('Y-m-d');
								$make_onclick = '';
								
								foreach($arr as $date){
								if($date == $date_holder){
								$background_color = 'background-color:#8181F7;';
								}
								}
								
                            }
                            else if($today_dow < $dow)
                            {
                                $date_interval = new DateInterval('P'.strval($dow - $today_dow).'D');
                                $today->add($date_interval);
                                $background_color = '';
								$make_disable = '';
								$date_holder = $today->format('Y-m-d');
								$make_onclick = "setDateSlot('$date_holder');";
								
								foreach($arr as $date){
								if($date == $date_holder){
								$background_color = 'background-color:#8181F7;';
								}
								}
								
                            }elseif($today_dow == $dow){
								$background_color = 'background-color:#48a25a;';
								$make_disable = '';
								$date_holder = $today->format('Y-m-d');
								$make_onclick = "setDateSlot('$date_holder');";
								
								foreach($arr as $date){
								if($date == $date_holder){
								$background_color = 'background-color:#8181F7;';
								}
								}
								
							}
							echo '<button onclick="'.$make_onclick.'" class="days_button" id="mon" lang="en" style="width:13%;'.$background_color.'" '.$make_disable.'">Mon<br/>';
                            echo '<span style="font-size: 10px;">'.$today->format('d M').'</span>';
                            echo '<input type="hidden" value="'.$today->format('Y-m-d').'" />';
                            ?>
                        <input type="hidden" value="" />
                        <input type="hidden" value="" />
                        </button>
                        
                            <?php $today = new DateTime('now'); $today_dow = intval($today->format('N')); $dow = 2 + ($i * 7) + $pagination;
                            if($today_dow > $dow)
                            {
                                $date_interval = new DateInterval('P'.strval(($today_dow) - $dow).'D');
								$date_interval->invert = 1;
                                $today->add($date_interval);
								$background_color = 'background-color:#E74C3C;';
								$make_disable = 'disabled';
								$date_holder = $today->format('Y-m-d');
								$make_onclick = '';
								
								foreach($arr as $date){
								if($date == $date_holder){
								$background_color = 'background-color:#8181F7;';
								}
								}
								
                            }
                            else if($today_dow < $dow)
                            {
                                $date_interval = new DateInterval('P'.strval($dow - $today_dow).'D');
                                $today->add($date_interval);
                                $background_color = '';
								$make_disable = '';
								$date_holder = $today->format('Y-m-d');
								$make_onclick = "setDateSlot('$date_holder');";
								
								foreach($arr as $date){
								if($date == $date_holder){
								$background_color = 'background-color:#8181F7;';
								}
								}
								
                            }elseif($today_dow == $dow){
								$background_color = 'background-color:#48a25a;';
								$make_disable = '';
								$date_holder = $today->format('Y-m-d');
								$make_onclick = "setDateSlot('$date_holder');";
								
								foreach($arr as $date){
								if($date == $date_holder){
								$background_color = 'background-color:#8181F7;';
								}
								}
								
							}
							echo '<button onclick="'.$make_onclick.'" class="days_button" id="tues" lang="en" style="width:13%;'.$background_color.'" '.$make_disable.'">Tues<br/>';
                            echo '<span style="font-size: 10px;">'.$today->format('d M').'</span>';
                            echo '<input type="hidden" value="'.$today->format('Y-m-d').'" />';
                            ?>
                        <input type="hidden" value="" />
                        <input type="hidden" value="" />
                        </button>
                        
                            <?php $today = new DateTime('now'); $today_dow = intval($today->format('N')); $dow = 3 + ($i * 7) + $pagination;
                            if($today_dow > $dow)
                            {
                                $date_interval = new DateInterval('P'.strval(($today_dow) - $dow).'D');
								$date_interval->invert = 1;
                                $today->add($date_interval);
								$background_color = 'background-color:#E74C3C;';
								$make_disable = 'disabled';
								$date_holder = $today->format('Y-m-d');
								$make_onclick = '';
								
								foreach($arr as $date){
								if($date == $date_holder){
								$background_color = 'background-color:#8181F7;';
								}
								}
								
                            }
                            else if($today_dow < $dow)
                            {
                                $date_interval = new DateInterval('P'.strval($dow - $today_dow).'D');
                                $today->add($date_interval);
                                $background_color = '';
								$make_disable = '';
								$date_holder = $today->format('Y-m-d');
								$make_onclick = "setDateSlot('$date_holder');";
								
								foreach($arr as $date){
								if($date == $date_holder){
								$background_color = 'background-color:#8181F7;';
								}
								}
								
                            }elseif($today_dow == $dow){
								$background_color = 'background-color:#48a25a;';
								$make_disable = '';
								$date_holder = $today->format('Y-m-d');
								$make_onclick = "setDateSlot('$date_holder');";
								
								foreach($arr as $date){
								if($date == $date_holder){
								$background_color = 'background-color:#8181F7;';
								}
								}
								
							}
							echo '<button onclick="'.$make_onclick.'" class="days_button" id="wed" lang="en" style="width:13%;'.$background_color.'" '.$make_disable.'">Wed<br/>';
                            echo '<span style="font-size: 10px;">'.$today->format('d M').'</span>';
                            echo '<input type="hidden" value="'.$today->format('Y-m-d').'" />';
                            ?>
                        <input type="hidden" value="" />
                        <input type="hidden" value="" />
                        </button>
                        
                            <?php $today = new DateTime('now'); $today_dow = intval($today->format('N')); $dow = 4 + ($i * 7) + $pagination;
                            if($today_dow > $dow)
                            {
                                $date_interval = new DateInterval('P'.strval(($today_dow) - $dow).'D');
								$date_interval->invert = 1;
                                $today->add($date_interval);
								$background_color = 'background-color:#E74C3C;';
								$make_disable = 'disabled';
								$date_holder = $today->format('Y-m-d');
								$make_onclick = '';
								
								foreach($arr as $date){
								if($date == $date_holder){
								$background_color = 'background-color:#8181F7;';
								}
								}
								
                            }
                            else if($today_dow < $dow)
                            {
                                $date_interval = new DateInterval('P'.strval($dow - $today_dow).'D');
                                $today->add($date_interval);
                                $background_color = '';
								$make_disable = '';
								$date_holder = $today->format('Y-m-d');
								$make_onclick = "setDateSlot('$date_holder');";
								
								foreach($arr as $date){
								if($date == $date_holder){
								$background_color = 'background-color:#8181F7;';
								}
								}
								
                            }elseif($today_dow == $dow){
								$background_color = 'background-color:#48a25a;';
								$make_disable = '';
								$date_holder = $today->format('Y-m-d');
								$make_onclick = "setDateSlot('$date_holder');";
								
								foreach($arr as $date){
								if($date == $date_holder){
								$background_color = 'background-color:#8181F7;';
								}
								}
								
							}
							echo '<button onclick="'.$make_onclick.'" class="days_button" id="thur" lang="en" style="width:13%;'.$background_color.'" '.$make_disable.'">Thur<br/>';
                            echo '<span style="font-size: 10px;">'.$today->format('d M').'</span>';
                            echo '<input type="hidden" value="'.$today->format('Y-m-d').'" />';
                            ?>
                        <input type="hidden" value="" />
                        <input type="hidden" value="" />
                        </button>
                        
                            <?php $today = new DateTime('now'); $today_dow = intval($today->format('N')); $dow = 5 + ($i * 7) + $pagination;
                            if($today_dow > $dow)
                            {
                                $date_interval = new DateInterval('P'.strval(($today_dow) - $dow).'D');
								$date_interval->invert = 1;
                                $today->add($date_interval);
								$background_color = 'background-color:#E74C3C;';
								$make_disable = 'disabled';
								$date_holder = $today->format('Y-m-d');
								$make_onclick = '';
								
								foreach($arr as $date){
								if($date == $date_holder){
								$background_color = 'background-color:#8181F7;';
								}
								}
								
                            }
                            else if($today_dow < $dow)
                            {
                                $date_interval = new DateInterval('P'.strval($dow - $today_dow).'D');
                                $today->add($date_interval);
                                $background_color = '';
								$make_disable = '';
								$date_holder = $today->format('Y-m-d');
								$make_onclick = "setDateSlot('$date_holder');";
								
								foreach($arr as $date){
								if($date == $date_holder){
								$background_color = 'background-color:#8181F7;';
								}
								}
								
                            }elseif($today_dow == $dow){
								$background_color = 'background-color:#48a25a;';
								$make_disable = '';
								$date_holder = $today->format('Y-m-d');
								$make_onclick = "setDateSlot('$date_holder');";
								
								foreach($arr as $date){
								if($date == $date_holder){
								$background_color = 'background-color:#8181F7;';
								}
								}
								
							}
							echo '<button onclick="'.$make_onclick.'" class="days_button" id="fri" lang="en" style="width:13%;'.$background_color.'" '.$make_disable.'">Fri<br/>';
                            echo '<span style="font-size: 10px;">'.$today->format('d M').'</span>';
                            echo '<input type="hidden" value="'.$today->format('Y-m-d').'" />';
                            ?>
                        <input type="hidden" value="" />
                        <input type="hidden" value="" />
                        </button>
                        
                            <?php $today = new DateTime('now'); $today_dow = intval($today->format('N')); $dow = 6 + ($i * 7) + $pagination;
                            if($today_dow > $dow)
                            {
                                $date_interval = new DateInterval('P'.strval(($today_dow) - $dow).'D');
								$date_interval->invert = 1;
                                $today->add($date_interval);
								$background_color = 'background-color:#E74C3C;';
								$make_disable = 'disabled';
								$date_holder = $today->format('Y-m-d');
								$make_onclick = '';
								
								foreach($arr as $date){
								if($date == $date_holder){
								$background_color = 'background-color:#8181F7;';
								}
								}
								
                            }
                            else if($today_dow < $dow)
                            {
                                $date_interval = new DateInterval('P'.strval($dow - $today_dow).'D');
                                $today->add($date_interval);
                                $background_color = '';
								$make_disable = '';
								$date_holder = $today->format('Y-m-d');
								$make_onclick = "setDateSlot('$date_holder');";
								
								foreach($arr as $date){
								if($date == $date_holder){
								$background_color = 'background-color:#8181F7;';
								}
								}
								
                            }elseif($today_dow == $dow){
								$background_color = 'background-color:#48a25a;';
								$make_disable = '';
								$date_holder = $today->format('Y-m-d');
								$make_onclick = "setDateSlot('$date_holder');";
								
								foreach($arr as $date){
								if($date == $date_holder){
								$background_color = 'background-color:#8181F7;';
								}
								}
								
							}
							echo '<button onclick="'.$make_onclick.'" class="days_button" id="sat" style="width:13%;'.$background_color.'" '.$make_disable.'" lang="en">Sat<br/>';
                            echo '<span style="font-size: 10px;">'.$today->format('d M').'</span>';
                            echo '<input type="hidden" value="'.$today->format('Y-m-d').'" />';
							$todays_date = new DateTime('now');
							
							$result = $con->prepare("SELECT timezone FROM doctors where id=?");
							$result->bindValue(1, $MEDID, PDO::PARAM_INT);
							$result->execute();
							$row_timezone = $result->fetch(PDO::FETCH_ASSOC);
							
							$doc_timezone = $row_timezone['timezone'];
							$pull_increment = explode(":", $doc_timezone);

							$this_hour = $todays_date->format('H');
							$this_hour = $this_hour + $pull_increment[0];
							$rem_hour = $this_hour % 12;
							$am_pm = $this_hour / 12;
							if($am_pm >= 1){
							$rem_hour = $rem_hour."pm - ".($rem_hour+1)."pm";
							}else{
							$rem_hour = $rem_hour."am - ".($rem_hour+1)."am";
							}
                            ?>
                        <input type="hidden" value="" />
                        <input type="hidden" value="" />
                        </button>
                    </div>
					<?php } ?>
					
					</div>
					<div style="margin-left:59%;">
					<?php echo $today->format('Y'); ?>
					</div>