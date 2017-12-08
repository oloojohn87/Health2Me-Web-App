<?php
 session_start();
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
	set_time_limit(12000000000);
	define('INCLUDE_CHECK',1);
	require "logger.php";
require("NotificationClass.php");
 require("environment_detail.php");
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];
 $domain = $env_var_db['hardcode'];
 $local = $env_var_db['hardcode'];

$path_for_ffmpeg = "ffmpeg\\bin\\"; 
 
// Connect to server and select databse.
		$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
		
		if (!$con){
			die('Could not connect: ' . mysql_error());
			}
 
 
$tbl_name="lifepin"; // Table name
$queId = $_GET['queId'];
$fromname=$_GET['from'];
$hide_from_member = 0;

$result = $con->prepare("SELECT * FROM usuarios where Identif=?");
$result->bindValue(1, $queId, PDO::PARAM_INT);
$result->execute();
	
$row = $result->fetch(PDO::FETCH_ASSOC);

if($row['GrantAccess'] != 'HTI-SPA' && $row['GrantAccess'] != 'HTI-COL' && $row['GrantAccess'] != 'HTI-RIVA' && $row['GrantAccess'] != 'HTI-24X7'){
	if($_SESSION['BOTHID'] == $queId){
		$hide_from_member = 0;
	}else{
		$hide_from_member = 1;
	}
}

$reportdate=$_POST['reportdate'];

$reporttype=intval($_POST['reporttype']);

file_put_contents("upload_test.txt", $queId, FILE_APPEND);
file_put_contents("upload_test.txt", $fromname, FILE_APPEND);
 
$notifications = new Notifications();

$enc_result = $con->prepare("select pass from encryption_pass where id = (select max(id) from encryption_pass)");
$enc_result->execute();

$row_enc = $enc_result->fetch(PDO::FETCH_ASSOC);

$enc_pass = $row_enc['pass'];

$newlog_q = '';
 
 

//$extension = pathinfo(strtolower($_FILES["file"]["name"]), PATHINFO_EXTENSION);
$filename_splited = explode('.', $_FILES['file']['name']);
$extension = strtolower(end($filename_splited));

// Start of new code added by Pallab for handling jpeg files extension
if($extension == 'jpeg')
    $extension = 'jpg';
//End of new code added by Pallab for handling jpeg files extension


//echo "File Type:".$extension;
file_put_contents("upload_test.txt", "File Type:".$extension, FILE_APPEND);
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

                    try{ if ($Tamano > 1024*1024*25) 
                            throw new RuntimeException("SizeError"); 
                    } catch (RuntimeException $r) { 
                        echo 'SizeError'; 
                        die();  
                    }
				
				    //Code for parsing the filename and extracting information from filestrings array
				    //in order of [1]&[2] Patient first& Last name,[3] Date,[4] Type,[5] Last Part
				    
				    //Check for record duplication
				    $new_image_name_doc='';
                    $isaDocument=0;
				    //echo md5_file($filename);
				        if(strtolower($extension)=="doc"){
                            
                            
                            $ext = pathinfo($filename, PATHINFO_EXTENSION);
                            //$new_image_name = 'eML'.$confcode.'.docx';
                            $new_image_name_doc = 'eML'.$confcode.'.'.$ext;
                            $docpath="Document_Processing/docs/".$new_image_name_doc;
                            $resultado = move_uploaded_file($_FILES["file"]["tmp_name"], $docpath);
                            $isaDocument=1;
                        }else{
                            $extensions_list = array('png', 'jpg', 'tiff', 'gif', 'mov', 'pdf');
                            
                            try { if(!in_array(strtolower($extension), $extensions_list)) throw new RuntimeException("ExtError"); }
                            catch (RuntimeException $e) { 
                                echo 'ExtError';
                                die();  
                            }
                            finally { $resultado = move_uploaded_file($_FILES["file"]["tmp_name"], $filepath); }
                        }

						//$f = fopen($filepath, "w+b" );
						if (isset($resultado))
							{
							//echo $new_image_name;
							// Resize it
							//GenerateThumbnail("Packages/".$new_image_name,"PackagesTH/".$new_image_name,400,500); 

							echo "File Upload OK: and Filename parsing Started...".$filepath;
							file_put_contents("upload_test.txt", "File Upload OK: and Filename parsing Started...".$filepath, FILE_APPEND);		
							//echo "PinImageSet/".$new_image_name;
							//return;

							}
						else{
							//echo "***** ERROR *****";
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
                    //COMMENTED DUE TO DUPLICATE
					//$extension = substr($_FILES["file"]["name"],1+strpos($_FILES["file"]["name"], '.'),3);
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
						$PatientDetails=getPatientDetails($con,$firstname,$lastname);
						$IdUsu=$PatientDetails['Identif'];
						if($IdUsu==null)
							$IdUsu=0;
						$IdUsFIXED=$PatientDetails['IdUsFIXED'];
						$IdUsFIXEDNAME=$PatientDetails['IdUsFIXEDNAME'];
						$typoid=getReportType($con, $type);
					}else{
						echo "<br>\n"; 	 
						echo "Fetching patient name";
						echo "<br>\n"; 
						$PatientDetails=getPatientName($con, $queId);
						$IdUsu=$PatientDetails['Identif'];
						if($IdUsu==null)
							$IdUsu=0;
						$IdUsFIXED=$PatientDetails['IdUsFIXED'];
						$IdUsFIXEDNAME=$PatientDetails['IdUsFIXEDNAME'];
						
					}
					//print_r($fileMetadata);
	
					//echo " ". $folderMetadata;
					echo "folderMetadata end";
  	
 
 					echo 'INSERCION IdUsFIXED = '.$IdUsFIXED;
					echo "<br>\n"; 
							  
					echo 'INSERCION IdUsFIXED = '.intval($IdUsFIXED);
					echo "<br>\n"; 
					
				
					
					// Connect to server and select databse.

					if($format){
					 $Isql="INSERT INTO lifepin SET NeedACTION = 1, IdEmail='1', IdUsu=$IdUsu, FechaInput=NOW(), Fecha='$reportdate' , FechaEmail = Now() , IdUsFIXED='$IdUsFIXED', IdUsFIXEDNAME='$IdUsFIXEDNAME', IdMedEmail = '$fromname', CANAL=5, ValidationStatus=99, EvRuPunt= 2, Evento=99, Tipo=$reporttype, fs=1, checksum='$checksum', hide_from_member='$hide_from_member', idcreator = 1";
					 echo "<br>\n"; 	 
					 //KYLEecho $Isql;
					}else {
					 $Isql="INSERT INTO lifepin SET NeedACTION = 1, IdEmail='1', FechaInput=NOW(), Fecha='$reportdate' , FechaEmail = Now() , IdUsFIXED='$IdUsFIXED', IdUsFIXEDNAME='$IdUsFIXEDNAME', IdMedEmail = '$fromname', CANAL=5, ValidationStatus=99, EvRuPunt= 2, Evento=99, Tipo=$reporttype,checksum='$checksum', hide_from_member='$hide_from_member', idcreator = 1";
					 echo "<br>\n"; 
					 //KYLEecho $Isql;	 
					}

                    $foundDocs = array();
                    
                    //IF THE PATIENT HAS BEEN REFERRED, NOTIFY THIS OCCURRENCE TO THE DOCTORS WHO REFERRED THIS PATIENT
                    $findRefDocs = $con->prepare('SELECT IdMED, IdMED2 FROM doctorslinkdoctors WHERE IdPac = ? AND (IdMED = ? OR IdMED2 = ?) UNION SELECT IdMED, null AS IdMED2 FROM doctorslinkusers WHERE IdUs = ?');
                    $findRefDocs->bindValue(1, $IdUsu, PDO::PARAM_INT);
                    $findRefDocs->bindValue(2, $_SESSION['MEDID'], PDO::PARAM_INT);
                    $findRefDocs->bindValue(3, $_SESSION['MEDID'], PDO::PARAM_INT);
                    $findRefDocs->bindValue(4, $IdUsu, PDO::PARAM_INT);
                    $findRefDocs->execute();
                    
                    while($RefDocRow = $findRefDocs->fetch(PDO::FETCH_ASSOC)) {
                        if(!in_array($RefDocRow['IdMED'], $foundDocs)) array_push($foundDocs, $RefDocRow['IdMED']);
                        else if($RefDocRow['IdMED2'] !== NULL && !in_array($RefDocRow['IdMED2'], $foundDocs)) array_push($foundDocs, $RefDocRow['IdMED2']);
                    }
                    
                    // doctor
                    if($_SESSION['BOTHID'] == $_SESSION['MEDID'])
                    {
                        //to the patient
                        $notifications->add('REPUPL', $_SESSION['MEDID'], true, $IdUsu, false, null);
                        //to the related doctors
                        foreach($foundDocs as $foundDoc) 
							if($foundDoc !== NULL && $foundDoc != $_SESSION['MEDID']){
								$notifications->add('REPUPL', $_SESSION['MEDID'], true, $foundDoc, true, $IdUsu);
							}
                    }
                    //below means patient himself uploading
                    elseif($hide_from_member == 0) 
                    {
                        //to the related doctors
                        foreach($foundDocs as $foundDoc)
                            if($foundDoc != null){
                                $notifications->add('REPUPL', $IdUsu, false, $foundDoc, true, null);
                        }
                    }
					
					
					echo "<br>\n"; 		
					echo "<br>\n"; 	    
					//$q = mysql_query($Isql) or die(mysql_error());
					$q = $con->query($Isql);
                    $IdPin_new = $con->lastInsertId();
					
					
					//$q = mysqli::query($Isql);		 
					if($q){
							//echo $Isql;
							echo "Successful: Database Updated";
							echo "<br>\n";
                            //$IdMed_new = $_SESSION['MEDID'];
                            $IdMed_new =$_SESSION['BOTHID'];
                            $ip_new = $_SERVER['REMOTE_ADDR'];
                            $newlog_q = "INSERT INTO bpinview SET IdPIN=".$IdPin_new.", Content='Report Uploaded', DateTimeSTAMP=NOW(), VIEWIdUser=".$IdUsu.", VIEWIdMed=".$IdMed_new.", VIEWIP='".$ip_new."', MEDIO=0";
					}else {
																																				
						/*if(($errorno=mysql_errno($link))==1062){
							
																																		
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
						//$message.='http:/php?passkey=$confirm_code';

						//EnviaMail2($EmailDOC, $IdUsFIXED, 'MediBANK Cloud Channel validation system','Duplicate clinical record information alert.', $message, 0, 9999, 'Grace@health2.me', 'Inmers', 9);  		 
						}else {
							
						//LogBLOCK (0, 'Error in record found.', $EmailDOC, '', $IdUsFIXED, '', '', 1); 																											
																																	
						}																									
							
							//echo "Error Updating Database :" . mysql_error();
							echo mysql_errno($link) . ": " . mysql_error($link) . "\n";
							die(mysql_error($link));
							echo "<br>\n"; 	
					}	

					
		   
					//$extension = substr($laststring,1+strpos($laststring, '.'),3); 	//This is not the most efficient way of finding the extension as a dot might be present in the filename
 					$laststring=$folderMetadata['contents'][$i]['mime_type'];
 					$extension= substr($laststring,1+strpos($laststring, '/'));
 					//echo "EXTENSION : ".$extension;
 					echo "<br>\n"; 
 					//echo "SIZE: ";
 					echo $Tamano;
 					echo "<br>\n";
 					if($extension!="pdf"&&$extension!="png"&&$extension!="jpg") {
 						$extension="pdf";*/
 					}
                    $IdPin = $con->lastInsertId();
                        
                        
                        if($extension=="doc" || $isaDocument==1){
                            
                            $query="INSERT INTO pending_documents SET idpin=$IdPin, rawimage='$new_image_name_doc'";
				    
					        $q=$con->query($query);
                            
                            if($q){
							//echo $Isql;
							LogMov($IdUsFIXED, $fromname, 'webchannel', '4', 'Doc Processing Pending', 'PIN INSERTED, Database UPDATED' , $filename, 1);
                            }
                            
                            $Isql="UPDATE lifepin SET IdEmail='1', FechaInput=NOW(), ValidationStatus=8 , orig_filename ='$filename',fs=0,idusu=".$queId.",idcreator=".$_SESSION['BOTHID'].", CreatorType = ".($_SESSION['BOTHID'] == $_SESSION['UserID'] ? 0 : 1).", IdMed = 0 WHERE IdPin='$IdPin'";
						    $q = $con->query($Isql);

						    chdir("Document_Processing");
						    echo getcwd();
						    $output = shell_exec('sh mainprogram.sh') or die("bash didn't work");
						   	chdir("..");
						   	echo getcwd();
                        
                        }else if ($isaDocument==0) {
                            
 						    	
						// GRABACIÓN DEL ATTACHMENT EN EL DIRECTORIO LOCAL PARA LUEGO HACER EL FTP
	 					/*$confcode = $IdUsFIXED.md5(date('Ymdgisu'));
	 					$new_image_name = 'eML'.$confcode.'.'.$extension;			// ASIGNACIÓN DE UN NOMBRE DE FICHERO ÚNICO

	 					echo " NEW FILE : ".$new_image_name;*/
 						echo "<br>\n"; 								
 						
						// GRABACIÓN FÍSICA DEL FICHERO CONTENIDO EN EL ATTACHMENT EN EL SERVIDOR (PACKAGES)

 						$locFile = "Packages_Encrypted";
						$ds = DIRECTORY_SEPARATOR; 
						$deleteError = 0;
						
						
						 if ($deleteError) {
  							  echo 'file delete error';
  						 }
						//fclose($f);
						//fclose($f_new); 						
								
						$locFileTH = "PackagesTH_Encrypted";
						$path="/usr/lib/x86_64-linux-gnu/ImageMagick-6.7.7";
						
						//Changes for handling jpg files
						if($extension=="jpg") {
                            $new_image_nameTH = 'eML'.$confcode.'.jpg';	
						}elseif($extension=="gif") {
                            $new_image_nameTH = 'eML'.$confcode.'.gif';	
						}else {
                            $new_image_nameTH = 'eML'.$confcode.'.png';
						}
							

						if($extension=="MOV") //for video use this command
						{
							$path = 'ffmpeg';  //path of ffmpeg
							$cadenaConvert = 'ffmpeg -i '.$locFile.$ds.$new_image_name.' -f image2 -t 0.001 -ss 3 '. $locFileTH.$ds.$new_image_nameTH;
							
						}
						else   //use this command for other file types
						{
							$path="/usr/lib/x86_64-linux-gnu/ImageMagick-6.7.7";  //path of ImageMagick
							$cadenaConvert = 'convert "'.$locFile.$ds.$new_image_name.'[0]" -colorspace RGB -geometry 200 "'.$locFileTH.$ds.$new_image_nameTH.'" 2>&1';
							//error_log($cadenaConvert, 3, "/var/log/apache2/error.log");
						}

						
					    //$cadenaConvert = $path.'convert "'.$locFile.$new_image_name.'[0]" -colorspace RGB -geometry 200 "'.$locFileTH.$new_image_nameTH.'" 2>&1';
																																	
						echo "<br>\n"; 
						echo "Thumbnail: ";
						echo $cadenaConvert;
						echo "<br>\n"; 
						file_put_contents("upload_test.txt",$cadenaConvert, FILE_APPEND);
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
                        shell_exec("rm temp/".$new_image_name);        //Encrypt the report (ankit)
						//file_put_contents("upload_test.txt",'Encrypt.bat Packages_Encrypted '.$new_image_name.' '.$enc_pass, FILE_APPEND);
						
						//reación de un thumbnail desde el PDF  
					  

						$Isql="UPDATE lifepin SET IdEmail='1', RawImage='$new_image_name' , FechaInput=NOW(), ValidationStatus=8 , orig_filename ='$filename',fs=1,idusu=".$queId.",idcreator=".$_SESSION['BOTHID'].", CreatorType = ".($_SESSION['BOTHID'] == $_SESSION['UserID'] ? 0 : 1).", IdMed = 0 WHERE IdPin='$IdPin'";
						$q =$con->query($Isql);
						$IdPin = $con->lastInsertId();

						// if successfully insert data into database, displays message "Successful". 
						if($q){
							echo "Successful: Database Updated";
							echo "<br>\n"; 	
							//echo "REGISTROS CAMBIADOS: ".mysql_affected_rows();
							echo "<br>\n"; 	
							LogMov($IdUsFIXED, $fromname, 'webchannel', '4', 'dB INSERTION', 'PIN INSERTED, Database UPDATED' , $filename, 1);
				    	}
						else{
							echo "Error Updating Database ********";
							echo "<br>\n"; 	 
							LogMov($IdUsFIXED, $fromname, 'webchannel', '4', 'dB INSERTION', 'Error Updating Database ********' , $filename, 1);
						}
							
							
							LogMov($IdUsFIXED, $fromname, 'webchannel', '2', 'File Local Save', $new_image_name, $filename, 1);
							
							     
 							
						}     //End of document type check if condition


if(strlen($newlog_q) > 0)
{
    $new_q = $con->query($newlog_q);
}

sleep(10);
//$newURL='patientdetailMED-new.php?IdUsu='.$queId;
//header('Location: '.$newURL);				
 						
                  
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

function getPatientDetails($con, $firstname,$lastname)	{
	
				// Connect to server and select databse.
				
				echo"<br>";
				echo "Fetching Patient ID details......";
				
				$query="SELECT * FROM usuarios where Name='$firstname' and Surname='$lastname'";
				$result1 = $con->query($query);
				$count1=$result1->rowCount();
				/*echo "<br>";
				echo "query: ".$query;
				echo "<br>";
				echo "count: ".$count1;*/
				$i=-1;
				if ($count1>1){
					
				}else if ($count1==0){					
					$i=0;					
				}else if ($count1==1){
					$row=$result1->fetch(PDO::FETCH_ASSOC);
					//$i=$row['Identif'];
					return $row;
				}
		       
			    return $i;
			   
	}
 function getPatientName($con, $patientID)	{
				
	
				// Connect to server and select databse.
				
				echo"<br>";
				echo "Fetching Patient ID details......";
				
				$result1 = $con->query("SELECT * FROM usuarios where Identif='".$patientID."'");
				$count1 = $result1->rowCount();
				/*echo "<br>";
				echo "query: ".$result1;
				echo "<br>";
				echo "count: ".$count1;*/
				$i=-1;
				if ($count1>1){
					
				}else if ($count1==0){					
					$i=0;					
				}else if ($count1==1){
					$row=$result1->fetch(PDO::FETCH_ASSOC);
					//$i=$row['Identif'];
					return $row;
				}
		       
			    return $i;
			   
	}	
	
	function getReportType($con, $type)	{					
				// Connect to server and select databse.
				
				echo"<br>";
				echo "Fetching Report Type......";
						
				//$query="SELECT * FROM tipopin where NombreEng='$type'";
				$query="SELECT * FROM tipopin where abreviation='$type'";
				$result1 = $con->query($query);
				$count1 = $result1->rowCount();
				/*echo "<br>";
				echo "query: ".$query;
				echo "<br>";
				echo "count: ".$count1;*/
				$i=-1;
				if ($count1>1){
					
				}else if ($count1==0){					
					$i=0;					
				}else if ($count1==1){
					$row = $result1->fetch(PDO::FETCH_ASSOC);
					$i=$row['Id'];
				}
		       
			    return $i;
			   
	}	
		
####changes from cloud channel---Ends

?>
