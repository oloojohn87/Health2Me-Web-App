<?php
//Telemed console class
//Getting really tired of copying and pasting this crap everywhere, so I will go ahead and build a modular class for this.
//Author: Kyle Austin

class chartClass{
	
	//PDO
	private $con;
	
	//Page
	public $page;
	
	//Ajax
	public $is_ajax;
	
	//Chart type for entirely different chart displays
	public $chart_type;
	
	//Data variables
	public $scope = "Global";
	public $period;
	public $group;
	public $interval;
	public $hourlyConsultationData = array();
	public $hourlyUserData = array();
	public $weeklyConsultationData = array();
	public $weeklyUserData = array();
	public $monthlyConsultationData = array();
	public $monthlyUserData = array();
	public $yearlyConsultationData = array();
	public $yearlyUserData = array();
	public $numUsers = 0;
	public $med_id;
	public $doctor_row;
	public $total_owed;

	public $distinctTodayUserCount;
	public $distinctWeekyUserCount;
	public $distinctMonthyUserCount;    
	public $distinctYearlyUserCount;
	
	public function __construct($ajax, $chart_type){
		require('../../environment_detailForLogin.php');
		$dbhost = $env_var_db['dbhost']; 
		$dbname = $env_var_db['dbname'];
		$dbuser = $env_var_db['dbuser'];
		$dbpass = $env_var_db['dbpass'];
		
		//SET DB CONNECTION/
		$this->dbhost = $dbhost;
		$this->dbname = $dbname;
		$this->dbuser = $dbuser;
		$this->dbpass = $dbpass;
		
		$this->con = new PDO('mysql:host='.$this->dbhost.';dbname='.$this->dbname.';charset=utf8', ''.$this->dbuser.'', ''.$this->dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
		if (!$this->con)
		{
			die('Could not connect: ' . mysql_error());
		}
		session_start();
		$this->med_id = $_SESSION['MEDID'];
		$this->chart_type = $chart_type;
		
		$query = $this->con->prepare("SELECT * FROM doctors WHERE id = ?");
		$query->bindValue(1, $this->med_id, PDO::PARAM_INT);
		$success = $query->execute();
		
		$this->doctor_row = $query->fetch(PDO::FETCH_ASSOC);
		
		$owed = $this->con->prepare("SELECT * FROM payments WHERE payout_id = ? && owner_type = 'member' && (service_type = 'probe' OR service_type = 'consultation') && paid = 0");
		$owed->bindValue(1, $this->med_id, PDO::PARAM_INT);
		$owed_success = $owed->execute();
		
		$this->total_owed = 0;
		
		while($row = $owed->fetch(PDO::FETCH_ASSOC)){
			if($row['service_type'] == 'probe'){
				$this->total_owed = $this->total_owed + (($row['months'] * $row['base_price']) / 100);
			}elseif($row['service_type'] == 'consultation'){
				$this->total_owed = $this->total_owed + ( $row['base_price'] / 100);
			}
		}
		
		if($ajax == 0){
			echo '<script type="text/javascript" src="../../js/jquery.min.js"></script>
				<script type="text/javascript" src="../../js/jquery-ui.min.js"></script>
				<link href="../../css/style.css" rel="stylesheet">
				<link href="../../css/bootstrap.css" rel="stylesheet">
				<link rel="stylesheet" href="../../css/jquery-ui-1.8.16.custom.css" media="screen"  />
                <link rel="stylesheet" href="../../css/icon/font-awesome.css">
				<link rel="stylesheet" href="../../font-awesome/css/font-awesome.min.css">
				<link rel="stylesheet" type="text/css" href="../../css/sweet-alert.css">
				<script type="text/javascript" src="../../js/sweet-alert.min.js"></script>
                <!--script src="../../js/iframeResizer.contentWindow.min.js"></script-->
                <style>
                .patient_row_button{
                    width: 31px; 
                    height: 33px;
                    padding-left: 9px;
                    padding-top: 5px; 
                    border-radius: 40px; 
                    margin-left: 25px;
                    float: left;
                    font-size: 24px;
                    background-color: #FFF;
                    border: 2px solid #CECECE;
                }
                td {
                    text-align:center;
                }
                </style>
                ';
				
				//HIDDEN INPUTS
				echo '<input type="hidden" id="scope_select" value="Global" />
				<input type="hidden" id="MEDID" value="'.$this->med_id.'" />
				<input type="hidden" id="doc-tracking-price" value="'.$this->doctor_row['tracking_price'].'" />
				<input type="hidden" id="doc-consult-price" value="'.$this->doctor_row['consult_price'].'" />';
		}
		
		$this->is_ajax = $ajax;
		
		if($ajax == 0){
			$this->display_html();
			$this->display_javascript();
		}
	}

	public function display_html(){
		if($this->is_ajax == 0 && $this->chart_type == 'complex'){
		echo '<!-- BEGIN TELEMED ACTIVITY CONSOLE-->
						 <link rel="stylesheet" href="../../css/bootstrap-responsive.css">
						 <link rel="stylesheet" href="../../css/toggle-switch.css">
						 <style>
							.segmented_control{
								width: 100px;
								height: 35px;
								background-color: #FBFBFB;
								color: #222;
								border: 1px solid #E6E6E6;
								outline: 0px;
								float: left;
								cursor: pointer;
							}
							.segmented_control_selected{
								width: 100px;
								height: 35px;
								background-color: #22AEFF;
								color: #FFF;
								border: 0px solid #E6E6E6;
								outline: 0px;
								float: left;
								cursor: default;
							}
						</style>
						
						 <div class="tab-pane" id="telemed_activity_config">
						 <div class="content">
					<div class="grid" class="grid span4" style="width:950px; height:1010px; margin: 0 auto; margin-top:30px; padding-top:10px;">
						
						<!-- ADD STUFF HERE -->
						<div style="background-color: #22AEFF; color: #FFF; padding: 12px; border-radius: 8px; margin: auto; text-align: center; font-size:30px; width: 94%;" lang="en">Financial Data Console</div>
					
						<div style="width: 400px; height: 170px; float: right; margin-right: 40px; margin-top: 30px;">
							<div style="width: 200px; height: 100px; float: left;">
								<h4 style="color: #8A8A8A; text-align: center; font-size: 32px;" lang="en">Inbound</h4> <!-- Added Lang by Pallab-->
								<p id="number_of_consultations" style="color: #22AEFF; font-size: 42px; font-weight: bold; text-align: center; margin-top: 25px;"></p>
							</div>
							<div style="width: 200px; height: 100px; float: left;">
								<h4 style="color: #8A8A8A; text-align: center; font-size: 32px;" lang="en">Outbound</h4> <!-- Added Lang by Pallab-->
								<p id="number_of_users" style="color: #5EB529; font-size: 42px; font-weight: bold; text-align: center; margin-top: 25px;"></p>

							</div>
							<div style="width: 200px; height: 35px; margin-top: 25px; float: right; margin-right: 20px;">
								<button id="toggle_1" class="segmented_control_selected" style="border-top-left-radius: 5px; border-bottom-left-radius: 5px;" lang="en">Actual</button>
								<button id="toggle_2" class="segmented_control" style="border-top-right-radius: 5px; border-bottom-right-radius: 5px;" lang="en">Cumulative</button>
							</div>
						</div>
						<div style="width: 500px; height: 100px; margin-left: 40px; margin-top: 30px;">

							<div style="width: 500px; height: 50px;">
								<label style="margin-right: 30px; float: left; margin-top: 5px;"><span lang="en">Period: </span></label> <!-- Added Lang by Pallab-->
								<select onchange="updatePeriod();" id="period_select2" style="float: left;">
									<option value="1" selected lang="en">Today</option> <!-- Added Lang by Pallab-->
									<option value="2" lang="en">This Week</option> <!-- Added Lang by Pallab-->
									<option value="3" lang="en">This Month</option> <!-- Added Lang by Pallab-->
									<option value="4" lang="en">This Year</option> <!-- Added Lang by Pallab-->
								</select>
							</div>
						</div>
						<div style="width: 210px; height: 30px; margin-left: 75px; margin-top: 45px; margin-bottom: -35px;">
							<style>
								.consultations_button{
									width: 100px;
									height: 30px;
									background-color: #FBFBFB;
									color: #222;
									border: 1px solid #E6E6E6;
									outline: 0px;
									float: left;
									cursor: pointer;
									border-radius: 5px;
								}
								.consultations_button_selected{
									width: 100px;
									height: 30px;
									background-color: #22AEFF;
									color: #FFF;
									border: 0px solid #E6E6E6;
									outline: 0px;
									float: left;
									cursor: pointer;
									border-radius: 5px;
								}
								.users_button{
									width: 100px;
									height: 30px;
									background-color: #FBFBFB;
									color: #222;
									border: 1px solid #E6E6E6;
									outline: 0px;
									float: right;
									cursor: pointer;
									border-radius: 5px;
								}
								.users_button_selected{
									width: 100px;
									height: 30px;
									background-color: #5EB529;
									color: #FFF;
									border: 0px solid #E6E6E6;
									outline: 0px;
									float: right;
									cursor: pointer;
									border-radius: 5px;
								}
							</style>
							<button class="consultations_button_selected" lang="en">Consultations</button> <!-- Added Lang by Pallab-->
						</div>
						<div style="width: 900px; height: 300px; margin-left: 40px; margin-top: 30px;">
							<canvas id="day_chart" width="900" height="300"></canvas>
							<canvas id="week_chart" width="900" height="300" style="display: none;"></canvas>
							<canvas id="month_chart" width="900" height="300" style="display: none;"></canvas>
							<canvas id="year_chart" width="900" height="300" style="display: none;"></canvas>
						</div>
						<div id="chart_loader" style="width: 52px; height: 42px; margin-left: auto; margin-right: auto; margin-top: -170px; margin-bottom: 208px; display: none;">
							<img src="../../images/load/29.gif"  alt="">
						</div>
						<style>
							.search_bar_button{
								width: 70px;
								height: 30px;
								background-color: #F6F6F6;
								outline: 0px;
								border: 1px solid #E7E7E7;
								color: #3A3A3A;
								border-top-right-radius: 5px;
								border-bottom-right-radius: 5px;
							}
						</style>
						<div class="controls" style="float: right; width: 500px; margin-right: 50px; margin-top: 50px;">
							<input lang="en" placeholder="name or surname or email" class="span7" id="search_bar" style="margin-left:50px;float: left; width: 370px; border-top-right-radius: 0px; border-bottom-right-radius: 0px;" size="16" type="text">
							<button id="search_bar_clear_button" style="height: 18px; width: 18px; border-radius: 15px; border: 0px solid #FFF; outline: 0px; background-color: #E6E6E6; color: #FFF; font-size: 10px; padding: 0px; margin-top: 6px; margin-left: -105px; visibility: hidden;">X</button>
							<button class="search_bar_button" style="float: left; width: 80px;" id="search_bar_button" lang="en">Search</button>
						</div>
						<div style="width: 400px; height: 35px; margin-left: 50px; margin-top: 80px;margin-right">
						</div>
						<div id="customers_table" style="width: 900px; margin: auto; margin-top: 15px;display:none;">
							<table>
								<tr>
									<th style="background-color: #6ECCFF; width: 225px; height: 25px;" lang="en">
										Name <!-- Added Lang by Pallab-->
										<button id="caret_button_customers_Surname,Name" class="icon-caret-down" style="margin-left: -2px; border: 0px solid #FFF; background-color: inherit; outline: 0px; padding: 0px;"></button>
									</th>
									<th style="background-color: #22AEFF; width: 225px; height: 25px;" lang="en">
										Calls <!-- Added Lang by Pallab-->
										<button id="caret_button_customers_numberOfPhoneCalls" class="icon-caret-down" style="margin-left: -2px; border: 0px solid #FFF; background-color: inherit; outline: 0px; padding: 0px;"></button>
									</th>
									<!--<th style="background-color: #22AEFF; width: 180px; height: 25px;">
										Video Conferences
										<button id="caret_button_customers_videoconferences" class="icon-caret-down" style="margin-left: 7px; border: 0px solid #FFF; background-color: inherit; outline: 0px;"></button>
									</th>-->
									<th style="background-color: #22AEFF; width: 225px; height: 25px;">
										<span lang="en">Sign Up Date</span> <!-- Added Lang by Pallab-->
										<button id="caret_button_customers_SignUpDate" class="icon-caret-down" style="margin-left: -2px; border: 0px solid #FFF; background-color: inherit; outline: 0px; padding: 0px;"></button>
									</th>
									<th style="background-color: #22AEFF; width: 225px; height: 25px;"><span lang="en">Plan </span>
										<!-- Added Lang by Pallab-->
										<button id="caret_button_customers_typeOfPlan" class="icon-caret-down" style="margin-left: -2px; border: 0px solid #FFF; background-color: inherit; outline: 0px; padding: 0px;"></button>
									</th>
								</tr>
								<tr>
									<td style="background-color: #F5F5F5; width: 225px; height: 15px;"></td>
									<td style="background-color: #F5F5F5; width: 225px; height: 15px;"></td>
									<td style="background-color: #F5F5F5; width: 225px; height: 15px;"></td>
									<td style="background-color: #F5F5F5; width: 225px; height: 15px;"></td>
								</tr>
								<tr>
									<td style="background-color: #E9E9E9; width: 225px; height: 15px;"></td>
									<td style="background-color: #E9E9E9; width: 225px; height: 15px;"></td>
									<td style="background-color: #E9E9E9; width: 225px; height: 15px;"></td>
									<td style="background-color: #E9E9E9; width: 225px; height: 15px;"></td>
								</tr>
								<tr>
									<td style="background-color: #F5F5F5; width: 225px; height: 15px;"></td>
									<td style="background-color: #F5F5F5; width: 225px; height: 15px;"></td>
									<td style="background-color: #F5F5F5; width: 225px; height: 15px;"></td>
									<td style="background-color: #F5F5F5; width: 225px; height: 15px;"></td>
								</tr>
								<tr>
									<td style="background-color: #E9E9E9; width: 225px; height: 15px;"></td>
									<td style="background-color: #E9E9E9; width: 225px; height: 15px;"></td>
									<td style="background-color: #E9E9E9; width: 225px; height: 15px;"></td>
									<td style="background-color: #E9E9E9; width: 225px; height: 15px;"></td>
								</tr>
								<tr>
									<td style="background-color: #F5F5F5; width: 225px; height: 15px;"></td>
									<td style="background-color: #F5F5F5; width: 225px; height: 15px;"></td>
									<td style="background-color: #F5F5F5; width: 225px; height: 15px;"></td>
									<td style="background-color: #F5F5F5; width: 225px; height: 15px;"></td>
								</tr>
								<tr>
									<td style="background-color: #E9E9E9; width: 225px; height: 15px;"></td>
									<td style="background-color: #E9E9E9; width: 225px; height: 15px;"></td>
									<td style="background-color: #E9E9E9; width: 225px; height: 15px;"></td>
									<td style="background-color: #E9E9E9; width: 225px; height: 15px;"></td>
								</tr>
							</table>
						</div>
						<div id="consultations_table" style="width: 900px; margin: auto; margin-top: -15px; display: none;">
							<table>
								<tr>
									<th style="background-color: #22AEFF; width: 155px; height: 25px;" lang="en">
										Doctor <!-- Added Lang by Pallab-->
										<button id="caret_button_consultations_doctorSurname,doctorName" class="icon-caret-down" style="margin-left: -2px; border: 0px solid #FFF; background-color: inherit; outline: 0px; padding: 0px;"></button>
									</th>
									<th style="background-color: #22AEFF; width: 155px; height: 25px;" lang="en">
										Patient <!-- Added Lang by Pallab-->
										<button id="caret_button_consultations_patientSurname,patientName" class="icon-caret-down" style="margin-left: -2px; border: 0px solid #FFF; background-color: inherit; outline: 0px; padding: 0px;"></button>
									</th>
									<th style="background-color: #22AEFF; width: 180px; height: 25px;" lang="en">
										Time
										<button id="caret_button_consultations_DateTime" class="icon-caret-down" style="margin-left: -2px; border: 0px solid #FFF; background-color: inherit; outline: 0px; padding: 0px;"></button>
									</th>
									<th style="background-color: #22AEFF; width: 50px; height: 25px;" lang="en">
										Type
										<button id="caret_button_consultations_Type" class="icon-caret-down" style="margin-left: -2px; border: 0px solid #FFF; background-color: inherit; outline: 0px; padding: 0px;"></button>
									</th>
									<th style="background-color: #22AEFF; width: 50px; height: 25px;" lang="en">
										Length
										<button id="caret_button_consultations_Length" class="icon-caret-down" style="margin-left: -2px; border: 0px solid #FFF; background-color: inherit; outline: 0px; padding: 0px;"></button>
									</th>
									<th style="background-color: #22AEFF; width: 100px; height: 25px;" lang="en">
										Status
										<button id="caret_button_consultations_Status" class="icon-caret-down" style="margin-left: -2px; border: 0px solid #FFF; background-color: inherit; outline: 0px; padding: 0px;"></button>
									</th>
									<th style="background-color: #22AEFF; width: 70px; height: 25px;" lang="en">
										Notes
										<!--<button id="caret_button_consultations_Data_File" class="icon-caret-down" style="margin-left: -2px; border: 0px solid #FFF; background-color: inherit; outline: 0px; padding: 0px; "></button>-->
									</th>
									<th style="background-color: #22AEFF; width: 70px; height: 25px;" lang="en">
										Summ.
										<!--<button id="caret_button_consultations_Summary_PDF" class="icon-caret-down" style="margin-left: -2px; border: 0px solid #FFF; background-color: inherit; outline: 0px; padding: 0px;"></button>-->
									</th>
									<th style="background-color: #22AEFF; width: 70px; height: 25px;" lang="en">
										Rec.
										<!--<button id="caret_button_consultations_Recorded_File" class="icon-caret-down" style="margin-left: -2px; border: 0px solid #FFF; background-color: inherit; outline: 0px; padding: 0px;"></button>-->
									</th>
								</tr>
								<tr>
									<td style="background-color: #F5F5F5; width: 130px; height: 15px;"></td>
									<td style="background-color: #F5F5F5; width: 130px; height: 15px;"></td>
									<td style="background-color: #F5F5F5; width: 130px; height: 15px;"></td>
									<td style="background-color: #F5F5F5; width: 130px; height: 15px;"></td>
									<td style="background-color: #F5F5F5; width: 130px; height: 15px;"></td>
									<td style="background-color: #F5F5F5; width: 130px; height: 15px;"></td>
									<td style="background-color: #F5F5F5; width: 40px; height: 15px;"></td>
									<td style="background-color: #F5F5F5; width: 40px; height: 15px;"></td>
									<td style="background-color: #F5F5F5; width: 40px; height: 15px;"></td>
								</tr>
								<tr>
									<td style="background-color: #E9E9E9; width: 130px; height: 15px;"></td>
									<td style="background-color: #E9E9E9; width: 130px; height: 15px;"></td>
									<td style="background-color: #E9E9E9; width: 130px; height: 15px;"></td>
									<td style="background-color: #E9E9E9; width: 130px; height: 15px;"></td>
									<td style="background-color: #E9E9E9; width: 130px; height: 15px;"></td>
									<td style="background-color: #E9E9E9; width: 130px; height: 15px;"></td>
									<td style="background-color: #E9E9E9; width: 40px; height: 15px;"></td>
									<td style="background-color: #E9E9E9; width: 40px; height: 15px;"></td>
									<td style="background-color: #E9E9E9; width: 40px; height: 15px;"></td>
								</tr>
								<tr>
									<td style="background-color: #F5F5F5; width: 130px; height: 15px;"></td>
									<td style="background-color: #F5F5F5; width: 130px; height: 15px;"></td>
									<td style="background-color: #F5F5F5; width: 130px; height: 15px;"></td>
									<td style="background-color: #F5F5F5; width: 130px; height: 15px;"></td>
									<td style="background-color: #F5F5F5; width: 130px; height: 15px;"></td>
									<td style="background-color: #F5F5F5; width: 130px; height: 15px;"></td>
									<td style="background-color: #F5F5F5; width: 40px; height: 15px;"></td>
									<td style="background-color: #F5F5F5; width: 40px; height: 15px;"></td>
									<td style="background-color: #F5F5F5; width: 40px; height: 15px;"></td>
								</tr>
								<tr>
									<td style="background-color: #E9E9E9; width: 130px; height: 15px;"></td>
									<td style="background-color: #E9E9E9; width: 130px; height: 15px;"></td>
									<td style="background-color: #E9E9E9; width: 130px; height: 15px;"></td>
									<td style="background-color: #E9E9E9; width: 130px; height: 15px;"></td>
									<td style="background-color: #E9E9E9; width: 130px; height: 15px;"></td>
									<td style="background-color: #E9E9E9; width: 130px; height: 15px;"></td>
									<td style="background-color: #E9E9E9; width: 40px; height: 15px;"></td>
									<td style="background-color: #E9E9E9; width: 40px; height: 15px;"></td>
									<td style="background-color: #E9E9E9; width: 40px; height: 15px;"></td>
								</tr>
								<tr>
									<td style="background-color: #F5F5F5; width: 130px; height: 15px;"></td>
									<td style="background-color: #F5F5F5; width: 130px; height: 15px;"></td>
									<td style="background-color: #F5F5F5; width: 130px; height: 15px;"></td>
									<td style="background-color: #F5F5F5; width: 130px; height: 15px;"></td>
									<td style="background-color: #F5F5F5; width: 130px; height: 15px;"></td>
									<td style="background-color: #F5F5F5; width: 130px; height: 15px;"></td>
									<td style="background-color: #F5F5F5; width: 40px; height: 15px;"></td>
									<td style="background-color: #F5F5F5; width: 40px; height: 15px;"></td>
									<td style="background-color: #F5F5F5; width: 40px; height: 15px;"></td>
								</tr>
								<tr>
									<td style="background-color: #E9E9E9; width: 130px; height: 15px;"></td>
									<td style="background-color: #E9E9E9; width: 130px; height: 15px;"></td>
									<td style="background-color: #E9E9E9; width: 130px; height: 15px;"></td>
									<td style="background-color: #E9E9E9; width: 130px; height: 15px;"></td>
									<td style="background-color: #E9E9E9; width: 130px; height: 15px;"></td>
									<td style="background-color: #E9E9E9; width: 130px; height: 15px;"></td>
									<td style="background-color: #E9E9E9; width: 40px; height: 15px;"></td>
									<td style="background-color: #E9E9E9; width: 40px; height: 15px;"></td>
									<td style="background-color: #E9E9E9; width: 40px; height: 15px;"></td>
								</tr>
							</table>
						</div>
						<div id="doctors_table" style="width: 1000px; margin: auto; margin-top: 15px; display: none;">
							<table>
								<tr>
									<th style="background-color: #22AEFF; width: 225px; height: 25px;" lang="en">
										Name
										<button id="caret_button_doctors_name" class="icon-caret-down" style="margin-left: -2px; border: 0px solid #FFF; background-color: inherit; outline: 0px; padding: 0px;"></button>
									</th>
									<th style="background-color: #22AEFF; width: 225px; height: 25px;" lang="en">
										Calls
										<button id="caret_button_doctors_calls" class="icon-caret-down" style="margin-left: -2px; border: 0px solid #FFF; background-color: inherit; outline: 0px; padding: 0px;"></button>
									</th>
									<!--<th style="background-color: #22AEFF; width: 180px; height: 25px;">
										Video Conferences
										<button id="caret_button_doctors_cideoconferences" class="icon-caret-down" style="margin-left: 7px; border: 0px solid #FFF; background-color: inherit; outline: 0px;"></button>
									</th>-->
									<th style="background-color: #22AEFF; width: 225px; height: 25px;" lang="en">
										Patients
										<button id="caret_button_doctors_numberOfConsultedPatients" class="icon-caret-down" style="margin-left: -2px; border: 0px solid #FFF; background-color: inherit; outline: 0px; padding: 0px;"></button>
									</th>
									<!--<th style="background-color: #22AEFF; width: 150px; height: 25px;">
										Summaries Edited
										<button id="caret_button_doctors_summaries" class="icon-caret-down" style="margin-left: 7px; border: 0px solid #FFF; background-color: inherit; outline: 0px;"></button>
									</th>-->
									<th style="background-color: #22AEFF; width: 225px; height: 25px;"><span  lang="en">PDFs Created</span>
										
										<button id="caret_button_doctors_reportsCreated" class="icon-caret-down" style="margin-left: -2px; border: 0px solid #FFF; background-color: inherit; outline: 0px; padding: 0px;"></button>
									</th>
								</tr>
								<tr>
									<td style="background-color: #F5F5F5; width: 225px; height: 15px;"></td>
									<td style="background-color: #F5F5F5; width: 225px; height: 15px;"></td>
									<td style="background-color: #F5F5F5; width: 225px; height: 15px;"></td>
									<td style="background-color: #F5F5F5; width: 225px; height: 15px;"></td>
								</tr>
								<tr>
									<td style="background-color: #E9E9E9; width: 225px; height: 15px;"></td>
									<td style="background-color: #E9E9E9; width: 225px; height: 15px;"></td>
									<td style="background-color: #E9E9E9; width: 225px; height: 15px;"></td>
									<td style="background-color: #E9E9E9; width: 225px; height: 15px;"></td>
								</tr>
								<tr>
									<td style="background-color: #F5F5F5; width: 225px; height: 15px;"></td>
									<td style="background-color: #F5F5F5; width: 225px; height: 15px;"></td>
									<td style="background-color: #F5F5F5; width: 225px; height: 15px;"></td>
									<td style="background-color: #F5F5F5; width: 225px; height: 15px;"></td>
								</tr>
								<tr>
									<td style="background-color: #E9E9E9; width: 225px; height: 15px;"></td>
									<td style="background-color: #E9E9E9; width: 225px; height: 15px;"></td>
									<td style="background-color: #E9E9E9; width: 225px; height: 15px;"></td>
									<td style="background-color: #E9E9E9; width: 225px; height: 15px;"></td>
								</tr>
								<tr>
									<td style="background-color: #F5F5F5; width: 225px; height: 15px;"></td>
									<td style="background-color: #F5F5F5; width: 225px; height: 15px;"></td>
									<td style="background-color: #F5F5F5; width: 225px; height: 15px;"></td>
									<td style="background-color: #F5F5F5; width: 225px; height: 15px;"></td>
								</tr>
								<tr>
									<td style="background-color: #E9E9E9; width: 225px; height: 15px;"></td>
									<td style="background-color: #E9E9E9; width: 225px; height: 15px;"></td>
									<td style="background-color: #E9E9E9; width: 225px; height: 15px;"></td>
									<td style="background-color: #E9E9E9; width: 225px; height: 15px;"></td>
								</tr>
								<tr>
									<td style="background-color: #F5F5F5; width: 225px; height: 15px;"></td>
									<td style="background-color: #F5F5F5; width: 225px; height: 15px;"></td>
									<td style="background-color: #F5F5F5; width: 225px; height: 15px;"></td>
									<td style="background-color: #F5F5F5; width: 225px; height: 15px;"></td>
								</tr>
								<tr>
									<td style="background-color: #E9E9E9; width: 225px; height: 15px;"></td>
									<td style="background-color: #E9E9E9; width: 225px; height: 15px;"></td>
									<td style="background-color: #E9E9E9; width: 225px; height: 15px;"></td>
									<td style="background-color: #E9E9E9; width: 225px; height: 15px;"></td>
								</tr>
							</table>
							
							
						</div>
						<div id="newusers_table" style="width: 900px; margin: auto; margin-top: 15px; display: none;">
							<table>
								<tr>
									<th style="background-color: #22AEFF; width: 225px; height: 25px;" lang="en">
										Name
										<button id="caret_button_newusers_name" class="icon-caret-down" style="margin-left: -2px; border: 0px solid #FFF; background-color: inherit; outline: 0px; padding: 0px;"></button>
									</th>
									<th style="background-color: #22AEFF; width: 225px; height: 25px;" lang="en">
										Phone
										<button id="caret_button_newusers2_telefono" class="icon-caret-down" style="margin-left: -2px; border: 0px solid #FFF; background-color: inherit; outline: 0px; padding: 0px;"></button>
									</th>
									<!--<th style="background-color: #22AEFF; width: 180px; height: 25px;">
										Video Conferences
										<button id="caret_button_doctors_cideoconferences" class="icon-caret-down" style="margin-left: 7px; border: 0px solid #FFF; background-color: inherit; outline: 0px;"></button>
									</th>-->
									<th style="background-color: #22AEFF; width: 225px; height: 25px;" lang="en">
										Email
										<button id="caret_button_doctorsnewusers3_email" class="icon-caret-down" style="margin-left: -2px; border: 0px solid #FFF; background-color: inherit; outline: 0px; padding: 0px;"></button>
									</th>
									<!--<th style="background-color: #22AEFF; width: 150px; height: 25px;">
										Summaries Edited
										<button id="caret_button_doctors_summaries" class="icon-caret-down" style="margin-left: 7px; border: 0px solid #FFF; background-color: inherit; outline: 0px;"></button>
									</th>-->
									<th style="background-color: #22AEFF; width: 225px; height: 25px;"><span lang="en">Creation Date</span>
										
										<button id="caret_button_doctorsnewusers4_signupdate" class="icon-caret-down" style="margin-left: -2px; border: 0px solid #FFF; background-color: inherit; outline: 0px; padding: 0px;"></button>
									</th>
								</tr>
								<tr>
									<td style="background-color: #F5F5F5; width: 225px; height: 15px;"></td>
									<td style="background-color: #F5F5F5; width: 225px; height: 15px;"></td>
									<td style="background-color: #F5F5F5; width: 225px; height: 15px;"></td>
									<td style="background-color: #F5F5F5; width: 225px; height: 15px;"></td>
								</tr>
								<tr>
									<td style="background-color: #E9E9E9; width: 225px; height: 15px;"></td>
									<td style="background-color: #E9E9E9; width: 225px; height: 15px;"></td>
									<td style="background-color: #E9E9E9; width: 225px; height: 15px;"></td>
									<td style="background-color: #E9E9E9; width: 225px; height: 15px;"></td>
								</tr>
								<tr>
									<td style="background-color: #F5F5F5; width: 225px; height: 15px;"></td>
									<td style="background-color: #F5F5F5; width: 225px; height: 15px;"></td>
									<td style="background-color: #F5F5F5; width: 225px; height: 15px;"></td>
									<td style="background-color: #F5F5F5; width: 225px; height: 15px;"></td>
								</tr>
								<tr>
									<td style="background-color: #E9E9E9; width: 225px; height: 15px;"></td>
									<td style="background-color: #E9E9E9; width: 225px; height: 15px;"></td>
									<td style="background-color: #E9E9E9; width: 225px; height: 15px;"></td>
									<td style="background-color: #E9E9E9; width: 225px; height: 15px;"></td>
								</tr>
								<tr>
									<td style="background-color: #F5F5F5; width: 225px; height: 15px;"></td>
									<td style="background-color: #F5F5F5; width: 225px; height: 15px;"></td>
									<td style="background-color: #F5F5F5; width: 225px; height: 15px;"></td>
									<td style="background-color: #F5F5F5; width: 225px; height: 15px;"></td>
								</tr>
								<tr>
									<td style="background-color: #E9E9E9; width: 225px; height: 15px;"></td>
									<td style="background-color: #E9E9E9; width: 225px; height: 15px;"></td>
									<td style="background-color: #E9E9E9; width: 225px; height: 15px;"></td>
									<td style="background-color: #E9E9E9; width: 225px; height: 15px;"></td>
								</tr>
								<tr>
									<td style="background-color: #F5F5F5; width: 225px; height: 15px;"></td>
									<td style="background-color: #F5F5F5; width: 225px; height: 15px;"></td>
									<td style="background-color: #F5F5F5; width: 225px; height: 15px;"></td>
									<td style="background-color: #F5F5F5; width: 225px; height: 15px;"></td>
								</tr>
								<tr>
									<td style="background-color: #E9E9E9; width: 225px; height: 15px;"></td>
									<td style="background-color: #E9E9E9; width: 225px; height: 15px;"></td>
									<td style="background-color: #E9E9E9; width: 225px; height: 15px;"></td>
									<td style="background-color: #E9E9E9; width: 225px; height: 15px;"></td>
								</tr>
							</table>
							
							
						</div>
						<div id="table_loader" style="width: 220px; height: 19px; margin-left: auto; margin-right: auto; margin-top: 40px; margin-bottom: 40px; display: none;">
							<img src="../../images/load/8.gif"  alt="">
						</div>
						<div style="width: 120px; height: 50px; margin-top: 25px; margin-left: auto; margin-right: auto;">
							<style>
								.page_button{
									width: 30px; 
									height: 30px; 
									border-radius: 20px; 
									outline: 0px; 
									background-color: #22AEFF; 
									color: #FFF; 
									border: 0px solid #FFF; 
									float: left;
								}
								.page_button:disabled{
									background-color: #6ECCFF;
									cursor: default;
								}
							</style>
							
							<button id="page_button_left" class="page_button" disabled>
								<i class="icon-arrow-left"></i>
							</button>
							<div id="page_label" style="width: 60px; height: 30px; float: left; margin-top: 5px; text-align: center;">
								1 of 1
							</div>
							<button id="page_button_right" class="page_button" disabled>
								<i class="icon-arrow-right"></i>
							</button>
							
						</div>
						<style>
							#medical_availability_container{
								width: 100%;
							}
							.medical_availability_row{
								width: 100%;
								height: 30px;
								margin-bottom: 25px;
							}
							.medical_availability_row_name{
								width: 13%;
								padding-left: 2%;
								height: 25px;
								padding-top: 5px;
								float: left;
							}
							.medical_availability_row_timeslots{
								width: 83%;
								padding-left: 2%;
								height: 25px;
								padding-top: 5px;
								float: left;
							}
							.timeCellIndicator{
								border-style: solid; 
								border-width: 1px;
								border-color: #BABABA;
								height: 15px; 
								width: 32px;
							}
							.timeCellIndicatorOff{
								height: 15px; 
								width: 8px;
								margin-top: -1px;
								float: left;
							}
							.timeCellIndicatorOn{
								background-color: #3d94f6;
								height: 15px;
								width: 8px;
								float: left;
							}
							
							.timeCellIndicatorTempOn{
								background-color: #3d94f6;
								height: 15px;
								width: 8px;
								float: left;
							}
							
							.timeCellIndicatorMarked{
								background-color: #A5A5A5;
								border-style: solid; 
								border-width: 1px;
								border-color: #B2B2B2;
								border-right-color: #FFFFFF;
								height: 15px;
								margin-top: -1px;
								width: 32px;
							}
							.timeLabel{
								color: #CACACA; 
								font-size: 10px; 
								margin-left: -10px;
							}
							.timeCell{
								height: 40px; 
								width: 32px; 
								float: left;
								margin-left: 1px;
							}
							#medical_availability_show_doctors{
							   float: right; 
								width: 150px; 
								height: 42px; <!-- Changed height from 30 to 42 -->
								margin-right: 40px; 
								margin-top: -95px; 
								background-color: #F6F6F6; 
								border: 1px solid #999; 
								outline: none; 
								border-radius: 5px; 
								color: #333;
							}
							#medical_availability_show_doctors:hover{
								background-color: #22AEFF;
								color: #FFF;
								border: 1px solid #22AEFF; 
							}
							#launch_telemedicine_button{
								width: 150px; 
								height: 30px; 
								margin: auto; 
								background-color: #22AEFF; 
								border: 1px solid #22AEFF; 
								outline: none; 
								border-radius: 5px; 
								color: #FFF;
								font-size: 20px;
							}
							#launch_telemedicine_button:disabled{
								background-color: #EEE; 
								border: 1px solid #EEE;
								cursor: default;
							}
                            
						</style>
						
						</div>
					</div>
				</div>


			
						 <!-- END TELEMED ACTIVITY CONSOLE-->';
		}
		
		if($this->is_ajax == 0 && $this->chart_type == 'simple'){
			echo '<div class="grid" style="width:950px; overflow:auto; margin: 0 auto; border:none;">
                <div style="background-color: #22AEFF; color: #FFF; padding: 12px; border-radius: 8px; margin: auto; text-align: center; font-size:30px; width: 94%;" lang="en">Activity/Billing</div>
				<div style="margin-top:50px;">
					<span style="float:left; margin-left:25px;">Period: </span>
					<select id="period_select2" style="float: left;margin-left:15px;">
						<option value="1" selected="" lang="en">Today</option>
						<option value="2" lang="en">This Week</option>
						<option value="3" lang="en">This Month</option>
						<option value="4" lang="en">This Year</option>
					</select>
                </div>
                <div style="float:right; margin-right:30px;">
					<span style="margin-right:75px;"><span>Balance Owed: $</span><span style="color:#8e8e8e;">'.$this->total_owed.'</span></span>
					<span>Credits: </span>$<span style="color:#8e8e8e;">'.(number_format((float)($this->doctor_row['credits'] / 100), 2, '.', '')).'</span>
                </div>
                </br>
                <div style="margin-top:50px;width:100%; text-align:center;">
                    <div role="tabpannel" style="display:inline-block; width:95%;">
                        <!--nav tabs-->
                        <ul class="nav nav-tabs" role="tablist">
                            <li role="presentation" class="active"><a href="#inbound" aria-controls="inbound" role="tab" data-toggle="tab">Inbound</a></li>
                            <li role="presentation"><a href="#outbound" aria-controls="outbound" role="tab" data-toggle="tab">Outbound</a></li>
                        </ul>
                        
                        <!--tab panes-->
                        <div class="tab-content">
                            <div class="tab-pane active" id="inbound" role="tabpannel">
                            <center style="margin-bottom: 20px;"><span style="font-size:150%;color:#54bc00;">Inbound:</span><span id="inbound-display-counter" style="margin-left:50px;font-size:150%;color:#54bc00;"></span>
                            <span style="font-size:150%;color:#54bc00; margin-left:50px;">Revenue:</span><span id="inbound-display-revenue" style="margin-left:50px;font-size:150%;color:#54bc00;"></span>
                            </center>
                                <table style="border-radius: 10px; border: 2px solid #5EB529;" id="table-inbound" class="display" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Date/Time</th>
                                            <th>Type(<span style="font-size:60%;">Consultation/Tracking</span>)</th>
                                            <th>Price</th>
                                            <th>Revenue</th>
                                        </tr>
                                    </thead>

                                    <!--tfoot>
                                        <tr>
                                            <th>Date/Time</th>
                                            <th>Type(<span style="font-size:60%;">Consultation/Tracking</span>)</th>
                                            <th>Price</th>
                                        </tr>
                                    </tfoot-->
                                </table>
                            </div>

                            <div class="tab-pane" id="outbound" role="tabpannel">
                            <center style="margin-bottom: 20px;"><span style="font-size:150%;color:#22aeff;">Outbound:</span><span id="outbound-display-counter" style="margin-left:50px;color:#22aeff;font-size:150%;"></span></center>
                                <table style="border-radius: 10px; border: 2px solid #22AEFF;" id="table-outbound" class="display" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Date/Time</th>
                                            <th>Type(<span style="font-size:60%;">Consultation/Tracking</span>)</th>
                                            <th>Price</th>
                                        </tr>
                                    </thead>

                                    <!--tfoot>
                                        <tr>
                                            <th>Date/Time</th>
                                            <th>Type(<span style="font-size:60%;">Consultation/Tracking</span>)</th>
                                            <th>Price</th>
                                        </tr>
                                    </tfoot-->
                                </table>
                            </div>
                        </div>
                        <hr style="border-top: 1px solid #cacaca; margin-top:30px;"/>
                    </div>
                </div>
                <div style="background-color: #22AEFF; color: #FFF; padding: 12px; border-radius: 8px; text-align: center; font-size:30px; width: 94%; margin: 40px 0 0 15px; display: inline-block;" lang="en">Pricing Structure</div>
                <div style="width:100%; text-align:center;">
                    <div style="width:40%;margin-top:25px;margin-left:80px; display:inline-block; text-align:left;">
                        <span class="patient_row_button" style="color:#22AEFF; margin-right:10px;">
                            <i class="icon-signal" style="margin-left: -1px;"></i>
                        </span>
                        <div style="margin-top:10px; font-weight:bold;">Health Tracking</div>
                        <div style="margin-top:20px; color: #8e8e8e;"><span>My Price: </span>
                            <input id="my-price-tracking" style="width:175px;height:30px;" type="text" value="'.(number_format((float)($this->doctor_row['tracking_price'] / 100), 2, '.', '')).'" placeholder="Enter Your Tracking Price"/><i onclick="saveTrackingPrice();" style="padding:5px;color:#54bc00; font-size:24px;" class="icon-save"></i>
                        </div>
                        <div style="margin-top:12px; color: #8e8e8e;"><span>Final Patient Price: </span>$<span id="display-tracking-total">18.50</span></div>
                    </div>
                    <div style="width:40%;margin-top:25px; margin-left:3%; display:inline-block; text-align:left;">
                        <span class="patient_row_button" style="color:#5Eb529; margin-right:10px;">
                            <i class="icon-facetime-video" style="margin: 2px 0 0 -1px;"></i>
                        </span>
                        <div style="margin:10px; font-weight:bold;">Teleconsultation</div>
                        <div style="margin-top:20px; color: #8e8e8e;"><span>My Price: </span>
                            <input id="my-price-consultation" style="width:175px;height:30px;" type="text" value="'.(number_format((float)($this->doctor_row['consult_price'] / 100), 2, '.', '')).'" placeholder="Your Consultation Price"/><i onclick="saveConsultPrice();" style="padding:5px;color:#22aeff; font-size:24px;" class="icon-save"></i>
                        </div>
                        <div style="margin-top:12px; color: #8e8e8e;"><span>Final Patient Price: </span>$<span id="display-consult-total">28.90</span></div>
                    </div>
                </div>
            </div>';
		}
	}
	
	public function display_javascript(){
		if($this->is_ajax == 0 && $this->chart_type == 'complex'){
		echo '<script type="text/javascript" src="js/Chart.min.js"></script>
		<script type="text/javascript" src="js/bootstrap.min.js"></script>
		<script type="text/javascript" src="js/bootstrap-popover.js"></script>
		<script type="text/javascript" src="js/init_chart.js"></script>
		<script type="text/javascript" src="js/load_table_init.js"></script>
		<script type="text/javascript" src="js/reload_chart_init.js"></script>
		<script type="text/javascript" src="js/get_main_data_init.js"></script>
		<script type="text/javascript" src="js/init.js"></script>';
		
		}elseif($this->is_ajax == 0 && $this->chart_type == 'simple'){
		echo '<script type="text/javascript" src="../../js/bootstrap.min.js"></script>
		<script type="text/javascript" src="../../MODULES/Financial_Console/js/dataTables.min.js"></script>
		<script type="text/javascript" src="../../MODULES/Financial_Console/js/reloadTable.js"></script>
		<link rel="stylesheet" href="../../MODULES/Financial_Console/css/dataTables.min.css">
        <link rel="stylesheet" href="../../MODULES/Financial_Console/css/tableStyle.css">
		
		<script type="text/javascript">
		$(document).ready(function() {
            
			$("#period_select2").css({"background-color": "white", "border": "1px solid #cacaca", "width": "125px", "height":"25px", "border-radius": "5px" });
		
			var period = $("#period_select2").val();
			calcTotals();
			
			$("#period_select2").change(function(){
				period = $("#period_select2").val();
				inbound.fnReloadAjax( "chartClassUnit.php?ajax=grab_financial_data&medid='.$this->med_id.'&type=inbound&period="+period );
				outbound.fnReloadAjax( "chartClassUnit.php?ajax=grab_financial_data&medid='.$this->med_id.'&type=outbound&period="+period );
				calcTotals();
			});
			
			function calcTotals(){
				period = $("#period_select2").val();
				
				$.post("chartClassUnit.php?ajax=grabTotals&medid='.$this->med_id.'&type=outbound&period="+period, {medid: '.$this->med_id.', period: period, type: \'outbound\'})
					.done(function(data){
						obj = JSON.parse(data);
						console.log(obj);
						$("#outbound-display-counter").text("$"+obj.doc_total);
					});
					
				$.post("chartClassUnit.php?ajax=grabTotals&medid='.$this->med_id.'&type=inbound&period="+period, {medid: '.$this->med_id.', period: period, type: \'inbound\'})
					.done(function(data){
						obj = JSON.parse(data);
						console.log(obj);
						$("#inbound-display-counter").text("$"+obj.total);
                        $("#inbound-display-revenue").text("$"+obj.doc_total);
					});
			}
			
			var inbound = $("#table-inbound").dataTable("chartClassUnit.php?ajax=grab_financial_data&medid='.$this->med_id.'&type=inbound&period="+period);
				
			var outbound = $("#table-outbound").dataTable("chartClassUnit.php?ajax=grab_financial_data&medid='.$this->med_id.'&type=outbound&period="+period);
			
			inbound.fnReloadAjax( "chartClassUnit.php?ajax=grab_financial_data&medid='.$this->med_id.'&type=inbound&period=1" );
			outbound.fnReloadAjax( "chartClassUnit.php?ajax=grab_financial_data&medid='.$this->med_id.'&type=outbound&period="+period );
            
            //hide pagination controler
            $(".dataTables_length").hide();

		});
		
		calcPatientPrice();
		
		function calcPatientPrice(){
			var tracking_price = parseInt($("#doc-tracking-price").val());
			var consult_price = parseInt($("#doc-consult-price").val());
			
			if(tracking_price * 0.02 > 1000){
				var tracking_price_final = (tracking_price / 100) + (tracking_price * 0.02 / 100);
			}else{
				var tracking_price_final = (tracking_price + 1000) / 100;
			}
			
			if(consult_price * 0.02 > 1200){
				var consult_price_final = (consult_price / 100) + (consult_price * 0.02 / 100);
			}else{
				var consult_price_final = (consult_price + 1200) / 100;
			}
			
			$("#display-tracking-total").text(tracking_price_final);
			$("#display-consult-total").text(consult_price_final);
		}
		
		function saveTrackingPrice(){
			var price = $("#my-price-tracking").val();
			var medid = '.$this->med_id.';
			
			$.post("chartClassUnit.php?ajax=update_tracking_price", {price: price, medid: medid})
			.done(function(data){
				$("#doc-tracking-price").val((price * 100));
				calcPatientPrice();
				swal("Tracking price updated.");
			});
		}
		
		function saveConsultPrice(){
			var price = $("#my-price-consultation").val();
			var medid = '.$this->med_id.';
			
			$.post("chartClassUnit.php?ajax=update_consult_price", {price: price, medid: medid})
			.done(function(data){
				$("#doc-consult-price").val((price * 100));
				calcPatientPrice();
				swal("Consultation price updated.");
			});
		}
		</script>';
		}
	}


	public function grab_financial_ajax($period, $med_id){
		$numUsers = 0;
		
		//Start of getting all global records for HTI
		if($this->scope == "Global")
		{
		   
				//start of getting the hourly data if the selected data period is 'today'
			if($period == 1)
			{
			  
				$curdate = date("Y-m-d");
				
				for($i=0;$i<=23;$i++)
				{
					$time = "";
					if($i < 10)
					{
						$time = "0";
					}
					$time .= $i;
					
					$query = $this->con->prepare("select consultationId from consults where DateTime between ? and ? AND Doctor = ?");
					$query->bindValue(1, $curdate." ".$time.":00:00", PDO::PARAM_STR);
					$query->bindValue(2, $curdate." ".$time.":59:59", PDO::PARAM_STR);
					$query->bindValue(3, $med_id, PDO::PARAM_INT);
					$query->execute();
					$result = $query->rowCount();
					
					$query1 = $this->con->prepare("select distinct(patient) from consults where DateTime between ? and ? AND Doctor = ?");
					$query1->bindValue(1, $curdate." ".$time.":00:00", PDO::PARAM_STR);
					$query1->bindValue(2, $curdate." ".$time.":59:59", PDO::PARAM_STR);
					$query1->bindValue(3, $med_id, PDO::PARAM_INT);
					$query1->execute();
					$result1 = $query1->rowCount();           
				   
					array_push($this->hourlyConsultationData,$result);
					array_push($this->hourlyUserData,$result1);
				}
				
				$query2 = $this->con->prepare("SELECT DISTINCT Patient FROM consults WHERE DateTime LIKE '".date("Y-m-d")."%' AND Doctor = ?");
				$query2->bindValue(1, $med_id, PDO::PARAM_INT);
				$query2->execute();
				$numUsers = $query2->rowCount();     
			}
			
			//start of getting the weekly data if the selected data period is 'week'
			elseif($period == 2)
			{
			   
				$dateOfLastSunday = date('Y-m-d',strtotime('last sunday'));
				$todayDate = date("Y-m-d");
				
				$current_date = "";
				$count = 0;
				
			   while(strcmp($current_date, $todayDate) != 0)
			   {
					
					$current_date = date("Y-m-d", strtotime("+".$count." day", strtotime($dateOfLastSunday)));

					$query = $this->con->prepare("select consultationId from consults where DateTime between ? and ? AND Doctor = ?");
					$query->bindValue(1, $curdate." 00:00:00", PDO::PARAM_STR);
					$query->bindValue(2, $curdate." 23:59:59", PDO::PARAM_STR);
					$query->bindValue(3, $this->med_id, PDO::PARAM_INT);
					$query->execute();
					$result = $query->rowCount();
					array_push($this->weeklyConsultationData,$result);
							
					
					$query1 = $this->con->prepare("select distinct(patient) from consults where DateTime between ? and ? AND Doctor = ?");
					$query1->bindValue(1, $curdate." 00:00:00", PDO::PARAM_STR);
					$query1->bindValue(2, $curdate." 23:59:59", PDO::PARAM_STR);
					$query1->bindValue(3, $this->med_id, PDO::PARAM_INT);
					$query1->execute();
					$result1 = $query1->rowCount();
					array_push($this->weeklyUserData,$result1);
					$count++;
				}
				
				$query2 = $this->con->prepare("SELECT DISTINCT Patient FROM consults WHERE DateTime BETWEEN ? AND ? AND Doctor = ?");
				$query2->bindValue(1, $dateOfLastSunday, PDO::PARAM_STR);
				$query2->bindValue(2, $todayDate, PDO::PARAM_STR);
				$query2->bindValue(3, $this->med_id, PDO::PARAM_INT);
				$query2->execute();
				$numUsers = $query2->rowCount();
			}
			
			//start of getting the monthy data if the selected data period is 'month'
			elseif($period == 3)
			{
				$firstDateOfMonth = date('Y-m-01');
				$todayDate = date("Y-m-d");
				$current_date = "";
				$count = 0;
				
				while(strcmp($current_date, $todayDate) != 0)
				{
					$current_date = date("Y-m-d", strtotime("+".$count." day", strtotime($firstDateOfMonth)));
					
					$query = $this->con->prepare("select consultationId from consults where DateTime between ? and ? AND Doctor = ?");
					$query->bindValue(1, $current_date." 00:00:00", PDO::PARAM_STR);
					$query->bindValue(2, $current_date." 23:59:59", PDO::PARAM_STR);
					$query->bindValue(3, $this->med_id, PDO::PARAM_INT);
					$query->execute();
					$result = $query->rowCount();
					array_push($this->monthlyConsultationData,$result);
							
					
					$query1 = $this->con->prepare("select distinct(patient) from consults where DateTime between ? and ? AND Doctor = ?");
					$query1->bindValue(1, $current_date." 00:00:00", PDO::PARAM_STR);
					$query1->bindValue(2, $current_date." 23:59:59", PDO::PARAM_STR);
					$query1->bindValue(3, $this->med_id, PDO::PARAM_INT);
					$query1->execute();
					$result1 = $query1->rowCount();
					array_push($this->monthlyUserData,$result1);
					$count++;
				}
				
				$query2 = $this->con->prepare("SELECT DISTINCT Patient FROM consults WHERE DateTime BETWEEN ? AND ? AND Doctor = ?");
				$query2->bindValue(1, $firstDateOfMonth, PDO::PARAM_STR);
				$query2->bindValue(2, $todayDate, PDO::PARAM_STR);
				$query2->bindValue(3, $this->med_id, PDO::PARAM_INT);
				$query2->execute();
				$numUsers = $query2->rowCount();
				
			}
			//end of getting the monthly data if the selected data period is 'month'
			
			//Start of getting the yearly data if the selected data period is 'year'              
			elseif($period == 4)
			{
				$firstDateOfYear = date('Y-01-01');
				$todayDate = date("Y-m-01");
				$current_date = "";
				$count = 0;
				
				while(strcmp($current_date, $todayDate) != 0)
				{
					$current_date = date("Y-m-d", strtotime("+".$count." month", strtotime($firstDateOfYear)));
					$compare_str = explode("-", $current_date);
					$compare_str = $compare_str[0]."-".$compare_str[1];
					
					$query = $this->con->prepare("select consultationId from consults where DateTime like ? AND Doctor = ?");
					$query->bindValue(1, $compare_str."%", PDO::PARAM_STR);
					$query->bindValue(2, $med_id, PDO::PARAM_INT);
					$query->execute();
					$result = $query->rowCount();
					array_push($this->yearlyConsultationData,$result);
							
					
					$query1 = $this->con->prepare("select distinct(patient) from consults where DateTime like ? AND Doctor = ?");
					$query1->bindValue(1, $compare_str."%", PDO::PARAM_STR);
					$query1->bindValue(2, $med_id, PDO::PARAM_INT);
					$query1->execute();
					$result1 = $query1->rowCount();
					array_push($this->yearlyUserData,$result1);
					$count++;
				}

				$query2 = $this->con->prepare("SELECT DISTINCT Patient FROM consults WHERE DateTime BETWEEN ? AND ? AND Doctor = ?");
				$query2->bindValue(1, $firstDateOfYear, PDO::PARAM_STR);
				$query2->bindValue(2, date("Y-m-d"), PDO::PARAM_STR);
				$query2->bindValue(3, $med_id, PDO::PARAM_INT);
				$query2->execute();
				$numUsers = $query2->rowCount();
				
			}
		//end of getting the yearly data if the selected data period is 'year'
		}


		if($period == 1)
		{
		 echo json_encode(array("consultations" => $this->hourlyConsultationData, "users" => $this->hourlyUserData, "numUsers" => $numUsers));
		}

		elseif($period == 2)
		{
		 echo json_encode(array("consultations" => $this->weeklyConsultationData, "users" => $this->weeklyUserData, "numUsers" => $numUsers));
		}

		elseif($period == 3)
		{
		 echo json_encode(array("consultations" => $this->monthlyConsultationData, "users" => $this->monthlyUserData, "numUsers" => $numUsers));
		}
		elseif($period == 4)
		{
		 echo json_encode(array("consultations" => $this->yearlyConsultationData, "users" => $this->yearlyUserData, "numUsers" => $numUsers));
		}
	}
	
	public function grab_financial_data_ajax($med_id, $type, $period){
		
		if($type == 'outbound'){
			if($period == 1)
			{
			  
				$curdate = date("Y-m-d");
					
				$result = $this->con->prepare("SELECT * FROM payments WHERE owner_id=? && owner_type='doctor' && service_type = 'buy credits' && timestamp BETWEEN ? AND ?");
				$result->bindValue(1, $med_id, PDO::PARAM_INT);
				$result->bindValue(2, $curdate." 00:00:00", PDO::PARAM_STR);
				$result->bindValue(3, $curdate." 23:59:59", PDO::PARAM_STR);
				$result->execute();
				
			}elseif($period == 2){
			   
				$dateOfLastSunday = date('Y-m-d',strtotime('last sunday'));
				$todayDate = date("Y-m-d");
					
				$result = $this->con->prepare("SELECT * FROM payments WHERE owner_id=? && owner_type='doctor' && service_type = 'buy credits' && timestamp BETWEEN ? AND ?");
				$result->bindValue(1, $med_id, PDO::PARAM_INT);
				$result->bindValue(2, $dateOfLastSunday." 00:00:00", PDO::PARAM_STR);
				$result->bindValue(3, $todayDate." 23:59:59", PDO::PARAM_STR);
				$result->execute();
				
				
			}elseif($period == 3){
				
				$firstDateOfMonth = date('Y-m-01');
				$todayDate = date("Y-m-d");
				
				$result = $this->con->prepare("SELECT * FROM payments WHERE owner_id=? && owner_type='doctor' && service_type = 'buy credits' && timestamp BETWEEN ? AND ?");
				$result->bindValue(1, $med_id, PDO::PARAM_INT);
				$result->bindValue(2, $firstDateOfMonth." 00:00:00", PDO::PARAM_STR);
				$result->bindValue(3, $todayDate." 23:59:59", PDO::PARAM_STR);
				$result->execute();
				
			}elseif($period == 4){
				
				$firstDateOfYear = date('Y-01-01');
				$todayDate = date("Y-m-d");
				
				$result = $this->con->prepare("SELECT * FROM payments WHERE owner_id=? && owner_type='doctor' && service_type = 'buy credits' && timestamp BETWEEN ? AND ?");
				$result->bindValue(1, $med_id, PDO::PARAM_INT);
				$result->bindValue(2, $firstDateOfYear." 00:00:00", PDO::PARAM_STR);
				$result->bindValue(3, $todayDate." 23:59:59", PDO::PARAM_STR);
				$result->execute();
				
			}else{
				$result = $this->con->prepare("SELECT * FROM payments WHERE owner_id=? && owner_type='doctor' && service_type = 'buy credits'");
				$result->bindValue(1, $med_id, PDO::PARAM_INT);
				$result->execute();
			}
		}elseif($type == 'inbound'){
			if($period == 1){
				
				$curdate = date("Y-m-d");
				
				$result = $this->con->prepare("SELECT * FROM payments WHERE ((owner_id=? && owner_type='doctor') OR payout_id = ?) && service_type != 'buy credits' && timestamp BETWEEN ? AND ?");
				$result->bindValue(1, $med_id, PDO::PARAM_INT);
				$result->bindValue(2, $med_id, PDO::PARAM_INT);
				$result->bindValue(3, $curdate." 00:00:00", PDO::PARAM_STR);
				$result->bindValue(4, $curdate." 23:59:59", PDO::PARAM_STR);
				$result->execute();
			}elseif($period == 2){
			   
				$dateOfLastSunday = date('Y-m-d',strtotime('last sunday'));
				$todayDate = date("Y-m-d");
					
				$result = $this->con->prepare("SELECT * FROM payments WHERE ((owner_id=? && owner_type='doctor') OR payout_id = ?) && service_type != 'buy credits' && timestamp BETWEEN ? AND ?");
				$result->bindValue(1, $med_id, PDO::PARAM_INT);
				$result->bindValue(2, $med_id, PDO::PARAM_INT);
				$result->bindValue(3, $dateOfLastSunday." 00:00:00", PDO::PARAM_STR);
				$result->bindValue(4, $todayDate." 23:59:59", PDO::PARAM_STR);
				$result->execute();
				
				
			}elseif($period == 3){
				
				$firstDateOfMonth = date('Y-m-01');
				$todayDate = date("Y-m-d");
				
				$result = $this->con->prepare("SELECT * FROM payments WHERE ((owner_id=? && owner_type='doctor') OR payout_id = ?) && service_type != 'buy credits' && timestamp BETWEEN ? AND ?");
				$result->bindValue(1, $med_id, PDO::PARAM_INT);
				$result->bindValue(2, $med_id, PDO::PARAM_INT);
				$result->bindValue(3, $firstDateOfMonth." 00:00:00", PDO::PARAM_STR);
				$result->bindValue(4, $todayDate." 23:59:59", PDO::PARAM_STR);
				$result->execute();
				
			}elseif($period == 4){
				
				$firstDateOfYear = date('Y-01-01');
				$todayDate = date("Y-m-d");
				
				$result = $this->con->prepare("SELECT * FROM payments WHERE ((owner_id=? && owner_type='doctor') OR payout_id = ?) && service_type != 'buy credits' && timestamp BETWEEN ? AND ?");
				$result->bindValue(1, $med_id, PDO::PARAM_INT);
				$result->bindValue(2, $med_id, PDO::PARAM_INT);
				$result->bindValue(3, $firstDateOfYear." 00:00:00", PDO::PARAM_STR);
				$result->bindValue(4, $todayDate." 23:59:59", PDO::PARAM_STR);
				$result->execute();
				
			}else{
				$result = $this->con->prepare("SELECT * FROM payments WHERE ((owner_id=? && owner_type='doctor') OR payout_id = ?) && service_type != 'buy credits'");
				$result->bindValue(1, $med_id, PDO::PARAM_INT);
				$result->execute();
			}
		}
		
		$count = $result->rowCount();
		
		$data = '{"data": [';
		$i = 0;
		while($row = $result->fetch(PDO::FETCH_ASSOC)){
			
			$name_holder = '';
			
			$service = $this->con->prepare("SELECT * FROM usuarios WHERE Identif = ?");
			$service->bindValue(1, $row['service_id'], PDO::PARAM_INT);
			$service->execute();
			
			$service_row = $service->fetch(PDO::FETCH_ASSOC);
			
			if($row['service_type'] == 'probe'){
				$type = 'Tracking';
				$base_sub = 10;
				$name_holder = '('.$service_row['Name'].' '.$service_row['Surname'].' for '.$row['months'].' months)';
			}elseif($row['service_type'] == 'buy credits'){
				$type = 'Purchase Credits';
				$base_sub = 0;
				$name_holder = '';
			}elseif($row['service_type'] == 'consultation'){
				$type = 'Consultation';
				$base_sub = 12;
				$name_holder = '('.$service_row['Name'].' '.$service_row['Surname'].')';
			}
			
			$display_date = substr($row['timestamp'],0 , 10);
			$hour = substr($row['timestamp'],11 , 2);
			$minute = substr($row['timestamp'],14 , 2);
			
			if($hour / 12 >= 1){
				$ampm = 'pm';
				$hour = $hour % 12;
			}else{
				$ampm = 'am';
			}

			$price = '$'.($row['amount'] / 100);
			if($row['amount'] == null){
				$doctor_portion = '$0';
			}else{
				$doctor_portion = '$'.(($row['amount'] / 100) - $base_sub);
			}
			
			if($row['service_type'] == 'probe'){
				if($row['currency'] == 'usd'){
					$doctor_portion = '$'.(($row['amount'] / 100) - ($base_sub * $row['months']));
				}else{
					$doctor_portion = '$0';
				}
			}
			
			if($row['service_type'] == 'buy credits'){
				$doctor_portion = '';
			}

			
			$data .= '[
						"'.$display_date.' '.$hour.':'.$minute.$ampm.'",
						"'.$type.' '.$name_holder.'",
						"'.$price.'",
						"'.$doctor_portion.'"
						]';
						
						if($i != ($count - 1)){
							$data .= ',';
						}
						
						$i++;
		}
		
		$data .= ']}';
		
		echo $data;
	}

	public function update_tracking_price_ajax($price, $medid){
		$result = $this->con->prepare("UPDATE doctors SET tracking_price = ? WHERE id = ?");
		$result->bindValue(1, ($price*100), PDO::PARAM_INT);
		$result->bindValue(2, $medid, PDO::PARAM_INT);
		$success = $result->execute();
		
		if($success){
			echo "success";
		}else{
			echo "error";
		}
	}
	
	public function calc_new_totals($med_id, $type, $period){
		if($type == 'outbound'){
			if($period == 1)
			{
			  
				$curdate = date("Y-m-d");
					
				$result = $this->con->prepare("SELECT * FROM payments WHERE owner_id=? && owner_type='doctor' && service_type = 'buy credits' && timestamp BETWEEN ? AND ?");
				$result->bindValue(1, $med_id, PDO::PARAM_INT);
				$result->bindValue(2, $curdate." 00:00:00", PDO::PARAM_STR);
				$result->bindValue(3, $curdate." 23:59:59", PDO::PARAM_STR);
				$result->execute();
				
			}elseif($period == 2){
			   
				$dateOfLastSunday = date('Y-m-d',strtotime('last sunday'));
				$todayDate = date("Y-m-d");
					
				$result = $this->con->prepare("SELECT * FROM payments WHERE owner_id=? && owner_type='doctor' && service_type = 'buy credits' && timestamp BETWEEN ? AND ?");
				$result->bindValue(1, $med_id, PDO::PARAM_INT);
				$result->bindValue(2, $dateOfLastSunday." 00:00:00", PDO::PARAM_STR);
				$result->bindValue(3, $todayDate." 23:59:59", PDO::PARAM_STR);
				$result->execute();
				
				
			}elseif($period == 3){
				
				$firstDateOfMonth = date('Y-m-01');
				$todayDate = date("Y-m-d");
				
				$result = $this->con->prepare("SELECT * FROM payments WHERE owner_id=? && owner_type='doctor' && service_type = 'buy credits' && timestamp BETWEEN ? AND ?");
				$result->bindValue(1, $med_id, PDO::PARAM_INT);
				$result->bindValue(2, $firstDateOfMonth." 00:00:00", PDO::PARAM_STR);
				$result->bindValue(3, $todayDate." 23:59:59", PDO::PARAM_STR);
				$result->execute();
				
			}elseif($period == 4){
				
				$firstDateOfYear = date('Y-01-01');
				$todayDate = date("Y-m-d");
				
				$result = $this->con->prepare("SELECT * FROM payments WHERE owner_id=? && owner_type='doctor' && service_type = 'buy credits' && timestamp BETWEEN ? AND ?");
				$result->bindValue(1, $med_id, PDO::PARAM_INT);
				$result->bindValue(2, $firstDateOfYear." 00:00:00", PDO::PARAM_STR);
				$result->bindValue(3, $todayDate." 23:59:59", PDO::PARAM_STR);
				$result->execute();
				
			}else{
				$result = $this->con->prepare("SELECT * FROM payments WHERE owner_id=? && owner_type='doctor' && service_type = 'buy credits'");
				$result->bindValue(1, $med_id, PDO::PARAM_INT);
				$result->execute();
			}
		}elseif($type == 'inbound'){
			if($period == 1){
				
				$curdate = date("Y-m-d");
				
				$result = $this->con->prepare("SELECT * FROM payments WHERE ((owner_id=? && owner_type='doctor') OR payout_id = ?) && service_type != 'buy credits' && timestamp BETWEEN ? AND ?");
				$result->bindValue(1, $med_id, PDO::PARAM_INT);
				$result->bindValue(2, $med_id, PDO::PARAM_INT);
				$result->bindValue(3, $curdate." 00:00:00", PDO::PARAM_STR);
				$result->bindValue(4, $curdate." 23:59:59", PDO::PARAM_STR);
				$result->execute();
			}elseif($period == 2){
			   
				$dateOfLastSunday = date('Y-m-d',strtotime('last sunday'));
				$todayDate = date("Y-m-d");
					
				$result = $this->con->prepare("SELECT * FROM payments WHERE ((owner_id=? && owner_type='doctor') OR payout_id = ?) && service_type != 'buy credits' && timestamp BETWEEN ? AND ?");
				$result->bindValue(1, $med_id, PDO::PARAM_INT);
				$result->bindValue(2, $med_id, PDO::PARAM_INT);
				$result->bindValue(3, $dateOfLastSunday." 00:00:00", PDO::PARAM_STR);
				$result->bindValue(4, $todayDate." 23:59:59", PDO::PARAM_STR);
				$result->execute();
				
				
			}elseif($period == 3){
				
				$firstDateOfMonth = date('Y-m-01');
				$todayDate = date("Y-m-d");
				
				$result = $this->con->prepare("SELECT * FROM payments WHERE ((owner_id=? && owner_type='doctor') OR payout_id = ?) && service_type != 'buy credits' && timestamp BETWEEN ? AND ?");
				$result->bindValue(1, $med_id, PDO::PARAM_INT);
				$result->bindValue(2, $med_id, PDO::PARAM_INT);
				$result->bindValue(3, $firstDateOfMonth." 00:00:00", PDO::PARAM_STR);
				$result->bindValue(4, $todayDate." 23:59:59", PDO::PARAM_STR);
				$result->execute();
				
			}elseif($period == 4){
				
				$firstDateOfYear = date('Y-01-01');
				$todayDate = date("Y-m-d");
				
				$result = $this->con->prepare("SELECT * FROM payments WHERE ((owner_id=? && owner_type='doctor') OR payout_id = ?) && service_type != 'buy credits' && timestamp BETWEEN ? AND ?");
				$result->bindValue(1, $med_id, PDO::PARAM_INT);
				$result->bindValue(2, $med_id, PDO::PARAM_INT);
				$result->bindValue(3, $firstDateOfYear." 00:00:00", PDO::PARAM_STR);
				$result->bindValue(4, $todayDate." 23:59:59", PDO::PARAM_STR);
				$result->execute();
				
			}else{
				$result = $this->con->prepare("SELECT * FROM payments WHERE ((owner_id=? && owner_type='doctor') OR payout_id = ?) && service_type != 'buy credits'");
				$result->bindValue(1, $med_id, PDO::PARAM_INT);
				$result->execute();
			}
		}
		
		$total = 0;
		$doc_total = 0;
        $base_sub = 0;
		while($row = $result->fetch(PDO::FETCH_ASSOC)){
			
			if($row['service_type'] == 'probe' && $row['owner_type'] == 'doctor'){
				$base_sub = 1000;
			}elseif($row['service_type'] == 'buy credits'){
				$base_sub = 0;
			}elseif($row['service_type'] == 'consultation'){
				$base_sub = 1200;
			}
			
			$doc_total = $doc_total + ($row['amount'] - ($base_sub * $row['months']));
			$total = $total + ($row['amount']);
		}
		
		$doc_total = $doc_total / 100;
		$total = $total / 100;
		
		$arr = array('total' => $total, 'doc_total' => $doc_total);
		
		echo json_encode($arr);
	}
	
	public function update_consult_price_ajax($price, $medid){
		$result = $this->con->prepare("UPDATE doctors SET consult_price = ? WHERE id = ?");
		$result->bindValue(1, ($price*100), PDO::PARAM_INT);
		$result->bindValue(2, $medid, PDO::PARAM_INT);
		$success = $result->execute();
		
		if($success){
			echo "success";
		}else{
			echo "error";
		}
	}
	
}

?>
