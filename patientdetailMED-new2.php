<?php
session_start();
set_time_limit(180);
require("environment_detail.php");
//require("push_server.php");
require('getFullUsersMEDCLASS.php');
require_once("displayExitClass.php");
//ini_set("display_errors", 0);

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
    //echo $telemed_on;


    if ($_GET['TELEMED']==1) {
        $telemed_value = 1;
    }    
    else if ($_GET['TELEMED']==2) {
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

//if($checker->status != 'true1' && $checker->status != 'true2' && $checker->status != 'true3' && $checker->status != 'true4'){
//$exit_display = new displayExitClass();
//$exit_display->displayFunction(5);
//die();
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

//$result = mysql_query("SELECT * FROM usuarios where IdUsFIXEDNAME='$NombreEnt' and IdUsRESERV='$PasswordEnt'");
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
    //BLOCKSLIFEPIN $result = mysql_query("SELECT * FROM blocks");
    //$result = mysql_query("SELECT * FROM lifepin");
    
    
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



?>
<!DOCTYPE html>
<html lang="en"><head>
    <meta charset="utf-8">
    <title>Health2me Patient Detail<?php echo $checker->status;?></title>
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
	
	<!-- Create language switcher instance and set default language to en-->
	<link rel="stylesheet" type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.11/themes/base/jquery-ui.css">


      <link rel="stylesheet" type="text/css" href="http://static.jquery.com/ui/css/demo-docs-theme/ui.theme.css">

      <script type='text/javascript' src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.11/jquery-ui.min.js"></script>
      <script type='text/javascript' src='https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js'></script>
  <script type="text/javascript" src="http://code.jquery.com/ui/1.8.18/jquery-ui.min.js"></script>
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
	    	<input type="hidden" id="IdMEDLogo" Value="<?php echo $MedLogo; ?>">	
	        <input type="hidden" id="USERID" Value="<?php echo $USERID; ?>">
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
		   <a href="#en" onclick="setCookie('lang', 'en', 30); pageRefresh(); return false;"><img src="images/icons/english.png"></a>
		   </br>
			<a href="#sp" onclick="setCookie('lang', 'th', 30); pageRefresh(); return false;"><img src="images/icons/spain.png"></a>
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
              <li><a href="signin.php" lang="en"><i class="icon-globe"></i> Home</a></li>
              
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
    <div id="summary_modal" title="Summary" style="display:none; text-align:center; width: 1000px; height: 660px; overflow: hidden;">
    </div>
    <!-- END MODAL VIEW FOR SUMMARY -->
        
    <!-- MODAL VIEW FOR PHONE TELEMEDICINE -->    
    <div id="phoneTelemed" title="Telemedicine" style="display:none; width: 480px; height: 30px; margin: auto; margin-top: 7px;">
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
					<tr>
						<td style="height:24px;" lang="en">Date</td>
                    </tr>
                    <tr>    
						<td style="height:24px;"><input id="note_date"  /></td>
					</tr>
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
                echo '<li><a href="dashboard.php"  lang="en">Dashboard</a></li>';
                echo '<li><a href="patients.php"  class="act_link" lang="en">Patients</a></li>';
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
		 
		 <div id="upload_reports_form" title="Upload New Report" style="display: none;">
		 
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
			<input type="hidden" id="report_type" value="0">

            
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
							$queQuery ='SELECT * FROM tipopin WHERE Agrup = '.$rg;
							$result2 = mysql_query($queQuery);
							$row2 = mysql_fetch_array($result2); 

							echo '                            <optgroup label="'.$row2['GroupName'].'">';

							$queQuery ='SELECT * FROM tipopin WHERE Agrup = '.$rg;
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
     
        <div class="grid" class="grid span4" style="width:1000px; margin: 0 auto; margin-top:30px; padding-top:30px; padding-bottom:30px; padding-left:20px; overflow:auto;">
	    
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
				shell_exec('Decrypt_Image.bat PatientImage '.$USERID.'.jpg '.$doctorId.' '.$pass.' 2>&1');
				//echo 'Decrypt_Image.bat PatientImage '.$USERID.'.jpg '.$_SESSION['MEDID'].' '.$pass.' 2>&1';
                
				$file = "temp/".$doctorId."/".$USERID.".jpg";
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
				<img src="<?php echo $file;?>" style="<?php echo $style;?>"> 
				</td>
				
				<td style="height:100px;">
				<span id="NombreComp" style="font: bold 24px Arial, Helvetica, sans-serif; color: #3D93E0; cursor: auto;  margin-left:10px;"><?php echo $MedUserName;?> <?php echo $MedUserSurname;?></span>
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
				echo '<input type="button" style="margin-left:10px" class="btn b2" value="Create Message" id="compose_message">';
              
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
					 	if($referral_type_array[$i]==1) echo '<div id="SecondSeal" style="float:left; position:absolute; left: 849px; top: -16px;height: 100px;width: 130px;"><img src="images/SecondOpinion.png"></div>';
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
											echo '<div style="width:90%; margin-top:12px; float:left;margin-left:20px"><span class="label label-success" style="font-size:14px;">Welcome Dr. '.$IdMEDName.' '.$IdMEDSurname.', this patient has been referred by <i>Dr. '.$otherdocnamearray[$i].' '.$otherdocSurnamearray[$i].'.</i></span><button id='.$referral_id_array[$i].' class="btn PrintHighlighted" style="float:right;margin-right:-60px"><i class="icon-print"></i>Print Highlighted Reports</button><span style="float:right;margin-right:10px;display:none;color:#22aeff;" id="H2M_Spin_Stream_Print"><i class="icon-spinner icon-2x icon-spin" ></i></span></div>';
										}
								}else{
									if($attachments_dld_array[$i]==0)
									{
									
										echo '<div style="width:90%; margin-top:12px; float:left;margin-left:20px"><span class="label label-success" style="font-size:14px;">Welcome Dr. '.$IdMEDName.' '.$IdMEDSurname.', this patient has been referred by <i>Dr. '.$otherdocnamearray[$i].' '.$otherdocSurnamearray[$i].'.</i></span></span></div>';
									}
									else
									{
										echo '<div style="width:90%; margin-top:12px; float:left;margin-left:20px"><span class="label label-success" style="font-size:14px;">Welcome Dr. '.$IdMEDName.' '.$IdMEDSurname.', this patient has been referred by <i>Dr. '.$otherdocnamearray[$i].' '.$otherdocSurnamearray[$i].'.</i></span></span><button id='.$referral_id_array[$i].' class="btn PrintHighlighted" style="float:right;margin-right:-60px"><i class="icon-print"></i>Print Highlighted Reports</button><span style="float:right;margin-right:10px;display:none;color:#22aeff" id="H2M_Spin_Stream_Print"><i class="icon-spinner icon-2x icon-spin" ></i></span></div>';	
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
										echo '<div style="width:90%; margin-top:12px; float:left;margin-left:20px"><span class="label label-success" style="font-size:14px;">Welcome Dr. '.$IdMEDName.' '.$IdMEDSurname.', this patient has been referred by <i>Dr. '.$otherdocnamearray[$i].' '.$otherdocSurnamearray[$i].'.</i></span></span><button id='.$referral_id_array[$i].' class="btn PrintHighlighted" style="float:right;margin-right:-60px"><i class="icon-print"></i>Print Highlighted Reports</button><span style="float:right;margin-right:10px;display:none;color:#22aeff" id="H2M_Spin_Stream_Print"><i class="icon-spinner icon-2x icon-spin" ></i></span></div>';	
									}
								
								}else{
									if($attachments_dld_array[$i]==0)
									{
										echo '<div style="width:90%; margin-top:12px; float:left;margin-left:20px"><span class="label label-success" style="font-size:14px;">Welcome Dr. '.$IdMEDName.' '.$IdMEDSurname.', you referred this patient to <i>Dr. '.$otherdocnamearray[$i].' '.$otherdocSurnamearray[$i].'.</i></span></div>';
									}
									else
									{
										echo '<div style="width:90%; margin-top:12px; float:left;margin-left:20px"><span class="label label-success" style="font-size:14px;">Welcome Dr. '.$IdMEDName.' '.$IdMEDSurname.', this patient has been referred by <i>Dr. '.$otherdocnamearray[$i].' '.$otherdocSurnamearray[$i].'.</i></span></span><button id='.$referral_id_array[$i].' class="btn PrintHighlighted" style="float:right;margin-right:-60px"><i class="icon-print"></i>Print Highlighted Reports</button><span style="float:right;margin-right:10px;display:none;color:#22aeff" id="H2M_Spin_Stream_Print"><i class="icon-spinner icon-2x icon-spin" ></i></span></div>';	
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
									echo '<input type="button" style="margin-left:10px" class="btn b2" value="Create Message" id="compose_message'.$i.'">';
								  
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
        <div class="label label-info" id="EtiTML" style=" margin-top: 0px; width: 96%; font-size:16px; text-shadow:none; text-decoration:none; padding: 7px; text-align: center;">Toolbox</div>
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
                if($MedID > 0)
                {
			         
                     //echo '<div class="pull-left" style="margin-left:20px;"><a href="emr_ret.php?idusu='.$IdUsu.'" class="btn"><i class="icon-folder-open"></i> Patient Details</a> </div>';
                    echo '<a id="Patient_Details_Button" class="btn ButtonDrAct" href="emr_ret.php?idusu='.$IdUsu.'" style="margin-top: -10px; margin-left: 15px; width: '.$width.'; height: '.$height.'">';
                    echo '    <div style=" margin-left: '.$margin_sides.'; margin-right: -'.$margin_sides.'; margin-top:'.$margin_top.'; width:'.$size.'; height:'.$size.'; font-size: '.$icon_size.'; color: #5EB529;"><i class="icon-folder-open"></i></div>';
                    echo '    <div style="line-height:25px; width: '.$label_width.'; height: 25px; font-size: '.$label_font_size.'; margin-left: '.$label_margin_left.';"><span lang="en">Patient Details</span></div>';
                    echo '</a>';
                }
			     //echo '<div class="pull-left" style="margin-left:20px;"><a href="medicalPassport.php?IdUsu='.$IdUsu.'" class="btn" target="_blank"><i class="icon-compass"></i> SUMMARY</a> </div>';
                echo '<a id="Summary_Button" value="BotonUpload_New" class="btn ButtonDrAct" href="medicalPassport.php?IdUsu='.$IdUsu.'" style="margin-top: -10px; margin-left: 15px; width: '.$width.'; height: '.$height.'">';
                echo '    <div style=" margin-left: '.$margin_sides.'; margin-right: -'.$margin_sides.'; margin-top:'.$margin_top.'; width:'.$size.'; height:'.$size.'; font-size: '.$icon_size.'; color: #5EB529;"><i class="icon-book"></i></div>';
                echo '    <div style="line-height:25px; width: '.$label_width.'; height: 25px; font-size: '.$label_font_size.'; margin-left: '.$label_margin_left.';"><span lang="en">Summary</span></div>';
                echo '</a>';
    
			     if($MedID > 0)
                 {
                    //echo '<div id="BotonRecords" style="margin-left:20px;" data-target="#header-modal4" data-toggle="modal" class="pull-left"><a href="#" class="btn" title="Records Board"><i class="icon-desktop"></i> Board</a> </div>';
                    echo '<a id="BotonRecords" class="btn ButtonDrAct" data-target="#header-modal4" data-toggle="modal" style="margin-top: -10px; margin-left: 15px; width: '.$width.'; height: '.$height.'">';
                    echo '    <div style=" margin-left: '.$margin_sides.'; margin-right: -'.$margin_sides.'; margin-top:'.$margin_top.'; width:'.$size.'; height:'.$size.'; font-size: '.$icon_size.'; color: #5EB529;"><i class="icon-table"></i></div>';
                    echo '    <div style="line-height:25px; width: '.$label_width.'; height: 25px; font-size: '.$label_font_size.'; margin-left: '.$label_margin_left.';"><span lang="en">Board</span></div>';
                    echo '</a>';
                 }
                 //echo '<div class="pull-left" style="margin-left:20px;"><a href="dropzone_short.php?IdUsu='.$IdUsu.'" class="btn"><i class="icon-th"></i> Drop Files</a> </div>';
                //echo '<a id="Drop_Button" class="btn ButtonDrAct" href="dropzone_short.php?IdUsu='.$IdUsu.'" style="margin-top: -10px; margin-left: 15px; width: '.$width.'; height: '.$height.'">';
                //echo '    <div style=" margin-left: '.$margin_sides.'; margin-right: -'.$margin_sides.'; margin-top:'.$margin_top.'; width:'.$size.'; height:'.$size.'; font-size: '.$icon_size.'; color: #5EB529;"><i class="icon-inbox"></i></div>';
                //echo '    <div style="line-height:25px; width: '.$label_width.'; height: 25px; font-size: '.$label_font_size.'; margin-left: '.$label_margin_left.';"><span>Drop Files</span></div>';
               // echo '</a>';
                if($MedID > 0)
                 {
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
            
        <div class="label label-success" style="left:0px; margin-left:0px; margin-top:20px; margin-bottom:5px; font-size:14px; padding: 7px; width: 96%; text-align: center; margin-bottom: -20px;" lang="en">Health Repository</div>
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
         
          <ul id="myTab" class="nav nav-tabs tabs-main" style="width: 972px;">
            <li id="0" class="TABES" style="width:<?php if($multireferral>0) {echo 97;} else {echo 108;}?>px; background: none repeat scroll 0% 0% rgb(204, 204, 204); text-align:center;"><a href="#ALL" data-toggle="tab" style="height:40px; font-size:16px;"><i class="icon-ok-sign icon-large" style="color:black; font-size: 1.0em; width:100%;" lang="en"></i><div style="width:100%"></div>ALL</a></li>
			<?php
				if($multireferral>0){
					$backgroundcolor=$TipoColorGroup[5];
					echo '<li id="5" class="TABES" style="text-align: center; width:10%; background-color:'.$backgroundcolor.'"><a href="#ALL"   data-toggle="tab" style="height:40px; color:'.$backgroundcolor.'"><i class="icon-star icon-large" style="color:RGB(111,111,111); font-size: 1.0em; width:100%;"></i><div style="width:100%"></div>Highlighted</a></li>';
				}
			?>
			<li id="1" class="TABES" style="text-align: center; width:<?php if($multireferral>0) {echo 97;} else {echo 108;}?>px; background-color:<?php echo $TipoColorGroup[1] ?>;"><a href="#ALL"  lang="en" data-toggle="tab" style=" color:<?php echo $TipoColorGroup[1] ?>;"><i class="<?php echo $TipoIconGroup[1] ?> icon-large" style="color:RGB(111,111,111); font-size: 1.0em; width:100%;"></i><div style="width:100%"></div>Imaging</a></li>
            <li id="2" class="TABES" style="text-align: center; width:<?php if($multireferral>0) {echo 97;} else {echo 108;}?>px; background-color:<?php echo $TipoColorGroup[2] ?>;"><a href="#ALL" lang="en" data-toggle="tab" style="height:40px; color:<?php echo $TipoColorGroup[2] ?>;"><i class="<?php echo $TipoIconGroup[2] ?> icon-large" style="color:RGB(111,111,111); font-size: 1.0em; width:100%;"></i><div style="width:100%"></div>Laboratory</a></li>
            <li id="3" class="TABES" style="text-align: center; width:<?php if($multireferral>0) {echo 97;} else {echo 108;}?>px; background-color:<?php echo $TipoColorGroup[3] ?>;"><a href="#ALL" lang="en" data-toggle="tab" style="height:40px; color:<?php echo $TipoColorGroup[3] ?>;"><i class="<?php echo $TipoIconGroup[3] ?> icon-large" style="color:RGB(111,111,111); font-size: 1.0em; width:100%;"></i><div style="width:100%"></div>Notes</a></li>
            <li id="4" class="TABES" style="text-align: center; width:<?php if($multireferral>0) {echo 97;} else {echo 108;}?>px; background-color:<?php echo $TipoColorGroup[4] ?>;"><a href="#ALL" lang="en" data-toggle="tab" style="height:40px; color:<?php echo $TipoColorGroup[4] ?>;"><i class="<?php echo $TipoIconGroup[4] ?> icon-large" style="color:RGB(111,111,111); font-size: 1.0em; width:100%;"></i><div style="width:100%"></div>Other </a></li>
            <!--<li id="5" class="TABES" style="width:10%; background-color:<?php echo $TipoColorGroup[5] ?>;"><a href="#ALL"   data-toggle="tab" style="height:40px; color:<?php echo $TipoColorGroup[5] ?>;"><i class="<?php echo $TipoIconGroup[5] ?> icon-large" style="color:RGB(111,111,111); font-size: 1.0em; width:100%;"></i><div style="width:100%"></div>-n/a-</a></li>-->
			
            <li id="6" class="TABES" style="text-align: center; width:<?php if($multireferral>0) {echo 97;} else {echo 108;}?>px; background-color:<?php echo $TipoColorGroup[6] ?>;"><a href="#ALL" lang="en" data-toggle="tab" style="height:40px; color:<?php echo $TipoColorGroup[6] ?>;"><i class="<?php echo $TipoIconGroup[6] ?> icon-large" style="color:RGB(111,111,111); font-size: 1.0em; width:100%;"></i><div style="width:100%"></div>SUMMARY</a></li>
            <li id="7" class="TABES" style="text-align: center; width:<?php if($multireferral>0) {echo 97;} else {echo 108;}?>px; background-color:<?php echo $TipoColorGroup[7] ?>;"><a href="#ALL" lang="en" data-toggle="tab" style="height:40px; color:<?php echo $TipoColorGroup[7] ?>;"><i class="<?php echo $TipoIconGroup[7] ?> icon-large" style="color:RGB(111,111,111); font-size: 1.0em; width:100%;"></i><div style="width:100%"></div>Pictures</a></li>
            <li id="8" class="TABES" style="text-align: center; width:<?php if($multireferral>0) {echo 97;} else {echo 108;}?>px; background-color:<?php echo $TipoColorGroup[8] ?>;"><a href="#ALL" lang="en" data-toggle="tab" style="height:40px; color:<?php echo $TipoColorGroup[8] ?>;"><i class="<?php echo $TipoIconGroup[8] ?> icon-large" style="color:RGB(111,111,111); font-size: 1.0em; width:100%;"></i><div style="width:100%"></div>Mem. Notes</a></li>
            <li id="9" class="TABES" style="text-align: center; width:<?php if($multireferral>0) {echo 97;} else {echo 108;}?>px; background-color:<?php echo $TipoColorGroup[9] ?>;"><a href="#ALL" lang="en" data-toggle="tab" style="height:40px; color:<?php echo $TipoColorGroup[9] ?>;"><i class="<?php echo $TipoIconGroup[9] ?> icon-large" style="color:RGB(111,111,111); font-size: 1.0em; width:100%;"></i><div style="width:100%"></div>Superbill</a></li>
            
          </ul> 
          
         <div id="myTabContent" class="tab-content tabs-main-content padding-null" style="width: 970px;" >
                <div  class="tab-pane tab-overflow-main fade in active" id="ALL" style="overflow: auto;overflow-y: hidden;">
					<div class="horizontal-only notes" id="hscroll" style="height: 290px; width:100%; margin-top:10px; background-color:white;" >
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
		
		
	 <div class="clear"></div><br/><br/>
       
        <div class="label label-success " id="EtiTML" style="left:0px; margin-left:0px; margin-bottom:-10px; font-size:14px; padding: 7px; width: 96%; text-align: center; color: #FFF;"  lang="en">
            <i id="TLineScrollDown" class="icon-caret-down icon-2x navArrows" style="float:right;margin-left:-15px;margin-top:-7px;color:#FFF"></i>                     
            <i id="TLineScrollUp" class="icon-caret-up icon-2x navArrows" style="float:right;margin-left:-15px;margin-top:-7px;color:#FFF;display:none"></i>
            Health History Timeline
            
        </div>
		<span style="display:none;color:#22aeff;margin-left:100px" id="H2M_Timeline_Spin_Stream" lang="en"><i class="icon-spinner icon-2x icon-spin" ></i> <b> Loading... </b> </span>
		 
		<div class="grid span3 " id="timeline-box" style="margin-left:0px; width:98%; height:400px; margin-bottom:20px;display:none;text-align:center;float:inherit" >
			
         	<div id="timeline-embed" ></div>
         	
				
        </div>
                       

  
     <div class="grid span3" style="width:97%;margin-left:0px;">
          <div class="grid-title a" style="height:60px; margin-bottom:30px;">
           <div class="pull-left a" id="AreaTipo" style="font-size:24px;"></div>
		   
		   <!--<div class="pull-right">
               <div class="grid-title-label" ><button id="PrintImage" class="btn" style="margin-left:10px;"><i class="icon-print"></i>Print</button></div>
           </div> -->
		   <div class="pull-right">
				<button id="BotonMod" data-target="#header-modal3" data-toggle="modal" class="btn" style="float:right; margin-right:10px;" lang="en"><i class="icon-indent-left"></i>Classification</button>
		   </div>
		   <div class="pull-right">
               <div class="grid-title-label" id="History" ><span class="label label-info" style="left:0px; margin-left:20px; margin-top:20px; margin-bottom:5px; font-size:16px;" lang="en">Detailed Report Tracking History</span></div>
           </div>
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
		  
		 
		  
		  
			<?php
			//BLOCKSLIFEPIN $sql="SELECT * FROM blocks where IdUsu ='$IdUsu'";
			$sql=$con->prepare("SELECT * FROM lifepin where IdUsu =?");
			$sql->bindValue(1, $IdUsu, PDO::PARAM_INT);
			$q = $sql->execute();
			
			$row = $sql->fetch(PDO::FETCH_ASSOC);
			?>
 
             <img id="ImagenAmp" style="margin:0 auto;" src="">
          </div>
		  <div id="media-active"></div>
        </div>
        
                      
     </div>
     <!--CONTENT MAIN END-->

    </div>
	</div>
    <!--Content END-->

    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->

	<!--<script type="text/javascript" src="js/storyjs-embed.js"></script>

    <!--<script src="js/jquery.min.js"></script>
    <script src="js/jquery-ui.min.js"></script> -->
   
    <!--<script src="js/bootstrap.min.js"></script>
    <!--<script src="js/bootstrap-datepicker.js"></script>-->
    <!--<script src="js/jquery.timepicker.js"></script>
    <script src="js/bootstrap-colorpicker.js"></script>
	<!--<script src="js/bootstrap-modal.js"></script>
	<script src="js/bootstrap-dropdown.js"></script>-->
    <!--<script src="js/google-code-prettify/prettify.js"></script>
   
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
    
	<!--<script src="js/application.js"></script>-->
    
     
  <!--  <script type="text/javascript" src="http://code.jquery.com/jquery-1.8.0.min.js"></script>--->
    <!--<script type="text/javascript" src="js/jquery.tooltipster.min.js"></script>

	<!--<script src="imageLens/jquery.js" type="text/javascript"></script>-->
	<!--<script src="imageLens/jquery.imageLens.js" type="text/javascript"></script>
	<script src="audio/AudioContextMonkeyPatch.js"></script>
	<script src="audio/recorder.js"></script>
	<!-- <script src="audio/main.js"></script>  -->
	<!--<script src="audio/audiodisplay.js"></script> 
	<!--<script src="fileupload/filedrag.js"></script> -->
	
	<!--<script src="realtime-notifications/lib/gritter/js/jquery.gritter.min.js"></script>
	<script src="realtime-notifications/pusher.min.js"></script>
	<script src="realtime-notifications/PusherNotifier.js"></script>
    <script type="text/javascript" src="js/timer.js"></script>-->
	<link href="realtime-notifications/lib/gritter/css/jquery.gritter.css"rel="stylesheet" type="text/css" />
	
	 <script>
		$(function() {
	    var pusher = new Pusher('d869a07d8f17a76448ed');
	    var channel_name=$('#MEDID').val();
		var channel = pusher.subscribe(channel_name);
		var notifier=new PusherNotifier(channel);
		
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
    <script type="text/javascript" >

	var list = new Array();
	var curr_file=-1;
	var timeoutTime = 18000000;
	//var timeoutTime = 300000;  //5minutes
	var timeoutTimer = setTimeout(ShowTimeOutWarning, timeoutTime);


	var active_session_timer = 60000; //1minute
	var sessionTimer = setTimeout(inform_about_session, active_session_timer);
	
	var offset1=0;    //used for tab scrolling
	var last_pos=0;	 //used for tab scrolling
	var num_reports=0; //used for tab scrolling
	var jump=8;//used for tab scrolling
    var timeline_loaded = false;
	var oWindow=new Array();
	var pages;
	
	    $('#evolution_date').datepicker({
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
        $('#note_date').datepicker({
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
		
		
				
		$('#classification_datepicker').datepicker({
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
		
		
	

	//This function is called at regular intervals and it updates ongoing_sessions lastseen time
	function inform_about_session()
	{
        <?php if(isset($_SESSION['MEDID'])){ ?>
		$.ajax({
			url: '<?php echo $domain?>/ongoing_sessions.php?userid='+<?php echo $_SESSION['MEDID'] ?>,
			success: function(data){
			//alert('done');
			}
		});
        <?php } ?>
		clearTimeout(sessionTimer);
		sessionTimer = setTimeout(inform_about_session, active_session_timer);
	}

	function ShowTimeOutWarning()
	{
		alert ('Session expired');
		var a=0;
		window.location = 'timeout.php';
	}
	
	
	function PrintAll(){
		for (var i = 0; i < pages.length; i++) {
			oWindow[i].print();
			oWindow[i].close();

		}
	}
	
    $(document).ready(function() {
	
		$('body').bind('mousedown keydown', function(event) {
			clearTimeout(timeoutTimer);
			timeoutTimer = setTimeout(ShowTimeOutWarning, timeoutTime);
		});
        var telemedOn = <?php if($telemed_on){echo 'true';}else{echo 'false';} ?>;

        //var telemedValue = <?php echo $_GET['TELEMED']; ?>;
        var telemedValue = <?php echo $telemed_value; ?>;
        
        //see if it is a phone consultation
        if (telemedValue == 2) {
            //open the phone dialog
            $("#phoneTelemed").show();
            $("#phoneTelemed").dialog({bigframe: true, width: 550, height: 200});
            
                setTimeout(function() {
                // turns red after 10 minutes
                    $( ".timer" ).css( "border", "3px solid red" );
                    $( ".timer" ).css( "color", "red" );
                }, 600000);
            
                $('#startButton').trigger('click');
        }    
        // or a video consultation
        if(telemedOn&&telemedValue==1)

        {
 
            var webrtc = new SimpleWebRTC({localVideoEl: 'localVideo',remoteVideosEl: 'remoteVideo',autoRequestMedia: true, url: '8888'});
            webrtc.on('readyToCall', function () 
            {
                setTimeout(function() {
                // turns red after 10 minutes
                    $( ".timer" ).css( "border", "3px solid red" );
                    $( ".timer" ).css( "color", "red" );
                }, 600000);
            
                $('#startButton').trigger('click');
                
                //video_recorder = RecordRTC(webrtc.webrtc.localStream, video_record_options);
                //audio_recorder = RecordRTC(webrtc.webrtc.localStream, audio_record_options);
                webrtc.joinRoom('health2me_room_' + $("#MEDID").val() + '_' + $("#USERID").val());
                $("#remoteVideo").css("height", ($("#videoTelemed").css("width") * 0.6538461538 * 0.68).toString() + "px");
                $("#remoteVideo").css("width", ($("#videoTelemed").css("width") * 0.68).toString() + "px");
                $("#telemed_notes").attr("rows", (Math.floor($("#videoTelemed").css("width") * 0.6538461538 * 0.68) / 22));
                var interval = setInterval(function()
                {
                    $("#remoteVideo").children("video").each(function()
                    {
                        $(this).css("height", "100%");
                        $(this).css("width", "100%");
                        clearInterval(interval);
                    });
                }, 200);
                $.post("update_webrtc.php", {doc_id: $("#MEDID").val(), pat_id: $("#USERID").val(), status: 1, add_to_recent_doctors: 1}, function(){});
                $("#remoteVideo").children("video").each(function()
                 {
                    $(this).css("height", "100%");
                    $(this).css("width", "100%");
                 });
                $(window).on("unload", function () 
                {
                    $.post("update_webrtc.php", {doc_id: $("#MEDID").val(), status: 0}, function(){});
                    
                });
                $('a').click(function()
                {
                    $.post("update_webrtc.php", {doc_id: $("#MEDID").val(), status: 0}, function(){});
                });
            });
            

            
            $("#videoTelemed").dialog({bigframe: true, width: 550, height: 440, minWidth: 550, minHeight: 440, sticky: false, modal: false, dialogClass: 'telemedModalClass', position: {my: "left+70",
    of: "#NombreComp"}, resize: function( event, ui ) 
            {
                $("#remoteVideo").children("video").each(function()
                 {
                    $(this).css("height", "100%");
                    $(this).css("width", "100%");
                 });
                
            }, 
            beforeClose: function( event, ui ) 
            {
                $.post("update_webrtc.php", {doc_id: $("#MEDID").val(), status: 0}, function(){});
            }});
            $(".telemedModalClass").children(".ui-dialog-titlebar").append('<span id="icon_float" class="icon-unlock" style="float: right; margin-right: 15px; margin-top: 3px; cursor: pointer;"></span>');
            $("#icon_float").on('click', function()
            {
                if($(this).hasClass("icon-unlock"))
                {
                    $("#videoTelemed").dialog({sticky: true});
                    $(this).removeClass("icon-unlock").addClass("icon-lock");
                }
                else
                {
                    $("#videoTelemed").dialog({sticky: false});
                    $(this).removeClass("icon-lock").addClass("icon-unlock");
                }
                
            });
            
        }
	
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
		
		//Changes for the multi-referral doctors screen
		var ismultireferral=0;
		var multireferral_num=parseInt(<?php echo $num_multireferral ?>);
		//alert(multireferral_num);
		var referral_state_array=new Array();
		for (var i=0;i<multireferral_num;i++)
		{ 
			//alert(i);
			ismultireferral=1;
			referral_state_array[i]=parseInt($('#referral_state'+i).val());
			//alert(referral_state_array[i]);
		}
		
		var referral_state;
		if(ismultireferral==0){
			referral_state = parseInt($('#referral_state').val());
			//alert(referral_state);
			if (isNaN(referral_state)){
			referral_state=0;
			//alert('The referral stages functionality is not working. Please contact Health2me!');
			}
		}
		
	
	
		
	$('.PrintHighlighted').live('click',function()	
	{
		var myClass = $(this).attr("id");
		//alert('clicked' + myClass);
		//$("#H2M_Spin_Stream_Print").show();
		//setTimeout($("#H2M_Spin_Stream_Print").show(), 3000);
		var link = 'mergePDF.php?referralID='+myClass;
		$.ajax(
		{
			url: link,
			dataType: "html",
			async: true,
			success: function(data)
			{
				//alert('Data Fetched');
				RecTipo = data;
				var fileURL = 'temp/' + $('#MEDID').val() + '/'+myClass+'.pdf';
				//alert(fileURL);
				if(checkFileExists(fileURL)==true)
				{
					//alert('done');

					pages = [fileURL];
					
					for (var i = 0; i < pages.length; i++) 
					{
						oWindow[i] = window.open(pages[i]);
						/*var myWindow = window.open(pages[i]);;
						oWindow[i] = myWindow;
						$(myWindow.document).ready(function() {
							//alert('Window is open');
							//PrintAll();
						});*/
					}
					setTimeout("PrintAll()", 5000);
					$("#H2M_Spin_Stream_Print").hide();	
				}
				else
				{
					displaynotification('Error','No highlighted reports to print');
					$("#H2M_Spin_Stream_Print").hide();	
				}
			},
			error:function(data){
				displaynotification('Error','Error Printing Reports');
				$("#H2M_Spin_Stream_Print").hide();	
			}
		});
		//$("#H2M_Spin_Stream_Print").hide();	
	
	});
	
	function checkFileExists(FileUrl)
	{
		var exists=false;
		$.ajax({
		  url: FileUrl, 
		  async: false,
		  success: function(data){
			//alert('found');
			exists=true;
			//return true;
		  },
		  error: function(data){
			//alert('notfound');
			//return false;
		  },
		})
		return exists;
	}
	
	$('#AddEvolution').live('click',function()	
	{
		var userid = <?php echo $IdUsu ?>;
		var date = $('#evolution_date').val();
		var text = $('#evolution_text').val();
		
		if(date == '')
		{
			alert('Enter Valid Date');
			return;
		}
		
		if(text == '')
		{
			alert('Enter some text');
			return;
		}
		
		var cadena = 'add_evolutions.php?userid='+userid+'&date='+date+'&text='+text;
		var RecTipo = LanzaAjax (cadena);
		//alert(RecTipo);
		if(RecTipo=='success')
		{
			var cadena = 'EvolutionPDF.php?idusu='+userid;
			//alert(cadena);
			var RecTipo = LanzaAjax (cadena);
			window.location.reload();
			
			
		}
		else
		{
			alert("Error Adding Data");
		}
	});
        
    //Start of ajax call for adding notes temporarily in temp directory using AddNote button    
        $('#AddNote').live('click',function()	
            {
                var userId = <?php echo $IdUsu ?>;
                var doctorId = <?php echo $IdMed ?>;
                var date = $('#note_date').val();
                var findings = '"'+$('#note_findings').val()+'"'; //changed from note_text to note_findings
                var recommendations = '"'+$('#note_recommendations').val()+'"';
                
                $.post("storePatientNotesTemp.php",{doctorId: doctorId,findings: findings,recommendations: recommendations},function(data,success)
                       {
                        console.log(data);
                       });


            });	
	//End of ajax call for adding notes using AddNote button	
	
    $(".nav-container").click(function()
	{
		//alert("here");
		/*var left = 0;
		setTimeout(function ()
			{
				left = parseInt( $('.slider-container').css("left").replace(/px/,""));
				//alert(left);
				var sliderIndex;
				if(left>100)
				sliderIndex=-1;
				else
				sliderIndex=0;
				while(left<100)
				{
					if(left>100)
					 break;
					else 
					 {
						left+=1000;
						sliderIndex+=1;
					 }
				}
				if(sliderIndex>0)
				{
					var count =1;
					$( ".note2" ).each(function() 
					{
						// alert("count: "+count);
						if(count==sliderIndex)
						{
						  id = $( this ).attr('id');
						  //alert("note id: "+id);
						}  
						count++; 
					});
					//$('.note2').trigger('click',[id,false]);
				}
			},2000);
		//$('#'+id).scrollIntoView();
		//alert(left);*/
		$('#AreaConten').hide();
		$('#AreaTipo').innerHtml="";
		$('#AreaClas').innerHTML="";
	});
	
	
	
	$(".marker").live('click',function(event)
	{
		//console.log("event.originalEvent:" +event.originalEvent);
		if( event.originalEvent !== undefined)
		{
			var divIndex = $("#"+this.id).index();
			// alert("divIndex: "+divIndex);
			var id=0;
			var count =1;
			$( ".note2" ).each(function() 
			{
				// alert("count: "+count);
				if(count==divIndex)
				{
				  id = $( this ).attr('id');
				  //alert("note id: "+id);
				}  
				count++; 
			});
			//$('.note2').trigger('click',[id,false]);
			//alert("scroll id:"+id);
			
			//document.getElementById(id).focus();
			  // $('html, body').animate({
				// scrollTop: $('#'+id).offset().top
			// }, 1000);
			 // $('#tabsWithStyle').animate({
				// scrollTop: $('#'+id).offset().top
			// },{ duration:800,complete:function(){window.location.hash = '#'+id;}});
			 location.href="#"+id;
			  // $('#tabsWithStyle').animate({
				// scrollTop: $('#'+id).offset().top
				// },800);
			  var windowHeight = $(window).height();
			  var elementHeight = $("#"+id).height();

			  var elementPosition = $("#"+id).position();
			  var elementTop = elementPosition.top;
			  var toScroll = (windowHeight / 2)-(elementHeight/30);
			  window.scroll(0,elementTop-toScroll);
			//$("#"+id).css('border','solid 1px red');
			$("#"+id).effect('pulsate');
			$('#AreaConten').hide();
			$('#AreaTipo').html("");
			$('#AreaClas').html("");
		}			
	});
	
	// $('.timenav').children('.content').on('click',function(event,cascade,id)
	// {
		// if(cascade==false)
		// {
			// $('.timenav').children('.content:nth-child('+(parseInt(id)+1)+')').click();
		// }
		// else
		// {
			// var divIndex = $("#"+this.id).index();
				// alert("divIndex: "+divIndex);
			// var id=0;
			// var count =1;
			// $( ".note2" ).each(function() 
			// {
				// alert("count: "+count);
				// if(count==divIndex)
				// {
				  // id = $( this ).attr('id');
				 // alert("note id: "+id);
				// }  
				// count++; 
			// });
			// $('.note2').trigger('click',[id,true]);
		// }
	// });
	
	$('#NewMES').live('click',function(){
		var queUrl ='<?php echo $domain;?>/getInboxMessageUNREAD.php?IdMED=<?php echo $MedID;?>&patient=<?php echo $USERID;?>&sendingdoc='+otherdocid[i];		

		$.ajax(
		{
			url: queUrl,
			dataType: "json",
			async: false,
			success: function(data)
			{
				//alert('Data Fetched');
				NewMES = data.items;
			}
		});

		Pa = NewMES.length;
		
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
			var formatDate = formatDateP.format('dddd mmmm dd, yyyy');
			MesTimeline = MesTimeline + '<div style="float:left; width:100%; margin-top:15px; ">'; 
				MesTimeline = MesTimeline + '<div style="float:left; width:10%; ">'; 
					//MesTimeline = MesTimeline + NewMES[n].MessageID;			
					if (n==2) 
						{
						MesTimeline = MesTimeline + '<img src="images/PersonalPicSample.jpeg" style="margin-left:0px; width: 40px; height: 40px; border:#cacaca;" class="img-circle">';
						}
					else
					{
						MesTimeline = MesTimeline + '<div class="LetterCircleON" style="width:40px; height:40px; font-size:12px; margin-left:0px; background-color:' + whatcolor + ';"><p style="margin:0px; padding:0px; margin-top:13px;">'+ NewMES[n].MessageINIT +'</p></div>	';
					}	
				MesTimeline = MesTimeline + '</div>'; 
	
				MesTimeline = MesTimeline + '<div style="float:left; width:87%; margin-left:3%; border-bottom:thin dotted #cacaca;">'; 
					MesTimeline = MesTimeline + '<p style="font-weight:bold; font-size:14px; ">' +  NewMES[n].MessageSEND + '<span style="color:#cacaca; float:right; font-size:12px; font-weight:normal;">    ' + formatDate + '</span> ' + '</p>' ;
					MesTimeline = MesTimeline +  '<p style="color:grey; font-weight:bold; font-size:12px; margin-bottom:-5px; margin-top:-10px;">' + NewMES[n].MessageSUBJ + '</p>';
					MesTimeline = MesTimeline +  '<p style="color:grey; margin-bottom:0px; ">' + NewMES[n].MessageCONT.replace(/sp0e/gi," ") + '</p>';
					
					var splitted = NewMES[n].MessageRIDS.split(" ");
					NumReports = splitted.length - 1;
					if (NumReports>0)
					{
						MesTimeline = MesTimeline + '<div style="margin:0 auto; width:95%; border:solid #cacaca; border-radius:5px; height:80px;">'; 
							//MesTimeline = MesTimeline + NumReports + ' Reports.  ';
							var rn = 0;
							while (rn < NumReports)
							{
								var cadena = '<?php echo $domain;?>/DecryptFileId.php?reportid='+splitted[rn]+'&queMed='+<?php echo $MedID;?>;
								var RecTipo = LanzaAjax (cadena);
								var thumbnail = RecTipo.substr(0,RecTipo.indexOf("."))+'.png';
								MesTimeline = MesTimeline + '<img class="ThumbTwitt" id="'+splitted[rn]+'" style="height:70px; margin:5px; border:solid 1px #cacaca;" src="temp/'+<?php echo $MedID;?>+'/PackagesTH_Encrypted/'+thumbnail+'">';
								rn++;
							}
						MesTimeline = MesTimeline + '</div>'; 
					}	
					
					MesTimeline = MesTimeline +  '<p style="color:#cacaca; font-size:10px; margin-left:50px;"> <span style="margin-right:15px;"><i style="font-size:10px; margin-right:5px;" class="icon-mail-reply"></i>Reply</span><span style="margin-right:15px;"><i style="font-size:10px; margin-right:5px;" class="icon-arrow-right"></i>Forward</span><span style="margin-right:15px;"><i style="font-size:10px; margin-right:5px;" class="icon-bookmark"></i>Mark</span></p>';
				MesTimeline = MesTimeline + '</div>'; 
				
				
			MesTimeline = MesTimeline + '</div>'; 
			
			n++;
		}
        $('#NewMES').html(MesTimeline);
        //$('#TextMES').html(MesTimeline);
		
	});

	$(".ThumbTwitt").live('click',function() {
		 var myClass = $(this).attr("id");
		 var cadena = '<?php echo $domain;?>/DecryptFileId.php?reportid='+myClass	+'&queMed='+<?php echo $MedID;?>;
		 var RecTipo = LanzaAjax (cadena);
		 //var thumbnail = RecTipo.substr(0,RecTipo.indexOf("."))+'.png';
		 var thumbnail = RecTipo;
		 var src='temp/'+<?php echo $MedID;?>+'/Packages_Encrypted/'+thumbnail;		
		 $('#ZoomedIframe').attr('src',src);				
		 $('#ZoomedIframe').css('display','inline');				
		 //alert (src);					
	});

	$('body', $('#ZoomedIframe').contents()).click(function(event) {
			 $('#ZoomedIframe').css('display','none');
	});
	

	$("#TLineScrollDown").live('click',function() {
				
		
		
		var elem = document.getElementById("TLineScrollDown");
		elem.style.display = 'none';
		
		var elem = document.getElementById("TLineScrollUp");
		elem.style.display = 'block';
		
		
		
		if(timeline_loaded==true)
		{
			$("#timeline-box").slideDown("slow");
			return;
		}
		loadTimeline();
		$("#timeline-box").slideDown("slow");
	});

	$('#TLineScrollUp').live('click',function(){
		var elem = document.getElementById("TLineScrollDown");
		elem.style.display = 'block';
		
		var elem = document.getElementById("TLineScrollUp");
		elem.style.display = 'none';
		
		$("#timeline-box").slideUp("slow");
		
	});
	
	
	function loadTimeline(){
	
		//$('#H2M_Timeline_Spin_Stream').show();
		//alert('displayed');
		/*var t = setTimeout(function(){
					$("#H2M_Timeline_Spin_Stream").hide();
					 },1000);
		*/
		
		
		var usuario = <?php echo $IdUsu;?>;
		var IdMed = <?php if($IdMed > 0){echo $IdMed;}else{echo $IdUsu;}?>;
		//$('#H2M_Timeline_Spin_Stream').show();		
		var DirURL = 'Timeline.php?usuario='+usuario+'&medid='+IdMed;
								
				$.ajax(
			    {
				   url: DirURL,
				   dataType: "html",
				   complete: function(){ 
							},
					beforeSend: function(msg){
						
					},		
				   success: function(data) 
				            {
							
							  //alert('produced');
							  
							  createTimeline('jsondata2.txt');
							  timeline_loaded = true;
							  //$('#H2M_Timeline_Spin_Stream').hide();
							  	
							}
				});
			
				
		}
	
		
		function createTimeline(data){
					createStoryJS({

						width: "100%",
						debug : false,
						height: "100%",
						source: data,
						type: 'timeline',
						embed_id: 'timeline-embed'
					});
				}

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
	 
	 
 $('#PrintImage').live('click',function(){
		//var uniqueID="";
		var path = $('#ImagenN').attr("src");
		var rawimage = "";
		var idpin;
		if(path==null){
			alert('Select a Report');
			return;
		}
		else{
			rawimage=rawimage+ path.substr(path.lastIndexOf("/")+1,path.length);
		}

	   	window.open('printimage.php?path='+path+'&IdUs=<?php echo $IdUsu ?>&MedID=<?php echo $MedID;?>;','','left=200,width=900,height=700,resizable=0,scrollbars=1');
		myWindow.focus();
		
		window.print();
		//document.getElementById(#DropBoxID).style.display="block";
	}); 
	
	
	
	//Function to delete report
	$(".icon-trash").live('click',function() {
		//alert('clicked' + this.parentNode.parentNode.parentNode.getAttribute('id'));
		var idpin = this.parentNode.parentNode.parentNode.getAttribute('id');
		//alert("ID is " + idpin);
		
		var cadena = '<?php echo $domain;?>/getReportStatus.php?IdPin='+idpin;
		var packetstatus = LanzaAjax (cadena);
		//alert(packetstatus);
		if(packetstatus==2)
		{
			alert('This report contains Patients Basic EMR Data. It cannot be deleted !');
			return;
		}
		else if(packetstatus==1)
		{
			alert('This report has already been deleted !');
			return;
		}
		else if(packetstatus==3)
		{
				var del=confirm("Are You sure you want to delete this report.");
				if(!del)
					//alert("permanently deleted");
					return;
				
				var cadena = '<?php echo $domain;?>/deleteReports.php?IdPin='+idpin+'&state='+packetstatus;
				//alert(cadena);
				var RecTipo = LanzaAjax (cadena);
				//alert(RecTipo);
				var Content='report marked deleted';
				var VIEWIdUser = 0;
				var VIEWIdMed = $("#MEDID").val();
				var MEDIO = 0;
				var cadena = '<?php echo $domain ;?>/LogEvent.php?IDPIN='+idpin+'&Content='+Content+'&VIEWIdUser='+VIEWIdUser+'&VIEWIdMed='+VIEWIdMed+'&MEDIO='+MEDIO;
				var RecTipo = LanzaAjax (cadena);
				displaynotification('status','Report Deleted');
				window.location.reload();
		}
		
				  
		
	});
	
	
	
	function fileExists(url) {
		if(url){
			var req = new XMLHttpRequest();
			req.open('GET', url, false);
			req.send();
			return req.status==200;
		} else {
			return false;
		}
	}
	
	
	$("#EvolutionToggle").live('click',function() {
		
		//var contenURL='Evolution/'+<?php echo $IdUsu ?>+'.pdf';
		var contenURL='Packages_Encrypted/'+<?php echo $IdUsu ?>+'.pdf';
		var fileFound = fileExists(contenURL);
		
		if(fileFound)
		{
			var rawimage=<?php echo $IdUsu ?>+'.pdf';
			var cadena = '<?php echo $domain;?>/DecryptFile.php?rawimage='+rawimage+'&queMed='+<?php echo $MedID;?>;
			var RecTipo = LanzaAjax (cadena);
			
			var filepath='<?php echo $domain; ?>/temp/<?php echo $MedID ;?>/Packages_Encrypted/'+rawimage;
			var conten =  '<iframe id="ImagenN1" style="border:1px solid #666CCC" title="PDF" src="'+filepath+'" alt="File Not Found" frameborder="1" scrolling="auto" height="850" width="600" ></iframe>';
			$('#AreaConten2').html(conten);			
			$("#modal_evolution_display").trigger('click');
		}
		else
		{
			var url='Evolution/DataNotFound.jpg';
			var conten =  '<iframe id="ImagenN1" style="border:1px solid #666CCC" title="PDF" src="'+url+'" alt="File Not Found" frameborder="1" scrolling="auto" height="850" width="600" ></iframe>';
			$('#AreaConten2').html(conten);			
			$("#modal_evolution_display").trigger('click');
			//alert("No Evolution Data Found");
		}
		
	});
        
        
		
	$("#EvolutionButton").on('click',function(){
		$("#modal_evolution").trigger('click');
	});
    
    //Adding code for popping up modal window for PhoneNotes button
        
       $("#PhoneNotes").on('click',function(){
		$("#modal_phoneNotes").trigger('click');
	});
	
        
    $("#Summary_Button").on('click',function(e){
        e.preventDefault();
        var myClass = $('#USERID').val();
		alert('working');
        //$("#summary_modal").html('<iframe src="medicalPassport.php?modal=1&IdUsu='+myClass+'" scrolling="no" style="width:1000px;height:660px; margin: 0px; border: 0px solid #FFF; outline: 0px; padding: 0px; overflow: hidden;"></iframe>');
        $("#summary_modal").dialog({bigframe: true, width: 1050, height: 690, resize: false, modal: true}).on("dialogclose",function(data,success)
        {
		alert('working2');
             /*$.post("SavePatientSummaryAsPDF.php",{IdUsu: <?php echo $UserID; ?>},function(data,success)
               {
              });*/
            
             /* $(selector").html2canvas({
        onrendered: function (canvas) {
            console.log('Canvas is'+canvas.getContext('2d'));
            //Set hidden field's value to image data (base-64 string)
            $('#img_val').val(canvas.toDataURL("image/png"));
            //Submit the form manually
            console.log('File is'+$('#img_val').val());
            document.getElementById("myForm").submit();
        } */
            
           
           
        });
	});
    $(".Telemed_Summary_Button").on('click',function(e)
    {
        var myClass = $('#USERID').val();
        $("#summary_modal").html('<iframe src="medicalPassport.php?modal=1&IdUsu='+myClass+'" width="1000" height="660" scrolling="no" style="width:1000px;height:660px; margin: 0px; border: 0px solid #FFF; outline: 0px; padding: 0px; overflow: hidden;"></iframe>');
        $("#summary_modal").dialog({bigframe: true, width: 1050, height: 690, resize: false, modal: true}).onclose(function(data,success)
        {
             /*$.post("SavePatientSummaryAsPDF.php",{IdUsu: <?php echo $UserID; ?>},function(data,success)
               {
              });*/
           
        });
    });
    $(".Telemed_Notes_Button").on('click',function(e)
    {
        $("#modal_phoneNotes").trigger('click');
    });

    $(".Telemed_Close_Button").on('click',function(e)

    {
        var doctorId = -1;
        <?php if(isset($_SESSION['MEDID'])){echo "doctorId = ".$_SESSION['MEDID'].";";} ?>;
        var userId = <?php echo $IdUsu; ?>;
        var json,findings,recommendations;
        
        $("#phoneTelemed").dialog("close");
        $("#videoTelemed").dialog("close");
        //Ajax call for retrieving the Notes from the temp file
        $.get("getNotes.php?doctorId="+doctorId,function(data,status)
              {
                    json = JSON.parse(data);
                    findings = "'"+json.findings+"'";
                    recommendations = "'"+json.recommendations+"'";
                    
                    console.log("In Get Notes"+"findings is:"+findings+"reco is:"+recommendations);
                      //Ajax call for creating PDF of notes and storing it in backend database
                $.post("addPatientNotes.php",{doctorId: doctorId,userId: userId,findings:findings,recommendations: recommendations},function(data,success)
                       {
                       });
                  
              });
        
        
        

        
        
        //ajax call for cerating PDF of summary and storing it in backend database
       /* $.get("medicalPassport_PDF.php?IdUsu="+userId, function(data, status)
              {
            
                 htmlContent = data;
                  
                  $.post("SavePatientSummaryAsPDF_table.php",{IdUsu:IdUsu},function(data,status)
                   {
                      console.log(data);
                   });
              });
        
        //window.open("medicalPassport_PDF.php?IdUsu="+userId); */
         
        //ajax call for cerating PDF of summary and storing it in backend database
        
        $.post("SavePatientSummaryAsPDF_table.php",{IdUsu:userId},function(data,status)
                   {
                      console.log(data);
                   });

        
        //Ajax call for sending mail to patient to access his records
        
        $.get("sendPatientNotesAndSummary.php?doctorId="+doctorId+"&userId="+userId,function(data,status)
              {
                
              });

        //window.location.replace("MainDashboard.php");

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
	 	    var idusfixed=<?php echo $IdUsFIXED;?>;
			var idusfixedname='<?php echo $IdUsFIXEDNAME;?>';
			var idmed = -1;
            <?php if(isset($_SESSION['MEDID'])){echo "idmed = ".$_SESSION['MEDID'].";";} ?>;
	        var fecha=$('#classification_datepicker').val();
			
	 	    var cadena = '<?php echo $domain; ?>/GrabaClasif.php?queBlock='+queBlock+'&queUser='+queUsu+'&queERU='+queERU+'&queEvento='+queEvento+'&queTipo='+queTipo+'&idusfixed='+idusfixed+'&idusfixedname='+idusfixedname+'&Idmed='+idmed+'&fecha='+fecha;
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
            var doctorId = -1;
            <?php if(isset($_SESSION['MEDID'])){echo "doctorId = ".$_SESSION['MEDID'].";";} ?>;
			var url = 'get_file_list.php?ElementDOM=na&EntryTypegroup=0&Usuario='+Usuario+'&MedID='+doctorId;
			//alert(url);
			var RecTipo = LanzaAjax (url);
		//alert(RecTipo);
			list = RecTipo.split(';');
		//}
		curr_file=-1;
		list.pop();
		next_click();
		
		
		
	});
	
	jQuery.fn.outerHTML = function(s) {
	return (s) ? this.before(s).remove() : jQuery("&lt;p&gt;").append(this.eq(0).clone()).html();
	}
	
 
	/*$("#Telemedicine").on('click',function() {

	 	$.ajax({
		 url: '<?php echo $domain?>/weemo_test.php?calleeID=<?php echo $email?>',
		 method: 'get',
			success: function(data){
			var htmlContent = data;
			var e = document.createElement('div');
			e.setAttribute('style', 'display: none;');
			e.innerHTML = htmlContent;
			document.body.appendChild(e);
			eval(document.getElementById("runscript").innerHTML);
			 }
		 });
		// window.open('<?php echo $domain?>/weemo_test.php?calleeID=<?php echo $email?>','_newtab');
		 // window.open(document.URL,'_self');

	}); */
	
	$("#choose_report_button").on('click', function()
    {
        $("#fileselect").trigger('click');
        //$(upload_dialog).dialog("close");
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
	var eDOM = '';
   $(".TABES").live('click',function() {
		//alert('clicked');
		if ($('#ascroll').length){
			var element = document.getElementById("ascroll");
			element.parentNode.removeChild(element);
		}	
		$(this).addClass("active");
		var queid = $(this).attr("id");
		var ElementDOM="";
        $("#stream_indicator").css("display", "block");
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
		//alert(queid);
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
		eDOM = ElementDOM;
		var EntryTypegroup =queid;
		var Usuario = $('#userId').val();
		var MedID =$('#MEDID').val();
        if(MedID < 0)
        {
            MedID = $("#USERID").val();
        }
		
		
		//$("#H2M_Spin_Stream").show();
	
		var queUrl = 'getNumThumbnails.php?ElementDOM=na&EntryTypegroup='+EntryTypegroup+'&Usuario='+Usuario+'&MedID='+MedID;
		//console.log(queUrl);
		num_reports = LanzaAjax (queUrl);
		//alert(num_reports);
	   
		
		
		
		
		//var queUrl ='CreateReportStream.php?ElementDOM=na&EntryTypegroup='+EntryTypegroup+'&Usuario='+Usuario+'&MedID='+MedID;
		var queUrl ='CreateReportStream.php?ElementDOM=na&EntryTypegroup='+EntryTypegroup+'&Usuario='+Usuario+'&MedID='+MedID+'&num_reports='+num_reports;
		//console.log(queUrl);
		
		
      	//alert (queUrl);
		//$("#ALL").show();
		//PrevElementDOM=ElementDOM;
      	//$(ElementDOM).load(queUrl,function() { alert( "Load was performed." );});
		var RecTipo='<span class="label label-success" style="left:0px; margin-left:0px; margin-top:20px; margin-bottom:20px; font-size:14px; text-shadow:none; text-decoration:none;background-color:red">Could not load Reports due to Internet issues</span>';
		setTimeout(function(){ 
				
				$.ajax(
				   {
				   url: queUrl,
				   dataType: "html",
				   async: false,
				   complete: function(){ //alert('Completed');
							},
				   success: function(data) {
							if (typeof data == "string") {
										RecTipo = data;
										$(ElementDOM).html(RecTipo);
										$(ElementDOM).scrollLeft(0);
										offset1=20;              //global var
										last_pos = 0;			//global var
                                        $("#stream_indicator").css("display", "none");
										
							   }
										
							 },
				   error: function(data){
						$(ElementDOM).html(RecTipo);
				   }
					
				});
				
				//var RecTipo = LanzaAjax (queUrl);
				//$(ElementDOM).html(RecTipo);
				//$(ElementDOM).load(queUrl);
				$(ElementDOM).trigger('click');
				$(ElementDOM).trigger('update');
				//$("#H2M_Spin_Stream").hide();
		},1000);
		
		
		//alert('here');
        setTimeout(function() {highlightattachedreports();},1000);  
		$(ElementDOM).show();
   
   });
   
	
   
   $('#LessPage').live('click',function(){
		//alert(eDOM);
		var newVal = $(eDOM).scrollLeft()-600;
		//$("#StreamContainerALL").scrollLeft($("#StreamContainerALL").scrollLeft()-165);
		$(eDOM).animate({ scrollLeft: newVal}, "slow");
   });
   
   $('#MorePage').live('click',function(){
		//alert(eDOM);
		var newVal = $(eDOM).scrollLeft()+600;
		//$("#StreamContainerALL").scrollLeft($("#StreamContainerALL").scrollLeft()+165);
		$(eDOM).animate({ scrollLeft: newVal}, "slow");
   });
   
   
   $("#StreamContainerALL").scroll(function() {
		scroller("#StreamContainerALL",0);
		
	});
	
	$("#StreamContainerIMAG").scroll(function() {
		scroller("#StreamContainerIMAG",1);
	});
	
	$("#StreamContainerLABO").scroll(function() {
		scroller("#StreamContainerLABO",2);
	});
	
	$("#StreamContainerDRRE").scroll(function() {
		scroller("#StreamContainerDRRE",3);
	});
	
	$("#StreamContainerOTHE").scroll(function() {
		scroller("#StreamContainerOTHE",4);
	});

	$("#StreamContainerNA").scroll(function() {
		scroller("#StreamContainerNA",5);
	});
	
	$("#StreamContainerSUMM").scroll(function() {
		scroller("#StreamContainerSUMM",6);
	});
	
	$("#StreamContainerPICT").scroll(function() {
		scroller("#StreamContainerPICT",7);
	});
	
	$("#StreamContainerPATN").scroll(function() {
		scroller("#StreamContainerPATN",8);
	});
	
	$("#StreamContainerSUPE").scroll(function() {
		scroller("#StreamContainerSUPE",9);
	});
        
    function countOccurences(str, value){
       var regExp = new RegExp(value, "gi");
       return str.match(regExp) ? str.match(regExp).length : 0;  
    }
   
   
    function scroller(ElementDOM,id)
	{
		if(last_pos < $(ElementDOM).scrollLeft())
		{	
			//if($(ElementDOM).scrollLeft() + $(ElementDOM).width() > $("#ascroll").width()-200) 
			
			if($(ElementDOM).scrollLeft() + $(ElementDOM).width() > ((offset1*160) + 60)) 
			{
				//$("#H2M_Spin_Stream").show();
				var t = setTimeout(function(){
												
											 },1000);
				
				last_pos=$(ElementDOM).scrollLeft();
				$(ElementDOM).children().eq(0).children().last().css("display", "block");
				var html = get_more_reports(id,offset1);       //get html data
				
				
				
				//$("#ascroll").append(html);				//append new html for reports
				
				
				
				
			}
			
		}
		else
			{
				//$("#H2M_Spin_Stream").hide();
			}
	}
	
	var lastRectipo='';
	function get_more_reports(queid,offset)
	{
		var EntryTypegroup =queid;
		var Usuario = $('#userId').val();
		var MedID =$('#MEDID').val();
        if(MedID < 0)
        {
            MedID = $("#USERID").val();
        }
        $("#stream_load_indicator").css("display", "block");
		//alert('before url ' + offset);
		var queUrl ='CreateReportStreamChunk.php?ElementDOM=na&EntryTypegroup='+EntryTypegroup+'&Usuario='+Usuario+'&MedID='+MedID+'&offset='+offset+'&jump='+jump;
		//alert(queUrl);
		var RecTipo='<span class="label label-success" style="left:0px; margin-left:0px; margin-top:20px; margin-bottom:20px; font-size:14px; text-shadow:none; text-decoration:none;background-color:red">Could not load Reports due to Internet issues</span>';
		//$("#H2M_Spin_Stream").show();
				
				$.ajax(
				   {
				   url: queUrl,
				   dataType: "html",
				   
				   complete: function(){ 
							},
				   success: function(data) {
							if (typeof data == "string") {
									
										RecTipo = data;
										//offset1=offset+Math.round(num_reports/2);
										offset1=offset+jump;
										//alert('new offset='+offset);	
							   }
							//$("#H2M_Spin_Stream").hide();	
							if(RecTipo != lastRectipo)
							{
							
								//console.log(queUrl);
								//alert(lastRectipo);
								//alert(RecTipo);
                                var count = countOccurences(RecTipo, "note2");  
				
				                var new_width=$("#ascroll").width()+(count * 160);			//set new width
                                $("#ascroll").css("width", new_width+"px");
                                
                                $('#ascroll').children().eq($('#ascroll').children().length - 2).after(RecTipo);
                                setTimeout(function() {highlightattachedreports();},1000);  
                                $("#stream_load_indicator").css("display", "none");
                                //alert(lastRectipo);
                                
                                //$("#ascroll").append(RecTipo);
                                lastRectipo=RecTipo;
                                if(count < 8)
                                {   $('#ascroll').children().last().css("display", "none");
                                    $('#ascroll').css("width", $('#ascroll').width() - 60);
                                }
							}
				},
				   error: function(data){
						displaynotification('Failed to Load Data','');
				   }
					
				});
		
		//return RecTipo;
		
	}    
   
   
   
   
    $("#BotonTestRS").live('click',function() {
		var ElementDOM ='testDIV';
		var EntryTypegroup ='3';
		var Usuario = $('#userId').val();
		var MedID =$('#MEDID').val();
		if(MedID < 0)
        {
            MedID = $("#USERID").val();
        }
		var queUrl ='CreateReportStream.php?ElementDOM='+ElementDOM+'&EntryTypegroup='+EntryTypegroup+'&Usuario='+Usuario+'&MedID='+MedID;
      	
      	$('#StreamContainer').load(queUrl);
    	$('#StreamContainer').trigger('update');

	});
	
	//$("#attachments").live('click',function() {
	
		

	//});
	
	var DELAY = 400,clicks = 0, timer = null;
	
	$(".note2").live("click", function(event,index,cascade)
    {
        var doc_id = <?php echo $IdUsu; ?>;
        var is_doc = false;
        <?php 
            if($MedID >= 0)
            {
                echo 'doc_id = '.$MedID.';';
                echo 'is_doc = true;';
            }
        ?>
        console.log("FILE CLICKED FOR DOCTOR");
        //var docviewer1 = document.getElementById('AreaConten');
        //docviewer1.style.display = 'block';
       // $('.TABES:eq(9)').click(); // commented out this line as because there was a bug earlier. When a thumbnail is clicked it was reverting back to reportstream fo superbill section

        var divIndex = $(this).index();
        //alert("divIndex: "+divIndex);
        var count = -1;
            $( ".marker" ).each(function() 
            {
                // alert("count: "+count);
                if(count==divIndex)
                {
                  id = $( this ).attr('id');
                  //alert("marker id: "+id);
                }  
                count++; 
            });
        //$("#"+id).trigger('click',[id,false]);	
        //$("#"+id).children('.flag').click();
        // ($('.timenav').children('.content')).trigger('click',[false,divIndex]);
        $('#AreaConten').show();
        $('#media-active').hide();	
        clicks++;  //count clicks
        //alert('event.originalEvent: '+event.originalEvent);
        if( event.originalEvent !== undefined)
        {
            if(clicks === 1) {
                var screen;
                window.location.href="#AreaConten";
                   if(index!= undefined )
                    screen = document.getElementById(index);
                   else
                    screen=this;
                    console.log(screen);
                   var queBLD = $(".queBLD", screen).attr("id");
                    console.log("queBLD"+queBLD);
                   timer = setTimeout(function() {
    
                    //alert(this);
                    
                    var queBLD = $(".queBLD", screen).attr("id");
                    //alert("queBLD"+queBLD);
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
                    
                    
                    var med=doc_id;
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
                    var isDicom = 0;
                    if(queImg.indexOf(".") == -1)
                    {
                        isDicom = 1;
                    }
                    
                    if (isDicom)
                    {
                        // load Dicom URL Here
                        $("#BotonMod").hide();
                        $('#AreaConten').show();
                        window.open("http://54.225.67.15/AlmaWebPlatform/WebViewer/webviewer.php?AccessionNumber="+IDPIN+"&PatientID="+queUsu+"&Width=1680&Height=911&Orientation=undefined&workerMode=2", "IsPacs","width=1680,height=911");
                        return 0;
                    }
                    else if (extensionR=='pdf')
                    {
                        var cadena = '<?php echo $domain;?>/DecryptFile.php?rawimage='+queImg+'&queMed='+doc_id;
                        var RecTipo = LanzaAjax (cadena);
                
                        var contenTHURL = '<?php echo $domain; ?>/temp/'+doc_id+'/PackagesTH_Encrypted/'+ImagenRaiz+'.png';  // PONER /PinImageSetTH/ en lugar de /PinImageSet/ PARA CARGAR THUMBNAILS EN LUGAR DE IMAGENES COMPLETAS
                        var contenURL =   '<?php echo $domain; ?>/temp/'+doc_id+'/Packages_Encrypted/'+ImagenRaiz+'.'+extensionR;  // PONER /PinImageSetTH/ en lugar de /PinImageSet/ PARA CARGAR THUMBNAILS EN LUGAR DE IMAGENES COMPLETAS
                        //var conten = '<img id="ImagenN" src="'+contenURL+'" alt="">';
                        var conten =  '<iframe id="ImagenN" style="border:1px solid #666CCC; margin:0 auto; display:block;" title="PDF" src="'+contenURL+'" alt="'+queId+'" frameborder="1" scrolling="auto" height="850" width="600" ></iframe>';
                        var contenTH = '<img id="ImagenTH"  src="'+contenTHURL+'" alt="">';
                    }
                    else if(extensionR=='MOV')
                    {
                        var cadena = '<?php echo $domain;?>/DecryptFile.php?rawimage='+queImg+'&queMed='+doc_id;
                        var RecTipo = LanzaAjax (cadena);
                
                    
                        $('#AreaConten').hide();
                        //var docviewer = document.getElementById('AreaConten');
                        //docviewer.style.display = 'none';
                        //alert('here');		
                        /*document.getElementById('media-active').innerHTML='';
                        document.getElementById('media-active').innerHTML='<embed src="video/abc.MOV" height="500" width="800" controller="true" autoplay="true" type="video/quicktime" pluginspage="http://www.apple.com/quicktime/download/" ></embed>';   
                        document.getElementById('media-active').display='block';*/
                        alert('Videos may take time to load depending on your internet speed .');
                        var src = '<?php echo $domain; ?>/temp/'+doc_id+'/Packages_Encrypted/'+ImagenRaiz+'.'+extensionR;
                        //alert(src);
                        $('#media-active').html('<embed src="'+src+'" height="500" width="800" controller="true" autoplay="true" type="video/quicktime" pluginspage="http://www.apple.com/quicktime/download/" >');
                        $('#media-active').show();
                        
                        /*$('#videoplayer').show();
                        
                        
                        var vp = document.getElementById('videoplayer');
                        //vp.style.display = 'block';
                        vp.setAttribute("src","video/abc.MOV");
                        vp.setAttribute("autoplay","true");
                        //vp.style.display='block';
                        */
                        
                        
                        
                        
                    }
                    else if(extensionR=='jpg' || extensionR=='jpeg' || extensionR=='JPG' )
                    {
                        //alert(queImg);
                        var cadena = '<?php echo $domain;?>/DecryptFile.php?rawimage='+queImg+'&queMed='+doc_id;
                        var RecTipo = LanzaAjax (cadena);
                
                        var contenTHURL = '<?php echo $domain; ?>/temp/'+doc_id+'/PackagesTH_Encrypted/'+queImg;  // PONER /PinImageSetTH/ en lugar de /PinImageSet/ PARA CARGAR THUMBNAILS EN LUGAR DE IMAGENES COMPLETAS
                        var contenURL =   '<?php echo $domain; ?>/temp/'+doc_id+'/Packages_Encrypted/'+queImg;  // PONER /PinImageSetTH/ en lugar de /PinImageSet/ PARA CARGAR THUMBNAILS EN LUGAR DE IMAGENES COMPLETAS
                        //var conten = '<img id="ImagenN" src="'+contenURL+'" alt="">';
                        //var conten =  '<iframe id="ImagenN" style="border:1px solid #666CCC; margin:0 auto; display:block;" title="JPG" src="'+contenURL+'" alt="'+queId+'" frameborder="1" scrolling="auto" height="850" width="600" ></iframe>';
                        var conten = '<img id="ImagenN" style="border:1px solid #666CCC; margin:0 auto; display:block;max-height:1500px;max-width:600px;"  src="'+contenURL+'" alt="'+queId+'">';
                        var contenTH = '<img  id="ImagenTH"  src="'+contenTHURL+'" alt="" style="max-height:1500px;max-width:600px;">';
                    }
                    else{
                        var canal = null;
                        <?php 
                            if(isset($row['CANAL']))
                            {
                                echo 'canal = '.$row['CANAL'].';';
                            }
                        ?>
                        if(canal == '7' ||extensionR=='png'||extensionR=='PNG'){
                            var cadena = '<?php echo $domain;?>/DecryptFile.php?rawimage='+queImg+'&queMed='+doc_id;
                            var RecTipo = LanzaAjax (cadena);
                
                            var contenTHURL = '<?php echo $domain; ?>/temp/'+doc_id+'/PackagesTH_Encrypted/'+queImg; 
                            var conten = '<img id="ImagenN" src="<?php echo $domain;?>/temp/'+doc_id+'/Packages_Encrypted/'+queImg+'" alt="'+queId+'" style="border:1px solid #666CCC; margin:0 auto; display:block;">';
                                //alert('here');
                        }else{
                        if (subtipo=='XX') { 
                            var cadena = '<?php echo $domain;?>/DecryptFile.php?rawimage='+queImg+'&queMed='+doc_id;
                            var RecTipo = LanzaAjax (cadena);
                
                            var contenTHURL = '<?php echo $domain; ?>/temp/'+doc_id+'/PackagesTH_Encrypted/'+queImg; 
                            var conten = '<img id="ImagenN" src="<?php echo $domain;?>/temp/'+doc_id+'/Packages_Encrypted/'+queImg+'" alt="'+queId+'" style="border:1px solid #666CCC; margin:0 auto; display:block;">';
                            }
                        else{ 
                            var contenTHURL = '<?php echo $domain; ?>/eMapLife/PinImageSetTH/'+queImg; 
                            var conten = '<img id="ImagenN" src="<?php echo $domain; ?>/eMapLife/PinImageSet/'+queImg+'" alt="'+queId+'" style="border:1px solid #666CCC; margin:0 auto; display:block;">';
                            }  // PONER /PinImageSetTH/ en lugar de /PinImageSet/ PARA CARGAR THUMBNAILS EN LUGAR DE IMAGENES COMPLETAS
                        
                    //if(urlExists(contenTHURL)) {}else { contenTHURL = '<?php $domain?>/eMapLife/PinImageSet/'+queImg;}
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
                    
                    //Here changes are required for the multiple referrals area
                    if(referral_state==2){
            
                        var cadena='getReportInformationReview.php?referralid=<?php echo $referral_id;?>';
                        //alert(cadena);
                        var status=LanzaAjax(cadena);
                        //alert(status);
                        if(parseInt(status)){
                        $("div#infr").css("color","#3d93e0");
                        $("div#infr").children("*").css("color","#3d93e0");
                        $(screen).css("color","#3d93e0");
                        
                        var cadena='setReferralStage.php?referralid=<?php echo $referral_id;?>&stage=3';
                        LanzaAjax(cadena);
                        referral_state=3;
                        $("div#infr_btn").append('<i style="margin-left:10px;font-size:15px" class="icon-arrow-right"></i>');
                        var cadena='push_server.php?message="Referral stage information review is completed"&NotifType=2&IdUsu=<?php echo $IdUsu;?>&FromDoctorId='+doc_id+'&channel='+<?php echo $otherdoc?>;
                        var RecTipo=LanzaAjax(cadena);
                        var content="referal stage information review is completed. This is a system Generated Message";
                        var subject="Referral stage information";
                        var reportids=0;
                        var cadena='sendMessage.php?sender='+doc_id+'&receiver=<?php echo $otherdoc;?>&patient=<?php echo $USERID;?>'+'&content='+content+'&subject='+subject+'&attachments='+reportids+'&connection_id=<?php echo $referral_id;?>';
                        var RecTipo=LanzaAjax(cadena);
                        displaynotification('status','Referal stage information review is completed');
                        setTimeout(function(){window.location.reload(true)},2000); 
                        }
                    
                    //update referral table.
                    }
                    
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
                if(index!= undefined )
                    screen = document.getElementById(index);
                else
                    screen=this;
                clearTimeout(timer);  //prevent single-click action
                var queUsu = $("#IdUsuP").val();
                var idpin = $(screen).attr("id");
                //var med=<?php echo $IdMed ?>;
                //var privstate=$(screen).attr("privstate");
                var queBLD = $(".queBLD", screen).attr("id");
                //alert('queBLD: '+queBLD);
                var quePEN=  $(".quePEN", screen).attr("id");
                //alert("quePEN: "+quePEN);
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
						
						//alert("yup, its valid number digits");
					} else {
						//alert("not valid number");
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
   
   
    //changes for the audio files
			  	$('#saveaudio').prop('disabled',true);	
			   
			   var audioContext = new AudioContext();
				var audioInput = null,
					realAudioInput = null,
					inputPoint = null,
					audioRecorder = null;
				var rafID = null;
				var analyserContext = null;
				var canvasWidth, canvasHeight;
				var recIndex = 0;
				
				
				function toggleRecording( e ) {
				if (e.classList.contains("recording")) {
					// stop recording
					window.clearInterval(audiotimer);
					audioRecorder.stop();
					e.classList.remove("recording");
					$('#record').val('Start');
					//audioRecorder.getBuffer( drawWave );
					$('#saveaudio').prop('disabled',false);	
				} else {
					// start recording
					if (!audioRecorder){
						alert("Error in capturing audio");
						return;
					}
					$('#saveaudio').prop('disabled',true);	
					e.classList.add("recording");
					$('#record').val('Stop');
					audioRecorder.clear();
					audioRecorder.record();
					var start = new Date;
					var i=0;
					audiotimer=setInterval(function() {
							$('.Timer').text((i++) + " Seconds");
					}, 1000);
				}
			}
			
				




			   /*function drawWave( buffers ) {
				var canvas = document.getElementById( "wavedisplay" );

				drawBuffer( canvas.width, canvas.height, canvas.getContext('2d'), buffers[0] );
			   }*/
	   
		    function saveAudio() {
				audioRecorder.exportWAV( upload );
				// could get mono instead by saying
				// audioRecorder.exportMonoWAV( doneEncoding );
			   }

			function convertToMono( input ) {
				var splitter = audioContext.createChannelSplitter(2);
				var merger = audioContext.createChannelMerger(2);

				input.connect( splitter );
				splitter.connect( merger, 0, 0 );
				splitter.connect( merger, 0, 1 );
				return merger;
			}

			function cancelAnalyserUpdates() {
				window.cancelAnimationFrame( rafID );
				rafID = null;
			}

			function updateAnalysers(time) {
				if (!analyserContext) {
					var canvas = document.getElementById("analyser");
					canvasWidth = canvas.width;
					canvasHeight = canvas.height;
					analyserContext = canvas.getContext('2d');
				}

				// analyzer draw code here
				{
					var SPACING = 3;
					var BAR_WIDTH = 1;
					var numBars = Math.round(canvasWidth / SPACING);
					var freqByteData = new Uint8Array(analyserNode.frequencyBinCount);

					analyserNode.getByteFrequencyData(freqByteData); 

					analyserContext.clearRect(0, 0, canvasWidth, canvasHeight);
					analyserContext.fillStyle = '#F6D565';
					analyserContext.lineCap = 'round';
					var multiplier = analyserNode.frequencyBinCount / numBars;

					// Draw rectangle for each frequency bin.
					for (var i = 0; i < numBars; ++i) {
						var magnitude = 0;
						var offset = Math.floor( i * multiplier );
						// gotta sum/average the block, or we miss narrow-bandwidth spikes
						for (var j = 0; j< multiplier; j++)
							magnitude += freqByteData[offset + j];
						magnitude = magnitude / multiplier;
						var magnitude2 = freqByteData[i * multiplier];
						analyserContext.fillStyle = "hsl( " + Math.round((i*360)/numBars) + ", 100%, 50%)";
						analyserContext.fillRect(i * SPACING, canvasHeight, BAR_WIDTH, -magnitude);
					}
				}
				
				rafID = window.requestAnimationFrame( updateAnalysers );
			}

			function toggleMono() {
				if (audioInput != realAudioInput) {
					audioInput.disconnect();
					realAudioInput.disconnect();
					audioInput = realAudioInput;
				} else {
					realAudioInput.disconnect();
					audioInput = convertToMono( realAudioInput );
				}

				audioInput.connect(inputPoint);
			}

			function gotStream(stream) {
				inputPoint = audioContext.createGain();

				// Create an AudioNode from the stream.
				realAudioInput = audioContext.createMediaStreamSource(stream);
				audioInput = realAudioInput;
				audioInput.connect(inputPoint);

			//    audioInput = convertToMono( input );

				analyserNode = audioContext.createAnalyser();
				analyserNode.fftSize = 2048;
				inputPoint.connect( analyserNode );

				audioRecorder = new Recorder( inputPoint );

				zeroGain = audioContext.createGain();
				zeroGain.gain.value = 0.0;
				inputPoint.connect( zeroGain );
				zeroGain.connect( audioContext.destination );
				updateAnalysers();
			}
			
			var initFlag=0;
			function initAudio() {
					if (!navigator.getUserMedia)
						navigator.getUserMedia = navigator.webkitGetUserMedia || navigator.mozGetUserMedia;
					if (!navigator.cancelAnimationFrame)
						navigator.cancelAnimationFrame = navigator.webkitCancelAnimationFrame || navigator.mozCancelAnimationFrame;
					if (!navigator.requestAnimationFrame)
						navigator.requestAnimationFrame = navigator.webkitRequestAnimationFrame || navigator.mozRequestAnimationFrame;
				initFlag=1;
				navigator.getUserMedia({audio:true}, gotStream, function(e) {
						alert('Error getting audio');
						initFlag=0;
						console.log(e);
					});
			}

			//window.addEventListener('load', initAudio );
			
			
			   function upload(blob) {
				  var xhr=new XMLHttpRequest();
				  $('#wait_audio').show();
				  xhr.onload=function(e) {
					  if(this.readyState === 4) {
						  console.log("Server returned: ",e.target.responseText);
						  //alert('Audio data successfully saved into Health2me Server.');
					  }
				  };
				  xhr.onreadystatechange=function()
				  {
					  if (xhr.readyState==4 && xhr.status==200)
						{
							alert('Audio data successfully saved into Health2me Server.');
							$('#saveaudio').prop('disabled',true);
							$('#wait_audio').hide();
							$('.Timer').text("");
							$('#closeaudiotab').click();
						}
					 if (xhr.readyState==4 && xhr.status!=200){
							
							alert('Audio data transfer error!');
							$('#saveaudio').prop('disabled',true);
							$('#wait_audio').hide();
							$('.Timer').text("");
						
						}
				  }
				  var fd=new FormData();
				  fd.append("file",blob);

                  var doc_id = 0;
                  <?php
                        if(isset($MedID))
                        {
                            echo 'doc_id = '.$MedID.';';
                        }
                  ?>
				  xhr.open("POST","upload_file_audio.php?queId=<?php echo $IdUsu; ?>&from="+doc_id,true);
				  xhr.send(fd);
				  //alert('Audio data successfully saved into Health2me Server');
				}
				
				
		  $('#Dictate').live('click', function() {
				if(initFlag==0)
					initAudio();
				$('#message_modal_audio').click();
		   });
			   
			  
		 $('#record').live('click', function() { toggleRecording(this);}); 
			   
		 $('#saveaudio').live('click', function() { saveAudio(); }); 

   
   //Adding datepicker for the upload report
    $("#APP_Date" ).datepicker({
        inline: true,
        nextText: '&rarr;',
        prevText: '&larr;',
        showOtherMonths: true,
        dateFormat: 'yy-mm-dd',
        dayNamesMin: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
        showOn: "button",
        buttonImage: "images/calendar-blue.png",
        buttonImageOnly: true,
        changeYear: true ,
        changeMonth: true,
        yearRange: '1900:c',
	});
	
	var rep_date;
	$('#datepicker2').change(function() {
				
			//var idpin = $('#idpin').val();
			rep_date = $('#datepicker2').val();
			//alert('Report Date '+ rep_date);
			//$("report_date").attr('val',rep_date);
	});
	
	var types;
	
	$('#reptype').change(function() {
			//var idpin = parseInt($('#idpin').val());
			var report_type = document.getElementById("reptype");
			types = report_type.options[report_type.selectedIndex].value;
			//alert('changed '+types);
			$("report_type").attr('val',types);
			
			//alert('changed1 '+document.getElementById.("report_date").getAttribute("value"));
			//alert('changed2 '+document.getElementById.("report_type").getAttribute("value"));
			
		
	});
	
	
	function $id(id) {
		return document.getElementById(id);
	}


	// output information
	function Output(msg) {
		var m = $id("messages");
		//m.innerHTML = msg + m.innerHTML;
		m.innerHTML = msg;
	}


	// file drag hover
	function FileDragHover(e) {
		e.stopPropagation();
		e.preventDefault();
		e.target.className = (e.type == "dragover" ? "hover" : "");
	}

	var files_upload;
	
	var isFileUploaded=0;

	// file selection
	function FileSelectHandler(e) {

		// cancel event and hover styling
		FileDragHover(e);

		// fetch FileList object
		var files = e.target.files || e.dataTransfer.files;
	
		files_upload=files;
		isFileUploaded==0;
		// process all File objects
		
		//Added code to check whether the date and report type has already been selected.
		
		/*var repdate=$id("report_date").getAttribute("value");
		var reptype=$id("report_type").getAttribute("value");*/
		
		
		if(types==0)
		{
			alert("Please select the type of the report");
			return;
		}
		
		if (files.length>1)
			alert("This feature only allows to upload single file at once");
		else{
			for (var i = 0, f; f = files[i]; i++) {
				//ParseFile(f);
				//UploadFile(f);
                var m = $id("messages");
		        //m.innerHTML = msg + m.innerHTML;
		        m.innerHTML = "Selected File: " + files[i].name;
                
			}
		}

	}


	// output file information
	function ParseFile(file) {

		/*Output(
			"<p>File information: <strong>" + file.name +
			"</strong> type: <strong>" + file.type +
			"</strong> size: <strong>" + file.size +
			"</strong> bytes</p>"
		);*/
		
		//alert("File src:"+file);

		// display an image
		if (file.type.indexOf("image") == 0) {
			var reader = new FileReader();
			reader.onload = function(e) {
				Output(
					"<p>File information: <strong>" + file.name +
					"</strong> type: <strong>" + file.type +
					"</strong> size: <strong>" + file.size +
					"</strong> bytes</p>"+"<p><strong>" + file.name + 
					":</strong><br />" + '<img src="' + e.target.result + '" /></p>'
				);
			}
			reader.readAsDataURL(file);
		}

		// display text
		if (file.type.indexOf("text") == 0) {
			var reader = new FileReader();
			reader.onload = function(e) {
				Output(
					"<p><strong>" + file.name + ":</strong></p><pre>" +
					e.target.result.replace(/</g, "&lt;").replace(/>/g, "&gt;") +
					"</pre>"
				);
			}
			reader.readAsText(file);
		}
		
		
		if (file.type.indexOf("pdf") == 12) {
			var reader = new FileReader();
			reader.onload = function(e) {
			
				//alert("File src:"+e.target.result);
				
				Output(
					"<p><strong>" + file.name + ":</strong></p>" +
					'<br /><iframe style="border:1px solid #666CCC" title="PDF" src="'+ 
					e.target.result+'" alt="Loading" frameborder="1" scrolling="auto" height="700" width="600" ></iframe>'
					
				);
			}
			reader.readAsDataURL(file);
		
		
		}

	}


	// upload JPEG files
	function UploadFile(file) {

		// following line is not necessary: prevents running on SitePoint servers
		if (location.host.indexOf("sitepointstatic") >= 0) return

		var xhr = new XMLHttpRequest();
		//alert("outside Upload");
			
		//if (xhr.upload && file.type == "image/jpeg" && file.size <= $id("MAX_FILE_SIZE").value) {
		if (xhr.upload) {
		
            $("#H2M_Spin_upload").css("visibility", "visible");
			isFileUploaded=1;
			//alert("Inside Upload");
			//$id("submitbutton").style.display = "none";
			
			//alert("report date" + rep_date);
			
			// create progress bar
			/*var o = $id("progress");
			var progress = o.appendChild(document.createElement("p"));
			progress.appendChild(document.createTextNode("upload " + file.name));


			// progress bar
			xhr.upload.addEventListener("progress", function(e) {
				var pc = parseInt(100 - (e.loaded / e.total * 100));
				progress.style.backgroundPosition = pc + "% 0";
			}, false);*/

			// file received/failed
			xhr.onreadystatechange = function(e) {
				if (xhr.readyState == 4) {
					progress.className = (xhr.status == 200 ? "success" : "failure");
					if(xhr.status==200) {
						
						$("#H2M_Spin_upload").css("visibility", "hidden");
						//alert('Report created successfully');
						//Init();
						isFileUploaded=0;
						setTimeout(function(){window.location.reload()},100);
					}
						//$id("submitbutton").style.display = "block";
						
				}
			};

			// start upload
			var fd=new FormData();
			fd.append("file",file);
			
			
			
			fd.append("reportdate", rep_date);
			fd.append("reporttype",types);
			
			xhr.open("POST", $id("upload").action, true);
			//xhr.setRequestHeader("", file.name);
			xhr.send(fd);
			//alert("Inside Upload END");
			
		}

	}


	// initialize
	function Init() {

		var fileselect = $id("fileselect"),
			filedrag = $id("filedrag"),
			submitbutton = $id("submitbutton");

		// file select
		fileselect.addEventListener("change", FileSelectHandler, false);

		// is XHR2 available?
		var xhr = new XMLHttpRequest();
		
		//alert("Init function:"+xhr.upload);
		if (xhr.upload) {

			// file drop
			/*filedrag.addEventListener("dragover", FileDragHover, false);
			filedrag.addEventListener("dragleave", FileDragHover, false);
			filedrag.addEventListener("drop", FileSelectHandler, false);
			filedrag.style.display = "block";*/

			// remove submit button
			//submitbutton.style.display = "none";
		}

	}

	// call initialization file
	
	if (window.File && window.FileList && window.FileReader) {
		Init();
	}
	
	/*$("#upload_report").live('click', function(){
	
	
		if(rep_date==null || types==0)
			alert("Report Date or Type Missing. Enter these values before proceeding");
			
		else{
	
			if (files_upload.length>1)
				alert("This feature only allows to upload single file at once");
			else{
				for (var i = 0, f; f = files_upload[i]; i++) {
					//ParseFile(f);
					UploadFile(f);
				}
			}
		
		}
		//Init();
		
	
	});*/



	//This function will create dialog modal form
	
	  

	$("#BotonUpload_New").live('click',function(){
		//$( "#dialog-form" ).dialog( "open" );
        
        // for the HTI version, there is a limit of 5 files. If the user already has 5 files, 
        // then they can't upload anymore and the following modal should not open
        
        <?php if (isset($_SESSION['CustomLook']) && $_SESSION['CustomLook']=="COL") { ?>
        $.post("get_num_reports.php", {id: $("#USERID").val()}, function(data, status)
        {
            var num = parseInt(data);
            if(num < 5)
            {
        <?php } ?>
                $( "#upload_reports_form" ).dialog({
                      autoOpen: true,
                      resizable:false,
                      height: 460,
                      width: 700,
                      modal: true,
                      buttons: [{
                        text: "Upload Report", click: function() {
                            if(isFileUploaded==0){
                        
                            if(rep_date==null || types==0)
                            alert("Report Date or Type Missing. Enter these values before proceeding");
                                
                            else{
                        
                                if (files_upload.length>1)
                                    alert("This feature only allows to upload single file at once");
                                else{
                                
                                
                                    for (var i = 0, f; f = files_upload[i]; i++) {
                                        //ParseFile(f);
                                        UploadFile(f);
                                    }
                                }
                            
                            }
                          }else
                            alert("Health2Me detected that you are pressing the Upload button Multiple times! Doesn't matter it will upload file only once");
                         
                        }, style: "background-color: #5EB529; color: #FFF; outline: 0px; border: 0px solid #FFF; border-radius: 5px; width: 150px; font-size: 14px;"},
                                {text: "Cancel", click: function() {
                            if(isFileUploaded!=0)
                                alert('Your report is being sent to Health2Me central repository. Please wait!');
                            $(this).dialog("close");
                        }, style: "background-color: #D84840; color: #FFF; outline: 0px; border: 0px solid #FFF; border-radius: 5px; width: 150px; font-size: 14px;"}]
                      ,
                      close: function() {
                            if(isFileUploaded!=0)
                                alert('Your report is being sent to Health2Me central repository. Please wait!');
                      }
                });
        <?php if (isset($_SESSION['CustomLook']) && $_SESSION['CustomLook']=="COL") { ?>
            }
            else
            {
                alert("You have reached your limit of 5 uploads. To upload more records, you need to delete some records");
            }
        
        });
        <?php } ?>
        
	});
   
   //Changes for adding messagings and notification services 
	
   //Add changes for multi-referral area
   var referral_id_array=new Array();
   var referral_type_array=new Array();
   var otherdocid=new Array();
   if(ismultireferral==1){
   for (var i=0;i<multireferral_num;i++) { 
			//alert(i);
			//ismultireferral=1;
			referral_id_array[i]=parseInt($('#referral_id'+i).val());
			otherdocid[i]=parseInt($('#otherdocid'+i).val());
			referral_type_array[i]=parseInt($('#referraltype'+i).val());
			
		if(referral_state_array[i]>=1){
			$("div#ack"+i).css("color","#3d93e0");
			$("div#ack"+i).children("*").css("color","#3d93e0");
			$("div#ack_btn"+i+" a").css("color","#3d93e0");
			$("#ack_ok"+i).show();
			$("div#ack_btn"+i).append('<i style="margin-left:10px;font-size:15px" class="icon-arrow-right" id="ack_arrow'+i+'"></i>');
			if (referral_state_array[i]>=2){
			
				if(referral_type_array[i]==1){
					$("div#infr_ref"+i).css("color","#3d93e0");
					$("div#infr_ref"+i).children("*").css("color","#3d93e0");
					$("div#infr_ref_btn"+i+" a").css("color","#3d93e0");
					$("div#infr_ref_btn"+i).append('<i style="margin-left:10px;font-size:15px" class="icon-arrow-right"></i>');
					if (referral_state_array[i]==3){
							$("div#cmnt_ref"+i).css("color","#3d93e0");
							$("div#cmnt_ref"+i).children("*").css("color","#3d93e0");
							$("div#cmnt_ref_btn"+i+" a").css("color","#3d93e0");
					}				
					
				}else{
					//$("div#reject_btn").hide();
					$("div#app"+i).css("color","#3d93e0");
					$("div#app"+i).children("*").css("color","#3d93e0");
					$("div#app_btn"+i+" a").css("color","#3d93e0");
					$("div#app_btn"+i).append('<i style="margin-left:10px;font-size:15px" class="icon-arrow-right"></i>');
					if (referral_state_array[i]>=3){
						$("div#infr"+i).css("color","#3d93e0");
						$("div#infr"+i).children("*").css("color","#3d93e0");
						$("div#infr_btn"+i+" a").css("color","#3d93e0");
						$("div#infr_btn"+i).append('<i style="margin-left:10px;font-size:15px" class="icon-arrow-right"></i>');
						if (referral_state_array[i]==4){
							$("div#inpa"+i).css("color","#3d93e0");
							$("div#inpa"+i).children("*").css("color","#3d93e0");
							$("div#inpa_btn"+i+" a").css("color","#3d93e0");
						
						}
					}
				}			
			}
		}else if(referral_state_array[i]==-1){
						//Disable all ack button
						$("div#ack"+i).css("color","#F16C6C");
						$("div#ack"+i).children("*").css("color","#F16C6C");
						$("div#ack_btn"+i+" a").css("color","#F16C6C");
						$("#ack_ok"+i).show();
						$("div#ack_btn"+i+" a").attr('disabled', 'disabled');
						
						
						//Disable app button
						$("div#app"+i).css("color","#F16C6C");
						$("div#app"+i).children("*").css("color","#F16C6C");
						$("div#app_btn"+i+" a").css("color","#F16C6C");
						$("div#app_btn"+i+" a").attr('disabled', 'disabled');
						
						//Disable infr button
						$("div#infr"+i).css("color","#F16C6C");
						$("div#infr"+i).children("*").css("color","#F16C6C");
						$("div#infr_btn"+i+" a").css("color","#F16C6C");
						$("div#infr_btn"+i+" a").attr('disabled', 'disabled');
						
						//Disable interview patient button
						$("div#inpa"+i).css("color","#F16C6C");
						$("div#inpa"+i).children("*").css("color","#F16C6C");
						$("div#inpa_btn"+i+" a").css("color","#F16C6C");
						$("div#inpa_btn"+i+" a").attr('disabled', 'disabled');				
		}
		
		 setTimeout(function(){
					$('#newinbox'+i).trigger('click');},1000);
		
	 }} else {
	
			if(referral_state>=1){
			$("div#ack").css("color","#3d93e0");
			$("div#ack").children("*").css("color","#3d93e0");
			$("div#ack_btn a").css("color","#3d93e0");
			$("#ack_ok").show();
			$("div#ack_btn").append('<i style="margin-left:10px;font-size:15px" class="icon-arrow-right"></i>');
			if (referral_state>=2){
				//$("div#reject_btn").hide();
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
   	
	}
			
			$("div[id^='reject_btn'] a").live('click',function(){
			
				var idstring=$(this).parents("div[id^='reject_btn']").attr("id");
				var i=idstring.substring(idstring.length,idstring.length-1);
				
				//alert (referral_state_array[i]);

				option1=confirm("Are you sure you want to reject this referred patient ?");
				if (option1)
				{
						
						$("#H2M_Spin").show();
						setTimeout(function(){
						//Disable all ack button
						$("div#ack"+i).css("color","#F16C6C");
						$("div#ack"+i).children("*").css("color","#F16C6C");
						$("div#ack_btn"+i+" a").css("color","#F16C6C");
						$("#ack_ok"+i).show();
						$("#ack_arrow"+i).hide();
						$("div#ack_btn"+i+" a").attr('disabled', 'disabled');
						
						
						//Disable app button
						$("div#app"+i).css("color","#F16C6C");
						$("div#app"+i).children("*").css("color","#F16C6C");
						$("div#app_btn"+i+" a").css("color","#F16C6C");
						$("div#app_btn"+i+" a").attr('disabled', 'disabled');
						
						//Disable infr button
						$("div#infr"+i).css("color","#F16C6C");
						$("div#infr"+i).children("*").css("color","#F16C6C");
						$("div#infr_btn"+i+" a").css("color","#F16C6C");
						$("div#infr_btn"+i+" a").attr('disabled', 'disabled');
						
						//Disable interview patient button
						$("div#inpa"+i).css("color","#F16C6C");
						$("div#inpa"+i).children("*").css("color","#F16C6C");
						$("div#inpa_btn"+i+" a").css("color","#F16C6C");
						$("div#inpa_btn"+i+" a").attr('disabled', 'disabled');						
						
						
						
						var cadena='setReferralStage.php?referralid='+referral_id_array[i]+'&stage=-1';
						salida = LanzaAjax(cadena);
						
						referral_state_array[i]=-1;
						
						var cadena='push_server.php?message="Referral has been rejected!"&NotifType=2&IdUsu=<?php echo $IdUsu;?>&FromDoctorId=<?php echo $IdMed;?>&channel='+otherdocid[i];
						var RecTipo=LanzaAjax(cadena);
						var content=" The referral has been rejected. This is a System Generated Message";
						var subject="Referral stage information";
						var reportids=0;
						var cadena='sendMessage.php?sender=<?php echo $MedID;?>&receiver='+otherdocid[i]+'&patient=<?php echo $USERID;?>'+'&content='+content+'&subject='+subject+'&attachments='+reportids+'&connection_id='+referral_id_array[i];
						var RecTipo=LanzaAjax(cadena);
						$("#H2M_Spin").hide();
						displaynotification('status','Referral rejection confirmed!');
						("div#reject_btn"+i).hide();
						},1000);
						
				}
			
			});
			
			//Adding acknowledgement directly through the system
			
			$("div[id^='ack_btn_pending'] a").live('click',function(){
			
				var idstring=$(this).parents("div[id^='ack_btn_pending']").attr("id");
				var i=idstring.substring(idstring.length,idstring.length-1);
				
				$(this).parents("div.note2").attr('id')
				//alert (i);
				
				/*if(referral_state_array[i]==0){
				alert('The referral stages functionality is not working. Please contact Health2me!');
				}else{
					
					if(referral_state_array[i]>1)
						 displaynotification('status','This stage is already complete!');
						
					
				}*/
				
				//alert(referral_id_array[i]);
				
				var cadena='setReferralStage.php?referralid='+referral_id_array[i]+'&stage=1&pending=1';
				//alert(cadena);
				var RecTipo=LanzaAjax(cadena);
				//alert(RecTipo);
				referral_state_array[i]=1;				
				displaynotification('status','You have acknowledged the referred patient!');
				var cadena='push_server.php?message="Referral stage Acknowledgement is complete!"&NotifType=2&IdUsu=<?php echo $IdUsu;?>&FromDoctorId=<?php echo $IdMed;?>&channel='+otherdocid[i];
				var RecTipo=LanzaAjax(cadena);
				var content="referal stage Acknowledgement is completed. This is a System Generated Message";
				var subject="Referral stage information";
				var reportids=0;
				var cadena='sendMessage.php?sender=<?php echo $MedID;?>&receiver='+otherdocid[i]+'&patient=<?php echo $USERID;?>'+'&content='+content+'&subject='+subject+'&attachments='+reportids+'&connection_id='+referral_id_array[i];
				var RecTipo=LanzaAjax(cadena);
				setTimeout(function(){window.location.reload()},100);
			
			});
			

			$("div[id^='ack_btn'] a").live('click',function(){
			
			var idstring=$(this).parents("div[id^='ack_btn']").attr("id");
			var i=idstring.substring(idstring.length,idstring.length-1);
			
			$(this).parents("div.note2").attr('id')
			//alert (i);
			
			if(referral_state_array[i]==0){
			alert('The referral stages functionality is not working. Please contact Health2me!');
			}else{
				
				if(referral_state_array[i]>1)
					 displaynotification('status','This stage is already complete!');
					
				
			}
			
			});
			
			
		   $("div[id^='inpa_btn'] a").live('click',function(){
				var idstring=$(this).parents("div[id^='inpa_btn']").attr("id");
				var i=idstring.substring(idstring.length,idstring.length-1);
				//alert (i);
			if(referral_state_array[i]==0){
			alert('The referral stages functionality is not working. Please contact Health2me!');
			}else{
			
				if(referral_state_array[i]==4)
					 displaynotification('status','This stage is already complete!');
				
				if(referral_state_array[i]<3 && referral_state_array[i]!=-1)
					 alert('Previous Stages are not complete!');
					
				else if(referral_state_array[i]==3){
				$("div#inpa"+i).css("color","#3d93e0");
				$("div#inpa"+i).children("*").css("color","#3d93e0");
				$(this).css("color","#3d93e0");
				var cadena='setReferralStage.php?referralid='+referral_id_array[i]+'&stage=4';
				LanzaAjax(cadena);
				referral_state_array[i]=4;
				
				displaynotification('status','Referral stages for this patient completed!');
				var cadena='push_server.php?message="Referral stages has been completed"&NotifType=2&IdUsu=<?php echo $IdUsu;?>&FromDoctorId=<?php echo $IdMed;?>&channel='+otherdocid[i];
				var RecTipo=LanzaAjax(cadena);
				var content="referal stage visit is completed. This is a System Generated Message";
				var subject="Referral stage information";
				var reportids=0;
				var cadena='sendMessage.php?sender=<?php echo $MedID;?>&receiver='+otherdocid[i]+'&patient=<?php echo $USERID;?>'+'&content='+content+'&subject='+subject+'&attachments='+reportids+'&connection_id='+referral_id_array[i];
				var RecTipo=LanzaAjax(cadena);
				//update referral table.
				}
			}
			
			});
			
			
			//Adding information review for new referrals
			 $("div[id^='infr_ref_btn'] a").live('click',function(){
				var idstring=$(this).parents("div[id^='infr_ref_btn']").attr("id");
				var i=idstring.substring(idstring.length,idstring.length-1);
				//alert (i);
			   if(referral_state_array[i]==0){
				alert('The referral stages functionality is not working. Please contact Health2me!');
				}else{
					if(referral_state_array[i]>=2)
						 displaynotification('status','This stage is already complete!');
					else if(referral_state_array[i]<1 && referral_state_array[i]!=-1){
						 alert('Previous Stages are not complete!');
						 
					}else if(referral_state_array[i]==1){
						var cadena='getReportInformationReview.php?referralid='+referral_id_array[i];
						var status=LanzaAjax(cadena);
						var conf='false';
						if(status=='true'){
						 conf='true';
						}
						else {
						 conf=confirm("All the report information has not been reveiwed for this patient. Do you still want to confirm review stage?");
						}
						//var conf='true';
						if(conf){
							$("div#infr_ref"+i).css("color","#3d93e0");
							$("div#infr_ref"+i).children("*").css("color","#3d93e0");
							$(this).css("color","#3d93e0");
							
							var cadena='setReferralStage.php?referralid='+referral_id_array[i]+'&stage=2';
							LanzaAjax(cadena);
							referral_state_array[i]=2;
							$("div#infr_ref_btn"+i).append('<i style="margin-left:10px;font-size:15px" class="icon-arrow-right"></i>');
							var cadena='push_server.php?message="Referral stage information review is completed"&NotifType=2&IdUsu=<?php echo $IdUsu;?>&FromDoctorId=<?php echo $IdMed;?>&channel='+otherdocid[i];
							var RecTipo=LanzaAjax(cadena);
							var content="Referal stage information review is completed. This is a System Generated Message";
							var subject="Referral stage information";
							var reportids=0;
							var cadena='sendMessage.php?sender=<?php echo $MedID;?>&receiver='+otherdocid[i]+'&patient=<?php echo $USERID;?>&content='+content+'&subject='+subject+'&attachments='+reportids+'&connection_id='+referral_id_array[i];
							var RecTipo=LanzaAjax(cadena);
							displaynotification('status','Referal stage information review is completed');
							//update referral table.
						}
					 }
				}
			});
		   
		    var hassentmessage=0;
			
		    $("div[id^='cmnt_ref_btn'] a").live('click',function(){
				var idstring=$(this).parents("div[id^='cmnt_ref_btn']").attr("id");
				var i=idstring.substring(idstring.length,idstring.length-1);
				//alert (i);
				if(referral_state_array[i]==0){
				alert('The referral stages functionality is not working. Please contact Health2me!');
				}else{
			
				if(referral_state_array[i]==3)
					 displaynotification('status','This stage is already complete!');
				
				if(referral_state_array[i]<2 && referral_state_array[i]!=-1)
					 alert('Previous Stages are not complete!');
					
				else if(referral_state_array[i]==2){
					if(hassentmessage==0){
						 alert('Stage cannot be marked complete as there has not been any communication. Please send atleast one message to complete this stage!');
					}else {
						$("div#cmnt_ref"+i).css("color","#3d93e0");
						$("div#cmnt_ref"+i).children("*").css("color","#3d93e0");
						$(this).css("color","#3d93e0");
						var cadena='setReferralStage.php?referralid='+referral_id_array[i]+'&stage=3';
						LanzaAjax(cadena);
						referral_state_array[i]=3;
						
						displaynotification('status','Referral stages for this patient completed!');
						var cadena='push_server.php?message="Referral stages has been completed"&NotifType=2&IdUsu=<?php echo $IdUsu;?>&FromDoctorId=<?php echo $IdMed;?>&channel='+otherdocid[i];
						var RecTipo=LanzaAjax(cadena);
						var content="Referal stage Comment is completed. This is a System Generated Message";
						var subject="Referral stage information";
						var reportids=0;
						var cadena='sendMessage.php?sender=<?php echo $MedID;?>&receiver='+otherdocid[i]+'&patient=<?php echo $USERID;?>'+'&content='+content+'&subject='+subject+'&attachments='+reportids+'&connection_id='+referral_id_array[i];
						var RecTipo=LanzaAjax(cadena);
						//update referral table.
					}
				}
			}
			
			});
			
			//Added for the new referral type-end
			
			$("div[id^='app_btn'] a").live('click',function(){
				var idstring=$(this).parents("div[id^='app_btn']").attr("id");
				var i=idstring.substring(idstring.length,idstring.length-1);
				//alert (i);
			if(referral_state_array[i]==0){
			alert('The referral stages functionality is not working. Please contact Health2me!');
			}else{
				if(referral_state_array[i]>=2)
					 displaynotification('status','This stage is already complete!');
				
					
				else if(referral_state_array[i]==1){
					var conf=confirm("Health2me couldn't find any appointments for this patient. Do you confirm that patient meeting has happened?");
					if(conf){
						$("div#app"+i).css("color","#3d93e0");
						$("div#app"+i).children("*").css("color","#3d93e0");
						$(this).css("color","#3d93e0");
						
						var cadena='setReferralStage.php?referralid='+referral_id_array[i]+'&stage=2';
						LanzaAjax(cadena);
						referral_state_array[i]=2;
						$("div#app_btn"+i).append('<i style="margin-left:10px;font-size:15px" class="icon-arrow-right"></i>');
						var cadena='push_server.php?message="Referral stage appointment is completed"&NotifType=2&IdUsu=<?php echo $IdUsu;?>&FromDoctorId=<?php echo $IdMed;?>&channel='+otherdocid[i];
						var RecTipo=LanzaAjax(cadena);
						
						var content="referal stage appointment is completed. This is a System Generated Message";
						var subject="Referral stage information";
						var reportids=0;
						var cadena='sendMessage.php?sender=<?php echo $MedID;?>&receiver='+otherdocid[i]+'&patient=<?php echo $USERID;?>'+'&content='+content+'&subject='+subject+'&attachments='+reportids+'&connection_id='+referral_id_array[i];
						var RecTipo=LanzaAjax(cadena);
						
						//update referral table.
					}
				 }
			 }
		   });
		   
		   $("div[id^='infr_btn'] a").live('click',function(){
				var idstring=$(this).parents("div[id^='infr_btn']").attr("id");
				var i=idstring.substring(idstring.length,idstring.length-1);
				//alert (i);
			   if(referral_state_array[i]==0){
				alert('The referral stages functionality is not working. Please contact Health2me!');
				}else{
					if(referral_state_array[i]>=3)
						 displaynotification('status','This stage is already complete!');
					else if(referral_state_array[i]<2 && referral_state_array[i]!=-1){
						 alert('Previous Stages are not complete!');
						 
					}else if(referral_state_array[i]==2){
						var cadena='getReportInformationReview.php?referralid='+referral_id_array[i];
						var status=LanzaAjax(cadena);
						var conf='false';
						if(status=='true'){
						 conf='true';
						}
						else {
						 conf=confirm("All the report information has not been reveiwed for this patient. Do you still want to confirm review stage?");
						}
						//var conf='true';
						if(conf){
							$("div#infr"+i).css("color","#3d93e0");
							$("div#infr"+i).children("*").css("color","#3d93e0");
							$(this).css("color","#3d93e0");
							
							var cadena='setReferralStage.php?referralid='+referral_id_array[i]+'&stage=3';
							LanzaAjax(cadena);
							referral_state_array[i]=3;
							$("div#infr_btn"+i).append('<i style="margin-left:10px;font-size:15px" class="icon-arrow-right"></i>');
							var cadena='push_server.php?message="Referral stage information review is completed"&NotifType=2&IdUsu=<?php echo $IdUsu;?>&FromDoctorId=<?php echo $IdMed;?>&channel='+otherdocid[i];
							var RecTipo=LanzaAjax(cadena);
							var content="Referal stage information review is completed. This is a System Generated Message";
							var subject="Referral stage information";
							var reportids=0;
							var cadena='sendMessage.php?sender=<?php echo $MedID;?>&receiver='+otherdocid[i]+'&patient=<?php echo $USERID;?>&content='+content+'&subject='+subject+'&attachments='+reportids+'&connection_id='+referral_id_array[i];
							var RecTipo=LanzaAjax(cadena);
							displaynotification('status','Referal stage information review is completed');
							//update referral table.
						}
					 }
				}
		   });
		   
		  
		   
		   $("[id^='newinbox']").live('click',function(){
			  
				
			  // $('#datatable_3_'+i).load(queUrl);
			  /* alert(queUrl);
			   $("#datatable_3_"+i).load(queUrl, function( response, status, xhr ) {  
					if ( status == "error" ) { alert('Error loading file'); }
					//if (status == "success") {
					alert (response);

				});*/
				var idstring=$(this).attr("id");
				var i=idstring.substring(idstring.length,idstring.length-1);
				whichdocidclicked=i;
				//alert (i);
				var queUrl ='<?php echo $domain;?>/getInboxMessage.php?IdMED=<?php echo $MedID;?>&patient=<?php echo $USERID;?>&sendingdoc='+otherdocid[i];
				var RecTipo=LanzaAjax(queUrl);
				//alert (RecTipo);
				$("#datatable_3_"+i).html(RecTipo);
			    $("#datatable_3_"+i).trigger('update');
			 //alert('triggerend');   
		   });
		   
		  $("[id^='newoutbox']").live('click',function(){
			  //alert('trigger');
				var idstring=$(this).attr("id");
				var i=idstring.substring(idstring.length,idstring.length-1);
				whichdocidclicked=i;
				//alert (i);
			 var queUrl ='<?php echo $domain;?>/getOutboxMessage.php?IdMED=<?php echo $MedID;?>&patient=<?php echo $USERID;?>&receivingdoc='+otherdocid[i];
			   $('#datatable_4_'+i).load(queUrl);
			   $('#datatable_4_'+i).trigger('update');
				   
		   }); 
		   
		   
		setTimeout(function(){
			$("[id^='newinbox']").trigger('click');},1000);
		   /*setTimeout(function(){
				$("[id^='newinbox1']").trigger('click');},1000);*/
		   
		var whichdocidclicked=0;
		
		/*function setWhichDoctorClicked(in){
			whichdocidclicked=in;
		}*/
		
	$("[id^='referraldoctab']").live('click',function(){
	
		var idstring=$(this).attr("id");
		var i=idstring.substring(idstring.length,idstring.length-1);
		//This variable sets the global parameter to the doctor
										
		whichdocidclicked=i;
	
	});
		//Changes added for multireferral
 	$("[id^='compose_message']").live('click',function(){
		
		var idstring=$(this).attr("id");
		var i=idstring.substring(idstring.length,idstring.length-1);
		//This variable sets the global parameter to the doctor
										
		whichdocidclicked=i;
		//alert("In client "+whichdocidclicked);
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
   
   var reportids='';
   var reportcheck = new Array();
   
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
	   $('#newinbox'+whichdocidclicked).trigger('click');},1000);
	   
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
   
   $("#Reply").live('click',function(){
   
	   var subject_name='RE:'+($('#subjectname_inbox').val()).replace(/RE:/,'');
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
       //alert ('clicked');
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
	$("#attachments").empty();
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
	
	 //alert('sending multireferral message'+ whichdocidclicked);
	 var sel=$('#doctorsdetails').find(":selected").attr('id');
	 var content=$('#messagecontent').val().replace(/ /g,"sp0e").replace(/\r\n|\r|\n/g,"br8k");
	 //boxText.replace(/<br\s?\/?>/g,"\n");
	 var subject=$('#subjectname').val();
	 if(subject==''||content=='')
	  displaynotification('Error','Error sending message.Empty subject or content area!');
	 else{
	 var cadena='sendMessage.php?sender=<?php echo $MedID;?>&receiver='+otherdocid[whichdocidclicked]+'&patient=<?php echo $USERID;?>'+'&content='+content+'&subject='+subject;
	 var RecTipo=LanzaAjax(cadena);
	 //alert(RecTipo);
	 $('#messagecontent').attr('value','');
	 $('#subjectname').attr('value','');
	 displaynotification('status',RecTipo);
	 //$('#add-regular').trigger('click');
	 var cadena='push_server.php?message="message from a doctor"&NotifType=1&IdUsu=<?php echo $IdUsu;?>&FromDoctorId=<?php echo $IdMed;?>&channel='+otherdocid[whichdocidclicked];
	 var RecTipo=LanzaAjax(cadena);
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
	 //Added for updating comment stage for new referrral type
	 if(referral_type_array[whichdocidclicked]==1){
		if(referral_state_array[whichdocidclicked]==2)
			hassentmessage=1;
	 }
	 var cadena='sendMessage.php?sender=<?php echo $MedID;?>&receiver='+otherdocid[whichdocidclicked]+'&patient=<?php echo $USERID;?>'+'&content='+content+'&subject='+subject+'&attachments='+reportids+'&connection_id=<?php echo $referral_id;?>';
	 var RecTipo=LanzaAjax(cadena);
	 //alert(RecTipo);
	 $('#messagecontent_inbox').attr('value','');
	 $('#subjectname_inbox').attr('value','');
	 displaynotification('status',RecTipo);
	 //$('#add-regular').trigger('click');
	 var cadena='push_server.php?FromDoctorName=<?php echo $IdMEDName;?>&FromDoctorSurname=<?php echo $IdMEDSurname;?>&Patientname=<?php echo $MedUserName; ?>&PatientSurname=<?php echo $MedUserSurname; ?>&IdUsu=<?php echo $USERID;?>&message= New Message <br>From: Dr. <?php echo $IdMEDName;?> <?php echo $IdMEDSurname;?><br>Subject: '+(subject).replace(/RE:/,'')+'&NotifType=1&IdUsu=<?php echo $IdUsu;?>&FromDoctorId=<?php echo $IdMed;?>&channel='+otherdocid[whichdocidclicked];
	 //alert(cadena);
	 var RecTipo=LanzaAjax(cadena);
	 //}
	 reportids='';
	 $("#attachment_icon").remove();
	 $('#message_modal').trigger('click');
  }); 
  
 
  $("[id^='delete_message']").live('click',function(){
  
	   var idstring=$(this).attr("id");
	   var i=idstring.substring(idstring.length,idstring.length-1);
	   setWhichDoctorClicked=i;
	   //alert (i);
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
	   $('#newinbox'+setWhichDoctorClicked).trigger('click');},50);
	displaynotification('status','Seleted Messages Deleted!');
	}else{
	displaynotification('status','No Messages Selected!');
	}
	}
	
   }); 
	
	
	
	
	
	
	
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
   
    $("div[id^='notif_']").live('click',function(){
   //alert('clicked closed'+$(this).parents("div[id^='notif_']").attr("id"));
   //$("div.ichat").show();
   
   var str=$(this).attr("id");
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
   
   var cadena ='<?php echo $domain;?>/getNotificationCount.php?IdMED=<?php echo $MedID;?>';
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
   
   
   
  /*      
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
		var cadena='push_server.php?message="Referral stages has been completed"&NotifType=2&IdUsu=<?php echo $IdUsu;?>&FromDoctorId=<?php echo $IdMed;?>&channel='+<?php echo $otherdoc?>;
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
				var cadena='push_server.php?message="Referral stage appointment is completed"&NotifType=2&IdUsu=<?php echo $IdUsu;?>&FromDoctorId=<?php echo $IdMed;?>&channel='+<?php echo $otherdoc?>;
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
			var cadena='getReportInformationReview.php?referralid=<?php echo $referral_id; ?>';
			var status=LanzaAjax(cadena);
			var conf='false';
			if(status=='true'){
			 conf='true';
			}
			else {
			 conf=confirm("All the report information has not been reveiwed for this patient. Do you still want to confirm review stage?");
			}
			//var conf='true';
			if(conf){
				$("div#infr").css("color","#3d93e0");
				$("div#infr").children("*").css("color","#3d93e0");
				$(this).css("color","#3d93e0");
				
				var cadena='setReferralStage.php?referralid=<?php echo $referral_id;?>&stage=3';
				LanzaAjax(cadena);
				referral_state=3;
				$("div#infr_btn").append('<i style="margin-left:10px;font-size:15px" class="icon-arrow-right"></i>');
				var cadena='push_server.php?message="Referral stage information review is completed"&NotifType=2&IdUsu=<?php echo $IdUsu;?>&FromDoctorId=<?php echo $IdMed;?>&channel='+<?php echo $otherdoc?>;
				var RecTipo=LanzaAjax(cadena);
				var content="Referal stage information review is completed. This is a System Generated Message";
				var subject="Referral stage information";
				var reportids=0;
				var cadena='sendMessage.php?sender=<?php echo $MedID;?>&receiver=<?php echo $otherdoc;?>&patient=<?php echo $USERID;?>'+'&content='+content+'&subject='+subject+'&attachments='+reportids+'&connection_id=<?php echo $referral_id;?>';
				var RecTipo=LanzaAjax(cadena);
				displaynotification('status','Referal stage information review is completed');
				//update referral table.
			}
		 }
	}
   });
   
   $('#newinbox').live('click',function(){
      //alert('trigger');
	 var queUrl ='<?php echo $domain;?>/getInboxMessage.php?IdMED=<?php echo $MedID;?>&patient=<?php echo $USERID;?>&sendingdoc=0';
	   $('#datatable_3').load(queUrl);
	   $('#datatable_3').trigger('update');
           
   });
   
   $('#newoutbox').live('click',function(){
      //alert('trigger');
	 var queUrl ='<?php echo $domain;?>/getOutboxMessage.php?IdMED=<?php echo $MedID;?>&patient=<?php echo $USERID;?>&receivingdoc=0';
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
/*		$("#attachments").show();
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
/*		$("#attachments").show();
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
/*   var subject_name='RE:'+($('#subjectname_inbox').val()).replace(/RE:/,'');
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
       alert ('clicked');
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
/*				}
			
				
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
	 var cadena='push_server.php?message="message from a doctor"&NotifType=1&IdUsu=<?php echo $IdUsu;?>&FromDoctorId=<?php echo $IdMed;?>&channel='+<?php echo $otherdoc?>;
	 var RecTipo=LanzaAjax(cadena);
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
/*	 var cadena='sendMessage.php?sender=<?php echo $MedID;?>&receiver=<?php echo $otherdoc;?>&patient=<?php echo $USERID;?>'+'&content='+content+'&subject='+subject+'&attachments='+reportids+'&connection_id=<?php echo $referral_id;?>';
	 var RecTipo=LanzaAjax(cadena);
	 //alert(RecTipo);
	 $('#messagecontent_inbox').attr('value','');
	 $('#subjectname_inbox').attr('value','');
	 displaynotification('status',RecTipo);
	 //$('#add-regular').trigger('click');
	 var cadena='push_server.php?FromDoctorName=<?php echo $IdMEDName;?>&FromDoctorSurname=<?php echo $IdMEDSurname;?>&Patientname=<?php echo $MedUserName; ?>&PatientSurname=<?php echo $MedUserSurname; ?>&IdUsu=<?php echo $USERID;?>&message= New Message <br>From: Dr. <?php echo $IdMEDName;?> <?php echo $IdMEDSurname;?><br>Subject: '+(subject).replace(/RE:/,'')+'&NotifType=1&IdUsu=<?php echo $IdUsu;?>&FromDoctorId=<?php echo $IdMed;?>&channel='+<?php echo $otherdoc?>;
	 //alert(cadena);
	 var RecTipo=LanzaAjax(cadena);
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
   
 */ 
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
  
  function getScriptsAsText() {
   var div = document.createElement('div');
   var scripts = [];
   var scriptNodes = document.getElementsByTagName('script');
   for (var i=0, iLen=scriptNodes.length; i<iLen; i++) {
     div.appendChild(scriptNodes[i].cloneNode(true));
     scripts.push(div.innerHTML);
     div.removeChild(div.firstChild);
   }
   return scripts;
  }
  function highlightattachedreports(){
		//alert(multireferral_num+"andar");
			for (var j=0;j<multireferral_num;j++)
		{ 
				var attcolor=$('#referralcolor'+j).val();
				//alert(attcolor);
			   var reportstobereviewed=$('#reportid_review'+j).val();
			   //alert(reportstobereviewed);
			   var reportstobereviewed_ids=reportstobereviewed.split(" "); 
			   for (var i = 0, len = reportstobereviewed_ids.length; i < len; ++i)
			   {
				  if(reportstobereviewed_ids[i].length==0)
				  {
					continue;
				  }
				  //id^="reportcol"]'
				  $('i[id^="report-eye"]').each(function(){
				  
				  var id=parseInt($(this).parents("div.note2").attr('id'));
				  //alert(id);
				  if(id==parseInt(reportstobereviewed_ids[i]))
				  {//alert('highlighting '+id);
				   $(this).css("color","#000000");
				  // $(this).parents("div.note2").css({"border": "3px solid blue"});
					
				   $(this).parents("div.note2").css({"border": "2px solid"});
				   $(this).parents("div.note2").css({"border-radius": "7px"});
				   $(this).parents("div.note2").css({"outline": "none"});
				   $(this).parents("div.note2").css({"border-color": attcolor});
				   $(this).parents("div.note2").css({"box-shadow": "0 0 10px "+attcolor});
				  }
				  
				  
				  
				  });
			   
			   
			   } 	
		}
            
  }
  
  function displaynotification(status,message){
  
  var gritterOptions = {
			   title: status,
			   text: message,
			   image:'images/Icono_H2M.png',
			   sticky: false,
			   time: '6000'
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
		//$(".loader_spinner").fadeOut("slow");
		//$("#tabsWithStyle").show();
	//Commented- Start
		
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
	//Commented-stop
	
        //highlightattachedreports();
		var multireferral_num=parseInt(<?php echo $num_multireferral ?>);
		//alert(multireferral_num);
		/*for (var j=0;j<multireferral_num;j++)
		{ 
				var attcolor=$('#referralcolor'+j).val();
				//alert(attcolor);
			   var reportstobereviewed=$('#reportid_review'+j).val();
			   //alert(reportstobereviewed);
			   var reportstobereviewed_ids=reportstobereviewed.split(" "); 
			   //alert(reportstobereviewed_ids.length);
			   for (var i = 0, len = reportstobereviewed_ids.length; i < len; ++i)
			   {
				  if(reportstobereviewed_ids[i].length==0)
				  {
					continue;
				  }
				  //id^="reportcol"]'
				  $('i[id^="report-eye"]').each(function(){
				  
				  var id=parseInt($(this).parents("div.note2").attr('id'));
				  //alert(id);
				  if(id==parseInt(reportstobereviewed_ids[i]))
				  {
				   //alert('highlighting' + id);
				   $(this).css("color","#000000");
				  // $(this).parents("div.note2").css({"border": "3px solid blue"});
					//alert(attcolor);
				   $(this).parents("div.note2").css({"border": "2px solid"});
				   $(this).parents("div.note2").css({"border-radius": "7px"});
				   $(this).parents("div.note2").css({"outline": "none"});
				   $(this).parents("div.note2").css({"border-color": attcolor});
				   $(this).parents("div.note2").css({"box-shadow": "0 0 10px "+attcolor});
				   
				   //$(this).parents("div.note2").css({"border": "2px solid blue"});
				   //$(this).parents("div.note2").css({"border-radius": "7px"});
				   //$(this).parents("div.note2").css({"outline": "none"});
				   //$(this).parents("div.note2").css({"border-color": "blue"});
				   //$(this).parents("div.note2").css({"box-shadow": "0 0 10px blue"});
				  }
				  
				  
				  
				  });
			   
			   
			   } 	
		}*/
		
	
	}, 1000 );
	}
	});
	
	function progress() {
	var progressbarValue = progressbar.find( ".ui-progressbar-value" );
	var val = progressbar.progressbar( "value" ) || 0;
	progressbar.progressbar( "value", val + 10 );
	progressbar.css({"background": 'black'});
	progressbarValue.css({"background": '#4169E1'});    //#5882FA
	if ( val < 99 ) {
	setTimeout( progress, 50 );
	}
	} 
	setTimeout( progress, 1000 );
	
	//Commented
	
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
            var doctorId = -1;
            <?php if(isset($_SESSION['MEDID'])){echo "doctorId = ".$_SESSION['MEDID'].";";} ?>;
			contenURL =   '<?php echo $domain ;?>/temp/'+doctorId+'/Packages_Encrypted/'+file_name;
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
            var doctorId = -1;
            <?php if(isset($_SESSION['MEDID'])){echo "doctorId = ".$_SESSION['MEDID'].";";} ?>;
			contenURL =   '<?php echo $domain ;?>/temp/'+doctorId+'/Packages_Encrypted/'+file_name;
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
//BLOCKSLIFEPIN $result = mysql_query("SELECT * FROM blocks ORDER BY FechaInput DESC");

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
/*$sql1="SELECT Idpin,Tipo FROM lifepin where IdUsu ='$queUsu' and Tipo NOT IN (select Id from tipopin where Agrup=9) and IdMed IS NOT NULL and (IdMed NOT IN ((select distinct(idDoctor) from doctorsgroups where idGroup IN (select idGroup from doctorsgroups where idDoctor= '$MedID'))) 
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
	//$sql1="SELECT Idpin FROM lifepin where IdUsu ='$IdUsu' and IdMed IS NOT NULL and (IdMed NOT IN ((select distinct(idDoctor) from doctorsgroups where idGroup IN (select idGroup from doctorsgroups where idDoctor= '$IdMed'))) and IdMed NOT IN (Select idmed from doctorslinkdoctors where idmed2='$IdMed' and IdPac='$IdUsu'))";
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
		shell_exec('Decrypt.bat PackagesTH_Encrypted '.$ImageRaiz.'.'.$extension.' '.$queMed.' '.$pass);
		//echo "Thumbnail Generated";
	}


}

?>
