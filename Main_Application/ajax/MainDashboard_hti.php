<?php
session_start();
 require("environment_detail.php");
 require("PasswordHash.php");
 require "Services/Twilio.php";
 require("displayExitClass.php");
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];

if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 1800)) {
    // last request was more than 30 minutes ago
    session_unset();     // unset $_SESSION variable for the run-time 
    session_destroy();   // destroy session data in storage
    echo "<META http-equiv='refresh' content='0;URL=index-col.html'>";
}
$_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp

$name=empty($_POST['Nombre'])   ? $_SESSION['Nombre'] : $_POST['Nombre'];


$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }	

//For checking multiple logins	
$query = $con->prepare("select * from ongoing_sessions where userid=?");
$query->bindValue(1, $_SESSION['MEDID'], PDO::PARAM_INT);
$result=$query->execute();

$count = $query->rowCount();
$ip = $_SERVER['REMOTE_ADDR'];	
if($count==0)
{
    $q = $con->prepare("insert into  ongoing_sessions values(?,now(),?)");
	$q->bindValue(1, $_SESSION['MEDID'], PDO::PARAM_INT);
	$q->bindValue(2, $ip, PDO::PARAM_STR);
	$q->execute();
}
else
{

    $q = $con->prepare("select * from ongoing_sessions where userid=? and ip=?");
	$q->bindValue(1, $_SESSION['MEDID'], PDO::PARAM_INT);
	$q->bindValue(2, $ip, PDO::PARAM_STR);
	$res=$q->execute();
	
    $cnt = $q->rowCount();
    if($cnt==1)
    {
        //The same user came back after abrupt logout (and before service could detect)
        //DO NOTHING
    }
    else
    {
        echo "Other users are Accessing this account";
     
        $q = $con->prepare("insert into  ongoing_sessions values(?,now(),?)");
		$q->bindValue(1, $_SESSION['MEDID'], PDO::PARAM_INT);
		$q->bindValue(2, $ip, PDO::PARAM_STR);
		$q->execute();
        header( 'Location: double_login.php' ) ;
        
    }
}

//Create a folder for user if not already present
if (!file_exists('temp/'.$_SESSION['MEDID'])) 
{
    mkdir('temp/'.$_SESSION['MEDID'], 0777, true);
	mkdir('temp/'.$_SESSION['MEDID'].'/Packages_Encrypted', 0777, true);
	mkdir('temp/'.$_SESSION['MEDID'].'/PackagesTH_Encrypted', 0777, true);
}


$result = $con->prepare("select pass from encryption_pass where id = (select max(id) from encryption_pass)");
$result->execute();
$row = $result->fetch(PDO::FETCH_ASSOC);
$_SESSION['decrypt']=$row['pass'];

$NombreEnt = $_SESSION['Nombre'];
$MEDID = $_SESSION['MEDID'];
$Acceso = $_SESSION['Acceso'];
$privilege=$_SESSION['Previlege'];
$privilege=3; //debug testing
if ($Acceso != '23432')
{
    $exit_display = new displayExitClass();

	$exit_display->displayFunction(1);
    die;
}


$result = $con->prepare("SELECT * FROM doctors where id=?");
$result->bindValue(1, $MEDID, PDO::PARAM_INT);
$result->execute();

$count = $result->rowCount();
$row = $result->fetch(PDO::FETCH_ASSOC);

$telemed = -1;
$telemed_name = '';
$success ='NO';
if($count==1){
	$success ='SI';
	$MedID = $row['id'];
	$MedUserEmail= $row['IdMEDEmail'];
	$MedUserName = $row['Name'];
	$MedUserSurname = $row['Surname'];
	$MedUserLogo = $row['ImageLogo'];
	$IdMedFIXED = $row['IdMEDFIXED'];
	$IdMedFIXEDNAME = $row['IdMEDFIXEDNAME'];
    $telemed_timestamp = $row['cons_req_time'];
    $date = date_create();
    $telemed = $row['consultation_pat'];
    //$result_pat = mysql_query("SELECT Name,Surname FROM usuarios where Identif='".$telemed."'");
    //$row_pat = mysql_fetch_array($result_pat);
    
    $result_pat = $con->prepare("SELECT Name,Surname FROM usuarios where Identif=?");
	$result_pat->bindValue(1, $telemed, PDO::PARAM_INT);
	$result_pat->execute();
	
    $row_pat = $result_pat->fetch(PDO::FETCH_ASSOC);
    
    $telemed_name = $row_pat['Name'].' '.$row_pat['Surname'];
    $telemed_type = $row['telemed_type'];
    $in_consultation = intval($row['in_consultation']);
    
    $API_VERSION = '2010-04-01';
    $AccountSid = "AC109c7554cf28cdfe596e4811c03495bd";
    $AuthToken = "26b187fb3258d199a6d6edeb7256ecc1";
    $client = new Services_Twilio($AccountSid, $AuthToken);
    $docs_in_consultation = array();
    foreach ($client->account->conferences->getIterator(0, 50, array("Status" => "in-progress")) as $conference)
    {
        $conference_name = explode("_", $conference->friendly_name);
        $doc_id = intval($conference_name[0]);
        if(!in_array($doc_id, $docs_in_consultation))
        {
            array_push($docs_in_consultation, $doc_id);
        }
    }
    if($in_consultation != 1)
    {
        $telemed_type = 1;
        if(in_array(intval($row['id']), $docs_in_consultation))
        {
            $in_consultation = 1;
        }
        else
        {
            $in_consultation = 0;
        }
    }
    else
    {
        $telemed_type = 2;
    }
	
    $MedUserRole = $row['Role'];
	if ($MedUserRole=='1') $MedUserTitle ='Dr. '; else $MedUserTitle =' ';
    
    // Get data about the Group this user belongs to
    $resultG = $con->prepare("SELECT * FROM doctorsgroups where idDoctor=?");
	$resultG->bindValue(1, $MEDID, PDO::PARAM_INT);
	$resultG->execute();
	
    $countG = $resultG->rowCount();
    $rowG = $resultG->fetch(PDO::FETCH_ASSOC);
    
    $successG ='NO';
    $MedGroupName = '';
    $MedGroupAddress = '';
    $MedGroupZIP = '';
    $MedGroupCity = '';
    $MedGroupState = '';
    $MedGroupCountry = '';
	$MedGroupId = '-1';
	
    if($countG==1)
    {
        $MedGroupId = $rowG['idGroup'];
        
        $resultGN = $con->prepare("SELECT * FROM groups where id=?");
		$resultGN->bindValue(1, $MedGroupId, PDO::PARAM_INT);
		$resultGN->execute();
		
        $countGN = $resultGN->rowCount();
        $rowGN = $resultGN->fetch(PDO::FETCH_ASSOC);
        $MedGroupName = $rowGN['Name'];
        $MedGroupAddress = $rowGN['Address'];
        $MedGroupZIP = $rowGN['ZIP'];
        $MedGroupCity = $rowGN['City'];
        $MedGroupState = $rowGN['State'];
        $MedGroupCountry = $rowGN['Country'];
        
        //Get Number of users attatched to this group
        $resultGN2 = $con->prepare("SELECT * FROM doctorsgroups where idGroup=?");
		$resultGN2->bindValue(1, $MedGroupId, PDO::PARAM_INT);
		$resultGN2->execute();
		
        $countGN2 = $resultGN2->rowCount();
        $UsersInGroup = $countGN2;
    }
    

    
    // Get Information about number of patients belonging to this Doctor (and reports)
    $Num_Own_Patients = 0;
    $Num_Group_Patients = 0;
    $Num_Own_Reports = 0;
    $Num_Group_Reports = 0;
    $IdMed = $MEDID;
 
	// Get Number of Messages from patients

    $result2 = $con->prepare("SELECT * FROM message_infrastructureuser WHERE receiver_id = ? AND tofrom='to' AND status='new' ");
	$result2->bindValue(1, $MEDID, PDO::PARAM_INT);
	$result2->execute();
	
	$count2 = $result2->rowCount();
    
	$NumMessagesUser = $count2;
	if ($NumMessagesUser>0) {$VisibleUser = ''; $OpaUser='1';} else {$VisibleUser = 'hidden';$OpaUser='0.3';}
 	// Get Number of Messages from doctors
     	// Get Number of Messages from doctors
	$result2 = $con->prepare("SELECT * FROM message_infrasture WHERE receiver_id = ? AND status='new' ");
	$result2->bindValue(1, $MEDID, PDO::PARAM_INT);
	$result2->execute();
	
	$count2 = $result2->rowCount();
	//$result2 = mysql_query("SELECT * FROM message_infrasture WHERE receiver_id = '$MEDID' AND status='new' ");
	//$count2=mysql_num_rows($result2);
	$NumMessagesDr = $count2;
	if ($NumMessagesDr>0) {$VisibleDr = ''; $OpaDr='1';} else {$VisibleDr = 'hidden'; $OpaDr='0.3';}
    /*
    
    // Get Report Activity for this doctor
    // This retrieves only the last 30 days
	$MyGroup = $MedGroupId;
    $NumRepViewUser = 0;
    $NumRepViewDr = 0;
    $NumRepUpUser = 0;
    $NumRepUpDr = 0;
    $NumActiveUsers = 0;
    */
    
}
else
{
    $exit_display = new displayExitClass();

	$exit_display->displayFunction(2);
    die;
}

?>

<!DOCTYPE html>
<html lang="en" style="background: #F9F9F9;">
<head>
    <meta charset="utf-8">
    <title>Health2Me - HOME</title>
    <link rel="icon" type="image/ico" href="favicon.ico"/>
	
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

function setCookie2(name,value,days) {
//confirm('Would you like to switch languages?');
delete_cookie('lang');
    if (days) {
        var date = new Date();
        date.setTime(date.getTime()+(days*24*60*60*1000));
        var expires = "; expires="+date.toGMTString();
    }
    else var expires = "";
    document.cookie = name+"="+value+expires+"; path=/";
	
	 
}

function pageRefresh(){
location.reload();
}

//alert($.cookie('lang'));

var langType = $.cookie('lang');

if(langType == 'th'){
var language = 'th';
}else{
var language = 'en';
}

if(langType == 'th'){
setTimeout(function(){
window.lang.change('th');
lang.change('th');
//alert('th');
}, 2000);
}else if(langType == 'en'){
setTimeout(function(){
window.lang.change('en');
lang.change('en');
//alert('th');
}, 2000);
} else {
setCookie('lang', 'th', 30);
window.lang.change('th');
lang.change('th');
}

</script>
	
	
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
    
    <link href="css/bootstrap.css" rel="stylesheet">
    
    <link rel="stylesheet" href="css/jquery-ui-1.8.16.custom.css" media="screen"  />
    <link rel="stylesheet" href="css/fullcalendar.css" media="screen"  />
    <link rel="stylesheet" href="css/chosen.css" media="screen"  />
    <link rel="stylesheet" href="css/datepicker.css" >
    <link rel="stylesheet" type="text/css" href="css/jquery.timepicker.css" />
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
	<link rel="stylesheet" href="css/H2MIcons.css" />
    <link rel="stylesheet" href="css/bootstrap-responsive.css">
	<link rel="stylesheet" href="css/toggle-switch.css">
	<link rel="stylesheet" href="css/csslider.css">
    <link rel="shortcut icon" href="/images/icons/favicon.ico">
    <link rel="stylesheet" type="text/css" href="css/tooltipster.css" />
    <link href="css/style.css" rel="stylesheet">
    <link href="css/styleCol.css" rel="stylesheet">
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
	
	
    
    <!--Adobe Edge Runtime-->
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <script type="text/javascript" charset="utf-8" src="/TutorialBox/AnimationA2_edgePreload.js"></script>
    <style>
        .edgeLoad-EDGE-43 { visibility:hidden; }
    </style>
    <script type="text/javascript" charset="utf-8" src="/TutorialBox/AnimationA3_edgePreload.js"></script>
    <style>
        .edgeLoad-EDGE-44 { visibility:hidden; }
    </style>
    <script type="text/javascript" charset="utf-8" src="/TutorialBox/AnimationA4_edgePreload.js"></script>
    <style>
        .edgeLoad-EDGE-45 { visibility:hidden; }
    </style>
    <script type="text/javascript" charset="utf-8" src="/TutorialBox/AnimationA5_edgePreload.js"></script>
    <style>
        .edgeLoad-EDGE-46 { visibility:hidden; }
    </style>
    <script type="text/javascript" charset="utf-8" src="/TutorialBox/AnimationA6_edgePreload.js"></script>
    <style>
        .edgeLoad-EDGE-47 { visibility:hidden; }
    </style>
<!--Adobe Edge Runtime End-->

</head>
<body style="background: #F9F9F9;">
    <style>
        html, body { height: 100%; }
        body .modal {
          width: 70%;
          left: 15%;
          margin-left:auto;
          margin-right:auto; 
        }
        .modal-body {
            height:1000px;
        }
        .InfoRow{
            border:solid 1px #cacaca; 
            height:20px; 
            padding:5px;
            margin-left:5px;
            margin-right:5px;
            margin-top:-1px;	
        }
        .PatName{
            margin-left:10px;
            font-size:14px;
            color:#54bc00;	
        }
        body .ui-autocomplete {
          background-color:white;
          color:#22aeff;
          /* font-family to all */
        }
    </style>
    
    <!-- MODAL VIEW FOR DECK -->
    <div id="modalContents" style="display:none; text-align:center; padding:20px;">
        <div style="color:#22aeff; font-size:14px; text-align: right; width: 73%; height: 10px; float: left; padding-bottom: 10px;"> <span lang="en">Patient's Deck for Dr.</span> <?php echo $MedUserName.' '.$MedUserSurname; ?>'<?php if($MedUserSurname[(strlen($MedUserSurname) - 1)] != 's') echo 's'; ?> Office</div>
        <div style="text-align: right; width: 27%; float: left; color:#474747;" id="deck_date">
            <a href="#" class="icon-arrow-left" style="color: #22aeff" id="deck_date_left"></a>
            <span id="deck_date_val">Mar. 5</span>
            <a href="#" class="icon-arrow-right" style="color: #22aeff" id="deck_date_right"></a>
        </div>
        <div id="DeckContainer" style="border:solid 1px #cacaca; height:300px; margin-top:30px; padding-top:5px; overflow:auto;">
        </div>
        <div style="border:solid 1px #cacaca; height:70px; margin-top:20px; padding:10px;">
            <input id="field_id" type="text" style="width: 40px; margin-top:5px; visibility:hidden;"><input id="PatientSBox" type="text" style="width: 200px; float:left;" placeholder="Enter Patient's Name"><span id="results_count"></span>
            <div style="width:100%;"></div>
            <div style="float:left; border:solid 1px #cacaca; padding:3px; margin-top: -10px; width: 60px; height: 30px; ">
                <i id="IconSch" data-toggle="tooltip" class="icon-time icon-2x" style="float:left; margin-left:3px; color:#cacaca;" title="Title"></i> 
                <i id="IconSur" data-toggle="tooltip" class="icon-pencil icon-2x" style="float:left; margin-left:3px; color:#cacaca;" title="Title"></i> 
            </div>
            <div style="float:left; border:solid 1px #cacaca; padding:3px; margin-top: -10px; width: 30px; height: 30px; margin-left:10px; ">
                <i id="IconAle" data-toggle="tooltip" class="icon-check-sign icon-2x" style="float:left; margin-left:3px; color:#cacaca;" title="Title"></i> 
            </div>
            <input id="TimePat" type="text" style="width: 70px;float: left;margin-left: 10px; margin-top: -5px; text-align:center;" placeholder="Time" />
            <a id="ButtonAddDeck" class="btn" style="height: 50px; width:30px; color:#22aeff; float:right; margin-top:-40px;">Add</a>
        </div>
    </div>
    <!-- END MODAL VIEW FOR DECK -->
    
    <div class="loader_spinner"></div>

    <input type="hidden" id="NombreEnt" value="<?php echo $NombreEnt; ?>">
    <input type="hidden" id="PasswordEnt" value="<?php if(isset($PasswordEnt)) echo $PasswordEnt; ?>">
    <input type="hidden" id="UserHidden">

	<!-- HEADER VIEW FOR MAIN TOOLBAR -->
	<div class="header" >
     	<input type="hidden" id="USERDID" Value="<?php if(isset($USERID)) echo $USERID; ?>">	
    	<input type="hidden" id="MEDID" Value="<?php echo $MedID; ?>">	
    	<input type="hidden" id="GROUPID" Value="<?php echo $MedGroupId; ?>">	
    	<input type="hidden" id="IdMEDEmail" Value="<?php echo $MedUserEmail; ?>">	
    	<input type="hidden" id="IdMEDName" Value="<?php echo $MedUserName; ?>">	
    	<input type="hidden" id="IdMEDSurname" Value="<?php echo $MedUserSurname; ?>">	
    	<input type="hidden" id="IdMEDLogo" Value="<?php echo $MedUserLogo; ?>">	
  		<input type="hidden" id="TELEMED_TYPE" Value="<?php echo $telemed_type; ?>">
        <input type="hidden" id="IN_CONSULTATION" Value="<?php echo $in_consultation; ?>">
        <a href="index-col.html" class="logo"><h1>Health2me</h1></a>
		
		<div style="float:left;">
		   <a href="#en" onclick="setCookie('lang', 'en', 30); return false;"><img src="images/icons/english.png"></a>
		   </br>
			<a href="#sp" onclick="setCookie('lang', 'th', 30); return false;"><img src="images/icons/spain.png"></a>
			</div>
           
        <div class="pull-right">
            
            <div class="notifications-head">
            <!-- MESSAGES PATIENTS -->
            <div class="btn-group m_left hide-mobile">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#" id="patient_messages_button">
                    <span class="notification" style="display: none;">531</span>
                    <span class="triangle-1" style="display: none;"></span>
                    <i class="icon-user" style="-webkit-filter: hue-rotate(240deg) saturate(8.9); "></i>
                    <span class="caret"></span>
                </a>
                <div class="dropdown-menu">
                
                    <span class="triangle-2"></span>
                    <div class="ichat" id="patient_messages">
                        <div class="ichat-messages">
                            <div class="ichat-title">
                                <div class="pull-left" lang="en">Unread Member Messages</div>
                                <div class="clear"></div>
                            </div>
                            <div style="text-align: center; align: center" id="patient_messages_indicator">
                                <i class="icon-spinner icon-spin " style="margin-top: 20px; margin-bottom: 20px; margin-right: auto; margin-left: auto; color:#22aeff;"></i>
                            </div>
                            
                            <div class="imessage" id="patient_mes_1" style="display: none;">
                                <div class="iavatar"></div>
                                <div class="imes">
                                    <div class="iauthor"></div>
                                    <div class="itext" style="width: 140px; white-space: normal;"></div>
                                </div>
                                <div class="idelete"><a id="patient_mes_link_1" href="#"><span><i class="icon-arrow-right"></i></span></a></div>
                                <div class="clear"></div>
                            </div>
                            <div class="imessage" id="patient_mes_2" style="display: none;">
                                <div class="iavatar"></div>
                                <div class="imes">
                                    <div class="iauthor"></div>
                                    <div class="itext" style="width: 140px; white-space: normal;"></div>
                                </div>
                                <div class="idelete"><a id="patient_mes_link_2" href="#"><span><i class="icon-arrow-right"></i></span></a></div>
                                <div class="clear"></div>
                            </div>
                            <div class="imessage" id="patient_mes_3" style="display: none;">
                                <div class="iavatar"></div>
                                <div class="imes">
                                    <div class="iauthor"></div>
                                    <div class="itext" style="width: 140px; white-space: normal;"></div>
                                </div>
                                <div class="idelete"><a id="patient_mes_link_3" href="#"><span><i class="icon-arrow-right"></i></span></a></div>
                                <div class="clear"></div>
                            </div>
                            <div class="imessage" id="patient_mes_4" style="display: none;">
                                <div class="iavatar"></div>
                                <div class="imes">
                                    <div class="iauthor"></div>
                                    <div class="itext" style="width: 140px; white-space: normal;"></div>
                                </div>
                                <div class="idelete"><a id="patient_mes_link_4" href="#"><span><i class="icon-arrow-right"></i></span></a></div>
                                <div class="clear"></div>
                            </div>
                            <div class="imessage" id="patient_mes_5" style="display: none;">
                                <div class="iavatar"></div>
                                <div class="imes">
                                    <div class="iauthor"></div>
                                    <div class="itext" style="width: 140px; white-space: normal;"></div>
                                </div>
                               <div class="idelete"><a id="patient_mes_link_5" href="#"><span><i class="icon-arrow-right"></i></span></a></div>
                                <div class="clear"></div>
                            </div>
                            <div class="ichat-link" style="text-align: center;">
                                <p id="patient_more_messages">0 <span lang="en">More Messages</span></p> 
                                <div class="clear"></div>
                            </div>
                        
                        </div>
                        <a href="Messages.php" class="iview" style="width: 100%" lang="en">View all</a>
                   
                    </div>
                
                </div>
            </div>
            <!-- END MESSAGES PATIENTS -->
            
            <!-- MESSAGES DOCTORS -->
            <div class="btn-group pull-left hide-mobile">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#" id="doctor_messages_button">
                    <span class="notification" style="display: none;">77</span>
                    <span class="triangle-1" style="display: none;"></span>
                    <i class="icon-user-md" style="-webkit-filter: hue-rotate(340deg) saturate(8.9);" ></i>
                    <span class="caret"></span>
                </a>
                <div class="dropdown-menu">
                
                    <span class="triangle-2"></span>
                    <div class="ichat" id="doctor_messages">
                        <div class="ichat-messages">
                            <div class="ichat-title">
                                <div class="pull-left" lang="en">Unread Doctor Messages</div>
                                <div class="clear"></div>
                            </div>
                            
                            <div style="text-align: center; align: center" id="doctor_messages_indicator">
                                <i class="icon-spinner icon-spin " style="margin-top: 20px; margin-bottom: 20px; margin-right: auto; margin-left: auto; color:#22aeff;"></i>
                            </div>
                            
                            <div class="imessage" id="doctor_mes_1" style="display: none;">
                                <div class="iavatar"></div>
                                <div class="imes">
                                    <div class="iauthor"></div>
                                    <div class="itext" style="width: 140px; white-space: normal;"></div>
                                </div>
                                <div class="idelete"><a id="doctor_mes_link_1" href="#"><span><i class="icon-arrow-right"></i></span></a></div>
                                <div class="clear"></div>
                            </div>
                            <div class="imessage" id="doctor_mes_2" style="display: none;">
                                <div class="iavatar"></div>
                                <div class="imes">
                                    <div class="iauthor"></div>
                                    <div class="itext" style="width: 140px; white-space: normal;"></div>
                                </div>
                                <div class="idelete"><a id="doctor_mes_link_2" href="#"><span><i class="icon-arrow-right"></i></span></a></div>
                                <div class="clear"></div>
                            </div>
                            <div class="imessage" id="doctor_mes_3" style="display: none;">
                                <div class="iavatar"></div>
                                <div class="imes">
                                    <div class="iauthor"></div>
                                    <div class="itext" style="width: 140px; white-space: normal;"></div>
                                </div>
                                <div class="idelete"><a id="doctor_mes_link_3" href="#"><span><i class="icon-arrow-right"></i></span></a></div>
                                <div class="clear"></div>
                            </div>
                            <div class="imessage" id="doctor_mes_4" style="display: none;">
                                <div class="iavatar"></div>
                                <div class="imes">
                                    <div class="iauthor"></div>
                                    <div class="itext" style="width: 140px; white-space: normal;"></div>
                                </div>
                                <div class="idelete"><a id="doctor_mes_link_4" href="#"><span><i class="icon-arrow-right"></i></span></a></div>
                                <div class="clear"></div>
                            </div>
                            <div class="imessage" id="doctor_mes_5" style="display: none;">
                                <div class="iavatar"></div>
                                <div class="imes">
                                    <div class="iauthor"></div>
                                    <div class="itext" style="width: 140px; white-space: normal;"></div>
                                </div>
                                <div class="idelete"><a id="doctor_mes_link_5" href="#"><span><i class="icon-arrow-right"></i></span></a></div>
                                <div class="clear"></div>
                            </div>
                            <div class="ichat-link" style="text-align: center;">
                                <p id="doctor_more_messages">0 <span lang="en">More Messages</span></p> 
                                <div class="clear"></div>
                            </div>
                        
                        </div>
                        <a href="Messages.php?isDoctors=1" class="iview" style="width: 100%" lang="en">View all</a>
                   
                    </div>
                
                </div>
            </div>
            <!-- END MESSAGES DOCTORS -->
            </div>
            
            <!--Button User Start-->
            <div class="btn-group pull-right" >
            <a class="btn btn-profile dropdown-toggle" id="button-profile" data-toggle="dropdown" href="#">
			<?php
			$current_encoding = mb_detect_encoding($MedUserName, 'auto');
			$show_text = iconv($current_encoding, 'ISO-8859-1', $MedUserName);

			$current_encoding = mb_detect_encoding($MedUserSurname, 'auto');
			$show_text2 = iconv($current_encoding, 'ISO-8859-1', $MedUserSurname); 
			?>
                <span class="name-user"><strong lang="en">Welcome</strong> Dr. <?php echo $show_text.' '.$show_text2; ?></span> 
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
                        <li>
                            <?php 
                                if ($privilege==1) echo '<a href="dashboard.php">';
                                else if($privilege==2) echo '<a href="patients.php">';
                                else if($privilege==3) echo '<a href="MainDashboard.php">';    
                                    
                            ?>
                        <i class="icon-globe"></i> <span lang="en">Home</span></a></li>
                    
                        <li><a href="medicalConfiguration.php" lang="en"><i class="icon-cog"></i> Settings</a></li>
                        <li><a href="logout.php" lang="en"><i class="icon-off"></i> Sign Out</a></li>
                    </ul>
                </div>
            </div>
            <!--Button User END-->  
          
        </div>
    </div>
    <!-- END HEADER VIEW FOR MAIN TOOLBAR -->
    
    
    <div id="content" style="background: #F9F9F9; padding-left:0px;">

    <!-- Button trigger modal -->
    <button id="LaunchModal" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#myModal" style="display:none;" lang="en">Launch demo modal</button>
     	    
	 <!-- MAIN TOOLBAR -->
     <div class="speedbar">
        <div class="speedbar-content">
            <ul class="menu-speedbar">
                <li><a href="MainDashboard.php" class="act_link" lang="en">Home</a></li>
                <?php 
                    if ($privilege != 3)
                    {
                        echo '<li><a href="dashboard.php"  lang="en">Dashboard</a></li>';
                    }  
                ?>
                <li><a href="patients.php"  lang="en">Members</a></li>
                <?php 
                    if ($privilege==1)
                    {
                        echo '<li><a href="medicalConnections.php"  lang="en">Doctor Connections</a></li>';
                        echo '<li><a href="PatientNetwork.php"  lang="en">Patient Network</a></li>';
                    }
                ?>
                <li><a href="medicalConfiguration.php" lang="en">Configuration</a></li>
                <li><a href="logout.php" style="color:#d9d907;" lang="en">Sign Out</a></li>
            </ul>

     
        </div>
    </div>
    <!-- END MAIN TOOLBAR -->
        
    <style>
            
        div.H2MBox{
            float:left; 
            margin-left:30px; 
            width:250px; 
            height:400px; 
            border:1px solid #b0b0b0; 
            background-color:white;
        }
        div.H2MBox:hover 
            .H2MTextFooter{
                background-color:#e8e8e8;
                color:black;       
        }
        div.H2MBox:hover 
          .H2MTitleA{
                color:black;       
        }
            
        div.H2MTitle{
            width:100%; 
            height:20%; 
            display:table;
        }
    
        div.H2MTitleA{
            padding:10px; 
            font-size:30px; 
            text-align:left; 
            color:white; 
            display:table-cell; 
            vertical-align:middle;
            padding-left:30px;
            width:50%;    
        }
    
            
        div.H2MTitleB{
            padding:10px; 
            font-size:30px; 
            text-align:right; 
            color:white; 
            display:table-cell; 
            vertical-align:middle;
        }
    
        div.H2MSet1{
            width:100%; 
            height:15%;
            color:inherit;    
        }
        
        div.H2MSet1A{
            opacity:1.0; 
            padding:10px; 
            padding-top:20px; 
            font-size:14px; 
            text-align:left; 
            color:inherit;
            display:table-cell; 
            vertical-align:middle;
        }
            
        span.H2MBigNumber{
            font-size:20px; 
            margin-left:10px; 
            margin-right:-10px;
            color:inherit;
            font-weight:bold;    
        }
            
        div.H2MTextContent{
            opacity:1.0; 
            padding:10px; 
            padding-top:20px; 
            font-size:16px; 
            text-align:left; 
            color:grey; 
            display:table-cell; 
            vertical-align:middle;
        }
    
        div.H2MFooter{
            display:table; 
            width:100%; 
            height:20%; 
        }
    
    
        div.H2MTextFooter{
            padding:10px; 
            font-size:25px; 
            font-style:italic; 
            font-weight:bold;
            text-align:center; 
            color:grey; 
            display:table-cell; 
            vertical-align:middle;
        }
    </style>
    <input type="hidden" id="Privilege" value="<?php echo $privilege; ?>">
        
    <!-- MAIN CONTENT -->
     <div class="content">
    <!-- MAIN GRID -->
     <div class="grid" class="grid span4" style="width:1000px; margin: 0 auto; margin-top:30px; padding-top:30px;">
         <!-- PART A -->
         <div class="grid" class="grid span4" style="height:80px; margin:0px auto; margin-top:-20px; margin-left:10px; margin-right:10px; padding:20px;">
            <div id="DivAv" style="float:left; width:60px; height:100%; ">
                <img src="<?php echo $avat; ?>" style="width:50px; margin:0px; float:left; font-size:18px;  border:1px solid #b0b0b0;"/>
            </div>   
            <div style="float:left; width:360px; height:100%; ">
            <div id="NombreComp" style="width:100%; color: rgba(34,174,255,1); font: bold 22px Arial, Helvetica, sans-serif; cursor: auto;"><?php echo $MedUserTitle.' '.$show_text;?> <?php echo $show_text2;?></div>
                <span id="NombreComp" style="color: rgba(84,188,0,1); font: bold 16px Arial, Helvetica, sans-serif; cursor: auto; margin-top:-5px;"><span lang="en">CENTER:</span>  <?php if ($MedGroupName<' ') echo 'single license'; else echo $MedGroupName ;?></span>
 
                <div style="width:100%; margin-top:5px; "></div>
                <?php 
                if ($privilege==1)
                {
                    echo '<div style="float:left; width:150px; height:20px; text-align:center; border:1px solid darkgrey; border-radius:5px;display:table;margin:0px;background-color: silver;">';
                    echo '<span id="TypeAccount" style="color: white; font: bold 12px Arial, Helvetica, sans-serif; cursor: auto; display:table-cell; vertical-align:middle; padding-top:1px;">PREMIUM ACCOUNT</span>';
                    echo '</div>';
                    $VoidLink = '';
                }
                ?>
                <?php if ($privilege==2)
                {
                    echo '<div style="float:left;  width:150px; height:20px; text-align:center; border:1px solid goldenrod; border-radius:5px;display:table;margin:0px;background-color: gold; margin-right:20px;">';
                    echo '<span id="TypeAccount" style="color: grey; font: bold 12px Arial, Helvetica, sans-serif; cursor: auto; display:table-cell; vertical-align:middle; padding-top:1px;">FREE ACCOUNT</span>';
                    echo '</div>';
                    echo '<div style=" float:left; margin-left:20px;  width:100px; height:20px; text-align:center; border:1px solid goldenrod; border-radius:5px; display:table; margin:0px;background-color: gold;">';
                    echo '<span id="TypeAccount" style="color: grey; font: bold 12px Arial, Helvetica, sans-serif; cursor: auto; display:table-cell; vertical-align:middle; padding-top:1px;">Upgrade</span>';
                    echo '</div>';
                    $VoidLink = '1';
                }
                ?>

            </div>  
        <div style="display:none; margin-right:10px; min-width: 70px;">
            <div style="width:100%; height:90px; margin-top:0px;">
                <a  href="dropzone.php" class="btn" title="Records" style="text-align:center; padding:15px; width:85px; height:57px; color:#22aeff; margin-left:5px; float:left;">
                    <i class="icon-gears icon-2x" style="margin:0 auto; padding:0px; color:#54bc00;"></i>
                    <div style="width:100%;"></div>
                    <span style="font-size:12px;" lang="en">Create Member</span>
                </a>
    
            </div>
        </div> 
           <div id="EngaBOX" style="display:inline-block; vertical-align:top; width:250px;float:right; margin-right:15px;">
            <div style="background-color:#549500; height:30px; width:100%;">
                <p style="text-align:center; color:white; font-size:20px; font-weight:bold; font-style:italic; padding:5px;" lang="en">Members</p>
            </div>
            <div id="EngageSCROLL" style="height:60px; overflow:hidden;">		             	
                <div style="background-color:#54bc00; height:60px; width:100%;">
                    <div style="float:left; background-color:#54bc00; height:60px; width:45%; display:table; text-align:center;">
                        <div style="display:table-cell; vertical-align:middle;">
                            <p id="Engage1" style="color:white; font-size:35px; font-weight:bold; text-align:right; margin-right:5px; padding-top:8px;">
                                <i class="icon-spinner icon-spin " id="H2M_Spin" style="margin:0 auto; color:white;"></i>
                            </p>
                        </div>	
                    </div>
                    <div style="float:left; background-color:#54bc00; height:60px; width:55%; display:table; text-align:center;">
                        <div style="display:table-cell; vertical-align:middle;">
                            <p style="color:white; font-weight:bold; text-align:left; line-height:100%; width:90%; padding-top:8px;">
                                <span id="Engage1T1" style="width:200px; font-size:16px; font-weight:normal;"></span><br/>
                                <span id="Engage1T2" style="text-align:center; width:100%; font-size:24px; font-weight:bold;"></span>
                            </p>
                        </div>
                    </div>
                    
                </div>	 	 	
            </div> 	
        </div>
            <div style="display:none; float:left; width:200px; height:100%;">
             <?php
                if ($privilege != 3) {
             ?>     
                <span id="NumMsgUser<?php echo $VisibleUser?><?php echo $VoidLink?>" style="color:rgba(105,188,0,<?php echo $OpaUser?>); margin-left:10px;" title="Doctors uploaded information in the past 30 days"><i class="icon-envelope icon-3x" style="cursor: pointer;" id="PatientMessagesButton"></i></span>
            
                <span style="visibility:<?php echo $VisibleUser?>; background-color:black;" class="H2MBaloon" ><?php echo $NumMessagesUser?></span>
               
                <span  id="NumMsgDr<?php echo $VisibleDr?><?php echo $VoidLink?>" style="color:rgba(90,170,255,<?php echo $OpaDr;?>); margin-left:10px;" title="Doctors uploaded information in the past 30 days"><i class="icon-envelope icon-3x" style="cursor: pointer;" id="DoctorMessagesButton"></i></span>
                <span style="visibility:<?php echo $VisibleDr?>; background-color:red;" class="H2MBaloon" ><?php echo $NumMessagesDr?></span>
               
           <?php 
                } 
            ?>             
							
            </div>
            <div style="float:left; width:300px; height:100%; ">
                <style>
                    div.H2MExBox{
                        width:200px; 
                        height:50px; 
                        border:1px solid #cacaca; 
                        border-radius:5px; 
                        display:table;
                        margin:10px;
                        background-color: rgb(34,174,255);
                        opacity: 0.1;
                    }
                    
                    div.H2MInText{
                        padding:0px; 
                        font-size:16px; 
                        text-align:left; 
                        color:grey; 
                        display:table-cell; 
                        vertical-align:middle;
                        padding-left:30px;
                        width:50%;  
                          
                        }
                    
                    div.H2MTray{
                        width:300px; 
                        height:50px; 
                        border:1px solid #cacaca; 
                        border-radius:5px; 
                        display:table;
                        margin:0px;
                        background-color: #F8F8F8;
                     }
                    
                    div.H2MTSCont{
                        width:300px;
                        overflow:hidden;
                    }
                    
                    div.H2MTrayScroll{
                        width:1000px;
                    }    
                    
                    tr.MsgRow:hover {
                        background-color: #f4f4f4;
                        border: 3px solid #cacaca;
                    }	         
                    
                    td.RightZone:hover {
                        background-color: black;
                        border: 1px solid #black;
                        cursor:pointer;
                    }	         
                    
                </style>
            <!-- NOTIFICATION TRAY -->
            <div class="H2MTray" style="display:none;">
                <div class="H2MTSCont">
                    <div class="H2MTrayScroll">
                        <div class="H2MInText" style="width:270px;">
                            <div style="width:40%; float:right; margin-top:10px; text-align:right; padding-right:20px;"><i class="icon-bullhorn icon-2x"></i></div>
                            <span style="font-size:10px;" lang="en">H2M messaging system</span>
                            <div style="width:50%; padding-top:-10px;"></div>
                            <span style="" lang="en">Notification tray</span>
                        </div>
                        <div id="Notif1" class="H2MInText" style="width:270px; color:#22aeff;">
                            <div id="IconText1" style="width:20%; float:right; margin-top:10px; text-align:right; padding-right:20px; "><i class="icon-folder-open-alt icon-2x"></i></div>
                            <span id="SubText1" style="font-size:10px;"> </span>
                            <div style="width:70%; padding-top:-10px; "></div>
                            <a id="MainText1" style=""><span id="" style="text-decoration:none;"></span></a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END NOTIFICATION TRAY -->

        </div>
        <style>
            .myPsClass {
                font-size:14px;
                color:blue;
            }
            .myPsClass:active {
                font-size:14px;
                color:green;
            }
            .myPsClass:hover {
                font-size:14px;
                color:grey;
            }
        </style>
             
        
        <a id="BotonRef" class="btn" title="Billing" style="color:black; margin-right:20px;  float:right; visibility:hidden;" lang="en"><i class="icon-plus"></i>Patients In</a>
        
    </div>
    <!-- END PART A -->
         
         <!-- PART F -->
   
         
    <div class="grid" class="grid span4" style="margin:0px auto; margin-top: 10px; margin-left:10px; margin-right:10px; padding:20px; height: 40px;  display:none; background-color: #54bc00;" id="part_f">
        <div><div style="width: 78%; height: 30px; margin-top: -20px; float: right;"><a id="phone_consultation_connect" href="javascript:void(0)" class="btn" title="Acknowledge" style="width:18%; color: green; margin-bottom: 2px; float: right; padding-left: -15px;" lang="en">View Member Records</a></a><p id="phone_consultation_text" style="color: #FFF; height: 10px; font-size: 14px; margin-top: 4px;"><span lang="en">You are on a phone consultation with member</span> <?php echo $telemed_name; ?>.</p></div><input type="hidden" id="phone_consultation_id" value="<?php echo $telemed; ?>" /><input type="hidden" id="phone_consultation_name" value="<?php echo $telemed_name; ?>" /><span class="label label-info" id="EtiTML" style="float:left;background-color:#FFF; margin-top: -15px; margin-left:0px; margin-bottom:0px; font-size:16px; text-shadow:none; text-decoration:none; color:#54bc00;" lang="en">Phone Consultation</span></div>
    </div>
    <!-- END PART F -->
         
    <!-- PART E -->
    <div class="grid" class="grid span4" style="display:none; margin:0px auto; margin-top: 10px; margin-left:10px; margin-right:10px; padding:20px; padding-top: 10px; height: 30px; display: none; background-color: #54bc00;" id="part_e">
        <div style="margin-top:10px;"><div style="width: 78%; height: 30px; margin-top: -6px; float: right;"><a id="telemed_deny_button" href="javascript:void(0)" class="btn" title="Acknowledge" style="width:2%; color: red; margin-bottom: 2px;margin-left: 2%; float: right; padding-left: -15px;"><i id="' + items[i].ID + '" class="icon-remove"></i></a><a id="telemed_connect_button" href="javascript:void(0)" class="btn" title="Acknowledge" style="width:2%; color: green; margin-bottom: 2px; float: right; padding-left: -15px;"><i id="' + items[i].ID + '" class="icon-facetime-video"></i></a><p id="video_consultation_text" style="color: #FFF; height: 10px; font-size: 14px; margin-top: 4px;" lang="en">Member Jane Doe is calling you for a video consultation </p></div><input type="hidden" id="video_consultation_id" value="<?php echo $telemed; ?>" /><input type="hidden" id="video_consultation_name" value="<?php echo $telemed_name; ?>" /><span class="label label-info" id="EtiTML" style="background-color:#FFF; margin:20px; margin-left:0px; margin-bottom:0px; font-size:16px; text-shadow:none; text-decoration:none; color:#54bc00;" lang="en">Video Consultation</span></div>
        
    </div>
    <!-- END PART E -->
         
    <!-- PART D -->
    <div class="grid" class="grid span4" style="display:none; margin:0px auto; margin-top: 10px; margin-left:10px; margin-right:10px; padding:20px; padding-top: 10px; display: none;" id="part_d">
        <div style="margin-top:10px;"><span class="label label-info" id="EtiTML" style="background-color:#22aeff; margin:10px; margin-left:0px; margin-bottom:20px; font-size:16px; text-shadow:none; text-decoration:none;" lang="en">Pending Referrals</span></div>
        <table style="width: 100%; background-color: #FFFFFF;" id="pending_referrals">
        </table>
    </div>
    <!-- END PART D -->
         
      <!-- Start of Pending Review-->
    <div class="grid" class="grid span4" style="display:none; margin:0px auto; margin-top: 10px; margin-left:10px; margin-right:10px; padding:20px; padding-top: 10px; display: none;" id="pendingReview">
        <div style="margin-top:10px;"><span class="label label-info" id="EtiTML" style="background-color:#22aeff; margin:10px; margin-left:0px; margin-bottom:20px; font-size:16px; text-shadow:none; text-decoration:none;" lang="en">Pending Review</span></div>
        <table style="width: 100%; background-color: #FFFFFF;" id="pending_review">
        </table>
    </div>
    <!-- End of Pending Review -->   
         
         
    <!-- PART B -->  
    <div id="StatsNew" style="width:938px; padding:20px; margin:10px; border:1px solid #cacaca; display:none; border-radius:5px; overflow:auto;"><center>
 
        <?php  

        if ($privilege !=3 ) { ?>
        
        <div id="ConnBOX" style="width:250px; float:left; margin-right:25px; margin-left:20px;">
            <div style="background-color:#1d92d7; height:30px; width:100%;">
                <p style="color:white; font-size:20px; font-weight:bold; font-style:italic; padding-top:5px;" lang="en">Connect</p>
            </div>
            <div id="ConnectSCROLL" style="height:60px; overflow:hidden;">		             		 	
                <div style="background-color:#1d92d7; height:60px; width:100%;">
                    <div style="float:left; background-color:#22aeff; height:60px; width:45%; display:table; text-align:center;">
                        <div style="display:table-cell; vertical-align:middle;">
                            <p id="Connect2" style="color:white; font-size:35px; font-weight:bold; text-align:right; margin-right:5px; padding-top:8px;">
                                <i class="icon-spinner icon-spin" id="H2M_Spin" style="margin:0 auto; color:white;"></i>
                            </p>
                        </div>	
                    </div>
                    <div style="float:left; background-color:#22aeff; height:60px; width:55%; display:table; text-align:center;">
                        <div style="display:table-cell; vertical-align:middle;">
                            <p style="color:white; font-weight:bold; text-align:center; line-height:100%; width:90%; padding-top:8px;">
                                <span id="Connect2T1" style="text-align:center; width:200px; font-size:16px; font-weight:normal;"></span><br/>
                                <span id="Connect2T2" style="text-align:center; width:100%; font-size:14px; font-weight:bold;"></span>
                            </p>
                        </div>
                    </div>
                    <div style="float:left; background-color:#22aeff; height:60px; width:45%; display:table; text-align:center;">
                        <div style="display:table-cell; vertical-align:middle;">
                            <p id="Connect3" style="color:white; font-size:35px; font-weight:bold; text-align:right; margin-right:5px; padding-top:8px;">
                                <i class="icon-spinner icon-spin" id="H2M_Spin" style="margin:0 auto; color:white;"></i>
                            </p>
                        </div>	
                    </div>
                    <div style="float:left; background-color:#22aeff; height:60px; width:55%; display:table; text-align:center;">
                        <div style="display:table-cell; vertical-align:middle;">
                            <p style="color:white; font-weight:bold; text-align:left; line-height:100%; width:90%; padding-top:8px;">
                                <span id="Connect3T1" style="width:200px; font-size:16px; font-weight:normal;"></span><br/>
                                <span id="Connect3T2" style="width:100%; font-size:14px; font-weight:bold;"></span>
                            </p>
                        </div>
                    </div>
                </div>	 	
            </div> 	
        </div>
        <?php } ?>
        
        </center>
    </div>
    <!-- END PART B -->
    
 
    <!-- PART C -->     
    <div id="NewsArea" style="width:978px; margin:10px; border:1px solid #cacaca; padding-left:0px; padding-right:0px; display:table; border-radius:5px; border:none;">
        <div class="tabbable">
            <ul class="nav nav-tabs myPsClass">
                <!--<li class="active" style="width:120px;"><a href="#lA" data-toggle="tab"><i class="icon-info-sign" style="margin:0 auto; padding:0px; color:#54bc00; margin-right: 4px;"></i><span class="label label-success" lang="en">Information</span></a></li>-->
                <li class="active" style="width:170px;"><a href="#lB" data-toggle="tab"><i class="icon-sort-by-order" style="margin:0 auto; padding:0px; color:#22aeff;"></i> <span class="label label-info" style="-webkit-animation: glow 2s linear infinite;" lang="en">Recent EMR Activity</span></a></li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane" id="lA">
                    <div style="width:930px; height:400px; border:1 solid #cacaca; padding-left:50px;">
            
                        <div class="csslider">
                            <input type="radio" name="slides" id="slides_1" checked />
                            <input type="radio" name="slides" id="slides_2"  />
                            <input type="radio" name="slides" id="slides_3"  />
                            <input type="radio" name="slides" id="slides_4"  />
                            <input type="radio" name="slides" id="slides_5"  />
                            <input type="radio" name="slides" id="slides_6"  />
                            <ul>
                                <li><img src="/TutorialBox/H2MAnimations001.png" /></li>
                                <li><div id="Stage" class="EDGE-43"></div></li>
                                <li><div id="Stage2" class="EDGE-44"></div></li>
                                <li><div id="Stage3" class="EDGE-45"></div></li>
                                <li><div id="Stage4" class="EDGE-46"></div></li>
                                <li><div id="Stage5" class="EDGE-47"></div></li>
                            </ul>
                            <div class="arrows">
                                <label for="slides_1" id="Arr0"></label>
                                <label for="slides_2" id="Arr1"></label>
                                <label for="slides_3"></label>
                                <label for="slides_4"></label>
                                <label for="slides_5"></label>
                                <label for="slides_6"></label>
                                <label for="slides_N"></label>
                                <label for="slides_1" class="goto-first"></label>
                                <label for="slides_N" class="goto-last"></label>
                            </div>
                            <div class="navigation">
                                <div>
                                    <label for="slides_1"></label>
                                    <label for="slides_2"></label>
                                    <label for="slides_3"></label>
                                    <label for="slides_4"></label>
                                    <label for="slides_5"></label>
                                    <label for="slides_6"></label>
                                </div>
                            </div>
                        </div>
                                     
                                     
                    </div>
                </div>
                
                
		        <div class="tab-pane active" id="lB">
                    <div id="leftColumn" class="elements" style="width:40%; min-width:390px;  margin:0px; padding-left:10px; border:none; background-color:white; border-right:1px solid #cacaca; border-radius:5px; border-bottom-right-radius:0px; border-top-right-radius:0px; display:table-cell; ">
                        
        
                        <style>
                            div.BarExternal
                            {
                                width:95%; 
                                height:50px; 
                                margin-top:20px; 
                                background-color:white; 
                                border:1px solid #cacaca; 
                                border-radius:5px; 
                                border-bottom-left-radius:0px; 
                                border-bottom-right-radius:0px;  
                            }
                            
                            a.BarInternal{
                                float:left; 
                                width:33.33%; 
                                border:0px solid black; 
                                height:100%; 
                                text-align:center; 
                                display:table; 
                                color:#22aeff;
                                margin-left:-1px;
                                text-decoration: none;
                            }
                            
                            a.BarInternal:hover{
                                color:white; 
                                background-color:#22aeff;
                            }
                            
                            a.BarInternal:active{
                                color:white; 
                                background-color:grey;
                            }
                            
                            a.BarInternalMid{
                                float:left; 
                                width:33.33%; 
                                border:0px solid black; 
                                height:100%; 
                                text-align:center; 
                                display:table; 
                                border-left:1px solid #cacaca; 
                                border-right:1px solid #cacaca;
                                color:#22aeff;
                                text-decoration: none;
                            }
                            
                            a.BarInternalMid:hover{
                                color:white; 
                                background-color:#22aeff;
                            }
                            
                            .ColumnLabel{
                                color: #22aeff; 
                                font-size: 18px; 
                                width: 190px; 
                                margin-top: -8px; 
                                margin-bottom: 8px; 
                                text-align: right; 
                                float: left; 
                                margin-right: 15px;
                            }
                        
                        </style>
                        
                        <!-- RECENT ACTIVITY COLUMN -->
                        <center><div id="PatNewlyN" style="width:75%;">
                            <div class="BarExternal" style="text-align: center; align: center; vertical-align: middle; padding-top: 12px; height: 38px;">
                                    <h3 class="ColumnLabel" style='width:55%;' lang="en">Recent Activity</h3>
                                    <i class="icon-briefcase icon-2x" id="H2M_Spin" style="color: #22aeff; float: left;"></i>
                                    <!--<span id="BaloonActivity" style="visibility:hidden; background-color:#cacaca; float:left; margin-top:5px; margin-left:-50px; border:none;" class="H2MBaloon">2</span>-->
                            </div>
                            <div id="PatNewlyContainer_emract" style="width:95%; min-height:100px; display:table; margin-top:-1px; margin-bottom:20px; border:1px solid #cacaca; border-radius:5px; border-top-left-radius:0px; border-top-right-radius:0px;">
                                <p id="EMRActIndicator" style="color: #22aeff; font-size:35px; font-weight:bold; text-align:center; margin-top: 30px;">
                                    <i class="icon-spinner icon-spin " id="H2M_Spin" style="margin:0 auto; color: #22aeff;"></i>
                                </p>
                            </div>
                        </div></center>
                        <!-- END RECENT ACTIVITY COLUMN -->
                        
                        <!-- REFERRED IN COLUMN -->
                        
                        
                        <div id="PatNewlyN" style="display:none; width:33%; float: left">
                            <div class="BarExternal" style="text-align: center; align: center; vertical-align: middle; padding-top: 12px; height: 38px;">
                                    <h3 class="ColumnLabel" lang="en">Referred In</h3>
                                    <i id="IconRefIn"  class="icon-signin icon-2x" id="H2M_Spin"  style="color: #22aeff; float: left;"></i>
                                    <!--<span id="BaloonRefIn" style="visibility:hidden; background-color:#cacaca; float:left; margin-top:5px; margin-left:-50px; border:none;" class="H2MBaloon">2</span>-->
                            </div>
                            <div id="PatNewlyContainer_refin" style="width:95%; min-height:100px; display:table; margin-top:-1px; margin-bottom:20px; border:1px solid #cacaca; border-radius:5px; border-top-left-radius:0px; border-top-right-radius:0px;">
                                <p id="RefInIndicator" style="color: #22aeff; font-size:35px; font-weight:bold; text-align:center; margin-top: 30px;">
                                    <i class="icon-spinner icon-spin " id="H2M_Spin" style="margin:0 auto; color: #22aeff;"></i>
                                </p>
                            </div>
                        </div>
                        <!-- END REFERRED IN COLUMN -->
                        
                        <!-- REFERRED OUT COLUMN -->
                        <div id="PatNewlyN" style="display:none; width:33%; float: left">
                            <div class="BarExternal" style="text-align: center; align: center; vertical-align: middle; padding-top: 12px; height: 38px;">
                                    <h3 class="ColumnLabel" lang="en">Referred Out</h3>
                                    <i id="IconRefOut" class="icon-signout icon-2x" id="H2M_Spin" style="color: #22aeff; float: left;"></i>
                                    <!--<span id="BaloonRefOut" style="visibility:hidden; background-color:#cacaca; float:left; margin-top:5px; margin-left:-50px; border:none;" class="H2MBaloon">2</span>-->
                            </div>
                            <div id="PatNewlyContainer_refout" style="width:95%; min-height:100px; display:none; margin-top:-1px; margin-bottom:20px; border:1px solid #cacaca; border-radius:5px; border-top-left-radius:0px; border-top-right-radius:0px;">
                                    <p id="RefOutIndicator" style="color: #22aeff; font-size:35px; font-weight:bold; text-align:center; margin-top: 30px;">
                                        <i class="icon-spinner icon-spin " id="H2M_Spin" style="margin:0 auto; color: #22aeff;"></i>
                                    </p>
                            
                            </div>
                        </div>
                        <!-- REFERRED OUT COLUMN -->
                   
                        
                        
                    </div>
                </div>
                <div class="tab-pane" id="lC">
                    <p></p>
                </div>
            </div>
        </div>
				
    </div>
    <!-- END PART C -->
         
    <span style="color:grey; font-size:14px; margin:20px;">&copy 2014 Inmers. All rights reserved.</span>
         
</div>
<!-- END MAIN GRID -->
</div>
<audio>
    <source id="Beep24" src="sound/beep-24.mp3"></source>
</audio>
<!-- END MAIN CONTENT -->











<!-- JAVASCRIPT -->
<script src="js/jquery.min.js"></script>
<script src="js/jquery-ui.min.js"></script>
<script src="TypeWatch/jquery.typewatch.js"></script>
<script src="js/spin.js"></script>
<script src="realtime-notifications/lib/gritter/js/jquery.gritter.min.js"></script>
<link href="realtime-notifications/lib/gritter/css/jquery.gritter.css"rel="stylesheet" type="text/css" />
<script src="realtime-notifications/pusher.min.js"></script>
<script src="realtime-notifications/PusherNotifier.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/bootstrap-datepicker.js"></script>
<script src="js/bootstrap-colorpicker.js"></script>
<script src="js/jquery.timepicker.js"></script>
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


<script>
	  
</script>
<script type="text/javascript" >
        
$(window).load(function() {
    $(".loader_spinner").fadeOut("slow");
});
	
var Privilege = $("#Privilege").val();

var timeoutTime = 18000000;
var timeoutTimer = setTimeout(ShowTimeOutWarning, timeoutTime);

var toggleRead = 1;


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

var compA;
var compB;
var compC;
var compD;
var compE;
AdobeEdge.bootstrapCallback(function(compId) {
    compA = AdobeEdge.getComposition("EDGE-43").getStage();
    compB = AdobeEdge.getComposition("EDGE-44").getStage();
    compC = AdobeEdge.getComposition("EDGE-45").getStage();
    compD = AdobeEdge.getComposition("EDGE-46").getStage();
    compE = AdobeEdge.getComposition("EDGE-47").getStage();
});

function vc_stop(animNumber){
	switch(animNumber)
		{
			case 1: compA.stop();
					  break;	
			case 2: compB.stop();
					  break;	
			case 3: compC.stop();
					  break;	
			case 4: compD.stop();
					  break;	
			case 5: compE.stop();
					  break;	
		
		}
}
function vc_play(animNumber){
	switch(animNumber)
		{
			case 1: compA.play(0);
					  break;	
			case 2: compB.play(0);
					  break;	
			case 3: compC.play(0);
					  break;	
			case 4: compD.play(0);
					  break;	
			case 5: compE.play(0);
					  break;	
		
		}
}

function CheckTray()
{
    var queMED = $("#MEDID").val();
    
    var url = 'CheckTray.php?doctor_id='+queMED;
    //var cadenaGUD = '<?php echo $domain;?>/GetMedCreator.php?UserId='+UserId;
    var cadenaGUD = url;
    $.ajax(
           {
           url: cadenaGUD,
           dataType: "json",
           async: false,
           success: function(data)
           {
           //alert ('success');
           tray = data.items;
           }
           });

    Pa = tray.length;
   
    if (Pa > 0)
    {
        RecTipo = tray[0].TimeDif;
        if ((RecTipo/60) < 4300) //That's 3 days
        {
            SubText = tray[0].SubText;
            MainText = tray[0].MainText;
            IconText = tray[0].IconText;
            MColor = tray[0].MColor;
            MLink = tray[0].MLink;
            newcolor = '#'.MColor;
            newlink = 'patientdetailMED-new.php?IdUsu='+MLink;
            newicon = '<i class="'+IconText+' icon-2x"></i>';
            newmaintext = '<span id="" style="text-decoration:none;">'+MainText+'</span></a>';

            
            $('#SubText1').html(SubText);
            $('#MainText1').html(newmaintext);
            $('#IconText1').html(newicon);
            $('#Notif1').css('color',newcolor);
            $('#Notif1').attr('href',newlink);
            $('#MainText1').attr('href',newlink);

            SendTray(1);			
        }
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
        complete: function(){},
        success: function(data) 
        {
            if (typeof data == "string") 
            {
                RecTipo = data;
            }
        }
    });
    return RecTipo;
}    

function SendTray(order)
{
    var audio = document.getElementsByTagName("audio")[0];
    audio.play();
    
    var n=0;
    function loop() {
        $('.H2MInText').css({opacity: 1.0});
        $('.H2MInText').animate({opacity: 0.2}, 400, function() {
            if (n<10)
                {
                    loop();
                    n++;
                }
            else
                {
                    var audio = document.getElementsByTagName("audio")[0];
                    audio.play();
                    $('.H2MInText').css({opacity: 1.0});
                    $(".H2MTSCont").animate({ scrollLeft: 300}, 1475);
                }	
        });
    }
    loop();
};

function urlExists(testUrl) {
     var http = jQuery.ajax({
        type:"HEAD",
        url: testUrl,
        async: false
      })
      return http.status;
          // this will return 200 on success, and 0 or negative value on error
};


function BeauStr (EString)
{
    var FirstChar = EString.substring(0,1).toUpperCase();
    var ELen = EString.length;
    var RemainChar = EString.substring(1, ELen).toLowerCase();
    var MExit = FirstChar + RemainChar;
    return MExit;
}

var PatList = [{"id": "1", "value": "Afghanistan", "label": "Afghanistan"}];

GetValidPatients();
	
function GetValidPatients()
{
    var queMED = $("#MEDID").val();
    
    var queUrl ='getValidPatients.php?Doctor='+queMED+'&NReports=3';		
        
    $.ajax(
    {
        url: queUrl,
        dataType: "json",
        async: false,
        success: function(data)
        {
            ValPatData = data.items;
        }
    });
    
    Pa = ValPatData.length;

    PatList = ValPatData;
    ValPatData.sort( function(a,b){ return a.email - b.email } );
    
}
	
var VPatType = 0;
var VPatAle = 0;
var VPatId = 0;
var VPatName = '';
var VPatTime = '';

var AnimSelected = 0;

$(document).ready(function() 
{
    var telemed_pat = -1;
    
    var pusher = new Pusher('d869a07d8f17a76448ed');
    var channel_name=$('#MEDID').val();
    var channel = pusher.subscribe(channel_name);
    var notifier=new PusherNotifier(channel);

    channel.bind('telemed_video_call', function(data) 
    {
        var d = JSON.parse(data);
        telemed_pat = d.id;
		
        $("#video_consultation_text").text("Member " + d.name + " is calling you for a video consultation");
        $("#part_e").slideDown();
        telemed_pat = $("#video_consultation_id").val();
    });
    
    if($("#TELEMED_TYPE").val() == '1' && $("#IN_CONSULTATION").val() == '1')
    {
		var translation = '';
		
		if(language == 'th'){
		translation = 'Está en una sesión telefónica con un miembro';
		}else if(language == 'en'){
		translation = 'You are on a phone consultation with member';
		}
		
        $("#phone_consultation_text").text(translation+" " + $("#phone_consultation_name").val() + ".");
        $("#part_f").css("display", "block");
        telemed_pat = $("#phone_consultation_id").val();
    }
    $("#phone_consultation_connect").live('click', function()
    {
        window.location = 'patientdetailMED-new.php?IdUsu='+telemed_pat+"&TELEMED=2";
    });
    
    if($("#TELEMED_TYPE").val() == '2' && $("#IN_CONSULTATION").val() == '1')
    {
        $("#video_consultation_text").text("Member " + $("#video_consultation_name").val() + " is calling you for a video consultation.");
        $("#part_e").css("display", "block");
        telemed_pat = $("#video_consultation_id").val();
    }
    $('#telemed_connect_button').live('click',function()
    {
        //$.post("handle_telemed_connection.php", {med_id: $("#MEDID").val(), accepted: 1}, function(data, status){});
        //window.open("telemedicine_doctor.php?MED=" + $("#MEDID").val() + "&PAT=" + telemed_pat,"Telemedicine","height=540,width=1000,left=100,top=100,resizable=yes,scrollbars=yes,toolbar=yes,menubar=no,location=no,directories=no,status=yes");
        window.location = 'patientdetailMED-new.php?IdUsu='+telemed_pat+"&TELEMED=1";
    });
    $('#telemed_deny_button').live('click',function()
    {
        //$.post("handle_telemed_connection.php", {med_id: $("#MEDID").val(), accepted: 0}, function(data, status){});
        $("#part_e").slideUp();
    });
    
    $('#TimePat').timepicker({ 'scrollDefaultNow': true });
    var toLoad = new Array();
    var nextToLoad = -1;
    toLoad[0] = 0;
    toLoad[1] = 1;
    toLoad[2] = 2;
    toLoad[3] = 3;
    toLoad[4] = 4;
    toLoad[5] = 5;
    toLoad[6] = 6;
    toLoad[7] = 7;
    toLoad[8] = 8;
    toLoad[9] = 9;
    toLoad[10] = -1;
    
	$('input[type=radio][id^="slides_"]').live('click',function() {
		var getidstr=$(this).attr("id");
		var id=parseInt(getidstr.substr(7,8))  //This give the id number
	    vc_play(id-1);
	    vc_stop(id);
	    vc_stop(id-2);	    
//	    alert (id-1+' stopping '+(id-2)+' and '+(id));
	});
    
    function array_rem(arr, val)
    {
        for(var i = 0; i < arr.length; i++)
        {
            if (arr[i] == val)
            {
                arr.splice(i, 1);
                break;
            }
        }
    }
    function load_next()
    {
        var t = -1;
        if (nextToLoad >= 0)
        {
            t = nextToLoad;
            nextToLoad = -1;
            array_rem(toLoad, t);
        }
        else
        {
            t = toLoad[0];
            array_rem(toLoad, t);
        }
        switch(t)
        {
            case (0):
                FillConnect();
                break;
            case (1):
                FillEngage();
                break;
            case (2):
                GetActivity();
                break;
            case (3):
                GetReferred('in');
                break;
            case (4):
                GetReferred('out');
                break;
            case (5):
                GetTimeLine(0, 'doctor');
                break;
            case (6):
                GetTimeLine(0, 'patient');
                break;
            case (7):
                GetPendingReferrals();
                break;
            case (8):
                GetDeck(deck_date);
                break;
            case (9):
                EchoPendingReviews();
                break;
            default:
                ;
        }
    }
    $('#Group_toggle').click(function(event) 
    {
        toLoad[0] = 0;
        toLoad[1] = 1;
        toLoad[2] = 2;
        toLoad[3] = 3;
        toLoad[4] = 4;
        toLoad[5] = -1;
        toLoad[6] = -1;
        toLoad[7] = -1;
        toLoad[8] = -1;
        toLoad[9] = -1;
        $('#Connect2').html('<i class="icon-spinner icon-spin" id="H2M_Spin" style="margin:0 auto; color:white;"></i>');
        $('#Connect2T1').html('');
        $('#Connect2T2').html('');
        $('#Connect3').html('<i class="icon-spinner icon-spin" id="H2M_Spin" style="margin:0 auto; color:white;"></i>');
        $('#Connect3T1').html('');
        $('#Connect3T2').html('');
        
        $('#Engage1').html('<i class="icon-spinner icon-spin" id="H2M_Spin" style="margin:0 auto; color:white;"></i>');
        $('#Engage1T1').html('');
        $('#Engage1T2').html('');
        $('#Engage2').html('<i class="icon-spinner icon-spin" id="H2M_Spin" style="margin:0 auto; color:white;"></i>');
        $('#Engage2T1').html('');
        $('#Engage2T2').html('');
        
        $("#PatNewlyContainer_emract").html('<p id="EMRActIndicator" style="color: #22aeff; font-size:35px; font-weight:bold; text-align:center; margin-top: 30px;"><i class="icon-spinner icon-spin " id="H2M_Spin" style="margin:0 auto; color: #22aeff;"></i></p>');
        
        $("#PatNewlyContainer_refin").html('<p id="RefInIndicator" style="color: #22aeff; font-size:35px; font-weight:bold; text-align:center; margin-top: 30px;"><i class="icon-spinner icon-spin " id="H2M_Spin" style="margin:0 auto; color: #22aeff;"></i></p>');
        $("#PatNewlyContainer_refout").html('<p id="RefOutIndicator" style="color: #22aeff; font-size:35px; font-weight:bold; text-align:center; margin-top: 30px;"><i class="icon-spinner icon-spin " id="H2M_Spin" style="margin:0 auto; color: #22aeff;"></i></p>');
        
        nextToLoad = -1;
        load_next();
    });
    
    
	//GetDeck();
    var deck_date = new Date();
    var today = deck_date;
	$('#ButtonDeck').live('click',function()
    {
        deck_date = new Date();
        $('#deck_date_val').text(get_mon(deck_date.getMonth()) + ' ' + deck_date.getDate());
		GetDeck(deck_date);
		$("#modalContents").dialog({bgiframe: true, width: 550, height: 550, modal: false});
	});
    $('#deck_date_right').live('click',function()
    {
        deck_date.setDate(deck_date.getDate() + 1);
        $('#deck_date_val').text(get_mon(deck_date.getMonth()) + ' ' + deck_date.getDate());
        GetDeck(deck_date);
    });
    $('#deck_date_left').live('click',function()
    {
        deck_date.setDate(deck_date.getDate() - 1);
        $('#deck_date_val').text(get_mon(deck_date.getMonth()) + ' ' + deck_date.getDate());
        GetDeck(deck_date);
    });
    function get_mon(val)
    {
        switch(val)
        {
            case 0:
                return 'Jan.';
                break;
            case 1:
                return 'Feb.';
                break;
            case 2:
                return 'Mar.';
                break;
            case 3:
                return 'Apr.';
                break;
            case 4:
                return 'May';
                break;
            case 5:
                return 'Jun.';
                break;
            case 6:
                return 'Jul.';
                break;
            case 7:
                return 'Aug.';
                break;
            case 8:
                return 'Sep.';
                break;
            case 9:
                return 'Oct.';
                break;
            case 10:
                return 'Nov.';
                break;
            case 11:
                return 'Dec.';
                break;
            default:
                ;
        };
    }

    $("#PatientSBox").autocomplete({
        source: PatList,
        minLength: 1,
        select: function(event, ui) {
            // feed hidden id field
            $("#field_id").val(ui.item.id);
            // update number of returned rows
            $('#results_count').html('');
            VPatName =  ui.item.label;
            VPatId =  $("#field_id").val();
        },
        open: function(event, ui) {
            // update number of returned rows
            var len = $('.ui-autocomplete > li').length;
            $('#results_count').html('(#' + len + ')');
        },
        close: function(event, ui) {
            // update number of returned rows
            $('#results_count').html('');
        },
        // mustMatch implementation
        change: function (event, ui) {
            if (ui.item === null) {
                $(this).val('');
                $('#field_id').val('');
            }
        }
    });
   
    $("#PatientSBox").focusout(function() {
        if ($("#field").val() === '') {
            $('#field_id').val('');
        }
    });

    $("#IconSch").click(function() {
		$("#IconSch").css('color','#22aeff');
		$("#IconSur").css('color','#cacaca');
		VPatType = 1;
    });
    
    $("#IconSur").click(function() {
		$("#IconSur").css('color','#22aeff');
		$("#IconSch").css('color','#cacaca');
		VPatType = 2;
    });
    
    $("#IconAle").click(function() {
		if (VPatAle==0){
			VPatAle=1;
			$("#IconAle").css('color','#22aeff');
		} else {
			VPatAle=0;
			$("#IconAle").css('color','#cacaca');
		}
    });
    
    $("#TimePat").change(function() {
        var temp = $("#TimePat").val().split(":");
        
        var hr = parseInt(temp[0]);
        var min = temp[1].substr(0, temp[1].length - 2);
        var type = temp[1].substr(temp[1].length - 2, 2);
        if (type == "pm" && hr != 12)
        {
            hr += 12;
        }
        else if (type == "am" && hr == 12)
        {
            hr = 0;
        }
		VPatTime =  hr.toString() + ":" + min;
	});
	
    $("#ButtonAddDeck").click(function() {
		//alert ('Id =  '+VPatId+' Name =  '+VPatName+' Time = '+VPatTime+' Type =  '+VPatType+' Ale ='+VPatAle);
		
		var queMED = $("#MEDID").val();
        var date_str = deck_date.getFullYear()+"-";
        if ((deck_date.getMonth() + 1) < 10)
        {
            date_str += "0";
        }
        date_str += (deck_date.getMonth() + 1)+"-";
        if (deck_date.getDate() < 10)
        {
            date_str += "0";
        }
        date_str += deck_date.getDate();
		var cadena='setNewDeck.php?IdDr='+queMED+'&IdPatient='+VPatId +'&NamePatient='+VPatName+'&Type='+VPatType+'&Alert='+VPatAle+'&Time='+VPatTime+'&Date='+date_str;
		var RecTipo=LanzaAjax(cadena);

		GetDeck(deck_date);
		
	});

    $(".DeleteDeck").live('click',function(){
		var myClass = $(this).attr("id"); 
		//alert (myClass);
		var cadena='deleteDeck.php?IdDr='+queMED+'&IdPatient='+myClass;
		var RecTipo=LanzaAjax(cadena);
		GetDeck(deck_date);

	});
		
	function GetDeck(date){
		var queMED = $("#MEDID").val();
        var date_str = deck_date.getFullYear()+"-";
        if ((deck_date.getMonth() + 1) < 10)
        {
            date_str += "0";
        }
        date_str += (deck_date.getMonth() + 1)+"-";
        if (deck_date.getDate() < 10)
        {
            date_str += "0";
        }
        date_str += deck_date.getDate();
        
		var queUrl ='getDeck.php?Doctor='+queMED+'&NReports=3&Date='+date_str;		
		$.ajax(
		{
			url: queUrl,
			dataType: "json",
			async: false,
			success: function(data)
			{
				Deck = data.items;
			}
		});
		
		Pa = Deck.length;

		//PatData.sort( function(a,b){ return a[8] - b[8] } );
		Deck.sort( function(a,b){ return a.email - b.email } );
		
		var n = 0;
		var DeckBox='';
		while (n<Pa){
            var times = Deck[n].time.split(":");
            var hr = times[0];
            var min = times[1];
            var label = "am";
            if (parseInt(hr) > 12)
            {
                hr -= 12;
                label = "pm";
            }
            else if (parseInt(hr) == 12)
            {
                label = "pm";
            }
            else if (parseInt(hr) == 0)
            {
                hr = 12;
            }
            var newTime = "";
            newTime += hr.toString() + ":" + min + label;
			DeckBox += '<div class="InfoRow">';
			DeckBox += '<a href="patientdetailMED-new.php?IdUsu='+Deck[n].PatId+'"><div style="width:150px; float:left; text-align:left;"><span class="PatName">'+Deck[n].name+'</span></div></a> ';
			DeckBox += '<div style="width:130px; float:left; text-align:left; color:#22aeff; font-size:8px;"><span class="DrName">(by '+Deck[n].drname+')</span></div>';
			if (Deck[n].type=='1'){
				DeckBox += '<div style="width:20px; float:left; color:#22aeff;"><i class="icon-time"></i> </div>';
			}else{
				DeckBox += '<div style="width:20px; float:left; color:#22aeff;"><i class="icon-pencil"></i> </div>';
			}
			
			DeckBox += '<div style="width:80px; float:left;"><span >'+newTime+'</span></div>';
			if (Deck[n].alert=='1'){
				DeckBox += '<div style="width:20px; float:left; color:#22aeff;"><i class="icon-check-sign"></i> </div>';
			}else{
				DeckBox += '<div style="width:20px; float:left; color:#cacaca;"><i class="icon-check-sign"></i> </div>';
			}
			DeckBox += '<div class="DeleteDeck" id="'+Deck[n].PatId+'" style="width:60px; float:left;"><a id="'+Deck[n].PatId+'"  class="btn" style="height: 15px; padding-top: 0px; margin-top: -5px; color:red;">Del</a></div>';
			DeckBox += '</div>';
			n++;
		}
		
		$('#DeckContainer').html(DeckBox);
        if(date.getDate() == today.getDate() && date.getMonth() == today.getMonth())
        {
            $('#BaloonDeck').html(n);
            if (n>0) $('#BaloonDeck').css('visibility','visible');
            else $('#BaloonDeck').css('visibility','hidden');
        }

		load_next();
	}

	//Start of code for EchoPendingReviews which periodically checks the any mails to be review by doctor sent by patients through         Send feature of UserDashboard-new.php file
    var num_pending_review_mails = 0;
    
    function EchoPendingReviews()
    {
     
        var doctorId = $("#MEDID").val();   
        $.post("getPendingReviewMails.php", {Doctor: doctorId}, function(data, status)
		{
            var items = JSON.parse(data);
            var num = items.length;
            num_pending_review_mails = num;
            var html = '<tbody><tr style="border-bottom:1px solid #cacaca; height:20px;"></tr>';
            for(var i = 0; i < items.length; i++)
            {
                if (i == 0)
                {
                    html += '<tr style="border-bottom:1px solid #cacaca; border-top:1px solid #cacaca; height:40px;">';
                }
                else
                {
                    html += '<tr style="border-bottom:1px solid #cacaca; height:40px;">';
                }
                html += '<td style="width: 80%; padding:6px; ">';
                html += '<div style="line-height:1; font-size:15px; width:100%;">';
                html += '<span style="color: #54bc00;">' + items[i].PATIENT + '</span>';
                html += '<span style="color: #494949;"> review by </span>';
                html += '<span style="color: #22aeff;">' + items[i].DOCTOR + '</span></div></td>';
                    
                html += '<td style="width: 20%; padding:6px; ">';
                html += '<a id="pending_review_mail" href="javascript:void(0)" class="btn" title="Review" style="width:65%; color:grey; margin-bottom: 2px;">Review</a></td></tr>';
            }
            html += '</tbody>';
            $('#pending_review').html(html);
            if (num > 0)
            {
                $('#pendingReview').slideDown();
            }
            load_next();
        });
    } //End of code for EchoPendingReviews which periodically checks the any mails to be review by doctor sent by patients                through Send feature of UserDashboard-new.php file
    
    
    
    //Start of code for Review button in Pending Review division
    
    $('#pending_review_mail').live('click',function()
    {
        var doctorId = $("#MEDID").val();
        $.post("SetNewMessagesAsRead.php",{doctorID: doctorId},function(data,success)
               {
               });
                
        $("#pendingReview").slideUp();
                
          
    }); // End of code for Review button in Pending Review division
    
    function GetActivity()
	{
        var queMED = $("#MEDID").val();
        var group = 0;
        if($('#Group_toggle').is(":checked")) group=1;
        var queUrl ='getNewlyAll.php?Doctor='+queMED+'&NReports=3';		
        var PatData;
        
        $.post("getNewlyAllLight.php", {Doctor: queMED, NReports: 3, Group: group}, function(data, status)
        {
            
            console.log("In getNewlyAllLight"+data);
            $("#PatNewlyContainer_emract").show();
            $("#PatNewlyContainer_emract").html(data);
            //$("#BaloonActivity").html(ncount);
            //if (ncount>0) $("#BaloonActivity").css('visibility','visible');
            load_next();
            
        });

        
        //PatData.sort( function(a,b){ return a[8] - b[8] } );
       
			
	
	}

    
        
	function GetReferred(type)
	{
        var queMED = $("#MEDID").val();
        var group = 0;
        if($('#Group_toggle').is(":checked")) group=1;
        var queUrl ='getReferredInArrayLight.php?Doctor='+queMED+'&NReports=3&TypeEntry='+type+'&Group='+group;		
        
        $.get(queUrl, function(data, status)
        {
            if (type == 'in') 
            {
                $("#PatNewlyContainer_refin").show();
                $("#PatNewlyContainer_refin").html(data);
                //$("#BaloonRefIn").html(n);
                //if (n>0) $("#BaloonRefIn").css('visibility','visible');
            }else
            {
                $("#PatNewlyContainer_refout").show();
                $("#PatNewlyContainer_refout").html(data);
                //$("#BaloonRefOut").html(n);
                //if (n>0) $("#BaloonRefOut").css('visibility','visible');
            }
            load_next();
        });

	
	}
    
    /*
	$('#BarBtnEMRACT').live('click',function(){
		waitcontent = '<div style="width:100%; height:100%; text-align:center; display:table-cell; vertical-align:middle;"><i class="icon-spinner icon-spin icon-4x" id="H2M_Spin" style="margin:0 auto; color:#22aeff;"></i></div>';
		$("#PatNewlyContainer").html(waitcontent);			  		
		window.setTimeout(function() {
        	GetActivity();
		}, 300);
	});

	$('#BarBtnREFIN').live('click',function(){
		waitcontent = '<div style="width:100%; height:100%; text-align:center; display:table-cell; vertical-align:middle;"><i class="icon-spinner icon-spin icon-4x" id="H2M_Spin" style="margin:0 auto; color:#22aeff;"></i></div>';
		$("#PatNewlyContainer").html(waitcontent);			  		
		window.setTimeout(function() {
        	GetReferred('in');
		}, 300);
	});

	$('#BarBtnREFOUT').live('click',function(){
		waitcontent = '<div style="width:100%; height:100%; text-align:center; display:table-cell; vertical-align:middle;"><i class="icon-spinner icon-spin icon-4x" id="H2M_Spin" style="margin:0 auto; color:#22aeff;"></i></div>';
		$("#PatNewlyContainer").html(waitcontent);			  		
		window.setTimeout(function() {
        	GetReferred('out');
		}, 300);
	});
    */
	$("#PatRefIn").hide();


	waitcontent = '<div style="width:100%; height:100%; text-align:center; display:table-cell; vertical-align:middle;"><i class="icon-spinner icon-spin icon-4x" id="H2M_Spin" style="margin:0 auto; color:#22aeff;"></i></div>';
	$("#PatNewlyContainer").html(waitcontent);			  		
	load_next();

	$('#NewsComm').live('click',function(){
		GetTimeLine(toggleRead,'doctor');	
	});
	$('#EngaBOX').live('click',function(){
		FillEngage();	
	});

	var ConnectItem = 0;
    
    setInterval(function(){
		Gotoflag = 0;
	
		if (ConnectItem == 0 && Gotoflag == 0) {
			ConnectItem = 1;
			Gotoflag=1;
			$("#ConnectSCROLL").animate({ scrollTop: 60 }, { duration: 700 } );
			$("#EngageSCROLL").animate({ scrollTop: 60 }, { duration: 700 } );
		};
		if (ConnectItem == 1 && Gotoflag == 0) {
			ConnectItem = 2;
			Gotoflag=1;
			$("#ConnectSCROLL").animate({ scrollTop: 120 }, { duration: 700 } );
		};
		if (ConnectItem == 2 && Gotoflag == 0) {
			ConnectItem = 0;
			Gotoflag=1;
			$("#ConnectSCROLL").animate({ scrollTop: 0 }, { duration: 700 } );
			$("#EngageSCROLL").animate({ scrollTop: 0 }, { duration: 700 } );
		};
		
	},10000)

	function FillConnect()
	{
	
		var queMED = $("#MEDID").val();
		var queUrl ='getConnected.php?Doctor='+queMED+'&NReports=3';
        var group = 0;
        if($('#Group_toggle').is(":checked")) group=1;
		
		$.post("GetConnectedLight.php", {Doctor: queMED, NReports: 3, Group: group}, function(data, status)
		{
			var ConnData = JSON.parse(data);
            $('#Connect2').html(ConnData.IN+'/'+ConnData.OUT);
            $('#Connect2T1').html('Patients');
            $('#Connect2T2').html('IN/OUT RATIO');
            $('#Connect3').html(ConnData.DRIN+'/'+ConnData.DROUT);
            $('#Connect3T1').html('Doctors');
            $('#Connect3T2').html('IN/OUT RATIO');
            load_next();
		});
	}

	function FillEngage()
	{
	
		var queMED = $("#MEDID").val();
        var group = 0;
        if($('#Group_toggle').is(":checked")) group=1;
        $.post("GetEngagedLight.php", {Doctor: queMED, NReports: 3, Group: group}, function(data, status)
		{
            var EngData = JSON.parse(data);
            $('#Engage1').html(EngData.NPatients);
           
            $('#Engage1T2').html('TOTAL');
            $('#Engage2').html(EngData.NActive);
            $('#Engage2T2').html('ACTIVE');
            load_next();
        });

	}
    
    var num_pending_referrals = 0;
    function GetPendingReferrals()
    {
        var queMED = $("#MEDID").val();
        num_pending_referrals = 0
        $.post("getPendingReferralsLight.php", {Doctor: queMED}, function(data, status)
		{
            var items = JSON.parse(data);
            var num = items.length;
            num_pending_referrals = num;
            var html = '<tbody><tr style="border-bottom:1px solid #cacaca; height:20px;"></tr>';
            for(var i = 0; i < items.length; i++)
            {
                if (i == 0)
                {
                    html += '<tr style="border-bottom:1px solid #cacaca; border-top:1px solid #cacaca; height:40px;">';
                }
                else
                {
                    html += '<tr style="border-bottom:1px solid #cacaca; height:40px;">';
                }
                html += '<td style="width: 80%; padding:6px; ">';
                html += '<div style="line-height:1; font-size:15px; width:100%;">';
                html += '<span style="color: #54bc00;">' + items[i].PATIENT + '</span>';
                html += '<span style="color: #494949;"> from </span>';
                html += '<span style="color: #22aeff;">' + items[i].DOCTOR + '</span></div></td>';
                    
                html += '<td style="width: 20%; padding:6px; ">';
                html += '<a id="pending_referral_button" href="javascript:void(0)" class="btn" title="Acknowledge" style="width:65%; color:grey; margin-bottom: 2px;"><i id="' + items[i].ID + '" class="icon-ok"></i> Acknowledge</a></td></tr>';
            }
            html += '</tbody>';
            $('#pending_referrals').html(html);
            if (num > 0)
            {
                $('#part_d').slideDown();
            }
            load_next();
        });
    }
    
    $('#pending_referral_button').live('click',function()
    {
        var referral_id = $(this).children("i.icon-ok").attr("id");
        $.post("setReferralsLight.php", {ID: referral_id}, function(data, status)
		{
            if (data == 'success')
            {
                $(this).parent().parent().slideUp();
                num_pending_referrals--;
                if (num_pending_referrals == 0)
                {
                    $("#part_d").slideUp();
                }
            }
        });
    });

	var scrollTW = 0;
	var mesScroll = 0;
	var TotalMes = 0;
	var messCount = 0;
    
	$('#MesL').live('click',function(){
		var cadena='setMessageStatus.php?msgid='+NewMES[mesScroll].MessageID;
		var RecTipo=LanzaAjax(cadena);
		if (mesScroll>0) mesScroll--;
		$('#RCounter').html((mesScroll+1)+' of '+TotalMes);
		scrollTW = (mesScroll * 465);
		$("#TWContainer" ).animate({scrollLeft: scrollTW, opacity : 0.3 });
		$("#TWContainer" ).animate({ opacity : 1 },300);
	});
	$('#MesR').live('click',function(){
		var cadena='setMessageStatus.php?msgid='+NewMES[mesScroll].MessageID;
		var RecTipo=LanzaAjax(cadena);
		if (mesScroll < TotalMes) mesScroll++;
		$('#RCounter').html((mesScroll+1)+' of '+TotalMes);
		scrollTW = (mesScroll * 465);
		$("#TWContainer" ).animate({scrollLeft: scrollTW, opacity : 0.3 });
		$("#TWContainer" ).animate({ opacity : 1 },300);
	});
	
	$("#COthers").click(function(event) {
		 if (toggleRead == 0) toggleRead = 1; else toggleRead = 0;
		 waitcontent = '<div style="width:100%; height:100px; text-align:center;"><i class="icon-spinner icon-spin icon-4x" id="H2M_Spin" style="margin:0 auto; color:#22aeff;"></i></div>';
		 $('#MessageColumn').html(waitcontent);			  		
		 window.setTimeout(function() {
        	GetTimeLine(toggleRead,'doctor');
		}, 300);
    		
   	});

    var start = 0;
    var lastPage = 1;
    var currPage = 1;
    var numDisplay = 6;
    
    var queMED = $("#MEDID").val();
    //var totalMessages = LanzaAjax('getInboxTotalUNREAD.php?IdMED='+queMED+'&patient=-1&unread=toggleRead&scenario=doctor');
    //lastPage = Math.ceil(totalMessages/numDisplay);
    //updatePageDisplay();
            
    $("#prevMessage").click(function(event)
    {
        if(currPage > 1)
        {
            currPage--;
            start -= numDisplay;
            GetTimeLine(toggleRead, 'doctor');
            updatePageDisplay();
        }
    });
    
    $("#nextMessage").click(function(event)
    {
        if(currPage < lastPage)
        {
            currPage++;
            start += numDisplay;
            GetTimeLine(toggleRead, 'doctor');
            updatePageDisplay();
        }
    });
    
    function updatePageDisplay()
    {
        document.getElementById("pageDisplay").innerHTML = currPage + "/" + lastPage;
    } 
    
    function array_swap(arr, a, b)
    {
        var x = arr[a];
        arr[a] = arr[b];
        arr[b] = x;
    }
    
    $("#doctor_mes_link_1").click(function(event)
    {
        window.open("Messages.php?isDoctors=1&id=" + $("#doctor_mes_link_1").attr("name"),'_self',false);
        event.preventDefault();
    });
    $("#doctor_mes_link_2").click(function(event)
    {
        window.open("Messages.php?isDoctors=1&id=" + $("#doctor_mes_link_2").attr("name"),'_self',false);
        event.preventDefault();
    });
    $("#doctor_mes_link_3").click(function(event)
    {
        window.open("Messages.php?isDoctors=1&id=" + $("#doctor_mes_link_3").attr("name"),'_self',false);
        event.preventDefault();
    });
    $("#doctor_mes_link_4").click(function(event)
    {
        window.open("Messages.php?isDoctors=1&id=" + $("#doctor_mes_link_4").attr("name"),'_self',false);
        event.preventDefault();
    });
    $("#doctor_mes_link_5").click(function(event)
    {
        window.open("Messages.php?isDoctors=1&id=" + $("#doctor_mes_link_5").attr("name"),'_self',false);
        event.preventDefault();
    });
    $("#patient_mes_link_1").click(function(event)
    {
        window.open("Messages.php?isDoctors=0&id=" + $("#patient_mes_link_1").attr("name"),'_self',false);
        event.preventDefault();
    });
    $("#patient_mes_link_2").click(function(event)
    {
        window.open("Messages.php?isDoctors=0&id=" + $("#patient_mes_link_2").attr("name"),'_self',false);
        event.preventDefault();
    });
    $("#patient_mes_link_3").click(function(event)
    {
        window.open("Messages.php?isDoctors=0&id=" + $("#patient_mes_link_3").attr("name"),'_self',false);
        event.preventDefault();
    });
    $("#patient_mes_link_4").click(function(event)
    {
        window.open("Messages.php?isDoctors=0&id=" + $("#patient_mes_link_4").attr("name"),'_self',false);
        event.preventDefault();
    });
    $("#patient_mes_link_5").click(function(event)
    {
        window.open("Messages.php?isDoctors=0&id=" + $("#patient_mes_link_5").attr("name"),'_self',false);
        event.preventDefault();
    });
    function GetTimeLine(unread,scenario)
    {
        $("#WaitTW").css('visibility','visible');
        var queMED = $("#MEDID").val();
        var queUrl ='getInboxMessageLight.php?IdMED='+queMED+'&scenario='+scenario;
        var type = "doctor";
        if (scenario == "patient")
        {
            type = "patient";
        }
        $("#"+type+"_messages_indicator").css("display", "block");
        $("#"+type+"_mes_1").css("display", "none");
        $("#"+type+"_mes_2").css("display", "none");
        $("#"+type+"_mes_3").css("display", "none");
        $("#"+type+"_mes_4").css("display", "none");
        $("#"+type+"_mes_5").css("display", "none");
        $(type+"_messages_button").children("span.notification").css("display", "none");
        $(type+"_messages_button").children("span.triangle-1").css("display", "none");
        $.post("getInboxMessageLight.php", {IdMED: queMED, scenario: scenario}, function(data, status)
        {
            var items = JSON.parse(data);
            var num_items = items.length;
            
            // set indicator
            if (num_items > 0)
            {
                $("#"+type+"_messages_button").children("span.notification").css("display", "block");
                $("#"+type+"_messages_button").children("span.triangle-1").css("display", "block");
                $("#"+type+"_messages_button").children("span.notification").text(items[0].NUM);
                var more = items[0].NUM - 5;
                if (more < 0)
                {
                    more = 0;
                }
                $("#"+type+"_more_messages").text(more + " more messages ...");
            }
            else
            {
                $("#"+type+"_more_messages").text("0 more messages ...");
            }
            
            for(var k = 1; k <= 5; k++)
            {
                if (k <= num_items)
                {
                    $("#"+type+"_mes_"+k).css("display", "block");
                }
                else
                {
                    $("#"+type+"_mes_"+k).css("display", "none");
                }
            }
            for(var i = 0; i < num_items && i < 5; i++)
            {
                if(items[i])
                {
                    var isDoctor = 0;
                    if(type === 'doctor')
                    {
                        isDoctor = 1;
                    }
                    $("#"+type+"_mes_"+(i+1)).children("div.imes").children("div.iauthor").text(items[i].NAME_sender);
                    $("#"+type+"_mes_link_"+(i+1)).attr("name", items[i].ID);
                    var text = items[i].CONTENT;
                    if (text.length > 110);
                    {
                        text = text.substr(0, 106) + " ...";
                    }
                    $("#"+type+"_mes_"+(i+1)).children("div.imes").children("div.itext").text(text);
                    if (items[i].PIC.indexOf('<div') == -1)
                    {
                        $("#"+type+"_mes_"+(i+1)).children("div.iavatar").html('<img src="'+items[i].PIC+'" alt="">');
                    }
                    else
                    {
                        $("#"+type+"_mes_"+(i+1)).children("div.iavatar").html(items[i].PIC);
                    }
                }
                
            }
            $("#"+type+"_messages_indicator").css("display", "none");
            load_next();
        });
    
        
    };  
    
            
    function checkImage(src) {
        var CheckResult = 0;
        $.ajax({
            url: src,
            type: "POST",
            dataType: "image",
            success: function() {
                CheckResult = 1;
            },
            error: function(){
               /* function if image doesn't exist like hideing div or setting default image*/
              } 
        });	  
    
        return CheckResult;
    }
    
    
    function GetTimeLineBOX(unread)
    {
        $("#WaitTW").css('visibility','visible');
        var queMED = $("#MEDID").val();
        var queUrl ='getInboxMessageUNREAD.php?IdMED='+queMED+'&patient=-1'+'&unread='+unread;		
        
        //alert (queUrl);
        
        $.ajax(
        {
            url: queUrl,
            dataType: "json",
            async: false,
            success: function(data)
            {
                //alert('Data Fetched');
                NewMES = data.items;
                //NewMESArray = data.items;
            }
        });
    
        Pa = NewMES.length;
        TotalMes = Pa;
        $('#RCounter').html('1 of '+Pa);
        //alert (Pa);
        $('#NewMES').css('width',Pa*470);
        var n=0;
        MesTimeline = '';
        while (n < Pa)
        {
            var queini = NewMES[n].MessageINIT.charCodeAt(0); 
            var whatcolor = 'RGB()';
            switch (true)
            {
                case (queini == 0):
                    result = "Equals Zero.";
                    break;
                case ((queini >= 64) && (queini <= 78)):		
                    whatcolor = '#0066CC';
                    break;
                case ((queini >= 79) && (queini <= 100)):		
                    whatcolor = '#00CC66';
                    break;
                case ((queini >= 101) ):		
                    whatcolor = '#663300';
                    break;
            }
            var formatDateP = new Date(NewMES[n].MessageDATE);
            var m_names = new Array("January", "February", "March","April", "May", "June", "July", "August", "September","October", "November", "December");
            var formatDate = m_names[formatDateP.getMonth()] + ' ' + formatDateP.getDay() + ', '+ formatDateP.getFullYear();
            MesTimeline = MesTimeline + '<div style="float:left; width:465px; margin-top:5px;">'; 
                MesTimeline = MesTimeline + '<div style="float:left; width:45px; margin-left:10px;">'; 
                    if (n==2) 
                        {
                        MesTimeline = MesTimeline + '<img src="/images/PersonalPicSample.jpeg" style="margin-left:0px; width: 40px; height: 40px; border:#cacaca; margin-top:13px;" class="img-circle">';
                        }
                    else
                    {
                        MesTimeline = MesTimeline + '<div class="LetterCircleON" style="width:40px; height:40px; font-size:12px; margin-left:0px; background-color:' + whatcolor + ';"><p style="margin:0px; padding:0px; margin-top:13px;">'+ NewMES[n].MessageINIT +'</p></div>	';
                    }	
                MesTimeline = MesTimeline + '</div>'; 
    
                MesTimeline = MesTimeline + '<div style="float:left; width:380px; margin-left:10px; <!--border-bottom:thin dotted #cacaca;-->">'; 
                    MesTimeline = MesTimeline + '<p style="font-weight:bold; font-size:14px; ">' +  NewMES[n].MessageSEND + '<span style="color:#cacaca; float:right; font-size:12px; font-weight:normal;">    ' + formatDate + '</span> ' + '</p>' ;
                    MesTimeline = MesTimeline +  '<p style="color:grey; font-weight:bold; font-size:12px; margin-bottom:0px; margin-top:-10px;">' + NewMES[n].MessageSUBJ + '</p>';
                    MesTimeline = MesTimeline +  '<p style="color:grey; margin-bottom:0px; line-height:100%; ">' + NewMES[n].MessageCONT.replace(/sp0e/gi," ") + '</p>';
                    
                    var splitted = NewMES[n].MessageRIDS.split(" ");
                    NumReports = splitted.length - 1;
                    if (NumReports>0)
                    {
                        MesTimeline = MesTimeline + '<div style="margin:0 auto; margin-top:10px; width:95%; border:solid #cacaca; border-radius:5px; height:80px;">'; 
                            //MesTimeline = MesTimeline + NumReports + ' Reports.  ';
                            var rn = 0;
                            while (rn < NumReports)
                            {
                                var cadena = 'DecryptFileId.php?reportid='+splitted[rn]+'&queMed='+queMED;
                                var RecTipo = LanzaAjax (cadena);
                                var thumbnail = RecTipo.substr(0,RecTipo.indexOf("."))+'.png';
                                MesTimeline = MesTimeline + '<img class="ThumbTimeline" id="'+splitted[rn]+'" style="height:70px; margin:5px; border:solid 1px #cacaca;" src="temp/'+queMED+'/PackagesTH_Encrypted/'+thumbnail+'">';
                                rn++;
                            }
                        MesTimeline = MesTimeline + '</div>'; 
                    }	
                    
                    MesTimeline = MesTimeline +  '<p style="color:#cacaca; font-size:10px; margin-left:50px; margin-top:5px;"> <span style="margin-right:15px;"><i style="font-size:10px; margin-right:5px;" class="icon-mail-reply"></i>Reply</span><span style="margin-right:15px;"><i style="font-size:10px; margin-right:5px;" class="icon-arrow-right"></i>Forward</span><span style="margin-right:15px;"><i style="font-size:10px; margin-right:5px;" class="icon-bookmark"></i>Mark</span></p>';
                MesTimeline = MesTimeline + '</div>'; 
                
                
            MesTimeline = MesTimeline + '</div>'; 
            
            n++;
        }
        
        if (MesTimeline == '')
        {
            MesTimeline = '<div id="WaitTW" style="position:relative; top:80px; left:150px; height:40px; width:50px; visibility:visible;"><icon class="icon-check icon-3x " style="color:#22aeff;"></icon>  No new messages</div>';
            $('#RCounter').html('      ');
            $("#NewMES").css('width','300px');
            $("#WaitTW").css('visibility','visible');
        }
        $('#NewMES').html(MesTimeline);
        //$('#TextMES').html(MesTimeline);
    
            
    }
    
    function HideTWWait()
    {
        $("#WaitTW").css('visibility','hidden');
        //$("#WaitTW").hide();
    
        
    }	
    
    $('#NewMES').live('click',function(){
        /*
        $("#WaitTW").css('visibility','visible');
        alert ('ok');
        $.when(GetTimeLine()).then(HideTWWait());
        */
        GetTimeLine(0,'doctor');	
    });
    
    //$(".MessageItem").live('click',function() {
    $(".RightZone").live('click',function() {
         var myClass = $(this).attr("id");  //Message ID
         var myClass2 = $(this).attr("id2");  // Patient ID
         var myClass3 = $(this).attr("id3");  // Sender ID
         //alert (myClass+' '+myClass2+' '+myClass3);
         
         var cadena='setMessageStatus.php?msgid='+myClass;
         var RecTipo=LanzaAjax(cadena);
         var WLink="patientdetailMED-new.php?IdUsu="+myClass2; 
         window.location.href = WLink;
    });
    
    $("#PatientMessagesButton").live('click',function(e) {
        window.open('Messages.php','_self',false);
        e.preventDefault();
    });
    $("#DoctorMessagesButton").live('click',function(e) {
        window.open('Messages.php?isDoctors=1','_self',false);
        e.preventDefault();
    });
    
    $(".ThumbTimeline").live('click',function() {
         var myClass = $(this).attr("id");
         var cadena = '<?php echo $domain;?>/DecryptFileId.php?reportid='+myClass	+'&queMed='+<?php echo $MedID;?>;
         var RecTipo = LanzaAjax (cadena);
         //var thumbnail = RecTipo.substr(0,RecTipo.indexOf("."))+'.png';
         var thumbnail = RecTipo;
         var src='temp/'+<?php echo $MedID;?>+'/Packages_Encrypted/'+thumbnail;		
         
         //alert (src);
        $('#myModal').css('display','inline'); 
        
         $("#LaunchModal").trigger("click");
         $('#ZoomedIframe').attr('src',src);
    //		 $('#ZoomedIframe').attr('src',src);				
    //		 $('#ZoomedIframe').css('display','inline');				
    });
    
    $('body', $('#ZoomedIframe').contents()).click(function(event) {
             $('#ZoomedIframe').css('display','none');
    });
    
    
    $('#TypeAccount').bind('click mousedown keydown', function(event) {
        
        getNumRepViewUser();
        getNumActiveUsers();
        getNumRepUpUser();
        
        });
    
    function getNumRepViewUser()
    {
        var queMED = $("#MEDID").val();
        var queGroup = <?php echo $MedGroupId ?>;
        var queUrl ='CheckNumRepViewPat.php?MEDID='+queMED+'&GROUPID='+queGroup;
        var NumRepViewUser = LanzaAjax (queUrl);
        $('#NumRepViewUser').html(NumRepViewUser);
    }
    function getNumActiveUsers()
    {
        var queMED = $("#MEDID").val();
        var queGroup = <?php echo $MedGroupId ?>;
        var queUrl ='CheckNumActiveUsers.php?MEDID='+queMED+'&GROUPID='+queGroup;
        var NumActiveUsers = LanzaAjax (queUrl);
        $('#NumActiveUsers').html(NumActiveUsers);
    }
    function getNumRepUpUser()
    {
        var queMED = $("#MEDID").val();
        var queGroup = <?php echo $MedGroupId ?>;
        var queUrl ='CheckNumRepUpUser.php?MEDID='+queMED+'&GROUPID='+queGroup;
        var NumRepUpUser = LanzaAjax (queUrl);
        $('#NumRepUpUser').html(NumRepUpUser);
    }
    
    
    function daysBetween( date1, date2 ) {
        //Get 1 day in milliseconds
        var one_day=1000*60*60*24;
        var one_hour=1000*60*60;
        
        // Convert both dates to milliseconds
        var date1_ms = date1.getTime();
        var date2_ms = date2.getTime();
        
        // Calculate the difference in milliseconds
        var difference_ms = date2_ms - date1_ms;
        
        // Convert back to days and return
        return [Math.round(difference_ms/one_day), Math.round(difference_ms/one_hour)]; 
    }
    
        
        
    $('body').bind('mousedown keydown', function(event) {
        clearTimeout(timeoutTimer);
        timeoutTimer = setTimeout(ShowTimeOutWarning, timeoutTime);
    });
    
    //<!--xH2MTRAY-->
    
    $('#DivAv').bind('click', function(event) {
        var audio = document.getElementsByTagName("audio")[0];
        audio.play();
        
        var n=0;
        function loop() {
            $('.H2MInText').css({opacity: 1.0});
            $('.H2MInText').animate({opacity: 0.2}, 400, function() {
                if (n<10)
                    {
                        loop();
                        n++;
                    }
                else
                    {
                        var audio = document.getElementsByTagName("audio")[0];
                        audio.play();
                        $('.H2MInText').css({opacity: 1.0});
                        $(".H2MTSCont").animate({ scrollLeft: 300}, 1475);
                    }	
            });
        }
        loop();
    });
                
    $('#DivDoctors').bind('click mousedown keydown', function(event) {
        if (Privilege == '1') 
            window.location.replace('medicalConnections.php');
        else
            window.location.replace('Patients.php');
            
    });
    $('#DivPatients').bind('click mousedown keydown', function(event) {
        if (Privilege == '1') 
            window.location.replace('PatientNetwork.php');
        else
            window.location.replace('Patients.php');
    });
    $('#DivEMR').bind('click mousedown keydown', function(event) {
        if (Privilege == '1') 
            window.location.replace('dashboard.php');
        else
            window.location.replace('Patients.php');
    });
    
        
    $("#SearchUser").typeWatch({
                captureLength: 1,
                callback: function(value) {
                    $("#BotonBusquedaPac").trigger('click');
                    //alert('searching');
                }
    });
    $("#RetrievePatient").click(function(event) {
         $('#BotonBusquedaPac').trigger('click');
    });
    $("#BotonBusquedaPac").click(function(event) {
            var queMED = $("#MEDID").val();
            var UserInput = $('#SearchUser').val();
            //alert(UserInput);
            /*
            var IdUs =156;
            var UserInput = $('#SearchUser').val();
                                  
            var serviceUp' + '?Usuario=' + UserInput; 
            getLifePines(serviceURL);
            var longit = Object.keys(pines).length;	
            */
            //alert (longit);
             
            var onlyGroup=0;
            if ($('#RetrievePatient').is(":checked")){
            onlyGroup=1;
            }else{
            onlyGroup=1;
            }
           
             if(UserInput===""){
                UserInput=-111;
             }
            // alert(UserInput);
            var queUrl ='getFullUsersMED.php?Usuario='+UserInput+'&IdMed='+queMED+'&Usuario='+UserInput+'&Group='+onlyGroup;
            //alert(queUrl);
            $('#TablaPac').load(queUrl);
            //$('#TablaPac').trigger('click');
            $('#TablaPac').trigger('update');
        
    });
    
    $("#BotonBusquedaPacCOMP").click(function(event) {
            var IdUs =156;
            var UserInput = $('#SearchUserYCOMP').val();
                                  
            var serviceURL = '<?php echo $domain;?>/getpines.php' + '?Usuario=' + UserInput; 
            getLifePines(serviceURL);
            var longit = Object.keys(pines).length;	
            //alert (longit);
            var queUrl ='getFullUsers.php?Usuario='+UserInput+'&NReports='+longit;
            $('#TablaPacCOMP').load(queUrl);
            //$('#TablaPac').trigger('click');
            $('#TablaPacCOMP').trigger('update');
    });
         
    
     $(".CFILA").live('click',function() {
        var myClass = $(this).attr("id");
        var queMED = $("#MEDID").val();
        document.getElementById('UserHidden').value=myClass;
        //alert(document.getElementById('UserHidden').value);
        window.location.replace('patientdetailMED-new.php?IdUsu='+myClass);
        //alert("Here");
        //window.location.replace('patientdetailMED.php');
    }); 
    
    $('#TablaPacCOMP').bind('click', function() {
        alert($(this).text());
    });
    
            
    $('#datatable_1').dataTable( {
        "bProcessing": true,
        "bDestroy": true,
        "bRetrieve": true,
        "sAjaxSource": "getBLOCKS.php"
    });
        
    $('#Wait1').hide().ajaxStart(function() 
    {
        $(this).show();
    })
    .ajaxStop(function() 
    {
        $(this).hide();
    }); 
    
    $('#datatable_1 tbody').click( function () 
    {
        // Alert the contents of an element in a SPAN in the first TD    
        alert( $('td:eq(0) span', this).html() );
    });
 
	
});        
    
     
function getLifePines(serviceURL) {
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

window.onload = function(){		
    
    var quePorcentaje = $('#quePorcentaje').val();
    var g = new JustGage({
        id: "gauge", 
        value: quePorcentaje, 
        min: 0,
        max: 100,
        title: " ",
        label: "% Refered to me"
    }); 
};
                    
</script>


    
</body>
</html>
