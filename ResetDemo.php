<?php

session_start();
require("environment_detail.php");
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];

$capture = $_GET['Tipo'];

echo 'Starting procedure as "'.$capture.'"';
echo '<br/>';
				
// Connect to server and select database.
//$link = mysql_connect("$dbhost", "$dbuser", "$dbpass")or die("cannot connect");
mysql_select_db("$dbname")or die("cannot select DB");

//$result = mysql_query("SELECT * FROM usuarios where IdUsFIXEDNAME='$NombreEnt' and IdUsRESERV='$PasswordEnt'");
//$result = mysql_query("SELECT * FROM usuarios where Identif='$IdUsu'");

$ValidReports = array();
$ValidReports[1]='1531';
$ValidReports[2]='1532';
$ValidReports[3]='1533';
$ValidReports[4]='1546';
$ValidReports[5]='1541';
$ValidReports[6]='1542';

$ValidDoctor = array();
$ValidDoctor[1]='45';
$ValidDoctor[2]='46';
$ValidDoctor[3]='47';
$ValidDoctor[4]='48';
$ValidDoctor[5]='49';

$ValidUser = array();
$ValidUser[1]='1133';
$ValidUser[2]='1134';
$ValidUser[3]='1135';

// Operations over Lifepin table
// RESET LIFEPIN *** Erase all reports created by demo doctors not being validreports
$maxdoctors = count($ValidDoctor);
$tobedeleted = 0;
$n=1;
while ($n <= $maxdoctors)
{
	$thisdoctor = $ValidDoctor[$n];
	$result = mysql_query("SELECT * FROM Lifepin WHERE IdMed='$thisdoctor'");
	while ($row = mysql_fetch_array($result)) {
		if (!in_array($row['IdPin'], $ValidReports)) 
		{
			if ($capture == 'n' || $capture == 'N')
			{
			//echo 'Report Id = '.$row['IdPin'].' will be deleted';
			//echo '<br/>';
			$tobedeleted ++;
			}
		}
	}
	$n ++;
}
if ($capture == 'n' || $capture == 'N')
{
echo $tobedeleted.' reports from Lifepin will be deleted (doctor created). ------------';
echo '<br/>';
}
// ENTER HERE VALIDATION CAPTURE OF S/N
if ($capture == 's' || $capture == 'S')
	{
	$tobedeleted = 0;
	$n=1;
	while ($n <= $maxdoctors)
	{
		$thisdoctor = $ValidDoctor[$n];
		$result2 = mysql_query("SELECT * FROM Lifepin WHERE IdMed='$thisdoctor'");
		while ($row2 = mysql_fetch_array($result2)) {
			if (!in_array($row2['IdPin'], $ValidReports)) 
			{
				$pinborra = $row2['IdPin'];
				$borUSUDOC = mysql_query("DELETE FROM Lifepin WHERE IdMed='$thisdoctor' AND IdPin='$pinborra'");
				$tobedeleted ++;
			}
		}
		$n ++;
	}
echo ' ****  '.$tobedeleted.' reports FROM lifepin DELETED (doctor created)';
echo '<br/>';
}
// ALSO IN LIFEPIN *** Erase all reports created by demo users (via mobile apps)
// As this is done AFTER erasing reports created by doctors it is assumed that all reports from demousers (except the ones that we keep in array ValidReports) are marked to be deleted
$maxusers = count($ValidUser);
$tobedeleted = 0;
$n=1;
while ($n <= $maxusers)
{
	$thisuser = $ValidUser[$n];
	$result = mysql_query("SELECT * FROM Lifepin WHERE IdUsu='$thisuser'");
	while ($row = mysql_fetch_array($result)) {
		if (!in_array($row['IdPin'], $ValidReports)) 
		{
			if ($capture == 'n' || $capture == 'N')
			{
				//echo 'Report Id = '.$row['IdPin'].' will be deleted';
				//echo '<br/>';
				$tobedeleted ++;
			}
		}
	}
	$n ++;
}
if ($capture == 'n' || $capture == 'N')
{
echo $tobedeleted.' reports from Lifepin will be deleted (user created). ------------';
echo '<br/>';
}
// ENTER HERE VALIDATION CAPTURE OF S/N
if ($capture == 's' || $capture == 'S')
	{
		$maxusers = count($ValidUser);
		$tobedeleted = 0;
		$n=1;
		while ($n <= $maxusers)
		{
			$thisuser = $ValidUser[$n];
			$result3 = mysql_query("SELECT * FROM Lifepin WHERE IdUsu='$thisuser'");
			while ($row3 = mysql_fetch_array($result3)) {
				if (!in_array($row3['IdPin'], $ValidReports)) 
				{
					if ($capture == 's' || $capture == 'S')
					{
						$pinborra = $row3['IdPin'];
						$borPINUSU = mysql_query("DELETE FROM Lifepin WHERE IdUsu='$thisuser' AND IdPin = '$pinborra'");
						$tobedeleted ++;
					}
				}
			}
			$n ++;
		}
		echo ' ****  '.$tobedeleted.' reports FROM lifepin DELETED (user created)';
		echo '<br/>';
	}

// RESET DLD *** Erase all reports that link 2 demodoctors
$n=1;
$tobeerased = 0;
while ($n <= $maxdoctors)
{
	$thisdoctor = $ValidDoctor[$n];
	$m=1;
	while ($m <= $maxdoctors)
	{
		$thisdoctor2 = $ValidDoctor[$m];
		//echo '* '.$thisdoctor.'-'.$thisdoctor2.' *';
		$result = mysql_query("SELECT * FROM doctorslinkdoctors WHERE IdMED='$thisdoctor' AND IdMED2='$thisdoctor2'");
		$count=mysql_num_rows($result);
		//echo '('.$count.')';
		$tobeerased = $tobeerased + $count;
		$m ++;
	}
	$n ++;
}
if ($capture == 'n' || $capture == 'N')
{
echo $tobeerased.' record from DLD will be deleted. ------------';
echo '<br/>';
}
	// ENTER HERE VALIDATION CAPTURE OF S/N
	if ($capture == 's' || $capture == 'S')
	{
		$n=1;
		$tobeerased = 0;
		while ($n <= $maxdoctors)
		{
			$thisdoctor = $ValidDoctor[$n];
			$m=1;
			while ($m <= $maxdoctors)
			{
				$thisdoctor2 = $ValidDoctor[$m];
				//echo '* '.$thisdoctor.'-'.$thisdoctor2.' *';
				//$result = mysql_query("SELECT * FROM doctorslinkdoctors WHERE IdMED='$thisdoctor' AND IdMED2='$thisdoctor2'");
				$resultD = mysql_query("DELETE FROM doctorslinkdoctors WHERE IdMED='$thisdoctor' AND IdMED2='$thisdoctor2'");
				$countD=mysql_affected_rows();
				//echo '('.$count.')';
				$tobeerased = $tobeerased + $countD;
				$m ++;
			}
			$n ++;
		}
		echo ' ****  '.$tobeerased.' reports from DLD DELETED';
		echo '<br/>';
	}

// RESET DLU *** Erase all reports that link 1 demodoctor and 1 demouser 
// doctor x created user y , doctor x2 created user y2  (*** this is an exception) in the first place
$doctorX = 46;
$userY = 1135;
$doctorX2 = 45;
$userY2 = 1134;
$n=1;
while ($n <= $maxdoctors)
{
	$thisdoctor = $ValidDoctor[$n];
	$tobeerased = 0;
	$maxusers = count($ValidUser);
	$m=1;
	while ($m <= $maxusers)
	{
		$thisuser = $ValidUser[$m];
		$dontdo = 0;
		if ($thisdoctor == $doctorX && $thisuser == $userY) $dontdo = 1;
		if ($thisdoctor == $doctorX2 && $thisuser == $userY2) $dontdo = 1;
		if ($dontdo == 0) 
			{
			$result = mysql_query("SELECT * FROM doctorslinkusers where IdMed='$thisdoctor' AND IdUs='$thisuser'");
			$count=mysql_num_rows($result);
			$tobeerased = $tobeerased + $count;
			}
		$m ++;
	}
	$n++;
}
if ($capture == 'n' || $capture == 'N')
{
echo $tobeerased.' records from DLU will be deleted. ------------';
echo '<br/>';
}
// ENTER HERE VALIDATION CAPTURE OF S/N
if ($capture == 's' || $capture == 'S')
	{
	$doctorX = 46;
	$userY = 1135;
	$doctorX2 = 45;
	$userY2 = 1134;
	$n=1;
	while ($n <= $maxdoctors)
	{
		$thisdoctor = $ValidDoctor[$n];
		$tobeerased = 0;
		$maxusers = count($ValidUser);
		$m=1;
		while ($m <= $maxusers)
		{
			$thisuser = $ValidUser[$m];
			$dontdo = 0;
			if ($thisdoctor == $doctorX && $thisuser == $userY) $dontdo = 1;
			if ($thisdoctor == $doctorX2 && $thisuser == $userY2) $dontdo = 1;
			if ($dontdo == 0) 
				{
				//$result = mysql_query("SELECT * FROM doctorslinkusers where IdMed='$thisdoctor' AND IdUs='$thisuser'");
				$resultU = mysql_query("DELETE FROM doctorslinkusers where IdMed='$thisdoctor' AND IdUs='$thisuser'");
				$countU=mysql_affected_rows();
				$tobeerased = $tobeerased + $countU;
				}
			$m ++;
		}
		$n++;
	}
	echo ' ****  '.$tobeerased.' reports from DLU DELETED';
	echo '<br/>';
	}

// RESET Usuarios *** Reset Temporary fields for all demousers
$reseted = 0;
$maxusers = count($ValidUser);
if ($capture == 'n' || $capture == 'N')
{
echo 'Reports from '.$maxusers.' users will be reseted in table usuarios. ------------';
echo '<br/>';
}
if ($capture == 's' || $capture == 'S')
	{
	$m=1;
	while ($m <= $maxusers)
		{
			$thisuser = $ValidUser[$m];
			$result = mysql_query("UPDATE usuarios SET TempoPass = '', Password = '', Verificado = '', IdUsRESERV = '', confirmcode = ''   WHERE Identif='$thisuser'");
			$count=mysql_affected_rows();
			$reseted = $reseted + $count;
			$m ++;
		}
	echo ' ****  '.$reseted.' reports EDITED FROM usuarios';
	echo '<br/>';
	}

echo '**** Reset procedure for demo completed ****';
echo '<br/>';


