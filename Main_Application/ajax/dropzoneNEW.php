<?php
/*KYLE
session_start();


 require("environment_detail.php");
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];

$NombreEnt = 'x';
$PasswordEnt = 'x';

if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 1800)) {
    // last request was more than 30 minutes ago
    session_unset();     // unset $_SESSION variable for the run-time 
    session_destroy();   // destroy session data in storage
    echo "<META http-equiv='refresh' content='0;URL=index.html'>";
}
$_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp

$NombreEnt = $_SESSION['Nombre'];
$PasswordEnt = $_SESSION['Password'];
$Acceso = $_SESSION['Acceso'];
$MEDID = $_SESSION['MEDID'];
$privilege=$_SESSION['Previlege'];

if ($Acceso != '23432')
{
echo "Incorrect credentials for login";
echo "<br>\n"; 	
echo "<h2><a href='".$domain."'>Return to Inmers HomePage</a></h2>";
die;
}


// Connect to server and select databse.
$link = mysql_connect("$dbhost", "$dbuser", "$dbpass")or die("cannot connect");
mysql_select_db("$dbname")or die("cannot select DB");

$result = mysql_query("SELECT * FROM doctors where id='$MEDID'");
$count=mysql_num_rows($result);
$row = mysql_fetch_array($result);
$success ='NO';
if($count==1){
	$success ='SI';
	$MedID = $row['id'];
	$MedUserEmail= $row['IdMEDEmail'];
	$MedUserName = $row['Name'];
	$MedUserSurname = $row['Surname'];
	$MedUserLogo = $row['ImageLogo'];

}
else
{
echo "MEDICAL USER NOT VALID. Incorrect credentials for login";
echo "<br>\n"; 	
echo "<h2><a href='".$domain."'>Return to Inmers HomePage</a></h2>";
die;
}

// Meter tipos en un Array
     $sql="SELECT * FROM tipopin";
     $q = mysql_query($sql);
     
     $Tipo[0]='N/A';
     while($row=mysql_fetch_assoc($q)){
     	$Tipo[$row['Id']]=$row['NombreEng'];
     	$TipoAB[$row['Id']]=$row['NombreCorto'];
     	$TipoColor[$row['Id']]=$row['Color'];
     	$TipoIcon[$row['Id']]=$row['Icon'];
     	
     	$TipoColorGroup[$row['Agrup']]=$row['Color'];
     	$TipoIconGroup[$row['Agrup']]=$row['Icon'];
     }

	 $query = "select * from emr_config where userid = ".$_SESSION['MEDID'];
$result = mysql_query($query);
$row = mysql_fetch_array($result);	 


?>
<!DOCTYPE html>
<html lang="en" style="background: #F9F9F9;"><head>
    <meta charset="utf-8">
    <title>Inmers - Center Management Console</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
	<link href="css/login.css" rel="stylesheet">
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/bootstrap.min.css" rel="stylesheet"> 

<!--	<link rel="stylesheet" href="css/icon/font-awesome.css">-->
    <link rel="stylesheet" href="font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/bootstrap-responsive.css">
	<link rel="stylesheet" type="text/css" href="css/tooltipster.css" />

    <link href="css/style.css" rel="stylesheet">
    <link rel="stylesheet" href="css/basic.css" />
    <link rel="stylesheet" href="css/dropzone.css"/>
    <script src="js/dropzone.min.js"></script>
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
	
	<link href="css/demo_style.css" rel="stylesheet" type="text/css"/>
	<link href="css/smart_wizard.css" rel="stylesheet" type="text/css"/>
	
	
	<link rel="stylesheet" href="css/jquery-ui-autocomplete.css" />
				<script src="js/jquery-1.9.1-autocomplete.js"></script>
	<script src="js/jquery-ui-autocomplete.js"></script>
	<link rel="stylesheet" type="text/css" href="js/uploadify/uploadify.css">
    <script type="text/javascript" src="js/uploadify/jquery.uploadify.min.js"></script> 
   

   

	<!--
	<link rel="stylesheet" href="css/icon/font-awesome.css">
    <link rel="stylesheet" href="css/bootstrap-responsive.css">
    -->
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
  <body style="background: #F9F9F9;" >
<div class="loader_spinner"></div>
     	<input type="hidden" id="MEDID" Value="<?php echo $MedID; ?>">	
    	<input type="hidden" id="IdMEDEmail" Value="<?php echo $MedUserEmail; ?>">	
    	<input type="hidden" id="IdMEDName" Value="<?php echo $MedUserName; ?>">	
    	<input type="hidden" id="IdMEDSurname" Value="<?php echo $MedUserSurname; ?>">	
    	<input type="hidden" id="IdMEDLogo" Value="<?php echo $MedUserLogo; ?>">	
     	<input type="hidden" id="USERDID" Value="<?php echo $USERID; ?>">	
		<input type="hidden" id="patientid" Value="0">
		<input type="hidden" id="patientname" >	

	<!--Header Start-->
	<div class="header" >
    	
           <a href="index.html" class="logo"><h1>health2.me</h1></a>
           
           <div class="pull-right">
           
            
           <!--Button User Start-->
		   <div class="btn-group pull-right" >
           
            <a class="btn btn-profile dropdown-toggle" id="button-profile" data-toggle="dropdown" href="#">
              <span class="name-user"><strong>Welcome</strong> Dr, <?php echo $MedUserName.' '.$MedUserSurname; ?></span> 
             <?php 
             $hash = md5( strtolower( trim( $MedUserEmail ) ) );
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

    <!--Content Start-->
	<div id="content" style="background: #F9F9F9; padding-left:0px;">



	  <!--SpeedBar Start--->
     <div class="speedbar">
     <div class="speedbar-content">
     <ul class="menu-speedbar">
		
    	 <li><a href="MainDashboard.php" class="act_link">Home</a></li>
		 <li><a href="dashboard.php" >Dashboard</a></li>
    	 <li><a href="patients.php" >Patients</a></li>
		 <?php if ($privilege==1)
		 {
				 echo '<li><a href="medicalConnections.php" >Doctor Connections</a></li>';
				 echo '<li><a href="PatientNetwork.php" >Patient Network</a></li>';
		 }
		 ?>
         <li><a href="medicalConfiguration.php">Configuration</a></li>
         <li><a href="logout.php" style="color:yellow;">Sign Out</a></li>
     </ul>

     
     </div>
     </div>
     <!--SpeedBar END-->
     
     <div class="content">
	     <div class="grid" class="grid span4" style="width:850px; height:850px; margin: 0 auto; margin-top:30px; padding-top:10px;">
         <div class="modal-header" style="height:60px;">
             <!--<button class="close" type="button" data-dismiss="modal">×</button>-->
             <div style="width:90%; margin-top:-12px; float:left;">
                 <div id="selpat" style="width: 100px; margin-left:5%; float: left; color: rgb(61, 147, 224);">
                     <div style="font-size: 20px; font-weight: bold; width: 100%;">Step 1</div>
                     <span style="font-size: 12px; width: 100%;">Select Option</span>
                 </div>
                 <div id="seldr" style="width:100px; margin-left:5%; float:left; color:#cacaca;">
                     <div style="font-size:20px; font-weight:bold; width:100%; ">Step 2</div>
                     <span style="font-size:12px; width:100%;">Select Session</span>
                 </div>
                 <div id="att" style="width:100px; margin-left:5%; float:left; color:#cacaca;">
                     <div style="font-size:20px; font-weight:bold; width:100%; ">Step 3</div>
                     <span style="font-size:12px; width:100%;">Select Patient</span>
                 </div>
                 <div id="addcom" style="width:100px; margin-left:5%; float:left; color:#cacaca;">
                     <div style="font-size:20px; font-weight:bold; width:100%;">Step 4</div>
                     <span style="font-size:12px; width:100%;">Review & Classify</span>
                 </div>
                 <!--<i id="attachment_icon" class="icon-paper-clip icon-4x" style="color:#ccc; margin-left:10px;font-size:30px"></i>-->
             </div>
         </div>
         <div  style="height:691px;">
         	 <div id="ContentScroller" style="width:100%; height:691px; overflow: hidden; ">
                 <div id="ScrollerContainer" style="width:3000px; height:691px; ">
					 <div id="Step1" style="float:left; width:850px; height:691px;">
				 		<table style="background-color:white; text-align:center;">
				 		  <tr height="200">
					 		  <td width="200" valign="top"></td>
					 		  <td width="350" valign="middle"><span style="font-size:24px; color:#cacaca;">Select Option</span></td>
					 		  <td width="200" valign="bottom"></td>
				 		  </tr>
				 		  <tr height="230">
					 		  <td width="200" valign="top"></td>
					 		  <td width="350" valign="middle">
						 		  <span id="DropFiles" style="font-size:30px; color:#22aeff; cursor:pointer;">Drop files now</span>
						 		  <div style="width:100%; height:50px;"></div>
						 		  <span id="Sessions" style="font-size:30px; color:#54bc00; cursor:pointer;">Work with Sessions</span>
					 		  </td>
					 		  <td width="200" valign="bottom"></td>
				 		  </tr>
				 		  <tr height="230">
				 		  </tr>
				 		</table>
					 </div>
					 <div id="Step2" style="float:left; width:850px; height:691px;">
				 		<table style="background-color:white; text-align:center;">
				 		  <tr height="200">
					 		  <td width="200" valign="top"></td>
					 		  <td width="350" valign="middle"><span style="font-size:24px; color:#cacaca;">Select Session</span></td>
					 		  <td width="200" valign="bottom"></td>
				 		  </tr>
				 		  <tr height="230">
					 		  <td width="20" valign="top"></td>
					 		  <td width="850" valign="middle" style="text-align:left;">
                                  <?php
                                        $session_query = "SELECT * FROM dropbox_sessions WHERE id=".$MEDID;
                                        $res = mysql_query($session_query);
                                        //$count = mysql_num_rows($res);
                                        while($row = mysql_fetch_row($res, MYSQL_ASSOC))
                                        {
                                            $uploaded = (100.0 * (float)($row["uploaded"] / $row["size"]));
                                            $verified = (100.0 * (float)($row["verified"] / $row["size"]));
                                    ?>
                                            <div class="SessionRow" style="font-size:14px; color:#54bc00; background-color: <?php echo $C0.'0.99'; ?>; cursor:pointer;">
                                            
                                            <!--<input type="button" class="btn btn-success" value="<?php //echo $row['session_name']; ?>" id="SessionSelect" style="margin-left:100px; margin-top:5px;width:300px;">-->
                                             <br/><div style="margin-left: 0px;" id="SessionSelect" value="<?php echo $row['session_name']; ?>"><?php echo $row['session_name']; ?>
                                            <span style="color:#22aeff;">Recorded <?php echo $row['date']; echo ' ('.$MedUserName.' '.$MedUserSurname.')'; ?>
                                            </span><span style="color:grey;"><?php echo $row['size']; ?> reports</span></div>
                                            <div class="progress progress-striped" style="width: 40%; float:left; margin-left:5%;">
                                            <div id="upload-progress-bar-<?php echo $row['session_name'] ?>" class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $uploaded; ?>%;">
                                            <span >Upload <?php echo $uploaded; ?>% Complete</span></div></div>
                                            <div class="progress progress-striped active" style="width: 40%; float:left; margin-left:10%;">
                                            <div id="upload-progress-bar-<?php echo $row['session_name'] ?>" class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $verified; ?>%;">
                                            <span >Verification <?php echo $verified; ?>% Complete</span></div></div></div>
                                <?php } ?>
                                  <!--
						 		  <div class="SessionRow" style="font-size:14px; color:#54bc00; background-color: <?php //echo $C0.'0.99)' ?> cursor:pointer;">
									<input type="button" class="btn btn-success" value="Session" id="SessionDetails" style="margin-left:100px; margin-top:5px;width:300px;">
						 		        Session "name.surname"<span style="color:#22aeff;">Recorded Jan 20,2014 (user usersurname)</span><span style="color:grey;">25 reports</span>
							 		  	<div class="progress progress-striped" style="width: 40%; float:left; margin-left:5%;">
										  <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 100%;">
										    <span >Upload 100% Complete</span>
										  </div>
										</div>
							 		  	<div class="progress progress-striped active" style="width: 40%; float:left; margin-left:10%;">
										  <div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%;">
										    <span >Verification 60% Complete</span>
										  </div>
										</div>
								  </div>
						 		  <div style="width:100%; height:10px;"></div>
						 		  <div class="SessionRow" style="font-size:14px; color:#54bc00; cursor:pointer;">
						 		        Session "name.surname"<span style="color:#22aeff;">Recorded Jan 22,2014 (other surname)</span><span style="color:grey;">47 reports</span>
							 		  	<div class="progress progress-striped active" style="width: 40%; float:left; margin-left:5%;">
										  <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 70%;">
										    <span >Upload 70% Complete</span>
										  </div>
										</div>
							 		  	<div class="progress progress-striped" style="width: 40%; float:left; margin-left:10%;">
										  <div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 0%;">
										    <span style="color:#cacaca;" >Pending Review</span>
										  </div>
										</div>
								  </div>
						 		  <div style="width:100%; height:10px;"></div>
						 		  <div class="SessionRow" style="font-size:14px; color:#54bc00; cursor:pointer;">
						 		        Session "name.surname"<span style="color:#22aeff;">Recorded Jan 25,2014 (other name)</span><span style="color:grey;">10 reports</span>
							 		  	<div class="progress progress-striped" style="width: 40%; float:left; margin-left:5%;">
										  <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 100%;">
										    <span >Upload 100% Complete</span>
										  </div>
										</div>
							 		  	<div class="progress progress-striped active" style="width: 40%; float:left; margin-left:10%;">
										  <div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 0%;">
										    <span style="color:#cacaca;">Pending Review</span>
										  </div>
										</div>
								  </div>-->
					 		  <td width="20" valign="bottom"></td>
				 		  </tr>
				 		  <tr height="230">
				 		  </tr>
				 		</table>
					 </div>
					 <div id="Step3" style="float:left; width:850px; height:691px;">
                         
                         
                         <div id="Step3Header" style="margin-top: 20px; text-align: center;"><span style="font-size:24px; color:#cacaca;">Select Patient</span></div>
                         
                         <!--<div id="myTabContent1" class="tab-content tabs-main-content padding-null" style="margin-top: 30px">-->
                             <ul id="myTab1" class="nav nav-tabs tabs-main" style="width: 765px; margin-left: auto; margin-right: auto; margin-bottom: 20px;">
                                <li class="active" id="DemographicsTab" style="margin-top: 24px; margin-bottom: -24px;"><a href="#TabDemographics" data-toggle="tab">Demographics</a></li>
                                <li id="PersonalTab" style="margin-top: 24px; margin-bottom: -24px;"><a href="#TabPersonal" data-toggle="tab">Personal History</a></li>
                                <li id="PastDXTab" style="margin-top: 24px; margin-bottom: -24px;"><a href="#TabPastDX" data-toggle="tab">Past Diagnostics</a></li>
                                <li id="MedicationTab" style="margin-top: 24px; margin-bottom: -24px;"><a href="#TabMedication" data-toggle="tab">Medication</a></li>
                                <li id="ImmunizationTab" style="margin-top: 24px; margin-bottom: -24px;"><a href="#TabImmunization" data-toggle="tab">Immunization</a></li>
                                <li id="AllergiesTab" style="margin-top: 24px; margin-bottom: -24px;"><a href="#TabAllergies" data-toggle="tab">Allergies</a></li>
                            </ul>
                                
                                <!-- DEMOGRAPHICS -->
										<div class="tab-pane tab-overflow-main fade in active" id="TabDemographics">
											<div style="margin:0px; margin-top:-40px;">
												<div class="row-fluid"  style="">	            
													<div class="grid" style="height:420px;width:765px;margin-left: auto; margin-right: auto; margin-top: 40px;">
														
														<div style="margin-left:20px; margin-top:20px; color: #3d93e0; font-size:20px; font-weight:bold; width:100%; ">Demographics</div>
															<hr>
															<div style="float:left; width:280px; margin:10px; padding:10px; height:500px; margin-top:-10px; margin-left: 60px;">
																<div class="formRow">
																	<input id="fname" name="fname" placeholder="Name" style="border-radius: 4px; padding: 15px; width:200px;" size="30" type="text" />
																	<input id="initial" name="initial" placeholder="Middle Initial" style="border-radius: 4px; padding: 15px; width:200px;" size="30" type="text" />
                                                                    <input id="surname" name="surname" placeholder="Surname" style="border-radius: 4px; padding: 15px; width:200px;" size="30" type="text" />
																	<select name="gender" id="gender" class="validate[required] span_select" style="width:200px;">
																        <option value="">Select Gender:</option>
																        <option value="0">Female</option> 
																        <option value="1">Male</option>
																    </select><br/>
                                                                    <div class="formRow">
                                                                        <label style="color: #595959; font-weight: normal;">Date of Birth: </label>
                                                                    </div>
                                                                    <div class="formRow">
                                                                        <input id="dp32" style="width:200px; border-radius: 4px;" readonly/>
                                                                    </div>
                                                                        
																</div>
									 
																
																
																
																	
																
															</div>
															
															<div style="float:left; width:280px; margin:10px; padding:10px; height:500px; margin-top:-10px">
																
																
																<div class="formRow">
                                                                    <input id="address" name="address" placeholder="Address" style="border-radius: 4px; padding: 15px; width:200px;" size="30" type="text" />
                                                                    <input id="address2" name="address2" placeholder="Address 2" style="border-radius: 4px; padding: 15px; width:200px;" size="30" type="text" />
                                                                    <input id="city" name="city" placeholder="City" style="border-radius: 4px; padding: 15px; width:200px;" size="30" type="text" />
                                                                    <input id="state" name="state" placeholder="State" style="border-radius: 4px; padding: 15px; width:200px;" size="30" type="text" />
                                                                    <input id="country" name="country" placeholder="Country" style="border-radius: 4px; padding: 15px; width:200px;" size="30" type="text" />
                                                                    <div class="formRow">
																	   <label style="color: #595959; font-weight: normal;">Notes:</label>
																		  <textarea rows="3" cols="150" style="width:200px;margin-left:0px;resize:none" id="notes">	</textarea>
																    </div>
																</div>
												
																
																<div>
																	<label for="file" style="color: #595959; font-weight: normal;">Image:</label>
																	<input type="file" name="file_upload" id="file_upload"/>	
																</div>
																<div id="patientImageDiv">
																	<img id="patientImage" style="width:80px; height:80px;"/>
																</div>
															
															</div>
															
															
																						
													</div>
												</div>
											</div>
										</div>
                                
                                <!-- END DEMOGRAPHICS -->
                         
                                <!-- FAMILY -->
                                        <div class="tab-pane" id="TabFamily">		
											<div style="margin:0px; margin-top:-40px;">
												<div class="row-fluid"  style="">	            
													<div class="grid" style="height:420px;width:765px;margin-left: auto; margin-right: auto; margin-top: 40px;">
														
														<div style="margin-left:20px; margin-top:20px; color: #3d93e0; font-size:20px; font-weight:bold; width:100%; ">Family History</div>
															<hr>
															
															
																<div class="formRow">
																	<label style="margin-left:20px;">Father: </label>
																	
																	<div class="formRight" >
																		<input type="checkbox" id="c2" name="cc">
																		<label for="c2"><span></span> Alive </label>
																		
																		<input id="fcod" name="fcod" type="text" class="first-input" placeholder="Cause of Death" style="margin-left:20px; width:200px; border-bottom-right-radius:3px; border-bottom-left-radius:3px;" />
																		<input id="faod" name="faod" type="text" class="first-input" placeholder="Age of Death" style="margin-left:20px; width:85px; border-bottom-right-radius:3px; border-bottom-left-radius:3px;" />
																		<input id="frd" name="frd" type="text" class="first-input" placeholder="Relevant Diseases" style="margin-left:20px; width:200px; border-bottom-right-radius:3px; border-bottom-left-radius:3px;" />
																		
																	</div>
																</div>
																
																<div class="formRow">
																	<label style="margin-left:20px;">Mother: </label>
																	
																	<div class="formRight" >
																		<input type="checkbox" id="c3" name="cc1">
																		<label for="c3"><span></span> Alive </label>
																		
																		<input id="mcod" name="mcod" type="text" class="first-input" placeholder="Cause of Death" style="margin-left:20px; width:200px; border-bottom-right-radius:3px; border-bottom-left-radius:3px;" />
																		<input id="maod" name="maod" type="text" class="first-input" placeholder="Age of Death" style="margin-left:20px; width:85px; border-bottom-right-radius:3px; border-bottom-left-radius:3px;" />
																		<input id="mrd" name="mrd" type="text" class="first-input" placeholder="Relevant Diseases" style="margin-left:20px; width:200px; border-bottom-right-radius:3px; border-bottom-left-radius:3px;" />
																		
																	</div>
																</div>
																
																<div class="formRow">
																	<label style="margin-left:20px;">Siblings: </label>
																	<div class="formRight" >
																		<input id="siblingsRD" name="siblingsRD" type="text" class="first-input" placeholder="Relevant Diseases" style="width:400px; border-bottom-right-radius:3px; border-bottom-left-radius:3px;"/>
																	</div>
																</div>
																
													</div>
												</div>
											</div>
										</div>	
                                <!-- END FAMILY -->
                         
                                <!-- PAST DIAGOSTICS -->
                                        <div class="tab-pane" id="TabPastDX">		
											<div style="margin:0px; margin-top:-40px;">
												<div class="row-fluid"  style="">	            
													<div class="grid" style="height:420px;width:765px;margin-left: auto; margin-right: auto; margin-top: 40px;">
														
														<div style="margin-left:20px; margin-top:20px; color: #3d93e0; font-size:20px; font-weight:bold; width:100%; ">Past Diagnostics</div>
															<hr>
															
																<div id="VacioAUN" style="margin-left:20px;margin-top:10px; border: 1px SOLID #CACACA; float:left; width:90%">
																	<table class="table table-mod" id="PastDX">
																		<thead><tr id="FILA" class="CFILAMODAL"><th style="text-align: center;">DX Name</th><th style="text-align: center;">ICD Code</th><th style="text-align: center;">Date Start</th><th style="text-align: center;">Date Stop</th></tr></thead>
																	</table> 
																</div>
															
																
															
																<div style="float:right; margin-right:20px;margin-top:10px;">
																	<input type="button" class="btn btn-primary" value="Add" onClick="openPastDXPopUp();" >	
																</div>
															
																
													</div>
												</div>
											</div>
										</div>
                         
                                <!-- END PAST DIAGOSTICS -->
                         
                                <!-- MEDICATIONS -->
                         
                                        <div class="tab-pane" id="TabMedication">	
											<div style="margin:0px; margin-top:-40px;">
												<div class="row-fluid"  style="">	            
													<div class="grid" style="height:420px;width:765px;margin-left: auto; margin-right: auto; margin-top: 40px;">
														
														<div style="margin-left:20px; margin-top:20px; color: #3d93e0; font-size:20px; font-weight:bold; width:100%; ">Medication</div>
															<hr>
															
																<div id="VacioAUN" style="margin-left:20px;margin-top:10px; border: 1px SOLID #CACACA; float:left; width:90%">
																	<table class="table table-mod" id="Medication">
																		<thead><tr id="FILA1" class="CFILAMODAL"><th style="text-align: center;">Drug Name</th><th style="text-align: center;">Drug Code</th><th style="text-align: center;">Dossage</th><th style="text-align: center;">Number per Day</th><th style="text-align: center;">Day Start</th><th style="text-align: center;">Day Stop</th></tr></thead>
																	</table> 
																</div>
																
																<div style="float:right; margin-right:20px;margin-top:10px;">
																	<input type="button" class="btn btn-primary" value="Add" onClick="openMedicationPopUp();">	
																</div>
																
													</div>
												</div>
											</div>
										</div>
                         
                                <!-- END MEDICATIONS -->
                         
                                <!-- IMMUNIZATIONS -->
                         
                                        <div class="tab-pane" id="TabImmunization">	
											<div style="margin:0px; margin-top:-40px;">
												<div class="row-fluid"  style="">	            
													<div class="grid" style="height:420px;width:765px;margin-left: auto; margin-right: auto; margin-top: 40px;">
														
														<div style="margin-left:20px; margin-top:20px; color: #3d93e0; font-size:20px; font-weight:bold; width:100%; ">Immunization</div>
															<hr>
															
															
																<div id="VacioAUN" style="  margin-left:20px;margin-top:10px; border: 1px SOLID #CACACA; float:left; width:90%">
																	<table class="table table-mod" id="Immunization">
																		<thead><tr id="FILA" class="CFILAMODAL"><th style="text-align: center;">Name</th><th style="text-align: center;">Age</th><th style="text-align: center;">Date </th><th style="text-align: center;">Reaction</th></tr></thead>
																	</table> 
																</div>
																
																<div style="float:right; margin-right:20px;margin-top:10px;">
																	<input type="button" class="btn btn-primary" value="Add" onClick="openImmunizationPopUp();">	
																</div>
															
																
													</div>
												</div>
											</div>
										</div>
                         
                                <!-- END IMMUNIZATIONS -->
                         
                         
                                <!-- ALLERGIES -->
                         
                                        <div class="tab-pane" id="TabAllergies">	
											<div style="margin:0px; margin-top:-40px;">
												<div class="row-fluid"  style="">	            
													<div class="grid" style="height:420px;width:765px;margin-left: auto; margin-right: auto; margin-top: 40px;">
														
														<div style="margin-left:20px; margin-top:20px; color: #3d93e0; font-size:20px; font-weight:bold; width:100%; ">Allergies</div>
															<hr>
															
															
																<div id="VacioAUN" style="  margin-left:20px;margin-top:10px; border: 1px SOLID #CACACA; float:left; width:90%">
																	<table class="table table-mod" id="Allergies">
																		<thead><tr id="FILA" class="CFILAMODAL"><th style="text-align: center;">Allergy Name</th><th style="text-align: center;">Type (Food, Drug, Pollen, etc)</th><th style="text-align: center;">Date Recorded </th><th style="text-align: center;">Description (Severity)</th></tr></thead>
																	</table> 
																</div>
																
																<div style="float:right; margin-right:20px;margin-top:10px;">
																	<input type="button" class="btn btn-primary" value="Add" onClick="openAllergyPopUp();">	
																</div>
															
																
													</div>
												</div>
											</div>
										</div>
                         
                                 <!-- END ALLERGIES -->
                         
                                </div>
                        <!--</div>-->
                         
                         
                         
                         
                         
				 		<table style="background-color:white; text-align:center; table-layout: fixed; width: 850px;">
				 		  <tbody>
				 		  <tr height="200">
					 		  <td width="200" valign="top"></td>
					 		  <td width="350" valign="middle"><span style="font-size:24px; color:#cacaca;">Select Patient</span></td>
					 		  <td width="200" valign="bottom"></td>
				 		  </tr>
				 		  <tr height="200">
					 		  <td width="900px" valign="top"></td>
					 		  <td width="900px" valign="middle">
						 		  <!--<span id="DropFiles" style="font-size:30px; color:#22aeff; cursor:pointer;"></span>
						 		  <div style="width:100%; height:50px;"></div>
						 		  <span id="Sessions" style="font-size:30px; color:#54bc00; cursor:pointer;"></span>-->
                                  <div id="PatientSelect">
                                  <?php
                                        $session_query = "SELECT * FROM usuarios WHERE idCreator=".$MEDID;
                                        $res = mysql_query($session_query);
                                        //$count = mysql_num_rows($res);
                                        $r_count = 0;
                                        while($row = mysql_fetch_row($res, MYSQL_ASSOC))
                                        {
                                            $r_count += 1;
                                    ?>
                                            <input type="button" class="btn btn-success" value="<?php echo $row['Name'].' '.$row['Surname']; ?>" id="PatientSelect<?php echo $r_count; ?>" style="margin-left:0px; margin-top:5px;width:300px;">
                                            <script type="text/javascript">$("#PatientSelect<?php echo $r_count ?>").data("patient_id", <?php echo $row["IdUsFIXED"]; ?> );</script>
                                      <?php
                                            
                                        } ?>
                                </div>    
					 		  </td>
					 		  <td width="200" valign="bottom"></td>
				 		  </tr>
				 		  <tr height="230">
				 		  </tr>
				 		  </tbody>
				 		</table>
					 </div>
					 <div id="Step4" style="float:left; width:850px; height:691px;">
				 		<table style="background-color:white; text-align:center;">
				 		  <tr height="10">
					 		  <td width="200" valign="top"></td>
					 		  <td width="350" valign="top"><span style="font-size:24px; color:#cacaca;">Review & Classify</span></td>
					 		  <td width="200" valign="bottom"></td>
				 		  </tr>
				 		  <tr height="60">
					 		  <td width="10" valign="top"></td>
					 		  <td width="730" valign="top"><span style="font-size:12px; color:#cacaca;">Thumbnails</span></td>
					 		  <td width="10" valign="top"></td>
				 		  </tr>
				 		  <tr height="550">
					 		  <td width="50" valign="middle">
						 		  <img class="ImgArrow" width="50" src="images/arrowleft2.png" />
					 		  </td>
					 		  <td width="650" valign="middle" style="background-color:#fffff0;">
						 		  <span id="DropFiles" style="font-size:30px; color:#22aeff; cursor:pointer;">CONTENT</span>
						 		  <div style="width:100%; height:50px;"></div>
						 		  <span id="Sessions" style="font-size:30px; color:#54bc00; cursor:pointer;">CONTENT</span>
					 		  </td>
					 		  <td width="50" valign="middle" >
						 		  <img class="ImgArrow" width="50" src="images/arrowright2.png" />
					 		  </td>
				 		  </tr>
				 		  <style>
				 		     div.TypeIcon{
					 			 color: #cacaca;
					 			 }
				 		     div.TypeIcon:hover{
					 			 color: grey;
					 			 }
	 
				 		  </style>
				 		  <tr height="60">
					 		  <td width="10" valign="top" style="width:10px;"></td>
					 		  <td width="730" valign="top">
						 		  <div class="TypeIcon" style="width:70px; height:60px; float:left;">
							 		  <i class="icon-list-alt icon-2x" ></i>
							 		  <div style="width:100%;"></div>
							 		  <span style="font-size:12px;">Demographics</span>
						 		  </div>
						 		  <div class="TypeIcon" style="width:70px; height:60px; float:left;">
							 		  <i class="icon-user-md icon-2x" ></i>
							 		  <div style="width:100%;"></div>
							 		  <span style="font-size:12px;">Dr. Notes</span>
						 		  </div>
						 		  <div class="TypeIcon" style="width:70px; height:60px; float:left;">
							 		  <i class="icon-beaker icon-2x" ></i>
							 		  <div style="width:100%;"></div>
							 		  <span style="font-size:12px;">Laboratory</span>
						 		  </div>
						 		  <div class="TypeIcon" style="width:70px; height:60px; float:left;">
							 		  <i class="icon-picture icon-2x" ></i>
							 		  <div style="width:100%;"></div>
							 		  <span style="font-size:12px;">Imaging</span>
						 		  </div>
						 		  <div class="TypeIcon" style="width:70px; height:60px; float:left;">
							 		  <i class="icon-user icon-2x" ></i>
							 		  <div style="width:100%;"></div>
							 		  <span style="font-size:12px;">Pat. Notes</span>
						 		  </div>
						 		  <div class="TypeIcon" style="width:70px; height:60px; float:left;">
							 		  <i class="icon-film icon-2x" ></i>
							 		  <div style="width:100%;"></div>
							 		  <span style="font-size:12px;">Pictures</span>
						 		  </div>
						 		  <div class="TypeIcon" style="width:70px; height:60px; float:left;">
							 		  <i class="icon-dollar icon-2x"></i>
							 		  <div style="width:100%;"></div>
							 		  <span style="font-size:12px;">Superbill</span>
						 		  </div>
						 		  <div class="TypeIcon" style="width:70px; height:60px; float:left;">
							 		  <i class="icon-list-alt icon-2x"></i>
							 		  <div style="width:100%;"></div>
							 		  <span style="font-size:12px;">Summary</span>
						 		  </div>
						 		  <div class="TypeIcon" style="width:70px; height:60px; float:left;">
							 		  <i class="icon-inbox icon-2x"></i>
							 		  <div style="width:100%;"></div>
							 		  <span style="font-size:12px;">Other</span>
						 		  </div>
					 		  </td>
					 		  <td width="10" valign="top"></td>
				 		  </tr>
				 		</table>
					 </div>
                 </div>
         	 </div>    
         </div>
         <div class="modal-footer" style="height:72px;">
	         <div style="height:10px; width:100%:">
                    <p id="TextoSend" style="text-align:center; margin-top:-10px; ">
                        <span style="color:grey;">Messages </span>
                        <span style="color:#54bc00; font-size:30px;">      </span>
                        <span style="color:grey;"> here </span>
                        <span style="color:#22aeff; font-size:30px;">     </span>
                    </p>
             </div>
	         <div style="height:20px; width:100%:">
                 <input type="button" class="btn btn-success" value="SEND" id="SendButton" style="width:100px; display:none;">
                 <input type="button" class="btn btn-success" value="NEXT" id="Attach" style="visibility: hidden;">
                 <a href="#" class="btn btn-danger" data-dismiss="modal" id="CloseModal" >Cancel</a>
                 <input type="button" class="btn btn-info" value="Previous" id="PhasePrev">
                 <input type="button" class="btn btn-success" value="NEXT" id="PhaseNext" style="width:100px;">
                 
             </div> 
         </div>


	     </div>
     </div>
     	 
     <!--CONTENT MAIN START-->
    </div>
    <!--Content END-->
   <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    
	
	<script src="js/jquery.min.js"></script>
    <script src="js/jquery-ui.min.js"></script>
	
    <script type="text/javascript" >
	var $j = jQuery.noConflict();
	
	
	
	
	var filelist = new Array();
	var datelist = new Array();
	var filecount=0;
	var upload_count =0;
	var num=0;
	var orig_file_array = new Array();
	var idpin_array = new Array();
	var files = new Array();
	var types=new Array();
	var curr_file=0;
	var pats = new Array();
	var availablePatientTags = new Array();
	var rep_date = new Array();
	var last_press;
	var last_step;
	var verified=false;
	var existing=true;
	
	var timeoutTime = 18000000;
	//var timeoutTime = 300000;  //5minutes
var timeoutTimer = setTimeout(ShowTimeOutWarning, timeoutTime);


var active_session_timer = 60000; //1minute
var sessionTimer = setTimeout(inform_about_session, active_session_timer);

//This function is called at regular intervals and it updates ongoing_sessions lastseen time
function inform_about_session()
{
	$j.ajax({
		url: '<?php echo $domain?>/ongoing_sessions.php?userid='+$('#MEDID').val(),
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
        
//this function repeatedly updates the progress bars of the sessions
/*
setInterval(function()
{
    $.get(
}, 5000);
*/
	/*KYLE
	 function getpatients(serviceURL) 
	{
		$j.ajax(
		{
			url: serviceURL,
			dataType: "json",
			async: false,
			success: function(data)
			{
				//alert('Data Fetched');
				patients = data.items;
			}
		});
	}   
	
	function renameFile(serviceURL,oldName,newName) 
		{
			$j.ajax(
			{
				url: serviceURL+'?oldName='+oldName+'&newName='+newName,
				dataType: "json",
				async: false,
				success: function(data)
				{
					//alert('Data Fetched');
					//patients = data.items;
				}
			});
		}   
	function getImageDimension(serviceURL) 
		{
			$j.ajax(
			{
				url: serviceURL,
				dataType: "json",
				async: false,
				success: function(data)
				{
					//alert('Data Fetched');
					//patients = data.items;
					//alert(data);
				}
			});
		}   
	window.onbeforeunload = confirmExit;
	function confirmExit()
	{
	
		switch(last_step)
		{
			case 1: if(upload_count!=filecount && filecount!=0)
					{
						return "Some of the reports are getting uploaded. Are you sure you want to exit this page?";
					}
					else if(upload_count==filecount && filecount!=0)
					{
						return "Uploaded Reports have not been verified yet. Are you sure you want to exit this page?";
					}
					break;
			case 2: if(curr_file !=idpin_array.length)
					{
						return "Some reports have not been verified. Are you sure you want to exit this page?";
					}
					else if(verified==false)
					{
						return "Changes have not been saved yet. Are you sure you want to exit this page?";
					}
					break;
		}
		
		
	}
		
		$j(window).load(function() {
		//alert("started");
		$j(".loader_spinner").fadeOut("slow");
		})
	$j(document).ready(function() {

	       var ancho=850;
		   var position=0;
		   var ActualPhase=0;
		   var lockNext=0;
		   var lockPrev=0;
        
            var selected_session = "";
		   
	       $("#PhasePrev").click(function(){
	       		if (lockPrev!=0) MovePhase(0);
	       });
	       $("#PhaseNext").click(function(){
	       		if (lockNext!=0) MovePhase(1);
	       });
	       $("#Sessions").click(function(){
	       		MovePhase(1);
	       		lockPrev = 1;
	       });
	       $("#DropFiles").click(function(){
	       		MovePhase(1);
	       });
	       $("#SessionSelect").click(function(event){
		   
                MovePhase(1);
                lockNext = 1;
                selected_session = $(this).attr('value');
                //$("#PatientSelect").attr("value",selected_session);
	       });
            $("#PatientSelect > input").click(function(event){
                  // add code here
                $.post("dropbox_sessions_update_patient.php",{name:$(this).attr("value"),session:selected_session,id:$(this).data("patient_id")},function(data,status){});
                MovePhase(1);
                lockNext = 1;
	       });
	       $(".TypeIcon").click(function(){
	           $(".TypeIcon").css('color','#cacaca');
			   $(this).css('color','#22aeff');
	       });

		   function MovePhase(direction){
			   if (direction=='1'){
				   if (position < (ancho*3)){
			       		position = position + ancho;
			       		ActualPhase++;
				   		}
			       $("#ContentScroller").animate({ scrollLeft: position}, 175);
			   }else{
					if (position >0){
			       		position = position - ancho;
			       		ActualPhase--;
				   		}
		       		$("#ContentScroller").animate({ scrollLeft: position}, 175);
			   }
			   $('#selpat').css('color','#cacaca');
			   $('#seldr').css('color','#cacaca');
			   $('#att').css('color','#cacaca');
			   $('#addcom').css('color','#cacaca');
			   if (ActualPhase >-1){
				   $('#selpat').css('color','#22aeff');
			   }
			   if (ActualPhase > 0){
				   $('#seldr').css('color','#22aeff');
			   }
			   if (ActualPhase > 1){
				   $('#att').css('color','#22aeff');
			   }
			   if (ActualPhase > 2){
				   $('#addcom').css('color','#22aeff');
			   }
			   
		   }
            $("#DemographicsTab").click(function(){
                $("#DemographicsTab").attr("class", "active");
                $("#PersonalTab").attr("class", "");
                $("#PastDXTab").attr("class", "");
                $("#MedicationTab").attr("class", "");
                $("#ImmunizationTab").attr("class", "");
                $("#AllergiesTab").attr("class", "");
                $("#TabDemographics").show();
                $("#TabPersonal").hide();
                $("#TabPastDX").hide();
                $("#TabMedication").hide();
                $("#TabImmunization").hide();
                $("#TabAllergies").hide();
                
	       });
            $("#PersonalTab").click(function(){
                $("#DemographicsTab").attr("class", "");
                $("#PersonalTab").attr("class", "active");
                $("#PastDXTab").attr("class", "");
                $("#MedicationTab").attr("class", "");
                $("#ImmunizationTab").attr("class", "");
                $("#AllergiesTab").attr("class", "");
                $("#TabDemographics").hide();
                $("#TabPersonal").show();
                $("#TabPastDX").hide();
                $("#TabMedication").hide();
                $("#TabImmunization").hide();
                $("#TabAllergies").hide();
	       });
            $("#PastDXTab").click(function(){
                $("#DemographicsTab").attr("class", "");
                $("#PersonalTab").attr("class", "");
                $("#PastDXTab").attr("class", "active");
                $("#MedicationTab").attr("class", "");
                $("#ImmunizationTab").attr("class", "");
                $("#AllergiesTab").attr("class", "");
                $("#TabDemographics").hide();
                $("#TabPersonal").hide();
                $("#TabPastDX").show();
                $("#TabMedication").hide();
                $("#TabImmunization").hide();
                $("#TabAllergies").hide();
	       });
            $("#MedicationTab").click(function(){
                $("#DemographicsTab").attr("class", "");
                $("#PersonalTab").attr("class", "");
                $("#PastDXTab").attr("class", "");
                $("#MedicationTab").attr("class", "active");
                $("#ImmunizationTab").attr("class", "");
                $("#AllergiesTab").attr("class", "");
                $("#TabDemographics").hide();
                $("#TabPersonal").hide();
                $("#TabPastDX").hide();
                $("#TabMedication").show();
                $("#TabImmunization").hide();
                $("#TabAllergies").hide();
	       });
            $("#ImmunizationTab").click(function(){
                $("#DemographicsTab").attr("class", "");
                $("#PersonalTab").attr("class", "");
                $("#PastDXTab").attr("class", "");
                $("#MedicationTab").attr("class", "");
                $("#ImmunizationTab").attr("class", "active");
                $("#AllergiesTab").attr("class", "");
                $("#TabDemographics").hide();
                $("#TabPersonal").hide();
                $("#TabPastDX").hide();
                $("#TabMedication").hide();
                $("#TabImmunization").show();
                $("#TabAllergies").hide();
	       });
            $("#AllergiesTab").click(function(){
                $("#DemographicsTab").attr("class", "");
                $("#PersonalTab").attr("class", "");
                $("#PastDXTab").attr("class", "");
                $("#MedicationTab").attr("class", "");
                $("#ImmunizationTab").attr("class", "");
                $("#AllergiesTab").attr("class", "active");
                $("#TabDemographics").hide();
                $("#TabPersonal").hide();
                $("#TabPastDX").hide();
                $("#TabMedication").hide();
                $("#TabImmunization").hide();
                $("#TabAllergies").show();
	       });
	
	
		$('#patientImageDiv').hide();
		<?php $timestamp = time();?>
		//$("#file_upload-button").attr('class','btn btn-primary');
		$j(function() {
    $('#file_upload').uploadify({
		'method'   : 'post',
		'formData'     : {
					'timestamp' : '<?php echo $timestamp;?>',
					'token'     : '<?php echo md5('unique_salt' . $timestamp);?>'
				},
        'swf'      : 'js/uploadify/uploadify.swf',
        'uploader' : 'uploadify.php',
		'multi'    :  false,
        'onUploadSuccess' : function(file, data, response) {
		var split = data.split('|');
        //alert('The file was saved to: ' + split[0]);
		if(split[1]=="1")
		{
			alert("Kindly upload image of minimum dimensions 70X70");
			return;	
		}
		$('#patientImageDiv').show();
		if(split[0]=="fileError")
		{
			alert("Please select a file belonging to one of the following types: jpeg,gif or png");
			$('#patientImage').attr('src','PatientImage/defaultDP.jpg');
			return;
		}
		else
		$('#patientImage').attr('src',split[0]);
    }
    });
	}); 
		$j('body').bind('mousedown keydown', function(event) {
        clearTimeout(timeoutTimer);
        timeoutTimer = setTimeout(ShowTimeOutWarning, timeoutTime);
		});
		
		verified=false;
		document.getElementById("upload_count_label").innerHTML = upload_count + '/' + filecount;
		$j('#wizard').hide();
		$j('#wizard').smartWizard({transitionEffect:'slideleft',onLeaveStep:leaveAStepCallback,onFinish:onFinishCallback,onShowStep:showstepcallback,enableFinishButton:true});
		//alert('loaded');
		setTimeout(function(){$j('#wizard').show();},100);
		
		function showstepcallback(obj)
		{
			//alert('here');
			var step_num = obj.attr('rel');
			if(parseInt(step_num) < parseInt(last_step))
			{
				//alert('inside');
				$j("#wizard").smartWizard('goToStep', last_step);
				//goToStep(last_step);
				return false;
			}
			return true;
		}
		
		function leaveAStepCallback(obj)
		{
			var step_num = obj.attr('rel');
			
			switch(parseInt(step_num))
			{
				case 1:	
						var patient_id;
					var patient_name = $j('#tags').val();
					if(existing==true){
					if(patient_name=="")
					{
						patient_id=0;
					}
					else
					{
						var index = pats.indexOf(patient_name);
						if(index==-1)
						{
							patient_id=0;
						}
						else
						{
							patient_id = index;
						}
					}
					$j('#patientid').val(patient_id);
					$j('#patientname').val(patient_name);
					}
				  	   if(parseInt($('#patientid').val())==0)
					   {
						   alert('Please select/create a patient');
						   return false;
					   }
						last_step=1;			
						return true;
						break;
				case 2: 
						if(idpin_array.length==0)
						{
							alert('Please upload some files');
							return false;
						}
						else if(filecount != upload_count)
						{
							alert('Please wait while we upload all files');
							return false;
						}
						else
						{
							done_uploading();
						}
						last_step=2;
						return true;
						break;
				case 3: last_step=3;
						return false;
						break;
			
			
			}
			
		
			
			return true;
		}

		$(document).keydown(function(e){
			if (e.keyCode == 37) { 
			   //alert( "left pressed" );
			   previous_click();
			}
			else if(e.keyCode == 39)
			{
				//alert( "right pressed" );
				next_click();
			}
			return true;
		});

		
      function onFinishCallback()
	  {
		for(var i=0;i<idpin_array.length;i++)
		{
			var idpin = parseInt(idpin_array[i]);
			if(rep_date[idpin]=='')
			{
				alert('You have not verified all reports. Please verify all reports before clicking finishing');
				return;
			}
	   
		}
	   
		for(var i=0;i<idpin_array.length;i++)
		{
			var idpin = parseInt(idpin_array[i]);
			var type = types[idpin];
			var rdate = rep_date[idpin];
			var url = 'update_date.php?idpin='+idpin+'&tipo='+type+'&fecha='+rdate+'&user='+$('#patientid').val();
			var resp = LanzaAjax(url);
		 
		}
		//alert('All your changes have been saved');
		verified=true;
		
				
		// Section for sending TrayMessage to referral (if existing)
		patient_id = $j('#patientid').val();
		doctor_id = $j('#MEDID').val();
		//alert ('Doctor: '+doctor_id+' , Patient: '+patient_id);		
		var url = 'CheckReferral.php?patient_id='+patient_id+'&doctor_id='+doctor_id;
		// Mini-parser of Rectipo to extract multiple values
	    RecTipo = LanzaAjax (url);
		//alert (RecTipo);	
		var n = RecTipo.indexOf(",");
		var IdDoctorAdd = RecTipo.substr(0,n);
	    var Remaining = RecTipo.substr(n+1,RecTipo.length);
	    m = Remaining.indexOf(",");
		var NameDoctor = Remaining.substr(0,m);
	    var NamePatient = Remaining.substr(m+1,Remaining.length);
	    //throw "stop execution";
		//alert ('Id =  '+IdDoctorAdd+'  NameDr=  '+NameDoctor+' NAmePAtient= '+NamePatient);
		
		if (IdDoctorAdd > 0)
		{
			
			var url = 'MessageTray.php?patient_id='+patient_id+'&doctor_id='+doctor_id+'&Add_doctor_id='+IdDoctorAdd+'&MType=1&TMessage=&IconText=icon-folder-open-alt&SubText=NEW REPORT ('+NamePatient+')&MainText=By '+NameDoctor+'&ColorText=22aeff&LinkText=1115';
			
			//alert (url);
			RecTipo = LanzaAjax (url);
			//alert (RecTipo);
		}	
		//alert (RecTipo);	



		window.location.replace("<?php echo $domain;?>/dashboard.php");
      }
		
		
		
		
		
		
		
		
		
	/*	
		getpatients('getuserpatients.php');
		//alert(<?php echo $_SESSION['MEDID'];?>);
	
	
		for(var i=0;i<patients.length;i++)
		{
			availablePatientTags[i]=patients[i].idusfixedname;
			pats[patients[i].identif] = patients[i].idusfixedname;;
		}
	*/
			/*$j( "#tags" ).autocomplete({
				source: availablePatientTags,
				change: function( event, ui ) {
					//alert($('#tags').val());
					
					var patient_id;
					var patient_name = $j('#tags').val();
					if(patient_name=="")
					{
						patient_id=0;
					}
					else
					{
						var index = pats.indexOf(patient_name);
						if(index==-1)
						{
							patient_id=0;
						}
						else
						{
							patient_id = index;
						}
					}
					$j('#patientid').val(patient_id);
					$j('#patientname').val(patient_name);
					//alert('Set patient id to '+ $j('#patientid').val() );
				}
			});
  */

	/*KYLE
	});	
		
		Dropzone.options.myAwesomeDropzone1 = {
			//autoProcessQueue: false,	
			//previewTemplate: '<span class="label label-info" style="left:0px; margin-left:30px; font-size:30px;">Patient Creator</span>',
			
			init: function() 
			{
				myDropzone1 = this; 
				this.on("addedfile", function(file) {
					num=1;
					if($('#patientid').val() == 0)
					{
						myDropzone1.removeFile(file);
						alert('Please Select/Create a patient');
						return;
					}
					//alert('File dropped on 1' + file.name);
					$j('#filename').val(file.name);
					$j('#datepicker1').val('');
					//$('#BotonModal').trigger('click');
					
					
				});
				
				this.on("sending", function(file, xhr, formData) {
					//formData.append("repdate", $j('#datepicker1').val()); // Append all the additional input data of your form here!
					formData.append("idus",$('#patientid').val());
					formData.append("tipo",60);
					formData.append("id",filecount);
					orig_file_array[filecount] = file.name;
					filecount++;
					//alert('sending file');
					document.getElementById("upload_count_label").innerHTML = upload_count + '/' + filecount;
					myDropzone1.processQueue();
					
				});
				
				this.on("success",function(file,resp){
					//alert(resp);
					upload_count++;
					document.getElementById("upload_count_label").innerHTML = upload_count + '/' + filecount;
					var str = resp.split(";");
					//alert(str[0] + '  ' + str[1] + '  ' + str[2] + '  ' + str[3]);
					idpin_array[str[0]] = str[1];
					files[str[1]] = str[2];
					types[str[1]] = str[3];
					//alert('file sent'+ str[2]);
					//var contenURL =   '<?php echo $domain ;?>/temp/<?php echo $_SESSION['MEDID'] ;?>/Packages_Encrypted/'+str[2];
					//var conten =  '<iframe id="ImagenN" style="border:1px solid #666CCC" title="PDF" src="'+contenURL+'" frameborder="1" scrolling="auto" height="850" width="600" ></iframe>';
					//$('#AreaConten').html(conten);
					
					
				});
				
						
			}
		};
		
		Dropzone.options.myAwesomeDropzone2 = {
			//autoProcessQueue: false,	
			init: function() 
			{
				myDropzone2 = this; 
				this.on("addedfile", function(file) {
					num=2;
					if($('#patientid').val() == 0)
					{
						myDropzone2.removeFile(file);
						alert('Please Select/Create a patient');
						 
						return;
					}
					//alert('file dropped on 2');
					$j('#filename').val(file.name);
					$j('#datepicker1').val('');
					//$('#BotonModal').trigger('click');
					
				});
				
				this.on("sending", function(file, xhr, formData) {
					//formData.append("repdate", $j('#datepicker1').val()); // Append all the additional input data of your form here!
					formData.append("idus",$('#patientid').val());
					formData.append("tipo",30);
					formData.append("id",filecount);
					orig_file_array[filecount] = file.name;
					filecount++;
					document.getElementById("upload_count_label").innerHTML = upload_count + '/' + filecount;
					myDropzone2.processQueue();
					
				});
				
				this.on("success",function(file,resp){
					//alert(resp);
					upload_count++;
					document.getElementById("upload_count_label").innerHTML = upload_count + '/' + filecount;
					var str = resp.split(";");
					//alert(str[0] + '  ' + str[1] + '  ' + str[2] + '  ' + str[3]);
					idpin_array[str[0]] = str[1];
					files[str[1]] = str[2];
					types[str[1]] = str[3];
				});
						
			}
		};
		
		Dropzone.options.myAwesomeDropzone3 = {
			//autoProcessQueue: false,	
			init: function() 
			{
				myDropzone3 = this; 
				this.on("addedfile", function(file) {
					num=3;
					if($('#patientid').val() == 0)
					{
						myDropzone3.removeFile(file);
						alert('Please Select/Create a patient');
						return;
					}
					//alert('file dropped on 3');
					$j('#filename').val(file.name);
					$j('#datepicker1').val('');
					//$('#BotonModal').trigger('click');
					
				});
				
				this.on("sending", function(file, xhr, formData) {
					//formData.append("repdate", $j('#datepicker1').val()); // Append all the additional input data of your form here!
					formData.append("idus",$('#patientid').val());
					formData.append("tipo",20);
					formData.append("id",filecount);
					orig_file_array[filecount] = file.name;
					filecount++;
					document.getElementById("upload_count_label").innerHTML = upload_count + '/' + filecount;
					myDropzone3.processQueue();
					
				});
				
				this.on("success",function(file,resp){
					upload_count++;
					document.getElementById("upload_count_label").innerHTML = upload_count + '/' + filecount;
					//alert(resp);
					var str = resp.split(";");
					//alert(str[0] + '  ' + str[1] + '  ' + str[2] + '  ' + str[3]);
					idpin_array[str[0]] = str[1];
					files[str[1]] = str[2];
					types[str[1]] = str[3];
				});
				
						
			}
		};
		
		Dropzone.options.myAwesomeDropzone4 = {
			//autoProcessQueue: false,	
			init: function() 
			{
				myDropzone4 = this; 
				this.on("addedfile", function(file) {
					num=4;
					if($('#patientid').val() == 0)
					{
						myDropzone4.removeFile(file);
						alert('Please Select/Create a patient');
						return;
					}
					//alert('File dropped on 4' + file.name);
					$j('#filename').val(file.name);
					$j('#datepicker1').val('');
					//$('#BotonModal').trigger('click');
					
				});
				
				this.on("sending", function(file, xhr, formData) {
					//formData.append("repdate", $j('#datepicker1').val()); // Append all the additional input data of your form here!
					formData.append("idus",$('#patientid').val());
					formData.append("tipo",1);
					formData.append("id",filecount);
					orig_file_array[filecount] = file.name;
					filecount++;
					document.getElementById("upload_count_label").innerHTML = upload_count + '/' + filecount;
					myDropzone4.processQueue();
					
				});
				
				this.on("success",function(file,resp){
					//alert(resp);
					upload_count++;
					document.getElementById("upload_count_label").innerHTML = upload_count + '/' + filecount;
					var str = resp.split(";");
					//alert(str[0] + '  ' + str[1] + '  ' + str[2] + '  ' + str[3]);
					idpin_array[str[0]] = str[1];
					files[str[1]] = str[2];
					types[str[1]] = str[3];
				});						
			}
		};
		
		Dropzone.options.myAwesomeDropzone5 = {
			//autoProcessQueue: false,	
			init: function() 
			{
				myDropzone5 = this; 
				this.on("addedfile", function(file) {
					num=5;
					if($('#patientid').val() == 0)
					{
						myDropzone5.removeFile(file);
						alert('Please Select/Create a patient');
						return;
					}
					//alert('File dropped on 5' + file.name);
					$j('#filename').val(file.name);
					$j('#datepicker1').val('');
					//$('#BotonModal').trigger('click');
					
				});
				
				this.on("sending", function(file, xhr, formData) {
				
					//formData.append("repdate", $j('#datepicker1').val()); // Append all the additional input data of your form here!
					formData.append("idus",$('#patientid').val());
					formData.append("tipo",76);
					formData.append("id",filecount);
					orig_file_array[filecount] = file.name;
					filecount++;
					document.getElementById("upload_count_label").innerHTML = upload_count + '/' + filecount;
				myDropzone5.processQueue();
					
				});
				
				this.on("success",function(file,resp){
					//alert(resp);
					upload_count++;
					document.getElementById("upload_count_label").innerHTML = upload_count + '/' + filecount;
					var str = resp.split(";");
					//alert(str[0] + '  ' + str[1] + '  ' + str[2] + '  ' + str[3]);
					idpin_array[str[0]] = str[1];
					files[str[1]] = str[2];
					types[str[1]] = str[3];
				});
				
				/*this.on("success",function(file,resp){
					alert(resp);
				});*/
				
						/*KYLE
			 }
		 };		
		
		Dropzone.options.myAwesomeDropzone6 = {
			//autoProcessQueue: false,	
			init: function() 
			{
				myDropzone6 = this; 
				this.on("addedfile", function(file) {
					num=6;
					if($('#patientid').val() == 0)
					{
						myDropzone6.removeFile(file);
						alert('Please Select/Create a patient');
						return;
					}
					//alert('File dropped on 6' + file.name);
					$j('#filename').val(file.name);
					$j('#datepicker1').val('');
					//$('#BotonModal').trigger('click');
					
				});
				
				this.on("sending", function(file, xhr, formData) {
					//formData.append("repdate", $j('#datepicker1').val()); // Append all the additional input data of your form here!
					formData.append("idus",$('#patientid').val());
					formData.append("tipo",74);
					formData.append("id",filecount);
					orig_file_array[filecount] = file.name;
					filecount++;
					document.getElementById("upload_count_label").innerHTML = upload_count + '/' + filecount;
					myDropzone6.processQueue();
					
				});
				
				this.on("success",function(file,resp){
					//alert(resp);
					upload_count++;
					document.getElementById("upload_count_label").innerHTML = upload_count + '/' + filecount;
					var str = resp.split(";");
					//alert(str[0] + '  ' + str[1] + '  ' + str[2] + '  ' + str[3]);
					idpin_array[str[0]] = str[1];
					files[str[1]] = str[2];
					types[str[1]] = str[3];
				});
				/*
				this.on("success",function(file,resp){
					alert(resp);
				});
				*/
			/*KYLE			
			}
		};
		
		Dropzone.options.myAwesomeDropzone7 = {
			//autoProcessQueue: false,	
			init: function() 
			{
				myDropzone7 = this; 
				this.on("addedfile", function(file) {
					num=7;
					if($('#patientid').val() == 0)
					{
						myDropzone7.removeFile(file);
						alert('Please Select/Create a patient');
						return;
					}
					//alert('file dropped on 7');
					$j('#filename').val(file.name);
					$j('#datepicker1').val('');
					//$('#BotonModal').trigger('click');
					
				});
				
				this.on("sending", function(file, xhr, formData) {
					//formData.append("repdate", $j('#datepicker1').val()); // Append all the additional input data of your form here!
					formData.append("idus",$('#patientid').val());
					formData.append("tipo",77);
					formData.append("id",filecount);
					orig_file_array[filecount] = file.name;
					filecount++;
					document.getElementById("upload_count_label").innerHTML = upload_count + '/' + filecount;
					myDropzone7.processQueue();
					
				});
				
				this.on("success",function(file,resp){
					//alert(resp);
					upload_count++;
					document.getElementById("upload_count_label").innerHTML = upload_count + '/' + filecount;
					var str = resp.split(";");
					//alert(str[0] + '  ' + str[1] + '  ' + str[2] + '  ' + str[3]);
					idpin_array[str[0]] = str[1];
					files[str[1]] = str[2];
					types[str[1]] = str[3];
				});
				
						
			}
		};
		
		Dropzone.options.myAwesomeDropzone8 = {
			//autoProcessQueue: false,	
			init: function() 
			{
				myDropzone8 = this; 
				this.on("addedfile", function(file) {
					num=8;
					if($('#patientid').val() == 0)
					{
						myDropzone8.removeFile(file);
						alert('Please Select/Create a patient');
						return;
					}
					//alert('file dropped on 8');
					$j('#filename').val(file.name);
					$j('#datepicker1').val('');
					//$('#BotonModal').trigger('click');
					
				});
				
				this.on("sending", function(file, xhr, formData) {
					//formData.append("repdate", $j('#datepicker1').val()); // Append all the additional input data of your form here!
					formData.append("idus",$('#patientid').val());
					formData.append("tipo",60);
					formData.append("id",filecount);
					orig_file_array[filecount] = file.name;
					filecount++;
					document.getElementById("upload_count_label").innerHTML = upload_count + '/' + filecount;
					myDropzone8.processQueue();
					
					
				});
				
				this.on("success",function(file,resp){
					//alert(resp);
					upload_count++;
					document.getElementById("upload_count_label").innerHTML = upload_count + '/' + filecount;
					var str = resp.split(";");
					//alert(str[0] + '  ' + str[1] + '  ' + str[2] + '  ' + str[3]);
					idpin_array[str[0]] = str[1];
					files[str[1]] = str[2];
					types[str[1]] = str[3];
				});
				
						
			}
		};
		
		Dropzone.options.myAwesomeDropzone9 = {
			//autoProcessQueue: false,	
			init: function() 
			{
				myDropzone9 = this; 
				this.on("addedfile", function(file) {
					num=9;
					if($('#patientid').val() == 0)
					{
						myDropzone9.removeFile(file);
						alert('Please Select/Create a patient');
						return;
					}
					//alert('file dropped on 9');
					$j('#filename').val(file.name);
					$j('#datepicker1').val('');
					//$('#BotonModal').trigger('click');
					
				});
				
				this.on("sending", function(file, xhr, formData) {
					//formData.append("repdate", $j('#datepicker1').val()); // Append all the additional input data of your form here!
					formData.append("idus",$('#patientid').val());
					formData.append("tipo",70);
					formData.append("id",filecount);
					orig_file_array[filecount] = file.name;
					filecount++;
					document.getElementById("upload_count_label").innerHTML = upload_count + '/' + filecount;
					myDropzone9.processQueue();
					
				});
				
				this.on("success",function(file,resp){
					//alert(resp);
					upload_count++;
					document.getElementById("upload_count_label").innerHTML = upload_count + '/' + filecount;
					var str = resp.split(";");
					//alert(str[0] + '  ' + str[1] + '  ' + str[2] + '  ' + str[3]);
					idpin_array[str[0]] = str[1];
					files[str[1]] = str[2];
					types[str[1]] = str[3];
				});
				
						
			}
		};
		
		
		
		
		
		
		
		$j("#ConfirmaLink").live('click',function()
		{
			//filelist[filecount]= $j('#filename').val();
			//datelist[filecount]= $j('#datepicker1').val();
			//filecount++;
			$j('#CloseModal').trigger('click');
			switch(num)
			{
				case 1: myDropzone1.processQueue();
						break;
				case 2: myDropzone2.processQueue();
						break;
				case 3: myDropzone3.processQueue();
						break;
				case 4: //alert('calling 4');
						//alert(myDropzone4.getQueuedFiles().length);
						myDropzone4.processQueue();
						break;
				case 5: //alert(myDropzone5.getQueuedFiles().length);
						myDropzone5.processQueue();
						break;
				case 6: myDropzone6.processQueue();
						break;
				case 7: myDropzone7.processQueue();
						break;
				case 8: myDropzone8.processQueue();
						break;
				case 9: myDropzone9.processQueue();
						break;
			}
			
			
		});
		
		$j("#datepicker1" ).datepicker();
		$j("#datepicker2" ).datepicker({
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
		
		/*	
		$j("#addPatient").live('click',function() 
		{
		
			//alert("clicked me");
			var fname = $j('#fname').val();
			var sname = $j('#sname').val();
			var initial = $j('#initial').val();
			var year = $j('#Year').val();
			var month = $j('#Month').val();
			var day = $j('#Day').val();
			
			var e = document.getElementById("Gender");
			var gender = parseInt(e.options[e.selectedIndex].value);
			
			if(fname.length==0)
			{
				alert("Enter First Name");
				return;
			}
			
			if(sname.length==0)
			{
				alert("Enter Surname");
				return;
			}
				
			var isnum = /^\d+$/.test(year);
			if(year.length!=4 || isnum==false)
			{
				alert("Enter Valid 4 digit year. eg : 1998");
				return;
			}
			
			var isnum1 = /^\d+$/.test(month);
			if(month.length==0 || month.length>2 || isnum1==false)
			{
				
				alert("Enter valid month : eg : 05");
				return;
			}
			else if(month.length==1)
			{
				month='0'+month;
			}
			
			if(parseInt(month)<0 || parseInt(month)>12)
			{
				alert("Invalid Month");
				return;
			}
			
			var isnum2 = /^\d+$/.test(day);
			if(day.length==0 || day.length>2 || isnum2==false)
			{
				
				alert("Enter valid day : eg : 07");
				return;
			}
			else if(day.length==1)
			{
				day='0'+day;
			}
			
			if(parseInt(day)<0 || parseInt(day)>31)
			{
				alert("Invalid Day");
				return;
			}
			
			
			
			var idusfixedname = fname.toLowerCase()+'.'+sname.toLowerCase();
			var idusfixed = year+month+day+gender+'1';
			
			var DirURL = 'dropzone_create_patient.php?idcreator=<?php echo $_SESSION['MEDID'];?>&idusfixed='+idusfixed+'&idusfixedname='+idusfixedname+'&name='+fname+'&surname='+sname+'&initial='+initial;
			console.log(DirURL);
			
		  $j.ajax({
           url: DirURL,
		   type: 'POST',
          // dataType: "html",
		  processData: false,
		  contentType:false,
		   //data: dataFile,
           async: false,
           complete: function(){ //alert('Completed');
                    },
           success: function(data) {
		   
				if(data == 'failure')
				{
					alert('Patient Already Exists');
					return;
				}
		   
				alert('here');
				var resp = ""
                    if (typeof data == "string") {
                                RecTipo = data;
								resp = RecTipo;
								//alert(resp);
                                }
				$j('#patientid').val(resp);
				$j('#patientname').val(idusfixedname);
				var oldName=<?php echo $_SESSION['MEDID'];?>+'.jpg';
				var newName=resp+'.jpg';
				renameFile('renameFile.php',oldName,newName);
				var url = 'create_emr_data.php?idp='+ resp + '&dob='+$j('#dp32').val() +'&gender='+gender + '&address='+$j('#address').val() + '&address2='+ $j('#address2').val() + '&city='+ $j('#city').val() + '&state='+ $j('#state').val() + '&country='+ $j('#country').val() + '&notes='+ $j('#notes').val() + '&fractures='+ $j('#fractures').val() + '&surgeries='+ $j('#surgeries').val() +  '&otherknown='+ $j('#otherknown').val() + '&obstetric='+ $j('#obstetric').val() + '&other='+ $j('#other').val() + '&fatheralive='+ father_alive + '&fcod='+ $j('#fcod').val() + '&faod='+ $j('#faod').val() + '&frd='+ $j('#frd').val() + '&motheralive='+ mother_alive + '&mcod='+ $j('#mcod').val() + '&maod='+ $j('#maod').val() + '&mrd='+ $j('#mrd').val() + '&srd=' + $j('#siblingsRD').val(); 
				resp = LanzaAjax(url);
				add_PastDX($j('#patientid').val());
				add_medication($j('#patientid').val());
				add_immunization($j('#patientid').val());
				add_allergy($j('#patientid').val());
				var url = 'h2pdf.php?id='+$j('#patientid').val();
				resp = LanzaAjax(url);
				alert('Patient created successfully.');
				existing=false;
				$j(".buttonNext").trigger('click');
               }
            });
		});
		
	*/
	/*KYLE
		$j('#fname').blur(function() {
			$j('#patientid').val(0);
		});
		
		$j('#sname').blur(function() {
			$j('#patientid').val(0);
		});
		
		$j('#Year').blur(function() {
			$j('#patientid').val(0);
		});
		
		$j('#Month').blur(function() {
			$j('#patientid').val(0);
		});
		
		$j('#Day').blur(function() {
			$j('#patientid').val(0);
		});
		
		$j("#BotonBusquedaSents").click(function(event) {
		 //alert('clicked');
 	     var IdMed = $j('#MEDID').val();
	     var UserDOB = '';
		 var UserInput = $j('#SearchUserUSERFIXED').val();
	     //alert(IdMed + '   ' + UserInput);
		 var queUrl ='getSearchUsers.php?Usuario='+UserInput+'&UserDOB='+UserDOB+'&IdDoc='+IdMed+'&NReports=2';
		 //var queUrl ='getSents.php?Doctor='+IdMed+'&DrEmail='+UserDOB+'&NReports=3';
    	 $j('#TablaSents').load(queUrl);
    	 $j('#TablaSents').trigger('update');
		 
    });       



        function LanzaAjax (DirURL)
		{
		var RecTipo = 'SIN MODIFICACIÓN';
	    $j.ajax(
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
		function done_uploading()
		{
			
			//curr_file=0;
			last_press='next';
			for(var i=0;i<idpin_array.length;i++)
			{
				rep_date[idpin_array[i]]='';
			}
			
			curr_file=0;	
			$j('#next').trigger('click');
			$j('#patient_name').disabled=true;
			//$('#BotonModal1').trigger('click');
			
		}
		
		
		function previous_click()
		{
			//alert(curr_file);
			if(curr_file==1 || curr_file==0 )
			{
				curr_file=0;
			}
			else
			{
				if(last_press == 'next')
				{
					curr_file = curr_file-2;
				}
				else
				{
					curr_file--;
				}
			}
			
			//alert('set to '+curr_file);
			var report_type = document.getElementById("reptype");
			
			var idpin = idpin_array[curr_file];
			var file_name = files[idpin];
			var type = types[idpin];
			$j('#idpin').val(idpin);
			$j('#datepicker2').val(convertDateFormat1(rep_date[idpin]));
			$j('#patient_name').val($('#patientname').val());
			
		
			var options = report_type.options;
			
			for (var i = 0;i < options.length; i++) 
			{
				//alert('Comparing ' + options[i].value + ' and ' + type );
				if (parseInt(options[i].value) == parseInt(type)) 
				{
					//alert('selecting '+ options[i].value);
					report_type.selectedIndex = i;
            
				}
			}
			
		
			//alert(idpin + '  ' + type);
			
			
			/*var ext = file_name.split('.')[1];
			
			if(ext=='jpg')
			{
				var contenURL =   '<?php echo $domain ;?>/temp/<?php echo $_SESSION['MEDID'] ;?>/Packages_Encrypted/'+file_name;
				var conten =  '<img id="ImageN" style="border:1px solid #666CCC; margin:0 auto; display:block;max-height:1500px;max-width:600px;"  src="'+contenURL+'" alt="">';
			}
			else
			{*//*KYLE
				var contenURL =   '<?php echo $domain ;?>/temp/<?php echo $_SESSION['MEDID'] ;?>/Packages_Encrypted/'+file_name;
				var conten =  '<iframe id="ImagenN" style="border:1px solid #666CCC" title="PDF" src="'+contenURL+'" alt="Loading" frameborder="1" scrolling="auto" height="850" width="600" ></iframe>';
			//}
			$j('#AreaConten').html(conten);			
			
			last_press='previous';
			document.getElementById("verified_count_label").innerHTML = curr_file+1 + '/' + idpin_array.length;
		}
		
		
		function next_click()
		{
			
			if(curr_file!=0)
			{
				
				if($('#datepicker2').val()=='')
				{
					alert('You did not select a date');
					return;
				}
		
			}
				
			if(last_press=='previous')
			{
				curr_file++;
			}
			
			if(curr_file == idpin_array.length)
			{
				if(curr_file==0)
				{
					alert('You have not uploaded any files');
					return;
				}
				alert('You have finished verifying all the files..Click Finish to Save your changes..');
				return;
			}
			
			
			var report_type = document.getElementById("reptype");
			
			
			//$('#datepicker2').val('');
			
			
			var idpin = idpin_array[curr_file];
			var file_name = files[idpin];
			var type = types[idpin];
			$j('#idpin').val(idpin);
			
			
			if(rep_date[idpin] == '')
			{
				if(curr_file != 0)
				{
					var prev_idpin = idpin_array[curr_file-1];
					$j('#datepicker2').val(convertDateFormat1(rep_date[prev_idpin]));
					$j('#datepicker2').trigger('change');
					
					//rep_date[curr_file] = convertDateFormat1(rep_date[prev_idpin]);
					//alert('Prev_ID = ' + prev_idpin + '  '+rep_date[prev_idpin]);
				}
			}
			else
			{
				$j('#datepicker2').val(convertDateFormat1(rep_date[idpin]));
			}
			
			
			
			
			$j('#patient_name').val($('#patientname').val());
						
			var options = report_type.options;
			
			for (var i = 0;i < options.length; i++) 
			{
				//alert('Comparing ' + options[i].value + ' and ' + type );
				if (parseInt(options[i].value) == parseInt(type)) 
				{
					//alert('selecting '+ options[i].value);
					report_type.selectedIndex = i;
            
				}
			}
			
		
			//alert(idpin + '  ' + type);
			var contenURL =   '<?php echo $domain ;?>/temp/<?php echo $_SESSION['MEDID'] ;?>/Packages_Encrypted/'+file_name;
			var conten =  '<iframe id="ImagenN" style="border:1px solid #666CCC" title="PDF" src="'+contenURL+'" alt="Loading" frameborder="1" scrolling="auto" height="850" width="600" ></iframe>';
			$j('#AreaConten').html(conten);			
			curr_file++;
			last_press='next';
			document.getElementById("verified_count_label").innerHTML = curr_file + '/' + idpin_array.length;
		}
		
		$j('#reptype').change(function() {
			var idpin = parseInt($('#idpin').val());
			var report_type = document.getElementById("reptype");
			types[idpin] = report_type.options[report_type.selectedIndex].value;
			//alert('changed '+ idpin+'  '+types[idpin]);
		
		});
		
		$j('#gender').change(function() {
			var e = document.getElementById("gender");
			var gender = parseInt(e.options[e.selectedIndex].value);
			if(gender==1)
			{
				var elem = document.getElementById("obstetric");
				elem.value = 'N/A';
				elem.disabled=true;
				
			}
			else
			{
				var elem = document.getElementById("obstetric");
				elem.value = '';
				elem.disabled=false;
			}
			
		
		});
		
		$j('#datepicker2').change(function() {
				
			var idpin = $j('#idpin').val();
			rep_date[idpin] = convertDateFormat($j('#datepicker2').val());
			//alert('set ' + idpin + '   ' +rep_date[idpin]);
		});
		
		$j('#dp32').datepicker({
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
		
		$j('#DXStartDate').datepicker({
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
		
		$j('#DXEndDate').datepicker({
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
		
		$j('#MedicationStartDate').datepicker({
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
		
		
		
		$j('#MedicationEndDate').datepicker({
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
		
		$j('#IDate').datepicker({
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
		
		$j('#ADate').datepicker({
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
			
        $j('#CP_Date').datepicker({
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
        
		function openPastDXPopUp()
		{
			$j('#DXName').val('');
			$j('#icdcode').val('');
			$j('#DXStartDate').val('');
			$j('#DXEndDate').val('');
			$j('#BotonPastDX').trigger('click');
		}
		
		
		$j('#UpdatePastDX').live('click',function()
		{
			var table = document.getElementById('PastDX');
            var rowCount = table.rows.length;
			//alert(rowCount);
			 
			 var row = table.insertRow(rowCount);
			 
			 var cell1 = row.insertCell(0);
             cell1.innerHTML = '<center>'+$('#DXName').val()+'</center>';
			 
			 var cell2 = row.insertCell(1);
             cell2.innerHTML = '<center>'+$('#icdcode').val()+'</center>';
			 
			 var cell3 = row.insertCell(2);
             cell3.innerHTML = '<center>'+$('#DXStartDate').val() + '</center>';
			 
			 
			 var cell4 = row.insertCell(3);
             cell4.innerHTML = '<center>'+$('#DXEndDate').val()+'</center>';
			 
			//alert('Clicked Update PastDX');
		
		});
		
		function openMedicationPopUp()
		{
			$j('#DrugName').val('');
			$j('#DrugCode').val('');
			$j('#Dossage').val('');
			$j('#NumPerDay').val('');
			$j('#MedicationStartDate').val('');
			$j('#MedicatioEndDate').val('');
			$j('#BotonMedication').trigger('click');
		}
		
		
		$j('#UpdateMedication').live('click',function()
		{
			var table = document.getElementById('Medication');
            var rowCount = table.rows.length;
			//alert(rowCount);
			 
			 var row = table.insertRow(rowCount);
			 
			 var cell1 = row.insertCell(0);
             cell1.innerHTML = '<center>'+$('#DrugName').val()+'</center>';
			 
			 var cell2 = row.insertCell(1);
             cell2.innerHTML = '<center>'+$('#DrugCode').val()+'</center>';
			 
			 var cell3 = row.insertCell(2);
             cell3.innerHTML = '<center>'+$('#Dossage').val()+'</center>';
			 
			 var cell4 = row.insertCell(3);
             cell4.innerHTML = '<center>'+$('#NumPerDay').val()+'</center>';
			 
			 var cell5 = row.insertCell(4);
             cell5.innerHTML = '<center>'+$('#MedicationStartDate').val() + '</center>';
			 			 
			 var cell6 = row.insertCell(5);
             cell6.innerHTML = '<center>'+$('#MedicationEndDate').val()+'</center>';
		
		
			//alert('Clicked Update Medication');
		
		});
		
		function openImmunizationPopUp()
		{
			$j('#IName').val('');
			$j('#IDate').val('');
			$j('#IAge').val('');
			$j('#IReaction').val('');
			$j('#BotonImmunization').trigger('click');
		}
		
		
		$j('#UpdateImmunization').live('click',function()
		{
			var table = document.getElementById('Immunization');
            var rowCount = table.rows.length;
			//alert(rowCount);
			 
			 var row = table.insertRow(rowCount);
			 
			 var cell1 = row.insertCell(0);
             cell1.innerHTML = '<center>'+$('#IName').val()+'</center>';
			 
			 var cell2 = row.insertCell(1);
             cell2.innerHTML = '<center>'+$('#IDate').val()+'</center>';
			 
			 var cell3 = row.insertCell(2);
             cell3.innerHTML = '<center>'+$('#IAge').val() + '</center>';
			 
			 
			 var cell4 = row.insertCell(3);
             cell4.innerHTML = '<center>'+$('#IReaction').val()+'</center>';
			//alert('Clicked Update Immunization');
		
		});
		
		$j("#c2").click(function(event) {
	    	var cosa=chkb($("#c2").is(':checked'));
			if(cosa==1)
			{
				//alert('Father is alive');
				
				var element = document.getElementById('fcod');
				element.value = 'N/A';
				element.disabled=true;
				
				element = document.getElementById('faod');
				element.value = '';
				element.disabled=true;
			}
			else
			{
				//alert('Father is dead');
				var element = document.getElementById('fcod');
				element.value = '';
				element.disabled=false;
				
				element = document.getElementById('faod');
				element.value = '';
				element.disabled=false;
			}
		
		});
		
		$j("#c3").click(function(event) {
	    	var cosa=chkb($("#c3").is(':checked'));
			if(cosa==1)
			{
				//alert('Mother is alive');
				
				var element = document.getElementById('mcod');
				element.value = 'N/A';
				element.disabled=true;
				
				element = document.getElementById('maod');
				element.value = '';
				element.disabled=true;
			}
			else
			{
				//alert('Father is dead');
				var element = document.getElementById('mcod');
				element.value = '';
				element.disabled=false;
				
				element = document.getElementById('maod');
				element.value = '';
				element.disabled=false;
			}
		
		});
		
		function chkb(bool){
	    if(bool)
	    	return 1;
	    	return 0;
	   }
		
		function openAllergyPopUp()
		{
			$j('#AName').val('');
			$j('#AType').val('');
			$j('#ADate').val('');
			$j('#Description').val('');
			$j('#BotonAllergy').trigger('click');
		}
		
		
		$j('#UpdateAllergy').live('click',function()
		{
			var table = document.getElementById('Allergies');
            var rowCount = table.rows.length;
			//alert(rowCount);
			 
			 var row = table.insertRow(rowCount);
			 
			 var cell1 = row.insertCell(0);
             cell1.innerHTML = '<center>'+$('#AName').val()+'</center>';
			 
			 var cell2 = row.insertCell(1);
             cell2.innerHTML = '<center>'+$('#AType').val()+'</center>';
			 
			 var cell3 = row.insertCell(2);
             cell3.innerHTML = '<center>'+$('#ADate').val() + '</center>';
			 
			 
			 var cell4 = row.insertCell(3);
             cell4.innerHTML = '<center>'+$('#Description').val()+'</center>';
		
			//alert('Clicked Update Allergy');
		
		});
		
		// var _URL = window.URL || window.webkitURL;
		
		// $((document.getElementById('file'))[0].files[0]).change(function (e) {
			// var file, img;
			// if ((file = this.files[0])) {
				// img = new Image();
				// img.onload = function () {
					// alert(this.width + " " + this.height);
				// };
				// img.src = _URL.createObjectURL(file);
			// }
		// });
		
		
        function openCPPopUp()
		{
			$j('#CP_Date').val('');
			$j('#CP_Height').val('');
			$j('#CP_Weight').val('');
			$j('#CP_hbp').val('');
			$j('#CP_lbp').val('');
			update_flag = false;
			$j('#BotonCP').trigger('click');
		}
        
        $j('#UpdateCP').live('click',function()
		{
            var table = document.getElementById('CP');
            var rowCount = table.rows.length;
            
            var row = table.insertRow(rowCount);
            
            var cell1 = row.insertCell(0);
            cell1.innerHTML = '<center>'+$("#CP_Date").val()+'</center>';
            
            var cell2 = row.insertCell(1);
            cell2.innerHTML = '<center>'+$("#CP_Height").val()+'</center>';
            
            var cell3 = row.insertCell(2);
            cell3.innerHTML = '<center>'+$("#CP_Weight").val()+'</center>';
            
            var cell4 = row.insertCell(3);
            cell4.innerHTML = '<center>'+$("#CP_hbp").val()+'</center>';
            
            var cell5 = row.insertCell(4);
            cell5.innerHTML = '<center>'+$("#CP_lbp").val()+'</center>';
		
		});
        
		function convertDateFormat(input)
		{
			//Input : Date in mm-dd-yy Format
			//Output: Date in yy-mm-dd Format
			var str = input.split('-');
			return str[2] + '-' + str[0] + '-' + str[1];
		}
		
		function convertDateFormat1(input)
		{
			//Input : Date in yy-mm-dd Format
			//Output: Date in mm-dd-yy Format
			var str = input.split('-');
			return str[1] + '-' + str[2] + '-' + str[0];
		}
		
		function createPatient()
		{
					//alert("clicked me");
			var fname = $j('#fname').val();
			var sname = $j('#surname').val();
			var initial = $j('#initial').val();
			
			/*
			//Code Added for Changing Personal
			var height = $j('#height').val();
			var weight = $j('#weight').val();
			var hbp = $j('#hbp').val();
			var lbp = $j('#lbp').val();
			
			if(height.length==0)
			{
				alert('Enter Height');
				return;
			}
			if(weight.length==0)
			{
				alert('Enter Weight');
				return;
			}
			if(hbp.length==0)
			{
				alert('Enter High Blood Pressure');
				return;
			}
			if(lbp.length==0)
			{
				alert('Enter Low Blood Pressure');
				return;
			}
			//---------------------------------------------
			*/
			
			//alert($('#dp32').val());
			
			var n = $j('#dp32').val().split('-');
			
			var year = n[0];
			var month = n[1];
			var day = n[2];
			
			var e = document.getElementById("gender");
			var gender = parseInt(e.options[e.selectedIndex].value);
			
			if(fname.length==0)
			{
				alert("Enter First Name");
				return;
			}
			
			if(sname.length==0)
			{
				alert("Enter Surname");
				return;
			}
				
			
			
			var mother = document.getElementById('c3');
			var mother_alive = false;
			if(mother.checked)
			{
				mother_alive=true;
				
			}
			
			var father_alive = false;
			var father = document.getElementById('c2');
			if(father.checked)
			{
				father_alive = true;
			}
			//var resp = 1187;
			
			
			
			var idusfixedname = fname.toLowerCase()+'.'+sname.toLowerCase();
			var idusfixed = year+month+day+gender+'1';
			//alert(fname + ' ' +sname + ' ' + year+month+day+gender + '  ' + idusfixedname + '  ' + idusfixed);
			// var initial = $j('#initial').val();
			// var img = new Image();
			// img.src = $j('#file').val();
			//alert(img.src);
			//or however you get a handle to the IMG
			 // var width = img.clientWidth;
			 // var height = img.clientHeight;
			/*if(width<150 || height<150)
			{
			 alert("Please select image of minimum size 150 X 150.");
			 //alert(width);
			 //alert(height);
			 return;
			}*/
		// var dataFile=new FormData();
			// var file = document.getElementById('file');
			// var img;
			// if ((file = $j(file)[0].files[0])) {
				// img = new Image();
				// img.onload = function () {
					// alert(this.width + " " + this.height);
				// };
				// img.src = _URL.createObjectURL(file);
			// }
            // dataFile.append('file',$(file)[0].files[0]);
			//getImageDimension('getImageDimension.php');
		
		//alert(convertDateFormat($j('#dp32').val()));
		//return;
		
		/*KYLE
		var DirURL = 'dropzone_create_patient.php?idcreator=<?php echo $_SESSION['MEDID'];?>&idusfixed='+idusfixed+'&idusfixedname='+idusfixedname+'&name='+fname+'&surname='+sname+'&initial='+initial;
			//var DirURL = 'checkFileUploaded.php';
			 //alert(DirURL);
		  $j.ajax({
           url: DirURL,
		   type: 'POST',
          // dataType: "html",
		  processData: false,
		  contentType:false,
		   // data: dataFile,
           async: false,
           complete: function(){ //alert('Completed');
                    },
           success: function(data) {
				//alert('Recieved Data :' + data);
				if(data == 'failure')
				{
					alert('User Already Exists');
					return;
				}
		   
		   
				var resp = ""
                    if (typeof data == "string") {
                                RecTipo = data;
								resp = RecTipo;
								//alert('resp=' + resp);
                                }
				$j('#patientid').val(resp);
				$j('#patientname').val(idusfixedname);
				var oldName=<?php echo $_SESSION['MEDID'];?>+'.jpg';
				var newName=resp+'.jpg';
				renameFile('renameFile.php',oldName,newName);
				var url = 'create_emr_data.php?idp='+ resp + '&dob='+convertDateFormat($j('#dp32').val()) +'&gender='+gender + '&address='+$j('#address').val() + '&address2='+ $j('#address2').val() + '&city='+ $j('#city').val() + '&state='+ $j('#state').val() + '&country='+ $j('#country').val() + '&notes='+ $j('#notes').val() + '&fractures='+ $j('#fractures').val() + '&surgeries='+ $j('#surgeries').val() +  '&otherknown='+ $j('#otherknown').val() + '&obstetric='+ $j('#obstetric').val() + '&other='+ $j('#other').val() + '&fatheralive='+ father_alive + '&fcod='+ $j('#fcod').val() + '&faod='+ $j('#faod').val() + '&frd='+ $j('#frd').val() + '&motheralive='+ mother_alive + '&mcod='+ $j('#mcod').val() + '&maod='+ $j('#maod').val() + '&mrd='+ $j('#mrd').val() + '&srd=' + $j('#siblingsRD').val(); 
				resp = LanzaAjax(url);
				//alert(resp);
                add_changing_personal_history($j('#patientid').val());
				add_PastDX($j('#patientid').val());
				add_medication($j('#patientid').val());
				add_immunization($j('#patientid').val());
				add_allergy($j('#patientid').val());
				var url = 'h2pdf.php?id='+$j('#patientid').val();
				resp = LanzaAjax(url);
				//alert(resp);
				alert('Patient created successfully.');
				existing=false;
				$j(".buttonNext")[0].click()	;
               }
            })
		}
		
		
		//Added this code for changing personal history
		function add_changing_personal_history(idp)
		{
            var table = document.getElementById('CP');
            var rowCount = table.rows.length;
            
            for(var i=1; i<rowCount; i++)
			{
				var row = table.rows[i];
				var param  = '&height=' + row.cells[1].innerText + '&weight=' + row.cells[2].innerText+'&hbp=' + row.cells[3].innerText + '&lbp=' + row.cells[4].innerText + '&daterec=' + convertDateFormat(row.cells[0].innerText);
				var url = 'create_changing_personal_history.php?idp='+idp+param;
			var resp = LanzaAjax(url);
			}
		}
		
		
		function add_PastDX(idp)
		{
			var table = document.getElementById('PastDX');
            var rowCount = table.rows.length;
 
            for(var i=1; i<rowCount; i++)
			{
				var row = table.rows[i];
				var param = '&dxname=' + row.cells[0].innerText + '&icdcode=' + row.cells[1].innerText + '&dxstart=' + convertDateFormat(row.cells[2].innerText) + '&dxend=' + convertDateFormat(row.cells[3].innerText)   ;
				var url = 'create_pastdx_Entry.php?idp='+idp+param;
				//alert(url);
				var resp = LanzaAjax(url);
			}
		}
		
		
		
		function add_medication(idp)
		{
			var table = document.getElementById('Medication');
            var rowCount = table.rows.length;
 
            for(var i=1; i<rowCount; i++)
			{
				var row = table.rows[i];
				var param = '&drugname=' + row.cells[0].innerText + '&drugcode=' + row.cells[1].innerText + '&dossage=' + row.cells[2].innerText + '&numperday=' + row.cells[3].innerText + '&start=' + convertDateFormat(row.cells[4].innerText)  + '&end=' + convertDateFormat(row.cells[5].innerText) ;
				var url = 'create_medication_Entry.php?idp='+idp+param;
				//alert(url);
				var resp = LanzaAjax(url);
			}
		}
		
		function add_immunization(idp)
		{
			var table = document.getElementById('Immunization');
            var rowCount = table.rows.length;
 
            for(var i=1; i<rowCount; i++)
			{
				var row = table.rows[i];
				var param = '&name=' + row.cells[0].innerText + '&date=' + convertDateFormat(row.cells[1].innerText) + '&age=' + row.cells[2].innerText + '&reaction=' + row.cells[3].innerText ;
				var url = 'create_immunization_Entry.php?idp='+idp+param;
				//alert(url);
				var resp = LanzaAjax(url);
			}
		}
		
		function add_allergy(idp)
		{
			var table = document.getElementById('Allergies');
            var rowCount = table.rows.length;
 
            for(var i=1; i<rowCount; i++)
			{
				var row = table.rows[i];
				var param = '&name=' + row.cells[0].innerText + '&type=' + row.cells[1].innerText + '&date=' + convertDateFormat(row.cells[2].innerText) + '&description=' + row.cells[3].innerText ;
				var url = 'create_allergy_Entry.php?idp='+idp+param;
				//alert(url);
				var resp = LanzaAjax(url);
			}
		}
		
		function displayalertnotification(message){
  
			var gritterOptions = {
               title: 'status',
               text: message,
               sticky: false,
               time: '3000'
              };
			$j.gritter.add(gritterOptions);
    
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
    <script src="js/morris.js"></script>
    
	<script src="js/application.js"></script>
	<script type="text/javascript" src="js/jquery.tooltipster.js"></script>

	<script src="realtime-notifications/lib/gritter/js/jquery.gritter.min.js"></script>
	<link href="realtime-notifications/lib/gritter/css/jquery.gritter.css"rel="stylesheet" type="text/css" />
	
<!--<script type="text/javascript" src="js/jquery-2.0.0.min.js"></script>-->
<script type="text/javascript" src="js/jquery.smartWizard.js"></script>

	

  </body>
</html>