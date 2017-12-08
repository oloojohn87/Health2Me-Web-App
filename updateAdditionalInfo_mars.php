<?php
 session_start();
 require("environment_detail.php");
require("NotificationClass.php");
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

$notifications = new Notifications();

$idpatient = null;
if(isset($_GET['IdUsu']))
{
    $idpatient = $_GET['IdUsu'];
}
else
{
    $idpatient = $_SESSION['UserID'];
}
$iddoctor = $_SESSION['MEDID'];
//if ($iddoctor == $idpatient) $idauthor = -1; else $idauthor = $iddoctor;
if ($iddoctor > 0) 
    $idauthor = $iddoctor; 
else 
    $idauthor = -1; 

$insurance = $_GET['insurance'];
$address = $_GET['address'];
$city = $_GET['city'];
$state = $_GET['state'];
$zip = $_GET['zip'];
$telefono = cleanPhoneNumber($_GET['phone']);
$email = $_GET['email'];
$sexo  = $_GET['gender'];
$blood_type = $_GET['blood_type'];
$weight = strval($_GET['weight']);
$weight_type = intval($_GET['weight_type']);
$height1 = intval($_GET['height1']);
$height2 = intval($_GET['height2']);
$height_type = intval($_GET['height_type']);
if($sexo == 'Male')
{
    $sexo = 1;
}
else
{
    $sexo = 0;
}

$dob = $_GET['dob'].' 00:00:00';


/*
if($_SESSION['isPatient']==1)
{
	$edited=0;
}
else
{
	$edited = $_SESSION['MEDID'];
}
*/

$checkRow = $con->prepare("SELECT * FROM usuarios WHERE Identif=? ");
$checkRow->bindValue(1, $idpatient, PDO::PARAM_INT);
$resultRow = $checkRow->execute();

if($checkRow->rowCount() > 0){

/*$queryText = $con->prepare("Update basicemrdata SET insurance=?, address=?,City=?,zip=?,latest_update=NOW(),doctor_signed=?,DOB=?,bloodType=?,weight=?,weightType=?,height1=?,height2=?,heightType=?,state=?  where IdPatient=? ");*/
$queryText = $con->prepare("INSERT INTO p_userInfo (address,City,zip,doctor_signed,DOB,bloodType,weight,weightType,height1,height2,heightType,state,telefono,sexo,email,idpatient) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)"; 
$queryText->bindValue(1, $address, PDO::PARAM_STR);
$queryText->bindValue(2, $city, PDO::PARAM_STR);
$queryText->bindValue(3, $zip, PDO::PARAM_STR);
$queryText->bindValue(4, $idauthor, PDO::PARAM_INT);
$queryText->bindValue(5, $dob, PDO::PARAM_STR);
$queryText->bindValue(6, $blood_type, PDO::PARAM_STR);
$queryText->bindValue(7, $weight, PDO::PARAM_STR);
$queryText->bindValue(8, $weight_type, PDO::PARAM_INT);
$queryText->bindValue(9, $height1, PDO::PARAM_INT);
$queryText->bindValue(10, $height2, PDO::PARAM_INT);
$queryText->bindValue(11, $height_type, PDO::PARAM_INT);
$queryText->bindValue(12, $state, PDO::PARAM_STR);
$queryText->bindValue(13, $telefono, PDO::PARAM_INT);
$queryText->bindValue(14, $sexo, PDO::PARAM_INT);
$queryText->bindValue(15, $email, PDO::PARAM_INT);
$queryText->bindValue(16, $idpatient, PDO::PARAM_STR);
$result = $queryText->execute();


}/*else{ 
//		  $error = "MySQL error ".mysql_errno().": ".mysql_error()."\n<br>When executing:<br>\n$queryText\n<br>"; 
//		  echo $error;
    $queryText = $con->prepare("INSERT INTO basicemrdata SET IdPatient=?,insurance=?,address=?,City=?,zip=?,latest_update=NOW(),doctor_signed=?,DOB=?,bloodType=?,weight=?,weightType=?,height1=?,height2=?,heightType=?,state=?");
    $queryText->bindValue(1, $idpatient, PDO::PARAM_INT);
    $queryText->bindValue(2, $insurance, PDO::PARAM_STR);
    $queryText->bindValue(3, $address, PDO::PARAM_STR);
    $queryText->bindValue(4, $city, PDO::PARAM_STR);
    $queryText->bindValue(5, $zip, PDO::PARAM_STR);
    $queryText->bindValue(6, $idauthor, PDO::PARAM_INT);
	$queryText->bindValue(7, $dob, PDO::PARAM_STR);
	$queryText->bindValue(8, $blood_type, PDO::PARAM_STR);
	$queryText->bindValue(9, $weight, PDO::PARAM_STR);
	$queryText->bindValue(10, $weight_type, PDO::PARAM_INT);
	$queryText->bindValue(11, $height1, PDO::PARAM_INT);
	$queryText->bindValue(12, $height2, PDO::PARAM_INT);
	$queryText->bindValue(13, $height_type, PDO::PARAM_INT);
	$queryText->bindValue(14, $state, PDO::PARAM_STR);

    //echo 'query 2:' .$queryText;
    $result = $queryText->execute();
} */

$queryText = $con->prepare("Update usuarios SET telefono=?, email=?, Sexo=? where Identif=? ");
$queryText->bindValue(1, $telefono, PDO::PARAM_STR);
$queryText->bindValue(2, $email, PDO::PARAM_STR);
$queryText->bindValue(3, $sexo, PDO::PARAM_INT);
$queryText->bindValue(4, $idpatient, PDO::PARAM_INT);

$result = $queryText->execute();

if (!$queryText) 
{ 
    $error = "MySQL error \n<br>When executing:<br>\n$queryText\n<br>"; 
    echo $error;
} 

if($_SESSION['BOTHID'] == $_SESSION['MEDID'])
{
    // doctor
    $notifications->add('SUMEDT', $iddoctor, true, $idpatient, false, null);
}
else
{
    $pers_doc = $con->prepare("SELECT personal_doctor FROM usuarios WHERE Identif = ?");
    $pers_doc->bindValue(1, $idpatient, PDO::PARAM_INT);
    $pers_doc->execute();
    $pers_doc_row = $pers_doc->fetch(PDO::FETCH_ASSOC);
    if($pers_doc_row['personal_doctor'] != NULL)
    {
        $notifications->add('SUMEDT', $idpatient, false, $pers_doc_row['personal_doctor'], true, null);
    }
}


function cleanPhoneNumber($phone)
{
    return preg_replace("/[^0-9]/", "",$phone);
}

?>
