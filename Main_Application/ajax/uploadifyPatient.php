<?php
//header('Content-Type: text/plain; charset=utf-8');
error_reporting(E_ALL);
ini_set('display_errors', '1');
require('environment_detail.php');
$dbhost = $env_var_db['dbhost']; 
$dbname = $env_var_db['dbname'];
$dbuser = $env_var_db['dbuser'];
$dbpass = $env_var_db['dbpass'];
$hardcode = $env_var_db['hardcode'];
$local = $env_var_db['local'];

if(isset($_GET['pullfile'])) $newid = $_GET['pullfile'];

if(isset($_GET['pulldoc'])) $newid = 'temp_'.$_GET['pulldoc'];
$sub_dir = '';
if(isset($_GET['upload'])){
	if($_GET['upload'] == 'license') $sub_dir = 'licenses/';
}
error_log('filename: '.$_FILES['fileToUpload2']['tmp_name'].'\n');
try {
    
    // Undefined | Multiple Files | $_FILES Corruption Attack
    // If this request falls under any of them, treat it invalid.
    if (
        !isset($_FILES['fileToUpload2']['error'])
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
	$dir_holder = 'PatientImage';
	//}
    if(file_exists($dir_holder.'/'.$sub_dir.$newid.'.jpg'))
    {
        unlink($dir_holder.'/'.$sub_dir.$newid.'.jpg');
    }
    if (!move_uploaded_file('../../'.$_FILES['fileToUpload2']['tmp_name'], '../../'.$dir_holder.'/'.$sub_dir.$newid.'.jpg')) {
        throw new RuntimeException('Failed to move uploaded file.');
        
    }
    
    /*$dir = new DirectoryIterator('PatientImage');
	foreach ($dir as $fileinfo) {
		if (!$fileinfo->isDot() && $fileinfo != $newid.'.jpg' && $_GET['upload'] == 'profile') {
			$output = shell_exec("br -algorithm FaceRecognition -compare PatientImage/".$newid.".jpg PatientImage/".$fileinfo);
			
			if($output > .95){
				shell_exec("rm -f PatientImage/".$newid.".jpg");
			}
		}
	}*/

    echo 'File is uploaded successfully.';

} catch (RuntimeException $e) {

    echo $e->getMessage();

}

?>
