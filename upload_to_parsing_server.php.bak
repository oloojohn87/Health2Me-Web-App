<?php

function upload_to_parsing_server($filename)
{
	$ds = DIRECTORY_SEPARATOR;  
	
	$local_file_path = "Packages_Encrypted";
	$remote_file_path = "htdocs".$ds."Parsing_Transferred";
	$server = "54.225.100.7";
	$ftp_username = "ITGroup";
	$ftp_pass = "stream";
	
	$connection = ftp_connect($server,$port = 21);
	
	$login_result = ftp_login($connection,$ftp_username,$ftp_pass);
	ftp_pasv($connection, true);
	if (!$connection || !$login_result) { 
						
						die('Connection attempt failed!'); 
						}
						
	$remote = $remote_file_path.$ds.$filename;					
	$local = $local_file_path.$ds.$filename;
	//echo $remote;
	//echo "<br>".$local;
	//ftp_chdir($connection, "htdocs");
	ftp_chdir($connection, "Parsing_Transferred");
	if(ftp_put($connection,$filename, $local, FTP_BINARY))
	{
		ftp_close($connection);
		return true;
	}
	else
	{
		ftp_close($connection);
		return false;
	}
	

}


?>
