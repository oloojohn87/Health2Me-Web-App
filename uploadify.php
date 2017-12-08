<?php
/*
Uploadify
Copyright (c) 2012 Reactive Apps, Ronnie Garcia
Released under the MIT License <http://www.opensource.org/licenses/mit-license.php> 
*/

 session_start();
 require "environment_detail.php";
$dbhost = $env_var_db['dbhost'];
$dbname = $env_var_db['dbname'];
$dbuser = $env_var_db['dbuser'];
$dbpass = $env_var_db['dbpass'];
		
		
$path_for_ffmpeg = "ffmpeg\\bin\\";

if(isset($_GET['pullfile'])){
$newid = $_GET['pullfile'];
}

//Below SQl queries commented by Pallab
/*
$link = mysql_connect("$dbhost", "$dbuser", "$dbpass")or die("cannot connect");
mysql_select_db("$dbname")or die("cannot select DB");
*/

//Below lines were added by Pallab

// Connect to server and select databse.
// Start of new code added by Pallab
$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }	
//End of new code added by Pallab


//Below SQL lines were commented by Pallab
/*
$enc_result = mysql_query("select pass from encryption_pass where id = (select max(id) from encryption_pass)");
$row_enc = mysql_fetch_array($enc_result);
$enc_pass=$row_enc['pass'];
*/

//Start of new code added by Pallab
$enc_result = $con->prepare("select pass from encryption_pass where id = (select max(id) from encryption_pass)");
$enc_result->execute();
$row_enc = $enc_result->fetch(PDO::FETCH_ASSOC);
$enc_pass = $row_enc['pass'];
//End of new code added by Pallab


// Define a destination
$targetFolder = 'PatientImage'; // Relative to the root

//Below SQL queries were updated by Pallab
//$grab_id = mysql_query('SELECT * FROM usuarios ORDER BY Identif DESC LIMIT 1');
//$id_holder = mysql_fetch_array($grab_id);

//Start of new code added by Pallab
$grab_id = $con->prepare("SELECT * FROM usuarios ORDER BY Identif DESC LIMIT 1");
$grab_id->execute();
$id_holder = $grab_id->fetch(PDO::FETCH_ASSOC);

//End of new code added by Pallab

$verifyToken = md5('unique_salt' . $_POST['timestamp']);

//Below SQL commented by Pallab
//mysql_query("INSERT INTO debug_table SET 2='1', 3='2'");
$query = $con->prepare("INSERT INTO debug_table SET 2='1', 3='2'");
$query->execute();

if (!empty($_FILES) && $_POST['token'] == $verifyToken) {
	$tempFile = $_FILES['Filedata']['tmp_name'];
	$targetPath = $_SERVER['DOCUMENT_ROOT'] . $targetFolder;
	$targetFile = rtrim($targetPath,'/') . '/' .$_FILES['Filedata']['name'];
	$dimLess= "0";
	// Validate the file type
	$fileTypes = array('jpg','jpeg','gif','png'); // File extensions
	$fileParts = (pathinfo($_FILES['Filedata']['name']));
	$extension = strtolower($fileParts['extension']);
	//$newTargetFile = rtrim($targetPath,'/') . '/' .$newid.'.'.($extension);
	$newTargetFile = rtrim($targetPath,'/') . '/' .$newid.'.'.($extension);
	//rename("PatientImage\\".$_SESSION['MEDID'].'.'.($fileParts['extension']),"PatientImage\\".$_SESSION['MEDID'].'.'.$extension);
	foreach($fileTypes as $ext)
	{
	 if(file_exists("PatientImage/".$newid.".".$ext))
	 {
	 	unlink("PatientImage/".$newid.".".$ext);
		//echo "Session file unlinked";
		break;
	 }
	}	
	if (in_array($extension ,$fileTypes)) {
		move_uploaded_file($tempFile,$newTargetFile);		
		
		//Code Added by Ankit
		if(($extension )=='jpg')
		{
		$target='PatientImage/';
			$path="/usr/lib/x86_64-linux-gnu/ImageMagick-6.7.7";  //path of ImageMagick
			$cadenaConvert = 'convert '.$target. '/' .$newid.'.'.($extension).' '.$target. '/' .$newid.'.jpg 2>&1';
			//echo $cadenaConvert;
			shell_exec($cadenaConvert);
			//unlink($target. '/' .$_SESSION['MEDID'].'.'.($fileParts['extension']));
		}
		else
		//if($fileParts['extension']!='jpg' || $fileParts['extension']!='jpeg')
		{
			$target='PatientImage/';
			$path="/usr/lib/x86_64-linux-gnu/ImageMagick-6.7.7";  //path of ImageMagick
			$cadenaConvert = 'convert '.$target. '/' .$newid.'.'.($extension).' '.$target. '/' .$newid.'.jpg 2>&1';
			//echo $cadenaConvert;
			shell_exec($cadenaConvert);
			//unlink($target. '/' .$_SESSION['MEDID'].'.'.($fileParts['extension']));
		} 
		//--------------------------------------------------------------
		list($width, $height, $type, $attr) = getimagesize(( 'PatientImage/' .$newid.'.jpg'));
		if($width<80 || $height<80)
		{
			 $dimLess= "1";
			 unlink($newTargetFile);
		}
		echo $targetFolder. '/' .$newid.'.jpg|'.$dimLess;
	} else {
		echo 'fileError';
	}
}
?>