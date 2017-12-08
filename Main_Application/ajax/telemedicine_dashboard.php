<?php
session_start();
 require("environment_detail.php");
 require("PasswordHash.php");
 require_once("displayExitClass.php");
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



   

//KYLE$link = mysql_connect("$dbhost", "$dbuser", "$dbpass")or die("cannot connect");
mysql_select_db("$dbname")or die("cannot select DB");

//For checking multiple logins	
$query = "select * from ongoing_sessions where userid=".$_SESSION['MEDID'];
$result=mysql_query($query);
$count=mysql_num_rows($result);
$ip = $_SERVER['REMOTE_ADDR'];	
if($count==0)
{
    //die("Here");
    $q = "insert into  ongoing_sessions values(".$_SESSION['MEDID'].",now(),'".$ip."')";
    mysql_query($q);
    
    //$q = "insert into  session_log values(".$_SESSION['MEDID'].",'Login',now(),'".$ip."')";
    //echo $q;
    //mysql_query($q);
}
else
{
    $q = "select * from ongoing_sessions where userid=".$_SESSION['MEDID']." and ip='".$ip."'";
    //die($q);
    $res=mysql_query($q);
    $cnt=mysql_num_rows($res);
    if($cnt==1)
    {
        //The same user came back after abrupt logout (and before service could detect)
        //DO NOTHING
    }
    else
    {
        echo "Other users are Accessing this account";
        $q = "insert into  ongoing_sessions values(".$_SESSION['MEDID'].",now(),'".$ip."')";
        mysql_query($q);
        header( 'Location: double_login.php' ) ;
        //$q = "insert into  session_log values(".$_SESSION['MEDID'].",'Login',now(),'".$ip."')";
        //mysql_query($q);
    }
}

$result = mysql_query("select pass from encryption_pass where id = (select max(id) from encryption_pass)");
$row = mysql_fetch_array($result);
$_SESSION['decrypt']=$row['pass'];

$NombreEnt = $_SESSION['Nombre'];
$MEDID = $_SESSION['MEDID'];
$Acceso = $_SESSION['Acceso'];
$privilege=$_SESSION['Previlege'];
if ($Acceso != '23432')
{
    $exit_display = new displayExitClass();

$exit_display->displayFunction(1);
die;
}

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
	$IdMedFIXED = $row['IdMEDFIXED'];
	$IdMedFIXEDNAME = $row['IdMEDFIXEDNAME'];
	
    $MedUserRole = $row['Role'];
	if ($MedUserRole=='1') $MedUserTitle ='Dr. '; else $MedUserTitle =' ';
    
    // Get data about the Group this user belongs to
    $resultG = mysql_query("SELECT * FROM doctorsgroups where idDoctor='$MEDID'");
    $countG=mysql_num_rows($resultG);
    $rowG = mysql_fetch_array($resultG);
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
        $resultGN = mysql_query("SELECT * FROM groups where id='$MedGroupId'");
        $countGN=mysql_num_rows($resultGN);
        $rowGN = mysql_fetch_array($resultGN);
        $MedGroupName = $rowGN['Name'];
        $MedGroupAddress = $rowGN['Address'];
        $MedGroupZIP = $rowGN['ZIP'];
        $MedGroupCity = $rowGN['City'];
        $MedGroupState = $rowGN['State'];
        $MedGroupCountry = $rowGN['Country'];
        
        //Get Number of users attatched to this group
        $resultGN2 = mysql_query("SELECT * FROM doctorsgroups where idGroup='$MedGroupId'");
        $countGN2=mysql_num_rows($resultGN2);
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
<html lang="en" style="background: #F9F9F9;">
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
    <link rel="stylesheet" type="text/css" href="css/css3-gmail-style.css" />
    
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
<body style="background: #F9F9F9;">
    <input type="hidden" id="Message_id" value="<?php echo $mes_id; ?>">

    
    
    
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

    
    <div class="loader_spinner"></div>

    <input type="hidden" id="NombreEnt" value="<?php echo $NombreEnt; ?>">
    <input type="hidden" id="PasswordEnt" value="<?php echo $PasswordEnt; ?>">
    <input type="hidden" id="UserHidden">

	<!-- HEADER VIEW FOR MAIN TOOLBAR -->
	<div class="header" >
     	<input type="hidden" id="USERDID" Value="<?php echo $USERID; ?>">	
    	<input type="hidden" id="MEDID" Value="<?php echo $MedID; ?>">	
    	<input type="hidden" id="GROUPID" Value="<?php echo $MedGroupId; ?>">	
    	<input type="hidden" id="IdMEDEmail" Value="<?php echo $MedUserEmail; ?>">	
    	<input type="hidden" id="IdMEDName" Value="<?php echo $MedUserName; ?>">	
    	<input type="hidden" id="IdMEDSurname" Value="<?php echo $MedUserSurname; ?>">	
    	<input type="hidden" id="IdMEDLogo" Value="<?php echo $MedUserLogo; ?>">	
  		
        <a href="index.html" class="logo"><h1>Health2me</h1></a>
           
        <div class="pull-right">
            
            
            
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
            <div class="dropdown-menu" id="prof_dropdown">
                <div class="item_m"><span class="caret"></span></div>
                    <ul class="clear_ul" >
                        <li>
                            <?php 
                                if ($privilege==1) echo '<a href="dashboard.php">';
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
    
    
    <div id="content" style="background: #F9F9F9; padding-left:0px;">

    <!-- Button trigger modal -->
    <button id="LaunchModal" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#myModal" style="display:none;">Launch demo modal</button>
     	    
	 <!-- MAIN TOOLBAR -->
     <div class="speedbar">
        <div class="speedbar-content" style="position:relative;">
            <ul class="menu-speedbar">
                <li><a href="MainDashboard.php">Home</a></li>
                <li><a href="dashboard.php" >Dashboard</a></li>
                <li><a href="patients.php" >Patients</a></li>
                <?php 
                    if ($privilege==1)
                    {
                        echo '<li><a href="medicalConnections.php" >Doctor Connections</a></li>';
                        echo '<li><a href="PatientNetwork.php" >Patient Network</a></li>';
                    }
                ?>
                <li><a href="medicalConfiguration.php">Configuration</a></li>
                <li><a href="logout.php" style="color:yellow;">Sign Out</a></li>
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
        
        .log_item{
            width: 95%; 
            padding-left: 2%; 
            padding-top: 3px; 
            padding-bottom: 4px; 
            height: 20px; 
            outline: 1px solid #CCC; 
            color: #333;
        }
    </style>
    
    
    <input type="hidden" id="Privilege" value="<?php echo $privilege; ?>">
        
    <!-- MAIN CONTENT -->
     <div class="content">
        <!-- MAIN GRID -->
        <div class="grid" class="grid span4" style="width:1000px; margin: 0 auto; margin-top:30px; padding-top:30px;">
            <span class="label label-info" id="EtiTML" style="background-color:#22aeff; margin:10px; margin-left:35px; margin-top: 20px; font-size:24px; text-shadow:none; text-decoration:none;">Telemedicine Dashboard</span>
            <div id="log" style="margin-top: 30px; width: 95%; padding-left: 4%; padding-right: 1%;" >
                
            </div>
            <br/><br/>
        </div>
         
        <!-- END MAIN GRID -->
    </div>
    <!-- END MAIN CONTENT -->


</div>








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


$(document).ready(function() 
{
    

    //var pusher = new Pusher('d869a07d8f17a76448ed');
    //var channel_name=$('#MEDID').val();
    //var channel = pusher.subscribe(channel_name);

    var push = new Push($("#MEDID").val(), window.location.hostname + ':3955');
    push.bind('ameridoc', function(data) {
        $("#log").prepend('<div class="log_item">'+data+'</div>');
        console.log(data);
    });
});        
                    
</script>


    
</body>
</html>