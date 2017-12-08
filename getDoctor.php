<?php
session_start();
require("environment_detail.php");
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

if(isset($_SESSION['MEDID']))
{
    $doc = array();
    
    $doctor = $con->prepare("SELECT * FROM doctors WHERE id = ?");
    $doctor->bindValue(1, $_SESSION['MEDID'], PDO::PARAM_INT);
    $doctor->execute();
    $doctor_row = $doctor->fetch(PDO::FETCH_ASSOC);
    
    // generate identicon
    $identicon = 'identicon.php?size=29&hash='.md5( strtolower( trim( $doctor_row['IdMEDEmail'] ) ) );
    
    // check if doctor can access dashboard.php
    $canAccessDashboard = checkAccessPage($doctor_row['id'], 'dashboard.php', $con);
    
    $doc['id'] = $doctor_row['id'];
    $doc['first_name'] = $doctor_row['Name'];
    $doc['last_name'] = $doctor_row['Surname'];
    $doc['email'] = $doctor_row['IdMEDEmail'];
    $doc['identicon'] = $identicon;
    $doc['privilege'] = intval($doctor_row['previlege']);
    $doc['canAccessDashboard'] = $canAccessDashboard;
    
    echo json_encode($doc);
}
else
{
    echo 'NULL';
}

function checkAccessPage($doc, $page, $con){
    
    $result = $con->prepare("select pc.accessid,pc.page from pageAccessControl pc INNER JOIN (select idGroup from doctorsgroups where idDoctor=?) dg where dg.idGroup=pc.groupid and pc.page=?");
    $result->bindValue(1, $doc, PDO::PARAM_INT);
    $result->bindValue(2, $page, PDO::PARAM_STR);
    $result->execute();


    if($result->rowCount() > 0)   
    {
        $row = $result->fetch(PDO::FETCH_ASSOC);
        return $row['accessid'] === 1; 
    }
    else
    {
        return false;
    }
}


?>