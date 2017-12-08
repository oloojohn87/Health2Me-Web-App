<?php
error_reporting(E_ALL);
    ini_set('display_errors', '1');
 require("environment_detail.php");
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];

$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
		
		if (!$con){
			die('Could not connect: ' . mysql_error());
			}

$foundDocs = array();
                    
$_SESSION['MEDID'] = 2806;
$IdUsu = 2653;

//IF THE PATIENT HAS BEEN REFERRED, NOTIFY THIS OCCURRENCE TO THE DOCTORS WHO REFERRED THIS PATIENT
$findRefDocs = $con->prepare('SELECT IdMED, IdMED2 FROM doctorslinkdoctors WHERE IdMED = ? OR IdMED2 = ? AND IdPac = ? UNION SELECT IdMED, null AS IdMED2 FROM doctorslinkusers WHERE IdUs = ?');
$findRefDocs->bindValue(1, $_SESSION['MEDID'], PDO::PARAM_INT);
$findRefDocs->bindValue(2, $_SESSION['MEDID'], PDO::PARAM_INT);
$findRefDocs->bindValue(3, $IdUsu, PDO::PARAM_INT);
$findRefDocs->bindValue(4, $IdUsu, PDO::PARAM_INT);
$findRefDocs->execute();

while($RefDocRow = $findRefDocs->fetch(PDO::FETCH_ASSOC)) {
    if(!in_array($RefDocRow['IdMED'], $foundDocs)) array_push($foundDocs, $RefDocRow['IdMED']);
    else if($RefDocRow['IdMED2'] !== null && !in_array($RefDocRow['IdMED2'], $foundDocs)) array_push($foundDocs, $RefDocRow['IdMED2']);
}

var_dump($foundDocs);

?>