<?php
 error_reporting(E_ALL);
 ini_set("display_errors", 1);
 require("environment_detail.php");
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];

$tbl_name="doctors"; // Table name

$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }	

$UserID = $_GET['User'];

$AdminData = 0;
$PastDx = 0;
$cadena = '';
$counter1 = 0;
$latest_update = '1975-01-01 00:00:00';
$doctor_signed = -1;
$member_signed = -1;

/*Below new variables added by Pallab
$doctor_previous = '';
$doctor_updateTime = '1975-01-01 00:00:00';*/

$result = $con->prepare("SELECT * FROM basicemrdata WHERE IdPatient = ? ORDER BY IdPatient DESC LIMIT 1");
$result->bindValue(1, $UserID, PDO::PARAM_INT);
$result->execute();

while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
if(isset($row['address'])){
$hold_address = $row['address'];
}else{
$hold_address = '';
}
	if ((htmlspecialchars($row['DOB'])) > '') $AdminData++;
	if ((htmlspecialchars($hold_address)) > '') $AdminData++;
	if ((htmlspecialchars($row['City'])) > '') $AdminData++;
	if ((htmlspecialchars($row['state'])) > '') $AdminData++;
	if ((htmlspecialchars($row['zip'])) > '') $AdminData++;
	if ((htmlspecialchars($row['phone'])) > '') $AdminData++;
	if ((htmlspecialchars($row['insurance'])) > '') $AdminData++;
}
    
$latest_update = $row['latest_update'];
if($row['doctor_signed'] != -1) {
    $doctor_signed = $row['doctor_signed'];
    
}
else {
    $latest_update = $row['latest_update'];
    
    $result = $con->prepare("SELECT IdUsFIXEDNAME FROM usuarios WHERE Identif = ?");
    $result->bindValue(1, $UserID, PDO::PARAM_INT);
    $result->execute();
      
    if($result->rowCount() > 0) {
        $row = $result->fetch(PDO::FETCH_ASSOC);
        $member_signed = $row['IdUsFIXEDNAME'];   
      }    
}


// Admin Data Retrieval [0] ------------------------------------------------------------	
if ($counter1>0) $cadena.=',';    
$cadena.='
    {
        "Category": "basicemrdata",
        "Data":"'.$AdminData.'",
        "doctor_signed":"'.$doctor_signed.'",
        "member_signed":"'.$member_signed.'",
        "latest_update":"'.$latest_update.'"
        }';    
    
$counter1++;


// PAST DIAGNOSTICS Retrieval [1] ------------------------------------------------------------	
$latest_update = '1975-01-01 00:00:00';
$doctor_signed = -1;
$member_signed = -1;
$AdminData = 0;


//Start of new code by Pallab
$result = $con->prepare("select * from p_diagnostics where idpatient = ? order by id DESC LIMIT 1");
$result->bindValue(1, $UserID, PDO::PARAM_INT);
$result->execute();
$row = $result->fetch(PDO::FETCH_ASSOC);
    
$diag_count = $result->rowCount();

$latest_update = $row['latest_update'];
if($row['doctor_signed'] != -1) {
    $doctor_signed = $row['doctor_signed'];
    
}
else { //WHen a patient edited this area
      $latest_update = $row['latest_update'];
    
      $result = $con->prepare("SELECT IdUsFIXEDNAME FROM usuarios WHERE Identif = ?");
      $result->bindValue(1, $UserID, PDO::PARAM_INT);
      $result->execute();
      
      if($result->rowCount() > 0)
      {
        $row = $result->fetch(PDO::FETCH_ASSOC);
        $member_signed = $row['IdUsFIXEDNAME'];   
      }    
}


if ($counter1>0) $cadena.=',';    

//Two new parameters added by Pallab to cadena

    $cadena.='
    {
        "Category": "p_diagnosis",
        "Data":"'.$diag_count.'",
        "doctor_signed":"'.$doctor_signed.'",
        "member_signed":"'.$member_signed.'",
        "latest_update":"'.$latest_update.'"
        }'; 

$counter1++;

// MEDICATION Retrieval [2] ------------------------------------------------------------	
$latest_update = '1975-01-01 00:00:00';
$doctor_signed = -1;
$AdminData = 0;
$member_signed = -1;


//Start of new code by Pallab
$result = $con->prepare("select * from p_medication where idpatient = ? order by id DESC LIMIT 1");
$result->bindValue(1, $UserID, PDO::PARAM_INT);
$result->execute();
$row = $result->fetch(PDO::FETCH_ASSOC);
    
$meds_count = $result->rowCount();

$latest_update = $row['latest_update'];
if($row['doctor_signed'] != -1) {
    $doctor_signed = $row['doctor_signed'];
    
}
else { //WHen a patient edited this area
    $latest_update = $row['latest_update'];
    
      $result = $con->prepare("SELECT IdUsFIXEDNAME FROM usuarios WHERE Identif = ?");
      $result->bindValue(1, $UserID, PDO::PARAM_INT);
      $result->execute();
      
      if($result->rowCount() > 0)
      {
        $row = $result->fetch(PDO::FETCH_ASSOC);
        $member_signed = $row['IdUsFIXEDNAME'];   
      }    
}


if ($counter1>0) $cadena.=',';
$cadena.='
{
    "Category": "p_medication",
    "Data":"'.$meds_count.'",
    "doctor_signed":"'.$doctor_signed.'",
    "member_signed":"'.$member_signed.'",
    "latest_update":"'.$latest_update.'"
    }'; 
   
$counter1++;

// IMMUNO  Retrieval [3] ------------------------------------------------------------	
$latest_update = '1975-01-01 00:00:00';
$doctor_signed = -1;
$AdminData = 0;
$member_signed = -1;


//Start of new code by Pallab
$result = $con->prepare("select * from p_immuno where idpatient = ? AND AllerName is null order by id DESC LIMIT 1");
$result->bindValue(1, $UserID, PDO::PARAM_INT);
$result->execute();
$row = $result->fetch(PDO::FETCH_ASSOC);
    
$immu_count = $result->rowCount();

$latest_update = $row['latest_update'];
if($row['doctor_signed'] != -1) {
    $doctor_signed = $row['doctor_signed'];
    
}
else { //WHen a patient edited this area
    $latest_update = $row['latest_update'];
    
      $result = $con->prepare("SELECT IdUsFIXEDNAME FROM usuarios WHERE Identif = ?");
      $result->bindValue(1, $UserID, PDO::PARAM_INT);
      $result->execute();
      
      if($result->rowCount() > 0)
      {
        $row = $result->fetch(PDO::FETCH_ASSOC);
        $member_signed = $row['IdUsFIXEDNAME'];   
      }    
}


if ($counter1>0) $cadena.=',';
$cadena.='
{
    "Category": "p_immuno",
    "Data":"'.$immu_count.'",
    "doctor_signed":"'.$doctor_signed.'",
    "member_signed":"'.$member_signed.'",
    "latest_update":"'.$latest_update.'"
    }'; 
   
$counter1++;

// FAMILY HISTORY Retrieval [4] ------------------------------------------------------------	
$latest_update = '1975-01-01 00:00:00';
$doctor_signed = -1;
$AdminData = 0;
$member_signed = -1;

//Start of new code by Pallab
$result = $con->prepare("select * from p_family where idpatient = ? order by id DESC LIMIT 1");
$result->bindValue(1, $UserID, PDO::PARAM_INT);
$result->execute();
$row = $result->fetch(PDO::FETCH_ASSOC);
    
$fam_count = $result->rowCount();

$latest_update = $row['latest_update'];
if($row['doctor_signed'] != -1) {
    $doctor_signed = $row['doctor_signed'];
    
}
else { //WHen a patient edited this area
    $latest_update = $row['latest_update'];
    
      $result = $con->prepare("SELECT IdUsFIXEDNAME FROM usuarios WHERE Identif = ?");
      $result->bindValue(1, $UserID, PDO::PARAM_INT);
      $result->execute();
      
      if($result->rowCount() > 0)
      {
        $row = $result->fetch(PDO::FETCH_ASSOC);
        $member_signed = $row['IdUsFIXEDNAME'];   
      }    
}

if ($counter1>0) $cadena.=',';
$cadena.='
{
    "Category": "p_family",
    "Data":"'.$fam_count.'",
    "doctor_signed":"'.$doctor_signed.'",
    "member_signed":"'.$member_signed.'",
    "latest_update":"'.$latest_update.'"
    }'; 
 
$counter1++;

// HABITS Retrieval [5] ------------------------------------------------------------	
$latest_update = '1975-01-01 00:00:00';
$doctor_signed = -1;
$AdminData = 0;
$member_signed = -1;

//Start of new code by Pallab
$result = $con->prepare("select * from p_habits where idpatient = ? order by id DESC LIMIT 1");
$result->bindValue(1, $UserID, PDO::PARAM_INT);
$result->execute();
$row = $result->fetch(PDO::FETCH_ASSOC);
    
$hab_count = $result->rowCount();

$latest_update = $row['latest_update'];
if($row['doctor_signed'] != -1) {
    $doctor_signed = $row['doctor_signed'];
    
}
else { //WHen a patient edited this area
    $latest_update = $row['latest_update'];
    
      $result = $con->prepare("SELECT IdUsFIXEDNAME FROM usuarios WHERE Identif = ?");
      $result->bindValue(1, $UserID, PDO::PARAM_INT);
      $result->execute();
      
      if($result->rowCount() > 0)
      {
        $row = $result->fetch(PDO::FETCH_ASSOC);
        $member_signed = $row['IdUsFIXEDNAME'];   
      }    
}

if ($counter1>0) $cadena.=',';
$cadena.='
{
    "Category": "p_habits",
    "Data":"'.$hab_count.'",
    "doctor_signed":"'.$doctor_signed.'",
    "member_signed":"'.$member_signed.'",
    "latest_update":"'.$latest_update.'"
    }'; 

$counter1++;


// ALLERGY Retrieval [6] ------------------------------------------------------------	
$latest_update = '1975-01-01 00:00:00';
$doctor_signed = -1;
$AdminData = 0;
$member_signed = -1;

//Start of new code by Pallab
$result = $con->prepare("select * from p_immuno where idpatient = ? and AllerName is not null order by id DESC LIMIT 1");
$result->bindValue(1, $UserID, PDO::PARAM_INT);
$result->execute();
$row = $result->fetch(PDO::FETCH_ASSOC);
    
$aller_count = $result->rowCount();

$latest_update = $row['latest_update'];
if($row['doctor_signed'] != -1) {
    $doctor_signed = $row['doctor_signed'];
    
}
else { //WHen a patient edited this area
    $latest_update = $row['latest_update'];
    
      $result = $con->prepare("SELECT IdUsFIXEDNAME FROM usuarios WHERE Identif = ?");
      $result->bindValue(1, $UserID, PDO::PARAM_INT);
      $result->execute();
      
      if($result->rowCount() > 0)
      {
        $row = $result->fetch(PDO::FETCH_ASSOC);
        $member_signed = $row['IdUsFIXEDNAME'];   
      }    
}

if ($counter1>0) $cadena.=',';
$cadena.='
{
    "Category": "Allergy",
    "Data":"'.$aller_count.'",
    "doctor_signed":"'.$doctor_signed.'",
    "member_signed":"'.$member_signed.'",
    "latest_update":"'.$latest_update.'"
    }'; 

$counter1++;

//print_r($cadena);

$encode = json_encode($cadena);
echo '{"items":['.$cadena.']}'; 

?>
