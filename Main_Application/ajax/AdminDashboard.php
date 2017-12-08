<?php
session_start();
 require("environment_detail.php");
 require("PasswordHash.php");
 require_once("displayExitClass.php");
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];


// FIRST SCREEN LANDING (SETS UP ENVIRONMENTAL VARIABLES)
$NombreEnt = 'x';
$PasswordEnt = 'x';

//	$access = empty($_SESSION['Acceso'])   ? null : $_SESSION['Acceso'];
 
/* if($access==23432)
 {
	echo "Validated via access code";
 }
 else
 {*/
	
	$name=empty($_POST['Nombre'])   ? $_SESSION['Nombre'] : $_POST['Nombre'];
	$pass=empty($_POST['Password'])   ? $_SESSION['Password'] : $_POST['Password'];

	$link = mysql_connect("$dbhost", "$dbuser", "$dbpass")or die("cannot connect");
	mysql_select_db("$dbname")or die("cannot select DB");

	
	//$result = mysql_query("SELECT * FROM doctors where IdMEDEmail='$name' and IdMEDRESERV='$pass'");
	$result = mysql_query("SELECT * FROM doctors where IdMEDEmail='$name'"); 
	$count=mysql_num_rows($result);
	$row = mysql_fetch_array($result);
	$success ='NO';
	if($count==1)
	{
		
		$IdMedRESERV=$row['IdMEDRESERV'];
		$addstring=$row['salt'];
		$correcthash=PBKDF2_HASH_ALGORITHM .":". PBKDF2_ITERATIONS. ":" . $addstring . ":" .$IdMedRESERV;
		if(validate_password($pass,$correcthash)){
		$success ='SI';
		$MedID = $row['id'];
		$MedUserEmail= $row['IdMEDEmail'];
		$MedUserName = $row['Name'];
		$MedUserSurname = $row['Surname'];
		$MedUserLogo = $row['ImageLogo'];
		$IdMedFIXED = $row['IdMEDFIXED'];
		$IdMedFIXEDNAME = $row['IdMEDFIXEDNAME'];
		
		//echo "Validated via Password";
		$_SESSION['Acceso']=23432;
		$_SESSION['MEDID']=$MedID;
		
		$_SESSION['Nombre']=$name;
		$_SESSION['Password']=$pass;
		
		}else {
		
		$exit_display = new displayExitClass();

		$exit_display->displayFunction(2);
		die();
		
		
		}

	}
	else
	{
		$exit_display = new displayExitClass();

		$exit_display->displayFunction(2);
		die();
	}
	
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
	
	
	
//Create a folder for user if not already present
if (!file_exists('temp/'.$_SESSION['MEDID'])) 
{
    mkdir('temp/'.$_SESSION['MEDID'], 0777, true);
	mkdir('temp/'.$_SESSION['MEDID'].'/Packages_Encrypted', 0777, true);
	mkdir('temp/'.$_SESSION['MEDID'].'/PackagesTH_Encrypted', 0777, true);
}

$result = mysql_query("select pass from encryption_pass where id = (select max(id) from encryption_pass)");
$row = mysql_fetch_array($result);
$_SESSION['decrypt']=$row['pass'];


//FIRST SCREEN LANDING (SETS UP ENVIRONMENTAL VARIABLES)














$NombreEnt = $_SESSION['Nombre'];
$PasswordEnt = $_SESSION['Password'];
$MEDID = $_SESSION['MEDID'];
$Acceso = $_SESSION['Acceso'];
$privilege=$_SESSION['Previlege'];
if ($Acceso != '23432')
{
		$exit_display = new displayExitClass();

		$exit_display->displayFunction(1);
		die();
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
	
    if($countG==1){
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
    
    // Get information about the number of appointments for this user 
    //FOR GROUPS: $sql="SELECT * FROM events where userid=".$MEDID." or userid in (SELECT iddoctor from doctorsgroups where idgroup in (select idgroup from doctorsgroups where iddoctor=".$userid."))";
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
     }
     
    // Get Information about number of patients belonging to this Doctor (and reports)
    $Num_Own_Patients = 0;
    $Num_Group_Patients = 0;
    $Num_Own_Reports = 0;
    $Num_Group_Reports = 0;
    $IdMed = $MEDID;
    $resultPRE = mysql_query("SELECT distinct(IdUs) FROM doctorslinkusers WHERE IdPin IS NULL and (Idmed='$IdMed' or IdMED IN (Select distinct(idDoctor) from doctorsgroups where idGroup IN (select idGroup from doctorsgroups where idDoctor= '$IdMed'))) and Estado IN (2,null)");
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
     
    //Get Information about Referrals
    $result = mysql_query("SELECT id FROM doctorslinkdoctors WHERE IdMED = '$MEDID' ");
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
        
	// Get Number of Messages from patients
	$result2 = mysql_query("SELECT * FROM message_infrastructureuser WHERE sender_id = '$MEDID' AND status='new' ");
	$count2=mysql_num_rows($result2);
	$NumMessagesUser = $count2;
	if ($NumMessagesUser>0) {$VisibleUser = ''; $OpaUser='1';} else {$VisibleUser = 'hidden';$OpaUser='0.3';}
 	// Get Number of Messages from doctors
	$result2 = mysql_query("SELECT * FROM message_infrasture WHERE receiver_id = '$MEDID' AND status='new' ");
	$count2=mysql_num_rows($result2);
	$NumMessagesDr = $count2;
	if ($NumMessagesDr>0) {$VisibleDr = ''; $OpaDr='1';} else {$VisibleDr = 'hidden'; $OpaDr='0.3';}
   
    // Get Report Activity for this doctor
    // This retrieves only the last 30 days
	$MyGroup = $MedGroupId;


    $NumRepViewUser = 0;
    $NumRepViewDr = 0;
    $NumRepUpUser = 0;
    $NumRepUpDr = 0;
    $NumActiveUsers = 0;

    
}
else
{
		$exit_display = new displayExitClass();

		$exit_display->displayFunction(2);
		die();
}


//BLOCKSLIFEPIN $result = mysql_query("SELECT * FROM blocks");
//$result = mysql_query("SELECT * FROM lifepin");

?>
<!DOCTYPE html>
<html lang="en" style="background: #F9F9F9;"><head>
    <meta charset="utf-8">
    <title>Health2Me - HOME</title>
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
	<link rel="stylesheet" href="css/H2MIcons.css" />
    <link rel="stylesheet" href="css/bootstrap-responsive.css">
	<link rel="stylesheet" href="css/toggle-switch.css">
    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    <!--[if lte IE 8]><script type="text/javascript" src="/js/excanvas.min.js"></script><![endif]-->
 
    <!-- Le fav and touch icons -->
    <link rel="shortcut icon" href="/images/icons/favicon.ico">
    
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
<div class="loader_spinner"></div>

<input type="hidden" id="NombreEnt" value="<?php echo $NombreEnt; ?>">
<input type="hidden" id="PasswordEnt" value="<?php echo $PasswordEnt; ?>">
<input type="hidden" id="UserHidden">

	<!--Header Start-->
	<div class="header" >
     	<input type="hidden" id="USERDID" Value="<?php echo $USERID; ?>">	
    	<input type="hidden" id="MEDID" Value="<?php echo $MedID; ?>">	
    	<input type="hidden" id="IdMEDEmail" Value="<?php echo $MedUserEmail; ?>">	
    	<input type="hidden" id="IdMEDName" Value="<?php echo $MedUserName; ?>">	
    	<input type="hidden" id="IdMEDSurname" Value="<?php echo $MedUserSurname; ?>">	
    	<input type="hidden" id="IdMEDLogo" Value="<?php echo $MedUserLogo; ?>">	
  		
           <a href="index.html" class="logo"><h1>Health2me</h1></a>
           
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
			<li>
			 <?php if ($privilege==1)
					echo '<a href="dashboard.php">';
				   else if($privilege==2)
					echo '<a href="patients.php">';
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
    <!--Header END-->
    
    <!--Content Start-->
	<div id="content" style="background: #F9F9F9; padding-left:0px;">
    
    	    
     
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
            
           #navlist li{
				display: inline;
				list-style-type: none;
				padding-right: 20px;
				cursor: hand;
				cursor: pointer;
		   }

           #navlist li:active{
				color:#22aeff;
		   }
			
           </style>
     
<input type="hidden" id="Privilege" value="<?php echo $privilege; ?>">
     
     <!--CONTENT MAIN START-->
     <div class="content">
			<div class="grid" class="grid span4" style="width:1000px; margin: 0 auto; margin-top:30px; padding-top:30px;">
	            <div class="grid" class="grid span4" style="margin:0px auto; margin-left:10px; margin-right:10px; margin-bottom:10px; padding:20px; min-height:50px;">
	            	<div style="width:10%; float:left; ">
	            		<img src="images/H2MLogoNEW.png" style="height:60px; margin-top:-5px;">
	            	</div>	
	            	<div style="width:12%; float:left;">
	            		<div style="width:95%; margin:0px; text-align:left; font-size:35px; font-weight:bold;">
	            			Active
	            		</div>
	            		<div style="width:95%; margin-top:8px; text-align:left; font-size:35px;">
	            			Users
	            		</div>
	            	</div>
					<div style="width:10%; float:left;">
						<div id="ActiveUsers" style="width:95%; margin:0px; margin-top:15px; text-align:left; font-size:80px; font-weight:bold; color:#22aeff;">
	            			26
	            		</div>
					</div>	
					<div style="width:10%; float:right;">
						<p style="color:#22aeff; font-size:14px; margin-top:0px; line-height:90%;">Health2Me Administrative Console</p>
						<p style="color:#54bc00; font-size:10px; margin-top:2px;">For internal use only</p>
					</div>	
	            </div>
				<div class="grid" class="grid span4" style="margin:0px auto; margin-top:10px; margin-left:10px; margin-right:10px; padding:20px;">
					<div class="grid" style="margin:0px auto; padding:20px; margin-bottom:10px; margin-top:-5px; min-height:50px;">
			            <div style="width:35%; float:left;">
				            <input type="button" class="btn btn-primary" value="Refresh" style="margin-left:10px;" id="ButtonRefreshActivity2">
				            <input type="text"  style="margin-left:20px; margin-top:10px; width:20px;" id="InputDays" value="7">
				            <span style="margin-left:5px;">Select days</span>
			            </div>	
			            <div style="width:60%; float:left; margin-top:10px;">
			            	<ul id="navlist" style="font-size:16px; color:#cacaca; margin-left:20px;">
			            		<li id="Sessions">Sessions</li>
			            		<li id="Browsing">Reports</li>
			            		<li id="Referrals">Referrals</li>
			            		<li id="Messages">Messages</li>
			            		<li id="ALL">All</li>
			            	</ul>
			            </div>
					</div>
					<table class="table table-mod" id="TableActivity" style="width:100%; table-layout: fixed; "> 
	    			</table> 
	            </div>
	
				<span style="color:grey; font-size:14px; margin:20px;">&copy 2014 Inmers. All rights reserved.</span>
	         
	         
			</div>
        
        <audio>
			<source id="Beep24" src="sound/beep-24.mp3"></source>
		</audio>
         <!--
             <div class="grid" class="grid span4" style="margin: 30px auto; margin-top:30px; margin-left:30px; margin-right:30px; padding:30px;">
                <p style="color:orange; font-size:14px; font-weight:bold;">Your EMR: </p>
                <div style="color:orange; font-size:12px; font-weight:normal;">
                    <p>Scheduling Data: <?php echo $Pat_App_Week.' '; ?> Patients appointed this week.</p>
                    <p>Scheduling Data: <?php echo $Pat_App_NextWeek.' '; ?> Patients appointed for next week.</p>
                    <p>Scheduling Data: <?php echo $Pat_App_Month.' '; ?> Patients appointed this month so far.</p>
                    <p>Billing Module Data: TB Extracted here.</p>
                    <p>GROUP Data: <?php echo $UsersInGroup.' '; ?>Accounts attached to <?php echo $MedGroupName.' '; ?> Group</p>
                    <p> Patients Data:  <?php echo $Num_Own_Patients.' ';?>directly created patients.</p>
                    <p> Patients Data:  <?php echo $Num_Group_Patients.' ';?>patients administered by the Group.</p>
                    <p> Chart Data:  <?php echo $Num_Own_Reports.' ';?>reports created by this user.</p>
                    <p> Chart Data:  <?php echo $Num_Group_Reports.' ';?>reports created by this Group in total.</p>
                </div>
                <p style="color:green; font-size:14px; font-weight:bold;">Your Referrals Network: </p>
                <div style="color:green; font-size:12px; font-weight:normal;">
                    <p>Referrals: Referred patients to <?php echo ' '.$Number_Doctors_IReferred.' '; ?> doctors.</p>
                    <p>Referrals:<?php echo ' ',$Number_Patients_IReferred.' '; ?> patients referred to other doctors.</p>
                    <p>Received: <?php echo ' '.$Number_Doctors_Referred2Me.' '; ?> doctors are referring patients to me.</p>
                    <p>Received:<?php echo ' '.$Number_Patients_Referred2Me.' '; ?> patients have been referred to me.</p>
                    
                    <p>Balance Doctors: <?php echo ' '.sprintf("%+d",($Number_Doctors_Referred2Me-$Number_Doctors_IReferred)).' '; ?> is the doctors Balance.</p>
                    <p>Balance Patients:<?php echo ' '.sprintf("%+d",($Number_Patients_Referred2Me-$Number_Patients_IReferred)).' '; ?> is the patients Balance.</p>
                    
                    <p>Waiting Time: Mean waiting time is <?php echo ' '.number_format($Mean_TTV,2); ?> days.</p>
                    
                </div>
             </div> 
         
     </div>
     </div>

-->
     <!--CONTENT MAIN END-->

    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="js/jquery.min.js"></script>
    <script src="js/jquery-ui.min.js"></script>
	<script src="TypeWatch/jquery.typewatch.js"></script>
	
	<!--<script src="js/h2mcommons.js" type="text/javascript"></script>-->
	
    <script type="text/javascript" >
    
 	$(window).load(function() {
	//alert("started");
	$(".loader_spinner").fadeOut("slow");
	$("#BotonRef").trigger("click");
})
	
	
	var Privilege = $("#Privilege").val();

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
	
	
	function CheckTray()
	{
		var queMED = $("#MEDID").val();
		
		var url = 'CheckTray.php?doctor_id='+queMED;
		//var cadenaGUD = '<?php echo $domain;?>/GetMedCreator.php?UserId='+UserId;
		// return array from php function LanzaAjaxArray
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

	function sortJsonArrayByProperty(objArray, prop, direction){
	    if (arguments.length<2) throw new Error("sortJsonArrayByProp requires 2 arguments");
	    var direct = arguments.length>2 ? arguments[2] : 1; //Default to ascending
	
	    if (objArray && objArray.constructor===Array){
	        var propPath = (prop.constructor===Array) ? prop : prop.split(".");
	        objArray.sort(function(a,b){
	            for (var p in propPath){
	                if (a[propPath[p]] && b[propPath[p]]){
	                    a = a[propPath[p]];
	                    b = b[propPath[p]];
	                }
	            }
	            // convert numeric strings to integers
	            a = a.match(/^\d+$/) ? +a : a;
	            b = b.match(/^\d+$/) ? +b : b;
	            return ( (a < b) ? -1*direct : ((a > b) ? 1*direct : 0) );
	        });
	    }
	}





    $(document).ready(function() {
	
	$("#PatRefIn").hide();
	$("#PatNewly").hide();

	var ordersort = -1;
	var ActSessions = 1;
	var ActBrowsing = 1;
	var ActReferrals = 1;
	var ActMessages = 1;
	GetActiveUsers();
	
	function GetActiveUsers()
	{
		var queUrl ='getActiveUsers.php';		
		var Stats = Array();

		$.ajax(
		{
			url: queUrl,
			dataType: "json",
			async: false,
			success: function(data)
			{
				//alert('Data Fetched');
				Stats = data.items;
			}
		});
			
		$('#ActiveUsers').html(Stats[0].Users);
    	$('#ActiveUsers').trigger('update');

	}
	
	$('body').bind('mousedown keydown', function(event) {
        clearTimeout(timeoutTimer);
        timeoutTimer = setTimeout(ShowTimeOutWarning, timeoutTime);
    });
	
	//<!--xH2MTRAY-->
	
	$("#ButtonRefreshActivity2").click(function(event) {
		ReloadTable('LastLogin',ActSessions, ActBrowsing, ActReferrals, ActMessages);

	});
	
	$(".CFILA").live('click',function() {
 		//var myClass = $(this).parents("tr.CFILA").attr("id");
		var myClass = $(this).attr("id");
		//alert (myClass);
	});
	
	$("#Col1").live('click',function() {
		ReloadTable('IdMed',ActSessions, ActBrowsing, ActReferrals, ActMessages);
	});
	$("#Col2").live('click',function() {
		ReloadTable('NameMed',ActSessions, ActBrowsing, ActReferrals, ActMessages);
	});
	$("#Col3").live('click',function() {
		ReloadTable('SurnameMed',ActSessions, ActBrowsing, ActReferrals, ActMessages);
	});
	$("#CEmail").live('click',function() {
		ReloadTable('Email',ActSessions, ActBrowsing, ActReferrals, ActMessages);
	});
	$("#Col4").live('click',function() {
		ReloadTable('LastLogin',ActSessions, ActBrowsing, ActReferrals, ActMessages);
	});
	$("#CSes").live('click',function() {
		ReloadTable('CompletedSessions',ActSessions, ActBrowsing, ActReferrals, ActMessages);
	});
	$("#STime").live('click',function() {
		ReloadTable('SessionTime',ActSessions, ActBrowsing, ActReferrals, ActMessages);
	});
	$("#Col5").live('click',function() {
		ReloadTable('UserViews',ActSessions, ActBrowsing, ActReferrals, ActMessages);
	});
	$("#Col6").live('click',function() {
		ReloadTable('UserVerified',ActSessions, ActBrowsing, ActReferrals, ActMessages);
	});
	$("#Col7").live('click',function() {
		ReloadTable('UserUploaded',ActSessions, ActBrowsing, ActReferrals, ActMessages);
	});
	$("#Col8").live('click',function() {
		ReloadTable('ReferralsSent',ActSessions, ActBrowsing, ActReferrals, ActMessages);
	});
	$("#Col9").live('click',function() {
		ReloadTable('ReferralsAck',ActSessions, ActBrowsing, ActReferrals, ActMessages);
	});
	$("#Col10").live('click',function() {
		ReloadTable('ReferralsRec',ActSessions, ActBrowsing, ActReferrals, ActMessages);
	});
	$("#MSent").live('click',function() {
		ReloadTable('MessagesSent',ActSessions, ActBrowsing, ActReferrals, ActMessages);
	});
	$("#MRec").live('click',function() {
		ReloadTable('MessagesRec',ActSessions, ActBrowsing, ActReferrals, ActMessages);
	});
	
	$("#Sessions").live('click',function() {
		ActSessions = 1;
		ActBrowsing = 0;
		ActReferrals = 0;
		ActMessages = 0;
		ReloadTable('MessagesRec',ActSessions, ActBrowsing, ActReferrals, ActMessages);
	});
	$("#Browsing").live('click',function() {
		ActSessions = 0;
		ActBrowsing = 1;
		ActReferrals = 0;
		ActMessages = 0;
		ReloadTable('MessagesRec',ActSessions, ActBrowsing, ActReferrals, ActMessages);
	});
	$("#Referrals").live('click',function() {
		ActSessions = 0;
		ActBrowsing = 0;
		ActReferrals = 1;
		ActMessages = 0;
		ReloadTable('MessagesRec',ActSessions, ActBrowsing, ActReferrals, ActMessages);
	});
	$("#Messages").live('click',function() {
		ActSessions = 0;
		ActBrowsing = 0;
		ActReferrals = 0;
		ActMessages = 1;
		ReloadTable('MessagesRec',ActSessions, ActBrowsing, ActReferrals, ActMessages);
	});
	$("#ALL").live('click',function() {
		ActSessions = 1;
		ActBrowsing = 1;
		ActReferrals = 1;
		ActMessages = 1;
		ReloadTable('MessagesRec',ActSessions, ActBrowsing, ActReferrals, ActMessages);
	});
	
	        
	function ReloadTable(SortField, Sessions, Browsing, Referrals, Messages){
		var Days = $("#InputDays").val();
		var queUrl ='getActivity.php?Days='+Days;		
		var Doctors = Array();

		//var TestRep = LanzaAjax (queUrl);
		//alert (TestRep);
		
		$.ajax(
		{
			url: queUrl,
			dataType: "json",
			async: false,
			success: function(data)
			{
				//alert('Data Fetched');
				Doctors = data.items;
			}
		});


		if (ordersort == 0) 
		{
			sortJsonArrayByProperty(Doctors, SortField);
			ordersort = -1; 
		}
		else
		{
			sortJsonArrayByProperty(Doctors, SortField,ordersort);
			ordersort = 0; 
		}
		Pa = Doctors.length;
		IntTable = '';
		var n=0;
		while (n < Pa)
		{
			if (n == 0)
			{
				IntTable = IntTable +  '<thead>';
				IntTable = IntTable +  '<tr id="FILA" class="CFILA">';
				IntTable = IntTable +  '<th id="Col1" style="width:20px;">Id</th>';
				IntTable = IntTable +  '<th id="Col2" style="width:60px;">Name</th>';
				IntTable = IntTable +  '<th id="Col3" style="width:60px;">Surname</th>';
				IntTable = IntTable +  '<th id="CEmail" style="width:120px;">e-mail</th>';
				IntTable = IntTable +  '<th id="Col4" style="width:70px;">Last LogIn (GMT)</th>';
				if (Sessions == 1)
				{
					IntTable = IntTable +  '<th id="CSes" style="width:20px; font-size:8px;"># Sessions</th>';
					IntTable = IntTable +  '<th id="STime" style="width:20px; font-size:8px;">Session Time</th>';
				}
				if (Browsing == 1)
				{
					IntTable = IntTable +  '<th id="Col5" style="width:15px; font-size:8px;">Reports Viewed</th>';
					IntTable = IntTable +  '<th id="Col6" style="width:15px; font-size:8px;">Reports Verified</th>';
					IntTable = IntTable +  '<th id="Col7" style="width:15px; font-size:8px;">Reports Uploaded</th>';
				}
				if (Referrals == 1)
				{
					IntTable = IntTable +  '<th id="Col8" style="width:15px; font-size:8px;">Referrals Sent</th>';
					IntTable = IntTable +  '<th id="Col9" style="width:15px; font-size:8px;">Referrals Ack.</th>';
					IntTable = IntTable +  '<th id="Col10" style="width:15px; font-size:8px;">Referrals Received</th>';
					IntTable = IntTable +  '<th style="width:15px; font-size:8px;">Referrals Accepted</th>';
				}
				if (Messages == 1)
				{
					IntTable = IntTable +  '<th id="MSent" style="width:15px; font-size:8px;">Messages Sent</th>';
					IntTable = IntTable +  '<th id="MRec" style="width:15px; font-size:8px;">Messages Received</th></tr></thead>';
				}	
				IntTable = IntTable +  '<tbody>';
			}
			
			IntTable = IntTable +  '<tr class="CFILA" id="'+Doctors[n].IdMed+'">';
			IntTable = IntTable +  '<td>'+Doctors[n].IdMed+'</td>';
			IntTable = IntTable +  '<td>'+Doctors[n].NameMed+'</td>';
			IntTable = IntTable +  '<td>'+Doctors[n].SurnameMed+'</td>';
			IntTable = IntTable +  '<td>'+Doctors[n].Email.substr(0,20)+'</td>';
			IntTable = IntTable +  '<td>'+Doctors[n].LastLogin+'</td>';
			if (Sessions == 1)
			{
				IntTable = IntTable +  '<td>'+Doctors[n].CompletedSessions+'</td>';
				IntTable = IntTable +  '<td>'+Doctors[n].SessionTime+' ('+parseInt(Doctors[n].SessionTime/60)+')</td>';
			}
			if (Browsing == 1)
			{
				IntTable = IntTable +  '<td>'+Doctors[n].UserViews+'</td>';
				IntTable = IntTable +  '<td>'+Doctors[n].UserVerified+'</td>';
				IntTable = IntTable +  '<td>'+Doctors[n].UserUploaded+'</td>';
			}
			if (Referrals == 1)
			{
				IntTable = IntTable +  '<td>'+Doctors[n].ReferralsSent+'</td>';
				IntTable = IntTable +  '<td>'+Doctors[n].ReferralsAck+'</td>';
				IntTable = IntTable +  '<td>'+Doctors[n].ReferralsRec+'</td>';
				IntTable = IntTable +  '<td>'+Doctors[n].ReferralsCon+'</td>';
			}
			if (Messages == 1)
			{
				IntTable = IntTable +  '<td>'+Doctors[n].MessagesSent+'</td>';
				IntTable = IntTable +  '<td>'+Doctors[n].MessagesRec+'</td>';
			}	
			IntTable = IntTable +  '</tr>';

			n++;
		}

		IntTable = IntTable +  '</tbody>';
		//IntTable = IntTable +  '</table>';
		$('#TableActivity').html(IntTable);
    	$('#TableActivity').trigger('update');
	
	};
    
    $('#Wait1')
    .hide()  // hide it initially
    .ajaxStart(function() {
        //alert ('ajax start');
        $(this).show();
    })
    .ajaxStop(function() {
        $(this).hide();
    }); 

    $('#datatable_1 tbody').click( function () {
    // Alert the contents of an element in a SPAN in the first TD    
    alert( $('td:eq(0) span', this).html() );
    } );
 
	
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
