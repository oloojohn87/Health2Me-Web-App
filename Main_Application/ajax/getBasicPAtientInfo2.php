<?php

session_start();


 require("environment_detail.php");
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];

$UserID = $_SESSION['UserID'];
$MEDID = $_SESSION['MEDID'];
//$UserID = 1090;
$pass=$_SESSION['decrypt'];

$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }		

$basicQuery = $con->prepare("SELECT FNac,name,surname,sexo,email FROM usuarios WHERE identif=?");
$basicQuery->bindValue(1, $UserID, PDO::PARAM_INT);


$ageQuery= $con->prepare("SELECT DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(dob)), '%Y')+0 AS age,DATE_FORMAT(dob,'%e-%b-%Y') AS DOB FROM basicemrdata where idpatient=?");
$ageQuery->bindValue(1, $UserID, PDO::PARAM_INT);
//echo $basicQuery;


$result = $basicQuery->execute();
$count = $basicQuery->rowCount();
if($count==1)
{
	$row = $basicQuery->fetch(PDO::FETCH_ASSOC);
	$name = capitalizeFirst($row['name']);
	$surname = capitalizeFirst($row['surname']);
	$sex = $row['sexo'];
	$UserEmail = $row['email'];	
	
	$result = $ageQuery->execute();
	$row2 = $ageQuery->fetch(PDO::FETCH_ASSOC);
	$age = $row2['age'];
	$dob = $row['FNac'];
	
	//Image
	$fileName = "PatientImage/".$UserID.".jpg";
	if(file_exists($fileName))
	{
		//echo 'Decrypt_Image.bat PatientImage '.$UserID.'.jpg '.$UserID.' '.$pass.' 2>&1';
		shell_exec('Decrypt_Image.bat PatientImage '.$UserID.'.jpg '.$UserID.' '.$pass.' 2>&1');
		$file = "temp/".$UserID."/".$UserID.".jpg";
		$style = "max-height: 80px; max-width:80px;";
    }
	else
	{
		$hash = md5( strtolower( trim( $UserEmail ) ) );
        $file = 'identicon.php?size=29&hash='.$hash;
		//$file = "/PatientImage/defaultDP.jpg";
		$style = "max-height: 80px; max-width:80px;";
	}
	
	echo '<div id="DivAv" style="float:left; width:60px; height:100%; ">
			<img src="'.$file.'" style="width:50px; margin:0px; float:left; font-size:18px;  border:1px solid #b0b0b0;"/>
		 </div>';   
 	
	
	//Name	and DOB
	echo '<div style="float:left; height:100%; ">
				<div id="NombreComp" style="width:100%; color: rgba(34,174,255,1); font: bold 30px Arial, Helvetica, sans-serif; cursor: auto;">'.$name.' '.$surname.'</div>
				<span id="UDOB" style="color: rgba(84,188,0,1); font: bold 16px Arial, Helvetica, sans-serif; cursor: auto; margin-top:-5px;">DOB : '.$dob.'</span>
		 </div>';
	
	echo '<div style="float:right;height:100%; ">'.getGenderIcon($sex).'
			  
			  <span id="NombreComp2" style="width:100%; color: rgba(34,174,255,1); font: bold 30px Arial, Helvetica, sans-serif; cursor: auto;margin-left:10px">'.$age.'</span>
		 </div>';
	
	echo '<input id="MEDID" value="'.$MEDID.'" style="float:left; width:25px; font-size:10px; display:none;">';
	echo '<input id="USERID" value="'.$UserID.'" style="float:left; width:25px; font-size:10px; display:none;">';


}
else
{
	echo "Error";
}


function capitalizeFirst($string)
{
	return ucfirst(strtolower($string));
}

function getGenderIcon($gender)
{
	$path='images/PassportIcons/';
	switch($gender)
	{
		case 0:return '<img src="'.$path.'female_blue.png" style="height:30px;width:30px;margin-top:-10px" title="Female"></img>';
		case 1:return '<img src="'.$path.'male_blue.png" style="height:30px;width:30px;margin-top:-10px" title="Male"></img>';
		default:return '<img src="" style="height:30px;width:30px;margin-top:-10px" title="Gender Not Stored"></img>';;
	
	}

}

?>

