<?php
ini_set("display_errors", 0);
session_start(); 
 require("environment_detailForLogin.php");
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

$UserID = $_SESSION['UserID'];
//$UserID = 1313;

$query = $con->prepare("select * from p_medication where deleted=0 and idpatient=? ORDER BY numDays DESC");
$query->bindValue(1, $UserID, PDO::PARAM_INT);
$result = $query->execute();

$count = $query->rowCount();

$query2 = $con->prepare("select * from p_medication where deleted=0 and drugname='No Medications' and idpatient=? ORDER BY numDays DESC");
$query2->bindValue(1, $UserID, PDO::PARAM_INT);
$result2 = $query2->execute();

$count2 = $query2->rowCount();

if($count2 > 0){
echo '<center>';
	echo  '<table style="background-color:white;" id="TablaPac">';
	echo '<div style="width:315px; height:20px; border:0px solid; text-align:center; background-color: #18bc9c; color: white;line-height: 20px;" lang="en">';
	if($_COOKIE["lang"] == 'en'){
	echo 'Medications';
	}else{
	echo 'Medicamentos';
	}
	echo '</div>';
	echo '<tr><td style="background-color:#cacaca; text-align:center;width:315px; height:20px;color:white;"><span lang="en">';
	if($_COOKIE["lang"] == 'en'){
	echo 'Not Taking Medications';
	}else{
	echo 'No tomar la medicación';
	}
	echo '</span></td></tr>';
	echo '</table>';
	echo '</center>';
	exit();
}

if($count==0)
{
	echo '<center>';
	echo  '<table style="background-color:white;" id="TablaPac">';
	echo '<div style="width:100%; height:20px; border:0px solid; text-align:center; background-color: #18bc9c; color: white;line-height: 20px;" lang="en">';
	if($_COOKIE["lang"] == 'en'){
	echo 'Medications';
	}else{
	echo 'Medicamentos';
	}
	echo '</div>';
	echo '<tr><td><span><center>';
	if($_COOKIE["lang"] == 'en'){
	echo 'No Entries';
	}else{
	echo 'No hay entradas';
	}
	echo '</center></span><p><center><img width="75px" src="/images/icons/general_user_error_icon.png" alt="No Data Icon"></center></td></tr>';
	echo '</table>';
	echo '</center>';
	return;
}


//echo '<div  style="width:315px; height:16px; border:0px solid; text-align:center; background-color: #22aeff;color: white;line-height: 16px;">Medications</div>';
echo '<div  style="width:100%; height:20px; border:0px solid; text-align:center; background-color: #18bc9c; color: white;line-height: 20px;" lang="en">';
	if($_COOKIE["lang"] == 'en'){
	echo 'Medications';
	}else{
	echo 'Medicamentos';
	}
	echo '</div>';

echo  '<table style="background-color:white; margin-top:5px;" id="TablaPac">';

$latest_update = '1975-01-01 00:00:00';
$doctor_signed = -1;

while($row = $query->fetch(PDO::FETCH_ASSOC))
{
	$numDays = $row['numDays'];
	$drugname = $row['drugname'];
	$dossage = $row['dossage'];
	
	$doctor_signedP = $row['doctor_signed'];
	$latest_updateP = $row['latest_update'];
	if (strtotime($latest_updateP) > strtotime($latest_update)) { $latest_update = $latest_updateP; $doctor_signed = $doctor_signedP;}
	
	echo '<tr>';
	if($numDays==-1)
	{
		echo '<td style="width:30px;height:16px; background-color:'.getColor($numDays).';text-align:center"><span style="color:white;">n/a</span></td>';
	}
	else
	{
		echo '<td style="width:30px;height:18px; background-color:'.getColor($numDays).';text-align:center"><span style="color:white;">'.$numDays.'</span></td>';
	}
	
	echo '<td><div style="width:150px; float:left; text-align:left;"><span style="margin-left:20px;" class="PatName">'.$drugname.'</span></div></td>';
	echo '<td><div style="float:left; text-align:left; color:#22aeff;font-size:10px;margin-left:20px"><span class="DrName" lang="en">'.$dossage.' </span></div></td>';
	echo '</tr>';

}

echo '</table>';

echo '<input id="Mdoctor_signed" value="'.$doctor_signed.'" style="width:20px; float:left; display:none;">';	//display:none;
echo '<input id="Mlatest_update" value="'.$latest_update.'" style="width:120px; float:left; display:none;">';	


function getColor($numDays)
{
	if($numDays==-1)
	{
		return '#BCF5A9';
	}
	else if($numDays>=0 and $numDays<=10)
	{
		return '#9FF781';	
	}
	else if($numDays>=11 and $numDays<=30)
	{
		return '#82FA58';	
	}
	else if($numDays>=31 and $numDays<=90)
	{
		return '#64FE2E';	
	}
	else if($numDays>=91 and $numDays<=180)
	{
		return '#74DF00';	
	}
	else if($numDays>=181 and $numDays<=365)
	{
		return '#5FB404';	
	}
	else if($numDays>=366 and $numDays<=999)
	{
		return '#4B8A08';	
	}
	else
	{
		return '#38610B';	
	}




}

?>