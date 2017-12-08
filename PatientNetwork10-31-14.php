<?php
session_start();
 require("environment_detail.php");
 require_once("displayExitClass.php");
 ini_set("display_errors", 0);
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

if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 1800)) {
    // last request was more than 30 minutes ago
    session_unset();     // unset $_SESSION variable for the run-time 
    session_destroy();   // destroy session data in storage
    echo "<META http-equiv='refresh' content='0;URL=index.html'>";
}
$_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp

if ($Acceso != '23432')
{
$exit_display = new displayExitClass();

$exit_display->displayFunction(1);
die;
}

if(!isset($_SESSION['MEDID']) && !empty($_SESSION['MEDID']))
{
    echo "<META http-equiv='refresh' content='0;URL=index.html'>";
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
			$current_encoding = mb_detect_encoding($row['Name'], 'auto');
			$show_text = iconv($current_encoding, 'ISO-8859-1', $row['Name']);

			$current_encoding = mb_detect_encoding($row['Surname'], 'auto');
			$show_text2 = iconv($current_encoding, 'ISO-8859-1', $row['Surname']); 
			
	$success ='SI';
	$MedID = $row['id'];
	$MedUserEmail= (htmlspecialchars($row['IdMEDEmail']));
	$MedUserName = (htmlspecialchars($show_text));
	$MedUserSurname = (htmlspecialchars($show_text2));
	$MedUserLogo = $row['ImageLogo'];

	$MedUserRole = $row['Role'];
	if ($MedUserRole=='1') $MedUserTitle ='Dr. '; else $MedUserTitle =' '; 
	
}
else
{
$exit_display = new displayExitClass();

$exit_display->displayFunction(2);
die;
}

$resultG = $con->prepare("SELECT IdGroup FROM doctorsgroups WHERE IdDoctor = ?");
$resultG->bindValue(1, $MedID, PDO::PARAM_INT);
$resultG->execute();

$rowG = $resultG->fetch(PDO::FETCH_ASSOC);
$MyGroup = $rowG['IdGroup'];
//echo 'MY GROUP = '.$MyGroup.' **********  ';


//BLOCKSLIFEPIN $result = mysql_query("SELECT * FROM blocks");
//$result = mysql_query("SELECT * FROM lifepin");

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
    <link href="css/bootstrap-dropdowns.css" rel="stylesheet">
	
	<link rel="stylesheet" href="css/jquery.timepicker.css" >
    
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
    
  	<link rel="stylesheet" href="font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/bootstrap-responsive.css">
	<link rel="stylesheet" href="css/toggle-switch.css">
	<link rel="stylesheet" href="build/css/intlTelInput.css">
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
    <link rel="shortcut icon" href="images/icons/favicon.ico">
	
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
}, 5000);
}

if(langType == 'en'){
setTimeout(function(){
window.lang.change('en');
lang.change('en');
//alert('th');
}, 5000);
}
	
</script>
    
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
  <body style="background: #F9F9F9;" >
<div class="loader_spinner"></div>
        <input type="hidden" id ="quePorcentaje" value="<?php if(isset($porcentajeCreados)) echo ($porcentajeCreados) ?>" /> 

     	<input type="hidden" id="MEDID" Value="<?php echo $MedID; ?>">	
    	<input type="hidden" id="IdMEDEmail" Value="<?php echo $MedUserEmail; ?>">	
    	<input type="hidden" id="IdMEDName" Value="<?php echo $MedUserName; ?>">	
    	<input type="hidden" id="IdMEDSurname" Value="<?php echo $MedUserSurname; ?>">	
    	<input type="hidden" id="IdMEDLogo" Value="<?php echo $MedUserLogo; ?>">	
     	<input type="hidden" id="USERDID" Value="<?php if(isset($USERID)) echo $USERID; ?>">	
		<input type="hidden" id="ProbeIDHidden"> 	

	<!--Header Start-->
	<div class="header" >
    	
           <a href="index.html" class="logo"><h1>health2.me</h1></a>
           
		   
        <!-- Below language feature commented by Pallab -->
            <!--<div style="float:left;">
		    <a href="#en" onclick="setCookie('lang', 'en', 30); return false;"><img src="images/icons/english.png"></a>
		    </br>
			<a href="#sp" onclick="setCookie('lang', 'th', 30); return false;"><img src="images/icons/spain.png"></a>
			</div> -->
        
           <!-- Start of new code by Pallab -->
               
               <div style="margin-top:9px;float:left;" class="btn-group">
                      <button id="lang1" type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                        Language <span class="caret"></span>
                      </button>
                      <ul class="dropdown-menu" role="menu">
                        <li><a href="#en" onclick="setCookie('lang', 'en', 30); return false;">English</a></li>
                        <li><a href="#sp" onclick="setCookie('lang', 'th', 30); return false;">Espa&ntilde;ol</a></li>
                      </ul>
                </div>
               
             <script>
               var langType = $.cookie('lang');

                if(langType == 'th')
                {
                var language = 'th';
                $("#lang1").html("Espa&ntilde;ol <span class=\"caret\"></span>");
                }
                else{
                var language = 'en';
                $("#lang1").html("English <span class=\"caret\"></span>");
                }
                </script>
              <!-- End of new code by Pallab-->
		   
           <div class="pull-right">
           
            
           <!--Button User Start-->
		   <div class="btn-group pull-right" >
           
            <a class="btn btn-profile dropdown-toggle" id="button-profile" data-toggle="dropdown" href="#">
              <span class="name-user"><strong lang="en">Welcome</strong> Dr, <?php echo $MedUserName.' '.$MedUserSurname; ?></span> 
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
              <li><a href="MainDashboard.php" lang="en"><i class="icon-globe"></i> Home</a></li>
              
              <li><a href="medicalConfiguration.php" lang="en"><i class="icon-cog"></i> Settings</a></li>
              <li><a href="logout.php" lang="en"><i class="icon-off"></i> Sign Out</a></li>
            </ul>
            </div>
          </div>
          <!--Button User END-->  
          
          </div>
    </div>
    <!--Header END-->
	
	<!--Pop up to create Probe-->
	<button id="probeRequest" data-target="#header-modal333" data-toggle="modal" class="btn btn-warning" style="display: none;" lang="en">Modal with Header</button> 
				<div id="header-modal333" class="modal fade hide" style="display: none;overflow:visible;" aria-hidden="true">
				<div class="modal-header">
					<button class="close" type="button" data-dismiss="modal">×</button>
						<div id="InfB" >
	                 	<h4 lang="en">Create Probe Request</h4>
						 </div>
				
						
				</div>
				<div class="modal-body" style="overflow-y:visible;">
					<div id="InfoIDPacienteB" style="float:left;  margin-left:50%;">
					</div>
					<br><br><br>
					<center><!--
					<div style="margin-top:-15px;">	Timezone &nbsp : &nbsp <select id="Timezone" style="width:15em;margin-top:10px"> </select></div>
					<div >	Time &nbsp : &nbsp&nbsp&nbsp&nbsp <input id="Time" style="width:16em;margin-top:10px"> </input></div>
					<div>	Interval &nbsp : &nbsp <select id="Interval" style="width:15em;margin-top:10px"> </select></div>
					<div>	<input style="display:block" type="radio" name="feedbackValue" value="Email"/> Email</div>-->
					<table style="background-color:white" id = "ProbeTab">
						<tr>
							<td lang="en"> Timezone :</td>
							<td><select id="Timezone" style="width:15em;margin-top:10px"> </select></td>
						</tr>
						<tr>
							<td lang="en"> Time :</td>
							<td><input id="Time" style="width:16em;margin-top:10px"> </input></td>
						</tr>
						<tr>
							<td lang="en"> Interval :</td>
							<td><select id="Interval" style="width:15em;margin-top:10px"> </select></td>
						</tr>
												
						<tr>
							<td lang="en"> Probe Method :</td>
							<td><select id="Method" style="width:15em;margin-top:10px" > 
									<option value="Email" lang="en">Email</option>
									<option value="Phone" lang="en">Phone</option>
									<option value="Message" lang="en">Text Message</option>
								</select>
							</td>
						</tr>
						
						<tr id="EmailRow">
							<td lang="en"> Email ID : </td>
							<td> <input id="Email" style="width:16em;margin-top:10px" readonly> </td>
						</tr>
						<tr id="PhoneRow" style="display:none;">
							<td lang="en"> Phone Number : </td>
							<td><input id="Phone" type="tel" placeholder="e.g. +1 702 123 4567" style="width:15em;margin-top:10px;height:30px" readonly></td>
							
						</tr>
						<tr id="MessageRow" style="display:none;">
							<td lang="en"> Phone Number : </td>
							<td> <input id="Message" type="tel" placeholder="e.g. +1 702 123 4567" style="width:15em;margin-top:10px;height:30px" readonly> </td>
						</tr>
						
						
					
					</table>
					<input type="hidden" id="patientID" style="display:none">
					
					</center>
					
				</div>
					
				<div class="modal-footer">
					<a href="#" class="btn btn-success" data-dismiss="modal" id="createProbe" lang="en">Create</a>
					<a href="#" class="btn btn-primary" data-dismiss="modal" id="CloseModallink" lang="en">Close</a>
				</div>
				</div>  
	<!--End Pop up for creating probe-->
	
	<!--Pop up to show Probe History-->
	<button id="probeHistory" data-target="#header-modal5656" data-toggle="modal" class="btn btn-warning" style="display: none;" lang="en">Modal with Header</button> 
				<div id="header-modal5656" class="modal fade hide" style="display: none;overflow:visible;width:800px;margin-left:-400px" aria-hidden="true">
					<div class="modal-header">
						<button class="close" type="button" data-dismiss="modal">×</button>
							<div id="InfB" >
							<h4 lang="en">Probe History</h4>
							 </div>
					
							
					</div>
					<div class="modal-body" style="overflow-y:visible;">
						<div id="InfoIDPacienteStats" style="float:left;">
						</div>
						<!--<div id="InfoIDPacienteBB" style="float:left;  margin-left:50%;">-->
						<div id="InfoIDPacienteBB" style="float:right;">
						</div>
						<br><br><br>
						<div class="grid" style="margin-top:0px;overflow:scroll;overflow-x:hidden;height:300px">
							<table class="table table-mod" id="TablaProbeHistory" style="width:100%; table-layout: fixed; ">
							</table> 
						</div>
					</div>
						
					<div class="modal-footer">
						<a href="#" class="btn btn-primary" data-dismiss="modal" id="CloseModallink">Close</a>
					</div>
				</div>  
	<!--End Pop up Probe History-->
	
	
	
	 <!--- VENTANA MODAL  This has been added to show individual message content which user click on the inbox messages ---> 
   	 <button id="message_modal" data-target="#header-message" data-toggle="modal" class="btn btn-warning" style="display: none;" lang="en">Modal with Header</button> 
   	  <div id="header-message" class="modal hide" style="display: none;" aria-hidden="true">
         <div class="modal-header" lang="en">
             <button class="close" type="button" data-dismiss="modal">×</button>
                  Message Details
         </div>
         <div class="modal-body">
         <div class="formRow" style=" margin-top:-10px; margin-bottom:10px;">
             <span id="ToDoctor" style="color:#2c93dd; font-weight:bold;"></span><input type="hidden" id="IdDoctor" value='-'/>
         </div>
         <textarea  id="messagedetails" class="span message-text" style="height:200px;" name="message" rows="1"></textarea>
         
		 <form id="replymessage" class="new-message">
                   <div class="formRow">
                        <label lang="en">Subject: </label>
                        <div class="formRight">
                            <input type="text" id="subjectname_inbox" name="name"  class="span"> 
                        </div>
                   </div>
				   <div class="formRow">
						<label lang="en">Attachments: </label>
						<div id="attachreportdiv" class="formRight">
							<input type="button" class="btn btn-success" value="Attach Reports" id="attachreports">
						</div>
				   </div>
                   <div class="formRow">
                        <label lang="en">Message:</label>
                        <div class="formRight tooltip-top" style="height:120px;">
                            <textarea  id="messagecontent_inbox" class="span message-text" name="message" style="height:90px;" rows="1"></textarea>
                            
                            <div class="clear"></div>
                        </div>
                   </div>
            </form>
			<div id="attachments" style="display:none">
			
			
			
			</div>
		 </div>
         <input type="hidden" id="Idpin">
        <!-- <input type="hidden" id="docId" value="<?php if(isset($IdMed)) echo $IdMed; ?>"/> -->
         <input type="hidden" id="userId" value="<?php if(isset($IdUsu)) echo $IdUsu; ?>" />
         <div class="modal-footer">
		     <input type="button" class="btn btn-info" value="Send message" id="sendmessages_inbox">
             <input type="button" class="btn btn-success" value="Attach" id="Attach">	
	         <input type="button" class="btn btn-success" value="Reply" id="Reply">			 
	         <a href="#" class="btn btn-primary" data-dismiss="modal" id="CloseMessage" lang="en">Close</a>
         </div>
      </div>  
	  <!--- VENTANA MODAL  ---> 	

    <!--Content Start-->
	<div id="content" style="background: #F9F9F9; padding-left:0px;">

	  <!--- VENTANA MODAL  phases wizard---> 
   	  <button id="BotonModal" data-target="#header-modal" data-toggle="modal" class="btn btn-warning" style="display: none;" lang="en">Modal with Header</button>
   	 <!--<div id="header-modal" class="modal hide" style="display: none; width: 80%; /* desired relative width */left: 10%; /* (100%-width)/2 */ /* place center */ margin-left:auto; margin-right:auto; " aria-hidden="true">-->
   	 <div id="header-modal" class="modal hide" style="display: none; width: 800px; left: 20%; margin-left:auto; margin-right:auto;" aria-hidden="true">
         <div class="modal-header" style="height:60px;">
             <button class="close" type="button" data-dismiss="modal">×</button>
             <div style="width:90%; margin-top:12px; float:left;">
                 <div id="selpat" style="width: 100px; margin-left:5%; float: left; color: rgb(61, 147, 224);">
                     <div style="font-size: 20px; font-weight: bold; width: 100%;">Step 1</div>
                     <span style="font-size: 12px; width: 100%;" lang="en">Select Patient</span>
                 </div>
                 <div id="seldr" style="width:100px; margin-left:5%; float:left;">
                     <div style="font-size:20px; font-weight:bold; width:100%; ">Step 2</div>
                     <span style="font-size:12px; width:100%;" lang="en">Link to Patient</span>
                 </div>
             </div>
         </div>
         <div class="modal-body" style="height:300px;">
             <div id="ContentScroller" style="width:100%; height:280px; overflow: hidden; ">
                 <div id="ScrollerContainer" style="width:2000px; height:275px; ">
                        <div id="content_selpat" style="float:left; width:800px; height:270px; ">
                           <p>
                           	<!-- PATIENT SELECTION TABLE --->
							<div class="grid" style="float:left; width:90%; height:265px; margin: 0 auto; margin-left:2%; margin-top:0px; margin-bottom:0px; overflow:scroll;">
                                <div class="grid-title">
									<div class="pull-left" lang="en"><div class="fam-user" style="margin-right:10px;"></div>Select a Patient</div>
									<img src="images/load/8.gif" alt="" style="margin-left:50px; margin-top:10px; display: none;" id="Wait2">
									<div class="pull-right"></div>
									<div class="clear"></div>   
								</div>
								<div class="search-bar">
									<input type="text" class="span" name="" placeholder="Search Patient" style="width:150px;" id="SearchUserT"> 
									<input type="button" class="btn btn-primary" value="Filter" style="margin-left:50px;" id="BotonBusquedaPacNEW">
								</div>
								<div class="grid" style="margin-top:0px;">
									<table class="table table-mod" id="TablaPacModal" style="width:100%; table-layout: fixed; ">
									</table> 
								</div>
						   </div>
							<!-- PATIENT SELECTION TABLE --->
                           </p>
                        </div>    
                        <div id="content_seldr" style="float:left; text-align:center; width:900px; height:370px; ">
                            <div style="text-align:center; width:750px; height:170px;">
                                <div style="margin:0 auto; margin-top: 10px; width:360px; height:160px;">
                                    <div id="DivConnect" style="float:left; display: table-cell; vertical-align: middle; text-align:center; margin:0 auto; width:150px; height:150px; border:1px solid #cacaca; border-radius:5px; background-color:#f5f5f5">
                                        <div style="display: inline-block; width: 150px; height: 100px; margin-top:40px; text-align: center; ">
                                            <div id="IconConnect"><i class="icon-link icon-3x" ></i></div>
                                            <div style="margin-top:10px;"><span id="SpanConnect" lang="en">Connect Connect Connect Connect</span></div>
                                        </div>
                                    </div>
                                     <div id="DivInvite" style="float:left; text-align:center; margin:0 auto; margin-left:50px; width:150px; height:150px; border:1px solid #cacaca; border-radius:5px; background-color:#f5f5f5">
                                        <div style="display: inline-block; width: 150px; height: 100px; margin-top:40px; text-align: center; ">
                                            <div id="IconInvite"><i class="icon-tag icon-3x" ></i></div>
                                            <div style="margin-top:10px;"><span id="SpanInvite" lang="en">Invite Invite Invite Invite</span></div>
                                        </div>
                                     </div>
                                </div>
                                <div style="margin:0 auto; margin-top: 0px; width:400px; height:190px; ">
                                    <input id="IdPatient" type="hidden" value="0">
                                    <input id="UEmail" type="text" value="" placeholder="email" value="" >
                                    <input id="UPhone" type="text" value="" placeholder="phone number" value="" >
                                    <input id="UTempoPass" type="text" value="" placeholder="Temporary Password" value="">
                                    
                                </div>    
                             </div>
                        </div>
                        <div id="content_att" style="float:left; width:370px; height:50px; ">
                        </div>
                        <div id="content_addcom" style="float:left; width:370px; height:150px; padding:30px;">
                        </div>
                  </div>
             </div>
         </div>
         <input type="hidden" id="queId">
         <div class="modal-footer" style="height:120px;">
	         <div style="height:80px; width:100%:">
                    <p id="TextoSend" style="text-align:center; margin-top:0px; ">
                        <span style="color:grey;" lang="en">Send </span>
                        <span style="color:#54bc00; font-size:30px;">      </span>
                        <span style="color:grey;" lang="en"> to </span>
                        <span style="color:#22aeff; font-size:30px;">     </span>
                    </p>
             </div>
	         <div style="height:20px; width:100%:">
                 <input type="button" class="btn btn-success" value="SEND" id="SendButton" style="width:100px; display:none;">
                 <input type="button" class="btn btn-success" value="NEXT" id="Attach" style="visibility: hidden;">
                 <a href="#" class="btn btn-danger" data-dismiss="modal" id="CloseModal"  lang="en">Cancel</a>
                 <input type="button" class="btn btn-info" value="Previous" id="PhasePrev">
                 <input type="button" class="btn btn-success" value="NEXT" id="PhaseNext" style="width:100px;">
                 
             </div> 
         </div>
      </div>  
	  <!--- VENTANA MODAL  ---> 

      <!--- VENTANA MODAL  2 ---> 
   	  <!--<button id="BotonModal" data-target="#header-modal" data-toggle="modal" class="btn btn-warning" style="display: none;">Modal with Header</button>-->
   	  <div id="header-modal2" class="modal hide" style="display: none; height:320px;" aria-hidden="true">
 <button id="BotonMod2" data-target="#header-modal2" data-toggle="modal" class="btn" style="float:right; margin-right:10px; display:none;" lang="en"><i class="icon-indent-left"></i>New</button>

         <div class="modal-header">
             <button class="close" type="button" data-dismiss="modal">×</button>
                 <h4 lang="en">Connection with patient</h3>
                 <input type="hidden" id="IdUserSel"></input>
                 <input type="hidden" id="IdUserSwitch"></input>
         </div>
         
         <div class="modal-body" id="ContenidoModal2" style="height:170px;">
           <p lang="en">
           Reset your password. 
           </p>
           
         </div>
         <input type="hidden" id="queId">
        
         <div class="modal-footer">
	         <!--<input type="button" class="btn btn-success" value="Confirm" id="ConfirmaLink">
             <a href="#" class="btn btn-success" data-dismiss="modal" id="GrabaDatos">Proceed</a>--->
             <a href="#" class="btn btn-primary" data-dismiss="modal" id="CloseModal2" lang="en">OK</a>
         </div>
      </div>  
	  <!--- VENTANA MODAL 2 ---> 

	 <!--SpeedBar Start--->
     <div class="speedbar">
     <div class="speedbar-content">
     <ul class="menu-speedbar">
		
    	 <li><a href="MainDashboard.php" lang="en">Home</a></li>
         <?php require("checkPageAccessControl.php");
                
            
                                    
                $arr=checkAccessPage("dashboard.php");
                $arr_d=json_decode($arr, true);

                if((count($arr_d['items'])>0)&&($arr_d['items'][0]['accessid']==1)){ 
                
                    echo '<li><a lang="en" href="dashboard.php"  lang="en">Dashboard</a></li>';
                }
         ?>
		 
    	 <li><a href="patients.php"  lang="en">Members</a></li>
		 <?php if ($privilege==1)
		 {
				 echo '<li><a href="medicalConnections.php"  lang="en">Doctor Connections</a></li>';
				 echo '<li><a href="PatientNetwork.php" class="act_link" lang="en">Member Network</a></li>';
		 }
		 ?>
         <li><a href="medicalConfiguration.php" lang="en">Configuration</a></li>
         <li><a href="logout.php" style="color:yellow;" lang="en">Sign Out</a></li>
     </ul>

     
     </div>
     </div>
     <!--SpeedBar END-->
     
     
     <!--CONTENT MAIN START-->
	 <div class="content" style="" onmouseover="setGrabQuery2()">
         
            <div class="grid" class="grid span4" style="width:1000px; min-height:1300px; margin: 0 auto; margin-top:30px; padding-top:30px;">

                <span class="label label-info" style="left:0px; margin-left:30px; font-size:30px;" lang="en">Member Network</span>
                <!-- UPPER INFORMATION AREA --->     
                <div class="row-fluid"  style="width:940px; margin-left:30px; margin-right:0px; margin-top:0px;">	            
                            <input type="hidden" id ="quePorcentaje" value="<?php if(isset($porcentajeCreados)) echo ($porcentajeCreados) ?>" /> 
                            <div class="grid" style="padding:10px; height:110px;">
                            
        <?php
              // Sección para construir la información estadística del Médico (Dashboard: relativo a "packets")  
              //$resultDOC = mysql_query("SELECT * FROM lifepin");
              $resultDOC = $con->prepare("SELECT * FROM lifepin WHERE IdMed=?");
			  $resultDOC->bindValue(1, $MedID, PDO::PARAM_INT);
			  $resultDOC->execute();

              $countDOC = $resultDOC->rowCount();
              $r=0;
              $EstadCanal = array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);
              $EstadCanalValid = array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);
              $EstadCanalNOValid = array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);
              $ValidationStatus = array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);
              while ($rowDOC = $resultDOC->fetch(PDO::FETCH_ASSOC))
              {
                $Valid = $rowDOC['ValidationStatus'];
                $esvalido=0;
                if (is_numeric($Valid)) {$ValidationStatus[$Valid] ++; $esvalido=1;}
    
                $Canal = $rowDOC['CANAL'];
                if (is_numeric($Canal)){
                    $EstadCanal[$Canal] ++;
                    if ($Valid==0 && $esvalido==1) {$EstadCanalValid[$Canal] ++;} else {$EstadCanalNOValid[$Canal] ++;}
                    }
    
                $r++;  
                
              }	
              $ArrayPacientes = array();
              $numeral=0;
              $numeralF=0;
              $antiguo = 30;
              $NPackets = 0;
              
              $resultPAC = $con->prepare("SELECT * FROM doctorslinkusers WHERE IdMED=? ");
			  $resultPAC->bindValue(1, $MedID, PDO::PARAM_INT);
			  $resultPAC->execute();
			  
              //$countPAC=mysql_num_rows($resultPAC);
              while ($rowP1 = $resultPAC->fetch(PDO::FETCH_ASSOC))
              {
                    $ArrayPacientes[$numeral]=$rowP1['IdUs'];
                    $numeral++;
                    $antig = time()-strtotime($rowP1['Fecha']);
                    $days = floor($antig / (60*60*24));
                    if ($days<$antiguo) $numeralF++;
                    
                    $idEncontrado = $rowP1['IdUs'];
                    $resultPIN = $con->prepare("SELECT * FROM lifepin WHERE IdUsu = ? ");
					$resultPIN->bindValue(1, $idEncontrado, PDO::PARAM_INT);
					$resultPIN->execute();
					
                    $countPIN = $resultPIN->rowCount();
                    $NPackets=$NPackets+$countPIN;
    
              }
              
              $NPacketsMIOS = $NPackets;
              $MIOS = $numeral;
              $MIOSF = $numeralF;
              
              //$sumatotalPAC = 0;
              $resultCRU = $con->prepare("SELECT * FROM doctorslinkdoctors WHERE IdMED=? ");
			  $resultCRU->bindValue(1, $MedID, PDO::PARAM_INT);
			  $resultCRU->execute();
			  
              //$countCRU=mysql_num_rows($resultCRU);
              while ($rowCRU = $resultCRU->fetch(PDO::FETCH_ASSOC))
              {
                  $Autorizado = $rowCRU['IdMED2'];
                  $resultPAC2 = $con->prepare("SELECT * FROM doctorslinkusers WHERE IdMED=? ");
				  $resultPAC2->bindValue(1, $Autorizado, PDO::PARAM_INT);
				  $resultPAC2->execute();
				  
                  //$countPAC2=mysql_num_rows($resultPAC2);
                  while ($rowC2 = $resultPAC2->fetch(PDO::FETCH_ASSOC))
                  {
                    $idEncontrado = $rowC2['IdUs'];
                    if (!in_array($idEncontrado, $ArrayPacientes)){
                         $ArrayPacientes[$numeral]=$idEncontrado;
                         $numeral++;
                         $antig = time()-strtotime($rowC2['Fecha']);
                         $days = floor($antig / (60*60*24));
                         if ($days<$antiguo) $numeralF++;
    
                         $resultPIN = $con->prepare("SELECT * FROM lifepin WHERE IdUsu = ? ");
						 $resultPIN->bindValue(1, $idEncontrado, PDO::PARAM_INT);
						 $resultPIN->execute();
						 
                         $countPIN = $resultPIN->rowCount();
                         $NPackets = $NPackets+$countPIN;
    
                         }
                  }		 
                  //$sumatotalPAC = $sumatotalPAC + $countPAC2;
              }
              
              $CONACCESO = $numeral;
              $CONACCESOF = $numeralF;
              if ($NPackets!=0) //$porcentajeCreados = number_format((100*$NPacketsMIOS/$NPackets), 0, ',', ' '); else $porcentajeCreados=0;
              
              // Variante para calcular de forma más ajustada el porcentaje de packetes sobre el total de los paquetes de los pacientes que están vinculados a mi. (FORMA LARGA)
     
              // Sección para construir la información estadística del Médico (Dashboard: relativo a "packets")  
              $hash = md5( strtolower( trim( $MedUserEmail ) ) );
              $avat = 'identicon.php?size=75&hash='.$hash;
                            
                            $maximo = max($EstadCanal);
                            if ($maximo == 0) $maximo=0.0001;
                            $maximoR = 100;
                
                            $G0=($EstadCanal[0] * $maximoR) / $maximo;
                            $P0=($EstadCanalValid[0] * $maximoR) / $maximo;;
                            $C0='rgba(255,200,49,';
                            $V0=$EstadCanal[0];
                            $VV0=$EstadCanalValid[0];
                            
                            $G1=($EstadCanal[6] * $maximoR) / $maximo;
                            $P1=($EstadCanalValid[6] * $maximoR) / $maximo;;
                            $C1='rgba(115,187,59,';
                            $V1=$EstadCanal[6];
                            $VV1=$EstadCanalValid[6];
    
                            $G2=($EstadCanal[1] * $maximoR) / $maximo;
                            $P2=($EstadCanalValid[1] * $maximoR) / $maximo;;
                            $C2='rgba(215,240,100,';
                            $V2=$EstadCanal[1];
                            $VV2=$EstadCanalValid[1];
    
                            $G3=($EstadCanal[2] * $maximoR) / $maximo;
                            $P3=($EstadCanalValid[2] * $maximoR) / $maximo;;
                            $C3='rgba(185,200,150,';
                            $V3=$EstadCanal[2];
                            $VV3=$EstadCanalValid[2];
    
                            $G4=($EstadCanal[4] * $maximoR) / $maximo;
                            $P4=($EstadCanalValid[4] * $maximoR) / $maximo;;
                            $C4='rgba(145,100,200,';
                            $V4=$EstadCanal[4];
                            $VV4=$EstadCanalValid[4];
    
                            $G5=($EstadCanal[5] * $maximoR) / $maximo;
                            $P5=($EstadCanalValid[5] * $maximoR) / $maximo;;
                            $C5='rgba(105,120,250,';
                            $V5=$EstadCanal[5];
                            $VV5=$EstadCanalValid[5];
                            ?>
                            
                            <!--Pie Chart-->
                             
                            <div id="gaugetitulo" style="width:200px; height:160px; float:left; margin-top:-35px; " title="">
                            
                                <div class = "gauge_spinner" style = "padding-top: 50px;">
                                    <center>
                                        <span style="font-size:60px;" id="H2M_Spin_Stream">
                                            <i class="icon-spinner icon-1x icon-spin" ></i>
                                        </span>
                                    </center>
                                </div>
                                <div id="gauge" class="200x160px" style="padding:0px;">
                                 
                            </div>
                            </div>
                            <!--Pie END-->
                            <div id="MidIcons" style="margin-top:30px; margin-bottom:30px; text-align:center; float:left; width:270px; font-size:14px; color:#22aeff;">
                                <span style="color:#22aeff;" title="New messages present"><a class="neutral" style="color:#22aeff;"><i class="icon-envelope-alt icon-3x"></i></a></span>
                                <span id="TotMsgV" style="visibility:hidden;" class="H2MBaloon" ></span>

                                <span style="color:#54bc00; margin-left:5px;" title="Members uploaded information in the past 30 days"><i class="icon-circle-arrow-up icon-3x "></i></span>
                                <span id="TotUpUsV" style="visibility:hidden; background-color:grey;" class="H2MBaloon" ></span>

                                <span style="color:#22aeff; margin-left:10px;" title="Doctors uploaded information in the past 30 days"><i class="icon-arrow-up icon-3x "></i></span>
                                <span id="TotUpDrV" style="visibility:hidden; background-color:black;" class="H2MBaloon" ></span>
            
                            </div>    
                                
                            <?php
                                $MIOS = 14;
                                $CONACCESO = 35;
                            ?>
                            <div style="width:440px; float:right; margin:0px; padding:0px;"><!-- WRAPPER DE ESTA AREA --->
                            <!---- CAJA DE PRESENTACIÓN DE NÚMEROS ---->
                            <!--Probes-->
							<div style="width:100px; border:1px solid rgba(194,194,194,1); float:right;  -webkit-border-radius: 5px; -moz-border-radius: 5px; border-radius: 5px; margin-left:10px;">
                            
								<div style="height:80px; width:100px;  text-align:center; margin:0px;">  <!-- Font-size: 70 para 2 digitos, 50 para 3 digitos, 30 para 4 digitos -->
									<p id="TotProbe" style=" font-size:px; font-weight:bold; color: ; padding-top:27px;">
										<span style="font-size:40px;" id="H2M_Spin_Stream">
											<i class="icon-spinner icon-1x icon-spin" ></i>
										</span>
									</p>
								</div>
                            
								<div id="TotProbeD" style="width:100px;  text-align:center; margin:0px; background-color: ; border:1px solid ; margin-left:-1px;  -webkit-border-bottom-right-radius: 5px; -moz-border-bottom-right-radius: 5px; border-bottom-right-radius: 5px; -webkit-border-bottom-left-radius: 5px; -moz-border-bottom-left-radius: 5px; border-bottom-left-radius: 5px;">
								<p style="font-family: arial; font-size:18px; color:white; padding:5px; margin:0px; " lang="en">Probes</p>
								</div>	
                            
                            </div>
							
							
							<!--Connected-->
							<div style="width:100px; border:1px solid rgba(194,194,194,1); float:right;  -webkit-border-radius: 5px; -moz-border-radius: 5px; border-radius: 5px; margin-left:10px;">
                            
								<div style="height:80px; width:100px;  text-align:center; margin:0px;">  <!-- Font-size: 70 para 2 digitos, 50 para 3 digitos, 30 para 4 digitos -->
									<p id="TotConn" style=" font-size:px; font-weight:bold; color: ; padding-top:27px;">
										<span style="font-size:40px;" id="H2M_Spin_Stream">
											<i class="icon-spinner icon-1x icon-spin" ></i>
										</span>
									</p>
								</div>
                            
								<div id="TotConnD" style="width:100px;  text-align:center; margin:0px; background-color: ; border:1px solid ; margin-left:-1px;  -webkit-border-bottom-right-radius: 5px; -moz-border-bottom-right-radius: 5px; border-bottom-right-radius: 5px; -webkit-border-bottom-left-radius: 5px; -moz-border-bottom-left-radius: 5px; border-bottom-left-radius: 5px;">
								<p style="font-family: arial; font-size:18px; color:white; padding:5px; margin:0px; " lang="en">Connected</p>
								</div>	
                            
                            </div>
							
							<!--Patients-->
							<div style="width:100px; border:1px solid rgba(194,194,194,1); float:right;  -webkit-border-radius: 5px; -moz-border-radius: 5px; border-radius: 5px; margin-left:10px;">
                            
								<div style="height:80px; width:100px;  text-align:center; margin:0px;">  <!-- Font-size: 70 para 2 digitos, 50 para 3 digitos, 30 para 4 digitos -->
									<p id="TotPats" style=" font-size:px; font-weight:bold; color: ; padding-top:27px;">
										<span style="font-size:40px;" id="H2M_Spin_Stream">
											<i class="icon-spinner icon-1x icon-spin" ></i>
										</span>
									</p>
								</div>
                            
								<div id="TotPatsD" style="width:100px;  text-align:center; margin:0px; background-color: ; border:1px solid ; margin-left:-1px;  -webkit-border-bottom-right-radius: 5px; -moz-border-bottom-right-radius: 5px; border-bottom-right-radius: 5px; -webkit-border-bottom-left-radius: 5px; -moz-border-bottom-left-radius: 5px; border-bottom-left-radius: 5px;">
								<p style="font-family: arial; font-size:18px; color:white; padding:5px; margin:0px; " lang="en">Members</p>
								</div>	
                            
                            </div>
                            <!---- CAJA DE PRESENTACIÓN DE NÚMEROS ---->
                            <!---- CAJA DE PRESENTACIÓN DE NÚMEROS ---->
                            
                            <!---- CAJA DE PRESENTACIÓN DE NÚMEROS ---->
    
                           



						   <!---- CAJA DE PRESENTACIÓN DE NÚMEROS (TIPO ANCHO PARA 2 VALORES) ---->
                           <!--
						   <div style="width:200px; border:1px solid rgba(194,194,194,1); float:left;  -webkit-border-radius: 5px; -moz-border-radius: 5px; border-radius: 5px; margin-left:10px;">
                            
                            <div style="height:80px; width:200px;  text-align:center; margin:0px;">  
                                <p id="StatsPat" style=" font-size:<?php $accesibles=$MIOSF; $accesibles2=$MIOS+.000001; echo 50/*echo queFuente2($accesibles,$accesibles2);*/?>px; font-weight:bold; color: <?php echo $C1.'0.99)' ?>; padding-top:20px;"><?php echo number_format(($accesibles*100/$accesibles2), 0, ',', ' ').' % ' ?></p>
                                <p id="StatsPatN" style="margin-top:8px;  padding-top:0px; font-size:<?php $accesibles=$CONACCESOF; $accesibles2=$CONACCESO+.000001; echo 16/*echo queFuente2($accesibles,$accesibles2);*/?>px; font-weight:bold; color: <?php echo $C1.'0.70)' ?>; "><?php echo '( '.number_format(($accesibles*100/$accesibles2), 0, ',', ' ').' % from reach)' ?></p>
                            </div>
                            
                            <div id="StatsPatD" style="width:200px;  text-align:center; margin:0px; background-color: <?php echo $C1.'0.99)' ?>; border:1px solid <?php echo $C1.'0.99)' ?>; margin-left:-1px;  -webkit-border-bottom-right-radius: 5px; -moz-border-bottom-right-radius: 5px; border-bottom-right-radius: 5px; -webkit-border-bottom-left-radius: 5px; -moz-border-bottom-left-radius: 5px; border-bottom-left-radius: 5px;">
                            <p style="font-family: arial; font-size:18px; color:white; padding:5px; margin:0px; ">New Information</p>
                            </div>	
                            
                            </div>
                            -->
							</div>
                            <div class="clear"></div>   
                                                    
                            </div>
                        </div>	
                <!-- UPPER INFORMATION AREA --->     

                
                <!-- PENDING TASKS TABLE --->     
                <input type="hidden" class="btn btn-primary" value="Filter" style="margin-left:50px;" id="BotonBusquedaPending">
                <div id="PendingTasks" style="width:940px; margin-left:30px; margin-right:0px; margin-top:30px;" >
                        <div class="grid" style="width:100%; min-height:100px; max-height:300px;margin: 0 auto; overflow-y:scroll;">
                                    <div class="grid-title">
                                        <div class="pull-left" lang="en"><div class="fam-user" style="margin-right:10px;"></div>Pending Confirmations and Invitations</div>
               
                                            <img src="images/load/8.gif" alt="" style="margin-left:50px; margin-top:10px; display: none;" id="Wait1">
    
                                        <div class="pull-right"></div>
                                        <div class="clear"></div>   
                                    </div>
                                    <div class="grid" style="margin-top:0px; ">
                                       <div style="min-height:56px; ">
                                            <table class="table table-mod" id="TablaPacPending" style="width:100%; table-layout: fixed; ">
                                            </table> 
                                        </div>
                                    </div>
                        </div>
               </div>  
                <!-- PENDING TASKS TABLE --->     
                
		<div style="margin:30px; " >
        <!--TAB Start-->
          <ul id="myTab" class="nav nav-tabs tabs-main">
            <li class="active" style="width:50%; " id="probeTab"><a href="#probe"  data-toggle="tab" lang="en">Probes</a></li>
			<li style="width:50%; " id="connectedpatients"><a href="#cpatients" data-toggle="tab" lang="en">Connected Members</a></li>
            
          </ul> 
          <div id="myTabContent" class="tab-content tabs-main-content padding-null" style="overflow-x:hidden;width:100%;">
					<div class="tab-pane tab-overflow-main fade in active" id="probe" style="width:940px; margin-left:30px; margin-right:0px; margin-top:30px;">
						<div class="grid" style="width:95%; " id="probeGrid">
                                    <div class="grid-title">
                                        <div class="pull-left" lang="en"><div class="fam-user" style="margin-right:10px;"></div>Members Database</div>
										<img src="images/load/8.gif" alt="" style="margin-left:50px; margin-top:10px; display: none;" id="Wait3">
                                            
                                        <div class="pull-right"></div>
                                        <div class="clear"></div>   
                                    </div>
                                    <div class="search-bar" >
                                        <input type="text" class="span" name="" lang="en" placeholder="Search Member" style="width:150px;" id="searchProbe"> 
                                        <input type="button" class="btn btn-primary" lang="en" value="Filter" style="margin-left:50px;" id="BotonBusquedaProbe">
									
										
									
										<div style="float:right;" class="the-icons">
											<i class="icon-chevron-right" style="padding:10px 10px;float:right;margin-right:0px;" id="next"></i>
											<label style="padding:10px;float:right;margin-right:0px;" id="CurrentPage"></label>
											<i class="icon-chevron-left" style="padding:10px ;float:right;margin-right:0px;" id="prev"></i>
										</div>
										
										<div style="float:right; margin-right:100px;">
											<label class="checkbox toggle candy" onclick="" style="width:100px;">
												<input type="checkbox" id="CToggle" name="CToggle" />
												<p>
													<span lang="en">Probe</span>
													<span lang="en">All</span>
												</p>
												
												<a class="slide-button"></a>
											</label>
										</div>
                                        <!--<input type="button" class="btn btn-success" value="Connect more patients" id="BotonWizard" style="margin-left:250px; margin-top:0px; width:250px;">-->
										<!--<div style="float:right; margin-right:100px;">
											<label class="checkbox toggle candy blue" onclick="" style="width:100px;">
												<input type="checkbox" id="RetrievePatient" name="RetrievePatient" />
												<p>
													<span title="Search in only in group">Group</span>
													<span title="Search all patients">All</span>
												</p>
												
												<a class="slide-button"></a>
											</label>
										</div>-->
                                    </div>
                                    <div class="grid" style="margin-top:0px;">
                                       <div style="max-height:800px; overflow-x:hidden;overflow-y:scroll;width:890px ">
                                            <table class="table table-mod" id="TablaProbe" style="width:100%; table-layout: fixed; ">
                                            </table>
											
											<div style="width:100%; height:2px;"></div>
		
                                        </div>
                                    </div>
									
								</div>
								
  				    </div><span style="font-size:40px;" id="H2M_Spin_Stream">
					<center><i id="connect_spinner" class="icon-spinner icon-2x icon-spin" ></i></center>
					</span>
					
	
					
						<!--<div class="tab-pane " id="cpatients">-->
						<div class="tab-pane" style="width:940px; margin-left:30px; margin-right:0px; margin-top:30px;"  id="cpatients" >
								
								
								
								
								<div class="grid" style="width:95%; margin: -8px;" id="connectedPatientsGrid">
                                    <div class="grid-title">
                                        <div class="pull-left" lang="en"><div class="fam-user" style="margin-right:10px;"></div>Connected Members</div>
										<img src="images/load/8.gif" alt="" style="margin-left:50px; margin-top:10px; display: none;" id="Wait4">
                                            
                                        <div class="pull-right"></div>
                                        <div class="clear"></div>   
                                    </div>
                                    <div class="search-bar" >
                                        <input type="text" class="span" name="" lang="en" placeholder="Search Member" style="width:150px;" id="SearchUserUSERFIXED"> 
                                        <input type="button" class="btn btn-primary" lang="en" value="Filter" style="margin-left:50px;" id="BotonBusquedaSents">
                                        <input type="button" class="btn btn-success" lang="en" value="Connect more members" id="BotonWizard" style="margin-left:250px; margin-top:0px; width:250px;">
                                    </div>
                                    <div class="grid" style="margin-top:0px;">
                                       <div style="max-height:800px; overflow-y:scroll; overflow-x:hidden">
                                            <table class="table table-mod" id="TablaSents" style="width:100%; table-layout: fixed; ">
                                            </table>
											<center>
												<div id = "prevPatients" style = "width: 75px; height: 50px; margin-right: 100px; display: inline-block; cursor: pointer" lang="en">
												   <i class = "icon-chevron-left"></i> Previous
												</div>
												<div id = "pageDisplay" style = "display: inline-block;">
													
												</div>
												<div id = "nextPatients" style = "width: 75px; height: 50px; margin-left: 100px; display: inline-block; cursor: pointer" lang="en">
													Next <i class = "icon-chevron-right"></i> 
												</div>
											</center>
											<div style="width:100%; height:2px;"></div>
		
                                        </div>
                                    </div>
								</div>
						
						
						
						</div>
					


				
		  </div>	 		
        </div>    
                <!-- COMMUNICATIONS & MESSAGING --->     
                <div class="row-fluid"  style="">	            
                 <div class="grid" class="grid span4" style="height:420px; margin: 30px auto; margin-top:30px; margin-left:30px; margin-right:30px; padding:10px; ">
                    <span class="label label-info" id="EtiTML" style="left:0px; margin-left:0px; margin-top:20px; margin-bottom:20px; font-size:16px; text-shadow:none; text-decoration:none;" lang="en">User&Doctor Communications Area</span>
                    <ul id="myTab" class="nav nav-tabs tabs-main">
                    <li class="active"><a href="#inbox" data-toggle="tab" id="newinbox" lang="en">InBox</a></li>
                    <li><a href="#outbox" data-toggle="tab" id="newoutbox" lang="en">OutBox</a></li></ul>
                    <div id="myTabContent" class="tab-content tabs-main-content padding-null" >
                                   
                    <div class="tab-pane tab-overflow-main fade in active" id="inbox" >
                    <div class="message-list" ><div class="clearfix" style="margin-bottom:40px;">
                    <div class="action-message" ><div class="btn-group">
                   
                    <button id="delete_message" class="btn b2"><i class="icon-trash padding-null"></i></button>
                    <!--<input type="button" style="margin-left:10px" class="btn b2" value="Create Message" id="compose_message">-->
                  
                    </div></div>
                    </div>
                        <div style="height:270px; overflow:auto; ">
                            <table class="table table-striped table-mod" id="datatable_3"></table>
                        </div>
                    </div></div>
                    <div class="tab-pane" id="outbox">
                    <div class="message-list"><div class="clearfix" style="margin-bottom:40px;">
                    <div class="action-message"><div class="btn-group">
                    <button id="delete_message_outbox" class="btn b2"><i class="icon-trash padding-null"></i></button>
                    </div></div>
                    </div>
                        <div style="height:270px; overflow:auto; ">
                            <table class="table table-striped table-mod" id="datatable_4"></table> 
                        </div>
                    </div>
                    </div>
                    </div>
            </div>     
            <!-- COMMUNICATIONS & MESSAGING --->     
            <div style="width:100%; height:2px;"></div>     
                
            <!-- PATIENT SELECTION TABLE --->
            
            <div id="OLDpatientstable" class="grid" style="float:left; width:90%; height:300px; margin: 0 auto; margin-left:2%; margin-top:30px; margin-bottom:30px; overflow:scroll;">
                    <div class="grid-title">
                        <div class="pull-left" lang="en"><div class="fam-user" style="margin-right:10px;"></div>Select a Member</div>
                            <img src="images/load/8.gif" alt="" style="margin-left:50px; margin-top:10px; display: none;" id="Wait1">
                        <div class="pull-right"></div>
                        <div class="clear"></div>   
                    </div>
                    <div class="search-bar">
                        <input type="text" class="span" name="" lang="en" placeholder="Member Name" style="width:150px;" id="SearchUserT"> 
                        <input type="button" class="btn btn-primary" value="Filter" style="margin-left:50px;" id="BotonBusquedaPacCOMP">
                    </div>
                    <div class="grid" style="margin-top:0px;">
                        <table class="table table-mod" id="TablaPac" style="width:100%; table-layout: fixed; ">
                        </table> 
        
                    </div>
               </div>
            
            <!-- PATIENT SELECTION TABLE --->

            </div>
    </div>
     <!--Content END-->
	<div id="footer" style="margin:30px;">
		<div id="center_footer">
			<span ><p>©Copyright 2014 Inmers LLC. Health2.Me is a property of Inmers LLC. Patent pending. </p></span>
		</div>
	</div> 
    
    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="js/jquery.min.js"></script>
    <script src="js/jquery-ui.min.js"></script>

	<script src="build/js/intlTelInput.js"></script>
	<script src="js/isValidNumber.js"></script>
	
    <!-- Libraries for notifications -->
    <script src="realtime-notifications/lib/gritter/js/jquery.gritter.min.js"></script>
	<script src="realtime-notifications/pusher.min.js"></script>
	<script src="realtime-notifications/PusherNotifier.js"></script>
	<link href="realtime-notifications/lib/gritter/css/jquery.gritter.css"rel="stylesheet" type="text/css" />
	    <script>
		$(function() {
	    var pusher = new Pusher('d869a07d8f17a76448ed');
	    var channel_name=$('#MEDID').val();
		var channel = pusher.subscribe(channel_name);
		var notifier=new PusherNotifier(channel);
		
	  });
    </script>
	
	<!--<script src="imageLens/jquery.js" type="text/javascript"></script>-->
	<script src="imageLens/jquery.imageLens.js" type="text/javascript"></script>
    <!-- Libraries for notifications -->
                
                    
    <script type="text/javascript" >
	var time_stop = 0;
	function setGrabQuery2()
	{
	if(time_stop == 0){
	time_stop = 1;
			//getPatientList();
			$('#BotonBusquedaProbe').trigger('click');
            $('#BotonBusquedaPending').trigger('click');
            $('#newinbox').trigger('click');
			$('#connect_spinner').css('display', 'none');
			//alert('mouse moved!');
			}
	}
        
	
	var currpage = 1; //for probe paging
    var paciente='';
    var destino='';
    var IdPaciente = -1;
    var GRelType = -1;
    var GKnows = -1;
    var GlobalIdUser = -1;
    var IdDoctor = -1;
    var timeoutTime = 30000000;
	//var timeoutTime = 300000;  //5minutes
	var timeoutTimer = setTimeout(ShowTimeOutWarning, timeoutTime);

    $('#PendingTasks').hide(); 
    $('#OLDpatientstable').hide(); 

    var reportcheck = new Array();

	var active_session_timer = 60000; //1minute
	var sessionTimer = setTimeout(inform_about_session, active_session_timer);
 var gconn=0;
	
$(window).load(function() {

	//alert("started");
	$(".loader_spinner").fadeOut("slow");
	
	var userSurname = $('#SearchUserUSERFIXED').val();
 	     var IdMed = $('#MEDID').val();
	     var UserDOB = '';
         var queUrl ='getPatientNetworkStats.php?Usuario='+userSurname+'&UserDOB='+UserDOB+'&IdDoc='+IdMed;
		 
	
		$.ajax({
			url: queUrl,
			success: function(data){
				//alert(data);
				var res = data.split("::");
				//alert(res[0] + '   ' + res[1]);
				var UConn = res[0];
				gconn=UConn;
				var UTotal = res[1];
				var UProbe = res[2];
				var TotMsg = res[3];
				var TotUpDr = res[4];
				var TotUpUs = res[5];
				
				$('#TotMsgV').html(TotMsg);
				$('#TotUpDrV').html(TotUpDr);
				$('#TotUpUsV').html(TotUpUs);
				if (TotMsg > 0) $('#TotMsgV').css('visibility','visible');
				if (TotUpDr > 0) $('#TotUpDrV').css('visibility','visible');
				if (TotUpUs > 0) $('#TotUpUsV').css('visibility','visible');
				
				//alert(UProbe);
				titulo = 'Percentage of the members that are connected to you ('+UConn+'), out of the total of members available ('+UTotal+') ';
				 $('#gaugetitulo').attr('Title',titulo);
				
				 $('#TotPats').css('font-size','50px');
				 $('#TotPats').css('color','#22aeff');
				 $('#TotPats').html(UTotal);
				 $('#TotPatsD').css('background-color','#22aeff');
				 $('#TotPatsD').css('border','1px solid #22aeff');
						
				 $('#TotConn').css('font-size','50px');
				 $('#TotConn').css('color','#54bc00');
				 $('#TotConn').html(UConn);
				 $('#TotConnD').css('background-color','#54bc00');
				 $('#TotConnD').css('border','1px solid #54bc00');
				 
				 $('#TotProbe').css('font-size','50px');
				 $('#TotProbe').css('color','#FFBF00');
				 $('#TotProbe').html(UProbe);
				 $('#TotProbeD').css('background-color','#FFBF00');
				 $('#TotProbeD').css('border','1px solid #FFBF00');

				 /*
				 $('#StatsPat').css('font-size','50px');
				 $('#StatsPat').css('color','#FF8000');
				 $('#StatsPat').html(pases);
				 $('#StatsPatN').css('font-size','16px');
				 $('#StatsPatN').css('color','#FF8000');
				 $('#StatsPatN').html(' x% from reach');
				 $('#StatsPatD').css('background-color','#FF8000');
				 $('#StatsPatD').css('border','1px solid #FF8000');
				*/
				 var quePorcentaje = Math.floor(100*(parseInt(UConn)/parseInt(UTotal)));
				 var g = new JustGage({
						id: "gauge", 
						value: quePorcentaje, 
						min: 0,
						max: 100,
						title: " ",
						label: "% CONNECTED"
					 });
				 $(".gauge_spinner").hide();
				
						
				
				
				
			}
		});

		
	
	
	
	})

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


    setTimeout(function(){
	   $('#newinbox').trigger('click');},2000);
	
	
	
    $(document).ready(function() {

    var start = 0;
    var currPage = 1;
    var lastPage = 1;
    var numDisplay = 20;
    var phase = 1;
    var user;
    var doctor;
	
	
	$("#Phone").intlTelInput();
	$("#Message").intlTelInput();
	
    $(window).bind('load', function(){
        $('#newinbox').trigger('click');
        //$('#BotonBusquedaSents').trigger('click');
        //getPatientList();
		
		//alert('clicked');
    });

    $('body').bind('mousedown keydown', function(event) {
        clearTimeout(timeoutTimer);
        timeoutTimer = setTimeout(ShowTimeOutWarning, timeoutTime);
    });

	/*function setGrabQuery()
	{
    setInterval(function() {
    		//alert ('in setInterval');
			//$('#BotonBusquedaPacCOMP').trigger('click');
            //$('#BotonBusquedaSents').trigger('click');
            
			//window.onload = function(){
			
            getPatientList();
			$('#BotonBusquedaProbe').trigger('click');
            $('#BotonBusquedaPending').trigger('click');
            $('#newinbox').trigger('click');
			$('#connect_spinner').css('display', 'none');
			//}
            
			//alert('called interval');
      }, 180000);
	  }
	  setGrabQuery();*/
	  
	  getPatientList();
			$('#BotonBusquedaProbe').trigger('click');
            $('#BotonBusquedaPending').trigger('click');
            $('#newinbox').trigger('click');


        
    $('#newinbox').live('click',function(){
       var MEDID = $('#MEDID').val();
       var queUrl ='<?php echo $domain;?>/getInboxMessageUSER-DR.php?IdMED='+MEDID;
	   ContentLoad = LanzaAjax (queUrl);
	   $('#datatable_3').html(ContentLoad);
       $('#datatable_3').trigger('update');
    });
   
    $('#newoutbox').live('click',function(){
       var MEDID = $('#MEDID').val();
       var queUrl ='<?php echo $domain;?>/getOutboxMessageUSER-DR.php?IdMED='+MEDID;
	   ContentLoad = LanzaAjax (queUrl);
	   $('#datatable_4').html(ContentLoad);
	   $('#datatable_4').trigger('update');
           
   });

    $('#compose_message').live('click',function(){
        $('#messagecontent_inbox').attr('value','');
        $('#ToDoctor').attr('value','To: ');
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
   
   
   //----------For Create Probe Pop up------------------------------------------------
   //Set options for timezone 
		get_timezones('gettimezones.php');
		var tz = document.getElementById('Timezone');
		for(var i=0;i<timezones.length;i++)
		{
			tz.options[tz.options.length] = new Option(timezones[i].timezones,timezones[i].id);
		}
		
		
		$('#Time').timepicker({ 'scrollDefaultNow': true });
		
		//Set options for probe intervals
		var interval = document.getElementById('Interval');
		interval.options[interval.options.length] = new Option("Daily",1);
		interval.options[interval.options.length] = new Option("Weekly",7);
		interval.options[interval.options.length] = new Option("Monthly",30);
		interval.options[interval.options.length] = new Option("Yearly",365);
		//alert('clicking');
		$('#BotonBusquedaProbe').trigger('click');
		
		//-----------------------------------------------------------------------------

    $('.CFILAPAT').live('click',function(){
       var id = $(this).attr("id");
       window.location.replace('patientdetailMED-new.php?IdUsu='+id);
    });

    $('.CFILAPATPendingBot2').live('click',function(){
       var id = $(this).attr("id");
       var id2 = $(this).attr("id2");    
       var id3 = $(this).attr("id3");    
       conf=confirm("Do you want to revoke ?");
	   if(conf){
           var IdMed = $('#MEDID').val();
           var cadena='RevokeLink.php?IdPatient='+id+'&IdDoctor='+IdMed+'&IdType='+id3;
           var RecTipo=LanzaAjax(cadena);
       }

    });

    $('.CENVELOPE').live('click',function(){
        var id = $(this).attr("id");
        //var id2 = $(this).attr("id2");
        //alert ('ENVELOPE Id= '+id+' Id2= ');
    });
                
    $('.BMessage').live('click',function(){
     	var myClass = $(this).attr("id");
	 	var myClass2 = $(this).attr("id2");
        SendMsgOnClick (myClass,myClass2);
     });

    $('.BRevoke').live('click',function(){
       var id = $(this).attr("id");
       var id2 = $(this).attr("id2");
       //alert ('Id= '+id+' Id2= '+id2);
     });

    $('.CFILA').live('click',function(){
       var id = $(this).attr("id");
       // Get Doctor id and some info
       var cadena='getDoctorMessage.php?msgid='+id;
       // Mini-parser of Rectipo to extract multiple values
       var RecTipo=LanzaAjax(cadena);
       var n = RecTipo.indexOf(",");
	   var IdDoctor = RecTipo.substr(0,n);
       var Remaining = RecTipo.substr(n+1,RecTipo.length);
       m = Remaining.indexOf(",");
	   var NameDoctor = Remaining.substr(0,m);
       var SurnameDoctor = Remaining.substr(m+1,Remaining.length);
       $("#IdDoctor").attr('value',IdDoctor);
       //throw "stop execution";
       
       //displaynotification('Message ID'+ id);
	   
       
       $('input[type=checkbox][id^="reportcol"]').each(
        function () {
		 $('input[type=checkbox][id^="reportcol"]').checked=false;
		});
	   reportcheck.length = 0;
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
               //alert ('Usuario: '+Usuario+' MedID: '+MedID);
                var queUrl ='CreateAttachmentStream.php?ElementDOM=na&EntryTypegroup='+EntryTypegroup+'&Usuario='+Usuario+'&MedID='+MedID+'&Reports='+reportsattached;
                var RecTipo=LanzaAjax(queUrl);
                //alert(RecTipo);
                $("#attachments").append(RecTipo);
                /*$("#attachments").load(queUrl);
                $("#attachments").trigger('update');*/
                $("#attachments").show();
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
       $("#ToDoctor").show();
       $('#ToDoctor').html('To: '+NameDoctor+' '+SurnameDoctor);
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
	   $('#message_modal').trigger('click');
	   var cadena='setMessageStatusUSER.php?msgid='+id;
	   var RecTipo=LanzaAjax(cadena);
	   /*setTimeout(function(){
	   $('#newinbox').trigger('click');},1000);*/
	   
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
		$("#attachments").show();
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
				}
			
				
	});
	 //alert(reportids);
	var conf=false;
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

    $("#Reply").live('click',function(){
       var subject_name='RE:'+($('#subjectname_inbox').val()).replace(/RE:/,'');
       $('#ToDoctor').show();
       $('#ToDoctor').attr('value','To: ');
       $('#subjectname_inbox').val(subject_name);   
       $('#messagedetails').hide();
       $('#replymessage').show();
       $('#attachments').hide();
       $('#Attach').hide();
       $(this).hide();
       $("#CloseMessage").hide();
       $('#sendmessages_inbox').show();
       $('#attachreports').show();
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
	     var queUrl ='getSearchUsers.php?Usuario='+UserInput+'&UserDOB='+UserDOB+'&IdDoc='+IdMed+'&NReports=4';
		 //console.log(queUrl);
    	 $('#TablaPac').load(queUrl);
    	 $('#TablaPac').trigger('update');
    	 //$('#BotonBusquedaSents').trigger('click');
        getPatientList();
    });     

    $("#BotonBusquedaMedCOMP").live('click',function() {
	     var UserInput = $('#SearchDoctor').val();
	     var UserDOB = $('#DoctorEmail').val();
	     var queUrl ='getSearchDoctors.php?Doctor='+UserInput+'&DrEmail='+UserDOB+'&NReports=4';
    	 $('#TablaMed').load(queUrl);
    	 $('#TablaMed').trigger('update');
    }); 

    $("#BotonBusquedaPending").click(function(event) {
 	     var IdMed = $('#MEDID').val();
	     var UserDOB = '';
         var queUrl ='getPatientsPending.php?Usuario=0&UserDOB='+UserDOB+'&IdDoc='+IdMed+'&NReports=3';
	     ContentDyn = LanzaAjax(queUrl);
        
         //console.log("From Pending patients"+ContentDyn);
         if (ContentDyn >'')
         {
            //$('#PendingTasks').show(); 
           // $('#TablaPacPending').html(ContentDyn);
           // $('#TablaPacPending').trigger('update');
         }
         else
         {
            $('#PendingTasks').hide(); 
         }
    });
    
    //$("#BotonBusquedaSents").live('click',function() {
    
	
	
	$("#probeTab").click(function(event) {
		//alert('clicked');
		$("#BotonBusquedaProbe").trigger('click');
	});
	$("#connectedpatients").click(function(event) {
	//$('#connectedpatients').live('click',function(){
		//$("#H2M_Spin_Stream_CP").show();
		//alert('clicked');
		//document.getElementById("TablaSents").innerHTML="<p>Loading</p>";
		//$('#TablaSents').trigger('update');
		//$('#Wait2').show(); 
		//document.getElementById("Wait2").style.display="block";
		$("#BotonBusquedaSents").trigger('click');
		
	});
    
    $("#BotonBusquedaSents").click(function(event){
        currPage = 1;
        start = 0;
        getPatientList();
    });
	
    var pases = 0;
    function getPatientList() 
    {
	
	var maxPerPage = 20;
		
     		var queMED = $("#MEDID").val();
    	    var UserInput = $('#SearchUserUSERFIXED').val();
    	       	     
				   
    	     if(UserInput===""){
			    UserInput=-111;
			 }
		
			
			var url = 'getNumPatients.php?searchString='+UserInput+'&IdMed='+queMED+'&All=1';
			var totalPatients = LanzaAjax(url);
	
		 var userSurname = $('#SearchUserUSERFIXED').val();
 	     var IdMed = $('#MEDID').val();
	     var UserDOB = '';
         var queUrl ='getPatientsConnected.php?Usuario='+userSurname+'&UserDOB='+UserDOB+'&IdDoc='+IdMed+'&start='+start;
		 //console.log(queUrl);
	     //ContentDyn = LanzaAjax(queUrl);
        var ContentDyn = "";
		$.ajax(
		{
			url: queUrl,
			
			success: function(data)
			{
				//alert(data);
				ContentDyn = data;
				//alert('loaded' + ContentDyn);
				$('#TablaSents').html(ContentDyn);
				$('#TablaSents').trigger('update');
				if (pases==1) $('#BotonBusquedaSents').trigger('update');
				$("#ConnectedPatientsLoader").hide();
				
				lastPage = Math.ceil(totalPatients/maxPerPage);
				updatePageDisplay();
				//alert(totalPatients);
			}
		});
		
        
    }     

    $("#BotonBusquedaPermit").click(function(event) {
 	     var IdMed = $('#MEDID').val();
	     var UserDOB = '';
	     var queUrl ='getPermits.php?Doctor='+IdMed+'&DrEmail='+UserDOB+'&NReports=3';
    	 $('#TablaPermit').load(queUrl);
    	 $('#TablaPermit').trigger('update');
    });       

    $("#BotonInvite").click(function(event) {
		alert ('Invite member');
    });       

    $(".CFILASents").live('click',function() {
     	 var myClass = $(this).attr("id");
 	     
 	     /*
 	     var IdMed = $('#MEDID').val();
	     var UserDOB = '';
	     var queUrl ='getPermits.php?Doctor='+IdMed+'&DrEmail='+UserDOB+'&NReports=3';
    	 $('#TablaPermit').load(queUrl);
    	 $('#TablaPermit').trigger('update');
		 */
    });       

    $("#SendButton").live('click',function() {
	     // Confirm
	     var subcadena='';
	     var CallPhone = 0;
	     if ($('#c2').attr('checked')=='checked'){ 
	     	subcadena =' (will call phone number also)';
		    CallPhone = 1; 
	     }
	     
	     var r=confirm('Confirm sending request to '+paciente+'?   '+subcadena);
	 	 if (r==true)
	 	 {
	    	var IdDocOrigin = $('#MEDID').val();
	     	var NameDocOrigin = $('#IdMEDName').val() ;
	     	var SurnameDocOrigin = $('#IdMEDSurname').val() ;
	     	// Update database table (1 or 2) and handle communication with Referral
		 	var cadena = '<?php echo $domain;?>/SendReferral.php?Tipo=1&IdPac='+IdPaciente+'&IdDoc='+IdDoctor+'&IdDocOrigin='+IdDocOrigin+'&NameDocOrigin='+NameDocOrigin+'&SurnameDocOrigin='+SurnameDocOrigin+'&ToEmail='+doctor[0].IdMEDEmail+'&From='+'&Leido=0&Push=0&estado=1'+'&CallPhone='+CallPhone;
		 	//alert (cadena);
		    var RecTipo = LanzaAjax (cadena);
			
			//$('#BotonBusquedaSents').trigger('click');
             getPatientList();
			$('#TextoSend').html('');
			//alert (RecTipo);
        	// Refresh table in this page accordingly
	 	 }
    });     
    
    $(".CFILADoctor").live('click',function() {
     	var myClass = $(this).attr("id");
	 	getMedCreator(myClass);
	 	destino = "Dr. "+doctor[0].Name+" "+doctor[0].Surname; 
	 	IdDoctor = doctor[0].id;
	    //alert (destino);	
	    TextoS ='<span style="color:grey;">Send </span><span style="color:#54bc00; font-size:30px;">   '+paciente+'   </span><span style="color:grey;"> to </span><span style="color:#22aeff; font-size:30px;">   '+destino+'   </span>';
	    if (paciente>'' && destino>'') TextoS = TextoS + '<input type="button" class="btn btn-success" value="SEND" id="SendButton" style="margin-left:20px; margin-top:-15px;"><p><input type="checkbox" id="c2" name="cc"><label for="c2" style="margin-top:10px;"><span></span> Urgent (call phone)</label></p>';
		$('#TextoSend').html(TextoS);
    }); 	

    $(".CFILAMODALxxxxxxx").live('click',function() {
     	var myClass = $(this).attr("id");
	 	alert (myClass);
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

    $(".CFILAMODALIdent").live('click',function() {
     	var myClass = $(this).attr("id");
	 	var myClass2 = $(this).attr("id2");
        SendMsgOnClick (myClass,myClass2);
    }); 	

    function SendMsgOnClick (id1,id2){
        //var myClass = $(this).attr("id");
	 	//var myClass2 = $(this).attr("id2");
 	    var myClass = id1;
	 	var myClass2 = id2;
 	    $('#IdUserSel').val(myClass);
 	    $('#IdUserSwitch').val(myClass2);
 	    
    	getUserData(myClass);
	 	paciente = user[0].Name+" "+user[0].Surname; 
	 	emailpaciente = user[0].email; 
	    phonepaciente = user[0].telefono; 
	    if (!emailpaciente) {contenido2 = '<p><input id="TempoEmail" name="TempoEmail" type="text" class="last-input" placeholder="Email" title="Please insert temporary email" style="padding-left:10px; margin-top:5px; width:250px;" /></p>'; }else{ contenido2 = '<p>Email is '+emailpaciente+'</p>';} 
	    if (!phonepaciente) {contenido3 = '<p><input id="TempoPhone" name="TempoPhone" type="text" class="last-input" placeholder="Phone number (inlude country code)" title="Please insert temporary phone" style="padding-left:10px; margin-top:5px; width:300px;" /></p>'; }else{ contenido3 = '<p>Phone number is '+phonepaciente+'</p>';}
 	    
        //alert (myClass2);
 	    switch (myClass2)
 	    {
	 	    case 'conn':	$('#ContenidoModal2').html('<p>Please enter member email to send a connection request:</p><p><input id="ResPas" name="ResPas" type="text" class="last-input" placeholder="email" title="Please insert email" style="padding-left:10px; margin-top:5px; width:250px;" /></p><p><input id="TelefUser" name="TelefUser" type="text" class="last-input" placeholder="phone number (add country code)" title="Please insert phone number" style="padding-left:10px; margin-top:5px; width:300px;" /></p>');
	        				$('#BotonMod2').trigger('click');
							break;
	 	    case 'invi':  	contenido1 ='<p>Please provide a temporary password for your member.</p><p><input id="TempoPas" name="TempoPas" type="text" class="last-input" placeholder="Temporary Password" title="Please insert temporary password" style="padding-left:10px; margin-top:5px; width:250px;" /></p>';
	 	    				contenido = contenido1+contenido2+contenido3;
	 	    				$('#ContenidoModal2').html(contenido);
	        				$('#BotonMod2').trigger('click');
							break;
	 	    case 'msg':  	//contenido = contenido1+contenido2+contenido3;
	 	    				//$('#ContenidoModal2').html(contenido);
                            $('#messagecontent_inbox').attr('value','');
                            $('#subjectname_inbox').attr('value','');
                            $("#ToDoctor").show();
                            $('#ToDoctor').html('To: '+paciente);
                            $('#subjectname_inbox').removeAttr("readonly");   
                            $('#messagedetails').hide();
                            $('#replymessage').show();
                            $("#attachments").empty();
                            $('#attachments').hide();
                            $('#Reply').hide();
                            $("#CloseMessage").hide();
                            $('#Attach').hide();
                            GlobalIdUser = myClass;
                            $('#sendmessages_inbox').show();
                            $('#attachreports').show();
                                                        
                            $('#message_modal').trigger('click');
                            //$('#BotonMod2').trigger('click');
							break;
	 	    default: //
	 	    			break;
 	    }
 	    
 	    //alert (myClass+' - '+myClass2);
 		/*
	 	getUserData(myClass);
	 	paciente = user[0].Name+" "+user[0].Surname; 
	 	IdPaciente = user[0].Identif;
	    TextoS ='<span style="color:grey;">Send </span><span style="color:#54bc00; font-size:30px;">   '+paciente+'   </span><span style="color:grey;"> to </span><span style="color:#22aeff; font-size:30px;">   '+destino+'   </span>';
	    if (paciente>'' && destino>'') TextoS = TextoS + '<input type="button" class="btn btn-success" value="SEND" id="SendButton" style="margin-left:20px; margin-top:-15px;"><p><input type="checkbox" id="c2" name="cc"><label for="c2"  style="margin-top:10px;" ><span></span> Urgent (call phone)</label></p>';
		$('#TextoSend').html(TextoS);
		*/

        }
        
    $('#sendmessages_inbox').live('click',function(){
             var content = $('#messagecontent_inbox').val();
             var subject = $('#subjectname_inbox').val();
			 var NameMed = $('#IdMEDName').val();
     	     var SurnameMed = $('#IdMEDSurname').val();
			 var UserName = user[0].Name;
             var UserSurname = user[0].Surname; 
			 
			 alert('Your messsage has been sent to '+UserName+' '+UserSurname);
			 
             if (subject < ' ')  subject='Message from your doctor.';
             //reportids = reportids.replace(/\s+$/g,' ');
             var IdPaciente = GlobalIdUser;
             var IdDocOrigin = $('#MEDID').val();
	     	 var Receiver = 0;
        
             reportids = ' ';
             var IdDocOrigin = $('#MEDID').val();
             //alert ('IdPaciente: '+IdPaciente+' - '+'Sender: '+IdDocOrigin+' - '+'Attachments: '+reportids+' - '+'Receiver: '+IdDoctor+' - '+'Content: '+content+' - '+'subject: '+subject+' - '+'connection_id: 0');
             var cadena='sendMessageUSER.php?sender='+IdDocOrigin+'&receiver='+IdDoctor+'&patient='+IdPaciente+''+'&content='+content+'&subject='+subject+'&attachments='+reportids+'&connection_id=0&tofrom=from';
             var RecTipo=LanzaAjax(cadena);
             //alert ('Answer of Messg Proc.?: '+RecTipo);
        	 $('#messagecontent_inbox').attr('value','');

             $('#subjectname_inbox').attr('value','');
             displaynotification('status',RecTipo);
             
             getUserData(IdPaciente);
	 	   
             var cadena='push_serverUSER.php?FromDoctorName='+NameMed+'&FromDoctorSurname='+SurnameMed+'&FromDoctorId='+IdDocOrigin+'&Patientname='+UserName+'&PatientSurname='+UserSurname+'&IdUsu='+IdPaciente+'&message= New Message <br>From: Dr. '+NameMed+' '+SurnameMed+' <br>Subject: '+(subject).replace(/RE:/,'')+'&NotifType=1&channel='+IdPaciente;
             //alert(cadena);
             var RecTipo=LanzaAjax(cadena);
             //}
             reportids='';
             $("#attachment_icon").remove();
             
             $('#message_modal').trigger('click');
			 
});
            
   	$('#CloseModal2').bind("click", function(){
	     // Confirm
	     var TelefTarget = $('#TelefUser').val();
 		 var subcadena='';
	     var CallPhone = 0;
	     if (TelefTarget > ' '){ 
	     	subcadena =' (will call phone number also)';
		    CallPhone = 1; 
	     }
	     
 		var UserInput =  $('#IdUserSel').val();
 		var UserSwitch =  $('#IdUserSwitch').val();
 		var TempoEmail =  $('#TempoEmail').val();
 		var TempoPhone =  $('#TempoPhone').val();
 		
 		getUserData(UserInput);
	 	paciente = user[0].Name+" "+user[0].Surname; 
	 	emailpaciente = user[0].email; 
	    phonepaciente = user[0].telefono; 
	 
	  	switch (UserSwitch)
 	    {
	 	    case 'conn':	EmailTarget = $('#ResPas').val();;
	 	    				emailsArray = EmailTarget.match(/([a-zA-Z0-9._-]+@[a-zA-Z0-9._-]+\.[a-zA-Z0-9._-]+)/gi);
						 	if (emailsArray == null || !emailsArray.length) 
						 	{
							 	alert ('Please enter a valid email address');
						 	}
						 	else
						 	{
							    var r=confirm('Confirm sending connection request to member '+paciente+' ?   '+subcadena);
							 	if (r==true)
							 	 {
							    	var IdDocOrigin = $('#MEDID').val();
							     	var NameDocOrigin = $('#IdMEDName').val() ;
							     	var SurnameDocOrigin = $('#IdMEDSurname').val() ;
								 	var cadena = '<?php echo $domain;?>/SendPatientCL.php?Tipo=1&IdPac='+UserInput+'&IdDoc='+IdDocOrigin+'&IdDocOrigin='+IdDocOrigin+'&NameDocOrigin='+NameDocOrigin+'&SurnameDocOrigin='+SurnameDocOrigin+'&ToEmail='+EmailTarget+'&From='+'&Leido=0&Push=0&estado=1'+'&PhoneNumber='+TelefTarget+'&CallPhone='+CallPhone+'&TempoPass=void';
							        //alert (cadena);
							        var RecTipo = LanzaAjax (cadena);
							        //alert (RecTipo);
								 }
						 	}
						 	break;
	 	    case 'invi':	PasswordTarget = $('#UTempoPass').val();
                            if (!emailpaciente) emailpaciente = TempoEmail;
	 						if (!phonepaciente) phonepaciente = TempoPhone;
	 						subcadena =' (will send email to: '+emailpaciente+', and call phone number: '+phonepaciente+')';
	 						
	 						if (phonepaciente > '') CallPhone = '1';
	 						
	 						var r=confirm('Confirm sending invitation to member '+paciente+' ?   '+subcadena);
							if (r==true)
							 	 {
							    	var IdDocOrigin = $('#MEDID').val();
							     	var NameDocOrigin = $('#IdMEDName').val() ;
							     	var SurnameDocOrigin = $('#IdMEDSurname').val() ;
								 	var cadena = '<?php echo $domain;?>/SendPatientCL.php?Tipo=2&IdPac='+UserInput+'&IdDoc='+IdDocOrigin+'&IdDocOrigin='+IdDocOrigin+'&NameDocOrigin='+NameDocOrigin+'&SurnameDocOrigin='+SurnameDocOrigin+'&ToEmail='+emailpaciente+'&From='+'&Leido=0&Push=0&estado=1'+'&PhoneNumber='+phonepaciente+'&CallPhone='+CallPhone+'&TempoPass='+PasswordTarget;
							        //alert (cadena);
							        var RecTipo = LanzaAjax (cadena);
								 }
						 	break;
	 	}   
	 	    
	 	    
	 	

    
    });   
     
    $("#BotonWizard").click(function(event) {
         $("#BotonBusquedaPacNEW").trigger('click');
         phase = 1;
         GRelType = -1;
         GKnows = -1;
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
         $('#TablaMed').empty();
         $('#TablaPacModal').empty();
         $('#SearchUserT').val('');
         $('#UEmail').val('');
         $('#UPhone').val('');
         $('#UTempoPass').val('');
         $('#IdPatient').val('0');

    });
 
    $("#PhaseNext").click(function(event) {
      
     if (GKnows == 1) {
          $('#UTempoPass').css('visibility','visible');
          
          $("#DivConnect").css("background-color","#f5f5f5");
          $("#SpanConnect").html("Already linked");
          $("#SpanConnect").css('color','black');
          $("#IconConnect").css("color","black");
          $("#IconConnect").html('<i class="icon-link icon-3x" ></i>');

          $("#DivInvite").css("background-color","#ddd");
          $("#SpanInvite").html("WILL SEND INVITATION");
          $("#SpanInvite").css('color','#54bc00');
          $("#IconInvite").css("color","#54bc00");
          $("#IconInvite").html('<i class="icon-tag icon-3x icon-spin" ></i>');
      }
      else
      {
          $('#UTempoPass').css('visibility','hidden');

          $("#DivConnect").css("background-color","#ddd");
          $("#SpanConnect").html("WILL SEND REQUEST");
          $("#SpanConnect").css('color','#22aeff');
          $("#IconConnect").css("color","#22aeff");
          $("#IconConnect").html('<i class="icon-link icon-3x icon-spin" ></i>');

          $("#DivInvite").css("background-color","#f5f5f5");
          $("#SpanInvite").html("Wait for permission");
          $("#SpanInvite").css('color','black');
          $("#IconInvite").css("color","black");
          $("#IconInvite").html('<i class="icon-tag icon-3x" ></i>');
      }
      if (phase < 2) 
      {
          var UserInput =  $('#IdPatient').val();
          if (UserInput<1)
          {
          }
          else
          {
          phase++; 
          }
      } else 
        {
                var TempoPassSEL = $('#UTempoPass').val();
                if (GKnows == 1 && TempoPassSEL < 8)
                {
                    alert ('Please create a password (8 characters minimum)');
                }
                else
                {
                                var TelefTarget = $('#UPhone').val();
                                var subcadena='';
                                var CallPhone = 0;
                                if (TelefTarget > ' '){ 
                                   subcadena =' (will call phone number also)';
                                   CallPhone = 1; 
                                }
                                var UserInput =  $('#IdPatient').val();
                                getUserData(UserInput);
                                paciente = user[0].Name+" "+user[0].Surname; 
                                emailpaciente = user[0].email; 
                                phonepaciente = user[0].telefono; 
                
                                //alert (UserInput);
                                    if (GKnows == 1) {
                                        var UserSwitch =  'invi';
                                    }else  {
                                        UserSwitch =  'conn'; 
                                    }
                                    var TempoEmail =  $('#UEmail').val();
                                    var TempoPhone =  $('#UPhone').val();
                                    
                                 
                                    switch (UserSwitch)
                                    {
                                        case 'conn':	EmailTarget = TempoEmail;
                                                        emailsArray = EmailTarget.match(/([a-zA-Z0-9._-]+@[a-zA-Z0-9._-]+\.[a-zA-Z0-9._-]+)/gi);
                                                        if (emailsArray == null || !emailsArray.length) 
                                                        {
                                                            alert ('Please enter a valid email address');
                                                        }
                                                        else
                                                        {
                                                            var r=confirm('Confirm sending connection request to member '+paciente+' ?   '+subcadena);
                                                            if (r==true)
                                                             {
                                                                var IdDocOrigin = $('#MEDID').val();
                                                                var NameDocOrigin = $('#IdMEDName').val() ;
                                                                var SurnameDocOrigin = $('#IdMEDSurname').val() ;
                                                                var cadena = '<?php echo $domain;?>/SendPatientCL.php?Tipo=1&IdPac='+UserInput+'&IdDoc='+IdDocOrigin+'&IdDocOrigin='+IdDocOrigin+'&NameDocOrigin='+NameDocOrigin+'&SurnameDocOrigin='+SurnameDocOrigin+'&ToEmail='+EmailTarget+'&From='+'&Leido=0&Push=0&estado=1'+'&PhoneNumber='+TelefTarget+'&CallPhone='+CallPhone+'&TempoPass=void';
                                                                //alert (cadena);
                                                                var RecTipo = LanzaAjax (cadena);
                                                                //alert (RecTipo);
                                                             }
                                                        }
                                                        break;
                                        case 'invi':	PasswordTarget = $('#UTempoPass').val();
                                                        if (!emailpaciente) emailpaciente = TempoEmail;
                                                        if (!phonepaciente) phonepaciente = TempoPhone;
                                                        subcadena =' (will send email to: '+emailpaciente+', and call phone number: '+phonepaciente+')';
                                                        
                                                        if (phonepaciente > '') CallPhone = '1';
                                                        
                                                        var r=confirm('Confirm sending invitation to member '+paciente+' ?   '+subcadena);
                                                        if (r==true)
                                                             {
                                                                var IdDocOrigin = $('#MEDID').val();
                                                                var NameDocOrigin = $('#IdMEDName').val() ;
                                                                var SurnameDocOrigin = $('#IdMEDSurname').val() ;
                                                                var cadena = '<?php echo $domain;?>/SendPatientCL.php?Tipo=2&IdPac='+UserInput+'&IdDoc='+IdDocOrigin+'&IdDocOrigin='+IdDocOrigin+'&NameDocOrigin='+NameDocOrigin+'&SurnameDocOrigin='+SurnameDocOrigin+'&ToEmail='+emailpaciente+'&From='+'&Leido=0&Push=0&estado=1'+'&PhoneNumber='+phonepaciente+'&CallPhone='+CallPhone+'&TempoPass='+PasswordTarget;
                                                                //alert (cadena);
                                                                var RecTipo = LanzaAjax (cadena);
                                                             }
                                                        break;
                                    }   
                                $('#CloseModal').trigger('click');
                                $('#SendButton').trigger('click');
                }
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
    
    $("#BotonBusquedaPacNEW").click(function(event) {
 	     //wizard
		 
		 var search = $('#SearchUserT').val();
         var IdMed = $('#MEDID').val();
	     var UserDOB = '';
		 $('#Wait2').show();
         var queUrl ='getPatientsConnectedShort.php?Usuario='+search+'&UserDOB='+UserDOB+'&IdDoc='+IdMed+'&NReports=3';
	     //alert(queUrl);
		 
		 
		 $.ajax(
           {
           url: queUrl,
           dataType: "html",
           success: function(data)
           {
           	
  			 $('#TablaPacModal').html(data);
			 $('#TablaPacModal').trigger('update');
			 if (pases==1) $('#BotonBusquedaSents').trigger('update');
			 //console.log('done');
			 $('#Wait2').hide();
           }
         });
		 
		 
		 
		 
		 
		 
		 
		 
		 
		 /*ContentDyn = LanzaAjax(queUrl);
		 console.log('done');
        
         $('#TablaPacModal').html(ContentDyn);
    	 $('#TablaPacModal').trigger('update');
         if (pases==1) $('#BotonBusquedaSents').trigger('update');
		*/
    });       
    
    $('.CFILAPATModal').live('click',function(){
        var id = $(this).attr("id");
        var IdPaciente = id;
        $('#IdPatient').val(id);
        var conte = $(this).attr("id2");
        var Relat = $(this).attr("id3");
        var Knows = $(this).attr("id4");
        var PEmail = $(this).attr("id5");
        var PPhone = $(this).attr("id6");
        if (PEmail < '..') PEmail='';
        $('#UEmail').val(PEmail);
        $('#UPhone').val(PPhone);
        GRelType = Relat;
        GKnows = Knows;

        $('#PhaseNext').trigger('click');
        TextoS ='<span style="color:grey;">Selected </span><span style="color:#54bc00; font-size:30px;">   '+conte+'   </span><span style="color:#22aeff; font-size:20px;">   ('+IdPaciente+')  (Relat: '+Relat+')  (Knows: '+Knows+') </span>';
        //if (paciente>'' && destino>'') TextoS = TextoS + '';
        $('#TextoSend').html(TextoS);
    });

        
function justtest()
{
alert ('Test ok');
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
        
	    	var Content = 'Dr. '+NameMed+' '+SurnameMed+' is requesting to establish connection with your member named: '+user[0].Name+' '+user[0].Surname+' (UserId:  '+To+'). Please confirm, or just close this message to reject.';
    	
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

$("#prevPatients").click(function(event){
    if(currPage > 1)
    {
        currPage--;
        start -= numDisplay;
        getPatientList();
        updatePageDisplay();
    }
});

$("#nextPatients").click(function(event){
    if(currPage < lastPage)
    {
        currPage++;
        start += numDisplay;
        getPatientList();
        updatePageDisplay();
    }
})

function updatePageDisplay()
{
    document.getElementById("pageDisplay").innerHTML = currPage + "/" + lastPage;
}

$('#Wait1')
    .hide()  // hide it initially
    .ajaxStart(function() {
        //alert ('ajax start');
        $(this).show();
    })
    .ajaxStop(function() {
        $(this).hide();
    }); 

$('#Wait3')
    .hide()  // hide it initially
    .ajaxStart(function() {
        //alert ('ajax start');
        $(this).show();
    })
    .ajaxStop(function() {
        $(this).hide();
    }); 

$('#Wait4')
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
  
  
  $("#BotonBusquedaProbe").click(function(event) {
			var maxPerPage = 20;
		
     		var queMED = $("#MEDID").val();
    	    var UserInput = $('#searchProbe').val();
    	       	     
			var onlyGroup=0;
				   
    	     if(UserInput===""){
			    UserInput=-111;
			 }
		
			var GetAll = 1;
    	    if ($('#CToggle').is(":checked")) GetAll = 0;
			
			//GetAll=1 --> Get all patients
			//GetAll=0 --> Get only probed patients
		
			
			var url = 'getNumPatients.php?searchString='+UserInput+'&IdMed='+queMED+'&All='+GetAll;
			var totalPatients = LanzaAjax(url);
			
			
			var totalpage = Math.ceil(totalPatients/maxPerPage);
			
			//alert(totalPatients);
			
			
			if(currpage<1)
			{
				currpage=totalpage;
			}
			
			if(currpage>totalpage)
			{
				currpage=1;
			}
			
			
			$('#CurrentPage').text(currpage+" of "+totalpage);
			
			
						
    	    //var queUrl ='getFullUsersMED.php?Usuario='+UserInput+'&IdMed='+queMED+'&Usuario='+UserInput+'&Group='+onlyGroup;
			var queUrl = 'getProbeTable.php?searchString='+UserInput+'&IdMed='+queMED+'&All='+GetAll+'&currPage='+currpage+'&Limit='+maxPerPage;
			//console.log(queUrl);
			
    	    $('#TablaProbe').load(queUrl);
    	    $('#TablaProbe').trigger('update');
  	    
    });
	
	 $("#prev").live('click',function() {
		currpage=currpage-1;
		$('#BotonBusquedaProbe').trigger('click');
	 });
	 
	$("#next").live('click',function() {
		currpage=currpage+1;
		$('#BotonBusquedaProbe').trigger('click');
	 });

	$(".SendMessage").live('click',function() {
		var myClass = $(this).attr("id");
//		alert(myClass);
		var r = confirm("Are you sure you want to probe this member now ?");
		if (r == true)
 	    {
		  var url = 'sendEmergencyProbe.php?probeID='+myClass;
		  console.log(url);
		  var response = LanzaAjax(url);
		  displaynotification("Probe Sent",response);
		  
		}
		else
		{
		  //x = "You pressed Cancel!";
		}
		
		
		
	});
	 
	$(".RevokeProbe").live('click',function() {
		var myClass = $(this).attr("id");
		var url = 'revokeProbe.php?probeID='+myClass;
		var response = LanzaAjax(url);
		$('#BotonBusquedaProbe').trigger('click');
		displaynotification("Probe Revoked"," ");
	});
	
	$(".CannotProbe").live('click',function() {
		displaynotification("Cannot Probe","Member does not want to receive Probes.");
	});
	
	$(".RestartProbe").live('click',function() {
		var myClass = $(this).attr("id");
		var url = 'restartProbe.php?probeID='+myClass;
		var response = LanzaAjax(url);
		$('#BotonBusquedaProbe').trigger('click');
		displaynotification("Probe Restarted"," ");
	});
	
	$(".probePopUp").live('click',function() {
		
		var myClass=$(this).closest('div').attr('id');
		var url = 'getPatientContactInfo.php?idusu='+myClass;
		getusuariosdata(url);
			
		var quecolor='RED';
		$('#InfoIDPacienteBB').html('<span id="ETUSER" class="label label-info" style="font-size:18px; padding:5px 10px 5px 10px; font-family: “Andale Mono”, AndaleMono, monospace; margin-left:20px;">'+myClass+'</span><span class="label label-info" style="background-color:'+quecolor+'; font-size:14px; padding:5px 10px 5px 10px; font-family: “Andale Mono”, AndaleMono, monospace; margin-left:20px;">'+patient[0].idusfixedname+'</span>');	
				
		
		var url = 'getProbeHistory.php?patientID='+myClass;
		var RecTipo = LanzaAjax(url);
		$('#TablaProbeHistory').html(RecTipo);
		
		var responded = $('#ProbesResponded').val();
		var sent = $('#ProbesSent').val();
		var method = $('#ProbeMethod').val();
		
		var methodIcon ='';
		switch(parseInt(method))
		{
			case 1: methodIcon='<i class="icon-envelope icon-2x" style="color:#61a4f0;margin-left:20px" title="Probed via Email"></i>';
					break;
			case 2: methodIcon='<i class="icon-phone icon-2x" style="color:green;margin-left:20px" title="Probed via Phone Call"></i>';
					break;
			case 3: methodIcon='<i class="icon-comment icon-2x" style="color:gray;margin-left:20px" title="Probed via SMS"></i>';
					break;		
		}
		
		
		
		
		
		
		var per = responded/sent*100;
		
		
		
		
		$('#InfoIDPacienteStats').html('<span id="ETUSER" class="label label-info" style="background-color:#54bc00;font-size:18px; padding:5px 10px 5px 10px; font-family: “Andale Mono”, AndaleMono, monospace; ">'+per.toPrecision(3)+'% Answered</span><span>'+methodIcon+'</span>');			
	
		$('#TablaProbeHistory').trigger('update');
	
		$('#probeHistory').trigger('click');
			
		
		//alert(pid);
	});
	
	

  
	
	$(".EditProbe").live('click',function() {
		var probeID = $(this).attr("id");
		
		var url = 'getProbeData.php?probeID='+probeID;
		getprobedata(url);
		
		
		//Fill out the existing data in the popup
		var url = 'getPatientContactInfo.php?idusu='+probe[0].patientID;
		getusuariosdata(url);
		$('#patientID').val(probe[0].patientID);
		
		var quecolor='RED';
		$('#InfoIDPacienteB').html('<span id="ETUSER" class="label label-info" style="font-size:18px; padding:5px 10px 5px 10px; font-family: “Andale Mono”, AndaleMono, monospace; margin-left:20px;">'+probe[0].patientID+'</span><span class="label label-info" style="background-color:'+quecolor+'; font-size:14px; padding:5px 10px 5px 10px; font-family: “Andale Mono”, AndaleMono, monospace; margin-left:20px;">'+patient[0].idusfixedname+'</span>');
		//alert(patient[0].idusfixedname + '  ' + patient[0].email + '   ' + patient[0].telefono);
		
		$('#Email').val(patient[0].email);
		//$('#Phone').val(patient[0].telefono);
		//$('#Message').val(patient[0].telefono);
		$("#Phone").val('+'+patient[0].telefono);
		$("#Message").val('+'+ patient[0].telefono);
		
		document.getElementById('Timezone').value=probe[0].timezone;
		document.getElementById('Time').value=probe[0].desiredTime;
		document.getElementById('Interval').value=probe[0].probeInterval;
		
		if(probe[0].emailRequest==1)
		{
			document.getElementById('Method').value='Email';
		}
		else if(probe[0].phoneRequest==1)
		{
			document.getElementById('Method').value='Phone';
		}
		else //if(probe[0].smsRequest==1)
		{
			document.getElementById('Method').value='Message';
		}
		changeMethod();
		
		$('#ProbeIDHidden').val(probeID);
		
		$('#probeRequest').trigger('click');
		
		
		
		
		
		//displaynotification("Probe Edited"," ");
		
	});
	
	$("#CToggle").click(function(event) {
		$('#BotonBusquedaProbe').trigger('click');
   	});
	
   $(".CreateProbe").live('click',function() {
		var myClass = $(this).attr("id");
		var url = 'getPatientContactInfo.php?idusu='+myClass;
		getusuariosdata(url);
		$('#patientID').val(myClass);
		
		var quecolor='RED';
		$('#InfoIDPacienteB').html('<span id="ETUSER" class="label label-info" style="font-size:18px; padding:5px 10px 5px 10px; font-family: “Andale Mono”, AndaleMono, monospace; margin-left:20px;">'+myClass+'</span><span class="label label-info" style="background-color:'+quecolor+'; font-size:14px; padding:5px 10px 5px 10px; font-family: “Andale Mono”, AndaleMono, monospace; margin-left:20px;">'+patient[0].idusfixedname+'</span>');
		//alert(patient[0].idusfixedname + '  ' + patient[0].email + '   ' + patient[0].telefono);
		
		$('#Email').val(patient[0].email);
		$('#Phone').val(patient[0].telefono);
		$('#Message').val(patient[0].telefono);
		
		$('#ProbeIDHidden').val(-111);
		
		$('#probeRequest').trigger('click');
		
   });
   
     
	$('#createProbe').live('click',function(){	 
	
	
		var timezone = $('#Timezone').val();
		var time = $('#Time').val();
		var interval = $('#Interval').val();
		
		if(time=='')
		{
			alert('Please select appropriate time');
			return;
		}
		
		
		
		var selectedOption = $('#Method').val();
		
		var email=1;
		var phone=0;
		var message=0;

		var contactMedium = "";	
		switch(selectedOption)
		{
			case 'Email':email=1;
						 phone=0;
						 message=0;
						 if(validateEmail($('#Email').val())==false)
						 {
							alert('Invalid Email');
							return;
						 }
						 
						 contactMedium = "1::"+$('#Email').val();
						 break;
			case 'Phone':phone=1;
						 email=0;
						 message=0;
						 
						 //if($("#Phone").intlTelInput("isValidNumber")==false)
						//{
						//	alert('Invalid Phone Number');
						//	return;
						//}
						//else
						//{	
							//$('#Phone').val($('#Phone').val().replace(/-/g, '')); //remove dashes
							$('#Phone').val($('#Phone').val().replace(/\s+/g, '')); //remove spaces
							
							
						//}
						contactMedium = "2::"+$('#Phone').val();
						
						 break;
			case 'Message':phone=0;
						 email=0;
						 message=1;
						 
						 //if($("#Message").intlTelInput("isValidNumber")==false)
						//{
							//alert('Invalid Phone Number');
							//return;
						//}
						//else
						//{
							//$('#Message').val($('#Message').val().replace(/-/g, '')); //remove dashes
							$('#Message').val($('#Message').val().replace(/\s+/g, '')); //remove spaces
							
							
						//}
						contactMedium = "2::"+$('#Message').val();
						 break;
		}
		
		
		
		
		var intervalText ='';
		switch(interval)
		{
			case '1':intervalText='daily';break;
			case '7':intervalText='weekly';break;
			case '30':intervalText='monthly';break;
			case '365':intervalText='yearly';break;
		
		}
		
		
		
		var text = 'Are you sure you want '+patient[0].idusfixedname+' to be contacted '+intervalText+' at '+time + ' for health status feedback.';
		var r = confirm(text);
		if(r==true)
		{
		
			
			//return;
			var url='createProbe.php?idusu='+$('#patientID').val()+'&timezone='+timezone+'&time='+time+'&interval='+interval+'&email='+email+'&phone='+phone+'&message='+message+'&contact='+contactMedium+'&probeID='+$('#ProbeIDHidden').val();	
			//alert(url);
			var response = LanzaAjax(url);
			//alert(response);
			
			if(response=='success'){
				//alert('Probe Created Successfully');
				
				if($('#ProbeIDHidden').val()==-111)
				{			
					displaynotification("Probe Created Successfully"," ");
				}
				else
				{
					displaynotification("Probe Edited Successfully"," ");
				}
			}
			else {
				alert('Error Creating Probe');
			}
		}
		else
		{
		
		}
		$('#BotonBusquedaProbe').trigger('click');
		
	});
	
	function validateEmail(email) { 
		var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
		return re.test(email);
	} 
	/*
	function validatePhone(inputtxt)
	{
		  var phoneno = /^\(?([0-9]{3})\)?[-. ]?([0-9]{3})[-. ]?([0-9]{4})$/;
		  return phoneno.test(inputtxt);
		  
		  
	}*/
	
	$( "#Method" ).change(function() {
		
		changeMethod();
		/*var selectedOption = $('#Method').val();
		
		var emailRow = document.getElementById('EmailRow').style;
		var phoneRow = document.getElementById('PhoneRow').style
		var messageRow = document.getElementById('MessageRow').style
		switch(selectedOption)
		{
			case 'Email':		     
				 emailRow.display = 'table-row';
				 phoneRow.display = 'none';
				 messageRow.display = 'none';
				 break;
			case 'Phone':
				 emailRow.display = 'none';
				 phoneRow.display = 'table-row';
				 messageRow.display = 'none';		
				break;
			case 'Message':
				 emailRow.display = 'none';
				 phoneRow.display = 'none';
				 messageRow.display = 'table-row';	
				break;
		
		
		}*/
		
		
	});
	
	function changeMethod()
	{
		var selectedOption = $('#Method').val();
		
		var emailRow = document.getElementById('EmailRow').style;
		var phoneRow = document.getElementById('PhoneRow').style
		var messageRow = document.getElementById('MessageRow').style
		switch(selectedOption)
		{
			case 'Email':		     
				 emailRow.display = 'table-row';
				 phoneRow.display = 'none';
				 messageRow.display = 'none';
				 break;
			case 'Phone':
				 emailRow.display = 'none';
				 phoneRow.display = 'table-row';
				 messageRow.display = 'none';		
				break;
			case 'Message':
				 emailRow.display = 'none';
				 phoneRow.display = 'none';
				 messageRow.display = 'table-row';	
				break;
		
		
		}
	
	}

	
  function getusuariosdata(serviceURL) 
	{
		//retrieves patient idusfixedname,email and phone
		$.ajax(
		{
			url: serviceURL,
			dataType: "json",
			async: false,
			success: function(data)
			{
				//alert('Data Fetched');
				patient = data.items;
			}
		});
	}
	
	//retrieves probe information for given probeID
	function getprobedata(serviceURL) 
	{
		
		$.ajax(
		{
			url: serviceURL,
			dataType: "json",
			async: false,
			success: function(data)
			{
				//alert('Data Fetched');
				probe = data.items;
			}
		});
	}
	
	
	//Gets all rows from timezone table
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
   
    <script src="js/jquery.timepicker.min.js"></script>   
    <script src="js/jquery.flot.min.js"></script>
    <!--<script src="js/jquery.flot.pie.js"></script>-->
    <!--<script src="js/jquery.flot.orderBars.js"></script>-->
    <!--<script src="js/jquery.flot.resize.js"></script>-->
    <!--<script src="js/graphtable.js"></script>-->
    <script src="js/fullcalendar.min.js"></script>
    <script src="js/chosen.jquery.min.js"></script>
    <script src="js/autoresize.jquery.min.js"></script>
    <script src="js/jquery.tagsinput.min.js"></script>
    <script src="js/jquery.autotab.js"></script>
    <script src="js/elfinder/js/elfinder.min.js" charset="utf-8"></script>
	<script src="js/tiny_mce/tiny_mce.js"></script>
    <!--<script src="js/validation/languages/jquery.validationEngine-en.js" charset="utf-8"></script>-->
	<script src="js/validation/jquery.validationEngine.js" charset="utf-8"></script>
    <script src="js/jquery.jgrowl_minimized.js"></script>
    <script src="js/jquery.dataTables.min.js"></script>
    <!--<script src="js/jquery.mousewheel.js"></script>-->
    <script src="js/jquery.jscrollpane.min.js"></script>
    <!--<script src="js/jquery.stepy.min.js"></script>-->
    <!--<script src="js/jquery.validate.min.js"></script>-->
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
