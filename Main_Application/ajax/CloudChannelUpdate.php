<?php session_start();

## Include the Dropbox SDK libraries
set_time_limit(120);
define('INCLUDE_CHECK',1);
require "logger.php";
require_once "dropbox-sdk/Dropbox/autoload.php";
require "upload_to_parsing_server.php";

$path_for_ffmpeg = "ffmpeg\\bin\\";

use \Dropbox as dbx;

//This is for connecting to the database tables
echo "Initiating Database Connection....";
echo "<br>";
// $hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']);
//echo $_SERVER['HTTP_HOST'];
echo "<br>";
require "environment_detailForLogin.php";
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
    // check dicom session to see if there are any that are ready to transfer to Indra. if there are, transfer them
    // connect to indra server
    $indra_server = "54.225.67.15";
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
        
        
        $Isql = $con->prepare("SELECT * FROM dicom_sessions WHERE ready_for_transfer=1");
        $res = $Isql->execute();
        while($row = $res->fetch(PDO::FETCH_ASSOC))
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
            $q2 = $con->prepare("SELECT * FROM lifepin WHERE IdPin=?");
			$q2->bindValue(1, $idpin, PDO::PARAM_INT);
			
            $res2 = $q2->execute();
            if($row2 = $q2->fetch(PDO::FETCH_ASSOC))
            {
                $pat_id = $row2['IdUsu'];
                upload_dicom_xml($row['indra_folder'], $idpin, $pat_id, $indra_connection);
                $q3 = $con->prepare("UPDATE dicom_sessions SET ready_for_transfer=2 WHERE id=?");
				$q3->bindValue(1, $idpin, PDO::PARAM_INT);
                $res3 = $q3->execute();
            }
        }
        ftp_close($indra_connection);
    }
    
    $IsqlX=$con->prepare("UPDATE vitalidad SET Fecha=NOW(), Programa = 'CloudChannelUpdate.php' WHERE IdProg = 3");
    $qX = $IsqlX->execute();
    // Grabación de un registro con fecha en la dB para comprobar la VITALIDAD
    
    //Get Encryption Password (added by Ankit)
    $enc_result = $con->prepare("select pass from encryption_pass where id = (select max(id) from encryption_pass)");
	$enc_result->execute();
	
    $row_enc = $enc_result->fetch(PDO::FETCH_ASSOC);
    $enc_pass=$row_enc['pass'];
    $userid = $_SESSION['MEDID'];
    
            
    $result = $con->prepare("SELECT * FROM dropboxdetails");
	$result->execute();
	
    $count=$result->rowCount();
		
    //mysql_close($link);
    if($count>0) 
    {

        echo "Start scanning dropbox accounts...";
        echo "<br>\n";
        echo $count;
        echo "<br>\n";    
        echo date(DATE_RFC822);
        echo "<br>\n"; 	
		
        while($eachrow=$result->fetch(PDO::FETCH_ASSOC))
        {
	 	
            $appInfo = dbx\AppInfo::loadFromJsonFile("config.json");

            $dbxConfig = new dbx\Config($appInfo, "PHP-Example/1.0");

            $accessToken = dbx\AccessToken::deserialize($eachrow['AccessToken']);

            $dbxClient = new dbx\Client($dbxConfig, $accessToken);

            $accountInfo = $dbxClient->getAccountInfo();

            $EmailDOC = $accountInfo['email'];
            $fromaddress=$eachrow['IdMEDEmail'];
            $fromname= $accountInfo['display_name'];
            $q = $con->prepare("SELECT * FROM doctors WHERE IdMEDEmail = ?");
			$q->bindValue(1, $fromaddress, PDO::PARAM_STR);
			$q->execute();
			
            $row2 = $q->fetch(PDO::FETCH_ASSOC);
            $IdDoc = $row2['id'];
            echo "Scanning ";
            print_r($fromname);
            echo "Dropbox folder <br>";
            //echo "Account Info: " .$accountInfo;
            echo "<br>";

            $path='/';
            $folderMetadata = $dbxClient->getMetadataWithChildren($path);
            

            print("Folder Contents <br>");
            $size=0;
            if($folderMetadata!=null) 
            {
                print_r($folderMetadata['contents']);
                $size=count($folderMetadata['contents']);
            }
             
            $dbxClient->createFolder("/Processed");
            if ($size > 0)
            {
                echo "Total Files present: ";
                echo $size;
                echo "<br>\n"; 	 
            
                for ($i = 0; $i < $size; $i++)
                {
                    //print_r($folderMetadata['contents'][$i]['path']);
                    $filename=ltrim($folderMetadata['contents'][$i]['path'],'/');    //This is used for storing the filename present in the dropbox area
                    //changes for duplicate files.
                    $filepath="Processed/".time().'_'.$filename;
                    /*if($filename=="Processed")
                        continue;*/
                    if($filename=="Processed" )
                    {
                        echo "<br> Skipped ".$filename." folder<br>" ;
                        continue;
                    }
                    else if($folderMetadata['contents'][$i]['is_dir'] == 1)
                    {
                        $childFolderMetadata = $dbxClient->getMetadataWithChildren($folderMetadata['contents'][$i]['path']);
                        $childFolderSize = count($childFolderMetadata['contents']);
                        $childFolderDate = $folderMetadata['contents'][$i]['modified'];
                        if ($childFolderSize > 0)
                        {
                            $folderName = basename($folderMetadata['contents'][$i]['path']);
                            $newFolderPath = "/Processed/".$folderName;
                            
                            $dbxClient->createFolder($newFolderPath);
                            
                            // check if this is a dicom session
                            $isDicom = dicom_check($childFolderMetadata, $childFolderSize);
                            
                            /*for ($k = 0; $k < $childFolderSize; $k++)
                            {
                                $childFileInfo = pathinfo($childFolderMetadata['contents'][$k]['path']);
                                $childFileType = $childFileInfo['extension'];
                                if($childFileType == 'DICOM' || $childFileType == 'dicom' || $childFileType == 'dcm' || $childFileType == 'DCM')
                                {
                                    $isDicom = 1;
                                }
                            }*/
                            
                            if($isDicom == 0)
                            {
                                $child_query = $con->prepare("SELECT * FROM dropbox_sessions WHERE session_name=?");
								$child_query->bindValue(1, $folderName, PDO::PARAM_STR);
								
                                $res = $child_query->execute();
                                if($child_query->rowCount() == 0)
                                {
                                    echo $folderName.'<br/>';
                                    echo $userid.'<br/>';
                                    echo $childFolderDate.'<br/>';
                                    echo $childFolderSize.'<br/>';
                                    $child_query = $con->prepare("INSERT INTO dropbox_sessions (session_name, id, date, size, uploaded, verified) VALUES (?,?,?,?,0,0)");
									$child_query->bindValue(1, $folderName, PDO::PARAM_STR);
									$child_query->bindValue(2, $IdDoc, PDO::PARAM_INT);
									$child_query->bindValue(3, $childFolderDate, PDO::PARAM_STR);
									$child_query->bindValue(4, $childFolderSize, PDO::PARAM_INT);
									
                                    $res = $child_query->execute();
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
                                    $childFileName = basename($childFolderMetadata['contents'][$k]['path']);
                                    
                                    $childFilePath = "Processed/".time().'_'.$childFileName;
                                    $childFileSize = $childFolderMetadata['contents'][$k]['size'];
                                    $childFileDate = $childFolderMetadata['contents'][$k]['modified'];
                                    
                                    $childTempFile = fopen($childFilePath, "w+b" );
                                    $fileMetadata = $dbxClient->getFile($childFolderMetadata['contents'][$k]['path'], $childTempFile);
                                    $checksum=md5_file($childFilePath);
                                    $confcode = 'eML'.md5(date('Ymdgisu'));
                                    $id_pin = parseAndEncrypt($childFileName, $childFolderMetadata, $k, $childFileSize, $childFileDate, $checksum, $childFilePath, false, '', $confcode);
                                    echo "ID PIN: ".$id_pin;
                                    echo "Encrypted file successfully<br/>\n";
                                    try
                                    {
                                        $dbxClient->move($childFolderMetadata['contents'][$k]['path'],$newFolderPath."/".$childFileName);
                                    }catch(Exception $e)
                                    {
                                        echo "<br> Exception Caught:  ".$e."<br>";
                                        $dbxClient->delete($childFolderMetadata['contents'][$k]['path']);
                                    }
                                    echo $folderName.'<br/>';
                                    //echo $userid.'<br/>';
                                    echo $userid.'<br/>';
                                    echo $childFilePath.'<br/>';
                                    echo $childFileSize.'<br/>';
                                    $child_file_query = $con->prepare("INSERT INTO dropbox_sessions_files (session_name, id, id_pin, size, uploaded, verified) VALUES (?,?,?,?,1,0)");
									$child_file_query->bindValue(1, $folderName, PDO::PARAM_STR);
									$child_file_query->bindValue(2, $IdDoc, PDO::PARAM_INT);
									$child_file_query->bindValue(3, $id_pin, PDO::PARAM_INT);
									$child_file_query->bindValue(4, $childFileSize, PDO::PARAM_INT);
									
									
                                    $res = $child_file_query->execute();
                                    if (!$res)
                                    {
                                        echo "Error creating Dropbox Session File<br/>";
                                    }
                                    else
                                    {
                                        echo "New Dropbox Session File Created!<br/>";
                                    }
                                    fclose($childTempFile);
                                    $child_file_query = $con->prepare("UPDATE dropbox_sessions SET uploaded=uploaded+1 WHERE session_name=? AND id=?");
									$child_file_query->bindValue(1, $folderName, PDO::PARAM_STR);
									$child_file_query->bindValue(2, $IdDoc, PDO::PARAM_INT);
									
									
                                    $res = $child_file_query->execute();
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
                                // Connect to server and select databse.
$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }		
                                
                                echo "Found DICOM Folder";
                                
                                
                                $new_dir = "Processed/".time().'_'.$filename;
                                $confcode = 'eML'.md5(date('Ymdgisu'));
                                
                                $pre_checksum = null;//md5_file($new_dir);
                                $pre_folderpath = $folderMetadata['contents'][$i]['path'];
                                $IdPin = 0;
                                $indra_dir = "";
                                // check if this session was already being processed
                                $query = $con->prepare("SELECT * FROM dicom_sessions WHERE name=? AND user=?");
								$query->bindValue(1, $filename, PDO::PARAM_STR);
								$query->bindValue(2, $fromaddress, PDO::PARAM_STR);
								
								
                                $q=$query->execute();
                                if($row = $query->fetch(PDO::FETCH_ASSOC))
                                {
                                    $confcode = $row['encrypted_folder'];
                                    $IdPin = $row['id'];
                                    $indra_dir = $row['indra_folder'];
                                    $new_dir = $row['processed_folder'];
                                }
                                else
                                {
                                    $med_q = $con->prepare("SELECT id FROM doctors WHERE IdMEDEmail=?");
									$med_q->bindValue(1, $fromaddress, PDO::PARAM_STR);
									
									
                                    $med_res = $med_q->execute();
                                    $med_row = $med_q->fetch(PDO::FETCH_ASSOC);
                                    $med_id = $med_row['id'];
                                    $Isql=$con->prepare("INSERT INTO lifepin SET NeedACTION = 1, IdEmail=?, FechaInput=NOW(), Fecha=NOW() , FechaEmail = ?, IdUsFIXED=0, IdUsFIXEDNAME='No Patient Assigned', IdMedEmail = ?, CANAL=7, ValidationStatus=99, EvRuPunt= 2, Evento=99, Tipo=30,checksum=?,isDicom=1,IdMed = ?, IdCreator = ?, CreatorType=1");
									$Isql->bindValue(1, $i, PDO::PARAM_INT);
									$Isql->bindValue(2, $childFolderDate, PDO::PARAM_STR);
									$Isql->bindValue(3, $fromaddress, PDO::PARAM_STR);
									$Isql->bindValue(4, $pre_checksum, PDO::PARAM_STR);
									$Isql->bindValue(5, $med_id, PDO::PARAM_INT);
									$Isql->bindValue(6, $med_id, PDO::PARAM_INT);
									
    
                                    $q=$Isql->execute();
                                    $dicom_dir_name = "";
                                    
                                    $IdPin = $con->lastInsertId(); 
                                    $indra_dir = date('YmdHis')."_".$IdPin;
                                    mkdir($new_dir, 0777);
                                    
                                    $Isql = $con->prepare("INSERT INTO dicom_sessions SET name=?, id=?, indra_folder=?, processed_folder=?, encrypted_folder=?, user=?, ready_for_transfer=0, transfered=0");
									$Isql->bindValue(1, $filename, PDO::PARAM_STR);
									$Isql->bindValue(2, $IdPin, PDO::PARAM_INT);
									$Isql->bindValue(3, $indra_dir, PDO::PARAM_STR);
									$Isql->bindValue(4, $new_dir, PDO::PARAM_STR);
									$Isql->bindValue(5, $confcode, PDO::PARAM_STR);
									$Isql->bindValue(6, $fromaddress, PDO::PARAM_STR);
									
									
                                    $q=$Isql->execute();
                                }
                                
                                // connect to indra server
                                /*$indra_server = "54.225.67.15";
                                $indra_ftp_username = "Indra";
                                $indra_ftp_pass = "IndraH2M14";
                                
                                $indra_connection = ftp_connect($indra_server,$port = 21);
                                $indra_login_result = ftp_login($indra_connection,$indra_ftp_username,$indra_ftp_pass);
                                ftp_pasv($indra_connection, true);
                                if (!$indra_connection || !$indra_login_result) 
                                { 
                                    die('Connection attempt failed!'); 
                                }
                                else
                                {
                                    echo "Connection Successful";
                                }
                                ftp_chdir($indra_connection, "INDRA\ISPACS\INTEGRACION\DicomIN");		//Path where we want to create new folder
                                
                                $contents_on_server = ftp_nlist($indra_connection, ".");
                                if (!in_array($indra_dir, $contents_on_server)) 
                                {
                                    if (ftp_mkdir($indra_connection, $indra_dir)) 
                                    {
                                        echo "successfully created $indra_dir\n";
                                    } 
                                    else 
                                    {
                                        echo "There was a problem while creating $indra_dir\n";
                                    }
                                }
                                ftp_chdir($indra_connection, $indra_dir);
                                
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
                                */
                                $dicom_dir_name = dicom_recursive($childFolderMetadata, $childFolderSize, $childFolderDate, null, $filename, $confcode, $new_dir, $newFolderPath, $indra_connection, $indra_login_result);
                                
                                // upload xml here
                                //upload_dicom_xml($indra_dir, $IdPin, 0, $indra_connection, $indra_login_result);
                                //ftp_close($indra_connection);
                                // end indra connection
                                
                                $dicom_file_info = pathinfo($dicom_dir_name);
                                $dicom_dir_name = $dicom_file_info['dirname'];
                                
                                $path_parts = pathinfo($dicom_dir_name);
                                $dirs = explode("\\", $dicom_dir_name);
                                if(count($dirs) > 0)
                                {
                                    $dicom_dir_name = $dirs[0];
                                }
                                
                                $Isql=$con->prepare("UPDATE lifepin SET IdEmail=?, RawImage=? , FechaInput=NOW(), ValidationStatus=8 , orig_filename =? WHERE IdPin=?");
								$Isql->bindValue(1, ($i+1), PDO::PARAM_INT);
								$Isql->bindValue(2, $dicom_dir_name, PDO::PARAM_STR);
								$Isql->bindValue(3, $filename, PDO::PARAM_STR);
								$Isql->bindValue(4, $IdPin, PDO::PARAM_INT);
								
								
                                $q = $Isql->execute();
                            }
                            $dbxClient->delete($folderMetadata['contents'][$i]['path']);
                        }
                        continue;
                    }
                    
                    
                    $Tamano=$folderMetadata['contents'][$i]['size'];
                    $date=$folderMetadata['contents'][$i]['modified'];
                    print(ltrim($filename, '/'));
                    
                    //print_r(ltrim($folderMetadata['contents'][$i]['path'], '/'));
                
                    //Code for parsing the filename and extracting information from filestrings array
                    //in order of [1]&[2] Patient first& Last name,[3] Date,[4] Type,[5] Last Part
                    
                    //Check for record duplication
                    
                    //echo md5_file($filename);
                    
                    
                    $f = fopen($filepath, "w+b" );
                    $fileMetadata = $dbxClient->getFile($folderMetadata['contents'][$i]['path'], $f);
                    try
                    {
                        $dbxClient->move("/".$filename,"/Processed/".$filename);
                    }
                    catch(Exception $e)
                    {
                        echo "<br> Exception Caught:  ".$e."<br>";
                        $dbxClient->delete($folderMetadata['contents'][$i]['path']);
                    }
                    //$dbxClient->delete($folderMetadata['contents'][$i]['path']);
                    echo "<br>\n"; 
                    echo "Checksum: "; 
                    $checksum=md5_file($filepath);
                    echo $checksum; 
                    echo "<br>\n"; 
                 
                    $confcode = 'eML'.md5(date('Ymdgisu'));
                    parseAndEncrypt($filename, $folderMetadata, $i, $Tamano, $date, $checksum, $filepath, false, '', $confcode);
                    fclose($f);
                            
                                 
                    // FTP TRANSFER *****************************************************************************************	// TRANSFERENCIA DEL ARCHIVO AL SERVIDOR QUE TIENE LA BASE DE DATOS  (PinEXT)
                    /*$server = "monimed.com"; //target server address or domain name from we wana download file
                    $user = "moni8484"; //username on target server
                    $pass = "ardiLLA98@"; //password on target server for Ftp*/
                    
                    //This is added for testing in the development environments=
                    
                    /*$s//target server address or domain name from we wana download file
                    $user = "ITGroup"; //username on target server
                    $pass = "InmersIT";*/
                    /*
                    $server = $env_var_ftp['server']; //target server address or domain name from we wana download file
                    $user = $env_var_ftp['user']; //username on target server
                    $pass = $env_var_ftp['pass']; //password on target server for Ftp
                    
                    $local_file = './Packages_Encrypted/'.$new_image_name;
                    $dest_file = $new_image_name ;*/

                    /*$connection = ftp_connect($server, $port = 21);
                    $login = ftp_login($connection, $user, $pass);
                    ftp_pasv($connection, true);

                    /*if (!$connection || !$login) { 
                    
                    die('Connection attempt failed!'); 
                    //echo "Connection attempt failed";
                    echo "<br>\n"; 
                    LogMov($IdUsu, $fromname, $fromaddress, '3', 'File Upload', 'FTP Connection attempt failed', $fielname, $i);
                    } */
                    //if(ftp_chdir($connection, "htdocs")) {
                        
                    /*	if (ftp_chdir($connection, "PinEXT")) {
                       
                           $upload = ftp_put($connection, $dest_file, $local_file, FTP_BINARY);
                        
                           if (!$upload) {
                             echo 'FTP upload failed!'; 
                           }else  {
                            // EL FTP HA IDO BIEN.  YA SE PUEDE CREAR EL BLOCK ****************************
                            echo "File uploaded successfully";
                            echo "<br>\n"; 
                            echo "<br>\n"; 
                            LogMov($IdUsFIXED, $fromname, $fromaddress, '3', 'File Upload', 'FTP file UPLOADED SUCCESSFULLY', $filename, $i);	
                        
                        
                            // Inserta el PIN en la Base de Datos
                            $confirm_code = md5(uniqid(rand()));
                            
                            //echo ' ************************************************ INSERCION 2 EN BASE DE DATOS ***********************************************************';
                            
                            echo 'UPDATE IdUsFIXED = '.$IdUsFIXED;
                            echo "<br>\n"; 
                            echo "IdPin = ";
                            echo $IdPin;
                            echo "<br>\n"; 
                            
                            echo 'UPDATE IdUsFIXED = '.intval($IdUsFIXED);
                                                            
                            $Isql="UPDATE lifepin SET IdEmail='$i', RawImage='$dest_file' , FechaInput=NOW() , IdUsFIXED='$IdUsFIXED', ValidationStatus=8  WHERE IdPin='$IdPin'";
                           
                            $q = mysql_query($Isql);
                            $IdPin = mysql_insert_id();
                            // if successfully insert data into database, displays message "Successful". 
                            if($q){
                                    echo "Successful: Database Updated";
                                    echo "<br>\n"; 	
                                    echo "REGISTROS CAMBIADOS: ".mysql_affected_rows();
                                    echo "<br>\n"; 	
                                    //  EnviaMail($eMailUsu,0, $IdUsu, $fromname, $fromaddress, $IdPin, $confirm_code);
                                    LogMov($IdUsFIXED, $fromname, $fromaddress, '4', 'dB INSERTION', 'PIN INSERTED, Database UPDATED' , $filename, $i);

                                    }
                                    else{
                                    echo "Error Updating Database ********";
                                    echo "<br>\n"; 	 
                                    LogMov($IdUsFIXED, $fromname, $fromaddress, '4', 'dB INSERTION', 'Error Updating Database ********' , $filename, $i);

                                    }
                                }

                            } 
                        //} 
                        else { 
                            echo "Couldn't change directory\n";
                            echo "<br>\n"; 
                            LogMov($IdUsFIXED, $fromname, $fromaddress, '3', 'File Upload', 'FTP directory change failed', $filename, $i);

                            echo "<br>\n"; 	    
                        } 	*/

=======
                                echo "Found DICOM Folder";
                                $new_dir = "Processed/".time().'_'.$filename;
                                $confcode = md5(date('Ymdgisu'));
                                mkdir($new_dir, 0777);
                                
                                // Connect to server and select databse.
$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }		
                                $pre_checksum = null;//md5_file($new_dir);
                                $pre_folderpath = $folderMetadata['contents'][$i]['path'];
                                
                                $Isql=$con->prepare("INSERT INTO lifepin SET NeedACTION = 1, IdEmail=?, FechaInput=NOW(), Fecha=NOW() , FechaEmail = ?, IdUsFIXED=0, IdUsFIXEDNAME='No Patient Assigned', IdMedEmail = ?, CANAL=7, ValidationStatus=99, EvRuPunt= 2, Evento=99, Tipo=30,checksum='$pre_checksum',isDicom=1");
								$Isql->bindValue(1, $i, PDO::PARAM_INT);
								$Isql->bindValue(2, $childFolderDate, PDO::PARAM_STR);
								$Isql->bindValue(3, $fromaddress, PDO::PARAM_STR);
								$Isql->bindValue(4, $pre_checksum, PDO::PARAM_STR);
								

                                $q=$Isql->execute();
                                $dicom_dir_name = "";
                                
                                $IdPin = $con->lastInsertId(); 
                                
                                // connect to indra server
                                $indra_server = "54.225.67.15";
                                $indra_ftp_username = "Indra";
                                $indra_ftp_pass = "IndraH2M14";
                                
                                $indra_connection = ftp_connect($indra_server,$port = 21);
                                $indra_login_result = ftp_login($indra_connection,$indra_ftp_username,$indra_ftp_pass);
                                ftp_pasv($indra_connection, true);
                                if (!$indra_connection || !$indra_login_result) 
                                { 
                                    die('Connection attempt failed!'); 
                                }
                                else
                                {
                                    echo "Connection Successful";
                                }
                                ftp_chdir($indra_connection, "INDRA\ISPACS\INTEGRACION\DicomIN");		//Path where we want to create new folder
                                $indra_dir = date('YmdHis')."_".$IdPin;
                                if (ftp_mkdir($indra_connection, $indra_dir)) 
                                {
                                    echo "successfully created $indra_dir\n";
                                } 
                                else 
                                {
                                    die("There was a problem while creating $indra_dir\n");
                                }
                                ftp_chdir($indra_connection, $indra_dir);
                                
                                $DICOM_dir = "DICOM";
                                if (ftp_mkdir($indra_connection, $DICOM_dir)) 
                                {
                                    echo "successfully created $DICOM_dir\n";
                                } 
                                else 
                                {
                                    die("There was a problem while creating $DICOM_dir\n");
                                }
                                ftp_chdir($indra_connection, $DICOM_dir);
                                
                                $dicom_dir_name = dicom_recursive($childFolderMetadata, $childFolderSize, $childFolderDate, null, $filename, $confcode, $new_dir, $newFolderPath, $indra_connection, $indra_login_result);
                                
                                // upload xml here
                                upload_dicom_xml($indra_dir, $IdPin, 0, $indra_connection, $indra_login_result);
                                ftp_close($indra_connection);
                                // end indra connection
                                
                                $dicom_file_info = pathinfo($dicom_dir_name);
                                $dicom_dir_name = $dicom_file_info['dirname'];
                                
                                $path_parts = pathinfo($dicom_dir_name);
                                $dirs = explode("\\", $dicom_dir_name);
                                if(count($dirs) > 0)
                                {
                                    $dicom_dir_name = $dirs[0];
                                }
                                
                                $Isql=$con->prepare("UPDATE lifepin SET IdEmail=?, RawImage=? , FechaInput=NOW(), ValidationStatus=8 , orig_filename =? WHERE IdPin=?");
								$Isql->bindValue(1, ($k+1), PDO::PARAM_INT);
								$Isql->bindValue(2, $dicom_dir_name, PDO::PARAM_STR);
								$Isql->bindValue(3, $filename, PDO::PARAM_STR);
								$Isql->bindValue(4, $IdPin, PDO::PARAM_INT);
								
								
                                $q = $Isql->execute();
                            }
                            //$dbxClient->delete($folderMetadata['contents'][$i]['path']);
                        }
                        continue;
                    }
                    
                    
                    $Tamano=$folderMetadata['contents'][$i]['size'];
                    $date=$folderMetadata['contents'][$i]['modified'];
                    print(ltrim($filename, '/'));
                    
                    //print_r(ltrim($folderMetadata['contents'][$i]['path'], '/'));
                
                    //Code for parsing the filename and extracting information from filestrings array
                    //in order of [1]&[2] Patient first& Last name,[3] Date,[4] Type,[5] Last Part
                    
                    //Check for record duplication
                    
                    //echo md5_file($filename);
                    
                    
                    $f = fopen($filepath, "w+b" );
                    $fileMetadata = $dbxClient->getFile($folderMetadata['contents'][$i]['path'], $f);
                    try
                    {
                        $dbxClient->move("/".$filename,"/Processed/".$filename);
                    }
                    catch(Exception $e)
                    {
                        echo "<br> Exception Caught:  ".$e."<br>";
                        $dbxClient->delete($folderMetadata['contents'][$i]['path']);
                    }
                    //$dbxClient->delete($folderMetadata['contents'][$i]['path']);
                    echo "<br>\n"; 
                    echo "Checksum: "; 
                    $checksum=md5_file($filepath);
                    echo $checksum; 
                    echo "<br>\n"; 
                 
                
                    parseAndEncrypt($filename, $folderMetadata, $i, $Tamano, $date, $checksum, $filepath, false, '', '');
                    fclose($f);
                            
                                 
                    // FTP TRANSFER *****************************************************************************************	// TRANSFERENCIA DEL ARCHIVO AL SERVIDOR QUE TIENE LA BASE DE DATOS  (PinEXT)
                    /*$server = "monimed.com"; //target server address or domain name from we wana download file
                    $user = "moni8484"; //username on target server
                    $pass = "ardiLLA98@"; //password on target server for Ftp*/
                    
                    //This is added for testing in the development environments=
                    
                    /*$server =target server address or domain name from we wana download file
                    $user = "ITGroup"; //username on target server
                    $pass = "InmersIT";*/
                    /*
                    $server = $env_var_ftp['server']; //target server address or domain name from we wana download file
                    $user = $env_var_ftp['user']; //username on target server
                    $pass = $env_var_ftp['pass']; //password on target server for Ftp
                    
                    $local_file = './Packages_Encrypted/'.$new_image_name;
                    $dest_file = $new_image_name ;*/

                    /*$connection = ftp_connect($server, $port = 21);
                    $login = ftp_login($connection, $user, $pass);
                    ftp_pasv($connection, true);

                    /*if (!$connection || !$login) { 
                    
                    die('Connection attempt failed!'); 
                    //echo "Connection attempt failed";
                    echo "<br>\n"; 
                    LogMov($IdUsu, $fromname, $fromaddress, '3', 'File Upload', 'FTP Connection attempt failed', $fielname, $i);
                    } */
                    //if(ftp_chdir($connection, "htdocs")) {
                        
                    /*	if (ftp_chdir($connection, "PinEXT")) {
                       
                           $upload = ftp_put($connection, $dest_file, $local_file, FTP_BINARY);
                        
                           if (!$upload) {
                             echo 'FTP upload failed!'; 
                           }else  {
                            // EL FTP HA IDO BIEN.  YA SE PUEDE CREAR EL BLOCK ****************************
                            echo "File uploaded successfully";
                            echo "<br>\n"; 
                            echo "<br>\n"; 
                            LogMov($IdUsFIXED, $fromname, $fromaddress, '3', 'File Upload', 'FTP file UPLOADED SUCCESSFULLY', $filename, $i);	
                        
                        
                            // Inserta el PIN en la Base de Datos
                            $confirm_code = md5(uniqid(rand()));
                            
                            //echo ' ************************************************ INSERCION 2 EN BASE DE DATOS ***********************************************************';
                            
                            echo 'UPDATE IdUsFIXED = '.$IdUsFIXED;
                            echo "<br>\n"; 
                            echo "IdPin = ";
                            echo $IdPin;
                            echo "<br>\n"; 
                            
                            echo 'UPDATE IdUsFIXED = '.intval($IdUsFIXED);
                                                            
                            $Isql="UPDATE lifepin SET IdEmail='$i', RawImage='$dest_file' , FechaInput=NOW() , IdUsFIXED='$IdUsFIXED', ValidationStatus=8  WHERE IdPin='$IdPin'";
                           
                            $q = mysql_query($Isql);
                            $IdPin = mysql_insert_id();
                            // if successfully insert data into database, displays message "Successful". 
                            if($q){
                                    echo "Successful: Database Updated";
                                    echo "<br>\n"; 	
                                    echo "REGISTROS CAMBIADOS: ".mysql_affected_rows();
                                    echo "<br>\n"; 	
                                    //  EnviaMail($eMailUsu,0, $IdUsu, $fromname, $fromaddress, $IdPin, $confirm_code);
                                    LogMov($IdUsFIXED, $fromname, $fromaddress, '4', 'dB INSERTION', 'PIN INSERTED, Database UPDATED' , $filename, $i);

                                    }
                                    else{
                                    echo "Error Updating Database ********";
                                    echo "<br>\n"; 	 
                                    LogMov($IdUsFIXED, $fromname, $fromaddress, '4', 'dB INSERTION', 'Error Updating Database ********' , $filename, $i);

                                    }
                                }

=======
                                echo "Found DICOM Folder";
                                $new_dir = "Processed/".time().'_'.$filename;
                                $confcode = md5(date('Ymdgisu'));
                                mkdir($new_dir, 0777);
                                
                                $link = mysql_connect("$dbhost", "$dbuser", "$dbpass")or die("cannot connect");

                                mysql_select_db("$dbname")or die("cannot select DB");
                                $pre_checksum = null;//md5_file($new_dir);
                                $pre_folderpath = $folderMetadata['contents'][$i]['path'];
                                
                                $Isql="INSERT INTO lifepin SET NeedACTION = 1, IdEmail='$i', FechaInput=NOW(), Fecha=NOW() , FechaEmail = '$childFolderDate', IdUsFIXED=0, IdUsFIXEDNAME='No Patient Assigned', IdMedEmail = '$fromaddress', CANAL=7, ValidationStatus=99, EvRuPunt= 2, Evento=99, Tipo=30,checksum='$pre_checksum',isDicom=1";

                                $q=mysql_query($Isql, $link);
                                $dicom_dir_name = "";
                                
                                $IdPin = mysql_insert_id();
                                
                                // connect to indra server
                                $indra_server = "54.225.67.15";
                                $indra_ftp_username = "Indra";
                                $indra_ftp_pass = "IndraH2M14";
                                
                                $indra_connection = ftp_connect($indra_server,$port = 21);
                                $indra_login_result = ftp_login($indra_connection,$indra_ftp_username,$indra_ftp_pass);
                                ftp_pasv($indra_connection, true);
                                if (!$indra_connection || !$indra_login_result) 
                                { 
                                    die('Connection attempt failed!'); 
                                }
                                else
                                {
                                    echo "Connection Successful";
                                }
                                ftp_chdir($indra_connection, "INDRA\ISPACS\INTEGRACION\DicomIN");		//Path where we want to create new folder
                                $indra_dir = date('YmdHis')."_".$IdPin;
                                if (ftp_mkdir($indra_connection, $indra_dir)) 
                                {
                                    echo "successfully created $indra_dir\n";
                                } 
                                else 
                                {
                                    die("There was a problem while creating $indra_dir\n");
                                }
                                ftp_chdir($indra_connection, $indra_dir);
                                
                                $DICOM_dir = "DICOM";
                                if (ftp_mkdir($indra_connection, $DICOM_dir)) 
                                {
                                    echo "successfully created $DICOM_dir\n";
                                } 
                                else 
                                {
                                    die("There was a problem while creating $DICOM_dir\n");
                                }
                                ftp_chdir($indra_connection, $DICOM_dir);
                                
                                $dicom_dir_name = dicom_recursive($childFolderMetadata, $childFolderSize, $childFolderDate, null, $filename, $confcode, $new_dir, $newFolderPath, $indra_connection, $indra_login_result);
                                
                                // upload xml here
                                upload_dicom_xml($indra_dir, $IdPin, 0, $indra_connection, $indra_login_result);
                                ftp_close($indra_connection);
                                // end indra connection
                                
                                $dicom_file_info = pathinfo($dicom_dir_name);
                                $dicom_dir_name = $dicom_file_info['dirname'];
                                
                                $path_parts = pathinfo($dicom_dir_name);
                                $dirs = explode("\\", $dicom_dir_name);
                                if(count($dirs) > 0)
                                {
                                    $dicom_dir_name = $dirs[0];
                                }
                                
                                $Isql="UPDATE lifepin SET IdEmail='$k+1', RawImage='$dicom_dir_name' , FechaInput=NOW(), ValidationStatus=8 , orig_filename ='$filename' WHERE IdPin='$IdPin'";
                                $q = mysql_query($Isql);
                            }
                            //$dbxClient->delete($folderMetadata['contents'][$i]['path']);
                        }
                        continue;
                    }
                    
                    
                    $Tamano=$folderMetadata['contents'][$i]['size'];
                    $date=$folderMetadata['contents'][$i]['modified'];
                    print(ltrim($filename, '/'));
                    
                    //print_r(ltrim($folderMetadata['contents'][$i]['path'], '/'));
                
                    //Code for parsing the filename and extracting information from filestrings array
                    //in order of [1]&[2] Patient first& Last name,[3] Date,[4] Type,[5] Last Part
                    
                    //Check for record duplication
                    
                    //echo md5_file($filename);
                    
                    
                    $f = fopen($filepath, "w+b" );
                    $fileMetadata = $dbxClient->getFile($folderMetadata['contents'][$i]['path'], $f);
                    try
                    {
                        $dbxClient->move("/".$filename,"/Processed/".$filename);
                    }
                    catch(Exception $e)
                    {
                        echo "<br> Exception Caught:  ".$e."<br>";
                        $dbxClient->delete($folderMetadata['contents'][$i]['path']);
                    }
                    //$dbxClient->delete($folderMetadata['contents'][$i]['path']);
                    echo "<br>\n"; 
                    echo "Checksum: "; 
                    $checksum=md5_file($filepath);
                    echo $checksum; 
                    echo "<br>\n"; 
                 
                
                    parseAndEncrypt($filename, $folderMetadata, $i, $Tamano, $date, $checksum, $filepath, false, '', '');
                    fclose($f);
                            
                                 
                    // FTP TRANSFER *****************************************************************************************	// TRANSFERENCIA DEL ARCHIVO AL SERVIDOR QUE TIENE LA BASE DE DATOS  (PinEXT)
                    /*$server = "monimed.com"; //target server address or domain name from we wana download file
                    $user = "moni8484"; //username on target server
                    $pass = "ardiLLA98@"; //password on target server for Ftp*/
                    
                    //This is added for testing in the development environments=
                    
                    /*$serverget server address or domain name from we wana download file
                    $user = "ITGroup"; //username on target server
                    $pass = "InmersIT";*/
                    /*
                    $server = $env_var_ftp['server']; //target server address or domain name from we wana download file
                    $user = $env_var_ftp['user']; //username on target server
                    $pass = $env_var_ftp['pass']; //password on target server for Ftp
                    
                    $local_file = './Packages_Encrypted/'.$new_image_name;
                    $dest_file = $new_image_name ;*/

                    /*$connection = ftp_connect($server, $port = 21);
                    $login = ftp_login($connection, $user, $pass);
                    ftp_pasv($connection, true);

                    /*if (!$connection || !$login) { 
                    
                    die('Connection attempt failed!'); 
                    //echo "Connection attempt failed";
                    echo "<br>\n"; 
                    LogMov($IdUsu, $fromname, $fromaddress, '3', 'File Upload', 'FTP Connection attempt failed', $fielname, $i);
                    } */
                    //if(ftp_chdir($connection, "htdocs")) {
                        
                    /*	if (ftp_chdir($connection, "PinEXT")) {
                       
                           $upload = ftp_put($connection, $dest_file, $local_file, FTP_BINARY);
                        
                           if (!$upload) {
                             echo 'FTP upload failed!'; 
                           }else  {
                            // EL FTP HA IDO BIEN.  YA SE PUEDE CREAR EL BLOCK ****************************
                            echo "File uploaded successfully";
                            echo "<br>\n"; 
                            echo "<br>\n"; 
                            LogMov($IdUsFIXED, $fromname, $fromaddress, '3', 'File Upload', 'FTP file UPLOADED SUCCESSFULLY', $filename, $i);	
                        
                        
                            // Inserta el PIN en la Base de Datos
                            $confirm_code = md5(uniqid(rand()));
                            
                            //echo ' ************************************************ INSERCION 2 EN BASE DE DATOS ***********************************************************';
                            
                            echo 'UPDATE IdUsFIXED = '.$IdUsFIXED;
                            echo "<br>\n"; 
                            echo "IdPin = ";
                            echo $IdPin;
                            echo "<br>\n"; 
                            
                            echo 'UPDATE IdUsFIXED = '.intval($IdUsFIXED);
                                                            
                            $Isql="UPDATE lifepin SET IdEmail='$i', RawImage='$dest_file' , FechaInput=NOW() , IdUsFIXED='$IdUsFIXED', ValidationStatus=8  WHERE IdPin='$IdPin'";
                           
                            $q = mysql_query($Isql);
                            $IdPin = mysql_insert_id();
                            // if successfully insert data into database, displays message "Successful". 
                            if($q){
                                    echo "Successful: Database Updated";
                                    echo "<br>\n"; 	
                                    echo "REGISTROS CAMBIADOS: ".mysql_affected_rows();
                                    echo "<br>\n"; 	
                                    //  EnviaMail($eMailUsu,0, $IdUsu, $fromname, $fromaddress, $IdPin, $confirm_code);
                                    LogMov($IdUsFIXED, $fromname, $fromaddress, '4', 'dB INSERTION', 'PIN INSERTED, Database UPDATED' , $filename, $i);

                                    }
                                    else{
                                    echo "Error Updating Database ********";
                                    echo "<br>\n"; 	 
                                    LogMov($IdUsFIXED, $fromname, $fromaddress, '4', 'dB INSERTION', 'Error Updating Database ********' , $filename, $i);

                                    }
                                }

>>>>>>> 853044a7b443fb0ea9259bccfbab8c480c5161d9
                            } 
                        //} 
                        else { 
                            echo "Couldn't change directory\n";
                            echo "<br>\n"; 
                            LogMov($IdUsFIXED, $fromname, $fromaddress, '3', 'File Upload', 'FTP directory change failed', $filename, $i);

                            echo "<br>\n"; 	    
                        } 	*/

                            //ftp_close($connection); 								
                        
                    //}		
                        
                        
                }

            }
                         
        }// This ends the For loop for each row from dropboxdetail sql table
                
    }
    else
    {
        echo "The DropBox Cloud Channel has not been activated yet for any user!!";
        echo "<br>\n";
        //mysql_close($link);
    }
		
		
}

mysql_close($con);

// this function recursively checks if there are any dicom files in a folder
function dicom_check($childFolderMetadata, $childFolderSize)
{
    global $dbxClient;
    $res = 0;
    for ($k = 0; $k < $childFolderSize; $k++)
    {
        if($childFolderMetadata['contents'][$k]['is_dir'] == 1)
        {
            $newFolderMetadata = $dbxClient->getMetadataWithChildren($childFolderMetadata['contents'][$k]['path']);
            $newFolderSize = count($newFolderMetadata['contents']);
            $new_res = dicom_check($newFolderMetadata, $newFolderSize);
            if($new_res == 1)
                $res = 1;
        }
        else
        {
            $childFileInfo = pathinfo($childFolderMetadata['contents'][$k]['path']);
            $childFileType = $childFileInfo['extension'];
            if($childFileType == 'DICOM' || $childFileType == 'dicom' || $childFileType == 'dcm' || $childFileType == 'DCM')
            {
                $res = 1;
            }
        }
    }
    return $res;
}

// this function recursively uploads files from dropbox for a dicom session folder
function dicom_recursive($childFolderMetadata, $childFolderSize, $childFolderDate, $checksum, $filename, $confcode, $new_dir, $newFolderPath, $ftp_conn, $ftp_login)
{
    global $dbxClient;
    $dicom_dir_name = "";
    for ($k = 0; $k < $childFolderSize; $k++)
    {
        if($childFolderMetadata['contents'][$k]['is_dir'] == 1)
        {
            $dbxClient->createFolder('/Processed/'.$childFolderMetadata['contents'][$k]['path']);
            $newFolderMetadata = $dbxClient->getMetadataWithChildren($childFolderMetadata['contents'][$k]['path']);
            $newFolderSize = count($newFolderMetadata['contents']);
            $newFolderDate = $childFolderMetadata['contents'][$k]['modified'];
            $newFolderName = basename($childFolderMetadata['contents'][$k]['path']);
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
            $dicom_dir_name = dicom_recursive($newFolderMetadata, $newFolderSize, $newFolderDate, $checksum, $filename, $new_confcode, $new_new_dir, $new_newFolderPath, $ftp_conn, $ftp_login);
            
            
        }
        else
        {
            $childFileName = basename($childFolderMetadata['contents'][$k]['path']);
            echo "HANDLING FILE: ".$childFileName;
            $childFilePath = $new_dir.'/'.$childFileName;
            $childTempFile = fopen($childFilePath, "w+b" );
            $fileMetadata = $dbxClient->getFile($childFolderMetadata['contents'][$k]['path'], $childTempFile);
            $checksum = null;//md5_file($childFilePath);
            //upload_dicom_file($childFilePath, $ftp_conn, $ftp_login);
            upload_dicom_file($childFilePath, $ftp_conn, $ftp_login);
            $dicom_dir_name = parseAndEncrypt($childFileName, $childFolderMetadata, $k, $childFolderSize, $childFolderDate, $checksum, $childFilePath, true, $filename, $confcode);
            
            fclose($childTempFile);

            try
            {
                $dbxClient->move($childFolderMetadata['contents'][$k]['path'],$newFolderPath."/".$childFileName);
            }catch(Exception $e)
            {
                echo "<br> Exception Caught:  ".$e."<br>";
                $dbxClient->delete($childFolderMetadata['contents'][$k]['path']);
            }
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
                        $Isql = $con->prepare("UPDATE dicom_sessions SET transfered=? WHERE id=?");
						$Isql->bindValue(1, $uploaded, PDO::PARAM_INT);
						$Isql->bindValue(2, $IdPin, PDO::PARAM_INT);
						
						
                        $res = $Isql->execute();
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
                                  
function parseAndEncrypt($filename, $folderMetadata, $i, $Tamano, $date, $checksum, $filepath, $isDicom, $foldername, $code)
{
    global $EmailDOC, $fromaddress, $fromname, $dbhost, $dbname, $dbuser, $dbpass, $enc_pass;
    $filestrings=explode("$", $filename);     //The components are separated by $
    echo "<br>\n"; 	 
    echo "filename :";
    print_r($filestrings);
    echo "<br>\n";
    echo "File Type ";
    $laststring=$folderMetadata['contents'][$i]['mime_type'];
    $extension = '';
    if(!$isDicom)
    {
       $extension= substr($laststring,1+strpos($laststring, '/'));
        echo "EXTENSION : ".$extension; 
    }
    
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
$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }		
    $q = 1;
    if(!$isDicom)
    {
        if($format){
         $Isql=$con->prepare("INSERT INTO lifepin SET NeedACTION = 1, IdEmail=?, IdUsu=?, FechaInput=NOW(), Fecha=? , FechaEmail = ?, IdUsFIXED=?, IdUsFIXEDNAME=?, IdMedEmail = ?, CANAL=7, ValidationStatus=99, EvRuPunt= 2, Evento=99, Tipo=?, fs=1, checksum=?,isDicom=0");
		 $Isql->bindValue(1, $i, PDO::PARAM_INT);
		 $Isql->bindValue(2, $IdUsu, PDO::PARAM_INT);
		 $Isql->bindValue(3, $report_date, PDO::PARAM_STR);
		 $Isql->bindValue(4, $date, PDO::PARAM_STR);
		 $Isql->bindValue(5, $IdUsFIXED, PDO::PARAM_INT);
		 $Isql->bindValue(6, $IdUsFIXEDNAME, PDO::PARAM_STR);
		 $Isql->bindValue(7, $fromaddress, PDO::PARAM_STR);
		 $Isql->bindValue(8, $typoid, PDO::PARAM_INT);
		 $Isql->bindValue(9, $checksum, PDO::PARAM_STR);
		 
		 
		 $Isql2 = "INSERT INTO lifepin SET NeedACTION = 1, IdEmail=?, IdUsu=?, FechaInput=NOW(), Fecha=? , FechaEmail = ?, IdUsFIXED=?, IdUsFIXEDNAME=?, IdMedEmail = ?, CANAL=7, ValidationStatus=99, EvRuPunt= 2, Evento=99, Tipo=?, fs=1, checksum=?,isDicom=0";
		 
         echo "<br>\n"; 	 
         echo $Isql2;
        }else {
         $Isql=$con->prepare("INSERT INTO lifepin SET NeedACTION = 1, IdEmail=?, FechaInput=NOW(), Fecha=NOW() , FechaEmail = ?, IdUsFIXED=?, IdUsFIXEDNAME=?, IdMedEmail = ?, CANAL=7, ValidationStatus=99, EvRuPunt= 2, Evento=99, Tipo=30,checksum=?,isDicom=0");
		 $Isql->bindValue(1, $i, PDO::PARAM_INT);
		 $Isql->bindValue(2, $date, PDO::PARAM_STR);
		 $Isql->bindValue(3, $IdUsFIXED, PDO::PARAM_INT);
		 $Isql->bindValue(4, $IdUsFIXEDNAME, PDO::PARAM_STR);
		 $Isql->bindValue(5, $fromaddress, PDO::PARAM_STR);
		 $Isql->bindValue(6, $checksum, PDO::PARAM_STR);
         echo "<br>\n"; 
		 
		 $Isql2="INSERT INTO lifepin SET NeedACTION = 1, IdEmail='$i', FechaInput=NOW(), Fecha=NOW() , FechaEmail = '$date', IdUsFIXED='$IdUsFIXED', IdUsFIXEDNAME='$IdUsFIXEDNAME', IdMedEmail = '$fromaddress', CANAL=7, ValidationStatus=99, EvRuPunt= 2, Evento=99, Tipo=30,checksum='$checksum',isDicom=0";
         echo $Isql2;	 
        }
    
    
        echo "<br>\n"; 		
        echo "<br>\n"; 	    
        //$q = mysql_query($Isql) or die(mysql_error());
        $q=$Isql->execute();
    }
    
    
    //$q = mysqli::query($Isql);		 
    if($q){
            //echo $Isql;
            echo "Successful: Database Updated";
            echo "<br>\n"; 	
    }else {
                                                                                                                                
        if(($errorno=mysql_errno($con))==1062){
            
                                                                                                                        
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

        EnviaMail2($EmailDOC, $IdUsFIXED, 'MediBANK Cloud Channel validation system','Duplicate clinical record information alert.', $message, 0, 9999, 'Grace@health2.me', 'Inmers', 9);  		 
        }else {
            
        //LogBLOCK (0, 'Error in record found.', $EmailDOC, '', $IdUsFIXED, '', '', 1); 																											
                                                                                                                    
        }																									
            
            //echo "Error Updating Database :" . mysql_error();
            echo mysql_errno($con) . ": " . mysql_error($con) . "\n";
            die(mysql_error($con));
            echo "<br>\n"; 	
    }	
    $IdPin = 0;
    $IdPinBackup = 0;
    if(!$isDicom)
    {
        $IdPin = $con->lastInsertId(); 
        
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
    shell_exec("rm temp/".$new_image_nameTH);         				//Encrypt the thumbnail(added by ankit)
            echo "Thumbnail Encrypted Successfully";															//added by ankit
            //reación de un thumbnail desde el PDF  
        }
        
    
    shell_exec("echo '".$enc_pass."' | openssl aes-256-cbc -pass stdin -salt -in Packages_Encrypted/".$new_image_name." -out temp/".$new_image_name);
    shell_exec("rm Packages_Encrypted/".$new_image_name);
    shell_exec("cp temp/".$new_image_name." Packages_Encrypted/");
    shell_exec("rm temp/".$new_image_name);          //Encrypt the report (ankit)
        echo "<br>File Encrypted in Packages_Encrypted Folder";   			//added by ankit
      
        echo "Uploading to Parsing Server : ";
        //$status =  upload_to_parsing_server($new_image_name);
        //echo $status . "<br><br>";
        $q = 1;
        if(!$isDicom)
        {
            $Isql=$con->prepare("UPDATE lifepin SET IdEmail=?, RawImage=? , FechaInput=NOW(), ValidationStatus=8 , orig_filename =? WHERE IdPin=?");
			$Isql->bindValue(1, ($i+1), PDO::PARAM_INT);
			$Isql->bindValue(2, $new_image_name, PDO::PARAM_STR);
			$Isql->bindValue(3, $filename, PDO::PARAM_STR);
			$Isql->bindValue(4, $IdPin, PDO::PARAM_INT);
			
			
            $q = $Isql->execute();
        }
        $IdPinBackup = $IdPin;
        if(!$isDicom)
        {
            $IdPin = $con->lastInsertId(); 
        }
        // if successfully insert data into database, displays message "Successful". 
        if($q){
            echo "Successful: Database Updated";
            echo "<br>\n"; 	
            echo "REGISTROS CAMBIADOS: ".$Isql->rowCount();
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
$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }		
				
				echo"<br>";
				echo "Fetching Patient ID details......";
				
				$query=$con->prepare("SELECT * FROM usuarios where Name=? and Surname=?");
				$query->bindValue(1, $firstname, PDO::PARAM_STR);
				$query->bindValue(2, $lastname, PDO::PARAM_STR);
				$query_hold = "SELECT * FROM usuarios where Name=? and Surname=?";
				
				$result1 = $query->execute();
				$count1=$query->rowCount();
				echo "<br>";
				echo "query: ".$query_hold;
				echo "<br>";
				echo "count: ".$count1;
				$i=-1;
				if ($count1>1){
					
				}else if ($count1==0){					
					$i=0;					
				}else if ($count1==1){
					$row=$query->fetch(PDO::FETCH_ASSOC);
					//$i=$row['Identif'];
					return $row;
				}
		       
			    mysql_close($con);
			    return $i;
			   
	}	  	
	
	function getReportType($type)	{
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
				
				echo"<br>";
				echo "Fetching Report Type......";
						
				//$query="SELECT * FROM tipopin where NombreEng='$type'";
				$query=$con->prepare("SELECT * FROM tipopin where abreviation=?");
				$query->bindValue(1, $type, PDO::PARAM_STR);
				$query_holder= "SELECT * FROM tipopin where abreviation=?";
				
				$result1 = $query->execute();
				$count1=$query->rowCount();
				echo "<br>";
				echo "query: ".$query_holder;
				echo "<br>";
				echo "count: ".$count1;
				$i=-1;
				if ($count1>1){
					
				}else if ($count1==0){					
					$i=0;					
				}else if ($count1==1){
					$row=$query->fetch(PDO::FETCH_ASSOC);
					$i=$row['Id'];
				}
		       
			    mysql_close($con);
			    return $i;
			   
	}	
	
	function checkDuplicateRecord($checksum)	{
	
		require("environment_detail.php");		
		 $dbhost = $env_var_db['dbhost'];
		 $dbname = $env_var_db['dbname'];
		 $dbuser = $env_var_db['dbuser'];
		 $dbpass = $env_var_db['dbpass'];
		 

					
				// Connect to server and select databse.
				// Connect to server and select databse.
$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }		
				
				echo"<br>";
				echo "Fetching Report Type......";
						
				$query=$con->prepare("SELECT * FROM tipopin where NombreEng=?");
				$query->bindValue(1, $type, PDO::PARAM_STR);
				$query_holder = "SELECT * FROM tipopin where NombreEng=?";
				
				$result1 = $query->execute();
				$count1=$query->rowCount();
				echo "<br>";
				echo "query: ".$query_holder;
				echo "<br>";
				echo "count: ".$count1;
				$i=-1;
				if ($count1>1){
					
				}else if ($count1==0){					
					$i=0;					
				}else if ($count1==1){
					$row=$query->fetch(PDO::FETCH_ASSOC);
					$i=$row['Id'];
				}
		       
			    mysql_close($con);
			    return $i;
			   
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
