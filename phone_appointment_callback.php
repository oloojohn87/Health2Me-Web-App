<?php
require "environment_detailForLogin.php";
require "Services/Twilio.php";
$version = '2010-04-01';
//require_once('realtime-notifications/pusherlib/lib/Pusher.php');
require_once('push/push.php');
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

//$app_key = 'd869a07d8f17a76448ed';
//$app_secret = '92f67fb5b104260bbc02';
//$app_id = '51379';
//$pusher = new Pusher($app_key, $app_secret, $app_id);
$push = new Push();


$rec_url = $_POST['RecordingUrl'];
$file = "recordings/".basename($rec_url);

if(isset($_GET['info']))
{

    $info = explode("_", $_GET['info']);
    $doc_id = $info[0];
    $pat_id = $info[1];
    $recent_id;
    $callStatus;

    //Start of updating the ameridoc_calls table - Pallab
    if(count($info) > 2)
    {
        //$file = fopen("test.txt","w");
        //echo fwrite($file,"Hello World. Testing!");
        //fclose($file);

        //$recent_id = $info[2];

        $callSID = $info[3];


        // Set Twilio AccountSid and AuthToken
        $sid = "AC109c7554cf28cdfe596e4811c03495bd";
        $token = "26b187fb3258d199a6d6edeb7256ecc1";
        $client = new Services_Twilio($sid, $token);
        $call = $client->account->calls->get($callSID);
        $callStatus = $call->status;

        //mysql_query("update ameridoc_calls set endTime = curtime(), endDate = curdate() where consultationId = $recent_id and patientId =".$pat_id." and doctorId =".$doc_id);
    }

    //End of updating the ameridoc_calls table - Pallab

    //finding the length/duration of the just ended consulation 

    //$query = mysql_query("select startTime,endTime from ameridoc_calls where consultationId =".$recent_id);
    //$result = mysql_fetch_array($query);

    
    $result = $con->prepare("SELECT * FROM consults WHERE Doctor = ? AND Patient = ? ORDER BY DateTime DESC");
    $result->bindValue(1, $doc_id, PDO::PARAM_INT);
    $result->bindValue(2, $pat_id, PDO::PARAM_INT);
    $result->execute();
    $row = $result->fetch(PDO::FETCH_ASSOC);
    
    $recent_id = $row['consultationId'];
	
	

    $consultation_start_time = explode(" ", $row['DateTime']);
    $startTime = strtotime($consultation_start_time[1]);//strtotime($result['startTime']);
    $result = $con->prepare("SELECT CURTIME() as time");
    $result->execute();
    $row = $result->fetch(PDO::FETCH_ASSOC);
    $endTime = strtotime($row['time']);//strtotime($result['endTime']);
    $timeDiff = $endTime - $startTime;

    //updating the consults table

    $result = $con->prepare("UPDATE consults SET Status ='Completed', Recorded_File = ? WHERE consultationId = ? && Doctor_Status = ? && Patient_Status = ?");
    $result->bindValue(1, $file, PDO::PARAM_STR);
    $result->bindValue(2, $recent_id, PDO::PARAM_INT);
	$result->bindValue(3, 1, PDO::PARAM_INT);
	$result->bindValue(4, 1, PDO::PARAM_INT);
    $result->execute();
	
	$result = $con->prepare("UPDATE consults SET Status ='Not answered by doctor.', Recorded_File = ? WHERE consultationId = ? && Doctor_Status is null && Patient_Status = ?");
    $result->bindValue(1, $file, PDO::PARAM_STR);
    $result->bindValue(2, $recent_id, PDO::PARAM_INT);
	$result->bindValue(3, 1, PDO::PARAM_INT);
    $result->execute();


    //finding whether any new report created by the doctor
    $query = $con->prepare("SELECT count(*) AS count FROM lifepin WHERE idcreator = ?");
    $query->bindValue(1, $doc_id, PDO::PARAM_INT);
    $query->execute();
    $result = $query->fetch(PDO::FETCH_ASSOC);
    
    $countOfReportsInLifePin = $result['count'];
    
    $query = $con->prepare("SELECT reportsCreated FROM doctors_calls WHERE id = ?");
    $query->bindValue(1, $doc_id, PDO::PARAM_INT);
    $query->execute();
    $result = $query->fetch(PDO::FETCH_ASSOC);
    
    $currentCountOfReports = $result['reportsCreated'];
    $diff = $countOfReportsInLifePin - $currentCountOfReports;
    if($diff > 0)
    {
      $currentCountOfReports = $countOfReportsInLifePin; 
    }

    //Updating the doctors_calls table
    $query = $con->prepare("SELECT * FROM doctors_calls WHERE id = ?");
    $query->bindValue(1, $doc_id, PDO::PARAM_INT);
    $query->execute();
    $result = $query->rowCount();
    if($result > 0)
    {  
        $query = $con->prepare("UPDATE doctors_calls SET reportsCreated  = ?,numberOfConsultations = numberOfConsultations + 1 where id = ?");
        $query->bindValue(1, $currentCountOfReports, PDO::PARAM_INT);
        $query->bindValue(2, $doc_id, PDO::PARAM_INT);
        $query->execute();
    }

    else
    {        
        $query = $con->prepare("SELECT name,surname FROM doctors WHERE id = ?");
        $query->bindValue(1, $doc_id, PDO::PARAM_INT);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_ASSOC);
        
        $doctorName = "'".$result['name']."'";
        $doctorSurname = "'".$result['surname']."'";
        $numberOfPatientsConsulted = 1;
        $numberOfConsultations = 1;
        
        $query = $con->prepare("INSERT INTO doctors_calls (id,name,surname,reportsCreated,numberOfConsultedPatients,numberOfConsultations) VALUES(?,?,?,?,?,?)");
        $query->bindValue(1, $doc_id, PDO::PARAM_INT);
        $query->bindValue(2, $doctorName, PDO::PARAM_STR);
        $query->bindValue(3, $doctorName, PDO::PARAM_STR);
        $query->bindValue(4, $countOfReportsInLifePin, PDO::PARAM_INT);
        $query->bindValue(5, $numberOfPatientsConsulted, PDO::PARAM_INT);
        $query->bindValue(6, $numberOfConsultations, PDO::PARAM_INT);
        $query->execute();
    }

    //updating the usuarios table
    $query = $con->prepare("UPDATE usuarios SET numberOfPhoneCalls = numberOfPhoneCalls + 1");
    $query->execute();

        
    $query = $con->prepare("SELECT Name,Surname FROM doctors WHERE id = ?");
    $query->bindValue(1, $doc_id, PDO::PARAM_INT);
    $query->execute();
    $row = $query->fetch(PDO::FETCH_ASSOC);

    $doc_name = $row['Name'].' '.$row['Surname'];
    
    $query = $con->prepare("SELECT Name,Surname FROM usuarios WHERE Identif = ?");
    $query->bindValue(1, $pat_id, PDO::PARAM_INT);
    $query->execute();
    $row = $query->fetch(PDO::FETCH_ASSOC);

    $pat_name = $row['Name'].' '.$row['Surname'];
    $push->send('Telemedicine', 'ameridoc', 'Consultation Between Doctor '.$doc_name.' and patient '.$pat_name.' ended.');
    
    $query = $con->prepare("UPDATE doctors SET in_consultation=0,telemed_type=0 WHERE id = ?");
    $query->bindValue(1, $doc_id, PDO::PARAM_INT);
    $query->execute();
}

$src = fopen($rec_url, 'r');
$dest = fopen($file, 'w');
stream_copy_to_stream($src, $dest);

$enc_pass= $con->prepare("SELECT pass FROM encryption_pass WHERE id = (SELECT max(id) FROM encryption_pass)");
$enc_pass->execute();
$row_enc = $enc_pass->fetch(PDO::FETCH_ASSOC);
$enc_pass=$row_enc['pass'];
//    $cmd = escapeshellarg(getcwd().'\\Encrypt.bat').' '.escapeshellarg(getcwd().'\\recordings').' '.basename($rec_url).' '.escapeshellarg($enc_pass);
shell_exec("echo '".$enc_pass."' | openssl aes-256-cbc -pass stdin -salt -in recordings/".basename($rec_url)." -out recordings/recordings/".basename($rec_url)."");
//    exec($cmd);
?>