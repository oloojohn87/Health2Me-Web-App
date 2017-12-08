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
$userid = $_GET['idusu'];

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

$enc_result = mysql_query("select pass from encryption_pass where id = (select max(id) from encryption_pass)");
$row_enc = mysql_fetch_array($enc_result);
$enc_pass=$row_enc['pass'];

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

<!--	<link rel="stylesheet" href="css/icon/font-awesome.css">-->
    <link rel="stylesheet" href="font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/bootstrap-responsive.css">
	<link rel="stylesheet" type="text/css" href="css/tooltipster.css" />

    <link href="css/style.css" rel="stylesheet">
    <link href="css/bootstrap.css" rel="stylesheet">
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
    <link rel="stylesheet" href="css/zebra-datepicker-bootstrap.css" type="text/css">
	
	<link href="css/demo_style.css" rel="stylesheet" type="text/css"/>
	<link href="css/smart_wizard.css" rel="stylesheet" type="text/css"/>
	<link rel="stylesheet" type="text/css" href="css/MooEditable.css">
	<link rel="stylesheet" href="css/jquery-ui-autocomplete.css" />
	<script src="js/jquery-1.9.1-autocomplete.js"></script>
	<script src="js/jquery-ui-autocomplete.js"></script>
		
	
	<link rel="stylesheet" type="text/css" href="js/uploadify/uploadify.css">
    <script type="text/javascript" src="js/uploadify/jquery.uploadify.min.js"></script> 
	
	<link href="js/signature/assets/jquery.signaturepad.css" rel="stylesheet">
  
   

   

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
<input type="hidden" id="patientid" value="<?php echo $userid;?>">	
<input type="hidden" id="patientname" >	     	
<input type="hidden" id="doctorid" value="<?php echo $_SESSION['MEDID'];?>">	
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
     
     
     <!--CONTENT MAIN START-->
	 <div class="content">
	 
			<!--Pop Up For Edit and Delete -->
			<button id="BotonModal_Edit" data-target="#header-modal333" data-toggle="modal" class="btn btn-warning" style="display: none;">Modal with Header</button> 
				<div id="header-modal333" class="modal fade hide" style="display: none;" aria-hidden="true">
				<div class="modal-header">
					<button class="close" type="button" data-dismiss="modal">×</button>
						Choose Action
				</div>
				<div class="modal-body">
					<input type="button" class="btn btn-danger" data-dismiss="modal" value="Delete" style="" id="Delete">
					<input type="button" class="btn btn-primary" data-dismiss="modal" value="Edit" style="" id="Edit"> 
				</div>
					
				<div class="modal-footer">
					<a href="#" class="btn btn-primary" data-dismiss="modal" id="CloseModallink">Close</a>
				</div>
				</div>  
			<!--Pop Up For Edit and Delete End-->
	 
			<!-- Pop up PastDX Start-->
			<button id="BotonPastDX" data-target="#header-modal" data-toggle="modal" class="btn btn-warning" style="display: none;">Modal with Header</button>
				<div id="header-modal" class="modal hide" style="display: none; height:300px; width:400px; margin-left:-200px;" aria-hidden="true">
				<div class="modal-header">
					<button class="close" type="button" data-dismiss="modal">×</button>
					<div id="InfB" >
	                 	<h4>Past Diagnostics</h4>
					</div>
        		</div>
         		<div class="modal-body" id="ContenidoModal" style="height:150px;">
					<center>
					<table style="background:transparent; height:150px;" >
						<tr>
							<td style="height:24px;">Diagnostic Name : </td>
							<td style="height:24px;"><input id="DXName"  /></td>
						</tr>
								
						<tr>
							<td style="height:24px;">ICD Code:</td>
							<td style="height:24px; "> <input id="icdcode" ></td> 
						</tr>
					
						<tr >
							<td style="height:24px">Start Date: </td>
							<td style="height:24px;"><input id="DXStartDate"  /></td>
						</tr>
						
						<tr>
							<td style="height:24px;">End Date: </td>
							<td style="height:24px;"><input id="DXEndDate"/></td>
						</tr>
					
					</table>
					</center>
					
					
				</div>
				<div class="modal-footer">
			
					<a href="#" class="btn btn-success" data-dismiss="modal" id="UpdatePastDX">Update Data</a>
					<a href="#" class="btn btn-primary" data-dismiss="modal" id="CloseModal">Close</a>
				</div>
			</div>  
			<!--Pop up PastDX End-->
			
			
			<!-- Pop up Medication Start-->
			<button id="BotonMedication" data-target="#header-modal1" data-toggle="modal" class="btn btn-warning" style="display: none;">Modal with Header</button>
				<div id="header-modal1" class="modal hide" style="display: none;  width:400px; margin-left:-200px;" aria-hidden="true">
				<div class="modal-header">
					<button class="close" type="button" data-dismiss="modal">×</button>
					<div id="InfB" >
	                 	<h4>Medication</h4>
					</div>
        		</div>
         		<div class="modal-body" id="ContenidoModal" style="height:300px;">
					<center>
					<table style="background:transparent; height:300px;" >
						<tr>
							<td style="height:24px;">Drug Name : </td>
							<td style="height:24px;"><input id="DrugName"  /></td>
						</tr>
								
						<tr>
							<td style="height:24px;">Drug Code:</td>
							<td style="height:24px; "> <input id="DrugCode" ></td> 
						</tr>
						
						<tr>
							<td style="height:24px;">Dossage : </td>
							<td style="height:24px;"><input id="Dossage"  /></td>
						</tr>
								
						<tr>
							<td style="height:24px;">Number per Day:</td>
							<td style="height:24px; "> <input id="NumPerDay" ></td> 
						</tr>
					
						<tr >
							<td style="height:24px">Start Date: </td>
							<td style="height:24px;"><input id="MedicationStartDate"  /></td>
						</tr>
						
						<tr>
							<td style="height:24px;">Stop Date: </td>
							<td style="height:24px;"><input id="MedicationEndDate"/></td>
						</tr>
					
					</table>
					</center>
					
					
				</div>
				<div class="modal-footer">
			
					<a href="#" class="btn btn-success" data-dismiss="modal" id="UpdateMedication">Update Data</a>
					<a href="#" class="btn btn-primary" data-dismiss="modal" id="CloseModal">Close</a>
				</div>
			</div>  
			<!--Pop up Medication End-->
			
			<!-- Pop up Immunization Start-->
			<button id="BotonImmunization" data-target="#header-modal2" data-toggle="modal" class="btn btn-warning" style="display: none;">Modal with Header</button>
				<div id="header-modal2" class="modal hide" style="display: none;  width:400px; margin-left:-200px;" aria-hidden="true">
				<div class="modal-header">
					<button class="close" type="button" data-dismiss="modal">×</button>
					<div id="InfB" >
	                 	<h4>Immunization</h4>
					</div>
        		</div>
         		<div class="modal-body" id="ContenidoModal" style="height:200px;">
					<center>
					<table style="background:transparent; height:200px;" >
						<tr>
							<td style="height:24px;">Name : </td>
							<td style="height:24px;"><input id="IName"  /></td>
						</tr>

						<tr >
							<td style="height:24px">Date: </td>
							<td style="height:24px;"><input id="IDate"  /></td>
						</tr>
						
						<tr>
							<td style="height:24px;">Age :</td>
							<td style="height:24px; "> <input id="IAge" ></td> 
						</tr>
						
						<tr>
							<td style="height:24px;">Reaction :</td>
							<td style="height:24px; "> <input id="IReaction" ></td> 
						</tr>
					</table>
					</center>
					
					
				</div>
				<div class="modal-footer">
			
					<a href="#" class="btn btn-success" data-dismiss="modal" id="UpdateImmunization">Update Data</a>
					<a href="#" class="btn btn-primary" data-dismiss="modal" id="CloseModal">Close</a>
				</div>
			</div>  
			<!--Pop up Immunization End-->
			
			
			<!-- Pop up Allergy Start-->
			<button id="BotonAllergy" data-target="#header-modal3" data-toggle="modal" class="btn btn-warning" style="display: none;">Modal with Header</button>
				<div id="header-modal3" class="modal hide" style="display: none;  width:400px; margin-left:-200px;" aria-hidden="true">
				<div class="modal-header">
					<button class="close" type="button" data-dismiss="modal">×</button>
					<div id="InfB" >
	                 	<h4>Allergy</h4>
					</div>
        		</div>
         		<div class="modal-body" id="ContenidoModal" style="height:200px;">
					<center>
					<table style="background:transparent; height:200px;" >
						<tr>
							<td style="height:24px;">Allergy Name : </td>
							<td style="height:24px;"><input id="AName"  /></td>
						</tr>

						<tr >
							<td style="height:24px">Type: </td>
							<td style="height:24px;"><input id="AType"  /></td>
						</tr>
						
						<tr>
							<td style="height:24px;">Date Recorded :</td>
							<td style="height:24px; "> <input id="ADate" ></td> 
						</tr>
						
						<tr>
							<td style="height:24px;">Description :</td>
							<td style="height:24px; "> <input id="Description" ></td> 
						</tr>
					</table>
					</center>
					
					
				</div>
				<div class="modal-footer">
			
					<a href="#" class="btn btn-success" data-dismiss="modal" id="UpdateAllergy">Update Data</a>
					<a href="#" class="btn btn-primary" data-dismiss="modal" id="CloseModal">Close</a>
				</div>
			</div>  
			<!--Pop up Allergy End-->
			
			<!-- Pop up Changing Refrigerator/Freezer Logs -->
			<button id="BotonReflog" data-target="#header-modal4" data-toggle="modal" class="btn btn-warning" style="display: none;">Modal with Header</button>
				<div id="header-modal4" class="modal hide" style="display: none;  width:500px; margin-left:-200px;" aria-hidden="true">
				<div class="modal-header">
					<button class="close" type="button" data-dismiss="modal">×</button>
					<div id="InfB" >
	                 	<h4>Add Refrigerator/Freezer Logs</h4>
					</div>
        		</div>
         		<div class="modal-body" id="ContenidoModal" style="height:300px;">
					<center>
					<table style="background:transparent; height:300px;" >
						<tr>
							<td style="height:24px;">Type: </td>
							<td style="height:24px;">
								<select id="Reflog_type">
								  <option value="Refrigerator">Refrigerator</option>
								  <option value="Freezer">Freezer</option>							  
								</select>
							</td>
						</tr>
						
						<tr>
							<td style="height:24px;">Time: </td>
							<td style="height:24px;">
								<select id="Reflog_time">
								  <option value="AM">AM</option>
								  <option value="PM">PM</option>							  
								</select>
							</td>
						</tr>
						
						<tr>
							<td style="height:24px;">Day: </td>
							<td style="height:24px;"><input id="Reflog_Day"/></td>
						</tr>

						<tr >
							<td style="height:24px">Temp(F): </td>
							<td style="height:24px;"><input id="Reflog_temp"  /></td>
						</tr>
						
						<tr>
							<td style="height:24px;">Min :</td>
							<td style="height:24px; "> <input id="Reflog_min" ></td> 
						</tr>
						
						<tr>
							<td style="height:24px;">Max :</td>
							<td style="height:24px; "> <input id="Reflog_max" ></td> 
						</tr>
						<tr>
							<td style="height:24px;">Initials/Comments :</td>
							<td style="height:24px; "> <input id="Reflog_cmt" ></td> 
						</tr>
					</table>
					</center>
					
					
				</div>
				<div class="modal-footer">
			
					<a href="#" class="btn btn-success" data-dismiss="modal" id="UpdateCP">Update Data</a>
					<a href="#" class="btn btn-primary" data-dismiss="modal" id="CloseModal">Close</a>
				</div>
			</div>  
			<!--Pop up Changing Refrigerator/Freezer Logs-->
	
	
	 
			<div class="grid" class="grid span4" style="width:1100px; margin: 0 auto; margin-top:30px; padding-top:30px;">
			<center>
			<ul id="myTab" class="nav nav-tabs tabs-main">
				<li class="active" ><a href="#TabRefrigeratorLog" data-toggle="tab">Medication Refrigerator/Freezer Temperature Log</a></li>
				<?php if($row['personal'])
					echo '<li><a href="#TabPatientEnc" data-toggle="tab">Patient Encounter Form</a></li>';
				?>
                <li><a href="#AllergyTestResultsForm" data-toggle="tab">Allergy Test Results Form</a></li>
                <li><a href="#PatientTreatmentOptionsElectionForm" data-toggle="tab">Patient Treatment Options Election Form</a></li>
                <li><a href="#TestingAuthorizationForm" data-toggle="tab">Testing Authorization Form</a></li>
                <li><a href="#TabImmunizationTherapy" data-toggle="tab">Immunization Form</a></li>
				<li><a href="#TabFinancialAgreement" data-toggle="tab">Financial Agreement</a></li>
						
			</ul>
			</center>
			<div id="myTabContent" class="tab-content tabs-main-content padding-null" >	
			<div class="tab-pane tab-overflow-main fade in active" id="TabRefrigeratorLog">
				<div style="margin:15px; margin-top:5px;">
					<div class="row-fluid"  style="">	            
						<div class="grid" style="padding:10px;height:620px;position:relative;">
							
							<div style="margin-left:20px; margin-top:20px; color: #3d93e0; font-size:20px; font-weight:bold; width:100%; ">Refrigerator Logs</div>
								<hr>
								<div> 
								  <span style="margin-left:50px;"> Agency Name <input type="text" style="width:200px"> </span>
								  <span style="margin-left:50px;"> Location: <input type="text" style="width:200px"> </span>
								  <span style="margin-left:50px;"> Month/Year: <input type="text" style="width:200px"> </span>
								</div>
								<div style="margin-top:20px">
									<span style="margin-left:80px;font-size:15px; font-weight:bold;"> Appropriate Refrigerator Range = 36 F to 46 F </span>
									<span style="margin-left:190px;font-size:15px; font-weight:bold;"> Appropriate Freezer Range = 5 F or Lower </span>
								</div>
								<div style="margin-top:40px">
									
									<span style="margin-left:80px;font-size:15px; font-weight:bold;text-align: center;"> REFRIGERATOR/FREEZER </span>
									<div class="clear" style="margin-top:20px;"></div>
									
									<table class="table table-mod-2" style="border:1px solid black; text-align: center;width:90%">
										  <thead>
											<tr>
											  <th  style="text-align: center;width:30px">day</th>
											  <th  style="text-align: center;width:30px">Temp(F)</th>
											  <th  style="text-align: center;width:30px">Min</th>
											  <th  style="text-align: center;width:30px">Max</th>
											  <th  style="text-align: center;width:30px">Initials/Comments</th>
											</tr>
										  </thead>
										  <tbody>
											
										  </tbody>
									</table>
									
									<div style="float:right; margin-right:20px;margin-top:-35px;">
										<input type="button" class="btn btn-primary" value="Add" onClick="openCARefLog();">	
									</div>
								</div>
								
								<div style="position:absolute; bottom:0;margin-bottom:30px">
									<p align="justify"><span style="margin-left:0px;font-size:15px;">
									In the event that the refrigerator and/or freezer temperature <span style="font-weight:bold;">(including min/max) temperature values 
									for refrigerator) </span> are not within the accepted range, staff shall <span style="font-weight:bold;"><u>IMMEDIATELY NOTIFY the clinic manager or
									nursing leader </u></span> who will seek consultation to determine if medications and antigens remain viable. 
									</span><p>
								</div>
																						
						</div>
					</div>
				</div>
			</div>	
				
				
				
			<div class="tab-pane" id="TabPatientEnc">	
				<div style="margin:15px; margin-top:5px;">
					<div class="row-fluid"  style="">	            
						<div class="grid" style="padding:10px;height:1100px">
							
							<div style="margin-left:20px; margin-top:20px; color: #3d93e0; font-size:20px; font-weight:bold; width:100%; ">Patient Encounter Form</div>
								<hr>
								<span style="font-size:13px; font-weight:bold;">DATE: </span><input type="date" id="PatEncDate" style="margin-left:10px">
								<div style="border:2px solid #a1a1a1;padding:10px 40px;margin-top:20px;margin:10px;float:left">
									<div style="border:2px solid #a1a1a1;float:left;height:220px;width:300px;">
											<p align="center" style="margin:10px;"> <b> Pre-Test VITAL SIGNS: </b></p>
											
											
											<p style="margin-left:10px;">Temperature:<input id="Pre_PatEncTemp" style="margin-right:10px;float:right"/></p>
											<p style="margin-left:10px;">Blood Pressure:<input id="Pre_PatEncBldPressure" style="margin-right:10px;float:right"/></p>
											<p style="margin-left:10px;">Pulse:<input id="Pre_PatEncBldPulse" style="margin-right:10px;float:right"/></p>
											<p style="margin-left:10px;">Respirations:<input id="Pre_PatEncBldRes" style="margin-right:10px;float:right"/></p>
									
									</div>
									<div style="border:2px solid #a1a1a1;float:left;height:220px;width:300px;">
											<p align="center" style="margin:10px;"> <b> TYPE OF TESTING ORDERED: </b></p>
											<textarea id="PatEnctypeOrd" rows="6" cols="50" style="margin-top:20px;margin-left:50px"></textarea>
											
											
									</div>
									<div style="border:2px solid #a1a1a1;float:left;height:220px;width:300px;">
											<p align="center" style="margin:10px;"> <b> LIST TESTING COMPLETED: </b></p>
											<p style="margin-left:10px;">1 <input id="PatEnclst1" style="margin-right:30px;float:right"></p>
											<p style="margin-left:10px;">2 <input id="PatEnclst2" style="margin-right:30px;float:right"></p>
											<p style="margin-left:10px;">3 <input id="PatEnclst3" style="margin-right:30px;float:right"></p>
											<p style="margin-left:10px;">4 <input id="PatEnclst4" style="margin-right:30px;float:right"></p>
											<p style="margin-left:10px;">5 <input id="PatEnclst5" style="margin-right:30px;float:right"></p>
											<p style="margin-left:10px;">6 <input id="PatEnclst6" style="margin-right:30px;float:right"></p>
																		
									</div>
									<div style="border:2px solid #a1a1a1;float:left;height:220px;width:300px;">
											<p align="center" style="margin:10px;"> <b> BODY LOCATION OF TEST COMPLETED: </b></p>
											<p style="margin-left:10px;">1 <input id="PatEncLoc1" style="margin-right:30px;float:right"></p>
											<p style="margin-left:10px;">2 <input id="PatEncLoc2" style="margin-right:30px;float:right"></p>
											<p style="margin-left:10px;">3 <input id="PatEncLoc3" style="margin-right:30px;float:right"></p>
											<p style="margin-left:10px;">4 <input id="PatEncLoc4" style="margin-right:30px;float:right"></p>
											<p style="margin-left:10px;">5 <input id="PatEncLoc5" style="margin-right:30px;float:right"></p>
											<p style="margin-left:10px;">6 <input id="PatEncLoc6" style="margin-right:30px;float:right"></p>
																		
									</div>
									<div style="border:2px solid #a1a1a1;float:left;height:220px;width:300px;">
											<p align="center" style="margin:10px;"> <b> PATIENTS TEST REACTION: </b></p>
											<p style="margin-left:10px;">1 <input id="PatEncRea1" style="margin-right:30px;float:right"></p>
											<p style="margin-left:10px;">2 <input id="PatEncRea2" style="margin-right:30px;float:right"></p>
											<p style="margin-left:10px;">3 <input id="PatEncRea3" style="margin-right:30px;float:right"></p>
											<p style="margin-left:10px;">4 <input id="PatEncRea4" style="margin-right:30px;float:right"></p>
											<p style="margin-left:10px;">5 <input id="PatEncRea5" style="margin-right:30px;float:right"></p>
											<p style="margin-left:10px;">6 <input id="PatEncRea6" style="margin-right:30px;float:right"></p>
																		
									</div>
									<div style="border:2px solid #a1a1a1;float:left;height:220px;width:300px;">
											<p align="center" style="margin:10px;"> <b> DID PATIENT TOLERATE PROCEDURE WELL: </b></p>
											<p style="margin-left:10px;">
												<select id="PatEncTolPrcopt">
												  <option value="YES">YES</option>
												  <option value="NO">NO</option>							  
												</select>
											</p>
											<p style="margin-left:10px;">If NO, please explain
											<textarea id="PatEncTolPrc" rows="3" cols="50" style="margin-right:10px;"></textarea>
											</p>
											
																		
									</div>
									
									<div style="border:2px solid #a1a1a1;float:left;height:220px;width:300px;">
											<p align="center" style="margin:10px;"> <b> PATIENT INSTRUCTIONS GIVEN FOR AVOIDANCE STRATAGIES? </b></p>
											<p style="margin-left:10px;">
												<select id="PatEncavdstr">
												  <option value="YES">YES</option>
												  <option value="NO">NO</option>							  
												</select>
											</p>
											
																		
									</div>
									<div style="border:2px solid #a1a1a1;float:left;height:220px;width:300px;">
											<p align="center" style="margin:10px;"> <b>DID PATIENT VOICE ANY QUIESTIONS OR CONCERNS? </b></p>
											<p style="margin-left:10px;">
												<select id="PatEncvoiceopt">
												  <option value="YES">YES</option>
												  <option value="NO">NO</option>							  
												</select>
											</p>
											
											<p style="margin-left:10px;">EXPLAIN: 
											<textarea id="PatEncvoice" rows="3" cols="50" style="margin-right:10px;"></textarea>
											</p>
											
																		
									</div>
									<div style="border:2px solid #a1a1a1;float:left;height:220px;width:300px;">
											<p align="center" style="margin:10px;"> <b> Post-Test VITAL SIGNS: </b></p>
											
											
											<p style="margin-left:10px;">Temperature:<input id="Post_PatEncTemp" style="margin-right:10px;float:right"/></p>
											<p style="margin-left:10px;">Blood Pressure:<input id="Post_PatEncBldPressure" style="margin-right:10px;float:right"/></p>
											<p style="margin-left:10px;">Pulse:<input id="Post_PatEncBldPulse" style="margin-right:10px;float:right"/></p>
											<p style="margin-left:10px;">Respirations:<input id="Post_PatEncBldRes" style="margin-right:10px;float:right"/></p>
																		
									</div>
								</div>
								
								<div style="margin-left:250px;float:left;margin-top:30px;">
										<!--<form method="post" action="" class="sigPad">-->	
										<div class="sigPad">
											
											<p class="drawItDesc">signature</p>
											<ul class="sigNav">
											  <li class="drawIt"><a href="#draw-it" class="current">Please Sign here</a></li>
											  <li class="clearButton"><a href="#clear">Clear</a></li>
											</ul>
											<div class="sig sigWrapper">
											  <div class="typed"></div>
											  <canvas class="pad" width="198" height="55"></canvas>
											  <input type="hidden" name="output" class="output">
											</div>
											<button type="submit" id="accept_sig">Accept Signature</button>
										</div>
									 <!-- </form> -->
								
								</div>
								

								<div style="margin-left:160px;float:left;margin-top: 80px;">
										<p align="center"><b><u>Signature Captured</u></b></p>
										<div class="sigPad2 signed">
											
											<div class="sigWrapper">
											 
											  <canvas class="pad" width="198" height="55"></canvas>
											</div>
										</div>
								</div>
								
								
									
						</div>
							<center>
								<input id="clear_allergy_temp" type="button" class="btn btn-primary" value="SAVE" style="margin-top:7px;">	
							</center>
					</div>
				</div>
			</div>
                
                
            <div class="tab-pane" id="AllergyTestResultsForm">	
                <div style="margin:15px; margin-top:5px;">
					<div class="row-fluid"  style="">	            
						<div class="grid" style="padding:10px;height:1450px;padding-left: 0px;">
							
							<div style="margin-left:20px; margin-top:20px; color: #3d93e0; font-size:20px; font-weight:bold; width:100%; ">Allergy Test Results Form</div>
                            <style>
                                .all_test_panel{
                                    width: 49%;
                                    float: left;
                                    height: 140px;
                                    margin-bottom: 10px;
                                    margin-left: 1%;

                                }
                                .all_test_panel_field{
                                    padding-left: 1%;
                                    width: 59%;
                                    height: 20px;
                                    float: left;
                                    outline: 1px solid #444;
                                }
                                .all_test_panel_field input{
                                    background-color: transparent;
                                    border: 0px solid;
                                    height:20px;
                                    width:100%;
                                    box-shadow: 0px 0px 0px #000;
                                    color:#555;
                                }
                                .all_test_info input{
                                    height: 20px;
                                    background-color: #FFF;
                                    float: left;
                                    padding: 10px;
                                }
                            </style>
                            <br/>
                            <div class="all_test_info" style="width: 100%; height: 40px; margin-left: 1%;">
                                <label style="float: left; margin-right: 10px;">Patient Name: </label>
                                <input type="text" id="all_test_result_name" size="10" />
                                
                                <label style="float: left; margin-right: 10px; margin-left: 10px;">Patient Date of Birth: </label>
                                <input type="text" id="all_test_result_dob" size="10" />
                                
                            </div>
                            <!-- Panel Section A 1 -->
                            <div id="AllergyTestPanelA1" class="all_test_panel">
                                <div class="all_test_panel_field"><strong>PANEL A</strong></div>
                                <div class="all_test_panel_field" style="width: 29%"><strong>Epicutaneous</strong></div>
                                <div class="all_test_panel_field" style="width: 9%"><strong>Class</strong></div>
                                
                                <div class="all_test_panel_field" style="width: 14%"><strong>Site</strong></div>
                                <div class="all_test_panel_field" style="width: 44%"><strong>Allergen</strong></div>
                                <div class="all_test_panel_field" style="width: 14%"><strong>W (mm)</strong></div>
                                <div class="all_test_panel_field" style="width: 14%"><strong>F</strong></div>
                                <div class="all_test_panel_field" style="width: 9%;"></div>
                                
                                <div class="all_test_panel_field" style="width: 14%">1</div>
                                <div class="all_test_panel_field" style="width: 44%">Positive Control</div>
                                <div class="all_test_panel_field" style="width: 14%"><input type="text" id="panelA_site1_W" /></div>
                                <div class="all_test_panel_field" style="width: 14%"><input type="text" id="panelA_site1_F" /></div>
                                <div class="all_test_panel_field" style="width: 9%"><input type="text" id="panelA_site1_C" /></div>
                                
                                <div class="all_test_panel_field" style="width: 14%">2</div>
                                <div class="all_test_panel_field" style="width: 44%">Johnson Grass</div>
                                <div class="all_test_panel_field" style="width: 14%"><input type="text" id="panelA_site2_W" /></div>
                                <div class="all_test_panel_field" style="width: 14%"><input type="text" id="panelA_site2_F" /></div>
                                <div class="all_test_panel_field" style="width: 9%"><input type="text" id="panelA_site2_C" /></div>
                                
                                <div class="all_test_panel_field" style="width: 14%">3</div>
                                <div class="all_test_panel_field" style="width: 44%">Kentucky Blue</div>
                                <div class="all_test_panel_field" style="width: 14%"><input type="text" id="panelA_site3_W" /></div>
                                <div class="all_test_panel_field" style="width: 14%"><input type="text" id="panelA_site3_F" /></div>
                                <div class="all_test_panel_field" style="width: 9%"><input type="text" id="panelA_site3_C" /></div>
                                
                                <div class="all_test_panel_field" style="width: 14%">4</div>
                                <div class="all_test_panel_field" style="width: 44%">Perennial Rye</div>
                                <div class="all_test_panel_field" style="width: 14%"><input type="text" id="panelA_site4_W" /></div>
                                <div class="all_test_panel_field" style="width: 14%"><input type="text" id="panelA_site4_F" /></div>
                                <div class="all_test_panel_field" style="width: 9%"><input type="text" id="panelA_site4_C" /></div>
                                
                                <div class="all_test_panel_field" style="width: 14%">5</div>
                                <div class="all_test_panel_field" style="width: 44%">Rough Pigweed</div>
                                <div class="all_test_panel_field" style="width: 14%"><input type="text" id="panelA_site5_W" /></div>
                                <div class="all_test_panel_field" style="width: 14%"><input type="text" id="panelA_site5_F" /></div>
                                <div class="all_test_panel_field" style="width: 9%"><input type="text" id="panelA_site5_C" /></div>
                            
                            </div>
                            <!-- End Panel Section A 1 -->
                            
                            <!-- Panel Section A 2 -->
                            <div id="AllergyTestPanelA1" class="all_test_panel">
                                <div class="all_test_panel_field"><strong>PANEL A</strong></div>
                                <div class="all_test_panel_field" style="width: 29%"><strong>Epicutaneous</strong></div>
                                <div class="all_test_panel_field" style="width: 9%"><strong>Class</strong></div>
                                
                                <div class="all_test_panel_field" style="width: 14%"><strong>Site</strong></div>
                                <div class="all_test_panel_field" style="width: 44%"><strong>Allergen</strong></div>
                                <div class="all_test_panel_field" style="width: 14%"><strong>W (mm)</strong></div>
                                <div class="all_test_panel_field" style="width: 14%"><strong>F</strong></div>
                                <div class="all_test_panel_field" style="width: 9%;"></div>
                                
                                <div class="all_test_panel_field" style="width: 14%">6</div>
                                <div class="all_test_panel_field" style="width: 44%">Red/Green Ash</div>
                                <div class="all_test_panel_field" style="width: 14%"><input type="text" id="panelA_site6_W" /></div>
                                <div class="all_test_panel_field" style="width: 14%"><input type="text" id="panelA_site6_F" /></div>
                                <div class="all_test_panel_field" style="width: 9%"><input type="text" id="panelA_site6_C" /></div>
                                
                                <div class="all_test_panel_field" style="width: 14%">7</div>
                                <div class="all_test_panel_field" style="width: 44%">Bur Oak</div>
                                <div class="all_test_panel_field" style="width: 14%"><input type="text" id="panelA_site7_W" /></div>
                                <div class="all_test_panel_field" style="width: 14%"><input type="text" id="panelA_site7_F" /></div>
                                <div class="all_test_panel_field" style="width: 9%"><input type="text" id="panelA_site7_C" /></div>
                                
                                <div class="all_test_panel_field" style="width: 14%">8</div>
                                <div class="all_test_panel_field" style="width: 44%">Post Oak</div>
                                <div class="all_test_panel_field" style="width: 14%"><input type="text" id="panelA_site8_W" /></div>
                                <div class="all_test_panel_field" style="width: 14%"><input type="text" id="panelA_site8_F" /></div>
                                <div class="all_test_panel_field" style="width: 9%"><input type="text" id="panelA_site8_C" /></div>
                                
                                <div class="all_test_panel_field" style="width: 14%">9</div>
                                <div class="all_test_panel_field" style="width: 44%">Eastern Cottonwood</div>
                                <div class="all_test_panel_field" style="width: 14%"><input type="text" id="panelA_site9_W" /></div>
                                <div class="all_test_panel_field" style="width: 14%"><input type="text" id="panelA_site9_F" /></div>
                                <div class="all_test_panel_field" style="width: 9%"><input type="text" id="panelA_site9_C" /></div>
                                
                                <div class="all_test_panel_field" style="width: 14%">10</div>
                                <div class="all_test_panel_field" style="width: 44%">American Elm</div>
                                <div class="all_test_panel_field" style="width: 14%"><input type="text" id="panelA_site10_W" /></div>
                                <div class="all_test_panel_field" style="width: 14%"><input type="text" id="panelA_site10_F" /></div>
                                <div class="all_test_panel_field" style="width: 9%"><input type="text" id="panelA_site10_C" /></div>
                            
                            </div>
                            <!-- End Panel Section A 2 -->
                            
                            <!-- Panel Section B 1 -->
                            <div id="AllergyTestPanelA1" class="all_test_panel">
                                <div class="all_test_panel_field"><strong>PANEL B</strong></div>
                                <div class="all_test_panel_field" style="width: 29%"><strong>Epicutaneous</strong></div>
                                <div class="all_test_panel_field" style="width: 9%"><strong>Class</strong></div>
                                
                                <div class="all_test_panel_field" style="width: 14%"><strong>Site</strong></div>
                                <div class="all_test_panel_field" style="width: 44%"><strong>Allergen</strong></div>
                                <div class="all_test_panel_field" style="width: 14%"><strong>W (mm)</strong></div>
                                <div class="all_test_panel_field" style="width: 14%"><strong>F</strong></div>
                                <div class="all_test_panel_field" style="width: 9%;"></div>
                                
                                <div class="all_test_panel_field" style="width: 14%">1</div>
                                <div class="all_test_panel_field" style="width: 44%">Pecan</div>
                                <div class="all_test_panel_field" style="width: 14%"><input type="text" id="panelB_site1_W" /></div>
                                <div class="all_test_panel_field" style="width: 14%"><input type="text" id="panelB_site1_F" /></div>
                                <div class="all_test_panel_field" style="width: 9%"><input type="text" id="panelB_site1_C" /></div>
                                
                                <div class="all_test_panel_field" style="width: 14%">2</div>
                                <div class="all_test_panel_field" style="width: 44%">Red Mulberry</div>
                                <div class="all_test_panel_field" style="width: 14%"><input type="text" id="panelB_site2_W" /></div>
                                <div class="all_test_panel_field" style="width: 14%"><input type="text" id="panelB_site2_F" /></div>
                                <div class="all_test_panel_field" style="width: 9%"><input type="text" id="panelB_site2_C" /></div>
                                
                                <div class="all_test_panel_field" style="width: 14%">3</div>
                                <div class="all_test_panel_field" style="width: 44%">Hackberry</div>
                                <div class="all_test_panel_field" style="width: 14%"><input type="text" id="panelB_site3_W" /></div>
                                <div class="all_test_panel_field" style="width: 14%"><input type="text" id="panelB_site3_F" /></div>
                                <div class="all_test_panel_field" style="width: 9%"><input type="text" id="panelB_site3_C" /></div>
                                
                                <div class="all_test_panel_field" style="width: 14%">4</div>
                                <div class="all_test_panel_field" style="width: 44%">Acacia</div>
                                <div class="all_test_panel_field" style="width: 14%"><input type="text" id="panelB_site4_W" /></div>
                                <div class="all_test_panel_field" style="width: 14%"><input type="text" id="panelB_site4_F" /></div>
                                <div class="all_test_panel_field" style="width: 9%"><input type="text" id="panelB_site4_C" /></div>
                                
                                <div class="all_test_panel_field" style="width: 14%">5</div>
                                <div class="all_test_panel_field" style="width: 44%">Box Elder</div>
                                <div class="all_test_panel_field" style="width: 14%"><input type="text" id="panelB_site5_W" /></div>
                                <div class="all_test_panel_field" style="width: 14%"><input type="text" id="panelB_site5_F" /></div>
                                <div class="all_test_panel_field" style="width: 9%"><input type="text" id="panelB_site5_C" /></div>
                            
                            </div>
                            <!-- End Panel Section B 1 -->
                            
                            <!-- Panel Section B 2 -->
                            <div id="AllergyTestPanelA1" class="all_test_panel">
                                <div class="all_test_panel_field"><strong>PANEL B</strong></div>
                                <div class="all_test_panel_field" style="width: 29%"><strong>Epicutaneous</strong></div>
                                <div class="all_test_panel_field" style="width: 9%"><strong>Class</strong></div>
                                
                                <div class="all_test_panel_field" style="width: 14%"><strong>Site</strong></div>
                                <div class="all_test_panel_field" style="width: 44%"><strong>Allergen</strong></div>
                                <div class="all_test_panel_field" style="width: 14%"><strong>W (mm)</strong></div>
                                <div class="all_test_panel_field" style="width: 14%"><strong>F</strong></div>
                                <div class="all_test_panel_field" style="width: 9%;"></div>
                                
                                <div class="all_test_panel_field" style="width: 14%">6</div>
                                <div class="all_test_panel_field" style="width: 44%">Mesquite</div>
                                <div class="all_test_panel_field" style="width: 14%"><input type="text" id="panelB_site6_W" /></div>
                                <div class="all_test_panel_field" style="width: 14%"><input type="text" id="panelB_site6_F" /></div>
                                <div class="all_test_panel_field" style="width: 9%"><input type="text" id="panelB_site6_C" /></div>
                                
                                <div class="all_test_panel_field" style="width: 14%">7</div>
                                <div class="all_test_panel_field" style="width: 44%">Eastern Sycamore</div>
                                <div class="all_test_panel_field" style="width: 14%"><input type="text" id="panelB_site7_W" /></div>
                                <div class="all_test_panel_field" style="width: 14%"><input type="text" id="panelB_site7_F" /></div>
                                <div class="all_test_panel_field" style="width: 9%"><input type="text" id="panelB_site7_C" /></div>
                                
                                <div class="all_test_panel_field" style="width: 14%">8</div>
                                <div class="all_test_panel_field" style="width: 44%">Red Cedar</div>
                                <div class="all_test_panel_field" style="width: 14%"><input type="text" id="panelB_site8_W" /></div>
                                <div class="all_test_panel_field" style="width: 14%"><input type="text" id="panelB_site8_F" /></div>
                                <div class="all_test_panel_field" style="width: 9%"><input type="text" id="panelB_site8_C" /></div>
                                
                                <div class="all_test_panel_field" style="width: 14%">9</div>
                                <div class="all_test_panel_field" style="width: 44%">Giant Ragweed</div>
                                <div class="all_test_panel_field" style="width: 14%"><input type="text" id="panelB_site9_W" /></div>
                                <div class="all_test_panel_field" style="width: 14%"><input type="text" id="panelB_site9_F" /></div>
                                <div class="all_test_panel_field" style="width: 9%"><input type="text" id="panelB_site9_C" /></div>
                                
                                <div class="all_test_panel_field" style="width: 14%">10</div>
                                <div class="all_test_panel_field" style="width: 44%">Western Ragweed</div>
                                <div class="all_test_panel_field" style="width: 14%"><input type="text" id="panelB_site10_W" /></div>
                                <div class="all_test_panel_field" style="width: 14%"><input type="text" id="panelB_site10_F" /></div>
                                <div class="all_test_panel_field" style="width: 9%"><input type="text" id="panelB_site10_C" /></div>
                            
                            </div>
                            <!-- End Panel Section B 2 -->
                            
                            <!-- Panel Section C 1 -->
                            <div id="AllergyTestPanelA1" class="all_test_panel">
                                <div class="all_test_panel_field"><strong>PANEL C</strong></div>
                                <div class="all_test_panel_field" style="width: 29%"><strong>Epicutaneous</strong></div>
                                <div class="all_test_panel_field" style="width: 9%"><strong>Class</strong></div>
                                
                                <div class="all_test_panel_field" style="width: 14%"><strong>Site</strong></div>
                                <div class="all_test_panel_field" style="width: 44%"><strong>Allergen</strong></div>
                                <div class="all_test_panel_field" style="width: 14%"><strong>W (mm)</strong></div>
                                <div class="all_test_panel_field" style="width: 14%"><strong>F</strong></div>
                                <div class="all_test_panel_field" style="width: 9%;"></div>
                                
                                <div class="all_test_panel_field" style="width: 14%">1</div>
                                <div class="all_test_panel_field" style="width: 44%">Cocklebur</div>
                                <div class="all_test_panel_field" style="width: 14%"><input type="text" id="panelC_site1_W" /></div>
                                <div class="all_test_panel_field" style="width: 14%"><input type="text" id="panelC_site1_F" /></div>
                                <div class="all_test_panel_field" style="width: 9%"><input type="text" id="panelC_site1_C" /></div>
                                
                                <div class="all_test_panel_field" style="width: 14%">2</div>
                                <div class="all_test_panel_field" style="width: 44%">Yellow Dock</div>
                                <div class="all_test_panel_field" style="width: 14%"><input type="text" id="panelC_site2_W" /></div>
                                <div class="all_test_panel_field" style="width: 14%"><input type="text" id="panelC_site2_F" /></div>
                                <div class="all_test_panel_field" style="width: 9%"><input type="text" id="panelC_site2_C" /></div>
                                
                                <div class="all_test_panel_field" style="width: 14%">3</div>
                                <div class="all_test_panel_field" style="width: 44%">Firebush</div>
                                <div class="all_test_panel_field" style="width: 14%"><input type="text" id="panelC_site3_W" /></div>
                                <div class="all_test_panel_field" style="width: 14%"><input type="text" id="panelC_site3_F" /></div>
                                <div class="all_test_panel_field" style="width: 9%"><input type="text" id="panelC_site3_C" /></div>
                                
                                <div class="all_test_panel_field" style="width: 14%">4</div>
                                <div class="all_test_panel_field" style="width: 44%">Lambs Quarter</div>
                                <div class="all_test_panel_field" style="width: 14%"><input type="text" id="panelC_site4_W" /></div>
                                <div class="all_test_panel_field" style="width: 14%"><input type="text" id="panelC_site4_F" /></div>
                                <div class="all_test_panel_field" style="width: 9%"><input type="text" id="panelC_site4_C" /></div>
                                
                                <div class="all_test_panel_field" style="width: 14%">5</div>
                                <div class="all_test_panel_field" style="width: 44%">Marsh Elder</div>
                                <div class="all_test_panel_field" style="width: 14%"><input type="text" id="panelC_site5_W" /></div>
                                <div class="all_test_panel_field" style="width: 14%"><input type="text" id="panelC_site5_F" /></div>
                                <div class="all_test_panel_field" style="width: 9%"><input type="text" id="panelC_site5_C" /></div>
                            
                            </div>
                            <!-- End Panel Section C 1 -->
                            
                            <!-- Panel Section C 2 -->
                            <div id="AllergyTestPanelA1" class="all_test_panel">
                                <div class="all_test_panel_field"><strong>PANEL C</strong></div>
                                <div class="all_test_panel_field" style="width: 29%"><strong>Epicutaneous</strong></div>
                                <div class="all_test_panel_field" style="width: 9%"><strong>Class</strong></div>
                                
                                <div class="all_test_panel_field" style="width: 14%"><strong>Site</strong></div>
                                <div class="all_test_panel_field" style="width: 44%"><strong>Allergen</strong></div>
                                <div class="all_test_panel_field" style="width: 14%"><strong>W (mm)</strong></div>
                                <div class="all_test_panel_field" style="width: 14%"><strong>F</strong></div>
                                <div class="all_test_panel_field" style="width: 9%;"></div>
                                
                                <div class="all_test_panel_field" style="width: 14%">6</div>
                                <div class="all_test_panel_field" style="width: 44%">Nettle</div>
                                <div class="all_test_panel_field" style="width: 14%"><input type="text" id="panelC_site6_W" /></div>
                                <div class="all_test_panel_field" style="width: 14%"><input type="text" id="panelC_site6_F" /></div>
                                <div class="all_test_panel_field" style="width: 9%"><input type="text" id="panelC_site6_C" /></div>
                                
                                <div class="all_test_panel_field" style="width: 14%">7</div>
                                <div class="all_test_panel_field" style="width: 44%">Dark Leaf Mugwort</div>
                                <div class="all_test_panel_field" style="width: 14%"><input type="text" id="panelC_site7_W" /></div>
                                <div class="all_test_panel_field" style="width: 14%"><input type="text" id="panelC_site7_F" /></div>
                                <div class="all_test_panel_field" style="width: 9%"><input type="text" id="panelC_site7_C" /></div>
                                
                                <div class="all_test_panel_field" style="width: 14%">8</div>
                                <div class="all_test_panel_field" style="width: 44%">Cockroach</div>
                                <div class="all_test_panel_field" style="width: 14%"><input type="text" id="panelC_site8_W" /></div>
                                <div class="all_test_panel_field" style="width: 14%"><input type="text" id="panelC_site8_F" /></div>
                                <div class="all_test_panel_field" style="width: 9%"><input type="text" id="panelC_site8_C" /></div>
                                
                                <div class="all_test_panel_field" style="width: 14%">9</div>
                                <div class="all_test_panel_field" style="width: 44%">Atriplex</div>
                                <div class="all_test_panel_field" style="width: 14%"><input type="text" id="panelC_site9_W" /></div>
                                <div class="all_test_panel_field" style="width: 14%"><input type="text" id="panelC_site9_F" /></div>
                                <div class="all_test_panel_field" style="width: 9%"><input type="text" id="panelC_site9_C" /></div>
                                
                                <div class="all_test_panel_field" style="width: 14%">10</div>
                                <div class="all_test_panel_field" style="width: 44%">Cat</div>
                                <div class="all_test_panel_field" style="width: 14%"><input type="text" id="panelC_site10_W" /></div>
                                <div class="all_test_panel_field" style="width: 14%"><input type="text" id="panelC_site10_F" /></div>
                                <div class="all_test_panel_field" style="width: 9%"><input type="text" id="panelC_site10_C" /></div>
                            
                            </div>
                            <!-- End Panel Section C 2 -->
                            
                            <!-- Panel Section D 1 -->
                            <div id="AllergyTestPanelA1" class="all_test_panel">
                                <div class="all_test_panel_field"><strong>PANEL D</strong></div>
                                <div class="all_test_panel_field" style="width: 29%"><strong>Epicutaneous</strong></div>
                                <div class="all_test_panel_field" style="width: 9%"><strong>Class</strong></div>
                                
                                <div class="all_test_panel_field" style="width: 14%"><strong>Site</strong></div>
                                <div class="all_test_panel_field" style="width: 44%"><strong>Allergen</strong></div>
                                <div class="all_test_panel_field" style="width: 14%"><strong>W (mm)</strong></div>
                                <div class="all_test_panel_field" style="width: 14%"><strong>F</strong></div>
                                <div class="all_test_panel_field" style="width: 9%;"></div>
                                
                                <div class="all_test_panel_field" style="width: 14%">1</div>
                                <div class="all_test_panel_field" style="width: 44%">Cattle</div>
                                <div class="all_test_panel_field" style="width: 14%"><input type="text" id="panelD_site1_W" /></div>
                                <div class="all_test_panel_field" style="width: 14%"><input type="text" id="panelD_site1_F" /></div>
                                <div class="all_test_panel_field" style="width: 9%"><input type="text" id="panelD_site1_C" /></div>
                                
                                <div class="all_test_panel_field" style="width: 14%">2</div>
                                <div class="all_test_panel_field" style="width: 44%">Dog</div>
                                <div class="all_test_panel_field" style="width: 14%"><input type="text" id="panelD_site2_W" /></div>
                                <div class="all_test_panel_field" style="width: 14%"><input type="text" id="panelD_site2_F" /></div>
                                <div class="all_test_panel_field" style="width: 9%"><input type="text" id="panelD_site2_C" /></div>
                                
                                <div class="all_test_panel_field" style="width: 14%">3</div>
                                <div class="all_test_panel_field" style="width: 44%">Horse</div>
                                <div class="all_test_panel_field" style="width: 14%"><input type="text" id="panelD_site3_W" /></div>
                                <div class="all_test_panel_field" style="width: 14%"><input type="text" id="panelD_site3_F" /></div>
                                <div class="all_test_panel_field" style="width: 9%"><input type="text" id="panelD_site3_C" /></div>
                                
                                <div class="all_test_panel_field" style="width: 14%">4</div>
                                <div class="all_test_panel_field" style="width: 44%">M Farinae</div>
                                <div class="all_test_panel_field" style="width: 14%"><input type="text" id="panelD_site4_W" /></div>
                                <div class="all_test_panel_field" style="width: 14%"><input type="text" id="panelD_site4_F" /></div>
                                <div class="all_test_panel_field" style="width: 9%"><input type="text" id="panelD_site4_C" /></div>
                                
                                <div class="all_test_panel_field" style="width: 14%">5</div>
                                <div class="all_test_panel_field" style="width: 44%">M Pteryonsis</div>
                                <div class="all_test_panel_field" style="width: 14%"><input type="text" id="panelD_site5_W" /></div>
                                <div class="all_test_panel_field" style="width: 14%"><input type="text" id="panelD_site5_F" /></div>
                                <div class="all_test_panel_field" style="width: 9%"><input type="text" id="panelD_site5_C" /></div>
                            
                            </div>
                            <!-- End Panel Section D 1 -->
                            
                            <!-- Panel Section D 2 -->
                            <div id="AllergyTestPanelA1" class="all_test_panel">
                                <div class="all_test_panel_field"><strong>PANEL D</strong></div>
                                <div class="all_test_panel_field" style="width: 29%"><strong>Epicutaneous</strong></div>
                                <div class="all_test_panel_field" style="width: 9%"><strong>Class</strong></div>
                                
                                <div class="all_test_panel_field" style="width: 14%"><strong>Site</strong></div>
                                <div class="all_test_panel_field" style="width: 44%"><strong>Allergen</strong></div>
                                <div class="all_test_panel_field" style="width: 14%"><strong>W (mm)</strong></div>
                                <div class="all_test_panel_field" style="width: 14%"><strong>F</strong></div>
                                <div class="all_test_panel_field" style="width: 9%;"></div>
                                
                                <div class="all_test_panel_field" style="width: 14%">6</div>
                                <div class="all_test_panel_field" style="width: 44%">Candida Albicans</div>
                                <div class="all_test_panel_field" style="width: 14%"><input type="text" id="panelD_site6_W" /></div>
                                <div class="all_test_panel_field" style="width: 14%"><input type="text" id="panelD_site6_F" /></div>
                                <div class="all_test_panel_field" style="width: 9%"><input type="text" id="panelD_site6_C" /></div>
                                
                                <div class="all_test_panel_field" style="width: 14%">7</div>
                                <div class="all_test_panel_field" style="width: 44%">B. Sorokiana</div>
                                <div class="all_test_panel_field" style="width: 14%"><input type="text" id="panelD_site7_W" /></div>
                                <div class="all_test_panel_field" style="width: 14%"><input type="text" id="panelD_site7_F" /></div>
                                <div class="all_test_panel_field" style="width: 9%"><input type="text" id="panelD_site7_C" /></div>
                                
                                <div class="all_test_panel_field" style="width: 14%">8</div>
                                <div class="all_test_panel_field" style="width: 44%">Penicillium</div>
                                <div class="all_test_panel_field" style="width: 14%"><input type="text" id="panelD_site8_W" /></div>
                                <div class="all_test_panel_field" style="width: 14%"><input type="text" id="panelD_site8_F" /></div>
                                <div class="all_test_panel_field" style="width: 9%"><input type="text" id="panelD_site8_C" /></div>
                                
                                <div class="all_test_panel_field" style="width: 14%">9</div>
                                <div class="all_test_panel_field" style="width: 44%">S Solani</div>
                                <div class="all_test_panel_field" style="width: 14%"><input type="text" id="panelD_site9_W" /></div>
                                <div class="all_test_panel_field" style="width: 14%"><input type="text" id="panelD_site9_F" /></div>
                                <div class="all_test_panel_field" style="width: 9%"><input type="text" id="panelD_site9_C" /></div>
                                
                                <div class="all_test_panel_field" style="width: 14%">10</div>
                                <div class="all_test_panel_field" style="width: 44%">Fusarium</div>
                                <div class="all_test_panel_field" style="width: 14%"><input type="text" id="panelD_site10_W" /></div>
                                <div class="all_test_panel_field" style="width: 14%"><input type="text" id="panelD_site10_F" /></div>
                                <div class="all_test_panel_field" style="width: 9%"><input type="text" id="panelD_site10_C" /></div>
                            
                            </div>
                            <!-- End Panel Section D 2 -->
                            
                            <!-- Panel Section E 1 -->
                            <div id="AllergyTestPanelA1" class="all_test_panel">
                                <div class="all_test_panel_field"><strong>PANEL E</strong></div>
                                <div class="all_test_panel_field" style="width: 29%"><strong>Epicutaneous</strong></div>
                                <div class="all_test_panel_field" style="width: 9%"><strong>Class</strong></div>
                                
                                <div class="all_test_panel_field" style="width: 14%"><strong>Site</strong></div>
                                <div class="all_test_panel_field" style="width: 44%"><strong>Allergen</strong></div>
                                <div class="all_test_panel_field" style="width: 14%"><strong>W (mm)</strong></div>
                                <div class="all_test_panel_field" style="width: 14%"><strong>F</strong></div>
                                <div class="all_test_panel_field" style="width: 9%;"></div>
                                
                                <div class="all_test_panel_field" style="width: 14%">1</div>
                                <div class="all_test_panel_field" style="width: 44%">Negative Control</div>
                                <div class="all_test_panel_field" style="width: 14%"><input type="text" id="panelE_site1_W" /></div>
                                <div class="all_test_panel_field" style="width: 14%"><input type="text" id="panelE_site1_F" /></div>
                                <div class="all_test_panel_field" style="width: 9%"><input type="text" id="panelE_site1_C" /></div>
                                
                                <div class="all_test_panel_field" style="width: 14%">2</div>
                                <div class="all_test_panel_field" style="width: 44%">Aspergillius</div>
                                <div class="all_test_panel_field" style="width: 14%"><input type="text" id="panelE_site2_W" /></div>
                                <div class="all_test_panel_field" style="width: 14%"><input type="text" id="panelE_site2_F" /></div>
                                <div class="all_test_panel_field" style="width: 9%"><input type="text" id="panelE_site2_C" /></div>
                                
                                <div class="all_test_panel_field" style="width: 14%">3</div>
                                <div class="all_test_panel_field" style="width: 44%">A. Strictum</div>
                                <div class="all_test_panel_field" style="width: 14%"><input type="text" id="panelE_site3_W" /></div>
                                <div class="all_test_panel_field" style="width: 14%"><input type="text" id="panelE_site3_F" /></div>
                                <div class="all_test_panel_field" style="width: 9%"><input type="text" id="panelE_site3_C" /></div>
                                
                                <div class="all_test_panel_field" style="width: 14%">4</div>
                                <div class="all_test_panel_field" style="width: 44%">D. Spicifera</div>
                                <div class="all_test_panel_field" style="width: 14%"><input type="text" id="panelE_site4_W" /></div>
                                <div class="all_test_panel_field" style="width: 14%"><input type="text" id="panelE_site4_F" /></div>
                                <div class="all_test_panel_field" style="width: 9%"><input type="text" id="panelE_site4_C" /></div>
                                
                                <div class="all_test_panel_field" style="width: 14%">5</div>
                                <div class="all_test_panel_field" style="width: 44%">Rhodotorula</div>
                                <div class="all_test_panel_field" style="width: 14%"><input type="text" id="panelE_site5_W" /></div>
                                <div class="all_test_panel_field" style="width: 14%"><input type="text" id="panelE_site5_F" /></div>
                                <div class="all_test_panel_field" style="width: 9%"><input type="text" id="panelE_site5_C" /></div>
                            
                            </div>
                            <!-- End Panel Section E 1 -->
                            
                            <!-- Panel Section E 2 -->
                            <div id="AllergyTestPanelA1" class="all_test_panel">
                                <div class="all_test_panel_field"><strong>PANEL E</strong></div>
                                <div class="all_test_panel_field" style="width: 29%"><strong>Epicutaneous</strong></div>
                                <div class="all_test_panel_field" style="width: 9%"><strong>Class</strong></div>
                                
                                <div class="all_test_panel_field" style="width: 14%"><strong>Site</strong></div>
                                <div class="all_test_panel_field" style="width: 44%"><strong>Allergen</strong></div>
                                <div class="all_test_panel_field" style="width: 14%"><strong>W (mm)</strong></div>
                                <div class="all_test_panel_field" style="width: 14%"><strong>F</strong></div>
                                <div class="all_test_panel_field" style="width: 9%;"></div>
                                
                                <div class="all_test_panel_field" style="width: 14%">6</div>
                                <div class="all_test_panel_field" style="width: 44%">Baccharis</div>
                                <div class="all_test_panel_field" style="width: 14%"><input type="text" id="panelE_site6_W" /></div>
                                <div class="all_test_panel_field" style="width: 14%"><input type="text" id="panelE_site6_F" /></div>
                                <div class="all_test_panel_field" style="width: 9%"><input type="text" id="panelE_site6_C" /></div>
                                
                                <div class="all_test_panel_field" style="width: 14%">7</div>
                                <div class="all_test_panel_field" style="width: 44%">Russian Thistle</div>
                                <div class="all_test_panel_field" style="width: 14%"><input type="text" id="panelE_site7_W" /></div>
                                <div class="all_test_panel_field" style="width: 14%"><input type="text" id="panelE_site7_F" /></div>
                                <div class="all_test_panel_field" style="width: 9%"><input type="text" id="panelE_site7_C" /></div>
                                
                                <div class="all_test_panel_field" style="width: 14%">8</div>
                                <div class="all_test_panel_field" style="width: 44%">Bermuda</div>
                                <div class="all_test_panel_field" style="width: 14%"><input type="text" id="panelE_site8_W" /></div>
                                <div class="all_test_panel_field" style="width: 14%"><input type="text" id="panelE_site8_F" /></div>
                                <div class="all_test_panel_field" style="width: 9%"><input type="text" id="panelE_site8_C" /></div>
                                
                                <div class="all_test_panel_field" style="width: 14%">9</div>
                                <div class="all_test_panel_field" style="width: 44%">Bahia</div>
                                <div class="all_test_panel_field" style="width: 14%"><input type="text" id="panelE_site9_W" /></div>
                                <div class="all_test_panel_field" style="width: 14%"><input type="text" id="panelE_site9_F" /></div>
                                <div class="all_test_panel_field" style="width: 9%"><input type="text" id="panelE_site9_C" /></div>
                                
                                <div class="all_test_panel_field" style="width: 14%">10</div>
                                <div class="all_test_panel_field" style="width: 44%">W Wheat Grass</div>
                                <div class="all_test_panel_field" style="width: 14%"><input type="text" id="panelE_site10_W" /></div>
                                <div class="all_test_panel_field" style="width: 14%"><input type="text" id="panelE_site10_F" /></div>
                                <div class="all_test_panel_field" style="width: 9%"><input type="text" id="panelE_site10_C" /></div>
                            
                            </div>
                            <!-- End Panel Section E 2 -->
                            
                            <!-- Panel Section F 1 -->
                            <div id="AllergyTestPanelA1" class="all_test_panel">
                                <div class="all_test_panel_field"><strong>PANEL F</strong></div>
                                <div class="all_test_panel_field" style="width: 29%"><strong>Epicutaneous</strong></div>
                                <div class="all_test_panel_field" style="width: 9%"><strong>Class</strong></div>
                                
                                <div class="all_test_panel_field" style="width: 14%"><strong>Site</strong></div>
                                <div class="all_test_panel_field" style="width: 44%"><strong>Allergen</strong></div>
                                <div class="all_test_panel_field" style="width: 14%"><strong>W (mm)</strong></div>
                                <div class="all_test_panel_field" style="width: 14%"><strong>F</strong></div>
                                <div class="all_test_panel_field" style="width: 9%;"></div>
                                
                                <div class="all_test_panel_field" style="width: 14%">1</div>
                                <div class="all_test_panel_field" style="width: 44%"><input type="text" id="panelF_site1_A" /></div>
                                <div class="all_test_panel_field" style="width: 14%"><input type="text" id="panelF_site1_W" /></div>
                                <div class="all_test_panel_field" style="width: 14%"><input type="text" id="panelF_site1_F" /></div>
                                <div class="all_test_panel_field" style="width: 9%"><input type="text" id="panelF_site1_C" /></div>
                                
                                <div class="all_test_panel_field" style="width: 14%">2</div>
                                <div class="all_test_panel_field" style="width: 44%"><input type="text" id="panelF_site2_A" /></div>
                                <div class="all_test_panel_field" style="width: 14%"><input type="text" id="panelF_site2_W" /></div>
                                <div class="all_test_panel_field" style="width: 14%"><input type="text" id="panelF_site2_F" /></div>
                                <div class="all_test_panel_field" style="width: 9%"><input type="text" id="panelF_site2_C" /></div>
                                
                                <div class="all_test_panel_field" style="width: 14%">3</div>
                                <div class="all_test_panel_field" style="width: 44%"><input type="text" id="panelF_site3_A" /></div>
                                <div class="all_test_panel_field" style="width: 14%"><input type="text" id="panelF_site3_W" /></div>
                                <div class="all_test_panel_field" style="width: 14%"><input type="text" id="panelF_site3_F" /></div>
                                <div class="all_test_panel_field" style="width: 9%"><input type="text" id="panelF_site3_C" /></div>
                                
                                <div class="all_test_panel_field" style="width: 14%">4</div>
                                <div class="all_test_panel_field" style="width: 44%"><input type="text" id="panelF_site4_A" /></div>
                                <div class="all_test_panel_field" style="width: 14%"><input type="text" id="panelF_site4_W" /></div>
                                <div class="all_test_panel_field" style="width: 14%"><input type="text" id="panelF_site4_F" /></div>
                                <div class="all_test_panel_field" style="width: 9%"><input type="text" id="panelF_site4_C" /></div>
                                
                                <div class="all_test_panel_field" style="width: 14%">5</div>
                                <div class="all_test_panel_field" style="width: 44%"><input type="text" id="panelF_site5_A" /></div>
                                <div class="all_test_panel_field" style="width: 14%"><input type="text" id="panelF_site5_W" /></div>
                                <div class="all_test_panel_field" style="width: 14%"><input type="text" id="panelF_site5_F" /></div>
                                <div class="all_test_panel_field" style="width: 9%"><input type="text" id="panelF_site5_C" /></div>
                            
                            </div>
                            <!-- End Panel Section F 1 -->
                            
                            <!-- Panel Section F 2 -->
                            <div id="AllergyTestPanelA1" class="all_test_panel">
                                <div class="all_test_panel_field"><strong>PANEL F</strong></div>
                                <div class="all_test_panel_field" style="width: 29%"><strong>Epicutaneous</strong></div>
                                <div class="all_test_panel_field" style="width: 9%"><strong>Class</strong></div>
                                
                                <div class="all_test_panel_field" style="width: 14%"><strong>Site</strong></div>
                                <div class="all_test_panel_field" style="width: 44%"><strong>Allergen</strong></div>
                                <div class="all_test_panel_field" style="width: 14%"><strong>W (mm)</strong></div>
                                <div class="all_test_panel_field" style="width: 14%"><strong>F</strong></div>
                                <div class="all_test_panel_field" style="width: 9%;"></div>
                                
                                <div class="all_test_panel_field" style="width: 14%">6</div>
                                <div class="all_test_panel_field" style="width: 44%"><input type="text" id="panelF_site6_A" /></div>
                                <div class="all_test_panel_field" style="width: 14%"><input type="text" id="panelF_site6_W" /></div>
                                <div class="all_test_panel_field" style="width: 14%"><input type="text" id="panelF_site6_F" /></div>
                                <div class="all_test_panel_field" style="width: 9%"><input type="text" id="panelE_site6_C" /></div>
                                
                                <div class="all_test_panel_field" style="width: 14%">7</div>
                                <div class="all_test_panel_field" style="width: 44%"><input type="text" id="panelF_site7_A" /></div>
                                <div class="all_test_panel_field" style="width: 14%"><input type="text" id="panelF_site7_W" /></div>
                                <div class="all_test_panel_field" style="width: 14%"><input type="text" id="panelF_site7_F" /></div>
                                <div class="all_test_panel_field" style="width: 9%"><input type="text" id="panelF_site7_C" /></div>
                                
                                <div class="all_test_panel_field" style="width: 14%">8</div>
                                <div class="all_test_panel_field" style="width: 44%"><input type="text" id="panelF_site8_A" /></div>
                                <div class="all_test_panel_field" style="width: 14%"><input type="text" id="panelF_site8_W" /></div>
                                <div class="all_test_panel_field" style="width: 14%"><input type="text" id="panelF_site8_F" /></div>
                                <div class="all_test_panel_field" style="width: 9%"><input type="text" id="panelF_site8_C" /></div>
                                
                                <div class="all_test_panel_field" style="width: 14%">9</div>
                                <div class="all_test_panel_field" style="width: 44%"><input type="text" id="panelF_site9_A" /></div>
                                <div class="all_test_panel_field" style="width: 14%"><input type="text" id="panelF_site9_W" /></div>
                                <div class="all_test_panel_field" style="width: 14%"><input type="text" id="panelF_site9_F" /></div>
                                <div class="all_test_panel_field" style="width: 9%"><input type="text" id="panelF_site9_C" /></div>
                                
                                <div class="all_test_panel_field" style="width: 14%">10</div>
                                <div class="all_test_panel_field" style="width: 44%"><input type="text" id="panelF_site10_A" /></div>
                                <div class="all_test_panel_field" style="width: 14%"><input type="text" id="panelF_site10_W" /></div>
                                <div class="all_test_panel_field" style="width: 14%"><input type="text" id="panelF_site10_F" /></div>
                                <div class="all_test_panel_field" style="width: 9%"><input type="text" id="panelF_site10_C" /></div>
                            
                            </div>
                            <!-- End Panel Section F 2 -->
                            
                            <!-- Panel Section G 1 -->
                            <div id="AllergyTestPanelA1" class="all_test_panel">
                                <div class="all_test_panel_field"><strong>PANEL G</strong></div>
                                <div class="all_test_panel_field" style="width: 29%"><strong>Epicutaneous</strong></div>
                                <div class="all_test_panel_field" style="width: 9%"><strong>Class</strong></div>
                                
                                <div class="all_test_panel_field" style="width: 14%"><strong>Site</strong></div>
                                <div class="all_test_panel_field" style="width: 44%"><strong>Allergen</strong></div>
                                <div class="all_test_panel_field" style="width: 14%"><strong>W (mm)</strong></div>
                                <div class="all_test_panel_field" style="width: 14%"><strong>F</strong></div>
                                <div class="all_test_panel_field" style="width: 9%;"></div>
                                
                                <div class="all_test_panel_field" style="width: 14%">1</div>
                                <div class="all_test_panel_field" style="width: 44%"><input type="text" id="panelG_site1_A" /></div>
                                <div class="all_test_panel_field" style="width: 14%"><input type="text" id="panelG_site1_W" /></div>
                                <div class="all_test_panel_field" style="width: 14%"><input type="text" id="panelG_site1_F" /></div>
                                <div class="all_test_panel_field" style="width: 9%"><input type="text" id="panelG_site1_C" /></div>
                                
                                <div class="all_test_panel_field" style="width: 14%">2</div>
                                <div class="all_test_panel_field" style="width: 44%"><input type="text" id="panelG_site2_A" /></div>
                                <div class="all_test_panel_field" style="width: 14%"><input type="text" id="panelG_site2_W" /></div>
                                <div class="all_test_panel_field" style="width: 14%"><input type="text" id="panelG_site2_F" /></div>
                                <div class="all_test_panel_field" style="width: 9%"><input type="text" id="panelG_site2_C" /></div>
                                
                                <div class="all_test_panel_field" style="width: 14%">3</div>
                                <div class="all_test_panel_field" style="width: 44%"><input type="text" id="panelG_site3_A" /></div>
                                <div class="all_test_panel_field" style="width: 14%"><input type="text" id="panelG_site3_W" /></div>
                                <div class="all_test_panel_field" style="width: 14%"><input type="text" id="panelG_site3_F" /></div>
                                <div class="all_test_panel_field" style="width: 9%"><input type="text" id="panelG_site3_C" /></div>
                                
                                <div class="all_test_panel_field" style="width: 14%">4</div>
                                <div class="all_test_panel_field" style="width: 44%"><input type="text" id="panelG_site4_A" /></div>
                                <div class="all_test_panel_field" style="width: 14%"><input type="text" id="panelG_site4_W" /></div>
                                <div class="all_test_panel_field" style="width: 14%"><input type="text" id="panelG_site4_F" /></div>
                                <div class="all_test_panel_field" style="width: 9%"><input type="text" id="panelG_site4_C" /></div>
                                
                                <div class="all_test_panel_field" style="width: 14%">5</div>
                                <div class="all_test_panel_field" style="width: 44%"><input type="text" id="panelG_site5_A" /></div>
                                <div class="all_test_panel_field" style="width: 14%"><input type="text" id="panelG_site5_W" /></div>
                                <div class="all_test_panel_field" style="width: 14%"><input type="text" id="panelG_site5_F" /></div>
                                <div class="all_test_panel_field" style="width: 9%"><input type="text" id="panelG_site5_C" /></div>
                            
                            </div>
                            <!-- End Panel Section G 1 -->
                            
                            <!-- Panel Section G 2 -->
                            <div id="AllergyTestPanelA1" class="all_test_panel">
                                <div class="all_test_panel_field"><strong>PANEL G</strong></div>
                                <div class="all_test_panel_field" style="width: 29%"><strong>Epicutaneous</strong></div>
                                <div class="all_test_panel_field" style="width: 9%"><strong>Class</strong></div>
                                
                                <div class="all_test_panel_field" style="width: 14%"><strong>Site</strong></div>
                                <div class="all_test_panel_field" style="width: 44%"><strong>Allergen</strong></div>
                                <div class="all_test_panel_field" style="width: 14%"><strong>W (mm)</strong></div>
                                <div class="all_test_panel_field" style="width: 14%"><strong>F</strong></div>
                                <div class="all_test_panel_field" style="width: 9%;"></div>
                                
                                <div class="all_test_panel_field" style="width: 14%">6</div>
                                <div class="all_test_panel_field" style="width: 44%"><input type="text" id="panelG_site6_A" /></div>
                                <div class="all_test_panel_field" style="width: 14%"><input type="text" id="panelG_site6_W" /></div>
                                <div class="all_test_panel_field" style="width: 14%"><input type="text" id="panelG_site6_F" /></div>
                                <div class="all_test_panel_field" style="width: 9%"><input type="text" id="panelG_site6_C" /></div>
                                
                                <div class="all_test_panel_field" style="width: 14%">7</div>
                                <div class="all_test_panel_field" style="width: 44%"><input type="text" id="panelG_site7_A" /></div>
                                <div class="all_test_panel_field" style="width: 14%"><input type="text" id="panelG_site7_W" /></div>
                                <div class="all_test_panel_field" style="width: 14%"><input type="text" id="panelG_site7_F" /></div>
                                <div class="all_test_panel_field" style="width: 9%"><input type="text" id="panelG_site7_C" /></div>
                                
                                <div class="all_test_panel_field" style="width: 14%">8</div>
                                <div class="all_test_panel_field" style="width: 44%"><input type="text" id="panelG_site8_A" /></div>
                                <div class="all_test_panel_field" style="width: 14%"><input type="text" id="panelG_site8_W" /></div>
                                <div class="all_test_panel_field" style="width: 14%"><input type="text" id="panelG_site8_F" /></div>
                                <div class="all_test_panel_field" style="width: 9%"><input type="text" id="panelG_site8_C" /></div>
                                
                                <div class="all_test_panel_field" style="width: 14%">9</div>
                                <div class="all_test_panel_field" style="width: 44%"><input type="text" id="panelG_site9_A" /></div>
                                <div class="all_test_panel_field" style="width: 14%"><input type="text" id="panelG_site9_W" /></div>
                                <div class="all_test_panel_field" style="width: 14%"><input type="text" id="panelG_site9_F" /></div>
                                <div class="all_test_panel_field" style="width: 9%"><input type="text" id="panelG_site9_C" /></div>
                                
                                <div class="all_test_panel_field" style="width: 14%">10</div>
                                <div class="all_test_panel_field" style="width: 44%"><input type="text" id="panelG_site10_A" /></div>
                                <div class="all_test_panel_field" style="width: 14%"><input type="text" id="panelG_site10_W" /></div>
                                <div class="all_test_panel_field" style="width: 14%"><input type="text" id="panelG_site10_F" /></div>
                                <div class="all_test_panel_field" style="width: 9%"><input type="text" id="panelG_site10_C" /></div>
                            
                            </div>
                            <!-- End Panel Section G 2 -->
                            <div style="margin-left: 1%; width: 99%; height: 40px; margin-top: 10px;">
                                <label>Epicutaneous Controls:
                                    <select id="all_test_epicutaneous_controls" style="margin-left: 10px; margin-bottom: 2px; margin-top: -2px;">
                                        <option Value="Positive">Positive</option>
                                        <option Value="Negative">Negative</option>
                                    </select>
                                </label>
                            </div>
                            <div style="margin-top: 75px; width: 300px; height: 200px; float: left; margin-left: 150px;">
                                <div class="sigPad" id="all_test_signature">
                                                
                                    <p class="drawItDesc" style="display: block;">signature</p>
                                    <ul class="sigNav" style="display: block;">
                                        <li class="drawIt"><a href="#draw-it" class="current">Please Sign here</a></li>
                                        <li class="clearButton" style="display: list-item;"><a href="#clear">Clear</a></li>
                                    </ul>
                                    <div class="sig sigWrapper current" style="display: block;">
                                        <div class="typed" style="display: none;"></div>
                                        <canvas class="pad" width="198" height="55"></canvas>
                                        <input type="hidden" name="output" class="output" value="">
                                    </div>
                                    
                                </div>
                            </div>
                            <div style="margin-left:50px;float:left;margin-top: 80px;">
                                <style>
                                    .capture_button{
                                        border-radius: 8px;
                                        background-color: #C3C3C3;
                                        color: #555;
                                        width: 200px;
                                        height: 30px;
                                        float: left;
                                        border-width: 0px;
                                        font-weight: bold;
                                        margin-bottom: 10px;
                                    }
                                    .capture_button:hover{
                                        background-color: #222;
                                        color: #FFF;
                                    }
                                </style>
                                <button type="submit" id="accept_sig_doctor" class="capture_button">Capture Physician Signature</button>
                                <div class="sigPad2 signed" id="all_test_doc_signed">
                                    
                                    <div class="sigWrapper">
                                     
                                      <canvas class="pad" width="198" height="55"></canvas>
                                    </div>
                                    <p style="text-align: center;"><em>Physician Signature</em></p>
                                </div>
                            </div>
                            <div style="margin-left:50px;float:left;margin-top: 80px;">
                                <button type="submit" id="accept_sig_patient" class="capture_button">Capture Patient Signature</button>
                                <div class="sigPad2 signed" id="all_test_pat_signed">
                                    
                                    <div class="sigWrapper">
                                     
                                      <canvas class="pad" width="198" height="55"></canvas>
                                    </div>
                                    <p style="text-align: center;"><em>Patient Signature</em></p>
                                </div>
                            </div>
                            <p style="float: left; margin-left: 1%; width: 99%">Diagnosis Code Allergic Rhinitis Due to:<span style="margin-left: 20px;">Pollen 477.0</span><span style="margin-left: 20px;">Animal or Dander 477.2</span><span style="margin-left: 20px;">Other 477.8</span><span style="margin-left: 20px;">Unspecified 477.9</span></p>
                            
                            
                        </div>
                        <center><input id="allerty_test_result_template" type="button" class="btn btn-primary" value="SAVE" style="margin: auto; margin-top: 7px;"></center>
                    </div>
                </div>
			</div>
                
                
                
                
                
                
            <div class="tab-pane" id="PatientTreatmentOptionsElectionForm">	
                <div style="margin:15px; margin-top:5px;">
					<div class="row-fluid"  style="">	            
						<div class="grid" style="padding:10px;height:830px;padding-left: 0px;">
							
							<div style="margin-left:20px; margin-top:20px; color: #3d93e0; font-size:20px; font-weight:bold; width:100%; ">Patient Treatment Options Election Form</div>
                            <style>
                                
                            </style>
                            <br/>
                            <div style="width: 98%; height: 500px; margin-left: 2%">
                                <p><strong>General:</strong> Immunotherapy has up to an 85% success rate. The practical alternatives include antihistamines and other medical treatments which have unknown results.
    Duration of Treatment: The average patient will be on allergy immunotherapy for a minimum of 2 years. This schedule is impossible to predict and will differ from patient to patient depending on what your allergies are, how severe they are, and how you tolerate treatment. Your treatment with immunotherapy will be more successful and pose less risk if you consistently receive your shots and/or SLIT according to your dosage log, which will be communicated to you by the medical personnel.<br/>
    <em>*Patient must initial one of the options listed below and sign this form before being scheduled for treatment*</em>
                                </p>
    <input type="text" id="in_office_injections" size="2" style="width: 40px; margin-right: 10px;" /><strong>IN-OFFICE INJECTIONS</strong>
    <p>I understand I must bring my EpiPen with me to all office visits in order to ensure I have filled the prescription or it is not expired or used in order to receive treatment medications. I understand that if I am not consistent in arriving at the appointed time for my allergy shots, I will not only decrease the success of my treatment but also increase my risk of having adverse reactions. If I consistently miss appointments I will be asked to change to an “at-home” therapy.<br/> <strong><em>REQUIRES 30 MINUTE WAIT IN-OFFICE FOLLOWING EACH TREATMENT FOR OBSERVATION PURPOSES.</em></strong><br/> Estimated treatment period 1-3 years.</p>
    <input type="text" id="at_home_injections" size="2" style="width: 40px; margin-right: 10px;" /><strong>AT-HOME INJECTIONS</strong>
    <p>For home immunotherapy injections , I agree to receive my first 4 treatments in my providers office for training and supervision purposes. I will be instructed in the self administration of the shots, recognition of anaphylactic reactions(s), use and training of an epinephrine auto injector, and have been instructed to call “911” for anaphylactic reaction(s). I also agree to always administer my shots as directed in the presence of another adult. I waive any liability for home use. I understand I must bring my EpiPen with me to all office visits in order to ensure I have filled the prescription or it is not expired or used in order to receive treatment medications. <br/></>Estimated treatment period 1-3 years.</p>
    <input type="text" id="at_home_oral_administration" size="2" style="width: 40px; margin-right: 10px;" /><strong>AT-HOME ORAL ADMINISTRATION (SLIT)</strong>
    <p>For home oral (SLIT) immunotherapy, I understand that oral (SLIT) therapy may take longer to achieve effective immunotherapy results. I have been instructed in the self administration of the treatment, recognition of anaphylactic reactions(s), use and training of an epinephrine auto injector, and have been instructed to call “911” for anaphylactic reaction(s). I also agree to always administer my treatment as directed in the presence of another adult. I waive any liability for home use. I understand I must bring my EpiPen with me to all office visits in order to ensure I have filled the prescription or it is not expired or used in order to receive treatment medications. <br/></>Estimated treatment period 2-5 years.</p>
                                
Patient Name: <input type="text" id="PTOEF_name" size="70" style="width: 250px; margin-right: 40px; margin-left: 5px;" /> Relationship to Patient: <input type="text" id="PTOEF_relationship" size="70" style="width: 250px; margin-right: 10px; margin-left: 5px;" />
<p>I understand that if I decide during my treatment that I would like to change to one of the other options of treatment I
may do so ONLY after the first set of ordered medications has been completed.</p>
                            </div>
                            <br/><br/>
                            <div style="margin-top: 75px; width: 300px; height: 200px; float: left; margin-left: 150px;">
                                <div class="sigPad" id="PTOEF_signature">
                                                
                                    <p class="drawItDesc" style="display: block;">signature</p>
                                    <ul class="sigNav" style="display: block;">
                                        <li class="drawIt"><a href="#draw-it" class="current">Please Sign here</a></li>
                                        <li class="clearButton" style="display: list-item;"><a href="#clear">Clear</a></li>
                                    </ul>
                                    <div class="sig sigWrapper current" style="display: block;">
                                        <div class="typed" style="display: none;"></div>
                                        <canvas class="pad" width="198" height="55"></canvas>
                                        <input type="hidden" name="output" class="output" value="">
                                    </div>
                                    
                                </div>
                            </div>
                            
                            
                            
                            <div style="margin-left:50px;float:left;margin-top: 80px;">
                                <button type="submit" id="accept_sig_patient_PTOEF" class="capture_button">Capture Patient Signature</button>
                                <div class="sigPad2 signed" id="PTOEF_pat_signed">
                                    
                                    <div class="sigWrapper">
                                     
                                      <canvas class="pad" width="198" height="55"></canvas>
                                    </div>
                                    <p style="text-align: center;"><em>Patient Signature</em></p>
                                </div>
                            </div>
                            <div style="margin-left:50px;float:left;margin-top: 80px;">
                                <button type="submit" id="accept_sig_witness_PTOEF" class="capture_button">Capture Witness Signature</button>
                                <div class="sigPad2 signed" id="PTOEF_wit_signed">
                                    
                                    <div class="sigWrapper">
                                     
                                      <canvas class="pad" width="198" height="55"></canvas>
                                    </div>
                                    <p style="text-align: center;"><em>Witness Signature</em></p>
                                </div>
                            </div>
                            
                            
                        </div>
                        <input id="PTOEF_template" type="button" class="btn btn-primary" value="SAVE" style="margin: auto; margin-top: 7px;">
                    </div>
                </div>
			</div>	
                
                
                
                
            <div class="tab-pane" id="TestingAuthorizationForm">	
                <div style="margin:15px; margin-top:5px;">
					<div class="row-fluid"  style="">	            
						<div class="grid" style="padding:10px;height:2200px;padding-left: 0px;">
							
							<div style="margin-left:20px; margin-top:20px; color: #3d93e0; font-size:20px; font-weight:bold; width:100%; ">Testing Authorization Form</div>
                            <style>
                                h4{
                                    font-size: 14px;
                                }
                            </style>
                            <br/>
                           
                            
                            <div style="width: 98%; height: 500px; margin-left: 2%">
                                <h4 style="text-align: center;">Skin Test Authorization</h4>
                                <p><strong><u>Skin Test</u>:</strong> Skin tests are methods of testing for allergic antibodies. A test consists of introducing small amounts of the suspected substance, or allergen, onto the skin and noting the development of a positive reaction (which consists of a wheal, swelling, or flare in the surrounding area of redness). The results are read at 15 to 20 minutes after the application of the allergen.</p>
                                
<p><strong>Please note that these reactions rarely occur but in the event a reaction would occur, the staff is fully trained and emergency equipment is available.</strong></p>
<p>After skin testing, you will consult with your physician or other health care professional who will make further recommendations regarding your treatment.</p>
<p>We request that you do not bring small children with you when you are scheduled for skin testing unless they are accompanied by another adult who can sit with them in the reception room.</p>
                                <p><strong>I have read the patient information sheet on allergy skin testing and understand it. The opportunity has been provided for me to ask questions regarding the potential side effects of allergy skin testing and these questions have been answered to my satisfaction. I understand that every precaution consistent with the best medical practice will be carried out to protect me against such reactions.</strong></p>
                                Patient Name: <input type="text" id="TAF_name" size="70" style="width: 250px; margin-right: 40px; margin-left: 5px;" /> Relationship to Patient: <input type="text" id="TAF_relationship" size="70" style="width: 250px; margin-right: 10px; margin-left: 5px;" />
                                <div width="height: 300px; width: 100%;">
                                    <div style="margin-top: 75px; width: 300px; height: 200px; float: left; margin-left: 150px;">
                                        <div class="sigPad" id="TAF_signature1">
                                                        
                                            <p class="drawItDesc" style="display: block;">signature</p>
                                            <ul class="sigNav" style="display: block;">
                                                <li class="drawIt"><a href="#draw-it" class="current">Please Sign here</a></li>
                                                <li class="clearButton" style="display: list-item;"><a href="#clear">Clear</a></li>
                                            </ul>
                                            <div class="sig sigWrapper current" style="display: block;">
                                                <div class="typed" style="display: none;"></div>
                                                <canvas class="pad" width="198" height="55"></canvas>
                                                <input type="hidden" name="output" class="output" value="">
                                            </div>
                                            
                                        </div>
                                    </div>
                                
                                    <style>
                                    .date_input{
                                        height: 20px;
                                        background-color: #FFF;
                                        float: left;
                                        padding: 10px;
                                    }
                                    </style>
                                
                                    <div style="margin-left:50px;float:left;margin-top: 80px;">
                                        <button type="submit" id="accept_sig_patient1_TAF" class="capture_button">Capture Patient Signature</button>
                                        <div class="sigPad2 signed" id="TAF_pat_signed1">
                                            
                                            <div class="sigWrapper">
                                             
                                              <canvas class="pad" width="198" height="55"></canvas>
                                            </div>
                                            <p style="text-align: center;"><em>Patient Signature</em></p>
                                            <label style="float: left; margin-right: 10px; margin-left: 10px;">Date: </label>
                                            <input type="text" id="sig_patient1_TAF_date" size="3" style="width: 130px; background-color: #FFF; padding: 10px;" />
                                        </div>
                                    </div>
                                    <div style="margin-left:50px;float:left;margin-top: 80px;">
                                        <button type="submit" id="accept_sig_witness1_TAF" class="capture_button">Capture Witness Signature</button>
                                        <div class="sigPad2 signed" id="TAF_wit_signed1">
                                            
                                            <div class="sigWrapper">
                                             
                                              <canvas class="pad" width="198" height="55"></canvas>
                                            </div>
                                            <p style="text-align: center;"><em>Witness Signature</em></p>
                                            <label style="float: left; margin-right: 10px; margin-left: 10px;">Date: </label>
                                            <input type="text" class="date_input" id="sig_witness1_TAF_date" size="3" style="width: 130px; background-color: #FFF; padding: 10px;" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div style="width: 98%; height: 640px; margin-left: 2%; float: left;">
                                        
                                <hr/>
                                <h3 style="text-align: center; font-size: 18px;">Clear Allergy Services</h4>
                                <h4 style="text-align: center; margin-top: -20px;"><u>Informed Consent for Allergy Testing</u></h4>
                                <br/>
                                <div class="all_test_info" style="width: 100%; height: 40px;">
                                    <label style="float: left; margin-right: 10px;">Patient Name: </label>
                                    <input type="text" id="TAF_patient_name" size="10" />
                                    
                                    <label style="float: left; margin-right: 10px; margin-left: 30px;">Date: </label>
                                    <input type="text" id="TAF_date" size="10" style="width: 130px; background-color: #FFF; padding: 10px;" />
                                    
                                </div>  
                                <p>The following consent is intended to improve communication with and education of patients. The following has been explained:</p>
                                <p>The purpose of these procedures is to test for allergies and prescribe treatment to help relieve the allergic symptoms.</p>
                                <p>Possible Risks: Possible risks associated with testing and treatment may include but are not limited to the following:</p>
                                <p style="padding-left: 3em;"><strong>Local Reactions:</strong> Burning, itching, bleeding, swelling and or hives, redness of skin, skin blistering/sloughing, and or possible infection at the injection puncture sight.</p>
                                <p style="padding-left: 3em;"><strong>Mild Systemic Reactions:</strong> Nasal congestion and or runny nose, skin rash, diarrhea, headache, itching of ears, nose, throat and or sneezing may occurring within 2 hour of the injection and/or puncture, as well as itchy, watery, or red eyes.</p>
                                <p style="padding-left: 3em;"><strong>More Severe Reactions:</strong> Wheezing, coughing, or shortness of breath; bronchial asthma, generalized hives (welts), swelling of tissue around the eyes, tongue or throat; stomach or uterine (menstrual type), cramps, possible miscarriage if pregnant.</p>
                                <p style="padding-left: 3em;"><strong>Rare and Severe Complications:</strong> abnormalities of the heartbeat, delayed response, loss of ability to maintain blood pressure and pulse, anaphylactic shock. Possibility of a severe reaction involving the heart, lungs, and blood vessels which, if untreated, could be fatal.</p>
                                <p style="padding-left: 3em;">1. Precautions to be taken: Experience has shown that the majority of reactions for allergy testing and or immunotherapy, which require emergency treatment, occur within 30 minutes of injection or puncture. It is for this reason that all patients who receive allergy injections in our office must remain in our designated waiting area for no less than 30 minutes or until checked by one of our technicians. If you choose to leave prior to the 30 minute waiting time, you do so against medical advice and then therefore accept all responsibility and liability for any subsequent reactions from your allergy shot. There is a possibility of a reaction occurring after a patient who receives there injection or skin testing leaves our office. It is vitally important that any such reaction be reported to the physician before receiving the next injection. If you are ever concerned about a reaction, you should return to our office or go to the local emergency room. <em>IF YOU ARE HAVING A LIFE THREATENING EMERGENCY, IMMEDIATELY CALL “911”</em>.</p>
                                <br/>
                                <label style="float: left; margin-right: 10px;">Patient Initials: </label>
                                <input type="text" id="TAF_patient_initials" size="3" style="width: 40px;" />
                                
                                 
                                
                                
                            </div>
                            
                            <div style="width: 98%; height: 800px; margin-left: 2%; float: left;">
                                        
                                <hr/>
                                <h4 style="text-align: center;"><u>Informed Consent for Allergy Testing and Immunotherapy Treatment</u></h4>   
                                <p style="text-align: center; margin-top: -10px;"><em>Do not sign this form until you have read it and fully understand its contents.</em></p>
<h4 style="text-align: center;">CONSENT TO TESTING</h4>
<p>I understand that the physician or medical personnel will rely on statements about me, my medical history, or other information provided to them in determining whether to perform the procedures / course of treatment for my condition. No guarantees or assurances have been made to me concerning the results of these procedures.</p>
<p>In the event unforeseen additional procedures are required as a result of my treatment, I consent to allow the medical personnel or physician to make the decision concerning and perform such additional procedures deemed medically necessary.</p>
                                
                                
                                <p><strong>I have been offered the opportunity to have a chaperone present at all times during my testing and treatment. It is my choice to have a chaperone: <br/><input type="text" id="TAF_chaperone_yes" size="2" style="width: 40px; margin-right: 10px;" /> YES <input type="text" id="TAF_chaperone_no" size="2" style="width: 40px; margin-right: 10px; margin-left: 10px;" /> NO (initial)</strong></p>
                                <p>
                                    <strong>Are you allergic to sterile alcohol or latex? If yes which one?
                                        <select id="TAF_allergy_select" style="margin-left: 10px;">
                                            <option value="none">None</option>
                                            <option value="Sterile Alcohol">Sterile Alcohol</option>
                                            <option value="Latex">Latex</option>
                                            <option value="Both">Both</option>
                                        </select>
                                    </strong>
                                </p>
                                
                                
                                <p><strong><u>Medical Notification:</u></strong></p>
                                <p><input type="text" id="TAF_beta_blockers" size="2" style="width: 40px; margin-right: 10px;" /> BETA BLOCKERS: I am aware that beta-blockers reduce the effectiveness off the EpiPen if needed for a severe reaction. I will notify my physician and allergy provider if at any time I begin taking beta-blockers.</p>
                                <p><input type="text" id="TAF_med_interactions" size="2" style="width: 40px; margin-right: 10px;" /> I am aware that certain medications including but not limited to beta-blockers, antihistamines, and high blood pressure medications; certain activities such as smoking; and certain medical conditions such as heart disease, stroke, pregnancy, hypertension, and asthma may suppress my test results and / or cause complications with my treatment and/or the effectiveness of using my Epi-Pen in the event of an allergic reaction. I have notified my physician of all of my medical conditions (including pregnancy) and medications prior to testing or treatment for immunotherapy. I agree to notify my allergy provider and physician if I have a change to any medications or medical conditions.
By signing this form, I acknowledge that I have read or have had this form read to me and that I fully understand its contents. I have been given the opportunity to ask questions and all of my questions have been answered satisfactorily. I voluntarily consent to have the physician and all medical personnel under the supervision and/or control of such physician to perform procedures described or otherwise referred to herein.</p>
                                Patient Name: <input type="text" id="TAF_patient_2" size="70" style="width: 250px; margin-right: 40px; margin-left: 5px;" /> Relationship to Patient: <input type="text" id="TAF_relationship2" size="70" style="width: 250px; margin-right: 10px; margin-left: 5px;" />
                                
                                <div width="height: 300px; width: 100%;">
                                    <div style="margin-top: 75px; width: 300px; height: 200px; float: left; margin-left: 150px;">
                                        <div class="sigPad" id="TAF_signature2">
                                                        
                                            <p class="drawItDesc" style="display: block;">signature</p>
                                            <ul class="sigNav" style="display: block;">
                                                <li class="drawIt"><a href="#draw-it" class="current">Please Sign here</a></li>
                                                <li class="clearButton" style="display: list-item;"><a href="#clear">Clear</a></li>
                                            </ul>
                                            <div class="sig sigWrapper current" style="display: block;">
                                                <div class="typed" style="display: none;"></div>
                                                <canvas class="pad" width="198" height="55"></canvas>
                                                <input type="hidden" name="output" class="output" value="">
                                            </div>
                                            
                                        </div>
                                    </div>
                                
                                    <div style="margin-left:50px;float:left;margin-top: 80px;">
                                        <button type="submit" id="accept_sig_patient2_TAF" class="capture_button">Capture Patient Signature</button>
                                        <div class="sigPad2 signed" id="TAF_pat_signed2">
                                            
                                            <div class="sigWrapper">
                                             
                                              <canvas class="pad" width="198" height="55"></canvas>
                                            </div>
                                            <p style="text-align: center;"><em>Patient Signature</em></p>
                                            <label style="float: left; margin-right: 10px; margin-left: 10px;">Date: </label>
                                            <input type="text" id="sig_patient2_TAF_date" size="3" style="width: 130px; background-color: #FFF; padding: 10px;" />
                                        </div>
                                    </div>
                                </div>
                                
                                
                                
                            
                                
                            </div>
                        </div>
                        <center><input id="TAF_template" type="button" class="btn btn-primary" value="SAVE" style="margin: auto; margin-top: 7px;"></center>
                    </div>
                </div>
			</div>       
                
                
                
                
                
				
			<div class="tab-pane" id="TabFinancialAgreement">		
				<div style="margin:15px; margin-top:5px;">
					<div class="row-fluid"  style="">	            
						<div class="grid" style="padding:10px;height:1000px">
							
							<div style="margin-left:20px; margin-top:20px; color: #3d93e0; font-size:20px; font-weight:bold; width:100%; ">Financial Responsibility Agreement </div>
								<hr>
								
								<p align="justify"> We appreciate the confidence you have shown in choosing us to provide for your immunotherapy and allergy needs. The service you have elected to participate in implies a financial responsibility on your part. This responsibility obligates you to ensure payment in full of your fees. As a courtesy, we spoke with 

								 <input type="text" style="background-color:transparent;border-style: solid; border-width: 0px 0px 1px 0px; border-color:black"/>(reference #: <input type="text" style="background-color:transparent;border-style: solid; border-width: 0px 0px 1px 0px; border-color:black"/>) at your insurance company to verify your coverage based on your contract with them and will bill your insurance carrier on your behalf. 
								 </p>
								<p align="justify">
									However, you are ultimately responsible for the payment of your bill. You are responsible for all that apply to your insurance contract at the time services are rendered. The following information was provided to us by your insurance provider based on the benefits associated with your contract:
								</p>
								<div>
								<div style="">
									<p align="justify">
										Co-payment   :  <input type="text" style="background-color:transparent;border-style: solid; border-width: 0px 0px 1px 0px; border-color:black"/>
									</p>
									<p align="justify">
										Deductible   :  <input type="text" style="background-color:transparent;border-style: solid; border-width: 0px 0px 1px 0px; border-color:black"/>
									</p>
									<p align="justify">
										Coinsurance  :  <input type="text" style="background-color:transparent;border-style: solid; border-width: 0px 0px 1px 0px; border-color:black"/>  
									</p>
								</div>
								<div style="border-style:solid;border-width: 1px 1px 1px 1px;">Please see the physicians front office and/or billing personnel for payment procedures.</div>
								</div>
								<div style="margin-top:20px">
								<p align="justify">In the event your insurance company has additional stipulations that may affect your coverage, you have the choice to move forward with treatment. You are responsible for any amount not covered by your insurer.
									If your insurance carrier denies any part of your claim, or if you and your physician elect to continue therapy past your approved period, you will be responsible for your remaining account balance.
								</p>
								<p align="justify">
									
									I have read the above policy regarding my financial responsibility to my physician for providing immunotherapy and allergy services to the above named patient or me. I certify that the information provided is, to the best of my knowledge, true and accurate.
									I authorize my insurer to pay any benefits directly to my physician. If I need immunotherapy and decide to initiate treatment, I understand I will be given 72 hours to notify my provider then at that time my insurance will be billed entirely regardless of whether I continue therapy or not. 
									I also understand if I change insurance during the duration of the treatment that it is my responsibility to notify my physician immediately; otherwise I will be responsible for the remaining balance in full. Although benefits have been verified the quoted benefits may not be a guarantee of payment by the insurance carrier(s).
									I agree to pay my physician the full and entire amount of all bills incurred by me or the above named patient, if applicable, any amount due after payment has been made by my insurance carrier. The carrier will process the claim according to the policy and provisions outlined in your health insurance benefit.

								</p>
								</div >
								<div style="margin-top:20px">
										Patient Name: <input type="text" id="TAF_name" size="70" style="width: 250px; margin-right: 40px; margin-left: 5px;" /> Relationship to Patient: <input type="text" id="TAF_relationship" size="70" style="width: 250px; margin-right: 10px; margin-left: 5px;" />
									<div width="height: 300px; width: 100%;">
										<div style="margin-top: 75px; width: 300px; height: 200px; float: left; margin-left: 150px;">
											<div class="sigPad" id="TAF_signature1">
															
												<p class="drawItDesc" style="display: block;">signature</p>
												<ul class="sigNav" style="display: block;">
													<li class="drawIt"><a href="#draw-it" class="current">Please Sign here</a></li>
													<li class="clearButton" style="display: list-item;"><a href="#clear">Clear</a></li>
												</ul>
												<div class="sig sigWrapper current" style="display: block;">
													<div class="typed" style="display: none;"></div>
													<canvas class="pad" width="198" height="55"></canvas>
													<input type="hidden" name="output" class="output" value="">
												</div>
												
											</div>
										</div>
									
										<style>
										.date_input{
											height: 20px;
											background-color: #FFF;
											float: left;
											padding: 10px;
										}
										</style>
									
										<div style="margin-left:50px;float:left;margin-top: 80px;">
											<button type="submit" id="accept_sig_patient1_TAF" class="capture_button">Capture Patient Signature</button>
											<div class="sigPad2 signed" id="TAF_pat_signed1">
												
												<div class="sigWrapper">
												 
												  <canvas class="pad" width="198" height="55"></canvas>
												</div>
												<p style="text-align: center;"><em>Patient Signature</em></p>
												<label style="float: left; margin-right: 10px; margin-left: 10px;">Date: </label>
												<input type="text" id="sig_patient1_TAF_date" size="3" style="width: 130px; background-color: #FFF; padding: 10px;" />
											</div>
										</div>
										<div style="margin-left:50px;float:left;margin-top: 80px;">
											<button type="submit" id="accept_sig_witness1_TAF" class="capture_button">Capture Witness Signature</button>
											<div class="sigPad2 signed" id="TAF_wit_signed1">
												
												<div class="sigWrapper">
												 
												  <canvas class="pad" width="198" height="55"></canvas>
												</div>
												<p style="text-align: center;"><em>Witness Signature</em></p>
												<label style="float: left; margin-right: 10px; margin-left: 10px;">Date: </label>
												<input type="text" class="date_input" id="sig_witness1_TAF_date" size="3" style="width: 130px; background-color: #FFF; padding: 10px;" />
											</div>
										</div>
									</div>
								</div>
									
						</div>
					</div>
				</div>
			</div>	
                
                //My code
                            <div class="tab-pane" id="TabImmunizationTherapy">	
                <div style="margin:15px; margin-top:5px;">
					<div class="row-fluid"  style="">	            
						<div class="grid" style="padding:10px;height:2700px;padding-left: 0px;">
							
							<div style="margin-left:20px; margin-top:20px; color: #3d93e0; font-size:20px; font-weight:bold; width:100%; ">Immunotherapy Schedule</div>
                            <style>
                                h4{
                                    font-size: 14px;
                                }
                            </style>
                            <br/>
                            <div style="width: 98%; height: 640px; margin-left: 2%">
                            <table cellspacing="0" cellpadding"0" border="1">
<tr bgcolor="#C0C0C0"> 
<th colspan="8"> TAKE HOME **RED** Vial 1 Immunotherapy Schedule </th>
</tr>
<tr>
<th colspan="8">Name: <input type="text" id="Imm_thrpy_red_name" value="" style="border:hidden;"> 
		DOB: <input type="date" id="Imm_thrpy_red_dob" value="" style="border:hidden;">
		Date Started:<input type="date" id="Imm_thrpy_red_strt_date" value="" style="border:hidden;">
</th>
</tr>

<tr bgcolor="#C0C0C0">
<th colspan="8"><center> Vial 1 Dilution 1:1 Red </center> </th>
</tr>

<tr>
  <th style="width: 200;" colspan="2">Record Date and location of injection</th>
  <th colspan="2"> 1 Dose per week </th>
  <th colspan="1">Patient's Initial</th>
  <th colspan="1">Date</th>
  <th colspan="2">Reaction</th>
</tr>
<tr>
  <th colspan="2" align="left">1)<input type="text" id="Imm_thrpy_red_record1" value="" style="border:hidden;"></th>
  <th colspan="2"> 0.05 </th>
  <th colspan="1"><input type="text" id="Imm_thrpy_P_redinitial1" value="" style="border:hidden;"></th>
  <th colspan="1"><input type="date" id="Imm_thrpy_red_date_1" value="" style="border:hidden;"></th>
  <th colspan="2"><input type="text" id="Imm_thrpy_red_reaction_1" value="" style="border:hidden;"></th>
</tr>
<tr>
  <th colspan="2" align="left">2)<input type="text" id="Imm_thrpy_red_record2" value="" style="border:hidden;"></th>
  <th colspan="2"> 0.10 </th>
  <th colspan="1"><input type="text" id="Imm_thrpy_P_redinitial2" value="" style="border:hidden;"></th>
  <th colspan="1"><input type="date" id="Imm_thrpy_red_date_2" value="" style="border:hidden;"></th>
  <th colspan="2"><input type="text" id="Imm_thrpy_red_reaction_2" value="" style="border:hidden;"></th>
</tr>
<tr>
  <th colspan="2" align="left">3)<input type="text" id="Imm_thrpy_red_record3" value="" style="border:hidden;"></th>
  <th colspan="2"> 0.15 </th>
  <th colspan="1"><input type="text" id="Imm_thrpy_P_redinitial3" value="" style="border:hidden;"></th>
  <th colspan="1"><input type="date" id="Imm_thrpy_red_date_3" value="" style="border:hidden;"></th>
  <th colspan="2"><input type="text" id="Imm_thrpy_red_reaction_3" value="" style="border:hidden;"></th>
</tr>
<tr>
  <th colspan="2" align="left">4)<input type="text" id="Imm_thrpy_red_record4" value="" style="border:hidden;"></th>
  <th colspan="2"> 0.20 </th>
  <th colspan="1"><input type="text" id="Imm_thrpy_P_redinitial4" value="" style="border:hidden;"></th>
  <th colspan="1"><input type="date" id="Imm_thrpy_red_date_4" value="" style="border:hidden;"></th>
  <th colspan="2"><input type="text" id="Imm_thrpy_red_reaction_4" value="" style="border:hidden;"></th>
</tr>
<tr>
  <th colspan="2" align="left">5)<input type="text" id="Imm_thrpy_red_record5" value="" style="border:hidden;"></th>
  <th colspan="2"> 0.25 </th>
  <th colspan="1"><input type="text" id="Imm_thrpy_P_redinitial5" value="" style="border:hidden;"></th>
  <th colspan="1"><input type="date" id="Imm_thrpy_red_date_5" value="" style="border:hidden;"></th>
  <th colspan="2"><input type="text" id="Imm_thrpy_red_reaction_5" value="" style="border:hidden;"></th>
</tr>
<tr>
  <th colspan="2" align="left">6)<input type="text" id="Imm_thrpy_red_record6" value="" style="border:hidden;"></th>
  <th colspan="2"> 0.30 </th>
  <th colspan="1"><input type="text" id="Imm_thrpy_P_redinitial6" value="" style="border:hidden;"></th>
  <th colspan="1"><input type="date" id="Imm_thrpy_red_date_6" value="" style="border:hidden;"></th>
  <th colspan="2"><input type="text" id="Imm_thrpy_red_reaction_6" value="" style="border:hidden;"></th>
</tr>
<tr>
  <th colspan="2" align="left">7)<input type="text" id="Imm_thrpy_red_record7" value="" style="border:hidden;"></th>
  <th colspan="2"> 0.35 </th>
  <th colspan="1"><input type="text" id="Imm_thrpy_P_red_initial7" value="" style="border:hidden;"></th>
  <th colspan="1"><input type="date" id="Imm_thrpy_red_date_7" value="" style="border:hidden;"></th>
  <th colspan="2"><input type="text" id="Imm_thrpy_red_reaction_7" value="" style="border:hidden;"></th>
</tr>
<tr>
  <th colspan="2" align="left">8)<input type="text" id="Imm_thrpy_red_record8" value="" style="border:hidden;"></th>
  <th colspan="2"> 0.40 </th>
  <th colspan="1"><input type="text" id="Imm_thrpy_P_redinitial8" value="" style="border:hidden;"></th>
  <th colspan="1"><input type="date" id="Imm_thrpy_red_date_8" value="" style="border:hidden;"></th>
  <th colspan="2"><input type="text" id="Imm_thrpy_red_reaction_8" value="" style="border:hidden;"></th>
</tr>
<tr>
  <th colspan="2" align="left">9)<input type="text" id="Imm_thrpy_red_record9" value="" style="border:hidden;"></th>
  <th colspan="2"> 0.45 </th>
  <th colspan="1"><input type="text" id="Imm_thrpy_P_redinitial9" value="" style="border:hidden;"></th>
  <th colspan="1"><input type="date" id="Imm_thrpy_red_date_9" value="" style="border:hidden;"></th>
  <th colspan="2"><input type="text" id="Imm_thrpy_red_reaction_9" value="" style="border:hidden;"></th>
</tr>
<tr>
  <th colspan="2" align="left">10)<input type="text" id="Imm_thrpy_red_record10" value="" style="border:hidden;"></th>
  <th colspan="2"> 0.50 </th>
  <th colspan="1"><input type="text" id="Imm_thrpy_P_red_initial10" value="" style="border:hidden;"></th>
  <th colspan="1"><input type="date" id="Imm_thrpy_red_date_10" value="" style="border:hidden;"></th>
  <th colspan="2"><input type="text" id="Imm_thrpy_red_reaction_10" value="" style="border:hidden;"></th>
</tr>

<tr> 
<th colspan="8" align="left"> Patient Signature/Date Completed: <input type="text" id="Imm_thrpy_P_redsign" value="" style="border:hidden;"></th>
</tr>

<tr> 
<th colspan="8" align="left"> Staff Signature/Date Reviewed: <input type="text" id="Imm_thrpy_S_redsign" value="" style="border:hidden;"></th>
</tr>

<tr> 
<th colspan="8"> Remember to bring your EPI-PEN, BENADRYL AND SHARPS CONTAINER TO YOUR NEXT APPOINTMENT</br>ON<input type="date" id="Imm_thrpy_on_date_red" value="" style="border:hidden;"> @<input type="time" id="Imm_thrpy_at_time_red" value="" style="border:hidden;"> You will receive your next vial at this time. </th>
</tr>

</table>
                            </div>
                            
                            <div style="width: 98%; height: 640px; margin-left: 2%; float: left;">
                                        
                                <table cellspacing="0" cellpadding"0" border="1">
<tr bgcolor="#C0C0C0"> 
<th colspan="8"> TAKE HOME **YELLOW** Vial 2 Immunotherapy Schedule </th>
</tr>
<tr>
<th colspan="8">Name: <input type="text" id="Imm_thrpy_yel_name" value="" style="border:hidden;"> 
		DOB: <input type="date" id="Imm_thrpy_yel_dob" value="" style="border:hidden;">
		Date Started:<input type="date" id="Imm_thrpy_yel_strt_date" value="" style="border:hidden;">
</th>
</tr>

<tr bgcolor="#C0C0C0">
<th colspan="8"><center> Vial 2 Dilution 1:10 Yellow </center> </th>
</tr>

<tr>
  <th style="width: 200;" colspan="2">Record Date and location of injection</th>
  <th colspan="2"> 1 Dose per week </th>
  <th colspan="1">Patient's Initial</th>
  <th colspan="1">Date</th>
  <th colspan="2">Reaction</th>
</tr>
<tr>
  <th colspan="2" align="left">1)<input type="text" id="Imm_thrpy_yel_record1" value="" style="border:hidden;"></th>
  <th colspan="2"> 0.05 </th>
  <th colspan="1">&nbsp<input type="text" id="Imm_thrpy_P_yelinitial1" value="" style="border:hidden;"></th>
  <th colspan="1">&nbsp<input type="date" id="Imm_thrpy_yel_date_1" value="" style="border:hidden;"></th>
  <th colspan="2">&nbsp<input type="text" id="Imm_thrpy_yel_reaction_1" value="" style="border:hidden;"></th>
</tr>
<tr>
  <th colspan="2" align="left">2)<input type="text" id="Imm_thrpy_yel_record2" value="" style="border:hidden;"></th>
  <th colspan="2"> 0.10 </th>
  <th colspan="1">&nbsp<input type="text" id="Imm_thrpy_P_yelinitial2" value="" style="border:hidden;"></th>
  <th colspan="1">&nbsp<input type="date" id="Imm_thrpy_yel_date_2" value="" style="border:hidden;"></th>
  <th colspan="2">&nbsp<input type="text" id="Imm_thrpy_yel_reaction_2" value="" style="border:hidden;"></th>
</tr>
<tr>
  <th colspan="2" align="left">3)<input type="text" id="Imm_thrpy_yel_record3" value="" style="border:hidden;"></th>
  <th colspan="2"> 0.15 </th>
  <th colspan="1">&nbsp<input type="text" id="Imm_thrpy_P_yelinitial3" value="" style="border:hidden;"></th>
  <th colspan="1">&nbsp<input type="date" id="Imm_thrpy_yel_date_3" value="" style="border:hidden;"></th>
  <th colspan="2">&nbsp<input type="text" id="Imm_thrpy_yel_reaction_3" value="" style="border:hidden;"></th>
</tr>
<tr>
  <th colspan="2" align="left">4)<input type="text" id="Imm_thrpy_yel_record4" value="" style="border:hidden;"></th>
  <th colspan="2"> 0.20 </th>
  <th colspan="1">&nbsp<input type="text" id="Imm_thrpy_P_yelinitial4" value="" style="border:hidden;"></th>
  <th colspan="1">&nbsp<input type="date" id="Imm_thrpy_yel_date_4" value="" style="border:hidden;"></th>
  <th colspan="2">&nbsp<input type="text" id="Imm_thrpy_yel_reaction_4" value="" style="border:hidden;"></th>
</tr>
<tr>
  <th colspan="2" align="left">5)<input type="text" id="Imm_thrpy_yel_record5" value="" style="border:hidden;"></th>
  <th colspan="2"> 0.25 </th>
  <th colspan="1">&nbsp<input type="text" id="Imm_thrpy_P_yelinitial5" value="" style="border:hidden;"></th>
  <th colspan="1">&nbsp<input type="date" id="Imm_thrpy_yel_date_5" value="" style="border:hidden;"></th>
  <th colspan="2">&nbsp<input type="text" id="Imm_thrpy_yel_reaction_5" value="" style="border:hidden;"></th>
</tr>
<tr>
  <th colspan="2" align="left">6)<input type="text" id="Imm_thrpy_yel_record6" value="" style="border:hidden;"></th>
  <th colspan="2"> 0.30 </th>
  <th colspan="1">&nbsp<input type="text" id="Imm_thrpy_P_yelinitial6" value="" style="border:hidden;"></th>
  <th colspan="1">&nbsp<input type="date" id="Imm_thrpy_yel_date_6" value="" style="border:hidden;"></th>
  <th colspan="2">&nbsp<input type="text" id="Imm_thrpy_yel_reaction_6" value="" style="border:hidden;"></th>
</tr>
<tr>
  <th colspan="2" align="left">7)<input type="text" id="Imm_thrpy_yel_record7" value="" style="border:hidden;"></th>
  <th colspan="2"> 0.35 </th>
  <th colspan="1">&nbsp<input type="text" id="Imm_thrpy_P_yelinitial7" value="" style="border:hidden;"></th>
  <th colspan="1">&nbsp<input type="date" id="Imm_thrpy_yel_date_7" value="" style="border:hidden;"></th>
  <th colspan="2">&nbsp<input type="text" id="Imm_thrpy_yel_reaction_7" value="" style="border:hidden;"></th>
</tr>
<tr>
  <th colspan="2" align="left">8)<input type="text" id="Imm_thrpy_yel_record8" value="" style="border:hidden;"></th>
  <th colspan="2"> 0.40 </th>
  <th colspan="1">&nbsp<input type="text" id="Imm_thrpy_P_yelinitial8" value="" style="border:hidden;"></th>
  <th colspan="1">&nbsp<input type="date" id="Imm_thrpy_yel_date_8" value="" style="border:hidden;"></th>
  <th colspan="2">&nbsp<input type="text" id="Imm_thrpy_yel_reaction_8" value="" style="border:hidden;"></th>
</tr>
<tr>
  <th colspan="2" align="left">9)<input type="text" id="Imm_thrpy_yel_record9" value="" style="border:hidden;"></th>
  <th colspan="2"> 0.45 </th>
  <th colspan="1">&nbsp<input type="text" id="Imm_thrpy_P_yelinitial9" value="" style="border:hidden;"></th>
  <th colspan="1">&nbsp<input type="date" id="Imm_thrpy_yel_date_9" value="" style="border:hidden;"></th>
  <th colspan="2">&nbsp<input type="text" id="Imm_thrpy_yel_reaction_9" value="" style="border:hidden;"></th>
</tr>
<tr>
  <th colspan="2" align="left">10)<input type="text" id="Imm_thrpy_yel_record10" value="" style="border:hidden;"></th>
  <th colspan="2"> 0.50 </th>
  <th colspan="1">&nbsp<input type="text" id="Imm_thrpy_P_yelinitial10" value="" style="border:hidden;"></th>
  <th colspan="1">&nbsp<input type="date" id="Imm_thrpy_yel_date_10" value="" style="border:hidden;"></th>
  <th colspan="2">&nbsp<input type="text" id="Imm_thrpy_yel_reaction_10" value="" style="border:hidden;"></th>
</tr>

<tr> 
<th colspan="8" align="left"> Patient Signature/Date Completed: <input type="text" id="Imm_thrpy_P_yelsign" value="" style="border:hidden;"></th>
</tr>

<tr> 
<th colspan="8" align="left"> Staff Signature/Date Reviewed: <input type="text" id="Imm_thrpy_S_yelsign" value="" style="border:hidden;"></th>
</tr>

<tr> 
<th colspan="8"> Remember to bring your EPI-PEN BANADRYL AND CURRENT VIAL TO YOUR NEXT APPOINTMENT</br>ON<input type="date" id="Imm_thrpy_on_date_yel" value="" style="border:hidden;"> @<input type="time" id="Imm_thrpy_at_time_yel" value="" style="border:hidden;"> YOU WILL RECEIVE NEXT VIAL AT THIS TIME. </th>
</tr>

</table>
                                 
 </div>
                            
                            <div style="width: 98%; height: 500px; margin-left: 2%; float: left;">
                                        
                                <table cellspacing="0" cellpadding"0" border="1">
<tr bgcolor="#C0C0C0"> 
<th colspan="8"> TAKE HOME Vial 3 **BLUE** Immunotherapy Schedule </th>
</tr>
<tr>
<th colspan="8">Name: <input type="text" id="Imm_thrpy_blue_name" value="" style="border:hidden;"> 
		DOB: <input type="date" id="Imm_thrpy_blue_dob" value="" style="border:hidden;">
		Date Started:<input type="date" id="Imm_thrpy_blue_strt_date" value="" style="border:hidden;">
</th>
</tr>

<tr bgcolor="#C0C0C0">
<th colspan="8"><center> Vial 3 Dilution 1:100 Blue </center> </th>
</tr>

<tr>
  <th style="width: 200;" colspan="2">Record Date and location of injection</th>
  <th colspan="2"> 1 Dose per week </th>
  <th colspan="1">Patient's Initial</th>
  <th colspan="1">Date</th>
  <th colspan="2">Reaction</th>
</tr>
<tr>
  <th colspan="2" align="left">1)<input type="text" id="Imm_thrpy_blue_record1" value="" style="border:hidden;"></th>
  <th colspan="2"> 0.05 </th>
  <th colspan="1">&nbsp<input type="text" id="Imm_thrpy_P_blueinitial1" value="" style="border:hidden;"></th>
  <th colspan="1">&nbsp<input type="date" id="Imm_thrpy_blue_date_1" value="" style="border:hidden;"></th>
  <th colspan="2">&nbsp<input type="text" id="Imm_thrpy_blue_reaction_1" value="" style="border:hidden;"></th>
</tr>
<tr>
  <th colspan="2" align="left">2)<input type="text" id="Imm_thrpy_blue_record2" value="" style="border:hidden;"></th>
  <th colspan="2"> 0.10 </th>
  <th colspan="1">&nbsp<input type="text" id="Imm_thrpy_P_blueinitial2" value="" style="border:hidden;"></th>
  <th colspan="1">&nbsp<input type="date" id="Imm_thrpy_blue_date_2" value="" style="border:hidden;"></th>
  <th colspan="2">&nbsp<input type="text" id="Imm_thrpy_blue_reaction_2" value="" style="border:hidden;"></th>
</tr>
<tr>
  <th colspan="2" align="left">3)<input type="text" id="Imm_thrpy_blue_record3" value="" style="border:hidden;"></th>
  <th colspan="2"> 0.15 </th>
  <th colspan="1">&nbsp<input type="text" id="Imm_thrpy_P_blueinitial3" value="" style="border:hidden;"></th>
  <th colspan="1">&nbsp<input type="date" id="Imm_thrpy_blue_date_3" value="" style="border:hidden;"></th>
  <th colspan="2">&nbsp<input type="text" id="Imm_thrpy_blue_reaction_3" value="" style="border:hidden;"></th>
</tr>
<tr>
  <th colspan="2" align="left">4)<input type="text" id="Imm_thrpy_blue_record4" value="" style="border:hidden;"></th>
  <th colspan="2"> 0.20 </th>
  <th colspan="1">&nbsp<input type="text" id="Imm_thrpy_P_blueinitial4" value="" style="border:hidden;"></th>
  <th colspan="1">&nbsp<input type="date" id="Imm_thrpy_blue_date_4" value="" style="border:hidden;"></th>
  <th colspan="2">&nbsp<input type="text" id="Imm_thrpy_blue_reaction_4" value="" style="border:hidden;"></th>
</tr>
<tr>
  <th colspan="2" align="left">5)<input type="text" id="Imm_thrpy_blue_record5" value="" style="border:hidden;"></th>
  <th colspan="2"> 0.25 </th>
  <th colspan="1">&nbsp<input type="text" id="Imm_thrpy_P_blueinitial5" value="" style="border:hidden;"></th>
  <th colspan="1">&nbsp<input type="date" id="Imm_thrpy_blue_date_5" value="" style="border:hidden;"></th>
  <th colspan="2">&nbsp<input type="text" id="Imm_thrpy_blue_reaction_5" value="" style="border:hidden;"></th>
</tr>


<tr> 
<th colspan="8" align="left"> Patient Signature/Date Completed: <input type="text" id="Imm_thrpy_P_bluesign" value="" style="border:hidden;"></th>
</tr>

<tr> 
<th colspan="8" align="left"> Staff Signature/Date Reviewed: <input type="text" id="Imm_thrpy_S_bluesign" value="" style="border:hidden;"></th>
</tr>

<tr> 
<th colspan="8"> Remember to bring your EPI-PEN, BANADRYL AND CURRENT VIAL TO YOUR NEXT APPOINTMENT</br>ON<input type="date" id="Imm_thrpy_on_date_blue" value="" style="border:hidden;"> @<input type="time" id="Imm_thrpy_at_time_blue" value="" style="border:hidden;"> YOU WILL RECEIVE NEXT VIAL AT THIS TIME. </th>
</tr>

</table>
                            
                                
                         </div>
                                     <div style="width: 98%; height: 500px; margin-left: 2%; float: left;">
                                        
<table cellspacing="0" cellpadding"0" border="1">
<tr bgcolor="#C0C0C0"> 
<th colspan="8"> TAKE HOME Vial 4 **GREEN** Immunotherapy Schedule </th>
</tr>
<tr>
<th colspan="8">Name: <input type="text" id="Imm_thrpy_gr_name" value="" style="border:hidden;"> 
		DOB: <input type="date" id="Imm_thrpy_gr_dob" value="" style="border:hidden;">
		Date Started:<input type="date" id="Imm_thrpy_gr_strt_date" value="" style="border:hidden;">
</th>
</tr>

<tr bgcolor="#C0C0C0">
<th colspan="8"><center> Vial 4 Dilution 1:1000 GREEN </center> </th>
</tr>

<tr>
  <th style="width: 200;" colspan="2">Record Date and location of injection</th>
  <th colspan="2"> 1 Dose per week </th>
  <th colspan="1">Patient's Initial</th>
  <th colspan="1">Date</th>
  <th colspan="2">Reaction</th>
</tr>
<tr>
  <th colspan="2" align="left">1)<input type="text" id="Imm_thrpy_gr_record1" value="" style="border:hidden;"></th>
  <th colspan="2"> 0.05 </th>
  <th colspan="1">&nbsp<input type="text" id="Imm_thrpy_P_grinitial1" value="" style="border:hidden;"></th>
  <th colspan="1">&nbsp<input type="date" id="Imm_thrpy_gr_date_1" value="" style="border:hidden;"></th>
  <th colspan="2">&nbsp<input type="text" id="Imm_thrpy_gr_reaction_1" value="" style="border:hidden;"></th>
</tr>
<tr>
  <th colspan="2" align="left">2)<input type="text" id="Imm_thrpy_gr_record2" value="" style="border:hidden;"></th>
  <th colspan="2"> 0.10 </th>
  <th colspan="1">&nbsp<input type="text" id="Imm_thrpy_P_grinitial2" value="" style="border:hidden;"></th>
  <th colspan="1">&nbsp<input type="date" id="Imm_thrpy_gr_date_2" value="" style="border:hidden;"></th>
  <th colspan="2">&nbsp<input type="text" id="Imm_thrpy_gr_reaction_2" value="" style="border:hidden;"></th>
</tr>
<tr>
  <th colspan="2" align="left">3)<input type="text" id="Imm_thrpy_gr_record3" value="" style="border:hidden;"></th>
  <th colspan="2"> 0.15 </th>
  <th colspan="1">&nbsp<input type="text" id="Imm_thrpy_P_grinitial3" value="" style="border:hidden;"></th>
  <th colspan="1">&nbsp<input type="date" id="Imm_thrpy_gr_date_3" value="" style="border:hidden;"></th>
  <th colspan="2">&nbsp<input type="text" id="Imm_thrpy_gr_reaction_3" value="" style="border:hidden;"></th>
</tr>
<tr>
  <th colspan="2" align="left">4)<input type="text" id="Imm_thrpy_gr_record4" value="" style="border:hidden;"></th>
  <th colspan="2"> 0.20 </th>
  <th colspan="1">&nbsp<input type="text" id="Imm_thrpy_P_grinitial4" value="" style="border:hidden;"></th>
  <th colspan="1">&nbsp<input type="date" id="Imm_thrpy_gr_date_4" value="" style="border:hidden;"></th>
  <th colspan="2">&nbsp<input type="text" id="Imm_thrpy_gr_reaction_4" value="" style="border:hidden;"></th>
</tr>
<tr>
  <th colspan="2" align="left">5)<input type="text" id="Imm_thrpy_gr_record5" value="" style="border:hidden;"></th>
  <th colspan="2"> 0.25 </th>
  <th colspan="1">&nbsp<input type="text" id="Imm_thrpy_P_grinitial5" value="" style="border:hidden;"></th>
  <th colspan="1">&nbsp<input type="date" id="Imm_thrpy_gr_date_5" value="" style="border:hidden;"></th>
  <th colspan="2">&nbsp<input type="text" id="Imm_thrpy_gr_reaction_5" value="" style="border:hidden;"></th>
</tr>


<tr> 
<th colspan="8" align="left"> Patient Signature/Date Completed: <input type="text" id="Imm_thrpy_P_grsign" value="" style="border:hidden;"></th>
</tr>

<tr> 
<th colspan="8" align="left"> Staff Signature/Date Reviewed: <input type="text" id="Imm_thrpy_S_grsign" value="" style="border:hidden;"></th>
</tr>

<tr> 
<th colspan="8"> Remember to bring your EPI-PEN, BANADRYL AND CURRENT VIAL TO YOUR NEXT APPOINTMENT</br>ON<input type="date" id="Imm_thrpy_on_date_gr" value="" style="border:hidden;"> @<input type="time" id="Imm_thrpy_at_time_gr" value="" style="border:hidden;"> YOU WILL RECEIVE NEXT VIAL AT THIS TIME. </th>
</tr>

</table>            
                                
                         </div>
                                           <div style="width: 98%; height: 500px; margin-left: 2%; float: left;">
<table cellspacing="0" cellpadding"0" border="1">
<tr bgcolor="#C0C0C0"> 
<th colspan="8"> TRAINING PHASE/Vial 5 *SILVER* Immunotherapy Schedule </th>
</tr>
<tr>
<th colspan="8">Name: <input type="text" id="Imm_thrpy_sil_name" value="" style="border:hidden;"> 
		DOB: <input type="date" id="Imm_thrpy_sil_dob" value="" style="border:hidden;">
		Date Started:<input type="date" id="Imm_thrpy_sil_strt_date" value="" style="border:hidden;">
</th>
</tr>

<tr bgcolor="#C0C0C0">
<th colspan="8"><center> Vial 5 Dilution 1:10000 Silver </center> </th>
</tr>

<tr>
  <th style="width: 200;" colspan="2">Record Date and location of injection</th>
  <th colspan="2"> 1 Dose per week </th>
  <th colspan="1">Patient's Initial</th>
  <th colspan="1">Date</th>
  <th colspan="2">Reaction</th>
</tr>
<tr>
  <th colspan="2" align="left">1)<input type="text" id="Imm_thrpy_sil_record1" value="" style="border:hidden;"></th>
  <th colspan="2"> 0.05 </th>
  <th colspan="1">&nbsp<input type="text" id="Imm_thrpy_P_silinitial1" value="" style="border:hidden;"></th>
  <th colspan="1">&nbsp<input type="date" id="Imm_thrpy_sil_date_1" value="" style="border:hidden;"></th>
  <th colspan="2">&nbsp<input type="text" id="Imm_thrpy_sil_reaction_1" value="" style="border:hidden;"></th>
</tr>
<tr>
  <th colspan="2" align="left">2)<input type="text" id="Imm_thrpy_sil_record2" value="" style="border:hidden;"></th>
  <th colspan="2"> 0.10 </th>
  <th colspan="1">&nbsp<input type="text" id="Imm_thrpy_P_silinitial2" value="" style="border:hidden;"></th>
  <th colspan="1">&nbsp<input type="date" id="Imm_thrpy_sil_date_2" value="" style="border:hidden;"></th>
  <th colspan="2">&nbsp<input type="text" id="Imm_thrpy_sil_reaction_2" value="" style="border:hidden;"></th>
</tr>
<tr>
  <th colspan="2" align="left">3)<input type="text" id="Imm_thrpy_sil_record3" value="" style="border:hidden;"></th>
  <th colspan="2"> 0.15 </th>
  <th colspan="1">&nbsp<input type="text" id="Imm_thrpy_P_silinitial3" value="" style="border:hidden;"></th>
  <th colspan="1">&nbsp<input type="date" id="Imm_thrpy_sil_date_3" value="" style="border:hidden;"></th>
  <th colspan="2">&nbsp<input type="text" id="Imm_thrpy_sil_reaction_3" value="" style="border:hidden;"></th>
</tr>
<tr>
  <th colspan="2" align="left">4)<input type="text" id="Imm_thrpy_sil_record4" value="" style="border:hidden;"></th>
  <th colspan="2"> 0.20 </th>
  <th colspan="1">&nbsp<input type="text" id="Imm_thrpy_P_silinitial4" value="" style="border:hidden;"></th>
  <th colspan="1">&nbsp<input type="date" id="Imm_thrpy_sil_date_4" value="" style="border:hidden;"></th>
  <th colspan="2">&nbsp<input type="text" id="Imm_thrpy_sil_reaction_4" value="" style="border:hidden;"></th>
</tr>
<tr>
  <th colspan="2" align="left">5)<input type="text" id="Imm_thrpy_sil_record5" value="" style="border:hidden;"></th>
  <th colspan="2"> 0.25 </th>
  <th colspan="1">&nbsp<input type="text" id="Imm_thrpy_P_silinitial5" value="" style="border:hidden;"></th>
  <th colspan="1">&nbsp<input type="date" id="Imm_thrpy_sil_date_5" value="" style="border:hidden;"></th>
  <th colspan="2">&nbsp<input type="text" id="Imm_thrpy_sil_reaction_5" value="" style="border:hidden;"></th>
</tr>


<tr> 
<th colspan="8" align="left"> Patient Signature/Date Completed: <input type="text" id="Imm_thrpy_P_silsign" value="" style="border:hidden;"></th>
</tr>

<tr> 
<th colspan="8" align="left"> Staff Signature/Date Reviewed: <input type="text" id="Imm_thrpy_S_silsign" value="" style="border:hidden;"></th>
</tr>

</table>
<p><b>*Dispense GREEN vial @4th visit. PATIENT WILL BEGIN GREEN VIAL AFTER 5TH INJECTION FROM SILVER*</b></P>             
                                    
                            </div>
                    </div>
             <center><input id="Tab_Imm_Therapy" type="button" class="btn btn-primary" value="SAVE" style="margin: auto; margin-top: 7px;"></center>
                </div>
                </div>
                </div>

                
       //Mycode                         
      
                
    
				
			<div class="tab-pane" id="TabPastDX">		
				<div style="margin:15px; margin-top:5px;">
					<div class="row-fluid"  style="">	            
						<div class="grid" style="padding:10px;height:250px;overflow:auto">
							
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
			
			<div class="tab-pane" id="TabMedication">	
				<div style="margin:15px; margin-top:5px;">
					<div class="row-fluid"  style="">	            
						<div class="grid" style="padding:10px;height:250px;overflow:auto">
							
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
			
			<div class="tab-pane" id="TabImmunization">	
				<div style="margin:15px; margin-top:5px;">
					<div class="row-fluid"  style="">	            
						<div class="grid" style="padding:10px;height:250px;overflow:auto">
							
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
			
			<!--<div class="tab-pane" id="TabAllergies">	
				<div style="margin:15px; margin-top:5px;">
					<div class="row-fluid"  style="">	            
						<div class="grid" style="padding:10px;height:250px;overflow:auto">
							
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
			</div>-->
			
			<div class="tab-pane" id="TabNotes">	
				<div style="margin:15px; margin-top:5px;">
					<div class="row-fluid"  style="">	            
						<div class="grid" style="padding:10px;height:250px">
							
							<div style="margin-left:20px; margin-top:20px; color: #3d93e0; font-size:20px; font-weight:bold; width:100%; ">Physician Notes</div>
								<hr>
								
								
									
									
									<!--<div style="float:right; margin-right:20px;margin-top:10px;">-->
										<textarea id="textareanotes" name="editable1">
						
										</textarea>
									<!--</div>-->
								
									
						</div>
					</div>
				</div>
			</div>
			<!--<center>
					<input type="button" class="btn btn-primary" value="SAVE" onClick="savePatientData();" style="margin-top:7px;">	
			</center>-->
						
			
			</div>
			
			</div>
</div>
    <!--Content END-->
   <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    
	
	<script src="js/jquery.min.js"></script>
    <script src="js/jquery-ui.min.js"></script>
	<script src="js/signature/jquery.signaturepad.js"></script>
	
	<script src="js/signature/assets/json2.min.js"></script>
    <script src="js/zebra_datepicker.js"></script>
	
    <script type="text/javascript" >
	
	var $j = jQuery.noConflict();
	var popup_to_show;
	var current_id;
	var update_flag=false;
	var prev_pastdx = new Array();
	var prev_medications = new Array();
	var prev_immunizations = new Array();
	var prev_allergies = new Array();
	var prev_cp = new Array();
	 
	  
	var timeoutTime = 18000000;
	//var timeoutTime = 300000;  //5minutes
	var timeoutTimer = setTimeout(ShowTimeOutWarning, timeoutTime);


	var active_session_timer = 60000; //1minute
	var sessionTimer = setTimeout(inform_about_session, active_session_timer);

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
	
		function openCARefLog()	{
			$j('#Reflog_day').val('');
			$j('#Reflog_temp').val('');
			$j('#Reflog_min').val('');
			$j('#Reflog_max').val('');
			$j('#Reflog_cmt').val('');
			update_flag = false;
			$j('#BotonReflog').trigger('click');
		}
		
	
		function convertDateFormat(input)
		{
			//Input : Date in mm-dd-yy Format
			//Output: Date in yy-mm-dd Format
			var str = input.split('-');
			return str[2] + '-' + str[0] + '-' + str[1];
		}
		
		function displayalertnotification(message)
		{
  
			var gritterOptions = {
               title: 'status',
               text: message,
               sticky: false,
               time: '3000'
              };
			$j.gritter.add(gritterOptions);
    
		}

		var api=$j('.sigPad').signaturePad({drawOnly:true});
		var sig;
		$j('#accept_sig').click(function(event) {
			 
			sig = api.getSignature();
			alert(sig);
			$j('.sigPad2').signaturePad({displayOnly:true}).regenerate(sig);
		});
        
        var api2 = $j('#all_test_signature').signaturePad({drawOnly:true});
		var sig2;
		$j('#accept_sig_doctor').click(function(event) {
			sig2 = api2.getSignature();
			$j('#all_test_doc_signed').signaturePad({displayOnly:true}).regenerate(sig2);
		});
        $j('#accept_sig_patient').click(function(event) {
			sig2 = api2.getSignature();
			$j('#all_test_pat_signed').signaturePad({displayOnly:true}).regenerate(sig2);
		});
        var api3 = $j('#PTOEF_signature').signaturePad({drawOnly:true});
		var sig3;
        $j('#accept_sig_patient_PTOEF').click(function(event) {
			sig3 = api3.getSignature();
			$j('#PTOEF_pat_signed').signaturePad({displayOnly:true}).regenerate(sig3);
		});
        $j('#accept_sig_witness_PTOEF').click(function(event) {
			sig3 = api3.getSignature();
			$j('#PTOEF_wit_signed').signaturePad({displayOnly:true}).regenerate(sig3);
		});
        

        var api4 = $j('#TAF_signature1').signaturePad({drawOnly:true});
		var sig4; 
        $j('#accept_sig_patient1_TAF').click(function(event) {
			sig4 = api4.getSignature();
			$j('#TAF_pat_signed1').signaturePad({displayOnly:true}).regenerate(sig4);
		});
        $j('#accept_sig_witness1_TAF').click(function(event) {
			sig4 = api4.getSignature();
			$j('#TAF_wit_signed1').signaturePad({displayOnly:true}).regenerate(sig4);
		});
        
        var api5 = $j('#TAF_signature2').signaturePad({drawOnly:true});
		var sig5; 
        $j('#accept_sig_patient2_TAF').click(function(event) {
			sig5 = api5.getSignature();
			$j('#TAF_pat_signed2').signaturePad({displayOnly:true}).regenerate(sig5);
		});
        
        
        
        $j(document).ready(function() {
            $j('#all_test_result_dob').Zebra_DatePicker({format: 'M d, Y'});
            $j('#sig_witness1_TAF_date').Zebra_DatePicker({format: 'M d, Y'});
            $j('#sig_patient1_TAF_date').Zebra_DatePicker({format: 'M d, Y'});
            $j('#TAF_date').Zebra_DatePicker({format: 'M d, Y'});
            $j('#sig_patient2_TAF_date').Zebra_DatePicker({format: 'M d, Y'});
            
            setTimeout(function()
            {
                $j(".Zebra_DatePicker_Icon").css("top", "3px");
                $j(".Zebra_DatePicker_Icon").css("margin-left", "-22px");
            }, 100);
            
        });

		

		$j('#clear_allergy_temp').live('click',function(){
		
			
			//Get the values for pre-vitals signs
			var pre_patenctemp=$j('#Pre_PatEncTemp').val();
			//if(pre_patenctemp==null)pre_patenctemp;
			var pre_patencbp=$j('#Pre_PatEncBldPressure').val();
			var pre_patencpul=$j('#Pre_PatEncBldPulse').val();
			var pre_patencres=$j('#Pre_PatEncBldRes').val();
			alert(pre_patenctemp);
			//Get the values for Type of testing ordered
			var PatEnctypeOrd=$j('#PatEnctypeOrd').val();
			//Get list testing completed
			var PatEnclst1=$j('#PatEnclst1').val();
			var PatEnclst2=$j('#PatEnclst2').val();
			var PatEnclst3=$j('#PatEnclst3').val();
			var PatEnclst4=$j('#PatEnclst4').val();
			var PatEnclst5=$j('#PatEnclst5').val();
			var PatEnclst6=$j('#PatEnclst6').val();
			//Get body location of the test completed
			var PatEncLoc1=$j('#PatEncLoc1').val();
			var PatEncLoc2=$j('#PatEncLoc2').val();
			var PatEncLoc3=$j('#PatEncLoc3').val();
			var PatEncLoc4=$j('#PatEncLoc4').val();
			var PatEncLoc5=$j('#PatEncLoc5').val();
			var PatEncLoc6=$j('#PatEncLoc6').val();
			
			//get the patients test reaction
			var PatEncRea1=$j('#PatEncRea1').val();
			var PatEncRea2=$j('#PatEncRea2').val();
			var PatEncRea3=$j('#PatEncRea3').val();
			var PatEncRea4=$j('#PatEncRea4').val();
			var PatEncRea5=$j('#PatEncRea5').val();
			var PatEncRea6=$j('#PatEncRea6').val();
			//get the patients tolerate procedure
			var PatEncTolPrcopt=$j('#PatEncTolPrcopt').val();
			var PatEncTolPrc=$j('#PatEncTolPrc').val();
			
			//get the patients instructions given for avoidance strategies
			var PatEncavdstr=$j('#PatEncavdstr').val();
			//get the patients voice concerns
			var PatEncvoiceopt=$j('#PatEncvoiceopt').val();
			var PatEncvoice=$j('#PatEncvoice').val();
			//get the post-vitas signs
			
			var post_patenctemp=$j('#Post_PatEncTemp').val();
			var post_patencbp=$j('#Post_PatEncBldPressure').val();
			var post_patencpul=$j('#Post_PatEncBldPulse').val();
			var post_patencres=$j('#Post_PatEncBldRes').val();
			
			
			jsonObj = [];
			
			item = {}
			item["pre_patenctemp"] = pre_patenctemp;
			item["pre_patencbp"] = pre_patencbp;
			item["pre_patencpul"]=pre_patencpul;
			item["pre_patencres"]=pre_patencres;
			
			item["PatEnctypeOrd"]=PatEnctypeOrd;
			
			item["PatEnclst1"]=PatEnclst1;
			item["PatEnclst2"]=PatEnclst2;
			item["PatEnclst3"]=PatEnclst3;
			item["PatEnclst4"]=PatEnclst4;
			item["PatEnclst5"]=PatEnclst5;
			item["PatEnclst6"]=PatEnclst6;
			
			item["PatEncLoc1"]=PatEncLoc1;
			item["PatEncLoc2"]=PatEncLoc2;
			item["PatEncLoc3"]=PatEncLoc3;
			item["PatEncLoc4"]=PatEncLoc4;
			item["PatEncLoc5"]=PatEncLoc5;
			item["PatEncLoc6"]=PatEncLoc6;
			
			item["PatEncRea1"]=PatEncRea1;
			item["PatEncRea2"]=PatEncRea2;
			item["PatEncRea3"]=PatEncRea3;
			item["PatEncRea4"]=PatEncRea4;
			item["PatEncRea5"]=PatEncRea5;
			item["PatEncRea6"]=PatEncRea6;
			
			item["PatEncTolPrcopt"]=PatEncTolPrcopt;
			item["PatEncTolPrc"]=PatEncTolPrc;
			
			item["PatEncavdstr"]=PatEncavdstr;
			item["PatEncvoiceopt"]=PatEncvoiceopt;
			item["PatEncvoice"]=PatEncvoice;
			
			item["post_patenctemp"] = post_patenctemp;
			item["post_patencbp"] = post_patencbp;
			item["post_patencpul"]=post_patencpul;
			item["post_patencres"]=post_patencres;
						
			jsonObj.push(item);
			console.log(jsonObj);
			//var json = JSON.stringify(jsonArr);
			//var blar = JSON.parse(json);
			var URL='getCAPatEncFormData.php?json='+JSON.stringify(jsonObj);
            
			
			var Rectipo=LanzaAjax(URL);
			
			alert(Rectipo);
			/*$.ajax({
				URL: 'getCAPatEncFormData.php',
				type: 'post',
				data: JSON.stringify(jsonObj),
				contentType: 'application/json',
				dataType: 'json',
				success: function(data) {
                    /*if (typeof data == "string") {
                                RecTipo = data;
                                }*/
				/*	alert(data);
                }
					 
			});*//*KYLE
			
		});
        
        //Amtul
        $j('#Tab_Imm_Therapy').live('click',function(){
		
			
			//Get the values for pre-vitals signs
            //dates,dob,names and signatures
            var Imm_thrpy_red_name=$j('#Imm_thrpy_red_name').val();
            var Imm_thrpy_yel_name=$j('#Imm_thrpy_yel_name').val();
            var Imm_thrpy_blue_name=$j('#Imm_thrpy_blue_name').val();
            var Imm_thrpy_gr_name=$j('#Imm_thrpy_gr_name').val();
            var Imm_thrpy_sil_name=$j('#Imm_thrpy_sil_name').val();

            var Imm_thrpy_red_dob=$j('#Imm_thrpy_red_dob').val();
            var Imm_thrpy_yel_dob=$j('#Imm_thrpy_yel_dob').val();
            var Imm_thrpy_blue_dob=$j('#Imm_thrpy_blue_dob').val();
            var Imm_thrpy_gr_dob=$j('#Imm_thrpy_gr_dob').val();
            var Imm_thrpy_sil_dob=$j('#Imm_thrpy_sil_dob').val();
        
            var Imm_thrpy_red_strt_date=$j('#Imm_thrpy_red_strt_date').val();
            var Imm_thrpy_yel_strt_date=$j('#Imm_thrpy_yel_strt_date').val();
            var Imm_thrpy_blue_strt_date=$j('#Imm_thrpy_blue_strt_date').val();
            var Imm_thrpy_gr_strt_date=$j('#Imm_thrpy_gr_strt_date').val();
            var Imm_thrpy_sil_strt_date=$j('#Imm_thrpy_sil_strt_date').val();
            
            var Imm_thrpy_on_date_red=$j('#Imm_thrpy_on_date_red').val();
            var Imm_thrpy_at_time_red=$j('#Imm_thrpy_at_time_red').val();
            var Imm_thrpy_on_date_yel=$j('#Imm_thrpy_on_date_yel').val();
            var Imm_thrpy_at_time_yel=$j('#Imm_thrpy_at_time_yel').val();
            var Imm_thrpy_on_date_blue=$j('#Imm_thrpy_on_date_blue').val();
            var Imm_thrpy_at_time_blue=$j('#Imm_thrpy_at_time_blue').val();
            var Imm_thrpy_on_date_gr=$j('#Imm_thrpy_on_date_gr').val();
            var Imm_thrpy_at_time_gr=$j('#Imm_thrpy_at_time_gr').val();
            
            //red record
            var Imm_thrpy_red_record1=$j('#Imm_thrpy_red_record1').val();
            var Imm_thrpy_red_record2=$j('#Imm_thrpy_red_record2').val();
            var Imm_thrpy_red_record3=$j('#Imm_thrpy_red_record3').val();
            var Imm_thrpy_red_record4=$j('#Imm_thrpy_red_record4').val();
            var Imm_thrpy_red_record5=$j('#Imm_thrpy_red_record5').val();
            var Imm_thrpy_red_record6=$j('#Imm_thrpy_red_record6').val();
            var Imm_thrpy_red_record7=$j('#Imm_thrpy_red_record7').val();
            var Imm_thrpy_red_record8=$j('#Imm_thrpy_red_record8').val();
            var Imm_thrpy_red_record9=$j('#Imm_thrpy_red_record9').val();
            var Imm_thrpy_red_record10=$j('#Imm_thrpy_red_record10').val();
                //rec initials
            var Imm_thrpy_P_redinitial1=$j('#Imm_thrpy_P_redinitial1').val();
            var Imm_thrpy_P_redinitial2=$j('#Imm_thrpy_P_redinitial2').val();
            var Imm_thrpy_P_redinitial3=$j('#Imm_thrpy_P_redinitial3').val();
            var Imm_thrpy_P_redinitial4=$j('#Imm_thrpy_P_redinitial4').val();
            var Imm_thrpy_P_redinitial5=$j('#Imm_thrpy_P_redinitial5').val();
            var Imm_thrpy_P_redinitial6=$j('#Imm_thrpy_P_redinitial6').val();
            var Imm_thrpy_P_redinitial7=$j('#Imm_thrpy_P_redinitial7').val();
            var Imm_thrpy_P_redinitial8=$j('#Imm_thrpy_P_redinitial8').val();
            var Imm_thrpy_P_redinitial9=$j('#Imm_thrpy_P_redinitial9').val();
            var Imm_thrpy_P_redinitial10=$j('#Imm_thrpy_P_redinitial10').val();
                //rec dates
            var Imm_thrpy_red_date_1=$j('#Imm_thrpy_red_date_1').val();
            var Imm_thrpy_red_date_2=$j('#Imm_thrpy_red_date_2').val();
            var Imm_thrpy_red_date_3=$j('#Imm_thrpy_red_date_3').val();
            var Imm_thrpy_red_date_4=$j('#Imm_thrpy_red_date_4').val();
            var Imm_thrpy_red_date_5=$j('#Imm_thrpy_red_date_5').val();
            var Imm_thrpy_red_date_6=$j('#Imm_thrpy_red_date_6').val();
            var Imm_thrpy_red_date_7=$j('#Imm_thrpy_red_date_7').val();
            var Imm_thrpy_red_date_8=$j('#Imm_thrpy_red_date_8').val();
            var Imm_thrpy_red_date_9=$j('#Imm_thrpy_red_date_9').val();
            var Imm_thrpy_red_date_10=$j('#Imm_thrpy_red_date_10').val();
                // red rec reactions
            var Imm_thrpy_red_reaction_1=$j('#Imm_thrpy_red_reaction_1').val();
            var Imm_thrpy_red_reaction_2=$j('#Imm_thrpy_red_reaction_2').val();
            var Imm_thrpy_red_reaction_3=$j('#Imm_thrpy_red_reaction_3').val();
            var Imm_thrpy_red_reaction_4=$j('#Imm_thrpy_red_reaction_4').val();
            var Imm_thrpy_red_reaction_5=$j('#Imm_thrpy_red_reaction_5').val();
            var Imm_thrpy_red_reaction_6=$j('#Imm_thrpy_red_reaction_6').val();
            var Imm_thrpy_red_reaction_7=$j('#Imm_thrpy_red_reaction_7').val();
            var Imm_thrpy_red_reaction_8=$j('#Imm_thrpy_red_reaction_8').val();
            var Imm_thrpy_red_reaction_9=$j('#Imm_thrpy_red_reaction_9').val();
            var Imm_thrpy_red_reaction_10=$j('#Imm_thrpy_red_reaction_10').val();
            
            //yellow record
            var Imm_thrpy_yel_record1=$j('#Imm_thrpy_yel_record1').val();
            var Imm_thrpy_yel_record2=$j('#Imm_thrpy_yel_record2').val();
            var Imm_thrpy_yel_record3=$j('#Imm_thrpy_yel_record3').val();
            var Imm_thrpy_yel_record4=$j('#Imm_thrpy_yel_record4').val();
            var Imm_thrpy_yel_record5=$j('#Imm_thrpy_yel_record5').val();
            var Imm_thrpy_yel_record6=$j('#Imm_thrpy_yel_record6').val();
            var Imm_thrpy_yel_record7=$j('#Imm_thrpy_yel_record7').val();
            var Imm_thrpy_yel_record8=$j('#Imm_thrpy_yel_record8').val();
            var Imm_thrpy_yel_record9=$j('#Imm_thrpy_yel_record9').val();
            var Imm_thrpy_yel_record10=$j('#Imm_thrpy_yel_record10').val();
                //yel rec initials
            var Imm_thrpy_P_yelinitial1=$j('#Imm_thrpy_P_yelinitial1').val();
            var Imm_thrpy_P_yelinitial2=$j('#Imm_thrpy_P_yelinitial2').val();
            var Imm_thrpy_P_yelinitial3=$j('#Imm_thrpy_P_yelinitial3').val();
            var Imm_thrpy_P_yelinitial4=$j('#Imm_thrpy_P_yelinitial4').val();
            var Imm_thrpy_P_yelinitial5=$j('#Imm_thrpy_P_yelinitial5').val();
            var Imm_thrpy_P_yelinitial6=$j('#Imm_thrpy_P_yelinitial6').val();
            var Imm_thrpy_P_yelinitial7=$j('#Imm_thrpy_P_yelinitial7').val();
            var Imm_thrpy_P_yelinitial8=$j('#Imm_thrpy_P_yelinitial8').val();
            var Imm_thrpy_P_yelinitial9=$j('#Imm_thrpy_P_yelinitial9').val();
            var Imm_thrpy_P_yelinitial10=$j('#Imm_thrpy_P_yelinitial10').val();
                //yel rec dates
            var Imm_thrpy_yel_date_1=$j('#Imm_thrpy_yel_date_1').val();
            var Imm_thrpy_yel_date_2=$j('#Imm_thrpy_yel_date_2').val();
            var Imm_thrpy_yel_date_3=$j('#Imm_thrpy_yel_date_3').val();
            var Imm_thrpy_yel_date_4=$j('#Imm_thrpy_yel_date_4').val();
            var Imm_thrpy_yel_date_5=$j('#Imm_thrpy_yel_date_5').val();
            var Imm_thrpy_yel_date_6=$j('#Imm_thrpy_yel_date_6').val();
            var Imm_thrpy_yel_date_7=$j('#Imm_thrpy_yel_date_7').val();
            var Imm_thrpy_yel_date_8=$j('#Imm_thrpy_yel_date_8').val();
            var Imm_thrpy_yel_date_9=$j('#Imm_thrpy_yel_date_9').val();
            var Imm_thrpy_yel_date_10=$j('#Imm_thrpy_yel_date_10').val();
                    // yel rec reactions
            var Imm_thrpy_yel_reaction_1=$j('#Imm_thrpy_yel_reaction_1').val();
            var Imm_thrpy_yel_reaction_2=$j('#Imm_thrpy_yel_reaction_2').val();
            var Imm_thrpy_yel_reaction_3=$j('#Imm_thrpy_yel_reaction_3').val();
            var Imm_thrpy_yel_reaction_4=$j('#Imm_thrpy_yel_reaction_4').val();
            var Imm_thrpy_yel_reaction_5=$j('#Imm_thrpy_yel_reaction_5').val();
            var Imm_thrpy_yel_reaction_6=$j('#Imm_thrpy_yel_reaction_6').val();
            var Imm_thrpy_yel_reaction_7=$j('#Imm_thrpy_yel_reaction_7').val();
            var Imm_thrpy_yel_reaction_8=$j('#Imm_thrpy_yel_reaction_8').val();
            var Imm_thrpy_yel_reaction_9=$j('#Imm_thrpy_yel_reaction_9').val();
            var Imm_thrpy_yel_reaction_10=$j('#Imm_thrpy_yel_reaction_10').val();
            
            
            //blue record
            var Imm_thrpy_blue_record1=$j('#Imm_thrpy_blue_record1').val();
            var Imm_thrpy_blue_record2=$j('#Imm_thrpy_blue_record2').val();
            var Imm_thrpy_blue_record3=$j('#Imm_thrpy_blue_record3').val();
            var Imm_thrpy_blue_record4=$j('#Imm_thrpy_blue_record4').val();
            var Imm_thrpy_blue_record5=$j('#Imm_thrpy_blue_record5').val();
                
                //blue rec initials
            var Imm_thrpy_P_blueinitial1=$j('#Imm_thrpy_P_blueinitial1').val();
            var Imm_thrpy_P_blueinitial2=$j('#Imm_thrpy_P_blueinitial2').val();
            var Imm_thrpy_P_blueinitial3=$j('#Imm_thrpy_P_blueinitial3').val();
            var Imm_thrpy_P_blueinitial4=$j('#Imm_thrpy_P_blueinitial4').val();
            var Imm_thrpy_P_blueinitial5=$j('#Imm_thrpy_P_blueinitial5').val();
                //blue rec dates
            var Imm_thrpy_blue_date_1=$j('#Imm_thrpy_blue_date_1').val();
            var Imm_thrpy_blue_date_2=$j('#Imm_thrpy_blue_date_2').val();
            var Imm_thrpy_blue_date_3=$j('#Imm_thrpy_blue_date_3').val();
            var Imm_thrpy_blue_date_4=$j('#Imm_thrpy_blue_date_4').val();
            var Imm_thrpy_blue_date_5=$j('#Imm_thrpy_blue_date_5').val();
                // blue rec reactions
            var Imm_thrpy_blue_reaction_1=$j('#Imm_thrpy_blue_reaction_1').val();
            var Imm_thrpy_blue_reaction_2=$j('#Imm_thrpy_blue_reaction_2').val();
            var Imm_thrpy_blue_reaction_3=$j('#Imm_thrpy_blue_reaction_3').val();
            var Imm_thrpy_blue_reaction_4=$j('#Imm_thrpy_blue_reaction_4').val();
            var Imm_thrpy_blue_reaction_5=$j('#Imm_thrpy_blue_reaction_5').val();
            
            
            //green record
            var Imm_thrpy_gr_record1=$j('#Imm_thrpy_gr_record1').val();
            var Imm_thrpy_gr_record2=$j('#Imm_thrpy_gr_record2').val();
            var Imm_thrpy_gr_record3=$j('#Imm_thrpy_gr_record3').val();
            var Imm_thrpy_gr_record4=$j('#Imm_thrpy_gr_record4').val();
            var Imm_thrpy_gr_record5=$j('#Imm_thrpy_gr_record5').val();
                //green rec initials
            var Imm_thrpy_P_grinitial1=$j('#Imm_thrpy_P_grinitial1').val();
            var Imm_thrpy_P_grinitial2=$j('#Imm_thrpy_P_grinitial2').val();
            var Imm_thrpy_P_grinitial3=$j('#Imm_thrpy_P_grinitial3').val();
            var Imm_thrpy_P_grinitial4=$j('#Imm_thrpy_P_grinitial4').val();
            var Imm_thrpy_P_grinitial5=$j('#Imm_thrpy_P_grinitial5').val();
                //green rec dates
            var Imm_thrpy_gr_date_1=$j('#Imm_thrpy_gr_date_1').val();
            var Imm_thrpy_gr_date_2=$j('#Imm_thrpy_gr_date_2').val();
            var Imm_thrpy_gr_date_3=$j('#Imm_thrpy_gr_date_3').val();
            var Imm_thrpy_gr_date_4=$j('#Imm_thrpy_gr_date_4').val();
            var Imm_thrpy_gr_date_5=$j('#Imm_thrpy_gr_date_5').val();
                //green rec reactions
            var Imm_thrpy_gr_reaction_1=$j('#Imm_thrpy_gr_reaction_1').val();
            var Imm_thrpy_gr_reaction_2=$j('#Imm_thrpy_gr_reaction_2').val();
            var Imm_thrpy_gr_reaction_3=$j('#Imm_thrpy_gr_reaction_3').val();
            var Imm_thrpy_gr_reaction_4=$j('#Imm_thrpy_gr_reaction_4').val();
            var Imm_thrpy_gr_reaction_5=$j('#Imm_thrpy_gr_reaction_5').val();
            
            
            //silver record
            var Imm_thrpy_sil_record1=$j('#Imm_thrpy_sil_record1').val();
            var Imm_thrpy_sil_record2=$j('#Imm_thrpy_sil_record2').val();
            var Imm_thrpy_sil_record3=$j('#Imm_thrpy_sil_record3').val();
            var Imm_thrpy_sil_record4=$j('#Imm_thrpy_sil_record4').val();
            var Imm_thrpy_sil_record5=$j('#Imm_thrpy_sil_record5').val();
                //rec initials
            var Imm_thrpy_P_silinitial1=$j('#Imm_thrpy_P_silinitial1').val();
            var Imm_thrpy_P_silinitial2=$j('#Imm_thrpy_P_silinitial2').val();
            var Imm_thrpy_P_silinitial3=$j('#Imm_thrpy_P_silinitial3').val();
            var Imm_thrpy_P_silinitial4=$j('#Imm_thrpy_P_silinitial4').val();
            var Imm_thrpy_P_silinitial5=$j('#Imm_thrpy_P_silinitial5').val();
                //silver rec dates
            var Imm_thrpy_sil_date_1=$j('#Imm_thrpy_sil_date_1').val();
            var Imm_thrpy_sil_date_2=$j('#Imm_thrpy_sil_date_2').val();
            var Imm_thrpy_sil_date_3=$j('#Imm_thrpy_sil_date_3').val();
            var Imm_thrpy_sil_date_4=$j('#Imm_thrpy_sil_date_4').val();
            var Imm_thrpy_sil_date_5=$j('#Imm_thrpy_sil_date_5').val();            
                //silver rec reactions
            var Imm_thrpy_sil_reaction_1=$j('#Imm_thrpy_sil_reaction_1').val();
            var Imm_thrpy_sil_reaction_2=$j('#Imm_thrpy_sil_reaction_2').val();
            var Imm_thrpy_sil_reaction_3=$j('#Imm_thrpy_sil_reaction_3').val();
            var Imm_thrpy_sil_reaction_4=$j('#Imm_thrpy_sil_reaction_4').val();
            var Imm_thrpy_sil_reaction_5=$j('#Imm_thrpy_sil_reaction_5').val();
            
            
            
			
			
			jsonObj1 = [];
			
			item = {}
			item["Imm_thrpy_red_name"] = Imm_thrpy_red_name;
            item["Imm_thrpy_red_dob"] = Imm_thrpy_red_dob;
            item["Imm_thrpy_red_strt_date"] = Imm_thrpy_red_strt_date;
            item["Imm_thrpy_on_date_red"] = Imm_thrpy_on_date_red;
            item["Imm_thrpy_at_time_red"] = Imm_thrpy_at_time_red;
            item["Imm_thrpy_red_record1"] = Imm_thrpy_red_record1;
            item["Imm_thrpy_red_record2"] = Imm_thrpy_red_record2;
            item["Imm_thrpy_red_record3"] = Imm_thrpy_red_record3;
            item["Imm_thrpy_red_record4"] = Imm_thrpy_red_record4;
            item["Imm_thrpy_red_record5"] = Imm_thrpy_red_record5;
            item["Imm_thrpy_red_record6"] = Imm_thrpy_red_record6;
            item["Imm_thrpy_red_record7"] = Imm_thrpy_red_record7;
            item["Imm_thrpy_red_record8"] = Imm_thrpy_red_record8;
            item["Imm_thrpy_red_record9"] = Imm_thrpy_red_record9;
            item["Imm_thrpy_red_record10"] = Imm_thrpy_red_record10;
            item["Imm_thrpy_P_redinitial1"] = Imm_thrpy_P_redinitial1;
            item["Imm_thrpy_P_redinitial2"] = Imm_thrpy_P_redinitial2;
            item["Imm_thrpy_P_redinitial3"] = Imm_thrpy_P_redinitial3;
            item["Imm_thrpy_P_redinitial4"] = Imm_thrpy_P_redinitial4;
            item["Imm_thrpy_P_redinitial5"] = Imm_thrpy_P_redinitial5;
            item["Imm_thrpy_P_redinitial6"] = Imm_thrpy_P_redinitial6;
            item["Imm_thrpy_P_redinitial7"] = Imm_thrpy_P_redinitial7;
            item["Imm_thrpy_P_redinitial8"] = Imm_thrpy_P_redinitial8;
            item["Imm_thrpy_P_redinitial9"] = Imm_thrpy_P_redinitial9;
            item["Imm_thrpy_P_redinitial10"] = Imm_thrpy_P_redinitial10;
            item["Imm_thrpy_red_date_1"] = Imm_thrpy_red_date_1;
            item["Imm_thrpy_red_date_2"] = Imm_thrpy_red_date_2;
            item["Imm_thrpy_red_date_3"] = Imm_thrpy_red_date_3;
            item["Imm_thrpy_red_date_4"] = Imm_thrpy_red_date_4;
            item["Imm_thrpy_red_date_5"] = Imm_thrpy_red_date_5;
            item["Imm_thrpy_red_date_6"] = Imm_thrpy_red_date_6;
            item["Imm_thrpy_red_date_7"] = Imm_thrpy_red_date_7;
            item["Imm_thrpy_red_date_8"] = Imm_thrpy_red_date_8;
            item["Imm_thrpy_red_date_9"] = Imm_thrpy_red_date_9;
            item["Imm_thrpy_red_date_10"] = Imm_thrpy_red_date_10;
            item["Imm_thrpy_red_reaction_1"] = Imm_thrpy_red_reaction_1;
            item["Imm_thrpy_red_reaction_2"] = Imm_thrpy_red_reaction_2;
            item["Imm_thrpy_red_reaction_3"] = Imm_thrpy_red_reaction_3;
            item["Imm_thrpy_red_reaction_4"] = Imm_thrpy_red_reaction_4;
            item["Imm_thrpy_red_reaction_5"] = Imm_thrpy_red_reaction_5;
            item["Imm_thrpy_red_reaction_6"] = Imm_thrpy_red_reaction_6;
            item["Imm_thrpy_red_reaction_7"] = Imm_thrpy_red_reaction_7;
            item["Imm_thrpy_red_reaction_8"] = Imm_thrpy_red_reaction_8;
            item["Imm_thrpy_red_reaction_9"] = Imm_thrpy_red_reaction_9;
            item["Imm_thrpy_red_reaction_10"] = Imm_thrpy_red_reaction_10;
            
            
            item["Imm_thrpy_yel_name"] = Imm_thrpy_yel_name;
            item["Imm_thrpy_yel_dob"] = Imm_thrpy_yel_dob;
            item["Imm_thrpy_yel_strt_date"] = Imm_thrpy_yel_strt_date;
            item["Imm_thrpy_on_date_yel"] = Imm_thrpy_on_date_yel;
            item["Imm_thrpy_at_time_yel"] = Imm_thrpy_at_time_yel;
            item["Imm_thrpy_yel_record1"] = Imm_thrpy_yel_record1;
            item["Imm_thrpy_yel_record2"] = Imm_thrpy_yel_record2;
            item["Imm_thrpy_yel_record3"] = Imm_thrpy_yel_record3;
            item["Imm_thrpy_yel_record4"] = Imm_thrpy_yel_record4;
            item["Imm_thrpy_yel_record5"] = Imm_thrpy_yel_record5;
            item["Imm_thrpy_yel_record6"] = Imm_thrpy_yel_record6;
            item["Imm_thrpy_yel_record7"] = Imm_thrpy_yel_record7;
            item["Imm_thrpy_yel_record8"] = Imm_thrpy_yel_record8;
            item["Imm_thrpy_yel_record9"] = Imm_thrpy_yel_record9;
            item["Imm_thrpy_yel_record10"] = Imm_thrpy_yel_record10;
            item["Imm_thrpy_P_yelinitial1"] = Imm_thrpy_P_yelinitial1;
            item["Imm_thrpy_P_yelinitial2"] = Imm_thrpy_P_yelinitial2;
            item["Imm_thrpy_P_yelinitial3"] = Imm_thrpy_P_yelinitial3;
            item["Imm_thrpy_P_yelinitial4"] = Imm_thrpy_P_yelinitial4;
            item["Imm_thrpy_P_yelinitial5"] = Imm_thrpy_P_yelinitial5;
            item["Imm_thrpy_P_yelinitial6"] = Imm_thrpy_P_yelinitial6;
            item["Imm_thrpy_P_yelinitial7"] = Imm_thrpy_P_yelinitial7;
            item["Imm_thrpy_P_yelinitial8"] = Imm_thrpy_P_yelinitial8;
            item["Imm_thrpy_P_yelinitial9"] = Imm_thrpy_P_yelinitial9;
            item["Imm_thrpy_P_yelinitial10"] = Imm_thrpy_P_yelinitial10;
            item["Imm_thrpy_yel_date_1"] = Imm_thrpy_yel_date_1;
            item["Imm_thrpy_yel_date_2"] = Imm_thrpy_yel_date_2;
            item["Imm_thrpy_yel_date_3"] = Imm_thrpy_yel_date_3;
            item["Imm_thrpy_yel_date_4"] = Imm_thrpy_yel_date_4;
            item["Imm_thrpy_yel_date_5"] = Imm_thrpy_yel_date_5;
            item["Imm_thrpy_yel_date_6"] = Imm_thrpy_yel_date_6;
            item["Imm_thrpy_yel_date_7"] = Imm_thrpy_yel_date_7;
            item["Imm_thrpy_yel_date_8"] = Imm_thrpy_yel_date_8;
            item["Imm_thrpy_yel_date_9"] = Imm_thrpy_yel_date_9;
            item["Imm_thrpy_yel_date_10"] = Imm_thrpy_yel_date_10;
            item["Imm_thrpy_yel_reaction_1"] = Imm_thrpy_yel_reaction_1;
            item["Imm_thrpy_yel_reaction_2"] = Imm_thrpy_yel_reaction_2;
            item["Imm_thrpy_yel_reaction_3"] = Imm_thrpy_yel_reaction_3;
            item["Imm_thrpy_yel_reaction_4"] = Imm_thrpy_yel_reaction_4;
            item["Imm_thrpy_yel_reaction_5"] = Imm_thrpy_yel_reaction_5;
            item["Imm_thrpy_yel_reaction_6"] = Imm_thrpy_yel_reaction_6;
            item["Imm_thrpy_yel_reaction_7"] = Imm_thrpy_yel_reaction_7;
            item["Imm_thrpy_yel_reaction_8"] = Imm_thrpy_yel_reaction_8;
            item["Imm_thrpy_yel_reaction_9"] = Imm_thrpy_yel_reaction_9;
            item["Imm_thrpy_yel_reaction_10"] = Imm_thrpy_yel_reaction_10;

            item["Imm_thrpy_blueblue_name"] = Imm_thrpy_blue_name;
            item["Imm_thrpy_blue_dob"] = Imm_thrpy_blue_dob;
            item["Imm_thrpy_blue_strt_date"] = Imm_thrpy_blue_strt_date;
            item["Imm_thrpy_on_date_blue"] = Imm_thrpy_on_date_blue;
            item["Imm_thrpy_at_time_blue"] = Imm_thrpy_at_time_blue;
            item["Imm_thrpy_blue_record1"] = Imm_thrpy_blue_record1;
            item["Imm_thrpy_blue_record2"] = Imm_thrpy_blue_record2;
            item["Imm_thrpy_blue_record3"] = Imm_thrpy_blue_record3;
            item["Imm_thrpy_blue_record4"] = Imm_thrpy_blue_record4;
            item["Imm_thrpy_blue_record5"] = Imm_thrpy_blue_record5;
            item["Imm_thrpy_P_blueinitial1"] = Imm_thrpy_P_blueinitial1;
            item["Imm_thrpy_P_blueinitial2"] = Imm_thrpy_P_blueinitial2;
            item["Imm_thrpy_P_blueinitial3"] = Imm_thrpy_P_blueinitial3;
            item["Imm_thrpy_P_blueinitial4"] = Imm_thrpy_P_blueinitial4;
            item["Imm_thrpy_P_blueinitial5"] = Imm_thrpy_P_blueinitial5;
            item["Imm_thrpy_blue_date_1"] = Imm_thrpy_blue_date_1;
            item["Imm_thrpy_blue_date_2"] = Imm_thrpy_blue_date_2;
            item["Imm_thrpy_blue_date_3"] = Imm_thrpy_blue_date_3;
            item["Imm_thrpy_blue_date_4"] = Imm_thrpy_blue_date_4;
            item["Imm_thrpy_blue_date_5"] = Imm_thrpy_blue_date_5;
            item["Imm_thrpy_blue_reaction_1"] = Imm_thrpy_blue_reaction_1;
            item["Imm_thrpy_blue_reaction_2"] = Imm_thrpy_blue_reaction_2;
            item["Imm_thrpy_blue_reaction_3"] = Imm_thrpy_blue_reaction_3;
            item["Imm_thrpy_blue_reaction_4"] = Imm_thrpy_blue_reaction_4;
            item["Imm_thrpy_blue_reaction_5"] = Imm_thrpy_blue_reaction_5;
            
            
            item["Imm_thrpy_gr_name"] = Imm_thrpy_gr_name;
            item["Imm_thrpy_gr_dob"] = Imm_thrpy_gr_dob;
            item["Imm_thrpy_gr_strt_date"] = Imm_thrpy_gr_strt_date;
            item["Imm_thrpy_on_date_gr"] = Imm_thrpy_on_date_gr;
            item["Imm_thrpy_at_time_gr"] = Imm_thrpy_at_time_gr;
            item["Imm_thrpy_gr_record1"] = Imm_thrpy_gr_record1;
            item["Imm_thrpy_gr_record2"] = Imm_thrpy_gr_record2;
            item["Imm_thrpy_gr_record3"] = Imm_thrpy_gr_record3;
            item["Imm_thrpy_gr_record4"] = Imm_thrpy_gr_record4;
            item["Imm_thrpy_gr_record5"] = Imm_thrpy_gr_record5;
            item["Imm_thrpy_P_grinitial1"] = Imm_thrpy_P_grinitial1;
            item["Imm_thrpy_P_grinitial2"] = Imm_thrpy_P_grinitial2;
            item["Imm_thrpy_P_grinitial3"] = Imm_thrpy_P_grinitial3;
            item["Imm_thrpy_P_grinitial4"] = Imm_thrpy_P_grinitial4;
            item["Imm_thrpy_P_grinitial5"] = Imm_thrpy_P_grinitial5;
            item["Imm_thrpy_gr_date_1"] = Imm_thrpy_gr_date_1;
            item["Imm_thrpy_gr_date_2"] = Imm_thrpy_gr_date_2;
            item["Imm_thrpy_gr_date_3"] = Imm_thrpy_gr_date_3;
            item["Imm_thrpy_gr_date_4"] = Imm_thrpy_gr_date_4;
            item["Imm_thrpy_gr_date_5"] = Imm_thrpy_gr_date_5;
            item["Imm_thrpy_gr_reaction_1"] = Imm_thrpy_gr_reaction_1;
            item["Imm_thrpy_gr_reaction_2"] = Imm_thrpy_gr_reaction_2;
            item["Imm_thrpy_gr_reaction_3"] = Imm_thrpy_gr_reaction_3;
            item["Imm_thrpy_gr_reaction_4"] = Imm_thrpy_gr_reaction_4;
            item["Imm_thrpy_gr_reaction_5"] = Imm_thrpy_gr_reaction_5;

            
            item["Imm_thrpy_sil_name"] = Imm_thrpy_sil_name;
			item["Imm_thrpy_sil_dob"] = Imm_thrpy_sil_dob;
            item["Imm_thrpy_sil_strt_date"] = Imm_thrpy_sil_strt_date;
            item["Imm_thrpy_sil_record1"] = Imm_thrpy_sil_record1;
            item["Imm_thrpy_sil_record2"] = Imm_thrpy_sil_record2;
            item["Imm_thrpy_sil_record3"] = Imm_thrpy_sil_record3;
            item["Imm_thrpy_sil_record4"] = Imm_thrpy_sil_record4;
            item["Imm_thrpy_sil_record5"] = Imm_thrpy_sil_record5;
            item["Imm_thrpy_P_silinitial1"] = Imm_thrpy_P_silinitial1;
            item["Imm_thrpy_P_silinitial2"] = Imm_thrpy_P_silinitial2;
            item["Imm_thrpy_P_silinitial3"] = Imm_thrpy_P_silinitial3;
            item["Imm_thrpy_P_silinitial4"] = Imm_thrpy_P_silinitial4;
            item["Imm_thrpy_P_silinitial5"] = Imm_thrpy_P_silinitial5;
            item["Imm_thrpy_sil_date_1"] = Imm_thrpy_sil_date_1;
            item["Imm_thrpy_sil_date_2"] = Imm_thrpy_sil_date_2;
            item["Imm_thrpy_sil_date_3"] = Imm_thrpy_sil_date_3;
            item["Imm_thrpy_sil_date_4"] = Imm_thrpy_sil_date_4;
            item["Imm_thrpy_sil_date_5"] = Imm_thrpy_sil_date_5;
            item["Imm_thrpy_sil_reaction_1"] = Imm_thrpy_sil_reaction_1;
            item["Imm_thrpy_sil_reaction_2"] = Imm_thrpy_sil_reaction_2;
            item["Imm_thrpy_sil_reaction_3"] = Imm_thrpy_sil_reaction_3;
            item["Imm_thrpy_sil_reaction_4"] = Imm_thrpy_sil_reaction_4;
            item["Imm_thrpy_sil_reaction_5"] = Imm_thrpy_sil_reaction_5;

            
            jsonObj1.push(item);
			console.log(jsonObj1);
            
            
            var URL='ImmThrpySchdule.php?json='+JSON.stringify(jsonObj1);
			var Rectipo=LanzaAjax(URL);
			
			alert(Rectipo);
            });
            
            //Amtul
            
		
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
	
	
<!--<script type="text/javascript" src="js/jquery-2.0.0.min.js"></script>-->
<script type="text/javascript" src="js/jquery.smartWizard.js"></script>

<script src="realtime-notifications/lib/gritter/js/jquery.gritter.min.js"></script>
<link href="realtime-notifications/lib/gritter/css/jquery.gritter.css"rel="stylesheet" type="text/css" />

	

  </body>
</html>