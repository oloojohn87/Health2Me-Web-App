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
$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }	

$result = $con->prepare("SELECT * FROM doctors where id=?");
$result->bindValue(1, $MEDID, PDO::PARAM_INT);
$result->execute();

$count = $result->rowCount();
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
     while($row=$sql->fetch(PDO::FETCH_ASSOC);){
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
$row = $query->fetch(PDO::FETCH_ASSOC); 

$enc_result = $con->prepare("select pass from encryption_pass where id = (select max(id) from encryption_pass)");
$enc_result->execute();

$row_enc = $enc_result->fetch(PDO::FETCH_ASSOC);
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
	
	<link href="css/demo_style.css" rel="stylesheet" type="text/css"/>
	<link href="css/smart_wizard.css" rel="stylesheet" type="text/css"/>
	<link rel="stylesheet" type="text/css" href="css/MooEditable.css">
	<link rel="stylesheet" href="css/jquery-ui-autocomplete.css" />
	<script src="js/jquery-1.9.1-autocomplete.js"></script>
	<script src="js/jquery-ui-autocomplete.js"></script>
		
	
	<link rel="stylesheet" type="text/css" href="js/uploadify/uploadify.css">
    <?php
        if ($_SESSION['CustomLook']=="COL") { ?>
            <link href="css/styleCol.css" rel="stylesheet">
    <?php  } ?>
    
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
			
			<!-- Pop up Changing PErsonal Start-->
			<button id="BotonCP" data-target="#header-modal4" data-toggle="modal" class="btn btn-warning" style="display: none;">Modal with Header</button>
				<div id="header-modal4" class="modal hide" style="display: none;  width:400px; margin-left:-200px;" aria-hidden="true">
				<div class="modal-header">
					<button class="close" type="button" data-dismiss="modal">×</button>
					<div id="InfB" >
	                 	<h4>Changing Personal Data</h4>
					</div>
        		</div>
         		<div class="modal-body" id="ContenidoModal" style="height:200px;">
					<center>
					<table style="background:transparent; height:200px;" >
						<tr>
							<td style="height:24px;">Date Recorded : </td>
							<td style="height:24px;"><input id="CP_Date"  /></td>
						</tr>

						<tr >
							<td style="height:24px">Height: </td>
							<td style="height:24px;"><input id="CP_Height"  /></td>
						</tr>
						
						<tr>
							<td style="height:24px;">Weight :</td>
							<td style="height:24px; "> <input id="CP_Weight" ></td> 
						</tr>
						
						<tr>
							<td style="height:24px;">High BP :</td>
							<td style="height:24px; "> <input id="CP_hbp" ></td> 
						</tr>
						<tr>
							<td style="height:24px;">Low BP :</td>
							<td style="height:24px; "> <input id="CP_lbp" ></td> 
						</tr>
					</table>
					</center>
					
					
				</div>
				<div class="modal-footer">
			
					<a href="#" class="btn btn-success" data-dismiss="modal" id="UpdateCP">Update Data</a>
					<a href="#" class="btn btn-primary" data-dismiss="modal" id="CloseModal">Close</a>
				</div>
			</div>  
			<!--Pop up Changing Personal End-->
	
	
	 
			<div class="grid" class="grid span4" style="width:1100px; margin: 0 auto; margin-top:30px; padding-top:30px;">
			<center>
			<ul id="myTab" class="nav nav-tabs tabs-main">
				<li class="active" ><a href="#TabDemographics" data-toggle="tab">Demographics</a></li>
				<?php if($row['personal'])
					echo '<li><a href="#TabPersonal" data-toggle="tab">Personal History</a></li>';
				?>
				<?php if($row['family'])
					echo '<li><a href="#TabFamily" data-toggle="tab">Family History</a></li>';
				?>
				<?php if($row['pastdx'])
					echo '<li><a href="#TabPastDX" data-toggle="tab">Past Diagnostics</a></li>';
				?>
				<?php if($row['medications'])
					echo '<li><a href="#TabMedication" data-toggle="tab">Medication</a></li>';
				?>
				<?php if($row['immunizations'])
					echo '<li><a href="#TabImmunization" data-toggle="tab">Immunization</a></li>';
				?>
				<?php if($row['allergies'])
					echo '<li><a href="#TabAllergies" data-toggle="tab">Allergies</a></li>';
				?>
				<!--<li><a href="#TabNotes" data-toggle="tab">Physician Notes</a></li>-->
		
			</ul>
			</center>
			<div id="myTabContent" class="tab-content tabs-main-content padding-null" >	
			<div class="tab-pane tab-overflow-main fade in active" id="TabDemographics">
				<div style="margin:15px; margin-top:5px;">
					<div class="row-fluid"  style="">	            
						<div class="grid" style="padding:10px;height:620px">
							
							<div style="margin-left:20px; margin-top:20px; color: #3d93e0; font-size:20px; font-weight:bold; width:100%; ">Demographics</div>
								<hr>
								
								<div style="float:left; width:450px; margin:10px; padding:10px; height:250px; ">
									<div class="formRow">
										<label style="margin-left:10px;">Name: </label>
										<div class="formRight" >
											<input id="fname" name="fname" type="text" class="first-input" style="width:120px; border-bottom-right-radius:3px; border-bottom-left-radius:3px;"/>
										</div>
										
										
										
									</div>
								
									<div class="formRow">
										<label style="margin-left:10px;">Middle Initial: </label>
										<div class="formRight">
											<input id="initial" name="initial" type="text" class="first-input" style="width:10px; border-bottom-right-radius:3px; border-bottom-left-radius:3px;"/>
										</div>
									
									</div>
								
									<div class="formRow">
										<label style="margin-left:10px;">Surname: </label>
										<div class="formRight">
											<input id="surname" name="surname" type="text" class="first-input" style="width:120px; border-bottom-right-radius:3px; border-bottom-left-radius:3px;"/>
										</div>
									
									</div>
									
									<div class="formRow">
										<label style="margin-left:10px;">Gender: </label>
										<div class="formRight">
											<select name="gender" id="gender" class="validate[required] span_select" style="width:200px;">
												<option value="">Select Gender:</option>
													<option value="0">Female</option> 
													<option value="1">Male</option>
											</select>
										</div>
									</div>
		 
									
									
									<div class="formRow">
										<label style="margin-left:10px;">Date of Birth: </label>
										<div class="formRight">
											<input id="dp32" style="width:150px"/>
											<!--<div class="validate[custom[date],past[2013/01/01]] input-append date" id="dp3" data-date="12-02-2012" data-date-format="dd-mm-yyyy" >
												<input class="span2" size="16" type="text" value="12-02-2012" id="dp32" name="dp32" readonly="" style="width:150px">
												<span class="add-on" onclick='return false;' style="width:20px"><i class="icon-th"></i></span>
											</div>-->
										</div>
									</div>
								</div>
								
								<div style="float:left; width:500px; margin:10px; padding:10px; height:250px; ">
									<div class="formRow" <?php if(!$row['address']) echo 'style="display:none"'?>>
										<label style="margin-left:30px;">Address: </label>
										<div class="formRight" >
											<input id="address" name="address" type="text" class="first-input" style="width:200px; border-bottom-right-radius:3px; border-bottom-left-radius:3px;"/>
										</div>
									</div>
									
									<div class="formRow" <?php if(!$row['address2']) echo 'style="display:none"'?>>
										<label style="margin-left:30px;">Address2: </label>
										<div class="formRight" >
											<input id="address2" name="address2" type="text" class="first-input" style="width:200px; border-bottom-right-radius:3px; border-bottom-left-radius:3px;"/>
										</div>
									</div>
									
									<div class="formRow" <?php if(!$row['city']) echo 'style="display:none"'?>>
										<label style="margin-left:30px;">City: </label>
										<div class="formRight" >
											<input id="city" name="city" type="text" class="first-input" style="width:120px; border-bottom-right-radius:3px; border-bottom-left-radius:3px;"/>
										</div>
									</div>
									
									<div class="formRow">
										<?php if($row['state']) 
											echo '<label style="margin-left:30px;">State: </label>';
										?>
										
										<div class="formRight" >
											<input id="state" name="state" type="text" class="first-input" style="width:120px; border-bottom-right-radius:3px; border-bottom-left-radius:3px; <?php if(!$row['state']) echo 'display:none'?> " />
											 <?php if($row['country']) echo '&nbsp &nbsp Country :';?>
											<input id="country" name="country" type="text" class="first-input" style="width:60px; border-bottom-right-radius:3px; border-bottom-left-radius:3px;<?php if(!$row['country']) echo 'display:none'?>"/>
											
										</div>
									</div>
									
									<div class="formRow" <?php if(!$row['phone']) echo 'style="display:none"'?>>
											<label style="margin-left:30px;">Phone: </label>
											<div class="formRight" >
												<input id="phone" name="phone" type="text" class="first-input" style="width:120px; border-bottom-right-radius:3px; border-bottom-left-radius:3px;"/>
											</div>
									</div>
									
									<div class="formRow" <?php if(!$row['insurance']) echo 'style="display:none"'?>>
										<label style="margin-left:30px;">Insurance</label>
										<div class="formRight" >
											<input id="insurance" name="insurance" type="text" class="first-input" style="width:120px; border-bottom-right-radius:3px; border-bottom-left-radius:3px;"/>
										</div>
									</div>
									
									
									<div class="formRow" <?php if(!$row['notes']) echo 'style="display:none"'?>>
										<label style="margin-left:0px;">Notes: </label>
										<!--<div class="formRight" style="margin-left:0px;">-->
											<textarea rows="3" cols="150" style="width:420px;margin-left:25px;resize:none" id="notes">	</textarea>
										<!--</div>-->
									</div>
								
								<?php
								
									$USERID = $userid;
									$file = "PatientImage/".$USERID.".jpg";
									 if(file_exists($file))
									 {
										//shell_exec('Decrypt_.bat PatientImage '.$USERID.".jpg"' '.$pass.' 2>&1');
										//$file = "/PatientImage/temp/".$USERID.".jpg";
										$file="temp/".$_SESSION['MEDID']."/".$USERID.".jpg";
										$fileName = $USERID.".jpg";
										
										$style = "max-height: 80px; max-width:80px;";	
									}
									 else
									 {
										$file = "/PatientImage/defaultDP.jpg";
										$fileName = "defaultDP.jpg";
										$style = "max-height: 80px; max-width:80px;";
									
									 }
								?>	
								<div class="formRow" >
								<div>
								<label style="margin-left:40px;">Image: </label>
								<div id="imageDiv">
								<img id="patientImage" src="<?php echo $file;?>" style="<?php echo $style;?>;margin-left:15px;"> 
								</div>
								</div>
								<!--	<input type="button" id="checkEdit" style="margin-left:100px; margin-top:10px;"> </input> -->
									
									<input type="file" style="margin-top:10px;" name="file_upload" id="file_upload" >
									<input type="button" class="btn btn-primary" id="removeImage" onclick="removeImage();" value="Remove Image" style="display:none; margin-left:40px; margin-top:10px;"> </input>
																		
								</div>
									<div class="formRow" id="imageFileDiv" >
										
									</div>
									<br>
								</div>
								
								
								
															
						</div>
					</div>
				</div>
			</div>	
				
				
				
			<div class="tab-pane" id="TabPersonal">	
				<div style="margin:15px; margin-top:5px;">
					<div class="row-fluid"  style="">	            
						<div class="grid" style="padding:10px;height:450px">
							
							<div style="margin-left:20px; margin-top:20px; color: #3d93e0; font-size:20px; font-weight:bold; width:100%; ">Personal History</div>
								<hr>
								
								
									<div class="formRow" <?php if(!$row['fractures']) echo 'style="display:none"'?>>
										<label style="margin-left:20px;">Fractures and Other Traumas: </label>
										<div class="formRight" >
											<input id="fractures" name="fractures" type="text" class="first-input" style="width:800px; border-bottom-right-radius:3px; border-bottom-left-radius:3px;"/>
										</div>
									</div>
									
									<div class="formRow" <?php if(!$row['surgeries']) echo 'style="display:none"'?>>
										<label style="margin-left:20px;">Surgeries: </label>
										<div class="formRight" >
											<input id="surgeries" name="surgeries" type="text" class="first-input" style="width:800px; border-bottom-right-radius:3px; border-bottom-left-radius:3px;"/>
										</div>
									</div>
									
									<div class="formRow" <?php if(!$row['otherknown']) echo 'style="display:none"'?>>
										<label style="margin-left:20px;">Other Known Medical Events: </label>
										<div class="formRight" >
											<input id="otherknown" name="otherknown" type="text" class="first-input" style="width:800px; border-bottom-right-radius:3px; border-bottom-left-radius:3px;"/>
										</div>
									</div>
									
									<div class="formRow" <?php if(!$row['obstetric']) echo 'style="display:none"'?>>
										<label style="margin-left:20px;">Obstetric History: </label>
										<div class="formRight" >
											<input id="obstetric" name="obstetric" type="text" class="first-input" style="width:800px; border-bottom-right-radius:3px; border-bottom-left-radius:3px;"/>
										</div>
									</div>
									
									<div class="formRow" <?php if(!$row['othermed']) echo 'style="display:none"'?>>
										<label style="margin-left:20px;">Other Medical Data: </label>
										<div class="formRight" >
											<input id="other" name="other" type="text" class="first-input" style="width:800px; border-bottom-right-radius:3px; border-bottom-left-radius:3px;"/>
										</div>
									</div>
									
									<div id="VacioAUN" style="  margin-left:20px;margin-top:10px; border: 1px SOLID #CACACA; float:left; width:90%">
										<table class="table table-mod" id="CP">
											<thead><tr id="FILA" class="CFILAMODAL"><th style="text-align: center;">Date Recorded</th><th style="text-align: center;">Height</th><th style="text-align: center;">Weight</th><th style="text-align: center;">High BP</th><th style="text-align: center;">Low BP</th></tr></thead>
											
										</table> 
									</div>
									
									<div style="float:right; margin-right:20px;margin-top:10px;">
										<input type="button" class="btn btn-primary" value="Add" onClick="openCPPopUp();">	
									</div>
						</div>
					</div>
				</div>
			</div>	
				
				
			<div class="tab-pane" id="TabFamily">		
				<div style="margin:15px; margin-top:5px;">
					<div class="row-fluid"  style="">	            
						<div class="grid" style="padding:10px;height:250px">
							
							<div style="margin-left:20px; margin-top:20px; color: #3d93e0; font-size:20px; font-weight:bold; width:100%; ">Family History</div>
								<hr>
								
								
									<div class="formRow" <?php if(!$row['father']) echo 'style="display:none"'?>>
										<label style="margin-left:20px;">Father: </label>
										
										<div class="formRight" >
											<input type="checkbox" id="c2" name="cc">
											<label for="c2"><span></span> Alive </label>
											
											<input id="fcod" name="fcod" type="text" class="first-input" placeholder="Cause of Death" style="margin-left:20px; width:280px; border-bottom-right-radius:3px; border-bottom-left-radius:3px;" />
											<input id="faod" name="faod" type="text" class="first-input" placeholder="Age of Death" style="margin-left:20px; width:85px; border-bottom-right-radius:3px; border-bottom-left-radius:3px;" />
											<input id="frd" name="frd" type="text" class="first-input" placeholder="Relevant Diseases" style="margin-left:20px; width:280px; border-bottom-right-radius:3px; border-bottom-left-radius:3px;" />
											
										</div>
									</div>
									
									<div class="formRow" <?php if(!$row['mother']) echo 'style="display:none"'?>>
										<label style="margin-left:20px;">Mother: </label>
										
										<div class="formRight" >
											<input type="checkbox" id="c3" name="cc1">
											<label for="c3"><span></span> Alive </label>
											
											<input id="mcod" name="mcod" type="text" class="first-input" placeholder="Cause of Death" style="margin-left:20px; width:280px; border-bottom-right-radius:3px; border-bottom-left-radius:3px;" />
											<input id="maod" name="maod" type="text" class="first-input" placeholder="Age of Death" style="margin-left:20px; width:85px; border-bottom-right-radius:3px; border-bottom-left-radius:3px;" />
											<input id="mrd" name="mrd" type="text" class="first-input" placeholder="Relevant Diseases" style="margin-left:20px; width:280px; border-bottom-right-radius:3px; border-bottom-left-radius:3px;" />
											
										</div>
									</div>
									
									<div class="formRow" <?php if(!$row['siblings']) echo 'style="display:none"'?>>
										<label style="margin-left:20px;">Siblings: </label>
										<div class="formRight" >
											<input id="siblingsRD" name="siblingsRD" type="text" class="first-input" placeholder="Relevant Diseases" style="width:800px; border-bottom-right-radius:3px; border-bottom-left-radius:3px;"/>
										</div>
									</div>
									
						</div>
					</div>
				</div>
			</div>	
				
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
			
			<div class="tab-pane" id="TabAllergies">	
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
			</div>
			
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
			<center>
					<input type="button" class="btn btn-primary" value="SAVE" onClick="savePatientData();" style="margin-top:7px;">	
			</center>
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			</div>
			
			</div>
</div>
    <!--Content END-->
   <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    
	
	<script src="js/jquery.min.js"></script>
    <script src="js/jquery-ui.min.js"></script>
	
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
	
		$j("#checkEdit").click(function() {
			if($j("#checkEdit").val()=='Edit Image')
			{
				$j("#imageFileDiv").show();
				$j("#checkEdit").prop('value', 'Cancel');
			}
			else if($j("#checkEdit").val()=='Cancel')
			{
				$j("#imageFileDiv").hide();
				$j("#checkEdit").prop('value', 'Edit Image');
				if($j('#file').val()!='')
				{
					cancelImageEdit();
				}
			}
		});

		function getusuariosdata(serviceURL) 
		{
			$j.ajax(
			{
				url: serviceURL,
				dataType: "json",
				async: false,
				success: function(data)
				{
					//alert('Data Fetched');
					user = data.items;
				}
			});
		}   
	
		function getemrdata(serviceURL) 
		{
			$j.ajax(
			{
				url: serviceURL,
				dataType: "json",
				async: false,
				success: function(data)
				{
					//alert('Data Fetched');
					emr = data.items;
				}
			});
		}   	
			
		function getpastdx(serviceURL) 
		{
			$j.ajax(
			{
				url: serviceURL,
				dataType: "json",
				async: false,
				success: function(data)
				{
					//alert('Data Fetched');
					pastdx = data.items;
				}
			});
		}
		
		function getmedications(serviceURL) 
		{
			$j.ajax(
			{
				url: serviceURL,
				dataType: "json",
				async: false,
				success: function(data)
				{
					//alert('Data Fetched');
					medications = data.items;
				}
			});
		}
		
		function getimmunizations(serviceURL) 
		{
			$j.ajax(
			{
				url: serviceURL,
				dataType: "json",
				async: false,
				success: function(data)
				{
					//alert('Data Fetched');
					immunizations = data.items;
				}
			});
		}
		
		function getallergies(serviceURL) 
		{
			$j.ajax(
			{
				url: serviceURL,
				dataType: "json",
				async: false,
				success: function(data)
				{
					//alert('Data Fetched');
					allergies = data.items;
				}
			});
		}
		
		function getcp(serviceURL) 
		{
			$j.ajax(
			{
				url: serviceURL,
				dataType: "json",
				async: false,
				success: function(data)
				{
					//alert('Data Fetched');
					cp = data.items;
				}
			});
		}
		
		$(document).ready(function() {
			$('body').bind('mousedown keydown', function(event) {
			clearTimeout(timeoutTimer);
			timeoutTimer = setTimeout(ShowTimeOutWarning, timeoutTime);
		});
	

		if($j("#patientImage").attr('src')!="/PatientImage/defaultDP.jpg")
				$j("#removeImage").show();
			// $j("#checkEdit").prop('value', 'Edit Image');
			// $j("#imageFileDiv").hide();
			<?php $timestamp = time();?>
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
				if(data=="fileError")
				{
					alert("Please select a file belonging to one of the following types: jpeg,gif or png");
					$('#patientImage').attr('src','PatientImage/defaultDP.jpg');
					return;
				}	
				var split = data.split('|');
				//alert('The file was saved to: ' + split[0]);
				if(split[1]=="1")
				{
					alert("Kindly upload image of minimum dimensions 70X70");
					return;	
				}
				$j('#patientImageDiv').show();
				$j('#patientImage').attr('src',split[0]);
				$j("#removeImage").show();
			}
			});
			}); 
			
			var queUrl ='getPastDX.php?idusu='+$j('#patientid').val();
			$j('#PastDX').load(queUrl);
			$j('#PastDX').trigger('update');
			
			var queUrl ='getMedication.php?idusu='+$j('#patientid').val();
			$j('#Medication').load(queUrl);
			$j('#Medication').trigger('update');
		
			var queUrl ='getImmunization.php?idusu='+$j('#patientid').val();
			$j('#Immunization').load(queUrl);
			$j('#Immunization').trigger('update');
			
			var queUrl ='getAllergies.php?idusu='+$j('#patientid').val();
			$j('#Allergies').load(queUrl);
			$j('#Allergies').trigger('update');
			
			var queUrl ='get_changing_personal.php?idusu='+$j('#patientid').val();
			$j('#CP').load(queUrl);
			$j('#CP').trigger('update');
			
			
			//---------------------------------------------------------------------------------------------
			getusuariosdata('getusuariosdata.php?idusu='+$j('#patientid').val());   //fills user
			
			$j('#fname').val(user[0].name);
			if(user[0].idcreator != $j('#doctorid').val())
			{
				var element = document.getElementById('fname');
				element.disabled=true;
			}
			$j('#surname').val(user[0].surname);
			if(user[0].idcreator != $j('#doctorid').val())
			{
				var element = document.getElementById('surname');
				element.disabled=true;
			}
			$j('#initial').val(user[0].mi);
			if(user[0].idcreator != $j('#doctorid').val())
			{
				var element = document.getElementById('initial');
				element.disabled=true;
			}

			
			var select=document.getElementById('gender');
			var sex='';
			switch(parseInt(user[0].sexo))
			{
				case 0:sex='Female';break;
				case 1:sex='Male';break;
				
			}
			for(var i=0;i<select.options.length;i++)
			{
				if(select.options[i].text == sex)
				{
					select.options[i].setAttribute('selected',true);
				}
			}
			if(user[0].idcreator != $j('#doctorid').val())
			{
				var element = document.getElementById('gender');
				element.disabled=true;
			}

			
			
			if(user[0].idcreator != $j('#doctorid').val())
			{
				var element = document.getElementById('dp32');
				element.disabled=true;
			}

			//----------------------------------------------------------------------------------------------
			
			getemrdata('getemrdata.php?idusu='+$j('#patientid').val());  		   //fills emr
			if(emr.length == 0)
			{
				alert('No EMR Data Found found for this user');
				return;
			}
			
			$j('#dp32').val(emr[0].dob);
			
			$j('#address').val(emr[0].address);
			$j('#address2').val(emr[0].address2);
			$j('#city').val(emr[0].city);
			$j('#state').val(emr[0].state);
			$j('#country').val(emr[0].country);
			$j('#notes').val(emr[0].notes);
			$j('#fractures').val(emr[0].fractures);
			$j('#surgeries').val(emr[0].surgeries);
			$j('#otherknown').val(emr[0].otherknown);
			$j('#obstetric').val(emr[0].obstetric);
			$j('#other').val(emr[0].othermed);
			$j('#fcod').val(emr[0].fcod);
			$j('#faod').val(emr[0].faod);
			$j('#frd').val(emr[0].frd);
			$j('#mcod').val(emr[0].mcod);
			$j('#maod').val(emr[0].maod);
			$j('#mrd').val(emr[0].mrd);
			$j('#siblingsRD').val(emr[0].siblingsrd);
		
			$j('#phone').val(emr[0].phone);
			$j('#insurance').val(emr[0].insurance);
		
			var mother = document.getElementById('c3');
			if(emr[0].motheralive)
			{
				mother.checked='checked';
				var element = document.getElementById('mcod');
				element.value = 'N/A';
				element.disabled=true;
				
				element = document.getElementById('maod');
				element.value = '';
				element.disabled=true;
				
			}
			else
			{
				//mother.checked=false;
				var element = document.getElementById('mcod');
				element.value = '';
				element.disabled=false;
				
				element = document.getElementById('maod');
				element.value = '';
				element.disabled=false;
			}
			
			
			
			var father = document.getElementById('c2');
			if(emr[0].fatheralive)
			{
				father.checked='checked';
				var element = document.getElementById('fcod');
				element.value = 'N/A';
				element.disabled=true;
				
				element = document.getElementById('faod');
				element.value = '';
				element.disabled=true;
			}
			else
			{
				//father.checked=;
				var element = document.getElementById('fcod');
				element.value = '';
				element.disabled=false;
				
				element = document.getElementById('faod');
				element.value = '';
				element.disabled=false;
			}
		

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
	
		$j("#c2").click(function(event) {
	    	var cosa=chkb($j("#c2").is(':checked'));
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
	    	var cosa=chkb($j("#c3").is(':checked'));
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
            hangeYear: true ,
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
            hangeYear: true ,
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
            hangeYear: true ,
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
            hangeYear: true ,
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
            hangeYear: true ,
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
            hangeYear: true ,
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
			update_flag = false;
			$j('#BotonPastDX').trigger('click');
		}
		
		function convertDateFormat(input)
		{
			//Input : Date in mm-dd-yy Format
			//Output: Date in yy-mm-dd Format
			if(input=='')
			{
				return '';
			}
			var str = input.split('-');
			return str[2] + '-' + str[0] + '-' + str[1];
		}
		
		
		$j('#UpdatePastDX').live('click',function()
		{
			var dxname = $j('#DXName').val();
			var icd = $j('#icdcode').val();
			var dxstart = $j('#DXStartDate').val();
			var dxend = $j('#DXEndDate').val();
			var flag=false;
			if(update_flag == true)
			{
				if(dxname != prev_pastdx[0])
				{
					flag=true;
				}
				if(icd != prev_pastdx[1])
				{
					flag=true;
				}
				if(dxstart != prev_pastdx[2])
				{
					flag=true;
				}
				if(dxend != prev_pastdx[3])
				{
					flag=true;
				}
				
				if(flag==true)
				{
					var url = 'delete_pastdx.php?id='+current_id;
					var RecTipo = LanzaAjax(url);
				}
				else
				{
					return;
				}
			}
	
			//alert('here');
			var param = '&dxname=' + dxname + '&icdcode=' + icd + '&dxstart=' + convertDateFormat(dxstart) + '&dxend=' + convertDateFormat(dxend)   ;
			var url = 'create_pastdx_Entry.php?idp='+$j('#patientid').val()+param;
			var resp = LanzaAjax(url);
			var queUrl ='getPastDX.php?idusu='+$j('#patientid').val();
			$j('#PastDX').load(queUrl);
			$j('#PastDX').trigger('update');
			//alert('Clicked Update PastDX');
		
		});
		
				
		function openMedicationPopUp()
		{
			$j('#DrugName').val('');
			$j('#DrugCode').val('');
			$j('#Dossage').val('');
			$j('#NumPerDay').val('');
			$j('#MedicationStartDate').val('');
			$j('#MedicationEndDate').val('');
			update_flag = false;
			$j('#BotonMedication').trigger('click');
		}
		
		
		$j('#UpdateMedication').live('click',function()
		{
			var drugname = $j('#DrugName').val();
			var drugcode = $j('#DrugCode').val();
			var dossage = $j('#Dossage').val();
			var numperday = $j('#NumPerDay').val();
			var startdate = $j('#MedicationStartDate').val();
			var enddate = $j('#MedicationEndDate').val();

			var flag=false;
			if(update_flag == true)
			{
				if(drugname != prev_medications[0])
				{
					flag=true;
				}
				if(drugcode != prev_medications[1])
				{
					flag=true;
				}
				if(dossage != prev_medications[2])
				{
					flag=true;
				}
				if(numperday != prev_medications[3])
				{
					flag=true;
				}
				if(startdate != prev_medications[4])
				{
					flag=true;
				}
				if(enddate != prev_medications[5])
				{
					flag=true;
				}
				
				if(flag==true)
				{
					var url = 'delete_medicatons.php?id='+current_id;
					var RecTipo = LanzaAjax(url);
				}
				else
				{
					return;
				}
			}

		
			var param = '&drugname=' + drugname + '&drugcode=' + drugcode + '&dossage=' + dossage + '&numperday=' + numperday + '&start=' + convertDateFormat(startdate)  + '&end=' + convertDateFormat(enddate) ;
			var url = 'create_medication_Entry.php?idp='+$j('#patientid').val()+param;
			//alert(url);
			var resp = LanzaAjax(url);
			//alert(resp);
			var queUrl ='getMedication.php?idusu='+$j('#patientid').val();
			$j('#Medication').load(queUrl);
			$j('#Medication').trigger('update');
		
			
			//alert('Clicked Update Medication');
		
		});
		
		function openImmunizationPopUp()
		{
			$j('#IName').val('');
			$j('#IDate').val('');
			$j('#IAge').val('');
			$j('#IReaction').val('');
			update_flag = false;
			$j('#BotonImmunization').trigger('click');
		}
		
		
		$j('#UpdateImmunization').live('click',function()
		{
			var iname = $j('#IName').val();
			var idate = $j('#IDate').val();
			var iage = $j('#IAge').val();
			var ireaction = $j('#IReaction').val();
			
			var flag=false;
			if(update_flag == true)
			{
				if(iname != prev_immunizations[0])
				{
					flag=true;
				}
				if(idate != prev_immunizations[1])
				{
					flag=true;
				}
				if(iage != prev_immunizations[2])
				{
					flag=true;
				}
				if(ireaction != prev_immunizations[3])
				{
					flag=true;
				}
				
				if(flag==true)
				{
					var url = 'delete_immunizations.php?id='+current_id;
					var RecTipo = LanzaAjax(url);
				}
				else
				{
					return;
				}
			}

			
			
			var param = '&name=' + iname + '&date=' + convertDateFormat(idate) + '&age=' + iage + '&reaction=' + ireaction ;
			var url = 'create_immunization_Entry.php?idp='+$j('#patientid').val()+param;
			var resp = LanzaAjax(url);
			
			var queUrl ='getImmunization.php?idusu='+$j('#patientid').val();
			$j('#Immunization').load(queUrl);
			$j('#Immunization').trigger('update');
		
		});
		
		function openAllergyPopUp()
		{
			$j('#AName').val('');
			$j('#AType').val('');
			$j('#ADate').val('');
			$j('#Description').val('');
			update_flag = false;
			$j('#BotonAllergy').trigger('click');
		}
		
		
		$j('#UpdateAllergy').live('click',function()
		{
			var aname = $j('#AName').val();
			var atype = $j('#AType').val();
			var adate = $j('#ADate').val();
			var desc = $j('#Description').val();
		
			var flag=false;
			if(update_flag == true)
			{
				if(aname != prev_allergies[0])
				{
					flag=true;
				}
				if(atype != prev_allergies[1])
				{
					flag=true;
				}
				if(adate != prev_allergies[2])
				{
					flag=true;
				}
				if(desc != prev_allergies[3])
				{
					flag=true;
				}
				
				if(flag==true)
				{
					var url = 'delete_allergies.php?id='+current_id;
					var RecTipo = LanzaAjax(url);
				}
				else
				{
					return;
				}
			}
		
			var param = '&name=' + aname + '&type=' + atype + '&date=' + convertDateFormat(adate) + '&description=' + desc ;
			var url = 'create_allergy_Entry.php?idp='+$j('#patientid').val()+param;
			var resp = LanzaAjax(url);
			
			var queUrl ='getAllergies.php?idusu='+$j('#patientid').val();
			$j('#Allergies').load(queUrl);
			$j('#Allergies').trigger('update');
		
		});
		
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
			var daterec = $j('#CP_Date').val();
			var height = $j('#CP_Height').val();
			var weight = $j('#CP_Weight').val();
			var hbp = $j('#CP_hbp').val();
			var lbp = $j('#CP_lbp').val();
		
			var flag=false;
			if(update_flag == true)
			{
				if(daterec != prev_cp[0])
				{
					flag=true;
				}
				if(height != prev_cp[1])
				{
					flag=true;
				}
				if(weight != prev_cp[2])
				{
					flag=true;
				}
				if(hbp != prev_cp[3])
				{
					flag=true;
				}
				if(lbp != prev_cp[4])
				{
					flag=true;
				}
				
				if(flag==true)
				{
					var url = 'delete_cp.php?id='+current_id;
					var RecTipo = LanzaAjax(url);
				}
				else
				{
					return;
				}
			}
		
			var param = '&height='+height+'&weight='+weight+'&hbp='+hbp+'&lbp='+lbp+'&daterec='+convertDateFormat(daterec);
			var url = 'create_changing_personal_history.php?idp='+$j('#patientid').val()+param;
			var resp = LanzaAjax(url);
			
			var queUrl ='get_changing_personal.php?idusu='+$j('#patientid').val();
			$j('#CP').load(queUrl);
			$j('#CP').trigger('update');
		
		});
		
		function cancelImageEdit()
		{
			document.getElementById('file').remove();
			$j('#imageFileDiv').append('<input type="file" class="btn btn-success" name="<?php echo $fileName;?>;" id="file" style="margin-left:40px;">')
		}
		
		function removeFile(serviceURL,oldName,newName) 
		{
			//console.log(serviceURL+'?oldName='+oldName+'&newName='+newName);
			$j.ajax(
			{
				url: serviceURL+'?oldName='+oldName+'&newName='+newName,
				dataType: "json",
				async: false,
				method: 'GET',
				success: function(data)
				{
					//alert('File removed.');
					//patients = data.items;
				}
			});
		}   
		function removeImage()
		{
			var response = confirm("Are you sure you want to remove the image?");
			if(response)
			{
				document.getElementById('patientImage').remove();
				$j('#imageDiv').append('<img id="patientImage" src="/PatientImage/defaultDP.jpg" style="<?php echo $style;?>;margin-left:15px;"> ')
				var oldName=<?php echo $userid;?>+'.jpg';
				//var newName=resp+'.jpg';
				removeFile('removeFile.php',oldName,oldName);
				$j("#removeImage").hide();
			}
		}
		
		function createPatient()
		{
					//alert("clicked me");
			var fname = $j('#fname').val();
			var sname = $j('#surname').val();
			
			//alert($j('#dp32').val());
			
			var n = $j('#dp32').val().split('-');
			
			var year = n[2];
			var month = n[0];
			var day = n[1];
			
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
			
			var url = 'dropzone_create_patient.php?idcreator=<?php echo $_SESSION['MEDID'];?>&idusfixed='+idusfixed+'&idusfixedname='+idusfixedname+'&name='+fname+'&surname='+sname;
			var resp = LanzaAjax(url);
			if(resp == 'failure')
			{
				alert('Error Adding Patient');
				return;
			}
			else
			{
				
				$j('#patientid').val(resp);
				//alert(resp);
				var url = 'create_emr_data.php?idp='+ resp + '&dob='+year+'-'+month+'-'+day +'&gender='+gender + '&address='+$j('#address').val() + '&address2='+ $j('#address2').val() + '&city='+ $j('#city').val() + '&state='+ $j('#state').val() + '&country='+ $j('#country').val() + '&notes='+ $j('#notes').val() + '&fractures='+ $j('#fractures').val() + '&surgeries='+ $j('#surgeries').val() +  '&otherknown='+ $j('#otherknown').val() + '&obstetric='+ $j('#obstetric').val() + '&other='+ $j('#other').val() + '&fatheralive='+ father_alive + '&fcod='+ $j('#fcod').val() + '&faod='+ $j('#faod').val() + '&frd='+ $j('#frd').val() + '&motheralive='+ mother_alive + '&mcod='+ $j('#mcod').val() + '&maod='+ $j('#maod').val() + '&mrd='+ $j('#mrd').val() + '&srd=' + $j('#siblingsRD').val(); 
				//alert(url);
				resp = LanzaAjax(url);
				alert('EMR Data Add status ' +  resp);
				add_PastDX($j('#patientid').val());
				add_medication($j('#patientid').val());
				add_immunization($j('#patientid').val());
				add_allergy($j('#patientid').val());
				alert('Patient created successfully.');
				
				
			}
						
		}
		
				
		
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
		
		
		$(document).on('click', '#Edit', function(){ 
			update_flag = true;
			switch(popup_to_show)
			{
				case 1 : $j('#DXName').val(prev_pastdx[0]);
						 $j('#icdcode').val(prev_pastdx[1]);
						 $j('#DXStartDate').val(prev_pastdx[2]);
						 $j('#DXEndDate').val(prev_pastdx[3]);
						 $j('#BotonPastDX').trigger('click');
				
				
						 break;
				case 2 : $j('#DrugName').val(prev_medications[0]);
						 $j('#DrugCode').val(prev_medications[1]);
						 $j('#Dossage').val(prev_medications[2]);
						 $j('#NumPerDay').val(prev_medications[3]);
						 $j('#MedicationStartDate').val(prev_medications[4]);
						 $j('#MedicationEndDate').val(prev_medications[5]);
						 $j('#BotonMedication').trigger('click');
				
						 break;
				case 3 : $j('#IName').val(prev_immunizations[0]);
						 $j('#IDate').val(prev_immunizations[1]);
						 $j('#IAge').val(prev_immunizations[2]);
						 $j('#IReaction').val(prev_immunizations[3]);
						 $j('#BotonImmunization').trigger('click');
							
						 break;
				case 4 : $j('#AName').val(prev_allergies[0]);
						 $j('#AType').val(prev_allergies[1]);
						 $j('#ADate').val(prev_allergies[2]);
						 $j('#Description').val(prev_allergies[3]);
						 $j('#BotonAllergy').trigger('click');
				
						 break;
				case 5 : $j('#CP_Date').val(prev_cp[0]);
						 $j('#CP_Height').val(prev_cp[1]);
						 $j('#CP_Weight').val(prev_cp[2]);
						 $j('#CP_hbp').val(prev_cp[3]);
						 $j('#CP_lbp').val(prev_cp[4]);
						 $j('#BotonCP').trigger('click');
				
						 break;
			}
		});
		
		
		$(document).on('click', '#Delete', function(){
			switch(popup_to_show)
			{
				case 1 : var url = 'delete_pastdx.php?id='+current_id;
						 var RecTipo = LanzaAjax(url);
						 if(RecTipo == 'success')
						 {
							alert('Deleted Successfully');
						 }
						 else
						 {
							alert('Error Deleting');
						 }
						 var queUrl ='getPastDX.php?idusu='+$j('#patientid').val();
						 $j('#PastDX').load(queUrl);
						 $j('#PastDX').trigger('update');
				
						break;
				case 2 : var url = 'delete_medicatons.php?id='+current_id;
						 var RecTipo = LanzaAjax(url);
						 if(RecTipo == 'success')
						 {
							alert('Deleted Successfully');
						 }
						 else
						 {
							alert('Error Deleting');
						 } 
						 var queUrl ='getMedication.php?idusu='+$j('#patientid').val();
						 $j('#Medication').load(queUrl);
						 $j('#Medication').trigger('update');
				
						break;
				case 3 : var url = 'delete_immunizations.php?id='+current_id;
						 var RecTipo = LanzaAjax(url);
						 if(RecTipo == 'success')
						 {
							alert('Deleted Successfully');
						 }
						 else
						 {
							alert('Error Deleting');
						 } 
						 var queUrl ='getImmunization.php?idusu='+$j('#patientid').val();
						 $j('#Immunization').load(queUrl);
						 $j('#Immunization').trigger('update');
				
						break;
				case 4 : var url = 'delete_allergies.php?id='+current_id;
						 var RecTipo = LanzaAjax(url);
						 if(RecTipo == 'success')
						 {
							alert('Deleted Successfully');
						 }
						 else
						 {
							alert('Error Deleting');
						 } 
				
						 var queUrl ='getAllergies.php?idusu='+$j('#patientid').val();
						 $j('#Allergies').load(queUrl);
						 $j('#Allergies').trigger('update');
				
				
				
						break;
				case 5 : var url = 'delete_cp.php?id='+current_id;
						 var RecTipo = LanzaAjax(url);
						 if(RecTipo == 'success')
						 {
							alert('Deleted Successfully');
						 }
						 else
						 {
							alert('Error Deleting');
						 } 
				
						var queUrl ='get_changing_personal.php?idusu='+$j('#patientid').val();
						$j('#CP').load(queUrl);
						$j('#CP').trigger('update');
			
						
						break;
			}
		});		
		
		 $j("tr.CFILA_pastdx").live("click",function (){
			var myClass = $(this).attr("id");
			//alert(myClass);
			popup_to_show = 1;
			current_id = myClass;
			getpastdx('getsinglepastdx.php?id='+myClass);	//fills pastdx
						
			switch(parseInt(pastdx[0].del))
			{
				case 0 : prev_pastdx[0] = pastdx[0].name;
						 prev_pastdx[1] = pastdx[0].icdcode;
						 prev_pastdx[2] = pastdx[0].datestart;
						 prev_pastdx[3] = pastdx[0].datestop;
						 $j('#BotonModal_Edit').trigger('click');
						break;
				case 1 : alert('This entry has already been deleted. ');
						 return;
					
			}
			
			
		 });
		 
		$j("tr.CFILA_medications").live("click",function (){
			var myClass = $(this).attr("id");
			//alert(myClass);
			popup_to_show = 2;
			current_id = myClass;
			getmedications('getsinglemedications.php?id='+myClass);	//fills medications
			//alert(medications[0].del);			
			switch(parseInt(medications[0].del))
			{
				case 0 : prev_medications[0] = medications[0].name;
						 prev_medications[1] = medications[0].drugcode;
						 prev_medications[2] = medications[0].dossage;
						 prev_medications[3] = medications[0].numberday;
						 prev_medications[4] = medications[0].datestart;
						 prev_medications[5] = medications[0].datestop;
						 //alert('triggering');
						 $j('#BotonModal_Edit').trigger('click');
						break;
				case 1 : alert('This entry has already been deleted. ');
						 return;
			
			
			}
		 });
		 
		$j("tr.CFILA_immunizations").live("click",function (){
			var myClass = $(this).attr("id");
			//alert(myClass);
			popup_to_show = 3;
			current_id = myClass;
			getimmunizations('getsingleimmunizations.php?id='+myClass);	//fills medications
			//alert(medications[0].del);			
			switch(parseInt(immunizations[0].del))
			{
				case 0 : prev_immunizations[0] = immunizations[0].name;
						 prev_immunizations[1] = immunizations[0].date;
						 prev_immunizations[2] = immunizations[0].age;
						 prev_immunizations[3] = immunizations[0].reaction;
						 //alert('triggering');
						 $j('#BotonModal_Edit').trigger('click');
						break;
				case 1 : alert('This entry has already been deleted. ');
						 return;
			
			
			}
			
		 });

		$j("tr.CFILA_allergies").live("click",function (){
			var myClass = $(this).attr("id");
			//alert(myClass);
			popup_to_show = 4;
			current_id = myClass;
			getallergies('getsingleallergies.php?id='+myClass);	//fills medications
			//alert(medications[0].del);			
			switch(parseInt(allergies[0].del))
			{
				case 0 : prev_allergies[0] = allergies[0].name;
						 prev_allergies[1] = allergies[0].type;
						 prev_allergies[2] = allergies[0].daterec;
						 prev_allergies[3] = allergies[0].description;
						 //alert('triggering');
						 $j('#BotonModal_Edit').trigger('click');
						break;
				case 1 : alert('This entry has already been deleted. ');
						 return;
			
			
			}
			
			
			
			
			
		 });

		$j("tr.CFILA_CP").live("click",function (){
			var myClass = $(this).attr("id");
			//alert(myClass);
			popup_to_show = 5;
			current_id = myClass;
			getcp('getsinglecp.php?id='+myClass);	//fills medications
			//alert(cp[0].del);
			
			switch(parseInt(cp[0].del))
			{
				case 0 : prev_cp[0] = cp[0].daterec;
						 prev_cp[1] = cp[0].height;
						 prev_cp[2] = cp[0].weight;
						 prev_cp[3] = cp[0].hbp;
						 prev_cp[4] = cp[0].lbp;
						 //alert('triggering');
						 $j('#BotonModal_Edit').trigger('click');
						break;
				case 1 : alert('This entry has already been deleted. ');
						 return;
			
			
			}
			
			
			
			
			
		 });
		 
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
		
		 function savePatientData()
		{
			var e = document.getElementById("gender");
			var gender = parseInt(e.options[e.selectedIndex].value);
			
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
			// var dataFile=new FormData();
			// var file = document.getElementById('file');
            // dataFile.append('file',$(file)[0].files[0]);
			var url = 'update_emr_data.php?idp='+ $j('#patientid').val() + '&dob='+convertDateFormat($j('#dp32').val()) +'&gender='+gender + '&address='+$j('#address').val() + '&address2='+ $j('#address2').val() + '&city='+ $j('#city').val() + '&state='+ $j('#state').val() + '&country='+ $j('#country').val() + '&notes='+ $j('#notes').val() + '&fractures='+ $j('#fractures').val() + '&surgeries='+ $j('#surgeries').val() +  '&otherknown='+ $j('#otherknown').val() + '&obstetric='+ $j('#obstetric').val() + '&other='+ $j('#other').val() + '&fatheralive='+ father_alive + '&fcod='+ $j('#fcod').val() + '&faod='+ $j('#faod').val() + '&frd='+ $j('#frd').val() + '&motheralive='+ mother_alive + '&mcod='+ $j('#mcod').val() + '&maod='+ $j('#maod').val() + '&mrd='+ $j('#mrd').val() + '&srd=' + $j('#siblingsRD').val() + '&phone=' + $j('#phone').val() + '&insurance=' + $j('#insurance').val() + '&name=' + $j('#fname').val() + '&surname=' + $j('#surname').val() + '&initial=' + $j('#initial').val(); 
			console.log(url);
			$j.ajax({
           url: url,
		   type: 'GET',
		  processData: false,
		  contentType:false,
		   // data: dataFile,
           async: false,
           success: function(resp) {		
			if(resp=='success')
			{	
				var oldName=<?php echo $_SESSION['MEDID'];?>+'.jpg';
				var newName=$j('#patientid').val()+'.jpg';
				renameFile('renameFile.php',oldName,newName);
				//set the emr_old of current report to 1
				//alert('going in');
				var id = $j('#patientid').val();
				var link = 'update_emr_report.php?id='+id;
				var RecTipo = LanzaAjax(link);
				//alert(RecTipo);
				//generate new emr report 
				var url = 'h2pdf.php?id='+$j('#patientid').val();
				resp = LanzaAjax(url);
				window.location.replace("<?php echo $domain;?>/patientdetailMED-new.php?IdUsu="+$j('#patientid').val());
			}
			else
			{
				alert('Error Saving Data');
			}
			}
			});
			
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