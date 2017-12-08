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
$hardcode = $env_var_db['hardcode'];
$local = $env_var_db['local'];

if(isset($_GET['pulldoc'])) $newid = $_GET['pulldoc'];
try {
    
    // Undefined | Multiple Files | $_FILES Corruption Attack
    // If this request falls under any of them, treat it invalid.
    if (
        !isset($_FILES['fileToUpload2']['error']) ||
        is_array($_FILES['fileToUpload2']['error'])
    ) {
        throw new RuntimeException('Invalid parameters.');
    }

    // Check $_FILES['fileToUpload2']['error'] value.
    switch ($_FILES['fileToUpload2']['error']) {
        case UPLOAD_ERR_OK:
            break;
        case UPLOAD_ERR_NO_FILE:
            throw new RuntimeException('No file sent.');
        case UPLOAD_ERR_INI_SIZE:
        case UPLOAD_ERR_FORM_SIZE:
            throw new RuntimeException('Exceeded filesize limit.');
        default:
            throw new RuntimeException('Unknown errors.');
    }

    // You should also check filesize here. 
    if ($_FILES['fileToUpload2']['size'] > 1000000) {
        $fileActualSize = strval(ceil($_FILES['fileToUpload2']['size']/1000000)).' MB'; 
        throw new RuntimeException('Exceeded filesize limit. Your filesize: '.$fileActualSize.'. Please upload a picture less than 1MB.');
    }

    // DO NOT TRUST $_FILES['fileToUpload2']['mime'] VALUE !!
    // Check MIME Type by yourself.
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    if (false === $ext = array_search(
        $finfo->file($_FILES['fileToUpload2']['tmp_name']),
        array(
            'jpg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
        ),
        true
    )) {
        throw new RuntimeException('Invalid file format.');
    }

    // You should name it uniquely.
    // DO NOT USE $_FILES['fileToUpload2']['name'] WITHOUT ANY VALIDATION !!
    // On this example, obtain safe unique name from its binary data.
	/*if(isset($_GET['pulldoc'])){
	$dir_holder = 'DocPatImage';
	}else{*/
	$dir_holder = 'DoctorImage';
	//}
    if (!move_uploaded_file(
        $_FILES['fileToUpload2']['tmp_name'],
        sprintf($dir_holder.'/'.$newid.'.jpg',
            sha1_file($_FILES['fileToUpload2']['tmp_name']),
            $ext
        )
    )) {
        throw new RuntimeException('Failed to move uploaded file.');
    }
    
    /*$dir = new DirectoryIterator('DoctorImage');
	foreach ($dir as $fileinfo) {
		if (!$fileinfo->isDot() && $fileinfo != $_GET['pulldoc'].'.jpg' && $_GET['upload'] == 'profile') {
			$output = shell_exec("br -algorithm FaceRecognition -compare PatientImage/".$_GET['pullfile'].".jpg PatientImage/".$fileinfo);
			
			if($output > .95){
				shell_exec("rm -f PatientImage/".$_GET['pullfile'].".jpg");
			}
		}
	}*/

    echo 'File is uploaded successfully.';

} catch (RuntimeException $e) {

    echo $e->getMessage();

}

		
/*$path_for_ffmpeg = "ffmpeg\\bin\\";

// Connect to server and select databse.
$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }		*/

//Below SQl lines were commented by Pallab
/*$enc_result = mysql_query("select pass from encryption_pass where id = (select max(id) from encryption_pass)");
$row_enc = mysql_fetch_array($enc_result);
$enc_pass=$row_enc['pass']; */

/*$enc_result = $con->prepare("select pass from encryption_pass where id = (select max(id) from encryption_pass)");
$enc_result->execute();
$row_enc = $enc_result->fetch(PDO::FETCH_ASSOC);
$enc_pass = $row_enc['pass'];


// Define a destination
$targetFolder = 'DoctorImage'; // Relative to the root

$verifyToken = md5('unique_salt' . $_POST['timestamp']);

if (!empty($_FILES) && $_POST['token'] == $verifyToken) {
	$tempFile = $_FILES['Filedata']['tmp_name'];
	$targetPath = $_SERVER['DOCUMENT_ROOT'] . $targetFolder;
	$targetFile = rtrim($targetPath,'/') . '/' .$_FILES['Filedata']['name'];
	$dimLess= "0";
	// Validate the file type
	$fileTypes = array('jpg','jpeg','gif','png'); // File extensions
	$fileParts = (pathinfo($_FILES['Filedata']['name']));
	$extension = strtolower($fileParts['extension']);
	$newTargetFile = rtrim($targetPath,'/') . '/' .$_SESSION['MEDID'].'.'.($extension);
	
	foreach($fileTypes as $ext)
	{
	 if(file_exists("DoctorImage/".$_SESSION["MEDID"].".".$ext))
	 {
	 	unlink("DoctorImage/".$_SESSION["MEDID"].".".$ext);
		//echo "Session file unlinked";
		break;
	 }
	}	
	if (in_array($extension ,$fileTypes)) {
		move_uploaded_file($tempFile,$newTargetFile);		
		//Code Added by Ankit
		if(($extension )=='jpg')
		{
		$target='DoctorImage/';
			$path="/usr/lib/x86_64-linux-gnu/ImageMagick-6.7.7";  //path of ImageMagick
			$cadenaConvert = 'convert '.$target. '/' .$_SESSION['MEDID'].'.'.($extension).' '.$target. '/' .$_SESSION['MEDID'].'.jpg 2>&1';
			//echo $cadenaConvert;
			shell_exec($cadenaConvert);
		}
		else
		//if($fileParts['extension']!='jpg' || $fileParts['extension']!='jpeg')
		{
			$target='DoctorImage/';
			$path="/usr/lib/x86_64-linux-gnu/ImageMagick-6.7.7";  //path of ImageMagick
			$cadenaConvert = 'convert '.$target. '/' .$_SESSION['MEDID'].'.'.($extension).' '.$target. '/' .$_SESSION['MEDID'].'.jpg 2>&1';
			//echo $cadenaConvert;
			shell_exec($cadenaConvert);
			//unlink($target. '/' .$_SESSION['MEDID'].'.'.($fileParts['extension']));
		} 
		//--------------------------------------------------------------
		list($width, $height, $type, $attr) = getimagesize(( 'DoctorImage/' .$_SESSION['MEDID'].'.jpg'));
		if($width<80 || $height<80)
		{
			 $dimLess= "1";
			 unlink($newTargetFile);
		}
		echo $targetFolder. '/' .$_SESSION['MEDID'].'.jpg|'.$dimLess;
	} else {
		echo 'fileError';
	}
}*/
?>