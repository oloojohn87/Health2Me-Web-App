<?php
session_start();
set_time_limit(180);
require("environment_detail.php");
require("push_server.php");
require_once("displayExitClass.php");
//require_once("push_server.php");
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];
 


				$NombreEnt = $_SESSION['Nombre'];
				$PasswordEnt = $_SESSION['Password'];
				$Acceso = $_SESSION['Acceso'];
				$IdUsu = $_GET['IdUsu'];
				$IdMed = $_SESSION['MEDID'];
				$MedID = $IdMed;
				$pass=$_SESSION['decrypt'];	
				$privilege=$_SESSION['Previlege'];		

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

//$result = mysql_query("SELECT * FROM usuarios where IdUsFIXEDNAME='$NombreEnt' and IdUsRESERV='$PasswordEnt'");
$result = $con->prepare("SELECT * FROM usuarios where Identif=?");
$result->bindValue(1, $IdUsu, PDO::PARAM_INT);
$result->execute();


$count=$result->rowCount();
$row = $result->fetch(PDO::FETCH_ASSOC);
$success ='NO';
if($count==1){
	$success ='SI';
	$USERID = $row['Identif'];
//	$MedUserEmail= $row['IdMEDEmail'];
	$MedUserName = $row['Name'];
	$MedUserSurname = $row['Surname'];
	$IdUsFIXED = $row['IdUsFIXED'];
	$IdUsFIXEDNAME = $row['IdUsFIXEDNAME'];
	$IdUsRESERV = $row['IdUsRESERV'];
	$IdUsPassword = $row['Password'];
	$email = $row['email'];
	$UserEmail = $email;
	
//	$MedUserLogo = $row['ImageLogo'];
	
}
else
{
$exit_display = new displayExitClass();

$exit_display->displayFunction(2);
die;
}

$result = $con->prepare("SELECT * FROM doctors where id=?");
$result->bindValue(1, $IdMed, PDO::PARAM_INT);
$result->execute();

$count=$result->rowCount();
$row = $result->fetch(PDO::FETCH_ASSOC);
//$success ='NO';
if($count==1){
	//$success ='SI';
	//$MedID = $row['id'];
	$IdMEDEmail= $row['IdMEDEmail'];
	$IdMEDName = $row['Name'];
	$IdMEDSurname = $row['Surname'];
	$MedLogo = $row['ImageLogo'];
	
}
//Global variable for blind reports.
//$blindReportId=array();
CreaTimeline($IdUsu,$IdMed,$pass);

/*
$showreferralsection=0;
$otherdoc=0;
$otherdocname='';
$otherdocSurename='';
$referral_id=0;
$referral_stage=1;
$fechaconfirm=0;
$attachments_dld=0;
$sql="SELECT * FROM doctorslinkdoctors where Idpac ='$IdUsu' and (IdMED='$IdMed' or IdMED2='$IdMed') and estado=2 ";
$q = mysql_query($sql);
if($q){
	$cnt=mysql_num_rows($q);
	if($cnt>1){
		$errormessage='Error in Referral Communications Area for this patient due to multiple referral connections.Please Contact Health2me Support!';
		$showreferralsection=420;
		//echo $errormessage;
		//echo "<br>\n"; 	
		//echo "<h2><a href='".$domain."'>Return to Health2me Dashboard</a></h2>";
		//die;
	}
	//The below code has been written based on the assumption that there will always be only one entry in DLD on this patient. That the patient
	//has been referred only once
	else if($cnt==1){
	    $row=mysql_fetch_assoc($q);
		if($row['IdMED2']==$IdMed){
		   $otherdoc=$row['IdMED'];
			$showreferralsection=1;
			$referral_id=$row['id'];
			
		}else if($row['IdMED']==$IdMed) {
			$otherdoc=$row['IdMED2'];
			$showreferralsection=2;
			$referral_id=$row['id'];			
		}
		$getname = mysql_query("select Name,Surname,IdMEDEmail from doctors where id='$otherdoc'");
		$row11 = mysql_fetch_array($getname);
		$otherdocname = $row11['Name'];
	    $otherdocSurname = $row11['Surname'];
		if($otherdocname=='' and $otherdocSurname=='')
			$otherdoctoremail=$row11['IdMEDEmail'];
		$fechaconfirm=$row['FechaConfirm'];
		$attachments_dld=$row['attachments'];
		}
		
		
	//echo "referral is present";
}

//Update the referrral stages
if($showreferralsection!=0){
//echo "".$referral_id."<br>";
$doc_id=0;
if($showreferralsection==1){
$doc_id=$IdMed;
}else{
$doc_id=$otherdoc;
}
$getstage = mysql_query("select stage from referral_stage where referral_id='$referral_id'");
$row13 = mysql_fetch_array($getstage);
$referral_stage=$row13['stage'];
if($referral_stage==1){
//Code for appointment from events table

$getschedule=mysql_query("select * from events where userid='$doc_id' and patient='$USERID' and start>'$fechaconfirm'");
$cnt=mysql_num_rows($getschedule);

if($cnt>=1){
mysql_query("update referral_stage set stage=2 where referral_id='$referral_id'");
$referral_stage=2;
Push_notification($IdMed,"Referral Appointment Stage completed!");
Push_notification($otherdoc,"Referral Appointment Stage completed!");

}

}
if($referral_stage==2){
	//Code for information review from bpinview
	$reportviewed=false;
	if($attachments_dld!=0){
		$report_id=explode(" ",$attachments_dld);
		$cntt=count($report_id);
		$i=0;
		//Remember to add the check for date of the reports viewed. It should always be greater than appointment schedule date
		while($cntt>0){
		$getinfo = mysql_query("select id from bpinview where viewIdUser='$USERID' and viewIdMed='$doc_id' and content='Report Viewed' and IDPIN='$report_id[$i]'");
		$cnt_info=mysql_num_rows($getinfo);
		if($cnt_info)
			$reportviewed=true;
		else
			$reportviewed=false;
		$i++;
		$cntt--;
		}
		/*$getinfo = mysql_query("select id from bpinview where viewIdUser='$USERID' and viewIdMed='$doc_id' and content='Report Viewed'");
		$cnt_info=mysql_num_rows($getinfo);*/
		//echo "".$cnt_info."<br>";
	/*	if($reportviewed)
		{
		mysql_query("update referral_stage set stage=3 where referral_id='$referral_id'");
		$referral_stage=3;
		Push_notification($IdMed,"Referral report view stage completed!");
		Push_notification($otherdoc,"Referral report view stage completed!");
		}
	}else {
		$getinfo = mysql_query("select id from bpinview where viewIdUser='$USERID' and viewIdMed='$doc_id' and content='Report Viewed'");
		$cnt_info=mysql_num_rows($getinfo);
		//echo "".$cnt_info."<br>";
		if($cnt_info>3)
		{
		mysql_query("update referral_stage set stage=3 where referral_id='$referral_id'");
		$referral_stage=3;
		Push_notification($IdMed,"Referral report view stage completed!");
		Push_notification($otherdoc,"Referral report view stage completed!");
		}
		
	}

}

}*/

//BLOCKSLIFEPIN $result = mysql_query("SELECT * FROM blocks");
//$result = mysql_query("SELECT * FROM lifepin");

?>
<!DOCTYPE html>
<html lang="en"><head>
    <meta charset="utf-8">
    <title>Health2me Patient Detail</title>
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
    <link rel="stylesheet" href="css/jvInmers.css">
    <link rel="stylesheet" type="text/css" href="css/tooltipster.css" />
    <?php
    if ($_SESSION['CustomLook']=="COL") { ?>
        <link href="css/styleCol.css" rel="stylesheet">
    <?php } ?>
    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    <!--[if lte IE 8]><script type="text/javascript" src="/js/excanvas.min.js"></script><![endif]-->

    <!-- Le fav and touch icons -->
    <link rel="shortcut icon" href="/images/icons/favicon.ico">
	<!--<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
	<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
	<script src="http://code.jquery.com/jquery-migrate-1.2.1.js"></script>
	<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>-->
	
	<script src="js/jquery.min.js"></script>
    <script src="js/jquery-ui.min.js"></script>
	
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

<!--  <body onload="$('.note').trigger('click'); $('.TABES').children().trigger('click');"> -->
  <body onload=" $('.TABES:eq(9)').click();">

			<input type="hidden" id="MEDID" Value="<?php echo $IdMed; ?>">	
	    	<input type="hidden" id="IdMEDEmail" Value="<?php echo $IdMEDEmail; ?>">	
	    	<input type="hidden" id="IdMEDName" Value="<?php echo $IdMEDName; ?>">	
	    	<input type="hidden" id="IdMEDSurname" Value="<?php echo $IdMEDSurname; ?>">	
	    	<input type="hidden" id="IdMEDLogo" Value="<?php echo $MedLogo; ?>">	
	     	<!-- <input type="hidden" id="USERDID" Value="<?php echo $USERID; ?>">	-->
	<!--Header Start-->
	
	<div class="header" >
    		
           <a href="index.html" class="logo"><h1>Health2me</h1></a>
           
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
              <li><a href="UserDashboard.php"><i class="icon-globe"></i> Home</a></li>
              
              <<li><a href="medicalConfiguration.php"><i class="icon-cog"></i> Settings</a></li>
              <li><a href="logout.php"><i class="icon-off"></i> Sign Out</a></li>
            </ul>
            </div>
          </div>
          <!--Button User END-->  

          
          </div>
    </div>
    <!--Header END-->

   
    <!--Content Start-->
	<div id="content" style="padding-left:0px; background: #F9F9F9; height:2100px;">
	<!--- VENTANA MODAL  This has been added for enabling blind report access ---> 
	 
   	 <button id="BotonModal" data-target="#header-modal0" data-toggle="modal" class="btn btn-warning" style="display: none;">Modal with Header</button> 
   	  <div id="header-modal0" class="modal fade hide" style="display: none;" aria-hidden="true">
         <div class="modal-header">
             <button class="close" type="button" data-dismiss="modal">×</button>
                  To unlock please see below options
         </div>
         <div class="modal-body">
         	 <p>----------------------------------------------------------------------------------------------------</p>
             <p id="Thisreport">**Click on "This report" in case you want to unlock only this report.**</p>
             
             <p id="Allreport">**Click on "All reports" in case you want to unlock all reports of this user.**</p>
         
			 <p id="TextoSend" style="text-align:center;"></p>
		     <p>----------------------------------------------------------------------------------------------------</p>
         </div>
		 
         <input type="hidden" id="Idpin">
        <!-- <input type="hidden" id="docId" value="<?php echo $IdMed; ?>"/> -->
         <input type="hidden" id="userId" value="<?php echo $IdUsu; ?>" />
         <div class="modal-footer">
	         <input type="button" class="btn btn-success" value="This report" id="ConfirmaLink">
	         <input type="button" class="btn btn-success" value="All reports" id="ConfirmaLinkAll">
             <a href="#" class="btn btn-primary" data-dismiss="modal" id="CloseModallink">Close</a>
         </div>
      </div>  
	  <!--- VENTANA MODAL  ---> 	
	

     	  <!--- History  ---> 
   	  <button id="BotonModal1" data-target="#header-modal1" data-toggle="modal" class="btn btn-warning" style="display: none;">Modal with Header</button>
   	  <div id="header-modal1" class="modal fade hide" style="display: none; height:470px; width:800px; margin-left:-400px;" aria-hidden="true">
         <div class="modal-header">
             <button class="close" type="button" data-dismiss="modal">×</button>
                 <div id="InfB" >
	                 	<h4>Report Tracking History</h4>
                 </div>
         </div>
		 
						<!--   Pop Up For Maps 
						<button id="BotonModalMap" data-target="#header-modalMap" data-toggle="modal" class="btn btn-warning" style="display: none;">Modal with Header</button>
							<div id="header-modalMap" class="modal hide" style="display: none; height:450px; width:500px; margin-left:-200px; margin-right:-200px;" aria-hidden="true">
												
							</div>
						<!--  End of Pop Up For Maps -->
      
      
         <div class="modal-body" id="ContenidoModal22" style="height:320px;">
			<div id="InfoIDPaciente">
            </div>
			<div id="SeccionBusqueda"> <!--- SECCIÓN DE BÚSQUEDA ---->
		        
				<div id="VacioAUNViewers" style=" width:35%; margin: 0 auto; margin-top:10px; border: 1px SOLID #CACACA; ">
					<table class="table table-mod" id="TablaPacMODALViewers">
					</table> 
				</div>
			</div>
		 
         
			<div id="SeccionBusqueda"> <!--- SECCIÓN DE BÚSQUEDA ---->
		        
				<div id="VacioAUN" style=" width:98.5%; margin-top:10px; border: 1px SOLID #CACACA; text-align:center;">
					<table class="table table-mod" id="TablaPacMODAL" >
					</table> 
				</div>
			</div>						<!--- SECCIÓN DE BÚSQUEDA ---->
         
         
         </div>
                 
         <div class="modal-footer">
	         <!--<input type="button" class="btn btn-success" value="Confirm" id="ConfirmaLink">--->
             <a href="#" class="btn btn-primary" data-dismiss="modal" id="CloseModal22">Close</a>
         </div>
      </div>  
	  <!--- Report History  ---> 
  
       <!--   Pop Up For Maps -->
						<button id="BotonModalMap" data-target="#header-modalMap" data-toggle="modal" class="btn btn-warning" style="display: none;">Modal with Header</button>
							<div id="header-modalMap" class="modal fade hide" style="display: none; height:450px; width:500px; margin-left:-200px; margin-right:-200px;" aria-hidden="true">
									
							 <!--<div class="modal-footer">
								 <!--<input type="button" class="btn btn-success" value="Confirm" id="ConfirmaLink">
								 <a href="javascript:void(0)" class="btn btn-primary" data-dismiss="modal" id="CloseModal23">Close</a>
							 </div> --->
							</div>
   
      <!--   Pop Up For Maps --> 
	 <!-- <div id="content" style="background: #F9F9F9; padding-left:0px;"> -->
	 <!--SpeedBar Start--->
     <div class="speedbar">
     <div class="speedbar-content">
     
     <ul class="menu-speedbar">
		
         <?php if ($privilege==1)
				 echo '<li><a href="UserDashboard.php" >Dashboard</a></li>';
		 ?>
    	 <li><a href="patientdetailMED-new.php?IdUsu=<?php echo $UserID;?>" class="act_link" >Medical Records</a></li>
         <!--<li><a href="medicalConfiguration.php">Configuration</a></li>-->
         <li><a href="logout.php" style="color:yellow;">Sign Out</a></li>
     </ul>

     
     </div>
     </div>
     <!--SpeedBar END-->

     <!--Search Start-->   
	 <!--
     <div class="search">
     <form class="search-form">
     	<input type="text" name="" value="" placeholder="Enter keywords">
     </form>
	 <div class="clear"></div>	
     </div>
     -->
     <!--Search END-->
     
     <?php             // AREA PRINCIPAL DE ASOCIACIÓN DE LA INFORMACIÓN DEL PACIENTE  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
     //$IdUsu=131;
     
     $sql=$con->prepare"SELECT * FROM usuarios where Identif =?");
	 $sql->bindValue(1, $IdUsu, PDO::PARAM_INT);
	 
     $q = $sql->execute();
     $row = $sql->fetch(PDO::FETCH_ASSOC);
     
     $Name = $row['Name'];
     $Surname = $row['Surname'];
     
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
     
     $Tipo[999]='N/A';
     // Meter clases en un Array
     $Clase[999]='Episode';
     $Clase[0]='Episode';
     $Clase[1]='Check or Preventive';
     $Clase[2]='Isolated Report';
     $Clase[3]='Drug Data';
     
  
     
     ?>

     <!--CONTENT MAIN START
	  
      <div id="overlay" ><img id="progressbar" style="margin-top:15px;margin-left:500px;width:300px" src="/images/load/36.gif"/></div> -->
	<!--<div id="progress-bar" style="margin-top:15px;margin-left:0px;" class="progress progress-info">
    <div class="bar" style="width: 1%;"></div> -->
	<!--Commented by Javier
	<div id="progressstatus" style="margin-left:400px;text-shadow:none;color:black;">Loading and decrypting clinical content please wait...</div>
	<div id="progressbar" style="margin-top:15px;margin-left:0px;"><div style="margin-left:-50px;text-shadow:none;color:white;" class="progress-label"></div></div>
	    Commented by Javier   -->
	
    <!--</div>-->
     <!--CONTENT MAIN START-->
     <div class="content" >
     	  <!--- VENTANA MODAL  ---> 
   	  <!--<button id="BotonModal" data-target="#header-modal" data-toggle="modal" class="btn btn-warning" style="display: none;">Modal with Header</button>-->
   	  <div id="header-modal3" class="modal hide" style="display: none; height:470px;" aria-hidden="true">
         <div class="modal-header">
             <button class="close" type="button" data-dismiss="modal">×</button>
                 <h4>Set Classification</h3>
         </div>
         
         <div class="modal-body" id="ContenidoModal" style="height:320px;">
             <div  id="RepoThumb" style="width:70px; float:right; -webkit-box-shadow: 3px 3px 14px rgba(25, 25, 25, 0.5); -moz-box-shadow:3px 3px 14px rgba(25, 25, 25, 0.5); box-shadow:3px 3px 14px rgba(25, 25, 25, 0.5);"></div>
           <div class="ContenDinamico">
	         <p><H4>Class:  </H3>
	               <div class="formRight" stytle="width:50px;">
		               <select name="Clases" id="Clases" data-placeholder="Select Class (reason for this data ?)" class="chzn-select chosen_select" multiple tabindex="5" >
                            <option value=""></option>
                            <optgroup label="Episodes (user folder)">
                              <option>Epi 1</option>
                              <option>Epi 2</option>
                            <optgroup>
                            <optgroup label="Routine / Checks">
                              <option>Routine / Checks</option>
                            </optgroup>
                            <optgroup label="Isolated Data">
                              <option>Isolated Data</option>
                            </optgroup>
                            <optgroup label="Drug Related Data">
                              <option>Drug Related Data</option>
                           </optgroup>
                          </select>
                       </div>   
              <button id="BotonAddClase"  class="btn btn-small" style=""><i class="icon-plus-sign"></i>Add New Episode (Class)</button>
 
	         </p>
	         <p><H4>Type:  </H3>
	         	    <div class="formRight">
		               <select name="Tipos" id="Tipos" data-placeholder="Select Type (is it a report, an image, etc, ?)" class="chzn-select chosen_select" multiple tabindex="5">
                            <option value=""></option>
                            <optgroup label="Imaging Tests">
                              <option>Epi 1</option>
                              <option>Epi 2</option>
                            <optgroup>
                            <optgroup label="Lab Tests">
                              <option>Routine / Checks</option>
                            </optgroup>
                            <optgroup label="Physician Reports">
                              <option>Isolated Data</option>
                            </optgroup>
                         </select>
                    </div>   

	         </p>
	         <p><H5>Clinical Area:  </H3></p>
         </div>
         </div>
         <input type="hidden" id="queId">
         <div class="modal-footer">
	         <!--<input type="button" class="btn btn-success" value="Confirm" id="ConfirmaLink">--->
             <a href="#" class="btn btn-success" data-dismiss="modal" id="GrabaDatos">Update Data</a>
             <a href="#" class="btn btn-primary" data-dismiss="modal" id="CloseModal">Close</a>
         </div>
      </div>  
	  <!--- VENTANA MODAL  ---> 
	  
     	  <!--- VENTANA MODAL NUMERO 2 ---> 
   	  <!--<button id="BotonModal" data-target="#header-modal" data-toggle="modal" class="btn btn-warning" style="display: none;">Modal with Header</button>-->
   	  <div id="header-modal2" class="modal hide" style="display: none; height:470px;" aria-hidden="true">
         <div class="modal-header">
             <button class="close" type="button" data-dismiss="modal">×</button>
                 <h4>Upload New Report</h3>
                 <input type="hidden" id="URLIma" value="zero"/>
         </div>
         
         <div class="modal-body" id="ContenidoModal2" style="height:320px;">
             <div  id="RepoThumb" style="width:70px; float:right; -webkit-box-shadow: 3px 3px 14px rgba(25, 25, 25, 0.5); -moz-box-shadow:3px 3px 14px rgba(25, 25, 25, 0.5); box-shadow:3px 3px 14px rgba(25, 25, 25, 0.5);"></div>
           <div class="ContenDinamico2">
        
           <!-- <a href="#" class="btn btn-success" id="ParseReport" style="margin-top:10px; margin-bottom:10px;">Parse this report now.</a> -->

           		<form action="upload_fileUSER.php?queId=<?php echo $IdUsu ?>&from=0" method="post" enctype="multipart/form-data">
	           		<label for="file">Report:</label>
	           		<input type="file" class="btn btn-success" name="file" id="file" style="margin-right:20px;"><br>


            </div>  

         </div>
         <div class="modal-footer">
	         <!--<input type="button" class="btn btn-success" value="Confirm" id="ConfirmaLink">--->
             <!--<a href="#" class="btn btn-success" data-dismiss="modal" id="GrabaDatos">Update Data</a>-->
             <input type="submit" class="btn btn-success" name="submit" value="Submit">
             <a href="#" class="btn btn-primary" data-dismiss="modal" id="CloseModal" >Close</a>
             
             	</form>

         </div>
       </div>
	  <!--- VENTANA MODAL NUMERO 2  ---> 
     
		 <!--Pop up for records board -->
     <div id="header-modal4" class="modal hide" style="display: none; height:700px;width:1380px;margin-left:-700px;margin-top:-350px" aria-hidden="true">
         <div class="modal-header">
             <button class="close" type="button" data-dismiss="modal">×</button>
                 <h4>Records Board</h3>
         </div>
         
         <div class="modal-body" id="ContenidoModal4" style="max-height:550px" >
		 <label align="right" id="verified_count_label" style="color:red;"></label>
		 <center>
			<table>
				<tr>
									
					<td><input type="button"  id="previous" value = "Previous" onClick="previous_click();"></td>
					<td><div class="grid-content" id="AreaConten1">
							<img id="ImagenAmp1" src="">
						</div>
					</td>
									
					<td><input type="button"  id="next"   value="Next" onClick="next_click();"></td>
			    </tr>
			</table>
             
		</center>
         </div>
         <div class="modal-footer">
	         
             <a href="#" class="btn btn-primary" data-dismiss="modal" id="CloseModal" >Close</a>
             
             	

         </div>
     </div>
	   <!--End Pop up for records board -->
     
        <div class="grid" class="grid span4" style="width:1000px; margin: 0 auto; margin-top:30px; height:2000px; padding-top:30px; padding-left:20px;">
	    
		<div style="display:none"><a href="#" id="add-regular">Add regular notification</a></div>
	    <!--PROGRESS BAR Box Start-->
        <div class="grid" style="width:97%; margin-top:-20px; margin-bottom:10px;" id="encryptbox">
        	<div class="grid-content overflow">
				<div id="progressstatus" style="width:80%; margin:0 auto; text-shadow:none; color:black; text-align:center;">Loading and decrypting clinical content please wait...</div>
				<div id="progressbar" style="margin-top:15px; height:20px; width:80%; margin:0 auto; text-align:center;"><div style="margin-left:0px; margin-top:-5px; padding:0px; text-shadow:none; color:white; font-size:10px;" class="progress-label"></div></div> 
      		</div>	
        </div>
        <!--PROGRESS BAR Box Start-->
        <div class="clearfix" style="margin-bottom:20px;"></div>

	        	 <span class="label label-success" style="left:0px; margin-left:10px; margin-top:40px; font-size:30px;">Clinical Records</span>
			     <div class="clearfix" style="margin-bottom:20px;"></div>

		  <?php
		  	$hash = md5( strtolower( trim( $email ) ) );
		  	$avat = 'identicon.php?size=50&hash='.$hash;
		  ?>	
             
			    <a href="meaningfuluse.php?Acceso=23432&Nombre=<?php echo $NombreEnt;?>&Password=<?php echo $PasswordEnt;?>&IdUsu=<?php echo $USERID;?>&IdMed=<?php echo $MedID;?>"><img src="<?php echo $avat; ?>" style="float:right; margin-right:20px; font-size:18px; padding:5px 10px 5px 10px; font-family: “Andale Mono”, AndaleMono, monospace;"/></a>
				<span id="NombreComp" style="font: bold 24px Arial, Helvetica, sans-serif; color: #3D93E0; cursor: auto; margin-left:20px; "><?php echo $MedUserName;?> <?php echo $MedUserSurname;?></span>
		  		<span id="IdUsFIXED" style="font-size: 12px; color: #3D93E0; font-weight: normal; font-family: Arial, Helvetica, sans-serif; display: block; margin-left:20px;"><?php echo $IdUsFIXED;?></span>
			  	<span id="IdUsFIXEDNAME" style="font-size: 14px; color: GREY; font-weight: bold; font-family: Arial, Helvetica, sans-serif; margin-left:20px;"><?php echo $IdUsFIXEDNAME;?></span>
	  	    	<span id="email" style="font-size: 14px; color: #5EB529; font-weight: bold; font-family: Arial, Helvetica, sans-serif; margin-left:20px;"><?php echo $email;?></span>

        <!--NOTES Start-->
         
        <div class="clearfix" style="margin-bottom:20px;"></div>
        
         <!--Upload Box Start-->
        <div class="grid" style="width:97%; padding-top:10px;">
        <input type="hidden" id="IdUsuP" value="<?php echo $IdUsu ?>" />
        	<span class="label label-info" id="EtiTML" style="margin:10px; font-size:16px; text-shadow:none; text-decoration:none;">Upload Report Area</span>
        	        
        	<div class="grid-content overflow">
        	  <div id="BotonUpload"  data-target="#header-modal2" data-toggle="modal" class="pull-left"><a href="#" class="btn" title="Upload Report"><i class="icon-upload-alt"></i> Upload Report</a> </div>
              <div class="pull-left" style="margin-left:20px;"><a href="patientReportGallery.php?Acceso=23432&USERID='<?php echo $USERID; ?>'&Nombre=<?php echo $IdUsFIXEDNAME;?>&Password=<?php echo $IdUsRESERV;?>&Idmed=<?php echo $IdMed;?>&IdUsu=<?php echo $USERID; ?>" class="btn"><i class="icon-folder-open"></i> Records Board</a> </div>
              <div id="BotonRecords" style="margin-left:20px;" data-target="#header-modal4" data-toggle="modal" class="pull-left"><a href="#" class="btn" title="New Records Board"><i class="icon-folder-open"></i> New Records Board</a> </div>
              <button id="BotonMod" data-target="#header-modal3" data-toggle="modal" class="btn" style="float:right; margin-right:10px;"><i class="icon-indent-left"></i>Classification</button>
              
             
        	</div>
        </div>
        <!--Upload Box END-->
        <div class="clearfix" style="margin-bottom:20px;"></div>
   
       
        <?php
        	//echo '***--- '.$IdUsFIXEDNAME.' ----**** '.$IdUsRESERV.' ****';
        	if ($IdUsPassword < ' ')
        	{
        	$Token = md5($IdUsu);
        	//echo '<div class="grid-content overflow">';
			//echo '<p style="font-size:18px; margin-top:0px; float:left;">Send Invitation token to <span style="color: #3D93E0;">'.$MedUserName.'</span>: </p>';		        	
        	//echo '<div id="BotonEnviaInvit"  style="margin-left:30px; margin-top:-5px; float:left;" class="pull-left"><a href="#" class="btn" title="Send Invitation"><i class="icon-share"></i>Send Invitation</a> </div>';
        	//echo '<span class="label label-info" style="left:0px; margin-left:10px; margin-top:40px; font-size:18px;">'.$Token.'</span>';
        	//echo '</div>';
        	//echo '<div class="clearfix" style="margin-bottom:20px;"></div>';
        	}
        ?>
       
        <span class="label label-success" id="EtiTML" style="left:0px; margin-left:0px; margin-top:20px; margin-bottom:5px; font-size:14px;">Health History Timeline</span>
        <div class="grid span3" style="margin-left:0px; width:98%; height:400px; margin-bottom:20px;" >
         <!-- BEGIN Timeline Embed -->
         	<div id="timeline-embed"></div>
         		<script type="text/javascript">
	         		var timeline_config = {
		         		width: "100%",
		         		height: "100%",
		         		source: 'jsondata2.txt'
		         		//     source: 'example_json.json'
		         		}
		         </script>
		 <!-- END Timeline Embed-->
        </div>
        
                    <div class="clear"></div>   
                     
        <span class="label label-success" style="left:0px; margin-left:0px; margin-top:20px; margin-bottom:5px; font-size:14px;">Health Repository</span>
        <!--Progress Bar -->
		
		<!-- <div id=progressbar></div> -->
		
        <!--TAB Start-->
  <div id="tabsWithStyle" class="style-tabs">
         
          <ul id="myTab" class="nav nav-tabs tabs-main">
            <li id="1" class="TABES" style="width:10%; background-color:<?php echo $TipoColorGroup[1] ?>;"><a href="#ALL" data-toggle="tab" style=" color:<?php echo $TipoColorGroup[1] ?>;"><i class="<?php echo $TipoIconGroup[1] ?> icon-large" style="color:RGB(111,111,111); font-size: 1.0em; width:100%;"></i><div style="width:100%"></div>Imaging</a></li>
            <li id="2" class="TABES" style="width:10%; background-color:<?php echo $TipoColorGroup[2] ?>;"><a href="#ALL" data-toggle="tab" style="height:40px; color:<?php echo $TipoColorGroup[2] ?>;"><i class="<?php echo $TipoIconGroup[2] ?> icon-large" style="color:RGB(111,111,111); font-size: 1.0em; width:100%;"></i><div style="width:100%"></div>Laboratory</a></li>
            <li id="3" class="TABES" style="width:10%; background-color:<?php echo $TipoColorGroup[3] ?>;"><a href="#ALL" data-toggle="tab" style="height:40px; color:<?php echo $TipoColorGroup[3] ?>;"><i class="<?php echo $TipoIconGroup[3] ?> icon-large" style="color:RGB(111,111,111); font-size: 1.0em; width:100%;"></i><div style="width:100%"></div>Notes</a></li>
            <li id="4" class="TABES" style="width:10%; background-color:<?php echo $TipoColorGroup[4] ?>;"><a href="#ALL" data-toggle="tab" style="height:40px; color:<?php echo $TipoColorGroup[4] ?>;"><i class="<?php echo $TipoIconGroup[4] ?> icon-large" style="color:RGB(111,111,111); font-size: 1.0em; width:100%;"></i><div style="width:100%"></div>Other </a></li>
            <li id="5" class="TABES" style="width:10%; background-color:<?php echo $TipoColorGroup[5] ?>;"><a href="#ALL"   data-toggle="tab" style="height:40px; color:<?php echo $TipoColorGroup[5] ?>;"><i class="<?php echo $TipoIconGroup[5] ?> icon-large" style="color:RGB(111,111,111); font-size: 1.0em; width:100%;"></i><div style="width:100%"></div>-n/a-</a></li>
            <li id="6" class="TABES" style="width:10%; background-color:<?php echo $TipoColorGroup[6] ?>;"><a href="#ALL" data-toggle="tab" style="height:40px; color:<?php echo $TipoColorGroup[6] ?>;"><i class="<?php echo $TipoIconGroup[6] ?> icon-large" style="color:RGB(111,111,111); font-size: 1.0em; width:100%;"></i><div style="width:100%"></div>SUMMARY</a></li>
            <li id="7" class="TABES" style="width:10%; background-color:<?php echo $TipoColorGroup[7] ?>;"><a href="#ALL" data-toggle="tab" style="height:40px; color:<?php echo $TipoColorGroup[7] ?>;"><i class="<?php echo $TipoIconGroup[7] ?> icon-large" style="color:RGB(111,111,111); font-size: 1.0em; width:100%;"></i><div style="width:100%"></div>Pictures</a></li>
            <li id="8" class="TABES" style="width:10%; background-color:<?php echo $TipoColorGroup[8] ?>;"><a href="#ALL" data-toggle="tab" style="height:40px; color:<?php echo $TipoColorGroup[8] ?>;"><i class="<?php echo $TipoIconGroup[8] ?> icon-large" style="color:RGB(111,111,111); font-size: 1.0em; width:100%;"></i><div style="width:100%"></div>Pat. Notes</a></li>
            <li id="9" class="TABES" style="width:10%; background-color:<?php echo $TipoColorGroup[9] ?>;"><a href="#ALL" data-toggle="tab" style="height:40px; color:<?php echo $TipoColorGroup[9] ?>;"><i class="<?php echo $TipoIconGroup[9] ?> icon-large" style="color:RGB(111,111,111); font-size: 1.0em; width:100%;"></i><div style="width:100%"></div>Superbill</a></li>
            <li id="0" class="TABES" style="width:10%; background: none repeat scroll 0% 0% rgb(204, 204, 204); text-align:center;"><a href="#ALL" data-toggle="tab" style="height:40px; font-size:16px;"><i class="icon-ok-sign icon-large" style="color:black; font-size: 1.0em; width:100%;"></i><div style="width:100%"></div>ALL</a></li>
          </ul> 
          
          <div id="myTabContent" class="tab-content tabs-main-content padding-null" >
                <div  class="tab-pane tab-overflow-main fade in active" id="ALL">
					<div class="scroll-pane horizontal-only notes" style="height: 290px; width:100%; margin-top:-10px; background-color:white;" >
						<div id="StreamContainerALL" style="width: 2420px; height: 250px; margin-top:10px; margin-left:10px;"></div>
						<div id="StreamContainerIMAG" style="width: 2420px; height: 250px; margin-top:10px; margin-left:10px;"></div>
						<div id="StreamContainerLABO" style="width: 2420px; height: 250px; margin-top:10px; margin-left:10px;"></div>
						<div id="StreamContainerDRRE" style="width: 2420px; height: 250px; margin-top:10px; margin-left:10px;"></div>
						<div id="StreamContainerOTHE" style="width: 2420px; height: 250px; margin-top:10px; margin-left:10px;"></div>
						<div id="StreamContainerNA" style="width: 2420px; height: 250px; margin-top:10px; margin-left:10px;"></div>
						<div id="StreamContainerSUMM" style="width: 2420px; height: 250px; margin-top:10px; margin-left:10px;"></div>
						<div id="StreamContainerPICT" style="width: 2420px; height: 250px; margin-top:10px; margin-left:10px;"></div>
						<div id="StreamContainerPATN" style="width: 2420px; height: 250px; margin-top:10px; margin-left:10px;"></div>
						<div id="StreamContainerSUPE" style="width: 2420px; height: 250px; margin-top:10px; margin-left:10px;"></div>
						
					</div>  
					<!--NOTES END-->
                </div>
				<!-- <div class="tab-pane tab-overflow-main fade in active" id="IMAG">
					<div class="scroll-pane horizontal-only notes" style="height: 290px; width:100%;  margin-top:-10px; background-color:white;" >
						<div id="StreamContainerIMAG" style="width: 2420px; height: 250px; margin-top:10px; margin-left:10px;"></div>
					</div>  
				</div>
                <div class="tab-pane tab-overflow-main fade in active" id="LABO">
					<div class="scroll-pane horizontal-only notes" style="height: 290px; width:100%;  margin-top:-10px; background-color:white;" >
						<div id="StreamContainerLABO" style="width: 2420px; height: 250px; margin-top:10px; margin-left:10px;"></div>
					</div>  
                </div>		
                <div class="tab-pane tab-overflow-main fade in active" id="DRRE">
					<div class="scroll-pane horizontal-only notes" style="height: 290px; width:100%;  margin-top:-10px; background-color:white;" >
						<div id="StreamContainerDRRE" style="width: 2420px; height: 250px; margin-top:10px; margin-left:10px;"></div>
					</div>  
                </div>		
                <div class="tab-pane tab-overflow-main fade in active" id="OTHE">
					<div class="scroll-pane horizontal-only notes" style="height: 290px; width:100%; margin-top:-10px; background-color:white;" >
						<div id="StreamContainerOTHE" style="width: 2420px; height: 250px; margin-top:10px; margin-left:10px;"></div>
					</div>  
                </div>		
                <div class="tab-pane tab-overflow-main fade in active" id="NA">
					<div class="scroll-pane horizontal-only notes" style="height: 290px; width:100%; margin-top:-10px; background-color:white;" >
						<div id="StreamContainerNA" style="width: 2420px; height: 250px; margin-top:10px; margin-left:10px;"></div>
					</div>  
                </div>		
                <div class="tab-pane tab-overflow-main fade in active" id="SUMM">
					<div class="scroll-pane horizontal-only notes" style="height: 290px; width:100%; margin-top:-10px; background-color:white;" >
						<div id="StreamContainerSUMM" style="width: 2420px; height: 250px; margin-top:10px; margin-left:10px;"></div>
					</div>  
                </div>		
                <div class="tab-pane tab-overflow-main fade in active" id="PICT">
					<div class="scroll-pane horizontal-only notes" style="height: 290px; width:100%; margin-top:-10px; background-color:white;" >
						<div id="StreamContainerPICT" style="width: 2420px; height: 250px; margin-top:10px; margin-left:10px;"></div>
					</div>  
                </div>		
                <div class="tab-pane tab-overflow-main fade in active" id="PATN">
					<div class="scroll-pane horizontal-only notes" style="height: 290px; width:100%; margin-top:-10px; background-color:white;" >
						<div id="StreamContainerPATN" style="width: 2420px; height: 250px; margin-top:10px; margin-left:10px;"></div>
					</div>  
                </div>		
                <div class="tab-pane tab-overflow-main fade in active" id="SUPE">
					<div class="scroll-pane horizontal-only notes" style="height: 290px; width:100%; margin-top:-10px; background-color:white;" >
						<div id="StreamContainerSUPE" style="width: 2420px; height: 250px; margin-top:10px; margin-left:10px;"></div>
					</div>  
                </div>	-->	
          </div>		
  
  </div>
  
     <div class="grid span3" style="width:90%;">
          <div class="grid-title a" style="height:60px;">
           <div class="pull-left a" id="AreaTipo" style="font-size:24px;"></div>
		   <div class="pull-right">
               <div class="grid-title-label" id="History" ><span class="label label-info" style="left:0px; margin-left:20px; margin-top:20px; margin-bottom:5px; font-size:16px;">Detailed Report Tracking History</span></div>
           </div>
           <div class="pull-right">
               <div class="grid-title-label" id="AreaFecha" ><span class="label label-warning" ></span></div>
           </div>
          <div class="clear"></div>  
           <div>
           <span class="ClClas" id="AreaClas" style="font-size:18px; color:grey;"></span>
           </div>
           <div class="clear"></div>   
          </div>
          
          <div class="grid-content" id="AreaConten" style="">
<?php
//BLOCKSLIFEPIN $sql="SELECT * FROM blocks where IdUsu ='$IdUsu'";
$sql=$con->prepare("SELECT * FROM lifepin where IdUsu =?");
$sql->bindValue(1, $IdUsu, PDO::PARAM_INT);

$q = $sql->execute();
$row = $sql->fetch(PDO::FETCH_ASSOC);
?>
 
             <img id="ImagenAmp" style="margin:0 auto;" src="">
          </div>
        </div>
        
                      
     </div>
     <!--CONTENT MAIN END-->

    </div>
	</div>
    <!--Content END-->

    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->

	<script type="text/javascript" src="js/storyjs-embed.js"></script>

    <!--<script src="js/jquery.min.js"></script>
    <script src="js/jquery-ui.min.js"></script> -->
   
    <script src="js/bootstrap.min.js"></script>
    <script src="js/bootstrap-datepicker.js"></script>
    <script src="js/bootstrap-colorpicker.js"></script>
	<!--<script src="js/bootstrap-modal.js"></script>
	<script src="js/bootstrap-dropdown.js"></script>-->
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
    
	<!--<script src="js/application.js"></script>-->
    
     
  <!--  <script type="text/javascript" src="http://code.jquery.com/jquery-1.8.0.min.js"></script>--->
    <script type="text/javascript" src="js/jquery.tooltipster.min.js"></script>

  
	<script src="realtime-notifications/lib/gritter/js/jquery.gritter.min.js"></script>
	<script src="realtime-notifications/pusher.min.js"></script>
	<script src="realtime-notifications/PusherNotifier.js"></script>
	<link href="realtime-notifications/lib/gritter/css/jquery.gritter.css"rel="stylesheet" type="text/css" />
	<!--<script src="imageLens/jquery.js" type="text/javascript"></script>-->
	<script src="imageLens/jquery.imageLens.js" type="text/javascript"></script>
	 <script>
		$(function() {
	    var pusher = new Pusher('d869a07d8f17a76448ed');
	    var channel_name=$('#MEDID').val();
		var channel = pusher.subscribe(channel_name);
		var notifier=new PusherNotifier(channel);
		
	  });

  </script>
    <script type="text/javascript" >
	var list = new Array();
	var curr_file=-1;
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
	
	
    $(document).ready(function() {
	
		$('body').bind('mousedown keydown', function(event) {
			clearTimeout(timeoutTimer);
			timeoutTimer = setTimeout(ShowTimeOutWarning, timeoutTime);
		});
	
		//$("#progress-bar").hide();
		//$("div[class='bar']").width(100);
		
		//$('.tooltip').tooltipster();
		//$('.tooltip').
		//$('.tooltip').show();
		$("#tabsWithStyle").hide();
		//$("#referral_stage").hide();
		var ElementDOM ='#StreamContainerALL';
		var EntryTypegroup = 0 ;
		var Usuario = $('#userId').val();
		var MedID =$('#MEDID').val();
		var PrevElementDOM='';
		/*var queUrl ='CreateReportStream.php?ElementDOM=na&EntryTypegroup='+EntryTypegroup+'&Usuario='+Usuario+'&MedID='+MedID;
      	//alert (queUrl);
      	$(ElementDOM).load(queUrl);
    	$(ElementDOM).trigger('update');*/
		
	$("#EtiTML").live('click',function() {
		alert ('Timeline launched');
		var timeline_config = {
		         		width: "100%",
		         		height: "100%",
		         		source: 'jsondata2.txt'
		         		//     source: 'example_json.json'
		         		}

		});    

    $("#Clases").live('change',function() {
	    var doble = $(this).val();
	    var newVal = String(doble).substr(0,1);
	    var newVal2 = String(doble).substr(1,2);
		$("#SelecERU").val(newVal);		   
		$("#SelecEvento").val(newVal2);		   
		    //do something
	});
	
    $("#Tipos").live('change',function() {
	    var newVal = $(this).val();
		$("#SelecTipo").val(newVal);		   
		//alert (newVal);
		    //do something
	});   
	
	$("#BotonMod").hide();
	
	$(".CFILAMODAL").live('click',function() {
		var ipadd=$('td', this).eq(3).text();
		//alert(ipadd);
		/*var id=$(this).attr("id");
		var url = 'map.php?ipaddress='+ipadd+'&id='+id;
		var RecTipo = LanzaAjax (url);
		
		var serviceUrl = '<?php echo $domain;?>/getReportLocation.php?id='+id;
		getreportLocation(serviceUrl);
		alert(geolocation[0].latitude);	
		
		var map = new GMap(document.getElementById("map"));
		var point = new GPoint(29.7397,-95.8302);
		map.centerAndZoom(point, 3);
		var marker = new GMarker(point);
		map.addOverlay(marker);
		google.maps.event.trigger(map, 'resize');
			*/
		$("#header-modalMap").html("")	;	
		$("#header-modalMap").load("maps.php?ipaddress="+ipadd);
	    $('#BotonModalMap').trigger('click');
		//$('#BotonModalMap').show();
		//alert("Here");
	
	});
	
	$('#CloseModal22').live('click',function(){
	 
	 //$('#BotonModalMap').trigger('click');
		$('#BotonModalMap').hide();
	});
	
	 $('#BotonModalMap').live('click',function(){
	 e.preventDefault();
	 $('#BotonModalMap').show();
	 
	 });
	
	$("#History").live('click',function() {
		var path = $('#ImagenN').attr("src");
		var rawimage = "";
		var idpin;
		//alert(path);
		if(path == null)
		{
			alert('Select a Report');
			return;
		}
		else if(path == '<?php echo $domain;?>/images/deletedfileTH.png')
		{
				//alert("Deleted Report");
				rawimage=$('#ImagenN').attr("alt");
				//alert('Idpin is '+idpin);
		}
		else
		{
			rawimage=rawimage+ path.substr(path.lastIndexOf("/")+1,path.length);
		}
			var serviceUrl = '<?php echo $domain;?>/getReportData.php?rawimage='+rawimage;
			alert(serviceUrl);
			//alert(query);
			//var RecTipo = LanzaAjax (cadena);
			getreportData(serviceUrl);
			//alert(pines[0].idpin);
			IDEt ='<span class="label label-info" style="font-size:18px; padding:5px 10px 5px 10px; font-family: “Andale Mono”, AndaleMono, monospace;">ID:'+ pines[0].idpin+' </span>';
			var text="";
			//if(pines[0].orig_filename!= null)
			//{
				text = '<span class="label label-success" style="font-size:18px; padding:5px 10px 5px 10px; font-family: “Andale Mono”, AndaleMono, monospace; margin-left:20px;">Uploaded By : ' + pines[0].idmedfixedname+'</span>';
			//}
			//text = text + '<br><br><span class="label label-info" style="font-size:18px; padding:5px 10px 5px 10px; font-family: “Andale Mono”, AndaleMono, monospace;">Filename :'+ pines[0].orig_filename+'</span>';
			$('#InfoIDPaciente').html(IDEt+text);
			
			var queUrl ='<?php echo $domain;?>/getReportHistory.php?id='+pines[0].idpin;
			var queUrl1 ='<?php echo $domain;?>/getReportViewers.php?id='+pines[0].idpin;
			
			
			$('#TablaPacMODALViewers').load(queUrl1);
			$('#TablaPacMODALViewers').trigger('update');
			
			$('#TablaPacMODAL').load(queUrl);
			$('#TablaPacMODAL').trigger('update');
			
			//alert("Here");
			$('#BotonModal1').trigger('click');
	    
		
		
		
	});


	function getreportData(serviceURL) {
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
	
    $("#BotonAddClase").live('click',function() {
 	   var name=prompt("Please enter new Episode (class)","");
 	   if (name!=null && name!="")
 	   	{
	 	   var queUsu = $("#queUsu").val();
	 	   var queBlock = $("#queBlock").val();
	 	   var UltimoEvento = $("#UltimoEvento").val();
	 	   var cadena = '<?php echo $domain;?>/AnadeClase.php?queBlock='+queBlock+'&queUser='+queUsu+'&UltimoEvento='+UltimoEvento+'&Nombre='+name;
		   var RecTipo = LanzaAjax (cadena);
		   $("#Episodios").append('<option value='+(UltimoEvento+1)+' selected="selected">'+name+'</option>');
		   $("#UltimoEvento").val(1+parseInt(UltimoEvento));		   
		}
   	});

    $("#BotonElimClase").live('click',function() {
 	  var name = $('#Episodios').find(":selected").text();
 	  var r=confirm('Confirm removal of episode ('+name+') ?');
 	  	if (r==true)
 	  	{
	 	    var queUsu = $("#queUsu").val();
	 	    var queBlock = $("#queBlock").val();
	 	    var UltimoEvento = $("#UltimoEvento").val();
	 	    var cadena = '<?php echo $domain;?>/EliminaClase.php?queBlock='+queBlock+'&queUser='+queUsu+'&UltimoEvento='+UltimoEvento+'&Nombre='+name;
	 	  	var RecTipo = LanzaAjax (cadena);
		    $('#Episodios option:selected').remove();
         	$('#CloseModal').trigger('click');
	 	}
	 	else
	 	{
		 	//x="You pressed Cancel!";
		}
   	});

    $("#GrabaDatos").live('click',function() {
 	  var name = $('#Episodios').find(":selected").text();
 	  var r=confirm('Confirm updating information for this block ?');
 	  	if (r==true)
 	  	{
	 	    var queUsu = $("#queUsu").val();
	 	    var queBlock = $("#queBlock").val();
	 	    var UltimoEvento = $("#UltimoEvento").val();
	 	    
	 	    var queERU = $("#SelecERU").val();
	 	    var queEvento = $("#SelecEvento").val();
	 	    var queTipo = $("#SelecTipo").val();
	 	    
	 	    var cadena = '<?php echo $domain; ?>/GrabaClasif.php?queBlock='+queBlock+'&queUser='+queUsu+'&queERU='+queERU+'&queEvento='+queEvento+'&queTipo='+queTipo;
	 	  	//alert (cadena);
	 	  	var RecTipo = LanzaAjax (cadena);
		    //alert (RecTipo);
		    $('#Episodios option:selected').remove();
         	$('#CloseModal').trigger('click');
         	window.location.reload();
	 	}

   	});

	$("#BotonRecords").live('click',function() {
	 	//if(list.length==0)
		//{
			var Usuario = $('#userId').val();
			//var MedID =$('#MEDIdecryptD').val();
			var url = 'get_file_list.php?ElementDOM=na&EntryTypegroup=0&Usuario='+Usuario+'&MedID='+<?php echo $_SESSION['MEDID'];?>;
			//alert(url);
			var RecTipo = LanzaAjax (url);
		//alert(RecTipo);
			list = RecTipo.split(';');
		//}
		curr_file=-1;
		list.pop();
		next_click();
		
		
		
	});
	
    $("#BotonUpload").live('click',function() {
	 
	  	/*  Pruebas de la grabación del archivo para Timeline
	  	var queUsu = $("#IdUsuP").val();
	 	var cadena = '<?php $domain?>/UsuTimeline.php?Usuario='+queUsu+'&IdMed=0';
	 	var RecTipo = LanzaAjax (cadena);
	    alert (RecTipo);
	    */
	    
	    
	    //alert (RecTipo);
	    /*
	    var IDPIN = 0;
	    var Content = 0;
	    var VIEWIdUser = 0;
	    var VIEWIdMed = 0;
	    var VIEWIP = 0;
	    var MEDIO = 0;
	    var cadena = '<?php $domain?>/LogEvent.php?IDPIN='+IDPIN+'&Content='+Content+'&VIEWIdUser='+VIEWIdUser+'&VIEWIdMed='+VIEWIdMed+'&VIEWIP='+VIEWIP+'&MEDIO='+MEDIO;
	 	var RecTipo = LanzaAjax (cadena);
	 	//alert (RecTipo);
	 	*/
   	});

   $(".TABES").live('click',function() {
		var queid = $(this).attr("id");
		var ElementDOM="";
		//alert(queid);
		//$("#ALL").hide();
		$("#StreamContainerALL").hide();
		$("#StreamContainerIMAG").hide();
		$("#StreamContainerLABO").hide();
		$("#StreamContainerDRRE").hide();
		$("#StreamContainerOTHE").hide();
		$("#StreamContainerNA").hide();
		$("#StreamContainerSUMM").hide();
		$("#StreamContainerPICT").hide();
		$("#StreamContainerPATN").hide();
		$("#StreamContainerSUPE").hide();
		$("#StreamContainerPICT").hide();
		//$(PrevElementDOM).hide();
		switch (queid)
		{
			case '0': 	ElementDOM ='#StreamContainerALL';
						//$("#ALL").show();
						break;
			case '1': 	ElementDOM ='#StreamContainerIMAG';
						//$("#IMAG").show();
						break;
			case '2': 	ElementDOM ='#StreamContainerLABO';
						//$("#LABO").show();
						break;
			case '3': 	ElementDOM ='#StreamContainerDRRE';
						//$("#DRRE").show();
						break;
			case '4': 	ElementDOM ='#StreamContainerOTHE';
						//$("#OTHE").show();
						break;
			case '5': 	ElementDOM ='#StreamContainerNA';
						//$("#NA").show();
						break;
			case '6': 	ElementDOM ='#StreamContainerSUMM';
						//$("#SUMM").show();
						break;
			case '7': 	ElementDOM ='#StreamContainerPICT';
						//$("#PICT").show();
						break;
			case '8': 	ElementDOM ='#StreamContainerPATN';
						//$("#PATN").show();
						break;
			case '9': 	ElementDOM ='#StreamContainerSUPE';
						//$("#SUPE").show();
						break;
			default: 	ElementDOM ='testDIV';
						//$("#DIV").show();
						break;
				
		}
		var EntryTypegroup =queid;
		var Usuario = $('#userId').val();
		var MedID =$('#MEDID').val();
		
		
		var queUrl ='CreateReportStreamUSER.php?ElementDOM=na&EntryTypegroup='+EntryTypegroup+'&Usuario='+Usuario+'&MedID='+MedID;
		
      	//alert (queUrl);
		//$("#ALL").show();
		//PrevElementDOM=ElementDOM;
      	$(ElementDOM).load(queUrl);
		$(ElementDOM).trigger('click');
    	$(ElementDOM).trigger('update');
        setTimeout(function() {highlightattachedreports();},1000);  
		$(ElementDOM).show();
   
   });
   
    $("#BotonTestRS").live('click',function() {
		var ElementDOM ='testDIV';
		var EntryTypegroup ='3';
		var Usuario = $('#userId').val();
		var MedID =$('#MEDID').val();
		
		var queUrl ='CreateReportStreamUSER.php?ElementDOM='+ElementDOM+'&EntryTypegroup='+EntryTypegroup+'&Usuario='+Usuario+'&MedID='+MedID;
      	
      	$('#StreamContainer').load(queUrl);
    	$('#StreamContainer').trigger('update');

	});
	
	//$("#attachments").live('click',function() {
	
		

	//});
	
	var DELAY = 400, clicks = 0, timer = null;
	
	$(".note2").live("click", function(e){

        clicks++;  //count clicks
		//alert(this);
        if(clicks === 1) {
			   var screen=this;
               timer = setTimeout(function() {

                //alert(this);
				
				var queBLD = $(".queBLD", screen).attr("id");
				//alert('here');
				var queId = $(screen).attr("id");
				var quePEN=  $(".quePEN", screen).attr("id");
				var queDEL=	 $(".queDEL", screen).attr("id");			
				if(queBLD==null){
    	
				if(quePEN==null){
					
				//var queId = $(this).attr("id");
				var queTip = $(".queTIP", screen).attr("id");
				var queClas = $(".queEVE", screen).attr("id");
				var queFecha = $(".queFEC", screen).attr("id");
				var queUsu = $("#IdUsuP").val();
				
				if(queDEL==null){
				
				var readwriteaccess=$(".queIMG", screen).children("img").attr("alt");
				if(readwriteaccess==1){
				$("#BotonMod").show();
				}else{
				$("#BotonMod").hide();
				}
				
				
				var med=<?php echo $IdMed ?>;
				var IDPIN = queId;
				var Content = 'Report Viewed';
				var VIEWIdUser =<?php echo $USERID?>;
				var VIEWIdMed = med;
				var MEDIO = 0;
				var cadena = '<?php echo $domain ;?>/LogEvent.php?IDPIN='+IDPIN+'&Content='+Content+'&VIEWIdUser='+VIEWIdUser+'&VIEWIdMed='+VIEWIdMed+'&MEDIO='+MEDIO;
				var RecTipo = LanzaAjax (cadena);
				
				//alert (RecTipo);
				var queImg = $(".queIMG", screen).attr("id");
				var extensionR = queImg.substr(queImg.length-3,3);
				var ImagenRaiz = queImg.substr(0,queImg.length-4);
				var subtipo = queImg.substr(3,2);  // Para los casos en que eMapLife+ (PROF) ya sube las imagenes a AMAZON y no a GODADDY
				//$ImageRaiz = substr($row['RawImage'],0,strlen($row['RawImage'])-4);
				
				//alert(extensionR + '  ' + queImg + IDPIN);
				if (extensionR=='pdf')
				{
					var cadena = '<?php echo $domain;?>/DecryptFile.php?rawimage='+queImg+'&queMed='+<?php echo $MedID;?>;
					var RecTipo = LanzaAjax (cadena);
		   	
					var contenTHURL = '<?php echo $domain; ?>/temp/<?php echo $MedID ;?>/PackagesTH_Encrypted/'+ImagenRaiz+'.png';  // PONER /PinImageSetTH/ en lugar de /PinImageSet/ PARA CARGAR THUMBNAILS EN LUGAR DE IMAGENES COMPLETAS
					var contenURL =   '<?php echo $domain; ?>/temp/<?php echo $MedID ;?>/Packages_Encrypted/'+ImagenRaiz+'.'+extensionR;  // PONER /PinImageSetTH/ en lugar de /PinImageSet/ PARA CARGAR THUMBNAILS EN LUGAR DE IMAGENES COMPLETAS
					//var conten = '<img id="ImagenN" src="'+contenURL+'" alt="">';
					var conten =  '<iframe id="ImagenN" style="border:1px solid #666CCC; margin:0 auto; display:block;" title="PDF" src="'+contenURL+'" alt="'+queId+'" frameborder="1" scrolling="auto" height="850" width="600" ></iframe>';
					var contenTH = '<img id="ImagenTH"  src="'+contenTHURL+'" alt="">';
				}
				else{
                    var canal = '';
                    <?php
                        if(isset($row['CANAL']))
                        {
                            echo 'canal = '.$row['CANAL'].';';
                        }
                    ?>
                    
					if(canal.length > 0 && canal == '7' ){
						
						var cadena = '<?php echo $domain;?>/DecryptFile.php?rawimage='+queImg+'&queMed='+<?php echo $MedID;?>;
						var RecTipo = LanzaAjax (cadena);
		   	
						var contenTHURL = '<?php echo $domain; ?>/temp/<?php echo $MedID ;?>/PackagesTH_Encrypted/'+queImg; 
						var conten = '<img id="ImagenN" src="<?php echo $domain;?>/temp/<?php echo $MedID ;?>/Packages_Encrypted/'+queImg+'" alt="'+queId+'" style="border:1px solid #666CCC; margin:0 auto; display:block;">';
						//alert(contenTHURL);
						//alert(conten);
					}
					else{
					if (subtipo=='XX') { 
						var cadena = '<?php echo $domain;?>/DecryptFile.php?rawimage='+queImg+'&queMed='+<?php echo $MedID;?>;
						var RecTipo = LanzaAjax (cadena);
		   	
						var contenTHURL = '<?php echo $domain; ?>/temp/<?php echo $MedID ;?>/PackagesTH_Encrypted/'+queImg; 
						var conten = '<img id="ImagenN" src="<?php echo $domain;?>/temp/<?php echo $MedID ;?>/Packages_Encrypted/'+queImg+'" alt="'+queId+'" style="border:1px solid #666CCC; margin:0 auto; display:block;">';
						}
					else{ 
						//var contenTHURL = '<?php echo $domain; ?>/eMapLife/PinImageSetTH/'+queImg; 
						//var conten = '<img id="ImagenN" src="<?php echo $domain; ?>/eMapLife/PinImageSet/'+queImg+'" alt="'+queId+'" style="border:1px solid #666CCC; margin:0 auto; display:block;">';
						
						var cadena = '<?php echo $domain;?>/DecryptFile.php?rawimage='+queImg+'&queMed='+<?php echo $MedID;?>;
						var RecTipo = LanzaAjax (cadena);
		   	
						var contenTHURL = '<?php echo $domain; ?>/temp/<?php echo $MedID ;?>/PackagesTH_Encrypted/'+queImg; 
						var conten = '<img id="ImagenN" src="<?php echo $domain;?>/temp/<?php echo $MedID ;?>/Packages_Encrypted/'+queImg+'" alt="'+queId+'" style="border:1px solid #666CCC; margin:0 auto; display:block;">';
						
						
						
						}  // PONER /PinImageSetTH/ en lugar de /PinImageSet/ PARA CARGAR THUMBNAILS EN LUGAR DE IMAGENES COMPLETAS
					
				if(urlExists(contenTHURL)) {}else { contenTHURL = '<?php $domain?>/eMapLife/PinImageSet/'+queImg;}
				}
					var contenTH = '<img id="ImagenTH"  src="'+contenTHURL+'" alt="'+queId+'">';
				}
				}else{
				//code for report deletion
				var queImg1 = $(".queDEL", screen).attr("id");
				var contenURL='<?php echo $domain; ?>/images/deletedfileTH.png';
				var conten =  '<iframe id="ImagenN" style="border:1px solid #666CCC; margin:0 auto; display:block;" title="PDF" src="'+contenURL+'" alt="'+queImg1+'" frameborder="1" scrolling="auto" height="850" width="600" ></iframe>';
				var contenTH = '<img id="ImagenTH"  src="<?php echo $domain; ?>/images/deletedfile.png" alt="">';
						
				}
				
				//alert (queClas);
							
				//$('div.grid-content').html(conten);
				$('#AreaConten').html(conten);
				$('#RepoThumb').html(contenTH);
				
				//$('div.pull-left.a').html(queTip);
				$('#AreaTipo').html(queTip);
				
				//$('.ClClas').html(queClas);
				$('#AreaClas').html(queClas);
				
				//$('div.grid-title-label').html('<span class="label label-warning" style="font-size:16px;">'+queFecha+'</span>');
				$('#AreaFecha').html('<span class="label label-warning" style="font-size:16px;">'+queFecha+'</span>');
				
				var queUrl ='getTipoClase.php?BlockId='+queId;
					
				$('.ContenDinamico').load(queUrl);
					//$('#TablaPac').trigger('click');
				$('.ContenDinamico').trigger('update');
				
				}else{
					
				
				alert("An unlock request for the report(s) has already been send to screen user.Pending user confirmation!!");
				
				}
					
				
				}else{
					
					//var queId = $(screen).attr("id");
					var myClass = $(screen).attr("id");
					$('#Idpin').attr("value",myClass);
					//Adding the option of showing only "All reports incase the patient is not a valid user
					var To= $('#userId').val();
					getUserData(To);
					$('#TextoSend').html("");
					$("#ConfirmaLink,#ConfirmaLinkAll").show();
					$('#Thisreport,#Allreport').show();
					if (user[0].email==''){
						  //alert("Patient email not found. Request will be sent to reportcreator!");
						 // senderoption=2;
						  $('#BotonModal').trigger('click');
						  $('#ConfirmaLink').hide();
						  $('#Thisreport').hide();
					}else{
						
						 $('#BotonModal').trigger('click');
					}
					//$('#header-modal0').show();
					
					//alert("This is a blind report!!");
				}
                clicks = 0;  //after action performed, reset counter

            }, DELAY);

        } else {
			var screen=this;
            clearTimeout(timer);  //prevent single-click action
			var queUsu = $("#IdUsuP").val();
			var idpin = $(screen).attr("id");
			//var med=<?php echo $IdMed ?>;
			//var privstate=$(screen).attr("privstate");
			var queBLD = $(".queBLD", screen).attr("id");
		    //alert('here');
			var quePEN=  $(".quePEN", screen).attr("id");
				//alert(queId);
			var readwriteaccess=$(".queIMG", screen).children("img").attr("alt");	
			//alert(readwriteaccess);
			if(queBLD==null){
    	
				if(quePEN==null){
						if(readwriteaccess==1){
							$("#BotonMod").show();
							var cadena = '<?php echo $domain ;?>/getprivacystatus.php?Idpin='+idpin+'&state=0+&type=0';
							//alert(cadena);
							var RecTipo = LanzaAjax (cadena);
							//alert(RecTipo);
							var normalprivate="";
							//var superprivate="";
							var priv="";
								//alert (RecTipo);
							//alert('Double Click');  //perform double-click action
							if(RecTipo=="normal"){
									normalprivate=confirm('Please confirm that you want to make this report private!');
									if(normalprivate==true){
									//alert('normal!');
									var cadena = '<?php echo $domain ;?>/getprivacystatus.php?Idpin='+idpin+'&state=1+&type=1';
									var RecTipo = LanzaAjax (cadena);
									//alert (RecTipo);
									}
							}else if(RecTipo=="private"){
									priv=confirm('Please confirm that you want to remove the privacy of this report!');
									if(priv==true){
									var cadena = '<?php echo $domain ;?>/getprivacystatus.php?Idpin='+idpin+'&state=1+&type=0';
									var RecTipo = LanzaAjax (cadena);
									//alert (RecTipo);
									}
							}else if(RecTipo=="superprivate"){
									alert('Privacy of this report cannot be changed!');
							}
							var myClass = $(screen).attr("id");
							//alert(privstate);
						  }else {
							$("#BotonMod").hide();
						    alert("You don't have permissions to change the privacy of this file!");
						  }
						}
					}
			//$('#BotonModal00').trigger('click');
            clicks = 0;  //after action performed, reset counter
        }

    })
    .live("dblclick", function(e){
        e.preventDefault();  //cancel system double-click event
    });

	

//Adding button action for blind reports

 $("#SendButton").live('click',function() {
 	 
 	 var option=$(this).attr("value");
 	  
 	 //if(option=="This report"){
 	 	 var IdPin=$('#Idpin').val();
		 //alert("IdPin:"+IdPin);
		 if(IdPin=="00A"){
		 IdPin=-111;
		 //alert("It works!");
		 }
 	// }else{
 	 	//alert("Clicked on All reports!!");
 	 	//var IdPin=-111;
 	 	//alert("Clicked on All reports!! "+IdPin);
 	// }
 	var senderoption;
	if(option=="Request Patient"){
 	 	 //alert("Clicked on request Patient!!");
		 senderoption=1;
 	 }else{
 	 	//alert("Clicked on reuqest Doctor!!");
		senderoption=2;
 	 }
	 var usephone;
	 if ($('#c2').attr('checked')=='checked'){ 
	  	//subcadena =' (will call phone number also)';
		usephone = 1;
		//alert("Phone number option selected");
	}
	 //return;
	 
     	var To= $('#userId').val();
    	getUserData(To);
    	
    	
    	//var IdDoc=$()
		var NameMed = $('#IdMEDName').val();
	    var SurnameMed = $('#IdMEDSurname').val();
	    var From = $('#MEDID').val();
	    var FromEmail = $('#IdMEDEmail').val();
		if(Idpin==-111){						//Indicator whether to send for this report or for all reports.
			getReportCreator(IdPin,From,To);
		}				
		else{
			getReportCreator(IdPin,0,0);
		}	
    	var doc;
		//alert("Total number of report creator: "+reportcreator.length);
		if(reportcreator.length==0){
		
		  //var option1=confirm("Reportcreator not found. Do you want to continue!!");
			 
			 var option1;	
			 if(senderoption==2){
			 alert("Reportcreator not found!");
			 return;
			 }else {
				option1=confirm("Reportcreator not found. Do you want to continue!!");
			 }
			 if(option1){
				reportcreator=user;
				doc=user;
			 }else {
			  return;
			 }
		 
		}
		
		
		for (var i = 0, len = reportcreator.length; i < len; ++i) {
			
			if(doc==user){
			}else{
			 doc = reportcreator[i];
			}
				
		 if (user[0].email==''){
        	var IdCreador = user[0].IdCreator;
	    	getMedCreator(IdCreador);
	    	//alert ('orphan user . Patient Creator= '+IdCreador);
	    	if(doc==user){
				alert("Both reportcreator and Patient details are not found in the system. Please contact support!!");
				return;
			}
			//alert('Permission Request sent to '+doc.Name + '.'+doc.Surname + ' at ' + doc.IdMEDEmail);
	    	var Subject = 'Unlock report from Dr. '+NameMed+' '+SurnameMed;
        
	    	var Content = 'Dr. '+NameMed+' '+SurnameMed+' has requested to see reportID'+IdPin+ 'of your patient named: '+user[0].Name+' '+user[0].Surname+' (UserId:  '+To+'). Please confirm, or just close this message to reject.';
    	
	    	//alert (Content);
	    	
			var destino = "Dr. "+doc.Name+" "+doc.Surname; 
			if(usephone==1){
				var phone=doc.phone;
				alert(phone);
				if(phone!=null){
					phone = phone.replace(/[^0-9]/g, '');
					if(phone.length == 10 || phone.length==11) { 
						
						alert("yup, its valid number digits");
					} else {
						alert("not valid number");
						phone='Null';
					} 
				}else{
					alert("Health2me could not find a valid phone number!")
				}
			var cadena = '<?php echo $domain;?>/MsgInterno.php?Tipo=1&IdPac='+user[0].Identif+'&To='+doc.id+'&ToEmail='+doc.IdMEDEmail+'&NameDoctor='+doc.Name+'&SurnameDoctor='+doc.Surname+'&NameDoctorOrigin='+NameMed+'&SurnameDoctorOrigin='+SurnameMed+'&NamePatient='+user[0].Name+'&SurnamePatient='+user[0].surname+'&callphone='+phone+'&From='+From+'&FromEmail='+FromEmail+'&Subject='+Subject+'&Content='+Content+'&Leido=0&Push=0&estado=1&Idpin='+IdPin;
			}else{
			var cadena = '<?php echo $domain;?>/MsgInterno.php?Tipo=1&IdPac='+user[0].Identif+'&To='+doc.id+'&ToEmail='+doc.IdMEDEmail+'&NameDoctor='+doc.Name+'&SurnameDoctor='+doc.Surname+'&NameDoctorOrigin='+NameMed+'&SurnameDoctorOrigin='+SurnameMed+'&NamePatient='+user[0].Name+'&SurnamePatient='+user[0].surname+'&From='+From+'&FromEmail='+FromEmail+'&Subject='+Subject+'&Content='+Content+'&Leido=0&Push=0&estado=1&Idpin='+IdPin;
			}
	    	
	    	
	    	//alert (cadena);
	    	var RecTipo = LanzaAjax (cadena);
	    	
	    	//$('#CloseModallink').trigger('click');
	    	alert (RecTipo);
		 } else{
			var NameMed = $('#IdMEDName').val();
			var SurnameMed = $('#IdMEDSurname').val();
			var From = $('#MEDID').val();
			var FromEmail = $('#IdMEDEmail').val();
			var Subject = 'Unlock report';
			var option;
			if(doc==user)
			  senderoption=1;
			
		  
		//alert(senderoption);
		//Request should go to the patient
		 if(senderoption==1) {
			//alert('Permission Request sent to '+doc.Name + '.'+doc.Surname + ' at ' + doc.IdMEDEmail);

			var Content = 'Dr. '+NameMed+' '+SurnameMed+' has requested to see your (UserId:  '+To+') reportID'+IdPin+ ' Please confirm, or just close this message to reject.';
			//var cadena = '<?php echo $domain;?>/MsgInterno.php?Tipo=0&IdPac=0&To='+To+'&ToEmail='+user[0].email+'&callphone='+user[0].telefone+'&From='+From+'&FromEmail='+FromEmail+'&Subject='+Subject+'&Content='+Content+'&Leido=0&Push=0&estado=1&Idpin='+IdPin;
			if(usephone==1){
				var phone=user[0].telefono;
				//alert(phone);
				if(phone!=null){
					phone = phone.replace(/[^0-9]/g, '');
					if(phone.length == 10 || phone.length==11) { 
						
						//alert("yup, its valid number digits");
					} else {
						alert("Phone number is not valid!");
						phone='Null';
					} 
				}else{
					alert("Health2me could not find a valid phone number!");
				}
			var cadena = '<?php echo $domain;?>/MsgInterno.php?Tipo=0&IdPac=0&To='+To+'&ToEmail='+user[0].email+'&NameDoctor='+user[0].Name+'&SurnameDoctor='+user[0].Surname+'&NameDoctorOrigin='+NameMed+'&SurnameDoctorOrigin='+SurnameMed+'&NamePatient='+user[0].Name+'&SurnamePatient='+user[0].Surname+'&callphone='+phone+'&From='+From+'&FromEmail='+FromEmail+'&Subject='+Subject+'&Content='+Content+'&Leido=0&Push=0&estado=1&Idpin='+IdPin;
			}else{
			var cadena = '<?php echo $domain;?>/MsgInterno.php?Tipo=0&IdPac=0&To='+To+'&ToEmail='+user[0].email+'&NameDoctor='+user[0].Name+'&SurnameDoctor='+user[0].Surname+'&NameDoctorOrigin='+NameMed+'&SurnameDoctorOrigin='+SurnameMed+'&NamePatient='+user[0].Name+'&SurnamePatient='+user[0].Surname+'&From='+From+'&FromEmail='+FromEmail+'&Subject='+Subject+'&Content='+Content+'&Leido=0&Push=0&estado=1&Idpin='+IdPin;;
			
			}
			//alert (cadena);
			//alert('patient iteration:'+i);
			if(i==1)
				break;
		}else if(senderoption==2){           //request would go to doctors
			if(Idpin!=-111){
				var oktogo=confirm("You have selected doctor to send request.This request would be send to unlock all applicable reports and not limited to only this report!");
				if(!oktogo)
					return;
				}
		   // alert('Permission Request sent to '+doc.Name + '.'+doc.Surname + ' at ' + doc.IdMEDEmail);
			
			var Content = 'Dr. '+NameMed+' '+SurnameMed+' has requested to see your (UserId:  '+To+') reportID'+IdPin+ ' Please confirm, or just close this message to reject.';
			if(usephone==1){
				var phone=doc.phone;
				//alert(phone);
				if(phone!=null){
					phone = phone.replace(/[^0-9]/g, '');
					if(phone.length == 10 || phone.length==11) { 
						
						//alert("yup, its valid number digits");
					} else {
						alert("Phone number is not valid!");
						phone='Null';
					} 
				}else{
					alert("Health2me could not find a valid phone number!")
				}
			var cadena = '<?php echo $domain;?>/MsgInterno.php?Tipo=1&IdPac='+user[0].Identif+'&To='+doc.id+'&ToEmail='+doc.IdMEDEmail+'&NameDoctor='+doc.Name+'&SurnameDoctor='+doc.Surname+'&NameDoctorOrigin='+NameMed+'&SurnameDoctorOrigin='+SurnameMed+'&NamePatient='+user[0].Name+'&SurnamePatient='+user[0].Surname+'&callphone='+phone+'&From='+From+'&FromEmail='+FromEmail+'&Subject='+Subject+'&Content='+Content+'&Leido=0&Push=0&estado=1&Idpin='+IdPin;
			}else{
			var cadena = '<?php echo $domain;?>/MsgInterno.php?Tipo=1&IdPac='+user[0].Identif+'&To='+doc.id+'&ToEmail='+doc.IdMEDEmail+'&NameDoctor='+doc.Name+'&SurnameDoctor='+doc.Surname+'&NameDoctorOrigin='+NameMed+'&SurnameDoctorOrigin='+SurnameMed+'&NamePatient='+user[0].Name+'&SurnamePatient='+user[0].Surname+'&From='+From+'&FromEmail='+FromEmail+'&Subject='+Subject+'&Content='+Content+'&Leido=0&Push=0&estado=1&Idpin='+IdPin;
			}
			//var cadena = '<?php echo $domain;?>/MsgInterno.php?Tipo=1&IdPac='+user[0].Identif+'&To='+doc.id+'&ToEmail='+doc.IdMEDEmail+'&NameDoctor='+doc.Name+'&SurnameDoctor='+doc.Surname+'&NameDoctorOrigin='+NameMed+'&SurnameDoctorOrigin='+SurnameMed+'&NamePatient='+user[0].Name+'&SurnamePatient='+user[0].Surname+'&callphone='+doc.phone+'&From='+From+'&FromEmail='+FromEmail+'&Subject='+Subject+'&Content='+Content+'&Leido=0&Push=0&estado=1&Idpin='+IdPin;
		}else{
			 alert("Incorrect option!");
			 return;
		}		
		//alert (cadena);
		
		var RecTipo = 'Temporal';
	                     $.ajax(
                                {
                                url: cadena,
                                dataType: "html",
                                async: false,
                                complete: function(){ 
								//alert('Completed');
                                },
                                success: function(data)
                                {
                                if (typeof data == "string") {
                                RecTipo = data;
                                }
                                }
                                });
                                
       //$('#CloseModal').trigger('click'); 
	   
	   alert (RecTipo);	    
	   //var Content = 'Dr. '+NameMed+' '+SurnameMed+' is requesting to establish connection with you (UserId:  '+To+'). Please click the button: </br><input type="button" href="www.inmers.com/ConfirmaLink?User='+To+'&Doctor='+From+'&Confirm='+RecTipo+'" class="btn btn-success" value="Confirm" id="ConfirmaLink" style="margin-top:10px; margin-bottom:10px;"> </br> to confirm, or just close this message to reject.';
	   
	   //EnMail(user[0].email, 'MediBANK Link Request', Content);  // NO SE USA AQUÍ, PERO SI FUNCIONA PERFECTAMENTE PARA ENVIAR MENSAJES DE EMAIL DESDE JAVASCRIPT
	   
	   }
	   
	   }
	   
	   $('#CloseModal').trigger('click');
	   //$('#BotonBusquedaPac').trigger('click');
	   location.reload(true);
     
    });
	    
 //Adding changes for the send button 
 
  $("#ConfirmaLink,#ConfirmaLinkAll").live('click',function() {
	     // Confirm
	     var subcadena='';
	     //var CallPhone = 0;
	     /*if ($('#c2').attr('checked')=='checked'){ 
	     	subcadena =' (will call phone number also)';
		    CallPhone = 1; 
	     }*/
		 
		 var whichreport=$(this).attr("value");
		 if(whichreport=="All reports"){
		 $('#Idpin').attr("value","00A");
		 }
		 var To= $('#userId').val();
    	 getUserData(To);
		 if (user[0].email==''){
			  //alert("Patient email not found. Request will be sent to reportcreator!");
			  var Text='<span>Patient email not found. Request will be sent to reportcreator!</span><br><br>';
			  Text=Text+'<p><input type="button" class="btn btn-success" value="Request Doctor" id="SendButton" style="margin-left:10px; margin-top:-15px;"></p>';
			  Text=Text+'<input type="checkbox" id="c2" name="cc"><label for="c2" style="margin-top:10px;"><span></span></label><i class="icon-phone"></i><span></span>Urgent(call phone) ';
			  $('#TextoSend').html(Text);
			 // return;
			 // senderoption=2;
		}else{
		//Show the option to select either patient or doctor. Depending on the selection also show the details.
			  var Text='<span>Please select "request Patient" or "request doctor".</span><span>The unlock request would be send accordingly.<span><br><br>';
			  Text=Text+'<p><input type="button" class="btn btn-success" value="Request Patient" id="SendButton" style="margin-left:20px; margin-top:-15px;">';
			  Text=Text+'<input type="button" class="btn btn-success" value="Request Doctor" id="SendButton" style="margin-left:25px; margin-top:-15px;"></p>';
			  Text=Text+'<input type="checkbox" id="c2" name="cc"><label for="c2" style="margin-top:30px;margin-left:10px;"><span></span></label><i class="icon-phone"></i><span></span>Urgent(call phone)';
			  $('#TextoSend').html(Text);
			  //  return;
		}
		$("#ConfirmaLink,#ConfirmaLinkAll").hide();
		$('#Thisreport,#Allreport').hide();
   });
   
   //Changes for adding messagings and notification services 
	/*var referral_state = parseInt($('#referral_state').val());
	//alert(referral_state);
	if (isNaN(referral_state)){
	referral_state=0;
	alert('The referral stages functionality is not working. Please contact Health2me!');
	}
	if(referral_state>=1){
		$("div#ack").css("color","#3d93e0");
		$("div#ack").children("*").css("color","#3d93e0");
		$("div#ack_btn a").css("color","#3d93e0");
		$("#ack_ok").show();
		$("div#ack_btn").append('<i style="margin-left:10px;font-size:15px" class="icon-arrow-right"></i>');
		if (referral_state>=2){
			$("div#app").css("color","#3d93e0");
			$("div#app").children("*").css("color","#3d93e0");
			$("div#app_btn a").css("color","#3d93e0");
			$("div#app_btn").append('<i style="margin-left:10px;font-size:15px" class="icon-arrow-right"></i>');
			if (referral_state>=3){
				$("div#infr").css("color","#3d93e0");
				$("div#infr").children("*").css("color","#3d93e0");
				$("div#infr_btn a").css("color","#3d93e0");
				$("div#infr_btn").append('<i style="margin-left:10px;font-size:15px" class="icon-arrow-right"></i>');
				if (referral_state==4){
					$("div#inpa").css("color","#3d93e0");
					$("div#inpa").children("*").css("color","#3d93e0");
					$("div#inpa_btn a").css("color","#3d93e0");
				
				}
			}
		}
	}
   
   
   /*$("button[id^='notif_btn']").live('click',function(){
   //alert('clicked closed'+$(this).parents("div[id^='notif_']").attr("id"));
   //$("div.ichat").show();
   
   var str=$(this).parents("div[id^='notif_']").attr("id");
   var id=/\d+/.exec(str);
   
   var cadena ='<?php echo $domain;?>/setNotificationStatus.php?Id='+id;
   var status=LanzaAjax(cadena);
   //alert(status);
   //$("div[id^='notif']").remove();  
   $(this).parents("div[id^='notif_']").remove();   
   e.stopPropagation(); // This is the preferred method.
   return false;    
   //$("div.ichat").show();
   });*/
   
  //  $("div[id^='notif_']").live('click',function(){
   //alert('clicked closed'+$(this).parents("div[id^='notif_']").attr("id"));
   //$("div.ichat").show();
   
  /* var str=$(this).attr("id");
   var id=/\d+/.exec(str);
   
   var cadena ='<?php echo $domain;?>/setNotificationStatus.php?Id='+id;
   var status=LanzaAjax(cadena);
   //alert(status);
   //$("div[id^='notif']").remove();  
   $(this).remove();   
   e.stopPropagation(); // This is the preferred method.
   return false;    
   //$("div.ichat").show();
   });
   
    $("div[id^='notif_']").live("mouseenter",function () {
			$(this).css("background","LightSteelBlue");
			$(this).css("cursor","pointer");
		});
		
	$("div[id^='notif_']").live("mouseleave",function () {
			$(this).css("background","");
	});
   /*$("div#app_btn a,div#infr_btn a").live('click',function(){
   
	if($(this).attr("title")=="Schedule"){
	$("div#app").css("color","#3d93e0");
	$("div#app").children("*").css("color","#3d93e0");
	$(this).css("color","#3d93e0");
	}else if($(this).attr("title")=="IReview"){
	$("div#infr").css("color","#3d93e0");
	$("div#infr").children("*").css("color","#3d93e0");
	$(this).css("color","#3d93e0");
	}
	
   });*/
   /*var queUrl ='<?php echo $domain;?>/getInboxMessage.php?IdMED=<?php echo $MedID;?>';
   $('#datatable_3').load(queUrl);
   $('#datatable_3').trigger('update');*/
   
   //Below code is written for status update on the notification window
   
 /*  var cadena ='<?php echo $domain;?>/getNotificationCount.php?IdMED=<?php echo $MedID;?>';
   var getCount=LanzaAjax(cadena);
   if(parseInt(getCount)){
   $('#notificaton_num').text(getCount);
   }
   
   var num=parseInt($('#notificaton_num').text());
   if(!num){
   $('#notificaton_num').hide();
   $('#notification_triangle').hide();
   }
   
   setTimeout(function(){
	   $('#newinbox').trigger('click');},1000);
   
   $(document).click(function() {
    
    //$('#notificaton_num').hide();
	//$('#notification_triangle').hide();
	$("#notification_window").find("*").hide(); 
   });
   
   $("a#notification_bar").live('click',function(e){
   
    //$("#notification_window").show();   
   $("a#notification_bar").toggle(
   function(){
   var notify_num=parseInt($('#notificaton_num').text());
   if(notify_num){
	$('#notificaton_num').text(0);
	$('#notificaton_num').hide();
	$('#notification_triangle').hide();
	}
	//var notify_num=78;
	var queUrl ='<?php echo $domain;?>/getNotificationMessages.php?IdMED=<?php echo $MedID;?>';
    //
    
    setTimeout(function(){
	$("#getnotificationmessages").load(queUrl);
	$("#getnotificationmessages").trigger('update');
	},1); 
    $("#notification_window").find("*").show();	
	//$("#getnotificationmessages").find("*").show();
	//alert('here');
   }
   ,function(){  
   //alert('in hidden option');
   $("#notification_window").find("*").hide();   
   });
   
   e.stopPropagation(); // This is the preferred method.
   //return false;    
   
   });
   
        
   $("div#ack_btn a").live('click',function(){
    if(referral_state==0){
	alert('The referral stages functionality is not working. Please contact Health2me!');
	}else{
		
		if(referral_state>1)
			 displaynotification('status','This stage is already complete!');
			
		
	}
	
	});
	
	
   $("div#inpa_btn a").live('click',function(){
    if(referral_state==0){
	alert('The referral stages functionality is not working. Please contact Health2me!');
	}else{
	
	    if(referral_state==4)
			 displaynotification('status','This stage is already complete!');
		
		if(referral_state<3)
			 alert('Previous Stages are not complete!');
			
		else if(referral_state==3){
		$("div#inpa").css("color","#3d93e0");
		$("div#inpa").children("*").css("color","#3d93e0");
		$(this).css("color","#3d93e0");
		var cadena='setReferralStage.php?referralid=<?php echo $referral_id;?>&stage=4';
		LanzaAjax(cadena);
		referral_state=4;
		displaynotification('status','Referral stages for this patient completed!');
		var cadena='push_server.php?message="Referral stages has been completed"&channel='+<?php echo $otherdoc?>;
		var RecTipo=LanzaAjax(cadena);
		
		var content="referal stage visit is completed. This is a System Generated Message";
		var subject="Referral stage information";
		var reportids=0;
		var cadena='sendMessage.php?sender=<?php echo $MedID;?>&receiver=<?php echo $otherdoc;?>&patient=<?php echo $USERID;?>'+'&content='+content+'&subject='+subject+'&attachments='+reportids+'&connection_id=<?php echo $referral_id;?>';
		var RecTipo=LanzaAjax(cadena);
		//update referral table.
		}
	}
	
	});
	
	$("div#app_btn a").live('click',function(){
	if(referral_state==0){
	alert('The referral stages functionality is not working. Please contact Health2me!');
	}else{
		if(referral_state>=2)
			 displaynotification('status','This stage is already complete!');
		
			
		else if(referral_state==1){
			var conf=confirm("Health2me couldn't find any appointments for this patient. Do you confirm that patient meeting has happened?");
			if(conf){
				$("div#app").css("color","#3d93e0");
				$("div#app").children("*").css("color","#3d93e0");
				$(this).css("color","#3d93e0");
				
				var cadena='setReferralStage.php?referralid=<?php echo $referral_id;?>&stage=2';
				LanzaAjax(cadena);
				referral_state=2;
				$("div#app_btn").append('<i style="margin-left:10px;font-size:15px" class="icon-arrow-right"></i>');
				var cadena='push_server.php?message="Referral stage appointment is completed"&channel='+<?php echo $otherdoc?>;
				var RecTipo=LanzaAjax(cadena);
				
				var content="referal stage appointment is completed. This is a System Generated Message";
				var subject="Referral stage information";
				var reportids=0;
				var cadena='sendMessage.php?sender=<?php echo $MedID;?>&receiver=<?php echo $otherdoc;?>&patient=<?php echo $USERID;?>'+'&content='+content+'&subject='+subject+'&attachments='+reportids+'&connection_id=<?php echo $referral_id;?>';
				var RecTipo=LanzaAjax(cadena);
				
				//update referral table.
			}
		 }
	 }
   });
   
   $("div#infr_btn a").live('click',function(){
   if(referral_state==0){
	alert('The referral stages functionality is not working. Please contact Health2me!');
	}else{
		if(referral_state>=3)
			 displaynotification('status','This stage is already complete!');
		else if(referral_state<2){
			 alert('Previous Stages are not complete!');
			 
		}else if(referral_state==2){
			var conf=confirm("All the report information has not been reveiwed for this patient. Do you still want to confirm review stage?");
			if(conf){
				$("div#infr").css("color","#3d93e0");
				$("div#infr").children("*").css("color","#3d93e0");
				$(this).css("color","#3d93e0");
				
				var cadena='setReferralStage.php?referralid=<?php echo $referral_id;?>&stage=3';
				LanzaAjax(cadena);
				referral_state=3;
				$("div#infr_btn").append('<i style="margin-left:10px;font-size:15px" class="icon-arrow-right"></i>');
				var cadena='push_server.php?message="Referral stage information review is completed"&channel='+<?php echo $otherdoc?>;
				var RecTipo=LanzaAjax(cadena);
				
				var content="referal stage information review is completed. This is a System Generated Message";
				var subject="Referral stage information";
				var reportids=0;
				var cadena='sendMessage.php?sender=<?php echo $MedID;?>&receiver=<?php echo $otherdoc;?>&patient=<?php echo $USERID;?>'+'&content='+content+'&subject='+subject+'&attachments='+reportids+'&connection_id=<?php echo $referral_id;?>';
				var RecTipo=LanzaAjax(cadena);
				//update referral table.
			}
		 }
	}
   });
   
   $('#newinbox').live('click',function(){
      //alert('trigger');
	 var queUrl ='<?php echo $domain;?>/getInboxMessage.php?IdMED=<?php echo $MedID;?>&patient=<?php echo $USERID;?>';
	   $('#datatable_3').load(queUrl);
	   $('#datatable_3').trigger('update');
           
   });
   
   $('#newoutbox').live('click',function(){
      //alert('trigger');
	 var queUrl ='<?php echo $domain;?>/getOutboxMessage.php?IdMED=<?php echo $MedID;?>&patient=<?php echo $USERID;?>';
	   $('#datatable_4').load(queUrl);
	   $('#datatable_4').trigger('update');
           
   });
   
   $('#compose_message').live('click',function(){
    
   $('#messagecontent_inbox').attr('value','');
   $('#subjectname_inbox').attr('value','');
   $('#subjectname_inbox').removeAttr("readonly");   
   $('#messagedetails').hide();
   $('#replymessage').show();
   $("#attachments").empty();
   $('#attachments').hide();
   $('#Reply').hide();
   $("#CloseMessage").hide();
   $('#Attach').hide();
   $('#sendmessages_inbox').show();
   $('#attachreports').show();
   $('#message_modal').trigger('click');
      
   
   });
   
   $('.CFILA').live('click',function(){
       var id = $(this).attr("id");
	   //displaynotification('Message ID'+ id);
	   $('input[type=checkbox][id^="reportcol"]').each(
        function () {
		 $('input[type=checkbox][id^="reportcol"]').checked=false;
		});
	   reportcheck.length=0;
	   var content=$(this).find('span#'+id).text().replace(/br8k/g,"\n").replace(/sp0e/g," ");
	   $(this).find('span').hide();
	   var reportsattached=$(this).find('ul#'+id).text();
	   //alert(reportsattached);
	   $("#attachments").empty();
	   if(reportsattached){
	   //alert("into attachments");
	   var ElementDOM ='All';
	   var EntryTypegroup ='0';
	   var Usuario = $('#userId').val();
	   var MedID =$('#MEDID').val();
	   
		var queUrl ='CreateAttachmentStream.php?ElementDOM=na&EntryTypegroup='+EntryTypegroup+'&Usuario='+Usuario+'&MedID='+MedID+'&Reports='+reportsattached;
	    var RecTipo=LanzaAjax(queUrl);
		//alert(RecTipo);
		$("#attachments").append(RecTipo);
      	/*$("#attachments").load(queUrl);
    	$("#attachments").trigger('update');*/
	/*	$("#attachments").show();
		}else{
		$('#attachments').hide();
		//alert("no attachments");
		}
	   //content.replace(/[break]/g,"\n").replace(/[space]/g," ");
	   //alert($(this).find('a').text());
	   //displaynotification('Message read');
	   //$('#attachments').hide();
	   $('#Attach').hide();
	   $('#messagedetails').show();
       $('#replymessage').hide();
	   $("#Reply").attr('value','Reply');
       $("#Reply").show();
       $("#CloseMessage").show();
	   $('#messagedetails').val(content);
	   $('#messagedetails').attr('readonly','readonly');
	   $('#messagedetails,#subjectname_inbox').css("cursor","pointer");
	   $('#subjectname_inbox').val($(this).find('a').text());
	   $('#subjectname_inbox').attr('readonly','readonly');
	   $('#replymessage').hide();
	   $('#sendmessages_inbox').hide();
	   $('#attachreports').hide();
	   //$('#clearmessage').hide();
	   $('#message_modal').trigger('click');
	   var cadena='setMessageStatus.php?msgid='+id;
	   var RecTipo=LanzaAjax(cadena);
	   setTimeout(function(){
	   $('#newinbox').trigger('click');},1000);	   
   });
   
    $('.CFILA_out').live('click',function(){
       var id = $(this).attr("id");
	   //displaynotification('Message ID'+ id);
	   $('input[type=checkbox][id^="reportcol"]').each(
        function () {
		 $('input[type=checkbox][id^="reportcol"]').checked=false;
		});
	   reportcheck.length=0;
	   var content=$(this).find('span#'+id).text().replace(/br8k/g,"\n").replace(/sp0e/g," ");
	   $(this).find('span').hide();
	   var reportsattached=$(this).find('ul#'+id).text();
	   //alert(reportsattached);
	   $("#attachments").empty();
	   if(reportsattached){
	   //alert("into attachments");
	   var ElementDOM ='All';
	   var EntryTypegroup ='0';
	   var Usuario = $('#userId').val();
	   var MedID =$('#MEDID').val();
	   
		var queUrl ='CreateAttachmentStream.php?ElementDOM=na&EntryTypegroup='+EntryTypegroup+'&Usuario='+Usuario+'&MedID='+MedID+'&Reports='+reportsattached;
	    var RecTipo=LanzaAjax(queUrl);
		//alert(RecTipo);
		$("#attachments").append(RecTipo);
      	/*$("#attachments").load(queUrl);
    	$("#attachments").trigger('update');*/
		/*$("#attachments").show();
		}else{
		$('#attachments').hide();
		//alert("no attachments");
		}
	   //content.replace(/[break]/g,"\n").replace(/[space]/g," ");
	   //alert($(this).find('a').text());
	   //displaynotification('Message read');
	   //$('#attachments').hide();
	   $('#Attach').hide();
	   $('#messagedetails').show();
       $('#replymessage').hide();
	   $("#Reply").attr('value','Reply');
       $("#Reply").hide();
       $("#CloseMessage").show();
	   $('#messagedetails').val(content);
	   $('#messagedetails').attr('readonly','readonly');
	   $('#messagedetails,#subjectname_inbox').css("cursor","pointer");
	   $('#subjectname_inbox').val($(this).find('a').text());
	   $('#subjectname_inbox').attr('readonly','readonly');
	   $('#replymessage').hide();
	   $('#sendmessages_inbox').hide();
	   $('#attachreports').hide();
	   //$('#clearmessage').hide();
	   $('#message_modal').trigger('click');
	   /*var cadena='setMessageStatus.php?msgid='+id;
	   var RecTipo=LanzaAjax(cadena);
	   setTimeout(function(){
	   $('#newoutbox').trigger('click');},1000);*/
/*	   
   });
   
   var reportids='';
   var reportcheck = new Array();
   
     
   
   $("#Reply").live('click',function(){
    //reportids='';
	/*$('input[type=checkbox][id^="reportcol"]').each(
     function () {
				var sThisVal = (this.checked ? "1" : "0");
				if(sThisVal==1){
				reportcheck.push(this.id);
				}
				
	});
	for (i = 0 ; i < reportcheck.length; i++ ){
				
		document.getElementById(reportcheck[i]).checked = false;
				
	}*/
  /* var subject_name='RE:'+($('#subjectname_inbox').val()).replace(/RE:/,'');
   $('#subjectname_inbox').val(subject_name);   
   $('#messagedetails').hide();
   $('#replymessage').show();
   $('#attachments').hide();
   $('#Attach').hide();
   $(this).hide();
   $("#CloseMessage").hide();
   $('#sendmessages_inbox').show();
   $('#attachreports').show();
   //$('#clearmessage').show();
      
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
				
				 /*var cadena='setMessageStatus.php?msgid='+idp+'&delete=1';
				 LanzaAjax(cadena);*/
	/*			}
			
				
	});
	 //alert(reportids);
/*	var conf=false;
	if(reportids>'')
		conf=confirm("Confirm Attachments");
	
	if(conf){
	$("#Reply").trigger('click');
	$("#attachreportdiv").append('<i id="attachment_icon" class="icon-paper-clip" style="margin-left:10px"></i>');
	//alert(reportids);
	}else{
	reportids='';
	for (i = 0 ; i < reportcheck.length; i++ ){
				
		document.getElementById(reportcheck[i]).checked = false;
				
	}
	reportcheck.length=0;
	$("#Reply").trigger('click');
	}
      
   });
   
   var isloaded=false;   //This variable is to make sure the page loads the report only once.
   
   $('#attachreports').live('click',function(){
   
   $('input[type=checkbox][id^="reportcol"]').each(
     function () {
				var sThisVal = (this.checked ? "1" : "0");
				if(sThisVal==1){
				reportcheck.push(this.id);
				}
				
	});
	/*if(!isloaded){
	//$('#attachments').remove();*/
/*	$("#attachments").empty();
	createPatientReports();
	//isloaded=true;
	//}
	setTimeout(function(){
	for (i = 0 ; i < reportcheck.length; i++ ){
				
		document.getElementById(reportcheck[i]).checked = true;
				
	}},400);
   $("#attachment_icon").remove();
   $('#sendmessages_inbox').hide();
   $('#replymessage').hide();
   $(this).hide();   
   $('#attachments').show();
   $('#Attach').show();
   $("#Reply").attr('value','Back');
   $("#Reply").show();
   
   
   });
   
  $('#sendmessages').live('click',function(){
	
	 var sel=$('#doctorsdetails').find(":selected").attr('id');
	 var content=$('#messagecontent').val().replace(/ /g,"sp0e").replace(/\r\n|\r|\n/g,"br8k");
	 //boxText.replace(/<br\s?\/?>/g,"\n");
	 var subject=$('#subjectname').val();
	 if(subject==''||content=='')
	  displaynotification('Error','Error sending message.Empty subject or content area!');
	 else{
	 var cadena='sendMessage.php?sender=<?php echo $MedID;?>&receiver=<?php echo $otherdoc;?>&patient=<?php echo $USERID;?>'+'&content='+content+'&subject='+subject;
	 var RecTipo=LanzaAjax(cadena);
	 //alert(RecTipo);
	 $('#messagecontent').attr('value','');
	 $('#subjectname').attr('value','');
	 displaynotification('status',RecTipo);
	 //$('#add-regular').trigger('click');
	 /*
     var cadena='push_server.php?message="message from a doctor"&channel='+<?php echo $otherdoc?>;
	 */
  /*   var RecTipo=LanzaAjax(cadena);
	 }
	 
	
	 
  });
  
  
  $('#sendmessages_inbox').live('click',function(){
	
	 var sel=$('#doctorsdetails').find(":selected").attr('id');
	 var content=$('#messagecontent_inbox').val().replace(/ /g,"sp0e").replace(/\r\n|\r|\n/g,"br8k");
	 //boxText.replace(/<br\s?\/?>/g,"\n");
	 var subject=$('#subjectname_inbox').val();
	 //alert(reportids);
	 reportids = reportids.replace(/\s+$/g,' ');
	 /*if(subject==''||content=='')
	  displaynotification('Error','Error sending message.Empty subject or content area!');
	 else{*/
	 
      /*
      var cadena='sendMessage.php?sender=<?php echo $MedID;?>&receiver=<?php echo $otherdoc;?>&patient=<?php echo $USERID;?>'+'&content='+content+'&subject='+subject+'&attachments='+reportids+'&connection_id=<?php echo $referral_id;?>';
	 */
  /*   var RecTipo=LanzaAjax(cadena);
	 //alert(RecTipo);
	 $('#messagecontent_inbox').attr('value','');
	 $('#subjectname_inbox').attr('value','');
	 displaynotification('status',RecTipo);
	 //$('#add-regular').trigger('click');
	 /*
     var cadena='push_server.php?FromDoctorName=<?php echo $IdMEDName;?>&FromDoctorSurname=<?php echo $IdMEDSurname;?>&Patientname=<?php echo $MedUserName; ?>&PatientSurname=<?php echo $MedUserSurname; ?>&IdUsu=<?php echo $USERID;?>&message= New Message <br>From: Dr. <?php echo $IdMEDName;?> <?php echo $IdMEDSurname;?><br>Subject: '+(subject).replace(/RE:/,'')+'&channel='+<?php echo $otherdoc?>;
	 */
     //alert(cadena);
/*	 var RecTipo=LanzaAjax(cadena);
	 //}
	 reportids='';
	 $("#attachment_icon").remove();
	 $('#message_modal').trigger('click');
  });
  
  $("#delete_message").live('click',function(){
   var num=0;
   var conf=confirm('The message will be deleted permanently!Press Ok to continue.');
   if(conf){
   $('input[type=checkbox][id^="checkcol"]').each(
   function () {
				var sThisVal = (this.checked ? "1" : "0");
				
				//sList += (sList=="" ? sThisVal : "," + sThisVal);
				if(sThisVal==1){
				 var idp=$(this).parents("tr.CFILA_P").attr("id");
				 //alert("Id "+idp+" selected"); 
				 //messageid=messageid+idp+' ,';
				 num=num+1;
				 var cadena='setMessageStatus.php?msgid='+idp+'&delete=1';
				 LanzaAjax(cadena);
				}
			
				
	});
	
	if(num)
	{
	setTimeout(function(){
	   $('#newinbox').trigger('click');},50);
	displaynotification('status','Seleted Messages Deleted!');
	}else{
	displaynotification('status','No Messages Selected!');
	}
	}
	
   });
   
  function createPatientReports(){
		var ElementDOM ='All';
		var EntryTypegroup ='0';
		var Usuario = $('#userId').val();
		var MedID =$('#MEDID').val();
		
		var queUrl ='CreateAttachmentStream.php?ElementDOM=na&EntryTypegroup='+EntryTypegroup+'&Usuario='+Usuario+'&MedID='+MedID;
      	//var queUrl ='CreateAttachmentStream.php?ElementDOM=na&EntryTypegroup='+EntryTypegroup+'&Usuario='+Usuario+'&MedID='+MedID+'&Reports=1226';
      	$("#attachments").load(queUrl);
    	$("#attachments").trigger('update');
  
  }
        
  function highlightattachedreports(){
            var reportstobereviewed=$('#reportid_review').val();
               // alert(reportstobereviewed);
           var reportstobereviewed_ids_new=reportstobereviewed.split(" "); 
           for (var i = 0, len = reportstobereviewed_ids_new.length; i < len; ++i)
           {
              //alert(reportstobereviewed_ids_new[i]);
              //id^="reportcol"]'
              $('i[id^="report-eye"]').each(function(){
              
              var id=parseInt($(this).parents("div.note2").attr('id'));
              //alert(id);
              if(id==parseInt(reportstobereviewed_ids_new[i]))
              {
               $(this).css("color","#000000");
                $(this).parents("div.note2").css({"border": "2px solid blue"});
               $(this).parents("div.note2").css({"border-radius": "7px"});
               $(this).parents("div.note2").css({"outline": "none"});
               $(this).parents("div.note2").css({"border-color": "blue"});
               $(this).parents("div.note2").css({"box-shadow": "0 0 10px blue"});
                   
              }
              
              
              
              });
           
           
           }
   
  
  
  
  
  }
*/  
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
	
  function getReportCreator(Idpin,Iddoc,Idusu) {
    
 	var cadenaGUD = '<?php echo $domain;?>/getReportCreator.php?Idpin='+Idpin+'&Iddoc=<?php echo $MedID;?>&Idusu=<?php echo $IdUsu;?>';
	//alert(cadenaGUD);
    $.ajax(
           {
           url: cadenaGUD,
           dataType: "json",
           async: false,
           success: function(data)
           {
           //alert ('success');
           reportcreator = data.items;
           }
           });
    }
		 
function urlExists(url, callback){
  $.ajax({
    type: 'HEAD',
    url: url,
    success: function(){
      callback(true);
    },
    error: function() {
      callback(false);
    }
  });
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
 
	}); 		
	
	$(window).load(function() {
		/* var val=10;
	 (function myLoop (i,val) {          
		setTimeout(function () {   
         $("div[class='bar']").width(val); 
          val=val+90;          
		  if (--i) myLoop(i,val);     
	   }, 200)
	})(15,10);*/
	//$('.tooltip').tooltipster('show');
	var progressbar = $( "#progressbar" ),
	progressLabel = $( ".progress-label" );
	//progressbar.width(1025);
	//progressbar.css({"margin-left":'125px'});
	progressbar.progressbar({
	value: false,
	change: function() {
	progressLabel.css({"color":'white'});
	progressLabel.css({"text-shadow":'none'});
	progressLabel.text( progressbar.progressbar( "value" ) + "%" );
	},
	complete: function() {
	progressLabel.css({"color":'white'});
	progressLabel.css({"font-size":'10px'});
	progressLabel.css({"text-shadow":'none'});
	progressLabel.css({"text-align":'center'});
	progressLabel.css({"margin-left":'-50px'});
	progressLabel.text( "Decryption Complete!" );
	setTimeout( function(){
	$("#progressstatus").hide();
	$("#progressbar").hide();
	$("#encryptbox").hide();
	$("#tabsWithStyle").show();
       // highlightattachedreports();
	 /*  var reportstobereviewed=$('#reportid_review').val();
	   var reportstobereviewed_ids=reportstobereviewed.split(" "); 
	   for (var i = 0, len = reportstobereviewed_ids.length; i < len; ++i)
	   {
		  //alert(reportstobereviewed_ids[i]);
		  //id^="reportcol"]'
		  $('i[id^="report-eye"]').each(function(){
		  
		  var id=parseInt($(this).parents("div.note2").attr('id'));
		  //alert(id);
		  if(id==parseInt(reportstobereviewed_ids[i]))
		  {
		   $(this).css("color","#000000");
          // $(this).parents("div.note2").css({"border": "3px solid blue"});
            
           $(this).parents("div.note2").css({"border": "2px solid blue"});
           $(this).parents("div.note2").css({"border-radius": "7px"});
           $(this).parents("div.note2").css({"outline": "none"});
           $(this).parents("div.note2").css({"border-color": "blue"});
           $(this).parents("div.note2").css({"box-shadow": "0 0 10px blue"});
		  }
		  
		  
		  
		  });
	   
	   
	   }*/
		
	
	}, 2000 );
	}
	});
	function progress() {
	var progressbarValue = progressbar.find( ".ui-progressbar-value" );
	var val = progressbar.progressbar( "value" ) || 0;
	progressbar.progressbar( "value", val + 1 );
	progressbar.css({"background": 'black'});
	progressbarValue.css({"background": '#4169E1'});    //#5882FA
	if ( val < 99 ) {
	setTimeout( progress, 50 );
	}
	}
	setTimeout( progress, 1000 );
	
	/*setTimeout(function(){
	 //$("#progressbar").hide();
	 //alert("triggered");
	 $('.TABES:eq(9)').trigger('update');
	 
	 },3000);*/
	
	});
    
	function next_click()
	{
		if(curr_file==-1)
		{
			curr_file=0;
			
		}
		else
		{
			curr_file = (curr_file + 1)%(list.length);
		}
		document.getElementById("verified_count_label").innerHTML = parseInt(curr_file)+1 + '/' + parseInt(list.length);
		var file_name = list[curr_file];
		var contenURL;
		//alert(file_name);
		
		
		
		if(file_name == 'lockedfile.png')
		{
			contenURL = '<?php echo $domain ;?>/images/'+file_name;
		}
		else if(file_name == 'deletedfile.png')
		{
			contenURL = '<?php echo $domain ;?>/images/'+file_name;
		}
		else
		{
			var cadena = '<?php echo $domain;?>/DecryptFile.php?rawimage='+file_name+'&queMed='+<?php echo $MedID;?>;
			//alert(cadena);
			var RecTipo = LanzaAjax (cadena);
			//alert(RecTipo);
			contenURL =   '<?php echo $domain ;?>/temp/<?php echo $_SESSION['MEDID'] ;?>/Packages_Encrypted/'+file_name;
		}   	
		
		var conten =  '<iframe id="ImagenN1" style="border:1px solid #666CCC" title="PDF" src="'+contenURL+'" alt="Loading" frameborder="1" scrolling="auto" height="850" width="600" ></iframe>';
		$('#AreaConten1').html(conten);			
		
	}
	
	function previous_click()
	{
		//alert(curr_file);
		if(curr_file==0)
		{
			curr_file=list.length - 1;
		}
		else
		{
			curr_file=(curr_file-1)%(list.length);
		}
		
		//alert(curr_file + '   ' + list.length);
		//alert(curr_file);
		document.getElementById("verified_count_label").innerHTML = parseInt(curr_file)+1 + '/' + parseInt(list.length);
		var file_name = list[curr_file];
		//alert(file_name);
		
		
		var contenURL;
		//alert(file_name);
		if(file_name == 'lockedfile.png')
		{
			contenURL = '<?php echo $domain ;?>/images/'+file_name;
		}
		else if(file_name == 'deletedfile.png')
		{
			contenURL = '<?php echo $domain ;?>/images/'+file_name;
		}
		else
		{
			var cadena = '<?php echo $domain;?>/DecryptFile.php?rawimage='+file_name+'&queMed='+<?php echo $MedID;?>;
			//alert(cadena);
			var RecTipo = LanzaAjax (cadena);
			//alert(RecTipo);
			contenURL =   '<?php echo $domain ;?>/temp/<?php echo $_SESSION['MEDID'] ;?>/Packages_Encrypted/'+file_name;
		}   	
		
		var conten =  '<iframe id="ImagenN1" style="border:1px solid #666CCC" title="PDF" src="'+contenURL+'" alt="Loading" frameborder="1" scrolling="auto" height="850" width="600" ></iframe>';
		$('#AreaConten1').html(conten);			
	
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
	
  

  </body>
</html>
<?php

function url_exists($url) {
    if (!$fp = curl_init($url)) return false;
    return true;
}

?>
<?php
function CreaTimeline($Usuario,$MedID,$pass)
{
 require("environment_detail.php");

 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];


	 $tbl_name="usuarios"; // Table name
		
	 // Connect to server and select databse.
$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }	
		
		//$queUsu = $_GET['Usuario'];
		//$queMed = $_GET['IdMed'];
	 $queUsu = $Usuario;
	 $queMed = 0;


     $sql=$con->prepare("SELECT * FROM usuarios where Identif =?");
	 $sql->bindValue(1, $queUsu, PDO::PARAM_INT);
	 
	 
     $q = $sql->execute();
     $row = $sql->fetch(PDO::FETCH_ASSOC);
     
     $Name = $row['Name'];
     $Surname = $row['Surname'];
     
     // Meter tipos en un Array
     $sql=$con->prepare("SELECT * FROM tipopin");
     $q = $sql->execute();
     
     $Tipo[0]='N/A';
     while($row=$sql->fetch(PDO::FETCH_ASSOC)){
     	$Tipo[$row['Id']]=$row['NombreEng'];
     }
     
     $Tipo[999]='N/A';
     // Meter clases en un Array
     $Clase[999]='Episode';
     $Clase[0]='Episode';
     $Clase[1]='Check or Preventive';
     $Clase[2]='Isolated Report';
     $Clase[3]='Drug Data';

	 $email = $row['email'];
     $hash = md5( strtolower( trim( $email ) ) );
	 $avat = 'identicon.php?size=50&hash='.$hash;


//             "media":"'$domaindev'/images/ReportsGeneric.png",



$cadena='{
    "timeline":
    {
        "headline":"Health Events",
        "type":"default",
        "text":"<p>User Id.: '.$queUsu.'</p>",
        "asset": {
            "media":"'.$domain.'/images/ReportsGeneric.png", 
            "credit":"(c) health2.me",
            "caption":"Use side arrows for browsing"
        },
        "date": [               
        ';



//getting IdPin for blind reports

//$blindReprtId=array();
//$blindReprtId=blindReports($MedID,$queUsu);
//BLOCKSLIFEPIN $result = mysql_query("SELECT * FROM blocks ORDER BY FechaInput DESC");

$sql_query=$con->prepare("select distinct(idDoctor) from doctorsgroups where idDoctor IN (select Idcreator FROM usuarios where Identif=?) or idGroup IN (select idGroup from doctorsgroups where idDoctor IN (select Idcreator FROM usuarios where Identif=?))");
$sql_query->bindValue(1, $queUsu, PDO::PARAM_INT);
$sql_query->bindValue(2, $queUsu, PDO::PARAM_INT);


	$res = $sql_query->execute();
	
	$privateDoctorID=array();
	$num=0;
	while($rowp=$sql_query->fetch(PDO::FETCH_ASSOC)){
		$privateDoctorID[$num]=$rowp['idDoctor'];
		$num++;
	}
	/*if($privateDoctorID==null)
		$privateDoctorID[0]=$MedID;*/
	
$sql_que=$con->prepare("select Id from tipopin where Agrup=9");
	$res=$sql_que->execute();
	
	$privatetypes=array();
	$num1=0;
	while($rowpr=$sql_que->fetch(PDO::FETCH_ASSOC)){
		$privatetypes[$num1]=$rowpr['Id'];
		$num1++;
}
#####changes for blind report#########
/*$sql1="SELECT Idpin,Tipo FROM lifepin where IdUsu ='$queUsu' and Tipo NOT IN (select Id from tipopin where Agrup=9) and IdMed IS NOT NULL and (IdMed NOT IN ((select distinct(idDoctor) from doctorsgroups where idGroup IN (select idGroup from doctorsgroups where idDoctor= '$MedID'))) 
and IdMed NOT IN (select distinct(idDoctor) from doctorsgroups where idGroup IN (select idGroup from doctorsgroups where idDoctor IN (Select idmed from doctorslinkdoctors where idmed2='$MedID' and IdPac='$queUsu'))))";
//and IdMed NOT IN (Select idmed from doctorslinkdoctors where idmed2='$MedID' and IdPac='$queUsu'))";*/
$sql1=$con->prepare("SELECT Idpin,Tipo FROM lifepin where (markfordelete IS NULL or markfordelete=0) and IdUsu =? and Tipo NOT IN (select Id from tipopin where Agrup=9) and IdMed !=0 and IdMed IS NOT NULL and IdMed!=? and IdMed NOT IN (select distinct(idDoctor) from doctorsgroups where idGroup IN (select idGroup from doctorsgroups where idDoctor=?)) and IdMed NOT IN (select idmed from doctorslinkdoctors where idmed2=? and IdPac=? and estado=2) and IdMed NOT IN (select distinct(idDoctor) from doctorsgroups where idGroup IN (select idGroup from doctorsgroups where idDoctor IN (Select idmed from doctorslinkdoctors where idmed2=? and IdPac=? and estado=2)))");
$sql1->bindValue(1, $queUsu, PDO::PARAM_INT);
$sql1->bindValue(2, $MedID, PDO::PARAM_INT);
$sql1->bindValue(3, $MedID, PDO::PARAM_INT);
$sql1->bindValue(4, $MedID, PDO::PARAM_INT);
$sql1->bindValue(5, $queUsu, PDO::PARAM_INT);
$sql1->bindValue(6, $MedID, PDO::PARAM_INT);
$sql1->bindValue(7, $queUsu, PDO::PARAM_INT);

$q1=$sql1->execute();

	$size=0;
	$blindReportId=array();
	while($row1=$sql1->fetch(PDO::FETCH_ASSOC)){
		
		$ReportId=$row1['Idpin'];
		$type=$row1['Tipo'];
		/*if($type==null)
			$type=-1;*/
		if(in_array($type,$privatetypes)){
			if(!in_array($MedID,$privateDoctorID)){
				continue;
			}
		}
		$query=$con->prepare"SELECT estado FROM doctorslinkusers where IdMed=? and IdUs=? and Idpin=?");
		$query->bindValue(1, $MedID, PDO::PARAM_INT);
		$query->bindValue(2, $queUsu, PDO::PARAM_INT);
		$query->bindValue(3, $ReportId, PDO::PARAM_INT);
		
		$q11=$query->execute();
		if($rowes=$query->fetch(PDO::FETCH_ASSOC)){
			$estad=$rowes['estado'];
			if($estad==1){
				$blindReportId[$size]=$ReportId;
				$size++;
			}
		}else{
			$blindReportId[$size]=$ReportId;
			$size++;
		}
		
	}
	
 $sql_que=$con->prepare("SELECT IdPin FROM lifepin WHERE markfordelete=1 and IdUsu=?");
 $sql_que->bindValue(1, $queUsu, PDO::PARAM_INT);

 
	$res = $sql_que->execute();
	
	$deletedreports=array();
	$num2=0;
	if($res){
	while($rowpr=$sql_que->fetch(PDO::FETCH_ASSOC)){
		
		$deletedreports[$num2]=$rowpr['IdPin'];
		$num2++;
	}}else{
	$deletedreports[0]=0;
	}
	
$result = $con->prepare("SELECT * FROM lifepin WHERE IdUsu=? ORDER BY Fecha DESC LIMIT 50");
$result->bindValue(1, $queUsu, PDO::PARAM_INT);
$result->execute();

$numero=$result->rowCount();
$n=0;

while ($row = $result->fetch(PDO::FETCH_ASSOC))
{    
 
	$extensionR = substr($row['RawImage'],strlen($row['RawImage'])-3,3);
	$ImageRaiz = substr($row['RawImage'],0,strlen($row['RawImage'])-4);
	$type=$row['Tipo'];

    // Override 'Pending' limitation for users (related to blinded reports?)
    unset ($blindReportId);
    $blindReportId = Array();
    // Override 'Pending' limitation for users (related to blinded reports?)
    
	if(!in_array($row['IdPin'], $blindReportId)){
				
		  //For private report functionality
		  if(in_array($type,$privatetypes)){
     		if(!in_array($MedID,$privateDoctorID)){
     				continue;
			}
		 }
		
		 if(!in_array($row['IdPin'], $deletedreports)){
		  if ($extensionR!='jpg')
			{
				decrypt_files($row['RawImage'],$MedID,$pass);
				$selecURL =$domain.'/temp/'.$MedID.'/PackagesTH_Encrypted/'.$ImageRaiz.'.png';
				$selecURLAMP =$domain.'/temp/'.$MedID.'/Packages_Encrypted/'.$ImageRaiz.$extensionR;
			}
			else {
			if	($row['CANAL']==7){
				decrypt_files($row['RawImage'],$MedID,$pass);
				$selecURL =$domain.'/temp/'.$MedID.'/PackagesTH_Encrypted/'.$row['RawImage'];
				$selecURLAMP =$domain.'/temp/'.$MedID.'/Packages_Encrypted/'.$row['RawImage'];
			} else {
				decrypt_files($row['RawImage'],$MedID,$pass);
				$subtipo = substr($row['RawImage'], 3 , 2);
				if ($subtipo=='XX')  {decrypt_files($row['RawImage'],$MedID,$pass); $selecURL =$domain.'/temp/'.$MedID.'/Packages_Encrypted/'.$row['RawImage']; }
				else { //$selecURL =$domain.'/eMapLife/PinImageSetTH/'.$row['RawImage']; 
					decrypt_files($row['RawImage'],$MedID,$pass);
				$selecURL =$domain.'/temp/'.$MedID.'/PackagesTH_Encrypted/'.$row['RawImage'];
				$selecURLAMP =$domain.'/temp/'.$MedID.'/Packages_Encrypted/'.$row['RawImage'];
				
				}
				// COMPROBACIÓN DE EXISTENCIA DEL ARCHIVO (PARA LOS CASOS DE EMAPLIFE iOS o ANDROID QUE TODAVIA NO GENERAN THUMBNAILS Y NO REFERENCIAN AL DIRECTORIO -TH
				$file = $selecURL;
				$file_headers = @get_headers($file);
				if($file_headers[0] == 'HTTP/1.1 404 Not Found') {
			  	  	$exists = false;
			  	  	$selecURL =$domain.'/eMapLife/PinImageSet/'.$row['RawImage'];
			  	  }
			  	  else {
				  	  $exists = true;
				  	  }
				}
			}
		}else{
			$selecURL =$domain.'/images/deletedfile.png';
		    $selecURLAMP =$domain.'/images/deletedfile.png';
		}
	}else{
				 $selecURL =$domain.'/images/lockedfile.png';
				 $selecURLAMP =$domain.'/images/lockedfile.png';
		  }

if (!$row['Tipo']){$indi = 999;}else{$indi = $row['Tipo'];};
//echo $Tipo[$indi];
//echo $Tipo[$indi];

//if (!$row['EvRuPunt']){$indi2 = 999;}else{$indi2 = $row['EvRuPunt'];}; 

     $Evento = $row['Evento'];
     $sqlE=$con->prepare("SELECT * FROM usueventos where IdUsu =? and IdEvento = ? ");
	 $sqlE->bindValue(1, $queUsu, PDO::PARAM_INT);
	 $sqlE->bindValue(2, $Evento, PDO::PARAM_INT);
	 
	 
     $qE = $sqlE->execute();
     $rowE=$sqlE->fetch(PDO::FETCH_ASSOC);
     $EventoALFA = $rowE['Nombre'];
     
     if (!$row['EvRuPunt']){
    	 $indi2 = 999; 
    	 $salida=$EventoALFA; 
     }else{
     	$indi2 = $row['EvRuPunt']; 
     	$salida=$Clase[$indi2]; 
     }; 

if ($n>0) $cadena=$cadena.',';
$n++;



//$FechaFor =  date('j/n/y H:i:s',strtotime($row['Fecha']));
$FechaFor =  date('n/j/Y H:i:s',strtotime($row['Fecha']));

$cadena = $cadena.'
            {
                "startDate":"'.$FechaFor.'",
                "endDate":"'.$FechaFor.'",
                "headline":"'.$Tipo[$indi].'",
                "text":"<p>'.$salida.'</p>",
                "tag":"'.$salida.'",
                "asset": {
                    "media":"'.$selecURL.'",
                    "thumbnail":"'.$selecURL.'",
                    "credit":"(r) Author: '.$email.' ('.$Name.' '.$Surname.')",
                    "caption":""
                    }
            }
';


}

$cadena = $cadena.'
       ],
        "era": [
            {
                "startDate":"2013,12,10",
                "endDate":"2013,12,11",
                "headline":"Inmers Clinical Timeline",
                "text":"<p>Powered by eMapLife</p>"
            }

        ]
    }
}';

$jsondata = json_encode($cadena);

//echo "***********************************************************************************";
//echo $cadena;
//echo "***********************************************************************************";

/*
                "startDate":"'.$row['Fecha'].'",
                "endDate":"'.$row['Fecha'].'",
                "headline":"'.$Tipo[$indi].'",
                "text":"<p>'.$Clase[$indi2].'</p>",
                "tag":"'.$Clase[$indi2].'",
                "asset": {
                    "media":"'.$selecURL.'",
                    "thumbnail":"'.$selecURL.'",
                    "credit":"Credit Name Goes Here",
                    "caption":"Caption text goes here"
                    }

*/

//$cadena = str_replace('\n','',$cadena);
//$cadena = str_replace('\r','',$cadena);
//$cadena = str_replace(' ','',$cadena);

$countfile="jsondata2.txt";
$fp = fopen($countfile, 'w');
fwrite($fp, $cadena);
fclose($fp);
//sleep(5);
}

function blindReports($doctorid,$patientid){
	
	require("environment_detail.php");
	 $dbhost = $env_var_db['dbhost'];
	 $dbname = $env_var_db['dbname'];
	 $dbuser = $env_var_db['dbuser'];
	 $dbpass = $env_var_db['dbpass'];
	 
	 // Connect to server and select databse.
$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }		
	 
	 $IdMed=$doctorid;
	 $IdUsu=$patientid;
	$sql1=$con->prepare("SELECT Idpin FROM lifepin where IdUsu =? and IdMed IS NOT NULL and (IdMed NOT IN ((select distinct(idDoctor) from doctorsgroups where idGroup IN (select idGroup from doctorsgroups where idDoctor= ?))) and IdMed NOT IN (Select idmed from doctorslinkdoctors where idmed2=? and IdPac=?))");
	$sql1->bindValue(1, $IdUsu, PDO::PARAM_INT);
	$sql1->bindValue(2, $IdMed, PDO::PARAM_INT);
	$sql1->bindValue(3, $IdMed, PDO::PARAM_INT);
	$sql1->bindValue(4, $IdUsu, PDO::PARAM_INT);
	
	
	$q1=$sql1->execute();

	$size=0;
	$blindRepId=array();
	while($row1=$sql1->fetch(PDO::FETCH_ASSOC)){
		
		$ReportId=$row1['Idpin'];
		$query=$con->prepare("SELECT estado FROM doctorslinkusers where IdMed=? and IdUs=? and Idpin=? ");
		$query->bindValue(1, $IdMed, PDO::PARAM_INT);
		$query->bindValue(2, $IdUsu, PDO::PARAM_INT);
		$query->bindValue(3, $ReportId, PDO::PARAM_INT);
		
		$q11=$query->execute();
		if($rowes=$query->fetch(PDO::FETCH_ASSOC)){
			$estad=$rowes['estado'];
			if($estad==1){
				$blindRepId[$size]=$ReportId;
				$size++;
			}
		}else{
			$blindRepId[$size]=$ReportId;
			$size++;
		}
		
	}
	
	
	mysql_close($con);
	return $blindRepId;
	
}


function decrypt_files($rawimage,$queMed,$pass )
{
	$ImageRaiz = substr($rawimage,0,strlen($rawimage)-4);
	$extensionR = substr($rawimage,strlen($rawimage)-3,3);
	
	/*$filename = 'temp/'.$queMed.'/Packages_Encrypted/'.$rawimage;
	if (file_exists($filename)) 
	{
		//do nothing
		//echo "The file $filename exists";
	}
	else 
	{
		shell_exec('Decrypt.bat Packages_Encrypted '.$rawimage.' '.$queMed .' 2>&1');
		//echo "PDF Generated";	
	}*/
	
	if($extensionR=='jpg')
	{
		//die("Found JPG Extension");
		$extension='jpg';
		//return;
	}
	else
	{
		$extension='png';
	}
	$filename = 'temp/'.$queMed.'/PackagesTH_Encrypted/'.$ImageRaiz.'.'.$extension;	
	//echo $filename;
	if (file_exists($filename)) 
	{
		//do nothing
		//echo "The file $filename exists";	
	}
	else 
	{
		shell_exec("echo '".$pass."' | openssl aes-256-cbc -pass stdin -d -in PackagesTH_Encrypted/".$ImageRaiz.".".$extension." -out temp/".$queMed."/PackagesTH_Encrypted/".$ImageRaiz.".".$extension);
		//echo "Thumbnail Generated";
	}


}

?>
