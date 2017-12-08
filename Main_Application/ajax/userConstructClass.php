<?php
//AUTHOR: Kyle Austin
//DATE: 10/29/14
//Purpose: Create class to consolidate many functions that are redundant within our systems...

class userConstructClass{

//Variables for class are instantiated here...
//Private variables are set here...
public $con;
private $name;
private $password;
private $access;
private $privs;
private $encryption_password;
private $dbhost;
private $dbname;
private $dbuser;
private $dbpass;
		
//Public variables are set here....
//Doctor....
public $med_id;
public $doctor_first_name;
public $doctor_last_name;
public $doctor_email;
public $doctor_email2;
public $doctor_fixed_id;
public $doctor_fixed_name;
public $doctor_role;
public $doctor_phone;
public $doctor_dob;
public $doctor_dob_day;
public $doctor_dob_month;
public $doctor_dob_year;
public $doctor_gender;
public $doctor_sign_up_date;
public $doctor_last_ip;
public $doctor_privilege;
public $doctor_hourly_rate;
public $doctor_speciality;
public $doctor_location;
public $doctor_telemed;
public $doctor_in_consultation;
public $doctor_consultation_pat = -1;
public $doctor_cons_req_time;
public $doctor_consultation_date;
public $doctor_rating_score;
public $doctor_telemed_type;
public $doctor_registered_code;
public $doctor_hospital_name;
public $doctor_hospital_address;
public $doctor_timezone;
public $doctor_session_hash;
public $doctors_session_hash;//This stores the hash that is held under the session variable.  If this does not match $doctor_session_hash then the hash is invalid...
public $doctor_phs_identifier;
public $doctor_title;
public $doctor_country;
public $doctor_state;
public $doctor_credits;
public $doctor_tracking_price;
public $doctor_consult_price;
public $doctor_language;

//Calculated doctor variables...
public $doctor_member_count;
public $doctor_reach_count;
public $doctor_notReach_count;
public $doctor_track_count;
public $doctor_reports_count;
public $doctor_message_count;
public $doctor_probe_count;
public $doctor_summary_count;
public $doctor_appointment_count;
public $doctor_new_acts_count;
    
public $doctor_vitals_count = 0;
public $doctor_allergies_count = 0;
public $doctor_vaccines_count = 0;
public $doctor_diagnostics_count = 0;
public $doctor_family_count = 0;
public $doctor_habits_count = 0;
public $doctor_medications_count = 0;

//Doctor group variables...
public $doctor_group;
public $doctor_group_name;
public $doctor_group_address;
public $doctor_group_city;
public $doctor_group_state;
public $doctor_group_zip;
public $doctor_group_country;
public $doctor_users_in_group;

//Doctor message variables...
public $new_mesages_to_doctor;
public $total_mesages_to_doctor;
public $opacity_user;
public $opacity_doctor;
public $visable_user;
public $visable_doctor;

//Holds sensitive info(needs to be private)
private $doctor_stripe_id;
private $doctor_npi;
private $doctor_dea;

//Member...
public $mem_id;
public $member_first_name;
public $member_last_name;
public $member_id_creator;
public $member_email;
public $member_fixed_id;
public $member_fixed_name;
public $member_password_hash;


public $member_alias;
public $member_phone;
public $member_dob;
public $member_age;
public $member_gender;
public $member_sign_up_date;
public $member_last_ip;
public $member_privilege;
public $member_is_verified;
public $member_most_recent_doc;
public $member_current_calling_doctor;
public $member_timezone;
public $member_number_of_phone_calls;
public $member_location;
public $member_plan;
public $member_subs_type;
public $member_owner_account;
public $member_relationship;
public $member_family_access;
public $member_session_hash;
public $members_session_hash;//This stores the hash that is held under the session variable.  If this does not match $member_session_hash then the hash is invalid...
public $member_personal_doctor;
public $member_personal_doctor_accepted;
public $member_phs_identifier;
public $member_charge1;
public $member_charge2;
public $member_cc_verified;
public $member_charge_address;
public $member_language;

//Slated probe vars
public $member_slated_probe;
public $member_slated_probe_count;
public $slated_doctor_row;
public $slated_protocol_row;
public $slated_probe_type;
public $slated_probe_interval;
public $slated_probe_timezone;
public $slated_probe_price;
public $slated_probe_months;

//Holds sensitive info(needs to be private)
private $member_stripe_id;

//BOTHID is used for pages that need to have the ability to be accessed by doctor and patient simultaneously.
public $both_id;

//Probe variables...
public $vg_response = 0;
public $g_response = 0;
public $n_response = 0;
public $b_response = 0;
public $vb_response = 0;
public $t_response = 0;

//Twilio variables...
private $api_version = "2010-04-01";
private $account_sid = "AC109c7554cf28cdfe596e4811c03495bd";
private $auth_token = "26b187fb3258d199a6d6edeb7256ecc1";

//Telemed common variables...
public $telemed_name;
public $telemed_type;
public $in_consultation;
public $docs_in_consultation = array();
    
//FOOTER COPYRIGHT VAR
public $footer_copy = '<span style="color:grey; font-size:14px; margin:20px;">&copyCopyright 2015 Inmers LLC. Health2.Me is a property of Inmers LLC. All Rights Reserved. <a href="legal/tos.html" tabindex="7" target="_blank">Terms of Service</a> | <a href="legal/privacy.html" tabindex="9" target="_blank">Privacy Policy</a></span>';

/////////////////////////////////////////////////////////////////////BEGIN MAIN///////////////////////////////////////////////////////////////////////////////
//CONSTRUCTOR FUNCTION INITIALIZES ON OBJECT CREATION////////////////////////////////////////////////////////////////////////
	function __construct(){
		
		require("environment_detail.php");

		$this->dbhost = $env_var_db["dbhost"];
		$this->dbname = $env_var_db["dbname"];
		$this->dbuser = $env_var_db["dbuser"];
		$this->dbpass = $env_var_db["dbpass"];
		
		session_start();
		
		// Connect to server and select databse.
		$this->con = new PDO('mysql:host='.$this->dbhost.';dbname='.$this->dbname.';charset=utf8', ''.$this->dbuser.'', ''.$this->dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
		
		if (!$this->con){
			die('Could not connect: ' . mysql_error());
			}
			
		/*if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 1800)) {
			// last request was more than 30 minutes ago
			echo "<META http-equiv='refresh' content='0;URL=timeout.php'>";
			}
			
		$_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp*/
		
		//If no medid session set go to login page...
		if(!isset($_SESSION['MEDID']) && !empty($_SESSION['MEDID']))
			{
			echo "<META http-equiv='refresh' content='0;URL=index.html'>";
			}
			
		

		$this->privateSetters($_SESSION['Nombre'], $_SESSION['Password'], $_SESSION['Acceso'], $_SESSION['Previlege'], $_SESSION['MEDID'], $_SESSION['UserID'], $_SESSION['BOTHID'], $_SESSION['session_hash_doctor'], $_SESSION['session_hash_member']);

		
		if ($this->access != '23432')
			{
			//If wrong access exit...
			$this->exit_onfail(1);
			}
			
		
		
		if(isset($this->med_id)){
		$this->doctorSetters();
		$this->checkDoctorSessionHash();
		$this->ongoing_sessions($this->med_id);
		}
		
		if(isset($this->mem_id)){
		$this->memberSetters();
		$this->encryptionSetter();
		$this->checkMemberSessionHash();
		$this->ongoing_sessions($this->mem_id);
		}
		
		//Create a folder for user if not already present
		if (!file_exists('temp/'.$this->med_id)) 
		{
			mkdir('temp/'.$this->med_id, 0777, true);
			mkdir('temp/'.$this->med_id.'/Packages_Encrypted', 0777, true);
			mkdir('temp/'.$this->med_id.'/PackagesTH_Encrypted', 0777, true);
		}
		
		//This breaks up doctor location into state and country...
		if(strpos($this->doctor_location, ",") == false)
		{
			$this->doctor_country = str_replace(":", ",", $this->doctor_location);
		}
		else
		{
			$arr = explode(", ", $this->doctor_location);
			$this->doctor_state = $arr[0];
			$this->doctor_country = str_replace(":", ",", $arr[1]);
		}
		
		//Standard HTML Header....
		echo "<!DOCTYPE html>
		<html lang='en'><head>
		<meta charset='utf-8'>
		<title>Inmers - Center Management Console</title>
		<meta name='viewport' content='width=device-width, initial-scale=1.0'>
		<meta name='description' content=''>
		<meta name='author' content=''>
		<script src='js/jquery.min.js'></script>
		<script src='js/jquery-ui.min.js'></script>
        <script src='js/sweet-alert.min.js'></script>";
		
		
		//Place common links(links used on every page) here...
		if($this->member_privilege != 'CATA'){
			echo "<link href='css/style.css' rel='stylesheet'>";
		}else{
			echo "<link href='css/styleCATA.css' rel='stylesheet'>";
		}
		echo "<link href='css/bootstrap.css' rel='stylesheet'>  
		<link rel='stylesheet' href='css/icon/font-awesome.css'>
		<link rel='stylesheet' href='css/bootstrap-responsive.css'>
		<link rel='shortcut icon' href='images/icons/favicon.ico'>
        <link rel='stylesheet' type='text/css' href='css/sweet-alert.css'>";
        
        echo "<input type='hidden' id='stripe-id-holder' value='".$this->member_stripe_id."' />
        <input type='hidden' id='address-holder' value='".$this->member_charge_address."' />
        <input type='hidden' id='domain' value='".$this->dbhost."' />
        <input type='hidden' id='probe_id_holder_for_purchase' value='' />";
		
		//Display this style sheet if HTI account or others, this style sheets overwrites previous styles...
		if ($_SESSION['CustomLook']=="COL") { 
        echo "<link href='css/styleCol.css' rel='stylesheet'>";
		} 
		if ($_SESSION['CustomLook']=="VIT") { 
        echo "<link href='css/styleVit.css' rel='stylesheet'>";
		} 		
		//Language includes and switching functionality...
        
        $lang_user = 0;
        $lang_type = 1;
        $lang_initial = 'en';
        if($_SESSION['isPatient']==0)
        {
            $lang_user = $this->med_id;
            $lang_type = 1;
            $lang_initial = $this->doctor_language;
        }
        else
        {
            $lang_user = $this->mem_id;
            $lang_type = 2;
            $lang_initial = $this->member_language;
        }
		echo "<!--<script src='jquery-lang-js-master/js/jquery-cookie.js' charset='utf-8' type='text/javascript'></script>
		<script src='jquery-lang-js-master/js/jquery-lang.js' charset='utf-8' type='text/javascript'></script>-->
		<script type='text/javascript'>
		//var lang = new Lang('en');
        //window.lang.dynamic('th', 'jquery-lang-js-master/js/langpack/th.json');
        //window.lang.dynamic('tu', 'jquery-lang-js-master/js/langpack/tu.json');
        //window.lang.dynamic('hi', 'jquery-lang-js-master/js/langpack/hi.json');
        
        
        
        // ALTERNATIVE TRANSLATION METHOD
        /*
        $.get('jquery-lang-js-master/js/langpack/th.json', function(data, status)
        {
            var es_json = data;
            $('*[lang^=\"en\"]').each(function()
            {
                if(es_json.token.hasOwnProperty($(this).text()))
                {
                    $(this).text(es_json.token[$(this).text()]);
                }
            });
        });*/
        
        



		function delete_cookie( name ) {
		  document.cookie = name + '=; expires=Thu, 01 Jan 1970 00:00:01 GMT;';
		}


		//var langType = $.cookie('lang');
        var idleTime = 0;
        
        function timerIncrement() {
            idleTime += 1;
            if (idleTime > 9) { // 10 minutes
                swal('Session Timeout', 'Session expired', 'error');
                setTimeout(function() { window.location.href = 'timeout.php'; }, 5000);
            }
            else if (idleTime == 9) { // 9 minutes
                swal({
                    title: 'Session Timeout',
                    text: 'Your session will be expired in 1 minute. Do you want to continue?',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, Continue',  
                    cancelButtonText: 'No, Log Out',   
                    closeOnConfirm: true,   
                    closeOnCancel: false
                    },
                    function(isConfirm) {
                        if(isConfirm) idleTime = 0;
                        else {
                            swal('Session Timeout', 'Your session has been expired!', 'error');
                            setTimeout(function() { window.location.href = 'timeout.php'; }, 5000);
                        }
                    });
            }
        }
        
        var initial_language = '".$lang_initial."';
        var last_language = 'en';
        function setCookie(name, value, days)
        {
            delete_cookie('lang');
                if (days) {
                    var date = new Date();
                    date.setTime(date.getTime()+(days*24*60*60*1000));
                    var expires = '; expires='+date.toGMTString();
                }
                else var expires = '';
                document.cookie = name+'='+value+expires+'; path=/';	
                $.when( swal({
                    title: 'The Language Setting Has Been Changed',
                    type: 'success',
                    timer: 2000
                }) ).then(function() {
                    setTimeout(function() 
                    {
                        $.post('set_language.php', {lang: value, user: '".$lang_user."', type: ".$lang_type."}, function(data, status)
                        {
                            //console.log('lang_user: ".$lang_user."');
                            location.reload();
                        });
                    }, 2000);
                });
        
        }
        $(document).ready(function() {
            /*if(langType == 'th'){
                window.lang.change('th');
            }
            if(langType == 'en'){
                window.lang.change('en');
            }
            if(langType == 'hi'){
                window.lang.change('hi');
            }
            if(langType == 'tu'){
                window.lang.change('tu');
            }*/
            
            console.log('INITIAL LANGUAGE: ' + initial_language);
            var langtype = 'en';
            if(initial_language != 'en')
            {
                langtype = initial_language;
                $.get('jquery-lang-js-master/js/langpack/'+initial_language+'.json', function(data, status)
                {
                    var json = data;
                    $('*[lang^=\"en\"]').each(function()
                    {
                        $(this).attr('original_eng_text', $(this).text());
                        if(json.token.hasOwnProperty($(this).text()))
                        {
                            $(this).text(json.token[$(this).text()]);
                        }
                        else if(json.token.hasOwnProperty($(this).html()))
                        {
                            $(this).html(json.token[$(this).html()]);
                        }
                        else if ($(this).prop('tagName') == 'INPUT' && $(this).prop('type') == 'submit' || $(this).prop('type') == 'button' && json.token.hasOwnProperty($(this).val()))
                        {
                            $(this).val(json.token[$(this).val()]);
                        }
                        else if ($(this).prop('tagName') == 'INPUT' && $(this).prop('type') == 'text' && json.token.hasOwnProperty($(this).attr('placeholder')))
                        {
                            $(this).attr('placeholder', (json.token[$(this).attr('placeholder')]));
                        }
                        else if ($(this).prop('tagName') == 'BUTTON' && json.token.hasOwnProperty($(this).html()))
                        {
                            $(this).html(json.token[$(this).html()]);
                        }
                        else if(json.token.hasOwnProperty($(this).prop('title')))
                        {
                            $(this).prop('title', json.token[$(this).prop('title')]);
                        }
                    });
                });
                console.log('selected language: '+initial_language+'.json');
            }
            
            
            
            //Increment the idle time counter every minute.
            //var idleInterval = setInterval(timerIncrement, 60000); // 1 minute

            //Zero the idle timer on every possible movement.
            $('body').bind('mousemove', function() {
                idleTime = 0;
            });
        });
		</script>";
		
		if(isset($_GET['checkout'])){
		include('checkoutClass.php');
			$user_id = $this->mem_id;
			$med_id = $this->med_id;
			$includes = 'yes';

			$checkout = new checkoutClass($user_id, $med_id, $includes);
		}
		
		if(isset($this->med_id)){
			echo "<input type='hidden' id='doc-tracking-price' value='".$this->doctor_tracking_price."' />
			<input type='hidden' id='doc-consult-price' value='".$this->doctor_consult_price."' />";
		}
		//END CONSTRUCT....
	}
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	//This builds session variables for class...
	private function privateSetters($name, $password, $access, $privs, $med_id, $mem_id, $both_id, $doctors_session_hash, $members_session_hash){
	$this->name = $name;
	$this->password = $password;
	$this->access = $access;
	$this->privs = $privs;
	$this->med_id = $med_id;
	$this->mem_id = $mem_id;
	$this->both_id = $both_id;
	$this->doctors_session_hash = $doctors_session_hash;
	$this->members_session_hash = $members_session_hash;
	
	if($this->mem_id == $this->both_id){
		$_SESSION['isPatient']=1;
	}else{
		$_SESSION['isPatient']=0;
	}
	}
	
	//This builds doctor variables for class...
	private function doctorSetters(){
	
	$result = $this->con->prepare("SELECT * FROM doctors where id=?");
	$result->bindValue(1, $this->med_id, PDO::PARAM_INT);
	$result->execute();
	
	$count = $result->rowCount();
	$row = $result->fetch(PDO::FETCH_ASSOC);
	
	if($count==1){
	//These allow for correct encoding of doctor name....
		$current_encoding = mb_detect_encoding($row['Name'], 'auto');
		$show_text = iconv($current_encoding, 'ISO-8859-1', $row['Name']);

		$current_encoding = mb_detect_encoding($row['Surname'], 'auto');
		$show_text2 = iconv($current_encoding, 'ISO-8859-1', $row['Surname']); 
			
		//Public doctor variables
		$this->doctor_first_name = $row['Name'];
		$this->doctor_last_name = $row['Surname'];
		$this->doctor_email = $row['IdMEDEmail'];
		$this->doctor_email2 = $row['Email2'];
		$this->doctor_fixed_id = $row['IdMEDFIXED'];
		$this->doctor_fixed_name = $row['IdMEDFIXEDNAME'];        
		$this->doctor_role = $row['Role'];
		$this->doctor_phone = $row['phone'];
		$this->doctor_dob = $row['DOB'];
		$this->doctor_gender = $row['Gender'];
		$this->doctor_sign_up_date = $row['Fecha'];
		$this->doctor_last_ip = $row['IPVALID'];
		$this->doctor_privilege = $row['previlege'];		
		$this->doctor_hourly_rate = $row['hourly_rate'];
		$this->doctor_speciality = $row['speciality'];
		$this->doctor_location = $row['location'];
		$this->doctor_telemed = $row['telemed'];
		$this->doctor_in_consultation = $row['in_consultation'];
		$this->doctor_consultation_pat = $row['consultation_pat'];
		$this->doctor_cons_req_time = $row['cons_req_time'];
		$this->doctor_rating_score = $row['rating_score'];
		$this->doctor_telemed_type = $row['telemed_type'];
		$this->doctor_registered_code = $row['registered_code'];
		$this->doctor_hospital_name = $row['hospital_name'];
		$this->doctor_hospital_address = $row['hospital_addr'];
		$this->doctor_timezone = $row['timezone'];
		$this->doctor_session_hash = $row['session_hash'];
		$this->doctor_phs_identifier = $row['phs_identifier'];
        $this->doctor_credits = $row['credits'];
		//Private doctor variables
		$this->doctor_stripe_id = $row['stripe_id'];
		$this->doctor_npi = $row['npi'];
		$this->doctor_dea = $row['dea'];
		$this->doctor_tracking_price = $row['tracking_price'];
		$this->doctor_consult_price = $row['consult_price'];
		$this->doctor_language = $row['language'];
		
		//Sets the title of doctor
		if ($row['Role']=='1') $this->doctor_title ='Dr. '; else $this->doctor_title =' '; 
		
		//Separates year, month, and day for DOB 
		$this->doctor_dob_year = substr ($this->doctor_dob,0,4);
		$this->doctor_dob_month = substr ($this->doctor_dob,4,2);
		$this->doctor_dob_day = substr ($this->doctor_dob,6,2);
	
		$result = $this->con->prepare("SELECT idGroup FROM doctorsgroups WHERE idDoctor = ? LIMIT 1");
		$result->bindValue(1, $this->med_id, PDO::PARAM_INT);
		$result->execute();

		$row = $result->fetch(PDO::FETCH_ASSOC);
		$count = $result->rowCount();
		
		if($count == 1){
		$this->doctor_group = $row['idGroup'];
		
		$result = $this->con->prepare("SELECT * FROM groups where id=?");
		$result->bindValue(1, $this->doctor_group, PDO::PARAM_INT);
		$result->execute();
		
        $row = $result->fetch(PDO::FETCH_ASSOC);
		
		//Assigns group details to public variables...
        $this->doctor_group_name = $row['Name'];
        $this->doctor_group_address = $row['Address'];
        $this->doctor_group_zip = $row['ZIP'];
        $this->doctor_group_city = $row['City'];
        $this->doctor_group_state = $row['State'];
        $this->doctor_group_country = $row['Country'];
        
        //Get Number of users attatched to this group
        $result1 = $this->con->prepare("SELECT * FROM doctorsgroups where idGroup=?");
		$result1->bindValue(1, $this->doctor_group, PDO::PARAM_INT);
		$result1->execute();
		
        $this->doctor_users_in_group = $result1->rowCount();
		}
		
		//This sets message variables for doctor...
		// Get Number of Messages from patients
		$result2 = $this->con->prepare("SELECT * FROM message_infrastructureuser WHERE receiver_id = ? AND tofrom='to' AND status='new' ");
		$result2->bindValue(1, $this->med_id, PDO::PARAM_INT);
		$result2->execute();
		
		$this->new_mesages_to_doctor = $result2->rowCount();

		if ($this->new_mesages_to_doctor>0) {$this->visable_user = ''; $this->opacity_user='1';} else {$this->visable_user = 'hidden';$this->opacity_user='0.3';}
		// Get Number of Messages from doctors
		$result3 = $this->con->prepare("SELECT * FROM message_infrasture WHERE receiver_id = ? AND status='new' ");
		$result3->bindValue(1, $this->med_id, PDO::PARAM_INT);
		$result3->execute();
		
		$this->total_mesages_to_doctor = $result3->rowCount();
		if ($this->total_mesages_to_doctor>0) {$this->visable_doctor = ''; $this->opacity_doctor='1';} else {$this->visable_doctor = 'hidden'; $this->opacity_doctor='0.3';}
		
		}
		else
		{
		//If no doctors found with that id then exit...
		$this->exit_onfail(2);
		}
        
	//END DOCTOR SETTERS
	}
	
	//This builds member variables for class...
	private function memberSetters(){
			
	$result = $this->con->prepare("SELECT *,floor(datediff(curdate(),DOB) / 365) as age FROM usuarios U INNER JOIN basicemrdata B where U.Identif=? AND U.Identif = B.IdPatient");
	$result->bindValue(1, $this->mem_id, PDO::PARAM_INT);
	$result->execute();
	
	$count = $result->rowCount();
	$row = $result->fetch(PDO::FETCH_ASSOC);
	
	if($count==1){
	//These allow for correct encoding of member name....
		$current_encoding = mb_detect_encoding($row['Name'], 'auto');
		$show_text = iconv($current_encoding, 'ISO-8859-1', $row['Name']);

		$current_encoding = mb_detect_encoding($row['Surname'], 'auto');
		$show_text2 = iconv($current_encoding, 'ISO-8859-1', $row['Surname']); 
		
		//Public member variables...
		$this->member_first_name = $show_text;
		$this->member_last_name = $show_text2;
		$this->member_id_creator = $row['IdCreator'];
		$this->member_email = $row['email'];
		$this->member_fixed_id = $row['IdUsFIXED'];
		$this->member_fixed_name = $row['IdUsFIXEDNAME'];
		$this->member_password_hash = $row['IdUsRESERV'];


		$this->member_alias = $row['Alias'];
		$this->member_phone = $row['telefono'];
		$this->member_dob = $row['FNac'];
		$this->member_age = $row['age'];
		$this->member_gender = $row['Sexo'];
		$this->member_sign_up_date = $row['signUpDate'];
		$this->member_last_ip = $row['IPVALID'];
		$this->member_privilege = $row['GrantAccess'];
		$this->member_is_verified = $row['Verificado'];
		$this->member_most_recent_doc = $row['most_recent_doc'];
		$this->member_current_calling_doctor = $row['current_calling_doctor'];
		$this->member_timezone = $row['timezone'];
		$this->member_number_of_phone_calls = $row['numberofPhoneCalls'];
		$this->member_location = $row['location'];
		$this->member_plan = $row['plan'];
		$this->member_subs_type = $row['subsType'];
		$this->member_owner_account = $row['ownerAcc'];
		$this->member_relationship = $row['relationship'];
        $this->member_family_access = $row['grant_access'];
		$this->member_session_hash = $row['session_hash'];
		$this->member_personal_doctor = $row['personal_doctor'];
		$this->member_personal_doctor_accepted = $row['personal_doctor_accepted'];
		$this->member_phs_identifier = $row['phs_identifier'];
		$this->member_charge1 = $row['charge1'];
		$this->member_charge2 = $row['charge2'];
		$this->member_cc_verified = $row['cc_verified'];
		$this->member_charge_address = $row['Address'].' '.$row['City'].', '.$row['state'].' '.$row['country'];
        $this->member_language = $row['language'];

		//Holds sensitive info(needs to be private)
		$this->member_stripe_id = $row['stripe_id'];
	}
	//END MEMBER SETTERS
	}
	
	//This stores the encryptions password for encryption/decryption
	private function encryptionSetter(){
			
	$result = $this->con->prepare("select pass from encryption_pass where id = (select max(id) from encryption_pass)");
	$result->execute();
	$row = $result->fetch(PDO::FETCH_ASSOC);
	$this->encryption_password = $row['pass'];
	}
	
	//This sets public telemed variables....
	public function telemedSetter(){
	require "Services/Twilio.php";
			
			//Calculate doctor consultation date....
		if(strlen($this->doctor_cons_req_time) > 0)
		{
			$cons_date = new DateTime();
			$cons_date->setTimestamp(intval($this->doctor_cons_req_time));
			$this->doctor_consultation_date = $cons_date->format('F j, Y g:i A e');
		}
		
		$result_pat = $this->con->prepare("SELECT Name,Surname FROM usuarios where Identif=?");
		$result_pat->bindValue(1, $this->doctor_consultation_pat, PDO::PARAM_INT);
		$result_pat->execute();
		
		$row_pat = $result_pat->fetch(PDO::FETCH_ASSOC);
		$this->telemed_name = $row_pat['Name'].' '.$row_pat['Surname'];
		$this->telemed_type = $row['telemed_type'];
		$this->in_consultation = intval($row['in_consultation']);
		
		$client = new Services_Twilio($this->account_sid, $this->auth_token);
		
		try
		{
			foreach ($client->account->conferences->getIterator(0, 50, array("Status" => "in-progress")) as $conference)
			{
				$conference_name = explode("_", $conference->friendly_name);
				$doc_id = intval($conference_name[0]);
				if(!in_array($doc_id, $this->docs_in_consultation))
				{
					array_push($this->docs_in_consultation, $doc_id);
				}
			}
		}catch(Exception $e)
		{
			//TODO: add catch exception;
		}
		
		if($this->in_consultation != 1)
		{
			$this->telemed_type = 1;
			if(in_array(intval($this->med_id), $this->docs_in_consultation))
			{
				$this->in_consultation = 1;
			}
			else
			{
				$this->in_consultation = 0;
			}
		}
		else
		{
			$this->telemed_type = 2;
		}
	//END OF TELEMED SETTER	
	}
	/////////////////////////////////////////////////////////////////////////END OF MAIN/////////////////////////////////////////////////////////////////////////////
	
	///////////////////////////////////////////////////////////////////////////PROBE/////////////////////////////////////////////////////////////////////////////////
	
	//This pulls probe response data to be displayed in probe graph utilizing charts.js...
	public function probeGauge(){
			
	$probe_query = $this->con->prepare("SELECT * FROM probe WHERE doctorID = ?");
	$probe_query->bindValue(1, $this->med_id, PDO::PARAM_INT);
	$probe_query->execute();

	while($probe_row = $probe_query->fetch(PDO::FETCH_ASSOC)){

	$probe_response = $this->con->prepare("SELECT * FROM proberesponse WHERE probeID = ? ORDER BY responseTime DESC LIMIT 1");
	$probe_response->bindValue(1, $probe_row['probeID'], PDO::PARAM_INT);
	$probe_response->execute();

	$row_response = $probe_response->fetch(PDO::FETCH_ASSOC);

	if($row_response['response'] == 1){
	$this->vb_response = $this->vb_response + 1;
	}elseif($row_response['response'] == 2){
	$this->b_response = $this->b_response + 1;
	}elseif($row_response['response'] == 3){
	$this->n_response = $this->n_response + 1;
	}elseif($row_response['response'] == 4){
	$this->g_response = $this->g_response + 1;
	}elseif($row_response['response'] == 5){
	$this->vg_response = $this->vg_response + 1;
	}else{
	$this->t_response = $this->t_response - 1;
	}
	$this->t_response = $this->t_response + 1;
	}
	
	//END PROBE GAUGE
	}
	
	//This pulls pending probes slated for purchase later
	public function checkForSlatedProbe(){
			
		$probe_slated = $this->con->prepare("SELECT * FROM probe WHERE patientID = ? && doctorPermission = 1 && patientPermission = 0");
		$probe_slated->bindValue(1, $this->mem_id, PDO::PARAM_INT);
		$probe_slated->execute();
		
		$probe_slated_first = $this->con->prepare("SELECT * FROM probe WHERE patientID = ? && doctorPermission = 1 && patientPermission = 0 LIMIT 1");
		$probe_slated_first->bindValue(1, $this->mem_id, PDO::PARAM_INT);
		$probe_slated_first->execute();
		
		$this->member_slated_probe = $probe_slated_first->fetch(PDO::FETCH_ASSOC);
		$this->member_slated_probe_count = $probe_slated->rowCount();
		
		$probe_slated_doctor = $this->con->prepare("SELECT * FROM doctors WHERE id=?");
		$probe_slated_doctor->bindValue(1, $this->member_slated_probe['doctorID'], PDO::PARAM_INT);
		$probe_slated_doctor->execute();
		
		$this->slated_doctor_row = $probe_slated_doctor->fetch(PDO::FETCH_ASSOC);
		
		$probe_slated_protocol = $this->con->prepare("SELECT * FROM probe_protocols WHERE protocolID=?");
		$probe_slated_protocol->bindValue(1, $this->member_slated_probe['protocolID'], PDO::PARAM_INT);
		$probe_slated_protocol->execute();
		
		$this->slated_protocol_row = $probe_slated_protocol->fetch(PDO::FETCH_ASSOC);
		
		if($this->member_slated_probe['emailRequest'] == 1){
			$method = 3;
			$this->slated_probe_type = 'Email';
		}elseif($this->member_slated_probe['phoneRequest'] == 1){
			$method = 2;
			$this->slated_probe_type = 'Phone';
		}elseif($this->member_slated_probe['smsRequest'] == 1){
			$method = 1;
			$this->slated_probe_type = 'Text';
		}
		
		if($this->member_slated_probe['probeInterval'] == 1){
			$this->slated_probe_interval = "Daily";
		}else if($this->member_slated_probe['probeInterval'] == 7){
			$this->slated_probe_interval = "Weekly";
		}else if($this->member_slated_probe['probeInterval'] == 30){
			$this->slated_probe_interval = "Monthly";
		}else if($this->member_slated_probe['probeInterval'] == 365){
			$this->slated_probe_interval = "Yearly";
		}
		
		if($this->member_slated_probe['timezone'] == 1){
			$this->slated_probe_timezone = "US Eastern Time";
		}else if($this->member_slated_probe['timezone'] == 2){
			$this->slated_probe_timezone = "US Central Time";
		}else if($this->member_slated_probe['timezone'] == 3){
			$this->slated_probe_timezone = "US Pacific Time";
		}else if($this->member_slated_probe['timezone'] == 4){
			$this->slated_probe_timezone = "US Mountain Time";
		}else if($this->member_slated_probe['timezone'] == 5){
			$this->slated_probe_timezone = "Europe Central Time";
		}else if($this->member_slated_probe['timezone'] == 6){
			$this->slated_probe_timezone = "Greenwich Mean Time";
		}
		
		$this->slated_probe_price = '$'.($this->member_slated_probe['scheduledMonthCount'] * (($this->slated_doctor_row['tracking_price'] + 1000) / 100)).'.00';
		$this->slated_probe_months = $this->member_slated_probe['scheduledMonthCount'];
		
		echo "<input type='hidden' id='probe_protocols_pass' value='".$this->member_slated_probe['protocolID']."' />
		<input type='hidden' id='probe_time_pass' value='".$this->member_slated_probe['desiredTime']."' />
		<input type='hidden' id='probe_timezone_pass' value='".$this->member_slated_probe['timezone']."' />
		<input type='hidden' id='probe_months_pass' value='".$this->member_slated_probe['scheduledMonthCount']."' />
		<input type='hidden' id='probe_medid_pass' value='".$this->member_slated_probe['doctorID']."' />
		<input type='hidden' id='probe_medid_base_price' value='".$this->slated_doctor_row['tracking_price']."' />
		<input type='hidden' id='probe_method_pass' value='".$method."' />
		<input type='hidden' id='probe_interval_pass' value='".$this->member_slated_probe['probeInterval']."' />
		<input type='hidden' id='probe_name_pass' value='".$this->slated_protocol_row['name']."' />
		<input type='hidden' id='member_email_pass' value='".$this->member_email."' />
		<input type='hidden' id='member_phone_pass' value='".$this->member_phone."' />
		<input type='hidden' id='member_name_pass' value='".$this->member_first_name." ".$this->member_last_name."' />";
	}
	
	///////////////////////////////////////////////////////////////////////////END OF PROBE//////////////////////////////////////////////////////////////////////////
	
	/////////////////////////////////////////////////////////////////////////BEGIN MISC/////////////////////////////////////////////////////////////////////////////
	
	//This checks the session hash.  If there is a hash mismatch the id, hash, and timestamp are added to hacking_attemps table....
	private function checkDoctorSessionHash(){
	//THIS SECTIONS CHECK DOC HASH......
		if(isset($this->med_id) && isset($this->doctor_session_hash) && isset($this->doctors_session_hash)){

			if($this->doctors_session_hash != $this->doctor_session_hash){
			$result = $this->con->prepare("INSERT INTO hacking_attempts SET type='DOCTOR', id_hacker = ?, hash = ?, datetime=NOW(), location = ?, hash2 = ?"); 
			$result->bindValue(1, $this->med_id, PDO::PARAM_INT);
			$result->bindValue(2, $this->doctor_session_hash, PDO::PARAM_STR);
			$result->bindValue(3, $_SERVER["REQUEST_URI"], PDO::PARAM_STR);
			$result->bindValue(4, $this->doctors_session_hash, PDO::PARAM_STR);
			$result->execute();

			//$this->exit_onfail(3);
			}else{
			//ADD NEW HASH TO DATABASE FOR DOCTOR....
			if(isset($this->med_id) && isset($this->doctors_session_hash) && isset($this->doctor_session_hash) && $this->doctors_session_hash == $this->doctor_session_hash){
			$new_session_hash = $this->generateHash();
			
			$result = $this->con->prepare("UPDATE doctors SET session_hash = ? where id=?"); 
			$result->bindValue(1, $new_session_hash, PDO::PARAM_STR);
			$result->bindValue(2, $this->med_id, PDO::PARAM_INT);
			$result->execute();

			//SETS NEW HASH SESSION....
			$_SESSION['session_hash_doctor'] = $new_session_hash;
			}
			}
		}
			
		//END OF CHECK DOCTOR SESSION HASH
		}
	
	//This checks the session hash.  If there is a hash mismatch the id, hash, and timestamp are added to hacking_attemps table....
	private function checkMemberSessionHash(){
		
	if(isset($this->mem_id) && isset($this->member_session_hash) && isset($this->members_session_hash)){

			if($this->members_session_hash != $this->member_session_hash){
			$result = $this->con->prepare("INSERT INTO hacking_attempts SET type='MEMBER', id_hacker = ?, hash = ?, datetime=NOW(), location=?, hash2 = ?"); 
			$result->bindValue(1, $this->mem_id, PDO::PARAM_INT);
			$result->bindValue(2, $this->member_session_hash, PDO::PARAM_STR);
			$result->bindValue(3, $_SERVER["REQUEST_URI"], PDO::PARAM_STR);
			$result->bindValue(4, $this->members_session_hash, PDO::PARAM_STR);
			$result->execute();

			//$this->exit_onfail(3);
			}else{
			//ADD NEW HASH TO DATABSE FOR MEMBER.....
			if(isset($this->mem_id) && isset($this->members_session_hash) && isset($this->member_session_hash) && $this->members_session_hash == $this->member_session_hash){
			$new_session_hash = $this->generateHash();
			
			$result = $this->con->prepare("UPDATE usuarios SET session_hash = ? where Identif=?"); 
			$result->bindValue(1, $new_session_hash, PDO::PARAM_STR);
			$result->bindValue(2, $this->mem_id, PDO::PARAM_INT);
			$result->execute();

			//SETS NEW HASH SESSION....
			$_SESSION['session_hash_member'] = $new_session_hash;

			}
			}
	}
	//END OF CHECK MEMBER SESSION HASH
	}
	
	//This generates 255 character hash...
	private function generateHash(){
	$charset='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
	$str = '';
	$length = 255;
		$count = strlen($charset);
		while ($length--) {
			$str .= $charset[mt_rand(0, $count-1)];
		}
	$hash = $str;
	
	return $hash;
	//END OF GENERATE HASH
	}
	
	//This will display correct page links depending on the page string that is passed to this function...
	
	//These are common links that are included in the __construct function.  DO NOT INCLUDE THESE TWICE!!!!
	//<link href='css/style.css' rel='stylesheet'>
	//<link href='css/bootstrap.css' rel='stylesheet'>  
	//<link rel='stylesheet' href='css/icon/font-awesome.css'>
	//<link rel='stylesheet' href='css/bootstrap-responsive.css'>
	//<link rel='shortcut icon' href='images/icons/favicon.ico'>
	
	public function pageLinks($page){
	if($page == 'PatientNetwork.php' || $page == 'PatientNetworkRedesign.php'){
	echo "
	<link rel='stylesheet' href='h2m_css/h2m_patientnetwork.css' />
	
	<link rel='stylesheet' href='css/jquery.timepicker.css' >
    <link rel='stylesheet' href='css/jquery-ui-1.8.16.custom.css' media='screen'  />
    <link rel='stylesheet' href='css/autocomplete.css' media='screen'  />
    <link rel='stylesheet' href='css/fullcalendar.css' media='screen'  />
    <link rel='stylesheet' href='css/chosen.css' media='screen'  />
    <link rel='stylesheet' href='css/datepicker.css' >
    <link rel='stylesheet' href='css/colorpicker.css'>
    <link rel='stylesheet' href='css/glisse.css?1.css'>
    <link rel='stylesheet' href='css/jquery.jgrowl.css'>
    <link rel='stylesheet' href='js/elfinder/css/elfinder.css' media='screen' />
    <link rel='stylesheet' href='css/jquery.tagsinput.css' />
    <link rel='stylesheet' href='css/demo_table.css' >
    <link rel='stylesheet' href='css/jquery.jscrollpane.css' >
    <link rel='stylesheet' href='css/validationEngine.jquery.css'>
    <link rel='stylesheet' href='css/jquery.stepy.css' />
  	<link rel='stylesheet' href='font-awesome/css/font-awesome.min.css'>
	<link rel='stylesheet' href='css/tipped.css'>
	<link rel='stylesheet' href='css/toggle-switch.css'>
	<link rel='stylesheet' href='build/css/intlTelInput.css'>";
    
	}
	
	if($page == 'MainDashboard.php'){
	echo "
	<link href='css/bootstrap-dropdowns.css' rel='stylesheet'>
    <link rel='stylesheet' href='css/jquery-ui-1.8.16.custom.css' media='screen'  />
    <link rel='stylesheet' href='css/fullcalendar.css' media='screen'  />
    <link rel='stylesheet' href='css/chosen.css' media='screen'  />
    <link rel='stylesheet' href='css/datepicker.css' >
    <link rel='stylesheet' type='text/css' href='css/jquery.timepicker.css' />
    <link rel='stylesheet' href='css/colorpicker.css'>
    <link rel='stylesheet' href='css/glisse.css?1.css'>
    <link rel='stylesheet' href='css/jquery.jgrowl.css'>
    <link rel='stylesheet' href='js/elfinder/css/elfinder.css' media='screen' />
    <link rel='stylesheet' href='css/jquery.tagsinput.css' />
    <link rel='stylesheet' href='css/demo_table.css' >
    <link rel='stylesheet' href='css/jquery.jscrollpane.css' >
    <link rel='stylesheet' href='css/validationEngine.jquery.css'>
    <link rel='stylesheet' href='css/jquery.stepy.css' />
   	<link rel='stylesheet' href='font-awesome/css/font-awesome.min.css'>
	<link rel='stylesheet' href='css/H2MIcons.css' />
	<link rel='stylesheet' href='css/toggle-switch.css'>
	<link rel='stylesheet' href='css/csslider.css'>
    <link rel='shortcut icon' href='images/icons/favicon.ico'>
    <link rel='stylesheet' type='text/css' href='css/tooltipster.css' />";
	}
	
	if($page == 'patients.php'){
	echo "
    <link href='css/bootstrap-dropdowns.css' rel='stylesheet'>
    <link rel='stylesheet' href='css/jquery-ui-1.8.16.custom.css' media='screen'  />
    <link rel='stylesheet' href='css/fullcalendar.css' media='screen'  />
    <link rel='stylesheet' href='css/chosen.css' media='screen'  />
    <link rel='stylesheet' href='css/datepicker.css' >
    <link rel='stylesheet' href='css/colorpicker.css'>
    <link rel='stylesheet' href='css/glisse.css?1.css'>
    <link rel='stylesheet' href='css/jquery.jgrowl.css'>
    <link rel='stylesheet' href='js/elfinder/css/elfinder.css' media='screen' />
    <link rel='stylesheet' href='css/jquery.tagsinput.css' />
    <link rel='stylesheet' href='css/demo_table.css' >
    <link rel='stylesheet' href='css/jquery.jscrollpane.css' >
    <link rel='stylesheet' href='css/validationEngine.jquery.css'>
    <link rel='stylesheet' href='css/jquery.stepy.css' />
    <link rel='stylesheet' href='font-awesome/css/font-awesome.min.css'>
	<link rel='stylesheet' href='css/toggle-switch.css'>";
	}
	
	if($page == 'medicalConnections.php'){
	echo "
    <link href='css/bootstrap-dropdowns.css' rel='stylesheet'>
    <link rel='stylesheet' href='css/jquery-ui-1.8.16.custom.css' media='screen'  />
    <link rel='stylesheet' href='css/fullcalendar.css' media='screen'  />
    <link rel='stylesheet' href='css/chosen.css' media='screen'  />
    <link rel='stylesheet' href='css/datepicker.css' >
    <link rel='stylesheet' href='css/colorpicker.css'>
    <link rel='stylesheet' href='css/glisse.css?1.css'>
    <link rel='stylesheet' href='css/jquery.jgrowl.css'>
    <link rel='stylesheet' href='js/elfinder/css/elfinder.css' media='screen' />
    <link rel='stylesheet' href='css/jquery.tagsinput.css' />
    <link rel='stylesheet' href='css/demo_table.css' >
    <link rel='stylesheet' href='css/jquery.jscrollpane.css' >
    <link rel='stylesheet' href='css/validationEngine.jquery.css'>
    <link rel='stylesheet' href='css/jquery.stepy.css' />
	<link rel='stylesheet' href='font-awesome/css/font-awesome.min.css'>
	<link rel='stylesheet' href='css/toggle-switch.css'>
    <link rel='stylesheet' href='build/css/intlTelInput.css'>";
	}
	
	if($page == 'medicalConfiguration.php'){
	echo "
    <link href='css/bootstrap-dropdowns.css' rel='stylesheet'>
    <link rel='stylesheet' href='css/jquery-ui-1.8.16.custom.css' media='screen'  />
	<link rel='stylesheet' href='css/autocomplete.css' media='screen'  />
    <link rel='stylesheet' href='css/fullcalendar.css' media='screen'  />
    <link rel='stylesheet' href='css/chosen.css' media='screen'  />
    <link rel='stylesheet' href='css/datepicker.css' >
    <link rel='stylesheet' type='text/css' href='css/jquery.timepicker.css' />
    <link rel='stylesheet' href='css/colorpicker.css'>
    <link rel='stylesheet' href='css/tooltipster.css'>
    <link rel='stylesheet' href='css/glisse.css?1.css'>
    <link rel='stylesheet' href='css/jquery.jgrowl.css'>
    <link rel='stylesheet' href='js/elfinder/css/elfinder.css' media='screen' />
    <link rel='stylesheet' href='css/jquery.tagsinput.css' />
    <link rel='stylesheet' href='css/demo_table.css' >
    <link rel='stylesheet' href='css/jquery.jscrollpane.css' >
    <link rel='stylesheet' href='css/validationEngine.jquery.css'>
    <link rel='stylesheet' href='css/jquery.stepy.css' />
    <link rel='stylesheet' type='text/css' href='js/uploadify/uploadify.css'>
	<link rel='stylesheet' href='font-awesome/css/font-awesome.min.css'>
	<link rel='stylesheet' href='css/icon/font-awesome.css'>
    <link rel='stylesheet' href='build/css/intlTelInput.css'>
    <link rel='stylesheet' href='css/flags/css/flag-icon.min.css'>";
	}
	
	if($page == 'medicalPassport.php'){
	echo "
    <link rel='stylesheet' href='font-awesome/css/font-awesome.min.css'>
    <link rel='stylesheet' href='css/jquery-ui-1.8.16.custom.css' media='screen'  />
    <link rel='stylesheet' href='css/fullcalendar.css' media='screen'  />
    <link rel='stylesheet' href='css/chosen.css' media='screen'  />
    <link rel='stylesheet' href='css/datepicker.css' >
    <link rel='stylesheet' href='css/colorpicker.css'>
    <link rel='stylesheet' href='css/glisse.css?1.css'>
    <link rel='stylesheet' href='css/jquery.jgrowl.css'>
    <link rel='stylesheet' href='js/elfinder/css/elfinder.css' media='screen' />
    <link rel='stylesheet' href='css/jquery.tagsinput.css' />
    <link rel='stylesheet' href='css/demo_table.css' >
    <link rel='stylesheet' href='css/jquery.jscrollpane.css' >
    <link rel='stylesheet' href='css/validationEngine.jquery.css'>
    <link rel='stylesheet' href='css/jquery.stepy.css' />
	<link rel='stylesheet' href='css/toggle-switch.css'>
	<link rel='stylesheet' href='build/css/intlTelInput.css'>";
	}
	
	if($page == 'UserDashboard.php'){
	echo "
    <link href='css/bootstrap-dropdowns.css' rel='stylesheet'>
    <link rel='stylesheet' href='font-awesome/css/font-awesome.min.css'>
    <link rel='stylesheet' href='css/jquery-ui-1.8.16.custom.css' media='screen'  />
    <link rel='stylesheet' href='css/fullcalendar.css' media='screen'  />
    <link rel='stylesheet' href='css/chosen.css' media='screen'  />
    <link rel='stylesheet' href='css/datepicker.css' >
    <link rel='stylesheet' href='css/colorpicker.css'>
    <link rel='stylesheet' href='css/glisse.css?1.css'>
    <link rel='stylesheet' href='css/jquery.jgrowl.css'>
    <link rel='stylesheet' href='js/elfinder/css/elfinder.css' media='screen' />
    <link rel='stylesheet' href='css/jquery.tagsinput.css' />
    <link rel='stylesheet' href='css/demo_table.css' >
    <link rel='stylesheet' href='css/jquery.jscrollpane.css' >
    <link rel='stylesheet' href='css/validationEngine.jquery.css'>
    <link rel='stylesheet' href='css/jquery.stepy.css' />
    <link rel='stylesheet' type='text/css' href='css/googleAPIFamilyCabin.css'>
	<link rel='stylesheet' href='css/toggle-switch.css'>
    <link rel='stylesheet' href='css/doctor_styles.css'>
    <link rel='stylesheet' href='build/css/intlTelInput.css'>
    <link rel='stylesheet' type='text/css' href='css/tipped.css'/>";
	}
	
	if($page == 'dropzone.php'){
	echo "
	<link href='css/login.css' rel='stylesheet'>
    <link rel='stylesheet' href='font-awesome/css/font-awesome.min.css'>
	<link rel='stylesheet' type='text/css' href='css/tooltipster.css' />
    <link href='css/style.css' rel='stylesheet'>
    <link href='css/bootstrap.css' rel='stylesheet'>
    <link rel='stylesheet' href='css/basic.css' />
    <link rel='stylesheet' href='css/dropzone.css'/>
    <script src='js/dropzone.min.js'></script>
    <link rel='stylesheet' href='css/jquery-ui-1.8.16.custom.css' media='screen'  />
    <link rel='stylesheet' href='css/fullcalendar.css' media='screen'  />
    <link rel='stylesheet' href='css/chosen.css' media='screen'  />
    <link rel='stylesheet' href='css/datepicker.css' >
    <link rel='stylesheet' href='css/colorpicker.css'>
    <link rel='stylesheet' href='css/glisse.css?1.css'>
    <link rel='stylesheet' href='css/jquery.jgrowl.css'>
    <link rel='stylesheet' href='js/elfinder/css/elfinder.css' media='screen' />
    <link rel='stylesheet' href='css/jquery.tagsinput.css' />
    <link rel='stylesheet' href='css/demo_table.css' >
    <link rel='stylesheet' href='css/jquery.jscrollpane.css' >
    <link rel='stylesheet' href='css/validationEngine.jquery.css'>
    <link rel='stylesheet' href='css/jquery.stepy.css' />
	<link href='css/demo_style.css' rel='stylesheet' type='text/css'/>
	<link href='css/smart_wizard.css' rel='stylesheet' type='text/css'/>
	<link rel='stylesheet' href='css/jquery-ui-autocomplete.css' />";
	}
	
	if($page == 'dropzone_short.php'){
	echo "
	<!-- Le styles -->
	<link href='css/login.css' rel='stylesheet'>
    <link rel='stylesheet' href='font-awesome/css/font-awesome.min.css'>
	<link rel='stylesheet' type='text/css' href='css/tooltipster.css' />
    <link href='css/style.css' rel='stylesheet'>
    <link rel='stylesheet' href='css/basic.css' />
    <link rel='stylesheet' href='css/dropzone.css'/>
    <link rel='stylesheet' href='css/jquery-ui-1.8.16.custom.css' media='screen'  />
    <link rel='stylesheet' href='css/fullcalendar.css' media='screen'  />
    <link rel='stylesheet' href='css/chosen.css' media='screen'  />
    <link rel='stylesheet' href='css/datepicker.css' >
    <link rel='stylesheet' href='css/colorpicker.css'>
    <link rel='stylesheet' href='css/glisse.css?1.css'>
    <link rel='stylesheet' href='css/jquery.jgrowl.css'>
    <link rel='stylesheet' href='js/elfinder/css/elfinder.css' media='screen' />
    <link rel='stylesheet' href='css/jquery.tagsinput.css' />
    <link rel='stylesheet' href='css/demo_table.css' >
    <link rel='stylesheet' href='css/jquery.jscrollpane.css' >
    <link rel='stylesheet' href='css/validationEngine.jquery.css'>
    <link rel='stylesheet' href='css/jquery.stepy.css' />
	<link href='css/demo_style.css' rel='stylesheet' type='text/css'/>
	<link href='css/smart_wizard.css' rel='stylesheet' type='text/css'/>
	<link rel='stylesheet' href='css/jquery-ui-autocomplete.css' />";
	}
	//END OF PAGE LINKS
	}
	
	//Exit function is executed if there is a problem with the users session or access, you can display different messages by passing $exitType to exit_onfail...
	private function exit_onfail($exitType){
	echo "<!DOCTYPE html>
	<html lang='en'  class='body-error'><head>
    <meta charset='utf-8'>
    <title>health2.me</title>
    <!--<meta name='viewport' content='width=device-width, initial-scale=1.0'>-->
    <meta name='description' content=''>
    <meta name='author' content=''>

    <!-- Le styles -->
    <link href='css/style.css' rel='stylesheet'>
    <link href='css/bootstrap.css' rel='stylesheet'>  
	<link rel='stylesheet' href='css/icon/font-awesome.css'>
    <link rel='stylesheet' href='css/bootstrap-responsive.css'>

    <!-- Le fav and touch icons -->
    <link rel='shortcut icon' href='images/icons/favicon.ico'>    
	</head>
	<body>
	<!--Header Start-->
	<div class='header' >
    <a href='index.html' class='logo'><h1>I</h1></a>
    <div class='pull-right'></div>
    </div>
    <!--Header END-->
    <div class='error-bg'>
      <div class='error-s'>
        <!--<div class='error-number'>Health2me</div>-->
        <div class='error-number'><img src='images/health2meLOGO.png' width='350' /img></div>";
		if($exitType == 4){
        echo "<div class='error-number' style='font-size:20px; margin-top:0px; padding:0px; border:0ox;'>unlocking health</div>";
		echo "<!--<div class='error-number' style='font-size:20px; margin:0px; padding:0px; border:0px;'>social health networking</div>-->";
		}else{
		echo "<div class='error-number' style='font-size:20px; margin-top:15px;'>unlocking health</div>";
		echo "<!--<div class='error-number' style='font-size:20px; margin-top:15px;'>social health networking</div>-->";
		}
        
        echo "<div class='error-text' style='margin-top:10px;'>version 1.1</div>";
		
		if($exitType == 1){
		echo "<div class='error-text'>Incorrect credentials for login.</div>";
		}elseif($exitType == 2){
		echo "<div class='error-text'>MEDICAL USER NOT VALID. Incorrect credentials for login.</div>";
		}elseif($exitType == 3){
		echo "<div class='error-text'>USER DATA INCOMPLETE. No Doctor assigned to this User.</div>";
		}elseif($exitType == 4){
		echo "<div class='error-text' style='margin-top:10px;'>You have already activated dropbox cloud Channel.</div>";
		}elseif($exitType == 5){
		echo "<div class='error-text' style='margin-top:10px;'>You do not have permission to access this page.</div>";
		}elseif($exitType == 6){
        echo "<div class='error-text' style='margin-top:10px;'>Password reset error. Please contact Health2me support</div>";  
        }elseif($exitType == 7){
        echo "<div class='error-text' style='margin-top:10px;'>The member information you entered has already been created.  Please refer to forgot password link on login page.</div>";  
        }
		if($exitType != 4){
		echo "<a class='error-text' href='index.html' style='color: #2eb82e; text-decoration: underline;'><center>Click here to return Inmers Homepage</center></a>";
        }
		
      echo "</div>
     </div>
  </body>
</html>
";
die;
	}

	//Will check ongoing sessions for multiple login attempts...
	private function ongoing_sessions($user_id){
			
	//For checking multiple logins	
	$query = $this->con->prepare("select * from ongoing_sessions where userid=?");
	$query->bindValue(1, $user_id, PDO::PARAM_INT);
	$result=$query->execute();

	$count = $query->rowCount();
	$ip = $_SERVER['REMOTE_ADDR'];	
	if($count==0)
	{
		$q = $this->con->prepare("INSERT INTO ongoing_sessions VALUES(?,now(),?)");
		$q->bindValue(1, $user_id, PDO::PARAM_INT);
		$q->bindValue(2, $ip, PDO::PARAM_STR);
		$q->execute();
	}
	else
	{
		$q = $this->con->prepare("SELECT * FROM ongoing_sessions WHERE userid=? and ip=?");
		$q->bindValue(1, $user_id, PDO::PARAM_INT);
		$q->bindValue(2, $ip, PDO::PARAM_STR);
		$res=$q->execute();
		
		$cnt = $q->rowCount();
		if($cnt==1)
		{
			//The same user came back after abrupt logout (and before service could detect)
			//DO NOTHING
		}
		else
		{
			//echo "Other users are Accessing this account";
			$q = $this->con->prepare("INSERT INTO ongoing_sessions VALUES(?,now(),?)");
			$q->bindValue(1, $user_id, PDO::PARAM_INT);
			$q->bindValue(2, $ip, PDO::PARAM_STR);
			$q->execute();
			//header( 'Location: double_login.php' ) ;
		}
	}
	}

	//This checks access for nav links at top of application...
	public function checkAccessPage($page){

		$result = $this->con->prepare("select pc.accessid,pc.page from pageAccessControl pc INNER JOIN (select idGroup from doctorsgroups where idDoctor=?) dg where dg.idGroup=pc.groupid and pc.page=?");
		$result->bindValue(1, $this->med_id, PDO::PARAM_INT);
		$result->bindValue(2, $page, PDO::PARAM_STR);
		$result->execute();


        if($result->rowCount()>=1)   
        {
            $row = $result->fetchAll(PDO::FETCH_OBJ);
            return '{"items":'. json_encode($row) .'}'; 
        }
		//END OF CHECK ACCESS PAGE
	}

	public function doctor_calculations(){
        //include 'PatientNetworkHeaderCalls.php';
              
		//$this->doctor_vitals_count = $vitals_count;

		//echo $UConn."::".$UTotal."::".$UProbe."::".$TotMsg."::".$TotUpDr."::".$TotUpUs;
    }
//END OF USER CONSTRUCT CLASS
}

?>