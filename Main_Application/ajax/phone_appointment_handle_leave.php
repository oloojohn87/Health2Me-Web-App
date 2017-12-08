<?php echo '<?xml version="1.0" encoding="UTF-8" ?>' ?>
<?php
require("environment_detailForLogin.php");
$dbhost = $env_var_db["dbhost"];
$dbname = $env_var_db["dbname"];
$dbuser = $env_var_db["dbuser"];
$dbpass = $env_var_db["dbpass"];
$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
{
    die('Could not connect: ' . mysql_error());
}	

if(isset($_GET['id']))
{
    $doc_id = $_GET['id'];
	$consult_id = $_GET['consultid'];
    
    $query = $con->prepare("UPDATE doctors SET in_consultation=0,telemed_type=0 WHERE id = ?");
    $query->bindValue(1, $doc_id, PDO::PARAM_INT);
    $query->execute();
    
    $query = $con->prepare("SELECT * FROM consults WHERE Doctor = ? AND Status = 'In Progress' ORDER BY DateTime DESC LIMIT 1");
    $query->bindValue(1, $doc_id, PDO::PARAM_INT);
    $query->execute();
    $row = $query->fetch(PDO::FETCH_ASSOC);

    $recent_id = $row['consultationId'];
	
	if($row['Doctor_Status'] == null && $row['Patient_Status'] == 1){
	$result55 = $con->prepare("UPDATE consults SET Status ='Not Answered By Doctor' WHERE consultationId = ?");
    $result55->bindValue(1, $recent_id, PDO::PARAM_INT);
    $result55->execute();
	}
	
	if($row['Patient_Status'] == null && $row['Doctor_Status'] == 1){
	$result55 = $con->prepare("UPDATE consults SET Status ='Not Answered By Patient' WHERE consultationId = ?");
    $result55->bindValue(1, $recent_id, PDO::PARAM_INT);
    $result55->execute();
	}
	
	if($row['Patient_Status'] != 1 && $row['Doctor_Status'] != 1){
	$result55 = $con->prepare("UPDATE consults SET Status ='Not Answered By Patient or Doctor' WHERE consultationId = ?");
    $result55->bindValue(1, $recent_id, PDO::PARAM_INT);
    $result55->execute();
	}
	
    $consultation_start_time = explode(" ", $row['DateTime']);
    $startTime = strtotime($consultation_start_time[1]);//strtotime($result['startTime']);
    $result = $con->prepare("SELECT CURTIME() as time");
    $result->execute();
    $row = $result->fetch(PDO::FETCH_ASSOC);
    $endTime = strtotime($row['time']);//strtotime($result['endTime']);
    $timeDiff = $endTime - $startTime;
	
    $query = $con->prepare("UPDATE consults SET Status='Completed', Length = ? WHERE Doctor = ? && Status='In Progress' && Doctor_Status = 1 && Patient_Status = 1");
    $query->bindValue(1, $timeDiff, PDO::PARAM_INT);
    $query->bindValue(2, $doc_id, PDO::PARAM_INT);
    $query->execute();
	
}
echo '<Response><Hangup/></Response>';
?>