<?php

$strServer = "remote.zenithtranscriptions.com";
$strServerPort = "22";
$strServerUsername = "5000ubi-emr";
$strServerPassword = "0295";

//connect to server
$resConnection = ssh2_connect($strServer, $strServerPort);

if(ssh2_auth_password($resConnection, $strServerUsername, $strServerPassword)){
	//Initialize SFTP subsystem
	$resSFTP = ssh2_sftp($resConnection);
	
	
	$dh = opendir("ssh2.sftp://$resSFTP/Transcribed"); 

	while (($file = readdir($dh)) !== false) { 
	echo "$file"; 
	} 

	closedir($dh); 

}else{
	echo "Unable to authenticate on server";
}

?>