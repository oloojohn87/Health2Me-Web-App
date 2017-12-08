<?php
session_start();
set_time_limit(180);
require("environment_detail.php");
require("push_server.php");
require('getFullUsersMEDCLASS.php');
require("displayExitClass.php");
ini_set("display_errors", 0);
//require_once("push_server.php");
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];
 

if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 1800)) {
    // last request was more than 30 minutes ago
    session_unset();     // unset $_SESSION variable for the run-time 
    session_destroy();   // destroy session data in storage
    echo "<META http-equiv='refresh' content='0;URL=index.html'>";
}
$_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp

$telemed_on = false;
$telemed_value=0;
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
if(isset($_SESSION['MEDID']) && isset($_SESSION['Previlege']) && $_SESSION['MEDID'] != $IdUsu)
{
    
    $IdMed = $_SESSION['MEDID'];
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

$doc_id = $IdMed;
$mem_id = $IdUsu;

$checker = new checkPatientsClass();



$checker->setters($mem_id, $doc_id);
$checker->checker();

/*if($checker->status1 != 'true' && $checker->status2 != 'true' && $checker->status3 != 'true' && $checker->status4 != 'true' && $_GET['TELEMED'] != 2 && $_GET['TELEMED'] != 1){
    $exit_display = new displayExitClass();
    $exit_display->displayFunction(5);
    die();
}*///else if($checker->status5 == 'false'){

//   $exit_display = new displayExitClass();
 //   $exit_display->displayFunction(-1);
 //   die();

//}



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

//	$MedUserLogo = $row['ImageLogo'];

}
else
{
$exit_display = new displayExitClass();
$exit_display->displayFunction(2);
die;
}

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
if(isset($_SESSION['MEDID']))
{
        $doctorId = $_SESSION['MEDID'];
        
    
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
    <link href="css/style.css" rel="stylesheet">
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
    
	<!--<link rel="stylesheet" href="css/icon/font-awesome.css">-->
	<link rel="stylesheet" href="font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/bootstrap-responsive.css">
    <link rel="stylesheet" href="css/jvInmers.css">

    <link rel="stylesheet" type="text/css" href="css/tooltipster.css" />
    
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
	
	<!-- Create language switcher instance and set default language to en-->
	<!--<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js" type="text/javascript"></script>-->
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
		canvas { display: inline-block; background: #202020; box-shadow: 0px 0px 10px blue;}
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

	<!--<div class="loader_spinner"></div>-->
	
			<input type="hidden" id="MEDID" Value="<?php echo $IdMed; ?>">	
	    	<input type="hidden" id="IdMEDEmail" Value="<?php echo $IdMEDEmail; ?>">	
	    	<input type="hidden" id="IdMEDName" Value="<?php echo $IdMEDName; ?>">	
	    	<input type="hidden" id="IdMEDSurname" Value="<?php echo $IdMEDSurname; ?>">	
	    	<input type="hidden" id="IdMEDLogo" Value="<?php if(isset($MedLogo)) echo $MedLogo; ?>">	
	        <input type="hidden" id="USERID" Value="<?php echo $USERID; ?>">
			<input type="hidden" id="NPI" Value="<?php echo $npi; ?>">
			<input type="hidden" id="DEA" Value="<?php echo $dea; ?>">
	<!--Header Start-->
	
	<div class="header" >
    		
          <?php
  		        if ($_SESSION['CustomLook'] == 'COL') {
                    echo '<a href="index-col.html" class="logo"><h1>health2.me</h1></a>';
                } else {
                    echo '<a href="index.html" class="logo"><h1>health2.me</h1></a>';
                }    
           
           ?>
           
		   <div style="float:left;">
		   <a href="#en" onclick="setCookie('lang', 'en', 30); return false;"><img src="images/icons/english.png"></a>
		   </br>
			<a href="#sp" onclick="setCookie('lang', 'th', 30); return false;"><img src="images/icons/spain.png"></a>
			</div>
		   
           <div class="pull-right">
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
	<div id="content" style="padding-left:0px; background: #F9F9F9; overflow:auto;">
    
        <!-- Lines of code added by Pallab -->
         <form method="POST" enctype="multipart/form-data" action="save.php" id="myForm">
            <input type="hidden" name="img_val" id="img_val" value="" />
        </form>  
         <!-- Lines of code added by Pallab -->
        
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

	  
	  
	  <!-- Modal for Evolution support -->
	  <button id="modal_evolution" data-target="#header-evolution" data-toggle="modal" class="btn btn-warning" style="display: none;" lang="en">Modal with Header</button> 
   	  <div id="header-evolution" class="modal hide" style="display: none;" aria-hidden="true">
         <div class="modal-header" lang="en">
             <button class="close" type="button" data-dismiss="modal">×</button>
                  Evolution Input
         </div>
         <div class="modal-body" style="text-align: center;font: 14pt Arial, sans-serif; background: lightgrey;">
			<center>
				<table style="background:transparent; height:150px;" >
					<tr>
						<td style="height:24px;" lang="en">Date : </td>
						<td style="height:24px;"><input id="evolution_date"  /></td>
					</tr>
					<tr>
					</tr>
					<tr>
						<td style="height:24px;" lang="en">Note : </td>
						<td style="height:24px;"><textarea rows="5" cols="150" style="width:420px;resize:none" id="evolution_text">	</textarea></td>
					</tr>
				</table>
			</center>
		 </div>
        
        
         <div class="modal-footer">	
			 <a href="#" class="btn btn-success" data-dismiss="modal" id="AddEvolution" lang="en">Add Data</a>
	         <a href="#" class="btn btn-primary" data-dismiss="modal" id="CloseEvolution" lang="en">Close</a>
         </div>
      </div>  
	  <!--- Evolution Support  --->
        
    <!-- Start of Modal for PhoneNotes -->
    <button id="modal_phoneNotes" data-target="#header-phoneNotes" data-toggle="modal" class="btn btn-warning" style="display: none;" lang="en">Modal with Header</button> 
   	  <div id="header-phoneNotes" class="modal hide" style="display: none;" aria-hidden="true">
         <div class="modal-header" lang="en">
             <button class="close" type="button" data-dismiss="modal">×</button>
                  Evolution Input
         </div>
         <div class="modal-body" style="text-align: center;font: 10pt Arial, sans-serif; background: lightgrey;">
			<center>
				<table style="background:transparent; height:150px;" >
					<!-- Commented out by Pallab on request of Javier as date is not required for notes pdf <tr>
						<td style="height:24px;" lang="en">Date</td>
                    </tr>
                    <tr>    
						<td style="height:24px;"><input id="note_date"  /></td>
					</tr> -->
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
             <a href="#" class="btn btn-primary" data-dismiss="modal" id="CloseModal22" lang="en">Close</a>
         </div>
      </div>  
	  <!--- Report History  ---> 
  
       <!--   Pop Up For Maps -->
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
                //echo '<li><a href="dashboard.php"  lang="en">Dashboard</a></li>';
                echo '<li><a href="patients.php"  class="act_link" lang="en">Members</a></li>';
                if ($privilege==1)
                {
                    echo '<li><a href="medicalConnections.php"  lang="en">Doctor Connections</a></li>';
                    echo '<li><a href="PatientNetwork.php"  lang="en">Patient Network</a></li>';
                }
                    echo '<li><a href="medicalConfiguration.php" lang="en">Configuration</a></li>';
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
			
			<div  id="RepoThumb" style="width:70px; float:right; -webkit-box-shadow: 3px 3px 14px rgba(25, 25, 25, 0.5); -moz-box-shadow:3px 3px 14px rgba(25, 25, 25, 0.5); box-shadow:3px 3px 14px rgba(25, 25, 25, 0.5);"></div>
           <div class="ContenDinamico">
		     
	         <p><H4 lang="en">Class:  </H3>
	               <div class="formRight" stytle="width:50px;">
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
	         <p><H5 lang="en">Clinical Area:  </H3></p>
         </div>
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
			$fileName = "PatientImage/".$USERID.".jpg";
			 if(file_exists($fileName))
			 {
                $doctorId = -1;
                if(isset($_SESSION['MEDID']))
                {
                    $doctorId = $_SESSION['MEDID'];
                }
				shell_exec("echo '".$pass."' | openssl aes-256-cbc -pass stdin -d -in PackagesTH_Encrypted/".$ImageRaiz.".".$extension." -out temp/".$queMed."/PackagesTH_Encrypted/".$ImageRaiz.".".$extension);
				//echo 'Decrypt_Image.bat PatientImage '.$USERID.'.jpg '.$_SESSION['MEDID'].' '.$pass.' 2>&1';
                
				$file = "PatientImage/".$USERID.".jpg";
				$style = "max-height: 80px; max-width:80px;";
			 }
			 else
			 {
				$file = "/PatientImage/defaultDP.jpg";
				$style = "max-height: 80px; max-width:80px;";
			 }
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
				<span id="NombreComp" style="font: bold 24px Arial, Helvetica, sans-serif; color: #3D93E0; cursor: auto;  margin-left:10px;"><?php echo $show_text; ?> <?php echo $show_text2; ?></span>
		  		<!-- Commented out below lines by Pallab as we do not need the IDUsFIXED and IdUsFIXEDNAME -->
            <!--    <span id="IdUsFIXED" style="font-size: 12px; color: #3D93E0; font-weight: normal; font-family: Arial, Helvetica, sans-serif; display: block;margin-left:10px;"><?php echo $IdUsFIXED;?></span>
			  	<span id="IdUsFIXEDNAME" style="font-size: 14px; color: GREY; font-weight: bold; font-family: Arial, Helvetica, sans-serif;margin-left:10px; "><?php echo $IdUsFIXEDNAME;?></span><br/> -->
				<span id="email" style="font-size: 14px; color: #5EB529; font-weight: bold; font-family: Arial, Helvetica, sans-serif; margin-left:10px;"><?php echo $email;?></span>
				</td>
				
				</tr>
				</table>
				
	<!-- image upload changes end-->
	

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
		echo '<div style="width:90%; margin-top:12px; float:left;"><span class="label label-success" style="font-size:14px;">Welcome Dr. '.$IdMEDName.' '.$IdMEDSurname.', this patient has been referred by <i>Dr. '.$otherdocname.' '.$otherdocSurname.'.</i></span></div>';

		}else{
		echo '<div style="width:90%; margin-top:12px; float:left;"><span class="label label-success" style="font-size:14px;">Welcome Dr. '.$IdMEDName.' '.$IdMEDSurname.', this patient has been referred by <i>Dr. '.$otherdocname.' '.$otherdocSurname.'.</i></span></div>';
		}
			//echo '<div style="width:90%; margin-top:12px; float:left;"><span class="label label-success" style="font-size:14px;">Welcome Dr. '.$IdMEDName.' '.$IdMEDSurname.', this patient has been referred by <i>Dr. '.$otherdocname.' '.$otherdocSurname.'.</i></span></div>';
		}else{

		if($otherdocname=='' and $otherdocSurname==''){
		//echo '<div style="width:90%; margin-top:12px; float:left;"><span class="label label-success" style="font-size:14px;">Welcome Dr. '.$IdMEDName.' '.$IdMEDSurname.', this patient has been referred by <i>Dr. '.$otherdocname.' '.$otherdocSurname.'.</i></span></div>';
		echo '<div style="width:90%; margin-top:12px; float:left;"><span class="label label-success" style="font-size:14px;">Welcome Dr. '.$IdMEDName.' '.$IdMEDSurname.', you referred this patient to <i>Dr. '.$otherdoctoremail.'.</i></span></div>';
		}else{
		echo '<div style="width:90%; margin-top:12px; float:left;"><span class="label label-success" style="font-size:14px;">Welcome Dr. '.$IdMEDName.' '.$IdMEDSurname.', you referred this patient to <i>Dr. '.$otherdocname.' '.$otherdocSurname.'.</i></span></div>';
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
					 echo '<input type="hidden" id="multireferral_num" value="'.$num_multireferral.'">';
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
											echo '<div style="width:90%; margin-top:12px; float:left;margin-left:20px"><span class="label label-success" style="font-size:14px;">Welcome Dr. '.$IdMEDName.' '.$IdMEDSurname.', this patient has been referred by <i>Dr. '.$otherdocnamearray[$i].' '.$otherdocSurnamearray[$i].'.</i></span></div>';
										}
										else
										{
											echo '<div style="width:90%; margin-top:12px; float:left;margin-left:20px"><span class="label label-success" style="font-size:14px;">Welcome Dr. '.$IdMEDName.' '.$IdMEDSurname.', this patient has been referred by <i>Dr. '.$otherdocnamearray[$i].' '.$otherdocSurnamearray[$i].'.</i></span><button id='.$referral_id_array[$i].' class="btn PrintHighlighted" style="float:right;margin-right:-60px" lang="en"><i class="icon-print"></i>Print Highlighted Reports</button><span style="float:right;margin-right:10px;display:none;color:#22aeff;" id="H2M_Spin_Stream_Print"><i class="icon-spinner icon-2x icon-spin" ></i></span></div>';
										}
								}else{
									if($attachments_dld_array[$i]==0)
									{
									
										echo '<div style="width:90%; margin-top:12px; float:left;margin-left:20px"><span class="label label-success" style="font-size:14px;">Welcome Dr. '.$IdMEDName.' '.$IdMEDSurname.', this patient has been referred by <i>Dr. '.$otherdocnamearray[$i].' '.$otherdocSurnamearray[$i].'.</i></span></span></div>';
									}
									else
									{
										echo '<div style="width:90%; margin-top:12px; float:left;margin-left:20px"><span class="label label-success" style="font-size:14px;">Welcome Dr. '.$IdMEDName.' '.$IdMEDSurname.', this patient has been referred by <i>Dr. '.$otherdocnamearray[$i].' '.$otherdocSurnamearray[$i].'.</i></span></span><button id='.$referral_id_array[$i].' class="btn PrintHighlighted" style="float:right;margin-right:-60px" lang="en"><i class="icon-print"></i>Print Highlighted Reports</button><span style="float:right;margin-right:10px;display:none;color:#22aeff" id="H2M_Spin_Stream_Print"><i class="icon-spinner icon-2x icon-spin" ></i></span></div>';	
									}
								
								
								}
									//echo '<div style="width:90%; margin-top:12px; float:left;"><span class="label label-success" style="font-size:14px;">Welcome Dr. '.$IdMEDName.' '.$IdMEDSurname.', this patient has been referred by <i>Dr. '.$otherdocname.' '.$otherdocSurname.'.</i></span></div>';
								}else{

								if($otherdocnamearray[$i]=='' and $otherdocSurnamearray[$i]==''){
								//echo '<div style="width:90%; margin-top:12px; float:left;"><span class="label label-success" style="font-size:14px;">Welcome Dr. '.$IdMEDName.' '.$IdMEDSurname.', this patient has been referred by <i>Dr. '.$otherdocname.' '.$otherdocSurname.'.</i></span></div>';
									if($attachments_dld_array[$i]==0)
									{
										echo '<div style="width:90%; margin-top:12px; float:left;margin-left:20px"><span class="label label-success" style="font-size:14px;">Welcome Dr. '.$IdMEDName.' '.$IdMEDSurname.', you referred this patient to <i>Dr. '.$otherdoctoremailarray[$i].'.</i></span></div>';
									}
									else
									{
										echo '<div style="width:90%; margin-top:12px; float:left;margin-left:20px"><span class="label label-success" style="font-size:14px;">Welcome Dr. '.$IdMEDName.' '.$IdMEDSurname.', this patient has been referred by <i>Dr. '.$otherdocnamearray[$i].' '.$otherdocSurnamearray[$i].'.</i></span></span><button id='.$referral_id_array[$i].' class="btn PrintHighlighted" style="float:right;margin-right:-60px" lang="en"><i class="icon-print"></i>Print Highlighted Reports</button><span style="float:right;margin-right:10px;display:none;color:#22aeff" id="H2M_Spin_Stream_Print"><i class="icon-spinner icon-2x icon-spin" ></i></span></div>';	
									}
								
								}else{
									if($attachments_dld_array[$i]==0)
									{
										echo '<div style="width:90%; margin-top:12px; float:left;margin-left:20px"><span class="label label-success" style="font-size:14px;">Welcome Dr. '.$IdMEDName.' '.$IdMEDSurname.', you referred this patient to <i>Dr. '.$otherdocnamearray[$i].' '.$otherdocSurnamearray[$i].'.</i></span></div>';
									}
									else
									{
										echo '<div style="width:90%; margin-top:12px; float:left;margin-left:20px"><span class="label label-success" style="font-size:14px;">Welcome Dr. '.$IdMEDName.' '.$IdMEDSurname.', this patient has been referred by <i>Dr. '.$otherdocnamearray[$i].' '.$otherdocSurnamearray[$i].'.</i></span></span><button id='.$referral_id_array[$i].' class="btn PrintHighlighted" style="float:right;margin-right:-60px" lang="en"><i class="icon-print"></i>Print Highlighted Reports</button><span style="float:right;margin-right:10px;display:none;color:#22aeff" id="H2M_Spin_Stream_Print"><i class="icon-spinner icon-2x icon-spin" ></i></span></div>';	
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
                $label_width = "80px";
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
                    echo '<a id="PhoneNotes" value="BotonUpload_New" class="btn ButtonDrAct" style="margin-top: -10px; margin-left: 15px; width: '.$width.'; height: '.$height.'">';
                    echo '    <div style=" margin-left: '.$margin_sides.'; margin-right: -'.$margin_sides.'; margin-top:'.$margin_top.'; width:'.$size.'; height:'.$size.'; font-size: '.$icon_size.'; color: #5EB529;"><i class="icon-file"></i></div>';
                    echo '    <div style="line-height:25px; width: '.$label_width.'; height: 25px; font-size: '.$label_font_size.'; margin-left: '.$label_margin_left.';"><span lang="en">Notes</span></div>';
                    echo '</a>';
                    echo '<a id="Dictate" value="BotonUpload_New" class="btn ButtonDrAct" style="margin-top: -10px; margin-left: 15px; width: '.$width.'; height: '.$height.'">';
                    echo '    <div style=" margin-left: '.$margin_sides.'; margin-right: -'.$margin_sides.'; margin-top:'.$margin_top.'; width:'.$size.'; height:'.$size.'; font-size: '.$icon_size.'; color: #5EB529;"><i class="icon-microphone"></i></div>';
                    echo '    <div style="line-height:25px; width: '.$label_width.'; height: 25px; font-size: '.$label_font_size.'; margin-left: '.$label_margin_left.';"><span lang="en">Dictate</span></div>';
                    echo '</a>';
					echo '<a id="ePrescribe" value="BotonUpload_New" class="btn ButtonDrAct" style="margin-top: -10px; margin-left: 15px; width: '.$width.'; height: '.$height.'">';
                    echo '    <div style=" margin-left: '.$margin_sides.'; margin-right: -'.$margin_sides.'; margin-top:'.$margin_top.'; width:'.$size.'; height:'.$size.'; font-size: '.$icon_size.'; color: #5EB529;"><i class="icon-list"></i></div>';
                    echo '    <div style="line-height:25px; width: '.$label_width.'; height: 25px; font-size: '.$label_font_size.'; margin-left: '.$label_margin_left.';"><span lang="en">E-Prescribe</span></div>';
                    echo '</a>';
                    //echo '<!-- Adding below Notes button for taking notes by doctor during telemedicine phone call -->
                    //<button id="PhoneNotes" class="btn" style="float:left; margin-left:20px; margin-right:10px;"><i class="icon-file-text"></i>Notes</button>';
                    //echo '<button id="Dictate" class="btn" style="float:right;margin-right:10px;"><i class="icon-microphone"></i>Dictate</button>';
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
            <li id="0" class="TABES" style="width:<?php $medid = $_SESSION['MEDID'];
                $userid = $_GET['IdUsu'];
                if($medid != $userid && $multireferral != 1) {echo 108;} else if($multireferral>0) {echo 97;} else if($multireferral == 0){echo 119;}else {echo 108;}?>px; background: none repeat scroll 0% 0% rgb(204, 204, 204); text-align:center;"><a href="#ALL" data-toggle="tab" style="height:40px; font-size:16px;" lang="en"><i class="icon-ok-sign icon-large" style="color:black; font-size: 1.0em; width:100%;"></i><div style="width:100%"></div>ALL</a></li> 
			<?php
                    if($multireferral>0){
					$backgroundcolor=$TipoColorGroup[5];
					echo '<li id="5" class="TABES" style="text-align: center; width:10%; background-color:'.$backgroundcolor.'"><a href="#ALL"   data-toggle="tab" style="height:40px; color:'.$backgroundcolor.'"><i class="icon-star icon-large" style="color:RGB(111,111,111); font-size: 1.0em; width:100%;"></i><div style="width:100%"></div>Highlighted</a></li>';
				}
			?>
			
              <li id="1" class="TABES" style="text-align: center; width:<?php $medid = $_SESSION['MEDID'];
                $userid = $_GET['IdUsu'];
                if($medid != $userid && $multireferral != 1) {echo 108;} else if($multireferral>0) {echo 97;} else if($multireferral == 0){echo 120;}else {echo 108;}?>px; background-color:<?php echo $TipoColorGroup[1] ?>;"><a href="#ALL"  lang="en" data-toggle="tab" style=" color:<?php echo $TipoColorGroup[1] ?>;"><i class="<?php echo $TipoIconGroup[1] ?> icon-large" style="color:RGB(111,111,111); font-size: 1.0em; width:100%;"></i><div style="width:100%"></div>Imaging</a></li>
            <li id="2" class="TABES" style="text-align: center; width:<?php $medid = $_SESSION['MEDID'];
                $userid = $_GET['IdUsu'];
                if($medid != $userid && $multireferral != 1) {echo 108;} else if($multireferral>0) {echo 97;} else if($multireferral == 0){echo 120;}else {echo 108;}?>px; background-color:<?php echo $TipoColorGroup[2] ?>;"><a href="#ALL" lang="en" data-toggle="tab" style="height:40px; color:<?php echo $TipoColorGroup[2] ?>;"><i class="<?php echo $TipoIconGroup[2] ?> icon-large" style="color:RGB(111,111,111); font-size: 1.0em; width:100%;"></i><div style="width:100%"></div>Laboratory</a></li>
            <li id="3" class="TABES" style="text-align: center; width:<?php $medid = $_SESSION['MEDID'];
                $userid = $_GET['IdUsu'];
                if($medid != $userid && $multireferral != 1) {echo 108;} else if($multireferral>0) {echo 97;} else if($multireferral == 0){echo 120;}else {echo 108;}?>px; background-color:<?php echo $TipoColorGroup[3] ?>;"><a href="#ALL" lang="en" data-toggle="tab" style="height:40px; color:<?php echo $TipoColorGroup[3] ?>;"><i class="<?php echo $TipoIconGroup[3] ?> icon-large" style="color:RGB(111,111,111); font-size: 1.0em; width:100%;"></i><div style="width:100%"></div>Notes</a></li>
            <li id="4" class="TABES" style="text-align: center; width:<?php $medid = $_SESSION['MEDID'];
                $userid = $_GET['IdUsu'];
                if($medid != $userid && $multireferral != 1) {echo 108;} else if($multireferral>0) {echo 97;} else if($multireferral == 0){echo 120;}else {echo 108;}?>px; background-color:<?php echo $TipoColorGroup[4] ?>;"><a href="#ALL" lang="en" data-toggle="tab" style="height:40px; color:<?php echo $TipoColorGroup[4] ?>;"><i class="<?php echo $TipoIconGroup[4] ?> icon-large" style="color:RGB(111,111,111); font-size: 1.0em; width:100%;"></i><div style="width:100%"></div>Other </a></li>
            <!--<li id="5" class="TABES" style="width:10%; background-color:<?php echo $TipoColorGroup[5] ?>;"><a href="#ALL"   data-toggle="tab" style="height:40px; color:<?php echo $TipoColorGroup[5] ?>;"><i class="<?php echo $TipoIconGroup[5] ?> icon-large" style="color:RGB(111,111,111); font-size: 1.0em; width:100%;"></i><div style="width:100%"></div>-n/a-</a></li>-->
			
            <li id="6" class="TABES" style="text-align: center; width:<?php $medid = $_SESSION['MEDID'];
                $userid = $_GET['IdUsu'];
                if($medid != $userid && $multireferral != 1) {echo 108;} else if($multireferral>0) {echo 97;} else if($multireferral == 0){echo 120;}else {echo 108;}?>px; background-color:<?php echo $TipoColorGroup[6] ?>;"><a href="#ALL" lang="en" data-toggle="tab" style="height:40px; color:<?php echo $TipoColorGroup[6] ?>;"><i class="<?php echo $TipoIconGroup[6] ?> icon-large" style="color:RGB(111,111,111); font-size: 1.0em; width:100%;"></i><div style="width:100%"></div>SUMMARY</a></li>
            <li id="7" class="TABES" style="text-align: center; width:<?php $medid = $_SESSION['MEDID'];
                $userid = $_GET['IdUsu'];
                if($medid != $userid && $multireferral != 1) {echo 108;} else if($multireferral>0) {echo 97;} else if($multireferral == 0){echo 120;}else {echo 108;}?>px; background-color:<?php echo $TipoColorGroup[7] ?>;"><a href="#ALL" lang="en" data-toggle="tab" style="height:40px; color:<?php echo $TipoColorGroup[7] ?>;"><i class="<?php echo $TipoIconGroup[7] ?> icon-large" style="color:RGB(111,111,111); font-size: 1.0em; width:100%;"></i><div style="width:100%"></div>Pictures</a></li>
            <li id="8" class="TABES" style="text-align: center; width:<?php $medid = $_SESSION['MEDID'];
                $userid = $_GET['IdUsu'];
                if($medid != $userid && $multireferral != 1) {echo 108;} else if($multireferral>0) {echo 97;} else if($multireferral == 0){echo 120;}else {echo 108;}?>px; background-color:<?php echo $TipoColorGroup[8] ?>;"><a href="#ALL" lang="en" data-toggle="tab" style="height:40px; color:<?php echo $TipoColorGroup[8] ?>;"><i class="<?php echo $TipoIconGroup[8] ?> icon-large" style="color:RGB(111,111,111); font-size: 1.0em; width:100%;"></i><div style="width:100%"></div>Pat. Notes</a></li>
             <?php 
                
                $medid = $_SESSION['MEDID'];
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
           
      
      <li id="10" class="TABES" style="text-align: center; width:<?php $medid = $_SESSION['MEDID'];
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
                <span style="float: left; margin-top: 6px; margin-right: 72px;" lang="en">Practice Name :</span>
                <input type="text" id="pname" style="float: left; width: 250px;" >
				<span style="float: left; margin-top: 6px; margin-right: 101px;" lang="en">Address1 :</span>
                <input type="text" id="address1" style="float: left; width: 250px;" >
				<span style="float: left; margin-top: 6px; margin-right: 101px;" lang="en">Address2 :</span>
                <input type="text" id="address2" style="float: left; width: 250px;" >
				<span style="float: left; margin-top: 6px; margin-right: 141px;" lang="en">City :</span>
                <input type="text" id="city_holder" style="float: left; width: 250px;" >
				<span style="float: left; margin-top: 6px; margin-right: 130px;" lang="en">State :</span>
                <input type="text" id="state_holder" style="float: left; width: 250px;" >
				<span style="float: left; margin-top: 6px; margin-right: 146px;" lang="en">Zip :</span>
                <input type="text" id="zip_holder" style="float: left; width: 250px;" >
				<span style="float: left; margin-top: 6px; margin-right: 125px;" lang="en">Phone :</span>
                <input type="text" id="phone_holder" style="float: left; width: 250px;" >
				<span style="float: left; margin-top: 6px; margin-right: 143px;" lang="en">Fax :</span>
                <input type="text" id="fax_holder" style="float: left; width: 250px;" >
			</div>
			
			<div style="width: 500px; height: 25px; color: #777; margin-top: 25px;"> 
				
			
			<div style="width: 200px; height: 30px; margin: auto;">
                 <button id="create_location" style="width: 200px; height: 30px; background-color: #22AEFF; color: #FFF; outline: 0px; border: 0px solid #FFF; border-radius: 7px; margin-top: 15px;" lang="en">
                    Add Location
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
		   
		   <!--<div class="pull-right">
               <div class="grid-title-label" ><button id="PrintImage" class="btn" style="margin-left:10px;"><i class="icon-print"></i>Print</button></div>
           </div> -->
		   <div class="pull-right">
				<button id="BotonMod" data-target="#header-modal3" data-toggle="modal" class="btn" style="float:right; margin-right:10px;" lang="en"><i class="icon-indent-left"></i>Classi
