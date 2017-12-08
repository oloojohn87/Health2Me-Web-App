<?php
require('environment_detail.php');
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

$med_id = $_POST['med_id'];
$state = $_POST['state'];
$type = $_POST['type'];
if($state != '-1') {
$query = $con->prepare("SELECT additional_licenses FROM doctors WHERE id = ?");
$query->bindValue(1, $med_id, PDO::PARAM_INT);
$query->execute();
 
$row = $query->fetch(PDO::FETCH_ASSOC);

if($type != 'display'){
    if($type == 'add'){        
        if($row['additional_licenses'] != '') {
            $addflag = true;
            $additional_states = explode(", ", $row['additional_licenses']);
            foreach($additional_states as $additional_state) {
                if($additional_state == $state) $addflag = false;
            }    
            if($addflag) $state = $row['additional_licenses'].', '.$state;
            else $state = $row['additional_licenses'];
        }
    }elseif($type == 'remove'){
        $state = preg_replace(('/\b('.$state.', |, '.$state.'|'.$state.')\b/i'), '', $row['additional_licenses']);
    }
    $query = $con->prepare("UPDATE doctors SET additional_licenses = ? WHERE id = ?");
    $query->bindValue(1, $state, PDO::PARAM_STR);
    $query->bindValue(2, $med_id, PDO::PARAM_INT);
    $query->execute();
}else{
echo $row['additional_licenses'];
}
}
?>
