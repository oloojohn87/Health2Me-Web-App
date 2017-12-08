<?php

require("environment_detailForLogin.php");
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass']; 

// Connect to server and select databse.
 $link = mysql_connect("$dbhost", "$dbuser", "$dbpass")or die("cannot connect");
 mysql_select_db("$dbname")or die("cannot select DB");

$user = $_GET['user'];
$email = $_GET['emailDoc'];


$splittedUserName = explode(" ",$user);
$userName = $splittedUserName[0];
$userSurname = $splittedUserName[1];


$alias = $userName.".".$userSurname;


$query1 = mysql_query("select Identif,email from usuarios where alias = '$alias'");

$row1 = mysql_fetch_array($query1);
$patientId = $row1['Identif'];
$emailUser = $row1['email'];

$query2 = mysql_query("select id from doctors where IdMEDEmail = '$email'");
$row2 = mysql_fetch_array($query2);
$doctorId = $row2['id'];


$date1 = date('Y/m/d');
$date2 = explode("/",$date1);
$dateFinal= "\"".$date2[1]."/".$date2[2]."/".$date2[0]."\"";


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
    <!--<link href="css/style_version1.css" rel="stylesheet"> Commented out by Pallab for datepicker stupid calendar-->
    <link href="css/bootstrap.css" rel="stylesheet">  
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="font-awesome/css/font-awesome.min.css">
    <!--<link rel="stylesheet" href="css/basic.css" /> -->
    
    <link rel="stylesheet" href="css/jquery-ui-1.8.16.custom.css" media="screen"  />
   <!-- <link rel="stylesheet" href="http://code.jquery.com/ui/1.9.2/themes/base/jquery-ui.css" /> -->
    
    <link rel="stylesheet" href="css/fullcalendar.css" media="screen"  />
    <link rel="stylesheet" href="css/chosen.css" media="screen"  />
    <link rel="stylesheet" href="css/datepicker.css" >
    <link rel="stylesheet" type="text/css" href="css/jquery.timepicker.css" />
    <link rel="stylesheet" href="css/colorpicker.css">
    <link rel="stylesheet" href="css/glisse.css?1.css">
    <link rel="stylesheet" href="css/jquery.jgrowl.css">
    <link rel="stylesheet" href="js/elfinder/css/elfinder.css" media="screen" />
    <link rel="stylesheet" href="css/jquery.tagsinput.css" />
    <link rel="stylesheet" href="css/demo_table.css" >
    <link rel="stylesheet" href="css/jquery.jscrollpane.css" >
    <link rel="stylesheet" href="css/validationEngine.jquery.css">
    <link rel="stylesheet" href="css/jquery.stepy.css" />
	<link rel="stylesheet" href="css/icon/font-awesome.css">
 <!--   <link href="//netdna.bootstrapcdn.com/font-awesome/4.0.0/css/font-awesome.css" rel="stylesheet"> -->
    <link rel="stylesheet" href="css/bootstrap-responsive.css">
	<link rel="stylesheet" href="css/toggle-switch.css">
    <link rel="stylesheet" href="css/dropzone_version1.css"/>
    <link rel="stylesheet" type="text/css" href="css/tooltipster.css" />
    <script src="js/dropzone.min_version1.js"></script>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>

   
<style> 
#dragandrophandler
{
border:2px dotted #0B85A1;
width:400px;
color:#92AAB0;
text-align:center;vertical-align:middle;
padding:10px 10px 10px 10px;
margin-bottom:10px; margin-left:40px;
font-size:200%;
height:200px;
display:table-cell;
}   
.progressBar {
    width: 200px;
    height: 22px;
    border: 1px solid #ddd;
    border-radius: 5px; 
    overflow: hidden;
    display:inline-block;
    margin:0px 10px 5px 5px;
    vertical-align:top;
}
 
.progressBar div {
    height: 100%;
    color: #fff;
    text-align: right;
    line-height: 22px; /* same as #progressBar height if we want text middle aligned */
    width: 0;
    background-color: #0ba1b5; border-radius: 3px; 
}
.statusbar
{
    border-top:1px solid #A9CCD1;
    min-height:25px;
    width:400px;
    padding:10px 10px 0px 10px;
    vertical-align:top;
}
.statusbar:nth-child(odd){
    background:#EBEFF0;
}
.filename
{
display:inline-block;
vertical-align:top;
width:150px;
}
.filesize
{
display:inline-block;
vertical-align:top;
color:#30693D;
width:50px;
margin-left:10px;
margin-right:5px;
}
.abort{
    background-color:#A8352F;
    -moz-border-radius:4px;
    -webkit-border-radius:4px;
    border-radius:4px;display:inline-block;
    color:#fff;
    font-family:arial;font-size:13px;font-weight:normal;
    padding:4px 15px;
    cursor:pointer;
    vertical-align:top
    }
</style>
    

	
    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    <!--[if lte IE 8]><script type="text/javascript" src="/js/excanvas.min.js"></script><![endif]-->
 
    <!-- Le fav and touch icons -->
    <link rel="shortcut icon" href="images/icons/favicon.ico">
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

<input type="hidden" id="NombreEnt" value="<?php if(isset($NombreEnt)) echo $NombreEnt; ?>">
<input type="hidden" id="PasswordEnt" value="<?php if(isset($PasswordEnt)) echo $PasswordEnt; ?>">
<input type="hidden" id="UserHidden">

	<!--Header Start-->
	<div class="header" >
     	<input type="hidden" id="USERID" Value="<?php if(isset($UserID)) echo $UserID; ?>">	
    	<input type="hidden" id="MEDID" Value="<?php if(isset($MedID)) echo $MedID; ?>">	
    	<input type="hidden" id="IdMEDEmail" Value="<?php if(isset($MedUserEmail)) echo $MedUserEmail; ?>">	
    	<input type="hidden" id="IdMEDName" Value="<?php if(isset($MedUserName)) echo $MedUserName; ?>">	
    	<input type="hidden" id="IdMEDSurname" Value="<?php if(isset($MedUserSurname)) echo $MedUserSurname; ?>">	
    	<input type="hidden" id="IdMEDLogo" Value="<?php if(isset($MedUserLogo)) echo $MedUserLogo; ?>">
        <input type="hidden" id="USERNAME" Value="<?php if(isset($UserName)) echo $UserName; ?>">	
        <input type="hidden" id="USERSURNAME" Value="<?php if(isset($UserSurname)) echo $UserSurname; ?>">	
        <input type="hidden" id="USERPHONE" Value="<?php if(isset($UserPhone)) echo $UserPhone; ?>">	
  		
           <a href="index.html" class="logo"><h1>Health2me</h1></a>
           
           <div class="pull-right">
           
            
           <!--Button User Start-->
		   <div class="btn-group pull-right" >
           
            <a class="btn btn-profile dropdown-toggle" id="button-profile" data-toggle="dropdown" href="#">
              <span class="name-user"><strong>Welcome</strong> <?php echo $email; ?></span> 
             
              <span class="avatar" style="background-color:WHITE;"><img src="<?php if(isset($avat)) echo $avat; ?>" alt="" ></span> 
              <span class="caret"></span>
            </a>
            <div class="dropdown-menu" id="prof_dropdown">
            <div class="item_m"><span class="caret"></span></div>
            <ul class="clear_ul" >
			 <!-- <li><a href="dashboard.php"><i class="icon-globe"></i> Home</a></li>
              <li><a href="medicalConfiguration.php"><i class="icon-cog"></i> Settings</a></li> -->
             <!-- <li><a href="logout.php"><i class="icon-off"></i> Sign Out</a></li> -->
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
             <span id="ToDoctor" style="color:#2c93dd; font-weight:bold;">TO <?php if(isset($NameDoctor) && isset($SurnameDoctor)) echo 'Dr. '.$NameDoctor.' '.$SurnameDoctor; ?></span><input type="hidden" id="IdDoctor" value='<?php if(isset($IdDoctor)) echo $IdDoctor; ?>'/>
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
        <!-- <input type="hidden" id="docId" value="<?php if(isset($IdMed)) echo $IdMed; ?>"/> -->
         <input type="hidden" id="userId" value="<?php if(isset($IdUsu)) echo $IdUsu; ?>" />
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
			 <!-- <li><a href="dashboard.php"><i class="icon-globe"></i> Home</a></li>
              <li><a href="medicalConfiguration.php"><i class="icon-cog"></i> Settings</a></li> -->
           <!--   <li><a href="logout.php" style="color:yellow;"><i class="icon-off"></i> Sign Out</a></li> -->
     </ul>

     
     </div>
     </div>
     <!--SpeedBar END-->
     
     
     
     <!--CONTENT MAIN START-->
     <div class="content">

	     <div class="grid" class="grid span4" style="width:1000px; height:500px; margin: 0 auto; margin-top:30px; padding-top:30px;">

			 <div style="float:left; height:50px;">
				 <p style="font-size:16px; color:grey; margin-left:20px;">Health Information DROP AREA</p>
			 </div>	 
			 <div style="float:right; height:50px; margin-right:20px;">
				 <p style="font-size:14px; color:#54bc00; margin-left:20px;"> Patient: <span style="font-size:16px;"><?php echo $alias; ?></span></p>
				 <p style="font-size:14px; color:##22aeff; margin-left:20px; margin-top:-10px; font-style: oblique; font-weight: bold;">Personalized for: <?php echo $email; ?> </p>
			 </div>
			 
			 <!-- Utility Area -->
			 <div style="float:left; width:960px; height:10px; border:0px solid #cacaca; margin-left:20px; margin-top:20px; margin-bottom:20px;">
			 </div>

			 <!-- Left Column -->

			<div id = "dropzone" style="float:left; width:470px; height:300px; border:0px solid #cacaca; margin-left:20px; text-align:center;">
			 	 <span style="font-size:18px; color:#22aeff;">Please Drop Reports Here</span>
			 	 <div style="margin:0 auto; margin-top:15px;"><i class="icon-arrow-down icon-4x" style="color:#22aeff; margin:0 auto; "></i> </div>
			 	 
                 <!-- The original divison for dropzone.js area as used in quickdropzonefornonh2mdoc-new.php-->
                <!-- <div style="border: 3px #9a8989 dashed; border-radius:15px; margin:0 auto; margin-top:15px; background-color: #fdfcfc; color: #22aeff; height:180px; width:220px;">

			    <form action="upload_dropzoneRequestReportsExtDoc.php" method="post" class="dropzone" id="ReportsDropzone" 
                           class= "dropzone dz-cliclable" style="overflow:scroll;border-radius:15px;background-color: #fdfcfc;color: #22aeff; height:180px; width:220px;">	 	   
                      <center style="color:#cbcbcb; font-size:30px;margin-top">
                                &nbsp;&nbsp;Drop Area
                      </center>
                 </form>

			 	 </div> -->
                 
             <div id="dragandrophandler" style="position:absolute; margin-left:36px; margin-top:10px; height:190px;"><span style="margin-top:60px; margin-left:40px; float:left;">Drag & Drop Files Here</span></div>
              <br><br>
             <div id="status1"></div>
             
			</div>
			 <!-- Right Column -->
			 <style>
			 div.RepRow{
				 height:90px; 
				 width:430px; 
				 border:1px solid #cacaca; 
				 margin:0 auto;
				 margin-top:10px;
			 }
			 </style>


			 <div id="rightColumn" style="float:right;margin-right:20px; width:470px; height:300px; border:1px solid #cacaca;;overflow:scroll;">
				 
         <div id ="container" style="width: 450px;">
                 
    </div>
    


             </div>

             <style>
.bottom_buttons{
    width: 200px;
    height: 30px;
    background-color: #22aeff;
    border-radius: 7px;
    border: 0px solid #FFF;
    float: right;
    color: #FFF;
    font-weight: bold;
}
</style>

<!-- Start of finish and create button section -->             
 <div style="width: 100%; height: 30px; float: right; padding-right: 30px; margin-top: 20px;">

<button  id = "Finish" class="bottom_buttons" style="margin-left: 10px;">Finish</button>
<!--<button id ="CreateAccount" class="bottom_buttons" style="background-color: #54bc00;">Create Account</button>-->
</div>
<!-- End of finish and create button section -->             
            </div>
             
     </div>

<!-- start of code for modal window for create account button -->

  <div id="createAccount" style="overflow:visible;display:none;  padding:20px;">
        <div style="border:solid 1px #cacaca; margin-top:5px; padding:10px;">
		
			<table style="width:100%;background-color:white">
				<tr>
					<td style="width:20%"><span style="float: left;text-align:center;margin-top: 5px;font-size:14px;margin-left:5px">Name: </span></td>
					<td style="width:80%"><input id="NameCreateAccountPage" type="text" style="margin-top: 10px;width: 95%; float:left;font-size:14px;height:25px" placeholder="Enter Name"></td>
				</tr>
				<tr>
					<td style="width:20%"><span style="float: left;text-align:center;margin-top: 5px;font-size:14px;margin-left:5px">Surname: </span></td>
					<td style="width:80%"><input id="SurnameCreateAccountPage" type="text" style="margin-top: 10px;width: 95%; float:left;font-size:14px;height:25px" placeholder="Enter Surname"></td>
				</tr>
				<tr>
					<td style="width:20%"><span style="float: left;text-align:center;margin-top: 5px;font-size:14px;margin-left:5px">Email: </span></td>
					<td style="width:80%"><input id="EmailCreateAccountPage" type="text" style="margin-top: 10px;width: 95%; float:left;font-size:14px;height:25px" placeholder="Enter Email"></td>
				</tr>
				<tr>
					<td style="width:20%"><span style="float: left;text-align:center;margin-top: 5px;font-size:14px;margin-left:5px">Password: </span></td>
					<td style="width:80%"><input id="PasswordCreateAccountPage" class="password" type="password" style="margin-top: 10px;width: 95%; float:left;font-size:14px;height:25px";></td>
				</tr>
				<tr>
					<td style="width:20%"><span style="float: left;text-align:center;margin-top: 5px;font-size:14px;margin-left:0px">Repeat Password: </span></td>
					<td style="width:80%"><input id="RepeatPasswordCreateAccountPage" type="password"  style="margin-top: 10px;width: 95%; float:left;font-size:14px;;height:25px;"></td>
				</tr>
				<tr>
					<td style="width:20%"><span style="float: left;text-align:center;margin-top: 5px;font-size:14px;margin-left:5px">DOB: </span></td>
					<td style="width:80%"><input id="DOB" class ="datepicker" style="margin-top: 10px;width: 95%; float:left;font-size:14px;height:25px" placeholder="Enter Date of Birth"></td>
				</tr>
                
			</table>
            <div>
                
           <p> <span id = "gender">Gender:</span> 
		   <select name="sex" id="Gender" style="margin-top: 10px;width: 40%;font-size:14px;height:26px;margin-left:30px;" lang="en">
        <option name="sex" value="male" />Male</option><br>
        <option name="sex" value="female" />Female</option></p>
		</select>
		</div>
        <a id="createAccountNewDoc" class="btn" style="width:150px; color:#FFF; float:right; margin-top:15px;margin-bottom:10px;background-color:#54bc00;"><span>Create Account</span></a>
		
    </div>
        
<!-- End of code for modal window for create account button -->

<div id ="previewModal" style="width: 600px; height: 600px; padding: 0px;"></div>


     <!--CONTENT MAIN END-->

    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="js/jquery.min.js"></script>
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
		$(function() {
	    //var pusher = new Pusher('d869a07d8f17a76448ed');
	    //var channel_name=$('#MEDID').val();
		//var channel = pusher.subscribe(channel_name);
		//var notifier=new PusherNotifier(channel);
            
        var push = new Push($("#MEDID").val(), window.location.hostname + ':3955');
        push.bind('notification', function(data) 
        {
            displaynotification('New Message', data);
        });
		
	  });
    </script>
    
<link type="text/css" href="css/bootstrap-timepicker.min.css" />

<script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
<!--<script src="js/bootstrap.min.js"></script>-->
<!--<script src="js/bootstrap-datepicker.js"></script> -->

    <!-- Libraries for notifications -->

<script src="js/bootstrap.min.js"></script>
        
<!--<script src="http://code.jquery.com/jquery-1.11.0.min.js"></script> -->
<!--<script src="http://netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script> -->


<script src="TypeWatch/jquery.typewatch.js"></script>

<script type="text/javascript" >

// Start of code for new dropzone hayageek
var patientId = <?php echo $patientId?>;
var doctorId = <?php echo $doctorId?>;
 
function sendFileToServer(formData,status)
{
    var uploadURL ="upload_dropzoneRequestReportsExtDoc.php"; //Upload URL
    var extraData ={}; //Extra Data.
    formData.append("idus",patientId);
    formData.append("doctorId",doctorId);
    var jqXHR=$.ajax({
            xhr: function() {
            var xhrobj = $.ajaxSettings.xhr();
            if (xhrobj.upload) {
                    xhrobj.upload.addEventListener('progress', function(event) {
                        var percent = 0;
                        var position = event.loaded || event.position;
                        var total = event.total;
                        if (event.lengthComputable) {
                            percent = Math.ceil(position / total * 100);
                        }
                        //Set progress
                        status.setProgress(percent);
                    }, false);
                }
            return xhrobj;
        },
    url: uploadURL,
    type: "POST",
    contentType:false,
    processData: false,
        cache: false,
        data: formData,
        success: function(data){
            status.setProgress(100);
 
          //  $("#status1").append("File upload Done<br>");  
            
            
        }
    }); 
 
    status.setAbort(jqXHR);
}
 
var rowCount=0;
function createStatusbar(obj)
{
     rowCount++;
     var row="odd";
     if(rowCount %2 ==0) row ="even";
    this.statusbar = $("<div class='statusbar "+row+"'></div>");
    this.filename = $("<div class='filename'></div>").appendTo(this.statusbar);
    this.size = $("<div class='filesize'></div>").appendTo(this.statusbar);
    this.progressBar = $("<div class='progressBar'><div></div></div>").appendTo(this.statusbar);
    this.abort = $("<div class='abort'>Abort</div>").appendTo(this.statusbar);
     obj.after(this.statusbar);
 
    this.setFileNameSize = function(name,size)
    {
        var sizeStr="";
        var sizeKB = size/1024;
        if(parseInt(sizeKB) > 1024)
        {
            var sizeMB = sizeKB/1024;
            sizeStr = sizeMB.toFixed(2)+" MB";
        }
        else
        {
            sizeStr = sizeKB.toFixed(2)+" KB";
        }
 
        this.filename.html(name);
        this.size.html(sizeStr);
    }
    this.setProgress = function(progress)
    {       
        var progressBarWidth =progress*this.progressBar.width()/ 100;  
        this.progressBar.find('div').animate({ width: progressBarWidth }, 10).html(progress + "% ");
        if(parseInt(progress) >= 100)
        {
            this.abort.hide();
        }
    }
    this.setAbort = function(jqxhr)
    {
        var sb = this.statusbar;
        this.abort.click(function()
        {
            jqxhr.abort();
            sb.hide();
        });
    }
    
    $(".statusbar").css("display", "none");
}

//Adding variables as originallly used in below dropzone.js area

var filecount = 0;
var filename = new Array();
//var current;
var filenameLifepin = new Object();
var reportTypeAssociativeArray = new Object();
var fileAlreadyDroppedInContainer = false;
    
var date = new Date();
var today = date.getDate();
var month = date.getMonth() + 1;
var year = date.getFullYear();
var todayDate = year+'-'+month+'-'+today;

    
if(month.toString.length <  2)
    todayDate = year+'-0'+month+'-'+today;
else
    todayDate = year+'-'+month+'-'+today;
    
function handleFileUpload(files,obj)
{
  
  var originalValueFileCount = filecount;
  
  for (var i = 0; i < files.length; i++) 
   {
        var fd = new FormData();
        fd.append('file', files[i]);
 
       var status = new createStatusbar(obj); //Using this we can set progress.
        status.setFileNameSize(files[i].name,files[i].size);
        sendFileToServer(fd,status,originalValueFileCount);
       
    }
    
    
           //Alerting the user he has added the same file and needs to be deleted
            
            for(var i = 0;i < files.length;i++)            
                {   
                
                    var fileSearchStatus = jQuery.inArray(files[i].name,filename);
                    if(fileSearchStatus != -1)
                     {
                       alert("File you just dropped was already dropped and uploaded earlier. Delete subbox: "+(filecount+i+1)+" using Del button to delete the current dropped file");
                      }
                }
    
    //Code for storing the filename           
    if(fileAlreadyDroppedInContainer == true)
        {
           
        
           var j = filecount;
           
           for(var i = 0;i < files.length;i++,j++) 
            {
             filename[j] = files[i].name;
            }
            
            filecount = filecount + files.length;
        }
    else
        {
        
            filecount = 0;
            
           for(var i = filecount;i < filecount + files.length;i++) 
            {
             filename[i] = files[i].name;
            }
            
            filecount = files.length;
        }
    
    
    
    for (var i = 0; i < files.length; i++) 
       {
           //Code for getting the extension from filename and then setting up appropriate value for icon to get correct icon image
        var splittedString = files[i].name.split(".");
        var icon = splittedString[1]+'.png';
        var date = 

           $("#container").append('<div id="processedReportMetaData" class ="RepRow"            style="display:none;width:430px;height:90px;"><input type="hidden" value="" /><div style="float:left; width:20px; height:89px; background-color:#22aeff;"></div><div style="float:left; border:0px solid #cacaca; width:380px; height:89px;"><div style="float:left; width:50px; height:80px; background-color:white;"><button id="ViewFilePreview" value = "" style="margin-top:15px;margin-left:5px;height:48px;width:48px;font-weight:bold; background-image: url(images/File-icons/48px/'+icon+'); background-color: #FFF; border: 0px solid #FFF;"></button> </div><div style="float:left; width:330px; height:70px; background-color:white; padding-top:10px; border:0px solid #cacaca;"><div style="float:left; width:270px; height:70px; background-color:white; border:0px solid #cacaca;"><div style="width:280px; height:25px; border:0px solid #cacaca;"><span style="float:left; width:160px;"> Select Date Of Report</span><div style="float:left; width:100px;"><input type="date" class="span2" value="'+todayDate+'" id="ReportDate" style="margin-left:-17px;width:127px; min-height:20px; font-size:12px; "></div></div><div style="width:280px; height:25px; border:0px solid #cacaca; margin-top:5px;"><span style="float:left; width:160px;"> Select Type of Report</span><select id="ReportType" style="margin-left:-17px;width:126px;height:20px;float: left;font-size:12px;"><option value="OTHER TESTS" selected>Other Tests</option><option value="LABORATORY TESTS">Lab Reports</option><option value ="SUMMARY AND DEMOGRAPHICS">Summary</option><option value = "DOCTORS NOTES">Doctor Notes</option> <option value="PATIENTS NOTES">Patient Notes</option><option value="IMAGING TESTS">Imaging</option><option value="LIVE DATA">Pictures</option></select></div></div><div style="float:left; width:50px; height:70px; background-color:white; border:0px solid #cacaca;"><button id = "Close" class="btn btn-danger" style="height: 50px; padding-top: 0px; margin-top: 0px; margin-left:8px;value=""">Del</button></div> </div><div class="progress progress-striped active" style="float:left; width:300px; height:10px; margin-left:5px;"><div id="progressbar" class="progress-bar"  role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 45%"> <span id = "progressbarSpan" class="sr-only">45% Complete</span></div></div></div><div style="float:right; width:20px; height:89px; background-color:#54bc00;"></div></div>');
  
           
           
           
           

       }
    // The repRow(right column) has been added to container in the above statement and below line code displays it
    $("#container").children('div').each(function(){if($(this).css("display") == 'none'){$(this).slideDown();}});
    
   
    // Changing the meta data of some elements of the children of container
    if(fileAlreadyDroppedInContainer == true)
            {
               filecount = originalValueFileCount;
            }
            else
            {
               filecount = 0;
            }
    
    $("#container").children('#processedReportMetaData').each(function()
            {
                    
                      $(this).children('input').each(function(){
                    
                      $(this).attr("value",filecount);
                    });
                                                   
                      $(this).attr("id", "processedReportMetaData"+filecount);
                    
                    
                    //Changing the id of the progress bar according to each processedReportMetaData
                      $(this).find('#progressbar').each(function(){
                    
                      $(this).attr("id","progressbar"+filecount);
                    });
                    
                    //Changing the id of the ReportDate
                
                       $(this).find(".span2").each(function(){
                   
                       $(this).attr("id","ReportDate"+filecount);
                    });
                    
                    //Changing the id of the ReportType section
                    
                       $(this).find("option:first").each(function(){
                       $(this).closest("select").each(function(){
                       $(this).attr("id","ReportType"+filecount);
                     });
                    });
                    
                    
                    
                    // Changing the id and value for Preview File button
                        $(this).find('#ViewFilePreview').each(function(){
                        $(this).attr("id", "ViewFilePreview"+filecount);
                        $(this).attr("value",filecount);
                    });
                    
                    //Changing the id for Close button
                    
                        $(this).find("#Close").each(function(){
                        $(this).attr("id", "Close"+filecount);
                        $(this).attr("value",filecount);
                        
                    });
                    
                
                   var key = $("#ReportType"+filecount).attr('id');
                   console.log("in for changing id section "+key);
                   reportTypeAssociativeArray[key] = 'OTHER TESTS';
                
                   filecount++;
                
               
            });
                 //adjusting the value of filecount           
                if(fileAlreadyDroppedInContainer == true)
                                {
                                   filecount = originalValueFileCount + files.length;
                                }
                           else
                                {
                                   filecount = files.length;
                                }
    
   
                // Code for  Preview File button
                var j; 
                if(fileAlreadyDroppedInContainer == true)
                        {
                           filecount = originalValueFileCount;
                            j = filecount;
                        }
                        else
                        {
                           filecount = 0;
                            j = filecount;
                        }

                   for(var i = 0;i < files.length;i++,j++)  
                        {
                            $("#ViewFilePreview"+j).on('click',function(){

                             var current = $(this).attr('value');
                             $("#previewModal").html('<iframe src="<?php echo $domain;?>/dropzone_uploads/temporaryForFilePreview/'+filename[current]+'" style="width:600px;height:600px; margin-left: -20px;"></iframe>');

                             $("#previewModal").dialog({bigframe: true, width: 600, height: 600, modal: false});

                            });
                        }
    
            //Adjusting the value of filecount
           if(fileAlreadyDroppedInContainer == true)
            {
               filecount = originalValueFileCount + files.length;
            }
           else
            {
               filecount = files.length;
            }
                
            //Code for Close button
            var j;
            if(fileAlreadyDroppedInContainer == true)
                {
                   filecount = originalValueFileCount;
                    j = filecount;
                }
                else
                {
                   filecount = 0;
                    j = filecount;
                }
                
            for(var i = 0;i < files.length;i++,j++) 
                {
                    $("#Close"+j).off('click');
                    $("#Close"+j).on('click',function(){
                    
                    var current = $(this).attr('value');
                    
                    var gridToBeDeleted = "processedReportMetaData"+current;
                    
                    $/*(this).closest('#container').find*/('#'+gridToBeDeleted).remove();//.each(function(){
                       // alert($(this).attr('id'));
                        //$(this).remove();
                   // });
                    
                    //deleting the entry from the filenameLifepin associative array so that current status of files is maintained
                    //delete filenameLifepin[filename[current]];
                    delete reportTypeAssociativeArray["ReportType"+current];
                
                    
                    $.get("deleteFileFromLifePin.php?filename="+filename[current],function(data,status){   
                    });
                    
                    
                });
              } 
    
            //Adjusting the value of filecount
               if(fileAlreadyDroppedInContainer == true)
                {
                   filecount = originalValueFileCount + files.length;
                }
               else
                {
                   filecount = files.length;
                }
    
				
                   // Code for datepicker text area in dob of create account page
                   // Changing the width of the progress bar 
                   // Binding the function to the Report Type button adjacent to the Select Type of Report
                    var j;
                    if(fileAlreadyDroppedInContainer == true)
                        {
                           filecount = originalValueFileCount;
                            j = filecount;
                        }
                        else
                        {
                           filecount = 0;
                            j = filecount;
                        }
                    for(var i = 0;i < files.length;i++,j++) 
                        {
                          //  code for datepicker text area in dob of create account page
                            /*$("#ReportDate"+j).datepicker({
                                            inline: true,
                                            nextText: '&rarr;',
                                            prevText: '&larr;',
                                            showOtherMonths: true,
                                            dateFormat: 'mm-dd-yy',
                                            dayNamesMin: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
                                            showOn: "button",
                                            buttonImage: "images/calendar-blue.png",
                                            buttonImageOnly: true,
                                            changeYear: true ,
                                            changeMonth: true,
                                            yearRange: '1900:c',
                                        }); */
                           // $("#ReportDate"+j).attr("type","date");


                           // Changing the width of the progress bar   
                            $("#progressbar"+j).css("width","100%");


                            // Binding the function to the Report Type button adjacent to the Select Type of Report
                            
                            
                            $("#ReportType"+j).on('change',function(){ // changed from input to live

                            var id = $(this).attr('id');
                                
                            console.log(" In ReportType change:"+$(this).attr('id'));

                            reportTypeAssociativeArray[id] = $("#"+id).val(); 
                            });

                        }    
        
                    //Adjusting the value of filecount
                           if(fileAlreadyDroppedInContainer == true)
                            {
                               filecount = originalValueFileCount + files.length;
                            }
                           else
                            {
                               filecount = files.length;
                            }
    
                   // filenameLifepin[filename[filecount]] = [,];
                    
                    fileAlreadyDroppedInContainer = true;
    
}

$(document).ready(function()
{
var obj = $("#dragandrophandler");
obj.on('dragenter', function (e) 
{
    e.stopPropagation();
    e.preventDefault();
    $(this).css('border', '2px solid #0B85A1');
});
obj.on('dragover', function (e) 
{
     e.stopPropagation();
     e.preventDefault();
});
obj.on('drop', function (e) 
{
 
     $(this).css('border', '2px dotted #0B85A1');
     e.preventDefault();
     var files = e.originalEvent.dataTransfer.files;
 
     //We need to send dropped files to Server
     handleFileUpload(files,obj);
    
    
});

$(document).on('dragenter', function (e) 
{
    e.stopPropagation();
    e.preventDefault();
});
$(document).on('dragover', function (e) 
{
  e.stopPropagation();
  e.preventDefault();
  obj.css('border', '2px dotted #0B85A1');
});
$(document).on('drop', function (e) 
{
    e.stopPropagation();
    e.preventDefault();
});
 
});

// End of code for new dropzone hayageek    

    //$('.datepicker').datepicker();
    $('.datepicker').datepicker({
			inline: true,
			nextText: '&rarr;',
			prevText: '&larr;',
			showOtherMonths: true,
			dateFormat: 'mm-dd-yy',
			dayNamesMin: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
			showOn: "button",
			buttonImage: "images/calendar-blue.png",
			buttonImageOnly: true,
            changeYear: true ,
			changeMonth: true,
			yearRange: '1900:c',
		});
    
       
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
			url: '<?php echo $domain?>/ongoing_sessions.php?userid='+<?php echo $doctorId ?>,
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
		// url: ',
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
	});
	

	
	$('body').bind('mousedown keydown', function(event) {
        clearTimeout(timeoutTimer);
        timeoutTimer = setTimeout(ShowTimeOutWarning, timeoutTime);
    });

 
	$('#BotMessages').live('click',function(){
        $('#compose_message').trigger('click');
	});
	
   
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
				 		

// Adding the code from the original quickDropzoneForNonH2MDoc-new.php file to this file

//var filecount = 0;
//var num=0;
//var orig_file_array = new Array();
//var patientId = <?php //echo $patientId ?>;
//var doctorId = <?php //echo $doctorId ?>;
//var filename = new Array();
//var filepointer = new Array();
//var idString = 0;
//var reportType = new Array();
//var reportDate = new Array();
//var gridToBeDeleted;
//var current;
//var value = new Array();


// Earlier code for dropping files using dropzone.js api which is currently done through Hayageek

/*Dropzone.options.ReportsDropzone = {
			//autoProcessQueue: false,	
			//previewTemplate: '<span class="label label-info" style="left:0px; margin-left:30px; font-size:30px;">Patient Creator</span>',
			//maxFilesize:0,
			init: function() 
			{
				
               myDropzone1 = this; 

			this.on("sending", function(file, xhr, formData) {
					//formData.append("repdate", $('#datepicker1').val()); // Append all the additional input data of your form here!
					filepointer[filecount] = file;
                    formData.append("idus",patientId);
					//formData.append("tipo",60);
					formData.append("id",filecount);
					formData.append("doctorId",doctorId);
                    orig_file_array[filecount] = file.name;
                    filename[filecount] = file.name;
                    
                
					
					//alert('sending file');
					//document.getElementById("upload_count_label").innerHTML = upload_count + '/' + filecount;
					myDropzone1.processQueue();
                
                // Below code is called for displaying the grid of processed reports sub section whenever a file is dropped in drop                    area

                
                $("#container").append('<div id="processedReportMetaData" class ="RepRow"            style="display:none;width:430px;height:90px;"><input type="hidden" value="" /><div style="float:left; width:20px; height:89px; background-color:#22aeff;"></div><div style="float:left; border:0px solid #cacaca; width:380px; height:89px;"><div style="float:left; width:50px; height:80px; background-color:white;"><button id="ViewFilePreview" value = "" style="margin-top:15px;margin-left:5px;height:48px;width:48px;font-weight:bold; background-image: url(images/File-icons/48px/pdf.png); background-color: #FFF; border: 0px solid #FFF;"></button> </div><div style="float:left; width:330px; height:70px; background-color:white; padding-top:10px; border:0px solid #cacaca;"><div style="float:left; width:270px; height:70px; background-color:white; border:0px solid #cacaca;"><div style="width:280px; height:25px; border:0px solid #cacaca;"><span style="float:left; width:160px;"> Select Date of Report</span><div style="float:left; width:100px;"><input type="text" class="span2" value="02-16-2012" id="ReportDate" readonly="" style="width:100px; min-height:20px; font-size:12px; "></div></div><div style="width:280px; height:25px; border:0px solid #cacaca; margin-top:5px;"><span style="float:left; width:160px;"> Select Type of Report</span><select id="ReportType" style="width:100px;height:20px;float: left;"><option>Imaging</option><option>Lab Reports</option><option>Summary</option><option>Reports</option> <option>Doctor Notes</option><option>Unspecified</option></select></div></div><div style="float:left; width:50px; height:70px; background-color:white; border:0px solid #cacaca;"><button id = "Close" class="btn btn-danger" style="height: 50px; padding-top: 0px; margin-top: 0px; margin-left:8px;">Del</button></div> </div><div class="progress progress-striped active" style="float:left; width:300px; height:10px; margin-left:5px;"><div id="progressbar" class="progress-bar"  role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 45%"> <span id = "progressbarSpan" class="sr-only">45% Complete</span></div></div></div><div style="float:right; width:20px; height:89px; background-color:#54bc00;"></div></div>');

                $("#container").children('div').each(function(){if($(this).css("display") == 'none'){$(this).slideDown();}});
                
                $("#container").children('#processedReportMetaData').each(function(){
     
                    $(this).children('input').each(function(){
                    
                    $(this).attr("value",filecount);
                    });
                                                   
                    $(this).attr("id", "processedReportMetaData"+filecount);
                    
                    
                    //Changing the id of the progress bar according to each processedReportMetaData
                    $(this).find('#progressbar').each(function(){
                    
                    $(this).attr("id","progressbar"+filecount);
                    });
                    
                    //Changing the id of the ReportDate
                
                   $(this).find(".span2").each(function(){
                   
                       $(this).attr("id","ReportDate"+filecount);
                    });
                    
                    //Changing the id of the ReportType section
                    
                    $(this).find("option:first").each(function(){
                     $(this).closest("select").each(function(){
                        
                         $(this).attr("id","ReportType"+filecount);
                     });
                    });
                    
                    //
                    

                    // Changing the id and value for Preview File button
                    $(this).find('#ViewFilePreview').each(function(){
                       $(this).attr("id", "ViewFilePreview"+filecount);
                        $(this).attr("value",filecount);
                    });
                    
                    //Changing the id for Close button
                    
                    $(this).find("#Close").each(function(){
                        $(this).attr("id", "Close"+filecount);
                        $(this).attr("value",filecount);
                        
                    });
                    
                });
                
                
                
                // Code for  Preview File button
                   $("#ViewFilePreview"+filecount).on('click',function(){
                       
                     current = $(this).attr('value');

                     $("#previewModal").html('<iframe src="http://dev.health2.me/dropzone_uploads/temporaryForFilePreview/'+filename[current]+'" style="width:600px;height:600px; margin-left: -20px;"></iframe>');

                     
                     $("#previewModal").dialog({bigframe: true, width: 600, height: 600, modal: false});

                    });
                
                //Code for Close button
                
                $("#Close"+filecount).on('click',function(){
                    
                    current = $(this).attr('value');

                    gridToBeDeleted = "processedReportMetaData"+current;
                    
                    $(this).closest('#container').find('#'+gridToBeDeleted).each(function(){
                        $(this).remove();
                    });
                    
                    $.get("deleteFileFromLifePin.php?filename="+filename[current],function(data,status){
                    alert(data);    
                    });
                    
                });
    
				//  code for datepicker text area in dob of create account page
                $("#ReportDate"+filecount).datepicker();
                
                
                
              $("#progressbar"+filecount).css("width","100%");
               // $("#progressbarSpan").html("100% uploaded");
                
                
                
                $("#ReportType"+filecount).on('change',function(){
                 
                    var id = $(this).attr('id');
                    
                    
                    reportType.push($("#"+id+" option:selected").text()); 
                });

                
                filecount++;
				});
								

                this.on("error",function(file,errorMessage){


					//alert(file.name + " not uploaded properly");
					failed_uploads++;
				});
						
			}
		}; */
    

    //Code for popping up the modal window for Create Account button
      $("#CreateAccount").on('click',function(){
		$("#createAccount").dialog({bigframe: true, width: 550, height: 300, modal: false});
      }); 

    
      var name,surname,email,password,repeatPassword,date,gender;
      var emailDocToBeAdded = '<?php echo $email ?>';
    
    
      $('input:radio[name=sex]').click(function() {
      gender = $('input:radio[name=sex]:checked').val();
        
      }); // End of code for popping up the modal window of create account button
    
      
       //Code for createAccountNewDoc(the button in the create account modal window) button

        
        $("#createAccountNewDoc").on('click',function(){
        
        name = $("#NameCreateAccountPage").val();
        surname = $("#SurnameCreateAccountPage").val();
        email = $("#EmailCreateAccountPage").val();
        password = $("#PasswordCreateAccountPage").val();
        repeatPassword = $("#RepeatPasswordCreateAccountPage").val();
        date = $("#DOB").val();
        var passwordStatus = 'correct';   
        
        //Checking for validity of the password
        if(password != repeatPassword)
        {
        
           $('<div></div>').dialog({width:300,height:200,modal: true,title: "Password Status",open: function() {
                   var markup = 'Password Incorrect';
                   $(this).html(markup); },
                   buttons: {
                   Ok: function() {
                   $( this ).dialog( "close" );
                       }
                   }   }); 
            passwordStatus = 'incorrect';
        }
        
        //Making ajax call for creating doctor account
        
        if(passwordStatus == 'correct')
        {
            $.get("createAccountNewDoc.php?name="+name+"&surname="+surname+"&email="+email+"&password="+password+"&date="+date+"&gender="+gender+"&emailDoc="+emailDocToBeAdded,function(data,status){

            
            
        });
         
alert('Doctor account created.  To gain full access, contact health2me for details.');
            $("#createAccount").dialog("close");
        }
    }); // End of code for create account button in create account modal window
    
     
     // Defining and declaring to be used further in the below code of FINISH button
     var filenames = new Object();
     var filenameReportDate = new Array();
     var filenameReportType = new Array();
     var reportTypeIds = new Array();
     var idtempo;
    
    
    //Code for Finish button
        $("#Finish").on('click',function(){
           
            var emailUser = '<?php echo $emailUser; ?>';
            var emailDoc ='<?php echo $email; ?>';
            
            filenameReportDate.length = 0;
            filenameReportType.length = 0;
            
            $("#container").children('div').each(function(){
               
                //Extracting the value of number attached to processedReportMetaData id
                var idValue = $(this).attr('id');
                var lenghtOfId = idValue.length;
                var originalLength = 'processedReportMetaData'.length;
                
                var diff = lenghtOfId - originalLength;
                
                var current;
                
                if(diff == 1)
                {
                    current = idValue[lenghtOfId -1];
                }
                
                else
                {
                    current = idValue[lenghtOfId - 2]+idValue[lenghtOfId - 1];
                }
                
                //Extracting the reportdate and reporttype and attaching it to filenameLifepin object
                $(this).find('#ReportDate'+current).each(function(){
                   
                    var dateR = $(this).val();
                    console.log(dateR);
                    filenameReportDate.push(dateR);
                    var index = "ReportType"+current;
                    filenameLifepin[filename[current]] = [dateR,reportTypeAssociativeArray[""+index+""]];
                    
                    
                });
            });
            
           
              filenames = Object.keys(filenameLifepin);
              
              if(Object.keys(reportTypeAssociativeArray).length == 0)
              {
              
                for(var i = 0; i < Object.keys(filenameLifepin).length;i++)
                {
                  filenameReportType.push("UNSPECIFIED");
                }
              }
              else
              {
                reportTypeIds = Object.keys(reportTypeAssociativeArray);
                var i = 0; 
                 for(i = 0; i < Object.keys(reportTypeAssociativeArray).length; i++)
                  {
                      idtempo = "#"+reportTypeIds[i];
                      filenameReportType.push($(""+idtempo+"").val());
                      
                      console.log("File Type:"+$(""+idtempo+"").val());

                  }
              }
        
           /* $.post("sendReturnMail2User.php",{filenames:      filenames,filenameReportDate:filenameReportDate,filenameReportType:filenameReportType,emailUser:emailUser,emailDoc:emailDoc},function(data,status)
        {
    
        }); */
            
        var updateUrl = "sendReturnMail2User.php";
        $.ajax({

             type: 		'GET',
             url: 		updateUrl,
             data: 		{filenames:        filenames,filenameReportDate:filenameReportDate,filenameReportType:filenameReportType,emailUser:emailUser,emailDoc:emailDoc},
             
            dataType: 	"string",
            success: 	function(data){    console.log("ReportDate:"+data);
            },
             async:     false
                });
            
        alert("The files have been uploaded");
      domainRedirect = '<?php echo $domain;?>'; 
      window.location=domainRedirect;
        
    });

        
        
  </script>

    <!--<script src="js/bootstrap.min.js"></script> Removing it from here and placing at the initial of the script-->

   <!-- <script src="js/bootstrap-datepicker.js"></script> -->
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
