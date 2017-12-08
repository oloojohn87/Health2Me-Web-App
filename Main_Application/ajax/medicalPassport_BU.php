<?php
session_start();
 require("environment_detail.php");
 require_once("displayExitClass.php");
// ini_set("display_errors", 0);
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];

$NombreEnt = $_SESSION['Nombre'];
$PasswordEnt = $_SESSION['Password'];
$MEDID = $_SESSION['MEDID'];
if (isset($_SESSION['UserID'])) $UserID = $_SESSION['UserID']; else  $UserID = -1;
if( isset($_GET['IdUsu']) ) {$UserID = $_GET['IdUsu']; $_SESSION['UserID']=$UserID;} // Restore Global variable for UserID (so php constructors for summary can work)
$Acceso = $_SESSION['Acceso'];
$privilege=$_SESSION['Previlege'];
if ($Acceso != '23432')
{
$exit_display = new displayExitClass();

$exit_display->displayFunction(1);
die;
}

if(isset($SESSION['MEDID'])){
unset($_SESSION['UserID']);
}

$is_modal = false;
if(isset($_GET['modal']) && $_GET['modal'] == 1)
{
    $is_modal = true;
}

$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }		




//See if verified happened
/*$result = mysql_query("SELECT doctor_signed, latest_update FROM basicemrdata WHERE IdPatient = '$UserID'");
$count=mysql_num_rows($result);
$row = mysql_fetch_array($result);
if($count==1){
    $doctor_signed = $row['doctor_signed'];
	$latest_update = $row['latest_update'];
    $latest_update = date("m-d-Y", strtotime($latest_update));
 
} 


$result = mysql_query("SELECT Name, Surname FROM doctors WHERE id = '$doctor_signed'");
$count=mysql_num_rows($result);
$row = mysql_fetch_array($result);
if($count==1){
    $doctor_name = $row['Name'];
	$doctor_surname = $row['Surname'];
}*/    
//end verified check

$result = $con->prepare("SELECT * FROM usuarios where Identif=?");
$result->bindValue(1, $UserID, PDO::PARAM_INT);
$result->execute();

$count = $result->rowCount();
$row = $result->fetch(PDO::FETCH_ASSOC);
$success ='NO';
if($count==1){
	
    $UserID = $row['Identif'];
	$UserEmail= $row['email'];
    $UserName = $row['Name'];
    $UserSurname = $row['Surname'];
    //$UserLogo = $row['ImageLogo'];
    $IdUsFIXED = $row['IdUsFIXED'];
    $IdUsFIXEDNAME = $row['IdUsFIXEDNAME'];
    $privilege=1;
}
else
{
$exit_display = new displayExitClass();

$exit_display->displayFunction(3);



//echo "USER DATA INCOMPLETE. No Doctor assigned to this User";
//echo "<br>\n"; 	
//echo "<h2><a href='".$domain."'>Return to Health2me Dashboard</a></h2>";
die;
}

$isPatient=0;
if ($MEDID == $UserID) $isPatient=1;
?>

<!DOCTYPE html>
<html id ="html" lang="en" style="background: #F9F9F9;"><head>
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
	
	
}

function delete_cookie( name ) {
  document.cookie = name + '=; expires=Thu, 01 Jan 1970 00:00:01 GMT;';
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
}, 5000);
}

if(langType == 'en'){
setTimeout(function(){
window.lang.change('en');
lang.change('en');
//alert('th');
}, 5000);
}
	
</script>
	
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
    <!--<link rel="stylesheet" type="text/css" href=href="css/googleAPIFamilyCabin.css">-->
      <script type="text/javascript" src="js/42b6r0yr5470"></script>
	<link rel="stylesheet" href="css/icon/font-awesome.css">
 <!--   <link href="//netdna.bootstrapcdn.com/font-awesome/4.0.0/css/font-awesome.css" rel="stylesheet"> -->
    <link rel="stylesheet" href="css/bootstrap-responsive.css">
	<link rel="stylesheet" href="css/toggle-switch.css">
	<link rel="stylesheet" href="build/css/intlTelInput.css">
	
    <?php 
        if ($_SESSION['CustomLook']=="COL") { ?>
        <link href="css/styleCol.css" rel="stylesheet">
    <?php  }  ?>
<!--    <link href="css/FamilyTree.css" rel="stylesheet">-->
	
	<script src="js/jquery.min.js"></script>
    
	<script type="text/javascript" src="js/jquery.easing.1.3.js"></script>	
	<script type="text/javascript" src="js/modernizr.2.5.3.min.js"></script>	
	
    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    <!--[if lte IE 8]><script type="text/javascript" src="/js/excanvas.min.js"></script><![endif]-->
 
    <!-- Le fav and touch icons -->
    <link rel="shortcut icon" href="/images/icons/favicon.ico">
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
		.ui-dialog{
			overflow:visible;
		}
		.ui-datepicker-trigger{
			margin-left:-125px;
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
	<style>
		.intl-tel-input{
			width:100%;
			margin-top:10px;
		}
	</style>
	  
	 <style>
        html, body { height: 100%; }
        body .modal {
          width: 70%;
          left: 15%;
          margin-left:auto;
          margin-right:auto; 
        }
        .modal-body {
            height:1000px;
        }
        .InfoRow{
            border:solid 1px #cacaca; 
            height:20px; 
            padding:5px;
            margin-left:5px;
            margin-right:5px;
            margin-top:-1px;	
        }
        .PatName{
            margin-left:10px;
            font-size:14px;
            color:grey;	
        }
        body .ui-autocomplete {
          background-color:white;
          color:#22aeff;
          /* font-family to all */
        }
    </style> 
	  <style>
  #draggable {
    width: 30px;
    height: 10px;
  }
  
  #dropable {
    width: 30px;
    height: 10px;
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

  <body id = "body" <?php if($is_modal){ echo 'style="background: #FFF; width: 1000px; height: 690px;overflow: hidden;"'; } else { echo 'style="background: #F9F9F9;"'; } ?> >

<input type="hidden" id="NombreEnt" value="<?php echo $NombreEnt; ?> ">
<input type="hidden" id="PasswordEnt" value="<?php echo $PasswordEnt; ?> ">
<input type="hidden" id="UserHidden">

      
	<!--Header Start-->
      <?php if($is_modal){ echo '<!--'; } ?>
	<div class="header" >
			<a href="index.html" class="logo"><h1>Health2me</h1></a>
			<div class="pull-right">
              
                      
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
              
              <li><a href="medicalConfiguration.php" lang="en"><i class="icon-cog"></i> Settings</a></li>
              <li><a href="logout.php" lang="en"><i class="icon-off"></i> Sign Out</a></li>
            </ul>
            </div>
          </div>
          
          </div>
    </div>
    <?php if($is_modal){ echo ''; } ?>
    <!--Header END-->
    
   	 
    <!--Content Start-->
    <?php 
            if(!$is_modal)
            {
                echo '<div id="content" style="background: #F9F9F9; padding-left:0px;">';
            }
            else
            {
                echo '<div id="content" style="background: #FFF; padding-left:0px; width: 1000px; height: 660px;">';
            }
    ?>
    <div class="ribbon-banner" id="ribbon-verified" href = "#"></div>
    
	<div id='verifyBanner'></div>
	<!-- MODAL VIEW FOR ADDITIONAL INFO -->
    <div id="modalAdditionalInfo" style="overflow:visible;display:none;  padding:20px;">
        <div style="border:solid 1px #cacaca; margin-top:5px; padding:10px;">
		
			<table style="width:100%;background-color:white">
	<!--			<tr>
					<td style="width:20%"><span style="float: left;text-align:center;margin-top: 5px;font-size:14px;margin-left:5px" lang="en">Insurance: </span></td>
					<td style="width:80%"><input id="Insurance" type="text" style="margin-top: 10px;width: 95%; float:left;font-size:14px;height:17px" lang="en" placeholder="Enter Insurance"></td>
				</tr>-->
                <tr>
					<td style="width:20%"><span style="float: left;text-align:center;margin-top: 5px;font-size:14px;margin-left:5px" lang="en">DOB: </span></td>
					<td style="width:80%"><input id="DOB" type="date" style="margin-top: 10px;width: 95%; float:left;font-size:14px;height:17px" lang="en" /></td>
				</tr>
                <tr>
					<td style="width:20%"><span style="float: left;text-align:center;margin-top: 5px;font-size:14px;margin-left:5px" lang="en">Gender: </span></td>
					<td style="width:80%">
                        <select id="Gender" style="margin-top: 10px;width: 100%; float:left;font-size:14px;height:26px" lang="en">
                            <option value="Male">Male</option>
                            <option value="Female" selected>Female</option>
                        </select>
                    </td>
				</tr>
				<tr>
					<td style="width:20%"><span style="float: left;text-align:center;margin-top: 5px;font-size:14px;margin-left:5px" lang="en">Address: </span></td>
					<td style="width:80%"><input id="Address" type="text" style="margin-top: 10px;width: 95%; float:left;font-size:14px;height:17px" lang="en" placeholder="Enter Address"></td>
				</tr>
				<tr>
					<td style="width:20%"><span style="float: left;text-align:center;margin-top: 5px;font-size:14px;margin-left:5px" lang="en">City: </span></td>
					<td style="width:80%"><input id="City" type="text" style="margin-top: 10px;width: 95%; float:left;font-size:14px;height:17px" lang="en" placeholder="Enter City"></td>
				</tr>
				<tr>
					<td style="width:20%"><span style="float: left;text-align:center;margin-top: 5px;font-size:14px;margin-left:5px" lang="en">Zip: </span></td>
					<td style="width:80%"><input id="Zip" class="numbersOnly" type="text" style="margin-top: 10px;width: 95%; float:left;font-size:14px;height:17px" lang="en" placeholder="Enter Zip-Code"></td>
				</tr>
				<tr>
					<td style="width:20%"><span style="float: left;text-align:center;margin-top: 5px;font-size:14px;margin-left:5px" lang="en">Phone: </span></td>
					<td style="width:80%"><input id="Phone" type="tel" placeholder="e.g. +1 702 123 4567" style="margin-top: 10px;width: 100%; float:left;font-size:14px;;height:25px"></td>
				</tr>
				<tr>
					<td style="width:20%"><span style="float: left;text-align:center;margin-top: 5px;font-size:14px;margin-left:5px" lang="en">Email: </span></td>
					<td style="width:80%"><input id="Email" type="text" style="margin-top: 10px;width: 95%; float:left;font-size:14px;height:17px" lang="en" placeholder="Select Email"></td>
				</tr>
                <tr>
					<td style="width:20%"><span style="float: left;text-align:center;margin-top: 5px;font-size:14px;margin-left:5px" lang="en">Blood: </span></td>
					<td style="width:80%">
                        <select id="blood_type" style="margin-top: 10px;width: 100%; float:left;font-size:14px;height:26px">
                            <option value="none">Not Specified</option>
                            <option value="Apos">A+</option>
                            <option value="Aneg">A-</option>
                            <option value="Bpos">B+</option>
                            <option value="Bneg">B-</option>
                            <option value="ABpos">AB+</option>
                            <option value="ABneg">AB-</option>
                            <option value="Opos">O+</option>
                            <option value="Oneg">O-</option>
                        </select>
                    </td>
				</tr>
                <tr>
					<td style="width:20%"><span style="float: left;text-align:center;margin-top: 5px;font-size:14px;margin-left:5px" lang="en">Weight: </span></td>
					<td style="width:80%">
                        <input id="weight" type="text" style="margin-top: 10px;width: 58%; float:left;font-size:14px;height:17px" lang="en" placeholder="Enter Weight">
                        <select id="weight_type" style="margin-top: 10px;width: 30%; margin-left: 5%; float:left;font-size:14px;height:26px">
                            <option value="lb">lb</option>
                            <option value="kg">kg</option>
                        </select>
                    </td>
				</tr>
                <tr>
					<td style="width:20%"><span style="float: left;text-align:center;margin-top: 5px;font-size:14px;margin-left:5px" lang="en">Height: </span></td>
					<td style="width:80%">
                        <input id="height1" type="text" style="margin-top: 10px;width: 13%; float:left;font-size:14px;height:17px" lang="en">
                        <div id="height1label" style="margin-top: 10px;width: 10%; float:left;font-size:14px;height:17px; padding-top: 5px; margin-left: 8px;" lang="en">
                            ft
                        </div>
                        <input id="height2" type="text" style="margin-top: 10px;width: 13%; float:left;font-size:14px;height:17px" lang="en">
                        <div id="height2label" style="margin-top: 10px;width: 10%; float:left;font-size:14px;height:17px; padding-top: 5px; margin-left: 8px;" lang="en">
                            in
                        </div>
                        <select id="height_type" style="margin-top: 10px;width: 31%; margin-left: 5%; float:left;font-size:14px;height:26px">
                            <option value="ft">feet</option>
                            <option value="m">meters</option>
                        </select>
                    </td>
				</tr>
			</table>
		</div>
        <a id="ButtonUpdateAdditionalInfo" class="btn" style="width:50px; color:#22aeff; float:right; margin-top:10px;margin-bottom:10px"><span lang="en">Update</span></a>
		
    </div>
    <!-- END MODAL VIEW FOR ADDITIONAL INFO -->
	
	
	
	
	
	
	
	<!-- MODAL VIEW FOR MEDICATION -->
    <div id="modalMedication" style="display:none; text-align:center; padding:20px;">
        <div style="color:#22aeff; font-size:14px; text-align: right; width: 70%; height: 10px; float: left; padding-bottom: 10px;"> <span lang="en">Medications for</span> <?php echo $UserName.' '.$UserSurname; ?> </div>
		<div id="deletedMedications"><img class="btn" src="/icons/trashicon.jpg" style="margin-left:25px;border:solid 1px #cacaca;" alt="Deleted Medications" width="15" height="15"></div>
        <div id="MedicationContainer" style="border:solid 1px #cacaca; height:150px; margin-top:30px; padding-top:5px; overflow:auto;">
        </div>
        <div style="border:solid 1px #cacaca; height:140px; margin-top:20px; padding:10px;">
			<span style="float: left;text-align:center;margin-top: 5px;font-size:14px;margin-left:5px" lang="en">Drugname: </span>
			<input id="field_id" type="text" style="width: 40px; margin-top:5px;margin-left:10px; visibility:hidden;"><input id="DrugBox" type="text" style="margin-left:10px;width: 200px; float:left;font-size:14px;height:17px" lang="en" placeholder="Enter Drug Name"><span id="results_count"></span>
			
            <div style="width:100%;"></div>
            <div style="padding:3px;height: 30px; ">
				<span style="float: left;text-align:center;margin-top: 5px;font-size:14px" lang="en">Dose (number of pills): </span>
                <input id="NumberPills" class="numbersOnly" type="text" style="width: 20px;float: left;margin-left: 10px;font-size:14px;height:17px;text-align:center" placeholder="x" />
				<span style="float: left;text-align:center;margin-top: 5px;font-size:14px;margin-left: 10px" lang="en">pills per</span>
				<select id="Frequency" type="text" style="width: 85px;float: left;margin-left: 10px;text-align:center;font-size:14px;height:27px"  />
					<option value="1" lang="en">Day</option>
					<option value="2" lang="en">Week</option>
					<option value="3" lang="en">Month</option>
					<option value="4" lang="en">Year</option>
					
				</select>
            </div>
			<div style="width:100%;"></div>
			<div style="padding:3px;height: 30px;margin-top: 5px ">
				<span style="float: left;text-align:center;margin-top: 5px;font-size:14px" lang="en">How long have you been taking this?: </span>
                <input id="NumberDays" type="text" style="width: 20px;float: left;margin-left: 10px;text-align:center;font-size:14px;height:17px" placeholder="x" />
				<select id="Time" type="text" style="width: 85px;float: left;margin-left: 10px;text-align:center;font-size:14px;height:27px"  />
					<option value="1" lang="en">Days</option>
					<option value="2" lang="en">Weeks</option>
					<option value="3" lang="en">Months</option>
					<option value="4" lang="en">Years</option>
					
				</select>
            </div>
			
            <a id="buttonNoDrugs" style="float:left; text-align:center; margin-left: 3px; margin-top: -5px; width: 150px; font-size:12px; height:17px;" class="btn" lang="en">Not Taking Any Drugs</a>

            <a id="ButtonAddMedication" class="btn" style="height: 80px; width:50px; color:#22aeff; float:right; margin-top:-130px;"><span lang="en">Add</span></a>
        </div>
    </div>
	
	<!-- MODAL VIEW FOR EDITING MEDICATION -->
    <div id="editMedications" style="display:none; text-align:center; padding:20px;">
        <div style="color:#22aeff; font-size:14px; text-align: right; width: 70%; height: 10px; float: left; padding-bottom: 10px;"> <span lang="en">Edit Medications for</span> <?php echo $UserName.' '.$UserSurname; ?> </div>
        <div id="MedicationContainerEdit" style="border:solid 1px #cacaca; height:80px; margin-top:30px; padding-top:5px; overflow:auto;">
        </div>
        <div style="border:solid 1px #cacaca; height:140px; margin-top:20px; padding:10px;">
			<span style="float: left;text-align:center;margin-top: 5px;font-size:14px;margin-left:5px" lang="en">Drugname: </span>
			<input id="field_id" type="text" style="width: 40px; margin-top:5px;margin-left:10px; visibility:hidden;"><input id="DrugBox2" type="text" style="margin-left:10px;width: 200px; float:left;font-size:14px;height:17px" lang="en" placeholder="Enter Drug Name"><span id="results_count"></span>
			
            <div style="width:100%;"></div>
            <div style="padding:3px;height: 30px; ">
				<span style="float: left;text-align:center;margin-top: 5px;font-size:14px" lang="en">Dose (number of pills): </span>
                <input id="NumberPills2" class="numbersOnly" type="text" style="width: 20px;float: left;margin-left: 10px;font-size:14px;height:17px;text-align:center" placeholder="x" />
				<span style="float: left;text-align:center;margin-top: 5px;font-size:14px;margin-left: 10px" lang="en">pills per</span>
				<select id="Frequency2" type="text" style="width: 85px;float: left;margin-left: 10px;text-align:center;font-size:14px;height:27px"  />
					<option value="1" lang="en">Day</option>
					<option value="2" lang="en">Week</option>
					<option value="3" lang="en">Month</option>
					<option value="4" lang="en">Year</option>
					
				</select>
            </div>
			<div style="width:100%;"></div>
			<div style="padding:3px;height: 30px;margin-top: 5px ">
				<span style="float: left;text-align:center;margin-top: 5px;font-size:14px" lang="en">How long have you been taking this?: </span>
                <input id="NumberDays2" type="text" style="width: 20px;float: left;margin-left: 10px;text-align:center;font-size:14px;height:17px" placeholder="x" />
				<select id="Time2" type="text" style="width: 85px;float: left;margin-left: 10px;text-align:center;font-size:14px;height:27px"  />
					<option value="1" lang="en">Days</option>
					<option value="2" lang="en">Weeks</option>
					<option value="3" lang="en">Months</option>
					<option value="4" lang="en">Years</option>
					
				</select>
            </div>
			

            <a id="ButtonEditMedication" class="btn" style="height: 80px; width:50px; color:#22aeff; float:right; margin-top:-130px;"><span lang="en">Edit</span></a>
        </div>
    </div>
	
	<!-- Model for deleted medications -->
	<div id="modalDeletedMedications" style="display:none; text-align:center; padding:20px;">

        <div style="color:#22aeff; font-size:14px; text-align: right; width: 80%; height: 10px; float: left; padding-bottom: 10px;"> <span lang="en">Deleted medications for</span> <?php echo $UserName.' '.$UserSurname; ?> </div>
		<div><img src="/icons/trashicon.jpg" style="margin-left:25px;" alt="Deleted Medications" width="30" height="30"></div>
        <div id="MedicationContainerDeleted" style="border:solid 1px #cacaca; height:180px; margin-top:30px; padding-top:5px; overflow:auto;">
        </div>

        </div>
		
    <!-- END MODAL VIEW FOR MEDICATION -->
	
	<!-- MODAL VIEW FOR FAMILY HISTORY -->
    <div id="modalFamilyHistory" style="display:none; text-align:center; padding:20px;">

        <div style="color:#22aeff; font-size:14px; text-align: right; width: 70%; height: 10px; float: left; padding-bottom: 10px;"> <span lang="en">Family History for</span> <?php echo $UserName.' '.$UserSurname; ?> </div>
		<div id="deletedFamilyHistory"><img class="btn" src="/icons/trashicon.jpg" style="margin-left:25px;border:solid 1px #cacaca;" alt="Deleted Medications" width="15" height="15"></div>
        <div id="RelativesContainer" style="border:solid 1px #cacaca; height:200px; margin-top:30px; padding-top:5px; overflow:auto;">
        </div>

        <div style="border:solid 1px #cacaca; height:100px; margin-top:20px; padding:10px;">

				<span style="float: left;text-align:center;margin-top: 5px;font-size:14px;margin-left: 5px" lang="en">Type of Relative</span>
				<select id="TypeRelative" type="text" style="width: 232px;float: left; margin-left: 5px;text-align:center;font-size:14px;height:27px"  />
					<option value="1" lang="en">Father</option>
					<option value="2" lang="en">Mother</option>
					<option value="3" lang="en">Maternal Grandfather</option>
					<option value="4" lang="en">Maternal Grandmother</option>
					<option value="5" lang="en">Maternal Uncle</option>
					<option value="6" lang="en">Maternal Aunt</option>
					<option value="7" lang="en">Paternal Grandfather</option>
					<option value="8" lang="en">Paternal Grandmother</option>
					<option value="9" lang="en">Paternal Uncle</option>
					<option value="10" lang="en">Paternal Aunt</option>
					<option value="11" lang="en">Brother</option>
					<option value="12" lang="en">Sister</option>
					<!--<option value="13" lang="en">Maternal Brother</option>
					<option value="14" lang="en">Maternal Sister</option>
					<option value="15" lang="en">Paternal Brother</option>
					<option value="16" lang="en">Paternal Sister</option>
					<option value="17" lang="en">Son</option>
					<option value="18" lang="en">Daughter</option>-->
				</select>
				
				<div style="width:100%; float:left;"></div>
				<?php 
				if($_COOKIE["lang"] == 'en'){
				$holder = 'Digestive';
				}else{
				$holder = 'Digestivos';
				} ?>
				<span style="float:left; text-align:center;margin-top: 5px;font-size:14px;margin-left:5px" lang="en">Disease: </span>
<!--				<input id="DiseaseName" type="text" style="width: 40px; margin-top:5px;margin-left:10px; visibility:hidden;">-->
				<input id="DiseaseName" type="text" style="margin-left:10px;width: 140px; float:left;font-size:14px;height:17px" lang="en" placeholder="Disease Name">
				
				<select id="DiseaseGroup" type="text" style="width: 120px;float: left; margin-left: 5px;text-align:center;font-size:14px;height:27px" />
					<option value="1">Neuro</option>
					<option value="2">Otolaryngo</option>
					<option value="3">Endocrino</option>
					<option value="4"><?php echo $holder; ?></option>
					<option value="5">Pneumo</option>
					<option value="6">Cardio</option>
					<option value="7">Uro</option>
					<option value="8">Repro</option>
					<option value="9">Dermo</option>
					<option value="10">Onco</option>
					<option value="11">Trauma</option>
				</select>

				<div style="width:100%; float:left;"></div>
				<span style="float: left;text-align:center;margin-top: 5px;font-size:14px;margin-left:5px" lang="en">Age at Event: </span>
<!--				<input id="AgeEvent" type="text" style="width: 40px; margin-top:5px;margin-left:10px; visibility:hidden;">-->
				<input id="AgeEvent" type="text" class="numbersOnly" style="margin-left:10px;width: 50px; float:left;font-size:14px;height:17px" lang="en" placeholder="Age">
<!--			<span style="float: left;text-align:center;margin-top: 5px;font-size:14px;margin-left:25px;margin-right:5px;">Deceased</span>
			<input id="isDeadCheck" type="checkbox" style="float:left;height:23px;" name="isDeadCheck" value="1">
-->
				<input id="ICD10" type="text" style="visibility: hidden;" ><input id="ICD9" type="text" style="visibility: hidden;" >

				<div style="width:100%;"></div>
	

            </div>            
            <a id="ButtonAddRelative" class="btn" style="height: 80px; width:50px; color:#22aeff; float:right; margin-top:-110px; margin-right:10px;"><span lang="en">Add</span></a>

    </div>
	
	<!-- MODAL VIEW FOR EDITING FAMILY HISTORY -->
    <div id="editFamilyHistoryModal" style="display:none; text-align:center; padding:20px;">

        <div style="color:#22aeff; font-size:14px; text-align: right; width: 70%; height: 10px; float: left; padding-bottom: 10px;"> <span lang="en">Edit Family History for</span> <?php echo $UserName.' '.$UserSurname; ?> </div>
        <div id="RelativesContainerEdit" style="border:solid 1px #cacaca; height:80px; margin-top:30px; padding-top:5px; overflow:auto;">
        </div>

        <div style="border:solid 1px #cacaca; height:100px; margin-top:20px; padding:10px;">

				<span style="float: left;text-align:center;margin-top: 5px;font-size:14px;margin-left: 5px" lang="en">Type of Relative</span>
				<input id="DiagnosticRow2" type="hidden" style="margin-left:10px;width: 200px; float:left;font-size:14px;height:17px">
				<select id="TypeRelative2" type="text" style="width: 232px;float: left; margin-left: 5px;text-align:center;font-size:14px;height:27px"  />
					<option value="1" lang="en">Father</option>
					<option value="2" lang="en">Mother</option>
					<option value="3" lang="en">Maternal Grandfather</option>
					<option value="4" lang="en">Maternal Grandmother</option>
					<option value="5" lang="en">Maternal Uncle</option>
					<option value="6" lang="en">Maternal Aunt</option>
					<option value="7" lang="en">Paternal Grandfather</option>
					<option value="8" lang="en">Paternal Grandmother</option>
					<option value="9" lang="en">Paternal Uncle</option>
					<option value="10" lang="en">Paternal Aunt</option>
					<option value="11" lang="en">Brother</option>
					<option value="12" lang="en">Sister</option>
					<option value="13" lang="en">Maternal Brother</option>
					<option value="14" lang="en">Maternal Sister</option>
					<option value="15" lang="en">Paternal Brother</option>
					<option value="16" lang="en">Paternal Sister</option>
					<option value="17" lang="en">Son</option>
					<option value="18" lang="en">Daughter</option>
				</select>
				
				<div style="width:100%; float:left;"></div>

				<span style="float:left; text-align:center;margin-top: 5px;font-size:14px;margin-left:5px" lang="en">Disease: </span>
<!--				<input id="DiseaseName" type="text" style="width: 40px; margin-top:5px;margin-left:10px; visibility:hidden;">-->
				<input id="DiseaseName2" type="text" style="margin-left:10px;width: 140px; float:left;font-size:14px;height:17px" lang="en" placeholder="Disease Name">
				
				<select id="DiseaseGroup2" type="text" style="width: 120px;float: left; margin-left: 5px;text-align:center;font-size:14px;height:27px" />
					<option value="1">Neuro</option>
					<option value="2">Otolaryngo</option>
					<option value="3">Endocrino</option>
					<option value="4">Digestive</option>
					<option value="5">Pneumo</option>
					<option value="6">Cardio</option>
					<option value="7">Uro</option>
					<option value="8">Repro</option>
					<option value="9">Dermo</option>
					<option value="10">Onco</option>
					<option value="11">Trauma</option>
				</select>

				<div style="width:100%; float:left;"></div>
				<span style="float: left;text-align:center;margin-top: 5px;font-size:14px;margin-left:5px" lang="en">Age at Event: </span>
<!--				<input id="AgeEvent" type="text" style="width: 40px; margin-top:5px;margin-left:10px; visibility:hidden;">-->
				<input id="AgeEvent2" type="text" class="numbersOnly" style="margin-left:10px;width: 50px; float:left;font-size:14px;height:17px" lang="en" placeholder="Age">
<!--			<span style="float: left;text-align:center;margin-top: 5px;font-size:14px;margin-left:25px;margin-right:5px;">Deceased</span>
			<input id="isDeadCheck" type="checkbox" style="float:left;height:23px;" name="isDeadCheck" value="1">
-->
				<input id="ICD102" type="text" style="visibility: hidden;" ><input id="ICD9" type="text" style="visibility: hidden;" >

				<div style="width:100%;"></div>
	

            </div>            
            <a id="ButtonEditRelative" class="btn" style="height: 80px; width:50px; color:#22aeff; float:right; margin-top:-110px; margin-right:10px;"><span lang="en">Edit</span></a>

    </div>
	
	<!-- Model for deleted family history -->
	
	<div id="modalDeletedFamilyHistory" style="display:none; text-align:center; padding:20px;">

        <div style="color:#22aeff; font-size:14px; text-align: right; width: 80%; height: 10px; float: left; padding-bottom: 10px;"> <span lang="en">Deleted family history for</span> <?php echo $UserName.' '.$UserSurname; ?> </div>
		<div><img id="deletedFamilyHistory" src="/icons/trashicon.jpg" style="margin-left:25px;" alt="Deleted Family History" width="30" height="30"></div>
        <div id="RelativesContainerDeleted" style="border:solid 1px #cacaca; height:180px; margin-top:30px; padding-top:5px; overflow:auto;">
        </div>

        </div>
		
    <!-- END MODAL VIEW FOR FAMILY HISTORY -->

	<!-- MODAL VIEW FOR IMMUNIZATION -->
    <div id="modalImmunizationAllergy" style="display:none; text-align:center; padding:20px;">

        <div style="color:#22aeff; font-size:14px; text-align: right; width: 70%; height: 10px; float: left; padding-bottom: 10px;"> <span lang="en">Immunization for</span> <?php echo $UserName.' '.$UserSurname; ?> </div>
		<div id="deletedImmunizations"><img class="btn" src="/icons/trashicon.jpg" style="margin-left:25px;border:solid 1px #cacaca;" alt="Deleted Medications" width="15" height="15"></div>
        <div id="VaccinesContainer" style="border:solid 1px #cacaca; height:180px; margin-top:30px; padding-top:5px; overflow:auto;">
        </div>

        <div style="border:solid 1px #cacaca; height:120px; margin-top:20px; padding:10px;">

				 <select id="VaccineCode" type="text" style="width: 200px;float: left; margin-left: 5px;text-align:center;font-size:14px;height:27px"  />
					<option value="" lang="en">Vaccine Codes</option>
					<option value="CHOLERA,Cholera">CHOLERA..........Cholera</option>
					<option value="DIP,Diphtheria vaccine">Dip..........Diphtheria vaccine</option>
					<option value="DT,Tetanus and diphtheria toxoid childrens' dose">DT..........Tetanus and diphtheria toxoid childrens' dose</option>
					<option value="DTAP,Diphtheria and tetanus toxoid with acellular pertussis vaccine">DTaP..........Diphtheria and tetanus toxoid with acellular pertussis vaccine</option>
					<option value="DTAPHEPBIPV,Diphtheria and Tetanus and Pertussis and Hepatitis B and Polio">DTaPHepBIPV..........Diphtheria and Tetanus and Pertussis and Hepatitis B and Polio</option>
					<option value="DTAPHEPIPV,Diphtheria and tetanus toxoid with acellular pertussis, HepB and IPV vaccine">DTaPHepIPV..........Diphtheria and tetanus toxoid with acellular pertussis, HepB and IPV vaccine</option>
					<option value="DTAPHIB,Diphtheria and tetanus toxoid with acellular pertussis and Hib vaccine">DTaPHib..........Diphtheria and tetanus toxoid with acellular pertussis and Hib vaccine</option>
					<option value="DTAPHIBHEP,Diphtheria and tetanus toxoid with acellular pertussis, Hib and HepB vaccine">DTaPHibHep..........Diphtheria and tetanus toxoid with acellular pertussis, Hib and HepB vaccine</option>
					<option value="DTAPHIBHEPB,Diphtheria and tetanus toxoid with acellular pertussis, Hib and HepB vaccine">DTaPHibHepB..........Diphtheria and tetanus toxoid with acellular pertussis, Hib and HepB vaccine</option>
					<option value="DTAPHIBHEPIPV,Hexavalent diphtheria, tetanus toxoid with acellular pertussis, Hib, hepatitis B and IPV vaccine">DTaPHibHepIPV..........Hexavalent diphtheria, tetanus toxoid with acellular pertussis, Hib, hepatitis B and IPV vaccine</option>
					<option value="DTAPHIBIPV,Diphtheria and tetanus toxoid with acellular pertussis, Hib and IPV vaccine">DTaPHibIPV..........Diphtheria and tetanus toxoid with acellular pertussis, Hib and IPV vaccine</option>
					<option value="DTAPIPV,Diphtheria and tetanus toxoid with acellular pertussis, and IPV vaccine">DTaPIPV..........Diphtheria and tetanus toxoid with acellular pertussis, and IPV vaccine</option>
					<option value="DTIPV,Diphtheria and tetanus toxoid vaccine and IPV">DTIPV..........Diphtheria and tetanus toxoid vaccine and IPV</option>
					<option value="DTP,Diptheria and tetanus toxoid with pertussis vaccine">DTP..........Diptheria and tetanus toxoid with pertussis vaccine</option>
					<option value="DTPHIBIPV,Pentavalent diphtheria and tetanus toxoid with pertussis, Hib and IPV vaccine">DTPHibIPV..........Pentavalent diphtheria and tetanus toxoid with pertussis, Hib and IPV vaccine</option>
					<option value="DTWP,Diphtheria and tetanus toxoid with whole cell pertussis vaccine">DTwP..........Diphtheria and tetanus toxoid with whole cell pertussis vaccine</option>
					<option value="DTWPHEP,Diphtheria and tetanus toxoid with whole cell pertussis and HepB vaccine">DTwPHep..........Diphtheria and tetanus toxoid with whole cell pertussis and HepB vaccine</option>
					<option value="DTWPHIB,Diphtheria and tetanus toxoid with whole cell pertussis and Hib vaccine">DTwPHib..........Diphtheria and tetanus toxoid with whole cell pertussis and Hib vaccine</option>
					<option value="DTWPHIB,Diphtheria and tetanus toxoid with whole cell pertussis and Hib vaccine">DTwPHiB..........Diphtheria and tetanus toxoid with whole cell pertussis and Hib vaccine</option>
					<option value="DTWPHIBHEP,Diphtheria and tetanus toxoid with whole cell pertussis, Hib and HepB vaccine">DTwPHibHep..........Diphtheria and tetanus toxoid with whole cell pertussis, Hib and HepB vaccine</option>
					<option value="DTWPHIBHEPB,Diphtheria and Tetanus and Pertussis and Haemophilus influenzae and Hepatitis B">DTwPHibHepB..........Diphtheria and Tetanus and Pertussis and Haemophilus influenzae and Hepatitis B</option>
					<option value="DTWPHIBIPV,Diphtheria and tetanus toxoid with whole cell pertussis, Hib and IPV vaccine">DTwPHibIPV..........Diphtheria and tetanus toxoid with whole cell pertussis, Hib and IPV vaccine</option>
					<option value="DTWPIPV,Diphtheria and tetanus toxoid with whole cell pertussis, and IPV vaccine">DTwPIPV..........Diphtheria and tetanus toxoid with whole cell pertussis, and IPV vaccine</option>
					<option value="HEPA,Hepatitis A vaccine">HepA..........Hepatitis A vaccine</option>
					<option value="HEPAHEPB,Hepatitis A, Hepatitis B vaccine">HepAHepB..........Hepatitis A, Hepatitis B vaccine</option>
					<option value="HEPB,Hepatitis B vaccine">HepB..........Hepatitis B vaccine</option>
					<option value="HFRS,">HFRS..........Hemorrhagic fever with renal syndrome</option>
					<option value="HIB,Hemorrhagic fever with renal syndrome">HIB..........Haemophilus influenzae type b vaccine</option>
					<option value="HIB,Haemophilus influenzae type b vaccine">Hib..........Haemophilus influenzae type b vaccine</option>
					<option value="HIBMENC,Haemophilus influenza type b, Meningococcal C vaccine">HibMenC..........Haemophilus influenza type b, Meningococcal C vaccine</option>
					<option value="HPV,Human Papillomavirus vaccine">HPV..........Human Papillomavirus vaccine</option>
					<option value="INFLUENZA,Influenza">Influenza..........Influenza</option>
					<option value="IPV,Inactivated polio vaccine">IPV..........Inactivated polio vaccine</option>
					<option value="JAPENC,Japanese encephalitis">JapEnc..........Japanese encephalitis</option>
					<option value="MEASLES,Measles vaccine">Measles..........Measles vaccine</option>
					<option value="MENA,Meningococcal A">MenA..........Meningococcal A</option>
					<option value="MENAC,Meningococcal AC">MenAC..........Meningococcal AC</option>
					<option value="MENACW,Meningococcal ACW">MenACW..........Meningococcal ACW</option>
					<option value="MENACWY,">MenACWY..........Meningococcal ACWY</option>
					<option value="MENBC,Meningococcal ACWY">MenBC..........Meningococcal group B &amp; group C vaccine</option>
					<option value="MENC_CONJ,Meningococcal group B &amp; group C vaccine">MenC_conj..........Meningococcal C conjugate vaccine</option>
					<option value="MM,Measles and mumps vaccine<">MM..........Measles and mumps vaccine</option>
					<option value="MMR,Measles mumps and rubella vaccine">MMR..........Measles mumps and rubella vaccine</option>
					<option value="MMRV,Measles, mumps, rubella and varicella vaccine">MMRV..........Measles, mumps, rubella and varicella vaccine</option>
					<option value="MR,Measles and rubella vaccine">MR..........Measles and rubella vaccine</option>
					<option value="MUMPS,Mumps vaccine">Mumps..........Mumps vaccine</option>
					<option value="OPV,Oral polio vaccine">OPV..........Oral polio vaccine</option>
					<option value="PNEUMO_CONJ,Pneumococcal conjugate vaccine">Pneumo_conj..........Pneumococcal conjugate vaccine</option>
					<option value="PNEUMO_PS,Pneumococcal polysaccharide vaccine">Pneumo_ps..........Pneumococcal polysaccharide vaccine</option>
					<option value="RABIES,Rabies vaccine">Rabies..........Rabies vaccine</option>
					<option value="ROTAVIRUS,Rotavirus vaccine">Rotavirus..........Rotavirus vaccine</option>
					<option value="RUBELLA,Rubella vaccine">Rubella..........Rubella vaccine</option>
					<option value="TBE,Tick borne encephalitis">TBE..........Tick borne encephalitis</option>
					<option value="TD,Tetanus and diphtheria toxoid for older children / adults">Td..........Tetanus and diphtheria toxoid for older children / adults</option>
					<option value="TDAP,Tetanus and diphtheria toxoids and acellular pertussis vaccine">TdaP..........Tetanus and diphtheria toxoids and acellular pertussis vaccine</option>
					<option value="TDAPIPV,Tetanus and diphtheria toxoids and acellular pertussis vaccine IPV">TdaPIPV..........Tetanus and diphtheria toxoids and acellular pertussis vaccine IPV</option>
					<option value="TDIPV,Tetanus and diphtheria toxoid for older children / adults with inactivated Polio vaccine">TdIPV..........Tetanus and diphtheria toxoid for older children / adults with inactivated Polio vaccine</option>
					<option value="TT,Tetanus toxoid">TT..........Tetanus toxoid</option>
					<option value="TYPHOID,Typhoid fever vaccine">Typhoid..........Typhoid fever vaccine</option>
					<option value="TYPHOIDHEPA,Typhoid fever and Hepatitis A vaccine">TyphoidHepA..........Typhoid fever and Hepatitis A vaccine</option>
					<option value="VARICELLA,Varicella vaccine">Varicella..........Varicella vaccine</option>
					<option value="VITAMINA,Vitamin A supplementation">VitaminA..........Vitamin A supplementation</option>
					<option value="YF,Yellow fever vaccine">YF..........Yellow fever vaccine</option>
					<option value="ZOSTER,Varicella vaccine">Zoster..........Varicella vaccine</option>
				</select>
				<input id="VaccineName" type="text" style="margin-left:10px;width: 150px; float:left;font-size:14px;height:17px" lang="en" placeholder="Vaccine Code">

				<div style="width:10%; float:left; text-align:center; margin-bottom:10px; margin-left:10px;"></div>


				<div style="width:100%; float:left; text-align:center; height:10px; "></div>
				
				<div style="padding:3px;height: 30px;margin-top: 5px ">
					<span style="float: left; text-align:center; font-size:14px; " lang="en">Date: </span>
					<input id="dateEvent" type="date" style="margin-left:10px; width: 139px; float:left; font-size:14px; height:17px">
				</div>
				<!--<input id="ageEventVac" type="text" style="margin-left:10px;width: 150px; float:left;font-size:14px;height:17px" lang="en" placeholder="Vaccination Age">-->

				<div style="width:100%; float:left; text-align:center; height:10px; "></div>
					
				

            </div>            
            <a id="ButtonAddVaccine" class="btn" style="height: 80px; width:50px; color:#22aeff; float:right; margin-top:-130px; margin-right:19px;"><span lang="en">Add</span></a>
			<select id="VaccineCalendarImport" type="text" style="width: 200px;float: left; margin-left: 15px;text-align:center;margin-top:-45px;font-size:14px;height:27px"  />
			<option value="" lang="en">Import Vacc. Calendar</option>
			<option value="USA">United States</option>
			<option value="COL">Colombia</option>
			<option value="SPA">Spain</option>
			</select>
			<a id="ButtonAddVaccineCalendar" class="btn" style="height: 17px; width:135px; color:#22aeff; float:right; margin-top:-45px; margin-right:118px;"><span lang="en">Import Calendar</span></a>

    </div>
	
	<!-- MODAL VIEW FOR EDITING IMMUNIZATION -->
    <div id="editVaccineModal" style="display:none; text-align:center; padding:20px;">

        <div style="color:#22aeff; font-size:14px; text-align: right; width: 70%; height: 10px; float: left; padding-bottom: 10px;"> <span lang="en">Edit Immunization for</span> <?php echo $UserName.' '.$UserSurname; ?> </div>
        <div id="VaccinesContainer2" style="border:solid 1px #cacaca; height:80px; margin-top:30px; padding-top:5px; overflow:auto;">
        </div>

        <div style="border:solid 1px #cacaca; height:120px; margin-top:20px; padding:10px;">
		<input id="DiagnosticRow2" type="hidden" style="margin-left:10px;width: 200px; float:left;font-size:14px;height:17px">
				 <select id="VaccineCode2" type="text" style="width: 200px;float: left; margin-left: 5px;text-align:center;font-size:14px;height:27px"  />
					<option value="">Vaccine Codes</option>
					<option value="CHOLERA,Cholera">CHOLERA..........Cholera</option>
					<option value="DIP,Diphtheria vaccine">Dip..........Diphtheria vaccine</option>
					<option value="DT,Tetanus and diphtheria toxoid childrens' dose">DT..........Tetanus and diphtheria toxoid childrens' dose</option>
					<option value="DTAP,Diphtheria and tetanus toxoid with acellular pertussis vaccine">DTaP..........Diphtheria and tetanus toxoid with acellular pertussis vaccine</option>
					<option value="DTAPHEPBIPV,Diphtheria and Tetanus and Pertussis and Hepatitis B and Polio">DTaPHepBIPV..........Diphtheria and Tetanus and Pertussis and Hepatitis B and Polio</option>
					<option value="DTAPHEPIPV,Diphtheria and tetanus toxoid with acellular pertussis, HepB and IPV vaccine">DTaPHepIPV..........Diphtheria and tetanus toxoid with acellular pertussis, HepB and IPV vaccine</option>
					<option value="DTAPHIB,Diphtheria and tetanus toxoid with acellular pertussis and Hib vaccine">DTaPHib..........Diphtheria and tetanus toxoid with acellular pertussis and Hib vaccine</option>
					<option value="DTAPHIBHEP,Diphtheria and tetanus toxoid with acellular pertussis, Hib and HepB vaccine">DTaPHibHep..........Diphtheria and tetanus toxoid with acellular pertussis, Hib and HepB vaccine</option>
					<option value="DTAPHIBHEPB,Diphtheria and tetanus toxoid with acellular pertussis, Hib and HepB vaccine">DTaPHibHepB..........Diphtheria and tetanus toxoid with acellular pertussis, Hib and HepB vaccine</option>
					<option value="DTAPHIBHEPIPV,Hexavalent diphtheria, tetanus toxoid with acellular pertussis, Hib, hepatitis B and IPV vaccine">DTaPHibHepIPV..........Hexavalent diphtheria, tetanus toxoid with acellular pertussis, Hib, hepatitis B and IPV vaccine</option>
					<option value="DTAPHIBIPV,Diphtheria and tetanus toxoid with acellular pertussis, Hib and IPV vaccine">DTaPHibIPV..........Diphtheria and tetanus toxoid with acellular pertussis, Hib and IPV vaccine</option>
					<option value="DTAPIPV,Diphtheria and tetanus toxoid with acellular pertussis, and IPV vaccine">DTaPIPV..........Diphtheria and tetanus toxoid with acellular pertussis, and IPV vaccine</option>
					<option value="DTIPV,Diphtheria and tetanus toxoid vaccine and IPV">DTIPV..........Diphtheria and tetanus toxoid vaccine and IPV</option>
					<option value="DTP,Diptheria and tetanus toxoid with pertussis vaccine">DTP..........Diptheria and tetanus toxoid with pertussis vaccine</option>
					<option value="DTPHIBIPV,Pentavalent diphtheria and tetanus toxoid with pertussis, Hib and IPV vaccine">DTPHibIPV..........Pentavalent diphtheria and tetanus toxoid with pertussis, Hib and IPV vaccine</option>
					<option value="DTWP,Diphtheria and tetanus toxoid with whole cell pertussis vaccine">DTwP..........Diphtheria and tetanus toxoid with whole cell pertussis vaccine</option>
					<option value="DTWPHEP,Diphtheria and tetanus toxoid with whole cell pertussis and HepB vaccine">DTwPHep..........Diphtheria and tetanus toxoid with whole cell pertussis and HepB vaccine</option>
					<option value="DTWPHIB,Diphtheria and tetanus toxoid with whole cell pertussis and Hib vaccine">DTwPHib..........Diphtheria and tetanus toxoid with whole cell pertussis and Hib vaccine</option>
					<option value="DTWPHIB,Diphtheria and tetanus toxoid with whole cell pertussis and Hib vaccine">DTwPHiB..........Diphtheria and tetanus toxoid with whole cell pertussis and Hib vaccine</option>
					<option value="DTWPHIBHEP,Diphtheria and tetanus toxoid with whole cell pertussis, Hib and HepB vaccine">DTwPHibHep..........Diphtheria and tetanus toxoid with whole cell pertussis, Hib and HepB vaccine</option>
					<option value="DTWPHIBHEPB,Diphtheria and Tetanus and Pertussis and Haemophilus influenzae and Hepatitis B">DTwPHibHepB..........Diphtheria and Tetanus and Pertussis and Haemophilus influenzae and Hepatitis B</option>
					<option value="DTWPHIBIPV,Diphtheria and tetanus toxoid with whole cell pertussis, Hib and IPV vaccine">DTwPHibIPV..........Diphtheria and tetanus toxoid with whole cell pertussis, Hib and IPV vaccine</option>
					<option value="DTWPIPV,Diphtheria and tetanus toxoid with whole cell pertussis, and IPV vaccine">DTwPIPV..........Diphtheria and tetanus toxoid with whole cell pertussis, and IPV vaccine</option>
					<option value="HEPA,Hepatitis A vaccine">HepA..........Hepatitis A vaccine</option>
					<option value="HEPAHEPB,Hepatitis A, Hepatitis B vaccine">HepAHepB..........Hepatitis A, Hepatitis B vaccine</option>
					<option value="HEPB,Hepatitis B vaccine">HepB..........Hepatitis B vaccine</option>
					<option value="HFRS,">HFRS..........Hemorrhagic fever with renal syndrome</option>
					<option value="HIB,Hemorrhagic fever with renal syndrome">HIB..........Haemophilus influenzae type b vaccine</option>
					<option value="HIB,Haemophilus influenzae type b vaccine">Hib..........Haemophilus influenzae type b vaccine</option>
					<option value="HIBMENC,Haemophilus influenza type b, Meningococcal C vaccine">HibMenC..........Haemophilus influenza type b, Meningococcal C vaccine</option>
					<option value="HPV,Human Papillomavirus vaccine">HPV..........Human Papillomavirus vaccine</option>
					<option value="INFLUENZA,Influenza">Influenza..........Influenza</option>
					<option value="IPV,Inactivated polio vaccine">IPV..........Inactivated polio vaccine</option>
					<option value="JAPENC,Japanese encephalitis">JapEnc..........Japanese encephalitis</option>
					<option value="MEASLES,Measles vaccine">Measles..........Measles vaccine</option>
					<option value="MENA,Meningococcal A">MenA..........Meningococcal A</option>
					<option value="MENAC,Meningococcal AC">MenAC..........Meningococcal AC</option>
					<option value="MENACW,Meningococcal ACW">MenACW..........Meningococcal ACW</option>
					<option value="MENACWY,">MenACWY..........Meningococcal ACWY</option>
					<option value="MENBC,Meningococcal ACWY">MenBC..........Meningococcal group B &amp; group C vaccine</option>
					<option value="MENC_CONJ,Meningococcal group B &amp; group C vaccine">MenC_conj..........Meningococcal C conjugate vaccine</option>
					<option value="MM,Measles and mumps vaccine<">MM..........Measles and mumps vaccine</option>
					<option value="MMR,Measles mumps and rubella vaccine">MMR..........Measles mumps and rubella vaccine</option>
					<option value="MMRV,Measles, mumps, rubella and varicella vaccine">MMRV..........Measles, mumps, rubella and varicella vaccine</option>
					<option value="MR,Measles and rubella vaccine">MR..........Measles and rubella vaccine</option>
					<option value="MUMPS,Mumps vaccine">Mumps..........Mumps vaccine</option>
					<option value="OPV,Oral polio vaccine">OPV..........Oral polio vaccine</option>
					<option value="PNEUMO_CONJ,Pneumococcal conjugate vaccine">Pneumo_conj..........Pneumococcal conjugate vaccine</option>
					<option value="PNEUMO_PS,Pneumococcal polysaccharide vaccine">Pneumo_ps..........Pneumococcal polysaccharide vaccine</option>
					<option value="RABIES,Rabies vaccine">Rabies..........Rabies vaccine</option>
					<option value="ROTAVIRUS,Rotavirus vaccine">Rotavirus..........Rotavirus vaccine</option>
					<option value="RUBELLA,Rubella vaccine">Rubella..........Rubella vaccine</option>
					<option value="TBE,Tick borne encephalitis">TBE..........Tick borne encephalitis</option>
					<option value="TD,Tetanus and diphtheria toxoid for older children / adults">Td..........Tetanus and diphtheria toxoid for older children / adults</option>
					<option value="TDAP,Tetanus and diphtheria toxoids and acellular pertussis vaccine">TdaP..........Tetanus and diphtheria toxoids and acellular pertussis vaccine</option>
					<option value="TDAPIPV,Tetanus and diphtheria toxoids and acellular pertussis vaccine IPV">TdaPIPV..........Tetanus and diphtheria toxoids and acellular pertussis vaccine IPV</option>
					<option value="TDIPV,Tetanus and diphtheria toxoid for older children / adults with inactivated Polio vaccine">TdIPV..........Tetanus and diphtheria toxoid for older children / adults with inactivated Polio vaccine</option>
					<option value="TT,Tetanus toxoid">TT..........Tetanus toxoid</option>
					<option value="TYPHOID,Typhoid fever vaccine">Typhoid..........Typhoid fever vaccine</option>
					<option value="TYPHOIDHEPA,Typhoid fever and Hepatitis A vaccine">TyphoidHepA..........Typhoid fever and Hepatitis A vaccine</option>
					<option value="VARICELLA,Varicella vaccine">Varicella..........Varicella vaccine</option>
					<option value="VITAMINA,Vitamin A supplementation">VitaminA..........Vitamin A supplementation</option>
					<option value="YF,Yellow fever vaccine">YF..........Yellow fever vaccine</option>
					<option value="ZOSTER,Varicella vaccine">Zoster..........Varicella vaccine</option>
				</select>
				<input id="VaccineName2" type="text" style="margin-left:10px;width: 150px; float:left;font-size:14px;height:17px" lang="en" placeholder="Vaccine Code">

				<div style="width:10%; float:left; text-align:center; margin-bottom:10px; margin-left:10px;"></div>


				<div style="width:100%; float:left; text-align:center; height:10px; "></div>
				
				<div style="padding:3px;height: 30px;margin-top: 5px ">
					<span style="float: left; text-align:center; font-size:14px; " lang="en">Date: </span>
					<input id="dateEvent2" type="date" style="margin-left:10px; width: 139px; float:left; font-size:14px; height:17px">
				</div>
				<!--<input id="ageEventVac2" type="text" style="margin-left:10px;width: 150px; float:left;font-size:14px;height:17px" lang="en" placeholder="Vaccination Age">-->

				<div style="width:100%; float:left; text-align:center; height:10px; "></div>
					
				

            </div>            
            <a id="ButtonEditVaccine" class="btn" style="height: 80px; width:50px; color:#22aeff; float:right; margin-top:-130px; margin-right:19px;"><span lang="en">Edit</span></a>

    </div>
	<!-- Model for deleted immunization -->
	
	<div id="modalDeletedImmunizations" style="display:none; text-align:center; padding:20px;">

        <div style="color:#22aeff; font-size:14px; text-align: right; width: 80%; height: 10px; float: left; padding-bottom: 10px;"> <span lang="en">Deleted immunizations for</span> <?php echo $UserName.' '.$UserSurname; ?> </div>
		<div><img id="deletedImmunizations" src="/icons/trashicon.jpg" style="margin-left:25px;" alt="Deleted Immunizations" width="30" height="30"></div>
        <div id="VaccinesContainerDeleted" style="border:solid 1px #cacaca; height:180px; margin-top:30px; padding-top:5px; overflow:auto;">
        </div>

        </div>
		
    <!-- END MODAL VIEW FOR IMMUNIZATION   -->

	<!-- MODAL VIEW FOR ALLERGY -->
    <div id="modalAllergy" style="display:none; text-align:center; padding:20px;">

        <div style="color:#22aeff; font-size:14px; text-align: right; width: 70%; height: 10px; float: left; padding-bottom: 10px;"> <span lang="en">Known allergies for<span> <?php echo $UserName.' '.$UserSurname; ?> </div>
		<div id="deletedAllergy"><img class="btn" src="/icons/trashicon.jpg" style="margin-left:25px;border:solid 1px #cacaca;" alt="Deleted Medications" width="15" height="15"></div>
        <div id="AllergyContainer" style="border:solid 1px #cacaca; height:180px; margin-top:30px; padding-top:5px; overflow:auto;">
        </div>

        <div style="border:solid 1px #cacaca; height:140px; margin-top:20px; padding:10px;">

				 
				

				

				<select id="AllergyCode" type="text" style="width: 210px;float: left; margin-left: 5px;text-align:center;font-size:14px;height:27px"  />
					<option value="" lang="en">Allergy Codes</option>
					<option value="FOO,Foods" lang="en">Foods</option>
					<option value="DRU,Drugs" lang="en">Drugs</option>
					<option value="ENV,Environmental" lang="en">Environmental</option>
					<option value="OTH, Other" lang="en">Other</option>
				</select>
				<input id="AllergyName" type="text" style="margin-left:10px;width: 140px; float:left;font-size:14px;height:17px" lang="en" placeholder="Allergy Name">

				<div style="width:100%; float:left; text-align:center; height:10px; "></div>
				
				<div style="padding:3px;height: 30px;margin-top: 5px ">
					<span style="float: left; text-align:center; font-size:14px; " lang="en">Date: </span>
					<input id="dateEventAll" type="date" style="margin-left:10px; width: 150px; float:left; font-size:14px; height:17px">
				</div>
				<!--<input id="ageEventAll" type="text" style="margin-left:10px;width: 140px; float:left;font-size:14px;height:17px" lang="en" placeholder="Age of Outbreak">-->

				<div style="width:100%; float:left; text-align:center; height:10px; "></div>
					
				<span style="margin-left:5px;float: left; text-align:center; font-size:14px; ">Intensity </span>
				<div id="intensity-slider" style="float:left; margin-left:25px; width:200px;"></div>					
				<span  id="intensitylabel" style="float:left; text-align:center; font-size:12px; margin-top:0px; margin-left:10px"><font style="color:hsl(0, 100%, 20%);" lang="en">Very Low</font></span>
				<input id="intensity" type="text" style="float:left; text-align:center; margin-left: 20px; margin-top: -5px; width: 30px; font-size:12px; height:17px; visibility:hidden;">
				<a id="buttonNoAllergies" style="float:left; text-align:center; margin-left: 5px; margin-top: -5px; width: 150px; font-size:12px; height:17px;" class="btn" lang="en">No Known Allergies</a>


            </div>            
            <a id="ButtonAddAllergy" class="btn" style="height: 80px; width:50px; color:#22aeff; float:right; margin-top:-140px; margin-right:19px;"><span lang="en">Add</span></a>

    </div>
	
	<!-- MODAL VIEW FOR EDITING ALLERGY -->
    <div id="editAllergyModal" style="display:none; text-align:center; padding:20px;">

        <div style="color:#22aeff; font-size:14px; text-align: right; width: 70%; height: 10px; float: left; padding-bottom: 10px;"> <span lang="en">Edit known allergies for<span> <?php echo $UserName.' '.$UserSurname; ?> </div>
        <div id="AllergyContainer2" style="border:solid 1px #cacaca; height:80px; margin-top:30px; padding-top:5px; overflow:auto;">
        </div>

        <div style="border:solid 1px #cacaca; height:140px; margin-top:20px; padding:10px;">

				 
				

				
				<input id="DiagnosticRow2" type="hidden" style="margin-left:10px;width: 200px; float:left;font-size:14px;height:17px">
				<select id="AllergyCode2" type="text" style="width: 210px;float: left; margin-left: 5px;text-align:center;font-size:14px;height:27px"  />
					<option value="" lang="en">Allergy Codes</option>
					<option value="FOO,Foods" lang="en">Foods</option>
					<option value="DRU,Drugs" lang="en">Drugs</option>
					<option value="ENV,Environmental" lang="en">Environmental</option>
					<option value="OTH, Other" lang="en">Other</option>
				</select>
				<input id="AllergyName2" type="text" style="margin-left:10px;width: 140px; float:left;font-size:14px;height:17px" lang="en" placeholder="Allergy Name">

				<div style="width:100%; float:left; text-align:center; height:10px; "></div>
				
				<div style="padding:3px;height: 30px;margin-top: 5px ">
					<span style="float: left; text-align:center; font-size:14px; " lang="en">Date: </span>
					<input id="dateEventAll2" type="date" style="margin-left:10px; width: 150px; float:left; font-size:14px; height:17px">
				</div>
				<!--<input id="ageEventAll2" type="text" style="margin-left:10px;width: 140px; float:left;font-size:14px;height:17px" lang="en" placeholder="Age of Outbreak">-->

				<div style="width:100%; float:left; text-align:center; height:10px; "></div>
					
				<span style="margin-left:5px;float: left; text-align:center; font-size:14px; ">Intensity </span>
				<div id="intensity-slider2" style="float:left; margin-left:25px; width:200px;"></div>					
				<span  id="intensitylabel2" style="float:left; text-align:center; font-size:12px; margin-top:0px; margin-left:10px"><font style="color:hsl(0, 100%, 20%);" lang="en">Very Low</font></span>
				<input id="intensity2" type="text" style="float:left; text-align:center; margin-left: 20px; margin-top: -5px; width: 30px; font-size:12px; height:17px; visibility:hidden;">


            </div>            
            <a id="ButtonEditAllergy" class="btn" style="height: 80px; width:50px; color:#22aeff; float:right; margin-top:-140px; margin-right:19px;"><span lang="en">Edit</span></a>

    </div>
	<!-- Model for deleted allergies -->
	
	<div id="modalDeletedAllergy" style="display:none; text-align:center; padding:20px;">

        <div style="color:#22aeff; font-size:14px; text-align: right; width: 80%; height: 10px; float: left; padding-bottom: 10px;"> <span lang="en">Deleted allergies for</span> <?php echo $UserName.' '.$UserSurname; ?> </div>
		<div><img id="deletedAllergy" src="/icons/trashicon.jpg" style="margin-left:25px;" alt="Deleted Allergies" width="30" height="30"></div>
        <div id="AllergyContainerDeleted" style="border:solid 1px #cacaca; height:180px; margin-top:30px; padding-top:5px; overflow:auto;">
        </div>

        </div>
    <!-- END MODAL VIEW FOR ALLERGY -->

  	<!-- MODAL VIEW FOR ICD SEARCH -->
    <div id="modalICDSearch" style="overflow:visible; display:none;  padding:20px;">
        <div style="border:solid 1px #cacaca; margin-top:5px; padding:10px;">
			<input id="ICDLABox" type="text" style="margin-left:10px; margin-top:12px; width: 200px; float:left; font-size:14px; height:17px" lang="en" placeholder="Enter search term">
			<a id="ButtonSearchICD" class="btn" style="width:50px; color:#22aeff; float:right; margin-top:10px;margin-bottom:10px"><span lang="en">Search</span></a>
			<select id="ICDList" size="8" style="width:340px;" >
			</select>		
		</div>
        <div id="SelCode" style="float:left; width:60px; height:20px; border:0px solid #cacaca; margin-left:100px; margin-top:12px; color:#22aeff; font-size:14px;"></div>
        <div id="SelOption" style="display:none; float:left; width:60px; height:20px; border:1px solid #cacaca; margin-left:100px; margin-top:12px; color:#22aeff; font-size:14px;"></div>
        <a id="ButtonUpdateICDCode" class="btn" style="width:50px; color:#22aeff; float:right; margin-top:10px;margin-bottom:10px"><span lang="en">Add</span></a>
		
    </div>
    <!-- END MODAL VIEW FOR ICD SEARCH -->



	<!-- MODAL VIEW FOR DIAGNOSTICS -->
    <div id="modalDiagnostics" style="display:none; text-align:center; padding:20px;">
        <div style="color:#22aeff; font-size:14px; text-align: right; width: 70%; height: 10px; float: left; padding-bottom: 10px;"> <span lang="en">Diagnostics for</span> <?php echo $UserName.' '.$UserSurname; ?> </div>
		<div id="deletedDiagnostics"><img class="btn" src="/icons/trashicon.jpg" style="margin-left:25px;border:solid 1px #cacaca;" alt="Deleted Medications" width="15" height="15"></div>
        <div id="DiagnosticContainer" style="border:solid 1px #cacaca; height:150px; margin-top:30px; padding-top:5px; overflow:auto;">
        </div>
        <div style="border:solid 1px #cacaca;  margin-top:20px; padding:10px;height:240px">
			<span style="float: left;text-align:center;margin-top: 5px;font-size:14px;margin-left:5px" lang="en">Diagnostic Name: </span>
			<input id="field_id1" type="text" style="width: 1px; margin-top:5px;margin-left:10px; visibility:hidden;"><input id="DiagnosticBox" type="text" style="margin-left:10px;width: 200px; float:left;font-size:14px;height:17px" lang="en" placeholder="Enter Diagnostic Name">
			<!--<input id="ICD_id" type="text" style="width: 40px; margin-top:5px;margin-left:10px; visibility:hidden;">-->
			<?php 
			if($isPatient != 1){
			echo '<input id="ICDBox" type="text" style="margin-left:10px;width: 60px; float:left;font-size:14px;height:17px" placeholder="ICD 10">';
			}else{
			echo '<input id="ICDBox" type="hidden" style="margin-left:10px;width: 60px; float:left;font-size:14px;height:17px" placeholder="ICD 10">';
			} 
			?>

			<div style="width:100%;"></div>
			<div style="padding:3px;height: 30px;margin-top: 5px ">
				<span style="float: left;text-align:center;margin-top: 5px;font-size:14px;margin-left:5px" lang="en">Start Date: </span>
				<input id="DiagnosticStart" type="date" style="margin-left:10px;width: 200px; float:left;font-size:14px;height:17px">
			</div>
			<div style="width:100%;"></div>
			<div style="padding:3px;height: 30px;margin-top: 5px ">
				<span style="float: left;text-align:center;margin-top: 5px;font-size:14px;margin-left:5px" lang="en">End Date: </span>
				<input id="DiagnosticEnd" type="date" style="margin-left:18px;width: 200px; float:left;font-size:14px;height:17px">
            </div>		
			<div style="width:100%;"></div>
			<div style="padding:3px;height: 30px;margin-top: 5px ">
				<span style="float: left;text-align:center;margin-top: 5px;font-size:14px;margin-left:5px" lang="en">Notes: </span>
				<textarea rows="3" col="550" id="DiagnosticNotes" style="margin-left:38px;float:left;font-size:14px;width:250px"></textarea>
            </div>		
            
            <a id="ButtonAddDiagnostic" class="btn" style="height: 80px; width:50px; color:#22aeff; float:right; margin-top:-110px;"><span lang="en">Add</span></a>
			<a id="buttonNoDiagnostics" style="float:left; text-align:center; margin-left: 5px; width: 150px; font-size:12px; height:17px;" class="btn" lang="en">No Incidents to Report</a>
        </div>
    </div>
	<!-- MODAL VIEW FOR Editing DIAGNOSTICS -->
    <div id="editDiagnostics" style="display:none; text-align:center; padding:20px;">
        <div style="color:#22aeff; font-size:14px; text-align: right; width: 70%; height: 10px; float: left; padding-bottom: 10px;"> <span lang="en">Edit Diagnostics for</span> <?php echo $UserName.' '.$UserSurname; ?> </div>
		
        <div id="DiagnosticContainerEdit" style="border:solid 1px #cacaca; height:100px; margin-top:30px; padding-top:5px; overflow:auto;">
        </div>
        <div style="border:solid 1px #cacaca;  margin-top:20px; padding:10px;height:240px">
			<span style="float: left;text-align:center;margin-top: 5px;font-size:14px;margin-left:5px" lang="en">Diagnostic Name: </span>
			<input id="field_id1" type="text" style="width: 1px; margin-top:5px;margin-left:10px; visibility:hidden;"><input id="DiagnosticBox2" type="text" style="margin-left:10px;width: 200px; float:left;font-size:14px;height:17px" lang="en" placeholder="Enter Diagnostic Name">
			<input id="DiagnosticRow2" type="hidden" style="margin-left:10px;width: 200px; float:left;font-size:14px;height:17px">
			<!--<input id="ICD_id" type="text" style="width: 40px; margin-top:5px;margin-left:10px; visibility:hidden;">-->
			<?php 
			if($isPatient != 1){
			echo '<input id="ICDBox2" type="text" style="margin-left:10px;width: 60px; float:left;font-size:14px;height:17px" placeholder="ICD 10">';
			}else{
			echo '<input id="ICDBox2" type="hidden" style="margin-left:10px;width: 60px; float:left;font-size:14px;height:17px" placeholder="ICD 10">';
			} 
			?>

			<div style="width:100%;"></div>
			<div style="padding:3px;height: 30px;margin-top: 5px ">
				<span style="float: left;text-align:center;margin-top: 5px;font-size:14px;margin-left:5px" lang="en">Start Date: </span>
				<input id="DiagnosticStart2" type="date" style="margin-left:10px;width: 200px; float:left;font-size:14px;height:17px">
			</div>
			<div style="width:100%;"></div>
			<div style="padding:3px;height: 30px;margin-top: 5px ">
				<span style="float: left;text-align:center;margin-top: 5px;font-size:14px;margin-left:5px" lang="en">End Date: </span>
				<input id="DiagnosticEnd2" type="date" style="margin-left:18px;width: 200px; float:left;font-size:14px;height:17px">
            </div>		
			<div style="width:100%;"></div>
			<div style="padding:3px;height: 30px;margin-top: 5px ">
				<span style="float: left;text-align:center;margin-top: 5px;font-size:14px;margin-left:5px" lang="en">Notes: </span>
				<textarea rows="3" col="550" id="DiagnosticNotes2" style="margin-left:38px;float:left;font-size:14px;width:250px"></textarea>
            </div>		
            
            <a id="ButtonEditDiagnostic" class="btn" style="height: 80px; width:50px; color:#22aeff; float:right; margin-top:-110px;"><span lang="en">Edit</span></a>
			
        </div>
    </div>
	<!-- Model view for deleted diagnostics -->
	<div id="modalDeletedDiagnostics" style="display:none; text-align:center; padding:20px;">

        <div style="color:#22aeff; font-size:14px; text-align: right; width: 80%; height: 10px; float: left; padding-bottom: 10px;"> <span lang="en">Deleted diagnostics for</span> <?php echo $UserName.' '.$UserSurname; ?> </div>
		<div><img id="deletedDiagnostics" src="/icons/trashicon.jpg" style="margin-left:25px;" alt="Deleted Diagnostics" width="30" height="30"></div>
        <div id="DiagnosticContainerDeleted" style="border:solid 1px #cacaca; height:180px; margin-top:30px; padding-top:5px; overflow:auto;">
        </div>

        </div>
		
    <!-- END MODAL VIEW FOR DIAGNOSTICS -->
	

	<!-- MODAL VIEW FOR HABITS INFO -->
    <div id="modalHabitsInfo" style="overflow:visible;display:none;  padding:20px;">
        <div style="border:solid 1px #cacaca; margin-top:5px; padding:10px;">
		
			<table style="width:100%;background-color:white">
				<tr>
					<td style="width:15%"><span style="float: left;text-align:center;font-size:12px;margin-left:5px" lang="en">Cigarettes: </span></td>
					<td style="width:50%"><div id="cigarette-slider" style="margin-left:10px"></div></td>
					<td style="width:35%"><input id="cigarettes" type="text" style=";text-align:center;margin-left: 5px;margin-top: 10px;width: 25%; float:left;font-size:12px;height:17px"><span style="float: left;text-align:center;font-size:10px;margin-top: 10px;margin-left:5px" lang="en">Per Day</span></td>
				</tr>
				<tr>
					<td style="width:15%"><span style="float: left;text-align:center;font-size:12px;margin-left:5px" lang="en">Alcohol: </span></td>
					<td style="width:50%"><div id="alcohol-slider" style="margin-left:10px;"></div></td>
					<td style="width:35%"><input id="alcohol" type="text" style=";text-align:center;margin-left: 5px;margin-top: 10px;width: 25%; float:left;font-size:12px;height:17px"><span style="float: left;text-align:center;font-size:10px;margin-top: 10px;margin-left:5px" lang="en">Glass/Week</span></td>
				</tr>
				<tr>
					<td style="width:15%"><span style="float: left;text-align:center;font-size:12px;margin-left:5px" lang="en">Exercise: </span></td>
					<td style="width:50%"><div id="exercise-slider" style="margin-left:10px;"></div></td>
					<td style="width:35%"><input id="exercise" type="text" style=";text-align:center;margin-left: 5px;margin-top: 10px;width: 25%; float:left;font-size:12px;height:17px"><span style="float: left;text-align:center;font-size:10px;margin-top: 10px;margin-left:5px" lang="en">Hours/Week</span></td>
				</tr>
				<tr>
					<td style="width:15%"><span style="float: left;text-align:center;font-size:12px;margin-left:5px" lang="en">Sleep: </span></td>
					<td style="width:50%"><div id="sleep-slider" style="margin-left:10px;"></div></td>
					<td style="width:35%"><input id="sleep" type="text" style=";text-align:center;margin-left: 5px;margin-top: 10px;width: 25%; float:left;font-size:12px;height:17px"><span style="float: left;text-align:center;font-size:10px;margin-top: 10px;margin-left:5px" lang="en">Hours/Day</span></td>
				</tr>
				<!--<tr>
					<td style="width:20%"><span style="float: left;text-align:center;font-size:12px;margin-left:5px">Coffee: </span></td>
					<td style="width:50%"><div id="coffee-slider" style="margin-left:10px;"></div></td>
					<td style="width:30%"><input id="coffee" type="text" style=";text-align:center;margin-left: 5px;margin-top: 10px;width: 25%; float:left;font-size:12px;height:17px"><span style="float: left;text-align:center;font-size:12px;margin-top: 10px;margin-left:5px">hours/Week</span></td>
				</tr>-->  
			</table>
		</div>
        <a id="ButtonUpdateHabitsInfo" class="btn" style="width:50px; color:#22aeff; float:right; margin-top:10px;margin-bottom:10px"><span>Add</span></a>
		
    </div>
    <!-- END MODAL VIEW FOR HABITS INFO -->
		     
     
     <!--CONTENT MAIN START-->
     <?php 
        if($is_modal)
        {
            echo '<div style="width:1000px; height:660px; padding: 0px; padding-top: 30px;">';
        }
        else
        {
            echo '<div class="content">';
            echo '<div class="grid" class="grid span4" style="width:1000px; height:630px; margin: 0 auto; margin-top:30px; padding-top:30px;">';
        } 
    ?>
     
			<div id="BasicInfo" class="grid" class="grid span4" style="cursor:pointer;float:left;height:55px; margin:0px auto; margin-top:-20px; margin-left:10px; margin-right:10px; padding:20px;width:400px;display:table">
				<div style="display:table-cell; vertical-align:middle;">
                    <p id="Connect2" style="color:white; font-size:35px; font-weight:bold; text-align:center;  padding-top:8px;">
                        <i class="icon-spinner icon-spin" id="H2M_Spin" style="margin:0 auto; color:black;"></i>
                    </p>
                </div>	
			</div>
			<div  id="DiagnosticHistoryInfo" class="grid" class="grid span4" style="cursor:pointer; overflow:auto;float:right; width:495px; height:277px; margin:0px auto; margin-top:-20px;  margin-right:10px; padding:5px; display:table; position:relative;">
				<div style="display:table-cell; vertical-align:middle;">
                    <p style="color:white; font-size:35px; font-weight:bold; text-align:center;  padding-top:8px;">
                        <i class="icon-spinner icon-spin" id="H2M_Spin" style="margin:0 auto; color:black;"></i>
                    </p>
                </div>
			</div>
			<div id="AdditionalInfo" class="grid" class="grid span4" style="cursor:pointer;float:left;height:140px; margin:0px auto; margin-top:10px; margin-left:10px; margin-right:10px; padding:20px;width:400px;display:table">
				<div style="display:table-cell; vertical-align:middle;">
                    <p style="color:white; font-size:35px; font-weight:bold; text-align:center;  padding-top:8px;">
                        <i class="icon-spinner icon-spin" id="H2M_Spin" style="margin:0 auto; color:black;"></i>
                    </p>
                </div>	
			</div>
			<div id="MedicationInfo" class="grid" class="grid span4" style="cursor:pointer; float:left; overflow:auto; height:150px; margin:0px auto; margin-top:10px; margin-left:10px; margin-right:10px; padding:5px;width:315px;display:table;">
				<div style="display:table-cell; vertical-align:middle;">
                    <p style="color:white; font-size:35px; font-weight:bold; text-align:center;  padding-top:8px;">
                        <i class="icon-spinner icon-spin" id="H2M_Spin" style="margin:0 auto; color:black;"></i>
                    </p>
                </div>	
			</div>
			<div id="FamilyHistoryInfo" class="grid" class="grid span4" style="cursor:pointer; position:relative; float:left;height:150px; margin:0px auto; margin-top:10px; margin-right:10px; padding:5px;width:360px;display:table">
				<div style="display:table-cell; vertical-align:middle;">
                    <p style="color:white; font-size:35px; font-weight:bold; text-align:center;  padding-top:8px;">
                        <i class="icon-spinner icon-spin" id="H2M_Spin" style="margin:0 auto; color:black;"></i>
                    </p>
                </div>		
			</div>
			<div  id="HabitsInfo" class="grid" class="grid span4" style="cursor:pointer; float:right;height:300px; margin:0px auto; margin-top:10px;  margin-right:10px; padding:5px;width:245px;display:table">
				<div style="display:table-cell; vertical-align:middle;">
                    <p style="color:white; font-size:35px; font-weight:bold; text-align:center;  padding-top:8px;">
                        <i class="icon-spinner icon-spin" id="H2M_Spin" style="margin:0 auto; color:black;"></i>
                    </p>
                </div>		
			</div>
			<div id="ImmunizationAllergyInfo" class="grid" class="grid span4" style="cursor:pointer; float:left;height:140px; margin:0px auto; margin-top:10px; margin-left:10px; margin-right:5px; padding:0px; width:500px; display:table">
				<div style="display:table-cell; vertical-align:middle;">
                    <p style="color:white; font-size:35px; font-weight:bold; text-align:center;  padding-top:8px;">
                        <i class="icon-spinner icon-spin" id="H2M_SpinA" style="margin:0 auto; color:black;"></i>
                    </p>
                </div>
			</div>
			<div id="AllergyInfo" class="grid" class="grid span4" style="cursor:pointer; float:left;height:140px; margin:0px auto; margin-top:10px; margin-left:5px; margin-right:5px; padding:0px; width:195px; display:table">
				<div style="display:table-cell; vertical-align:middle;">
                    <p style="color:white; font-size:35px; font-weight:bold; text-align:center;  padding-top:8px;">
                        <i class="icon-spinner icon-spin" id="H2M_SpinA" style="margin:0 auto; color:black;"></i>
                    </p>
                </div>
			</div>
 		     
		</div>
     </div>
     <!--CONTENT MAIN END-->

    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="js/jquery-ui.min.js"></script>

    <!-- Libraries for notifications -->
    <script src="realtime-notifications/lib/gritter/js/jquery.gritter.min.js"></script>
	<script src="realtime-notifications/pusher.min.js"></script>
	<script src="realtime-notifications/PusherNotifier.js"></script>

	<link href="realtime-notifications/lib/gritter/css/jquery.gritter.css"rel="stylesheet" type="text/css" />
	<!--<script src="imageLens/jquery.js" type="text/javascript"></script>-->
	<script src="imageLens/jquery.imageLens.js" type="text/javascript"></script>
    <script>
		/*$(function() {
	    var pusher = new Pusher('d869a07d8f17a76448ed');
	    var channel_name=$('#MEDID').val();
		var channel = pusher.subscribe(channel_name);
		var notifier=new PusherNotifier(channel);
		
	  });*/
    </script>
    <!-- Libraries for notifications -->

    <script src="TypeWatch/jquery.typewatch.js"></script>
	
	<script src="build/js/intlTelInput.js"></script>
	<script src="js/isValidNumber.js"></script>
	
    <script type="text/javascript" >

	window.onload = function(){
    $( "#draggable" ).draggable();
    LoadDonuts();    
	};
	
	var translationdel = '';

		if(language == 'th'){
		translationdel = 'Bor';
		}else if(language == 'en'){
		translationdel = 'Del';
		}
	
	var timeoutTime = 18000000;
	//var timeoutTime = 300000;  //5minutes
	var timeoutTimer = setTimeout(ShowTimeOutWarning, timeoutTime);


	var active_session_timer = 60000; //1minute
	var sessionTimer = setTimeout(inform_about_session, active_session_timer);

    var reportcheck = new Array();
	var DrugList = [{"id": "1", "value": "Afghanistan", "label": "Afghanistan"}];
   
	//This function is called at regular intervals and it updates ongoing_sessions lastseen time
	function inform_about_session()
	{
		$.ajax({
			url: '<?php echo $domain ?> /ongoing_sessions.php?userid='+ <?php echo $_SESSION['MEDID'] ?> ,
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
    		//$('#newinbox').trigger('click');
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
  
  

  GetDrugList();                //Load Data in Drug List Autocomplete Box
  getBasicInfo();				//Load HTML into Basic Info Box
  getAdditionalInfo();			//Load HTML into Additional Info Box
  getMedicationInfo();			//Load HTML into Medication Info Box
  getFamilyHistoryInfo();		//Load HTML into Family History Info Box
  getImmunoAllergyInfo();		//Load HTML into ImmunizationAllergy Info Box
  getAllergyInfo();	            //Load HTML into Allergy Info Box
  getDiagnosticHistoryInfo();	//Load HTML into Diagnostic History Box
  getHabitsInfo();
  
	function getBasicInfo()
	{
		var link = 'getBasicPatientInfo.php?user=patient';
    
    
		$.ajax({
           url: link,
           dataType: "html",
           async: true,
           success: function(data)
           {
				$('#BasicInfo').html(data);
				var myElement = document.querySelector("#BasicInfo");
				myElement.style.display = "block";
				//alert('done');
           }
        });

	
	}
	
	function getAdditionalInfo()
	{
		var link = 'getAdditionalPatientInfo.php';
    
    
		$.ajax({
           url: link,
           dataType: "html",
           async: true,
           success: function(data)
           {
				$('#AdditionalInfo').html(data);
				var myElement = document.querySelector("#AdditionalInfo");
				myElement.style.display = "block";
				//alert('done');
           }
        });

	
	}
	
	function getMedicationInfo()
	{
		var link = 'getMedicationInfo.php';
    
    
		$.ajax({
           url: link,
           dataType: "html",
           async: true,
           success: function(data)
           {
				$('#MedicationInfo').html(data);
				var myElement = document.querySelector("#MedicationInfo");
				myElement.style.display = "block";
				//alert('done');
           }
        });

	
	}

	function getFamilyHistoryInfo()
	{
		var link = 'getFamilyHistoryInfo.php';
    
    
		$.ajax({
           url: link,
           dataType: "html",
           async: true,
           success: function(data)
           {
				$('#FamilyHistoryInfo').html(data);
				var myElement = document.querySelector("#FamilyHistoryInfo");
				myElement.style.display = "block";
				//alert('done');
           }
        });

	
	}

	function getImmunoAllergyInfo()
	{
		var link = 'getImmunoAllergyInfo.php';
    
    
		$.ajax({
           url: link,
           dataType: "html",
           async: true,
           success: function(data)
           {
				$('#ImmunizationAllergyInfo').html(data);
				var myElement = document.querySelector("#ImmunizationAllergyInfo");
				//$('#H2M_SpinA').css('visibility','hidden');
				myElement.style.display = "block";
           }
        });

	
	}
	
	function getAllergyInfo()
	{
		var link = 'getImmunoAllergyInfo.php?allergy=yes';
    
    
		$.ajax({
           url: link,
           dataType: "html",
           async: true,
           success: function(data)
           {
				$('#AllergyInfo').html(data);
				var myElement = document.querySelector("#AllergyInfo");
				//$('#H2M_SpinA').css('visibility','hidden');
				myElement.style.display = "block";
           }
        });

	
	}

	
	function getDiagnosticHistoryInfo()
	{
		var link = 'getDiagnosticInfo.php';
    
    
		$.ajax({
           url: link,
           dataType: "html",
           async: true,
           success: function(data)
           {
				//var optionBox = '<div class="grid" id="DiagnosticEditBox" style="display:none;opacity:0.8;cursor:pointer;float:right;position:absolute;margin-top:-20px;margin-left:420px;background-color:grey;font-size:15px">'+'<i class="icon-edit" style="float:right;padding:5px"> Edit </i></div>';
				//$('#DiagnosticHistoryInfo').html(optionBox+data);
				$('#DiagnosticHistoryInfo').html(data);
				var myElement = document.querySelector("#DiagnosticHistoryInfo");
				myElement.style.display = "block";
				//alert('done');
           }
        });

	
	}
	
	function getHabitsInfo()
	{
		var link = 'getHabitsInfo.php';
    
    
		$.ajax({
           url: link,
           dataType: "html",
           async: true,
           success: function(data)
           {
				$('#HabitsInfo').html(data);
				var myElement = document.querySelector("#HabitsInfo");
				myElement.style.display = "block";
				//alert('done');
           }
        });

	
	}
	
/*	var UserID = <?php echo $UserID;?>;
		
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
	*/
	
	
	//---------------CODE FOR ADDITIONAL INFO--------------------------------------------
	
	$("#Phone").intlTelInput();
	
	$('#AdditionalInfo').live('click',function()	{
		//alert('clicked');
		fillAdditionalInfoDialogBox();
		
		$("#modalAdditionalInfo").dialog({bgiframe: true, width: 400, height: 450, modal: true,title:"Patient Information"});
	});
    $('#BasicInfo').live('click',function()	{
		//alert('clicked');
		fillAdditionalInfoDialogBox();
		
		$("#modalAdditionalInfo").dialog({bgiframe: true, width: 400, height: 450, modal: true,title:"Patient Information"});
	});
	$("#height_type").on('change', function()
    {
        if($(this).val() == 'ft')
        {
            $("#height1label").text('ft');
            $("#height2label").text('in');
        }
        else
        {
            $("#height1label").text('m');
            $("#height2label").text('cm');
        }
    });
	
	function fillAdditionalInfoDialogBox()
	{
		var insurance = $('#inp-Insurance').text();
		if(insurance=='Unknown')
		{
			insurance='';
		}
	
		var address = $('#inp-Address').text();
		if(address=='Unknown')
		{
			address='';
		}
		
		var city = $('#inp-City').text();
		if(city=='Unknown')
		{
			city='';
		}
		
		var zip = $('#inp-Zip').text();
		if(zip=='Unknown')
		{
			zip='';
		}
        
        var gender = $('#inp-Gender').text();
        
        var dob = $('#RAWDOB').val();

		
		var phone = $('#inp-Phone').text();
		if(phone=='Unknown')
		{
			phone='';
		}
		else
		{
			$("#Phone").intlTelInput("setNumber", phone);
		}
		
		var email = $('#inp-Email').text();
		if(email=='Unknown')
		{
			email='';
		}
        
        var blood_type = $('#BLOODTYPE').val();
        var weight = $('#WEIGHT').val();
        var weight_type = $('#WEIGHTTYPE').val();
        if(weight_type == 1)
        {
            weight_type = 'lb';
        }
        else
        {
            weight_type = 'kg';
        }
        
        var height1 = $('#HEIGHT1').val();
        var height2 = $('#HEIGHT2').val();
        var height_type = $('#HEIGHTTYPE').val();
        if(height_type == 1)
        {
            height_type = 'ft';
        }
        else
        {
            height_type = 'm';
        }
        if(height_type == 'ft')
        {
            $("#height1label").text('ft');
            $("#height2label").text('in');
        }
        else
        {
            $("#height1label").text('m');
            $("#height2label").text('cm');
        }
		
		$('#Insurance').val(insurance);
		$('#Address').val(address);
		$('#City').val(city);
		$('#Zip').val(zip);
		//Phone is handled above
		$('#Email').val(email);
        $('#Gender').val(gender);
        $('#DOB').val(dob);
        $('#blood_type').val(blood_type);
        $('#blood_type').change();
        $('#weight').val(weight);
        $('#weight_type').val(weight_type);
        $('#weight_type').change();
		$('#height1').val(height1);
        $('#height2').val(height2);
        $('#height_type').val(height_type);
        $('#height_type').change();
	
	}
	
	
	$("#ButtonUpdateAdditionalInfo").click(function() {
		if($("#Phone").intlTelInput("isValidNumber")==false)
		{
			alert('Invalid Phone Number');
			$('#Phone').focus();
			return;
		}
		else
		{	
			//$('#Phone').val($('#Phone').val().replace(/-/g, '')); //remove dashes
			$('#Phone').val($('#Phone').val().replace(/\s+/g, '')); //remove spaces
		}

		if(validateEmail($('#Email').val())==false)
		{
			alert('Invalid Email');
			$('#Email').focus();
			return;
	    }
		
		var insurance = $('#Insurance').val();
		var address = $('#Address').val();
		var city = $('#City').val();
		var zip = $('#Zip').val();
		var phone = $('#Phone').val();
		var email = $('#Email').val();
        var gender = $('#Gender').val();
        var dob = $('#DOB').val();
        var blood_type = $("#blood_type").val();
        var weight = $("#weight").val();
        var weight_type = $("#weight_type").val();
        var height1 = $("#height1").val();
        var height2 = $("#height2").val();
        var height_type = $("#height_type").val();
        if(weight_type == 'lb')
        {
            weight_type = 1;
        }
        else
        {
            weight_type = 2;
        }
        if(height_type == 'ft')
        {
            height_type = 1;
        }
        else
        {
            height_type = 2;
        }
        $.post("validate_email.php", {email: email, type: '0'}, function(data, status)
        {
            console.log(data);
            if(data == 1)
            {
                var url = 'updateAdditionalInfo.php?insurance='+insurance+'&address='+address+'&city='+city+'&zip='+zip+'&phone='+phone+'&email='+email+'&gender='+gender+'&dob='+dob+'&blood_type='+blood_type+'&weight='+weight+'&weight_type='+weight_type+'&height1='+height1+'&height2='+height2+'&height_type='+height_type;
                console.log(url);
                //alert(url);
                var Rectipo = LanzaAjax(url);
                //alert(Rectipo);
                getBasicInfo();
                getAdditionalInfo();
                $("#modalAdditionalInfo").dialog('close');
            }
            else if(data == 2)
            {
                alert("Invalid Email");
            }
            else
            {
                alert("Another user is already using this email");
            }
        });
	});
	
	function validateEmail(email) { 
		if(email=='')
		{
			return true;
		}
	
		var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
		return re.test(email);
	}
	
	//---------------END CODE FOR ADDITIONAL INFO--------------------------------------------
	
	//---------------CODE FOR MEDICATIONS--------------------------------------------
	
	$('.numbersOnly').keyup(function () { 
        this.value = this.value.replace(/[^0-9\.]/g,'');
    });
  
    function GetDrugList()
	{
		
		var queUrl ='getDrugList.php';	
		//alert(queUrl);	
		var drugs='';	
		$.ajax(
		{
			url: queUrl,
			dataType: "json",
			async:false,
			success: function(data)
			{
				drugs= data.items;
				//alert(data.items);
				
			},
            error: function(data, errorThrown){
               alert(errorThrown);
              } 
		});
		
		DrugList=drugs;
		//alert(DrugList);	
		
	}
	
	$("#DrugBox").autocomplete({
        source: DrugList,
        minLength: 1,
        select: function(event, ui) {
            // feed hidden id field
            $("#field_id").val(ui.item.id);
            //alert($("#field_id").val());
        },
               
		// if user enter some drug which is not in our list
        change: function (event, ui) {
            if (ui.item === null) {
                $('#field_id').val('DB00000');
				//alert($("#field_id").val());
            }
        }
    });
	
	$("#DrugBox2").autocomplete({
        source: DrugList,
        minLength: 1,
        select: function(event, ui) {
            // feed hidden id field
            $("#field_id").val(ui.item.id);
            //alert($("#field_id").val());
        },
               
		// if user enter some drug which is not in our list
        change: function (event, ui) {
            if (ui.item === null) {
                $('#field_id').val('DB00000');
				//alert($("#field_id").val());
            }
        }
    });
   
	var translation2 = '';

		if(language == 'th'){
		translation2 = 'Medicamentos';
		}else if(language == 'en'){
		translation2 = 'Medications';
		}
		
	$('#MedicationInfo').live('click',function()	{
		//alert('clicked');
		getMedications()
		$("#modalMedication").dialog({bgiframe: true, width: 550, height: 470, modal: true,title:translation2});
	});
	
	
	function getSelectedText(elementId) {
		var elt = document.getElementById(elementId);

		if (elt.selectedIndex == -1)
			return null;

		return elt.options[elt.selectedIndex].text;
	}

	$("#ButtonAddMedication").click(function() {
		/*var idPatient = */
		var drugname = $('#DrugBox').val();
		var drugcode = $('#field_id').val();
		var frequency = '';
		var dossage = '';
		var numDays=-1;
		var factor=1;
		
		if(drugname=='')
		{
			alert('Enter Drugname');
			$('#DrugBox').focus();
			return;
			
		}
		
		if($('#NumberPills').val()=='')
		{
		   dossage='';
		}
		else
		{
		var translation5 = '';

		if(language == 'th'){
		translation5 = ' caps/';
		}else if(language == 'en'){
		translation5 = ' pills/';
		}
		  dossage=$('#NumberPills').val()+translation5 + getSelectedText('Frequency');
		}
		
		if($('#NumberDays').val()=='')
		{
		   frequency='';
		}
		else
		{
		  var unit = getSelectedText('Time');	
		  frequency=$('#NumberDays').val()+' ' + unit;
		  
		  //Calculate number of days this medicine was taken
		  if(unit=='Days')
		  {
			factor=1;
		  }
		  else if(unit=='Weeks')
		  {
			factor=7;
		  }
		  else if(unit=='Months')
		  {
			factor=30;
		  }
		  else if(unit=='Years')
		  {
			factor=365;
		  }
		  numDays=parseInt($('#NumberDays').val())*factor;
		  		  
		}
		
		var url = 'createMedicationData.php?drugname='+drugname+'&drugcode='+drugcode+'&dossage='+dossage+'&frequency='+frequency+'&numDays='+numDays;
		//alert(url);
		//return;
		var Rectipo = LanzaAjax(url);
		//console.log(url);
		getMedications();
	
		//clear data
		$('#DrugBox').val("");
		$('#NumberPills').val("");
		$('#NumberDays').val("");
		getMedicationInfo();
				
	});
	
	$("#ButtonEditMedication").click(function() {
		/*var idPatient = */
		var idrow = $('#DiagnosticRow2').val();
		var drugname = $('#DrugBox2').val();
		var drugcode = $('#field_id2').val();
		var frequency = '';
		var dossage = '';
		var numDays=-1;
		var factor=1;
//		alert(idrow);
		
		if(drugname=='')
		{
			alert('Enter Drugname');
			$('#DrugBox2').focus();
			return;
			
		}
		
		if($('#NumberPills2').val()=='')
		{
		   dossage='';
		}
		else
		{
		  dossage=$('#NumberPills2').val()+' pills/' + getSelectedText('Frequency');
		}
		
		if($('#NumberDays2').val()=='')
		{
		   frequency='';
		}
		else
		{
		  var unit = getSelectedText('Time');	
		  frequency=$('#NumberDays2').val()+' ' + unit;
		  
		  //Calculate number of days this medicine was taken
		  if(unit=='Days')
		  {
			factor=1;
		  }
		  else if(unit=='Weeks')
		  {
			factor=7;
		  }
		  else if(unit=='Months')
		  {
			factor=30;
		  }
		  else if(unit=='Years')
		  {
			factor=365;
		  }
		  numDays=parseInt($('#NumberDays2').val())*factor;
		  		  
		}
		
		var url = 'createMedicationData.php?drugname='+drugname+'&drugcode='+drugcode+'&dossage='+dossage+'&frequency='+frequency+'&numDays='+numDays+'&rowediter='+idrow;
		//alert(url);
		//return;
		var Rectipo = LanzaAjax(url);
		//console.log(url);
		getMedications();
	
		//clear data
		$('#DrugBox2').val("");
		$('#NumberPills2').val("");
		$('#NumberDays2').val("");
		getMedicationInfo();
		$("#editMedications").dialog('close');		
	});
  
	function getMedications()
	{
		var queUrl ='getMedications.php';
		$.ajax(
		{
			url: queUrl,
			dataType: "json",
			async: false,
			success: function(data)
			{
				MedicationData = data.items;
			}
		});
	
		numMedications = MedicationData.length;
	
		var n = 0;
		var MedicationBox
		if (numMedications==0)
		{
		var translation51 = '';

		if(language == 'th'){
		translation51 = 'No se encontraron datos';
		}else if(language == 'en'){
		translation51 = 'No Data Found';
		}
			MedicationBox='<span>'+translation51+'</span>';
		}
		else
		{
			MedicationBox='';
		}
		
		while (n<numMedications){
			var del = MedicationData[n].deleted;
			var drugname = MedicationData[n].drugname;
			var frequency = MedicationData[n].frequency;
			var dossage = MedicationData[n].dossage;
			var rowid = MedicationData[n].id;	
			if(del==0)
			{
			MedicationBox += '<div class="InfoRow">';
			
			
			var f=1;
			var d=1;
			
			if(frequency=='' || frequency==null)
			{
				f=0;
			}
			
			if(dossage=='' || dossage==null)
			{
				d=0;
			}
			
			var middlecolumn='Unknown';
			
			switch(f)
			{
				case 0:	switch(d)
						{
							case 0:middlecolumn='Unknown';
								   break;
							case 1:middlecolumn=dossage;
								   break;
						}
						break;
				
				case 1:switch(d)
						{
							case 0:middlecolumn=frequency;
								   break;
							case 1:middlecolumn=frequency + ' for ' + dossage;
								   break;
						}
						break;
			}
			
			
					MedicationBox += '<div style="width:160px; float:left; text-align:left;"><span class="PatName">'+drugname+'</span></div>';
					MedicationBox += '<div style="width:170px; float:left; text-align:left; color:#22aeff;"><span class="DrName">'+middlecolumn +' </span></div>';
					MedicationBox += '<div class="EditMedication" id="'+rowid+'" style="width:60px; float:right;margin-right:10px;"><a id="'+rowid+'"  class="btn" style="height: 15px; padding-top: 0px; margin-top: -5px; color:green;" lang="en">Edit</a></div>';
					MedicationBox += '<div class="DeleteMedication" id="'+rowid+'" style="width:60px; float:right;"><a id="'+rowid+'"  class="btn" style="height: 15px; padding-top: 0px; margin-top: -5px; color:red;" lang="en">'+translationdel+'</a></div>';
				
			
			MedicationBox += '</div>';
			}
			n++;
		}
		$('#MedicationContainer').html(MedicationBox);
	
	}
	
	function getMedicationsEdit(toShow)
	{
		var queUrl ='getMedications.php';
		$.ajax(
		{
			url: queUrl,
			dataType: "json",
			async: false,
			success: function(data)
			{
				MedicationData = data.items;
			}
		});
	
		numMedications = MedicationData.length;
		var displayRow = $('#DiagnosticRow2').val(toShow);
		var n = 0;
		var MedicationBox
		if (numMedications==0)
		{
		var translation51 = '';

		if(language == 'th'){
		translation51 = 'No se encontraron datos';
		}else if(language == 'en'){
		translation51 = 'No Data Found';
		}
			MedicationBox='<span>'+translation51+'</span>';
		}
		else
		{
			MedicationBox='';
		}
		
		while (n<numMedications){
			var del = MedicationData[n].deleted;
			var drugname = MedicationData[n].drugname;
			var frequency = MedicationData[n].frequency;
			var dossage = MedicationData[n].dossage;
			var rowid = MedicationData[n].id;	
			if(del==0)
			{
			if(rowid == toShow){
			MedicationBox += '<div class="InfoRow">';
			
			
			var f=1;
			var d=1;
			
			if(frequency=='' || frequency==null)
			{
				f=0;
			}
			
			if(dossage=='' || dossage==null)
			{
				d=0;
			}
			
			var middlecolumn='Unknown';
			
			switch(f)
			{
				case 0:	switch(d)
						{
							case 0:middlecolumn='Unknown';
								   break;
							case 1:middlecolumn=dossage;
								   break;
						}
						break;
				
				case 1:switch(d)
						{
							case 0:middlecolumn=frequency;
								   break;
							case 1:middlecolumn=frequency + ' for ' + dossage;
								   break;
						}
						break;
			}
			translatione1 = '';
				if(language == 'th'){
		translatione1 = 'Edición';
		}else if(language == 'en'){
		translatione1 = 'Editing';
		}
			
					MedicationBox += '<div style="width:180px; float:left; text-align:left;"><span class="PatName">'+drugname+'</span></div>';
					MedicationBox += '<div style="width:180px; float:left; text-align:left; color:#22aeff;"><span class="DrName">'+middlecolumn +' </span></div>';
					MedicationBox += '<div class="DeleteMedication" id="'+rowid+'" style="width:60px; float:left;">'+translatione1+'</div>';
				
			
			MedicationBox += '</div>';
			}
			}
			n++;
		}
		$('#MedicationContainerEdit').html(MedicationBox);
		var rowtrack = $('#DiagnosticRow2').val(toShow);
	
	}
	
	function getMedicationsDeleted()
	{
		var queUrl ='getMedications.php';
		$.ajax(
		{
			url: queUrl,
			dataType: "json",
			async: false,
			success: function(data)
			{
				MedicationData = data.items;
			}
		});
	
		numMedications = MedicationData.length;
	
		var n = 0;
		var MedicationBox
		if (numMedications==0)
		{
		var translation51 = '';

		if(language == 'th'){
		translation51 = 'No se encontraron datos';
		}else if(language == 'en'){
		translation51 = 'No Data Found';
		}
			MedicationBox='<span>'+translation51+'</span>';
		}
		else
		{
			MedicationBox='';
		}
		
		while (n<numMedications){
			var del = MedicationData[n].deleted;
			var drugname = MedicationData[n].drugname;
			var frequency = MedicationData[n].frequency;
			var dossage = MedicationData[n].dossage;
			var rowid = MedicationData[n].id;	
			if(del==1)
			{
			MedicationBox += '<div class="InfoRow">';
			
			
			var f=1;
			var d=1;
			
			if(frequency=='' || frequency==null)
			{
				f=0;
			}
			
			if(dossage=='' || dossage==null)
			{
				d=0;
			}
			
			var middlecolumn='Unknown';
			
			switch(f)
			{
				case 0:	switch(d)
						{
							case 0:middlecolumn='Unknown';
								   break;
							case 1:middlecolumn=dossage;
								   break;
						}
						break;
				
				case 1:switch(d)
						{
							case 0:middlecolumn=frequency;
								   break;
							case 1:middlecolumn=frequency + ' for ' + dossage;
								   break;
						}
						break;
			}
			
			
					MedicationBox += '<div style="width:100px; float:left; text-align:left;"><span class="PatName"><font size="0">'+drugname+'</font></span></div>';
					MedicationBox += '<div style="width:150px; float:left; text-align:left; color:#22aeff;"><span class="DrName"><font size="0">'+middlecolumn +' </font></span></div>';
					//MedicationBox += '<div class="DeleteMedication" id="'+rowid+'" style="width:60px; float:right;"><a id="'+rowid+'"  class="btn" style="height: 15px; padding-top: 0px; margin-top: -5px; color:red;">Del</a></div>';
				
			
			MedicationBox += '</div>';
			}
			n++;
		}
		$('#MedicationContainerDeleted').html(MedicationBox);
	
	}
	
	$(".DeleteMedication").live('click',function(){
		var myClass = $(this).attr("id"); 
		//alert (myClass);
		var cadena='deleteMedication.php?id='+myClass;
		var RecTipo=LanzaAjax(cadena);
		getMedications();
		getMedicationInfo();

	});
  
	$("#Frequency").change(function() {
		var selectedValue = parseInt($(this).attr('value'));
		$('#Time').empty();
		//alert(selectedValue);
		
		var i;
		for(i=selectedValue;i<5;i++)
		{
			
			switch(i)
			{
				case 1:$('#Time').append('<option value="1">Days</option>');
					   break;
				case 2:$('#Time').append('<option value="2">Weeks</option>');
					   break;
				case 3:$('#Time').append('<option value="3">Months</option>');
					   break;
				case 4:$('#Time').append('<option value="4">Years</option>');
					   break;	   
			}
			
		}
		
		
		
	});
	
	
  
  //--------------------------------------END CODE FOR MEDICATIONS--------------------------------------------
 
 
  //-------------------------------------- CODE FOR FAMILY HISTORY -------------------------------------------------------------
  var translation3 = '';

		if(language == 'th'){
		translation3 = 'Historia Familiar';
		}else if(language == 'en'){
		translation3 = 'Family History';
		}
	$('#FamilyHistoryInfo').live('click',function()	{
		//alert('clicked');
		getRelatives()
		$("#modalFamilyHistory").dialog({bgiframe: true, width: 550, height: 500, modal: true,title:translation3});
	});


	$("#ButtonAddRelative").click(function() {
		/*var idPatient = */
		var relativecodeI = $('#TypeRelative').val();
		var relativename = $("#TypeRelative option:selected").text();
		var disease = $('#DiseaseName').val();
		var diseasegroup = $('#DiseaseGroup').val();
		var ICD10 = $('#ICD10').val();
		var ICD9 = $('#ICD9').val();
		var ageevent = $('#AgeEvent').val();
		var isdeadcheck = $('input[name="isDeadCheck"]:checked').val();

		if(relativename == '')
		{
			alert('Select Relative');
			$('#TypeRelative').focus();
			return;			
		}

		if(disease == '')
		{
			alert('Select Disease');
			$('#DiseaseName').focus();
			return;			
		}

		if(diseasegroup == '')
		{
			alert('Select Disease Group');
			$('#DiseaseGroup').focus();
			return;			
		}

		if(ageevent == '')
		{
			alert('Select Age at the Event');
			$('#AgeEvent').focus();
			return;			
		}
		
	
		
		var url = 'createFamilyData.php?relativename='+relativename+'&relativecode='+relativecodeI+'&diseasename='+disease+'&diseasegroup='+diseasegroup+'&ICD10='+ICD10+'&ICD9='+ICD9+'&ageevent='+ageevent+'&isdeadcheck='+isdeadcheck;
		//alert(url);
		//return;
		console.log(url);
		var Rectipo = LanzaAjax(url);
		getRelatives();
		
	
		//clear data
		$('#TypeRelative').val("");
		$('#DiseaseName').val("");
		$('#DiseaseGroup').val("");
		$('#AgeEvent').val("");
		$('#isDeadCheck').val("");
		$('#ICD10').val("");
		$('#ICD9').val("");

		getFamilyHistoryInfo();
				
	});
	
	$("#ButtonEditRelative").click(function() {
		/*var idPatient = */
		var relativecodeI = $('#TypeRelative2').val();
		var relativename = $("#TypeRelative2 option:selected").text();
		var disease = $('#DiseaseName2').val();
		var diseasegroup = $('#DiseaseGroup2').val();
		var ICD10 = $('#ICD102').val();
		var ICD9 = $('#ICD92').val();
		var ageevent = $('#AgeEvent2').val();
		var idrow = $('#DiagnosticRow2').val();
		var isdeadcheck = $('input[name="isDeadCheck2"]:checked').val();
//		alert(idrow);

		if(relativename == '')
		{
			alert('Select Relative');
			$('#TypeRelative2').focus();
			return;			
		}

		if(disease == '')
		{
			alert('Select Disease');
			$('#DiseaseName2').focus();
			return;			
		}

		if(diseasegroup == '')
		{
			alert('Select Disease Group');
			$('#DiseaseGroup2').focus();
			return;			
		}

		if(ageevent == '')
		{
			alert('Select Age at the Event');
			$('#AgeEvent2').focus();
			return;			
		}
		
	
		
		var url = 'createFamilyData.php?relativename='+relativename+'&relativecode='+relativecodeI+'&diseasename='+disease+'&diseasegroup='+diseasegroup+'&ICD10='+ICD10+'&ICD9='+ICD9+'&ageevent='+ageevent+'&isdeadcheck='+isdeadcheck+'&rowediter='+idrow;
		//alert(url);
		//return;
		console.log(url);
		var Rectipo = LanzaAjax(url);
		getRelatives();
		
	
		//clear data
		$('#TypeRelative2').val("");
		$('#DiseaseName2').val("");
		$('#DiseaseGroup2').val("");
		$('#AgeEvent2').val("");
		$('#isDeadCheck2').val("");
		$('#ICD102').val("");
		$('#ICD92').val("");
		$('#DiagnosticRow2').val("");

		getFamilyHistoryInfo();
		$("#editFamilyHistoryModal").dialog('close');
	});
  
	function getRelatives()
	{
		var queUrl ='getRelatives.php';
		$.ajax(
		{
			url: queUrl,
			dataType: "json",
			async: false,
			success: function(data)
			{
				RelativesData = data.items;
			}
		});
	
		numRelatives = RelativesData.length;
	
		var n = 0;
		var RelativesBox
		if (numRelatives==0)
		{
		var translation51 = '';

		if(language == 'th'){
		translation51 = 'No se encontraron datos';
		}else if(language == 'en'){
		translation51 = 'No Data Found';
		}
			RelativesBox='<span>'+translation51+'</span>';
		}
		else
		{
			RelativesBox='';
		}
		
		while (n<numRelatives){
			var del = RelativesData[n].deleted;
			var relativename = RelativesData[n].relative;
			var relativetype = RelativesData[n].relativetype;
			var disease = RelativesData[n].disease;
			var diseasegroup = RelativesData[n].diseasegroup;
			var ICD10 = RelativesData[n].ICD10;
			var ICD9 = RelativesData[n].ICD9;
			var ageevent = RelativesData[n].atage;
			var rowid = RelativesData[n].id;	

			var middlecolumn = disease + ' @ '+ageevent;
			if(del==0)
			{
			RelativesBox += '<div class="InfoRow">';
			
			
					RelativesBox += '<div style="width:180px; float:left; text-align:left;"><span class="PatName">'+relativename+'</span></div>';
					RelativesBox += '<div style="width:150px; float:left; text-align:left; color:#22aeff;"><span class="DrName">'+middlecolumn +' </span></div>';
					RelativesBox += '<div class="EditFamilyHistory" id="'+rowid+'" style="width:60px; float:right;height:30px;margin-right:10px;"><a id="'+rowid+'"  class="btn" style="height: 15px; padding-top: 0px; margin-top: -5px; color:green;" lang="en">Edit</a></div>';
					RelativesBox += '<div class="DeleteRelative" id="'+rowid+'" style="width:60px; float:right;height:30px;"><a id="'+rowid+'"  class="btn" style="height: 15px; padding-top: 0px; margin-top: -5px; color:red;" lang="en">'+translationdel+'</a></div>';
			
			
			RelativesBox += '</div>';
			}
			n++;
		}
		$('#RelativesContainer').html(RelativesBox);
	    //alert (RelativesBox);
	    
	}
	
	function getRelativesEdit(toShow)
	{
		var queUrl ='getRelatives.php';
		$.ajax(
		{
			url: queUrl,
			dataType: "json",
			async: false,
			success: function(data)
			{
				RelativesData = data.items;
			}
		});
	
		numRelatives = RelativesData.length;
	
		var n = 0;
		var RelativesBox
		if (numRelatives==0)
		{
		var translation51 = '';

		if(language == 'th'){
		translation51 = 'No se encontraron datos';
		}else if(language == 'en'){
		translation51 = 'No Data Found';
		}
			RelativesBox='<span>'+translation51+'</span>';
		}
		else
		{
			RelativesBox='';
		}
		
		while (n<numRelatives){
			var del = RelativesData[n].deleted;
			var relativename = RelativesData[n].relative;
			var relativetype = RelativesData[n].relativetype;
			var disease = RelativesData[n].disease;
			var diseasegroup = RelativesData[n].diseasegroup;
			var ICD10 = RelativesData[n].ICD10;
			var ICD9 = RelativesData[n].ICD9;
			var ageevent = RelativesData[n].atage;
			var rowid = RelativesData[n].id;	

			var middlecolumn = disease + ' @ '+ageevent;
			if(del==0)
			{
			if(rowid==toShow)
			{
			RelativesBox += '<div class="InfoRow">';
			
			translatione1 = '';
				if(language == 'th'){
		translatione1 = 'Edición';
		}else if(language == 'en'){
		translatione1 = 'Editing';
		}
					RelativesBox += '<div style="width:200px; float:left; text-align:left;"><span class="PatName">'+relativename+'</span></div>';
					RelativesBox += '<div style="width:140px; float:left; text-align:left; color:#22aeff;"><span class="DrName">'+middlecolumn +' </span></div>';
					RelativesBox += '<div class="DeleteRelative" id="'+rowid+'" style="width:60px; float:left;height:30px;">'+translatione1+'</div>';
			
			
			RelativesBox += '</div>';
			}
			}
			n++;
		}
		$('#RelativesContainerEdit').html(RelativesBox);
		var rowtrack = $('#DiagnosticRow2').val(toShow);
	    //alert (RelativesBox);
	    
	}
	
	function getRelativesDeleted()
	{
		var queUrl ='getRelatives.php';
		$.ajax(
		{
			url: queUrl,
			dataType: "json",
			async: false,
			success: function(data)
			{
				RelativesData = data.items;
			}
		});
	
		numRelatives = RelativesData.length;
	 var translation51 = '';

		if(language == 'th'){
		translation51 = 'No se encontraron datos';
		}else if(language == 'en'){
		translation51 = 'No Data Found';
		}
		var n = 0;
		var RelativesBox
		if (numRelatives==0)
		{
			RelativesBox='<span>'+translation51+'</span>';
		}
		else
		{
			RelativesBox='';
		}
		
		while (n<numRelatives){
			var del = RelativesData[n].deleted;
			var relativename = RelativesData[n].relative;
			var relativetype = RelativesData[n].relativetype;
			var disease = RelativesData[n].disease;
			var diseasegroup = RelativesData[n].diseasegroup;
			var ICD10 = RelativesData[n].ICD10;
			var ICD9 = RelativesData[n].ICD9;
			var ageevent = RelativesData[n].atage;
			var rowid = RelativesData[n].id;	

			var middlecolumn = disease + ' @ '+ageevent;
			if(del==1)
			{
			RelativesBox += '<div class="InfoRow">';
			
			
					RelativesBox += '<div style="width:150px; float:left; text-align:left;"><span class="PatName"><font size="0">'+relativename+'</font></span></div>';
					RelativesBox += '<div style="width:100px; float:left; text-align:left; color:#22aeff;"><span class="DrName"><font size="0">'+middlecolumn +' </font></span></div>';
			//		RelativesBox += '<div class="DeleteRelative" id="'+rowid+'" style="width:60px; float:right;height:30px;"><a id="'+rowid+'"  class="btn" style="height: 15px; padding-top: 0px; margin-top: -5px; color:red;">Del</a></div>';
			
			
			RelativesBox += '</div>';
			}
			n++;
		}
		$('#RelativesContainerDeleted').html(RelativesBox);
	    //alert (RelativesBox);
	    
	}

var prev_click='';
	$(".DeleteRelative").live('click',function(){
		var myClass = $(this).attr("id"); 
		//alert (myClass);
		prev_click='delete';
		var cadena='deleteRelative.php?id='+myClass;
		var RecTipo=LanzaAjax(cadena);
		getRelatives();
		getFamilyHistoryInfo();

	});



  //-------------------------------------- END CODE FOR FAMILY HISTORY ---------------------------------------------------------

  //-------------------------------------- CODE FOR IMMUNIZATION AND ALLERGY -------------------------------------------------------------
  var translation41 = '';
			var translation42 = '';
			var translation43 = '';
			var translation44 = '';
			var translation45 = '';
			var translation46 = '';

		if(language == 'th'){
		translation41 = 'Muy Leve';
		translation42 = 'Leve';
		translation43 = 'Moderada';
		translation44 = 'Severa';
		translation45 = 'Muy Severa';
		translation46 = 'Extrema';
		}else if(language == 'en'){
		translation41 = 'Very Low';
		translation42 = 'Low';
		translation43 = 'Medium';
		translation44 = 'High';
		translation45 = 'Very High';
		translation46 = 'Extreme';
		}
	var dob = '';
	$( "#intensity-slider" ).slider({
	      range: "min",
	      value: 0,
	      min: 0,
	      max: 5,
	      slide: function( event, ui ) {
	        $( "#intensity" ).val( ui.value );
	        switch (ui.value)
	        {
			
		        case 0:	$("#intensitylabel" ).html('<font style="color:hsl(0, 100%, 20%);">'+translation41+'</font>');
						break;
		        case 1:	$("#intensitylabel" ).html('<font style="color:hsl(0, 100%, 26%);">'+translation42+'</font>');
						break;
		        case 2:	$("#intensitylabel" ).html('<font style="color:hsl(0, 100%, 32%);">'+translation43+'</font>');
						break;
		        case 3:	$("#intensitylabel" ).html('<font style="color:hsl(0, 100%, 38%);">'+translation44+'</font>');
						break;
		        case 4:	$("#intensitylabel" ).html('<font style="color:hsl(0, 100%, 44%);">'+translation45+'</font>');
						break;
		        case 5:	$("#intensitylabel" ).html('<font style="color:hsl(0, 100%, 50%);">'+translation46+'</font>');
						break;
	        }
	        $( "#intensity" ).val( ui.value );
	        
      }
    });
	
	$( "#intensity-slider2" ).slider({
	      range: "min",
	      value: 0,
	      min: 0,
	      max: 5,
	      slide: function( event, ui ) {
	        $( "#intensity2" ).val( ui.value );
	        switch (ui.value)
	        {
		        case 0:	$("#intensitylabel2" ).html('<font style="color:hsl(0, 100%, 20%);">'+translation41+'</font>');
						break;
		        case 1:	$("#intensitylabel2" ).html('<font style="color:hsl(0, 100%, 26%);">'+translation42+'</font>');
						break;
		        case 2:	$("#intensitylabel2" ).html('<font style="color:hsl(0, 100%, 32%);">'+translation43+'</font>');
						break;
		        case 3:	$("#intensitylabel2" ).html('<font style="color:hsl(0, 100%, 38%);">'+translation44+'</font>');
						break;
		        case 4:	$("#intensitylabel2" ).html('<font style="color:hsl(0, 100%, 44%);">'+translation45+'</font>');
						break;
		        case 5:	$("#intensitylabel2" ).html('<font style="color:hsl(0, 100%, 50%);">'+translation46+'</font>');
						break;
	        }
	        $( "#intensity2" ).val( ui.value );
	        
      }
    });
        var translation4 = '';

		if(language == 'th'){
		translation4 = 'Calendario De Vacunación';
		}else if(language == 'en'){
		translation4 = 'Immunization History';
		}

	$('#ImmunizationAllergyInfo').live('click',function()	{
		getVaccines();
//		dob = getDOB();
//		$("#UDOB").html('DOB: '+dob);
		
		$("#modalImmunizationAllergy").dialog({bgiframe: true, width: 550, height: 500, modal: true,title:translation4});
	});
	
	$('#AllergyInfo').live('click',function()	{
		getAllergy();
		
//		dob = getDOB();
//		$("#UDOB").html('DOB: '+dob);
		
		var translation = '';
		if(language == 'th'){
		translation = 'Alergias Conocidas';
		}else if(language == 'en'){
		translation = 'Allergy History';
		}
		
		$("#modalAllergy").dialog({bgiframe: true, width: 550, height: 500, modal: true,title:translation});
	});

	$("#VaccineName,#VaccineCode,#AllergyName,#AllergyCode").focus(function() {
		$("#VaccineName").val('');
		$("#VaccineCode").val('');
		$("#AllergyName").val('');
		$("#AllergyCode").val('');
	});

	$("#VaccineName").blur(function() {
		$("#AllergyName").val('');
		$("#AllergyCode").val('');
	});

	$("#VaccineCode").change(function() {
		var value = $(this).attr('value');
		var split = value.split(",");
		var v1 = split[0];
		var v2 = split[1];		

		var selectedVaccine = v2 ;
		
		$("#VaccineName").val(v2);		
			
	});
	
	$("#VaccineCode2").change(function() {
		var value = $(this).attr('value');
		var split = value.split(",");
		var v1 = split[0];
		var v2 = split[1];		

		var selectedVaccine = v2 ;
		
		$("#VaccineName2").val(v2);		
			
	});

	$("#AllergyCode").change(function() {
		var value = $(this).attr('value');
		var split = value.split(",");
		var v1 = split[0];
		var v2 = split[1];		

		var selectedAllergy = v2 ;
		
		$("#AllergyName").val(v2);
				
	});
	
	$("#AllergyCode2").change(function() {
		var value = $(this).attr('value');
		var split = value.split(",");
		var v1 = split[0];
		var v2 = split[1];		

		var selectedAllergy = v2 ;
		
		$("#AllergyName2").val(v2);
				
	});

	$("#ageEvent,#dateEvent").focus(function() {
		$("#ageEvent").val('');
		$("#dateEvent").val('');
	});

/*	$("#dateEvent").change(function() {
		var value = $(this).attr('value');
		var selecteddate = value ;

		eventd = new Date(selecteddate); // remember this is equivalent to 06 01 2010
		var dobconv = dob.substring(0, 4)+'-'+dob.substring(4, 6);
		Adob = new Date(dobconv); // remember this is equivalent to 06 01 2010 

		a = calcDate(eventd,Adob)
				
		unitdate = 'y';
		validvalue = a[2];
		if (a[2] < 1)
		{
			unitdate = 'm';
			validvalue = a[1];
		}	
		$("#ageEvent").val(validvalue+' '+unitdate);
				
	});*/


	$("#ButtonAddVaccine").click(function() {
		var value = $("#VaccineCode option:selected").text();
		var value = $("#VaccineCode option:selected").val();
		var split = value.split(",");
		var v1 = split[0];
		var v2 = split[1];		
		var VaccCode = v1;
		var VaccName = $("#VaccineName").val();

		var value = $("#AllergyCode option:selected").text();
		var value = $("#AllergyCode option:selected").val();
		var split = value.split(",");
		var v1 = split[0];
		var v2 = split[1];		
		var AllerCode = v1;
		var AllerName = $("#AllergyName").val();
//		alert(VaccName);
		if(VaccCode == '' && VaccName == '')
		{
			alert('Select Vaccine or Input Vaccine');
			$('#VaccineCode').focus();
			return;			
		}

		var intensity = $('#intensity').val();
		var dateEvent = $('#dateEvent').val();
		var ageevent = $('#ageEventVac').val();

		
		if(ageevent == '' && dateEvent == '')
		{
			alert('Select Age at the time of Event');
			$('#AgeEvent').focus();
			return;			
		}
		
		
//		var dob = getDOB();
		
		var url = 'createImmunoData.php?VaccCode='+VaccCode+'&VaccName='+VaccName+'&AllerCode='+AllerCode+'&AllerName='+AllerName+'&intensity='+intensity+'&dateEvent='+dateEvent+'&ageEvent='+ageevent+'&dob='+dob;
		var Rectipo = LanzaAjax(url);
		getVaccines();		
	
		//clear data
		$('#VaccCode').val("");
		$('#VaccName').val("");
		$('#AllerCode').val("");
		$('#AllerName').val("");
		$('#intensity').val("");
		$('#intensitylabel').val("");
		$('#dateEvent').val("");
		$('#ageEvent').val("");

		getImmunoAllergyInfo();
				
	});
	
	$("#ButtonEditVaccine").click(function() {
		var idrow = $('#DiagnosticRow2').val();
		var value = $("#VaccineCode2 option:selected").text();
		var value = $("#VaccineCode2 option:selected").val();
		var split = value.split(",");
		var v1 = split[0];
		var v2 = split[1];		
		var VaccCode = v1;
		var VaccName = $("#VaccineName2").val();
//		alert(idrow);

		var value = $("#AllergyCode option:selected").text();
		var value = $("#AllergyCode option:selected").val();
		var split = value.split(",");
		var v1 = split[0];
		var v2 = split[1];		
		var AllerCode = v1;
		var AllerName = $("#AllergyName").val();

		if(VaccName == '')
		{
			alert('Select Vaccine');
			$('#VaccineCode2').focus();
			return;			
		}

		var intensity = $('#intensity').val();
		var dateEvent = $('#dateEvent2').val();
		var ageevent = $('#ageEventVac2').val();

		
		if(ageevent == '' && dateEvent == '')
		{
			alert('Select Age at the time of Event');
			$('#AgeEvent2').focus();
			return;			
		}
		
		
//		var dob = getDOB();
		
		var url = 'createImmunoData.php?VaccCode='+VaccCode+'&VaccName='+VaccName+'&AllerCode='+AllerCode+'&AllerName='+AllerName+'&intensity='+intensity+'&dateEvent='+dateEvent+'&ageEvent='+ageevent+'&dob='+dob+'&rowediter='+idrow;
		var Rectipo = LanzaAjax(url);
		getVaccines();		
	
		//clear data
		$('#VaccCode').val("");
		$('#VaccName').val("");
		$('#AllerCode').val("");
		$('#AllerName').val("");
		$('#intensity').val("");
		$('#intensitylabel').val("");
		$('#dateEvent').val("");
		$('#ageEvent').val("");
		$('#DiagnosticRow2').val("");

		getImmunoAllergyInfo();
		$("#editVaccineModal").dialog('close');
	});
	
	$("#ButtonAddVaccineCalendar").click(function() {
		var value = $("#VaccineCalendarImport option:selected").text();
		var value = $("#VaccineCalendarImport option:selected").val();
//		dob = getDOB();
//		alert(dob);
		var url = 'createImmunoDataCalendar.php?country='+value+'&dob='+dob;
		var Rectipo = LanzaAjax(url);
		getVaccines();	
				
	
		//clear data
		$('#VaccineCalendarImport').val("");
		getImmunoAllergyInfo();
		getAllergyInfo();
				
	});
	
	$(".DeleteVaccine").live('click',function(){
		var myClass = $(this).attr("id"); 
		//alert (myClass);
		var cadena='deleteVaccine.php?id='+myClass;
		var RecTipo=LanzaAjax(cadena);
		getVaccines();
		getImmunoAllergyInfo();

	});
	
	
	
	$("#ButtonAddAllergy").click(function() {
		var value = $("#VaccineCode option:selected").text();
		var value = $("#VaccineCode option:selected").val();
		var split = value.split(",");
		var v1 = split[0];
		var v2 = split[1];		
		var VaccCode = v1;
		var VaccName = $("#VaccineName").val();

		var value = $("#AllergyCode option:selected").text();
		var value = $("#AllergyCode option:selected").val();
		var split = value.split(",");
		var v1 = split[0];
		var v2 = split[1];		
		var AllerCode = v1;
		var AllerName = $("#AllergyName").val();

		if(AllerName == '')
		{
			alert('Select Allergy');
			$('#VaccineCode').focus();
			return;			
		}

		var intensity = $('#intensity').val();
		var dateEvent = $('#dateEventAll').val();
		var ageEvent = $('#ageEventAll').val();

		if(ageEvent == '' && dateEvent == '')
		{
			alert('Select Age at the time of Event');
			$('#AgeEvent').focus();
			return;			
		}
		
//		var dob = getDOB();
		
		var url = 'createImmunoData.php?VaccCode='+VaccCode+'&VaccName='+VaccName+'&AllerCode='+AllerCode+'&AllerName='+AllerName+'&intensity='+intensity+'&dateEvent='+dateEvent+'&ageEvent='+ageEvent+'&dob='+dob;
		var Rectipo = LanzaAjax(url);
		getAllergy(0);		
	
		//clear data
		$('#VaccCode').val("");
		$('#VaccName').val("");
		$('#AllerCode').val("");
		$('#AllerName').val("");
		$('#intensity').val("");
		$('#intensitylabel').val("");
		$('#dateEvent').val("");
		$('#ageEvent').val("");

		getAllergyInfo();
				
	});
	
	$("#ButtonEditAllergy").click(function() {
	var idrow = $('#DiagnosticRow2').val();
		var value = $("#VaccineCode2 option:selected").text();
		var value = $("#VaccineCode2 option:selected").val();
		var split = value.split(",");
		var v1 = split[0];
		var v2 = split[1];		
		var VaccCode = v1;
		var VaccName = $("#VaccineName2").val();
//		alert(idrow);

		var value = $("#AllergyCode2 option:selected").text();
		var value = $("#AllergyCode2 option:selected").val();
		var split = value.split(",");
		var v1 = split[0];
		var v2 = split[1];		
		var AllerCode = v1;
		var AllerName = $("#AllergyName2").val();

		if(AllerName == '')
		{
			alert('Select Allergy');
			$('#VaccineCode2').focus();
			return;			
		}

		var intensity = $('#intensity2').val();
		var dateEvent = $('#dateEventAll2').val();
		var ageEvent = $('#ageEventAll2').val();

		if(ageEvent == '' && dateEvent == '')
		{
			alert('Select Age at the time of Event');
			$('#AgeEvent2').focus();
			return;			
		}
		
//		var dob = getDOB();
		
		var url = 'createImmunoData.php?VaccCode='+VaccCode+'&VaccName='+VaccName+'&AllerCode='+AllerCode+'&AllerName='+AllerName+'&intensity='+intensity+'&dateEvent='+dateEvent+'&ageEvent='+ageEvent+'&dob='+dob+'&rowediter='+idrow;
		var Rectipo = LanzaAjax(url);
		getAllergy(0);		
	
		//clear data
		$('#VaccCode2').val("");
		$('#VaccName2').val("");
		$('#AllerCode2').val("");
		$('#AllerName2').val("");
		$('#intensity2').val("");
		$('#intensitylabel2').val("");
		$('#dateEvent2').val("");
		$('#ageEvent2').val("");
		$('#DiagnosticRow2').val("");

		getAllergyInfo();
		$("#editAllergyModal").dialog('close');
	});
	
	$("#buttonNoAllergies").click(function() {

	var r=confirm('This action will delete all previous allergies and will prevent future allergies from being displayed.  Is this really what you would like to do?');
	 	 if (r==true)
	 	 {
		var url = 'createImmunoData.php?noallergies=yes';
		var Rectipo = LanzaAjax(url);
		getAllergy(0);	
	
		getAllergyInfo();
				}
	});
	
	$("#buttonNoDrugs").click(function() {

	var r=confirm('This action will delete all previous medications and will prevent future medications from being displayed.  Is this really what you would like to do?');
	 	 if (r==true)
	 	 {
		var url = 'createMedicationData.php?nomeds=yes';
		var Rectipo = LanzaAjax(url);
		getMedications();	
	
		getMedicationInfo();
		}
				
	});
	
	$("#buttonNoDiagnostics").click(function() {

	var r=confirm('This action will delete all previous diagnostics and will prevent future diagnostics from being displayed.  Is this really what you would like to do?');
	 	 if (r==true)
	 	 {
		var url = 'createDiagnosticData.php?nodiag=yes';
		var Rectipo = LanzaAjax(url);
		getDiagnostics(0);	
	
		getDiagnosticHistoryInfo();
		}
				
	});
 
 	function getDOB()
	{
		var queUrl ='getDOB.php';
		$.ajax(
		{
			url: queUrl,
			dataType: "json",
			async: false,
			success: function(data)
			{
				DOBData = data.items;
			}
		});
	
		numDOB = DOBData.length;
		return DOBData[0].dob;
		
	}
 
	function getVaccines()
	{
		var queUrl ='getVaccines.php';
		$.ajax(
		{
			url: queUrl,
			dataType: "json",
			async: false,
			success: function(data)
			{
				VaccinesData = data.items;
			}
		});
	
		numVaccines = VaccinesData.length;
	
		var n = 0;
		var VaccinesBox
		if (numVaccines==0)
		{
		var translation51 = '';

		if(language == 'th'){
		translation51 = 'No se encontraron datos';
		}else if(language == 'en'){
		translation51 = 'No Data Found';
		}
			VaccinesBox='<span>'+translation51+'</span>';
		}
		else
		{
			VaccinesBox='';
		}
		
		today = new Date();
		eventd = new Date(2010,05,01); // remember this is equivalent to 06 01 2010
		dob = new Date(2003,8,11); // remember this is equivalent to 06 01 2010

		a = calcDate(eventd,dob)
		
		while (n<numVaccines){
			var del = VaccinesData[n].deleted;
			var VaccCode = VaccinesData[n].VaccCode;
			var VaccName = VaccinesData[n].VaccName;
			var AllerCode = VaccinesData[n].AllerCode;
			var AllerName = VaccinesData[n].AllerName;
			var intensity = VaccinesData[n].intensity;
			var dateEvent = VaccinesData[n].dateEvent;
			var ageEvent = VaccinesData[n].ageEvent;

			var rowid = VaccinesData[n].id;	

			if (VaccName != '')
			{
				var isAllergy = 0;
				var leftcolumn = VaccCode;
				var middlecolumn = 'at '+ ageEvent+' of age';
			

			if(del==0)
			{
			VaccinesBox += '<div class="InfoRow">';
					
			
					VaccinesBox += '<div style="width:180px; float:left; text-align:left;"><span class="PatName">'+leftcolumn+'</span></div>';
					VaccinesBox += '<div style="width:150px; float:left; text-align:left; color:#22aeff;"><span class="DrName">'+middlecolumn +' </span></div>';
					VaccinesBox += '<div class="EditVaccine" id="'+rowid+'" style="width:60px; float:right;margin-right:10px;"><a id="'+rowid+'"  class="btn" style="height: 15px; padding-top: 0px; margin-top: -5px; color:green;" lang="en">Edit</a></div>';
					VaccinesBox += '<div class="DeleteVaccine" id="'+rowid+'" style="width:60px; float:right;"><a id="'+rowid+'"  class="btn" style="height: 15px; padding-top: 0px; margin-top: -5px; color:red;" lang="en">'+translationdel+'</a></div>';
			
			
			VaccinesBox += '</div>';
			}
			}
			n++;
	
		}
		$('#VaccinesContainer').html(VaccinesBox);
	    //alert (RelativesBox);
	    
	}
	
	function getVaccinesEdit(toShow)
	{
		var queUrl ='getVaccines.php';
		$.ajax(
		{
			url: queUrl,
			dataType: "json",
			async: false,
			success: function(data)
			{
				VaccinesData = data.items;
			}
		});
	
		numVaccines = VaccinesData.length;
	
		var n = 0;
		var VaccinesBox
		if (numVaccines==0)
		{
		var translation51 = '';

		if(language == 'th'){
		translation51 = 'No se encontraron datos';
		}else if(language == 'en'){
		translation51 = 'No Data Found';
		}
			VaccinesBox='<span>'+translation51+'</span>';
		}
		else
		{
			VaccinesBox='';
		}
		
		today = new Date();
		eventd = new Date(2010,05,01); // remember this is equivalent to 06 01 2010
		dob = new Date(2003,8,11); // remember this is equivalent to 06 01 2010

		a = calcDate(eventd,dob)
		
		while (n<numVaccines){
			var del = VaccinesData[n].deleted;
			var VaccCode = VaccinesData[n].VaccCode;
			var VaccName = VaccinesData[n].VaccName;
			var AllerCode = VaccinesData[n].AllerCode;
			var AllerName = VaccinesData[n].AllerName;
			var intensity = VaccinesData[n].intensity;
			var dateEvent = VaccinesData[n].dateEvent;
			var ageEvent = VaccinesData[n].ageEvent;

			var rowid = VaccinesData[n].id;	

			if (VaccName != '')
			{
				var isAllergy = 0;
				var leftcolumn = VaccCode;
				var middlecolumn = 'at '+ ageEvent+' of age';
			

			if(del==0)
			{
			if(toShow == rowid){
			VaccinesBox += '<div class="InfoRow">';
					
			translatione1 = '';
				if(language == 'th'){
		translatione1 = 'Edición';
		}else if(language == 'en'){
		translatione1 = 'Editing';
		}
					VaccinesBox += '<div style="width:180px; float:left; text-align:left;"><span class="PatName">'+leftcolumn+'</span></div>';
					VaccinesBox += '<div style="width:160px; float:left; text-align:left; color:#22aeff;"><span class="DrName">'+middlecolumn +' </span></div>';
					VaccinesBox += '<div class="DeleteVaccine" id="'+rowid+'" style="width:60px; float:left;">'+translatione1+'</div>';
			
			
			VaccinesBox += '</div>';
			}
			}
			}
			n++;
	
		}
		$('#VaccinesContainer2').html(VaccinesBox);
	    //alert (RelativesBox);
	    var rowtrack = $('#DiagnosticRow2').val(toShow);
	}
	
	function getVaccinesDeleted()
	{
		var queUrl ='getVaccines.php';
		$.ajax(
		{
			url: queUrl,
			dataType: "json",
			async: false,
			success: function(data)
			{
				VaccinesData = data.items;
			}
		});
	
		numVaccines = VaccinesData.length;
	
		var n = 0;
		var VaccinesBox
		if (numVaccines==0)
		{
		var translation51 = '';

		if(language == 'th'){
		translation51 = 'No se encontraron datos';
		}else if(language == 'en'){
		translation51 = 'No Data Found';
		}
			VaccinesBox='<span>'+translation51+'</span>';
		}
		else
		{
			VaccinesBox='';
		}
		
		today = new Date();
		eventd = new Date(2010,05,01); // remember this is equivalent to 06 01 2010
		dob = new Date(2003,8,11); // remember this is equivalent to 06 01 2010

		a = calcDate(eventd,dob)
		
		while (n<numVaccines){
			var del = VaccinesData[n].deleted;
			var VaccCode = VaccinesData[n].VaccCode;
			var VaccName = VaccinesData[n].VaccName;
			var AllerCode = VaccinesData[n].AllerCode;
			var AllerName = VaccinesData[n].AllerName;
			var intensity = VaccinesData[n].intensity;
			var dateEvent = VaccinesData[n].dateEvent;
			var ageEvent = VaccinesData[n].ageEvent;

			var rowid = VaccinesData[n].id;	

			if (VaccName != '')
			{
				var isAllergy = 0;
				var leftcolumn = VaccCode;
				var middlecolumn = 'at '+ ageEvent+' of age';
			

			if(del==1)
			{
			VaccinesBox += '<div class="InfoRow">';
					
			
					VaccinesBox += '<div style="width:150px; float:left; text-align:left;"><span class="PatName"><font size="0">'+leftcolumn+'</font></span></div>';
					VaccinesBox += '<div style="width:150px; float:left; text-align:left; color:#22aeff;"><span class="DrName"><font size="0">'+middlecolumn +' </font></span></div>';
			//		VaccinesBox += '<div class="DeleteVaccine" id="'+rowid+'" style="width:60px; float:right;"><a id="'+rowid+'"  class="btn" style="height: 15px; padding-top: 0px; margin-top: -5px; color:red;">Del</a></div>';
			
			
			VaccinesBox += '</div>';
			}
			}
			n++;
	
		}
		$('#VaccinesContainerDeleted').html(VaccinesBox);
	    //alert (RelativesBox);
	    
	}
	
	function getAllergy()
	{
		var queUrl ='getVaccines.php';
		$.ajax(
		{
			url: queUrl,
			dataType: "json",
			async: false,
			success: function(data)
			{
				VaccinesData = data.items;
			}
		});
	
		numVaccines = VaccinesData.length;
	
		var n = 0;
		var VaccinesBox
		if (numVaccines==0)
		{
		var translation51 = '';

		if(language == 'th'){
		translation51 = 'No se encontraron datos';
		}else if(language == 'en'){
		translation51 = 'No Data Found';
		}
			VaccinesBox='<span>'+translation51+'</span>';
		}
		else
		{
			VaccinesBox='';
		}
		
		today = new Date();
		eventd = new Date(2010,05,01); // remember this is equivalent to 06 01 2010
		dob = new Date(2003,8,11); // remember this is equivalent to 06 01 2010

		a = calcDate(eventd,dob)
		
		while (n<numVaccines){
			var del = VaccinesData[n].deleted;
			var VaccCode = VaccinesData[n].VaccCode;
			var VaccName = VaccinesData[n].VaccName;
			var AllerCode = VaccinesData[n].AllerCode;
			var AllerName = VaccinesData[n].AllerName;
			var intensity = VaccinesData[n].intensity;
			var dateEvent = VaccinesData[n].dateEvent;
			var ageEvent = VaccinesData[n].ageEvent;

			var rowid = VaccinesData[n].id;	
   
			if (VaccName == '')
			{
			var translation31 = '';
			var translation32 = '';
			var translation33 = '';

		if(language == 'th'){
		translation31 = 'Alérgico a';
		translation32 = 'desde';
		translation33 = 'de edad';
		if(AllerName == 'Environmental'){
		AllerName = 'Ambiental';
		} else if(AllerName == 'Foods'){
		AllerName = 'Comidas';
		} else if(AllerName == 'Drugs'){
		AllerName = 'Medicamentos';
		} else if(AllerName == 'Other'){
		AllerName = 'Otros';
		}
		}else if(language == 'en'){
		translation31 = 'Allergic to';
		translation32 = 'since';
		translation33 = 'of age';
		}
				var isAllergy = 1;
				var leftcolumn = translation31+' '+AllerName;
				var middlecolumn = translation32+' '+ageEvent+' '+translation33;
			
if(del==0)
			{
			VaccinesBox += '<div class="InfoRow">';
					
			
					VaccinesBox += '<div style="width:210px; float:left; text-align:left;"><span class="PatName">'+leftcolumn+'</span></div>';
					VaccinesBox += '<div style="width:140px; float:left; text-align:left; color:#22aeff;"><span class="DrName">'+middlecolumn +' </span></div>';
					VaccinesBox += '<div class="EditAllergy" id="'+rowid+'" style="width:60px; float:right;margin-right:10px;"><a id="'+rowid+'"  class="btn" style="height: 15px; padding-top: 0px; margin-top: -5px; color:green;" lang="en">Edit</a></div>';
					VaccinesBox += '<div class="DeleteAllergy" id="'+rowid+'" style="width:60px; float:right;"><a id="'+rowid+'"  class="btn" style="height: 15px; padding-top: 0px; margin-top: -5px; color:red;" lang="en">'+translationdel+'</a></div>';
				
			
			VaccinesBox += '</div>';
			}
			}
			n++;
		}
		$('#AllergyContainer').html(VaccinesBox);
	    //alert (RelativesBox);
	    
	}
	
	function getAllergiesEdit(toShow)
	{
		var queUrl ='getVaccines.php';
		$.ajax(
		{
			url: queUrl,
			dataType: "json",
			async: false,
			success: function(data)
			{
				VaccinesData = data.items;
			}
		});
	
		numVaccines = VaccinesData.length;
	
		var n = 0;
		var VaccinesBox
		if (numVaccines==0)
		{
		var translation51 = '';

		if(language == 'th'){
		translation51 = 'No se encontraron datos';
		}else if(language == 'en'){
		translation51 = 'No Data Found';
		}
			VaccinesBox='<span>'+translation51+'</span>';
		}
		else
		{
			VaccinesBox='';
		}
		
		today = new Date();
		eventd = new Date(2010,05,01); // remember this is equivalent to 06 01 2010
		dob = new Date(2003,8,11); // remember this is equivalent to 06 01 2010

		a = calcDate(eventd,dob)
		
		while (n<numVaccines){
			var del = VaccinesData[n].deleted;
			var VaccCode = VaccinesData[n].VaccCode;
			var VaccName = VaccinesData[n].VaccName;
			var AllerCode = VaccinesData[n].AllerCode;
			var AllerName = VaccinesData[n].AllerName;
			var intensity = VaccinesData[n].intensity;
			var dateEvent = VaccinesData[n].dateEvent;
			var ageEvent = VaccinesData[n].ageEvent;

			var rowid = VaccinesData[n].id;	
   
			if (VaccName == '')
			{
			translation31 = '';
			translation32 = '';
			translation33 = '';
			translation34 = '';
				if(language == 'th'){
		translation31 = 'Alérgico a';
		translation32 = 'desde';
		translation33 = 'de edad';
		translation34 = 'Edición';
		if(AllerName == 'Environmental'){
		AllerName = 'Ambiental';
		} else if(AllerName == 'Foods'){
		AllerName = 'Comidas';
		} else if(AllerName == 'Drugs'){
		AllerName = 'Medicamentos';
		} else if(AllerName == 'Other'){
		AllerName = 'Otros';
		}
		}else if(language == 'en'){
		translation31 = 'Allergic to';
		translation32 = 'since';
		translation33 = 'of age';
		translation34 = 'Editing';
		}
				var isAllergy = 1;
				var leftcolumn = translation31+' '+AllerName;
				var middlecolumn = translation32+' '+ageEvent+' '+translation33;
			
if(del==0)
			{
			if(toShow == rowid){
			VaccinesBox += '<div class="InfoRow">';
					
			
					VaccinesBox += '<div style="width:210px; float:left; text-align:left;"><span class="PatName">'+leftcolumn+'</span></div>';
					VaccinesBox += '<div style="width:140px; float:left; text-align:left; color:#22aeff;"><span class="DrName">'+middlecolumn +' </span></div>';
					VaccinesBox += '<div class="DeleteAllergy" id="'+rowid+'" style="width:60px; float:left;">'+translation34+'</div>';
				
			
			VaccinesBox += '</div>';
			}
			}
			}
			n++;
		}
		$('#AllergyContainer2').html(VaccinesBox);
	    //alert (RelativesBox);
	    var rowtrack = $('#DiagnosticRow2').val(toShow);
	}
	
	function getAllergyDeleted()
	{
		var queUrl ='getVaccines.php';
		$.ajax(
		{
			url: queUrl,
			dataType: "json",
			async: false,
			success: function(data)
			{
				VaccinesData = data.items;
			}
		});
	
		numVaccines = VaccinesData.length;
	
		var n = 0;
		var VaccinesBox
		if (numVaccines==0)
		{
		var translation51 = '';

		if(language == 'th'){
		translation51 = 'No se encontraron datos';
		}else if(language == 'en'){
		translation51 = 'No Data Found';
		}
			VaccinesBox='<span>'+translation51+'</span>';
		}
		else
		{
			VaccinesBox='';
		}
		
		today = new Date();
		eventd = new Date(2010,05,01); // remember this is equivalent to 06 01 2010
		dob = new Date(2003,8,11); // remember this is equivalent to 06 01 2010

		a = calcDate(eventd,dob)
		
		while (n<numVaccines){
			var del = VaccinesData[n].deleted;
			var VaccCode = VaccinesData[n].VaccCode;
			var VaccName = VaccinesData[n].VaccName;
			var AllerCode = VaccinesData[n].AllerCode;
			var AllerName = VaccinesData[n].AllerName;
			var intensity = VaccinesData[n].intensity;
			var dateEvent = VaccinesData[n].dateEvent;
			var ageEvent = VaccinesData[n].ageEvent;

			var rowid = VaccinesData[n].id;	
   
			if (VaccName == '')
			{
				var isAllergy = 1;
				var leftcolumn = 'Allergic to '+AllerName;
				var middlecolumn =' since '+ageEvent+' of age';
			
if(del==1)
			{
			VaccinesBox += '<div class="InfoRow">';
					
			
					VaccinesBox += '<div style="width:175px; float:left; text-align:left;"><span class="PatName"><font size="0">'+leftcolumn+'</font></span></div>';
					VaccinesBox += '<div style="width:125px; float:left; text-align:left; color:#22aeff;"><span class="DrName"><font size="0">'+middlecolumn +' </font></span></div>';
			//		VaccinesBox += '<div class="DeleteAllergy" id="'+rowid+'" style="width:60px; float:right;"><a id="'+rowid+'"  class="btn" style="height: 15px; padding-top: 0px; margin-top: -5px; color:red;">Del</a></div>';
				
			
			VaccinesBox += '</div>';
			}
			}
			n++;
		}
		$('#AllergyContainerDeleted').html(VaccinesBox);
	    //alert (RelativesBox);
	    
	}


	
	//Deleted Allergy
	$(".DeleteAllergy").live('click',function(){
		var myClass = $(this).attr("id"); 
		//alert (myClass);
		var cadena='deleteVaccine.php?id='+myClass;
		var RecTipo=LanzaAjax(cadena);
		getAllergy();
		getAllergyInfo();

	});

	function calcDate(date1,date2) {
	    var diff = Math.floor(date1.getTime() - date2.getTime());
	    var day = 1000* 60 * 60 * 24;
	
	    var days = Math.floor(diff/day);
	    var months = Math.floor(days/31);
	    var years = Math.floor(months/12);
	
	    var message = date2.toDateString();
	    message += " was "
	    message += days + " days " 
	    message += months + " months "
	    message += years + " years ago \n"
		    
	    var cadena = Array();
	    cadena[0] = days;
	    cadena[1] = months;
	    cadena[2] = years;
	    
		return cadena;
		
	    }

  //-------------------------------------- END CODE FOR IMMUNIZATION AND ALLERGY ---------------------------------------------------------
 var translation = '';

		if(language == 'th'){
		translation = 'Historia Personal';
		}else if(language == 'en'){
		translation = 'Past Diagnostics';
		}
  $('#DiagnosticHistoryInfo').live('click',function()	{
		getDiagnostics();
		$("#modalDiagnostics").dialog({bgiframe: true, width: 550, height: 570, modal: true,title:translation});
  });

  $('#ICDBox').live('click',function()	{
		$("#modalICDSearch").dialog({bgiframe: true, width: 400, height: 400, modal: true,title:"ICD10 LookUp"});
  });
  
  var translation11 = '';

		if(language == 'th'){
		translation11 = 'Alergias Eliminados';
		}else if(language == 'en'){
		translation11 = 'Deleted Allergies';
		}
  $('#deletedAllergy').live('click',function()	{
  getAllergyDeleted();
		$("#modalDeletedAllergy").dialog({bgiframe: true, width: 400, height: 400, modal: true,title:translation11});
  });
  
  var translation12 = '';

		if(language == 'th'){
		translation12 = 'Diagnóstico Eliminados';
		}else if(language == 'en'){
		translation12 = 'Deleted Diagnostics';
		}
  
   $('#deletedDiagnostics').live('click',function()	{
   getDiagnosticsDeleted();
		$("#modalDeletedDiagnostics").dialog({bgiframe: true, width: 400, height: 400, modal: true,title:translation12});
  });
  
  var translation13 = '';

		if(language == 'th'){
		translation13 = 'Inmunización Eliminados';
		}else if(language == 'en'){
		translation13 = 'Deleted Immunization';
		}
  
  $('#deletedImmunizations').live('click',function()	{
  getVaccinesDeleted();
		$("#modalDeletedImmunizations").dialog({bgiframe: true, width: 400, height: 400, modal: true,title:translation13});
  });
  
  var translation14 = '';

		if(language == 'th'){
		translation14 = 'Eliminado de Historia Familiar';
		}else if(language == 'en'){
		translation14 = 'Deleted Family History';
		}
  
  $('#deletedFamilyHistory').live('click',function()	{
  getRelativesDeleted();
		$("#modalDeletedFamilyHistory").dialog({bgiframe: true, width: 400, height: 400, modal: true,title:translation14});
  });
  
  var translation15 = '';

		if(language == 'th'){
		translation15 = 'Medicamentos Eliminados';
		}else if(language == 'en'){
		translation15 = 'Deleted Medications';
		}
  
  $('#deletedMedications').live('click',function()	{
  getMedicationsDeleted();
		$("#modalDeletedMedications").dialog({bgiframe: true, width: 400, height: 400, modal: true,title:translation15});
  });

  $('#ButtonSearchICD').live('click',function()	{
		var tosearch = $('#ICDLABox').val();
		var Codes = GetICD10Code(tosearch);
		var longit = Object.keys(Codes).length;	   	  
		var n=0;
		$('#ICDList').empty();
		while (n < longit)
		{
			$('#ICDList').append($('<option>', {
				value: Codes[n].name,
				text: Codes[n].description
			}));
			
			n++;
		}			 
  });

  $('#ICDList').live('change', 'select', function() {
	    console.log($(this).val()); // the selected options’s value
	
		$('#SelCode').html($(this).val());
	    // if you want to do stuff based on the OPTION element:
	    //var opt = $(this).find('option:selected')[0];
		var opt = $("#ICDList option:selected").text();
		$('#SelOption').html(opt);
	    // use switch or if/else etc.
	});
	
  $('#ButtonUpdateICDCode').live('click',function()	{
		var Wvalue = $('#SelCode').html();
		var Woption = $('#SelOption').html();
		$('#ICDBox').val(Wvalue);
		$('#DiagnosticBox').val(Woption);
		$('#modalICDSearch').dialog('close');
	});
	
	
  $('#DiagnosticEditBox').live('click',function()	{
  
		//alert('clicked');
		getDiagnostics();
		$("#modalDiagnostics").dialog({bgiframe: true, width: 550, height: 590, modal: true,title:"Past Diagnostics"});
		
	});
        
    function LoadDonuts(){

        var UserID = <?php echo $UserID; ?> ;
		
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
			namedoctor = LanzaAjax ('/getDoctorName.php?IdDoctor='+bestVerified);
			contentVerif = 'Verified';// by '+namedoctor;
			title =  '<span class ="ribbon-lgtext">Verified</span><br> <span class ="ribbon-smtext">by '+namedoctor+' on '+ VerifiedDate.substr(0, 10)+'<span class ="ribbon-lgtext">';
            document.getElementById("ribbon-verified").innerHTML=title;
            document.getElementById("ribbon-verified").style.display = 'block';
						
		}
        else {
            contentVerif = 'Not Verified'; 
        }    
    }    
	
	function getDiagnostics(toShow)
	{
		//alert(toShow);
		var queUrl ='getDiagnostics.php';
		$.ajax(
		{
			url: queUrl,
			dataType: "json",
			async: false,
			success: function(data)
			{
				DiagnosticData = data.items;
			}
		});
	
		numDiagnostics = DiagnosticData.length;
	
		var n = 0;
		var DiagnosticBox;
		if (numDiagnostics==0)
		{
		var translation51 = '';

		if(language == 'th'){
		translation51 = 'No se encontraron datos';
		}else if(language == 'en'){
		translation51 = 'No Data Found';
		}
			DiagnosticBox='<span>'+translation51+'</span>';
		}
		else
		{
			DiagnosticBox='';
		}
		
		while (n<numDiagnostics){
			var del = DiagnosticData[n].deleted;
			var dxname = DiagnosticData[n].dxname;
			var dxcode = DiagnosticData[n].dxcode;
			var sdate = DiagnosticData[n].sdate;
			var edate = DiagnosticData[n].edate;	
			var notes = DiagnosticData[n].notes;
			var rowid = DiagnosticData[n].id;	
if(del==0)
			{			
			DiagnosticBox += '<div class="InfoRow DiagnosticRow" id='+rowid+'>';
			
			
			
			var middleColumn = sdate;
			
			if(edate.length>0)
			{
				middleColumn = middleColumn + '-' + edate;
			}
			
			if(notes.length==0)
			{
				notes = 'No Notes Recorded';
			}	
			
			
			if(rowid==toShow)
			{
				DiagnosticBox += '<div style="width:10px; float:left; text-align:left;"><i class="icon-chevron-down"></i></div>';
			}
			
			
			
			
					DiagnosticBox += '<div style="width:140px; float:left; text-align:left;cursor:pointer"><span class="PatName" style="white-space:nowrap">'+dxname.substr(0, 30)+'</span></div>';
					DiagnosticBox += '<div style="width:190px; float:left; text-align:center; color:#22aeff;"><span class="DrName">'+middleColumn +' </span></div>';
					DiagnosticBox += '<div class="EditDiagnostic" id="'+rowid+'" style="width:60px; float:right;margin-right:10px;"><a id="'+rowid+'"  class="btn" style="height: 15px; padding-top: 0px; margin-top: -5px; color:green;" lang="en">Edit</a></div>';
					DiagnosticBox += '<div class="DeleteDiagnostic" id="'+rowid+'" style="width:60px; float:right;"><a id="'+rowid+'"  class="btn" style="height: 15px; padding-top: 0px; margin-top: -5px; color:red;" lang="en">'+translationdel+'</a></div>';
			
	//				DiagnosticBox += '<div style="width:200px; float:left; text-align:left;cursor:pointer"><span class="PatName" style="white-space:nowrap"><strike>'+dxname.substr(0, 30)+'</strike></span></div>';
	//				DiagnosticBox += '<div style="width:200px; float:left; text-align:center; color:#22aeff;"><span class="DrName"><strike>'+middleColumn +' </strike></span></div>';
										
			
			DiagnosticBox += '</div>';
			
			DiagnosticBox += '<div class="InfoRow NotesRow" id=Note'+rowid;
			if(rowid==toShow)
			{
				DiagnosticBox += '>';
			}
			else
			{
				DiagnosticBox += ' style="display:none">';
			}
			
			if(del==0)
			{
				DiagnosticBox += '<div style="width:100%; float:left; text-align:left;">'+notes+'</div>';
			}
			else
			{
				DiagnosticBox += '<div style="width:100%; float:left; text-align:left;"><strike>'+notes+'</strike></div>';
			}
			DiagnosticBox += '</div>';
			}
			n++;
		}
		$('#DiagnosticContainer').html(DiagnosticBox);
		//adjustHeights(".PatName");
	}
	
	function getDiagnosticsEdit(toShow)
	{
		//alert(toShow);
		var queUrl ='getDiagnostics.php';
		$.ajax(
		{
			url: queUrl,
			dataType: "json",
			async: false,
			success: function(data)
			{
				DiagnosticData = data.items;
			}
		});
		numDiagnostics = DiagnosticData.length;
	
		var n = 0;
		var DiagnosticBox;
		if (numDiagnostics==0)
		{
		var translation51 = '';

		if(language == 'th'){
		translation51 = 'No se encontraron datos';
		}else if(language == 'en'){
		translation51 = 'No Data Found';
		}
			DiagnosticBox='<span>'+translation51+'</span>';
		}
		else
		{
			DiagnosticBox='';
		}
		
		while (n<numDiagnostics){
			var del = DiagnosticData[n].deleted;
			var dxname = DiagnosticData[n].dxname;
			var dxcode = DiagnosticData[n].dxcode;
			var sdate = DiagnosticData[n].sdate;
			var edate = DiagnosticData[n].edate;	
			var notes = DiagnosticData[n].notes;
			var rowid = DiagnosticData[n].id;	
if(del==0)
			{		
if(rowid==toShow)
			{			
			DiagnosticBox += '<div class="InfoRow DiagnosticRow" id='+rowid+'>';
			
			
			
			var middleColumn = sdate;
			
			if(edate.length>0)
			{
				middleColumn = middleColumn + '-' + edate;
			}
			
			if(notes.length==0)
			{
				notes = 'No Notes Recorded';
			}	
			
			
			
				DiagnosticBox += '<div style="width:10px; float:left; text-align:left;"><i class="icon-chevron-down"></i></div>';
			
			translatione1 = '';
				if(language == 'th'){
		translatione1 = 'Edición';
		}else if(language == 'en'){
		translatione1 = 'Editing';
		}
					DiagnosticBox += '<div style="width:140px; float:left; text-align:left;cursor:pointer"><span class="PatName" style="white-space:nowrap">'+dxname.substr(0, 30)+'</span></div>';
					DiagnosticBox += '<div style="width:210px; float:left; text-align:center; color:#22aeff;"><span class="DrName">'+middleColumn +' </span></div>';
					DiagnosticBox += '<div class="EditDiagnostic" id="'+rowid+'" style="width:60px; float:left;margin-right:20px;">'+translatione1+'</div>';
			}
	//				DiagnosticBox += '<div style="width:200px; float:left; text-align:left;cursor:pointer"><span class="PatName" style="white-space:nowrap"><strike>'+dxname.substr(0, 30)+'</strike></span></div>';
	//				DiagnosticBox += '<div style="width:200px; float:left; text-align:center; color:#22aeff;"><span class="DrName"><strike>'+middleColumn +' </strike></span></div>';
										
			
			DiagnosticBox += '</div>';
			
			DiagnosticBox += '<div class="InfoRow NotesRow" id=Note'+rowid;
			if(rowid==toShow)
			{
				DiagnosticBox += '>';
			}
			else
			{
				DiagnosticBox += ' style="display:none">';
			}
			
			if(del==0)
			{
				DiagnosticBox += '<div style="width:100%; float:left; text-align:left;">'+notes+'</div>';
			}
			else
			{
				DiagnosticBox += '<div style="width:100%; float:left; text-align:left;"><strike>'+notes+'</strike></div>';
			}
			DiagnosticBox += '</div>';
			}
			n++;
		}
		$('#DiagnosticContainerEdit').html(DiagnosticBox);
		var rowtrack = $('#DiagnosticRow2').val(toShow);
		//adjustHeights(".PatName");
	}
	
	function getDiagnosticsDeleted(toShow)
	{
		//alert(toShow);
		var queUrl ='getDiagnostics.php';
		$.ajax(
		{
			url: queUrl,
			dataType: "json",
			async: false,
			success: function(data)
			{
				DiagnosticData = data.items;
			}
		});
	
		numDiagnostics = DiagnosticData.length;
	
		var n = 0;
		var DiagnosticBox;
		if (numDiagnostics==0)
		{
		var translation51 = '';

		if(language == 'th'){
		translation51 = 'No se encontraron datos';
		}else if(language == 'en'){
		translation51 = 'No Data Found';
		}
			DiagnosticBox='<span>'+translation51+'</span>';
		}
		else
		{
			DiagnosticBox='';
		}
		
		while (n<numDiagnostics){
			var del = DiagnosticData[n].deleted;
			var dxname = DiagnosticData[n].dxname;
			var dxcode = DiagnosticData[n].dxcode;
			var sdate = DiagnosticData[n].sdate;
			var edate = DiagnosticData[n].edate;	
			var notes = DiagnosticData[n].notes;
			var rowid = DiagnosticData[n].id;	
if(del==1)
			{			
			DiagnosticBox += '<div class="InfoRow DiagnosticRow" id='+rowid+'>';
			
			
			
			var middleColumn = sdate;
			
			if(edate.length>0)
			{
				middleColumn = middleColumn + '-' + edate;
			}
			
			if(notes.length==0)
			{
				notes = 'No Notes Recorded';
			}	
			
			
			if(rowid==toShow)
			{
				DiagnosticBox += '<div style="width:10px; float:left; text-align:left;"><i class="icon-chevron-down"></i></div>';
			}
			
			
			
			
	//				DiagnosticBox += '<div style="width:200px; float:left; text-align:left;cursor:pointer"><span class="PatName" style="white-space:nowrap">'+dxname.substr(0, 30)+'</span></div>';
	//				DiagnosticBox += '<div style="width:200px; float:left; text-align:center; color:#22aeff;"><span class="DrName">'+middleColumn +' </span></div>';
	//				DiagnosticBox += '<div class="DeleteDiagnostic" id="'+rowid+'" style="width:60px; float:right;"><a id="'+rowid+'"  class="btn" style="height: 15px; padding-top: 0px; margin-top: -5px; color:red;">Del</a></div>';
			
					DiagnosticBox += '<div style="width:175px; float:left; text-align:left;cursor:pointer"><span class="PatName" style="white-space:nowrap"><font size="1">'+dxname.substr(0, 30)+'</font></span></div>';
					DiagnosticBox += '<div style="width:125px; float:left; text-align:center; color:#22aeff;"><span class="DrName"><font size="1">'+middleColumn +' </font></span></div>';
										
			
			DiagnosticBox += '</div>';
			
			
			}
			n++;
		}
		$('#DiagnosticContainerDeleted').html(DiagnosticBox);
		//adjustHeights(".PatName");
	}
	
	var prev=0;
	$(".DiagnosticRow").live('click',function(){
		
		
		
		var myClass = $(this).attr("id"); 
		
		if(prev==myClass || prev_click=='delete')
		{
			prev=0;
			prev_click='';
		}
		else
		{
			prev = myClass;
		}
		getDiagnostics(prev);    
	});
	
	function adjustHeights(elem) {
      var fontstep = 2;
      if ($(elem).height()>$(elem).parent().height() || $(elem).width()>$(elem).parent().width()) {
        $(elem).css('font-size',(($(elem).css('font-size').substr(0,2)-fontstep)) + 'px').css('line-height',(($(elem).css('font-size').substr(0,2))) + 'px');
        adjustHeights(elem);
      }
    }	
		
	
	$("#ButtonAddDiagnostic").click(function() {
		var dxname = $('#DiagnosticBox').val();
//		var dxcode = $('#field_id1').val();
		var dxcode = $('#ICDBox').val();
		var sdate = $('#DiagnosticStart').val();
		var edate = $('#DiagnosticEnd').val();
		var notes= $('#DiagnosticNotes').val();;
		
		
		if(dxname == '')
		{
			alert('Enter Diagnostic Name');
			$('#DiagnosticBox').focus();
			return;
		}
		
		if(sdate == '')
		{
			alert('Enter Start Date');
			$('#DiagnosticStart').focus();
			return;
		}
		
		//console.log(dxname + '   ' + dxcode + '   ' + sdate + '     ' + edate + '        ' + notes);
		
		var url = 'createDiagnosticData.php?dxname='+dxname+'&dxcode='+dxcode+'&sdate='+sdate+'&edate='+edate+'&notes='+notes;
		console.log(url);
		var Rectipo = LanzaAjax(url);
		//alert(Rectipo);
		
		getDiagnostics(0);    //Refresh the table on the popup
	
		//clear data
		$('#DiagnosticBox').val("");
		$('#DiagnosticStart').val("");
		$('#DiagnosticEnd').val("");
		$('#DiagnosticNotes').val("");
		getDiagnosticHistoryInfo();   //Refresh the div on mainpage
		
	});
	
	$("#ButtonEditDiagnostic").click(function() {
		var idrow = $('#DiagnosticRow2').val();
		var dxname = $('#DiagnosticBox2').val();
//		var dxcode = $('#field_id1').val();
		var dxcode = $('#ICDBox2').val();
		var sdate = $('#DiagnosticStart2').val();
		var edate = $('#DiagnosticEnd2').val();
		var notes= $('#DiagnosticNotes2').val();;
//		alert(idrow);
		
		
		if(dxname == '')
		{
			alert('Enter Diagnostic Name');
			$('#DiagnosticBox2').focus();
			return;
		}
		
		if(sdate == '')
		{
			alert('Enter Start Date');
			$('#DiagnosticStart2').focus();
			return;
		}
		
		//console.log(dxname + '   ' + dxcode + '   ' + sdate + '     ' + edate + '        ' + notes);
		
		var url = 'createDiagnosticData.php?dxname='+dxname+'&dxcode='+dxcode+'&sdate='+sdate+'&edate='+edate+'&notes='+notes+'&rowediter='+idrow;
		console.log(url);
		var Rectipo = LanzaAjax(url);
		//alert(Rectipo);
		
		getDiagnostics(0);    //Refresh the table on the popup
	
		//clear data
		$('#DiagnosticBox2').val("");
		$('#DiagnosticRow2').val("");
		$('#DiagnosticStart2').val("");
		$('#DiagnosticEnd2').val("");
		$('#DiagnosticNotes2').val("");
		getDiagnosticHistoryInfo();   //Refresh the div on mainpage
		$("#editDiagnostics").dialog('close');
	});
	

	
	var prev_click='';
	$(".DeleteDiagnostic").live('click',function(){
		var myClass = $(this).attr("id"); 
		//alert (myClass);
		prev_click='delete';
		var cadena='deleteDiagnostic.php?id='+myClass;
		var RecTipo=LanzaAjax(cadena);
		getDiagnostics(0);
		getDiagnosticHistoryInfo();

	});
	
		var translation21 = '';

		if(language == 'th'){
		translation21 = 'Diagnóstico Editar';
		}else if(language == 'en'){
		translation21 = 'Edit Past Diagnostics';
		}
		
	$(".EditDiagnostic").live('click',function(){
		var myClass = $(this).attr("id"); 
		//alert (myClass);
		$("#editDiagnostics").dialog({bgiframe: true, width: 550, height: 510, modal: true,title:translation21});
		getDiagnosticsEdit(myClass);
	});
	
	var translation22 = '';

		if(language == 'th'){
		translation22 = 'Medicamentos Editar';
		}else if(language == 'en'){
		translation22 = 'Edit Past Medications';
		}
	
	$(".EditMedication").live('click',function(){
		var myClass = $(this).attr("id"); 
		//alert (myClass);
		$("#editMedications").dialog({bgiframe: true, width: 550, height: 380, modal: true,title:translation22});
		getMedicationsEdit(myClass);
	});
	
	var translation23 = '';

		if(language == 'th'){
		translation23 = 'Historia Familiar Editar';
		}else if(language == 'en'){
		translation23 = 'Edit Past Family History';
		}
	
	$(".EditFamilyHistory").live('click',function(){
		var myClass = $(this).attr("id"); 
		//alert (myClass);
		$("#editFamilyHistoryModal").dialog({bgiframe: true, width: 550, height: 380, modal: true,title:translation23});
		getRelativesEdit(myClass);
	});
	
	var translation24 = '';

		if(language == 'th'){
		translation24 = 'Vacunas Editar';
		}else if(language == 'en'){
		translation24 = 'Edit Past Vaccines';
		}
	
	$(".EditVaccine").live('click',function(){
		var myClass = $(this).attr("id"); 
		//alert (myClass);
		$("#editVaccineModal").dialog({bgiframe: true, width: 550, height: 380, modal: true,title:translation24});
		getVaccinesEdit(myClass);
	});
	
	var translation25 = '';

		if(language == 'th'){
		translation25 = 'Editar Alergias';
		}else if(language == 'en'){
		translation25 = 'Edit Past Allergies';
		}
	
	$(".EditAllergy").live('click',function(){
		var myClass = $(this).attr("id"); 
		//alert (myClass);
		$("#editAllergyModal").dialog({bgiframe: true, width: 550, height: 380, modal: true,title:translation25});
		getAllergiesEdit(myClass);
	});
  
	$( "#DiagnosticHistoryInfo" ).mouseover(function() {
	    //console.log('In');
		showElement('DiagnosticEditBox');
	});
	
	$( "#DiagnosticHistoryInfo" ).mouseout(function() {
	    //console.log('Out');
		hideElement('DiagnosticEditBox');
	});
  
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

  
	function showElement(element)
	{
	   var welcome = document.getElementById(element);
	   welcome.style.display = 'block';
	   //$("#"+element).animate({display:'block'});
	   
	}

	function hideElement(element)
	{
	   var welcome = document.getElementById(element);
	   welcome.style.display = 'none';
	   
	}
	
	
	
	
	$('#HabitsInfo').live('click',function()	{
//		alert('clicked');
		
		$("#cigarette-slider").slider( "value", $('#cig').val() );	
		$("#alcohol-slider").slider( "value", $('#alc').val() );	
		$("#exercise-slider").slider( "value", $('#exer').val() );
		$("#sleep-slider").slider( "value", $('#slee').val() );
//		$("#coffee-slider").slider( "value", $('#coff').val() );		
			
		$( "#cigarettes" ).val( $( "#cigarette-slider" ).slider( "value" ) );	
		$( "#alcohol" ).val( $( "#alcohol-slider" ).slider( "value" ) );	
		$( "#exercise" ).val( $( "#exercise-slider" ).slider( "value" ) );
		$( "#sleep" ).val( $( "#sleep-slider" ).slider( "value" ) );
//		$( "#coffee" ).val( $( "#coffee-slider" ).slider( "value" ) );
		var translation = '';

		if(language == 'th'){
		translation = 'Hábitos';
		}else if(language == 'en'){
		translation = 'Habits';
		}
		
		$("#modalHabitsInfo").dialog({bgiframe: true, width: 500, height: 450, modal: true,title:translation});
	});
	
	
    $( "#cigarette-slider" ).slider({
      range: "min",
      value: 0,
      min: 0,
      max: 20,
      slide: function( event, ui ) {
        $( "#cigarettes" ).val( ui.value );
      }
    });
	
	
	$('#cigarettes').keyup(function () { 
		this.value = this.value.replace(/[^0-9\.]/g,'');
        $("#cigarette-slider").slider( "value", this.value );	
    });
	
	
	$( "#alcohol-slider" ).slider({
      range: "min",
      value: 0,
      min: 0,
      max: 50,
      slide: function( event, ui ) {
        $( "#alcohol" ).val( ui.value );
      }
    });
	
	
	$('#alcohol').keyup(function () { 
		this.value = this.value.replace(/[^0-9\.]/g,'');
        $("#alcohol-slider").slider( "value", this.value );	
    });
	
	$( "#exercise-slider" ).slider({
      range: "min",
      value: 0,
      min: 0,
      max: 50,
      slide: function( event, ui ) {
        $( "#exercise" ).val( ui.value );
      }
    });
	
	
	$('#exercise').keyup(function () { 
		this.value = this.value.replace(/[^0-9\.]/g,'');
        $("#exercise-slider").slider( "value", this.value );	
    });
	
	$( "#sleep-slider" ).slider({
      range: "min",
      value: 8,
      min: 0,
      max: 24,
      slide: function( event, ui ) {
        $( "#sleep" ).val( ui.value );
      }
    });
	
	
	$('#sleep').keyup(function () { 
		this.value = this.value.replace(/[^0-9\.]/g,'');
        $("#sleep-slider").slider( "value", this.value );	
    });
	//THIS SLIDER BREAKS THE AJAX QUERY FOR SOME REASON
/*	( "#coffee-slider" ).slider({
      range: "min",
      value: 0,
      min: 0,
      max: 50,
      slide: function( event, ui ) {
        $( "#coffee" ).val( ui.value );
      }
    });
	
	
	$('#coffee').keyup(function () { 
		this.value = this.value.replace(/[^0-9\.]/g,'');
        $("#coffee-slider").slider( "value", this.value );	
    });*/
	
	
	$("#ButtonUpdateHabitsInfo").click(function() {
		
		var cigarettes = $('#cigarettes').val();
		var alcohol = $('#alcohol').val();
		var exercise = $('#exercise').val();
		var sleep = $('#sleep').val();
//		var coffee = $('#coffee').val();
		//alert(cigarettes + '  ' + alcohol + '   ' + exercise);
		var url = 'saveHabitInfo.php?cigarette='+cigarettes+'&alcohol='+alcohol+'&exercise='+exercise+'&sleep='+sleep+'';
		console.log(url);
		var RecTipo = LanzaAjax(url);
		//alert(RecTipo);
		getHabitsInfo();
		$("#modalHabitsInfo").dialog('close');
	});
	
	
	
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

 
  </body>
</html>

