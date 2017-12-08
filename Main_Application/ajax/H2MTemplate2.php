<?php
/*KYLE
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
if ($Acceso != '23432')
{
echo "Incorrect credentials for login";
echo "<br>\n"; 	
echo "<h2><a href='".$domain."'>Return to Health2me Dashboard</a></h2>";
die;
}

					// Connect to server and select databse.
$link = mysql_connect("$dbhost", "$dbuser", "$dbpass")or die("cannot connect");
mysql_select_db("$dbname")or die("cannot select DB");

$result = mysql_query("SELECT * FROM usuarios where Identif='$UserID'");
$count=mysql_num_rows($result);
$row = mysql_fetch_array($result);
$success ='NO';
if($count==1){
	$success ='SI';
	/*
    $MedID = $row['id'];
	$MedUserEmail= $row['IdMEDEmail'];
	$MedUserName = $row['Name'];
	$MedUserSurname = $row['Surname'];
	$MedUserLogo = $row['ImageLogo'];
	$IdMedFIXED = $row['IdMEDFIXED'];
	$IdMedFIXEDNAME = $row['IdMEDFIXEDNAME'];
*/
/*KYLE
    $UserID = $row['Identif'];
	$UserEmail= $row['email'];
    $UserName = $row['Name'];
    $UserSurname = $row['Surname'];
    $UserPhone = $row['telefono'];
    //$UserLogo = $row['ImageLogo'];
    $IdUsFIXED = $row['IdUsFIXED'];
    $IdUsFIXEDNAME = $row['IdUsFIXEDNAME'];
    $privilege=1;

    $IdDoctor = $row['IdInvite'];
    $resultD = mysql_query("SELECT * FROM doctors where id='$IdDoctor'");
	$rowD = mysql_fetch_array($resultD);
    $NameDoctor = $rowD['Name'];
    $SurnameDoctor = $rowD['Surname'];
	$DoctorEmail = $rowD['IdMEDEmail'];
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
$result = mysql_query("SELECT * FROM lifepin");

?>
<!DOCTYPE html>
<html lang="en" style="background: #F9F9F9;"><head>
    <meta charset="utf-8">
    <title>Inmers - Center Management Console</title>
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
    <link rel="stylesheet" type="text/css" href=href="css/googleAPIFamilyCabin.css">
      <script type="text/javascript" src="js/42b6r0yr5470"></script>
	<link rel="stylesheet" href="css/icon/font-awesome.css">
 <!--   <link href="//netdna.bootstrapcdn.com/font-awesome/4.0.0/css/font-awesome.css" rel="stylesheet"> -->
    <link rel="stylesheet" href="css/bootstrap-responsive.css">
	<link rel="stylesheet" href="css/toggle-switch.css">
	
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
	</style>
	<style>
	#overlay {
	  background-color: none;
	  position: auto;
	  top: 0; right: 0; bottom: 0; left: 0;
	  opacity: 1.0; /* also -moz-opacity, etc. */
	  /*KYLE
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
      
      
      
      <!-- MODAL VIEW TO FIND DOCTOR -->
    <div id="find_doctor_modal" title="Find Doctor" style="display:none; text-align:center; padding:20px;">
        <div id="Talk_Section_1" style="display: block;">
            <!--<input type="text" style="width: 90%; margin-top: 15px; margin-bottom: 15px; height: 20px; color: #CACACA; padding: 5px;" placeholder="Search for a doctor..." value="" />-->
            <style>
            .recent_doctor_button{
                    padding: 3px; 
                    width: 80%; 
                    margin: auto; 
                    color: #22aeff; 
                    background-color: #FBFBFB; 
                    height: 25px; 
                    border: 1px solid #CFCFCF;
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
           
            </style>
            <div id="recent_doctors_section" style="display: block;"></div>
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
        </div>
        <div id="Talk_Section_2" style="display: none;">
            <button style="width: 200px; heightL 30px; background-color: #22aeff; color: #FFF; border: 0px solid #FFF; margin: auto; margin-top: 15px; margin-left: 20px; border-radius: 7px; outline: 0px; float: left;" id="video_call_button">Video Call</button>
            <button style="width: 200px; heightL 30px; background-color: #22aeff; color: #FFF; border: 0px solid #FFF; margin: auto; margin-top: 15px; margin-right: 20px; border-radius: 7px; outline: 0px; float: right;" id="phone_call_button">Phone Call</button>
           
            
        </div>
        <div id="Talk_Section_3" style="display: none;">
            <br/>
            <p>No doctors are available at this time. Please try again later.</p>
           
            
        </div>
        <div id="Talk_Section_4" style="display: none;">
            <br/>
            <p>We are now calling your doctor, please wait...</p>
           
            
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
    	<input type="hidden" id="IdMEDLogo" Value="<?php echo $MedUserLogo; ?>">
        <input type="hidden" id="USERNAME" Value="<?php echo $UserName; ?>">	
        <input type="hidden" id="USERSURNAME" Value="<?php echo $UserSurname; ?>">	
        <input type="hidden" id="USERPHONE" Value="<?php echo $UserPhone; ?>">	
  		
           <a href="index.html" class="logo"><h1>Health2me</h1></a>
           
           <div class="pull-right">
           
            
           <!--Button User Start-->
		   <div class="btn-group pull-right" >
           
            <a class="btn btn-profile dropdown-toggle" id="button-profile" data-toggle="dropdown" href="#">
              <span class="name-user"><strong>Welcome</strong> <?php echo $UserName.' '.$UserSurname; ?></span> 
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
			  <li><a href="dashboard.php"><i class="icon-globe"></i> Home</a></li>
              <li><a href="medicalConfiguration.php"><i class="icon-cog"></i> Settings</a></li>
              <li><a href="logout.php"><i class="icon-off"></i> Sign Out</a></li>
            </ul>
            </div>
          </div>
          <!--Button User END-->  
          
          </div>
    </div>
    <!--Header END-->
 
   	 <!--- VENTANA MODAL  This has been added to show individual message content which user click on the inbox messages ---> 
   	 <button id="message_modal" data-target="#header-message" data-toggle="modal" class="btn btn-warning" style="display: none;">Modal with Header</button> 
   	  <div id="header-message" class="modal hide" style="display: none;" aria-hidden="true">
         <div class="modal-header">
             <button class="close" type="button" data-dismiss="modal">×</button>
                  Message Details
         </div>
         <div class="modal-body">
         <div class="formRow" style=" margin-top:-10px; margin-bottom:10px;">
             <span id="ToDoctor" style="color:#2c93dd; font-weight:bold;">TO <?php echo 'Dr. '.$NameDoctor.' '.$SurnameDoctor; ?></span><input type="hidden" id="IdDoctor" value='<?php echo $IdDoctor; ?>'/>
         </div>
         <textarea  id="messagedetails" class="span message-text" style="height:200px;" name="message" rows="1"></textarea>
         
		 <form id="replymessage" class="new-message">
                   <div class="formRow">
                        <label>Subject: </label>
                        <div class="formRight">
                            <input type="text" id="subjectname_inbox" name="name"  class="span"> 
                        </div>
                   </div>
				   <div class="formRow">
						<label>Attachments: </label>
						<div id="attachreportdiv" class="formRight">
							<input type="button" class="btn btn-success" value="Attach Reports" id="attachreports">
						</div>
				   </div>
                   <div class="formRow">
                        <label>Message:</label>
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
	         <a href="#" class="btn btn-primary" data-dismiss="modal" id="CloseMessage">Close</a>
         </div>
      </div>  
	  <!--- VENTANA MODAL  ---> 	
       
    <!--Content Start-->
	<div id="content" style="background: #F9F9F9; padding-left:0px;">
    
    	    
	 <!--SpeedBar Start--->
     <div class="speedbar">
     <div class="speedbar-content">
     <ul class="menu-speedbar">		
			  <li><a href="dashboard.php"><i class="icon-globe"></i> Home</a></li>
              <li><a href="medicalConfiguration.php"><i class="icon-cog"></i> Settings</a></li>
              <li><a href="logout.php" style="color:yellow;"><i class="icon-off"></i> Sign Out</a></li>
     </ul>

     
     </div>
     </div>
     <!--SpeedBar END-->
     
     
     
     <!--CONTENT MAIN START-->
     <div class="content">
	     <div class="grid" class="grid span4" style="width:1000px; height:500px; margin: 0 auto; margin-top:30px; padding-top:30px;">
	
	        
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

        
    $(document).ready(function() {
	
	$(window).load(function() {
		$('#LoadCanvas1').css('display','none');
		$('#LoadCanvas2').css('display','none');
		LoadDonuts();
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
				ConnData = data;
			}
		});
		
	  AdminData =	ConnData.AdminData;

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
	  
	  ColSeg[1] = '#54bc00';
	  MaxValue[1] = 5;
	  UIValue[1] = 10;
	  if (AdminData > MaxValue[1]) {SizSeg[1] = UIValue[1]} else {SizSeg[1] = (AdminData * UIValue[1] / MaxValue[1])};
	  //alert (SizSeg[1]+' - '+MaxValue[1]+' - '+UIValue[1]+' - '+AdminData);
	  ImgSeg[1] = 'Admin';
	  ColSeg[2] = '#2c3e50';
	  SizSeg[2] = 40;
	  ImgSeg[2] = 'History';
	  ColSeg[3] = '#18bc9c';
	  SizSeg[3] = 15;
	  ImgSeg[3] = 'Medication';
	  ColSeg[4] = '#f39c12';
	  SizSeg[4] = 10;
	  ImgSeg[4] = 'Immuno';
	  ColSeg[5] = '#e74c3c';
	  SizSeg[5] = 15;
	  ImgSeg[5] = 'Family';
	  ColSeg[6] = '#3498db';
	  SizSeg[6] = 10;
	  ImgSeg[6] = 'Habits';


	  var SummaryData = 0;
	  
      context.lineWidth = 35;
	  startAngle = 0 - (Math.PI/2);
	  var lastAngle = 0;
	      	    
	  var n = 1;
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
	      context.strokeStyle = SegColor;
	      endAngle = startAngle + TranslateAngle(SizSeg[n],100);
	      midAngle = startAngle + (TranslateAngle(SizSeg[n]/2,100));
		  context.lineWidth = 35;
	      context.arc(x, y, radius, startAngle, endAngle, counterClockwise);
	      context.stroke();
		  
	      // Draw icons into segments
	      var posx = x + (radius * Math.cos (midAngle)) - 10;
	      var posy = y + (radius * Math.sin (midAngle)) - 10;
		  var imageObj=document.getElementById(ImgSeg[n]);
		  if (SizSeg[n] > 4) context.drawImage(imageObj, posx, posy,20,20);
	     
	     
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
	      	    
	  var n = 1;
	  while (n <= MaxSegments)
	  {
	      context.beginPath();
	      SegColor = ColSeg[n];
	      if (SizSeg[n]>0){
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
	      context.stroke();
		  context.lineWidth = 35;

 		  // Draw Main Text
		  var font = '30pt Lucida Sans Unicode';
		  var message = MaxReports+'';
		  context.fillStyle = '#54bc00';
		  context.fillStyle = 'white';
		  context.textAlign = 'left';
		  context.textBaseline = 'top'; // important!
		  context.font = font;
		  var w = context.measureText(message).width;
		  var TextH = GetCanvasTextHeight(message,font);
		  context.fillText(message, x-(w/2), y-(TextH));
 
 
	//}, 500)
	};


	
	$('body').bind('mousedown keydown', function(event) {
        clearTimeout(timeoutTimer);
        timeoutTimer = setTimeout(ShowTimeOutWarning, timeoutTime);
    });

	$('#myCanvas').live('click',function(){
		var myClass = $('#USERID').val();
		window.location.replace('medicalPassport.php?IdUsu='+myClass);
	});

	$('#myCanvas2').live('click',function(){
		var myClass = $('#USERID').val();
		window.location.replace('patientdetailMED-newUSER.php?IdUsu='+myClass);
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
    
    var doctor_to_connect = '';
    var type_of_doctor_to_find = '';
    $('#Talk').live('click', function()
    {	
        doctor_to_connect = '';
        type_of_doctor_to_find = '';
        $('.recent_doctor_button_selected').attr("class", "recent_doctor_button");
        $("#Talk_Section_1").css("display", "block");
        $("#Talk_Section_2").css("display", "none");
        $("#Talk_Section_3").css("display", "none");
        var h = ($("#recent_doctors").children().length - 1) * 35;
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
        }
        $("#find_doctor_modal").dialog({bgiframe: true, width: 550, height: 250 + h, resize: false, modal: true});
        
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
        $('#find_doctor_modal').dialog('option', 'title', 'Calling Doctor ' + info[2]);
        $("#Talk_Section_2").fadeOut("Slow", function(){$("#Talk_Section_4").fadeIn("slow");});
        $.post("start_telemed_phonecall.php", {pat_phone: $("#USERPHONE").val(), doc_phone: info[1], doc_id: info[0], pat_id: $("#USERID").val(), doc_name: (info[2] + ' ' + info[3]), pat_name: ($("#USERNAME").val() + ' ' + $("#USERSURNAME").val())}, function(data, status)
        {
            console.log(data);
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
				/*KYLE
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
	   /*KYLE
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
		/*KYLE
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
	   /*KYLE
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
				 /*KYLE
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
	/*KYLE
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
                                  

    	    getLifePines(serviceURL);
    	    var longit = Object.keys(pines).length;	
    	    */
    	    //alert (longit);
    	     /*KYLE
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
/*KYLE
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
/*KYLE
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
/*KYLE
    return end - start;
    };
				 		
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
