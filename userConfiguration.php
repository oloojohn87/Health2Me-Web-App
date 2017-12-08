<?php
session_start();
 require("environment_detail.php");
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];

$QueUsu= $_SESSION['MEDID'];
$Acceso = $_SESSION['Acceso'];
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

$QueQuery = "SELECT * FROM doctors where id=".$QueUsu;
$result = mysql_query($QueQuery);
//$result = mysql_query("SELECT * FROM usuarios where IdUsFIXEDNAME='$NombreEnt' and IdUsRESERV='$PasswordEnt'");

$count=mysql_num_rows($result);
$row = mysql_fetch_array($result);
$success ='NO';
if($count==1){
	$success ='SI';
	$USERID = $row['id'];

	$MedID = $row['id'];
	$NombreEnt= $row['IdMEDEmail'];
	$PasswordEnt = $row['IdMEDRESERV'];

	$QueGender = $row['Gender'];
	$QueOrden = $row['OrderOB'];
	$IdUsFIXED = $row['IdMEDFIXED'];
	
	$Year = substr ($IdUsFIXED,0,4);
	$Month = substr ($IdUsFIXED,4,2);
	$Day = substr ($IdUsFIXED,6,2);
	$QueDOB = $Day.'-'.$Month.'-'.$Year;
	
	$UserName = $row['Name'];
	$UserSurname = $row['Surname'];
	$UserPassword = $row['IdMEDRESERV'];
	$IdUsRESERV = $row['IdMEDRESERV'];
	$IdUsFIXEDNAME = $row['IdMEDFIXEDNAME'];
	
	$email = $row['IdMEDEmail'];
	$telefono = $row['phone'];
	
	$MedUserName = $row['Name'];
	$MedUserSurname = $row['Surname'];
		
//	$MedUserLogo = $row['ImageLogo'];
$QueEntrada = 1;  // 1= EDICIÓN , 2= CREACIÓN.
}
else
{
echo "MEDICAL USER NOT VALID. Incorrect credentials for login";
echo "<br>\n"; 	
echo "<h2><a href='".$domain."'>Return to Inmers HomePage</a></h2>";
die;
}

//BLOCKSLIFEPIN $result = mysql_query("SELECT * FROM blocks");
$result = mysql_query("SELECT * FROM lifepin");

?>
<!DOCTYPE html>
<html lang="en"><head>
    <meta charset="utf-8">
    <title>Inmers</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
    <link href="css/style.css" rel="stylesheet">
    <link href="css/bootstrap.css" rel="stylesheet">
    
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
    
	<!--<link rel="stylesheet" href="css/icon/font-awesome.css">-->
	<link rel="stylesheet" href="font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/bootstrap-responsive.css">



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

<!--  <body onload="CargaDatos(<?php echo $QueEntrada;?>);">-->
<script type="text/javascript" >
 	function CargaDatos(queEntrada)
	{
		if (queEntrada ==1)
		{
			//retf = $('#IdDoB').val();
			//alert (retf);
			$('#dp32').val($('#IdDoB').val());
			$('#gender').val($('#IdGender').val());
			$('#orderOB').val($('#IdOrden').val());
			
			$('#VIdUsFIXED').html($('#IdUsFIXED').val());

			$('#Vname').val($('#IdUserName').val());
			$('#Vsurname').val($('#IdUserSurname').val());
			$('#Vpassword').val($('#IdUserPassword').val());
			$('#password2').val($('#IdUserPassword').val());
			$('#VIdUsFIXEDNAME').html($('#IdUsFIXEDNAME').val());

			$('#Vemail').val($('#IdEmail').val());
			$('#Vphone').val($('#IdTelefono').val());

			$('#VIdUsFIXEDINSERT').html($('#IdUsFIXED').val());
			$('#VIdUsFIXEDNAMEINSERT').html($('#IdUsFIXEDNAME').val());

		}
	}  
</script>

 
  <body onload="CargaDatos(<?php echo $QueEntrada; ?>)" >


	<!--Header Start-->
	<div class="header" >
     	<input type="hidden" id="USERDID" Value="<?php echo $USERID; ?>">	
    	
    	<input type="hidden" id="IdDoB" Value="<?php echo $QueDOB; ?>">	
    	<input type="hidden" id="IdGender" Value="<?php echo $QueGender; ?>">	
    	<input type="hidden" id="IdOrden" Value="<?php echo $QueOrden; ?>">	
    	<input type="hidden" id="IdUsFIXED" Value="<?php echo $IdUsFIXED; ?>">	
    	
    	<input type="hidden" id="IdUserName" Value="<?php echo $UserName; ?>">	
    	<input type="hidden" id="IdUserSurname" Value="<?php echo $UserSurname; ?>">	
    	<input type="hidden" id="IdUserPassword" Value="<?php echo $UserPassword; ?>">	
    	<input type="hidden" id="IdUsFIXEDNAME" Value="<?php echo $IdUsFIXEDNAME; ?>">	
  
    	<input type="hidden" id="IdEmail" Value="<?php echo $email; ?>">	
    	<input type="hidden" id="IdTelefono" Value="<?php echo $telefono; ?>">	

    	<input type="hidden" id="IdMEDEmail" Value="<?php echo $MedUserEmail; ?>">	
    	<input type="hidden" id="IdMEDName" Value="<?php echo $MedUserName; ?>">	
    	<input type="hidden" id="IdMEDSurname" Value="<?php echo $MedUserSurname; ?>">	
    	<input type="hidden" id="IdMEDLogo" Value="<?php echo $MedUserLogo; ?>">	
    	<input type="hidden" id="IdMEDLogo" Value="<?php echo $MedUserLogo; ?>">	
		
		<input type="hidden" id="notify_id">	
  		
           <a href="index.html" class="logo"><h1>health2.me</h1></a>
           
           <div class="pull-right">
           
            
           <!--Button User Start-->
		   <div class="btn-group pull-right" >
           
            <a class="btn btn-profile dropdown-toggle" id="button-profile" data-toggle="dropdown" href="#">
              <span class="name-user"><strong>Welcome</strong>, <?php echo $MedUserName.' '.$MedUserSurname; ?></span> 
             <?php 
             $hash = md5( strtolower( trim( $email ) ) );
             $avat = 'identicon.php?size=29&hash='.$hash;
			?>	
              <span class="avatar" style="background-color:WHITE;"><img src="<?php echo $avat; ?>" alt="" ></span> 
              <span class="caret"></span>
            </a>
            <div class="dropdown-menu" id="prof_dropdown">
            <div class="item_m"><span class="caret"></span></div>
            <ul class="clear_ul" >
              <li><a href="dashboard.php"><i class="icon-globe"></i> Home</a></li>
              
              <li><a href="medicalCongiguration.php"><i class="icon-cog"></i> Settings</a></li>
              <li><a href="logout.php"><i class="icon-off"></i> Sign Out</a></li>
            </ul>
            </div>
          </div>
          <!--Button User END-->  
          
          </div>
    </div>
    <!--Header END-->

    
    <!--Sidebar Start --->
<!--	<div id="sidebar">  -->
    <!--
      <ul class="menu-sidebar">
       <li><a href=""><i class="icon-home"></i> <span>Home</span></a></li>
       <li><a href="UserAccount.php?Acceso=23432&USERID='<?php echo $USERID; ?>'&Nombre=<?php echo $IdUsFIXEDNAME;?>&Password=<?php echo $IdUsRESERV;?>"><i class="icon-group"></i> <span>User</span></a></li>
       <li><a href="form_validation.php?Acceso=23432&USERID='<?php echo $USERID; ?>'"><i class="icon-edit"></i> <span>Setup</span></a></li>
      </ul>
    --> 
    
<!--    </div> --->
	<!--    Sidebar END-->

    
    <!--Content Start-->
	<div id="content" style="padding-left:0px; background: #F9F9F9; ">
    
   	  <!--- VENTANA MODAL  ---> 
   	  <button id="BotonModal" data-target="#header-modal" data-toggle="modal" class="btn btn-warning" style="display: none;">Modal with Header</button>
   	  <div id="header-modal" class="modal hide" style="display: none;" aria-hidden="true">
         <div class="modal-header">
             <button class="close" type="button" data-dismiss="modal">×</button>
                  Establish Connection with User
         </div>
         <div class="modal-body">
             <p>Please confirm that you want to link with this user.</p>
         </div>
         <input type="hidden" id="queId">
         <div class="modal-footer">
	         <input type="button" class="btn btn-success" value="Confirm" id="ConfirmaLink">
             <a href="#" class="btn btn-primary" data-dismiss="modal" id="CloseModal">Close</a>
         </div>
      </div>  
	  <!--- VENTANA MODAL  ---> 
	 <!--SpeedBar Start--->
     <div class="speedbar">
     <div class="speedbar-content">
     <ul class="menu-speedbar">
		
    	 <li><a href="MainDashboard.php">Home</a></li>
		 <li><a href="dashboard.php" >Dashboard</a></li>
    	 <li><a href="patients.php"  >Patients</a></li>
		 <?php if ($privilege==1)
		 {
				 echo '<li><a href="medicalConnections.php" >Doctor Connections</a></li>';
				 echo '<li><a href="PatientNetwork.php" >Patient Network</a></li>';
		 }
		 ?>
         <li><a href="medicalConfiguration.php" class="act_link" >Configuration</a></li>
         <li><a href="logout.php" style="color:yellow;">Sign Out</a></li>
     </ul>

     
     </div>
     </div>
     <!--SpeedBar END-->
		  
     
          
     <!--CONTENT MAIN START-->
	<div id="content" style="padding-left:0px;padding-bottom:0px; margin-left:0px; background-image:none;">
        <div style="width:1000px; margin: 0 auto; margin-top:30px;">
			<ul id="myTab" class="nav nav-tabs tabs-main">
			 <li class="active"><a href="#acct_config" data-toggle="tab">Account Configuration</a></li>
            <li><a href="#notify_config" data-toggle="tab">Notifications Configuration</a></li>
			<li><a href="#scheduler_config" data-toggle="tab">Scheduler Configuration</a></li>
            <li><a href="#emr_config" data-toggle="tab">EMR Configuration</a></li>
        </ul>
	        <!--Form Validation Start-->
			<div id="myTabContent" class="tab-content tabs-main-content padding-null" style="padding-left:0px; background: #F9F9F9; height:1000px; ">
				<div class="tab-pane tab-overflow-main fade in active" id="acct_config" style="padding-left:30px">
			        <div class="grid" class="grid span4" style="width:700px; margin: 0 auto; margin-top:30px;">
						<div class="grid-content" style="padding-top:30px;">
				           	<span class="label label-info" style="left:0px; margin-left:10px; margin-top:40px; font-size:30px;">Account Configuration</span>
						    <div class="clearfix" style="margin-bottom:20px;"></div>
				            <form id="formID" class="formular" method="post" action="UpdateMEDUser.php">
                 
                 <input type="hidden" id="EnviaUserid" name="EnviaUserid" Value="<?php echo $USERID; ?>">	
                 <input type="hidden" id="EnviaModo" name="EnviaModo" Value="<?php echo $QueEntrada; ?>">	
                 <input type="hidden" id="EnviaTipoUsuario" name="EnviaTipoUsuario" Value="2">	
                 <input type="hidden" id="VIdUsFIXEDINSERT" name="VIdUsFIXED" Value="<?php echo $IdUsFIXED; ?>">	
                 <input type="hidden" id="VIdUsFIXEDNAMEINSERT" name="VIdUsFIXEDNAME" Value="<?php echo $IdUsFIXEDNAME; ?>">	

                 <div class="clearfix"></div>
                 </br>
                 <span style="font: bold 18px Arial, Helvetica, sans-serif; color: #3D93E0; cursor: auto; margin-left:20px; ">User Identification .- Fixed Part</span>
                 <span id ="VIdUsFIXED" class="label label-success" style="float:right; font-size:18px; padding:5px 10px 5px 10px; font-family: “Andale Mono”, AndaleMono, monospace;">00000000</span>

                 <div class="clearfix"></div>
                
            
                <div id="CLICK">
                 <div class="formRow">
	                 <label>Date of Birth: </label>
	                 <div class="formRight">
	                 <div class="validate[custom[date],past[2013/01/01]] input-append date" id="dp3" data-date="12-02-2012" data-date-format="dd-mm-yyyy">
		                 <input class="span2" size="16" type="text" value="12-02-2012" id="dp32" name="dp32" readonly="">
		                 <span class="add-on" onclick='return false;'><i class="icon-th"></i></span>
		             </div>
	                 </div>
                 </div>
                </div>
                 
                 <div class="formRow">
                        <label>Gender: </label>
                        <div class="formRight">
                            <select name="gender" id="gender" class="validate[required] span_select" style="width:200px;">
                                <option value="">Select Gender:</option>
                                <option value="0">Female</option> 
                                <option value="1">Male</option>
                            </select>
                        </div>
                 </div>
 
                  <div class="formRow">
                     
	                     <div style="margin-left:0px;">
		                     <p>
			                     <input type="checkbox" id="c2" name="cc" >
			                     <label for="c2"><span></span> Part of a multiple birth ? (twins, triplets, etc.)</label>
			                 </p>
	                     </div>
	                         <div class="clearfix"></div>
             
                        <div id="MULTIPLE" style="margin-left:0px; display:none;">
                   
                        <label>Order of Birth: </label>
                        <div class="formRight">
                            <select name="orderOB" id="orderOB" style="width:200px;">
                                <option value="">Select Order:</option>
                                <option value="1">First</option> 
                                <option value="2">Second</option>
                                <option value="3">Third</option>
                                <option value="4">Fourth</option>
                            </select>
                        </div>
                        </div>
                 </div>
 
                 <div class="clearfix"></div>
                 <hr>

                 <span style="font: bold 18px Arial, Helvetica, sans-serif; color: #3D93E0; cursor: auto; margin-left:20px; ">User Identification .- Flexible Part</span>
                 <span id ="VIdUsFIXEDNAME" class="label label-success" style="float:right; font-size:18px; padding:5px 10px 5px 10px; font-family: “Andale Mono”, AndaleMono, monospace;">name.surname</span>

                 
                 <div class="formRow">
                        <label>Name: </label>
                        <div class="formRight">
                           <input value="" class="validate[required] span" type="text" name="Vname" id="Vname" style="width:300px;" placeholder="Name">
                        </div>
                 </div>
                 
                 <div class="formRow">
                        <label>Surname: </label>
                        <div class="formRight">
                           <input value="" class="validate[required] span" type="text" name="Vsurname" id="Vsurname" style="width:300px;" placeholder="Surname">
                        </div>
                 </div>
                 
                 </br></br></br>
                        
                  <div class="formRow">
                        <label>Password: </label>
                        <div class="formRight">
                           <!--<input value="" class="validate[required,minSize[6],maxSize[6]] span" type="password" name="minsize" id="minsize">-->
                           <input value="" class="validate[required,minSize[8],maxSize[14]] span" type="password" name="Vpassword" id="Vpassword" style="width:200px;" placeholder="Password">
                        </div>   
                 </div>
                 <div class="formRow">
                        <label>Retype Password: </label>
                        <div class="formRight">
                        	<input value="" class="validate[required,equals[Vpassword]] span" type="password" name="password2" id="password2" style="width:200px;" placeholder="Same Password">
                        </div>
                 
                 <div class="clearfix"></div>
                 <hr>
 
                 <span style="font: bold 18px Arial, Helvetica, sans-serif; color: #3D93E0; cursor: auto; margin-left:20px; ">Connections</span>
                
                  <div class="formRow">
                        <label>Email: </label>
                        <div class="formRight">
                           <input value="" class="validate[required, custom[email]] span" type="email" name="Vemail" id="Vemail" style="width:300px;" placeholder="email">
                        </div>
                 </div>
                  <div class="formRow">
                        <label>Phone: </label>
                        <div class="formRight">
                           <input value="" type="text" name="Vphone" id="Vphone" style="width:300px;" placeholder="phone">
                        </div>
                 </div>

                 </br></br> </br>
                 <input type="submit" class="btn btn-large btn-primary" value="SAVE" style="width:200px;">
            
                </form>
                 <div class="clear"></div>
          </div>  
            </form>  
						</div>
					</div>
				<!--CONTENT MAIN END-->
				</div>
				<div class="tab-pane" id="notify_config" >
					<div class="grid" class="grid span4" style="width:700px; margin: 0 auto; margin-top:30px;">
						<div class="grid-content" style="padding-top:30px;">
							 <span class="label label-info" style="left:0px; margin-left:10px; margin-top:40px; font-size:30px; ">Notification Configuration</span>
						     <div class="clearfix" style="margin-bottom:20px;"></div>
							 <form id="formID" class="formular" method="post" action="">
                
				<div class="clearfix"></div>
                
            
          
 
                  <div class="formRow" style="margin-bottom:50px;">
                     
	                     <div style="margin-left:10px; padding-bottom:20px;">
		                     <p>
			                     <input type="checkbox" id="notify_link" name="cc" >
			                     <label for="notify_link"><span></span> Send Internal Notifications to external Email ID</label>
			                 </p>
	                     </div>
	                         <div class="clearfix"></div>
             
                        <!--<div id="MULTIPLE" style="margin-left:0px; display:block;">
                   
                        <label>Order of Birth: </label>
                        <div class="formRight">
                            <select name="orderOB" id="orderOB" style="width:200px;">
                                <option value="">Select Order:</option>
                                <option value="1">First</option> 
                                <option value="2">Second</option>
                                <option value="3">Third</option>
                                <option value="4">Fourth</option>
                            </select>
                        </div>
                        </div>-->
                 </div>
				 <div class="clearfix"></div>
				 <div class="clearfix"></div>
				 <div class="clearfix"></div>
				 <hr>
				  <input type="button" class="btn btn-large btn-primary" id="notif_update" value="Update" style="width:200px;">
				</form> 
						</div>	 
					</div>
				</div>
				<div class="tab-pane" id="scheduler_config">
							<div class="grid" class="grid span4" style="width:700px; margin: 0 auto; margin-top:30px;">
								<div class="grid-content" style="padding-top:30px;">
									 <span class="label label-info" style="left:0px; margin-left:10px; margin-top:40px; font-size:30px;">Scheduler Configuration</span>
								     <div class="clearfix" style="margin-bottom:20px;"></div>
								     <div class="clearfix"></div>
						             		<span style="font: bold 18px Arial, Helvetica, sans-serif; color: #3D93E0; cursor: auto; margin-left:20px; ">Visit Types : </span>
											<center>
											<div id="CLICK_EVENT"></div>
											</center>
											<div class="clearfix"></div>
											<br>
											<input type="button" class="btn btn-primary" value="Add more Visit Types" onClick="add_visit_types();" >
											<div class="clearfix"></div>
											<hr>
											<div style="margin-top:-15px;">
											Send Appointment Notification before &nbsp &nbsp <select id="appoint_notify" style="width:8em;margin-top:10px"> </select>&nbsp minutes
										 
											</div>
										 <!--<div class="clearfix"></div>-->
										     <hr>
											 <div style="margin-top:-15px;">
											Timezone &nbsp : &nbsp <select id="Timezone" style="width:15em;margin-top:10px"> </select>
										 
											</div>
											<hr>
										<!--<br><br>-->
										  
										 
										 <!--<br><br>-->
										 <!--<center>-->
											 <input type="button" class="btn btn-large btn-primary" onClick="saveSchedulerConfig();" value="UPDATE" style="width:200px;margin-top:20px;">
									     <!--</center>-->
										 
										<br>
								</div>		
							</div>
				 </div>
				 
				 <div class="tab-pane" id="emr_config">
							<div class="grid" class="grid span4" style="width:700px; margin: 0 auto; margin-top:30px;">
								<div class="grid-content" style="padding-top:30px;">
									 <span class="label label-info" style="left:0px; margin-left:10px; margin-top:40px; font-size:30px;">EMR Configuration</span>
								     <div class="clearfix" style="margin-bottom:20px;"></div>
								     <div class="clearfix"></div>
											<br>
						             		<input type="checkbox" id="emr_demographics" checked disabled="true"><label for="emr_demographics" style="font: bold 18px Arial, Helvetica, sans-serif; color: #3D93E0;margin-left:20px "><span></span>&nbsp&nbsp&nbspDemographics : </label>
											<br><br>
											<center>
											<table width="60%" >
												<tr bgcolor="#FFFFFF">
													<td width="50%" > <input type="checkbox" id="emr_name" checked disabled="true">	<label for="emr_name"><span></span>&nbsp&nbsp&nbspName</label> </td>
													<td width="50%" > <input type="checkbox" id="emr_address" >	<label for="emr_address"><span></span>&nbsp&nbsp&nbspAddress</label>  </td>
												<tr>
												<tr bgcolor="#FFFFFF">
													<td width="50%" > <input type="checkbox" id="emr_middle" checked disabled="true">	<label for="emr_middle"><span></span>&nbsp&nbsp&nbspMiddle Initial</label> </td>
													<td width="50%" > <input type="checkbox" id="emr_address2" >	<label for="emr_address2"><span></span>&nbsp&nbsp&nbspAddress2</label>  </td>
												<tr>
												<tr bgcolor="#FFFFFF">
													<td width="50%" > <input type="checkbox" id="emr_surname" checked disabled="true">	<label for="emr_surname"><span></span>&nbsp&nbsp&nbspSurname</label> </td>
													<td width="50%" > <input type="checkbox" id="emr_city" >	<label for="emr_city"><span></span>&nbsp&nbsp&nbspCity</label>  </td>
												<tr>
												<tr bgcolor="#FFFFFF">
													<td width="50%" > <input type="checkbox" id="emr_gender" checked disabled="true">	<label for="emr_gender"><span></span>&nbsp&nbsp&nbspGender</label> </td>
													<td width="50%" > <input type="checkbox" id="emr_state" >	<label for="emr_state"><span></span>&nbsp&nbsp&nbspState</label>  </td>
												<tr>
												<tr bgcolor="#FFFFFF">
													<td width="50%" > <input type="checkbox" id="emr_dob" checked disabled="true">	<label for="emr_dob"><span></span>&nbsp&nbsp&nbspDate of Birth</label> </td>
													<td width="50%" > <input type="checkbox" id="emr_country" >	<label for="emr_country"><span></span>&nbsp&nbsp&nbspCountry</label>  </td>
												<tr>
												<tr bgcolor="#FFFFFF">
													<td width="50%" > <input type="checkbox" id="emr_notes" >	<label for="emr_notes"><span></span>&nbsp&nbsp&nbspNotes</label> </td>
													<td width="50%" >   </td>
												<tr>
											</table>
											</center>
											
											<div class="clearfix"></div>
											<hr>
											
						             		<input type="checkbox" id="emr_personal" ><label for="emr_personal" style="font: bold 18px Arial, Helvetica, sans-serif; color: #3D93E0;margin-left:20px "><span></span>&nbsp&nbsp&nbspPersonal History : </label>
						
											<div id="PH_Tab" style="margin-left:0px; display:none;">
												<div class="formRow" >
													<input type="checkbox" id="emr_fractures" >	<label for="emr_fractures" style="margin-left:60px"><span></span>&nbsp&nbsp&nbspFractures and Other Traumas</label>
												</div>
												<div class="formRow" >
													<input type="checkbox" id="emr_surgeries" >	<label for="emr_surgeries" style="margin-left:60px"><span></span>&nbsp&nbsp&nbspSurgeries</label>
												</div>
												<div class="formRow" >
													<input type="checkbox" id="emr_otherknown" >	<label for="emr_otherknown" style="margin-left:60px"><span></span>&nbsp&nbsp&nbspOther Known Medical Events</label>
												</div>
												<div class="formRow" >
													<input type="checkbox" id="emr_obstetric" >	<label for="emr_obstetric" style="margin-left:60px"><span></span>&nbsp&nbsp&nbspObstetric History</label>
												</div>
												<div class="formRow" >
													<input type="checkbox" id="emr_othermed" >	<label for="emr_othermed" style="margin-left:60px"><span></span>&nbsp&nbsp&nbspOther Medical Data</label>
												</div>
											</div>
											
											<div class="clearfix"></div>
											<hr>
											<input type="checkbox" id="emr_family" ><label for="emr_family" style="font: bold 18px Arial, Helvetica, sans-serif; color: #3D93E0;margin-left:20px "><span></span>&nbsp&nbsp&nbspFamily History : </label>
											<div id="FA_Tab" style="margin-left:0px; display:none;">
												<div class="formRow" >
													<input type="checkbox" id="emr_father" >	<label for="emr_father" style="margin-left:60px"><span></span>&nbsp&nbsp&nbspFather</label>
												</div>
												<div class="formRow" >
													<input type="checkbox" id="emr_mother" >	<label for="emr_mother" style="margin-left:60px"><span></span>&nbsp&nbsp&nbspMother</label>
												</div>
												<div class="formRow" >
													<input type="checkbox" id="emr_siblings" >	<label for="emr_siblings" style="margin-left:60px"><span></span>&nbsp&nbsp&nbspSiblings</label>
												</div>
												
											</div>
											<div class="clearfix"></div>
											<hr>
											<input type="checkbox" id="emr_pastdx" ><label for="emr_pastdx" style="font: bold 18px Arial, Helvetica, sans-serif; color: #3D93E0;margin-left:20px "><span></span>&nbsp&nbsp&nbspPast Diagnostics  </label>
											
											<div class="clearfix"></div>
											<hr>
											<input type="checkbox" id="emr_medications" ><label for="emr_medications" style="font: bold 18px Arial, Helvetica, sans-serif; color: #3D93E0;margin-left:20px "><span></span>&nbsp&nbsp&nbspMedications  </label>
	
											<div class="clearfix"></div>
											<hr>
											<input type="checkbox" id="emr_immunizations" ><label for="emr_immunizations" style="font: bold 18px Arial, Helvetica, sans-serif; color: #3D93E0;margin-left:20px "><span></span>&nbsp&nbsp&nbspImmunizations  </label>
											
											<div class="clearfix"></div>
											<hr>
											<input type="checkbox" id="emr_allergies" ><label for="emr_allergies" style="font: bold 18px Arial, Helvetica, sans-serif; color: #3D93E0;margin-left:20px "><span></span>&nbsp&nbsp&nbspAllergies  </label>
											<hr>
											<input type="button" class="btn btn-large btn-primary" onClick="saveEMRConfig();" value="UPDATE" style="width:200px;margin-top:20px;">
											<!--<div style="margin-top:-15px;">
											Send Appointment Notification before &nbsp &nbsp <select id="appoint_notify" style="width:8em;margin-top:10px"> </select>&nbsp minutes
										 
											</div>
										 
										     <hr>
											 <div style="margin-top:-15px;">
											Timezone &nbsp : &nbsp <select id="Timezone" style="width:15em;margin-top:10px"> </select>
										 
											</div>
											<hr>
										
										  
										 
										
											 <input type="button" class="btn btn-large btn-primary" onClick="saveSchedulerConfig();" value="UPDATE" style="width:200px;margin-top:20px;">
									    -->
										 
										<br>
								</div>		
							</div>
				 </div>
			</div>
		</div>
	</div>



	
	</div>
	
	</div>
	
	</div>
    <!--Content END-->

    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="js/jquery.min.js"></script>
    <script src="js/jquery-ui.min.js"></script>
   
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
   <script src="realtime-notifications/lib/gritter/js/jquery.gritter.min.js"></script>
    <link href="realtime-notifications/lib/gritter/css/jquery.gritter.css"rel="stylesheet" type="text/css" />
	
	<link rel="stylesheet" media="screen" type="text/css" href="css/colorpicker2.css" />
	<script type="text/javascript" src="js/colorpicker.js"></script>
	<script type="text/javascript" src="jscolor/jscolor.js"></script>

    <script type="text/javascript" >
	//var timeoutTime = 18000000;
	var timeoutTime = 300000;  //5minutes
	var timeoutTimer = setTimeout(ShowTimeOutWarning, timeoutTime);
	var predefined_events = 0;
	var deleted_elements = new Array();

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
	
	function get_user_event_config(serviceURL) 
	{
		$.ajax(
		{
			url: serviceURL,
			dataType: "json",
			async: false,
			
			success: function(data)
			{
				event_config = data.items;
						
			}
		});
	}
	
	function get_user_timezone(serviceURL) 
	{
		$.ajax(
		{
			url: serviceURL,
			dataType: "json",
			async: false,
			
			success: function(data)
			{
				user_timezone = data.items;
						
			}
		});
	}
	
	function get_timezones(serviceURL) 
	{
		$.ajax(
		{
			url: serviceURL,
			dataType: "json",
			async: false,
			
			success: function(data)
			{
				timezones = data.items;
						
			}
		});
	}
	function get_user_notify_time(serviceURL) 
	{
		$.ajax(
		{
			url: serviceURL,
			dataType: "json",
			async: false,
			success: function(data)
			{
				notify_time = data.items;
						
			}
		});
	}
	
	function get_emr_config(serviceURL) 
	{
		$.ajax(
		{
			url: serviceURL,
			dataType: "json",
			async: false,
			success: function(data)
			{
				emr_config = data.items;
						
			}
		});
	}
    
    $(document).ready(function() {
   
		get_user_event_config('getusereventconfig.php');
		//predefined_events = event_config.length;
		for(var i=0;i<event_config.length;i++)
		{
			add_event_config(i,event_config[i].title,event_config[i].hours,event_config[i].minutes,event_config[i].colour)
		}
		
		
		
		get_user_notify_time('getusernotifytime.php');
		var appoint_notify = document.getElementById('appoint_notify');
		for(var j=1;j<61;j++)
		{
			appoint_notify.options[appoint_notify.options.length]= new Option(j, j);
		}
		appoint_notify.options[parseInt(notify_time[0].minutes)-1].setAttribute('selected',true);
		$('#notify_id').val(notify_time[0].id);
		
		get_timezones('gettimezones.php');
		var tz = document.getElementById('Timezone');
		for(var i=0;i<timezones.length;i++)
		{
			tz.options[tz.options.length] = new Option(timezones[i].timezones,timezones[i].id);
		}
		get_user_timezone('getusertimezone.php');
		tz.options[parseInt(user_timezone[0].timez)-1].setAttribute('selected',true);
		//$('#Timezone :selected').text(user_timezone[0].timezones);
   
		get_emr_config('getemrconfig.php');
		set_checkbox_value('emr_personal',emr_config[0].personal);
		set_checkbox_value('emr_family',emr_config[0].family);
		set_checkbox_value('emr_pastdx',emr_config[0].pastdx);
		set_checkbox_value('emr_medications',emr_config[0].medications);
		set_checkbox_value('emr_immunizations',emr_config[0].immunizations);
		set_checkbox_value('emr_allergies',emr_config[0].allergies);
   
		set_checkbox_value('emr_address',emr_config[0].address);
		set_checkbox_value('emr_address2',emr_config[0].address2);
		set_checkbox_value('emr_city',emr_config[0].city);
		set_checkbox_value('emr_state',emr_config[0].state);
		set_checkbox_value('emr_country',emr_config[0].country);
		set_checkbox_value('emr_notes',emr_config[0].notes);
		set_checkbox_value('emr_fractures',emr_config[0].fractures);
		set_checkbox_value('emr_surgeries',emr_config[0].surgeries);
		set_checkbox_value('emr_otherknown',emr_config[0].otherknown);
		set_checkbox_value('emr_obstetric',emr_config[0].obstetric);
		set_checkbox_value('emr_othermed',emr_config[0].othermed);
		
		set_checkbox_value('emr_father',emr_config[0].father);
		set_checkbox_value('emr_mother',emr_config[0].mother);
		set_checkbox_value('emr_siblings',emr_config[0].siblings);
   
	$('body').bind('mousedown keydown', function(event) {
        clearTimeout(timeoutTimer);
        timeoutTimer = setTimeout(ShowTimeOutWarning, timeoutTime);
    });
   		
   	$('#dp32').live("change", function(){
	     getIdUsFIXED();
	     alert ('Change');
   		});	
 	
   		
    $('#gender').bind("focusout change", function(){
	     getIdUsFIXED();  		
   		});
 
     $('#orderOB').bind("focusout  change", function(){
	     getIdUsFIXED();
   		});
   	     
   	$('#Vname').bind("focusout  change", function(){
	     getIdUsFIXEDNAME();
   		});	

   	$('#Vsurname').bind("focusout  change", function(){
	     getIdUsFIXEDNAME();
   		});	
   		  		  
    $("#c2").click(function(event) {
	    	$("#MULTIPLE").css("display","inherit");
	    	var cosa=chkb($("#c2").is(':checked'));
	    	if (cosa==1){
		    	$("#MULTIPLE").css("display","inherit");
	    	}else{
		    	$("#MULTIPLE").css("display","none");
		    	$("#orderOB").val('0');
	    	}
  	  	     getIdUsFIXED();
			 
			 
  
    });
	
	$("#emr_personal").click(function(event) {
	    	$("#PH_Tab").css("display","inherit");
	    	var cosa=chkb($("#emr_personal").is(':checked'));
	    	if (cosa==1){
		    	$("#PH_Tab").css("display","inherit");
	    	}else{
		    	$("#PH_Tab").css("display","none");
		    
	    	}
  	  	      
    });
	
	$("#emr_family").click(function(event) {
			//alert('Here');
	    	$("#FA_Tab").css("display","inherit");
	    	var cosa=chkb($("#emr_family").is(':checked'));
	    	if (cosa==1){
		    	$("#FA_Tab").css("display","inherit");
	    	}else{
		    	$("#FA_Tab").css("display","none");
		    
	    	}
  	  	      
    });
	
	//Added for getting the notification state and updating it
	 setTimeout(function(){
	 var cadena = '<?php echo $domain;?>/linkNotifications.php?idDoc=<?php echo $_SESSION['MEDID']?>&state=0&value=0';
	 var state=LanzaAjax(cadena);
	 //alert('state'+state);
	 if(parseInt(state))
	 $("#notify_link").prop('checked', true);
	 else
	 $("#notify_link").prop('checked', false);
	 },500);
	
	
	/*$("#notify_link").click(function(event) {
		
		var chkval=chkb($("#notify_link").is(':checked'));
		/*if(chkval==1){
			alert("Linked");
		}else {
			alert("Delinked");
		}*/
		
		 //alert(chkval);
		 //updating the link state in the database state signfy an update to the database;
		/* var cadena = '<?php echo $domain;?>/linkNotifications.php?idDoc=<?php echo $_SESSION['MEDID']?>&state=1&value='+chkval;
		 LanzaAjax(cadena);
		 displayalertnotification('Status Updated');
		
	});*/
	
	$("#notif_update").click(function() {
	
		var chkval=chkb($("#notify_link").is(':checked'));
		/*if(chkval==1){
			alert("Linked");
		}else {
			alert("Delinked");
		}*/
		
		 //alert(chkval);
		 //updating the link state in the database state signfy an update to the database;
		 var cadena = '<?php echo $domain;?>/linkNotifications.php?idDoc=<?php echo $_SESSION['MEDID']?>&state=1&value='+chkval;
		 LanzaAjax(cadena);
		 displayalertnotification('Status Updated');
	
	
	
	
	});
 
    function getIdUsFIXED(){
    	var fnac = $("#dp32").val();
   		var fnacnum = fnac.substr(6,4)+fnac.substr(3,2)+fnac.substr(0,2);
   		var gender = chkb($("#c2").is(':checked'));
   		
   		var gender = $("#gender").val();
   		var orderOB = $("#orderOB").val();
   		if (gender==0){ gender='0';}
   		if (orderOB==0){ orderOB='0';}
   		
   		var VIdUsFIXED = fnacnum+gender+orderOB;
   		$('#VIdUsFIXED').html(VIdUsFIXED);
   		$('#VIdUsFIXEDINSERT').val(VIdUsFIXED);
   		
    	}
 
     function getIdUsFIXEDNAME(){
    	var vname = $("#Vname").val().toLowerCase().replace(".","").replace(" ","");
   		var vsurname = $("#Vsurname").val().toLowerCase().replace(".","").replace(" ","");
    		
   		var VIdUsFIXEDNAME = vname+'.'+vsurname;
   		$('#VIdUsFIXEDNAME').html(VIdUsFIXEDNAME);
   		$('#VIdUsFIXEDNAMEINSERT').val(VIdUsFIXEDNAME);
    	}

    function chkb(bool){
	    if(bool)
	    	return 1;
	    	return 0;
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

	 function displayalertnotification(message){
	 
	  var gritterOptions = {
				   title: 'status',
				   text: message,
				   image:'images/Icono_H2M.png',
				   sticky: false,
				   time: '3000'
				  };
		$.gritter.add(gritterOptions);
	   
	  } 
	  
	  function set_checkbox_value(element_name,value)
	  {
		var element = document.getElementById(element_name);
		//alert('Setting to checked ' + element_name + '  ' + value);
		if(value==1)
		{
			element.checked='checked';
			if(element_name == 'emr_family')
			{
					$("#FA_Tab").css("display","inherit");
					var cosa=chkb($("#emr_family").is(':checked'));
					if (cosa==1){
					$("#FA_Tab").css("display","inherit");
					}else{
						$("#FA_Tab").css("display","none");
		    
					}
			}
			else if(element_name == 'emr_personal')
			{
				$("#PH_Tab").css("display","inherit");
				var cosa=chkb($("#emr_personal").is(':checked'));
				if (cosa==1){
					$("#PH_Tab").css("display","inherit");
				}else{
					$("#PH_Tab").css("display","none");
				
				}
				}

			}
	
	  }
 
 
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

		
	function saveEMRConfig()
	{
		var demographics_checked = 1;   //Demographics has to be 1 by default
			var name_checked = 1;
			var middle_checked = 1;
			var surname_checked = 1;
			var gender_checked = 1;
			var dob_checked = 1;
			var address_checked = get_checkbox_value('emr_address');
			var address2_checked = get_checkbox_value('emr_address2');
			var city_checked = get_checkbox_value('emr_city');
			var state_checked = get_checkbox_value('emr_state');
			var country_checked = get_checkbox_value('emr_country');
			var notes_checked = get_checkbox_value('emr_notes');
			
		var personal_checked = get_checkbox_value('emr_personal');
			var fractures_checked = 0;
			var surgeries_checked = 0;
			var otherknown_checked = 0;
			var obstetric_checked = 0;
			var othermed_checked = 0;
			if(personal_checked)
			{
				fractures_checked = get_checkbox_value('emr_fractures');
				surgeries_checked = get_checkbox_value('emr_surgeries');
				otherknown_checked = get_checkbox_value('emr_otherknown');
				obstetric_checked = get_checkbox_value('emr_obstetric');
				othermed_checked = get_checkbox_value('emr_othermed');
			}
		var family_checked = get_checkbox_value('emr_family');
			var father_checked = 0;
			var mother_checked = 0;
			var siblings_checked = 0;
			if(family_checked)
			{
				father_checked = get_checkbox_value('emr_father');
				mother_checked = get_checkbox_value('emr_mother');
				siblings_checked = get_checkbox_value('emr_siblings');
			}
			
		var pastdx_checked = get_checkbox_value('emr_pastdx');
		var medications_checked = get_checkbox_value('emr_medications');
		var immunizations_checked = get_checkbox_value('emr_immunizations');
		var allergies_checked = get_checkbox_value('emr_allergies');
		
		var url='save_emr_config.php?ph='+personal_checked+'&fh='+family_checked+'&pastdx='+pastdx_checked+'&medications='+medications_checked+'&immunizations='+immunizations_checked+'&allergies='+allergies_checked+'&address='+address_checked+'&address2='+address2_checked+'&city='+city_checked+'&state='+state_checked+'&country='+country_checked+'&notes='+notes_checked+'&fractures='+fractures_checked+'&surgeries='+surgeries_checked+'&otherknown='+otherknown_checked+'&obstetric='+obstetric_checked+'&othermed='+othermed_checked+'&father='+father_checked+'&mother='+mother_checked+'&siblings='+siblings_checked;
		var RecTipo = LanzaAjax(url);
		alert(RecTipo);
	}	
	
	function get_checkbox_value(element_name)
	{
		var element = document.getElementById(element_name);
		var element_checked = 0;
		if(element.checked)
		{
			element_checked=1;
		}
		return element_checked;
	}

	function saveSchedulerConfig()
	{	
		var serviceURL = 'clear_scheduler_data.php';
		var RecTipo = LanzaAjax(serviceURL);

		
		for(var j=0;j<predefined_events;j++)
		{
		
			if(deleted_elements.indexOf(j)==-1)
			{
				var title = $('#event'+j).val();
				var hours = $('#hours'+j).val();
				var minutes = $('#minutes'+j).val();
				var colour = $('#colour'+j).val();
				//alert(title + '  ' + hours + '  ' + minutes);
			
			
				var url = 'create_type_config_entry.php?title='+title+'&hours='+hours+'&minutes='+minutes+' &colour='+colour;
				//alert(url);
				var RecTipo = LanzaAjax(url);
				//alert(RecTipo);
			}
		}
		
		var url = 'save_notify_time.php?id='+ $('#notify_id').val() +'&minutes='+ $('#appoint_notify').val();
		//alert(url);
		RecTipo = LanzaAjax(url);
		
		
		
		var timezone_url = 'save_user_timezone.php?userid=<?php echo $_SESSION['MEDID'];?> &timez=' + $('#Timezone').val();
		//alert(timezone_url);
		Rectipo = LanzaAjax(timezone_url);
		
		window.location.replace("<?php echo $domain;?>/scheduler-n.php?curr_source=<?php echo $_SESSION['MEDID'];?>");
		
	}
	
	
	function add_visit_types()
		{
			add_event_config(predefined_events,'',0,0,'ffffff');
			//predefined_events++;
		}
		
		function add_event_config(i,title,hours,minutes,colour)
		{
			var grandfather = document.getElementById("CLICK_EVENT");
			//alert('Found GrandFather '+grandfather);
			
			var father = document.createElement("div");
				father.setAttribute("class","formRow");
				father.setAttribute("id","row"+i);	
					
					father.innerHTML = "Type : ";
					var title_child = document.createElement("input");
					title_child.setAttribute("type","text");
					title_child.setAttribute("id","event"+i);
					title_child.setAttribute("name","event"+i);
					title_child.setAttribute("value",title);
					title_child.style.width = '8em'
					father.appendChild(title_child);
				
					father.innerHTML += "  Hours : ";
					var hours_child = document.createElement("select");
					hours_child.setAttribute("id","hours"+i);
					hours_child.setAttribute("name","hours"+i);
					hours_child.style.width = '8em'
					for(var j=0;j<25;j++)
					{
						hours_child.options[hours_child.options.length]= new Option(j, j);
					}
					hours_child.options[parseInt(hours)].setAttribute('selected',true)
					
					father.appendChild(hours_child);
					
					father.innerHTML += "  Minutes : ";
					var minutes_child = document.createElement("select");
					minutes_child.setAttribute("id","minutes"+i);
					minutes_child.setAttribute("name","minutes"+i);
					minutes_child.style.width = '8em'
					for(var j=0;j<61;j++)
					{
						minutes_child.options[minutes_child.options.length]= new Option(j, j);
					}
					minutes_child.options[parseInt(minutes)].setAttribute('selected',true)
					father.appendChild(minutes_child);
					
					father.innerHTML += "  Pick Colour: ";
			/*		var color_child = document.createElement("select");
					color_child.class = "color";
					color_child.value = "ffffff";
					father.appendChild(color_child);*/
					
					var input = document.createElement('INPUT');
					input.style.width = '5em';
					input.setAttribute("id","colour"+i);
					input.setAttribute("name","colour"+i);
					// bind jscolor
					var col = new jscolor.color(input);
					//col.fromHSV(6/event_config.length*i, 1, 1);
					col.fromString(colour);
					father.appendChild(input);
					
					var button = document.createElement('input');
					button.setAttribute('type','image');
					button.setAttribute('id','but_'+i);
					button.setAttribute('src','images/del_button.jpg');
					button.style.width='20px';
					button.style.height='20px';
					button.onclick = function () {
						//alert('You pressed '+this.id);
						var n = this.id.split('_');
						//alert('delete' + n[1]);
						var row_to_del = 'row'+n[1];
						var element = document.getElementById(row_to_del);
						element.parentNode.removeChild(element);
						deleted_elements.push(n[1]);	
						//arrayToModify[arrayToModify.length] = this.id;
					};
					
					father.appendChild(button);
			grandfather.appendChild(father);
			predefined_events++;
		
		}
    </script>

  </body>
</html>

