<?php
 /*KYLE
 require("environment_detail.php");
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];

$tbl_name="doctors"; // Table name

$link = mysql_connect("$dbhost", "$dbuser", "$dbpass")or die("cannot connect");
mysql_select_db("$dbname")or die("cannot select DB");	

$queUsu = $_GET['Doctor'];
$NReports = $_GET['NReports'];

$result = mysql_query("SELECT * FROM doctorslinkdoctors WHERE IdMED2 = '$queUsu' ORDER BY Fecha LIMIT 15 ");
$n=0;

while ($row = mysql_fetch_array($result)) {

$n++;
if ($n == 1){
	echo '<span class="label label-info" id="EtiTML" style="margin:10px; margin-left:0px; margin-bottom:20px; font-size:16px; text-shadow:none; text-decoration:none;">Patients Referred to you</span>';
	echo '<div style="width:100%; margin-bottom:20px;"></div>';
}

$referral_id = $row['id'];
$result2B = mysql_query("select stage,datevisit from referral_stage where referral_id='$referral_id'");
$row2B = mysql_fetch_array($result2B);
$referral_stage=$row2B['stage'];
switch ($referral_stage){
    case 1: $LetStage='A';
            break;
    case 2: $LetStage='B';
            break;
    case 3: $LetStage='C';
            break;
    case 4: $LetStage='D';
            break;
}
$whatStage = "<div class='LetterCircleONSmall'>".$LetStage."</div>";

$RefDate = $row['Fecha'];

$seekthis = $row['IdMED'];
$result2 = mysql_query("SELECT * FROM doctors WHERE id = '$seekthis' LIMIT 15");

while ($row2 = mysql_fetch_array($result2)) {
	$NameD2 = (htmlspecialchars($row2['Name']));
	$SurnameD2 = (htmlspecialchars($row2['Surname']));
	$RoleD2 = $row2['Role'];
	$TreatD2 = '';
	if ($RoleD2 == '1') $TreatD2 = 'Dr.';
}
$seekthis = $row['IdPac'];
$result2 = mysql_query("SELECT * FROM usuarios WHERE Identif = '$seekthis' LIMIT 15");

while ($row2 = mysql_fetch_array($result2)) {
	$NameP = (htmlspecialchars($row2['Name']));
	$SurnameP = (htmlspecialchars($row2['Surname']));
}

$formattedDate = date("F j, Y",strtotime($RefDate));
//$formattedDate = $RefDate;



//echo '<span aria-hidden="true" class="iconH-Referred2 img-rounded" style="float:left; margin-top:0px; font-size:24px; color:#54bc00; margin-right:10px; "></span>';
echo '<a href="patientdetailMED-new.php?IdUsu='.$seekthis.'" style="text-decoration:none;"><p> '.$whatStage.'   '.$formattedDate.' <span style="color: #54bc00; font-size:16px;">'.$NameP.' '.$SurnameP.'</span> by <span style="color: #22aeff; font-size:16px;">'.$TreatD2.' '.$SurnameD2.', '.$NameD2.'</span></p></a>';

}
    

?>