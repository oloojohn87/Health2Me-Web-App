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
//$exit_display = new displayExitClass();

//$exit_display->displayFunction(1);
//die;
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
//$exit_display = new displayExitClass();

//$exit_display->displayFunction(3);



//echo "USER DATA INCOMPLETE. No Doctor assigned to this User";
//echo "<br>\n"; 	
//echo "<h2><a href='".$domain."'>Return to Health2me Dashboard</a></h2>";
//die;
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
	 
	 <div id="summary_modal" lang="en" title="Summary" style="text-align:center; width: 1000px; height: 660px; overflow: hidden;display:none;">
    </div>
	 
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
        
        push.bind('notification', function(data) {
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
    	$("#summary_modal").html('<iframe src="https://test.mdtoolboxrx.net/rxtest/access1.aspx?code=44CV-CR44-PT31-T41-G1Z4&aid=5091&sid=14_1&user=Test&menu=0&header=0&page=rx&pid=43543543&did=4545&lid=232" width="1000" height="660" scrolling="no" style="width:1000px;height:660px; margin: 0px; border: 0px solid #FFF; outline: 0px; padding: 0px; overflow: hidden;"></iframe>');
		$("#summary_modal").dialog({bigframe: true, width: 1050, height: 690, resize: false, modal: true});
		});
	</script>