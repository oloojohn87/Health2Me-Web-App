<?php

session_start();


 require("environment_detail.php");
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];

$UserID = $_GET['IdUsu'];
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


$ageQuery= $con->prepare("SELECT DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(dob)), '%Y')+0 AS age,DOB FROM basicemrdata where idpatient=?");
$ageQuery->bindValue(1, $UserID, PDO::PARAM_INT);
//echo $basicQuery;


$result = $basicQuery->execute();
$count = $basicQuery->rowCount();
if($count==1)
{
	$row = $basicQuery->fetch(PDO::FETCH_ASSOC);
	
			$current_encoding = mb_detect_encoding($row['name'], 'auto');
			$show_text = iconv($current_encoding, 'ISO-8859-1', $row['name']);

			$current_encoding = mb_detect_encoding($row['surname'], 'auto');
			$show_text2 = iconv($current_encoding, 'ISO-8859-1', $row['surname']); 
	
	$name = capitalizeFirst($row['name']);
	$surname = capitalizeFirst($row['surname']);
	$sex = $row['sexo'];
	$UserEmail = $row['email'];	
	
	$result = $ageQuery->execute();
	$row2 = $ageQuery->fetch(PDO::FETCH_ASSOC);
	$age = $row2['age'];
	$dob = $row2['DOB'];
    $dob_object = new DateTime($dob);
    $readable_dob = $dob_object->format('F j, Y');
    $dob = explode(" ", $dob);
    $dob = $dob[0];
	
	//Image
	$fileName = "PatientImage/".$UserID.".jpg";
	if(file_exists($fileName))
	{
		//echo 'Decrypt_Image.bat PatientImage '.$UserID.'.jpg '.$UserID.' '.$pass.' 2>&1';
		shell_exec('Decrypt_Image.bat PatientImage '.$UserID.'.jpg '.$UserID.' '.$pass.' 2>&1');
		$file = "temp/".$UserID."/".$UserID.".jpg";
	    $file = "PatientImage/".$UserID.".jpg";   // TEMPORARY FIX: Check (Is patient picture not being encrypted ??)
		//$file = "checkFilezilla";   // TEMPORARY FIX: Check (Is patient picture not being encrypted ??)
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
			<img src="'.$file.'" style="width:50px; margin-left:20%;margin-top:30%; float:left; font-size:18px;  border:1px solid #b0b0b0;"/>
		 </div>';   
 	
if($_COOKIE["lang"] == 'th'){
$show_text = 'F. Nac. : ';
}else{
$show_text = 'DOB : ';
}
$SToday = $readable_dob;
if($_COOKIE["lang"] == 'th'){
if(substr($SToday, 0, 3) == 'Jan'){
$new_month = 'Ene '.substr($SToday, -8, 8);
}
if(substr($SToday, 0, 3) == 'Feb'){
$new_month = 'Feb '.substr($SToday, -8, 8);
}
if(substr($SToday, 0, 3) == 'Mar'){
$new_month = 'Mar '.substr($SToday, -8, 8);
}
if(substr($SToday, 0, 3) == 'Apr'){
$new_month = 'Abr '.substr($SToday, -8, 8);
}
if(substr($SToday, 0, 3) == 'May'){
$new_month = 'May '.substr($SToday, -8, 8);
}
if(substr($SToday, 0, 3) == 'Jun'){
$new_month = 'Jun '.substr($SToday, -8, 8);
}
if(substr($SToday, 0, 3) == 'Jul'){
$new_month = 'Jul '.substr($SToday, -8, 8);
}
if(substr($SToday, 0, 3) == 'Aug'){
$new_month = 'Ago '.substr($SToday, -8, 8);
}
if(substr($SToday, 0, 3) == 'Sep'){
$new_month = 'Sep '.substr($SToday, -8, 8);
}
if(substr($SToday, 0, 3) == 'Oct'){
$new_month = 'Oct '.substr($SToday, -8, 8);
}
if(substr($SToday, 0, 3) == 'Nov'){
$new_month = 'Nov '.substr($SToday, -8, 8);
}
if(substr($SToday, 0, 3) == 'Dec'){
$new_month = 'Dic '.substr($SToday, -8, 8);
}
}else{
if(substr($SToday, 0, 3) == 'Jan'){
$new_month = 'Jan '.substr($SToday, -8, 8);
}
if(substr($SToday, 0, 3) == 'Feb'){
$new_month = 'Feb '.substr($SToday, -8, 8);
}
if(substr($SToday, 0, 3) == 'Mar'){
$new_month = 'Mar '.substr($SToday, -8, 8);
}
if(substr($SToday, 0, 3) == 'Apr'){
$new_month = 'Apr '.substr($SToday, -8, 8);
}
if(substr($SToday, 0, 3) == 'May'){
$new_month = 'May '.substr($SToday, -8, 8);
}
if(substr($SToday, 0, 3) == 'Jun'){
$new_month = 'Jun '.substr($SToday, -8, 8);
}
if(substr($SToday, 0, 3) == 'Jul'){
$new_month = 'Jul '.substr($SToday, -8, 8);
}
if(substr($SToday, 0, 3) == 'Aug'){
$new_month = 'Aug '.substr($SToday, -8, 8);
}
if(substr($SToday, 0, 3) == 'Sep'){
$new_month = 'Sep '.substr($SToday, -8, 8);
}
if(substr($SToday, 0, 3) == 'Oct'){
$new_month = 'Oct '.substr($SToday, -8, 8);
}
if(substr($SToday, 0, 3) == 'Nov'){
$new_month = 'Nov '.substr($SToday, -8, 8);
}
if(substr($SToday, 0, 3) == 'Dec'){
$new_month = 'Dec '.substr($SToday, -8, 8);
}
}
	//Name	and DOB
	echo '<div style="float:left; height:100%; ">
				<div id="NombreComp" style="margin-top:10%;margin-left:10%;width:100%; color: rgba(34,174,255,1); font: bold 30px Arial, Helvetica, sans-serif; cursor: auto;">'.(htmlspecialchars($name)).' '.(htmlspecialchars($surname)).'</div>
				<div id="UDOB" style="color: rgba(84,188,0,1); font: bold 16px Arial, Helvetica, sans-serif; cursor: auto; margin-left:10%; width: 200px;">'.$show_text.$new_month.'</div>
                <input type="hidden" id="RAWDOB" value="'.$dob.'" />
		 </div>';
	
	echo '<div style="float:right;height:100%;margin-left:10%;margin-right:5%;margin-top:10%; ">'.getGenderIcon($sex).'
			  
			  <span id="NombreComp2" style="width:100%; color: rgba(34,174,255,1); font: bold 30px Arial, Helvetica, sans-serif; cursor: auto;">'.$age.'</span>
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

