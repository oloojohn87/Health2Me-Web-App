<?php
ini_set('display_errors',1);
error_reporting(E_ALL);
session_start();
 require("environment_detail.php");
 require_once("displayExitClass.php");
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];

$NombreEnt = $_SESSION['Nombre'];
$PasswordEnt = $_SESSION['Password'];
$MEDID = $_SESSION['MEDID'];
$UserID = $_SESSION['UserID'];
$Acceso = $_SESSION['Acceso'];
$privilege=$_SESSION['Previlege'];
$GrantAccess = 'HTI-COL';

//set a javascript variable here;
if ($Acceso != '23432')
{
$exit_display = new displayExitClass();

$exit_display->displayFunction(1);
die;
}
//new PDO connection
$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }

// Old way to connect to server and select database.
//$link = mysql_connect("$dbhost", "$dbuser", "$dbpass")or die("cannot connect");
//mysql_select_db("$dbname")or die("cannot select DB");

$result = $con->prepare("SELECT * FROM usuarios where Identif=?");
$result->bindValue(1, $UserID, PDO::PARAM_INT);
$result->execute();


$count = $result->rowCount();
$row = $result->fetch(PDO::FETCH_ASSOC);
$success ='NO';
if($count==1){
	$success ='SI';

    $UserID = $row['Identif'];
	$UserEmail= $row['email'];
    $UserName = $row['Name'];
    $UserSurname = $row['Surname'];
    $UserPhone = $row['telefono'];
    //$UserLogo = $row['ImageLogo'];
    $IdUsFIXED = $row['IdUsFIXED'];
    $IdUsFIXEDNAME = $row['IdUsFIXEDNAME'];
    $Timezone = $row['timezone'];
    $Place = $row['location'];
    $current_calling_doctor = '';
    $current_calling_doctor_name = '';
    
    if(isset($row['current_calling_doctor']))
    {
        
        $current_calling_doctor = $row['current_calling_doctor'];
        $doc_res = $con->prepare("SELECT Name,Surname FROM doctors WHERE id=?");
		$doc_res->bindValue(1, $current_calling_doctor, PDO::PARAM_INT);
		$doc_res->execute();
		
        $doc_row = $doc_res->fetch(PDO::FETCH_ASSOC);
        $current_calling_doctor_name = $doc_row['Name'].' '.$doc_row['Surname'];
    }
    $privilege=1;
     

    $IdDoctor = $row['IdInvite'];
 
    $resultD = $con->prepare("SELECT * FROM doctors where id=?");
	$resultD->bindValue(1, $IdDoctor, PDO::PARAM_INT);
	$resultD->execute();
	
	$rowD = $resultD->fetch(PDO::FETCH_ASSOC);
    $NameDoctor = $rowD['Name'];
    $SurnameDoctor = $rowD['Surname'];
	$DoctorEmail = $rowD['IdMEDEmail'];    
    
}
else
{
$exit_display = new displayExitClass();

$exit_display->displayFunction(2);
die;
}

$result = $con->prepare("SELECT * FROM lifepin");
$result->execute();
?>
<!DOCTYPE html>
<html lang="en" style="background: #F9F9F9;"><head>
    <meta charset="utf-8">
    <title>Inmers - Center Management Console</title>
    <link rel="icon" type="image/ico" href="favicon.ico"/>
	
	<!-- Create language switcher instance and set default language to en-->
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js" type="text/javascript"></script>
<script src="jquery-lang-js-master/js/jquery-cookie.js" charset="utf-8" type="text/javascript"></script>
<script src="jquery-lang-js-master/js/jquery-lang.js" charset="utf-8" type="text/javascript"></script>
<script type="text/javascript">
	var lang = new Lang('en');
	window.lang.dynamic('th', 'jquery-lang-js-master/js/langpack/th.json');


function delete_cookie( name ) {
  document.cookie = name + '=; expires=Thu, 01 Jan 1970 00:00:01 GMT;';
}

function setCookie(name,value,days) {
confirm('Would you like to switch languages?');
delete_cookie('lang');
    if (days) {
        var date = new Date();
        date.setTime(date.getTime()+(days*24*60*60*1000));
        var expires = "; expires="+date.toGMTString();
    }
    else var expires = "";
    document.cookie = name+"="+value+expires+"; path=/";
	
	pageRefresh(); 
}

function setCookie2(name,value,days) {
//confirm('Would you like to switch languages?');
delete_cookie('lang');
    if (days) {
        var date = new Date();
        date.setTime(date.getTime()+(days*24*60*60*1000));
        var expires = "; expires="+date.toGMTString();
    }
    else var expires = "";
    document.cookie = name+"="+value+expires+"; path=/";
	
	 
}

function pageRefresh(){
location.reload();
}

//alert($.cookie('lang'));

var langType = $.cookie('lang');

if(langType == 'th'){
var language = 'th';
}else{
var language = 'en';
}

if(langType == 'th'){
setTimeout(function(){
window.lang.change('th');
lang.change('th');
//alert('th');
}, 2000);
}else if(langType == 'en'){
setTimeout(function(){
window.lang.change('en');
lang.change('en');
//alert('th');
}, 2000);
} else {
setCookie('lang', 'th', 30);
window.lang.change('th');
lang.change('th');
}
</script>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
    <link href="css/style.css" rel="stylesheet">
    <link href="css/styleCol.css" rel="stylesheet">
    <link href="css/bootstrap.css" rel="stylesheet">
    <link rel="stylesheet" href="font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/jquery-ui-1.8.16.custom.css" media="screen"  />
    <link rel="stylesheet" href="css/fullcalendar.css" media="screen"  />
    <link rel="stylesheet" href="css/chosen.css" media="screen"  />
    <link rel="stylesheet" href="css/datepicker.css" >
    <link rel="stylesheet" href="css/colorpicker.css">
    <link rel="stylesheet" href="css/glisse.css?1.css">
    <link rel="stylesheet" href="css/jquery.jgrowl.css">
    <link rel="stylesheet" href="js/elfinder/css/elfinder.css" media="screen" />
    <link rel="stylesheet" href="css/jquery.tagsinput.css" />
    <link rel="stylesheet" href="css/demo_table.css" >
    <link rel="stylesheet" href="css/jquery.jscrollpane.css" >
    <link rel="stylesheet" href="css/validationEngine.jquery.css">
    <link rel="stylesheet" href="css/jquery.stepy.css" />
    <link rel="stylesheet" type="text/css" href="css/googleAPIFamilyCabin.css">
      <script type="text/javascript" src="js/42b6r0yr5470"></script>
	<link rel="stylesheet" href="css/icon/font-awesome.css">

    <link rel="stylesheet" href="css/bootstrap-responsive.css">
	<link rel="stylesheet" href="css/toggle-switch.css">
    <link rel="stylesheet" href="build/css/intlTelInput.css">
	
    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    <!--[if lte IE 8]><script type="text/javascript" src="/js/excanvas.min.js"></script><![endif]-->
 
    <!-- Le fav and touch icons -->
    <link rel="shortcut icon" href="favicon.ico">
    	<style>
		.ui-progressbar {
		position: relative;
		}
		.progress-label {
		position: absolute;
		left: 50%;
		top: 4px;
		font-weight: bold;
		text-shadow: 1px 1px 0 #fff;
		}
	</style>
	<style>
	#overlay {
	  background-color: none;
	  position: auto;
	  top: 0; right: 0; bottom: 0; left: 0;
	  opacity: 1.0; /* also -moz-opacity, etc. */
	  
    }
	#messagecontent {
	  white-space: pre-wrap;   
	}
	</style>
	  <style>
		#progressbar .ui-progressbar-value {
		background-color: #ccc;
		}
	  </style>

    <script type="text/javascript">
   
  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-37863944-1']);
  _gaq.push(['_setDomainName', 'health2.me']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>

  </head>

  <body style="background: #F9F9F9;">
      
     <!-- MODAL VIEW FOR SUMMARY -->
    <div id="summary_modal" lang="en" title="Summary" style="display:none; text-align:center; width: 1000px; height: 660px; overflow: hidden;">
    </div>  
      
    <!-- MODAL VIEW FOR SETUP -->
    <div id="setup_modal" title="SetUp" style="display:none; text-align:center; padding:20px; width: 600px; height: 310px;">
        <div id="setup_modal_container" style="width: 600px; height: 280px;">
            <h4 id="setup_title" lang="en">Change Password</h4>
            <br/>
            <div style="width: 15%; height:200px; float: left;">
                <button id="setup_menu_1" accesskey=""style="background-color: #22AEFF; color: #FFF; width: 50px; height: 50px; font-size: 24px; border: 0px solid #FFF; outline: 0px; border-top-left-radius: 10px; border-top-right-radius: 10px; float: left;">
                    <i class="icon-lock"></i>
                </button>
                <button id="setup_menu_2" style="background-color: #FBFBFB; color: #22AEFF; width: 50px; height: 50px; font-size: 24px; border: 1px solid #E6E6E6; outline: 0px; float: left;">
                    <i class="icon-time"></i>
                </button>
                <!--<button id="setup_menu_3" style="background-color: #FBFBFB; color: #22AEFF; width: 50px; height: 50px; font-size: 24px; border: 1px solid #E6E6E6; outline: 0px; float: left;">
                    <i class="icon-credit-card"></i>
                </button>-->
                <button id="setup_menu_4" style="background-color: #FBFBFB; color: #22AEFF; width: 50px; height: 50px; font-size: 24px; border: 1px solid #E6E6E6; outline: 0px; float: left;">
                    <i class="icon-map-marker"></i>
                </button>
                <button id="setup_menu_5" style="background-color: #FBFBFB; color: #22AEFF; width: 50px; height: 50px; font-size: 24px; border: 1px solid #E6E6E6; outline: 0px; border-bottom-left-radius: 10px; border-bottom-right-radius: 10px; float: left;">
                    <i class="icon-phone"></i>
                </button>
            </div>
            <div style="width: 85%; height: 200px; float: left;">
                <div id="setup_page_1">
                    <div style="width: 100%; height: 40px;">
                        <div style="float: left; margin-top: 6px; margin-bottom: -6px; color: #777; width: 200px; text-align: left;" lang="en">Type current password: </div>
                        <input id="pw1" type="password" style="float: left; height: 15px; width: 200px; padding: 7px; color: #444;" value="" />
                        <button id="change_password_validate_button" style="width: 75px; height: 30px; background-color: #52D859; color: #FFF; border-radius: 7px; border: 0px solid #FFF; float: left; margin-left: 10px; outline: 0px;">Validate</button>
                    </div>
                    <div id="change_password_validated_section" style="display: none;">
                        <div style="width: 100%; height: 40px;">
                            <div style="float: left; margin-top: 6px; margin-bottom: -6px; color: #777; width: 200px; text-align: left;" lang="en">Type new password: </div>
                            <input id="pw2" type="password" style="float: left; height: 15px; width: 200px; padding: 7px; color: #444;" value="" />
                        </div>
                        <div style="width: 100%; height: 40px;">
                            <div style="float: left; margin-top: 6px; margin-bottom: -6px; color: #777; width: 200px; text-align: left;" lang="en">Retype new password: </div>
                            <input id="pw3" type="password" style="float: left; height: 15px; width: 200px; padding: 7px; color: #444;" value="" />
                            <button id="change_password_finish_button" style="width: 75px; height: 30px; background-color: #52D859; color: #FFF; border-radius: 7px; border: 0px solid #FFF; float: left; margin-left: 10px; outline: 0px;">Finish</button>
                        </div>
                    </div>
                </div>
               
                <div id="setup_page_2" style="display: none;">
                    <select class="timezonepicker" id="timezone_picker" size="10" style="display:block; margin-top: 0px; width: 100%;">
                      <option value="-12.0">(GMT -12:00) Eniwetok, Kwajalein</option>
                      <option value="-11.0">(GMT -11:00) Midway Island, Samoa</option>
                      <option value="-10.0">(GMT -10:00) Hawaii</option>
                      <option value="-9.0">(GMT -9:00) Alaska</option>
                      <option value="-8.0">(GMT -8:00) Pacific Time (US &amp; Canada)</option>
                      <option value="-7.0">(GMT -7:00) Mountain Time (US &amp; Canada)</option>
                      <option value="-6.0">(GMT -6:00) Central Time (US &amp; Canada), Mexico City</option>
                      <option value="-5.0">(GMT -5:00) Eastern Time (US &amp; Canada), Bogota, Lima</option>
                      <option value="-4.0">(GMT -4:00) Atlantic Time (Canada), Caracas, La Paz</option>
                      <option value="-3.5">(GMT -3:30) Newfoundland</option>
                      <option value="-3.0">(GMT -3:00) Brazil, Buenos Aires, Georgetown</option>
                      <option value="-2.0">(GMT -2:00) Mid-Atlantic</option>
                      <option value="-1.0">(GMT -1:00 hour) Azores, Cape Verde Islands</option>
                      <option value="0.0">(GMT) Western Europe Time, London, Lisbon, Casablanca</option>
                      <option value="1.0">(GMT +1:00 hour) Brussels, Copenhagen, Madrid, Paris</option>
                      <option value="2.0">(GMT +2:00) Kaliningrad, South Africa</option>
                      <option value="3.0">(GMT +3:00) Baghdad, Riyadh, Moscow, St. Petersburg</option>
                      <option value="3.5">(GMT +3:30) Tehran</option>
                      <option value="4.0">(GMT +4:00) Abu Dhabi, Muscat, Baku, Tbilisi</option>
                      <option value="4.5">(GMT +4:30) Kabul</option>
                      <option value="5.0">(GMT +5:00) Ekaterinburg, Islamabad, Karachi, Tashkent</option>
                      <option value="5.5">(GMT +5:30) Bombay, Calcutta, Madras, New Delhi</option>
                      <option value="5.75">(GMT +5:45) Kathmandu</option>
                      <option value="6.0">(GMT +6:00) Almaty, Dhaka, Colombo</option>
                      <option value="7.0">(GMT +7:00) Bangkok, Hanoi, Jakarta</option>
                      <option value="8.0">(GMT +8:00) Beijing, Perth, Singapore, Hong Kong</option>
                      <option value="9.0">(GMT +9:00) Tokyo, Seoul, Osaka, Sapporo, Yakutsk</option>
                      <option value="9.5">(GMT +9:30) Adelaide, Darwin</option>
                      <option value="10.0">(GMT +10:00) Eastern Australia, Guam, Vladivostok</option>
                      <option value="11.0">(GMT +11:00) Magadan, Solomon Islands, New Caledonia</option>
                      <option value="12.0">(GMT +12:00) Auckland, Wellington, Fiji, Kamchatka</option>
                    </select>
                    <button id="timezone_button" style="width: 100%; height: 30px; border-radius: 5px; border: 0px solid #FFF; outline: 0px; color: #FFF; background-color: #22AEFF;">
                        Update
                    </button>
                </div>
                <div id="setup_page_3" style="display: none;">
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
                    <div style="width: 70%; margin-left: auto; margin-right: auto; height: 135px; overflow: scroll;">
                        

                    </div>
                    <div style="margin-top: 10px; width: 70%; margin-left: auto; margin-right: auto;">
                        <script>
                        function isNumberKey(evt)
                        {
                            var charCode = (evt.which) ? evt.which : event.keyCode
                            if (charCode > 31 && (charCode < 48 || charCode > 57))
                                return false;
                            return true;
                        }    
                        </script>
                        <input type="text" onkeypress="return isNumberKey(event)" id="credit_card_number" maxLength="16" placeholder="Enter card number" style="width: 220px; height: 20px; float: left; border-radius: 5px;">
                        <input type="text" onkeypress="return isNumberKey(event)" id="csv_code" maxLength="3" placeholder="CSV" style="width: 85px; height: 20px; margin-left: 18px; float: left; border-radius: 5px;">
                        <div style="color: #969696; width: 80px; float: left; text-align: left; padding-left: 5px; border-top-left-radius: 5px; border-bottom-left-radius: 5px; border: 1px solid #CACACA; height: 23px; padding-top: 5px; border-right: 0px solid #FFF;" lang="en">Exp. Date:</div>
                        <input type="month" style="width: 135px; height: 20px; float: left; font-size: 12px; border-radius: 0px; border-left: 0px solid #FFF; border-top-right-radius: 5px; border-bottom-right-radius: 5px;" />
                        <button style="width: 100px; height: 30px; background-color: #52D859; border-radius: 0px; border: 0px solid #FFF; color: #FFF; float: left; outline: 0px; margin-left: 18px; border-radius: 5px;" lang="en">Add Card</button>
                    </div>
                </div>
                <div id="setup_page_4" style="display: none;">
                    <div class="formRow" style="margin-left: 50px; margin-top: 20px;">
                        <label lang="en">Country: </label>
                        <div class="formRight">
                            <select id="country_setup" name ="country_setup"></select>
                        </div>
                    </div>
                    <div class="formRow" style="margin-left: 50px;">
                        <label lang="en">Region: </label>
                        <div class="formRight">
                            <select name ="state_setup" id ="state_setup"></select>
                        </div>
                    </div>
                    <script language="javascript">
                        //populateCountries("country_setup", "state_setup");
                    </script>
                    <button id="location_button" style="width: 100%; height: 30px; border-radius: 5px; border: 0px solid #FFF; outline: 0px; color: #FFF; background-color: #22AEFF; margin-top: 45px;" lang="en">
                        Update
                    </button>
                </div>
                <div id="setup_page_5" style="display: none;">
                    <div class="formRow" style="margin-left: 50px; margin-top: 20px; margin-bottom: 40px;">
                        <label lang="en">Phone: </label>
                        <div class="formRight">
                            <input id="setup_phone" type="text" name="phone" class="intermediate-input validate[required, funcCall[checkPhoneFormat]] span" placeholder="phone" title="Please insert your phone number including country code(just numbers, no special characters or punctuation signs)" style="width: 300px;"/>
                        </div>
                    </div>
                    <div class="formRow" style="margin-left: 50px; margin-top: 10px; margin-bottom: 20px;">
                        <label lang="en">Email: </label>
                        <div class="formRight">
                            <input type="text" id="setup_email" name ="email" style="width: 290px;" />
                        </div>
                    </div>

                    <button id="contact_button" style="width: 100%; height: 30px; border-radius: 5px; border: 0px solid #FFF; outline: 0px; color: #FFF; background-color: #22AEFF; margin-top: 45px;" lang="en">
                        Update
                    </button>
                </div>
            </div>
            
        </div>
        
        <div id="setup_modal_notification_container" style="width: 600px; height: 30px; opacity: 0.0;">
            <div id="setup_modal_notification" style="height: 30px; padding-top: 10px; width: 500px; background-color: #888; border-radius: 20px; margin-left: 50px; position: relative;"></div>
        </div>
    </div>
      
      
      <!-- MODAL VIEW TO FIND DOCTOR -->
    <div id="find_doctor_modal" lang="en" title="Find Doctor" style="display:none; text-align:center; padding:20px;">
        <div id="Talk_Section_1" style="display: block;">
            <!--<input type="text" style="width: 90%; margin-top: 15px; margin-bottom: 15px; height: 20px; color: #CACACA; padding: 5px;" placeholder="Search for a doctor..." value="" />-->
            <style>
                .recent_doctor_button{
                        height: 50px; 
                        width: 50px; 
                        margin: auto; 
                        color: #FFFFFF; 
                        background-color: #22AEFF;
                        outline: 0px;
                    }
                .recent_doctor_button_selected{
                        border: 1px solid #22aeff;
                        background-color: #22aeff; 
                        color: #FFF;
                        padding: 3px; 
                        width: 80%; 
                        margin: auto; 
                        height: 25px;
                        outline: 0px;
                    }
                .find_doctor_button
                {
                    width: 100px;
                    height: 30px;
                    border-radius: 7px;
                    font-size: 14px;
                    color: #FFFFFF;
                    border: 0px solid #FFF;
                    float: right;
                    margin-top: 3px;
                    margin-left: 10px;
                    outline: 0px;
                }
                .square_blue_button
                {
                    width: 110px;
                    height: 110px;
                    border-radius: 7px;
                    font-size: 14px;
                    color: #FFFFFF;
                    background-color: #22aeff;
                    border: 0px solid #FFF;
                    outline: 0px;
                    margin-top: 55px;
                    margin-left: 15px;
                    margin-right: 15px;
                    
                }
                .square_blue_button_disabled
                {
                    width: 110px;
                    height: 110px;
                    border-radius: 7px;
                    font-size: 14px;
                    color: #FFFFFF;
                    border: 0px solid #FFF;
                    outline: 0px;
                    margin-top: 55px;
                    margin-left: 15px;
                    margin-right: 15px;
                    background-color: #D4F0FF;
                    cursor: default;
                }
                .step_circle
                {
                    background-color: #909090;
                    padding-top: 5px;
                    padding-left: 2px;
                    width: 28px;
                    height: 25px;
                    border: 1px solid #909090;
                    border-radius: 15px;
                    color: #FFFFFF;
                    font-weight: bold;
                    float: left;
                    font-size: 12px;
                    <!--margin-right: 10px;-->
                }
                .step_bar
                {
                    background-color: #909090;
                    margin-top: 14px;
                    width: 10px;
                    height: 3px;
                    border: 1px solid #909090;
                    float: left;
                }
                .lit
                {
                    background-color: #52D859;
                    border: 1px solid #52D859;
                }
                .yes_no_button{
                    width: 60px;
                    height: 40px;
                    border-radius: 4px;
                    font-size: 14px;
                    color: #FFFFFF;
                    background-color: #22aeff;
                    border: 0px solid #FFF;
                    outline: 0px;
                    margin-top: 40px;
                    margin-left: 10px;
                    margin-right: 10px;
                }
            </style>
            <div style="width: 100%; height: 35px; margin-top: -5px; margin-left: -5px;">
                <p id="find_doctor_label" style="font-size: 18px; color: #CACACA; font-style: italic; float: right;"></p>
                <div id="step_circle_1" class="step_circle lit">1</div>
                    <div id="step_bar_1" class="step_bar"></div>
                <div id="step_circle_2" class="step_circle">2</div>
                    <div id="step_bar_2" class="step_bar"></div>
                <div id="step_circle_3" class="step_circle">3</div>
                    <div id="step_bar_3" class="step_bar"></div>
                <div id="step_circle_4" class="step_circle">4</div>
                    <div id="step_bar_4" class="step_bar"></div>
                <div id="step_circle_5" class="step_circle">5</div>
                    <div id="step_bar_5" class="step_bar"></div>
                <div id="step_circle_6" class="step_circle"><i class="icon-ok" style="font-size: 20px;"></i></div>
            </div>
            <div id="find_doctor_container" style="width: 100%; margin-top: 10px; height: 250px;">
                <div stlye="width: 100%; height: 250px;" id="find_doctor_main">
                    <button id="find_doctor_now_button" class="square_blue_button<?php 
                             

                                $res = $con->prepare("SELECT id FROM doctors WHERE telemed=1 AND in_consultation=0");
								$res->execute();
                                $num_rows = $res->rowCount();
                                if($num_rows == 0)
                                {
                                    echo "_disabled";
                                }
                            ?>">
                        <div style="margin-bottom: -8px;"><i class="icon-bolt" style="font-size: 40px;"></i></div>
                        <br/><span lang="en">Call Now</span>
                    </button>
                    <button id="find_doctor_my_doctors_button" class="square_blue_button<?php 
       
                        $result = $con->prepare("SELECT most_recent_doc FROM usuarios where Identif=?");
						$result->bindValue(1, $UserID, PDO::PARAM_INT);
						$result->execute();
						
                        $count = $result->rowCount();
                        $row = $result->fetch(PDO::FETCH_ASSOC);

                        $str = $row['most_recent_doc'];
                        if(strlen($str) < 3)
                        {
                            echo "_disabled";
                        }?>">
                        <div style="margin-bottom: -8px;"><i class="icon-user-md" style="font-size: 40px;"></i></div>
                        <br/><span lang="en">My Doctors</span>
                    </button>
                    <button id="find_doctor_appointment_button" class="square_blue_button" style="display:none;">
                        <div style="margin-bottom: -8px;"><i class="icon-calendar" style="font-size: 40px;"></i></div>
                        <br/><span lang="en">Appointment</span>
                    </button>
                </div>
                
                <!-- My Doctors Pages -->
                <div style="width: 100%; height: 235px; padding-top: 15px; display: none;" id="find_doctor_my_doctors_1">
                    <?php 
                  
                        $result = $con->prepare("SELECT most_recent_doc FROM usuarios where Identif=?");
						$result->bindValue(1, $UserID, PDO::PARAM_INT);
						$result->execute();
						
                        $count = $result->rowCount();
                        $row = $result->fetch(PDO::FETCH_ASSOC);


                        $str = $row['most_recent_doc'];
                        $str = str_replace(array("[", "]"), "", $str);
                        $ids = explode(",", $str);
                
                        date_default_timezone_set ("GMT");
                        $date = new DateTime('now');
                        for($i = 0; $i < count($ids); $i++)
                        {
                        
                            $doc_result = $con->prepare("SELECT Name,Surname,phone,location FROM doctors WHERE id=? AND telemed=1 AND in_consultation=0");
							$doc_result->bindValue(1, $ids[$i], PDO::PARAM_INT);
							$doc_result->execute();

                            if($doc_result->rowCount() > 0)
                            {   
                                $doc_row = $doc_result->fetch(PDO::FETCH_ASSOC);
                                $result2 = $con->prepare("SELECT * FROM timeslots WHERE doc_id=?");
								$result2->bindValue(1, $ids[$i], PDO::PARAM_INT);
								$result2->execute();
                                
                                $found = false;
                            
                        
                                while(($row2 = $result2->fetch(PDO::FETCH_ASSOC)) && !$found)
                                {
                                    $start = new DateTime($row2['week'].' '.$row2['start_time']);
                                    $end = new DateTime($row2['week'].' '.$row2['end_time']);
                                    $date_interval = new DateInterval('P'.$row2['week_day'].'D');
                                    $time_interval = new DateInterval('PT'.intval(substr($row2['timezone'], strlen($row2['timezone']) - 8, 2)).'H'.intval(substr($row2['timezone'], strlen($row2['timezone']) - 5, 2)).'M');
                                    if(substr($row2['timezone'], 0 , 1) != '-')
                                    {
                                        $time_interval->invert = 1;
                                    }
                                    $start->add($date_interval);
                                    $end->add($date_interval);
                                    $start->add($time_interval);
                                    $end->add($time_interval);
                                    if($start <= $date && $end >= $date)
                                    {
                                        // doctor is available
                                        $found = true;
                                        break;
                                    }
                                    
                                }
                                echo '<button id="recdochti_'.$ids[$i].'_'.$doc_row['phone'].'_'.$doc_row['Name'].'_'.$doc_row['Surname'].'_'.$doc_row['location'];
                                if($found)
                                {
                                    echo '_Available';
                                }
                                echo '" class="square_blue_button" style="width: 100px; height: 100px; margin-left: 3px; margin-right: 3px; padding: 0px;">Doctor<br/>'.$doc_row['Name'].' '.$doc_row['Surname'].'</button>';
                            }
                        }
                    ?>
                </div>
                
                <div style="width: 100%; height: 235px; padding-top: 15px; display: none;" id="find_doctor_my_doctors_2">
                    <div style="width: 100%; height: 75px;">
                        
                        <p style="float: right; margin-top: 5px; margin-right: 50px;">
						   <input type="checkbox" id="in_location_checkbox">
            			   <label for="in_location_checkbox"><span></span></label>
                        </p>
                        <p style="text-align: left; margin-top: 30px; margin-bottom: -30px; margin-left: 50px;" id="doctor_location_text" lang="en">Doctor Janme Doe is in <strong>TEXAS</strong>.<br/>Please confirm that you are in <strong>TEXAS</strong> as well.</p>
                    </div>
                    <!--
                    <div style="width: 90%; margin-left: 10%; height: 50px; margin-top: 7px;">
                        <p style="text-align: left; float: left;" lang="en">Video or phone consultation?</p>
                        
                        <div style="width: 100px; height: 30px; border-radius: 3px; background-color: #535353; float: left; margin-left:105px; margin-top: -6px;">
                            <button style="width: 50px; height: 30px; border-top-left-radius: 3px; border-bottom-left-radius: 3px; background-color: #22aeff; border: 0px solid #FFF; color: #FFF; float: left; outline: 0px;" id="find_doctor_video_button_2">
                                <i class="icon-facetime-video"></i>
                            </button>
                            <button style="width: 50px; height: 30px; border-top-right-radius: 3px; border-bottom-right-radius: 3px; background-color:  #535353; border: 0px solid #FFF; color: #FFF; float: left; outline: 0px;" id="find_doctor_phone_button_2">
                                <i class="icon-phone"></i>
                            </button>
                        </div>
                    </div>
                    -->
                </div>
                
                <div style="width: 100%; height: 235px; padding-top: 15px; display: none;" id="find_doctor_my_doctors_3">
                    <p style="margin-top: 30px; margin-bottom: -30px;" id="doctor_oncall_text" lang="en">Doctor Jane Doe is ON CALL NOW!<br/>Would you like to connect now?</p>
                    <button class="yes_no_button" id="connect_now_yes" lang="en">Yes</button>
                    <button class="yes_no_button" id="connect_now_no" lang="en">No</button>
                </div>
                <!-- End My Doctors Pages -->
                
                <!-- Appointment Pages -->
               
                <div style="width: 100%; height: 235px; padding-top: 15px; display: none;" id="find_doctor_appointment_1">
                    <div style="width: 100%; height: 140px;">
                        <p lang="en">Which area will you be calling from?</p>
                        <div class="formRow" style="margin-left: 50px;">
                            <label lang="en">Country: </label>
                            <div class="formRight">
                                <select id="country" name ="country" onclick="toggleRegion();"></select>
                            </div>
                        </div>
                        <div class="formRow" id="region" style="margin-left: 50px;">
                            <label lang="en">Region: </label>
                            <div class="formRight">
                                <select name ="state" id ="state"></select>
                            </div>
                        </div>
                    </div>
                    
                   
                    
                    <script type= "text/javascript" src = "js/countries.js"></script>
                    <script language="javascript">
                        populateCountries("country", "state");
                    </script>
                </div>
                <div style="width: 100%; height: 235px; padding-top: 15px; display: none;" id="find_doctor_appointment_2">
                    <button id="find_doctor_general_practicioner_hti" class="square_blue_button" style="float: right; margin-top: 15px;">
                        <div style="margin-bottom: -8px;"><i class="icon-user-md" style="font-size: 40px;"></i></div>
                        <br/><span lang="en">General Practicioner</span>
                    </button>
                    <div style="width: 400px; height: 140px;">
                        <select name="speciality" id="speciality" size="6" style="float: left; width: 360px; margin-top: 15px;">
                            <option value="Allergy and Immunology">Allergist / Immunologist</option>
                            <option value="Anaesthetics">Aesthetician</option>
                            <option value="Cardiology">Cardiologist</option>
                            <option value="Cardiothoracic Surgery">Cardiothoracic Surgeon</option>
                            <option value="Child & Adolescent Psychiatry">Child & Adolescent Psychiatrist</option>
                            <option value="Clinical Neurophysiology">Clinical Neurophysiologist</option>
                            <option value="Dermato-Venereology">Dermato-Venereologist</option>
                            <option value="Dermatology">Dermatologist</option>
                            <option value="-Emergency Medicine">Emergency Medicine Specialist</option>
                            <option value="Endocrinology">Endocrinologist</option>
                            <option value="Gastroenterology">Gastroenterologist</option>
                            <option value="General Surgery">General Surgeon</option>
                            <option value="Geriatrics">Geriatrician</option>
                            <option value="Gynaecology and Obstetrics">Gynaecologist / Obstetrician</option>
                            <option value="Health Informatics">Health Informatics Specialist</option>
                            <option value="Infectious Diseases">Infectious Disease Specialist</option>
                            <option value="Internal Medicine">Internal Medicine Specialist</option>
                            <option value="Interventional Radiology">Interventional Radiologist</option>
                            <option value="Microbiology">Microbiologist</option>
                            <option value="Neonatology">Neonatologist</option>
                            <option value="Nephrology">Nephrologist</option>
                            <option value="Neurology">Neurologist</option>
                            <option value="Neuroradiology">Neuroradiologist</option>
                            <option value="Neurosurgery">Neurosurgeon</option>
                            <option value="Nuclear Medicine">Nuclear Medicine Specialist</option>
                            <option value="Occupational Medicine">Occupational Medicine Specialist</option>
                            <option value="Oncology">Oncologist</option>
                            <option value="Ophthalmology">Ophthalmologist</option>
                            <option value="Oral and Maxillofacial Surgery">Oral and Maxillofacial Surgeon</option>
                            <option value="Orthopaedics">Orthopedician</option>
                            <option value="Otorhinolaryngology">Otorhinolaryngologist</option>
                            <option value="Paediatric Cardiology">Paediatric Cardiologist</option>
                            <option value="Paediatric Surgery">Paediatric Surgeon</option>
                            <option value="Paediatrics">Paediatrician</option>
                            <option value="Pathology">Pathologist</option>
                            <option value="Physical Medicine and Rehabilitation">Physical Medicine and Rehabilitation Specialist</option>
                            <option value="Plastic, Reconstructive and Aesthetic Surgery">Plastic, Reconstructive and Aesthetic Surgeon</option>
                            <option value="Pneumology">Pulmonologist</option>
                            <option value="Psychiatry">Psychiatrist</option>
                            <option value="Public Health">Public Health Specialist</option>
                            <option value="Radiology">Radiologist</option>
                            <option value="Radiotherapy">Radiotherapist</option>
                            <option value="Stomatology">Stomatologist</option>
                            <option value="Vascular Medicine">Vascular Medicine Specialist</option>
                            <option value="Vascular Surgery">Vascular Surgeon</option>
                            <option value="Urology">Urologist</option>
                        </select>
                    </div>
                </div>
                <div style="width: 100%; height: 235px; padding-top: 15px; display: none;" id="find_doctor_appointment_3">
                    <br/><br/><br/><br/>
                    <p id="not_found_text" style="color: #FF3730; font-weight: bold; text-align: center;">Sorry, we could not find<br/>any general practicioners in your area.</p>
                </div>
                <div style="width: 100%; height: 235px; padding-top: 15px; display: none;" id="find_doctor_time">
                    <style>
                        .days_button{
                            width: 47px;
                            height: 50px;
                            font-size: 14px;
                            color: #FFFFFF;
                            background-color: #22AEFF;
                            border: 0px solid #FFF;
                            outline: 0px;
                            margin-top: 55px;
                            margin-right: 2px;
                            float: left;
                        }
                        .day_selected{
                            background-color: #1673A5;
                        }
                        .day_disabled{
                            cursor: default;
                            background-color: #B3E4FF;
                        }
                        .slots_button{
                            width: 125px;
                            height: 30px;
                            font-size: 14px;
                            color: #FFFFFF;
                            background-color: #FF8C2C;
                            border: 0px solid #FFF;
                            outline: 0px;
                            margin-bottom: 2px;
                            float: right;
                        }
                        .slot_selected{
                            background-color: #AA5D1D;
                        }
                        .slot_disabled{
                            cursor: default;
                            background-color: #FFDABC;
                        }
                    </style>
                    <div style="height: 100%; float: right; margin-top: -20px; width: 20px;">
                        <i class="icon-chevron-left" id="time_selector_1" style="display: none;"></i>
                    </div>
                    <div style="width: 23%; height: 100%; float: right; margin-top: -20px;">
                        <button class="slots_button" id="8_10_am" style="border-top-left-radius: 4px; border-top-right-radius: 4px;">8 to 10 am</button>
                        <button class="slots_button" id="10_12">10 to 12 pm</button>
                        <button class="slots_button" id="12_2">12 to 2 pm</button>
                        <button class="slots_button" id="2_4">2 to 4 pm</button>
                        <button class="slots_button" id="4_6">4 to 6 pm</button>
                        <button class="slots_button" id="6_8">6 to 8 pm</button>
                        <button class="slots_button" id="8_10_pm" style="border-bottom-left-radius: 4px; border-bottom-right-radius: 4px;">8 to 10 pm</button>
                    </div>
                    <div style="width: 70%; height: 75px; float: left;">
                        <button class="days_button" id="sun" style="border-top-left-radius: 4px; border-bottom-left-radius: 4px;">Sun<br/>
                            <?php $today = new DateTime('now'); $today_dow = intval($today->format('N')); $dow = 0;
                            if($today_dow > $dow)
                            {
                                $date_interval = new DateInterval('P'.strval((7 - $today_dow) + $dow).'D');
                                $today->add($date_interval);
                            }
                            else if($today_dow < $dow)
                            {
                                $date_interval = new DateInterval('P'.strval($dow - $today_dow).'D');
                                $today->add($date_interval);
                                
                            }
                            echo '<span style="font-size: 10px;">'.$today->format('d M').'</span>';
                            echo '<input type="hidden" value="'.$today->format('Y-m-d').'" />';
                            ?>
                        <input type="hidden" value="" />
                        <input type="hidden" value="" />
                        </button>
                        <button class="days_button" id="mon">Mon<br/>
                            <?php $today = new DateTime('now'); $today_dow = intval($today->format('N')); $dow = 1;
                            if($today_dow > $dow)
                            {
                                $date_interval = new DateInterval('P'.strval((7 - $today_dow) + $dow).'D');
                                $today->add($date_interval);
                            }
                            else if($today_dow < $dow)
                            {
                                $date_interval = new DateInterval('P'.strval($dow - $today_dow).'D');
                                $today->add($date_interval);
                                
                            }
                            echo '<span style="font-size: 10px;">'.$today->format('d M').'</span>';
                            echo '<input type="hidden" value="'.$today->format('Y-m-d').'" />';
                            ?>
                        <input type="hidden" value="" />
                        <input type="hidden" value="" />
                        </button>
                        <button class="days_button" id="tues">Tues<br/>
                            <?php $today = new DateTime('now'); $today_dow = intval($today->format('N')); $dow = 2;
                            if($today_dow > $dow)
                            {
                                $date_interval = new DateInterval('P'.strval((7 - $today_dow) + $dow).'D');
                                $today->add($date_interval);
                            }
                            else if($today_dow < $dow)
                            {
                                $date_interval = new DateInterval('P'.strval($dow - $today_dow).'D');
                                $today->add($date_interval);
                                
                            }
                            echo '<span style="font-size: 10px;">'.$today->format('d M').'</span>';
                            echo '<input type="hidden" value="'.$today->format('Y-m-d').'" />';
                            ?>
                        <input type="hidden" value="" />
                        <input type="hidden" value="" />
                        </button>
                        <button class="days_button" id="wed">Wed<br/>
                            <?php $today = new DateTime('now'); $today_dow = intval($today->format('N')); $dow = 3;
                            if($today_dow > $dow)
                            {
                                $date_interval = new DateInterval('P'.strval((7 - $today_dow) + $dow).'D');
                                $today->add($date_interval);
                            }
                            else if($today_dow < $dow)
                            {
                                $date_interval = new DateInterval('P'.strval($dow - $today_dow).'D');
                                $today->add($date_interval);
                                
                            }
                            echo '<span style="font-size: 10px;">'.$today->format('d M').'</span>';
                            echo '<input type="hidden" value="'.$today->format('Y-m-d').'" />';
                            ?>
                        <input type="hidden" value="" />
                        <input type="hidden" value="" />
                        </button>
                        <button class="days_button" id="thur">Thur<br/>
                            <?php $today = new DateTime('now'); $today_dow = intval($today->format('N')); $dow = 4;
                            if($today_dow > $dow)
                            {
                                $date_interval = new DateInterval('P'.strval((7 - $today_dow) + $dow).'D');
                                $today->add($date_interval);
                            }
                            else if($today_dow < $dow)
                            {
                                $date_interval = new DateInterval('P'.strval($dow - $today_dow).'D');
                                $today->add($date_interval);
                                
                            }
                            echo '<span style="font-size: 10px;">'.$today->format('d M').'</span>';
                            echo '<input type="hidden" value="'.$today->format('Y-m-d').'" />';
                            ?>
                        <input type="hidden" value="" />
                        <input type="hidden" value="" />
                        </button>
                        <button class="days_button" id="fri">Fri<br/>
                            <?php $today = new DateTime('now'); $today_dow = intval($today->format('N')); $dow = 5;
                            if($today_dow > $dow)
                            {
                                $date_interval = new DateInterval('P'.strval((7 - $today_dow) + $dow).'D');
                                $today->add($date_interval);
                            }
                            else if($today_dow < $dow)
                            {
                                $date_interval = new DateInterval('P'.strval($dow - $today_dow).'D');
                                $today->add($date_interval);
                                
                            }
                            echo '<span style="font-size: 10px;">'.$today->format('d M').'</span>';
                            echo '<input type="hidden" value="'.$today->format('Y-m-d').'" />';
                            ?>
                        <input type="hidden" value="" />
                        <input type="hidden" value="" />
                        </button>
                        <button class="days_button" id="sat" style="border-top-right-radius: 4px; border-bottom-right-radius: 4px;">Sat<br/>
                            <?php $today = new DateTime('now'); $today_dow = intval($today->format('N')); $dow = 6;
                            if($today_dow > $dow)
                            {
                                $date_interval = new DateInterval('P'.strval((7 - $today_dow) + $dow).'D');
                                $today->add($date_interval);
                            }
                            else if($today_dow < $dow)
                            {
                                $date_interval = new DateInterval('P'.strval($dow - $today_dow).'D');
                                $today->add($date_interval);
                                
                            }
                            echo '<span style="font-size: 10px;">'.$today->format('d M').'</span>';
                            echo '<input type="hidden" value="'.$today->format('Y-m-d').'" />';
                            ?>
                        <input type="hidden" value="" />
                        <input type="hidden" value="" />
                        </button>
                    </div>
                    <div style="height: 75px; width: 70%; float: left; margin-top: 30px; margin-left: 0px;">
                        <i class="icon-chevron-up" id="day_selector_1" style="float: left; display: none;"></i>
                    </div>
                    
                </div>
                <div style="width: 100%; height: 235px; padding-top: 15px; display: none;" id="find_doctor_receipt">
                    <ul style="color: #22AEFF; margin-top: 50px; margin-left: 120px;">
                        <li style="text-align: left;">Receipt: <strong>HTI - CR102388</strong></li>
                        <li style="text-align: left;" lang="en"><strong>Video Consultation</strong></li>
                        <li style="text-align: left;" lang="en">With a <strong>General Practicioner</strong></li>
                        <li style="text-align: left;">next <strong>Thursday</strong> between <strong>12 and 2 pm</strong></li>
                    </ul>
                </div>
                <!-- End Appointment Pages -->
                
                <div style="width: 100%; height: 235px; padding-top: 15px; display: none;" id="find_doctor_confirmation">
                    <p style="color: #22AEFF; margin-top: 50px;">
                        <strong lang="en">Thank you!</strong><br/><strong lang="en">Your consultation appointment is confirmed</strong><br/><span lang="en">Please be ready at the selected date and time, and follow the instructions that we sent you.</span>
                    </p>
                </div>
            </div>
            <div style="width: 100%; height: 40px; margin-top: 10px;">
                <button id="find_doctor_cancel_button" class="find_doctor_button" style="background-color: #D84840; float:left; margin-left: 0px;" lang="en">Cancel</button>
                <button id="find_doctor_close_button" class="find_doctor_button" style="background-color: #52D859; display: none; margin-left: auto; margin-right: auto; float: none;" lang="en">Close</button>
                <button id="find_doctor_next_button_hti" class="find_doctor_button" style="background-color: #52D859;" lang="en">Next</button>
                <button id="find_doctor_previous_button_hti" class="find_doctor_button" style="background-color: #22aeff;" lang="en">Previous</button>
            </div>

        </div>
        <div id="Talk_Section_2" style="display: none;">
            <button style="width: 200px; heightL 30px; background-color: #22aeff; color: #FFF; border: 0px solid #FFF; margin: auto; margin-top: 15px; margin-left: 20px; border-radius: 7px; outline: 0px; float: left;" id="video_call_button" lang="en">Video Call</button>
            <button style="width: 200px; heightL 30px; background-color: #22aeff; color: #FFF; border: 0px solid #FFF; margin: auto; margin-top: 15px; margin-right: 20px; border-radius: 7px; outline: 0px; float: right;" id="phone_call_button" lang="en">Phone Call</button>
           
            
        </div>
        <div id="Talk_Section_3" style="display: none;">
            <br/>
            <p lang="en">No doctors are available at this time. Please try again later.</p>
           
            
        </div>
        <div id="Talk_Section_4" style="display: none;">
            <br/>
            <p lang="en">We are now calling your doctor, please wait...</p>
           
            
        </div>
    </div>
    <!-- END MODAL VIEW TO FIND DOCTOR -->

<input type="hidden" id="NombreEnt" value="<?php echo $NombreEnt; ?>">
<input type="hidden" id="PasswordEnt" value="<?php echo $PasswordEnt; ?>">
<input type="hidden" id="UserHidden">

	<!--Header Start-->
    
           <div class="header" >
     
        
      
     	<input type="hidden" id="USERID" Value="<?php echo $UserID; ?>">	
    	<input type="hidden" id="MEDID" Value="<?php echo $MedID; ?>">	
    	<input type="hidden" id="IdMEDEmail" Value="<?php echo $MedUserEmail; ?>">	
    	<input type="hidden" id="IdMEDName" Value="<?php echo $MedUserName; ?>">	
    	<input type="hidden" id="IdMEDSurname" Value="<?php echo $MedUserSurname; ?>">	
    	<input type="hidden" id="IdMEDSurname" Value="<?php echo $MedUserSurname; ?>">	
    	<input type="hidden" id="IdMEDLogo" Value="<?php echo $MedUserLogo; ?>">
        <input type="hidden" id="USERNAME" Value="<?php echo $UserName; ?>">	
        <input type="hidden" id="USERSURNAME" Value="<?php echo $UserSurname; ?>">	
        <input type="hidden" id="USERPHONE" Value="<?php echo $UserPhone; ?>">	
        <input type="hidden" id="CURRENTCALLINGDOCTOR" Value="<?php echo $current_calling_doctor; ?>">	
        <input type="hidden" id="CURRENTCALLINGDOCTORNAME" Value="<?php echo $current_calling_doctor_name; ?>" />
  		<input type="hidden" id="GRANTACCESS" Value="<?php echo $GrantAccess; ?>" />
               
           <a href="index-col.html" class="logo"><h1>Health2me</h1></a>
           <div style="float:left;">
		   <a href="#en" onclick="setCookie('lang', 'en', 30); return false;"><img src="images/icons/english.png"></a>
		   </br>
			<a href="#sp" onclick="setCookie('lang', 'th', 30); return false;"><img src="images/icons/spain.png"></a>
			</div>
           <div class="pull-right">
           
            
           <!--Button User Start-->
               
     
		   <div class="btn-group pull-right" >
         
           
            <a class="btn btn-profile dropdown-toggle" id="button-profile" data-toggle="dropdown" href="#">
      
                    <span class="name-user"><strong lang="en">Welcome</strong> <?php echo $UserName.' '.$UserSurname; ?></span> 
          
                
                
             <?php 
             $hash = md5( strtolower( trim( $UserEmail ) ) );
             $avat = 'identicon.php?size=29&hash='.$hash;
			?>	
              <span class="avatar" style="background-color:WHITE;"><img src="<?php echo $avat; ?>" alt="" ></span> 
              <span class="caret"></span>
            </a>
            <div class="dropdown-menu" id="prof_dropdown">
            <div class="item_m"><span class="caret"></span></div>
            <ul class="clear_ul" >
			<li>
			 <?php if ($privilege==1)
					echo '<a href="UserDashboard.php">';
				   else if($privilege==2)
					echo '<a href="patients.php">';
			 ?>
			<i class="icon-globe"></i> <span lang="en">Home</span></a></li>
              
              <!--<li><a href="medicalConfiguration.php" lang="en"><i class="icon-cog"></i> Settings</a></li>-->
              <li><a href="logout.php" lang="en"><i class="icon-off"></i> Sign Out</a></li>
            </ul>
            </div>
          </div>
          <!--Button User END-->  
          
          </div>
    </div>
    <!--Header END-->

<!-- Start of code inserted from userdashboard-new-pallab.php -->
<!-- Start of code for displaying modal window for Send buttton-->
<div id="modal_send" style="display:none; text-align:center; padding:10px;">
    
        <form>
         <span lang="en">Email of Doctor</span> <input type = "text" id="EmailID" width ="80" value='' />
        </form>
    
        <button id="CaptureEmail2Send2Doc" style="border:0px;border-radius:6px;height: 24px; width:50px; color:#FFF; background-color:#22aeff;float:bottom; margin-top:20px;" lang="en">Send</button>
    
</div>
<!-- End of code for displaying modal window for Send buttton-->

<!-- Start of code for displaying modal window for Request button -->

<div id="modal_request" style="display:none; text-align:center; padding:10px;">
    
        <form>
         <span style="" lang="en">Email of Doctor</span> <input type = "text" id = "EmailIDRequestPage" style="width:250px">
        </form>
        
        <div style="text-align: top;margin-top:20px">
           <!--<form>-->
            <span style="" lang="en">Message</span> <textarea name = "Message" id = "MessageForDoctor" style="height:100px;width:300px;" ></textarea>      
           <!--</form>-->
        </div>
        
        <div> 
            <button id="CaptureEmail2Request2Doc"  style="border:0px;color:#FFF;border-radius:6px;height: 40px; width:138px;margin-top:20px;margin-right:40px;margin-left:40px;background-color:#22aeff;" lang="en">Request Reports</button>
        </div>
        
    
</div>

<!-- End of code for displaying modal window for Request button -->   

 
   	 <!--- VENTANA MODAL  This has been added to show individual message content which user click on the inbox messages ---> 
   	 <button id="message_modal" data-target="#header-message" data-toggle="modal" class="btn btn-warning" style="display: none;" lang="en">Modal with Header</button> 
   	  <div id="header-message" class="modal hide" style="display: none;" aria-hidden="true">
         <div class="modal-header">
             <button class="close" type="button" data-dismiss="modal">×</button>
                  <span lang="en">Message Details</span>
         </div>
         <div class="modal-body">
         <div class="formRow" style=" margin-top:-10px; margin-bottom:10px;">
             <span id="ToDoctor" style="color:#2c93dd; font-weight:bold;">TO <?php echo 'Dr. '.$NameDoctor.' '.$SurnameDoctor; ?></span><input type="hidden" id="IdDoctor" value='<?php echo $IdDoctor; ?>'/>
         </div>
         <textarea  id="messagedetails" class="span message-text" style="height:200px;" name="message" rows="1"></textarea>
         
		 <form id="replymessage" class="new-message">
                   <div class="formRow">
                        <label lang="en">Subject: </label>
                        <div class="formRight">
                            <input type="text" id="subjectname_inbox" name="name"  class="span"> 
                        </div>
                   </div>
				   <div class="formRow">
						<label lang="en">Attachments: </label>
						<div id="attachreportdiv" class="formRight">
							<input type="button" class="btn btn-success" value="Attach Reports" id="attachreports">
						</div>
				   </div>
                   <div class="formRow">
                        <label lang="en">Message:</label>
                        <div class="formRight tooltip-top" style="height:120px;">
                            <textarea  id="messagecontent_inbox" class="span message-text" name="message" style="height:90px;" rows="1"></textarea>
                            
                            <div class="clear"></div>
                        </div>
                   </div>
            </form>
			<div id="attachments" style="display:none">
			
			
			
			</div>
		 </div>
         <input type="hidden" id="Idpin">
        <!-- <input type="hidden" id="docId" value="<?php echo $IdMed; ?>"/> -->
         <input type="hidden" id="userId" value="<?php echo $IdUsu; ?>" />
         <div class="modal-footer">
		     <input type="button" class="btn btn-info" value="Send messages" id="sendmessages_inbox">
             <input type="button" class="btn btn-success" value="Attach" id="Attach">	
	         <input type="button" class="btn btn-success" value="Reply" id="Reply">			 
	         <a href="#" class="btn btn-primary" data-dismiss="modal" id="CloseMessage" lang="en">Close</a>
         </div>
      </div>  
	  <!--- VENTANA MODAL  ---> 	
      
      <!--- VENTANA MODAL NUMERO 2 ---> 
   	  <!--<button id="BotonModal" data-target="#header-modal" data-toggle="modal" class="btn btn-warning" style="display: none;">Modal with Header</button>-->
   	  <div id="header-modal2" class="modal hide" style="display: none; height:470px;" aria-hidden="true">
         <div class="modal-header">
             <button class="close" type="button" data-dismiss="modal">×</button>
                 <h4 lang="en">Upload New Report</h4>
                 <input type="hidden" id="URLIma" value="zero"/>
         </div>
         
         <div class="modal-body" id="ContenidoModal2" style="height:320px;">
             <div  id="RepoThumb" style="width:70px; float:right; -webkit-box-shadow: 3px 3px 14px rgba(25, 25, 25, 0.5); -moz-box-shadow:3px 3px 14px rgba(25, 25, 25, 0.5); box-shadow:3px 3px 14px rgba(25, 25, 25, 0.5);"></div>
           <div class="ContenDinamico2">
        
           <!-- <a href="#" class="btn btn-success" id="ParseReport" style="margin-top:10px; margin-bottom:10px;">Parse this report now.</a> -->

           		<form action="upload_fileUSER.php?queId=<?php echo $UserID ?>&from=0" method="post" enctype="multipart/form-data">
	           		<label for="file" lang="en">Report:</label>
	           		<input type="file" class="btn btn-success" name="file" id="file" style="margin-right:20px;"><br>


            </div>  

         </div>
         <div class="modal-footer">
	         <!--<input type="button" class="btn btn-success" value="Confirm" id="ConfirmaLink">--->
             <!--<a href="#" class="btn btn-success" data-dismiss="modal" id="GrabaDatos">Update Data</a>-->
             <input type="submit" class="btn btn-success" name="submit" value="Submit">
             <a href="#" class="btn btn-primary" data-dismiss="modal" id="CloseModal"  lang="en">Close</a>
             
             	</form>

         </div>
       </div>
	  <!--- VENTANA MODAL NUMERO 2  ---> 
 
    <!--Content Start-->
	<div id="content" style="background: #F9F9F9; padding-left:0px;">
    
    	    
	 <!--SpeedBar Start--->
     <div class="speedbar">
     <div class="speedbar-content">
     <ul class="menu-speedbar">
		
         <?php if ($privilege==1)
				 echo '<li><a href="UserDashboard.php" class="act_link" lang="en">Home</a></li>';
		 ?>
         <li><a href="logout.php" style="color:#d9d907;" lang="en">Sign Out</a></li>
     </ul>
     </div>
     </div>
     <!--SpeedBar END-->
     
     
     
     <!--CONTENT MAIN START-->
     <div class="content">
     <div class="grid" class="grid span4" style="display:block; width:1000px; height:675px; margin: 0 auto; margin-top:30px; padding-top:30px;">
 		     <div class="row-fluid" style="display:block; height:200px;">	            
                <div style="display:block; margin:15px; padding-top:0px;">
                             <span class="label label-success" style="left:0px; margin-left:10px; margin-top:0px; font-size:30px;" lang="en">User Dashboard</span>
                             <div class="clearfix" style="margin-bottom:20px;"></div>
                                          <?php
                                          $hash = md5( strtolower( trim( $UserEmail ) ) );
                                          $avat = 'identicon.php?size=75&hash='.$hash;
                                            ?>	
                     
                             <img src="<?php echo $avat; ?>" style="float:left; margin-right:40px; margin-left:10px; font-size:18px; padding:5px 10px 5px 10px; font-family: “Andale Mono”, AndaleMono, monospace; box-shadow: 3px 3px 15px #CACACA;"/>
                           
                            <div style="vertical-align:top; display:inline-block; margin-right:40px;">
                             <span id="NombreComp" style="display: block; font: bold 24px Arial, Helvetica, sans-serif; color: #3D93E0; cursor: auto; margin-left:20px; "><?php echo $UserName;?> <?php echo $UserSurname;?></span>
                             <span id="IdUsFIXED" style="font-size: 12px; color: #3D93E0; font-weight: normal; font-family: Arial, Helvetica, sans-serif; display: block; margin-left:20px;"><?php echo $IdUsFIXED;?></span>
                             <span id="IdUsFIXEDNAME" style="display: block; font-size: 14px; color: GREY; font-weight: bold; font-family: Arial, Helvetica, sans-serif; margin-left:20px;"><?php echo $IdUsFIXEDNAME;?></span>
                             <span id="email" style="display: block; font-size: 14px; color: #5EB529; font-weight: bold; font-family: Arial, Helvetica, sans-serif; margin-left:20px;"><?php echo $UserEmail;?></span>
                            </div> 
                          
                            <div style="vertical-align:top; float:right; display:inline-block; margin-left:20px; margin-right:30px;">
                                
                                        <a id='MedHistory'  value="MedHistory" class="btn ButtonDrAct"  title="Access and update your medical summary" >  
                                        
		                     	        <img src="images/icons/medhistory_svg.png" style=" margin-top:-2px; width:50px; height:50px;">
		                     	        <div style="line-height:15px; margin-left:6px;"><span lang="en">History</span></div>
                                        
	                     	         </a>
                              
                                <a href="patientdetailMED-new.php?IdUsu=<?php echo $UserID;?>" id='Records'  value="Records" class="btn ButtonDrAct"  title="Access your medical records" > 
		                     	        <img src="images/icons/records_svg.png" style=" margin-top:-2px; width:50px; height:50px;">
		                     	        <div style="line-height:15px; margin-left:6px;"><span lang="en">Records</span></div>
	                     	         </a>
                                     <a id='Talk_hti'  value="Talk_hti" class="btn ButtonDrAct"  title="Talk to a doctor by phone or by video chat" > 
		                     	        <img src="images/icons/Talk_svg.png" style=" margin-top:-2px; width:50px; height:50px;">
		                     	        <div style="line-height:15px; margin-left:0px;"><span lang="en">Talk To Doctor</span></div>
	                     	         </a>
                          	         <a id='SetUp'  value="Account" class="btn ButtonDrAct" title="Configure your account settings"> 
		                     	        <img src="images/icons/Configuration_svg.png" style="margin-top:-2px; width:50px; height:50px;">
		                     	        <div style="line-height:15px; margin-left:6px;"><span lang="en">SetUp</span></div>
	                     	         </a>
                                 
                             </div> 
                      </div>
             </div>
             <!--
             <img src="images/GooglePlay.png"  width="120" style="margin:30px; margin-top:0px;margin-bottom:20px;"/>
			 <img src="images/AppStore.png"  width="120" style="margin:30px; margin-top:0px;margin-bottom:20px; margin-left:0px;"/>
			 -->
			 <div style="display:none; visibility: hidden; margin-top:-50px; margin-right:30px; padding:10px; float:right; border:solid; height:80px; width:500px; border:solid 1px #cacaca; -webkit-border-radius: 3px; -moz-border-radius: 3px; border-radius: 3px;">
				 <span style="font-size: 16px; color:#22aeff;" lang="en">This season the flu strain is more dangerous: </span><span style="font-size: 14px; color:grey;" lang="en">  Please be aware of the flu shots to prevent major complications. If there is any doubt please <span style="font-weight:bold;">call our office</span> or go to our Health2Me profile page and <span style="font-weight:bold;">appoint a meeting</span>. </span>
				 
			 </div>
			 			 
<div id="modalContents" style="display:none; text-align:center; padding-top:20px;">
		<a id='ButtonSkype' href="skype:health2me?call" value="Telemedicine" class="btn" title="Telemedicine" style="text-align:center; padding:15px; width:40px; height:40px; color:#22aeff; margin-left:50px; float:left;"> 
         	<i class="icon-skype icon-2x" style="margin:0 auto; padding:0px; color:#22aeff;"></i>
         	<div style="width:100%; line-height:5px;"></div>
         	<span lang="en">Skype</span>
        </a>
		<a id='ButtonWeemo'  value="Telemedicine" class="btn" title="Telemedicine" style="text-align:center; padding:15px; width:40px; height:40px; color:#22aeff; margin-left:20px; float:left;"> 
         	<i class="icon-camera icon-2x" style="margin:0 auto; padding:0px; color:#22aeff;"></i>
         	<div style="width:100%; line-height:5px;"></div>
         	<span lang="en">Embed</span>
        </a>
</div>
	 <i id="LoadCanvas1" class="icon-refresh  icon-spin icon-2x" style="color:#22aeff; position:relative; left:300px; top:160px; z-index: 999;"></i>
	 <i id="LoadCanvas2" class="icon-refresh  icon-spin icon-2x" style="color:#22aeff; position:relative; left:680px; top:160px; z-index: 999;"></i>
 		
 						<!-- 	<div id="status">Status here</div>  -->
 
			 <div style="width:100%;"></div>
         
            <!-- TELEMED NOTIFICATOR -->
                <div class="grid" class="grid span4" style="margin:0px auto; margin-bottom: 20px; margin-left: 30px; float: left; padding:20px; padding-top: 10px; height: 30px; display: none; background-color: #54bc00; width: 88%" id="telemed_notificator">
                    <div style="margin-top:10px;"><div style="width: 78%; height: 30px; margin-top: -6px; float: right;"><a id="telemed_deny_button" href="javascript:void(0)" class="btn" title="Acknowledge" style="width:2%; color: red; margin-bottom: 2px;margin-left: 2%; float: right; padding-left: -15px;"><i id="' + items[i].ID + '" class="icon-remove"></i></a><a id="telemed_connect_button" href="javascript:void(0)" class="btn" title="Acknowledge" style="width:2%; color: green; margin-bottom: 2px; float: right; padding-left: -15px;"><i id="' + items[i].ID + '" class="icon-facetime-video"></i></a><p id="video_consultation_text" style="color: #FFF; height: 10px; font-size: 14px; margin-top: 4px;" lang="en">Doctor Javier Vinals is calling you for a video consultation </p></div><input type="hidden" id="video_consultation_id" value="<?php echo $telemed; ?>" /><input type="hidden" id="video_consultation_name" value="<?php echo $telemed_name; ?>" /><span class="label label-info" id="EtiTML" style="background-color:#FFF; margin:20px; margin-left:0px; margin-bottom:0px; font-size:16px; text-shadow:none; text-decoration:none; color:#54bc00;" lang="en">Video Consultation</span></div>
                    
                </div>
            <!-- END TELEMED NOTIFICATOR -->
         
             <div style="float:left; height:290px; width:900px; margin: 30px auto; margin-top:-10px; margin-left:30px; margin-right:30px; padding:10px; border:1px solid #cacaca; font-size:0px; ">             
                <!-- Upper ROW  ************************************************** -->
                <div class="UpperRow" style="float:left; margin-top:-40px; width:855px; border:1px solid #cacaca; padding:10px; margin:10px; border-radius: 3px;">
             		<div style="margin-left:-10px; margin-top:-10px; margin-bottom:-10px; float:left; width:60px; height:260px; border-radius:0px;  border-top-left-radius: 2px; border-bottom-left-radius: 2px; background-color:#54bc00;">
             			<div style="width:190px;height:50px; position:relative; top:100px; left:-50px; -ms-transform:rotate(270deg); -moz-transform:rotate(270deg); -webkit-transform:rotate(270deg);-o-transform:rotate(270deg); color:white; font-size:20px;" lang="en">Health Passport</div>
	             	</div>
	             	
	             	<div style="float:left; width:; height:24px;  border:0px solid #cacaca; ">
		             	<div id="PHSLabelOrig" style="float:left; margin-left:23px; width:360px; height:22px;  border:0px solid #cacaca; text-align:center; font-size:18px; color: #54bc00;" lang="en">Personal Health Summary</div>
		             	<div id="MHRLabel" style="float:left; margin-left:20px; width:360px; height:22px;  border:0px solid #cacaca; text-align:center; font-size:18px; color: #54bc00;" lang="en">Medical History Reports</div>
	             	</div>	 	
            	   
	             	<div style="float:left; width:; height:;  border:0px solid #cacaca; ">
		             	<div style="float:left; width:; height:;  border:0px solid #cacaca; ">
			             	<canvas id="myCanvas" width="360" height="200" style="float:left; border:0px solid #cacaca; margin-left:20px; cursor: pointer; "></canvas>		
			             	<div id="ALTCanvas1" style="float:left; position:absolute; width:360px; height:200px;  font-size:10px; margin-left:20px; border:0px solid #cacaca; background-color:white; overflow:hidden; "></div>           
		             	</div>
		             	<canvas id="myCanvas2" width="360" height="200" style="float:left; border:0px solid #cacaca; margin-left:20px; cursor: pointer; "></canvas>
		            </div>	
					<button id="PHSLabel" class="find_doctor_button" style="float:left; display: block; background-color: rgb(82, 216, 89); width:80px; margin-top: 0px;height: 16px;font-size: 12px;line-height: 12px;" lang="en">See Graph</button>
					<button id="ButtonReview" class="find_doctor_button" style="float:left; margin-left:10px; display: block; background-color: rgb(34, 174, 255); width:80px; margin-top: 0px;height: 16px;font-size: 12px;line-height: 12px;" lang="en">Edit</button>
                    <!-- Edit for Patient Detail Med ****************************** -->
                    <button id="editPatient" class="find_doctor_button" style="float:left; display: block; background-color: rgb(34, 174, 255); width:80px; margin-top: 0px;margin-left:300px;height: 16px;font-size: 12px;line-height: 12px;" lang="en">Edit</button>

				</div> 
				
                <!-- Bottom ROW  ************************************************** -->
                <div class="doctor-table" style="float:left; margin-top:-40px; width:854px; height:120px; border:1px solid #cacaca; padding:10px; margin:10px; border-radius: 3px;">
             		<div style="margin-left:-10px; margin-top:-10px; margin-bottom:-10px; float:left; width:60px; height:140px; border-radius:0px;  border-top-left-radius: 2px; border-bottom-left-radius: 2px; background-color:#22aeff;"></div>    
             			<div style="float:left; width:180px; height:50px; position:relative; top:-12px; left:-110px; -ms-transform:rotate(270deg); -moz-transform:rotate(270deg); -webkit-transform:rotate(270deg);-o-transform:rotate(270deg); color:white; font-size:24px;" lang="en">Doctors</div>
          			    
			 		<style>
			 			a.ButtonDrAct{
				 			float:left; 
				 			text-align:left; 
				 			margin-top:0px; 
				 			margin-right:5px;
                            margin-left:10px;
				 			padding:0px; 
				 			width:90px; 
				 			height:80px; 
				 			color:#22aeff; 
				 			text-align:center;
			 			}
			 		</style>
			 		    
			 			<div style="float:left; margin-top:-50px; margin-left:60px; width:730px; height:120px;  ">
                        
	             			<a id='Talk_hti'  value="Talk_hti" class="btn ButtonDrAct"  title="Talk" > 
		                     	<img src="images/icons/Talk_svg.png" style=" margin-top:-2px; width:50px; height:50px;">
		                     	<div style="line-height:25px; margin-left:6px;"><span lang="en">Talk</span></div>
	                     	</a>

	             			<a id='SetUp'  value="SetUp" class="btn ButtonDrAct" title="SetUp"> 
		                     	<img src="images/icons/Configuration_svg.png" style="margin-top:-2px; width:50px; height:50px;">
		                     	<div style="line-height:25px; margin-left:6px;"><span lang="en">SetUp</span></div>
	                     	</a>
			 			</div>
			 			
 
				</div> 
                
             </div>     
             <div  style="display:none; float:left; border:1px solid #cacaca; width:900px; height:420px; margin: 30px auto; margin-top:30px; margin-left:30px; margin-right:30px; padding:10px; ">
                <span class="label label-info" id="EtiTML" style="left:0px; margin-left:0px; margin-top:20px; margin-bottom:20px; font-size:16px; text-shadow:none; text-decoration:none;" lang="en">User&Doctor Communications Area</span>
                <ul id="myTab" class="nav nav-tabs tabs-main">
                <li class="active"><a href="#inbox" data-toggle="tab" id="newinbox" lang="en">InBox</a></li>
                <li><a href="#outbox" data-toggle="tab" id="newoutbox" lang="en">OutBox</a></li></ul>
                <div id="myTabContent" class="tab-content tabs-main-content padding-null">
                               
                <div class="tab-pane tab-overflow-main fade in active" id="inbox">
				<div class="message-list"><div class="clearfix" style="margin-bottom:40px;">
                <div class="action-message"><div class="btn-group">
               
                <button id="delete_message" class="btn b2"><i class="icon-trash padding-null"></i></button>
				<input type="button" style="margin-left:10px" class="btn b2" value="Create Message" id="compose_message">
              
             	</div></div>
				</div>
                    <div style="height:270px; overflow:auto; ">
                            <table class="table table-striped table-mod" id="datatable_3"></table>
                    </div>
                </div></div>
				
				<div class="tab-pane" id="outbox">
				<div class="message-list"><div class="clearfix" style="margin-bottom:40px;">
                <div class="action-message"><div class="btn-group">
                
                <button id="delete_message_outbox" class="btn b2"><i class="icon-trash padding-null"></i></button>
				</div></div>
				</div>
                   <div style="height:270px; overflow:auto; ">
                        <table class="table table-striped table-mod" id="datatable_4"></table> 
                   </div> 
                </div>
                </div>
				
				</div>

             </div>     


           
             </div> 
         </div>
     </div>


				 		<img id="Admin" src="images/icons/Admin_svg.png" style="visibility:hidden;" >
					    <img id="History" src="images/icons/history_svg.png" style="visibility:hidden;" >
					    <img id="Medication" src="images/icons/medication_svg.png" style="visibility:hidden;" >
					    <img id="Immuno" src="images/icons/immuno_svg.png" style="visibility:hidden;" >
					    <img id="Family" src="images/icons/family_svg.png" style="visibility:hidden;" >
					    <img id="Habits" src="images/icons/habits_svg.png" style="visibility:hidden;" >
					
					    <img id="R1" src="images/icons/picture_svg.png"  style="visibility:hidden;">
					    <img id="R2" src="images/icons/beaker_svg.png" style="visibility:hidden;">
					    <img id="R3" src="images/icons/user-md_svg.png"  style="visibility:hidden;">
					    <img id="R4" src="images/icons/inbox_svg.png"  style="visibility:hidden;">
					    <img id="R5" src="images/icons/circle-blank_svg.png" style="visibility:hidden;">
					    <img id="R6" src="images/icons/list-alt_svg.png"  style="visibility:hidden;">
					    <img id="R7" src="images/icons/film_svg.png"  style="visibility:hidden;">
					    <img id="R8" src="images/icons/user_svg.png" style="visibility:hidden;">
					    <img id="R9" src="images/icons/dollar_svg.png"  style="visibility:hidden;">
					    <img id="R10" src="images/icons/question-sign_svg.png"  style="visibility:hidden;">
					    <img id="R11" src="images/icons/list-alt_svg.png" style="visibility:hidden;">
					    <img id="R12" src="images/icons/beaker_svg.png"  style="visibility:hidden;">


     <!--CONTENT MAIN END-->

    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="js/jquery.min.js"></script>
    <script src="js/jquery-ui.min.js"></script>
    <script src="js/jquery.easing.1.3.js"></script>
    <script src="build/js/intlTelInput.js"></script>
    <script src="js/isValidNumber.js"></script>	
	<script src="js/moment-with-locales.js"></script>

    <!-- Libraries for notifications -->
    <script src="realtime-notifications/lib/gritter/js/jquery.gritter.min.js"></script>
	<script src="realtime-notifications/pusher.min.js"></script>
	<script src="realtime-notifications/PusherNotifier.js"></script>
	<link href="realtime-notifications/lib/gritter/css/jquery.gritter.css"rel="stylesheet" type="text/css" />
	<!--<script src="imageLens/jquery.js" type="text/javascript"></script>-->
	<script src="imageLens/jquery.imageLens.js" type="text/javascript"></script>
    <script>
		$(function() {
	    var pusher = new Pusher('d869a07d8f17a76448ed');
	    var channel_name=$('#MEDID').val();
		var channel = pusher.subscribe(channel_name);
		var notifier=new PusherNotifier(channel);
		
	  });
    </script>
    <!-- Libraries for notifications -->

    <script type="text/javascript" src="https://js.stripe.com/v2/"></script>
    <script src="TypeWatch/jquery.typewatch.js"></script>
    <script src="js/userdashboard.js"></script>
    <!--<script src="js/UserdashboardHTI.js"></script>-->

    
    
    <script src="js/bootstrap.min.js"></script>
    <script src="js/bootstrap-datepicker.js"></script>
    <script src="js/bootstrap-colorpicker.js"></script>
    <script src="js/google-code-prettify/prettify.js"></script>
   
    <script src="js/jquery.flot.min.js"></script>
    <script src="js/jquery.flot.pie.js"></script>
    <script src="js/jquery.flot.orderBars.js"></script>
    <script src="js/jquery.flot.resize.js"></script>
    <script src="js/graphtable.js"></script>
    <script src="js/fullcalendar.min.js"></script>
    <script src="js/chosen.jquery.min.js"></script>
    <script src="js/autoresize.jquery.min.js"></script>
    <script src="js/jquery.tagsinput.min.js"></script>
    <script src="js/jquery.autotab.js"></script>
    <script src="js/elfinder/js/elfinder.min.js" charset="utf-8"></script>
	<script src="js/tiny_mce/tiny_mce.js"></script>
    <script src="js/validation/languages/jquery.validationEngine-en.js" charset="utf-8"></script>
	<script src="js/validation/jquery.validationEngine.js" charset="utf-8"></script>
    <script src="js/jquery.jgrowl_minimized.js"></script>
    <script src="js/jquery.dataTables.min.js"></script>
    <script src="js/jquery.mousewheel.js"></script>
    <script src="js/jquery.jscrollpane.min.js"></script>
    <script src="js/jquery.stepy.min.js"></script>
    <script src="js/jquery.validate.min.js"></script>
    <script src="js/raphael.2.1.0.min.js"></script>
    <script src="js/justgage.1.0.1.min.js"></script>
	<script src="js/glisse.js"></script>

	<script src="js/application.js"></script>
	
	<script language="JavaScript" src="http://www.geoplugin.net/javascript.gp" type="text/javascript"></script>

<?php

function queFuente ($numero)
{
$queF=10;
switch ($numero)
{
	case ($numero>999 && $numero<9999):	$queF = 30;
										break;
	case ($numero>99 && $numero<1000):	$queF = 50;
										break;
	case ($numero>0 && $numero<100):	$queF = 70;
										break;
}

return ($queF);

}

function queFuente2 ($numero1, $numero2)
{
$queF=10;
$numero= digitos($numero1)+digitos($numero2);
switch ($numero)
{
	case 2:	$queF = 60;
			break;
	case 3:	$queF = 55;
			break;
	case 4:	$queF = 50;
			break;
	case 5:	$queF = 45;
			break;
	case 6:	$queF = 40;
			break;
	case 7:	$queF = 35;
			break;
	case 8:	$queF = 30;
			break;
}

return ($queF);

}

function digitos ($numero)
{
$queF=0;

switch ($numero)
{
	case ($numero>999 && $numero<9999):	$queF = 4;
										break;
	case ($numero>99 && $numero<1000):	$queF = 3;
										break;
	case ($numero>0 && $numero<100):	$queF = 2;
										break;
}

return ($queF);

}
?>

  </body>
</html>
