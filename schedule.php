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
if( isset($_GET['IdUsu']) ) $UserID = $_GET['IdUsu']; $_SESSION['UserID']=$UserID; // Restore Global variable for UserID (so php constructors for summary can work)
$Acceso = $_SESSION['Acceso'];
$privilege=$_SESSION['Previlege'];
if (!isset($MEDID))
{
    $exit_display = new displayExitClass();
    $exit_display->displayFunction(1);
    die;
}

if(isset($SESSION['MEDID'])) unset($_SESSION['UserID']);

$is_modal = false;
if(isset($_GET['modal']) && $_GET['modal'] == 1) $is_modal = true;

$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', 
               array(PDO::ATTR_EMULATE_PREPARES => false, 
                     PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con) die('Could not connect: ' . mysql_error());	

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

/*
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
*/

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
	
    <?php if ($_SESSION['CustomLook']=="COL") : ?>
        <link href="css/styleCol.css" rel="stylesheet">
    <?php endif; ?>
<!--    <link href="css/FamilyTree.css" rel="stylesheet">-->
	
	<script src="js/jquery.min.js"></script>
    
	<script type="text/javascript" src="js/jquery.easing.1.3.js"></script>	
	<script type="text/javascript" src="js/modernizr.2.5.3.min.js"></script>	
	

 
    <!-- Le fav and touch icons -->
    <link rel="shortcut icon" href="images/icons/favicon.ico">
    	
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

  <body id = "body" <?php ($is_modal ? echo 'style="background: #FFF; width: 1000px; height: 690px;overflow: hidden;"' : echo 'style="background: #F9F9F9;"'); ?> >

    <input type="hidden" id="NombreEnt" value="<?php echo $NombreEnt; ?> ">
    <input type="hidden" id="PasswordEnt" value="<?php echo $PasswordEnt; ?> ">
    <input type="hidden" id="UserHidden">

      
	<!--Header Start-->
      <?php if($is_modal) echo '<!--'; ?>
	<div class="header" >
        <a href="index.html" class="logo"><h1>Health2me</h1></a>
        <div class="pull-right">  
            <div class="btn-group pull-right" >          
                <a class="btn btn-profile dropdown-toggle" id="button-profile" data-toggle="dropdown" href="#">
                  <span class="name-user"><strong lang="en">Welcome</strong> <?php// echo $UserName.' '.$UserSurname; ?></span> 
                  <?php 
                  //$hash = md5( strtolower( trim( $UserEmail ) ) );
                  //$avat = 'identicon.php?size=29&hash='.$hash;
                  ?>	
                  <span class="avatar" style="background-color:WHITE;"><img src="<?php// echo $avat; ?>" alt="" ></span> 
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
                            <i class="icon-globe"></i> <span lang="en">Home</span></a>
                        </li> 
                        <li><a href="medicalConfiguration.php" lang="en"><i class="icon-cog"></i> Settings</a></li>
                        <li><a href="logout.php" lang="en"><i class="icon-off"></i> Sign Out</a></li>
                    </ul>
                </div>
            </div>   
        </div>
    </div>
    <?php if($is_modal) echo ''; ?>
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
						
    ?>
	<div style="width:100%;">
        <div style="width:20%;">
            <img src="images/health2meLOGO.png" style="margin-top:5%;margin-left:15%;width:70%;">
        </div>
        <span style="margin-left:42%;margin-top:-3%;float:left;">
        <span id="NombreComp" style="font: bold 24px Arial, Helvetica, sans-serif; color: #3D93E0; cursor: auto;  margin-left:10px;"><?php //echo $show_text." ".$show_text2; ?></span>
        <span id="email" style="font-size: 14px; color: #5EB529; font-weight: bold; font-family: Arial, Helvetica, sans-serif; margin-left:10px;"><?php //echo $UserEmail;?></span>
        </span>
        <span style="float:right;margin-top:-10%;">
            <img src="<?php //echo $patient_image; ?>" style="width:80%;margin-top:20%;">
        </span>
    </div>
	 
    <div style="width:18.5%;border:1px solid gray;float:left;height:70%;margin-left:.5%;overflow:scroll;">
        <div id="facility_holder" style="display:none;" />	 
    </div>
	 
    <div style="width:80%;border:1px solid gray;height:80%;float:right;margin-right:.5%;overflow:scroll;">
	   <div id="facility_room_holder" style="display:none;" />
    </div>
    <!----------------ADD FACILITY BUTTON-->
    <div style="width: 200px; height: 30px; margin-left:2%;float:left;">
        <button id="add_facility_button" style="width: 200px; height: 30px; background-color: #22AEFF; color: #FFF; outline: 0px; border: 0px solid #FFF; border-radius: 7px; margin-top: 15px;" lang="en">
            Add Facility
        </button>
    </div>
    <!------------------END ADD FACILITY BUTTON-->
			 
    <!--ADD ROOM BUTTON------------------------>
    <div style="width: 200px; height: 30px; margin-left:2%;float:left;display:none;margin-top:-2.2%;" id="room_button_holder">
        <button onclick="addRoom();" id="add_room_button" style="width: 200px; height: 30px; background-color: #22AEFF; color: #FFF; outline: 0px; border: 0px solid #FFF; border-radius: 7px; margin-top: 15px;" lang="en">
            Add Room
        </button>
    </div>  
    <!--END ADD ROOM BUTTON---------------------->
			 
    <!--THIS FORM IS FOR ADDING A NEW FACILITY---------------------------------------------------------->
    <div id="add_facility_form" lang="en" title="Add Facility To Schedule" style="display: none;">		 
        <div style="width: 500px; height: 25px; color: #777; margin-top: 25px;"> 
            <span style="float: left; margin-top: 6px; margin-right: 84px;" lang="en">Facility Name :</span>
            <input type="text" id="facility_name" style="float: left; width: 250px;" >
            <span style="float: left; margin-top: 6px; margin-right: 83px;" lang="en">Facilty Street :</span>
            <input type="text" id="facility_street" style="float: left; width: 250px;" >
            <span style="float: left; margin-top: 6px; margin-right: 97px;" lang="en">Facility City :</span>
            <input type="text" id="facility_city" style="float: left; width: 250px;" >
            <span style="float: left; margin-top: 6px; margin-right: 86px;" lang="en">Facility State :</span>
            <input type="text" id="facility_state" style="float: left; width: 250px;" >
            <span style="float: left; margin-top: 6px; margin-right: 102px;" lang="en">Facility Zip :</span>
            <input type="text" id="facility_zip" style="float: left; width: 250px;" >
        </div>
			
        <div style="width: 500px; height: 25px; color: #777; margin-top: 25px;"> 

        <div style="width: 200px; height: 30px; margin: auto;">
             <button id="create_facility_button" style="width: 200px; height: 30px; background-color: #22AEFF; color: #FFF; outline: 0px; border: 0px solid #FFF; border-radius: 7px; margin-top: 15px;" lang="en">
                Add Facility To Schedule
             </button>
         </div>
         </div>			 
    </div>
    <!--END THIS FORM IS FOR ADDING A NEW FACILITY------------------------------------------------------------------>
		
    <!------------------ MODAL VIEW FOR SUMMARY -->
    <div id="summary_modal" lang="en" title="Summary" style="display:none; text-align:center; width: 1000px; height: 660px; overflow: hidden;" />
    <!---------------------- END MODAL VIEW FOR SUMMARY -->
		
    <!--CHANGE APPOINTMENT DIALOG BOX------------------------------>
    <div id="change_appointment" lang="en" title="Alter Appointment" style="display: none;">
        <button id="show_summary" style="width: 200px; height: 30px; background-color: #22AEFF; color: #FFF; outline: 0px; border: 0px solid #FFF; border-radius: 7px; margin-top: 15px;" lang="en">
            Display Summary
        </button>
        </br>
        <button id="cancel_appointment" style="width: 200px; height: 30px; background-color: #22AEFF; color: #FFF; outline: 0px; border: 0px solid #FFF; border-radius: 7px; margin-top: 15px;" lang="en">
            Cancel Appointment
        </button>
        </br>
        <button id="reschedule_appointment" style="width: 200px; height: 30px; background-color: #22AEFF; color: #FFF; outline: 0px; border: 0px solid #FFF; border-radius: 7px; margin-top: 15px;" lang="en">
            Reschedule Appointment
        </button>
        </br>
        <button id="check_in" style="width: 200px; height: 30px; background-color: #48a25a; color: #FFF; outline: 0px; border: 0px solid #FFF; border-radius: 7px; margin-top: 15px;" lang="en">
            Check In
        </button>
        </br>
        <div id="facility_room_display" style="float:right;width:275px;height:150px;margin-top:-33.5%;" />
    </div>
    <!--END CHANGE APPOINTMENT DIALOG BOX--------------------------------->
		
    <div id="timeslot_box" lang="en" title="Choose A Timeslot" style="display: none;" />
    
    <div id="room_details" lang="en" title="Add Member To Room" style="display: none;">
        <div id="room_box" />
    </div>
    
    <input type="hidden" value="<?php echo $MEDID; ?>" id="doc_id_holder" />
    <input type="hidden" value="" id="facility_id_holder" />
    <!--------------------------THIS IS CRITICAL FOR ASSIGNING THE CORRECT DATE AND TIME ON ENTRY---------------------------------->
    <input type="hidden" value="<?php $todays_date = new DateTime('now');							
                                      $result = $con->prepare("SELECT timezone FROM doctors where id=?");
                                      $result->bindValue(1, $MEDID, PDO::PARAM_INT);
                                      $result->execute();
                                      $row_timezone = $result->fetch(PDO::FETCH_ASSOC);

                                      $doc_timezone = $row_timezone['timezone'];
                                      $pull_increment = explode(":", $doc_timezone);

                                      $this_hour = $todays_date->format('H');
                                      $this_hour = $this_hour + $pull_increment[0] + 1;
                                      $rem_hour = $this_hour % 12;
                                      $am_pm = $this_hour / 12;
                                      if($am_pm > 1){
                                        $rem_hour = $rem_hour."pm - ".($rem_hour+1)."pm";
                                        $late_hour = ($rem_hour+1)."pm - ".($rem_hour+2)."pm";
                                      }elseif($am_pm == 1){
                                        $rem_hour = "12pm - 1pm";
                                        $late_hour = "1pm - 2pm";
                                      }else{
                                        $rem_hour = $rem_hour."am - ".($rem_hour+1)."am";
                                        $late_hour = ($rem_hour+1)."am - ".($rem_hour+2)."am";
                                      }
	 echo $rem_hour; ?>" id="time_holder" />
	 <input type="hidden" value="<?php echo $todays_date->format('Y-m-d'); ?>" id="date_holder" />
	 <!---------------------------END THIS IS CRITICAL FOR ASSIGNING THE CORRECT DATE AND TIME ON ENTRY---------------------------------->
	 <input type="hidden" value="" id="member_id_holder" />
	 
	 <?php
         //THIS WILL CHANGE STATUS BASED ON TIME/////////////////////////////////////
        $time_array = array('8am - 9am', '9am - 10am', '10am - 11am', '11am - 12pm', '12pm - 1pm', '1pm - 2pm', '2pm - 3pm', '3pm - 4pm', '4pm - 5pm', '5pm - 6pm', '6pm - 7pm', '7pm - 8pm', '8pm - 9pm', '9pm - 10pm');

        $build_query = '';
        foreach($time_array as $late){
           $build_query.="'".$late."'"." OR time=";
           if($late == $late_hour){
               $build_query.="'".$late."'";
               break;
           }
        }

        //$late_query = $con->prepare("UPDATE schedule_appointment SET status=2 WHERE time=".$build_query." && status!=2");
        //$late_query->execute();

        ///////////////////////////////////////////////////////////////////////////
	 ?>
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

	<script src="imageLens/jquery.imageLens.js" type="text/javascript"></script>
   

    <script src="TypeWatch/jquery.typewatch.js"></script>
	
	<script src="build/js/intlTelInput.js"></script>
	<script src="js/isValidNumber.js"></script>
	
    <script type="text/javascript" >
	//THESE FUNCTIONS ARE RUN AFTER PAGE LOADS FOR DISPLAY LATER///////////////////////////////////////////////////////////
	$( document ).ready(function() {
        //pullCalendar();
        getFacilityInfo();
        getFacilityInfoAll();
        //pullDateTimeSlot();
	});
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	//THIS WILL OPEN A DIALOG WHERE DOCS CAN ADD NEW FACILITIES///////////////////////////////////////////////////////////////////////
	$("#add_facility_button").live('click',function(){
        $( "#add_facility_form" ).dialog({
              autoOpen: true,
              resizable:false,
              height: 350,
              width: 520,
              modal: true,

        });       
	});
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	//THIS WILL DISPLAY SUMMARY FOR AN APPOINTMENT/////////////////////////////////////////////////////
	var summary_modal = $("#summary_modal").dialog({bigframe: true, width: 1050, height: 690, resize: false, modal: true, autoOpen: false});
    $("#show_summary").on('click',function(e){
        e.preventDefault();
        var myClass = $('#member_id_holder').val();
        //alert('reach here');
        $("#summary_modal").html('<iframe src="medicalPassport.php?modal=1&IdUsu='+myClass+'" width="1000" height="660" scrolling="no" style="width:1000px;height:660px; margin: 0px; border: 0px solid #FFF; outline: 0px; padding: 0px; overflow: hidden;"></iframe>');

        summary_modal.dialog('open');                        
	});
	//////////////////////////////////////////////////////////////////////////////////////////////////////
        
    //THIS DELETES AN APPOINTMENT/////////////////////////////////////////////////////
    $("#cancel_appointment").on('click',function(e){
        e.preventDefault();
        var roomid = $('#doc_id_holder').val();
        var memid = $('#doc_id_holder').val();
        if(confirm('Cancel this appointment?')) cancelAppintment(apptId); 
    });
        
    function cancelAppointment(roomid, memid, date, time)
    {
        var link = ""+id;    
        $.ajax({
           url: link,
           dataType: "html",
           async: true,
           success: function(data)
           {
                //ADD HTML DATA FROM AJAX CALL......
                $('#facility_holder').html(data);
				
				//REPLACES CSS TO DISPLAY FACILITY_HOLDER BLOCK.....
				var myElement = document.querySelector("#facility_holder");
				myElement.style.display = "block";
           }
        });
    }

	//////////////////////////////////////////////////////////////////////////////////////////////////////
	
	//THIS OPENS CHANGE APPOINTMENT DIALOG//////////////////////////////////////////////////////////////////
	function changeAppointment(id, mem_id)
	{
	    $( "#change_appointment" ).dialog({
          autoOpen: true,
          resizable:false,
          height: 250,
          width: 520,
          modal: true,                      
        });
        
        //THIS SETS HIDDEN INPUT FOR MEMBER ID THAT CAN BE CALLED LATER TO PULL SUMMARY FOR CORRECT PATIENT.....
        $('#member_id_holder').val(mem_id);
        getFacilityRoomForDisplay(id);
	}
	////////////////////////////////////////////////////////////////////////////////////////////
	
	//THIS CREATES A FACILITY TO SCHEDULE PATIENTS/////////////////////////////////////////////////
	$("#create_facility_button").on('click', function()
    {
        //PULL INPUTS FROM ADD_FACILITY_FORM................
        var facility_name = $('#facility_name').val();
        var facility_street = $('#facility_street').val();
        var facility_city = $('#facility_city').val();
        var facility_state = $('#facility_state').val();
        var facility_zip = $('#facility_zip').val();
        var doc_id = $('#doc_id_holder').val();

        //THIS IS PRETTY SELF EXPLAINATORY.....
        var url = 'createFacilityData.php?name='+facility_name+'&street='+facility_street+'&city='+facility_city+'&state='+facility_state+'&zip='+facility_zip+'&docid='+doc_id;
        var Rectipo = LanzaAjax(url);
        alert('Facility has been added to the schedule.');
        $('#add_facility_form').dialog('close');

        //CLEARING FACILITY ADD FORM INPUTS.............
        var facility_name = $('#facility_name').val("");
        var facility_street = $('#facility_street').val("");
        var facility_city = $('#facility_city').val("");
        var facility_state = $('#facility_state').val("");
        var facility_zip = $('#facility_zip').val("");
        getFacilityInfo();
    });
	///////////////////////////////////////////////////////////////////////////////////////////////////////
	
	//THIS PULLS A LIST OF ALL FACILITIES FOR WHERE ASSIGNED TO DOC_ID/////////////////////////////////////
	function getFacilityInfo()
	{
		var doc_id = $('#doc_id_holder').val();
		var link = 'getFacilityInfo.php?docid='+doc_id;
	
		$.ajax({
           url: link,
           dataType: "html",
           async: true,
           success: function(data)
           {
                //ADD HTML DATA FROM AJAX CALL......
                $('#facility_holder').html(data);
				
				//REPLACES CSS TO DISPLAY FACILITY_HOLDER BLOCK.....
				var myElement = document.querySelector("#facility_holder");
				myElement.style.display = "block";
           }
        });

	
	}
	/////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	//THIS IS A SECONDARY DISPLAY OF ALL FACILITIES, BUT DISPLAYS STATS FOR THAT FACILITY///////////////////
	function getFacilityInfoAll()
	{
		var doc_id = $('#doc_id_holder').val();
		//YOU NOTICE 'GRABSTATS' $_GET DATA ALTER AJAX CALL TO DISPLAY SLIGHLY DIFFERENT RESULTS.......
		var link = 'getFacilityInfo.php?docid='+doc_id+'&grabstats=yes';
			
		$.ajax({
           url: link,
           dataType: "html",
           async: true,
           success: function(data)
           {
               //ADDS HTML FROM AJAX CALL TO FACILITY ROOM HOLDER......
               $('#facility_room_holder').html(data);
				
               //CHANGES CSS TO DISPLAY FACILITY ROOM HOLDER BLOCK.....
               var myElement = document.querySelector("#facility_room_holder");
               myElement.style.display = "block";
           }
        });
	}
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	//THIS ALTERS THE TIMESLOT YOU ARE CURRENTLY VIEWING/////////////////////////////////////////////////////////
	function setTimeSlot(time){
        $('#time_holder').val(time);
        $("div#timeslot_display").text(time);
        var dateslot_holder = $('#date_holder').val();
        //THIS WILL CHANGE THE TITLE OF THE TIMESLOT BOX TO MATCH THE NEW TIMESLOT............
        $( "#timeslot_box" ).dialog( "option", "title", "Selected Timeslot : "+dateslot_holder+" @ "+time );
        var facilityIdHolder = $('#facility_id_holder').val();
        //THIS WILL CHANGE THE ROOMS THAT ARE BEING DISPLAYED BASED ON NEW TIMESLOT..........
        getFacilityRooms(facilityIdHolder);
	}
	////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	//THIS PULLS THE CALENDAR WHERE YOU CAN CHOOSE A NEW DATE AND TIMESLOT///////////////////////////////////
	function pullCalendar(holder){
        var doc_id = $('#doc_id_holder').val();
        var link2 = 'getTimeslots.php?docid='+doc_id+'&holder='+holder;
        $.ajax({
           url: link2,
           dataType: "html",
           async: true,
           success: function(data)
           {
                $('#timeslot_box').html(data);
           }
        });
	}
	///////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	//THIS SETS THE ACTUAL DATE FOR THE NEW DATESLOT///////////////////////////////////////////////////
	function setDateSlot(date){
        $('#date_holder').val(date);
        $("div#dateslot_display").text(date+' @ ');
        var timeslot_holder = $('#time_holder').val();
        //THIS WILL CHANGE THE TITLE OF THE TIMESLOT BOX TO MATCH THE NEW DATESLOT............
        $( "#timeslot_box" ).dialog( "option", "title", "Selected Timeslot : "+date+" @ "+timeslot_holder );
        var facilityIdHolder = $('#facility_id_holder').val();
        //THIS WILL CHANGE THE ROOMS THAT ARE BEING DISPLAYED BASED ON NEW DATESLOT..........
        getFacilityRooms(facilityIdHolder);
	}
	///////////////////////////////////////////////////////////////////////////////////////
	
	//THIS WILL PULL NEW DATESLOT AND TIMESLOT AND ADD TO MAIN SCREEN TO MATCH DIALOG/////////////////////////
	function pullDateTimeSlot(){
        var timeslot_holder = $('#time_holder').val();
        var dateslot_holder = $('#date_holder').val();
        $("div#dateslot_display").text(dateslot_holder+' @ ');
        $("div#timeslot_display").text(timeslot_holder);
	}
	//////////////////////////////////////////////////////////////////////////////////////////////////////
	
	//THIS WILL PULL A LIST OF YOUR PATIENTS FOR SCHEDULING PURPOSES////////////////////////////////
	function createRoomDetails(id){
        $( "#room_details" ).dialog({
              autoOpen: true,
              resizable:false,
              height: 350,
              width: 720,
              modal: true,

        });
        //THIS DISPLAYS A SPINNING ICON WHILE LOADING.............
        $('#room_box').html('<span style="font-size:40px;" id="H2M_Spin_Stream"><center><i class="icon-spinner icon-5x icon-spin" ></center></i><p><center>Loading please wait...</center></span>');

		var doc_id = $('#doc_id_holder').val();
		var link = 'getMembersForSchedule.php?id='+id+'&IdMed='+doc_id+'&Usuario=';
		$.ajax({
           url: link,
           dataType: "html",
           async: true,
           success: function(data)
           {
				$('#room_box').html(data);				
           }
        });			
	}
	////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	//THIS OPENS DIALOG BOX TO SHOW THE TIMSLOTS AND PULLS CALENDAR TO PICK NEW DATE/TIME////////////////////
	function showTimeSlots(){
        $( "#timeslot_box" ).dialog({
              autoOpen: true,
              resizable:false,
              height: 420,
              width: 660,
              modal: true,
        });
        //THIS WILL PULL AJAX CALL OF CALENDAR, THE PARAMETER INDICATES THAT IT IS TODAY + 4 WEEKS TO DISPLAY.....IF THIS WAS 1 IT WOULD SHOW THE NEXT 4 WEEKS..... IF -1 IT SHOWS PREVIOUS 4 WEEKS...............
        pullCalendar(0);
    }
	/////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	//THIS ACTUAL SETS THE APPOINTMENT FOR MEMBER SELECTED///////////////////////////////////////////
	function setAppointmentForMember(member, room_id){
        var timeslot_holder = $('#time_holder').val();
        var dateslot_holder = $('#date_holder').val();
        var facility_id = $('#facility_id_holder').val();

        var link = 'setAppointmentForMember.php?id='+member+'&roomid='+room_id+'&date='+dateslot_holder+'&time='+timeslot_holder;
        $.ajax({
           url: link,
           dataType: "html",
           async: true,
           success: function(data)
           {
                alert('You have scheduled the patient successfully.');
                $('#room_details').dialog('close');
                //REFRESHES DISPLAYED ROOMS WITH NEW APPOINTMENT ADDED.......
                getFacilityRooms(facility_id);
           }
        });
	}
	/////////////////////////////////////////////////////////////////////////////////////////////////////
	
	//THIS PULLS A LIST OF ALL THE ROOMS FOR THAT FACILITY AND SET TIMESLOT & DATESLOT////////////////////////
	function getFacilityRooms(id)
	{
		var timeslot_holder = $('#time_holder').val();
		var dateslot_holder = $('#date_holder').val();
		var link = 'getFacilityRooms.php?id='+id+'&date='+dateslot_holder+'&time='+timeslot_holder;
			
		$.ajax({
           url: link,
           dataType: "html",
           async: true,
           success: function(data)
           {
				$('#facility_room_holder').html(data);
				var myElement = document.querySelector("#facility_room_holder");
				myElement.style.display = "block";
				//THIS WILL ADD THE DATE/TIME OF NOW TO DISPLAY CORRECT TIMESLOT ON LOGIN.........,.
				pullDateTimeSlot();
           }
        });
        
		//SHOWS AND HIDES APPROPRIATE ELEMENTS......................
		var myElement = document.querySelector("#add_facility_button");
		myElement.style.display = "none";
		var myElement = document.querySelector("#room_button_holder");
		myElement.style.display = "block";
		//THIS ASSIGNS THE FACILITY ID TO BE USED LATER..............
		$('#facility_id_holder').val(id);		
	}
	//////////////////////////////////////////////////////////////////////////////////////////////////
	
	//THIS IS AN ALTERED AJAX QUERY TO DISPLAY THE ROOM SELECTED FOR CHANGEAPPOINTMENT();---===='SHOWROOM=YES'====---////////////////////////
	function getFacilityRoomForDisplay(id)
	{
		var timeslot_holder = $('#time_holder').val();
		var dateslot_holder = $('#date_holder').val();
		var link = 'getFacilityRooms.php?id='+id+'&date='+dateslot_holder+'&time='+timeslot_holder+'&showroom=yes';
			
		$.ajax({
           url: link,
           dataType: "html",
           async: true,
           success: function(data)
           {
				$('#facility_room_display').html(data+'<input type="hidden" id="roomid" value="'+id+'" />');
           }
        });
	}
	//////////////////////////////////////////////////////////////////////////////////////////////////
	
	//THIS WILL ADD A NEW ROOM TO THE FACILITY THAT WAS SELECTED/////////////////////////////////////
	function addRoom()
	{
		var id = $('#facility_id_holder').val();
		var link = 'createRoom.php?id='+id;
		
		$.ajax({
           url: link,
           dataType: "html",
           async: true,
           success: function(data)
           {
				
           }
        });

	   getFacilityRooms(id);
	}
	///////////////////////////////////////////////////////////////////////////////////////////
	
	//STANDARD AJAX CALL FUNCTION////////////////////////////////////////////////////////////
	function LanzaAjax (DirURL)
		{
		var RecTipo = 'SIN MODIFICACIÓN';
		
	    $.ajax(
        {
            url: DirURL,
            dataType: "html",
            async: false,
            complete: function(){ //alert('Completed');},
            success: function(data) 
            {
                if (typeof data == "string") RecTipo = data;
            }
        });
        return RecTipo;
    }
    //////////////////////////////////////////////////////////////////////////////////
</script>
