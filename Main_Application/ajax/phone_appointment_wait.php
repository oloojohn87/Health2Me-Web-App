<?php echo '<?xml version="1.0" encoding="UTF-8" ?>' ?>
<?php
require("environment_detailForLogin.php");
$dbhost = $env_var_db["dbhost"];
$dbname = $env_var_db["dbname"];
$dbuser = $env_var_db["dbuser"];
$dbpass = $env_var_db["dbpass"];
$hardcode = $env_var_db["hardcode"];
$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
{
    die('Could not connect: ' . mysql_error());
}

$info = explode("-", $_GET['info']);


$result = $con->prepare("SELECT in_consultation FROM doctors where id = ?");
$result->bindValue(1, $info[0], PDO::PARAM_INT);
$result->execute();
$row = $result->fetch(PDO::FETCH_ASSOC);
$count = $row["in_consultation"];

$doc_id = $info[0];
$pat_id = $info[3];
$recent_id = $info[4];


//if($count == '2')
//{
    //$result = mysql_query("UPDATE doctors SET in_consultation=3 WHERE id=".$doc_id);
    $result = $con->prepare("SELECT most_recent_doc,GrantAccess FROM usuarios WHERE Identif = ?");
    $result->bindValue(1, $pat_id, PDO::PARAM_INT);
    $result->execute();
    $row = $result->fetch(PDO::FETCH_ASSOC);
    $str = $row['most_recent_doc'];
    $res = "";
    if(strlen($str) > 0)
    {
        $str = str_replace(array("[", "]"), "", $str);
        $ids = explode(",", $str);
        $found = false;
        for($i = 0; $i < count($ids); $i++)
        {
            if($ids[$i] == $doc_id)
            {
                $found = true;
            }
        }
        if(!$found)
        {
            array_unshift($ids , $doc_id);
            while(count($ids) > 5)
            {
                $doc = array_pop($ids);
            }
        }
        $res = implode(",", $ids);
    }
    else
    {
        $res = $doc_id;
    }
    $new_ids = "[".$res."]";
    $result = $con->prepare("UPDATE usuarios SET most_recent_doc = ? WHERE Identif = ?");
    $result->bindValue(1, $new_ids, PDO::PARAM_STR);
    $result->bindValue(2, $pat_id, PDO::PARAM_INT);
    $result->execute();


//}
//else
//{
//    $result = mysql_query("UPDATE doctors SET in_consultation=2 WHERE id=".$doc_id);
    
//}

if(isset($_GET['Digits']))
{

    echo '<Response>';
    echo '<Dial action="phone_appointment_handle_leave.php?id='.$doc_id.'" method="GET">';
    echo '<Conference record="record-from-start" maxParticipants="2" endConferenceOnExit="true" eventCallbackUrl="phone_appointment_callback.php?info='.$info[0].'_'.$info[3].'_'.$info[4].'">'.$info[2].'</Conference>';
    echo '</Dial>';
    echo '</Response>';
	
	$result = $con->prepare("UPDATE consults SET Patient_Status = ? WHERE consultationId = ?");
    $result->bindValue(1, 1, PDO::PARAM_INT);
    $result->bindValue(2, $recent_id, PDO::PARAM_INT);
    $result->execute();

}
else
{
if($_GET['grant_access'] == 'HTI-COL'){
echo '<Response>';
    echo '<Gather timeout="5" action="'.$hardcode.'phone_appointment_wait.php?info='.str_replace(" ", "%20", $_GET['info']).'" method="GET" numDigits="1"><Say voice="alice" language="es-MX">Usted esta a punto de comenzar su orientación con el médico '.$info[1].'. Por favor marque cualquier digito para confirmar.</Say></Gather>';//Changing to digit 5 to identify as patient
    echo '<Redirect/>';

    echo '</Response>';
}else if($_GET['grant_access'] == 'HTI-RIVA'){
echo '<Response>';
    echo '<Gather timeout="5" action="'.$hardcode.'phone_appointment_wait.php?info='.str_replace(" ", "%20", $_GET['info']).'" method="GET" numDigits="1"><Say>We are redirecting you to the call center.  Press any digit to confirm.</Say></Gather>';//Changing to digit 5 to identify as patient
    echo '<Redirect/>';

    echo '</Response>';
}else{
    echo '<Response>';
    echo '<Gather timeout="5" action="'.$hardcode.'phone_appointment_wait.php?info='.str_replace(" ", "%20", $_GET['info']).'" method="GET" numDigits="1"><Say>You are about to enter a consultation with doctor '.$info[1].'. Please press any digit to confirm.</Say></Gather>';//Changing to digit 5 to identify as patient
    echo '<Redirect/>';

    echo '</Response>';
	}
}

?>
