<?php
require("environment_detail.php");
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

$path_parts = "";
$erase = false;
$erase_file = "";
$is_recording = false;
if(isset($_POST['file']))
{
    $path_parts = pathinfo($_POST['file']);
}
if(isset($_POST['erase']))
{
    $erase = true;
}
if(isset($_POST['erase_file']))
{
    $erase_file = $_POST['erase_file'];
}
if(isset($_POST['recording']))
{
    $is_recording = $_POST['recording'];
}

if($erase)
{
    set_time_limit (27);
    sleep(25);
    echo unlink($erase_file);
}
else if($is_recording)
{
    $enc_result = $con->prepare("select pass from encryption_pass where id = (select max(id) from encryption_pass)");
	$enc_result->execute();
    $row_enc = $enc_result->fetch(PDO::FETCH_ASSOC);
    $enc_pass=$row_enc['pass'];
    $cmd = 'echo "'.$enc_pass.'" | openssl aes-256-cbc -pass stdin -d -in recordings/recordings/'.$path_parts['basename'].' -out temp/'.$path_parts['basename'].'.mp3';
    shell_exec($cmd);
    
    echo 'temp/'.$path_parts['basename'].'.mp3';
}
else
{
    $enc_result = $con->prepare("select pass from encryption_pass where id = (select max(id) from encryption_pass)");
	$enc_result->execute();
    $row_enc = $enc_result->fetch(PDO::FETCH_ASSOC);
    $enc_pass=$row_enc['pass'];
    $cmd = 'echo "'.$enc_pass.'" | openssl aes-256-cbc -pass stdin -d -in '.$path_parts['dirname'].'/'.$path_parts['basename'].' -out temp/'.$path_parts['basename'];
    shell_exec($cmd);
    
    echo 'temp/'.$path_parts['basename'];
}
?>
