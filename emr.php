<?php
session_start();
 require("environment_detail.php");
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];

$NombreEnt = 'x';
$PasswordEnt = 'x';


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
$row = $query->fetch(PDO::FETCH_ASSOC);	 

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
	
	<link rel="stylesheet" href="css/jquery-ui-autocomplete.css" />
				<script src="js/jquery-1.9.1-autocomplete.js"></script>
	<script src="js/jquery-ui-autocomplete.js"></script>

   

   


 
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
  <body style="background: #F9F9F9;" >
<input type="hidden" id="patientid">	
     	
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
         
		
		 
    	 <li><a href="patients.php" class="act_link">Patients</a></li>
         <li><a href="medicalConnections.php" >Doctor Connections</a></li>
		
         <li><a href="medicalConfiguration.php">Configuration</a></li>
         <li><a href="logout.php" style="color:yellow;">Sign Out</a></li>
     </ul>

     
     </div>
     </div>
     <!--SpeedBar END-->
     
     
     <!--CONTENT MAIN START-->
	 <div class="content">
	 
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
				<!--<li><a href="#TabNotes" data-toggle="tab">Confirm</a></li>-->
		
			</ul>
			</center>
			<div id="myTabContent" class="tab-content tabs-main-content padding-null" >	
			<div class="tab-pane tab-overflow-main fade in active" id="TabDemographics">
				<div style="margin:15px; margin-top:5px;">
					<div class="row-fluid"  style="">	            
						<div class="grid" style="padding:10px;height:400px">
							
							<div style="margin-left:20px; margin-top:20px; color: #3d93e0; font-size:20px; font-weight:bold; width:100%; ">Demographics</div>
								<hr>
								
								<div style="float:left; width:450px; margin:10px; padding:10px; height:250px; margin-top:-10px">
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
											<input id="dp32"  />
											<!--<div class="validate[custom[date],past[2013/01/01]] input-append date" id="dp3" data-date="12-02-2012" data-date-format="dd-mm-yyyy" >
												<input class="span2" size="16" type="text" value="12-02-2012" id="dp32" name="dp32" readonly="" style="width:150px">
												<span class="add-on" onclick='return false;' style="width:20px"><i class="icon-th"></i></span>
											</div>--->
										</div>
									</div>
								</div>
								
								<div style="float:left; width:500px; margin:10px; padding:10px; height:250px; margin-top:-10px">
									
									
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
									
									<div class="formRow" >
										<?php if($row['state']) 
										echo '<label style="margin-left:30px;">State: </label>';
										?>
										<div class="formRight" >
											 <input id="state" name="state" type="text" class="first-input" style="width:120px; border-bottom-right-radius:3px; border-bottom-left-radius:3px; <?php if(!$row['state']) echo 'display:none'?> "/>
											 
											 
												<?php if($row['country']) echo '&nbsp &nbsp Country :';?><input id="country" name="country" type="text" class="first-input" style="width:60px; border-bottom-right-radius:3px; border-bottom-left-radius:3px;<?php if(!$row['country']) echo 'display:none'?>"/>
											
											
										</div>
									</div>
									
									
									<div class="formRow" <?php if(!$row['notes']) echo 'style="display:none"'?>>
										<label style="margin-left:0px;">Notes: </label>
										<!--<div class="formRight" style="margin-left:0px;">-->
											<textarea rows="3" cols="150" style="width:420px;margin-left:25px;resize:none" id="notes">	</textarea>
										<!--</div>-->
									</div>
								
								</div>
								
								
								
															
						</div>
					</div>
				</div>
			</div>	
				
				
				
			<div class="tab-pane" id="TabPersonal">	
				<div style="margin:15px; margin-top:5px;">
					<div class="row-fluid"  style="">	            
						<div class="grid" style="padding:10px;height:330px">
							
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
						<div class="grid" style="padding:10px;height:250px">
							
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
						<div class="grid" style="padding:10px;height:250px">
							
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
						<div class="grid" style="padding:10px;height:250px">
							
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
						<div class="grid" style="padding:10px;height:250px">
							
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
						<div class="grid" style="padding:10px;height:150px">
							
							<div style="margin-left:20px; margin-top:20px; color: #3d93e0; font-size:20px; font-weight:bold; width:100%; ">Confirmation</div>
								<hr>
								
								
									<div style="margin-left:20px;margin-top:10px; float:left;">
										Please confirm that you want to create this patient : &nbsp &nbsp
									</div>
									<!--<div style="float:right; margin-right:20px;margin-top:10px;">-->
										<input type="button" class="btn btn-primary" value="Yes" onClick="createPatient();" style="margin-top:7px;">	
										<input type="button" class="btn btn-primary" value="No" style="margin-top:7px;">	
									<!--</div>-->
								
									
						</div>
					</div>
				</div>
			</div>
			
			<center>
			<input type="button" class="btn btn-primary" value="SAVE" onClick="createPatient();" style="margin-top:7px;">	
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
	 		
		$(document).ready(function() {

			
			//var queUrl ='getPastDX.php';
			//$('#PastDX').load(queUrl);
			//$('#PastDX').trigger('update');
			/*
			var queUrl ='getMedication.php';
			$('#Medication').load(queUrl);
			$('#Medication').trigger('update');
		
			var queUrl ='getImmunization.php';
			$('#Immunization').load(queUrl);
			$('#Immunization').trigger('update');
			
			var queUrl ='getAllergies.php';
			$('#Allergies').load(queUrl);
			$('#Allergies').trigger('update');
			*/
			
			
			
			
		
		});	

		$('#dp32').datepicker({
			inline: true,
			nextText: '&rarr;',
			prevText: '&larr;',
			showOtherMonths: true,
			dateFormat: 'yy-mm-dd',
			dayNamesMin: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
			showOn: "button",
			buttonImage: "images/calendar-blue.png",
			buttonImageOnly: true,
		});
		
		$('#DXStartDate').datepicker({
			inline: true,
			nextText: '&rarr;',
			prevText: '&larr;',
			showOtherMonths: true,
			dateFormat: 'yy-mm-dd',
			dayNamesMin: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
			showOn: "button",
			buttonImage: "images/calendar-blue.png",
			buttonImageOnly: true,
		});
		
		$('#DXEndDate').datepicker({
			inline: true,
			nextText: '&rarr;',
			prevText: '&larr;',
			showOtherMonths: true,
			dateFormat: 'yy-mm-dd',
			dayNamesMin: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
			showOn: "button",
			buttonImage: "images/calendar-blue.png",
			buttonImageOnly: true,
		});
		
		$('#MedicationStartDate').datepicker({
			inline: true,
			nextText: '&rarr;',
			prevText: '&larr;',
			showOtherMonths: true,
			dateFormat: 'yy-mm-dd',
			dayNamesMin: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
			showOn: "button",
			buttonImage: "images/calendar-blue.png",
			buttonImageOnly: true,
		});
		
		$('#MedicationEndDate').datepicker({
			inline: true,
			nextText: '&rarr;',
			prevText: '&larr;',
			showOtherMonths: true,
			dateFormat: 'yy-mm-dd',
			dayNamesMin: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
			showOn: "button",
			buttonImage: "images/calendar-blue.png",
			buttonImageOnly: true,
		});
		
		$('#IDate').datepicker({
			inline: true,
			nextText: '&rarr;',
			prevText: '&larr;',
			showOtherMonths: true,
			dateFormat: 'yy-mm-dd',
			dayNamesMin: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
			showOn: "button",
			buttonImage: "images/calendar-blue.png",
			buttonImageOnly: true,
		});
		
		$('#ADate').datepicker({
			inline: true,
			nextText: '&rarr;',
			prevText: '&larr;',
			showOtherMonths: true,
			dateFormat: 'yy-mm-dd',
			dayNamesMin: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
			showOn: "button",
			buttonImage: "images/calendar-blue.png",
			buttonImageOnly: true,
		});
			
		function openPastDXPopUp()
		{
			$('#DXName').val('');
			$('#icdcode').val('');
			$('#DXStartDate').val('');
			$('#DXEndDate').val('');
			$('#BotonPastDX').trigger('click');
		}
		
		
		$('#UpdatePastDX').live('click',function()
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
			$('#DrugName').val('');
			$('#DrugCode').val('');
			$('#Dossage').val('');
			$('#NumPerDay').val('');
			$('#MedicationStartDate').val('');
			$('#MedicatioEndDate').val('');
			$('#BotonMedication').trigger('click');
		}
		
		
		$('#UpdateMedication').live('click',function()
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
			$('#IName').val('');
			$('#IDate').val('');
			$('#IAge').val('');
			$('#IReaction').val('');
			$('#BotonImmunization').trigger('click');
		}
		
		
		$('#UpdateImmunization').live('click',function()
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
		
		$("#c2").click(function(event) {
	    	var cosa=chkb($("#c2").is(':checked'));
			if(cosa==1)
			{
				//alert('Father is alive');
				
				var element = document.getElementById('fcod');
				element.disabled=true;
				
				element = document.getElementById('faod');
				element.disabled=true;
			}
			else
			{
				//alert('Father is dead');
				var element = document.getElementById('fcod');
				element.disabled=false;
				
				element = document.getElementById('faod');
				element.disabled=false;
			}
		
		});
		
		$("#c3").click(function(event) {
	    	var cosa=chkb($("#c3").is(':checked'));
			if(cosa==1)
			{
				//alert('Mother is alive');
				
				var element = document.getElementById('mcod');
				element.disabled=true;
				
				element = document.getElementById('maod');
				element.disabled=true;
			}
			else
			{
				//alert('Father is dead');
				var element = document.getElementById('mcod');
				element.disabled=false;
				
				element = document.getElementById('maod');
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
			$('#AName').val('');
			$('#AType').val('');
			$('#ADate').val('');
			$('#Description').val('');
			$('#BotonAllergy').trigger('click');
		}
		
		
		$('#UpdateAllergy').live('click',function()
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
		
		
		function createPatient()
		{
					//alert("clicked me");
			var fname = $('#fname').val();
			var sname = $('#surname').val();
			
			//alert($('#dp32').val());
			
			var n = $('#dp32').val().split('-');
			
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
			
			var url = 'dropzone_create_patient.php?idcreator=<?php echo $_SESSION['MEDID'];?>&idusfixed='+idusfixed+'&idusfixedname='+idusfixedname+'&name='+fname+'&surname='+sname;
			var resp = LanzaAjax(url);
			if(resp == 'failure')
			{
				alert('Error Adding Patient');
				return;
			}
			else
			{
				
				$('#patientid').val(resp);
				//alert(resp);
				//resp=1187;
				var url = 'create_emr_data.php?idp='+ resp + '&dob='+$('#dp32').val() +'&gender='+gender + '&address='+$('#address').val() + '&address2='+ $('#address2').val() + '&city='+ $('#city').val() + '&state='+ $('#state').val() + '&country='+ $('#country').val() + '&notes='+ $('#notes').val() + '&fractures='+ $('#fractures').val() + '&surgeries='+ $('#surgeries').val() +  '&otherknown='+ $('#otherknown').val() + '&obstetric='+ $('#obstetric').val() + '&other='+ $('#other').val() + '&fatheralive='+ father_alive + '&fcod='+ $('#fcod').val() + '&faod='+ $('#faod').val() + '&frd='+ $('#frd').val() + '&motheralive='+ mother_alive + '&mcod='+ $('#mcod').val() + '&maod='+ $('#maod').val() + '&mrd='+ $('#mrd').val() + '&srd=' + $('#siblingsRD').val(); 
				
				
				resp = LanzaAjax(url);
				alert('EMR Data Add status ' +  resp);
				add_PastDX($('#patientid').val());
				add_medication($('#patientid').val());
				add_immunization($('#patientid').val());
				add_allergy($('#patientid').val());
				alert('Patient created successfully.');
				
				
			}
						
		}
		
		function add_PastDX(idp)
		{
			var table = document.getElementById('PastDX');
            var rowCount = table.rows.length;
 
            for(var i=1; i<rowCount; i++)
			{
				var row = table.rows[i];
				var param = '&dxname=' + row.cells[0].innerText + '&icdcode=' + row.cells[1].innerText + '&dxstart=' + row.cells[2].innerText + '&dxend=' + row.cells[3].innerText   ;
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
				var param = '&drugname=' + row.cells[0].innerText + '&drugcode=' + row.cells[1].innerText + '&dossage=' + row.cells[2].innerText + '&numperday=' + row.cells[3].innerText + '&start=' + row.cells[4].innerText  + '&end=' + row.cells[5].innerText ;
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
				var param = '&name=' + row.cells[0].innerText + '&date=' + row.cells[1].innerText + '&age=' + row.cells[2].innerText + '&reaction=' + row.cells[3].innerText ;
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
				var param = '&name=' + row.cells[0].innerText + '&type=' + row.cells[1].innerText + '&date=' + row.cells[2].innerText + '&description=' + row.cells[3].innerText ;
				var url = 'create_allergy_Entry.php?idp='+idp+param;
				//alert(url);
				var resp = LanzaAjax(url);
			}
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
    <script src="js/morris.js"></script>
    
	<script src="js/application.js"></script>
	<script type="text/javascript" src="js/jquery.tooltipster.js"></script>


	
<!--<script type="text/javascript" src="js/jquery-2.0.0.min.js"></script>-->
<script type="text/javascript" src="js/jquery.smartWizard.js"></script>

	

  </body>
</html>
