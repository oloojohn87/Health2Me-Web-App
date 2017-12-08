<?php
/*KYLE
session_start();
 require("environment_detail.php");
 require("PasswordHash.php");
 require_once("displayExitClass.php");
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];
$NombreEnt = $_SESSION['Nombre'];
$PasswordEnt = $_SESSION['Password'];
$MEDID = $_SESSION['MEDID'];
$Acceso = $_SESSION['Acceso'];
$privilege=$_SESSION['Previlege'];
// echo $_GET['month'];
// echo $_GET['year'];
if(isset($_GET['month']))$month =$_GET['month'];
if(isset($_GET['year']))$year = $_GET['year'];
// echo $month;
// echo $year;
if ($Acceso != '23432')
{
$exit_display = new displayExitClass();

$exit_display->displayFunction(1);
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
	$MedUserEmail= (htmlspecialchars($row['IdMEDEmail']));
	$MedUserName = (htmlspecialchars($row['Name']));
	$MedUserSurname = (htmlspecialchars($row['Surname']));
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
        $MedGroupName = (htmlspecialchars($rowGN['Name']));
        $MedGroupAddress = (htmlspecialchars($rowGN['Address']));
        $MedGroupZIP = (htmlspecialchars($rowGN['ZIP']));
        $MedGroupCity = (htmlspecialchars($rowGN['City']));
        $MedGroupState = (htmlspecialchars($rowGN['State']));
        $MedGroupCountry = (htmlspecialchars($rowGN['Country']));
        
        //Get Number of users attatched to this group
        $resultGN2 = mysql_query("SELECT * FROM doctorsgroups where idGroup='$MedGroupId'");
        $countGN2=mysql_num_rows($resultGN2);
        $UsersInGroup = $countGN2;
        }
    
    // Get information about the number of appointments for this user 
    //FOR GROUPS: $sql="SELECT * FROM events where userid=".$MEDID." or userid in (SELECT iddoctor from doctorsgroups where idgroup in (select idgroup from doctorsgroups where iddoctor=".$userid."))";
    // $sql="SELECT * FROM events where userid=".$MEDID;
    // $resultS = mysql_query($sql);
	if(isset($year) &&isset($month))
	{
		$dateStart = date($year."-".$month."-01 00:00:00");
		$dateEnd = date($year."-".$month."-30 00:00:00");
		$resultS = mysql_query("SELECT * FROM events where userid='$MEDID' and start between '$dateStart' and '$dateEnd'");	
	}
	else if(isset($year))
	{
		$dateStart = date($year."-01-01 00:00:00");
		$dateEnd = date($year."-12-30 00:00:00");
		$resultS = mysql_query("SELECT * FROM events where userid='$MEDID' and start between '$dateStart' and '$dateEnd'");	
	}
	else
	{
		$resultS = mysql_query("SELECT * FROM events where userid='$MEDID'");
	}
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
    //$resultPRE = mysql_query("SELECT distinct(IdUs) FROM doctorslinkusers WHERE IdPin IS NULL and (Idmed='$IdMed' or IdMED IN (Select distinct(idDoctor) from doctorsgroups where idGroup IN (select idGroup from doctorsgroups where idDoctor= '$IdMed'))) and Estado IN (2,null)");
	if(isset($year) &&isset($month))
	{
		$dateStart = date($year."-".$month."-01 00:00:00");
		$dateEnd = date($year."-".$month."-30 00:00:00");
		$resultPRE = mysql_query("SELECT distinct(IdUs) FROM doctorslinkusers WHERE IdPin IS NULL and (Idmed='$IdMed' or IdMED IN (Select distinct(idDoctor) from doctorsgroups where idGroup IN (select idGroup from doctorsgroups where idDoctor= '$IdMed'))) and Estado IN (2,null) and fecha between '$dateStart' and '$dateEnd'");	
		//echo "SELECT distinct(IdUs) FROM doctorslinkusers WHERE IdPin IS NULL and (Idmed='$IdMed' or IdMED IN (Select distinct(idDoctor) from doctorsgroups where idGroup IN (select idGroup from doctorsgroups where idDoctor= '$IdMed'))) and Estado IN (2,null) and fecha between '$dateStart' and '$dateEnd'";
	}
	else if(isset($year))
	{
		$dateStart = date($year."-01-01 00:00:00");
		$dateEnd = date($year."-12-30 00:00:00");
		$resultPRE = mysql_query("SELECT distinct(IdUs) FROM doctorslinkusers WHERE IdPin IS NULL and (Idmed='$IdMed' or IdMED IN (Select distinct(idDoctor) from doctorsgroups where idGroup IN (select idGroup from doctorsgroups where idDoctor= '$IdMed'))) and Estado IN (2,null) and fecha between '$dateStart' and '$dateEnd'");	
		//echo "SELECT distinct(IdUs) FROM doctorslinkusers WHERE IdPin IS NULL and (Idmed='$IdMed' or IdMED IN (Select distinct(idDoctor) from doctorsgroups where idGroup IN (select idGroup from doctorsgroups where idDoctor= '$IdMed'))) and Estado IN (2,null)) and fecha between '$dateStart' and '$dateEnd'";
	}
	else
	{
		$resultPRE = mysql_query("SELECT distinct(IdUs) FROM doctorslinkusers WHERE IdPin IS NULL and (Idmed='$IdMed' or IdMED IN (Select distinct(idDoctor) from doctorsgroups where idGroup IN (select idGroup from doctorsgroups where idDoctor= '$IdMed'))) and Estado IN (2,null)");
	}
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
    //$resultPRE = mysql_query("SELECT distinct(IdUs) FROM doctorslinkusers WHERE IdPin IS NULL and Idmed='$IdMed' and Estado IN (2,null)");
	if(isset($year) &&isset($month))
	{
		$dateStart = date($year."-".$month."-01 00:00:00");
		$dateEnd = date($year."-".$month."-30 00:00:00");
		$resultPRE = mysql_query("SELECT distinct(IdUs) FROM doctorslinkusers WHERE IdPin IS NULL and Idmed='$IdMed' and Estado IN (2,null) and fecha between '$dateStart' and '$dateEnd'");	
	}
	else if(isset($year))
	{
		$dateStart = date($year."-01-01 00:00:00");
		$dateEnd = date($year."-12-30 00:00:00");
		$resultPRE = mysql_query("SELECT distinct(IdUs) FROM doctorslinkusers WHERE IdPin IS NULL and Idmed='$IdMed' and Estado IN (2,null) and fecha between '$dateStart' and '$dateEnd'");	
	}
	else
	{
		$resultPRE = mysql_query("SELECT distinct(IdUs) FROM doctorslinkusers WHERE IdPin IS NULL and Idmed='$IdMed' and Estado IN (2,null)");
	}
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
    //$result = mysql_query("SELECT id FROM doctorslinkdoctors WHERE IdMED = '$MEDID' ");
	if(isset($year) &&isset($month))
	{
		$dateStart = date($year."-".$month."-01 00:00:00");
		$dateEnd = date($year."-".$month."-30 00:00:00");
		$result = mysql_query("SELECT id FROM doctorslinkdoctors WHERE IdMED = '$MEDID' and fecha between '$dateStart' and '$dateEnd'");	
	}
	else if(isset($year))
	{
		$dateStart = date($year."-01-01 00:00:00");
		$dateEnd = date($year."-12-30 00:00:00");
		$result = mysql_query("SELECT id FROM doctorslinkdoctors WHERE IdMED = '$MEDID' and fecha between '$dateStart' and '$dateEnd'");	
	}
	else
	{
		$result = mysql_query("SELECT id FROM doctorslinkdoctors WHERE IdMED = '$MEDID'");
	}
    $Number_Patients_IReferred = mysql_num_rows($result);
    //$result = mysql_query("SELECT distinct(IdMED2) FROM doctorslinkdoctors WHERE IdMED = '$MEDID' ");
	if(isset($year) &&isset($month))
	{
		$dateStart = date($year."-".$month."-01 00:00:00");
		$dateEnd = date($year."-".$month."-30 00:00:00");
		$result = mysql_query("SELECT distinct(IdMED2) FROM doctorslinkdoctors WHERE IdMED = '$MEDID' and fecha between '$dateStart' and '$dateEnd'");	
	}
	else if(isset($year))
	{
		$dateStart = date($year."-01-01 00:00:00");
		$dateEnd = date($year."-12-30 00:00:00");
		$result = mysql_query("SELECT distinct(IdMED2) FROM doctorslinkdoctors WHERE IdMED = '$MEDID' and fecha between '$dateStart' and '$dateEnd'");	
	}
	else
	{
		$result = mysql_query("SELECT distinct(IdMED2) FROM doctorslinkdoctors WHERE IdMED = '$MEDID'");
	}
    $Number_Doctors_IReferred = mysql_num_rows($result);
    
    //$result = mysql_query("SELECT id FROM doctorslinkdoctors WHERE IdMED2 = '$MEDID' ");
	if(isset($year) &&isset($month))
	{
		$dateStart = date($year."-".$month."-01 00:00:00");
		$dateEnd = date($year."-".$month."-30 00:00:00");
		$result = mysql_query("SELECT id FROM doctorslinkdoctors WHERE IdMED2 = '$MEDID' and fecha between '$dateStart' and '$dateEnd'");	
	}
	else if(isset($year))
	{
		$dateStart = date($year."-01-01 00:00:00");
		$dateEnd = date($year."-12-30 00:00:00");
		$result = mysql_query("SELECT id FROM doctorslinkdoctors WHERE IdMED2 = '$MEDID' and fecha between '$dateStart' and '$dateEnd'");	
	}
	else
	{
		$result = mysql_query("SELECT id FROM doctorslinkdoctors WHERE IdMED2 = '$MEDID'");
	}
    $Number_Patients_Referred2Me = mysql_num_rows($result);
    //$result = mysql_query("SELECT distinct(IdMED) FROM doctorslinkdoctors WHERE IdMED2 = '$MEDID' ");
	 if(isset($year) &&isset($month))
	{
		$dateStart = date($year."-".$month."-01 00:00:00");
		$dateEnd = date($year."-".$month."-30 00:00:00");
		$result = mysql_query("SELECT distinct(IdMED) FROM doctorslinkdoctors WHERE IdMED2 = '$MEDID' and fecha between '$dateStart' and '$dateEnd'");	
	}
	else if(isset($year))
	{
		$dateStart = date($year."-01-01 00:00:00");
		$dateEnd = date($year."-12-30 00:00:00");
		$result = mysql_query("SELECT distinct(IdMED) FROM doctorslinkdoctors WHERE IdMED2 = '$MEDID' and fecha between '$dateStart' and '$dateEnd'");	
	}
	else
	{
		$result = mysql_query("SELECT distinct(IdMED) FROM doctorslinkdoctors WHERE IdMED2 = '$MEDID'");
	}
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
	//$result2 = mysql_query("SELECT * FROM message_infrastructureuser WHERE sender_id = '$MEDID' AND status='new' ");
	 if(isset($year) &&isset($month))
	{
		$dateStart = date($year."-".$month."-01 00:00:00");
		//echo $dateStart;
		$dateEnd = date($year."-".$month."-30 00:00:00");
		//echo "SELECT * FROM message_infrastructureuser WHERE sender_id = '$MEDID' AND status='new' and fecha between '$dateStart' and '$dateEnd'";
		$result2 = mysql_query("SELECT * FROM message_infrastructureuser WHERE sender_id = '$MEDID' AND status='new' and fecha between '$dateStart' and '$dateEnd'");	
	}
	else if(isset($year))
	{
		$dateStart = date($year."-01-01 00:00:00");
		$dateEnd = date($year."-12-30 00:00:00");
		$result2 = mysql_query("SELECT * FROM message_infrastructureuser WHERE sender_id = '$MEDID' AND status='new' and fecha between '$dateStart' and '$dateEnd'");	
	}
	else
	{
		$result2 = mysql_query("SELECT * FROM message_infrastructureuser WHERE sender_id = '$MEDID' AND status='new' ");
	}
	$count2=mysql_num_rows($result2);
	$NumMessagesUser = $count2;
	if ($NumMessagesUser>0) {$VisibleUser = ''; $OpaUser='1';} else {$VisibleUser = 'hidden';$OpaUser='0.3';}
 	// Get Number of Messages from doctors
	//$result2 = mysql_query("SELECT * FROM message_infrasture WHERE receiver_id = '$MEDID' AND status='new' ");
	 if(isset($year) &&isset($month))
	{
		$dateStart = date($year."-".$month."-01 00:00:00");
		$dateEnd = date($year."-".$month."-30 00:00:00");
		$result2 = mysql_query("SELECT * FROM message_infrasture WHERE receiver_id = '$MEDID' AND status='new' and fecha between '$dateStart' and '$dateEnd'");	
	}
	else if(isset($year))
	{
		$dateStart = date($year."-01-01 00:00:00");
		$dateEnd = date($year."-12-30 00:00:00");
		$result2 = mysql_query("SELECT * FROM message_infrasture WHERE receiver_id = '$MEDID' AND status='new' and fecha between '$dateStart' and '$dateEnd'");	
	}
	else
	{
		$result2 = mysql_query("SELECT * FROM message_infrasture WHERE receiver_id = '$MEDID' AND status='new' ");
	}
	$count2=mysql_num_rows($result2);
	$NumMessagesDr = $count2;
	if ($NumMessagesDr>0) {$VisibleDr = ''; $OpaDr='1';} else {$VisibleDr = 'hidden'; $OpaDr='0.3';}
   
    // Get Report Activity for this doctor
    // This retrieves only the last 30 days
	$MyGroup = $MedGroupId;

/*
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

*/
    $NumRepViewUser = 0;
    $NumRepViewDr = 0;
    $NumRepUpUser = 0;
    $NumRepUpDr = 0;
    $NumActiveUsers = 0;
	$referredRatio = sprintf("%+d",($Number_Patients_Referred2Me-$Number_Patients_IReferred));
    echo $Number_Doctors_IReferred."|".$Number_Doctors_Referred2Me."|".$Number_Patients_IReferred."|".$referredRatio."|".$Num_Group_Reports."|".$Pat_App_Week."|".$Num_Own_Patients."|".$Num_Group_Patients;
}
else
{
$exit_display = new displayExitClass();

$exit_display->displayFunction(2);
die;
}

?>