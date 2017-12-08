<?php

 require("environment_detail.php");
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];
 session_start();

// Connect to server and select databse.
$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }	
  
$enc_result = $con->prepare("select pass from encryption_pass where id = (select max(id) from encryption_pass)");
$enc_result->execute();
			$row_enc = $enc_result->fetch(PDO::FETCH_ASSOC);
			$enc_pass=$row_enc['pass'];

$oldName = $_GET['oldName'];
$newName=$_GET['newName'];
//sleep(8);

//FOR THE OCURRENCE OF A PATIENT PROFILE PHOTO INITIALLY UPLOADED BY A DOCTOR
if(file_exists("DocPatImage/".$oldName))
{
	rename("DocPatImage/".$oldName,"DocPatImage/".$newName);
    
    shell_exec("echo '".$enc_pass."' | openssl aes-256-cbc -pass stdin -salt -in DocPatImage/ ".$newName." ".$enc_pass." -out temp/".$newName);
}

if(file_exists("PatientImage/".$oldName))
{
	rename("PatientImage/".$oldName,"PatientImage/".$newName);
	//MAYBE REVISIT THIS
	//shell_exec('Encrypt.bat PatientImage '.$newName.' '.$enc_pass);          //Encrypt the image (prasanna)
	
	shell_exec("echo '".$enc_pass."' | openssl aes-256-cbc -pass stdin -salt -in PatientImage/ ".$newName." ".$enc_pass." -out temp/".$newName);
}
 $fileTypes = array('jpg','jpeg','gif','png'); // File extensions
 foreach($fileTypes as $ext)
 {
	if(file_exists("../htdocs/PatientImage/".$_SESSION["MEDID"].".".$ext))
	{
		unlink("../htdocs/PatientImage/".$_SESSION["MEDID"].".".$ext);
		//echo "Session file unlinked";
	}
 }

?>