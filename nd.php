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
//KYLE$link = mysql_connect("$dbhost", "$dbuser", "$dbpass")or die("cannot connect");
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

	  <!--- VENTANA MODAL  ---> 
   	  <button id="BotonModal" data-target="#header-modal" data-toggle="modal" class="btn btn-warning" style="display: none;">Modal with Header</button>
   	  <div id="header-modal" class="modal hide" style="display: none;" aria-hidden="true">
         <div class="modal-header">
             <button class="close" type="button" data-dismiss="modal">×</button>
                  Report Date
         </div>
         <div class="modal-body">
		 
             <p>Please enter a Report Date : </p>
			 <input type="text" id="datepicker1" >
         </div>
         <input type="hidden" id="filename">
         <div class="modal-footer">
	         <input type="button" class="btn btn-success" value="Confirm" id="ConfirmaLink">
			 <a href="#" class="btn btn-primary" data-dismiss="modal" id="CloseModal">Close</a>
         </div>
      </div>  
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
         <?php if ($privilege==1)
				 echo '<li><a href="dashboard.php" >Dashboard</a></li>';
		 ?>
    	 <li><a href="patients.php" class="act_link">Patients</a></li>
         <li><a href="medicalConnections.php" >Doctor Connections</a></li>
		 <?php if ($privilege==1)
				 echo '<li><a href="PatientNetwork.php" >Patient Network</a></li>';
		 ?>
         <li><a href="medicalConfiguration.php">Configuration</a></li>
         <li><a href="logout.php" style="color:yellow;">Sign Out</a></li>
     </ul>

     
     </div>
     </div>
     <!--SpeedBar END-->
     
     
     <!--CONTENT MAIN START-->
	 <div class="content">
	      
	     <div class="grid" class="grid span4" style="width:1000px; margin: 0 auto; margin-top:30px; padding-top:30px;">

		 <span class="label label-info" style="left:0px; margin-left:30px; font-size:30px;">Patient Creator</span>
         <div style="margin:10px; margin-top:20px;" >     
			<table align="center" border="0" cellpadding="0" cellspacing="0">
			<tr><td> 
			<!-- Smart Wizard -->
        
			<div id="wizard" class="swMain">
				
				<ul>
					<li><a href="#step-1">
						<label class="stepNumber">1</label>
						<span class="stepDesc">
							Step 1<br />
							<small>Create/Select Patient</small>
						</span>
					</a></li>
					<li><a href="#step-2">
						<label class="stepNumber">2</label>
						<span class="stepDesc">
						Step 2<br />
						<small>Drop Files</small>
						</span>
					</a></li>
					<li><a href="#step-3">
						<label class="stepNumber">3</label>
						<span class="stepDesc">
						Step 3<br />
						<small>Verify Details</small>
						</span>                   
					</a></li>
  				</ul>
				
  			
				<div id="step-1" syyle="height:30px;">	
					<h2 class="StepTitle">Create/Select Patient</h2>
						<ul id="myTab" class="nav nav-tabs tabs-main">
							<li class="active" style="width:50%; "><a href="#sendR" data-toggle="tab">Existing Patient</a></li>
							<li style="width:50%; "><a href="#getR" data-toggle="tab">New Patient</a></li>
						</ul>
						
					<div id="myTabContent" class="tab-content tabs-main-content padding-null" >	
							<div class="tab-pane tab-overflow-main fade in active" id="sendR">
                	
								<!--<div class="grid" style="float:left; width:95%; height:300px; margin: 0 auto; margin-left:2%; margin-top:30px; margin-bottom:30px; overflow:scroll;">-->
								<div class="grid-title">
									<!--<div class="pull-left"><div class="fam-user" style="margin-right:10px;"></div>Search Patient</div>-->
									<div style="margin-right:10px;" class="ui-widget">Select Patient : <input id="tags" /></div>
									<img src="images/load/8.gif" alt="" style="margin-left:50px; margin-top:10px; display: none;" id="Wait1">

									<div class="pull-right"></div>	
									<div class="clear"></div>   
								</div>
                        
							</div>
						
							<div class="tab-pane" id="getR">
								<div style="height:300px; margin:10px; padding:10px;">
                
								<div class="grid" style="float:left; width:95%; height:300px; margin: 0 auto; margin-left:2%; margin-top:30px; margin-bottom:30px; overflow:scroll;">
								<div class="grid-title">
									<div class="pull-left"><div class="fam-user" style="margin-right:10px;"></div>Enter Patient Details</div>
           
										<img src="images/load/8.gif" alt="" style="margin-left:50px; margin-top:10px; display: none;" id="Wait1">

									<div class="pull-right"></div>
									<div class="clear"></div>   
								</div>
          
							    <!--Copied from SignIn-->
									<div class="clear"></div>
									<div style="margin-left:20px; margin-top:20px; color: #3d93e0; font-size:20px; font-weight:bold; width:100%; ">Demographics</div>
									<span style="margin-left:20px; color: #3d93e0; font-size:12px; width:100%;">Section 1</span>

									<br><br>
									<div id='LeftBoxDem' style=" width:50%; float:left; ">
									
									<input id="fname" name="fname" type="text" class="first-input" placeholder="Name" style="margin-left:20px; width:120px; border-bottom-right-radius:3px; border-bottom-left-radius:3px;" title="Please insert your name (one word only, no signs or special characters)"/>
									<input id="sname"  name="sname" type="text" class="first-input" placeholder="Surname" style="margin-left:10px; width:120px; border-bottom-right-radius:3px; border-bottom-left-radius:3px;" title="Please insert your surname (one word only, no signs or special characters, no middle initial)"/>
									<br>
									<br>
									<input id="Year" name="Year" type="text" class="first-input" placeholder="Year of Birth" style="margin-left:20px; width:100px; border-bottom-right-radius:3px; border-bottom-left-radius:3px;" title="Please insert your year of birth" />
									<input id="Month" name="Month" type="text" class="first-input" placeholder="Month" style="width:50px; border-bottom-right-radius:3px; border-bottom-left-radius:3px;" title="Please insert your month of birth"/>
									<input id="Day" name="Day" type="text" class="first-input" placeholder="Day" style="width:50px; border-bottom-right-radius:3px; border-bottom-left-radius:3px;" title="Please inser your day of birth" />
									
								
									</div>

									<div id='RightBoxDem' style=" width:40%; float:left; ">
									
									<!--<input id="frace" name="frace" type="text" class="first-input" placeholder="Race" style="margin-left:20px; width:120px; border-bottom-right-radius:3px; border-bottom-left-radius:3px;" title="Please insert your name (one word only, no signs or special characters)"/>-->
									<br>
									<br>
									<!--<input id="Gender" name="Gender" type="text" class="first-input" placeholder="Gender" style="margin-left:20px; width:100px; border-bottom-right-radius:3px; border-bottom-left-radius:3px;" title="Please enter your gender (Male/Female)" />-->
									<select id="Gender" name="Gender" placeholder="Select Gender" style="margin-left:20px; width:150px; border-bottom-right-radius:3px; border-bottom-left-radius:3px;" title="Please enter your gender (Male/Female)">
										<option value="">Select Gender:</option>
										<option value="1">Male</option>
										<option value="0">Female</option>
		                            </select>
																		</div>
									
									<div class="clear"></div>
									<br>
									<input type="button" class="btn btn-primary" value="Add Patient" style="margin-left:300px;" id="addPatient">
								
								<!--End Copy-->
          
								</div>
								</div>
               
							</div>
						</div>
						
						
					
					
				</div><!--End step 1-->
				<div id="step-2">
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
								<form action="upload_dropzone.php" method="post" class="dropzone" id="myAwesomeDropzone2" style="background:<?php echo $TipoColorGroup[3] ?>; height:30px; width:auto;  margin-top:0px; text-align:center;"><center style=" font-size:22px; opacity:1;"><i class="<?php echo $TipoIconGroup[3] ?> icon-2x" style="color:white;"></i>&nbsp;&nbsp;Doctors Notes</center></form>
							</div>
							</td>
			
							<td>
							<div id="dropzone" style="background: #F9F9F9; height:30px; width:290px;margin-top:0px">
								<center></center>
								<form action="upload_dropzone.php" method="post" class="dropzone" id="myAwesomeDropzone3" style="background:<?php echo $TipoColorGroup[2] ?>; height:30px; width:auto;  margin-top:0px; opacity:1; text-align:center;"><center style="color:white; font-size:22px;"><i class="<?php echo $TipoIconGroup[2] ?> icon-2x" style="color:white;"></i>&nbsp;&nbsp;Laboratory</center></form>
							</div>
							</td>
						</tr>
		
				
						<tr>
							<td>
							<div id="dropzone" style="background: #cacaca; height:30px; width:290px;">
								<center>Imaging</center>
								<form action="upload_dropzone.php" method="post" class="dropzone" id="myAwesomeDropzone4" style="background:<?php echo $TipoColorGroup[1] ?>;height:30px; width:auto; overflow:auto;margin-top:300px; text-align:center;"><center style="color:white; font-size:22px;"><i class="<?php echo $TipoIconGroup[1] ?> icon-2x" style="color:white;"></i>&nbsp;&nbsp;Imaging</center></form>
							</div>
							</td>
			
							<td>
							<div id="dropzone" style="background: #F9F9F9; height:30px; width:290px;">
								<center>Pat. Notes</center>
								<form action="upload_dropzone.php" method="post" class="dropzone" id="myAwesomeDropzone5" style="background:<?php echo $TipoColorGroup[8] ?>; height:30px; width:auto;  margin-top:300px"><center style="color:white; font-size:22px;"><i class="<?php echo $TipoIconGroup[8] ?> icon-2x" style="color:white;"></i>&nbsp;&nbsp;Pat. Notes</center></form>
							</div>
							</td>
			
							<td>
							<div id="dropzone" style="background: #F9F9F9; height:30px; width:290px;">
								<center>Pictures</center>
								<form action="upload_dropzone.php" method="post" class="dropzone" id="myAwesomeDropzone6" style="background:<?php echo $TipoColorGroup[7] ?>; height:30px; width:auto;  margin-top:300px"><center style="color:white; font-size:22px;"><i class="<?php echo $TipoIconGroup[7] ?> icon-2x" style="color:white;"></i>&nbsp;&nbsp;Pictures</center></form>
							</div>
							</td>
						</tr>
		
						<tr>
							<td>
							<div id="dropzone" style="background: #F9F9F9; height:30px; width:290px;">
								<center>SuperBill</center>
								<form action="upload_dropzone.php" method="post" class="dropzone" id="myAwesomeDropzone7" style="background:<?php echo $TipoColorGroup[9] ?>;height:30px; width:auto; overflow:auto;margin-top:600px"><center style="color:white; font-size:22px;"><i class="<?php echo $TipoIconGroup[9] ?> icon-2x" style="color:white;"></i>&nbsp;&nbsp;Superbill</center></form>
							</div>
							</td>
			
							<td>
							<div id="dropzone" style="background: #F9F9F9; height:30px; width:290px;">
								<center>Summary</center>
								<form action="upload_dropzone.php" method="post" class="dropzone" id="myAwesomeDropzone8" style="background:<?php echo $TipoColorGroup[6] ?>; height:30px; width:auto;  margin-top:600px"><center style="color:white; font-size:22px;"><i class="<?php echo $TipoIconGroup[6] ?> icon-2x" style="color:white;"></i>&nbsp;&nbsp;Summary</center></form>
							</div>
							</td>
			
							<td>
							<div id="dropzone" style="background: #F9F9F9; height:30px; width:290px;">
								<center>Other</center>
								<form action="upload_dropzone.php" method="post" class="dropzone" id="myAwesomeDropzone9" style="background:<?php echo $TipoColorGroup[4] ?>; height:30px; width:auto;  margin-top:600px"><center style="color:white; font-size:22px;"><i class="<?php echo $TipoIconGroup[4] ?> icon-2x" style="color:white;"></i>&nbsp;&nbsp;Other</center></form>
							</div>
							</td>
						</tr>
		
		
					</table>
					</center>
				</div>                      
				<div id="step-3">
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
		
	$(document).ready(function() {
		//$('#wizard').smartWizard();
		
		verified=false;
		document.getElementById("upload_count_label").innerHTML = upload_count + '/' + filecount;
		
		$('#wizard').smartWizard({transitionEffect:'slideleft',onLeaveStep:leaveAStepCallback,onFinish:onFinishCallback,onShowStep:showstepcallback,enableFinishButton:true});
		
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
			
			switch(parseInt(step_num))
			{
				case 1:	
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
			var url = 'update_date.php?idpin='+idpin+'&tipo='+type+'&fecha='+rdate;
			var resp = LanzaAjax(url);
		 
		}
		alert('All your changes have been saved');
		verified=true;
		window.location.replace("<?php echo $domain;?>/dashboard.php");
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
				
				/*this.on("success",function(file,resp){
					alert(resp);
				});*/
				
						
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
				/*
				this.on("success",function(file,resp){
					alert(resp);
				});
				*/
						
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
		
		$("#datepicker1" ).datepicker();
		$("#datepicker2" ).datepicker();
		
			
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
			
			var url = 'dropzone_create_patient.php?idcreator=<?php echo $_SESSION['MEDID'];?>&idusfixed='+idusfixed+'&idusfixedname='+idusfixedname+'&name='+fname+'&surname='+sname;
			var resp = LanzaAjax(url);
			if(resp == 'failure')
			{
				alert('Error Adding Patient');
				return;
			}
			else
			{
				alert('Patient created successfully. Please drop files for the patient');
				$('#patientid').val(resp);
				$('#patientname').val(idusfixedname);
				//alert($('#patientid').val());
			}
			
			
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
			
			//curr_file=0;
			last_press='next';
			for(var i=0;i<idpin_array.length;i++)
			{
				rep_date[idpin_array[i]]='';
			}
			
			
			$('#next').trigger('click');
			$('#patient_name').disabled=true;
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
			$('#idpin').val(idpin);
			$('#datepicker2').val(rep_date[idpin]);
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
			$('#idpin').val(idpin);
			$('#datepicker2').val(rep_date[idpin]);
			
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
		
		$('#datepicker2').change(function() {
				
			var idpin = $('#idpin').val();
			rep_date[idpin] = $('#datepicker2').val();
			//alert('set ' + idpin + '   ' +rep_date[idpin]);
		});
	 		
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