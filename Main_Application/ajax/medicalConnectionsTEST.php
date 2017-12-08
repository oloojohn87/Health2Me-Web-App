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

//BLOCKSLIFEPIN $result = mysql_query("SELECT * FROM blocks");
$result = mysql_query("SELECT * FROM lifepin");

?>
<!DOCTYPE html>
<html lang="en" style="background: #F9F9F9;"><head>
    <meta charset="utf-8">
    <title>Inmers - Center Management Console</title>
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
  <body style="background: #F9F9F9;" onload="$('#BotonBusquedaSents').trigger('click');">

     	<input type="hidden" id="MEDID" Value="<?php echo $MedID; ?>">	
    	<input type="hidden" id="IdMEDEmail" Value="<?php echo $MedUserEmail; ?>">	
    	<input type="hidden" id="IdMEDName" Value="<?php echo $MedUserName; ?>">	
    	<input type="hidden" id="IdMEDSurname" Value="<?php echo $MedUserSurname; ?>">	
    	<input type="hidden" id="IdMEDLogo" Value="<?php echo $MedUserLogo; ?>">	
     	<input type="hidden" id="USERDID" Value="<?php echo $USERID; ?>">	

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
   	 <!--<div id="header-modal" class="modal hide" style="display: none; width: 80%; /* desired relative width */left: 10%; /* (100%-width)/2 */ /* place center *//*KYLE margin-left:auto; margin-right:auto; " aria-hidden="true">-->
   	 <div id="header-modal" class="modal hide" style="display: none; width: 800px; left: 20%; margin-left:auto; margin-right:auto;" aria-hidden="true">
         <div class="modal-header" style="height:60px;">
             <button class="close" type="button" data-dismiss="modal">×</button>
             <div style="width:90%; margin-top:12px; float:left;">
                 <div id="selpat" style="width: 100px; margin-left:5%; float: left; color: rgb(61, 147, 224);">
                     <div style="font-size: 20px; font-weight: bold; width: 100%;">Step 1</div>
                     <span style="font-size: 12px; width: 100%;">Select Patient</span>
                 </div>
                 <div id="seldr" style="width:100px; margin-left:5%; float:left;">
                     <div style="font-size:20px; font-weight:bold; width:100%; ">Step 2</div>
                     <span style="font-size:12px; width:100%;">Select Doctor</span>
                 </div>
                 <div id="att" style="width:100px; margin-left:5%; float:left;">
                     <div style="font-size:20px; font-weight:bold; width:100%; ">Step 3</div>
                     <span style="font-size:12px; width:100%;">Attach Reports</span>
                 </div>
                 <div id="addcom" style="width:100px; margin-left:5%; float:left;">
                     <div style="font-size:20px; font-weight:bold; width:100%;">Step 4</div>
                     <span style="font-size:12px; width:100%;">Add Comments</span>
                 </div>
                 <i id="attachment_icon" class="icon-paper-clip icon-4x" style="color:#ccc; margin-left:10px;font-size:30px"></i>
             </div>
         </div>
         <div class="modal-body" style="height:300px;">
             <div id="ContentScroller" style="width:100%; height:280px; overflow: hidden; ">
                 <div id="ScrollerContainer" style="width:2000px; height:275px; ">
                        <div id="content_selpat" style="float:left; width:400px; height:270px; ">
                           <p>
                           	<!-- PATIENT SELECTION TABLE --->
							<div class="grid" style="float:left; width:90%; height:265px; margin: 0 auto; margin-left:2%; margin-top:0px; margin-bottom:0px; overflow:scroll;">
                                <div class="grid-title">
									<div class="pull-left"><div class="fam-user" style="margin-right:10px;"></div>Select a Patient</div>
										<img src="images/load/8.gif" alt="" style="margin-left:50px; margin-top:10px; display: none;" id="Wait1">
									<div class="pull-right"></div>
									<div class="clear"></div>   
								</div>
								<div class="search-bar">
									<input type="text" class="span" name="" placeholder="Search Patient" style="width:150px;" id="SearchUserT"> 
									<input type="button" class="btn btn-primary" value="Filter" style="margin-left:50px;" id="BotonBusquedaPacCOMP">
								</div>
								<div class="grid" style="margin-top:0px;">
									<table class="table table-mod" id="TablaPac" style="width:100%; table-layout: fixed; ">
									</table> 
								</div>
						   </div>
							<!-- PATIENT SELECTION TABLE --->
                           </p>
                        </div>    
                        <div id="content_seldr" style="float:left; width:400px; height:370px; ">
                           <p>
                            
                            <!-- DOCTOR SELECTION TABLE --->
							<div class="grid" style="float:left; width:90%; height:265px; margin: 0 auto; margin-left:2%; margin-top:0px; margin-bottom:0px; overflow:scroll;">

								<div class="grid-title">
									<div class="pull-left"><div class="fam-user-delete" style="margin-right:10px;"></div>If Doctor is not H2M User:</div>
										<input type="text" class="span" name="" placeholder="Doctor's email" style="margin:5px; margin-left:10px; width:32%;" id="DoctorEmail"> 
										<input type="button" class="btn btn-primary" value="Add" style="margin-top:7px; margin-left:7px;" id="AddNonUser">
										
									<div class="pull-right"></div>
									<div class="clear"></div>   
								</div>

								<div class="grid-title">
									<div class="pull-left"><div class="fam-user-delete" style="margin-right:10px;"></div>Select a Doctor</div>
           
										<img src="images/load/8.gif" alt="" style="margin-left:50px; margin-top:10px; display: none;" id="Wait1">

									<div class="pull-right"></div>
									<div class="clear"></div>   
								</div>
          
								<div class="search-bar">
          
									<input type="text" class="span" name="" placeholder="Search Doctor" style="width:150px;" id="SearchDoctor"> 
									<input type="button" class="btn btn-primary" value="Filter" style="margin-left:50px;" id="BotonBusquedaMedCOMP">
                   
								</div>
          
								<div class="grid" style="margin-top:0px;">
									<table class="table table-mod" id="TablaMed" style="width:100%; table-layout: fixed; ">
									</table> 
  
								</div>
          
						   </div>
							<!-- DOCTOR SELECTION TABLE --->
   
                               
                           </p>
                        </div>
                        <div id="content_att" style="float:left; width:370px; height:50px; ">
                           <p>
                               <div id="Phase3Container" style="width:100%; height:290px; overflow: auto; margin-top:-20px;">
                                    <div id="ReportStream" style="">
                                    </div>
                               </div>       
                               <p id="NumberRA" style="color:#22aeff; font-size:16px; text-align:center; margin:0 auto; height:0px;">
                               </p>
                           </p>
                        </div>
                        <div id="content_addcom" style="float:left; width:370px; height:150px; padding:30px;">
                           <div style="height:60px; width:350px;">
                                <p style="float:left;">
                                        <input type="checkbox" id="c2" name="cc" style="width:140px;">
                                        <label for="c2" >
                                            <span></span>
                                        </label>
                                </p>
                                <span style="float:left; margin-left:20px; font-size:14px; color:#22aeff;"> Urgent (will call cell phone)</span>
                           </div>
                           <div style="height:220px; width:500px;">
                                <p>
                                    <span style="margin-left:20px; font-size:14px; color:#22aeff;"> Welcoming Message:</span>
                                    <textarea id="WelMes" type="textarea" style="width:450px; height:60px; margin-left:20px; font-size:14px; color:#54bc00;"></textarea>
                                </p>
                            </div>
                        </div>
                  </div>
             </div>
         </div>
         <input type="hidden" id="queId">
         <div class="modal-footer" style="height:120px;">
	         <div style="height:80px; width:100%:">
                    <p id="TextoSend" style="text-align:center; margin-top:0px; ">
                        <span style="color:grey;">Send </span>
                        <span style="color:#54bc00; font-size:30px;">      </span>
                        <span style="color:grey;"> to </span>
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
	  <!--- VENTANA MODAL  ---> 
	 <!--SpeedBar Start--->
     <div class="speedbar">
     <div class="speedbar-content">
     <ul class="menu-speedbar">
		
    	 <li><a href="MainDashboard.php">Home</a></li>
		 <li><a href="dashboard.php" >Dashboard</a></li>
    	 <li><a href="patients.php" >Patients</a></li>
		 <?php if ($privilege==1)
		 {
				 echo '<li><a href="medicalConnections.php"  class="act_link">Doctor Connections</a></li>';
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
	      
	     <div class="grid" class="grid span4" style="width:1000px; margin: 0 auto; margin-top:30px; padding-top:30px;">

		 <span class="label label-info" style="left:0px; margin-left:30px; font-size:30px;">Doctor Connections</span>
       	 <input type="button" class="btn btn-primary" value="Clear" style="margin-left:50px;" id="BotonClear">
		 <input type="button" class="btn btn-primary" value="Redraw" style="margin-left:50px;" id="BotonRedraw">
		 <input type="button" class="btn btn-primary" value="Neww" style="margin-left:50px;" id="BotonNew">
		 <input type="text"  value="0" style="margin-left:50px; width:40px;" id="ValIni">
		 <input type="text"  value="10" style="margin-left:10px; width:40px;" id="ValFin">

       		  <div style="margin:15px; margin-top:5px;">
		  <?php
		  // Sección para construir la información estadística del Médico (Dashboard: relativo a "packets")  
		  $r=0;
		  $EstadCanal = array(0,0,0,0,0,0,0);
		  $EstadCanalValid = array(0,0,0,0,0,0,0);
		  $EstadCanalNOValid = array(0,0,0,0,0,0,0);
		  $ValidationStatus = array(0,0,0,0,0,0,0,0,0,0);
		 	
		  $r=0;
		  while ($r<6)
		  {
		  $EstadCanal[$r]= 1;
		  $EstadCanalValid[$r]= 1;
		  $EstadCanalNOValid[$r]= 1;
		  $ValidationStatus[$r]= 1;
		  $r++;
		  }	
		  	  
		  $resultDOC = mysql_query("SELECT * FROM lifepin WHERE IdMed='$MedID' AND IdCreator='$MedID'");
		  $countDOC2=mysql_num_rows($resultDOC);
		  
		  $PacketsVistos = 0;
		  
		  //$UsersAccess = array();
		  $UsersAccess = new SplFixedArray(1000);
//		  $UsersAccess->setSize(1000);
		  $MedsAccess = new SplFixedArray(1000);
//		  $MedsAccess->setSize(1000);
 		  $indice=0;
 		  $indiceM=0;
 		  
		  while ($rowDOC = mysql_fetch_array($resultDOC))
		  {
		  	 $quePIN = $rowDOC['IdPin'];
		  	 $resultVIEW = mysql_query("SELECT * FROM bpinview WHERE IDPIN='$quePIN' AND VIEWIdMed!='$MedID' ");
		  	 $countVIEW=mysql_num_rows($resultVIEW);
		  	
		  	 if ($countVIEW>0) $PacketsVistos++;
 		   
		  	 while ($rowPIN = mysql_fetch_array($resultVIEW))
		  	 {
			  	 $UserView = $rowPIN['VIEWIdUser'];
			  	 $MedView = $rowPIN['VIEWIdMed'];
			  	 $nn=0;
			  	 $encontrado =0;
			  	 $encontradoM =0;
			  	 while ($nn<=$indice) {
			  	 	if ($UsersAccess[$nn]==$UserView) $encontrado=1;  
			  	 	if ($UsersAccess[$nn]==$MedView) $encontradoM=1;  
				  	$nn++; }
			  	 if ($encontrado==0 && $UserView!=0) {$UsersAccess[$indice] = $UserView;    $indice++;}
			  	 if ($encontradoM==0 && $MedView!=0) {$MedsAccess[$indice] = $MedView;    $indiceM++;}
		  	 }
		  	 /*
		  	 echo "<br>\n"; 
		  	 echo "PIN = ".$quePIN." --- Usuarios= ".$indice;
		  	 echo "<br>\n"; 
		  	 echo "<br>\n"; 
		  	 echo "PIN = ".$quePIN." --- Medicos= ".$indiceM;
		  	 echo "<br>\n"; 
		  	 */
		  }
		  /*
		  echo "<br>\n"; 
		  echo "PINES = ".$countDOC2." --- VIEWS= ".$PacketsVistos;
		  echo "<br>\n"; 
		  */
		  		  
		  // Variante para calcular de forma más ajustada el porcentaje de packetes sobre el total de los paquetes de los pacientes que han sido vistos por otros (EXPORTADOS) ((FORMA LARGA)
 
		  // Sección para construir la información estadística del Médico (Dashboard: relativo a "packets")  
		  /*KYLE
		  $hash = md5( strtolower( trim( $MedUserEmail ) ) );
		  $avat = 'identicon.php?size=75&hash='.$hash;
			?>	

	  	    		<div class="row-fluid"  style="">	            
		  	    		<input type="hidden" id ="quePorcentaje" value="<?php echo $porcentajeCreados ?>" />  
           <!--- MAIN GRAPHICAL DASHBOARD --->
            <div style="height:400px; text-align:center; margin:0 auto; margin-top:30px; margin-botom:-20px; border: 1px solid #cacaca; -webkit-border-radius: 3px; -moz-border-radius: 3px; border-radius: 3px;">
            	<div style="width:100%; margin-top:15px; text-align:center;">
            	<style>
            	i.navArrows{
	            	color: #22aeff;
            	}
            	i.navArrows:hover{
	            	color: #226fff;
            	}
            	i.navArrows:focus{
	            	color: black;
            	}
            	</style>
            	<div id="CenterLabels" style="width:450px; margin:0 auto; text-align:center; padding-left:100px;">
	            	<i id="LeftChevron" class="icon-chevron-sign-left icon-2x navArrows" ></i>
	                <span id="MyRadar" style="left: 0px; margin-left: 20px; margin-right: 20px; margin-top: 20px; margin-bottom: 5px; font-size: 16px; background-color: #22aeff; padding: 1px 4px 2px; -webkit-border-radius: 3px; -moz-border-radius: 3px; border-radius: 3px; font-size: 22px; font-weight: bold; line-height: 22px; color: #ffffff; white-space: nowrap; vertical-align: baseline; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;"> Referral's Radar&copy</span>
	                <i id="RightChevron" class="icon-chevron-sign-right icon-2x navArrows"></i>
	                <span id="pageNumber" style="font-size:18px; color:#22aeff; float:right; margin-top:5px;">page 1/3</span>
            	</div>
	                <div style="width:300px; height:350px; float:left; padding-top:50px; /*border:solid;*//*KYLE" id="BoxLeft">
		                <?php 
		                	$indice=0;
		                	$indiceM = 0;
		                	$PacketsVistos = 0; 
		                	$C5='rgba(105,120,250,';
							$C0='rgba(115,187,59,';
		  				//rgba(115,187,59,0.99)
 		  				?>
		               	<!---- CAJA DE PRESENTACIÓN DE NÚMEROS ---->
		  	    		<div style="width:100px; border:1px solid rgba(194,194,194,1); float:left;  -webkit-border-radius: 5px; -moz-border-radius: 5px; border-radius: 5px; margin-left:30px;" title="This figure indicates the number of individual patients that have accessed information created by this user">
		  	    		
		  	    		<div style="height:50px; width:100px;  text-align:center; margin:0px;">  <!-- Font-size: 70 para 2 digitos, 50 para 3 digitos, 30 para 4 digitos -->
			  	    		<p id="NReferrals" style=" font-size:<?php echo queFuente(12);?>px; font-weight:bold; color: <?php echo $C5.'0.99)' ?>; margin-top:30px; font-family:Arial;"><?php echo $indice ?></p>
		  	    		</div>
		  	    		
		  	    		<div style="width:100px;  text-align:center; margin:0px; background-color: <?php echo $C5.'0.99)' ?>; border:1px solid <?php echo $C5.'0.99)' ?>; margin-left:-1px;  -webkit-border-bottom-right-radius: 5px; -moz-border-bottom-right-radius: 5px; border-bottom-right-radius: 5px; -webkit-border-bottom-left-radius: 5px; -moz-border-bottom-left-radius: 5px; border-bottom-left-radius: 5px;">
		  	    		<p style="font-family: arial; font-size:18px; color:white; padding:5px; margin:0px; ">Referrals</p>
		  	    		</div>	
		  	    		
		  	    		</div>
		  	    		<!---- CAJA DE PRESENTACIÓN DE NÚMEROS ---->
		  	    		<!---- CAJA DE PRESENTACIÓN DE NÚMEROS ---->
		  	    		<div style="width:100px; border:1px solid rgba(194,194,194,1); float:left;  -webkit-border-radius: 5px; -moz-border-radius: 5px; border-radius: 5px; margin-left:10px; " title="This figure indicates the number of individual doctors that have accessed information created by this user">
		  	    		
		  	    		<div style="height:50px; width:100px;  text-align:center; margin:0px;">  <!-- Font-size: 70 para 2 digitos, 50 para 3 digitos, 30 para 4 digitos -->
			  	    		<p id="NPatients" style=" font-size:<?php echo queFuente(25);?>px; font-weight:bold; color: <?php echo $C0.'0.99)' ?>;  margin-top:30px; font-family:Arial;"><?php echo $indiceM ?></p>
		  	    		</div>
		  	    		
		  	    		<div style="width:100px;  text-align:center; margin:0px; background-color: <?php echo $C0.'0.99)' ?>; border:1px solid <?php echo $C0.'0.99)' ?>; margin-left:-1px;  -webkit-border-bottom-right-radius: 5px; -moz-border-bottom-right-radius: 5px; border-bottom-right-radius: 5px; -webkit-border-bottom-left-radius: 5px; -moz-border-bottom-left-radius: 5px; border-bottom-left-radius: 5px;">
		  	    		<p style="font-family: arial; font-size:18px; color:white; padding:5px; margin:0px; ">Patients</p>
		  	    		</div>	
		  	    		
		  	    		</div>
		  	    		<!---- CAJA DE PRESENTACIÓN DE NÚMEROS ---->
		  	    		<!---- CAJA DE PRESENTACIÓN DE NÚMEROS ---->
		  	    		<div style="margin-top:15px; margin-left:45px; width:180px; border:1px solid rgba(194,194,194,1); float:left;  -webkit-border-radius: 5px; -moz-border-radius: 5px; border-radius: 5px; " title="This figure indicates the number of individual pieces of information (packets) created by this user that have benn accessed in total">
		  	    		
		  	    		<div style="height:80px; width:180px;  text-align:center; margin:0px; display: table;">  <!-- Font-size: 70 para 2 digitos, 50 para 3 digitos, 30 para 4 digitos -->
			  	    		<p id="NTime2Visit" style=" font-size:<?php echo queFuente(3);?>px; font-weight:bold; color: rgba(255,66,66,0.99);  margin-top:0px; font-family:Arial; font-size:<?php echo queFuente(3)/2;?>px; display: table-cell; vertical-align: middle;"><?php echo $PacketsVistos.' days'; ?></p>
		  	    		</div>
		  	    		
		  	    		<div style="width:180px;  text-align:center; margin:0px; background-color: rgba(255,66,66,0.99); border:1px solid rgba(255,66,66,0.99); margin-left:-1px;  -webkit-border-bottom-right-radius: 5px; -moz-border-bottom-right-radius: 5px; border-bottom-right-radius: 5px; -webkit-border-bottom-left-radius: 5px; -moz-border-bottom-left-radius: 5px; border-bottom-left-radius: 5px;">
		  	    		<p style="font-family: arial; font-size:18px; color:white; padding:5px; margin:0px; ">Time to Visit</p>
		  	    		</div>	
		  	    		
		  	    		</div>
		  	    		<!---- CAJA DE PRESENTACIÓN DE NÚMEROS ---->
		                
		                
	                </div>
	                
					<canvas id="myCanvas" width="450" height="350" style="width:0px; height:0px; margin:0 auto; /*border:solid;*//*KYLE ">
	                </canvas>
	                <div id="CanvasContainer" style="width:450px; height:350px; margin:0 auto; float:left;"></div>
	                
	                <div style="width:200px; height:300px; float:right; text-align:left; padding-top:100px;" id="BoxRight">
		             
		                <!--<div id="DrName" style="font-family: 'Cabin'; color: #3d93e0; font-size:30px; font-weight:bold; width:100%; "></div>-->
		                <div id="DrName" style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #3d93e0; font-size:20px; font-weight:bold; width:100%; "></div>
						<div style="width:100%; margin-top:5px;"></div>
						<span id="DrEmail" style="margin-top:10px; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #3d93e0; font-size:15px; width:100%;"></span>
<!--
		             	<span id="DrName" style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size:20px; font-weight:bold; color:#3d93e0;">Dr. Nameajdh Surnamelkd</span>   
		             	<div style="width:100%;"></div>
		             	<span id="DrEmail" style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size:14px;  color:#3d93e0;">namea.surnamelkd@mail.com</span>   
-->		             	
		             	<div style="width:100%; margin-bottom:15px;"></div>
		             	<span id="DrPatients" style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size:20px;  color:rgb(115,187,59); font-weight:bold;"></span>   
		             	<span id="AdditHtml" style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size:16px;  color:grey;"></span>   
		             	<div style="width:100%;"></div>
		             	<span id="DrTtoVText" style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size:16px;  color:grey;"></span>   
		             	<span id="DrTtoV" style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size:20px;  color:rgb(115,187,59); font-weight:bold;"></span>   

					 	<div style="width:100%; margin-bottom:45px;"></div>
		             	<!--
		             	<span id="DrName" style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size:20px; font-weight:bold; color:rgb(115,187,59);">Patname Patsurname</span>   
		             	<span id="DrEmail" style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size:14px;  color:rgb(115,187,59);">npatnaa.supatied@mail.com</span>   
		             	<div style="width:100%; margin-bottom:15px;"></div>
		             	<span style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size:14px;  color:grey;"> Time to Visit: </span>   
		             	<span id="DrTtoV" style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size:18px;  color:#3d93e0;">5 days</span>   
		             	-->
		                
	                </div>
            	</div>
                    </div>	


    	  </div>

       
     
       
       
            <!--Tabs Start-->
        <div style="margin:10px; margin-top:-30px;" >
        <!--TAB Start-->
          <ul id="myTab" class="nav nav-tabs tabs-main">
            <li class="active" style="width:50%; "><a href="#sendR" data-toggle="tab">SEND to referrals</a></li>
            <li style="width:50%; "><a href="#getR" data-toggle="tab">GET from referrals</a></li>
          </ul> 
          <div id="myTabContent" class="tab-content tabs-main-content padding-null">
                <div class="tab-pane tab-overflow-main fade in active" id="sendR">
                	
                	<div class="grid" style="float:left; width:95%; height:800px; margin: 0 auto; margin-left:2%; margin-top:10px; margin-bottom:30px; overflow:scroll;">
								<div class="grid-title">
									<div class="pull-left"><div class="fam-user" style="margin-right:10px;"></div>Referrals List & Status</div>
           
										<img src="images/load/8.gif" alt="" style="margin-left:50px; margin-top:10px; display: none;" id="Wait1">

									<div class="pull-right"></div>
									<div class="clear"></div>   
								</div>
          
								<div class="search-bar">
									<input type="text" class="span" name="" placeholder="Search Patient" style="width:150px;" id="SearchUserUSERFIXED"> 
									<input type="button" class="btn btn-primary" value="Filter" style="margin-left:50px;" id="BotonBusquedaSents">
                                    <input type="button" class="btn btn-success" value="SEND" id="BotonWizard" style="margin-left:250px; margin-top:0px; width:120px;">
								</div>
          
								<div class="grid" style="margin-top:0px;">
									<table class="table table-mod" id="TablaSents" style="width:100%; table-layout: fixed; ">
									</table> 
  
								</div>
          
					</div>

                
                    <p id="TextoSend" style="text-align:center;"></p>

                
                
 
                
                 </div>
                
                <div class="tab-pane" id="getR">
                	<div style="height:500px; margin:10px; padding:10px;">
                
                        <div class="grid" style="float:left; width:95%; height:800px; margin: 0 auto; margin-left:2%; margin-top:30px; margin-bottom:30px; overflow:scroll;">
								<div class="grid-title">
									<div class="pull-left"><div class="fam-user" style="margin-right:10px;"></div>Connection Permits List</div>
           
										<img src="images/load/8.gif" alt="" style="margin-left:50px; margin-top:10px; display: none;" id="Wait1">

									<div class="pull-right"></div>
									<div class="clear"></div>   
								</div>
          
								<div class="search-bar">
          
									<input type="text" class="span" name="" placeholder="Search Patient" style="width:150px;" id="SearchUserUSERFIXED"> 
									<input type="button" class="btn btn-primary" value="Filter" style="margin-left:50px;" id="BotonBusquedaPermit">
                   
								</div>
          
								<div class="grid" style="margin-top:0px;">
									<table class="table table-mod" id="TablaPermit" style="width:100%; table-layout: fixed; ">
									</table> 
  
								</div>
          
						</div>

							
                </div>
          </div>
        <!--TAB END-->
        </div>
        <!--Tabs END-->  
	     
     </div>
     <!--CONTENT MAIN END-->
     
	 </div> 
    </div>
    <!--Content END-->
	<div id="footer">
		<div id="center_footer">
			<span ><p>©Copyright 2014 Inmers LLC. Health2.Me is a property of Inmers LLC. Patent pending. </p></span>
		</div>
	</div> 
    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <!--<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>-->
    <script src="js/jquery.min.js"></script>
    <script src="js/jquery-ui.min.js"></script>
	<script src="js/kinetic-v4.5.5.min.js"></script>


    <script src="realtime-notifications/lib/gritter/js/jquery.gritter.min.js"></script>
    <link href="realtime-notifications/lib/gritter/css/jquery.gritter.css"rel="stylesheet" type="text/css" />
    <script type="text/javascript" >
    
    var paciente='';
    var destino='';
	var Nondestino='';
    var IdPaciente = -1;
    var IdDoctor = -1;
    
	//var timeoutTime = 18000000;
	var timeoutTime = 300000;  //5minutes
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
						// RADAR: INITIAL LOADING OF ARRAY AND LAYER SETUP
                        var stageScenery = new Kinetic.Stage({
                                container: 'CanvasContainer',
                                width: 450,
                                height: 350
                        });
        
                        var layer = new Kinetic.Layer();
                        var layerDr = new Kinetic.Layer();
                        var layerEvents = new Kinetic.Layer();
                        var layerArcs = new Kinetic.Layer();
               			var unidad = (Math.PI * 0.1);
               			
               			var PatientName = Array();
               			var PatientStage = Array();
               			var PatientDoctor = Array();
               			var DoctorId = Array();
               			var DoctorEmail = Array();
               			var PatientTTV = Array();
               			var DoctorsNames = Array();
               			var DoctorsEmails = Array();
        
                        var IsOn = Array();
                        var IsOnCounter = 0;
                        var IsOnCounter2 = 0;
        
                        getReferredPatients('getReferredPatients.php');
                        $thisreco =0;
                        while ($thisreco <  ReferredPatients.length)
                        {
                            PatientName[$thisreco] = ReferredPatients[$thisreco].PatientName;
                            PatientStage[$thisreco] = ReferredPatients[$thisreco].PatientStage;
                            PatientDoctor[$thisreco] = ReferredPatients[$thisreco].PatientDoctor;
                            DoctorId[$thisreco] = ReferredPatients[$thisreco].IdMED2;
                            DoctorEmail[$thisreco] = ReferredPatients[$thisreco].EmailDoctor;
                            if ( PatientDoctor[$thisreco] < "   ")  PatientDoctor[$thisreco]=DoctorEmail[$thisreco];
                            PatientTTV[$thisreco] = ReferredPatients[$thisreco].TTV;
                            DoctorsNames[DoctorId[$thisreco]] = PatientDoctor[$thisreco];
               			    DoctorsEmails[DoctorId[$thisreco]] = DoctorEmail[$thisreco];
               			    $thisreco ++;
                        }
						var DrColor = Array();
               			
               			// Create array for Referrals (doctors)
               			var ReferralId = Array();
               			var ReferralName = Array();
               			var ReferralStaP = Array();
               			var ReferralEndP = Array();
               			var brokenReferral = Array();
               			$thisreco =0;
                        var ReferralNumber = 0;
                        var LatestPatient = 0;
                        var LatestDoctor = 0;
                        while ($thisreco <=  ReferredPatients.length)
                        {
	                        if ($thisreco > 0 && (PatientDoctor[$thisreco] != LatestDoctor))
	                        {
		                        ReferralStaP[ReferralNumber] = LatestPatient;
		                        ReferralEndP[ReferralNumber] = $thisreco-1;

		                        ReferralId[ReferralNumber] = DoctorId[$thisreco-1];
		                        ReferralName[ReferralNumber] = DoctorsNames[DoctorId[$thisreco-1]];
								
								console.log('- NEW REFERRAL: -' ); 
								console.log(ReferralNumber ); 
								console.log(ReferralId[ReferralNumber] ); 
								console.log(ReferralName[ReferralNumber] ); 
								console.log(ReferralStaP[ReferralNumber] ); 
								console.log(ReferralEndP[ReferralNumber] ); 
								console.log('' ); 
								
								LatestPatient = $thisreco;
								LatestDoctor = PatientDoctor[$thisreco];
		                        ReferralNumber++;
	                        }
               			    $thisreco ++;
                        }
                        
               			
               			DrColor[0]='rgb(255,66,66)';
               			DrColor[1]='rgb(105,120,250)';
               			DrColor[2]='orange';
               			DrColor[3]='rgb(255,66,166)'; 
               			DrColor[4]='rgb(122,199,59)';
               			DrColor[5]='rgb(115,100,59)';
               			DrColor[6]='rgb(115,150,59)';
               			DrColor[7]='rgb(115,250,59)';
               			DrColor[8]='rgb(115,187,59)';
               			DrColor[9]='rgb(115,187,159)';
						// RADAR: INITIAL LOADING OF ARRAY AND LAYER SETUP


               			/*
               			DrColor[0]='Blue';
               			DrColor[1]='Green';
               			DrColor[2]='Orange';
               			DrColor[3]='Magenta'; 
               			DrColor[4]='Red';
               			DrColor[5]='Yellow';
               			DrColor[6]='Salmon';
               			DrColor[7]='Sienna';
               			DrColor[8]='SteelBlue';
               			DrColor[9]='Thistle';
			   			*/
			   			// This is the desired number of patients in every pie graph
						/*KYLE
			   			var DesiredPatientsPie = 12;
    
			   			// Find the number of Referrals (doctors) that add closer number of patients to the desired number of patients per graph 
			   			
			   				
			   			
						function Slice(order,stage,centerX,centerY){
								var stagenum = 15;
								//var centerX = 200;
								//var centerY = 175;
								switch (stage)
								{
									case '': 	stagenum = 25;
												break;
									case '1': 	stagenum = 25;
												break;
									case '2': 	stagenum = 50;
												break;
									case '3': 	stagenum = 75;
												break;
									case '4': 	stagenum = 100;
												break;
								}
								var canvas = $("#myCanvas");
								var context = canvas.get(0).getContext("2d");
								context.lineWidth = 1;
										
								context.beginPath(); // Start the path
								context.moveTo(200, 175); // Set the path origin
								//context.lineTo(440, 40); // Set the path destination
								var inicio = -(Math.PI*.5)+(order*unidad);
								var sumas = unidad /2;							
								//context.arc(centerX, centerY, stagenum, inicio,(inicio+ unidad), false); // Draw a circle
								var wedgeP = new Kinetic.Wedge({
							        x: centerX,
							        y: centerY,
							        radius: stagenum,
							        angle: unidad,
							        fill: 'grey',
							        stroke: 'e6e6e6',
							        strokeWidth: 1,
							        rotation: inicio,
							        opacity:0.99
							      });
							    // add the shape to the layer
							    layer.add(wedgeP);

							    wedgeP.on('touchstart click', function() {
							    	//this.setFill('blue');
									alert ('patient');
									this.setOpacity(0.8);
									this.setStroke('black');
									this.setStrokeWidth(2);
									layer.draw();
        
							        $('#DrName').html(DrName);
							        $('#DrEmail').html(DrEmail);
							        $('#DrPatients').html(NumPatients);
							        $('#AdditHtml').html(NumPatients);
							        $('#DrTtoV').html(MeanTTV.toFixed(1)+' days');
									$('#DrTtoVText').html('Time to visit: ');
									//this.setFill(Acolor);
									this.setOpacity(0.4);
									this.setStroke(Acolor);
									this.setStrokeWidth(1);									
							      });

								context.closePath(); // Close the path
								context.fillStyle = "#3d93e0";
								context.globalAlpha=0.99; // Half opacity
								context.fill(); // Fill the path
							 	context.strokeStyle = '#3d93e0';
      							context.strokeStyle = 'blue';
      							context.stroke();
      							      							
						};
						
						function SliceDrANT(n,startPatient, endPatient, stage, Acolor, DrId, NumPatients, MeanTTV){
								var stagenum = 15;
								switch (stage)
								{
									case 1: 	stagenum = 25;
												break;
									case 2: 	stagenum = 50;
												break;
									case 3: 	stagenum = 75;
												break;
									case 4: 	stagenum = 100;
												break;
								}
								var canvas = $("#myCanvas");
								var context = canvas.get(0).getContext("2d");
								context.lineWidth = 1;
								
								context.beginPath(); // Start the path
								context.moveTo(200, 175); // Set the path origin

								var inicio = -(Math.PI*.5) + (startPatient*unidad);
								var final =  -(Math.PI*.5) + ((endPatient+1)*unidad);							
								// 'wedge' is the pie slice that corresponds to a referral (doctor)	
								var wedge = new Kinetic.Wedge({
							        x: 200,
							        y: 175,
							        radius: stagenum,
							        angle: unidad * (endPatient-startPatient+1),
							        fill: Acolor,
							        stroke: Acolor,
							        strokeWidth: 1,
							        rotation: inicio,
							        opacity:0.4
							      });
							    layer.add(wedge);

	  							// 'arc' is the external arc outside the pie, representing a referral (doctor)
								var arc = new Kinetic.Shape({
								    drawFunc: function(canvas) {
								        var context = canvas.getContext();
								        var context = canvas.getContext('2d');
								        context.globalAlpha=0.8; // 0.8 opacity
										context.beginPath();
								        context.arc(200, 175, 120, inicio, final, false);
								        canvas.stroke(this);
								    },
								    fill: Acolor,
								    stroke: Acolor,
								    opacity:0.1,
								    strokeWidth: 10,
								});
							    layer.add(arc);
								
								arc.on('mousedown click', function() {
							        //writeMessage(messageLayer, 'Mousedown circle');
							        alert ('click on arc');
							      });

							    
							    wedge.on('touchstart click mouseover', function() {
							    	//this.setFill('blue');
									this.setOpacity(0.8);
									this.setStroke('black');
									this.setStrokeWidth(2);
									arc.setStrokeWidth(15);
									
									layer.draw();
        
							        $('#DrName').html(n + ' ' + DoctorsNames[DrId]);
							        $('#DrEmail').html(DoctorsEmails[DrId]);
							        $('#DrPatients').html(NumPatients);
							        $('#AdditHtml').html(' patients');
							        $('#DrTtoV').html(MeanTTV.toFixed(1)+' days');
									$('#DrTtoVText').html('Time to visit: ');
                                    //this.setFill(Acolor);
									this.setOpacity(0.4);
									this.setStroke(Acolor);
									this.setStrokeWidth(1);		
									arc.setStrokeWidth(10);
																
								});
								
							    
								context.closePath(); // Close the path
								//context.fillStyle = "#3d93e0";
								context.fillStyle = Acolor;
								context.globalAlpha=0.4; // Half opacity
								context.fill(); // Fill the path
							 	//context.strokeStyle = '#3d93e0';
      							context.strokeStyle = Acolor;
      							context.stroke();
      							
      							var arcpad = 0.09;
								inicio = inicio + arcpad;
     							final = final - arcpad;
     							
     							context.globalAlpha=0.8; // 0.8 opacity
								
								context.beginPath(); // Start the path
      							
      							context.arc(200, 175, 120, inicio, final, false); // Draw a circle
	  							
								
								context.lineWidth = 15;
								//context.closePath(); // Close the path
								context.strokeStyle = Acolor;
      							context.stroke();

	  							// Position the label for the Doctor
	  							TextFontsize = 12;
	  							context.font = 'bold 12px Helvetica';
                                DrName = DoctorsNames[DrId].substr(0,12);
                                DrName = n + ' ' + DrName;
                                if (DoctorsNames[DrId].length>11) DrName = DrName+'..';
                                var midArc = inicio + ((final-inicio)/2) ;
	  							//var newX = 200 + (120 * Math.sin(midArc)) ;
	  							//var newY = 175 + (120 * Math.cos(midArc)) ;
	  							var newX = 200 + (140 * Math.cos(midArc)) ;
	  							var newY = 175 + (140 * Math.sin(midArc)) ;
	  							var textmetrics = context.measureText(DrName);
	  							var textwidth = textmetrics.width;
	  							var textheight = TextFontsize * 1.5;
	  							if (newX < 200) 
	  							{
	  								newX = newX - textwidth;
								}
								if (newY < 175)
	  							{
		  							var basePos = 'bottom';
		  							var iniBox = 15 ;
	  							} 
	  							else
	  							{
		  							var basePos = 'top';
		  							var iniBox = 0;
	  							}
	  							
	  							// Draw Box around text
								context.beginPath();
//								context.rect(newX, newY-iniBox, textwidth, textheight);
								context.rect(newX-5, newY-iniBox, textwidth+10, 16);
								var rect = new Kinetic.Rect({
							        x: newX-5,
							        y: newY-iniBox,
							        width: textwidth+10,
							        height: 16,
							        fill: Acolor,
							        stroke: Acolor,
							        strokeWidth: 1,
							        opacity:0.8
							      });
							    // add the shape to the layer
								layer.add(rect);
								rect.on('mousedown', function() {
							        //writeMessage(messageLayer, 'Mousedown circle');
							        alert ('click on rect');
							      });

								context.fillStyle = Acolor;
								context.fill();
								context.lineWidth = 1;
								context.strokeStyle = Acolor;
								context.stroke();


	  							context.fillStyle = 'white';
								context.font = 'bold 12px Helvetica';
								context.textBaseline = basePos;
								//context.fillText(DrName, 50, 100);
								context.fillText(DrName, newX, newY);
								var simpleText = new Kinetic.Text({
							        x: newX,
							        y: newY-iniBox+2,
							        text: DrName,
							        fontSize: 12,
							        fontFamily: 'Helvetica',
							        fill: 'white'
							      });
							    // add the shape to the layer
								layer.add(simpleText);

						};
						function SliceDr(n,startPatient, endPatient, stage, Acolor, DrId, NumPatients, MeanTTV){
								var stagenum = 15;
								switch (stage)
								{
									case 1: 	stagenum = 25;
												break;
									case 2: 	stagenum = 50;
												break;
									case 3: 	stagenum = 75;
												break;
									case 4: 	stagenum = 100;
												break;
								}
								var canvas = $("#myCanvas");
								var context = canvas.get(0).getContext("2d");
								context.lineWidth = 1;
								
								context.beginPath(); // Start the path
								context.moveTo(200, 175); // Set the path origin

								var inicio = -(Math.PI*.5) + (startPatient*unidad);
								var final =  -(Math.PI*.5) + ((endPatient)*unidad);							
								// 'wedge' is the pie slice that corresponds to a referral (doctor)	
								var wedge = new Kinetic.Wedge({
							        x: 200,
							        y: 175,
							        radius: stagenum,
							        angle: unidad * (endPatient-startPatient),
							        fill: Acolor,
							        stroke: Acolor,
							        strokeWidth: 1,
							        rotation: inicio,
							        opacity:0.4
							      });
							    layer.add(wedge);

	  							// 'arc' is the external arc outside the pie, representing a referral (doctor)
								var arc = new Kinetic.Shape({
								    drawFunc: function(canvas) {
								        var context = canvas.getContext();
								        var context = canvas.getContext('2d');
								        context.globalAlpha=0.8; // 0.8 opacity
										context.beginPath();
								        context.arc(200, 175, 120, inicio, final, false);
								        canvas.stroke(this);
								    },
								    fill: Acolor,
								    stroke: Acolor,
								    opacity:0.1,
								    strokeWidth: 10,
								});
							    layer.add(arc);
								
								arc.on('mousedown click', function() {
							        //writeMessage(messageLayer, 'Mousedown circle');
							        alert ('click on arc');
							      });

							    
							    wedge.on('touchstart click mouseover', function() {
							    	//this.setFill('blue');
									this.setOpacity(0.8);
									this.setStroke('black');
									this.setStrokeWidth(2);
									arc.setStrokeWidth(15);
									
									layer.draw();
        
							        $('#DrName').html(n + ' ' + DoctorsNames[DrId]);
							        $('#DrEmail').html(DoctorsEmails[DrId]);
							        $('#DrPatients').html(NumPatients);
							        $('#AdditHtml').html(' patients');
							        $('#DrTtoV').html(MeanTTV.toFixed(1)+' days');
									$('#DrTtoVText').html('Time to visit: ');
                                    //this.setFill(Acolor);
									this.setOpacity(0.4);
									this.setStroke(Acolor);
									this.setStrokeWidth(1);		
									arc.setStrokeWidth(10);
																
								});
								
							    
								context.closePath(); // Close the path
								//context.fillStyle = "#3d93e0";
								context.fillStyle = Acolor;
								context.globalAlpha=0.4; // Half opacity
								context.fill(); // Fill the path
							 	//context.strokeStyle = '#3d93e0';
      							context.strokeStyle = Acolor;
      							context.stroke();
      							
      							var arcpad = 0.09;
								inicio = inicio + arcpad;
     							final = final - arcpad;
     							
     							context.globalAlpha=0.8; // 0.8 opacity
								
								context.beginPath(); // Start the path
      							
      							context.arc(200, 175, 120, inicio, final, false); // Draw a circle
	  							
								
								context.lineWidth = 15;
								//context.closePath(); // Close the path
								context.strokeStyle = Acolor;
      							context.stroke();

	  							// Position the label for the Doctor
	  							TextFontsize = 12;
	  							context.font = 'bold 12px Helvetica';
                                DrName = DoctorsNames[DrId].substr(0,12);
                                DrName = n + ' ' + DrName;
                                if (DoctorsNames[DrId].length>11) DrName = DrName+'..';
                                var midArc = inicio + ((final-inicio)/2) ;
	  							//var newX = 200 + (120 * Math.sin(midArc)) ;
	  							//var newY = 175 + (120 * Math.cos(midArc)) ;
	  							var newX = 200 + (140 * Math.cos(midArc)) ;
	  							var newY = 175 + (140 * Math.sin(midArc)) ;
	  							var textmetrics = context.measureText(DrName);
	  							var textwidth = textmetrics.width;
	  							var textheight = TextFontsize * 1.5;
	  							if (newX < 200) 
	  							{
	  								newX = newX - textwidth;
								}
								if (newY < 175)
	  							{
		  							var basePos = 'bottom';
		  							var iniBox = 15 ;
	  							} 
	  							else
	  							{
		  							var basePos = 'top';
		  							var iniBox = 0;
	  							}
	  							
	  							// Draw Box around text
								context.beginPath();
//								context.rect(newX, newY-iniBox, textwidth, textheight);
								context.rect(newX-5, newY-iniBox, textwidth+10, 16);
								var rect = new Kinetic.Rect({
							        x: newX-5,
							        y: newY-iniBox,
							        width: textwidth+10,
							        height: 16,
							        fill: Acolor,
							        stroke: Acolor,
							        strokeWidth: 1,
							        opacity:0.8
							      });
							    // add the shape to the layer
								layer.add(rect);
								rect.on('mousedown', function() {
							        //writeMessage(messageLayer, 'Mousedown circle');
							        alert ('click on rect');
							      });

								context.fillStyle = Acolor;
								context.fill();
								context.lineWidth = 1;
								context.strokeStyle = Acolor;
								context.stroke();


	  							context.fillStyle = 'white';
								context.font = 'bold 12px Helvetica';
								context.textBaseline = basePos;
								//context.fillText(DrName, 50, 100);
								context.fillText(DrName, newX, newY);
								var simpleText = new Kinetic.Text({
							        x: newX,
							        y: newY-iniBox+2,
							        text: DrName,
							        fontSize: 12,
							        fontFamily: 'Helvetica',
							        fill: 'white'
							      });
							    // add the shape to the layer
								layer.add(simpleText);

						};
	                    
	                    function getReferredPatients(serviceURL){
							$.ajax(
							{
								url: serviceURL,
								dataType: "json",
								async: false,
								success: function(data)
								{
									//alert('Data Fetched');
									ReferredPatients = data.items;
								}
							});
						}   

   
     
    $(document).ready(function() {

    $(window).bind('load', function(){
        $('#BotonBusquedaSents').trigger('click');
    });
    
        
    var phase = 1;
    var ancho = $("#header-modal").width();
    $('#content_selpat').css('width',ancho);
    $('#content_seldr').css('width',ancho);
    $('#content_att').css('width',ancho);
    $('#content_addcom').css('width',ancho);
	$('#ScrollerContainer').css('width',ancho*5);
   
    
    function DrawRadarPatients (startingPatient, endingPatient)
    {
		var centerX = 200;
		var centerY = 175;
		var stagenum = 0;
		// Draw patients section 
		
		// This puts a limit if the endingpatient value is above maximum number of patients
		var endSequence = parseInt(endingPatient);
		if (endSequence >= PatientName.length) endSequence = parseInt(PatientName.length)-1;

    	var NumberOfItems = endSequence - startingPatient +1;
		if (NumberOfItems !=  0) unidad = (Math.PI * 2 / NumberOfItems); else  unidad = (Math.PI);
		var canvas = $("#myCanvas");
		var context = canvas.get(0).getContext("2d");
		context.lineWidth = 1;
		context.beginPath(); // Start the path
		context.moveTo(200, 175); // Set the path origin

		var n = startingPatient;
		/*
		console.log(' ************************************ ');
		console.log('From: '+n+'  To: '+endingPatient);
		console.log('Total patients: '+PatientName.length);
		*/
		/*KYLE
		while (parseInt(n) <= endSequence)
		{
			drawingOrder = n - startingPatient;
			stage = PatientStage[n] ;
			switch (stage)
			{
				case '': 	stagenum = 25;
							break;
				case '1': 	stagenum = 25;
							break;
				case '2': 	stagenum = 50;
							break;
				case '3': 	stagenum = 75;
							break;
				case '4': 	stagenum = 100;
							break;
			}
			var angleShift = -(Math.PI*.5)+(drawingOrder*unidad);
						
			var wedgeP = new Kinetic.Wedge({
		        x: centerX,
		        y: centerY,
		        radius: stagenum,
		        angle: unidad,
		        fill: 'grey',
		        stroke: 'e6e6e6',
		        strokeWidth: 1,
		        rotation: angleShift,
		        opacity:0.40
		      });
		    // add the shape to the layer
		    layer.add(wedgeP);
		
			wedgeP.on('touchstart click mouseover', function(evt) {
				var shape = evt.targetNode;
				this.setOpacity(0.8);
				this.setStroke('black');
				this.setStrokeWidth(2);
				layer.draw();	
				m = this.index;
				RealIndex = parseInt(m) + parseInt(startingPatient);
		        $('#DrName').html(RealIndex + ' ' + PatientName[RealIndex]);
		        $('#DrEmail').html(DoctorsNames[DoctorId[RealIndex]]);
				this.setOpacity(0.4);
				this.setStroke(DrColor[RealIndex]);
				this.setStrokeWidth(1);						//arc.setStrokeWidth(10);
			});
			
			//console.log(NumberOfItems+' Items  '+n+'   Name:'+PatientName[n]+'   stagenum:'+stagenum);
		
			n++;	
		}
		stageScenery.add(layer);
		
		// Draw doctors section (referrals)
		n = 0;
		maxReferrals = ReferralId.length;
		console.log('***************************************');
		var countseries = 0;
				
		while (n <= maxReferrals)
		{
			decena = parseInt(n/10);
			secColor = n - (decena*10);
			
			mustDrawReferral = 0;
			if ((ReferralEndP[n] >= startingPatient) && (ReferralStaP[n] <= endSequence)) mustDrawReferral = 1; else mustDrawReferral = 0; 
			console.log ('REFERRAL: '+n+'        ReferralEndPatient: '+ReferralEndP[n]+'     startingPatient: '+startingPatient);
			if (mustDrawReferral == 1)
			{
				countseries++;
				startDrawPat = ReferralStaP[n]- startingPatient;
				if (countseries == 1) {startDrawPat = 0; var FirstReferral = n;}

				if (ReferralEndP[n] <= endSequence) 
				{
					endDrawPat = ReferralEndP[n] - startingPatient; 
					brokenReferral[n] = 0;
				}
				else 
				{
					endDrawPat = endSequence - startingPatient;
					brokenReferral[n] = 1;
				}
				var inicio = -(Math.PI*.5) + (startDrawPat*unidad);
				var final =  -(Math.PI*.5) + (endDrawPat*unidad);							
				// 'wedge' is the pie slice that corresponds to a referral (doctor)	
				referralWidth = unidad * (endDrawPat-startDrawPat+1); 
				var wedge = new Kinetic.Wedge({
			        x: 200,
			        y: 175,
			        radius: 100,
			        angle: referralWidth,
			        fill: DrColor[secColor],
			        stroke: DrColor[secColor],
			        strokeWidth: 1,
			        rotation: inicio,
			        name: 'cosa',
			        opacity:0.40
			      });
			    
			    layerEvents.add(wedge);
				
				// 'arc' is the external arc outside the pie, representing a referral (doctor)
				
				DrawArcRef (inicio+0.1,inicio+referralWidth-0.1,DrColor[secColor]);

	  			// Position the label for the Doctor
				TextFontsize = 12;
				context.font = 'bold 12px Helvetica';
                DrName = ReferralName[n].substr(0,12);
                DrName = n + ' ' + DrName;
                if (ReferralName[n].length>11) DrName = DrName+'..';
                var midArc = inicio + ((final-inicio)/2) ;
					var newX = 200 + (140 * Math.cos(midArc)) ;
					var newY = 175 + (140 * Math.sin(midArc)) ;
					var textmetrics = context.measureText(DrName);
					var textwidth = textmetrics.width;
					var textheight = TextFontsize * 1.5;
					if (newX < 200) 
					{
						newX = newX - textwidth;
				}
				if (newY < 175)
					{
						var basePos = 'bottom';
						var iniBox = 15 ;
					} 
					else
					{
						var basePos = 'top';
						var iniBox = 0;
					}
					
				// Draw Box around text
				
				context.beginPath();
				context.rect(newX-5, newY-iniBox, textwidth+10, 16);
				var rect = new Kinetic.Rect({
			        x: newX-5,
			        y: newY-iniBox,
			        width: textwidth+10,
			        height: 16,
			        fill: DrColor[secColor],
			        stroke: DrColor[secColor],
			        strokeWidth: 1,
			        opacity:0.8
			      });
			    
			    // add the shape to the layer
				layerDr.add(rect);
				context.fillStyle = DrColor[secColor];
				context.fill();
				context.lineWidth = 1;
				context.strokeStyle = DrColor[secColor];
				context.stroke();


				context.fillStyle = 'white';
				context.font = 'bold 12px Helvetica';
				context.textBaseline = basePos;
				//context.fillText(DrName, 50, 100);
				context.fillText(DrName, newX, newY);
				var simpleText = new Kinetic.Text({
			        x: newX,
			        y: newY-iniBox+2,
			        text: DrName,
			        fontSize: 12,
			        fontFamily: 'Helvetica',
			        fill: 'white'
			      });
			    // add the shape to the layer
				layerDr.add(simpleText);
				
				// Add interaction
				wedge.on('touchstart click mouseover', function(evt) {
					document.body.style.cursor = 'pointer';		
					var shape = evt.targetNode;
					this.setOpacity(0.6);
					this.setStroke('black');
					this.setStrokeWidth(2);
					layerEvents.draw();
	
					m = this.index;
					RealM = m + FirstReferral;
					if (brokenReferral[m] == 0)  $('#DrName').html(RealM + ' - ' +ReferralName[RealM]); else $('#DrName').html(RealM + ' ' + ReferralName[RealM]+'../.');
					
			        $('#DrEmail').html(DoctorsEmails[m]);
			        $('#DrPatients').html(ReferralEndP[m]-ReferralStaP[m]+1);
			        $('#AdditHtml').html(' patients');
			        //$('#DrTtoV').html(MeanTTV.toFixed(1)+' days');
					$('#DrTtoVText').html('Time to visit: ');
	                //this.setFill(Acolor);
					this.setOpacity(0.4);
					this.setStroke(DrColor[m]);
					this.setStrokeWidth(1);		
					//arc.setStrokeWidth(10);
				});
				
				wedge.on('mouseout', function() {		//Added by Ankit
					document.body.style.cursor = 'default';
				});
	
				wedge.on('click', function() {
					var m = this.index;
					//var RealM = m + FirstReferral;
					var RealM = parseInt(m) + parseInt(FirstReferral);
					var DrId = ReferralId[RealM];
					
					console.log(' IdOrden: '+RealM+'   IdDoctor'+DrId+'  Name: '+ReferralName[RealM]);
					
					var IdMed = $('#MEDID').val();
					var UserDOB='';
					var queUrl ='getSents.php?Doctor='+IdMed+'&DrEmail='+UserDOB+'&NReports=3&ToDoc='+DrId;
					$('#TablaSents').load(queUrl);
					$('#TablaSents').trigger('update');
					var txt='<b> Dr. '+DoctorsNames[DrId] + '</b>';
					$('#radartext').html(txt);
					$('#BotonBusquedaReset').show();
				});


	
			}
			
			/*
			console.log('- REFERRAL drawing: -' ); 
			console.log(n); 
			console.log(ReferralName[n]); 
			console.log('Starts in: '+ReferralStaP[n]+' Ends In:'+ReferralEndP[n]); 
			console.log(mustDrawReferral); 
			console.log('' ); 
			*/
			/*KYLE
			n++;
		}
		

		stageScenery.add(layerDr);
		stageScenery.add(layerEvents);
		stageScenery.add(layerArcs);

		
    }
    
	function DrawArcRef (ArcStart,ArcEnd,ArcColor)
    {
				var arc = new Kinetic.Shape({
			    drawFunc: function(canvas) {
			        var context = canvas.getContext();
			        var context = canvas.getContext('2d');
			        context.globalAlpha=0.7; // 0.8 opacity
					context.beginPath();
			        context.arc(200, 175, 120, ArcStart, ArcEnd, false);
			        canvas.stroke(this);
			    },
			    fill: ArcColor,
			    stroke: ArcColor,
			    opacity:0.7,
			    strokeWidth: 10,
				});
			    layerArcs.add(arc);
	    
    }
    
    function DrawStats(init,end){
    							// This function draws a Radar of end-init patients, and their correspondent referred doctors.
    							// init is patient where the Radar starts (index of the patient as per the retrieved array of referred patients)  end is the patient to end  
                                var reco = PatientName.length;
							 	var n = 0;
							 	var m = 0;
							 	var actualDr = -1;
							 	var initDr = 0;
							 	var endDr = 9999;
							 	var T2V = 0;
							 	var T2Vparc = 0;
							 	var MeanDrTTV = 0;
							 	var NumDrPac = 0;
							 	var NumDrPacREAL = 0;
                                var DrAcc = 0;
                                
                                
                                
                                n = init;
                                if (end < reco) reco = end;
                                
                                Pa = ReferredPatients.length;
		                        Pa = reco - init-1;
		                        amplitude = 1;
		                        switch (true){
		                            case (Pa >= 0 && Pa < 6) :  amplitude = 4;
		                                                        break;
		                            case (Pa > 5  && Pa < 11) : amplitude = 2;
		                                                        break;
		                            case (Pa > 10 && Pa < 16) : amplitude = 1.5;
		                                                        break;
		                            case (Pa > 15 ) :           amplitude = 1;
		                                                        break;
		                        };
		                        //alert ('Amplitude = '+amplitude);
		                        unidad = (Math.PI * 0.1 * amplitude);
		                        if (Pa !=  0) unidad = (Math.PI * 2 / Pa); else  unidad = (Math.PI);
								
								initDr = n;
                                        
							 	while (n < reco)
							 	{
							 		Slice(n,PatientStage[n],200,175);
							 		MeanDrTTV = MeanDrTTV + parseInt(PatientTTV[n]);
							 		NumDrPac++;
                                    if (parseInt(PatientTTV[n])>0) NumDrPacREAL++;
							 		if (n==init){ actualDr = PatientDoctor[n]; NumDrPac--;}  
							 		if (PatientDoctor[n] != actualDr && n > (init+1) )
							 		{
							 			endDr = n-1;
							 			//alert ('n= '+n+'      initDr='+initDr+'    endDr=  '+endDr);
                                        MeanCor = (MeanDrTTV/NumDrPacREAL);
                                        //alert ('Doctor: '+DoctorsNames[DoctorId[n-1]]+' , Cum.TTV=  '+MeanDrTTV+' , RealPatients= '+NumDrPacREAL);
                                        if (NumDrPacREAL == 0) MeanCor = 0;
								 		if (MeanCor>0) NumDrPacREAL++;
                                        if (MeanCor>0) DrAcc++;
                                        T2Vparc = T2Vparc + MeanCor;	
                                        //alert ('Func A.- MeanDrTTV = '+MeanDrTTV+' , NumDrPac = '+NumDrPac+', MEAN = '+MeanCor+' NumDrPacREAL : '+NumDrPacREAL);
                                        SliceDr (n,initDr,endDr,4,DrColor[m], DoctorId[n-1],NumDrPac,MeanCor);
							 			m++;
							 			if (m > 9) m = 0 ;
							 			initDr = n-1;
								 		actualDr = PatientDoctor[n];
                                        //alert ('This Doctor MeanTTV = '+(MeanDrTTV/NumDrPac)+'  ** Doctor REAL. = '+NumDrPacREAL+' -- TTV MEAN = '+T2Vparc);
							            MeanDrTTV = 0;
								 		NumDrPac = 0;
								 		NumDrPacREAL = 0;
							 		}	
							 		
							 		n++;
							 	}
							 	endDr = n-1;
							 	endDr = reco-init-1;
							 	NumDrPac++;
							 	if (parseInt(PatientTTV[n])>0) NumDrPacREAL++;
                                MeanCor = (MeanDrTTV/NumDrPacREAL);
                                if (NumDrPacREAL == 0) MeanCor = 0;
							 	if (MeanCor>0) NumDrPacREAL++;
                                if (MeanCor>0) DrAcc++;
                                T2Vparc = T2Vparc + MeanCor;	
                                //alert ('Init Dr.: '+initDr+' End Dr: '+endDr);
                                SliceDr (n,initDr,endDr,4,DrColor[m], DoctorId[n-1],NumDrPac,MeanCor);
							    if (DrAcc>0) T2V = T2Vparc / DrAcc; else T2V = 0;
							 	T2V = T2V.toFixed(1);
							 	$("#NReferrals").html(m+1);
							 	$("#NPatients").html(reco);
							 	$("#NTime2Visit").html(T2V+' days');
							 	

							    		

								stageScenery.add(layer);
	     }			
    
    DrawStats(0,999);    
        
    $('body').bind('mousedown keydown', function(event) {
        //clearTimeout(timeoutTimer);
        //timeoutTimer = setTimeout(ShowTimeOutWarning, timeoutTime);
    });
  	
  	var user;
    var doctor;
	//changes for the attachments
    var reportcheck=new Array();
	var reportids='';
	var pageRadar = 0;
    
    setInterval(function() {
       $('#BotonBusquedaSents').trigger('click');
      }, 10000);

    $("#BotonClear").click(function(event) {
	    	layer.removeChildren();
			layer.draw();
			//DrawStats(0,9999);    

		});
 
    $("#BotonRedraw").click(function(event) {
	    	layer.removeChildren();
			elinicio = 0;
			elfinal = 10;                        
			DrawStats(elinicio,elfinal);    
		});

    $("#BotonNew").click(function(event) {
	    	layer.removeChildren();
			layer.draw();
	    	layerDr.removeChildren();
			layerDr.draw();
	    	layerEvents.removeChildren();
			layerEvents.draw();
	    	layerArcs.removeChildren();
			layerArcs.draw();
			
			elinicio = $('#ValIni').val();
			elfinal = $('#ValFin').val(); 
			DrawRadarPatients(elinicio,elfinal);    
		});

    $("#RightChevron").click(function(event) {
	    	pageRadar++;
	    	layer.removeChildren();
			elinicio = 0+ ((pageRadar-1)*10);
			elfinal = (pageRadar)*10;                        
	    	layer.removeChildren();
			layer.draw();
			DrawStats(elinicio,elfinal);   
			$("#pageNumber").html(elinicio+' to '+elfinal+' .Page '+pageRadar);
		});
    $("#LeftChevron").click(function(event) {
	    	pageRadar--;
	    	layer.removeChildren();
			elinicio = 1+ ((pageRadar-1)*10);
			elfinal = (pageRadar)*10;                        
	    	layer.removeChildren();
			layer.draw();
			DrawStats(elinicio,elfinal);   
			$("#pageNumber").html(elinicio+' to '+elfinal+' .Page '+pageRadar);
		});
    
    $("#PhaseNext").click(function(event) {
       if (phase == 3) $("#Attach").trigger('click');
       if (phase < 4) phase++; else 
        {
            //alert ('end of loop');   
            $('#CloseModal').trigger('click');
            $('#SendButton').trigger('click');
        } 
       var ancho = $("#header-modal").width()*(phase-1);
       switch (phase){
        case 1:   $("#selpat").css("color","rgb(61, 147, 224)");
                    $("#seldr").css("color","#ccc");
                    $("#att").css("color","#ccc");
                    $("#addcom").css("color","#ccc");
                    break;     
        case 2:   $("#selpat").css("color","rgb(61, 147, 224)");
                    $("#seldr").css("color","rgb(61, 147, 224)");
                    $("#att").css("color","#ccc");
                    $("#addcom").css("color","#ccc");
                    break;     
        case 3:   $("#selpat").css("color","rgb(61, 147, 224)");
                    $("#seldr").css("color","rgb(61, 147, 224)");
                    $("#att").css("color","rgb(61, 147, 224)");
                    $("#addcom").css("color","#ccc");
                    createPatientReportsNEW ();
                    break;     
        case 4:   $("#selpat").css("color","rgb(61, 147, 224)");
                    $("#seldr").css("color","rgb(61, 147, 224)");
                    $("#att").css("color","rgb(61, 147, 224)");
                    $("#addcom").css("color","rgb(61, 147, 224)");
                    break; 
        default:    alert ('no phase detected');
                    break;
       }
       $("#ContentScroller").animate({ scrollLeft: ancho}, 175);
    });

    $("#PhasePrev").click(function(event) {
        if (phase == 3) $("#Attach").trigger('click');
        if (phase >1) phase--; else 
        {
           // alert ('beginning of loop');    
        } 
       var ancho = $("#header-modal").width()*(phase-1);
       switch (phase){
        case 1:   $("#selpat").css("color","rgb(61, 147, 224)");
                    $("#seldr").css("color","#ccc");
                    $("#att").css("color","#ccc");
                    $("#addcom").css("color","#ccc");
                    break;     
        case 2:   $("#selpat").css("color","rgb(61, 147, 224)");
                    $("#seldr").css("color","rgb(61, 147, 224)");
                    $("#att").css("color","#ccc");
                    $("#addcom").css("color","#ccc");
                    break;     
        case 3:   $("#selpat").css("color","rgb(61, 147, 224)");
                    $("#seldr").css("color","rgb(61, 147, 224)");
                    $("#att").css("color","rgb(61, 147, 224)");
                    $("#addcom").css("color","#ccc");
                    createPatientReportsNEW ();
                    break;     
        case 4:   $("#selpat").css("color","rgb(61, 147, 224)");
                    $("#seldr").css("color","rgb(61, 147, 224)");
                    $("#att").css("color","rgb(61, 147, 224)");
                    $("#addcom").css("color","rgb(61, 147, 224)");
                    break; 
        default:    alert ('no phase detected');
                    break;
       }
       $("#ContentScroller").animate({ scrollLeft: ancho}, 175);
    });
   
    $("#BotonBusquedaPac").click(function(event) {
    	    var IdUs =156;
    	    var UserInput = $('#SearchUser').val();
    	    var UserEmail = $('#SearchEmail').val();
    	    var IdUsFIXED = $('#SearchIdUsFIXED').val();
    	    var MEDID = $('#MEDID').val();
            var queUrl ='getFullUsersLINK.php?Usuario='+UserInput+'&NReports=10&MEDID='+MEDID+'&Email='+UserEmail+'&IdUsFIXED='+IdUsFIXED;
      	    
      	    $('#TablaPac').load(queUrl);
    	    //$('#TablaPac').trigger('click');
    	    $('#TablaPac').trigger('update');
  	    
    });
    
    $("#BotonBusquedaMen").click(function(event) {
    	    var IdUs =156;
    	    var UserInput = $('#SearchUser').val();
    	    var MEDID = $('#MEDID').val();
            var queUrl ='getMessages.php?Usuario='+UserInput+'&NReports=10&MEDID='+MEDID;
      	    
      	    $('#TablaPac').load(queUrl);
    	    //$('#TablaPac').trigger('click');
    	    $('#TablaPac').trigger('update');
  	    
    });
    
    $("#BotonBusquedaPacCOMP").live('click',function() {
	     var UserInput = $('#SearchUserT').val();
	     var UserDOB = '';
	     var IdMed = $('#MEDID').val();
	     var queUrl ='getSearchUsers.php?Usuario='+UserInput+'&UserDOB='+UserDOB+'&IdDoc='+IdMed+'&NReports=3';
    	 $('#TablaPac').load(queUrl);
    	 $('#TablaPac').trigger('update');
    	 $('#BotonBusquedaSents').trigger('click');
    });     

    $("#BotonBusquedaMedCOMP").live('click',function() {
	     var UserInput = $('#SearchDoctor').val();
	     var UserDOB = $('#DoctorEmail').val();
	     var queUrl ='getSearchDoctors.php?Doctor='+UserInput+'&DrEmail='+UserDOB+'&NReports=3';
    	 $('#TablaMed').load(queUrl);
    	 $('#TablaMed').trigger('update');
    }); 

    $("#BotonWizard").click(function(event) {
         phase = 1;
         var ancho = $("#header-modal").width()*0;
         $("#ContentScroller").animate({ scrollLeft: ancho}, 175);
        paciente='';
        destino='';
        TextoS ='<span style="color:grey;">Send </span><span style="color:#54bc00; font-size:30px;">   '+paciente+'   </span><span style="color:grey;"> to </span><span style="color:#22aeff; font-size:30px;">   '+destino+'   </span>';
	    if (paciente>'' && destino>'') TextoS = TextoS + '';
		IsOnCounter = 0;
		IsOnCounter2 = 0;
        $('#TextoSend').html(TextoS);
        $("#selpat").css("color","rgb(61, 147, 224)");
        $("#seldr").css("color","#ccc");
        $("#att").css("color","#ccc");
        $("#addcom").css("color","#ccc");
        $('#BotonModal').trigger('click');
        $('#SearchUserT').value = '';
        $('#SearchDoctor').value = '';
        $('#DoctorEmail').value = '';
        $('#TablaPac').empty();
        $('#TablaMed').empty();
    });
        
    //$("#BotonBusquedaSents").live('click',function() {
    $("#BotonBusquedaSents").click(function(event) {
 	     var IdMed = $('#MEDID').val();
	     var UserDOB = '';
	     var queUrl ='getSents.php?Doctor='+IdMed+'&DrEmail='+UserDOB+'&NReports=3';
    	 //alert (queUrl);
         $('#TablaSents').load(queUrl);
    	 $('#TablaSents').trigger('update');
         //DrawStats();    
    }); 

    $(".ROWREF").live('click',function() {
        var myClass = $(this).attr("id");
		//var queMED = $("#MEDID").val();
		//document.getElementById('UserHidden').value=myClass;
		//alert(document.getElementById('UserHidden').value);
		window.location.replace('patientdetailMED-new.php?IdUsu='+myClass);
		//alert('patientdetailMED-new.php?IdUsu='+myClass);
     	//window.location.replace('patientdetailMED.php');
		}); 

	// Changes for revoking the connection
	$("#BotRevoke,#BotCancel").live('click',function(event){
	
	    var conf=confirm("Are you sure that you want to revoke this referral connection?");
		
		if(conf){
	
		var id=$(this).parents(".CFILASents").attr('id');
		var revokeurl ='revokeReferralConnection.php?id='+id;
		RecTipo = LanzaAjax (revokeurl);
		displayalertnotification('Referral connection revoked. All related data has been deleted!');
	    $("#BotonBusquedaSents").trigger('click');

	   }

        event.stopPropagation();

	
	});

    $("#BotonBusquedaPermit").click(function(event) {
 	     var IdMed = $('#MEDID').val();
	     var UserDOB = '';
	     var queUrl ='getPermits.php?Doctor='+IdMed+'&DrEmail='+UserDOB+'&NReports=3';
    	 $('#TablaPermit').load(queUrl);
    	 $('#TablaPermit').trigger('update');
    });       

    $(".CFILASents").live('click',function() {
     	 var myClass = $(this).attr("id");
 	    // alert (myClass);
 	     
 	     
 	     var IdMed = $('#MEDID').val();
	     var UserDOB = '';
	     var queUrl ='getPermits.php?Doctor='+IdMed+'&DrEmail='+UserDOB+'&NReports=3';
    	 $('#TablaPermit').load(queUrl);
    	 $('#TablaPermit').trigger('update');
		 
    });       
 
    function SendWelcomeMessage(){ 
             var content = $('#WelMes').val();
             var subject='I referred a new patient to you.';
             reportids = reportids.replace(/\s+$/g,' ');
             var IdDocOrigin = $('#MEDID').val();
             var cadena = '<?php echo $domain;?>/GetConnectionId.php?Tipo=1&IdPac='+IdPaciente+'&IdDoc='+IdDoctor+'&IdDocOrigin='+IdDocOrigin;
			 //alert (cadena);
		     RecTipo = LanzaAjax (cadena);
			 //alert (RecTipo);
             //alert ('IdPaciente: '+IdPaciente+' - '+'Sender: '+IdDocOrigin+' - '+'Attachments: '+reportids+' - '+'Receiver: '+IdDoctor+' - '+'Content: '+content+' - '+'subject: '+IdPaciente+' - '+'connection_id: '+RecTipo);
             var cadena='sendMessage.php?sender='+IdDocOrigin+'&receiver='+IdDoctor+'&patient='+IdPaciente+''+'&content='+content+'&subject='+subject+'&attachments='+reportids+'&connection_id='+RecTipo;
             var RecTipo=LanzaAjax(cadena);
             //alert ('Answer of Messg Proc.?: '+RecTipo);
    };

    $('#sendmessages_inbox').live('click',function(){
             var sel=$('#doctorsdetails').find(":selected").attr('id');
             var content=$('#messagecontent_inbox').val().replace(/ /g,"sp0e").replace(/\r\n|\r|\n/g,"br8k");
             var subject=$('#subjectname_inbox').val();
             reportids = reportids.replace(/\s+$/g,' ');
            /*
              var cadena='sendMessage.php?sender=<?php echo $MedID;?>&receiver=<?php echo $otherdoc;?>&patient=<?php echo $USERID;?>'+'&content='+content+'&subject='+subject+'&attachments='+reportids+'&connection_id=<?php echo $referral_id;?>';
             */
			 /*KYLE
             var RecTipo=LanzaAjax(cadena);
             $('#messagecontent_inbox').attr('value','');
             $('#subjectname_inbox').attr('value','');
             displaynotification('status',RecTipo);
            /*
              var cadena='push_server.php?FromDoctorName=<?php echo $IdMEDName;?>&FromDoctorSurname=<?php echo $IdMEDSurname;?>&Patientname=<?php echo $MedUserName; ?>&PatientSurname=<?php echo $MedUserSurname; ?>&IdUsu=<?php echo $USERID;?>&message= New Message <br>From: Dr. <?php echo $IdMEDName;?> <?php echo $IdMEDSurname;?><br>Subject: '+(subject).replace(/RE:/,'')+'&channel='+<?php echo $otherdoc?>;
             */
			 /*KYLE
             var RecTipo=LanzaAjax(cadena);
             reportids='';
             $("#attachment_icon").remove();
             $('#message_modal').trigger('click');
  });
    
        
    $("#SendButton").live('click',function() {
	     // Confirm
		 //alert ('sending...');
	     var subcadena='';
	     var CallPhone = 0;
	     if ($('#c2').attr('checked')=='checked'){ 
	     	subcadena =' (will call phone number also)';
		    CallPhone = 1; 
	     }
		 var IdDocOrigin = $('#MEDID').val();
	     var NameDocOrigin = $('#IdMEDName').val() ;
	     var SurnameDocOrigin = $('#IdMEDSurname').val() ;
	     reportids = reportids.replace(/\s+$/g,' ');
		 var RecTipo;
	     if(destino>'') {
		 var r=confirm('Confirm sending patient '+paciente+' to '+destino+' )?   '+subcadena);
	 	 if (r==true)
	 	 {
	    	
	     	// Update database table (1 or 2) and handle communication with Referral
		 	var cadena = '<?php echo $domain;?>/SendReferral.php?Tipo=1&IdPac='+IdPaciente+'&IdDoc='+IdDoctor+'&IdDocOrigin='+IdDocOrigin+'&NameDocOrigin='+NameDocOrigin+'&SurnameDocOrigin='+SurnameDocOrigin+'&ToEmail='+doctor[0].IdMEDEmail+'&From='+'&Leido=0&Push=0&estado=1'+'&CallPhone='+CallPhone+'&attachments='+reportids;
			//alert (cadena);
		    RecTipo = LanzaAjax (cadena);
			//alert (RecTipo);
			$('#BotonBusquedaSents').trigger('click');
            SendWelcomeMessage();
            
			//alert (RecTipo);
        	// Refresh table in this page accordingly
	 	 }
		 }else if(Nondestino>''){
             var cadena = '<?php echo $domain;?>/SendReferral.php?Tipo=0&IdPac='+IdPaciente+'&IdDoc='+'&IdDocOrigin='+IdDocOrigin+'&NameDocOrigin='+NameDocOrigin+'&SurnameDocOrigin='+SurnameDocOrigin+'&ToEmail='+Nondestino+'&From='+'&Leido=0&Push=0&estado=1'+'&CallPhone='+'&attachments='+reportids;
             //alert(cadena);
             RecTipo = LanzaAjax (cadena);
             //alert (RecTipo);
             SendWelcomeMessage();
		 }
		 //alert(RecTipo);
		 var res=parseInt(RecTipo);
		 if(res){
		 if(res==3){
		 alert('Referral request already present for this patient!');
		 }else{
		 displayalertnotification('Referral request sent!');
		 }}
		 else{
		 
		 alert('Although this email is already present in the system, your referral request has been sent. Please make sure you use the filter button for normal referral process!');
		 }
		  //alert(RecTipo);
		 //}
		 
		 $('#TextoSend').html('');
		 destino='';
		 Nondestino='';
		 $("#attachment_icon").hide();
    });     


 
    $(".CFILADoctor").live('click',function() {
	    //$("#attachment_icon").hide();
     	var myClass = $(this).attr("id");
	 	getMedCreator(myClass);
	 	destino = "Dr. "+doctor[0].Name+" "+doctor[0].Surname; 
	 	IdDoctor = doctor[0].id;
	    //alert (destino);	
		Nondestino='';
	    TextoS ='<span style="color:grey;">Send </span><span style="color:#54bc00; font-size:30px;">   '+paciente+'   </span><span style="color:grey;"> to </span><span style="color:#22aeff; font-size:30px;">   '+destino+'   </span>';
	    if (paciente>'' && destino>'') TextoS = TextoS + '';
		$('#TextoSend').html(TextoS);
        $('#PhaseNext').trigger('click');
    }); 	

    $(".CFILAMODAL").live('click',function() {
	    //$("#attachment_icon").hide();
     	var myClass = $(this).attr("id");
	 	getUserData(myClass);
	 	paciente = user[0].Name+" "+user[0].Surname; 
	 	IdPaciente = user[0].Identif;
	    if(Nondestino>'')
		 TextoS ='<span style="color:grey;">Send </span><span style="color:#54bc00; font-size:30px;">   '+paciente+'   </span><span style="color:grey;"> to </span><span style="color:#22aeff; font-size:30px;">   '+Nondestino+'   </span>';
		else 
		 TextoS ='<span style="color:grey;">Send </span><span style="color:#54bc00; font-size:30px;">   '+paciente+'   </span><span style="color:grey;"> to </span><span style="color:#22aeff; font-size:30px;">   '+destino+'   </span>';
		if (paciente>'' && destino>'') TextoS = TextoS + '<p><span><input type="button" class="btn btn-info" value="Attach Reports" id="AttachButton" style="margin-top:10px;"></p>';
		else if (paciente>'' && Nondestino>'') TextoS = TextoS + '';
		$('#TextoSend').html(TextoS);
        $('#PhaseNext').trigger('click');
    }); 

    //Changes for adding a non-user

	$("#AddNonUser").live('click',function() {
			//var myClass = $(this).attr("id");
			//getMedCreator(myClass);
			//$("#attachment_icon").hide();
			destino='';
			var docId=$("#DoctorEmail").val();
			Nondestino = docId; 
			//IdDoctor = doctor[0].id;
			//alert (destino);	
		 TextoS ='<span style="color:grey;">Send </span><span style="color:#54bc00; font-size:30px;">   '+paciente+'   </span><span style="color:grey;"> to </span><span style="color:#22aeff; font-size:30px;">   '+Nondestino+'   </span>';
		if (paciente>'' && destino>'') TextoS = TextoS + '';
		else if (paciente>'' && Nondestino>'') TextoS = TextoS + '';
		$('#TextoSend').html(TextoS);
        $('#PhaseNext').trigger('click');
		});
       
	 
	 
	//Changes for attaching reports
	$("#AttachButton").live('click',function(){
	createPatientReports();
	setTimeout(function(){$("#report_modal").trigger('click');},500);
	
	});

        
    $(".CheckContainer").live('mousedown',function(){
        var myClass = $(this).attr("id");
    
        var recoZ = 0;
        var Yaexiste = 0;


        while (recoZ < IsOnCounter)
        {
           if (IsOn[recoZ] == myClass ) 
            {
                IsOn[recoZ] = 0;
                IsOnCounter2--; 
                Yaexiste = 1;
            }
            recoZ++;
        }
        if (Yaexiste == 0) 
        { 
            IsOnCounter++ ; 
            IsOnCounter2++ ; 
            IsOn[IsOnCounter] = myClass;
        }
        $('#NumberRA').html (IsOnCounter2+' Reports Attached');
        if (IsOnCounter2 >0) 
        {
            $("#attachment_icon").css("display","visible");
            $("#attachment_icon").css("color","#22aeff");
            $("#attachment_icon").addClass("icon-spin");
       }
        else
        {
           // $("#attachment_icon").css("display","none");
            $("#attachment_icon").css("color","#ccc");
            $("#attachment_icon").removeClass("icon-spin");
        }
        
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
				
				 
				}
				
			
				
	});
	// alert(reportids);
	var conf=false;
    if(reportids>'')
//		conf=confirm("Confirm Attachments");
        conf=true;
	if(conf){
        //alert ('confirmed');
	$("#AttachButton").attr('value','Reports Attached');
	//$("#attachment_icon").show();
	
	//$("#attachreportdiv").append('<i id="attachment_icon" class="icon-paper-clip" style="margin-left:10px"></i>');
	//alert(reportids);
	}else{
	reportids='';
	for (i = 0 ; i < reportcheck.length; i++ ){
				
		document.getElementById(reportcheck[i]).checked = false;
				
	}
	reportcheck.length=0;
	//$("#Reply").trigger('click');
	}
	setTimeout(function(){$("#report_modal").trigger('click');},50);
		
	});
	
  function createPatientReports(){
		var ElementDOM ='All';
		var EntryTypegroup ='0';
		var Usuario = IdPaciente   //$('#userId').val();
		var MedID =$('#MEDID').val();
		
		var queUrl ='CreateAttachmentStream.php?ElementDOM=na&EntryTypegroup='+EntryTypegroup+'&Usuario='+Usuario+'&MedID='+MedID;
      	//var queUrl ='CreateAttachmentStream.php?ElementDOM=na&EntryTypegroup='+EntryTypegroup+'&Usuario='+Usuario+'&MedID='+MedID+'&Reports=1226';
      	$("#attachments").load(queUrl);
    	$("#attachments").trigger('update');
		$("#attachments").show();
  
  }	
  function createPatientReportsNEW (){
		var ElementDOM ='All';
		var EntryTypegroup ='0';
		var Usuario = IdPaciente   //$('#userId').val();
		var MedID =$('#MEDID').val();
		
		var queUrl ='CreateAttachmentStreamNEW.php?ElementDOM=na&EntryTypegroup='+EntryTypegroup+'&Usuario='+Usuario+'&MedID='+MedID;
      	//var queUrl ='CreateAttachmentStream.php?ElementDOM=na&EntryTypegroup='+EntryTypegroup+'&Usuario='+Usuario+'&MedID='+MedID+'&Reports=1226';
      	$("#Phase3Container").load(queUrl);
    	$("#Phase3Container").trigger('update');
		$("#Phase3Container").show();
  
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


        
    function getBlocks(serviceURL) {
    	$.ajax(
           {
           url: serviceURL,
           dataType: "json",
           async: false,
           success: function(data)
           {
           	blocks = data.items;
           	//$('#Wait1').hide(); 
           	//alert ("PASA");
           	//alert (employees);
           }
         });
     }        

    $(".CFILA").live('click',function() {
     /*	var myClass = $(this).attr("id");
     	var NombreEnt = $('#NombreEnt').val();
     	var PasswordEnt = $('#PasswordEnt').val();
     	//window.location.replace('patientdetail.php?Nombre='+NombreEnt+'&Password='+PasswordEnt+'&IdUsu='+myClass);
     	//alert (myClass);
        $('#BotonModal').trigger('click');
      */
	  /*KYLE
    });
    
    $(".view-button").live('click',function() {
     	var myClass = $(this).attr("id");
     	$('#queId').attr("value",myClass);
     	var NameMed = $('#IdMEDName').val();
     	var SurnameMed = $('#IdMEDSurname').val();
     	var PasswordEnt = $('#PasswordEnt').val();
        var MEDID = $('#MEDID').val();
        var MEDEmail = $('#IdMEDEmail').val();
    
        $('#BotonModal').trigger('click');
    });
  
    $("#ConfirmaLink").live('click',function() {
     	var To = $('#queId').val();
    	getUserData(To);
    
    	if (user[0].email==''){
        	var IdCreador = user[0].IdCreator;
	    	
	    	alert ('orphan user . Creator= '+IdCreador);
	    	
	    	getMedCreator(IdCreador);

	    	var NameMed = $('#IdMEDName').val();
	    	var SurnameMed = $('#IdMEDSurname').val();
	    	var From = $('#MEDID').val();
	    	var FromEmail = $('#IdMEDEmail').val();
	    	var Subject = 'Request conection from Dr. '+NameMed+' '+SurnameMed;
        
	    	var Content = 'Dr. '+NameMed+' '+SurnameMed+' is requesting to establish connection with your patient named: '+user[0].Name+' '+user[0].Surname+' (UserId:  '+To+'). Please confirm, or just close this message to reject.';
    	
	    	//alert (Content);
	    	var destino = "Dr. "+doctor[0].Name+" "+doctor[0].Surname; 
	    	var cadena = '<?php echo $domain;?>/MsgInterno.php?Tipo=1&IdPac='+user[0].Identif+'&To='+doctor[0].id+'&ToEmail='+doctor[0].IdMEDEmail+'&From='+From+'&FromEmail='+FromEmail+'&Subject='+Subject+'&Content='+Content+'&Leido=0&Push=0&estado=1';
	    	
	    	//alert (cadena);
	    	var RecTipo = LanzaAjax (cadena);
	    	
	    	//alert (RecTipo);
    	}
    	else
    	{
      	var NameMed = $('#IdMEDName').val();
     	var SurnameMed = $('#IdMEDSurname').val();
    	var From = $('#MEDID').val();
        var FromEmail = $('#IdMEDEmail').val();
        var Subject = 'Request conection ';
        
        var Content = 'Dr. '+NameMed+' '+SurnameMed+' is requesting to establish connection with you (UserId:  '+To+'). Please confirm, or just close this message to reject.';
    	
    	var cadena = '<?php echo $domain;?>/MsgInterno.php?Tipo=0&IdPac=0&To='+To+'&ToEmail='+user[0].email+'&From='+From+'&FromEmail='+FromEmail+'&Subject='+Subject+'&Content='+Content+'&Leido=0&Push=0&estado=1';
				
		//alert (cadena);
		var RecTipo = 'Temporal';
	                     $.ajax(
                                {
                                url: cadena,
                                dataType: "html",
                                async: false,
                                complete: function(){ //alert('Completed');
                                
                                },
                                success: function(data)
                                {
                                if (typeof data == "string") {
                                RecTipo = data;
                                }
                                }
                                });
                         
	   //alert (RecTipo);	    
	   //var Content = 'Dr. '+NameMed+' '+SurnameMed+' is requesting to establish connection with you (UserId:  '+To+'). Please click the button: </br><input type="button" href="www.inmers.com/ConfirmaLink?User='+To+'&Doctor='+From+'&Confirm='+RecTipo+'" class="btn btn-success" value="Confirm" id="ConfirmaLink" style="margin-top:10px; margin-bottom:10px;"> </br> to confirm, or just close this message to reject.';
	   
	   //EnMail(user[0].email, 'MediBANK Link Request', Content);  // NO SE USA AQUÍ, PERO SI FUNCIONA PERFECTAMENTE PARA ENVIAR MENSAJES DE EMAIL DESDE JAVASCRIPT
	   }
	   
	   $('#CloseModal').trigger('click');
	   $('#BotonBusquedaPac').trigger('click');

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
    
  /*
  	$("#datatable_1 tbody").click(function(event) {
  		alert ('click');
		$(oTable.fnSettings().aoData).each(function (){
			$(this.nTr).removeClass('row_selected');
		});
		$(event.target.parentNode).addClass('row_selected');
	});
  */
    //alert ('ok');
    
   
/*
    setInterval(function() {
   
  
	 //  alert ('Redraw now');
	 // "bDestroy": true,
	 // "bRetrieve": true,
	

	$('#datatable_1').dataTable( {
		"bProcessing": true,
		"bDestroy": true,
		"sAjaxSource": "getBLOCKS.php"
	} );
						//location.reload();
   				 		//$('#loaddiv').fadeOut('slow').load('reload.php').fadeIn("slow");
   				 		
   				 		}, 10000);  
  				 		
  */  
  /*KYLE
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

  
  function EnMail (aQuien, Tema, Contenido)
  {
	  var cadena = '<?php echo $domain;?>/EnMail.php?aQuien='+aQuien+'&Tema='+Tema+'&Contenido='+Contenido;
	  var RecTipo = 'Temporal';
	  $.ajax(
      {
          url: cadena,
          dataType: "html",
          async: false,
          complete: function(){ //alert('Completed');
               },
          success: function(data)
               {
               if (typeof data == "string") {
               RecTipo = data;
                      }
               }
      });
	  //alert (RecTipo);	    
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

  
  
/* VERDES
#16ff00
#12cb00
#0e9a00
*/
/* VARIOS COL
#54bc00
#ffdb14
#6cb1ff
*/
/*KYLE
 	window.onload = function(){		
	 	
	 	var PaquetesSI = parseInt($('#PaquetesSI').val());
	 	var PaquetesNO = parseInt($('#PaquetesNO').val());
	 	var PTotal = PaquetesSI + PaquetesNO;
	 	var porcenSI = Math.floor((PaquetesSI*100)/PTotal);
	 	var porcenNO = Math.floor((PaquetesNO*100)/PTotal);
	 	Morris.Donut({
			element: 'MiDonut',
			colors: ['#0fa200','#ff5d5d'],
			formatter: function (y) { return  y +' %' },
			data: [
				{label: "IN USE", value: porcenSI},
				{label: "Not used", value: porcenNO}
				]
			});
		};
  
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

function queFuente3 ($numero)
{
$queF=10;
switch ($numero)
{
	case ($numero>999 && $numero<9999):	$queF = 40;
										break;
	case ($numero>99 && $numero<1000):	$queF = 60;
										break;
	case ($numero>0 && $numero<100):	$queF = 80;
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
