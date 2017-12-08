<?php
session_start();
require("environment_detail.php");
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];

 
// Connect to server and select databse.
$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }		

$idcreator = $_SESSION['MEDID'];
//$userid = 28;
$idusfixed = $_GET['idusfixed'];
$idusfixedname = $_GET['idusfixedname'];
$name = $_GET['name'];
$surname = $_GET['surname'];
$initial = $_GET['initial'];
$phone = $_GET['phone'];
$email = $_GET['email'];
if($phone == ''){
	$phone_holder = 'asdfasdfasdfasdfsadf';
}else{
	$phone_holder = $phone;
}


if($email == ''){
	$email_holder = 'ssdfgsdfgsdfgsdfgsdfgdsf';
}else{
	$email_holder = $email;

}

$dupe_check = $con->prepare("SELECT * FROM usuarios WHERE (IdUsFIXED = ? AND Name = ? AND Surname = ?) OR telefono = ? OR email = ?");
$dupe_check->bindValue(1, $idusfixed, PDO::PARAM_INT);
$dupe_check->bindValue(2, $name, PDO::PARAM_STR);
$dupe_check->bindValue(3, $surname, PDO::PARAM_STR);
$dupe_check->bindValue(4, $phone_holder, PDO::PARAM_STR);
$dupe_check->bindValue(5, $email_holder, PDO::PARAM_STR);
$dupe_check->execute();
$dupe_count = $dupe_check->rowCount();
$row_dupe = $dupe_check->fetch(PDO::FETCH_ASSOC);

if($dupe_count != 0){
	$query4 = $con->prepare("SELECT * FROM doctorslinkusers WHERE idmed=? && idus=? && idpin IS NULL");
	$query4->bindValue(1, $idcreator, PDO::PARAM_INT);
	$query4->bindValue(2, $row_dupe['Identif'], PDO::PARAM_INT);
	$result4 = $query4->execute();
	$count4 = $query4->rowCount();

	
	if($count4 >= 1){
		$array_1 = 'exists';
		$array_2 = "We see that you are already connected with this member.  Would you like to navigate to their profile now?";
		$cadena ='
				{
				"row1":"'.$array_1.'",
				"row2":"'.$array_2.'",
				"rowid":"'.$row_dupe['Identif'].'",
				"name":"'.$row_dupe['Name'].'",
				"surname":"'.$row_dupe['Surname'].'",
				"email":"'.$row_dupe['email'].'",
				"phone":"'.$row_dupe['telefono'].'"
				}';    
	}else{
		$array_1 = 'none';
		$array_2 = "We detected that there is already a user with those credentials.  Would you like to send an email requesting access to this members records?";
		$cadena ='
				{
				"row1":"'.$array_1.'",
				"row2":"'.$array_2.'",
				"rowid":"'.$row_dupe['Identif'].'",
				"name":"'.$row_dupe['Name'].'",
				"surname":"'.$row_dupe['Surname'].'",
				"email":"'.$row_dupe['email'].'",
				"phone":"'.$row_dupe['telefono'].'"
				}';  
	}
	$encode = json_encode($cadena);
	echo '{"items":['.($cadena).']}'; 
}else{

	$query = $con->prepare("insert into usuarios(idcreator,idusfixed,idusfixedname,alias,name,surname,mi,signUpDate,email,telefono, personal_doctor, personal_doctor_accepted) 
	values(?,?,?,?,?,?,?,NOW(),?,?,?,1)"); //Added by Pallab signUpDate, earlier it was missing
	$query->bindValue(1, $idcreator, PDO::PARAM_INT);
	$query->bindValue(2, $idusfixed, PDO::PARAM_INT);
	$query->bindValue(3, $idusfixedname, PDO::PARAM_STR);
	$query->bindValue(4, $idusfixedname, PDO::PARAM_STR);
	$query->bindValue(5, $name, PDO::PARAM_STR);
	$query->bindValue(6, $surname, PDO::PARAM_STR);
	$query->bindValue(7, $initial, PDO::PARAM_STR);
	$query->bindValue(8, $email, PDO::PARAM_STR);
	$query->bindValue(9, $phone, PDO::PARAM_STR);
	$query->bindValue(10, $idcreator, PDO::PARAM_INT);


	//echo $query;
	if($query->execute())
	{
		
		include('makeNewUserKeyClass.php');
		
		$med_id = $_SESSION['MEDID'];
		$type = 'register';
		$maker_type = 'doctor';

		$newImage_ID = $con->lastInsertId();
		$owner = $newImage_ID; 
		
		$key = new makeNewUserKeyClass($med_id, $owner, $type, $maker_type);

		if(file_exists('DocPatientImage/'.$idcreator.'.jpg')){
			copy('DocPatientImage/'.$idcreator.'.jpg', 'PatientImage/'.$newImage_ID.'.jpg');
			unlink('DocPatientImage/'.$idcreator.'.jpg');
		}

		if(file_exists('DocPatientImage/'.$idcreator.'.png')){
			copy('DocPatientImage/'.$idcreator.'.png', 'PatientImage/'.$newImage_ID.'.png');
			unlink('DocPatientImage/'.$idcreator.'.png');
		}

		if(file_exists('DocPatientImage/'.$idcreator.'.gif')){
			copy('DocPatientImage/'.$idcreator.'.gif', 'PatientImage/'.$newImage_ID.'.gif');
			unlink('DocPatientImage/'.$idcreator.'.gif');
		}

		$query = $con->prepare("select max(identif) as idusu from usuarios");
		$result = $query->execute();
		$row = $query->fetch(PDO::FETCH_ASSOC);
		$array_1 = 'created';
		$cadena ='
				{
				"row1":"'.$array_1.'",
				"row2":"'.$row['idusu'].'",
				"key":"'.$key->short_key.'",
				"alias":"'.$idusfixedname.'"
				}';   
				
		echo '{"items":['.($cadena).']}';
			
		$query = $con->prepare("insert into doctorslinkusers(idmed,idus,fecha,idpin,estado,confirm,tipo) values(?,?,now(),NULL,2,?,1)");
		$query->bindValue(1, $idcreator, PDO::PARAM_INT);
		$query->bindValue(2, $row['idusu'], PDO::PARAM_INT);
		$query->bindValue(3, 'Created by Dr. Id= '.$idcreator.'', PDO::PARAM_STR);
			
			
		file_put_contents("ip.txt", $query, FILE_APPEND);
		$result = $query->execute();
		
		return;
			
			
	}else{
		$array_1 = 'failed';
		$cadena ='
				{
				"row1":"'.$array_1.'",
				"row2":"'.$row['idusu'].'"
				}';   
				
		echo '{"items":['.($cadena).']}';
	}
}

?>
