<?php
$id = $_POST['user'];

if(file_exists("PatientImage/".$id.".jpg") && file_exists("PatientImage/licenses/".$id.".jpg")){
	$output = shell_exec("br -algorithm FaceRecognition -compare /var/www/html/PatientImage/".$id.".jpg /var/www/html/PatientImage/licenses/".$id.".jpg");
	echo ($output * 100)."% Match";
}
?>
