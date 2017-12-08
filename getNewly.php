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

$result2 = mysql_query("SELECT DISTINCT IdUsu FROM lifepin WHERE IdCreator = '$queUsu' AND DATEDIFF(NOW(), FechaInput) <= 7 ORDER BY FechaInput DESC ");
$counter1 = 0 ;
while ($row2 = mysql_fetch_array($result2)) {
	$UsIds[$counter1] = $row2['IdUsu'];
	//echo 'Id= '.$counter1.'User='.$UsIds[$counter1].'    ...  ';
	$counter1++;
}


$counter2 = 0 ;
while ($counter2 < ($counter1 - 1)) {
	$UserId = $UsIds[$counter2];
	$result2 = mysql_query("SELECT * FROM usuarios WHERE Identif = '$UserId'"); 
	$row2 = mysql_fetch_array($result2);
	if ($counter2 == 0)
	{
		echo '<span class="label label-info" id="EtiTML" style="margin:10px; margin-left:0px; margin-bottom:20px; font-size:16px; text-shadow:none; text-decoration:none;">Your recent EMR activity</span>';
		echo '<div style="width:100%; margin-bottom:20px;"></div>';
	}

	$NameP = (htmlspecialchars($row2['Name']));
	$SurnameP = (htmlspecialchars($row2['Surname']));
	$seekthis = $row2['Identif'];

	echo '<a href="patientdetailMED-new.php?IdUsu='.$seekthis.'" style="text-decoration:none;"><p style="margin-bottom:0px;"><span style="color: #22aeff; font-size:16px; margin-right:10px;">'.$counter2.'  <i class="icon-caret-right"></i></span><span style="color: #54bc00; font-size:16px;">'.$NameP.' '.$SurnameP.'</span></p></a>';

	$counter2++;
}





    

?>