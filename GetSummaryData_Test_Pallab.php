<?php
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

$result = $con->prepare("SELECT * FROM basicemrdata WHERE IdPatient = ?");
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
	
	$doctor_signedP = $row['doctor_signed'];
	$latest_updateP = $row['latest_update'];
	if (strtotime($latest_updateP) > strtotime($latest_update)) { $latest_update = $latest_updateP; $doctor_signed = $doctor_signedP;}
	
};

// Admin Data Retrieval [0] ------------------------------------------------------------	
if ($counter1>0) $cadena.=',';    
$cadena.='
    {
        "Data":"'.$AdminData.'",
        "doctor_signed":"'.$doctor_signed.'",
        "latest_update":"'.$latest_update.'"
        }';    
$counter1++;


// PAST DIAGNOSTICS Retrieval [1] ------------------------------------------------------------	
$latest_update = '1975-01-01 00:00:00';
$doctor_signed = -1;
$AdminData = 0;

$result = $con->prepare("SELECT * FROM p_diagnostics WHERE idpatient = ? and doctor_signed is not null order by latest_update desc limit 1");
$result->bindValue(1, $UserID, PDO::PARAM_INT);
$result->execute();

while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
	if ($row['deleted'] != 1) $AdminData++;	
	$doctor_signedP = $row['doctor_signed'];
	$latest_updateP = $row['latest_update'];
	if (strtotime($latest_updateP) > strtotime($latest_update)) { $latest_update = $latest_updateP; $doctor_signed = $doctor_signedP;}
	
};

if ($counter1>0) $cadena.=',';    
$cadena.='
    {
        "Data":"'.$AdminData.'",
        "doctor_signed":"'.$doctor_signed.'",
        "latest_update":"'.$latest_update.'"
        }';    
$counter1++;

// MEDICATION Retrieval [2] ------------------------------------------------------------	
$latest_update = '1975-01-01 00:00:00';
$doctor_signed = -1;
$AdminData = 0;

$result = $con->prepare("SELECT * FROM p_medication WHERE idpatient = ? and doctor_signed is not null order by latest_update limit 1");
$result->bindValue(1, $UserID, PDO::PARAM_INT);
$result->execute();

while ($row = $result->fetch(PDO::FETCH_ASSOC)) 
{
	if ($row['doctor_signed'] != $row['edited'])
    {
     $latest_update = $row['latest_update'];
     $doctor_signed = $row['edited'];
    }
	
};

if ($counter1>0) $cadena.=',';    
$cadena.='
    {
        "Data":"'.$AdminData.'",
        "doctor_signed":"'.$doctor_signed.'",
        "latest_update":"'.$latest_update.'"
        }';    
$counter1++;

// IMMUNO  Retrieval [3] ------------------------------------------------------------	
$latest_update = '1975-01-01 00:00:00';
$doctor_signed = -1;
$AdminData = 0;

$result = $con->prepare("SELECT * FROM p_immuno WHERE idpatient = ?  and VaccName != '' and doctor_signed is not null order by latest_update limit 1");
$result->bindValue(1, $UserID, PDO::PARAM_INT);
$result->execute();

while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
	if ($row['deleted'] != 1) $AdminData++;	
	$doctor_signedP = $row['doctor_signed'];
	$latest_updateP = $row['latest_update'];
	if (strtotime($latest_updateP) > strtotime($latest_update)) { $latest_update = $latest_updateP; $doctor_signed = $doctor_signedP;}
	
};

if ($counter1>0) $cadena.=',';    
$cadena.='
    {
        "Data":"'.$AdminData.'",
        "doctor_signed":"'.$doctor_signed.'",
        "latest_update":"'.$latest_update.'"
        }';    
$counter1++;

// FAMILY HISTORY Retrieval [4] ------------------------------------------------------------	
$latest_update = '1975-01-01 00:00:00';
$doctor_signed = -1;
$AdminData = 0;

$result = $con->prepare("SELECT * FROM p_family WHERE idpatient = ? and doctor_signed is not null order by latest_update limit 1");
$result->bindValue(1, $UserID, PDO::PARAM_INT);
$result->execute();

while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
	if ($row['deleted'] != 1) $AdminData++;	
	$doctor_signedP = $row['doctor_signed'];
	$latest_updateP = $row['latest_update'];
	if (strtotime($latest_updateP) > strtotime($latest_update)) { $latest_update = $latest_updateP; $doctor_signed = $doctor_signedP;}
	
};

if ($counter1>0) $cadena.=',';    
$cadena.='
    {
        "Data":"'.$AdminData.'",
        "doctor_signed":"'.$doctor_signed.'",
        "latest_update":"'.$latest_update.'"
        }';    
$counter1++;

// HABITS Retrieval [5] ------------------------------------------------------------	
$latest_update = '1975-01-01 00:00:00';
$doctor_signed = -1;
$AdminData = 0;

$result = $con->prepare("SELECT * FROM p_habits WHERE idpatient = ? and doctor_signed is not null order by latest_update limit 1");
$result->bindValue(1, $UserID, PDO::PARAM_INT);
$result->execute();

while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
	//if ($row['deleted'] != 1) $AdminData++;	
	$AdminData++;	
	$doctor_signedP = $row['doctor_signed'];
	$latest_updateP = $row['latest_update'];
	if (strtotime($latest_updateP) > strtotime($latest_update)) { $latest_update = $latest_updateP; $doctor_signed = $doctor_signedP;}
	
};

if ($counter1>0) $cadena.=',';    
$cadena.='
    {
        "Data":"'.$AdminData.'",
        "doctor_signed":"'.$doctor_signed.'",
        "latest_update":"'.$latest_update.'"
        }';    
$counter1++;


// ALLERGY Retrieval [3] ------------------------------------------------------------	
$latest_update = '1975-01-01 00:00:00';
$doctor_signed = -1;
$AdminData = 0;

$result = $con->prepare("SELECT * FROM p_immuno WHERE idpatient = ? and AllerName != '' and doctor_signed is not null order by latest_update limit 1");
$result->bindValue(1, $UserID, PDO::PARAM_INT);
$result->execute();

while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
	if ($row['deleted'] != 1) $AdminData++;	
	$doctor_signedP = $row['doctor_signed'];
	$latest_updateP = $row['latest_update'];
	if (strtotime($latest_updateP) > strtotime($latest_update)) { $latest_update = $latest_updateP; $doctor_signed = $doctor_signedP;}
	
};

if ($counter1>0) $cadena.=',';    
$cadena.='
    {
        "Data":"'.$AdminData.'",
        "doctor_signed":"'.$doctor_signed.'",
        "latest_update":"'.$latest_update.'"
        }';    
$counter1++;




$encode = json_encode($cadena);
echo '{"items":['.($cadena).']}'; 

//var_dump($show_json);


?>