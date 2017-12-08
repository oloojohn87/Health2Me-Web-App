 <?php
 header("Access-Control-Allow-Origin: *");
 require("../environment_detailForLogin.php");

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

//Below new variables added by Pallab
$doctor_previous = '';
$doctor_updateTime = '1975-01-01 00:00:00';

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

//Below new variables added by Pallab
$doctor_previous = '';
$doctor_updateTime = '1975-01-01 00:00:00';

//Below lines commented by Pallab to test and fix the verified ribbon issue of member updating the summary and blank doctor name 
//Start of comment by Pallab
//$result = $con->prepare("SELECT * FROM p_diagnostics WHERE idpatient = ?");
//$result->bindValue(1, $UserID, PDO::PARAM_INT);
//$result->execute();
/*while ($row = $result->fetch(PDO::FETCH_ASSOC)) 
{
	if ($row['deleted'] != 1) $AdminData++;	
	$doctor_signedP = $row['doctor_signed'];
	$latest_updateP = $row['latest_update'];
	if (strtotime($latest_updateP) > strtotime($latest_update)) { $latest_update = $latest_updateP; $doctor_signed = $doctor_signedP;}
	
};*/
//End of comment

//Start of new code by Pallab
$result = $con->prepare("select * from p_diagnostics where idpatient = ? order by latest_update desc limit 1");
$result->bindValue(1, $UserID, PDO::PARAM_INT);
$result->execute();
$row = $result->fetch(PDO::FETCH_ASSOC);

if($row['doctor_signed'] != 0)
{

      $doctor_signed = $row['doctor_signed'];
      $latest_update = $row['latest_update'];
}

else //This else section happens when the latest update was by a member where in doctor_signed column = 0
{
      $doctor_signed = $row['doctor_signed'];
      $latest_update = $row['latest_update'];
    
      //Below portion of code is for getting the row where a doctor made the last update
      $result = $con->prepare("select * from p_diagnostics where idpatient = ? and doctor_signed !=0 order by latest_update desc limit 1");
      $result->bindValue(1, $UserID, PDO::PARAM_INT);
      $result->execute();
      
      if($result->rowCount() > 0)
      {
        $row = $result->fetch(PDO::FETCH_ASSOC);
      
        $doctor_previous = $row['doctor_signed'];
        $doctor_updateTime = $row['latest_update'];
      }
      
}

//End of new code by Pallab


if ($counter1>0) $cadena.=',';    

//Two new parameters added by Pallab to cadena

if(($doctor_previous > '') && ($doctor_updateTime > '1975-01-01 00:00:00'))
{
    $cadena.='
    {
        "Data":"'.$AdminData.'",
        "doctor_signed":"'.$doctor_signed.'",
        "latest_update":"'.$latest_update.'",
        "doctor_updateTime":"'.$doctor_updateTime.'",
        "doctor_previous":"'.$doctor_previous.'"
        }';    
}
else
{
    $cadena.='
    {
        "Data":"'.$AdminData.'",
        "doctor_signed":"'.$doctor_signed.'",
        "latest_update":"'.$latest_update.'"
        }'; 
}
$counter1++;

// MEDICATION Retrieval [2] ------------------------------------------------------------	
$latest_update = '1975-01-01 00:00:00';
$doctor_signed = -1;
$AdminData = 0;

//Below new variables added by Pallab
$doctor_previous = '';
$doctor_updateTime = '1975-01-01 00:00:00';


//Below Lines commented by Pallab
/*$result = $con->prepare("SELECT * FROM p_medication WHERE idpatient = ?");
$result->bindValue(1, $UserID, PDO::PARAM_INT);
$result->execute();

while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
	if ($row['deleted'] != 1) $AdminData++;	
	$doctor_signedP = $row['doctor_signed'];
	$latest_updateP = $row['latest_update'];
	if (strtotime($latest_updateP) > strtotime($latest_update)) { $latest_update = $latest_updateP; $doctor_signed = $doctor_signedP;}
	
};
*/

//Start of new code by Pallab
$result = $con->prepare("select * from p_medication where idpatient = ? order by latest_update desc limit 1");
$result->bindValue(1, $UserID, PDO::PARAM_INT);
$result->execute();
$row = $result->fetch(PDO::FETCH_ASSOC);

if($row['doctor_signed'] != 0)
{

      $doctor_signed = $row['doctor_signed'];
      $latest_update = $row['latest_update'];
}

else //This else section happens when the latest update was by a member where in doctor_signed column = 0
{
      $doctor_signed = $row['doctor_signed'];
      $latest_update = $row['latest_update'];
    
      //Below portion of code is for getting the row where a doctor made the last update
      $result = $con->prepare("select * from p_medication where idpatient = ? and doctor_signed !=0 order by latest_update desc limit 1");
      $result->bindValue(1, $UserID, PDO::PARAM_INT);
      $result->execute();
      if($result->rowCount() > 0)
      {
        $row = $result->fetch(PDO::FETCH_ASSOC);
      
        $doctor_previous = $row['doctor_signed'];
        $doctor_updateTime = $row['latest_update'];
      }
      
}

//End of new code by Pallab


if ($counter1>0) $cadena.=',';    
if(($doctor_previous > '') && ($doctor_updateTime > '1975-01-01 00:00:00'))
{
    $cadena.='
    {
        "Data":"'.$AdminData.'",
        "doctor_signed":"'.$doctor_signed.'",
        "latest_update":"'.$latest_update.'",
        "doctor_updateTime":"'.$doctor_updateTime.'",
        "doctor_previous":"'.$doctor_previous.'"
        }';    
}
else
{
    $cadena.='
    {
        "Data":"'.$AdminData.'",
        "doctor_signed":"'.$doctor_signed.'",
        "latest_update":"'.$latest_update.'"
        }'; 
}   
$counter1++;

// IMMUNO  Retrieval [3] ------------------------------------------------------------	
$latest_update = '1975-01-01 00:00:00';
$doctor_signed = -1;
$AdminData = 0;

//Below lines commented by Pallab

/*$result = $con->prepare("SELECT * FROM p_immuno WHERE idpatient = ?  and VaccName != '' ");
$result->bindValue(1, $UserID, PDO::PARAM_INT);
$result->execute();

while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
	if ($row['deleted'] != 1) $AdminData++;	
	$doctor_signedP = $row['doctor_signed'];
	$latest_updateP = $row['latest_update'];
	if (strtotime($latest_updateP) > strtotime($latest_update)) { $latest_update = $latest_updateP; $doctor_signed = $doctor_signedP;}
	
};
*/

//Start of new code by Pallab
$result = $con->prepare("select * from p_immuno where idpatient = ? order by latest_update desc limit 1");
$result->bindValue(1, $UserID, PDO::PARAM_INT);
$result->execute();
$row = $result->fetch(PDO::FETCH_ASSOC);

if($row['doctor_signed'] != 0)
{

      $doctor_signed = $row['doctor_signed'];
      $latest_update = $row['latest_update'];
}

else //This else section happens when the latest update was by a member where in doctor_signed column = 0
{
      $doctor_signed = $row['doctor_signed'];
      $latest_update = $row['latest_update'];
    
      //Below portion of code is for getting the row where a doctor made the last update
      $result = $con->prepare("select * from p_immuno where idpatient = ? and doctor_signed !=0 order by latest_update desc limit 1");
      $result->bindValue(1, $UserID, PDO::PARAM_INT);
      $result->execute();
      if($result->rowCount() > 0)
      {
        $row = $result->fetch(PDO::FETCH_ASSOC);
      
        $doctor_previous = $row['doctor_signed'];
        $doctor_updateTime = $row['latest_update'];
      }
      
}

//End of new code by Pallab

if ($counter1>0) $cadena.=',';    
if(($doctor_previous > '' ) && ($doctor_updateTime > '1975-01-01 00:00:00'))
{
    $cadena.='
    {
        "Data":"'.$AdminData.'",
        "doctor_signed":"'.$doctor_signed.'",
        "latest_update":"'.$latest_update.'",
        "doctor_updateTime":"'.$doctor_updateTime.'",
        "doctor_previous":"'.$doctor_previous.'"
        }';    
}
else
{
    $cadena.='
    {
        "Data":"'.$AdminData.'",
        "doctor_signed":"'.$doctor_signed.'",
        "latest_update":"'.$latest_update.'"
        }'; 
}   
$counter1++;

// FAMILY HISTORY Retrieval [4] ------------------------------------------------------------	
$latest_update = '1975-01-01 00:00:00';
$doctor_signed = -1;
$AdminData = 0;

//Below new variables added by Pallab
$doctor_previous = '';
$doctor_updateTime = '1975-01-01 00:00:00';

//Below lines commented by Pallab

/*$result = $con->prepare("SELECT * FROM p_family WHERE idpatient = ?");
$result->bindValue(1, $UserID, PDO::PARAM_INT);
$result->execute();

while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
	if ($row['deleted'] != 1) $AdminData++;	
	$doctor_signedP = $row['doctor_signed'];
	$latest_updateP = $row['latest_update'];
	if (strtotime($latest_updateP) > strtotime($latest_update)) { $latest_update = $latest_updateP; $doctor_signed = $doctor_signedP;}
	
};
*/

//Start of new code by Pallab
$result = $con->prepare("select * from p_family where idpatient = ? order by latest_update desc limit 1");
$result->bindValue(1, $UserID, PDO::PARAM_INT);
$result->execute();
$row = $result->fetch(PDO::FETCH_ASSOC);

if($row['doctor_signed'] != 0)
{

      $doctor_signed = $row['doctor_signed'];
      $latest_update = $row['latest_update'];
}

else //This else section happens when the latest update was by a member where in doctor_signed column = 0
{
      $doctor_signed = $row['doctor_signed'];
      $latest_update = $row['latest_update'];
    
      //Below portion of code is for getting the row where a doctor made the last update
      $result = $con->prepare("select * from p_family where idpatient = ? and doctor_signed !=0 order by latest_update desc limit 1");
      $result->bindValue(1, $UserID, PDO::PARAM_INT);
      $result->execute();
      if($result->rowCount() > 0)
      {
        $row = $result->fetch(PDO::FETCH_ASSOC);
      
        $doctor_previous = $row['doctor_signed'];
        $doctor_updateTime = $row['latest_update'];
      }
      
}

//End of new code by Pallab

if ($counter1>0) $cadena.=',';    
if(($doctor_previous > '' ) && ($doctor_updateTime > '1975-01-01 00:00:00'))
{
    $cadena.='
    {
        "Data":"'.$AdminData.'",
        "doctor_signed":"'.$doctor_signed.'",
        "latest_update":"'.$latest_update.'",
        "doctor_updateTime":"'.$doctor_updateTime.'",
        "doctor_previous":"'.$doctor_previous.'"
        }';    
}
else
{
    $cadena.='
    {
        "Data":"'.$AdminData.'",
        "doctor_signed":"'.$doctor_signed.'",
        "latest_update":"'.$latest_update.'"
        }'; 
}   
$counter1++;

// HABITS Retrieval [5] ------------------------------------------------------------	
$latest_update = '1975-01-01 00:00:00';
$doctor_signed = -1;
$AdminData = 0;

//Below new variables added by Pallab
$doctor_previous = '';
$doctor_updateTime = '1975-01-01 00:00:00';

//Below lines commented by Pallab

/*$result = $con->prepare("SELECT * FROM p_habits WHERE idpatient = ?");
$result->bindValue(1, $UserID, PDO::PARAM_INT);
$result->execute();

while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
	//if ($row['deleted'] != 1) $AdminData++;	
	$AdminData++;	
	$doctor_signedP = $row['doctor_signed'];
	$latest_updateP = $row['latest_update'];
	if (strtotime($latest_updateP) > strtotime($latest_update)) { $latest_update = $latest_updateP; $doctor_signed = $doctor_signedP;}
	
};
*/

//Start of new code by Pallab
$result = $con->prepare("select * from p_habits where idpatient = ? order by latest_update desc limit 1");
$result->bindValue(1, $UserID, PDO::PARAM_INT);
$result->execute();
$row = $result->fetch(PDO::FETCH_ASSOC);

if($row['doctor_signed'] != 0)
{

      $doctor_signed = $row['doctor_signed'];
      $latest_update = $row['latest_update'];
}

else //This else section happens when the latest update was by a member where in doctor_signed column = 0
{
      $doctor_signed = $row['doctor_signed'];
      $latest_update = $row['latest_update'];
    
      //Below portion of code is for getting the row where a doctor made the last update
      $result = $con->prepare("select * from p_habits where idpatient = ? and doctor_signed !=0 order by latest_update desc limit 1");
      $result->bindValue(1, $UserID, PDO::PARAM_INT);
      $result->execute();
      if($result->rowCount() > 0)
      {
        $row = $result->fetch(PDO::FETCH_ASSOC);
      
        $doctor_previous = $row['doctor_signed'];
        $doctor_updateTime = $row['latest_update'];
      }
      
}

//End of new code by Pallab

if ($counter1>0) $cadena.=',';    
if(($doctor_previous > '' )&& ($doctor_updateTime > '1975-01-01 00:00:00'))
{
    $cadena.='
    {
        "Data":"'.$AdminData.'",
        "doctor_signed":"'.$doctor_signed.'",
        "latest_update":"'.$latest_update.'",
        "doctor_updateTime":"'.$doctor_updateTime.'",
        "doctor_previous":"'.$doctor_previous.'"
        }';    
}
else
{
    $cadena.='
    {
        "Data":"'.$AdminData.'",
        "doctor_signed":"'.$doctor_signed.'",
        "latest_update":"'.$latest_update.'"
        }'; 
}   
$counter1++;


// ALLERGY Retrieval [3] ------------------------------------------------------------	
$latest_update = '1975-01-01 00:00:00';
$doctor_signed = -1;
$AdminData = 0;

//Below new variables added by Pallab
$doctor_previous = '';
$doctor_updateTime = '1975-01-01 00:00:00';

//Below lines commented by Pallab

/*$result = $con->prepare("SELECT * FROM p_immuno WHERE idpatient = ? and AllerName != '' ");
$result->bindValue(1, $UserID, PDO::PARAM_INT);
$result->execute();

while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
	if ($row['deleted'] != 1) $AdminData++;	
	$doctor_signedP = $row['doctor_signed'];
	$latest_updateP = $row['latest_update'];
	if (strtotime($latest_updateP) > strtotime($latest_update)) { $latest_update = $latest_updateP; $doctor_signed = $doctor_signedP;}
	
};
*/

//Start of new code by Pallab
$result = $con->prepare("select * from p_immuno where idpatient = ? and AllerName is not null order by latest_update desc limit 1");
$result->bindValue(1, $UserID, PDO::PARAM_INT);
$result->execute();
$row = $result->fetch(PDO::FETCH_ASSOC);

if($row['doctor_signed'] != 0)
{

      $doctor_signed = $row['doctor_signed'];
      $latest_update = $row['latest_update'];
}

else //This else section happens when the latest update was by a member where in doctor_signed column = 0
{
      $doctor_signed = $row['doctor_signed'];
      $latest_update = $row['latest_update'];
    
      //Below portion of code is for getting the row where a doctor made the last update
      $result = $con->prepare("select * from p_immuno where idpatient = ? and doctor_signed !=0 and AllerName is not null order by latest_update desc limit 1");
      $result->bindValue(1, $UserID, PDO::PARAM_INT);
      $result->execute();
      if($result->rowCount() > 0)
      {
        $row = $result->fetch(PDO::FETCH_ASSOC);
      
        $doctor_previous = $row['doctor_signed'];
        $doctor_updateTime = $row['latest_update'];
      }
      
}

//End of new code by Pallab

if ($counter1>0) $cadena.=',';    
if($doctor_previous > '' && $doctor_updateTime > '1975-01-01 00:00:00')
{
    $cadena.='
    {
        "Data":"'.$AdminData.'",
        "doctor_signed":"'.$doctor_signed.'",
        "latest_update":"'.$latest_update.'",
        "doctor_updateTime":"'.$doctor_updateTime.'",
        "doctor_previous":"'.$doctor_previous.'"
        }';    
}
else
{
    $cadena.='
    {
        "Data":"'.$AdminData.'",
        "doctor_signed":"'.$doctor_signed.'",
        "latest_update":"'.$latest_update.'"
        }'; 
}  
$counter1++;


//print_r($cadena);

$encode = json_encode($cadena);
echo '{"items":['.($cadena).']}'; 

//var_dump($show_json);


?>