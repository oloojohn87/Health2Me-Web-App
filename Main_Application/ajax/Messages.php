<?php
session_start();
 require("environment_detail.php");
 require("PasswordHash.php");
 require_once("displayExitClass.php");
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];

include('userConstructClass.php');
$user = new userConstructClass();

if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 1800)) {
    // last request was more than 30 minutes ago
    session_unset();     // unset $_SESSION variable for the run-time 
    session_destroy();   // destroy session data in storage
    echo "<META http-equiv='refresh' content='0;URL=index.html'>";
}
$_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp

$name=empty($_POST['Nombre'])   ? $_SESSION['Nombre'] : $_POST['Nombre'];
$isDoctors = false;
if(isset($_GET['isDoctors']))
{
    $isDoctors = $_GET['isDoctors'];
}
$mes_id = -1;
if(isset($_GET['id']))
{
    $mes_id = $_GET['id'];
}


   

$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
{
    die('Could not connect: ' . mysql_error());
}		

//For checking multiple logins	
$query = $con->prepare("select * from ongoing_sessions where userid=?");
$query->bindValue(1, $_SESSION['MEDID'], PDO::PARAM_INT);
$result=$query->execute();

$count=$query->rowCount();
$ip = $_SERVER['REMOTE_ADDR'];	
if($count==0)
{
    //die("Here");
    $q = $con->prepare("insert into  ongoing_sessions values(?,now(),?)");
	$q->bindValue(1, $_SESSION['MEDID'], PDO::PARAM_INT);
	$q->bindValue(2, $ip, PDO::PARAM_STR);
	$q->execute();
    //$q = "insert into  session_log values(".$_SESSION['MEDID'].",'Login',now(),'".$ip."')";
    //echo $q;
    //mysql_query($q);
}
else
{
    $q = $con->prepare("select * from ongoing_sessions where userid=? and ip=?");
	$q->bindValue(1, $_SESSION['MEDID'], PDO::PARAM_INT);
	$q->bindValue(2, $ip, PDO::PARAM_STR);
	$res=$q->execute();
	
    //die($q);
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
        //$q = "insert into  session_log values(".$_SESSION['MEDID'].",'Login',now(),'".$ip."')";
        //mysql_query($q);
    }
}

$result = $con->prepare("select pass from encryption_pass where id = (select max(id) from encryption_pass)");
$result->execute();

$row = $result->rowCount();
$_SESSION['decrypt']=$row['pass'];

$NombreEnt = $_SESSION['Nombre'];
$MEDID = $_SESSION['MEDID'];
$BOTHID = $_SESSION['BOTHID'];
$Acceso = $_SESSION['Acceso'];
$privilege=$_SESSION['Previlege'];
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
    
   /* // Get information about the number of appointments for this user 
    $sql="SELECT * FROM events where userid=".$MEDID;
    $resultS = mysql_query($sql);
    $Pat_App_Week = 0;
    $Pat_App_NextWeek = 0;
    $Pat_App_Month = 0;
    while ($rowS = mysql_fetch_array($resultS))
    {
        $current_date = date("Y-m-d");
        $db_date = $rowS['start'];//date("Y-m-d");
        //echo '--'.date("W", strtotime($current_date)).' / '.date("W",strtotime($db_date));
        if (date("W",strtotime($db_date)) == date("W",strtotime($current_date)))
        {
            $Pat_App_Week ++;
        }
        if (date("W",strtotime($db_date)) == (1+date("W",strtotime($current_date))))
        {
            $Pat_App_NextWeek ++;
        }
        if (date("n",strtotime($db_date)) == date("n",strtotime($current_date)))
        {
            $Pat_App_Month ++;
        }
    }*/
    
    // Get Information about number of patients belonging to this Doctor (and reports)
    $Num_Own_Patients = 0;
    $Num_Group_Patients = 0;
    $Num_Own_Reports = 0;
    $Num_Group_Reports = 0;
    $IdMed = $MEDID;
    /*$resultPRE = mysql_query("SELECT distinct(IdUs) FROM doctorslinkusers WHERE IdPin IS NULL and (Idmed='$IdMed' or IdMED IN (Select distinct(idDoctor) from doctorsgroups where idGroup IN (select idGroup from doctorsgroups where idDoctor= '$IdMed'))) and Estado IN (2,null)");
	$Num_Group_Patients = mysql_num_rows($resultPRE);
    while ($rowS = mysql_fetch_array($resultPRE))
    {
        $User_Reports = 0;
        $IdUsu = $rowS['IdUs'];
        $resultREPORT = mysql_query("SELECT IdPin FROM lifepin WHERE IdUsu = '$IdUsu'");
	    $User_Reports = mysql_num_rows($resultREPORT);
        $Num_Group_Reports = $Num_Group_Reports + $User_Reports;
        //echo $Num_Group_Reports.' ****************';
    }
    $resultPRE = mysql_query("SELECT distinct(IdUs) FROM doctorslinkusers WHERE IdPin IS NULL and Idmed='$IdMed' and Estado IN (2,null)");
	$Num_Own_Patients = mysql_num_rows($resultPRE);
    while ($rowS = mysql_fetch_array($resultPRE))
    {
        $User_Reports = 0;
        $IdUsu = $rowS['IdUs'];
        $resultREPORT = mysql_query("SELECT IdPin FROM lifepin WHERE IdUsu = '$IdUsu'");
	    $User_Reports = mysql_num_rows($resultREPORT);
        $Num_Own_Reports = $Num_Own_Reports + $User_Reports;
       // echo $Num_Own_Reports.' ----------------';
    }
    */
    //Get Information about Referrals
    /*$result = mysql_query("SELECT id FROM doctorslinkdoctors WHERE IdMED = '$MEDID' ");
    $Number_Patients_IReferred = mysql_num_rows($result);
    $result = mysql_query("SELECT distinct(IdMED2) FROM doctorslinkdoctors WHERE IdMED = '$MEDID' ");
    $Number_Doctors_IReferred = mysql_num_rows($result);
    
    $result = mysql_query("SELECT id FROM doctorslinkdoctors WHERE IdMED2 = '$MEDID' ");
    $Number_Patients_Referred2Me = mysql_num_rows($result);
    $result = mysql_query("SELECT distinct(IdMED) FROM doctorslinkdoctors WHERE IdMED2 = '$MEDID' ");
    $Number_Doctors_Referred2Me = mysql_num_rows($result);
      
    //Get Statistics on Stages, Waiting time for referred patients
    $resultS2 = mysql_query("SELECT * FROM doctorslinkdoctors WHERE IdMED = '$MEDID' ");
    $Number_Patients_IReferred2 = 0;
    $Cum_TTV = 0;
    $current_date = date("Y-m-d");
    $Waiting_Patients = 0;
    while ($rowS = mysql_fetch_array($resultS2))
    {
        $Number_Patients_IReferred2++;
        $referral_id = $rowS['id'];
        $current_date = $rowS['Fecha'];
        $result2B = mysql_query("select stage,datevisit from referral_stage where referral_id='$referral_id'");
        $row2B = mysql_fetch_array($result2B);
        $referral_stage=$row2B['stage'];
        $db_date = $row2B['datevisit'];
        $diff = abs(strtotime($current_date) - strtotime($db_date));
        $daystovisit = floor ($diff /  (60*60*24));
        if ($daystovisit > 0) $Waiting_Patients++;
        $Cum_TTV = $Cum_TTV + $daystovisit;
    }
    if ($Waiting_Patients == 0) $Mean_TTV=0; else $Mean_TTV = $Cum_TTV / $Waiting_Patients;
    */   
	// Get Number of Messages from patients
	/*$result2 = mysql_query("SELECT * FROM message_infrastructureuser WHERE receiver_id = '$MEDID' AND status='new' ");
	$count2=mysql_num_rows($result2);
	$NumMessagesUser = $count2;
	if ($NumMessagesUser>0) {$VisibleUser = ''; $OpaUser='1';} else {$VisibleUser = 'hidden';$OpaUser='0.3';}
 	// Get Number of Messages from doctors
	$result2 = mysql_query("SELECT * FROM message_infrasture WHERE receiver_id = '$MEDID' AND status='new' ");
	$count2=mysql_num_rows($result2);
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
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Health2Me - MESSAGES</title>
    <link rel="icon" type="image/ico" href="favicon.ico"/>
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
    
   	<link rel="stylesheet" href="font-awesome/css/font-awesome.min.css">
	<link rel="stylesheet" href="css/H2MIcons.css" />
    <link rel="stylesheet" href="css/bootstrap-responsive.css">
	<link rel="stylesheet" href="css/toggle-switch.css">
	<link rel="stylesheet" href="css/csslider.css">
    <link rel="shortcut icon" href="/images/icons/favicon.ico">
    <link rel="stylesheet" type="text/css" href="css/tooltipster.css" />
    <link rel="stylesheet" type="text/css" href="css/mail_styles.css" />
    
     <?php
        if ($_SESSION['CustomLook']=="COL") { ?>
        <link href="css/styleCol.css" rel="stylesheet">
    <?php  }  ?>
    
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
    <link rel='stylesheet' href='css/bootstrap-dropdowns.css'>
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
</head>
<body>
    <input type="hidden" id="Message_id" value="<?php echo $mes_id; ?>">
    
    <style>
        table, caption, tbody, tfoot, thead, tr, th, td {
            background: transparent;
            border: 0;
            margin: 0;
            padding: 0;
            vertical-align: baseline;
        }
        div#mail { width:70%; margin:20px auto; }
            .mailinbox tbody tr td { background: #fafafa; }
            .mailinbox tbody tr.unread td { background: #fff; font-weight: bold; }
            .mailinbox tbody tr.selected td { background:#FFFFD2; }
            .mailinbox thead th, .mailinbox tfoot th { border: 1px solid #ccc; border-right: 0; }
            .mailinbox tfoot th { border-bottom: 1px solid #ccc !important; }
            .mailinbox a.title { font-weight: normal; text-decoration:none; }
            .mailinbox tbody tr.unread a.title { font-weight: bold; }
            .mailinbox td.star, .mailinbox td.attachment { text-align: center; }
            .msgstar { 
                display: inline-block; width: 16px; height: 16px; background: url(images/mail/unstar.png) no-repeat 0 0; 
                cursor: pointer; opacity: 0.5; 
            }
            .msgstar:hover { opacity: 1; }
            .starred { background-image: url(images/mail/star.png); opacity: 1; }



            .table-bordered caption + thead tr:first-child th:first-child, 
            .table-bordered caption + tbody tr:first-child td:first-child, 
            .table-bordered colgroup + thead tr:first-child th:first- child, 
            .table-bordered colgroup + tbody tr:first-child td:first-child { border-top-left-radius: 0; }

            .table-bordered caption + thead tr:first-child th:last-child, 
            .table-bordered caption + tbody tr:first-child td:last-child, 
            .table-bordered colgroup + thead tr:first-child th:last-child, 
            .table-bordered colgroup + tbody tr:first-child td:last-child { border-top-right-radius: 0; }

            .table-bordered thead:first-child tr:first-child th:first-child, 
            .table-bordered tbody:first-child tr:first-child td:first-child,
            .table-bordered thead:first-child tr:first-child th:last-child, 
            .table-bordered tbody:first-child tr:first-child td:last-child,
            .table-bordered thead:last-child tr:last-child th:first-child, 
            .table-bordered tbody:last-child tr:last-child td:first-child, 
            .table-bordered tfoot:last-child tr:last-child td:first-child { -moz-border-radius: 0; -webkit-border-radius: 0; border-radius: 0; }


            .table { margin-bottom: 0; width:100%; font-size:14px }
            .table th { background: #fcfcfc; }
            .table tfoot th { border-bottom: 1px solid #ddd; }
            .table th.aligncenter, .table td.aligncenter { text-align: center; }
            .table tr { padding:5px; height:28px}
            table td.center, table th.center { text-align: center; }

            .clearall { clear: both; }

            .mailinbox thead th, .mailinbox tfoot th {
                background: rgb(237,237,237);
                background: -moz-linear-gradient(top, rgba(237,237,237,1) 0%, rgba(222,222,222,1) 100%);
                background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,rgba(237,237,237,1)), color-stop(100%,rgba(222,222,222,1)));
                background: -webkit-linear-gradient(top, rgba(237,237,237,1) 0%,rgba(222,222,222,1) 100%);
                background: -o-linear-gradient(top, rgba(237,237,237,1) 0%,rgba(222,222,222,1) 100%);
                background: -ms-linear-gradient(top, rgba(237,237,237,1) 0%,rgba(222,222,222,1) 100%);
                background: linear-gradient(to bottom, rgba(237,237,237,1) 0%,rgba(222,222,222,1) 100%);
                filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#ededed', endColorstr='#dedede',GradientType=0 );
            }

</style>
        
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
        .search_users_bar_button{
            width: 70px;
            height: 30px;
            background-color: #FAFAFA;
            outline: 0px;
            border: 1px solid #E7E7E7;
            color: #7A7A7A;
            border-top-right-radius: 5px;
            border-bottom-right-radius: 5px;
        }
    </style>

    <div id="message_modal" title="Message Details" style="display: none; width: 1000px; height: 500px;">
         <div style="width: 100%; height: 410px; margin-top: 10px;">
             <div id="read_message">
                <p><strong><span id="name_field" >From</span>:  </strong><span id="messagedetail_from"></span></p>
                <p><strong>Subject:  </strong><span id="messagedetail_subject"></span></p>
                <textarea id="messagedetails" class="span message-text" style="height: 300px; cursor: pointer;" name="message" rows="1" readonly="readonly"></textarea>
            </div>
             
             <div id="reply_message" style="display: none; margin-top: 10px;">
                   <div class="formRow">
                        <label>Subject: </label>
                        <div class="formRight">
                            <input type="text" id="reply_subject" name="name"  class="span"> 
                        </div>
                   </div>
				   <!--<div class="formRow">
						<label>Attachments: </label>
						<div id="attachreportdiv" class="formRight">
							<input type="button" class="btn btn-success" value="Attach Reports" id="attachreports">
						</div>
				   </div>-->
                   <div class="formRow">
                        <label>Message:</label>
                        <div class="formRight tooltip-top" style="height:120px;">
                            <textarea  id="reply_content" class="span message-text" name="message" style="height:340px;" rows="1"></textarea>
                            
                            <div class="clear"></div>
                        </div>
                   </div>
             </div>
        
            <div id="attachments" style="display: none; height: 400px; width: 100%;">
             
             </div>
         </div>
         <input type="hidden" id="Idpin">
         <input type="hidden" id="userId" value="1485">
         <div style="width: 100%; height: 30px;">	 
             <input type="button" class="btn btn-success" style="float: right; margin-left: 8px;" value="Reply" id="ReplyButton" >
             <input type="button" class="btn btn-success" style="float: right; margin-left: 8px;" value="Send" id="SendButton" style="display: none;">
             <input type="button" class="btn btn-danger" style="float: right; margin-left: 8px;" value="Cancel" id="CancelButton" style="display: none;">
             <!--<input type="button" class="btn btn-primary" style="float: right; margin-left: 8px;" value="Print" id="PrintButton" style="display: none;">-->
             <input type="button" class="btn btn-success" value="View Attachments" id="AttachmentButton" style="display: none; float: right; margin-left: 8px;">
             <input type="button" class="btn btn-info" value="Send message" id="sendmessages_inbox" style="display: none; float: right; margin-left: 8px;">
         </div>
    </div>
    
    <div id="compose_modal" class="grid" class="grid span4" title="Compose New Message" style="display: none; width: 1000px; height: 500px;">
        <div style="float: left; width: 400px; height: 400px; margin: 0px; border-right: 1px solid #F5F5F5;">
            <div class="controls" style="margin-left: 10px; margin-top: 10px; width: 400px; margin-bottom: 20px;">
                <input class="span7" id="search_users_bar" style="float: left; width: 270px; border-top-right-radius: 0px; border-bottom-right-radius: 0px;" placeholder="Search User (Name or Email)" size="16" type="text">
                <button class="search_users_bar_button" style="float: left;" id="search_users_bar_button" lang="en">Search</button>
            </div>
            <div id="search_users_results" style="width: 340px; height: 350px; margin-top: 50px; margin-left: 10px; overflow-y: scroll;">
            </div>
        </div>
        <div style="float: left; width: 500px; height: 380px; margin-left: 40px;">
            <strong>To: </strong><span id="compose_message_recipient_label"></span><br/><br/>
            <strong>Subject: </strong><br/><input type="text" id="compose_message_subject" style="width: 500px; margin-top: 5px;" /><br/>
            <strong>Message:</strong><br/>
            <textarea id="compose_message_content" style="width: 500px; height: 200px; margin-top: 15px;"></textarea>
        </div>
        <div id="compose_attachments" style="margin-left: 2%; width: 96%; height: 380px; display: none;">
            <div style="width: 100%; height: 30px;">
                <div style="width: 14%; height: 30px; margin-top: 4px; float: left; color: #666">Select Member:</div>
                <select id="compose_select_member" style="width: 58%; float: left;">

                </select>
                <button style="width: 12%; float: left; margin-left: 2%; height: 30px; border: 0px solid #FFF; border-radius: 5px; outline: none; background-color: #22AEFF; color: #FFF;">Select All</button>
                <button style="width: 12%; float: left; margin-left: 2%; height: 30px; border: 0px solid #FFF; border-radius: 5px; outline: none; background-color: #D84840; color: #FFF;">Deselect All</button>
            </div>
            <div id="compose_attachments_container" style="width: 100%; height: 300px; margin-top: 15px; background-color: #333;">
            
            </div>
        </div>
        <div style="width: 100%; height: 30px;">	 
            <input type="button" class="btn btn-success" style="float: right; margin-left: 8px; margin-right: 18px;" value="Send" id="ComposeSendButton" style="display: none;">
            <!--<input type="button" class="btn btn-success" value="Attachments" id="ComposeAttachmentButton" style="display: none; float: right; margin-left: 8px;">-->
            <input type="button" class="btn btn-success" value="Message" id="ComposeMessageButton" style="display: none; float: right; margin-left: 8px; margin-right: 18px;">
            <input type="button" class="btn btn-danger" style="float: right; margin-left: 8px;" value="Cancel" id="ComposeCancelButton" style="display: none;">
         </div>
    </div>
    
    <div id="attachment" title="View Attachment" style="display: none; height: 900px; width: 700px;">
        <iframe id="attachment_frame" src="index.html" style="border: 0px solid #FFF; width: 100%; height: 830px; margin-top: 10px;"></iframe>
    </div>
    
    
    

    
    <div class="loader_spinner"></div>

    <input type="hidden" id="NombreEnt" value="<?php echo $NombreEnt; ?>">
    <input type="hidden" id="PasswordEnt" value="<?php echo $PasswordEnt; ?>">
    <input type="hidden" id="UserHidden">

	<!-- HEADER VIEW FOR MAIN TOOLBAR -->
	<div class="header" >
     	<input type="hidden" id="USERDID" Value="<?php echo $USERID; ?>">	
    	<input type="hidden" id="MEDID" Value="<?php echo $MedID; ?>">	
        <input type="hidden" id="BOTHID" Value="<?php echo $BOTHID; ?>">	
    	<input type="hidden" id="GROUPID" Value="<?php echo $MedGroupId; ?>">	
    	<input type="hidden" id="IdMEDEmail" Value="<?php echo $MedUserEmail; ?>">	
    	<input type="hidden" id="IdMEDName" Value="<?php echo $MedUserName; ?>">	
    	<input type="hidden" id="IdMEDSurname" Value="<?php echo $MedUserSurname; ?>">	
    	<input type="hidden" id="IdMEDLogo" Value="<?php echo $MedUserLogo; ?>">	
  		
        <a href="index.html" class="logo"><h1>Health2me</h1></a>
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
        <div class="pull-right">
            
            
            <?php include 'message_center.php'; ?>
            <!--Button User Start-->
            <div class="btn-group pull-right" >
            <a class="btn btn-profile dropdown-toggle" id="button-profile" data-toggle="dropdown" href="#">
                <span class="name-user"><strong>Welcome</strong> Dr. <?php echo $MedUserName.' '.$MedUserSurname; ?></span> 
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
                        <li>
                            <?php 
                                if ($privilege==1) echo '<a href="MainDashboard.php">';
                                else if($privilege==2) echo '<a href="patients.php">';
                            ?>
                        <i class="icon-globe"></i> Home</a></li>
                    
                        <li><a href="medicalConfiguration.php"><i class="icon-cog"></i> Settings</a></li>
                        <li><a href="logout.php"><i class="icon-off"></i> Sign Out</a></li>
                    </ul>
                </div>
            </div>
            <!--Button User END-->  
          
        </div>
    </div>
    <!-- END HEADER VIEW FOR MAIN TOOLBAR -->
    
    
    <div id="content" style="padding-left:0px;">

    <!-- Button trigger modal -->
    <button id="LaunchModal" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#myModal" style="display:none;">Launch demo modal</button>
     	    
	 <!-- MAIN TOOLBAR -->
     <div class="speedbar">
        <div class="speedbar-content" style="position:relative;">
            <ul class="menu-speedbar">
                <li><a href="MainDashboard.php">Home</a></li>
                <!--li><a href="patients.php" >Members</a></li-->
                <?php 
                    if ($privilege==1)
                    {
                        echo '<li><a href="medicalConnections.php" >Doctor Connections</a></li>';
                        echo '<li><a href="PatientNetwork.php" >Member Network</a></li>';
                    }
                ?>
                <li><a href="medicalConfiguration.php">Configuration</a></li>
                <li><a href="logout.php" style="color:yellow;">Sign Out</a></li>
            </ul>

     
        </div>
    </div>
    <!-- END MAIN TOOLBAR -->
    
    
    <input type="hidden" id="Privilege" value="<?php echo $privilege; ?>">
        
    <!-- MAIN CONTENT -->
     <div class="content">
        <!-- MAIN GRID -->
        <div class="grid" class="grid span4" style="width:1000px; margin: 0 auto; margin-top:30px; padding-top:30px;">
            <div class="label label-info" id="EtiTML" style="background-color:#22aeff; margin:10px; margin-left:35px; margin-top: -10px; font-size:24px; text-shadow:none; text-decoration:none; text-align: center; padding: 15px; width: 90%; border-radius: 5px;">Messages</div>
            <div class="controls" style="margin-left: 430px; margin-top: 15px; margin-bottom: -50px;">
                <div class="input-append">
                    <input class="span7" id="search_bar" style="width: 300px;" size="16" type="text"><button class="btn" type="button" id="search_bar_button">Search</button>
                </div>
            </div>
            <div style="margin-top: 18px; margin-bottom: -18px;">
                <label class="checkbox toggle candy" onclick="" style="width:150px; float:right; margin-right: 35px; margin-bottom: 10px; background-color: #2d3035;">
                    <input type="checkbox" id="Type_toggle" name="CRows" <?php if($isDoctors){echo 'checked="checked"';} ?>  />
                    <p >
                        <span>Doctors</span>
                        <span>Patients</span>
                    </p>
        
                    <a class="slide-button" style="background-color: #38a3d4;" ></a>
                </label>
            </div>
        
        
            <div id="mail" style="width: 93%">

                <ul class="grouped">
                    <!--<li style="margin-right:20px;"><a href="#" class="bttn blue">COMPOSE</a></li>-->
                    <li><a id="compose_button" href="#" class="bttn icon add">Compose</a></li>
                    <li><a id="refresh_button" href="#" class="bttn icon refresh">Refresh</a></li>
                    <li><a id="mark_unread_button" href="#" class="bttn mark_unread">Mark as Unread</a></li>
                    <li><a id="change_type_button" href="#" class="bttn">View Sent</a></li>
                    <li><a id="delete_button" href="#" class="bttn icon delete">Delete</a></li>
                </ul>
                
    
                
                <table class="table table-bordered mailinbox" style="border-collapse:collapse; margin-top:10px;" border="0" cellpadding="5" cellspacing="3">
    
                    <thead>
                    <tr>
                        <th class="head1 aligncenter" style="width: 6%">&nbsp;</th>
                        <th id="name_column" class="head1" style="width: 24%">From</th>
                        <th class="head0" style="width: 44%">Subject</th>
                        <th class="head1 attachement" style="width: 6%">&nbsp;</th>
                        <th class="head1 attachement" style="width: 6%">&nbsp;</th>
                        <th class="head0" style="width: 14%">Date</th>
                    </tr>
                    </thead>
                    <tbody id="messages_body">
                        <!--<tr class="unread">
                            <td class="aligncenter"><input type="checkbox" class="checkbox" name="" /></td>
                            <td class="star"><a class="msgstar"></a></td>
                            <td>Itzurkarthi</td>
                            <td><a href="" class="title">Facebook Style YouTube Video Expanding with jQuery</a></td>
                            <td class="attachment"><img src="images/mail/attachment.png" alt="" /></td>
                            <td class="date">May 1</td>
                        </tr>
                        <tr class="unread">
                            <td class="aligncenter"><input type="checkbox" class="checkbox" name="" /></td>
                            <td class="star"><a class="msgstar"></a></td>
                            <td>Facebook</td>
                            <td><a href="" class="title">Facebook Wall Script 3.0</a></td>
                            <td class="attachment"></td>
                            <td class="date">Apr 30</td>
                        </tr>
                        <tr>
                            <td class="aligncenter"><input type="checkbox" class="checkbox" name="" /></td>
                            <td class="star"><a class="msgstar"></a></td>
                            <td>Twitter</td>
                            <td><a href="" class="title">Animated Login Form with jQuery & CSS3</a></td>
                            <td class="attachment"></td>
                            <td class="date">Apr 28</td>
                        </tr>
                        <tr>
                            <td class="aligncenter"><input type="checkbox" class="checkbox" name="" /></td>
                            <td class="star"><a class="msgstar"></a></td>
                            <td>PHP</td>
                            <td><a href="" class="title">Validate IPv4 Address in PHP</a></td>
                            <td class="attachment"><img src="images/mail/attachment.png" alt="" /></td>
                            <td class="date">Apr 20</td>
                        </tr>
                        <tr>
                            <td class="aligncenter"><input type="checkbox" class="checkbox" name="" /></td>
                            <td class="star"><a class="msgstar starred"></a></td>
                            <td>Itzurkarthi</td>
                            <td><a href="" class="title">Identifying Browser Window Screen Size & Name with JQuery</a></td>
                            <td class="attachment"></td>
                            <td class="date">June 19</td>
                        </tr>
                              <tr class="unread">
                            <td class="aligncenter"><input type="checkbox" class="checkbox" name="" /></td>
                            <td class="star"><a class="msgstar"></a></td>
                            <td>Itzurkarthi</td>
                            <td><a href="" class="title">Facebook Style YouTube Video Expanding with jQuery</a></td>
                            <td class="attachment"><img src="images/mail/attachment.png" alt="" /></td>
                            <td class="date">May 1</td>
                        </tr>
                        <tr class="unread">
                            <td class="aligncenter"><input type="checkbox" class="checkbox" name="" /></td>
                            <td class="star"><a class="msgstar"></a></td>
                            <td>Facebook</td>
                            <td><a href="" class="title">Facebook Wall Script 3.0</a></td>
                            <td class="attachment"></td>
                            <td class="date">Apr 30</td>
                        </tr>
                        <tr>
                            <td class="aligncenter"><input type="checkbox" class="checkbox" name="te" value="te" /></td>
                            <td class="star"><a class="msgstar"></a></td>
                            <td>Twitter</td>
                            <td><a href="" class="title">Animated Login Form with jQuery & CSS3</a></td>
                            <td class="attachment"></td>
                            <td class="date">Apr 28</td>
                        </tr>
                        <tr>
                            <td class="aligncenter"><input type="checkbox" class="checkbox" name="" /></td>
                            <td class="star"><a class="msgstar"></a></td>
                            <td>PHP</td>
                            <td><a href="" class="title">Validate IPv4 Address in PHP</a></td>
                            <td class="attachment"><img src="images/mail/attachment.png" alt="" /></td>
                            <td class="date">Apr 20</td>
                        </tr>
                        <tr>
                            <td class="aligncenter"><input type="checkbox" class="checkbox" name="" /></td>
                            <td class="star"><a class="msgstar starred"></a></td>
                            <td>Itzurkarthi</td>
                            <td><a href="" class="title">Identifying Browser Window Screen Size & Name with JQuery</a></td>
                            <td class="attachment"></td>
                            <td class="date">June 19</td>
                        </tr>-->
    
                    </tbody>
                </table>
                <style>
                    #page a:link{
                        color: #22aeff;
                        text-decoration: none;
                    }
                    #page a:hover{
                        color: #1691d3;
                        text-decoration: none;
                    }
                    
                    #page a:visited{
                        color: #22aeff;
                        text-decoration: none;
                    }
                    #page a:active{
                        color: #22aeff;
                        text-decoration: none;
                    }

                </style>
                <div style="text-align: center; width: 100%;" id="page">
                    <a href="#" class="icon-arrow-left" style="color: #22aeff; margin-right: 10px;" id="page_left"></a>
                    <span id="page_val">1 of 12</span>
                    <a href="#" class="icon-arrow-right" style="color: #22aeff; margin-left: 10px;" id="page_right"></a>
                </div>
                
            </div>     
             
             
             
             
            <?=$user->footer_copy;?>
             
        </div>
        <!-- END MAIN GRID -->
    </div>
    <!-- END MAIN CONTENT -->











<!-- JAVASCRIPT -->
<script src="js/jquery.min.js"></script>
<script src="js/jquery-ui.min.js"></script>
<script src="TypeWatch/jquery.typewatch.js"></script>
<script src="js/spin.js"></script>
<script src="realtime-notifications/lib/gritter/js/jquery.gritter.min.js"></script>
<link href="realtime-notifications/lib/gritter/css/jquery.gritter.css"rel="stylesheet" type="text/css" />
<!--<script src="realtime-notifications/pusher.min.js"></script>
<script src="realtime-notifications/PusherNotifier.js"></script>-->
<script src="js/socket.io-1.3.5.js"></script>
<script src="push/push_client.js"></script>
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


$(document).ready(function() 
{
    //var pusher = new Pusher('d869a07d8f17a76448ed');
    //var channel = pusher.subscribe($("#BOTHID").val());
    var push = new Push($("#BOTHID").val(), window.location.hostname + ':3955');
    push.bind('notification', function(data) 
    {
        console.log('GOT NOTIFICATION');
        displaynotification('New Message', data);
    });
    var doctor_pages = 1;
    var patient_pages = 1;
    var current_page = 1;
    var mes_id = $("#Message_id").val();
    var messages = new Array();
    var selected = new Array();
    var selectedAttachments = new Array();
    var current_message = -1;
    var folder = 1;
    var compose_selected_user = -1;
    $('#Type_toggle').live('click',function(){
        selected.length = 0;
        current_page = 1;
        GetMessages(folder, '');
    });
    
    $('#page_left').live('click',function(e){
        selected.length = 0;
        current_page -= 1;
        GetMessages(folder, '');
        e.preventDefault();
    });
    $('#page_right').live('click',function(e){
        selected.length = 0;
        current_page += 1;
        GetMessages(folder, '');
        e.preventDefault();
    });
    $('#ReplyButton').live('click',function(e){
        $("#ReplyButton").hide();
        $("#CloseMessage").hide();
        $("#CancelButton").show();
        $("#SendButton").show();
        $("#reply_message").show();
        $("#read_message").hide();
        $("#attachments").hide();
        $("#reply_subject").val("RE: " + current_message.SUBJECT);
        $('#AttachmentButton').hide();
        e.preventDefault();
    });
    $('#CancelButton').live('click',function(e){
        $("#ReplyButton").show();
        $("#CloseMessage").show();
        $("#CancelButton").hide();
        $("#SendButton").hide();
        $("#reply_message").hide();
        $("#read_message").show();
        $("#attachments").hide();
        if(current_message.ATT)
        {
            $("#AttachmentButton").show();
        }
        else
        {
            $("#AttachmentButton").hide();
        }
        e.preventDefault();
    });
    $('#PrintButton').live('click',function(e){
        printReports();
    });
    $('#AttachmentButton').live('click',function(e){
        if($('#AttachmentButton').val() == "View Attachments")
        {
            $("#attachments").empty();
            var queUrl ='createAttachmentStreamNEW.php?ElementDOM=na&EntryTypegroup=0&Usuario='+current_message.SENDER_ID+'&MedID='+$("#MEDID").val()+'&IGNPR=1&Reports='+current_message.ATTACHMENTS;
            console.log(queUrl);
            var RecTipo=LanzaAjax(queUrl);
            //alert(RecTipo);
            $("#attachments").append(RecTipo);
            $("#attachments").show();
            $("#reply_message").hide();
            $("#read_message").hide();
            $('#AttachmentButton').val("View Message");
            $('#ReplyButton').hide();
            selectedAttachments.length = 0;
            
            $(".attachments").css("cursor", "pointer");
            $(".attachments").css("margin-bottom", "0px");
            $(".attachments").on('click', function(e)
            {
                console.log($(this).attr("id"));
                var file = $(this).children('input').first().val();
                console.log(file);
                $.get('DecryptFile.php?rawimage='+file+'&queMed='+$("#MEDID").val(), function(data, status)
                {
                    $("#attachment_frame").attr("src", "temp/"+$("#MEDID").val()+"/Packages_Encrypted/"+file);
                    $("#attachment").dialog({bgiframe: true, height: 900, width: 700, modal: true});
                });
            });
        }
        else
        {
            $("#attachments").hide();
            $("#reply_message").hide();
            $("#read_message").show();
            if(folder == 1)
            {
                $('#ReplyButton').show();
            }
            else
            {
                $('#ReplyButton').hide();
            }
            $('#AttachmentButton').val("View Attachments");
        }
    });
    $('#SendButton').live('click',function(e){
        if($("#reply_subject").val().length == 0)
        {
            alert("Please input a message subject.");
        }
        else if($("#reply_content").val().length == 0)
        {
            alert("Please input a message.");
        }
        else
        {
            var type = 'patient';
            if($('#Type_toggle').is(":checked")/* || folder == 2*/) type = 'doctor';
            var sen_id = 0;
            if(type === 'patient')
            {
                sen_id = current_message.ID_pat;
            }
            else
            {
                sen_id = current_message.SENDER_ID;
            }
            $.post("updateMessage.php", {mes: 0, scenario: type, type: 'send', IdMED: $("#MEDID").val(), IdRECEIVER: sen_id, SUBJECT: $("#reply_subject").val(), MESSAGE: $("#reply_content").val()}, function(data, status){});
            $('#message_modal').dialog('close');//.trigger('click');
        }
        e.preventDefault();
    });
    $('#ComposeSendButton').live('click',function(e){
        if(compose_selected_user == -1)
        {
            alert("Please choose a recipient.");
        }
        else if($("#compose_message_subject").val().length == 0)
        {
            alert("Please input a message subject.");
        }
        else if($("#compose_message_content").val().length == 0)
        {
            alert("Please input a message.");
        }
        else
        {
            var type = 'patient';
            if($('#Type_toggle').is(":checked")/* || folder == 2*/) type = 'doctor';
            $.post("updateMessage.php", {mes: 0, scenario: type, type: 'send', IdMED: $("#MEDID").val(), IdRECEIVER: compose_selected_user, SUBJECT: $("#compose_message_subject").val(), MESSAGE: $("#compose_message_content").val()}, function(data, status){
				alert("Message sent");
				});
            $('#compose_modal').dialog('close');
        }
        e.preventDefault();
    });
    $('#ComposeCancelButton').live('click',function(e)
    {
        $('#compose_modal').dialog('close');
    });
    
    
    // attachment checkboxes
    $('.mc').live('click', function(e){
        if($(this).attr("checked") === "checked")
        {
            selectedAttachments.push(this.id);
            
        }
        else
        {
            selectedAttachments.splice(selectedAttachments.indexOf(this.id), 1);
        }
        console.log(selectedAttachments);
    });
    
    $('#refresh_button').live('click',function(e){
        selected.length = 0;
        GetMessages(folder, '');
        e.preventDefault();
    });
    $('#change_type_button').live('click',function(e){
        e.preventDefault();
        if($(this).text() == 'View Sent')
        {
            $(this).text('View Inbox');
            folder = 2;
            //$(".candy").css("display", "none");
            //$("#search_bar").css("width", "466px");
            $("#name_column").text("To");
            $("#name_field").text("To");
            
        }
        else
        {
            $(this).text('View Sent');
            folder = 1;
            //$(".candy").css("display", "block");
            //$("#search_bar").css("width", "300px");
            $("#name_column").text("From");
            $("#name_field").text("From");
        }
        selected.length = 0;
        console.log(folder);
        GetMessages(folder, '');
    });
    $("#compose_button").on('click', function(e)
    {
        compose_selected_user = -1;
        $("#search_users_results").html('');
        $("#search_users_bar").val('');
        $("#compose_message_recipient_label").text('');
        $("#compose_message_content").val('');
        $("#compose_message_subject").val('');
        if($('#Type_toggle').is(":checked"))
        {
            $("#ComposeAttachmentButton").css("display", "block");
        }
        else
        {
            $("#ComposeAttachmentButton").css("display", "none");
        }
        $("#ComposeMessageButton").css("display", "none");
        $("#ComposeSendButton").css("display", "block");
        $("#ComposeCancelButton").css("display", "block");
        $("#compose_modal").children('div').eq(0).css("display", "block");
        $("#compose_modal").children('div').eq(1).css("display", "block");
        $("#compose_attachments").css("display", "none");
        $("#compose_modal").dialog({bgiframe: true, width: 1000, height: 500, modal: true, resizable: false});
    });
    $("#ComposeAttachmentButton").on('click', function(e)
    {
        $("#compose_modal").children('div').eq(0).css("display", "none");
        $("#compose_modal").children('div').eq(1).css("display", "none");
        $("#compose_attachments").css("display", "block");
        $("#ComposeSendButton").css("display", "none");
        $("#ComposeCancelButton").css("display", "none");
        $("#ComposeAttachmentButton").css("display", "none");
        $("#ComposeMessageButton").css("display", "block");
    });
    $("#ComposeMessageButton").on('click', function(e)
    {
        $("#compose_modal").children('div').eq(0).css("display", "block");
        $("#compose_modal").children('div').eq(1).css("display", "block");
        $("#compose_attachments").css("display", "none");
        $("#ComposeSendButton").css("display", "block");
        $("#ComposeCancelButton").css("display", "block");
        $("#ComposeAttachmentButton").css("display", "block");
        $("#ComposeMessageButton").css("display", "none");
    });
    $('#delete_button').live('click',function(e){
        var type = 'patient';
        if($('#Type_toggle').is(":checked")/* || folder == 2*/) type = 'doctor';
        $.post("updateMessage.php", {mes: selected, status: 'read', scenario: type, type: 'delete', IdMED: $("#MEDID").val()}, function(data, status){GetMessages(folder, '');});
        e.preventDefault();
    });
    $('#search_bar_button').live('click',function(e){
        if($("#search_bar").val().length > 0)
        {
            GetMessages(folder, $("#search_bar").val());
        }
        else
        {
            GetMessages(folder, '');
        }
    });
    $('#search_bar').keypress(function (e) 
    {
        if (e.which == 13) 
        {
            $("#search_bar_button").trigger('click');
        }
    });
    
    $('#search_users_bar_button').live('click',function(e){
        if($("#search_users_bar").val().length > 0)
        {
            var type = 2;
            if($('#Type_toggle').is(":checked")) type = 1;
            $.post("getUsersSearch.php", {search: $("#search_users_bar").val(), type: type, doc: $("#MEDID").val()}, function(data, status)
            {
                var info = JSON.parse(data);
                var html = '';
                for(var i = 0; i < info.length; i++)
                {
                    
                    
                    html += '<button id="searchuser_'+info[i]['id']+'" style="width: 100%; height: 50px; background-color: #FBFBFB; color: #333; border: 0px solid #000; outline: none;text-align: left; border: 1px solid #E8E8E8;" >';
                    html += '<span style="color: #';
                    if(info[i]['type'] == 1)
                    {
                        html += '22AEFF';
                    }
                    else
                    {
                        html += '54bc00';
                    }
                    html += '">';
                    if(info[i]['type'] == 1)
                    {
                        html += "Dr ";
                    }
                    html += info[i]['name']+' '+info[i]['surname']+'</span> <br/> <span style="font-size: 12px;">'+info[i]['email']+'</span>';
                    html += '</button>';
                }
                $("#search_users_results").html(html);
                $("#search_users_results").children().first().css('border-top-right-radius', '5px');
                $("#search_users_results").children().first().css('border-top-left-radius', '5px');
                $("#search_users_results").children().last().css('border-bottom-right-radius', '5px');
                $("#search_users_results").children().last().css('border-bottom-left-radius', '5px');
                
                $('button[id^="searchuser_"]').on('click', function(e)
                {
                    var user_id = $(this).attr("id").split("_")[1];
                    var user_name = $(this).children().first().text();
                    compose_selected_user = user_id;
                    $("#compose_message_recipient_label").text(user_name);
                    console.log(compose_selected_user);
                    
                });
                
            });
        }
    });
    $('#search_users_bar').keypress(function (e) 
    {
        if (e.which == 13) 
        {
            $("#search_users_bar_button").trigger('click');
        }
    });
    
    
    $('#mark_unread_button').live('click',function(e){
        if(folder == 1)
        {
            var type = 'patient';
            if($('#Type_toggle').is(":checked")/* || folder == 2*/) type = 'doctor';
            $.post("updateMessage.php", {mes: selected, status: 'new', scenario: type, type: 'unread', IdMED: $("#MEDID").val()}, function(data, status){GetMessages(folder, '');});
        }
        else
        {
            alert("You cannot set sent messages as unread");
        }
    });
    
    
    $('input.mes_sel').live('click',function(e){
        if($(this).attr("checked") === "checked")
        {
            selected.push(this.id);
            
        }
        else
        {
            selected.splice(selected.indexOf(this.id), 1);
        }
        console.log(selected);
        
    });
    
    
    
    $("#subject_title a").live('click',function(e){
        e.preventDefault();
        if(folder == 1)
        {
            $("#ReplyButton").show();
        }
        else
        {
            $("#ReplyButton").hide();
        }
        //$("#CloseMessage").show();
        $("#CancelButton").hide();
        $("#SendButton").hide();
        $("#reply_message").hide();
        $("#read_message").show();
        $("#attachments").hide();
        $("#AttachmentButton").val("View Attachments");
        
        if(messages[this.id].ATT)
        {
            $("#AttachmentButton").show();
        }
        else
        {
            $("#AttachmentButton").hide();
        }
        var type = 'patient';
        if($('#Type_toggle').is(":checked")/* || folder == 2*/) type = 'doctor';
        if(folder == 1)
        {
            $(this).parent().parent().removeAttr("class");
            $.post("updateMessage.php", {mes: messages[this.id].ID, status: 'read', type: 'read', scenario: type, IdMED: $("#MEDID").val()}, function(data, status){});
        }
        
        $('#message_modal').dialog({bgiframe: true, width: 1000, height: 500, modal: false, resizable: false});
        if(type == 'patient')
        {
            $("#messagedetail_from").html('<a style="color: #54bc00;" href="patientdetailMED-new.php?IdUsu=' + messages[this.id].SENDER_ID + '">' + messages[this.id].SENDER + '</a>');
        }
        else
        {
            $("#messagedetail_from").html(messages[this.id].SENDER);
        }
        $("#messagedetail_subject").text(messages[this.id].SUBJECT);
        $("#messagedetails").text(messages[this.id].MESSAGE);
        current_message = messages[this.id];
    });
    
    function LoadSingleMessage(mes)
    {
        if(folder == 1)
        {
            $("#ReplyButton").show();
        }
        else
        {
            $("#ReplyButton").hide();
        }
        $("#CloseMessage").show();
        $("#CancelButton").hide();
        $("#SendButton").hide();
        $("#reply_message").hide();
        $("#read_message").show();
        $("#attachments").hide();
        $("#AttachmentButton").val("View Attachments");
        
        var type = 'patient';
        if($('#Type_toggle').is(":checked")/* || folder == 2*/) type = 'doctor';
        $.post("updateMessage.php", {mes: mes, status: 'read', type: 'read',scenario: type, IdMED: $("#MEDID").val()}, function(data, status){GetMessages(folder, '');});
        $.post("getInboxMessageSingle.php", {id: mes, scenario: type}, function(data, status)
		{
            var item = JSON.parse(data);
            if(item.ATT)
            {
                $("#AttachmentButton").show();
            }
            else
            {
                $("#AttachmentButton").hide();
            }
            if(type == 'patient')
            {
                $("#messagedetail_from").html('<a style="color: #54bc00;" href="patientdetailMED-new.php?IdUsu=' + item.ID + '">' + item.SENDER + '</a>');
            }
            else
            {
                $("#messagedetail_from").html(item.SENDER);
            }
            $("#messagedetail_subject").text(item.SUBJECT);
            $("#messagedetails").text(item.MESSAGE);
            $('#message_modal').dialog({bgiframe: true, width: 1000, height: 500, modal: false, resizable: false});
            current_message = item;
        });
    }
    
    function printReports()
    {
        var reps = "";
        for(var i = 0; i < selectedAttachments.length; i++)
        {
            if(i != 0)
            {
                reps += " ";
            }
            reps += selectedAttachments[i];
        }
        if(selectedAttachments.length > 0)
        {
            // print reports given in reps variable
        }
    }
    
    
    function GetMessages(t, search)
	{
	
		var queMED = $("#MEDID").val();
        var type = 'patient';
        if($('#Type_toggle').is(":checked")/* || folder == 2*/) type = 'doctor';
        var file = "getInboxMessageAll.php";
        if(t == 2)
        {
            file = "getSentMessageAll.php";
        }
        $.post(file, {IdMED: queMED, scenario: type, page: current_page, search: search}, function(data, status)
		{
            var items = JSON.parse(data);
            if(items.length > 0)
            {
                if(type === 'patient' && items.length > 0)
                {
                    patient_pages = Math.ceil(items[0].NUM / 20);
                    $("#page_val").text(current_page + " of " + patient_pages);
                    if(current_page == 1)
                    {
                        $("#page_left").css("pointer-events", "none");
                        $("#page_left").css("color", "#eaeaea");
                    }
                    else
                    {
                        $("#page_left").css("pointer-events", "auto");
                        $("#page_left").css("color", "#22aeff");
                    }
                    if(current_page >= patient_pages)
                    {
                        $("#page_right").css("pointer-events", "none");
                        $("#page_right").css("color", "#eaeaea");
                    }
                    else
                    {
                        $("#page_right").css("pointer-events", "auto");
                        $("#page_right").css("color", "#22aeff");
                    }
                }
                else
                {
                    doctor_pages = Math.ceil(items[0].NUM / 20);
                    $("#page_val").text(current_page + " of " + doctor_pages);
                    if(current_page == 1)
                    {
                        $("#page_left").css("pointer-events", "none");
                        $("#page_left").css("color", "#eaeaea");
                    }
                    else
                    {
                        $("#page_left").css("pointer-events", "auto");
                        $("#page_left").css("color", "#22aeff");
                    }
                    if(current_page >= doctor_pages)
                    {
                        $("#page_right").css("pointer-events", "none");
                        $("#page_right").css("color", "#eaeaea");
                    }
                    else
                    {
                        $("#page_right").css("pointer-events", "auto");
                        $("#page_right").css("color", "#22aeff");
                    }
                }
                var html_str = '';
                messages.length = 0;
                for(var i = 0; i < items.length; i++)
                {
                    if(type === 'patient')
                    {
                        var arr = {'SENDER' : items[i].NAME_sender, 'SUBJECT': items[i].SUBJECT, 'MESSAGE': items[i].CONTENT, 'ID_pat': items[i].ID_pat, 'ID': items[i].ID, 'ATT': items[i].ATT, ATTACHMENTS: items[i].ATTACHMENTS};
                        messages.push(arr);
                    }
                    else
                    {
                        var arr = {'SENDER' : items[i].NAME_sender, 'SUBJECT': items[i].SUBJECT, 'MESSAGE': items[i].CONTENT, 'SENDER_ID': items[i].ID_sender, 'ID': items[i].ID, 'ATT': items[i].ATT, ATTACHMENTS: items[i].ATTACHMENTS};
                         messages.push(arr);
                    }
                    if(items[i].STATUS === 'new')
                    {
                        html_str += '<tr class="unread"><td class="aligncenter">';
                    }
                    else
                    {
                        html_str += '<tr><td class="aligncenter">';
                    }
                    html_str += '<p><input type="checkbox" class="mes_sel" id="'+items[i].ID+'" name="cc"><label for="'+items[i].ID+'"><span></span></label></p>';
                    html_str += '</td><td>';
                    if(type === 'patient')
                    {
                        html_str += '<a style="color: #54bc00;" href="patientdetailMED-new.php?IdUsu=' + items[i].ID_pat + '">';
                    }
                    html_str += items[i].NAME_sender;
                    if(type === 'patient')
                    {
                        html_str += '</a>';
                    }
                    html_str += '</td><td id="subject_title"><a href="" class="title" id="'+i+'">';
                    html_str += items[i].SUBJECT;
                    html_str += '</a></td><td class="attachment">';
                    if(items[i].ATT == true)
                    {
                        html_str += '<img src="images/mail/attachment.png"" alt="" />';
                    }
                    
                    html_str += '</td><td>';
                    if(items[i].MOBILE)
                    {
                        html_str += '<img src="images/mail/mobile.png" style="margin-left: 10px;" alt="" />';
                    }
                    html_str += '</td><td class="date">';
                    if(items[i].DATE != null)
                    {
                        html_str += items[i].DATE;
                    }
                    else
                    {
                        html_str += "-";
                    }
                    html_str += '</td></tr>';
                }
                $('#messages_body').html(html_str);
            }
            else
            {
                $('#messages_body').html('');
                $("#page_val").text('0 of 0');
                $("#page_left").css("pointer-events", "none");
                $("#page_left").css("color", "#eaeaea");
                $("#page_right").css("pointer-events", "none");
                $("#page_right").css("color", "#eaeaea");
            }
            selected.length = 0;
        });

	}
    if(mes_id > -1)
    {
        LoadSingleMessage(mes_id);
    }
    else
    {
        GetMessages(1, '');
    }
    
 
	
});        
                    
</script>


    
</body>
</html>
