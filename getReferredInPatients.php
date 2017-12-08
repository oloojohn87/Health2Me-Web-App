<?php
//include 'config.php';
session_start();
set_time_limit(150);

 require("environment_detail.php");
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];

$userid = $_SESSION['MEDID'];

$group=empty($_GET['group'])?0:$_GET['group'];


$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }		
  
 $cnt=0;
if($group==1){
	$result_group=$con->prepare("select distinct idDoctor from doctorsgroups where idGroup IN (select idGroup from doctorsgroups where idDoctor=?)");
	$result_group->bindValue(1, $userid, PDO::PARAM_INT);
	$result_group->execute();
	
	while($row = $result_group->fetch(PDO::FETCH_ASSOC)){
		$ArrayDoctors[$cnt]=$row['idDoctor'];
		//echo 'DocId'.$cnt.' '.$ArrayDoctors[$cnt];
		$cnt++;
	}
	if($cnt==0){
		$ArrayDoctors[$cnt]=$userid;
		$cnt++;
		}
	
}else{

	$ArrayDoctors[$cnt]=$userid;
	$cnt++;
}

$contad = 0;
$cadena='';

while($cnt>0){
		$cnt--;
		$userid=$ArrayDoctors[$cnt];
		$result = $con->prepare("SELECT * FROM doctorslinkdoctors WHERE IdMED2 = ? ORDER BY IdMED DESC");
		$result->bindValue(1, $userid, PDO::PARAM_INT);
		$result->execute();

		while ($row = $result->fetch(PDO::FETCH_ASSOC)) {

		$referral_date=$row['Fecha'];

		$referral_id = $row['id'];
		$result2B = $con->prepare("select stage,datevisit from referral_stage where referral_id=?");
		$result2B->bindValue(1, $referral_id, PDO::PARAM_INT);
		$result2B->execute();
		
		$row2B = $result2B->fetch(PDO::FETCH_ASSOC);
		$referral_stage=$row2B['stage'];
		$last_date=$row2B['datevisit'];
			   
		$seekthis = $row['IdMED'];
		$IdReferral = $row['IdMED'];     
		$result2 = $con->prepare("SELECT * FROM doctors WHERE id = ? LIMIT 15");
		$result2->bindValue(1, $seekthis, PDO::PARAM_INT);
		$result2->execute();
		
		$emailD2='';
		$NameD2='';
		$SurnameD2='';

		while ($row2 = $result2->fetch(PDO::FETCH_ASSOC)) {
			$NameD2 = empty($row2['Name'])? '' : $row2['Name'];
			$SurnameD2 = empty($row2['Surname'])? '' : $row2['Surname'];
			
			$emailD2=$row2['IdMEDEmail'];
			
			if($NameD2=='' and $SurnameD2==''){
			   $NameD2 = $row2['IdMEDEmail'];
			}
			$RoleD2 = $row2['Role'];
			$TreatD2 = '';
			if ($RoleD2 == '1') $TreatD2 = 'Dr.';
		}
		$seekthis = $row['IdPac'];
		$IdPatient = $row['IdPac'];     

		$result2 = $con->prepare("SELECT * FROM usuarios WHERE Identif = ? LIMIT 15");
		$result2->bindValue(1, $seekthis, PDO::PARAM_INT);
		$result2->execute();

		while ($row2 = $result2->fetch(PDO::FETCH_ASSOC)) {
			$NameP = $row2['Name'];
			$SurnameP = $row2['Surname'];
			$idP = $row2['Identif'];
		}
			
		$daystovisit = 0;
		if ($referral_stage > 1)
		{
			//$dayscolor = 'grey';
			//$current_date = date("Y-m-d");
			//$current_date = $referral_date;
			$current_date = date("Y-m-d");
			$db_date = $last_date;//date("Y-m-d");
			//echo ' ***: '.$current_date.' / '.$db_date;
			//$diff = abs(strtotime($current_date) - strtotime($db_date));
			$diff = abs(strtotime($current_date) - strtotime($referral_date));
			$daystovisit = floor ($diff /  (60*60*24));
			if ($referral_stage == 4) {$dayscolor='orange'; $text_time='Time-to-Visit';}
		}
			
			
			
		$TreatD2 = 'Dr. ';    
		if ($contad>0) $cadena.=',';    
		if (strlen($SurnameD2) > 7) $addChar = '.'; else $addChar ='';
		$FormDrName = strtoupper(substr($SurnameD2,0,1)).substr($SurnameD2,1,6).$addChar.', '.strtoupper(substr($NameD2,0,1));
		$FullDrName = strtoupper(substr($SurnameD2,0,1)).substr($SurnameD2,1).', '.strtoupper(substr($NameD2,0,1)).'.';
            
		$cadena.='
			{
				"IdPatient":"'.$idP.'",
				"PatientName":"'.$NameP.' '.$SurnameP.'",
				"PatientStage":"'.$referral_stage.'",
				"PatientDoctor":"'.$FormDrName.'",
				"PatientDoctorFULL":"'.$FullDrName.'",
				"EmailDoctor":"'.$emailD2.'",
				"IdMED":"'.$IdReferral.'",
				"DateReferred":"'.$referral_date.'",
				"DateStage":"'.$last_date.'",
				"TTV":"'.$daystovisit.'"
				}';    
		$contad++;
		}
	}

	header('Content-type: application/json');
		/*
		$encode = json_encode($chat);
		echo '{"items":'. json_encode($chat) .'}'; 
		*/

	$encode = json_encode($cadena);
	echo '{"items":['.($cadena).']}'; 
		

?>
