<?php
 session_start();
 
	set_time_limit(120);
	define('INCLUDE_CHECK',1);
	require "logger.php";
 require("environment_detail.php");
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];

$tbl_name="lifepin"; // Table name
$queId = $_GET['queId'];
$fromname=$_GET['from'];
$idcreator=$_SESSION['MEDID'];


//Below lines commented by Pallab
/*
$link = mysql_connect("$dbhost", "$dbuser", "$dbpass")or die("cannot connect");
mysql_select_db("$dbname")or die("cannot select DB");
*/

// Connect to server and select databse.
 $con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }	
 
 //Below lines commented by Pallab
/*$enc_result = mysql_query("select pass from encryption_pass where id = (select max(id) from encryption_pass)");
			$row_enc = mysql_fetch_array($enc_result);
			$enc_pass=$row_enc['pass'];
 */

//Start of new code added by Palla
$enc_result = $con->prepare("select pass from encryption_pass where id = (select max(id) from encryption_pass)");
$enc_result->execute();
$row_enc = $enc_result->fetch(PDO::FETCH_ASSOC);
$enc_pass = $row_enc['pass'];
//End of new code added by Pallab

$extension = substr($_FILES["file"]["name"],1+strpos($_FILES["file"]["name"], '.'),3);
echo "File Type:".$extension;
/*
if($extension!="pdf")
	$extension=pdf;
*/
	


####changes from cloud channel---Starts
	//for ($i = 0; $i < $size; $i++) {
    				//print_r($folderMetadata['contents'][$i]['path']);
					$filename=$_FILES["file"]["name"];    //This is used for storing the filename present in the dropbox area
					//changes for duplicate files.
					//$filepath="Processed\\".time().'_'.$filename;
					$IdUsFIXED=0;
					$confcode = $IdUsFIXED.md5(date('Ymdgisu'));
	 				$new_image_name = 'eML'.$confcode.'.'.$extension;
					$filepath="Packages_Encrypted/".$new_image_name;
					
					$Tamano=$_FILES["file"]["size"];
				
				    //Code for parsing the filename and extracting information from filestrings array
				    //in order of [1]&[2] Patient first& Last name,[3] Date,[4] Type,[5] Last Part
				    
				    //Check for record duplication
				    
				    //echo md5_file($filename);
				    
						$resultado = move_uploaded_file($_FILES["file"]["tmp_name"], $filepath);
						//$f = fopen($filepath, "w+b" );
						if ($resultado)
							{
							//echo $new_image_name;
							// Resize it
							//GenerateThumbnail("Packages/".$new_image_name,"PackagesTH/".$new_image_name,400,500); 

							echo "File Upload OK: and Filename parsing Started...".$filepath;
							//echo "PinImageSet/".$new_image_name;
							//return;

							}
						else{
							echo "***** ERROR *****";
							die();
						}
						/*$fileMetadata = $dbxClient->getFile($folderMetadata['contents'][$i]['path'], $f);
						try{
						$dbxClient->move("/".$filename,"/Processed/".$filename);
						}catch(Exception $e){
							echo "<br> Exception Caught:  ".$e."<br>";
							$dbxClient->delete($folderMetadata['contents'][$i]['path']);
						}*/
						//$dbxClient->delete($folderMetadata['contents'][$i]['path']);
						echo "<br>\n"; 
						echo "Checksum: "; 
						$checksum=md5_file($filepath);
						echo $checksum; 
						echo "<br>\n"; 
					 
				    
					$filestrings=explode("$", $filename);     //The components are separated by $
					echo "<br>\n"; 	 
					echo "filename :";
					print_r($filestrings);
					echo "<br>\n";
					echo "File Type ";
					$extension = substr($_FILES["file"]["name"],1+strpos($_FILES["file"]["name"], '.'),3);
 					echo "EXTENSION : ".$extension;
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
					}else{
						echo "<br>\n"; 	 
						echo "Fetching patient name";
						echo "<br>\n"; 
						$PatientDetails=getPatientName($queId);
						$IdUsu=$PatientDetails['Identif'];
						if($IdUsu==null)
							$IdUsu=0;
						$IdUsFIXED=$PatientDetails['IdUsFIXED'];
						$IdUsFIXEDNAME=$PatientDetails['IdUsFIXEDNAME'];
						
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
					
                    $q;				
                    if($format)
                    {
					 $Isql="INSERT INTO lifepin SET NeedACTION = 1, IdEmail='1', IdUsu=?, FechaInput=NOW(), Fecha=? , FechaEmail = Now() , IdUsFIXED=?, IdUsFIXEDNAME=?, IdMedEmail = ?, CANAL=5, ValidationStatus=99, EvRuPunt= 2, Evento=99, Tipo=?, fs=1, checksum=?, idcreator=?";
					 echo "<br>\n"; 	 
					 echo $Isql;
                     $q = $con->prepare($Isql);
                     $q->bindValue(1,$queId,PDO::PARAM_INT);
                     $q->bindValue(2,$report_date,PDO::PARAM_STR);
                     $q->bindValue(3,$IdUsFIXED,PDO::PARAM_INT);
                     $q->bindValue(4,$IdUsFIXEDNAME,PDO::PARAM_STR);
                     $q->bindValue(5,$fromname,PDO::PARAM_STR);
                     $q->bindValue(6,$typoid,PDO::PARAM_INT);
                     $q->bindValue(7,$checksum,PDO::PARAM_STR);
                     $q->bindValue(8,$dicreator,PDO::PARAM_INT);
                      
					}else {
					 $Isql="INSERT INTO lifepin SET NeedACTION = 1, IdEmail='1', IdUsu=?, FechaInput=NOW(), Fecha=NOW() , FechaEmail = Now() , IdUsFIXED=?, IdUsFIXEDNAME=?, IdMedEmail = ?, CANAL=5, ValidationStatus=99, EvRuPunt= 2, Evento=99, Tipo=30,checksum=? , idcreator=?";
					 echo "<br>\n"; 
					 echo $Isql;	
                       
                     $q = $con->prepare($Isql);
                     $q->bindValue(1,$queId,PDO::PARAM_INT);
                     $q->bindValue(2,$IdUsFIXED,PDO::PARAM_INT);
                     $q->bindValue(3,$IdUsFIXEDNAME,PDO::PARAM_STR);
                     $q->bindValue(4,$fromname,PDO::PARAM_STR);
                     $q->bindValue(5,$checksum,PDO::PARAM_STR);
                     $q->bindValue(6,$dicreator,PDO::PARAM_INT);
                     
					}
					
					
					echo "<br>\n"; 		
					echo "<br>\n"; 	    
					//$q = mysql_query($Isql) or die(mysql_error());
					$q=$q->execute();
					
					
					//$q = mysqli::query($Isql);		 
					if($q){
							//echo $Isql;
							echo "Successful: Database Updated";
							echo "<br>\n"; 	
					}else {
																																				
						if(($errorno=$q->errorCode())==1062){
							
																																		
						/*$message = 'Dear ';
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
						$message.= '. Please contact support for futher information.';*/
						//$confirm_code=md5(uniqid(rand()));
						//$message.='http://www.health2.me/confirmationDoc.php?passkey=$confirm_code';

						//EnviaMail2($EmailDOC, $IdUsFIXED, 'MediBANK Cloud Channel validation system','Duplicate clinical record information alert.', $message, 0, 9999, 'Grace@health2.me', 'Inmers', 9);  		 
						}else {
							
						//LogBLOCK (0, 'Error in record found.', $EmailDOC, '', $IdUsFIXED, '', '', 1); 																											
																																	
						}																									
							
							//echo "Error Updating Database :" . mysql_error();
							echo $q->errorCode() . ": " . $q->errorInfo() . "\n";
							die($q->errorInfo());
							echo "<br>\n"; 	
					}	

					$IdPin = $con->lastInsertId(); //Removed mysql_insert_id
		   
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
					if($extension=="jpeg"){
 						$extension="jpg";
						}
 					if ($Tamano > 20000000){
	 	    			echo "SIZE EXCEEDS 20 Mb. LIMIT";
	 	    			echo "<br>\n"; 
 					}else{
 						    	
						// GRABACIÓN DEL ATTACHMENT EN EL DIRECTORIO LOCAL PARA LUEGO HACER EL FTP
	 					/*$confcode = $IdUsFIXED.md5(date('Ymdgisu'));
	 					$new_image_name = 'eML'.$confcode.'.'.$extension;			// ASIGNACIÓN DE UN NOMBRE DE FICHERO ÚNICO

	 					echo " NEW FILE : ".$new_image_name;*/
 						echo "<br>\n"; 								
 						
						// GRABACIÓN FÍSICA DEL FICHERO CONTENIDO EN EL ATTACHMENT EN EL SERVIDOR (PACKAGES)

 						$locFile = "Packages_Encrypted\\";
						
						$deleteError = 0;
						
						//$f_new = fopen( $locFile.$new_image_name, "w+b" );
						//($filepath, $locFile.$new_image_name)
						/*if (!rename($filepath, $locFile.$new_image_name)){
								echo "Failed to Create File in the Package area!";
						}else {
								//unlink ($filename);
								$lines = array();




								//exec("DEL /F/Q \"$filepath\"", $lines, $deleteError); 		//Added for windows server as unlink command has some problem with the permissions.
								echo "File created in Package folder successfully!!";
						}*/
						
						 if ($deleteError) {
  							  echo 'file delete error';
  						 }
						//fclose($f);
						//fclose($f_new); 		
								
						$locFileTH = "PackagesTH_Encrypted\\";
						$path="C:\\PROGRA~2\\ImageMagick-6.8.1-Q16\\";
						
						//Changes for handling jpg files
						if($extension!="jpg") {
						$new_image_nameTH = 'eML'.$confcode.'.png';	
						}else {
						$new_image_nameTH = 'eML'.$confcode.'.jpg';
						}
														
					    $cadenaConvert = $path.'convert "'.$locFile.$new_image_name.'[0]" -colorspace RGB -geometry 200 "'.$locFileTH.$new_image_nameTH.'" 2>&1';
																																	
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
    
    shell_exec("echo '".$enc_pass."' | openssl aes-256-cbc -pass stdin -salt -in Packages_Encrypted/".$new_image_name." -out temp/".$new_image_name);
    shell_exec("rm Packages_Encrypted/".$new_image_name);
    shell_exec("cp temp/".$new_image_name." Packages_Encrypted/");
    shell_exec("rm temp/".$new_image_name);       //Encrypt the report (ankit)
						
						
						//reación de un thumbnail desde el PDF  
					    $ActualIdPin = $IdPin;
						$Isql="UPDATE lifepin SET IdEmail='1', RawImage=? , FechaInput=NOW(), ValidationStatus=8 , orig_filename =? WHERE IdPin=?";
                        //Below SQl were commented by Pallab
						/*$q = mysql_query($Isql);
						$IdPin = mysql_insert_id();
                        */
                        
                        //Below SQL queries were added by Pallab
                        //Start of new code added by Pallab
                        $q = $con->prepare($Isql);
                        $q->bindValue(1,$new_image_name,PDO::PARAM_STR);
                        $q->bindValue(2,$filename,PDO::PARAM_INT);
                        $q->bindValue(3,$IdPin,PDO::PARAM_INT);
                        $q->execute();
                        $IdPin = $q->lastInsertId();
                        //End of new code added by Pallab
                        
						// if successfully insert data into database, displays message "Successful". 
						if($q){
							echo "Successful: Database Updated";
							echo "<br>\n"; 	
							echo "REGISTROS CAMBIADOS: ".mysql_affected_rows();
							echo "<br>\n"; 	
							LogMov($IdUsFIXED, $fromname, 'webchannel', '4', 'dB INSERTION', 'PIN INSERTED, Database UPDATED' , $filename, 1);
                            //NEWLOG (Health2Me 2013) Log that report has been uploaded
                            $IdPin=$IdPin;
                            $content = "Report Uploaded";
                            $VIEWIdUser=$queId;
                            $VIEWIdMed=$queId;
                            $ip=$_SERVER['REMOTE_ADDR'];
                            $MEDIO=1;
                            //Below SQL query commented by Pallab
                            //$q = mysql_query("INSERT INTO bpinview  SET  IDPIN ='$ActualIdPin', Content='$content', DateTimeSTAMP = NOW(), VIEWIdUser = '$VIEWIdUser', VIEWIdMed = '$VIEWIdMed', VIEWIP = '$ip', MEDIO = '$MEDIO' ");
                            //Below SQL queries were added by Pallab
                            $q =$con->prepare("INSERT INTO bpinview  SET  IDPIN =?, Content=?, DateTimeSTAMP = NOW(), VIEWIdUser = ?, VIEWIdMed = ?, VIEWIP = ?, MEDIO = ?");
                            $q->bindValue(1,$ActualIdPin,PDO:PARAM_INT);
                            $q->bindValue(2,$content,PDO:PARAM_STR);
                            $q->bindValue(3,$VIEWIdUser,PDO:PARAM_INT);
                            $q->bindValue(4,$VIEWIdMed,PDO:PARAM_INT);
                            $q->bindValue(5,$ip,PDO:PARAM_STR);
                            $q->bindValue(6,$MEDIO,PDO:PARAM_INT);
                            $q->execute();
                            //LogBLOCKAMP ($IdPin, $content, $VIEWIdUser, $VIEWIdMed, $ip, $MEDIO);

				    	}
						else{
							echo "Error Updating Database ********";
							echo "<br>\n"; 	 
							LogMov($IdUsFIXED, $fromname, 'webchannel', '4', 'dB INSERTION', 'Error Updating Database ********' , $filename, 1);
						}
							
							
							LogMov($IdUsFIXED, $fromname, 'webchannel', '2', 'File Local Save', $new_image_name, $filename, 1);
							
							     
 						// FTP TRANSFER *****************************************************************************************	// TRANSFERENCIA DEL ARCHIVO AL SERVIDOR QUE TIENE LA BASE DE DATOS  (PinEXT)
						/*$server = "monimed.com"; //target server address or domain name from we wana download file
						$user = "moni8484"; //username on target server
						$pass = "ardiLLA98@"; //password on target server for Ftp*/
						
						//This is added for testing in the development environments=
						
						/*
						$user = "ITGroup"; //username on target server
						$pass = "InmersIT";*/
						
						/*$server = $env_var_ftp['server']; //target server address or domain name from we wana download file
						$user = $env_var_ftp['user']; //username on target server
						$pass = $env_var_ftp['pass']; //password on target server for Ftp
						
						$local_file = './Packages/'.$new_image_name;
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

								//ftp_close($connection); 								
							
						}

sleep(10);
$newURL='patientdetailMED-newUSER.php?IdUsu='.$queId;
header('Location: '.$newURL);				
 						
						
 					//}				   
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

function getPatientDetails($firstname,$lastname)	{
				
		
			 require("environment_detail.php");
			 $dbhost = $env_var_db['dbhost'];
			 $dbname = $env_var_db['dbname'];
			 $dbuser = $env_var_db['dbuser'];
			 $dbpass = $env_var_db['dbpass'];
	

				// Connect to server and select databse.
				//Below SQL connection commented by Pallab
                /*
                $link1 = mysql_connect("$dbhost", "$dbuser", "$dbpass")or die("cannot connect");
				mysql_select_db("$dbname")or die("cannot select DB");
                */
				
                // Connect to server and select databse.
 $con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }	
				echo"<br>";
				echo "Fetching Patient ID details......";
				
				//Below SQl queries were commented by Pallab
                /*
                $query="SELECT * FROM usuarios where Name='$firstname' and Surname='$lastname'";
				$result1 = mysql_query($query);
				$count1=mysql_num_rows($result1);
                */
                
                //Start of new SQl code added  by Pallab
                $query = "SELECT * FROM usuarios where Name=? and Surname=?";
                $result1 = $con->prepare($query);
                $result1->bindValue(1,$firstname,PDO::PARAM_STR);
                $result1->bindValue(2,$lastname,PDO::PARAM_STR);
                $result1->execute();
                $count1 = $result1->rowCount();
                //End of new SQL code added by Pallab
				echo "<br>";
				echo "query: ".$query;
				echo "<br>";
				echo "count: ".$count1;
				$i=-1;
				if ($count1>1){
					
				}else if ($count1==0){					
					$i=0;					
				}else if ($count1==1){
					//Below SQL query commented by Pallab
                    //$row=mysql_fetch_assoc($result1);
                    
                    $row = $result1->fetch(PDO::FETCH_ASSOC);
					//$i=$row['Identif'];
					return $row;
				}
		       
			    //mysql_close($link1); Commented by Pallab
                $con = null;
			    return $i;
			   
	}
 function getPatientName($patientID)	{
				
		
			 require("environment_detail.php");
			 $dbhost = $env_var_db['dbhost'];
			 $dbname = $env_var_db['dbname'];
			 $dbuser = $env_var_db['dbuser'];
			 $dbpass = $env_var_db['dbpass'];

				// Connect to server and select databse.
				//Below SQL lines were commented by Pallab
               /* 
               $link1 = mysql_connect("$dbhost", "$dbuser", "$dbpass")or die("cannot connect");
				mysql_select_db("$dbname")or die("cannot select DB");
                */
     
                // Connect to server and select databse.
 $con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }	
				
				echo"<br>";
				echo "Fetching Patient ID details......";
				
				//Below SQL lines were commented by Pallab
                /*
                $query="SELECT * FROM usuarios where Identif='$patientID'";
				$result1 = mysql_query($query);
				$count1=mysql_num_rows($result1);
                */
     
                $query = "SELECT * FROM usuarios where Identif=?";
                $result1 = $con->prepare($query);
                $result1->bindValue(1,$patientID,PDO::PARAM_INT);
                $result1->execute();
				$count1 = $result1->rowCount();
				echo "<br>";
				echo "query: ".$query;
				echo "<br>";
				echo "count: ".$count1;
				$i=-1;
				if ($count1>1){
					
				}else if ($count1==0){					
					$i=0;					
				}else if ($count1==1){
					//Below SQL query commented by Pallab
                   // $row=mysql_fetch_assoc($result1);
                    
                    $row = $result1->fetch(PDO::FETCH_ASSOC);
					//$i=$row['Identif'];
					return $row;
				}
		       
			    //mysql_close($link1); Commented by Pallab
                $con = null;
			    return $i;
			   
	}	
	
	function getReportType($type)	{
				require("environment_detail.php");			
			 $dbhost = $env_var_db['dbhost'];
			 $dbname = $env_var_db['dbname'];
			 $dbuser = $env_var_db['dbuser'];
			 $dbpass = $env_var_db['dbpass'];

	
					
				// Connect to server and select databse.
				/*
                $link1 = mysql_connect("$dbhost", "$dbuser", "$dbpass")or die("cannot connect");
				mysql_select_db("$dbname")or die("cannot select DB");
                */
        
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
				//Below SQL queries were commented by Pallab
                /*
                $query="SELECT * FROM tipopin where abreviation='$type'";
				$result1 = mysql_query($query);
				$count1=mysql_num_rows($result1);
                */
        
                //Start of new code added by Pallab
                $query = "SELECT * FROM tipopin where abreviation=?";
                $result1 = $con->prepare($query);
                $result1->bindValue(1,$type,PDO::PARAM_STR);
                $result1->execute();
				$count1 = $result1->rowCount();
                //End of new code added by Pallab
				echo "<br>";
				echo "query: ".$query;
				echo "<br>";
				echo "count: ".$count1;
				$i=-1;
				if ($count1>1){
					
				}else if ($count1==0){					
					$i=0;					
				}else if ($count1==1){
					//below SQL query commented by Pallab
                    //$row=mysql_fetch_assoc($result1);
                    $row = $result1->fetch(PDO::FETCH_ASSOC);
					$i=$row['Id'];
				}
		       
			    //Commented by Pallab mysql_close($link1);
                $con = null;
			    return $i;
			   
	}	
		
####changes from cloud channel---Ends

?>
