<?php
/*KYLE
session_start();
 require("environment_detail.php");
 require("PasswordHash.php");
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
	/*KYLE
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
		
		 echo "MEDICAL USER NOT VALID. Incorrect credentials for login";
			echo "<br>\n"; 	
			echo "<h2><a href='".$domain."'>Return to Inmers HomePage</a></h2>";
			session_destroy();
			die;
		
		
		}

	}
	else
	{
		//$result2 = mysql_query("SELECT * FROM doctors where IdMEDFIXEDNAME='$name' and IdMEDRESERV='$pass'");
		$result2 = mysql_query("SELECT * FROM doctors where IdMEDFIXEDNAME='$name'");
		$count2=mysql_num_rows($result2);
		$row2 = mysql_fetch_array($result2);
		$success ='NO';
		if($count2==1)
		{
			$IdMedRESERV=$row2['IdMEDRESERV'];
			$addstring=$row2['salt'];
			$correcthash=PBKDF2_HASH_ALGORITHM .":". PBKDF2_ITERATIONS .":".$addstring.":".$IdMedRESERV;
		    if(validate_password($pass,$correcthash)){
			$success ='SI';
			$MedID = $row2['id'];
			$MedUserEmail= $row2['IdMEDEmail'];
			$MedUserName = $row2['Name'];
			$MedUserSurname = $row2['Surname'];
			$MedUserRole = $row2['Role'];
			$MedUserLogo = $row2['ImageLogo'];
			$IdMedFIXED = $row2['IdMEDFIXED'];
			$IdMedFIXEDNAME = $row2['IdMEDFIXEDNAME'];
			//echo "Validated via Password";
			$_SESSION['Acceso']=23432;
			$_SESSION['MEDID']=$MedID;
			$_SESSION['Nombre']=$name;
			$_SESSION['Password']=$pass;
		
			if ($MedUserRole=='1') $MedUserTitle ='Dr. '; else $MedUserTitle =' '; 
			}else {
			echo "MEDICAL USER NOT VALID. Incorrect credentials for login";
			echo "<br>\n"; 	
			echo "<h2><a href='".$domain."'>Return to Inmers HomePage</a></h2>";
			session_destroy();
			die;			
			
			}
		}
		else
		{
			echo "MEDICAL USER NOT VALID. Incorrect credentials for login";
			echo "<br>\n"; 	
			echo "<h2><a href='".$domain."'>Return to Inmers HomePage</a></h2>";
			session_destroy();
			die;
		}
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
echo "Incorrect credentials for login";
echo "<br>\n"; 	
echo "<h2><a href='".$domain."'>Return to Health2me Dashboard</a></h2>";
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

    // Reports viewed by patients
	$result2 = mysql_query("SELECT * FROM bpinview WHERE VIEWIdUser IN (SELECT Identif FROM usuarios WHERE IdCreator='$MEDID' or IdCreator IN (SELECT IdDoctor FROM doctorsgroups WHERE IdGroup = '$MyGroup') or Identif IN (SELECT IdUs FROM doctorslinkusers WHERE IdMED = '$MEDID')) AND (Content = 'Report Viewed') AND (DateTimeSTAMP BETWEEN SYSDATE()-INTERVAL 30 DAY AND SYSDATE()) ");
	$NumRepViewUser = mysql_num_rows($result2);
	
    // Reports viewed by doctors
	$result2 = mysql_query("SELECT * FROM bpinview WHERE VIEWIdUser = 0 AND (Content = 'Report Viewed') AND (DateTimeSTAMP BETWEEN SYSDATE()-INTERVAL 30 DAY AND SYSDATE()) ");
	$NumRepViewDr = mysql_num_rows($result2);
    
    // Reports Uploaded by patients
	$result2 = mysql_query("SELECT * FROM bpinview WHERE (VIEWIdUser = VIEWIdMed) AND VIEWIdUser IN (SELECT Identif FROM usuarios WHERE IdCreator='$MEDID' or IdCreator IN (SELECT IdDoctor FROM doctorsgroups WHERE IdGroup = '$MyGroup') or Identif IN (SELECT IdUs FROM doctorslinkusers WHERE IdMED = '$MEDID')) AND (Content = 'Report Uploaded') AND (DateTimeSTAMP BETWEEN SYSDATE()-INTERVAL 30 DAY AND SYSDATE()) ");
	$NumRepUpUser = mysql_num_rows($result2);
    
    // Reports Uploaded by Doctors
	$result2 = mysql_query("SELECT * FROM bpinview WHERE VIEWIdUser = 0 AND (Content = 'Report Uploaded') AND (DateTimeSTAMP BETWEEN SYSDATE()-INTERVAL 30 DAY AND SYSDATE()) ");
	$NumRepUpDr = mysql_num_rows($result2);
    
    // Number Active Users
    $result2 = mysql_query("SELECT * FROM usuarios where Password IS NOT NULL AND  (IdCreator='$MEDID' or IdCreator IN (SELECT IdDoctor FROM doctorsgroups WHERE IdGroup = '$MyGroup') or Identif IN (SELECT IdUs FROM doctorslinkusers WHERE IdMED = '$MEDID'))");
	$NumActiveUsers = mysql_num_rows($result2);

    
    
    
}
else
{
echo "MEDICAL USER NOT VALID. Incorrect credentials for login";
echo "<br>\n"; 	
echo "<h2><a href='".$domain."'>Return to Health2me Dashboard</a></h2>";
die;
}


//BLOCKSLIFEPIN $result = mysql_query("SELECT * FROM blocks");
$result = mysql_query("SELECT * FROM lifepin");

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
    <link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Cabin">
    
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
    
    	    
	 <!--SpeedBar Start--->
     <div class="speedbar">
     <div class="speedbar-content">
     <ul class="menu-speedbar">
		
    	 <li><a href="MainDashboard.php" class="act_link">Home</a></li>
		 <li><a href="dashboard.php" >Dashboard</a></li>
    	 <li><a href="patients.php" >Patients</a></li>
		 <?php if ($privilege==1)
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
     <!--SpeedBar END-->
     
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
     
     <!--CONTENT MAIN START-->
     <div class="content">
     <div class="grid" class="grid span4" style="width:1000px; margin: 0 auto; margin-top:30px; padding-top:30px;">
                <div class="grid" class="grid span4" style="height:60px; margin:0px auto; margin-top:-20px; margin-left:10px; margin-right:10px; padding:20px;">
                    <div id="DivAv" style="float:left; width:60px; height:100%; ">
                        <img src="<?php echo $avat; ?>" style="width:50px; margin:0px; float:left; font-size:18px;  border:1px solid #b0b0b0;"/>
                    </div>   
                    <div style="float:left; width:360px; height:100%; ">
                        <div id="NombreComp" style="width:100%; color: rgba(34,174,255,1); font: bold 22px Arial, Helvetica, sans-serif; cursor: auto;"><?php echo $MedUserTitle.' '.$MedUserName;?> <?php echo $MedUserSurname;?></div>
                       <span id="NombreComp" style="color: rgba(84,188,0,1); font: bold 16px Arial, Helvetica, sans-serif; cursor: auto; margin-top:-5px;">CENTER:  <?php if ($MedGroupName<' ') echo 'single license'; else echo $MedGroupName ;?></span>
                       <div style="width:100%; margin-top:5px; "></div>
	                       <?php if ($privilege==1)
	                       		{
	                       		echo '<div style="float:left; width:150px; height:20px; text-align:center; border:1px solid darkgrey; border-radius:5px;display:table;margin:0px;background-color: silver;">';
	                       		echo '<span id="TypeAccount" style="color: white; font: bold 12px Arial, Helvetica, sans-serif; cursor: auto; display:table-cell; vertical-align:middle; padding-top:1px;">PREMIUM ACCOUNT</span>';
	                       		echo '</div>';
	                       		$VoidLink = '';
	                       		}
						   ?>
	                       <?php if ($privilege==2)
	                       		{
	                       		echo '<div style="float:left;  width:150px; height:20px; text-align:center; border:1px solid goldenrod; border-radius:5px;display:table;margin:0px;background-color: gold;">';
	                       		echo '<span id="TypeAccount" style="color: grey; font: bold 12px Arial, Helvetica, sans-serif; cursor: auto; display:table-cell; vertical-align:middle; padding-top:1px;">FREE ACCOUNT</span>';
	                       		echo '</div>';
	                       		echo '<div style=" float:left; margin-left:20px;  width:100px; height:20px; text-align:center; border:1px solid goldenrod; border-radius:5px; display:table; margin:0px;background-color: gold;">';
	                       		echo '<span id="TypeAccount" style="color: grey; font: bold 12px Arial, Helvetica, sans-serif; cursor: auto; display:table-cell; vertical-align:middle; padding-top:1px;">>>> Upgrade</span>';
	                       		echo '</div>';
	                       		//echo '<input type="button" class="btn btn-primary" value="Upgrade NOW" style="text-shadow:1px 1px black; margin-left:80px; margin-top:-10px;" id="">';
	                       		$VoidLink = '1';
	                       		}
						   ?>

                    </div>    
                    <div style="float:left; width:200px; height:100%;">
                    		
                                <span  id="NumMsgDr<?php echo $VisibleDr?><?php echo $VoidLink?>" style="color:rgba(90,170,255,<?php echo $OpaDr?>); margin-left:10px;" title="Doctors uploaded information in the past 30 days"><i class="icon-envelope icon-3x"></i></span>
                                <span style="visibility:<?php echo $VisibleDr?>; background-color:red;" class="H2MBaloon" ><?php echo $NumMessagesDr?></span>

                                <span id="NumMsgUser<?php echo $VisibleUser?><?php echo $VoidLink?>" style="color:rgba(105,188,0,<?php echo $OpaUser?>); margin-left:10px;" title="Doctors uploaded information in the past 30 days"><i class="icon-envelope icon-3x"></i></span>
                                <span style="visibility:<?php echo $VisibleUser?>; background-color:black;" class="H2MBaloon" ><?php echo $NumMessagesUser?></span>
							
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
         </style>
         <!--xH2MTRAY-->
         <div class="H2MTray">
            <div class="H2MTSCont">
            <div class="H2MTrayScroll">
	            <div class="H2MInText" style="width:270px;">
	                <div style="width:40%; float:right; margin-top:10px; text-align:right; padding-right:20px;"><i class="icon-bullhorn icon-2x"></i></div>
	                <span style="font-size:10px;">H2M messaging system</span>
	                <div style="width:50%; padding-top:-10px;"></div>
					<span style="">Notification tray</span>
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

                    </div>   
                        
        
             </div>
        
             <div class="grid" class="grid span4" style="height:400px; margin: 10px auto; margin-top:10px; margin-left:10px; margin-right:10px; padding:20px; padding-left:50px;">
  				
  					
  					<div style="width:100%;">	               
                      <p aria-hidden="true" class="iconH-patientIndiv img-rounded" style="margin-top:0px; float:left; font-size:24px; color:#54bc00;"></p>
                      <p style="float:left; color:grey; font: 16px Arial, Helvetica, sans-serif; cursor: auto; margin-top:3px; margin-left:10px; "> You have <?php echo $Num_Own_Patients;?> patients.</p>
  					</div>  
				    <div class="clearfix" style="margin-bottom:0px;"></div>			 
   					<div style="width:100%;">	               
                      <p aria-hidden="true" class="iconH-patientGroup img-rounded" style="margin-top:0px; float:left; font-size:24px; color:#54bc00;"></p>
                      <p style="float:left; color:grey; font:  16px Arial, Helvetica, sans-serif; cursor: auto; margin-top:3px; margin-left:10px; "> Your office has <?php echo $Num_Group_Patients;?> patients in total.</p>
  					</div>  
				    <div class="clearfix" style="margin-bottom:0px;"></div>			 
   					<div style="width:100%;">	               
                      <p aria-hidden="true" class="iconH-Referred3 img-rounded" style="margin-top:0px; float:left; font-size:24px; color:#22aeff;"></p>
                      <p style="float:left; color:grey; font:  16px Arial, Helvetica, sans-serif; cursor: auto; margin-top:3px; margin-left:10px; "> <?php echo $Number_Doctors_Referred2Me; ?> doctors are referring <?php echo $Number_Patients_IReferred; ?> patients to you.</p>
  					</div>  


				<div class="clearfix" style="margin-bottom:30px;"></div>			 
                 
               <!-- 
                 
                 <div id="DivDoctors" class="H2MBox" style="color:rgba(34,174,255,1);" href="medicalConnections.php">
                    <div class="H2MTitle" style="background-color:rgba(34,174,255,1);">
                        <div class="H2MTitleA">
                            <i class="icon-stethoscope"></i>
                        </div>
                        <div class="H2MTitleB">
                            Doctors
                        </div>
                    </div> 
                     <div class="H2MSet1" style="background-color:rgba(34,174,255,0.05);">
                         <div class="H2MSet1A">
                            <i class="icon-paste"></i>
                            <span class="H2MBigNumber"><?php echo $Number_Doctors_IReferred; ?></span>
                         </div>
                         <div  class="H2MTextContent">
                            Referrals 
                         </div>
                     </div> 
                     <div style="width:100%; height:15%; background-color:rgba(34,174,255,0.10);">
                         <div class="H2MSet1A">
                            <i class="icon-paste"></i>
                            <span class="H2MBigNumber"><?php echo $Number_Doctors_Referred2Me; ?></span>
                         </div>
                         <div  class="H2MTextContent">
                            Dr. referring to me 
                         </div>
                     </div> 
                     <div class="H2MSet1" style="background-color:rgba(34,174,255,0.15);">
                         <div class="H2MSet1A">
                            <i class="icon-paste"></i>
                            <span class="H2MBigNumber"><?php echo $Number_Patients_IReferred; ?></span>
                         </div>
                         <div  class="H2MTextContent">
                            Patients referred 
                         </div>
                     </div> 
                     <div class="H2MSet1" style="background-color:rgba(34,174,255,0.30);">
                         <div class="H2MSet1A">
                            <i class="icon-paste"></i>
                            <span class="H2MBigNumber"><?php echo sprintf("%+d",($Number_Patients_Referred2Me-$Number_Patients_IReferred)); ?></span>
                         </div>
                         <div  class="H2MTextContent">
                            Ratio Referred In/Out 
                         </div>
                     </div> 
                     <div class="H2MFooter" style="">
                        <div class="H2MTextFooter" style="">
                            Connect <i class="icon-chevron-right"></i>
                        </div>
                     </div> 
                </div>
                 
                 <div id="DivPatients" class="H2MBox" style="color:rgba(84,188,0,1);">
                    <div class="H2MTitle" style="background-color:rgba(84,188,0,1);">
                        <div class="H2MTitleA">
                            <i class="iconH-patientGroup img-rounded"></i>
                        </div>
                        <div class="H2MTitleB">
                            Patients
                        </div>
                    </div> 
                     <div class="H2MSet1" style="background-color:rgba(84,188,0,0.05);">
                         <div class="H2MSet1A">
                            <i class="icon-paste"></i>
                            <span class="H2MBigNumber" style="color:rgba(84,188,0,1);"><?php echo $NumRepViewUser ?></span>
                         </div>
                         <div  class="H2MTextContent">
                            Reports Viewed 
                         </div>
                     </div> 
                     <div class="H2MSet1" style="background-color:rgba(84,188,0,0.10);">
                         <div class="H2MSet1A">
                            <i class="icon-paste"></i>
                            <span class="H2MBigNumber" style="color:rgba(84,188,0,1);"><?php echo $NumActiveUsers ?></span>
                         </div>
                         <div  class="H2MTextContent">
                            Active Users 
                         </div>
                     </div> 
                     <div class="H2MSet1" style="background-color:rgba(84,188,0,0.15);">
                         <div class="H2MSet1A">
                            <i class="icon-paste"></i>
                            <span class="H2MBigNumber" style="color:rgba(84,188,0,1);"><?php echo $NumRepUpUser ?></span>
                         </div>
                         <div  class="H2MTextContent">
                            Uploaded by other Drs 
                         </div>
                     </div> 
                     <div style="width:100%; height:15%; background-color:rgba(84,188,0,0.30);">
                     </div> 
                     <div class="H2MFooter" >
                        <div class="H2MTextFooter" style="">
                            Engage <i class="icon-chevron-right"></i>
                        </div>
                     </div> 
                </div>

                  <div id="DivEMR" class="H2MBox" style="color:rgba(231,175,95,1);">
                    <div class="H2MTitle" style="background-color:rgba(231,175,95,1);">
                        <div class="H2MTitleA">
                            <i class="icon-gears"></i>
                        </div>
                        <div class="H2MTitleB">
                            EMR
                        </div>
                    </div> 
                     <div class="H2MSet1" style="background-color:rgba(231,175,95,0.05); color:inherit;">
                         <div class="H2MSet1A" style="color:inherit;">
                            <i class="icon-paste" style="color:inherit;"></i>
                            <span class="H2MBigNumber" style="color:inherit;"><?php echo $Num_Group_Reports;?></span>
                         </div>
                         <div  class="H2MTextContent">
                            Charts created 
                         </div>
                     </div> 
                     <div style="width:100%; height:15%; background-color:rgba(231,175,95,0.10);">
                         <div class="H2MSet1A">
                            <i class="icon-paste"></i>
                            <span class="H2MBigNumber" style="color:rgba(231,175,95,0,1);"><?php echo $Pat_App_Week; ?></span>
                         </div>
                         <div  class="H2MTextContent">
                            Patients this week 
                         </div>
                     </div> 
                     <div class="H2MSet1" style="background-color:rgba(231,175,95,0.15);">
                         <div class="H2MSet1A">
                            <i class="icon-paste"></i>
                            <span class="H2MBigNumber" style="color:rgba(231,175,95,0,1);"><?php echo $Num_Own_Patients;?></span>
                         </div>
                         <div  class="H2MTextContent">
                            Own patients 
                         </div>
                     </div> 
                     <div style="width:100%; height:15%; background-color:rgba(231,175,95,0.30);">
                         <div class="H2MSet1A">
                            <i class="icon-paste"></i>
                            <span class="H2MBigNumber" style="color:rgba(231,175,95,0,1);"><?php echo $Num_Group_Patients;?></span>
                         </div>
                         <div  class="H2MTextContent">
                            Group patients 
                         </div>
                     </div> 
                     <div class="H2MFooter" >
                        <div class="H2MTextFooter" style="">
                            Manage <i class="icon-chevron-right"></i>
                        </div>
                     </div> 
                </div>
                
                -->
                 
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
    <script type="text/javascript" >
    
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






    $(document).ready(function() {
	
	
	setInterval(function() {
		
		CheckTray();
			
      }, 10000);

	
	
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

	$('#NumMsgDr').bind('click mousedown keydown', function(event) {
		window.location.replace('medicalConnections.php');
    });

	$('#NumMsgUser').bind('click mousedown keydown', function(event) {
		window.location.replace('PatientNetwork.php');
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
                                  
    	    getLifePines(serviceURL);
    	    var longit = Object.keys(pines).length;	
    	    */
    	    //alert (longit);
    	     /*KYLE
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
  
     /*
     $('#TablaPac').bind('click', function() {
          	
          	var NombreEnt = $('#NombreEnt').val();
            var PasswordEnt = $('#PasswordEnt').val();
                                   
          	window.location.replace('patientdetail.php?Nombre='+NombreEnt+'&Password='+PasswordEnt);

//      alert($(this).text());
      });
*/
/*KYLE
     $('#TablaPacCOMP').bind('click', function() {
      alert($(this).text());
      });

	    
 	$('#datatable_1').dataTable( {
		"bProcessing": true,
		"bDestroy": true,
		"bRetrieve": true,
		"sAjaxSource": "getBLOCKS.php"
	} );
    
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

