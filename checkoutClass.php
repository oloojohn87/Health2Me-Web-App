<?php
class checkoutClass{
	//PDO
	private $con;
	
	//Doctor
	public $med_id;
	public $doctor_name;
	public $doctor_surname;
	public $doctor_currency;
	public $doctor_base_price;
	
	//Member
	public $user_id;
	public $member_name;
	public $member_surname;
	public $member_email;
	public $member_phone;
	public $member_currency;
	public $member_update_phone = '';
	public $member_update_email = '';
	
	//Selected probe
	public $probe_id = '';
	public $probe_time = '';
	public $probe_timezone = '';
	public $probe_method = '';
	public $probe_interval = '';
	public $pre_selected_probe = 0;
	public $probe_data;
	
	//Scheduled probes for later payment
	public $scheduled_probes;
	
	//Display
	public $display_type;
	
	//CSS
	public $body_height;
	public $doctor_credits_button_css;
	public $display_credits_css;
	
	//Title
	public $modal_title;
	
	public $time_select = 
	'<option value="0">12:00am</option>
	<option value="0.5">12:30am</option>
	<option value="1">1:00am</option>
	<option value="1.5">1:30am</option>
	<option value="2">2:00am</option>
	<option value="2.5">2:30am</option>
	<option value="3">3:00am</option>
	<option value="3.5">3:30am</option>
	<option value="4">4:00am</option>
	<option value="4.5">4:30am</option>
	<option value="5">5:00am</option>
	<option value="5.5">5:30am</option>
	<option value="6">6:00am</option>
	<option value="6.5">6:30am</option>
	<option value="7">7:00am</option>
	<option value="7.5">7:30am</option>
	<option value="8">8:00am</option>
	<option value="8.5">8:30am</option>
	<option value="9">9:00am</option>
	<option value="9.5">9:30am</option>
	<option value="10">10:00am</option>
	<option value="10.5">10:30am</option>
	<option value="11">11:00am</option>
	<option value="11.5">11:30am</option>
	<option value="12">12:00pm</option>
	<option value="12.5">12:30pm</option>
	<option value="13">1:00pm</option>
	<option value="13.5">1:30pm</option>
	<option value="14">2:00pm</option>
	<option value="14.5">2:30pm</option>
	<option value="15">3:00pm</option>
	<option value="15.5">3:30pm</option>
	<option value="16">4:00pm</option>
	<option value="16.5">4:30pm</option>
	<option value="17">5:00pm</option>
	<option value="17.5">5:30pm</option>
	<option value="18">6:00pm</option>
	<option value="18.5">6:30pm</option>
	<option value="19">7:00pm</option>
	<option value="19.5">7:30pm</option>
	<option value="20">8:00pm</option>
	<option value="20.5">8:30pm</option>
	<option value="21">9:00pm</option>
	<option value="21.5">9:30pm</option>
	<option value="22">10:00pm</option>
	<option value="22.5">10:30pm</option>
	<option value="23">11:00pm</option>
	<option value="23.5">11:30pm</option>';
	
	
	public function __construct($user_id, $med_id, $includes, $ask = false){
		require("environment_detail.php");
		
		if($includes == 'yes'){
			echo '<body style="background-image:none;"><script src="js/jquery.min.js"></script>
			<script src="js/jquery-ui.min.js"></script>
			<link href="css/style.css" rel="stylesheet">
			<link href="css/bootstrap.css" rel="stylesheet">
			<link rel="stylesheet" href="css/jquery-ui-1.8.16.custom.css" media="screen"  />
			<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
			<link rel="stylesheet" href="css/icon/font-awesome.css">
			<link rel="stylesheet" type="text/css" href="css/sweet-alert.css">
			<script src="js/sweet-alert.min.js"></script>';
		}
		
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
		
		if(isset($med_id)){
			$this->med_id = $med_id;
		}else{
			$this->med_id = 0;
		}
		if(isset($user_id)){
			$this->user_id = $user_id;
		}else{
			$this->user_id = 0;
		}
		
		if(isset($_GET['probe_id']) && isset($_GET['probe_time']) && isset($_GET['probe_timezone']) && isset($_GET['probe_method']) && isset($_GET['probe_interval'])){
			$this->probe_id = $_GET['probe_id'];
			$this->probe_time = $_GET['probe_time'];
			$this->probe_timezone = $_GET['probe_timezone'];
			$this->probe_method = $_GET['probe_method'];
			$this->probe_interval = $_GET['probe_interval'];
			$this->pre_selected_probe = 1;
			
			$probe = $this->con->prepare("SELECT * FROM probe_protocols WHERE protocolID = ?");
			$probe->bindValue(1, $this->probe_id, PDO::PARAM_INT);
			$probe_result = $probe->execute();
			$probe_row = $probe->fetch(PDO::FETCH_ASSOC);
			$this->probe_data = $probe_row;
		}
		
		$query = $this->con->prepare("SELECT * FROM usuarios WHERE Identif = ?");
		$query->bindValue(1, $this->user_id, PDO::PARAM_INT);
		$result = $query->execute();
		$row = $query->fetch(PDO::FETCH_ASSOC);
		
		if(isset($_GET['member_email'])){
			$this->member_update_email = $_GET['member_email'];
		}else{
			$this->member_update_email = $row['email'];
		}
		
		if(isset($_GET['member_phone'])){
			$this->member_update_phone = $_GET['member_phone'];
		}else{
			$this->member_update_phone = $row['telefono'];
		}
		
		$this->member_name = $row['Name'];
		$this->member_surname = $row['Surname'];
		$this->member_email = $row['email'];
		$this->member_phone = $row['telefono'];
		$this->member_currency = $row['credits'];
		
		if($this->member_email == ''){
			$this->member_email = 'Unknown';
		}
		
		if($this->member_phone == ''){
			$this->member_phone = 'Unknown';
		}
		
		if(!isset($_GET['member'])){
			$this->display_type = 'doctor';
		}else{
			$this->display_type = 'member';
			$sched_probes = $this->con->prepare("SELECT * FROM probe WHERE patientID = ? && patientPermission = 0");
			$sched_probes->bindValue(1, $this->user_id, PDO::PARAM_INT);
			$res_sched_probes = $sched_probes->execute();
			$row_sched_probes = $sched_probes->fetch(PDO::FETCH_ASSOC);
			
			$this->scheduled_probes = $row_sched_probes;
		}
		
		if($this->pre_selected_probe == 1){
			$this->body_height = '57%';
			$this->doctor_credits_button_css = '-25px';
			$this->display_credits_css = '20px';
		}else{
			$this->body_height = '96%';
			$this->doctor_credits_button_css = '10px';
			$this->display_credits_css = '10px';
		}
		
		if($this->med_id != ''){
			$query3 = $this->con->prepare("SELECT * FROM doctors WHERE id = ?");
			$query3->bindValue(1, $this->med_id, PDO::PARAM_INT);
			$result3 = $query3->execute();
			$row3 = $query3->fetch(PDO::FETCH_ASSOC);
			
			$this->doctor_name = $row3['Name'];
			$this->doctor_surname = $row3['Surname'];	
			$this->doctor_currency = $row3['credits'];
			$this->doctor_base_price = $row3['tracking_price'];
			
			$this->modal_title = 'Check out for Dr. '.$this->doctor_name.' '.$this->doctor_surname.' regarding member '.$this->member_name.' '.$this->member_surname;
		}else{
			$this->modal_title = 'Check out for member '.$this->member_name.' '.$this->member_surname;
		}
		
		$this->modal_window($ask);
	}
	
	private function modal_window($ask){
        
        echo '<div id="checkout_ask_window" style="display: none; text-align: center;" title="Checkout?">';
        echo '<br/>Would you like to checkout this patient now?';
        echo '<br/><br/><br/>';
        echo '<div style="width: 230px; height: 30px; margin: auto;">';
        echo '<button id="checkout_ask_no_button" style="width: 100px; height: 25px; color: #FFF; background-color: #D84840; border: 0px solid #FFF; outline: none; border-radius: 5px;">No</button>';
        echo '<button id="checkout_ask_yes_button" style="width: 100px; height: 25px; color: #FFF; background-color: #54BC00; border: 0px solid #FFF; outline: none; border-radius: 5px; margin-left: 30px;">Yes</button>';
        echo '</div>';
        echo '</div>'; 
		
		echo '<div id="checkout-window" style="background-color:white;overflow:hidden;">
		<div id="checkout-header" style="width:100%;">
			<div style="width:75%;margin-left:200px;">
				<div style="width:33%;float:left;color: #22aeff; font-size: 18px;">Member</div><div style="width:33%;float:left;color: #22aeff; font-size: 18px;">Email</div><div style="width:33%;float:left;color: #22aeff; font-size: 18px;">Phone</div>
			</div>
			<div style="width:75%;margin-left:200px;">
				<div style="width:33%;float:left;color: #54BC00; font-size: 18px;">'.$this->member_name.' '.$this->member_surname.'</div><div style="width:33%;float:left;color: #54BC00; font-size: 18px;">'.$this->member_email.'</div><div style="width:33%;float:left;color: #54BC00; font-size: 18px;">'.$this->member_phone.'</div>
			</div>
		</div>
		';
		if($this->display_type == 'doctor'){
			$this->make_display('doctor');
		}else{
			$this->make_display('member');
		}
		echo '
		<div id="checkout-footer" style="margin-left:700px">
			<button id="check-out-button-final" onclick="check_out_button();" style="margin-top:10px;float:right;margin-right:8px;" class="btn btn-success" disabled>Check Out</button>
			<button id="share-reports" onclick="share_reports();" style="margin-top:10px;float:right;margin-right:8px;" class="btn btn-info">Share Reports</button>
			<button id="back-reports" onclick="back_reports();" style="margin-top:10px;float:right;margin-right:8px;display:none;" class="btn btn-warning">Back</button>
			<button id="send-reports" onclick="send_reports_now();" style="margin-top:10px;float:right;margin-right:8px;display:none;" class="btn btn-info">Share</button>
		</div>
		</div>
		<input type="hidden" id="stripe-id-holder" value="" />
		<input type="hidden" id="payment-type" value="" />
		<input type="hidden" id="doctor-credits-count" value="'.$this->doctor_currency.'" />
		<input type="hidden" id="doctor-remaining-credits-count" value="" />
		<input type="hidden" id="stripe-id-holder-doctor" value="" />
		<input type="hidden" id="review-cc-number-doctor" value="" />';
		
        $ast_t = 'false';
        if($ask)
            $ask_t = 'true';
		if($this->pre_selected_probe == 0){
			echo '<script type="text/javascript">
                var ask = '.$ask_t.';
				var checkout_window = $("#checkout-window").dialog({bgiframe: true, width: 1000, height: 800, autoOpen: false, modal: true,title:"'.$this->modal_title.'"});
                if(ask)
                {
                    var ask_window = $("#checkout_ask_window").dialog({bgiframe: true, width: 400, height: 180, autoOpen: true, modal: true,});
                    $("#checkout_ask_yes_button").on("click", function()
                    {
                        checkout_window.dialog("open");
                        ask_window.dialog("close");
                    });
                    $("#checkout_ask_no_button").on("click", function()
                    {
                        ask_window.dialog("close");
                    });
                }
                else
                {
                    checkout_window.dialog("open");
                }
			</script>
			';
		}
		
		if($this->display_type == 'doctor'){
			$this->make_javascript('doctor');
		}else{
			$this->make_javascript('member');
		}
		echo '
		';
	}
	
	private function make_display($display_type){
		if($display_type == 'doctor'){
			echo '<div><div id="probe-select" style="">
			<style>
        #probe_toggle{
            margin-top: 25px;
            width: 97%;
            height: 35px;
            border: 0px solid #FFF;
            outline: none;
            border-radius: 5px;
            background-color: #54bc00;
            color: #FFF;
        }
        #edit_probes_button, #save_probes_button{
            margin-top: 10px;
            width: 47%;
            height: 35px;
            border: 0px solid #FFF;
            outline: none;
            border-radius: 5px;
            background-color: #22AEFF;
            color: #FFF;
            float: left;
            margin-top: 20px;
        }
        #launch_probes_button{
            margin-top: 10px;
            width: 97%;
            height: 35px;
            border: 0px solid #FFF;
            outline: none;
            border-radius: 5px;
            background-color: #22AEFF;
            color: #FFF;
        }
        #probe_add,#probe_cancel{
            margin-top: 10px;
            width: 100%;
            height: 35px;
            border: 0px solid #FFF;
            outline: none;
            border-radius: 5px;
            background-color: #54bc00;
            color: #FFF;
        }
        .probe_info_section{
            width: 94%; 
            margin: auto;
            padding: 2%;
            background-color: #FDFDFD;
            border: 1px solid #F2F2F2;
            border-radius: 5px;
            color: #6A6A6A;
            text-align: center;
        }
        .probe_cc_section{
            width: 94%; 
            margin: auto;
            padding: 2%;
            background-color: #FDFDFD;
            border: 1px solid #F2F2F2;
            border-radius: 5px;
            color: #6A6A6A;
            text-align: center;
            margin-top:10px;
        }
    </style>
    <div id="get-temp-months" style="display:none;">
		<center>How many months would you like to schedule this probe for?</center></br>
		<center><input style="height:30px;" type="number" id="temp-months-holder" placeholder="Enter Total Months" /></center>
		<center><button onclick="assign_later();" class="btn btn-success btn-large">Schedule</button></center>
	</div>
    <div id="purchase-credits-doctor" style="display:none;">
		<div class="doctor_cc_section" style="height:215px;">
        <style>
                        .credit_card_doctor_row{
                            background-color: #FBFBFB;
                            color: #222;
                            border: 1px solid #E6E6E6;
                            width: 96%;
                            height: 35px;
                            padding: 4px;
                        }
                    </style>
                    <div id="credit_cards_container_doctor" style="width: 100%; margin-left: auto; margin-right: auto; height: 135px; overflow: scroll;">
                    <i class="fa fa-spinner fa-spin"></i>
                    </div>
                    <div style="margin-top: 10px; width: 100%; margin-left: auto; margin-right: auto;">
                        <script>
                        function isNumberKey(evt)
                        {
                            var charCode = (evt.which) ? evt.which : event.keyCode
                            if (charCode > 31 && (charCode < 48 || charCode > 57))
                                return false;
                            return true;
                        }    
                        </script>
                        <input type="text" onkeypress="return isNumberKey(event)" id="credit_card_number_doctor" maxLength="16" placeholder="Enter card number" style="width: 220px; height: 30px; float: left; border-radius: 5px;">
                        <input id="credit_card_csv_code_doctor" type="text" onkeypress="return isNumberKey(event)" id="csv_code" maxLength="3" placeholder="CSV" style="width: 85px; height: 30px; margin-left: 18px; float: left; border-radius: 5px;">
                        <div style="color: #969696; width: 80px; float: left; text-align: left; padding-left: 5px; border-top-left-radius: 5px; border-bottom-left-radius: 5px; border: 1px solid #CACACA; height: 23px; padding-top: 5px; border-right: 0px solid #FFF;" lang="en">Exp. Date:</div>
                        <input id="credit_card_exp_date_doctor" type="month" style="width: 155px; height: 30px; float: left; font-size: 12px; border-radius: 0px; border-left: 0px solid #FFF; border-top-right-radius: 5px; border-bottom-right-radius: 5px;" />
                        <button onclick="add_credit_card_doctor();" id="add_card_button_doctor" style="width: 100px; height: 30px; background-color: #52D859; border-radius: 0px; border: 0px solid #FFF; color: #FFF; float: right; outline: 0px; margin-left: 18px; border-radius: 5px;" lang="en">Add Card</button>
						</div>
        </div>
        <div id="doctor-cc-review" style="display:none;height:30px; border-radius:5px; width:94%; margin:auto; padding: 2%; background-color: rgb(59, 158, 225);"><center><span id="review-doctor-cc-number"></span>
			<button onclick="change_cards_doctor();" class="btn btn-default" style="float:right;border-radius:10px;">Change Card</button></center>
		</div>
		<div id="purchase-credits-review" style="display:none;width:94%; margin:auto; padding: 2%;">
			<span>How much would you like to add to your account? </span><input onchange="calcPurchasecredits();" id="calc-purchase-credits" style="height:30px;" type="number" min=".5" max="1000" placeholder="Amount in dollars" />
			</br>
			<div style="margin-top:100px;"><span>Total:</span><span style="margin-top:15px;" id="doctor-credits-purchase-total">$0.00</span></div>
			<button onclick="purchasecredits();" style="float:right;margin-top:-30px;" class="btn btn-success">Purchase credits</button>
		</div>
    </div>
    <div id="probe_editor" title="Probes" style="height:'.$this->body_height.';">
        <div id="manage_user_probe" style="display: block; margin: auto; margin-top: 10px;">';
        if($this->pre_selected_probe == 0){
            echo '</br></br><div id="track-probe-toggle"><h1 style="color: #444; font-size: 14px; text-align: center;">Track this member with probe?</h1>
			<center><input onchange="send_probe_toggle();" style="display:inline;margin-top:-3px;" type="radio" name="probe_radio" value="yes" /><span>Yes, track this member.</span>
			<input onchange="send_probe_toggle();" style="display:inline;margin-top:-3px;" type="radio" name="probe_radio" value="no" /><span>No, do not send probe.</span></center></div>
			 
			 <div id="Phase3Container" style="width:100%; height:300px; overflow: auto; margin-top:20px;display:none;">
				<span style="margin-left:300px;margin-top:-10px;display:none;color:#22aeff" id="H2M_Spin_Stream"><i class="icon-spinner icon-2x icon-spin" ></i></span>
				<div id="ReportStream" style=""></div>
             </div>';
		}else{
			echo "<div style='height:100px;margin-top:100px;'><center><button onclick='select_doctor_credits();' class='btn btn-info btn-large'>Use Credit</button><button onclick='change_cards();' class='btn btn-info btn-large' style='margin-left:120px;'>Use Credit Card</button><button onclick='display_months_window();' class='btn btn-info btn-large' style='margin-left:120px;'>Pay Later</button></center></div>";
		}
			
	if($this->pre_selected_probe == 0){
	echo '<div id="initial-probe-buttons" style="height:50px;margin-top:25px;display:none;"><center><button onclick="select_doctor_credits();" class="btn btn-info btn-large">Use Credit</button><button onclick="change_cards();" class="btn btn-info btn-large" style="margin-left:120px;">Use Credit Card</button><button onclick="display_months_window();" class="btn btn-info btn-large" style="margin-left:120px;">Pay Later</button></center></div>
	<div class="probe_info_section" style="height: 200px;display:none;">
                <div style="width: 27%; float: left; margin-right: 40px; margin-left: 5px; height: 24px; padding-top: 6px; text-align: left;">Select Probe:</div>
                <select id="probe_protocols" style="width: 64%; height: 30px;">
                </select>
                <br/>
                <div style="width: 27%; float: left; margin-right: 40px; margin-left: 5px; height: 24px; padding-top: 6px; text-align: left;">Select Time:</div>
                <select id="probe_time" type="text"  style="width: 64%; height: 30px;">
					'.$this->time_select.'
                </select>
                <br/>
                <div style="width: 27%; float: left; margin-right: 40px; margin-left: 5px; height: 24px; padding-top: 6px; text-align: left;">Select Timezone: </div>
                <select id="probe_timezone" style="width: 64%; height: 30px;">
                    <option value="3">US Pacific Time</option>
                    <option value="4">US Mountain Time</option>
                    <option value="2">US Central Time</option>
                    <option value="1">US Eastern Time</option>
                    <option value="5">Europe Central Time</option>
                    <option value="6">Greenwich Mean Time</option>
                </select>
                <br/>
                <div style="width: 27%; float: left; margin-right: 40px; margin-left: 5px; height: 24px; padding-top: 6px; text-align: left;">Select Method: </div>
                <select id="probe_method" style="width: 64%; height: 30px;">
                    <option value="1">Text Message</option>
                    <option value="2">Phone Call</option>
                    <option value="3">Email</option>
                </select>
                <br/>
                <div style="width: 27%; float: left; margin-right: 35px; margin-left: 5px; height: 24px; padding-top: 6px; text-align: left;">Select Interval: </div>
                <select id="probe_interval" style="width: 64%; height: 30px;"> 
                    <option value="1">Daily</option>
                    <option value="7">Weekly</option>
                    <option value="30">Monthly</option>
                    <option value="365">Yearly</option>
                </select>
        </div>';
	}
        
        echo '<center><div id="probe-review-section" style="height:30px;display:none;background-color:#54BC00;border-radius:5px;margin-top:10px;width: 94%; margin: auto;padding: 2%;">
			<center><span id="review-cc-number"></span></center>
			<button onclick="change_cards();" class="btn btn-default" style="float:right;margin-top:-38px;border-radius:10px;">Change Card</button>
        </div></center>
        <center><div id="drcredits-review-section" style="height:30px;display:none;background-color:#3b9ee1;border-radius:5px;margin-top:10px;width: 94%; margin: auto;padding: 2%;">
			<center><span id="review-dr-credits" style="color: white; font-size: 18px;">You have chosen to spend Dr. '.$this->doctor_name.' '.$this->doctor_surname.'\'s credits.</span></center>
			<button onclick="change_cards();" class="btn btn-default" style="float:right;margin-top:-25px;border-radius:10px;">Change To Member\'s Card</button>
        </div></center>
        
        <div id="payment-review-section" style="height:150px;display:none;background-color:#FDFDFD;border-radius:5px;margin-top:10px;width: 94%; margin: auto;padding: 2%;">
			<span>For how many months would you like to run this probe?</span>
			<select onchange="calculate_total();" id="month-count">
				<option value="1">1 Month</option>
				<option value="2">2 Months</option>
				<option value="3">3 Months</option>
				<option value="4">4 Months</option>
				<option value="5">5 Months</option>
				<option value="6">6 Months</option>
				<option value="7">7 Months</option>
				<option value="8">8 Months</option>
				<option value="9">9 Months</option>
				<option value="10">10 Months</option>
				<option value="11">11 Months</option>
				<option value="12">12 Months</option>
			</select>
			<p>
			<span>Number of Months: </span><span id="display-months">1</span>
			<p>
			<span>Cost per Month: </span><span id="display-init-cost">$'.(($this->doctor_base_price + 1200) / 100).'</span>
			<p>
			<span>Sub Total: </span><span id="display-sub-total">$'.(($this->doctor_base_price + 1200) / 100).'</span>
			<p>
			<span>Total :</span><span id="display-total">$'.(($this->doctor_base_price + 1200) / 100).'</span>
        </div>
        
        <div id="credits-review-section" style="height:150px;display:none;background-color:#FDFDFD;border-radius:5px;margin-top:10px;width: 94%; margin: auto;padding: 2%;">
			<span>For how many months would you like to run this probe?</span>
			<select onchange="calculate_total_credits();" id="month-count-credits">
				<option value="1">1 Month</option>
				<option value="2">2 Months</option>
				<option value="3">3 Months</option>
				<option value="4">4 Months</option>
				<option value="5">5 Months</option>
				<option value="6">6 Months</option>
				<option value="7">7 Months</option>
				<option value="8">8 Months</option>
				<option value="9">9 Months</option>
				<option value="10">10 Months</option>
				<option value="11">11 Months</option>
				<option value="12">12 Months</option>
			</select>
			<p>
			<span>Number of Months: </span><span id="display-months-credits">1</span>
			<p>
			<span>credits per Month: </span>$<span id="display-init-cost">12</span>
			<p>
			<span>Sub Total: </span>$<span id="display-sub-credits">12</span>
			<p>
			<span>Total credits:</span>$<span id="display-total-credits">12</span><span style="margin-left:100px;">Total credits for Dr. '.$this->doctor_name.' '.$this->doctor_surname.': <span id="display-doctor-credits">'.('$'.number_format((float)($this->doctor_currency / 100), 2, '.', '')).'</span></span><span style="float:right;">Remaining credits: <span id="display-credits-remaining">'.('$'.number_format((float)(($this->doctor_currency / 100) - (12)), 2, '.', '')).'</span></span>
        </div>
        
        <div class="probe_cc_section" style="display:none;height:215px;">
        <style>
                        .credit_card_row{
                            background-color: #FBFBFB;
                            color: #222;
                            border: 1px solid #E6E6E6;
                            width: 96%;
                            height: 35px;
                            padding: 4px;
                        }
                    </style>
                    <div id="credit_cards_container" style="width: 100%; margin-left: auto; margin-right: auto; height: 135px; overflow: scroll;">
                    <i class="fa fa-spinner fa-spin"></i>
                    </div>
                    <div style="margin-top: 10px; width: 100%; margin-left: auto; margin-right: auto;">
                        <script>
                        function isNumberKey(evt)
                        {
                            var charCode = (evt.which) ? evt.which : event.keyCode
                            if (charCode > 31 && (charCode < 48 || charCode > 57))
                                return false;
                            return true;
                        }    
                        </script>
                        <input type="text" onkeypress="return isNumberKey(event)" id="credit_card_number" maxLength="16" placeholder="Enter card number" style="width: 220px; height: 30px; float: left; border-radius: 5px;">
                        <input id="credit_card_csv_code" type="text" onkeypress="return isNumberKey(event)" id="csv_code" maxLength="3" placeholder="CSV" style="width: 85px; height: 30px; margin-left: 18px; float: left; border-radius: 5px;">
                        <div style="color: #969696; width: 80px; float: left; text-align: left; padding-left: 5px; border-top-left-radius: 5px; border-bottom-left-radius: 5px; border: 1px solid #CACACA; height: 23px; padding-top: 5px; border-right: 0px solid #FFF;" lang="en">Exp. Date:</div>
                        <input id="credit_card_exp_date" type="month" style="width: 155px; height: 30px; float: left; font-size: 12px; border-radius: 0px; border-left: 0px solid #FFF; border-top-right-radius: 5px; border-bottom-right-radius: 5px;" />
                        <button onclick="add_credit_card();" id="add_card_button" style="width: 100px; height: 30px; background-color: #52D859; border-radius: 0px; border: 0px solid #FFF; color: #FFF; float: left; outline: 0px; margin-left: 18px; border-radius: 5px;" lang="en">Add Card</button>
                        <span style="float:left;margin-top:'.$this->display_credits_css.';">Dr. '.$this->doctor_name.' '.$this->doctor_surname.' has '.('$'.number_format((float)($this->doctor_currency / 100), 2, '.', '')).' credits available for use.</span><button onclick="select_doctor_credits();" id="use-doctor-credits" style="float:left;margin-top:'.$this->doctor_credits_button_css.';margin-left:30px;" class="btn btn-info">Use Doctor credits and Not Credit Card</button></p>
						</div>
        </div></div></div></div></div>';
		}else{
			foreach($this->scheduled_probes as $probe){
				echo $probe;
			}
		}
	}
	
	private function make_javascript($display_type){
		if($display_type == 'doctor'){
			echo ' <script type="text/javascript">
				var probe_question_en = ["", "", "", "", ""];
				var probe_question_es = ["", "", "", "", ""];
				var probe_min = [1, 1, 1, 1, 1];
				var probe_max = [5, 5, 5, 5, 5];
				var probe_answer_type = [1, 1, 1, 1, 1];
				var probe_question = 1;
				var selected_probe = -1;
				var english_questions = null;
				var spanish_questions = null;
				var mins = null;
				var maxs = null;
				var answers = null;
				load_probe_protocols();
				
				var reportcheck=new Array();
				var reportids="";
				function attachReports(){
					reportids="";

					$("input[type=checkbox][id^=\"reportcol\"]").each(
						function () {
							var sThisVal = (this.checked ? "1" : "0");
							if(sThisVal==1)
							{
								var idp=$(this).parents("div.attachments").attr("id");
								reportcheck.push(this.id);
								reportids=reportids+idp+" ";

							}



						});
						console.log(reportids);
						$.post( "display_pin_for_member.php", { reports: reportids })
						.done(function( data ) {
							swal("Reports are now viewable by member!", "You have successfully shared the selected reports with this member.  Their reports will now be viewable after login.", "success");
							back_reports();
						});

						reportids="";
						reportcheck.length=0;
					}
				
				function share_reports (){
						var ElementDOM ="All";
						var EntryTypegroup ="0";
						var Usuario = '.$this->user_id.';
						var MedID = '.$this->med_id.';
						$("#ReportStream").html("");
						$("#H2M_Spin_Stream").show();
						$("#track-probe-toggle").hide();
						$("#share-reports").hide();
						$("#initial-probe-buttons").hide();
						$(".probe_cc_section").hide();
						$(".probe_info_section").hide();
						$("#drcredits-review-section").hide();
						$("#credits-review-section").hide();
						$("#back-reports").show();
						$("#send-reports").show();
						setTimeout(function(){
							var queUrl ="createAttachmentStreamNEWTEST.php?ElementDOM=na&EntryTypegroup="+EntryTypegroup+"&Usuario="+Usuario+"&MedID="+MedID+"&display_member=yes";
							$.get(queUrl, function(data, status)
							{
								$("#ReportStream").html(data);
								$("#Phase3Container").show();
								$("#H2M_Spin_Stream").hide();
							});
							
							
							
							
						},1000);
				 }
				 
				function back_reports(){
					$("#Phase3Container").hide();
					$("#share-reports").show();
					$("#back-reports").hide();
					$("#send-reports").hide();
					$("#track-probe-toggle").show();
				}
				
				function send_reports_now(){
					attachReports();
				}
				
				function send_probe_toggle(){
					if($("input:radio[name=probe_radio]:checked").val() == "yes"){
						$("#initial-probe-buttons").show();
						$(".probe_info_section").show();
					}else{
						$("#initial-probe-buttons").hide();
						$(".probe_info_section").hide();
					}
				};
				
				function load_probe_protocols()
				{
					$.post("get_probe_protocols.php", {doctor: '.$this->med_id.'}, function(data, status)
					{
						var d = JSON.parse(data);
						console.log(d);
						var var_length = d["protocols"].length;
						$("#probe_protocols").html("");
						$("#probes_container").html("");
						for(var i = 0; i < var_length; i++)
						{
						console.log("hello");
							$("#probe_protocols").append("<option value=\""+d["protocols"][i].protocolID+"\">"+d["protocols"][i].name+"</option>");
							var html = "<div class=\"probes_row\">";
							html += "<div style=\"float: left; width: 40%; margin-right: 3%; overflow: hidden; white-space: nowrap; text-overflow: ellipsis; font-weight: bold;\">"+d["protocols"][i].name+"</div>";
							html += "<div style=\"float: left; width: 40%; margin-right: 3%; overflow: hidden; white-space: nowrap; text-overflow: ellipsis;\">"+d["protocols"][i].description+"</div>";
							html += "<button class=\"probes_edit_button\" id=\"probes_edit_button_"+d["protocols"][i].protocolID+"\"><i class=\"icon-pencil\"></i></button>";
							html += "<button class=\"probes_delete_button\" id=\"probes_delete_button_"+d["protocols"][i].protocolID+"\">X</button>";
							html += "</div>";
							$("#probes_container").append(html);
						}
						if(var_length == 0)
						{
							$("#probe_protocols").append("<option>No probes defined.  Please add a probe below.</option>");
							//console.log("no probes");
							var html = "<div class=\"no_probes_notification\">";
							html += "You do not have any probes defined.<br/>To add probes, click the "+" button bellow.";
							html += "</div>";
							$("#probes_container").append(html);
						}
					});
				}
				
				function purchasecredits(){
					var credits = $("#calc-purchase-credits").val();
					var total = "$"+(credits);
					swal({   title: "Purchase credits?",   text: ("Are you sure you would like to purchase "+credits+" credits? \n Cost: "+total+""),   type: "warning",   showCancelButton: true,   confirmButtonColor: "#DD6B55",   confirmButtonText: "Yes, purchase credits!",   closeOnConfirm: false }, 
					function(){   
						swal("Purchased!", ("You have successfully purchased "+credits+" credits."), "success");
						$.post("purchaseCredits.php", {doctor: '.$this->med_id.', quantity: (credits*100)}).done(function(data, status)
						{
							var old_count = $("#doctor-credits-count").val();
							var credits_remaining = $("#doctor-remaining-credits-count").val();
							var count = parseInt(old_count / 100)+parseInt(credits);
							var update_remaining = Math.round((credits_remaining + credits)) / 100;
							$("#doctor-credits-count").val(count);
							$("#display-doctor-credits").text(count);
							$("#display-credits-remaining").text(update_remaining);
							$("#doctor-remaining-credits-count").val(update_remaining);
							if(update_remaining < 0){
								$("#display-credits-remaining").css("color", "red");
							}else{
								$("#display-credits-remaining").css("color", "black");
							}
						}).error(function(){
								swal("The charge was rejected!");
						});	
						 
					});
				}
				
				function add_credit_card()
				{
					var date = $("#credit_card_exp_date").val().split("-");
					Stripe.card.createToken({
						number: $("#credit_card_number").val(),
						cvc: $("#credit_card_csv_code").val(),
						exp_month: date[1],
						exp_year: date[0]
					}, stripeResponseHandler);
					
				}
				
				function calcPurchasecredits(){
					var amount = $("#calc-purchase-credits").val();
					var total = "$"+(amount);
					$("#doctor-credits-purchase-total").text(total);
				}
				
				function add_credit_card_doctor()
				{
					var date = $("#credit_card_exp_date_doctor").val().split("-");
					Stripe.card.createToken({
						number: $("#credit_card_number_doctor").val(),
						cvc: $("#credit_card_csv_code_doctor").val(),
						exp_month: date[1],
						exp_year: date[0]
					}, stripeResponseHandlerDoctor);
						
				}
				
				function get_time_from_int(pass_int){
					var time = "";
					
					switch(pass_int){
						case "0":
							time = "12:00am";
							break;
						case "0.5":
							time = "12:30am";
							break;
						case "1":
							time = "1:00am";
							break;
						case "1.5":
							time = "1:30am";
							break;
						case "2":
							time = "2:00am";
							break;
						case "2.5":
							time = "2:30am";
							break;
						case "3":
							time = "3:00am";
							break;
						case "3.5":
							time = "3:30am";
							break;
						case "4":
							time = "4:00am";
							break;
						case "4.5":
							time = "4:30am";
							break;
						case "5":
							time = "5:00am";
							break;
						case "5.5":
							time = "5:30am";
							break;
						case "6":
							time = "6:00am";
							break;
						case "6.5":
							time = "6:30am";
							break;
						case "7":
							time = "7:00am";
							break;
						case "7.5":
							time = "7:30am";
							break;
						case "8":
							time = "8:00am";
							break;
						case "8.5":
							time = "8:30am";
							break;
						case "9":
							time = "9:00am";
							break;
						case "9.5":
							time = "9:30am";
							break;
						case "10":
							time = "10:00am";
							break;
						case "10.5":
							time = "10:30am";
							break;
						case "11":
							time = "11:00am";
							break;
						case "11.5":
							time = "11:30am";
							break;
						case "12":
							time = "12:00pm";
							break;
						case "12.5":
							time = "12:30pm";
							break;
						case "13":
							time = "1:00pm";
							break;
						case "13.5":
							time = "1:30pm";
							break;
						case "14":
							time = "2:00pm";
							break;
						case "14.5":
							time = "2:30pm";
							break;
						case "15":
							time = "3:00pm";
							break;
						case "15.5":
							time = "3:30pm";
							break;
						case "16":
							time = "4:00pm";
							break;
						case "16.5":
							time = "4:30pm";
							break;
						case "17":
							time = "5:00pm";
							break;
						case "17.5":
							time = "5:30pm";
							break;
						case "18":
							time = "6:00pm";
							break;
						case "18.5":
							time = "6:30pm";
							break;
						case "19":
							time = "7:00pm";
							break;
						case "19.5":
							time = "7:30pm";
							break;
						case "20":
							time = "8:00pm";
							break;
						case "20.5":
							time = "8:30pm";
							break;
						case "21":
							time = "9:00pm";
							break;
						case "21.5":
							time = "9:30pm";
							break;
						case "22":
							time = "10:00pm";
							break;
						case "22.5":
							time = "10:30pm";
							break;
						case "23":
							time = "11:00pm";
							break;
						case "23.5":
							time = "11:30pm";
							break;
					}
				
				return time;
				}
				
				$.post("get_user_info.php", {id: '.$this->user_id.'}, function(data, status)
				{
					var info = JSON.parse(data);
					$("#timezone_picker option[value=\"" + info["timezone"] + "\"]").attr("selected", "selected");
					if(info.hasOwnProperty("location") && info["location"].length > 0)
					{
						setTimeout(function(){
							//console.log(info["location"].trim());
							$("#country_setup").val(info["location"]);
							$("#country_setup").change();
						}, 800);
					}else{
						$("#credit_cards_container").html("<center>No credit cards on file for member '.$this->member_name.' '.$this->member_surname.'.</br>Please add a credit card to continue.</center>");
					}
					if(info.hasOwnProperty("location2") && info["location2"].length > 0)
					{
						setTimeout(function(){
							$("#state_setup").val(info["location2"]);
							$("#state_setup").change();
						}, 900);
					}
					if(info.hasOwnProperty("email") && info["email"].length > 0)
					{
						$("#setup_email").val(info["email"]);
					}
					if(info.hasOwnProperty("phone") && info["phone"].length > 0)
					{
						$("#setup_phone").val("+" + info["phone"]);
					}
					if(info.hasOwnProperty("cards") && info["cards"].length > 0)
					{
						load_credit_cards(info["cards"]);
					}
				});
				
				$.post("get_doctor_credit_cards.php", {id: '.$this->med_id.'}, function(data, status)
				{
					var info = JSON.parse(data);
					if(info.hasOwnProperty("cards") && info["cards"].length > 0)
					{
						load_credit_cards_doctor(info["cards"]);
					}else{
						$("#credit_cards_container_doctor").html("<center>No credit cards on file for Dr. '.$this->doctor_name.' '.$this->doctor_surname.'.</br>Please add a credit card to continue.</center>");
					}
				});
				
				function stripeResponseHandler(status, response) {
        
					if (response.error) 
					{
						$("#setup_modal_notification").css("background-color", "#D5483A");
						$("#setup_modal_notification").html("<p style=\"color: #fff;\">"+response.error.message+"</p>");
						$("#setup_modal_notification_container").animate({opacity: "1.0"}, {duration: 1000, easing: "easeInOutQuad", complete: function() {
							setTimeout(function(){$("#setup_modal_notification_container").animate({opacity: "0.0"}, {duration: 1000, easing: "easeInOutQuad", complete: function() {}});}, 2000);
						}});
					} 
					else 
					{
						$("#setup_modal_notification").html("<img src=\"images/load/8.gif\" alt=\"\">");
						$("#setup_modal_notification").css("background-color", "#FFF");
						$("#setup_modal_notification_container").css("opacity", "1.0");
						$.post("change_credit_card.php", {type: "2", action: "1", id: '.$this->user_id.', token: response.id}, function(data, status)
						{
						swal("Credit Card Added!", "You have successfully added a credit card to '.$this->member_name.' '.$this->member_surname.'\'s account.", "success")
							//console.log(data);
							$("#setup_modal_notification_container").css("opacity", "0.0");
							if(data == "1")
							{
								$("#setup_modal_notification").css("background-color", "#52D859");
								$("#setup_modal_notification").html("<p style=\"color: #fff;\">Credit Card Added!</p>");
								$("#setup_modal_notification_container").animate({opacity: "1.0"}, {duration: 1000, easing: "easeInOutQuad", complete: function() {
									setTimeout(function(){$("#setup_modal_notification_container").animate({opacity: "0.0"}, {duration: 1000, easing: "easeInOutQuad", complete: function() {}});}, 2000);
								}});
								$.post("get_user_info.php", {id: '.$this->user_id.'}, function(data, status)
								{
									var info = JSON.parse(data);
									if(info.hasOwnProperty("cards") && info["cards"].length > 0)
									{
										load_credit_cards(info["cards"]);
									}
								});
							}
							else
							{
								$("#setup_modal_notification").css("background-color", "#D5483A");
								$("#setup_modal_notification").html("<p style=\"color: #fff;\">Unable To Add Card</p>");
								$("#setup_modal_notification_container").animate({opacity: "1.0"}, {duration: 1000, easing: "easeInOutQuad", complete: function() {
									setTimeout(function(){$("#setup_modal_notification_container").animate({opacity: "0.0"}, {duration: 1000, easing: "easeInOutQuad", complete: function() {}});}, 2000);
								}});
							}
						});
					}
				};
				
				function stripeResponseHandlerDoctor(status, response) {
        
					if (response.error) 
					{
						$("#setup_modal_notification").css("background-color", "#D5483A");
						$("#setup_modal_notification").html("<p style=\"color: #fff;\">"+response.error.message+"</p>");
						$("#setup_modal_notification_container").animate({opacity: "1.0"}, {duration: 1000, easing: "easeInOutQuad", complete: function() {
							setTimeout(function(){$("#setup_modal_notification_container").animate({opacity: "0.0"}, {duration: 1000, easing: "easeInOutQuad", complete: function() {}});}, 2000);
						}});
					} 
					else 
					{
						$("#setup_modal_notification").html("<img src=\"images/load/8.gif\" alt=\"\">");
						$("#setup_modal_notification").css("background-color", "#FFF");
						$("#setup_modal_notification_container").css("opacity", "1.0");
						$.post("change_credit_card.php", {type: "1", action: "3", id: '.$this->med_id.', token: response.id}, function(data, status)
						{
						swal("Credit Card Added!", "You have successfully added a credit card to Dr. '.$this->doctor_name.' '.$this->doctor_surname.'\'s account.", "success")
							//console.log(data);
							$("#setup_modal_notification_container").css("opacity", "0.0");
							if(data == "1")
							{
								$("#setup_modal_notification").css("background-color", "#52D859");
								$("#setup_modal_notification").html("<p style=\"color: #fff;\">Credit Card Added!</p>");
								$("#setup_modal_notification_container").animate({opacity: "1.0"}, {duration: 1000, easing: "easeInOutQuad", complete: function() {
									setTimeout(function(){$("#setup_modal_notification_container").animate({opacity: "0.0"}, {duration: 1000, easing: "easeInOutQuad", complete: function() {}});}, 2000);
								}});
								$.post("get_doctor_credit_cards.php", {id: '.$this->med_id.'}, function(data, status)
								{
									var info = JSON.parse(data);
									if(info.hasOwnProperty("cards") && info["cards"].length > 0)
									{
										load_credit_cards_doctor(info["cards"]);
									}
								});
							}
							else
							{
								$("#setup_modal_notification").css("background-color", "#D5483A");
								$("#setup_modal_notification").html("<p style=\"color: #fff;\">Unable To Add Card</p>");
								$("#setup_modal_notification_container").animate({opacity: "1.0"}, {duration: 1000, easing: "easeInOutQuad", complete: function() {
									setTimeout(function(){$("#setup_modal_notification_container").animate({opacity: "0.0"}, {duration: 1000, easing: "easeInOutQuad", complete: function() {}});}, 2000);
								}});
							}
						});
					}
				};
				
				function select_card(stripe_id, cc_num, cc_icon){
					$("#stripe-id-holder").val(stripe_id);
					$("#review-cc-number").html("<span style=\"color: white; font-size: 18px;\">You have selected </span><img src=\""+cc_icon+"\" style=\"height: 38px;\" /><span style=\"color: white; font-size: 18px;\"> card ending in "+cc_num+".</span>");
					$(".probe_cc_section").hide();
					$("#probe-review-section").show();
					$("#payment-review-section").show();
					swal("Credit Card Selected!", ("You have selected card ending in ("+cc_num+")."), "success");
					$("#payment-type").val(0);
				}
				
				function select_card_doctor(stripe_id, cc_num, cc_icon){
					$("#stripe-id-holder-doctor").val(stripe_id);
					$("#review-cc-number").html("<span>You have selected </span><img src=\""+cc_icon+"\" style=\"height: 38px;\" /><span> card ending in </span>"+cc_num+".");
					swal("Credit Card Selected!", ("You have selected card ending in ("+cc_num+")."), "success");
					$(".doctor_cc_section").hide();
					$("#doctor-cc-review").show();
					$("#review-doctor-cc-number").html("<span>You have selected </span><img src=\""+cc_icon+"\" style=\"height: 38px;\" /><span> card ending in </span>"+cc_num+".");
					$("#purchase-credits-review").show();
				}
				
				function select_doctor_credits(){
					$(".probe_cc_section").hide();
					$("#drcredits-review-section").show();
					$("#credits-review-section").show();
					$("#probe-review-section").hide();
					$("#payment-review-section").hide();
					swal("Utilizing doctor credits!", ("You have chosen to use doctor credits in place of member\'s credit card."), "success");
					$("#payment-type").val(1);
					$("#check-out-button-final").removeAttr("disabled");
				}
				
				function change_cards(){
					$(".probe_cc_section").show();
					$("#probe-review-section").hide();
					$("#stripe-id-holder").val("");
					$("#payment-review-section").hide();
					$("#credits-review-section").hide();
					$("#drcredits-review-section").hide();
					$("#check-out-button-final").removeAttr("disabled");
					swal("Utilizing member credit card!", ("You have chosen to use member\'s credit card as payment."), "success");
				}
				
				function change_cards_doctor(){
					$("#doctor-cc-review").hide();
					$(".doctor_cc_section").show();
					$("#purchase-credits-review").hide();
				}
				
				function calculate_total(){
					var months = $("#month-count").val();
					var sub_total = "$"+(months * '.(($this->doctor_base_price + 1200) / 100).');
					var total = "$"+(months * '.(($this->doctor_base_price + 1200) / 100).');
					$("#display-months").text(months+" Months");
					$("#display-sub-total").text(sub_total);
					$("#display-total").text(total);
				}
				
				function display_purchase_credits(){
					var purchase_credits_doctor = $("#purchase-credits-doctor").dialog({bgiframe: true, width: 600, height: 300, autoOpen: false, modal: true,title:"Purchase credits for Dr. '.$this->doctor_name.' '.$this->doctor_surname.'"});
					purchase_credits_doctor.dialog("open");
				}
				
				function display_months_window(){
					var display_months_window = $("#get-temp-months").dialog({bgiframe: true, width: 600, height: 200, autoOpen: false, modal: true,title:"Slate for how many months?"});
					display_months_window.dialog("open");
				}
				
				function assign_later(){
					var months = $("#temp-months-holder").val();
					var charge_calc = months * 1500;
					var total = (months * 1500)+" tokens";';
							
					if($this->pre_selected_probe == 0){
						echo 'var protocol = $("#probe_protocols").val();
						var probe_time = $("#probe_time").val();
						var probe_timezone = $("#probe_timezone").val();
						var probe_method = $("#probe_method").val();
						var probe_interval = $("#probe_interval").val();
						var probe_name = $("#probe_protocols").text();
						var probe_time_display = get_time_from_int(probe_time);
						var update_email = "'.$this->member_email.'";
						var update_phone = "'.$this->member_phone.'";';
					}else{
						echo 'var protocol = '.$this->probe_id.';
						var probe_time_display = "'.$this->probe_time.'";
						var probe_timezone = '.$this->probe_timezone.';
						var probe_method = '.$this->probe_method.';
						var probe_interval = '.$this->probe_interval.';
						var probe_name = "'.$this->probe_data['name'].'";
						var update_email = "'.$this->member_email.'";
						var update_phone = "'.$this->member_phone.'";';
					}
							
							
					echo 'var time = "";
					if(probe_interval == 1){
						time = "Daily";
					}else if(probe_interval == 7){
						time = "Weekly";
					}else if(probe_interval == 30){
						time = "Monthly";
					}else if(probe_interval == 365){
						time = "Yearly";
					}
							
					var method = "";
					if(probe_method == 1){
						method = "Text Message";
					}else if(probe_method == 2){
						method = "Phone Call";
					}else{
						method = "Email";
					}
							
					var timezone= "";
					if(probe_timezone == 1){
						timezone = "US Eastern Time";
					}else if(probe_timezone == 2){
						timezone = "US Central Time";
					}else if(probe_timezone == 3){
						timezone = "US Pacific Time";
					}else if(probe_timezone == 4){
						timezone = "US Mountain Time";
					}else if(probe_timezone == 5){
						timezone = "Europe Central Time";
					}else if(probe_timezone == 6){
						timezone = "Greenwich Mean Time";
					}
							
							
							
							
					swal({   
					title: "Checking out member '.$this->member_name.' '.$this->member_surname.'!",   
					text: ("To summarize... \n Member: '.$this->member_name.' '.$this->member_surname.' \n Email: "+update_email+" \n Phone: "+update_phone+" \n Will not be charged now. \n\n Probe: "+probe_name+" \n For "+months+" Months \n At: "+probe_time_display+" \n Timezone: "+timezone+" \n Contact: By "+method+" \n Interval: "+time+""),   type: "warning",   showCancelButton: true,   confirmButtonColor: "#DD6B55",   confirmButtonText: "Yes, complete checkout!",   closeOnConfirm: true }, 
					function(){ 
						$.post("member_invitation_checkout.php", {paid: true, IdMed: '.$this->med_id.', IdUsu: '.$this->user_id.', email: update_email, phone: update_phone, probe: protocol, probe_time: probe_time_display, probe_timezone: probe_timezone, probe_method: probe_method, probe_interval: probe_interval, end_date: months, send:"no"}, function(data, status)
						{
						});
							
						setTimeout(function(){
						swal({title:"Probe Has Been Queued", 
						text:"The probe has been successfully Queued for this member.  This member will have the ability to purchase this probe at a later date.", 
						type:"success", 
						confirmButtonColor: "#DD6B55",   
						confirmButtonText: "Okay",   
						closeOnConfirm: true}); }, 500);
					});
				}
				
				function check_out_button(){
					var remaining_credits = $("#doctor-remaining-credits-count").val();
					var payment_type = $("#payment-type").val();
					if(payment_type == 1){
						if(remaining_credits < 0){
							swal({   title: "Insufficient credits!",   text: "You do not have enough credits to purchase this probe.  Would you like to purchase some credits now?",   type: "error",   showCancelButton: true,   confirmButtonColor: "#DD6B55",   confirmButtonText: "Yes, purchase credits!",   closeOnConfirm: true }, 
							function(){ var purchase_credits_doctor = $("#purchase-credits-doctor").dialog({bgiframe: true, width: 600, height: 300, autoOpen: false, modal: true,title:"Purchase credits for Dr. '.$this->doctor_name.' '.$this->doctor_surname.'"});purchase_credits_doctor.dialog("open"); });
						}else{
							var months = $("#month-count-credits").val();
							var charge_calc = months * 1200;
							var total = "$"+(months * 12)+" credits";';
							
							if($this->pre_selected_probe == 0){
								echo 'var protocol = $("#probe_protocols").val();
								var probe_time = $("#probe_time").val();
								var probe_timezone = $("#probe_timezone").val();
								var probe_method = $("#probe_method").val();
								var probe_interval = $("#probe_interval").val();
								var probe_name = $("#probe_protocols").text();
								var probe_time_display = get_time_from_int(probe_time);
								var update_email = "'.$this->member_email.'";
								var update_phone = "'.$this->member_phone.'";';
							}else{
								echo 'var protocol = '.$this->probe_id.';
								var probe_time_display = "'.$this->probe_time.'";
								var probe_timezone = '.$this->probe_timezone.';
								var probe_method = '.$this->probe_method.';
								var probe_interval = '.$this->probe_interval.';
								var probe_name = "'.$this->probe_data['name'].'";
								var update_email = "'.$this->member_email.'";
								var update_phone = "'.$this->member_phone.'";';
							}
							
							
							echo 'var time = "";
							if(probe_interval == 1){
								time = "Daily";
							}else if(probe_interval == 7){
								time = "Weekly";
							}else if(probe_interval == 30){
								time = "Monthly";
							}else if(probe_interval == 365){
								time = "Yearly";
							}
							
							var method = "";
							if(probe_method == 1){
								method = "Text Message";
							}else if(probe_method == 2){
								method = "Phone Call";
							}else{
								method = "Email";
							}
							
							var timezone= "";
							if(probe_timezone == 1){
								timezone = "US Eastern Time";
							}else if(probe_timezone == 2){
								timezone = "US Central Time";
							}else if(probe_timezone == 3){
								timezone = "US Pacific Time";
							}else if(probe_timezone == 4){
								timezone = "US Mountain Time";
							}else if(probe_timezone == 5){
								timezone = "Europe Central Time";
							}else if(probe_timezone == 6){
								timezone = "Greenwich Mean Time";
							}
							
							
							
							
							swal({   
							title: "Checking out member '.$this->member_name.' '.$this->member_surname.'!",   
							text: ("To summarize... \n Member: '.$this->member_name.' '.$this->member_surname.' \n Email: "+update_email+" \n Phone: "+update_phone+" \n Will be charged a total: "+total+" \n\n Probe: "+probe_name+" \n For "+months+" Months \n At: "+probe_time_display+" \n Timezone: "+timezone+" \n Contact: By "+method+" \n Interval: "+time+""),   type: "warning",   showCancelButton: true,   confirmButtonColor: "#DD6B55",   confirmButtonText: "Yes, complete checkout!",   closeOnConfirm: true }, 
							function(){ 
								$.post("spendDoctorHealthies.php", {user_id: "'.$this->user_id.'", doc_id: "'.$this->med_id.'", quantity: charge_calc, months:months}).done( function(data, status)
								{
									$.post("member_invitation_checkout.php", {paid: true, IdMed: '.$this->med_id.', IdUsu: '.$this->user_id.', email: update_email, phone: update_phone, probe: protocol, probe_time: probe_time_display, probe_timezone: probe_timezone, probe_method: probe_method, probe_interval: probe_interval, end_date: months}, function(data, status)
									{
									});
									$("#checkout-window").dialog("close");
									setTimeout(function(){
									swal({title:"Probe Purchased", 
									text:"The probe has been successfully purchased for this member.", 
									type:"success", 
									confirmButtonColor: "#DD6B55",   
									confirmButtonText: "Okay",   
									closeOnConfirm: true},
									function(){

									}); }, 500);
								}).error(function(data){
									swal("The charge was rejected!");
								});
							});
						}
					}else{
						var months = $("#month-count").val();
						var charge_calc = months * '.($this->doctor_base_price + 1200).';
						var total = "$"+(months * '.(($this->doctor_base_price + 1200) / 100).');';
						
						if($this->pre_selected_probe == 0){
							echo 'var protocol = $("#probe_protocols").val();
							var probe_time = $("#probe_time").val();
							var probe_timezone = $("#probe_timezone").val();
							var probe_method = $("#probe_method").val();
							var probe_interval = $("#probe_interval").val();
							var probe_name = $("#probe_protocols").text();
							var probe_time_display = get_time_from_int(probe_time);
							var update_email = "'.$this->member_email.'";
							var update_phone = "'.$this->member_phone.'";';
						}else{
							echo 'var protocol = '.$this->probe_id.';
							var probe_time_display = "'.$this->probe_time.'";
							var probe_timezone = '.$this->probe_timezone.';
							var probe_method = '.$this->probe_method.';
							var probe_interval = '.$this->probe_interval.';
							var probe_name = "'.$this->probe_data['name'].'";
							var update_email = "'.$this->member_update_email.'";
							var update_phone = "'.$this->member_update_phone.'";';
						}
						
						
						echo 'var time = "";
						if(probe_interval == 1){
							time = "Daily";
						}else if(probe_interval == 7){
							time = "Weekly";
						}else if(probe_interval == 30){
							time = "Monthly";
						}else if(probe_interval == 365){
							time = "Yearly";
						}
						
						var method = "";
						if(probe_method == 1){
							method = "Text Message";
						}else if(probe_method == 2){
							method = "Phone Call";
						}else{
							method = "Email";
						}
						
						var timezone= "";
						if(probe_timezone == 1){
							timezone = "US Eastern Time";
						}else if(probe_timezone == 2){
							timezone = "US Central Time";
						}else if(probe_timezone == 3){
							timezone = "US Pacific Time";
						}else if(probe_timezone == 4){
							timezone = "US Mountain Time";
						}else if(probe_timezone == 5){
							timezone = "Europe Central Time";
						}else if(probe_timezone == 6){
							timezone = "Greenwich Mean Time";
						}
						
						
						
						
						swal({   
						title: "Checking out member '.$this->member_name.' '.$this->member_surname.'!",   
						text: ("To summarize... \n Member: '.$this->member_name.' '.$this->member_surname.' \n Email: "+update_email+" \n Phone: "+update_phone+" \n Will be charged a total: "+total+" \n\n Probe: "+probe_name+" \n For "+months+" Months \n At: "+probe_time_display+" \n Timezone: "+timezone+" \n Contact: By "+method+" \n Interval: "+time+""),   type: "warning",   showCancelButton: true,   confirmButtonColor: "#DD6B55",   confirmButtonText: "Yes, complete checkout!",   closeOnConfirm: true }, 
						function(){ 
							$.post("memberCardPurchase.php", {user_id: "'.$this->user_id.'", doc_id: "'.$this->med_id.'", quantity: charge_calc, months:months}).done( function(data, status)
							{
								$.post("member_invitation_checkout.php", {paid: true, IdMed: '.$this->med_id.', IdUsu: '.$this->user_id.', email: update_email, phone: update_phone, probe: protocol, probe_time: probe_time_display, probe_timezone: probe_timezone, probe_method: probe_method, probe_interval: probe_interval, end_date: months}, function(data, status)
								{
								});
								$("#checkout-window").dialog("close");
								setTimeout(function(){
								swal({title:"Probe Purchased", 
								text:"The probe has been successfully purchased for this member.", 
								type:"success", 
								confirmButtonColor: "#DD6B55",   
								confirmButtonText: "Okay",   
								closeOnConfirm: true},
								function(){
									
								}); }, 500);
							}).error(function(data){
								swal("The charge was rejected!");
							});
						});
					}
				}
				
				function calculate_total_credits(){
					var months = $("#month-count-credits").val();
					var sub_total = (months * 12)+" credits";
					var total = (months * 12)+" credits";
					var doctor_credits = $("#doctor-credits-count").val();
					var remaining_credits = Math.round(((doctor_credits/100) - (months * 12)) * 100) / 100;
					$("#display-months-credits").text(months+" Months");
					$("#display-sub-credits").text(sub_total);
					$("#display-total-credits").text(total);
					$("#display-credits-remaining").text(remaining_credits);
					$("#doctor-remaining-credits-count").val(remaining_credits);
					if(remaining_credits < 0){
						$("#display-credits-remaining").css("color", "red");
					}
				}
				
				Stripe.setPublishableKey("pk_test_YBtrxG7xwZU9RO1VY8SeaEe9");
				function load_credit_cards(cards)
				{
					$("#credit_cards_container").html("");
					for(var i = 0; i < cards.length; i++)
					{
						var html = "";
						var stripe_holder = cards[i]["stripe_id"];
						stripe_holder = JSON.stringify(stripe_holder).replace(/&/, "&amp;").replace(/"/g, "&quot;");
						var cc_spot_holder = cards[i]["number"];
						cc_spot_holder = JSON.stringify(cc_spot_holder).replace(/&/, "&amp;").replace(/"/g, "&quot;");
						var cc_icon = cards[i]["icon"];
						cc_icon = JSON.stringify(cc_icon).replace(/&/, "&amp;").replace(/"/g, "&quot;");
						html += "<div onclick=\"select_card("+stripe_holder+", "+cc_spot_holder+", "+cc_icon+");\" class=\"credit_card_row\"";
						if(i == 0 && i == cards.length - 1)
						{
							html += " style=\"border-radius: 10px;\"";
						}
						else if(i == 0)
						{
							html += " style=\"border-top-left-radius: 10px; border-top-right-radius: 10px;\"";
						}
						else if(i == cards.length - 1)
						{
							html += " style=\"border-bottom-left-radius: 10px; border-bottom-right-radius: 10px;\"";
						}
						html += ">";
						html += "</button>"
						html += "<img src=\""+cards[i]["icon"]+"\" style=\"float: left; margin-left: 325px; height: 38px;\" />";
						html += "<span style=\"float: left; color: #5A5A5A; font-size: 14px; margin-left: 60px; margin-top: 8px;\">****   ****   ****   "+cards[i]["number"]+"</span>";
						html += "</div>";
						$("#credit_cards_container").append(html);
					}
					$("button[id^=\"clear-credit-card\"]").on("click", function()
					{
						var card_id = $(this).attr("id").split("-")[3];
						$("#setup_modal_notification").html("<img src=\"images/load/8.gif\" alt=\"\">");
						$("#setup_modal_notification").css("background-color", "#FFF");
						$("#setup_modal_notification_container").css("opacity", "1.0");
						$.post("change_credit_card.php", {type: "2", action: "2", id: '.$this->user_id.', card_id: card_id}, function(data, status)
						{
							//console.log(data);
							$("#setup_modal_notification_container").css("opacity", "0.0");
							if(data == "1")
							{
								$("#setup_modal_notification").css("background-color", "#52D859");
								$("#setup_modal_notification").html("<p style=\"color: #fff;\">Credit Card Removed!</p>");
								$("#setup_modal_notification_container").animate({opacity: "1.0"}, {duration: 1000, easing: "easeInOutQuad", complete: function() {
									setTimeout(function(){$("#setup_modal_notification_container").animate({opacity: "0.0"}, {duration: 1000, easing: "easeInOutQuad", complete: function() {}});}, 2000);
								}});
								$.post("get_user_info.php", {id: '.$this->user_id.'}, function(data, status)
								{
									var info = JSON.parse(data);
									if(info.hasOwnProperty("cards") && info["cards"].length > 0)
									{
										load_credit_cards(info["cards"]);
									}
								});
							}
							else if(data.substr(0, 2) == "IC")
							{
								alert("You are currently in a consultation, please wait until the consultation is over to delete credit cards.");
							}
							else
							{
								$("#setup_modal_notification").css("background-color", "#D5483A");
								$("#setup_modal_notification").html("<p style=\"color: #fff;\">Unable To Remove Card</p>");
								$("#setup_modal_notification_container").animate({opacity: "1.0"}, {duration: 1000, easing: "easeInOutQuad", complete: function() {
									setTimeout(function(){$("#setup_modal_notification_container").animate({opacity: "0.0"}, {duration: 1000, easing: "easeInOutQuad", complete: function() {}});}, 2000);
								}});
							}
						});
					});
				}
				
				function load_credit_cards_doctor(cards)
				{
					$("#credit_cards_container_doctor").html("");
					for(var i = 0; i < cards.length; i++)
					{
						var html = "";
						var stripe_holder = cards[i]["stripe_id"];
						stripe_holder = JSON.stringify(stripe_holder).replace(/&/, "&amp;").replace(/"/g, "&quot;");
						var cc_spot_holder = cards[i]["number"];
						cc_spot_holder = JSON.stringify(cc_spot_holder).replace(/&/, "&amp;").replace(/"/g, "&quot;");
						var cc_icon = cards[i]["icon"];
						cc_icon = JSON.stringify(cc_icon).replace(/&/, "&amp;").replace(/"/g, "&quot;");
						html += "<div onclick=\"select_card_doctor("+stripe_holder+", "+cc_spot_holder+", "+cc_icon+");\" class=\"credit_card_row\"";
						if(i == 0 && i == cards.length - 1)
						{
							html += " style=\"border-radius: 10px;\"";
						}
						else if(i == 0)
						{
							html += " style=\"border-top-left-radius: 10px; border-top-right-radius: 10px;\"";
						}
						else if(i == cards.length - 1)
						{
							html += " style=\"border-bottom-left-radius: 10px; border-bottom-right-radius: 10px;\"";
						}
						html += ">";
						html += "</button>"
						html += "<img src=\""+cards[i]["icon"]+"\" style=\"float: left; margin-left: 150px; height: 38px;\" />";
						html += "<span style=\"float: left; color: #5A5A5A; font-size: 14px; margin-left: 60px; margin-top: 8px;\">****   ****   ****   "+cards[i]["number"]+"</span>";
						html += "</div>";
						$("#credit_cards_container_doctor").append(html);
					}
					$("button[id^=\"clear-credit-card\"]").on("click", function()
					{
						var card_id = $(this).attr("id").split("-")[3];
						$("#setup_modal_notification").html("<img src=\"images/load/8.gif\" alt=\"\">");
						$("#setup_modal_notification").css("background-color", "#FFF");
						$("#setup_modal_notification_container").css("opacity", "1.0");
						$.post("change_credit_card.php", {type: "1", action: "2", id: '.$this->med_id.', card_id: card_id}, function(data, status)
						{
							//console.log(data);
							$("#setup_modal_notification_container").css("opacity", "0.0");
							if(data == "1")
							{
								$("#setup_modal_notification").css("background-color", "#52D859");
								$("#setup_modal_notification").html("<p style=\"color: #fff;\">Credit Card Removed!</p>");
								$("#setup_modal_notification_container").animate({opacity: "1.0"}, {duration: 1000, easing: "easeInOutQuad", complete: function() {
									setTimeout(function(){$("#setup_modal_notification_container").animate({opacity: "0.0"}, {duration: 1000, easing: "easeInOutQuad", complete: function() {}});}, 2000);
								}});
								$.post("get_doctor_credit_cards", {id: '.$this->med_id.'}, function(data, status)
								{
									var info = JSON.parse(data);
									if(info.hasOwnProperty("cards") && info["cards"].length > 0)
									{
										load_credit_cards_doctor(info["cards"]);
									}
								});
							}
							else if(data.substr(0, 2) == "IC")
							{
								alert("You are currently in a consultation, please wait until the consultation is over to delete credit cards.");
							}
							else
							{
								$("#setup_modal_notification").css("background-color", "#D5483A");
								$("#setup_modal_notification").html("<p style=\"color: #fff;\">Unable To Remove Card</p>");
								$("#setup_modal_notification_container").animate({opacity: "1.0"}, {duration: 1000, easing: "easeInOutQuad", complete: function() {
									setTimeout(function(){$("#setup_modal_notification_container").animate({opacity: "0.0"}, {duration: 1000, easing: "easeInOutQuad", complete: function() {}});}, 2000);
								}});
							}
						});
					});
				}
			</script>';
		}
		
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		if($display_type == 'member'){
			echo ' <script type="text/javascript">
				var probe_question_en = ["", "", "", "", ""];
				var probe_question_es = ["", "", "", "", ""];
				var probe_min = [1, 1, 1, 1, 1];
				var probe_max = [5, 5, 5, 5, 5];
				var probe_answer_type = [1, 1, 1, 1, 1];
				var probe_question = 1;
				var selected_probe = -1;
				var english_questions = null;
				var spanish_questions = null;
				var mins = null;
				var maxs = null;
				var answers = null;
				load_probe_protocols();
				
				function send_probe_toggle(){
					if($("input:radio[name=probe_radio]:checked").val() == "yes"){
						$("#initial-probe-buttons").show();
						$(".probe_info_section").show();
					}else{
						$("#initial-probe-buttons").hide();
						$(".probe_info_section").hide();
					}
				};
				
				function load_probe_protocols()
				{
					$.post("get_probe_protocols.php", {doctor: '.$this->med_id.'}, function(data, status)
					{
						var d = JSON.parse(data);
						console.log(d);
						var var_length = d.length;
						$("#probe_protocols").html("");
						$("#probes_container").html("");
						for(var i = 0; i < var_length; i++)
						{
							$("#probe_protocols").append("<option value=\""+d[i].protocolID+"\">"+d[i].name+"</option>");
							var html = "<div class=\"probes_row\">";
							html += "<div style=\"float: left; width: 40%; margin-right: 3%; overflow: hidden; white-space: nowrap; text-overflow: ellipsis; font-weight: bold;\">"+d[i].name+"</div>";
							html += "<div style=\"float: left; width: 40%; margin-right: 3%; overflow: hidden; white-space: nowrap; text-overflow: ellipsis;\">"+d[i].description+"</div>";
							html += "<button class=\"probes_edit_button\" id=\"probes_edit_button_"+d[i].protocolID+"\"><i class=\"icon-pencil\"></i></button>";
							html += "<button class=\"probes_delete_button\" id=\"probes_delete_button_"+d[i].protocolID+"\">X</button>";
							html += "</div>";
							$("#probes_container").append(html);
						}
						if(var_length == 0)
						{
							$("#probe_protocols").append("<option>No probes defined.  Please add a probe below.</option>");
							//console.log("no probes");
							var html = "<div class=\"no_probes_notification\">";
							html += "You do not have any probes defined.<br/>To add probes, click the "+" button bellow.";
							html += "</div>";
							$("#probes_container").append(html);
						}
					});
				}
				
				function purchasecredits(){
					var credits = $("#calc-purchase-credits").val();
					var total = "$"+(credits);
					swal({   title: "Purchase credits?",   text: ("Are you sure you would like to purchase "+credits+" credits? \n Cost: "+total+""),   type: "warning",   showCancelButton: true,   confirmButtonColor: "#DD6B55",   confirmButtonText: "Yes, purchase credits!",   closeOnConfirm: false }, 
					function(){   
						swal("Purchased!", ("You have successfully purchased "+credits+" credits."), "success");
						$.post("purchaseCredits.php", {doctor: '.$this->med_id.', quantity: (credits*100)}).done(function(data, status)
						{
							var old_count = $("#doctor-credits-count").val();
							var credits_remaining = $("#doctor-remaining-credits-count").val();
							var count = parseInt(old_count / 100)+parseInt(credits);
							var update_remaining = Math.round((credits_remaining + credits) * 100) / 100;
							$("#doctor-credits-count").val(count);
							$("#display-doctor-credits").text(count);
							$("#display-credits-remaining").text(update_remaining);
							$("#doctor-remaining-credits-count").val(update_remaining);
							if(update_remaining < 0){
								$("#display-credits-remaining").css("color", "red");
							}else{
								$("#display-credits-remaining").css("color", "black");
							}
						}).error(function(){
								swal("The charge was rejected!");
						});	
						 
					});
				}
				
				function add_credit_card()
				{
					var date = $("#credit_card_exp_date").val().split("-");
					Stripe.card.createToken({
						number: $("#credit_card_number").val(),
						cvc: $("#credit_card_csv_code").val(),
						exp_month: date[1],
						exp_year: date[0]
					}, stripeResponseHandler);
					
				}
				
				function calcPurchasecredits(){
					var amount = $("#calc-purchase-credits").val();
					var total = "$"+(amount);
					$("#doctor-credits-purchase-total").text(total);
				}
				
				function add_credit_card_doctor()
				{
					var date = $("#credit_card_exp_date_doctor").val().split("-");
					Stripe.card.createToken({
						number: $("#credit_card_number_doctor").val(),
						cvc: $("#credit_card_csv_code_doctor").val(),
						exp_month: date[1],
						exp_year: date[0]
					}, stripeResponseHandlerDoctor);
					
				}
				
				function get_time_from_int(pass_int){
					var time = "";
					
					switch(pass_int){
						case "0":
							time = "12:00am";
							break;
						case "0.5":
							time = "12:30am";
							break;
						case "1":
							time = "1:00am";
							break;
						case "1.5":
							time = "1:30am";
							break;
						case "2":
							time = "2:00am";
							break;
						case "2.5":
							time = "2:30am";
							break;
						case "3":
							time = "3:00am";
							break;
						case "3.5":
							time = "3:30am";
							break;
						case "4":
							time = "4:00am";
							break;
						case "4.5":
							time = "4:30am";
							break;
						case "5":
							time = "5:00am";
							break;
						case "5.5":
							time = "5:30am";
							break;
						case "6":
							time = "6:00am";
							break;
						case "6.5":
							time = "6:30am";
							break;
						case "7":
							time = "7:00am";
							break;
						case "7.5":
							time = "7:30am";
							break;
						case "8":
							time = "8:00am";
							break;
						case "8.5":
							time = "8:30am";
							break;
						case "9":
							time = "9:00am";
							break;
						case "9.5":
							time = "9:30am";
							break;
						case "10":
							time = "10:00am";
							break;
						case "10.5":
							time = "10:30am";
							break;
						case "11":
							time = "11:00am";
							break;
						case "11.5":
							time = "11:30am";
							break;
						case "12":
							time = "12:00pm";
							break;
						case "12.5":
							time = "12:30pm";
							break;
						case "13":
							time = "1:00pm";
							break;
						case "13.5":
							time = "1:30pm";
							break;
						case "14":
							time = "2:00pm";
							break;
						case "14.5":
							time = "2:30pm";
							break;
						case "15":
							time = "3:00pm";
							break;
						case "15.5":
							time = "3:30pm";
							break;
						case "16":
							time = "4:00pm";
							break;
						case "16.5":
							time = "4:30pm";
							break;
						case "17":
							time = "5:00pm";
							break;
						case "17.5":
							time = "5:30pm";
							break;
						case "18":
							time = "6:00pm";
							break;
						case "18.5":
							time = "6:30pm";
							break;
						case "19":
							time = "7:00pm";
							break;
						case "19.5":
							time = "7:30pm";
							break;
						case "20":
							time = "8:00pm";
							break;
						case "20.5":
							time = "8:30pm";
							break;
						case "21":
							time = "9:00pm";
							break;
						case "21.5":
							time = "9:30pm";
							break;
						case "22":
							time = "10:00pm";
							break;
						case "22.5":
							time = "10:30pm";
							break;
						case "23":
							time = "11:00pm";
							break;
						case "23.5":
							time = "11:30pm";
							break;
					}
				
				return time;
				}
				
				$.post("get_user_info.php", {id: '.$this->user_id.'}, function(data, status)
				{
					var info = JSON.parse(data);
					$("#timezone_picker option[value=\"" + info["timezone"] + "\"]").attr("selected", "selected");
					if(info.hasOwnProperty("location") && info["location"].length > 0)
					{
						setTimeout(function(){
							//console.log(info["location"].trim());
							$("#country_setup").val(info["location"]);
							$("#country_setup").change();
						}, 800);
					}else{
						$("#credit_cards_container").html("<center>No credit cards on file for member '.$this->member_name.' '.$this->member_surname.'.</br>Please add a credit card to continue.</center>");
					}
					if(info.hasOwnProperty("location2") && info["location2"].length > 0)
					{
						setTimeout(function(){
							$("#state_setup").val(info["location2"]);
							$("#state_setup").change();
						}, 900);
					}
					if(info.hasOwnProperty("email") && info["email"].length > 0)
					{
						$("#setup_email").val(info["email"]);
					}
					if(info.hasOwnProperty("phone") && info["phone"].length > 0)
					{
						$("#setup_phone").val("+" + info["phone"]);
					}
					if(info.hasOwnProperty("cards") && info["cards"].length > 0)
					{
						load_credit_cards(info["cards"]);
					}
				});
				
				$.post("get_doctor_credit_cards.php", {id: '.$this->med_id.'}, function(data, status)
				{
					var info = JSON.parse(data);
					if(info.hasOwnProperty("cards") && info["cards"].length > 0)
					{
						load_credit_cards_doctor(info["cards"]);
					}else{
						$("#credit_cards_container_doctor").html("<center>No credit cards on file for Dr. '.$this->doctor_name.' '.$this->doctor_surname.'.</br>Please add a credit card to continue.</center>");
					}
				});
				
				function stripeResponseHandler(status, response) {
        
					if (response.error) 
					{
						$("#setup_modal_notification").css("background-color", "#D5483A");
						$("#setup_modal_notification").html("<p style=\"color: #fff;\">"+response.error.message+"</p>");
						$("#setup_modal_notification_container").animate({opacity: "1.0"}, {duration: 1000, easing: "easeInOutQuad", complete: function() {
							setTimeout(function(){$("#setup_modal_notification_container").animate({opacity: "0.0"}, {duration: 1000, easing: "easeInOutQuad", complete: function() {}});}, 2000);
						}});
					} 
					else 
					{
						$("#setup_modal_notification").html("<img src=\"images/load/8.gif\" alt=\"\">");
						$("#setup_modal_notification").css("background-color", "#FFF");
						$("#setup_modal_notification_container").css("opacity", "1.0");
						$.post("change_credit_card.php", {type: "2", action: "1", id: '.$this->user_id.', token: response.id}, function(data, status)
						{
						swal("Credit Card Added!", "You have successfully added a credit card to '.$this->member_name.' '.$this->member_surname.'\'s account.", "success")
							//console.log(data);
							$("#setup_modal_notification_container").css("opacity", "0.0");
							if(data == "1")
							{
								$("#setup_modal_notification").css("background-color", "#52D859");
								$("#setup_modal_notification").html("<p style=\"color: #fff;\">Credit Card Added!</p>");
								$("#setup_modal_notification_container").animate({opacity: "1.0"}, {duration: 1000, easing: "easeInOutQuad", complete: function() {
									setTimeout(function(){$("#setup_modal_notification_container").animate({opacity: "0.0"}, {duration: 1000, easing: "easeInOutQuad", complete: function() {}});}, 2000);
								}});
								$.post("get_user_info.php", {id: '.$this->user_id.'}, function(data, status)
								{
									var info = JSON.parse(data);
									if(info.hasOwnProperty("cards") && info["cards"].length > 0)
									{
										load_credit_cards(info["cards"]);
									}
								});
							}
							else
							{
								$("#setup_modal_notification").css("background-color", "#D5483A");
								$("#setup_modal_notification").html("<p style=\"color: #fff;\">Unable To Add Card</p>");
								$("#setup_modal_notification_container").animate({opacity: "1.0"}, {duration: 1000, easing: "easeInOutQuad", complete: function() {
									setTimeout(function(){$("#setup_modal_notification_container").animate({opacity: "0.0"}, {duration: 1000, easing: "easeInOutQuad", complete: function() {}});}, 2000);
								}});
							}
						});
					}
				};
				
				function stripeResponseHandlerDoctor(status, response) {
        
					if (response.error) 
					{
						$("#setup_modal_notification").css("background-color", "#D5483A");
						$("#setup_modal_notification").html("<p style=\"color: #fff;\">"+response.error.message+"</p>");
						$("#setup_modal_notification_container").animate({opacity: "1.0"}, {duration: 1000, easing: "easeInOutQuad", complete: function() {
							setTimeout(function(){$("#setup_modal_notification_container").animate({opacity: "0.0"}, {duration: 1000, easing: "easeInOutQuad", complete: function() {}});}, 2000);
						}});
					} 
					else 
					{
						$("#setup_modal_notification").html("<img src=\"images/load/8.gif\" alt=\"\">");
						$("#setup_modal_notification").css("background-color", "#FFF");
						$("#setup_modal_notification_container").css("opacity", "1.0");
						$.post("change_credit_card.php", {type: "1", action: "3", id: '.$this->med_id.', token: response.id}, function(data, status)
						{
						swal("Credit Card Added!", "You have successfully added a credit card to Dr. '.$this->doctor_name.' '.$this->doctor_surname.'\'s account.", "success")
							//console.log(data);
							$("#setup_modal_notification_container").css("opacity", "0.0");
							if(data == "1")
							{
								$("#setup_modal_notification").css("background-color", "#52D859");
								$("#setup_modal_notification").html("<p style=\"color: #fff;\">Credit Card Added!</p>");
								$("#setup_modal_notification_container").animate({opacity: "1.0"}, {duration: 1000, easing: "easeInOutQuad", complete: function() {
									setTimeout(function(){$("#setup_modal_notification_container").animate({opacity: "0.0"}, {duration: 1000, easing: "easeInOutQuad", complete: function() {}});}, 2000);
								}});
								$.post("get_doctor_credit_cards", {id: '.$this->med_id.'}, function(data, status)
								{
									var info = JSON.parse(data);
									if(info.hasOwnProperty("cards") && info["cards"].length > 0)
									{
										load_credit_cards_doctor(info["cards"]);
									}
								});
							}
							else
							{
								$("#setup_modal_notification").css("background-color", "#D5483A");
								$("#setup_modal_notification").html("<p style=\"color: #fff;\">Unable To Add Card</p>");
								$("#setup_modal_notification_container").animate({opacity: "1.0"}, {duration: 1000, easing: "easeInOutQuad", complete: function() {
									setTimeout(function(){$("#setup_modal_notification_container").animate({opacity: "0.0"}, {duration: 1000, easing: "easeInOutQuad", complete: function() {}});}, 2000);
								}});
							}
						});
					}
				};
				
				function select_card(stripe_id, cc_num, cc_icon){
					$("#stripe-id-holder").val(stripe_id);
					$("#review-cc-number").html("<span style=\"color: white; font-size: 18px;\">You have selected </span><img src=\""+cc_icon+"\" style=\"height: 38px;\" /><span style=\"color: white; font-size: 18px;\"> card ending in "+cc_num+".</span>");
					$(".probe_cc_section").hide();
					$("#probe-review-section").show();
					$("#payment-review-section").show();
					swal("Credit Card Selected!", ("You have selected card ending in ("+cc_num+")."), "success");
					$("#payment-type").val(0);
				}
				
				function select_card_doctor(stripe_id, cc_num, cc_icon){
					$("#stripe-id-holder-doctor").val(stripe_id);
					$("#review-cc-number").html("<span>You have selected </span><img src=\""+cc_icon+"\" style=\"height: 38px;\" /><span> card ending in </span>"+cc_num+".");
					swal("Credit Card Selected!", ("You have selected card ending in ("+cc_num+")."), "success");
					$(".doctor_cc_section").hide();
					$("#doctor-cc-review").show();
					$("#review-doctor-cc-number").html("<span>You have selected </span><img src=\""+cc_icon+"\" style=\"height: 38px;\" /><span> card ending in </span>"+cc_num+".");
					$("#purchase-credits-review").show();
				}
				
				function select_doctor_credits(){
					$(".probe_cc_section").hide();
					$("#drcredits-review-section").show();
					$("#credits-review-section").show();
					$("#probe-review-section").hide();
					$("#payment-review-section").hide();
					swal("Utilizing doctor credits!", ("You have chosen to use doctor credits in place of member\'s credit card."), "success");
					$("#payment-type").val(1);
					$("#check-out-button-final").removeAttr("disabled");
				}
				
				function change_cards(){
					$(".probe_cc_section").show();
					$("#probe-review-section").hide();
					$("#stripe-id-holder").val("");
					$("#payment-review-section").hide();
					$("#credits-review-section").hide();
					$("#drcredits-review-section").hide();
					$("#check-out-button-final").removeAttr("disabled");
					swal("Utilizing member credit card!", ("You have chosen to use member\'s credit card as payment."), "success");
				}
				
				function change_cards_doctor(){
					$("#doctor-cc-review").hide();
					$(".doctor_cc_section").show();
					$("#purchase-credits-review").hide();
				}
				
				function calculate_total(){
					var months = $("#month-count").val();
					var sub_total = "$"+(months * '.(($this->doctor_base_price + 1200) / 100).');
					var total = "$"+(months * '.(($this->doctor_base_price + 1200) / 100).');
					$("#display-months").text(months+" Months");
					$("#display-sub-total").text(sub_total);
					$("#display-total").text(total);
				}
				
				function display_purchase_credits(){
					var purchase_credits_doctor = $("#purchase-credits-doctor").dialog({bgiframe: true, width: 600, height: 300, autoOpen: false, modal: true,title:"Purchase credits for Dr. '.$this->doctor_name.' '.$this->doctor_surname.'"});
					purchase_credits_doctor.dialog("open");
				}
				
				function display_months_window(){
					var display_months_window = $("#get-temp-months").dialog({bgiframe: true, width: 600, height: 200, autoOpen: false, modal: true,title:"Slate for how many months?"});
					display_months_window.dialog("open");
				}
				
				function assign_later(){
					var months = $("#temp-months-holder").val();
					var charge_calc = months * 1500;
					var total = (months * 1500)+" tokens";';
							
					if($this->pre_selected_probe == 0){
						echo 'var protocol = $("#probe_protocols").val();
						var probe_time = $("#probe_time").val();
						var probe_timezone = $("#probe_timezone").val();
						var probe_method = $("#probe_method").val();
						var probe_interval = $("#probe_interval").val();
						var probe_name = $("#probe_protocols").text();
						var probe_time_display = get_time_from_int(probe_time);
						var update_email = "'.$this->member_email.'";
						var update_phone = "'.$this->member_phone.'";';
					}else{
						echo 'var protocol = '.$this->probe_id.';
						var probe_time_display = "'.$this->probe_time.'";
						var probe_timezone = '.$this->probe_timezone.';
						var probe_method = '.$this->probe_method.';
						var probe_interval = '.$this->probe_interval.';
						var probe_name = "'.$this->probe_data['name'].'";
						var update_email = "'.$this->member_update_email.'";
						var update_phone = "'.$this->member_update_phone.'";';
					}
							
							
					echo 'var time = "";
					if(probe_interval == 1){
						time = "Daily";
					}else if(probe_interval == 7){
						time = "Weekly";
					}else if(probe_interval == 30){
						time = "Monthly";
					}else if(probe_interval == 365){
						time = "Yearly";
					}
							
					var method = "";
					if(probe_method == 1){
						method = "Text Message";
					}else if(probe_method == 2){
						method = "Phone Call";
					}else{
						method = "Email";
					}
							
					var timezone= "";
					if(probe_timezone == 1){
						timezone = "US Eastern Time";
					}else if(probe_timezone == 2){
						timezone = "US Central Time";
					}else if(probe_timezone == 3){
						timezone = "US Pacific Time";
					}else if(probe_timezone == 4){
						timezone = "US Mountain Time";
					}else if(probe_timezone == 5){
						timezone = "Europe Central Time";
					}else if(probe_timezone == 6){
						timezone = "Greenwich Mean Time";
					}
							
							
							
							
					swal({   
					title: "Checking out member '.$this->member_name.' '.$this->member_surname.'!",   
					text: ("To summarize... \n Member: '.$this->member_name.' '.$this->member_surname.' \n Email: "+update_email+" \n Phone: "+update_phone+" \n Will not be charged now. \n\n Probe: "+probe_name+" \n For "+months+" Months \n At: "+probe_time_display+" \n Timezone: "+timezone+" \n Contact: By "+method+" \n Interval: "+time+""),   type: "warning",   showCancelButton: true,   confirmButtonColor: "#DD6B55",   confirmButtonText: "Yes, complete checkout!",   closeOnConfirm: true }, 
					function(){ 
						$.post("member_invitation_checkout.php", {paid: true, IdMed: '.$this->med_id.', IdUsu: '.$this->user_id.', email: update_email, phone: update_phone, probe: protocol, probe_time: probe_time_display, probe_timezone: probe_timezone, probe_method: probe_method, probe_interval: probe_interval, end_date: months, send:"no"}, function(data, status)
						{
						});
							
						setTimeout(function(){
						swal({title:"Probe Has Been Queued", 
						text:"The probe has been successfully Queued for this member.  This member will have the ability to purchase this probe at a later date.", 
						type:"success", 
						confirmButtonColor: "#DD6B55",   
						confirmButtonText: "Okay",   
						closeOnConfirm: true}); }, 500);
					});
				}
				
				function check_out_button(){
					var remaining_credits = $("#doctor-remaining-credits-count").val();
					var payment_type = $("#payment-type").val();
					if(payment_type == 1){
						if(remaining_credits < 0){
							swal({   title: "Insufficient credits!",   text: "You do not have enough credits to purchase this probe.  Would you like to purchase some credits now?",   type: "error",   showCancelButton: true,   confirmButtonColor: "#DD6B55",   confirmButtonText: "Yes, purchase credits!",   closeOnConfirm: true }, 
							function(){ var purchase_credits_doctor = $("#purchase-credits-doctor").dialog({bgiframe: true, width: 600, height: 300, autoOpen: false, modal: true,title:"Purchase credits for Dr. '.$this->doctor_name.' '.$this->doctor_surname.'"});purchase_credits_doctor.dialog("open"); });
						}else{
							var months = $("#month-count-credits").val();
							var charge_calc = months * 1200;
							var total = "$"+(months * 12)+" credits";';
							
							if($this->pre_selected_probe == 0){
								echo 'var protocol = $("#probe_protocols").val();
								var probe_time = $("#probe_time").val();
								var probe_timezone = $("#probe_timezone").val();
								var probe_method = $("#probe_method").val();
								var probe_interval = $("#probe_interval").val();
								var probe_name = $("#probe_protocols").text();
								var probe_time_display = get_time_from_int(probe_time);
								var update_email = "'.$this->member_email.'";
								var update_phone = "'.$this->member_phone.'";';
							}else{
								echo 'var protocol = '.$this->probe_id.';
								var probe_time_display = "'.$this->probe_time.'";
								var probe_timezone = '.$this->probe_timezone.';
								var probe_method = '.$this->probe_method.';
								var probe_interval = '.$this->probe_interval.';
								var probe_name = "'.$this->probe_data['name'].'";
								var update_email = "'.$this->member_update_email.'";
								var update_phone = "'.$this->member_update_phone.'";';
							}
							
							
							echo 'var time = "";
							if(probe_interval == 1){
								time = "Daily";
							}else if(probe_interval == 7){
								time = "Weekly";
							}else if(probe_interval == 30){
								time = "Monthly";
							}else if(probe_interval == 365){
								time = "Yearly";
							}
							
							var method = "";
							if(probe_method == 1){
								method = "Text Message";
							}else if(probe_method == 2){
								method = "Phone Call";
							}else{
								method = "Email";
							}
							
							var timezone= "";
							if(probe_timezone == 1){
								timezone = "US Eastern Time";
							}else if(probe_timezone == 2){
								timezone = "US Central Time";
							}else if(probe_timezone == 3){
								timezone = "US Pacific Time";
							}else if(probe_timezone == 4){
								timezone = "US Mountain Time";
							}else if(probe_timezone == 5){
								timezone = "Europe Central Time";
							}else if(probe_timezone == 6){
								timezone = "Greenwich Mean Time";
							}
							
							
							
							
							swal({   
							title: "Checking out member '.$this->member_name.' '.$this->member_surname.'!",   
							text: ("To summarize... \n Member: '.$this->member_name.' '.$this->member_surname.' \n Email: "+update_email+" \n Phone: "+update_phone+" \n Will be charged a total: "+total+" \n\n Probe: "+probe_name+" \n For "+months+" Months \n At: "+probe_time_display+" \n Timezone: "+timezone+" \n Contact: By "+method+" \n Interval: "+time+""),   type: "warning",   showCancelButton: true,   confirmButtonColor: "#DD6B55",   confirmButtonText: "Yes, complete checkout!",   closeOnConfirm: true }, 
							function(){ 
								$.post("spendDoctorHealthies.php", {user_id: "'.$this->user_id.'", doc_id: "'.$this->med_id.'", quantity: charge_calc, months:months}).done( function(data, status)
								{
									$.post("member_invitation_checkout.php", {paid: true, IdMed: '.$this->med_id.', IdUsu: '.$this->user_id.', email: update_email, phone: update_phone, probe: protocol, probe_time: probe_time_display, probe_timezone: probe_timezone, probe_method: probe_method, probe_interval: probe_interval, end_date: months}, function(data, status)
									{
									});
									$("#checkout-window").dialog("close");
									setTimeout(function(){
									swal({title:"Probe Purchased", 
									text:"The probe has been successfully purchased for this member.", 
									type:"success", 
									confirmButtonColor: "#DD6B55",   
									confirmButtonText: "Okay",   
									closeOnConfirm: true}); }, 500);
								}).error(function(data){
									swal("The charge was rejected!");
								});
							});
						}
					}else{
						var months = $("#month-count").val();
						var charge_calc = months * '.($this->doctor_base_price + 1200).';
						var total = "$"+(months * '.(($this->doctor_base_price + 1200) / 100).')';
						
						if($this->pre_selected_probe == 0){
							echo 'var protocol = $("#probe_protocols").val();
							var probe_time = $("#probe_time").val();
							var probe_timezone = $("#probe_timezone").val();
							var probe_method = $("#probe_method").val();
							var probe_interval = $("#probe_interval").val();
							var probe_name = $("#probe_protocols").text();
							var probe_time_display = get_time_from_int(probe_time);
							var update_email = "'.$this->member_email.'";
							var update_phone = "'.$this->member_phone.'";';
						}else{
							echo 'var protocol = '.$this->probe_id.';
							var probe_time_display = "'.$this->probe_time.'";
							var probe_timezone = '.$this->probe_timezone.';
							var probe_method = '.$this->probe_method.';
							var probe_interval = '.$this->probe_interval.';
							var probe_name = "'.$this->probe_data['name'].'";
							var update_email = "'.$this->member_update_email.'";
							var update_phone = "'.$this->member_update_phone.'";';
						}
						
						
						echo 'var time = "";
						if(probe_interval == 1){
							time = "Daily";
						}else if(probe_interval == 7){
							time = "Weekly";
						}else if(probe_interval == 30){
							time = "Monthly";
						}else if(probe_interval == 365){
							time = "Yearly";
						}
						
						var method = "";
						if(probe_method == 1){
							method = "Text Message";
						}else if(probe_method == 2){
							method = "Phone Call";
						}else{
							method = "Email";
						}
						
						var timezone= "";
						if(probe_timezone == 1){
							timezone = "US Eastern Time";
						}else if(probe_timezone == 2){
							timezone = "US Central Time";
						}else if(probe_timezone == 3){
							timezone = "US Pacific Time";
						}else if(probe_timezone == 4){
							timezone = "US Mountain Time";
						}else if(probe_timezone == 5){
							timezone = "Europe Central Time";
						}else if(probe_timezone == 6){
							timezone = "Greenwich Mean Time";
						}
						
						
						
						
						swal({   
						title: "Checking out member '.$this->member_name.' '.$this->member_surname.'!",   
						text: ("To summarize... \n Member: '.$this->member_name.' '.$this->member_surname.' \n Email: "+update_email+" \n Phone: "+update_phone+" \n Will be charged a total: "+total+" \n\n Probe: "+probe_name+" \n For "+months+" Months \n At: "+probe_time_display+" \n Timezone: "+timezone+" \n Contact: By "+method+" \n Interval: "+time+""),   type: "warning",   showCancelButton: true,   confirmButtonColor: "#DD6B55",   confirmButtonText: "Yes, complete checkout!",   closeOnConfirm: true }, 
						function(){ 
							$.post("memberCardPurchase.php", {user_id: "'.$this->user_id.'", doc_id: "'.$this->med_id.'", quantity: charge_calc, months:months}).done( function(data, status)
							{
								$.post("member_invitation_checkout.php", {paid: true, IdMed: '.$this->med_id.', IdUsu: '.$this->user_id.', email: update_email, phone: update_phone, probe: protocol, probe_time: probe_time_display, probe_timezone: probe_timezone, probe_method: probe_method, probe_interval: probe_interval, end_date: months}, function(data, status)
								{
								});
								$("#checkout-window").dialog("close");
								setTimeout(function(){
								swal({title:"Probe Purchased", 
								text:"The probe has been successfully purchased for this member.", 
								type:"success", 
								confirmButtonColor: "#DD6B55",   
								confirmButtonText: "Okay",   
								closeOnConfirm: true}); }, 500);
							}).error(function(data){
								swal("The charge was rejected!");
							});
						});
					}
				}
				
				function calculate_total_credits(){
					var months = $("#month-count-credits").val();
					var sub_total = (months * 15)+" credits";
					var total = (months * 15)+" credits";
					var doctor_credits = $("#doctor-credits-count").val();
					var update_remaining = Math.round((credits_remaining + credits) * 100) / 100;
					$("#display-months-credits").text(months+" Months");
					$("#display-sub-credits").text(sub_total);
					$("#display-total-credits").text(total);
					$("#display-credits-remaining").text(remaining_credits);
					$("#doctor-remaining-credits-count").val(remaining_credits);
					if(remaining_credits < 0){
						$("#display-credits-remaining").css("color", "red");
					}
				}
				
				Stripe.setPublishableKey("pk_test_YBtrxG7xwZU9RO1VY8SeaEe9");
				function load_credit_cards(cards)
				{
					$("#credit_cards_container").html("");
					for(var i = 0; i < cards.length; i++)
					{
						var html = "";
						var stripe_holder = cards[i]["stripe_id"];
						stripe_holder = JSON.stringify(stripe_holder).replace(/&/, "&amp;").replace(/"/g, "&quot;");
						var cc_spot_holder = cards[i]["number"];
						cc_spot_holder = JSON.stringify(cc_spot_holder).replace(/&/, "&amp;").replace(/"/g, "&quot;");
						var cc_icon = cards[i]["icon"];
						cc_icon = JSON.stringify(cc_icon).replace(/&/, "&amp;").replace(/"/g, "&quot;");
						html += "<div onclick=\"select_card("+stripe_holder+", "+cc_spot_holder+", "+cc_icon+");\" class=\"credit_card_row\"";
						if(i == 0 && i == cards.length - 1)
						{
							html += " style=\"border-radius: 10px;\"";
						}
						else if(i == 0)
						{
							html += " style=\"border-top-left-radius: 10px; border-top-right-radius: 10px;\"";
						}
						else if(i == cards.length - 1)
						{
							html += " style=\"border-bottom-left-radius: 10px; border-bottom-right-radius: 10px;\"";
						}
						html += ">";
						html += "</button>"
						html += "<img src=\""+cards[i]["icon"]+"\" style=\"float: left; margin-left: 325px; height: 38px;\" />";
						html += "<span style=\"float: left; color: #5A5A5A; font-size: 14px; margin-left: 60px; margin-top: 8px;\">****   ****   ****   "+cards[i]["number"]+"</span>";
						html += "</div>";
						$("#credit_cards_container").append(html);
					}
					$("button[id^=\"clear-credit-card\"]").on("click", function()
					{
						var card_id = $(this).attr("id").split("-")[3];
						$("#setup_modal_notification").html("<img src=\"images/load/8.gif\" alt=\"\">");
						$("#setup_modal_notification").css("background-color", "#FFF");
						$("#setup_modal_notification_container").css("opacity", "1.0");
						$.post("change_credit_card.php", {type: "2", action: "2", id: '.$this->user_id.', card_id: card_id}, function(data, status)
						{
							//console.log(data);
							$("#setup_modal_notification_container").css("opacity", "0.0");
							if(data == "1")
							{
								$("#setup_modal_notification").css("background-color", "#52D859");
								$("#setup_modal_notification").html("<p style=\"color: #fff;\">Credit Card Removed!</p>");
								$("#setup_modal_notification_container").animate({opacity: "1.0"}, {duration: 1000, easing: "easeInOutQuad", complete: function() {
									setTimeout(function(){$("#setup_modal_notification_container").animate({opacity: "0.0"}, {duration: 1000, easing: "easeInOutQuad", complete: function() {}});}, 2000);
								}});
								$.post("get_user_info.php", {id: '.$this->user_id.'}, function(data, status)
								{
									var info = JSON.parse(data);
									if(info.hasOwnProperty("cards") && info["cards"].length > 0)
									{
										load_credit_cards(info["cards"]);
									}
								});
							}
							else if(data.substr(0, 2) == "IC")
							{
								alert("You are currently in a consultation, please wait until the consultation is over to delete credit cards.");
							}
							else
							{
								$("#setup_modal_notification").css("background-color", "#D5483A");
								$("#setup_modal_notification").html("<p style=\"color: #fff;\">Unable To Remove Card</p>");
								$("#setup_modal_notification_container").animate({opacity: "1.0"}, {duration: 1000, easing: "easeInOutQuad", complete: function() {
									setTimeout(function(){$("#setup_modal_notification_container").animate({opacity: "0.0"}, {duration: 1000, easing: "easeInOutQuad", complete: function() {}});}, 2000);
								}});
							}
						});
					});
				}
				
				function load_credit_cards_doctor(cards)
				{
					$("#credit_cards_container_doctor").html("");
					for(var i = 0; i < cards.length; i++)
					{
						var html = "";
						var stripe_holder = cards[i]["stripe_id"];
						stripe_holder = JSON.stringify(stripe_holder).replace(/&/, "&amp;").replace(/"/g, "&quot;");
						var cc_spot_holder = cards[i]["number"];
						cc_spot_holder = JSON.stringify(cc_spot_holder).replace(/&/, "&amp;").replace(/"/g, "&quot;");
						var cc_icon = cards[i]["icon"];
						cc_icon = JSON.stringify(cc_icon).replace(/&/, "&amp;").replace(/"/g, "&quot;");
						html += "<div onclick=\"select_card_doctor("+stripe_holder+", "+cc_spot_holder+", "+cc_icon+");\" class=\"credit_card_row\"";
						if(i == 0 && i == cards.length - 1)
						{
							html += " style=\"border-radius: 10px;\"";
						}
						else if(i == 0)
						{
							html += " style=\"border-top-left-radius: 10px; border-top-right-radius: 10px;\"";
						}
						else if(i == cards.length - 1)
						{
							html += " style=\"border-bottom-left-radius: 10px; border-bottom-right-radius: 10px;\"";
						}
						html += ">";
						html += "</button>"
						html += "<img src=\""+cards[i]["icon"]+"\" style=\"float: left; margin-left: 150px; height: 38px;\" />";
						html += "<span style=\"float: left; color: #5A5A5A; font-size: 14px; margin-left: 60px; margin-top: 8px;\">****   ****   ****   "+cards[i]["number"]+"</span>";
						html += "</div>";
						$("#credit_cards_container_doctor").append(html);
					}
					$("button[id^=\"clear-credit-card\"]").on("click", function()
					{
						var card_id = $(this).attr("id").split("-")[3];
						$("#setup_modal_notification").html("<img src=\"images/load/8.gif\" alt=\"\">");
						$("#setup_modal_notification").css("background-color", "#FFF");
						$("#setup_modal_notification_container").css("opacity", "1.0");
						$.post("change_credit_card.php", {type: "1", action: "2", id: '.$this->med_id.', card_id: card_id}, function(data, status)
						{
							//console.log(data);
							$("#setup_modal_notification_container").css("opacity", "0.0");
							if(data == "1")
							{
								$("#setup_modal_notification").css("background-color", "#52D859");
								$("#setup_modal_notification").html("<p style=\"color: #fff;\">Credit Card Removed!</p>");
								$("#setup_modal_notification_container").animate({opacity: "1.0"}, {duration: 1000, easing: "easeInOutQuad", complete: function() {
									setTimeout(function(){$("#setup_modal_notification_container").animate({opacity: "0.0"}, {duration: 1000, easing: "easeInOutQuad", complete: function() {}});}, 2000);
								}});
								$.post("get_doctor_credit_cards", {id: '.$this->med_id.'}, function(data, status)
								{
									var info = JSON.parse(data);
									if(info.hasOwnProperty("cards") && info["cards"].length > 0)
									{
										load_credit_cards_doctor(info["cards"]);
									}
								});
							}
							else if(data.substr(0, 2) == "IC")
							{
								alert("You are currently in a consultation, please wait until the consultation is over to delete credit cards.");
							}
							else
							{
								$("#setup_modal_notification").css("background-color", "#D5483A");
								$("#setup_modal_notification").html("<p style=\"color: #fff;\">Unable To Remove Card</p>");
								$("#setup_modal_notification_container").animate({opacity: "1.0"}, {duration: 1000, easing: "easeInOutQuad", complete: function() {
									setTimeout(function(){$("#setup_modal_notification_container").animate({opacity: "0.0"}, {duration: 1000, easing: "easeInOutQuad", complete: function() {}});}, 2000);
								}});
							}
						});
					});
				}
			</script>';
		}
	}
}
?>
