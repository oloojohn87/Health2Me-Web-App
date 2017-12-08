<?php

session_start();
 require_once("environment_detail.php");
 require_once("displayExitClass.php");
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];

$NombreEnt = $_SESSION['Nombre'];
$PasswordEnt = $_SESSION['Password'];
$MEDID = $_SESSION['BOTHID'];
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

$result = $con->prepare("SELECT *,floor(datediff(curdate(),DOB) / 365) as age FROM usuarios U INNER JOIN basicemrdata B where U.Identif=? AND U.Identif = B.IdPatient");
$result->bindValue(1, $UserID, PDO::PARAM_INT);
$result->execute();


$count = $result->rowCount();
$row = $result->fetch(PDO::FETCH_ASSOC);
$success ='NO';
if($count==1){
	$success ='SI';
    $age = $row['age'];
    $UserID = $row['Identif'];
	$UserEmail= $row['email'];
    $UserName = $row['Name'];
    $UserSurname = $row['Surname'];
    $UserPhone = $row['telefono'];
    //$UserLogo = $row['ImageLogo'];
    $IdUsFIXED = $row['IdUsFIXED'];
    $IdUsFIXEDNAME = $row['IdUsFIXEDNAME'];
    $GrantAccess = $row['GrantAccess'];
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
    $doctor_language = $rowD['language'];
    
    $plan = $row['plan'];
    $access = '';
    $original_access = '';
    if(isset($_SESSION['Original_User']))
    {
        $original_user = $_SESSION['Original_User'];
    }
    if(isset($_SESSION['Original_User_Access']))
    {
        $original_access = $_SESSION['Original_User_Access'];
    }
    if(isset($row['subsType']) && $row['subsType'] != null)
    {
        $access = $row['subsType'];
    }
    if($plan == 'FAMILY' && isset($row['ownerAcc']))
    {
        $user_accts = array();
        $resultD = $con->prepare("SELECT Name,Surname,Identif,email,relationship,subsType,grant_access,floor(datediff(curdate(),DOB) / 365) as age FROM usuarios U INNER JOIN basicemrdata B where U.ownerAcc = ? AND U.Identif != ? AND U.Identif = B.IdPatient");
        $resultD->bindValue(1, $row['ownerAcc'], PDO::PARAM_INT);
        $resultD->bindValue(2, $row['Identif'], PDO::PARAM_INT);
        $resultD->execute();
        while($rowAcct = $resultD->fetch(PDO::FETCH_ASSOC))
        {
            $inf = array("Name" => $rowAcct['Name'].' '.$rowAcct['Surname'], "ID" => $rowAcct['Identif'], "email" => $rowAcct['email'], "age" => $rowAcct['age'], "Relationship" => $rowAcct['relationship'], "access" => $rowAcct['subsType'], "grant_access" => $rowAcct['grant_access']);
            array_push($user_accts, $inf);
        }
    }
    
}
else
{
$exit_display = new displayExitClass();

$exit_display->displayFunction(2);
die;
}

$result = $con->prepare("SELECT * FROM lifepin");
$result->execute();

//QR Code
include "lib/classes/infographics.php";  

$basefilename = $UserID.'.png';
$qrdata = $domain."/emergency.php?id=".$UserID;
     
$qrcode = new H2M_Qrcode();
$qrcodeResults = $qrcode->create($basefilename, $qrdata);

//Language includes and switching functionality...
        
$lang_user = 0;
$lang_type = 1;
$lang_initial = 'en';
if($_SESSION['BOTHID'] == $_SESSION['MEDID'])
{
    $lang_user = $_SESSION['MEDID'];
    $lang_type = 1;
    $lang_initial = $doctor_language;
}
else
{
    $lang_user = $_SESSION['UserID'];
    $lang_type = 2;
    $lang_initial = $member_language;
}

echo "<script type='text/javascript'>
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
                            
                            location.reload();
                        });
                    }, 2000);
                });
        
        }    
        </script>";


?>
<!DOCTYPE html>
<html lang="en" style="background: #F9F9F9;"><head>
    <meta charset="utf-8">
    <title>Inmers - Center Management Console</title>
    <link rel="icon" type="image/ico" href="favicon.ico"/>
    <link rel='stylesheet' type='text/css' href='css/sweet-alert.css'>
	
	<!-- Create language switcher instance and set default language to en-->
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js" type="text/javascript"></script>
<script src="jquery-lang-js-master/js/jquery-cookie.js" charset="utf-8" type="text/javascript"></script>
<script src="jquery-lang-js-master/js/jquery-lang.js" charset="utf-8" type="text/javascript"></script>
<script src='js/sweet-alert.min.js'></script>
<script type="text/javascript">
	var lang = new Lang('en');
	window.lang.dynamic('th', 'jquery-lang-js-master/js/langpack/th.json');


function delete_cookie( name ) {
  document.cookie = name + '=; expires=Thu, 01 Jan 1970 00:00:01 GMT;';
}

/*function setCookie(name,value,days) {
    delete_cookie('lang');
    if (days) {
        var date = new Date();
        date.setTime(date.getTime()+(days*24*60*60*1000));
        var expires = "; expires="+date.toGMTString();
    }
    else var expires = "";
    document.cookie = name+"="+value+expires+"; path=";
	$.when( swal({
        title: 'The Language Setting Has Been Changed',
        type: 'success',
        timer: 2000
    }) ).then(function() {
        setTimeout(function() { location.reload(); }, 2000);
    });
}*/

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
    
    
    
$(document).ready(function() {
    console.log('INITIAL LANGUAGE: ' + initial_language);
            if(initial_language != 'en')
            {
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
                    });
                });
            }
            
    //Increment the idle time counter every minute.
    //var idleInterval = setInterval(timerIncrement, 60000); // 1 minute

    //Zero the idle timer on every possible movement.
    $('body').bind('mousemove', function() {
        idleTime = 0;
    });
});
</script>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
    <link href="css/style.css" rel="stylesheet">
    <link href="css/styleCol.css" rel="stylesheet">
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/bootstrap-dropdowns.css" rel="stylesheet">
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
    <script type= "text/javascript" src = "js/countries.js"></script>
	<link rel="stylesheet" href="css/icon/font-awesome.css">
 <!--   <link href="//netdna.bootstrapcdn.com/font-awesome/4.0.0/css/font-awesome.css" rel="stylesheet"> -->
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
        #overlay {
          background-color: none;
          position: auto;
          top: 0; right: 0; bottom: 0; left: 0;
          opacity: 1.0; /* also -moz-opacity, etc. */

        }
        #messagecontent {
          white-space: pre-wrap;   
        }
		#progressbar .ui-progressbar-value {
		background-color: #ccc;
		}
        .addit_button{
            background: transparent;
            color: #048F90;
            text-shadow: none;
            border: 1px solid #048F90;
            font-size: 12px !important;
            height: 20px;
            line-height: 12px;      
        }
        .addit_caret{
           border-top: 4px solid #048F90;
           margin-top: 3px !important;
           margin-left: 5px !important;
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
      
    <!--<div id="notification_bar" style="position: fixed;text-align:center;width: 100%; height: 44px; color: white; z-index: 2; background-color: rgb(119, 190, 247);"><div id="notification_bar_msg" style="display:inline-block;margin-right:20px;height:40px;vertical-align: middle;">We use cookies to improve your experience. By your continued use of this site you accept such use. </div>
       <div id="notification_bar_close" style="display:inline-block;margin-top: 2px;margin-left:40px">
           <i class="icon-remove-circle icon-3x"></i>
   </div></div>-->
      <div id="notification_bar"></div>
     <!-- MODAL VIEW FOR SUMMARY -->
    <div id="summary_modal" lang="en" title="Summary" style="display:none; text-align:center; width: 1000px; height: 660px; overflow: hidden;">
    </div>  
      
    <!-- MODAL VIEW FOR SETUP -->
    <div id="setup_modal" title="SetUp" style="display:none; text-align:center; padding:20px; width: 600px; height: 380px;">
        <div id="setup_modal_container" style="width: 600px; height: 350px;">
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
                <button id="setup_menu_6" style="display:none;background-color: #FBFBFB; color: #22AEFF; width: 50px; height: 50px; font-size: 24px; border: 1px solid #E6E6E6; outline: 0px; border-bottom-left-radius: 10px; border-bottom-right-radius: 10px; float: left;">
                    <i class="icon-key"></i>
                </button>
            </div>
            <div style="width: 85%; height: 200px; float: left;">
                <div id="setup_page_1">
                    <div style="width: 100%; height: 40px;">
                        <div style="float: left; margin-top: 6px; margin-bottom: -6px; color: #777; width: 200px; text-align: left;" lang="en">Type current password: </div>
                        <input id="pw1" type="password" style="float: left; height: 30px; width: 200px; padding: 7px; color: #444;" value="" />
                        <button id="change_password_validate_button" style="width: 75px; height: 30px; background-color: #52D859; color: #FFF; border-radius: 7px; border: 0px solid #FFF; float: left; margin-left: 10px; outline: 0px;">Validate</button>
                    </div>
                    <div id="change_password_validated_section" style="display: none;">
                        <div style="width: 100%; height: 40px;">
                            <div style="float: left; margin-top: 6px; margin-bottom: -6px; color: #777; width: 200px; text-align: left;" lang="en">Type new password: </div>
                            <input id="pw2" type="password" style="float: left; height: 30px; width: 200px; padding: 7px; color: #444;" value="" />
                        </div>
                        <div style="width: 100%; height: 40px;">
                            <div style="float: left; margin-top: 6px; margin-bottom: -6px; color: #777; width: 200px; text-align: left;" lang="en">Retype new password: </div>
                            <input id="pw3" type="password" style="float: left; height: 30px; width: 200px; padding: 7px; color: #444;" value="" />
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
                        populateCountries("country_setup", "state_setup");
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
                <div id="setup_page_6" style="display: none;">
                    <div style="width: 300px; text-align: center; margin: auto; margin-top: 5px; margin-bottom: 10px;">
                        <label lang="en">Your current subscription is: <span style="color: #22AEFF;"><?php echo $plan; ?></span></label>
                    </div>
                    <?php
                        if($plan == 'FREE')
                        {
                            echo '<button id="upgrade_premium_button" style="width: 60%; height: 30px; border-radius: 5px; border: 0px solid #FFF; outline: 0px; color: #FFF; background-color: #54bc00; margin: auto; margin-top: 20px;" lang="en">Change To Premium ($8.00 / Month)</button>';
                        }
                        if($plan == 'FREE' || $plan == 'PREMIUM')
                        {
                            echo '<button id="upgrade_family_button" style="width: 60%; height: 30px; border-radius: 5px; border: 0px solid #FFF; outline: 0px; color: #FFF; background-color: #54bc00; margin: auto; margin-top: 20px;" lang="en">Change To Family ($14.00 / Month)</button><div style="width: 42px; height: 48px; padding-top: 70px; margin: auto; visibility: hidden;" id="upgrade_loading_bar"><img src="images/load/29.gif" style="margin-bottom: 7px;"  alt=""></div>';
                        }
                        if($plan == 'FAMILY' && ($access == 'Owner' || $access == 'Admin'))
                        {
                    ?>
                    
                    <!-- Family Account management will only be loaded if the current account's subscription is 'FAMILY' -->
                    <div id="family_members" style="width: 500px; height: 200px; background-color: #FFF;">
                        <style>
                            .family_member_row{
                                width: 100%;
                                height: 26px;
                                margin-bottom: 8px;
                            }
                        </style>
                        <div style="width: 100%; height: 150px; overflow: scroll;" id="family_users">
                        <?php
                            for($i = 0; $i < count($user_accts); $i++)
                            {
                                if($user_accts[$i]['access'] != 'Owner')
                                {
                                    echo '<div id="family_member_row_'.$user_accts[$i]['ID'].'" class="family_member_row">';
                                    echo '<div style="width: 20%; height: 26px; float: left;">'.$user_accts[$i]['Name'].'</div>';
                                    echo '<div style="width: 20%; height: 26px; float: left; color: #22AEFF;">'.ucwords($user_accts[$i]['Relationship']).'</div>';
                                    echo '<div style="width: 20%; height: 26px; float: left; color: #54bc00; ';
                                    if($user_accts[$i]['access'] == 'Owner' || $user_accts[$i]['access'] == 'Admin')
                                    {
                                        echo 'font-weight: bold;';
                                    }
                                    echo '">'.$user_accts[$i]['access'].'</div>';
                                    echo '<button id="family_member_edit_'.$user_accts[$i]['ID'].'" style="width: 15%; margin-left: 5%; height: 26px; float: left; color: #FFF; background-color: #54bc00; border-radius: 5px; outline: none; border: 0px solid #FFF;">Edit</button>';
                                    echo '<button id="family_member_delete_'.$user_accts[$i]['ID'].'" style="width: 15%; margin-left: 5%; height: 26px; float: left; color: #FFF; background-color: #D84840; border-radius: 5px; outline: none; border: 0px solid #FFF;">Delete</button>';
                                    echo '</div>';
                                }
                            }
                            
                        ?>
                            
                        </div>
                        <button id="add_family_member_button" style="width: 45%; height: 30px; border-radius: 5px; border: 0px solid #FFF; outline: 0px; color: #FFF; background-color: #54bc00; margin-top: 20px;" lang="en">
                            Add Family Member
                        </button>
                        <?php
                            if($age >= 18)
                            {
                                $result = $con->prepare("SELECT grant_access FROM usuarios WHERE Identif=?");
                                $result->bindValue(1, $UserID, PDO::PARAM_INT);
                                $result->execute();
                                $row = $result->fetch(PDO::FETCH_ASSOC);
                                $grant_access = $row['grant_access'];
                        ?>
                            <button id="grant_admin_access_button" style="width: 65%; height: 30px; border-radius: 5px; border: 0px solid #FFF; outline: 0px; color: #FFF; background-color: <?php if($grant_access == '0'){echo '#54bc00;';}else{echo '#D84840;';} ?> margin-top: 20px;" lang="en">
                                <?php 
                                    if($grant_access == 0)
                                    {
                                        echo 'Grant Admin Access To My Account';
                                    }
                                    else
                                    {
                                        echo 'Remove Admin Access To My Account';
                                    }
                                ?>
                                
                            </button>
                        
                        <?php
                            }
                        ?>
                    
                    </div>
                    <div id="edit_family_member" style="width: 500px; height: 200px; background-color: #FFF; display: none;">
                        <style>
                            #edit_family_member input{
                                border-radius: 0px;
                                height: 18px;
                                width: 200px;
                                margin: 0px;
                                box-shadow: 0px 0px 0px #FFF;
                                outline: none;
                            }
                            #edit_family_member select{
                                border-radius: 0px;
                                height: 26px;
                                width: 212px;
                                margin: 0px;
                                margin-top: 15px;
                                border: 1px solid #CCC;
                                box-shadow: 0px 0px 0px #FFF;
                                outline: none;
                            }
                        </style>
                        <div style="width: 100%; height: 150px;">
                            <div style="width: 50%; height: 150px; float: left;">
                                <input type="text" placeholder="Name" id="family_member_name" style="border-top-left-radius: 5px; border-top-right-radius: 5px;" />
                                <input type="text" placeholder="Surname" id="family_member_surname" />
                                <div style="width: 40px; height: 22px; border: 1px solid #CCC; border-right: 0px solid #FFF; color: #999; float: left; margin-left: 18px; padding-left: 5px; padding-top: 4px;">DOB: </div>
                                
                                <input type="date" id="family_member_dob" style="width: 155px; float: left; border-left: 0px solid #FFF;" />
                                <input type="text" placeholder="Phone number" id="family_member_phone" class="intermediate-input validate[required, funcCall[checkPhoneFormat]] span" style="border-bottom-left-radius: 5px; border-bottom-right-radius: 5px;" />
                                
                                
                                <select id="family_member_gender" style="margin-top: 8px;">
                                    <option value="none">Gender</option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                </select>
                            </div>
                            <div style="width: 50%; height: 150px; float: left;">
                                <input type="password" placeholder="Password" id="family_member_password" style="border-top-left-radius: 5px; border-top-right-radius: 5px;" />
                                <input type="password" placeholder="Retype Password" id="family_member_password2" />
                                <input type="text" placeholder="Email" id="family_member_email" />
                                
                                <input type="text" placeholder="Birth order (if twin)" id="family_member_order" style="border-bottom-left-radius: 5px; border-bottom-right-radius: 5px;" />
                                
                                
                                <select id="family_member_relationship">
                                    <option value="none">Relationship to owner</option>
                                    <option value="Father">Father</option>
                                    <option value="Mother">Mother</option>
                                    <option value="Husband">Husband</option>
                                    <option value="Wife">Wife</option>
                                    <option value="Son">Son</option>
                                    <option value="Daughter">Daughter</option>
                                </select>
                            </div>
                        </div>
                        <div style="width: 100%; height: 10px; margin-top: 10px;">
                            <span style="font-size: 10px; text-align: left; "><em>* Email and phone only required if user is over 18</em></span>
                        </div>
                        <button id="edit_family_member_cancel_button" style="width: 15%; height: 30px; border-radius: 5px; border: 0px solid #FFF; outline: 0px; color: #FFF; background-color: #D84840; margin-top: 20px; float: right; margin-right: 20px;" lang="en">
                            Cancel
                        </button>
                        <button id="edit_family_member_done_button" style="width: 15%; height: 30px; border-radius: 5px; border: 0px solid #FFF; outline: 0px; color: #FFF; background-color: #54bc00; margin-top: 20px; margin-right: 10px; float: right;" lang="en">
                            Done
                        </button>
                        
                        <button id="give_admin_privileges" style="height: 16px; width: 16px; background-color: #F8F8F8; border: 1px solid #CCC; padding: 0px; float: left; outline: 0px; border-radius: 16px; margin-top: 22px; margin-left: 20px;">
                            <input type="hidden" value="0" />
                            <div style="width: 10px; height: 10px; margin-left: 2px; border-radius: 10px; background-color: #54bc00;" ></div>
                        </button>
                        <span style="float: left; text-align: center; margin-left: 10px; margin-top: 20px;">Give Admin Privileges</span>
                    
                    </div>
                    
                    <?php
                            }
                            else if($plan == 'FAMILY' && $access == 'Delegate' && $age >= 18)
                            {
                                $result = $con->prepare("SELECT grant_access FROM usuarios WHERE Identif=?");
                                $result->bindValue(1, $UserID, PDO::PARAM_INT);
                                $result->execute();
                                $row = $result->fetch(PDO::FETCH_ASSOC);
                                $grant_access = $row['grant_access'];
                        ?>
                            <button id="grant_admin_access_button" style="width: 65%; height: 30px; border-radius: 5px; border: 0px solid #FFF; outline: 0px; color: #FFF; background-color: <?php if($grant_access == '0'){echo '#54bc00;';}else{echo '#D84840;';} ?> margin-top: 20px;" lang="en">
                                <?php 
                                    if($grant_access == 0)
                                    {
                                        echo 'Grant Admin Access To My Account';
                                    }
                                    else
                                    {
                                        echo 'Remove Admin Access To My Account';
                                    }
                                ?>
                                
                            </button>
                        
                        <?php
                            }
                        ?>
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
                                echo '<button id="recdoc_'.$ids[$i].'_'.$doc_row['phone'].'_'.$doc_row['Name'].'_'.$doc_row['Surname'].'_'.$doc_row['location'];
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
                    <!--<div style="width: 90%; margin-left: 10%; height: 50px; margin-top: 7px;">
                        <p style="text-align: left; float: left;" lang="en">Video or phone consultation?</p>
                        
                        <div style="width: 100px; height: 30px; border-radius: 3px; background-color: #535353; float: left; margin-left:105px; margin-top: -6px;">
                            <button style="width: 50px; height: 30px; border-top-left-radius: 3px; border-bottom-left-radius: 3px; background-color: #22aeff; border: 0px solid #FFF; color: #FFF; float: left; outline: 0px;" id="find_doctor_video_button_2">
                                <i class="icon-facetime-video"></i>
                            </button>
                            <button style="width: 50px; height: 30px; border-top-right-radius: 3px; border-bottom-right-radius: 3px; background-color:  #535353; border: 0px solid #FFF; color: #FFF; float: left; outline: 0px;" id="find_doctor_phone_button_2">
                                <i class="icon-phone"></i>
                            </button>
                        </div>
                    </div>-->
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
                    <?php 
                        if ($GrantAccess != 'HTI-COL') {
                    ?>  
                    <div style="width: 90%; margin-left: 10%; height: 50px; margin-top: 7px;">
                        <p style="text-align: left; float: left;" lang="en">Video or phone consultation?</p>
                        
                        <div style="width: 100px; height: 30px; border-radius: 3px; background-color: #535353; float: left; margin-left:80px; margin-top: -6px;">
                            <button style="width: 50px; height: 30px; border-top-left-radius: 3px; border-bottom-left-radius: 3px; background-color: #22aeff; border: 0px solid #FFF; color: #FFF; float: left; outline: 0px;" id="find_doctor_video_button">
                                <i class="icon-facetime-video"></i>
                            </button>
                            <button style="width: 50px; height: 30px; border-top-right-radius: 3px; border-bottom-right-radius: 3px; background-color:  #535353; border: 0px solid #FFF; color: #FFF; float: left; outline: 0px;" id="find_doctor_phone_button">
                                <i class="icon-phone"></i>
                            </button>
                        </div>
                    </div>
                    <?php 
                        } 
                    ?>
                   
                    
                    <script type= "text/javascript" src = "js/countries.js"></script>
                    <script language="javascript">
                        populateCountries("country", "state");
                    </script>
                </div>
                <div style="width: 100%; height: 235px; padding-top: 15px; display: none;" id="find_doctor_appointment_2">
                    <button id="find_doctor_general_practicioner" class="square_blue_button" style="float: right; margin-top: 15px;">
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
                <button id="find_doctor_next_button" class="find_doctor_button" style="background-color: #52D859;" lang="en">Next</button>
                <button id="find_doctor_previous_button" class="find_doctor_button" style="background-color: #22aeff;" lang="en">Previous</button>
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
			<?php
			if($GrantAccess == 'HTI-RIVA'){
				echo "<center><img style='margin-top:-350px;' src='images/RivaCare_Logo.png' /></center>";
			}elseif($GrantAccess == 'HTI-24X7'){
				echo "<center><img style='margin-top:-350px;' src='http://24x7hellodoctor.com/img/logo-24x7-hellodoctor.jpg' /></center>";
			}else{
				echo "<center><img style='margin-top:-350px;' src='images/llamaaldoctor_trans.png' /></center>";
			}
		?>
           
            <p lang="en" style='margin-top:-100px;'>We are connecting you with the call center.  Please wait...</p>
           
            
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
  		  
           <?php
			if($GrantAccess == 'HTI-RIVA'){
				echo "<a href='UserDashboardHTI.php' style='background-image:url(images/RivaCare_Logo.png);display:block;width:325px;height:42px;float:left;'></a>";
			}elseif($GrantAccess == 'HTI-24X7'){
				echo "<a href='http://24x7hellodoctor.com/' style='background-image:url(http://24x7hellodoctor.com/img/logo-24x7-hellodoctor.jpg); background-size: 250px 42px;background-repeat: no-repeat;display:block;width:255px;height:42px;float:left;'></a>";
			}else{
				echo '<a href="index-col.html" class="logo"><h1>Health2me</h1></a>';
			}
		  ?>
          <!-- Start of commenting by Pallab for language changes-->
          <!--  <div style="float:left;">
		   <a href="#en" onclick="setCookie('lang', 'en', 30); return false;"><img src="images/icons/english.png"></a>
		   </br>
			<a href="#sp" onclick="setCookie('lang', 'th', 30); return false;"><img src="images/icons/spain.png"></a>
			</div> -->
          <!-- End of commenting by Pallab--> 
               
               <!-- Start of new code by Pallab -->
               
               <div style="margin-top:11px;float:left;margin-left:10px;" class="btn-group">
                      <button id="lang1" type="button" class="btn btn-default dropdown-toggle addit_button" data-toggle="dropdown">
                        Language <span class="caret addit_caret"></span>
                      </button>
                      <ul class="dropdown-menu" role="menu">
                        <li><a href="#en" onclick="setCookie('lang', 'en', 30); return false;">English</a></li>
                        <li><a href="#sp" onclick="setCookie('lang', 'th', 30); return false;">Espa&ntilde;ol</a></li>
                      </ul>
                </div>
               
              <!-- End of new code by Pallab-->
           
               
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
               <?php
                // If this is a family subscription, load all of the users in the drop down menu
                if($plan == 'FAMILY' && count($user_accts) > 0 && ($original_access == 'Owner' || $original_access == 'Admin'))
                {
                    $count = count($user_accts);
                    echo '<div id="family_members_dropdown">';
                    for($i = 0; $i < $count; $i++)
                    {
                        echo '<li><a href="#" class="change_user_dropdown_button" id="user_'.$user_accts[$i]['ID'].'_'.$user_accts[$i]['email'].'_'.$user_accts[$i]['age'].'_'.$user_accts[$i]['grant_access'].'_dropdown" lang="en">';
                        echo '<i class="icon-user"></i> '.$user_accts[$i]['Name'].'</a></li>';
                    }
                    echo '</div>';
                }
            ?>
              
              <!--<li><a href="medicalConfiguration.php" lang="en"><i class="icon-cog"></i> Settings</a></li>-->
              <?php
		if($GrantAccess == 'HTI-COL'){
			echo '<li><a href="logout.php?access=hti-col" lang="en"><i class="icon-off"></i> Sign Out</a></li>';
		}elseif($GrantAccess == 'HTI-RIVA'){
			echo '<li><a href="logout.php?access=hti-riva" lang="en"><i class="icon-off"></i> Sign Out</a></li>';
		}elseif($GrantAccess == 'HTI-24X7'){
			echo '<li><a href="logout.php?access=hti-24x7" lang="en"><i class="icon-off"></i> Sign Out</a></li>';
		}else{
			echo '<li><a href="logout.php" lang="en"><i class="icon-off"></i> Sign Out</a></li>';
		}
?>
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
<!-- End of code inserted from userdashboard-new-pallab.php -->
 
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
		
     <?php if ($privilege==1) echo '<li><a href="UserDashboard.php" class="act_link" lang="en">Home</a></li>';
        echo '<li><a href="patientdetailMED-new.php?IdUsu='.$UserID.'" lang="en">Medical Records</a></li>';

        if($GrantAccess == 'HTI-COL'){
            echo '<li><a href="logout.php?access=hti-col" style="color:#d9d907;" lang="en">Sign Out</a></li>';
        }elseif($GrantAccess == 'HTI-RIVA'){
            echo '<li><a href="logout.php?access=hti-riva" style="color:#d9d907;" lang="en">Sign Out</a></li>';
        }elseif($GrantAccess == 'HTI-24X7'){
            echo '<li><a href="logout.php?access=hti-24x7" style="color:#d9d907;" lang="en"></i> Sign Out</a></li>';
        }else{
            echo '<li><a href="logout.php" style="color:#d9d907;" lang="en">Sign Out</a></li>';
        }	
     ?>
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
                     
                             <img src="<?php echo $qrcodeResults; ?>" style="float:left; margin-right:40px; margin-left:10px; font-size:18px; padding:0px 0px 0px 10px; font-family: “Andale Mono”, AndaleMono, monospace;width:110px;height:110px"/>
                           
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
                                     <a id='Talk'  value="Talk" class="btn ButtonDrAct"  title="Talk to a doctor by phone or by video chat" > 
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
                            <?php 
                                if ($GrantAccess != 'HTI-COL') {
                            ?>    
                            
	             			<a id='SearchDirectory'  value="SearchDirectory" class="btn ButtonDrAct" > 
		                     	<img src="images/icons/SearchDirectory_svg.png" style=" margin-top:-2px; width:50px; height:50px;">
		                     	<div style="line-height:25px; margin-left:6px;"><span lang="en">Search</span></div>
	                     	</a>
                            <?php } ?>
	             			<a id='Talk'  value="Talk" class="btn ButtonDrAct"  title="Talk" > 
		                     	<img src="images/icons/Talk_svg.png" style=" margin-top:-2px; width:50px; height:50px;">
		                     	<div style="line-height:25px; margin-left:6px;"><span lang="en">Talk</span></div>
	                     	</a>
                             <?php 
                                if ($GrantAccess != 'HTI-COL') {
                            ?>  
	             			<a id='Request'  value="Request" class="btn ButtonDrAct"  title="Request"> 
		                     	<img src="images/icons/Request_svg.png" style=" margin-top:-2px; width:50px; height:50px;">
		                     	<div style="line-height:25px; margin-left:6px;"><span lang="en">Request</span></div>
	                     	</a>
	             			<a id='Send2Doc'  value="Send2Doc" class="btn ButtonDrAct" title="Send2Doc"> 
		                     	<img src="images/icons/Send_svg.png" style=" margin-top:-2px; width:50px; height:50px;">
		                     	<div style=" line-height:25px; margin-left:6px;"><span lang="en">Send</span></div>
	                     	</a>
	             			<a id='MessageD'  value="MessageD" class="btn ButtonDrAct" title="MessageD" > 
		                     	<img src="images/icons/Message_svg.png" style=" margin-top:-2px; width:50px; height:50px;">
		                     	<div style=" line-height:25px; margin-left:6px;"><span lang="en">Message</span></div>
	                     	</a>
                            <?php } ?>
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
    <script src="js/jquery.cookie.js"></script>

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



    <script src="TypeWatch/jquery.typewatch.js"></script>
    <script type="text/javascript" >
    
	var timeoutTime = 18000000;
	//var timeoutTime = 300000;  //5minutes
	var timeoutTimer = setTimeout(ShowTimeOutWarning, timeoutTime);


	var active_session_timer = 60000; //1minute
	var sessionTimer = setTimeout(inform_about_session, active_session_timer);

    var reportcheck = new Array();
   
	//This function is called at regular intervals and it updates ongoing_sessions lastseen time
	function inform_about_session()
	{
		/*$.ajax({
			url: '<?php echo $domain?>/ongoing_sessions.php?userid='+<?php echo $MEDID ?>,
			success: function(data){
			//alert('done');
			}
		});
		clearTimeout(sessionTimer);
		sessionTimer = setTimeout(inform_about_session, active_session_timer);*/
	}

	function ShowTimeOutWarning()
	{
		alert ('Session expired');
		var a=0;
		window.location = 'timeout.php';
	}
        
	// function launchTelemedicine()
    // {
		// $.ajax({
		// url: '<?php echo $domain?>/weemo_test.php?calleeID='+<?php echo $DoctorEmail?>,
			// success: function(data){
			//alert('done');
			// }
		// });
    // }	
    function LanzaAjax (DirURL)
		{
		var RecTipo = 'SIN MODIFICACIÓN';
	    $.ajax(
           {
           url: DirURL,
           dataType: "html",
           async: false,
           complete: function(){ //alert('Completed');
                    },
           success: function(data) {
                    if (typeof data == "string") {
                                RecTipo = data;
                                }
                     }
            });
		return RecTipo;
		} 	
	
	setInterval(function() {
    		$('#newinbox').trigger('click');
      }, 10000);

    function displaynotification(status,message){
  
  var gritterOptions = {
			   title: status,
			   text: message,
			   image:'images/Icono_H2M.png',
			   sticky: false,
			   time: '3000'
			  };
	$.gritter.add(gritterOptions);
	
  }

    function getUserData(UserId) {
 	var cadenaGUD = '<?php echo $domain;?>/GetUserData.php?UserId='+UserId;
    $.ajax(
           {
           url: cadenaGUD,
           dataType: "json",
           async: false,
           success: function(data)
           {
           //alert ('success');
           user = data.items;
           }
           });
    }

    function getMedCreator(UserId) {
 	var cadenaGUD = '<?php echo $domain;?>/GetMedCreator.php?UserId='+UserId;
    $.ajax(
           {
           url: cadenaGUD,
           dataType: "json",
           async: false,
           success: function(data)
           {
           //alert ('success');
           doctor = data.items;
           }
           });
    }

        
    $(document).ready(function() { 
        
        
         var cookieValue = $.cookie("health2me");
        
        if (cookieValue == "H2M116130") {
            
           $("#notification_bar").html("");
        
        }else{
            
            document.cookie="health2me=H2M116130";
            $("#notification_bar").html('<div style="position: fixed;text-align:center;width: 100%; height: 44px; color: white; z-index: 2; background-color: rgb(119, 190, 247);"><div id="notification_bar_msg" style="display:inline-block;margin-right:20px;height:40px;vertical-align: middle;">We use cookies to improve your experience. By your continued use of this site you accept such use. </div><div id="notification_bar_close" style="display:inline-block;margin-top: 2px;margin-left:40px"><i class="icon-remove-circle icon-3x"></i></div></div>');
            $("#notification_bar").slideDown("fast");
            
        }
        
        $("#notification_bar_close").on('click', function()
        { 
        
            
        $("#notification_bar").slideUp("fast");
        
        
        });
        
        // the following code is for selecting different users if this is a family account
        var family_editing_mode = 1;
        var selected_family_member = 0;
        $("#select_users_button").on('click', function()
        {
            console.log($(this).children().length);
            if($(this).children().eq(0).hasClass("icon-caret-down"))
            {
                $(this).children().removeClass("icon-caret-down").addClass("icon-caret-up");
                $("#select_users").css("display", "block");
            }
            else
            {
                $(this).children().removeClass("icon-caret-up").addClass("icon-caret-down");
                $("#select_users").css("display", "none");
            }
        });
        
        $(".user_button").hover( function()
        {
            $(this).css("background-color", "#49BFFF");
        }, function()
        {
            $(this).css("background-color", "#22AEFF");
        });
        
        $(".user_button").live('click', function()
        {
            var email = $(this).attr('id').split("_")[2];
            var org_user = "<?php echo $original_user; ?>";
            var age = parseInt($(this).attr('id').split("_")[3]);
            var grant_access = parseInt($(this).attr('id').split("_")[4]);
            if(age >= 18 && org_user != email && grant_access == 0)
            {
                alert("This user cannot be accessed because the user account is private");
            }
            else
            {
                $.post("logout.php", {logging_out_family_member: true}, function()
                {
                    $.post("loginUSER.php", {Nombre: email},  function(data, status)
                    {
                        location.reload();
                    });
                });
            }
        });
        
        $(".change_user_dropdown_button").live('click', function(e)
        {
            e.preventDefault();
            var email = $(this).attr('id').split("_")[2];
            var org_user = "<?php echo $original_user; ?>";
            var age = parseInt($(this).attr('id').split("_")[3]);
            var grant_access = parseInt($(this).attr('id').split("_")[4]);
            if(age >= 18 && org_user != email && grant_access == 0)
            {
                alert("This user cannot be accessed because the user account is private");
            }
            else
            {
                $.post("logout.php", {logging_out_family_member: true}, function()
                {
                    $.post("loginUSER.php", {Nombre: email},  function(data, status)
                    {
                        location.reload();
                    });
                });
            }
        });
        
        $("#upgrade_premium_button").on('click', function()
        {
            $("#upgrade_loading_bar").css('visibility', 'visible');
            $.post("change_subscription.php", {user: $("#USERID").val(), plan: 1}, function(data, status)
            {
                $("#upgrade_loading_bar").css('visibility', 'hidden');
                if(data == 'GOOD')
                {
                    location.reload();
                }
                else
                {
                    alert("You have not entered a credit card for your account, please enter a credit card by selecting the credit card icon to change your subscription");
                }
            });
        });
        
        $("#upgrade_family_button").on('click', function()
        {
            $("#upgrade_loading_bar").css('visibility', 'visible');
            $.post("change_subscription.php", {user: $("#USERID").val(), plan: 2}, function(data, status)
            {
                $("#upgrade_loading_bar").css('visibility', 'hidden');
                if(data == 'GOOD')
                {
                    location.reload();
                }
                else
                {
                    alert("You have not entered a credit card for your account, please enter a credit card by selecting the credit card icon to change your subscription");
                }
            });
        });
        
        $("#add_family_member_button").on('click', function()
        {
            family_editing_mode = 1;
            $("#family_members").css("display", "none");
            $("#edit_family_member").css("display", "block");
            $("#give_admin_privileges").children().eq(0).val('0');
            $("#give_admin_privileges").children().eq(1).css("display", "none");
            $("#family_member_name").val('');
            $("#family_member_surname").val('');
            $("#family_member_dob").val('');
            $("#family_member_phone").val('');
            $("#family_member_password").val('');
            $("#family_member_password2").val('');
            $("#family_member_email").val('');
            $("#family_member_order").val('');
            $("#family_member_gender").val('none');
            $("#family_member_gender").change();
            $("#family_member_relationship").val('none');
            $("#family_member_relationship").change();
        });
        $("#grant_admin_access_button").on('click', function()
        {
            console.log("hello");
            var access = 1;
            if($(this).text().search("Remove") != -1)
            {
                access = 0;
                $(this).css('background-color', "#54bc00");
                $(this).text('Grant Admin Access To My Account');
            }
            else
            {
                $(this).css('background-color', '#D84840');
                $(this).text('Remove Admin Access To My Account');
            }
            $.post("EditUserFam.php", {User: $("#USERID").val(), Access: access, Grant_Access: true});
        });
        
        //New Variable added  by Pallab
        var stausOfFamilyEditingMode;
        $("#edit_family_member_done_button").on('click', function()
        {
           /* //Start of new code added by Pallab
            if(stausOfFamilyEditingMode > 0)
            {
               console.log("In edit_family_member_done_button");
               family_editing_mode = 2;
            }
            //End of new code added by Pallab
            */
            console.log("edit_family_member_done_button"+family_editing_mode); //Line added by Pallab
            
            $("#setup_modal_notification").html('<img src="images/load/8.gif" alt="">');
            $("#setup_modal_notification").css("background-color", "#FFF");
            $("#setup_modal_notification_container").css("opacity", "1.0");
            $.post("EditUserFam.php", {Name: $("#family_member_name").val(), Surname: $("#family_member_surname").val(), DOB: $("#family_member_dob").val(), Phone: $("#family_member_phone").val(), Password: $("#family_member_password").val(), Password2: $("#family_member_password2").val(), Email: $("#family_member_email").val(), Order: $("#family_member_order").val(), Gender: $("#family_member_gender").val(), Relationship: $("#family_member_relationship").val(), Admin: $("#give_admin_privileges").children().eq(0).val(), Owner: $("#USERID").val(), Mode: family_editing_mode, User: selected_family_member}, function(data, status)
            {
                $("#setup_modal_notification_container").css("opacity", "0.0");
                if(data.substr(0, 4) == 'GOOD')
                {
                    var items = data.split('_');
                    if(family_editing_mode == 1)
                    {
                        
                        var html = '<div id="family_member_row_'+items[1]+'" class="family_member_row">';
                        html += '<div style="width: 20%; height: 26px; float: left;">'+$("#family_member_name").val()+' '+$("#family_member_surname").val()+'</div>';
                        html += '<div style="width: 20%; height: 26px; float: left; color: #22AEFF;">'+$("#family_member_relationship").val()+'</div>';
                        html += '<div style="width: 20%; height: 26px; float: left; color: #54bc00; ';
                        if($("#give_admin_privileges").children().eq(0).val() == '1')
                        {
                            html += 'font-weight: bold;';
                            html += '">Admin</div>';
                        }
                        else
                        {
                            html += '">Delegated</div>';
                        }
                        html += '<button id="family_member_edit_'+items[1]+'" style="width: 15%; margin-left: 5%; height: 26px; float: left; color: #FFF; background-color: #54bc00; border-radius: 5px; outline: none; border: 0px solid #FFF;">Edit</button>';
                        html += '<button id="family_member_delete_'+items[1]+'" style="width: 15%; margin-left: 5%; height: 26px; float: left; color: #FFF; background-color: #D84840; border-radius: 5px; outline: none; border: 0px solid #FFF;">Delete</button>';
                        html += '</div>';

                        $("#family_users").append(html);

                        $("#family_members_dropdown").append('<li><a href="#" class="change_user_dropdown_button" id="user_'+items[1]+'_'+$("#family_member_email").val()+'_'+items[2]+'_'+items[3]+'" lang="en"><i class="icon-user"></i> '+$("#family_member_name").val()+' '+$("#family_member_surname").val()+'</a></li>');

                        $("#select_users").children().last().css("border-bottom-right-radius", "0px");
                        $("#select_users").children().last().css("border-bottom-left-radius", "0px");
                        html = '<button id="user_'+items[1]+'_'+$("#family_member_email").val()+'_'+items[2]+"_"+items[3]+'" class="user_button" style="width: 100%; height: 30px; background-color: #22AEFF; outline: 0px; border: 1px solid #555; color: #FFF; border-bottom-left-radius: 5px; border-bottom-right-radius: 5px;">'+$("#family_member_name").val()+' '+$("#family_member_surname").val()+'</button>';
                        $("#select_users").append(html);
                        $("#select_users").children().first().css("border-top-right-radius", "5px");
                        $("#select_users").children().first().css("border-top-left-radius", "5px");
                        $("#select_users").children().last().css("border-bottom-right-radius", "5px");
                        $("#select_users").children().last().css("border-bottom-left-radius", "5px");

                        $(".user_button").hover( function()
                        {
                            $(this).css("background-color", "#49BFFF");
                        }, function()
                        {
                            $(this).css("background-color", "#22AEFF");
                        });

                        $("#select_users").css("height", (($("#select_users").children().length - 1) * 30) + 'px');
                        $("#select_users").css("margin-bottom", '-' + ((($("#select_users").children().length - 1) * 30) + 14) + 'px');

                        
                        $("#family_members").css("display", "block");
                        $("#edit_family_member").css("display", "none");
                        $("#setup_modal_notification").css("background-color", "#52D859");
                        $("#setup_modal_notification").html('<p style="color: #fff;">Family Member Added!</p>');
                        $("#setup_modal_notification_container").animate({opacity: '1.0'}, {duration: 1000, easing: 'easeInOutQuad', complete: function() {
                            setTimeout(function(){$("#setup_modal_notification_container").animate({opacity: '0.0'}, {duration: 1000, easing: 'easeInOutQuad', complete: function() {}});}, 2000);
                        }});
                    }
                    else
                    {
                        $("#family_member_row_"+selected_family_member).children().eq(0).text($("#family_member_name").val()+' '+$("#family_member_surname").val());
                        $("#family_member_row_"+selected_family_member).children().eq(1).text($("#family_member_relationship").val());
                        if($("#give_admin_privileges").children().eq(0).val() == '1')
                        {
                            $("#family_member_row_"+selected_family_member).children().eq(2).text('Admin');
                            $("#family_member_row_"+selected_family_member).children().eq(2).css("font-weight", "bold");
                        }
                        else
                        {
                            $("#family_member_row_"+selected_family_member).children().eq(2).text('Delegated');
                            $("#family_member_row_"+selected_family_member).children().eq(2).css("font-weight", "normal");
                        }
                        $('button[id^="user_'+selected_family_member+'"]').text($("#family_member_name").val()+' '+$("#family_member_surname").val());
                        $('button[id^="user_'+selected_family_member+'"]').attr('id', 'user_'+selected_family_member+'_'+$("#family_member_email").val()+'_'+items[2]+'_'+items[3]);
                        
                        $('a[id^="user_'+selected_family_member+'"]').html('<i class="icon-user"></i> '+$("#family_member_name").val()+' '+$("#family_member_surname").val());
                        $('a[id^="user_'+selected_family_member+'"]').attr('id', 'user_'+selected_family_member+'_'+$("#family_member_email").val()+'_'+items[2]+'_'+items[3]);
                            
                        $("#family_members").css("display", "block");
                        $("#edit_family_member").css("display", "none");
                        $("#setup_modal_notification").css("background-color", "#52D859");
                        $("#setup_modal_notification").html('<p style="color: #fff;">Family Member Edited!</p>');
                        $("#setup_modal_notification_container").animate({opacity: '1.0'}, {duration: 1000, easing: 'easeInOutQuad', complete: function() {
                            setTimeout(function(){$("#setup_modal_notification_container").animate({opacity: '0.0'}, {duration: 1000, easing: 'easeInOutQuad', complete: function() {}});}, 2000);
                        }});
                    }
                }
                else
                {
                    $("#setup_modal_notification").css("background-color", "#D5483A");
                    $("#setup_modal_notification").html('<p style="color: #fff;">'+data+'</p>');
                    $("#setup_modal_notification_container").animate({opacity: '1.0'}, {duration: 1000, easing: 'easeInOutQuad', complete: function() {
                        setTimeout(function(){$("#setup_modal_notification_container").animate({opacity: '0.0'}, {duration: 1000, easing: 'easeInOutQuad', complete: function() {}});}, 2000);
                    }});
                }
            });
            
            
            
            
        });
        $("#edit_family_member_cancel_button").on('click', function()
        {
            $("#family_members").css("display", "block");
            $("#edit_family_member").css("display", "none");
        });
        $("#give_admin_privileges").on('click', function()
        {
            if($(this).children().eq(1).css("display") == 'block')
            {
                $(this).children().eq(0).val('0');
                $(this).children().eq(1).css("display", "none");
            }
            else
            {
                $(this).children().eq(0).val('1');
                $(this).children().eq(1).css("display", "block");
            }
        });
        $("#family_member_phone").intlTelInput();
        $("#family_member_phone").css("width", "214px").css("height", "26px").css("margin-top", "0px");
        
        // to delete a family member:
        $('button[id^="family_member_delete_"]').live('click', function()
        {
            var id = $(this).attr("id").split("_")[3];
            var r = confirm("Are you sure you want to delete this family member?");
            if (r == true) 
            {
                $.post("EditUserFam.php", {Delete: true, User: id}, function(data, status)
                {
                    if(data == '1')
                    {
                        $("#family_member_row_"+id).remove();
                        $('button[id^="user_'+id+'"]').remove();
                        $('a[id^="user_'+id+'"]').remove();
                        $("#select_users").css("height", (($("#select_users").children().length - 1) * 30) + 'px');
                        $("#select_users").css("margin-bottom", '-' + ((($("#select_users").children().length - 1) * 30) + 14) + 'px');
                        $("#select_users").children().last().css("border-bottom-right-radius", "5px");
                        $("#select_users").children().last().css("border-bottom-left-radius", "5px");
                        $("#select_users").children().first().css("border-top-right-radius", "5px");
                        $("#select_users").children().first().css("border-top-left-radius", "5px");
                        
                        $("#setup_modal_notification").css("background-color", "#52D859");
                        $("#setup_modal_notification").html('<p style="color: #fff;">Family Member Deleted!</p>');
                        $("#setup_modal_notification_container").animate({opacity: '1.0'}, {duration: 1000, easing: 'easeInOutQuad', complete: function() {
                            setTimeout(function(){$("#setup_modal_notification_container").animate({opacity: '0.0'}, {duration: 1000, easing: 'easeInOutQuad', complete: function() {}});}, 2000);
                        }});
                    }
                    else
                    {
                        $("#setup_modal_notification").css("background-color", "#D5483A");
                        $("#setup_modal_notification").html('<p style="color: #fff;">Unable to delete this family member.</p>');
                        $("#setup_modal_notification_container").animate({opacity: '1.0'}, {duration: 1000, easing: 'easeInOutQuad', complete: function() {
                            setTimeout(function(){$("#setup_modal_notification_container").animate({opacity: '0.0'}, {duration: 1000, easing: 'easeInOutQuad', complete: function() {}});}, 2000);
                        }});
                    }
                });
            }
        });
        
        // to edit a family member:
        $('button[id^="family_member_edit_"]').live('click', function()
        {
           // stausOfFamilyEditingMode = 2;
            var id = $(this).attr("id").split("_")[3];
            selected_family_member = id;
            family_editing_mode = 2;
            $.post("EditUserFam.php", {Get_info: true, User: id}, function(data, status)
            {
                console.log(data);
                var dat = JSON.parse(data);
                if(dat['subsType'] == 'Admin' || dat['subsType'] == 'Owner')
                {
                    $("#give_admin_privileges").children().eq(0).val('1');
                    $("#give_admin_privileges").children().eq(1).css("display", "block");
                }
                else
                {
                    $("#give_admin_privileges").children().eq(0).val('0');
                    $("#give_admin_privileges").children().eq(1).css("display", "none");
                }
                $("#family_member_name").val(dat['Name']);
                $("#family_member_surname").val(dat['Surname']);
                $("#family_member_dob").val(dat['DOB'].split(' ')[0]);
                $("#family_member_phone").val('+'+dat['telefono']);
                $("#family_member_password").val('');
                $("#family_member_password2").val('');
                $("#family_member_email").val(dat['email']);
                $("#family_member_order").val(dat['Orden']);
                if(dat['Sexo'] == '0')
                {
                    $("#family_member_gender").val('female');
                }
                else
                {
                    $("#family_member_gender").val('male');
                }
                $("#family_member_gender").change();
                $("#family_member_relationship").val(dat['relationship']);
                $("#family_member_relationship").change();
                $("#family_members").css("display", "none");
                $("#edit_family_member").css("display", "block");
            });
        });
        
        if($("#CURRENTCALLINGDOCTOR").val() != '' && $("#CURRENTCALLINGDOCTOR").val() != '0' && $("#CURRENTCALLINGDOCTOR").val() != '-1')
        {
            $("#video_consultation_text").text("You have a video consultation appointment with Doctor " + $("#CURRENTCALLINGDOCTORNAME").val() + ".");
            $("#telemed_notificator").css("display", "block");
        }
        $("#telemed_connect_button").live("click", function()
        {
            window.open("telemedicine_patient.php?MED="+$("#CURRENTCALLINGDOCTOR").val()+"&PAT="+$("#USERID").val()+"&show=0","Telemedicine","height=585,width=700,left=100,top=100,resizable=yes,scrollbars=yes,toolbar=yes,menubar=no,location=no,directories=no,status=yes");
        });
        $("#telemed_deny_button").live("click", function()
        {
            $("#telemed_notificator").slideUp();
            $.post("reset_appointment.php", {pat_id: $("#USERID").val()}, function(){});
        });
	
	$(window).load(function() {
		$('#LoadCanvas1').css('display','none');
		$('#LoadCanvas2').css('display','none');
		LoadDonuts();
        $('#PHSLabel').trigger('click');
	});
	
	function LoadDonuts(){
	//var timerId = setTimeout(function() { 

        var UserID = <?php echo $UserID;?>;
		
		var AdminData = 0;
		// Ajax call to retrieve a JSON Array **php return array** 
//		$.post("GetConnectedLight.php", {User: queMED, NReports: 3, Group: group}, function(data, status)
		var queUrl = 'GetSummaryData.php?User='+UserID;
		$.ajax(
		{
			url: queUrl,
			dataType: "json",
			async: false,
			success: function(data)
			{
				//alert('Data Fetched');
				ConnData = data.items;
			}
		});
		
	
	  AdminData = ConnData[0].Data;
	  PastDx = ConnData[1].Data;
	  Medications = ConnData[2].Data;
	  Allergies = ConnData[3].Data;
	  Family = ConnData[4].Data;
	  Habits = ConnData[5].Data;
	  
	  //alert ('AdminData: '+AdminData+'   Admin Latest Update: '+ConnData[0].latest_update+'   Admin Doctor: '+ConnData[0].doctor_signed+' ***********   '+'DxData: '+ConnData[1].Data+'   Dx Latest Update: '+ConnData[1].latest_update+'   Dx Doctor: '+ConnData[1].doctor_signed+' ');

	  // SUMMARY GRAPH
	  var canvas = document.getElementById('myCanvas');
      var context = canvas.getContext('2d');

      var x = canvas.width / 2;
      var y = canvas.height / 2;
      var radius = 75;
      var startAngle = 0 - (Math.PI/2);
      var counterClockwise = false;
	  
	  var ColSeg = new Array();
	  var SizSeg = new Array();
	  var ImgSeg = new Array();
	  var MaxValue = new Array();
	  var UIValue = new Array();
	  var NameSeg1 = new Array();
	  var NameSeg2 = new Array();
	  
	  ColSeg[1] = '#54bc00';
	  MaxValue[1] = 5;
	  UIValue[1] = 10;
	  if (AdminData > MaxValue[1]) {SizSeg[1] = UIValue[1]} else {SizSeg[1] = (AdminData * UIValue[1] / MaxValue[1])};
	  //alert (SizSeg[1]+' - '+MaxValue[1]+' - '+UIValue[1]+' - '+AdminData);
	  ImgSeg[1] = 'Admin';
	  NameSeg1[1] = 'Admin';
	  NameSeg2[1] = 'Data';
	 
	  ColSeg[2] = '#f39c12';
	  UIValue[2] = 10;
	  if (Allergies > 0) SizSeg[2] = 10; else SizSeg[2]=0;
	  ImgSeg[2] = 'Immuno';
	  NameSeg1[2] = 'Immun';
	  NameSeg2[2] = 'Allergy';
	  
	  ColSeg[3] = '#2c3e50';
	  MaxValue[3] = 5;
	  UIValue[3] = 40
	  if (PastDx > 0) SizSeg[3] = 40; else SizSeg[3]=0;
	  //SizSeg[3] = 40;
	  ImgSeg[3] = 'History';
	  NameSeg1[3] = 'Perso';
	  NameSeg2[3] = 'History';
	  
	  ColSeg[4] = '#18bc9c';
	  SizSeg[4] = 15;
	  UIValue[4] = 15;
	  if (Medications > 0) SizSeg[4] = 15; else SizSeg[4]=0;
	  ImgSeg[4] = 'Medication';
	  NameSeg1[4] = 'Drugs';
	  NameSeg2[4] = 'Meds';
	  	  
	  ColSeg[5] = '#e74c3c';
	  SizSeg[5] = 15;
	  UIValue[5] = 15;
	  if (Family > 0) SizSeg[5] = 15; else SizSeg[5]=0;
	  ImgSeg[5] = 'Family';
	  NameSeg1[5] = 'Family';
	  NameSeg2[5] = 'History';
	  
	  ColSeg[6] = '#3498db';
	  SizSeg[6] = 10;
	  UIValue[6] = 10;
	  if (Habits > 0) SizSeg[6] = 10; else SizSeg[6]=0;
	  ImgSeg[6] = 'Habits';
	  NameSeg1[6] = 'Habits';
	  NameSeg2[6] = 'Life';


	  //  *********************    Labels Section PART 1 (Review, calculate positions and swap array)
	  // Get points for Label Positioning
	  var side = new Array();
	  var rightPoints = 0;
	  var leftPoints = 0;
	  var orderside = 1;
	  var maxside = new Array();
	  var XBoxSize = 70;
	  var YBoxSize = 20;
	  var XBox = 0;
	  var YBox = 0;
	  var Swaped = new Array();

	  var n = 1;
	  var CumData = 0;
	  var midPos = Array();
	  startAngle = 0 - (Math.PI/2);
	  while (n < 7)
	  {
	  	  endAngle = startAngle + TranslateAngle(SizSeg[n],100);
	      midAngle = startAngle + (TranslateAngle(SizSeg[n]/2,100));
	      midPos[n] = midAngle;

		 halfsegment = SizSeg[n] / 2; 
	  	 //console.log ('N:  '+n+'       SizSeg: '+SizSeg[n]+'   Halfsegment:  '+halfsegment)
	  	 CumData = CumData + SizSeg[n];
	  	 //console.log ('N:  '+n+'       CumData: '+CumData+'   Halfsegment:  '+halfsegment)
	  	 if ((CumData - halfsegment) < 50 ) {
	  	 		side[n] = 'Right'; 
	  	 		rightPoints++;
	  	 	}else 
	  	 	{
	  	 		side[n] = 'Left';
	  	 		leftPoints++;
	  	 	}

	      startAngle = endAngle;
	      lastAngle = endAngle;
	  	 n++;
	  }
	  console.log ('Right:'+rightPoints+'    Left:'+leftPoints);
	  //  *********************    Labels Section PART 1 (Review, calculate positions and swap array)

	  var SummaryData = 0;
	  
      context.lineWidth = 35;
	  startAngle = 0 - (Math.PI/2);
	  var lastAngle = 0;
	  var divisor = 1;
	      	    
	  var n = 1;
	  var leftorderside = 0;
	  while (n < 7)
	  {
	  	  SummaryData = SummaryData + SizSeg[n];
	      context.beginPath();
	      ColorRGB = hexToRgb(ColSeg[n]);
	      // Opacity value reflects how recent data is
	      var Opac = Math.random();
	      var Opac = 1;
	      SegColor = 'rgba('+ColorRGB.r+','+ColorRGB.g+','+ColorRGB.b+','+Opac+')';
	      
	      // Draw the segments
 	      context.beginPath();
	      context.strokeStyle = SegColor;
	      context.lineCap = 'butt';
	      endAngle = startAngle + TranslateAngle(SizSeg[n],100);
	      midAngle = startAngle + (TranslateAngle(SizSeg[n]/2,100));
	      midPos[n] = midAngle;
		  context.lineWidth = 35;
	      context.arc(x, y, radius, startAngle, endAngle, counterClockwise);
	      context.stroke();
 	      //context.closePath();
 	      //context.restore();
 	      
	      // Draw icons into segments
	      var posx = x + (radius * Math.cos (midAngle)) - 12;     
	      var posy = y + (radius * Math.sin (midAngle)) - 12;
	      var borx = x + ((radius+(35/2)) * Math.cos (midAngle));   // Coordinates of the edge of the circle at its center
	      var bory = y + ((radius+(35/2)) * Math.sin (midAngle));   // Coordinates of the edge of the circle at its center
		  var imageObj=document.getElementById(ImgSeg[n]);
		  if (SizSeg[n] > 4) context.drawImage(imageObj, posx, posy,25,25);
	     

				  //  *********************    Labels Section   MAIN PART  *************************************************
				  //side = 'Left';
				  if (n <= rightPoints ) orderside = n; else orderside = n - rightPoints;
				   				  
				  //console.log('N: '+n+'  side: '+side[n]+'  left: '+leftPoints+'    right:  '+rightPoints+' ');
				  
				  // XBox and YBox are the coordinates of the "virtual" box than contains the label
				  if (side[n] == 'Left') {
					  	leftorderside++;
					  	XBox = 10 ; 
					  	divisor = (y*2) / (leftPoints+1);
					  	//console.log('y:  '+(y*2)+'     divisor: '+divisor+'    YBox: '+YBox+'    Arrival Segment: '+(6 - (n - leftPoints + 1 )));
					    // Invert arrival of line to segment for left side points
					    Swaped[n] = 6 - (n - leftPoints + 1 ); 
					    Swaped[n] = n; 
					    borx = x + ((radius+(35/2)) * Math.cos (midPos[Swaped[n]]));
					    bory = y + ((radius+(35/2)) * Math.sin (midPos[Swaped[n]]));
					  	YBox = (y*2) - (divisor * leftorderside);
					  	// Vertical Difference between Box and Edge here:
					  	//VerticalDiff = YBox - bory;
					  	//if (Math.abs(VerticalDiff) > 50) YBox = YBox - (VerticalDiff/2);
				  	}
				  else 
				  	{
					  	XBox = ((x) - (radius/2) + 20);
					  	XBox = 280;
					  	divisor = (y*2) / (rightPoints+1);
					  	YBox = divisor * orderside;
						Swaped[n] = n; 
						// Vertical Difference between Box and Edge here:
					  	VerticalDiff = YBox - bory;
					  	console.log('Diff: '+VerticalDiff+'     YBox: '+YBox+'     bory:'+bory);
					  	if (Math.abs(VerticalDiff) > 40) YBox = YBox - (VerticalDiff/2);
				  }
				  
			      // Virtual Box for the Label
				  
				  /*
			      context.beginPath();
			      context.fillStyle = '#cacaca';
			      context.fillRect(XBox,YBox,XBoxSize,YBoxSize);
			      context.stroke();
				  */
				  
				  // Label Text
				  
				  context.font = "10px Arial";
			      context.fillStyle = '#b6b6b6';
				  context.fillText(NameSeg1[Swaped[n]],XBox,YBox+8);
				  context.fillText(NameSeg2[Swaped[n]],XBox,YBox+8+10);
			     
			      
			      // Divisory Line
			      
			      context.beginPath();
				  context.lineWidth = 3;
				  context.strokeStyle = ColSeg[Swaped[n]];
				  context.lineCap = 'round';
		 	      context.moveTo(XBox +35, YBox);
			      context.lineTo(XBox +35, YBox+20);
			      context.stroke();
				  
				  // Section Percentage
				  
				  context.font = "bold 12px Arial";
			      context.fillStyle = 'grey';
			      percentSeg = parseInt (100 * (SizSeg[Swaped[n]] / UIValue[Swaped[n]]));
				  if (percentSeg == 100) labelSeg = 'OK'; else labelSeg = percentSeg + '%';
				  context.fillText(labelSeg,XBox+40,YBox+3+10);
			      //context.stroke();

				  // Connecting Line			      
			      context.beginPath();
				  context.strokeStyle = '#cacaca';
				  context.lineWidth = 2;
				  if (side[n] == 'Left') 
				  {
				  	X1 = XBox +40 + 30; 
				  	XMiddle = borx - ((borx-X1)/2);
				  }
				  else 
				  {
				  	X1 = XBox - 5;
				  	XMiddle = X1 - ((X1 - borx)/2);
			      }
			      Y1 = YBox+3+10-5;
				  YMiddle = bory + ((Y1-bory)/2);
		 	      YMiddle = bory ;
		 	      context.moveTo(X1, Y1); 
			      //context.lineTo(XMiddle,YMiddle);
			      //context.lineTo(borx,bory);
			      context.bezierCurveTo(XMiddle,YMiddle,XMiddle,YMiddle,borx,bory);
			      context.stroke();

				  context.lineCap = 'butt';
			      
				  //  *********************    Labels Section   MAIN PART  *************************************************
	     
	     
	      startAngle = endAngle;
	      lastAngle = endAngle;
	      n++;
	  }
 	      // Draw Inner Circle
 	      context.beginPath();
		  context.fillStyle = '#22aeff';
	      context.lineWidth = 1;
		  context.arc(x, y, radius-20, (-Math.PI/2), (Math.PI*2), counterClockwise);
	      context.fill();
	      context.stroke();
		  context.lineWidth = 35;

 		  // Draw Main Text
		  var font = '30pt Lucida Sans Unicode';
		  var message = SummaryData+' %';
		  context.fillStyle = '#22aeff';
		  context.fillStyle = 'white';
		  context.textAlign = 'left';
		  context.textBaseline = 'top'; // important!
		  context.font = font;
		  var w = context.measureText(message).width;
		  var TextH = GetCanvasTextHeight(message,font);
		  context.fillText(message, x-(w/2), y-(TextH));

		  
		  //Draw "Missing" segment
 	      context.beginPath();
	      ColorRGB = hexToRgb('#C0C0C0');
	      // Opacity value reflects how recent data is
	      var Opac = 0.2;
	      SegColor = 'rgba('+ColorRGB.r+','+ColorRGB.g+','+ColorRGB.b+','+Opac+')';
		  context.strokeStyle = SegColor;
 	      var MissingSize = 100 - SummaryData;
	      endAngle = startAngle + TranslateAngle(MissingSize,100);
	      midAngle = startAngle + (TranslateAngle(MissingSize/2,100));
		  context.lineWidth = 35;
	      context.arc(x, y, radius, startAngle, endAngle, counterClockwise);
	      context.stroke();

		  //Draw external border to existing group of segments
		  context.beginPath();
	      startAngle = 0 - (Math.PI/2);
	      endAngle = lastAngle;
	      context.strokeStyle = '#585858';
		  context.lineWidth = 2;
	      context.arc(x, y, radius+17, startAngle, endAngle, counterClockwise);
	      context.stroke();
	      context.beginPath();
	      context.arc(x, y, radius-17, startAngle, endAngle, counterClockwise);
	      context.stroke();
	      context.beginPath();
	      var posx = x + ((radius-18) * Math.cos (startAngle));
	      var posy = y + ((radius-18) * Math.sin (startAngle));
	      var posx2 = x + ((radius+18) * Math.cos (startAngle));
	      var posy2 = y + ((radius+18) * Math.sin (startAngle));
		  context.moveTo(posx,posy);
	      context.lineTo(posx2,posy2);
	      var posx = x + ((radius-18) * Math.cos (lastAngle));
	      var posy = y + ((radius-18) * Math.sin (lastAngle));
	      var posx2 = x + ((radius+18) * Math.cos (lastAngle));
	      var posy2 = y + ((radius+18) * Math.sin (lastAngle));
		  context.moveTo(posx,posy);
	      context.lineTo(posx2,posy2);
	      context.stroke();	
		  
 
 	  // REPORTS GRAPH
 	  // Get Basic Icons and Colors for every type of report
 	  	var RepData = Array();
		// Ajax call to retrieve a JSON Array **php return array** 
		var queUrl = 'GetReportSet.php';
		$.ajax(
		{
			url: queUrl,
			dataType: "json",
			async: false,
			success: function(data)
			{
				//alert('Data Fetched');
				RepData = data.items;
			},
 	       error: function (xhr, ajaxOptions, thrownError) {
	        	alert(xhr.status);
				alert(thrownError);
	       }

		});
 	  //
 	  // Get Report Data for this user
 	  	var RepNumbers = Array();
		// Ajax call to retrieve a JSON Array **php return array** 
		var queUrl = 'GetReportNumbers.php?User='+UserID;
		$.ajax(
		{
			url: queUrl,
			dataType: "json",
			async: false,
			success: function(data)
			{
				//alert('Data Fetched');
				RepNumbers = data.items;
			},
 	       error: function (xhr, ajaxOptions, thrownError) {
	        	alert(xhr.status);
				alert(thrownError);
	       }

		});
 	  //
 	  

	  var canvas = document.getElementById('myCanvas2');
      var context = canvas.getContext('2d');
      var x = canvas.width / 2;
      var y = canvas.height / 2;
      var radius = 85;
      var widthSegment = 15;
      var startAngle = 0 - (Math.PI/2);
      var counterClockwise = false;

 	  ColSeg.length = 0;
	  SizSeg.length = 0;
	  ImgSeg.length = 0;
	  
	  var qx = 1;
	  var TotalRep = 0;
	  while (qx < 10){
		  ColSeg[qx] = RepData[qx-1].color;
		  SizSeg[qx] = RepNumbers[qx-1].number;
		  NameSeg1[qx] = RepData[qx-1].title.substring(0, 4);
		  NameSeg2[qx] = RepData[qx-1].abrev;
		  TotalRep = TotalRep + parseInt(SizSeg[qx]);
		  qx++;
	  }
	  
	  ImgSeg[1] = 'R1';
	  ImgSeg[2] = 'R2';
	  ImgSeg[3] = 'R3';
	  ImgSeg[4] = 'R4';
	  ImgSeg[5] = 'R5';
	  ImgSeg[6] = 'R6';
	  ImgSeg[7] = 'R7';
	  ImgSeg[8] = 'R8';
	  ImgSeg[9] = 'R9';
	  ImgSeg[10] = 'R10';
     
	  var MaxSegments = 10;
	  var MaxReports = TotalRep;
     
      context.lineWidth = widthSegment;
	  startAngle = 0 - (Math.PI/2);


	  //  *********************    Labels Section PART 1 (Review, calculate positions and swap array)
	  // Get points for Label Positioning
	  var side = new Array();
	  var rightPoints = 0;
	  var leftPoints = 0;
	  var orderside = 1;
	  var maxside = new Array();
	  var XBoxSize = 70;
	  var YBoxSize = 20;
	  var XBox = 0;
	  var YBox = 0;
	  var Swaped = new Array();

	  var n = 1;
	  var sections = 0;
	  var CumData = 0;
	  var midPos = Array();
	  while (n <= MaxSegments)
	  {
	  if (SizSeg[n]>0)
		  {
		  	  endAngle = startAngle + TranslateAngle(SizSeg[n],100);
		      midAngle = startAngle + (TranslateAngle(SizSeg[n]/2,100));
		      midPos[n] = midAngle;
	
		  	 CumData = parseInt(CumData + parseInt(SizSeg[n]));
		  	 MidPoint = CumData - (parseInt(SizSeg[n])/2);
		  	 if (MidPoint <= (TotalRep/2) ) {
		  	 		side[n] = 'Right'; 
		  	 		rightPoints++;
		  	 	}else 
		  	 	{
		  	 		side[n] = 'Left';
		  	 		leftPoints++;
		  	 	}
	
			  //console.log ('Cummulated Number of Reports: '+parseInt(CumData)+'                    TotalReports/2: '+(TotalRep/2)+' ');
			  //console.log ('n: '+n+'  Size:'+SizSeg[n]+'      side:'+side[n]+'       midPos: '+midPos[n]+'             Angle Start:'+startAngle+'    Angle End: '+endAngle);

		      startAngle = endAngle;
		      lastAngle = endAngle;

			  
			  sections++;
			  
		  }
		  n++;
	  }
	 // console.log ('Total: '+sections+'     Right: '+rightPoints+'    Left: '+leftPoints);
	  //  *********************    Labels Section PART 1 (Review, calculate positions and swap array)

	      	    
	  var n = 1;
	  startAngle = 0 - (Math.PI/2);

	  while (n <= MaxSegments)
	  {
	      context.beginPath();
	      SegColor = ColSeg[n];
	      sections = 0;
	      if (SizSeg[n]>0){
		      sections++;
		      // Draw the segments
		      context.strokeStyle = SegColor;
		      endAngle = startAngle + TranslateAngle(SizSeg[n],MaxReports);
		      midAngle = startAngle + (TranslateAngle(SizSeg[n]/2,MaxReports));
			  context.lineWidth = widthSegment;
		      context.arc(x, y, radius, startAngle, endAngle, counterClockwise);
		      context.stroke();
	
		      // Draw badges at inner part of segments
			  var SizeBadge = 30;
			  var PointAngle = radius-(widthSegment/2)-(SizeBadge/2);
		      var posx = x + (PointAngle  * Math.cos (midAngle)) - (SizeBadge/2);
		      var posy = y + (PointAngle  * Math.sin (midAngle)) - (SizeBadge/2);
			  var imageObj=document.getElementById(ImgSeg[n]);
			  context.drawImage(imageObj, posx, posy,SizeBadge,SizeBadge);
	
				  //  *********************    Labels Section   MAIN PART  *************************************************
				  //side = 'Left';
			      var borx = x + ((radius+(widthSegment/2)) * Math.cos (midAngle));   // Coordinates of the edge of the circle at its center
			      var bory = y + ((radius+(widthSegment/2)) * Math.sin (midAngle));   // Coordinates of the edge of the circle at its center
				  if (sections <= rightPoints ) orderside = sections; else orderside = sections - rightPoints;
				   				  
				  //console.log('N: '+n+'  side: '+side[n]+'  left: '+leftPoints+'    right:  '+rightPoints+' ');
				  
				  // XBox and YBox are the coordinates of the "virtual" box than contains the label
				  if (side[n] == 'Left') {
					  	XBox = 10 ; 
					  	divisor = (y*2) / (leftPoints+1);
					    // Invert arrival of line to segment for left side points
					    Swaped[n] = 6 - (n - leftPoints + 1 ); 
					    if (Swaped[n]==0) Swaped[n]=1;
					    Swaped[n] = n; 
					    //borx = x + ((radius+(35/2)) * Math.cos (midPos[Swaped[n]]));
					    //bory = y + ((radius+(35/2)) * Math.sin (midPos[Swaped[n]]));
					  	YBox = divisor * orderside;
					  	// Vertical Difference between Box and Edge here:
					  	VerticalDiff = YBox - bory;
					  	if (Math.abs(VerticalDiff) > 2) YBox = YBox - (VerticalDiff/2);
					  	//console.log('Order: '+n+'   '+side[n]+' (Left y):  '+(y*2)+'     divisor: '+divisor+'    YBox: '+YBox+'   XBox:  '+XBox+'   Swaped: '+ Swaped[n]+ ' n =  '+n+'  orderside: '+orderside);
				  	}
				  else 
				  	{
					  	XBox = ((x) - (radius/2) + 20);
					  	XBox = 280;
					  	divisor = (y*2) / (rightPoints+1);
					  	YBox = divisor * orderside;
						Swaped[n] = n; 
						// Vertical Difference between Box and Edge here:
					  	VerticalDiff = YBox - bory;
					  	if (Math.abs(VerticalDiff) > 2) YBox = YBox - (VerticalDiff/2);
					  	//console.log('Order: '+n+'   '+side[n]+' (Right y):  '+(y*2)+'     divisor: '+divisor+'    YBox: '+YBox+'   XBox:  '+XBox+'   Swaped: '+ Swaped[n]+ ' n =  '+n+'  orderside: '+orderside);
				  
				  }
				  
			      // Virtual Box for the Label
				  
				  /*
			      context.beginPath();
			      context.fillStyle = '#cacaca';
			      context.fillRect(XBox,YBox,XBoxSize,YBoxSize);
			      context.stroke();
				  */
				  
				  // Label Text
				  
				  context.font = "10px Arial";
			      context.fillStyle = '#b6b6b6';
				  context.fillText(NameSeg1[Swaped[n]],XBox,YBox+8);
				  context.fillText(NameSeg2[Swaped[n]],XBox,YBox+8+10);
			     
			      
			      // Divisory Line
			      
			      context.beginPath();
				  context.lineWidth = 3;
				  context.strokeStyle = ColSeg[Swaped[n]];
				  context.lineCap = 'round';
		 	      context.moveTo(XBox +35, YBox);
			      context.lineTo(XBox +35, YBox+20);
			      context.stroke();
				  
				  // Section Percentage
				  
				  context.font = "bold 14px Arial";
			      context.fillStyle = 'grey';
			      //percentSeg = parseInt (100 * (SizSeg[Swaped[n]] / UIValue[Swaped[n]]));
				  percentSeg = SizSeg[Swaped[n]];
				  if (percentSeg == 100) labelSeg = 'OK'; else labelSeg = percentSeg + '';
				  context.fillText(labelSeg,XBox+40,YBox+5+10);
			      //context.stroke();

				  // Connecting Line			      
			      context.beginPath();
				  context.strokeStyle = '#cacaca';
				  context.lineWidth = 2;
				  if (side[n] == 'Left') 
				  {
				  	X1 = XBox +40 + 30; 
				  	XMiddle = borx - ((borx-X1)/2);
				  }
				  else 
				  {
				  	X1 = XBox - 5;
				  	XMiddle = X1 - ((X1 - borx)/2);
			      }
			      Y1 = YBox+3+10-5;
				  YMiddle = bory + ((Y1-bory)/2);
		 	      YMiddle = bory ;
		 	      context.moveTo(X1, Y1); 
			      //context.lineTo(XMiddle,YMiddle);
			      //context.lineTo(borx,bory);
			      context.bezierCurveTo(XMiddle,YMiddle,XMiddle,YMiddle,borx,bory);
			      context.stroke();

				  context.lineCap = 'butt';
			      
				  //  *********************    Labels Section   MAIN PART  *************************************************


	          //realn ++;

		      startAngle = endAngle;
	      }
		  n++;
	  }

 	      // Draw Inner Circle
 	      context.beginPath();
		  context.fillStyle = '#54bc00';
	      context.lineWidth = 1;
		  context.arc(x, y, radius-50, (-Math.PI/2), (Math.PI*2), counterClockwise);
	      context.fill();
          context.strokeStyle = "grey";
	      context.stroke();
		  context.lineWidth = 35;

 		  // Draw Main Text
		  //var font = '30pt Lucida Sans Unicode';
          var font = '28pt Arial';
		  var message = MaxReports+'';
		  context.fillStyle = '#54bc00';
		  context.fillStyle = 'white';
		  context.textAlign = 'left';
		  context.textBaseline = 'top'; // important!
		  context.font = font;
		  var w = context.measureText(message).width;
		  var TextH = GetCanvasTextHeight(message,font);
		  context.fillText(message, x-(w/2), y-(TextH-7));
 

 	//}, 500)
	};


	
	$('body').bind('mousedown keydown', function(event) {
        clearTimeout(timeoutTimer);
        timeoutTimer = setTimeout(ShowTimeOutWarning, timeoutTime);
    });
	
	var Canvas1State = 0;
	
     //Add code for new summary box -start
       $('#PHSLabel').live('click',function(){
	if (Canvas1State == 0)	{
		$('#PHSLabel').html('See Graph');
		var contentAlt = '';
		
		/*
		var contentAlt = 			'<p>Admin    Data: '+ConnData[0].Data+'   Latest Update: '+ConnData[0].latest_update+'    Doctor: '+ConnData[0].doctor_signed+'</p>';
		contentAlt = contentAlt+    '<p>DX       Data: '+ConnData[1].Data+'   Latest Update: '+ConnData[1].latest_update+'    Doctor: '+ConnData[1].doctor_signed+'</p>';
		contentAlt = contentAlt+    '<p>Med      Data: '+ConnData[2].Data+'   Latest Update: '+ConnData[2].latest_update+'    Doctor: '+ConnData[2].doctor_signed+'</p>';
		contentAlt = contentAlt+    '<p>Immuno   Data: '+ConnData[3].Data+'   Latest Update: '+ConnData[3].latest_update+'    Doctor: '+ConnData[3].doctor_signed+'</p>';
		contentAlt = contentAlt+    '<p>Family   Data: '+ConnData[4].Data+'   Latest Update: '+ConnData[4].latest_update+'    Doctor: '+ConnData[4].doctor_signed+'</p>';
		contentAlt = contentAlt+    '<p>Habits   Data: '+ConnData[5].Data+'   Latest Update: '+ConnData[5].latest_update+'    Doctor: '+ConnData[5].doctor_signed+'</p>';
		*/
		
		var bestDate = Date.parse(ConnData[0].latest_update);		
		var sourceDate = '';
		var textDate = '';
		var titleU = '';
		var k = 0;
		while (k < 5)
		{
			thisDate = Date.parse(ConnData[k].latest_update);
			if (thisDate >= bestDate) 
				{ 
					bestDate = thisDate;	
					sourceDate = ConnData[k].latest_update;
					//thisVerified = Date.parse(ConnData[k].doctor_signed);
					//if (thisVerified > -1) { bestVerified = ConnData[k].doctor_signed; }		
				}		
			k++;
		} 
		var translation = '';
		var translation2 = '';
		var translation3 = '';
		var translation4 = '';
		var translation5 = '';
		
		if(initial_language == 'th'){
		translation = 'Última actualización en';
		translation2 = 'dias';
		translation3 = 'semana';
		translation4 = 'meses';
		translation5 = 'años';
		}else if(initial_language == 'en'){
		translation = 'Latest update on';
		translation2 = 'days';
		translation3 = 'weeks';
		translation4 = 'months';
		translation5 = 'years';
		}
		
		titleU =  translation+' '+ sourceDate.substr(0, 10);			
		var todayDate = new Date();
		var ageDate = todayDate - bestDate;
		var DiffDays = parseInt(ageDate / (1000*60*60*24)) ;
		var DiffWeeks = parseInt(ageDate / (1000*60*60*24*7)) ;
		var DiffMonths = parseInt(ageDate / (1000*60*60*24*30)) ;
		var DiffYears = parseInt(ageDate / (1000*60*60*24*365)) ;
		if (DiffDays < 14) textDate = DiffDays+' '+translation2;
		if (DiffWeeks >= 2) textDate = DiffWeeks+' '+translation3;
		if (DiffWeeks >= 8) textDate = DiffMonths+' '+translation4;
		if (DiffMonths >= 13) textDate = DiffYears+' '+translation5;
		
		//alert ('Best Date: '+sourceDate+'  Now is: '+todayDate.getHours()+':'+todayDate.getMinutes()+' Diff in minutes: ' + ageDate / (1000*60)  );
		//alert ('Days: '+DiffDays+'   Weeks: '+DiffWeeks+'    Months: '+DiffMonths+'   Years: '+DiffYears+'   ');
		//alert (textDate);		
		var updated = DiffWeeks;
		if (updated > 1) 
			{
			var translation = '';

		if(initial_language == 'th'){
		translation = 'Actualizar';
		}else if(initial_language == 'en'){
		translation = 'Update';
		}
		
				contentTime = 'Summary is '+textDate+' old'; 
				iconTime = '<button id="UpdateSumm" class="find_doctor_button" style="float:right; display: block; background-color: rgb(82, 216, 89); width:80px; margin-top:-3px;">'+translation+'</button>';
			}
		else
			{
			var translation = '';

		if(initial_language == 'th'){
		translation = 'Actualizado';
		}else if(initial_language == 'en'){
		translation = 'Updated';
		}
				contentTime = translation;
				iconTime = '<i class="icon-check icon-2x" style="float:left;"></i>';
			}
		if (DiffYears > 4) 
			{
			var translation = '';
			var translation2 = '';

		if(initial_language == 'th'){
		translation = 'Actualizar';
		translation2 = 'Resumen nunca actualizado';
		}else if(initial_language == 'en'){
		translation = 'Update';
		translation2 = 'Summary never updated';
		}
		
				contentTime = translation2; 
				iconTime = '<i class="icon-exclamation icon-2x" style="float:left; color:rgb(216, 72, 64);"></i><button id="UpdateSumm" class="find_doctor_button" style="float:right; display: block; background-color: rgb(82, 216, 89); width:80px; margin-top:-3px;">'+translation+'</button>';
			}
		
		
		var verified = -1;
		var bestVerified = -1;
		var bestDate = Date.parse('1975-01-01 00:00:00');		
		var VerifiedDate = bestDate;
		var title = '';
			
		var k = 0;
		while (k < 5)
		{
			thisVerified = ConnData[k].doctor_signed;
			thisDate = Date.parse(ConnData[k].latest_update);
			console.log(' K='+k+'   Doctor:'+k+'  Date:'+k+'');
			if (thisVerified > -1 && (thisDate >= bestDate) ) 
				{ 
					bestDate = thisDate;	
					bestVerified = ConnData[k].doctor_signed; 
					VerifiedDate = ConnData[k].latest_update; 
				}		
			k++;
		}

        //ribbonText = '';
		if (bestVerified > -1)
		{
		var translation = '';
		var translation2 = '';
		var translation3 = '';

		if(initial_language == 'th'){
		translation = 'Verificar';
		tranlation2 = 'Verificado por';
		translation3 = 'en';
		}else if(initial_language == 'en'){
		translation = 'Verify';
		tranlation2 = 'Verified by';
		translation3 = 'on';
		}
			namedoctor = LanzaAjax ('/getDoctorName.php?IdDoctor='+bestVerified);
			contentVerif = translation;// by '+namedoctor;
            var offset = new Date().getTimezoneOffset();
            var formattedDate = moment(VerifiedDate).add('minutes',(offset*-1)).fromNow();
			title =  translation2+' '+namedoctor+' '+ formattedDate;
			iconVerif = '<i class="icon-check icon-2x"></i>';				
		}
		else
		{
			
			var translation = '';

		if(initial_language == 'th'){
		translation = 'Verificar';
		contentVerif = 'No Verificado'; 
		}else if(initial_language == 'en'){
		translation = 'Verify';
		contentVerif = 'Not Verified'; 
		}
			iconVerif = '<i class="icon-exclamation icon-2x" style="float:left; color:rgb(216, 72, 64);"></i><button id="VerifyDoctor" class="find_doctor_button" style="float:right; display: block; background-color: rgb(34, 174, 255); width:80px; margin-top:-3px;">'+translation+'</button>';			
		}
		
		var complete = 0;
		var segments = 0;
		var iconComplet = '<i class="icon-check icon-2x"></i>';
		var k = 0;
		while (k < 5)
		{
			complete += parseInt(ConnData[k].Data);
			if (ConnData[k].Data > 0 ) segments++;
			k++;
		}
		if (segments < 4) iconComplet = '<i class="icon-exclamation icon-2x" style="float:left; color:rgb(216, 72, 64);"></i>';			

			var translation = '';

		if(initial_language == 'th'){
		translation = 'eventos registrados';
		}else if(initial_language == 'en'){
		translation = 'events registered';
		}
        
 	    AdminData = ConnData[0].Data;
	    PastDx = ConnData[1].Data;
	    Medications = ConnData[2].Data;
	    Immuno = ConnData[3].Data;
	    Family = ConnData[4].Data;
	    Habits = ConnData[5].Data;
	    Allergies = ConnData[6].Data;
                           
        contentAlt += '	               <style>';
        contentAlt += '	                div.SumBox{';
        contentAlt += '	                    float:left; ';
        contentAlt += '	                    position: absolute; ';
        contentAlt += '	                    z-index: 10; ';
        contentAlt += '	                    box-shadow: inset 0px 0px 2px 0px whitesmoke;';
        contentAlt += '	                }';
        contentAlt += '	                div.SumBox:hover {         ';
        contentAlt += '	                    opacity: 0.8;                ';
        contentAlt += '	                }                                ';
        contentAlt += '	                ';
        contentAlt += '	                img.SumBoxIcon{';
        contentAlt += '	                    position:absolute;';
        contentAlt += '	                    width:30px;';
        contentAlt += '	                    height:30px;';
        contentAlt += '	                    top: 50%;';
        contentAlt += '	                    left: 50%;';
        contentAlt += '	                    transform: translate(-50%, -50%);';
        contentAlt += '	                    font-size: 20px;';
        contentAlt += '	                    color: grey;';
        contentAlt += '	                    z-index:460;';
        contentAlt += '	                    /*';
        contentAlt += '	                    border:1px solid #cacaca;';
        contentAlt += '	                    border-radius:10px;';
        contentAlt += '	                    */';
        contentAlt += '	                }';
        contentAlt += '	';
        contentAlt += '	                div.BannerIcon{     ';
        contentAlt += '	                    border: 1px solid #cacaca;       ';
        contentAlt += '	                    width: 15px;       ';
        contentAlt += '	                    height: 15px;      ';
        contentAlt += '	                    border-radius: 10px;       ';
        contentAlt += '	                    position: absolute;        ';
        contentAlt += '	                    left: 50%;       ';
        contentAlt += '	                    top: 50%;         ';
        contentAlt += '	                    z-index: 440;          ';
        contentAlt += '	                    margin-left: 8px;      ';
        contentAlt += '	                    margin-top: -18px;     ';
        contentAlt += '	                    color: #cacaca;     ';
        contentAlt += '	                    font-size: 12px;';
        contentAlt += '	                    text-align: center;     ';
        contentAlt += '	                    font-weight: bold;     ';
        contentAlt += '	                    line-height: 15px;     ';
        contentAlt += '	                }';

		//THIS DETERMINES IF USER IS MAC OR WINDOWS
var mactest=navigator.userAgent.indexOf("Mac")!=-1;

if (mactest)
{
//alert('mac');
var box1 = 'top:50%;left:45%;';
var box2 = 'top:50%;left:45%;';
var box3 = 'top:43%;left:48%;';
var box4 = 'top:43%;left:43%;';
var box5 = 'top:45%;left:45%;';
var box6 = 'top:48%;left:45%;';
var box7 = 'top:45%;left:40%;';
} else {
var box1 = 'top:40%;left:35%;';
var box2 = 'top:40%;left:40%;';
var box3 = 'top:30%;left:35%;';
var box4 = 'top:30%;left:32%;';
var box5 = 'top:30%;left:27%;';
var box6 = 'top:25%;left:35%;';
var box7 = 'top:25%;left:25%;';
}

        contentAlt += '	                    ';
        contentAlt += '	                </style>  ';
        contentAlt += '	                <div style="background-color:white; z-index:999; position:absolute; left:0%; top:0%; width:100%; height:10px;"></div>  ';
        contentAlt += '	                <div style="background-color:white; z-index:999; position:absolute; left:320px; top:0%; width:50px; height:100%;"></div>  ';
        
        contentAlt += '	                <div id="SumGraph2" style="width: 275px; height: 165px; margin-top: 10px; margin-left: 45px; border:0px solid #cacaca; position:relative; cursor: pointer; border-radius:10px;">';
        contentAlt += '	                  ';
        contentAlt += '	                    <div class="SumBox" style="width:45%; height:45%; top:0%; left:0%; background-color:#54bc00; border-top-left-radius:10px;">';
        contentAlt += '	                       <img style="'+box1+'" class="SumBoxIcon" src="images/icons/Adminx2_svg.png" />';
        if (AdminData > 0)      contentAlt += '	                       <div class="BannerIcon" >'+AdminData+'</div>';
        contentAlt += '	                    </div>        ';                              
        contentAlt += '	                    <div class="SumBox" style="width:55%; height:45%; top:0%; left:45%; background-color:#2C3E50;  border-top-right-radius:10px;">';
        contentAlt += '	                        <img style="'+box2+'" class="SumBoxIcon" src="images/icons/historyx2_svg.png" />';
        if (PastDx > 0)         contentAlt += '	                        <div class="BannerIcon" >'+PastDx+'</div>';
        contentAlt += '	                    </div>     ';       
        contentAlt += '	                    <div class="SumBox" style="width:30%; height:30%; top:45%; left:0%; background-color:#18BC9C;">';
        contentAlt += '	                        <img style="'+box3+'" class="SumBoxIcon" src="images/icons/medicationx2_svg.png" />';
        if (Medications > 0)     contentAlt += '	                        <div class="BannerIcon" >'+Medications+'</div>';
        contentAlt += '	                   </div>  ';                 
        contentAlt += '	                    <div class="SumBox" style="width:40%; height:30%; top:45%; left:30%; background-color:#E74C3C;">';
        contentAlt += '	                        <img style="'+box4+'" class="SumBoxIcon" src="images/icons/familyx2_svg.png" />';
        if (Family > 0)          contentAlt += '	                        <div class="BannerIcon" >'+Family+'</div>';
        contentAlt += '	                  </div>         ';   
        //contentAlt += '	                        <div style="width:calc(40% - 3px); height:calc(30% - 3px); top:calc(45% + 1px); left:calc(30% + 1px); border:1px solid white; position:absolute; z-index:490;"></div>';
        contentAlt += '	                    <div class="SumBox" style="width:30%; height:55%; top:45%; left:70%; background-color:#3498DB;  border-bottom-right-radius:10px;">';
        contentAlt += '	                       <img style="'+box5+'" class="SumBoxIcon" src="images/icons/habitsx2_svg.png" />';
        if (Habits > 0)         contentAlt += '	                        <div class="BannerIcon" >'+Habits+'</div>';
        contentAlt += '	                   </div>            ';
        //contentAlt += '	                        <div style="width:calc(30% - 3px); height:calc(55% - 3px); top:calc(45% + 1px); left:calc(70% + 1px); border:1px solid white; position:absolute; z-index:490;  border-bottom-right-radius: 10px;"></div>';
        contentAlt += '	                    <div class="SumBox" style="width:50%; height:25%; top:75%; left:0%; background-color:#F39C12;  border-bottom-left-radius:10px;">';
        contentAlt += '	                        <img style="'+box6+'" class="SumBoxIcon" src="images/icons/immunox2_svg.png" />';
        if (Immuno > 0)         contentAlt += '	                        <div class="BannerIcon" >'+Immuno+'</div>';
        contentAlt += '	                  </div>    ';        
        contentAlt += '	                    <div class="SumBox" style="width:20%; height:25%; top:75%; left:50%; background-color:#00FFFF;">';
        contentAlt += '	                        <img style="'+box7+'" class="SumBoxIcon" src="images/icons/allergyx2_svg.png" />';
        if (Allergies > 0)      contentAlt += '	                        <div class="BannerIcon" >'+Allergies+'</div>';
        contentAlt += '	                   </div>      ';         
        if (bestVerified > -1){
        contentAlt += '	                    <div class="ribbon-banner" id="ribbon-verified" href="#" style="display: block; width:220px; right:-63px; top:22px; background-color:#DB3469; text-align:center;"><span class="ribbon-lgtext">Verified</span><br> <span class="ribbon-smtext" style="font-size:8px;">'+title+'<span class="ribbon-lgtext"></span></span></div>';
        };
        contentAlt += '	';
        contentAlt += '	                  </div> ';
                          

		/*
		contentAlt += '				             	<div style="float:left; height:45px; width:100%;"></div>';
		contentAlt += '			             		<div style="float:left; height:125px; width:100%; ">';
		contentAlt += '			             		<div style="float:left; height:125px; width:; border:0px solid #cacaca;">';
		contentAlt += '								<div style="float:left; height:40px; width:"> <!-- ROW 1 -->';
		contentAlt += '									<div style="float:left; background-color:#22aeff; width:30px; height:30px; border-radius:15px; font-size:14px; color:white;"><p style="margin-top:5px; margin-left:10px;">A</p></div>';					             	 
		contentAlt += '									<div style="float:left; width:180px; height:30px; font-size:16px; color:#22aeff; margin-left:20px; margin-top:4px;" title="'+titleU+'" lang="en"> '+contentTime+'</div>	';				             	
		contentAlt += '									<div style="float:left; width:100px; height:30px; font-size:12px; color:#54bc00; margin-left:0px; margin-top:2px;">'+iconTime+'</div>';
		contentAlt += '								</div>	';
		contentAlt += '								<div style="float:left; height:40px; width:100%;"> <!-- ROW 2 -->';
		contentAlt += '									<div style="float:left; background-color:#22aeff; width:30px; height:30px; border-radius:15px; font-size:14px; color:white;"><p style="margin-top:5px; margin-left:10px;">B</p></div>';					             	 
		contentAlt += '									<div style="float:left; width:180px; height:30px; font-size:16px; color:#22aeff; margin-left:20px; margin-top:4px;" title="'+title+'" lang="en"> '+contentVerif+'</div>		';			             	
		contentAlt += '									<div style="float:left; width:100px; height:30px; font-size:12px; color:#54bc00; margin-left:0px; margin-top:2px;">'+iconVerif+'</div>';	             
		contentAlt += '								</div>';	
		contentAlt += '								<div style="float:left; height:40px; width:100%;"> <!-- ROW 3 -->';
		contentAlt += '									<div style="float:left; background-color:#22aeff; width:30px; height:30px; border-radius:15px; font-size:14px; color:white;"><p style="margin-top:5px; margin-left:10px;">C</p></div>';					             	 
		contentAlt += '									<div style="float:left; width:180px; height:30px; font-size:16px; color:#22aeff; margin-left:20px; margin-top:4px;" title="'+title+'"> '+complete+' <span lang="en">'+translation+'</span></div>		';			             	
		contentAlt += '									<div style="float:left; width:100px; height:30px; font-size:12px; color:#54bc00; margin-left:0px; margin-top:2px;">'+iconComplet+'</div>';	             
		contentAlt += '								</div>';	
		contentAlt += '			             	</div>';
		contentAlt += '			             	</div>';
		contentAlt += '			             	<div style="float:left; height:10px; width:100%;"></div>';
*/
		
		$('#ALTCanvas1').html(contentAlt);
		$('#ALTCanvas1').animate({"height":"200px"}, 1000);
		$('#PHSLabel').css('background-color','rgb(82, 216, 89)');
		Canvas1State = 1;
	}else
	{
		$('#PHSLabel').html('See Status');
		$('#PHSLabel').css('background-color','rgb(74, 134, 54)');
		$('#ALTCanvas1').animate({"height":"0px"}, 1000);
		Canvas1State = 0;
// rgb(82, 216, 89); // see graph
//		rgb(74, 134, 54);
	}
	
	});
    

	/*$('#myCanvas').live('click',function(){
		var myClass = $('#USERID').val();
		window.location.replace('medicalPassport.php?IdUsu='+myClass);
	});*/
        
    $('#myCanvas,#ALTCanvas1').live('click',function(){
		var myClass = $('#USERID').val();
        $("#summary_modal").html('<iframe src="medicalPassport.php?modal=1&IdUsu='+myClass+'" scrolling="no" style="width:1000px;height:690px; margin: 0px; border: 0px solid #FFF; outline: 0px; padding: 0px; overflow: hidden;"></iframe>');
        $("#summary_modal").dialog({bigframe: true, width: 1050, height: 690, resize: false, modal: true});
		//window.location.replace('medicalPassport.php?IdUsu='+myClass);
	});

    $('#editPatient').live('click',function(){
        var userID = $('#USERID').val();
		window.location.replace('patientdetailMED-new.php?IdUsu='+userID); // Changed the url value from patientdetailMED-newUSER.php to patientdetailMED-new.php
    });        
        
	$('#ButtonReview').live('click',function(){
		var myClass = $('#USERID').val();

        $("#summary_modal").html('<iframe src="medicalPassport.php?modal=1&IdUsu='+myClass+'" width="1000" height="660" scrolling="no" style="width:1000px;height:660px; margin: 0px; border: 0px solid #FFF; outline: 0px; padding: 0px; overflow: hidden;"></iframe>');
        $("#summary_modal").dialog({bigframe: true, width: 1050, height: 690, resize: false, modal: true}).onclose(function(data,success)
           {
             $.post("SavePatientSummaryAsPDF.php",{IdUsu: <?php echo $UserID; ?>},function(data,success)
               {
              });
           
           }); // Onclosing of the dialog frame the patient updated data is database and a pdf is generated

		//window.location.replace('medicalPassport.php?IdUsu='+myClass);
	});

	$('#UpdateSumm').live('click',function(){
		var myClass = $('#USERID').val();
		window.location.replace('medicalPassport.php?IdUsu='+myClass);
	});

	$('#VerifyDoctor').live('click',function(){
		 //stopPropagation();
		 $("#Talk").trigger("click");
	});

	$('#myCanvas').mousemove(function(e) {
	    var pos = findPos(this);
	    var x = e.pageX - pos.x;
	    var y = e.pageY - pos.y;
	    var coord = "x=" + x + ", y=" + y;
	    var c = this.getContext('2d');
	    var p = c.getImageData(x, y, 1, 1).data; 
	    var hex = "#" + ("000000" + rgbToHex(p[0], p[1], p[2])).slice(-6);
	    $('#status').html(coord + "<br>" + hex + " - ");
	});

	$('#myCanvas2').live('click',function(){
		var myClass = $('#USERID').val();
		window.location.replace('patientdetailMED-new.php?IdUsu='+myClass);
	});


    $(window).bind('load', function(){
        $('#newinbox').trigger('click');
    });
 
	$('#BotMessages').live('click',function(){
        $('#compose_message').trigger('click');
	});
	
	$('#newinbox').live('click',function(){
       //alert('trigger');
	   var queUrl ='<?php echo $domain;?>/getInboxMessageUSER.php?IdMED=<?php echo $MEDID;?>&patient=<?php echo $UserID;?>';
	   //alert (queUrl);
       $('#datatable_3').load(queUrl);
	   $('#datatable_3').trigger('update');
           
   });
   
    $('#newoutbox').live('click',function(){
      //alert('trigger');
	   var queUrl ='<?php echo $domain;?>/getOutboxMessageUSER.php?IdMED=<?php echo $MEDID;?>&patient=<?php echo $UserID;?>';
	   $('#datatable_4').load(queUrl);
	   $('#datatable_4').trigger('update');
           
   });
    
    $('#SearchDirectory').live('click', function()
    {		
		var Codes = GetICD10Code('Append');
		var longit = Object.keys(Codes).length;	   	   
		//alert ('Retrieved '+longit+' items. Example at [0].description: '+Codes[0].description);
		
	});
    $("#setup_phone").intlTelInput();
    $.post("get_user_info.php", {id: $("#USERID").val()}, function(data, status)
    {
        var info = JSON.parse(data);
        $("#timezone_picker option[value='" + info['timezone'] + "']").attr('selected', 'selected');
        if(info.hasOwnProperty('location') && info['location'].length > 0)
        {
            setTimeout(function(){
                $("#country_setup").val(info['location']);
                $("#country_setup").change();
            }, 800);
        }
        if(info.hasOwnProperty('location2') && info['location2'].length > 0)
        {
            setTimeout(function(){
                $("#state_setup").val(info['location2']);
                $("#state_setup").change();
            }, 900);
        }
        if(info.hasOwnProperty('email') && info['email'].length > 0)
        {
            $("#setup_email").val(info['email']);
        }
        if(info.hasOwnProperty('phone') && info['phone'].length > 0)
        {
            $("#setup_phone").val("+" + info['phone']);
        }
    });
    
    $('#timezone_picker').on('change', function()
    {
        console.log('clicked');
    });
        
        
    $('#MedHistory').live('click',function(){
		var myClass = $('#USERID').val();
        $("#summary_modal").html('<iframe src="medicalPassport.php?modal=1&IdUsu='+myClass+'" width="1000" height="660" scrolling="no" style="width:1000px;height:660px; margin: 0px; border: 0px solid #FFF; outline: 0px; padding: 0px; overflow: hidden;"></iframe>');
        $("#summary_modal").dialog({bigframe: true, width: 1050, height: 690, resize: false, modal: true});
		//window.location.replace('medicalPassport.php?IdUsu='+myClass);
	});       
        
    $('#timezone_button').on('click', function()
    {
        $.post("update_user.php", {id: $("#USERID").val(), timezone: $("#timezone_picker").val().toString()}, function(data, status){});
        $("#setup_modal_notification").css("background-color", "#52D859");
        $("#setup_modal_notification").html('<p style="color: #fff;">Timezone Changed Successfully!</p>');
        $("#setup_modal_notification_container").animate({opacity: '1.0'}, {duration: 1000, easing: 'easeInOutQuad', complete: function() {
            setTimeout(function(){$("#setup_modal_notification_container").animate({opacity: '0.0'}, {duration: 1000, easing: 'easeInOutQuad', complete: function() {}});}, 2000);
        }});
        timezone_changed = $("#timezone_picker").val();
    });    
    $('#location_button').on('click', function()
    {
        $.post("update_user.php", {id: $("#USERID").val(), location: $("#country_setup").val(), location2: $("#state_setup").val()}, function(data, status){});
        $("#setup_modal_notification").css("background-color", "#52D859");
        $("#setup_modal_notification").html('<p style="color: #fff;">Location Changed Successfully!</p>');
        $("#setup_modal_notification_container").animate({opacity: '1.0'}, {duration: 1000, easing: 'easeInOutQuad', complete: function() {
            setTimeout(function(){$("#setup_modal_notification_container").animate({opacity: '0.0'}, {duration: 1000, easing: 'easeInOutQuad', complete: function() {}});}, 2000);
        }});
        country_changed = $("#country_setup").val();
        state_changed = $("#state_setup").val();
        
    });
    $('#contact_button').on('click', function()
    {
        $.post("update_user.php", {id: $("#USERID").val(), email: $("#setup_email").val(), phone: $("#setup_phone").val()}, function(data, status)
        {
            console.log(data);
            if(data.length > 0)
            {
                $("#setup_modal_notification").css("background-color", "#D5483A");
                $("#setup_modal_notification").html('<p style="color: #fff;">'+data+'</p>');
                $("#setup_modal_notification_container").animate({opacity: '1.0'}, {duration: 1000, easing: 'easeInOutQuad', complete: function() {
                    setTimeout(function(){$("#setup_modal_notification_container").animate({opacity: '0.0'}, {duration: 1000, easing: 'easeInOutQuad', complete: function() {}});}, 2000);
                }});
            }
            else
            {
                $("#setup_modal_notification").css("background-color", "#52D859");
                $("#setup_modal_notification").html('<p style="color: #fff;">Contact Information Changed Successfully!</p>');
                $("#setup_modal_notification_container").animate({opacity: '1.0'}, {duration: 1000, easing: 'easeInOutQuad', complete: function() {
                    setTimeout(function(){$("#setup_modal_notification_container").animate({opacity: '0.0'}, {duration: 1000, easing: 'easeInOutQuad', complete: function() {}});}, 2000);
                }});
            }
        });
        
    });
        
    $('button[id^="setup_menu_"]').on('click', function()
    {
        $('button[id^="setup_menu_"]').each(function(index)
        {
            $(this).css('background-color', '#FBFBFB');
            $(this).css('color', '#22AEFF');
            $(this).css('border', '1px solid #E6E6E6');
        });
        $('div[id^="setup_page_"]').each(function(index)
        {
            $(this).css('display', 'none');
        });
        var this_id = $(this).attr('id').split('_')[2];
        
        $(this).css('background-color', '#22AEFF');
        $(this).css('color', '#FFF');
        $(this).css('border', '0px solid #E6E6E6');
        $("#setup_page_"+this_id).css('display', 'block');
        if(this_id == '1')
        {
            // password
            $('#setup_title').text("Change Password");
        }
        else if(this_id == '2')
        {
            // timezone
            $('#setup_title').text("Set Timezone");
        }
        else if(this_id == '3')
        {
            // credit card
            $('#setup_title').text("Manage Credit Cards");
        }
        else if(this_id == '4')
        {
            // location
            $('#setup_title').text("Set Location");
        }
        else if(this_id == '5')
        {
            // location
            $('#setup_title').text("Set Contact Information");
        }
        else if(this_id == '6')
        {
            // subscriptions
            $('#setup_title').text("Subscription Management");
        }
    });
        
    var state_changed = "";
    var country_changed = "";
    var timezone_changed = "";    
    $('#SetUp').live('click', function()
    {
        

        
        $("#change_password_validated_section").css("display", "none");
        $("#pw1").val("");
        $("#pw2").val("");
        $("#pw3").val("");
        $("#setup_modal_notification_container").css("margin-top", "0px");
        $("#setup_modal").dialog({bigframe: true, width: 650, height: 470, resize: false, modal: true});
        
        
        

        var country = "";//geoplugin_countryName();
        var region ="";
        if (country == "United States") {
            country = "USA";
        }   

        var place = "<?php echo $Place;?>";
        
        location_array = place.split(",");
        if (location_array.length ==2) {
            region = location_array[0].trim();
            country = location_array[1].trim();


        } else if ((location_array.length == 1)&& (location != "")) {
            
            country = place.trim();
        }   
        
        //if the country has already been changed
        if (country_changed != "") {
            country = country_changed;   
        }    
        if (country_changed != "") {
            region = state_changed;   
        }    
        
        $("#country_setup").val(country);			 
	    $("#country_setup").change();
        
        $("#state_setup").val(region);			 
	    $("#state_setup").change();
        
        var timezone = "<?php echo $Timezone;?>";
        
        timezone_array = timezone.split(":");
        timezone_format = timezone_array[0];
        timezone_format=parseInt(timezone_format, 10)+".0";

        if (timezone_changed != "") {
            timezone_format = timezone_changed;   
        }    
        $("#timezone_picker").val(timezone_format);
        $("#timezone_picker").change();
        
        
    });
        
    $("#change_password_validate_button").live('click', function()
    {
        $.post("validate_password.php", {pat_id: $("#USERID").val(), pw: $("#pw1").val()}, function(data, status)
        {
            if(data == '1' && $("#change_password_validated_section").css("display") == 'none')
            {
                $("#change_password_validated_section").slideDown();
                
            }
            else
            {
                $("#setup_modal_notification").css("background-color", "#D5483A");
                $("#setup_modal_notification").html('<p style="color: #fff;">Password Incorrect</p>');
                $("#setup_modal_notification_container").animate({opacity: '1.0'}, {duration: 1000, easing: 'easeInOutQuad', complete: function() {
                    setTimeout(function(){$("#setup_modal_notification_container").animate({opacity: '0.0'}, {duration: 1000, easing: 'easeInOutQuad', complete: function() {}});}, 2000);
                }});
            }
        });

    });
    $("#change_password_finish_button").live('click', function()
    {
        if($("#pw2").val() == $("#pw3").val())
        {
            $("#pw1").val("");
            $("#change_password_validated_section").slideUp();
            $.post("update_patient.php", {new_pw: $("#pw2").val(), pat_id: $("#USERID").val()}, function(data, status)
            {
                $("#pw2").val("");
                $("#pw3").val("");
                $("#setup_modal_notification").css("background-color", "#52D859");
                $("#setup_modal_notification").html('<p style="color: #fff;">Password Changed Successfully!</p>');
                $("#setup_modal_notification_container").animate({opacity: '1.0'}, {duration: 1000, easing: 'easeInOutQuad', complete: function() {
                    setTimeout(function(){$("#setup_modal_notification_container").animate({opacity: '0.0'}, {duration: 1000, easing: 'easeInOutQuad', complete: function() {}});}, 2000);
                }});
            });
        }
        else
        {
            $("#setup_modal_notification").css("background-color", "#D5483A");
            $("#setup_modal_notification").html('<p style="color: #fff;">Passwords Did Not Match</p>');
            $("#setup_modal_notification_container").animate({opacity: '1.0'}, {duration: 1000, easing: 'easeInOutQuad', complete: function() {
                setTimeout(function(){$("#setup_modal_notification_container").animate({opacity: '0.0'}, {duration: 1000, easing: 'easeInOutQuad', complete: function() {}});}, 2000);
                }});
        }
    });

    var doctor_to_connect = '';
    var type_of_doctor_to_find = '';
    var find_doctor_page = 0;
    var selected_doctor_available = 0;
    var selected_doctor_info = '';
    var time_selected = -1;
    var day_selected= -1;
    var date_selected = '';
    var consultation_type = 2;
    var zones = null;
    var selected_timezone = "00:00:00";
        
    $("#country").on('change', function()
    {
        // the following code is to show the region select menu if the country is USA.
        if($(this).val() == 'USA')
        {
            $("#state").parent().parent().css('display', 'block');
        }
        else
        {
            $("#state").parent().parent().css('display', 'none');
        }
    });
    $("#country_setup").on('change', function()
    {
        // the following code is to show the region select menu if the country is USA.
        if($(this).val() == 'USA')
        {
            $("#state_setup").parent().parent().css('visibility', 'visible');
        }
        else
        {
            $("#state_setup").parent().parent().css('visibility', 'hidden');
        }
    });  
        
    $('#Talk').live('click', function()
    {	
        doctor_to_connect = '';
        type_of_doctor_to_find = '';
        $('.recent_doctor_button_selected').attr("class", "recent_doctor_button");
        $("#Talk_Section_1").css("display", "block");
        $("#Talk_Section_2").css("display", "none");
        $("#Talk_Section_3").css("display", "none");
        $("#Talk_Section_4").css("display", "none");
        /*var h = ($("#recent_doctors").children().length - 1) * 35;
        for(var k = 0; k < $("#recent_doctors").children().length; k++)
        {
            if(k == 0 && $("#recent_doctors").children().length == 1)
            {
                $("#recent_doctors").children().eq(k).css("border-top-left-radius", "");
                $("#recent_doctors").children().eq(k).css("border-top-right-radius", "");
                $("#recent_doctors").children().eq(k).css("border-bottom-left-radius", "");
                $("#recent_doctors").children().eq(k).css("border-bottom-right-radius", "");
                $("#recent_doctors").children().eq(k).css("border-radius", "10px");
            }
            else if(k == 0 && $("#recent_doctors").children().length > 1)
            {
                $("#recent_doctors").children().eq(k).css("border-radius", "");
                $("#recent_doctors").children().eq(k).css("border-top-left-radius", "10px");
                $("#recent_doctors").children().eq(k).css("border-top-right-radius", "10px");
                $("#recent_doctors").children().eq(k).css("border-bottom-left-radius", "0px");
                $("#recent_doctors").children().eq(k).css("border-bottom-right-radius", "0px");
            }
            else if(k > 0 && k < $("#recent_doctors").children().length - 1)
            {
                $("#recent_doctors").children().eq(k).css("border-radius", "");
                $("#recent_doctors").children().eq(k).css("border-top-left-radius", "");
                $("#recent_doctors").children().eq(k).css("border-top-right-radius", "");
                $("#recent_doctors").children().eq(k).css("border-bottom-left-radius", "");
                $("#recent_doctors").children().eq(k).css("border-bottom-right-radius", "");
            }
            else if(k == $("#recent_doctors").children().length - 1)
            {
                $("#recent_doctors").children().eq(k).css("border-radius", "");
                $("#recent_doctors").children().eq(k).css("border-top-left-radius", "");
                $("#recent_doctors").children().eq(k).css("border-top-right-radius", "");
                $("#recent_doctors").children().eq(k).css("border-bottom-left-radius", "10px");
                $("#recent_doctors").children().eq(k).css("border-bottom-right-radius", "10px");
            }
        }
        $("#recent_doctors_section").css("display", "block");
        if($("#recent_doctors").children().length == 0)
        {
            h = -90;
            $("#recent_doctors_section").css("display", "none");
        }*/
        $("#find_doctor_modal").dialog({bgiframe: true, width: 550, height: 413, resize: false, modal: true});
        $("#step_bar_1").attr("class", "step_bar");
        $("#step_circle_1").attr("class", "step_circle lit");
        $("#step_circle_2").attr("class", "step_circle");
        $("#step_bar_2").attr("class", "step_bar");
        $("#step_circle_3").attr("class", "step_circle");
        $("#step_bar_3").attr("class", "step_bar");
        $("#step_circle_4").attr("class", "step_circle");
        $("#step_bar_4").attr("class", "step_bar");
        $("#step_circle_5").attr("class", "step_circle");
        $("#step_bar_5").attr("class", "step_bar");
        $("#step_circle_6").attr("class", "step_circle");
        $("#find_doctor_label").text("");
        $("#find_doctor_next_button").css("display", "block");
        $("#find_doctor_previous_button").css("display", "block");
        $("#find_doctor_cancel_button").css("display", "block");
        $("#find_doctor_close_button").css("display", "none");
        $('#find_doctor_my_doctors_1').css("display", "none");
        $('#find_doctor_my_doctors_2').css("display", "none");
        $('#find_doctor_appointment_1').css("display", "none");
        $('#find_doctor_appointment_2').css("display", "none");
        $('#find_doctor_appointment_3').css("display", "none");
        $('#find_doctor_time').css("display", "none");
        $('#find_doctor_receipt').css("display", "none");
        $('#find_doctor_confirmation').css("display", "none");
        $('#time_selector_1').css("display", "none");
        $('#day_selector_1').css("display", "none");
        $('#find_doctor_main').css("display", "block");
        find_doctor_page = 0;
        
    });
        
    function getDay(i)
    {
        if(i == 1)
        {
            return "Sunday";
        }
        else if(i == 2)
        {
            return "Monday";
        }
        else if(i == 3)
        {
            return "Tuesday";
        }
        else if(i == 4)
        {
            return "Wednesday";
        }
        else if(i == 5)
        {
            return "Thursday";
        }
        else if(i == 6)
        {
            return "Friday";
        }
        else if(i == 7)
        {
            return "Saturday";
        }
    }
    function getSlot(i)
    {
        if(i == 1)
        {
            return "8:00 AM and 10:00 AM";
        }
        else if(i == 2)
        {
            return "10:00 AM and 12:00 PM";
        }
        else if(i == 3)
        {
            return "12:00 PM and 2:00 PM";
        }
        else if(i == 4)
        {
            return "2:00 PM and 4:00 PM";
        }
        else if(i == 5)
        {
            return "4:00 PM and 6:00 PM";
        }
        else if(i == 6)
        {
            return "6:00 PM and 8:00 PM";
        }
        else if(i == 7)
        {
            return "8:00 PM and 10:00 PM";
        }
    }
    function getSlotStartTime(i)
    {
        if(i == 1)
        {
            return "08:00:00";
        }
        else if(i == 2)
        {
            return "10:00:00";
        }
        else if(i == 3)
        {
            return "12:00:00";
        }
        else if(i == 4)
        {
            return "14:00:00";
        }
        else if(i == 5)
        {
            return "16:00:00";
        }
        else if(i == 6)
        {
            return "18:00:00";
        }
        else if(i == 7)
        {
            return "20:00:00";
        }
    }
    function getSlotEndTime(i)
    {
        if(i == 1)
        {
            return "10:00:00";
        }
        else if(i == 2)
        {
            return "12:00:00";
        }
        else if(i == 3)
        {
            return "14:00:00";
        }
        else if(i == 4)
        {
            return "16:00:00";
        }
        else if(i == 5)
        {
            return "18:00:00";
        }
        else if(i == 6)
        {
            return "20:00:00";
        }
        else if(i == 7)
        {
            return "22:00:00";
        }
    }
    function resetDateTimeSelector()
    {
        $("#sun").removeClass("day_selected");
        $("#mon").removeClass("day_selected");
        $("#tues").removeClass("day_selected");
        $("#wed").removeClass("day_selected");
        $("#thur").removeClass("day_selected");
        $("#fri").removeClass("day_selected");
        $("#sat").removeClass("day_selected");

        $("#8_10_am").removeClass("slot_selected");
        $("#10_12").removeClass("slot_selected");
        $("#12_2").removeClass("slot_selected");
        $("#2_4").removeClass("slot_selected");
        $("#4_6").removeClass("slot_selected");
        $("#6_8").removeClass("slot_selected");
        $("#8_10_pm").removeClass("slot_selected");
        
        time_selected = -1;
        day_selected= -1;
        date_selected = '';
        $("#day_selector_1").css("display", "none");
        $("#time_selector_1").css("display", "none");
    }
    $("#find_doctor_next_button").live('click', function()
    {
        console.log(find_doctor_page);
        // commented out to skip step 2 for Llama al Doctor
        /*if(find_doctor_page == 30 || find_doctor_page == 10) 
        {
            if($("#country").val() != "-1" && $("#country").val().length > 0)
            {
                if(find_doctor_page == 30)
                {
                    find_doctor_page = 31;
                }
                else
                {
                    find_doctor_page = 11;
                }
                $("#find_doctor_appointment_1").fadeOut(300, function(){$("#find_doctor_appointment_2").fadeIn(300)});
                
                $("#step_bar_1").attr("class", "step_bar lit");
                $("#step_circle_1").attr("class", "step_circle lit");
                $("#step_circle_2").attr("class", "step_circle lit");
                $("#step_bar_2").attr("class", "step_bar lit");
                $("#step_circle_3").attr("class", "step_circle lit");
                $("#find_doctor_label").text("Select Speciality");
            }
        }
        else */if(find_doctor_page == 30 || find_doctor_page == 21 || find_doctor_page == 10)
        {
            $("#step_bar_1").attr("class", "step_bar lit");
            $("#step_circle_1").attr("class", "step_circle lit");
            $("#step_circle_2").attr("class", "step_circle lit");
            $("#step_bar_2").attr("class", "step_bar lit");
            $("#step_circle_3").attr("class", "step_circle lit");
            $("#step_bar_3").attr("class", "step_bar lit");
            $("#step_circle_4").attr("class", "step_circle lit");
            $("#find_doctor_label").text("Select Time");
            if(find_doctor_page == 30 || find_doctor_page == 10)
            {
                
                // find a doctor
                if(find_doctor_page == 10 || find_doctor_page == 30)
                {
                    var speciality = "General Practice";
                    if(find_doctor_page != 10 && find_doctor_page != 30 && $("#speciality").val() != null)
                    {
                        speciality = $("#speciality").val();
                    }
                    var loc_1 = $("#country").val();
                    var loc_2 = '';
                    if($("#state").val().length > 0 && $("#state").val() != '-1')
                    {
                        loc_2 = $("#state").val() + ", " + $("#country").val();
                    }
                    var mba = true;
                    if(find_doctor_page == 31)
                    {
                        mba = false;
                    }
                    $.post("find_doctor.php", {type: speciality, location_1: loc_1, location_2: loc_2, must_be_available: mba}, function(data, status)
                    {
                        if(data != 'none')
                        {
                            var info = JSON.parse(data);
                            selected_doctor_info = "recdoc_"+info['id']+"_"+info['phone']+"_"+info['name']+"_"+info['location'];
                            console.log(selected_doctor_info);
                            $.post("getDoctorAvailableTimeranges.php", {id: info['id']}, function(data2, status)
                            {
                                console.log(data2);
                                var info = JSON.parse(data2);
                                for(var i = 0; i < 7; i++)
                                {
                                    if(info['slots'][i].length == 0)
                                    {
                                        $("#"+getDayStr(i)).addClass("day_disabled");
                                        $("#"+getDayStr(i)).children("input").eq(1).val("[]");
                                        $("#"+getDayStr(i)).children("input").eq(2).val("");
                                    }
                                    else
                                    {
                                        $("#"+getDayStr(i)).removeClass("day_disabled");
                                        $("#"+getDayStr(i)).children("input").eq(1).val("["+info['slots'][i].toString()+"]");
                                        $("#"+getDayStr(i)).children("input").eq(2).val("["+info['zones'][i].toString()+"]");
                                    }
                                }
                                if(find_doctor_page == 30)
                                {
                                    find_doctor_page = 32;
                                    resetDateTimeSelector();
                                    $('#find_doctor_appointment_1').fadeOut(300, function(){$('#find_doctor_time').fadeIn(300)});
                                }
                                else
                                {
                                    find_doctor_page = 12;
                                    var info = selected_doctor_info.split("_");
                                    var html = '<ul style="color: #22AEFF; margin-top: 50px; margin-left: 120px;"><li style="text-align: left;">Receipt: <strong>HTI - CR102388</strong></li><li style="text-align: left;"><strong>';
                                    if(consultation_type == 1)
                                    {
                                        html += 'Video ';
                                    }
                                    else
                                    {
                                        html += 'Phone ';
                                    }
                                    html += 'Consultation</strong></li><li style="text-align: left;">With Dr. <strong>'+ info[3] + ' ' + info[4] + '</strong></li><li style="text-align: left;">starting <strong>NOW</strong></li></ul></div>';
                                    $("#find_doctor_receipt").html(html);
                                    $("#find_doctor_confirmation").html('<p style="color: #22AEFF; margin-top: 50px;"><strong>Thank you!</strong><br/><strong>Your consultation appointment is confirmed and will start IMMEDIATELY.</strong></p></div>');
                                    $('#find_doctor_appointment_1').fadeOut(300, function(){$('#find_doctor_receipt').fadeIn(300)});
                                    $("#step_bar_4").attr("class", "step_bar lit");
                                    $("#step_circle_5").attr("class", "step_circle lit");
                                    $("#find_doctor_label").text("Confirmation");
                                }
                            });
                        }
                        else
                        {
                            // tell user the doctor could not be found in their area
                            if(find_doctor_page == 31)
                            {
                                find_doctor_page = 35;
                            }
                            else
                            {
                                find_doctor_page = 15;
                            }
							if(initial_language == 'th'){
							translation = 'Lo siento, no hemos podido encontrar ningún</br>médico general en tu area.';
							}else if(initial_language == 'en'){
							translation = "Sorry, we could not find any<br/>general practicioners in your area.";
							}
                            $("#not_found_text").html(translation);
                            $('#find_doctor_appointment_1').fadeOut(300, function(){$('#find_doctor_appointment_3').fadeIn(300, function(){$("#find_doctor_next_button").css("display", "none");})}); 
                            $("#step_bar_3").attr("class", "step_bar");
                            $("#step_circle_4").attr("class", "step_circle");
							if(initial_language == 'th'){
							translation = 'Seleccionar Especialidad';
							}else if(initial_language == 'en'){
							translation = 'Select Speciality';
							}
                            $("#find_doctor_label").text(translation);
                        }
                    });
                }
                else
                {
                    $("#step_bar_3").attr("class", "step_bar");
                    $("#step_circle_4").attr("class", "step_circle");
						if(initial_language == 'th'){
				        translation = 'Seleccionar Especialidad';
						}else if(initial_language == 'en'){
						translation = 'Select Speciality';
						}
                    $("#find_doctor_label").text(translation);
                }
                
            }
            if(find_doctor_page == 21)
            {
                if(selected_doctor_available == 0)
                {
                    
                    if($('#in_location_checkbox').is(":checked"))
                    {
                        find_doctor_page = 22;
                        resetDateTimeSelector();
                        $('#find_doctor_my_doctors_2').fadeOut(300, function(){$('#find_doctor_time').fadeIn(300)});
                    }
                    else
                    {
                        $("#step_bar_3").attr("class", "step_bar");
                        $("#step_circle_4").attr("class", "step_circle");
						if(initial_language == 'th'){
						translation = 'Seleccionar Tipo';
						}else if(initial_language == 'en'){
						translation = 'Select Type';
						}
                        $("#find_doctor_label").text(translation);
                    }
                }
                else
                {
                    
                    if($('#in_location_checkbox').is(":checked"))
                    {
                        find_doctor_page = 25;
                        $('#find_doctor_my_doctors_2').fadeOut(300, function(){$('#find_doctor_my_doctors_3').fadeIn(300)});
                    }
                    else
                    {
                        $("#step_bar_3").attr("class", "step_bar");
                        $("#step_circle_4").attr("class", "step_circle");
						if(initial_language == 'th'){
						translation = 'Seleccionar Tipo';
						}else if(initial_language == 'en'){
						translation = 'Select Type';
						}
                        $("#find_doctor_label").text(translation);
                    }
                }
            }
        }
        else if(find_doctor_page == 32 || find_doctor_page == 22)
        {
            $("#step_bar_1").attr("class", "step_bar lit");
            $("#step_circle_1").attr("class", "step_circle lit");
            $("#step_circle_2").attr("class", "step_circle lit");
            $("#step_bar_2").attr("class", "step_bar lit");
            $("#step_circle_3").attr("class", "step_circle lit");
            $("#step_bar_3").attr("class", "step_bar lit");
            $("#step_circle_4").attr("class", "step_circle lit");
            $("#step_bar_4").attr("class", "step_bar lit");
            $("#step_circle_5").attr("class", "step_circle lit");
            $("#find_doctor_label").text("Confirmation");
            if(find_doctor_page == 32)
            {
                var info = selected_doctor_info.split("_");
                var html = '<ul style="color: #22AEFF; margin-top: 50px; margin-left: 120px;"><li style="text-align: left;">Receipt: <strong>HTI - CR102388</strong></li><li style="text-align: left;"><strong>';
                if(consultation_type == 1)
                {
                    html += 'Video ';
                }
                else
                {
                    html += 'Phone ';
                }
                html += 'Consultation</strong></li><li style="text-align: left;">With Dr. <strong>'+ info[3] + ' ' + info[4] + '</strong></li><li style="text-align: left;">next <strong>'+getDay(day_selected)+'</strong> between <strong>'+getSlot(time_selected)+'</strong></li></ul></div>';
                $("#find_doctor_receipt").html(html);
                $("#find_doctor_confirmation").html('<p style="color: #22AEFF; margin-top: 50px;"><strong>Thank you!</strong><br/><strong>Your consultation appointment is confirmed.</strong><br/>Please be ready at the selected day and time, and follow the instructions that we sent to you</p></div>');
                find_doctor_page = 33;
                $('#find_doctor_time').fadeOut(300, function(){$('#find_doctor_receipt').fadeIn(300)});
            }
            else if(find_doctor_page == 22 && time_selected != -1 && day_selected != -1)
            {
                find_doctor_page = 23;
                var info = selected_doctor_info.split("_");
                var html = '<ul style="color: #22AEFF; margin-top: 50px; margin-left: 120px;"><li style="text-align: left;">Receipt: <strong>HTI - CR102388</strong></li><li style="text-align: left;"><strong>';
                if(consultation_type == 1)
                {
                    html += 'Video ';
                }
                else
                {
                    html += 'Phone ';
                }
                html += 'Consultation</strong></li><li style="text-align: left;">With Dr. <strong>'+ info[3] + ' ' + info[4] + '</strong></li><li style="text-align: left;">next <strong>'+getDay(day_selected)+'</strong> between <strong>'+getSlot(time_selected)+'</strong></li></ul></div>';
                $("#find_doctor_receipt").html(html);
                $("#find_doctor_confirmation").html('<p style="color: #22AEFF; margin-top: 50px;"><strong>Thank you!</strong><br/><strong>Your consultation appointment is confirmed.</strong><br/>Please be ready at the selected day and time, and follow the instructions that we sent to you</p></div>');
                $('#find_doctor_time').fadeOut(300, function(){$('#find_doctor_receipt').fadeIn(300)});
            }
            else
            {
                $("#step_bar_4").attr("class", "step_bar");
                $("#step_circle_5").attr("class", "step_circle");
                $("#find_doctor_label").text("Select Time");
            }
            
        }
        else if(find_doctor_page == 33 || find_doctor_page == 23 || find_doctor_page == 26 || find_doctor_page == 12)
        {
            if(find_doctor_page == 33)
            {
                find_doctor_page = 34;
                var info = selected_doctor_info.split("_");
                console.log(date_selected);
                var type = 1;
                if(consultation_type != 1)
                {
                    type = 0;
                }
                $.post("add_appointment.php", {medid: info[1], patid: $("#USERID").val(), date: date_selected, start_time: getSlotStartTime(time_selected), end_time: getSlotEndTime(time_selected), video: type, timezone: selected_timezone}, function(data,status)
                {
                    if(data != '-1')
                    {
                        $.get("send_appointment_email.php?id="+data+"&type=patient", function(data, status)
                        {
                            $.get("send_appointment_email.php?id="+data+"&type=doctor", function(data, status){});
                        });
                    }
                });
            }
            if(find_doctor_page == 23)
            {
                find_doctor_page = 24;
                var info = selected_doctor_info.split("_");
                console.log(date_selected);
                var type = 1;
                if(consultation_type != 1)
                {
                    type = 0;
                }
                $.post("add_appointment.php", {medid: info[1], patid: $("#USERID").val(), date: date_selected, start_time: getSlotStartTime(time_selected), end_time: getSlotEndTime(time_selected), video: type, timezone: selected_timezone}, function(data,status)
                {
                    if(data != '-1')
                    {
                        $.get("send_appointment_email.php?id="+data+"&type=patient", function(data, status)
                        {
                            $.get("send_appointment_email.php?id="+data+"&type=doctor", function(data, status){});
                        });
                    }
                });
            }
            if(find_doctor_page == 26 || find_doctor_page == 12)
            {
                if(find_doctor_page == 26)
                {
                    find_doctor_page = 27;
                }
                else
                {
                    find_doctor_page = 13;
                }
                
                // start appointment now with selected doctor
                var info = selected_doctor_info.split("_");
                
                // NO VIDEO FOR HTI USERS
                /*if(consultation_type == 1)
                {
                    
                    $.post("start_telemed_videocall.php", {pat_phone: $("#USERPHONE").val(), doc_phone: info[2], doc_id: info[1], pat_id: $("#USERID").val(), doc_name: (info[3] + ' ' + info[4]), pat_name: ($("#USERNAME").val() + ' ' + $("#USERSURNAME").val())}, function(data, status)
                    {
                        if(data == 1)
                        {
                            
                            window.open("telemedicine_patient.php?MED=" + info[1] + "&PAT=" + $("#USERID").val(),"Telemedicine","height=650,width=700,left=100,top=100,resizable=yes,scrollbars=yes,toolbar=yes,menubar=no,location=no,directories=no,status=yes");
                        }
                        else
                        {
                            alert("There was an error. Please try again later");
                        }
                    });
                }
                else
                {*/
                    $.post("start_telemed_phonecall.php?stage=init-call", {pat_phone: $("#USERPHONE").val(), doc_phone: info[2], doc_id: info[1], pat_id: $("#USERID").val(), doc_name: (info[3] + ' ' + info[4]), pat_name: ($("#USERNAME").val() + ' ' + $("#USERSURNAME").val())}, function(data, status){console.log(data);});
                //}
            }
            $('#find_doctor_receipt').fadeOut(300, function(){$('#find_doctor_confirmation').fadeIn(300)});
            $("#step_bar_1").attr("class", "step_bar lit");
            $("#step_circle_1").attr("class", "step_circle lit");
            $("#step_circle_2").attr("class", "step_circle lit");
            $("#step_bar_2").attr("class", "step_bar lit");
            $("#step_circle_3").attr("class", "step_circle lit");
            $("#step_bar_3").attr("class", "step_bar lit");
            $("#step_circle_4").attr("class", "step_circle lit");
            $("#step_bar_4").attr("class", "step_bar lit");
            $("#step_circle_5").attr("class", "step_circle lit");
            $("#step_bar_5").attr("class", "step_bar lit");
            $("#step_circle_6").attr("class", "step_circle lit");
            $("#find_doctor_label").text("");
            $("#find_doctor_next_button").css("display", "none");
            $("#find_doctor_previous_button").css("display", "none");
            $("#find_doctor_cancel_button").css("display", "none");
            $("#find_doctor_close_button").css("display", "block");
        }
    });
    $("#find_doctor_previous_button").live('click', function()
    {
        if(find_doctor_page == 23 || find_doctor_page == 33)
        {
            if(find_doctor_page == 23)
            {
                find_doctor_page = 22;
            }
            else if(find_doctor_page == 33)
            {
                find_doctor_page = 32;
            }
            resetDateTimeSelector();
            $('#find_doctor_receipt').fadeOut(300, function(){$('#find_doctor_time').fadeIn(300)});
            $("#step_bar_1").attr("class", "step_bar lit");
            $("#step_circle_1").attr("class", "step_circle lit");
            $("#step_circle_2").attr("class", "step_circle lit");
            $("#step_bar_2").attr("class", "step_bar lit");
            $("#step_circle_3").attr("class", "step_circle lit");
            $("#step_bar_3").attr("class", "step_bar lit");
            $("#step_circle_4").attr("class", "step_circle lit");
            $("#step_bar_4").attr("class", "step_bar");
            $("#step_circle_5").attr("class", "step_circle");
            $("#find_doctor_label").text("Select Time");
        }
        else if(find_doctor_page == 22 || find_doctor_page == 32 || find_doctor_page == 12)
        {
            $("#step_bar_1").attr("class", "step_bar lit");
            $("#step_circle_1").attr("class", "step_circle lit");
            $("#step_circle_2").attr("class", "step_circle lit");
            $("#step_bar_2").attr("class", "step_bar lit");
            $("#step_circle_3").attr("class", "step_circle lit");
            $("#step_bar_3").attr("class", "step_bar");
            $("#step_circle_4").attr("class", "step_circle");
            $("#step_bar_4").attr("class", "step_bar");
            $("#step_circle_5").attr("class", "step_circle");
			if(initial_language == 'th'){
			translation = 'Seleccionar Tipo';
			}else if(initial_language == 'en'){
			translation = 'Select Type';
			}
            $("#find_doctor_label").text(translation);
            if(find_doctor_page == 22)
            {
                if(selected_doctor_available == 0)
                {
                    find_doctor_page = 21;
                    $('#find_doctor_time').fadeOut(300, function(){$('#find_doctor_my_doctors_2').fadeIn(300)});
                }
                else
                {
                    find_doctor_page = 25;
                    $('#find_doctor_time').fadeOut(300, function(){$('#find_doctor_my_doctors_3').fadeIn(300)});
                    $("#find_doctor_label").text("Select Time");
                    $("#step_bar_3").attr("class", "step_bar lit");
                    $("#step_circle_4").attr("class", "step_circle lit");
                }
                
            }
            
            else if(find_doctor_page == 32 || find_doctor_page == 12)
            {
                if(find_doctor_page == 32)
                {
                    find_doctor_page = 30;
                    $('#find_doctor_time').fadeOut(300, function(){$('#find_doctor_appointment_1').fadeIn(300)});
					if(initial_language == 'th'){
					translation = 'Seleccionar Tipo';
					}else if(initial_language == 'en'){
					translation = 'Select Type';
					}
                    $("#find_doctor_label").text(translation);
                    $("#step_bar_2").attr("class", "step_bar");
                    $("#step_circle_3").attr("class", "step_circle");
                }
                else
                {
                    find_doctor_page = 10;
                    $('#find_doctor_receipt').fadeOut(300, function(){$('#find_doctor_appointment_1').fadeIn(300)});
					if(initial_language == 'th'){
					translation = 'Seleccionar Tipo';
					}else if(initial_language == 'en'){
					translation = 'Select Type';
					}
                    $("#find_doctor_label").text(translation);
                    $("#step_bar_2").attr("class", "step_bar");
                    $("#step_circle_3").attr("class", "step_circle");
                }
            }
        }
        else if(find_doctor_page == 21 || find_doctor_page == 31 || find_doctor_page == 11)
        {
            if(find_doctor_page == 21)
            {
                $('#find_doctor_my_doctors_2').fadeOut(300, function(){$('#find_doctor_my_doctors_1').fadeIn(300)});
                find_doctor_page = 20;
                $("#find_doctor_label").text("Select Doctor");
                
            }
            else if(find_doctor_page == 31)
            {
                find_doctor_page = 30;
                $('#find_doctor_appointment_2').fadeOut(300, function(){$('#find_doctor_appointment_1').fadeIn(300)});
				if(initial_language == 'th'){
				translation = 'Seleccionar Tipo';
				}else if(initial_language == 'en'){
				translation = 'Select Type';
				}
                $("#find_doctor_label").text(translation);
            }
            else
            {
                find_doctor_page = 10;
                $('#find_doctor_appointment_2').fadeOut(300, function(){$('#find_doctor_appointment_1').fadeIn(300)});
				if(initial_language == 'th'){
				translation = 'Seleccionar Tipo';
				}else if(initial_language == 'en'){
				translation = 'Select Type';
				}
                $("#find_doctor_label").text(translation);
            }
            $("#step_bar_1").attr("class", "step_bar lit");
            $("#step_circle_1").attr("class", "step_circle lit");
            $("#step_circle_2").attr("class", "step_circle lit");
            $("#step_bar_2").attr("class", "step_bar");
            $("#step_circle_3").attr("class", "step_circle");
            $("#step_bar_3").attr("class", "step_bar");
            $("#step_circle_4").attr("class", "step_circle");
            $("#step_bar_4").attr("class", "step_bar");
            $("#step_circle_5").attr("class", "step_circle");
        }
        else if(find_doctor_page == 20 || find_doctor_page == 30 || find_doctor_page == 10)
        {
            if(find_doctor_page == 20)
            {
                $('#find_doctor_my_doctors_1').fadeOut(300, function(){$('#find_doctor_main').fadeIn(300)});
            }
            else if(find_doctor_page == 30 || find_doctor_page == 10)
            {
                $('#find_doctor_appointment_1').fadeOut(300, function(){$('#find_doctor_main').fadeIn(300)});
            }
            find_doctor_page = 0;
            $("#step_bar_1").attr("class", "step_bar");
            $("#step_circle_1").attr("class", "step_circle lit");
            $("#step_circle_2").attr("class", "step_circle");
            $("#step_bar_2").attr("class", "step_bar");
            $("#step_circle_3").attr("class", "step_circle");
            $("#step_bar_3").attr("class", "step_bar");
            $("#step_circle_4").attr("class", "step_circle");
            $("#step_bar_4").attr("class", "step_bar");
            $("#step_circle_5").attr("class", "step_circle");
            $("#find_doctor_label").text("");
        }
        else if(find_doctor_page == 25)
        {
            $('#find_doctor_my_doctors_3').fadeOut(300, function(){$('#find_doctor_my_doctors_2').fadeIn(300)});
            find_doctor_page = 21;
            $("#step_bar_1").attr("class", "step_bar lit");
            $("#step_circle_1").attr("class", "step_circle lit");
            $("#step_circle_2").attr("class", "step_circle lit");
            $("#step_bar_2").attr("class", "step_bar lit");
            $("#step_circle_3").attr("class", "step_circle lit");
            $("#step_bar_3").attr("class", "step_bar");
            $("#step_circle_4").attr("class", "step_circle");
            $("#step_bar_4").attr("class", "step_bar");
            $("#step_circle_5").attr("class", "step_circle");
			if(initial_language == 'th'){
			translation = 'Seleccionar Tipo';
			}else if(initial_language == 'en'){
			translation = 'Select Type';
			}
            $("#find_doctor_label").text(translation);
        }
        else if(find_doctor_page == 26)
        {
            $('#find_doctor_receipt').fadeOut(300, function(){$('#find_doctor_my_doctors_3').fadeIn(300)});
            find_doctor_page = 25;
            $("#step_bar_1").attr("class", "step_bar lit");
            $("#step_circle_1").attr("class", "step_circle lit");
            $("#step_circle_2").attr("class", "step_circle lit");
            $("#step_bar_2").attr("class", "step_bar lit");
            $("#step_circle_3").attr("class", "step_circle lit");
            $("#step_bar_3").attr("class", "step_bar lit");
            $("#step_circle_4").attr("class", "step_circle lit");
            $("#step_bar_4").attr("class", "step_bar");
            $("#step_circle_5").attr("class", "step_circle");
            $("#find_doctor_label").text("Select Time");
        }
        else if(find_doctor_page == 35)
        {
            $('#find_doctor_appointment_3').fadeOut(300, function(){$('#find_doctor_appointment_2').fadeIn(300)});
            find_doctor_page = 31;
            $("#find_doctor_next_button").css("display", "block");
        }
        else if(find_doctor_page == 15)
        {
            $('#find_doctor_appointment_3').fadeOut(300, function(){$('#find_doctor_appointment_1').fadeIn(300)});
            find_doctor_page = 10;
            $("#find_doctor_next_button").css("display", "block");
        }
    });
    $("#find_doctor_cancel_button").live('click', function()
    {
        $("#find_doctor_modal").dialog("close");
    });
    $("#find_doctor_close_button").live('click', function()
    {
        $("#find_doctor_modal").dialog("close");
    });
    $("#find_doctor_now_button").live('click',function()
    {
	$('#phone_call_button').click();
	$('#status_circles').hide();
	$('#find_doctor_now_button').hide();
	$('#find_doctor_cancel_button').hide();
	$('#find_doctor_close_button').hide();
	$('#find_doctor_next_button').hide();
	$('#find_doctor_previous_button').hide();
        // GEOLOCATION
        //alert("Your location is: " + geoplugin_countryName() + ", " + geoplugin_region() + ", " + geoplugin_city());
        //var country = geoplugin_countryName();
        //if (country == "United States") {
        //    country = "USA";
        //}    
	//	$("#country").val(country);	
	//    $("#country").change();
        //
        //if(country== 'USA')
        //{
        //    $("#state").parent().parent().css('display', 'block');
        //}
        //else
        //{
        //    $("#state").parent().parent().css('display', 'none');
        //}
	//    //populateCountries("country", "state");
	//    //var zone = geoplugin_region();
	//    //var district = geoplugin_city();
	//	//$("#state").val(district);			 
	//    //$("#state").change();
        // GEOLOCATION
    
        //if(!$(this).hasClass("square_blue_button_disabled"))
        //{
        //    $("#step_bar_1").attr("class", "step_bar lit");
        //    $("#step_circle_1").attr("class", "step_circle lit");
        //    $("#step_circle_2").attr("class", "step_circle lit");
        //    $("#find_doctor_main").fadeOut(300, function(){$("#find_doctor_appointment_1").fadeIn(300)});
        //    find_doctor_page = 10;
	//		if(initial_language == 'th'){
	//		translation = 'Seleccionar Tipo';
	//		}else if(initial_language == 'en'){
	//		translation = 'Select Type';
	//	}
        //    $("#find_doctor_label").text(translation);
        //}
    });
    $("#find_doctor_appointment_button").live('click',function()
    {
        // GEOLOCATION
        //alert("Your location is: " + geoplugin_countryName() + ", " + geoplugin_region() + ", " + geoplugin_city());
        /*var country = geoplugin_countryName();
        if (country == "United States") {
            country = "USA";
        }    
		$("#country").val(country);	
	    $("#country").change();
        if(country== 'USA')
        {
            $("#state").parent().parent().css('display', 'block');
        }
        else
        {
            $("#state").parent().parent().css('display', 'none');
        }
	    //populateCountries("country", "state");
	    var zone = geoplugin_region();
	    var district = geoplugin_city();
		$("#state").val(district);			 
	    $("#state").change();*/
        // 

        $("#step_bar_1").attr("class", "step_bar lit");
        $("#step_circle_1").attr("class", "step_circle lit");
        $("#step_circle_2").attr("class", "step_circle lit");
        $("#find_doctor_main").fadeOut(300, function(){$("#find_doctor_appointment_1").fadeIn(300)});
        find_doctor_page = 30;
		if(initial_language == 'th'){
		translation = 'Seleccionar Tipo';
		}else if(initial_language == 'en'){
		translation = 'Select Type';
		}
        $("#find_doctor_label").text(translation);
    });
    $("#find_doctor_my_doctors_button").live('click',function()
    {
        if(!$(this).hasClass("square_blue_button_disabled"))
        {
            $("#step_bar_1").attr("class", "step_bar lit");
            $("#step_circle_1").attr("class", "step_circle lit");
            $("#step_circle_2").attr("class", "step_circle lit");
            $("#find_doctor_main").fadeOut(300, function(){$("#find_doctor_my_doctors_1").fadeIn(300)});
            find_doctor_page = 20;
            $("#find_doctor_label").text("Select Doctor");
        }
    });
    function getDayStr(i)
    {
        if(i == 0)
        {
            return "sun";
        }
        else if(i == 1)
        {
            return "mon";
        }
        else if(i == 2)
        {
            return "tues";
        }
        else if(i == 3)
        {
            return "wed";
        }
        else if(i == 4)
        {
            return "thur";
        }
        else if(i == 5)
        {
            return "fri";
        }
        else if(i == 6)
        {
            return "sat";
        }
    }
    $("[id^='recdoc_']").live('click', function()
    {
        $("#step_bar_1").attr("class", "step_bar lit");
        $("#step_circle_1").attr("class", "step_circle lit");
        $("#step_circle_2").attr("class", "step_circle lit");
        $("#step_bar_2").attr("class", "step_bar lit");
        $("#step_circle_3").attr("class", "step_circle lit");
        $("#find_doctor_my_doctors_1").fadeOut(300, function(){$("#find_doctor_my_doctors_2").fadeIn(300)});
        find_doctor_page = 21;
        if($(this).attr("id").search("Available") != -1)
        {
            selected_doctor_available = 1;
        }
        else
        {
            selected_doctor_available = 0;
        }
		var translation = '';
		
		
		if(initial_language == 'th'){
		translation = 'Seleccionar Tipo';
		}else if(initial_language == 'en'){
		translation = 'Select Type';
		}
        $("#find_doctor_label").text(translation);
        var info = $(this).attr("id").split("_");
        selected_doctor_info = $(this).attr("id");
        $("#doctor_location_text").html("Doctor " + info[3] + " " + info[4] + " is in <strong>" + info[5] + "</strong>.<br/>Please confirm that you are in <strong>" + info[5] + "</strong> as well.");
        $("#doctor_oncall_text").html("Doctor " + info[3] + " " + info[4] + " is ON CALL NOW!<br/>Would you like to connect now?");
        $.post("getDoctorAvailableTimeranges.php", {id: info[1]}, function(data, status)
        {
            var info = JSON.parse(data);
            for(var i = 0; i < 7; i++)
            {
                if(info['slots'][i].length == 0)
                {
                    $("#"+getDayStr(i)).addClass("day_disabled");
                    $("#"+getDayStr(i)).children("input").eq(1).val("[]");
                    $("#"+getDayStr(i)).children("input").eq(2).val("");
                }
                else
                {
                    $("#"+getDayStr(i)).removeClass("day_disabled");
                    $("#"+getDayStr(i)).children("input").eq(1).val("["+info['slots'][i].toString()+"]");
                    $("#"+getDayStr(i)).children("input").eq(2).val("["+info['zones'][i].toString()+"]");
                }
            }
        });
    });
    $("#find_doctor_video_button").live('click', function()
    {
        $("#find_doctor_video_button").css('background-color', '#22aeff');
        $("#find_doctor_phone_button").css('background-color', '#535353');
        consultation_type = 1;
    });
    $("#find_doctor_phone_button").live('click', function()
    {
        $("#find_doctor_phone_button").css('background-color', '#22aeff');
        $("#find_doctor_video_button").css('background-color', '#535353');
        consultation_type = 2;
    });
    $("#find_doctor_video_button_2").live('click', function()
    {
        $("#find_doctor_video_button_2").css('background-color', '#22aeff');
        $("#find_doctor_phone_button_2").css('background-color', '#535353');
        consultation_type = 1;
    });
    $("#find_doctor_phone_button_2").live('click', function()
    {
        $("#find_doctor_phone_button_2").css('background-color', '#22aeff');
        $("#find_doctor_video_button_2").css('background-color', '#535353');
        consultation_type = 2;
    });
    $("#connect_now_yes").live('click', function()
    {
        find_doctor_page = 26;
        $('#find_doctor_my_doctors_3').fadeOut(300, function(){$('#find_doctor_receipt').fadeIn(300)});
        $("#step_bar_1").attr("class", "step_bar lit");
        $("#step_circle_1").attr("class", "step_circle lit");
        $("#step_circle_2").attr("class", "step_circle lit");
        $("#step_bar_2").attr("class", "step_bar lit");
        $("#step_circle_3").attr("class", "step_circle lit");
        $("#step_bar_3").attr("class", "step_bar lit");
        $("#step_circle_4").attr("class", "step_circle lit");
        $("#step_bar_4").attr("class", "step_bar lit");
        $("#step_circle_5").attr("class", "step_circle lit");
        $("#find_doctor_label").text("Confirmation");
        var info = selected_doctor_info.split("_");
        var html = '<ul style="color: #22AEFF; margin-top: 50px; margin-left: 120px;"><li style="text-align: left;">Receipt: <strong>HTI - CR102388</strong></li><li style="text-align: left;"><strong>';
        console.log($("#find_doctor_video_button_2").css("background-color"));
        if(consultation_type == 1)
        {
            html += 'Video ';
        }
        else
        {
            html += 'Phone ';
        }
        html += 'Consultation</strong></li><li style="text-align: left;">With Dr. <strong>'+ info[3] + ' ' + info[4] + '</strong></li><li style="text-align: left;">starting <strong>NOW</strong></li></ul></div>';
        $("#find_doctor_receipt").html(html);
        $("#find_doctor_confirmation").html('<p style="color: #22AEFF; margin-top: 50px;"><strong>Thank you!</strong><br/><strong>Your consultation appointment is confirmed<br/> and will start IMMEDIATELY.</strong></p></div>');
        
    });
    $("#connect_now_no").live('click', function()
    {
        find_doctor_page = 22;
        resetDateTimeSelector();
        $('#find_doctor_my_doctors_3').fadeOut(300, function(){$('#find_doctor_time').fadeIn(300)});
        $("#step_bar_1").attr("class", "step_bar lit");
        $("#step_circle_1").attr("class", "step_circle lit");
        $("#step_circle_2").attr("class", "step_circle lit");
        $("#step_bar_2").attr("class", "step_bar lit");
        $("#step_circle_3").attr("class", "step_circle lit");
        $("#step_bar_3").attr("class", "step_bar lit");
        $("#step_circle_4").attr("class", "step_circle lit");
        $("#find_doctor_label").text("Select Time");
    });
        
    $("#find_doctor_general_practicioner").live('click', function()
    {
        var loc_1 = $("#country").val();
        var loc_2 = '';
        if($("#state").val().length > 0 && $("#state").val() != '-1')
        {
            loc_2 = $("#state").val() + ", " + $("#country").val();
        }
        var mba = true;
        if(find_doctor_page == 31)
        {
            mba = false;
        }
        console.log(loc_1+" "+loc_2+" "+mba);
        $.post("find_doctor.php", {type: "General Practice", location_1: loc_1, location_2: loc_2, must_be_available: mba}, function(data, status)
        {
            
            
            if(data != 'none')
            {
                var info = JSON.parse(data);
                selected_doctor_info = "recdoc_"+info['id']+"_"+info['phone']+"_"+info['name']+"_"+info['location'];
                console.log(selected_doctor_info);
                $.post("getDoctorAvailableTimeranges.php", {id: info['id']}, function(data, status)
                {
                    console.log(data);
                    var info = JSON.parse(data);
                    for(var i = 0; i < 7; i++)
                    {
                        if(info['slots'][i].length == 0)
                        {
                            $("#"+getDayStr(i)).addClass("day_disabled");
                            $("#"+getDayStr(i)).children("input").eq(1).val("[]");
                            $("#"+getDayStr(i)).children("input").eq(2).val("");
                        }
                        else
                        {
                            $("#"+getDayStr(i)).removeClass("day_disabled");
                            $("#"+getDayStr(i)).children("input").eq(1).val("["+info['slots'][i].toString()+"]");
                            $("#"+getDayStr(i)).children("input").eq(2).val("["+info['zones'][i].toString()+"]");
                        }
                    }
                       
                    $("#step_bar_1").attr("class", "step_bar lit");
                    $("#step_circle_1").attr("class", "step_circle lit");
                    $("#step_circle_2").attr("class", "step_circle lit");
                    $("#step_bar_2").attr("class", "step_bar lit");
                    $("#step_circle_3").attr("class", "step_circle lit");
                    $("#step_bar_3").attr("class", "step_bar lit");
                    $("#step_circle_4").attr("class", "step_circle lit");
                    
                    if(find_doctor_page == 31)
                    {
                        find_doctor_page = 32;
                        $("#find_doctor_label").text("Select Time");
                        resetDateTimeSelector();
                        $('#find_doctor_appointment_2').fadeOut(300, function(){$('#find_doctor_time').fadeIn(300)}); 
                    }
                    else
                    {
                        find_doctor_page = 12;
                        var info = selected_doctor_info.split("_");
                        var html = '<ul style="color: #22AEFF; margin-top: 50px; margin-left: 120px;"><li style="text-align: left;">Receipt: <strong>HTI - CR102388</strong></li><li style="text-align: left;"><strong>';
                        if(consultation_type == 1)
                        {
                            html += 'Video ';
                        }
                        else
                        {
                            html += 'Phone ';
                        }
                        html += 'Consultation</strong></li><li style="text-align: left;">With Dr. <strong>'+ info[3] + ' ' + info[4] + '</strong></li><li style="text-align: left;">starting <strong>NOW</strong></li></ul></div>';
                        $("#find_doctor_receipt").html(html);
                        $("#find_doctor_confirmation").html('<p style="color: #22AEFF; margin-top: 50px;"><strong>Thank you!</strong><br/><strong>Your consultation appointment is confirmed and will start IMMEDIATELY.</strong></p></div>');
                        $("#find_doctor_label").text("Confirmation");
                        $('#find_doctor_appointment_2').fadeOut(300, function(){$('#find_doctor_receipt').fadeIn(300)});
                        $("#step_bar_4").attr("class", "step_bar lit");
                        $("#step_circle_5").attr("class", "step_circle lit");
                    }
                });
            }
            else
            {
                // tell user a general practicioner could not be found in their area
                if(find_doctor_page == 31)
                {
                    find_doctor_page = 35;
                }
                else
                {
                    find_doctor_page = 15;
                }
		var translation = '';
		
		
		if(initial_language == 'th'){
		translation = 'Lo siento, no hemos podido encontrar ningún</br>médico general en tu area.';
		
		}else if(initial_language == 'en'){
		translation = "Sorry, we could not find any<br/>general practicioners in your area.";
		
		}
                $("#not_found_text").html(translation);
                $('#find_doctor_appointment_2').fadeOut(300, function(){$('#find_doctor_appointment_3').fadeIn(300)}); 
                $("#find_doctor_next_button").css("display", "none");
            }
        });
        
    });
        
    function selectDay(day)
    {
        $("#sun").removeClass("day_selected");
        $("#mon").removeClass("day_selected");
        $("#tues").removeClass("day_selected");
        $("#wed").removeClass("day_selected");
        $("#thur").removeClass("day_selected");
        $("#fri").removeClass("day_selected");
        $("#sat").removeClass("day_selected");
        $("#"+day).addClass("day_selected");
    }
    function selectTime(slot)
    {
        $("#8_10_am").removeClass("slot_selected");
        $("#10_12").removeClass("slot_selected");
        $("#12_2").removeClass("slot_selected");
        $("#2_4").removeClass("slot_selected");
        $("#4_6").removeClass("slot_selected");
        $("#6_8").removeClass("slot_selected");
        $("#8_10_pm").removeClass("slot_selected");
        $("#"+slot).addClass("slot_selected");
    }
    $("#8_10_am").live('click', function()
    {
        if(!$(this).hasClass("slot_disabled"))
        {
            time_selected = 1;
            selected_timezone = zones[0];
            $("#time_selector_1").css("display", "block");
            $("#time_selector_1").css("margin-top", "10px");
            selectTime("8_10_am");
        }
    });
    $("#10_12").live('click', function()
    {
        if(!$(this).hasClass("slot_disabled"))
        {
            time_selected = 2;
            selected_timezone = zones[1];
            $("#time_selector_1").css("display", "block");
            $("#time_selector_1").css("margin-top", "42px");
            selectTime("10_12");
        }
    });
    $("#12_2").live('click', function()
    {
        if(!$(this).hasClass("slot_disabled"))
        {
            time_selected = 3;
            selected_timezone = zones[2];
            $("#time_selector_1").css("display", "block");
            $("#time_selector_1").css("margin-top", "74px");
            selectTime("12_2");
        }
    });
    $("#2_4").live('click', function()
    {
        if(!$(this).hasClass("slot_disabled"))
        {
            time_selected = 4;
            selected_timezone = zones[3];
            $("#time_selector_1").css("display", "block");
            $("#time_selector_1").css("margin-top", "106px");
            selectTime("2_4");
        }
    });
    $("#4_6").live('click', function()
    {
        if(!$(this).hasClass("slot_disabled"))
        {
            time_selected = 5;
            selected_timezone = zones[4];
            $("#time_selector_1").css("display", "block");
            $("#time_selector_1").css("margin-top", "138px");
            selectTime("4_6");
        }
    });
    $("#6_8").live('click', function()
    {
        if(!$(this).hasClass("slot_disabled"))
        {
            time_selected = 6;
            selected_timezone = zones[5];
            $("#time_selector_1").css("display", "block");
            $("#time_selector_1").css("margin-top", "170px");
            selectTime("6_8");
        }
    });
    $("#8_10_pm").live('click', function()
    {
        if(!$(this).hasClass("slot_disabled"))
        {
            time_selected = 7;
            selected_timezone = zones[6];
            $("#time_selector_1").css("display", "block");
            $("#time_selector_1").css("margin-top", "202px");
            selectTime("8_10_pm");
        }
    });
        
    function disableAllSlots()
    {
        $("#8_10_am").addClass("slot_disabled");
        $("#10_12").addClass("slot_disabled");
        $("#12_2").addClass("slot_disabled");
        $("#2_4").addClass("slot_disabled");
        $("#4_6").addClass("slot_disabled");
        $("#6_8").addClass("slot_disabled");
        $("#8_10_pm").addClass("slot_disabled");
    }
        
    $("#sun").live('click', function()
    {
        if(!$(this).hasClass("day_disabled"))
        {
            day_selected = 1;
            $("#day_selector_1").css("display", "block");
            $("#day_selector_1").css("margin-left", "20px");
            selectDay("sun");
            var info = $("#sun").children("input").eq(1).val().substr(1, $("#sun").children("input").eq(1).val().length - 2).split(",");
            zones = $("#sun").children("input").eq(2).val().substr(1, $("#sun").children("input").eq(2).val().length - 2).split(",");
            date_selected = $("#sun").children("input").eq(0).val();
            disableAllSlots();
            for(var k = 0; k < info.length; k++)
            {
                if(info[k] == '0')
                    $("#8_10_am").removeClass("slot_disabled");
                else if(info[k] == '1')
                    $("#10_12").removeClass("slot_disabled");
                else if(info[k] == '2')
                    $("#12_2").removeClass("slot_disabled");
                else if(info[k] == '3')
                    $("#2_4").removeClass("slot_disabled");
                else if(info[k] == '4')
                    $("#4_6").removeClass("slot_disabled");
                else if(info[k] == '5')
                    $("#6_8").removeClass("slot_disabled");
               else  if(info[k] == '6')
                    $("#8_10_pm").removeClass("slot_disabled");
            }
            
        }
    });
    $("#mon").live('click', function()
    {
        if(!$(this).hasClass("day_disabled"))
        {
            day_selected = 2;
            $("#day_selector_1").css("display", "block");
            $("#day_selector_1").css("margin-left", "68px");
            selectDay("mon");
            var info = $("#mon").children("input").eq(1).val().substr(1, $("#mon").children("input").eq(1).val().length - 2).split(",");
            zones = $("#mon").children("input").eq(2).val().substr(1, $("#mon").children("input").eq(2).val().length - 2).split(",");
            date_selected = $("#mon").children("input").eq(0).val();
            disableAllSlots();
            for(var k = 0; k < info.length; k++)
            {
                if(info[k] == '0')
                    $("#8_10_am").removeClass("slot_disabled");
                else if(info[k] == '1')
                    $("#10_12").removeClass("slot_disabled");
                else if(info[k] == '2')
                    $("#12_2").removeClass("slot_disabled");
                else if(info[k] == '3')
                    $("#2_4").removeClass("slot_disabled");
                else if(info[k] == '4')
                    $("#4_6").removeClass("slot_disabled");
                else if(info[k] == '5')
                    $("#6_8").removeClass("slot_disabled");
               else  if(info[k] == '6')
                    $("#8_10_pm").removeClass("slot_disabled");
            }
        }
    });
    $("#tues").live('click', function()
    {
        if(!$(this).hasClass("day_disabled"))
        {
            day_selected = 3;
            $("#day_selector_1").css("display", "block");
            $("#day_selector_1").css("margin-left", "116px");
            selectDay("tues");
            var info = $("#tues").children("input").eq(1).val().substr(1, $("#tues").children("input").eq(1).val().length - 2).split(",");
            zones = $("#tues").children("input").eq(2).val().substr(1, $("#tues").children("input").eq(2).val().length - 2).split(",");
            date_selected = $("#tues").children("input").eq(0).val();
            disableAllSlots();
            for(var k = 0; k < info.length; k++)
            {
                if(info[k] == '0')
                    $("#8_10_am").removeClass("slot_disabled");
                else if(info[k] == '1')
                    $("#10_12").removeClass("slot_disabled");
                else if(info[k] == '2')
                    $("#12_2").removeClass("slot_disabled");
                else if(info[k] == '3')
                    $("#2_4").removeClass("slot_disabled");
                else if(info[k] == '4')
                    $("#4_6").removeClass("slot_disabled");
                else if(info[k] == '5')
                    $("#6_8").removeClass("slot_disabled");
               else  if(info[k] == '6')
                    $("#8_10_pm").removeClass("slot_disabled");
            }
        }
    });
    $("#wed").live('click', function()
    {
        if(!$(this).hasClass("day_disabled"))
        {
            day_selected = 4;
            $("#day_selector_1").css("display", "block");
            $("#day_selector_1").css("margin-left", "164px");
            selectDay("wed");
            var info = $("#wed").children("input").eq(1).val().substr(1, $("#wed").children("input").eq(1).val().length - 2).split(",");
            zones = $("#wed").children("input").eq(2).val().substr(1, $("#wed").children("input").eq(2).val().length - 2).split(",");
            date_selected = $("#wed").children("input").eq(0).val();
            disableAllSlots();
            for(var k = 0; k < info.length; k++)
            {
                if(info[k] == '0')
                    $("#8_10_am").removeClass("slot_disabled");
                else if(info[k] == '1')
                    $("#10_12").removeClass("slot_disabled");
                else if(info[k] == '2')
                    $("#12_2").removeClass("slot_disabled");
                else if(info[k] == '3')
                    $("#2_4").removeClass("slot_disabled");
                else if(info[k] == '4')
                    $("#4_6").removeClass("slot_disabled");
                else if(info[k] == '5')
                    $("#6_8").removeClass("slot_disabled");
               else  if(info[k] == '6')
                    $("#8_10_pm").removeClass("slot_disabled");
            }
        }
    });
    $("#thur").live('click', function()
    {
        if(!$(this).hasClass("day_disabled"))
        {
            day_selected = 5;
            $("#day_selector_1").css("display", "block");
            $("#day_selector_1").css("margin-left", "212px");
            selectDay("thur");
            var info = $("#thur").children("input").eq(1).val().substr(1, $("#thur").children("input").eq(1).val().length - 2).split(",");
            zones = $("#thur").children("input").eq(2).val().substr(1, $("#thur").children("input").eq(2).val().length - 2).split(",");
            date_selected = $("#thur").children("input").eq(0).val();
            disableAllSlots();
            for(var k = 0; k < info.length; k++)
            {
                if(info[k] == '0')
                    $("#8_10_am").removeClass("slot_disabled");
                else if(info[k] == '1')
                    $("#10_12").removeClass("slot_disabled");
                else if(info[k] == '2')
                    $("#12_2").removeClass("slot_disabled");
                else if(info[k] == '3')
                    $("#2_4").removeClass("slot_disabled");
                else if(info[k] == '4')
                    $("#4_6").removeClass("slot_disabled");
                else if(info[k] == '5')
                    $("#6_8").removeClass("slot_disabled");
               else  if(info[k] == '6')
                    $("#8_10_pm").removeClass("slot_disabled");
            }
        }
    });
    $("#fri").live('click', function()
    {
        if(!$(this).hasClass("day_disabled"))
        {
            day_selected = 6;
            $("#day_selector_1").css("display", "block");
            $("#day_selector_1").css("margin-left", "260px");
            selectDay("fri");
            var info = $("#fri").children("input").eq(1).val().substr(1, $("#fri").children("input").eq(1).val().length - 2).split(",");
            zones = $("#fri").children("input").eq(2).val().substr(1, $("#fri").children("input").eq(2).val().length - 2).split(",");
            date_selected = $("#fri").children("input").eq(0).val();
            disableAllSlots();
            for(var k = 0; k < info.length; k++)
            {
                if(info[k] == '0')
                    $("#8_10_am").removeClass("slot_disabled");
                else if(info[k] == '1')
                    $("#10_12").removeClass("slot_disabled");
                else if(info[k] == '2')
                    $("#12_2").removeClass("slot_disabled");
                else if(info[k] == '3')
                    $("#2_4").removeClass("slot_disabled");
                else if(info[k] == '4')
                    $("#4_6").removeClass("slot_disabled");
                else if(info[k] == '5')
                    $("#6_8").removeClass("slot_disabled");
               else  if(info[k] == '6')
                    $("#8_10_pm").removeClass("slot_disabled");
            }
        }
    });
    $("#sat").live('click', function()
    {
        if(!$(this).hasClass("day_disabled"))
        {
            day_selected = 7;
            $("#day_selector_1").css("display", "block");
            $("#day_selector_1").css("margin-left", "308px");
            selectDay("sat");
            var info = $("#sat").children("input").eq(1).val().substr(1, $("#sat").children("input").eq(1).val().length - 2).split(",");
            zones = $("#sat").children("input").eq(2).val().substr(1, $("#sat").children("input").eq(2).val().length - 2).split(",");
            date_selected = $("#sat").children("input").eq(0).val();
            disableAllSlots();
            for(var k = 0; k < info.length; k++)
            {
                if(info[k] == '0')
                    $("#8_10_am").removeClass("slot_disabled");
                else if(info[k] == '1')
                    $("#10_12").removeClass("slot_disabled");
                else if(info[k] == '2')
                    $("#12_2").removeClass("slot_disabled");
                else if(info[k] == '3')
                    $("#2_4").removeClass("slot_disabled");
                else if(info[k] == '4')
                    $("#4_6").removeClass("slot_disabled");
                else if(info[k] == '5')
                    $("#6_8").removeClass("slot_disabled");
               else  if(info[k] == '6')
                    $("#8_10_pm").removeClass("slot_disabled");
            }
        }
    });
    
  
        
        
    $('#find_doctor_button').live('click', function()
    {
        if(doctor_to_connect.length > 0 || type_of_doctor_to_find.length > 0)
        {
            var info = doctor_to_connect.split("_");
            $.post("find_doctor.php", {type: type_of_doctor_to_find, id: info[0]}, function(data, status)
            {
                if(data == 'none')
                {
                    $('#find_doctor_modal').dialog('option', 'title', 'No Doctors Found');
                    $("#Talk_Section_1").fadeOut("Slow", function(){$("#Talk_Section_3").fadeIn("slow");});
                    $("#find_doctor_modal").dialog("widget").animate({width: '550px', height: '130px'}, {duration: 500, step: 
                        function()
                        {
                            $("#dialog").dialog('option', 'position', 'center');
                        }
                    });
                }
                else
                {
                    var doc = JSON.parse(data);
                    var name = doc.name;
                    name = name.replace("_", " ");
                    doctor_to_connect = doc.id + "_" + doc.phone + "_" + doc.name;
                    $('#find_doctor_modal').dialog('option', 'title', 'Doctor ' + name);
                    $("#Talk_Section_1").fadeOut("Slow", function(){$("#Talk_Section_2").fadeIn("slow");});
                    $("#find_doctor_modal").dialog("widget").animate({width: '550px', height: '130px'}, {duration: 500, step: 
                        function()
                        {
                            $("#dialog").dialog('option', 'position', 'center');
                        }
                    });
                }
            });
        }
        
    });
    $('.recent_doctor_button').live('click', function()
    {
        $('.recent_doctor_button_selected').attr("class", "recent_doctor_button");
        $(this).attr("class", "recent_doctor_button_selected");
        doctor_to_connect = $(this).attr("id");
        type_of_doctor_to_find = '';
    });
    $("#speciality").change(function()
    {
        doctor_to_connect = '';
        type_of_doctor_to_find = $(this).val();
    });
    $('#phone_call_button').live('click',function(e)
    {
        //$("#find_doctor_modal").dialog("close");
        var info = doctor_to_connect.split("_");
        $('#find_doctor_modal').dialog('option', 'title', 'Calling Center ' /*+ info[2]*/);
        $("#Talk_Section_2").fadeOut("Slow", function(){$("#Talk_Section_4").fadeIn("slow");});
        $.post("start_telemed_phonecall.php?stage=init-call", {pat_phone: $("#USERPHONE").val(), doc_phone: info[1], doc_id: info[0], pat_id: $("#USERID").val(), doc_name: (info[2] + ' ' + info[3]), pat_name: ($("#USERNAME").val() + ' ' + $("#USERSURNAME").val())}, function(data, status)
        {
            console.log($("#USERPHONE").val()+info[1]+info[0]+$("#USERID").val()+info[2] + ' ' + info[3]+$("#USERNAME").val() + ' ' + $("#USERSURNAME").val());
        });
    });
    $('#video_call_button').live('click',function(e)
     {
        $("#find_doctor_modal").dialog("close");
         var info = doctor_to_connect.split("_");
        $.post("start_telemed_videocall.php", {pat_phone: $("#USERPHONE").val(), doc_phone: info[1], doc_id: info[0], pat_id: $("#USERID").val(), doc_name: (info[2] + ' ' + info[3]), pat_name: ($("#USERNAME").val() + ' ' + $("#USERSURNAME").val())}, function(data, status)
        {
            console.log(data);
            if(data == 1)
            {
                var info = doctor_to_connect.split("_");
                window.open("telemedicine_patient.php?MED=" + info[0] + "&PAT=" + $("#USERID").val(),"Telemedicine","height=650,width=700,left=100,top=100,resizable=yes,scrollbars=yes,toolbar=yes,menubar=no,location=no,directories=no,status=yes");
            }
            else
            {
                alert("There was an error. Please try again later");
            }
        });
     });

    $('#compose_message').live('click',function(){
        $('#messagecontent_inbox').attr('value','');
        $('#ToDoctor').attr('value','To: ');
        $('#subjectname_inbox').attr('value','');
        $('#subjectname_inbox').removeAttr("readonly");   
        $('#messagedetails').hide();
        $('#replymessage').show();
        $("#attachments").empty();
        $('#attachments').hide();
        $('#Reply').hide();
        $("#CloseMessage").hide();
        $('#Attach').hide();
        $('#sendmessages_inbox').show();
        $('#attachreports').show();
        $('#message_modal').trigger('click');
   });
                              
    $('#sendmessages_inbox').live('click',function(){
         var IdPaciente = <?php echo $UserID;?>;
         var FullNamePaciente = $('#NombreComp').html();
         //alert (FullNamePaciente);
         IdDoctor = $("#IdDoctor").attr('value');
         var content = $('#messagecontent_inbox').val();
         var subject=$('#subjectname_inbox').val();;
         //reportids = reportids.replace(/\s+$/g,' ');
         var IdDocOrigin = IdDoctor;
         var IdDoctor = IdDoctor;
         var Receiver = 0;
         reportids = ' ';
         //alert ('IdPaciente: '+IdPaciente+' - '+'Sender: '+IdDocOrigin+' - '+'Attachments: '+reportids+' - '+'Receiver: '+IdDoctor+' - '+'Content: '+content+' - '+'subject: '+subject+' - '+'connection_id: 0');
         var cadena='sendMessageUSER.php?sender='+IdDocOrigin+'&receiver='+IdDoctor+'&patient='+IdPaciente+''+'&content='+content+'&subject='+subject+'&attachments='+reportids+'&connection_id=0&tofrom=to';
         var RecTipo=LanzaAjax(cadena);
         
         $('#messagecontent_inbox').attr('value','');
         $('#subjectname_inbox').attr('value','');
         displaynotification('status',RecTipo);
         getUserData(IdPaciente);
         var UserName = user[0].Name;
         var UserSurname = user[0].Surname; 
         
         getMedCreator(IdDoctor);
         var NameMed = doctor[0].Name;
         var SurnameMed = doctor[0].Surname; 
         
         var cadena='push_serverUSER.php?FromDoctorName='+UserName+'&FromDoctorSurname='+UserSurname+'&FromDoctorId='+IdDoctor+'&Patientname='+UserName+'&PatientSurname='+UserSurname+'&IdUsu='+IdPaciente+'&message= New Message <br>From: '+UserName+' '+UserSurname+' <br>Subject: '+(subject).replace(/RE:/,'')+'&NotifType=1&channel='+IdDoctor+'&Selector=2';
         //alert(cadena);
         var RecTipo=LanzaAjax(cadena);
         
         //}
         reportids='';
         $("#attachment_icon").remove();
    
        
        //alert ('Answer of Messg Proc.?: '+RecTipo);
         $('#message_modal').trigger('click');  //Close the modal window
});
     
    $('.CFILA').live('click',function(){
       var id = $(this).attr("id");
       // Get Doctor id and some info
       var cadena='getDoctorMessage.php?msgid='+id;
       var RecTipo=LanzaAjax(cadena);
       var n = RecTipo.indexOf(",");
	   var IdDoctor = RecTipo.substr(0,n);
       var Remaining = RecTipo.substr(n+1,RecTipo.length);
       m = Remaining.indexOf(",");
	   var NameDoctor = Remaining.substr(0,m);
       var SurnameDoctor = Remaining.substr(m+1,Remaining.length);
       $("#IdDoctor").attr('value',IdDoctor);
       //throw "stop execution";
       
       //displaynotification('Message ID'+ id);
	   
       
       $('input[type=checkbox][id^="reportcol"]').each(
        function () {
		 $('input[type=checkbox][id^="reportcol"]').checked=false;
		});
	   reportcheck.length=0;
	   var content=$(this).find('span#'+id).text().replace(/br8k/g,"\n").replace(/sp0e/g," ");
	   $(this).find('span').hide();
	   var reportsattached=$(this).find('ul#'+id).text();
	   
       //alert(reportsattached);
	   
       $("#attachments").empty();
	   if(reportsattached){
               //alert("into attachments");
               var ElementDOM ='All';
               var EntryTypegroup ='0';
               var Usuario = $('#userId').val();
               var MedID =$('#MEDID').val();
               //alert ('Usuario: '+Usuario+' MedID: '+MedID);
                var queUrl ='CreateAttachmentStream.php?ElementDOM=na&EntryTypegroup='+EntryTypegroup+'&Usuario='+Usuario+'&MedID='+MedID+'&Reports='+reportsattached;
                var RecTipo=LanzaAjax(queUrl);
                //alert(RecTipo);
                $("#attachments").append(RecTipo);
                /*$("#attachments").load(queUrl);
                $("#attachments").trigger('update');*/
                $("#attachments").show();
		}else{
                $('#attachments').hide();
                //alert("no attachments");
		}
	   //content.replace(/[break]/g,"\n").replace(/[space]/g," ");
	   //alert($(this).find('a').text());
	   //displaynotification('Message read');
	   //$('#attachments').hide();
	   $('#Attach').hide();
	   $('#messagedetails').show();
       $("#ToDoctor").show();
       $('#ToDoctor').html('To: '+NameDoctor+' '+SurnameDoctor);
       $('#replymessage').hide();
	   $("#Reply").attr('value','Reply');
       $("#Reply").show();
       $("#CloseMessage").show();
	   $('#messagedetails').val(content);
	   $('#messagedetails').attr('readonly','readonly');
	   $('#messagedetails,#subjectname_inbox').css("cursor","pointer");
	   $('#subjectname_inbox').val($(this).find('a').text());
	   $('#subjectname_inbox').attr('readonly','readonly');
	   $('#replymessage').hide();
	   $('#sendmessages_inbox').hide();
	   $('#attachreports').hide();
	   $('#message_modal').trigger('click');
	   var cadena='setMessageStatusUSER.php?msgid='+id;
	   var RecTipo=LanzaAjax(cadena);
	   /*setTimeout(function(){
	   $('#newinbox').trigger('click');},1000);*/
	   
   });
        
    $('.CFILA_out').live('click',function(){
       var id = $(this).attr("id");
	   //displaynotification('Message ID'+ id);
	   $('input[type=checkbox][id^="reportcol"]').each(
        function () {
		 $('input[type=checkbox][id^="reportcol"]').checked=false;
		});
	   reportcheck.length=0;
	   var content=$(this).find('span#'+id).text().replace(/br8k/g,"\n").replace(/sp0e/g," ");
	   $(this).find('span').hide();
	   var reportsattached=$(this).find('ul#'+id).text();
	   //alert(reportsattached);
	   $("#attachments").empty();
	   if(reportsattached){
	   //alert("into attachments");
	   var ElementDOM ='All';
	   var EntryTypegroup ='0';
	   var Usuario = $('#userId').val();
	   var MedID =$('#MEDID').val();
	   
		var queUrl ='CreateAttachmentStream.php?ElementDOM=na&EntryTypegroup='+EntryTypegroup+'&Usuario='+Usuario+'&MedID='+MedID+'&Reports='+reportsattached;
	    var RecTipo=LanzaAjax(queUrl);
		//alert(RecTipo);
		$("#attachments").append(RecTipo);
      	/*$("#attachments").load(queUrl);
    	$("#attachments").trigger('update');*/
		$("#attachments").show();
		}else{
		$('#attachments').hide();
		//alert("no attachments");
		}
	   //content.replace(/[break]/g,"\n").replace(/[space]/g," ");
	   //alert($(this).find('a').text());
	   //displaynotification('Message read');
	   //$('#attachments').hide();
	   $('#Attach').hide();
	   $('#messagedetails').show();
       $('#replymessage').hide();
	   $("#Reply").attr('value','Reply');
       $("#Reply").hide();
       $("#CloseMessage").show();
	   $('#messagedetails').val(content);
	   $('#messagedetails').attr('readonly','readonly');
	   $('#messagedetails,#subjectname_inbox').css("cursor","pointer");
	   $('#subjectname_inbox').val($(this).find('a').text());
	   $('#subjectname_inbox').attr('readonly','readonly');
	   $('#replymessage').hide();
	   $('#sendmessages_inbox').hide();
	   $('#attachreports').hide();
	   //$('#clearmessage').hide();
	   $('#message_modal').trigger('click');
	   /*var cadena='setMessageStatus.php?msgid='+id;
	   var RecTipo=LanzaAjax(cadena);
	   setTimeout(function(){
	   $('#newoutbox').trigger('click');},1000);*/
	   
   });
        
    $("#Attach").live('click',function(){
     reportids='';
    
    $('input[type=checkbox][id^="reportcol"]').each(
     function () {
				var sThisVal = (this.checked ? "1" : "0");
				
				//sList += (sList=="" ? sThisVal : "," + sThisVal);
				if(sThisVal==1){
				 var idp=$(this).parents("div.attachments").attr("id");
				//alert("Id "+idp+" selected"); 
				reportcheck.push(this.id);
				 //messageid=messageid+idp+' ,';
				 reportids=reportids+idp+' ';
				
				 /*var cadena='setMessageStatus.php?msgid='+idp+'&delete=1';
				 LanzaAjax(cadena);*/
				}
			
				
	});
	 //alert(reportids);
	var conf=false;
	if(reportids>'')
		conf=confirm("Confirm Attachments");
	
	if(conf){
	$("#Reply").trigger('click');
	$("#attachreportdiv").append('<i id="attachment_icon" class="icon-paper-clip" style="margin-left:10px"></i>');
	//alert(reportids);
	}else{
	reportids='';
	for (i = 0 ; i < reportcheck.length; i++ ){
				
		document.getElementById(reportcheck[i]).checked = false;
				
	}
	reportcheck.length=0;
	$("#Reply").trigger('click');
	}
      
   });
   
    var isloaded=false;   //This variable is to make sure the page loads the report only once.
   
  
   
    $('#attachreports').live('click',function(){
   
    $('input[type=checkbox][id^="reportcol"]').each(
     function () {
				var sThisVal = (this.checked ? "1" : "0");
				if(sThisVal==1){
				reportcheck.push(this.id);
				}
				
	});
	/*if(!isloaded){
	//$('#attachments').remove();*/
	$("#attachments").empty();
	createPatientReports();
	//isloaded=true;
	//}
	setTimeout(function(){
	for (i = 0 ; i < reportcheck.length; i++ ){
				
		document.getElementById(reportcheck[i]).checked = true;
				
	}},400);
   $("#attachment_icon").remove();
   $('#sendmessages_inbox').hide();
   $('#replymessage').hide();
   $(this).hide();   
   $('#attachments').show();
   $('#Attach').show();
   $("#Reply").attr('value','Back');
   $("#Reply").show();
   
   
   });
      
    $("#Reply").live('click',function(){
       var subject_name='RE:'+($('#subjectname_inbox').val()).replace(/RE:/,'');
       $('#ToDoctor').show();
       $('#ToDoctor').attr('value','To: ');
       $('#subjectname_inbox').val(subject_name);   
       $('#messagedetails').hide();
       $('#replymessage').show();
       $('#attachments').hide();
       $('#Attach').hide();
       $(this).hide();
       $("#CloseMessage").hide();
       $('#sendmessages_inbox').show();
       $('#attachreports').show();
   });

     
   
        
        
        
        
        
        
    $("#SearchUser").typeWatch({
				captureLength: 1,
				callback: function(value) {
					$("#BotonBusquedaPac").trigger('click');
					//alert('searching');
				}
	});
	$("#RetrievePatient").click(function(event) {
   		 $('#BotonBusquedaPac').trigger('click');
   	});
    $("#BotonBusquedaPac").click(function(event) {
     		var queMED = $("#MEDID").val();
    	    var UserInput = $('#SearchUser').val();
    	    //alert(UserInput);
    	    /*
    	    var IdUs =156;
    	    var UserInput = $('#SearchUser').val();
                                  
    	    var serviceURL = 'http://www.health2.me/getpines.php' + '?Usuario=' + UserInput; 
    	    getLifePines(serviceURL);
    	    var longit = Object.keys(pines).length;	
    	    */
    	    //alert (longit);
    	     
			var onlyGroup=0;
			if ($('#RetrievePatient').is(":checked")){
			onlyGroup=1;
			}else{
			onlyGroup=1;
			}
		   
    	     if(UserInput===""){
			    UserInput=-111;
			 }
			// alert(UserInput);
    	    var queUrl ='getFullUsersMED.php?Usuario='+UserInput+'&IdMed='+queMED+'&Usuario='+UserInput+'&Group='+onlyGroup;
			//alert(queUrl);
    	    $('#TablaPac').load(queUrl);
    	    //$('#TablaPac').trigger('click');
    	    $('#TablaPac').trigger('update');
  	    
    });

    $("#BotonBusquedaPacCOMP").click(function(event) {
    	    var IdUs =156;
    	    var UserInput = $('#SearchUserYCOMP').val();
                                  
    	    var serviceURL = '<?php echo $domain;?>/getpines.php' + '?Usuario=' + UserInput; 
    	    getLifePines(serviceURL);
    	    var longit = Object.keys(pines).length;	
    	    //alert (longit);
    	    var queUrl ='getFullUsers.php?Usuario='+UserInput+'&NReports='+longit;
    	    $('#TablaPacCOMP').load(queUrl);
    	    //$('#TablaPac').trigger('click');
    	    $('#TablaPacCOMP').trigger('update');
    });
	
	// function connectTelemedicine()
	// {
		// $.ajax({
		 // url: '<?php echo $domain?>/weemo_test.php?calleeID=<?php echo $DoctorEmail?>',
		 // method: 'get',
			// success: function(data){
		    // var htmlContent = data;
			// var e = document.createElement('div');
			// e.setAttribute('style', 'display: none;');
			// e.innerHTML = htmlContent;
			// document.body.appendChild(e);
			// console.log(document.getElementById("runscript").innerHTML);
			// eval(document.getElementById("runscript").innerHTML);
			 // }
		 // });
	// }

	// $("#Telemedicine").on('click',function() {
			// connectTelemedicine();
		 // });

	$('#BotPassbook').live('click',function(){
	  $("#modalContents").dialog({bgiframe: true, height: 1000, height: 340, modal: true});
	});

	$('#ButtonSkype').live('click',function(){
	 $('#modalContents').dialog( "close" );
	});

	$('#ButtonWeemo').live('click',function(){
		$.ajax({
			 url: '<?php echo $domain?>/weemo_test.php?calleeID=<?php echo $DoctorEmail;?>',
			 method: 'get',
				success: function(data){
				var htmlContent = data;
				var e = document.createElement('div');
				e.setAttribute('style', 'display: none;');
				e.innerHTML = htmlContent;
				document.body.appendChild(e);
				eval(document.getElementById("runscript").innerHTML);
			  }
		 });
		$('#modalContents').dialog( "close" );
	});
     
	$('#telemedicine').live('click',function(){
	 // e.preventDefault();
	  $("#modalContents").dialog({bgiframe: true, height: 140, modal: true});
	 });
     /*
     $('#TablaPac').bind('click', function() {
          	
          	var NombreEnt = $('#NombreEnt').val();
            var PasswordEnt = $('#PasswordEnt').val();
                                   
          	window.location.replace('patientdetail.php?Nombre='+NombreEnt+'&Password='+PasswordEnt);

//      alert($(this).text());
      });
*/

     $('#TablaPacCOMP').bind('click', function() {
      alert($(this).text());
      });

	    
 	$('#datatable_1').dataTable( {
		"bProcessing": true,
		"bDestroy": true,
		"bRetrieve": true,
		"sAjaxSource": "getBLOCKS.php"
	} );
    
    $('#Wait1')
    .hide()  // hide it initially
    .ajaxStart(function() {
        //alert ('ajax start');
        $(this).show();
    })
    .ajaxStop(function() {
        $(this).hide();
    }); 

    $('#datatable_1 tbody').click( function () {
    // Alert the contents of an element in a SPAN in the first TD    
    alert( $('td:eq(0) span', this).html() );
    } );
 
    });        
    
     
    function getLifePines(serviceURL) {
    $.ajax(
           {
           url: serviceURL,
           dataType: "json",
           async: false,
           success: function(data)
           {
           pines = data.items;
           }
           });
     }        

	window.onload = function(){		
		
		var quePorcentaje = $('#quePorcentaje').val();
		var g = new JustGage({
			id: "gauge", 
			value: quePorcentaje, 
			min: 0,
			max: 100,
			title: " ",
			label: "% Refered to me"
		}); 
	};
	
	  

  
    function TranslateAngle(x,maxim){
	    var y = (x * Math.PI * 2) / maxim;
	    return parseFloat(y);
    }
    
    function hexToRgb(hex) {
    var result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
    return result ? {
        r: parseInt(result[1], 16),
        g: parseInt(result[2], 16),
        b: parseInt(result[3], 16)
    } : null;
	}
	
	function GetICD10Code(searchW)
	{		
		var queUrl ='https://api.aqua.io/codes/beta/icd10.json?utf8=%E2%9C%93&q%5Bdescription_cont%5D='+searchW;	
		var ICDCodes = '';	
		var ICDArr = Array();	
		$.ajax({
			dataType: "json",
			url: queUrl,
			async:false,
			success: function(ajaxresult)
			{
				ICDCodes = ajaxresult;
				var ICDArr = ajaxresult[0];	
			},
            error: function(data, errorThrown){
               alert(errorThrown);
              }
         });
		return ICDCodes;
	}

    
    function GetCanvasTextHeight(text,font){
    var fontDraw = document.createElement("canvas");

    var height = 100;
    var width = 100;

    // here we expect that font size will be less canvas geometry
    fontDraw.setAttribute("height", height);
    fontDraw.setAttribute("width", width);

    var ctx = fontDraw.getContext('2d');
    // black is default
    ctx.fillRect(0, 0, width, height);
    ctx.textBaseline = 'top';
    ctx.fillStyle = 'white';
    ctx.font = font;
    ctx.fillText(text/*'Eg'*/, 0, 0);

    var pixels = ctx.getImageData(0, 0, width, height).data;

    // row numbers where we first find letter end where it ends 
    var start = -1;
    var end = -1;

    for (var row = 0; row < height; row++) {
        for (var column = 0; column < width; column++) {

            var index = (row * width + column) * 4;

            // if pixel is not white (background color)
            if (pixels[index] == 0) {
                // we havent met white (font color) pixel
                // on the row and the letters was detected
                if (column == width - 1 && start != -1) {
                    end = row;
                    row = height;
                    break;
                }
                continue;
            }
            else {
                // we find top of letter
                if (start == -1) {
                    start = row;
                }
                // ..letters body
                break;
            }

        }

    }
   /*
    document.body.appendChild(fontDraw);
    fontDraw.style.pixelLeft = 400;
    fontDraw.style.pixelTop = 400;
    fontDraw.style.position = "absolute";
   */

    return end - start;
    };
	
	function findPos(obj) {
	    var curleft = 0, curtop = 0;
	    if (obj.offsetParent) {
	        do {
	            curleft += obj.offsetLeft;
	            curtop += obj.offsetTop;
	        } while (obj = obj.offsetParent);
	        return { x: curleft, y: curtop };
	    }
	    return undefined;
	}

	function rgbToHex(r, g, b) {
	    if (r > 255 || g > 255 || b > 255)
	        throw "Invalid color component";
	    return ((r << 16) | (g << 8) | b).toString(16);
	}
				 		
//Start of code inserted from userdashboard-new-pallab.php
    // Start of code for display of modal window for Send button
    $("#Send2Doc").on('click',function(){
		$("#modal_send").dialog({bigframe: true, width: 400, height: 200, modal: false});
	});
    //End of code for display of modal window of Send button
    
    //Start of code for OK button in Send page
    $("#CaptureEmail2Send2Doc").on('click',function(){
		var emailId = $("#EmailID").val();
        var user = <?php echo $UserID; ?>;
        $.get("ValidateSendEmailDoc.php?emailId="+emailId+"&user="+user,function(data,status)
              {
              console.log(data);
              });
        
	$("#modal_send").dialog("close");
    });
    //End of code  for OK button in Send page
        
    //Start of code for display of modal window for Request button
    $("#Request").on('click',function(){
		$("#modal_request").dialog({bigframe: true, width: 550, height: 300, modal: false});
	});
    //End of code for display of modal windown for Request button
    
    // Start of Code for Request Reports button
    $("#CaptureEmail2Request2Doc").on('click',function(){
        
        var emailId = $("#EmailIDRequestPage").val();
        var messageForDoc = "'"+$("#MessageForDoctor").val()+"'";
        var user = <?php echo $UserID; ?>;
        $.get("RequestReportsFromExternalDoc.php?emailId="+emailId+"&user="+user+"&message="+messageForDoc,function(data,status)
              {
              //alert(data);
              });
        $("#modal_request").dialog("close");
        });
    // End of code for Request Reports button
    // End of code inserted from userdashboard-new-pallab.php	

</script>

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
