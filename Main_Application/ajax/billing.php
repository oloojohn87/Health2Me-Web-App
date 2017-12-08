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
    
		     
     
     <!--CONTENT MAIN START-->
     <?php 
        if($is_modal)
        {
            echo '<div style="width:1000px; height:660px; padding: 0px; padding-top: 30px;">';
        }
        else
        {
            echo '<div class="content">';
            echo '<div class="grid" class="grid span4" style="width:90%; height:630px; margin: 0 auto; margin-top:30px; padding-top:5px;">';
        } 
		
		
				$current_encoding = mb_detect_encoding($UserName, 'auto');
				$show_text = iconv($current_encoding, 'ISO-8859-1', $UserName);

				$current_encoding = mb_detect_encoding($UserSurname, 'auto');
				$show_text2 = iconv($current_encoding, 'ISO-8859-1', $UserSurname); 
				
				if(file_exists('PatientImage/'.$UserID.'.jpg')){
				$patient_image = 'PatientImage/'.$UserID.'.jpg';
				}elseif(file_exists('PatientImage/'.$UserID.'.png')){
				$patient_image = 'PatientImage/'.$UserID.'.png';
				}else{
				$patient_image = 'PatientImage/defaultDP.jpg';
				}
				
    ?>
	<div style="width:100%;">
     <div style="width:20%;">
	 <img src="images/health2meLOGO.png" style="margin-top:5%;margin-left:15%;width:70%;">
	 </div>
	 <span style="margin-left:42%;margin-top:-3%;float:left;">
	 <span id="NombreComp" style="font: bold 24px Arial, Helvetica, sans-serif; color: #3D93E0; cursor: auto;  margin-left:10px;"><?php echo $show_text." ".$show_text2; ?></span>
	 <span id="email" style="font-size: 14px; color: #5EB529; font-weight: bold; font-family: Arial, Helvetica, sans-serif; margin-left:10px;"><?php echo $UserEmail;?></span>
	 </span>
	 <span style="float:right;margin-top:-10%;">
	 <img src="<?php echo $patient_image; ?>" style="width:80%;margin-top:20%;">
	 </span>
	 </div>
	 
	 <div style="width:22%;height:80%;display:none;" id = "stream_categories">
	 <ul id="myTab" class="nav nav-tabs tabs-main" style="border-bottom:0px;">
	 <li id="toggle0" class="TABES active" style="margin-left:4%;margin-bottom:5%;text-align: center; width:80%;background: none repeat scroll 0% 0% rgb(204, 204, 204);">
	 <a href="#ALL"  lang="en" data-toggle="tab" style="height:50px;"><i class="icon-ok-sign icon-large" style="color:black; font-size: 1.0em; width:100%;"></i><div style="width:100%"></div>ALL</a>
	 
	 <li id="toggle1" class="TABES" style="margin-left:4%;margin-bottom:5%;text-align: center; width:80%;background-color:#22aeff;">
	 <a href="#ALL"  lang="en" data-toggle="tab" style="height:50px;color:#22aeff;"><i class="icon-picture icon-large" style="color:RGB(111,111,111); font-size: 1.0em; width:100%;"></i><div style="width:100%"></div>Imaging</a>
	 
	 <li id="toggle2" class="TABES" style="margin-left:4%;margin-bottom:5%;text-align: center; width:80%;background-color:RGB(71,163,94);">
	 <a href="#ALL"  lang="en" data-toggle="tab" style="height:50px;color:#22aeff;"><i class="icon-beaker icon-large" style="color:RGB(71,163,94); font-size: 1.0em; width:100%;"></i><div style="width:100%"></div>Labs</a>
	 
	 <li id="toggle3" class="TABES" style="margin-left:4%;margin-bottom:5%;text-align: center; width:80%;background-color:RGB(222,181,40);">
	 <a href="#ALL"  lang="en" data-toggle="tab" style="height:50px;color:#22aeff;"><i class="icon-user-md icon-large" style="color:RGB(222,181,40); font-size: 1.0em; width:100%;"></i><div style="width:100%"></div>Appointments</a>
	 
	 <li id="toggle4" class="TABES" style="margin-left:4%;margin-bottom:5%;text-align: center; width:80%;background-color:orange;">
	 <a href="#ALL"  lang="en" data-toggle="tab" style="height:50px;color:#22aeff;"><i class="icon-picture icon-large" style="color:orange; font-size: 1.0em; width:100%;"></i><div style="width:100%"></div>Immunizations</a>
	 </li>
	 </ul>
	 <a id="back_button" onclick="goBack();" class="btn" title="Add Bill" style="text-align:center; padding:5px; color:#22aeff;margin-left:4.5%;margin-top:-5%;">
	 <- BACK
	 </a>
	 <a id="create_bill" class="btn" title="Add Bill" style="text-align:center; padding:5px; color:#22aeff;margin-left:57.5%;margin-top:-18%;">
	 + Add Bill
	 </a>
	 </div>
	 <div style="width:79%;border:2px solid grey;height:69%;margin-left:20%;display:none;margin-top:-37.5%;" id = "stream_boxes">
	 
	<div id="StreamContainerALL" class="frame" ></div> 
	<div id="StreamContainerIMAG"  ></div>
	<div id="StreamContainerLABO"  ></div>
	<div id="StreamContainerDRRE"  ></div>
	<div id="StreamContainerOTHE"  ></div>

	 </div>
	 <a id="create_super_bill" class="btn" title="Add Super Bill" style="text-align:center; padding:5px; color:#22aeff;margin-left:90%;">
	 + Add Super Bill
	 </a>
	 <div style="width:98%;border:2px solid grey;height:70%;margin-left:1%;border:1px solid black;margin-top:1%;" id="super_bill_box">
	 </div>
	 
			
 		     
		</div>
     </div>
	 
	 <div id="add_bill_form" lang="en" title="Add Bill" style="display: none;">
		 
		 <div style="width: 500px; height: 25px; color: #777; margin-top: 15px;"> 
                <span style="float: left; margin-top: 6px; margin-right: 79px;" lang="en">Label Bill :</span>
                <input type="text" id="bill_name" style="float: left; width: 250px;" >
			
                <span style="float: left; margin-top: 6px; margin-right: 81px;" lang="en">Bill Date :</span>
                <input type="date" id="bill_date" style="float: left; width: 250px;" >
			
                <span style="float: left; margin-top: 6px; margin-right: 81px;" lang="en">Bill Type :</span>
				<select id="select_type">
				<option value="1">Imaging</option>
				<option value="2">Lab</option>
				<option value="3">Appointment</option>
				<option value="4">Immunization</option>
				</select>
				
				
                <span style="float: left; margin-top: 6px; margin-right: 29px;" lang="en">Invoice Amount :</span>
                <input type="number" step="0.01" id="invoice_amount" style="float: left; width: 250px;" >
			
                <span style="float: left; margin-top: 6px; margin-right: 47px;" lang="en">Taxes(if any) :</span>
                <input type="number" step="0.01" id="taxes" style="float: left; width: 250px;" >
			
                <span style="float: left; margin-top: 6px; margin-right: 57px;" lang="en">Fees(if any) :</span>
                <input type="number" step="0.01" id="fees" style="float: left; width: 250px;" >
			
                <span style="float: left; margin-top: 6px; margin-right: 50px;" lang="en">Amount Paid :</span>
                <input type="number" step="0.01" id="paid" style="float: left; width: 250px;" >
				
				<span style="float: left; margin-top: 6px; margin-right: 71px;" lang="en">CPT Code :</span>
                <input type="text" id="cpt" style="float: left; width: 150px;" >
				
				<div style="margin-top:40%;">
				<span style="float: left; margin-top: 6px; margin-right: 25px;" lang="en">Modifier 1 :</span>
                <input type="text" id="mod1" style="float: left; width: 100px;margin-right:25px;" >
				
				<span style="float: left; margin-top: 6px; margin-right: 25px;" lang="en">Modifier 2 :</span>
                <input type="text" id="mod2" style="float: left; width: 100px;" >
				
				<span style="float: left; margin-top: 6px; margin-right: 25px;" lang="en">Modifier 3 :</span>
                <input type="text" id="mod3" style="float: left; width: 100px;margin-right:25px;" >
				
				<span style="float: left; margin-top: 6px; margin-right: 25px;" lang="en">Modifier 4 :</span>
                <input type="text" id="mod4" style="float: left; width: 100px;" >
				</div>
			</div>
			
			<div style="width: 200px; height: 30px; margin: auto;">
			
                 <button id="create_bill_button" style="width: 200px; height: 30px; background-color: #22AEFF; color: #FFF; outline: 0px; border: 0px solid #FFF; border-radius: 7px; margin-top: 15px;margin-left:2%;" lang="en">
                    Add Bill
                 </button>
             </div>
			
			
             </div>
			 
			 
		</div>
		
		<div id="add_super_bill_form" lang="en" title="Add Super Bill" style="display: none;">
		 
		 <div style="width: 500px; height: 25px; color: #777; margin-top: 25px;"> 
                <span style="float: left; margin-top: 6px; margin-right: 84px;" lang="en">Label Bill :</span>
                <input type="text" id="super_bill_name" style="float: left; width: 250px;" >
                <span style="float: left; margin-top: 6px; margin-right: 84px;" lang="en">Bill Start :</span>
                <input type="date" id="super_bill_date" style="float: left; width: 250px;" >
				<span style="float: left; margin-top: 6px; margin-right: 93px;" lang="en">Bill End :</span>
                <input type="date" id="super_bill_edate" style="float: left; width: 250px;" >
				<span style="float: left; margin-top: 6px; margin-right: 30px;" lang="en">Service Location :</span>
                <input type="text" id="service_location" style="float: left; width: 250px;" >
			</div>
			
			<div style="width: 500px; height: 25px; color: #777; margin-top: 25px;"> 
				
			
			<div style="width: 200px; height: 30px; margin: auto;">
                 <button id="create_super_bill_button" style="width: 200px; height: 30px; background-color: #22AEFF; color: #FFF; outline: 0px; border: 0px solid #FFF; border-radius: 7px; margin-top: 15px;" lang="en">
                    Add Super Bill
                 </button>
             </div>
			
			
             </div>
			 
			 
		</div>
		<div id="add_image_dialog" lang="en" title="Add Images" style="display: none;">
		<div style="width: 95%; margin: auto; height: 300px; overflow-y:hidden;text-align: center; margin-top: 30px; margin-bottom: 20px;" id="attachimage">
            <!--<div style="width: 52px; height: 42px; margin-left: auto; margin-right: auto; margin-top: 100px;">
                <img src="images/load/29.gif"  alt=""> Loading Reports

            </div>-->
        </div>
		<center><button id="attach_image_to_bill" style="border:0px;border-radius:6px;height: 24px; width:75px; color:#FFF; background-color:#22aeff;float:bottom; margin-top:20px;" lang="en">Attach</button></center>
             </div>
			 <div id="show_image_dialog" lang="en" title="Attached Images" style="display: none;">
			 <div id="myTabContent" class="tab-content tabs-main-content padding-null" style="width: 100%;display:none;" >
                <div  class="tab-pane tab-overflow-main fade in active" id="ALL" style="overflow: auto;overflow-y: hidden;">
					<div class="horizontal-only notes" id="hscroll" style="height: 100%; width:100%; background-color:white;" >
					<div id="sliderleft" class="slider-image" style="float:left;"><i class="icon-chevron-sign-left icon-5x"></i></div>
					<div id="sliderright" class="slider-image" style="float:right;"><i class="icon-chevron-sign-right icon-5x"></i> </div> 
						<div id="StreamContainerALL2" class="frame" style="width:100%; height:100%; overflow: auto; margin-top:-40%;" ></div> 
						
					</div> 
					
                </div>
		 </div>
		 <div id="stream_indicator" style="width: 52px; height: 42px; display: none;">
            <img src="images/load/29.gif"  alt="">
        </div>
             </div>
			 
			 
		</div>
		
		<input type="hidden" value="" id="super_id_holder" />
		<input type="hidden" value="" id="bill_id_holder" />
		
		 
     <!--CONTENT MAIN END-->

    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="js/jquery-ui.min.js"></script>

    <!-- Libraries for notifications -->
    <script src="realtime-notifications/lib/gritter/js/jquery.gritter.min.js"></script>
	<!--<script src="realtime-notifications/pusher.min.js"></script>
    <script src="realtime-notifications/PusherNotifier.js"></script>-->
    <script src="js/socket.io-1.3.5.js"></script>
    <script src="push/push_client.js"></script>

	<link href="realtime-notifications/lib/gritter/css/jquery.gritter.css"rel="stylesheet" type="text/css" />
	<!--<script src="imageLens/jquery.js" type="text/javascript"></script>-->
	<script src="imageLens/jquery.imageLens.js" type="text/javascript"></script>
    <script>
		/*$(function() {
	    //var pusher = new Pusher('d869a07d8f17a76448ed');
	    //var channel_name=$('#MEDID').val();
		//var channel = pusher.subscribe(channel_name);
		//var notifier=new PusherNotifier(channel);
            
        var push = new Push($("#MEDID").val(), window.location.hostname + ':3955');
        push.bind('notification', function(data) 
        {
            displaynotification('New Message', data);
        });
		
	  });*/
    </script>
    <!-- Libraries for notifications -->

    <script src="TypeWatch/jquery.typewatch.js"></script>
	
	<script src="build/js/intlTelInput.js"></script>
	<script src="js/isValidNumber.js"></script>
	
    <script type="text/javascript" >
	$( document ).ready(function() {
    //getBillingInfo(1, 7, 1);
	getSuperBillingInfo(1, 1);
});

var eDOM = '';
    var flag=1;
    var Elementwidth=0;
   $(".TABES").live('click',function() {
		//alert('clicked');
		if ($('#ascroll').length){
			var element = document.getElementById("ascroll");
			element.parentNode.removeChild(element);
		}	
		$(this).addClass("active");
		var queid = $(this).attr("id");
		var ElementDOM="";
        $("#stream_indicator").css("display", "block");
		//alert(queid);
		//$("#ALL").hide();
		$("#StreamContainerALL").hide();
		$("#StreamContainerIMAG").hide();
		$("#StreamContainerLABO").hide();
		$("#StreamContainerDRRE").hide();
		$("#StreamContainerOTHE").hide();
		//$(PrevElementDOM).hide();
		//alert(queid);
		switch (queid)
		{
			case 'toggle0': 	ElementDOM ='#StreamContainerALL';
						getBillingInfo(1, 7, 1);
						break;
			case 'toggle1': 	ElementDOM ='#StreamContainerIMAG';
						getBillingInfo(2, 7, 1);
						break;
			case 'toggle2': 	ElementDOM ='#StreamContainerLABO';
						getBillingInfo(3, 7, 1);
						break;
			case 'toggle3': 	ElementDOM ='#StreamContainerDRRE';
						getBillingInfo(4, 7, 1);
						break;
			case 'toggle4': 	ElementDOM ='#StreamContainerOTHE';
						getBillingInfo(5, 7, 1);
						break;
			default: 	ElementDOM ='testDIV';
						//$("#DIV").show();
						break;
				
		}
		eDOM = ElementDOM;
		var EntryTypegroup =queid;
		var Usuario = $('#userId').val();
		var MedID =$('#MEDID').val();
        if(MedID < 0)
        {
            MedID = $("#USERID").val();
        }
		
		
		
        var isDoctor = 1;
        if(Usuario == MedID)
        {
            isDoctor = 0;
        }
        
       
		
		
		//alert('here');
        
		$(ElementDOM).show();
   
   });
   
   function billIdHolder(id)
	{
     var billId = $("#bill_id_holder").val(id);
	}
   
	

		
		
		//THIS CAPTURES IMAGES///////////////////////////////////////////////////////////////////////////
		$("#attach_image_to_bill").on('click',function(){
		var billId = $("#bill_id_holder").val();
		//alert(billId);
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
        $.get("addImageToBill.php?billid="+billId+"&user="+user+"&idpins="+idpins,function(data,status)
              {
                alert('The records you selected have been added to this bill.');
                //console.log(data);
              });
        
        
	   $("#add_image_dialog").dialog("close");
    });
   
   ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
   
   $("#create_bill").live('click',function(){
		
                $( "#add_bill_form" ).dialog({
                      autoOpen: true,
                      resizable:false,
                      height: 500,
                      width: 515,
                      modal: true,
                      
                });
        
        
	});
	
	$("#create_super_bill").live('click',function(){
		
                $( "#add_super_bill_form" ).dialog({
                      autoOpen: true,
                      resizable:false,
                      height: 350,
                      width: 520,
                      modal: true,
                      
                });
        
        
	});
	
	function addImage()
	{
	   var billIdForStream = $("#bill_id_holder").val();
   //THIS IS FOR ATTACHING IMAGES///////////////////////////////////////////////////////////////////////////////////////////////
   $.get("createBillStream.php?ElementDOM=na&billid="+billIdForStream+"&EntryTypegroup=0&Usuario=<?php echo $_SESSION['UserID']; ?>", function(data, status)
        {
            //console.log(data);
			//alert(billIdForStream);
            $("#attachimage").html(data);
        });
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                $( "#add_image_dialog" ).dialog({
                      autoOpen: true,
                      resizable:false,
                      height: 450,
                      width: 920,
                      modal: true,
                      
                });
        
        
	}
	
	function showImage()
	{
	showThumbnails();
                $( "#show_image_dialog" ).dialog({
                      autoOpen: true,
                      resizable:false,
                      height: 450,
                      width: 920,
                      modal: true,
                      
                });
        
        
	}
	
	$("#create_bill_button").on('click', function()
    {
	var new_super_id = $('#super_id_holder').val();
	var bill_name = $('#bill_name').val();
	var bill_date = $('#bill_date').val();
	var select_type = $('#select_type').val();
	var invoice_amount = $('#invoice_amount').val();
	var taxes = $('#taxes').val();
	var fees = $('#fees').val();
	var paid = $('#paid').val();
	var cpt = $('#cpt').val();
	var mod1 = $('#mod1').val();
	var mod2 = $('#mod2').val();
	var mod3 = $('#mod3').val();
	var mod4 = $('#mod4').val();
	
	var url = 'createBillData.php?billname='+bill_name+'&billdate='+bill_date+'&selecttype='+select_type+'&invoiceamount='+invoice_amount+'&taxes='+taxes+'&fees='+fees+'&paid='+paid+'&superid='+new_super_id+'&cpt='+cpt+'&mod1='+mod1+'&mod2='+mod2+'&mod3='+mod3+'&mod4='+mod4;
	//console.log(url);
	var Rectipo = LanzaAjax(url);
	alert('Bill has been added.');
	$('#add_bill_form').dialog('close');
	getBillingInfo(1,1,1,new_super_id);
	
	var bill_name = $('#bill_name').val("");
	var bill_date = $('#bill_date').val("");
	var select_type = $('#select_type').val("");
	var invoice_amount = $('#invoice_amount').val("");
	var taxes = $('#taxes').val("");
	var fees = $('#fees').val("");
	var paid = $('#paid').val("");
	var cpt = $('#cpt').val("");
	var mod1 = $('#mod1').val("");
	var mod2 = $('#mod2').val("");
	var mod3 = $('#mod3').val("");
	var mod4 = $('#mod4').val("");
    });
	
	$("#create_super_bill_button").on('click', function()
    {
	var super_bill_name = $('#super_bill_name').val();
	var super_bill_date = $('#super_bill_date').val();
	var super_bill_edate = $('#super_bill_edate').val();
	var service_location = $('#service_location').val();
	
	var url = 'createSuperBillData.php?superbillname='+super_bill_name+'&superbilldate='+super_bill_date+'&superbilledate='+super_bill_edate+'&servicelocation='+service_location;
	//console.log(url);
	var Rectipo = LanzaAjax(url);
	alert('Super bill has been added.');
	$('#add_super_bill_form').dialog('close');
	getSuperBillingInfo(1, 1);
	
	var super_bill_name = $('#super_bill_name').val("");
	var super_bill_date = $('#super_bill_date').val("");
	var super_bill_edate = $('#super_bill_edate').val("");
	var service_location = $('#service_location').val("");
    });
	
	function getBillingInfo(holder, sort, order, superid)
	{
		var new_super_id = $('#super_id_holder').val();
		
		if(new_super_id != ''){
		var my_super_id = new_super_id;
		}else{
		var my_super_id = superid;
		}
		if(holder == 1){
		var link = 'getBillingInfo.php?sort='+sort+'&order='+order+'&superid='+my_super_id;
		}else if(holder == 2){
		var link = 'getBillingInfo.php?type=1&sort='+sort+'&order='+order+'&superid='+my_super_id;
		}else if (holder == 3){
		var link = 'getBillingInfo.php?type=2&sort='+sort+'&order='+order+'&superid='+my_super_id;
		}else if(holder == 4){
		var link = 'getBillingInfo.php?type=3&sort='+sort+'&order='+order+'&superid='+my_super_id;
		}else if (holder == 5){
		var link = 'getBillingInfo.php?type=4&sort='+sort+'&order='+order+'&superid='+my_super_id;
		}
		//console.log(link);
		$.ajax({
           url: link,
           dataType: "html",
           async: true,
           success: function(data)
           {
				$('#StreamContainerALL').html(data);
				var myElement = document.querySelector("#StreamContainerALL");
				myElement.style.display = "block";
				//alert('done');
           }
        });

	
	}
	
	function getSuperBillingInfo(sort, order)
	{
		
		var link = 'getSuperBillingInfo.php?sort='+sort+'&order='+order;
		
		//console.log(link);
		$.ajax({
           url: link,
           dataType: "html",
           async: true,
           success: function(data)
           {
				$('#super_bill_box').html(data);
				var myElement = document.querySelector("#super_bill_box");
				myElement.style.display = "block";
				//alert('done');
           }
        });

	
	}
	
	function getBillingItem(id)
	{
		var link = 'getBillingItem.php?id='+id;
	
		//console.log(link);
		$.ajax({
           url: link,
           dataType: "html",
           async: true,
           success: function(data)
           {
				$('#StreamContainerALL').html(data);
				var myElement = document.querySelector("#StreamContainerALL");
				myElement.style.display = "block";
				//alert('done');
				
           }
        });

	
	}
	
	function getSuperBillingItem(id)
	{
		getBillingInfo(1,1,1,id);
		var myElement = document.querySelector("#stream_boxes");
		myElement.style.display = "block";
		var myElement2 = document.querySelector("#super_bill_box");
		myElement2.style.display = "none";
		var myElement3 = document.querySelector("#stream_categories");
		myElement3.style.display = "block";
		var myElement4 = document.querySelector("#create_super_bill");
		myElement4.style.display = "none";
		$('#super_id_holder').val(id);
	}
	
	function goBack()
	{
		var myElement = document.querySelector("#stream_boxes");
		myElement.style.display = "none";
		var myElement2 = document.querySelector("#super_bill_box");
		myElement2.style.display = "block";
		var myElement3 = document.querySelector("#stream_categories");
		myElement3.style.display = "none";
		var myElement4 = document.querySelector("#create_super_bill");
		myElement4.style.display = "block";
		$('#super_id_holder').val("");
		$("#bill_id_holder").val("");
	}
	
	function showThumbnails(){
	var billIdForStream = $("#bill_id_holder").val();
	//alert(billIdForStream);
	if ($('#ascroll').length){
			var element = document.getElementById("ascroll");
			element.parentNode.removeChild(element);
		}	
		$(this).addClass("active");
		var queid = $(this).attr("id");
		var ElementDOM="";
        $("#StreamContainerALL2").css("display", "block");
		$("#myTabContent").css("display", "block");
		//alert(queid);
		//$("#ALL").hide();
		$("#myTabContent").show();
		$("#StreamContainerALL2").show();

		//$(PrevElementDOM).hide();
		//alert(queid);
		switch (queid)
		{
			case 'thumbnails': 	ElementDOM ='#StreamContainerALL2';
						//getBillingInfo(1, 7, 1);
						break;
			default: 	ElementDOM ='testDIV';
						//$("#DIV").show();
						break;
				
		}
		ElementDOM ='#StreamContainerALL2';
		eDOM = ElementDOM;
		var EntryTypegroup =queid;
		var Usuario = <?php echo $UserID; ?>;
		var MedID = <?php echo $MEDID; ?>;
        if(MedID < 0)
        {
            MedID = $("#USERID").val();
        }
		
		
	
	var isDoctor = 1;
        if(Usuario == MedID)
        {
            isDoctor = 0;
        }
        
        var queUrl1='';
        
		queUrl1 ='createReportStreamForBilling.php?ElementDOM=na&Usuario='+Usuario+'&MedID='+MedID+'&num_reports=1&isDoctor='+isDoctor+'&billid='+billIdForStream;
		
		
      	//alert (queUrl);
		//$("#ALL").show();
		//PrevElementDOM=ElementDOM;
      	//$(ElementDOM).load(queUrl,function() { alert( "Load was performed." );});
		var RecTipo='<span class="label label-success" style="left:0px; margin-left:0px; margin-top:20px; margin-bottom:20px; font-size:14px; text-shadow:none; text-decoration:none;background-color:red">Could not load Reports due to Internet issues</span>';
		setTimeout(function(){ 
				
				$.ajax(
				   {
				   url: queUrl1,
				   dataType: "html",
				   async: false,
				   complete: function(){ //alert('Completed');
							},
				   success: function(data) {
							if (typeof data == "string") {
										RecTipo = data;
										$(ElementDOM).html(RecTipo);
										$(ElementDOM).scrollLeft(0);

										//offset1=20;              //global var
                                        offset1=10;
                                        Elementwidth=(offset1*160) + 60;
										last_pos = 0;			//global var
                                        $("#stream_indicator").css("display", "none");
										
							   }
										
							 },
				   error: function(data){
						$(ElementDOM).html(RecTipo);
				   }
					
				});
				
				//var RecTipo = LanzaAjax (queUrl);
				//$(ElementDOM).html(RecTipo);
				//$(ElementDOM).load(queUrl);
				$(ElementDOM).trigger('click');
				//$(ElementDOM).trigger('update');
				//$("#H2M_Spin_Stream").hide();
		},1000);
		
		
		//alert('here'); 
		$(ElementDOM).show();
   
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
    <script src="js/moment-with-locales.js"></script>

	<script src="js/application.js"></script>

 
  </body>
</html>

