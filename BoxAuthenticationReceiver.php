<?php session_start();

## Include the Dropbox SDK libraries
set_time_limit(3600);
define('INCLUDE_CHECK',1);
require "logger.php";

$path_for_ffmpeg = "ffmpeg\\bin\\";


//This is for connecting to the database tables
echo "Initiating Database Connection....";
echo "<br>";

echo "<br>";
require "environment_detail.php";
$dbhost = $env_var_db['dbhost'];
$dbname = $env_var_db['dbname'];
$dbuser = $env_var_db['dbuser'];
$dbpass = $env_var_db['dbpass'];
/*$dbhost = "dev.health2.me";
$dbname = "monimed";
$dbuser = "monimed";
$dbpass = "ardiLLA98";	*/			
// Connect to server and select databse.

//$link = mysql_connect("$dbhost", "$dbuser", "$dbpass")or die("cannot connect");
if(!$link)
{
    echo "Failed to Connect to database!!";
    echo "<br>";
}
else 
{
    echo "Database Connection Successful!!";
    echo "<br>";
    
    mysql_select_db("$dbname")or die("cannot select DB");
    // check dicom session to see if there are any that are ready to transfer to Indra. if there are, transfer them
    // connect to indra server
	//diacomcode-start
   /* $indra_server = "54.225.67.15";
    $indra_ftp_username = "Indra";
    $indra_ftp_pass = "IndraH2M14";
    
    $indra_connection = ftp_connect($indra_server,$port = 21);
    $indra_login_result = ftp_login($indra_connection,$indra_ftp_username,$indra_ftp_pass);
    ftp_pasv($indra_connection, true);
    if (!$indra_connection || !$indra_login_result) 
    { 
        echo 'Connection attempt failed!'; 
    }
    else
    {
        echo "Connection Successful";
    }
    if($indra_connection && $indra_login_result)
    {
        ftp_chdir($indra_connection, "INDRA\ISPACS\INTEGRACION\DicomIN");		//Path where we want to create new folder
        
        
        $Isql = "SELECT * FROM dicom_sessions WHERE ready_for_transfer=1";
        $res = mysql_query($Isql);
        while($row = mysql_fetch_assoc($res))
        {
            $contents_on_server = ftp_nlist($indra_connection, ".");
            $idpin = $row['id'];
            if (!in_array($row['indra_folder'], $contents_on_server)) 
            {
                if (ftp_mkdir($indra_connection, $row['indra_folder'])) 
                {
                    echo "successfully created row['indra_folder']\n";
                } 
                else 
                {
                    echo "There was a problem while creating row['indra_folder']\n";
                }
            }
            ftp_chdir($indra_connection, $row['indra_folder']);
            
            $DICOM_dir = "DICOM";
            $contents_on_server = ftp_nlist($indra_connection, ".");
            if (!in_array($DICOM_dir, $contents_on_server)) 
            {
                if (ftp_mkdir($indra_connection, $DICOM_dir)) 
                {
                    echo "successfully created $DICOM_dir\n";
                } 
                else 
                {
                    echo "There was a problem while creating $DICOM_dir\n";
                }
            }
            ftp_chdir($indra_connection, $DICOM_dir);
            upload_to_indra($row['id'], $row['processed_folder'], 0, $row['transfered'], $indra_connection);
            $q2 = "SELECT * FROM lifepin WHERE IdPin=".$idpin;
            $res2 = mysql_query($q2);
            if($row2 = mysql_fetch_assoc($res2))
            {
                $pat_id = $row2['IdUsu'];
                upload_dicom_xml($row['indra_folder'], $idpin, $pat_id, $indra_connection);
                $q3 = "UPDATE dicom_sessions SET ready_for_transfer=2 WHERE id=".$idpin;
                $res3 = mysql_query($q3);
            }
        }
        ftp_close($indra_connection);
    }*/
	
	//diacom code-end
    
    $IsqlX="UPDATE vitalidad SET Fecha=NOW(), Programa = 'CloudChannelUpdate.php' WHERE IdProg = 3";
    $qX = mysql_query($IsqlX);
    // Grabación de un registro con fecha en la dB para comprobar la VITALIDAD
    
    //Get Encryption Password (added by Ankit)
    $enc_result = mysql_query("select pass from encryption_pass where id = (select max(id) from encryption_pass)");
    $row_enc = mysql_fetch_array($enc_result);
    $enc_pass=$row_enc['pass'];
   // $userid = $_SESSION['MEDID'];
    
    //Adding changes for the new BOX channel-start
	/* Create a new mysqli object with database connection parameters */
	$mysqli = new mysqli($dbhost, $dbuser, $dbpass, $dbname);

	   if(mysqli_connect_errno()) {
		  echo "Connection Failed: " . mysqli_connect_errno();
		  exit();
	   }
	   
   
   
		/* Create a prepared statement */
	   if($stmt = $mysqli -> prepare("select IdMEDEmail,AccessToken,folderid,processedfolderid from boxuserdetails")) {
		$stmt -> execute();
		 
	    $res = $stmt->get_result();

		   $count=0;
		   for ($row_no = ($res->num_rows - 1); $row_no >= 0; $row_no--) {
				$res->data_seek($row_no);
				//var_dump($res->fetch_assoc());
				$token[$count]=$res->fetch_assoc();
				$count++;
				 //var_dump($refreshtoken);
			}
			$res->close();

		  /* Close statement */
		  $stmt -> close();
	   }
   
    //mysql_close($link);
    if($count>0) 
    {

        echo "Start scanning BOX channel accounts...";
        echo "<br>\n";
        echo $count;
        echo "<br>\n";    
        echo date(DATE_RFC822);
        echo "<br>\n"; 	
		
      for($i=0;$i<count($token);$i++){
	 	
		 
			$ch = curl_init();			
			$headr = array();
			$headr[] = 'Authorization: Bearer '.$token[$i]['AccessToken'];
			
			$folderid=$token[$i]['folderid'];
			
			echo 'header details: ';
			var_dump($headr);
			
			$result=getFolderDetails($headr,$folderid);
			
			
			$response =json_decode($result, true);

            //$EmailDOC = $accountInfo['email'];
			$EmailDOC=$response['owned_by']['login'];
            $fromaddress=$token[$i]['IdMEDEmail'];	//This has to be IdMEDEmail for BOX details 
            $fromname= $response['owned_by']['name'];	//This has to be name of the person who as the BOX account
			$copyfolderid=$token[$i]['processedfolderid']; //This is the folderid for the processed folder stored in the database
			
            $q = mysql_query("SELECT * FROM doctors WHERE IdMEDEmail = '".$fromaddress."'");
            $row2 = mysql_fetch_assoc($q);
            $IdDoc = $row2['id'];
            echo "Scanning ";
            print_r($fromname);
            echo "Dropbox folder <br>";
            //echo "Account Info: " .$accountInfo;
            echo "<br>";            

            print("Folder Contents <br>");
            $size=0;         
			
			$size=$response['item_collection']['total_count'];			
			
            if ($size > 0)
            {
                echo "Total Files present: ";
                echo $size;
                echo "<br>\n"; 	 
            
                for ($i = 0; $i < $size; $i++)
                {
                   
					$filename=$response['item_collection']['entries'][$i]['name'];
					//BOX changes-- get the filename in $filename					
					$filetype=$response['item_collection']['entries'][$i]['type'];			//Check the type of the BOX files
                    $filepath="Processed/".time().'_'.$filename;                   	
						
					//BOX chagnges add a check whether to enter processed folder
                    if($filetype=="folder" and $filename=="Processed" )
                    {
                        echo "<br> Skipped ".$filename." folder<br>" ;
                        continue;
                    }
                   
					else if($filetype=="folder")
                    {
                       
                        $childfolderid=$response['item_collection']['entries'][$i]['id'];
						
						$childFolderMetadata=getFolderDetails($headr,$childfolderid);
						
						$childFolderresponse =json_decode($childFolderMetadata, true);
						
					
						$childFolderSize = $childFolderresponse['item_collection']['total_count'];
                     
						$childFolderDate = $childFolderresponse['modified_at'];
                        if ($childFolderSize > 0)
                        {
                           
							$folderName=$filename; //Reassigning filename to foldername
							
                            $newFolderPath = "/Processed/".$folderName; //create a foldername for diacom files
                                                  
                            $isDicom = dicom_check($childFolderresponse, $childFolderSize);  
                            
							echo "<br>";
							echo "Diacom Check :".$isDicom;
							//Code for checking the diacom session and 
                            if($isDicom == 0)
                            {
                                $child_query = "SELECT * FROM dropbox_sessions WHERE session_name=".$folderName." and folderid=".$childfolderid;
                                $res = mysql_query($child_query);
                                if(mysql_num_rows($res) == 0)
                                {
                                    echo $folderName.'<br/>';
                                    echo $userid.'<br/>';
                                    echo $childFolderDate.'<br/>';
                                    echo $childFolderSize.'<br/>';
                                    $child_query = "INSERT INTO dropbox_sessions (session_name, id, date, size, uploaded, verified, folderid) VALUES ('".$folderName."',".$IdDoc.",'".$childFolderDate."',".$childFolderSize.",0,0,".$childfolderid.")";
                                    $res = mysql_query($child_query);
                                    if (!$res)
                                    {
                                        echo "Error creating Dropbox Session<br/>";
                                    }
                                    else
                                    {
                                        echo "New Dropbox Session Created!<br/>";
                                    }
                                }
                                for ($k = 0; $k < $childFolderSize; $k++)
                                {
                                    echo "Total Child Files present: ";
                                    echo $childFolderSize;
                                    echo "<br>\n";
									
                                    //$childFileName = basename($childFolderMetadata['contents'][$k]['path']);
									$childFileid = $childFolderresponse['item_collection']['entries'][$k]['id'];
                                    $childFileName = $childFolderresponse['item_collection']['entries'][$k]['name'];
                                    $childFilePath = "Processed/".time().'_'.$childFileName;
									$childFileDetails=json_decode(getFileDetails($headr,$childFileid),true);
									
									                                   
									$childFileSize = $childFileDetails['size'];
									$childFileDate = $childFileDetails['modified_at'];
									$childExtension = $childFileDetails['extension'];
                                 
                                    getFileContents($headr,$childFileid,$childFilePath);
									
									$checksum=md5_file($childFilePath);
                                    $confcode = 'eML'.md5(date('Ymdgisu'));
                                    $id_pin = parseAndEncrypt($childFileName, $childExtension, $k, $childFileSize, $childFileDate, $checksum, $childFilePath, false, '', $confcode);
                                    echo "ID PIN: ".$id_pin;
                                    echo "Encrypted file successfully<br/>\n";
                                									
                                    echo $folderName.'<br/>';
                                    //echo $userid.'<br/>';
                                    echo $userid.'<br/>';
                                    echo $childFilePath.'<br/>';
                                    echo $childFileSize.'<br/>';
                                    $child_file_query = "INSERT INTO dropbox_sessions_files (session_name, id, id_pin, size, uploaded, verified) VALUES ('".$folderName."',".$IdDoc.",".$id_pin.",'".$childFileSize."',1,0)";
                                    $res = mysql_query($child_file_query);
                                    if (!$res)
                                    {
                                        echo "Error creating Dropbox Session File<br/>";
                                    }
                                    else
                                    {
                                        echo "New Dropbox Session File Created!<br/>";
                                    }
                                   
                                    $child_file_query = "UPDATE dropbox_sessions SET uploaded=uploaded+1 WHERE session_name='".$folderName."' AND id=".$IdDoc."";
                                    $res = mysql_query($child_file_query);
                                    if (!$res)
                                    {
                                        echo "Dropbox Session Update Failed<br/>";
                                    }
                                    else
                                    {
                                        echo "Dropbox Session Updated Successfully!<br/>";
                                    }
                                }
                            }
                            else
                            {
                                // this session is a dicom session
                                //$link = mysql_connect("$dbhost", "$dbuser", "$dbpass")or die("cannot connect");

                                mysql_select_db("$dbname")or die("cannot select DB");
                                
                                echo "Found DICOM Folder";
                                                                
                                $new_dir = "Processed/".time().'_'.$filename;
                                $confcode = 'eML'.md5(date('Ymdgisu'));
                                
                                $pre_checksum = null;//md5_file($new_dir);
                              
                                $IdPin = 0;
                                $indra_dir = "";
                                // check if this session was already being processed
                                $query = "SELECT * FROM dicom_sessions WHERE name='".$filename."' AND user='".$fromaddress."'";
                                $q=mysql_query($query, $link);
                                if($row = mysql_fetch_assoc($q))
                                {
                                    $confcode = $row['encrypted_folder'];
                                    $IdPin = $row['id'];
                                    $indra_dir = $row['indra_folder'];
                                    $new_dir = $row['processed_folder'];
                                } else {
								
                                    $med_q = "SELECT id FROM doctors WHERE IdMEDEmail='".$fromaddress."'";
                                    $med_res = mysql_query($med_q);
                                    $med_row = mysql_fetch_assoc($med_res);
                                    $med_id = $med_row['id'];
                                    $Isql="INSERT INTO lifepin SET NeedACTION = 1, IdEmail='$i', FechaInput=NOW(), Fecha=NOW() , FechaEmail = '$childFolderDate', IdUsFIXED=0, IdUsFIXEDNAME='No Patient Assigned', IdMedEmail = '$fromaddress', CANAL=7, ValidationStatus=99, EvRuPunt= 2, Evento=99, Tipo=30,checksum='$pre_checksum',isDicom=1,IdMed = ".$med_id.", IdCreator = ".$med_id.", CreatorType=1";
    
                                    $q=mysql_query($Isql, $link);
                                    $dicom_dir_name = "";
                                    
                                    $IdPin = mysql_insert_id();
                                    $indra_dir = date('YmdHis')."_".$IdPin;
                                    mkdir($new_dir, 0777);
                                    
                                    $Isql = "INSERT INTO dicom_sessions SET name='".$filename."', id=".$IdPin.", indra_folder='".$indra_dir."', processed_folder='".$new_dir."', encrypted_folder='".$confcode."', user='".$fromaddress."', ready_for_transfer=0, transfered=0";
                                    $q=mysql_query($Isql, $link);
                                }
                                
                              
                                $dicom_dir_name = dicom_recursive($childFolderresponse, $childFolderSize, $childFolderDate, null, $filename, $confcode, $new_dir, $newFolderPath, $indra_connection, $indra_login_result);
                                                                                           
                                $dicom_file_info = pathinfo($dicom_dir_name);
                                $dicom_dir_name = $dicom_file_info['dirname'];
                                
                                $path_parts = pathinfo($dicom_dir_name);
                                $dirs = explode("\\", $dicom_dir_name);
                                if(count($dirs) > 0)
                                {
                                    $dicom_dir_name = $dirs[0];
                                }
                                
                                $Isql="UPDATE lifepin SET IdEmail='$i+1', RawImage='$dicom_dir_name' , FechaInput=NOW(), ValidationStatus=8 , orig_filename ='$filename' WHERE IdPin='$IdPin'";
                                $q = mysql_query($Isql);
                            }
							
                            //$dbxClient->delete($folderMetadata['contents'][$i]['path']);
                        }
						 //copyFolderDetails($headr,$folderid,$copyfolderid);
                        continue;
                    }
                    
					$fileid=$response['item_collection']['entries'][$i]['id'];
                    $filedetails=json_decode(getFileDetails($headr,$fileid),true);
					
                    //$Tamano=$folderMetadata['contents'][$i]['size'];
                    $Tamano=$filedetails['size'];
					//$date=$folderMetadata['contents'][$i]['modified'];
					$date=$filedetails['modified_at'];
                    print(ltrim($filename, '/'));
                    
                 
				    $result=getFileContents($headr,$fileid,$filepath);
					
					echo "getting into moveFile function..";
					echo $headr." ".$fileid." ".$copyfolderid;
					echo "<br>\n"; 
					moveFile($headr,$fileid,$copyfolderid);
					echo "<br>\n"; 
                    echo "Checksum: "; 
                    $checksum=md5_file($filepath);
                    echo $checksum; 
                    echo "<br>\n"; 
                 
                    $confcode = 'eML'.md5(date('Ymdgisu'));
					$extension= $filedetails['extension'];
                    //parseAndEncrypt($filename, $folderMetadata, $i, $Tamano, $date, $checksum, $filepath, false, '', $confcode);
					parseAndEncrypt($filename,$extension, $i, $Tamano, $date, $checksum, $filepath, false, '', $confcode);
                    fclose($f);
                }

            }
                         
        }// This ends the For loop for each row from dropboxdetail sql table
                
    }
    else
    {
        echo "The DropBox Cloud Channel has not been activated yet for any user!!";
        echo "<br>\n";
    }
		
		
}

mysql_close($link);

// this function recursively checks if there are any dicom files in a folder

function dicom_check($childFolderMetadata, $childFolderSize)
{
    //global $dbxClient;
	global $headr;
    $res = 0;
    for ($k = 0; $k < $childFolderSize; $k++)
	{
		
		$childtype=$childFolderMetadata['item_collection']['entries'][$k]['type'];
		//if($childFolderMetadata['contents'][$k]['is_dir'] == 1)
		if($childtype == 'folder')
        {
			
            //$newFolderMetadata = $dbxClient->getMetadataWithChildren($childFolderMetadata['contents'][$k]['path']);
            //$newFolderSize = count($newFolderMetadata['contents']);
			$childfolderid=$childFolderMetadata['item_collection']['entries'][$k]['id'];
			//$folderid=$response['item_collection']['entries'][$i]['id'];
			$newFolderMetadata=getFolderDetails($headr,$childfolderid);
			$childFolderresponse =json_decode($newFolderMetadata, true);
			$newFolderSize = $childFolderresponse['item_collection']['total_count'];
            $new_res = dicom_check($childFolderresponse, $newFolderSize);
			if($new_res == 1){
                $res = 1;
			 break;
			}
        }
        else
        {
            $childid=$childFolderMetadata['item_collection']['entries'][$k]['id'];
			$childName=$childFolderMetadata['item_collection']['entries'][$k]['name'];
			//$childFileInfo = pathinfo($childFolderMetadata['contents'][$k]['path']);
            //$childFileType = $childFileInfo['extension'];
			//$childFileDetails = json_decode(getFileDetails($headr,$childid),true);
			$pos=strpos($childName, '.');
            //echo "<br> Position of .pdf: ";
           // echo $pos;
            if($pos)
                $type= substr($childName,$pos+1,strlen($childName));
			//echo $type;
			$childFileType = $type;
			/*$childFileType = $childFileDetails['extension'];*/
			
            if($childFileType == 'DICOM' || $childFileType == 'dicom' || $childFileType == 'dcm' || $childFileType == 'DCM')
            {
                $res = 1;
				break;
            }
        }
    }
    return $res;
}

// this function recursively uploads files from dropbox for a dicom session folder

function dicom_recursive($childFolderMetadata, $childFolderSize, $childFolderDate, $checksum, $filename, $confcode, $new_dir, $newFolderPath, $ftp_conn, $ftp_login)
{
    //global $dbxClient;
	global $headr;
    $dicom_dir_name = "";
    for ($k = 0; $k < $childFolderSize; $k++)
    {
		$childtype=$childFolderMetadata['item_collection']['entries'][$k]['type'];
        //if($childFolderMetadata['contents'][$k]['is_dir'] == 1)
		if($childtype == 'folder')
        {
            //$dbxClient->createFolder('/Processed/'.$childFolderMetadata['contents'][$k]['path']);
            /*$newFolderMetadata = $dbxClient->getMetadataWithChildren($childFolderMetadata['contents'][$k]['path']);
            $newFolderSize = count($newFolderMetadata['contents']);
            $newFolderDate = $childFolderMetadata['contents'][$k]['modified'];
            $newFolderName = basename($childFolderMetadata['contents'][$k]['path']);*/
			
			$childfolderid=$childFolderMetadata['item_collection']['entries'][$k]['id'];
			//$folderid=$response['item_collection']['entries'][$i]['id'];
			$newFolderMetadata=getFolderDetails($headr,$childfolderid);
			$childFolderresponse =json_decode($newFolderMetadata, true);
			$newFolderSize = $childFolderresponse['item_collection']['total_count'];
			$newFolderDate = $childFolderresponse['modified_at'];
			$newFolderName = $childFolderresponse['name'];
			
			
            echo "HANDLING FOLDER: ".$childFolderMetadata['contents'][$k]['path'];
            
            $new_newFolderPath = $newFolderPath.'/'.$newFolderName;
            
            $new_new_dir = $new_dir.'/'.$newFolderName;
            mkdir($new_new_dir, 0777);
            $new_confcode = $confcode.'\\'.$newFolderName;
            
            if (ftp_mkdir($ftp_conn, $newFolderName)) 
            {
                echo "successfully created $newFolderName\n";
            } 
            else 
            {
                die("There was a problem while creating $newFolderName\n");
            }
            ftp_chdir($ftp_conn, $newFolderName);
			//$dicom_dir_name = dicom_recursive($newFolderMetadata, $newFolderSize, $newFolderDate, $checksum, $filename, $new_confcode, $new_new_dir, $new_newFolderPath, $ftp_conn, $ftp_login);
            $dicom_dir_name = dicom_recursive($childFolderresponse, $newFolderSize, $newFolderDate, $checksum, $filename, $new_confcode, $new_new_dir, $new_newFolderPath, $ftp_conn, $ftp_login);
            
            
        }
        else
        {
            //$childFileName = basename($childFolderMetadata['contents'][$k]['path']);
			$childFileid=$childFolderMetadata['item_collection']['entries'][$k]['id'];
			$childFileName=$childFolderMetadata['item_collection']['entries'][$k]['name'];
            //echo "HANDLING FILE: ".$childFileName;
			echo "HANDLING FILE: ".$childFileid;
            $childFilePath = $new_dir.'/'.$childFileName;
			getFileContents($headr,$childFileid,$childFilePath);
            //$childTempFile = fopen($childFilePath, "w+b" );
            //$fileMetadata = $dbxClient->getFile($childFolderMetadata['contents'][$k]['path'], $childTempFile);
            $checksum = null;//md5_file($childFilePath);
            //upload_dicom_file($childFilePath, $ftp_conn, $ftp_login);
            upload_dicom_file($childFilePath, $ftp_conn, $ftp_login);
			$childFileDetails = json_decode(getFileDetails($headr,$childFileid),true);
			$childFileType = $childFileDetails['extension'];
            //$dicom_dir_name = parseAndEncrypt($childFileName, $childFolderMetadata, $k, $childFolderSize, $childFolderDate, $checksum, $childFilePath, true, $filename, $confcode);
            $dicom_dir_name = parseAndEncrypt($childFileName, $childFileType, $k, $childFolderSize, $childFolderDate, $checksum, $childFilePath, true, $filename, $confcode);
            
            //fclose($childTempFile);
			/*
            try
            {
                $dbxClient->move($childFolderMetadata['contents'][$k]['path'],$newFolderPath."/".$childFileName);
            }catch(Exception $e)
            {
                echo "<br> Exception Caught:  ".$e."<br>";
                $dbxClient->delete($childFolderMetadata['contents'][$k]['path']);
            }*/
        }
    }

    ftp_cdup($ftp_conn);
    return $dicom_dir_name;
}

// this function uploads a file to the indra server

function upload_dicom_file($file, $connection, $login)//($local_file_path,$filename)
{
    $filename = basename($file);

	if (ftp_put($connection, $filename, $file, FTP_BINARY)) 
	{
		echo "successfully uploaded $filename\n";
	} 
	else 
	{
		echo "There was a problem while uploading $filename\n";
	}
}

function upload_dicom_xml($dir, $unique_id, $patient_id, $connection, $login)
{
    
    $xml = '<?xml version=\"1.0\" encoding=\"UTF-8\"?><studyData><patientID>'.$patient_id.'</patientID><accessionNumber>'.$unique_id.'</accessionNumber></studyData>';
    $file = $dir.'.xml';
    file_put_contents($file, $xml);
    
    if (ftp_put($connection, $file, $file, FTP_BINARY)) 
    {
        echo "successfully uploaded $file";
        unlink($file);
    } 
    else 
    {
        echo "There was a problem while uploading $file";
        unlink($file);
    }
    
}

function upload_to_indra($IdPin, $localFolder, $filesUploaded, $left_off, $ftp_conn)
{
    $uploaded = $filesUploaded;
    $files = scandir($localFolder);
    foreach($files as $file)
    {
        if($file != '.' && $file != '..') // exclude . and ..
        {
            if(is_dir($localFolder.'/'.$file))
            {
                // this is a directory
                $contents_on_server = ftp_nlist($ftp_conn, ".");
                if (!in_array($file, $contents_on_server)) 
                {
                    if (ftp_mkdir($ftp_conn, $file)) 
                    {
                        echo "successfully created $file\n";
                    } 
                    else 
                    {
                        echo "There was a problem while creating $file\n";
                    }
                }
                ftp_chdir($ftp_conn, $file);
                $uploaded = upload_to_indra($IdPin, $localFolder.'/'.$file, $uploaded, $left_off, $ftp_conn);
            }
            else
            {
                // this is a file
                $uploaded += 1;
                if($uploaded > $left_off)
                {
                    if (ftp_put($ftp_conn, $file, $localFolder.'/'.$file, FTP_BINARY)) 
                    {
                        echo "successfully uploaded $file to indra\n";
                        $Isql = "UPDATE dicom_sessions SET transfered=".$uploaded." WHERE id=".$IdPin;
                        $res = mysql_query($Isql);
                    } 
                    else 
                    {
                        echo "There was a problem while uploading $file to indra\n";
                    }
                }
            }
        }
    }
    ftp_cdup($ftp_conn);
    return $uploaded;
}
                                  

function parseAndEncrypt($filename, $extension, $i, $Tamano, $date, $checksum, $filepath, $isDicom, $foldername, $code)
{
    global $EmailDOC, $fromaddress, $fromname, $dbhost, $dbname, $dbuser, $dbpass, $enc_pass;
    $filestrings=explode("$", $filename);     //The components are separated by $
    echo "<br>\n"; 	 
    echo "filename :";
    print_r($filestrings);
    echo "<br>\n";
    echo "File Type ";
    //$laststring=$folderMetadata['contents'][$i]['mime_type'];			//get the extension for the BOX code level
    /*$extension = '';
    if(!$isDicom)
    {
       $extension= substr($laststring,1+strpos($laststring, '/'));
        echo "EXTENSION : ".$extension; 
    }*/
    
    echo "<br>\n"; 	 
    echo "count :";
    echo count($filestrings);
    $format=false;
    //$format=true;
    $firstname="";
    $lastname="";
    $PatientDetails=array();
    $report_date="";
    $type=-1;
    $laststring="file";
    $typoid=-1;
    $IdUsu=0;
    $IdUsFIXED=0;
    $IdUsFIXEDNAME= "No patient assigned";   
    $count_string=count($filestrings);
    $date_pos=1;
    if($count_string>1 && $count_string<6){
        foreach( $filestrings as $line)
            {
                //$line = str_replace( array( '@', '|', '(', ')'), '', trim( $line));
                 $line = strip_tags( $line);
            // if( (($time = strtotime( $line)) === false) or (($time=validateDate($line)=== false)))
              if (($time=validateDate($line))=== false)
              {
                 echo "<br>";
                 echo "Could not parse line - '" . $line . "'\n"; // Need additional processing / regex here
                 $date_pos++;
                 continue;
              }
                 
                 //$report_date=date( 'F jS, Y h:i:s A', $time);
                 //$report_date=date( 'Ymd', $time);
                 $report_date= $time;
                 $format=true;
                 echo "<br>";
                 echo "Converted '" . $line . "' to '" . $report_date . "'\n";
                 break;
             }
        //adam$robinson$2 jan 2004$.pdf 
        if($date_pos==3){
            $lastname=$filestrings[$date_pos-2];
            $firstname=$filestrings[$date_pos-3];
            $format=true;
        }else if($date_pos==2) {
            $pattern = '/[. ]/';
            echo "<br> Parse_details ";
            $patientname=preg_split( $pattern, $filestrings[$date_pos-2] );
            echo print_r($patientname , 1 );
            $firstname=$patientname[0];
            $lastname=$patientname[1];
            $format=true;
        }
        
        if($count_string >= ($date_pos)){
            //In this part most probably the report type exists as well as there might be file extension
            if($firstname=="")
                $firstname=$filestrings[0];
            if($lastname=="")
                $lastname=$filestrings[1];
            $pos=strpos($filestrings[$date_pos], '.');
            echo "<br> Position of .pdf: ";
            echo $pos;
            if($pos)
                $type= substr($filestrings[$date_pos],0,$pos);
            echo "<br> report type: ";
            echo $type;
            $format=true;
        }else if($count_string==5){
            //If the length of the string is 5. That means most probably file string follows a sequence
            //firstname$lastname$date$type$extension
        }
        //die("Exiting!!");		
    }else if($count_string==6){
            $format=true;
            $firstname=$filestrings[1];
            $lastname=$filestrings[2];
            $report_date=date($filestrings[3]);
            echo "<br> report_date: ".$report_date."<br>";
            $type=$filestrings[4];
            $laststring=$filestrings[5];								
    }else{
        
    }
    
    if($format){
        echo "<br>\n"; 	 
        echo "Fetching PatientID and Report Type";
        echo "<br>\n"; 	 
        $PatientDetails=getPatientDetails($firstname,$lastname);
        $IdUsu=$PatientDetails['Identif'];
        if($IdUsu==null)
            $IdUsu=0;
        $IdUsFIXED=$PatientDetails['IdUsFIXED'];
        $IdUsFIXEDNAME=$PatientDetails['IdUsFIXEDNAME'];
        $typoid=getReportType($type);
    }
    
    //print_r($fileMetadata);

    //echo " ". $folderMetadata;
    echo "folderMetadata end";
    //$host="54.225.226.163"; // Host name


    //mysql_select_db("$db_name")or die("cannot select DB");

            // Grabación de un registro con fecha en la dB para comprobar la VITALIDAD
   
    
    /*$IdUsFIXED = substr($filename,0,10);   //Add the file name as an IDname
    settype($IdUsFIXED, "integer");*/
    



    echo 'INSERCION IdUsFIXED = '.$IdUsFIXED;
    echo "<br>\n"; 
              
    echo 'INSERCION IdUsFIXED = '.intval($IdUsFIXED);
    echo "<br>\n"; 
    
    /* require("environment_detail.php");
     $dbhost = $env_var_db['dbhost'];
     $dbname = $env_var_db['dbname'];
     $dbuser = $env_var_db['dbuser'];
     $dbpass = $env_var_db['dbpass'];*/
        
    
    // Connect to server and select databse.
    //$link = mysql_connect("$dbhost", "$dbuser", "$dbpass")or die("cannot connect");

    mysql_select_db("$dbname")or die("cannot select DB");
    $q = 1;
    if(!$isDicom)
    {
        if($format){
         $Isql="INSERT INTO lifepin SET NeedACTION = 1, IdEmail='$i', IdUsu=$IdUsu, FechaInput=NOW(), Fecha='$report_date' , FechaEmail = '$date', IdUsFIXED='$IdUsFIXED', IdUsFIXEDNAME='$IdUsFIXEDNAME', IdMedEmail = '$fromaddress', CANAL=7, ValidationStatus=99, EvRuPunt= 2, Evento=99, Tipo=$typoid, fs=1, checksum='$checksum',isDicom=0";
         echo "<br>\n"; 	 
         echo $Isql;
        }else {
         $Isql="INSERT INTO lifepin SET NeedACTION = 1, IdEmail='$i', FechaInput=NOW(), Fecha=NOW() , FechaEmail = '$date', IdUsFIXED='$IdUsFIXED', IdUsFIXEDNAME='$IdUsFIXEDNAME', IdMedEmail = '$fromaddress', CANAL=7, ValidationStatus=99, EvRuPunt= 2, Evento=99, Tipo=30,checksum='$checksum',isDicom=0";
         echo "<br>\n"; 
         echo $Isql;	 
        }
    
    
        echo "<br>\n"; 		
        echo "<br>\n"; 	    
        //$q = mysql_query($Isql) or die(mysql_error());
        $q=mysql_query($Isql, $link);
    }
    
    
    //$q = mysqli::query($Isql);		 
    if($q){
            //echo $Isql;
            echo "Successful: Database Updated";
            echo "<br>\n"; 	
    }else {
                                                                                                                                
        if(($errorno=mysql_errno($link))==1062){
            
                                                                                                                        
        $message = 'Dear ';
        $message.= $EmailDOC.' : ';
        $message.= '<span style="color: black;">';
        $message.= ' We have received a duplicate clinical information package ';
        $message.= '<span style="color: green;">';
        $message.= $filename;
        $message.= '<span style="color: black;">';
        $message.= ' through MediBANK CLOUD CHANNEL from you at ';
        $message.= '<span style="color: green;">';
        $message.= $date;
        $message.= '<span style="color: black;">';
        $message.= '. Please contact support for futher information.';
        //$confirm_code=md5(uniqid(rand()));
        //$message.='http://www.health2.me/confirmationDoc.php?passkey=$confirm_code';

        EnviaMail2($EmailDOC, $IdUsFIXED, 'MediBANK Cloud Channel validation system','Duplicate clinical record information alert.', $message, 0, 9999, 'Grace@health2.me', 'Inmers', 9);  		 
        }else {
            
        //LogBLOCK (0, 'Error in record found.', $EmailDOC, '', $IdUsFIXED, '', '', 1); 																											
                                                                                                                    
        }																									
            
            //echo "Error Updating Database :" . mysql_error();
            echo mysql_errno($link) . ": " . mysql_error($link) . "\n";
            die(mysql_error($link));
            echo "<br>\n"; 	
    }	
    $IdPin = 0;
    $IdPinBackup = 0;
    if(!$isDicom)
    {
        $IdPin = mysql_insert_id();
        
    }

    //$extension = substr($laststring,1+strpos($laststring, '.'),3); 	//This is not the most efficient way of finding the extension as a dot might be present in the filename
    /*$laststring=$folderMetadata['contents'][$i]['mime_type'];
    $extension= substr($laststring,1+strpos($laststring, '/'));*/
    //echo "EXTENSION : ".$extension;
    echo "<br>\n"; 
    echo "SIZE: ";
    echo $Tamano;
    echo "<br>\n";
    /*if($extension!="pdf"&&$extension!="png"&&$extension!="jpg") {
        $extension="pdf";
    }*/
    
    if(!$isDicom)
    {
        echo "******".$extension;
        if($extension=="jpeg")
        {
            $extension="jpg";
        }
        else if($extension=="quicktime")
        {
            $extension="MOV";
        }
    }
    
    
        
        
    if ($Tamano > 20000000){
        echo "SIZE EXCEEDS 20 Mb. LIMIT";
        echo "<br>\n"; 
    }else{
                
        // GRABACIÓN DEL ATTACHMENT EN EL DIRECTORIO LOCAL PARA LUEGO HACER EL FTP
         $locFile = "Packages_Encrypted\\";  //Changed from PAckages to PAckages_Encrypted by Ankit
        $confcode = $code;
        $confcode = $IdUsFIXED.$code;
        
        $new_image_name = "";
        if(!$isDicom)
        {
            $new_image_name = $confcode.'.'.$extension; // ASIGNACIÓN DE UN NOMBRE DE FICHERO ÚNICO
        }
        else
        {
            $new_image_name = $confcode;
        }
        if($isDicom)
        {
            $paths = explode('\\', $new_image_name);
            $paths_size = count($paths);
            $new_image_name = $paths[0];
            if(!file_exists($locFile.$new_image_name))
            {
                if(!mkdir($locFile.$new_image_name, 0777))
                {
                    echo "Error creating directory ".$locFile.$new_image_name;
                }
                $checksum_temp = md5_file($locFile.$new_image_name);
                if($checksum_temp)
                {
                    $checksum = $checksum_temp;
                }
            }
            for($v = 1; $v < $paths_size; $v++)
            {   
                $new_image_name = $new_image_name.'\\'.$paths[$v];
                if(!file_exists($locFile.$new_image_name))
                {
                    if(!mkdir($locFile.$new_image_name, 0777))
                    {
                        echo "Error creating directory ".$locFile.$new_image_name;
                    }
                }
            }
            
            $new_image_name = 'eML'.$confcode.'.'.$extension; // ASIGNACIÓN DE UN NOMBRE DE FICHERO ÚNICO
        }
        else
        {
            $new_image_name = 'eML'.$confcode;
        }
        if($isDicom)
        {
            if(!file_exists($locFile.$new_image_name))
            {
                mkdir($locFile.$new_image_name, 0777);
            }
            $checksum = md5_file($locFile.$new_image_name);
            $new_image_name = $new_image_name.'\\'.$filename;
        }

        echo " NEW FILE : ".$new_image_name;
        echo "<br>\n"; 								
        
        // GRABACIÓN FÍSICA DEL FICHERO CONTENIDO EN EL ATTACHMENT EN EL SERVIDOR (PACKAGES)

        
        $deleteError = 0;
        
        $f_new = fopen( $locFile.$new_image_name, "w+b" );
        
        if (!copy($filepath, $locFile.$new_image_name)){
                echo "Failed to Create File in the Package area!";
        }else {
                //unlink ($filename);
                $lines = array();




                //exec("DEL /F/Q \"$filepath\"", $lines, $deleteError); 		//Added for windows server as unlink command has some problem with the permissions.
                echo "File created in Packages_Encrypted folder successfully!!";
                //Encrypt.bat takes 3 parameters...source path...destination path...encryption password
                echo 'Encrypt.bat '.$locFile.$new_image_name.' '.$locFile.$new_image_name.' '.$enc_pass;
                
        }
        
         if ($deleteError) {
              echo 'file delete error';
         }
        fclose($f_new); 		
        
        if(!$isDicom) // do not create thumbnail if this is a dicom session
        {
            $locFileTH = "PackagesTH_Encrypted\\";    //Changed from PackagesTH to PackagesTH_Encrypted
            
            
            //Changes for handling jpg files
            if($extension!="jpg") {
            $new_image_nameTH = 'eML'.$confcode.'.png';	
            }else {
            $new_image_nameTH = 'eML'.$confcode.'.jpg';
            }
            
            if($extension=="MOV") //for video use this command
            {
                $path = $path_for_ffmpeg;  //path of ffmpeg
                $cadenaConvert = $path.'ffmpeg.exe -i '.$locFile.$new_image_name.' -f image2 -t 0.001 -ss 3 '. $locFileTH.$new_image_nameTH;
            }
            else   //use this command for other file types
            {
                $path="C:\\PROGRA~2\\ImageMagick-6.8.1-Q16\\";  //path of ImageMagick
                $cadenaConvert = 'convert "'.$locFile.$new_image_name.'[0]" -colorspace RGB -geometry 200 "'.$locFileTH.$new_image_nameTH.'" 2>&1';
            }																											
            echo "<br>\n"; 
            echo "Thumbnail: ";
            echo $cadenaConvert;
            echo "<br>\n"; 
            
            echo "EXEC IMAGEMAGIK -----------";
    
            $output = shell_exec($cadenaConvert);  
            echo "<pre>$output</pre>";
            echo "<br>\n"; 
            echo "DONE EXEC IMAGEMAGIK --------";
            echo "<br>\n"; 
            
            shell_exec("echo '".$enc_pass."' | openssl aes-256-cbc -pass stdin -salt -in PackagesTH_Encrypted/".$new_image_nameTH." -out temp/".$new_image_nameTH);
    shell_exec("rm PackagesTH_Encrypted/".$new_image_nameTH);
    shell_exec("cp temp/".$new_image_nameTH." PackagesTH_Encrypted/");
    shell_exec("rm temp/".$new_image_nameTH);
    
         				//Encrypt the thumbnail(added by ankit)
            echo "Thumbnail Encrypted Successfully";															//added by ankit
            //reación de un thumbnail desde el PDF  
        }
        shell_exec("echo '".$enc_pass."' | openssl aes-256-cbc -pass stdin -salt -in Packages_Encrypted/".$new_image_name." -out temp/".$new_image_name);
    shell_exec("rm Packages_Encrypted/".$new_image_name);
    shell_exec("cp temp/".$new_image_name." Packages_Encrypted/");
    shell_exec("rm temp/".$new_image_name);    
	//Encrypt the report (ankit)
        echo "<br>File Encrypted in Packages_Encrypted Folder";   			//added by ankit
      
        echo "Uploading to Parsing Server : ";
        //$status =  upload_to_parsing_server($new_image_name);
        //echo $status . "<br><br>";
        $q = 1;
        if(!$isDicom)
        {
            $Isql="UPDATE lifepin SET IdEmail='$i+1', RawImage='$new_image_name' , FechaInput=NOW(), ValidationStatus=8 , orig_filename ='$filename' WHERE IdPin='$IdPin'";
            $q = mysql_query($Isql);
        }
        $IdPinBackup = $IdPin;
        if(!$isDicom)
        {
            $IdPin = mysql_insert_id();
        }
        // if successfully insert data into database, displays message "Successful". 
        if($q){
            echo "Successful: Database Updated";
            echo "<br>\n"; 	
            echo "REGISTROS CAMBIADOS: ".mysql_affected_rows();
            echo "<br>\n"; 	
            LogMov($IdUsFIXED, $fromname, $fromaddress, '4', 'dB INSERTION', 'PIN INSERTED, Database UPDATED' , $filename, $i);
        }
        else{
            echo "Error Updating Database ********";
            echo "<br>\n"; 	 
            LogMov($IdUsFIXED, $fromname, $fromaddress, '4', 'dB INSERTION', 'Error Updating Database ********' , $filename, $i);
        }
        LogMov($IdUsFIXED, $fromname, $fromaddress, '2', 'File Local Save', $new_image_name, $filename, $i);
    }
    if($isDicom)
        return $new_image_name;
    else
        return $IdPinBackup;
}
	
	function getPatientDetails($firstname,$lastname)	{
				
		
			 require("environment_detail.php");
			 $dbhost = $env_var_db['dbhost'];
			 $dbname = $env_var_db['dbname'];
			 $dbuser = $env_var_db['dbuser'];
			 $dbpass = $env_var_db['dbpass'];

				// Connect to server and select databse.
				//$link1 = mysql_connect("$dbhost", "$dbuser", "$dbpass")or die("cannot connect");
				mysql_select_db("$dbname")or die("cannot select DB");
				
				echo"<br>";
				echo "Fetching Patient ID details......";
				
				$query="SELECT * FROM usuarios where Name='$firstname' and Surname='$lastname'";
				$result1 = mysql_query($query);
				$count1=mysql_num_rows($result1);
				echo "<br>";
				echo "query: ".$query;
				echo "<br>";
				echo "count: ".$count1;
				$i=-1;
				if ($count1>1){
					
				}else if ($count1==0){					
					$i=0;					
				}else if ($count1==1){
					$row=mysql_fetch_assoc($result1);
					//$i=$row['Identif'];
					return $row;
				}
		       
			    mysql_close($link1);
			    return $i;
			   
	}	  	
	
	function getReportType($type)	{
				require("environment_detail.php");			
			 $dbhost = $env_var_db['dbhost'];
			 $dbname = $env_var_db['dbname'];
			 $dbuser = $env_var_db['dbuser'];
			 $dbpass = $env_var_db['dbpass'];

	
					
				// Connect to server and select databse.
				//$link1 = mysql_connect("$dbhost", "$dbuser", "$dbpass")or die("cannot connect");
				mysql_select_db("$dbname")or die("cannot select DB");
				
				echo"<br>";
				echo "Fetching Report Type......";
						
				//$query="SELECT * FROM tipopin where NombreEng='$type'";
				$query="SELECT * FROM tipopin where abreviation='$type'";
				$result1 = mysql_query($query);
				$count1=mysql_num_rows($result1);
				echo "<br>";
				echo "query: ".$query;
				echo "<br>";
				echo "count: ".$count1;
				$i=-1;
				if ($count1>1){
					
				}else if ($count1==0){					
					$i=0;					
				}else if ($count1==1){
					$row=mysql_fetch_assoc($result1);
					$i=$row['Id'];
				}
		       
			    mysql_close($link1);
			    return $i;
			   
	}	
	
	function checkDuplicateRecord($checksum)	{
	
		require("environment_detail.php");		
		 $dbhost = $env_var_db['dbhost'];
		 $dbname = $env_var_db['dbname'];
		 $dbuser = $env_var_db['dbuser'];
		 $dbpass = $env_var_db['dbpass'];
		 
					
				// Connect to server and select databse.
				//$link1 = mysql_connect("$dbhost", "$dbuser", "$dbpass")or die("cannot connect");
				mysql_select_db("$dbname")or die("cannot select DB");
				
				echo"<br>";
				echo "Fetching Report Type......";
						
				$query="SELECT * FROM tipopin where NombreEng='$type'";
				$result1 = mysql_query($query);
				$count1=mysql_num_rows($result1);
				echo "<br>";
				echo "query: ".$query;
				echo "<br>";
				echo "count: ".$count1;
				$i=-1;
				if ($count1>1){
					
				}else if ($count1==0){					
					$i=0;					
				}else if ($count1==1){
					$row=mysql_fetch_assoc($result1);
					$i=$row['Id'];
				}
		       
			    mysql_close($link1);
			    return $i;
			   
	}

   
   
    function copyFolderDetails($headr,$folderid,$processfolderid){
			$ch = curl_init();			
			
			/*$headr = array();
			$headr[] = 'Authorization: Bearer '.$token[$i]['AccessToken'];*/
			
			$data = json_encode(array('parent' => array('id' => $processfolderid)));
			echo $data;
			
			echo "copying BOX FOLDER details....";

			$URL='https://www.box.com/api/2.0/folders/'.$folderid.'/copy';
			
			curl_setopt($ch, CURLOPT_URL, $URL);

			curl_setopt($ch, CURLOPT_HTTPHEADER,$headr);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			
			if( ! $result = curl_exec($ch)){ 
					trigger_error(curl_error($ch)); 
			} 
			curl_close($ch);


			$ch = curl_init();
	
			curl_setopt($ch, CURLOPT_URL, 'https://www.box.com/api/2.0/folders/'.$folderid.'?recursive=true');

			//curl_setopt($ch, CURLOPT_URL, $URL);

			curl_setopt($ch, CURLOPT_HTTPHEADER,$headr);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
			//curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch,CURLOPT_CUSTOMREQUEST, 'DELETE');
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			
			if( ! $result = curl_exec($ch)){ 
					trigger_error(curl_error($ch)); 
			} 
			curl_close($ch); 
				
	}
	
	function getFolderDetails($headr,$folderid){
		//$fileid=$response['item_collection']['entries'][0]['id'];
	
		//download the file 
		
		echo "Into folder details method....";
		
		$ch = curl_init();
		
		$URL='https://www.box.com/api/2.0/folders/'.$folderid;
		
		curl_setopt($ch, CURLOPT_URL, $URL);

		curl_setopt($ch, CURLOPT_HTTPHEADER,$headr);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		//curl_setopt($ch, CURLOPT_POST, 1);
		
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		
		if( ! $result = curl_exec($ch)){ 
				trigger_error(curl_error($ch)); 
		} 
		 curl_close($ch); 
		 //echo "<br>";
		 //echo $result;
		 return $result; 
		 
		 //echo '<br><br>';
	}	
	
	function getFileDetails($headr,$fileid){
		//$fileid=$response['item_collection']['entries'][0]['id'];
	
		//download the file 
		
		echo "Into file details method....";
		
		$ch = curl_init();
		
		$URL='https://www.box.com/api/2.0/files/'.$fileid.'?fields=size,modified_at,extension';
		
		curl_setopt($ch, CURLOPT_URL, $URL);

		curl_setopt($ch, CURLOPT_HTTPHEADER,$headr);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		//curl_setopt($ch, CURLOPT_POST, 1);
		
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		
		if( ! $result = curl_exec($ch)){ 
				trigger_error(curl_error($ch)); 
		} 
		 curl_close($ch); 
		 echo "<br>";
		 //echo $result;
		 return $result; 
		 
		 //echo '<br><br>';
	}	
	
	function getFileContents($headr,$fileid,$filepath){
		//$fileid=$response['item_collection']['entries'][0]['id'];
		
		echo "downloading the files.....";
		//download the file 
		$ch = curl_init();
		
		$URL='https://www.box.com/api/2.0/files/'.$fileid.'/content';
		
		curl_setopt($ch, CURLOPT_URL, $URL);

		curl_setopt($ch, CURLOPT_HTTPHEADER,$headr);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		//curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		
		if( ! $result = curl_exec($ch)){ 
				trigger_error(curl_error($ch)); 
		} 
		 curl_close($ch); 
		 //return $result; 
		 //echo '<br><br>';
		 $fp = fopen($filepath,'wb');
		 fwrite($fp, $result);
		 fclose($fp);
		echo "<br>";
		echo "finished downloading the files.....";
	}
	
	function moveFile($headr,$fileid,$folderid){
		
		//Copy files to the processed folder
		echo "Moving the files to processed folder...";
		
		$data = json_encode(array('parent' => array('id' => $folderid)));
		echo $data;
		
		
		$ch = curl_init();
		
		$URL='https://www.box.com/api/2.0/files/'.$fileid.'/copy';
		
		curl_setopt($ch, CURLOPT_URL, $URL);

		curl_setopt($ch, CURLOPT_HTTPHEADER,$headr);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		
		if( ! $result = curl_exec($ch)){ 
				trigger_error(curl_error($ch)); 
		} 
		curl_close($ch); 
		//return $result; 
		
		//delete the original file
		
	
		$ch = curl_init();
		
		$URL='https://www.box.com/api/2.0/files/'.$fileid;
		
		curl_setopt($ch, CURLOPT_URL, $URL);

		curl_setopt($ch, CURLOPT_HTTPHEADER,$headr);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		//curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch,CURLOPT_CUSTOMREQUEST, 'DELETE');
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		
		if( ! $result = curl_exec($ch)){ 
				trigger_error(curl_error($ch)); 
		} 
		curl_close($ch); 
		return $result; 
	
	
	}
	
function EnviaMail2($objetivo, $IdUsFIXED, $sobre, $message0, $message, $codExito, $IdU, $fro, $froa , $queCodigo)
{
require_once 'lib/swift_required.php';

/*
echo "<br>\n"; 	
echo "Objetivo = ";
echo $objetivo;
echo "<br>\n"; 	
echo "sobre = ";
echo $sobre;
echo "<br>\n"; 	
echo "message = ";
echo $message;
echo "<br>\n"; 	
echo "codExito = ";
echo $codExito;
echo "<br>\n"; 	
echo "IdU = ";
echo $IdU;
echo "<br>\n"; 	
echo "fro = ";
echo $fro;
echo "<br>\n"; 	
echo "froa = ";
echo $froa;
echo "<br>\n"; 	
echo "quePIN = ";
echo $quePIN;
echo "<br>\n"; 	
echo "queCodigo = ";
echo $queCodigo;
echo "<br>\n"; 	
*/

if ($codExito==0)
{
//LogMov($IdUsu, $fromname, $fromaddress, '4', 'dB INSERTION', 'PIN INSERTED, Database UPDATED' , $subject, $email_number);
	$Sobre = $sobre;
	$Body = '<h1>'.$message0.'</h1>';
	if($IdUsFIXED!=0)
		$Body .= '<h2>Patient.: '.$IdUsFIXED.'</h2>';
	else 
		$Body .= '<h2>Patient.: Information not available </h2>';
	
	$confirm_code = $queCodigo;
	
	$message2= '***** HIPAA AND STANDARD COMPLIANCE PROCEDURES DISCLAIMER *****  :   ';
	$message2.= ' Text of the Legal, Disclaimer and technical explanations here ... Lore Ipsum';
	
	
    $Body.='<h3>';
    $Body.= $message;
    $Body.='</h3>';
   
    $Body.='<h3>';
    $Body.= $message2;
    $Body.='</h3>';

}
else
{
}

$domain = 'http://'.strval($dbhost);
    
$transporter = Swift_SmtpTransport::newInstance('smtp.gmail.com', 465, 'ssl')
  ->setUsername('dev.health2me@gmail.com')
  ->setPassword('health2me');

$mailer = Swift_Mailer::newInstance($transporter);


// Create the message
$message = Swift_Message::newInstance()

  // Give the message a subject
  ->setSubject($Sobre)

  // Set the From address with an associative array
  ->setFrom(array('HUB@health2.me' => 'eMapLife Clinical HUB'))

  // Set the To addresses with an associative array
  ->setTo(array($objetivo))

  // Give it a body
 // ->setBody('Here is the message itself')
  ->setBody('Here is the message itself')

  // And optionally an alternative bodys
//  ->addPart('<q>Here is the message itself</q>', 'text/html')
  ->addPart($Body, 'text/html')

  // Optionally add any attachments
 // ->attach(Swift_Attachment::fromPath('my-document.pdf'))
 ;
 
$result = $mailer->send($message);


}  	

function validateDate($str){
	
	$file = fopen("Filename_Parsing\\db.txt","w");
	echo fwrite($file,$str);
	fclose($file);
	$output = shell_exec("Filename_Parsing\Lexer_Filename Filename_Parsing\db.txt");
	//$output = shell_exec("Lexer_Filename Filename_Parsing\db.txt");
	
	if($output){
		echo "<br> Lexer Date found: ". $output;
		echo "<br>";
		
		//$pattern = '/[,]/';
		//return preg_split($pattern, $output);
		return trim(str_replace(","," ", $output));
	}else {
		
		return false;
	}
	
	
}

?>
