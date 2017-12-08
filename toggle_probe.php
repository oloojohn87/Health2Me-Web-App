<?php

require("environment_detail.php");
require("NotificationClass.php");
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

$notifications = new Notifications();

$res = -1;

$status = $_POST['status'];
$doc = $_POST['doctor'];
$pat = $_POST['patient'];

// see if there already is an entry in the probe table for this probe
$probe = null;
$check = $con->prepare("SELECT * FROM probe WHERE doctorID = ? AND patientID = ?");
$check->bindValue(1, $doc, PDO::PARAM_INT);
$check->bindValue(2, $pat, PDO::PARAM_INT);
$check->execute();
if($check->rowCount() > 0)
{
    $probe = $check->fetch(PDO::FETCH_ASSOC);
    
    if($status == 'on')
    {
        if($probe['desiredTime'] != NULL && strlen($probe['desiredTime']) > 0 && $probe['protocolID'] != 0 && $probe['probeInterval'] != 0 && $probe['timezone'] != 0 && ($probe['emailRequest'] == 1 || $probe['smsRequest'] == 1 || $probe['phoneRequest'] == 1) && $probe['patientPermission'] == 1)
        {
            $tok = strtok($probe['desiredTime'], ":");
            $hours = $tok;
            $tok = strtok(":");
            $ampm = substr($tok,strlen($tok)-2);
            $minutes = substr($tok,0,strlen($tok)-2);
            //$time = $hours.':'.$minutes.' '.$ampm;
            //echo $time;

            if($hours == '12' && $ampm == 'am')
                $hours = '00';
            else if($ampm == 'pm' && $hours != '12')
                $hours = strval(intval($hours) + 12);
            if(strlen($hours) == 1)
                $hours = '0'.$hours;
                
            $userDate = date('Y-m-d', strtotime($probe['creationDate']));
            $userTime = $userDate.' '.$hours.':'.$minutes.':00';

            $tzq = $con->prepare("SELECT offset FROM timezones WHERE id = ?");
            $tzq->bindValue(1, $probe['timezone'], PDO::PARAM_INT);
            $tzq->execute();
            $tzr = $tzq->fetch(PDO::FETCH_ASSOC);
            $tz = $tzr['offset'];

            $correctDateQ = $con->prepare("SELECT CONVERT_TZ(?, ?, '+00:00') AS tz");
            $correctDateQ->bindValue(1, $userTime, PDO::PARAM_STR);
            $correctDateQ->bindValue(2, $tz, PDO::PARAM_STR);
            $correctDateQ->execute();
            $correctDateR = $correctDateQ->fetch(PDO::FETCH_ASSOC);
            $correctDate = $correctDateR['tz'];

            /*$userTime =  strftime("%H:%M",strtotime($time));


            //Get Name for Timezone
            $query = $con->prepare("SELECT name FROM timezones WHERE id = ?");
            $query->bindValue(1, $probe['timezone'], PDO::PARAM_INT);
            $result = $query->execute();

            $row = $query->fetch(PDO::FETCH_ASSOC);
            $timezone = $row['name'];



            //Get Current Date
            $currentDate =  Date('Y-m-d');


            //Convert CurrentDate+UserTime to GMT (because MYSQL server follows GMT)
            $date_str = $currentDate.' '.$userTime; 
            $GMTDateTime = $date_str;//convertToGMT($timezone, $date_str);



            //Extract time from GMTDateTime
            $tok = strtok($GMTDateTime, " ");
            $GMTTime  = strtok(" ");


            $query = $con->prepare("select timediff(?,curtime()) as timediff,curdate() as currdate,curtime() as currtime");
            $query->bindValue(1, $GMTTime, PDO::PARAM_STR);
            $result=$query->execute();

            $row = $query->fetch(PDO::FETCH_ASSOC);
            $timediff = $row['timediff'];
            $currdate = $row['currdate'];
            $currtime = $row['currtime'];
            $correctDate = $currdate.' '.$GMTTime;

            if(substr($timediff,0,1)=="-")
            {
                //forward day by 1 day
                $newDate = date('Y-m-d', strtotime('+1 day', strtotime($currdate)));
                $correctDate = $newDate.' '.$GMTTime;
            }
            */
            $del = $con->prepare("DELETE FROM pendingprobes WHERE probeID = ?");
            $del->bindValue(1, $probe['probeID'], PDO::PARAM_INT);
            $del->execute();
            
            $ins2 = $con->prepare("INSERT INTO pendingprobes SET probeID = ?, requestTime = ?");
            $ins2->bindValue(1, $probe['probeID'], PDO::PARAM_INT);
            $ins2->bindValue(2, $correctDate, PDO::PARAM_STR);
            $ins2->execute();

            $ins3 = $con->prepare("SELECT TIMESTAMPDIFF(MINUTE, NOW(), requestTime) AS diff FROM pendingprobes WHERE probeID = ?");
            $ins3->bindValue(1, $probe['probeID'], PDO::PARAM_INT);
            $ins3->execute();
            $ins3_row = $ins3->fetch(PDO::FETCH_ASSOC);
            $diff = $ins3_row['diff'];
            if(intval($diff) <= -4)
            {
                $upd = $con->prepare("UPDATE pendingprobes SET requestTime = DATE_ADD(requestTime, INTERVAL 1 DAY) WHERE probeID = ?");
                $upd->bindValue(1, $probe['probeID'], PDO::PARAM_INT);
                $upd->execute();
            }

            $update = $con->prepare("UPDATE probe SET doctorPermission = 1 WHERE probeID = ?");
            $update->bindValue(1, $probe['probeID'], PDO::PARAM_INT);
            $update->execute();
            $res = 1;
        }
        else if(($probe['desiredTime'] == NULL || strlen($probe['desiredTime']) == 0) || $probe['protocolID'] == 0 || $probe['probeInterval'] == 0 || $probe['timezone'] == 0 || ($probe['emailRequest'] == 0 && $probe['smsRequest'] == 0 && $probe['phoneRequest'] == 0))
        {
            $res = -1;
            echo $res;
            exit();
        }
    }
    else if($status == 'off')
    {
        $del = $con->prepare("DELETE FROM pendingprobes WHERE probeID = ?");
        $del->bindValue(1, $probe['probeID'], PDO::PARAM_INT);
        $del->execute();

        $update = $con->prepare("UPDATE probe SET doctorPermission = 0 WHERE probeID = ?");
        $update->bindValue(1, $probe['probeID'], PDO::PARAM_INT);
        $update->execute();

        $res = 1;
    }
    if($probe['patientPermission'] == 0)
    {
		$res = -2;
	}
    
}
else
{
    $res = -1;
}

echo $res;

?>
