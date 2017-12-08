<?php
session_start();

 require_once("displayExitClass.php");
 require("environment_detail.php");
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];
 $domain = $env_var_db['hardcode'];

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
$patient_id = $_GET['IdUsu'];

if ($Acceso != '23432')
{
$exit_display = new displayExitClass();

$exit_display->displayFunction(1);
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
$exit_display = new displayExitClass();

$exit_display->displayFunction(2);
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

$enc_result = $con->prepare("select pass from encryption_pass where id = (select max(id) from encryption_pass)");
$enc_result->execute();
			$row_enc = $enc_result->fetch(PDO::FETCH_ASSOC);
			$enc_pass=$row_enc['pass'];

$pat = $con->prepare("select idusfixedname from usuarios where identif=?");
$pat->bindValue(1, $patient_id, PDO::PARAM_INT);
$pat->execute();

$pat_name=$pat->fetch(PDO::FETCH_ASSOC);
$patient_name = $pat_name['idusfixedname'];
			
			
			
			
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
	<link href="css/bootstrap-dropdowns.css" rel="stylesheet">
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
	
	<!-- Create language switcher instance and set default language to en-->
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js" type="text/javascript"></script>
<script src="jquery-lang-js-master/js/jquery-cookie.js" charset="utf-8" type="text/javascript"></script>
<script src="jquery-lang-js-master/js/jquery-lang.js" charset="utf-8" type="text/javascript"></script>
<script type="text/javascript">
var lang = new Lang('en');
	window.lang.dynamic('th', 'jquery-lang-js-master/js/langpack/th.json');


function delete_cookie( name ) {
  document.cookie = name + '=; expires=Thu, 01 Jan 1970 00:00:01 GMT;';
}

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
	
	pageRefresh();
}

function pageRefresh(){
location.reload();
}

//alert($.cookie('lang'));

var langType = $.cookie('lang');

if(langType == 'th'){
setTimeout(function(){
window.lang.change('th');
lang.change('th');
//alert('th');
}, 2000);
}

if(langType == 'en'){
setTimeout(function(){
window.lang.change('en');
lang.change('en');
//alert('th');
}, 2000);
}
	
</script>
    
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
     	<input type="hidden" id="USERDID" Value="<?php echo $patient_id; ?>">	
		<input type="hidden" id="patientid" Value="<?php echo $patient_id; ?>">
		<input type="hidden" id="patientname" Value="<?php echo $patient_name; ?>" >

	<!--Header Start-->
	<div class="header" >
    	
           <a href="index.html" class="logo"><h1>health2.me</h1></a>
		   
		    <style>
                .addit_button{
                    background: transparent;
                    color: whitesmoke;
                    text-shadow: none;
                    border: 1px solid #E5E5E5;
                    font-size: 12px !important;
                    height: 20px;
                    line-height: 12px;      
                }
                .addit_caret{
                   border-top: 4px solid whitesmoke;
                   margin-top: 3px !important;
                   margin-left: 5px !important;
                }                   

               </style>
               <div style="margin-top:11px;float:left; margin-left:50px;" class="btn-group">
                      <button id="lang1" type="button" class="btn btn-default dropdown-toggle addit_button" data-toggle="dropdown">
                        Language <span class="caret addit_caret"></span>
                      </button>
                      <ul class="dropdown-menu" role="menu">
                        <li><a href="#en" onclick="setCookie('lang', 'en', 30); return false;">English</a></li>
                        <li><a href="#sp" onclick="setCookie('lang', 'th', 30); return false;">Espa&ntilde;ol</a></li>
                        <li><a href="#tu" onclick="setCookie('lang', 'tu', 30); return false;">T&uuml;rk&ccedil;e</a></li>
                        <li><a href="#hi" onclick="setCookie('lang', 'hi', 30); return false;">हिंदी</a></li>
                      </ul>
                </div>
               
             <script>
               var langType = $.cookie('lang');

                if(langType == 'th')
                {
                    var language = 'th';
                    $("#lang1").html("Espa&ntilde;ol <span class=\"caret addit_caret\"></span>");
                }
                else if(langType == 'tu')
                {
                    var language = 'tu';
                    $("#lang1").html("T&uuml;rk&ccedil;e <span class=\"caret addit_caret\"></span>");
                }
                 else if(langType == 'hi')
                {
                    var language = 'hi';
                    $("#lang1").html("हिंदी <span class=\"caret addit_caret\"></span>");
                }
                else{
                    var language = 'en';
                    $("#lang1").html("English <span class=\"caret addit_caret\"></span>");
                }
                </script>
              <!-- End of new code by Pallab-->
           
           <div class="pull-right">
           
           <?php include 'message_center.php'; ?> 
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
            <div class="dropdown-menu" id="prof_dropdown" style="background-color:transparent; border:none; -webkit-box-shadow:none; box-shadow:none;">
            <div class="item_m"><span class="caret"></span></div>
            <ul class="clear_ul" >
              <li><a href="MainDashboard.php"><i class="icon-globe"></i> Home</a></li>
              
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

	  
	  <!--- VENTANA MODAL  ---> 
	  
	  <!--- VENTANA MODAL  for Records Board---> 
   	  <!--<button id="BotonModal1" data-target="#header-modal1" data-toggle="modal" class="btn btn-warning" style="display: none;">Modal with Header</button>
   	  <div id="header-modal1" class="modal hide" style="display:none;height:700px;width:1380px;margin-left:-700px;margin-top:-350px" aria-hidden="true">
         <div class="modal-header">
             <button class="close" type="button" data-dismiss="modal">×</button>
                  Report Verification
         </div>
         <div class="modal-body" style="height:700px">
			 <p>Patient Name :  <input type="text" id="patient_name" >
			    Report Type  :  <select name="reptype" id="reptype" >
									<option value="60">Summary and Demographics</option>
									<option value="30">Doctors Notes</option>
									<option value="20">Laboratory</option>
									<option value="1">Imaging</option>
									<option value="76">Pat. Notes</option>
									<option value="74">Pictures</option>
									<option value="77">Superbill</option>
									<option value="70">Other</option>
								</select>
			</p>
             <p>Please enter a Report Date : 
			 <input type="text" id="datepicker2" ></p>
			<input type="button" style="margin-top:100px" id="previous" value="Previous">
			 <input type="button" style="margin-top:100px" id="next" value="Next" onClick="next_click();">
			<div class="grid-content" id="AreaConten">
             		<img id="ImagenAmp" src="">
            </div>
			
		 </div>
		 
         <input type="hidden" id="idpin">
         <!--<div class="modal-footer" >
	         <input type="button" class="btn btn-success" value="Confirm" id="ConfirmaLink">
			 <a href="#" class="btn btn-primary" data-dismiss="modal" id="CloseModal">Close</a>
         </div>
      </div>  
	  <!--- VENTANA MODAL for Records Board ---> 


	  <!--SpeedBar Start--->
     <div class="speedbar">
     <div class="speedbar-content">
     <ul class="menu-speedbar">
		
    	 <li><a href="MainDashboard.php">Home</a></li>
		 <!--li><a href="dashboard.php" >Dashboard</a></li>
    	 <li><a href="patients.php" class="act_link">Patients</a--></li>
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

	 
	      
	     <div class="grid" class="grid span4" style="width:1150px; margin: 0 auto; margin-top:30px; padding-top:30px;">

		 <span class="label label-info" style="left:0px; margin-left:30px; font-size:30px;">Dropzone</span>
         <div style="margin:10px; margin-top:20px;" >     
			<table align="center" border="0" cellpadding="0" cellspacing="0">
			<tr><td> 
			<!-- Smart Wizard -->
        
			<div id="wizard" class="swMain" style="display:none;width:1100px">
				
				<ul>
					
					<li><a href="#step-1">
						<label class="stepNumber">1</label>
						<span class="stepDesc">
						Step 1<br />
						<small>Drop Files</small>
						</span>
					</a></li>
					<li><a href="#step-2">
						<label class="stepNumber">2</label>
						<span class="stepDesc">
						Step 2<br />
						<small>Verify Details</small>
						</span>                   
					</a></li>
  				</ul>
				
  			
				
				<div id="step-1">
					<h2 class="StepTitle">Drop Files <label align="right" id="upload_count_label" style="color:red;"></label></h2>	
					<center>
						<table style="margin-top:10px;">
						<tr>
							<td>
							<div id="dropzone" style="background: #F9F9F9; height:30px; width:290px;margin-top:0px">
								<center></center>
								<form action="upload_dropzone.php" method="post" class="dropzone" id="myAwesomeDropzone1" style="background:green;height:30px; width:auto; overflow:auto;margin-top:0px"><center style="color:white; font-size:22px;"><i class="<?php echo $TipoIconGroup[6] ?> icon-2x" style="color:white;"></i>&nbsp;&nbsp;Demographics</center></form>
							</div>
							</td>
			
							<td>
							<div id="dropzone" style="background: #F9F9F9; height:30px; width:290px;margin-top:0px;  opacity:1;">
								<center></center>
								<form action="upload_dropzone.php" method="post" class="dropzone" id="myAwesomeDropzone2" style="background:<?php echo $TipoColorGroup[3] ?>; height:30px; width:auto;overflow:auto; margin-top:0px; text-align:center;"><center style=" font-size:22px; opacity:1;"><i class="<?php echo $TipoIconGroup[3] ?> icon-2x" style="color:white;"></i>&nbsp;&nbsp;Doctors Notes</center></form>
							</div>
							</td>
			
							<td>
							<div id="dropzone" style="background: #F9F9F9; height:30px; width:290px;margin-top:0px">
								<center></center>
								<form action="upload_dropzone.php" method="post" class="dropzone" id="myAwesomeDropzone3" style="background:<?php echo $TipoColorGroup[2] ?>; height:30px; width:auto; overflow:auto; margin-top:0px; opacity:1; text-align:center;"><center style="color:white; font-size:22px;"><i class="<?php echo $TipoIconGroup[2] ?> icon-2x" style="color:white;"></i>&nbsp;&nbsp;Laboratory</center></form>
							</div>
							</td>
						</tr>
		
				
						<tr>
							<td>
							<div id="dropzone" style="background: #cacaca; height:30px; width:290px;">
								<center>Imaging</center>
								<form action="upload_dropzone.php" method="post" class="dropzone" id="myAwesomeDropzone4" style="background:<?php echo $TipoColorGroup[1] ?>;height:30px; width:auto; overflow:auto;margin-top:200px; text-align:center;"><center style="color:white; font-size:22px;"><i class="<?php echo $TipoIconGroup[1] ?> icon-2x" style="color:white;"></i>&nbsp;&nbsp;Imaging</center></form>
							</div>
							</td>
			
							<td>
							<div id="dropzone" style="background: #F9F9F9; height:30px; width:290px;">
								<center>Pat. Notes</center>
								<form action="upload_dropzone.php" method="post" class="dropzone" id="myAwesomeDropzone5" style="background:<?php echo $TipoColorGroup[8] ?>; height:30px; width:auto; overflow:auto; margin-top:200px"><center style="color:white; font-size:22px;"><i class="<?php echo $TipoIconGroup[8] ?> icon-2x" style="color:white;"></i>&nbsp;&nbsp;Pat. Notes</center></form>
							</div>
							</td>
			
							<td>
							<div id="dropzone" style="background: #F9F9F9; height:30px; width:290px;">
								<center>Pictures</center>
								<form action="upload_dropzone.php" method="post" class="dropzone" id="myAwesomeDropzone6" style="background:<?php echo $TipoColorGroup[7] ?>; height:30px; width:auto; overflow:auto; margin-top:200px"><center style="color:white; font-size:22px;"><i class="<?php echo $TipoIconGroup[7] ?> icon-2x" style="color:white;"></i>&nbsp;&nbsp;Pictures</center></form>
							</div>
							</td>
						</tr>
		
						<tr>
							<td>
							<div id="dropzone" style="background: #F9F9F9; height:30px; width:290px;">
								<center>SuperBill</center>
								<form action="upload_dropzone.php" method="post" class="dropzone" id="myAwesomeDropzone7" style="background:<?php echo $TipoColorGroup[9] ?>;height:30px; width:auto; overflow:auto;margin-top:410px"><center style="color:white; font-size:22px;"><i class="<?php echo $TipoIconGroup[9] ?> icon-2x" style="color:white;"></i>&nbsp;&nbsp;Superbill</center></form>
							</div>
							</td>
			
							<td>
							<div id="dropzone" style="background: #F9F9F9; height:30px; width:290px;">
								<center>Summary</center>
								<form action="upload_dropzone.php" method="post" class="dropzone" id="myAwesomeDropzone8" style="background:<?php echo $TipoColorGroup[6] ?>; height:30px; width:auto;overflow:auto;  margin-top:410px"><center style="color:white; font-size:22px;"><i class="<?php echo $TipoIconGroup[6] ?> icon-2x" style="color:white;"></i>&nbsp;&nbsp;Summary</center></form>
							</div>
							</td>
			
							<td>
							<div id="dropzone" style="background: #F9F9F9; height:30px; width:290px;">
								<center>Other</center>
								<form action="upload_dropzone.php" method="post" class="dropzone" id="myAwesomeDropzone9" style="background:<?php echo $TipoColorGroup[4] ?>; height:30px; width:auto; overflow:auto;  margin-top:410px"><center style="color:white; font-size:22px;"><i class="<?php echo $TipoIconGroup[4] ?> icon-2x" style="color:white;"></i>&nbsp;&nbsp;Other</center></form>
							</div>
							</td>
						</tr>
		
		
					</table>
					</center>
				</div>                      
				<div id="step-2" style="height:1050px;">
					<h2 class="StepTitle">Report Verification<label align="right" id="verified_count_label" style="color:red;"></label></h2>	
					<center>
					<br>
							<p>Patient Name :  <input type="text" id="patient_name" disabled>
							Report Type  :  <select name="reptype" id="reptype" >
									<option value="60">Summary and Demographics</option>
									<option value="30">Doctors Notes</option>
									<option value="20">Laboratory</option>
									<option value="1">Imaging</option>
									<option value="76">Pat. Notes</option>
									<option value="74">Pictures</option>
									<option value="77">Superbill</option>
									<option value="70">Other</option>
								</select>
							</p>
							<p>Please enter a Report Date : 
							<input type="text" id="datepicker2" ></p>
							<input type="hidden" id="idpin">
							<table>
								<tr>
									
									<td><input type="button"  id="previous" value = "Previous" onClick="previous_click();"></td>
									<td><div class="grid-content" id="AreaConten">
											<img id="ImagenAmp" src="">
										</div>
									</td>
									
									<td><input type="button"  id="next"   value="Next" onClick="next_click();"></td>
							    </tr>
							</table>
					</center>
				</div>
  			
			</div>
			<!-- End SmartWizard Content -->  		
 		
			</td></tr>
			</table>
		</div>

        
  
     </div>
     <!--CONTENT MAIN END-->
     
	 </div> 
    </div>
    <!--Content END-->
   <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    
	
	<script src="js/jquery.min.js"></script>
    <script src="js/jquery-ui.min.js"></script>
	
    <script type="text/javascript" >
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
	var failed_uploads=0;
	
	
	var timeoutTime = 18000000;
	//var timeoutTime = 300000;  //5minutes
var timeoutTimer = setTimeout(ShowTimeOutWarning, timeoutTime);


var active_session_timer = 60000; //1minute
var sessionTimer = setTimeout(inform_about_session, active_session_timer);

//This function is called at regular intervals and it updates ongoing_sessions lastseen time
function inform_about_session()
{
	$.ajax({
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

	
	 function getpatients(serviceURL) 
	{
		$.ajax(
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
			$.ajax(
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
			$.ajax(
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
	
	/* $(function() {
     $("input:file").change(function (){
       var fileName = $(this).val();
       var dataFile=new FormData();
	   dataFile.append('file',$(this)[0].files[0]);
			//getImageDimension('getImageDimension.php');
		var DirURL = 'upload_image.php';
			//var DirURL = 'checkFileUploaded.php';
			// alert(DirURL);
		  $.ajax({
           url: DirURL,
		   type: 'POST',
          // dataType: "html",
		  processData: false,
		  contentType:false,
		   data: dataFile,
           async: false,
           complete: function(){ //alert('Completed');
                    },
           success: function(data) {
			//alert("File uploaded.");
		   }
		   
		   
     });
	});
	});
	*/
		$(window).load(function() {
		//alert("started");
		$(".loader_spinner").fadeOut("slow");
		})
	$(document).ready(function() {
		//$('#wizard').smartWizard();
		//$("#myTab1").tabs('select',"TabDemographics");
		//$('#TabDemographics').show();
		
		$('body').bind('mousedown keydown', function(event) {
        clearTimeout(timeoutTimer);
        timeoutTimer = setTimeout(ShowTimeOutWarning, timeoutTime);
		});
		
		verified=false;
		document.getElementById("upload_count_label").innerHTML = upload_count + '/' + filecount;
		$('#wizard').hide();
		$('#wizard').smartWizard({transitionEffect:'slideleft',onLeaveStep:leaveAStepCallback,onFinish:onFinishCallback,onShowStep:showstepcallback,enableFinishButton:false});
		//alert('loaded');
		setTimeout(function(){$('#wizard').show();},100);
		
		function showstepcallback(obj)
		{
			//alert('here');
			var step_num = obj.attr('rel');
			if(parseInt(step_num) < parseInt(last_step))
			{
				//alert('inside');
				$("#wizard").smartWizard('goToStep', last_step);
				//goToStep(last_step);
				return false;
			}
			return true;
		}
		
		function leaveAStepCallback(obj)
		{
			var step_num = obj.attr('rel');
			
			switch(parseInt(step_num)+1)
			{
				case 1:	alert("In step 1");
						var patient_id;
					var patient_name = $('#tags').val();
					if(existing==true){
					patient_name="abc";
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
					
					//patient_id = $
					
					//$('#patientid').val(patient_id);
					//$('#patientname').val(patient_name);
					}
				  	   if(parseInt($('#patientid').val())==0)
					   {
						   alert('Please select/create a patient****');
						   return false;
					   }
						last_step=1;			
						return true;
						break;
				case 2: //alert("In step 2");
				
						//alert('Idpin:'+idpin_array.length + '  Failed:'+failed_uploads + ' Uploaded : '+filecount);
						if(idpin_array.length==0 && failed_uploads==0)
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
							if(failed_uploads!=0)
							{
								alert("Some of your files were not uploaded correctly");
							}
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
		patient_id = $('#patientid').val();
		doctor_id = $('#MEDID').val();
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
        
        //CALL NOTIFIICATION 
        var notificationAjax = 'push_to_notification.php?sender_id='+doctor_id+'&pat_id='+patient_id+'&is_sender_doctor=true&is_receiver_doctor=false&notifType=REPUPL';
		Rectipo = LanzaAjax(notificationAjax);
        //console.log(Rectipo);
        //confirm('passed notif: '+Rectipo);
          
		if (IdDoctorAdd > 0)
		{
			
			var url = 'MessageTray.php?patient_id='+patient_id+'&doctor_id='+doctor_id+'&Add_doctor_id='+IdDoctorAdd+'&MType=1&TMessage=&IconText=icon-folder-open-alt&SubText=NEW REPORT ('+NamePatient+')&MainText=By '+NameDoctor+'&ColorText=22aeff&LinkText=1115';
			
			//alert (url);
			RecTipo = LanzaAjax (url);
			//alert (RecTipo);
		}	
		//alert (RecTipo);	



		window.location.replace("<?php echo $domain;?>/patientdetailMED-new.php?IdUsu=<?php echo $patient_id;?>");
      }
		
		
		
		
		
		
		
		
		
		
		getpatients('getuserpatients.php');
		//alert(<?php echo $_SESSION['MEDID'];?>);
	
	
		for(var i=0;i<patients.length;i++)
		{
			availablePatientTags[i]=patients[i].idusfixedname;
			pats[patients[i].identif] = patients[i].idusfixedname;;
		}
	
			$( "#tags" ).autocomplete({
				source: availablePatientTags,
				change: function( event, ui ) {
					//alert($('#tags').val());
					
					var patient_id;
					var patient_name = $('#tags').val();
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
					$('#patientid').val(patient_id);
					$('#patientname').val(patient_name);
					//alert('Set patient id to '+ $('#patientid').val() );
				}
			});
  

	
	});	
		
		/*KYLEDropzone.options.myAwesomeDropzone1 = {
			//autoProcessQueue: false,	
			//previewTemplate: '<span class="label label-info" style="left:0px; margin-left:30px; font-size:30px;">Patient Creator</span>',
			//maxFilesize:0,
			init: function() 
			{
				myDropzone1 = this; 
				this.on("addedfile", function(file) {
					/*num=1;
					if($('#patientid').val() == 0)
					{
						myDropzone1.removeFile(file);
						alert('Please Select/Create a patient');
						return;
					}
					//alert('File dropped on 1' + file.name);
					$('#filename').val(file.name);
					$('#datepicker1').val('');
					//$('#BotonModal').trigger('click');
					
					*/
				/*KYLE});
				
				this.on("sending", function(file, xhr, formData) {
					//formData.append("repdate", $('#datepicker1').val()); // Append all the additional input data of your form here!
					/*formData.append("idus",$('#patientid').val());
					formData.append("tipo",60);
					formData.append("id",filecount);
					orig_file_array[filecount] = file.name;
					filecount++;
					//alert('sending file');
					document.getElementById("upload_count_label").innerHTML = upload_count + '/' + filecount;
					myDropzone1.processQueue();*/
					
				/*KYLE});
				
				this.on("success",function(file,resp){
					
					//alert(resp);
					/*upload_count++;
					document.getElementById("upload_count_label").innerHTML = upload_count + '/' + filecount;
					var str = resp.split(";");
					//alert(resp);
					//alert(str[0] + '  ' + str[1] + '  ' + str[2] + '  ' + str[3]);
					idpin_array[str[0]] = str[1];
					files[str[1]] = str[2];
					types[str[1]] = str[3];
					//alert('file sent'+ str[2]);
					//var contenURL =   '<?php echo $domain ;?>/temp/<?php echo $_SESSION['MEDID'] ;?>/Packages_Encrypted/'+str[2];
					//var conten =  '<iframe id="ImagenN" style="border:1px solid #666CCC" title="PDF" src="'+contenURL+'" frameborder="1" scrolling="auto" height="850" width="600" ></iframe>';
					//$('#AreaConten').html(conten);
					//alert("uploaded successfully");	*/
					
				/*KYLE});
				
								
				this.on("error",function(file,errorMessage){
					//alert(file.name + " not uploaded properly");
					failed_uploads++;
				});
						
			}
		};*/
		
		Dropzone.options.myAwesomeDropzone1 = {
			//autoProcessQueue: false,	
            maxFilesize: 25,  
            acceptedFiles: "image/png,image/jpeg,image/tiff,image/gif,application/pdf",
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
					//alert('file dropped on 2');
					$('#filename').val(file.name);
					$('#datepicker1').val('');
					//$('#BotonModal').trigger('click');
					
				});
				
				this.on("sending", function(file, xhr, formData) {
					//formData.append("repdate", $('#datepicker1').val()); // Append all the additional input data of your form here!
					formData.append("idus",$('#patientid').val());
					formData.append("tipo",30);
					formData.append("id",filecount);
					orig_file_array[filecount] = file.name;
					filecount++;
					document.getElementById("upload_count_label").innerHTML = upload_count + '/' + filecount;
					myDropzone1.processQueue();
					//alert($('#patientid').val());
					
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
				
				this.on("error",function(file,errorMessage){
					failed_uploads++;
				});
						
			}
		};
		
		Dropzone.options.myAwesomeDropzone2 = {
			//autoProcessQueue: false,	
            maxFilesize: 25,  
            acceptedFiles: "image/png,image/jpeg,image/tiff,image/gif,application/pdf",
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
					$('#filename').val(file.name);
					$('#datepicker1').val('');
					//$('#BotonModal').trigger('click');
					
				});
				
				this.on("sending", function(file, xhr, formData) {
					//formData.append("repdate", $('#datepicker1').val()); // Append all the additional input data of your form here!
					formData.append("idus",$('#patientid').val());
					formData.append("tipo",30);
					formData.append("id",filecount);
					orig_file_array[filecount] = file.name;
					filecount++;
					document.getElementById("upload_count_label").innerHTML = upload_count + '/' + filecount;
					myDropzone2.processQueue();
					//alert($('#patientid').val());
					
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
				
				this.on("error",function(file,errorMessage){
					failed_uploads++;
				});
						
			}
		};
		
		Dropzone.options.myAwesomeDropzone3 = {
			//autoProcessQueue: false,	
            maxFilesize: 25,  
            acceptedFiles: "image/png,image/jpeg,image/tiff,image/gif,application/pdf",
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
					$('#filename').val(file.name);
					$('#datepicker1').val('');
					//$('#BotonModal').trigger('click');
					
				});
				
				this.on("sending", function(file, xhr, formData) {
					//formData.append("repdate", $('#datepicker1').val()); // Append all the additional input data of your form here!
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
				
				this.on("error",function(file,errorMessage){
					failed_uploads++;
				});
				
						
			}
		};
		
		Dropzone.options.myAwesomeDropzone4 = {
			//autoProcessQueue: false,	
            maxFilesize: 25,  
            acceptedFiles: "image/png,image/jpeg,image/tiff,image/gif,application/pdf",
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
					$('#filename').val(file.name);
					$('#datepicker1').val('');
					//$('#BotonModal').trigger('click');
					
				});
				
				this.on("sending", function(file, xhr, formData) {
					//formData.append("repdate", $('#datepicker1').val()); // Append all the additional input data of your form here!
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

				this.on("error",function(file,errorMessage){
					failed_uploads++;
				});
			}
		};
		
		Dropzone.options.myAwesomeDropzone5 = {
			//autoProcessQueue: false,	
            maxFilesize: 25,  
            acceptedFiles: "image/png,image/jpeg,image/tiff,image/gif,application/pdf",
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
					$('#filename').val(file.name);
					$('#datepicker1').val('');
					//$('#BotonModal').trigger('click');
					
				});
				
				this.on("sending", function(file, xhr, formData) {
				
					//formData.append("repdate", $('#datepicker1').val()); // Append all the additional input data of your form here!
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
				
				this.on("error",function(file,errorMessage){
					failed_uploads++;
				});
				
						
			}
		};		
		
		Dropzone.options.myAwesomeDropzone6 = {
			//autoProcessQueue: false,	
            maxFilesize: 25,  
            acceptedFiles: "image/png,image/jpeg,image/tiff,image/gif,application/pdf",
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
					$('#filename').val(file.name);
					$('#datepicker1').val('');
					//$('#BotonModal').trigger('click');
					
				});
				
				this.on("sending", function(file, xhr, formData) {
					//formData.append("repdate", $('#datepicker1').val()); // Append all the additional input data of your form here!
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
				
				this.on("error",function(file,errorMessage){
					failed_uploads++;
				});
						
			}
		};
		
		Dropzone.options.myAwesomeDropzone7 = {
			//autoProcessQueue: false,	
            maxFilesize: 25,  
            acceptedFiles: "image/png,image/jpeg,image/tiff,image/gif,application/pdf",
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
					$('#filename').val(file.name);
					$('#datepicker1').val('');
					//$('#BotonModal').trigger('click');
					
				});
				
				this.on("sending", function(file, xhr, formData) {
					//formData.append("repdate", $('#datepicker1').val()); // Append all the additional input data of your form here!
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
				
				this.on("error",function(file,errorMessage){
					failed_uploads++;
				});		
			}
		};
		
		Dropzone.options.myAwesomeDropzone8 = {
			//autoProcessQueue: false,	
            maxFilesize: 25,  
            acceptedFiles: "image/png,image/jpeg,image/tiff,image/gif,application/pdf",
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
					$('#filename').val(file.name);
					$('#datepicker1').val('');
					//$('#BotonModal').trigger('click');
					
				});
				
				this.on("sending", function(file, xhr, formData) {
					//formData.append("repdate", $('#datepicker1').val()); // Append all the additional input data of your form here!
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
				
				this.on("error",function(file,errorMessage){
					failed_uploads++;
				});		
			}
		};
		
		Dropzone.options.myAwesomeDropzone9 = {
			//autoProcessQueue: false,	
            maxFilesize: 25,  
            acceptedFiles: "image/png,image/jpeg,image/tiff,image/gif,application/pdf",
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
					$('#filename').val(file.name);
					$('#datepicker1').val('');
					//$('#BotonModal').trigger('click');
					
				});
				
				this.on("sending", function(file, xhr, formData) {
					//formData.append("repdate", $('#datepicker1').val()); // Append all the additional input data of your form here!
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
				
				this.on("error",function(file,errorMessage){
					failed_uploads++;
				});		
			}
		};
		
		
		
		
		
		
		
		$("#ConfirmaLink").live('click',function()
		{
			//filelist[filecount]= $('#filename').val();
			//datelist[filecount]= $('#datepicker1').val();
			//filecount++;
			$('#CloseModal').trigger('click');
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
		
		$("#datepicker1" ).datepicker('');
		$("#datepicker2" ).datepicker({
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
		
			
		$("#addPatient").live('click',function() 
		{
		
			//alert("clicked me");
			var fname = $('#fname').val();
			var sname = $('#sname').val();
			
			var year = $('#Year').val();
			var month = $('#Month').val();
			var day = $('#Day').val();
			
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
			//alert(fname + ' ' +sname + ' ' + year+month+day+gender + '  ' + idusfixedname + '  ' + idusfixed);
			
			// var url = 'dropzone_create_patient.php?idcreator=<?php echo $_SESSION['MEDID'];?>&idusfixed='+idusfixed+'&idusfixedname='+idusfixedname+'&name='+fname+'&surname='+sname;
			// var resp = LanzaAjax(url);
			// if(resp == 'success')
			// {
				// alert('Patient created successfully. Please drop files for the patient');
				// $('#patientid').val(resp);
				// $('#patientname').val(idusfixedname);
				// existing=false;
				// $(".buttonNext").trigger('click');
				// alert($('#patientid').val());
			
				
			// }
			// else
			// {
				// if(resp == 'failure')
				// {
					// alert('Error Adding Patient');
				// }
				// else
				// {
					// alert('Patient Already Exists');
				// }
				// return;
			// }
			
			// var img = document.getElementById('file'); 
			// or however you get a handle to the IMG
			// var width = img.clientWidth;
			// var height = img.clientHeight;
			// if(width<150 || height<150)
			// {
				// alert("Please select image of minimum size 150 X 150.");
				// return;
			// }
		var dataFile=new FormData();
			var file = document.getElementById('file');
            dataFile.append('file',$(file)[0].files[0]);
			var DirURL = 'dropzone_create_patient.php?idcreator=<?php echo $_SESSION['MEDID'];?>&idusfixed='+idusfixed+'&idusfixedname='+idusfixedname+'&name='+fname+'&surname='+sname+'&initial='+initial;
			//var DirURL = 'checkFileUploaded.php';
			//alert(DirURL);
		  $.ajax({
           url: DirURL,
		   type: 'POST',
          // dataType: "html",
		  processData: false,
		  contentType:false,
		   data: dataFile,
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
				$('#patientid').val(resp);
				$('#patientname').val(idusfixedname);
				var oldName=<?php echo $_SESSION['MEDID'];?>+'.jpg';
				var newName=resp+'.jpg';
				renameFile('renameFile.php',oldName,newName);
				var url = 'create_emr_data.php?idp='+ resp + '&dob='+$('#dp32').val() +'&gender='+gender + '&address='+$('#address').val() + '&address2='+ $('#address2').val() + '&city='+ $('#city').val() + '&state='+ $('#state').val() + '&country='+ $('#country').val() + '&notes='+ $('#notes').val() + '&fractures='+ $('#fractures').val() + '&surgeries='+ $('#surgeries').val() +  '&otherknown='+ $('#otherknown').val() + '&obstetric='+ $('#obstetric').val() + '&other='+ $('#other').val() + '&fatheralive='+ father_alive + '&fcod='+ $('#fcod').val() + '&faod='+ $('#faod').val() + '&frd='+ $('#frd').val() + '&motheralive='+ mother_alive + '&mcod='+ $('#mcod').val() + '&maod='+ $('#maod').val() + '&mrd='+ $('#mrd').val() + '&srd=' + $('#siblingsRD').val(); 
				resp = LanzaAjax(url);
				add_PastDX($('#patientid').val());
				add_medication($('#patientid').val());
				add_immunization($('#patientid').val());
				add_allergy($('#patientid').val());
				var url = 'h2pdf.php?id='+$('#patientid').val();
				resp = LanzaAjax(url);
				alert('Patient created successfully.');
				existing=false;
				$(".buttonNext").trigger('click');
               }
            });
		});
		
	
	
		$('#fname').blur(function() {
			$('#patientid').val(0);
		});
		
		$('#sname').blur(function() {
			$('#patientid').val(0);
		});
		
		$('#Year').blur(function() {
			$('#patientid').val(0);
		});
		
		$('#Month').blur(function() {
			$('#patientid').val(0);
		});
		
		$('#Day').blur(function() {
			$('#patientid').val(0);
		});
		
		$("#BotonBusquedaSents").click(function(event) {
		 //alert('clicked');
 	     var IdMed = $('#MEDID').val();
	     var UserDOB = '';
		 var UserInput = $('#SearchUserUSERFIXED').val();
	     //alert(IdMed + '   ' + UserInput);
		 var queUrl ='getSearchUsers.php?Usuario='+UserInput+'&UserDOB='+UserDOB+'&IdDoc='+IdMed+'&NReports=2';
		 //var queUrl ='getSents.php?Doctor='+IdMed+'&DrEmail='+UserDOB+'&NReports=3';
    	 $('#TablaSents').load(queUrl);
    	 $('#TablaSents').trigger('update');
		 
    });       

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
		function done_uploading()
		{
			//alert("In Done Uploading " + idpin_array.length);
			//curr_file=0;
			last_press='next';
			
			var temp=new Array();
			var j=0;
			for(var i=0;i<idpin_array.length;i++)
			{
				if(idpin_array[i]==null)
				{
					continue;
				}
				else
				{
					
					temp[j]=idpin_array[i];
					j++;
				}
			}
			
			idpin_array=new Array();
			for(var i=0;i<temp.length;i++)
			{
				idpin_array[i]=temp[i];
			}
			
			//alert("New length: " + idpin_array.length);
			
			for(var i=0;i<idpin_array.length;i++)
			{
				//alert(idpin_array[i]);
				rep_date[idpin_array[i]]='';
			}
			
			curr_file=0;	
			$('#next').trigger('click');
			$('#patient_name').disabled=true;
			//$('#BotonModal1').trigger('click');
			
		}
        
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
			$('#idpin').val(idpin);
			if($('#datepicker2').val().length > 0) $('#datepicker2').val(convertDateFormat1(rep_date[idpin]));
            else $('#datepicker2').val('');
			$('#patient_name').val($('#patientname').val());
			
		
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
			$('#AreaConten').html(conten);			
			
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
					//alert('You have not uploaded any files');
					window.location.replace("<?php echo $domain;?>/patientdetailMED-new.php?IdUsu=<?php echo $patient_id;?>");
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
			$('#idpin').val(idpin);
			if(rep_date[idpin] == '')
			{
				if(curr_file != 0)
				{
					var prev_idpin = idpin_array[curr_file-1];
                    $('#datepicker2').val(convertDateFormat1(rep_date[prev_idpin]));
					$('#datepicker2').trigger('change');
				}
			}
			else
			{
				$('#datepicker2').val('');
			}
			
			$('#patient_name').val($('#patientname').val());
						
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
			$('#AreaConten').html(conten);			
			curr_file++;
			last_press='next';
			document.getElementById("verified_count_label").innerHTML = curr_file + '/' + idpin_array.length;
		}
		
		$('#reptype').change(function() {
			var idpin = parseInt($('#idpin').val());
			var report_type = document.getElementById("reptype");
			types[idpin] = report_type.options[report_type.selectedIndex].value;
			//alert('changed '+ idpin+'  '+types[idpin]);
		
		});
		
		$('#gender').change(function() {
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
		
		$('#datepicker2').change(function() {
				
			var idpin = $('#idpin').val();
			rep_date[idpin] = convertDateFormat($('#datepicker2').val());
			//alert('set ' + idpin + '   ' +rep_date[idpin]);
		});
		
		$('#dp32').datepicker({
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
		
		$('#DXStartDate').datepicker({
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
		
		$('#DXEndDate').datepicker({
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
		
		$('#MedicationStartDate').datepicker({
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
		
		
		
		$('#MedicationEndDate').datepicker({
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
		
		$('#IDate').datepicker({
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
		
		$('#ADate').datepicker({
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
		
		$("#c3").click(function(event) {
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
		
		function createPatient()
		{
					//alert("clicked me");
			var fname = $('#fname').val();
			var sname = $('#surname').val();
			
			/*
			//Code Added for Changing Personal
			var height = $('#height').val();
			var weight = $('#weight').val();
			var hbp = $('#hbp').val();
			var lbp = $('#lbp').val();
			
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
			var initial = $('#initial').val();
			var img = new Image();
			img.src = $('#file').val();
			//alert(img.src);
			//or however you get a handle to the IMG
			 var width = img.clientWidth;
			 var height = img.clientHeight;
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
			// if ((file = $(file)[0].files[0])) {
				// img = new Image();
				// img.onload = function () {
					// alert(this.width + " " + this.height);
				// };
				// img.src = _URL.createObjectURL(file);
			// }
            // dataFile.append('file',$(file)[0].files[0]);
			//getImageDimension('getImageDimension.php');
		var DirURL = 'dropzone_create_patient.php?idcreator=<?php echo $_SESSION['MEDID'];?>&idusfixed='+idusfixed+'&idusfixedname='+idusfixedname+'&name='+fname+'&surname='+sname+'&initial='+initial;
			//var DirURL = 'checkFileUploaded.php';
			// alert(DirURL);
		  $.ajax({
           url: DirURL,
		   type: 'POST',
          // dataType: "html",
		  processData: false,
		  contentType:false,
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
				$('#patientid').val(resp);
				$('#patientname').val(idusfixedname);
				var oldName=<?php echo $_SESSION['MEDID'];?>+'.jpg';
				var newName=resp+'.jpg';
				renameFile('renameFile.php',oldName,newName);
				var url = 'create_emr_data.php?idp='+ resp + '&dob='+convertDateFormat($('#dp32').val()) +'&gender='+gender + '&address='+$('#address').val() + '&address2='+ $('#address2').val() + '&city='+ $('#city').val() + '&state='+ $('#state').val() + '&country='+ $('#country').val() + '&notes='+ $('#notes').val() + '&fractures='+ $('#fractures').val() + '&surgeries='+ $('#surgeries').val() +  '&otherknown='+ $('#otherknown').val() + '&obstetric='+ $('#obstetric').val() + '&other='+ $('#other').val() + '&fatheralive='+ father_alive + '&fcod='+ $('#fcod').val() + '&faod='+ $('#faod').val() + '&frd='+ $('#frd').val() + '&motheralive='+ mother_alive + '&mcod='+ $('#mcod').val() + '&maod='+ $('#maod').val() + '&mrd='+ $('#mrd').val() + '&srd=' + $('#siblingsRD').val(); 
				resp = LanzaAjax(url);
				add_PastDX($('#patientid').val());
				add_medication($('#patientid').val());
				add_immunization($('#patientid').val());
				add_allergy($('#patientid').val());
				var url = 'h2pdf.php?id='+$('#patientid').val();
				resp = LanzaAjax(url);
				alert('Patient created successfully.');
				existing=false;
				$(".buttonNext").trigger('click');
               }
            })
		}
		
		
		//Added this code for changing personal history
		function add_changing_personal_history(idp)
		{
			var height = $('#height').val();
			var weight = $('#weight').val();
			var hbp = $('#hbp').val();
			var lbp = $('#lbp').val();
			var daterec = '';
			var param = '&height='+height+'&weight='+weight+'&hbp='+hbp+'&lbp='+lbp+'&daterec='+daterec;
			
			var url = 'create_changing_personal_history.php?idp='+idp+param;
			var resp = LanzaAjax(url);
			//alert(resp);	
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
		
		function displayalertnotification(message){
  
			var gritterOptions = {
               title: 'status',
               text: message,
               sticky: false,
               time: '3000'
              };
			$.gritter.add(gritterOptions);
    
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
