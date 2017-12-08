<?php
require "environment_detail.php";
$dbhost = $env_var_db['dbhost'];
$dbname = $env_var_db['dbname'];
$dbuser = $env_var_db['dbuser'];
$dbpass = $env_var_db['dbpass'];
$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if ($link && isset($_FILES["video-blob"]) && isset($_FILES["audio-blob"])) 
{
    if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }		

    $enc_result = $con->prepare("select pass from encryption_pass where id = (select max(id) from encryption_pass)");
	$enc_result->execute();
	
	$row_enc = $enc_result->fetch(PDO::FETCH_ASSOC);
    $enc_pass=$row_enc['pass'];
    $bat_cmd = getcwd().'\\rtc.bat '.escapeshellarg($_FILES["video-blob"]["tmp_name"]).' '.escapeshellarg($_FILES["audio-blob"]["tmp_name"]).' '.escapeshellarg($_POST["video-filename"]).' '.escapeshellarg($_POST["audio-filename"]).' '.$_POST['id'].' '.$_POST['user_type'].' '.escapeshellarg(getcwd()).' '.escapeshellarg($enc_pass);
    echo $bat_cmd;
    execInBackground($bat_cmd);
}
else
{
    echo "Files not set";
}

function execInBackground($cmd)
{
    $p = popen('start /b '.$cmd, "r");
    sleep(2);
    pclose($p);  
}
?>