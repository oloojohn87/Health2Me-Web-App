<?php
session_start();
set_time_limit(180);
require("environment_detail.php");
require("push_server.php");
require('getFullUsersMEDCLASS.php');
require_once("displayExitClass.php");
include('checkoutClass.php');


ini_set("display_errors", 0);
//require_once("push_server.php");
$dbhost = $env_var_db['dbhost'];
$dbname = $env_var_db['dbname'];
$dbuser = $env_var_db['dbuser'];
$dbpass = $env_var_db['dbpass'];
$hardcode = $env_var_db['hardcode'];
$local = $env_var_db['local'];

if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 1800)) {
    // last request was more than 30 minutes ago
    session_unset();     // unset $_SESSION variable for the run-time 
    session_destroy();   // destroy session data in storage
    echo "<META http-equiv='refresh' content='0;URL=index.html'>";
}
$_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp

$telemed_on = false;
$telemed_value=0;
$open_modal = 0;
if(isset($_GET['TELEMED']))
{
    $telemed_on = true;


    if (isset($_GET['TELEMED']) && $_GET['TELEMED']==1) {
        $telemed_value = 1;
    }    
    else if (isset($_GET['TELEMED']) && $_GET['TELEMED']==2) {
        $telemed_value = 2;   

    }    
}
if(isset($_GET['OPENMODAL']))
{
    $open_modal = $_GET['OPENMODAL'];
}
$BOTH_SESSION = $_SESSION['MEDID'];
$NombreEnt = "";
if(isset($_SESSION['Nombre']))
{
    $NombreEnt = $_SESSION['Nombre'];
}
$PasswordEnt = '';//$_SESSION['Password'];

$Acceso = "";
if(isset($_GET['Acceso']))
    { 
        $Acceso = $_GET['Acceso'];
    }
else if(isset($_SESSION['Acceso']))
    { 
        $Acceso = $_SESSION['Acceso'];
    }



//$Acceso = $_SESSION['Acceso'];
$IdUsu = $_GET['IdUsu'];
if(isset($BOTH_SESSION) && isset($_SESSION['Previlege']) /*&& $BOTH_SESSION != $IdUsu*/)
{
    
    $IdMed = $BOTH_SESSION;
    $MedID = $IdMed;
    $privilege=$_SESSION['Previlege'];
}
else
{
    $IdMed = -1;
    $MedID = -1;
    $IdMEDEmail= "";
    $IdMEDName = "";
    $IdMEDSurname = "";
    $tabToOpen=0;
    $appointment_id=0;
}
$pass = "";
if(isset($_SESSION['decrypt']))
{
    $pass = $_SESSION['decrypt'];	
}





if ($Acceso != '23432')
{
$exit_display = new displayExitClass();
$exit_display->displayFunction(1);
die;
}

$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }		

//$result = mysql_query("SELECT * FROM Usuarios where IdUsFIXEDNAME='$NombreEnt' and IdUsRESERV='$PasswordEnt'");
$result = $con->prepare("SELECT * FROM usuarios where Identif=?");
$result->bindValue(1, $IdUsu, PDO::PARAM_INT);
$result->execute();

$count = $result->rowCount();
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
	$IdUsPassword = $row['IdUsRESERV'];
	$email = $row['email'];
	$GrantAccess = $row['GrantAccess'];

//	$MedUserLogo = $row['ImageLogo'];

}
else
{
$exit_display = new displayExitClass();
$exit_display->displayFunction(2);
die;
}

$doc_id = $IdMed;
$mem_id = $IdUsu;

$checker = new checkPatientsClass();



$checker->setters($mem_id, $doc_id);
$checker->checker();
if($row['GrantAccess'] != 'HTI-COL' && $row['GrantAccess'] != 'HTI-PR' && $row['GrantAccess'] != 'HTI-ECU' && $row['GrantAccess'] != 'HTI-MEX' && $row['GrantAccess'] != 'HTI-SPA' && $row['GrantAccess'] != 'HTI-CR'){
if($checker->status1 != 'true' && $checker->status2 != 'true' && $checker->status3 != 'true' && $checker->status4 != 'true' && $_GET['TELEMED'] != 2 && $_GET['TELEMED'] != 1){
    $exit_display = new displayExitClass();
    $exit_display->displayFunction(5);
    die();
}else if($checker->status5 == 'false'){

   $exit_display = new displayExitClass();
    $exit_display->displayFunction(-1);
    die();

}
}

$credits = 0;
if($MedID >= 0)
{
    $result = $con->prepare("SELECT * FROM doctors where id=?");
	$result->bindValue(1, $IdMed, PDO::PARAM_INT);
	$result->execute();
	
    $count = $result->rowCount();
    $row = $result->fetch(PDO::FETCH_ASSOC);
    //$success ='NO';
    if($count==1){
        //$success ='SI';
        //$MedID = $row['id'];
        $IdMEDEmail= $row['IdMEDEmail'];
        $IdMEDName = $row['Name'];
        $IdMEDSurname = $row['Surname'];
        $MedLogo = $row['ImageLogo'];
		$npi = $row['npi'];
		$dea = $row['dea'];
        $credits = $row['credits'];
        $doctor_tracking_cost = $row['tracking_price'];
        $doctor_consult_cost = $row['consult_price'];
    }
    
    $appointment_id = -1;
    $app_res = $con->prepare("SELECT * FROM appointments WHERE med_id=?");
	$app_res->bindValue(1, $IdMed, PDO::PARAM_INT);
	$app_res->execute();
	
    if($app_row = $app_res->fetch(PDO::FETCH_ASSOC))
    {
        $appointment_id = $app_row['id'];
    }
}


//Global variable for blind reports.
//$blindReportId=array();
//CreaTimeline($IdUsu,$IdMed,$pass);

$showreferralsection=0;
$otherdoc=0;
$otherdocname='';
$otherdocSurename='';
$referral_id=0;
$referral_stage=1;
$fechaconfirm=0;
$attachments_dld=0;

$showreferralsectionarray=array();
$otherdocarray= array();
$otherdocnamearray=array();
$otherdocSurnamearray=array();
$otherdoctoremailarray=array();
$referral_id_array=array();
//Added for new referral type
$referral_type_array=array();
$referral_stage_array=array();
$fechaconfirm_array=array();
$attachments_dld_array=array();
$estado_ref=array();

$num_multireferral=0;
$multireferral=0;

$referralcolors=array("#0B701B", "#FC9856", "#4673D1", "#4673D1", "#725AF1", "#ECBE78", "#CDA7E2", "#0B701B", "#FC9856", "#4673D1", "#4673D1");

$i=0;

if($MedID >= 0)
{
    //$sql="SELECT * FROM doctorslinkdoctors where Idpac ='$IdUsu' and (IdMED='$IdMed' or IdMED2='$IdMed') and estado=2 ";
    $sql = $con->prepare("SELECT * FROM doctorslinkdoctors where Idpac =? and (IdMED=? or IdMED2=?) ");
	$sql->bindValue(1, $IdUsu, PDO::PARAM_INT);
	$sql->bindValue(2, $IdMed, PDO::PARAM_INT);
	$sql->bindValue(3, $IdMed, PDO::PARAM_INT);
	$q = $sql->execute();
	
    if($q){
        $cnt=$sql->rowCount();
        if($cnt>=1){
            $num_multireferral=$cnt;
            $multireferral=1;
            while($row = $sql->fetch(PDO::FETCH_ASSOC)){
                if($row['estado']==1)
                    $estado_ref[$i]=$row['estado'];				
                else if($row['estado']==2)
                    $estado_ref[$i]=$row['estado'];
                $referral_type_array[$i]=$row['Type'];
                    if($row['IdMED2']==$IdMed){
                        $otherdocarray[$i]=$row['IdMED'];
                        $showreferralsectionarray[$i]=1;
                        $referral_id_array[$i]=$row['id'];
    
                    }else if($row['IdMED']==$IdMed) {
                        $otherdocarray[$i]=$row['IdMED2'];
                        $showreferralsectionarray[$i]=2;
                        $referral_id_array[$i]=$row['id'];			
                    }
                        //echo "******************************".$otherdocarray[$i];
                        $getname = $con->prepare("select Name,Surname,IdMEDEmail from doctors where id=?");
						$getname->bindValue(1, $otherdocarray[$i], PDO::PARAM_INT);
						$getname->execute();
						
                        $row11 = $getname->fetch(PDO::FETCH_ASSOC);
                        $otherdocnamearray[$i] = $row11['Name'];				
                        $otherdocSurnamearray[$i] = $row11['Surname'];
                        
                        if($otherdocnamearray[$i]=='' and $otherdocSurnamearray[$i]=='')
                            $otherdoctoremailarray[$i]=$row11['IdMEDEmail'];
                        $fechaconfirm_array[$i]=$row['FechaConfirm'];
                        $attachments_dld_array[$i]=$row['attachments'];
                
                $i++;
            }
        }
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
    $getstage = $con->prepare("select stage from referral_stage where referral_id=?");
	$getstage->bindValue(1, $referral_id, PDO::PARAM_INT);
	$getstage->execute();
	
    $row13 = $getstage->fetch(PDO::FETCH_ASSOC);
    $referral_stage=$row13['stage'];
    if($referral_stage==1){
    //Code for appointment from events table
    
    $getschedule=$con->prepare("select * from events where userid=? and patient=? and start>?");
	$getschedule->bindValue(1, $doc_id, PDO::PARAM_INT);
	$getschedule->bindValue(2, $USERID, PDO::PARAM_INT);
	$getschedule->bindValue(3, $fechaconfirm, PDO::PARAM_STR);
	$getschedule->execute();
	
    $cnt = $getschedule->rowCount();
    
    if($cnt>=1){
    $result22=$con->prepare("update referral_stage set stage=2 where referral_id=?");
	$result22->bindValue(1, $referral_id, PDO::PARAM_INT);
	$result22->execute();
	
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
            $getinfo = $con->prepare("select id from bpinview USE INDEX(I1) where viewIdUser=? and viewIdMed=? and content='Report Viewed' and IDPIN=?");
			$getinfo->bindValue(1, $USERID, PDO::PARAM_INT);
			$getinfo->bindValue(2, $doc_id, PDO::PARAM_INT);
			$getinfo->bindValue(3, $report_id[$i], PDO::PARAM_INT);
			$getinfo->execute();
			
			
            $cnt_info = $getinfo->rowCount();
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
            if($reportviewed)
            {
            $result33 = $con->prepare("update referral_stage set stage=3 where referral_id=?");
			$result33->bindValue(1, $referral_id, PDO::PARAM_INT);
			$result33->execute();
			
            $referral_stage=3;
            Push_notification($IdMed,"Referral report view stage completed!");
            Push_notification($otherdoc,"Referral report view stage completed!");
            }
        }else {
            $getinfo = $con->prepare("select id from bpinview USE INDEX(I1) where viewIdUser=? and viewIdMed=? and content='Report Viewed'");
            $getinfo->bindValue(1, $USERID, PDO::PARAM_INT);
			$getinfo->bindValue(2, $doc_id, PDO::PARAM_INT);
			$getinfo->execute();
			
			$cnt_info = $getinfo->rowCount();
            //echo "".$cnt_info."<br>";
            if($cnt_info>3)
            {
            $result44 = $con->prepare("update referral_stage set stage=3 where referral_id=?");
			$result44->bindValue(1, $referral_id, PDO::PARAM_INT);
			$result44->execute();
			
            $referral_stage=3;
            Push_notification($IdMed,"Referral report view stage completed!");
            Push_notification($otherdoc,"Referral report view stage completed!");
            }
    
        }
    
    }
    
    }else{
    
    //check if multireferral is enabled
    if($multireferral==1){
    
        for($i=0;$i<$num_multireferral;$i++){
        
        //Add code for automatically handling the comments stage 3 for new referral
            
        $doc_id_array=array();
        if($showreferralsectionarray[$i]==1){
        $doc_id_array[$i]=$IdMed;
        }else{
        $doc_id_array[$i]=$otherdocarray[$i];
        }
        $getstage = $con->prepare("select stage from referral_stage where referral_id=?");
		$getstage->bindValue(1, $referral_id_array[$i], PDO::PARAM_INT);
		$getstage->execute();
		
        $row13 = $getstage->fetch(PDO::FETCH_ASSOC);
        $referral_stage_array[$i]=$row13['stage'];
        if($referral_stage_array[$i]==1){
        //Code for appointment from events table
    
        //Added changes. Work from here.#task170
        $getschedule=$con->prepare("select * from events where userid=? and patient=? and start>?");
		$getschedule->bindValue(1, $doc_id_array[$i], PDO::PARAM_INT);
		$getschedule->bindValue(2, $USERID, PDO::PARAM_INT);
		$getschedule->bindValue(3, $fechaconfirm_array[$i], PDO::PARAM_STR);
		$getschedule->execute();
		
        $cnt = $getschedule->rowCount();
    
        if($cnt>=1){
        $quick_query=$con->prepare("update referral_stage set stage=2 where referral_id=?");
		$quick_query->bindValue(1, $referral_id_array[$i], PDO::PARAM_INT);
		$quick_query->execute();
		
        $referral_stage_array[$i]=2;
        Push_notification($IdMed,"Referral Appointment Stage completed!",2);
        Push_notification($otherdocarray[$i],"Referral Appointment Stage completed!",2);
    
        }
    
        }
        if($referral_stage_array[$i]==2){
            //Code for information review from bpinview
            $reportviewed=false;
            if($attachments_dld_array[$i]!=0){
                $report_id=explode(" ",$attachments_dld_array[$i]);
                $cntt=count($report_id);
                $j=0;
                //Remember to add the check for date of the reports viewed. It should always be greater than appointment schedule date
                while($cntt>0){
                $getinfo = $con->prepare("select id from bpinview USE INDEX(I1) where viewIdUser=? and viewIdMed=? and content='Report Viewed' and IDPIN=?");
				$getinfo->bindValue(1, $USERID, PDO::PARAM_INT);
				$getinfo->bindValue(2, $doc_id_array[$i], PDO::PARAM_INT);
				$getinfo->bindValue(3, $report_id[$j], PDO::PARAM_INT);
				$getinfo->execute();
				
                $cnt_info = $getinfo->rowCount();
                if($cnt_info)
                    $reportviewed=true;
                else
                    $reportviewed=false;
                $j++;
                $cntt--;
                }
                /*$getinfo = mysql_query("select id from bpinview where viewIdUser='$USERID' and viewIdMed='$doc_id' and content='Report Viewed'");
                $cnt_info=mysql_num_rows($getinfo);*/
                //echo "".$cnt_info."<br>";
                if($reportviewed)
                {
                $quick_query=$con->prepare("update referral_stage set stage=3 where referral_id=?");
				$quick_query->bindValue(1, $referral_id_array[$i], PDO::PARAM_INT);
				$quick_query->execute();
				
                $referral_stage_array[$i]=3;
                Push_notification($IdMed,"Referral report view stage completed!",2);
                Push_notification($otherdocarray[$i],"Referral report view stage completed!",2);
                }
            }else {
                $getinfo = $con->prepare("select id from bpinview USE INDEX(I1) where viewIdUser=? and viewIdMed=? and content='Report Viewed'");
				$getinfo->bindValue(1, $USERID, PDO::PARAM_INT);
				$getinfo->bindValue(1, $doc_id_array[$i], PDO::PARAM_INT);
				$getinfo->execute();
				
                $cnt_info=$getinfo->rowCount();
                //echo "".$cnt_info."<br>";
                if($cnt_info>3)
                {
                $quick_query=$con->prepare("update referral_stage set stage=3 where referral_id=?");
				$quick_query->bindValue(1, $referral_id_array[$i], PDO::PARAM_INT);
				$quick_query->execute();
				
                $referral_stage_array[$i]=3;
                Push_notification($IdMed,"Referral report view stage completed!",2);
                Push_notification($otherdocarray[$i],"Referral report view stage completed!",2);
                }
    
            }
    
        }
        
        
        
        }	
        
     } }
    
    $enc_result = $con->prepare("select pass from encryption_pass where id = (select max(id) from encryption_pass)");
	$enc_result->execute();
    $row_enc = $enc_result->fetch(PDO::FETCH_ASSOC);
    $enc_pass=$row_enc['pass'];
    //BLOCKSLIFEPIN $result = mysql_query("SELECT * FROM BLOCKS");
    //$result = mysql_query("SELECT * FROM LifePin");
    
    
    //Code to decide tab to open by default
    //0:ALL
    //1:Highlighted
    
    $tabToOpen=0;
    if($multireferral>0)
    {
        $highlightedReports = array();
        $count=0;
        $query = $con->prepare("SELECT attachments FROM doctorslinkdoctors where Idpac =? and (IdMED=? or IdMED2=?) ");
		$query->bindValue(1, $IdUsu, PDO::PARAM_INT);
		$query->bindValue(2, $IdMed, PDO::PARAM_INT);
		$query->bindValue(3, $IdMed, PDO::PARAM_INT);
		$result=$query->execute();
		
        while($row = $query->fetch(PDO::FETCH_ASSOC))
        {
            $attachmentString = $row['attachments'];
            $repID = explode(" ",$attachmentString);
            for($i=0;$i<count($repID);$i++)
            {
                if(strlen($repID[$i])>0 && $repID[$i]!=0)
                {
                    $highlightedReports[$count] = $repID[$i];		
                    $count++;
                }
            }
            
        }
        if($count>0)
        {
            $tabToOpen=1;
        }
        else
        {
            $tabToOpen=0;
        }
    }

}

//DATA FOR TIMER START
$startTime = 0;
if(isset($BOTH_SESSION))
{
        $doctorId = $BOTH_SESSION;
        
    
        $DateTime = 0;
        date_default_timezone_set('America/Chicago');

        $sql=$con->prepare("SELECT DateTime FROM ".$dbname.".consults where Doctor=".$doctorId." ORDER BY DateTime DESC LIMIT 1;");
        $sql->execute();
        $timeZone = date_default_timezone_get();

        $resultRow = $sql->fetch(PDO::FETCH_ASSOC);
        $lastDate = $resultRow['DateTime'];
  

        //for testing DEBUG
        //$lastDate = '2014-08-18 11:50:13';

        //if you found a consultation
        if ($lastDate) {
            $startTime = time()-strtotime($lastDate);
            if ($startTime < 0) {
                $startTime = 0;   
            }    
         
        } 
    
}



?>
<!DOCTYPE html>
<html lang="en"><head>
    <meta charset="utf-8">
    <title>Health2me Patient Detail</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
    <?php
    if($GrantAccess != 'CATA'){
		echo '<link href="css/style.css" rel="stylesheet">';
	}else{
		echo '<link href="css/styleCATA.css" rel="stylesheet">';
	}
    ?>
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/bootstrap-dropdowns.css" rel="stylesheet">
    
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
    <link rel='stylesheet' href='build/css/intlTelInput.css'>
    <link rel='stylesheet' href='css/tipped.css'>
    <link rel="stylesheet" href="css/jquery-ui-autocomplete.css" />
    
	<!--<link rel="stylesheet" href="css/icon/font-awesome.css">-->
	<link rel="stylesheet" href="font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/bootstrap-responsive.css">
    <link rel="stylesheet" href="css/jvInmers.css">

    <link rel="stylesheet" type="text/css" href="css/tooltipster.css" />
    
    <link rel="stylesheet" type="text/css" href="h2m_css/h2m_patientdetailMED-new.css" />
    <link rel='stylesheet' type='text/css' href='css/sweet-alert.css'>
    
    <?php
    if (isset($_SESSION['CustomLook']) && $_SESSION['CustomLook']=="COL") { ?>
        <link href="css/styleCol.css" rel="stylesheet">
    <?php } ?>


    <!-- Le fav and touch icons -->
    <link rel="shortcut icon" href="images/icons/favicon.ico">
    
    <script src="js/jquery.min.js"></script>
    <script src="js/jquery-ui.min.js"></script>
	<link rel="stylesheet" type="text/css" href="js/uploadify/uploadify.css">
    <script type="text/javascript" src="js/uploadify/jquery.uploadify.min.js"></script> 
    <script type="text/javascript" src="js/jquery.fittext.js"></script> 
	
	<!-- Create language switcher instance and set default language to en-->
	<!--<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js" type="text/javascript"></script>-->
	   <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>
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
}

if(langType == 'en'){
setTimeout(function(){
window.lang.change('en');
lang.change('en');
//alert('th');
}, 2000);
}
	
</script>

	<!--<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
	<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
	<script src="http://code.jquery.com/jquery-migrate-1.2.1.js"></script>
	<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>-->
	<!-- Quick-Start: Step 2 -->
   <!-- <script type="text/javascript" src="../js/jquery-1.9.1.min.js"></script> -->
<!-- Quick-Start: Step 3 -->
   <!-- <script type="text/javascript" src="js/42b6r0yr5470"></script> -->
   
   <!-- Adding changes for the file upload -->
   <link rel="stylesheet" type="text/css" media="all" href="fileupload/styles.css" />

	
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
	  
	   <style>
		<!--canvas { display: inline-block; background: #202020; box-shadow: 0px 0px 10px blue;}-->
		#record.recording { color: rgba(10, 25, 133, 1);}
	
	</style>
	
	<style>
	.frame::-webkit-scrollbar {
		-webkit-appearance: none;
	}

	.frame::-webkit-scrollbar:vertical {
		width: 11px;
	}

	.frame::-webkit-scrollbar:horizontal {
		height: 11px;
	}

	.frame::-webkit-scrollbar-thumb {
		border-radius: 8px;
		border: 2px solid white; /* should match background, can't be transparent */
		background-color: rgba(0, 0, 0, .5);
	}

	.frame::-webkit-scrollbar-track { 
		background-color: #fff; 
		border-radius: 8px; 
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
  <body onload=" $('.TABES:eq(<?php echo $tabToOpen;?>)').click();">
      <input type="hidden" id="app_id" value="<?php echo $appointment_id; ?>" />
	  
	  <?php
			//BLOCKSLIFEPIN $sql="SELECT * FROM BLOCKS where IdUsu ='$IdUsu'";
			$sql=$con->prepare("SELECT * FROM lifepin where IdUsu =?");
			$sql->bindValue(1, $IdUsu, PDO::PARAM_INT);
			$q = $sql->execute();
			
			$row_lifepin = $sql->fetch(PDO::FETCH_ASSOC);
			?>

	<!--<div class="loader_spinner"></div>-->
	<!--------------------------------------------------------------------------------------------------------HIDDEN INPUTS------------------------------------------------------------------------------>
			<input type="hidden" id="MEDID" Value="<?php echo $IdMed; ?>">	
	    	<input type="hidden" id="IdMEDEmail" Value="<?php echo $IdMEDEmail; ?>">	
	    	<input type="hidden" id="IdMEDName" Value="<?php echo $IdMEDName; ?>">	
	    	<input type="hidden" id="IdMEDSurname" Value="<?php echo $IdMEDSurname; ?>">	
	    	<input type="hidden" id="IdMEDLogo" Value="<?php if(isset($MedLogo)) echo $MedLogo; ?>">	
	        <input type="hidden" id="USERID" Value="<?php echo $USERID; ?>">
			<input type="hidden" id="NPI" Value="<?php echo $npi; ?>">
			<input type="hidden" id="DEA" Value="<?php echo $dea; ?>">
			<input type="hidden" id="GET_IDUSU" value="<?php if(isset($_GET['IdUsu'])) echo $_GET['IdUsu']; ?>">
			<input type="hidden" id="BOTH_ID" value="<?php echo $BOTH_SESSION ?>">
            <input type="hidden" id="DOMAIN" value="<?php echo $hardcode; ?>">
			<input type="hidden" id="TELEMED_ON" value="<?php echo $telemed_on; ?>">
			<input type="hidden" id="TELEMED_VALUE" value="<?php echo $telemed_value; ?>">
            <input type="hidden" id="OPENMODAL" value="<?php echo $open_modal; ?>">
			<input type="hidden" id="START_TIME" value="<?php echo $startTime; ?>">
			<input type="hidden" id="NUM_MULTIREFERRAL" value="<?php echo $num_multireferral; ?>">
			<input type="hidden" id="IDUSFIXED" value="<?php echo $IdUsFIXED; ?>">
			<input type="hidden" id="IDUSFIXED_NAME" value="<?php echo $IdUsFIXEDNAME; ?>">
			<input type="hidden" id="CANAL" value="<?php if(isset($row_lifepin['CANAL'])) echo $row_lifepin['CANAL']; ?>">
			<input type="hidden" id="REFERRAL_ID" value="<?php echo $referral_id; ?>">
			<input type="hidden" id="OTHERDOC" value="<?php echo $otherdoc; ?>">
			<input type="hidden" id="CUSTOM_LOOK" value="<?php if(isset($_SESSION['CustomLook'])) echo $_SESSION['CustomLook']; ?>">
			<input type="hidden" id="MEDUSERNAME" value="<?php if(isset($MedUserName)) echo $MedUserName; ?>">
			<input type="hidden" id="MEDUSERSURNAME" value="<?php if(isset($MedUserSurname)) echo $MedUserSurname; ?>">
            <input type="hidden" id="CHECKOUT" value="<?php if(isset($_GET['checkout'])) { echo '1'; } else { echo '0'; } ?>" />
            <input type='hidden' id='doc-tracking-price' value='<?php echo $doctor_tracking_cost ?>' />
            <input type='hidden' id='doc-consult-price' value='<?php echo $doctor_consult_cost ?>' />
            <input type='hidden' id='probe_id_holder_for_purchase' value='<?php echo $_GET['IdUsu'] ?>' />
            
	<!------------------------------------------------------------------------------------------------------END HIDDEN INPUTS------------------------------------------------------------------------------->
	<!--Header Start-->
	
	<!--THIS HOLDS THE LOCATION DATA FOR E-PRESCRIBING-->
	<input type="hidden" id="cname2" Value="">	
	<input type="hidden" id="pname2" Value="">
	<input type="hidden" id="address12" Value="">
	<input type="hidden" id="address22" Value="">
	<input type="hidden" id="city2" Value="">
	<input type="hidden" id="state2" Value="">	
	<input type="hidden" id="zip2" Value="">
	<input type="hidden" id="phone2" Value="">	
	<input type="hidden" id="fax2" Value="">		
	<input type="hidden" id="id2" Value="">	
	<input type="hidden" id="id3" Value="">	
	<input type="hidden" id="practice" Value="">	
	
	
	<div class="header" >
    		
          <?php
  		       if($GrantAccess == 'HTI-RIVA'){
				echo "<a href='UserDashboardHTI.php' style='background-image:url(images/RivaCare_Logo.png);display:block;width:325px;height:42px;float:left;'></a>";
			}elseif($GrantAccess == 'HTI-24X7'){
				echo "<a href='http://24x7hellodoctor.com/' style='background-image:url(http://24x7hellodoctor.com/img/logo-24x7-hellodoctor.jpg); background-size: 250px 42px;background-repeat: no-repeat;display:block;width:255px;height:42px;float:left;'></a>";
			}elseif($GrantAccess == 'HTI-COL'){
				echo '<a href="index-col.html" class="logo"><h1>Health2me</h1></a>';
			}else{
				echo '<a href="index.html" class="logo"><h1>Health2me</h1></a>';
			}  
           /*if(isset($_GET['checkout'])){
               
				$checkout_user_id = $USERID;
				$checkout_doc_id = $IdMed;
				$includes = 'yes';
				$checkout = new checkoutClass($checkout_user_id, $checkout_doc_id, $includes, true);
                
			}*/
           ?>
            <!-- Below is earlier code for language -->
      <!--  Start of comment <div style="float:left;">
		   <a href="#en" onclick="setCookie('lang', 'en', 30); return false;"><img src="images/icons/english.png"></a>
		   </br>
			<a href="#sp" onclick="setCookie('lang', 'th', 30); return false;"><img src="images/icons/spain.png"></a>
			</div> End of comment -->
        
             <!-- Start of new code by Pallab -->
             <!-- Beautification of button (changes to standar classes to be added to this instance of dropdown -->
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
                <?php if($_SESSION['CustomLook'] == "COL") { ?>
                .addit_button{
                    color: #048F90;   
                    border: 1px solid #048F90;
                }
                .addit_caret{
                    border-top: 4px solid #048F90;
                }
                <?php } ?>
               </style>
               <div style="margin-top:11px;float:left; margin-left:50px;" class="btn-group">
                      <button id="lang1" type="button" class="btn btn-default dropdown-toggle addit_button" data-toggle="dropdown">
                        Language <span class="caret addit_caret"></span>
                      </button>
                      <ul class="dropdown-menu" role="menu">
                        <li><a href="#en" onclick="setCookie('lang', 'en', 30); return false;">English</a></li>
                        <li><a href="#sp" onclick="setCookie('lang', 'th', 30); return false;">Espa&ntilde;ol</a></li>
                        <!--li><a href="#tu" onclick="setCookie('lang', 'tu', 30); return false;">T&uuml;rk&ccedil;e</a></li>
                        <li><a href="#hi" onclick="setCookie('lang', 'hi', 30); return false;">हिंदी</a></li-->
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
		   <!--Notifications Start-->  
           <div class="notifications-head">
           
            <div class="btn-group pull-left hide-mobile" >

            <div  id="notification_window" class="dropdown-menu">
            
              <span class="triangle-2"></span>
              <div class="ichat">
               <div class="ichat-messages">
               	<div class="ichat-title">
                  <div class="pull-left" lang="en">Recent Activity</div>
                  <!--<div class="pull-right"><span>Update 14*</span></div>-->
                  <div class="clear"></div>
                </div>
                
                <div id="getnotificationmessages" class="r_activity" style="height:auto;">
               
                </div>
               
                
                </div>
                <!--<a href="#" class="iview">View all</a><a href="#" class="imark">Mark all read</a>-->
               
              </div>
            
            </div>
          </div>   <!--Recent Activity END--> 
		             
          </div><!--Notifications END-->
           <!--Button User Start-->
		   <div class="btn-group pull-right" >
           
            <a class="btn btn-profile dropdown-toggle" id="button-profile" data-toggle="dropdown" href="#">
              <span class="name-user"><strong lang="en">Welcome</strong>, <?php if($MedID > 0){echo $IdMEDEmail;} else{ echo $MedUserName.' '.$MedUserSurname;} ?></span> 
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
              <li><a href="UserDashboard.php" lang="en"><i class="icon-globe"></i> Home</a></li>
              
             <!-- <li><a href="medicalConfiguration.php"><i class="icon-cog"></i> Settings</a></li>-->
              <li><a href="logout.php" lang="en"><i class="icon-off"></i> Sign Out</a></li>
            </ul>
            </div>
          </div>
          <!--Button User END-->  

          
          </div>
    </div>
    <!--Header END-->

   
    <!--Content Start-->
	<div id="content" style="padding:0px; background: #F9F9F9; overflow:auto;">
    
        <!-- Lines of code added by Pallab -->
         <form method="POST" enctype="multipart/form-data" action="save.php" id="myForm">
            <input type="hidden" name="img_val" id="img_val" value="" />
        </form>  
         <!-- Lines of code added by Pallab -->
        
    <!-- HEALTH TRACKING MODAL -->
    <div id="tracking_info_modal" title="Tracking" style="width: 950px; height: 600px; display: none;">
        <style>
            .probe_chart_button{
                float: left; 
                width: 20px; 
                margin-right: 10px; 
                height: 20px; 
                border-radius: 20px; 
                border: 1px solid #B8B8B8; 
                outline: none; 
                color: #AAA; 
                background-color: #F8F8F8; 
                font-size: 8px;
                cursor: pointer;
            }
            .probe_chart_button:disabled{
                border: 1px solid #DDD; 
                color: #DDD;
                cursor: default;
                background-color: #FBFBFB; 
            }
            .probe_chart_button_selected{
                float: left; 
                width: 20px; 
                margin-right: 10px; 
                height: 20px; 
                border-radius: 20px; 
                border: 1px solid #22AEFF; 
                outline: none; 
                color: #FFF; 
                background-color: #22AEFF; 
                font-size: 10px;
                cursor: default;
            }
        </style>
        <div id="timeline" style="width: 95%; height:150px; float:left; position:relative; margin-left:20px; margin-bottom: 15px;"></div>
        <div style="width: 850px; margin: auto;">
            <div style="width: 100%; height: 516px;">
                <div class="external_graph_container" id="probe_graph_1"></div>
                <div id="probe_question_1" style="width: 98%; height: 20px; padding: 1%; border-radius: 5px; background-color: #FAFAFA; color: #888; margin-top: 5px; border: 1px solid #EEE; display: none;"></div>
                <div id="probe_graph_2" style="display: none;"></div>
                <div id="probe_question_2" style="width: 98%; height: 20px; padding: 1%; border-radius: 5px; background-color: #FAFAFA; color: #888; margin-top: 5px; border: 1px solid #EEE; display: none;"></div>
                <div id="probe_graph_3" style="display: none;"></div>
                <div id="probe_question_3" style="width: 98%; height: 20px; padding: 1%; border-radius: 5px; background-color: #FAFAFA; color: #888; margin-top: 5px; border: 1px solid #EEE; display: none;"></div>
                <div id="probe_graph_4" style="display: none;"></div>
                <div id="probe_question_4" style="width: 98%; height: 20px; padding: 1%; border-radius: 5px; background-color: #FAFAFA; color: #888; margin-top: 5px; border: 1px solid #EEE; display: none;"></div>
                <div id="probe_graph_5" style="display: none;"></div>
                <div id="probe_question_5" style="width: 98%; height: 20px; padding: 1%; border-radius: 5px; background-color: #FAFAFA; color: #888; margin-top: 5px; border: 1px solid #EEE; display: none;"></div>
            </div>
            <div style="width: 150px; height: 20px; margin: auto; margin-top: 15px;">
                <button id="probebutton_1" class="probe_chart_button" disabled>
                    <div style="margin-top: -3px;">1</div>
                </button>
                <button id="probebutton_2" class="probe_chart_button" disabled>
                    <div style="margin-top: -3px;">2</div>
                </button>
                <button id="probebutton_3" class="probe_chart_button" disabled>
                    <div style="margin-top: -3px;">3</div>
                </button>
                <button id="probebutton_4" class="probe_chart_button" disabled>
                    <div style="margin-top: -3px;">4</div>
                </button>
                <button id="probebutton_5" class="probe_chart_button" disabled>
                    <div style="margin-top: -3px;">5</div>
                </button>
            </div>
        </div>    
        
    </div>
    <!-- END MODAL FOR HEALTH TRACKING -->
        
    <!-- MODAL FOR MEDICATION REMINDERS -->
    <div id="medication_reminders_modal" title="Medication Reminders" style="width: 950px; height: 600px; display: none;">
        <style>
            .reminder_row{
                width: 96%;
                height: 44px;
                padding-top: 4px;
                padding-bottom: 2px;
                padding-left: 1%;
                padding-right: 1%;
                background-color: #FAFAFA;
                border: 1px solid #D8D8D8;
                border-radius: 6px;
                margin: auto;
                margin-bottom: 8px;
            }
            .no_reminders_notification{
                width: 94%;
                height: 50px;
                padding-top: 8px;
                padding-bottom: 2px;
                padding-left: 3%;
                padding-right: 1%;
                background-color: #F2F2F2;
                border: 1px dashed #E8E8E8;
                border-radius: 6px;
                margin: auto;
                margin-bottom: 8px;
                text-align: center;
                color: #777;
            }
            .reminders_edit_button{
                width: 30px;
                height: 30px;
                background-color: #54bc00;
                color: #FFF;
                border-radius: 30px;
                outline: none;
                border: 0px solid #FFF;

            }
            .reminders_delete_button{
                width: 30px;
                height: 30px;
                background-color: #D84840;
                color: #FFF;
                border-radius: 30px;
                outline: none;
                border: 0px solid #FFF;

                margin-left: 5px;
            }

        </style>
        <div id="reminders_container" style="width: 100%; height: 400px; overflow-y: auto;">
            <div class="reminder_row">
                <div style="float: left; width: 25%;">
                    <div class="reminder_name" style="width: 100%; overflow: hidden; white-space: nowrap; text-overflow: ellipsis; font-weight: bold; font-size: 16px; color: #555;">
                        Acetaminophen
                    </div>
                    <div class="reminder_last_taken" style="width: 100%; overflow: hidden; white-space: nowrap; text-overflow: ellipsis; color: #777;">
                        Last Taken: <span style="color: #54BC00;">2 hours ago</span>
                    </div>
                </div>
                <div style="float: left; width: 65%; height: 30px; border: 1px solid #333; border-radius: 30px; margin-right: 10px; margin-top: 5px; overflow: hidden;">
                    <div style="width: 40%; height: 30px; background-color: #54BC00; float: left;"></div>
                    <div style="width: 5%; height: 30px; background-color: #D84840; float: left;"></div>
                    <div style="width: 20%; height: 30px; background-color: #54BC00; float: left;"></div>
                </div> 
                <div style="float: left; margin-top: 6px;">
                    <button class="reminders_edit_button"><i class="icon-pencil"></i></button>
                    <button class="reminders_delete_button">X</button>
                </div>
			</div>
            
            <div class="reminder_row">
                <div style="float: left; width: 25%;">
                    <div class="reminder_name" style="width: 100%; overflow: hidden; white-space: nowrap; text-overflow: ellipsis; font-weight: bold; font-size: 16px; color: #555;">
                        Ibuprofen
                    </div>
                    <div class="reminder_last_taken" style="width: 100%; overflow: hidden; white-space: nowrap; text-overflow: ellipsis; color: #777;">
                        Last Taken: <span style="color: #D84840;">3 days ago</span>
                    </div>
                </div>
                <div style="float: left; width: 65%; height: 30px; border: 1px solid #333; border-radius: 30px; margin-right: 10px; margin-top: 5px; overflow: hidden;">
                    <div style="width: 30%; height: 30px; background-color: #54BC00; float: left;"></div>
                    <div style="width: 5%; height: 30px; background-color: #D84840; float: left;"></div>
                    <div style="width: 20%; height: 30px; background-color: #54BC00; float: left;"></div>
                    <div style="width: 10%; height: 30px; background-color: #D84840; float: left;"></div>
                </div> 
                <div style="float: left; margin-top: 6px;">
                    <button class="reminders_edit_button"><i class="icon-pencil"></i></button>
                    <button class="reminders_delete_button">X</button>
                </div>
			</div>
        </div>
        <div style="width: 200px; height: 80px; margin: auto;">
            <button id="add_medication_reminder_button" style="width: 150px; height: 80px; background-color: #22AEFF; color: #FFF; border-radius: 5px; outline: none; border: 0px solid #FFF; font-size: 16px;">
                <i class="icon-plus"></i><br/>
                Add New Reminder
            </button>
        </div>
    </div>
    <!-- END MODAL FOR MEDICATION REMINDERS -->
        
    <!-- MODAL FOR MEDICATION SEARCH -->
    <div id="medication_search_modal" title="Medication Search" style="width: 970px; height: 750px; display: none; overflow-y: hidden; overflow-x: scroll;">
    <style>
        .medication_search_result{
            margin: 10px;
            width: 210px;
            height: 270px;
            border: 1px solid #DDD;
            border-radius: 8px;
            padding: 10px;
            padding-top: 16px;
            background-color: #FFF;
            float: left;
        }
    </style>
        <div style="width: 950px; height: 300px;">
            <div style="float: left; width: 20%; padding-top: 10px; height: 20px; margin-bottom: 14px;">
                Name: 
            </div>
            <div style="float: left; width: 80%; margin-bottom: 14px;  height: 30px;">
                <input id="name" placeholder="Brand name or medical term"  style="width: 98%;" type="text" />
            </div>
            <div style="float: left; width: 20%; padding-top: 10px; height: 20px; margin-bottom: 14px;">
                Shape: 
            </div>
            <div style="float: left; width: 80%; margin-bottom: 14px;  height: 30px;">
                <select id="pill_shape"  style="width: 100%;">
                    <option value="0">N/A</option>
                    <option value="BULLET">Bullet</option>
                    <option value="CAPSULE">Capsule</option>
                    <option value="CLOVER">Clover</option>
                    <option value="DIAMOND">Diamond</option>
                    <option value="DOUBLE CIRCLE">Double Circle</option>
                    <option value="FREEFORM">Freeform</option>
                    <option value="GEAR">Gear</option>
                    <option value="HEPTAGON">Heptagon</option>
                    <option value="HEXAGON">Hexagon</option>
                    <option value="OCTAGON">Octagon</option>
                    <option value="OVAL">Oval</option>
                    <option value="PENTAGON">Pentagon</option>
                    <option value="RECTANGLE">Rectangle</option>
                    <option value="ROUND">Round</option>
                    <option value="SEMI-CIRCLE">Semi-circle</option>
                    <option value="SQUARE">Square</option>
                    <option value="TEAR">Tear</option>
                    <option value="TRAPEZOID">Trapezoid</option>
                    <option value="TRIANGLE">Triangle</option>
                </select>
            </div>
            <div style="float: left; width: 20%; padding-top: 10px; height: 20px; margin-bottom: 14px;">
                Color:
            </div>
            <div style="float: left; width: 80%; margin-bottom: 14px;  height: 30px;">
                <select id="pill_color"  style="width: 100%;">
                    <option value="0">N/A</option>
                    <option value="BLACK">Black</option>
                    <option value="BLUE">Blue</option>
                    <option value="BROWN">Brown</option>
                    <option value="GRAY">Gray</option>
                    <option value="GREEN">Green</option>
                    <option value="ORANGE">Orange</option>
                    <option value="PINK">Pink</option>
                    <option value="PURPLE">Purple</option>
                    <option value="RED">Red</option>
                    <option value="TURQUOISE">Turquoise</option>
                    <option value="WHITE">White</option>
                    <option value="YELLOW">Yellow</option>
                </select>
            </div>
            <div style="float: left; width: 20%; padding-top: 10px; height: 20px; margin-bottom: 14px;">
                Imprint:
            </div>
            <div style="float: left; width: 80%; margin-bottom: 14px;  height: 30px;">
                <input id="imprint" placeholder="Characters imprinted on pill" type="text"  style="width: 98%;" />
            </div>
            <div style="float: left; width: 20%; padding-top: 10px; height: 20px; margin-bottom: 14px;">
                Imprint Color:
            </div>
            <div style="float: left; width: 80%; margin-bottom: 14px;  height: 30px;">
                <select id="imprint_color"  style="width: 100%;">
                    <option value="0">N/A</option>
                    <option value="BLACK">Black</option>
                    <option value="BLUE">Blue</option>
                    <option value="BROWN">Brown</option>
                    <option value="GRAY">Gray</option>
                    <option value="GREEN">Green</option>
                    <option value="ORANGE">Orange</option>
                    <option value="PINK">Pink</option>
                    <option value="PURPLE">Purple</option>
                    <option value="RED">Red</option>
                    <option value="TURQUOISE">Turquoise</option>
                    <option value="WHITE">White</option>
                    <option value="YELLOW">Yellow</option>
                </select>
            </div>
            <div style="width: 150px; height: 40px; margin: auto;">
                <button id="medication_search_button" style="width: 150px; height: 40px; background-color: #22AEFF; border: 0px solid #FFF; border-radius: 5px; outline: none; color: #FFF; font-size: 18px; margin-top: 10px; cursor: pointer;">Search</button>
            </div>
        </div>
        <div style="width: 950px; height: 328px; overflow-x: scroll; overflow-y: hidden;">
            <div id="medications_search_results_container" style="width:100%; height: 328px; background-color: #EEE;" >

            </div>
        </div>
        <div style="width: 150px; height: 40px; margin: auto;">
            <style>
            #medication_select_button{
                width: 150px; 
                height: 40px; 
                background-color: #22AEFF; 
                border: 0px solid #FFF; 
                border-radius: 5px; 
                outline: none; 
                color: #FFF; 
                font-size: 18px; 
                margin-top: 10px; 
                cursor: pointer;
            }
            #medication_select_button:disabled{
                background-color: #89D5FF;
                cursor: default;
            }
            </style>
            <button id="medication_select_button">Select</button>
        </div>
        
    </div>
    <!-- END MODAL FOR MEDICATION SEARCH -->
    <!-- MODAL FOR MEDICATION REMINDERS EDITING -->
    <div id="reminders_edit_modal" title="Edit Medication Reminder" style="width: 400px; height: 360px; display: none; overflow-y: hidden;">
        <div style="width: 100%; height: 40px; margin-top: 10px;">
            <div style="width: 35%; height: 35px; padding-top: 5px; float: left;">
                Frequency: 
            </div>
            <div style="width: 65%; height: 40px; float: left;">
                Every <input style="width: 30%; margin-left: 7px;" id="reminder_frequency" type="number" />
                <select style="width: 35%;" id="reminder_frequency_unit">
                    <option value="hours">Hours</option>
                    <option value="days">Days</option>
                </select>
            </div>
        </div>    
        <div style="width: 100%; height: 40px; margin-top: 20px;">
            <div style="width: 35%; height: 35px; padding-top: 5px; float: left;">
                Starting Date: 
            </div>
            <div style="width: 65%; height: 40px; float: left;">
                <input type="date" style="width: 89%;" id="reminder_starting_date" />
            </div>
            
        </div>
        <div style="width: 100%; height: 40px; margin-top: 20px;">
            <div style="width: 35%; height: 35px; padding-top: 5px; float: left;">
                Starting Time: 
            </div>
            <div style="width: 20%; height: 40px; float: left;">
                <input type="text" style="width: 100%;" id="reminder_starting_time" />
            </div>
            <select id="reminder_timezone" style="width: 37%; margin-left: 5%;">
                <option value="Pacific/Kwajalein" lang="en">Eniwetok, Kwajalein</option>
                <option value="Pacific/Samoa" lang="en">Midway Island, Samoa</option>
                <option value="Pacific/Adak" lang="en">Hawaii</option>
                <option value="Pacific/Honolulu" lang="en">Hawaii (Honolulu)</option>
                <option value="America/Anchorage" lang="en">Alaska</option>
                <option value="America/Los_Angeles" lang="en">Pacific Time</option>
                <option value="America/Denver" lang="en">Mountain Time</option>
                <option value="America/Phoenix" lang="en">Mountain Time (Arizona)</option>
                <option value="America/Chicago" selected="" lang="en">Central Time, Mexico City</option>
                <option value="America/New_York" lang="en">Eastern Time (US &amp; Canada), Bogota, Lima</option>
                <option value="America/Caracas" lang="en">Atlantic Time (Canada), Caracas, La Paz</option>
                <option value="America/St_Johns" lang="en">Newfoundland</option>
                <option value="America/Sao_Paulo" lang="en">Brazil, Buenos Aires, Georgetown</option>
                <option value="Atlantic/Cape_Verde" lang="en">Azores, Cape Verde Islands</option>
                <option value="Europe/London" lang="en">Western Europe Time, London, Lisbon, Casablanca</option>
                <option value="Europe/Madrid" lang="en">Brussels, Copenhagen, Madrid, Paris</option>
                <option value="Africa/Johannesburg" lang="en">Kaliningrad, South Africa</option>
                <option value="Asia/Baghdad" lang="en">Baghdad, Riyadh, Moscow, St. Petersburg</option>
                <option value="Asia/Tehran" lang="en">Tehran</option>
                <option value="Asia/Baku" lang="en">Abu Dhabi, Muscat, Baku, Tbilisi</option>
                <option value="Asia/Kabul" lang="en">Kabul</option>
                <option value="Asia/Tashkent" lang="en">Ekaterinburg, Islamabad, Karachi, Tashkent</option>
                <option value="Asia/Calcutta" lang="en">Bombay, Calcutta, Madras, New Delhi</option>
                <option value="Asia/Katmandu" lang="en">Kathmandu</option>
                <option value="Asia/Dhaka" lang="en">Almaty, Dhaka, Colombo</option>
                <option value="Asia/Bangkok" lang="en">Bangkok, Hanoi, Jakarta</option>
                <option value="Asia/Singapore" lang="en">Beijing, Perth, Singapore, Hong Kong</option>
                <option value="Asia/Tokyo" lang="en">Tokyo, Seoul, Osaka, Sapporo, Yakutsk</option>
                <option value="Australia/Adelaide" lang="en">Adelaide, Darwin</option>
                <option value="Australia/Sydney" lang="en">Eastern Australia, Guam, Vladivostok</option>
                <option value="Australia/Brisbane" lang="en">Brisbane</option>
                <option value="Pacific/Nauru" lang="en">Magadan, Solomon Islands, Nauru, New Caledonia</option>
                <option value="Pacific/Auckland" lang="en">Auckland, Wellington, Fiji, Kamchatka</option>
            </select>
            
        </div>
        
        <div style="width: 100%; height: 40px; margin-top: 20px;">
            <div style="width: 35%; height: 35px; padding-top: 5px; float: left;">
                Alert: 
            </div>
            <div style="width: 65%; height: 40px; float: left;">
                After <input style="width: 15%; margin-left: 7px;" id="reminder_alert" type="number" /> misses
            </div>
        </div>
        <div style="width: 100px; height: 30px; margin: auto; margin-top: 20px;">
            <input id="probeToggle_reminder" class="h2m-toggle h2m-toggle-round" type="checkbox">
            <label id="pTL_reminder" for="probeToggle_reminder" data-on="Yes" data-off="No" style="float: left; opacity: 1;"></label>
            <div id="probeToggleLabel_reminder" style="color: rgb(216, 72, 64); float: right; width: 30px; height: 30px; font-size: 22px; text-align: right; margin-top: 7px; opacity: 1;">Off</div>
        </div>
    </div>
    <!-- END MODAL FOR MEDICATION REMINDERS EDITING -->
    
    <!-- MODAL VIEW FOR SUMMARY -->
    <div id="summary_modal" lang="en" title="Summary" style="display:none; text-align:center; width: 1000px; height: 660px; overflow: hidden;">
    </div>
    <!-- END MODAL VIEW FOR SUMMARY -->
	
	<!-- MODAL VIEW FOR E-Prescribe -->
    <div id="ePrescribe_Modal" lang="en" title="E-Prescribe -==UNDER CONSTRUCTION==-" style="display:none; text-align:center; width: 1000px; height: 660px; overflow: hidden;">
    </div>
	
    <!-- END MODAL VIEW FOR SUMMARY -->
        
    <!-- MODAL VIEW FOR PHONE TELEMEDICINE -->    
    <div id="phoneTelemed" lang="en" title="Telemedicine" style="display:none; width: 480px; height: 30px; margin: auto; margin-top: 7px;">
            <style>
                .telemed_button{
                    float: left; 
                    width: 150px; 
                    border: 0px solid #FFF; 
                    outline: 0px; 
                    color: #FFF;
                    margin-right: 5px;
                    margin-left: 5px;
                    border-radius: 7px;
                    margin-top: 5px;
                }
            </style>
            <!-- Start Code for timer-->
            <div style="align:center; margin-left:157px;">  
                <div class="timer">
                    <span class="hour">00</span>:<span class="minute">00</span>:<span class="second">00</span>
                </div>
                <div class="control">
                    <button style="display:none;" id="startButton1" onClick="timer.start(1000); timer.reset(<?php echo $startTime; ?>);">Start</button> 
                </div>
            </div>    
            <!--end code for time -->
            <button class="Telemed_Summary_Button telemed_button" style="background-color: #52D859;" lang="en">Patient Summary</button>
            <button class="Telemed_Notes_Button telemed_button" style="background-color: #22aeff;" lang="en">Notes</button>
            <button class="Telemed_Close_Button telemed_button" style="background-color: #D84840;" lang="en">Close Consultation</button>
        
    </div>  
     
    <!-- END MODAL VIEW FOR PHONE TELEMEDICINE -->    
        
    <!-- MODAL VIEW FOR VIDEO TELEMEDICINE -->
    <script src="js/SimpleWebRTC/latest.js"></script> 
    <script src="js/SimpleWebRTC/RecordRTC.js"></script> 
    <div id="videoTelemed" title="Telemedicine" style="display:none; text-align:center; width: 100%; height: 100%;">
        
        <div style="width: 100%; height: 85%; margin-top: 7px;">
            <div id="remoteVideo" style="background-color: #393939; width: 100%; height: 100%; border-radius: 6px;">
                
                
            </div>
            <div style="float:left; margin-left: 20px; margin-top: -68px;"><video id="localVideo" width="70" style="mask-image: url(small_video_mask.png);" autoplay></video></div>
            
        
        </div>
        <div style="width: 480px; height: 30px; margin: auto; margin-top: 7px;">
            <style>
                .telemed_button{
                    float: left; 
                    width: 150px; 
                    border: 0px solid #FFF; 
                    outline: 0px; 
                    color: #FFF;
                    margin-right: 5px;
                    margin-left: 5px;
                    border-radius: 7px;
                    margin-top: 5px;
                }
            </style>
            <!-- Start Code for timer-->
            <div style="align:center; margin-left:157px;">  
                <div class="timer">
                    <span class="hour">00</span>:<span class="minute">00</span>:<span class="second">00</span>
                </div>
                <div class="control">
                    <button style="display:none;" id="startButton" onClick="timer.start(1000)">Start</button> 
                </div>
            </div>    
            <!--end code for time -->
            <button class="Telemed_Summary_Button telemed_button" style="background-color: #52D859;" lang="en">Patient Summary</button>
            <button class="Telemed_Notes_Button telemed_button" style="background-color: #22aeff;" lang="en">Notes</button>
            <button class="Telemed_Close_Button telemed_button" style="background-color: #D84840;" lang="en">Close Consultation</button>
        
        </div>
    </div>
    <div id="please_wait_modal" title="Please Wait" style="width: 300px; height: 200px; display: none;">
        <img src="images/load/29.gif" style="margin-top: 15px; margin-left: 124px;margin-bottom: 30px;"  alt="">
        <p style="text-align: center;">Closing Consultation.<br/>Please do not exit this page.</p>
        
    </div>
    <!-- END MODAL VIEW FOR VIDEO TELEMEDICINE -->
        
    <!-- MODAL VIEW FOR SHARING REPORTS WITH MEMBERS (DOCTORS SIDE ONLY) -->
    <div title="Share With Patient" id="share_modal" style="width: 900px; height: 400px; display: none;">
        <div id="share_files_container" style="overflow: hidden; overflow-x: scroll;">
        </div>
        <link rel="stylesheet" href="css/toggle.css">
        <div style='width:500px;'>
			<button onclick='switchShareType();' id='share-type' style='color:#54BC00;float:left;display:none;'>Member</button>
			<input style='margin-left:50px;display:none;float:left;' type='text' id='search-doctor-share' placeholder='Doctor Name/Email' />
		</div>
        <div style="width: 100px; height: 25px; margin: auto; margin-top: 35px;">
            <button id="shareFilesButton" style="width: 100px; height: 25px; background-color: #22AEFF; border: 0px solid #FFF; border-radius: 5px; outline: none; color: #FFF;">Share</button>
        </div>
    </div>
        
    <style>
        #probe_toggle{
            margin-top: 25px;
            width: 97%;
            height: 35px;
            border: 0px solid #FFF;
            outline: none;
            border-radius: 5px;
            background-color: #54bc00;
            color: #FFF;
        }
        .probe_method_button{
            width: 50px;
            height: 50px;
            border: 1px solid #DDD;
            outline: none;
            border-radius: 50px;
            background-color: #FFF;
            color: #BBB;
            
        }
        .probe_method_button_selected{
            width: 50px;
            height: 50px;
            border: 1px solid #22AEFF;
            outline: none;
            border-radius: 50px;
            background-color: #22AEFF;
            color: #FFF;
            
        }
        .probe_interval_button{
            width: 82px;
            height: 40px;
            border: 1px solid #CCC;
            outline: none;
            background-color: #FFF;
            color: #777;
            margin: none;
            margin-left: -5px;
            margin-top: 5px;
            
        }
        .probe_interval_button_selected{
            width: 82px;
            height: 40px;
            border: 1px solid #22AEFF;
            outline: none;
            background-color: #22AEFF;
            color: #FFF;
            margin: none;
            margin-left: -5px;
            margin-top: 5px;
        }
        #edit_probes_button:disabled{
            cursor: default;
            background-color: #75CEFF;

        }
        #launch_probes_button{
            margin-top: 20px;
            width: 97%;
            height: 35px;
            border: 0px solid #FFF;
            outline: none;
            border-radius: 5px;
            background-color: #22AEFF;
            color: #FFF;
        }
        #probe_add,#probe_cancel{
            margin-top: 10px;
            width: 100%;
            height: 35px;
            border: 0px solid #FFF;
            outline: none;
            border-radius: 5px;
            background-color: #54bc00;
            color: #FFF;
        }
        .probe_info_section{
            width: 94%; 
            margin: auto;
            padding: 2%;
            background-color: #FDFDFD;
            border: 1px solid #F2F2F2;
            border-radius: 5px;
            color: #6A6A6A;
            text-align: center;
        }
        #probe_alert_button{
            width: 6%;
            height: 30px;
            padding-left: 1%;
            padding-right: 1%;
            background-color: #89150B;
            color: #FFF;
            outline: none;
            border-radius: 6px;
            border: 0px solid #FFF;
            margin-left: 1%;
            font-size: 18px;
        }
        #add_probe_button{
            width: 6%;
            height: 30px;
            padding-left: 1%;
            padding-right: 1%;
            background-color: #54BC00;
            color: #FFF;
            border-top-left-radius: 6px;
            border-bottom-left-radius: 6px;
            outline: none;
            border: 0px solid #FFF;
            font-size: 18px;
            margin-right: -5px;
            margin-left: 8px;
            font-weight: bold;
            font-size: 20px;
        }
        #probe_delete_button{
            width: 6%;
            height: 30px;
            padding-left: 1%;
            padding-right: 1%;
            background-color: #D84840;
            color: #FFF;
            border-top-right-radius: 6px;
            border-bottom-right-radius: 6px;
            outline: none;
            border: 0px solid #FFF;
            font-size: 18px;
            margin-right: -5px;
        }
    </style>
    <div id="probe_editor" title="Probes" style="display: none; overflow: hidden;">
        <div id="manage_user_probe" style="display: block; margin: auto; margin-top: 10px; overflow: hidden;">
            <h1 style="color: #444; font-size: 14px; text-align: center; margin-top: -13px;"><span lang="en">Manage Probe</span></h1>
            <div class="probe_info_section" style="height: 320px; margin-top: -10px;">
                <div style="width: 100%; margin: auto; height: 80px;">
                    <button id="standard_probe_button" data-on="0" style="height: 80px; width: 47%; float: left; background-color: #22AEFF; color: #FFF; border: 0px solid #FFF; border-radius: 5px; outline: none;">
                        <i class="icon-signal" style="font-size: 45px;"></i><br/><span lang="en">Use Standard Probe</span>
                    </button>
                    
                    <!--<div style="width: 27%; float: left; margin-right: 40px; margin-left: 5px; height: 24px; padding-top: 6px; text-align: left;">Select Probe:</div>-->
                    <div id="select_probe_section" style="height: 73px; padding-top: 7px; width: 47%; float: right; background-color: #22AEFF; color: #FFF; border-radius: 5px;">
                        <span lang="en">Select Probe</span><br/>
                        <div style="width: 100%; height: 12px; margin-top: -7px; margin-bottom: 7px;"><i class="icon-chevron-down" style="font-size: 12px;"></i></div>
                        <select id="probe_protocols" style="width: 90%;">
                        </select>
                    </div>
                    
                    <div style="height: 50px; padding-top: 30px; width: 4%; margin-right: 1%; float: right;">
                        or
                    </div>
                </div>
                <!--
                <button id="add_probe_button">+</button>
                <button id="probe_delete_button"><i class="icon-remove"></i></button>
                
                <button id="probe_alert_button"><i class="icon-exclamation"></i></button>
                -->
                <br/>
                <div style="width: 100%; height: 30px; margin-bottom: 20px;">
                    <div style="width: 18%; float: left; margin-right: 20px; margin-left: 5px; height: 24px; padding-top: 6px; text-align: left;"><span lang="en">Select Time</span>:</div>
                    <div style="width: 76%; float: left;">
                        <input id="probe_time5" type="text"  style="width: 17%; height: 18px; float: left;"/>
                        <select id="probe_timezone" style="width: 48%; margin-left: 18px; height: 30px; float: left;">
                            <option value="3" lang="en">US Pacific Time</option>
                            <option value="4" lang="en">US Mountain Time</option>
                            <option value="2" lang="en">US Central Time</option>
                            <option value="1" lang="en">US Eastern Time</option>
                            <option value="5" lang="en">Europe Central Time</option>
                            <option value="6" lang="en">Greenwich Mean Time</option>
                        </select>
                        <select id="probe_language" style="width: 22%; margin-left: 18px; height: 30px; float: left;">
                            <option value="en" lang="en">English</option>
                            <option value="es" lang="en">Spanish</option>
                        </select>
                    </div>
                </div>
                <div style="width: 100%; height: 56px;">
                    <div style="float: left; width: 170px;">
                        <button class="probe_method_button_selected" data-on="1" id="probe_method_phone"><i class="icon-phone" style="font-size: 30px;"></i></button>
                        <button class="probe_method_button" data-on="0" id="probe_method_text"><i class="icon-mobile-phone" style="font-size: 40px;"></i></button>
                        <button class="probe_method_button" data-on="0" id="probe_method_email"><i class="icon-envelope" style="font-size: 30px;"></i></button>
                    </div>
                    <div style="float: right; width: 62%; text-align: right;">
                        <button class="probe_interval_button_selected" data-on="1" id="probe_interval_daily" style="border-top-left-radius: 5px; border-bottom-left-radius: 5px;"><span lang="en">Daily</span></button>
                        <button class="probe_interval_button" data-on="0" id="probe_interval_weekly"><span lang="en">Weekly</span></button>
                        <button class="probe_interval_button" data-on="0" id="probe_interval_monthly"><span lang="en">Monthly</span></button>
                        <button class="probe_interval_button" data-on="0" id="probe_interval_yearly" style="border-top-right-radius: 5px; border-bottom-right-radius: 5px;"><span lang="en">Yearly</span></button>
                    </div>
                </div>
                <div style="width: 100%; text-align: left;"><span lang="en">Expectation</span>:</div>
                <div style="width: 73%; padding-top: 8px; padding-left: 10px; text-align: left; background-color: #FFF; border: 1px solid #CCC; border-radius: 5px; margin: auto; margin-top: 8px;">
                    <div id="probe_alert_chart_button" style="width: 25px; height: 22px; padding-top: 3px; margin-right: 5px; border-radius: 25px; background-color: #F8F8F8; border: 1px solid #DDD; float: right; color: #22AEFF; text-align: center;">
                        <i class="icon-bar-chart"></i>
                    </div>
                    <style>
                        input[type='range'] {  
                            -webkit-appearance: none;  
                            width: 72px;  
                            border-radius: 8px;  
                            height: 5px;  
                            border: 1px solid #CCC;  
                            background-color: #F8F8F8; 
                            outline: none;
                        }
                        input[type='range']::-webkit-slider-thumb {
                            -webkit-appearance: none;
                            background-color: #E8E8E8;
                            border: 1px solid #999;
                            width: 16px;
                            height: 16px;
                            border-radius: 10px;
                            cursor: pointer;
                            box-shadow: -1px 1px 3px #DDD;
                        }
                    </style>
                    <strong><span lang="en">Start</span>:</strong>&nbsp;&nbsp;&nbsp;<input type="number" id="probe_alert_start_value" style="width: 50px;" /> 
                    &nbsp;<strong><span lang="en">Tolerance</span>:</strong>&nbsp;&nbsp;<input id="probe_alert_tolerance" style="margin-right: 4px;" type="range" value="5" max="40" min="5" /><span id="probe_alert_tolerance_value">5</span>%<br/>
                    <strong><span lang="en">Finish</span>:</strong>&nbsp;<input type="number" id="probe_alert_expected_value" style="width: 50px;" /> <span lang="en">in</span> <input type="number" id="probe_alert_expected_day_1" style="width: 50px;" /> <span lang="en">to</span> <input type="number" id="probe_alert_expected_day_2" style="width: 50px;" /> <span lang="en">days</span>.
                </div>
                <!--<br/>
                <div style="width: 27%; float: left; margin-right: 40px; margin-left: 5px; height: 24px; padding-top: 6px; text-align: left;">Select Method: </div>
                <select id="probe_method" style="width: 64%; height: 30px;">
                    <option value="0">N/A</option>
                    <option value="1">Text Message</option>
                    <option value="2">Phone Call</option>
                    <option value="3">Email</option>
                </select>
                <br/>
                <div style="width: 27%; float: left; margin-right: 40px; margin-left: 5px; height: 24px; padding-top: 6px; text-align: left;">Select Interval: </div>
                <select id="probe_interval" style="width: 64%; height: 30px;">
                    <option value="0">N/A</option>
                    <option value="1">Daily</option>
                    <option value="7">Weekly</option>
                    <option value="30">Monthly</option>
                    <option value="365">Yearly</option>
                </select>-->
                <br/>
                
                <!--<button id="save_probes_button" style="margin-right: 4%; margin-left: 5px; background-color: #54BC00;"><i class="icon-lock"></i>&nbsp;&nbsp;Save</button>
                <button id="edit_probes_button"><i class="icon-pencil"></i>&nbsp;&nbsp;Edit Probes</button>-->
            </div>
            <div class="probe_info_section" style="height: 135px; margin-top: 10px;">
                <span lang="en">Current Probe Status</span>: 
                <link rel="stylesheet" href="css/toggle.css" />
                <div style="width: 100px; height: 30px; margin: auto; margin-top: 10px;">
                    <input id="probeToggle" class="h2m-toggle h2m-toggle-round" type="checkbox" />
                    <label id="pTL" for="probeToggle" data-on="Yes" data-off="No" style="float: left;"></label>
                    <div id="probeToggleLabel" style="color: #54BC00; float: right; width: 30px; height: 30px; font-size: 22px; text-align: right; margin-top: 7px;" lang="en">On</div>
                </div>
                <!--<button id="edit_probes_button"><i class="icon-pencil"></i>&nbsp;&nbsp;Edit Probe</button>-->
                <button id="launch_probes_button"><i class="icon-bolt"></i>&nbsp;&nbsp;<span lang="en">Launch Probe Now</span></button>
            </div>
        </div>
        <div id="view_probes" style="display: none; overflow: scroll;">
            <style>
                .probes_row{
                    width: 94%;
                    height: 25px;
                    padding-top: 8px;
                    padding-bottom: 2px;
                    padding-left: 3%;
                    padding-right: 1%;
                    background-color: #F2F2F2;
                    border: 1px solid #E8E8E8;
                    border-radius: 6px;
                    margin: auto;
                    margin-bottom: 8px;
                }
                .no_probes_notification{
                    width: 94%;
                    height: 50px;
                    padding-top: 8px;
                    padding-bottom: 2px;
                    padding-left: 3%;
                    padding-right: 1%;
                    background-color: #F2F2F2;
                    border: 1px dashed #E8E8E8;
                    border-radius: 6px;
                    margin: auto;
                    margin-bottom: 8px;
                    text-align: center;
                    color: #777;
                }
                .probes_edit_button{
                    width: 6%;
                    height: 25px;
                    padding-left: 1%;
                    padding-right: 1%;
                    background-color: #54bc00;
                    color: #FFF;
                    border-radius: 6px;
                    outline: none;
                    border: 0px solid #FFF;
                    margin-top: -5px;
                }
                .probes_delete_button{
                    width: 6%;
                    height: 25px;
                    padding-left: 1%;
                    padding-right: 1%;
                    background-color: #D84840;
                    color: #FFF;
                    border-radius: 6px;
                    outline: none;
                    border: 0px solid #FFF;
                    margin-top: -5px;
                    margin-left: 5px;
                }
                #probe_question_previous,#probe_question_next{
                    width: 27px;
                    height: 27px;
                    border-radius: 27px;
                    border: 0px solid #FFF;
                    background-color: #22AEFF;
                    color: #FFF;
                    outline: none;
                }
            </style>
            <div id="probes_container" style="width: 100%; height: 470px; overflow: scroll">
                <div class="probes_row">
                    <div style="float: left; width: 40%; margin-right: 3%; overflow: hidden; white-space: nowrap; text-overflow: ellipsis; font-weight: bold;">Thjekwhalkjhgakwejghkwjebgjkwebg</div>
                    <div style="float: left; width: 40%; margin-right: 3%; overflow: hidden; white-space: nowrap; text-overflow: ellipsis;">KEWGJKNKWJENgkwjengkajnwlgkjngkejbgerbgkjebrgkhebg</div>
                    <button class="probes_edit_button"><i class="icon-pencil"></i></button>
                    <button class="probes_delete_button">X</button>
                </div>
                <div class="probes_row"></div>
                <div class="probes_row"></div>
                <div class="probes_row"></div>
            </div>
            <!--<button id="add_probe_button" style="width: 99%; height: 35px; background-color: #54bc00; border-radius: 5px; border: 0px solid #FFF; outline: none; color: #FFF; margin-left: 2px;">+</button>-->
            <button id="add_probe_button_back" style="width: 99%; height: 35px; background-color: #22AEFF; border-radius: 5px; border: 0px solid #FFF; outline: none; color: #FFF; margin-left: 2px; margin-top: 10px;">Back</button>
        </div>
        <div id="add_probe" style="display: none; overflow: scroll;">
            <div style="width: 20%; float: left; margin-right: 4%; height: 24px; padding-top: 6px;"><span lang="en">Name</span>: </div>
            <input type="text" id="probe_name_edit" style="width: 73%; float: left;" />
            <br/>
            <div style="width: 20%; float: left; margin-right: 4%; height: 24px; padding-top: 6px;"><span lang="en">Description</span>: </div>
            <input type="text" id="probe_description" style="width: 73%; float: left;" />
            <br/>
            
            <div style="text-align: center; width: 100%; height: 30px; margin-top: 55px;">
                <button id="probe_question_previous"><i class="icon-chevron-left"></i></button>
                <span id="probe_question_label" style="font-size: 14px; color: #777;">&nbsp;&nbsp;<span lang="en">Question</span> 1&nbsp;&nbsp;</span>
                <button id="probe_question_next"><i class="icon-chevron-right"></i></button>
            </div>
            <br/>
            <div style="width: 19%; float: left; margin-right: 2%; height: 24px; padding-top: 6px;"><span lang="en">Title></span>:</div>
            <input id="probe_question_title" type="text" style="width: 76%; float: left;" />
            <br/>
            <div style="width: 19%; float: left; margin-right: 2%; height: 24px; padding-top: 6px;"><span lang="en">English</span>:</div>
            <input id="probe_question_en" type="text" style="width: 76%; float: left;" />
            <br/>
            <div style="width: 19%; float: left; margin-right: 2%; height: 24px; padding-top: 6px;"><span lang="en">Spanish</span>:</div>
            <input id="probe_question_es" type="text" style="width: 76%; float: left;" />
            <br/>
            <div style="width: 19%; float: left; margin-right: 2%; height: 24px; padding-top: 6px;"><span lang="en">Min Value</span>:</div> 
            <input type="number" id="probe_question_min" style="width: 12%; float: left;" />
            <div style="width: 15%; float: left; margin-right: 2%; height: 24px; padding-top: 6px; margin-left: 10px;"><span lang="en">Max Value</span>:</div>
            <input type="number" id="probe_question_max" style="width: 12%; float: left;" />
            <div style="width: 7%; float: left; margin-right: 2%; height: 24px; padding-top: 6px; margin-left: 19px;"><span lang="en">Unit</span>:</div>
            <input type="text" id="probe_question_unit" style="width: 16%; float: left;" />
            <br/>
            <div style="width: 19%; float: left; margin-right: 2%; height: 24px; padding-top: 6px;"><span lang="en">Answer Type</span>:</div>
            <select id="probe_question_answer_type" style="width: 78%; float: left;">
                <option value="1" lang="en">Single Digit (0 - 9)</option>
                <option value="2" lang="en">Regular Number</option>
                <option value="3"><span lang="en">Yes</span> / No</option>
            </select>
            <span style="text-align: center;"><span lang="en">Range Units</span>:</span>
            <div id="probe_range_selector" style="width: 100%; height: 80px; float: left; margin-top: 5px;"></div>
            <div style="text-align: center; width: 100%; height: 100px; margin-top: 95px; text-align: center;">
                <button id="probe_add"><i class="icon-ok"></i>&nbsp;&nbsp;<span lang="en">Done</span></button><br/>
                <button id="probe_cancel" style="background-color: #D84840"><i class="icon-remove"></i>&nbsp;&nbsp;<span lang="en">Cancel</span></button>
            </div>
            
        </div>
        <div id="edit_probe_alerts" style="display: none; overflow: scroll;">
            <h1 style="color: #444; font-size: 14px; text-align: center; margin-bottom: -10px;" lang="en">Probe Alerts</h1>
            
            <h1 style="color: #777; font-size: 12px; margin-bottom: -5px; margin-top: -30px;" lang="en">Select Question</h1>
            <select id="probe_alert_question" style="width: 100%;">
            </select>
            <!--<div style="float: right; width: 100%; height: 150px; background-color: #FCFCFC; border: 1px solid #EEE; border-radius: 5px; text-align: center;">
                <div style="font-size: 12px; color: #555; text-align: center; margin-bottom: 5px;">Expectations</div>
                The patient is starting with a value of <input type="number" id="probe_alert_start_value" style="width: 50px; margin-left: 10px;" /><br/> and is expected to reach a value of <input type="number" id="probe_alert_expected_value" style="width: 50px; margin-left: 10px;" /> <br/>in <input type="number" id="probe_alert_expected_day_1" style="width: 50px;" /> to <input type="number" id="probe_alert_expected_day_2" style="width: 50px;" /> days.
            </div>-->
            <h1 style="color: #777; font-size: 12px; margin-bottom: -10px;"><span lang="en">Tolerance</span>:</h1>
            <div style="width: 100%;">
                <div style="width: 7%; float: left;">5%</div>
                <!--<input id="probe_alert_tolerance" type="range" style="width: 86%; float: left;" max="40" min="5" />-->
                <div style="width: 7%; float: left; text-align: right;">40%</div>
            </div>
            <div style="width: 95px; height: 100px; margin-top: 10px; margin-top: 30px; float: right;">
                <div style="width: 20px; height: 20px; border-radius: 20px; background-color: #FFF; float: left; margin-top: 10px; border: 1px solid #AAA;"></div>
                <div style="width: 66px; height: 22px; margin-left: 5px; float: left; margin-top: 10px;" ><span lang="en">Good</span></div>
                <div style="width: 20px; height: 20px; border-radius: 20px; background-color: rgba(194, 206, 218, 1.0); float: left; margin-top: 10px; border: 1px solid #AAA;"></div>
                <div style="width: 66px; height: 22px; margin-left: 5px; float: left; margin-top: 10px;" ><span lang="en">Tolerated</span></div>
                <div style="width: 20px; height: 20px; border-radius: 20px; background-color: rgba(165, 175, 185, 1.0); float: left; margin-top: 10px; border: 1px solid #AAA;"></div>
                <div style="width: 66px; height: 22px; margin-left: 5px; float: left; margin-top: 10px;" ><span lang="en">Alert</span></div>
            </div>
            <div style="width: 400px; height: 150px; margin: auto; margin-top: 34px;">
                <canvas id="probe_alert_graph" width="380" height="150">

                </canvas>
            </div>
            <!--<button id="probe_alert_clear_button" style="width: 99%; height: 35px; background-color: #D84840; border-radius: 5px; border: 0px solid #FFF; outline: none; color: #FFF; margin-left: 2px; margin-top: 100px;">Clear All</button>-->
            <button id="probe_alerts_save_button" style="width: 99%; height: 35px; background-color: #54BC00; border-radius: 5px; border: 0px solid #FFF; outline: none; color: #FFF; margin-left: 2px; margin-top: 10px;">Save</button>
            <button id="probe_alerts_button_back" style="width: 99%; height: 35px; background-color: #22AEFF; border-radius: 5px; border: 0px solid #FFF; outline: none; color: #FFF; margin-left: 2px; margin-top: 10px;">Back</button>
        </div>
        
    </div>
        
    <!-- CONNECT MEMBER MODAL WINDOW -->
    <div id="checkout_ask_window" style="display: none; text-align: center;" title="Checkout?">
        <br/>Would you like to checkout this patient now?
        <br/><br/><br/>
        <div style="width: 240px; height: 30px; margin: auto;">
            <button id="checkout_ask_no_button" style="width: 100px; height: 25px; color: #FFF; background-color: #D84840; border: 0px solid #FFF; outline: none; border-radius: 5px;">
                No
            </button>
            <button id="checkout_ask_yes_button" style="width: 100px; height: 25px; color: #FFF; background-color: #54BC00; border: 0px solid #FFF; outline: none; border-radius: 5px; margin-left: 30px;">
                Yes
            </button>
        </div>
    </div>
    <div id="connectMemberDialog" title="Connect New Member" style="width: 600px; height: 600px; display: none">
        <div id="connectMemberStep1" style="width: 100%; height: 460px; margin-top: 20px; display: block;">
            <span style="color: #54BC00; font-size: 18px;">1. Select member to connect</span>
            <style>
                .connectMemberRow{
                    width: 486px; 
                    height: 38px;
                    padding: 6px;
                    color: #333;
                    border: 1px solid #E6E6E6;

                }
                .connectMemberRow_bg1{
                    background-color: #FFF;
                }
                .connectMemberRow_bg2{
                    background-color: #F2F9EC;
                }
                .connectMemberRow span{
                    color: #777;
                    font-size: 12px;
                    margin-top: -10px;
                }
                .connectMemberRow:hover{
                    background-color: #54BC00;
                    border: 1px solid #54BC00;
                    color: #FFF;
                    cursor: pointer;
                }
                .connectMemberRow:hover span{
                    color: #FFF;
                }

                .connectMembersSearchBarButton{
                    width: 70px;
                    height: 30px;
                    background-color: #FAFAFA;
                    outline: 0px;
                    border: 1px solid #E7E7E7;
                    color: #7A7A7A;
                    border-top-right-radius: 5px;
                    border-bottom-right-radius: 5px;
                    text-align: center;
                    font-size:0.7em;
                    padding: 4px 8px 4px 8px;
                }


            </style>
            <div class="controls" style="width: 500px; margin: auto; margin-top: 20px;margin-bottom: 8px;">
                <input class="span7" id="connectMembersSearchBar" style="float: left; width: 430px; border-top-right-radius: 0px; border-bottom-right-radius: 0px;" lang="en" placeholder="Search Member (Name or Email)" type="text">
                <input class="btn btn-default connectMembersSearchBarButton" id="connectMembersSearchBarButton" lang="en" type="button" value="Search" />
            </div>
            <div id="connectMemberTable" style="border-radius: 5px; width: 500px; height: 400px; margin: auto; overflow: hidden; overflow-y: auto; margin-top: 15px;">

            </div>
        </div>
        <div id="connectMemberStep2" style="width: 100%; height: 460px; margin-top: 20px; display: none; padding: 0px;">
            <span style="color: #54BC00; font-size: 18px;">2. Share Reports</span>
            <style>
                ::-webkit-scrollbar {
                    -webkit-appearance: none;
                    width: 7px;
                }
                ::-webkit-scrollbar-thumb {
                    border-radius: 4px;
                    background-color: rgba(0,0,0,.15);
                    -webkit-box-shadow: 0 0 1px rgba(255,255,255,.5);
                }
            </style>
            <div id="share_files_container2" style="overflow-y: hidden; overflow-x: scroll; width: 100%; height: 477px;">
            </div>
            <div style="width: 315px; height: 30px; margin: auto;">
                <button id="connectMemberSharePrevButton" style="width: 150px; height: 30px; background-color: #54BC00; outline: none; border: 0px solid #000; color: #FFF; border-radius: 5px; float: left;">Prev</button>
                    <button id="connectMemberShareNextButton" style="width: 150px; height: 30px; background-color: #54BC00; outline: none; border: 0px solid #000; color: #FFF; border-radius: 5px;float: right;">Next</button>
            </div>
        </div>
        <div id="connectMemberStep3" style="width: 100%; height: 460px; margin-top: 20px; display: none;">
            <span style="color: #54BC00; font-size: 18px;">3. Member Details</span>
            <div style="width: 500px; margin: auto; margin-top: 20px;">
                <div style="width: 100%; height: 40px;">
                    <div style="width: 60px; float: left; color: #666; padding-top: 5px;">Email:</div> <input type="text" id="connectMemberEmail" placeholder="Member Email" style="float: left; width: 400px;" />
                </div>
                <div style="width: 100%; height: 40px;">
                    <div style="width: 60px; float: left; color: #666; padding-top: 5px;">Phone:</div> <input type="tel" id="connectMemberPhone" placeholder="Member Phone Number" style="float: left; width: 400px;" />
                </div>
                <div style="width: 100%; height: 40px; margin-top: 25px;">
                    <div style="width: 300px; height: 15px; padding: 7px; background-color: #FAFAFA; border-radius: 15px; border: 1px solid #DDD; margin: auto;">
                        <button id="connectMemberSubscribeButton" style="width: 15px; height: 15px; outline: none; border: 1px solid #DDD; background-color: #FFF; border-radius: 15px; float: left; color: #54BC00; font-size: 12px;">
                        </button>
                        <div style="color: #666; padding-left: 17px; float: left; margin-top: -2px;">Subscribe this member to a probe</div>
                    </div>
                </div>
                <div id="connectMembersProbeSection" style="height: 200px; margin-top: 25px; display: none;">
                    <div style="width: 27%; float: left; margin-right: 40px; margin-left: 5px; height: 24px; padding-top: 6px; text-align: left;">Select Probe:</div>
                    <select id="connectMember_probe_protocols" style="width: 57%; height: 30px;">
                    </select>
                    <button id="connectMember_edit_probes" style="width: 5%; height: 29px; margin-top: -10px; margin-left: 4px; border: 0px solid #FFF; outline: none; background-color: #54BC00; color: #FFF; border-radius: 5px;">
                        <i class="icon-pencil"></i>
                    </button>
                    <br/>
                    <div style="width: 27%; float: left; margin-right: 40px; margin-left: 5px; height: 24px; padding-top: 6px; text-align: left;">Select Time:</div>
                    <input id="connectMember_probe_time" type="text"  style="width: 61%; height: 18px;"/>
                    <br/>
                    <div style="width: 27%; float: left; margin-right: 40px; margin-left: 5px; height: 24px; padding-top: 6px; text-align: left;">Select Timezone: </div>
                    <select id="connectMember_probe_timezone" style="width: 64%; height: 30px;">
                        <option value="3">US Pacific Time</option>
                        <option value="4">US Mountain Time</option>
                        <option value="2">US Central Time</option>
                        <option value="1">US Eastern Time</option>
                        <option value="5">Europe Central Time</option>
                        <option value="6">Greenwich Mean Time</option>
                    </select>
                    <br/>
                    <div style="width: 27%; float: left; margin-right: 40px; margin-left: 5px; height: 24px; padding-top: 6px; text-align: left;">Select Method: </div>
                    <select id="connectMember_probe_method" style="width: 64%; height: 30px;">
                        <option value="1">Text Message</option>
                        <option value="2">Phone Call</option>
                        <option value="3">Email</option>
                    </select>
                    <br/>
                    <div style="width: 27%; float: left; margin-right: 40px; margin-left: 5px; height: 24px; padding-top: 6px; text-align: left;">Select Interval: </div>
                    <select id="connectMember_probe_interval" style="width: 64%; height: 30px;"> 
                        <option value="1">Daily</option>
                        <option value="7">Weekly</option>
                        <option value="30">Monthly</option>
                        <option value="365">Yearly</option>
                    </select>
                </div>
            </div>
            <div style="width: 450px; height: 30px; margin: auto; text-align: center;">
                <button id="connectMemberCheckoutPrevButton" style="width: 150px; height: 30px; background-color: #54BC00; outline: none; border: 0px solid #000; color: #FFF; border-radius: 5px; margin: auto;">Prev</button>
                <button id="connectMemberCheckoutButton" style="width: 150px; height: 30px; background-color: #54BC00; outline: none; border: 0px solid #000; color: #FFF; border-radius: 5px; margin-left: 15px;">Next</button>
            </div>
        </div>
        <div id="connectMemberStep4" style="width: 100%; height: 460px; margin-top: 20px; display: none;">
            <span style="color: #54BC00; font-size: 18px;">4. Billing</span>
            <div style="width: 500px; margin: auto; margin-top: 20px; text-align: center;">
                <button id="connectMemberPayNow" style="width: 135px; height: 25px; background-color: #54BC00; color: #FFF; border: 0px solid #FFF; border-top-left-radius: 5px; border-bottom-left-radius: 5px; outline: none; margin-bottom: 7px;" data-on="1">
                    Doctor Pay Now
                </button>
                <button id="connectMemberCardPayNow" style="width: 135px; height: 25px; background-color: #F8F8F8; color: #555; border: 1px solid #DDD; outline: none; margin-bottom: 7px;" data-on="0">
                    Member Pay Now
                </button>
                <button id="connectMemberAssignPaymentToPatient" style="width: 135px; height: 25px; background-color: #F8F8F8; color: #555; border: 1px solid #DDD; border-top-right-radius: 5px; border-bottom-right-radius: 5px; outline: none; margin-bottom: 7px;" data-on="0">
                    Assign Payment To Patient
                </button>
                <div id="connectMemberPayNowSection">
                    <div style="text-align: center; width: 100%; height: 19px;" id="creditsLabel">You have $<span id="numDoctorCredits"><?php echo ($credits/100); ?></span> left</div>
                    <div id="creditsLoadingBar" style="width: 100%; height: 19px; text-align: center; display: none;">
                        <img src="images/load/8.gif" />
                    </div>
                    
                    
                    <button id="purchaseMoreCreditsButton" style="width: 100%; height: 30px; border: 0px solid #FFF; background-color: #54BC00; color: #FFF; border-radius: 5px; color: #FFF; outline: none;" data-on="0">
                        Purchase More Credits <i class="icon-caret-down"></i>
                    </button>
                    <div id="connectMemberPurchaseCredits" style="display: none;">
                        <div style="width: 100%; height: 40px; margin-top: 20px;">
                            <div style="width: 200px; float: left; color: #666; padding-top: 5px;">Amount:</div> 
                            <input type="number" min="1" id="connectMemberNumCredits" placeholder="Amount to Purchase" style="float: left; width: 260px;" />
                        </div>
                        <div style="width: 100%; height: 40px;">
                            <div style="width: 200px; float: left; color: #666; padding-top: 5px;">Credit Card Number:</div> <input type="text" id="connectMemberCreditCard" placeholder="Credit Card Number" style="float: left; width: 260px;" />
                        </div>
                        <div style="width: 100%; height: 40px;">
                            <div style="width: 200px; float: left; color: #666; padding-top: 5px;">Expiration Date:</div> <input type="month" id="connectMemberExpDate" style="float: left; width: 260px;" />
                        </div>
                        <div style="width: 100%; height: 40px;">
                            <div style="width: 200px; float: left; color: #666; padding-top: 5px;">CVC:</div> <input type="password" id="connectMemberCVC" placeholder="CVC" style="float: left; width: 260px;" />
                        </div>
                    
                        <div style="width: 200px; height: 30px; margin: auto; margin-top: 15px;">
                            <button id="connectMemberPurchaseTokensButton" style="width: 200px; height: 30px; background-color: #54BC00; outline: none; border: 0px solid #000; color: #FFF; border-radius: 5px; margin: auto;">
                                Purchase Credits
                            </button>
                        </div>
                    </div>
                    
                   <center><div id="probe-review-section" style="height:30px;display:none;background-color:#54BC00;border-radius:5px;margin-top:10px;width: 94%; margin: auto;padding: 2%;">
									<div>
										<span id="review-cc-number" style='float:left;margin-left:30px;'></span>
										<button onclick="change_cards();" class="btn btn-default" style="float:right;border-radius:10px;">Change Card</button>
									</div>
								</div></center>
								</div></center>
                
                <div id="connectMemberPayNowSectionCard" style='display:none;'>
						<div class="probe_cc_section" style="height:115px;">
							<style>
								.credit_card_row{
									background-color: #FBFBFB;
									color: #222;
									border: 1px solid #E6E6E6;
									width: 96%;
									height: 35px;
									padding: 4px;
								}
							</style>
							<div id="credit_cards_container" style="width: 300px; margin-left: auto; margin-right: auto; height: 0px; overflow: scroll;display:none;">
							<i class="fa fa-spinner fa-spin"></i>
							</div>
							<div style="margin-top: 10px; width: 100%; margin-left: auto; margin-right: auto;">
								<script>
								function isNumberKey(evt)
								{
									var charCode = (evt.which) ? evt.which : event.keyCode
									if (charCode > 31 && (charCode < 48 || charCode > 57))
										return false;
									return true;
								}    
								</script>
								<div style='width:100%;height:150px;margin-top:100px;'>
									<center>
									<input type="text" onkeypress="return isNumberKey(event)" id="credit_card_number" maxLength="16" placeholder="Enter card number" style="width: 220px; height: 30px; border-radius: 5px;">
									<input id="credit_card_csv_code" type="text" onkeypress="return isNumberKey(event)" id="csv_code" maxLength="3" placeholder="CSV" style="width: 85px; height: 30px; border-radius: 5px;">
									<div>
										<div style="margin-left:117px;float:left;color: #969696; width: 80px; text-align: left; padding-left: 5px; border-top-left-radius: 5px; border-bottom-left-radius: 5px; border: 1px solid #CACACA; height: 30px; padding-top: 5px; border-right: 0px solid #FFF;" lang="en">Exp. Date:</div>
										<input id="credit_card_exp_date" type="month" style="float:left;width: 155px; height: 27px; font-size: 12px; border-radius: 0px; border-left: 0px solid #FFF; border-top-right-radius: 5px; border-bottom-right-radius: 5px;" />
									</div>
									<button onclick="add_credit_card2();" id="add_card_button" style="display:none;margin-top:3px;margin-left:-90px;width: 100px; height: 30px; background-color: #52D859; border-radius: 0px; border: 0px solid #FFF; color: #FFF; outline: 0px; border-radius: 5px;" lang="en">Add Card</button>
									</center>
								</div>
								</div>
								</div>
								
								
                    </div>
                
                <div style="width: 100%; height: 15px; margin-top: 30px;">For how many months would you like to run this probe?</div>
                <select id="connectMemberMonths" style="margin-top: 10px;" id="month-count">
                    <option value="1">1 Month</option>
                    <option value="2">2 Months</option>
                    <option value="3">3 Months</option>
                    <option value="4">4 Months</option>
                    <option value="5">5 Months</option>
                    <option value="6">6 Months</option>
                    <option value="7">7 Months</option>
                    <option value="8">8 Months</option>
                    <option value="9">9 Months</option>
                    <option value="10">10 Months</option>
                    <option value="11">11 Months</option>
                    <option value="12">12 Months</option>
                </select>
                
                <div style="width: 100%; height: 15px; margin-top: 10px;">Cost: <span style="font-weight: bold">$<span id="costCredits">10</span></span></div>
                
                <div style="width: 150px; height: 30px; margin: auto; margin-top: 20px;">
                        <button id="connectMemberFinishButton" style="width: 150px; height: 30px; background-color: #54BC00; outline: none; border: 0px solid #000; color: #FFF; border-radius: 5px;float: right;">Finish</button>
                </div>
                <div id="connectMemberFinishCode" style="width: 100%; height: 100px; padding-top: 80px; margin-top: 10px; display: none; text-align: center;">
                    <span style="color: #333; font-size: 18px;">Member Code: <span style="color: #54BC00;">X10-PFe8Dfi</span></span><br/>
                    <span style="color: #888;">Please provide this code to the member.</span>
                </div>
            </div>
        </div>
        <div id="connectMemberFinalStep" style="width: 100%; height: 260px; padding-top: 200px; margin-top: 20px; display: none; text-align: center;">
            <span style="color: #333; font-size: 18px;">Member Code: <span style="color: #54BC00;">X10-PFe8Dfi</span></span><br/>
            <span style="color: #888;">Please provide this code to the member.</span>
        </div>
    </div>
        
        
        
	<!--- VENTANA MODAL  This has been added for enabling blind report access ---> 
	 
   	 <button id="BotonModal" data-target="#header-modal0" data-toggle="modal" class="btn btn-warning" style="display: none;" lang="en">Modal with Header</button> 
   	  <div id="header-modal0" class="modal fade hide" style="display: none;" aria-hidden="true">
         <div class="modal-header" lang="en">
             <button class="close" type="button" data-dismiss="modal">×</button>
                  To unlock please see below options
         </div>
         <div class="modal-body">
         	 <p>----------------------------------------------------------------------------------------------------</p>
             <p id="Thisreport" lang="en">**Click on "This report" in case you want to unlock only this report.**</p>
             
             <p id="Allreport" lang="en">**Click on "All reports" in case you want to unlock all reports of this user.**</p>
         
			 <p id="TextoSend" style="text-align:center;"></p>
		     <p>----------------------------------------------------------------------------------------------------</p>
         </div>
		 
         <input type="hidden" id="Idpin">
        <!-- <input type="hidden" id="docId" value="<?php echo $IdMed; ?>"/> -->
         <input type="hidden" id="userId" value="<?php echo $IdUsu; ?>" />
         <div class="modal-footer">
	         <input type="button" class="btn btn-success" value="This report" id="ConfirmaLink">
	         <input type="button" class="btn btn-success" value="All reports" id="ConfirmaLinkAll">
             <a href="#" class="btn btn-primary" data-dismiss="modal" id="CloseModallink" lang="en">Close</a>
         </div>
      </div>  
	  <!--- VENTANA MODAL  ---> 	
	 <!--- VENTANA MODAL  This has been added to show individual message content which user click on the inbox messages ---> 
	 
   	 <button id="message_modal" data-target="#header-message" data-toggle="modal" class="btn btn-warning" style="display: none;" lang="en">Modal with Header</button> 
   	  <div id="header-message" class="modal hide" style="display: none;" aria-hidden="true">
         <div class="modal-header" lang="en">
             <button class="close" type="button" data-dismiss="modal">×</button>
                  Message Details
         </div>
         <div class="modal-body">
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
        <!-- <input type="hidden" id="docId" value="<?php echo $IdMed; ?>"/> -->
         <input type="hidden" id="userId" value="<?php echo $IdUsu; ?>" />
         <div class="modal-footer">
		     <input type="button" class="btn btn-info" value="Send message" id="sendmessages_inbox">
             <input type="button" class="btn btn-success" value="Attach" id="Attach">	
	         <input type="button" class="btn btn-success" value="Reply" id="Reply">			 
	         <a href="#" class="btn btn-primary" data-dismiss="modal" id="CloseMessage" lang="en">Close</a>
         </div>
      </div>  
	  <!--- VENTANA MODAL  ---> 	
		<!-- Modal from audio files support -->
	  <button id="message_modal_audio" data-target="#header-message_audio" data-toggle="modal" class="btn btn-warning" style="display: none;" lang="en">Modal with Header</button> 
   	  <div id="header-message_audio" class="modal hide" style="display: none;" aria-hidden="true">
         <div class="modal-header" lang="en">
             <button class="close" type="button" data-dismiss="modal">×</button>
                  Audio Dictation
         </div>
         <div class="modal-body" style="text-align: center;font: 14pt Arial, sans-serif; background: lightgrey;">
			<canvas id="analyser" width="520" height="200"></canvas><br>
			<!--<canvas id="wavedisplay" width="520" height="200"></canvas><br>
			<img id="record" width="80" height="80" src="images/mic128.png" onclick="toggleRecording(this);"><br><br>
			<img src="images/save.svg" onclick="saveAudio();"><br>-->
			<div style="text-align:center;margin-top:10px;margin-bottom:10px;"><p class="Timer"><p></div>
			<div id="wait_audio" style="margin-top:10px;margin-left:50px; display: none;"> <p lang="en"> Audio data transferring. Please wait  </p>
			<img src="images/load/8.gif" alt="" ></div>
			<input id="record" type="button" style="margin-top:10px;margin-right:10px" class="btn btn-success" value="Start">	
	        <input id="saveaudio" type="button" style="margin-top:10px;margin-right:10px" class="btn btn-success" value="Save">		
			
		 </div>
        
        <!-- <input type="hidden" id="docId" value="<?php echo $IdMed; ?>"/> -->
         <input type="hidden" id="userId" value="<?php echo $IdUsu; ?>" />
         <div class="modal-footer">		     		 
	         <a href="#" class="btn btn-primary" data-dismiss="modal" id="closeaudiotab" lang="en">Close</a>
         </div>
      </div>  
	  <!--- audio files support  ---> 	

	  

        <div id="evolution_modal" title="Evolution Input" style="text-align: center;font: 14pt Arial, sans-serif; overflow: hidden; display: none;">
			<center>
				<table style="background:transparent; height:150px; color: #666; font-size: 16px; margin-top: 10px;" >
					<tr>
						<td style="height:16px; padding-top: 8px; vertical-align: top; width: 15%;" lang="en">Date : </td>
						<td style="height:24px; width: 85%;"><!--<input id="evolution_date"  />--><input id="evolution_date" type="date" style="width: 93%; margin-left: 2%;" /></td>
					</tr>
					<tr>
					</tr>
					<tr>
						<td style="height:16px; padding-top: 8px; vertical-align: top; width: 15%;" lang="en">Note : </td>
						<td style="height:24px; width: 85%;"><textarea rows="5" cols="150" style="width:93%;resize:none; margin-left: 2%;" id="evolution_text"></textarea></td>
					</tr>
				</table>
                <hr style="margin-top: 14px; margin-left: -30px; width: 600px;"/>
                <a href="#" class="btn btn-primary" style="margin-top: -8px; float: right; margin-left: 15px; margin-right: 8px; color: #FFF;" id="CloseEvolution" lang="en">Close</a>
                <a href="#" class="btn btn-success" style="margin-top: -8px; float: right; color: #FFF;" id="AddEvolution" lang="en">Add Data</a>
		 </div>
	  <!--- Evolution Support  --->
        
    <!-- Start of Modal for PhoneNotes -->
    <button id="modal_phoneNotes" data-target="#header-phoneNotes" data-toggle="modal" class="btn btn-warning" style="display: none;" lang="en">Modal with Header</button> 
   	  <div id="header-phoneNotes" class="modal hide" style="display: none;" aria-hidden="true">
         <div class="modal-header" lang="en">
             <button class="close" type="button" data-dismiss="modal">×</button>
                  <span lang='en'>Notes and Recommendations</span>
         </div>
         <div class="modal-body" style="text-align: center;font: 10pt Arial, sans-serif; background: lightgrey;">
			<center>
				<table style="background:transparent; height:150px;" >

					<tr>
					</tr>
					<tr>
						<td style="height:24px;" lang="en">Findings</td>
                    </tr>    
                    <tr>
						<td style="height:24px;"><textarea rows="5" cols="150" style="width:420px;resize:none" id="note_findings">	</textarea></td>
                    </tr>
                    <tr>
                        <td style="height:24px;" lang="en">Recommendations</td>
                    </tr>    
                    <tr>    
						<td style="height:24px;"><textarea rows="5" cols="150" style="width:420px;resize:none" id="note_recommendations">	</textarea></td>
					</tr>
				</table>
			</center>
		 </div>
        
        
         <div class="modal-footer">	
			 <a href="#" class="btn btn-success" data-dismiss="modal" id="AddNote" lang="en">Add Note</a>
	         <a href="#" class="btn btn-primary" data-dismiss="modal" id="CloseNote" lang="en">Close</a>
         </div>
      </div> 
    <!-- End of MOdal for PhoneNotes -->
        
	  
	  <!-- Modal for Evolution display -->
	  <button id="modal_evolution_display" data-target="#header-evolution_display" data-toggle="modal" class="btn btn-warning" style="display: none; " lang="en">Modal with Header</button> 
   	  <div id="header-evolution_display" class="modal hide" style="display: none;height:530px;width:670px;margin-left:-300px;margin-top:-350px" aria-hidden="true">
         <div class="modal-header" lang="en">
             <button class="close" type="button" data-dismiss="modal">×</button>
                  Patient Evolution
         </div>
         <div class="modal-body" style="text-align: center;font: 14pt Arial, sans-serif; background: lightgrey;">
			<!--<center>-->
			<table>
				<tr>
									
					
					<td><div class="grid-content" id="AreaConten2">
							<img id="ImagenAmp2" src="">
						</div>
					</td>
									
					
			    </tr>
			</table>
             
			<!--</center>-->
 
		 </div>
        
        
         <div class="modal-footer">	
			 
	         <a href="#" class="btn btn-primary" data-dismiss="modal" id="CloseEvolution" lang="en">Close</a>
         </div>
      </div>  
	  <!--- Evolution Support  --->
	  
	  
	  
     	  <!--- History  ---> 
   	  <button id="BotonModal1" data-target="#header-modal1" data-toggle="modal" class="btn btn-warning" style="display: none;" lang="en">Modal with Header</button>
   	  <div id="header-modal1" class="modal fade hide" style="display: none; height:470px; width:800px; margin-left:-400px;" aria-hidden="true">
         <div class="modal-header">
             <button class="close" type="button" data-dismiss="modal">×</button>
                 <div id="InfB" >
	                 	<h4 lang="en">Report Tracking History</h4>
                 </div>
         </div>
		 
						<!--   Pop Up For Maps -->
						<button id="BotonModalMap" data-target="#header-modalMap" data-toggle="modal" class="btn btn-warning" style="display: none;">Modal with Header</button>
							<div id="header-modalMap" class="modal hide" style="display: none; height:450px; width:500px; -webkit-transform:translate3d(0,0,0);" aria-hidden="true">
												
							</div>
		  	
						<!--  End of Pop Up For Maps -->
      
      
         <div class="modal-body" id="ContenidoModal22" style="height:320px;">
			<div id="InfoIDPaciente">
            </div>
			<div id="SeccionBusqueda"> <!--- SECCIÓN DE BÚSQUEDA ---->
		        
				<div id="VacioAUNViewers" style=" width:35%; margin: 0 auto; margin-top:10px; border: 1px SOLID #CACACA; ">
				
					<table class="table table-mod" id="TablaPacMODALViewers">
						 <span id="view-history-load"> 
				<i class="icon-spinner icon-2x icon-spin" style="margin-top: 8px; margin-bottom: -8px; margin-right: 10px;" ></i> <b> Processing request... </b> </span>
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
             <a href="#" class="btn btn-primary" data-dismiss="modal" id="CloseModal22" lang="en">Close</a>
         </div>
      </div>  
	  <!--- Report History  ---> 
  
       <!--   Pop Up For Maps
						<button id="BotonModalMap" data-target="#header-modalMap" data-toggle="modal" class="btn btn-warning" style="display: none;" lang="en">Modal with Header</button>
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
		<?php
            if($MedID > 0)
            {
                echo '<li><a href="MainDashboard.php" lang="en">Home</a></li>';
                
                require("checkPageAccessControl.php");
                
            
                                    
                $arr=checkAccessPage("dashboard.php");
                $arr_d=json_decode($arr, true);

                if((count($arr_d['items'])>0)&&($arr_d['items'][0]['accessid']==1)){ 
                
                    echo '<li><a lang="en" href="dashboard.php"  lang="en">Dashboard</a></li>';
                }
              
                //echo '<li><a href="dashboard.php"  lang="en">Dashboard</a></li>';
                //echo '<li><a href="patients.php"  class="act_link" lang="en">Members</a></li>';
                if ($privilege==1)
                {
                    echo '<li><a href="medicalConnections.php"  lang="en">Doctor Connections</a></li>';
                }
                    echo '<li><a href="PatientNetwork.php"  lang="en">Patient Network</a></li>';
                if ($privilege==1)
                {
                
                    echo '<li><a href="medicalConfiguration.php" lang="en">Configuration</a></li>';
                }
                    echo '<li><a href="logout.php" style="color:yellow;" lang="en">Sign Out</a></li>';
            }
            else
            {
                echo '<li><a href="UserDashboard.php" lang="en">Home</a></li>';
                echo '<li><a href="#" lang="en" class="act_link">Medical Records</a></li>';
                echo '<li><a href="logout.php" style="color:yellow;" lang="en">Sign Out</a></li>';
            }
        ?>
                
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
     
     $sql=$con->prepare("SELECT * FROM usuarios where Identif =?");
	 $sql->bindValue(1, $IdUsu, PDO::PARAM_INT);
	 $q = $sql->execute();
	 
     $row = $sql->fetch(PDO::FETCH_ASSOC);
     
     $Name = $row['Name'];
     $Surname = $row['Surname'];
     
     $Password = $row['IdUsRESERV'];
     if ($Password > ' ') $UConnected=1; else $UConnected = 0;
     
     // Meter tipos en un Array
     $sql=$con->prepare("SELECT * FROM tipopin");
     $q = $sql->execute();
     
     $Tipo[0]='N/A';
     while($row = $sql->fetch(PDO::FETCH_ASSOC)){
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
	  

	
    <!--</div>-->
			
			
     <!--CONTENT MAIN START-->
     <div class="content" >
     	  <!--- VENTANA MODAL  ---> 
   	  <!--<button id="BotonModal" data-target="#header-modal" data-toggle="modal" class="btn btn-warning" style="display: none;">Modal with Header</button>-->
   	  <div id="header-modal3" class="modal hide" style="display: none; height:470px;" aria-hidden="true">
         <div class="modal-header">
             <button class="close" type="button" data-dismiss="modal">×</button>
                 <h4 lang="en">Set Classification</h3>
         </div>
         
         <div class="modal-body" id="ContenidoModal" style="height:320px;">
			<div><h4 lang="en">Report Date: <input id="classification_datepicker">
			</div>
			
           <div class="ContenDinamico" style="width:340px; float:left;">
		     <!--<div id="class_id" style="display:none">
	         <p><H4 lang="en">Class:  </H4>
	               <div class="formRight" style="width:50px;">
		               <select name="Clases" id="Clases" data-placeholder="Select Class (reason for this data ?)" class="chzn-select chosen_select" multiple tabindex="5" >
                            <option value=""></option>
                            <optgroup label="Episodes (user folder)">
                              <option lang="en">Epi 1</option>
                              <option lang="en">Epi 2</option>
                            <optgroup>
                            <optgroup label="Routine / Checks">
                              <option lang="en">Routine / Checks</option>
                            </optgroup>
                            <optgroup label="Isolated Data">
                              <option lang="en">Isolated Data</option>
                            </optgroup>
                            <optgroup label="Drug Related Data">
                              <option lang="en">Drug Related Data</option>
                           </optgroup>
                          </select>
                       </div>   
              <button id="BotonAddClase"  class="btn btn-small" style="" lang="en"><i class="icon-plus-sign"></i>Add New Episode (Class)</button>
 
	         </p>
			 </div>-->
	         <p><H4 lang="en">Type:  </H3>
	         	    <div class="formRight">
		               <select name="Tipos" id="Tipos" data-placeholder="Select Type (is it a report, an image, etc, ?)" class="chzn-select chosen_select" multiple tabindex="5">
                            <option value=""></option>
                            <optgroup label="Imaging Tests">
                              <option lang="en">Epi 1</option>
                              <option lang="en">Epi 2</option>
                            <optgroup>
                            <optgroup label="Lab Tests">
                              <option lang="en">Routine / Checks</option>
                            </optgroup>
                            <optgroup label="Physician Reports">
                              <option lang="en">Isolated Data</option>
                            </optgroup>
                         </select>
                        
                        
                    </div>   

	         </p>
	         <!--<p><H5 lang="en">Clinical Area:  </H3></p>-->
         </div>
             <div id="RepoThumb" style="margin: 40px 0 0 30px; float:left; -webkit-box-shadow: 3px 3px 14px rgba(25, 25, 25, 0.5); -moz-box-shadow:3px 3px 14px rgba(25, 25, 25, 0.5); box-shadow:3px 3px 14px rgba(25, 25, 25, 0.5);"></div>
         </div>
         <input type="hidden" id="queId">
         <div class="modal-footer">
	         <!--<input type="button" class="btn btn-success" value="Confirm" id="ConfirmaLink">--->
             <a href="#" class="btn btn-success" data-dismiss="modal" id="GrabaDatos" lang="en">Update Data</a>
             <a href="#" class="btn btn-primary" data-dismiss="modal" id="CloseModal" lang="en">Close</a>
         </div>
      </div>  
	  <!--- VENTANA MODAL  ---> 
	  
     	  <!--- VENTANA MODAL NUMERO 2 ---> 
   	  <!--<button id="BotonModal" data-target="#header-modal" data-toggle="modal" class="btn btn-warning" style="display: none;">Modal with Header</button>-->
   	  <!-- Adding jquery dialog -->
		 
		 <div id="upload_reports_form" lang="en" title="Upload New Report" style="display: none;">
		 
			<div style="width: 210px; height: 30px; margin: auto;"> 
                <span style="visibility: hidden; color: #22aeff;" id="H2M_Spin_upload"> 
				<i class="icon-spinner icon-2x icon-spin" style="margin-top: 8px; margin-bottom: -8px; margin-right: 10px;" ></i> <b> Processing request... </b> </span>
			</div>
			
			
			<div id="progress"></div>
			
			<div style="width: 600px; height: 25px; color: #777; margin-top: 25px;"> 
                <span style="float: left; margin-top: 6px; margin-right: 50px;" lang="en">Report Date :</span>
                <input type="date" id="datepicker2" style="float: left; width: 300px;" >
			</div>
			
			<div style="width: 600px; height: 25px; color: #777; margin-top: 25px;"> 
                <span style="float: left; margin-top: 6px; margin-right: 50px;" lang="en">Report Type :</span>
				<?php
						echo '<select name="reptype" id="reptype" data-placeholder="Select Type" class="chzn-select chosen_select" style="width: 314px;">';
						$rg=0;
						while ($rg<10)
						{
                                
                                $rg++;
                                $queQuery =$con->prepare('SELECT * FROM tipopin WHERE Agrup = ?');
                                $queQuery->bindValue(1, $rg, PDO::PARAM_INT);
                                $result2 = $queQuery->execute();

                                $row2 = $queQuery->fetch(PDO::FETCH_ASSOC);

                                echo '<optgroup label="'.$row2['GroupName'].'">';

                                $queQuery =$con->prepare('SELECT * FROM tipopin WHERE Agrup = ?');
                                $queQuery->bindValue(1, $rg, PDO::PARAM_INT);
                                $result2 = $queQuery->execute();
                                $tamano = 0;
                                while ($row2 = $queQuery->fetch(PDO::FETCH_ASSOC)) 
                                {
                                    if ($tamano==0) $adiciona = ' (generic) '; else $adiciona = ' ';
                                    $nombreTipo[$tamano]=$row2['NombreEng'].$adiciona;
                                    $valorTipo[$tamano]=$row2['Id'];
                                    echo '<option value='.$valorTipo[$tamano].'>'.$nombreTipo[$tamano].'</option>';
                                    $tamano++;
                                }
                                echo '</optgroup>';
                                  
						}

						echo '</select>';
						echo '</div>';

				?>
			</p>
			
			<form id="upload" action="upload_file.php?queId=<?php echo $IdUsu ;?>&from=<?php echo $IdMEDEmail;?>" method="POST" enctype="multipart/form-data">

			<fieldset>
			
			<input type="hidden" id="MAX_FILE_SIZE" name="MAX_FILE_SIZE" value="300000" />
			<input type="hidden" id="report_date" value="0">
			<input type="hidden" id="report_type" value="0"> <!-- Changed value="0" to value="1" -->

            
            <input type="file" id="fileselect" name="fileselect[]" style="visibility: hidden;" />
			<div style="width: 300px; height: 100px; margin: auto;">
			<div id="messages" style="border: 1px dashed #AAA; border-radius: 5px; width: 300px; height: 100px; display: table-cell; vertical-align: middle; text-align: center; margin-left: auto; margin-right: auto; color: #777;">
			     <p lang="en">No File Selected</p>
			</div>
            </div>
			
			
			
			<!--<div id="submitbutton">
				<button type="submit" class="btn btn-success" style="margin-top:10px">Upload Files</button>
			</div>-->
			
			</fieldset>

			</form>
             <div style="width: 200px; height: 30px; margin: auto;">
                 <button id="choose_report_button" style="width: 200px; height: 30px; background-color: #22AEFF; color: #FFF; outline: 0px; border: 0px solid #FFF; border-radius: 7px; margin-top: 15px;" lang="en">
                    Select File
                 </button>
             </div>
             <br/>
             <span style="visibility: hidden; display: block; color: #D84840; text-align: center;" id="H2M_filetype_error_upload"></span>
		</div>
	  
	  
	  
	  <!-- jquery dialog end -->
	<!--  <div id="header-modal2" class="modal hide" style="display: none; height:470px;" aria-hidden="true">
         <div class="modal-header">
             <button class="close" type="button" data-dismiss="modal">×</button>
                 <h4>Upload New Report</h3>
                 <input type="hidden" id="URLIma" value="zero"/>
         </div>
         
         <!--<div class="modal-body" id="ContenidoModal2" style="height:320px;">
             <div  id="RepoThumb" style="width:70px; float:right; -webkit-box-shadow: 3px 3px 14px rgba(25, 25, 25, 0.5); -moz-box-shadow:3px 3px 14px rgba(25, 25, 25, 0.5); box-shadow:3px 3px 14px rgba(25, 25, 25, 0.5);"></div>
           <div class="ContenDinamico2">
        
           <!-- <a href="#" class="btn btn-success" id="ParseReport" style="margin-top:10px; margin-bottom:10px;">Parse this report now.</a> -->

          <!-- 		<form action="upload_file.php?queId=<?php echo $IdUsu ;?>&from=<?php echo $IdMEDEmail;?>" method="post" enctype="multipart/form-data">
	           		<label for="file">Report:</label>
	           		<input type="file" class="btn btn-success" name="file" id="file" style="margin-right:20px;"><br>


            </div>  

         </div> -->
		
		 
		<!-- <div class="modal-body" id="ContenidoModal12" style="height:320px;">
			
			<div> <span style="margin-left:100px;display:none;color:#22aeff" id="H2M_Spin_upload"> 
				<i class="icon-spinner icon-2x icon-spin" ></i> <b> Processing request... </b> </span>
			</div>
			
			
			<div id="progress"></div>
			
			<p> <H4>Report Date : </H4>
							<input type="text" id="datepicker2" >
			</p>
			
			<p> <H4> Report Type  : </H4>
			
				<?php
						//echo '	         <p><H4>Type:  </H3>';
					/*	echo '	         	    <div class="formRight">';
						echo '		               <select name="reptype" id="reptype" data-placeholder="Select Type (is it a report, an image, etc, ?)" class="chzn-select chosen_select" multiple tabindex="5">';
						echo '                            <option value=""></option>';
						$rg=0;
						while ($rg<10)
						{
							$rg++;
							$queQuery ='SELECT * FROM TipoPin WHERE Agrup = '.$rg;
							$result2 = mysql_query($queQuery);
							$row2 = mysql_fetch_array($result2); 

							echo '                            <optgroup label="'.$row2['GroupName'].'">';

							$queQuery ='SELECT * FROM TipoPin WHERE Agrup = '.$rg;
							$result2 = mysql_query($queQuery);
							$tamano = 0;
							while ($row2 = mysql_fetch_array($result2)) 
							{
								if ($tamano==0) $adiciona = ' (generic) '; else $adiciona = ' ';
								$nombreTipo[$tamano]=$row2['NombreEng'].$adiciona;
								$valorTipo[$tamano]=$row2['Id'];
								echo '	  							<option value='.$valorTipo[$tamano].'>'.$nombreTipo[$tamano].'</option>';
								$tamano++;
							}
							echo '                            </optgroup>';
						}

						echo '                         </select>';
						echo '                    </div>   ';
						echo '';*/
						//echo '	         </p>';*/

				?>	
					
			</p>
			
			<form id="upload" action="upload_file.php?queId=<?php echo $IdUsu ;?>&from=<?php echo $IdMEDEmail;?>" method="POST" enctype="multipart/form-data">

			<fieldset>
			
			<input type="hidden" id="MAX_FILE_SIZE" name="MAX_FILE_SIZE" value="300000" />
			<input type="hidden" id="report_date" value="0">
			<input type="hidden" id="report_type" value="0">

			<div id="upload_rep">
				<label for="fileselect">Select Report to upload:</label>
				<input type="file" id="fileselect" class="btn btn-primary" name="fileselect[]" multiple="multiple" />
				<!--<div id="filedrag">or drop files here</div>-->
	<!--		</div>
			
			<div id="messages">
			<p>File information</p>
			</div>
			
			
			
			<!--<div id="submitbutton">
				<button type="submit" class="btn btn-success" style="margin-top:10px">Upload Files</button>
			</div>-->
			
	<!--		</fieldset>

			</form>

			

			
		 
		 
		 </div>
         <div class="modal-footer">
	         <!--<input type="button" class="btn btn-success" value="Confirm" id="ConfirmaLink">--->
             <!--<a href="#" class="btn btn-success" data-dismiss="modal" id="GrabaDatos">Update Data</a>-->
		<!--	  <input type="button" class="btn btn-success" name="submit" value="Upload" id="upload_report"> 
             <!-- <a href="#" class="btn btn-primary" data-dismiss="modal" id="CloseModal" >Close</a> -->
             
             <!--	</form> -->

      <!--   </div>
		 
		 
		 
       </div> -->
	  <!--- VENTANA MODAL NUMERO 2  ---> 
     
		 <!--Pop up for records board -->
     <div id="header-modal4" class="modal hide" style="display: none; height:700px;width:1380px;margin-left:-700px;margin-top:-350px" aria-hidden="true">
         <div class="modal-header">
             <button class="close" type="button" data-dismiss="modal">×</button>
                 <h4 lang="en">Records Board</h3>
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
	         
             <a href="#" class="btn btn-primary" data-dismiss="modal" id="CloseModal"  lang="en">Close</a>
             
             	

         </div>
     </div>
	   <!--End Pop up for records board -->
     
        <div class="grid" class="grid span4" style="width:1100px; margin: 0 auto; margin-top:30px; padding-top:30px; padding-bottom:30px; padding-left:20px; overflow:auto;">
	    
		<div style="display:none"><a href="#" id="add-regular" lang="en">Add regular notification</a></div>
	    <!--PROGRESS BAR Box Start-->
        <div class="grid" style="width:97%; margin-top:-20px; margin-bottom:10px;" id="encryptbox">
        	<div class="grid-content overflow">
				<div id="progressstatus" style="width:80%; margin:0 auto; text-shadow:none; color:black; text-align:center;" lang="en">Loading and decrypting clinical content please wait...</div>
				<div id="progressbar" style="margin-top:15px; height:20px; width:80%; margin:0 auto; text-align:center;"><div style="margin-left:0px; margin-top:-5px; padding:0px; text-shadow:none; color:white; font-size:10px;" class="progress-label"></div></div> 
      		</div>	
        </div>
        <!--PROGRESS BAR Box Start-->
        <div class="clearfix" style="margin-bottom:20px;"></div>
          <!-- Commented out below the EvolutionToggle line as requirement for Ameridoc -->
	        	 <span class="label label-info" style="left:0px; margin-left:10px; margin-top:40px; font-size:30px;" lang="en">Clinical Records</span>
				<!-- <a href="" id="EvolutionToggle" data-toggle="tab"><span class="label label-info" style="-webkit-animation: glow 2s linear infinite;float:right;margin-right:25px">Evolution</span></a> -->
			     <div class="clearfix" style="margin-bottom:20px;"></div>

		 <!-- image upload changes start -->
		  <?php
		  	$hash = md5( strtolower( trim( $email ) ) );
		  	$avat = 'identicon.php?size=50&hash='.$hash;
			 if(file_exists("PatientImage/".$USERID.".jpg"))
			 {
                $file = "PatientImage/".$USERID.".jpg";
             }
             else if (file_exists("DocPatImage/".$USERID.".jpg")){
               $file = "DocPatImage/".$USERID.".jpg";
             }
			 else
			 {
				$file = "PatientImage/defaultDP.jpg";
			 }
            $style = "max-height: 80px; max-width:80px;";
            $doctorId = -1;
            if(isset($BOTH_SESSION))
            {
                $doctorId = $BOTH_SESSION;
                $queMed = $IdMed;
            }
            //shell_exec("echo '".$pass."' | openssl aes-256-cbc -pass stdin -d -in PackagesTH_Encrypted/".$ImageRaiz.".".$extension." -out temp/".$queMed."/PackagesTH_Encrypted/".$ImageRaiz.".".$extension);
            //echo 'Decrypt_Image.bat PatientImage '.$USERID.'.jpg '.$BOTH_SESSION.' '.$pass.' 2>&1';
		  ?>	
			    <!--<a href="meaningfuluse.php?Acceso=23432&Nombre=<?php //echo $NombreEnt;?>&Password=<?php //echo $PasswordEnt;?>&IdUsu=<?php //echo $USERID;?>&IdMed=<?php //echo $MedID;?>">--><img src="<?php echo $avat; ?>" style="float:right; margin-right:20px; font-size:18px; padding:5px 10px 5px 10px; font-family: “Andale Mono”, AndaleMono, monospace;"/><!--</a>-->
				<table style="margin-left:10px;background-color:transparent;">
				<tr>
				<td style="height:100px;">
				
				<input type="image" src="<?php echo $file;?>" style="<?php echo $style;?>">
				<!--<input type="file" name="file_upload" id="upload_avatar"/>
				<div id="patientImageDiv">
				<img id="patientImage" style="width:0px; height:0px;overflow:hidden;"/>
				</div>-->
						
						<div class="row">
						<label style="display:none;" for="fileToUpload">Select a File to Upload</label><br />
						<input style="display:none;" type="file" name="fileToUpload" id="fileToUpload2" onchange="fileSelected();"/>
						</div>
						<div  style="display:none;" id="fileName"></div>
						<div  style="display:none;" id="fileSize"></div>
						<div  style="display:none;" id="fileType"></div>
						<div class="row">
						<input id="make_upload" style="width:0px;display:none;" type="button" onclick="uploadFile2()" value="Upload" />
						</div>
						<div id="progressNumber"></div>
							
				</td>
				
				<?php
				$current_encoding = mb_detect_encoding($MedUserName, 'auto');
				$show_text = iconv($current_encoding, 'ISO-8859-1', $MedUserName);

				$current_encoding = mb_detect_encoding($MedUserSurname, 'auto');
				$show_text2 = iconv($current_encoding, 'ISO-8859-1', $MedUserSurname); 
				?>
				 
				<td style="height:100px;">
				<span id="NombreComp" style="font: bold 24px Arial, Helvetica, sans-serif; color: #3D93E0; cursor: auto;  margin-left:10px;"><?php echo $MedUserName; ?> <?php echo $MedUserSurname; ?></span>
				
				<?php
				if($IdUsRESERV == ''){
					echo "</br><button style='margin-left:10px;border-radius:5px;' onclick='showTempPassword();' class='btn btn-default'>Display Temporary Password</button>";
				}
				if($checker->status1 == 'true' || $checker->status2 == 'true' || $checker->status3 == 'true' || $checker->status4 == 'true'){
					echo "<div style='margin-left: 15px; margin-top: -15px;font-style: italic;color: #54bc00;'>";
                    $one_rule_is_enough = 0;
                    if($checker->status1 == 'true' && $one_rule_is_enough == 0){
						echo '</br><span>'.$checker->rule1.'</span>';
                        $one_rule_is_enough = 1;
					}
					if($checker->status2 == 'true' && $one_rule_is_enough == 0){
						echo '</br><span>'.$checker->rule2.'</span>';
                        $one_rule_is_enough = 1;
					}
					if($checker->status3 == 'true' && $one_rule_is_enough == 0){
						echo '</br><span>'.$checker->rule3.'</span>';
                        $one_rule_is_enough = 1;
					}
					if($checker->status4 == 'true' && $one_rule_is_enough == 0){
						echo '</br><span>'.$checker->rule4.'</span>';
                        $one_rule_is_enough = 1;
					}
					echo "</div>";
				}
				
				?>
		  		<!-- Commented out below lines by Pallab as we do not need the IDUsFIXED and IdUsFIXEDNAME -->
            <!--    <span id="IdUsFIXED" style="font-size: 12px; color: #3D93E0; font-weight: normal; font-family: Arial, Helvetica, sans-serif; display: block;margin-left:10px;"><?php echo $IdUsFIXED;?></span>
			  	<span id="IdUsFIXEDNAME" style="font-size: 14px; color: GREY; font-weight: bold; font-family: Arial, Helvetica, sans-serif;margin-left:10px; "><?php echo $IdUsFIXEDNAME;?></span><br/> -->
				<span id="email" style="font-size: 14px; color: #5EB529; font-weight: bold; font-family: Arial, Helvetica, sans-serif; margin-left:10px;"><?php echo $email;?></span>
				</td>
				
				</tr>
				</table>
				
	<!-- image upload changes end-->
            
        <!-- Section for starting telemedicine consultation -->
            
        <div id="telemedicine_start" data-desc="<?php echo $telemed_description; ?>" style="display: none;">
            <h2>Telemedicine Consultation</h2>
            You have accepted a telemedicine consultation with this user.
            <h4 id="telemedicine_user_notes_label">User Notes:</h4>
            <span id="telemedicine_user_notes" >
            </span>
            <br/>
            Please review the appropriate medical records and click "Start" below to begin the consultation.
            <br/>
            <button id="start_telemedicine">Start</button>
        </div>
    
        <!-- End section for starting telemedicine consultation -->
	

        <!--NOTES Start-->
   
        <!--Specific Referall Comm Box Start-->
            
            
		<?php
         
		//echo 'referralsection'.$showreferralsection;

		if($showreferralsection!=0) {

	    echo '<div class="grid" style="width:97%;">';
        echo  '<div class="grid-content overflow">';
        echo  '<span class="label label-info" id="EtiTML" style="left:0px; margin-left:0px; margin-top:20px; margin-bottom:20px; font-size:16px; text-shadow:none; text-decoration:none;" lang="en">Referral Communications Area</span>';
		echo  '<i class="icon-spinner icon-spin" id="H2M_Spin"></i>';
		echo '<input type="hidden" id="referral_state" value="'.$referral_stage.'">';
		echo '<input type="hidden" id="reportid_review" value="'.$attachments_dld.'">';
		//echo '<a href="scheduler.php" class="btn" title="Schedule" style="color:black; margin-left:50px; float:right;"><i class="icon-calendar"></i> Schedule using H2M</a>';            

		if($showreferralsection==1){
		if($otherdocname=='' and $otherdocSurname==''){
		echo '<div style="width:90%; margin-top:12px; float:left;"><span class="label label-success" style="font-size:14px;"><span lang="en">Welcome</span> Dr. '.$IdMEDName.' '.$IdMEDSurname.', <span lang="en">this patient has been referred by</span> <i>Dr. '.$otherdocname.' '.$otherdocSurname.'.</i></span></div>';

		}else{
		echo '<div style="width:90%; margin-top:12px; float:left;"><span class="label label-success" style="font-size:14px;"><span lang="en">Welcome</span> Dr. '.$IdMEDName.' '.$IdMEDSurname.', <span lang="en">this patient has been referred by</span> <i>Dr. '.$otherdocname.' '.$otherdocSurname.'.</i></span></div>';
		}
			//echo '<div style="width:90%; margin-top:12px; float:left;"><span class="label label-success" style="font-size:14px;"><span lang="en">Welcome</span> Dr. '.$IdMEDName.' '.$IdMEDSurname.', <span lang="en">this patient has been referred by</span> <i>Dr. '.$otherdocname.' '.$otherdocSurname.'.</i></span></div>';
		}else{

		if($otherdocname=='' and $otherdocSurname==''){
		//echo '<div style="width:90%; margin-top:12px; float:left;"><span class="label label-success" style="font-size:14px;"><span lang="en">Welcome</span> Dr. '.$IdMEDName.' '.$IdMEDSurname.', <span lang="en">this patient has been referred by</span> <i>Dr. '.$otherdocname.' '.$otherdocSurname.'.</i></span></div>';
		echo '<div style="width:90%; margin-top:12px; float:left;"><span class="label label-success" style="font-size:14px;"><span lang="en">Welcome</span> Dr. '.$IdMEDName.' '.$IdMEDSurname.', <span lang="en">you referred this patient to</span> <i>Dr. '.$otherdoctoremail.'.</i></span></div>';
		}else{
		echo '<div style="width:90%; margin-top:12px; float:left;"><span class="label label-success" style="font-size:14px;"><span lang="en">Welcome</span> Dr. '.$IdMEDName.' '.$IdMEDSurname.', <span lang="en">you referred this patient to</span> <i>Dr. '.$otherdocname.' '.$otherdocSurname.'.</i></span></div>';
		}

		}

		echo '<div class="clearfix" style="margin-bottom:20px;"></div>';

        echo '<div style="width:90%; margin-top:12px; float:left;">';
        			echo '<div id="ack" style="width:180px; float:left;"><div style="color: #ccc; font-size:20px; font-weight:bold; width:100%; ">Stage A</div><span style="color: #ccc; font-size:12px; width:100%;" lang="en">Acknowledgement</span></div>';
					echo '<div id="app" style="width:180px; float:left;"><div style="color: #ccc; font-size:20px; font-weight:bold; width:100%; ">Stage B</div><span style="color: #ccc; font-size:12px; width:100%;" lang="en">Appointment</span></div>';
					echo '<div id="infr" style="width:180px; float:left;"><div style="color: #ccc; font-size:20px; font-weight:bold; width:100%; ">Stage C</div><span style="color: #ccc; font-size:12px; width:100%;" lang="en">Information Review</span></div>';
					echo '<div id="inpa" style="width:180px; float:left;"><div style="color: #ccc; font-size:20px; font-weight:bold; width:100%;">Stage D</div><span style="color: #ccc; font-size:12px; width:100%;" lang="en">Interview Patient</span></div></div>';

					if($showreferralsection==1) {
					echo '<div id="referral_stage"><div style="width:90%; margin-top:12px;">';
					echo '<div id="ack_btn" style="width:180px; float:left;"><a href="javascript:void(0)" class="btn" title="Acknowledge" style="width:65%; color:grey;" lang="en"><i id="ack_ok" class="icon-ok"></i> Acknowledged</a></div>';
					//echo '<i style="margin-left:3px" class="icon-arrow-right"></i>';
					echo '<div id="app_btn" style="width:180px; float:left;"><a href="javascript:void(0)" class="btn" title="Schedule" style="width:65%; color:grey;" lang="en"><i class="icon-calendar"></i> Scheduled</a></div>';
					//echo '<i style="margin-left:3px" class="icon-arrow-right"></i>';
					echo '<div id="infr_btn" style="width:180px; float:left;"><a href="javascript:void(0)" class="btn" title="IReview" style="width:65%; color:grey;" lang="en"><i class="icon-eye-open"></i> Info Reviewed</a></div>';
					//echo '<i style="margin-left:3px" class="icon-arrow-right"></i>';
					echo '<div id="inpa_btn" style="width:180px; float:left;"><a href="javascript:void(0)" class="btn" title="Visited" style="width:65%; color:grey;" lang="en"><i class="icon-signin"></i> Visited</a></div>';
					
					
					echo '<div id="reject_btn" style="width:180px; float:left;"><a href="javascript:void(0)" class="btn" title="Visited" style="width:65%; color:red; float:right;" lang="en"><i class="icon-signin"></i> Reject Patient</a></div>';

					echo '</div></div>';
					}

					echo '<div class="clearfix" style="margin-bottom:20px;"></div>';

					echo '<div style="width:90%; margin-top:12px;">';
        		

        		
        //<!--Messages Start-->
          echo '<ul id="myTab" class="nav nav-tabs tabs-main">';
            echo '<li class="active"><a href="#inbox" data-toggle="tab" id="newinbox">InBox</a></li>';
			echo '<li><a href="#outbox" data-toggle="tab" id="newoutbox">OutBox</a></li></ul>';
			echo '<div id="myTabContent" class="tab-content tabs-main-content padding-null">';
                               
                echo '<div class="tab-pane tab-overflow-main fade in active" id="inbox">';
				echo '<div class="message-list"><div class="clearfix" style="margin-bottom:40px;">';
                echo '<div class="action-message"><div class="btn-group">';
               
                echo '<button id="delete_message" class="btn b2"><i class="icon-trash padding-null"></i></button>';
				echo '<input type="button" lang="en" style="margin-left:10px" class="btn b2" value="Create Message" id="compose_message">';
              
             	echo '</div></div>';
				echo '</div>';
                echo '<table class="table table-striped table-mod" id="datatable_3"></table>'; 
                    
                echo '</div></div>';

				echo '<div class="tab-pane" id="outbox">';
				echo '<div class="message-list"><div class="clearfix" style="margin-bottom:40px;">';
                echo '<div class="action-message"><div class="btn-group">';
                
                echo '<button id="delete_message_outbox" class="btn b2"><i class="icon-trash padding-null"></i></button>';
				echo '</div></div>';
				echo '</div>';
                echo '<table class="table table-striped table-mod" id="datatable_4"></table>'; 
                    
                echo '</div>';
                echo '</div>';

				echo '</div>';

         //<!--Messages END-->

        		
        		echo '</div></div></div>';
			
        //<!--Specific Referall Comm Box Start-->
		}else if($multireferral==1){
					/*
					 echo '<img id="ZoomedImage" style="display:none;">';
					 echo '<iframe id="ZoomedIframe" style="display:none;     
					 	 position:absolute; 
					     background-color:#000000;
						 z-index:100;
						 width:900px;
						 height:1200px;
						 text-align:center;
						 vertical-align:middle;
						 "></iframe>';
			
					 echo '<div class="grid" id="NewMES" style="margin:0 auto; max-height:2000px; min-height:20px; width:50%; padding:10px; overflow:scroll; ">';
					 echo '		<div style="float:left; width:10%;  padding:5px; border:solid;">';
					 echo '		</div>';
					 echo '		<div id="TextMES" style="float:left; width:80%;  margin-left:20px; padding:5px;">';
					 echo '		</div>';
					 echo '</div>';
					*/
					
					 echo '<div class="grid" style="width:97%; position:relative;">';
					  
					 // Additions for displaying "2nd Opinion" Seal
					 for($i=0;$i<$num_multireferral;$i++){
					 	if($referral_type_array[$i]==1) echo '<div id="SecondSeal" style="float:left; position:absolute; left: 951px; top: -16px;height: 100px;width: 130px;"><img src="images/SecondOpinion.png"></div>';
					 	//echo '<div id="SecondSeal" style="float:left; position:absolute; left: 849px; top: -16px;height: 100px;width: 130px;"><img src="images/SecondOpinion.png"></div>';
					 	};
					 echo  '<div class="grid-content overflow">';
					//MOVED TO HIDDEN INPUTS FOR ORGANIZATION.... echo '<input type="hidden" id="multireferral_num" value="'.$num_multireferral.'">';
					//based on the value of the number of referral doctors create tabs dynamically
					 echo  '<span class="label label-info" id="EtiTML" style="left:0px; margin-left:0px; margin-top:20px; margin-bottom:20px; font-size:16px; text-shadow:none; text-decoration:none;" lang="en">Referral Communications Area</span>';
					 echo  '<span style="margin-left:100px;display:none" id="H2M_Spin" lang="en"><i class="icon-spinner icon-3x icon-spin" ></i> <b> Processing request... </b> </span>';
					 //echo '<a href="scheduler.php" class="btn" title="Schedule" style="color:black; margin-left:50px; float:right;margin-right:10px"><i class="icon-calendar"></i> Schedule using H2M</a>'; 
					 echo '<ul id="myTab_ref" class="nav nav-tabs tabs-main">';
					//if($multireferral==1){

						for($i=0;$i<$num_multireferral;$i++){
						if($i==0)
							echo '<li class="active"><a href="#referraldoctab'.$i.'" data-toggle="tab" id="newreferraldoctab'.$i.'"><span class="label" style="font-size:14px;background-color:'.$referralcolors[$i].'">Dr. '.$otherdocnamearray[$i].' '.$otherdocSurnamearray[$i].'</span></a></li>';
						else
							echo '<li><a href="#referraldoctab'.$i.'" data-toggle="tab" id="newreferraldoctab'.$i.'"><span class="label" style="font-size:14px;background-color:'.$referralcolors[$i].'">Dr. '.$otherdocnamearray[$i].' '.$otherdocSurnamearray[$i].'</span></a></li>';
						}
						echo '</ul>';
						echo '<div id="myTabContent_ref" class="tab-content tabs-main-content padding-null">';
						
						
						for($i=0;$i<$num_multireferral;$i++){
																		
						if($i==0)
							echo '<div class="tab-pane tab-overflow-main fade in active" id="referraldoctab'.$i.'">';
						else
							echo '<div class="tab-pane" id="referraldoctab'.$i.'">';
							
						//Adding changes for the showing a label saying ack pending
						
						if($estado_ref[$i]==1){
						
							if($showreferralsectionarray[$i]==1){
								echo '<input type="hidden" id="referral_id'.$i.'" value="'.$referral_id_array[$i].'">';
								echo '<input type="hidden" id="otherdocid'.$i.'" value="'.$otherdocarray[$i].'">';
								//echo '<div style="width:90%; margin-top:12px; float:left;margin-left:20px"><span class="label label-success" style="font-size:14px;">Acknowledgement pending from Dr.'.$otherdocnamearray[$i].' '.$otherdocSurnamearray[$i].'</i></span></div>';
								echo '<div style="width:90%; margin-top:12px;margin-left:20px;float:left;">';
								echo '<div id="ack_pending'.$i.'" style="width:180px; float:left;"><div style="color: #ccc; font-size:20px; font-weight:bold; width:100%; ">Stage A</div><span style="color: #ccc; font-size:12px; width:100%;">Acknowledgement</span></div>';
								echo '<div id="ack_btn_pending'.$i.'" style="width:180px; float:left;"><a href="javascript:void(0)" class="btn" title="Acknowledge" style="width:65%; color:grey;"><i id="ack_ok_pending'.$i.'" class="icon-ok"></i> Acknowledge</a></div></div>';
							}else{
							
								echo '<div style="width:90%; margin-top:12px; float:left;margin-left:20px"><span class="label label-success" style="font-size:14px;">Acknowledgement pending from Dr.'.$otherdocnamearray[$i].' '.$otherdocSurnamearray[$i].'</i></span></div>';
							}
						
							//echo '<div style="width:90%; margin-top:12px; float:left;margin-left:20px"><span class="label label-success" style="font-size:14px;">Acknowledgement pending from Dr.'.$otherdocnamearray[$i].' '.$otherdocSurnamearray[$i].'</i></span></div>';
						
						}else{ 
						
								//individual referral area starts
								echo '<input type="hidden" id="referral_state'.$i.'" value="'.$referral_stage_array[$i].'">';
								echo '<input type="hidden" id="reportid_review'.$i.'" value="'.$attachments_dld_array[$i].'">';
								echo '<input type="hidden" id="referral_id'.$i.'" value="'.$referral_id_array[$i].'">';
								echo '<input type="hidden" id="otherdocid'.$i.'" value="'.$otherdocarray[$i].'">';
								echo '<input type="hidden" id="referralcolor'.$i.'" value="'.$referralcolors[$i].'">';
								//Adding new referral type
								echo '<input type="hidden" id="referraltype'.$i.'" value="'.$referral_type_array[$i].'">';
								if($showreferralsectionarray[$i]==1){
									if($otherdocnamearray[$i]=='' and $otherdocSurnamearray[$i]==''){
										if($attachments_dld_array[$i]==0)
										{
											echo '<div style="width:90%; margin-top:12px; float:left;margin-left:20px"><span class="label label-success" style="font-size:14px;"><span lang="en">Welcome</span> Dr. '.$IdMEDName.' '.$IdMEDSurname.', <span lang="en">this patient has been referred by</span> <i>Dr. '.$otherdocnamearray[$i].' '.$otherdocSurnamearray[$i].'.</i></span></div>';
										}
										else
										{
											echo '<div style="width:90%; margin-top:12px; float:left;margin-left:20px"><span class="label label-success" style="font-size:14px;"><span lang="en">Welcome</span> Dr. '.$IdMEDName.' '.$IdMEDSurname.', <span lang="en">this patient has been referred by</span> <i>Dr. '.$otherdocnamearray[$i].' '.$otherdocSurnamearray[$i].'.</i></span><button id='.$referral_id_array[$i].' class="btn PrintHighlighted" style="float:right;margin-right:-60px" lang="en"><i class="icon-print"></i>Print Highlighted Reports</button><span style="float:right;margin-right:10px;display:none;color:#22aeff;" id="H2M_Spin_Stream_Print"><i class="icon-spinner icon-2x icon-spin" ></i></span></div>';
										}
								}else{
									if($attachments_dld_array[$i]==0)
									{
									
										echo '<div style="width:90%; margin-top:12px; float:left;margin-left:20px"><span class="label label-success" style="font-size:14px;"><span lang="en">Welcome</span> Dr. '.$IdMEDName.' '.$IdMEDSurname.', <span lang="en">this patient has been referred by</span> <i>Dr. '.$otherdocnamearray[$i].' '.$otherdocSurnamearray[$i].'.</i></span></span></div>';
									}
									else
									{
										echo '<div style="width:90%; margin-top:12px; float:left;margin-left:20px"><span class="label label-success" style="font-size:14px;"><span lang="en">Welcome</span> Dr. '.$IdMEDName.' '.$IdMEDSurname.', <span lang="en">this patient has been referred by</span> <i>Dr. '.$otherdocnamearray[$i].' '.$otherdocSurnamearray[$i].'.</i></span></span><button id='.$referral_id_array[$i].' class="btn PrintHighlighted" style="float:right;margin-right:-60px" lang="en"><i class="icon-print"></i>Print Highlighted Reports</button><span style="float:right;margin-right:10px;display:none;color:#22aeff" id="H2M_Spin_Stream_Print"><i class="icon-spinner icon-2x icon-spin" ></i></span></div>';	
									}
								
								
								}
									//echo '<div style="width:90%; margin-top:12px; float:left;"><span class="label label-success" style="font-size:14px;"><span lang="en">Welcome</span> Dr. '.$IdMEDName.' '.$IdMEDSurname.', <span lang="en">this patient has been referred by</span> <i>Dr. '.$otherdocname.' '.$otherdocSurname.'.</i></span></div>';
								}else{

								if($otherdocnamearray[$i]=='' and $otherdocSurnamearray[$i]==''){
								//echo '<div style="width:90%; margin-top:12px; float:left;"><span class="label label-success" style="font-size:14px;"><span lang="en">Welcome</span> Dr. '.$IdMEDName.' '.$IdMEDSurname.', <span lang="en">this patient has been referred by</span> <i>Dr. '.$otherdocname.' '.$otherdocSurname.'.</i></span></div>';
									if($attachments_dld_array[$i]==0)
									{
										echo '<div style="width:90%; margin-top:12px; float:left;margin-left:20px"><span class="label label-success" style="font-size:14px;"><span lang="en">Welcome</span> Dr. '.$IdMEDName.' '.$IdMEDSurname.', <span lang="en">you referred this patient to</span> <i>Dr. '.$otherdoctoremailarray[$i].'.</i></span></div>';
									}
									else
									{
										echo '<div style="width:90%; margin-top:12px; float:left;margin-left:20px"><span class="label label-success" style="font-size:14px;"><span lang="en">Welcome</span> Dr. '.$IdMEDName.' '.$IdMEDSurname.', <span lang="en">this patient has been referred by</span> <i>Dr. '.$otherdocnamearray[$i].' '.$otherdocSurnamearray[$i].'.</i></span></span><button id='.$referral_id_array[$i].' class="btn PrintHighlighted" style="float:right;margin-right:-60px" lang="en"><i class="icon-print"></i>Print Highlighted Reports</button><span style="float:right;margin-right:10px;display:none;color:#22aeff" id="H2M_Spin_Stream_Print"><i class="icon-spinner icon-2x icon-spin" ></i></span></div>';	
									}
								
								}else{
									if($attachments_dld_array[$i]==0)
									{
										echo '<div style="width:90%; margin-top:12px; float:left;margin-left:20px"><span class="label label-success" style="font-size:14px;"><span lang="en">Welcome</span> Dr. '.$IdMEDName.' '.$IdMEDSurname.', <span lang="en">you referred this patient to</span> <i>Dr. '.$otherdocnamearray[$i].' '.$otherdocSurnamearray[$i].'.</i></span></div>';
									}
									else
									{
										echo '<div style="width:90%; margin-top:12px; float:left;margin-left:20px"><span class="label label-success" style="font-size:14px;"><span lang="en">Welcome</span> Dr. '.$IdMEDName.' '.$IdMEDSurname.', <span lang="en">this patient has been referred by</span> <i>Dr. '.$otherdocnamearray[$i].' '.$otherdocSurnamearray[$i].'.</i></span></span><button id='.$referral_id_array[$i].' class="btn PrintHighlighted" style="float:right;margin-right:-60px" lang="en"><i class="icon-print"></i>Print Highlighted Reports</button><span style="float:right;margin-right:10px;display:none;color:#22aeff" id="H2M_Spin_Stream_Print"><i class="icon-spinner icon-2x icon-spin" ></i></span></div>';	
									}									
								}

								}

								echo '<div class="clearfix" style="margin-bottom:20px;"></div>';

								echo '<div style="width:90%; margin-top:12px;margin-left:20px;float:left;">';
								
											echo '<div id="ack'.$i.'" style="width:180px; float:left;"><div style="color: #ccc; font-size:20px; font-weight:bold; width:100%; " lang="en">Stage A</div><span style="color: #ccc; font-size:12px; width:100%;" lang="en">Acknowledgement</span></div>';
											if($referral_type_array[$i]==1){
												echo '<div id="infr_ref'.$i.'" style="width:180px; float:left;"><div style="color: #ccc; font-size:20px; font-weight:bold; width:100%; " lang="en">Stage B</div><span style="color: #ccc; font-size:12px; width:100%;" lang="en">Information Review</span></div>';
												echo '<div id="cmnt_ref'.$i.'" style="width:180px; float:left;"><div style="color: #ccc; font-size:20px; font-weight:bold; width:100%; " lang="en">Stage C</div><span style="color: #ccc; font-size:12px; width:100%;" lang="en">Comments</span></div>';
											} else {
												echo '<div id="app'.$i.'" style="width:180px; float:left;"><div style="color: #ccc; font-size:20px; font-weight:bold; width:100%; " lang="en">Stage B</div><span style="color: #ccc; font-size:12px; width:100%;" lang="en">Appointment</span></div>';
												echo '<div id="infr'.$i.'" style="width:180px; float:left;"><div style="color: #ccc; font-size:20px; font-weight:bold; width:100%; " lang="en">Stage C</div><span style="color: #ccc; font-size:12px; width:100%;" lang="en">Information Review</span></div>';
												echo '<div id="inpa'.$i.'" style="width:180px; float:left;"><div style="color: #ccc; font-size:20px; font-weight:bold; width:100%;" lang="en">Stage D</div><span style="color: #ccc; font-size:12px; width:100%;" lang="en">Interview Patient</span></div>';
											}
											echo '</div>';
											
											if($showreferralsectionarray[$i]==1) {
												echo '<div id="referral_stage'.$i.'"><div style="width:90%; margin-top:12px;margin-left:20px;">';
												echo '<div id="ack_btn'.$i.'" style="width:180px; float:left;"><a href="javascript:void(0)" class="btn" title="Acknowledge" style="width:65%; color:grey;" lang="en"><i id="ack_ok'.$i.'" class="icon-ok"></i> Acknowledged</a></div>';
												//echo '<i style="margin-left:3px" class="icon-arrow-right"></i>';
												if($referral_type_array[$i]==1){
													echo '<div id="infr_ref_btn'.$i.'" style="width:180px; float:left;"><a href="javascript:void(0)" class="btn" title="IReview" style="width:65%; color:grey;" lang="en"><i class="icon-eye-open"></i> Info Reviewed</a></div>';
													//echo '<i style="margin-left:3px" class="icon-arrow-right"></i>';
													echo '<div id="cmnt_ref_btn'.$i.'" style="width:180px; float:left;"><a href="javascript:void(0)" class="btn" title="Comments" style="width:65%; color:grey;" lang="en"><i class="icon-comments"></i> Comments </a></div>';
													//echo '<i style="margin-left:3px" class="icon-arrow-right"></i>';
													
												}else {
													echo '<div id="app_btn'.$i.'" style="width:180px; float:left;"><a href="javascript:void(0)" class="btn" title="Schedule" style="width:65%; color:grey;" lang="en"><i class="icon-calendar"></i> Scheduled</a></div>';
													//echo '<i style="margin-left:3px" class="icon-arrow-right"></i>';
													echo '<div id="infr_btn'.$i.'" style="width:180px; float:left;"><a href="javascript:void(0)" class="btn" title="IReview" style="width:65%; color:grey;" lang="en"><i class="icon-eye-open"></i> Info Reviewed</a></div>';
													//echo '<i style="margin-left:3px" class="icon-arrow-right"></i>';
													echo '<div id="inpa_btn'.$i.'" style="width:180px; float:left;"><a href="javascript:void(0)" class="btn" title="Visited" style="width:65%; color:grey;" lang="en"><i class="icon-signin"></i> Visited</a></div>';
												}
											
											if($referral_stage_array[$i]==1 or $referral_stage_array[$i]==0){
											
												echo '<div id="reject_btn'.$i.'" style="width:120px; float:right;"><a href="javascript:void(0)" class="btn" title="Reject" style="width:65%; color:red;" lang="en"><i class="icon-mail-reply-all"></i> Reject</a></div>';
											
											}
											echo '</div></div>';
											}

											echo '<div class="clearfix" style="margin-bottom:20px;"></div>';

											echo '<div style="width:90%; margin-top:12px;margin-left:20px;">';
										

								
								//<!--Messages Start-->
								  echo '<ul id="myTab" class="nav nav-tabs tabs-main">';
									echo '<li class="active"><a href="#inbox'.$i.'" data-toggle="tab" id="newinbox'.$i.'" lang="en">InBox</a></li>';
									echo '<li><a href="#outbox'.$i.'" data-toggle="tab" id="newoutbox'.$i.'" lang="en">OutBox</a></li></ul>';
									echo '<div id="myTabContent" class="tab-content tabs-main-content padding-null">';
												   
									echo '<div class="tab-pane tab-overflow-main fade in active" id="inbox'.$i.'">';
									echo '<div class="message-list"><div class="clearfix" style="margin-bottom:40px;">';
									echo '<div class="action-message"><div class="btn-group">';
								   
									echo '<button id="delete_message'.$i.'" class="btn b2"><i class="icon-trash padding-null"></i></button>';
									echo '<input type="button" style="margin-left:10px" class="btn b2" lang="en" value="Create Message" id="compose_message'.$i.'">';
								  
									echo '</div></div>';
									echo '</div>';
									echo '<table class="table table-striped table-mod" id="datatable_3_'.$i.'"></table>'; 
										
									echo '</div></div>';

									echo '<div class="tab-pane" id="outbox'.$i.'">';
									echo '<div class="message-list"><div class="clearfix" style="margin-bottom:40px;">';
									echo '<div class="action-message"><div class="btn-group">';
									
									echo '<button id="delete_message_outbox'.$i.'" class="btn b2"><i class="icon-trash padding-null"></i></button>';
									echo '</div></div>';
									echo '</div>';
									echo '<table class="table table-striped table-mod" id="datatable_4_'.$i.'"></table>'; 
										
									echo '</div>'; 
									echo '</div>';

									echo '</div>';

									//<!--Messages END-->

								
								echo '</div>';
								
								//individual referral area stops
								
						}	
								echo '</div>';
					
				 }
				//echo  '<span class="label label-success" id="EtiTML" style="left:0px; margin-left:0px; margin-top:20px; margin-bottom:20px; font-size:14px; text-shadow:none; text-decoration:none;background-color:red">'.$errormessage.'</span>';
				//echo $errormessage;
				//echo '</div>';
			 echo '</div></div></div>';
		
		}else {echo '<input type="hidden" id="referral_state" value="0">';} ?>
        <div class="clearfix" style="margin-bottom:20px;"></div>
        <!-- Commented out the below line by Pallab as clearallergy features had to removed out due to HTI COL deployment -->
      <!--   <div class="label label-info" style="margin:20px;float:right;-webkit-animation: glow 2s linear infinite;"><a href="clearAllergy_emr.php?idusu=<?php echo $IdUsu ?>" class="btn"> Clear Allergy </a> </div> -->
        <div class="clearfix" style="margin-bottom:20px;"></div>
        
         <!--Upload Box Start-->
        <div class="label label-info" id="EtiTML" style=" margin-top: 0px; width: 96%; font-size:16px; text-shadow:none; text-decoration:none; padding: 7px; text-align: center;" lang="en">Toolbox</div>
        <div style="width:97%; padding-top:10px;">
		
        <input type="hidden" id="IdUsuP" value="<?php echo $IdUsu ?>" />
        	        
        	<div class="grid-content overflow" style="text-align: center;">
            <?php
                $width = "70px";
                $height = "70px";
                $margin_sides = "18px";
                $margin_top = "5px";
                $size = "40px";
                $icon_size = "38px";
                $label_width = "81px";
                $label_font_size = "12px";
                $label_margin_left = "-5px";
                if($MedID < 0)
                {
                    $width = "100px";
                    $height = "85px";
                    $margin_sides = "31px";
                    $margin_top = "10px";
                    $size = "50px";
                    $icon_size = "42px";
                    $label_width = "100px";
                    $label_font_size = "13px";
                    $label_margin_left = "0px";
                }
                //echo '<div id="BotonUpload_New"  class="pull-left"><a href="#" class="btn" title="Upload Report"><i class="icon-upload" style="color: #5EB529; width: 100px;"></i> Upload Report</a> </div>';
                echo '<a id="BotonUpload_New" value="BotonUpload_New" class="btn ButtonDrAct" style="margin-top: -10px; width: '.$width.'; height: '.$height.'">';
                echo '    <div style=" margin-left: '.$margin_sides.'; margin-right: -'.$margin_sides.'; margin-top:'.$margin_top.'; width:'.$size.'; height:'.$size.'; font-size: '.$icon_size.'; color: #5EB529;"><i class="icon-upload"></i></div>';
                echo '    <div style="line-height:25px; width: '.$label_width.'; height: 25px; font-size: '.$label_font_size.'; margin-left: '.$label_margin_left.';"><span lang="en">Upload Report</span></div>';
                echo '</a>';
                
                //Commenting Patient Details by Pallab as requested by Javier 
              /*  if(($MedID > 0)&&($privilege != 3))
                {
			         
                     //echo '<div class="pull-left" style="margin-left:20px;"><a href="emr_ret.php?idusu='.$IdUsu.'" class="btn"><i class="icon-folder-open"></i> Patient Details</a> </div>';
                    echo '<a id="Patient_Details_Button" class="btn ButtonDrAct" href="emr_ret.php?idusu='.$IdUsu.'" style="margin-top: -10px; margin-left: 15px; width: '.$width.'; height: '.$height.'">';
                    echo '    <div style=" margin-left: '.$margin_sides.'; margin-right: -'.$margin_sides.'; margin-top:'.$margin_top.'; width:'.$size.'; height:'.$size.'; font-size: '.$icon_size.'; color: #5EB529;"><i class="icon-folder-open"></i></div>';
                    echo '    <div style="line-height:25px; width: '.$label_width.'; height: 25px; font-size: '.$label_font_size.'; margin-left: '.$label_margin_left.';"><span lang="en">Patient Details</span></div>';
                    echo '</a>';
                }
                */
			     //echo '<div class="pull-left" style="margin-left:20px;"><a href="medicalPassport.php?IdUsu='.$IdUsu.'" class="btn" target="_blank"><i class="icon-compass"></i> SUMMARY</a> </div>';
                echo '<a id="Summary_Button" value="BotonUpload_New" class="btn ButtonDrAct" href="medicalPassport.php?IdUsu='.$IdUsu.'" style="margin-top: -10px; margin-left: 15px; width: '.$width.'; height: '.$height.'">';
                echo '    <div style=" margin-left: '.$margin_sides.'; margin-right: -'.$margin_sides.'; margin-top:'.$margin_top.'; width:'.$size.'; height:'.$size.'; font-size: '.$icon_size.'; color: #5EB529;"><i class="icon-book"></i></div>';
                echo '    <div style="line-height:25px; width: '.$label_width.'; height: 25px; font-size: '.$label_font_size.'; margin-left: '.$label_margin_left.';"><span lang="en">Summary</span></div>';
                echo '</a>';
                
                echo '<a id="Print_Button" value="BotonUpload_New" class="btn ButtonDrAct" href="printReports.php?IdUsu='.$IdUsu.'" style="margin-top: -10px; margin-left: 15px; width: '.$width.'; height: '.$height.'">';
                echo '    <div style=" margin-left: '.$margin_sides.'; margin-right: -'.$margin_sides.'; margin-top:'.$margin_top.'; width:'.$size.'; height:'.$size.'; font-size: '.$icon_size.'; color: #5EB529;"><i class="icon-list"></i></div>';
                echo '    <div style="line-height:25px; width: '.$label_width.'; height: 25px; font-size: '.$label_font_size.'; margin-left: '.$label_margin_left.';"><span lang="en">Print Summary</span></div>';
                echo '</a>';
				
				if($MedID > 0 && $privilege == 3){
				//E-PRESCRIBE FOR HTI & H2M
						echo '<a id="ePrescribe" value="BotonUpload_New" class="btn ButtonDrAct" style="margin-top: -10px; margin-left: 15px; width: '.$width.'; height: '.$height.'">';
						echo '    <div style=" margin-left: '.$margin_sides.'; margin-right: -'.$margin_sides.'; margin-top:'.$margin_top.'; width:'.$size.'; height:'.$size.'; font-size: '.$icon_size.'; color: #5EB529;"><i class="icon-list"></i></div>';
						echo '    <div style="line-height:25px; width: '.$label_width.'; height: 25px; font-size: '.$label_font_size.'; margin-left: '.$label_margin_left.';"><span lang="en">E-Prescribe</span></div>';
						echo '</a>';
						}
    
			     if(($MedID > 0)&&($privilege != 3))
                 {
                    //echo '<div id="BotonRecords" style="margin-left:20px;" data-target="#header-modal4" data-toggle="modal" class="pull-left"><a href="#" class="btn" title="Records Board"><i class="icon-desktop"></i> Board</a> </div>';
                    echo '<a id="BotonRecords" class="btn ButtonDrAct" data-target="#header-modal4" data-toggle="modal" style="margin-top: -10px; margin-left: 15px; width: '.$width.'; height: '.$height.'">';
                    echo '    <div style=" margin-left: '.$margin_sides.'; margin-right: -'.$margin_sides.'; margin-top:'.$margin_top.'; width:'.$size.'; height:'.$size.'; font-size: '.$icon_size.'; color: #5EB529;"><i class="icon-table"></i></div>';
                    echo '    <div style="line-height:25px; width: '.$label_width.'; height: 25px; font-size: '.$label_font_size.'; margin-left: '.$label_margin_left.';"><span lang="en">Board</span></div>';
                    echo '</a>';
                
                    //if ($privilege != 3) { 
                        //echo '<div class="pull-left" style="margin-left:20px;"><a href="dropzone_short.php?IdUsu='.$IdUsu.'" class="btn"><i class="icon-th"></i> Drop Files</a> </div>';
                        echo '<a id="Drop_Button" class="btn ButtonDrAct" href="dropzone_short.php?IdUsu='.$IdUsu.'" style="margin-top: -10px; margin-left: 15px; width: '.$width.'; height: '.$height.'">';
                        echo '    <div style=" margin-left: '.$margin_sides.'; margin-right: -'.$margin_sides.'; margin-top:'.$margin_top.'; width:'.$size.'; height:'.$size.'; font-size: '.$icon_size.'; color: #5EB529;"><i class="icon-inbox"></i></div>';
                        echo '    <div style="line-height:25px; width: '.$label_width.'; height: 25px; font-size: '.$label_font_size.'; margin-left: '.$label_margin_left.';"><span>Drop Files</span></div>';
                        echo '</a>';
						
						
                    //}    
                 //}     
                //if($MedID > 0)
                // {
			         //echo '<button id="EvolutionButton" class="btn" style="float:left; margin-left:20px; margin-right:10px;"><i class="icon-file-text"></i>Evolution</button>';
                    if (isset($_SESSION['CustomLook']) && $_SESSION['CustomLook']!='COL') {
                        
                        echo '<a id="EvolutionButton" value="BotonUpload_New" class="btn ButtonDrAct" style="margin-top: -10px; margin-left: 15px; width: '.$width.'; height: '.$height.'">';
                        echo '    <div style=" margin-left: '.$margin_sides.'; margin-right: -'.$margin_sides.'; margin-top:'.$margin_top.'; width:'.$size.'; height:'.$size.'; font-size: '.$icon_size.'; color: #5EB529;"><i class="icon-list"></i></div>';
                        echo '    <div style="line-height:25px; width: '.$label_width.'; height: 25px; font-size: '.$label_font_size.'; margin-left: '.$label_margin_left.';"><span lang="en">Evolution</span></div>';
                        echo '</a>';
                    }    
                   /* echo '<a id="PhoneNotes" value="BotonUpload_New" class="btn ButtonDrAct" style="margin-top: -10px; margin-left: 15px; width: '.$width.'; height: '.$height.'">';
                    echo '    <div style=" margin-left: '.$margin_sides.'; margin-right: -'.$margin_sides.'; margin-top:'.$margin_top.'; width:'.$size.'; height:'.$size.'; font-size: '.$icon_size.'; color: #5EB529;"><i class="icon-file"></i></div>';
                    echo '    <div style="line-height:25px; width: '.$label_width.'; height: 25px; font-size: '.$label_font_size.'; margin-left: '.$label_margin_left.';"><span lang="en">Notes</span></div>';*/
                    /*echo '</a>';
                    echo '<a id="Dictate" value="BotonUpload_New" class="btn ButtonDrAct" style="margin-top: -10px; margin-left: 15px; width: '.$width.'; height: '.$height.'">';
                    echo '    <div style=" margin-left: '.$margin_sides.'; margin-right: -'.$margin_sides.'; margin-top:'.$margin_top.'; width:'.$size.'; height:'.$size.'; font-size: '.$icon_size.'; color: #5EB529;"><i class="icon-microphone"></i></div>';
                    echo '    <div style="line-height:25px; width: '.$label_width.'; height: 25px; font-size: '.$label_font_size.'; margin-left: '.$label_margin_left.';"><span lang="en">Dictate</span></div>';
                    echo '</a>';*/
					echo '<a id="ePrescribe" value="BotonUpload_New" class="btn ButtonDrAct" style="margin-top: -10px; margin-left: 15px; width: '.$width.'; height: '.$height.'">';
                    echo '    <div style=" margin-left: '.$margin_sides.'; margin-right: -'.$margin_sides.'; margin-top:'.$margin_top.'; width:'.$size.'; height:'.$size.'; font-size: '.$icon_size.'; color: #5EB529;"><i class="icon-list"></i></div>';
                    echo '    <div style="line-height:25px; width: '.$label_width.'; height: 25px; font-size: '.$label_font_size.'; margin-left: '.$label_margin_left.';"><span lang="en">E-Prescribe</span></div>';
                    echo '</a>';
                     
                     echo '<a id="shareFiles" value="BotonUpload_New" class="btn ButtonDrAct" style="margin-top: -10px; margin-left: 15px; width: '.$width.'; height: '.$height.'">';
                    echo '    <div style=" margin-left: '.$margin_sides.'; margin-right: -'.$margin_sides.'; margin-top:'.$margin_top.'; width:'.$size.'; height:'.$size.'; font-size: '.$icon_size.'; color: #5EB529;"><i class="icon-share-alt"></i></div>';
                    echo '    <div style="line-height:25px; width: '.$label_width.'; height: 25px; font-size: '.$label_font_size.'; margin-left: '.$label_margin_left.';"><span lang="en">Share</span></div>';
                    echo '</a>';
                    //echo '<!-- Adding below Notes button for taking notes by doctor during telemedicine phone call -->
                    //<button id="PhoneNotes" class="btn" style="float:left; margin-left:20px; margin-right:10px;"><i class="icon-file-text"></i>Notes</button>';
                    //echo '<button id="Dictate" class="btn" style="float:right;margin-right:10px;"><i class="icon-microphone"></i>Dictate</button>';
                }
                if($MedID <= 0 && ((isset($_SESSION['CustomLook']) && $_SESSION['CustomLook']!='COL') || !isset($_SESSION['CustomLook'])))
                {
                    echo '<a id="MedicationsButton" class="btn ButtonDrAct" style="margin-top: -10px; margin-left: 15px; width: '.$width.'; height: '.$height.'">';
                    echo '    <div style=" margin-left: '.$margin_sides.'; margin-right: -'.$margin_sides.'; margin-top:'.$margin_top.'; width:'.$size.'; height:'.$size.'; font-size: '.$icon_size.'; color: #5EB529;"><i class="icon-medkit"></i></div>';
                    echo '    <div style="line-height:25px; width: '.$label_width.'; height: 25px; font-size: '.$label_font_size.'; margin-left: '.$label_margin_left.';"><span lang="en">Medications</span></div>';
                    echo '</a>';
                }
if (isset($_SESSION['CustomLook']) && $_SESSION['CustomLook']!='COL') {
                echo '<a id="TrackingButton" value="BotonUpload_New" class="btn ButtonDrAct" style="margin-top: -10px; margin-left: 15px; width: '.$width.'; height: '.$height.'">';
                echo '    <div style=" margin-left: '.$margin_sides.'; margin-right: -'.$margin_sides.'; margin-top:'.$margin_top.'; width:'.$size.'; height:'.$size.'; font-size: '.$icon_size.'; color: #5EB529;"><i class="icon-signal"></i></div>';
                echo '    <div style="line-height:25px; width: '.$label_width.'; height: 25px; font-size: '.$label_font_size.'; margin-left: '.$label_margin_left.';"><span lang="en">Tracking</span></div>';
                echo '</a>';
}
             ?> 
			  
 
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
            
        <div class="label label-success" style="left:0px; margin-left:0px; margin-top:20px; margin-bottom:5px; font-size:14px; padding: 7px; width: 97%; text-align: center; margin-bottom: -20px;" lang="en">Health Repository</div>
		<!--<span style="margin-left:100px;display:none;color:#22aeff" id="H2M_Spin_Stream"><i class="icon-spinner icon-2x icon-spin" ></i> <b> Processing request... </b> </span>-->
        <!--Progress Bar -->
		
		<!-- <div id=progressbar></div> -->
		<!--
		<center>
		<div id="CenterLabels" style="font-size:20px ; text-align:center;margin-top:10px">
	       	<i id="LessPage" class="icon-chevron-sign-left icon-2x navArrows" style="float:left;color:#22aeff" ></i>
	        <i id="MorePage" class="icon-chevron-sign-right icon-2x navArrows" style="float:right;margin-right:15px;color:#22aeff"></i>
	     
         </div>
		</center>-->
		
		
        <!--TAB Start-->
  <div id="tabsWithStyle" style="margin-top:20px" class="style-tabs"> 
      <!-- Added a new code of if($multireferral == 0) prior to ($multireferral > 0)checking whether it is patient through the multireferral variable, if multireferral == 0 then it is a patient viewing patiendetailMED, so, Superbill should not come up -->     <!-- IF it is a patient then due to commenting out of SuperBill, width of remaining blocks has to be increased to 119 --> 
      <ul id="myTab" class="nav nav-tabs tabs-main" style="width: 1080px;">
            <li id="0" class="TABES" style="width:<?php $medid = $BOTH_SESSION;
                $userid = $_GET['IdUsu'];
                if($medid != $userid && $multireferral != 1) {echo 108;} else if($multireferral>0) {echo 97;} else if($multireferral == 0){echo 119;}else {echo 108;}?>px; background: none repeat scroll 0% 0% rgb(204, 204, 204); text-align:center;"><a href="#ALL" data-toggle="tab" style="height:40px; font-size:16px;" lang="en"><i class="icon-ok-sign icon-large" style="color:black; font-size: 1.0em; width:100%;"></i><div style="width:100%"></div>ALL</a></li> 
			<?php
                    if($multireferral>0){
					$backgroundcolor=$TipoColorGroup[5];
					echo '<li id="5" class="TABES" style="text-align: center; width:10%; background-color:'.$backgroundcolor.'"><a href="#ALL"   data-toggle="tab" style="height:40px; color:'.$backgroundcolor.'"><i class="icon-star icon-large" style="color:RGB(111,111,111); font-size: 1.0em; width:100%;"></i><div style="width:100%"></div><span lang="en">Highlighted</span></a></li>';
				}
			?>
			
              <li id="1" class="TABES" style="text-align: center; width:<?php $medid = $BOTH_SESSION;
                $userid = $_GET['IdUsu'];
                if($medid != $userid && $multireferral != 1) {echo 108;} else if($multireferral>0) {echo 97;} else if($multireferral == 0){echo 120;}else {echo 108;}?>px; background-color:<?php echo $TipoColorGroup[1] ?>;"><a href="#ALL"  lang="en" data-toggle="tab" style=" color:<?php echo $TipoColorGroup[1] ?>;"><i class="<?php echo $TipoIconGroup[1] ?> icon-large" style="color:RGB(111,111,111); font-size: 1.0em; width:100%;"></i><div style="width:100%"></div>Imaging</a></li>
            <li id="2" class="TABES" style="text-align: center; width:<?php $medid = $BOTH_SESSION;
                $userid = $_GET['IdUsu'];
                if($medid != $userid && $multireferral != 1) {echo 108;} else if($multireferral>0) {echo 97;} else if($multireferral == 0){echo 120;}else {echo 108;}?>px; background-color:<?php echo $TipoColorGroup[2] ?>;"><a href="#ALL" lang="en" data-toggle="tab" style="height:40px; color:<?php echo $TipoColorGroup[2] ?>;"><i class="<?php echo $TipoIconGroup[2] ?> icon-large" style="color:RGB(111,111,111); font-size: 1.0em; width:100%;"></i><div style="width:100%"></div>Laboratory</a></li>
            <li id="3" class="TABES" style="text-align: center; width:<?php $medid = $BOTH_SESSION;
                $userid = $_GET['IdUsu'];
                if($medid != $userid && $multireferral != 1) {echo 108;} else if($multireferral>0) {echo 97;} else if($multireferral == 0){echo 120;}else {echo 108;}?>px; background-color:<?php echo $TipoColorGroup[3] ?>;"><a href="#ALL" lang="en" data-toggle="tab" style="height:40px; color:<?php echo $TipoColorGroup[3] ?>;"><i class="<?php echo $TipoIconGroup[3] ?> icon-large" style="color:RGB(111,111,111); font-size: 1.0em; width:100%;"></i><div style="width:100%"></div>Notes</a></li>
            <li id="4" class="TABES" style="text-align: center; width:<?php $medid = $BOTH_SESSION;
                $userid = $_GET['IdUsu'];
                if($medid != $userid && $multireferral != 1) {echo 108;} else if($multireferral>0) {echo 97;} else if($multireferral == 0){echo 120;}else {echo 108;}?>px; background-color:<?php echo $TipoColorGroup[4] ?>;"><a href="#ALL" lang="en" data-toggle="tab" style="height:40px; color:<?php echo $TipoColorGroup[4] ?>;"><i class="<?php echo $TipoIconGroup[4] ?> icon-large" style="color:RGB(111,111,111); font-size: 1.0em; width:100%;"></i><div style="width:100%"></div>Other </a></li>
            <!--<li id="5" class="TABES" style="width:10%; background-color:<?php echo $TipoColorGroup[5] ?>;"><a href="#ALL"   data-toggle="tab" style="height:40px; color:<?php echo $TipoColorGroup[5] ?>;"><i class="<?php echo $TipoIconGroup[5] ?> icon-large" style="color:RGB(111,111,111); font-size: 1.0em; width:100%;"></i><div style="width:100%"></div>-n/a-</a></li>-->
			
            <li id="6" class="TABES" style="text-align: center; width:<?php $medid = $BOTH_SESSION;
                $userid = $_GET['IdUsu'];
                if($medid != $userid && $multireferral != 1) {echo 108;} else if($multireferral>0) {echo 97;} else if($multireferral == 0){echo 120;}else {echo 108;}?>px; background-color:<?php echo $TipoColorGroup[6] ?>;"><a href="#ALL" lang="en" data-toggle="tab" style="height:40px; color:<?php echo $TipoColorGroup[6] ?>;"><i class="<?php echo $TipoIconGroup[6] ?> icon-large" style="color:RGB(111,111,111); font-size: 1.0em; width:100%;"></i><div style="width:100%"></div>SUMMARY</a></li>
            <li id="7" class="TABES" style="text-align: center; width:<?php $medid = $BOTH_SESSION;
                $userid = $_GET['IdUsu'];
                if($medid != $userid && $multireferral != 1) {echo 108;} else if($multireferral>0) {echo 97;} else if($multireferral == 0){echo 120;}else {echo 108;}?>px; background-color:<?php echo $TipoColorGroup[7] ?>;"><a href="#ALL" lang="en" data-toggle="tab" style="height:40px; color:<?php echo $TipoColorGroup[7] ?>;"><i class="<?php echo $TipoIconGroup[7] ?> icon-large" style="color:RGB(111,111,111); font-size: 1.0em; width:100%;"></i><div style="width:100%"></div>Pictures</a></li>
            <li id="8" class="TABES" style="text-align: center; width:<?php $medid = $BOTH_SESSION;
                $userid = $_GET['IdUsu'];
                if($medid != $userid && $multireferral != 1) {echo 108;} else if($multireferral>0) {echo 97;} else if($multireferral == 0){echo 120;}else {echo 108;}?>px; background-color:<?php echo $TipoColorGroup[8] ?>;"><a href="#ALL" lang="en" data-toggle="tab" style="height:40px; color:<?php echo $TipoColorGroup[8] ?>;"><i class="<?php echo $TipoIconGroup[8] ?> icon-large" style="color:RGB(111,111,111); font-size: 1.0em; width:100%;"></i><div style="width:100%"></div>Pat. Notes</a></li>
             <?php 
                
                $medid = $BOTH_SESSION;
                $userid = $_GET['IdUsu'];
                if($medid != $userid) 
                    { 
                        $superbill = '<li id="9" class="TABES" style="text-align: center; width:'; 
                        if($multireferral>0) 
                        {
                            $superbill .= '97';
                        } 
                        else 
                        {
                            $superbill .= '108';
                        } 
                        $superbill .= 'px; background-color:'.$TipoColorGroup[9].'"><a href="#ALL" lang="en" data-toggle="tab" style="height:40px; color:'.$TipoColorGroup[9].'"><i class="'.$TipoIconGroup[9].' icon-large" style="color:RGB(111,111,111); font-size: 1.0em; width:100%;"></i><div style="width:100%"></div>Superbill</a></li>';
                        echo $superbill;
                    } 
                ?>
           
      
      <li id="10" class="TABES" style="text-align: center; width:<?php $medid = $BOTH_SESSION;
                $userid = $_GET['IdUsu'];
                if($medid != $userid && $multireferral != 1) {echo 108;} else if($multireferral>0) {echo 97;} else if($multireferral == 0){echo 120;}else {echo 108;}?>px; background-color:black;"><a href="#ALL" lang="en" data-toggle="tab" style="height:40px; color:balck;"><i class="icon-lock icon-large" style="color:RGB(111,111,111); font-size: 1.0em; width:100%;"></i><div style="width:100%"></div>Locked</a></li>
            
          </ul> 
          <style>
            .slider-image {
                cursor: pointer;
                display: block;
                z-index: 3;
                position: absolute; /*newly added*/
                color: #22aeff;
                /*background-color: rgba(219, 215, 215, 0.5);*/
                /*background-color: rgb(222, 222, 228);*/
            }
          </style>
         <div id="myTabContent" class="tab-content tabs-main-content padding-null" style="width: 1077px;" >
                <div  class="tab-pane tab-overflow-main fade in active" id="ALL" style="overflow: auto;overflow-y: hidden;">
					<div class="horizontal-only notes" id="hscroll" style="height: 290px; width:100%; margin-top:10px; background-color:white;" ><div id="sliderleft" class="slider-image" style="float:left;margin-top:100px"><i class="icon-chevron-sign-left icon-5x"></i></div><div id="sliderright" class="slider-image" style="margin-left: 1022px;margin-top: 105px;"><i class="icon-chevron-sign-right icon-5x"></i> </div> 
						<div id="StreamContainerALL" class="frame" style="width:100%; height:290px; overflow: auto; margin-top:10px;" ></div> 
						<div id="StreamContainerIMAG"  style="width:100%; height:290px; overflow: auto; margin-top:10px;"></div>
						<div id="StreamContainerLABO"  style="width:100%; height:290px; overflow: auto; margin-top:10px;"></div>
						<div id="StreamContainerDRRE"  style="width:100%; height:290px; overflow: auto; margin-top:10px;"></div>
						<div id="StreamContainerOTHE"  style="width:100%; height:290px; overflow: auto; margin-top:10px;"></div>
						<div id="StreamContainerNA"  style="width:100%; height:290px; overflow: auto; margin-top:10px;"></div>
						<div id="StreamContainerSUMM"  style="width:100%; height:290px; overflow: auto; margin-top:10px;"></div>
						<div id="StreamContainerPICT"  style="width:100%; height:290px; overflow: auto; margin-top:10px;"></div>
						<div id="StreamContainerPATN"  style="width:100%; height:290px; overflow: auto; margin-top:10px;"></div>
						<div id="StreamContainerSUPE"  style="width:100%; height:290px; overflow: auto; margin-top:10px;"></div>
                        <div id="StreamContainerLCKR"  style="width:100%; height:290px; overflow: auto; margin-top:10px;"></div>
						
					</div> 
					<!--NOTES END-->
                </div>
		 </div>
         <div id="stream_indicator" style="width: 52px; height: 42px; margin-left: auto; margin-right: auto; margin-top: -180px; margin-bottom: 140px; display: none;">
            <img src="images/load/29.gif"  alt="">
        </div>
	  </div>
        
				
		<!--<div id="tabsWithStyle" style="float:left; width:370px; height:50px; ">
            <p>
                <div id="id="StreamContainerALL"" style="width:100%; height:290px; overflow: auto; margin-top:-20px;">
                <div id="ReportStream" style="">
                </div>
                </div>       
            <p id="NumberRA" style="color:#22aeff; font-size:16px; text-align:center; margin:0 auto; height:0px;">
            </p>
            </p>
        </div> -->
		
		<div id="add_credentials" lang="en" title="Add Credentials For E-Prescribing" style="display: none;">
		 
		 <div style="width: 500px; height: 25px; color: #777; margin-top: 25px;"> 
                <span style="float: left; margin-top: 6px; margin-right: 90px;" lang="en">NPI Number :</span>
                <input type="text" id="npi_number" style="float: left; width: 250px;" >
                <span style="float: left; margin-top: 6px; margin-right: 83px;" lang="en">DEA Number :</span>
                <input type="text" id="dea_number" style="float: left; width: 250px;" >
			</div>
			
			<div style="width: 500px; height: 25px; color: #777; margin-top: 25px;"> 
				
			
			<div style="width: 200px; height: 30px; margin: auto;">
                 <button id="create_credentials" style="width: 200px; height: 30px; background-color: #22AEFF; color: #FFF; outline: 0px; border: 0px solid #FFF; border-radius: 7px; margin-top: 15px;" lang="en">
                    Add Credentials
                 </button>
             </div>
			
			
             </div>
			 
			 
		</div>
		
		<div id="add_locations" lang="en" title="Add Locations For E-Prescribing" style="display: none;">
		 
		 <div style="width: 500px; height: 25px; color: #777; margin-top: 25px;"> 
                <span style="float: left; margin-top: 6px; margin-right: 90px;" lang="en">Clinic Name :</span>
                <input type="text" id="cname" style="float: left; width: 250px;" >
				<span style="float: left; margin-top: 6px; margin-right: 101px;" lang="en">Address1 :</span>
                <input type="text" id="address1" style="float: left; width: 250px;" >
				<span style="float: left; margin-top: 6px; margin-right: 101px;" lang="en">Address2 :</span>
                <input type="text" id="address2" style="float: left; width: 250px;" >
				<span style="float: left; margin-top: 6px; margin-right: 141px;" lang="en">City :</span>
                <input type="text" id="city_holder" style="float: left; width: 250px;" >
				<span style="float: left; margin-top: 6px; margin-right: 130px;" lang="en">State :</span>
                <input type="text" maxlength="2" id="state_holder" style="float: left; width: 250px;" >
				<span style="float: left; margin-top: 6px; margin-right: 146px;" lang="en">Zip :</span>
                <input type="text" maxlength="5" id="zip_holder" style="float: left; width: 250px;" >
				<span style="float: left; margin-top: 6px; margin-right: 125px;" lang="en">Phone :</span>
                <input type="text" maxlength="10" id="phone_holder" style="float: left; width: 250px;" >
				<span style="float: left; margin-top: 6px; margin-right: 143px;" lang="en">Fax :</span>
                <input type="text" maxlength="10" id="fax_holder" style="float: left; width: 250px;" >
			</div>
			
			<div style="width: 500px; height: 25px; color: #777; margin-top: 25px;"> 
				
			
			<div style="width: 200px; height: 30px; margin: auto;">
                 <button id="create_location" style="width: 200px; height: 30px; background-color: #22AEFF; color: #FFF; outline: 0px; border: 0px solid #FFF; border-radius: 7px; margin-top: 15px;" lang="en">
                    Add Location
                 </button>
             </div>
			
			
             </div>
			 
			 
		</div>
		
		<div id="add_practices" lang="en" title="Add Locations For E-Prescribing" style="display: none;">
		 
		 <div style="width: 500px; height: 25px; color: #777; margin-top: 25px;"> 
                <span style="float: left; margin-top: 6px; margin-right: 90px;" lang="en">Practice Name :</span>
                <input type="text" id="practice2" style="float: left; width: 250px;" >
			</div>
			
			<div style="width: 500px; height: 25px; color: #777; margin-top: 25px;"> 
				
			
			<div style="width: 200px; height: 30px; margin: auto;">
                 <button id="create_practice" style="width: 200px; height: 30px; background-color: #22AEFF; color: #FFF; outline: 0px; border: 0px solid #FFF; border-radius: 7px; margin-top: 15px;" lang="en">
                    Add Practice
                 </button>
             </div>
			
			
             </div>
			 
			 
		</div>
		
		
	 <div class="clear"></div><br/><br/>
       
        <div class="label label-success " id="EtiTML" style="left:0px; margin-left:0px; margin-bottom:-10px; font-size:14px; padding: 7px; width: 97%; text-align: center; color: #FFF;"  lang="en">
            <i id="TLineScrollDown" class="icon-caret-down icon-2x navArrows" style="float:right;margin-left:-15px;margin-top:-7px;color:#FFF"></i>                     
            <i id="TLineScrollUp" class="icon-caret-up icon-2x navArrows" style="float:right;margin-left:-15px;margin-top:-7px;color:#FFF;display:none"></i>
            Health History Timeline
            
        </div>
		<span style="display:none;color:#22aeff;margin-left:100px" id="H2M_Timeline_Spin_Stream" lang="en"><i class="icon-spinner icon-2x icon-spin" ></i> <b> Loading... </b> </span>
		 
		<div class="grid span3 " id="timeline-box" style="margin-left:0px; width:98%; height:400px; margin-bottom:20px;display:none;text-align:center;float:inherit" >
			
         	<div id="timeline-embed" ></div>
         	
				
        </div>
                       

  
     <div class="grid span3" style="width:98%;margin-left:0px;">
          <div class="grid-title a" style="height:60px; margin-bottom:30px;">
           <div class="pull-left a" id="AreaTipo" style="font-size:24px;"></div>
		   
		   <!--div class="pull-right">
               <div class="grid-title-label" ><button id="PrintImage" class="btn" style="margin-left:10px;"><i class="icon-print"></i>Print</button></div>
           </div-->
		   <div class="pull-right">
				<button id="BotonMod" data-target="#header-modal3" data-toggle="modal" class="btn" style="float:right; margin-right:10px;" lang="en"><i class="icon-indent-left"></i>Classification</button>
		   </div>
		   <!--div class="pull-right">
               <div class="grid-title-label" id="History" ><span class="label label-info" style="left:0px; margin-left:20px; margin-top:20px; margin-bottom:5px; font-size:16px;" lang="en">Detailed Report Tracking History</span></div>
           </div-->
		   <!-- Commented out the below div by Pallab to remove unwanted label and undefined stuff -->
       <!--    <div class="pull-right">
               <div class="grid-title-label" id="AreaFecha" ><span class="label label-warning" ></span></div>
           </div> -->
		   
          <div class="clear"></div>  
           <div>
           <span class="ClClas" id="AreaClas" style="font-size:18px; color:grey;"></span>
           </div>
           <div class="clear"></div>   
          </div>
          
          <div class="grid-content" id="AreaConten" style="">
		  
		 
		  
		  
			
 
             <img id="ImagenAmp" style="margin:0 auto;" src="">
          </div>
		  <div id="media-active"></div>
        </div>
        <div style="clear:both;"></div>
        <span style="color:grey; font-size:14px; margin:20px;">&copyCopyright 2015 Inmers LLC. Health2.Me is a property of Inmers LLC. All Rights Reserved. <a href="legal/tos.html" tabindex="7" target="_blank">Terms of Service</a> | <a href="legal/privacy.html" tabindex="9" target="_blank">Privacy Policy</a></span>
                      
     </div>
     <!--CONTENT MAIN END-->
    
    </div>
	</div>
    <!--Content END-->

    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->

	<script type="text/javascript" src="js/storyjs-embed.js"></script>
      
    <script src="js/kinetic-v4.5.5.min.js"></script>
    <script src="js/konva.min.js"></script>
    <script src="js/moment-with-locales.js"></script>
    <script src="js/moment-timezone.js"></script>
    <script type="text/javascript" src="js/H2MRange.js"></script>
    
    <script src="js/jquery.easing.1.3.js"></script>
    <script src="js/tipped.js"></script>
    <script src="js/imagesloaded.pkgd.min.js"></script>
    <script src="js/H2M_Timeline.js"></script>
    <script src="js/h2m_probegraph-newdes.js"></script>
      
    <script src="build/js/intlTelInput.js"></script>
	<script src="js/isValidNumber.js"></script>
      
    <script type="text/javascript" src="https://js.stripe.com/v2/"></script>

    <!--<script src="js/jquery.min.js"></script>
    <script src="js/jquery-ui.min.js"></script> -->
   
    <script src="js/bootstrap.min.js"></script>
    <!--<script src="js/bootstrap-datepicker.js"></script>-->
    <script src="js/jquery.timepicker.js"></script>
    <script src="js/bootstrap-colorpicker.js"></script>
	<!--<script src="js/bootstrap-modal.js"></script>
	<script src="js/bootstrap-dropdown.js"></script>-->
    <script src="js/google-code-prettify/prettify.js"></script>
    <script src='js/sweet-alert.min.js'></script>
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
    <script src="js/timezones.js"></script>
	<script src="js/detectRTC.js"></script>
    
    
	<!--<script src="js/application.js"></script>-->
    
     
  <!--  <script type="text/javascript" src="http://code.jquery.com/jquery-1.8.0.min.js"></script>--->
    <script type="text/javascript" src="js/jquery.tooltipster.min.js"></script>

	<!--<script src="imageLens/jquery.js" type="text/javascript"></script>-->
	<script src="imageLens/jquery.imageLens.js" type="text/javascript"></script>
	<script src="audio/AudioContextMonkeyPatch.js"></script>
	<script src="audio/recorder.js"></script>
	<!-- <script src="audio/main.js"></script>  -->
	<script src="audio/audiodisplay.js"></script> 
	<!--<script src="fileupload/filedrag.js"></script> -->
	
	<!-- CHECKOUT MODULES -->
    <script src="js/CHECKOUT/connect_member_button.js"></script>
    <script src="js/CHECKOUT/probe_checkout.js"></script>
	
	<script src="realtime-notifications/lib/gritter/js/jquery.gritter.min.js"></script>
	<!--<script src="realtime-notifications/pusher.min.js"></script>
    <script src="realtime-notifications/PusherNotifier.js"></script>-->
    <script src="js/socket.io-1.3.5.js"></script>
    <script src="push/push_client.js"></script>
    <script type="text/javascript" src="js/timer.js"></script>
	<link href="realtime-notifications/lib/gritter/css/jquery.gritter.css"rel="stylesheet" type="text/css" />
	
	 <script>
		$(function() {
	    //var pusher = new Pusher('d869a07d8f17a76448ed');
	    //var channel_name=$('#MEDID').val();
		//var channel = pusher.subscribe(channel_name);
		//var notifier=new PusherNotifier(channel);
            
        var push = new Push($("#BOTHID").val(), window.location.hostname + ':3955');
            
        push.bind('notification', function(data) {
            displaynotification('New Message', data);
        });
		
	  });

	</script>
    <!--
    <script>
        // Start of uiDialog widget extension... 
        var _init = $.ui.dialog.prototype._init; 
        $.ui.dialog.prototype._init = function() { 
            var self = this; 
            _init.apply(this, arguments); 
            this.uiDialog.bind('dragstop', function(event, ui) { 
                if (self.options.sticky) { 
                    var left = Math.floor(ui.position.left) - $ 
        (window).scrollLeft(); 
                    var top = Math.floor(ui.position.top) - $(window).scrollTop 
        (); 
                    self.options.position = [left, top]; 
                }; 
            }); 
            if (window.__dialogWindowScrollHandled === undefined) { 
                window.__dialogWindowScrollHandled = true; 
                $(window).scroll(function(e) { 
                    $('.ui-dialog-content').each(function() { 
                        var isSticky = $(this).dialog('option', 'sticky') && $ 
        (this).dialog('isOpen'); 
                        if (isSticky) { 
                            var position = $(this).dialog('option', 
        'position'); 
                            $(this).dialog('option', 'position', position); 
                        }; 
                    }); 
                }); 
            }; 
        }; 
        $.ui.dialog.defaults.sticky = false; 
        // End of uiDialog widget extension
    </script>
    -->
    <script type="text/javascript" src="js/h2m_patientdetailmed-new.js"></script>
	<!--<script src="fileupload/filedrag.js"></script>-->
	
  

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
     while($row = $sql->fetch(PDO::FETCH_ASSOC)){
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
            "media":"/images/ReportsGeneric.png", 
            "credit":"(c) health2.me",
            "caption":"Use side arrows for browsing"
        },
        "date": [               
        ';



//getting IdPin for blind reports

//$blindReprtId=array();
//$blindReprtId=blindReports($MedID,$queUsu);
//BLOCKSLIFEPIN $result = mysql_query("SELECT * FROM BLOCKS ORDER BY FechaInput DESC");

$sql_query=$con->prepare("select distinct(idDoctor) from doctorsgroups where idDoctor IN (select Idcreator from usuarios where Identif=?) or idGroup IN (select idGroup from doctorsgroups where idDoctor IN (select Idcreator from usuarios where Identif=?))");
$sql_query->bindValue(1, $queUsu, PDO::PARAM_INT);
$sql_query->bindValue(2, $queUsu, PDO::PARAM_INT);
$res=$sql_query->execute();


	$privateDoctorID=array();
	$num=0;
	while($rowp = $sql_query->fetch(PDO::FETCH_ASSOC)){
		$privateDoctorID[$num]=$rowp['idDoctor'];
		$num++;
	}
	/*if($privateDoctorID==null)
		$privateDoctorID[0]=$MedID;*/

$sql_que=$con->prepare("select Id from tipopin where Agrup=9");
$res=$sql_que->execute();

	$privatetypes=array();
	$num1=0;
	while($rowpr = $sql_que->fetch(PDO::FETCH_ASSOC)){
		$privatetypes[$num1]=$rowpr['Id'];
		$num1++;
}
#####changes for blind report#########
/*$sql1="SELECT Idpin,Tipo FROM LifePin where IdUsu ='$queUsu' and Tipo NOT IN (select Id from tipopin where Agrup=9) and IdMed IS NOT NULL and (IdMed NOT IN ((select distinct(idDoctor) from doctorsgroups where idGroup IN (select idGroup from doctorsgroups where idDoctor= '$MedID'))) 
and IdMed NOT IN (select distinct(idDoctor) from doctorsgroups where idGroup IN (select idGroup from doctorsgroups where idDoctor IN (Select idmed from doctorslinkdoctors where idmed2='$MedID' and IdPac='$queUsu'))))";
//and IdMed NOT IN (Select idmed from doctorslinkdoctors where idmed2='$MedID' and IdPac='$queUsu'))";*/

//Changes for bidirectional permission to view report

$sql1=$con->prepare("SELECT Idpin,Tipo FROM lifepin where (markfordelete IS NULL or markfordelete=0) and IdUsu =
? and Tipo NOT IN (select Id from tipopin where Agrup=9) and IdMed !=0 and IdMed IS NOT NULL and IdMed!=
? and IdMed NOT IN (select distinct(idDoctor) from doctorsgroups where idGroup IN (select idGroup from doctorsgroups where idDoctor=
?)) and IdMed NOT IN (select idmed from doctorslinkdoctors where idmed2=
? and IdPac=
?) and IdMed NOT IN (select distinct(idDoctor) from doctorsgroups where idGroup IN (select idGroup from doctorsgroups where idDoctor IN (Select idmed from doctorslinkdoctors where idmed2=
? and IdPac=
?))) and IdMed NOT IN (select idmed2 from doctorslinkdoctors where idmed=
? and IdPac=
?) and IdMed NOT IN (select distinct(idDoctor) from doctorsgroups where idGroup IN (select idGroup from doctorsgroups where idDoctor IN (Select idmed2 from doctorslinkdoctors where idmed=
? and IdPac=
?)))");
$sql1->bindValue(1, $queUsu, PDO::PARAM_INT);
$sql1->bindValue(2, $MedID, PDO::PARAM_INT);
$sql1->bindValue(3, $MedID, PDO::PARAM_INT);
$sql1->bindValue(4, $MedID, PDO::PARAM_INT);
$sql1->bindValue(5, $queUsu, PDO::PARAM_INT);
$sql1->bindValue(6, $MedID, PDO::PARAM_INT);
$sql1->bindValue(7, $queUsu, PDO::PARAM_INT);
$sql1->bindValue(8, $MedID, PDO::PARAM_INT);
$sql1->bindValue(9, $queUsu, PDO::PARAM_INT);
$sql1->bindValue(10, $MedID, PDO::PARAM_INT);
$sql1->bindValue(11, $queUsu, PDO::PARAM_INT);
$q1=$sql1->execute();


	$size=0;
	$blindReportId=array();
	while($row1 = $sql1->fetch(PDO::FETCH_ASSOC)){

		$ReportId=$row1['Idpin'];
		$type=$row1['Tipo'];
		/*if($type==null)
			$type=-1;*/
		if(in_array($type,$privatetypes)){
			if(!in_array($MedID,$privateDoctorID)){
				continue;
			}
		}
		$query=$con->prepare("SELECT estado from doctorslinkusers where IdMed=? and IdUs=? and Idpin=? ");
		$query->bindValue(1, $MedID, PDO::PARAM_INT);
		$query->bindValue(2, $queUsu, PDO::PARAM_INT);
		$query->bindValue(3, $ReportId, PDO::PARAM_INT);
		$q11=$query->execute();
		
		if($rowes = $query->fetch(PDO::FETCH_ASSOC)){
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
 $res=$sql_que->execute();

	$deletedreports=array();
	$num2=0;
	if($res){
	while($rowpr = $sql_que->fetch(PDO::FETCH_ASSOC)){

		$deletedreports[$num2]=$rowpr['IdPin'];
		$num2++;
	}}else{
	$deletedreports[0]=0;
	}

$result = $con->prepare("SELECT * FROM lifepin WHERE IdUsu=? and emr_old=0 ORDER BY Fecha DESC LIMIT 50");
$result->bindValue(1, $queUsu, PDO::PARAM_INT);
$result->execute();

$numero=$result->rowCount();
$n=0;

while ($row = $result->fetch(PDO::FETCH_ASSOC))
{    
 
	$extensionR = substr($row['RawImage'],strlen($row['RawImage'])-3,3);
	$ImageRaiz = substr($row['RawImage'],0,strlen($row['RawImage'])-4);
	$type=$row['Tipo'];

	if(!in_array($row['IdPin'], $blindReportId)){

		  //For private report functionality
		  if(in_array($type,$privatetypes)){
     		if(!in_array($MedID,$privateDoctorID)){
     				continue;
			}
		 }
        
        $decrypt_id = $MedID;
        if($decrypt_id < 0)
        {
            $decrypt_id = $IdUsu;
        }

		 if(!in_array($row['IdPin'], $deletedreports)){
		  if ($extensionR!='jpg')
			{
				decrypt_files($row['RawImage'],$decrypt_id,$pass);
				$selecURL ='temp/'.$decrypt_id.'/PackagesTH_Encrypted/'.$ImageRaiz.'.png';
				$selecURLAMP ='temp/'.$decrypt_id.'/Packages_Encrypted/'.$ImageRaiz.$extensionR;
			}
			else {
			if($extensionR == 'jpg')
			{
				decrypt_files($row['RawImage'],$decrypt_id,$pass);
				$selecURL ='temp/'.$decrypt_id.'/PackagesTH_Encrypted/'.$row['RawImage'];
				$selecURLAMP ='temp/'.$decrypt_id.'/Packages_Encrypted/'.$row['RawImage'];
			}
			
			else if	($row['CANAL']==7){
				decrypt_files($row['RawImage'],$decrypt_id,$pass);
				$selecURL ='temp/'.$decrypt_id.'/PackagesTH_Encrypted/'.$row['RawImage'];
				$selecURLAMP ='temp/'.$decrypt_id.'/Packages_Encrypted/'.$row['RawImage'];
			} else {
				decrypt_files($row['RawImage'],$decrypt_id,$pass);
				$subtipo = substr($row['RawImage'], 3 , 2);
				if ($subtipo=='XX')  {decrypt_files($row['RawImage'],$decrypt_id,$pass); $selecURL ='temp/'.$decrypt_id.'/Packages_Encrypted/'.$row['RawImage']; }
				else { $selecURL ='eMapLife/PinImageSetTH/'.$row['RawImage']; }
				// COMPROBACIÓN DE EXISTENCIA DEL ARCHIVO (PARA LOS CASOS DE EMAPLIFE iOS o ANDROID QUE TODAVIA NO GENERAN THUMBNAILS Y NO REFERENCIAN AL DIRECTORIO -TH
				$file = $selecURL;
				$file_headers = @get_headers($file);
				if($file_headers[0] == 'HTTP/1.1 404 Not Found') {
			  	  	$exists = false;
			  	  	$selecURL ='eMapLife/PinImageSet/'.$row['RawImage'];
			  	  }
			  	  else {
				  	  $exists = true;
				  	  }
				}
			}
		}else{
			$selecURL ='images/deletedfile.png';
		    $selecURLAMP ='images/deletedfile.png';
		}
	}else{
				 $selecURL ='images/lockedfile.png';
				 $selecURLAMP ='images/lockedfile.png';
		  }

if (!$row['Tipo']){$indi = 999;}else{$indi = $row['Tipo'];};
//echo $Tipo[$indi];
//echo $Tipo[$indi];

//if (!$row['EvRuPunt']){$indi2 = 999;}else{$indi2 = $row['EvRuPunt'];}; 

     $Evento = $row['Evento'];
     $sqlE=$con->prepare("SELECT * FROM usueventos where IdUsu =? and IdEvento =? ");
	 $sqlE->bindValue(1, $queUsu, PDO::PARAM_INT);
	 $sqlE->bindValue(2, $Evento, PDO::PARAM_INT);
	 $qE = $sqlE->execute();
	 
     $rowE = $sqlE->fetch(PDO::FETCH_ASSOC);
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

	 $con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }			

	 $IdMed=$doctorid;
	 $IdUsu=$patientid;
	//$sql1="SELECT Idpin FROM LifePin where IdUsu ='$IdUsu' and IdMed IS NOT NULL and (IdMed NOT IN ((select distinct(idDoctor) from doctorsgroups where idGroup IN (select idGroup from doctorsgroups where idDoctor= '$IdMed'))) and IdMed NOT IN (Select idmed from doctorslinkdoctors where idmed2='$IdMed' and IdPac='$IdUsu'))";
	//changes for the bidirectional 
	$sql1=$con->prepare("SELECT Idpin FROM lifepin where IdUsu =? and IdMed IS NOT NULL and IdMed NOT IN ((select distinct(idDoctor) from doctorsgroups where idGroup IN (select idGroup from doctorsgroups where idDoctor= ?))) and IdMed NOT IN (Select idmed from doctorslinkdoctors where idmed2=? and IdPac=?) and IdMed NOT IN (Select idmed2 from doctorslinkdoctors where idmed=? and IdPac=?)");
	$sql1->bindValue(1, $IdUsu, PDO::PARAM_INT);
	$sql1->bindValue(2, $IdMed, PDO::PARAM_INT);
	$sql1->bindValue(3, $IdMed, PDO::PARAM_INT);
	$sql1->bindValue(4, $IdUsu, PDO::PARAM_INT);
	$sql1->bindValue(5, $IdMed, PDO::PARAM_INT);
	$sql1->bindValue(6, $IdUsu, PDO::PARAM_INT);
	$q1=$sql1->execute();
	

	$size=0;
	$blindRepId=array();
	while($row1 = $sql1->fetch(PDO::FETCH_ASSOC)){

		$ReportId=$row1['Idpin'];
		$query=$con->prepare("SELECT estado from doctorslinkusers where IdMed=? and IdUs=? and Idpin=? ");
		$query->bindValue(1, $IdMed, PDO::PARAM_INT);
		$query->bindValue(2, $IdUsu, PDO::PARAM_INT);
		$query->bindValue(3, $ReportId, PDO::PARAM_INT);
		$q11=$query->execute();
		
		if($rowes = $query->fetch(PDO::FETCH_ASSOC)){
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


	$con = null;
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
