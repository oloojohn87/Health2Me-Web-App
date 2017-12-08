<?php
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
// Connect to server and select databse.
$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }		

$result = $con->prepare("SELECT * FROM doctors where id=?");
$result->bindValue(1, $MEDID, PDO::PARAM_INT);
$result->execute();

$count=$result->rowCount();
$row = $result->fetch(PDO::FETCH_ASSOC);
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
     $sql=$con->prepare("SELECT * FROM tipopin");
     $q = $sql->execute();
     
     $Tipo[0]='N/A';
     while($row=$sql->fetch(PDO::FETCH_ASSOC)){
     	$Tipo[$row['Id']]=$row['NombreEng'];
     	$TipoAB[$row['Id']]=$row['NombreCorto'];
     	$TipoColor[$row['Id']]=$row['Color'];
     	$TipoIcon[$row['Id']]=$row['Icon'];
     	
     	$TipoColorGroup[$row['Agrup']]=$row['Color'];
     	$TipoIconGroup[$row['Agrup']]=$row['Icon'];
     }

	 
$query = $con->prepare("select * from emr_config where userid = ?");
$query->bindValue(1, $_SESSION['MEDID'], PDO::PARAM_INT);

$result = $query->execute();
$row = $result->fetch(PDO::FETCH_ASSOC); 

$enc_result = $con->prepare("select pass from encryption_pass where id = (select max(id) from encryption_pass)");
$enc_result->execute();

$row_enc = $enc_result->fetch(PDO::FETCH_ASSOC);
$enc_pass=$row_enc['pass'];

?>

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
    <link rel="stylesheet" href="css/toggle-switch.css">
	
	<link rel="stylesheet" type="text/css" href="js/uploadify/uploadify.css">
    <script type="text/javascript" src="js/uploadify/jquery.uploadify.min.js"></script> 
	
	<link href="js/signature/assets/jquery.signaturepad.css" rel="stylesheet">
    <?php
    if ($_SESSION['CustomLook']=="COL") { ?>
        <link href="css/styleCol.css" rel="stylesheet">
    <?php } ?>
  
   

   

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
				<li><a href="#TabFinancialAgreement" data-toggle="tab">Financial Agreement</a></li>
                <li><a href="#TabImmunizationTherapy" data-toggle="tab">Immunization Form</a></li>
                <li><a href="#TabOFCImmunizationTherapy" data-toggle="tab">In Office Immunization Form</a>
                <li><a href="#CA_IncidentLog" data-toggle="tab">Incident Log</a></li>
                <li><a href="#sharps_injury_log" data-toggle="tab" >Sharps Injury Log</a></li>
                <li><a href="#TabPatientNote" data-toggle="tab" >Patient Note</a></li>
				<li><a href="#TabInsuranceVerification" data-toggle="tab">Insurance Benefits Verification Form</a></li>
                <li><a href="#TabSLITLogBW" data-toggle="tab">SLITLogBW</a></li>
                <li><a href="#TabAllergyQuestionnaire" data-toggle="tab">Allergy Questionnaire</a></li>
						
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
								  <span style="margin-left:50px;"> Agency Name <input id="refrigeratorlog_agency_name" type="text" style="width:200px"> </span>
								  <span style="margin-left:50px;"> Location: <input id="refrigeratorlog_location" type="text" style="width:200px"> </span>
								  <span style="margin-left:50px;"> Month/Year: <input id="refrigeratorlog_mmyy" type="text" style="width:200px"> </span>
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
											<p style="margin-left:10px;">Blood Pressure:<input id="post_patencbp" style="margin-right:10px;float:right"/></p>
											<p style="margin-left:10px;">Pulse:<input id="post_patencpul" style="margin-right:10px;float:right"/></p>
											<p style="margin-left:10px;">Respirations:<input id="post_patencres" style="margin-right:10px;float:right"/></p>
																		
									</div>
								</div>
								
								<div style="margin-left:250px;float:left;margin-top:30px;">
										<!--<form method="post" action="" class="sigPad">-->	
										<div class="sigPad" id="PatEncSig">
											
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
										<div class="sigPad_patenc signed">
											
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
                                <div class="all_test_panel_field" style="width: 9%"><input type="text" id="panelF_site6_C" /></div>
                                
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
                                <div class="sigPad_all" id="all_test_signature">
                                                
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
               
                
      <!--
-->
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
                            <table id="abc" cellspacing="0" cellpadding"0" border="1">
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
  <th colspan="1"><input type="text" id="Imm_thrpy_P_redinitial7" value="" style="border:hidden;"></th>
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
 <div class="tab-pane" id="TabOFCImmunizationTherapy">	
                <div style="margin:15px; margin-top:5px;">
					<div class="row-fluid"  style="">	            
						<div class="grid" style="padding:10px;height:1300px;padding-left: 0px;">
							

                            <style>
                                h4{
                                    font-size: 14px;
                                }
                            </style>
                            <br/>
                            <div style="width: 98%; height: 640px; margin-left: 2%">
                            <table cellspacing="0" cellpadding"0" border="1">
<tr bgcolor="#C0C0C0"> 
<th colspan="7"> IN OFFICE WEEKLY INJECTABLE IMMUNOTHERAPY SCHEDULE </th>
</tr>

<tr>
<th colspan="7">Name: <input type="text" id="OFC_Imm_thrpy_name" value="" style="border:hidden;"> 
		DOB: <input type="date" id="OFC_Imm_thrpy_dob" value="" style="border:hidden;">
		Date Started:<input type="date" id="OFC_Imm_thrpy_strt_date" value="" style="border:hidden;">
</th>
</tr>

<tr bgcolor="#C0C0C0">
<th colspan="7"><center> Vial 5 Dilution 1:10000 Silver </center> </th>
</tr>

<tr>
  <th style="width: 200;" colspan="2">Record Date and location of injection</th>
  <th colspan="1"> 1 Dose per week </th>
  <th colspan="1">Patient's Initial</th>
  <th colspan="2">Reaction</th>
  <th colspan="1">Date</th>
</tr>
<tr>
  <th colspan="2" align="left">1)<input type="text" id="OFC_Imm_thrpy_sil_record1" value="" style="border:hidden;"></th>
  <th colspan="1"> 0.05 </th>
  <th colspan="1"> <input type="text" id="OFC_Imm_thrpy_P_silinitial1" value="" style="border:hidden;"></th>
  <th colspan="2"> <input type="text" id="OFC_Imm_thrpy_sil_reaction_1" value="" style="border:hidden;"></th>
  <th colspan="1"> <input type="text" id="OFC_Imm_thrpy_S_silinitial1" value="" style="border:hidden;"></th>
</tr>
<tr>
  <th colspan="2" align="left">2)<input type="text" id="OFC_Imm_thrpy_sil_record2" value="" style="border:hidden;"></th>
  <th colspan="1"> 0.10 </th>
  <th colspan="1"> <input type="text" id="OFC_Imm_thrpy_P_silinitial2" value="" style="border:hidden;"></th>
  <th colspan="2"> <input type="text" id="OFC_Imm_thrpy_sil_reaction_2" value="" style="border:hidden;"></th>
     <th colspan="1"> <input type="text" id="OFC_Imm_thrpy_S_silinitial2" value="" style="border:hidden;"></th>
</tr>
<tr>
  <th colspan="2" align="left">3)<input type="text" id="OFC_Imm_thrpy_sil_record3" value="" style="border:hidden;"></th>
  <th colspan="1"> 0.15 </th>
  <th colspan="1"> <input type="text" id="OFC_Imm_thrpy_P_silinitial3" value="" style="border:hidden;"></th>
  <th colspan="2"> <input type="text" id="OFC_Imm_thrpy_sil_reaction_3" value="" style="border:hidden;"></th>
     <th colspan="1"> <input type="text" id="OFC_Imm_thrpy_S_silinitial3" value="" style="border:hidden;"></th>
</tr>
<tr>
  <th colspan="2" align="left">4)<input type="text" id="OFC_Imm_thrpy_sil_record4" value="" style="border:hidden;"></th>
  <th colspan="1"> 0.20 </th>
  <th colspan="1"> <input type="text" id="OFC_Imm_thrpy_P_silinitial4" value="" style="border:hidden;"></th>
  <th colspan="2"> <input type="text" id="OFC_Imm_thrpy_sil_reaction_4" value="" style="border:hidden;"></th>
     <th colspan="1"> <input type="text" id="OFC_Imm_thrpy_S_silinitial4" value="" style="border:hidden;"></th>
</tr>
<tr>
  <th colspan="2" align="left">5)<input type="text" id="OFC_Imm_thrpy_sil_record5" value="" style="border:hidden;"></th>
  <th colspan="1"> 0.25 </th>
  <th colspan="1"> <input type="text" id="OFC_Imm_thrpy_P_silinitial5" value="" style="border:hidden;"></th>
  <th colspan="2"> <input type="text" id="OFC_Imm_thrpy_sil_reaction_5" value="" style="border:hidden;"></th>
<th colspan="1"> <input type="text" id="OFC_Imm_thrpy_S_silinitial5" value="" style="border:hidden;"></th>
</tr>

<tr bgcolor="#C0C0C0">
<th colspan="7"><center> Vial 4 Dilution 1:1000 Green </center> </th>
</tr>

<tr>
  <th colspan="2" align="left">1)<input type="text" id="OFC_Imm_thrpy_gr_record1" value="" style="border:hidden;"></th>
  <th colspan="1"> 0.05 </th>
  <th colspan="1"><input type="text" id="OFC_Imm_thrpy_P_grinitial1" value="" style="border:hidden;"></th>
  <th colspan="2"> <input type="text" id="OFC_Imm_thrpy_gr_reaction_1" value="" style="border:hidden;"></th>
    <th colspan="1"><input type="text" id="OFC_Imm_thrpy_S_grinitial1" value="" style="border:hidden;"></th>
</tr>
<tr>
  <th colspan="2" align="left">2)<input type="text" id="OFC_Imm_thrpy_gr_record2" value="" style="border:hidden;"></th>
  <th colspan="1"> 0.10 </th>
  <th colspan="1"> <input type="text" id="OFC_Imm_thrpy_P_grinitial2" value="" style="border:hidden;"></th>
  <th colspan="2"> <input type="text" id="OFC_Imm_thrpy_gr_reaction_2" value="" style="border:hidden;"></th>
    <th colspan="1"><input type="text" id="OFC_Imm_thrpy_S_grinitial2" value="" style="border:hidden;"></th>
</tr>
<tr>
  <th colspan="2" align="left">3)<input type="text" id="OFC_Imm_thrpy_gr_record3" value="" style="border:hidden;"></th>
  <th colspan="1"> 0.15 </th>
  <th colspan="1"> <input type="text" id="OFC_Imm_thrpy_P_grinitial3" value="" style="border:hidden;"></th>
  <th colspan="2"> <input type="text" id="OFC_Imm_thrpy_gr_reaction_3" value="" style="border:hidden;"></th>
    <th colspan="1"><input type="text" id="OFC_Imm_thrpy_S_grinitial3" value="" style="border:hidden;"></th>
</tr>
<tr>
  <th colspan="2" align="left">4)<input type="text" id="OFC_Imm_thrpy_gr_record4" value="" style="border:hidden;"></th>
  <th colspan="1"> 0.20 </th>
  <th colspan="1"> <input type="text" id="OFC_Imm_thrpy_P_grinitial4" value="" style="border:hidden;"></th>
  <th colspan="2"> <input type="text" id="OFC_Imm_thrpy_gr_reaction_4" value="" style="border:hidden;"></th>
    <th colspan="1"><input type="text" id="OFC_Imm_thrpy_S_grinitial4" value="" style="border:hidden;"></th>
</tr>
<tr>
  <th colspan="2" align="left">5)<input type="text" id="OFC_Imm_thrpy_gr_record5" value="" style="border:hidden;"></th>
  <th colspan="1"> 0.25 </th>
  <th colspan="1"> <input type="text" id="OFC_Imm_thrpy_P_grinitial5" value="" style="border:hidden;"></th>
  <th colspan="2"> <input type="text" id="OFC_Imm_thrpy_gr_reaction_5" value="" style="border:hidden;"></th>
    <th colspan="1"><input type="text" id="OFC_Imm_thrpy_S_grinitial5" value="" style="border:hidden;"></th>
</tr>

<tr bgcolor="#C0C0C0">
<th colspan="7"><center> Vial 3 Dilution 1:100 Blue </center> </th>
</tr>


<tr>
  <th colspan="2" align="left">1)<input type="text" id="OFC_Imm_thrpy_blue_record1" value="" style="border:hidden;"></th>
  <th colspan="1"> 0.05 </th>
  <th colspan="1"> <input type="text" id="OFC_Imm_thrpy_P_blueinitial1" value="" style="border:hidden;"></th>
  <th colspan="2"> <input type="text" id="OFC_Imm_thrpy_blue_reaction_1" value="" style="border:hidden;"></th>
    <th colspan="1"> <input type="text" id="OFC_Imm_thrpy_S_blueinitial1" value="" style="border:hidden;"></th>
</tr>
<tr>
  <th colspan="2" align="left">2)<input type="text" id="OFC_Imm_thrpy_blue_record2" value="" style="border:hidden;"></th>
  <th colspan="1"> 0.10 </th>
  <th colspan="1"> <input type="text" id="OFC_Imm_thrpy_P_blueinitial2" value="" style="border:hidden;"></th>
  <th colspan="2"> <input type="text" id="OFC_Imm_thrpy_blue_reaction_2" value="" style="border:hidden;"></th>
    <th colspan="1"> <input type="text" id="OFC_Imm_thrpy_S_blueinitial2" value="" style="border:hidden;"></th>
</tr>
<tr>
  <th colspan="2" align="left">3)<input type="text" id="OFC_Imm_thrpy_blue_record3" value="" style="border:hidden;"></th>
  <th colspan="1"> 0.15 </th>
  <th colspan="1"> <input type="text" id="OFC_Imm_thrpy_P_blueinitial3" value="" style="border:hidden;"></th>
  <th colspan="2"> <input type="text" id="OFC_Imm_thrpy_blue_reaction_3" value="" style="border:hidden;"></th>
    <th colspan="1"> <input type="text" id="OFC_Imm_thrpy_S_blueinitial3" value="" style="border:hidden;"></th>
</tr>
<tr>
  <th colspan="2" align="left">4)<input type="text" id="OFC_Imm_thrpy_blue_record4" value="" style="border:hidden;"></th>
  <th colspan="1"> 0.20 </th>
  <th colspan="1"> <input type="text" id="OFC_Imm_thrpy_P_blueinitial4" value="" style="border:hidden;"></th>
  <th colspan="2"> <input type="text" id="OFC_Imm_thrpy_blue_reaction_4" value="" style="border:hidden;"></th>
    <th colspan="1"> <input type="text" id="OFC_Imm_thrpy_S_blueinitial4" value="" style="border:hidden;"></th>
</tr>
<tr>
  <th colspan="2" align="left">5)<input type="text" id="OFC_Imm_thrpy_blue_record5" value="" style="border:hidden;"></th>
  <th colspan="1"> 0.25 </th>
  <th colspan="1"> <input type="text" id="OFC_Imm_thrpy_P_blueinitial5" value="" style="border:hidden;"></th>
  <th colspan="2"> <input type="text" id="OFC_Imm_thrpy_blue_reaction_5" value="" style="border:hidden;"></th>
    <th colspan="1"> <input type="text" id="OFC_Imm_thrpy_S_blueinitial5" value="" style="border:hidden;"></th>
</tr>

<tr bgcolor="#C0C0C0">
<th colspan="7"><center> Vial 2 Dilution 1:10 Yellow </center> </th>
</tr>


<tr>
  <th colspan="2" align="left">1)<input type="text" id="OFC_Imm_thrpy_yel_record1" value="" style="border:hidden;"></th>
  <th colspan="1"> 0.05 </th>
  <th colspan="1"> <input type="text" id="OFC_Imm_thrpy_P_yelinitial1" value="" style="border:hidden;"></th>
  <th colspan="2"> <input type="text" id="OFC_Imm_thrpy_yel_reaction_1" value="" style="border:hidden;"></th>
    <th colspan="1"> <input type="text" id="OFC_Imm_thrpy_S_yelinitial1" value="" style="border:hidden;"></th>
</tr>
<tr>
  <th colspan="2" align="left">2)<input type="text" id="OFC_Imm_thrpy_yel_record2" value="" style="border:hidden;"></th>
  <th colspan="1"> 0.10 </th>
  <th colspan="1"> <input type="text" id="OFC_Imm_thrpy_P_yelinitial2" value="" style="border:hidden;"></th>
  <th colspan="2"> <input type="text" id="OFC_Imm_thrpy_yel_reaction_2" value="" style="border:hidden;"></th>
    <th colspan="1"> <input type="text" id="OFC_Imm_thrpy_S_yelinitial2" value="" style="border:hidden;"></th>
</tr>
<tr>
  <th colspan="2" align="left">3)<input type="text" id="OFC_Imm_thrpy_yel_record3" value="" style="border:hidden;"></th>
  <th colspan="1"> 0.15 </th>
  <th colspan="1"> <input type="text" id="OFC_Imm_thrpy_P_yelinitial3" value="" style="border:hidden;"></th>
  <th colspan="2"> <input type="text" id="OFC_Imm_thrpy_yel_reaction_3" value="" style="border:hidden;"></th>
    <th colspan="1"> <input type="text" id="OFC_Imm_thrpy_S_yelinitial3" value="" style="border:hidden;"></th>
</tr>
<tr>
  <th colspan="2" align="left">4)<input type="text" id="OFC_Imm_thrpy_yel_record4" value="" style="border:hidden;"></th>
  <th colspan="1"> 0.20 </th>
  <th colspan="1"> <input type="text" id="OFC_Imm_thrpy_P_yelinitial4" value="" style="border:hidden;"></th>
  <th colspan="2"> <input type="text" id="OFC_Imm_thrpy_yel_reaction_4" value="" style="border:hidden;"></th>
    <th colspan="1"> <input type="text" id="OFC_Imm_thrpy_S_yelinitial4" value="" style="border:hidden;"></th>
</tr>
<tr>
  <th colspan="2" align="left">5)<input type="text" id="OFC_Imm_thrpy_yel_record5" value="" style="border:hidden;"></th>
  <th colspan="1"> 0.25 </th>
  <th colspan="1"> <input type="text" id="OFC_Imm_thrpy_P_yelinitial5" value="" style="border:hidden;"></th>
  <th colspan="2"> <input type="text" id="OFC_Imm_thrpy_yel_reaction_5" value="" style="border:hidden;"></th>
    <th colspan="1"> <input type="text" id="OFC_Imm_thrpy_S_yelinitial5" value="" style="border:hidden;"></th>
</tr>
<tr>
<tr>
  <th colspan="2" align="left">6)<input type="text" id="OFC_Imm_thrpy_yel_record6" value="" style="border:hidden;"></th>
  <th colspan="1"> 0.30 </th>
  <th colspan="1"> <input type="text" id="OFC_Imm_thrpy_P_yelinitial6" value="" style="border:hidden;"></th>
  <th colspan="2"> <input type="text" id="OFC_Imm_thrpy_yel_reaction_6" value="" style="border:hidden;"></th>
    <th colspan="1"> <input type="text" id="OFC_Imm_thrpy_S_yelinitial6" value="" style="border:hidden;"></th>
</tr>
<tr>
  <th colspan="2" align="left">7)<input type="text" id="OFC_Imm_thrpy_yel_record7" value="" style="border:hidden;"></th>
  <th colspan="1"> 0.35 </th>
  <th colspan="1"> <input type="text" id="OFC_Imm_thrpy_P_yelinitial7" value="" style="border:hidden;"></th>
  <th colspan="2"> <input type="text" id="OFC_Imm_thrpy_yel_reaction_7" value="" style="border:hidden;"></th>
    <th colspan="1"> <input type="text" id="OFC_Imm_thrpy_S_yelinitial7" value="" style="border:hidden;"></th>
</tr>
<tr>
  <th colspan="2" align="left">8)<input type="text" id="OFC_Imm_thrpy_yel_record8" value="" style="border:hidden;"></th>
  <th colspan="1"> 0.40 </th>
  <th colspan="1"> <input type="text" id="OFC_Imm_thrpy_P_yelinitial8" value="" style="border:hidden;"></th>
  <th colspan="2"> <input type="text" id="OFC_Imm_thrpy_yel_reaction_8" value="" style="border:hidden;"></th>
     <th colspan="1"> <input type="text" id="OFC_Imm_thrpy_S_yelinitial8" value="" style="border:hidden;"></th>
</tr>
<tr>
  <th colspan="2" align="left">9)<input type="text" id="OFC_Imm_thrpy_yel_record9" value="" style="border:hidden;"></th>
  <th colspan="1"> 0.45 </th>
  <th colspan="1"> <input type="text" id="OFC_Imm_thrpy_P_yelinitial9" value="" style="border:hidden;"></th>
  <th colspan="2"> <input type="text" id="OFC_Imm_thrpy_yel_reaction_9" value="" style="border:hidden;"></th>
     <th colspan="1"> <input type="text" id="OFC_Imm_thrpy_S_yelinitial9" value="" style="border:hidden;"></th>
</tr>
<tr>
  <th colspan="2" align="left">10)<input type="text" id="OFC_Imm_thrpy_yel_record10" value="" style="border:hidden;"></th>
  <th colspan="1"> 0.50 </th>
  <th colspan="1"> <input type="text" id="OFC_Imm_thrpy_P_yelinitial10" value="" style="border:hidden;"></th>
  <th colspan="2"> <input type="text" id="OFC_Imm_thrpy_yel_reaction_10" value="" style="border:hidden;"></th>
     <th colspan="1"> <input type="text" id="OFC_Imm_thrpy_S_yelinitial10" value="" style="border:hidden;"></th>
</tr> 



<tr bgcolor="#C0C0C0">
<th colspan="7"><center> Vial 1 Dilution 1:1 Red </center> </th>
</tr>


<tr>
  <th colspan="2" align="left">1)<input type="text" id="OFC_Imm_thrpy_red_record1" value="" style="border:hidden;"></th>
  <th colspan="1"> 0.05 </th>
  <th colspan="1"><input type="text" id="OFC_Imm_thrpy_P_redinitial1" value="" style="border:hidden;"></th>
  <th colspan="2"><input type="text" id="OFC_Imm_thrpy_red_reaction_1" value="" style="border:hidden;"></th>
      <th colspan="1"><input type="text" id="OFC_Imm_thrpy_S_redinitial1" value="" style="border:hidden;"></th>
</tr>
<tr>
  <th colspan="2" align="left">2)<input type="text" id="OFC_Imm_thrpy_red_record2" value="" style="border:hidden;"></th>
  <th colspan="1"> 0.10 </th>
  <th colspan="1"><input type="text" id="OFC_Imm_thrpy_P_redinitial2" value="" style="border:hidden;"></th>
  <th colspan="2"><input type="text" id="OFC_Imm_thrpy_red_reaction_2" value="" style="border:hidden;"></th>
          <th colspan="1"><input type="text" id="OFC_Imm_thrpy_S_redinitial2" value="" style="border:hidden;"></th>
</tr>
<tr>
  <th colspan="2" align="left">3)<input type="text" id="OFC_Imm_thrpy_red_record3" value="" style="border:hidden;"></th>
  <th colspan="1"> 0.15 </th>
  <th colspan="1"><input type="text" id="OFC_Imm_thrpy_P_redinitial3" value="" style="border:hidden;"></th>
  <th colspan="2"><input type="text" id="OFC_Imm_thrpy_red_reaction_3" value="" style="border:hidden;"></th>
          <th colspan="1"><input type="text" id="OFC_Imm_thrpy_S_redinitial3" value="" style="border:hidden;"></th>
</tr>
<tr>
  <th colspan="2" align="left">4)<input type="text" id="OFC_Imm_thrpy_red_record4" value="" style="border:hidden;"></th>
  <th colspan="1"> 0.20 </th>
  <th colspan="1"><input type="text" id="OFC_Imm_thrpy_P_redinitial4" value="" style="border:hidden;"></th>
  <th colspan="2"><input type="text" id="OFC_Imm_thrpy_red_reaction_4" value="" style="border:hidden;"></th>
          <th colspan="1"><input type="text" id="OFC_Imm_thrpy_S_redinitial4" value="" style="border:hidden;"></th>
</tr>
<tr>
  <th colspan="2" align="left">5)<input type="text" id="OFC_Imm_thrpy_red_record5" value="" style="border:hidden;"></th>
  <th colspan="1"> 0.25 </th>
  <th colspan="1"><input type="text" id="OFC_Imm_thrpy_P_redinitial5" value="" style="border:hidden;"></th>
  <th colspan="2"><input type="text" id="OFC_Imm_thrpy_red_reaction_5" value="" style="border:hidden;"></th>
          <th colspan="1"><input type="text" id="OFC_Imm_thrpy_S_redinitial5" value="" style="border:hidden;"></th>
</tr>
<tr>
  <th colspan="2" align="left">6)<input type="text" id="OFC_Imm_thrpy_red_record6" value="" style="border:hidden;"></th>
  <th colspan="1"> 0.30 </th>
  <th colspan="1"><input type="text" id="OFC_Imm_thrpy_P_redinitial6" value="" style="border:hidden;"></th>
  <th colspan="2"><input type="text" id="OFC_Imm_thrpy_red_reaction_6" value="" style="border:hidden;"></th>
          <th colspan="1"><input type="text" id="OFC_Imm_thrpy_S_redinitial6" value="" style="border:hidden;"></th>
</tr>
<tr>
  <th colspan="2" align="left">7)<input type="text" id="OFC_Imm_thrpy_red_record7" value="" style="border:hidden;"></th>
  <th colspan="1"> 0.35 </th>
  <th colspan="1"><input type="text" id="OFC_Imm_thrpy_P_redinitial7" value="" style="border:hidden;"></th>
  <th colspan="2"><input type="text" id="OFC_Imm_thrpy_red_reaction_7" value="" style="border:hidden;"></th>
  <th colspan="1"><input type="text" id="OFC_Imm_thrpy_S_redinitial7" value="" style="border:hidden;"></th>
</tr>
<tr>
  <th colspan="2" align="left">8)<input type="text" id="OFC_Imm_thrpy_red_record8" value="" style="border:hidden;"></th>
  <th colspan="1"> 0.40 </th>
  <th colspan="1"><input type="text" id="OFC_Imm_thrpy_P_redinitial8" value="" style="border:hidden;"></th>
  <th colspan="2"><input type="text" id="OFC_Imm_thrpy_red_reaction_8" value="" style="border:hidden;"></th>
          <th colspan="1"><input type="text" id="OFC_Imm_thrpy_S_redinitial8" value="" style="border:hidden;"></th>
</tr>
<tr>
  <th colspan="2" align="left">9)<input type="text" id="OFC_Imm_thrpy_red_record9" value="" style="border:hidden;"></th>
  <th colspan="1"> 0.45 </th>
  <th colspan="1"><input type="text" id="OFC_Imm_thrpy_P_redinitial9" value="" style="border:hidden;"></th>
  <th colspan="2"><input type="text" id="OFC_Imm_thrpy_red_reaction_9" value="" style="border:hidden;"></th>
          <th colspan="1"><input type="text" id="OFC_Imm_thrpy_S_redinitial9" value="" style="border:hidden;"></th>
</tr>
<tr>
  <th colspan="2" align="left">10)<input type="text" id="OFC_Imm_thrpy_red_record10" value="" style="border:hidden;"></th>
  <th colspan="1"> 0.50 </th>
  <th colspan="1"><input type="text" id="OFC_Imm_thrpy_P_redinitial10" value="" style="border:hidden;"></th>
  <th colspan="2"><input type="text" id="OFC_Imm_thrpy_red_reaction_10" value="" style="border:hidden;"></th>
  <th colspan="1"><input type="text" id="OFC_Imm_thrpy_S_redinitial10" value="" style="border:hidden;"></th>
</tr>
<tr> 
<th colspan="7" align="left"> Patient Signature/Date Completed: <input type="text" id="OFC_Imm_thrpy_P_silsign" value="" style="border:hidden;"></th>
</tr>

<tr> 
<th colspan="7" align="left"> Staff Signature/Date Reviewed: <input type="text" id="OFC_Imm_thrpy_S_silsign" value="" style="border:hidden;"></th>
</tr>
</table>
                            
  </div>
             
                </div>
                        <center><input id="OFC_Tab_Imm_Therapy" type="button" class="btn btn-primary" value="SAVE" style="margin: auto; margin-top: 7px;"></center>
                </div>
                </div>
     </div>
     
     <div class="tab-pane" id="CA_IncidentLog">	
                <div style="margin:15px; margin-top:5px;">
					<div class="row-fluid"  style="">	            
						<div class="grid" style="padding:10px;height:950px;padding-left: 0px;">
                            <style>
                                h4{
                                    font-size: 14px;
                                }
                            </style>
                            <br/>
                            <div style="width: 98%; height: 640px; margin-left: 2%">
                            <table cellspacing="0" cellpadding"0" border="1">
<tr bgcolor="yellow"> 
    <th colspan="10"><i>Physician:</i><input type="text" id="Incdnt_log_phy_name" bgcolor="yellow" value="" style="border:hidden;"> <I>CLEAR ALLERGY SERVICES INCIDENT LOG</i> </th>
</tr>
        
   <tr>
  <th style="width: 200;" colspan="2">Date</th>
  <th colspan="3">Describe incident </th>
  <th colspan="2">Notification Completed</th>
  <th colspan="1">Completed Incident Form</th>
  <th colspan="2">Notes/ Initials</th>
</tr>

<tr>
  <th colspan="2" align="left">1)<input type="date" id="Incdnt_log_date1" value="" style="border:hidden;"></th>
  <th colspan="3"> <input type="text" id="Incdnt_log_incident1" value="" style="border:hidden;"></th>
  <th colspan="2"><input type="text" id="Incdnt_log_notification1" value="" style="border:hidden;"></th>
  <th colspan="1"><input type="text" id="Incdnt_log_form1" value="" style="border:hidden;"></th>
  <th colspan="2"><input type="text" id="Incdnt_log_initials1" value="" style="border:hidden;"></th>
</tr>
       
<tr>
  <th colspan="2" align="left">2)<input type="date" id="Incdnt_log_date2" value="" style="border:hidden;"></th>
  <th colspan="3"> <input type="text" id="Incdnt_log_incident2" value="" style="border:hidden;"></th>
  <th colspan="2"><input type="text" id="Incdnt_log_notification2" value="" style="border:hidden;"></th>
  <th colspan="1"><input type="text" id="Incdnt_log_form2" value="" style="border:hidden;"></th>
  <th colspan="2"><input type="text" id="Incdnt_log_initials2" value="" style="border:hidden;"></th>
</tr>

<tr>
  <th colspan="2" align="left">3)<input type="date" id="Incdnt_log_date3" value="" style="border:hidden;"></th>
  <th colspan="3"> <input type="text" id="Incdnt_log_incident3" value="" style="border:hidden;"></th>
  <th colspan="2"><input type="text" id="Incdnt_log_notification3" value="" style="border:hidden;"></th>
  <th colspan="1"><input type="text" id="Incdnt_log_form3" value="" style="border:hidden;"></th>
  <th colspan="2"><input type="text" id="Incdnt_log_initials3" value="" style="border:hidden;"></th>
</tr>

<tr>
  <th colspan="2" align="left">4)<input type="date" id="Incdnt_log_date4" value="" style="border:hidden;"></th>
  <th colspan="3"> <input type="text" id="Incdnt_log_incident4" value="" style="border:hidden;"></th>
  <th colspan="2"><input type="text" id="Incdnt_log_notification4" value="" style="border:hidden;"></th>
  <th colspan="1"><input type="text" id="Incdnt_log_form4" value="" style="border:hidden;"></th>
  <th colspan="2"><input type="text" id="Incdnt_log_initials4" value="" style="border:hidden;"></th>
</tr>
                                
<tr>
  <th colspan="2" align="left">5)<input type="date" id="Incdnt_log_date5" value="" style="border:hidden;"></th>
  <th colspan="3"> <input type="text" id="Incdnt_log_incident5" value="" style="border:hidden;"></th>
  <th colspan="2"><input type="text" id="Incdnt_log_notification5" value="" style="border:hidden;"></th>
  <th colspan="1"><input type="text" id="Incdnt_log_form5" value="" style="border:hidden;"></th>
  <th colspan="2"><input type="text" id="Incdnt_log_initials5" value="" style="border:hidden;"></th>
</tr>
                                
<tr>
  <th colspan="2" align="left">6)<input type="date" id="Incdnt_log_date6" value="" style="border:hidden;"></th>
  <th colspan="3"> <input type="text" id="Incdnt_log_incident6" value="" style="border:hidden;"></th>
  <th colspan="2"><input type="text" id="Incdnt_log_notification6" value="" style="border:hidden;"></th>
  <th colspan="1"><input type="text" id="Incdnt_log_form6" value="" style="border:hidden;"></th>
  <th colspan="2"><input type="text" id="Incdnt_log_initials6" value="" style="border:hidden;"></th>
</tr>
                                
<tr>
  <th colspan="2" align="left">7)<input type="date" id="Incdnt_log_date7" value="" style="border:hidden;"></th>
  <th colspan="3"> <input type="text" id="Incdnt_log_incident7" value="" style="border:hidden;"></th>
  <th colspan="2"><input type="text" id="Incdnt_log_notification7" value="" style="border:hidden;"></th>
  <th colspan="1"><input type="text" id="Incdnt_log_form7" value="" style="border:hidden;"></th>
  <th colspan="2"><input type="text" id="Incdnt_log_initials7" value="" style="border:hidden;"></th>
</tr>
                                
<tr>
  <th colspan="2" align="left">8)<input type="date" id="Incdnt_log_date8" value="" style="border:hidden;"></th>
  <th colspan="3"> <input type="text" id="Incdnt_log_incident8" value="" style="border:hidden;"></th>
  <th colspan="2"><input type="text" id="Incdnt_log_notification8" value="" style="border:hidden;"></th>
  <th colspan="1"><input type="text" id="Incdnt_log_form8" value="" style="border:hidden;"></th>
  <th colspan="2"><input type="text" id="Incdnt_log_initials8" value="" style="border:hidden;"></th>
</tr>
                                
<tr>
  <th colspan="2" align="left">9)<input type="date" id="Incdnt_log_date9" value="" style="border:hidden;"></th>
  <th colspan="3"> <input type="text" id="Incdnt_log_incident9" value="" style="border:hidden;"></th>
  <th colspan="2"><input type="text" id="Incdnt_log_notification9" value="" style="border:hidden;"></th>
  <th colspan="1"><input type="text" id="Incdnt_log_form9" value="" style="border:hidden;"></th>
  <th colspan="2"><input type="text" id="Incdnt_log_initials9" value="" style="border:hidden;"></th>
</tr>
                                
<tr>
  <th colspan="2" align="left">10)<input type="date" id="Incdnt_log_date10" value="" style="border:hidden;"></th>
  <th colspan="3"> <input type="text" id="Incdnt_log_incident10" value="" style="border:hidden;"></th>
  <th colspan="2"><input type="text" id="Incdnt_log_notification10" value="" style="border:hidden;"></th>
  <th colspan="1"><input type="text" id="Incdnt_log_form10" value="" style="border:hidden;"></th>
  <th colspan="2"><input type="text" id="Incdnt_log_initials10" value="" style="border:hidden;"></th>
</tr>
                                
<tr>
  <th colspan="2" align="left">11)<input type="date" id="Incdnt_log_date11" value="" style="border:hidden;"></th>
  <th colspan="3"> <input type="text" id="Incdnt_log_incident11" value="" style="border:hidden;"></th>
  <th colspan="2"><input type="text" id="Incdnt_log_notification11" value="" style="border:hidden;"></th>
  <th colspan="1"><input type="text" id="Incdnt_log_form11" value="" style="border:hidden;"></th>
  <th colspan="2"><input type="text" id="Incdnt_log_initials11" value="" style="border:hidden;"></th>
</tr>
                                
<tr>
  <th colspan="2" align="left">12)<input type="date" id="Incdnt_log_date12" value="" style="border:hidden;"></th>
  <th colspan="3"> <input type="text" id="Incdnt_log_incident12" value="" style="border:hidden;"></th>
  <th colspan="2"><input type="text" id="Incdnt_log_notification12" value="" style="border:hidden;"></th>
  <th colspan="1"><input type="text" id="Incdnt_log_form12" value="" style="border:hidden;"></th>
  <th colspan="2"><input type="text" id="Incdnt_log_initials12" value="" style="border:hidden;"></th>
</tr>
                                
<tr>
  <th colspan="2" align="left">13)<input type="date" id="Incdnt_log_date13" value="" style="border:hidden;"></th>
  <th colspan="3"> <input type="text" id="Incdnt_log_incident13" value="" style="border:hidden;"></th>
  <th colspan="2"><input type="text" id="Incdnt_log_notification13" value="" style="border:hidden;"></th>
  <th colspan="1"><input type="text" id="Incdnt_log_form13" value="" style="border:hidden;"></th>
  <th colspan="2"><input type="text" id="Incdnt_log_initials13" value="" style="border:hidden;"></th>
</tr>
                                
<tr>
  <th colspan="2" align="left">14)<input type="date" id="Incdnt_log_date14" value="" style="border:hidden;"></th>
  <th colspan="3"> <input type="text" id="Incdnt_log_incident14" value="" style="border:hidden;"></th>
  <th colspan="2"><input type="text" id="Incdnt_log_notification14" value="" style="border:hidden;"></th>
  <th colspan="1"><input type="text" id="Incdnt_log_form14" value="" style="border:hidden;"></th>
  <th colspan="2"><input type="text" id="Incdnt_log_initials14" value="" style="border:hidden;"></th>
</tr>
                                
<tr>
  <th colspan="2" align="left">15)<input type="date" id="Incdnt_log_date15" value="" style="border:hidden;"></th>
  <th colspan="3"> <input type="text" id="Incdnt_log_incident15" value="" style="border:hidden;"></th>
  <th colspan="2"><input type="text" id="Incdnt_log_notification15" value="" style="border:hidden;"></th>
  <th colspan="1"><input type="text" id="Incdnt_log_form15" value="" style="border:hidden;"></th>
  <th colspan="2"><input type="text" id="Incdnt_log_initials15" value="" style="border:hidden;"></th>
</tr>
                                </table>
                                </div>
                                       </div>
                    
             <center><input id="CA_Incident_log" type="button" class="btn btn-primary" value="SAVE" style="margin: auto; margin-top: 7px;"></center>
                </div>
                </div>
                </div>
     
     <div class="tab-pane" id="sharps_injury_log">	
                <div style="margin:15px; margin-top:5px;">
					<div class="row-fluid"  style="">	            
						<div class="grid" style="padding:10px;height:950px;padding-left: 0px;">
							
                            <style>
                                h4{
                                    font-size: 14px;
                                }
                            </style>
                            <br/>
                            <div style="width: 98%; height: 640px; margin-left: 2%">
                            <table cellspacing="0" cellpadding"0" border="1">
<tr bgcolor="#33FFFF"> 
    <th colspan="10"><i>Physician:</i><input type="text" id="shrp_injury_phy_name" value="" style="border:hidden;"> <I>CLEAR ALLERGY SERVICES INCIDENT LOG</i> </th>
</tr>
        
   <tr>
  <th style="width: 200;" colspan="2">Date</th>
  <th colspan="3">Describe incident </th>
  <th colspan="2">Notification Completed</th>
  <th colspan="1">Completed Incident Form</th>
  <th colspan="2">Notes/ Initials</th>
</tr>

<tr>
  <th colspan="2" align="left">1)<input type="date" id="shrp_injury_date1" value="" style="border:hidden;"></th>
  <th colspan="3"> <input type="text" id="shrp_injury_incident1" value="" style="border:hidden;"></th>
  <th colspan="2"><input type="text" id="shrp_injury_notification1" value="" style="border:hidden;"></th>
  <th colspan="1"><input type="text" id="shrp_injury_form1" value="" style="border:hidden;"></th>
  <th colspan="2"><input type="text" id="shrp_injury_initials1" value="" style="border:hidden;"></th>
</tr>
       
<tr>
  <th colspan="2" align="left">2)<input type="date" id="shrp_injury_date2" value="" style="border:hidden;"></th>
  <th colspan="3"> <input type="text" id="shrp_injury_incident2" value="" style="border:hidden;"></th>
  <th colspan="2"><input type="text" id="shrp_injury_notification2" value="" style="border:hidden;"></th>
  <th colspan="1"><input type="text" id="shrp_injury_form2" value="" style="border:hidden;"></th>
  <th colspan="2"><input type="text" id="shrp_injury_initials2" value="" style="border:hidden;"></th>
</tr>

<tr>
  <th colspan="2" align="left">3)<input type="date" id="shrp_log_date3" value="" style="border:hidden;"></th>
  <th colspan="3"> <input type="text" id="shrp_injury_incident3" value="" style="border:hidden;"></th>
  <th colspan="2"><input type="text" id="shrp_injury_notification3" value="" style="border:hidden;"></th>
  <th colspan="1"><input type="text" id="shrp_injury_form3" value="" style="border:hidden;"></th>
  <th colspan="2"><input type="text" id="shrp_injury_initials3" value="" style="border:hidden;"></th>
</tr>

<tr>
  <th colspan="2" align="left">4)<input type="date" id="shrp_injury_date4" value="" style="border:hidden;"></th>
  <th colspan="3"> <input type="text" id="shrp_injury_incident4" value="" style="border:hidden;"></th>
  <th colspan="2"><input type="text" id="shrp_injury_notification4" value="" style="border:hidden;"></th>
  <th colspan="1"><input type="text" id="shrp_injury_form4" value="" style="border:hidden;"></th>
  <th colspan="2"><input type="text" id="shrp_injury_initials4" value="" style="border:hidden;"></th>
</tr>
                                
<tr>
  <th colspan="2" align="left">5)<input type="date" id="shrp_injury_date5" value="" style="border:hidden;"></th>
  <th colspan="3"> <input type="text" id="shrp_injury_incident5" value="" style="border:hidden;"></th>
  <th colspan="2"><input type="text" id="shrp_injury_notification5" value="" style="border:hidden;"></th>
  <th colspan="1"><input type="text" id="shrp_injury_form5" value="" style="border:hidden;"></th>
  <th colspan="2"><input type="text" id="shrp_injury_initials5" value="" style="border:hidden;"></th>
</tr>
                                
<tr>
  <th colspan="2" align="left">6)<input type="date" id="shrp_injury_date6" value="" style="border:hidden;"></th>
  <th colspan="3"> <input type="text" id="shrp_injury_incident6" value="" style="border:hidden;"></th>
  <th colspan="2"><input type="text" id="shrp_injury_notification6" value="" style="border:hidden;"></th>
  <th colspan="1"><input type="text" id="shrp_injury_form6" value="" style="border:hidden;"></th>
  <th colspan="2"><input type="text" id="shrp_injury_initials6" value="" style="border:hidden;"></th>
</tr>
                                
<tr>
  <th colspan="2" align="left">7)<input type="date" id="shrp_injury_date7" value="" style="border:hidden;"></th>
  <th colspan="3"> <input type="text" id="shrp_injury_incident7" value="" style="border:hidden;"></th>
  <th colspan="2"><input type="text" id="shrp_injury_notification7" value="" style="border:hidden;"></th>
  <th colspan="1"><input type="text" id="shrp_injury_form7" value="" style="border:hidden;"></th>
  <th colspan="2"><input type="text" id="shrp_injury_initials7" value="" style="border:hidden;"></th>
</tr>
                                
<tr>
  <th colspan="2" align="left">8)<input type="date" id="shrp_injury_date8" value="" style="border:hidden;"></th>
  <th colspan="3"> <input type="text" id="shrp_injury_incident8" value="" style="border:hidden;"></th>
  <th colspan="2"><input type="text" id="shrp_injury_notification8" value="" style="border:hidden;"></th>
  <th colspan="1"><input type="text" id="shrp_injury_form8" value="" style="border:hidden;"></th>
  <th colspan="2"><input type="text" id="shrp_injury_initials8" value="" style="border:hidden;"></th>
</tr>
                                
<tr>
  <th colspan="2" align="left">9)<input type="date" id="shrp_injury_date9" value="" style="border:hidden;"></th>
  <th colspan="3"> <input type="text" id="shrp_injury_incident9" value="" style="border:hidden;"></th>
  <th colspan="2"><input type="text" id="shrp_injury_notification9" value="" style="border:hidden;"></th>
  <th colspan="1"><input type="text" id="shrp_injury_form9" value="" style="border:hidden;"></th>
  <th colspan="2"><input type="text" id="shrp_injury_initials9" value="" style="border:hidden;"></th>
</tr>
                                
<tr>
  <th colspan="2" align="left">10)<input type="date" id="shrp_injury_date10" value="" style="border:hidden;"></th>
  <th colspan="3"> <input type="text" id="shrp_injury_incident10" value="" style="border:hidden;"></th>
  <th colspan="2"><input type="text" id="shrp_injury_notification10" value="" style="border:hidden;"></th>
  <th colspan="1"><input type="text" id="shrp_injury_form10" value="" style="border:hidden;"></th>
  <th colspan="2"><input type="text" id="shrp_injury_initials10" value="" style="border:hidden;"></th>
</tr>
                                
<tr>
  <th colspan="2" align="left">11)<input type="date" id="shrp_injury_date11" value="" style="border:hidden;"></th>
  <th colspan="3"> <input type="text" id="shrp_injury_incident11" value="" style="border:hidden;"></th>
  <th colspan="2"><input type="text" id="shrp_injury_notification11" value="" style="border:hidden;"></th>
  <th colspan="1"><input type="text" id="shrp_injury_form11" value="" style="border:hidden;"></th>
  <th colspan="2"><input type="text" id="shrp_injury_initials11" value="" style="border:hidden;"></th>
</tr>
                                
<tr>
  <th colspan="2" align="left">12)<input type="date" id="shrp_injury_date12" value="" style="border:hidden;"></th>
  <th colspan="3"> <input type="text" id="shrp_injury_incident12" value="" style="border:hidden;"></th>
  <th colspan="2"><input type="text" id="shrp_injury_notification12" value="" style="border:hidden;"></th>
  <th colspan="1"><input type="text" id="shrp_injury_form12" value="" style="border:hidden;"></th>
  <th colspan="2"><input type="text" id="shrp_injury_initials12" value="" style="border:hidden;"></th>
</tr>
                                
<tr>
  <th colspan="2" align="left">13)<input type="date" id="shrp_injury_date13" value="" style="border:hidden;"></th>
  <th colspan="3"> <input type="text" id="shrp_injury_incident13" value="" style="border:hidden;"></th>
  <th colspan="2"><input type="text" id="shrp_injury_notification13" value="" style="border:hidden;"></th>
  <th colspan="1"><input type="text" id="shrp_injury_form13" value="" style="border:hidden;"></th>
  <th colspan="2"><input type="text" id="shrp_injury_initials13" value="" style="border:hidden;"></th>
</tr>
                                
<tr>
  <th colspan="2" align="left">14)<input type="date" id="shrp_injury_date14" value="" style="border:hidden;"></th>
  <th colspan="3"> <input type="text" id="shrp_injury_incident14" value="" style="border:hidden;"></th>
  <th colspan="2"><input type="text" id="shrp_injury_notification14" value="" style="border:hidden;"></th>
  <th colspan="1"><input type="text" id="shrp_injury_form14" value="" style="border:hidden;"></th>
  <th colspan="2"><input type="text" id="shrp_injury_initials14" value="" style="border:hidden;"></th>
</tr>
                                
<tr>
  <th colspan="2" align="left">15)<input type="date" id="shrp_injury_date15" value="" style="border:hidden;"></th>
  <th colspan="3"> <input type="text" id="shrp_injury_incident15" value="" style="border:hidden;"></th>
  <th colspan="2"><input type="text" id="shrp_injury_notification15" value="" style="border:hidden;"></th>
  <th colspan="1"><input type="text" id="shrp_injury_form15" value="" style="border:hidden;"></th>
  <th colspan="2"><input type="text" id="shrp_injury_initials15" value="" style="border:hidden;"></th>
</tr>
                                </table>
                                </div>
                                       </div>
                    </div>
             <center><input id="SI_log" type="button" class="btn btn-primary" value="SAVE" style="margin: auto; margin-top: 7px;"></center>
                </div>
                </div>


<!--
<div class="tab-pane" id="TabPatientNote">	
                <div style="margin:15px; margin-top:5px;">
					<div class="row-fluid"  style="">	            
						<div class="grid" style="padding:10px;height:1300px;padding-left: 0px;">
							

                            <style>
                                h4{
                                    font-size: 14px;
                                }
                            </style>
                            <br/>
                     <div style="width: 98%; height: 640px; margin-left: 2%">
                            

                         <table cellspacing="0" cellpadding="0" border="1" id="PN_outer_table">
                                <tr>
                                    <th>Date</td>
                                    <th>Patient Encounter Form</td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <table cellspacing="0" cellpadding="0" border="1" id="PN_inner_table" style="background-color: #FFF; width: 100%;">
                                            <tr>
                                                <td  colspan="2">Date: <input type="text" id="PN_date_1" size="10" style="width: 200px; background-color: #FFF; padding: 10px; margin-left: 10px; margin-top: 6px;" /></td>
                                                
                                                <td><input type="text" id="PN_note_1" size="10" style="width: 200px; background-color: #FFF; padding: 10px; margin-left: 10px; margin-top: 6px;" /></td>
                                                <td><button id="PN_remove_1" style="width: 100%"><input type="hidden" value="1" />X</button></td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2"><button id="PN_add" style="width: 100%">+</button></td>
                                </tr>
                            </table>
                        </div>
                    </div>
    </div>
                         <center><input id="PN_note" type="button" class="btn btn-primary" value="SAVE" style="margin: auto; margin-top: 7px;"></center>
</div>
</div>
                        
-->
<div class="tab-pane" id="TabPatientNote">	
                <div style="margin:15px; margin-top:5px;">
					<div class="row-fluid"  style="">	            
						<div class="grid" style="padding:10px;height:1300px;padding-left: 0px;">
							

                            <style>
                                h4{
                                    font-size: 14px;
                                }
                            </style>
                            <br/>
                     <div style="width: 98%; height: 500px; margin-left: 2%">
                            

                         
<center><img class="image" src= "images/Patient.JPG"></center>
<p style="font-size: 25px;">Patient Name:<input type="text" size="35"name="patientname;font-size:10px;"> DOB:<input type="date" size="25"name="dob"> </p>
<table cellspacing="0" cellpadding="0" border="1">
<!--<table id="PN_note_table" cellpadding=0 cellspacing=0; style="margin-left: 400px; width: 1000px">-->
<tr>
<th style="width: 500px; border:hidden;"> DATE</th>
<th style="width: 500px; border:hidden"> PATIENT ENCOUNTER FORM</th> 
	
</tr>
<tr>
	<td> <input type="date" id="PN_date1" value="" style="width: 500px; border:hidden;"></td>
        <td><input type="text" id="PN_note1" value="" style="width: 500px; border:hidden;"></td>
	
</tr>
<tr>
	<td> <input type="date" id="PN_date2" value="" style="width: 500px; border:hidden;"></td>
        <td><input type="text" id="PN_note2" value="" style="width: 500px; border:hidden;"></td>
	
</tr>
<tr>
	<td> <input type="date" id="PN_date3" value="" style="width: 500px; border:hidden;"></td>
        <td><input type="text" id="PN_note3" value="" style="width: 500px; border:hidden;"></td>
	
</tr>
<tr>
	<td> <input type="date" id="PN_date4" value="" style="width: 500px; border:hidden;"></td>
        <td><input type="text" id="PN_note4" value="" style="width: 500px; border:hidden;"></td>
</tr>
<tr>
	<td> <input type="date" id="PN_date5" value="" style="width: 500px; border:hidden;"></td>
        <td><input type="text" id="PN_note5" value="" style="width: 500px; border:hidden;"></td>
</tr>
<tr>
	<td> <input type="date" id="PN_date6" value="" style="width: 500px; border:hidden;"></td>
        <td><input type="text" id="PN_note6" value="" style="width: 500px; border:hidden;"></td>
</tr>
<tr>
	<td> <input type="date" id="PN_date7" value="" style="width: 500px; border:hidden;"></td>
        <td><input type="text" id="PN_note7" value="" style="width: 500px; border:hidden;"></td>
</tr>
<tr>
	<td> <input type="date" id="PN_date8" value="" style="width: 500px; border:hidden;"></td>
        <td><input type="text" id="PN_note8" value="" style="width: 500px; border:hidden;"></td>
</tr>
<tr>
	<td> <input type="date" id="PN_date9" value="" style="width: 500px; border:hidden;"></td>
        <td><input type="text" id="PN_note9" value="" style="width: 500px; border:hidden;"></td>
</tr>
<tr>
	<td> <input type="date" id="PN_date10" value="" style="width: 500px; border:hidden;"></td>
        <td><input type="text" id="PN_note10" value="" style="width: 500px; border:hidden;"></td>
</tr>
<tr>
	<td> <input type="date" id="PN_date11" value="" style="width: 500px; border:hidden;"></td>
        <td><input type="text" id="PN_note11" value="" style="width: 500px; border:hidden;"></td>
</tr>
<tr>
	<td> <input type="date" id="PN_date12" value="" style="width: 500px; border:hidden;"></td>
        <td><input type="text" id="PN_note12" value="" style="width: 500px; border:hidden;"></td>
</tr>
<tr>
	<td> <input type="date" id="PN_date13" value="" style="width: 500px; border:hidden;"></td>
        <td><input type="text" id="PN_note13" value="" style="width: 500px; border:hidden;"></td>
</tr>
<tr>
	<td> <input type="date" id="PN_date14" value="" style="width: 500px; border:hidden;"></td>
        <td><input type="text" id="PN_note14" value="" style="width: 500px; border:hidden;"></td>
</tr>
<tr>
	<td> <input type="date" id="PN_date15" value="" style="width: 500px; border:hidden;"></td>
        <td><input type="text" id="PN_note15" value="" style="width: 500px; border:hidden;"></td>
</tr>
<tr>
	<td> <input type="date" id="PN_date16" value="" style="width: 500px; border:hidden;"></td>
        <td><input type="text" id="PN_note16" value="" style="width: 500px; border:hidden;"></td>
</tr>
<tr>
	<td> <input type="date" id="PN_date17" value="" style="width: 500px; border:hidden;"></td>
        <td><input type="text" id="PN_note17" value="" style="width: 500px; border:hidden;"></td>
</tr>
<tr>
	<td> <input type="date" id="PN_date18" value="" style="width: 500px; border:hidden;"></td>
        <td><input type="text" id="PN_note18" value="" style="width: 500px; border:hidden;"></td>
</tr>
<tr>
	<td> <input type="date" id="PN_date19" value="" style="width: 500px; border:hidden;"></td>
        <td><input type="text" id="PN_note19" value="" style="width: 500px; border:hidden;"></td>
</tr>
<tr>
	<td> <input type="date" id="PN_date20" value="" style="width: 500px; border:hidden;"></td>
        <td><input type="text" id="PN_note20" value="" style="width: 500px; border:hidden;"></td>
</tr>
</table>
                          </div>
                                       </div>
                    </div>
             <center><input id="PN_button" type="button" class="btn btn-primary" value="SAVE" style="margin: auto; margin-top: 7px;"></center>
                </div>
                </div>




                

                <!--
-->
				
			<div class="tab-pane" id="TabFinancialAgreement">		
				<div style="margin:15px; margin-top:5px;">
					<div class="row-fluid"  style="">	            
						<div class="grid" style="padding:10px;height:1000px">
							
							<div style="margin-left:20px; margin-top:20px; color: #3d93e0; font-size:20px; font-weight:bold; width:100%; ">Financial Responsibility Agreement </div>
								<hr>
								
								<p align="justify"> We appreciate the confidence you have shown in choosing us to provide for your immunotherapy and allergy needs. The service you have elected to participate in implies a financial responsibility on your part. This responsibility obligates you to ensure payment in full of your fees. As a courtesy, we spoke with 

								 <input id="fin_agrmnt_spkewith" type="text" style="background-color:transparent;border-style: solid; border-width: 0px 0px 1px 0px; border-color:black"/>(reference #: <input id="fin_agrmnt_ref" type="text" style="background-color:transparent;border-style: solid; border-width: 0px 0px 1px 0px; border-color:black"/>) at your insurance company to verify your coverage based on your contract with them and will bill your insurance carrier on your behalf. 
								 </p>
								<p align="justify">
									However, you are ultimately responsible for the payment of your bill. You are responsible for all that apply to your insurance contract at the time services are rendered. The following information was provided to us by your insurance provider based on the benefits associated with your contract:
								</p>
								<div>
								<div style="float:left">
									<span>
										Co-payment   :  <input type="text" id="fin_agrmnt_cop" style="background-color:transparent;border-style: solid; border-width: 0px 0px 1px 0px; border-color:black"/>
									</span>
									<br/>
									<span>
										Deductible   :  <input type="text" id="fin_agrmnt_ded" style="background-color:transparent;border-style: solid; border-width: 0px 0px 1px 0px; border-color:black"/>
									</span>
									<br/>
									<span>
										Coinsurance  :  <input type="text" id="fin_agrmnt_coins" style="background-color:transparent;border-style: solid; border-width: 0px 0px 1px 0px; border-color:black"/>  
									</span>
								</div>
								<div style="border-style:solid;border-width: 1px 1px 1px 1px;float:right;margin-right:60px">Please see the physicians front <br/> office and/or billing personnel <br/> for payment procedures.</div>
								</div>
								<div style="margin-top:120px;">
								<p align="justify">In the event your insurance company has additional stipulations that may affect your coverage, you have the choice to move forward with treatment. You are responsible for any amount not covered by your insurer.
									If your insurance carrier denies any part of your claim, or if you and your physician elect to continue therapy past your approved period, you will be responsible for your remaining account balance.
								</p>
								<p align="justify">
									
									I have read the above policy regarding my financial responsibility to my physician for providing immunotherapy and allergy services to the above named patient or me. I certify that the information provided is, to the best of my knowledge, true and accurate.
									I authorize my insurer to pay any benefits directly to my physician. If I need immunotherapy and decide to initiate treatment, I understand I will be given 72 hours to notify my provider then at that time my insurance will be billed entirely regardless of whether I continue therapy or not. 
									I also understand if I change insurance during the duration of the treatment that it is my responsibility to notify my physician immediately; otherwise I will be responsible for the remaining balance in full. Although benefits have been verified the quoted benefits may not be a guarantee of payment by the insurance carrier(s).
									I agree to pay my physician the full and entire amount of all bills incurred by me or the above named patient, if applicable, any amount due after payment has been made by my insurance carrier. The carrier will process the claim according to the policy and provisions outlined in your health insurance benefit.

								</p>
								
								<p align="justify">
									<input type="text" id="fin_agrmnt_P_initial" style="background-color:transparent;border-style: solid; border-width: 0px 0px 1px 0px; border-color:black"/>Initial here if choosing a cash pay option. I understand this payment will not be presented to my 	 insurance company for the purposes of reducing future deductibles or copayments.
								</p>
								</div >
								<div style="margin-top:20px">
										Patient Name: <input type="text" id="fin_agrmnt_name" size="70" style="width: 250px; margin-right: 40px; margin-left: 5px;" /> Relationship to Patient: <input type="text" id="fin_agrmnt_relationship" size="70" style="width: 250px; margin-right: 10px; margin-left: 5px;" />
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
												<input type="text" id="sig_patient1_fin_agrmnt_date" size="3" style="width: 130px; background-color: #FFF; padding: 10px;" />
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
												<input type="text" class="date_input" id="sig_witness1_fin_agrmnt_date" size="3" style="width: 130px; background-color: #FFF; padding: 10px;" />
											</div>
										</div>
									</div>
								</div>
									
						</div>
					</div>
                    <center><input id="fin_agrmnt_button" type="button" class="btn btn-primary" value="SAVE" style="margin: auto; margin-top: 7px;"></center>
				</div>
			</div>	
				
			<div class="tab-pane" id="TabInsuranceVerification">		
				<div style="margin:15px; margin-top:5px;">
					<div class="row-fluid"  style="">	            
						<div class="grid" style="padding:10px;height:1000px;overflow:auto">
							
							<div style="margin-left:20px; margin-top:20px; color: #3d93e0; font-size:20px; font-weight:bold; width:100%; ">Insurance Benefits Verification form</div>
								<hr>
							<div style="width: 100%;" >
                                <span style="float: left; margin-right: 10px;">Patient Name: </span>
                                <input type="text" id="IV_pname" size="10" />
                                
                                <span style="margin-right: 10px; margin-left: 10px;">Patient Date of Birth: </span>
                                <input type="date" id="IV_pdob" size="10" />
                                
                                
                            </div>	
							<div style="margin-top:20px">
							
								<div style="float:left;width:49%">
									<div><span style="font-size:14px;font-weight:bold">Primary Insurance</span><br/></div>
									<div><span style="font-size:13px;">Name of Insurance Carrier:
										 <input type="text" id="IC_name" style="margin-left:10px;background-color:transparent;border-style: solid; border-width: 0px 0px 1px 0px; border-color:black"/>
									</span><br/>
									</div>
									<div><span style="font-size:13px;">Circle Type of Policy
										<select id="Tab_insurance_select" style="margin-left: 40px;">
                                            <option value="HMO">HMO</option>
                                            <option value="PPO">PPO</option>
                                            <option value="MEDICARE">MEDICARE</option>
                                        </select>
									</span><br/>
									</div>
									<div>
										<span style="font-size:13px;">Group Number
											<input type="text" id="IV_GN" style="margin-left:80px;background-color:transparent;border-style: solid; border-width: 0px 0px 1px 0px; border-color:black"/>
										</span><br/>
									</div>
									<div>
										<span style="font-size:13px;">Name of Policy Holder
											<input type="text" id="IV_PH_name" style="margin-left:35px;background-color:transparent;border-style: solid; border-width: 0px 0px 1px 0px; border-color:black"/>
										</span><br/>
									</div>
									<div>
										<span style="font-size:13px;">Relationship to Policy Holder
											<input type="text" id="IV_PH_relation" style="margin-left:8px;background-color:transparent;border-style: solid; border-width: 0px 0px 1px 0px; border-color:black"/>
										</span><br/>
									</div>
									<div>
										<span style="font-size:13px;">Policy Holder Date of Birth
											<input type="date" id="IV_PH_dob" style="margin-left:8px;background-color:transparent;border-style: solid; border-width: 0px 0px 1px 0px; border-color:black"/>
										</span><br/>
									</div>
									<div>
										<span style="font-size:13px;">Effective Date
											<input type="date" id="IV_effdate" style="margin-left:80px;background-color:transparent;border-style: solid; border-width: 0px 0px 1px 0px; border-color:black"/>
										</span><br/>
									</div>
									
								</div>
								<div style="float:left;width:45%">
								
													
									<div><span style="font-size:14px;font-weight:bold">Benefit Year:  Calendar or Fiscal</span><br/><br/></div>
									<div><span style="font-size:13px;font-weight:bold">Benefits:  In Network or OON</span><br/></div>
									<div><span style="font-size:13px;">Coinsurance:
										 <input type="text" id="IV_coins" style="margin-left:10px;width:290px;background-color:transparent;border-style: solid; border-width: 0px 0px 1px 0px; border-color:black"/>
									%</span><br/>
									</div>
									<br/>
									<div>
										<span style="font-size:13px;">Max Out of Pocket $
											<input type="text" id="IV_max_oop" style="margin-left:10px;width:250px;background-color:transparent;border-style: solid; border-width: 0px 0px 1px 0px; border-color:black"/>
										</span><br/>
									</div>
									<br/>
									<div>
										<span style="font-size:13px;">Ind Deductible $
											<input type="text" id="IV_deduct" style="margin-left:10px;width:100px;background-color:transparent;border-style: solid; border-width: 0px 0px 1px 0px; border-color:black"/>
										</span>
										<span style="font-size:13px;">Amt Met $
											<input type="text" id="IV_amt_met" style="margin-left:10px;width:100px;background-color:transparent;border-style: solid; border-width: 0px 0px 1px 0px; border-color:black"/>
										</span>
									</div>
									<br/>
									<div>
										<span style="font-size:13px;">Family Deductible $
											<input type="text" id="IV_famly_deduct" style="margin-left:10px;width:100px;background-color:transparent;border-style: solid; border-width: 0px 0px 1px 0px; border-color:black"/>
										</span>
										<span style="font-size:13px;">Amt Met $
											<input type="text" id="IV_amt_met1" style="margin-left:10px;width:80px;background-color:transparent;border-style: solid; border-width: 0px 0px 1px 0px; border-color:black"/>
										</span>
									</div>
									
								</div>
							
							</div>
							<div>
									<table style="width:49%;background-color:white;float:left;margin-top:30px">
										<tr>
											<td colspan="2">												
												<span style="font-size:14px;font-weight:bold;margin-right:50px;">95004-  Medications- Allergen Immunotherapy</span>
											</td>
										</tr>

										  
										<tr>
											<td>
												<span style="font-size:13px;">How many tests/units are allowed per yr?: </span>
											</td>
											<td>
												<input type="text" id="IV_unitspery1" style="margin-left:10px;background-color:transparent;border-style: solid; border-width: 0px 0px 1px 0px; border-color:black"/>
											<br/>
											</td>
										</tr>
										<tr>
											<td>
												<span style="font-size:13px;">Is this considered diagnostic service? </span>
											</td>
											<td>
												<input type="text" id="IV_diag_srvc1" style="margin-left:10px;background-color:transparent;border-style: solid; border-width: 0px 0px 1px 0px; border-color:black"/>
											<br/>
											</td>
										</tr>
										<tr>
											<td>
												<span style="font-size:13px;">Benefits with office visit? </span>
											</td>
											<td>
												<input type="text" id="IV_benftsw1" style="margin-left:10px;background-color:transparent;border-style: solid; border-width: 0px 0px 1px 0px; border-color:black"/>
											<br/>
											</td>
										</tr>
										<tr>
											<td>
												<span style="font-size:13px;">Benefits without office visit? </span>
											</td>
											<td>											
												<input type="text" id="IV_benftswo1" style="margin-left:10px;background-color:transparent;border-style: solid; border-width: 0px 0px 1px 0px; border-color:black"/>
												<br/>
											</td>
										</tr>
										<tr>
											<td>
												<span style="font-size:13px;">Copay for 95004? </span>
											</td>
											<td>
												<select id="Tab_insurance_select_cp1" style="margin-left: 40px; width:100px">
													<option value="Yes">Yes</option>
													<option value="No">No</option>
												</select>
											</td>
										</tr>
										<tr>
											<td>
												<span style="font-size:13px;">Does copay apply to deductible ? </span>
											</td>
											<td>
												<select id="Tab_insurance_select_cp2" style="margin-left: 40px;width:100px">
													<option value="Yes">Yes</option>
													<option value="No">No</option>
												</select>
											</td>
										</tr>
										<tr>
											<td>
												<span style="font-size:13px;">Does co-insurance apply to deductible ? </span>
											</td>
											<td>
												<select id="Tab_insurance_select_cp3" style="margin-left: 40px;width:100px">
													<option value="Yes">Yes</option>
													<option value="No">No</option>
												</select>
											</td>
										</tr>
										<tr>
											<td>
												<span style="font-size:13px;">Is precertification required for test or units? </span>
											</td>
											<td>
												<select id="Tab_insurance_select_cp4" style="margin-left: 40px;width:100px">
													<option value="Yes">Yes</option>
													<option value="No">No</option>
												</select>
											</td>
										</tr>
										<tr>
											<td>
												<span style="font-size:13px;">Pre-certification Phone: </span>
											</td>
											<td>
												<input type="text" id="IV_cert_phone1" style="margin-left:10px;background-color:transparent;border-style: solid; border-width: 0px 0px 1px 0px; border-color:black"/>
											</td>
										</tr>
										<tr>
											<td>
												<span style="font-size:13px;">Precertification Approval Number:</span>
											</td>
											<td>
												<input type="text" id="IV_cert_aprvl_num1" style="margin-left:10px;background-color:transparent;border-style: solid; border-width: 0px 0px 1px 0px; border-color:black"/>
											</td>
										</tr>
										<tr>
											<td>
												<span style="font-size:13px;">Name of Person at Insurance Company:</span>
											</td>
											<td>
												<input type="text" id="IV_comp_person_name1" style="margin-left:10px;background-color:transparent;border-style: solid; border-width: 0px 0px 1px 0px; border-color:black"/>
											</td>
										</tr>
									</table>
									<table style="width:45%;background-color:white;float:left;margin-top:30px">
										<tr>
										
											<td colspan="2">
												
													<span style="font-size:14px;font-weight:bold;margin-right:50px;">95165-  Allergy Test- Percutaneous test</span>
												
											</td>
										</tr>

										  
										<tr>
											<td>
												<span style="font-size:13px;">How many tests/units are allowed per yr?: </span>
											</td>
											<td>
												<input type="text" id="IV_unitspery2"  style="margin-left:10px;background-color:transparent;border-style: solid; border-width: 0px 0px 1px 0px; border-color:black"/>
											<br/>
											</td>
										</tr>
										<tr>
											<td>
												<span style="font-size:13px;">Is this considered diagnostic service? </span>
											</td>
											<td>
												<input type="text" id="IV_diag_srvc2" style="margin-left:10px;background-color:transparent;border-style: solid; border-width: 0px 0px 1px 0px; border-color:black"/>
											<br/>
											</td>
										</tr>
										<tr>
											<td>
												<span style="font-size:13px;">Benefits with office visit? </span>
											</td>
											<td>
												<input type="text" id="IV_benftsw2" style="margin-left:10px;background-color:transparent;border-style: solid; border-width: 0px 0px 1px 0px; border-color:black"/>
											<br/>
											</td>
										</tr>
										<tr>
											<td>
												<span style="font-size:13px;">Benefits without office visit? </span>
											</td>
											<td>											
												<input type="text" id="IV_benftswo2" style="margin-left:10px;background-color:transparent;border-style: solid; border-width: 0px 0px 1px 0px; border-color:black"/>
												<br/>
											</td>
										</tr>
										<tr>
											<td>
												<span style="font-size:13px;">Copay for 95004? </span>
											</td>
											<td>
												<select id="Tab_insurance_select_cp5" style="margin-left: 40px; width:100px">
													<option value="Yes">Yes</option>
													<option value="No">No</option>
												</select>
											</td>
										</tr>
										<tr>
											<td>
												<span style="font-size:13px;">Does copay apply to deductible ? </span>
											</td>
											<td>
												<select id="Tab_insurance_select_cp6" style="margin-left: 40px;width:100px">
													<option value="Yes">Yes</option>
													<option value="No">No</option>
												</select>
											</td>
										</tr>
										<tr>
											<td>
												<span style="font-size:13px;">Does co-insurance apply to deductible ? </span>
											</td>
											<td>
												<select id="Tab_insurance_select_cp7" style="margin-left: 40px;width:100px">
													<option value="Yes">Yes</option>
													<option value="No">No</option>
												</select>
											</td>
										</tr>
										<tr>
											<td>
												<span style="font-size:13px;">Is precertification required for test or units? </span>
											</td>
											<td>
												<select id="Tab_insurance_select_cp8" style="margin-left: 40px;width:100px">
													<option value="Yes">Yes</option>
													<option value="No">No</option>
												</select>
											</td>
										</tr>
										<tr>
											<td>
												<span style="font-size:13px;">Pre-certification Phone: </span>
											</td>
											<td>
												<input type="text" id="IV_cert_phone2" style="margin-left:10px;background-color:transparent;border-style: solid; border-width: 0px 0px 1px 0px; border-color:black"/>
											</td>
										</tr>
										<tr>
											<td>
												<span style="font-size:13px;">Precertification Approval Number:</span>
											</td>
											<td>
												<input type="text" id="IV_cert_aprvl_num2" style="margin-left:10px;background-color:transparent;border-style: solid; border-width: 0px 0px 1px 0px; border-color:black"/>
											</td>
										</tr>
										<tr>
											<td>
												<span style="font-size:13px;">Name of Person at Insurance Company:</span>
											</td>
											<td>
												<input type="text" id="IV_comp_person_name2" style="margin-left:10px;background-color:transparent;border-style: solid; border-width: 0px 0px 1px 0px; border-color:black"/>
											</td>
										</tr>
									</table>
							</div>
							<div>
								<div style="width:49%;margin-top:30px;float:left">
									<span style="font-size:13px;"><u>Notes:</u></span>
									<input type="textarea" id="IV_notes"/>
								</div>
								<div style="width:45%;margin-top:30px;float:left">
								
									<table style="background-color:white;">
											<tr>
											
												<td colspan="2">
													
													<span style="font-size:14px;font-weight:bold;margin-right:50px;"><u>95199-Medications-Sublingual Immunotherapy:</u></span>
													
												</td>
											</tr>

											  
											<tr>
												<td>
													<span style="font-size:13px;">Benefits with office visit? </span>
												</td>
												<td>
													<input type="text" id="IV_benftsw3" style="margin-left:10px;background-color:transparent;border-style: solid; border-width: 0px 0px 1px 0px; border-color:black"/>
												<br/>
												</td>
											</tr>
											<tr>
												<td>
													<span style="font-size:13px;">Benefits without office visit? </span>
												</td>
												<td>
													<input type="text" id="IV_benftswo3" style="margin-left:10px;background-color:transparent;border-style: solid; border-width: 0px 0px 1px 0px; border-color:black"/>
												<br/>
												</td>
											</tr>
											
									</table>
								
								</div>
								
							
							</div>
				        </div>
					</div>
                    <center><input id="IV_button" type="button" class="btn btn-primary" value="SAVE" style="margin: auto; margin-top: 7px;"></center>
				</div>
			</div>
			

			<div class="tab-pane" id="TabSLITLogBW">
				 <div style="margin:15px; margin-top:5px;">
					<div class="row-fluid"  style="">	            
						<div class="grid" style="padding:10px;height:800px;padding-left: 0px;">
							
                            <div style="margin-left:20px; margin-top:20px; color: #3d93e0; font-size:20px; font-weight:bold; width:100%; ">TAKE HOME SLIT Log BW</div>
                            <style>
                                h4{
                                    font-size: 14px;
                                }
                            </style>
                            <br/>
                           
                            
                            <!--<div style="width: 98%; height: 500px; margin-left: 2%">-->
                                
                               <div>
                                    <span style="float: left; margin-right: 10px;">Name: </span>
                                    <input type="text" id="slitlog_name" size="10" />
                                
                                    <span style="margin-right: 10px; margin-left: 10px;">DOB: </span>
                                    <input type="date" id="slitlog_dob" size="10" />
                                   
                                    <span style="margin-right:10px; margin-left: 10px;">Vial Expiration Date:</span>
                                    <input type="date" id="slitlog_expdate" size="10"/>
                                                                              
                               </div>
                                <div style="float:right; margin-right:0px;">
                                <label class="checkbox toggle candy" onclick="" style="width:200px;">
                                    <input type="checkbox" id="SlitLogSwitch" name="COthers" />
                                    <p>
                                        <span>Maintanence</span>
                                        <span>Dropper</span>
                                        
                                    </p>

                                    <a class="slide-button"></a>
                                </label>
                                </div>
                                <div id="switchDropper">
                                <div id="slitDropper1" style="width: 98%; height: 500px; margin-left: 2%">
                                    <h4 style="text-align: center;">ONE DROPPER: AT HOME SLIT BUILD UP SCHEDULE</h4>
                                    <center><input id="SD1_save_button" type="button" class="btn btn-primary" value="SAVE" style="margin: auto; margin-top: 7px;"></center>
                                    <input type="button" class="btn btn-primary" value="Next" style="float:right;margin-top:10px" id="dropperNext2">
                                    <table cellspacing="0" cellpadding="0" border="1" style="width:49%;background-color:white;float:left;margin-top:50px;margin-left:50px">
                                                    <tr>
                                                        <td colspan="6" bgcolor="#C0C0C0">								
                                                            <center style="font-size:14px;font-weight:bold;margin-right:50px;">BUILD-UP #1 VIALS</center>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <span style="font-size:14px;font-weight:bold;margin-right:50px;">Day</span>
                                                        </td>
                                                        <td>
                                                            <span style="font-size:14px;font-weight:bold;margin-right:50px;">Location</span>
                                                        </td>
                                                        <td>
                                                            <span style="font-size:14px;font-weight:bold;margin-right:50px;">Date</span>
                                                        </td>
                                                        <td>
                                                            <span style="font-size:14px;font-weight:bold;margin-right:50px;"># of Drops</span>
                                                        </td>
                                                        <td>
                                                            <span style="font-size:14px;font-weight:bold;margin-right:50px;width:600px">Remarks/Reactions</span>
                                                        </td>
                                                        <td>
                                                            <span style="font-size:14px;font-weight:bold;margin-right:50px;">Initials</span>
                                                        </td>


                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <center> 1 </center>
                                                        </td>

                                                        <td>
                                                            <span> Office </span>
                                                        </td>

                                                        <td>
                                                            <span> <input id="Slitlog_drp1_date1" type="date"> </span>
                                                        </td>

                                                        <td>
                                                            <center> 1 </center>
                                                        </td>

                                                        <td>
                                                            <span> <input id="Slitlog_drp1_remarks1" type="text"> </span>
                                                        </td>

                                                        <td>
                                                            <span> <input id="Slitlog_drp1_initials1" type="text"> </span>
                                                        </td>


                                                    </tr>
                                         <tr>
                                                        <td>
                                                            <center> 2 </center>
                                                        </td>

                                                        <td>
                                                            <span> Home </span>
                                                        </td>

                                                        <td>
                                                            <span> <input id="Slitlog_drp1_date2" type="date"> </span>
                                                        </td>

                                                        <td>
                                                            <center> 2 </center>
                                                        </td>

                                                        <td>
                                                            <span> <input id="Slitlog_drp1_remarks2" type="text"> </span>
                                                        </td>

                                                        <td>
                                                            <span> <input id="Slitlog_drp1_initials2" type="text"> </span>
                                                        </td>


                                                    </tr>
                                         <tr>
                                                        <td>
                                                            <center> 3 </center>
                                                        </td>

                                                        <td>
                                                            <span> Home </span>
                                                        </td>

                                                        <td>
                                                            <span> <input id="Slitlog_drp1_date3" type="date"> </span>
                                                        </td>

                                                        <td>
                                                            <center> 3 </center>
                                                        </td>

                                                        <td>
                                                            <span> <input id="Slitlog_drp1_remarks3" type="text"> </span>
                                                        </td>

                                                        <td>
                                                            <span> <input id="Slitlog_drp1_initials3" type="text"> </span>
                                                        </td>


                                                    </tr>
                                         <tr>
                                                        <td>
                                                           <center> 4 </center>
                                                        </td>

                                                        <td>
                                                            <span> Home </span>
                                                        </td>

                                                        <td>
                                                            <span> <input id="Slitlog_drp1_date4" type="date"> </span>
                                                        </td>

                                                        <td>
                                                            <center> 4 </center>
                                                        </td>

                                                        <td>
                                                            <span> <input id="Slitlog_drp1_remarks4" type="text"> </span>
                                                        </td>

                                                        <td>
                                                            <span> <input id="Slitlog_drp1_initials4" type="text"> </span>
                                                        </td>


                                                    </tr>
                                         <tr>
                                                        <td>
                                                           <center> 5 </center>
                                                        </td>

                                                        <td>
                                                            <span> Home </span>
                                                        </td>

                                                        <td>
                                                            <span> <input id="Slitlog_drp1_date5" type="date"> </span>
                                                        </td>

                                                        <td>
                                                            <center> 5 </center>
                                                        </td>

                                                        <td>
                                                            <span> <input id="Slitlog_drp1_remarks5" type="text"> </span>
                                                        </td>

                                                        <td>
                                                            <span> <input id="Slitlog_drp1_initials5" type="text"> </span>
                                                        </td>


                                                    </tr>
                                                    <!--table2-->
                                                    <tr>
                                                        <td colspan="6" bgcolor="#C0C0C0">								
                                                            <center style="font-size:14px;font-weight:bold;margin-right:50px;">BUILD-UP #2 VIALS</center>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <span style="font-size:14px;font-weight:bold;margin-right:50px;">Day</span>
                                                        </td>
                                                        <td>
                                                            <span style="font-size:14px;font-weight:bold;margin-right:50px;">Location</span>
                                                        </td>
                                                        <td>
                                                            <span style="font-size:14px;font-weight:bold;margin-right:50px;">Date</span>
                                                        </td>
                                                        <td>
                                                            <span style="font-size:14px;font-weight:bold;margin-right:50px;"># of Drops</span>
                                                        </td>
                                                        <td>
                                                            <span style="font-size:14px;font-weight:bold;margin-right:50px;width:600px">Remarks/Reactions</span>
                                                        </td>
                                                        <td>
                                                            <span style="font-size:14px;font-weight:bold;margin-right:50px;">Initials</span>
                                                        </td>


                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <center> 6 </center>
                                                        </td>

                                                        <td>
                                                            <span> Home </span>
                                                        </td>

                                                        <td>
                                                            <span> <input id="Slitlog_drp1_date6" type="date"> </span>
                                                        </td>

                                                        <td>
                                                            <center> 1 </center>
                                                        </td>

                                                        <td>
                                                            <span> <input id="Slitlog_drp1_remarks6" type="text"> </span>
                                                        </td>

                                                        <td>
                                                            <span> <input id="Slitlog_drp1_initials6" type="text"> </span>
                                                        </td>


                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <center> 7 </center>
                                                        </td>

                                                        <td>
                                                            <span> Home </span>
                                                        </td>

                                                        <td>
                                                            <span> <input id="Slitlog_drp1_date7" type="date"> </span>
                                                        </td>

                                                        <td>
                                                            <center> 2 </center>
                                                        </td>

                                                        <td>
                                                            <span> <input id="Slitlog_drp1_remarks7" type="text"> </span>
                                                        </td>

                                                        <td>
                                                            <span> <input id="Slitlog_drp1_initials7" type="text"> </span>
                                                        </td>


                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <center> 8 </center>
                                                        </td>

                                                        <td>
                                                            <span> Home </span>
                                                        </td>

                                                        <td>
                                                            <span> <input id="Slitlog_drp1_date8" type="date"> </span>
                                                        </td>

                                                        <td>
                                                            <center> 3 </center>
                                                        </td>

                                                        <td>
                                                            <span> <input id="Slitlog_drp1_remarks8" type="text"> </span>
                                                        </td>

                                                        <td>
                                                            <span> <input id="Slitlog_drp1_initials8" type="text"> </span>
                                                        </td>


                                                    </tr>
                                                    <tr>
                                                        <td>
                                                           <center> 9 </center>
                                                        </td>

                                                        <td>
                                                            <span> Home </span>
                                                        </td>

                                                        <td>
                                                            <span> <input id="Slitlog_drp1_date9" type="date"> </span>
                                                        </td>

                                                        <td>
                                                            <center> 4 </center>
                                                        </td>

                                                        <td>
                                                            <span> <input id="Slitlog_drp1_remarks9" type="text"> </span>
                                                        </td>

                                                        <td>
                                                            <span> <input id="Slitlog_drp1_initials9" type="text"> </span>
                                                        </td>


                                                    </tr>
                                                    <tr>
                                                        <td>
                                                           <center> 10 </center>
                                                        </td>

                                                        <td>
                                                            <span> Home </span>
                                                        </td>

                                                        <td>
                                                            <span> <input id="Slitlog_drp1_date10" type="date"> </span>
                                                        </td>

                                                        <td>
                                                            <center> 5 </center>
                                                        </td>

                                                        <td>
                                                            <span> <input id="Slitlog_drp1_remarks10" type="text"> </span>
                                                        </td>

                                                        <td>
                                                            <span> <input id="Slitlog_drp1_initials10" type="text"> </span>
                                                        </td>


                                                    </tr>
                                         
                                        
                                                    <!--table2-->
                                                    <tr>
                                                        <td colspan="6" bgcolor="#C0C0C0">								
                                                            <center style="font-size:14px;font-weight:bold;margin-right:50px;height:20px"></center>
                                                        </td>
                                                    </tr>
                                             </table>
                                     
                                </div>
                                    
                               
                           
                            
                            <div id="slitDropper2" style="width: 98%; height: 590px; margin-left: 2%; float: left;">
                                        
                                <!--<hr style="height:1px;border:none;color:#333;background-color:#333;" />-->
                                <h4 style="text-align: center; font-size: 14px;">2 DROPPER: AT HOME SLIT BUILD UP SCHEDULE</h4>
                                <center><input id="SD2_save_button" type="button" class="btn btn-primary" value="SAVE" style="margin: auto; margin-top: 7px;"></center>
                                 <input type="button" class="btn btn-primary" value="Prev" style="float:left;margin-top:10px" id="dropperPrev1">
                                 <input type="button" class="btn btn-primary" value="Next" style="float:right;margin-top:10px" id="dropperNext3">
                                 <table cellspacing="0" cellpadding="0" border="1" style="width:49%;background-color:white;float:left;margin-top:50px;margin-left:50px">
                                                    <tr>
                                                        <td colspan="8" bgcolor="#C0C0C0">								
                                                            <center style="font-size:14px;font-weight:bold;margin-right:50px;">BUILD-UP #1 VIALS</center>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <span style="font-size:14px;font-weight:bold;margin-right:50px;">Day</span>
                                                        </td>
                                                        
                                                         <td>
                                                            <span style="font-size:14px;font-weight:bold;margin-right:50px;">Date</span>
                                                        </td>
                                                         <td>
                                                            <span style="font-size:14px;font-weight:bold;margin-right:50px;">Dropper1(1)</span>
                                                        </td>                                                       
                                                        <td>
                                                            <span style="font-size:14px;font-weight:bold;margin-right:50px;"># of Drops</span>
                                                        </td>
                                                         <td>
                                                            <span style="font-size:14px;font-weight:bold;margin-right:50px;">Dropper1(2)</span>
                                                        </td>                                                       
                                                        <td>
                                                            <span style="font-size:14px;font-weight:bold;margin-right:50px;"># of Drops</span>
                                                        </td>
                                                        <td>
                                                            <span style="font-size:14px;font-weight:bold;margin-right:50px;">Remarks/Reactions</span>
                                                        </td>
                                                        <td>
                                                            <span style="font-size:14px;font-weight:bold;margin-right:50px;">Initials</span>
                                                        </td>


                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <center> 1 </center>
                                                        </td>

                                                         <td>
                                                            <span> <input id="Slitlog_drp2_date1" type="date" style="width:100px"> </span>
                                                        </td>

                                                        <td>
                                                            <span> <input id="dropper1_1" type="text" style="width:100px"> </span>
                                                        </td>
                                                        
                                                         <td>
                                                            <center> 1 </center>
                                                         </td>
                                                         <td>
                                                            <span> <input id="dropper1_2" type="text" style="width:100px"> </span>
                                                        </td>
                                                        
                                                         <td>
                                                            <center> 1 </center>
                                                         </td>

                                                        <td>
                                                            <span> <input id="Slitlog_drp2_remarks1" type="text" style="width:100px"> </span>
                                                        </td>

                                                        <td>
                                                            <span> <input id="Slitlog_drp2_initials1" type="text" style="width:100px"> </span>
                                                        </td>


                                                    </tr>
                                         <tr>
                                                        <td>
                                                            <center> 2 </center>
                                                        </td>

                                                         <td>
                                                            <span> <input id="Slitlog_drp2_date2" type="date" style="width:100px"> </span>
                                                        </td>
                                             
                                                        <td>
                                                            <span> <input id="dropper2_1" type="text" style="width:100px"> </span>
                                                        </td>
                                                        
                                                        <td>
                                                            <center> 2 </center>
                                                        </td>
                                             
                                                        <td>
                                                            <span> <input id="dropper2_2" type="text" style="width:100px"> </span>
                                                        </td>
                                                        
                                                        <td>
                                                            <center> 2 </center>
                                                        </td>

                                                        <td>
                                                            <span> <input id="Slitlog_drp2_remarks2" type="text" style="width:100px"> </span>
                                                        </td>

                                                        <td>
                                                            <span> <input id="Slitlog_drp2_initials2" type="text" style="width:100px"> </span>
                                                        </td>


                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <center> 3 </center>
                                                        </td>

                                                        <td>
                                                            <span> <input id="Slitlog_drp2_date3" type="date" style="width:100px"> </span>
                                                        </td>
                                                        
                                                        <td>
                                                            <span> <input id="dropper3_1" type="text" style="width:100px"> </span>
                                                        </td>
                                                        
                                                        <td>
                                                            <center> 3 </center>
                                                        </td>
                                             
                                                        <td>
                                                            <span> <input id="dropper3_2" type="text" style="width:100px"> </span>
                                                        </td>
                                                        
                                                        <td>
                                                            <center> 3 </center>
                                                        </td>
                                                        
                                                        <td>
                                                            <span> <input id="Slitlog_drp2_remarks3" type="text" style="width:100px"> </span>
                                                        </td>

                                                        <td>
                                                            <span> <input id="Slitlog_drp2_initials3" type="text" style="width:100px"> </span>
                                                        </td>


                                                    </tr>
                                         <tr>
                                                        <td>
                                                           <center> 4 </center>
                                                        </td>

                                                        <td>
                                                            <span> <input id="Slitlog_drp2_date4" type="date" style="width:100px"> </span>
                                                        </td>
                                                        
                                                        <td>
                                                            <span> <input id="dropper4_1" type="text" style="width:100px"> </span>
                                                        </td>
                                                        
                                                        <td>
                                                            <center> 4 </center>
                                                        </td>
                                             
                                                        <td>
                                                            <span> <input id="dropper4_2" type="text" style="width:100px"> </span>
                                                        </td>
                                                        
                                                        <td>
                                                            <center> 4 </center>
                                                        </td>
                                                        <td>
                                                            <span> <input id="Slitlog_drp2_remarks4" type="text" style="width:100px"> </span>
                                                        </td>

                                                        <td>
                                                            <span> <input id="SSlitlog_drp2_initials4" type="text" style="width:100px"> </span>
                                                        </td>


                                                    </tr>
                                                    <tr>
                                                        <td>
                                                           <center> 5 </center>
                                                        </td>
                                                        
                                                        <td>
                                                            <span> <input id="Slitlog_drp2_date5" type="date" style="width:100px"> </span>
                                                        </td>

                                                        <td>
                                                            <span> <input id="dropper5_1" type="text" style="width:100px"> </span>
                                                        </td>
                                                        
                                                        <td>
                                                            <center> 5 </center>
                                                        </td>
                                             
                                                        <td>
                                                            <span> <input id="dropper5_2" type="text" style="width:100px"> </span>
                                                        </td>
                                                        
                                                        <td>
                                                            <center> 5 </center>
                                                        </td>

                                                        <td>
                                                            <span> <input id="Slitlog_drp2_remarks5" type="text" style="width:100px"> </span>
                                                        </td>

                                                        <td>
                                                            <span> <input id="Slitlog_drp2_initials5" type="text" style="width:100px"> </span>
                                                        </td>


                                                    </tr>
                                                    <!--table2-->
                                                    <tr>
                                                        <td colspan="8" bgcolor="#C0C0C0">								
                                                            <center style="font-size:14px;font-weight:bold;margin-right:50px;">BUILD-UP #2 VIALS</center>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <span style="font-size:14px;font-weight:bold;margin-right:50px;">Day</span>
                                                        </td>
                                                        
                                                         <td>
                                                            <span style="font-size:14px;font-weight:bold;margin-right:50px;">Date</span>
                                                        </td>
                                                         <td>
                                                            <span style="font-size:14px;font-weight:bold;margin-right:50px;">Dropper2(1)</span>
                                                        </td>                                                       
                                                        <td>
                                                            <span style="font-size:14px;font-weight:bold;margin-right:50px;"># of Drops</span>
                                                        </td>
                                                         <td>
                                                            <span style="font-size:14px;font-weight:bold;margin-right:50px;">Dropper2(2)</span>
                                                        </td>                                                       
                                                        <td>
                                                            <span style="font-size:14px;font-weight:bold;margin-right:50px;"># of Drops</span>
                                                        </td>
                                                        <td>
                                                            <span style="font-size:14px;font-weight:bold;margin-right:50px;width:600px">Remarks/Reactions</span>
                                                        </td>
                                                        <td>
                                                            <span style="font-size:14px;font-weight:bold;margin-right:50px;">Initials</span>
                                                        </td>


                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <center> 1 </center>
                                                        </td>

                                                         <td>
                                                            <span> <input id="Slitlog_drp2_date6" type="date" style="width:100px"> </span>
                                                        </td>

                                                        <td>
                                                            <span> <input id="dropper1_1" type="text" style="width:100px"> </span>
                                                        </td>
                                                        
                                                         <td>
                                                            <center> 1 </center>
                                                         </td>
                                                         <td>
                                                            <span> <input id="dropper1_2" type="text" style="width:100px"> </span>
                                                        </td>
                                                        
                                                         <td>
                                                            <center> 1 </center>
                                                         </td>

                                                        <td>
                                                            <span> <input id="Slitlog_drp2_remarks6" type="text" style="width:100px"> </span>
                                                        </td>

                                                        <td>
                                                            <span> <input id="Slitlog_drp2_initials6" type="text" style="width:100px"> </span>
                                                        </td>


                                                    </tr>
                                         <tr>
                                                        <td>
                                                            <center> 2 </center>
                                                        </td>

                                                         <td>
                                                            <span> <input id="Slitlog_drp2_date7" type="date" style="width:100px"> </span>
                                                        </td>
                                             
                                                        <td>
                                                            <span> <input id="dropper2_1" type="text" style="width:100px"> </span>
                                                        </td>
                                                        
                                                        <td>
                                                            <center> 2 </center>
                                                        </td>
                                             
                                                        <td>
                                                            <span> <input id="dropper2_2" type="text" style="width:100px"> </span>
                                                        </td>
                                                        
                                                        <td>
                                                            <center> 2 </center>
                                                        </td>

                                                        <td>
                                                            <span> <input id="Slitlog_drp2_remarks7" type="text" style="width:100px"> </span>
                                                        </td>

                                                        <td>
                                                            <span> <input id="Slitlog_drp2_initials7" type="text" style="width:100px"> </span>
                                                        </td>


                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <center> 3 </center>
                                                        </td>

                                                        <td>
                                                            <span> <input id="Slitlog_drp2_date8" type="date" style="width:100px"> </span>
                                                        </td>
                                                        
                                                        <td>
                                                            <span> <input id="dropper3_1" type="text" style="width:100px"> </span>
                                                        </td>
                                                        
                                                        <td>
                                                            <center> 3 </center>
                                                        </td>
                                             
                                                        <td>
                                                            <span> <input id="dropper3_2" type="text" style="width:100px"> </span>
                                                        </td>
                                                        
                                                        <td>
                                                            <center> 3 </center>
                                                        </td>
                                                        
                                                        <td>
                                                            <span> <input id="Slitlog_drp2_remarks8" type="text" style="width:100px"> </span>
                                                        </td>

                                                        <td>
                                                            <span> <input id="Slitlog_drp2_initial8" type="text" style="width:100px"> </span>
                                                        </td>


                                                    </tr>
                                         <tr>
                                                        <td>
                                                           <center> 4 </center>
                                                        </td>

                                                        <td>
                                                            <span> <input id="Slitlog_drp2_date9" type="date" style="width:100px"> </span>
                                                        </td>
                                                        
                                                        <td>
                                                            <span> <input id="dropper4_1" type="text" style="width:100px"> </span>
                                                        </td>
                                                        
                                                        <td>
                                                            <center> 4 </center>
                                                        </td>
                                             
                                                        <td>
                                                            <span> <input id="dropper4_2" type="text" style="width:100px"> </span>
                                                        </td>
                                                        
                                                        <td>
                                                            <center> 4 </center>
                                                        </td>
                                                        <td>
                                                            <span> <input id="Slitlog_drp2_remarks9" type="text" style="width:100px"> </span>
                                                        </td>

                                                        <td>
                                                            <span> <input id="Slitlog_drp2_initials9" type="text" style="width:100px"> </span>
                                                        </td>


                                                    </tr>
                                                    <tr>
                                                        <td>
                                                           <center> 5 </center>
                                                        </td>
                                                        
                                                        <td>
                                                            <span> <input id="Slitlog_drp2_date10" type="date" style="width:100px"> </span>
                                                        </td>

                                                        <td>
                                                            <span> <input id="dropper5_1" type="text" style="width:100px"> </span>
                                                        </td>
                                                        
                                                        <td>
                                                            <center> 5 </center>
                                                        </td>
                                             
                                                        <td>
                                                            <span> <input id="dropper5_2" type="text" style="width:100px"> </span>
                                                        </td>
                                                        
                                                        <td>
                                                            <center> 5 </center>
                                                        </td>

                                                        <td>
                                                            <span> <input id="Slitlog_drp2_remarks10" type="text" style="width:100px"> </span>
                                                        </td>

                                                        <td>
                                                            <span> <input id="Slitlog_drp2_initials10" type="text" style="width:100px"> </span>
                                                        </td>


                                                    </tr>
                                                   
                                        
                                                    <!--table2-->
                                                    <tr>
                                                        <td colspan="8" bgcolor="#C0C0C0">								
                                                            <center style="font-size:14px;font-weight:bold;margin-right:50px;height:20px"></center>
                                                        </td>
                                                    </tr>
                                             </table>
                                
                               
                            </div>
                            <div id="slitDropper3" style="width: 98%; height: 590px; margin-left: 2%; float: left;">
                                        
                                <!--<hr style="height:1px;border:none;color:#333;background-color:#333;" />-->
                                <h4 style="text-align: center; font-size: 14px;">ADDITIONAL DROPPER: AT HOME SLIT BUILD UP SCHEDULE</h4>
                                <center><input id="SD3_save_button" type="button" class="btn btn-primary" value="SAVE" style="margin: auto; margin-top: 7px;"></center>
                                 <input type="button" class="btn btn-primary" value="Prev" style="float:left;margin-top:10px" id="dropperPrev2">
                                 <table cellspacing="0" cellpadding="0" border="1" style="width:49%;background-color:white;float:left;margin-top:50px;margin-left:50px">
                                                    <tr>
                                                        <td colspan="6" bgcolor="#C0C0C0">								
                                                            <center style="font-size:14px;font-weight:bold;margin-right:50px;">BUILD-UP #1 VIALS</center>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <span style="font-size:14px;font-weight:bold;margin-right:50px;">Day</span>
                                                        </td>
                                                        <td>
        `                                                    <span style="font-size:14px;font-weight:bold;margin-right:50px;">Location</span>
                                                        </td>
                                                        <td>
                                                            <span style="font-size:14px;font-weight:bold;margin-right:50px;">Date</span>
                                                        </td>
                                                        <td>
                                                            <span style="font-size:14px;font-weight:bold;margin-right:50px;"># of Drops</span>
                                                        </td>
                                                        <td>
                                                            <span style="font-size:14px;font-weight:bold;margin-right:50px;width:600px">Remarks/Reactions</span>
                                                        </td>
                                                        <td>
                                                            <span style="font-size:14px;font-weight:bold;margin-right:50px;">Initials</span>
                                                        </td>


                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <center> 1 </center>
                                                        </td>

                                                        <td>
                                                            <span> Office </span>
                                                        </td>

                                                        <td>
                                                            <span> <input id="Slitlog_drp3_date1" type="date"> </span>
                                                        </td>

                                                        <td>
                                                            <center> 1 </center>
                                                        </td>

                                                        <td>
                                                            <span> <input id="Slitlog_drp3_remarks1" type="text"> </span>
                                                        </td>

                                                        <td>
                                                            <span> <input id="Slitlog_drp3_initials1" type="text"> </span>
                                                        </td>


                                                    </tr>
                                         <tr>
                                                        <td>
                                                            <center> 2 </center>
                                                        </td>

                                                        <td>
                                                            <span> Home </span>
                                                        </td>

                                                        <td>
                                                            <span> <input id="Slitlog_drp3_date2" type="date"> </span>
                                                        </td>

                                                        <td>
                                                            <center> 2 </center>
                                                        </td>

                                                        <td>
                                                            <span> <input id="Slitlog_drp3_remarks2" type="text"> </span>
                                                        </td>

                                                        <td>
                                                            <span> <input id="Slitlog_drp3_initials2" type="text"> </span>
                                                        </td>


                                                    </tr>
                                         <tr>
                                                        <td>
                                                            <center> 3 </center>
                                                        </td>

                                                        <td>
                                                            <span> Home </span>
                                                        </td>

                                                        <td>
                                                            <span> <input id="Slitlog_drp3_date3" type="date"> </span>
                                                        </td>

                                                        <td>
                                                            <center> 3 </center>
                                                        </td>

                                                        <td>
                                                            <span> <input id="Slitlog_drp3_remarks3" type="text"> </span>
                                                        </td>

                                                        <td>
                                                            <span> <input id="Slitlog_drp3_initials3" type="text"> </span>
                                                        </td>


                                                    </tr>
                                         <tr>
                                                        <td>
                                                           <center> 4 </center>
                                                        </td>

                                                        <td>
                                                            <span> Home </span>
                                                        </td>

                                                        <td>
                                                            <span> <input id="Slitlog_drp3_date4" type="date"> </span>
                                                        </td>

                                                        <td>
                                                            <center> 4 </center>
                                                        </td>

                                                        <td>
                                                            <span> <input id="Slitlog_drp3_remarks4" type="text"> </span>
                                                        </td>

                                                        <td>
                                                            <span> <input id="Slitlog_drp3_initials4" type="text"> </span>
                                                        </td>


                                                    </tr>
                                         <tr>
                                                        <td>
                                                           <center> 5 </center>
                                                        </td>

                                                        <td>
                                                            <span> Home </span>
                                                        </td>

                                                        <td>
                                                            <span> <input id="Slitlog_drp3_date5" type="date"> </span>
                                                        </td>

                                                        <td>
                                                            <center> 5 </center>
                                                        </td>

                                                        <td>
                                                            <span> <input id="Slitlog_drp3_remarks5" type="text"> </span>
                                                        </td>

                                                        <td>
                                                            <span> <input id="Slitlog_drp3_initials5" type="text"> </span>
                                                        </td>


                                                    </tr>
                                                    <!--table2-->
                                                    <tr>
                                                        <td colspan="6" bgcolor="#C0C0C0">								
                                                            <center style="font-size:14px;font-weight:bold;margin-right:50px;">BUILD-UP #2 VIALS</center>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <span style="font-size:14px;font-weight:bold;margin-right:50px;">Day</span>
                                                        </td>
                                                        <td>
                                                            <span style="font-size:14px;font-weight:bold;margin-right:50px;">Location</span>
                                                        </td>
                                                        <td>
                                                            <span style="font-size:14px;font-weight:bold;margin-right:50px;">Date</span>
                                                        </td>
                                                        <td>
                                                            <span style="font-size:14px;font-weight:bold;margin-right:50px;"># of Drops</span>
                                                        </td>
                                                        <td>
                                                            <span style="font-size:14px;font-weight:bold;margin-right:50px;width:600px">Remarks/Reactions</span>
                                                        </td>
                                                        <td>
                                                            <span style="font-size:14px;font-weight:bold;margin-right:50px;">Initials</span>
                                                        </td>


                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <center> 6 </center>
                                                        </td>

                                                        <td>
                                                            <span> Home </span>
                                                        </td>

                                                        <td>
                                                            <span> <input id="Slitlog_drp3_date6" type="date"> </span>
                                                        </td>

                                                        <td>
                                                            <center> 1 </center>
                                                        </td>

                                                        <td>
                                                            <span> <input id="Slitlog_drp3_remarks6" type="text"> </span>
                                                        </td>

                                                        <td>
                                                            <span> <input id="Slitlog_drp3_initials6" type="text"> </span>
                                                        </td>


                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <center> 7 </center>
                                                        </td>

                                                        <td>
                                                            <span> Home </span>
                                                        </td>

                                                        <td>
                                                            <span> <input id="Slitlog_drp3_date7" type="date"> </span>
                                                        </td>

                                                        <td>
                                                            <center> 2 </center>
                                                        </td>

                                                        <td>
                                                            <span> <input id="Slitlog_drp3_remarks7" type="text"> </span>
                                                        </td>

                                                        <td>
                                                            <span> <input id="Slitlog_drp3_initials7" type="text"> </span>
                                                        </td>


                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <center> 8 </center>
                                                        </td>

                                                        <td>
                                                            <span> Home </span>
                                                        </td>

                                                        <td>
                                                            <span> <input id="Slitlog_drp3_date8" type="date"> </span>
                                                        </td>

                                                        <td>
                                                            <center> 3 </center>
                                                        </td>

                                                        <td>
                                                            <span> <input id="Slitlog_drp3_remarks8" type="text"> </span>
                                                        </td>

                                                        <td>
                                                            <span> <input id="Slitlog_drp3_initials8" type="text"> </span>
                                                        </td>


                                                    </tr>
                                                    <tr>
                                                        <td>
                                                           <center> 9 </center>
                                                        </td>

                                                        <td>
                                                            <span> Home </span>
                                                        </td>

                                                        <td>
                                                            <span> <input id="Slitlog_drp3_date9" type="date"> </span>
                                                        </td>

                                                        <td>
                                                            <center> 4 </center>
                                                        </td>

                                                        <td>
                                                            <span> <input id="Slitlog_drp3_remarks9" type="text"> </span>
                                                        </td>

                                                        <td>
                                                            <span> <input id="Slitlog_drp3_initials9" type="text"> </span>
                                                        </td>


                                                    </tr>
                                                    <tr>
                                                        <td>
                                                           <center> 10 </center>
                                                        </td>

                                                        <td>
                                                            <span> Home </span>
                                                        </td>

                                                        <td>
                                                            <span> <input id="Slitlog_drp3_date10" type="date"> </span>
                                                        </td>

                                                        <td>
                                                            <center> 5 </center>
                                                        </td>

                                                        <td>
                                                            <span> <input id="Slitlog_drp3_remarks10" type="text"> </span>
                                                        </td>

                                                        <td>
                                                            <span> <input id="Slitlog_drp3_initials10" type="text"> </span>
                                                        </td>


                                                    </tr>
                                         
                                        
                                                    <!--table2-->
                                                    <tr>
                                                        <td colspan="6" bgcolor="#C0C0C0">								
                                                            <center style="font-size:14px;font-weight:bold;margin-right:50px;height:20px"></center>
                                                        </td>
                                                    </tr>
                                             </table>
                                
                                
                            </div>
                            </div>
                            <div id="switchMaintanence" style="display:none">
                            <div id="slitMaintanence1" style="width: 98%; height: 590px; margin-left: 2%; float: left;">
                                        
                                <!--<hr style="height:1px;border:none;color:#333;background-color:#333;" />-->
                                <h4 style="text-align: center; font-size: 14px;">MAINTANENCE 1 - SLIT BUILD UP SCHEDULE</h4>
                                <center><input id="maintain_save1" type="button" class="btn btn-primary" value="SAVE" style="margin: auto; margin-top: 7px;"></center>
                                <input type="button" class="btn btn-primary" value="Next" style="float:right;margin-top:10px" id="maintenanceNext2">
                                <table cellspacing="0" cellpadding="0" border="1" style="width:49%;background-color:white;float:left;margin-top:50px;margin-left:20px">
                                                    <tr>
                                                        <td colspan="6" bgcolor="#C0C0C0">								
                                                            <center style="font-size:14px;font-weight:bold;margin-right:50px;">MAINTANENCE-VIAL #1 VIALS</center>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <span style="font-size:14px;font-weight:bold;margin-right:50px;">Day</span>
                                                        </td>
                                                      
                                                        <td>
                                                            <span style="font-size:14px;font-weight:bold;margin-right:50px;">Date</span>
                                                        </td>
                                                        <td>
                                                            <span style="font-size:14px;font-weight:bold;margin-right:50px;">Vial Number</span>
                                                        </td>
                                                        <td>
                                                            <span style="font-size:14px;font-weight:bold;margin-right:50px;"># of Pumps</span>
                                                        </td>
                                                        <td>
                                                            <span style="font-size:14px;font-weight:bold;margin-right:50px;width:600px">Remarks/Reactions</span>
                                                        </td>
                                                        <td>
                                                            <span style="font-size:14px;font-weight:bold;margin-right:50px;">Initials</span>
                                                        </td>


                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <center> 1 </center>
                                                        </td>

                                                        <td>
                                                            <span> <input id="Slitlog_main1_date1" type="date"> </span>
                                                        </td>
                                                        
                                                        <td>
                                                            <span> <input id="Slitlog_main1_num1" type="text"> </span>
                                                        </td>

                                                        <td>
                                                            <center> 1 </center>
                                                        </td>

                                                        <td>
                                                            <span> <input id="Slitlog_main1_remarks1" type="text"> </span>
                                                        </td>

                                                        <td>
                                                            <span> <input id="Slitlog_main1_initials1" type="text"> </span>
                                                        </td>


                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <center> 2 </center>
                                                        </td>

                                                       <td>
                                                            <span> <input id="Slitlog_main1_date2" type="date"> </span>
                                                        </td>
                                                        
                                                        <td>
                                                            <span> <input id="Slitlog_main1_num2" type="text"> </span>
                                                        </td>

                                                        <td>
                                                            <center> 1 </center>
                                                        </td>

                                                        <td>
                                                            <span> <input id="Slitlog_main1_remarks2" type="text"> </span>
                                                        </td>

                                                        <td>
                                                            <span> <input id="Slitlog_main1_initials2" type="text"> </span>
                                                        </td>

                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <center> 3 </center>
                                                        </td>

                                                        <td>
                                                            <span> <input id="Slitlog_main1_date3" type="date"> </span>
                                                        </td>
                                                        
                                                        <td>
                                                            <span> <input id="Slitlog_main1_num3" type="text"> </span>
                                                        </td>

                                                        <td>
                                                            <center> 1 </center>
                                                        </td>

                                                        <td>
                                                            <span> <input id="Slitlog_main1_remarks3" type="text"> </span>
                                                        </td>

                                                        <td>
                                                            <span> <input id="Slitlog_main1_initials3" type="text"> </span>
                                                        </td>

                                                    </tr>
                                                    <tr>
                                                        <td>
                                                           <center> 4 </center>
                                                        </td>

                                                       <td>
                                                            <span> <input id="Slitlog_main1_date4" type="date"> </span>
                                                        </td>
                                                        
                                                        <td>
                                                            <span> <input id="Slitlog_main1_num4" type="text"> </span>
                                                        </td>

                                                        <td>
                                                            <center> 1 </center>
                                                        </td>

                                                        <td>
                                                            <span> <input id="Slitlog_main1_remarks4" type="text"> </span>
                                                        </td>

                                                        <td>
                                                            <span> <input id="Slitlog_main1_initials4" type="text"> </span>
                                                        </td>

                                                    </tr>
                                                    <tr>
                                                        <td>
                                                           <center> 5 </center>
                                                        </td>
                                                        <td>
                                                            <span> <input id="Slitlog_main1_date5" type="date"> </span>
                                                        </td>
                                                        
                                                        <td>
                                                            <span> <input id="Slitlog_main1_num5" type="text"> </span>
                                                        </td>

                                                        <td>
                                                            <center> 1 </center>
                                                        </td>

                                                        <td>
                                                            <span> <input id="Slitlog_main1_remarks5" type="text"> </span>
                                                        </td>

                                                        <td>
                                                            <span> <input id="Slitlog_main1_initials5" type="text"> </span>
                                                        </td>

                                                    </tr>
                                                    <tr>
                                                        <td>
                                                           <center> 6 </center>
                                                        </td>
                                                        <td>
                                                            <span> <input id="Slitlog_main1_date6" type="date"> </span>
                                                        </td>
                                                        
                                                        <td>
                                                            <span> <input id="Slitlog_main1_num6" type="text"> </span>
                                                        </td>

                                                        <td>
                                                            <center> 1 </center>
                                                        </td>

                                                        <td>
                                                            <span> <input id="Slitlog_main1_remarks6" type="text"> </span>
                                                        </td>

                                                        <td>
                                                            <span> <input id="Slitlog_main1_initials6" type="text"> </span>
                                                        </td>

                                                    </tr>
                                     <tr>
                                                        <td>
                                                           <center> 7 </center>
                                                        </td>
                                                        <td>
                                                            <span> <input id="Slitlog_main1_date7" type="date"> </span>
                                                        </td>
                                                        
                                                        <td>
                                                            <span> <input id="Slitlog_main1_num7" type="text"> </span>
                                                        </td>

                                                        <td>
                                                            <center> 1 </center>
                                                        </td>

                                                        <td>
                                                            <span> <input id="Slitlog_main1_remarks7" type="text"> </span>
                                                        </td>

                                                        <td>
                                                            <span> <input id="Slitlog_main1_initials7" type="text"> </span>
                                                        </td>

                                                    </tr> <tr>
                                                        <td>
                                                           <center> 8 </center>
                                                        </td>
                                                        <td>
                                                            <span> <input id="Slitlog_main1_date8" type="date"> </span>
                                                        </td>
                                                        
                                                        <td>
                                                            <span> <input id="Slitlog_main1_num8" type="text"> </span>
                                                        </td>

                                                        <td>
                                                            <center> 1 </center>
                                                        </td>

                                                        <td>
                                                            <span> <input id="Slitlog_main1_remarks8" type="text"> </span>
                                                        </td>

                                                        <td>
                                                            <span> <input id="Slitlog_main1_initials8" type="text"> </span>
                                                        </td>

                                                    </tr> <tr>
                                                        <td>
                                                           <center> 9 </center>
                                                        </td>
                                                        <td>
                                                            <span> <input id="Slitlog_main1_date9" type="date"> </span>
                                                        </td>
                                                        
                                                        <td>
                                                            <span> <input id="Slitlog_main1_num9" type="text"> </span>
                                                        </td>

                                                        <td>
                                                            <center> 1 </center>
                                                        </td>

                                                        <td>
                                                            <span> <input id="Slitlog_main1_remarks9" type="text"> </span>
                                                        </td>

                                                        <td>
                                                            <span> <input id="Slitlog_main1_initials9" type="text"> </span>
                                                        </td>

                                                    </tr> <tr>
                                                        <td>
                                                           <center> 10 </center>
                                                        </td>
                                                        <td>
                                                            <span> <input id="Slitlog_main1_date10" type="date"> </span>
                                                        </td>
                                                        
                                                        <td>
                                                            <span> <input id="Slitlog_main1_num10" type="text"> </span>
                                                        </td>

                                                        <td>
                                                            <center> 1 </center>
                                                        </td>

                                                        <td>
                                                            <span> <input id="Slitlog_main1_remarks10" type="text"> </span>
                                                        </td>

                                                        <td>
                                                            <span> <input id="Slitlog_main1_initials10" type="text"> </span>
                                                        </td>

                                                    </tr> 
                                                   
                                                    <tr>
                                                        <td colspan="6" bgcolor="#C0C0C0">								
                                                            <center style="font-size:14px;font-weight:bold;margin-right:50px;height:20px"></center>
                                                        </td>
                                                    </tr>
                                             </table>
                                
                                </div>
                                <div id="slitMaintanence2" style="width: 98%; height: 590px; margin-left: 2%; float: left;">
                                        
                                <!--<hr style="height:1px;border:none;color:#333;background-color:#333;" />-->
                                <h4 style="text-align: center; font-size: 14px;">MAINTANENCE 2 - SLIT BUILD UP SCHEDULE</h4>
                                    <center><input id="maintain_save2" type="button" class="btn btn-primary" value="SAVE" style="margin: auto; margin-top: 7px;"></center>
                                <input type="button" class="btn btn-primary" value="Prev" style="float:left" id="maintenancePrev1">
                                <input type="button" class="btn btn-primary" value="Next" style="float:right" id="maintenanceNext3">
                                <table cellspacing="0" cellpadding="0" border="1" style="width:49%;background-color:white;float:left;margin-top:30px;margin-left:20px">
                                                    <tr>
                                                        <td colspan="6" bgcolor="#C0C0C0">								
                                                            <center style="font-size:14px;font-weight:bold;margin-right:50px;">MAINTANENCE-VIAL #2 VIALS</center>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <span style="font-size:14px;font-weight:bold;margin-right:50px;">Day</span>
                                                        </td>
                                                      
                                                        <td>
                                                            <span style="font-size:14px;font-weight:bold;margin-right:50px;">Date</span>
                                                        </td>
                                                        <td>
                                                            <span style="font-size:14px;font-weight:bold;margin-right:50px;">Vial Number</span>
                                                        </td>
                                                        <td>
                                                            <span style="font-size:14px;font-weight:bold;margin-right:50px;"># of Pumps</span>
                                                        </td>
                                                        <td>
                                                            <span style="font-size:14px;font-weight:bold;margin-right:50px;width:600px">Remarks/Reactions</span>
                                                        </td>
                                                        <td>
                                                            <span style="font-size:14px;font-weight:bold;margin-right:50px;">Initials</span>
                                                        </td>


                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <center> 1 </center>
                                                        </td>

                                                        <td>
                                                            <span> <input id="Slitlog_main2_date1" type="date"> </span>
                                                        </td>
                                                        
                                                        <td>
                                                            <span> <input id="Slitlog_main2_num1" type="text"> </span>
                                                        </td>

                                                        <td>
                                                            <center> 1 </center>
                                                        </td>

                                                        <td>
                                                            <span> <input id="Slitlog_main2_remarks1" type="text"> </span>
                                                        </td>

                                                        <td>
                                                            <span> <input id="Slitlog_main2_initials1" type="text"> </span>
                                                        </td>


                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <center> 2 </center>
                                                        </td>

                                                       <td>
                                                            <span> <input id="Slitlog_main2_date2" type="date"> </span>
                                                        </td>
                                                        
                                                        <td>
                                                            <span> <input id="Slitlog_main2_num2" type="text"> </span>
                                                        </td>

                                                        <td>
                                                            <center> 1 </center>
                                                        </td>

                                                        <td>
                                                            <span> <input id="Slitlog_main2_remarks2" type="text"> </span>
                                                        </td>

                                                        <td>
                                                            <span> <input id="Slitlog_main2_initials2" type="text"> </span>
                                                        </td>

                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <center> 3 </center>
                                                        </td>

                                                        <td>
                                                            <span> <input id="Slitlog_main2_date3" type="date"> </span>
                                                        </td>
                                                        
                                                        <td>
                                                            <span> <input id="Slitlog_main2_num3" type="text"> </span>
                                                        </td>

                                                        <td>
                                                            <center> 1 </center>
                                                        </td>

                                                        <td>
                                                            <span> <input id="Slitlog_main2_remarks3" type="text"> </span>
                                                        </td>

                                                        <td>
                                                            <span> <input id="Slitlog_main2_initials3" type="text"> </span>
                                                        </td>

                                                    </tr>
                                                    <tr>
                                                        <td>
                                                           <center> 4 </center>
                                                        </td>

                                                       <td>
                                                            <span> <input id="Slitlog_main2_date4" type="date"> </span>
                                                        </td>
                                                        
                                                        <td>
                                                            <span> <input id="Slitlog_main2_num4" type="text"> </span>
                                                        </td>

                                                        <td>
                                                            <center> 1 </center>
                                                        </td>

                                                        <td>
                                                            <span> <input id="Slitlog_main2_remarks4" type="text"> </span>
                                                        </td>

                                                        <td>
                                                            <span> <input id="Slitlog_main2_initials4" type="text"> </span>
                                                        </td>

                                                    </tr>
                                                    <tr>
                                                        <td>
                                                           <center> 5 </center>
                                                        </td>
                                                        <td>
                                                            <span> <input id="Slitlog_main2_date5" type="date"> </span>
                                                        </td>
                                                        
                                                        <td>
                                                            <span> <input id="Slitlog_main2_num5" type="text"> </span>
                                                        </td>

                                                        <td>
                                                            <center> 1 </center>
                                                        </td>

                                                        <td>
                                                            <span> <input id="Slitlog_main2_remarks5" type="text"> </span>
                                                        </td>

                                                        <td>
                                                            <span> <input id="Slitlog_main2_initials5" type="text"> </span>
                                                        </td>

                                                    </tr>
                                                    <tr>
                                                        <td>
                                                           <center> 6 </center>
                                                        </td>
                                                        <td>
                                                            <span> <input id="Slitlog_main2_date6" type="date"> </span>
                                                        </td>
                                                        
                                                        <td>
                                                            <span> <input id="Slitlog_main2_num6" type="text"> </span>
                                                        </td>

                                                        <td>
                                                            <center> 1 </center>
                                                        </td>

                                                        <td>
                                                            <span> <input id="Slitlog_main2_remarks6" type="text"> </span>
                                                        </td>

                                                        <td>
                                                            <span> <input id="Slitlog_main2_initials6" type="text"> </span>
                                                        </td>

                                                    </tr>
                                     <tr>
                                                        <td>
                                                           <center> 7 </center>
                                                        </td>
                                                        <td>
                                                            <span> <input id="Slitlog_main2_date7" type="date"> </span>
                                                        </td>
                                                        
                                                        <td>
                                                            <span> <input id="Slitlog_main2_num7" type="text"> </span>
                                                        </td>

                                                        <td>
                                                            <center> 1 </center>
                                                        </td>

                                                        <td>
                                                            <span> <input id="Slitlog_main2_remarks7" type="text"> </span>
                                                        </td>

                                                        <td>
                                                            <span> <input id="Slitlog_main2_initials7" type="text"> </span>
                                                        </td>

                                                    </tr> <tr>
                                                        <td>
                                                           <center> 8 </center>
                                                        </td>
                                                        <td>
                                                            <span> <input id="Slitlog_main2_date8" type="date"> </span>
                                                        </td>
                                                        
                                                        <td>
                                                            <span> <input id="Slitlog_main2_num8" type="text"> </span>
                                                        </td>

                                                        <td>
                                                            <center> 1 </center>
                                                        </td>

                                                        <td>
                                                            <span> <input id="Slitlog_main2_remarks8" type="text"> </span>
                                                        </td>

                                                        <td>
                                                            <span> <input id="Slitlog_main2_initials8" type="text"> </span>
                                                        </td>

                                                    </tr> <tr>
                                                        <td>
                                                           <center> 9 </center>
                                                        </td>
                                                        <td>
                                                            <span> <input id="Slitlog_main2_date9" type="date"> </span>
                                                        </td>
                                                        
                                                        <td>
                                                            <span> <input id="Slitlog_main2_num9" type="text"> </span>
                                                        </td>

                                                        <td>
                                                            <center> 1 </center>
                                                        </td>

                                                        <td>
                                                            <span> <input id="Slitlog_main2_remarks9" type="text"> </span>
                                                        </td>

                                                        <td>
                                                            <span> <input id="Slitlog_main2_initials9" type="text"> </span>
                                                        </td>

                                                    </tr> <tr>
                                                        <td>
                                                           <center> 10 </center>
                                                        </td>
                                                        <td>
                                                            <span> <input id="Slitlog_main2_date10" type="date"> </span>
                                                        </td>
                                                        
                                                        <td>
                                                            <span> <input id="Slitlog_main2_num10" type="text"> </span>
                                                        </td>

                                                        <td>
                                                            <center> 1 </center>
                                                        </td>

                                                        <td>
                                                            <span> <input id="Slitlog_main2_remarks10" type="text"> </span>
                                                        </td>

                                                        <td>
                                                            <span> <input id="Slitlog_main2_initials10" type="text"> </span>
                                                        </td>

                                                    </tr> 
                                                   
                                                    <tr>
                                                        <td colspan="6" bgcolor="#C0C0C0">								
                                                            <center style="font-size:14px;font-weight:bold;margin-right:50px;height:20px"></center>
                                                        </td>
                                                    </tr>
                                             </table>
                                
                                </div>
                                <div id="slitMaintanence3" style="width: 98%; height: 590px; margin-left: 2%; float: left;">
                                        
                                <!--<hr style="height:1px;border:none;color:#333;background-color:#333;" />-->
                                <h4 style="text-align: center; font-size: 14px;">MAINTANENCE 3 - SLIT BUILD UP SCHEDULE</h4>
                                    <center><input id="maintain_save3" type="button" class="btn btn-primary" value="SAVE" style="margin: auto; margin-top: 7px;"></center>
                                <input type="button" class="btn btn-primary" value="Prev" style="float:left" id="maintenancePrev2">
                                <input type="button" class="btn btn-primary" value="Next" style="float:right" id="maintenanceNext4">
                                <table cellspacing="0" cellpadding="0" border="1" style="width:49%;background-color:white;float:left;margin-top:30px;margin-left:20px">
                                                    <tr>
                                                        <td colspan="6" bgcolor="#C0C0C0">								
                                                            <center style="font-size:14px;font-weight:bold;margin-right:50px;">MAINTANENCE-VIAL #3 VIALS</center>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <span style="font-size:14px;font-weight:bold;margin-right:50px;">Day</span>
                                                        </td>
                                                      
                                                        <td>
                                                            <span style="font-size:14px;font-weight:bold;margin-right:50px;">Date</span>
                                                        </td>
                                                        <td>
                                                            <span style="font-size:14px;font-weight:bold;margin-right:50px;">Vial Number</span>
                                                        </td>
                                                        <td>
                                                            <span style="font-size:14px;font-weight:bold;margin-right:50px;"># of Pumps</span>
                                                        </td>
                                                        <td>
                                                            <span style="font-size:14px;font-weight:bold;margin-right:50px;width:600px">Remarks/Reactions</span>
                                                        </td>
                                                        <td>
                                                            <span style="font-size:14px;font-weight:bold;margin-right:50px;">Initials</span>
                                                        </td>


                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <center> 1 </center>
                                                        </td>

                                                        <td>
                                                            <span> <input id="Slitlog_main3_date1" type="date"> </span>
                                                        </td>
                                                        
                                                        <td>
                                                            <span> <input id="Slitlog_main3_num1" type="text"> </span>
                                                        </td>

                                                        <td>
                                                            <center> 1 </center>
                                                        </td>

                                                        <td>
                                                            <span> <input id="Slitlog_main3_remarks1" type="text"> </span>
                                                        </td>

                                                        <td>
                                                            <span> <input id="Slitlog_main3_initials1" type="text"> </span>
                                                        </td>


                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <center> 2 </center>
                                                        </td>

                                                       <td>
                                                            <span> <input id="Slitlog_main3_date2" type="date"> </span>
                                                        </td>
                                                        
                                                        <td>
                                                            <span> <input id="Slitlog_main3_num2" type="text"> </span>
                                                        </td>

                                                        <td>
                                                            <center> 1 </center>
                                                        </td>

                                                        <td>
                                                            <span> <input id="Slitlog_main3_remarks2" type="text"> </span>
                                                        </td>

                                                        <td>
                                                            <span> <input id="Slitlog_main3_initials2" type="text"> </span>
                                                        </td>

                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <center> 3 </center>
                                                        </td>

                                                        <td>
                                                            <span> <input id="Slitlog_main3_date3" type="date"> </span>
                                                        </td>
                                                        
                                                        <td>
                                                            <span> <input id="Slitlog_main3_num3" type="text"> </span>
                                                        </td>

                                                        <td>
                                                            <center> 1 </center>
                                                        </td>

                                                        <td>
                                                            <span> <input id="Slitlog_main3_remarks3" type="text"> </span>
                                                        </td>

                                                        <td>
                                                            <span> <input id="Slitlog_main3_initials3" type="text"> </span>
                                                        </td>

                                                    </tr>
                                                    <tr>
                                                        <td>
                                                           <center> 4 </center>
                                                        </td>

                                                       <td>
                                                            <span> <input id="Slitlog_main3_date4" type="date"> </span>
                                                        </td>
                                                        
                                                        <td>
                                                            <span> <input id="Slitlog_main3_num4" type="text"> </span>
                                                        </td>

                                                        <td>
                                                            <center> 1 </center>
                                                        </td>

                                                        <td>
                                                            <span> <input id="Slitlog_main3_remarks4" type="text"> </span>
                                                        </td>

                                                        <td>
                                                            <span> <input id="Slitlog_main3_initials4" type="text"> </span>
                                                        </td>

                                                    </tr>
                                                    <tr>
                                                        <td>
                                                           <center> 5 </center>
                                                        </td>
                                                        <td>
                                                            <span> <input id="Slitlog_main3_date5" type="date"> </span>
                                                        </td>
                                                        
                                                        <td>
                                                            <span> <input id="Slitlog_main3_num5" type="text"> </span>
                                                        </td>

                                                        <td>
                                                            <center> 1 </center>
                                                        </td>

                                                        <td>
                                                            <span> <input id="Slitlog_main3_remarks5" type="text"> </span>
                                                        </td>

                                                        <td>
                                                            <span> <input id="Slitlog_main3_initials5" type="text"> </span>
                                                        </td>

                                                    </tr>
                                                    <tr>
                                                        <td>
                                                           <center> 6 </center>
                                                        </td>
                                                        <td>
                                                            <span> <input id="Slitlog_main3_date6" type="date"> </span>
                                                        </td>
                                                        
                                                        <td>
                                                            <span> <input id="Slitlog_main3_num6" type="text"> </span>
                                                        </td>

                                                        <td>
                                                            <center> 1 </center>
                                                        </td>

                                                        <td>
                                                            <span> <input id="Slitlog_main3_remarks6" type="text"> </span>
                                                        </td>

                                                        <td>
                                                            <span> <input id="Slitlog_main3_initials6" type="text"> </span>
                                                        </td>

                                                    </tr>
                                     <tr>
                                                        <td>
                                                           <center> 7 </center>
                                                        </td>
                                                        <td>
                                                            <span> <input id="Slitlog_main3_date7" type="date"> </span>
                                                        </td>
                                                        
                                                        <td>
                                                            <span> <input id="Slitlog_main3_num7" type="text"> </span>
                                                        </td>

                                                        <td>
                                                            <center> 1 </center>
                                                        </td>

                                                        <td>
                                                            <span> <input id="Slitlog_main3_remarks7" type="text"> </span>
                                                        </td>

                                                        <td>
                                                            <span> <input id="Slitlog_main3_initials7" type="text"> </span>
                                                        </td>

                                                    </tr> <tr>
                                                        <td>
                                                           <center> 8 </center>
                                                        </td>
                                                        <td>
                                                            <span> <input id="Slitlog_main3_date8" type="date"> </span>
                                                        </td>
                                                        
                                                        <td>
                                                            <span> <input id="Slitlog_main3_num8" type="text"> </span>
                                                        </td>

                                                        <td>
                                                            <center> 1 </center>
                                                        </td>

                                                        <td>
                                                            <span> <input id="Slitlog_main3_remarks8" type="text"> </span>
                                                        </td>

                                                        <td>
                                                            <span> <input id="Slitlog_main3_initials8" type="text"> </span>
                                                        </td>

                                                    </tr> <tr>
                                                        <td>
                                                           <center> 9 </center>
                                                        </td>
                                                        <td>
                                                            <span> <input id="Slitlog_main3_date9" type="date"> </span>
                                                        </td>
                                                        
                                                        <td>
                                                            <span> <input id="Slitlog_main3_num9" type="text"> </span>
                                                        </td>

                                                        <td>
                                                            <center> 1 </center>
                                                        </td>

                                                        <td>
                                                            <span> <input id="Slitlog_main3_remarks9" type="text"> </span>
                                                        </td>

                                                        <td>
                                                            <span> <input id="Slitlog_main3_initials9" type="text"> </span>
                                                        </td>

                                                    </tr> <tr>
                                                        <td>
                                                           <center> 10 </center>
                                                        </td>
                                                        <td>
                                                            <span> <input id="Slitlog_main3_date10" type="date"> </span>
                                                        </td>
                                                        
                                                        <td>
                                                            <span> <input id="Slitlog_main3_num10" type="text"> </span>
                                                        </td>

                                                        <td>
                                                            <center> 1 </center>
                                                        </td>

                                                        <td>
                                                            <span> <input id="Slitlog_main3_remarks10" type="text"> </span>
                                                        </td>

                                                        <td>
                                                            <span> <input id="Slitlog_main3_initials10" type="text"> </span>
                                                        </td>

                                                    </tr> 
                                                   
                                                    <tr>
                                                        <td colspan="6" bgcolor="#C0C0C0">								
                                                            <center style="font-size:14px;font-weight:bold;margin-right:50px;height:20px"></center>
                                                        </td>
                                                    </tr>
                                             </table>
                                
                                </div>
                                <div id="slitMaintanence4" style="width: 98%; height: 590px; margin-left: 2%; float: left;">
                                        
                                <!--<hr style="height:1px;border:none;color:#333;background-color:#333;" />-->
                                <h4 style="text-align: center; font-size: 14px;">MAINTANENCE 4 - SLIT BUILD UP SCHEDULE</h4>
                                    <center><input id="maintain_save4" type="button" class="btn btn-primary" value="SAVE" style="margin: auto; margin-top: 7px;"></center>
                                <input type="button" class="btn btn-primary" value="Prev" style="float:left" id="maintenancePrev3">
                                <table cellspacing="0" cellpadding="0" border="1" style="width:49%;background-color:white;float:left;margin-top:30px;margin-left:20px">
                                                    <tr>
                                                        <td colspan="6" bgcolor="#C0C0C0">								
                                                            <center style="font-size:14px;font-weight:bold;margin-right:50px;">MAINTANENCE-VIAL #4 VIALS</center>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <span style="font-size:14px;font-weight:bold;margin-right:50px;">Day</span>
                                                        </td>
                                                      
                                                        <td>
                                                            <span style="font-size:14px;font-weight:bold;margin-right:50px;">Date</span>
                                                        </td>
                                                        <td>
                                                            <span style="font-size:14px;font-weight:bold;margin-right:50px;">Vial Number</span>
                                                        </td>
                                                        <td>
                                                            <span style="font-size:14px;font-weight:bold;margin-right:50px;"># of Pumps</span>
                                                        </td>
                                                        <td>
                                                            <span style="font-size:14px;font-weight:bold;margin-right:50px;width:600px">Remarks/Reactions</span>
                                                        </td>
                                                        <td>
                                                            <span style="font-size:14px;font-weight:bold;margin-right:50px;">Initials</span>
                                                        </td>


                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <center> 1 </center>
                                                        </td>

                                                        <td>
                                                            <span> <input id="Slitlog_main4_date1" type="date"> </span>
                                                        </td>
                                                        
                                                        <td>
                                                            <span> <input id="Slitlog_main4_num1" type="text"> </span>
                                                        </td>

                                                        <td>
                                                            <center> 1 </center>
                                                        </td>

                                                        <td>
                                                            <span> <input id="Slitlog_main4_remarks1" type="text"> </span>
                                                        </td>

                                                        <td>
                                                            <span> <input id="Slitlog_main4_initials1" type="text"> </span>
                                                        </td>


                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <center> 2 </center>
                                                        </td>

                                                       <td>
                                                            <span> <input id="Slitlog_main4_date2" type="date"> </span>
                                                        </td>
                                                        
                                                        <td>
                                                            <span> <input id="Slitlog_main4_num2" type="text"> </span>
                                                        </td>

                                                        <td>
                                                            <center> 1 </center>
                                                        </td>

                                                        <td>
                                                            <span> <input id="Slitlog_main4_remarks2" type="text"> </span>
                                                        </td>

                                                        <td>
                                                            <span> <input id="Slitlog_main4_initials2" type="text"> </span>
                                                        </td>

                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <center> 3 </center>
                                                        </td>

                                                        <td>
                                                            <span> <input id="Slitlog_main4_date3" type="date"> </span>
                                                        </td>
                                                        
                                                        <td>
                                                            <span> <input id="Slitlog_main4_num3" type="text"> </span>
                                                        </td>

                                                        <td>
                                                            <center> 1 </center>
                                                        </td>

                                                        <td>
                                                            <span> <input id="Slitlog_main4_remarks3" type="text"> </span>
                                                        </td>

                                                        <td>
                                                            <span> <input id="Slitlog_main4_initials3" type="text"> </span>
                                                        </td>

                                                    </tr>
                                                    <tr>
                                                        <td>
                                                           <center> 4 </center>
                                                        </td>

                                                       <td>
                                                            <span> <input id="Slitlog_main4_date4" type="date"> </span>
                                                        </td>
                                                        
                                                        <td>
                                                            <span> <input id="Slitlog_main4_num4" type="text"> </span>
                                                        </td>

                                                        <td>
                                                            <center> 1 </center>
                                                        </td>

                                                        <td>
                                                            <span> <input id="Slitlog_main4_remarks4" type="text"> </span>
                                                        </td>

                                                        <td>
                                                            <span> <input id="Slitlog_main4_initials4" type="text"> </span>
                                                        </td>

                                                    </tr>
                                                    <tr>
                                                        <td>
                                                           <center> 5 </center>
                                                        </td>
                                                        <td>
                                                            <span> <input id="Slitlog_main4_date5" type="date"> </span>
                                                        </td>
                                                        
                                                        <td>
                                                            <span> <input id="Slitlog_main4_num5" type="text"> </span>
                                                        </td>

                                                        <td>
                                                            <center> 1 </center>
                                                        </td>

                                                        <td>
                                                            <span> <input id="Slitlog_main4_remarks5" type="text"> </span>
                                                        </td>

                                                        <td>
                                                            <span> <input id="Slitlog_main4_initials5" type="text"> </span>
                                                        </td>

                                                    </tr>
                                                    <tr>
                                                        <td>
                                                           <center> 6 </center>
                                                        </td>
                                                        <td>
                                                            <span> <input id="Slitlog_main4_date6" type="date"> </span>
                                                        </td>
                                                        
                                                        <td>
                                                            <span> <input id="Slitlog_main4_num6" type="text"> </span>
                                                        </td>

                                                        <td>
                                                            <center> 1 </center>
                                                        </td>

                                                        <td>
                                                            <span> <input id="Slitlog_main4_remarks6" type="text"> </span>
                                                        </td>

                                                        <td>
                                                            <span> <input id="Slitlog_main4_initials6" type="text"> </span>
                                                        </td>

                                                    </tr>
                                     <tr>
                                                        <td>
                                                           <center> 7 </center>
                                                        </td>
                                                        <td>
                                                            <span> <input id="Slitlog_main4_date7" type="date"> </span>
                                                        </td>
                                                        
                                                        <td>
                                                            <span> <input id="Slitlog_main4_num7" type="text"> </span>
                                                        </td>

                                                        <td>
                                                            <center> 1 </center>
                                                        </td>

                                                        <td>
                                                            <span> <input id="Slitlog_main4_remarks7" type="text"> </span>
                                                        </td>

                                                        <td>
                                                            <span> <input id="Slitlog_main4_initials7" type="text"> </span>
                                                        </td>

                                                    </tr> <tr>
                                                        <td>
                                                           <center> 8 </center>
                                                        </td>
                                                        <td>
                                                            <span> <input id="Slitlog_main4_date8" type="date"> </span>
                                                        </td>
                                                        
                                                        <td>
                                                            <span> <input id="Slitlog_main4_num8" type="text"> </span>
                                                        </td>

                                                        <td>
                                                            <center> 1 </center>
                                                        </td>

                                                        <td>
                                                            <span> <input id="Slitlog_main4_remarks8" type="text"> </span>
                                                        </td>

                                                        <td>
                                                            <span> <input id="Slitlog_main4_initials8" type="text"> </span>
                                                        </td>

                                                    </tr> <tr>
                                                        <td>
                                                           <center> 9 </center>
                                                        </td>
                                                        <td>
                                                            <span> <input id="Slitlog_main4_date9" type="date"> </span>
                                                        </td>
                                                        
                                                        <td>
                                                            <span> <input id="Slitlog_main4_num9" type="text"> </span>
                                                        </td>

                                                        <td>
                                                            <center> 1 </center>
                                                        </td>

                                                        <td>
                                                            <span> <input id="Slitlog_main4_remarks9" type="text"> </span>
                                                        </td>

                                                        <td>
                                                            <span> <input id="Slitlog_main4_initials9" type="text"> </span>
                                                        </td>

                                                    </tr> <tr>
                                                        <td>
                                                           <center> 10 </center>
                                                        </td>
                                                        <td>
                                                            <span> <input id="Slitlog_main4_date10" type="date"> </span>
                                                        </td>
                                                        
                                                        <td>
                                                            <span> <input id="Slitlog_main4_num10" type="text"> </span>
                                                        </td>

                                                        <td>
                                                            <center> 1 </center>
                                                        </td>

                                                        <td>
                                                            <span> <input id="Slitlog_main4_remarks10" type="text"> </span>
                                                        </td>

                                                        <td>
                                                            <span> <input id="Slitlog_main4_initials10" type="text"> </span>
                                                        </td>

                                                    </tr> 
                                                   
                                                    <tr>
                                                        <td colspan="6" bgcolor="#C0C0C0">								
                                                            <center style="font-size:14px;font-weight:bold;margin-right:50px;height:20px"></center>
                                                        </td>
                                                    </tr>
                                             </table>
                                
                                </div>
                            </div>
						</div>
					</div>
				</div>			
			</div>
                
                
            <div class="tab-pane" id="TabAllergyQuestionnaire">		
				<div style="margin:15px; margin-top:5px;">
					<div class="row-fluid"  style="">	            
						<div class="grid" style="padding:10px;height:1000px;overflow:auto">
							<style>
                            .AQ_TABLE td{
                                    outline: 1px solid #444;
                                    <!--padding-left: 1%;-->
                                }
                            </style>
                            <table class="AQ_TABLE" style="background-color: #FFF; width: 100%; outline: 1px solid #444;">
                                <tr>
                                    <th>Allergy Questionnaire</th>
                                    <th  colspan="2">Date of visit: <input type="text" id="AQ_visit_date" size="10" style="width: 130px; background-color: #FFF; padding: 10px; margin-left: 10px; margin-top: 6px;" /></th>
                                </tr>
                                <tr>
                                    <td>First Name: <input type="text" id="AQ_first_name" size="10" style="width: 200px; background-color: #FFF; padding: 10px; margin-left: 10px; margin-top: 6px;" /></td>
                                    <td>Last Name: <input type="text" id="AQ_last_name" size="10" style="width: 200px; background-color: #FFF; padding: 10px; margin-left: 10px; margin-top: 6px;" /></td>
                                    <td>DOB: <input type="text" id="AQ_dob" size="10" style="width: 200px; background-color: #FFF; padding: 10px; margin-left: 10px; margin-top: 6px;" /></td>
                                </tr>
                                <tr>
                                    <td>Best Contact Number: <input type="text" id="AQ_contact_number" size="10" style="width: 200px; background-color: #FFF; padding: 10px; margin-left: 10px; margin-top: 6px;" /></td>
                                    <td colspan="2">Email: <input type="text" id="AQ_email" size="10" style="width: 200px; background-color: #FFF; padding: 10px; margin-left: 10px; margin-top: 6px;" /></td>
                                </tr>
                                <tr>
                                    <th colspan="3">Medications</th>
                                </tr>
                                <tr>
                                    <td colspan="3">List current medications (IF DIFFERENT THAN PHYSICIAN RECORD); specifically beta blockers, heart medications, high blood pressure medications or antihistamines including inhalers and nasal sprays:</td>
                                </tr>
                                <tr>
                                    <th>Name</td>
                                    <th>Dose</td>
                                    <th>Frequency Taken</td>
                                </tr>
                                <tr>
                                    <td colspan="3">
                                        <table id="AQ_medications" style="background-color: #FFF; width: 100%;">
                                            <tr>
                                                <td><input type="text" id="AQ_name_1" size="10" style="width: 200px; background-color: #FFF; padding: 10px; margin-left: 10px; margin-top: 6px;" /></td>
                                                <td><input type="text" id="AQ_dose_1" size="10" style="width: 200px; background-color: #FFF; padding: 10px; margin-left: 10px; margin-top: 6px;" /></td>
                                                <td><input type="text" id="AQ_frequency_1" size="10" style="width: 200px; background-color: #FFF; padding: 10px; margin-left: 10px; margin-top: 6px;" /></td>
                                                <td><button id="AQ_remove_medication_1" style="width: 100%"><input type="hidden" value="1" />X</button></td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3"><button id="AQ_add_medication" style="width: 100%">+</button></td>
                                </tr>
                            
                            
                            </table>
                            <h3>Allergy History</h3>
                            <h4>I. Skin Symptoms</h4>
                            <span>Does the patient have any of the following symptoms: (Check all that apply.)</span>
                            <style>
                            .check_box{
                                    float: left;
                                    margin-right: 10px;
                                }
                            </style>
                            <div style="width: 100%; height: 100px;">
                                <input type="checkbox" id="AQ_SS_1" name="cc" style="float: left;">
                                <label for="AQ_SS_1" class="check_box"><span></span> Itching</label>
                                <input type="checkbox" id="AQ_SS_2" name="cc" style="float: left;">
                                <label for="AQ_SS_2" class="check_box"><span></span> Dryness</label>
                                <input type="checkbox" id="AQ_SS_3" name="cc" style="float: left;">
                                <label for="AQ_SS_3" class="check_box"><span></span> Redness</label>
                                <input type="checkbox" id="AQ_SS_4" name="cc" style="float: left;">
                                <label for="AQ_SS_4" class="check_box"><span></span> Swelling</label>
                                <input type="checkbox" id="AQ_SS_5" name="cc" style="float: left;">
                                <label for="AQ_SS_5" class="check_box"><span></span> Bumpy texture of skin</label>
                                <input type="checkbox" id="AQ_SS_6" name="cc" style="float: left;">
                                <label for="AQ_SS_6" class="check_box"><span></span> Sensitivity to clothing or touch</label>
                            </div>
                            <h4>II. Other Symptoms (Check all that apply.)</h4>
                            <div style="width: 100%; height: 100px;">
                                <input type="checkbox" id="AQ_OS_1" name="cc" style="float: left;">
                                <label for="AQ_OS_1" class="check_box"><span></span> Nasal Congestion</label>
                                <input type="checkbox" id="AQ_OS_2" name="cc" style="float: left;">
                                <label for="AQ_OS_2" class="check_box"><span></span> Heart Burn</label>
                                <input type="checkbox" id="AQ_OS_3" name="cc" style="float: left;">
                                <label for="AQ_OS_3" class="check_box"><span></span> Post Nasal Drip</label>
                                <input type="checkbox" id="AQ_OS_4" name="cc" style="float: left;">
                                <label for="AQ_OS_4" class="check_box"><span></span> Runny Nose</label>
                                <input type="checkbox" id="AQ_OS_5" name="cc" style="float: left;">
                                <label for="AQ_OS_5" class="check_box"><span></span> Sneezing</label>
                                <input type="checkbox" id="AQ_OS_6" name="cc" style="float: left;">
                                <label for="AQ_OS_6" class="check_box"><span></span> Lost of Taste and Smell</label>
                                <input type="checkbox" id="AQ_OS_7" name="cc" style="float: left;">
                                <label for="AQ_OS_7" class="check_box"><span></span> Nasal Itch/rubs</label>
                                <input type="checkbox" id="AQ_OS_8" name="cc" style="float: left;">
                                <label for="AQ_OS_8" class="check_box"><span></span> Red Eyes</label>
                                <input type="checkbox" id="AQ_OS_9" name="cc" style="float: left;">
                                <label for="AQ_OS_9" class="check_box"><span></span> Itchy Eyes</label>
                                <input type="checkbox" id="AQ_OS_10" name="cc" style="float: left;">
                                <label for="AQ_OS_10" class="check_box"><span></span> Weight Gain</label>
                                <input type="checkbox" id="AQ_OS_11" name="cc" style="float: left;">
                                <label for="AQ_OS_11" class="check_box"><span></span> Discolored Drainage</label>
                                <input type="checkbox" id="AQ_OS_12" name="cc" style="float: left;">
                                <label for="AQ_OS_12" class="check_box"><span></span> Bad Breath</label>
                                <input type="checkbox" id="AQ_OS_13" name="cc" style="float: left;">
                                <label for="AQ_OS_13" class="check_box"><span></span> Snoring</label>
                                <input type="checkbox" id="AQ_OS_14" name="cc" style="float: left;">
                                <label for="AQ_OS_14" class="check_box"><span></span> Mouth Breathing</label>
                                <input type="checkbox" id="AQ_OS_15" name="cc" style="float: left;">
                                <label for="AQ_OS_15" class="check_box"><span></span> Nose Bleeds</label>
                                <input type="checkbox" id="AQ_OS_16" name="cc" style="float: left;">
                                <label for="AQ_OS_16" class="check_box"><span></span> Headache</label>
                            </div>
                            <h4>III. Skin / Nasal Causes (Check any of the following that trigger the syptoms)</h4>
                            <div style="width: 100%; height: 150px;">
                                <input type="checkbox" id="AQ_SNC_1" name="cc" style="float: left;">
                                <label for="AQ_SNC_1" class="check_box"><span></span> Dust</label>
                                <input type="checkbox" id="AQ_SNC_2" name="cc" style="float: left;">
                                <label for="AQ_SNC_2" class="check_box"><span></span> Fall Pollen</label>
                                <input type="checkbox" id="AQ_SNC_3" name="cc" style="float: left;">
                                <label for="AQ_SNC_3" class="check_box"><span></span> Spring Pollen</label>
                                <input type="checkbox" id="AQ_SNC_4" name="cc" style="float: left;">
                                <label for="AQ_SNC_4" class="check_box"><span></span> Cut Grass/Raked Leaves</label>
                                <input type="checkbox" id="AQ_SNC_5" name="cc" style="float: left;">
                                <label for="AQ_SNC_5" class="check_box"><span></span> Dog</label>
                                <input type="checkbox" id="AQ_SNC_6" name="cc" style="float: left;">
                                <label for="AQ_SNC_6" class="check_box"><span></span> Cat</label>
                                <input type="checkbox" id="AQ_SNC_7" name="cc" style="float: left;">
                                <label for="AQ_SNC_7" class="check_box"><span></span> Feathers</label>
                                <input type="checkbox" id="AQ_SNC_8" name="cc" style="float: left;">
                                <label for="AQ_SNC_8" class="check_box"><span></span> Mold/Mildew</label>
                                <input type="checkbox" id="AQ_SNC_9" name="cc" style="float: left;">
                                <label for="AQ_SNC_9" class="check_box"><span></span> Dampness</label>
                                <input type="checkbox" id="AQ_SNC_10" name="cc" style="float: left;">
                                <label for="AQ_SNC_10" class="check_box"><span></span> Indoors</label>
                                <input type="checkbox" id="AQ_SNC_11" name="cc" style="float: left;">
                                <label for="AQ_SNC_11" class="check_box"><span></span> Outdoors</label>
                                <input type="checkbox" id="AQ_SNC_12" name="cc" style="float: left;">
                                <label for="AQ_SNC_12" class="check_box"><span></span> Weather Changes</label>
                                <input type="checkbox" id="AQ_SNC_13" name="cc" style="float: left;">
                                <label for="AQ_SNC_13" class="check_box"><span></span> Smoke</label>
                                <input type="checkbox" id="AQ_SNC_14" name="cc" style="float: left;">
                                <label for="AQ_SNC_14" class="check_box"><span></span> Temperature Changes</label>
                                <input type="checkbox" id="AQ_SNC_15" name="cc" style="float: left;">
                                <label for="AQ_SNC_15" class="check_box"><span></span> PM</label>
                                <input type="checkbox" id="AQ_SNC_16" name="cc" style="float: left;">
                                <label for="AQ_SNC_16" class="check_box"><span></span> Home</label>
                                <input type="checkbox" id="AQ_SNC_13" name="cc" style="float: left;">
                                <label for="AQ_SNC_13" class="check_box"><span></span> Workplace</label>
                                <input type="checkbox" id="AQ_SNC_14" name="cc" style="float: left;">
                                <label for="AQ_SNC_14" class="check_box"><span></span> Food</label>
                                <input type="checkbox" id="AQ_SNC_15" name="cc" style="float: left;">
                                <label for="AQ_SNC_15" class="check_box"><span></span> Rain</label>
                                <input type="checkbox" id="AQ_SNC_16" name="cc" style="float: left;">
                                <label for="AQ_SNC_16" class="check_box"><span></span> Strong Odors</label>
                                <input type="text" id="AQ_SNC_17" size="10" style="width: 200px; background-color: #FFF; padding: 10px; margin-left: 10px;" />
                                <label for="AQ_SNC_17" class="check_box" style="margin-top: 4px;"><span></span> Animals:</label>
                                <br/>
                                <label>Patients symptoms occur (Check one or Both)</label>
                                <input type="checkbox" id="AQ_SNC_18" name="cc" style="float: left;">
                                <label for="AQ_SNC_18" class="check_box"><span></span> Year Round</label>
                                <input type="checkbox" id="AQ_SNC_19" name="cc" style="float: left;">
                                <label for="AQ_SNC_19" class="check_box"><span></span> Seasonally</label>
                                <input type="text" id="AQ_SNC_20" size="10" style="width: 200px; background-color: #FFF; padding: 10px; margin-left: 10px;  clear: both;" />
                                <label for="AQ_SNC_20" class="check_box" style="margin-top: 4px; margin-left: 20px;"><span></span> If Seasonally, the symptoms occur in what (Month or Months)?</label>
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

		var api=$j('#PatEncSig').signaturePad({drawOnly:true});
		var sig;
		$j('#accept_sig').click(function(event) {
			 
			sig = api.getSignature();
			//alert(sig);
			$j('.sigPad_patenc').signaturePad({displayOnly:true}).regenerate(sig);
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
        
		
        $j('#AQ_add_medication').click(function(event)
        {
            var item = $j('#AQ_medications tr').length + 1;
            $j('#AQ_medications').append('<tr><td><input type="text" id="AQ_name_'+item+'" size="10" style="width: 200px; background-color: #FFF; padding: 10px; margin-left: 10px; margin-top: 6px;" /></td><td><input type="text" id="AQ_dose_'+item+'" size="10" style="width: 200px; background-color: #FFF; padding: 10px; margin-left: 10px; margin-top: 6px;" /></td><td><input type="text" id="AQ_frequency_'+item+'" size="10" style="width: 200px; background-color: #FFF; padding: 10px; margin-left: 10px; margin-top: 6px;" /></td><td><button id="AQ_remove_medication_'+item+'" style="width: 100%"><input type="hidden" value="'+item+'" />X</button></td></tr>');
            $("[id^=AQ_remove_medication_"+item+"]").click(function(event)
            {
                var item = $(this).children('input').val();
                $j('#AQ_medications').find( "tr" ).eq(item - 1).remove();
            });
            
        });
        $("[id^=AQ_remove_medication_1]").click(function(event)
        {
            var item = $(this).children('input').val();
            $j('#AQ_medications').find( "tr" ).eq(item - 1).remove();
        });
        
        /*$j('#AQ_add_medication').click(function(event)
        {
            var item = $j('#PN_add tr').length + 1;
            $j('#PN_add').append('<tr><td  colspan="2">Date: <input type="text" id="PN_date_1" size="10" style="width: 200px; background-color: #FFF; padding: 10px; margin-left: 10px; margin-top: 6px;" /></td><td><input type="text" id="PN_note_1" size="10" style="width: 200px; background-color: #FFF; padding: 10px; margin-left: 10px; margin-top: 6px;" /></td><td><button id="PN_remove_1" style="width: 100%"><input type="hidden" value="1" />X</button></td></tr>');
            $("[id^=AQ_remove_medication_"+item+"]").click(function(event)
            {
                var item = $(this).children('input').val();
                $j('#AQ_medications').find( "tr" ).eq(item - 1).remove();
            });
            
        });
        $("[id^=AQ_remove_medication_1]").click(function(event)
        {
            var item = $(this).children('input').val();
            $j('#AQ_medications').find( "tr" ).eq(item - 1).remove();
        });*/
        
        
        
        
        
        $j(document).ready(function() {
            $j('#all_test_result_dob').Zebra_DatePicker({format: 'M d, Y'});
            $j('#sig_witness1_TAF_date').Zebra_DatePicker({format: 'M d, Y'});
            $j('#sig_patient1_TAF_date').Zebra_DatePicker({format: 'M d, Y'});
            $j('#TAF_date').Zebra_DatePicker({format: 'M d, Y'});
            $j('#sig_patient2_TAF_date').Zebra_DatePicker({format: 'M d, Y'});
            $j('#AQ_visit_date').Zebra_DatePicker({format: 'M d, Y'});
            
            setTimeout(function()
            {
                $j(".Zebra_DatePicker_Icon").css("top", "3px");
                $j(".Zebra_DatePicker_Icon").css("margin-left", "-22px");
            }, 100);
            
        });

		$j("#SlitLogSwitch").click(function(event) {
             if ($j(this).is(":checked")) {
                $j('#switchDropper').hide();
                $j('#switchMaintanence').show();
             
             }else{
                $j('#switchDropper').show();
                $j('#switchMaintanence').hide();             
             }
            
   		});
        
        $j('#slitMaintanence2').hide();
        $j('#slitMaintanence3').hide();
        $j('#slitMaintanence4').hide();
        
        $j('#slitDropper2').hide();
        $j('#slitDropper3').hide();
        
        
        $j('input[id^="maintenanceNext"]').live('click',function(){
            
            if($j(this).attr('id')=="maintenanceNext2")
            {
                $j("#slitMaintanence2").show();
                $j("#slitMaintanence1").hide();
            }else if($j(this).attr('id')=="maintenanceNext3")
            {
                $j("#slitMaintanence3").show();
                $j("#slitMaintanence2").hide();
            }else if($j(this).attr('id')=="maintenanceNext4")
            {
                $j("#slitMaintanence4").show();
                $j("#slitMaintanence3").hide();
            }
        
        
        });
        
         $j('input[id^="maintenancePrev"]').live('click',function(){
            
            if($j(this).attr('id')=="maintenancePrev1")
            {
                $j("#slitMaintanence1").show();
                $j("#slitMaintanence2").hide();
            }else if($j(this).attr('id')=="maintenancePrev2")
            {
                $j("#slitMaintanence2").show();
                $j("#slitMaintanence3").hide();
            }else if($j(this).attr('id')=="maintenancePrev3")
            {
                $j("#slitMaintanence3").show();
                $j("#slitMaintanence4").hide();
            }
        
        
        });
        
        $j('input[id^="dropperNext"]').live('click',function(){
            
            if($j(this).attr('id')=="dropperNext2")
            {
                $j("#slitDropper2").show();
                $j("#slitDropper1").hide();
            }else if($j(this).attr('id')=="dropperNext3")
            {
               $j("#slitDropper3").show();
                $j("#slitDropper2").hide();
            }
        
        
        });
        
         $j('input[id^="dropperPrev"]').live('click',function(){
            
            if($j(this).attr('id')=="dropperPrev1")
            {
                $j("#slitDropper1").show();
                $j("#slitDropper2").hide();
            }else if($j(this).attr('id')=="dropperPrev2")
            {
                $j("#slitDropper2").show();
                $j("#slitDropper3").hide();
            }
        
        
        });

		/*$j('#clear_allergy_temp').live('click',function(){
		
			
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
			var post_patencbp=$j('#post_patencbp').val();
			var post_patencpul=$j('#post_patencpul').val();
			var post_patencres=$j('#post_patencres').val();
			
			
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
					 
			});*/
			
		//});
        
     
       
                          $("#Tab_Imm_Therapy").click(function(){
                          var json_Tab_Imm_Therapy = {};
                         $("#TabImmunizationTherapy").find("input").each(function(){
                             json_Tab_Imm_Therapy[$(this).attr("id")] = $(this).val();
                         });
                          /*var num = $("#test").find("input").length;
                          for(var i = 0; i < num; i++)
                          {
                              json_item [$("#test").eq(i).attr("id")] = $("#test").eq(i).val();
                              
                          }*/
                          console.log(JSON.stringify(json_Tab_Imm_Therapy));
                              //console.log($("#TabImmunizationTherapy").html());
                          });
 
						$("#OFC_Tab_Imm_Therapy").click(function(){
                          var json_OFC_Tab_Imm_Therapy = {};
                         $("#TabOFCImmunizationTherapy").find("input").each(function(){
                             json_OFC_Tab_Imm_Therapy[$(this).attr("id")] = $(this).val();
                         });
                          console.log(JSON.stringify(json_OFC_Tab_Imm_Therapy));
                          });
        
        
        
						$j("#clear_allergy_temp").click(function(){
                          var json_clear_allergy_temp = {};
                         $j("#TabPatientEnc").find("input").each(function(){
                             json_clear_allergy_temp[$j(this).attr("id")] = $j(this).val();
                         });
						$j("#TabPatientEnc").find("select").each(function(){
                             json_clear_allergy_temp[$j(this).attr("id")] = $j(this).val();
                         });
						 
						 console.log(JSON.stringify(json_clear_allergy_temp));
						 
						 var URL='getCAPatEncFormData.php?json='+JSON.stringify(json_clear_allergy_temp)+'&idusu=<?php echo $userid?>';
			
						var Rectipo=LanzaAjax(URL);
						
						//alert(Rectipo);
						
						var url = 'ClearAllergyTemplateToPdf.php?entryid='+Rectipo+'&idusu=<?php echo $userid?>';
						
						var rectipo1=LanzaAjax(url);
						
						alert("Report Created! ");
			
              });
        
        
        $("#allerty_test_result_template").click(function(){
                          var json_allerty_test_result_template = {};
                         $("#AllergyTestResultsForm").find("input").each(function(){
                             json_allerty_test_result_template[$(this).attr("id")] = $(this).val();
                         });
             $("#AllergyTestResultsForm").find("select").each(function(){
                             json_allerty_test_result_template[$(this).attr("id")] = $(this).val();
                         });
                          console.log(JSON.stringify(json_allerty_test_result_template));
                          });
    
        
        
            $("#PTOEF_template").click(function(){
                          var json_PTOEF_template = {};
                         $("#PatientTreatmentOptionsElectionForm").find("input").each(function(){
                             json_PTOEF_template[$(this).attr("id")] = $(this).val();
                         });
             $("#PatientTreatmentOptionsElectionForm").find("select").each(function(){
                             json_PTOEF_template[$(this).attr("id")] = $(this).val();
                         });
                          console.log(JSON.stringify(json_PTOEF_template));
                          });
        
        
        $("#TAF_template").click(function(){
                          var json_TAF_template = {};
                         $("#TestingAuthorizationForm").find("input").each(function(){
                             json_TAF_template[$(this).attr("id")] = $(this).val();
                         });
             $("#TestingAuthorizationForm").find("select").each(function(){
                             json_TAF_template[$(this).attr("id")] = $(this).val();
                         });
                          console.log(JSON.stringify(json_TAF_template));
                          });
                        
        
        $("#fin_agrmnt_button").click(function(){
                          var json_fin_agrmnt = {};
                         $("#TabFinancialAgreement").find("input").each(function(){
                             json_fin_agrmnt[$(this).attr("id")] = $(this).val();
                         });
             $("#TabFinancialAgreement").find("select").each(function(){
                             json_fin_agrmnt[$(this).attr("id")] = $(this).val();
                         });
                          console.log(JSON.stringify(json_fin_agrmnt));
                          });
        $("#CA_Incident_log").click(function(){
                          var json_CA_Incident_log = {};
                         $("#CA_IncidentLog").find("input").each(function(){
                             json_CA_Incident_log[$(this).attr("id")] = $(this).val();
                         });
             $("#CA_IncidentLog").find("select").each(function(){
                             json_CA_Incident_log[$(this).attr("id")] = $(this).val();
                         });
                          console.log(JSON.stringify(json_CA_Incident_log));
                          });
        $("#SI_log").click(function(){
                          var json_SI_log = {};
                         $("#sharps_injury_log").find("input").each(function(){
                             json_SI_log[$(this).attr("id")] = $(this).val();
                         });
             $("#sharps_injury_log").find("select").each(function(){
                             json_SI_log[$(this).attr("id")] = $(this).val();
                         });
                          console.log(JSON.stringify(json_SI_log));
                          });
                    
        
        
        $("#PN_button").click(function(){
                          var json_PN_button = {};
                         $("#TabPatientNote").find("input").each(function(){
                             json_PN_button[$(this).attr("id")] = $(this).val();
                         });
             $("#TabPatientNote").find("select").each(function(){
                             json_PN_button[$(this).attr("id")] = $(this).val();
                         });
                          console.log(JSON.stringify(json_PN_button));
                          });
        
        
        $("#IV_button").click(function(){
                          var json_IV_button = {};
                         $("#TabInsuranceVerification").find("input").each(function(){
                             json_IV_button[$(this).attr("id")] = $(this).val();
                         });
             $("#TabInsuranceVerification").find("select").each(function(){
                             json_IV_button[$(this).attr("id")] = $(this).val();
                         });
                          console.log(JSON.stringify(json_IV_button));
                          });
        
        
        $("#SD1_save_button").click(function(){
                          var json_SD1_save_button = {};
                         $("#slitDropper1").find("input").each(function(){
                             json_SD1_save_button[$(this).attr("id")] = $(this).val();
                         });
             $("#slitDropper1").find("select").each(function(){
                             json_SD1_save_button[$(this).attr("id")] = $(this).val();
                         });
                          console.log(JSON.stringify(json_SD1_save_button));
                          });
        
        
        $("#SD2_save_button").click(function(){
                          var json_SD2_save_button = {};
                         $("#slitDropper2").find("input").each(function(){
                             json_SD2_save_button[$(this).attr("id")] = $(this).val();
                         });
             $("#slitDropper2").find("select").each(function(){
                             json_SD2_save_button[$(this).attr("id")] = $(this).val();
                         });
                          console.log(JSON.stringify(json_SD2_save_button));
                          });
        
         
        $("#SD3_save_button").click(function(){
                          var json_SD3_save_button = {};
                         $("#slitDropper3").find("input").each(function(){
                             json_SD3_save_button[$(this).attr("id")] = $(this).val();
                         });
             $("#slitDropper3").find("select").each(function(){
                             json_SD3_save_button[$(this).attr("id")] = $(this).val();
                         });
                          console.log(JSON.stringify(json_SD3_save_button));
                          });
        
        
        $("#maintain_save1").click(function(){
                          var json_maintain_save1 = {};
                         $("#slitMaintanence1").find("input").each(function(){
                             json_maintain_save1[$(this).attr("id")] = $(this).val();
                         });
             $("#slitMaintanence1").find("select").each(function(){
                             json_maintain_save1[$(this).attr("id")] = $(this).val();
                         });
                          console.log(JSON.stringify(json_maintain_save1));
                          });
        
        
        $("#maintain_save2").click(function(){
                          var json_maintain_save2 = {};
                         $("#slitMaintanence2").find("input").each(function(){
                             json_maintain_save2[$(this).attr("id")] = $(this).val();
                         });
             $("#slitMaintanence2").find("select").each(function(){
                             json_maintain_save2[$(this).attr("id")] = $(this).val();
                         });
                          console.log(JSON.stringify(json_maintain_save2));
                          });
        
        $("#maintain_save3").click(function(){
                          var json_maintain_save3 = {};
                         $("#slitMaintanence3").find("input").each(function(){
                             json_maintain_save3[$(this).attr("id")] = $(this).val();
                         });
             $("#slitMaintanence3").find("select").each(function(){
                             json_maintain_save3[$(this).attr("id")] = $(this).val();
                         });
                          console.log(JSON.stringify(json_maintain_save3));
                          });
        
        $("#maintain_save4").click(function(){
                          var json_maintain_save4 = {};
                         $("#slitMaintanence4").find("input").each(function(){
                             json_maintain_save4[$(this).attr("id")] = $(this).val();
                         });
             $("#slitMaintanence4").find("select").each(function(){
                             json_maintain_save4[$(this).attr("id")] = $(this).val();
                         });
                          console.log(JSON.stringify(json_maintain_save4));
                          });
        
    
        

        
        
        
        
        
        
        //Amtul-- Till here
		
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