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


// Connect to server and select databse.
$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }	
 
$isaudio=0;
//if(!empty($_POST)){

//if(isset($_FILES['file']) and !$_FILES['file']['error']){
    //$fname = "11" . ".wav";
	//$IdUsFIXED = 0;
	//$confcode = $IdUsFIXED.md5(date('Ymdgisu'));
	//$new_image_name = 'eML'.$confcode.'.jpg';
	$new_image_name = $_SESSION['MEDID'].'.jpg';
	$filepath="PatientImage/".$new_image_name;
	echo $filepath;
	move_uploaded_file($_FILES["file"]["tmp_name"], $filepath);
	sleep(10);
	//$newURL='patientdetailMED-new.php?IdUsu='.$queId;
	// $newURL='dropzone.php';
	// header('Location: '.$newURL);	
    //move_uploaded_file($_FILES['file']['tmp_name'], "../ext/wav/testes/" . $fname);
//}
    //$data = implode($_POST); //transforms the char array with the blob url to a string
    //$fname = "audio11" . ".wav";
	
	// $fromname='29';
	// $queId = '1165';
	//$fromname=$_POST['idmed'];
	//$queId = $_POST['queId'];
	// $IdUsFIXED=0;			
	// $isaudio=1;
	if(file_exists($filepath))
	 echo "success";
	else
	 echo "failure";
	 
//}
/*else{
/*
$queId = $_GET['queId'];
$fromname=$_GET['from'];



$extension = substr($_FILES["file"]["name"],1+strpos($_FILES["file"]["name"], '.'),3);
echo "File Type:".$extension;


//Changes for the wav files
/*$isaudio=0;
if($extension=='wav'){
$isaudio=1;
}*/
/*
if($extension!="pdf") {
if($isaudio==0)
$extension=pdf;
}



####changes from cloud channel---Starts
	//for ($i = 0; $i < $size; $i++) {
    				//print_r($folderMetadata['contents'][$i]['path']);
					$filename=$_FILES["file"]["name"];    //This is used for storing the filename present in the dropbox area
					//changes for duplicate files.
					//$filepath="Processed\\".time().'_'.$filename;
					$IdUsFIXED=0;
					$confcode = $IdUsFIXED.md5(date('Ymdgisu'));
	 				$new_image_name = 'eML'.$confcode.'.'.$extension;
					$filepath="Packages/".$new_image_name;
					
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
} 
						/*$fileMetadata = $dbxClient->getFile($folderMetadata['contents'][$i]['path'], $f);
						try{
						$dbxClient->move("/".$filename,"/Processed/".$filename);
						}catch(Exception $e){
							echo "<br> Exception Caught:  ".$e."<br>";
							$dbxClient->delete($folderMetadata['contents'][$i]['path']);
						}*/
						//$dbxClient->delete($folderMetadata['contents'][$i]['path']);
	/*					echo "<br>\n"; 
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
    
					

  	
 
 	/*				echo 'INSERCION IdUsFIXED = '.$IdUsFIXED;
					echo "<br>\n"; 
							  
					echo 'INSERCION IdUsFIXED = '.intval($IdUsFIXED);
					echo "<br>\n"; 
					
					/* require("environment_detail.php");
					 $dbhost = $env_var_db['dbhost'];
					 $dbname = $env_var_db['dbname'];
					 $dbuser = $env_var_db['dbuser'];
					 $dbpass = $env_var_db['dbpass'];*/
						
					
					// Connect to server and select databse.
	/*				$link = mysql_connect("$dbhost", "$dbuser", "$dbpass")or die("cannot connect");
		
					mysql_select_db("$dbname")or die("cannot select DB");
					
					if($format){
					 $Isql="INSERT INTO lifepin SET NeedACTION = 1, IdEmail='1', IdUsu=$IdUsu, FechaInput=NOW(), Fecha='$report_date' , FechaEmail = Now() , IdUsFIXED='$IdUsFIXED', IdUsFIXEDNAME='$IdUsFIXEDNAME', IdMedEmail = '$fromname', CANAL=5, ValidationStatus=99, EvRuPunt= 2, Evento=99, Tipo=$typoid, fs=1, checksum='$checksum'";
					 echo "<br>\n"; 	 
					 echo $Isql;
					}else {
					 $Isql="INSERT INTO lifepin SET NeedACTION = 1, IdEmail='1', FechaInput=NOW(), Fecha=NOW() , FechaEmail = Now() , IdUsFIXED='$IdUsFIXED', IdUsFIXEDNAME='$IdUsFIXEDNAME', IdMedEmail = '$fromname', CANAL=5, ValidationStatus=99, EvRuPunt= 2, Evento=99, Tipo=30,checksum='$checksum'";
					 echo "<br>\n"; 
					 echo $Isql;	 
					}
					
					
					echo "<br>\n"; 		
					echo "<br>\n"; 	    
					//$q = mysql_query($Isql) or die(mysql_error());
					$q=mysql_query($Isql, $link);
					
					
					//$q = mysqli::query($Isql);		 
					if($q){
							//echo $Isql;
							echo "Successful: Database Updated";
							echo "<br>\n"; 	
					}else {
																																				
						if(($errorno=mysql_errno($link))==1062){
							
																																		
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
						//$message.='
						//EnviaMail2($EmailDOC, $IdUsFIXED, 'MediBANK Cloud Channel validation system','Duplicate clinical record information alert.', $message, 0, 9999, 'Grace@health2.me', 'Inmers', 9);  		 
	/*					}else {
							
						//LogBLOCK (0, 'Error in record found.', $EmailDOC, '', $IdUsFIXED, '', '', 1); 																											
																																	
						}																									
							
							//echo "Error Updating Database :" . mysql_error();
							echo mysql_errno($link) . ": " . mysql_error($link) . "\n";
							die(mysql_error($link));
							echo "<br>\n"; 	
					}	

					$IdPin = mysql_insert_id();
					echo "IDPIN ".$IdPin;
					//$extension = substr($laststring,1+strpos($laststring, '.'),3); 	//This is not the most efficient way of finding the extension as a dot might be present in the filename
 					/*$laststring=$folderMetadata['contents'][$i]['mime_type'];
 					$extension= substr($laststring,1+strpos($laststring, '/'));*/
 					//echo "EXTENSION : ".$extension;
 	/*				echo "<br>\n"; 
 					echo "SIZE: ";
 					echo $Tamano;
 					echo "<br>\n";
 					/*if($extension!="pdf"&&$extension!="png"&&$extension!="jpg") {
 						$extension="pdf";
 					}*/
	/*				if($extension=="jpeg"){
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
 	/*					echo "<br>\n"; 								
 						
						// GRABACIÓN FÍSICA DEL FICHERO CONTENIDO EN EL ATTACHMENT EN EL SERVIDOR (PACKAGES)

 						$locFile = "Packages\\";
						
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
						
	/*					 if ($deleteError) {
  							  echo 'file delete error';
  						 }
						//fclose($f);
						//fclose($f_new); 		
						if($isaudio){
						
							
						
						
						
						}else{
						$locFileTH = "PackagesTH\\";
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
						
						}
						//reación de un thumbnail desde el PDF  
					  
						$Isql="UPDATE lifepin SET IdEmail='1', RawImage='$new_image_name' , FechaInput=NOW(), ValidationStatus=8 , orig_filename ='$filename' WHERE IdPin='$IdPin'";
						$q = mysql_query($Isql);
						$IdPin = mysql_insert_id();
						// if successfully insert data into database, displays message "Successful". 
						if($q){
							echo "Successful: Database Updated";
							echo "<br>\n"; 	
							echo "REGISTROS CAMBIADOS: ".mysql_affected_rows();
							echo "<br>\n"; 	
							LogMov($IdUsFIXED, $fromname, 'webchannel', '4', 'dB INSERTION', 'PIN INSERTED, Database UPDATED' , $filename, 1);
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
						
						/*$s//target server address or domain name from we wana download file
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
							
				//		}


/*sleep(10);
$newURL='patientdetailMED-new.php?IdUsu='.$queId;
header('Location: '.$newURL);		*/		
 						
						
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
				
				
				$result1 = $query->execute();
				$count1=$query->rowCount();
				echo "<br>";
				echo "query: ".$result1;
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
 function getPatientName($patientID)	{
				
		
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
				
				$query=$con->prepare("SELECT * FROM usuarios where Identif=?");
				$query->bindValue(1, $patientID, PDO::PARAM_INT);
				
				
				$result1 = $query->execute();
				$count1=$query->rowCount();
				echo "<br>";
				echo "query: ".$result1;
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
				$query=$con->prepare("SELECT * FROM tipopin where abreviation='$type'");
				$query->bindValue(1, $type, PDO::PARAM_STR);
				
				
				$result1 = $query->execute();
				$count1=$query->rowCount();
				echo "<br>";
				echo "query: ".$result1;
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
		
####changes from cloud channel---Ends

?>