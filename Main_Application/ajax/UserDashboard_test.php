<?php
session_start();
 require("environment_detail.php");
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
$CustomLook=$_SESSION['CustomLook'];
if ($CustomLook=='COL') {
    header('Location:UserDashboardHTI.php');
    echo "<html></html>";  // - Tell the browser there the page is done
    flush();               // - Make sure all buffers are flushed
    ob_flush();            // - Make sure all buffers are flushed
    exit;                  // - Prevent any more output from messing up the redirect
}       
if ($Acceso != '23432')
{
echo "Incorrect credentials for login";
echo "<br>\n"; 	
echo "<h2><a href='".$domain."'>Return to Health2me Dashboard</a></h2>";
die;
}

					// Connect to server and select databse.
$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }		

$result = $con->prepare("SELECT * FROM usuarios where Identif=?");
$result->bindValue(1, $UserID, PDO::PARAM_INT);
$result->execute();

$count = $result->rowCount();
$row = $result->fetch(PDO::FETCH_ASSOC);
$success ='NO';
if($count==1){
	$success ='SI';
	
   /* $MedID = $row['id'];
	$MedUserEmail= $row['IdMEDEmail'];
	$MedUserName = $row['Name'];
	$MedUserSurname = $row['Surname'];
	$MedUserLogo = $row['ImageLogo'];
	$IdMedFIXED = $row['IdMEDFIXED'];
	$IdMedFIXEDNAME = $row['IdMEDFIXEDNAME']; */

    $UserID = $row['Identif'];
	$UserEmail= (htmlspecialchars($row['email']));
    $UserName = (htmlspecialchars($row['Name']));
    $UserSurname = (htmlspecialchars($row['Surname']));
    $UserPhone = (htmlspecialchars($row['telefono']));
    //$UserLogo = $row['ImageLogo'];
    $IdUsFIXED = $row['IdUsFIXED'];
    $IdUsFIXEDNAME = $row['IdUsFIXEDNAME'];
    $Timezone = $row['timezone'];
    $Place = (htmlspecialchars($row['location']));
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
    $NameDoctor = (htmlspecialchars($rowD['Name']));
    $SurnameDoctor = (htmlspecialchars($rowD['Surname']));
	$DoctorEmail = (htmlspecialchars($rowD['IdMEDEmail']));
    //$MedUserRole = $row['Role'];
	//if ($MedUserRole=='1') $MedUserTitle ='Dr. '; else $MedUserTitle =' ';
    
       

    
    
}
else
{
echo "USER NOT VALID. Incorrect credentials for login";
echo "<br>\n"; 	
echo "<h2><a href='".$domain."'>Return to Health2me Dashboard</a></h2>";
die;
}


//BLOCKSLIFEPIN $result = mysql_query("SELECT * FROM blocks");
$result = $con->prepare("SELECT * FROM lifepin");
$result->execute();

?>
<!DOCTYPE html>
<html style="background: #F9F9F9;"><head>
    <meta charset="utf-8">
    <title lang="en">Inmers - Center Management Console</title>
    <link rel="icon" type="image/ico" href="favicon.ico"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
    <link href="css/style.css" rel="stylesheet">
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
 <!--   <link href="//netdna.bootstrapcdn.com/font-awesome/4.0.0/css/font-awesome.css" rel="stylesheet"> -->
    <link rel="stylesheet" href="css/bootstrap-responsive.css">
	<link rel="stylesheet" href="css/toggle-switch.css">
    <link rel="stylesheet" href="css/doctor_styles.css">
    <link rel="stylesheet" href="build/css/intlTelInput.css">
	
    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    <!--[if lte IE 8]><script type="text/javascript" src="/js/excanvas.min.js"></script><![endif]-->
 
    <!-- Le fav and touch icons -->
    <link rel="shortcut icon" href="/images/icons/favicon.ico">
	
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
setCookie('lang', 'en', 30);
}
</script>
	
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

<!--Start of Zopim Live Chat Script-->
<script type="text/javascript">
window.$zopim||(function(d,s){var z=$zopim=function(c){z._.push(c)},$=z.s=
d.createElement(s),e=d.getElementsByTagName(s)[0];z.set=function(o){z.set.
_.push(o)};z._=[];z.set._=[];$.async=!0;$.setAttribute('charset','utf-8');
$.src='//v2.zopim.com/?2MtPbkSnwlPlIVIVYQMfPNXnx6bGJ0Rj';z.t=+new Date;$.
type='text/javascript';e.parentNode.insertBefore($,e)})(document,'script');
</script>
<!--End of Zopim Live Chat Script-->  
    
    
  </head>

  <body style="background: #F9F9F9;">
    <!-- MODAL VIEW FOR DOCTOR DIRECTORY -->
    <!-- STYLES FOR THIS MODAL WINDOW ARE IN css/doctor_styles.css -->
    <!-- JAVSCRIPT CODE FOR THIS MODAL WINDOW IS IN js/doctor_search.js -->
    <div id="search_modal" title="Search Doctors" style="display:none; text-align:center; width: 900px; height: 700px; overflow: hidden;">
        <div style="width: 100%; height: 30px; margin-left: 15px;">
            <div style="float: left; margin-bottom: -25px; margin-top: 10px;">
                <button class="sort_button" id="name_button" style="border-top-left-radius: 5px; border-bottom-left-radius: 5px;" lang="en">
                   Name
                    <i class="icon-caret-up" style="margin-left: 3px;"></i>
                </button>
                <button class="sort_button" style="border-top-right-radius: 5px; border-bottom-right-radius: 5px;" id="rating_button" lang="en">
                   Rating
                    <i class="" style="margin-left: 3px;"></i>
                </button>
            </div>
            <div style="width: 670px; float: left; margin-left: 15px; margin-top: 10px; margin-bottom: -10px;">
                <div class="controls" style="float: left; width: 350px;">
                    <!--<div class="input-append">-->
                        <input class="span7" id="search_bar" style="float: left; width: 270px; border-top-right-radius: 0px; border-bottom-right-radius: 0px;" size="16" type="text">
                        <button class="search_bar_button" style="float: left;" id="search_bar_button" lang="en">Search</button>
                    <!--</div>-->
                </div>
                <div style="float: left; width:140px; margin-left: 10px;">
                    <div class="search_toggle"  style="width: 140px;" lang="en">
                        <button id="telemedicine_toggle_button" class="search_toggle_button" style="background-color: #22AEFF;" data-on="true" ></button>
                        Telemedicine
                    </div>
                </div>
                <div style="float: left; width:100px; margin-left: 20px;">
                    <div class="search_toggle" lang="en">
                        <button id="available_toggle_button" class="search_toggle_button" data-on="false" ></button>
                        Available
                    </div>
                </div>
                <div style="float: left; width:30px; margin-left: 20px; font-size: 20px;">
                    <button id="advanced_toggle_button" class="doctor_search_advanced_toggle_button" >
                        <i class="icon-cog" ></i>
                    </button>
                </div>
            </div>
            
        </div>
        <div id="doctor_search_advanced" style="width: 100%; margin-top: 50px; display: none;">
            <div style="width: 360px; height: 50px; margin: auto;">
            
                <div style="width: 100%; height; 25px; padding-top: 5px; margin-left: 15px; color: #767676; text-align: center;">
                    Speciality: 
                </div>
                <select name="search_speciality" id="search_speciality" style="float: left; width: 360px; margin-left: 15px;">
                    <option value="Any" selected>Any</option>
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
                    <option value="General Practice">General Practicioner</option>
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
            <div style="width: 360px; margin: auto; height: 50px; margin-top: 25px;">
            
                <div style="width: 100%; height; 25px; padding-top: 5px; margin-left: 15px; color: #767676; text-align: center;" lang="en">
                    Country: 
                </div>
                <select id="country_search" name ="country_search" style="width: 360px; margin-left: 15px;"></select>
                </select>
            </div>
            <div style="width: 360px; margin: auto; height: 50px; margin-top: 25px;">
            
                <div style="width: 100%; height; 25px; padding-top: 5px; margin-left: 15px; color: #767676; text-align: center;" lang="en">
                    Region: 
                </div>
                <select id="region_search" name ="region_search" style="width: 360px; margin-left: 15px;"></select>
                </select>
            </div>
            <div style="width: 215px; margin: auto; margin-top: 30px;">
                <button class="doctor_search_advanced_button" lang="en">Update</button>
                <button class="doctor_search_advanced_button" style="margin-left: 15px;" lang="en">Reset</button>
            </div>
            <script type= "text/javascript" src = "js/countries.js"></script>
            <script language="javascript">
                populateCountries("country_search", "region_search");
            </script>
        </div>
        <div id="doctor_rows" style="width: 100%; margin-top: 30px; height: 510px;">
            <!--<div class="doctor_row">
                <img class="doctor_pic" src="identicon.php?size=25&hash='<?php echo md5( strtolower( trim( 'blima@inmers.us' ) ) ); ?>'" />
                <div class="doctor_main_label">
                    <div class="doctor_name"><span style="color: #22AEFF">Bruno</span> <span style="color: #00639A">Lima</span></div>
                    <div class="doctor_speciality">General Practicioner</div>
                    <div class="doctor_location">Texas, USA</div>
                </div>
                <div class="doctor_hospital_info">
                    <div class="doctor_stars">
                        <i class="icon-star" style="float: left; font-size: 12px; color: #666;"></i>
                        <i class="icon-star" style="float: left; font-size: 12px; color: #666;"></i>
                        <i class="icon-star" style="float: left; font-size: 12px; color: #666;"></i>
                        <i class="icon-star" style="float: left; font-size: 12px; color: #666;"></i>
                        <i class="icon-star-half" style="float: left; font-size: 12px; color: #666;"></i>
                    </div>
                    <div class="doctor_hospital_name">
                        Baylor Medical Center
                    </div>
                    <div class="doctor_hospital_address">
                        1000 Super Rd. Dallas, TX 75001
                    </div>
                </div>
            </div>
            <div class="doctor_row">
            
            </div>
            <div class="doctor_row">
            
            </div>
            <div class="doctor_row">
            
            </div>
            <div class="doctor_row">
            
            </div>
            <div class="doctor_row">
            
            </div>
            <div class="doctor_row">
            
            </div>
            <div class="doctor_row">
            
            </div>
            <div class="doctor_row">
            
            </div>
            <div class="doctor_row">
            
            </div>
            -->
        </div>
        <div id="doctors_search_page_buttons" style="width: 80px; height: 50px; margin-top: 25px; margin-left: auto; margin-right: auto; display: none;">
            <button id="doctors_page_button_left" class="doctors_page_button" disabled>
                <i class="icon-arrow-left"></i>
            </button>
            <button id="doctors_page_button_right" class="doctors_page_button" style="margin-left: 20px" disabled>
                <i class="icon-arrow-right"></i>
            </button>
        </div>
    </div>
    <!-- END MODAL VIEW FOR DOCTOR DIRECTORY -->
      
      
    <!-- MODAL VIEW FOR SUMMARY -->
    <div id="summary_modal" lang="en" title="Summary" style="display:none; text-align:center; width: 1000px; height: 660px; overflow: hidden;">
    </div>
    <!-- END MODAL VIEW FOR SUMMARY -->
      
    <!-- MODAL VIEW FOR SETUP -->
    <div id="setup_modal" title="SetUp" style="display:none; text-align:center; padding:20px; width: 650px; height: 310px;">
        <div id="setup_modal_container" style="width: 600px; height: 280px;">
            <h4 id="setup_title" lang="en">Subscription Management</h4>
            <div style="width: 15%; height:250px; float: left; margin-top: -30px;">
                <button id="setup_menu_1" accesskey=""style="background-color: #22AEFF; color: #FFF; width: 50px; height: 50px; font-size: 24px; border: 0px solid #FFF; outline: 0px; border-top-left-radius: 10px; border-top-right-radius: 10px; float: left;">
                    <i class="icon-lock"></i>
                </button>
                <button id="setup_menu_2" style="background-color: #FBFBFB; color: #22AEFF; width: 50px; height: 50px; font-size: 24px; border: 1px solid #E6E6E6; outline: 0px; float: left;">
                    <i class="icon-time"></i>
                </button>
                <button id="setup_menu_3" style="background-color: #FBFBFB; color: #22AEFF; width: 50px; height: 50px; font-size: 24px; border: 1px solid #E6E6E6; outline: 0px; float: left;">
                    <i class="icon-credit-card"></i>
                </button>
                <button id="setup_menu_4" style="background-color: #FBFBFB; color: #22AEFF; width: 50px; height: 50px; font-size: 24px; border: 1px solid #E6E6E6; outline: 0px; float: left;">
                    <i class="icon-map-marker"></i>
                </button>
                <button id="setup_menu_5" style="background-color: #FBFBFB; color: #22AEFF; width: 50px; height: 50px; font-size: 24px; border: 1px solid #E6E6E6; outline: 0px; border-bottom-left-radius: 10px; border-bottom-right-radius: 10px; float: left;">
                    <i class="icon-phone"></i>
                </button>
            </div>
            <div style="width: 85%; height: 200px; float: left;">
                <div id="setup_page_1">
                    <div id="individual_account" style="display: none">
                        <div style="text-align: center; width: 100%; height: 40px;">
                            <div style="max-width: 400px; float:none;display:block; margin-top: 20px; margin-bottom: -6px; color: #777; text-align: center;" lang="en">Your subscription is an <span style="color:#22aeff">Individual</span> account. </div>
                            <div style="display:block; width: 400px;">
                                <button id="upgrade_account" style="padding:20px; margin-top: 20px; width: 150px; height: 120px; background-color: #52D859; color: #FFF; border-radius: 7px; border: 0px solid #FFF; outline: 0px;">Upgrade to Family Subscription</button>
                            </div> 
                        </div>    
                    </div>
                    <div id="family_account" style="display:none">
                        <div style="max-width: 400px; float:none;display:block; margin-top: 20px; margin-bottom: -6px; color: #777; text-align: center;" lang="en">Your subscription is a <span style="color:#22aeff">Family</span> account. </div>
                  
                    <div id="owner_member" style="text-align:left; display:block; margin:10px; margin-top:30px;">
                        <span style="margin-left:10px;">
                            Name Surname
                        </span>
                        <span style="margin-left:10px; color:#22aeff;">
                            Mother
                        </span>
                        <span style="margin-left:10px; color:#52D859;">
                            Owner
                        </span>
                        <span style="margin-left:10px;">
                            <button id="edit_member" style="vertical-align:middle;padding:0px;  width:60px; height:25px; background-color:#52D859; color: #FFF; border-radius:7px; border:0px solid #FFF; outline:0px;">Edit</button>
                        </span>    
                        <span style="margin-left:10px;">
                            <button id="delete_member" style="vertical-align:middle;padding:0px;  width:60px; height:25px; background-color:red; color: #FFF; border-radius:7px; border:0px solid #FFF; outline:0px;">Delete</button>
                        </span>    
                    </div>
                    <div id="add_member" style="vertical-align:bottom;display:block;">
                        <button id="add_member_button" style="margin-top:5px; padding:5px;  margin-left:205px; width:100px; height:50px; background-color:#52D859; color: #FFF; border-radius:7px; border:0px solid #FFF; outline:0px;">Add Member</button>
                    </div> 
                    </div>   
                    <div id="account_edit_member" style="display:block; border-style:solid; border-width:1px; border-color:#AAA; border-radius:7px;">
                        <div style="display:block;text-align: center; width: 100%; height: 40px;">
                            <div style="padding: 5px; max-width: 600px; float:none;display:block; margin-top:5px; margin-bottom:5px; color: #000; text-align: center;" lang="en">Edit Member</div></div>
                             <div style="display:block">  
                                <div style="display:inline-block; max-width:300px">
                                    <div class="formRow" style="text-align:left;margin-left: 0px; ">
                                    <label style="width:60px">Name</label><input type="text" id="member_name" name="member_name" style="width: 150px;" />   
                                    </div>
                                    <div class="formRow" style="text-align:left;margin-left: 0px;">
                                       <label style="width:60px">Surame</label><input type="text" id="member_surname" name="member_surname" style="width: 150px;" />
                                    </div>
                                    <div class="formRow" style="text-align:left;margin-left: 0px;">
                                        <label style="width:60px">DoB</label><input type="date" id="member_dob" name="member_dob" style="width: 150px;" />
                                    </div>
                                    
                                    <div class="formRow" style="text-align:left;margin-left: 0px;">
                                        <label style="width:60px">Gender</label><input type="text" id="member_gender" name="member_gender" style="width: 150px;" />
                                    </div>    
                                </div> 
                                <div style="display:inline-block; max-width:300px">
                                    <div class="formRow" style="text-align:left;margin-left: 0px;">
                                        <label style="width:80px">Password</label><input type="text" id="member_password" name="member_password" style="width: 150px;" />
                                    </div>    
                                    <div class="formRow" style="text-align:left;margin-left: 0px;">    
                                        <label style="width:80px">Password*</label><input type="text" id="member_password_check" name="member_password_check" style="width: 150px;" />
                                    </div>    
                                    <div class="formRow" style="text-align:left;margin-left: 0px;">    
                                        <label style="width:80px">email</label><input type="text" id="member_dob" name="member_dob" style="width: 150px;" />
                                    </div>    
                                    <div class="formRow" style="text-align:left;margin-left: 0px;">    
                                        <label style="width:80px">phone</label><input type="text" id="member_gender" name ="member_gender" style="width: 150px;" />
                                    </div>    
                                </div>
                            </div>    
                        <div style ="display:block; padding: 10px; text-align:right;">
                                <button id="cancel_edit_member" style="margin-top: 0px; width: 80px; height: 35px; background-color: red; color: #FFF; border-radius: 7px; border: 0px solid #FFF; outline: 0px;">Cancel</button>
                                <button id="save_edit_member" style="margin-top: 0px; width: 80px; height: 35px; background-color: #52D859; color: #FFF; border-radius: 7px; border: 0px solid #FFF; outline: 0px;">Save</button>
                        </div>      
                    </div>
                </div>
                <div id="setup_page_2" style="display: none;">
                    <select class="timezonepicker" id="timezone_picker" size="8" style="display:block; margin-top: 0px; width: 100%;">
                      <option value="-12.0">(GMT -12:00) Eniwetok, Kwajalein</option>
                      <option value="-11.0">(GMT -11:00) Midway Island, Samoa</option>
                      <option value="-10.0">(GMT -10:00) Hawaii</option>
                      <option value="-9.0">(GMT -9:00) Alaska</option>
                      <option value="-8.0">(GMT -8:00) Pacific Time (US &amp; Canada)</option>
                      <option value="-7.0">(GMT -7:00) Mountain Time (US &amp; Canada)</option>
                      <option value="-6.0">(GMT -6:00) Central Time (US &amp; Canada), Mexico City</option>
                      <option value="-5.0" selected>(GMT -5:00) Eastern Time (US &amp; Canada), Bogota, Lima</option>
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
                    <div id="credit_cards_container" style="width: 70%; margin-left: auto; margin-right: auto; height: 135px; overflow: scroll;">
                        <!--<div class="credit_card_row" style="border-top-left-radius: 10px; border-top-right-radius: 10px;">
                            <button id="clear_credit_card" style="width: 18px; height: 18px; float: right; color: #F00; padding: 0px; background-color: inherit; border: 0px solid #FFF; border-radius: 3px; outline: 0px;">
                                <i class="icon-remove" style="width: 12px; height: 12px;"></i>
                            </button>
                            <img src="images/credit_card_icons/visa.png" style="float: left; margin-left: 10px; height: 38px;" />
                            <span style="float: left; color: #5A5A5A; font-size: 14px; margin-left: 60px; margin-top: 8px;">****   ****   ****   4377</span>
                        </div>
                        <div class="credit_card_row">
                            <button id="clear_credit_card" style="width: 18px; height: 18px; float: right; color: #F00; padding: 0px; background-color: inherit; border: 0px solid #FFF; border-radius: 3px; outline: 0px;">
                                <i class="icon-remove" style="width: 12px; height: 12px;"></i>
                            </button>
                            <img src="images/credit_card_icons/mastercard.png" style="float: left; margin-left: 10px; height: 38px;" />
                            <span style="float: left; color: #5A5A5A; font-size: 14px; margin-left: 60px; margin-top: 8px;">****   ****   ****   5231</span>
                        </div>
                        <div class="credit_card_row" style="border-bottom-left-radius: 10px; border-bottom-right-radius: 10px;">
                            <button id="clear_credit_card" style="width: 18px; height: 18px; float: right; color: #F00; padding: 0px; background-color: inherit; border: 0px solid #FFF; border-radius: 3px; outline: 0px;">
                                <i class="icon-remove" style="width: 12px; height: 12px;"></i>
                            </button>
                            <img src="images/credit_card_icons/amex.png" style="float: left; margin-left: 10px; height: 38px;" />
                            <span style="float: left; color: #5A5A5A; font-size: 14px; margin-left: 60px; margin-top: 8px;">****   ****   ****   7396</span>
                        </div>-->

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
                        <input id="credit_card_csv_code" type="text" onkeypress="return isNumberKey(event)" id="csv_code" maxLength="3" placeholder="CSV" style="width: 85px; height: 20px; margin-left: 18px; float: left; border-radius: 5px;">
                        <div style="color: #969696; width: 80px; float: left; text-align: left; padding-left: 5px; border-top-left-radius: 5px; border-bottom-left-radius: 5px; border: 1px solid #CACACA; height: 23px; padding-top: 5px; border-right: 0px solid #FFF;" lang="en">Exp. Date:</div>
                        <input id="credit_card_exp_date" type="month" style="width: 135px; height: 20px; float: left; font-size: 12px; border-radius: 0px; border-left: 0px solid #FFF; border-top-right-radius: 5px; border-bottom-right-radius: 5px;" />
                        <button id="add_card_button" style="width: 100px; height: 30px; background-color: #52D859; border-radius: 0px; border: 0px solid #FFF; color: #FFF; float: left; outline: 0px; margin-left: 18px; border-radius: 5px;" lang="en">Add Card</button>
                    </div>
                </div>
                <div id="setup_page_4" style="display: none;">
                    <div class="formRow" style="margin-left: 50px; margin-top: 20px;">
                        <label lang="en">Country: </label>
                        <div class="formRight">
                            <select id="country_setup" name ="country_setup"></select>
                        </div>
                    </div>
                    <div class="formRow" style="margin-left: 50px; visibility: hidden;">
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
            </div>
            
        </div>
        
        <div id="setup_modal_notification_container" style="width: 600px; height: 30px; opacity: 0.0;">
            <div id="setup_modal_notification" style="height: 30px; padding-top: 10px; width: 500px; background-color: #888; border-radius: 20px; margin-left: 50px; position: relative;"></div>
        </div>
    </div>
      
      
      <!-- MODAL VIEW TO FIND DOCTOR -->
    <div id="find_doctor_modal" title="Find Doctor" style="display:none; text-align:center; padding:20px;">
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
                    <button lang="en" id="find_doctor_now_button" class="square_blue_button<?php 
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
                    <button lang="en" id="find_doctor_my_doctors_button" class="square_blue_button<?php 
                        $res = $con->prepare("SELECT most_recent_doc FROM usuarios WHERE Identif=?");
						$res->bindValue(1, $UserID, PDO::PARAM_INT);
						$res->execute();
						
                        $row = $res->fetch(PDO::FETCH_ASSOC);
                        $str = $row['most_recent_doc'];
                        if(strlen($str) < 3)
                        {
                            echo "_disabled";
                        }?>">
                        <div style="margin-bottom: -8px;"><i class="icon-user-md" style="font-size: 40px;"></i></div>
                        <br/><span lang="en">My Doctors</span>
                    </button>
                    <button lang="en" id="find_doctor_appointment_button" class="square_blue_button">
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
                                    $time_interval = new DateInterval('PT'.intval(substr((htmlspecialchars($row2['timezone'])), strlen((htmlspecialchars($row2['timezone']))) - 8, 2)).'H'.intval(substr((htmlspecialchars($row2['timezone'])), strlen((htmlspecialchars($row2['timezone']))) - 5, 2)).'M');
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
                                echo '<button id="recdoc_'.$ids[$i].'_'.(htmlspecialchars($doc_row['phone'])).'_'.(htmlspecialchars($doc_row['Name'])).'_'.(htmlspecialchars($doc_row['Surname'])).'_'.(htmlspecialchars($doc_row['location']));
                                if($found)
                                {
                                    echo '_Available';
                                }
                                echo '" class="square_blue_button" style="width: 100px; height: 100px; margin-left: 3px; margin-right: 3px; padding: 0px;">Doctor<br/>'.(htmlspecialchars($doc_row['Name'])).' '.(htmlspecialchars($doc_row['Surname'])).'</button>';
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
                                <select id="country" name ="country"></select>
                            </div>
                        </div>
                        <div class="formRow" style="margin-left: 50px; display: none;">
                            <label lang="en">Region: </label>
                            <div class="formRight">
                                <select name ="state" id ="state"></select>
                            </div>
                        </div>
                    </div>
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
                    <p id="not_found_text" style="color: #FF3730; font-weight: bold; text-align: center;" lang="en">Sorry, we could not find<br/>any general practicioners in your area.</p>
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
                        <button class="days_button" id="mon" lang="en">Mon<br/>
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
                        <button class="days_button" id="tues" lang="en">Tues<br/>
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
                        <button class="days_button" id="wed" lang="en">Wed<br/>
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
                        <button class="days_button" id="thur" lang="en">Thur<br/>
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
                        <button class="days_button" id="fri" lang="en">Fri<br/>
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
                        <button class="days_button" id="sat" style="border-top-right-radius: 4px; border-bottom-right-radius: 4px;" lang="en">Sat<br/>
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
                        <li style="text-align: left;"><span lang="en">Receipt:</span> <strong>HTI - CR102388</strong></li>
                        <li style="text-align: left;" lang="en"><strong lang="en">Video Consultation</strong></li>
                        <li style="text-align: left;" lang="en">With a <strong>General Practicioner</strong></li>
                        <li style="text-align: left;" lang="en">next <strong>Thursday</strong> between <strong>12 and 2 pm</strong></li>
                    </ul>
                </div>
                <!-- End Appointment Pages -->
                
                <div style="width: 100%; height: 235px; padding-top: 15px; display: none;" id="find_doctor_confirmation">
                    <p style="color: #22AEFF; margin-top: 50px;" lang="en">
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
            <!--<div id="recent_doctors_section" style="display: block;">
                
                <span style="font-size: 16px; color: #555">Your Recent Doctors</span>
                <div id="recent_doctors" style="margin-bottom: 10px;">
                    <?php 
                        /*$result = mysql_query("SELECT most_recent_doc FROM usuarios where Identif='$UserID'");
                        $count=mysql_num_rows($result);
                        $row = mysql_fetch_array($result);
                        $str = $row['most_recent_doc'];
                        $str = str_replace(array("[", "]"), "", $str);
                        $ids = explode(",", $str);
                
                        date_default_timezone_set ("GMT");
                        $date = new DateTime('now');
                        for($i = 0; $i < count($ids); $i++)
                        {
                            $doc_result = mysql_query("SELECT Name,Surname,phone FROM doctors WHERE id=".$ids[$i]." AND telemed=1 AND in_consultation=0");


                            if(mysql_num_rows($doc_result) > 0)
                            {
                                $doc_row = mysql_fetch_array($doc_result);
                                $result2 = mysql_query("SELECT * FROM timeslots WHERE doc_id=".$ids[$i]);
                                $found = false;
                            
                                while(($row2 = mysql_fetch_assoc($result2)) && !$found)
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
                                if($found)
                                {
                                    echo '<button id="'.$ids[$i].'_'.$doc_row['phone'].'_'.$doc_row['Name'].'_'.$doc_row['Surname'].'" class="recent_doctor_button" style="';
                                    if($i == 0)
                                    {
                                        echo 'border-top-left-radius: 10px; border-top-right-radius: 10px; ';
                                    }
                                    if($i == (count($ids) - 1))
                                    {
                                        echo 'border-bottom-left-radius: 10px; border-bottom-right-radius: 10px; ';
                                    }
                                    echo '">Doctor '.$doc_row['Name'].' '.$doc_row['Surname'].'</button>';
                                    
                                    
                                    
                                }
                            }
                        }*/
                    ?>
                </div>
                
                <p style="text-align: center">Or</p>
                <br/>
            </div>
            <div style="width: 100%; height: 40px; margin-left: 15px;">
                <label style="float: left;">Find me a(n) </label>
                <select style="float: left; width: 72%; margin-top: -5px; margin-left: 20px;" name="speciality" id="speciality">
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
                    <option value="General Practice" selected>General Practice Doctor</option>
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
            <button style="width: 200px; heightL 30px; background-color: #22aeff; color: #FFF; border: 0px solid #FFF; margin: auto; margin-top: 15px; border-radius: 7px; outline: 0px;" id="find_doctor_button">Next</button>
-->
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
    	<input type="hidden" id="MEDID" Value="<?php if(isset($MedID)) echo $MedID; ?>">	
    	<input type="hidden" id="IdMEDEmail" Value="<?php if(isset($MedUserEmail)) echo $MedUserEmail; ?>">	
    	<input type="hidden" id="IdMEDName" Value="<?php if(isset($MedUserName)) echo $MedUserName; ?>">	
    	<input type="hidden" id="IdMEDSurname" Value="<?php if(isset($MedUserSurname)) echo $MedUserSurname; ?>">	
    	<input type="hidden" id="IdMEDSurname" Value="<?php if(isset($MedUserSurname)) echo $MedUserSurname; ?>">	
    	<input type="hidden" id="IdMEDLogo" Value="<?php if(isset($MedUserLogo)) echo $MedUserLogo; ?>">
        <input type="hidden" id="USERNAME" Value="<?php echo $UserName; ?>">	
        <input type="hidden" id="USERSURNAME" Value="<?php echo $UserSurname; ?>">	
        <input type="hidden" id="USERPHONE" Value="<?php echo $UserPhone; ?>">	
        <input type="hidden" id="CURRENTCALLINGDOCTOR" Value="<?php echo $current_calling_doctor; ?>">	
        <input type="hidden" id="CURRENTCALLINGDOCTORNAME" Value="<?php echo $current_calling_doctor_name; ?>" />
  		
           <a href="index.html" class="logo"><h1>Health2me</h1></a>
		   
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
					echo '<a href="dashboard.php" lang="en">';
				   else if($privilege==2)
					echo '<a href="patients.php" lang="en">';
			 ?>
			<i class="icon-globe"></i> <span lang="en">Home</span></a></li>
              
<!--              <li><a href="medicalConfiguration.php"><i class="icon-cog"></i> Settings</a></li>-->
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
<div id="modal_send" title= "Send Reports Link" style="display:none; text-align:center; padding:10px;">
    
        <form lang="en">
         Doctor's Email: <input type = "text" id="EmailID" width ="80" value='' />
        </form>
    
        <!-- add stream here -->
        <div style="width: 95%; margin: auto; height: 300px; overflow: scroll; text-align: center; margin-top: 30px; margin-bottom: 20px;" id="sendStreamContainer">
            <div style="width: 52px; height: 42px; margin-left: auto; margin-right: auto; margin-top: 100px;">
                <img src="images/load/29.gif"  alt="">
            </div>
            Loading Reports
        </div>
    
    
    
    
        <button id="CaptureEmail2Send2Doc" style="border:0px;border-radius:6px;height: 24px; width:50px; color:#FFF; background-color:#22aeff;float:bottom; margin-top:20px;" lang="en">Send</button>
    
</div>
<!-- End of code for displaying modal window for Send buttton-->

<!-- Start of code for displaying modal window for Request button -->

<div id="modal_request" title ="Request Reports" style="display:none; text-align:center; padding:10px;">
    
        <form>
         <span style="" lang="en">Email of Doctor</span> <input type = "text" id = "EmailIDRequestPage" style="width:250px">
        </form>
        
     <!--  <div style="text-align: top;margin-top:20px">
           <!--<form>-->
        <!--    <span style="" lang="en">Message</span> <textarea name = "Message" id = "MessageForDoctor" style="height:100px;width:300px;" ></textarea>      
           <!--</form>-->
       <!-- </div> -->
        
        <div> 
            <button id="CaptureEmail2Request2Doc"  style="border:0px;color:#FFF;border-radius:6px;height: 40px; width:138px;margin-top:20px;margin-right:40px;margin-left:40px;background-color:#22aeff;" lang="en">Request Reports</button>
        </div>
        
    
</div>

<!-- End of code for displaying modal window for Request button -->   
<!-- End of code inserted from userdashboard-new-pallab.php -->
 
   	 <!--- VENTANA MODAL  This has been added to show individual message content which user click on the inbox messages ---> 
   	 <button id="message_modal" data-target="#header-message" data-toggle="modal" class="btn btn-warning" style="display: none;" lang="en">Modal with Header</button> 
   	  <div id="header-message" class="modal hide" style="display: none;" aria-hidden="true">
         <div class="modal-header" lang="en">
             <button class="close" type="button" data-dismiss="modal">×</button>
                  <span lang="en">Message Details</span>
         </div>
         <div class="modal-body">
         <div class="formRow" style=" margin-top:-10px; margin-bottom:10px;">
             <span id="ToDoctor" style="color:#2c93dd; font-weight:bold;" lang="en">TO <?php echo 'Dr. '.$NameDoctor.' '.$SurnameDoctor; ?></span><input type="hidden" id="IdDoctor" value='<?php echo $IdDoctor; ?>'/>
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
        <!-- <input type="hidden" id="docId" value="<?php if(isset($IdMed)) echo $IdMed; ?>"/> -->
         <input type="hidden" id="userId" value="<?php if(isset($IdUsu)) echo $IdUsu; ?>" />
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
             <a href="#" class="btn btn-primary" data-dismiss="modal" id="CloseModal" lang="en" >Close</a>
             
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
				 echo '<li><a href="UserDashboard.php" class="act_link" lang="en">Dashboard</a></li>';
		 ?>
    	 <li><a href="patientdetailMED-new.php?IdUsu=<?php echo $UserID;?>"  lang="en">Medical Records</a></li>
 <!--        <li><a href="medicalConfiguration.php">Configuration</a></li>-->
         <li><a href="logout.php" style="color:yellow;" lang="en">Sign Out</a></li>
     </ul>

     
     </div>
     </div>
     <!--SpeedBar END-->
     
     <style>
        #tickerwrapper{margin:0px; padding:0px;width:300px;}
         #tickerwrapper ul li{padding:5px;text-align:left}
         #tickerwrapper .container{width:300px;height:60px;overflow:hidden; float:left;display:inline-block}  
         div.H2MInText{
                        padding:0px; 
                        font-size:12px; 
                        font-family: "Arial";
                        text-align:left; 
                        color:grey; 
                        display:table-cell; 
                        vertical-align:middle;
                        padding-left:20px;
                        width:50%;  
                          
                        }
                    
        div.H2MTray{
                        width:300px; 
                        height:50px; 
                        border:1px solid #cacaca; 
                        border-radius:5px; 
                        display:table;
                        margin:0px;
                        padding: 10px;
                        background-color: #F8F8F8;
                        float:right;
                        margin-bottom: 20px;
                        vertical-align: top;
                        margin-right: 50px;
                     }
     </style>     
     
     <!--CONTENT MAIN START-->
     <div class="content">
     <div class="grid" class="grid span4" style="width:1000px; height:675px; margin: 0 auto; margin-top:30px; padding-top:30px;">
 		     <div class="row-fluid" style="height:200px;">	            
                      <div style="margin:15px; padding-top:0px;">
                             <span class="label label-success" style="left:0px; margin-left:10px; margin-top:0px; font-size:30px;" lang="en">User Dashboard</span>
                             <div class="clearfix" style="margin-bottom:20px;"></div>
                                          <?php
                                          $hash = md5( strtolower( trim( $UserEmail ) ) );
                                          $avat = 'identicon.php?size=75&hash='.$hash;
                                            ?>	
                             <img src="<?php echo $avat; ?>" style="float:right; margin-right:40px; font-size:18px; padding:5px 10px 5px 10px; font-family: “Andale Mono”, AndaleMono, monospace; box-shadow: 3px 3px 15px #CACACA;"/>
                            <div style="display:inline-block; line-height: 200%">
                             <span id="NombreComp" style="font: bold 24px Arial, Helvetica, sans-serif; color: #3D93E0; cursor: auto; margin-left:20px; "><?php echo $UserName;?>  <?php echo $UserSurname;?></span>
                             <span id="IdUsFIXED" style="font-size: 12px; color: #3D93E0; font-weight: normal; font-family: Arial, Helvetica, sans-serif; display: block; margin-left:20px;"><?php echo $IdUsFIXED;?></span>
                             <span id="IdUsFIXEDNAME" style="font-size: 14px; color: GREY; font-weight: bold; font-family: Arial, Helvetica, sans-serif; margin-left:20px;"><?php echo $IdUsFIXEDNAME;?></span>
                             <span id="email" style="font-size: 14px; color: #5EB529; font-weight: bold; font-family: Arial, Helvetica, sans-serif; margin-left:20px;"><?php echo $UserEmail;?></span>
                            </div>
                    <!--<div class="H2MTray">
        

                        <div class="H2MInText" style="width:270px;">
                            
                            <div id="tickerwrapper">
                            <div class="container">
                                <ul>
                                    <li>You are connected to 2 doctors</li>
                                    <li>You have 18 records available</li>
                                    <li>View your patient summary here</li>
                                    <li>You have 2 upcoming appointments</li>
                                    <li>View your medical history here</li>
                                    <li>View updates here</li>
                                </ul>
                            </div>
                                
                            </div>
                            </div>

                <div style="width:40%; margin-right: 20px; margin-top:20px; text-align:right;display:inline-block"><span class="icon-bullhorn icon-2x"></span></div>    
                        </div>

                <div style="width:40%; margin-right: 20px; margin-top:20px; text-align:right;display:inline-block"></div>    
                        </div>-->

                             
                           
                     
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
                    <div style="margin-top:10px;"><div style="width: 78%; height: 30px; margin-top: -6px; float: right;"><a id="telemed_deny_button" href="javascript:void(0)" class="btn" title="Acknowledge" style="width:2%; color: red; margin-bottom: 2px;margin-left: 2%; float: right; padding-left: -15px;"><i id="' + items[i].ID + '" class="icon-remove"></i></a><a id="telemed_connect_button" href="javascript:void(0)" class="btn" title="Acknowledge" style="width:2%; color: green; margin-bottom: 2px; float: right; padding-left: -15px;"><i id="' + items[i].ID + '" class="icon-facetime-video"></i></a><p id="video_consultation_text" style="color: #FFF; height: 10px; font-size: 14px; margin-top: 4px;">Doctor Javier Vinals is calling you for a video consultation </p></div><input type="hidden" id="video_consultation_id" value="<?php if(isset($telemed)) echo $telemed; ?>" /><input type="hidden" id="video_consultation_name" value="<?php if(isset($telemed_name)) echo $telemed_name; ?>" /><span class="label label-info" id="EtiTML" style="background-color:#FFF; margin:20px; margin-left:0px; margin-bottom:0px; font-size:16px; text-shadow:none; text-decoration:none; color:#54bc00;" lang="en">Video Consultation</span></div>
                    
                </div>
            <!-- END TELEMED NOTIFICATOR -->
         
             <div style="float:left; height:440px; width:900px; margin: 30px auto; margin-top:-10px; margin-left:30px; margin-right:30px; padding:10px; border:1px solid #cacaca; font-size:0px; ">             
                <!-- Upper ROW  ************************************************** -->
                <div class="UpperRow" style="float:left; margin-top:-40px; width:855px; border:1px solid #cacaca; padding:10px; margin:10px; border-radius: 3px;">
             		<div style="margin-left:-10px; margin-top:-10px; margin-bottom:-10px; float:left; width:60px; height:260px; border-radius:0px;  border-top-left-radius: 2px; border-bottom-left-radius: 2px; background-color:#54bc00;">
             			<div style="width:180px;height:50px; position:relative; top:100px; left:-50px; -ms-transform:rotate(270deg); -moz-transform:rotate(270deg); -webkit-transform:rotate(270deg);-o-transform:rotate(270deg); color:white; font-size:24px;" lang="en">Health Passport</div>
	             	</div>
	             	
	             	<div style="float:left; width:; height:24px;  border:0px solid #cacaca; ">
		             	<div id="PHSLabelOrig" style="float:left; margin-left:23px; width:360px; height:22px;  border:0px solid #cacaca; text-align:center; font-size:18px; color: #54bc00;" lang="en">Personal Health Summary</div>
		             	<div id="MHRLabel" style="float:left; margin-left:20px; width:360px; height:22px;  border:0px solid #cacaca; text-align:center; font-size:18px; color: #54bc00;" lang="en">Medical History Reports</div>
	             	</div>	 	
            	   
	             	<div style="float:left; width:; height:;  border:0px solid #cacaca; ">
		             	<div style="float:left; width:; height:;  border:0px solid #cacaca; ">
			             	<canvas id="myCanvas" width="360" height="200" style="float:left; border:0px solid #cacaca; margin-left:20px; cursor: pointer; "></canvas>		
			             	<div id="ALTCanvas1" style="float:left; position:absolute; width:360px; height:200px;  font-size:10px; margin-left:20px; border:0px solid #cacaca; background-color:white; overflow:auto; "></div>           
		             	</div>
		             	<canvas id="myCanvas2" width="360" height="200" style="float:left; border:0px solid #cacaca; margin-left:20px; cursor: pointer; "></canvas>
		            </div>	
					<button id="PHSLabel" class="find_doctor_button" style="float:left; display: block; background-color: rgb(82, 216, 89); width:80px; margin-top: 0px;height: 16px;font-size: 12px;line-height: 12px;" lang="en">See Graph</button>
					<button id="ButtonReview" class="find_doctor_button" style="float:left; margin-left:10px; display: block; background-color: rgb(34, 174, 255); width:80px; margin-top: 0px;height: 16px;font-size: 12px;line-height: 12px;" lang="en">Edit</button>
                    
                    
                    
                    <!-- Edit for Patient Detail Med ****************************** -->
                    <button id="editPatient" class="find_doctor_button" style="float:left; display: block; background-color: rgb(34, 174, 255); width:80px; margin-top: 0px;margin-left:300px;height: 16px;font-size: 12px;line-height: 12px;" lang="en">Edit</button>

				</div> 
				
                <!-- Bottom ROW  ************************************************** -->
                <div style="float:left; margin-top:-40px; width:854px; height:120px; border:1px solid #cacaca; padding:10px; margin:10px; border-radius: 3px;">
             		<div style="margin-left:-10px; margin-top:-10px; margin-bottom:-10px; float:left; width:60px; height:140px; border-radius:0px;  border-top-left-radius: 2px; border-bottom-left-radius: 2px; background-color:#22aeff;"></div>    
             			<div style="float:left; width:180px; height:50px; position:relative; top:-12px; left:-110px; -ms-transform:rotate(270deg); -moz-transform:rotate(270deg); -webkit-transform:rotate(270deg);-o-transform:rotate(270deg); color:white; font-size:24px;" lang="en">Doctors</div>
          			    
			 		<style>
			 			a.ButtonDrAct{
				 			float:left; 
				 			text-align:left; 
				 			margin-top:10px; 
				 			margin-right:30px;
				 			padding:10px; 
				 			width:90px; 
				 			height:80px; 
				 			color:#22aeff; 
				 			text-align:center;
			 			}
			 		</style>
			 		
			 			<div style="margin-top:-50px; margin-left:60px; height:120px; padding-right:30px; text-align:justify; display: inline-block;">
	             			<a id='SearchDirectory'  value="SearchDirectory" class="btn ButtonDrAct" title="Search for a doctor near you"> 
		                     	<img src="images/icons/SearchDirectory_svg.png" style=" margin-top:-2px; width:50px; height:50px;">
		                     	<div style="line-height:25px; margin-left:6px;"><span lang="en">Search</span></div>
	                     	</a>
	             			<a id='Talk'  value="Talk" class="btn ButtonDrAct"  title="Talk to a doctor by phone or by video chat" > 
		                     	<img src="images/icons/Talk_svg.png" style=" margin-top:-2px; width:50px; height:50px;">
		                     	<div style="line-height:25px; margin-left:6px;"><span lang="en">Talk</span></div>
	                     	</a>
	             			<a id='Request'  value="Request" class="btn ButtonDrAct"  title="Request your medical records from your doctor"> 
		                     	<img src="images/icons/Request_svg.png" style=" margin-top:-2px; width:50px; height:50px;">
		                     	<div style="line-height:25px; margin-left:6px;"><span lang="en">Request</span></div>
	                     	</a>
	             			<a id='Send2Doc'  value="Send2Doc" class="btn ButtonDrAct" title="Send your records to a doctor"> 
		                     	<img src="images/icons/Send_svg.png" style=" margin-top:-2px; width:50px; height:50px;">
		                     	<div style=" line-height:25px; margin-left:6px;"><span lang="en">Send</span></div>
	                     	</a>
	             			<a id='SetUp'  value="SetUp" class="btn ButtonDrAct" title="Configure your account settings"> 
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

    <!-- Libraries for notifications -->
    <script src="realtime-notifications/lib/gritter/js/jquery.gritter.min.js"></script>
	<script src="realtime-notifications/pusher.min.js"></script>
	<script src="realtime-notifications/PusherNotifier.js"></script>
	<link href="realtime-notifications/lib/gritter/css/jquery.gritter.css"rel="stylesheet" type="text/css" />
	<!--<script src="imageLens/jquery.js" type="text/javascript"></script>-->
	<script src="imageLens/jquery.imageLens.js" type="text/javascript"></script>
     <script src="js/jquery-easy-ticker-master/jquery.easy-ticker.js"></script>      
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
    //ticker code
    var containerheight = 0;
    var numbercount = 0;
    var liheight;
    var index = 1;
    function callticker() {
        $(".container ul").animate({
            "margin-top": (-1) * (liheight * index)
        }

        , 2500);
        if (index != numbercount - 1) {
            index = index + 1;
        }
        else {
            index = 0;
        }
        timer = setTimeout("callticker()", 3600);
    }           
    //end ticker code    
        
        
        
	var timeoutTime = 18000000;
	//var timeoutTime = 300000;  //5minutes
	var timeoutTimer = setTimeout(ShowTimeOutWarning, timeoutTime);


	var active_session_timer = 60000; //1minute
	var sessionTimer = setTimeout(inform_about_session, active_session_timer);

    var reportcheck = new Array();
   
	//This function is called at regular intervals and it updates ongoing_sessions lastseen time
	function inform_about_session()
	{
		$.ajax({
			url: '<?php echo $domain?>/ongoing_sessions.php?userid='+<?php echo $_SESSION['MEDID'] ?>,
			success: function(data){
			//alert('done');
			}
		});
		clearTimeout(sessionTimer);
		sessionTimer = setTimeout(inform_about_session, active_session_timer);
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

    var find_doctor_page = 0;
    var selected_doctor_available = 0;
    var selected_doctor_info = '';
    var talk_mode = 0; // 0 = from the talk button and 1 = from the search button
    $(document).ready(function() {
        
        // load send report thumbnails
        $.get("createSendStream.php?ElementDOM=na&EntryTypegroup=0&Usuario=<?php echo $_SESSION['UserID']; ?>", function(data, status)
        {
            //console.log(data);
            $("#sendStreamContainer").html(data);
        });
              
        
        
        
        //call ticker
        numbercount = $(".container ul li").size();
        liheight = $(".container ul li").outerHeight();
        containerheight = $(".container ul  li").outerHeight() * numbercount;
        $(".container ul").css("height", containerheight);
        var timer = setTimeout("callticker()", 3600);  
        
        
        
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
	  if (Family > 0) ImgSeg[5] = 'Family';
	  if (Family > 0) NameSeg1[5] = 'Family';
	  if (Family > 0) NameSeg2[5] = 'History';
	  
	  ColSeg[6] = '#3498db';
	  SizSeg[6] = 10;
	  UIValue[6] = 10;
	  if (Habits > 0) SizSeg[6] = 10; else SizSeg[6]=0;
	  if (Habits > 0) ImgSeg[6] = 'Habits';
	  if (Habits > 0) NameSeg1[6] = 'Habits';
	  if (Habits > 0) NameSeg2[6] = 'Life';


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
        console.log('url = '+queUrl);
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
        
      console.log('**** RepNumbers: *****');
      console.log(RepNumbers);
      console.log('*******************');
 	  
      while (qx < 10){
		  SizSeg[qx] = RepNumbers[qx-1].number;
		  NameSeg1[qx] = RepData[qx-1].title.substring(0, 4);
          ColSeg[qx] = RepData[qx-1].color;
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
      sections = 0;

	  while (n <= MaxSegments)
	  {
	      context.beginPath();
	      SegColor = ColSeg[n];
	      if (SizSeg[n]>0){
		      sections++;
              console.log(' section:  '+sections);
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
                        numberpoint = leftPoints+1;
                        neworderside = numberpoint - orderside; 
					  	divisor = (y*2) / (leftPoints+1);
                        YBox = divisor * neworderside;  
                      
					    // Invert arrival of line to segment for left side points
					    Swaped[n] = 6 - (n - leftPoints + 1 ); 
					    if (Swaped[n]==0) Swaped[n]=1;
					    Swaped[n] = n; 
				  	}
				  else 
				  	{
					  	XBox = ((x) - (radius/2) + 20);
					  	XBox = 280;
                        numberpoint = rightPoints+1;
                        divisor = (y*2) / (rightPoints+1);
                        YBox = divisor * orderside;  	  
                         
						Swaped[n] = n; 				  
				  }
				  				  
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
          context.strokeStyle="grey";
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
		
		if(language == 'th'){
		translation = 'Última actualización en';
		translation2 = 'dias';
		translation3 = 'semana';
		translation4 = 'meses';
		translation5 = 'años';
		}else if(language == 'en'){
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

		if(language == 'th'){
		translation = 'Actualizar';
		}else if(language == 'en'){
		translation = 'Update';
		}
		
				contentTime = 'Summary is '+textDate+' old'; 
				iconTime = '<button id="UpdateSumm" class="find_doctor_button" style="float:right; display: block; background-color: rgb(82, 216, 89); width:80px; margin-top:-3px;">'+translation+'</button>';
			}
		else
			{
			var translation = '';

		if(language == 'th'){
		translation = 'Actualizado';
		}else if(language == 'en'){
		translation = 'Updated';
		}
				contentTime = translation;
				iconTime = '<i class="icon-check icon-2x" style="float:left;"></i>';
			}
		if (DiffYears > 4) 
			{
			var translation = '';
			var translation2 = '';

		if(language == 'th'){
		translation = 'Actualizar';
		translation2 = 'Resumen nunca actualizado';
		}else if(language == 'en'){
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

		if (bestVerified > -1)
		{
		var translation = '';
		var translation2 = '';
		var translation3 = '';

		if(language == 'th'){
		translation = 'Verificar';
		tranlation2 = 'Verificado por';
		translation3 = 'en';
		}else if(language == 'en'){
		translation = 'Verify';
		tranlation2 = 'Verified by';
		translation3 = 'on';
		}
			namedoctor = LanzaAjax ('/getDoctorName.php?IdDoctor='+bestVerified);
			contentVerif = translation;// by '+namedoctor;
			title =  translation2+' '+namedoctor+' '+translation3+' '+ VerifiedDate.substr(0, 10);
			iconVerif = '<i class="icon-check icon-2x"></i>';				
		}
		else
		{
			
			var translation = '';

		if(language == 'th'){
		translation = 'Verificar';
		contentVerif = 'No Verificado'; 
		}else if(language == 'en'){
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

		if(language == 'th'){
		translation = 'eventos registrados';
		}else if(language == 'en'){
		translation = 'events registered';
		}
		
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

	$('#myCanvas').live('click',function(){
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
        $("#summary_modal").html('<iframe src="medicalPassport.php?modal=1&IdUsu='+myClass+'" width="1000" height="660" scrolling="no" style="width:1000px;height:660px; margin: 0px; border: 0px solid #FFF; outline: 0px; padding: 0px; overflow: hidden;"></iframe>');
        $("#summary_modal").dialog({bigframe: true, width: 1050, height: 690, resize: false, modal: true});
		//window.location.replace('medicalPassport.php?IdUsu='+myClass);
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
		window.location.replace('patientdetailMED-new.php?IdUsu='+myClass); // Changed the url value from patientdetailMED-newUSER.php to patientdetailMED-new.php
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
		//var Codes = GetICD10Code('Append');
		//var longit = Object.keys(Codes).length;	   	   
		//alert ('Retrieved '+longit+' items. Example at [0].description: '+Codes[0].description);
        $('#search_speciality').val("Any");
        $('#country_search').val("-1");
        $('#region_search').val("-1");
        $('#region_search').html('');
        $("#advanced_toggle_button").removeClass("doctor_search_advanced_toggle_button_selected").addClass("doctor_search_advanced_toggle_button");
        $("#doctor_rows").css("display", "block");
        $("#doctors_search_page_buttons").css("display", "block");
        $("#doctor_search_advanced").css("display", "none");
        doctors_page_memory.length = 0;
        doctors_last_result = 0;
        doctors_next_result = 0;
        get_doctors();
        $("#search_modal").dialog({bigframe: true, width: 900, height: 700, resize: false, modal: true});
		
	});
    
    Stripe.setPublishableKey("pk_test_YBtrxG7xwZU9RO1VY8SeaEe9");
    function load_credit_cards(cards)
    {
        $("#credit_cards_container").html('');
        for(var i = 0; i < cards.length; i++)
        {
            var html = '';
            html += '<div class="credit_card_row"';
            if(i == 0 && i == cards.length - 1)
            {
                html += ' style="border-radius: 10px;"';
            }
            else if(i == 0)
            {
                html += ' style="border-top-left-radius: 10px; border-top-right-radius: 10px;"';
            }
            else if(i == cards.length - 1)
            {
                html += ' style="border-bottom-left-radius: 10px; border-bottom-right-radius: 10px;"';
            }
            html += '>';
            html += '<button id="clear-credit-card-'+cards[i]['id']+'" style="width: 18px; height: 18px; float: right; color: #F00; padding: 0px; background-color: inherit; border: 0px solid #FFF; border-radius: 3px; outline: 0px;">';
            html += '<i class="icon-remove" style="width: 12px; height: 12px;"></i>';
            html += '</button>'
            html += '<img src="'+cards[i]['icon']+'" style="float: left; margin-left: 10px; height: 38px;" />';
            html += '<span style="float: left; color: #5A5A5A; font-size: 14px; margin-left: 60px; margin-top: 8px;">****   ****   ****   '+cards[i]['number']+'</span>';
            html += '</div>';
            $("#credit_cards_container").append(html);
        }
        $('button[id^="clear-credit-card"]').on('click', function()
        {
            var card_id = $(this).attr("id").split("-")[3];
            $("#setup_modal_notification").html('<img src="images/load/8.gif" alt="">');
            $("#setup_modal_notification").css("background-color", "#FFF");
            $("#setup_modal_notification_container").css("opacity", "1.0");
            $.post("change_credit_card.php", {type: '2', action: '2', id: $("#USERID").val(), card_id: card_id}, function(data, status)
            {
                $("#setup_modal_notification_container").css("opacity", "0.0");
                if(data == '1')
                {
                    $("#setup_modal_notification").css("background-color", "#52D859");
                    $("#setup_modal_notification").html('<p style="color: #fff;">Credit Card Removed!</p>');
                    $("#setup_modal_notification_container").animate({opacity: '1.0'}, {duration: 1000, easing: 'easeInOutQuad', complete: function() {
                        setTimeout(function(){$("#setup_modal_notification_container").animate({opacity: '0.0'}, {duration: 1000, easing: 'easeInOutQuad', complete: function() {}});}, 2000);
                    }});
                    $.post("get_user_info.php", {id: $("#USERID").val()}, function(data, status)
                    {
                        var info = JSON.parse(data);
                        if(info.hasOwnProperty('cards') && info['cards'].length > 0)
                        {
                            load_credit_cards(info['cards']);
                        }
                    });
                }
                else
                {
                    $("#setup_modal_notification").css("background-color", "#D5483A");
                    $("#setup_modal_notification").html('<p style="color: #fff;">Unable To Remove Card</p>');
                    $("#setup_modal_notification_container").animate({opacity: '1.0'}, {duration: 1000, easing: 'easeInOutQuad', complete: function() {
                        setTimeout(function(){$("#setup_modal_notification_container").animate({opacity: '0.0'}, {duration: 1000, easing: 'easeInOutQuad', complete: function() {}});}, 2000);
                    }});
                }
            });
        });
    }
    
    // get and load user info for setup modal
    $("#setup_phone").intlTelInput();
    $.post("get_user_info.php", {id: $("#USERID").val()}, function(data, status)
    {
        var info = JSON.parse(data);
        $("#timezone_picker option[value='" + info['timezone'] + "']").attr('selected', 'selected');
        if(info.hasOwnProperty('location') && info['location'].length > 0)
        {
            setTimeout(function(){
                //console.log(info['location'].trim());
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
        if(info.hasOwnProperty('cards') && info['cards'].length > 0)
        {
            load_credit_cards(info['cards']);
        }
    });
        
    function stripeResponseHandler(status, response) {
        
        if (response.error) 
        {
            $("#setup_modal_notification").css("background-color", "#D5483A");
            $("#setup_modal_notification").html('<p style="color: #fff;">Unable To Add Card</p>');
            $("#setup_modal_notification_container").animate({opacity: '1.0'}, {duration: 1000, easing: 'easeInOutQuad', complete: function() {
                setTimeout(function(){$("#setup_modal_notification_container").animate({opacity: '0.0'}, {duration: 1000, easing: 'easeInOutQuad', complete: function() {}});}, 2000);
            }});
        } 
        else 
        {
            $("#setup_modal_notification").html('<img src="images/load/8.gif" alt="">');
            $("#setup_modal_notification").css("background-color", "#FFF");
            $("#setup_modal_notification_container").css("opacity", "1.0");
            $.post("change_credit_card.php", {type: '2', action: '1', id: $("#USERID").val(), token: response.id}, function(data, status)
            {
                $("#setup_modal_notification_container").css("opacity", "0.0");
                if(data == '1')
                {
                    $("#setup_modal_notification").css("background-color", "#52D859");
                    $("#setup_modal_notification").html('<p style="color: #fff;">Credit Card Added!</p>');
                    $("#setup_modal_notification_container").animate({opacity: '1.0'}, {duration: 1000, easing: 'easeInOutQuad', complete: function() {
                        setTimeout(function(){$("#setup_modal_notification_container").animate({opacity: '0.0'}, {duration: 1000, easing: 'easeInOutQuad', complete: function() {}});}, 2000);
                    }});
                    $.post("get_user_info.php", {id: $("#USERID").val()}, function(data, status)
                    {
                        var info = JSON.parse(data);
                        if(info.hasOwnProperty('cards') && info['cards'].length > 0)
                        {
                            load_credit_cards(info['cards']);
                        }
                    });
                }
                else
                {
                    $("#setup_modal_notification").css("background-color", "#D5483A");
                    $("#setup_modal_notification").html('<p style="color: #fff;">Unable To Add Card</p>');
                    $("#setup_modal_notification_container").animate({opacity: '1.0'}, {duration: 1000, easing: 'easeInOutQuad', complete: function() {
                        setTimeout(function(){$("#setup_modal_notification_container").animate({opacity: '0.0'}, {duration: 1000, easing: 'easeInOutQuad', complete: function() {}});}, 2000);
                    }});
                }
            });
        }
    };
    
    $("#add_card_button").on('click', function()
    {
        var date = $('#credit_card_exp_date').val().split("-");
        Stripe.card.createToken({
            number: $('#credit_card_number').val(),
            cvc: $('#credit_card_csv_code').val(),
            exp_month: date[1],
            exp_year: date[0]
        }, stripeResponseHandler);
        
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
            //console.log(data);
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
    });
    
    var state_changed = "";
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
        $("#setup_modal").dialog({bigframe: true, width: 640, height: 400, resize: false, modal: true});
        
        
        var country = geoplugin_countryName();
        var region ="";
        if (country == "United States") {
            country = "USA";
        }   

        
        var patient_id = <?php echo $UserID;?>;
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
        timezone_format_mins = timezone_array[1];
        timezone_minutes = ".0";
        if (timezone_format_mins == "30") {
            timezone_minutes = ".5";   
        }
        timezone_format=parseInt(timezone_format, 10)+timezone_minutes;
        
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
    var time_selected = -1;
    var day_selected= -1;
    var date_selected = '';
    var consultation_type = 1;
    var zones = null;
    var selected_timezone = "00:00:00";
    var status_interval = null;
    var latest_sid = 0;
        
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
        talk_mode = 0;
        $('#in_location_checkbox').removeAttr("checked");
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
        $("#find_doctor_modal").dialog({bgiframe: true, width: 550, height: 413, resize: false, modal: true}).on("dialogclose",function(data,success)
        {
            clearInterval(status_interval);
        });
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
        $('#find_doctor_my_doctors_3').css("display", "none");
        $('#find_doctor_appointment_1').css("display", "none");
        $('#find_doctor_appointment_2').css("display", "none");
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
        if(find_doctor_page == 30 || find_doctor_page == 10)
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
        else if(find_doctor_page == 31 || find_doctor_page == 21 || find_doctor_page == 11)
        {
            $("#step_bar_1").attr("class", "step_bar lit");
            $("#step_circle_1").attr("class", "step_circle lit");
            $("#step_circle_2").attr("class", "step_circle lit");
            $("#step_bar_2").attr("class", "step_bar lit");
            $("#step_circle_3").attr("class", "step_circle lit");
            $("#step_bar_3").attr("class", "step_bar lit");
            $("#step_circle_4").attr("class", "step_circle lit");
            $("#find_doctor_label").text("Select Time");
            if(find_doctor_page == 31 || find_doctor_page == 11)
            {
                
                // find a doctor
                if($("#speciality").val() != null)
                {
                    var loc_1 = $("#country").val();
                    var loc_2 = '';
                    if($("#state").val().length > 0 && $("#state").val() != '-1' && $("#state").parent().parent().css('display') == 'block')
                    {
                        loc_2 = $("#state").val() + ", " + $("#country").val();
                    }
                    var mba = true;
                    if(find_doctor_page == 31)
                    {
                        mba = false;
                    }
                    $.post("find_doctor.php", {type: $("#speciality").val(), location_1: loc_1, location_2: loc_2, must_be_available: mba}, function(data, status)
                    {
                        //console.log(data);
                        if(data != 'none')
                        {
                            var info = JSON.parse(data);
                            selected_doctor_info = "recdoc_"+info['id']+"_"+info['phone']+"_"+info['name']+"_"+info['location'];
                            //console.log(selected_doctor_info);
                            $.post("getDoctorAvailableTimeranges.php", {id: info['id']}, function(data2, status)
                            {
                                //console.log(data2);
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
                                if(find_doctor_page == 31)
                                {
                                    find_doctor_page = 32;
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
                                    $("#find_doctor_confirmation").html('<p style="color: #22AEFF; margin-top: 50px;" lang="en"><strong lang="en">Thank you!</strong><br/><strong lang="en">Your consultation appointment is starting now.</strong><br/><br/><span lang="en" style="color: #555; font-size: 18px;">Call Status:  <span id="call_status_label" lang="en" style="color: #E07221;">Connecting</span></span></p>');
                                    $('#find_doctor_appointment_2').fadeOut(300, function(){$('#find_doctor_receipt').fadeIn(300)});
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
                            $("#not_found_text").html("Sorry, we could not find any<br/>"+$("#speciality").children("option:selected").text()+"s in your area.");
                            $('#find_doctor_appointment_2').fadeOut(300, function(){$('#find_doctor_appointment_3').fadeIn(300)}); 
                            $("#find_doctor_next_button").css("display", "none");
                            $("#step_bar_3").attr("class", "step_bar");
                            $("#step_circle_4").attr("class", "step_circle");
                            $("#find_doctor_label").text("Select Speciality");
                        }
                    });
                }
                else
                {
                    $("#step_bar_3").attr("class", "step_bar");
                    $("#step_circle_4").attr("class", "step_circle");
                    $("#find_doctor_label").text("Select Speciality");
                }
                
            }
            if(find_doctor_page == 21)
            {
                if(selected_doctor_available == 0)
                {
                    
                    if($('#in_location_checkbox').is(":checked"))
                    {
                        if(talk_mode == 0)
                        {
                            find_doctor_page = 22;
                            resetDateTimeSelector();
                            $('#find_doctor_my_doctors_2').fadeOut(300, function(){$('#find_doctor_time').fadeIn(300)});
                        }
                        else
                        {
                            find_doctor_page = 22;
                            resetDateTimeSelector();
                            $('#find_doctor_my_doctors_2').fadeOut(300, function(){$('#find_doctor_time').fadeIn(300)});
                        }
                    }
                    else
                    {
                        $("#step_bar_3").attr("class", "step_bar");
                        $("#step_circle_4").attr("class", "step_circle");
                        $("#find_doctor_label").text("Select Type");
                        alert("Please confirm your state by checking the confirmation checkbox.");
                    }
                }
                else
                {
                    
                    if($('#in_location_checkbox').is(":checked"))
                    {
                        if(talk_mode == 0)
                        {
                            find_doctor_page = 25;
                            $('#find_doctor_my_doctors_2').fadeOut(300, function(){$('#find_doctor_my_doctors_3').fadeIn(300)});
                        }
                        else
                        {
                            $('#connect_now_yes').trigger('click');
                        }
                    }
                    else
                    {
                        $("#step_bar_3").attr("class", "step_bar");
                        $("#step_circle_4").attr("class", "step_circle");
                        $("#find_doctor_label").text("Select Type");
                        alert("Please confirm your state by checking the confirmation checkbox.");
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
                //console.log(date_selected);
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
                //console.log(date_selected);
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
                if(consultation_type == 1)
                {
                    
                    $.post("start_telemed_videocall.php", {pat_phone: $("#USERPHONE").val(), doc_phone: info[2], doc_id: info[1], pat_id: $("#USERID").val(), doc_name: (info[3] + ' ' + info[4]), pat_name: ($("#USERNAME").val() + ' ' + $("#USERSURNAME").val())}, function(data, status)
                    {
                        //console.log(data);
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
                {
                    $.post("start_telemed_phonecall.php", {pat_phone: $("#USERPHONE").val(), doc_phone: info[2], doc_id: info[1], pat_id: $("#USERID").val(), doc_name: (info[3] + ' ' + info[4]), pat_name: ($("#USERNAME").val() + ' ' + $("#USERSURNAME").val())}, function(data, status)
                    {
                        //console.log(data);
                        latest_sid = data;
                        status_interval = setInterval(function()
                        {
                            $.get("get_call_status.php?sid="+latest_sid, function(data, status)
                            {
                                //console.log(data);
                                $("#call_status_label").text(data);
                                if(data == 'Completed')
                                {
                                    $("#call_status_label").css("color", "#54bc00"); // green
                                }
                                else if(data == 'No Answer' || data == 'Failed' || data == 'Busy' || data == 'Canceled')
                                {
                                    $("#call_status_label").css("color", "#D84840"); // red
                                }
                                else if(data == 'Queued' || data == 'Ringing')
                                {
                                    $("#call_status_label").css("color", "#E07221"); // orange
                                }
                                else
                                {
                                    $("#call_status_label").css("color", "#22AEFF"); // blue
                                }
                            });
                        }, 5000);
                    });
                }
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
            $("#find_doctor_label").text("Select Type");
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
                    find_doctor_page = 31;
                    $('#find_doctor_time').fadeOut(300, function(){$('#find_doctor_appointment_2').fadeIn(300)});
                    $("#find_doctor_label").text("Select Speciality");
                }
                else
                {
                    find_doctor_page = 11;
                    $('#find_doctor_receipt').fadeOut(300, function(){$('#find_doctor_appointment_2').fadeIn(300)});
                    $("#find_doctor_label").text("Select Speciality");
                }
            }
        }
        else if(find_doctor_page == 21 || find_doctor_page == 31 || find_doctor_page == 11)
        {
            if(find_doctor_page == 21 && talk_mode == 0)
            {
                $('#find_doctor_my_doctors_2').fadeOut(300, function(){$('#find_doctor_my_doctors_1').fadeIn(300)});
                find_doctor_page = 20;
                $("#find_doctor_label").text("Select Doctor");
                
            }
            else if(find_doctor_page == 31)
            {
                find_doctor_page = 30;
                $('#find_doctor_appointment_2').fadeOut(300, function(){$('#find_doctor_appointment_1').fadeIn(300)});
                $("#find_doctor_label").text("Select Type");
            }
            else if(find_doctor_page == 1)
            {
                find_doctor_page = 10;
                $('#find_doctor_appointment_2').fadeOut(300, function(){$('#find_doctor_appointment_1').fadeIn(300)});
                $("#find_doctor_label").text("Select Type");
            }
            if(talk_mode == 0)
            {
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
            $("#find_doctor_label").text("Select Type");
        }
        else if(find_doctor_page == 26)
        {
            if(talk_mode == 0)
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
            else
            {
                $('#find_doctor_receipt').fadeOut(300, function(){$('#find_doctor_my_doctors_2').fadeIn(300)});
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
                $("#find_doctor_label").text("Select Type");
            }
        }
        else if(find_doctor_page == 35)
        {
            $('#find_doctor_appointment_3').fadeOut(300, function(){$('#find_doctor_appointment_2').fadeIn(300)});
            find_doctor_page = 31;
            $("#find_doctor_next_button").css("display", "block");
        }
        else if(find_doctor_page == 15)
        {
            $('#find_doctor_appointment_3').fadeOut(300, function(){$('#find_doctor_appointment_2').fadeIn(300)});
            find_doctor_page = 11;
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
        clearInterval(status_interval);
    });
    $("#find_doctor_now_button").live('click',function()
    {
        // GEOLOCATION
        //alert("Your location is: " + geoplugin_countryName() + ", " + geoplugin_region() + ", " + geoplugin_city());
        var country = geoplugin_countryName();
        if (country == "United States") {
            country = "USA";
        }   
    
		$("#country").val(country);			 
	    $("#country").change();
	    //populateCountries("country", "state");
	    var zone = geoplugin_region();
	    var district = geoplugin_city();
		$("#state").val(district);			 
	    $("#state").change();
        // GEOLOCATION
    
        if(!$(this).hasClass("square_blue_button_disabled"))
        {
            $("#step_bar_1").attr("class", "step_bar lit");
            $("#step_circle_1").attr("class", "step_circle lit");
            $("#step_circle_2").attr("class", "step_circle lit");
            $("#find_doctor_main").fadeOut(300, function(){$("#find_doctor_appointment_1").fadeIn(300)});
            find_doctor_page = 10;
            $("#find_doctor_label").text("Select Type");
        }
    });
    $("#find_doctor_appointment_button").live('click',function()
    {
        // GEOLOCATION
        //alert("Your location is: " + geoplugin_countryName() + ", " + geoplugin_region() + ", " + geoplugin_city());
        var country = geoplugin_countryName();

        if (country == "United States") {
            country = "USA";
        }   
  
        $("#country").val(country);			 
	    $("#country").change();
	    //populateCountries("country", "state");
	    var zone = geoplugin_region();
	    var district = geoplugin_city();
		$("#state").val(district);			 
	    $("#state").change();
        // GEOLOCATION

        $("#step_bar_1").attr("class", "step_bar lit");
        $("#step_circle_1").attr("class", "step_circle lit");
        $("#step_circle_2").attr("class", "step_circle lit");
        $("#find_doctor_main").fadeOut(300, function(){$("#find_doctor_appointment_1").fadeIn(300)});
        find_doctor_page = 30;
        $("#find_doctor_label").text("Select Type");
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
        $("#find_doctor_label").text("Select Type");
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
        if(talk_mode == 0)
        {
            $('#find_doctor_my_doctors_3').fadeOut(300, function(){$('#find_doctor_receipt').fadeIn(300)});
        }
        else
        {
            $('#find_doctor_my_doctors_2').fadeOut(300, function(){$('#find_doctor_receipt').fadeIn(300)});
        }
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
        $("#find_doctor_confirmation").html('<p style="color: #22AEFF; margin-top: 50px;" lang="en"><strong lang="en">Thank you!</strong><br/><strong lang="en">Your consultation appointment is starting now.</strong><br/><br/><span lang="en" style="color: #555; font-size: 18px;">Call Status:  <span id="call_status_label" lang="en" style="color: #E07221;">Connecting</span></span></p>');
        
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
        if($("#state").val().length > 0 && $("#state").val() != '-1' && $("#state").parent().parent().css('display') == 'block')
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
            
            //console.log(data);
            if(data != 'none')
            {
                var info = JSON.parse(data);
                selected_doctor_info = "recdoc_"+info['id']+"_"+info['phone']+"_"+info['name']+"_"+info['location'];
                //console.log(selected_doctor_info);
                $.post("getDoctorAvailableTimeranges.php", {id: info['id']}, function(data, status)
                {
                    //console.log(data);
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
                        $("#find_doctor_confirmation").html('<p style="color: #22AEFF; margin-top: 50px;" lang="en"><strong lang="en">Thank you!</strong><br/><strong lang="en">Your consultation appointment is starting now.</strong><br/><br/><span lang="en" style="color: #555; font-size: 18px;">Call Status:  <span id="call_status_label" lang="en" style="color: #E07221;">Connecting</span></span></p>');
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
                $("#not_found_text").html("Sorry, we could not find any<br/>general practicioners in your area.");
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
                //console.log(data);
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
        $('#find_doctor_modal').dialog('option', 'title', 'Calling Doctor ' + info[2]);
        $("#Talk_Section_2").fadeOut("Slow", function(){$("#Talk_Section_4").fadeIn("slow");});
        $.post("start_telemed_phonecall.php", {pat_phone: $("#USERPHONE").val(), doc_phone: info[1], doc_id: info[0], pat_id: $("#USERID").val(), doc_name: (info[2] + ' ' + info[3]), pat_name: ($("#USERNAME").val() + ' ' + $("#USERSURNAME").val())}, function(data, status)
        {
            //console.log(data);
        });
    });
    $('#video_call_button').live('click',function(e)
     {
        $("#find_doctor_modal").dialog("close");
         var info = doctor_to_connect.split("_");
        $.post("start_telemed_videocall.php", {pat_phone: $("#USERPHONE").val(), doc_phone: info[1], doc_id: info[0], pat_id: $("#USERID").val(), doc_name: (info[2] + ' ' + info[3]), pat_name: ($("#USERNAME").val() + ' ' + $("#USERSURNAME").val())}, function(data, status)
        {
            //console.log(data);
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
                                  
    	    var shp' + '?Usuario=' + UserInput; 
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
		$("#modal_send").dialog({bigframe: true, width: 800, height: 500, modal: false});
	});
    //End of code for display of modal window of Send button
    
    //Start of code for OK button in Send page
    $("#CaptureEmail2Send2Doc").on('click',function(){
		var emailId = $("#EmailID").val();
        var user = <?php echo $UserID; ?>;
        var idpins = '';
        var idpins_count = 0;
        $("input[id^='reportcol']:checked").each(function(index)
        {
            if(idpins_count > 0)
            {
                idpins += '_';
            }
            idpins += $(this).attr("id").substr(9, $(this).attr("id").length - 9);
            idpins_count++;
        });
        $.get("ValidateSendEmailDoc.php?emailId="+emailId+"&user="+user+"&idpins="+idpins,function(data,status)
              {
			  alert('The records you selected have been sent to your chosen doctor.');
              //console.log(data);
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
        console.log("user"+user);
        $.get("RequestReportsFromExternalDoc.php?emailId="+emailId+"&user="+user+"&message="+messageForDoc,function(data,status)
              {
              alert('Your request has been sent.');
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
    <script src="js/timezones.js" type="text/javascript"></script>
    <script src="js/doctor_search.js"></script> <!-- used for doctor search modal window -->
	<script src="js/application.js"></script>
    <script src="js/jquery-easy-ticker-master/jquery.easy-ticker.js"></script>
    <script type="text/javascript" src="https://js.stripe.com/v2/"></script>

	
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
